<?php

/**
* Classe de real-time e sincronismo MyOmni
* Autor: Alisson Pelizaro (alissonpelizaro@hotmail.com)
*/

class Sincronismo extends Msg {

  public  $access;
  private $config;
  private $util;
  private $db;
  private $db2;

  public function __construct(){
    $this->access = new Ambiente;
    $this->util = new Util;
    ini_set ('memory_limit' ,  '512M');
    clearstatcache();
  }

  public function proccess($servico){

    $this->db = $this->connection();
    $this->db2 = new Database;
    $this->config = $this->carregaConfig();
    $this->util = new Util;

    switch ($servico) {

      case "whatsapp":
      //Sincronismo do chat WhatsApp
      $this->whatsappSinc();
      break;

      case "telegram":
      //Sincronismo do chat Telegram
      $this->telegramSinc();
      break;

      case "enterness":
      //Sincronismo do chat Enterness
      $this->enternessSinc();
      break;

      case "-all":
      //Sincronismo de todas as midias
      $this->whatsappSinc();
      $this->enternessSinc();
      //$this->telegramSinc();
      break;

      case "-test":
      //Testa sincronismos
      $this->checaApiWhatsApp();
      $this->checaApiEnterness();
      die("\n--------------\nTestes finalizados");
      break;

    }

    echo ".";

    $this->db = null;
    $this->util = null;
    $this->config = null;

  }

  public function checaParked(){
    $parked = $this->db2->getQuery('park', array('intenc' => array("!=", "")));
    $filas = $this->retFilas(true, true);

    foreach ($parked as $pk) {
      if($filas){
        foreach ($filas as $fl) {
          if($fl['nomeFila'] == $pk['intenc']){

            $damRet = $this->dam($pk['intenc'], false, $pk['rmt'], $pk['plataforma']);
            $agente = $damRet->id;

            if($agente){
              $texto = "Olá, já temos alguém disponível para lhe atender no setor *".$pk['intenc']."*. Estamos transferindo seu contato para o agente disponível.";
              $msg = new Msg();
              $msg->setPlataforma($pk['plataforma']);
              $msg->setMsg($texto);
              $msg->setDst($pk['rmt']);
              $msg->sendMessage();
              $protocolo = $this->geraProtocolo($pk['intenc']);

              $dados = array(
                'origem' => 'externo',
                'dataInicio' => date("Y-m-d H:i:s"),
                'status' => 0,
                'remetente' => $pk['rmt'],
                'idAgente' => $agente,
                'plataforma' => $pk['plataforma'],
                'fila' => $pk['intenc'],
                'nome' => $pk['nome'],
                'protocolo' => $protocolo,
                'pendente' => 0
              );

              $this->db2->insQuery('atendimento', $dados);

              unset($dados['fila']);
              $at = $this->db2->getQuery('atendimento', $dados, array('idAtendimento'));

              if($at[0]){
                $at = $at[0]['idAtendimento'];
              } else {
                echo "Erro ao linkar um Agente com o Cliente estacionado (".$pk['rmt'].")";
                return false;
              }

              $prevMsgs = $this->unsetPark($pk['rmt'], $pk['plataforma']);
              //Se tem mensagens do cliente antes da URA, grava
              if($prevMsgs){
                $this->gravaPrevMsg($at, $prevMsgs);
              }

              $retAgent = "Atendimento iniciado: <b><i class='expi'>".$pk['intenc']."</i> (" . date('d/m/Y H:i:s').").</b>.";
              $retClient = "Seu atendimento foi iniciado.";

              if($protocolo){
                $retAgent .= " Protocolo: <b>$protocolo</b>";
                $retClient .= " O número do seu protocolo é: *". $protocolo ."*.";
              }

              $dados = array(
                'idAtendimento' => $at,
                'chat' => $retAgent,
                'rmt' => 'cliente',
                'visualizada' => 0,
                'dataEnvio' => date("Y-m-d H:i:s")
              );

              $this->db2->insQuery('chat_atendimento', $dados);


              $msg = new Msg();
              $msg->setPlataforma($pk['plataforma']);
              $msg->setMsg($retClient);
              $msg->setDst($pk['rmt']);
              $msg->sendMessage();

              $this->enviarOla($pk['rmt'], $pk['plataforma'], $agente);

              return true;
            }

          }
        }
      }
    }

    return false;

  }

  private function enviarOla($rmt, $plataforma, $id = false){
    $comp = "";
    if($id){
      $nomeAgente = $this->db2->getQuery('user', array('idUser' => $id), array('nome'));
      if($nomeAgente[0]){
        $comp = ", meu nome é ".$nomeAgente[0]['nome'];
      }
    }
    $saudacao = "Olá".$comp.". Como posso ajudá-lo (a)?";

    $msg = new Msg();
    $msg->setPlataforma($plataforma);
    $msg->setMsg($saudacao);
    $msg->setDst($rmt);
    $msg->sendMessage();

    return true;

  }

  public function xcontact($ipxc, $ipen){
    echo "Integrando Xcontact ($ipxc - $ipen)\n\r";

    //Sincroniza filas
    $this->setupFilas($this->curlGetFilas($ipxc));

    /* Necessário loop para sinc agentes, caso o
    * cliente tenha mais de 100 agentes ativos
    */
    $pg = 0;
    $agentes = array();
    do{
      $pg++;
      $res = $this->curlGetAgentes($ipxc, $pg);
      $agentes = $this->montaArrayAgentes($res, $agentes);
    }while($pg < $res->pages);

    //Sincroniza Agentes pela API
    $this->setupAgentes($agentes);

  }

  public function checaXcontact($ip){
    //Curl para consumo da API de FILAS do Xcontact
    $url = "http://$ip:8001/api/v2/agendas?max=1&page=1";
    $header = array('Authorization: Bearer 1e5a475b7421c20cf0edb60543b20d406a2e55fd2619f5f04ee10dbdc41a007b');

    $cr = curl_init();
    curl_setopt($cr, CURLOPT_URL, $url);
    curl_setopt($cr, CURLOPT_POST, false);
    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cr, CURLOPT_HTTPHEADER, $header);

    curl_exec($cr);

