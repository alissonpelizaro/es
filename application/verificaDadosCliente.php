<?php
include '../coreExt.php';

$clientes = tratarString($_POST["dados"]);
$idCliente = tratarString($_POST["id"]);
$return = "true";
$util = new Util();
if(is_array($clientes[0])){
	ECHO "entro";
	foreach ($clientes as $dados) {
		
		if($dados[0] == $idCliente){
			if ($dados != "") {
				
				$nome = $dados[1];
				$nascimento = $dados[2];
				$email = $dados[3];
				$fone1 = $dados[4];
				$fone2 = $dados[5];
				$fone3 = $dados[6];
				$numAtivo = $dados[7];
				$cpf = $dados[8];
				$promocao = $dados[9];
				$rua = $dados[10];
				$bairro = $dados[11];
				$num = $dados[12];
				$cep = $dados[13];
				$estado = $dados[14];
				$complemento = $dados[15];
				$cidade = $dados[16];
				$empresa = $dados[17];
				
				
				
				$sql = "SELECT
								`nome`, `nascimento`, `email`, `fone`, `cpf`, `promocoes`, `rua`,
								`bairro`, `numero`, `cep`, `uf`, `complemento`, `cidade`, `empresa`
							FROM
								`cliente`
							WHERE
								`idCliente` = '$dados[0]'";
				
				
				$dadosCliente = $db->query($sql);
				$dadosCliente = $dadosCliente->fetch();

				$arrayFones = "";
				
				if ($numAtivo == 1) {
					$arrayFones = array(
							array(true, $fone1),
							array(false, $fone2),
							array(false, $fone3)
					);
				}elseif ($numAtivo == 2) {
					$arrayFones = array(
							array(false, $fone1),
							array(true, $fone2),
							array(false, $fone3)
					);
				}elseif ($numAtivo == 3) {
					$arrayFones = array(
							array(false, $fone1),
							array(false, $fone2),
							array(true, $fone3)
					);
				}
				
				$fones = json_encode($arrayFones);
				
				if ($nome != $dadosCliente['nome'] || 
						$util->dataHtmlParaBd($nascimento) != $dadosCliente['nascimento'] || 
						$email != $dadosCliente['email'] ||
						$fones != $dadosCliente['fone'] || 
						$cpf != $dadosCliente['cpf'] || 
						$promocao != $dadosCliente['promocoes'] ||
						$rua != $dadosCliente['rua'] || 
						$bairro != $dadosCliente['bairro'] || 
						$num != $dadosCliente['numero'] ||
						$cep != $dadosCliente['cep'] || 
						$estado != $dadosCliente['uf'] || 
						$complemento != $dadosCliente['complemento'] ||
						$cidade != $dadosCliente['cidade'] || 
						$empresa != $dadosCliente['empresa']) {
					$return = "false";
				}
			}
		}
	}
}else{
	if($clientes[0] == $idCliente){
		if ($clientes != "") {
			
			
			$nome = $clientes[1];
			$nascimento = $clientes[2];
			$email = $clientes[3];
			$fone1 = $clientes[4];
			$fone2 = $clientes[5];
			$fone3 = $clientes[6];
			$numAtivo = $clientes[7];
			$cpf = $clientes[8];
			$promocao = $clientes[9];
			$rua = $clientes[10];
			$bairro = $clientes[11];
			$num = $clientes[12];
			$cep = $clientes[13];
			$estado = $clientes[14];
			$complemento = $clientes[15];
			$cidade = $clientes[16];
			$empresa = $clientes[17];
			
			$sql = "SELECT
								`nome`, `nascimento`, `email`, `fone`, `cpf`, `promocoes`, `rua`,
								`bairro`, `numero`, `cep`, `uf`, `complemento`, `cidade`, `empresa`
							FROM
								`cliente`
							WHERE
								`idCliente` = '$clientes[0]'";
			
			
			$dadosCliente = $db->query($sql);
			$dadosCliente = $dadosCliente->fetch();
			
			$arrayFones = "";
			
			if ($numAtivo == 1) {
				$arrayFones = array(
						array(true, $fone1),
						array(false, $fone2),
						array(false, $fone3)
				);
			}elseif ($numAtivo == 2) {
				$arrayFones = array(
						array(false, $fone1),
						array(true, $fone2),
						array(false, $fone3)
				);
			}elseif ($numAtivo == 3) {
				$arrayFones = array(
						array(false, $fone1),
						array(false, $fone2),
						array(true, $fone3)
				);
			}
			
			$fones = json_encode($arrayFones);
			
			if ($nome != $dadosCliente['nome'] ||
					$util->dataHtmlParaBd($nascimento) != $dadosCliente['nascimento'] ||
					$email != $dadosCliente['email'] ||
					$fones != $dadosCliente['fone'] ||
					$cpf != $dadosCliente['cpf'] ||
					$promocao != $dadosCliente['promocoes'] ||
					$rua != $dadosCliente['rua'] ||
					$bairro != $dadosCliente['bairro'] ||
					$num != $dadosCliente['numero'] ||
					$cep != $dadosCliente['cep'] ||
					$estado != $dadosCliente['uf'] ||
					$complemento != $dadosCliente['complemento'] ||
					$cidade != $dadosCliente['cidade'] ||
					$empresa != $dadosCliente['empresa']) {
						$return = "false";
					}
		}
	}
}

echo $return

?>