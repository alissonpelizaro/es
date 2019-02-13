<?php
include '../core.php';

/* PREPARA DADOS DO MURAL */
$token = geraSenha(15);
$sql = "SELECT * FROM `mural`
WHERE `status` = '1' AND `setor` = '$setorUser' ORDER BY `criticidade`, `data` ASC";
$msgs = $db->query($sql);
$msgs = $msgs->fetchAll();
$casas = false;

if(count($msgs) == 0){
  $msgs = false;
}

/* Prepara dados das concessionárias se for AGENTE */
if($_SESSION['tipo'] != 'tecnico' && $_SESSION['tipo'] != 'gestor'){
  $sql = "SELECT `logo` FROM `casa` WHERE `status` = '1' GROUP BY `logo`";
  $logos = $db->query($sql);
  $logos = $logos->fetchAll();
  if(count($logos) == 0){
    $logos = false;
  }
}

//Carrega favoritos
$sql = "SELECT * FROM `favorito` WHERE `idUser` = '$idUser'";
$favoritos = $db->query($sql);
$favoritos = $favoritos->fetchAll();
if(count($favoritos) == 0){
  if($_SESSION['tipo'] == 'agente'){
    $sql = "INSERT INTO
  						`favorito`
  							(`idUser`, `lembrete`, `atendimento`, `clientes`, `meusAtendimentos`, `graficoAtendimentos`)
  					VALUES
  						('$idUser', 1, 1, 1, 1, 0)";
  } else if($_SESSION['tipo'] == 'agente'){

  } else {
    $sql = "INSERT INTO
  						`favorito`
  							(`idUser`, `lembrete`, `atendimento`, `clientes`, `meusAtendimentos`, `graficoAtendimentos`)
  					VALUES
  						('$idUser', 1, 1, 1, 1, 1)";
  }

  $db->query($sql);

  $sql = "SELECT * FROM `favorito` WHERE `idUser` = '$idUser'";
  $favoritos = $db->query($sql);
  $favoritos = $favoritos->fetchAll();
}

$favoritos = $favoritos[0];

$sql = "SELECT `idUser`, `nome`, `sobrenome` FROM `user` WHERE `status` = '1'";
$nomes = $db->query($sql);
$nomes = $nomes->fetchAll();

$sql = "SELECT `idGrupo`, `nome` FROM `grupo` WHERE `status` = '1'";
$grupos = $db->query($sql);
$grupos = $grupos->fetchAll();

$cardAtendimento = false;

$dadosGrafico = json_encode (getArrayDesempenho($db));

function getArrayDesempenho($db) {
	$dataIni = date("Y-m-d")." ".(date("H")-1).":59:59";
	$dataFim = date("Y-m-d")." 00:00:00";

	$sql = "SELECT
						`plataforma`, `dataFim`
					FROM
						`atendimento`
					WHERE
						`dataFim` <= '$dataIni'
			 		AND
						`dataFim` >= '$dataFim'
					AND
						`status` = 1";

	$dados = $db->query($sql);
	$dados = $dados->fetchAll();

	return  montaArrayGrafico($dados);
}

function montaArrayGrafico($dados) {
	$ats = array();
	for ($i = 0; $i < date('H'); $i++) {
		$ats[$i] = array(
				'whatsapp' => 0,
				'telegram' => 0,
				'enterness' => 0,
				'skype' => 0,
				'messenger' => 0
		);
	}


	foreach ($dados as $dado) {
		$indHora = (int) retIndiceDaHora($dado['dataFim']);
		$ats[$indHora][$dado['plataforma']]++;
	}
	return $ats;
}

function retIndiceDaHora($dataFim) {
	$hora = explode(" ", $dataFim);
	$hora = explode(":", $hora[1]);
	$hora = (int) $hora[0];
	$minutos = (int) $hora[1];
	if ($minutos > 0) {
		$hora++;
	}
	return $hora;
}

function retUserMural($nomes, $id){
  $retorno = "Ninguém";
  foreach ($nomes as $usr) {
    if($usr['idUser'] == $id){
      $retorno = $usr['nome'] . " " . $usr['sobrenome'];
    }
  }
  return $retorno;
}

function retGruposMural($grupos, $array){
  $retorno = "";
  $array = explode('-', $array);
  foreach ($array as $k) {
    foreach ($grupos as $g) {
      if($g['idGrupo'] == $k){
        $retorno .= $g['nome'] . ", ";
      }
    }
  }

  if($retorno == ""){
    return "Nenhum";
  } else {
    $retorno = substr($retorno, 0, -2);
    return $retorno.".";
  }
}

function retExpMural($data){
  if($data == '1000-01-01 00:00:00'){
    return "Nunca";
  } else {
    $data = explode(' ', $data);
    if(isset($data[1])){
      $data = explode('-', $data[0]);
      return $data[2] . "/" . $data[1] . "/" . $data[0];
    } else {
      return "Nunca";
    }
  }
}