    if(curl_errno($cr)){
      curl_close($cr);
      $this->desabilitaIntegracao();
      return false;
    } else {
      curl_close($cr);
      return true;
    }
  }

  private function checaApiWhatsApp(){
    //APENAS PARA DEBUG : Função de teste da API WhatsApp
    echo "\nChecando status da API do WhatsApp...\n";
    echo $this->curlExecWhatsApp();
  }

  private function checaApiEnterness(){
    //APENAS PARA DEBUG : Função de teste da API WhatsApp
    echo "\nChecando status da API do Orquestador Enterness...\n";
    echo $this->curlExecEnterness();
  }




  /*
  * Função que desloga agentes inativos e transborda
  * atendimentos se necessário
  */
  public function checaSessoes(){

    $this->config = $this->carregaConfig();

    //Desloga usuários que fecharam o browser
    $timed = date('Y-m-d H:i:s', strtotime('-2 minute'));
    $sql = "UPDATE `user` SET `logged` = 0 WHERE `sessionTime` < '$timed'";
    $this->db->query($sql);

    $sql = "SELECT `idAtendimento`, `idAgente`, `dataInicio`, `remetente`, `plataforma`, `fila` FROM `atendimento` WHERE `status` = 0";
    $ats = $this->db->query($sql);
    $ats = $ats->fetchAll();

    //Verifica a necessidade de transbordo de acordo com as configurações
    /*
    * Auto-transferir:
    * 0 = Nunca auto-transferir
    * 1 = Caso o agente saia do sistema
    * 2 = Caso o agente fique mais de 15 min sem responder o cliente
    * 3 = Caso o agente fique mais de 30 min sem responder o cliente
    * 4 = Caso o agente fique mais de 1 hora sem responder o cliente
    * 5 = Caso o agente fique mais de 2 horas sem responder o cliente
    */

    if($this->config->prioridade == 1){

      $agtsOn = $this->getAgentesOnline();

      foreach ($ats as $at) {
        if(!in_array($at['idAgente'], $agtsOn)){
          $this->transbordaAtendimento($at);
        }
      }

    } else {

      if($this->config->prioridade == 2){
        $timeOut = 15 * 60;
      } else if($this->config->prioridade == 3){
        $timeOut = 30 * 60;
      } else if($this->config->prioridade == 4){
        $timeOut = 60 * 60;
      } else if($this->config->prioridade == 5){
        $timeOut = 120 * 60;
      } else {
        return false;
      }

      foreach ($ats as $at) {
        $sql = "SELECT `dataEnvio`, `rmt` FROM `chat_atendimento` WHERE `idAtendimento` = '".$at['idAtendimento']."' ORDER BY `dataEnvio` DESC LIMIT 1";
        $chat = $this->db->query($sql);
        $chat = $chat->fetch();

        if($chat && $chat['rmt'] == 'cliente'){
          $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($chat['dataEnvio']);
          if($diff > $timeOut){
            $this->transbordaAtendimento($at);
          }
        }
      }

    }

    if($this->config->transb){

      $timeOut = $this->config->transb * 60;

      foreach ($ats as $at) {
        $sql = "SELECT count(`idChatAtendimento`) AS `tt`
        FROM `chat_atendimento`
        WHERE  `rmt` = 'agente'
        AND `idAtendimento` = '".$at['idAtendimento']."'";
        $chat = $this->db->query($sql);
        $chat = $chat->fetch();

        if($chat['tt'] == 0){
          $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($at['dataInicio']);
          if($diff > $timeOut){
            $this->transbordaAtendimento($at);
          }
        }
      }
    }

    return true;

  }

  private function transbordaAtendimento($at){
    //$damRet = $this->dam($at['fila'], false, $at['remetente'], $at['plataforma']);
    $agente = $this->sortAtend($at['fila'], $at['plataforma'], $at['idAgente']);

    if($agente){

      $idAt = $at['idAtendimento'];
      $sql = "UPDATE `atendimento` SET `idAgente` = '$agente' WHERE `idAtendimento` = '$idAt'";
      $this->db->query($sql);
      //transbordaAtendimento($at, $agente);

      $nomeAntigo = $this->getNomeUser($at['idAgente'], true);
      $nomeNovo = $this->getNomeUser($agente, true);

      $this->gravaLog('Transbordou atendimento', $at['idAgente'], $at['idAtendimento'], "Para: ".$nomeNovo);
      $this->gravaLog('Recebeu transbordo', $agente, $at['idAtendimento'], "De: " . $nomeAntigo);

      $msg = "<i><b>INFO: </b>Atendimento transbordou de <b>". $nomeAntigo."</b> para <b>". $nomeNovo."</b>.</i>";
      $this->carregaMsg($at['idAtendimento'], $msg, 'agente', date("Y-m-d H:i:s"));

      return true;
    }

    return false;

  }

  private function getNomeUser($idUser, $last = false){

    $sql = "SELECT `nome`, `sobrenome` FROM `user` WHERE `idUser` = '$idUser'";
    $user = $this->db->query($sql);
    $user = $user->fetch();

    if($user){
      if($last){
        return $user['nome'] . " " . $user['sobrenome'];
      }
      return $user['nome'];
    }

    return false;
  }

  /* Retorna array com o id dos agentes online */
  private function getAgentesOnline(){
    $sql = "SELECT `idUser` FROM `user` WHERE `status` = 1 AND `tipo` = 'agente'";
    $agts = $this->db->uery($sql);
    $agts = $agts->fetchAll();

    $agtsOn = array();

    foreach ($agts as $agt) {
      array_push($agtsOn, $agt['idUser']);
    }

    return $agtsOn;
  }

  private function sortAtend($fila, $plataforma, $id){
    $users = $this->getAgents($fila, $plataforma, $id);

    if(!count($users)){
      return false;
    }
    return $users[rand(0,(count($users)-1))]['idUser'];

  }

  // DAM - Distribuidor automatico de mensagens
  private function dam($fila = false, $transbordo = false, $cliente = false, $plataforma){

    // DEBUG: echo $fila . ">>" . $plataforma;

    $users = $this->getAgents($fila, $plataforma);

    $tt = count($users);

    if($tt == 1){
      //Somente um agente disponivel, passa pra ele
      $id = $users[0]['idUser'];
    } else if($tt == 0) {
      // Nenhum agente disponível
      if(!$fila){
        $id = false;
      } else if(!$transbordo){
        //Checa se essa fila possui transbordo
        $sql = "SELECT `transbordo` FROM `fila` WHERE `nomeFila` = '$fila' AND `status` = '1'";
        $trans = $this->db->query($sql);
        $trans = $trans->fetchAll();
        if(count($trans) == 0 || $trans[0]['transbordo'] == ""){
          $id = false;
        } else {
          //Função resursiva para encontrar agente disponivel na fila de transbordo
          $id = $this->dam($trans[0]['transbordo'], true, $cliente, $plataforma);
          $id = $id->id;
          if($id){
            $transbordo = $trans[0]['transbordo'];
          }
        }
      } else {
        $id = false;
      }
    } else {

      /*
      * Prioridades:
      * 0 = Qualquer agente disponivel na fila
      * 1 = O agente a mais tempo sem atender
      * 2 = O agente com menos atendimentos no dia
      * 3 = O agente com mais atendimentos no dia
      * 4 = O agente com menos atendimentos ativos
      * 5 = O agente com mais atendimentos ativos
      * 6 = O agente que efetuou atendimentos anteriores ao cliente
      */

      if($this->config->prioridade == 0){
        //Qualquer agente disponivel na fila

        $k = rand(0,$tt-1);
        $id = $users[$k]['idUser'];

      } else if($this->config->prioridade == 1){
        //O agente a mais tempo sem atender
        $id = $this->retAgenteMaisOcioso($users);

      } else if($this->config->prioridade == 2){
        //O agente com menos atendimentos no dia
        $id = $this->retAgenteMenosAtendimento($users);

      } else if($this->config->prioridade == 3){
        //O agente com mais atendimentos no dia
        $id = $this->retAgenteMaisAtendimento($users);

      } else if($this->config->prioridade == 4){
        //O agente com menos atendimentos ativos
        $id = $this->retAgenteMenosAtendimentoAtivo($users);

      } else if($this->config->prioridade == 5){
        //O agente com mais atendimentos ativos
        $id = $this->retAgenteMaisAtendimentoAtivo($users);

      } else if($this->config->prioridade == 6){
        //O agente que efetuou atendimentos anteriores ao cliente
        $id = $this->retAgenteAntendimentoAnterior($users, $cliente);

      }

      if(!$id){
        //Nunca atenderam esse cliente, sorteia alguém disponivel
        $k = rand(0, $tt-1);
        $id = $users[$k]['idUser'];
      }
    }
    //echo "retorno: $id / $transbordo.";
    return (object) array(
      'id' => $id,
      'transbordo' => $transbordo
    );

  }

  /*
  * Função que recebe um ARRAY contendo o ID dos agentes e o nome do cliente
  * e retorna o ID do agente que efetuou o ultimo atendimento com o cliente.
  *
  * $users = array('idUser' => 0);
  */
  private function retAgenteAntendimentoAnterior($users, $cliente){

    $id = false;

    $sql = "SELECT `idAgente` FROM `atendimento` WHERE `remetente` = '$cliente'";
    $ats = $this->db->query($sql);
    $ats = $ats->fetchAll();

    foreach ($ats as $at) {
      foreach ($users as $usr) {
        if($at['idAgente'] == $usr['idUser']){
          $id = $usr['idUser'];
        }
      }
    }

    return $id;

  }

  /*
  * Função que recebe um ARRAY contendo o ID dos agentes e retorna o
  * ID do agente que tem mais atendimentos ativos
  *
  * $users = array('idUser' => 0);
  */
  private function retAgenteMaisAtendimentoAtivo($users){
    $idAtual = false;
    $qtdAtual = 0;

    foreach ($users as $user) {

      $dados = (object) array(
        'idUser' => $user['idUser'],
        'status' => 0,
        'pendente' => 0
      );

      $ttUser = $this->retQtdAtendimento($dados);

      if(!$idAtual || $qtdAtual < $ttUser){
        $idAtual = $user['idUser'];
        $qtdAtual = $ttUser;
      }
    }

    return $idAtual;
  }

  /*
  * Função que recebe um ARRAY contendo o ID dos agentes e retorna o
  * ID do agente que tem menos atendimentos ativos
  *
  * $users = array('idUser' => 0);
  */
  private function retAgenteMenosAtendimentoAtivo($users){
    $idAtual = false;
    $qtdAtual = 0;

    //print_r($users);

    foreach ($users as $user) {

      $dados = (object) array(
        'idUser' => $user['idUser'],
        'status' => 0,
        'pendente' => 0
      );

      $ttUser = $this->retQtdAtendimento($dados);
      // DEBUG: echo "\n>>>>".$ttUser."<<<<\n";

      if(!$idAtual || $qtdAtual >= $ttUser){
        $idAtual = $user['idUser'];
        $qtdAtual = $ttUser;
      }
    }

    return $idAtual;

  }

  /*
  * Função que recebe um ARRAY contendo o ID dos agentes e retorna o
  * ID do agente que tem mais atendimentos no dia
  *
  * $users = array('idUser' => 0);
  */
  private function retAgenteMaisAtendimento($users){

    $idAtual = false;
    $qtdAtual = 0;
    $data = date("Y-m-d");

    foreach ($users as $user) {

      $dados = (object) array(
        'idUser' => $user['idUser'],
        'dataIni' => $data." 00:00:00",
        'status' => 1
      );

      $ttUser = $this->retQtdAtendimento($dados);

      if(!$idAtual || $qtdAtual < $ttUser){
        $idAtual = $user['idUser'];
        $qtdAtual = $ttUser;
      }
    }

    return $idAtual;

  }

  /*
  * Função que recebe um ARRAY contendo o ID dos agentes e retorna o
  * ID do agente que tem menos atendimentos no dia
  *
  * $users = array('idUser' => 0);
  */
  private function retAgenteMenosAtendimento($users){

    $idAtual = false;
    $qtdAtual = 0;
    $data = date("Y-m-d");

    foreach ($users as $user) {

      $dados = (object) array(
        'idUser' => $user['idUser'],
        'dataIni' => $data." 00:00:00",
        'status' => 1
      );

      $ttUser = $this->retQtdAtendimento($dados);

      if(!$idAtual || $qtdAtual >= $ttUser){
        $idAtual = $user['idUser'];
        $qtdAtual = $ttUser;
      }

    }

    return $idAtual;

  }

  /*
  * Função que recebe um ARRAY contendo o ID dos agentes e retorna o
  * ID do agente que está a mais tempo sem atender
  *
  * $users = array('idUser' => 0);
  */
  private function retAgenteMaisOcioso($users){
    //Monta Array com IDS dos agentes sem atendimento
    $ids = array();
    $aux = 0;
    foreach ($users as $user) {
      $dados = (object) array(
        'idUser' => $user['idUser'],
        'status' => 0
      );
      if($this->retQtdAtendimento($dados) == 0){
        $ids[$aux] = $user['idUser'];
        $aux++;
      }
    }

    if(count($ids) == 0){
      //Nenhum atendente ocioso, sorteia entre eles
      $k = rand(0,$tt-1);
      return $users[$k]['idUser'];

    } else if(count($ids) == 1){
      //Um unico agente ocioso, passa pra ele
      return $ids[0];

    }
    //Calcula quem está a mais tempo sem atender

    $idAtual = $ids[0];
    $dataAtual = false;

    foreach ($ids as $k) {

      $dataAgente = $this->retUltimoAtendimento($k);

      if(!$dataAtual || !$dataAgente || date("Y-m-d H:i:s", strtotime($dataAtual)) >= date("Y-m-d H:i:s", strtotime($dataAgente))){
        $idAtual = $k;
        $dataAtual = $dataAgente;
      }

    }

    return $idAtual;

  }

  private function retUltimoAtendimento($idUser){
    $sql = "SELECT MAX(`dataFim`) AS `last` FROM `atendimento`
    WHERE `idAgente` = '$idUser'";

    $last = $this->db->query($sql);
    $last = $last->fetch();

    if($last['last'] != "" && $last['last'] != NULL){
      return $last['last'];
    }
    return false;
  }

  private function retQtdAtendimento($dados){
    $idAgente = $dados->idUser;

    $sql = "SELECT count(`idAtendimento`) AS `tt` FROM `atendimento`
    WHERE `idAgente` = '$idAgente'";

    if(isset($dados->dataIni)){
      $sql .= " AND `dataInicio` >= '$dados->dataIni'";
    }

    if(isset($dados->dataFim)){
      $sql .= " AND `dataFim` <= '$dados->dataFim'";
    }

    if(isset($dados->status)){
      $sql .= " AND `status` = '$dados->status'";
    }

    if(isset($dados->pendente)){
      $sql .= " AND `pendente` = '$dados->pendente'";
    }

    if(isset($dados->plataforma)){
      $sql .= " AND `plataforma` = '$dados->plataforma'";
    }

    if(isset($dados->fila)){
      $sql .= " AND `fila` = '$dados->fila'";
    }

    // DEBUG: echo $sql;

    $tt = $this->db->query($sql);
    $tt = $tt->fetch();

    return $tt['tt'];
  }

  /* Retorna agentes disponiveis para atender, levando em consideração
  a fila, plataforma e quantidade de atendimentos permitidos */
  private function getAgents($fila = false, $plataforma = false, $agenteAtual = false){

    $sql = "SELECT `idUser`, `qtdAt` FROM `user` WHERE
    (`tipo` = 'agente' OR `tipo` = 'supervisor')
    AND `status` = '1'
    AND `logged` = '1'
    AND `pausa` != '1'";

    if($fila){
      $sql .= " AND `filas` LIKE '%$fila%'";
    }

    if($plataforma){
      $sql .= " AND `midias` LIKE '%".$plataforma."%'";
    }

    if($agenteAtual){
      $sql .= " AND `idUser` != '$agenteAtual'";
    }

    $users = $this->db->query($sql);
    $users = $users->fetchAll();

    //Carrega atendimentos abertos
    $sql = "SELECT `idAtendimento`, `idAgente` FROM `atendimento` WHERE `status` = 0 AND `pendente` != 1";
    $ats = $this->db->query($sql);
    $ats = $ats->fetchAll();

    /*Compara e elimina agentes que estão
    *atendendo o máximo permitido de clientes
    */
    $indx = 0;
    $rmvs = array();
    foreach ($users as $usr) {
      $qtd = 0;
      foreach ($ats as $at) {
        if($at['idAgente'] == $usr['idUser']){
          $qtd++;
        }
      }
      if($usr['qtdAt'] && $qtd >= $usr['qtdAt']){
        $rmvs[count($rmvs)] = $indx;
      }
      $indx++;
    }

    //Tira do array os usuários com mais atendimentos que o permitido
    if(count($rmvs) > 0){
      for ($i=0; $i < count($rmvs); $i++) {
        unset($users[$rmvs[$i]]);
      }
    }

    return $users;
  }

  public function telegramSinc(){
    $response = $this->curlExecTelegramApp();
    if(file_exists('telegram.bin')){
      $arch = fopen('telegram.bin', 'r');
      $index = tratarString(fgets($arch, 1024));
      fclose($arch);
    } else {
      $index = -1;
    }

    $loop = true;
    while($loop){
      $index++;
      if(isset($response->result[$index])){
        if(isset($response->result[$index]->message->text)){
          $idMsg = $response->result[$index]->message->from->id;
          $msg = $response->result[$index]->message->text;
          $data = date('Y-m-d H:i:s');
          $at = $this->setaAtendimento($idMsg, 'telegram', $msg);

          if(isset($at['retFirst'])){
            $msg = $at['retFirst'];
            $at = $at['atendimento'];
          }

          if($at){ // Se tem atendimento setado...

            //Grava a mensagem recebida no chat

            if($this->carregaMsg($at, $msg, "cliente", $data)){
              //Salva em arquivo o index da ultima mensagem cadastrada
              $arch = fopen('telegram.bin', "w+");
              fwrite($arch, $index);
              fclose($arch);
              return true;
            } else {
              echo "Erro ao gravar uma mensagem\n\r";
              // BUG: Erro ao gravar a mensagem
              return false;
            }
          } else {
            //echo "Atendimento não setado\n\r";
            // BUG: Atendimento não setado
            return false;
          }
        }
      } else {
        $loop = false;
      }
    }
  }

  private function montaArrayAgentes($res, $agentes){

    //Cria índice a partir do último agente
    $aux = count($agentes);

    foreach ($res->dados as $ag) {
      //Cria String como nome das filas que o agente pertence (explode -#-)
      $filas = "-#-";
      foreach ($ag->filas as $fl) {
        $filas .= $fl->fila."-#-";
      }
      $agentes[$aux] = array(
        'id' => $ag->id,
        'nome' => $ag->nome,
        'ramal' => $ag->ramal,
        'filas' => $filas
      );
      $aux++;
    }

    return $agentes;
  }

  private function setupAgentes($ar){

    /* LEGENDA:
    * $ar = Agentes Recebidos
    * $ae = Agentes Existentes
    */

    //Cria array de agentes existente no banco
    $ae = $this->retAgentes();

    if(count($ae) == 0){
      //Nenhum agente existente no banco... grava todos que vierem da API
      foreach ($ar as $ag) {
        $this->gravaAgente($ag['id'], $ag['nome'], $ag['ramal'], $ag['filas']);
      }

    } else if(count($ar) == 0){
      //Nenhum agente recebido pelo Xcontact... apaga os agentes do banco
      $this->apagaAgente('all');

    } else {
      //Compara os agentes recebidos com as existentes no banco

      //Grava agentes recebidos que não existem no banco
      foreach ($ar as $r) {
        $ex = false;
        foreach ($ae as $e) {
          if($r['id'] == $e['id'] && $r['nome'] == $e['nome'] && $r['ramal'] == $e['ramal'] && $r['filas'] == $e['filas']){
            $ex = true;
          }
        }
        if(!$ex){
          $this->gravaAgente($r['id'], $r['nome'], $r['ramal'], $r['filas']);
        }
      }

      //Limpa agentes que existem no banco mas que não foram recebidas
      foreach ($ae as $e) {
        $ex = false;
        foreach ($ar as $r) {
          if($r['id'] == $e['id'] && $r['nome'] == $e['nome'] && $r['ramal'] == $e['ramal'] && $r['filas'] == $e['filas']){
            $ex = true;
          }
        }
        if(!$ex){
          $this->apagaAgente($e['id']);
        }
      }
    }
  }

  private function setupFilas($res){

    /* LEGENDA:
    * $fr = Filas Recebidas
    * $fe = Filas Existentes
    */

    //Cria ARRAY das filas recebidas na API
    $fr = array();
    $ind = 0;
    foreach ($res->dados as $fila) {
      $fr[$ind] = array(
        'id' => $fila->id,
        'nome' => $fila->name
      );
      $ind++;
    }

    // DEBUG: print_r($fr);

    //Cria array das filas já existentes
    $fe = $this->retFilas();

    if(count($fe) == 0){
      //Nenhuma fila existente no banco... grava tudo o que vier
      foreach ($fr as $fila) {
        $this->gravaFila($fila['id'], $fila['nome']);
      }

    } else if(count($fr) == 0){
      //Nenhum fila recebida pelo Xcontact... apaga as filas do banco
      $this->apagaFila('all');

    } else {
      //Compara as filas recebidas com as existente no banco

      //Grava filas recebidas que não existe no DB
      foreach ($fr as $r) {
        $ex = false;
        foreach ($fe as $e) {
          if($r['id'] == $e['id'] && $r['nome'] == $e['nome']){
            $ex = true;
          }
        }
        if(!$ex){
          $this->gravaFila($r['id'], $r['nome']);
        }
      }

      //Limpa filas que existem no BD mas não não foram recebidas
      foreach ($fe as $e) {
        $ex = false;
        foreach ($fr as $r) {
          if($r['id'] == $e['id'] && $r['nome'] == $e['nome']){
            $ex = true;
          }
        }
        if(!$ex){
          $this->apagaFila($e['id']);
        }
      }
    }
  }

  private function gravaFila($id, $nome){
    $sql = "INSERT INTO `int_fila` (`id`, `nome`, `status`, `fantasia`) VALUES ('$id', '$nome', 1, '$nome')";
    $this->db->query($sql);
  }

  private function apagaFila($id){
    if($id == 'all'){
      $sql = "DELETE FROM `int_fila`";
    } else {
      $sql = "DELETE FROM `int_fila` WHERE `id` = '$id'";
    }
    $this->db->query($sql);
  }

  private function retFilas($filtro = false, $configIgnore = false){
    if(!$filtro){
      $filtro = "";
    } else {
      $filtro = "WHERE `status` = 1";
    }
    //Retorna as filas existentes no banco
    $sql = "SELECT `idFila`, `nomeFila` FROM `fila` $filtro ORDER BY `priority`";
    $filas = $this->db->query($sql);
    $filas = $filas->fetchAll();

    if(!$configIgnore && $this->config->exibirFilas == 'todas'){
      return $filas;
    }

    $novoArray = array();
    $i = 0;

    foreach ($filas as $fila) {
      $nomeFila = $fila["nomeFila"];
      //$data = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")."-20 minutes"));

      $sql = "SELECT count(`idUser`) AS `total` FROM `user` WHERE
      `logged` = '1' AND
      `pausa` != '1' AND
      `filas` LIKE '%-$nomeFila-%' AND
      `midias` LIKE '%-whatsapp-%'";
      $users = $this->db->query($sql);
      $users = $users->fetch();

      if($users["total"] > 0){
        $novoArray[$i] = $fila;
        $i++;
      }
    }

    if(count($novoArray) == 0){
      return false;
    }
    return $novoArray;
  }

  private function gravaAgente($id, $nome, $ramal, $filas){
    $sql = "INSERT INTO `int_agente` (`id`, `nome`, `ramal`, `filas`) VALUES ('$id', '$nome', '$ramal', '$filas')";
    $this->db->query($sql);

    $sql = "UPDATE `user` SET `filas` = '$filas' WHERE `ramal` = '$ramal'";
    $this->db->query($sql);
  }

  private function apagaAgente($id){
    if($id == 'all'){
      $sql = "DELETE FROM `int_agente`";
    } else {
      $sql = "DELETE FROM `int_agente` WHERE `id` = '$id'";
    }
    $this->db->query($sql);
  }

  private function retAgentes(){
    //Retorna os agentes existentes no banco
    $sql = "SELECT `id`, `nome`, `ramal`,`filas` FROM `int_agente` ORDER BY `nome`";
    $agentes = $this->db->query($sql);
    return $agentes->fetchAll();
  }

  private function curlGetAgentes($ip, $pg = 1){
    //Curl para consumo da API de FILAS do Xcontact
    $url = "http://$ip:8001/api/v2/agentes?max=100&page=$pg";
    $header = array('Authorization: Bearer 1e5a475b7421c20cf0edb60543b20d406a2e55fd2619f5f04ee10dbdc41a007b');

    $cr = curl_init();
    curl_setopt($cr, CURLOPT_URL, $url);
    curl_setopt($cr, CURLOPT_POST, false);
    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cr, CURLOPT_HTTPHEADER, $header);

    $response = curl_exec($cr);
    $response = json_decode($response);
    curl_close($cr);
    return $response;
  }

  private function curlGetFilas($ip, $pg = 1){
    //Curl para consumo da API de FILAS do Xcontact
    $url = "http://$ip:8001/api/v2/filas?max=100&page=$pg";
    $header = array('Authorization: Bearer 1e5a475b7421c20cf0edb60543b20d406a2e55fd2619f5f04ee10dbdc41a007b');

    $cr = curl_init();
    curl_setopt($cr, CURLOPT_URL, $url);
    curl_setopt($cr, CURLOPT_POST, false);
    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cr, CURLOPT_HTTPHEADER, $header);

    $response = curl_exec($cr);
    $response = json_decode($response);
    curl_close($cr);
    return $response;
  }

  private function enternessSinc(){

    //Consome orquestrador ENTERness
    $response = $this->curlExecEnterness();

    // DEBUG: print_r($response);

    if(is_array($response)){
      foreach ($response as $msg) {
        $dados = $msg->dados;
        $msg->horario = date("Y-m-d H:i:s");
        if(strpos($dados, "Piloto##->")){
          //Primeira chamada via chat, cria um atendimento
          $dados = explode('##->', $dados);
          $fila = $dados[1];
          $dados = explode("-#-#-", $dados[2]);
          $nome = $dados[0];
          $tel = $dados[1];
          $email = $dados[2];

          $at = $this->setaAtendimento($msg->token, 'enterness', false, $fila, $nome);

          $dados = array(
            'nome' => $nome,
            'fone' => $tel,
            'email' => $email,
            'cpf' => NULL,
            'foto' => NULL
          );

          $this->checaCadastroCliente($at['atendimento'], $dados);


        } else {
          $at = $this->setaAtendimento($msg->token, 'enterness', false);
        }

        if(isset($at['retFirst'])){
          $msg->dados = $at['retFirst'];
          $at = $at['atendimento'];
        }

        if($at){ // Se tem atendimento setado...

          //Grava a mensagem recebida no chat
          if($this->carregaMsg($at, $msg->dados, "cliente", $msg->horario)){
            //Mensagem gravada
          } else {
            echo "Erro ao gravar a mensagem\n\r";
            // BUG: Erro ao gravar a mensagem
          }
          unset($at);

        } else {
          //echo "Atendimento não setado\n\r";
          // BUG: Atendimento não setado
        }
      }
    }
  }

  private function preparaDadosWhatsapp($msg){

    if($msg->isMedia && $msg->mediaData->type == 'image'){
      //É midia (foto, gif, audio ou video)
      $msg->body = $this->baixaMediaWhatsApp(
        $msg->id,
        $msg->mediaData->type,
        $msg->mediaData->mimetype
      );

    } else if($msg->isMedia && $msg->mediaData->type == 'video'){
      //É midia (foto, gif, audio ou video)
      $msg->body = $this->baixaMediaWhatsApp(
        $msg->id,
        $msg->mediaData->type,
        $msg->mediaData->mimetype
      );

    } else if($msg->isMMS && $msg->mediaData->type == 'document') {
      //É documento (doc, docx ou pdf)
      $msg->body = $this->baixaMediaWhatsApp(
        $msg->id,
        $msg->mediaData->type,
        $msg->mediaData->mimetype,
        $msg->mediaData->filename
      );

    }

    return $msg;

  }

  private function whatsappSinc(){

    //Consome API do whatsapp
    $response = json_decode($this->curlExecWhatsApp());

    /*if(isset($response[0])){
    $response = $response[0]->messages;
  }*/

  if(is_array($response) && count($response) > 0){
    //Tem mensagem(ns) nova(s)
    // DEBUG: echo "Tem ".count($response)." mensagens novas no WhatsApp";

    //Inverte array para as mais antigas primeiro
    $response = array_reverse($response);

    //Inicia loop para trabalhar todas as mensagens recebidas em ordem
    foreach ($response as $msgAr) {
      $msgAr = $msgAr->messages;
      foreach ($msgAr as $msg) {
        $msg = $this->preparaDadosWhatsapp($msg);

        //Procura registro de atendimento desse remetente
        $at = $this->setaAtendimento($msg->chat->contact->id->_serialized, 'whatsapp', $msg->body, false, $msg->chat->contact->pushname);

        if(isset($at['retFirst'])){
          //print_r($msg->chat->contact->profilePicThumbObj->eurl);
          //echo "entrou";
          $msg->body = $at['retFirst'];
          $at = $at['atendimento'];

          $this->setupPhone($msg);

          $nomeCliente = $msg->chat->contact->pushname;
          $telCliente = $this->formataTelefone($msg->chat->contact->id->user, true);
          $fotoCliente = $msg->chat->contact->profilePicThumbObj->eurl;
          $namePic = md5($fotoCliente).".jpg";


          $dados = array(
            'nome' => $nomeCliente,
            'fone' => $telCliente,
            'email' => NULL,
            'cpf' => NULL,
            'foto' => $namePic
          );

          $this->checaCadastroCliente($at, $dados);
        }

        if($at){ // Se tem atendimento setado...

          if($at == 'Parked'){
            //Se está estacionado...
            // DEBUG: echo "estacionado";
          } else {

            //Mensagem de texto normal

            //Checa palavõres
            $resposta = $this->checaMensagem($msg->body, $at);

            if($resposta == true){
              $msg->body = $resposta;
              $ret = new Msg();
              $ret->setPlataforma("whatsapp");
              $ret->setMsg("Por favor, não utilize esse tipo de vocabulário.");
              $ret->setDst($msg->chat->contact->id->_serialized);
              $ret->sendMessage();
            }

            //Grava a mensagem recebida no chat

            if($msg->body && $this->carregaMsg($at, $this->util->setMessage($msg->body, 'whatsapp'), "cliente", date("Y-m-d H:i:s"))){
              //Mensagem gravada
            } else {
              if($msg->body){
                echo "Erro ao gravar a mensagem\n\r";
              }
              // BUG: Erro ao gravar a mensagem
            }
            unset($at);
          }

        } else {
          //echo "Atendimento não setado\n\r";
          // BUG: Atendimento não setado
        }
      }
    }
  } else {
    //Nenhuma mensagem nova
  }
}

