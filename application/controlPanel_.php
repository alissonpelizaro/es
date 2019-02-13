<?php
include '../core.php';

$saved = false;

if(isset($_POST['tab'])){

  if($_POST['tab'] == 'generals'){

    $prior = tratarString($_POST['prior']);
    //$transf = tratarString($_POST['transf']);
    $transf = 0;
    $bVindas = tratarString($_POST['boas-vindas']);

    $sql = "UPDATE `config` SET
    `prioridade` = '$prior',
    `transf` = '$transf',
    `saudacao` = '$bVindas'
    WHERE `idConfig` = 1";

    if($db->query($sql)){
      $saved = true;
    }

  } else if($_POST['tab'] == 'integration'){

  }

}


$sql = "SELECT * FROM `config` WHERE `idConfig` = 1";
$conf = $db->query($sql);
$conf = (object) $conf->fetch();

?>
