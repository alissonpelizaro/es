<?php

/**
 * Classe de relatórios
 */

class Relatorio extends Util{

	private $db;
	private $config;

	function __construct(){
		$this->config = new Ambiente;
		$this->db = $this->connection();
	}

	public function getFeed($idAtendimento){
		$sql = "SELECT * FROM `feed_atendimento` WHERE `idAtendimento` = '$idAtendimento'";
		$feed = $this->db->query($sql);
		return $feed->fetch();
	}

	public function getNomeUser($id){
		$sql = "SELECT `nome`, `sobrenome` FROM `user` WHERE `idUser` = '$id'";
		$user = $this->db->query($sql);
		$user = $user->fetch();
		if(!isset($user['nome'])){
			return false;
		}
		return $user['nome'] . " " . $user['sobrenome'];
	}

	public function getRelatorioAtendimento($dados){
		if($dados->dataini == ""){
			$dados->dataini = date("Y-m-d");
		} else {
			$dados->dataini = $this->dataHtmlParaBd($dados->dataini);
		}

		if($dados->horaini == ""){
			$dados->horaini = "00:00:00";
		} else {
			$dados->horaini .= ":00";
		}

		if($dados->datafim == ""){
			$dados->datafim = date("Y-m-d");
		} else {
			$dados->datafim = $this->dataHtmlParaBd($dados->datafim);
		}

		if($dados->horafim == ""){
			$dados->horafim = "23:59:59";
		} else {
			$dados->horafim .= ":59";
		}

		$sql = "SELECT * FROM `atendimento` WHERE
		`dataInicio` >= '".$dados->dataini . " ". $dados->horaini."'
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
			$sql .= " AND (`remetente` ". $this->retFiltro($dados->origemFiltro, $dados->origem)
			. " OR `nome`". $this->retFiltro($dados->origemFiltro, $dados->origem).")";
		}

		if($dados->plataforma){
			$qtdPlataforma = count($dados->plataforma);
			$plataformaAt = 0;
			$sql .= " AND (";
			foreach ($dados->plataforma as $plat) {
				$sql .= "  `plataforma` = '".tratarString($plat)."'";
				$plataformaAt++;
				if($plataformaAt < $qtdPlataforma){
					$sql .= " OR";
				}
			}
			$sql .= ")";
		}

		if($dados->protocolo != ""){
			$sql .= " AND `protocolo` ". $this->retFiltro($dados->protocoloFiltro, $dados->protocolo);
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



		$ttQuery = str_replace('SELECT * ', 'SELECT count(`idAtendimento`) AS `tt` ', $sql);

		if($dados->page == 0){
			$tt = 0;
		} else {
			$tt = (15 * $dados->page)-15;
		}
		$sql .= " LIMIT $tt, 15";

		$ttQuery = $this->db->query($ttQuery);
		$ttQuery = $ttQuery->fetch();
		$ttQuery = $ttQuery['tt'];

		$return = $this->db->query($sql);

		return (object) array(
			'total' => $ttQuery,
			'dados' => $return->fetchAll()
		);

	}

