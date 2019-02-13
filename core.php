<?php
session_start();

include 'config/Model.php';
include 'config/config.php';
include 'config/database.php';
include 'config/security.php';
include 'config/facilidades.php';
include 'config/Util.php';

if(__CALLBASE === false){
  //Acesso direto ao script --> Penetração
  abortAccess();
}

if(!isset($_SESSION['id'])){
  header('Location: ../my/login');
  die;
}

$time = $_SESSION['timeout'];

if($time == '0'){
  $time = 999999; //Sem time-out
}

$agora = date('Y-m-d H:i:s', strtotime('-'.$time.' minute'));
$userTime = date('Y-m-d H:i:s', strtotime($_SESSION['hora']));

if(strtotime($agora) > strtotime($userTime)){

  unset($_SESSION['nome']);
  unset($_SESSION['id']);
  unset($_SESSION['senha']);
  unset($_SESSION['tipo']);
  unset($_SESSION['hora']);
  header ('Location: ../my/login?session=timeout');
  die;

} else {

  $idUser = $_SESSION['id'];

  $sql = "SELECT `token`, `tipo`, `setor`, `pausa` FROM `user` WHERE `idUser` = '$idUser'";
  $ip = $db->query($sql);
  $ip = $ip->fetch();
  $tipo = $ip['tipo'];
  $setorUser = $ip['setor'];
  $pausaStatus = $ip['pausa'];
  $ip = $ip['token'];

  if($tipo != 'dev' && $ip != $_SESSION['token']){
    /*
    Usuário logado em dois computadores
    Desloga o primeiro que estava logado

    */
    unset($_SESSION['token']);
    unset($_SESSION['nome']);
    unset($_SESSION['id']);
    unset($_SESSION['senha']);
    unset($_SESSION['tipo']);
    unset($_SESSION['hora']);
    header ('Location: ../my/login?session=duplicate');
    die;
  }

  $agora = date('Y-m-d H:i:s');
  $sql = "UPDATE `user` SET `ultimoRegistro` = '$agora', `logged` = 1 WHERE `idUser` = '$idUser'";
  $db->query($sql);
  $_SESSION['hora'] = $agora;
  $util = new Util($setorUser);
}

?>
