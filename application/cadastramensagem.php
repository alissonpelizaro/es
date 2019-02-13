<?php
include '../core.php';

if(isset($_POST['tipo']) && $_POST['tipo'] == 'nova'){

  $titulo = tratarString($_POST['titulo']);
  $conteudo = tratarString($_POST['conteudo']);
  $criticidade = tratarString($_POST['criticidade']);
  $grupos = $_POST['grupos'];
  $exp = tratarString($_POST['expiracao']);
  $id = $_SESSION['id'];
  $data = date('Y-m-d H:i:s');

  $exp = explode('/', $exp);
  if(count($exp) == 3){
    $exp = $exp[2]."-".$exp[1]."-".$exp[0]." 00:00:00";
  } else {
    $exp = '1000-01-01 00:00:00';
  }

  if(count($grupos) == 0){
    $grupos = "";
  } else {
    $tmp = "";
    foreach ($grupos as $grupo) {
      $tmp .= '-'.$grupo.'-';
    }
    $grupos = tratarString(str_replace('--', '-', $tmp));
    if(strpos($grupos, 'todos')){
      $sql = "SELECT `idGrupo` FROM `grupo` WHERE `status` = '1'";
      $grupos = $db->query($sql);
      $tmp = "";
      foreach ($grupos as $grupo) {
        $tmp .= '-'.$grupo['idGrupo'].'-';
      }
      $grupos = str_replace('--', '-', $tmp);
    }
  }

  $sql = "INSERT INTO `mural` (`idUser`, `titulo`, `desc`, `data`, `expira`,
    `status`, `grupos`, `criticidade`, `setor`) VALUES ('$id', '$titulo', '$conteudo', '$data', '$exp',
    '1', '$grupos', '$criticidade', '$setorUser')";
  if($db->query($sql)){
    $obs = "Mensagem: ".$titulo;
    $log->setAcao('Cadastrou uma mensagem de mural');
    $log->setFerramenta('Mural');
    $log->setObs($obs);
    $log->gravaLog();
    header('Location: ../my/mural?cadastro=success');
  } else {
    header('Location: ../my/mural?cadastro=failure');
  }

} else if(isset($_POST['tipo']) && $_POST['tipo'] == 'edit'){
  //echo 'edita mensagem';
  $hash = tratarString($_POST['hash'])/7;
  $hash = $hash/7;
  $titulo = tratarString($_POST['titulo']);
  $conteudo = tratarString($_POST['conteudo']);
  $criticidade = tratarString($_POST['criticidade']);
  $grupos = $_POST['grupos'];
  $exp = tratarString($_POST['expiracao']);
  $id = $_SESSION['id'];
  $exp = explode('/', $exp);
  if(count($exp) == 3){
    $exp = $exp[2]."-".$exp[1]."-".$exp[0]." 00:00:00";
  } else {
    $exp = '1000-01-01 00:00:00';
  }

  if(count($grupos) == 0){
    $grupos = "";
  } else {
    $tmp = "";
    foreach ($grupos as $grupo) {
      $tmp .= '-'.$grupo.'-';
    }
    $grupos = tratarString(str_replace('--', '-', $tmp));
    if(strpos($grupos, 'todos')){
      $sql = "SELECT `idGrupo` FROM `grupo` WHERE `status` = '1'";
      $grupos = $db->query($sql);
      $tmp = "";
      foreach ($grupos as $grupo) {
        $tmp .= '-'.$grupo['idGrupo'].'-';
      }
      $grupos = str_replace('--', '-', $tmp);
    }
  }

  $sql = "UPDATE `mural` SET
  `titulo` = '$titulo',
  `desc` = '$conteudo',
  `expira` = '$exp',
  `status` = '1',
  `grupos` = '$grupos',
  `criticidade` = '$criticidade',
  `idUserEdit` = '$id'
  WHERE `idMural` = '$hash'";

  if($db->query($sql)){
    $obs = "Mensagem: ".$titulo;
    $log->gravaLog('Editou uma mensagem de mural','MyOmni',$obs);
    header('Location: ../my/mural?edit=success');
  } else {
    header('Location: ../my/mural?edit=failure');
  }

} else {
  if(isset($_GET['form']) && $_GET['form'] == 'edit' && isset($_GET['hash'])){
    $edicao = true;
    $id = tratarString($_GET['hash'])/7;
    $sql = "SELECT * FROM `mural` WHERE `idMural` = '$id'";
    $mural = $db->query($sql);
    $mural = $mural->fetchAll();

    if(count($mural) == 0){
      header("Location: ../my/mural");
    } else {
      $mural = $mural[0];
      $gps = $mural['grupos']; //Grupos alvo
      $expira = explode(' ', dataBdParaHtml($mural['expira']));
      if($expira[0] == '1000-01-01'){
        $expira = "Nenhuma validade";
      } else {
        $expira = $expira[0];
      }
    }

  } else {
    $edicao = false;
    $gps = ""; //Grupos alvo
  }
}


?>
