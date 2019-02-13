<?php
include '../core.php';

//Pega relação das casas cadastradas
$sql = "SELECT * FROM `casa` WHERE `status` = 1 ORDER BY `nome`";
$casas = $db->query($sql);
$casas = $casas->fetchAll();
$GLOBALS['db'] = $db;

function getGestor($casa){
  $sql = "SELECT `nome`, `sobrenome`, `usuario` FROM `user` WHERE `tipo` = 'gestor' AND `ramal` = '$casa'";
  $gestor = $GLOBALS['db']->query($sql);
  $gestor = $gestor->fetchAll();
  if(count($gestor) > 0){
    return $gestor[0];
  } else {
    $gestor = array();
    $gestor['nome'] = "";
    $gestor['sobrenome'] = "";
    $gestor['usuario'] = "";

    return $gestor;
  }
}

?>
