<?php
include '../coreExt.php';

$eu = $_SESSION['id'];

if(!isset($_POST['key'])){

  $token = geraSenha(10);
  $senha = geraSenha(4);

  if($config->getEnvironment() == "PRODUCTION"){
    $dev = "AND `tipo` != 'dev' ";
  } else {
    $dev = "";
  }

  if($_SESSION['chat'] == "sup"){

    $sql = "SELECT `idUser`, `nome`, `sobrenome`, `ultimoRegistro`, `avatar`, `tipo`
    FROM `user` WHERE `tipo` != 'agente' ".$dev."AND `status` = '1' AND `idUser` != '$eu' ORDER BY `logged` DESC, `nome` ASC";
    $contatos = $db->query($sql);
    $contatos = $contatos->fetchAll();

  } else if($_SESSION['chat'] == "todos"){
    if($_SESSION['tipo'] == "agente"){
      $sql = "SELECT `idUser`, `nome`, `sobrenome`, `ultimoRegistro`, `avatar`, `tipo`, `logged`
      FROM `user` WHERE `chat` = 'todos' ".$dev."AND `status` = '1'  AND `idUser` != '$eu' ORDER BY `logged` DESC, `nome` ASC";
    } else {
      $sql = "SELECT `idUser`, `nome`, `sobrenome`, `ultimoRegistro`, `avatar`, `tipo`, `logged`
      FROM `user` WHERE `chat` != 'nao' ".$dev."AND `chat` != '' AND `status` = '1'  AND `idUser` != '$eu' ORDER BY `logged` DESC, `nome` ASC";
    }

    $contatos = $db->query($sql);
    $contatos = $contatos->fetchAll();

  } else {
    $contatos = array();
  }
  echo "<ul>";
  foreach ($contatos as $contato) {
    $status = statusAgente($contato['logged'], $contato['ultimoRegistro']);
    $id = $contato['idUser'];
    if(($eu*311) > ($id*311)){
      $hash = ($id*311)."-".($eu*311);
    } else {
      $hash = ($eu*311)."-".($id*311);
    }

    $sql = "SELECT COUNT(`idChat`) AS 'total' FROM `chat` WHERE `rmt` = '$id' AND `dst` = '$eu' AND `visualizada` = '0'";
    $vis = $db->query($sql);
    $vis = $vis->fetchAll();
    $tt = $vis[0]['total'];
    ?>
    <li>
      <div class="media campo-contato-side" onclick="">
        <div class="avatar-chat border-color-<?php echo $status ?> avatar-side-contatos">
          <img alt="nome" src="assets/avatar/<?php if($contato['avatar'] == ''){ echo 'default.jpg'; } else { echo $contato['avatar']; } ?>">
        </div>
        <div class="media-body contato-lista-chat">
          <h4 class="media-heading-side">
            <<?php if($tt > 0){ echo "b"; } else { echo "d"; } ?>>
            <?php echo $contato['nome']. " " . $contato['sobrenome']; ?>
            </<?php if($tt > 0){ echo "b"; } else { echo "d"; } ?>>
            <?php if($tt > 0){
              ?>
              <span class="label label-info bg-bell" style="color: white;"><b><?php echo $tt ?></b></span>
              <?php
            } ?>
          </h4>
          <p style="color: silver; font-size: 12px; margin-top: -5px;"><?php echo retCargo($contato['tipo']) ?></p>
        </div>
      </div>
    </li>
    <?php
  }
  echo "</ul>";
} else {
  $chave = tratarString($_POST['key']);
  $chave = explode('-', tratarString($chave));


  if(!isset($chave[0]) || !isset($chave[1]) || !isset($chave[2])){
    echo 0;
    die;
  } else {
    if(($chave[0]/311) == $eu){
      $id = $chave[1]/311;
    } else if($chave[1]/311 != $eu){
      $id = $chave[0]/311;
    } else {
      echo 0;
      die;
    }

    if(isset($id)){
      $sql = "SELECT `ultimoRegistro`, `logged` FROM `user` WHERE `idUser` = '$id'";
      $last = $db->query($sql);
      $last = $last->fetchAll();
      if(count($last) == 0){
        echo 0;
        die;
      } else {
        echo statusAgente($last[0]['logged'], $last[0]['ultimoRegistro']);
      }
    }
  }
}
?>
