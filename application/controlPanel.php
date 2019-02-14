<?php
include '../core.php';

try {
	$saved = false;

	if(isset($_POST['tab'])){

		if($_POST['tab'] == 'generals'){

			$prior = tratarString($_POST['prior']);
			$transf = tratarString($_POST['transf']);
			$transb = tratarString($_POST['transb']);
			$exibirFilas = tratarString($_POST['exibirFilas']);

			$limiteIni = tratarString($_POST['horaIni']);
			$limiteFim = tratarString($_POST['horaFim']);
			$limiteResp = tratarString($_POST['limiteResp']);

			if($limiteIni && $limiteResp){
				$limiteHr = $limiteIni.','.$limiteFim;
			} else {
				$limiteHr = "";
				$limiteResp = "";
			}

			if(isset($_POST['checkTransb'])){
				$transb = tratarString($_POST['transb']);
				if($transb == ""){
					$transb = 0;
				}
			} else {
				$transb = 0;
			}

			$bVindas = tratarString($_POST['boas-vindas']);

			$sql = "UPDATE `config` SET
			`prioridade` = '$prior',
			`transf` = '$transf',
			`saudacao` = '$bVindas',
			`transb` = '$transb',
			`exibirFilas` = '$exibirFilas',
			`limite` = '$limiteHr',
			`limiteResp` = '$limiteResp'
			WHERE `idConfig` = 1";

			if($db->query($sql)){
				$saved = true;
			}

		} else if($_POST['tab'] == 'integration') {
			if($_POST['where'] == 'followise'){

				if(isset($_POST['checkFollowise']) && $_POST['checkFollowise'] == 1){
					$status = 1;
				} else {
					$status = 0;
				}

				$followiseSettings = json_encode(
					array(
						'status' => $status,
						'api' => tratarString($_POST['api']),
						'tokenClient' => tratarString($_POST['client']),
						'tokenTeam' => tratarString($_POST['team']),
						'tipo' => tratarString($_POST['tipo'])
					)
				);

				$sql = "UPDATE `config` SET
				`followise` = '$followiseSettings'
				WHERE `idConfig` = 1";

				if($db->query($sql)){
					$saved = true;
				}

			} else if($_POST['where'] == 'enterness'){
				$saved = true;
			}

		} else if($_POST['tab'] == 'clientes') {

			if (isset($_FILES["fileClientes"]) && $_FILES["fileClientes"]["size"] > 0) {
				$statusImportação = TRUE;
				$csv = TRUE;

				$listaClientes = $_FILES["fileClientes"];
				$nomeArq = $listaClientes["name"];
				$extencaoArq = explode(".", $nomeArq);
				if (isset($extencaoArq[1])) {
					$extencaoArq = $extencaoArq[1];
				}else{
					$extencaoArq = "";
				}

				if ($extencaoArq == "csv" || $extencaoArq == "CSV") {

					$dadosClientes = Array();
					$temp = $listaClientes['tmp_name'];
					$file = fopen("$temp", 'r');
					while (($line = fgetcsv($file)) !== false){
						$dadosClientes[] = $line;
					}
					fclose($file);

					if (count($dadosClientes)) {
						$sql = "SELECT * FROM `cliente`";
						$clientesBd = $db->query($sql);
						$clientesBd = $clientesBd->fetchAll();
					}

					if (isset($_POST["manter"])) {
						$manter = 0;
					}else {
						$manter = 1;
					}

					for ($i = 1; $i < count($dadosClientes); $i++) {
						$dadosCliente = explode(";", $dadosClientes[$i][0]);

						if ($dadosCliente[0] != "") {
							if (foneBd(trataFone($dadosCliente[1])) == "") {
								if (validaEmail($dadosCliente[3]) != "") {

									$insert = TRUE;

									foreach ($clientesBd as $clienteBd) {

										if ($dadosCliente[3] == $clienteBd["email"]) {
											$dados = $clienteBd;
											$insert = FALSE;
										}
									}

									if($insert){
										$statusImportação = insertCliente($dadosCliente, $db);
									}else{
										if ($manter) {
											$statusImportação = updateCliente($dadosCliente, $db, $dados);
										}else{
											$statusImportação = updateMaterCliente($dadosCliente, $db, $dados);
										}
									}
								}
							}else{
								$insert = TRUE;
								foreach ($clientesBd as $clienteBd) {

									if (foneBd(trataFone($dadosCliente[1])) == $clienteBd["fone"]) {
										$dados = $clienteBd;
										$insert = FALSE;
									}else if ($dadosCliente[3] == $clienteBd["email"] && $dadosCliente[3] != "") {
										$dados = $clienteBd;
										$insert = FALSE;
									}

								}
								if($insert){
									$statusImportação = insertCliente($dadosCliente, $db);
								}else{
									if ($manter) {
										$statusImportação = updateCliente($dadosCliente, $db, $dados);
									}else{
										$statusImportação = updateMaterCliente($dadosCliente, $db, $dados);
									}
								}
							}
						}
						if(!$statusImportação){
							$i = count($dadosClientes);
						}
					}
				}else{
					$csv = FALSE;
				}

			}
			if (isset($_POST['ativo'])) {
				$ativo = 1;
			}else{
				$ativo = 0;
			}

			$sql = "UPDATE
			`setor`
			SET
			`ativo` = '$ativo'
			WHERE
			`idSetor` = '$setorUser'";

			if($db->query($sql)){
				$saved = true;
			}
		}
	}

	$sql = "SELECT * FROM `config` WHERE `idConfig` = 1";
	$conf = $db->query($sql);
	$conf = (object) $conf->fetch();

	$limiteConf = (object) array(
		'status' => 0,
		'inicio' => "",
		'fim' => "",
		'resposta' => ""
	);

	if($conf->limite){
		$hrs = explode(",",$conf->limite);

		$limiteConf->status = 1;
		$limiteConf->inicio = $hrs[0];
		$limiteConf->fim = $hrs[1];
		$limiteConf->resposta = $conf->limiteResp;
	}

	$sql = "SELECT `ativo` FROM `setor` WHERE `idSetor` = '$setorUser'";
	$ativo = $db->query($sql);
	$ativo = $ativo->fetch();
	$ativo = $ativo[0];

	$sql = "SELECT * FROM `licenca` WHERE `chave` = '1'";
	$lic = $db->query($sql);
	$lic = $lic->fetchAll();
	$lic = $lic[0];

	if (isset($_POST['setor'])) {
		$idSetor = tratarString($_POST['setor']);
		$sql = "UPDATE `user` SET `setor` = '$idSetor' WHERE `idUser` = '$idUser'";
		if($db->query($sql)){
			header("Location: ../my/controlPanel?setor=atualizado");
		}
	}

	//Carrega setores cadastrados
	$sql = "SELECT `idSetor`, `nome` FROM `setor`";
	$setores = $db->query($sql);
	$setores = $setores->fetchAll();

} catch (Exception $e) {
	die($e);
}

