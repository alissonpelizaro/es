<?php
  include '../core.php';

  //chamada via Ajax apenas para checar a disponibilidade de um login ou e-mail
  $value = strtolower(tiraAcento(tratarString($_POST['value'])));
  $param = tratarString($_POST['param']);

  if($param == 'email'){
    $sql = "SELECT `idUser` FROM `user` WHERE `email` = '$value' AND `status` = '1'";
  } else if($param == 'login'){
    $sql = "SELECT `idUser` FROM `user` WHERE `usuario` = '$value' AND `status` = '1'";
  }

  $total = $db->query($sql);
  $total = $total->fetchAll();

  if(count($total) == 0){
    echo 0;
  } else {
    echo 1;
  }

?>
