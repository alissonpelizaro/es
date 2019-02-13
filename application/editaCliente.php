<?php
include '../core.php';

if(isset($_POST["idCliente"])){
	if ($_POST["nome"] != "") {
		$nome = tratarString($_POST["nome"]);
		$fone1 = tratarString($_POST["fone1"]);
		$fone2 = tratarString($_POST["fone2"]);
		$fone3 = tratarString($_POST["fone3"]);
		$numAtivo = tratarString($_POST["numAtivo"]);

		if ($_POST["nascimento"] != "") {
			$nascimento = "'".dateHtmlParaBd($_POST["nascimento"])."'";
		} else {
			$nascimento = 'NULL';
		}
		$email = tratarString($_POST["email"]);
		$cpf = tratarString($_POST["cpf"]);
		$promocoes = tratarString($_POST["promocoes"]);
		if($promocoes == ""){
			$promocoes = 0;
		}
		$rua = tratarString($_POST["rua"]);
		if ($_POST["numResi"] != "") {
			$numResi = "'".tratarString($_POST["numResi"])."'";
		} else {
			$numResi = 'NULL';
		}
		$bairro = tratarString($_POST["bairro"]);
		$cep = tratarString($_POST["cep"]);
		$uf = tratarString($_POST["estado"]);
		$complemento = tratarString($_POST["complemento"]);
		$empresa = tratarString($_POST["empresa"]);
		if($empresa == ""){
			$empresa = 0;
		}
		$cidade = tratarString($_POST["cidade"]);
		$idCliente = tratarString($_POST["idCliente"]);

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

		$sql = "UPDATE
		`cliente`
		SET
		`nome` = '$nome',
		`fone` = '$fones',
		`nascimento` = $nascimento,
		`email` = '$email',
		`cpf` = '$cpf',
		`promocoes` = '$promocoes',
		`cep` = '$cep',
		`rua` = '$rua',
		`numero` = $numResi,
		`complemento` = '$complemento',
		`bairro` = '$bairro',
		`uf` = '$uf',
		`empresa` = '$empresa',
		`cidade` = '$cidade'
		WHERE
		`idCliente` = '$idCliente'";

		if($db->query($sql)){
			echo "true";
		} else {
			echo "false";
		}
	}
} else if (isset($_GET["hash"])) {

	$hash = $_GET["hash"]/951;

	if (isset($_GET["delete"]) && $_GET["delete"] == "true") {

		$atds = $model->getQuery('cliente', array(
			'status' => 0,
			'idCliente' => $hash
		));
		if(count($atds) > 0){
			$hash = $hash * 951;
			header("Location: ../my/editaCliente?hash=$hash&delete=failure");
			die();
		}

		$sql = "DELETE FROM
		`cliente`
		WHERE
		`idCliente` = $hash";

		if($db->query($sql)){
			header("Location: ../my/clientes?delete=success");
		} else {
			header("Location: ../my/clientes?detele=failure");
		}
	} else {
		//Carrega informações do cliente
		$sql = "SELECT
		*
		FROM
		`cliente`
		WHERE
		`idCliente` = $hash";
		$cliente = $db->query($sql);
		$cliente = $cliente->fetchAll();
		$cliente = $cliente[0];

		$fones = json_decode($cliente["fone"]);

		if (isset($_POST["nome"])) {
			$nome = tratarString($_POST["nome"]);
			$fone1 = tratarString($_POST["fone1"]);
			$fone2 = tratarString($_POST["fone2"]);
			$fone3 = tratarString($_POST["fone3"]);
			$numAtivo = tratarString($_POST["numAtivo"]);
			if ($_POST["nascimento"] != "") {
				$nascimento = "'".dateHtmlParaBd($_POST["nascimento"])."'";
			} else {
				$nascimento = 'NULL';
			}
			$email = tratarString($_POST["email"]);
			$cpf = tratarString($_POST["cpf"]);
			$promocoes = tratarString($_POST["promocoes"]);
			$rua = tratarString($_POST["rua"]);
			$email = tratarString($_POST["email"]);
			$cpf = tratarString($_POST["cpf"]);
			$promocoes = tratarString($_POST["promocoes"]);
			if($promocoes == ""){
				$promocoes = 0;
			}
			$rua = tratarString($_POST["rua"]);
			if ($_POST["numResi"] != "") {
				$numResi = "'".tratarString($_POST["numResi"])."'";
			} else {
				$numResi = 'NULL';
			}
			$bairro = tratarString($_POST["bairro"]);
			$cep = tratarString($_POST["cep"]);
			$uf = tratarString($_POST["estado"]);
			$complemento = tratarString($_POST["complemento"]);
			$empresa = tratarString($_POST["clienteEmpresa"]);
			if($empresa == ""){
				$empresa = 0;
			}
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

			$sql = "UPDATE
			`cliente`
			SET
			`nome` = '$nome',
			`fone` = '$fones',
			`nascimento` = $nascimento,
			`email` = '$email',
			`cpf` = '$cpf',
			`promocoes` = '$promocoes',
			`cep` = '$cep',
			`rua` = '$rua',
			`numero` = $numResi,
			`complemento` = '$complemento',
			`bairro` = '$bairro',
			`uf` = '$uf',
			`empresa` = '$empresa',
			`cidade` = '$cidade'
			WHERE
			`idCliente` = '$hash'";


			if($db->query($sql)){
				$hash = $hash * 951;
				header("Location: ../my/editaCliente?hash=$hash&atualizacao=success");
			} else {
				$hash = $hash * 951;
				header("Location: ../my/editaCliente?hash=$hash&atualizacao=failure");
			}
		}
	}
} else{
	header('Location: ../my/clientes');
}

?>
