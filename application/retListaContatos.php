<?php
include '../coreExt.php';

$eu = $_SESSION['id'];

if(!isset($_POST['key'])){

  $token = geraSenha(10);
  $senha = geraSenha(4);

  if(isset($_POST['opened'])){
    $open = tratarString($_POST['opened']);
  } else {
    $open = "";
  }

  $search = tratarString($_POST['search']);

  if($config->getEnvironment() == "PRODUCTION"){
    $dev = "AND `tipo` != 'dev' ";
  } else {
    $dev = "";
  }

  if($search != ""){

    if(strpos($search, " ") !== false){
      $dev .= "AND (";
        $search = explode(" ", $search);
        $ttS = count($search);
        foreach ($search as $k) {
        $dev .= "`nome` LIKE '%$k%' OR `sobrenome` LIKE '%$k%' OR `tipo` LIKE '%$k%'";
        $ttS--;
        if($ttS > 0){
        $dev.= " OR ";
        }
        }
        $dev .= ")";
      } else {
        $dev .= "AND (`nome` LIKE '%$search%' OR `sobrenome` LIKE '%$search%' OR `tipo` LIKE '%$search%') ";
      }
    }


    //Carrega setores criados
    $sql = "SELECT * FROM `setor`";
    $setores = $db->query($sql);
    $setores = $setores->fetchAll();

    //Carega usuarios do sistema
    if($_SESSION['chat'] == "sup"){
      $sql = "SELECT `idUser`, `nome`, `sobrenome`, `ultimoRegistro`, `avatar`, `tipo`, `setor`
      FROM `user` WHERE `tipo` != 'agente' ".$dev."AND `status` = '1'
      AND `idUser` != '$eu' ORDER BY `logged` DESC, `nome` ASC";
    }  else if($_SESSION['chat'] == "todos"){
      if($_SESSION['tipo'] == "agente"){
        $sql = "SELECT `idUser`, `nome`, `sobrenome`, `ultimoRegistro`, `avatar`, `tipo`, `logged`, `setor`
        FROM `user` WHERE `chat` = 'todos' ".$dev."AND `status` = '1'  AND `idUser` != '$eu'
        ORDER BY `logged` DESC, `nome` ASC";
      } else {
        $sql = "SELECT `idUser`, `nome`, `sobrenome`, `ultimoRegistro`, `avatar`, `tipo`, `logged`, `setor`
        FROM `user` WHERE `chat` != 'nao' ".$dev."AND `chat` != '' AND `status` = '1'
        AND `idUser` != '$eu' ORDER BY `logged` DESC, `nome` ASC";
      }
    }
    $contatos = $db->query($sql);
    $contatos = $contatos->fetchAll();


    //Carrega mensagens não visualizadas
    $sql = "SELECT `rmt` FROM `chat` WHERE `dst` = '$eu' AND `visualizada` = '0'";
    $msgs = $db->query($sql);
    $msgs = $msgs->fetchAll();


    //Prepara arrays
    $usersArray = array();
    foreach ($contatos as $user) {
      $tt = 0;

      foreach ($msgs as $msg) {
        if($msg['rmt'] == $user['idUser']){
          $tt++;
        }
      }

      $usersArray[count($usersArray)] = array(
        'nome' => $user['nome']. " " . $user['sobrenome'],
        'setor' => $user['setor'],
        'status' => statusAgente($user['logged'], $user['ultimoRegistro']),
        'tipo' => retCargo($user['tipo']),
        'avatar' => $user['avatar'],
        'lastData' => $user['ultimoRegistro'],
        'tt' => $tt,
        'id' => $user['idUser']
      );
    }

    $setoresArray = array();
    $agentes = array();
    $ind = 0;
    $rmsArray = array();

    foreach ($setores as $set) {
      $tt = 0;
      $ton = 0;
      $tmpU = array();

      foreach ($usersArray as $agt) {

        if($agt['setor'] == $set['idSetor']){

          $tmpU[count($tmpU)] = array(
            'nome' => $agt['nome'],
            'setor' => $agt['setor'],
            'status' => $agt['status'],
            'tipo' => $agt['tipo'],
            'avatar' => $agt['avatar'],
            'lastData' => $agt['lastData'],
            'tt' => $agt['tt'],
            'id' => $agt['id']
          );

          $tt = $tt + $agt['tt'];

          if($agt['status'] == 'online'){
            $ton++;
          }
        }

      }
      $setoresArray[$ind] = array(
        'setor' => $set['nome'],
        'id' => $set['idSetor'],
        'tt' => $tt,
        'ton' => $ton,
        'agts' => $tmpU
      );

      if(count($tmpU) == 0){
        $rmsArray[count($rmsArray)] = $ind;
      }

      $ind++;
    }

    //Remove retores sem agentes cadastrados
    foreach ($rmsArray as $k){
      unset($setoresArray[$k]);
    }

    if(count($setoresArray) > 0){
      $auxF = 0;
      ?>
      <div class="accordion show" id="accordionContatos">

        <?php foreach ($setoresArray as $set){ ?>
          <div class="collapseContatosBorder">
            <div class="chat-section-group" id="heading<?php echo $auxF; ?>">
              <h5>
                <button id="btnCollapse<?php echo $auxF; ?>" class="btn btn-link text-info p-t-7 cursorPointer p-b-0 <?php if($set['tt'] > 0){ echo "font-weight-bold"; }?>" onclick="setSectionOpen('<?php echo $set['id']; ?>')" type="button" data-toggle="collapse" data-target="#collapse<?php echo $auxF; ?>" aria-expanded="true" aria-controls="collapse<?php echo $auxF; ?>"><?php echo $set['setor'] ?> <i class="text-muted">(<?php echo $set['ton'] ?> online)</i></button>
              </h5>
            </div>
            <div id="collapse<?php echo $auxF; ?>" class="collapse <?php if($open == $set['id'] || $search != "") { echo "show"; } ?>" aria-labelledby="heading<?php echo $auxF; ?>" data-parent="#accordionContatos">
              <?php foreach ($set['agts'] as $user) {
                $id = $user['id'];
                if(($eu*311) > ($id*311)){
                  $hash = ($id*311)."-".($eu*311);
                } else {
                  $hash = ($eu*311)."-".($id*311);
                }
                ?>

                <a href="chat?hash=<?php echo $hash ."-".($id*319)."-".$senha ?>&token=<?php echo $token ?>">
                  <div class="media campo-contato">
                    <div class="avatar-chat border-color-<?php echo $user['status'] ?> avatar-lista-contatos">
                      <img alt="nome" src="assets/avatar/<?php if($user['avatar'] == ''){ echo 'default.jpg'; } else { echo $user['avatar']; } ?>">
                    </div>
                    <div class="media-body contato-lista-chat">
                      <h4 class="media-heading">
                        <<?php if($user['tt'] > 0){ echo "b"; } else { echo "d"; } ?>>
                        <?php echo $user['nome']; ?>
                        </<?php if($user['tt'] > 0){ echo "b"; } else { echo "d"; } ?>>
                        <?php if($user['tt'] > 0){
                          ?>
                          <span class="label label-info bg-bell" style="color: white;"><b><?php echo $user['tt'] ?></b></span>
                          <?php
                        } ?>
                      </h4>
                      <p style="color: gray; font-size: 12px; margin-top: -10px;"><?php echo $user['tipo'] ?></p>
                      <p class="comment-date"><?php echo $user['status']; ?></p>
                    </div>
                  </div>
                </a>


                <?php
              } ?>



            </div>
          </div>
              <?php

              $auxF++;
            } ?>
          </div>


          <?php
        }


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
