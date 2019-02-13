<?php
include '../core.php';

$nome = tratarString($_POST['nome']);
$sobrenome = tratarString($_POST['sobrenome']);
$email = tratarString($_POST['email']);
$login = tratarString($_POST['login']);
$senha = md5(tratarString($_POST['senha']));
$tipo = tratarString($_POST['tipo']);
$agora = date('Y-m-d H:i:s');

// CHECA SE TEM LICENCA DISPONIVEL
if($tipo == 'coordenador'){
  if(!$licenca->validaLicencaCoordenador()){
    header('Location: ../my/coordenadores');
    die;
  }
} else if($tipo == 'administrador'){
  if(!$licenca->validaLicencaAdministrador()){
    header('Location: ../my/administradores');
    die;
  }
} else if($tipo == 'supervisor'){
  if(!$licenca->validaLicencaSupervisor()){
    header('Location: ../my/supervisores');
    die;
  }
} else {
  header('Location: ../my/inicio');
  die;
}

if(isset($_POST['setor'])){
  $setor = tratarString($_POST['setor']);
} else {
  $setor = $setorUser;
}


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

$sql = "INSERT INTO `user` (
  `nome`, `sobrenome`, `usuario`, `senha`, `email`,
  `tipo`, `status`, `dataCadastro`, `avatar`, `chat`, `setor`
) VALUES (
  '$nome', '$sobrenome', '$login', '$senha', '$email',
  '$tipo', '1', '$agora', '$avatar', 'todos', '$setor'
)";

if($db->query($sql)){
  $tmp = 'Nome: '. $nome." ".$sobrenome;
  $log->setAcao('Cadastrou um novo '.$tipo);
  $log->setFerramenta('MyOmni');
  $log->setObs($tmp);
  $log->gravaLog();
  header('Location: ../my/'.$tipo.'es?cadastro=success');
} else {
  header('Location: ../my/'.$tipo.'es?cadastro=failure');
}

?>
