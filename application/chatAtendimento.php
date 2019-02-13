<?php
include '../coreExt.php';
$at = tratarString($_POST['hash'])/53;
$last = tratarString($_POST['last']);
$atual = tratarString($_POST['dataLast']);
$sql = "SELECT `idChatAtendimento`, `chat`, `rmt`, `dataEnvio`
FROM `chat_atendimento`
WHERE `idChatAtendimento` > '$last' AND`idAtendimento` = '$at'";
$msgs = $db->query($sql);
$msgs = $msgs->fetchAll();


$sql = "SELECT
`idCliente`,
`idAgente`
FROM
`atendimento`
WHERE
`idAtendimento` = '$at'";
$atDet = $db->query($sql);
$atDet = $atDet->fetch();

if(!isset($_SESSION['id'])){
  echo "unlogged";
  die();
} /* else {
  if($_SESSION['id'] != $atDet["idAgente"]){
    echo "transbordo";
    die();
  }
}*/
$idCliente = $atDet["idCliente"];

if ($last == 0) {

  $util = new Util();

  $sql = "SELECT
				  	`feed_atendimento`.`chat`, `feed_atendimento`.`idFeed`, `atendimento`.`idAgente`,
						`atendimento`.`dataInicio`, `atendimento`.`dataFim`
				  FROM
				  	`atendimento`
				  INNER JOIN
				  	`feed_atendimento`
				  ON
				  	`atendimento`.`idAtendimento` = `feed_atendimento`.`idAtendimento`
				  WHERE
				  	`atendimento`.`idCliente` = '$idCliente'";

  $feeds = $db->query($sql);
  $feeds = $feeds->fetchAll();
  $j = 0;
  $mensagens = array();
  foreach ($feeds as $feed) {
  	if ($j == 0) {
  		$mensagens = $util->feedParaChat($feed["chat"],
  																		 $feed["idFeed"],
  																		 $feed["idAgente"],
  				 														 $feed["dataInicio"],
  																		 $feed["dataFim"]);
  	} else {
  		$arrayTemp = $mensagens;
  		$mensagens = $util->feedParaChat($feed["chat"],
														  				 $feed["idFeed"],
														  				 $feed["idAgente"],
														  				 $feed["dataInicio"],
														  				 $feed["dataFim"]);
	  	$x = count($arrayTemp);
	  	foreach ($mensagens as $mensagem) {
	  		$arrayTemp[$x] = $mensagem;
				$x++;
	  	}
	  	$mensagens = $arrayTemp;
  	}
  	$j++;
  }

  $i = count($mensagens);

} else {
  $mensagens = array();
  $i = 0;
}

foreach ($msgs as $msg) {
  $msgAtual = "";
  if($msg['rmt'] == 'cliente'){
    $sentidoAtual = 'entrante';
  } else {
    $sentidoAtual = "sainte";
  }
  $data = explode(" ", $msg['dataEnvio']);
  if ($data[0] != $atual) {
    $atual = $data[0];
    $msgAtual = '<p class="date-chat-divisor"><i>'.dataExtensa($atual).'</i></p>';
  }

  $msgAtual .= '<div class="msg-'.$sentidoAtual.' msg-fadeTo" id="idMsg'
  .$msg['idChatAtendimento'].'"><p>'.$msg['chat'].'<t>'
  .retHorarioData($msg['dataEnvio']).'</t></p></div>';

  $mensagens[$i] = array(
    'idMessage' => $msg['idChatAtendimento'],
    'bodyMessage' => $msgAtual,
    'dataMessage' => $data[0]
  );
  $i++;
}

if(count($msgs) == 0 && $atual == "0"){
  /*$mensagens[0] = array(
  'idMessage' => 0,
  'bodyMessage' => '<h3 class="m-t-110 text-center text-muted"><i></i></h3>',
  'dataMessage' => 0
);*/
}

echo json_encode($mensagens);

//Marca mensagens como visualizada
if(!isset($_POST['local'])){
  $sql = "UPDATE `chat_atendimento` SET `visualizada` = 1
  WHERE `visualizada` = 0 AND `idAtendimento` = '$at' AND `rmt` = 'cliente'";
  $db->query($sql);
}

function retHorarioData($data){
  if(strpos($data, " ") !== false){
    $hora = explode(" ", $data);
    $hora = explode(":", $hora[1]);
    return $hora[0].":".$hora[1];
  }
  return "";
}

function dataExtensa($data){
  $data = explode("-", $data);
  if(isset($data[1])){
	  return mb_strtoupper($data[2]." de ".retMes($data[1])." de ".$data[0]);
  } else {
  	$data = explode("/", $data[0]);
  	return mb_strtoupper($data[0]." de ".retMes($data[1])." de ".$data[2]);
  }
}

?>
