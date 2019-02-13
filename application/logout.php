<?php
include '../core.php';

$id = $_SESSION['id'];
$sql = "SELECT `idLog`, `dataLog` FROM `log` WHERE
`idUsuario` = '$id' AND
`acao` = 'Logou no sistema'
ORDER BY `dataLog` DESC LIMIT 1";
$lastData = $db->query($sql);
$lastData = $lastData->fetch();

$diff = strtotime(date('Y-m-d H:i:s')) - strtotime($lastData['dataLog']);

$log->setAcao('Saiu do sistema');
$log->setFerramenta('MyOmni');
$log->setObs($diff);
$log->gravaLog();

$log->atualizaObsLog($lastData['idLog'], $diff);
$sql = "UPDATE `user` SET `logged` = '0', `pausa` = 0 WHERE `idUser` = '$id'";
$db->query($sql);

unset($_SESSION['id']);
unset($_SESSION['nome']);
unset($_SESSION['token']);
unset($_SESSION['senha']);
unset($_SESSION['tipo']);
unset($_SESSION['chat']);
unset($_SESSION['avatar']);
unset($_SESSION['hora']);
unset($_SESSION['setor']);

if(isset($_SESSION)){
  session_destroy();
}

header('Location: ../my/login');

?>
