<?php
include '../core.php';

//Checa se possui integração com xContact
if($licenca->getXcontactStatus()){

  //Pega relação de filas sincronizadas com xContact
  $sql = "SELECT `idFila`, `nome`, `status`, `fantasia`, `transbordo` FROM `int_fila` ORDER BY `nome`";
  $filas = $db->query($sql);
  $filas = $filas->fetchAll();

} else {

  //Pega relação de filas cadastradas
  $sql = "SELECT * FROM `fila` ORDER BY `priority`";
  $filas = $db->query($sql);
  $filas = $filas->fetchAll();

  //Pega relação de emails cadastradas
  $sql = "SELECT * FROM `emailfila` ORDER BY `fila`";
  $emails = $db->query($sql);
  $emails = $emails->fetchAll();

}

//Pega filas dos agentes
$sql = "SELECT `filas` FROM `user` WHERE `tipo` = 'agente' AND `status` = '1'";
$agentes = $db->query($sql);
$GLOBALS['agentes'] = $agentes->fetchAll();

function qtdAgentes($fila){
  $fila = "#-$fila-#";
  $agentes = $GLOBALS['agentes'];
  $count = 0;
  foreach ($agentes as $ag) {
    if(strpos($ag['filas'], $fila)){
      $count++;
    }
  }
  return $count;
}


?>
