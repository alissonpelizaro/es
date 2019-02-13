<?php
include '../core.php';

$id = $_POST["idEdit"]/17;
$titulo = tratarString($_POST["titulo"]);
$desc = tratarString($_POST["lembrete"]);
$desc = str_replace("\\r\\n", "<br>", $desc);

$alarme = tratarString($_POST["notificacao"]);
$hora = tratarString($_POST["hora"]);

if($alarme != ""){
	$alarme = $util->dataHtmlParaBd($alarme, $hora);
} else {
	$alarme = "1000-01-01 00:00:00";
}

$page = $_POST["page"];
$sql = "UPDATE 
					`lembrete` 
				SET 
					`titulo`='$titulo', `desc`='$desc', `alarme`='$alarme' 
				WHERE  
					`idLembrete` = '$id'";

if($db->query($sql)){
	header("Location: ../my/$page?edit=success");
} else {
	header("Location: ../my/$page?edit=failure");
}

?>