function updateMaterCliente($dadosCliente, $db, $dados) {

	if($dados["nome"] == "" || $dados["nome"] == NULL){
		$nome = tratarString($dadosCliente[0]);
	}else{
		$nome = $dados["nome"];
	}

	if($dados["fone"] == "" || $dados["fone"] == NULL){
		$fone = foneBd(trataFone(tratarString($dadosCliente[1])));
	}else{
		$fone = $dados["fone"];
	}
	$fone = array(
		array(true, $fone),
		array(false, ""),
		array(false, "")
	);

	if($dados["nascimento"] == "" || $dados["nascimento"] == NULL){
		if($dadosCliente[2] == "" || $dadosCliente[2] == NULL){
			$nascimento = "NULL";
		}else{
			$nascimento = "'".tratarString(dtBd($dadosCliente[2]))."'";
		}
	}else{
		$nascimento = "'".$dados["nascimento"]."'";
	}

	if($dados["email"] == "" || $dados["email"] == NULL){
		$email = validaEmail(tratarString($dadosCliente[3]));
	}else{
		$email = $dados["email"];
	}

	if($dados["cpf"] == "" || $dados["cpf"] == NULL){
		$cpf = cpfBd(trataCpf(tratarString($dadosCliente[4])));
	}else{
		$cpf = $dados["cpf"];
	}

	if($dados["promocoes"] == "" || $dados["promocoes"] == NULL){
		if ($dadosCliente[5] == "" || $dadosCliente[5] == NULL) {
			$promocoes = 0;
		}else {
			$promocoes = tratarString($dadosCliente["promocoes"]);
		}
	}else{
		$promocoes = $dados[6];
	}

	if($dados["rua"] == "" || $dados["rua"] == NULL){
		$rua = tratarString($dadosCliente[6]);
	}else{
		$rua = $dados["rua"];
	}

	if($dados["numero"] == "" || $dados["numero"] == NULL){
		if ($dadosCliente[7] == "" || $dadosCliente[7] == NULL) {
			$num = "NULL";
		}else {
			$num = "'".tratarString($dadosCliente[7])."'";
		}
	}else{
		$num = "'".$dados["numero"]."'";
	}

	if($dados["bairro"] == "" || $dados["bairro"] == NULL){
		$bairro = tratarString($dadosCliente[8]);
	}else{
		$bairro = $dados["bairro"];
	}

	if($dados["cep"] == "" || $dados["cep"] == NULL){
		$cep = cepBd(trataCep(tratarString($dadosCliente[9])));
	}else{
		$cep = $dados["cep"];
	}

	if($dados["uf"] == "" || $dados["uf"] == NULL){
		$uf = strtoupper(tratarString($dadosCliente[10]));
	}else{
		$uf = strtoupper($dados["uf"]);
	}

	if($dados["complemento"] == "" || $dados["complemento"] == NULL){
		$complemento = tratarString($dadosCliente[11]);
	}else{
		$complemento = $dados["complemento"];
	}

	if($dados["cidade"] == "" || $dados["cidade"] == NULL){
		$cidade = tratarString($dadosCliente[12]);
	}else{
		$cidade = $dados["cidade"];
	}

	if($dados["empresa"] == "" || $dados["empresa"] == NULL){
		if($dadosCliente[13] == "" || $dadosCliente[13] == NULL){
			$empresa = 0;
		}else{
			$empresa = tratarString($dadosCliente[13]);
		}
	}else{
		$empresa = $dados["empresa"];
	}

	$id = $dados["idCliente"];

	$sql = "UPDATE
	`cliente`
	SET
	`nome` = '$nome',
	`fone` = '$fone',
	`nascimento` = $nascimento,
	`email` = '$email',
	`cpf` = '$cpf',
	`promocoes` = '$promocoes',
	`cep` = '$cep',
	`rua` = '$rua',
	`numero` = $num,
	`complemento` = '$complemento',
	`bairro` = '$bairro',
	`uf` = '$uf',
	`empresa` = '$empresa',
	`cidade` = '$cidade'
	WHERE
	`idCliente` = '$id'";

	if($db->query($sql)){
		return TRUE;
	}else{
		return FALSE;
	}
}

