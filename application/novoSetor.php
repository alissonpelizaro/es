<?php
/* CHAMADA VIA AJAX */
/* Cadastro de novo grupo de agentes */

include '../core.php';

$agora = date('Y-m-d H:i:s');
$id = $_SESSION['id'];
$grupo = tratarString($_POST['value']);

$sql = "INSERT INTO `setor` (`nome`, `dataCadastro`, `idSupervisor`, `status`, `modulos`)
        VALUES ('$grupo', '$agora', '$id', '1', '-conc--mural--broad--wiki--media-')";

if($db->query($sql)){
  $obs = "Grupo: ".$grupo;
  $log->setAcao('Cadastrou um novo setor');
  $log->setFerramenta('Setor');
  $log->setObs($obs);
  $log->gravaLog();
  echo 1;
} else {
  echo 0;
}

 ?>
