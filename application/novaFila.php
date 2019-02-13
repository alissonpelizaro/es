<?php
include '../core.php';

$nome = tratarString($_POST['nome']);
$trans = tratarString($_POST['transbordo']);
if(isset($_POST['status'])){
  $status = '1';
} else {
  $status = '0';
}
if(isset($_POST['statusProtocolo'])){
	$statusProtocolo = '1';
} else {
	$statusProtocolo = '0';
}

if(!isset($_POST['edit'])){
  $sql = "SELECT count(*) AS `total` FROM `fila` WHERE `nomeFila` = '$nome'";
  $tt = $db->query($sql);
  $tt = $tt->fetchAll();
  if($tt[0]['total'] > 0){
    header("Location: ../my/filas?cadastro=failure&registry=".urlencode($nome));
    die;
  } else {
    $data = date('Y-m-d H:i:s');
    $sql = "INSERT INTO `fila` (`nomeFila`, `dataCadastro`, `transbordo`, `status`, `statusProtocolo`, `setor`) VALUES ('$nome', '$data', '$trans', '$status', '$statusProtocolo', '$setorUser')";
  }
}

if($db->query($sql)){
  $tmp = 'Fila: '. $nome;
  $log->setAcao('Cadastrou uma nova fila');
  $log->setFerramenta('Filas');
  $log->setObs($tmp);
  $log->gravaLog();
  header("Location: ../my/filas?cadastro=success");
} else {
  header("Location: ../my/filas?cadastro=failure");
}

?>
