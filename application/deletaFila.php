<?php

/* CHAMADA VIA AJAX! */

include '../core.php';

if(isset($_POST['hash'])){
  $id = tratarString($_POST['hash'])/217;
  $nome = tratarString($_POST['nome']);

  $sql = "DELETE FROM `fila` WHERE `idFila` = '$id'";
  if($db->query($sql)){
    $tmp = 'Fila: '. $nome;
    $log->setAcao('Apagou uma fila');
    $log->setFerramenta('Filas');
    $log->setObs($tmp);
    $log->gravaLog();

    //Remove essa fila de todos os agentes
    $filaTemp = '#-'.$nome.'-#';
    $sql = "SELECT `idUser`, `filas` FROM `user` WHERE `filas` LIKE '%$filaTemp%'";
    $agentes = $db->query($sql);
    $agentes = $agentes->fetchAll();
    foreach ($agentes as $agt) {
      $filas = $agt['filas'];
      $idTemp = $agt['idUser'];
      if(strpos($filas, "#-".$nome."-#") == TRUE){
        $filas = str_replace("-#-".$nome."-#-", "-#-", $filas);
        $filas = str_replace("-#--#-", "-#-", $filas);
        $sql = "UPDATE `user` SET `filas` = '$filas' WHERE `idUser` = '$idTemp'";
        $db->query($sql);
      }
    }

    echo 1;
  } else {
    echo 0;
  }
} else {
  echo 0;
}

 ?>
