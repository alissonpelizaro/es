<?php
include '../core.php';

if(isset($_POST['setted'])){
  // Edição de fila cadastrada manualmente
  $id = tratarString($_POST['hash'])/53;
  $setted = tratarString($_POST['setted']);
  $nome = tratarString($_POST['nome']);
  $nomeAtual = tratarString($_POST['nomeAtual']);
  $transbordo = tratarString($_POST['transbordo']);
  if(isset($_POST['status'])){
    $status = '1';
  } else {
    $status = '0';
  }
  if(isset($_POST['statusProtocolo'])){
  	$statusProtocolo = '1';
  } else {
  	$statusProtocolo = '0';
  }

  $sql = "UPDATE `fila` SET
  `nomeFila` = '$nome',
  `transbordo` = '$transbordo',
  `status` = '$status',
  `statusProtocolo` = '$statusProtocolo'
  WHERE `idFila` = '$id'";
  if($db->query($sql)){
    //Remove essa fila de todos os agentes
    $filaTemp = '#-'.$nomeAtual.'-#';
    $sql = "SELECT `idUser`, `filas` FROM `user` WHERE `filas` LIKE '%$filaTemp%'";
    $agentes = $db->query($sql);
    $agentes = $agentes->fetchAll();
    $agtAloc = array();
    foreach ($agentes as $agt) {
      $filas = $agt['filas'];
      $idTemp = $agt['idUser'];
      array_push($agtAloc, $agt['idUser']);
      if(strpos($filas, "#-".$nomeAtual."-#")){
        $filas = str_replace("-#-".$nomeAtual."-#-", "-#-", $filas);
        $filas = str_replace("-#--#-", "-#-", $filas);
        $sql = "UPDATE `user` SET `filas` = '$filas' WHERE `idUser` = '$idTemp'";
        $db->query($sql);
        if(!in_array($idTemp, $agentes)){
          $log->setAcao("Saiu da fila");
          $log->setFerramenta("Medias");
          $log->setObs($nomeAtual);
          $log->setUser($idTemp);
          $log->gravaLog();
        }
      }
    }

    unset($agt);

    //Adiciona a fila aos agentes selecionados
    $agentes = explode("#", $setted);
    foreach ($agentes as $agt) {
      if($agt != ""){
        $sql = "SELECT `filas` FROM `user` WHERE `idUser` = '$agt'";
        $filas = $db->query($sql);
        $filas = $filas->fetchAll();
        if(count($filas) > 0){
          $filas = $filas[0]['filas'];
          $filas .= "-#-".$nome."-#-";
          $filas = str_replace("-#--#-", "-#-", $filas);
          $sql = "UPDATE `user` SET `filas` = '$filas' WHERE `idUser` = '$agt'";
          $db->query($sql);
          if(!in_array($agt, $agtAloc)){
            $log->setAcao("Entrou na fila");
            $log->setFerramenta("Medias");
            $log->setObs($nome);
            $log->setUser($agt);
            $log->gravaLog();
          }
        }
      }
    }
    if($nomeAtual != $nome){
      $sql = "UPDATE `fila` SET `transbordo` = '$nome' WHERE `transbordo` = '$nomeAtual'";
      $db->query($sql);
    }

    $log->setAcao('Editou informações de uma fila');
    $log->setFerramenta('Filas');
    $log->setObs('Fila: '. $nomeAtual);
    $log->gravaLog();

    header("Location: ../my/filas?update=success");
  } else {
    header("Location: ../my/filas?update=failure");
  }

} else {
  // Edição de fila sincronizada
  $id = tratarString($_POST['id']);
  $fantasia = tratarString($_POST['fantasia']);
  $trans = tratarString($_POST['transbordo']);

  if(isset($_POST['status'])){
    $status = '1';
  } else {
    $status = '0';
  }

  $sql = "UPDATE `int_fila` SET `fantasia` = '$fantasia', `status` = '$status', `transbordo` = '$trans' WHERE `idFila` = '$id'";
  if($db->query($sql)){
    header("Location: ../my/filas?update=success");
  } else {
    header("Location: ../my/filas?update=failure");
  }
}

?>