function retCorMuralBox($crit){
  if($crit == '3'){
    return '#58FAD0';
  } else if($crit == '1'){
    return 'red';
  } else {
    return '#045FB4';
  }
}

function retCorMuralTexto($crit){
  if($crit == '3'){
    return 'success';
  } else if($crit == '1'){
    return 'danger';
  } else {
    return 'info';
  }
}

function retCriticidade($k){
  if($k == 1){
    return "alta";
  } else if($k == 2){
    return "normal";
  } else if($k == 3){
    return "baixa";
  } else {
    return false;
  }
}

/* PREPARA DADOS DOS POST-IT */
$id = $_SESSION['id'];
$sql = "SELECT * FROM `lembrete` WHERE `idUser` = '$id' AND `status` = '1'";
$lembretes = $db->query($sql);
$lembretes = $lembretes->fetchAll();

if(count($lembretes) == 0){
  $lembretes = false;
}

//Carrega informações da casa se for GESTOR

if($_SESSION['tipo'] == 'gestor'){
  $idCasa = $_SESSION['casa'];

  //Checa se a cosa está devidamente cadastrada
  $sql = "SELECT * FROM `casa` WHERE `idCasa` = '$idCasa'";
  $casa = $db->query($sql);
  $casa = (object) $casa->fetch();

  if($casa->email == "" ||
  $casa->endereco == "" ||
  $casa->bairro == "" ||
  $casa->cidade == "" ||
  $casa->estado == ""){
    header("Location: ../my/setupcasa");
  }


  $sitRegra;
  //Carrega situação da regra de negócio
  $diaAtual = date('d');
  $mesAtual = date("m");
  $anoAtual = date('Y');

  $sql = "SELECT `idRegra`, `mes`, `ano` FROM `regra` WHERE `idCasa` = '$idCasa' ORDER BY `dataCadastro` DESC LIMIT 1";
  $last = $db->query($sql);
  $last = $last->fetchAll();

  if(count($last) == 0){
    $last = false;
  } else {
    $mes = $last[0]['mes'];
    $ano = $last[0]['ano'];

    if($mesAtual > $mes){
      if($mesAtual == 12 && $mes == 1){
        $sitRegra = 'dia';
      } else {
        $sitRegra = "atrasada";
      }    } else if($mesAtual == $mes && $diaAtual > 19){
        $sitRegra = "pendente";
      } else {
        $sitRegra = "dia";
      }
    }

    function checaValueNull($val){
      if($val == "" || !is_numeric($val)){
        return 0;
      } else {
        return $val;
      }
    }

    //Checa a quantidade de técnicos
    $sql = "SELECT count(`idUser`) AS `total` FROM `user` WHERE `ramal` = '$idCasa' AND `tipo` = 'tecnico' AND `status` = '1'";
    $tt = $db->query($sql);
    $tt = $tt->fetch();

    if($tt['total'] == 0){
      $tecnico = false;
    } else {
      $tecnico = true;
    }

  }

  $concessionaria = "";
  $lembreteCookie = "";
  $mural = "";
  $agentes = "";
  $supervisores = "";
  $atendimentos = "";
  $regraDeNegocio = "";
  $administradores = "";
  $coordenadores = "";
  $listaClientes = "";
  $meusAtendimentos = "";
  $graficoAtendimentos = "";
  if (isset($_COOKIE["inicioProsicoes"])) {

  	$dadosCookies = json_decode($_COOKIE["inicioProsicoes"]);

  	if(isset($dadosCookies[0]) && is_array($dadosCookies[0])){
	  	foreach ($dadosCookies as $dadosCookie) {
	  		if($dadosCookie[0] == "concessionaria") {
	  			$concessionaria = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}else if($dadosCookie[0] == "lembrete") {
	  			$lembreteCookie = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}else if($dadosCookie[0] == "mural") {
	  			$mural = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}else if($dadosCookie[0] == "agentes") {
	  			$agentes = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}else if($dadosCookie[0] == "supervisores") {
	  			$supervisores = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}else if($dadosCookie[0] == "atendimentos") {
	  			$atendimentos = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}else if($dadosCookie[0] == "regraDeNegocio") {
	  			$regraDeNegocio = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}else if($dadosCookie[0] == "administradores") {
	  			$administradores = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}else if($dadosCookie[0] == "coordenadores") {
	  			$coordenadores = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}else if($dadosCookie[0] == "listaClientes") {
	  			$listaClientes = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}else if($dadosCookie[0] == "meusAtendimentos") {
	  			$meusAtendimentos = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}else if($dadosCookie[0] == "graficoAtendimentos") {
	  			$graficoAtendimentos = array(
	  					$dadosCookie[1],
	  					$dadosCookie[2],
	  					$dadosCookie[3],
	  					$dadosCookie[4]
	  			);
	  		}
	  	}
  	}
  }

  $lembretePosicoesCookie = "";
  if(isset($_COOKIE["lembretes"])){
  	$lembretePosicoesCookie = json_decode($_COOKIE["lembretes"]);
  	if(!isset($lembretePosicoesCookie[0]) && !is_array($lembretePosicoesCookie[0])){
  		$lembretePosicoesCookie = "";
  	}
  }

