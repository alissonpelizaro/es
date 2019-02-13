<?php
include '../core.php';

if(isset($_POST['action']) && $_POST['action'] == 'delete'){
  //É exclusão

  /*
  * CHAMADA VIA AJAX
  */
  $id = tratarString($_POST['crip']);
  $sql = "DELETE FROM `emailfila` WHERE `idEmail` = '$id'";
  if($db->query($sql)){
    echo "success - $id";
  } else {
    echo "failure - $id";
  }
  die;

} else {

  $id = tratarString($_POST['id']);
  $fila = tratarString($_POST['fila']);
  $email = tratarString($_POST['email']);
  $senha = tratarString($_POST['senha']);
  $conn = tratarString($_POST['conexao']);
  $host = tratarString($_POST['host']);
  $port = tratarString($_POST['porta']);
  $ssl = tratarString($_POST['ssl']);

  if($id == ""){
    //Novo email
    $sql = "SELECT count(*) AS `total` FROM `emailfila` WHERE `email` = '$email'";
    $tt = $db->query($sql);
    $tt = $tt->fetchAll();
    if($tt[0]['total'] > 0){
      header("Location: ../my/filas?cadastroemail=failure&registry=".urlencode($email));
      die;
    } else {
      $data = date('Y-m-d H:i:s');
      $sql = "INSERT INTO `emailfila` (`email`, `senha`, `servidor`, `port`, `criptografia`, `fila`, `dataCadastro`, `valid`, `conexao`)
      VALUES ('$email', '$senha', '$host', '$port', '$ssl', '$fila', '$data', 'unknow', '$conn')";
    }
  } else {
    //Edição de email
    $sql = "UPDATE `emailfila` SET
    `email` = '$email',
    `senha` = '$senha',
    `servidor` = '$host',
    `port` = '$port',
    `criptografia` = '$ssl',
    `fila` = '$fila',
    `valid` = 'unknow',
    `conexao` = '$conn'
    WHERE `idEmail` = '$id'
    ";
  }

  if($db->query($sql)){
    if($id == ""){
      header("Location: ../my/filas?cadastroemail=success");
    } else {
      header("Location: ../my/filas?editemail=success");
    }
  } else {
    header("Location: ../my/filas?cadastro=failure");
  }
}

?>
