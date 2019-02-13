<?php
include '../coreExt.php';

/*
* Retorna um JSON com a seginte estrutura:
*
* 'status' => 0,
* 'qr' => false
*
* status = Situação da API (
* * 0: API sem resposta,
* * 1: Sincronismo ok
* * 2: Não sincronizado
* * 3: Cliente inexistente
* * 4: Impossivel capturar o QR Code
* )
*
*/

// Tempo máximo de execução: 2 min;
set_time_limit(240);

$util = new Util;

$client = $config->getUserApi();

$ret = array(
  'status' => false,
  'qr' => false
);


if(!isset($_POST['qrRequest'])){
  $sit = $util->whatsAppStatus();
  //Testa comunicações
  if($sit){

    $sit = json_decode($sit);
    if(isset($sit->$client)){

      if($sit->$client->is_logged_in == 1){
        //Sincronismo ok
        $ret['status'] = 1;
      } else {
        //Não sincronizado
        $ret['status'] = 2;
      }
    } else {
      //Cliente não existe

      //Vai tentar criá-lo na API
      if($util->insertWhatsAppClientId()){
        $sit = $util->whatsAppStatus();

        if($sit){
          $sit = json_decode($sit);
          if(isset($sit->$client)){
            if($sit->$client->is_logged_in == 1){
              //Sincronismo ok
              $ret['status'] = 1;
            } else {
              //Não sincronizado
              $ret['status'] = 2;
            }
          } else {
            //Tentou recriar o client_id e não conseguiu
            $ret['status'] = 3;
          }
        } else {
          //API sem resposta
          $ret['status'] = 0;
        }

      } else {
        //Tentou recriar o client_id e não conseguiu
        $ret['status'] = 3;
      }
    }
  } else {
    //API sem resposta
    $ret['status'] = 0;
  }

  if($ret['status'] == 2){
    $ret['qr'] = /*$util->getWhatsAppQR()*/1;
    if(!$ret['qr']){
      //Impossivel capturar o QR Code
      $ret['status'] = 4;
    }
  }

  echo json_encode($ret);

} else {
  $array = array(
    'error' => false,
    'target' => false,
    'sinc' => false
  );

  //Carrega QR CODE
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_PORT => $config->getWhatsAppPort(),
    CURLOPT_URL => $config->getWhatsAppServer()."screen",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => array(
      "Postman-Token: 63433460-6a9a-45fd-9390-1fb5e72d4f47",
      "auth-key: ".$config->getTokenWhatsApp(),
      "cache-control: no-cache",
      "client_id: ".$config->getUserApi()
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);

  curl_close($curl);

  if (!$err) {

    $targetPath = '../my/assets/medias/whatsApp/';

    if($objs = glob($targetPath."*")){
      foreach($objs as $obj) {
        unlink($obj);
      }
    }

    $newName = geraSenha(30).".png";
    $targetPath .= $newName;
    $array['target'] = $newName;

    $fp = fopen($targetPath,'x');
    fwrite($fp, $response);
    fclose($fp);


    list($largura, $altura) = getimagesize($targetPath);

    if($largura > 500) {
      $array['sinc'] = true;
      $array['target'] = false;
    } else {
      $array['sinc'] = false;
    }

  } else {
    $array['error'] = $err;
  }

  echo json_encode($array);

}

die();

?>
