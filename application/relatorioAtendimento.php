<?php
include '../core.php';
include 'Relatorio.php';

$rel = new Relatorio;

if(isset($_POST['origin']) && $_POST['origin'] == 'request'){

	if(isset($_POST['filas'])){
		$filas = $_POST['filas'];
	} else {
		$filas = false;
	}

	if(isset($_POST['agentes'])){
		$agentes = $_POST['agentes'];
	} else {
		$agentes = false;
	}

	if(isset($_POST['plataforma'])){
		$plataforma = $_POST['plataforma'];
	} else {
		$plataforma = false;
	}

	if(isset($_POST['ordenar'])){
		$ordenar = $_POST['ordenar'];
	} else {
		$ordenar = false;
	}

	$dados = (object) array(
		'dataini' => tratarString($_POST['dataini']),
		'horaini' => tratarString($_POST['horaini']),
		'datafim' => tratarString($_POST['datafim']),
		'horafim' => tratarString($_POST['horafim']),
		'sentido' => tratarString($_POST['sentido']),
		'filas' => $filas,
		'agentes' => $agentes,
		'origemFiltro' => tratarString($_POST['origemFiltro']),
		'origem' => tratarString($_POST['origem']),
		'plataforma' => $plataforma,
		'protocoloFiltro' => tratarString($_POST['protocoloFiltro']),
		'protocolo' => tratarString($_POST['protocolo']),
		'classificacaoFiltro' => tratarString($_POST['classificacaoFiltro']),
		'classificacao' => tratarString($_POST['classificacao']),
		'ordenar' => $ordenar,
		'page' => tratarString($_POST['page'])
	);

	$arrayDados = $rel->getRelatorioAtendimento($dados);

	if($arrayDados->total == 0){
		?>
		<hr>
		<p class="text-center"><i>Nenhum dado para exibir</i></p>
		<?php
	} else {
		$ttPage = (int) ($arrayDados->total/15);
		if($arrayDados->total % 15 != 0){
			$ttPage++;
		}

		?>
		<hr class='w-75'>
<!-- 		<div class="col-12 text-right m-b-20">
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
					<th>Sentido</th>
					<th>Fila</th>
					<th>Agente</th>
					<th>Plataforma</th>
					<th>De</th>
					<th>Espera (TM)</th>
					<th>Duração</th>
					<th>Status</th>
					<th>Protocolo</th>
					<th>Classificação</th>
					<th>Histórico</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($arrayDados->dados as $linha):
					if($linha['status'] == 1){
						$feed = $rel->getFeed($linha['idAtendimento']);
					}
					?>
					<tr>
						<td><?php echo dataBdParaHtml($linha['dataInicio']) ?></td>
						<td><?php echo $linha['origem'] == 'externo' ? 'Entrante' : 'Sainte'; ?> </td>
						<td><?php echo $linha['fila']; ?> </td>
						<td><?php echo $rel->getNomeUser($linha['idAgente']); ?> </td>
						<td><?php echo $linha['plataforma']; ?> </td>
						<td><?php
						if($linha['plataforma'] == 'whatsapp'){
							if($linha['nome'] != ""){
								echo $linha['nome']." (".str_replace("@c.us", "", $linha['remetente']).")";
							} else {
								echo str_replace("@c.us", "", $linha['remetente']);
							}
						} else {
							echo $linha['nome'];
						}
						?> </td>
						<td><?php echo $linha['status'] == 1 ? segundosParaHora($feed['tmra']) : ''; ?></td>
						<td><?php echo $linha['status'] == 1 ? segundosParaHora($feed['ta']) : ''; ?></td>
						<td><?php echo $linha['status'] == 1 ? 'Finalizado' : 'Em atendimento'; ?></td>
						<td><?php echo $linha['protocolo'] ?></td>
						<td></td>
						<td>
							<a href="#!" class="table-rel-icon" onclick='window.open("popAtendimento?hash=<?php echo $linha['idAtendimento']*777; ?>", "Histórico do atendimento", "width=750,height=600");'>
								<i class="fa fa-eye p-l-20 p-r-20" aria-hidden="true"></i>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="col-12 m-t-5">
			<small>Página <b id="bapg" class="padrao"><?php echo $dados->page ?></b> de <b id="btpg" class="padrao"><?php echo $ttPage ?></b> | Total de <b id="btrg" class="padrao"><?php echo $arrayDados->total ?></b> registros.</small>
			<nav class="pull-right">
				<div class="btn-group">
					<button onclick="clickPagination('first')" class="btn btn-info btn-sm btn-outline<?php if($dados->page == 1){ echo ' disabled'; } ?>" <?php if($dados->page == 1){ echo ' disabled'; } ?>>Primeira</button>
					<button onclick="clickPagination('prev')" class="btn btn-info btn-sm btn-outline<?php if($dados->page == 1){ echo ' disabled'; } ?>" <?php if($dados->page == 1){ echo ' disabled'; } ?>>Anterior</button>
					<button onclick="clickPagination('next')" class="btn btn-info btn-sm btn-outline<?php if($dados->page == $ttPage){ echo ' disabled'; } ?>" <?php if($dados->page == $ttPage){ echo ' disabled'; } ?>>Próxima</button>
					<button onclick="clickPagination('last')" class="btn btn-info btn-sm btn-outline<?php if($dados->page == $ttPage){ echo ' disabled'; } ?>" <?php if($dados->page == $ttPage){ echo ' disabled'; } ?>>Última</button>
				</div>
			</nav>
		</div>
		<pre>
			<?php
		}
	}

	function getRelatorioAtendimento($dados){
		if($dados->dataini == ""){
			$dados->dataini = date("Y-m-d");
		}

		if($dados->horaini == ""){
			$dados->horaini = "00:00:00";
		} else {
			$dados->horaini .= ":59";
		}

		if($dados->datafim == ""){
			$dados->datafim = date("Y-m-d");
		}

		if($dados->horafim == ""){
			$dados->horafim = "23:59:59";
		} else {
			$dados->horafim .= ":59";
		}

		$sql = "SELECT * FROM `atendimento` WHERE
		`dataIni` >= '".$dados->dataini . " ". $dados->horaini."'
		AND `dataFim` <= '".$dados->datafim . " ". $dados->horafim."'";

		if($dados->sentido != ""){
			$sql .= " AND `origem` = '$dados->sentido'";
		}

		if($dados->filas){
			$qtdFila = count($dados->filas);
			$filaAt = 0;
			$sql .= " AND (";
				foreach ($dados->filas as $fila) {
				$sql .= "  `fila` = '".tratarString($fila)."'";
				$filaAt++;
				if($filaAt < $qtdFila){
				$sql .= " OR";
				}
				}
				$sql .= ")";
			}

			if($dados->agentes){
				$qtdAgente = count($dados->agentes);
				$agenteAt = 0;
				$sql .= " AND (";
					foreach ($dados->agentes as $agente) {
					$sql .= "  `idAgente` = ".tratarString($agente);
					$agenteAt++;
					if($agenteAt < $qtdAgente){
					$sql .= " OR";
					}
					}
					$sql .= ")";
				}

				if($dados->origem != ""){
					$sql .= " AND (`remetente` ". retFiltro($dados->origemFiltro, $dados->origem)
					. " OR `nome`". retFiltro($dados->origemFiltro, $dados->origem).")";
				}

				if($dados->plataforma){
					$qtdPlataforma = count($dados->plataforma);
					$plataformaAt = 0;
					$sql .= " AND (";
						foreach ($dados->plataforma as $plat) {
						$sql .= "  `plataforma` = ".tratarString($plat);
						$plataformaAt++;
						if($plataformaAt < $qtdPlataforma){
						$sql .= " OR";
						}
						}
						$sql .= ")";
					}

					if($dados->protocolo != ""){
						$sql .= " AND `protocolo` ". retFiltro($dados->origemFiltro, $dados->origem);
					}

					if($dados->ordenar){
						$qtdOrdenar = count($dados->ordenar);
						$ordenarAt = 0;
						$sql .= " ORDER BY";
						foreach ($dados->ordenar as $ord) {
							$sql .= "  `$ord`";
							$ordenarAt++;
							if($ordenarAt < $qtdOrdenar){
								$sql .= ", ";
							}
						}
					}

					if($dados->page > 0){
						$tt = 15 * $dados->page;
						$sql .= " LIMIT 14, $tt";
					}

				}

				function retFiltro($opt, $val){
					if($opt == 'igual'){
						return "= '$val'";
					} else if($opt == 'contem'){
						return "LIKE '%$val%'";
					} else if($opt == 'inicia'){
						return "LIKE '$val%'";
					} else if($opt == 'diferente'){
						return "!= '%$val%'";
					} else if($opt == 'ncontem'){
						return "NOT LIKE '%$val%'";
					} else if($opt == 'termina'){
						return "LIKE '%$val'";
					} else {
						return false;
					}
				}



				/*
				//Cria relação de Log SELECT * FROM `log`".$filtro." ORDER BY `dataLog`
				$sql = "SELECT
				`atendimento`.`dataInicio`, `atendimento`.`remetente`, `atendimento`.`plataforma`,
				`atendimento`.`fila`, `user`.`nome`, `user`.`sobrenome`, `atendimento`.`idAtendimento`
				FROM `atendimento`
				INNER JOIN `user` ON `atendimento`.`idAgente` = `user`.`idUser`
				WHERE `atendimento`.`status` = '1'
				ORDER BY `atendimento`.`dataInicio`";
				$relAgt = $db->query($sql);
				$relAgt = $relAgt->fetchAll();
				*/
				?>
