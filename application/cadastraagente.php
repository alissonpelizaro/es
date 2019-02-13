<?php
include '../core.php';

if(!$licenca->validaLicencaAgente()){
  header('Location: ../my/agentes');
  die;
}

$nome = tratarString($_POST['nome']);
$sobrenome = tratarString($_POST['sobrenome']);
$email = tratarString($_POST['email']);
$login = tratarString($_POST['login']);
$senha = md5(tratarString($_POST['senha']));
$ramal = tratarString($_POST['ramal']);
$chat = tratarString($_POST['chat']);
$filas = $_POST['filas'];
$grupos = $_POST['grupos'];
$agora = date('Y-m-d H:i:s');


$qtdAt = (int)tratarString($_POST['qtdAt']);
$midias = "-#-";
if (isset($_POST['checkboxWhatsapp'])) {
	$midias .= "whatsapp-#-";
}
if (isset($_POST['checkboxEnterness'])) {
	$midias .= "enterness-#-";
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

$str = "-";
foreach ($grupos as $grupo) {
  $str .= tratarString($grupo)."-";
}

$strFilas = "";
foreach ($filas as $fila) {
  $strFilas .= "-#-".tratarString($fila)."-#-";
  $strFilas = str_replace("-#--#-", "-#-", $strFilas);
}

$sql = "INSERT INTO `user` (
  `nome`, `sobrenome`, `usuario`, `senha`, `email`,
  `tipo`, `status`, `dataCadastro`, `ramal`, `avatar`,
  `chat`, `filas`, `setor`, `pausa`, `midias`, `qtdAt`) VALUES (
  '$nome', '$sobrenome', '$login', '$senha', '$email',
  'agente', '1', '$agora', '$ramal', '$avatar', '$chat',
	'$strFilas', '$setorUser', 0, '$midias', $qtdAt)";

if($db->query($sql)){
  $sql = "SELECT `idUser` FROM `user` WHERE `senha` = '$senha' AND `dataCadastro` = '$agora'";
  $user = $db->query($sql);
  $user = $user->fetchAll();
  $user = $user[0]['idUser'];
  foreach ($grupos as $grupo) {
    $sql = "SELECT `agentes` FROM `grupo` WHERE `idGrupo` = '$grupo'";
    $agentes = $db->query($sql);
    $agentes = $agentes->fetchAll();
    $agentes = $agentes[0]['agentes'];
    $agentes = $agentes."-".$user."-";
    $agentes = str_replace('--', '-', $agentes);
    $sql = "UPDATE `grupo` SET `agentes` = '$agentes' WHERE `idGrupo` = '$grupo'";
    $db->query($sql);
  }

  $tmp = "Nome: ".$nome." ".$sobrenome;
  $log->setAcao('Cadastrou um novo agente');
  $log->setFerramenta('MyOmni');
  $log->setObs($tmp);
  $log->gravaLog();
  header('Location: ../my/agentes?cadastro=success');
} else {
  header('Location: ../my/agentes?cadastro=failure');
}

?>
