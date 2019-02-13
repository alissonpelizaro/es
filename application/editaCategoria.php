<?php
include '../core.php';

$id = tratarString($_POST['hash'])/17;
$cat = tratarString($_POST['categoria']);

$sql = "UPDATE `cat_wiki` SET `nomeCat` = '$cat' WHERE `idCat` = '$id'";
if($db->query($sql)){
  $obs = "Categoria: ".$log->retCategoria($id);
  $log->setAcao('Editou o nome de uma categoria');
  $log->setFerramenta('Wiki');
  $log->setObs($obs);
  $log->gravaLog();
  header('Location: ../my/wikicategories?edit=success');
} else {
  header('Location: ../my/wikicategories?edit=failure');
}


?>
