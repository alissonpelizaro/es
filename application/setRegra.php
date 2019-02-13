<?php
include '../core.php';

/*
* Cadastro de nova regra
* Chamada via formulário
*/

set_time_limit(120);

$idCasa = $_SESSION['casa'];
$vigor = explode("-", tratarString($_POST['vigor']));
$mes = $vigor[0];
$ano = $vigor[1];
$lDay = date("t", mktime(0,0,0,$mes,'01',$ano));

//Horario de atendimento dia de semana
$atIni = tratarString($_POST['entrada']);
$atAlm = '12:00';
$atRet = '13:00';
$atSai = tratarString($_POST['saida']);
$atLim = tratarString($_POST['limAg']); // Limite agendamento
$hrat = $atIni . "-" . $atAlm . "-" . $atRet . "-". $atSai;

//Horario de atendimento no sábado
$sAtIni = tratarString($_POST['inicioSabado']);
$sAtFim = tratarString($_POST['fimSabado']);
$sAtLim = tratarString($_POST['limAgSab']);
$sAtQtd = tratarString($_POST['qtdAtendimentoSab']);
$sPlQtd = tratarString($_POST['qpps']); // Quantidade de plantão por sábado
if($sAtIni != "" && $sAtFim != ""){
  $horarioAtendimento = $sAtIni."-".$sAtFim;
} else {
  $horarioAtendimento = "";
}

//Escala dos sábados
$diaSabado = array();
$escSabado = array();
$i = 0;
$sabados = explode("[-]", tratarString($_POST['arraySabados']));
foreach ($sabados as $sab) {
  if($sab != "" && isset($_POST[$sab])){
    $aux = explode("-", $sab);
    $diaSabado[$i] = $aux[1];
    $escSabado[$aux[1]] = tratarString($_POST[$sab]);
    $i++;
  }
}

//Regras gerais
$qtdParc = tratarString($_POST['quantidadeParcelas']); //Qtd de parcelas
$intAg = tratarString($_POST['intervalo']); //Intervalo de agendamentos
$qtdDd = tratarString($_POST['qtdd']); //Qtd de diagnósticos por dia
$qtdMec = tratarString($_POST['qtm']); //Qtd de técnicos mecânicos
$recDay = tratarString($_POST['recall']); //Dia do Recal. ("" = sem recal)

//Bloqueios fixos de Técnicos
$fixedBloc = explode("[-]", tratarString($_POST['fixedBlocks']));

//Escala de entrada e saída (banco de horas)
//Prepara dados
$escDados = explode("<->", tratarString($_POST['escDados']));
$diasSem = explode("[-]", $escDados[0]);
$tecs = explode("-", $escDados[1]);
$bancoHoras = array();

//Pega dados
$i = 0;
$j = 0;
foreach ($diasSem as $sem) {
  if($sem != ""){
    foreach ($tecs as $tec) {
      if($tec != ""){
        $indexInicio = "inicioBancoH-".$i."-".$tec;
        $indexFim = "saidaBancoH-".$i."-".$tec;
        $indexAg = "agendamentoBancoH-".$i."-".$tec;
        $indexObs = "obsBancoH-".$i."-".$tec;
        $bancoHoras[$j] = array(
          'semana' => $sem,
          'tecnico' => $tec,
          'inicio' => tratarString($_POST[$indexInicio]),
          'fim' => tratarString($_POST[$indexFim]),
          'agendamento' => tratarString($_POST[$indexAg]),
          'obs' => tratarString($_POST[$indexObs])
        );
        $j++;
      }
    }
    $i++;
  }
}

//Pega dados da tabela Escala de horário
$escHorario = array();
$i = 0;
foreach ($tecs as $tec) {
  if($tec != ""){
    $indexAlmocoEntrada = "inHorarioAlmocoEnt-".$tec;
    $indexAlmocoSaida = "inHorarioAlmocoSai-".$tec;
    $indexFolgas = "folgas-".$tec;
    $indexFerias = "ferias-".$tec;
    $indexBlock = "blocPas-".$tec;

    $escHorario[$i] = array(
      'tecnico' => $tec,
      'almocoEntrada' => tratarString($_POST[$indexAlmocoEntrada]),
      'almocoSaida' => tratarString($_POST[$indexAlmocoSaida]),
      'folgas' => tratarString($_POST[$indexFolgas]),
      'ferias' => tratarString($_POST[$indexFerias]),
      'block' => tratarString($_POST[$indexBlock])
    );
    $i++;
  }
}

