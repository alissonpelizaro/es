<?php
include '../coreExt.php';

if($_POST['hash'] == '5'){

  //Só mostra broadcast se for um agente logado
  if($_SESSION['tipo'] != 'agente'){
    echo 'false';
    die;
  }

  //SPLIT 8/8-8/
  $id = "-".$_SESSION['id']."-";
  $data = date("Y-m-d H:i:s");

  $sql = "SELECT `idBroadcast`, `broadcast` FROM `broadcast` WHERE `status` = '1' AND `destinatarios` LIKE '%$id%' AND (`confirmacoes` IS NULL OR `confirmacoes` NOT LIKE '%$id%')";
  $broad = $db->query($sql);
  $broad = $broad->fetchAll();

  if(count($broad) > 0){
    echo nl2br($broad[0]['idBroadcast']."8/8-8/".$broad[0]['broadcast']);
  } else {
    echo 'false';
  }
} else if($_POST['hash'] == '14'){
  $id = tratarString($_POST['id']);
  $sql = "SELECT `confirmacoes` FROM `broadcast` WHERE `idBroadcast` = '$id'";
  $broad = $db->query($sql);
  $broad = $broad->fetchAll();
  if(count($broad) == 1){
    $idUsr = $_SESSION['id'];
    $confirm = str_replace($idUsr, '', $broad[0]['confirmacoes']);
    $confirm .= "-".$idUsr."-";
    $confirm = str_replace('--', '-', $confirm);
    $sql = "UPDATE `broadcast` SET `confirmacoes` = '$confirm' WHERE `idBroadcast` = '$id'";
    $db->query($sql);
  }
  $agora = date('Y-m-d H:i:s');
  $sql = "UPDATE `user` SET `ultimoRegistro` = '$agora' WHERE `idUser` = '$idUser'";
  $db->query($sql);
  $_SESSION['hora'] = $agora;
}

//Função para corrigir status dos usuários exporadicamente
$i = rand(1,11);
if($i == 10){
  $time = $_SESSION['timeout'];
  if($time == '0'){
    $time = 999999999;
  }

  $agora = date('Y-m-d H:i:s', strtotime('-'.$time.' minute'));
  $sql = "UPDATE `user` SET `logged` = '0' WHERE `ultimoRegistro` < '$agora'";
  $db->query($sql);
}


?>
