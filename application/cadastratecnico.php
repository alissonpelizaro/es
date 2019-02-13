<?php
include '../core.php';

/*
* ARQUIVO DE CADASTRO DE NOVO TÉCNICO
* CHAMADA VIA FORMULÁRIO
*
*/

$nome = tratarString($_POST['nome']);
$sobrenome = tratarString($_POST['sobrenome']);
$email = tratarString($_POST['email']);
$ramal = tratarString($_POST['ramal']);
$entrada = tratarString($_POST['entrada']);
$saidaAlmoco = tratarString($_POST['saidaAlmoco']);
$entradaAlmoco = tratarString($_POST['entradaAlmoco']);
$saida = tratarString($_POST['saida']);
//$dias = tratarString($_POST['dias']);
$login = tratarString($_POST['login']);
$senha = md5(tratarString($_POST['senha']));
$data = date("Y-m-d H:i:s");
$casa = $_SESSION['casa'];

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

$sql = "INSERT INTO `user` (
  `nome`,
  `sobrenome`,
  `usuario`,
  `senha`,
  `email`,
  `ramalTec`,
  `tipo`,
  `status`,
  `dataCadastro`,
  `ramal`,
  `avatar`,
  `chat`,
  `filas`
) VALUES (
  '$nome',
  '$sobrenome',
  '$login',
  '$senha',
  '$email',
  '$ramal',
  'tecnico',
  '1',
  '$data',
  '$casa',
  '$avatar',
  'nao',
  '$obs'
)";

if($db->query($sql)){
  header("Location: ../my/tecnicos?cadastro=success");
} else {
  header("Location: ../my/tecnicos?cadastro=failure");
}

?>
