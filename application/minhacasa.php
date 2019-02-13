<?php
include '../core.php';


$idCasa = $_SESSION['casa'];

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
  //Checa a quantidade de técnicos
  $sql = "SELECT count(`idUser`) AS `total` FROM `user` WHERE `ramal` = '$idCasa' AND `tipo` = 'tecnico' AND `status` = '56'";
  $tt = $db->query($sql);
  $tt = $tt->fetch();

  if($tt['total'] == 0){
    header("Location: ../my/inicio");
  } else {
    header("Location: ../my/novaregra");
  }

  die();
}
$regra = $regra[0];

$ano = $regra['ano'];
$mes = $regra['mes'];

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

$dataIniMinhaCasa = $ano."-".$mes."-1 00:00:00";
$lDay = date("t", mktime(0,0,0,$mes,'01',$ano));
$dataFimMinhaCasa = $ano."-".$mes."-".$lDay." 23:59:59";


include 'util_regra.php';
?>
