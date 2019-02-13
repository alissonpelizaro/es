<?php
include '../core.php';

$id = $_SESSION['id'];
$type = tratarString($_POST['type']);
$titulo = tratarString($_POST['titulo']);
$cat = tratarString($_POST['categoria']);
$sub = tratarString($_POST['subtitulo']);
$icon = tratarString($_POST['icon']);
$conteudo = htmlentities(tratarString($_POST['conteudo']));
$data = date('Y-m-d H:i:s');

//CODE:   htmlentities()
//DECODE: html_entity_decode()
if($type == 'new'){
  $sql = "INSERT INTO `wiki` (`titulo`, `subtitulo`, `conteudo`, `idUser`,
    `dataCadastro`, `idCat`, `dataEdicao`, `logo`, `setor`) VALUES ('$titulo', '$sub', '$conteudo', '$id',
    '$data', '$cat', '1000-01-01 00:00:00', '$icon', '$setorUser')";
    if($db->query($sql)){
      $obs = "Titulo: ".$titulo;
      $log->setAcao('Cadastrou uma nova Wiki');
      $log->setFerramenta('Wiki');
      $log->setObs($obs);
      $log->gravaLog();
      header('Location: ../my/wiki?cadastro=success');
    } else {
      header('Location: ../my/wiki?cadastro=failure');
    }
} else {

  $idWiki = $type/27;
  $nomeEdicao = $_SESSION['nome'];
  $data = date('Y-m-d H:i:s');
  $sql = "UPDATE `wiki` SET
  `titulo` = '$titulo',
  `subtitulo` = '$sub',
  `conteudo` = '$conteudo',
  `idCat` = '$cat',
  `dataEdicao` = '$data',
  `nomeEdicao` = '$nomeEdicao',
  `logo` = '$icon'
  WHERE `idWiki` = '$idWiki'";

  if($db->query($sql)){
    $obs = "Titulo: ".$titulo;
    $log->gravaLog('Editou uma Wiki','Wiki',$obs);
    header('Location: ../my/wiki?edit=success');
  } else {
    header('Location: ../my/wiki?edit=failure');
  }
}



    ?>
