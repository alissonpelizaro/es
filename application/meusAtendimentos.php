<?php
include '../core.php';

$id = $_SESSION["id"];

$sql = "SELECT
						`filas`
					FROM
						`user`
					WHERE
						`idUser` = '$id'";
$filas = $db->query($sql);
$filas = $filas->fetch();

$filas = explode("-#-", $filas["filas"]);
$i = 0;
$j = 0;
$arrayTemp = array();
foreach ($filas as $fila) {
	if ($i != 0 && $i != (count($filas)-1)) {
		$arrayTemp[$j] = $fila;
		$j++;
	}
	$i++;
}
$filas = $arrayTemp;

if (isset($_POST["dtIni"])) {
	
	$where = "";
	
	if ($_POST["dtIni"] != "") {
		$dtIni = tratarString($_POST["dtIni"]);
		$dtIni = dateHtmlParaBd($dtIni);
		if($_POST["horaIni"] != ""){
			$horaIni = tratarString($_POST["horaIni"]);
		}else{
			$horaIni = "00:00";
		}
		$where .= " AND `dataInicio` >= '$dtIni $horaIni:00'";
	}else{
		if($_POST["horaIni"] != ""){
			$dtIni = date("Y-m-d");
			$horaIni = tratarString($_POST["horaIni"]);
			$where .= " AND `dataInicio` >= '$dtIni $horaIni:00'";
		}else{
			$dtIni = date("Y-m-d");
			$where .= " AND `dataInicio` >= '$dtIni 00:00:00'";
		}
	}

	if ($_POST["dtFim"] != "") {
		$dtFim = tratarString($_POST["dtFim"]);
		$dtFim = dateHtmlParaBd($dtFim);
		if($_POST["horaFim"] != ""){
			$horaFim = tratarString($_POST["horaFim"]);
		}else{
			$horaFim = "23:59";
		}
		$where .= " AND `dataFim` <= '$dtFim $horaFim:59'";
	}else{
		if($_POST["horaFim"] != ""){
			$dtFim = date("Y-m-d");
			$horaFim = tratarString($_POST["horaFim"]);
			$where .= " AND `dataFim` <= '$dtFim $horaFim:59'";
		}else{
			$dtFim = date("Y-m-d");
			$where .= " AND `dataFim` <= '$dtFim 23:59:59'";
		}
	}
	
	if ($_POST["origem"] != "") {
		$origem = tratarString($_POST["origem"]);
		$where .= " AND `origem` = '$origem'";
	}
	
	if ($_POST["cliente"] != "") {
		$cliente = tratarString($_POST["cliente"]);
		$where .= " AND `nome` LIKE '%$cliente%'";
	}
	
	if ($_POST["fone"] != "") {
		$fone = tratarString($_POST["fone"]);
		$fone = str_replace("(", "", $fone);
		$fone = str_replace(")", "", $fone);
		$fone = str_replace("-", "", $fone);
		$fone = str_replace(" ", "", $fone);
		$where .= " AND `remetente` LIKE '%$fone%'";
	}
	
	if ($_POST["protocolo"] != "") {
		$protocolo = tratarString($_POST["protocolo"]);
		$where .= " AND `protocolo` = '$protocolo'";
	}
	
	if (isset($_POST["fila"])) {
		$selectFilas = $_POST["fila"];
		$index = 0;
		foreach ($selectFilas as $selectFila) {
			if($index == 0){
				$where .= " AND (`fila` = '$selectFila'";
				$index++;
			}else{
				$where .= " OR `fila` = '$selectFila'";
			}
		}
		$where .= ")";
	}
	
	if (isset($_POST["plataforma"])) {
		$plataformas = $_POST["plataforma"];
		$index = 0;
		foreach ($plataformas as $plataforma) {
			if($index == 0){
				$where .= " AND (`plataforma` = '$plataforma'";
				$index++;
			}else{
				$where .= " OR `plataforma` = '$plataforma'";
			}
		}
		$where .= ")";
	}
	
	$sql = "SELECT
						*
					FROM 
						`atendimento`
					WHERE 
						`idAgente` = '$id'
					AND
						`status` = '1'
					$where
					ORDER BY 
						`dataInicio`";
					
	$atendimentos = $db->query($sql);
	$atendimentos = $atendimentos->fetchAll();
}

$sql = "SELECT `meusAtendimentos` FROM `favorito` WHERE `idUser` = '$idUser'";
$favorito = $db->query($sql);
$favorito = $favorito->fetchAll();
$favorito = $favorito[0][0];

?>