private function setupPhone($msg){
  $phone = $msg->to->user;
  if($this->config->phone != $phone){
    $sql = "UPDATE `config` SET `phone` = '$phone' WHERE `idConfig` = 1";
    if($this->db->query($sql)){
      $this->config->phone = $msg->to->user;
      return true;
    }
  }
  return false;
}

private function checaCadastroCliente($at, $dados){
  $sql = "SELECT `idCliente` FROM `atendimento` WHERE `idAtendimento` = '$at'";
  $idCliente = $this->db->query($sql);
  $idCliente = $idCliente->fetch();
  if($idCliente['idCliente'] == "" || $idCliente['idCliente'] == null){
    $idCliente = $this->buscaCliente($dados);
    if(!$idCliente){
      $idCliente = $this->gravaCliente($dados);
    }
    //print_r($idCliente);
    //print_r($at);
    if($idCliente){
      $sql = "UPDATE `atendimento` SET `idCliente` = '$idCliente' WHERE `idAtendimento` = '$at'";
      $this->db->query($sql);
    }
    return $idCliente;
  }
  return false;
}

private function checaAtendimentoParalelo($rmt){
  $dados = array(
    'fone'=> $rmt,
    'email' => null
  );
  $cliente = $this->buscaCliente($dados);

  if(!$cliente){
    return false;
  }

  $sql = "SELECT `idAtendimento` FROM `atendimento` WHERE `status` = 0 AND `idCliente` = '$cliente'";
  $at = $this->db->query($sql);
  $at = $at->fetch();

  if(isset($at['idAtendimento'])){
    return $at['idAtendimento'];
  }

  return false;

}

