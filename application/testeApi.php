<?php
$url = "http://68.235.33.35:8100/screen";
echo "<b>Request URL:</b> $url<br>";
echo "<b>Method:</b> POST<br>";

$response = curlExec($url);
//$response = array_reverse($response);
echo "<hr><b>Response:</b> <br><pre>";
print_r($response);

/* DEBUG:
if(is_array($response) && count($response) > 0){
  $response = array_reverse($response);
  foreach ($response as $msg) {
    echo $msg->mensagem;
  }
}*/

function curlExec($servidor){
/*
  $header = array(
    'Authorization: Bearer 4ccbb678-1efe-397e-a52f-42a2aa2bfea1'
  );*/
  $header = array(
    'auth-key' => 'QjbXR1w2f0bRB55M8jrF2tBU6tpTkkAu',
    'client_id' => 'teste'
  );
  echo "<b>Headers:</b><pre>"; print_r($header); echo "</pre>";

  $cr = curl_init();
  curl_setopt($cr, CURLOPT_URL, $servidor);
  curl_setopt($cr, CURLOPT_POST, true);
  curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($cr, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($cr, CURLOPT_HTTPHEADER, $header);

  $response = curl_exec($cr);
  $err = curl_error($cr);// Pegar o status da conexão
  //$response = json_decode($response);
  //$response = json_decode($response);
  echo $err;
  curl_close($cr);

  return $response;
}
 ?>
