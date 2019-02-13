<?php
include '../core.php';

//Carrega informações dos clientes
$sql = "SELECT
					*
				FROM
					`cliente`
				ORDER BY
					`nome`
				ASC";
$clientes = $db->query($sql);
$clientes = $clientes->fetchAll();

$sql = "SELECT `clientes` FROM `favorito` WHERE `idUser` = '$idUser'";
$favorito = $db->query($sql);
$favorito = $favorito->fetchAll();
$favorito = $favorito[0][0];

?>
