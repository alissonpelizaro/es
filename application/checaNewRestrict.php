<?php
include '../coreExt.php';

if(isset($_SESSION['id'])){
  $idUser = $_SESSION['id'];

  $sql = "SELECT `setor` FROM `user` WHERE `idUser` = '$idUser'";
  $ip = $db->query($sql);
  $ip = $ip->fetch();
  $setorUser = $ip['setor'];
  $_SESSION['setor'] = $ip['setor'];

  $util = new Util($setorUser);

  echo $util->getRestrictionStat();
}



?>