//Pega dias em que a casa não irá trabalhar
$diasCal = tratarString($_POST['diasCall']);
$diasCal = str_replace('--', '-', $diasCal);

//Pega intenções de agendamento
$int1 = checaValueNull(tratarString($_POST['int1']));
$ext1 = checaValueNull(tratarString($_POST['ext1']));
$pas1 = checaValueNull(tratarString($_POST['pas1']));
$int2 = checaValueNull(tratarString($_POST['int2']));
$ext2 = checaValueNull(tratarString($_POST['ext2']));
$pas2 = checaValueNull(tratarString($_POST['pas2']));
$int3 = checaValueNull(tratarString($_POST['int3']));
$ext3 = checaValueNull(tratarString($_POST['ext3']));
$pas3 = checaValueNull(tratarString($_POST['pas3']));
$int4 = checaValueNull(tratarString($_POST['int4']));
$ext4 = checaValueNull(tratarString($_POST['ext4']));
$pas4 = checaValueNull(tratarString($_POST['pas4']));

$qtdSem = 4;

if(isset($_POST['int5'])){
  $int5 = checaValueNull(tratarString($_POST['int5']));
  $ext5 = checaValueNull(tratarString($_POST['ext5']));
  $pas5 = checaValueNull(tratarString($_POST['pas5']));
  $qtdSem = 5;
} else {
  $int5 = 0;
  $ext5 = 0;
  $pas5 = 0;
}

if(isset($_POST['int6'])){
  $int6 = checaValueNull(tratarString($_POST['int6']));
  $ext6 = checaValueNull(tratarString($_POST['ext6']));
  $pas6 = checaValueNull(tratarString($_POST['pas6']));
  $qtdSem = 6;
} else {
  $int6 = 0;
  $ext6 = 0;
  $pas6 = 0;
}

//Pega observação
$obsR = tratarString(nl2br($_POST['obs']));

$tecnicos = arrayTecnicos($db);

/* ---- TRATAMENTO DOS BLOQUEIOS ----*/

//Bloqueio dos técnicos que não estão de plantão no sábado
//print_r($sabados);
foreach ($sabados as $sab) {
  if($sab != ""){
    $tecFolgasFds = $tecnicos;
    $tecEscala = "-";
    $data = explode("-", $sab);

    if(isset($escSabado[$data[1]])){
      foreach ($escSabado[$data[1]] as $tec) {
        if($tec != ""){
          $tecFolgasFds = str_replace('-'.$tec.'-', "-", $tecFolgasFds);
          $tecEscala .= $tec.'-';
        }
      }
    }

    $tecFolgasFds = explode("-", $tecFolgasFds);
    $data = explode("-", $sab);
    $dataIni = $data[1]."/".$mes."/".$ano;
    $dataFim = $data[1]."/".$mes."/".$ano;

    foreach ($tecFolgasFds as $id) {
      if($id != ""){
        addBloqueio($db, $id, $dataIni, "00:00:00", $dataFim, "23:59:59", 'fora', 'nesc', $ano, $mes, $lDay);
      }
    }

    $tecEscala = explode("-", $tecEscala);
    $hriniSab = $sAtFim.":00";
    foreach ($tecEscala as $id) {
      if($id != ""){
        addBloqueio($db, $id, $dataIni, $hriniSab, $dataFim, "23:59:59", 'fora', 'nesc', $ano, $mes, $lDay);
      }
    }
  }
}

