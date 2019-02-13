<?php
include '../core.php';

//echo strftime('1/%m/%Y', strtotime('-1 Month'));
//die();

$ano = date("Y");
$mes = date("m");

$qtdSem = countSemanasMes($ano, $mes);

$diasSemana = array();

$qtdDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
$frstDay = date("w", strtotime($ano."-".$mes."-1"));

$ldia = date("t", strtotime($ano."-".$mes."-01"));
$dataini = $ano."-".$mes."-01";
$datafim = $ano."-".$mes."-".$ldia;
$diasUteis = getWorkingDays($dataini, $datafim);

$idCasa = $_SESSION['casa'];

$sql = "SELECT `idRegra`, `mes`, `ano`, `tint`, `hrat`, `hrats`, `qtas` FROM `regra` WHERE `idCasa` = '$idCasa' ORDER BY `dataCadastro` DESC LIMIT 1";
$regra = $db->query($sql);
$regra = $regra->fetchAll();

if(count($regra) == 0){
  //Não existe nenhuma regra definida
  $regra = false;
} else {

  $regra = $regra[0];
  //Compara ultima regra definida com a data atual epara setar um mês em vigor
  if(date("d") < 20){
    if($regra['mes'] != $mes && $regra['ano'] == $ano){
      $mes = $regra['mes'] + 1;
      if($mes > 12){
        $ano++;
        $mes = 1;
      }
    } else if($regra['mes'] == $mes && $regra['ano'] == $ano){
      header("Location: ../my/inicio");
    } else {
      die("Erro ao trabalhar com as datas na virada do ano");
    }
  } else {
    if($regra['mes'] != $mes && $regra['ano'] == $ano){
      $mes--;
      if($mes == 0){
        $mes = 12;
        $ano--;
      }
    } else if($regra['mes'] == $mes && $regra['ano'] == $ano){
      $mes++;
      if($mes > 12){
        $ano++;
        $mes = 1;
      }
    } else {
      die("Erro ao trabalhar com as datas na virada do ano");
    }
  }


  $tint = $regra['tint'];

  //Soma a qtd de tecnicos
  $sql = "SELECT count(`idUser`) AS `total` FROM `user` WHERE  `ramal` = '$idCasa' AND `tipo` = 'tecnico'";
  $qtdTec = $db->query($sql);
  $qtdTec = $qtdTec->fetchAll();
  $qtdTec = $qtdTec[0]['total'];

  $datas = explode("-", $regra['hrat']);

  $hIni = $datas[0];
  $hAlm = $datas[1];
  $hRet = $datas[2];
  $hFim = $datas[3];

  $tAlm = horaParaSegundos($hRet) - horaParaSegundos($hAlm);
  $tJor = horaParaSegundos($hFim) - horaParaSegundos($hIni);

  $tJor = $tJor - $tAlm;
  $tJor = $tJor/60;
  //echo $tJor;

  //$tJot = segundosParaHora($tJor);
  $qtdSem = countSemanasMes($ano, $mes);

  $diasSemana = array();

  $qtdDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
  $frstDay = date("w", strtotime($ano."-".$mes."-1"));

  $ldia = date("t", strtotime($ano."-".$mes."-01"));
  $dataini = $ano."-".$mes."-01";
  $datafim = $ano."-".$mes."-".$ldia;

  $qtas = $regra['qtas'];

  $diasUteis = getWorkingDays($dataini, $datafim);
  $totalHora = ($diasUteis*$tJor)/60;

  $hF = $regra['hrats'];
  $tJorF = horaParaSegundos($hF) - horaParaSegundos($hIni);
  $tJorF = (($tJorF/60)*$qtas)/60;

  $totalHora = $totalHora + $tJorF;

  $totalRec = ($totalHora * $qtdTec);

  if($tint == 20){
    $totalRec = $totalRec*3;
  } else if($tint == 30){
    $totalRec = $totalRec*2;
  }

  $sql = "UPDATE `casa` SET `at_esp` = '$totalRec' WHERE `idCasa` = '$idCasa'";
  $db->query($sql);

}

// Prepara array da quantidade de sábados
$sabados = array();
$j = 1;
for($i=1; $i <= $qtdDias; $i++){
  if(date("w", mktime(0,0,0,$mes,$i,$ano)) == 6){
    $sabados[$j] = $i."/".retMesAbrev($mes);
    $j++;
  }
}

$domingos = array();
$j = 1;
for($i=1; $i<=$qtdDias; $i++){
  if(date("w", mktime(0,0,0,$mes,$i,$ano)) == 0){
    $domingos[$j] = $i."/".retMesAbrev($mes);
    $j++;
  }
}

// Prepara array para configuração do banco de horas
$frsSem = 1;
$lasSem = 0;
$j = 1;
for($i=1; $i<=$qtdDias; $i++){
  $diaLoop = date("w", mktime(0,0,0,$mes,$i,$ano));
  if($diaLoop == 1){
    $frsSem = $i;
  }
  if($diaLoop > 0 && $diaLoop < 6){
    if($i > $lasSem){
      $lasSem = $i;
    }
    if($diaLoop == 5){
      $banco[$j] = $frsSem ." à ".$lasSem."/".retMesAbrev($mes);
      $j++;
    }
  }
}

//Particularidade da pagina "MINHA CASA" que usa o uitl_regra
$dataIniMinhaCasa = $dataini . " 00:00:00";
$dataFimMinhaCasa = $datafim . " 23:59:59";

function maxData($mes, $ano, $num){
  if($mes + 1 < 13){
    $mes++;
  } else {
    $mes = 1;
    $ano++;
  }

  if($num == 0){
    $dia = date("t", mktime(0,0,0,$mes,'01',$ano));
    $data = $mes . "/" . $dia . "/" . $ano;

    return $data;
  } else {
    return $data = $mes . "/10/" . $ano;
  }
}


function explodeQtdDiaSemana($sem){
  $sem = explode(" à ", $sem);
  if(count($sem) == 0){
    return 0;
  }
  $sem[1] = explode("/", $sem[1]);
  $sem[1] = $sem[1][0];

  return (int) $sem[1] - (int) $sem[0] + 1;
}

function retTecHoraAlmoco($db, $id, $where){
  $sql = "SELECT `filas` FROM `user` WHERE `idUser` = '$id'";
  $hrs = $db->query($sql);
  $hrs = $hrs->fetch();

  if(!isset($hrs['filas'])){
    return '00:00';
  }

  $hrs = explode("-", $hrs['filas']);
  if(count($hrs) != 4){
    return "00:00";
  }

  if($where == 'saida'){
    return $hrs[2];
  } else {
    return $hrs[1];
  }
}

include 'util_regra.php';

?>
