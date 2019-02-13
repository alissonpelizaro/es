<?php
  include_once '../core.php';

  $sql = "SELECT * FROM `grupo` WHERE `status` = '1' AND `setor` = '$setorUser'";
  $grupos = $db->query($sql);
  $grupos = $grupos->fetchAll();

  if(count($grupos) == 0){
    $grupos = false;
  }

  //Carrega setores cadastrados
  $sql = "SELECT `idSetor`, `nome` FROM `setor`";
  $setores = $db->query($sql);
  $setores = $setores->fetchAll();

  //Carrega filas cadastrados
  $sql = "SELECT * FROM `fila` ORDER BY `priority`";
  $filas = $db->query($sql);
  $filas = $filas->fetchAll();

 ?>
