<?php
include '../core.php';

$sql = "SELECT `pausa` FROM `user` WHERE `idUser` = '$idUser'";
$pausa = $db->query($sql);
$pausa = $pausa->fetch();

if ($pausa["pausa"] == 1) {

	$sql = "UPDATE `user` SET `pausa` = 0 WHERE `idUser` = '$idUser'";
	$db->query($sql);

	$sql = "SELECT `idLog`, `dataLog` FROM `log` WHERE
	`idUsuario` = '$idUser' AND
	`acao` = 'Entrou em pausa'
	ORDER BY `dataLog` DESC LIMIT 1";
	$lastData = $db->query($sql);
	$lastData = $lastData->fetch();

	$diff = strtotime(date('Y-m-d H:i:s')) - strtotime($lastData['dataLog']);

	$log->setAcao('Saiu da pausa');
	$log->setFerramenta('Medias');
	$log->setObs($diff);
	$log->gravaLog();

	$log->atualizaObsLog($lastData['idLog'], $diff);

} else {

	$sql = "UPDATE `user` SET `pausa` = 1 WHERE `idUser` = '$idUser'";
	$db->query($sql);

	$log->setAcao('Entrou em pausa');
	$log->setFerramenta('Medias');
	$log->gravaLog();

}

?>
