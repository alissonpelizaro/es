<?php
include '../core.php';

//Pega relação dos técnicos cadastrados
$idCasa = $_SESSION['casa'];

$sql = "SELECT * FROM `user` WHERE `tipo` = 'tecnico' AND `status` = '1' AND `ramal` = '$idCasa' ORDER BY `nome`";
$tecnicos = $db->query($sql);
$tecnicos = $tecnicos->fetchAll();

if(count($tecnicos) == 0){
  $tecnicos = false;
}


function setObs($obs){
  $obs = explode("##", $obs);
  $horarios = explode('-', $obs[0]);
  $entrada = $horarios[0];
  $saidaAlmoco = $horarios[1];
  $entradaAlmoco = $horarios[2];
  $saida = $horarios[3];
  $dias = "";

  $obs = explode('-', $obs[1]);

  foreach ($obs as $dia) {
    if($dia == '1'){
      $dias = "Domingo";
    } else if($dia == "2"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Segunda";
    } else if($dia == "3"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Terça";
    } else if($dia == "4"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Quarta";
    } else if($dia == "5"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Quinta";
    } else if($dia == "6"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Sexta";
    } else if($dia == "7"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Sábado";
    }
  }

  return array(
    'dias' => $dias,
    'entrada' => $entrada,
    'saidaAlmoco' => $saidaAlmoco,
    'entradaAlmoco' => $entradaAlmoco,
    'saida' => $saida
  );
  
}



?>
