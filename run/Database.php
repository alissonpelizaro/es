<?php

/**
* Classe de Instruções SQL do Motor de sincronismo
*/
class Database {

  private $access;
  private $db;

  function __construct()  {
    $this->access = new Ambiente;
    $this->db = $this->connection();
  }

  public function insQuery($tabela, $dados){

    $p = "()";
    $i = 1;
    $cols = array_keys($dados);
    $sql = "INSERT INTO `$tabela` ".$p[0];

    foreach ($cols as $col) {
      $sql .="`$col`";
      if($i < count($cols)){
        $sql .= ", ";
        $i++;
      }
    }

    $sql .= $p[1]. " VALUES " .$p[0];
    $i = 1;
    foreach ($dados as $dado) {
      $sql .= '"'.$dado.'"';
      if($i < count($dados)){
        $sql .= ", ";
        $i++;
      }
    }

    $sql .= $p[1];

    // DEBUG: echo $sql;

    if($this->db->query($sql)){
      return true;
    }
    return false;

  }

  public function getQuery($tabela, $filtro = false, $retorno = false, $intruct = false){

    $ret = "*";
    if($retorno){
      $ret = "";
      foreach ($retorno as $r) {
        if($ret != ""){
          $ret .= ", ";
        }
        if(strpos($r, "(") !== false){
          $ret .= $r;
        } else {
          $ret .= "`".$r."`";
        }
      }
    }

    $sql = "SELECT $ret FROM `".$tabela."`";
    if($intruct){
      $sql .= " WHERE ".$intruct;
    } else {
      if($filtro){
        $instruc = $this->getInstrucao($filtro);
        if($instruc){
          $sql .= " WHERE ".$instruc;
        }
      }
    }

    // DEBUG: echo $sql . "\n\n";

    $ret = $this->db->query($sql);
    $ret = $ret->fetchAll();

    return $ret;
  }

  private function getInstrucao($array){

    if($array){
      $ret = "";
      $inds = array_keys($array);
      foreach ($inds as $k) {
        $val = $array[$k];
        if($ret != ""){
          $ret .= " AND ";
        }
        if(is_array($val)){
          $ret .= "`".$k."` ".$val[0];
          if(isset($val[1])){
            $ret .= " '".$val[1]."'";
          }
        } else {
          $ret .= "`".$k."` = '".$val."'";
        }
      }
      return $ret;
    }
    return false;
  }

  private function connection(){
    //Função de conexão com o DB
    $control = new PDO("mysql:host=".$this->access->dbHost().";dbname=".$this->access->dbDatabase(), $this->access->dbUser(), $this->access->dbPass());
    return $control;
  }

}
?>
