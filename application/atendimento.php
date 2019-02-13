<?php
include '../core.php';

$idAtendimento = tratarString($_GET['hash'])/777;

//$sql = "SELECT * FROM `feed_atendimento`
//  WHERE `idAtendimento` = '$idAtendimento'";

$sql = "SELECT
					`feed_atendimento`.`chat`, `feed_atendimento`.`avaliacao`, `feed_atendimento`.`email`,
					`feed_atendimento`.`ta`, `feed_atendimento`.`tmra`, `feed_atendimento`.`tmrc`,
					`user`.`nome`, `user`.`sobrenome`, `atendimento`.`protocolo`
				FROM `feed_atendimento`
				INNER JOIN `atendimento` ON `atendimento`.`idAtendimento` = `feed_atendimento`.`idAtendimento`
				INNER JOIN `user` ON `atendimento`.`idAgente` = `user`.`idUser`
				WHERE `feed_atendimento`.`idAtendimento` = '$idAtendimento'";

$atendimento = $db->query($sql);
$atendimento = $atendimento->fetchAll();
$atendimento = $atendimento[0];
$feed = $util->feedParaChat($atendimento['chat'], $idAtendimento);


?>
