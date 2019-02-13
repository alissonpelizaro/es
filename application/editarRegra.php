<?php
include '../core.php';

$idRegra = $_GET['hash']/313;

$sql = "SELECT * FROM `regra` WHERE `idRegra` = '$idRegra'";
$regra = $db->query($sql);
$regra = $regra->fetchAll();

if(count($regra) == 0){
  header("Location: ../my/inicio?failure=forbiddenId");
}

$regra = $regra[0];
$tint = $regra['tint'];
$idCasa = $regra['idCasa'];

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

//Horario de atendimento sábado

$hrAts = explode("-", $regra['hrats']);

if(count($hrAts) == 0){
  $hrAts[0] = "";
  $hrAts[1] = "";
}

$tAlm = horaParaSegundos($hRet) - horaParaSegundos($hAlm);
$tJor = horaParaSegundos($hFim) - horaParaSegundos($hIni);

$tJor = $tJor - $tAlm;
$tJor = $tJor/60;
//echo $tJor;

//$tJot = segundosParaHora($tJor);

$ano = $regra['ano'];
$mes = $regra['mes'];


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

// Prepara array da quantidade de sábados
$sabados = array();
$j = 1;
for($i=1; $i<=$qtdDias; ++$i){
  if(date("w", mktime(0,0,0,$mes,$i,$ano)) == 6){
    $sabados[$j] = $i."/".retMesAbrev($mes);
    $j++;
  }
}