function updateCliente($dadosCliente, $db, $dados) {

	if($dadosCliente[0] == "" || $dadosCliente[0] == NULL){
		$nome = $dados["nome"];
	}else{
		$nome = tratarString($dadosCliente[0]);
	}

	if($dadosCliente[1] == "" || $dadosCliente[1] == NULL){
		$fone = $dados["fone"];
	}else{
		$fone = foneBd(trataFone(tratarString($dadosCliente[1])));
	}
	$fone = array(
		array(true, $fone),
		array(false, ""),
		array(false, "")
	);

	if($dadosCliente[2] == "" || $dadosCliente[2] == NULL){
		if($dados["nascimento"] == "" || $dados["nascimento"] == NULL){
			$nascimento = "NULL";
		}else{
			$nascimento = "'".$dados["nascimento"]."'";
		}
	}else{
		$nascimento = "'".tratarString(dtBd($dadosCliente[2]))."'";
	}

	if($dadosCliente[3] == "" || $dadosCliente[3] == NULL){
		$email = $dados["email"];
	}else{
		$email = validaEmail(tratarString($dadosCliente[3]));
	}

	if($dadosCliente[4] == "" || $dadosCliente[4] == NULL){
		$cpf = $dados["cpf"];
	}else{
		$cpf = cpfBd(trataCpf(tratarString($dadosCliente[4])));
	}

	if($dadosCliente[5] == "" || $dadosCliente[5] == NULL){
		if ($dados["promocoes"] == "" || $dados["promocoes"] == NULL) {
			$promocoes = 0;
		}else {
			$promocoes = $dados["promocoes"];
		}
	}else{
		$promocoes = tratarString($dadosCliente[5]);
	}

	if($dadosCliente[6] == "" || $dadosCliente[6] == NULL){
		$rua = $dados["rua"];
	}else{
		$rua = tratarString($dadosCliente[6]);
	}

	if($dadosCliente[7] == "" || $dadosCliente[7] == NULL){
		if ($dados["numero"] == "" || $dados["numero"] == NULL) {
			$num = "NULL";
		}else {
			$num = "'".$dados["numero"]."'";
		}
	}else{
		$num = "'".tratarString($dadosCliente[7])."'";
	}

	if($dadosCliente[8] == "" || $dadosCliente[8] == NULL){
		$bairro = $dados["bairro"];
	}else{
		$bairro = tratarString($dadosCliente[8]);
	}

	if($dadosCliente[9] == "" || $dadosCliente[9] == NULL){
		$cep = $dados["cep"];
	}else{
		$cep = cepBd(trataCep(tratarString($dadosCliente[9])));
	}

	if($dadosCliente[10] == "" || $dadosCliente[10] == NULL){
		$uf = strtoupper($dados["uf"]);
	}else{
		$uf = strtoupper(tratarString($dadosCliente[10]));
	}

	if($dadosCliente[11] == "" || $dadosCliente[11] == NULL){
		$complemento = $dados["complemento"];
	}else{
		$complemento = tratarString($dadosCliente[11]);
	}

	if($dadosCliente[12] == "" || $dadosCliente[12] == NULL){
		$cidade = $dados["cidade"];
	}else{
		$cidade = tratarString($dadosCliente[12]);
	}

	if($dadosCliente[13] == "" || $dadosCliente[13] == NULL){
		if($dados["empresa"] == "" || $dados["empresa"] == NULL){
			$empresa = 0;
		}else{
			$empresa = $dados["empresa"];
		}
	}else{
		$empresa = tratarString($dadosCliente[13]);
	}

	$id = $dados["idCliente"];

	$sql = "UPDATE
	`cliente`
	SET
	`nome` = '$nome',
	`fone` = '$fone',
	`nascimento` = $nascimento,
	`email` = '$email',
	`cpf` = '$cpf',
	`promocoes` = '$promocoes',
	`cep` = '$cep',
	`rua` = '$rua',
	`numero` = $num,
	`complemento` = '$complemento',
	`bairro` = '$bairro',
	`uf` = '$uf',
	`empresa` = '$empresa',
	`cidade` = '$cidade'
	WHERE
	`idCliente` = '$id'";

	if($db->query($sql)){
		return TRUE;
	}else{
		return FALSE;
	}
}

