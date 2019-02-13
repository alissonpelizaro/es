<?php
include '../core.php';

$sql = "SELECT * FROM `lembrete` WHERE `idUser` = '$idUser' AND `status` = '1'";
$lembretes = $db->query($sql);
$lembretes = $lembretes->fetchAll();

if(count($lembretes) == 0){
  $lembretes = false;
}

$sql = "SELECT `lembrete` FROM `favorito` WHERE `idUser` = '$idUser'";
$favorito = $db->query($sql);
$favorito = $favorito->fetchAll();
$favorito = $favorito[0][0];

?>