	public function getRelatorioEvento($dados){

		//Filtro data e tipo do usuário
		$where = "WHERE
							`log`.`dataLog` >= '".$dados["dtIni"]." ".$dados["horaIni"]."'
						AND
							`log`.`dataLog` <= '".$dados["dtFim"]." ".$dados["horaFim"]."'
						AND
							`user`.`tipo` = 'agente'";

		//Filtro fila
		if($dados["filas"]){
			$trava = true;

			foreach ($dados["filas"] as $fila) {
				if($trava){
					$where .= " AND
											(`atendimento`.`fila` = '$fila'";
					$trava = false;
				}else{
					$where .= " OR
											`atendimento`.`fila` = '$fila'";
				}
			}
			$where .= ")";
		}

		//Filtro agente
		if($dados["agentes"]){
			$trava = true;

			foreach ($dados["agentes"] as $agente) {
				if($trava){
					$where .= " AND
											(`log`.`idUsuario` = '$agente'";
					$trava = false;
				}else{
					$where .= " OR
											`log`.`idUsuario` = '$agente'";
				}
			}
			$where .= ")";
		}

		//Filtro evento
		if (!$dados["eventos"]) {
			$dados["eventos"] = $this->getEventos();
		}

		$trava = true;
		foreach ($dados["eventos"] as $evento) {
			if(is_array($evento)){
				$evento = $evento['acao'];
			}

			if($trava){
				$where .= " AND
										(`log`.`acao` = '$evento'";
				$trava = false;
			}else{
				$where .= " OR
										`log`.`acao` = '$evento'";
			}
		}
		$where .= ")";

		//Filtro ordenar
		if ($dados["ordenar"]) {
			$trava = true;
			foreach ($dados["ordenar"] as $elemento) {
				if ($trava) {
					$where .= " ORDER BY ".
							$this->nomeBdOrdenar($elemento)." ASC";
							$trava = false;
				}else{
					$where .= ", ".$this->nomeBdOrdenar($elemento)." ASC";
				}
			}
			;
		}else{
			$where .= " ORDER BY `log`.`dataLog` DESC";
		}

		$sqlTT = "SELECT
						COUNT(`log`.`idLog`) AS `tt`
					FROM
						`log`
					INNER JOIN
						`user`
					ON
						`log`.`idUsuario` = `user`.`idUser`
					LEFT JOIN
						`atendimento`
					ON
						`log`.`idAtendimento` = `atendimento`.`idAtendimento`
					LEFT JOIN
						`feed_atendimento`
					ON
						`log`.`idAtendimento` = `feed_atendimento`.`idAtendimento`
					$where";

		$sql = str_replace(
				'COUNT(`log`.`idLog`) AS `tt`',
				'`log`.`dataLog`, `log`.`acao`, `log`.`obs`, `user`.`nome`,
				 `user`.`sobrenome`, `atendimento`.`fila`, `atendimento`.`remetente`,
				 `atendimento`.`status`, `atendimento`.`plataforma`, `feed_atendimento`.`ta`,
				 `feed_atendimento`.`tmra`',
				$sqlTT
				);
		
		if($dados["page"] == 0){
			$tt = 0;
		} else {
			$tt = (15 * $dados["page"])-15;
		}
		$sql .= " LIMIT $tt, 15";

		$totalDados = $this->db->query($sqlTT);
		$totalDados = $totalDados->fetch();
		$totalDados = $totalDados['tt'];

		$dadosTabela = $this->db->query($sql);
		$dadosTabela = $dadosTabela->fetchAll();

		return array(
				'totalDados' => $totalDados,
				'dadosTabela' => $dadosTabela
		);
	}

  public function getFilas(){
  	$sql = "SELECT
							`idFila`, `nomeFila`
						FROM
							`fila`";

  	$filas = $this->db->query($sql);
  	$filas = $filas->fetchAll();
  	return $filas;
  }

  public function getAgentes() {
		$sql = "SELECT
							`idUser`, `nome`, `sobrenome`
						FROM
							`user`
						WHERE
							`tipo` = 'agente'";

		$agentes = $this->db->query($sql);
		$agentes = $agentes->fetchAll();

		return $agentes;
	}

	/**
	 *
	 * @return unknown
	 */
	public function getEventos() {

		$eventos = array(
				array(
						'acao' => 'Logou no sistema',
						'evento' => 'Login'
				),
				array(
						'acao' => 'Saiu do sistema',
						'evento' => 'Logoff'
				),
				array(
						'acao' => 'Transferiu atendimento',
						'evento' => 'Transferiu atendimento'
				),
				array(
						'acao' => 'Recebeu transferencia',
						'evento' => 'Recebeu transferencia'
				),
				array(
						'acao' => 'Entrou em pausa',
						'evento' => 'Entrou em pausa'
				),
				array(
						'acao' => 'Saiu da pausa',
						'evento' => 'Saiu da pausa'
				),
				array(
						'acao' => 'Estacionou atendimento',
						'evento' => 'Estacionou atendimento'
				),
				array(
						'acao' => 'Tirou do estacionamento',
						'evento' => 'Tirou do estacionamento'
				),
				array(
						'acao' => 'Finalizado atendimento',
						'evento' => 'Finalizou atendimento'
				),
				array(
						'acao' => 'Recebeu atendimento',
						'evento' => 'Recebeu atendimento'
				),
				array(
						'acao' => 'Iniciou atendimento',
						'evento' => 'Iniciou atendimento'
				),
				array(
						'acao' => 'Saiu da fila',
						'evento' => 'Saiu da fila'
				),
				array(
						'acao' => 'Entrou na fila',
						'evento' => 'Entrou na fila'
				),
				array(
						'acao' => 'Transbordo de fila',
						'evento' => 'Transbordo de fila'
				),
				array(
						'acao' => 'Transbordou atendimento',
						'evento' => 'Transbordou atendimento'
				),
				array(
						'acao' => 'Recebeu transbordo',
						'evento' => 'Recebeu transbordo'
				)
		);

		return $eventos;
	}

	private function retFiltro($opt, $val){

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

	private function nomeBdOrdenar($selecao) {
		switch ($selecao) {
			case 'data':
				return "`log`.`dataLog`";

			case 'fila':
				return "`atendimento`.`fila`";

			case 'agente':
				return "`user`.`nome`";

			case 'evento':
				return "`log`.`acao`";

			case 'espera':
				return "`feed_atendimento`.`tmra`";

			case 'duracao':
				return "`feed_atendimento`.`ta`";

			case 'quemDesligou':
				return "`atendimento`.`status`";

			case 'numero-motivo':
				return "`atendimento`.`remetente`";
		};
	}

	//Prepara conexão com o BD
	private function connection(){
		$control = new PDO("mysql:host=".$this->config->dbHost().";dbname=".$this->config->dbDatabase(), $this->config->dbUser(), $this->config->dbPass());
		return $control;
	}

}
 ?>
