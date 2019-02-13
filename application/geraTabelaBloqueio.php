<?php
include '../core.php';

$inicio = tratarString($_POST['inicio']);
$saida = tratarString($_POST['saida']);
$int = tratarString($_POST['intervalo']);
$casa = tratarString($_POST['casa']);

/* $hrIni = explode(":", $inicio);
$mnIni = (int) $hrIni[1];
$hrIni = (int) $hrIni[0];
if($hrIni < 7){
  $hrIni = 7;
  $mnIni = 0;
}

$hrFim = explode(":", $saida);
$mnFim = (int) $hrFim[1];
$hrFim = (int) $hrFim[0];
if($hrFim > 18){
  $hrFim = 18;
  $mnFim = 0;
}
 */

$hrIni = 7;
$mnIni = 0;
$hrFim = 18;
$mnFim = 0;

//Carrega a quantidade de Técnicos
$sql = "SELECT `idUser`, `nome`, `sobrenome` FROM `user` WHERE `status` = 1 AND `ramal` = '$casa' AND `tipo` = 'tecnico'";
$tecnicos = $db->query($sql);
$tecnicos = $tecnicos->fetchAll();
?>
<h4 class="text-muted">Pré-adicionar bloqueios fixos nesse mês</h4>
<table class="table enterness-fade">
  <thead>
    <tr>
      <th style='border-top: 0px solid red;'>#</th>
      <th style='border-top: 0px solid red;'>Nome</th>
      <?php
      $loop = true;
      $tLoop = 0;
      $getHorarios = array();
      do {
        ?>
        <th style='border-top: 0px solid red; font-size: 12px;' class="text-center"><?php echo setNumLoop($hrIni)."h".setNumLoop($mnIni) ?></th>
        <?php
        $tLoop++;
        $getHorarios[$tLoop] = $hrIni.":".$mnIni;
        $mnIni = $mnIni + $int;
        if($mnIni >= 60){
          $mnIni = 0;
          $hrIni++;
        }
        if($int != 60){
          if($hrIni == $hrFim && $mnIni != 0){
            $loop = false;
          }
        } else {
          if($hrIni > $hrFim){
            $loop = false;
          }
        }

      } while ($loop);
      ?>
    </tr>
  </thead>
  <tbody>
    <?php
    $index = 1;
    foreach ($tecnicos as $tec) {
      ?>
      <tr>
        <td><?php echo $index; $index++; ?></td>
        <td><?php echo $tec['nome']." ".$tec['sobrenome'] ?></td>
        <?php
        for ($i=0; $i < $tLoop; $i++) {
          ?>
          <td class="text-center" style="border-left: 1px solid #eee;">
            <div class="botao-bloqueio-fixo" id="btnBlockFixed<?php echo $tec['idUser']. "-" . $getHorarios[$i+1] ?>" onclick="setFixedBlock(this, '<?php echo $tec['idUser']. "-" . $getHorarios[$i+1] ?>')">
            </div>
          </td>
          <?php
        }
         ?>
      </tr>
      <?php
    } ?>
  </tbody>
</table>
<hr style="width: 30%;">
<?php

function setNumLoop($num){
  if(!is_numeric($num)){
    return "0";
  }
  if((int) $num < 10){
    return "0".$num;
  }
  return $num;
}

?>
