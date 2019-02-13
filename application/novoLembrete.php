<?php
include '../core.php';

try {
  $id = $_SESSION['id'];
  $titulo = tratarString($_POST['titulo']);
  $desc = tratarString($_POST['lembrete']);
  $desc = str_replace("\\r\\n", "<br>", $desc);
  $cor = tratarString($_POST['cor']);
  $notif = tratarString($_POST['notificacao']);
  $hora = tratarString($_POST['hora']);
  $agora = date('Y-m-d H:i:s');
  if(isset($_POST['father']) && $_POST['father'] == 'inicio'){
    $k = 'inicio';
  } else {
    $k = 'lembretes';
  }
} catch (\Exception $e) {
  echo $e;
  die;
}

if($notif != ""){
	$notif = $util->dataHtmlParaBd($notif, $hora);	
} else {
  $notif = "1000-01-01 00:00:00";
}

$sql = "INSERT INTO `lembrete` (`titulo`, `desc`, `cor`, `alarme`, `status`,
  `dataCadastro`, `idUser`) VALUES ('$titulo', '$desc', '$cor', '$notif',
  '1', '$agora', '$id')";

if($db->query($sql)){
  header('Location: ../my/'.$k.'?cadastro=success');
} else {
  header('Location: ../my/'.$k.'?cadastro=failure');
}

?>
