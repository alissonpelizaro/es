<?php
include '../coreExt.php';

$collapsed = $_POST['showingCalls'];
$first = $_POST['first'];
$idUser = tratarString($_POST['id']);
$filter = tratarString($_POST['filter']);

if($filter != ""){
  $filter = " AND (`atendimento`.`remetente` LIKE '%$filter%' OR `atendimento`.`nome` LIKE '%$filter%')";
}

$sql = "SELECT
				 `atendimento`.`idAtendimento`,
				 `atendimento`.`dataInicio`,
				 `atendimento`.`remetente`,
				 `atendimento`.`plataforma`,
				 `atendimento`.`nome`,
				 `atendimento`.`notifRest`,
				 `atendimento`.`pendente`,
				 `atendimento`.`resposta`,
				 (SELECT
						MAX(`chat_atendimento`.`dataEnvio`)
  				FROM
						`chat_atendimento`
					WHERE
						`chat_atendimento`.`idAtendimento`=`atendimento`.`idAtendimento`)
  			FROM
					`atendimento`
				WHERE
					`atendimento`.`status` != '1'
				AND
					`atendimento`.`idAgente` = '$idUser'
				$filter";

$atendimentos = $db->query($sql);
$atendimentos = $atendimentos->fetchAll();

//Carrega agente
$sql = "SELECT
					`logged`
				FROM
					`user`
				WHERE
					`idUser` = '$idUser'";

$agente = $db->query($sql);
$agente = $agente->fetch();

foreach ($atendimentos as $at) {
  if($at['idAtendimento'] != ""){


    $ultima = setupUltimaResposta($at['resposta']);
    ?>

    <div id="atendimento-<?php echo $at['idAtendimento'];?>" class="<?php if($collapsed == 1){ echo "col-12"; } else { echo "col-xl-4"; }?> classAtendimento" onclick="clickAtendimento(<?php echo $at['idAtendimento'] ?>)">
      <div class="card<?php if($first == 1){ ?> enterness-fade<?php } ?>">
        <div class="blockAtendimento">
          <img src="assets/icons/social/<?php echo $at['plataforma']?>.png" alt="<?php echo $at['plataforma']?>">
          <h3 class="p-0 m-0"><?php
          if($at['nome'] != ""){
            echo $at['nome'];
          } else {
            echo $at['remetente'];
          }
          ?></h3>
          <p class="p-0 m-0"><i>Início: <?php echo dataBdParaHtml($at['dataInicio']) ?></i></p>
          <p class="p-0 m-0 m-l-55">
            <?php if($ultima){ ?>
              <i>Ultima resposta: </i>
              <span class="badge <?php if($agente['logged'] != 1) { echo "badge-secondary"; } else { echo $ultima['sit']; } ?> text-white"><?php echo $ultima['time'] ?></span>
            <?php } else { ?>
              <i>Aguardando cliente</i>
            <?php } ?>
          </p>
          <span class="iconsAtendimento">
            <i class="fa fa-exclamation-triangle text-<?php if($at['notifRest'] == '1') { echo "danger"; } else { echo "muted op-40"; }; ?>" aria-hidden="true"></i>
            <i class="fa fa-hourglass text-<?php if($at['pendente'] == '1') { echo "info"; } else { echo "muted op-40"; }; ?>" aria-hidden="true"></i>
          </span>
        </div>
      </div>
    </div>

    <?php
  }
}

function setupUltimaResposta($data){
  if($data == 'agente' || $data == ''){
    return false;
  }

  $hoje = date("Y-m-d H:i:s");
  $date_time  = new DateTime($data." America/Sao_Paulo");
  $diff       = $date_time->diff( new DateTime($hoje." America/Sao_Paulo"));
  $hr = ($diff->days * 24) + $diff->h;
  $min = (int) $diff->i;
  if(($hr*60)+$min < 10){
    $sit = 'bg-verde';
  } else if(($hr*60)+$min < 15){
    $sit = 'badge-warning';
  }else {
    $sit = 'badge-danger';
  }
  return array(
    'sit' => $sit,
    'time' => $hr."h".$min."min"
  );
  //return $diff->format('%y ano(s), %m mês(s), %d dia(s), %H hora(s), %i minuto(s) e %s segundo(s)');

}
