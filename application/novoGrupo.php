<?php

/* CHAMADA VIA AJAX */
/* Cadastro de novo grupo de agentes */

include '../core.php';

$agora = date('Y-m-d H:i:s');
$id = $_SESSION['id'];
$grupo = tratarString($_POST['value']);

$sql = "INSERT INTO `grupo` (`nome`, `dataCadastro`, `idSupervisor`, `status`, `setor`)
        VALUES ('$grupo', '$agora', '$id', '1', '$setorUser')";

if($db->query($sql)){
  $obs = "Grupo: ".$grupo;
  $log->setAcao('Cadastrou um novo grupo');
  $log->setFerramenta('Grupo');
  $log->setObs($obs);
  $log->gravaLog();
  echo 1;
} else {
  echo 0;
}

 ?>
