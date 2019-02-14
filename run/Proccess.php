<?php
/*
 * Motor de sincronismo das mídias sociais
 * Autor: Alisson Pelizaro (dev@enterness.com)
 */

include '../coreExt.php';
require_once 'Sincronismo.php';
require_once 'Database.php';

set_time_limit(0);

$sinc = new Sincronismo;
$run = true;
$eng = 0;
if(isset($argv[1]) && $sinc->checaParam($argv[1])){
  $parametro = $argv[1];
} else {
  echo "Parametro invalido!";
  die();
}
echo "O motor foi iniciado.\n\r";

while($run){
  usleep(1000);
  $sinc->proccess($parametro);

  /* Sincronismo com Xcontact e e-mail a cada 10 loops (~25 seg)*/
  if($eng == 2){
    $eng = 0;
    $conf = new Licenca;
    $conf = $conf->getXcontactStatus();

    if($conf && $conf['status'] && $sinc->checaXcontact($conf['ipXcontact'])){
      $sinc->xcontact($conf['ipXcontact'], $conf['ipEnterness']);
    }

    //Desloga usuários que fecharam o browser
    $sinc->checaSessoes();

    //Checa se exite usuários estacionados que ja podem ser atendidos
    $sinc->checaParked();

  } else {
    $eng++;
  }
}

?>
