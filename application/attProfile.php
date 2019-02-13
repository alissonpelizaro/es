<?php
include '../core.php';

if($_POST['type'] == 'info'){
  $id = $_SESSION['id'];
  $nome = tratarString($_POST['nome']);
  $sobrenome = tratarString($_POST['sobrenome']);

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
        if($_SESSION['avatar'] != 'default.jpg' && $_SESSION['avatar'] != ""){
          unlink('../my/assets/avatar/'.$_SESSION['avatar']);
        }
        $avatar = $novo_nome;
        $_SESSION['avatar'] = $avatar;
      }
    }
  }

  $sql = "UPDATE `user` SET
  `nome` = '$nome',
  `sobrenome` = '$sobrenome',
  `avatar` = '$avatar' WHERE `idUser` = '$id'";

  if($db->query($sql)){
    $log->setAcao('Atualizou as informações de perfil');
    $log->setFerramenta('MyOmni');
    $log->setObs('Atualizou nome e/ou sobrenome');
    $log->gravaLog();
    header('Location: ../my/profile?update=success');
  } else {
    header('Location: ../my/profile?update=failure');
  }

} else if ($_POST['type'] == 'rmvAvatar'){
  if($_SESSION['avatar'] != 'default.jpg' && $_SESSION['avatar'] != ""){
    unlink('../my/assets/avatar/'.$_SESSION['avatar']);
  }
  $id = $_SESSION['id'];
  $sql = "UPDATE `user` SET `avatar` = '' WHERE `idUser` = '$id'";
  if($db->query($sql)){
    $log->setAcao('Atualizou as informações de perfil');
    $log->setFerramenta('MyOmni');
    $log->setObs('Removeu foto de perfil');
    $log->gravaLog();
    $_SESSION['avatar'] = 'default.jpg';
    echo 1;
  } else {
    echo 0;
  }
} else if($_POST['type'] == 'email'){
  $senha = md5(tratarString($_POST['senha']));
  if($senha != $_SESSION['senha']){
    echo "senha";
  } else {
    $email = tratarString($_POST['email']);
    $id = $_SESSION['id'];
    $sql = "UPDATE `user` SET
    `email` = '$email' WHERE
    `idUser` = '$id'";

    if($db->query($sql)){
      $log->setAcao('Atualizou as informações de perfil');
      $log->setFerramenta('MyOmni');
      $log->setObs('Atualizou e-mail');
      $log->gravaLog();
      echo 1;
    } else {
      echo 0;
    }
  }
} else if($_POST['type'] == 'login'){
  $senha = md5(tratarString($_POST['senha']));
  if($senha != $_SESSION['senha']){
    echo "senha";
  } else {
    $login = tratarString($_POST['login']);
    $id = $_SESSION['id'];
    $sql = "UPDATE `user` SET
    `usuario` = '$login' WHERE
    `idUser` = '$id'";

    if($db->query($sql)){
      $log->setAcao('Atualizou as informações de perfil');
      $log->setFerramenta('MyOmni');
      $log->setObs('Atualizou o login de acesso');
      $log->gravaLog();
      echo 1;
    } else {
      echo 0;
    }
  }
} else if($_POST['type'] == "senha"){
  $senha = md5(tratarString($_POST['senha']));
  if($senha != $_SESSION['senha']){
    echo "senha";
  } else {
    $senha = md5(tratarString($_POST['nova']));
    $id = $_SESSION['id'];
    $sql = "UPDATE `user` SET
    `senha` = '$senha' WHERE
    `idUser` = '$id'";

    if($db->query($sql)){
      $log->setAcao('Atualizou as informações de perfil');
      $log->setFerramenta('MyOmni');
      $log->setObs('Atualizou a senha de acesso');
      $log->gravaLog();
      $_SESSION['senha'] = $senha;
      echo 1;
    } else {
      echo 0;
    }
  }
} else if($_POST['type'] == "reset"){
  $id = tratarString($_POST['hash'])/573;
  $senha = md5(tratarString($_POST['senha']));
  $sql = "UPDATE `user` SET `senha` = '$senha' WHERE `idUser` = '$id'";
  if($db->query($sql)){
    $_SESSION['senha'] = $senha;
    header("Location: ../my/inicio?pass=reseted");
  } else {
    header("Location: ../my/inicio?pass=failure");
  }
}



?>
