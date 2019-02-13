<?php
include '../core.php';

/* Pagina que recebe o ID de um cliente e o nome de uma plataforma
e inicia atvamente um atendimento com esse cliente

Recebe GET ou POST

hash = id do cliente (criptografia 31)
plataforma = nome da plataforma para dar o ativo

*/

if(isset($_GET['hash']) && isset($_GET['plataforma'])){
  $idCliente = tratarString($_GET['hash'])/31;
  $plataforma = $_GET['plataforma'];
} else if(isset($_POST['hash']) && isset($_POST['plataforma'])){
  $idCliente = tratarString($_POST['hash'])/31;
  $plataforma = $_POST['plataforma'];
} else {
  header('Location: ../my/editaCliente?hash='.($idCliente * 951).'&failure=paramLost');
  die;
}


try {
  $sql = "SELECT * FROM `cliente` WHERE `idCliente` = '$idCliente'";
  $cliente = $db->query($sql);
  $cliente = $cliente->fetch();

  if($plataforma == 'whatsapp'){
    if($cliente['fone'] != ""){
      $data = date("Y-m-d H:i:s");
      $fones = json_decode($cliente['fone']);
      foreach ($fones as $k) {
        if($k[0]){
          $fone = $k[1];
          break;
        }
      }
      if(!isset($fone)){
        header('Location: ../my/editaCliente?hash='.($idCliente * 951).'&failure=numberInactive');
      }
      $remetente = $util->celParaWhatsApp($cliente['fone']);
      if($util->emAtendimento($remetente)){
        header('Location: ../my/editaCliente?hash='.($idCliente * 951).'&failure=emAtendimento');
        die;
      }
      $fila = $util->getUserInfo();
      $fila = $util->geraArrayFilas($fila->filas, false);
      $nome = $cliente['nome'];

    } else {
      header('Location: ../my/editaCliente?hash='.($idCliente * 951).'&failure=phoneLost');
      die;
    }
  } //Adicionar plataformas aqui
  else {
    header('Location: ../my/editaCliente?hash='.($idCliente * 951).'&failure=platformLost');
    die;
  }

  $sql = "INSERT INTO `atendimento` (
    `origem`,
    `dataInicio`,
    `status`,
    `remetente`,
    `idAgente`,
    `plataforma`,
    `fila`,
    `nome`,
    `pendente`,
    `idCliente`
  ) VALUES (
    'interno',
    '$data',
    '0',
    '$remetente',
    '$idUser',
    '$plataforma',
    '$fila',
    '$nome',
    '0',
    '$idCliente'
  )";

  if($db->query($sql)){

    $sql = "SELECT `idAtendimento` FROM `atendimento` WHERE `status` = 0 AND
    `remetente` = '$remetente' AND `idAgente` = '$idUser'";
    $at = $db->query($sql);
    $at = $at->fetch();
    if(isset($at['idAtendimento'])){

      $log->setAcao('Iniciou atendimento');
      $log->setFerramenta('Medias');
      $log->setAtendimento($at['idAtendimento']);
      $log->gravaLog();

      header('Location: ../my/media?hash='.($at['idAtendimento'] * 253).'&token='.geraSenha(15));
      die;
    }
    header('Location: ../my/editaCliente?hash='.($idCliente * 951).'&failure=createFailure');
    die;
  } else {
    header('Location: ../my/editaCliente?hash='.($idCliente * 951).'&failure=createFailure');
    die;
  }


} catch (\Exception $e) {
  die($e);
}










 ?>
