<?php
include "../core.php";

$at = tratarString($_POST['crip'])/7;
$dst = tratarString($_POST['destino']);
$obs = tratarString($_POST['obs']);
$data = date("Y-m-d H:i:s");
$idLogado = $_SESSION['id'];

//Carrega informações do atendimento em questão
$sql = "SELECT * FROM `atendimento` WHERE `idAtendimento` = '$at'";
$at = $db->query($sql);
$at = $at->fetch();
$oldAgt = $at['idAgente'];
$idAtendimento = $at['idAtendimento'];

$sql = "SELECT `nome`, `sobrenome` FROM `user` WHERE `idUser` = '$oldAgt'";
$oldAgt = $db->query($sql);
$oldAgt = $oldAgt->fetch();

if($dst == "agente"){
  $agente = tratarString($_POST['agente']);

  $sql = "SELECT `nome`, `sobrenome` FROM `user` WHERE `idUser` = '$agente'";
  $newAgt = $db->query($sql);
  $newAgt = $newAgt->fetch();
  if(count($newAgt) == 0){
    header("Location: ../my/media?transferencia=unavailable?local=u");
  }

  $msg = "<b class='transferencia'><i>Transferência de atendimento.</i></b><br>Atendimento transferido de ".$oldAgt['nome']." ".$oldAgt['sobrenome']." para ".$newAgt['nome']." ".$newAgt['sobrenome'].".";
  if($obs != ""){
    $msg .= "<br><br><b>Motivo da transferência: </b> ".$obs;
  }


$msg = str_replace("'", '"', $msg);


  $sql = "INSERT INTO `chat_atendimento` (
    `idAtendimento`,
    `chat`,
    `rmt`,
    `visualizada`,
    `dataEnvio`
  ) VALUES (
    '$idAtendimento',
    '$msg',
    'cliente',
    '0',
    '$data'
  )";


  if($db->query($sql)){
    $sql = "UPDATE `atendimento` SET `idAgente` = '$agente' WHERE `idAtendimento` = '$idAtendimento'";
    if($db->query($sql)){

      $log->setAcao('Transferiu atendimento');
      $log->setFerramenta('Medias');
      $log->setObs("Para: " . $newAgt['nome']." ".$newAgt['sobrenome']);
      $log->setAtendimento($idAtendimento);
      $log->gravaLog();

      $log->setAcao('Recebeu transferencia');
      $log->setFerramenta('Medias');
      $log->setObs("De: " . $oldAgt['nome']." ".$oldAgt['sobrenome']);
      $log->setAtendimento($idAtendimento);
      $log->setUser($agente);
      $log->gravaLog();

      header("Location: ../my/media?transferencia=success");
    } else {
      header("Location: ../my/media?transferencia=falha&local=d");
    }
  } else {
    header("Location: ../my/media?transferencia=falha&local=u");
  }

} else if($dst == "fila"){
  $fila = tratarString($_POST['fila']);

  $sql = "SELECT * FROM `fila` WHERE `idFila` = '$fila'";
  $fila = $db->query($sql);
  $fila = $fila->fetch();
  $nomeFila = $fila['nomeFila'];

  $msg = "<b><i>Transferência de atendimento.</i></b><br>Atendimento transferido pelo ".$oldAgt['nome']." ".$oldAgt['sobrenome']." para a fila <b>".$nomeFila."</b>.";

  if($obs != ""){
    $msg .= "<br><br><b>Motivo da transferência: </b> ".$obs;
  }

  $sql = "SELECT `idUser` FROM `user` WHERE `tipo` = 'agente' AND `status` = '1' AND `logged` = '1' AND `idUser` != '$idLogado' AND `filas` LIKE '%"."\\#-".$fila['nomeFila']."-\\#"."%'";
  $users = $db->query($sql);
  $users = $users->fetchAll();
  $tUsers = count($users);
  if($tUsers == 0){
    header("Location: ../my/media?transferencia=unavailable?local=u");
  } else {
    $index = rand(0, $tUsers-1);
    $newAgt = $users[$index];
    $agente = $newAgt['idUser'];

    $sql = "INSERT INTO `chat_atendimento` (
      `idAtendimento`,
      `chat`,
      `rmt`,
      `visualizada`,
      `dataEnvio`
    ) VALUES (
      '$idAtendimento',
      '$msg',
      'cliente',
      '0',
      '$data'
    )";

    if($db->query($sql)){
      $sql = "UPDATE `atendimento` SET `idAgente` = '$agente', `fila` = '$nomeFila' WHERE `idAtendimento` = '$idAtendimento'";
      if($db->query($sql)){

        $log->setAcao('Transferiu atendimento');
        $log->setFerramenta('Medias');
        $log->setObs("Fila: ".$nomeFila);
        $log->setAtendimento($idAtendimento);
        $log->gravaLog();

        $log->setAcao('Recebeu transferência');
        $log->setFerramenta('Medias');
        $log->setObs("De: " . $oldAgt['nome']." ".$oldAgt['sobrenome']);
        $log->setAtendimento($idAtendimento);
        $log->setUser($agente);
        $log->gravaLog();

        header("Location: ../my/media?transferencia=success");
      } else {
        header("Location: ../my/media?transferencia=falha&local=d");
      }
    } else {
      header("Location: ../my/media?transferencia=falha&local=u");
    }

  }
} else {
  header("Location: ../my/media?transferencia=falha&local=i");
}



 ?>
