<?php
include '../core.php';

/*
* Atualização de regra
* Chamada via formulário
*/

/*
ENVIAR ID DA CASA E ID DA REGRA PELO FORM
*/

set_time_limit(120);


$idCasa = tratarString($_POST['idCasa']);
$idRegra = tratarString($_POST['idRegra']);

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
        $indexAg = "agendamentoBancoH-".$i."-".$tec;
        $indexObs = "obsBancoH-".$i."-".$tec;
        $bancoHoras[$j] = array(
          'semana' => $sem,
          'tecnico' => $tec,
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
$tecs = explode("-", arrayTecnicos($db, $idCasa));
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
$tecnicos = arrayTecnicos($db, $idCasa);


// LIMPA BLQUEIOS ANTERIORES
$dataIni = $ano."-".$mes."-01 00:00:00";
$dataFim = $ano."-".$mes."-".$lDay." 23:59:59";
$sql = "DELETE FROM `bloqueio` WHERE `idCasa` = '$idCasa' AND `dataInicio` >= '$dataIni' AND `dataFim` <= '$dataFim' AND `origem` = 'regra' AND `obs` != 'bancoHoras'";
$db->query($sql);


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
          $tecEscala = $tecEscala.$tec.'-';
        }
      }
    }

    $tecFolgasFds = explode("-", $tecFolgasFds);
    $data = explode("-", $sab);
    $dataIni = $data[1]."/".$mes."/".$ano;
    $dataFim = $data[1]."/".$mes."/".$ano;

    foreach ($tecFolgasFds as $id) {
      if($id != ""){
        addBloqueio($db, $id, $dataIni, "00:00:00", $dataFim, "23:59:59", 'fora', 'nesc', $idCasa, $ano, $mes, $lDay);
      }
    }

    $tecEscala = explode("-", $tecEscala);
    $hriniSab = $sAtFim.":00";


    foreach ($tecEscala as $id) {
      if($id != ""){
        //echo "teste22";
        addBloqueio($db, $id, $dataIni, $hriniSab, $dataFim, "23:59:59", 'fora', '', $idCasa, $ano, $mes, $lDay);
      }
    }

  }
}

//Bloqueio de horários compensados em banco de horas
foreach ($bancoHoras as $banco) {

  $sem = explode("/", $banco['semana']);
  $sem = explode(" à ", $sem[0]);
  $prim = $sem[0];
  $ult = $sem[1];

  $agend = $banco['agendamento'];
  $obs = $banco['obs'];
  $idTec = $banco['tecnico'];

  $sql = "UPDATE `banco_horas` SET `agendamento` = '$agend', `obs` = '$obs'
  WHERE `idTecnico` = '$idTec' AND `prim` = '$prim' AND `mes` = '$mes' AND `ano` = '$ano'";
  $db->query($sql);


  //Checa se o horário no banco de horas é menos que o da jornada da casa
  /*if((horaParaSegundos($banco['fim']) - horaParaSegundos($banco['inicio'])) < horaParaSegundos($atSai) - horaParaSegundos($atIni)){
    $int = explode(" à ", $banco['semana']);
    $intIni = $int[0];
    $int = explode("/", $int[1]);
    $intFim = $int[0];

    //Grava bloqueio se o horario de inicio do banco foi mais tarde que o horário de inicio da jornada
    if(horaParaSegundos($banco['inicio']) > horaParaSegundos($atIni)){
      $blockIni = $banco['inicio']."00";
      $blockFim = $atIni."00";
      for ($i=$intIni; $i <= $intFim ; $i++) {
        addBloqueio($db, $banco['tecnico'], $i."/".$mes."/".$ano, $blockIni, $i."/".$mes."/".$ano, $blockFim, 'folga', '');
      }
    }

    //Grava bloqueio se o horario de fim do banco for mais tarde que o horário de fim da jornada
    if(horaParaSegundos($banco['fim']) < horaParaSegundos($atFim)){
      $blockIni = $banco['fim']."00";
      $blockFim = $atFim."00";
      for ($i=$intIni; $i <= $intFim ; $i++) {
        addBloqueio($db, $banco['tecnico'], $i."/".$mes."/".$ano, $blockIni, $i."/".$mes."/".$ano, $blockFim, 'folga', '');
      }
    }
  */
}

