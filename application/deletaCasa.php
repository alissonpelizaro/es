<?php

/* CHAMADA VIA AJAX! */

include '../core.php';

if(isset($_POST['hash'])){
  $id = tratarString($_POST['hash'])/217;
  $nome = tratarString($_POST['nome']);

  $sql = "UPDATE `casa` SET `status` = '0' WHERE `idCasa` = '$id'";
  if($db->query($sql)){
    $sql = "UPDATE `user` SET `status` = '0' WHERE (`tipo` = 'tecnico' OR `tipo` = 'gestor') AND `ramal` = '$id'";
    $db->query($sql);
    $tmp = 'Nome: '. $nome;
    $log->setAcao('Apagou uma casa');
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
