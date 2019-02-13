<?php
include '../core.php';

if(isset($_GET['hash'])){
  //Carrega dados da promoção
  $id = tratarString($_GET['hash'])/13;
  $sql = "SELECT * FROM `promocao` WHERE `idPromocao` = '$id'";
  $promocao = $db->query($sql);
  $promocao = $promocao->fetchAll();

  if(count($promocao) == 0){
    header("Location: ../my/promocoes");
  } else {
    $promocao = $promocao[0];
  }


} else {
  //Aplica edição
  $idPromocao = tratarString($_POST['hash'])/13;
  $promocao = tratarString($_POST['promocao']);
  $valor = tratarString($_POST['valor']);
  $veiculacao = tratarString($_POST['veiculacao']);
  $validade = datepickerParaBd(tratarString($_POST['validade']));
  $obs = tratarString($_POST['obs']);

  $sql = "UPDATE `promocao` SET
    `promocao` = '$promocao',
    `veiculacao` = '$veiculacao',
    `valor` = '$valor',
    `dataExpiracao` = '$validade',
    `obs` = '$obs'
  WHERE `idPromocao` = '$idPromocao'";

  if($db->query($sql)){
    header("Location: ../my/promocoes?edit=success");
  } else {
    header("Location: ../my/promocoes?edit=failure");
  }
}


?>
