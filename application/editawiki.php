<?php
if(!isset($_GET['hash']) || !isset($_GET['action'])){
  $edit = false;
} else {
  if($_GET['action'] == 'edit'){
    $edit = true;
  } else {
    $edit = false;
  }
}

if($edit){
  $idWiki = tratarString($_GET['hash'])/313;
  $sql = "SELECT * FROM `wiki` WHERE `idWiki` = '$idWiki'";
  $wiki = $db->query($sql);
  $wiki = $wiki->fetchAll();
  if(count($wiki) == 0){
    backStart();
    die;
  } else {
    $wiki = $wiki[0];
  }
}

 ?>