// Prepara array para configuração do banco de horas
$frsSem = 1;
$lasSem = 0;
$j = 1;
for($i=1; $i<=$qtdDias; ++$i){
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

//---------------------------------------------

//Carrega dados
/*$sql = "SELECT * FROM `regra` WHERE `idCasa` = '$idCasa'";
$dados = $db->query($sql);
$dados = $dados->fetchAll();
$dados = $dados[0];*/

$dados = $regra;

$horarios = explode("-", $dados["hrat"]);

$inicio = $horarios[0];
$almoco = $horarios[1];
$retorno = $horarios[2];
$saida = $horarios[3];
$int = $dados["tint"];

$hrIni = explode(":", $inicio);
$mnIni = (int) $hrIni[1];
$hrIni = (int) $hrIni[0];
if($hrIni < 9){
  $hrIni = 9;
  $mnIni = 0;
}

$hrFim = explode(":", $saida);
$mnFim = (int) $hrFim[1];
$hrFim = (int) $hrFim[0];
if($hrFim > 15){
  $hrFim = 15;
  $mnFim = 0;
}

//Carrega a quantidade de Técnicos
$sql = "SELECT `idUser`, `nome`, `sobrenome` FROM `user` WHERE `status` = 1 AND `ramal` = '$idCasa' AND `tipo` = 'tecnico'";
$tecnicos = $db->query($sql);
$tecnicos = $tecnicos->fetchAll();

$cursor = explode("-", $dados["diasCall"]);
$diasSem = array();
$cont = 0;
$diasJs = "";
foreach($cursor as $dia){
  if($cont == 0){
    $cont = 1;
  } else if($cont != (count($cursor)-1)){
    $diasJs = $diasJs."-".$dia."-";
    array_push($diasSem, $dia);
    $cont++;
  }
}

$sql = "SELECT * FROM `bloqueio` WHERE `idCasa` = '$idCasa' AND `origem` = 'regra'";
$bloc = $db->query($sql);
$bloc = $bloc->fetchAll();

$diaIneFerias;
$diaFimFerias;
$diaIneFolga;
$diaIneBlocPas;
$horaIneAlmoco;
$horaFimAlmoco;
$horaIneBH;
$horaFimBH;
$horaIneBlocFix;
$horaFimBlocFix;

$feriasUser = array();
$feriasUserH = array();
$folgaUser = array();
$folgaUserH = array();
$bancoHoras = array();
$bolcPasUser = array();
$bolcPasUserH = array();
$horaAlmocoUser = array();
$diaIneBH = array();
$horarioBlocFix = array();


foreach($bloc as $bloqueio){
  //Férias
  if($bloqueio["motivo"] == "ferias"){
    $res = explode(" ", $bloqueio["dataInicio"]);
    $diaIneFerias = $res[0];
    $respIne = explode("-", $diaIneFerias);
  } else if($bloqueio["motivo"] == "ferias-cont"){
    $res = explode(" ", $bloqueio["dataFim"]);
    $diaFimFerias = $res[0];
    $respFim = explode("-", $diaFimFerias);
    $feriasUser[$bloqueio["idTecnico"]] = ferias($diaIneFerias, $diaFimFerias);
    $feriasUserH[$bloqueio["idTecnico"]] = $respIne[2]."/".$respIne[1]."/".$respIne[0]." - ".$respFim[2]."/".$respFim[1]."/".$respFim[0];
  }

  //Folga
  if($bloqueio["motivo"] == "folga"){
    $res = explode(" ", $bloqueio["dataInicio"]);
    $diaIneFolga = $res[0];
    $res = explode("-", $diaIneFolga);
    $diaIneFolga = (int) $res[2];
    if(isset($folgaUser[$bloqueio["idTecnico"]])){
      $folgaUser[$bloqueio["idTecnico"]] = $folgaUser[$bloqueio["idTecnico"]].",".$diaIneFolga;
      $folgaUserH[$bloqueio["idTecnico"]] = $folgaUserH[$bloqueio["idTecnico"]].", ".$res[2]."/".$res[1]."/".$res[0];
    }else{
      $folgaUser[$bloqueio["idTecnico"]]=$diaIneFolga;
      $folgaUserH[$bloqueio["idTecnico"]] = $res[2]."/".$res[1]."/".$res[0];
    }
  }

  if($bloqueio["motivo"] == "produtivo"){
    //Bolc. pas.
    if($bloqueio["obs"] == "blocpas"){
      $res = explode(" ", $bloqueio["dataInicio"]);
      $diaIneBlocPas = $res[0];
      $res = explode("-", $diaIneBlocPas);
      $diaIneBlocPas = (int) $res[2];

      if(isset($bolcPasUser[$bloqueio["idTecnico"]])){
        $bolcPasUser[$bloqueio["idTecnico"]] = $bolcPasUser[$bloqueio["idTecnico"]].", ".$diaIneBlocPas;
        $bolcPasUserH[$bloqueio["idTecnico"]]= $bolcPasUserH[$bloqueio["idTecnico"]].", ".$res[2]."/".$res[1]."/".$res[0];
      }else{
        $bolcPasUser[$bloqueio["idTecnico"]] = $diaIneBlocPas;
        $bolcPasUserH[$bloqueio["idTecnico"]] = $res[2]."/".$res[1]."/".$res[0];
      }
    }

    //Pré-adicionar bloqueios fixos nesse mês
    if($bloqueio["obs"] == "blocfix"){
      $res = explode(" ", $bloqueio["dataInicio"]);
      $horaIneBlocFix = $res[1];

      $res = explode(" ", $bloqueio["dataFim"]);
      $horaFimBlocFix = $res[1];

      if(isset($horarioBlocFix[$bloqueio["idTecnico"]])){
        $horarioBlocFix[$bloqueio["idTecnico"]] = $horarioBlocFix[$bloqueio["idTecnico"]] . " / " . $horaIneBlocFix;
      }else{
        $horarioBlocFix[$bloqueio["idTecnico"]] = $horaIneBlocFix;
      }

    }
  }

  //Escala de entrada e saída (banco de horas)
  if($bloqueio["motivo"] == "fora"){
    $res = explode(" ", $bloqueio["dataInicio"]);
    $horaIneBH = $res[1];
    $res = explode("-", $res[0]);
    $diaSemana = date("w", mktime(0,0,0,$res[1],$res[2],$res[0]));

    if($diaSemana != 0 && $diaSemana != 6){
      $resFim = explode(" ", $bloqueio["dataFim"]);
      $horaFimBH = $resFim[1];

      if(isset($bancoHoras[$bloqueio["idTecnico"]])){
        $temporario = $bancoHoras[$bloqueio["idTecnico"]];
      } else {
        $temporario = array();
      }
      array_push($temporario, array(str_replace("0", "", $res[2]), $horaIneBH, $horaFimBH));
      $bancoHoras[$bloqueio["idTecnico"]] = $temporario;
    }
  }

  //Almoço
  if($bloqueio["motivo"] == "almoco"){
    $res = explode(" ", $bloqueio["dataInicio"]);
    $horaIneAlmoco = $res[1];

    $res = explode(" ", $bloqueio["dataFim"]);
    $horaFimAlmoco = $res[1];

    $horaAlmocoUser[$bloqueio["idTecnico"]]=array($horaIneAlmoco, $horaFimAlmoco);
  }
}

/* -- CARREGAS TÉCNICOS EM ESCALA NOS SABADOS  -- */
//Carrega bloqueios do mês da regra selecionada
$sql = "SELECT * FROM `bloqueio` WHERE `dataInicio` >= '$dataIniMinhaCasa' AND `dataFim` <= '$dataFimMinhaCasa' AND `idCasa` = '$idCasa' AND `origem` = 'regra'";
$blocMes = $db->query($sql);
$blocMes = $blocMes->fetchAll();

$escTecnicos = "";
$arrayEscSab = array();

//Pega sabados
$escSab = "";
foreach ($sabados as $sab) {
  $sab = explode("/", $sab);
  $escSab.= "-".$sab[0]."-";
  $arrayEscSab[$sab[0]] = "";
}

//Percorre bloqueios do mes
foreach ($blocMes as $bloc) {
  //Pega dia do bloqueio
  $dia = explode(" ", $bloc['dataInicio']);
  $dia = explode("-", $dia[0]);
  $dia = (int) $dia[2];

  //Checa se dia atual do Loop é sábado
  if(strpos($escSab, "-".$dia."-") !== false){
    if(strpos($escTecnicos, "-".$bloc['idTecnico']."/".$dia."-") === false){
      if($bloc['motivo'] == 'fora' && $bloc['obs'] == 'nesc'){
        //Seta id do técnico como escalado
        $escTecnicos .= "-".$bloc['idTecnico']."/".$dia."-";
        $arrayEscSab[$dia] = "";
      }
    }
  }
}
//Trabalha dados dos técnicos escalados
$escTecnicos = str_replace("--", "-", $escTecnicos);
$escTecnicos = explode("-", $escTecnicos);
foreach ($escTecnicos as $esc) {
  if($esc != ""){
    $escS = explode("/", $esc);
    $arrayEscSab[$escS[1]] .= "-".$escS[0]."-";
  }
}

//---------------------------------------------

function dadosFixedBloc($horarioBlocFix, $user){
  $dados = "[-]";

  foreach($user as $tec){
    if(isset($horarioBlocFix[$tec[0]])){
      $resp = explode(" / ", $horarioBlocFix[$tec[0]]);

      foreach($resp as $frag){
        $frag = str_replace("0:00", "0", $frag);
        $res = explode(":", $frag);

        if($res[0] < 10){
          $res[0] = str_replace("0", "", $res[0]);
          $frag = (int) $res[0] . ":" . (int) $res[1];
        } else {
          $frag = (int) $res[0] . ":" . (int) $res[1];
        }

        if($dados == "[-]"){
          $dados = "[-]".$tec[0]."-".$frag."[-]";

        } else {
          if(strpos($dados, $tec[0]."-".$frag."[-]") === false){
            $dados = $dados.$tec[0]."-".$frag."[-]";
          }
        }
      }
    }
  }
  return $dados;
}

function marcaBlocFix($horarioBlocFix, $getHorarios){

  $resp = explode(" / ", $horarioBlocFix);
  $getHorarios = str_replace(":0", ":00", $getHorarios);

  foreach($resp as $frag){
    $frag = str_replace("0:00", "0", $frag);
    $res = explode(":", $frag);
    if($res[0] < 10){
      $res[0] = str_replace("0", "", $res[0]);
      $frag = $res[0] . ":" . $res[1];
    } else {
      $frag = $res[0] . ":" . $res[1];
    }

    if($frag == $getHorarios){
      return true;
    }
  }
  return false;
}

function imprimeSemana($semana, $bancoHoras){


  $resp = explode(" à ", $semana);

  $diaIni = explode("/", $resp[0]);
  $diaFim = explode("/", $resp[1]);

  foreach($bancoHoras as $dia){
    if($diaIni[0] <= $dia[0] && $dia[0] <= $diaFim[0]){
      return array($dia[1], $dia[2]);
    }
  }

}

function verificaSemana($semana, $bancoHoras){

  $resp = explode(" à ", $semana);

  $diaIni = explode("/", $resp[0]);
  $diaFim = explode("/", $resp[1]);

  foreach($bancoHoras as $dia){
    if($diaIni[0] <= $dia[0] && $dia[0] <= $diaFim[0]){
      return true;
    }
  }
  return false;

}

function ferias($diaIne, $diaFim){
  $meses = array("jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez");

  $cursorIne = explode("-", $diaIne);
  $cursorFim = explode("-", $diaFim);

  return $cursorIne[2]."/".$meses[($cursorIne[1]-1)]." à ".$cursorFim[2]."/".$meses[($cursorFim[1]-1)];
}

function setNumLoop($num){
  if(!is_numeric($num)){
    return "0";
  }
  if((int) $num < 10){
    return "0".$num;
  }
  return $num;
}

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

function retDadosBd($db, $idTec, $sem, $mes, $ano){
  $sem = explode("/", $sem);
  $sem = explode(" à ", $sem[0]);
  $prim = $sem[0];
  $ult = $sem[1];

  $sql = "SELECT `agendamento`, `obs` FROM `banco_horas` WHERE
  `idTecnico` = '$idTec' AND
  `mes` = '$mes' AND
  `ano` = '$ano' AND
  `prim` = '$prim'";
  $dados = $db->query($sql);
  $dados = $dados->fetchAll();
  if(count($dados) == 0){
    return array(
      "ag" => "",
      "obs" => ""
    );
  }
  $dados = $dados[0];

  return array(
    "ag" => $dados['agendamento'],
    "obs" => $dados['obs']
  );
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
