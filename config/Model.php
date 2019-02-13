<?php


/**
*
*/
class Model{

  private $db;
  private $config;

  function __construct() {
    $this->config = new Ambiente;
    $this->db = $this->connection();
  }

  public function getQuery($tab, $filtro = false, $intruct = false){
    $sql = "SELECT * FROM `".$tab."`";

    if($intruct){
      $sql = " WHERE ".$intruct;
    } else {
      if($filtro){
        $instruc = $this->getInstrucao($filtro);
        if($instruc){
          $sql .= " WHERE ".$instruc;
        }
      }
    }

    $ret = $this->db->query($sql);
    $ret = $ret->fetchAll();

    return $ret;

  }

  private function getInstrucao($array){

    if($array){
      $ret = "";
      $inds = array_keys($array);

      foreach ($inds as $k) {
        $val = tratarString($array[$k]);

        if($ret != ""){
          $ret .= " AND ";
        }
        $ret .= "`".$k."` = '".$val."'";

      }
      return $ret;
    }
    return false;
  }

  //Prepara conexão com o BD
  private function connection(){
    $control = new PDO("mysql:host=".$this->config->dbHost().";dbname=".$this->config->dbDatabase(), $this->config->dbUser(), $this->config->dbPass());
    return $control;
  }

}
?>
