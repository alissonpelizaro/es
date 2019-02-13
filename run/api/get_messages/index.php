<?php
include '../../../coreExt.php';
include '../../Api.php';

if(isset($_POST['auth']) && isset($_POST['token'])){
  $auth = tratarString($_POST['auth']);
  $token = tratarString($_POST['token']);

  $api = new Api;

  $msgs = $api->getMessages($api->checaAtendimento($token, 'enterness'));

  foreach ($msgs as $msg) {
    $msg['chat'] = trim(urlencode($msg['chat']));
  }

  echo json_encode($msgs);
}


?>
