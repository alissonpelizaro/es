<?php
include '../core.php';

if (isset($_POST["nome"])) {
	
	$nome = tratarString($_POST["nome"]);
	$fone1 = tratarString($_POST["fone1"]);
	$fone2 = tratarString($_POST["fone2"]);
	$fone3 = tratarString($_POST["fone3"]);
	if (isset($_POST["numAtivo"])) {
		$numAtivo = tratarString($_POST["numAtivo"]);
	}else{
		$numAtivo = 1;
	}
	$nascimento = dateHtmlParaBd($_POST["nascimento"]);
	if($nascimento == "--" || $nascimento == ""){
		$nascimento = 'NULL';
	}else{
		$nascimento = "'".$nascimento."'";
	}
	$email = tratarString($_POST["email"]);
	$cpf = tratarString($_POST["cpf"]);
	$promocoes = $_POST["promocoes"];
	if($promocoes == ""){
		$promocoes = 0;
	}
	$rua = tratarString($_POST["rua"]);
	$numResi = $_POST["numResi"];
	if ($numResi != "") {
		$numResi = "'".tratarString($_POST["numResi"])."'";
	} else {
		$numResi = 'NULL';
	}
	$bairro = tratarString($_POST["bairro"]);
	$cep = tratarString($_POST["cep"]);
	$uf = $_POST["estado"];
	$complemento = tratarString($_POST["complemento"]);
	$empresa = $_POST["clienteEmpresa"];
	if($empresa == ""){
		$empresa = 0;
	}
	$dtRegistro = date("Y-m-d");
	$cidade = tratarString($_POST["cidade"]);
	
	$fones = "";
	if ($numAtivo == 1) {
		if($fone1 != ""){			
			$fones = array(
					array(true, $fone1), 
					array(false, $fone2), 
					array(false, $fone3)
			);
		} else if ($fone2 != "") {
			$fones = array(
					array(false, $fone1),
					array(true, $fone2),
					array(false, $fone3)
			);
		} else if ($fone3 != "") {
			$fones = array(
					array(false, $fone1),
					array(false, $fone2),
					array(true, $fone3)
			);
		}
		
	} else if ($numAtivo == 2) {
		if($fone2 != ""){	
			$fones = array(
					array(false, $fone1),
					array(true, $fone2),
					array(false, $fone3)
			);
		} else if ($fone1 != "") {
			$fones = array(
					array(true, $fone1),
					array(false, $fone2),
					array(false, $fone3)
			);
		} else if ($fone3 != "") {
			$fones = array(
					array(false, $fone1),
					array(false, $fone2),
					array(true, $fone3)
			);
		}
		
	} else if ($numAtivo == 3) {
		if($fone3 != ""){
			$fones = array(
					array(false, $fone1),
					array(false, $fone2),
					array(true, $fone3)
			);
		} else if ($fone1 != "") {
			$fones = array(
					array(true, $fone1),
					array(false, $fone2),
					array(false, $fone3)
			);
		} else if ($fone2 != "") {
			$fones = array(
					array(false, $fone1),
					array(true, $fone2),
					array(false, $fone3)
			);
		}
	}
	
	if($fones != ""){
		$fones = json_encode($fones);	
	}
	
	$sql = "INSERT INTO
						`cliente`
						(`nome`, `fone`, `nascimento`, `email`,
						 `cpf`, `promocoes`, `cep`, `rua`, `numero`, `complemento`,
						 `bairro`, `uf`, `empresa`, `dtRegistro`, `cidade`, `status`)
					VALUES
						('$nome', '$fones', $nascimento, '$email', '$cpf', '$promocoes',
						 '$cep', '$rua', $numResi, '$complemento', '$bairro', '$uf',
						 '$empresa', '$dtRegistro', '$cidade', 'Novo');";
	
	if($db->query($sql)){
		header('Location: ../my/clientes?cadastro=success');
	} else {
		header('Location: ../my/clientes?cadastro=failure');
	}
}

?>