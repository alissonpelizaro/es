<?php
include '../core.php';

if(!isset($_POST['broadcast'])){
  backStart();
  die;
}

$broadcast = tratarString($_POST['broadcast']);
if(isset($_POST['grupos'])){
  $grupos = $_POST['grupos'];
} else {
  $grupos = array();
}
$id = $_SESSION['id'];

//print_r($grupos);
//Prepara ARRAY das usuários do sistema
$sql = "SELECT `idUser`, `nome`, `sobrenome`, `avatar`, `ultimoRegistro`, `logged` FROM `user` WHERE `status` = '1' AND `setor` = '$setorUser'";
$dados = $db->query($sql);
$dados = $dados->fetchAll();
$users = array();
foreach ($dados as $usr) {
  $i = $usr['idUser'];
  $users[$i] = array(
    'nome' => $usr['nome'],
    'sobrenome' => $usr['sobrenome']
  );
}

if(count($grupos) == 0){
  $grupos = "";
  $sql = "SELECT `idUser` FROM `user` WHERE `tipo` = 'agente' AND `status` = '1' AND `setor` = '$setorUser'";
  $agentes = $db->query($sql);
  $agentes = $agentes->fetchAll();
  $destinatarios = "-";
  foreach ($agentes as $agente) {
    $destinatarios .= $agente['idUser'].'-';
  }
} else {
  $tmp = "";
  foreach ($grupos as $grupo) {
    $tmp .= '-'.$grupo.'-';
  }
  $grupos = tratarString(str_replace('--', '-', $tmp));
  if(strpos($grupos, 'todos')){
    $sql = "SELECT `idGrupo` FROM `grupo` WHERE `status` = '1'";
    $grupos = $db->query($sql);
    $tmp = "";
    foreach ($grupos as $grupo) {
      $tmp .= '-'.$grupo['idGrupo'].'-';
    }
    $grupos = str_replace('--', '-', $tmp);
  }

  $destinatarios = "-";
  $gp = explode('-', $grupos);
  foreach ($gp as $grupo) {
    if($grupo != ""){
      $sql = "SELECT `agentes` FROM `grupo` WHERE `idGrupo` = '$grupo'";
      $agentes = $db->query($sql);
      $agentes = $agentes->fetchAll();
      if(count($agentes) == 0){
        backStart();
        die;
      }
      $agente = $agentes[0]['agentes'];
      $destinatarios .= $agente."-";

      //Corrige duplicitade de agentes
      $destinatarios = explode('-', $destinatarios);
      if(count($destinatarios) == 0){
        $destinatarios = "-";
      } else {
        $str = "-";
        foreach ($destinatarios as $dest) {
          if($dest != ""){
            if(!strpos($str, "-".$dest."-") && isset($users[$dest])){
              $str .= $dest."-";
            }
          }
        }
        $destinatarios = $str;
      }
    }
  }
}
$destinatarios = str_replace('--', '-', $destinatarios);
$data = date('Y-m-d H:i:s');
$sql = "INSERT INTO `broadcast` (`broadcast`, `data`, `grupos`, `idUser`, `status`, `destinatarios`, `setor`)
  VALUES ('$broadcast', '$data', '$grupos', '$id', '1', '$destinatarios', '$setorUser')";
if($db->query($sql)){
  $obs = "Broadcast: ".$broadcast;
  $log->setAcao('Enviou uma broadcast');
  $log->setFerramenta('Broadcast');
  $log->setObs($obs);
  $log->gravaLog();
  header('Location: ../my/broadcast?send=success');
} else {
  header('Location: ../my/broadcast?send=failure');
}

?>
