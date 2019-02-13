<?php

/**
* Classe de facilidades e utilizades
* Deverá substituir o arquivo "facilidades.php" futuramente
*/
class Util {

  private $db;
  private $modules;
  private $section;
  private $config;

  function __construct($idSection = false){
    $this->config = new Ambiente;
    $this->db = $this->connection();
    if($idSection){
      $this->section = $idSection;
    }
    $this->modules = $this->getSectionAccess($idSection);
  }

  public function checaPosLink($dados){
    if(strpos($dados, "http") !== false){
      $link = explode(" ", $dados);
      $links = array();
      foreach ($link as $k) {
        if(strpos($k, "http") !== false){
          $links[count($links)] = $k;
        }
      }
      foreach ($links as $k) {
        $dados = str_replace($k, '<a href="'.$k.'" target="_blank">'.$k.'</a>', $dados);
      }
    }
    return $dados;
  }

  public function feedParaChat($feed, $id, $idAgente = FALSE, $dataIni = FALSE, $dataFim = FALSE) {

    $cliente = explode("<hr>", $feed);
    $feed = $cliente[1];
    $cliente = $cliente[0];
    $msgs = explode("<br class=-expl->", $feed);
    $mensagens = array();
    $i = 0;
    $atual = 0;
    foreach ( $msgs as $msg ) {
      if ($i > 0) {
        $remetente = "";
        $dataEnvio = "";
        $chat = "";
        $msgAtual = "";

        if (preg_match ( "<i>", $msg ) && preg_match ( "</i>", $msg )) {
          $msg = explode ( "</b>", $msg );
          $remetente = $msg [0];
          $remetente = explode ( " (", $remetente );
          $dataEnvio = $remetente [1];
          $remetente = $remetente [0];
          $remetente = str_replace ( " ", "", $remetente );
          $remetente = str_replace ( "<b>", "", $remetente );
          $dataEnvio = str_replace ( " ", "", $dataEnvio );
          $dataEnvio = str_replace ( "às", " ", $dataEnvio );
          $dataEnvio = str_replace ( "):", "", $dataEnvio );
          if(!isset($msg [2])){
            $chat = $msg[1];
          } else {
            $chat = $msg [1] . "</b>" . $msg [2] . "</b>";
          }
        } else {
          $msg = explode ( "<b>", $msg );
          if(isset($msg[2])){
            $chat = "<b>".$msg [2];
            $msg = str_replace ( "</b>", "", $msg [1] );
            $msg = explode ( " (", $msg);
          }else{
            $msg = explode ( "</b>", $msg [1] );
            $chat = $msg [1];
            $msg = explode ( " (", $msg [0] );
          }
          $remetente = str_replace ( " ", "", $msg [0] );
          $msg = str_replace ( " ", "", $msg [1] );
          $msg = str_replace ( "às", " ", $msg );
          $dataEnvio = str_replace ( "):", "", $msg );
        }

        if ($remetente == 'Cliente') {
          $sentidoAtual = 'entrante';
        } else {
          $sentidoAtual = "sainte";
        }

        $data = explode ( " ", $dataEnvio );
        if ($data [0] != $atual) {
          $atual = $data [0];
          $msgAtual = '<p class="date-chat-divisor"><i>' . $this->dataExtensa ( $atual ) . '</i></p>';
        }

        if (strpos ( $chat, "-media-img-chat-" ) !== FALSE) {
          $chat = str_replace ( "-media-img-chat-", "'media-img-chat'", $chat );
        }
        if (strpos ( $chat, "-media-video-chat-" ) !== FALSE) {
          $chat = str_replace ( "-media-video-chat-", "'media-video-chat'", $chat );
        }
        if (strpos ( $chat, "-media-audio-chat-" ) !== FALSE) {
          $chat = str_replace ( "-media-audio-chat-", "'media-audio-chat'", $chat );
        }
        if (strpos ( $chat, "-media-doc-chat-" ) !== FALSE) {
          $chat = str_replace ( "-media-img-chat-", "'media-doc-chat'", $chat );
        }

        $msgAtual .= '<div class="msg-' . $sentidoAtual . ' msg-fadeTo" id="idMsgpre' . $i . $id . '"><p>' . $chat . '<t>' . $this->retHorarioData ( $dataEnvio ) . '</t></p></div>';

        $mensagens [($i - 1)] = array (
          'idMessage' => "pre" . $i . $id,
          'bodyMessage' => $msgAtual,
          'dataMessage' => $data [0]
        );
      }
      $i ++;
    }

    if ($idAgente) {
      $sql = "SELECT
      `user`.`nome`, `user`.`sobrenome`, `setor`.`nome`
      FROM
      `user`
      INNER JOIN
      `setor`
      ON
      `user`.`setor` = `setor`.`idSetor`
      WHERE
      `user`.`idUser` = '$idAgente'";
      $agente = $this->db->query ( $sql );
      $agente = $agente->fetch ();
      $nomeAgente = $agente [0];
      $sobrenomeAgente = $agente [1];
      $setorAgente = $agente [2];
      $dtIni = dataBdParaHtml ( $dataIni );
      $dtFim = dataBdParaHtml ( $dataFim );
      if(!isset($data [0])){
        $data = array("");
      }

      $mensagens [($i - 1)] = array (
        'idMessage' => "pre" . $i . $id,
        'bodyMessage' => "<br style='clear: both;'><br><div class='text-center' style='font-size: 0.7em;'>Atendimento efetuado por $nomeAgente $sobrenomeAgente/$setorAgente - Início: $dtIni, Fim: $dtFim</div><hr style='width: 90%;margin-top: 0px;'>",
        'dataMessage' => $data [0]
      );
    }

    return $mensagens;
  }

