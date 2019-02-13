<?php
  session_start();

  /*
    Arquivo para carregar a aplicação EXTERNAMENTE
    (Sem precisar estar logado)
  */

  include 'config/Model.php';
  include 'config/config.php';
  include 'config/database.php';
  include 'config/security.php';
  include 'config/facilidades.php';
  include 'config/Util.php';

  if(!defined('__CALLBASE')){
    //Acesso direto ao script --> Penetração
    abortAccess();
  }


 ?>
