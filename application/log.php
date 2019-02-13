<?php
include '../core.php';

$filtro = " WHERE";
if(isset($_POST['user'])){
  //Aplica filtros
  
	$dataini = $util->dataHtmlParaBd(tratarString($_POST['dataini']), tratarString($_POST['horaIni']));	
	$datafim = $util->dataHtmlParaBd(tratarString($_POST['datafim']), tratarString($_POST['horaFim']));	
	
  $user = tratarString($_POST['user']);
  $ferramenta = tratarString($_POST['ferramenta']);

  if($dataini != ""){
    $filtro .= " `dataLog` >= '$dataini'";
  }
  if($datafim != ""){
    if($filtro != " WHERE"){
      $filtro .= " AND";
    }
    $filtro .= "`dataLog` <= '$datafim'";
  }
  if($user != ""){
    if($filtro != " WHERE"){
      $filtro .= " AND";
    }
    $filtro .= "`idUsuario` = '$user'";
  }
  if ($ferramenta != "") {
    if($filtro != " WHERE"){
      $filtro .= " AND";
    }
    $filtro .= "`ferramenta` = '$ferramenta'";
  }

}

if($filtro == " WHERE"){
  //Não tem filtros
  $data = date("Y-m-d")." 00:00:00";
  $filtro = " WHERE `dataLog` >= '$data'";
}

//Cria relação de Log
$sql = "SELECT * FROM `log`".$filtro." ORDER BY `dataLog`";
$logs = $db->query($sql);
$logs = $logs->fetchAll();

//Cria array de usuarios
$sql = "SELECT `idUser`, `nome`, `sobrenome`, `tipo` FROM `user` WHERE `status` = '1' ORDER BY `nome`";
$instru = $db->query($sql);
$instru = $instru->fetchAll();

foreach ($instru as $k) {
  $users[$k['idUser']] = array(
    'id' => $k['idUser'],
    'nome' => $k['nome']." ".$k['sobrenome'],
    'tipo' => $k['tipo']
  );
}

?>
