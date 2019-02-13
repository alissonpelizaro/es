<?php
include '../core.php';

if(isset($_POST['idAtendimento'])){
  $idAt = tratarString($_POST['idAtendimento']);
  $palavra = tratarString($_POST['palavras']);

  //Carrega palavrões anteriores
  $sql = "SELECT `restUser` FROM `atendimento` WHERE `idAtendimento` = '$idAt'";
  $ant = $db->query($sql);
  $ant = $ant->fetch();

  $nPalavra = $ant['restUser'];

  if(strpos($palavra, ", ") !== false){
    $palavra = explode(", ", $palavra);
    foreach ($palavra as $k) {
      if($k != ""){
        if(strpos($ant['restUser'], $k) !== false){
          $nPalavra .= ", ".$k;
        }
      }
    }
  } else {

    if($nPalavra != ""){
      $nPalavra .= ", ";
    }
    $nPalavra .= $palavra;

  }


  $sql = "UPDATE `atendimento` SET `restUser` = '$nPalavra', `notifRest` = '1' WHERE `idAtendimento` = '$idAt'";
  $db->query($sql);
}

 ?>
