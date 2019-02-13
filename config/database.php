<?php

  $config = new Ambiente;
  define('__CALLBASE', true);

  try {
    $db = new PDO("mysql:host=".$config->dbHost().";dbname=".$config->dbDatabase(), $config->dbUser(), $config->dbPass());
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    // DEBUG: echo 'ERROR: ' . $e->getMessage();
  }

  if(!isset($db)){
    echo "Erro ao conectar no banco de dados. Cod 250";
    die;
  }

  $model = new Model;

 ?>
