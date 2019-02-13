<?php 
include '../coreExt.php';

$sql = "SELECT 
					`dataRet`, `remetente`, `nome`, `idAtendimento` 
				FROM 
					`atendimento` 
				WHERE 
					`status` = 0
				AND 
					`idAgente` = '".$_SESSION['id']."'";
$ret = $db->query($sql);
$ret = $ret->fetchAll();

$reposta = "false";
foreach ($ret as $dados) {
	if($dados[0] != "0000-00-00 00:00:00" && $dados[0] != null){
		$reposta = $dados[0].",".$dados[1].",".$dados[2].",".$dados[3].";";	
	}
}

echo $reposta;

?>