function concessionaria($x, $y, $logos, $util, $concessionaria) {
	if($util->getSectionPermission('conc')){?>
	<div class="grid-stack-item"
			 id="concessionaria"
			 data-gs-x="<?php if($concessionaria != ""){echo (int) $concessionaria[0];}else{echo $x;}?>"
			 data-gs-y="<?php if($concessionaria != ""){echo (int) $concessionaria[1];}else{echo $y;}?>"
			 data-gs-width="<?php if($concessionaria != ""){echo (int) $concessionaria[2];}else{echo 12;}?>"
			 data-gs-height="<?php if($concessionaria != ""){echo (int) $concessionaria[3];}else{echo 3;}?>"
			 data-gs-min-width="2"
			 data-gs-max-width="12"
			 data-gs-min-height="3"
			 onresize="setCookies()"
			 onclick="setCookies()">
		<div class="grid-stack-item-content card">
			<h4 style="margin-bottom: 0;" class="card-title">Concessionárias</h4>
			<div class="card-body body-concessionarias">
				<?php if(!$logos){?>
	      <hr>
	      <h3 class="text-muted text-center"><i>Nenhuma concessionária cadastrada.</i></h3>
	      <?php
	      	} else {
	      	$aux = 0;
	      ?>
	      <!-- Nav tabs -->
	      <ul class="nav nav-tabs profile-tab" role="tablist">
		      <?php foreach ($logos as $logo) {

		      ?>
		      <li class="nav-item">
		        <a class="nav-link" data-toggle="tab" href="#logoC<?php echo $aux; ?>" role="tab" onclick="requestCasas('<?php echo $logo['logo'] ?>', 'logoC<?php echo $aux; ?>')" aria-selected="true">
			        <div class="bloco-casa-menu" id="logoC<?php echo $aux; ?>img">
			        	<img src="assets/casas/<?php if($logo['logo'] != ""){ echo $logo['logo']; } else { echo "default.png"; } ?>" alt="Concessionária">
			        </div>
		        </a>
		      </li>
		      <?php
		      	$aux++;
		      	}
		      ?>
				</ul>

	      <!-- Tab panes -->
	      <div class="tab-content p-t-20">
	      <?php
	      	$aux = 0;
	        foreach ($logos as $logo) {
	      ?>
	        <div class="tab-pane" id="logoC<?php echo $aux; ?>" role="tabpanel"></div>
	      <?php
	      	$aux++;
	      }
	      $aux++;
	      ?>
	      </div>
	      <?php }?>
	    </div>
		</div>
	</div>
	<?php }
}

function mural($x, $y, $msgs, $nomes, $util, $mural) {
	if ($util->getSectionPermission('mural')) {?>
	<div class="grid-stack-item"
		id="mural"
		data-gs-x="<?php if($mural != ""){echo $mural[0];}else{echo $x;}?>"
		data-gs-y="<?php if($mural != ""){echo $mural[1];}else{echo $y;}?>"
		data-gs-width="<?php if($mural != ""){echo $mural[2];}else{echo 6;}?>"
		data-gs-height="<?php if($mural != ""){echo $mural[3];}else{if($msgs){echo 5;}else{echo 2;}}?>"
		data-gs-min-width="4"
		data-gs-max-width="12"
		data-gs-min-height="2"
		<?php if(!$msgs){?>
		data-gs-max-height="2"
		<?php }?>
		onresize="setCookies()"
    onclick="setCookies()">
			<div class="grid-stack-item-content card">
		    <div class="card-body">
         <h4 class="card-title">Mural da supervisão</h4>
         <div class="card-body">
           <div class="row">
             <?php if(!$msgs){
               ?>
               <span style="text-align: center; width: 100%; font-size: 13px;">
                 <i>O mural de mensagens está limpo</i>
               </span>
               <?php
             } else {
               $count = 0;
               foreach ($msgs as $msg) {
                 $count++;
                 ?>
                 <div class="col-md-12" style="margin-bottom: 30px;">
                   <div class="card-body">
                     <!-- Nav tabs -->
                     <ul class="nav nav-tabs" role="tablist">
                       <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#visao<?php echo $count ?>" role="tab"><span><i class="fa fa-tasks"></i></span></a> </li>
                       <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#det<?php echo $count ?>" role="tab"><span><i class="fa fa-info"></i></span></a> </li>
                     </ul>
                     <!-- Tab panes -->
                     <div class="tab-content tabcontent-border" style="border-left: 2px solid <?php echo retCorMuralBox($msg['criticidade']) ?>">
                       <div class="tab-pane active" id="visao<?php echo $count ?>" role="tabpanel">
                         <div class="p-20">
                           <h4 class="text-<?php echo retCorMuralTexto($msg['criticidade']) ?>"><?php echo $msg['titulo'] ?></h4>
                           <h5><?php echo nl2br($msg['desc']) ?></h5>
                           <i style="font-size: 12px;">Cadastrada por: <?php echo retUserMural($nomes, $msg['idUser']) ?> em <?php echo dataBdParaHtml($msg['data']) ?></i>
                         </div>
                       </div>
                       <div class="tab-pane  p-20" id="det<?php echo $count ?>" role="tabpanel">
                         <div class="p-1">
                           <h6 class="m-b-15">Informações dessa mensagem:</h6>
                           <section style="font-size: 11px;">
                             <span><b>Criticidade:</b> <?php echo retCriticidade($msg['criticidade']) ?></span><br>
                             <span><b>Data de cadastro:</b> <?php echo dataBdParaHtml($msg['data']) ?></span><br>
                             <span><b>Data de expiração:</b> <?php echo retExpMural($msg['expira']) ?></span><br>
                             <span><b>Autor(a):</b> <?php echo retUserMural($nomes, $msg['idUser']) ?></span><br>
                             <span><b>Editada por:</b> <?php echo retUserMural($nomes, $msg['idUserEdit']) ?></span>
                           </section>
                         </div>
                       </div>
                     </div>
                   </div>
                 </div>
                 <?php
               }
             } ?>
           </div>
         </div>
       </div>
			</div>
		</div>
	<?php }
}

