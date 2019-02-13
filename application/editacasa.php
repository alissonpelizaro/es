<?php
include '../core.php';

if(isset($_GET['action'])){
  //Carrega inf p/ a view "editacasa"

  //Carrega informações da casa
  $idCasa = tratarString($_GET['action'])/17;
  $sql = "SELECT * FROM `casa` WHERE `idCasa` = '$idCasa'";
  $casa = $db->query($sql);
  $casa = $casa->fetchAll();
  if(count($casa) == 0){
    header("Location: ../my/casas");
  }
  $casa = $casa[0];

  //Carrega informações do GESTOR da casa
  $sql = "SELECT * FROM `user` WHERE `tipo` = 'gestor' AND `ramal` = '$idCasa'";
  $gestor = $db->query($sql);
  $gestor = $gestor->fetchAll();
  if(count($gestor) == 0){
    header("Location: ../my/casas");
  }
  $gestor = $gestor[0];

} else if(isset($_POST['hash'])){
  //É edição

  $id = tratarString($_POST['hash'])/17;
  $nome = tratarString($_POST['nome']);
  $responsavel = tratarString($_POST['responsavel']);
  $endereco = tratarString($_POST['endereco']);
  $bairro = tratarString($_POST['bairro']);
  $cidade = tratarString($_POST['cidade']);
  $estado = tratarString($_POST['estado']);
  $telefone = tratarString($_POST['telefone']);
  $recado = tratarString($_POST['recado']);
  $email = tratarString($_POST['email']);
  $cep = tratarString($_POST['cep']);
  $numResp = tratarString($_POST['numResi']);
  $complemento = tratarString($_POST['complemento']);

  $avatar = tratarString($_POST['icon']);
  if($avatar != ""){
    $avatar .= ".png";
  }

  $sql = "UPDATE `casa` SET
  `nome` = '$nome',
  `responsavel` = '$responsavel',
  `endereco` = '$endereco',
  `bairro` = '$bairro',
  `cidade` = '$cidade',
  `estado` = '$estado',
  `telefone` = '$telefone',
  `recado` = '$recado',
  `cep` = '$cep',
  `numero` = '$numResp',
  `complemento` = '$complemento',
  `email` = '$email'";
  if($avatar != ""){
    $sql .= ", `logo` = '$avatar'";
  }
  $sql .= " WHERE `idCasa` = '$id'";
  //die($sql);
  if($db->query($sql)){
    header("Location: ../my/casas?edicao=success");
  } else {
    header("Location: ../my/casas?edicao=failure");
  }

} else {
  header("Location: ../my/casas");
}

?>
