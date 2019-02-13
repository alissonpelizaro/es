<?php
include '../core.php';

//Prepara ARRAY com todas as Categorias
$sql = "SELECT * FROM `cat_wiki` WHERE `status` = '1' AND `setor` = '$setorUser'";
$categorias = $db->query($sql);
$categorias = $categorias->fetchAll();
if(count($categorias) == 0){
  $categorias = false;
}

if(isset($_POST['filtro'])){
  $filtro = tratarString($_POST['filtro']);
  $filtro = " AND `setor` = '$setorUser' AND (`titulo` LIKE '%$filtro%' OR `subtitulo` LIKE '%$filtro%' OR `conteudo` LIKE '%$filtro%')";
} else {
  $filtro = " AND `setor` = '$setorUser'";
}


function retArrayWikis($db, $id, $filtro){
  $sql = "SELECT * FROM `wiki` WHERE `idCat` = '$id' $filtro";
  $wikis = $db->query($sql);
  $wikis = $wikis->fetchAll();
  if(count($wikis) == 0){
    $wikis = false;
  }
  return $wikis;
}

?>
