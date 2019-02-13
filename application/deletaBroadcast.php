<?php
/* CHAMADA VIA AJAX */

include '../core.php';

$id = tratarString($_POST['hash'])/13;

$sql = "UPDATE `broadcast` SET `status` = '0' WHERE `idBroadcast` = '$id'";
$obs = "Broadcast: ". $log->retBroadcast($id);
if($db->query($sql)){
  $log->setAcao('Apagou uma broadcast');
  $log->setFerramenta('Broadcast');
  $log->setObs($obs);
  $log->gravaLog();
  echo 1;
} else {
  echo 0;
}

?>
