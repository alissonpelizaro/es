<?php
include '../core.php';

$idAt = tratarString($_POST['hash'])/53;

$sql = "UPDATE `atendimento` SET `notifRest` = 0 WHERE `idAtendimento` = '$idAt'";
$db->query($sql);

 ?>
