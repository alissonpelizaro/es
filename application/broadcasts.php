<?php
include '../core.php';
$token = geraSenha(15);

$sql = "SELECT * FROM `broadcast` WHERE `status` = '1' AND `setor` = '$setorUser' ORDER BY `data` DESC";
$broadcasts = $db->query($sql);
$broadcasts = $broadcasts->fetchAll();

if(count($broadcasts) == 0){
  $broadcasts = false;
}


$sql = "SELECT `idGrupo`, `nome` FROM `grupo` WHERE `status` = '1' AND `setor` = '$setorUser'";
$grupos = $db->query($sql);
$grupos = $grupos->fetchAll();

function retGruposMural($grupos, $array){
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
    return "Nenhum.";
  } else {
    $retorno = substr($retorno, 0, -2);
    return $retorno.".";
  }
}

function retQtdAgente($str){
  $str = explode('-', $str);
  $aux = 0;
  if(isset($str[0])){
    foreach ($str as $k) {
      if($k != ""){
        $aux++;
      }
    }
  }
  return $aux;
}


?>
