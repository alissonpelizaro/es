<?php

if(!isset($dataIniMinhaCasa)){
  $dataIniMinhaCasa = date("Y")."-".date("m")."-01" . " 00:00:00";
}

if(!isset($dataFimMinhaCasa)){
  $dataFimMinhaCasa = date("Y-m")."-".date("t", mktime(0,0,0,date("m"),'01',date("Y"))) . " 23:59:59";
}

//Carrega tabela informações dos técnicos.
$sql = "SELECT `idUser`, `nome`, `sobrenome`, `filas`, `ramalTec` FROM `user` WHERE `ramal` = '$idCasa' AND `tipo` = 'tecnico' AND `status` = '1'";
$user = $db->query($sql);
$user = $user->fetchAll();

$tabelaTecnicos = array();
$index = 0;
foreach($user as $use){
  $idUser = $use['idUser'];
  $nome = $use['nome'];
  $ramal = $use['ramalTec'];
  $hAlmoco = horaAlmoco($db, $idUser, $dataIniMinhaCasa, $dataFimMinhaCasa, $idCasa);

  $sql = "SELECT `dataInicio`, `dataFim` FROM `bloqueio` WHERE `idCasa` = '$idCasa' AND `dataInicio` >= '$dataIniMinhaCasa' AND `dataInicio` <= '$dataFimMinhaCasa' AND `idTecnico` = '$idUser' AND `origem` = 'regra' AND`motivo` LIKE '%folga%'";
  $bloc = $db->query($sql);
  $bloc = $bloc->fetchAll();
  $diasDeFolga = "";
  $dias = array("");
  foreach($bloc as $blocqueado){
    $dataHoraInicio = $blocqueado[0];
    $dataHoraFim = $blocqueado[1];

    $dataIni = explode(" ", $dataHoraInicio);
    $dataInicial = $dataIni[0];
    $dataF = explode(" ", $dataHoraFim);
    $dataFim = $dataF[0];
    $diaIni = explode("-", $dataInicial);
    $diaInicio = $diaIni[2];
    $diaF= explode("-", $dataFim);
    $diaFim = $diaF[2];

    if($diaInicio < $diaFim){
      for($i = $diaInicio; $i <= $diaFim; $i++){
        if($dias[0] == ""){
          $dias[0] = $i;
        } else {
          array_push($dias, $i);
        }
      }
    } else {
      $dias[0] = $diaInicio;
    }

    foreach($dias as $dia){
      if($diasDeFolga == ""){
        $diasDeFolga = $dia;
      } else {
        if(strpos($diasDeFolga, $dia) == 0 || strpos($diasDeFolga, ", ".$dia) == 0){
          $diasDeFolga .= ", " . $dia;
        }
      }
    }
  }

  $sql = "SELECT `dataInicio`, `dataFim` FROM `bloqueio` WHERE `idCasa` = '$idCasa' AND `dataInicio` >= '$dataIniMinhaCasa' AND `dataInicio` <= '$dataFimMinhaCasa' AND `idTecnico` = '$idUser' AND `obs` = 'blocpas'";
  $bloc = $db->query($sql);
  $bloc = $bloc->fetchAll();
  $diasBlocPas = "";
  $dias = array("");
  foreach($bloc as $blocqueado){
    $dataHoraInicio = $blocqueado[0];
    $dataHoraFim = $blocqueado[1];

    $dataIni = explode(" ", $dataHoraInicio);
    $dataInicial = $dataIni[0];
    $dataF = explode(" ", $dataHoraFim);
    $dataFim = $dataF[0];
    $diaIni = explode("-", $dataInicial);
    $diaInicio = $diaIni[2];
    $diaF= explode("-", $dataFim);
    $diaFim = $diaF[2];

    if($diaInicio < $diaFim){
      for($i = $diaInicio; $i <= $diaFim; $i++){
        if($dias[0] == ""){
          $dias[0] = $i;
        } else {
          array_push($dias, $i);
        }
      }
    } else {
      $dias[0] = $diaInicio;
    }

    foreach($dias as $dia){
      if($diasBlocPas == ""){
        $diasBlocPas = $dia;
      } else {
        if(strpos($diasBlocPas, $dia) == 0 || strpos($diasBlocPas, ", ".$dia) == 0){
          $diasBlocPas .= ", " . $dia;
        }
      }
    }
  }

  $sql = "SELECT `dataInicio`, `dataFim`, `motivo` FROM `bloqueio` WHERE `dataInicio` >= '$dataIniMinhaCasa' AND `dataInicio` <= '$dataFimMinhaCasa' AND `idCasa` = '$idCasa' AND `idTecnico` = '$idUser' AND `motivo` LIKE '%ferias%'";
  $bloc = $db->query($sql);
  $bloc = $bloc->fetchAll();

  $ferias = "";


  $pri = true;
  foreach($bloc as $blocqueado){
    $dataHoraInicio = $blocqueado[0];
    $dataHoraFim = $blocqueado[1];

    if($pri){
      $dataIni = explode(" ", $dataHoraInicio);
      $dataInicial = $dataIni[0];
      $diaIni = explode("-", $dataInicial);
      $diaInicio = $diaIni[2];
      $mesInicio = nomeMes($diaIni[1]);
      $pri = false;
    }

    $dataF = explode(" ", $dataHoraFim);
    $dataFim = $dataF[0];
    $diaF= explode("-", $dataFim);
    $diaFim = $diaF[2];
    $mesFim = nomeMes($diaF[1]);
    $ferias = $diaInicio . "/" . $mesInicio . " à " . $diaFim . "/" . $mesFim;
  }
  $tabelaTecnicos[$index] = $nome . "~" . $ramal . "~" . $hAlmoco . "~" . $diasDeFolga . "~" . $ferias . "~" . $diasBlocPas;
  $index++;
}

