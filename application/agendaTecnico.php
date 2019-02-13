<?php
include '../core.php';

if(!isset($_GET['view'])){
  $view = "dia";
} else {
  $view = $_GET['view'];
}

//Verifica em qual view está (dia, semana ou mês).
if($view == "dia"){
  if(!isset($_POST['data'])){
    $data = date("Y-m-d");
  } else {
    $data = tratarString($_POST['data']);
  }
  $dataIne = $data;
  $dataFim = $data;
} else if($view == "semana"){
  if(!isset($_POST['data']) || $_POST['data'] == date('Y-m-d')){
    $diasSemana = diasSemana();
    $dataIne = $diasSemana[0];
    $dataFim = $diasSemana[6];
  } else {
    $data = explode(' ~ ', $_POST['data']);
    $dataIne = $data[0];
    $dataFim = $data[1];
  }
} else {

}


$idCasa = $_SESSION['casa'];
$user = $_SESSION['id'];
$erro = false;

//Carrega regra de negócio
$sql = "SELECT `idRegra`, `mes`, `ano`, `hrat`, `tint` FROM `regra` WHERE `idCasa` = '$idCasa' ORDER BY `dataCadastro` DESC LIMIT 1";
$regra = $db->query($sql);
$regra = $regra->fetchAll();
if(count($regra) == 0){
  $erro = "regra";
} else {
  $regra = $regra[0];
}

//Carrega bloqueio do técnico
$dataBlocIne = $dataIne . " 00:00:00";
$dataBlocFim = $dataFim . " 23:59:59";

$sql = "SELECT * FROM `bloqueio` WHERE `idTecnico` = '$user' AND `dataInicio` >= '$dataBlocIne' AND `dataFim` <= '$dataBlocFim' ORDER BY `motivo`";
$blocks = $db->query($sql);
$blocks = $blocks->fetchAll();

$sql = "SELECT * FROM `user` WHERE `idUser` = '$user'";
$tec = $db->query($sql);
$tec = $tec->fetchAll();
$tec = $tec[0];
$jornada = retJornada($tec['filas']);

//Cria array dos bloqueios
$int = $regra['tint'];
$arrayBlocks = array();
foreach ($blocks as $bl) {
  //echo $bl['dataInicio'];
  $hora = explode(" ", $bl['dataInicio']);
  $d = $hora[0];
  $hora = explode(":", $hora[1]);
  $h = $hora[0];
  $m = $hora[1];

  if($int == 20){
    do{
      if($m == 0 || $m == 20 || $m == 40){
        $loop = false;
      } else {
        $m++;
        if($m == 60){
          $m == 0;
        }
        $loop = true;
      }
    }while($loop);
  } else if($int == 30){
    do{
      if($m == 0 || $m == 30){
        $loop = false;
      } else {
        if($m > 30){
          $m++;
        } else {
          $m--;
        }
        if($m == 60){
          $m == 0;
        }
        $loop = true;
      }
    }while($loop);
  } else {
    if($m != 0){
      $m = 0;
    }
  }
  $diaindex = explode('-', $d);
  $hora = fixHora($h.":".$m);
  $index = $hora."-".$diaindex[2];
  $arrayBlocks[$index] = array(
    'dataini' => $bl['dataInicio'],
    'datafim' => $bl['dataFim'],
    'motivo' => $bl['motivo'],
    'obs' => $bl['obs']
  );

  do{
    $loop = true;
    if(date("Y-m-d H:i:s", strtotime($arrayBlocks[$index]['datafim'])) > date("Y-m-d H:i:s", strtotime($arrayBlocks[$index]['dataini']." +".$regra['tint']." minutes"))){
      $indexOld = $index;
      $index = explode("-", $index);
      $it = $index[1];
      $index = explode(":", $index[0]);
      $ih = (int) $index[0];
      $im = (int) $index[1] + $regra['tint'];
      if($im >= 60){
        $ih++;
        $im = $im-60;
      }
      if($ih > 23){
        $loop = false;
      }
      $hora = fixHora($ih.":".$im);
      $index = $hora."-".$it;
      $dataIniLoop = date("Y-m-d H:i:s", strtotime($arrayBlocks[$indexOld]['dataini']." +".$regra['tint']." minutes"));
      $arrayBlocks[$index] = array(
        'dataini' => $dataIniLoop,
        'datafim' => $bl['dataFim'],
        'motivo' => "cont-".$bl['motivo'],
        'obs' => $bl['obs']
      );

    } else {
      $loop = false;
    }

  }while($loop);

}

//Seta horário de inicio e fim da agenta com base na regra de negócio ativa
if(!$erro){
  $horario = explode('-', $regra['hrat']);
  if(count($horario) != 4){
    $erro = "horario";
  } else {
    $hIni = $horario[0];
    $hIni = explode(":", $hIni);
    $mIni = $hIni[1];
    $hIni = $hIni[0];

    $hFim = $horario[3];
    $hFim = explode(":", $hFim);
    $mFim = $hFim[1];
    $hFim = $hFim[0];

    $tint = $regra['tint'];

    if($tint == 20){
      $th = $hFim - $hIni;
      $th = $th*3;
      if($mFim >= 40){
        $th = $th+2;
      } else if($mFim >= 20){
        $th++;
      }

    } else if($tint == 30){
      $th = $hFim - $hIni;
      $th = $th*2;

      if($mFim >= 30){
        $th++;
      }

    } else if($tint == 60){

      $th = $hFim - $hIni;

    } else {
      $erro = "intervalo";
    }
  }
}