//Bloqueio de horários compensados em banco de horas
foreach ($bancoHoras as $banco) {
  //Checa se o horário no banco de horas é menos que o da jornada da casa
  if((horaParaSegundos($banco['fim']) - horaParaSegundos($banco['inicio'])) < horaParaSegundos($atSai) - horaParaSegundos($atIni)){
    $int = explode(" à ", $banco['semana']);
    $intIni = $int[0];
    $int = explode("/", $int[1]);
    $intFim = $int[0];

    //Grava bloqueio se o horario de inicio do banco foi mais tarde que o horário de inicio da jornada
    if(horaParaSegundos($banco['inicio']) > horaParaSegundos($atIni)){
      $blockIni = $banco['inicio']."00";
      $blockFim = $atIni.":00";
      for ($i=$intIni; $i <= $intFim ; $i++) {
        addBloqueio($db, $banco['tecnico'], $i."/".$mes."/".$ano, $blockIni, $i."/".$mes."/".$ano, $blockFim, 'fora', 'bancoHoras', $ano, $mes, $lDay);
      }
    }

    //Grava bloqueio se o horario de fim do banco for mais tarde que o horário de fim da jornada
    if(horaParaSegundos($banco['fim']) < horaParaSegundos($atFim)){
      $blockIni = $banco['fim']."00";
      $blockFim = $atFim.":00";
      for ($i=$intIni; $i <= $intFim ; $i++) {
        addBloqueio($db, $banco['tecnico'], $i."/".$mes."/".$ano, $blockIni, $i."/".$mes."/".$ano, $blockFim, 'fora', 'bancoHoras', $ano, $mes, $lDay);
      }
    }
  }

  $sem = explode("/", $banco['semana']);
  $sem = explode(" à ", $sem[0]);
  $prim = $sem[0];
  $ult = $sem[1];

  $agend = $banco['agendamento'];
  $obs = $banco['obs'];
  $idTec = $banco['tecnico'];

  $sql = "INSERT INTO `banco_horas` (`mes`, `ano`, `prim`, `ult`, `agendamento`, `obs`, `idTecnico`) VALUES('$mes', '$ano', '$prim', '$ult', '$agend', '$obs', '$idTec')";
  $db->query($sql);

}

//Grava bloqueios fixos
foreach ($fixedBloc as $bloc) {
  if($bloc != ""){
    $dados = explode("-", $bloc);

    for ($i=1; $i <= $lDay; $i++) {
      addBloqueio($db, $dados[0], "$i/".$mes."/".$ano, arrumaHoraBloqueio($dados[1]), $i."/".$mes."/".$ano, somaIntervalo($dados[1], $intAg), 'produtivo', 'blocfix', $ano, $mes, $lDay);
    }
  }
}

//Grava escala de horário de cada técnico
foreach ($escHorario as $esc) {
  //Grava horários de almoço
  if($esc['almocoEntrada'] != "" && $esc['almocoSaida'] != ""){
    for ($i=1; $i <= $lDay ; $i++) {
      addBloqueio($db, $esc['tecnico'], $i.'/'.$mes.'/'.$ano, $esc['almocoEntrada'], $i.'/'.$mes.'/'.$ano, $esc['almocoSaida'], 'almoco', '', $ano, $mes, $lDay);
    }
  }

  //Grava folgas
  $folgas = explode(", ", $esc['folgas']);
  if(count($folgas) > 0){
    foreach ($folgas as $f) {
      if($f != ""){
        addBloqueio($db, $esc['tecnico'], $f, "00:00:00", $f, '23:59:59', 'folga', '', $ano, $mes, $lDay);
      }
    }
  }

  //Grava férias
  if($esc['ferias'] != ""){
    $ferias = explode(" - ", $esc['ferias']);
    addBloqueio($db, $esc['tecnico'], $ferias[0], "00:00:00", $ferias[1], '23:59:59', 'ferias', '', $ano, $mes, $lDay);
  }

  //Grava bloqueios passantes
  $pas = explode(", ", $esc['block']);
  if(count($pas) > 0){
    foreach ($pas as $f){
      if($f != ""){
        addBloqueio($db, $esc['tecnico'], $f, "00:00:00", $f, '23:59:59', 'produtivo', 'blocpas', $ano, $mes, $lDay);
      }
    }
  }
}

