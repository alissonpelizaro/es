<?php
include '../core.php';

$token = geraSenha(20);

$sql = "SELECT * FROM `user` WHERE `tipo` = 'administrador' AND `status` = '1' AND `setor` = '$setorUser'";
$users = $db->query($sql);
$users = $users->fetchAll();

if(count($users) == 0){
  $users = false;
}

?>
