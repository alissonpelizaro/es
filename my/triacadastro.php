<?php
include '../core.php';

if($_GET['tipo'] == 'sup'){
  //Define nível de restrição da página
  $allowUser = array('dev', 'coordenador', 'administrador');
  checaPermissao($allowUser);
  $token = geraSenha(15);
} else if($_GET['tipo'] == 'adm'){
  //Define nível de restrição da página
  $allowUser = array('dev', 'coordenador');
  checaPermissao($allowUser);
  $token = geraSenha(20);
} else if($_GET['tipo'] == 'coord'){
  //Define nível de restrição da página
  $allowUser = array('dev');
  checaPermissao($allowUser);
  $token = geraSenha(17);
}

if(isset($token)){
  header('Location: novousuario?token='.$token);
} else {
  backStart();
}

?>