//Bloqueia dias em que a casa não irá trabalhar
$diasCalL = explode("-", $diasCal);
$tecArray = explode("-", $tecnicos);
foreach ($diasCalL as $dia) {
  if($dia != ""){
    foreach ($tecArray as $tec) {
      if($tec != ""){
        addBloqueio($db, $tec, $dia."/".$mes."/".$ano, "00:00:00", $dia."/".$mes."/".$ano, '23:59:59', 'fora', '', $ano, $mes, $lDay);
      }
    }
  }
}

//Calcula total de intenção de agendamento
//Soma tota de intenção de atendimento
$attotal = $int1 + $ext1 + $pas1 + $int2 + $ext2 + $pas2 + $int3 + $ext3 + $pas3 + $int4  + $ext4 + $pas4 + $int5 + $ext5 + $pas5 + $int6 + $ext6 + $pas6;



/* ----- GRAVA REGRA NO BANCO DE DADOS ----- */
$data = date('Y-m-d H:i:s');

$sql = "INSERT INTO `regra` (
  `idCasa`,
  `mes`,
  `ano`,
  `dataCadastro`,
  `s1int`,
  `s1ext`,
  `s1pas`,
  `s2int`,
  `s2ext`,
  `s2pas`,
  `s3int`,
  `s3ext`,
  `s3pas`,
  `s4int`,
  `s4ext`,
  `s4pas`,
  `s5int`,
  `s5ext`,
  `s5pas`,
  `s6int`,
  `s6ext`,
  `s6pas`,
  `attotal`,
  `qtas`,
  `qtdd`,
  `hrat`,
  `hrlag`,
  `hrats`,
  `hrlags`,
  `parc`,
  `tint`,
  `dataRecall`,
  `obs`,
  `diasCall`,
  `diasSem`,
  `qtm`,
  `qpps`
) VALUES (
  '$idCasa',
  '$mes',
  '$ano',
  '$data',
  '$int1',
  '$ext1',
  '$pas1',
  '$int2',
  '$ext2',
  '$pas2',
  '$int3',
  '$ext3',
  '$pas3',
  '$int4',
  '$ext4',
  '$pas4',
  '$int5',
  '$ext5',
  '$pas5',
  '$int6',
  '$ext6',
  '$pas6',
  '$attotal',
  '$sAtQtd',
  '$qtdDd',
  '$hrat',
  '$atLim',
  '$horarioAtendimento',
  '$sAtLim',
  '$qtdParc',
  '$intAg',
  '$recDay',
  '$obsR',
  '$diasCal',
  '-2-3-4-5-6-',
  '$qtdMec',
  '$sPlQtd'
)";


if($db->query($sql)){
  header("Location: ../my/inicio?setup=success");
} else {
  header("Location: ../my/inicio?setup=failure");
}


function somaIntervalo($hora, $int){
  $hora = explode(":", $hora);
  $hora[0] = (int) $hora[0];
  $hora[1] = (int) $hora[1];

  $hora[1] = $hora[1]+$int;
  if($hora[1] >= 60){
    $hora[0]++;
    $hora[1] = 0;
  }
  if($hora[0] < 10){
    $hora[0] = "0".$hora[0];
  }
  if($hora[1] < 10){
    $hora[1] = "0".$hora[1];
  }

  return $hora[0].":".$hora[1].":00";
}

function arrayTecnicos($db){
  $idCasa = $_SESSION['casa'];
  $sql = "SELECT `idUser` FROM `user` WHERE `ramal` = '$idCasa' AND `tipo` = 'tecnico' AND `status` = '1'";
  $tecs = $db->query($sql);
  $tecs = $tecs->fetchAll();
  $str = "-";

  foreach ($tecs as $tec) {
    $str .= $tec['idUser']."-";
  }

  return $str;
}

function arrumaHoraBloqueio($hora){
  $hora = explode(":", $hora);

  if(count($hora) == 0){
    return "00:00:00";
  }

  if((int) $hora[0] < 10){
    $hora[0] = "0".$hora[0];
  }
  if((int) $hora[1] < 10){
    $hora[1] = "0".$hora[1];
  }

  return $hora[0].":".$hora[1].":00";
}


