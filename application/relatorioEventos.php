<?php
// teste
include '../core.php';
include '../application/Relatorio.php';

$relatorio = new Relatorio();

if (isset($_POST["origem"]) && $_POST["origem"] == "requisicao") {
	
	$page = tratarString($_POST["page"]);
	
	$dtIni = tratarString($_POST["dtIni"]);
	if ($dtIni == "") {
		$dtIni = date("d/m/Y");
	}

	$horaIni = tratarString($_POST["horaIni"]);
	if ($horaIni == "") {
		$horaIni = "00:00:00";
	}else {
		$horaIni .= ":00";
	}
	
	$dtFim = tratarString($_POST["dtFim"]);
	if ($dtFim == "") {
		$dtFim = date("d/m/Y");
	}

	$horaFim = tratarString($_POST["horaFim"]);
	if ($horaFim == "") {
		$horaFim = "23:59:59";
	}else {
		$horaFim .= ":59";
	}

	if (isset($_POST["filas"])) {
		$filas = tratarString($_POST["filas"]);
	}else{
		$filas = FALSE;
	}

	if (isset($_POST["agentes"])) {
		$agentes = tratarString($_POST["agentes"]);
	}else{
		$agentes = FALSE;
	}

	if (isset($_POST["eventos"])) {
		$eventos = tratarString($_POST["eventos"]);
	}else{
		$eventos = FALSE;
	}

	if (isset($_POST["ordenar"])) {
		$ordenar = tratarString($_POST["ordenar"]);
	}else{
		$ordenar = FALSE;
	}
	
	$dados = array(
			"page" => $page,
			"dtIni" => $util->dataHtmlParaBd($dtIni),
			"horaIni" => $horaIni,
			"dtFim" => $util->dataHtmlParaBd($dtFim),
			"horaFim" => $horaFim,
			"filas" => $filas,
			"agentes" => $agentes,
			"eventos" => $eventos,
			"ordenar" => $ordenar
	);
	
	$dadosTabela = $relatorio->getRelatorioEvento($dados);
	$ttPage = (int) ($dadosTabela["totalDados"]/15) + 1;

	?>
	
	<hr class='w-75'>
	<?php if(!isset($dadosTabela["dadosTabela"][0])){?>
	<h3 class="text-muted center"><i>Nenhum evento foi encontrado.</i></h3>
	<?php }else{?>
<!-- 	<div class="col-12 text-right m-b-20">
		<nav>
			<button type="button" class="btn btn-sm btn-info">Excel </button>
			<button type="button" class="btn btn-sm btn-info">CSV</button>
			<button type="button" class="btn btn-sm btn-info">PDF</button>
		</nav>
	</div> -->
	<table class="table-rel table-hover table-striped" cellspacing="0">
		<thead>
			<tr>
				<th>Data</th>
				<th>Fila</th>
				<th>Agente</th>
				<th>Evento</th>
				<th>Espera</th>
				<th>Duração</th>
				<th>Quem desligou?</th>
				<th>Número/Motivo</th>
				<th>Observação</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ($dadosTabela["dadosTabela"] as $linha){

				if($linha['acao'] == "Transferiu atendimento" || $linha['acao'] == "Recebeu transferencia" ||
					 $linha['acao'] == "Finalizado atendimento" || $linha['acao'] == "Estacionou atendimento" ||
					 $linha['acao'] == "Tirou do estacionamento" || $linha['acao'] == "Iniciou atendimento" ||
					 $linha['acao'] == "Recebeu atendimento"){
					$linha['status'] = $linha['status'] ? "Finalizado" : "Em atendimento";
				}
			
				if ($linha['acao'] == "Logou no sistema" || $linha['acao'] == "Saiu do sistema" ||
						$linha['acao'] == "Estacionou atendimento" || $linha['acao'] == "Tirou do estacionamento" ||
						$linha['acao'] == "Entrou em pausa" || $linha['acao'] == "Saiu da pausa" ){
					if ($linha['obs'] != "") {
						$linha['ta'] = $linha['obs'];
					}
				}
				
				if ($linha['acao'] != "Transferiu atendimento" && $linha['acao'] != "Recebeu transferencia" &&
						$linha['acao'] != "Entrou na fila" && $linha['acao'] != "Saiu da fila") {
					$linha['obs'] = "";
				}
				
				if($linha['acao'] == "Transferiu atendimento"){
					$fulano = str_replace("Para: ", "", $linha['obs']);
					$linha['obs'] = "Transferido para $fulano";
				}
				
				if($linha['acao'] == "Recebeu transferencia"){
					$fulano = str_replace("De: ", "", $linha['obs']);
					$linha['obs'] = "Transferido de $fulano";
				}
				
				if($linha['acao'] == "Entrou na fila"){
					$linha['obs'] = "Entrou na fila ".$linha['obs'];
				}
				
				if($linha['acao'] == "Saiu da fila"){
					$linha['obs'] = "Saiu da fila ".$linha['obs'];
				}
				
			?>
				<tr>
					<td><?php echo dataBdParaHtml($linha['dataLog']) ?></td>
					<td><?php echo $linha['fila'] ?></td>
					<td><?php echo $linha['nome']." ".$linha["sobrenome"] ?></td>
					<td><?php echo $linha['acao'] ?></td>
					<td><?php echo segundosParaHora($linha['tmra']); ?></td>
					<td><?php echo segundosParaHora($linha['ta']); ?></td>
					<td><?php echo $linha['status'] ?></td>
					<td><?php echo $linha['remetente'] ?></td>
					<td><?php echo $linha['obs'] ?></td>
				</tr>
			<?php }?>
		</tbody>
	</table>
	<div class="col-12 m-t-5">
		<small>Página <b id="bapg" class="padrao"><?php echo $dados["page"] ?></b> de <b id="btpg" class="padrao"><?php echo $ttPage ?></b> | Total de <b id="btrg" class="padrao"><?php echo $dadosTabela["totalDados"] ?></b> registros.</small>
		<nav class="pull-right">
			<div class="btn-group">
				<button onclick="clickPagination('first')" class="btn btn-info btn-sm btn-outline<?php if($dados["page"] == 1){ echo ' disabled'; } ?>" <?php if($dados["page"] == 1){ echo ' disabled'; } ?>>Primeira</button>
				<button onclick="clickPagination('prev')" class="btn btn-info btn-sm btn-outline<?php if($dados["page"] == 1){ echo ' disabled'; } ?>" <?php if($dados["page"] == 1){ echo ' disabled'; } ?>>Anterior</button>
				<button onclick="clickPagination('next')" class="btn btn-info btn-sm btn-outline<?php if($dados["page"] == $ttPage){ echo ' disabled'; } ?>" <?php if($dados["page"] == $ttPage){ echo ' disabled'; } ?>>Próxima</button>
				<button onclick="clickPagination('last')" class="btn btn-info btn-sm btn-outline<?php if($dados["page"] == $ttPage){ echo ' disabled'; } ?>" <?php if($dados["page"] == $ttPage){ echo ' disabled'; } ?>>Última</button>
			</div>
		</nav>
	</div>
	<?php }
	
} else if (isset($_POST["origem"]) && $_POST["origem"] == "requisicaoAgentes") {
	if (isset($_POST["filas"])) {
		$filas = tratarString($_POST["filas"]);
		if(count($filas) > 0){
			$filtro = "";
			foreach($filas as $fila) {
				if($filtro == ""){
					$filtro = "AND (`filas` LIKE '%$fila%'";
				}else{
					$filtro .= " OR `filas` LIKE '%$fila%'";
				}
			}
			
			if ($filtro != "") {
				$filtro .= ")";
			}
			
			
			$sql = "SELECT
		    				`idUser`, `nome`, `sobrenome`
		    			FROM
		    				`user`
		    			WHERE
		    				`tipo` = 'agente'
		          AND
		          	`setor` = '".$_SESSION['setor']."'
							$filtro";

	 		$agentes = $db->query($sql);
			$agentes = $agentes->fetchAll();
			$reultado = "";
			
			foreach ($agentes as $agente) {
				$reultado .= '<option value="'.$agente["idUser"].'">'.$agente["nome"]." ".$agente["sobrenome"].'</option>';
			}
			
			if($reultado == ""){
				echo 'false';
			}else{
				echo $reultado;
			}
			 
		}else{
			echo 'false';
		}
	}else{
		
		$sql = "SELECT
	    				`idUser`, `nome`, `sobrenome`
	    			FROM
	    				`user`
	    			WHERE
	    				`tipo` = 'agente'
	          AND
	          	`setor` = '".$_SESSION['setor']."'";
						
		$agentes = $db->query($sql);
		$agentes = $agentes->fetchAll();
		$reultado = "";
		
		foreach ($agentes as $agente) {
			$reultado .= '<option value="'.$agente["idUser"].'">'.$agente["nome"]." ".$agente["sobrenome"].'</option>';
		}
		
		if($reultado == ""){
			echo 'false';
		}else{
			echo $reultado;
		}
	}
}


?>