<?php
  include '../core.php';

  $id = tratarString($_POST['hash'])/37;
  $nome = tratarString($_POST['nome']);

  echo $id . $nome;
  $sql = "UPDATE `grupo` SET `nome` = '$nome' WHERE `idGrupo` = '$id'";
  $nome = "Grupo: ".$log->retGrupo($id);
  if($db->query($sql)){
    $log->setAcao('Atualizou o nome de um grupo');
    $log->setFerramenta('Grupos');
    $log->setObs($nome);
    $log->gravaLog();
    header('Location: ../my/grupos?edicao=success');
  } else {
    header('Location: ../my/grupos?edicao=failure');
  }

 ?>
