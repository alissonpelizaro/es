<?php
include '../core.php';

//Carrega regra de negócio
if($_SESSION['tipo'] == 'gestor' || $_SESSION['tipo'] == 'tecnico'){
  $idCasa = $_SESSION['casa'];
} else {
  $idCasa = tratarString($_POST['hash'])/3237;
}
$sql = "SELECT `idRegra`, `mes`, `ano`, `hrat`, `tint` FROM `regra` WHERE `idCasa` = '$idCasa' ORDER BY `dataCadastro` DESC LIMIT 1";
$regra = $db->query($sql);
$regra = $regra->fetchAll();
if(count($regra) == 0){
  $erro = "regra";
} else {
  $regra = $regra[0];
}

if(isset($_POST['origin'])){
  if($_POST['origin'] == 'diariogestor' || $_POST['origin'] == 'detregra'){
    $tec = tratarString($_POST['tecnico']);
    $dataini = tratarString($_POST['dataini']);
    $horaini = tratarString($_POST['horaini']);
    $datafim = tratarString($_POST['datafim']);
    $horafim = tratarString($_POST['horafim']);
    $motivo = tratarString($_POST['motivo']);
    $descricaomotivo = tratarString($_POST['descricaomotivo']);

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
      $sql = "INSERT INTO `bloqueio` (`idTecnico`, `idCasa`, `dataInicio`, `dataFim`, `motivo`, `obs`)
      VALUES ('$tec', '$idCasa', '$dataini', '$datafim', '$motivo', '$descricaomotivo')";

      if($db->query($sql)){
        if(isset($_POST['hash'])){
          $return = "detregra?crip=ssl&hash=".$_POST['hash']."&token=none";
          header("Location: ../my/".$return."&cadastro=success#calendario-icone");
        } else {
          header("Location: ../my/agenda?cadastro=success");
        }
      } else {
        if(isset($_POST['hash'])){
          $return = "detregra?crip=ssl&hash=".$_POST['hash']."&token=none";
          header("Location: ../my/".$return."&cadastro=success#calendario-icone");
        } else {
          header("Location: ../my/agenda?cadastro=failure");
        }
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

      if(isset($_POST['hash'])){
        $return = "detregra?crip=ssl&hash=".$_POST['hash']."&token=none";
        header("Location: ../my/".$return."&cadastro=success#calendario-icone");
      } else {
        header("Location: ../my/agenda?cadastro=success");
      }
    }
  }
} else {
  header("Location: ../my/inicio");
}

?>
