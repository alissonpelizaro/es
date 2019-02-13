<?php
/**
 *
 */

 header('Access-Control-Allow-Origin: *');

class Api extends Msg {

  public $access;
  private $db;

  public function __construct(){
    $this->access = new Ambiente;
    $this->db = $this->connection();
  }

  public function checaAtendimento($token, $origin){
    $sql = "SELECT `idAtendimento` FROM `atendimento` WHERE `status` = 0 AND `plataforma` = '$origin' AND `remetente` = '$token'";
    $tt = $this->db->query($sql);
    $tt = $tt->fetchAll();

    if(count($tt) == 0){
      return false;
    } else {
      return $tt[0]['idAtendimento'];
    }
  }

  public function getMessages($at){
    $sql = "SELECT `chat`, `rmt` FROM `chat_atendimento` WHERE `idAtendimento` = '$at'";
    $chat = $this->db->query($sql);
    return $chat->fetchAll();
  }

  public function getQueues(){
    $sql = "SELECT `idFila`, `nomeFila` AS `nome`, `nomeFila` AS `fantasia` FROM `fila` WHERE `status` = '1' ORDER BY `priority`";
    $filas = $this->db->query($sql);
    $filas = $filas->fetchAll();
    if(count($filas) > 0){
      return $filas;
    } else {
      return false;
    }
  }

  private function connection(){
    //Função de conexão com o DB
    $control = new PDO("mysql:host=".$this->access->dbHost().";dbname=".$this->access->dbDatabase(), $this->access->dbUser(), $this->access->dbPass());
    return $control;
  }
}

?>
