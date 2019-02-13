<?php
include '../core.php';

$idTec = tratarString($_POST['tecnico']);

$dataBlocOld = corrigeData(tratarString($_POST['data']));
$mtvOld = tratarString($_POST['mtvOld']);
$idCasa = tratarString($_POST['hash'])/3237;
$idsOld = "";

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


//Salva bloqueios novos
$dataini = tratarString($_POST['dataini']);
$horaini = tratarString($_POST['horaini']);
$datafim = tratarString($_POST['datafim']);
$horafim = tratarString($_POST['horafim']);
$motivo = tratarString($_POST['motivo']);
$descricaomotivo = tratarString($_POST['descricaomotivo']);
$tint = tratarString($_POST['tint']);


if(addBloqueio($db, $idTec, $idCasa, $tint, $dataini, $horaini, $datafim, $horafim, $motivo, $descricaomotivo)){
  header("Location: ../my/detregra?crip=ssl&hash=".$_POST['hash']."&token=none&edit=success#calendario-icone");
} else {
  header("Location: ../my/detregra?crip=ssl&hash=".$_POST['hash']."&token=none&edit=failure#calendario-icone");
}


function addBloqueio($db, $tec, $idCasa, $tint, $dataini, $horaini, $datafim, $horafim, $motivo, $descricaomotivo){

  if($datafim == ""){

    $datafim = $dataini;
    $horafim = explode(":", $horaini);
    if($tint == '20'){
      $horafim[1] = $horafim[1]+20;
      if($horafim[1] >= 60){
        $horafim[0]++;
        $horafim[1] = $horafim[1]-60;
      }
    } else if($tint == 30){
      $horafim[1] = $horafim[1]+30;
      if($horafim[1] >= 60){
        $horafim[0]++;
        $horafim[1] = $horafim[1]-60;
      }
    } else {
      $horafim[0]++;
    }

    $datafim = dateHtmlParaBd($datafim." ".$horafim[0].":".$horafim[1]);
  } else {
    $datafim = dateHtmlParaBd($datafim." ".$horafim);
  }

  $dataini = dateHtmlParaBd($dataini." ".$horaini);

  $dataCompIni = explode(" ", $dataini);
  $dataCompFim = explode(" ", $datafim);

  $horaIni = $dataCompIni[1];
  $horaFim = $dataCompFim[1];

  $dataCompIni = new DateTime($dataCompIni[0]);
  $dataCompFim = new DateTime($dataCompFim[0]);

  $intComp = $dataCompFim->diff($dataCompIni);

  if($intComp->d == 0){
    $sql = "INSERT INTO `bloqueio` (`idTecnico`, `idCasa`, `dataInicio`, `dataFim`, `motivo`, `obs`)
    VALUES ('$tec', '$idCasa', '$dataini', '$datafim', '$motivo', '$descricaomotivo')";

    if($db->query($sql)){
      return true;
    }

  } else {

    $dataini = explode(" ", $dataini);
    $dataini = $dataini[0];

    for($i=0; $i <= $intComp->d; $i++){

      if($i != 0){
        $horaLIni = '00:00:00';
      } else {
        $horaLIni = $horaIni;
      }
      if($i != ($intComp->d)){
        $horaLFim = '23:59:59';
      } else {
        $horaLFim = $horaFim;
      }

      $dataFIni = $dataini." ".$horaLIni;
      $dataFFim = $dataini." ".$horaLFim;

      $sql = "INSERT INTO `bloqueio` (`idTecnico`, `idCasa`, `dataInicio`, `dataFim`, `motivo`, `obs`)
      VALUES ('$tec', '$idCasa', '$dataFIni', '$dataFFim', '$motivo', '$descricaomotivo')";

      $db->query($sql);

      $dataini = date("Y-m-d", strtotime($dataini." +1 days"));
      if(strpos($motivo, "-cont") === false){
        $motivo .= "-cont";
      }

    }
    return true;

  }
  return false;
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

?>
