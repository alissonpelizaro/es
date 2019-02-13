<?php
include '../coreExt.php';

if (isset($_POST['grafico'])) {
	switch ($_POST['grafico']) {
		case 'atendimento':
			echo json_encode (getArrayDesempenho($db));
			break;

		case '':
			;
			break;
		
		case '':
			;
			break;
		
		case '':
			;
			break;
	};
}

function getArrayDesempenho($db) {
	$data = date("Y-m-d")." ".(date("H")-1).":00:00";
	$sql = "SELECT `plataforma`, `dataFim` FROM `atendimento` WHERE `dataFim` >= '$data' AND `status` = 1";
	$dados = $db->query($sql);
	$dados = $dados->fetchAll();
	
	$ats = array(
			'whatsapp' => 0,
			'telegram' => 0,
			'enterness' => 0,
			'skype' => 0,
			'messenger' => 0);
	
	foreach ($dados as $dado) {
		$ats[$dado['plataforma']]++;
	}
	
	return $ats;
}

  
