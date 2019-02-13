<?php
include '../coreExt.php';

$mstChecked = $_POST["hash"] / 53;

//Carrega atendimento
$sql = "SELECT `remetente`, `nome`, `dataInicio` FROM `atendimento` WHERE `idAtendimento` = $mstChecked";
$temp = $db->query($sql);
$temp = $temp->fetchAll();
$temp = $temp[0];

if(isset($temp["nome"]) && $temp["nome"] != ""){
	$nome = $temp["nome"];
} else {
	$nome = $temp["remetente"];
}

//Nome que será impresso em cima da conversa
echo $nome."-*-";

//Cria o relatório desse atendimento
$sql = "SELECT * FROM `chat_atendimento` WHERE `idAtendimento` = '$mstChecked' ORDER BY `dataEnvio`";
$chat = $db->query($sql);
$chat = $chat->fetchAll();

$ultima = '';
$auxa = 0;
$auxc = 0;
$tmra = 0;
$tmrc = 0;

if(count($chat) > 0){
	foreach ($chat as $msg) {
		$dataEnvio = $msg['dataEnvio'];
		if($msg['rmt'] == 'cliente'){
			if($ultima == 'agente'){
				$auxc++;
				$ini = strtotime($tultima);
				$fim = strtotime($dataEnvio);
				$tmrc = $tmrc + ($fim - $ini);
			}
			$ultima = 'cliente';
			$tultima = $dataEnvio;
		} else {
			if($ultima == 'cliente'){
				$auxa++;
				$ini = strtotime($tultima);
				$fim = strtotime($dataEnvio);
				$tmra = $tmra + ($fim - $ini);
			}
			$ultima = 'agente';
			$tultima = $dataEnvio;
		}
	}
	$first = $chat[0]['dataEnvio'];
	$last = $dataEnvio;
} else {
	$first = false;
}

// Calculando o tempo médio de resposta
if($auxa > 0){
	$tmra = round($tmra/$auxa);
} else {
	$tmra = 0;
}

if($auxc > 0){
	$tmrc = round($tmrc/$auxc);
} else {
	$tmrc = 0;
}

$taIne = explode(" ", $temp["dataInicio"]);

if($taIne[0] == date("Y-m-d")){
	$taIne = horaParaSegundos($taIne[1]);
	$taFim = horaParaSegundos(date("H:i:s"));
	$ta = $taFim - $taIne;
} else {
	$diferenca = strtotime(date("Y-m-d")) - strtotime($taIne[0]);

	$tempDias = floor($diferenca / (60 * 60 * 24));

	if ($tempDias == 1) {
		$taIne = horaParaSegundos($taIne[1]);
		$fimDiaIne = horaParaSegundos("23:59:59");
		$fimDiaIne = $fimDiaIne - $taIne;

		$taFim = horaParaSegundos(date("H:i:s"));
		$ta = $fimDiaIne + $taFim;
	} else {
		$taIne = horaParaSegundos($taIne[1]);
		$fimDiaIne = horaParaSegundos("23:59:59");
		$fimDiaIne = $fimDiaIne - $taIne;

		$tempDias = ($tempDias-1)*24;
		$tempDias = horaParaSegundos($tempDias.":00:00");

		$taFim = horaParaSegundos(date("H:i:s"));
		$ta = $fimDiaIne + $tempDias + $taFim;
	}
}
?>

<div class="col-md-4">
	<div class="card p-10">
		<div class="media">
			<div class="media-left meida media-middle">
				<span class="btn btn-primary btn-sm "><b>TA</b></span>
			</div>
			<div class="media-body media-text-right">
				<h3 class="p-0 m-0">&nbsp;<?php echo segundosParaHora($ta); ?></h3>
			</div>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="card p-10">
		<div class="media">
			<div class="media-left meida media-middle">
				<span class="btn btn-warning btn-sm "><b>TMRC</b></span>
			</div>
			<div class="media-body media-text-right">
				<h3 class="p-0 m-0">&nbsp;<?php echo segundosParaHora($tmrc); ?></h3>
			</div>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="card p-10">
		<div class="media">
			<div class="media-left meida media-middle">
				<span class="btn btn-danger btn-sm"><b>TMRA</b></span>
			</div>
			<div class="media-body media-text-right">
				<h3 class="p-0 m-0">&nbsp;<?php echo segundosParaHora($tmra); ?></h3>
			</div>
		</div>
	</div>
</div>
