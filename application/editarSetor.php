<?php
include '../core.php';

if(!isset($_GET['hash'])){
  header("Location: ../my/setores");
}

$idSetor = tratarString($_GET['hash'])/311;

//Carrega informações dos setores
$sql = "SELECT * FROM `setor` WHERE `idSetor` = '$idSetor'";
$setor = $db->query($sql);
$setor = $setor->fetch();


 ?>
