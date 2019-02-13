<?php

/* CHAMADA VIA AJAX! */

include '../core.php';

if(isset($_POST['hash'])){
  $id = tratarString($_POST['hash'])/217;
  $novaSenha = md5('mudar123');

  $sql = "UPDATE `user` SET `senha` = '$novaSenha' WHERE `idUser` = '$id'";
  $obs = "Usuario: ".$log->retNome($id);
  $log->setAcao('Resetou a senha de um usuario');
  $log->setFerramenta('MyOmni');
  $log->setObs($obs);
  $log->gravaLog();
  if($db->query($sql)){
    echo 1;
  } else {
    echo 0;
  }
} else {
  echo 0;
}

 ?>
