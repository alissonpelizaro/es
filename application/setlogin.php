<?php
//Arquivo para atualização de dados cadastrais
include '../core.php';
$father = false;

if(isset($_POST['where'])){
  $onde = tratarString($_POST['where']);
  if($onde == 'login'){
    $id = tratarString($_POST['id']);
    $casa = tratarString($_POST['casa']);
    $login = tratarString($_POST['login']);

    $sql = "UPDATE `user` SET `usuario` = '$login' WHERE `idUser` = '$id'";

    if($db->query($sql)){
      //echo $sql;
      $father = $casa*17;
      $father = "editacasa?hash=ssl&id=hidden&action=$father&form=edit&update=success";
    }

  } else if($onde == 'senha'){
    //Desenvolver conforme a necessidade
  }
  //Adicionar outras edições aqui
}

if(!$father){
  header("Location: ../my/inicio?status=internalerror");
} else {
  header("Location: ../my/".$father);
}

?>