function lembretes($x, $y, $favoritos, $lembretes, $lembreteCookie, $lembretePosicoesCookie) {
	if($favoritos && $favoritos["lembrete"]){?>
	<div class="grid-stack-item"
  		 id="lembrete"
  		 data-gs-x="<?php if($lembreteCookie != ""){echo $lembreteCookie[0];}else{echo $x;}?>"
			 data-gs-y="<?php if($lembreteCookie != ""){echo $lembreteCookie[1];}else{echo $y;}?>"
			 data-gs-width="<?php if($lembreteCookie != ""){echo $lembreteCookie[2];}else{echo 6;}?>"
			 data-gs-height="<?php if($lembreteCookie != ""){echo $lembreteCookie[3];}else{if($lembretes){echo "3";}else{echo "2";}}?>"
  		 data-gs-min-width="4"
  		 data-gs-max-width="12"
  		 data-gs-min-height="<?php if($lembretes){echo "3";}else{echo "2";}?>"
  		 <?php if(!$lembretes){?>
  		 data-gs-max-height="2"
  		 <?php }?>
  		 onresize="setCookies()"
  		 onclick="setCookies()">
		<div class="grid-stack-item-content card grid-stack-one-column-mode">
			<div class="grid-stack-item"
					 data-gs-x="0"
	    		 data-gs-y="0"
	    		 data-gs-width="12"
	    		 data-gs-height="1"
					 data-gs-no-resize="yes">
				<div class="grid-stack-item-content">
					<h4 class="card-title">
	        	Meus post-its
	          <span>
	            <button class="btn btn-sm btn-outline btn-info btn-new"
	            				style="float: right;"
	            				data-toggle="modal"
	            				data-target="#modalNovoLembrete">
	              Novo post-it
	            </button>
	          </span>
	      	</h4>
				</div>
			</div>
			<?php if(!$lembretes){?>
			<span style="text-align: center; width: 100%; font-size: 13px;">
	    	<i>Você não tem nenhum post-it</i>
	    </span>
			<?php } else {?>
			<div class="grid-stack-item">
				<div class="grid-stack-item-content">
	    		<div class="grid-stack" id="grid-lembretes">
		    		<?php
		    		foreach ($lembretes as $lembrete) {
		    			$xLembrete = 0;
		    			$yLembrete = 0;
		    			$widthLembrete = 6;
		    			$heightLembrete = 4;
		    			$trava = true;
		    			if($lembretePosicoesCookie != ""){
		    				foreach ($lembretePosicoesCookie as $lembretePosicoes) {
		    					if ($lembretePosicoes[0] == "lembrete".($lembrete['idLembrete']*17)){
		    						$xLembrete = $lembretePosicoes[1];
		    						$yLembrete = $lembretePosicoes[2];
		    						$widthLembrete = $lembretePosicoes[3];
		    						$heightLembrete = $lembretePosicoes[4];
		    						$trava = false;
			    				}
			    			}
		    			}
		    		?>
	    			<div class="grid-stack-item"
	    					 id="lembrete<?php echo $lembrete['idLembrete']*17; ?>"
				    		 data-gs-x="<?php echo $xLembrete;?>"
				    		 data-gs-y="<?php echo $yLembrete;?>"
				    		 data-gs-width="<?php echo $widthLembrete;?>"
			    			 data-gs-height="<?php echo $heightLembrete;?>"
		    				 data-gs-max-width="12"
		    				 data-gs-min-width="3"
		    				 data-gs-max-height="8"
		    				 data-gs-min-height="4"
		    				 <?php if($trava){?>
		    				 data-gs-auto-position="yes"
		    				 <?php }?>
		    				 onresize="salvarCookieLembretes()"
		    				 onclick="salvarCookieLembretes()">
							<div class="grid-stack-item-content card bg-<?php echo $lembrete['cor']; ?> postit-box">
								<div class="trash-postit-edit"
	                   onclick="setEditLembrete('<?php echo $lembrete['titulo'];?>', '<?php echo $lembrete['desc'];?>', '<?php echo dataBdParaHtml($lembrete['alarme']);?>', '<?php echo $lembrete['idLembrete']*17;?>');"
	                   data-toggle="modal"
	                   data-target="#modalEditLembrete">
									<i class="fa fa-pencil-square-o"></i>
								</div>
								<div class="trash-postit" onclick="setTrash('<?php echo $lembrete['idLembrete']*17; ?>')">
									<i class="fa fa-times"></i>
								</div>
								<div class="media widget-ten">
									<div class="media-body media-text-right">
										<h2 class="color-white"><?php echo $lembrete['titulo'] ?></h2>
										<p class="m-b-5"><?php echo $lembrete['desc'] ?></p>
										<?php if($lembrete['alarme'] != '1000-01-01 00:00:00'){ ?>
										<p class="m-b-0 notif-postit ">Notificação em <?php echo dataBdParaHtml($lembrete['alarme']) ?></p>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<?php }?>
					</div>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
	<?php }
}

function agentes($x, $y, $licenca, $agentes) {?>
	<div class="grid-stack-item"
			 id="agentes"
			 data-gs-x="<?php if($agentes != ""){echo $agentes [0];}else{echo $x;}?>"
			 data-gs-y="<?php if($agentes != ""){echo $agentes [1];}else{echo $y;}?>"
			 data-gs-width="<?php if($agentes != ""){echo $agentes [2];}else{echo 3;}?>"
			 data-gs-height="<?php if($agentes != ""){echo $agentes [3];}else{echo 2;}?>"
			 data-gs-min-width="2"
			 data-gs-max-width="12"
			 data-gs-min-height="2"
			 data-gs-max-height="2"
			 onresize="setCookies()"
	     onclick="setCookies()">
		<div class="grid-stack-item-content card">
		 <div class="media">
       <div class="media-left meida media-middle">
         <span><i class="fa fa-headphones f-s-40 color-primary"></i></span>
       </div>
       <div class="media-body media-text-right">
         <h2><?php echo $licenca->getTotalAgente() ?><span style="font-size: 15px;" class="text-muted">/<?php echo $licenca->getLicencaAgente() ?></span></h2>
         <p class="m-b-0">Agentes</p>
       </div>
     </div>
		</div>
	</div>
<?php }

function supervisores($x, $y, $licenca, $supervisores) {?>
	<div class="grid-stack-item"
			 id="supervisores"
			 data-gs-x="<?php if($supervisores != ""){echo $supervisores [0];}else{echo $x;}?>"
			 data-gs-y="<?php if($supervisores != ""){echo $supervisores [1];}else{echo $y;}?>"
			 data-gs-width="<?php if($supervisores != ""){echo $supervisores [2];}else{echo 3;}?>"
			 data-gs-height="<?php if($supervisores != ""){echo $supervisores [3];}else{echo 2;}?>"
			 data-gs-min-width="2"
			 data-gs-max-width="12"
			 data-gs-min-height="2"
			 data-gs-max-height="2"
			 onresize="setCookies()"
	     onclick="setCookies()">
		<div class="grid-stack-item-content card">
		 <div class="media">
       <div class="media-left meida media-middle">
         <span><i class="fa fa-user-circle f-s-40 color-success"></i></span>
       </div>
       <div class="media-body media-text-right">
         <h2><?php echo $licenca->getTotalSupervisor() ?><span style="font-size: 15px;" class="text-muted">/<?php echo $licenca->getLicencaSupervisor() ?></span></h2>
         <p class="m-b-0">Supervisores</p>
       </div>
     </div>
		</div>
	</div>
<?php }

function administradores($x, $y, $licenca, $administradores) {?>
	<div class="grid-stack-item"
			 id="administradores"
			 data-gs-x="<?php if($administradores != ""){echo $administradores [0];}else{echo $x;}?>"
			 data-gs-y="<?php if($administradores != ""){echo $administradores [1];}else{echo $y;}?>"
			 data-gs-width="<?php if($administradores != ""){echo $administradores [2];}else{echo 3;}?>"
			 data-gs-height="<?php if($administradores != ""){echo $administradores [3];}else{echo 2;}?>"
			 data-gs-min-width="2"
			 data-gs-max-width="12"
			 data-gs-min-height="2"
			 data-gs-max-height="2"
			 onresize="setCookies()"
	     onclick="setCookies()">
		<div class="grid-stack-item-content card">
			<div class="media">
	      <div class="media-left meida media-middle">
	        <span><i class="fa fa-user f-s-40 color-warning"></i></span>
	      </div>
	      <div class="media-body media-text-right">
	        <h2><?php echo $licenca->getTotalAdministrador() ?>
	        	<span style="font-size: 15px;" class="text-muted">/<?php echo $licenca->getLicencaAdministrador() ?></span>
	        </h2>
	        <p class="m-b-0">Administradores</p>
	      </div>
	    </div>
		</div>
	</div>
<?php }

function coordenadores($x, $y, $licenca, $coordenadores) {?>
	<div class="grid-stack-item"
			 id="coordenadores"
			 data-gs-x="<?php if($coordenadores != ""){echo $coordenadores [0];}else{echo $x;}?>"
			 data-gs-y="<?php if($coordenadores != ""){echo $coordenadores [1];}else{echo $y;}?>"
			 data-gs-width="<?php if($coordenadores != ""){echo $coordenadores [2];}else{echo 3;}?>"
			 data-gs-height="<?php if($coordenadores != ""){echo $coordenadores [3];}else{echo 2;}?>"
			 data-gs-min-width="2"
			 data-gs-max-width="12"
			 data-gs-min-height="2"
			 data-gs-max-height="2"
			 onresize="setCookies()"
	     onclick="setCookies()">
		<div class="grid-stack-item-content card">
			<div class="media">
      	<div class="media-left meida media-middle">
        	<span><i class="fa fa-briefcase f-s-40 color-danger"></i></span>
        </div>
      	<div class="media-body media-text-right">
        	<h2><?php echo $licenca->getTotalCoordenador() ?><span style="font-size: 15px;" class="text-muted">/<?php echo $licenca->getLicencaCoordenador() ?></span></h2>
          <p class="m-b-0">Coordenadores</p>
        </div>
      </div>
		</div>
	</div>
<?php }

function atendimentos($x, $y, $atendimentos) {?>
	<div class="grid-stack-item"
			 id="atendimentos"
			 data-gs-x="<?php if($atendimentos != ""){echo $atendimentos [0];}else{echo $x;}?>"
			 data-gs-y="<?php if($atendimentos != ""){echo $atendimentos [1];}else{echo $y;}?>"
			 data-gs-width="<?php if($atendimentos != ""){echo $atendimentos [2];}else{echo 6;}?>"
			 data-gs-height="<?php if($atendimentos != ""){echo $atendimentos [3];}else{echo 4;}?>"
			 data-gs-min-width="6"
			 data-gs-max-width="12"
			 data-gs-min-height="4"
			 data-gs-max-height="4"
			 onresize="setCookies()"
	     onclick="setCookies()">
		<div class="grid-stack-item-content card">
    	<div id="cardAtendimento"></div>
		</div>
	</div>
	<?php
}

function regraDeNegocio($x, $y, $tecnico, $last, $regraDeNegocio) {?>
	<div class="grid-stack-item"
			 id="regraDeNegocio"
			 data-gs-x="<?php if($regraDeNegocio != ""){echo $regraDeNegocio [0];}else{echo $x;}?>"
			 data-gs-y="<?php if($regraDeNegocio != ""){echo $regraDeNegocio [1];}else{echo $y;}?>"
			 data-gs-width="<?php if($regraDeNegocio != ""){echo $regraDeNegocio [2];}else{echo 6;}?>"
			 data-gs-height="<?php if($regraDeNegocio != ""){echo $regraDeNegocio [3];}else{echo 3;}?>"
			 data-gs-min-width="4"
			 data-gs-max-width="12"
			 data-gs-min-height="3"
			 onresize="setCookies()"
	     onclick="setCookies()">
		<div class="grid-stack-item-content card">
    	<div class="card-body">
        <h4 class="card-title">Minha regra de negócio</h4>
        <div class="card-body">
          <br>
          <?php
          if(!$tecnico){
            ?>
            <i class="text-danger"><b>Importante!</b> Você precisa cadastrar, pelo menos, um técnico consultor.</i>
            <br><br>
            <a href="novotecnico">
              <button type="button" class="btn btn-sm btn-danger">Cadastrar agora</button>
            </a>
            <?php
          } else {
            if(!$last){
              ?>
              <i><b>Você deve definir uma regra de negócio para começar a utilizar o sistema.</b></i>
              <br><br>
              <a href="novaregra">
                <button type="button" class="btn btn-sm btn-info">Definir agora</button>
              </a>
              <?php
            } else if($sitRegra == 'dia'){

              ?>
              <i>Muito bem! Sua regra de negócio está em dia.</i>
              <br><br>
              <a href="minhacasa">
                <button type="button" class="btn btn-sm btn-info">Ver regra atual</button>
              </a>
              <?php

            } else if($sitRegra == 'pendente'){
              ?>
              <i class="text-danger"><b>Atenção!</b> Está na hora de definir a regra de negócio para o próximo mês.</i>
              <br><br>
              <a href="novaregra">
                <button type="button" class="btn btn-sm btn-danger">Definir agora</button>
              </a>
              <?php
            } else {
              ?>
              <i class="text-danger"><b>Atenção!</b> A sua regra de negócio está <strong>atrasada</strong>. Você deve definir uma regra de negócio agora.</i>
              <br><br>
              <a href="novaregra">
                <button type="button" class="btn btn-sm btn-danger">Definir agora</button>
              </a>
              <?php
            }
          }
          ?>
        </div>
      </div>
		</div>
	</div>
<?php }

function clientes($x, $y, $listaClientes, $favoritos){
	if($favoritos && $favoritos["clientes"]){?>
	<div class="grid-stack-item"
			 id="listaClientes"
			 data-gs-x="<?php if($listaClientes != ""){echo $listaClientes [0];}else{echo $x;}?>"
			 data-gs-y="<?php if($listaClientes != ""){echo $listaClientes [1];}else{echo $y;}?>"
			 data-gs-width="<?php if($listaClientes != ""){echo $listaClientes [2];}else{echo 6;}?>"
			 data-gs-height="<?php if($listaClientes != ""){echo $listaClientes [3];}else{echo 8;}?>"
			 data-gs-max-width="6"
			 data-gs-min-width="3"
			 data-gs-min-height="3"
			 data-gs-max-height="8"
			 onresize="setCookies()"
	     onclick="setCookies()">
		<div class="grid-stack-item-content card">
			<div class="card-body">
				<div class="row">
					<div class="col-7 p-r-0">
			      <h4 class="card-title">Lista de clientes</h4>
					</div>
					<div class="col-5 enterness form-group p-l-0" style="margin-bottom: 6px;">
			      <input type="text" class="form-control float-right" id="pesquisaCliente" placeholder="Pesquise">
					</div>
				</div>
			  <div id="tabelaCliente" class="card-body m-t-15"></div>
			</div>
		</div>
	</div>
	<?php }
}

function meusAtendimentos($x, $y, $meusAtendimentos, $favoritos){
	if($favoritos && $favoritos["meusAtendimentos"]){?>
	<div class="grid-stack-item"
			 id="meusAtendimentos"
			 data-gs-x="<?php if($meusAtendimentos != ""){echo $meusAtendimentos [0];}else{echo $x;}?>"
			 data-gs-y="<?php if($meusAtendimentos != ""){echo $meusAtendimentos [1];}else{echo $y;}?>"
			 data-gs-width="<?php if($meusAtendimentos != ""){echo $meusAtendimentos [2];}else{echo 6;}?>"
			 data-gs-height="<?php if($meusAtendimentos != ""){echo $meusAtendimentos [3];}else{echo 4;}?>"
			 data-gs-max-width="12"
			 data-gs-min-width="6"
			 data-gs-min-height="3"
			 onresize="setCookies()"
	     onclick="setCookies()">
		<div class="grid-stack-item-content card">
			<div class="card-body">
				<div class="row">
					<div class="col-12">
			      <h4 class="card-title">Meus atendimentos</h4>
					</div>
				</div>
			  <div class="card-body">
          <div class="row p-l-10 p-r-10"  id="body-meus-atendimentos">
          </div>
        </div>
			</div>
		</div>
	</div>
	<?php }
}

function chat($x, $y){
	$meusAtendimentos = "";
	if(/* $favoritos && $favoritos["meusAtendimentos"] */true){?>
	<div class="grid-stack-item"
			 id="meusAtendimentos"
			 data-gs-x="<?php if($meusAtendimentos != ""){echo $meusAtendimentos [0];}else{echo $x;}?>"
			 data-gs-y="<?php if($meusAtendimentos != ""){echo $meusAtendimentos [1];}else{echo $y;}?>"
			 data-gs-width="<?php if($meusAtendimentos != ""){echo $meusAtendimentos [2];}else{echo 6;}?>"
			 data-gs-height="<?php if($meusAtendimentos != ""){echo $meusAtendimentos [3];}else{echo 6;}?>"
			 data-gs-max-width="12"
			 data-gs-min-width="6"
			 data-gs-min-height="3"
			 onresize="setCookies()"
	     onclick="setCookies()">
		<div class="grid-stack-item-content card">
			<div class="card-body">
				<div class="row">
					<div class="col-12">
			      <h4 class="card-title">Chat</h4>
					</div>
				</div>
			  <div class="card-body">


<div id="frame">
	<div id="sidepanel">
		<div id="search">
			<label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
			<input type="text" placeholder="Pesquisar" />
		</div>
		<div id="contacts">
			<ul>
				<li class="contact">
					<div class="wrap">
						<span class="contact-status online"></span>
						<img src="http://emilcarlsson.se/assets/louislitt.png" alt="" />
						<div class="meta">
							<p class="name">Louis Litt</p>
						</div>
					</div>
				</li>
				<li class="contact active">
					<div class="wrap">
						<span class="contact-status busy"></span>
						<img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
						<div class="meta">
							<p class="name">Harvey Specter</p>
						</div>
					</div>
				</li>
				<li class="contact">
					<div class="wrap">
						<span class="contact-status away"></span>
						<img src="http://emilcarlsson.se/assets/rachelzane.png" alt="" />
						<div class="meta">
							<p class="name">Rachel Zane</p>
						</div>
					</div>
				</li>
				<li class="contact">
					<div class="wrap">
						<span class="contact-status online"></span>
						<img src="http://emilcarlsson.se/assets/donnapaulsen.png" alt="" />
						<div class="meta">
							<p class="name">Donna Paulsen</p>
						</div>
					</div>
				</li>
				<li class="contact">
					<div class="wrap">
						<span class="contact-status busy"></span>
						<img src="http://emilcarlsson.se/assets/jessicapearson.png" alt="" />
						<div class="meta">
							<p class="name">Jessica Pearson</p>
						</div>
					</div>
				</li>
				<li class="contact">
					<div class="wrap">
						<span class="contact-status"></span>
						<img src="http://emilcarlsson.se/assets/haroldgunderson.png" alt="" />
						<div class="meta">
							<p class="name">Harold Gunderson</p>
						</div>
					</div>
				</li>
				<li class="contact">
					<div class="wrap">
						<span class="contact-status"></span>
						<img src="http://emilcarlsson.se/assets/danielhardman.png" alt="" />
						<div class="meta">
							<p class="name">Daniel Hardman</p>
						</div>
					</div>
				</li>
				<li class="contact">
					<div class="wrap">
						<span class="contact-status busy"></span>
						<img src="http://emilcarlsson.se/assets/katrinabennett.png" alt="" />
						<div class="meta">
							<p class="name">Katrina Bennett</p>
						</div>
					</div>
				</li>
				<li class="contact">
					<div class="wrap">
						<span class="contact-status"></span>
						<img src="http://emilcarlsson.se/assets/charlesforstman.png" alt="" />
						<div class="meta">
							<p class="name">Charles Forstman</p>
						</div>
					</div>
				</li>
				<li class="contact">
					<div class="wrap">
						<span class="contact-status"></span>
						<img src="http://emilcarlsson.se/assets/jonathansidwell.png" alt="" />
						<div class="meta">
							<p class="name">Jonathan Sidwell</p>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="content">
		<div class="contact-profile">
			<img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
			<p>Harvey Specter</p>
		</div>
		<div class="messages">
			<ul>
				<li class="sent">
					<img src="http://emilcarlsson.se/assets/mikeross.png" alt="" />
					<p>How the hell am I supposed to get a jury to believe you when I am not even sure that I do?!</p>
				</li>
				<li class="replies">
					<img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
					<p>When you're backed against the wall, break the god damn thing down.</p>
				</li>
				<li class="replies">
					<img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
					<p>Excuses don't win championships.</p>
				</li>
				<li class="sent">
					<img src="http://emilcarlsson.se/assets/mikeross.png" alt="" />
					<p>Oh yeah, did Michael Jordan tell you that?</p>
				</li>
				<li class="replies">
					<img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
					<p>No, I told him that.</p>
				</li>
				<li class="replies">
					<img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
					<p>What are your choices when someone puts a gun to your head?</p>
				</li>
				<li class="sent">
					<img src="http://emilcarlsson.se/assets/mikeross.png" alt="" />
					<p>What are you talking about? You do what they say or they shoot you.</p>
				</li>
				<li class="replies">
					<img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
					<p>Wrong. You take the gun, or you pull out a bigger one. Or, you call their bluff. Or, you do any one of a hundred and forty six other things.</p>
				</li>
			</ul>
		</div>
		<div class="message-input">
			<div class="wrap">
				<input type="text" placeholder="Write your message..." />
				<button class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
			</div>
		</div>
	</div>
</div>



        </div>
			</div>
		</div>
	</div>
	<?php }
}

function grafico($x, $y, $favoritos, $graficoAtendimentos){
	if($favoritos && $favoritos["graficoAtendimentos"]){?>
	<div class="grid-stack-item"
			 id="graficoAtendimentos"
			 data-gs-x="<?php if($graficoAtendimentos != ""){echo $graficoAtendimentos [0];}else{echo $x;}?>"
			 data-gs-y="<?php if($graficoAtendimentos != ""){echo $graficoAtendimentos [1];}else{echo $y;}?>"
			 data-gs-width="<?php if($graficoAtendimentos != ""){echo $graficoAtendimentos [2];}else{echo 12;}?>"
			 data-gs-height="<?php if($graficoAtendimentos != ""){echo $graficoAtendimentos [3];}else{echo 6;}?>"
			 data-gs-max-width="12"
			 data-gs-min-width="6"
			 data-gs-max-height="6"
			 data-gs-min-height="6"
			 onresize="setCookies()"
	     onclick="setCookies()">
		<div class="grid-stack-item-content card">
			<div class="card-body">
			  <canvas class="line-chart"></canvas>
			</div>
		</div>
	</div>
	<?php
	}
}

?>
