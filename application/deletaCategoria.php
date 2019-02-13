<?php

/* CHAMADA VIA AJAX! */

include '../core.php';

if(isset($_POST['hash'])){
  $id = tratarString($_POST['hash'])/23;
  $obs = "Categoria: ".$log->retCategoria($id);
  $sql = "DELETE FROM `wiki` WHERE `idCat` = '$id'";
  if($db->query($sql)){
    $sql = "DELETE FROM `cat_wiki` WHERE `idCat` = '$id'";
    if($db->query($sql)){
      $log->setAcao('Apagou uma categoria na Wiki');
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
} else {
  echo 0;
}

 ?>
