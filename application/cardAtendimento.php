<?php
include '../coreExt.php';

//Checa quantas filas estão disponivels para atendimento em Mídias
$sql = "SELECT `nomeFila` FROM `fila` WHERE `status` = '1' ORDER BY `priority`";
$filas = $db->query($sql);
$filas = $filas->fetchAll();
$arrayFilas = array();
$strFilas = "";
$aux = 0;

//Carrega a quantidade de agentes disponivel para atendimento
$sql = "SELECT `nome`, `sobrenome`, `filas` FROM `user`
WHERE (`tipo` = 'agente' OR `tipo` = 'supervisor')
AND `filas` != ''
AND `filas` != '-#-'
AND `status` = '1'
AND `logged` = '1'";
$users = $db->query($sql);
$users = $users->fetchAll();
$nomeUsers = "";
$qtdUsers = 0;
$qtdFilas = 0;
//Cria array dinâmico comparando usuarios ativos com as filas existentes
foreach ($filas as $fila) {
  foreach ($users as $user) {
    if(strpos($user['filas'], $fila['nomeFila']) !== false){
      $arrayFilas[$aux] = $fila['nomeFila'];
      $aux++;
      if(strpos($strFilas, $fila['nomeFila']) === false){
        $strFilas .= $fila['nomeFila'] . ", ";
        $qtdFilas++;
      }

      if(strpos($nomeUsers, $user['nome']." ".$user['sobrenome']) === false){
        $nomeUsers .= $user['nome']." ".$user['sobrenome'] . ", ";
        $qtdUsers++;
      }
    }
  }
}

if($strFilas != ""){
  $strFilas = substr($strFilas, 0, -2);
}

if($nomeUsers != ""){
  $nomeUsers = substr($nomeUsers, 0, -2);
}

//Carrega quantidade de atendimentos ativos
$sql = "SELECT count(`idAtendimento`) AS `tt` FROM `atendimento` WHERE `status` = '0'";
$ttAt = $db->query($sql);
$ttAt = $ttAt->fetch();

//Carrega quantidade de atendimentos finalizados no dia
$date = date("Y-m-d")." 00:00:00";
$sql = "SELECT `idAtendimento` FROM `atendimento` WHERE `dataFim` >= '$date' AND `status` = '1'";
$ccAt = $db->query($sql);
$ccAt = $ccAt->fetchAll();

//Calcula TMA e TMR do dia
$tma = 0;
$tmra = 0;
$qtd = 0;

foreach ($ccAt as $at) {
  $id = $at['idAtendimento'];
  $sql = "SELECT `ta`, `tmra` FROM `feed_atendimento` WHERE `idAtendimento` = '$id'";
  $dados = $db->query($sql);
  $dados = $dados->fetch();
  if(isset($dados['ta'])){
    $tma = $tma + $dados['ta'];
    $tmra = $tmra + $dados['tmra'];
    $qtd++;
  }
}

if($qtd > 0){
  $tma = segundosParaHora((int) $tma/$qtd);
  $tmra = segundosParaHora((int) $tmra/$qtd);
} else {
  $tma = "00:00:00";
  $tmra = "00:00:00";
}

?>

<div class="">
  <h4 class="card-title m-0">Atendimentos</h4>
  <div class="row">
    <div class="col-xl-4">
      <div class="bloco-midias-inicio highlight p-0 p-b-20 rounded">
        <div class="rounded bg-padrao p-t-35">
        </div>
        <button class="span-midias-index m-l-10 text-info btn" 
        				tabindex="0" 
        				data-placement="bottom" 
        				role="button" 
        				data-toggle="popover" 
        				data-trigger="focus" 
        				title="Filas disponíveis:" 
        				data-content="<?php echo $strFilas ?>">
        	<i><?php echo $qtdFilas ?></i>
        </button>
        <i class="h4 m-l-10 text-info" style="">Filas</i>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="bloco-midias-inicio highlight p-0 p-b-20 rounded" 
      		 onclick="blocoAtendimentoCardClick(); attListaAtendimentos();" 
      		 data-toggle="modal" 
      		 data-target="#modalAtendimentos">
        <div class="rounded bg-success p-t-35">
        </div>
        <div class="span-midias-index m-l-10 text-success"><i><?php echo $ttAt['tt'] ?></i></div>
        <i class="h4 m-l-10 text-success">Atend.</i>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="bloco-midias-inicio highlight p-0 p-b-20 rounded">
        <div class="rounded bg-warning p-t-35">
        </div>
        <button class="span-midias-index m-l-10 text-warning btn" 
        				tabindex="0" 
        				data-placement="bottom" 
        				role="button" 
        				data-toggle="popover" 
        				data-trigger="focus" 
        				title="Agentes logados:" 
        				data-content="<?php echo $nomeUsers ?>">
        	<i><?php echo $qtdUsers ?></i>
        </button>
        <i class="h4 m-l-10 text-warning">Agentes</i>
      </div>
    </div>
  </div>
  <hr class="m-t-3 m-b-13" style="width: 40%;">
  <div class="row">
    <div class="col-4">
      <div class="text-center">
        <p>Atendidos: <span class="badge badge-secondary"><?php echo count($ccAt) ?></span></p>
      </div>
    </div>
    <div class="col-4">
      <div class="text-center">
        <p>TMA: <span class="badge badge-white bg-info"><?php echo $tma ?></span></p>
      </div>
    </div>
    <div class="col-4">
      <div class="text-center">
        <p>TMR: <span class="badge badge-danger"><?php echo $tmra ?></span></p>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(function () {
  $('[data-toggle="popover"]').popover();
});

var valFilter = "";

$("#filterModalAtendimentos").keyup(function(){
  valFilter = $(this).val();
  attListaAtendimentos(true);
});

function attListaAtendimentos(filterAsk = false){
  $.ajax({
    type: "POST",
    data: {
      filter : valFilter
    },
    url: "../application/cardAtendimentoModalLista",
    success: function(result){
      $("#modalAtendimentosBody").html(result);
      if(!filterAsk){
        setTimeout(attListaAtendimentos, 6000);
      }
    }
  });
}
</script>
