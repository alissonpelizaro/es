<?php
include '../core.php';

$novaPri = $_POST["novaPri"];
$cursor = explode("-", $novaPri);
$cont = 1;
foreach ($cursor as $linha) {
	$sql = "UPDATE `fila` SET `priority` = '$cont' WHERE `idFila` = '$linha';";
	if($db->query($sql)){} else {
		header("Location: ../my/filas?priedit=failure");
	}
	$cont++;
}
header("Location: ../my/filas?priedit=success");
?>