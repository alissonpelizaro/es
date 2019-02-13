<?php
include '../core.php';

if(isset($_GET['hash'])){
  //Carrega dados da promoção
  $id = tratarString($_GET['hash'])/13;
  $sql = "SELECT * FROM `produto` WHERE `idProduto` = '$id'";
  $produto = $db->query($sql);
  $produto = $produto->fetchAll();

  if(count($produto) == 0){
    header("Location: ../my/produtos");
  } else {
    $produto = $produto[0];
  }


} else {
  //Aplica edição
  $idProduto = tratarString($_POST['hash'])/13;
  $produto = tratarString($_POST['produto']);
  $valor = tratarString($_POST['valor']);
  $veiculacao = tratarString($_POST['veiculacao']);
  $obs = tratarString($_POST['obs']);

  $sql = "UPDATE `produto` SET
    `produto` = '$produto',
    `veiculacao` = '$veiculacao',
    `valor` = '$valor',
    `obs` = '$obs'
  WHERE `idProduto` = '$idProduto'";

  if($db->query($sql)){
    header("Location: ../my/produtos?edit=success");
  } else {
    header("Location: ../my/produtos?edit=failure");
  }
}


?>