  private function dataExtensa($data){
    $data = explode("-", $data);
    if(isset($data[1])){
      return mb_strtoupper($data[2]." de ".retMes($data[1])." de ".$data[0]);
    } else {
      $data = explode("/", $data[0]);
      return mb_strtoupper($data[0]." de ".retMes($data[1])." de ".$data[2]);
    }
  }

  private function retHorarioData($data){
    if(strpos($data, " ") !== false){
      $hora = explode(" ", $data);
      $hora = explode(":", $hora[1]);
      return $hora[0].":".$hora[1];
    }
    return "";
  }

  //Método que verifica se o cadastro do usuário está completo
  public function isCadastroClienteCompleto($cliente) {
    try {
      if ($cliente["nome"] != "" &&
      $cliente["fone"] != "" &&
      $cliente["nascimento"] != "" &&
      $cliente["nascimento"] != "1000-01-01" &&
      $cliente["email"] != "" &&
      $cliente["cpf"] != "" &&
      $cliente["cep"] != "" &&
      $cliente["rua"] != "" &&
      $cliente["numero"] != "" &&
      $cliente["bairro"] != "" &&
      $cliente["uf"] != "UF" &&
      $cliente["cidade"] != "") {
        return true;
      } else {
        return false;
      }
    } catch (Exception $e) {
      echo "Falta de informações para realizar o método cadastroClienteCompleto da classe Util";
      echo "<br>";
      print_r($e);
      die();
    }
  }

  public function getRestrictionStat(){
    if($_SESSION['tipo'] != 'agente' &&
    $_SESSION['tipo'] != 'tecnico' &&
    $_SESSION['tipo'] != 'gestor'){
      $sql = "SELECT `idAtendimento` AS `tt` FROM `atendimento` WHERE `notifRest` = 1 AND `status` = 0";
      $tt = $this->db->query($sql);
      $tt = $tt->fetchAll();
      if(isset($tt[0])){
        return $tt[0]['tt']*253;
      }
      return 0;
    } else {
      return 0;
    }
  }

  //Checa se o setor atual tem permissão para utilizar algum módulo
  public function getSectionPermission($module){
    if(strpos($this->modules, "-".$module."-") !== false){
      return true;
    }
    return false;
  }

  //Recebe id de um setor e retorna array dos módulos permitidos nesse setor
  public function getSectionAccess(){
    $idSection = $this->section;
    $access = $this->db->query("SELECT `modulos` FROM `setor` WHERE `idSetor` = '$idSection'");
    $access = $access->fetch();
    return $access['modulos'];
  }

  /*Retorna um objeto contendo todas as informações de um usuário,
  se nao for passado nenhum id, retorna informações do usuário logado */
  public function getUserInfo($user = false){
    if(!$user){
      $user = $_SESSION['id'];
    }
    $sql = "SELECT * FROM `user` WHERE `idUser` = '$user'";
    $user = $this->db->query($sql);
    $user = $user->fetch();

    if(isset($user['idUser'])){
      return (object) $user;
    }
    return false;
  }

