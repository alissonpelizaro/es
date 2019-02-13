<?php

/**
 * CLASSE DE COMANDOS DO CRONTAB
 */

class Crontab extends Ambiente {

  public function limpaUsuario(){
    $db = $this->connection();
    $data = $this->retDataConfigurada();
    $sql = "DELETE FROM `user` WHERE `ultimoRegistro` < '$data' AND `status` = '0'";

    if($db->query($sql)){
      return true;
    } else {
      return false;
    }
  }

  public function limpaMural(){
    $db = $this->connection();
    $data = $this->retDataConfigurada();
    $hoje = date('Y-m-d H:i:s');
    $sql = "DELETE FROM `mural` WHERE (`data` < '$data' AND `status` = '0') OR (`expira` < '$hoje' AND `expira` != '0000-00-00 00:00:00')";

    if($db->query($sql)){
      return true;
    } else {
      return false;
    }
  }

  public function limpaLembrete(){
    $db = $this->connection();
    $data = $this->retDataConfigurada();
    $sql = "DELETE FROM `lembrete` WHERE `dataCadastro` < '$data' AND `status` = '0'";

    if($db->query($sql)){
      return true;
    } else {
      return false;
    }
  }

  public function limpaGrupo(){
    $db = $this->connection();
    $data = $this->retDataConfigurada();
    $sql = "DELETE FROM `grupo` WHERE `dataCadastro` < '$data' AND `status` = '0'";

    if($db->query($sql)){
      return true;
    } else {
      return false;
    }
  }

  public function limpaChat(){
    $db = $this->connection();
    $data = $this->retDataConfigurada();
    $sql = "DELETE FROM `chat` WHERE `dataEnvio` < '$data'";

    if($db->query($sql)){
      return true;
    } else {
      return false;
    }
  }

  public function limpaCatWiki(){
    $db = $this->connection();
    $data = $this->retDataConfigurada();
    $sql = "DELETE FROM `cat_wiki` WHERE `status` = '0'";

    if($db->query($sql)){
      return true;
    } else {
      return false;
    }
  }

  public function limpaBroadcast(){
    $db = $this->connection();
    $data = $this->retDataConfigurada();
    $sql = "DELETE FROM `broadcast` WHERE `data` < '$data'";

    if($db->query($sql)){
      return true;
    } else {
      return false;
    }
  }

  private function retDataConfigurada(){
    return date('Y-m-d H:i:s', strtotime("-".$this->getDiasCrontab()." days",strtotime(date('Y-m-d H:i:s'))));
  }

  private function getDiasCrontab(){
    $db = $this->connection();
    $sql = "SELECT `diasCrontab` FROM `licenca` WHERE `chave` = '1'";
    $dias = $db->query($sql);
    $dias = $dias->fetchAll();
    $dias = $dias[0];

    return $dias['diasCrontab'];
  }


  private function connection(){
    $access = new Ambiente;
    $control = new PDO("mysql:host=".$access->dbHost().";dbname=".$access->dbDatabase(), $access->dbUser(), $access->dbPass());
    return $control;
  }

}
 ?>