function addBloqueio($db, $tec, $dataini, $horaini, $datafim, $horafim, $motivo, $descricaomotivo, $ano, $mes, $lDay){
  //return true;
  $idCasa = $_SESSION['casa'];

  if($datafim == ""){

    $datafim = $dataini;
    $horafim = explode(":", $horaini);
    if($regra['tint'] == '20'){
      $horafim[1] = $horafim[1]+20;
      if($horafim[1] >= 60){
        $horafim[0]++;
        $horafim[1] = $horafim[1]-60;
      }
    } else if($regra['tint'] == 30){
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
    $sql = "INSERT INTO `bloqueio` (`idTecnico`, `idCasa`, `dataInicio`, `dataFim`, `motivo`, `obs`, `origem`)
    VALUES ('$tec', '$idCasa', '$dataini', '$datafim', '$motivo', '$descricaomotivo', 'regra')";

    if($db->query($sql)){
      $sql = "DELETE a FROM bloqueio AS a, bloqueio  AS b WHERE a.idTecnico = b.idTecnico AND a.idCasa = b.idCasa AND a.dataInicio = b.dataInicio AND a.dataFim = b.dataFim AND a.obs = b.obs AND a.motivo = b.motivo AND a.origem = b.origem AND a.idBloqueio != b.idBloqueio AND a.dataInicio >= '$ano-$mes-01 00:00:00' AND a.dataFim <= '$ano-$mes-$lDay 23:59:59'";
      $db->query($sql);

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

      $sql = "INSERT INTO `bloqueio` (`idTecnico`, `idCasa`, `dataInicio`, `dataFim`, `motivo`, `obs`, `origem`)
      VALUES ('$tec', '$idCasa', '$dataFIni', '$dataFFim', '$motivo', '$descricaomotivo', 'regra')";

      $db->query($sql);

      $dataini = date("Y-m-d", strtotime($dataini." +1 days"));
      if(strpos($motivo, "cont-") === false){
        $motivo = "cont-".$motivo;
      }

    }
    $sql = "DELETE a FROM bloqueio AS a, bloqueio  AS b WHERE a.idTecnico = b.idTecnico AND a.idCasa = b.idCasa AND a.dataInicio = b.dataInicio AND a.dataFim = b.dataFim AND a.obs = b.obs AND a.motivo = b.motivo AND a.origem = b.origem AND a.idBloqueio != b.idBloqueio AND a.dataInicio >= '$ano-$mes-01 00:00:00' AND a.dataFim <= '$ano-$mes-$lDay 23:59:59'";
    $db->query($sql);
    return true;

  }
  return false;
}


function checaValueNull($val){
  if($val == "" || !is_numeric($val)){
    return 0;
  } else {
    return $val;
  }
}

/*
function checaValueNull($val){
if($val == "" || !is_numeric($val)){
return 0;
} else {
return $val;
}
}


$dia = date('d');
$mes = date('m');
$ano = date('Y');

$data = date('Y-m-d H:i:s');

$idCasa = $_SESSION['casa'];

$sql = "SELECT count(`idRegra`) AS `total` FROM `regra` WHERE `idCasa` = '$idCasa' AND `mes` = '$mes' AND `ano` = '$ano'" ;
$tt = $db->query($sql);
$tt = $tt->fetchAll();
$tt = $tt[0]['total'];


if($tt > 0){

if($dia >= 20){
$mes++;
if($mes > 12){
$ano++;
$mes = 1;
}

} else {
header('Location: ../my/inicio?regra=existente');
}

}


$diasCal = tratarString($_POST['diasCall']);
$diasCal = str_replace('--', '-', $diasCal);

$int1 = checaValueNull(tratarString($_POST['int1']));
$ext1 = checaValueNull(tratarString($_POST['ext1']));
$pas1 = checaValueNull(tratarString($_POST['pas1']));

$int2 = checaValueNull(tratarString($_POST['int2']));
$ext2 = checaValueNull(tratarString($_POST['ext2']));
$pas2 = checaValueNull(tratarString($_POST['pas2']));

$int3 = checaValueNull(tratarString($_POST['int3']));
$ext3 = checaValueNull(tratarString($_POST['ext3']));
$pas3 = checaValueNull(tratarString($_POST['pas3']));

$int4 = checaValueNull(tratarString($_POST['int4']));
$ext4 = checaValueNull(tratarString($_POST['ext4']));
$pas4 = checaValueNull(tratarString($_POST['pas4']));

$qtdSem = 4;

if(isset($_POST['int5'])){
$int5 = checaValueNull(tratarString($_POST['int5']));
$ext5 = checaValueNull(tratarString($_POST['ext5']));
$pas5 = checaValueNull(tratarString($_POST['pas5']));
$qtdSem = 5;
} else {
$int5 = 0;
$ext5 = 0;
$pas5 = 0;
}

if(isset($_POST['int6'])){
$int6 = checaValueNull(tratarString($_POST['int6']));
$ext6 = checaValueNull(tratarString($_POST['ext6']));
$pas6 = checaValueNull(tratarString($_POST['pas6']));
$qtdSem = 6;
} else {
$int6 = 0;
$ext6 = 0;
$pas6 = 0;
}

//Soma tota de intenção de atendimento
$attotal = $int1 + $ext1 + $pas1 + $int2 + $ext2 + $pas2 + $int3 + $ext3 + $pas3 + $int4  + $ext4 + $pas4 + $int5 + $ext5 + $pas5 + $int6 + $ext6 + $pas6;

$entrada = tratarString($_POST['entrada']);
$aAlamoco = tratarString($_POST['sAlmoco']);
$retAlmoco = tratarString($_POST['retAlmoco']);
$saida = tratarString($_POST['saida']);
$hrat = $entrada . "-" . $aAlamoco . "-" . $retAlmoco . "-". $saida;

$qtdd = tratarString($_POST['qtdd']);

$qtm = tratarString($_POST['qtm']);
$qpps = tratarString($_POST['qpps']);

$dias = tratarString($_POST['dias']);
$tmp = "";
foreach ($dias as $k) {
$tmp .= "-".$k."-";
}
$dias = str_replace("--","-",$tmp);

$horarioAgendamento = tratarString($_POST['horarioAgendamento']);
$horarioAtendimento = tratarString($_POST['horarioAtendimento']);
$limiteAgendamento = tratarString($_POST['limiteAgendamento']);
$quantidadeParcelas = tratarString($_POST['quantidadeParcelas']);
$qtdAtendimentoSab = tratarString($_POST['qtdAtendimentoSab']);

$intervalo = tratarString($_POST['intervalo']);

$recall = tratarString($_POST['recall']);

$obs = tratarString($_POST['obs']);

$sql = "INSERT INTO `regra` (
`idCasa`,
`mes`,
`ano`,
`dataCadastro`,
`s1int`,
`s1ext`,
`s1pas`,
`s2int`,
`s2ext`,
`s2pas`,
`s3int`,
`s3ext`,
`s3pas`,
`s4int`,
`s4ext`,
`s4pas`,
`s5int`,
`s5ext`,
`s5pas`,
`s6int`,
`s6ext`,
`s6pas`,
`attotal`,
`qtas`,
`qtdd`,
`hrat`,
`hrlag`,
`hrats`,
`hrlags`,
`parc`,
`tint`,
`dataRecall`,
`obs`,
`diasCall`,
`diasSem`,
`qtm`,
`qpps`
) VALUES (
'$idCasa',
'$mes',
'$ano',
'$data',
'$int1',
'$ext1',
'$pas1',
'$int2',
'$ext2',
'$pas2',
'$int3',
'$ext3',
'$pas3',
'$int4',
'$ext4',
'$pas4',
'$int5',
'$ext5',
'$pas5',
'$int6',
'$ext6',
'$pas6',
'$attotal',
'$qtdAtendimentoSab',
'$qtdd',
'$hrat',
'$horarioAgendamento',
'$horarioAtendimento',
'$limiteAgendamento',
'$quantidadeParcelas',
'$intervalo',
'$recall',
'$obs',
'$diasCal',
'$dias',
'$qtm',
'$qpps'
)";

if($db->query($sql)){
header("Location: ../my/inicio?setup=success");
} else {
header("Location: ../my/inicio?setup=failure");
}

*/

?>
