<?php
include '../core.php';

if (isset($_POST["nome"])) {
	
	$nome = tratarString($_POST["nome"]);
	$fone = tratarString($_POST["fone"]);
	$nascimento = dateHtmlParaBd($_POST["nascimento"]);
	$email = tratarString($_POST["email"]);
	$cpf = tratarString($_POST["cpf"]);
	$promocoes = $_POST["promocoes"];
	$rua = tratarString($_POST["rua"]);
	$numResi = $_POST["numResi"];
	$bairro = tratarString($_POST["bairro"]);
	$cep = tratarString($_POST["cep"]);
	$uf = $_POST["estado"];
	$complemento = tratarString($_POST["complemento"]);
	$empresa = $_POST["clienteEmpresa"];
	$dtRegistro = date("Y-m-d");
	$cidade = tratarString($_POST["cidade"]);
	
	$sql = "INSERT INTO 
						`cliente` 
						(`nome`, `fone`, `nascimento`, `email`,
						 `cpf`, `promocoes`, `cep`, `rua`, `numero`, `complemento`,
						 `bairro`, `uf`, `empresa`, `dtRegistro`, `cidade`) 
					VALUES 
						('$nome', '$fone', '$nascimento', '$email', '$cpf', '$promocoes',
						 '$cep', '$rua', '$numResi', '$complemento', '$bairro', '$uf', 
						 '$empresa', '$dtRegistro', '$cidade');";
	
	if($db->query($sql)){
		header('Location: ../my/clientes?cadastro=success');
	} else {
		header('Location: ../my/clientes?cadastro=failure');
	}
}

?>