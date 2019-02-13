<?php
include '../core.php';

if($_SESSION['chat'] == "nao" || $_SESSION['chat'] == ""){
  header('Location: ../my/inicio');
  die;
}

if(isset($_GET['hash']) && isset($_GET['token'])){
  $hash = explode('-', tratarString($_GET['hash']));
  if(!isset($hash[0]) || !isset($hash[1]) || !isset($hash[2])){
    $setted = false;
  } else {
    if(($hash[0]/311) == $_SESSION['id']){
      $dst = $hash[1]/311;
      $sql = "SELECT `idUser`, `nome`, `sobrenome`, `tipo`, `ultimoRegistro`, `avatar`, `logged`, `setor`
      FROM `user` WHERE `idUser` = '$dst' AND `status` = '1'";
      $dst = $db->query($sql);
      $dst = $dst->fetchAll();
      if(count($dst) == 0){
        $setted = false;
      } else {
        $dst = $dst[0];
        if($_SESSION['chat'] == 'sup' && $dst['tipo'] == 'agente'){
          $setted = false;
        } else {
          $setted = true;
        }
      }
    } else if(($hash[1]/311) == $_SESSION['id']){
      $dst = $hash[0]/311;
      $sql = "SELECT `idUser`, `nome`, `sobrenome`, `tipo`, `ultimoRegistro`, `avatar`, `logged`, `setor`
      FROM `user` WHERE `idUser` = '$dst' AND `status` = '1'";
      $dst = $db->query($sql);
      $dst = $dst->fetchAll();
      if(count($dst) == 0){
        $setted = false;
      } else {
        $dst = $dst[0];
        if($_SESSION['chat'] == 'sup' && $dst['tipo'] == 'agente'){
          $setted = false;
        } else {
          $setted = true;
        }
      }
    } else {
      $setted = false;
    }
  }
} else {
  $setted = false;
}


?>