function insertCliente($dadosCliente, $db) {
	$nome = tratarString($dadosCliente[0]);
	$fone = foneBd(trataFone(tratarString($dadosCliente[1])));
	$fone = array(
		array(true, $fone),
		array(false, ""),
		array(false, "")
	);
	if($dadosCliente[2] == ""){
		$nascimento = "NULL";
	}else{
		$nascimento = "'".tratarString(dtBd($dadosCliente[2]))."'";
	}
	$email = validaEmail(tratarString($dadosCliente[3]));
	$cpf = cpfBd(trataCpf(tratarString($dadosCliente[4])));
	if ($dadosCliente[5] == "") {
		$promocoes = 0;
	}else {
		$promocoes = tratarString($dadosCliente[5]);
	}
	$rua = tratarString($dadosCliente[6]);
	if($dadosCliente[7] == ""){
		$num = "NULL";
	}else{
		$num = "'".tratarString($dadosCliente[7])."'";
	}
	$bairro = tratarString($dadosCliente[8]);
	$cep = cepBd(trataCep(tratarString($dadosCliente[9])));
	$uf = strtoupper(tratarString($dadosCliente[10]));
	$complemento = tratarString($dadosCliente[11]);
	$cidade = tratarString($dadosCliente[12]);
	if ($dadosCliente[13] == "") {
		$empresa = 0;
	}else {
		$empresa = tratarString($dadosCliente[13]);
	}
	$hoje = date("Y-m-d");

	$sql = "INSERT INTO
	`cliente`(`nome`, `fone`, `nascimento`,
		`email`, `cpf`, `promocoes`,
		`cep`, `rua`, `numero`,
		`complemento`, `bairro`,
		`uf`, `empresa`, `dtRegistro`,
		`cidade`, `status`)
		VALUES
		('$nome','$fone',$nascimento,'$email',
			'$cpf','$promocoes','$cep','$rua',$num,
			'$complemento','$bairro','$uf','$empresa',
			'$hoje','$cidade', 'Novo')";


			if($db->query($sql)){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		function dtBd($data) {
			if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $data) || preg_match('/^\d{1,2}\-\d{1,2}\-\d{4}$/', $data)) {
				$data = explode("-", $data);
				if (isset($data[2])) {
					return $data[2]."-".$data[1]."-".$data[0];
				}else{
					$data = explode("/", $data[0]);
					return $data[2]."-".$data[1]."-".$data[0];
				}
			}
			return "";
		}

		function foneBd($fone) {
			$tamanho = strlen($fone);

			if($tamanho == 11){
				return "(".$fone[0].$fone[1].") ".$fone[2].$fone[3].$fone[4].$fone[5].$fone[6]."-".$fone[7].$fone[8].$fone[9].$fone[10];
			}else if($tamanho == 9){
				return "(41) ".$fone[0].$fone[1].$fone[2].$fone[3].$fone[4]."-".$fone[5].$fone[6].$fone[7].$fone[8];
			}else if($tamanho == 8){
				return "(41) 9".$fone[0].$fone[1].$fone[2].$fone[3]."-".$fone[4].$fone[5].$fone[6].$fone[7];
			}
			return "";
		}

		function cpfBd($cpf) {
			$tamanho = strlen($cpf);
			if($tamanho <= 11){
				for ($i = $tamanho; $i < 11; $i++) {
					$cpf = "0".$cpf;
				}
				if ($cpf == '00000000000' ||
				$cpf == '11111111111' ||
				$cpf == '22222222222' ||
				$cpf == '33333333333' ||
				$cpf == '44444444444' ||
				$cpf == '55555555555' ||
				$cpf == '66666666666' ||
				$cpf == '77777777777' ||
				$cpf == '88888888888' ||
				$cpf == '99999999999') {
					return "";
				}else{
					for ($t = 9; $t < 11; $t++) {
						for ($d = 0, $c = 0; $c < $t; $c++) {
							$d += $cpf{$c} * (($t + 1) - $c);
						}
						$d = ((10 * $d) % 11) % 10;
						if ($cpf{$c} != $d) {
							return "";
						}
					}
				}
				return $cpf[0].$cpf[1].$cpf[2].".".$cpf[3].$cpf[4].$cpf[5].".".$cpf[6].$cpf[7].$cpf[8]."-".$cpf[9].$cpf[10];
			}
			return "";
		}

		function cepBd($cep) {
			$tamanho = strlen($cep);
			if($tamanho == 8){
				$cep = $cep[0].$cep[1].".".$cep[2].$cep[3].$cep[4]."-".$cep[5].$cep[6].$cep[7];
				if ($cep == "00.000-000") {
					return "";
				}else {
					return $cep;
				}
			}
			return "";
		}

		function trataCep($cep) {
			$cep = str_replace(" ", "", $cep);
			$cep = str_replace(".", "", $cep);
			$cep = str_replace("-", "", $cep);
			return $cep;
		}

		function trataCpf($cpf) {
			$cpf = str_replace(" ", "", $cpf);
			$cpf = str_replace(".", "", $cpf);
			$cpf = str_replace("-", "", $cpf);
			return $cpf;
		}

		function trataFone($fone) {
			$fone = str_replace(" ", "", $fone);
			$fone = str_replace("(", "", $fone);
			$fone = str_replace(")", "", $fone);
			$fone = str_replace("-", "", $fone);
			return $fone;
		}

		function validaEmail($email) {
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				return $email;
			}else{
				return "";
			}
		}
		?>
