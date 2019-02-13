<?php
include '../coreExt.php';

if($_POST['hash'] == '5'){
  //SPLIT 8/8-8/
  $id = $_SESSION['id'];
  $data = date("Y-m-d H:i:s");

  $sql = "SELECT `idLembrete`, `titulo`, `desc` FROM `lembrete` WHERE `idUser` = '$id' AND `alarme` != '1000-01-01 00:00:00' AND `alarme` <= '$data'";
  $post = $db->query($sql);
  $post = $post->fetchAll();

  if(isset($post[0])){
    echo $post[0]['idLembrete']."8/8-8/".$post[0]['titulo']." - ".$post[0]['desc'];
  } else {
    echo "false";
  }
} else if($_POST['hash'] == '3'){
  $id = tratarString($_POST['id']);
  $data = date('Y-m-d H:i:s', strtotime('+5 minute', strtotime(date('Y-m-d H:i:s'))));
  $sql = "UPDATE `lembrete` SET `alarme` = '$data' WHERE `idLembrete` = '$id'";
  $db->query($sql);
} else if($_POST['hash'] == '19'){
  $id = tratarString($_POST['id']);
  $sql = "DELETE FROM `lembrete` WHERE `idLembrete` = '$id'";
  $db->query($sql);
}


?>
