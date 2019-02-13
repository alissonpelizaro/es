<?php
  include '../core.php';

  $token = geraSenha(15);

  if(isset($_GET['action'])){

    try {
      $idAgente = tratarString($_GET['action'])/17;

      $sql = "SELECT * FROM `user` WHERE `idUser` = '$idAgente'";
      $agente = $db->query($sql);
      $agente = $agente->fetchAll();

      if(count($agente) == 0){
        backStart();
      } else {

        $agente = $agente[0];
        $tmp = $agente['idUser'];
        $sql = "SELECT `idGrupo` FROM `grupo` WHERE `agentes` LIKE '%$tmp%'";
        $grupos = $db->query($sql);
        $grupos = $grupos->fetchAll();
        $tmp = "-";
        foreach ($grupos as $k) {
          $tmp .= $k['idGrupo']."-";
        }
        $gps = $tmp;
      }
    } catch (\Exception $e) {

    }
  } else {
    //é edição!
    //criptografia 19 hash
    $id = tratarString($_POST['hash'])/19;
    $nome = tratarString($_POST['nome']);
    $sobrenome = tratarString($_POST['sobrenome']);
    $ramal = tratarString($_POST['ramal']);
    $chat = tratarString($_POST['chat']);
    $medias = "";
    $qtdAt = tratarString($_POST['qtdAt']);
    if($qtdAt == ""){
      $qtdAt = 0;
    }

    $medias = "-#-";

    if(isset($_POST['checkboxWhatsapp'])){
      $medias .= "whatsapp-#-";
    }
    if(isset($_POST['checkboxEnterness'])){
      $medias .= "enterness-#-";
    }

    if(isset($_POST['grupos'])){
      $grupos = $_POST['grupos'];
    } else {
      $grupos = array();
    }
    $filas = $_POST['filas'];

    $avatar = $_FILES['avatar'];
    if($avatar['name'] == ""){
      $avatar = "";
    } else {
      $dir = "../my/assets/avatar/";
      if( $avatar['error'] == UPLOAD_ERR_OK ){
        $extensao = pegaExtensao($avatar['name']);
        $novo_nome  = md5(time()).".".$extensao;
        $enviou = move_uploaded_file($_FILES['avatar']['tmp_name'], $dir.$novo_nome);
        if($enviou){
          $avatar = $novo_nome;
        }
      }
    }

    $sql = "SELECT `idGrupo`, `agentes` FROM `grupo` WHERE `agentes` LIKE '%$id%'";
    $tmp = $db->query($sql);
    $tmp = $tmp->fetchAll();
    foreach ($tmp as $linha) {
      $array = $linha['agentes'];
      $grupo = $linha['idGrupo'];
      $array = str_replace($id, '', $array);
      $array = str_replace('--', '-', $array);
      $array = str_replace('--', '-', $array);
      $sql = "UPDATE `grupo` SET `agentes` = '$array' WHERE `idGrupo` = '$grupo'";
      $db->query($sql);
    }

    foreach ($grupos as $grupo) {
      $sql = "SELECT `agentes` FROM `grupo` WHERE `idGrupo` = '$grupo'";
      $agentes = $db->query($sql);
      $agentes = $agentes->fetchAll();
      $agentes = $agentes[0]['agentes'];
      $agentes = str_replace($id, '', $agentes);
      $agentes .= "-".$id."-";
      $agentes = str_replace('--', '-', $agentes);
      $agentes = str_replace('--', '-', $agentes);
      $sql = "UPDATE `grupo` SET `agentes` = '$agentes' WHERE `idGrupo` = '$grupo'";
      $db->query($sql);
    }

    $strFilas = "";
    foreach ($filas as $fila) {
      $strFilas .= "-#-".tratarString($fila)."-#-";
      $strFilas = str_replace("-#--#-", "-#-", $strFilas);
    }

    $sql = "UPDATE `user` SET
      `nome` = '$nome',
      `sobrenome` = '$sobrenome',
      `ramal` = '$ramal',
      `chat` = '$chat',
      `filas` = '$strFilas',
      `midias` = '$medias',
      `qtdAt` = '$qtdAt'";

    if($avatar != ""){
      $sql .= ",
      `avatar` = '$avatar'";
    }

    $sql .= " WHERE `idUser` = '$id'";

    if($db->query($sql)){
      $obs = "Nome: ".$nome." ".$sobrenome;
      $log->setAcao('Editou informações de um agente');
      $log->setFerramenta('MyOmni');
      $log->setObs($obs);
      $log->gravaLog();

      //header('Location: ../my/agentes?edicao=success');
    } else {
      header('Location: ../my/agentes?edicao=failure');
    }

  }


 ?>
