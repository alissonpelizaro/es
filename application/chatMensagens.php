<?php
include '../coreExt.php';
$hash = tratarString($_POST['hash']);
$last = tratarString($_POST['last']);
$atual = tratarString($_POST['dataLast']);
$hash = explode('-', $hash);
$id = $_SESSION['id'];

if(count($hash) != 4){
	die;
}

$hash[0] = $hash[0]/311;
$hash[1] = $hash[1]/311;
if($hash[0] == $id){
  $eu = $hash[0];
  $ele = $hash[1];
} else if($hash[1] == $id){
  $eu = $hash[1];
  $ele = $hash[0];
} else {
  die;
}

$sql = "SELECT `idChat`, `chat`, `rmt`, `dst`, `ativoRmt`, `ativoDst`, `dataEnvio`
FROM `chat` WHERE `idChat` > '$last' AND ((
  `rmt` = '$eu' AND `dst` = '$ele'
) OR (
  `rmt` = '$ele' AND `dst` = '$eu'
))";

$msgs = $db->query($sql);
$msgs = $msgs->fetchAll();

$mensagens = array();
$i = 0;

foreach ($msgs as $msg) {
  $msgAtual = "";

  $data = explode(" ", $msg['dataEnvio']);
  if ($data[0] != $atual) {
    $atual = $data[0];
    $msgAtual = '<p class="date-chat-divisor"><i>'.dataExtensa($atual).'</i></p>';
  }

  if($msg['rmt'] == $id && $msg['ativoRmt'] == '1'){
    $sentidoAtual = 'sainte';
  } else if($msg['dst'] == $id && $msg['ativoDst'] == '1'){
    $sentidoAtual = 'entrante';
  } else {
    $sentidoAtual = "sainte";
  }

  $msgAtual .= '<div class="msg-'.$sentidoAtual.' msg-fadeTo" id="idMsg'
  .$msg['idChat'].'"><p>'.$msg['chat'].'<t>'
  .retHorarioData($msg['dataEnvio']).'</t></p></div>';

  $mensagens[$i] = array(
    'idMessage' => $msg['idChat'],
    'bodyMessage' => $msgAtual,
    'dataMessage' => $data[0]
  );
  $i++;

}

$sql = "UPDATE `chat` SET `visualizada` = '1'
WHERE `rmt` = '$ele' AND `dst` = '$eu'";
$db->query($sql);

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
  return mb_strtoupper($data[2]." de ".retMes($data[1])." de ".$data[0]);
}

echo json_encode($mensagens);

?>
