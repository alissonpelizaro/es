<?php

include '../../../coreExt.php';
include '../../Api.php';

if(isset($_POST['token'])){

  $token = tratarString($_POST['token']);

  $api = new Api;

  if($api->checaAtendimento($token, 'enterness')){
    echo "true";
  } else {
    echo "false";
  }

}

?>
