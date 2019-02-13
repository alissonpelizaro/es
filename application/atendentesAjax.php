<?php
include '../coreExt.php';

$filtro = tratarString($_POST['filter']);
$setor = tratarString($_POST['setor']);
$dia = date("Y-m-d");

if($filtro != ""){
  $filtro = "AND (`nome` LIKE '%".$filtro."%' OR `sobrenome` LIKE '%".$filtro."%')";
}

//Carrega agentes
$sql = "SELECT `idUser`, `nome`, `sobrenome`, `logged`, `pausa`, `avatar`
FROM `user` WHERE `tipo` = 'agente' AND `status` = '1' AND `filas` != '' AND `setor` = '$setor' AND `filas` != '-#-' $filtro";

$agentes = $db->query($sql);
$agentes = $agentes->fetchAll();

//Carrega atendimentos finalizados no dia
$sql = "SELECT count(`idAtendimento`) AS `tt`, `idAgente` FROM `atendimento`
WHERE `status` = '1' AND `dataInicio` >= '$dia 00:00:00'
AND `dataFim` <= '$dia 23:59:59' GROUP BY `idAgente`";
$temp = $db->query($sql);
$temp = $temp->fetchAll();
$ats = array();
foreach ($temp as $at) {
  $ats[$at['idAgente']] = array(
    'finalizados' => $at['tt'],
    'pendentes' => 0,
    'ativos' => 0,
    'notif' => 0
  );
}

//Carrega atendimentos pendentes e ativos
$sql = "SELECT `idAgente`, `pendente`, `resposta`, `notifRest` FROM `atendimento` WHERE `status` = '0'";
$temp = $db->query($sql);
$temp = $temp->fetchAll();

foreach ($temp as $at) {

  if(!isset($ats[$at['idAgente']])){
    $ats[$at['idAgente']] = array(
      'finalizados' => 0,
      'pendentes' => 0,
      'ativos' => 0,
      'notif' => 0
    );
  }

  if($at['pendente'] == 1){
    $ats[$at['idAgente']]['pendentes']++;
  } else {
    $ats[$at['idAgente']]['ativos']++;
  }

  if($at['resposta'] != 'agente'){
    $agora = date('Y-m-d H:i:s', strtotime('-5 minute'));
    $userTime = date('Y-m-d H:i:s', strtotime($at['resposta']));

    if(strtotime(date('Y-m-d H:i:s', strtotime('-15 minute'))) > strtotime($userTime)){
      $ats[$at['idAgente']]['notif'] = 1;
    } else if(strtotime(date('Y-m-d H:i:s', strtotime('-10 minute'))) > strtotime($userTime)){
      $ats[$at['idAgente']]['notif'] = 2;
    }
  }

  if($at['notifRest'] == '1'){
    $ats[$at['idAgente']]['notif'] = 1;
  }
}

foreach ($agentes as $ag) {
  if(!isset($ats[$ag['idUser']])){
    $ats[$ag['idUser']] = array(
      'finalizados' => 0,
      'pendentes' => 0,
      'ativos' => 0,
      'notif' => 0
    );
  }
  ?>

  <div id="atendente-<?php echo $ag['idUser'];?>" class="div-atendente" onclick="clickAtendente(<?php echo $ag['idUser']; ?>)">
    <img src="assets/avatar/<?php if($ag['avatar'] == ""){ echo 'default.jpg'; } else { echo $ag['avatar']; }?>" class="avatar-ball avatar-bd-<?php
    if($ag['logged'] != 1){
      echo "offline";
    } else if($ag['pausa'] == 1){
      echo "ausente";
    } else {
      echo "online";
    }
    ?>" alt="<?php echo $ag['nome']." ".$ag['sobrenome'] ?>">
    <h4><?php echo $ag['nome']." ".$ag['sobrenome'] ?></h4>
    <i>Atendimentos:
      <span class="badge badge-success bg-verde"><?php echo $ats[$ag['idUser']]['ativos'] ?></span>
      <span class="badge badge-info"><?php echo $ats[$ag['idUser']]['pendentes'] ?></span>
      <span class="badge badge-secondary"><?php echo $ats[$ag['idUser']]['finalizados'] ?></span>
    </i>
    <i class="fa fa-circle text-<?php
    if($ag['logged'] != 1){
      echo "muted";
    } else if($ats[$ag['idUser']]['notif'] == 0){
      echo "verde";
    } else if($ats[$ag['idUser']]['notif'] == 1){
      echo "danger";
    } else {
      echo "warning";
    }
    ?> pull-right" aria-hidden="true"></i>
  </div>
  <?php
} ?>