function nomeMes($mesNum){
  $mesNome = "";
  $meses = array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez');
  return $meses[$mesNum - 1];
}

function quantConsultasTec($db, $idCasa){
  //Soma a qtd de tecnicos
  $sql = "SELECT count(`idUser`) AS `total` FROM `user` WHERE  `ramal` = '$idCasa' AND `tipo` = 'tecnico'";
  $qtdTec = $db->query($sql);
  $qtdTec = $qtdTec->fetchAll();
  $qtdTec = $qtdTec[0]['total'];

  if($qtdTec[0] > 0){
    return $qtdTec;
  }
  return 0;
}

function quantTecEmFerias($db, $a, $m, $idCasa){
  $dataIne = $a . "-" . $m . "-01";
  $ldia = date("t", strtotime($dataIne));
  $dataFim = $a . "-" . $m . "-" . $ldia;

  $sql = "SELECT count(`idTecnico`) AS `total` FROM `bloqueio` WHERE `idCasa` = '$idCasa' AND `motivo` LIKE '%ferias%' AND `dataInicio` >= '$dataIne' AND `dataFim` <= '$dataFim' GROUP BY `idTecnico`";

  $qtdTecFerias = $db->query($sql);
  $qtdTecFerias = $qtdTecFerias->fetchAll();
  if(!isset($qtdTecFerias[0])){
    return 0;
  }
  $qtdTecFerias = $qtdTecFerias[0]['total'];

  if($qtdTecFerias[0] > 0){
    return $qtdTecFerias;
  }
  return 0;
}

function quantPlanPorSabado($db, $a, $m, $idCasa){
  $sql = "SELECT `qpps` FROM `regra` WHERE `idCasa` = '$idCasa' AND `mes` = '$m' AND `ano` = '$a' ORDER BY `dataCadastro` DESC LIMIT 1";
  $regra = $db->query($sql);
  $regra = $regra->fetchAll();
  $regra = $regra[0];

  if(count($regra) > 0){
    return $regra['qpps'];
  }
  return 0;
}

function quantTecMec($db, $a, $m, $idCasa){
  $sql = "SELECT `qtm` FROM `regra` WHERE `idCasa` = '$idCasa' AND `mes` = '$m' AND `ano` = '$a' ORDER BY `dataCadastro` DESC LIMIT 1";
  $regra = $db->query($sql);
  $regra = $regra->fetchAll();
  $regra = $regra[0];

  if(count($regra) > 0){
    return $regra['qtm'];
  }
  return 0;
}

function horaAlmoco($db, $id, $dataIne, $dataFim, $idCasa){
  $sql = "SELECT `dataInicio`, `dataFim` FROM `bloqueio` WHERE `idTecnico` = '$id' AND `dataInicio` >= '$dataIne' AND `dataFim` <= '$dataFim' AND `motivo` = 'almoco'";
  $horaAlmoco = $db->query($sql);
  $horaAlmoco = $horaAlmoco->fetchAll();

  if(isset($horaAlmoco[0])){
    $horaAlmoco = $horaAlmoco[0];

    $horaIne = explode(" ", $horaAlmoco[0]);
    $horaFim = explode(" ", $horaAlmoco[1]);

    return substr($horaIne[1], 0, 5) . " às " . substr($horaFim[1], 0, 5);
  }

  $sql = "SELECT `filas` FROM `user` WHERE `idUser` = '$id'";
  $jor = $db->query($sql);
  $jor = $jor->fetch();

  try {

    $jor = explode("##", $jor['filas']);
    $jor = explode("-", $jor['0']);
    return $jor[1]." às ".$jor[2];

  } catch (\Exception $e) {
    return "12:00 às 13:00";
  }
}

?>
