<?php
include '../core.php';

if(!isset($_POST['mtdr'])){

  $sql = "SELECT * FROM `licenca` WHERE `chave` = '1'";
  $config = $db->query($sql);
  $config = $config->fetchAll();
  $config = $config[0];

  if (isset($_POST['setor'])) {
    $idSetor = tratarString($_POST['setor']);
    $sql = "UPDATE `user` SET `setor` = '$idSetor' WHERE `idUser` = '$idUser'";
    if($db->query($sql)){
      header("Location: ../my/controlPanel?setor=atualizado");
    }
  }

  //Carrega setores cadastrados
  $sql = "SELECT `idSetor`, `nome` FROM `setor`";
  $setores = $db->query($sql);
  $setores = $setores->fetchAll();

} else {

  $mtdr = tratarString($_POST['mtdr']);
  $mtmr = tratarString($_POST['mtmr']);
  $mtvr = tratarString($_POST['mtvr']);
  $mtnt = tratarString($_POST['mtnt']);
  $cront = tratarString($_POST['cront']);
  $timeout = tratarString($_POST['timeout']);

  $sql = "UPDATE `licenca` SET
  `mtdr` = '$mtdr',
  `mtmr` = '$mtmr',
  `mtvr` = '$mtvr',
  `mtnt` = '$mtnt',
  `diasCrontab` = '$cront',
  `timeoutSessao` = '$timeout'
  WHERE `chave` = '1'";

  if($db->query($sql)){
    $obs = "Licenças: Coord. (".$mtdr.") / Adm. (".$mtmr.") / Sup. (".$mtvr.") / Agente (".$mtnt.")";
    $log->setAcao('Alterou licenças');
    $log->setFerramenta('MyOmni');
    $log->setObs($obs);
    $log->gravaLog();
    header("Location: ../my/controlPanel?setup=success");
  } else {
    header("Location: ../my/controlPanel?setup=failure");
  }

}


?>