/* Função para cortar o nome do técnico se for muito grante
* para evitar quebra de layout da agenta
*/
function fixName($name){
  if(strlen($name) > 20){
    return substr($name, 0, 18)."...";
  } else {
    return $name;
  }
}

function setHoraAgenda($hora){
  if(strlen($hora) == 2){
    return $hora;
  } else {
    return "0".(int)$hora;
  }
}

//Função que atravez de uma data retorna o nome da semana.
function semana($data){
  $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
  $diasemana_numero = date('w', strtotime($data));

  return $diasemana[$diasemana_numero];
}

//Função que atravez de um número de 0 à 6 retorna o nome da semana.
function semanaProNumero($num){
  $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
  return $diasemana[$num];
}

//Funação que atravez de uma data, concatena o nome da semana com o dia.
function diaESemana($data){
  $dataDividida = explode('-', $data);
  return $dataDividida[2] . " " . semana($data);
}

//Funação que atravez de uma data, concatena o nome do mês com o ano.
function mesEAno($data){
  $dataDividida = explode('-', $data);
  return nomeMesPorNum($dataDividida[1] - 1) . " " . $dataDividida[0];
}

//Função que tras o nome do mês pelo número do mês.
function nomeMesPorNum($mes){
  $meses = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
  return $meses[$mes];
}

//Função para trazer o mes e o ano selecionados do calendario, casso temanha mais de um, trazer os dois
function nomeMes($dataIne, $dataFim){

  $meses = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
  $mesesBrev = array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez');
  $data = explode('-', $dataIne);
  $mesIne = $data[1];
  $anoIne = $data[0];
  $data = explode('-', $dataFim);
  $mesFim = $data[1];
  $anoFim = $data[0];

  if($mesIne == $mesFim){
    $msg = $meses[($mesIne - 1)];
  } else {
    $msg = $mesesBrev[($mesIne - 1)] . " - " . $mesesBrev[($mesFim - 1)];
  }

  if($anoIne == $anoFim){
    $msg = $msg . " " . $anoIne;
  } else {
    $msg = $mesesBrev[($mesIne - 1)] . " " . $anoIne . " - " . $mesesBrev[($mesFim - 1)] . " " . $anoFim;
  }

  return $msg;
}

function margemDeDias($index, $dataIne){
  $ultimoDia = date('t', strtotime($dataIne));
  $data = explode('-', $dataIne);
  $diaIne = $data[2];
  $dia = $diaIne + $index;

  if($dia > $ultimoDia){
    $dia = $dia - $ultimoDia;
  }

  if($dia < 10){
    $dia = "0" . $dia;
  }

  return $dia;
}

function setBloqueio($jor, $agora){
  if(segHora($agora) < segHora($jor['entrada']) || segHora($agora) > segHora($jor['saida'])){
    //Fora do horário
    return "fora";
  } else {
    return "livre";
  }
}

function segHora($h, $m = false){
  if(!$m){
    $h = explode(":", $h);
    $m = $h[1];
    $h = $h[0];
  }

  $h = (int) $h;
  $m = (int) $m;

  return (($h*60)*60) + ($m*60);

}

function retHoraAgenda($dataini, $datafim = "0000-00-00 00:00:00", $local = false){
  if(!$local){
    $dataini = explode(" ", $dataini);
    $horaini = explode(":", $dataini[1]);
    $horaini = $horaini[0]."h".$horaini[1];

    $datafim = explode(" ", $datafim);
    $horafim = explode(":", $datafim[1]);
    $horafim = $horafim[0]."h".$horafim[1];

    return "Das ".$horaini." às ".$horafim;
  } else if($local == "almoco"){

    $horaini = explode(":", $dataini['almoco']);
    $horaini = $horaini[0]."h".$horaini[1];

    $horafim = explode(":", $dataini['retorno']);
    $horafim = $horafim[0]."h".$horafim[1];

    return "Das ".$horaini." às ".$horafim;
  }

}

//função para pedar os dias da semana atual.
function diasSemana(){
  $dias = array();
  $semana = date('w');
  $semanaMais = date('w');
  $semanaMenos = date('w');

  for($i = 0; $i < 7; $i++){
    if($i == $semana){
      $dias[$i] = $semana;
    } else if(date('w') > $i){
      $diferenca = date('Y-m-d', strtotime('-'.$semanaMenos.' days'));
      $semanaMenos--;
      $dias[$i] = $diferenca;
    } else {
      $diferenca = date('Y-m-d', strtotime('+'.$semanaMais.' days'));
      $semanaMais++;
      $dias[$i] = $diferenca;
    }
  }
  return $dias;
}

?>
