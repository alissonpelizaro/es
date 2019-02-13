<?php

/* CHAMADA VIA AJAX! */

include '../core.php';

if(isset($_POST['hash'])){
  $id = tratarString($_POST['hash'])/7;

  $sql = "UPDATE `mural` SET `status` = '0' WHERE `idMural` = '$id'";
  if($db->query($sql)){
    $obs = "Mural: ".$log->retMural($id);
    $log->setAcao('Apagou uma mensagem de mural');
    $log->setFerramenta('Mural');
    $log->setObs($obs);
    $log->gravaLog();
    echo 1;
  } else {
    echo 0;
  }
} else {
  echo 0;
}

 ?>
