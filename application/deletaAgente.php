<?php

/* CHAMADA VIA AJAX! */

include '../core.php';

if(isset($_POST['hash'])){
  $id = tratarString($_POST['hash'])/217;

  $sql = "UPDATE `user` SET `status` = '0' WHERE `idUser` = '$id'";
  if($db->query($sql)){
    $tmp = 'Nome: '. $log->retNome($id);
    $log->setAcao('Apagou um agente');
    $log->setFerramenta('MyOmni');
    $log->setObs($tmp);
    $log->gravaLog();
    echo 1;
  } else {
    echo 0;
  }
} else {
  echo 0;
}

 ?>
