<?php
/* ARQUIVO DE FUNÇÕES DE SEGURANÇA */

require_once 'licenca.php';
require_once 'Logs.php';
require_once 'Msg.php';

$licenca = new Licenca;
$log = new Logs;

//Checa se o sistema está em manutenção
if($config->environment == "MAINTENANCE"){
  header('Location: ../my/manutencao');
}

function abort(){
  die('A aplicação foi parada por segurança!');
}

function abortAccess(){
  setBlackList(); //Grava o IP do usuário na blacklist
  die('Você não tem acesso direto ao script desse sistema! O seu IP foi banido do nosso servidor.');
}

function checaPermissao($array = array()){
  if(count($array) > 0){
    $user = $_SESSION['tipo'];
    if(!in_array($user, $array)) {
      header('Location: ../my/inicio?access=denied');
    }
  }
}

function checaLicenca($db, $tipo){
  if($tipo == 'coordenador'){
    $k = "mtdr";
  } else if($tipo == 'administrador'){
    $k = "mtmr";
  } else if($tipo == 'supervisor'){
    $k = "mtvr";
  } else if($tipo == 'agente'){
    $k = "mtnt";
  } else {
    return false;
    die;
  }
  $sql = "SELECT `$k` FROM `licenca` WHERE `chave` = '1'";
  $licenca = $db->query($sql);
  $licenca = $licenca->fetchAll();
  $licenca = $licenca[0];

  $sql = "SELECT count(*) AS `total` FROM `user` WHERE `tipo` = '$tipo'";
  $usr = $db->query($sql);
  $usr = $usr->fetchAll();
  $usr = $usr[0];

  if($usr['total'] >= $licenca[$k]){
    return false;
  } else {
    return true;
  }
}

function tratarString($inp){
  if(is_array($inp)){
    return array_map(__METHOD__, $inp);
  }

  if(!empty($inp) && is_string($inp)) {
    return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
  }

  return $inp;
}

function penetrationAbort(){
  $e = 'Houve uma tentativa suspeita de interação com o MyOmni. A aplicação foi parada forçadamente por segurança e o seu IP foi banido no nosso servidor.';
  //Joga IP na blacklist
  setBlackList();
  gravaErro($e);
  die($e);
}

function backStart(){
  header('Location: ../my/inicio');
  die;
}

function gravaErro($erro){
  //Desenvolver função de log de erro aqui
}

function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
  $lmin = 'abcdefghijklmnopqrstuvwxyz';
  $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $num = '1234567890';
  $simb = '!@#$%*-';
  $retorno = '';
  $caracteres = '';
  $caracteres .= $lmin;

  if ($maiusculas) $caracteres .= $lmai;
  if ($numeros) $caracteres .= $num;
  if ($simbolos) $caracteres .= $simb;

  $len = strlen($caracteres);

  for ($n = 1; $n <= $tamanho; $n++) {
    $rand = mt_rand(1, $len);
    $retorno .= $caracteres[$rand-1];
  }

  return $retorno;
}

function setBlackList(){
  //Desenvolver função de blacklist aqui
}


?>
