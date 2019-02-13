<?php
include '../core.php';

$page = tratarString($_POST["page"]);
if (isset($_POST["favorito"])) {
	$favorito = tratarString($_POST["favorito"]);
}

switch ($page) {
	case "lembretes":
		$sql = "UPDATE `favorito` SET `lembrete`= '$favorito' WHERE `idUser` = '$idUser'";
		if ($db->query($sql)) {
			if ($favorito) {
				echo "true";
			} else {
				echo "false";				
			}
		} else {
			echo "false";
		}
		break;
		
	case "atendimentos":
		$sql = "UPDATE `favorito` SET `atendimento`= '$favorito' WHERE `idUser` = '$idUser'";
		if ($db->query($sql)) {
			if ($favorito) {
				echo "true";
			} else {
				echo "false";
			}
		} else {
			echo "false";
		}
		break;
		
	case "clientes":
		$sql = "UPDATE `favorito` SET `clientes`= '$favorito' WHERE `idUser` = '$idUser'";
		if ($db->query($sql)) {
			if ($favorito) {
				echo "true";
			} else {
				echo "false";
			}
		} else {
			echo "false";
		}
		break;
		
	case "meusAtendimentos":
		$sql = "UPDATE `favorito` SET `meusAtendimentos`= '$favorito' WHERE `idUser` = '$idUser'";
		if ($db->query($sql)) {
			if ($favorito) {
				echo "true";
			} else {
				echo "false";
			}
		} else {
			echo "false";
		}
		break;

	case "inicio":
		$dados = tratarString($_POST["dados"]);
		$set = "";
		foreach ($dados as $dado) {
			if ($set == "") {
				$set = "`" . $dado[0] . "` = " . $dado[1];
			}else{
				$set .= ", `" . $dado[0] . "` = " . $dado[1];
			}
		}
		
		$sql = "UPDATE `favorito` SET $set WHERE `idUser` = '$idUser'";
		
		if ($db->query($sql)){
			echo "true";
		} else {
			echo "false";
		}
		break;

	case "":
		;
		break;
}

?>