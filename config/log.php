<?php

/*
 *  CLASSE DE GRAVAÇÃO DE LOG DE EVENTOS
 */

class Log extends Ambiente {

  public function gravaLog($a, $f, $o = false){
    $id = $_SESSION['id'];
    $data = date('Y-m-d H:i:s');

    $db = $this->connection();
    $sql = "INSERT INTO `log` (
      `idUsuario`, `dataLog`, `acao`, `obs`, `ferramenta`
    ) VALUES ('$id', '$data', '$a', '$o', '$f')";

    if($db->query($sql)){
      return true;
    } else {
      return false;
    }
  }

  public function retNome($id){
    $db = $this->connection();
    $sql = "SELECT `nome`, `sobrenome` FROM `user` WHERE `idUser` = '$id'";
    $nome = $db->query($sql);
    $nome = $nome->fetchAll();

    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['nome']." ".$nome[0]['sobrenome'];
    }
  }

  public function retGrupo($id){
    $db = $this->connection();
    $sql = "SELECT `nome` FROM `grupo` WHERE `idGrupo` = '$id'";
    $nome = $db->query($sql);
    $nome = $nome->fetchAll();
    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['nome'];
    }
  }


  public function retSetor($id){
    $db = $this->connection();
    $sql = "SELECT `nome` FROM `setor` WHERE `idSetor` = '$id'";
    $nome = $db->query($sql);
    $nome = $nome->fetchAll();
    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['nome'];
    }
  }

  public function retBroadcast($id){
    $db = $this->connection();
    $sql = "SELECT `broadcast` FROM `broadcast` WHERE `idBroadcast` = '$id'";
    $nome = $db->query($sql);
    $nome = $nome->fetchAll();
    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['broadcast'];
    }
  }

  public function retCategoria($id){
    $db = $this->connection();
    $sql = "SELECT `nomeCat` FROM `cat_wiki` WHERE `idCat` = '$id'";
    $nome = $db->query($sql);
    $nome = $nome->fetchAll();
    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['nomeCat'];
    }
  }

  public function retMural($id){
    $db = $this->connection();
    $sql = "SELECT `titulo` FROM `mural` WHERE `idMural` = '$id'";
    $nome = $db->query($sql);
    $nome = $nome->fetchAll();
    if(count($nome) == 0){
      return false;
    } else {
      return $nome[0]['titulo'];
    }
  }

  public function retWiki($id){
    $db = $this->connection();
    $sql = "SELECT `titulo` FROM `wiki` WHERE `idWiki` = '$id'";
    $nome = $db->query($sql);
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
