<?php
include '../core.php';

$nome = tratarString($_POST['nome']);
$responsavel = tratarString($_POST['responsavel']);
$telefone = tratarString($_POST['telefone']);
$data = date("Y-m-d H:i:s");

$avatar = tratarString($_POST['icon']);
if($avatar != ""){
  $avatar .= ".png";
}

$sql = "INSERT INTO `casa` (
  `nome`, `responsavel`, `logo`, `dataCadastro`, `status`, `telefone`
) VALUES (
  '$nome', '$responsavel', '$avatar', '$data', '1', '$telefone')";

  if($db->query($sql)){
    $tmp = 'Casa: '. $nome;
    $log->setAcao('Cadastrou uma nova casa');
    $log->setFerramenta('Casas');
    $log->setObs($tmp);
    $log->gravaLog();

    //Cria um usuário GESTOR para a casa

    //Cria um nome de usuario ($login)
    $gestor = explode(" ", $responsavel);
    if(count($gestor) == 0){
      $gestor = $responsavel;
    } else {
      $gestor = $gestor[0];
    }
    $casa = explode(" ", $nome);
    if(count($casa) == 0){
      $casa = $nome;
    } else {
      $casa = $casa[0];
    }
    $login = tiraAcento(strtolower($gestor)).".".tiraAcento(strtolower($casa));
    $senha = md5('mudar123');

    //Checa se login já existe
    $loop = "";
    do{
      $sql = "SELECT count(*) AS `total` FROM `user` WHERE `usuario` = '".$login.$loop."' AND `status` = '1'";
      $qtd = $db->query($sql);
      $qtd = $qtd->fetchAll;
      if($qtd[0]['total'] > 0){
        if($loop == ""){
          $loop = 1;
        }
        $loop++;
      } else {
        $login = $login.$loop;
        $loop = false;
      }
    } while ($loop);

    //Pega o ID da casa criada
    $sql = "SELECT `idCasa` FROM `casa` WHERE `nome` = '$nome' AND `responsavel` = '$responsavel' AND `dataCadastro` = '$data'";
    $idCasa = $db->query($sql);
    $idCasa = $idCasa->fetchAll();
    $idCasa = $idCasa[0]['idCasa'];


    $sql = "INSERT INTO `user`
    (`nome`, `usuario`, `senha`, `tipo`, `status`, `dataCadastro`, `ramal`, `chat`)
    VALUES
    ('$responsavel', '$login', '$senha', 'gestor', '1', '$data', '$idCasa', 'todos')";

    $db->query($sql);

    header('Location: ../my/casas?cadastro=success&casa='.$idCasa);
  } else {
    header('Location: ../my/casas?cadastro=failure');
  }

  ?>