private function buscaCliente($dados){
  if($dados['fone'] == null && $dados['email'] == null){
    return false;
  }
  $sql = "SELECT `idCliente` FROM `cliente` WHERE ";
  if($dados['email'] != null){
    $email = "`email` = '".$dados['email']."'";
  }
  if($dados['fone'] != null){
    $fone = "`fone` LIKE '%".$dados['fone']."%'";
  }

  if(isset($email) && isset($fone)){
    $sql .= $email . " OR " . $fone;
  } else if(isset($email)){
    $sql .= $email;
  } else if(isset($fone)){
    $sql .= $fone;
  }

  $sql .= " ORDER BY `dtRegistro` DESC LIMIT 1";

  // DEBUG: echo $sql;
  $cliente = $this->db->query($sql);
  $cliente = $cliente->fetch();
  if(isset($cliente['idCliente'])){
    return $cliente['idCliente'];
  }
  return false;
}

private function gravaCliente($dados){
  $data = date('Y-m-d');

  $arraytel = '[[true,"'.$dados["fone"].'"],[false,""],[false,""]]';

  /*  $sql = "INSERT INTO `cliente` (
  `nome`, `fone`, `email`, `cpf`, `foto`, `promocoes`, `empresa`, `dtRegistro`
  ) VALUES (
  '$dados->nome','$dados->fone','$dados->email','$dados->cpf','$dados->foto', '0', '0', '$data'
  )";*/

  $sql = "INSERT INTO
  `cliente`
  (`nome`, `fone`, `nascimento`, `email`,
    `cpf`, `promocoes`, `cep`, `rua`, `numero`, `complemento`,
    `bairro`, `uf`, `empresa`, `dtRegistro`, `cidade`, `status`)
    VALUES
    ('".$dados["nome"]."', '".$arraytel."', null, '".$dados["email"]."', '".$dados["cpf"]."', '0',
      '', '', null, '', '', '',
      '0', '$data', '', '');";


      if($this->db->query($sql)){
        $sql = "SELECT `idCliente` FROM `cliente` WHERE
        `nome` = '".$dados["nome"]."' AND
        `fone` = '".$arraytel."' AND
        `email` = '".$dados["email"]."' AND
        `cpf` = '".$dados["cpf"]."'";

        $idCliente = $this->db->query($sql);
        $idCliente = $idCliente->fetch();

        if(isset($idCliente['idCliente'])){
          return $idCliente['idCliente'];
        }
      }

      return false;
    }

    public function formataTelefone($num, $cel = false){

      try {
        $num = substr($num, 2);
        $ddd = substr($num, 0, 2);
        $num = substr($num, 2);
        $numL = substr($num, -4);
        $num = substr($num, 0, -4);

        if($cel){
          $num = "9".$num;
        }

        return "(".$ddd.") ".$num."-".$numL;

      } catch (\Exception $e) {
        echo $e;
        return "";
      }

    }

    private function getUrlImage($url, $saveto){

      $ch = curl_init ($url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
      $raw = curl_exec($ch);
      $err = curl_error($ch);
      curl_close ($ch);

      if($err){
        echo "Erro ao capturar a imagem do cliente -> $err";
        return false;
      }

      if($raw == 'URL signature expired'){
        return false;
      }

      if(file_exists($saveto)){
        unlink($saveto);
      }

      $fp = fopen($saveto,'x');
      fwrite($fp, $raw);
      fclose($fp);

      return true;
    }

    private function baixaMediaWhatsApp($idMsg, $type, $mimetype, $fileName = false){

      $url = $this->access->getWhatsAppServer()."messages/".$idMsg."/download";
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_PORT => $this->access->getWhatsAppPort(),
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
          "Postman-Token: 10ef7625-ecf7-4a4f-9d3a-ad1dac31f0b5",
          "auth-key: ".$this->access->getTokenWhatsApp(),
          "cache-control: no-cache",
          "client_id: ".$this->access->getUserApi()
        ),
      ));

      $response = curl_exec($curl);
      $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);

      if($type == 'image' || $type == "video"){
        $ext = explode("/", $contentType);
        if(count($ext) == 2){
          $ext = $ext[1];
        } else {
          $ext = "";
        }
      } else if($type == 'document'){

        $fileName = explode(".", $fileName);
        $tmp = count($fileName);
        if($tmp > 1){
          $ext = $fileName[$tmp-1];
        } else {
          $ext = "";
        }
      } else {
        $ext = "";
      }


      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "MEDIA METHOD -> cURL Error #:" . $err;
        return false;
      } else {

        $targetPath = '../my/assets/medias/';
        $newName = md5(round(0,50).date("Y-m-d H:i:s")).".".$ext;

        if(file_exists($targetPath.$newName)){
          return false;
        }

        $fp = fopen($targetPath.$newName,'x');
        fwrite($fp, $response);
        fclose($fp);

        //Registra a mensagem sainte no banco de dados
        if($type == 'image'){
          $return = "<span class='media-img-chat'><a href='http://".$this->access->server."/my/assets/medias/".$newName."' target='_blank'><img src='".$targetPath.$newName."'></a></span>";
        } else if($mimetype == 'video/'.$ext || $type == "video"){
          $return = "<span class='media-video-chat'>
          <video width='250' controls>
          <source src='".$targetPath.$newName."' type='video/mp4' />
          Seu navegador não suporta esse tipo de vídeo
          </video>
          </span>";
        } else if($mimetype == 'audio/mpeg'){
          $return = "<span class='media-audio-chat'>
          <audio controls>
          <source src='".$targetPath.$newName."' type='audio/mpeg' />
          Seu navegador não suporta esse tipo de audio
          </audio>
          </span>";
        } else if($type == "document"){
          $return = "<span class='media-doc-chat'>
          <a href='http://".$this->access->server."/my/assets/medias/".$newName."' target='_blank'>
          <i class='fa fa-download display-1'></i><br>
          <i>Documento anexado (".$ext.")</i>
          </a>
          </span>";
        } else {
          $return = "<i class='text-danger'>Tipo de arquivo inválido (".$ext.")</i>";
        }

        return $return;
      }

    }

    private function curlExecEnterness(){
      //Curl para consumo da API do orquestrador

      $url = "https://chat.enterness.com/int_get_message/";
      $header = array('Authorization: Bearer EuDtLW9TSKvtvrwVFZFsMq2dDcZgNIWSgXqAljr5w0oOhAHXwZ');

      $params = array(
        'auth' => $this->access->bearer
      );

      $cr = curl_init();
      curl_setopt($cr, CURLOPT_URL, $url);
      curl_setopt($cr, CURLOPT_POST, true);
      curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($cr, CURLOPT_POSTFIELDS, $params);
      curl_setopt($cr, CURLOPT_HTTPHEADER, $header);

      $response = curl_exec($cr);
      curl_close($cr);
      return json_decode($response);

    }

    private function curlExecWhatsApp(){
      /*
      //Curl para consumo da API do WhatsApp
      $header = array('Authorization: Bearer 4ccbb678-1efe-397e-a52f-42a2aa2bfea1');

      $cr = curl_init();
      curl_setopt($cr, CURLOPT_URL, $this->access->getWhatsAppUrlCheck());
      curl_setopt($cr, CURLOPT_POST, false);
      curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($cr, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($cr, CURLOPT_HTTPHEADER, $header);

      $response = curl_exec($cr);
      $response = json_decode($response);
      curl_close($cr);
      return $response;
      */

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_PORT => $this->access->getWhatsAppPort(),
        CURLOPT_URL => $this->access->getWhatsAppUrlCheck(),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
          "Postman-Token: 1a625d60-6342-46b7-8ed4-48643345e463",
          "auth-key: QjbXR1w2f0bRB55M8jrF2tBU6tpTkkAu",
          "cache-control: no-cache",
          "client_id: ".$this->access->getUserApi()
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
        return false;
      } else {
        return $response;
      }
    }

    public function curlExecTelegramApp(){
      //Curl para consumo da API do Telegram
      $header = array('Authorization: Bearer 4ccbb678-1efe-397e-a52f-42a2aa2bfea1');

      $cr = curl_init();
      curl_setopt($cr, CURLOPT_URL, $this->access->getTelegramUrlCheck());
      curl_setopt($cr, CURLOPT_POST, false);
      curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($cr, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($cr, CURLOPT_HTTPHEADER, $header);

      $response = curl_exec($cr);
      $response = json_decode($response);
      curl_close($cr);
      // DEBUG: print_r($response);

      return $response;
    }

    private function desabilitaIntegracao(){
      $sql = "UPDATE `licenca` SET `sinc` = 0 WHERE `chave` = 1";
      $this->db->query($sql);
    }

    private function setaAtendimento($rmt, $plataforma, $msgIn, $fila = false, $nome = false){

      $sql = "SELECT `idAtendimento` FROM `atendimento` WHERE `status` = 0 AND `remetente` = '$rmt' AND `plataforma` = '$plataforma'";
      $at = $this->db->query($sql);
      $at = $at->fetchAll();

      if(count($at) > 0){
        //Existe um atendimento em andamento

        //Seta o atendimento
        return $at[0]['idAtendimento'];
      } else {
        /* Não existe atendimento para esse cliente */

        /* Checa se já tem fila selecionada pelo cliente*/
        if($fila){

          $filaSelecionada = $fila;
          $filas = $this->retFilas(true);
          foreach ($filas as $fl) {
            if($fl['nomeFila'] == $filaSelecionada){
              $filaFantasia = $fl['nomeFila'];
            }
          }
          if(!isset($filaFantasia)){
            $filaFantasia = $filaSelecionada;
          }
          $retFirst = "Atendimento iniciado: <b><i>".$filaFantasia."</i> (" . date('d/m/Y H:i:s') . ")</b>";
          $semFila = true;

        } else {
          /*Não tem fila selecionada*/

          /* Checa se o atendimento está estacionado */
          //echo ">>>>". $msgIn;
          $checkPark = $this->checkPark($rmt, $plataforma, $msgIn);
          if($checkPark){
            //Está estacionado

            // DEBUG: echo "Esta estacionado";
            if(is_numeric($msgIn) && $msgIn > 0){

              //"TRUE" retorna apenas as filas ativas e ignora a configuração do DAM
              $filasDisp = $this->retFilas(true, true);
              $filasUra = json_decode($checkPark['ura']);

              $fila = false;

              if($filasDisp){
                foreach ($filasDisp as $fl) {
                  if(isset($filasUra->$msgIn) && $fl['nomeFila'] == $this->setCharset($filasUra->$msgIn)){
                    $fila = $fl['nomeFila'];
                  }
                }
              }

              //$msgIn = $msgIn-1;
              if($fila){
                $filaSelecionada = $fila;
                $prevMsgs = $this->unsetPark($rmt, $plataforma);
                $protocolo = $this->geraProtocolo($fila);

                $retFirst = "Atendimento iniciado: <b><i class='expi'>".$filaSelecionada."</i> (" . date('d/m/Y H:i:s').").</b>.";
                $retClient = "Certo! Seu atendimento foi iniciado.";

                if($protocolo){
                  $retFirst .= " Protocolo: <b>$protocolo</b>";
                  $retClient .= " O número do seu protocolo é: *". $protocolo ."*.";
                }

                $msg = new Msg();
                $msg->setPlataforma($plataforma);
                $msg->setMsg($retClient);
                $msg->setDst($rmt);
                $msg->sendMessage();

                $semFila = true;
              } else {

                if(!$filasDisp){
                  if($this->config->exibirFilas == 'todas'){
                    if(isset($filasUra->$msgIn)){

                      $msg = new Msg();
                      $msg->setPlataforma($plataforma);
                      $msg->setMsg("No momento não temos nenhum agente disponível para atendimento.\nRegistramos seu contato para o setor *".$this->setCharset($filasUra->$msgIn)."* e entraremos em contato o mais breve possível");
                      $msg->setDst($rmt);
                      $msg->sendMessage();

                      $this->setIntencaoPark($rmt, $plataforma, $this->setCharset($filasUra->$msgIn), $nome);

                    } else {

                      $msg = new Msg();
                      $msg->setPlataforma($plataforma);
                      $msg->setMsg('Opcao inválida!');
                      $msg->setDst($rmt);
                      $msg->sendMessage();
                      $filaSelecionada = "";

                    }

                  } else {

                    $msg = new Msg();
                    $msg->setPlataforma($plataforma);
                    $msg->setMsg("Não há mais ninguém disponível para atendê-lo (a). Por favor, retorne novamente em outro horário.");
                    $msg->setDst($rmt);
                    $msg->sendMessage();
                    $this->unsetPark($rmt, $plataforma);
                    $filaSelecionada = "";

                  }

                } else {
                  if(isset($filasUra->$msgIn)){
                    $filaSelecionada = $filasUra->$msgIn;
                    $semFila = true;
                    $protocolo = $this->geraProtocolo($filaSelecionada);
                    $transbordo = true;
                  } else {
                    $msg = new Msg();
                    $msg->setPlataforma($plataforma);
                    $msg->setMsg('Opcao inválida!');
                    $msg->setDst($rmt);
                    $msg->sendMessage();
                    $filaSelecionada = "";
                  }
                }

              }
            } else {
              /*
              $msg = new Msg();
              $msg->setPlataforma($plataforma);
              $msg->setMsg('Opcao inválida!');
              $msg->setDst($rmt);
              $msg->sendMessage();
              $filaSelecionada = "";
              */
            }

          } else {

            //Primeiro contato desse remetente

            // Checa se existe filas sincronizadas com x-contact
            $sinc = new Licenca;
            $sinc = $sinc->getXcontactStatus();
            if(!$sinc){
              $filas = $this->retFilas(true); //"TRUE" retorna apenas as filas ativas
              if(!$filas){
                $texto = "Infelizmente não há ninguem disponivel para atendê-lo no momento. Por favor, retorne em outra hora.";

                $msg = new Msg();
                $msg->setPlataforma($plataforma);
                $msg->setMsg($texto);
                $msg->setDst($rmt);
                $msg->sendMessage();

              } else {

                //Checa se o cliente não está em um atendimento em outra plataforma
                $rmtExp = explode("@", $rmt);
                $at = $this->checaAtendimentoParalelo($this->formataTelefone($rmtExp[0], true));

                if(!$at){

                  if($this->checaHorario()){

                    if(count($filas) > 0){
                      $texto = $this->config->saudacao."\n\nSobre qual assunto deseja falar?\nResponda com o *número* da opção desejada:\n";
                      $aux = 1;
                      $arrayJson = array();

                      foreach ($filas as $fl) {
                        $texto .= "\n*".$aux."* - ".$fl['nomeFila'];
                        $arrayJson[$aux] = $fl['nomeFila'];
                        $aux++;
                      }
                      $msg = new Msg();
                      $msg->setPlataforma($plataforma);
                      $msg->setMsg($texto);
                      $msg->setDst($rmt);
                      $msg->sendMessage();

                      $this->setPark($rmt, $plataforma, $msgIn, json_encode($arrayJson));
                      return 'Parked';
                    } else {
                      $semFila = true;
                    }
                  } else {

                    $msg = new Msg();
                    $msg->setPlataforma($plataforma);
                    $msg->setMsg($this->config->limiteResp);
                    $msg->setDst($rmt);
                    $msg->sendMessage();

                  }

                } else {
                  $this->changePlataforma($at, $rmt, 'whatsapp');
                  return $at;
                }

              }
            } else {
              $semFila = true;
            }
          }
        }

        /* -------------------------------- */

        if(isset($semFila)){
          //Não existe atendimento para esse remetente, vai criar um
          // DEBUG: echo "criando atendimento";
          $data = date('Y-m-d H:i:s');
          if(!isset($filaSelecionada)){
            $filaSelecionada = "";
          }
          //Encontra um agente disponivel conforme configurações do DAM
          //echo "chamou1";
          $filaSelecionada = html_entity_decode($filaSelecionada);
          echo $filaSelecionada . " - " . $rmt . ' - '. $plataforma;
          $damRet = $this->dam($filaSelecionada, false, $rmt, $plataforma);
          $agente = $damRet->id;

          // Se o atendimento vier do Plugin Enterness, gera protocolo:
          if($plataforma == 'enterness'){
            $protocolo = $this->geraProtocolo($filaSelecionada);
          }

          if(isset($transbordo) && $transbordo){

            $prevMsgs = $this->unsetPark($rmt, $plataforma);
            $protocolo = $this->geraProtocolo($filaSelecionada);

            $retFirst = "Atendimento iniciado (transbordo): <b><i class='expi'>".$filaSelecionada."</i> (" . date('d/m/Y H:i:s').").</b>.";
            $retClient = "Certo! Seu atendimento foi iniciado.";

            if($protocolo){
              $retFirst .= " Protocolo: <b>$protocolo</b>";
              $retClient .= " O número do seu protocolo é: *". $protocolo ."*.";
            }

            $msg = new Msg();
            $msg->setPlataforma($plataforma);
            $msg->setMsg($retClient);
            $msg->setDst($rmt);
            $msg->sendMessage();

            $semFila = true;
          }

          if($damRet->transbordo){
            $filaAt = $damRet->transbordo;
          } else {
            $filaAt = $filaSelecionada;
          }

          $sql = "INSERT INTO `atendimento` (`origem`, `dataInicio`, `status`, `remetente`, `idAgente`, `plataforma`, `fila`, `nome`, `protocolo`, `pendente`) VALUES ('externo', '$data', 0, '$rmt','$agente','$plataforma', '$filaAt', '$nome', '$protocolo', '0')";

          if($this->db->query($sql)){

            //Criou o atendimento, vai carregar ID do atendimento criado
            $sql = "SELECT `idAtendimento` FROM `atendimento` WHERE
            `status` = 0 AND
            `origem` = 'externo' AND
            `dataInicio` = '$data' AND
            `remetente` = '$rmt' AND
            `idAgente` = '$agente' AND
            `plataforma` = '$plataforma'
            ";
            $at = $this->db->query($sql);
            $at = $at->fetchAll();

            if(count($at) > 0){

              if($damRet->transbordo){
                $this->gravaLog('Transbordo de fila', $agente, $at[0]['idAtendimento'], 'Transbordou da fila '. $filaSelecionada);
              } else {
                $this->gravaLog('Recebeu atendimento', $agente, $at[0]['idAtendimento']);
              }

              //Se tem mensagens do cliente antes da URA, grava
              if(isset($prevMsgs)){
                $this->gravaPrevMsg($at[0]['idAtendimento'], $prevMsgs);
              }

              $sql = "SELECT `nome` FROM `user` WHERE `idUser` = $agente";
              //echo $sql;
              $nomeAgente = $this->db->query($sql);
              $nomeAgente = $nomeAgente->fetch();
              $nomeAgente = $nomeAgente["nome"];
              $saudacao = "Olá, meu nome é $nomeAgente. Como posso ajudá-lo (a)?";
              //$this->carregaMsg($at[0]['idAtendimento'], $saudacao, "agente", date("Y-m-d H:i:s"));

              $msg = new Msg();
              $msg->setPlataforma($plataforma);
              $msg->setMsg($saudacao);
              $msg->setDst($rmt);
              $msg->sendMessage();


              //Seta o atendimento
              if(isset($retFirst)){
                return array(
                  'retFirst' => $retFirst,
                  'atendimento' => $at[0]['idAtendimento']
                );
              } else {
                return $at[0]['idAtendimento'];
              }
            } else {
              // BUG: Erro ao encontrar o atendimento da mensagem
              return false;
            }
          } else {
            // BUG: Erro ao criar o atendimento
            return false;
          }
        }
      }
    }

    private function geraProtocolo($fila = false){
      if($fila){
        $sql = "SELECT `statusProtocolo` FROM `fila` WHERE `nomeFila` = '$fila'";
        $filaQ = $this->db->query($sql);
        $filaQ = $filaQ->fetch();
        if($filaQ && $filaQ['statusProtocolo']){
          return date('Y').date('m').date('d').date('H').date('i').date('s').rand(10, 99);
        } else {
          if(!$filaQ){
            echo "Fila '$fila' não encontrada";
          }
        }
      } else {
        return date('Y').date('m').date('d').date('H').date('i').date('s').rand(10, 99);
      }
      return 0;
    }

    private function changePlataforma($at, $cliente, $plataforma){

      $sql = "SELECT `plataforma` FROM `atendimento` WHERE `idAtendimento` = '$at'";
      $plat = $this->db->query($sql);
      $plat = $plat->fetch();

      if(isset($plat['plataforma']) && $plat['plataforma'] != $plataforma) {
        $sql = "UPDATE `atendimento` SET `plataforma` = '$plataforma', `remetente` = '$cliente' WHERE `idAtendimento` = '$at'";
        if($this->db->query($sql)){
          $this->carregaMsg($at, "<i class='text-info'>Plataforma alterada para <b>WhatsApp</b></i>", 'agente', date("Y-m-d H:i:s"));
          return true;
        }
      }

      return false;
    }

    private function contaFilas(){
      $sql = "SELECT count('idFila') AS `total` FROM `int_fila`";
      $tt = $this->db->query($sql);
      $tt = $tt->fetchAll();

      return $tt[0]['total'];
    }

    private function setPark($rmt, $plataforma, $msg = false, $ura = null){
      $p = "()";
      $data = date('Y-m-d H:i:s');
      $sql = 'INSERT INTO `park` (`rmt`, `dataPark`, `plataforma`, `msg`, `ura`)
      VALUES '.$p[0].'"'.$rmt.'", "'.$data.'", "'.$plataforma.'", "'.$msg.'", '."'".$ura."'".$p[1];
      $this->db->query($sql);
    }

    private function unsetPark($rmt, $plataforma){
      $sql = "SELECT `msg` FROM `park` WHERE `rmt` = '$rmt' AND `plataforma` = '$plataforma'";
      $park = $this->db->query($sql);
      $park = $park->fetch();

      $sql = "DELETE FROM `park` WHERE `rmt` = '$rmt' AND `plataforma` = '$plataforma'";
      $this->db->query($sql);

      if(isset($park['msg'])){
        return $park['msg'];
      }
      return false;
    }

    private function setIntencaoPark($rmt, $plataforma, $fila, $nome = false){

      $sql = 'UPDATE `park` SET
      `intenc` = "'.$fila.'",
      `dataIntenc` = "'.date('Y-m-d H:i:s').'",
      `nome` = "'.$nome.'"
      WHERE `rmt` = "'.$rmt.'" AND `plataforma` = "'.$plataforma.'"';
      // DEBUG: echo $sql;

      $this->db->query($sql);

    }

    private function checkPark($rmt, $plataforma, $msg = false){
      $sql = "SELECT `idPark`, `msg`, `ura` FROM `park` WHERE `rmt` = '$rmt' AND `plataforma` = '$plataforma'";
      $park = $this->db->query($sql);
      $park = $park->fetchAll();
      if(count($park) == 0){
        return false;
      } else {
        $idPark = $park[0]['idPark'];
        if($msg){
          $msg = $park[0]['msg']."*-#-*".$msg;
          $sql = 'UPDATE `park` SET `msg` = "'.$msg.'" WHERE `idPark` = "'.$idPark.'"';
          $this->db->query($sql);
        }
        return array(
          'idPark' => $idPark,
          'ura' => $park[0]['ura']
        );
      }
    }

    private function gravaPrevMsg($at, $msgs){
      $msgs = explode("*-#-*", $msgs);
      //$msgs = array_reverse($msgs);
      foreach ($msgs as $msg) {
        if($msg != ""){
          $this->carregaMsg($at, $msg, "cliente", date("Y-m-d H:i:s"));
        }
      }
    }

    public function checaParam($servico){
      switch ($servico) {
        case "whatsapp":
        return true;
        case "telegram":
        return true;
        case "enterness":
        return true;
        case "-all":
        return true;
        case "-test":
        return true;
        default:
        return false;
      }
    }

    private function checaMensagem($texto, $at){
      //echo $texto . $at;
      $sql = "SELECT `palavra`, `categoria` FROM `dicionario` ORDER BY CASE WHEN `palavra` LIKE '% %' THEN 1 ELSE 2 END";
      $lista = $this->db->query($sql);
      $lista = $lista->fetchAll();
      //$lista = $lista[0];
      $resposta = "";
      foreach($lista as $palavra){

        $comEspaco = strpos($palavra['palavra'], " ");

        if($comEspaco !== false){
          $pos = strpos(mb_strtolower($texto), mb_strtolower($palavra['palavra']));

          if ($pos !== false) {
            if($palavra['categoria'] == 'palavrao'){
              $asterisco = "";
              for($i = 0; $i < strlen($palavra['palavra']); $i++){
                $asterisco = $asterisco."*";
              }
              $resposta = str_replace(mb_strtolower($palavra['palavra']), $asterisco, mb_strtolower($texto));
            } else {
              $this->setNotifAtendimento($at, $palavra['palavra']);
            }
          }
        } else {
          $textoComEspaco = strpos($texto, " ");

          if($textoComEspaco !== false){
            if($resposta != ""){
              $texto = $resposta;
            }
            $resposta = "";
            $palavrasTexto = explode(" ", $texto);

            foreach($palavrasTexto as $palavraTexto){
              if (mb_strtolower($palavraTexto) == mb_strtolower($palavra['palavra'])) {
                if($palavra['categoria'] == 'palavrao'){
                  $asterisco = "";
                  for($i = 0; $i < strlen($palavra['palavra']); $i++){
                    $asterisco = $asterisco."*";
                  }
                  if($resposta == ""){
                    $resposta = $asterisco;
                  } else {
                    $resposta = $resposta." ".$asterisco;
                  }
                } else {
                  $this->setNotifAtendimento($at, $palavra['palavra']);
                }
              }
            }
          } else {
            if(mb_strtolower($palavra['palavra']) == mb_strtolower($texto)){
              if($palavra['categoria'] == 'palavrao'){
                $asterisco = "";
                for($i = 0; $i < strlen($palavra['palavra']); $i++){
                  $asterisco = $asterisco."*";
                }
                $resposta = $asterisco;
              } else {
                $this->setNotifAtendimento($at, $palavra['palavra']);
              }
            }
          }
        }
      }
      //echo "Resp: ".$resposta;
      return $resposta;
    }

    /* Carrega as configurações do MyOmni e retorna um objeto */
    private function carregaConfig(){
      $sql = "SELECT * FROM `config` WHERE `idConfig` = 1";
      if(!$this->db){
        $this->db = $this->connection();
      }
      $config = $this->db->query($sql);
      return (object) $config->fetch();
    }

    private function setNotifAtendimento($at, $palavra){

      $sql = "SELECT `restClient` FROM `atendimento` WHERE `idAtendimento` = '$at'";
      $atual = $this->db->query($sql);
      $atual = $atual->fetch();

      $atual = $atual['restClient'];

      if(strpos($atual, $palavra) === false){
        if($atual != ""){
          $atual .= ", ";
        }
        $atual .= $palavra;
      }

      $sql = "UPDATE `atendimento` SET `restClient` = '$atual', `notifRest` = '1' WHERE `idAtendimento` = '$at'";

      if($this->db->query($sql)){
        return true;
      }

      return false;
    }

    private function carregaMsg($at, $dados, $origem, $horario){

      if($dados){
        if(strpos($dados, "http") !== false && strpos($dados, 'a href') === false){
          $link = explode(" ", $dados);
          $links = array();
          foreach ($link as $k) {
            if(strpos($k, "http") !== false && strpos($k, 'a href') === false){
              $links[count($links)] = $k;
            }
          }
          foreach ($links as $k) {
            $dados = str_replace($k, "<a href='$k' target='_blank'>$k</a>", $dados);
          }
        }

        $sql = "INSERT INTO `chat_atendimento` (`idAtendimento`, `chat`, `rmt`, `visualizada`, `dataEnvio`) VALUES ('$at', ".'"'.$dados.'"'.", '$origem', 0, '$horario')";
        if($this->db->query($sql)){
          $data = date("Y-m-d H:i:s");
          $sql = "UPDATE `atendimento` SET `resposta` = '$data' WHERE `idAtendimento` = '$at'";
          $this->db->query($sql);
          return TRUE;
        }
      }
      return FALSE;
    }

    private function setCharset($str){
      $str = str_replace("u00c0", "À", $str);
      $str = str_replace("u00c1", "Á", $str);
      $str = str_replace("u00c2", "Â", $str);
      $str = str_replace("u00c3", "Ã", $str);
      $str = str_replace("u00c4", "Ä", $str);
      $str = str_replace("u00c5", "Å", $str);
      $str = str_replace("u00c6", "Æ", $str);
      $str = str_replace("u00c7", "Ç", $str);
      $str = str_replace("u00c8", "È", $str);
      $str = str_replace("u00c9", "É", $str);
      $str = str_replace("u00ca", "Ê", $str);
      $str = str_replace("u00cb", "Ë", $str);
      $str = str_replace("u00cc", "Ì", $str);
      $str = str_replace("u00cd", "Í", $str);
      $str = str_replace("u00ce", "Î", $str);
      $str = str_replace("u00cf", "Ï", $str);
      $str = str_replace("u00d1", "Ñ", $str);
      $str = str_replace("u00d2", "Ò", $str);
      $str = str_replace("u00d3", "Ó", $str);
      $str = str_replace("u00d4", "Ô", $str);
      $str = str_replace("u00d5", "Õ", $str);
      $str = str_replace("u00d6", "Ö", $str);
      $str = str_replace("u00d8", "Ø", $str);
      $str = str_replace("u00d9", "Ù", $str);
      $str = str_replace("u00da", "Ú", $str);
      $str = str_replace("u00db", "Û", $str);
      $str = str_replace("u00dc", "Ü", $str);
      $str = str_replace("u00dd", "Ý", $str);
      $str = str_replace("u00df", "ß", $str);
      $str = str_replace("u00e0", "à", $str);
      $str = str_replace("u00e1", "á", $str);
      $str = str_replace("u00e2", "â", $str);
      $str = str_replace("u00e3", "ã", $str);
      $str = str_replace("u00e4", "ä", $str);
      $str = str_replace("u00e5", "å", $str);
      $str = str_replace("u00e6", "æ", $str);
      $str = str_replace("u00e7", "ç", $str);
      $str = str_replace("u00e8", "è", $str);
      $str = str_replace("u00e9", "é", $str);
      $str = str_replace("u00ea", "ê", $str);
      $str = str_replace("u00eb", "ë", $str);
      $str = str_replace("u00ec", "ì", $str);
      $str = str_replace("u00ed", "í", $str);
      $str = str_replace("u00ee", "î", $str);
      $str = str_replace("u00ef", "ï", $str);
      $str = str_replace("u00f0", "ð", $str);
      $str = str_replace("u00f1", "ñ", $str);
      $str = str_replace("u00f2", "ò", $str);
      $str = str_replace("u00f3", "ó", $str);
      $str = str_replace("u00f4", "ô", $str);
      $str = str_replace("u00f5", "õ", $str);
      $str = str_replace("u00f6", "ö", $str);
      $str = str_replace("u00f8", "ø", $str);
      $str = str_replace("u00f9", "ù", $str);
      $str = str_replace("u00fa", "ú", $str);
      $str = str_replace("u00fb", "û", $str);
      $str = str_replace("u00fc", "ü", $str);
      $str = str_replace("u00fd", "ý", $str);
      $str = str_replace("u00ff", "ÿ", $str);
      return $str;
    }

    public function gravaLog($acao, $idUser, $idAt, $o = false){
      if(!$idUser){
        return false;
      }

      $data = date('Y-m-d H:i:s');

      $sql = "INSERT INTO `log` (
        `idUsuario`, `dataLog`, `acao`, `obs`, `ferramenta`, `idAtendimento`
      ) VALUES (
        '$idUser', '$data', '$acao', '$o', 'Medias', '$idAt'
      )";

      if($this->db->query($sql)){
        return true;
      } else {
        return false;
      }
    }

    private function checaHorario(){
      if($this->config->limite){
        $hrs = explode(",", $this->config->limite);
        $hrini = strtotime($hrs[0]);
        $hrfim = strtotime($hrs[1]);
        $now = strtotime(date("H:i"));

        if($now < $hrini || $now > $hrfim){
          return false;
        }
      }

      return true;
    }

    private function connection(){
      //Função de conexão com o DB
      $control = new PDO("mysql:host=".$this->access->dbHost().";dbname=".$this->access->dbDatabase(), $this->access->dbUser(), $this->access->dbPass());
      return $control;
    }

  }

  ?>
