<?php
include '../core.php';
$setted = false;
$idAgente = $_SESSION['id'];
$atFila = "";

if(isset($_GET['hash']) && isset($_GET['token'])){//Se está setado o atendimento e existe token

  //Carrega informações ao atendimento
  $atId = tratarString($_GET['hash'])/253;
  $sql = "SELECT * FROM `atendimento` WHERE `idAtendimento` = '$atId'";
  $at = $db->query($sql);
  $at = $at->fetchAll();
  $idCliente = $at[0]["idCliente"];
  if(count($at) > 0){
    //Se existe o atendimento setado...
    $at = (object) $at[0];
    //Checa se o atendimento é do mesmo agente que está logado
    if($at->idAgente != $idAgente){
      unset($at);
    } else {
      $atFila = $at->fila;
      $setted = true;
    }
  }

  //tira atendimento de pendencia
  if($at->pendente){
    $sql = "UPDATE `atendimento` SET `pendente` = 0, `dataRet` = NULL WHERE `idAtendimento` = '$atId'";
    $db->query($sql);

    $sql = "SELECT `idLog`, `dataLog` FROM `log` WHERE
  	`idUsuario` = '$idUser' AND
    `idAtendimento` = '$atId' AND
  	`acao` = 'Estacionou atendimento'
  	ORDER BY `dataLog` DESC LIMIT 1";
  	$lastData = $db->query($sql);
  	$lastData = $lastData->fetch();

    $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($lastData['dataLog']);

    $log->setAcao('Tirou do estacionamento');
    $log->setFerramenta('Medias');
    $log->setAtendimento($atId);
    $log->setObs($diff);
    $log->gravaLog();

    $log->atualizaObsLog($lastData['idLog'], $diff);

  }

  //Carrega informações do Cliente
  if($idCliente != ""){
    $sql = "SELECT
              *
            FROM
              `cliente`
            WHERE
              `idCliente` = $idCliente";

    $cliente = $db->query($sql);
    $cliente = $cliente->fetchAll();
    $cliente = $cliente[0];
    $fones = json_decode($cliente["fone"]);
    

    
  } else {
    $cliente = array(
      'idCliente' => '',
      'nome' => '',
      'fone' => '',
      'nascimento' => '',
      'email' => '',
      'cpf' => '',
      'promocoes' => '',
      'cep' => '',
      'rua' => '',
      'numero' => '',
      'complemento' => '',
      'bairro' => '',
      'uf' => '',
      'empresa' => '',
      'dtRegistro' => '',
      'foto' => '',
      'midias' => '',
      'cidade' => ''
    );
  }
}

//Carrega agentes cadastrados
$sql = "SELECT `idUser`, `nome`, `sobrenome`, `filas` FROM `user` WHERE `tipo` = 'agente' AND `status` = '1' AND `idUser` != '$idAgente' AND `logged` = '1' ORDER BY `nome`, `sobrenome`";
$agentes = $db->query($sql);
$agentes = $agentes->fetchAll();

//Carrega filas cadastradas
$sql = "SELECT `idFila`, `nomeFila` FROM `fila` WHERE `status` = '1' AND `nomeFila` != '$atFila'";
$filas = $db->query($sql);
$filas = $filas->fetchAll();

$sql = "SELECT `palavra` FROM `dicionario` WHERE `categoria` = 'palavrao' ORDER BY CASE WHEN `palavra` LIKE '% %' THEN 1 ELSE 2 END";
$listaPalavras = $db->query($sql);
$listaPalavras = $listaPalavras->fetchAll();

//Carrega atalhos
$sql = "SELECT * FROM `atalho` WHERE `setor` = '$setorUser' ORDER BY `atalho`.`atalho` ASC";
$listaAtalhos = $db->query($sql);
$listaAtalhos = $listaAtalhos->fetchAll();
$atalhos = array();

//Carrega configuraçõe
$sql = "SELECT * FROM `config` WHERE `idConfig` = 1";
$conf = $db->query($sql);
$conf = (object) $conf->fetch();

$followiseConf = json_decode($conf->followise);

$jTemp = 0;

for ($i = 0; $i < 10; $i++) {
	if (isset($listaAtalhos[$jTemp]) && is_array($listaAtalhos[$jTemp])) {
		if(isset($listaAtalhos[$jTemp]["atalho"]) && $listaAtalhos[$jTemp]["atalho"] == $i){
			$atalhos[$i] = $listaAtalhos[$jTemp];
			$jTemp++;
		}else{
			$atalhos[$i] = array("texto" => "<i style='color: #b3b3b3'>Nõo há atalho.</i>");
		}
	}else{
		if(isset($listaAtalhos["atalho"]) && $listaAtalhos["atalho"] == $i){
			$atalhos[$i] = $listaAtalhos;
		}else{
			$atalhos[$i] = array("texto" => "<i style='color: #b3b3b3'>Nõo há atalho.</i>");
		}
	}
}

function expFila($filas){
  $filas = explode("-#-", $filas);
  $ret = "";
  $tFila = count($filas);
  foreach ($filas as $fl) {
    if($fl != ""){
      $ret .= $fl;
      $ret .= ", ";
    }
  }

  return  substr($ret, 0, -2);
}

?>
