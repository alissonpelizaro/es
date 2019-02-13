<?php
include '../coreExt.php';

if(isset($_POST['value'])){
  $email = tratarString($_POST['value']);
  $sql = "SELECT * FROM `user` WHERE `email` = '$email'";
  $tt = $db->query($sql);
  $tt = $tt->fetchAll();
  if(count($tt) == 1){
    $token = geraSenha(30);
    $sql = "UPDATE `user` SET `recovery` = '$token' WHERE `email` = '$email'";
    if($db->query($sql)){
      echo $token;
    } else {
      echo 0;
    }
  } else {
    echo 0;
  }

} else if(isset($_POST['token'])){

  $email = tratarString($_POST['email']);
  $token = tratarString($_POST['token']);

  sendMailRecovery($email, $token);

}

?>
