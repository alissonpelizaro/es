<?php
include '../core.php';

if(!isset($_GET['hash'])){
  backStart();
  die;
}

// Prepara informações da Wiki
$idWiki = tratarString($_GET['hash'])/311;
$sql = "SELECT * FROM `wiki`
  WHERE `idWiki` = '$idWiki'";

$wiki = $db->query($sql);
$wiki = $wiki->fetchAll();

if(count($wiki) == 0){
  backStart();
  die;
} else {
  $wiki = $wiki[0];
}

//Carrega informações do Autor
$idAutor = $wiki['idUser'];
$sql = "SELECT `nome`, `sobrenome` FROM `user` WHERE `iduser` = '$idAutor'";
$autor = $db->query($sql);
$autor = $autor->fetchAll();
if(count($autor) == 0){
  $autor = "Desconhecido";
} else {
  $autor = $autor[0]['nome']." ".$autor[0]['sobrenome'];
}

?>
