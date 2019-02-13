<?php
include '../core.php';

$sql = "SELECT * FROM `grupo` WHERE `status` = '1' AND `setor` = '$setorUser'";
$grupos = $db->query($sql);
$grupos = $grupos->fetchAll();

if(count($grupos) == 0){
  $grupos = false;
}


?>
