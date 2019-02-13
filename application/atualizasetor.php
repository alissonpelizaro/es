<?php
  include '../core.php';

  $id = tratarString($_POST['hash'])/37;
  $nome = tratarString($_POST['nome']);
  $modulos = "";

  if(isset($_POST['checkCons'])){
    $modulos .= "-conc-";
  }
  if(isset($_POST['checkMural'])){
    $modulos .= "-mural-";
  }
  if(isset($_POST['checkBroad'])){
    $modulos .= "-broad-";
  }
  if(isset($_POST['checkWiki'])){
    $modulos .= "-wiki-";
  }
  if(isset($_POST['checkMedia'])){
    $modulos .= "-media-";
  }

  $sql = "UPDATE `setor` SET `nome` = '$nome', `modulos` = '$modulos' WHERE `idSetor` = '$id'";
  $nome = "Setor: ".$log->retSetor($id);

  if($db->query($sql)){
    $log->setAcao('Atualizou o nome de um setor');
    $log->setFerramenta('Setores');
    $log->setObs($nome);
    $log->gravaLog();
    header('Location: ../my/setores?edicao=success');
  } else {
    header('Location: ../my/setores?edicao=failure');
  }

 ?>
