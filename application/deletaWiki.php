<?php

/* CHAMADA VIA AJAX! */

include '../core.php';

if(isset($_POST['hash'])){
  $id = tratarString($_POST['hash'])/23;

  $sql = "DELETE FROM `wiki` WHERE `idWiki` = '$id'";
  $obs = "Titulo: ".$log->retWiki($id);
  if($db->query($sql)){
    $log->setAcao('Apagou uma Wiki');
    $log->setFerramenta('Wiki');
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
