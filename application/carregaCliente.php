<?php
include '../coreExt.php';

/*
Pagina que recebe um $_POST['idCliente'] OU um $_POST['idAtendimento'] via AJAX e
imprime um JSON com as informações do cliente.

Imprime "false" caso o cliente não exista
*/

if(isset($_POST['idAtendimento']) && $_POST['idAtendimento']){
  $idAtendimento = tratarString($_POST['idAtendimento']);
  $sql = "SELECT `idCliente` FROM `atendimento` WHERE `idAtendimento` = '$idAtendimento'";
  $idCliente = $db->query($sql);
  $idCliente = $idCliente->fetch();
  if(isset($idCliente['idCliente'])){
    $idCliente = $idCliente['idCliente'];
  } else {
    echo "false";
    die;
  }
} else if(isset($_POST['idCliente']) && $_POST['idCliente']){
  $idCliente = tratarString($_POST['idCliente']);
} else {
  echo "false";
  die;
}

$sql = "SELECT * FROM `cliente` WHERE `idCliente` = '$idCliente'";
$cliente = $db->query($sql);
$cliente = $cliente->fetch();
if(isset($cliente['idCliente'])){
  echo json_encode($cliente);
} else {
  echo "false";
  die;
}
 ?>
