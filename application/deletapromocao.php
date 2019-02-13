<?php
/* CHAMADA VIA AJAX */

include '../core.php';

$id = tratarString($_POST['hash'])/13;

$sql = "DELETE FROM `promocao` WHERE `idPromocao` = '$id'";
if($db->query($sql)){
  /*$obs = "Broadcast: ". $log->retBroadcast($id);
  $log->gravaLog('Apagou uma broadcast','Broadcast', $obs);*/
  echo 1;
} else {
  echo 0;
}

?>
