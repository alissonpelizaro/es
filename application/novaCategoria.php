<?php
include '../core.php';

if(!isset($_POST['categoria'])){
  backStart();
  die;
}

$cat = tratarString($_POST['categoria']);
$id = $_SESSION['id'];

$sql = "INSERT INTO `cat_wiki` (`nomeCat`, `status`, `idUser`, `setor`)
  VALUES ('$cat', '1', '$id', '$setorUser')";
if($db->query($sql)){
  $obs = "Categoria: ".$cat;
  $log->setAcao('Cadastrou uma categoria');
  $log->setFerramenta('Wiki');
  $log->setObs($obs);
  $log->gravaLog();
  header('Location: ../my/wikicategories?cadastro=success');
} else {
  header('Location: ../my/wikicategories?cadastro=failure');
}


 ?>
