<?php
include '../core.php';
$token = geraSenha(15);
$sql = "SELECT * FROM `mural`
  WHERE `status` = '1' AND `setor` = '$setorUser' ORDER BY `criticidade`, `data` ASC";
$msgs = $db->query($sql);
$msgs = $msgs->fetchAll();

if(count($msgs) == 0){
  $msgs = false;
}

$sql = "SELECT `idUser`, `nome`, `sobrenome` FROM `user` WHERE `status` = '1' AND `setor` = '$setorUser'";
$nomes = $db->query($sql);
$nomes = $nomes->fetchAll();

$sql = "SELECT `idGrupo`, `nome` FROM `grupo` WHERE `status` = '1' AND `setor` = '$setorUser'";
$grupos = $db->query($sql);
$grupos = $grupos->fetchAll();

function retUserMural($nomes, $id){
  $retorno = "Ninguém";
  foreach ($nomes as $usr) {
    if($usr['idUser'] == $id){
      $retorno = $usr['nome'] . " " . $usr['sobrenome'];
    }
  }
  return $retorno;
}

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
    return "Nenhum";
  } else {
    $retorno = substr($retorno, 0, -2);
    return $retorno.".";
  }
}

function retExpMural($data){
  if($data == '1000-01-01 00:00:00'){
    return "Nunca";
  } else {
    $data = explode(' ', $data);
    if(isset($data[1])){
      $data = explode('-', $data[0]);
      return $data[2] . "/" . $data[1] . "/" . $data[0];
    } else {
      return "Nunca";
    }
  }
}

function retCorMuralBox($crit){
  if($crit == '3'){
    return '#58FAD0';
  } else if($crit == '1'){
    return 'red';
  } else {
    return '#045FB4';
  }
}

function retCorMuralTexto($crit){
  if($crit == '3'){
    return 'success';
  } else if($crit == '1'){
    return 'danger';
  } else {
    return 'info';
  }
}

function retCriticidade($k){
  if($k == 1){
    return "alta";
  } else if($k == 2){
    return "normal";
  } else if($k == 3){
    return "baixa";
  } else {
    return false;
  }
}

?>
