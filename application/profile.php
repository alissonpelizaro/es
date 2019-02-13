<?php
include '../core.php';

$id = $_SESSION['id'];

$sql = "SELECT * FROM `user` WHERE `idUser` = '$id'";
$user = $db->query($sql);
$user = $user->fetchAll();
if(count($user) == 0){
  header('Location: ../my/login');
  die;
} else {
  $user = $user[0];
}

?>
