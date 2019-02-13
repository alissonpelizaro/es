<?php

/*
* Classe de requisição HTTP usada no envio de midias para o whatsapp durante um atendimento
*/

class HttpRequest {
  public $url = null;
  public $method = 'GET';
  public $body = null;
  public $headers = Array();
  public $allow_redirect = true;
  private $url_info = null;
  private $host_name = null;
  private $host_ip = null;
  private $response_body = null;
  private $response_headers = Array();
  private $response_code = null;
  private $response_message = null;
  private $port = null;
  private $verbose = true;
  public function __construct($url, $method = 'GET') {
    $this->url = $url;
    $this->method = $method;
    $this->url_info = parse_url($url);
    $this->host_name = $this->url_info['host'];
    $this->host_ip = gethostbyname($this->host_name);
    if(!isset($this->url_info['port'])) {
      if($this->url_info['scheme'] == "http")
        $this->port = 80;
      else if($this->url_info['scheme'] == "https")
        $this->port = 443;
    } else {
      $this->port = $this->url_info['port'];
    }
    $this->headers["Host"] = "$this->host_name";
    $this->headers["Connection"] = "close";
  }
  private function constructRequest() {
    $path = "/";
    if(isset($this->url_info['path']))
      $path = $this->url_info['path'];
    $req = "$this->method $path HTTP/1.1\r\n";
    foreach($this->headers as $header => $value) {
      $req .= "$header: $value\r\n";
    }

    return "$req\r\n";
  }
  function readLine($fp)
  {
    $line = "";
    while (!feof($fp)) {
      $line .= fgets($fp, 2048);
      if (substr($line, -1) == "\n") {
        return rtrim($line, "\r\n");
      }
    }
    return $line;
  }
  public function send() {
    $fp = fsockopen($this->host_ip, $this->port);
    $request = $this->constructRequest();
    fwrite($fp, $request);
    $line = $this->readline($fp);
    $status = explode(" ", $line);
    if(!isset($status[0]) || !preg_match("/^HTTP\/\d+\.?\d*/", $status[0]))
      die("Versão do HTTP impossivel de capturar.");
    if(!isset($status[1]))
      die("Impossivel pegar o código de resposta da requisição.");
    else $this->response_code = $status[1];
    if(!isset($status[2]))
      die("Impossivel pegar a razão da resposta da requisição.");
    else $this->response_reason = $status[2];
    do {
      $line = $this->readLine($fp);
      if($line != "") {
        $header = explode(":", $line);
        $this->response_headers[$header[0]] = ltrim($header[1]);
      }
    } while(!feof($fp) && $line != "");
    $this->response_body = "";
    do {
      $line = $this->readLine($fp); {
      if($line)
        $this->response_body .= "$line\n";
      }
    } while(!feof($fp));
    fclose($fp);
    return TRUE;
  }
  public function getStatus() {
    return $this->response_code;
  }
  public function getHeaders() {
    return $this->response_headers;
  }
  public function getResponseBody() {
    return $this->response_body;
  }
}

?>
