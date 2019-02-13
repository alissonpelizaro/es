<?php

$sql = "SELECT * FROM `atalho` WHERE `setor` = '$setorUser'";
$atalhoDados = $db->query($sql);
$atalhoDados = $atalhoDados->fetchAll();

$teclasAtalhos = array();
for($i = 0; $i < 10; $i++){
	$teclasAtalhos[$i] = "";
	foreach ($atalhoDados as $dados) {
		if($dados["atalho"] == $i){
			$teclasAtalhos[$i] = $dados["texto"];
		}
	}
}

?>

