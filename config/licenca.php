<?php

/**
* CLASSE DE CONTROLE DE LICENCA
*/

class Licenca extends Ambiente {

  public function validaLicencaCoordenador(){
    if($this->getTotalCoordenador() < $this->getLicencaCoordenador()){
      return true;
    } else {
      return false;
    }
  }

  public function validaLicencaAdministrador(){
    if($this->getTotalAdministrador() < $this->getLicencaAdministrador()){
      return true;
    } else {
      return false;
    }
  }

  public function validaLicencaSupervisor(){
    if($this->getTotalSupervisor() < $this->getLicencaSupervisor()){
      return true;
    } else {
      return false;
    }
  }

  public function validaLicencaAgente(){
    if($this->getTotalAgente() < $this->getLicencaAgente()){
      return true;
    } else {
      return false;
    }
  }

  public function getTotalCoordenador(){
    $db = $this->connection();
    $sql = "SELECT count(*) AS `total` FROM `user` WHERE `tipo` = 'coordenador' AND `status` = '1'";
    $total = $db->query($sql);
    $total = $total->fetchAll();
    $total = $total[0];

    return $total['total'];
  }

  public function getTotalAdministrador(){
    $db = $this->connection();
    $sql = "SELECT count(*) AS `total` FROM `user` WHERE `tipo` = 'administrador' AND `status` = '1'";
    $total = $db->query($sql);
    $total = $total->fetchAll();
    $total = $total[0];

    return $total['total'];
  }

  public function getTotalSupervisor(){
    $db = $this->connection();
    $sql = "SELECT count(*) AS `total` FROM `user` WHERE `tipo` = 'supervisor' AND `status` = '1'";
    $total = $db->query($sql);
    $total = $total->fetchAll();
    $total = $total[0];

    return $total['total'];
  }

  public function getTotalAgente(){
    $db = $this->connection();
    $sql = "SELECT count(*) AS `total` FROM `user` WHERE `tipo` = 'agente' AND `status` = '1'";
    $total = $db->query($sql);
    $total = $total->fetchAll();
    $total = $total[0];

    return $total['total'];
  }

  public function getLicencaCoordenador(){
    $db = $this->connection();
    $sql = "SELECT `mtdr` FROM `licenca` WHERE `chave` = '1'";
    $total = $db->query($sql);
    $total = $total->fetchAll();
    $total = $total[0];

    return $total['mtdr'];
  }

  public function getLicencaAdministrador(){
    $db = $this->connection();
    $sql = "SELECT `mtmr` FROM `licenca` WHERE `chave` = '1'";
    $total = $db->query($sql);
    $total = $total->fetchAll();
    $total = $total[0];

    return $total['mtmr'];
  }

  public function getLicencaSupervisor(){
    $db = $this->connection();
    $sql = "SELECT `mtvr` FROM `licenca` WHERE `chave` = '1'";
    $total = $db->query($sql);
    $total = $total->fetchAll();
    $total = $total[0];

    return $total['mtvr'];
  }

  public function getLicencaAgente(){
    $db = $this->connection();
    $sql = "SELECT `mtnt` FROM `licenca` WHERE `chave` = '1'";
    $total = $db->query($sql);
    $total = $total->fetchAll();
    $total = $total[0];

    return $total['mtnt'];
  }

  public function getXcontactStatus(){
    $db = $this->connection();
    $sql = "SELECT `sinc`, `ipXcontact`, `ipEnterness` FROM `licenca` WHERE `chave` = '1'";
    $total = $db->query($sql);
    $total = $total->fetchAll();
    $total = $total[0];

    if($total['sinc'] == 1 && $total['ipXcontact'] != "" && $total['ipEnterness'] != ""){
      return array(
        'status' => true,
        'ipXcontact' => $total['ipXcontact'],
        'ipEnterness' => $total['ipEnterness']
      );
    } else {
      return false;
    }

  }


  private function connection(){
    $access = new Ambiente;
    $control = new PDO("mysql:host=".$access->dbHost().";dbname=".$access->dbDatabase(), $access->dbUser(), $access->dbPass());
    return $control;
  }

}
?>
