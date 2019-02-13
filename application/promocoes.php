<?php
include '../core.php';

$idCasa = $_SESSION['casa'];
$data = date('Y-m-d H:i:s');

//Limpa promoções vencidas
$db->query("DELETE FROM `promocao` WHERE `dataExpiracao` <= '$data' AND `dataExpiracao` IS NOT NULL AND `dataExpiracao` != '2000-01-01 00:00:00'");

$sql = "SELECT * FROM `promocao` WHERE `casa` = '$idCasa'";
$promocoes = $db->query($sql);
$promocoes = $promocoes->fetchAll();

 ?>
