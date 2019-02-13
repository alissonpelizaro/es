<?php
include '../core.php';
$setted = false;
$idAgente = $_SESSION ['id'];
$atFila = "";

// Se está setado o atendimento e existe token
if (!isset ( $_GET ['hash'] ) || !isset ( $_GET ['token'] )) {
	header("Location: ../my/inicio");
}

// Carrega informações ao atendimento
$at = tratarString ( $_GET ['hash'] ) / 253;
$sql = "SELECT * FROM `atendimento` WHERE `idAtendimento` = '$at'";
$at = $db->query ( $sql );
$at = $at->fetchAll ();
if (count ( $at ) > 0) {
	// Se existe o atendimento setado...
	$at = ( object ) $at [0];
	// Checa se o atendimento é do mesmo agente que está logado
		$atFila = $at->fila;
		$idAgente = $at->idAgente;
		$setted = true;
} else {
	header("Location: ../my/inicio");
}


// Carrega agente
$sql = "SELECT `nome`, `sobrenome`, `avatar` FROM `user` WHERE `idUser` = '$idAgente'";
$agente = $db->query ( $sql );
$agente = $agente->fetch ();

$assistindo = true;


if(($idUser*311) > ($idAgente*311)){
	$hash = ($idAgente*311)."-".($idUser*311);
} else {
	$hash = ($idUser*311)."-".($idAgente*311);
}

?>
