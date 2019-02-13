<?php

/* CHAMADA VIA AJAX! */

include '../core.php';

if(isset($_POST['hash'])){
  $id = tratarString($_POST['hash'])/37;

  $sql = "UPDATE `grupo` SET `status` = '0' WHERE `idGrupo` = '$id'";
  $obs = "Grupo: ".$log->retGrupo($id);
  if($db->query($sql)){
    $log->setAcao('Apagou um grupo de agentes');
    $log->setFerramenta('Grupos');
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
