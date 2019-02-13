<?php
include '../coreExt.php';
$idUser = tratarString($_POST['id']);

$agora = date("Y-m-d H:i:s");

$sql = "UPDATE `user` SET `sessionTime` = '$agora' WHERE `idUser` = '$idUser'";
$db->query($sql);

?>
