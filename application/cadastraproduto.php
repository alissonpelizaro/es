<?php
include '../core.php';

/*
* ARQUIVO DE CADASTRO DE NOVO TÉCNICO
* CHAMADA VIA FORMULÁRIO
*
*/

$idCasa = $_SESSION['casa'];
$produto = tratarString($_POST['produto']);
$valor = tratarString($_POST['valor']);
$veiculacao = tratarString($_POST['veiculacao']);
$obs = tratarString($_POST['obs']);
$data = date("Y-m-d H:i:s");

$sql = "INSERT INTO `produto` (
  `produto`,
  `veiculacao`,
  `valor`,
  `dataCadastro`,
  `obs`,
  `casa`
) VALUES (
  '$produto',
  '$veiculacao',
  '$valor',
  '$data',
  '$obs',
  '$idCasa'
)";

if($db->query($sql)){
  header("Location: ../my/produtos?cadastro=success");
} else {
  header("Location: ../my/produtos?cadastro=failure");
}

?>
