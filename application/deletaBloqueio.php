<?php

include '../core.php';

$idTec = tratarString($_POST['idTecDel']);

$dataBlocOld = corrigeData(tratarString($_POST['dataDel']));
$mtvOld = tratarString($_POST['mtvDel']);
$idBlock = tratarString($_POST['idBlock']);
$int = tratarString($_POST['tInt']);
$idsOld = "";

if($mtvOld != 'cont-fora' && $mtvOld != 'fora'){

  //Carrega bloqueios antigos
  $sql = "SELECT `idBloqueio` FROM `bloqueio` WHERE `dataInicio` = '$dataBlocOld'
  AND `motivo` = '$mtvOld' AND `idTecnico` = '$idTec'";
  $blocOld = $db->query($sql);
  $blocOld = $blocOld->fetch();

  if(isset($blocOld['idBloqueio'])){
    $blocOld = $blocOld['idBloqueio'];
    $idsOld .= $blocOld."-";
    $loop = true;

    do {
      $blocOld++;
      $sql = "SELECT `idBloqueio`, `idTecnico`, `motivo` FROM `bloqueio` WHERE `idBloqueio` = '$blocOld'";
      $blocLoop = $db->query($sql);
      $blocLoop = $blocLoop->fetch();

      if($blocLoop['idTecnico'] == $idTec && $blocLoop['motivo'] == $mtvOld."-cont"){
        $idsOld .= $blocLoop['idBloqueio']."-";
      } else {
        $loop = false;
      }
    } while ($loop);

  }

  // Apaga bloqueios antigos
  $idsOld = explode("-", $idsOld);
  foreach ($idsOld as $id) {
    $sql = "DELETE FROM `bloqueio` WHERE `idBloqueio` = '$id'";
    $db->query($sql);
  }

} else {

  $sql = "SELECT * FROM `bloqueio` WHERE `idBloqueio` = '$idBlock'";
  $datas = $db->query($sql);
  $datas = $datas->fetch();

  //$dataBlocOld; == +20 nova data

  $dataIniOld = $datas['dataInicio'];
  $dataFimOld = $datas['dataFim'];

  if(date("Y-m-d H:i:s", strtotime($dataIniOld)) == date("Y-m-d H:i:s", strtotime($dataBlocOld))) {
    $novaData = date("Y-m-d H:i:s", strtotime($dataIniOld ." +$int minutes"));
    $sql = "UPDATE `bloqueio` SET `dataInicio` = '$novaData' WHERE `idBloqueio` = '$idBlock'";
    $db->query($sql);

  } else if(date("Y-m-d H:i:s", strtotime($dataFimOld)) == date("Y-m-d H:i:s", strtotime($dataBlocOld))) {
    $novaData = date("Y-m-d H:i:s", strtotime($dataIniOld ." -$int minutes"));
    $sql = "UPDATE `bloqueio` SET `dataFim` = '$novaData' WHERE `idBloqueio` = '$idBlock'";
    $db->query($sql);

  } else {

    $sql = "UPDATE `bloqueio` SET `dataFim` = '$dataBlocOld' WHERE `idBloqueio` = '$idBlock'";
    $db->query($sql);

    $novaData = date("Y-m-d H:i:s", strtotime($dataBlocOld ." +$int minutes"));

    $idCasa = $datas['idCasa'];
    $mtv = $datas['motivo'];
    $obs = $datas['obs'];
    $origem = $datas['origem'];
    $idTec = $datas['idTecnico'];

    $sql = "INSERT INTO `bloqueio` (`idTecnico`, `idCasa`, `dataInicio`, `dataFim`, `motivo`, `obs`, `origem`)
    VALUES ('$idTec', '$idCasa', '$novaData', '$dataFimOld', '$mtv', '$obs', '$origem')";
    $db->query($sql);
  }

}

function corrigeData($data){
  $data = explode(" ", $data);
  $hora = explode(":", $data[1]);
  if($hora[0] < 10){
    $hora[0] = "0".$hora[0];
  }
  if($hora[1] < 10){
    $hora[1] = "0".$hora[1];
  }

  return $data[0]." ".$hora[0].":".$hora[1].":00";
}

echo "true";

?>
