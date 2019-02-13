<?php
include '../coreExt.php';

$token = tratarString($_POST['token']);
$senha = md5(tratarString($_POST['senha']));

$sql = "UPDATE `user` SET `senha` = '$senha', `token` = '' WHERE `recovery` = '$token'";

if($db->query($sql)){
  header("Location: ../my/login?recovery=success");
} else {
  header("Location: ../my/login?recovery=failure");
}

 ?>
