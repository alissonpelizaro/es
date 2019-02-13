<?php
include '../core.php';

if(!isset($_GET['token'])){
  backStart();
  die;
}

$token = tratarString($_GET['token']);

if(strlen($token) == 15){
  if($licenca->validaLicencaSupervisor()){
    $nivel = 'supervisor';
  } else {
    header('Location: ../my/supervisores');
  }
} else if(strlen($token) == 17){
  if($licenca->validaLicencaCoordenador()){
    $nivel = 'coordenador';
  } else {
    header('Location: ../my/coordenadores');
  }
} else if(strlen($token) == 20){
  if($licenca->validaLicencaAdministrador()){
    $nivel = 'administrador';
  } else {
    header('Location: ../my/administradores');
  }
} else {
  backStart();
  die;
}

//Carrega setores cadastrados
$sql = "SELECT `idSetor`, `nome` FROM `setor`";
$setores = $db->query($sql);
$setores = $setores->fetchAll();

?>
