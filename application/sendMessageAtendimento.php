<?php
include '../core.php';

$chat = tratarString($_POST['msg']);
$hash = tratarString($_POST['hash'])/53;
$dst = tratarString($_POST['dst']);
$plataforma = tratarString($_POST['plataforma']);
$rst = tratarString($_POST['rst']);
$data = date('Y-m-d H:i:s');
$id = $_SESSION['id'];

$sql = "SELECT `remetente`, `plataforma` FROM `atendimento` WHERE `idAtendimento` = '$hash'";
$at = $db->query($sql);
$at = (object) $at->fetch();

if($at->plataforma  == 'enterness'){
  $chat = str_replace('"', "'", $chat);
}

if($rst == 0){
  $msg = new Msg();
  $msg->setPlataforma($at->plataforma);
  $msg->setMsg($chat);
  $msg->setDst($at->remetente);
  print_r($msg->sendMessage());
}
//echo ">>".$msg->url;

$chat = $util->setMessage($chat, $at->plataforma);
$chat = $util->checaPosLink($chat);

$sql = "INSERT INTO `chat_atendimento` (
  `idAtendimento`, `chat`, `rmt`, `visualizada`, `dataEnvio`
) VALUES (
  '$hash', '$chat', 'agente',  0, '$data'
)";

$db->query($sql);

$sql = "UPDATE `atendimento` SET `resposta` = 'agente' WHERE `idAtendimento` = '$hash'";
$db->query($sql);


?>
