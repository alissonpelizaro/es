<?php
include '../core.php';

$sql = "SELECT * FROM `atalho` WHERE `setor` = '$setorUser'";
$atalho = $db->query($sql);
$atalho = $atalho->fetchAll();

$atalhos = array();
for($i = 0; $i < 10; $i++){
	$atalhos[$i] = "";
	foreach ($atalho as $dados) {
		if($dados["atalho"] == $i){
			$atalhos[$i] = $dados["texto"];
		}
	}
}

$atualizar = false;


if(isset($_POST["c0"])){
	$sql = "DELETE FROM `atalho` WHERE `setor` = '$setorUser'";
	$db->query($sql);	
}

for ($i = 0; $i < 10; $i++) {
	if(isset($_POST["c".$i])){
		if($_POST["c".$i] != ""){
			$texto = tratarString($_POST["c".$i]);
			
			$sql = "INSERT INTO `atalho` (`texto`, `atalho`, `setor`) VALUES ('$texto', $i, '$setorUser')";
			if($db->query($sql)){
				$sucesso = true;
			} else {
				$sucesso = false;
			}
		} else {
			$sucesso = true;
		}
		$atualizar = true;
	}
}

if($atualizar){
	if($sucesso){
		header("location: ../my/mensagens-padrao?salvar=success");
	} else {
		header("location: ../my/mensagens-padrao?salvar=failure");
	}
}



?>