<?php
include '../coreExt.php';

if(!isset($_SESSION['id'])){
	echo "unlogged";
	die();
}

$page = tratarString($_POST["page"]);
$filtro = tratarString($_POST["filtro"]);

/* Carrega a lista de clientes */
$id = $_SESSION['id'];
$sqlTT = "SELECT COUNT(`idCliente`) AS `tt` FROM `cliente`";

if ($filtro != "") {
	$sqlTT .= " WHERE (`nome` LIKE '%".$filtro."%' OR `fone` LIKE '%".$filtro."%' OR `email` LIKE '%".$filtro."%')";
}

$sql = str_replace(
		'COUNT(`idCliente`) AS `tt`',
		'`idCliente`, `nome`, `fone`, `email`, `foto`',
		$sqlTT);

$tt = ($page*7)-7;
$sql .= " ORDER BY `nome` ASC LIMIT $tt, 7";

$totalClientes = $db->query($sqlTT);
$totalClientes = $totalClientes->fetch();
$totalClientes = $totalClientes['tt'];

if(($totalClientes%7) == 0){
	$ttPage = (int) ($totalClientes/7);
}else{
	$ttPage = (int) ($totalClientes/7) + 1;
}

$clientes = $db->query($sql);
$clientes = $clientes->fetchAll();

if(!$clientes && $filtro == ""){
?>
<hr>
<h3 class="text-muted center"><i>Nenhum cliente cadastrado.</i></h3>
<?php } else if(!$clientes) { ?>
<hr>
<h3 class="text-muted center"><i>Nenhum cliente encontrado.</i></h3>
<?php } else { ?>
<small>Página <b id="bapg" class="padrao"><?php echo $page ?></b> de <b id="btpg" class="padrao"><?php echo $ttPage ?></b> | Total de <b id="btrg" class="padrao"><?php echo $totalClientes ?></b> clientes.</small>

<div class="list-group list-group-flush m-t-5 m-b-15">
	<?php foreach ($clientes as $cliente) {?>
	<div class="list-group-item list-group-item-action p-5 cursorPointer" onclick="window.location.href = 'editaCliente?hash=<?php echo ($cliente["idCliente"]*951);?>';">
		<img class="rounded-circle min avatar-chat avatar-atendimentos" src="assets/<?php if($cliente['foto'] != ""){echo "medias/clients/".$cliente['foto'];}else{?>avatar/default.jpg<?php }?>" alt="Foto do cliente">
		<div class="m-l-40 lista-cliente">
			<h6 class="text-truncate lista-cliente-header">
				<b><?php echo $cliente['nome'];?></b>
			</h6>
			<div class="text-truncate lista-cliente-body">
				<?php if ($cliente['fone'] != "") {
					$fones = json_decode($cliente['fone']);
					foreach ($fones as $fone) {
						if($fone[0]){?>
							<i class="fa fa-volume-control-phone" aria-hidden="true"></i> <?php echo $fone[1];?>
						<?php }
					}
				}
				if ($cliente['email'] != "") {?>
				<i class="fa fa-envelope-o m-l-5" aria-hidden="true"></i> <?php echo $cliente['email'];?>
				<?php }?>
			</div>
		</div>
	</div>
	<?php }?>
</div>
<div class="m-t-5">
	<nav class="pull-right">
		<div class="btn-group">
			<button onclick="clickPagination('first')" class="btn btn-info btn-sm btn-outline<?php if($page == 1){ echo ' disabled'; } ?>" <?php if($page == 1){ echo ' disabled'; } ?>>Primeira</button>
			<button onclick="clickPagination('prev')" class="btn btn-info btn-sm btn-outline<?php if($page == 1){ echo ' disabled'; } ?>" <?php if($page == 1){ echo ' disabled'; } ?>>Anterior</button>
			<button onclick="clickPagination('next')" class="btn btn-info btn-sm btn-outline<?php if($page == $ttPage){ echo ' disabled'; } ?>" <?php if($page == $ttPage){ echo ' disabled'; } ?>>Próxima</button>
			<button onclick="clickPagination('last')" class="btn btn-info btn-sm btn-outline<?php if($page == $ttPage){ echo ' disabled'; } ?>" <?php if($page == $ttPage){ echo ' disabled'; } ?>>Última</button>
		</div>
	</nav>
</div>
<?php }?>