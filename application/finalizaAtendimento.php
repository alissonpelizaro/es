<?php
include '../core.php';

$id = tratarString($_POST['crip']) / 7;
$obs = tratarString($_POST['obs']);

if(isset($_POST['followise']) && $_POST['followise'] == 1){
  $followise = true;
} else {
  $followise = false;
}

$obs = tratarString($_POST['obs']);

//Carrega informações do atendimento
$sql = "SELECT * FROM `atendimento` WHERE `idAtendimento` = '$id'";
$at = $db->query($sql);
$at = $at->fetchAll();

if(count($at) == 0){
  header('Location: ../my/media?finish=failure');
  die;
} else {
  $at = (object) $at[0];
}


//Cria o relatório desse atendimento
$sql = "SELECT * FROM `chat_atendimento` WHERE `idAtendimento` = '$id' ORDER BY `dataEnvio`";
$chat = $db->query($sql);
$chat = $chat->fetchAll();

//$rel = "Inicio do atendimento: ".$at->dataInicio."\r\n<br>";
$rel = "Cliente: ".$at->remetente."<hr>";
$relFollow = "";
$ultima = '';
$auxa = 0;
$auxc = 0;
$tmra = 0;
$tmrc = 0;

if(count($chat) > 0){
  foreach ($chat as $msg) {
    $dataEnvio = $msg['dataEnvio'];
    if($msg['rmt'] == 'cliente'){
      if($ultima == 'agente'){
        $auxc++;
        $ini = strtotime($tultima);
        $fim = strtotime($dataEnvio);
        $tmrc = $tmrc + ($fim - $ini);
      }
      $ultima = 'cliente';
      $tultima = $dataEnvio;
      $who = "<br class='expl'><b>Cliente (".dataBdParaHtml($dataEnvio)."): </b>";
    } else {
      if($ultima == 'cliente'){
        $auxa++;
        $ini = strtotime($tultima);
        $fim = strtotime($dataEnvio);
        $tmra = $tmra + ($fim - $ini);
      }
      $ultima = 'agente';
      $tultima = $dataEnvio;
      $who = "<br class='expl'><b>Agente (".dataBdParaHtml($dataEnvio)."): </b>";
    }
    $rel .= $who.$msg['chat'];
    $relFollow .= strtoupper($ultima)." (".dataBdParaHtml($tultima)."): ".$msg['chat']."<br>";
  }
  $first = $chat[0]['dataEnvio'];
  $last = $dataEnvio;

} else {
  $first = false;
}

if($followise){
  $util->sendFollowise($relFollow, $at->idCliente);
}

// Calculando o tempo médio de resposta
if($auxa > 0){
  $tmra = round($tmra/$auxa);
} else {
  $tmra = 0;
}

if($auxc > 0){
  $tmrc = round($tmrc/$auxc);
} else {
  $tmrc = 0;
}

// Calculando o tempo de atendimento
if($first){
  $ini = strtotime($first);
  $fim = strtotime($last);
  $diff = $fim - $ini;
} else {
  $diff = 0;
}
$rel = str_replace("'", '-', $rel);
$rel = str_replace('"', '-', $rel);

//Registra o FEEDBACK do atendimento
$sql = "INSERT INTO `feed_atendimento` (`idAtendimento`, `chat`, `avaliacao`, `email`, `ta`, `tmra`, `tmrc`)
  VALUES ('$id', '$rel', 0, 0, '$diff', '$tmra', '$tmrc')";
if($db->query($sql)){
  //Fecha status do atendimento
  $date = date("Y-m-d H:i:s");
  $sql = "UPDATE `atendimento` SET `status` = 1, `dataFim` = '$date', `obs` = '$obs' WHERE `idAtendimento` = '$id'";
  if($db->query($sql)){
    //Limpa as mensagens do CHAT desse atendimento
    $sql = "DELETE FROM `chat_atendimento` WHERE `idAtendimento` = '$id'";
    if($db->query($sql)){

      $log->setAcao('Finalizado atendimento');
      $log->setFerramenta('Medias');
      $log->setAtendimento($id);
      $log->gravaLog();

      header('Location: ../my/media?finish=success');
    } else {
      header('Location: ../my/media?finish=failure');
    }
  } else {
    header('Location: ../my/media?finish=failure');
  }
} else {
  header('Location: ../my/media?finish=failure');
}

// DEBUG: echo $sql;

?>
