<?php

/**
*
*/
class Logs{

  private $db;
  private $usr = false;
  private $acao = false;
  private $ferramenta = false;
  private $atendimento = false;
  private $obs = false;

  function __construct(){
    $this->db = $this->connection();
  }

  /* FUNÇÕES SETTERS */
  public function setUser($var){
    $this->usr = $var;
  }

  public function setAcao($var){
    $this->acao = $var;
  }

  public function setObs($var){
    $this->obs = $var;
  }

  public function setFerramenta($var){
    $this->ferramenta = $var;
  }

  public function setAtendimento($var){
    $this->atendimento = $var;
  }

  public function teste(){
    return "- Sistema em manutenção... Poderá apresentar inconsistências.";
  }

  public function gravaLog(){
    if(!$this->acao || !$this->ferramenta){
      return false;
    }

    if(!$this->usr){
    	$this->usr =  $_SESSION['id'];
    }

    $data = date('Y-m-d H:i:s');

    if(!$this->atendimento){
      $this->atendimento = 'null';
    } else {
      $this->atendimento = "'".$this->atendimento."'";
    }

    $sql = "INSERT INTO `log` (
      `idUsuario`, `dataLog`, `acao`, `obs`, `ferramenta`, `idAtendimento`
    ) VALUES (
      '$this->usr', '$data', '$this->acao', '$this->obs',
      '$this->ferramenta' , $this->atendimento
    )";

    $this->clearLog();

    if($this->db->query($sql)){
      return true;
    }
    return false;

  }

  private function clearLog(){
    $this->usr = false;
    $this->acao = false;
    $this->obs = false;
    $this->ferramenta = false;
    $this->atendimento = false;

    return false;
  }

  public function gravaLogOld($a, $f, $o = false, $id = false){
    if(!$id){
      $id = $_SESSION['id'];
    }

    if(
      $a == 'Transferiu atendimento' ||
      $a == 'Recebeu transferencia' ||
      $a == 'Estacionou atendimento' ||
      $a == 'Tirou do estacionamento' ||
      $a == 'Finalizado atendimento' ||
      $a == 'Recebeu atendimento' ||
      $a == 'Iniciou atendimento'
    ){
      if(strpos($o, "-") !== false){
        $idAt = explode("-", $o);
        $idAt = $o[0];
        $o = $o[1];
      } else {
        $idAt = $o;
      }
    } else {
      $idAt = "";
    }

    $data = date('Y-m-d H:i:s');

    $sql = "INSERT INTO `log` (
      `idUsuario`, `dataLog`, `acao`, `obs`, `ferramenta`
    ) VALUES ('$id', '$data', '$a', '$o', '$f')";

    if($this->db->query($sql)){
      return true;
    }
    return false;
  }

  public function atualizaObsLog($id, $obs){
    $sql = "UPDATE `log` SET `obs` = '$obs' WHERE `idLog` = '$id'";
    if($this->db->query($sql)){
      return true;
    }
    return false;
  }

  public function retNome($id){
    $sql = "SELECT `nome`, `sobrenome` FROM `user` WHERE `idUser` = '$id'";
    $nome = $this->db->query($sql);
    $nome = $nome->fetchAll();

    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['nome']." ".$nome[0]['sobrenome'];
    }
  }

  public function retGrupo($id){
    $sql = "SELECT `nome` FROM `grupo` WHERE `idGrupo` = '$id'";
    $nome = $this->db->query($sql);
    $nome = $nome->fetchAll();
    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['nome'];
    }
  }

  public function retSetor($id){
    $sql = "SELECT `nome` FROM `setor` WHERE `idSetor` = '$id'";
    $nome = $this->db->query($sql);
    $nome = $nome->fetchAll();
    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['nome'];
    }
  }

  public function retBroadcast($id){
    $sql = "SELECT `broadcast` FROM `broadcast` WHERE `idBroadcast` = '$id'";
    $nome = $this->db->query($sql);
    $nome = $nome->fetchAll();
    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['broadcast'];
    }
  }

  public function retCategoria($id){
    $sql = "SELECT `nomeCat` FROM `cat_wiki` WHERE `idCat` = '$id'";
    $nome = $this->db->query($sql);
    $nome = $nome->fetchAll();
    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['nomeCat'];
    }
  }

  public function retMural($id){
    $sql = "SELECT `titulo` FROM `mural` WHERE `idMural` = '$id'";
    $nome = $this->db->query($sql);
    $nome = $nome->fetchAll();
    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['titulo'];
    }
  }

  public function retWiki($id){
    $sql = "SELECT `titulo` FROM `wiki` WHERE `idWiki` = '$id'";
    $nome = $this->db->query($sql);
    $nome = $nome->fetchAll();
    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['titulo'];
    }
  }

  private function connection(){
    $access = new Ambiente;
    $control = new PDO("mysql:host=".$access->dbHost().";dbname=".$access->dbDatabase(), $access->dbUser(), $access->dbPass());
    return $control;
  }

}

?>
