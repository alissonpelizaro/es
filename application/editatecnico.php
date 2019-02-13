<?php
include '../core.php';

if(isset($_GET['action'])){
  //Carrega informações da pagina de edição

  $id = tratarString($_GET['action'])/17;

  $sql = "SELECT * FROM `user` WHERE `idUser` = '$id'";
  $user = $db->query($sql);
  $user = $user->fetchAll();

  if(count($user) != 1){
    header("Location: ../my/inicio");
  }
  $user = $user[0];

  $obs = setObs($user['filas']);

} else if(isset($_POST['nome'])){
  //Aplica as edições

  $idUser = tratarString($_POST['hash'])/13;
  $nome = tratarString($_POST['nome']);
  $sobrenome = tratarString($_POST['sobrenome']);
  $email = tratarString($_POST['email']);
  $entrada = tratarString($_POST['entrada']);
  $saidaAlmoco = tratarString($_POST['saidaAlmoco']);
  $entradaAlmoco = tratarString($_POST['entradaAlmoco']);
  $saida = tratarString($_POST['saida']);
  $dias = tratarString($_POST['dias']);

  $avatar = $_FILES['avatar'];
  if($avatar['name'] == ""){
    $avatar = "";
  } else {
    $dir = "../my/assets/avatar/";
    if( $avatar['error'] == UPLOAD_ERR_OK ){
      $extensao = pegaExtensao($avatar['name']);
      $novo_nome  = md5(time()).".".$extensao;
      $enviou = move_uploaded_file($_FILES['avatar']['tmp_name'], $dir.$novo_nome);
      if($enviou){
        $avatar = $novo_nome;
      }
    }
  }

  $obs = $entrada."-".$saidaAlmoco."-".$entradaAlmoco."-".$saida."##";
  foreach ($dias as $dia) {
    $obs .= "-".$dia;
  }

  $sql = "UPDATE `user` SET
  `nome` = '$nome',
  `sobrenome` = '$sobrenome',
  `email` = '$email',
  `filas` = '$obs'";
  if($avatar != ""){
    $sql .=", `avatar` = '$avatar'";
  }
  $sql .= " WHERE `idUser` = '$idUser'";

  if($db->query($sql)){
    header("Location: ../my/tecnicos?edicao=success");
  } else {
    header("Location: ../my/tecnicos?edicao=failure");
  }

} else {
  header("Location: ../my/inicio");
}

function setObs($obs){
  $obs = explode("##", $obs);
  $horarios = explode('-', $obs[0]);
  $entrada = $horarios[0];
  $saidaAlmoco = $horarios[1];
  $entradaAlmoco = $horarios[2];
  $saida = $horarios[3];
  $dias = "";

  $obs = explode('-', $obs[1]);

  foreach ($obs as $dia) {
    if($dia == '1'){
      $dias = "Domingo";
    } else if($dia == "2"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Segunda";
    } else if($dia == "3"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Terça";
    } else if($dia == "4"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Quarta";
    } else if($dia == "5"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Quinta";
    } else if($dia == "6"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Sexta";
    } else if($dia == "7"){
      if($dias != ""){
        $dias .= ", ";
      }
      $dias .= "Sábado";
    }
  }

  return array(
    'dias' => $dias,
    'entrada' => $entrada,
    'saidaAlmoco' => $saidaAlmoco,
    'entradaAlmoco' => $entradaAlmoco,
    'saida' => $saida
  );

}

?>
