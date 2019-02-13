<?php
include "../core.php";

$idCasa = $_SESSION['casa'];

$sql = "SELECT * FROM `casa` WHERE `idCasa` = '$idCasa'";
$casa = $db->query($sql);
$casa = $casa->fetch();

 ?>
