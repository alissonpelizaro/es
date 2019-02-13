<?php
include '../core.php';

if(!isset($_GET['cript']) || !isset($_GET['id'])){
  header('Location: ../my/filas');
} else {
  $id = tratarString($_GET['id'])/53;
  $fila = $db->query("SELECT * FROM `fila` WHERE `idFila` = '$id'");
  $fila = $fila->fetchAll();
  if(count($fila) == 0){
    header('Location: ../my/filas');
  } else {
    $fila = $fila[0];
  }
}

//Pega relação de filas cadastradas p/ transbordo
$nome = $fila['nomeFila'];
$sql = "SELECT * FROM `fila` WHERE `nomeFila` != '$nome' ORDER BY `nomeFila`";
$filas = $db->query($sql);
$filas = $filas->fetchAll();


//Pega relação de agentes
$sql = "SELECT `idUser`, `nome`, `sobrenome`, `ramal`, `filas` FROM `user` WHERE (`tipo` = 'agente' OR `tipo` = 'supervisor') AND `status` = '1' ORDER BY `nome`";
$agts = $db->query($sql);
$agts = $agts->fetchAll();

$inside = array();
$i = 0;
$outside = array();
$o = 0;

$nome = "#-$nome-#";

//Percorre e encontra os agentes que estão na fila
foreach ($agts as $agt) {
  if(strpos($agt['filas'], $nome)){
    $inside[$i] = array(
      'id' => $agt['idUser'],
      'nome' => $agt['nome']." ".$agt['sobrenome'],
      'ramal' => $agt['ramal']
    );
    $i++;
  } else {
    $outside[$o] = array(
      'id' => $agt['idUser'],
      'nome' => $agt['nome']." ".$agt['sobrenome'],
      'ramal' => $agt['ramal']
    );
    $o++;
  }
}

?>
