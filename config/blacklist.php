<?php

/**
* Autor: Alisson Pelizaro (alissonpelizaro@hotmail.com)
* Classe de segurança contra ataque de força bruta e DDOS
*
* (Bloqueia o IP de origem a cada 20 tentativas de conexão sem sucesso)
*/
class Blacklist extends Ambiente {

  private $conf;
  private $db;
  private $ip;

  function __construct(){
    $this->conf = new Ambiente;
    $this->db = $this->connection();
    $this->ip = $this->getIp();
    $this->checaBlacklist();
  }

  public function tryLogin(){
    $qtd = $this->retQuantidade() + 1;
    $this->clearList();
    $this->setBlacklist($qtd);
  }

  public function clearList($param = false){
    if($param == 'all'){
      $sql = "DELETE FROM `blacklist`";
    } else {
      $sql = "DELETE FROM `blacklist` WHERE `ip` = '$this->ip'";
    }
    $this->db->query($sql);
  }

  private function checaBlacklist(){
    $sql = "SELECT count(*) AS `qtd` FROM `blacklist` WHERE `ip` = '$this->ip' AND `stat` = 1";
    $qtd = $this->db->query($sql);
    $qtd = $qtd->fetchAll();
    if($qtd[0]['qtd'] == 0){
      return false;
    } else {
      die("You have no more access on this system. Blocked IP: $this->ip");
    }
  }

  private function setBlacklist($qtd){
    if($qtd > 20){
      $stat = 1;
    } else {
      $stat = 0;
    }
    $data = date('Y-m-d H:i:s');

    $sql = "INSERT INTO `blacklist` (`ip`, `qtd`, `data`, `stat`) VALUES ('$this->ip', '$qtd', '$data', '$stat')";
    if($this->db->query($sql)){
      return true;
    } else {
      return false;
    }
  }

  public function retQuantidade(){
    $sql = "SELECT `qtd` FROM `blacklist` WHERE `ip` = '$this->ip'";
    $qtd = $this->db->query($sql);
    $qtd = $qtd->fetchAll();
    if(count($qtd) == 0){
      return 0;
    } else {
      return $qtd[0]['qtd'];
    }
  }

  private function getIP() {

    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')){
      $ipaddress = getenv('HTTP_CLIENT_IP');
    } else if(getenv('HTTP_X_FORWARDED_FOR')){
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    } else if(getenv('HTTP_X_FORWARDED')){
      $ipaddress = getenv('HTTP_X_FORWARDED');
    } else if(getenv('HTTP_FORWARDED_FOR')){
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    } else if(getenv('HTTP_FORWARDED')){
      $ipaddress = getenv('HTTP_FORWARDED');
    } else if(getenv('REMOTE_ADDR')){
      $ipaddress = getenv('REMOTE_ADDR');
    } else {
      $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
  }


  private function getIpOld(){

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      return $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      return $_SERVER['REMOTE_ADDR'];
    }

  }

  private function connection(){
    $control = new PDO("mysql:host=".$this->conf->dbHost().";dbname=".$this->conf->dbDatabase(), $this->conf->dbUser(), $this->conf->dbPass());
    if($control){
      return $control;
    } else {
      die('Erro ao conectar no banco de dados.');
    }
  }

}


?>