  /* Recebe uma string sem fomatação das filas e retorna um array,
  Se for passado parametro FALSE, retorna a primeira fila como sting */
  public function geraArrayFilas($filas, $array = true){
    $filas = explode('-#-', $filas);
    $newArray = array();
    $aux = 0;
    foreach ($filas as $fila) {
      if($fila != ""){
        $newArray[$aux] = $fila;
      }
    }
    if(count($newArray) > 0){
      if($array){
        return $newArray;
      }
      return $newArray[0];
    }
    return false;
  }

  /* Função que checa se um numero possui atendimento aberto
  Retorno booleano */
  public function emAtendimento($num){
    $sql = "SELECT count(`idAtendimento`) AS `total` FROM `atendimento` WHERE `status` = 0 AND `remetente` = '$num'";
    $at = $this->db->query($sql);
    $at = $at->fetch();
    if($at['total'] > 0){
      return true;
    }
    return false;
  }

  /* Recebe um numero de celular no formato BD e
  retorna formatado p/ whatsapp */
  public function celParaWhatsApp($num){
    $num = preg_replace("/[^0-9]/", "", $num);
    if(strlen($num) > 10){
      $num = substr($num, 0, 2) . substr($num, -8);
    }
    return '55'.$num."@c.us";
  }

  /* Recebe uma mensagem e converta a formatação
  dela para HTML de acordo com a plataforma */
  public function setMessage($msg, $plataforma){
    if($plataforma == 'whatsapp'){
      //Formata negrito

      $neg = (substr_count($msg, '*') >= 2) ? true : false;
      $ita = (substr_count($msg, '_') >= 2) ? true : false;
      $ris = (substr_count($msg, '~') >= 2) ? true : false;

      if($neg && strpos($msg, "**") !== false){
        $neg = false;
      }
      if($ita && strpos($msg, "__") !== false){
        $ita = false;
      }
      if($ris && strpos($msg, "~~") !== false){
        $ris = false;
      }

      $negInd = (object) array(
        'open' => false,
        'ind' => 0
      );

      $itaInd = (object) array(
        'open' => false,
        'ind' => 0
      );

      $risInd = (object) array(
        'open' => false,
        'ind' => 0
      );

      $tstr = strlen($msg);

      for ($i=0; $i < $tstr; $i++) {
        //echo $i."-".$msg[$i]."<br>";

        if($msg[$i] == '*' && $neg){
          //Negrito
          if(!$negInd->open){
            $msgN = substr($msg, 0, $i)."<b>".substr($msg, $i+1, $tstr);
            $msg = $msgN;
            $tstr = $tstr+2;
            $i = $i+2;

            $negInd->open = true;
            $negInd->ind = $i;

          } else if($negInd->open){
            $msgN = substr($msg, 0, $i)."</b>".substr($msg, $i+1, $tstr);
            $msg = $msgN;
            $tstr = $tstr+3;
            $negInd->open = false;
            $i = $i+3;
            $negInd->ind = $i;
          }

        } else if($msg[$i] == '_' && $ita){
          //Italico
          if(!$itaInd->open){
            $msgN = substr($msg, 0, $i)."<i>".substr($msg, $i+1, $tstr);
            $msg = $msgN;
            $tstr = $tstr+2;
            $i = $i+2;

            $itaInd->open = true;
            $itaInd->ind = $i;

          } else if($itaInd->open){
            $msgN = substr($msg, 0, $i)."</i>".substr($msg, $i+1, $tstr);
            $msg = $msgN;
            $tstr = $tstr+3;
            $itaInd->open = false;
            $i = $i+3;
            $itaInd->ind = $i;
          }

        } else if($msg[$i] == '~' && $ris){
          //Riscado
          if(!$risInd->open){
            $msgN = substr($msg, 0, $i)."<strike>".substr($msg, $i+1, $tstr);
            $msg = $msgN;
            $tstr = $tstr+7;
            $i = $i+7;

            $risInd->open = true;
            $risInd->ind = $i;

          } else if($risInd->open){
            $msgN = substr($msg, 0, $i)."</strike>".substr($msg, $i+1, $tstr);
            $msg = $msgN;
            $tstr = $tstr+8;
            $risInd->open = false;
            $i = $i+8;
            $risInd->ind = $i;
          }

        }

      }

      return $msg;

    } else if($plataforma == "enterness"){
      return $msg;
    }
    return false;
  }

