<?php

/* CHAMADA VIA AJAX! */

include '../core.php';

if(isset($_POST['hash'])){
  $id = tratarString($_POST['hash'])/17;

  $sql = "DELETE FROM `lembrete` WHERE `idLembrete` = '$id'";
  if($db->query($sql)){
    echo 1;
  } else {
    echo 0;
  }
} else {
  echo 0;
}

 ?>
