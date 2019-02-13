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
        $nivel = $agente['tipo'];
      }
    } catch (\Exception $e) {

    }
  } else {
    //é edição!
    //criptografia 19 hash
    $id = tratarString($_POST['hash'])/19;
    $nome = tratarString($_POST['nome']);
    $sobrenome = tratarString($_POST['sobrenome']);
    $nivel = tratarString($_POST['nivel']);

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

    $sql = "UPDATE `user` SET
      `nome` = '$nome',
      `sobrenome` = '$sobrenome',
      `tipo` = '$nivel'";

    if($avatar != ""){
      $sql .= ",
      `avatar` = '$avatar'";
    }

    $sql .= " WHERE `idUser` = '$id'";

    if($db->query($sql)){
      $obs = "Nome: ".$log->retNome($id);
      $log->setAcao('Atualizou informações de um '.$nivel);
      $log->setFerramenta('MyOmni');
      $log->setObs($obs);
      $log->gravaLog();
      header('Location: ../my/'.$nivel.'es?edicao=success');
    } else {
      header('Location: ../my/'.$nivel.'es?edicao=failure');
    }

  }


 ?>