  /* Retorna o status do cliente atual
  na API do WhatsApp */
  public function whatsAppStatus(){

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_PORT => $this->config->getWhatsAppPort(),
      CURLOPT_URL => $this->config->getWhatsAppServer()."admin/clients",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 120,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "auth-key: ".$this->config->getTokenWhatsApp(),
        "cache-control: no-cache"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return false;
    } else {
      return $response;
    }

  }

  /* Retona uma STRING do QR code
  para sincronismo do WhatsAppWeb */
  public function getWhatsAppQR(){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_PORT => $this->config->getWhatsAppPort(),
      CURLOPT_URL => $this->config->getWhatsAppServer()."screen/qr",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 60,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "auth-key: ".$this->config->getTokenWhatsApp(),
        "cache-control: no-cache",
        "client_id: ".$this->config->getUserApi()
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return false;
    } else {
      return $response;
    }
  }

  public function insertWhatsAppClientId(){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_PORT => $this->config->getWhatsAppPort(),
      CURLOPT_URL => $this->config->getWhatsAppServer()."client",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 60,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "PUT",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "auth-key: ".$this->config->getTokenWhatsApp(),
        "cache-control: no-cache",
        "client_id: ".$this->config->getUserApi()
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if (!$err) {
      $response = json_decode($response);
      if(isset($response->Success) && $response->Success){
        return true;
      }
    }

    return false;

  }

  public function getCliente($id){
    $sql = "SELECT * FROM `cliente` WHERE `idCliente` = '$id'";
    $cliente = $this->db->query($sql);
    $cliente = $cliente->fetch();
    if($cliente){
      return (object) $cliente;
    }
    return false;
  }

  public function sendFollowise($msg, $idCliente){

    $sql = "SELECT `followise` FROM `config` WHERE `idConfig` = 1";
    $conf = $this->db->query($sql);
    $conf = (object) $conf->fetch();
    $conf = json_decode($conf->followise);

    $cliente = $this->getCliente($idCliente);

    if(!$cliente){
      return false;
    }

    $followise = (object) array(
      'status' => $conf->status,
      'api' => $conf->api,
      'tokenClient' => $conf->tokenClient,
      'tokenTeam' => $conf->tokenTeam,
      'tipo' => $conf->tipo,
    );

    /*
    $data["clientKey"] = $conf->tokenClient;
    $data["teamKey"] = $conf->tokenTeam;
    $data["conversionGoal"] = $conf->tipo;
    $data["name"] = $cliente->nome;
    $data["email"] = $cliente->email;
    $data["message"] = $msg;

    $data = json_encode($data);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $conf->api);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $result = json_decode(curl_exec($curl), true);
    curl_close($curl);

    if ($result["success"] == 1){
      return true;
    }
    return false;
    */

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $conf->api,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\r\n\"clientKey\": \"$conf->tokenClient\",\r\n\"teamKey\": \"$conf->tokenTeam\",\r\n\"conversionGoal\": \"$conf->tipo\",\r\n\"name\": \"$cliente->nome\",\r\n\"email\": \"$cliente->email\", \r\n\"message\": \"$msg\"\r\n}",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: 6c79a938-d7a1-45a0-9b8b-a909374b5e05",
        "cache-control: no-cache"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if (!$err) {
      $response = json_decode($response, true);
      if ($response["success"] == 1){
        return true;
      }
    }
    return false;

  }

  public function iconFontWesome($plataforma) {
    switch ($plataforma) {
      case "whatsapp":
      return "fa fa-whatsapp";

      case "telegram":
      return "fa fa-telegram";

      case "email":
      return "fa fa-envelope-o";

      case "messenger":
      return "fa fa-facebook-square";

      case "enterness":
      return "fa fa-power-off";

      case "skype":
      return "fa fa-skype";

      default:
      return "fa fa-square-o";
    }
  }

  public function dataHtmlParaBd($data, $hora = "Nada") {

    if ($data == "") {
      $data = FALSE;
    }
    if ($hora == "") {
      $hora = date("H:i");
    }else if($hora == "Nada"){
      $hora = FALSE;
    }


    if($data != FALSE){
      if($data){
        if ($hora) {
          $data = explode("/", $data);
          return $data[2]."-".$data[1]."-".$data[0]." ".$hora.":".date('s');
        }else{
          $data = explode("/", $data);
          return $data[2]."-".$data[1]."-".$data[0];
        }
      }else{
        return date("Y-m-d")." ".$hora.":".date('s');
      }
      //echo "entro";
    }
    return "";
  }

  //Prepara conexão com o BD
  private function connection(){
    $control = new PDO("mysql:host=".$this->config->dbHost().";dbname=".$this->config->dbDatabase(), $this->config->dbUser(), $this->config->dbPass());
    return $control;
  }

}
?>
