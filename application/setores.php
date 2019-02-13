<?php
include '../core.php';

$sql = "SELECT * FROM `setor` WHERE `status` = '1'";
$setores = $db->query($sql);
$setores = $setores->fetchAll();

if(count($setores) == 0){
  $setores = false;
}

function retQtdSetor($db, $id){
  $sql = "SELECT count(`idUser`) AS `total` FROM `user` WHERE `setor` = '$id' AND `status` = '1'";
  $tt = $db->query($sql);
  $tt = $tt->fetch();
  return $tt['total'];
}

?>
