<?php
include_once '../core.php';

$idCasa = $_SESSION['casa'];
$erro = false;

if(!isset($_POST['data'])){
  $data = date("Y-m-d");
} else {
  $data = tratarString($_POST['data']);
}

$dataSplit = explode("-", $data);

//Carrega regra de negócio
$sql = "SELECT `idRegra`, `mes`, `ano`, `hrat`, `tint` FROM `regra` WHERE `idCasa` = '$idCasa' ORDER BY `dataCadastro` DESC LIMIT 1";
$regra = $db->query($sql);
$regra = $regra->fetchAll();
if(count($regra) == 0){
  header("Location: ../my/inicio");
  die();
  $erro = "regra";
} else {
  $regra = $regra[0];
  $int = $regra['tint'];
}

//Carrega técnicos
$sql = "SELECT `idUser`, `nome`, `sobrenome`, `avatar`, `filas` FROM `user` WHERE `tipo` = 'tecnico' AND `status` = '1' AND `ramal` = '$idCasa'";
$tecs = $db->query($sql);
$tecs = $tecs->fetchAll();

//Carrega bloqueios no periodo selecionado
$dataini = $data." 00:00:00";
$datafim = $data." 23:59:59";
$sql = "SELECT * FROM `bloqueio` WHERE `dataInicio` >= '$dataini' AND `dataInicio` <= '$datafim'";
$blocks = $db->query($sql);
$blocks = $blocks->fetchAll();

//Cria array dos bloqueios
$arrayBlocks = array();
foreach ($blocks as $bl) {
  //echo $bl['dataInicio'];
  $hora = explode(" ", $bl['dataInicio']);
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
  $hora = fixHora($h.":".$m);
  $index = $hora."-".$bl['idTecnico'];
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
        'motivo' => $bl['motivo'],
        'obs' => $bl['obs']
      );

    } else {
      $loop = false;
    }

  }while($loop);

}

//Seta horário de inicio e fim da agenda com base na regra de negócio ativa
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

/* Função para cortar o nome do técnico se for muito grande
* para evitar quebra de layout da agenda
*/
function fixName($name){
  if(strlen($name) > 20){
    return substr($name, 0, 18)."...";
  } else {
    return $name;
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

function setBloqueio($jor, $agora){
  if(segHora($agora) < segHora($jor['entrada']) || segHora($agora) > segHora($jor['saida'])){
    //Fora do horário
    return "fora";
  } else {
    return "livre";
  }
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

/*
function setHoraAgenda($hora){
if(strlen($hora) == 2){
return $hora;
} else {
return "0".(int)$hora;
}
}*/




?>
