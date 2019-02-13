<?php

/* CHAMADA VIA AJAX! */

include '../core.php';

if(isset($_POST['hash'])){
  $id = tratarString($_POST['hash'])/37;

  //Checa se existe usuario nesse setor. Se sim, não apaga!
  $sql = "SELECT count(`idUser`) AS `total` FROM `user` WHERE `setor` = '$id' AND `status` = '1'";
  $tt = $db->query($sql);
  $tt = $tt->fetch();

  if($tt['total'] > 0){
    echo 3;
  } else {
        $sql = "DELETE FROM `setor` WHERE `idSetor` = '$id'";
    $obs = "Setor: ".$log->retSetor($id);
    if($db->query($sql)){
      $log->setAcao('Apagou um setor');
      $log->setFerramenta('Setores');
      $log->setObs($obs);
      $log->gravaLog();
      echo 1;
    } else {
      echo 0;
    }
  }

} else {
  echo 0;
}

 ?>
