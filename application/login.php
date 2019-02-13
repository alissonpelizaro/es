<?php
  include '../coreExt.php';

  if(!isset($_POST['usuario']) || !isset($_POST['senha'])){
    header('Location: ../my/login');
    die;
  }

  $usr = tratarString($_POST['usuario']);
  $pass = md5(tratarString($_POST['senha']));
  $passPure = tratarString($_POST['senha']);

  $sql = "SELECT * FROM `user` WHERE `usuario` = '$usr' AND `senha` = '$pass'";
  $user = $db->query($sql);
  $user = $user->fetchAll();

  if(count($user) == 0){
    header('Location: ../my/login?user=invalid');
  } else {
    $user = $user[0];
    $id =  $user['idUser'];
    $token = geraSenha(10);

    $sql = "SELECT `timeoutSessao` FROM `licenca` WHERE `chave` = '1'";
    $time = $db->query($sql);
    $time = $time->fetchAll();

    $_SESSION['timeout'] = $time[0]['timeoutSessao'];
    $_SESSION['id'] = $id;
    $_SESSION['token'] = $token;
    $_SESSION['nome'] = $user['nome'];
    $_SESSION['senha'] = $user['senha'];
    $_SESSION['tipo'] = $user['tipo'];
    $_SESSION['chat'] = $user['chat'];

    if($user['tipo'] == 'gestor' || $user['tipo'] == 'tecnico'){
      $_SESSION['casa'] = $user['ramal'];
    }

    $sql = "UPDATE `user` SET `token` = '$token', `logged` = '1' WHERE `idUser` = '$id'";
    $db->query($sql);

    if($user['avatar'] == ""){
      $_SESSION['avatar'] = "default.jpg";
    } else {
      $_SESSION['avatar'] = $user['avatar'];
    }
    $_SESSION['hora'] = date('Y-m-d H:i:s');

    $log->setAcao('Logou no sistema');
    $log->setFerramenta('MyOmni');
    $log->gravaLog();

    if($passPure == 'mudar123'){
      header('Location: ../my/novasenha');
    } else {
      header('Location: ../my/inicio');
    }

  }

?>
