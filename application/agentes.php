<?php
  include '../core.php';

  $token = geraSenha(15);

  $sql = "SELECT * FROM `user` WHERE `tipo` = 'agente' AND `status` = '1' AND `setor` = '$setorUser'";
  $agentes = $db->query($sql);
  $agentes = $agentes->fetchAll();

  if(count($agentes) == 0){
    $agentes = false;
  }

  $sql = "SELECT `nome`, `agentes` FROM `grupo` WHERE `status` = '1'";
  $grupos = $db->query($sql);
  $grupos = $grupos->fetchAll();
  if(count($grupos) == 0){
    $grupos = false;
  }


  function retArrayGrupo($grupos, $id){
    if(!$grupos){
      $retorno =  "Nenhum";
    } else {
      $retorno = "";
      foreach ($grupos as $grupo) {
        if(strpos($grupo['agentes'], $id) !== FALSE){
          $retorno .= $grupo['nome'].", ";
        }
      }
      $retorno = substr($retorno, 0, -2);
      $retorno .= ".";
    }
    if($retorno == "."){
      $retorno = "Nenhum";
    }
    return $retorno;
  }


?>
