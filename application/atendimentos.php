<?php

include '../core.php';

$id = $_SESSION['id'];
$sql = "SELECT `atendimento` FROM `favorito` WHERE `idUser` = '$id'";
$favorito = $db->query($sql);
$favorito = $favorito->fetchAll();
$favorito = $favorito[0][0];

 ?>