//Grava bloqueios fixos
foreach ($fixedBloc as $bloc) {
  if($bloc != ""){
    $dados = explode("-", $bloc);

    for ($i=1; $i <= $lDay; $i++) {
      addBloqueio($db, $dados[0], "$i/".$mes."/".$ano, arrumaHoraBloqueio($dados[1]), $i."/".$mes."/".$ano, somaIntervalo($dados[1], $intAg), 'produtivo', 'blocfix',$idCasa, $ano, $mes, $lDay);
    }
  }
}

//Grava escala de horário de cada técnico
foreach ($escHorario as $esc) {
  //Grava horários de almoço
  if($esc['almocoEntrada'] != "" && $esc['almocoSaida'] != ""){
    for ($i=1; $i <= $lDay ; $i++) {
      addBloqueio($db, $esc['tecnico'], $i.'/'.$mes.'/'.$ano, $esc['almocoEntrada'], $i.'/'.$mes.'/'.$ano, $esc['almocoSaida'], 'almoco', '',$idCasa, $ano, $mes, $lDay);
    }
  }

  //Grava folgas
  $folgas = explode(", ", $esc['folgas']);
  if(count($folgas) > 0){
    foreach ($folgas as $f) {
      if($f != ""){
        addBloqueio($db, $esc['tecnico'], $f, "00:00:00", $f, '23:59:59', 'folga', '',$idCasa, $ano, $mes, $lDay);
      }
    }
  }

  //Grava férias
  if($esc['ferias'] != ""){
    $ferias = explode(" - ", $esc['ferias']);
    addBloqueio($db, $esc['tecnico'], $ferias[0], "00:00:00", $ferias[1], '23:59:59', 'ferias', '',$idCasa, $ano, $mes, $lDay);
  }

  //Grava bloqueios passantes
  $pas = explode(", ", $esc['block']);
  if(count($pas) > 0){
    foreach ($pas as $f){
      if($f != ""){
        addBloqueio($db, $esc['tecnico'], $f, "00:00:00", $f, '23:59:59', 'produtivo', 'blocpas',$idCasa, $ano, $mes, $lDay);
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
        addBloqueio($db, $tec, $dia."/".$mes."/".$ano, "00:00:00", $dia."/".$mes."/".$ano, '23:59:59', 'fora', '',$idCasa, $ano, $mes, $lDay);
      }
    }
  }
}

//Calcula total de intenção de agendamento
//Soma tota de intenção de atendimento
$attotal = $int1 + $ext1 + $pas1 + $int2 + $ext2 + $pas2 + $int3 + $ext3 + $pas3 + $int4  + $ext4 + $pas4 + $int5 + $ext5 + $pas5 + $int6 + $ext6 + $pas6;



/* ----- GRAVA REGRA NO BANCO DE DADOS ----- */
$data = date('Y-m-d H:i:s');

$sql = "UPDATE `regra` SET
  `idCasa` = '$idCasa',
  `mes` = '$mes',
  `ano` = '$ano',
  `dataCadastro` = '$data',
  `s1int` = '$int1',
  `s1ext` = '$ext1',
  `s1pas` = '$pas1',
  `s2int` = '$int2',
  `s2ext` = '$ext2',
  `s2pas` = '$pas2',
  `s3int` = '$int3',
  `s3ext` = '$ext3',
  `s3pas` = '$pas3',
  `s4int` = '$int4',
  `s4ext` = '$ext4',
  `s4pas` = '$pas4',
  `s5int` = '$int5',
  `s5ext` = '$ext5',
  `s5pas` = '$pas5',
  `s6int` = '$int6',
  `s6ext` = '$ext6',
  `s6pas` = '$pas6',
  `attotal` = '$attotal',
  `qtas` = '$sAtQtd',
  `qtdd` = '$qtdDd',
  `hrat` = '$hrat',
  `hrlag` = '$atLim',
  `hrats` = '$horarioAtendimento',
  `hrlags` = '$sAtLim',
  `parc` = '$qtdParc',
  `tint` = '$intAg',
  `dataRecall` = '$recDay',
  `obs` = '$obsR',
  `diasCall` = '$diasCal',
  `diasSem` = '-2-3-4-5-6-',
  `qtm` = '$qtdMec',
  `qpps` = '$sPlQtd'
  WHERE `idRegra` = '$idRegra'
";


if($db->query($sql)){
  header("Location: ../my/inicio?regraedit=success");
} else {
  header("Location: ../my/inicio?regraedit=failure");
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

function arrayTecnicos($db, $idCasa){
  //$idCasa = $_SESSION['casa'];
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


function addBloqueio($db, $tec, $dataini, $horaini, $datafim, $horafim, $motivo, $descricaomotivo, $idCasa, $ano, $mes, $lDay){

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

?>
