<?php
include '../core.php';

if(!isset($_GET['token']) || !isset($_GET['hash'])){
  backStart();
  die;
}

$tEnviadas = 0;
$tConfirmadas = 0;
$tPendentes = 0;
$tGrupos = 0;

// Carrega informações da página
$hash = tratarString($_GET['hash'])/13;
$sql = "SELECT * FROM `broadcast` WHERE `idBroadcast` = '$hash'";
$broad = $db->query($sql);
$broad = $broad->fetchAll();

if(count($broad) == 0){
  backStart();
  die;
}

$broad = $broad[0];

//Prepara ARRAY da lista "Enviada para"
$enviadas = explode('-', $broad['destinatarios']);
if(count($enviadas) == 0){
  $enviadas = array();
}

//Prepara ARRAY da lista "Confirmada por"
$confirmadas = explode('-', $broad['confirmacoes']);
if(!isset($confirmadas[0])){
  $confirmadas = array();
}

//Prepara ARRAY da lista "Ainda não confirmada por"
$confTemp = $broad['confirmacoes'];
$pendentes = "-";
foreach ($enviadas as $k) {
  if($k != ""){
    if(strpos($confTemp, $k) === false){
      $pendentes .= $k . "-";
    }
  }
}
$pendentes = explode('-', $pendentes);
if(!isset($pendentes[0])){
  $pendentes = array();
}

//Prepara ARRAY das usuários do sistema
$sql = "SELECT `idUser`, `nome`, `sobrenome`, `avatar`, `ultimoRegistro`, `logged` FROM `user` WHERE `status` = '1'";
$dados = $db->query($sql);
$dados = $dados->fetchAll();
$users = array();
foreach ($dados as $usr) {
  $i = $usr['idUser'];
  $users[$i] = array(
    'nome' => $usr['nome'],
    'sobrenome' => $usr['sobrenome'],
    'lastData' => $usr['ultimoRegistro'],
    'logged' => $usr['logged'],
    'avatar' => $usr['avatar']
  );
}

//Prepara ARRAY dos grupos existentes
$sql = "SELECT `idGrupo`, `nome` FROM `grupo` WHERE `status` = '1'";
$grupos = $db->query($sql);
$grupos = $grupos->fetchAll();


//Função que retorna a string dos nomes dos grupos
function relGruposBroadcast($grupos, $array){
  $retorno = "";
  $array = explode('-', $array);
  foreach ($array as $k) {
    foreach ($grupos as $g) {
      if($g['idGrupo'] == $k){
        $retorno .= $g['nome'] . ", ";
      }
    }
  }
  if($retorno == ""){
    return "todos os agentes";
  } else {
    $retorno = substr($retorno, 0, -2);
    return "os grupos: ". $retorno.".";
  }
}

?>
