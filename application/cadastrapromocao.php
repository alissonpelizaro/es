<?php
include '../core.php';

/*
* ARQUIVO DE CADASTRO DE NOVO TÉCNICO
* CHAMADA VIA FORMULÁRIO
*
*/

$idCasa = $_SESSION['casa'];
$promocao = tratarString($_POST['promocao']);
$valor = tratarString($_POST['valor']);
$veiculacao = tratarString($_POST['veiculacao']);

if($_POST['validade'] != ""){
  $validade = datepickerParaBd(tratarString($_POST['validade']));
} else {
  $validade = "2000-01-01 00:00:00";
}

$data = date("Y-m-d H:i:s");

if(isset($_POST['obs'])){
	$obs = tratarString($_POST['obs']);
} else {
	$obs = "";
}

$sql = "INSERT INTO `promocao` (
  `promocao`,
  `veiculacao`,
  `valor`,
  `dataCadastro`,
  `dataExpiracao`,
  `casa`,
  `obs`
) VALUES (
  '$promocao',
  '$veiculacao',
  '$valor',
  '$data',
  '$validade',
  '$idCasa',
  '$obs'
)";

if($db->query($sql)){
  header("Location: ../my/promocoes?cadastro=success");
} else {
  header("Location: ../my/promocoes?cadastro=failure");
}

?>
