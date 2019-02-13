<?php
include '../coreExt.php';

if(!isset($_GET["token"])){
  header("Location: ../my/login");
  die;
}

$token = tratarString($_GET['token']);

$sql = "SELECT * FROM `user` WHERE `recovery` = '$token'";
$dados = $db->query($sql);
$dados = $dados->fetchAll();
if(count($dados) != 1){
  header("Location: ../my/login");
  die;
} else {
  $dados = $dados[0];
}

?>
