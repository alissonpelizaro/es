<?php

/**
* Objeto de envio de mensagens das Midias sociais
* Autor:  Alisson Pelizaro
*/
class Msg extends Ambiente{

  public $url;
  private $plataforma;
  private $dst;
  private $msg;
  private $conf;
  private $media = false;
  private $mediaType;

  public function __construct(){
    $this->conf = new Ambiente;
  }

  public function sendMessage(){
    //echo "tentou enviar ->".$this->msg;
    if($this->getPlataforma() == 'whatsapp'){
      $this->conf->setUrlWhatsApp($this->getDst(), $this->getMsg());
      $this->url = $this->conf->getWhatsAppUrl();
      return $this->curlExecs('whatsapp');
      // DEBUG: return $this->url;
    } else if($this->plataforma == 'telegram'){
      $this->conf->setUrlTelegram($this->getDst(), $this->getMsg());
      $this->url = $this->conf->getTelegramUrl();
      return $this->curlExecs('telegram');
    } else if($this->plataforma == 'enterness'){
      $this->url = $this->conf->orquestrador .'//server_send/';
      return $this->curlExecsEnterness();
    }
  }

  private function curlExecsEnterness(){

    $header = array('Authorization: Bearer 4ccbb678-1efe-397e-a52f-42a2aa2bfea1');

    $params = array(
      'auth' => $this->conf->bearer,
      'token' => $this->dst,
      'msg' => $this->msg
    );

    $cr = curl_init();
    curl_setopt($cr, CURLOPT_URL, $this->url);
    curl_setopt($cr, CURLOPT_POST, true);
    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cr, CURLOPT_POSTFIELDS, $params);
    curl_setopt($cr, CURLOPT_HTTPHEADER, $header);
    $response = curl_exec($cr);
    curl_close($cr);

    // DEBUG: echo $this->url."\n";

    return $response;

  }


  private function curlExecs($plataforma){

    if($plataforma == 'whatsapp'){
      $curl = curl_init();
      //echo $this->url;
      if(!$this->media){
        curl_setopt_array($curl, array(
          CURLOPT_PORT => $this->conf->getWhatsAppPort(),
          CURLOPT_URL => $this->url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"message\"\r\n\r\n".$this->msg."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
          CURLOPT_HTTPHEADER => array(
            "auth-key: ".$this->conf->getTokenWhatsApp(),
            "client_id: ".$this->conf->getUserApi(),
            "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
          )
        ));
      } else {
        curl_setopt_array($curl, array(
          CURLOPT_PORT => $this->conf->getWhatsAppPort(),
          CURLOPT_URL => $this->url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"message\"; filename=\"C:\\Users\\admin\\Pictures\\Saved Pictures\\download.png\"\r\nContent-Type: image/png\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"message\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
          CURLOPT_HTTPHEADER => array(
            "Postman-Token: a7e4a3bb-c0bc-4492-b930-2c598bdab8d8",
            "auth-key: QjbXR1w2f0bRB55M8jrF2tBU6tpTkkAu",
            "cache-control: no-cache",
            "client_id: t00",
            "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
          ),
        ));
      }


      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "Send message cURL Error #:" . $err;
      } else {
        return $response;
      }
    } /*

    $header = array('Authorization: Bearer 4ccbb678-1efe-397e-a52f-42a2aa2bfea1');

    $cr = curl_init();
    curl_setopt($cr, CURLOPT_URL, $this->url);
    curl_setopt($cr, CURLOPT_POST, true);
    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cr, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($cr, CURLOPT_HTTPHEADER, $header);
    $response = curl_exec($cr);
    echo curl_getinfo($cr, CURLINFO_HTTP_CODE);
    curl_close($cr);
*/
    // DEBUG: echo $this->url."\n";

    //$response = shell_exec('curl -k -X POST "'.$this->url.'" -H "accept: application/json" -H "Content-Type: application/json" -H "Authorization: Bearer 4ccbb678-1efe-397e-a52f-42a2aa2bfea1" -d "{ \"payload\": \"string\"}"');

    //echo $response;

    return $response;

  }

  //Funções GETTERS e SETTERS
  public function getPlataforma(){
    return $this->plataforma;
  }

  public function setPlataforma($v){
    $this->plataforma = $v;
  }

  public function getMsg(){
    return $this->msg;
  }

  public function setMsg($v){
    $this->msg = $v;
  }

  public function getDst(){
    return $this->dst;
  }

  public function setDst($v){
    if($this->getPlataforma() == 'whatsapp'){
      if(strpos($v, '@c.us') === false){
        $v .= '@c.us';
      }
    }
    $this->dst = $v;
  }

  public function setMedia($v, $t){
    $this->media = $v;
    $this->mediaType = $t;
  }

}
?>
