<?php
include '../core.php';

$idCasa = $_SESSION['casa'];
$data = date('Y-m-d H:i:s');

$sql = "SELECT * FROM `produto` WHERE `casa` = '$idCasa'";
$produtos = $db->query($sql);
$produtos = $produtos->fetchAll();

 ?>
