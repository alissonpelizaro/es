<?php

include '../../../coreExt.php';
include '../../Api.php';

if(isset($_POST['auth']) &&
  isset($_POST['token']) &&
  isset($_POST['nome']) &&
  isset($_POST['sobrenome']) &&
  isset($_POST['email']) &&
  isset($_POST['fila'])){

  $auth = tratarString($_POST['auth']);
  $token = tratarString($_POST['token']);
  $nome = tratarString($_POST['nome']);
  $sobrenome = tratarString($_POST['sobrenome']);
  $email = tratarString($_POST['email']);
  $fila = tratarString($_POST['fila']);

  $api = new Api;


  if($api->criaAtendimento($auth, $token, $nome, $sobrenome, $email, $fila, 'enterness')){
    echo "true";
  } else {
    echo "false";
  }

}

?>
