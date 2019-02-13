<?php
include '../core.php';

if(!isset($_GET['hash'])){
  header("Location: ../my/inicio");
}

try {



  $idCasa = tratarString($_GET['hash'])/3237;

  $sql = "SELECT * FROM `casa` WHERE `idCasa` = '$idCasa'";
  $casa = $db->query($sql);
  $casa = $casa->fetchAll();

  if(count($casa) != 1){
    header("Location: ../my/inicio");
  }

  $casa = $casa[0];

  //Carrega regras cadastradas
  $sql = "SELECT `idRegra`, `mes`, `ano` FROM `regra` WHERE `idCasa` = '$idCasa' ORDER BY `dataCadastro` DESC";
  $regras = $db->query($sql);
  $regras = $regras->fetchAll();

  //Carrega informação da regra selecionada
  if(isset($_POST['idRegra'])){
    //Se uma regra de outro mes foi selecinada, carrega ela...
    $idRegra = tratarString($_POST['idRegra'])/11;
    $sql = "SELECT * FROM `regra` WHERE `idRegra` = '$idRegra'";
  } else {
    //Senão carrega a última regra cadastrada
    $sql = "SELECT * FROM `regra` WHERE `idCasa` = '$idCasa' ORDER BY `dataCadastro` DESC LIMIT 1";
  }

  $regra = $db->query($sql);
  $regra = $regra->fetchAll();
  if(count($regra) == 0){
    $regra = false;
  } else {

    $regra = $regra[0];

    $ano = $regra['ano'];
    $mes = $regra['mes'];
    $idRegra = $regra['idRegra'];

    $qtdSem = countSemanasMes($ano, $mes);
    $qtdDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    $frstDay = date("w", strtotime($ano."-".$mes."-1"));


    //Carrega os dias da semana trabalhados
    $diaSem = $regra['diasSem'];
    $diaSemTemp = "";
    if($diaSem == ""){
      $diaSem = "Nenhum";
    } else {
      $diaSem = explode("-", $diaSem);
      foreach ($diaSem as $dia) {
        if($dia != ""){
          $diaSemTemp .= retDiaSem($dia). ", ";
        }
      }
    }

    if($diaSemTemp == "Segunda, Terça, Quarta, Quinta, Sexta, "){
      $diaSemTemp = "Segunda à sexta";
    } else if($diaSemTemp == "Segunda, Terça, Quarta, Quinta, Sexta, Sábado, "){
      $diaSemTemp = "Segunda à sábado";
    } else if($diaSemTemp == "Domingo, Segunda, Terça, Quarta, Quinta, Sexta, Sábado, "){
      $diaSemTemp = "Domingo à domingo";
    } else if($diaSemTemp == "Terça, Quarta, Quinta, Sexta, Sábado, "){
      $diaSemTemp = "Terça à sábado";
    } else {
      $diaSemTemp = substr($diaSemTemp, 0, -2);
    }

    //Carrega o horário de atendimento
    $hrAt = explode("-",$regra['hrat']);
    if(count($hrAt) == 4){
      $hrAt = $hrAt[0]." às ".$hrAt[3];
    } else {
      $hrAt = "Horário desconhecido";
    }

    //Carrega intervalo entre agendamentos
    if($regra['tint'] == 20){
      $tInt = "20 em 20 min";
    } else if($regra['tint'] == 30){
      $tInt = "30 em 30 min";
    } else if($regra['tint'] == 60){
      $tInt = "60 em 60 min";
    } else {
      $tInt = "Desconhecido";
    }

    //Carrega RecallDay
    if($regra['dataRecall'] == "" || $regra['dataRecall'] == 0){
      $recall = false;
    } else {
      $recall = true;
    }

    //Carrega promoções da cancessionária
    $sql = "SELECT * FROM `promocao` WHERE `casa` = '$idCasa'";
    $promocoes = $db->query($sql);
    $promocoes = $promocoes->fetchAll();
    if(count($promocoes) == 0){
      $promocoes = false;
    }

    //Carrega produtos da cancessionária
    $sql = "SELECT * FROM `produto` WHERE `casa` = '$idCasa'";
    $produtos = $db->query($sql);
    $produtos = $produtos->fetchAll();
    if(count($produtos) == 0){
      $produtos = false;
    }


    /*TRECHO AGENDA.PHP*/

    //Carrega agenda da casa
    $erro = false;

    if(!isset($_POST['data'])){
      $data = date("Y-m-d");
    } else {
      $data = tratarString($_POST['data']);
    }

    $dataSplit = explode("-", $data);

    //Carrega técnicos
    $sql = "SELECT `idUser`, `nome`, `sobrenome`, `avatar`, `filas` FROM `user` WHERE `tipo` = 'tecnico' AND `status` = '1' AND `ramal` = '$idCasa'";
    $tecs = $db->query($sql);
    $tecs = $tecs->fetchAll();

    //Carrega bloqueios no periodo selecionado
    $dataini = $data." 00:00:00";
    $datafim = $data." 23:59:59";
    $sql = "SELECT * FROM `bloqueio` WHERE `dataInicio` >= '$dataini' AND `dataInicio` <= '$datafim' ORDER BY `motivo`";
    $blocks = $db->query($sql);
    $blocks = $blocks->fetchAll();

    //Cria array dos bloqueios
    $int = $regra['tint'];
    $arrayBlocks = array();
    $last = "";
    foreach ($blocks as $bl) {
      //echo $bl['dataInicio'];
      $hora = explode(" ", $bl['dataInicio']);
      $hora = explode(":", $hora[1]);
      $h = (int) $hora[0];
      $m = (int) $hora[1];

      if($int == 20){
        do{
          if($m == 0 || $m == 20 || $m == 40){
            $loop = false;
          } else {
            $m++;
            if($m == 60){
              $m = 0;
              $h++;
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
              $m = 0;
              $h++;
            }
            $loop = true;
          }
        }while($loop);
      } else {
        if($m != 0){
          $m = 0;
        }
      }

      if($last == $bl['motivo']){
        $motivo .= "-cont";
      } else {
        $last = $bl['motivo'];
        $motivo = $bl['motivo'];
      }
      $hora = fixHora($h.":".$m);
      $index = $hora."-".$bl['idTecnico'];
      $arrayBlocks[$index] = array(
        'dataini' => $bl['dataInicio'],
        'datafim' => $bl['dataFim'],
        'motivo' => $bl['motivo'],
        'obs' => $bl['obs'],
        'idBloc' => $bl['idBloqueio']
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

          if(strpos($bl['motivo'], "cont") === false){
            $bl['motivo'] .= "-cont";
          }

          $arrayBlocks[$index] = array(
            'dataini' => $dataIniLoop,
            'datafim' => $bl['dataFim'],
            'motivo' => $bl['motivo'],
            'obs' => $bl['obs'],
            'idBloc' => $bl['idBloqueio']
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
    } if(segHora($agora) >= segHora($jor['almoco']) && segHora($agora) < segHora($jor['retorno'])){
      //Horário de almoco
      return 'almoco';
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

  include 'util_regra.php';


} catch (\Exception $e) {
  echo $e;
}
?>
