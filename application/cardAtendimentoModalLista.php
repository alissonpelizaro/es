<?php
include '../coreExt.php';
$search = tratarString($_POST['filter']);
$token = geraSenha(15);
$ttAts = 0;

//Carrega atendimentos abertos
function retAtendimentoPorFila($db, $fila){
  $sql = "SELECT `idAtendimento`, `idAgente`, `dataInicio`, `remetente`,
  `plataforma`, `nome`, `notifRest` FROM `atendimento` WHERE
  `status` = 0 AND `fila` = '$fila' ORDER BY `notifRest` DESC";
  $atendimentos = $db->query($sql);
  return $atendimentos->fetchAll();
}

function calcTempoAtendimento($horaini){
  $horaini = strtotime($horaini);
  $agora = strtotime(date("Y-m-d H:i:s"));
  $ret = explode(":", segundosParaHora($agora - $horaini));
  return $ret[0]."h".$ret[1]."min";
}

//Carrega filas cadastradas
$sql = "SELECT `nomeFila` FROM `fila` WHERE `status` = 1 ORDER BY `priority`";
$filas = $db->query($sql);
$filas = $filas->fetchAll();

//Carrega atendentes
$sql = "SELECT `idUser`, `nome`, `sobrenome` FROM `user`
WHERE `filas` != '-#-' AND `status` = 1 ";
//Checa se há filtro
if($search != ""){

  if(strpos($search, " ") !== false){
    $sql .= "AND (";
      $search = explode(" ", $search);
      $ttS = count($search);
      foreach ($search as $k) {
      $sql .= "`nome` LIKE '%$k%' OR `sobrenome` LIKE '%$k%'";
      $ttS--;
      if($ttS > 0){
      $sql .= " OR ";
      }
      }
      $sql .= ")";
    } else {
      $sql .= "AND (`nome` LIKE '%$search%' OR `sobrenome` LIKE '%$search%')";
    }
  }
  $users = $db->query($sql);
  $users = $users->fetchAll();

  //Cria Array dos usuario colocando ID como indice
  $nUsers = array();
  foreach ($users as $usr) {
    $nUsers[$usr['idUser']] = $usr['nome']." ".$usr['sobrenome'];
  }

  //inicia LOOP de todas as filas
  foreach ($filas as $fl) {
    $ats = retAtendimentoPorFila($db, $fl['nomeFila']);
    if(count($ats) > 0){
      ?>
      <h4 class="text-info"><i class="fa fa-bars" aria-hidden="true"></i> <i><?php echo $fl['nomeFila'] ?></i></h4>
      <?php
      if(count($ats) > 0){
        foreach ($ats as $at) {
          if(isset($nUsers[$at['idAgente']])) {
            $ttAts++;
            ?>
            <div class="row">
              <div class="col-12">
                <div class="highlight p-10 rounded blocoAtendimentoModal <?php if($at['notifRest'] == "1"){ echo "blocoAtendimentoModalRestrict"; } ?>">
                  <p class="p-0 m-0"><i>Agente:</i> <?php echo $nUsers[$at['idAgente']]; ?></p>
                  <p class="p-0 m-0"><i>Cliente:</i> <?php if($at['nome'] != ""){ echo $at['nome']; } else { echo $at['remetente']; }  ?></p>
                  <p class="p-0 m-0"><i>Tempo de atendimento:</i> <b><?php echo calcTempoAtendimento($at['dataInicio']) ?></b>
                    <a href="remoteChat?hash=<?php echo $at['idAtendimento']*253 ?>&token=<?php echo $token ?>">
                      <button type="button" class="btn btn-sm btn-info btn-outline pull-right">Assistir</button>
                    </a>
                  </p>
                  <?php if($at['notifRest'] == "1"){ ?>
                    <i class="text-danger fa fa-exclamation-triangle iconREstrictModalAtendimentos" aria-hidden="true"></i>
                  <?php } ?>
                  <span class="iconPlataformaModalAtendimentos"><img src="../my/assets/icons/social/<?php echo $at['plataforma'] ?>.png" alt="<?php echo $at['plataforma'] ?>"></span>
                </div>
              </div>
            </div>
            <?php
          }
        }
      } else {
        ?>
        <i>Nenhum atendimento ativo nessa fila</i>
        <?php
      }
      ?>
      <hr class="p-0 w-30">
      <?php
    }
  }

  if ($ttAts == 0) {
    ?>
    <center>
      <?php if($search == ""){ ?>
        <h4><i>Nenhum atendimento ativo</i></h4>
      <?php } else {
        ?>
        <h4><i>Nenhum atendimento encontrado</h4>
          <?php
        } ?>
      </center>
      <?php
    }
    ?>
