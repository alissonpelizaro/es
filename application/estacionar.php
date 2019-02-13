<?php
include '../core.php';

if(isset($_GET['hash']) && isset($_GET['token'])){

	$atId = $_GET['hash']/253;

	$time = "";

	if (isset($_POST["notificacao"])) {
		$data = $_POST["notificacao"];
		$hora = $_POST["hora"];

		$data = $util->dataHtmlParaBd($data, $hora);
		if($data != ""){
			$time = " ,`dataRet` = '$data'";
		}
	}

	//Colocar atendimento de pendencia
	$sql = "UPDATE `atendimento` SET `pendente` = 1$time WHERE `idAtendimento` = '$atId'";

	if($db->query($sql)){
		
		$log->setAcao('Estacionou atendimento');
		$log->setFerramenta('Medias');
		$log->setAtendimento($atId);
		$log->gravaLog();

		header("location: ../my/media?estacionar=success");
	} else {//$_GET['token']
		header("location: ../my/media?hash=".$_GET['hash']."&token=".$_GET['token']."&estacionar=failure");
	}

}
?>
