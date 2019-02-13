<?php
include '../application/dicionario-restricoes.php';
// Define nível de restrição da página
$allowUser = array (
	'dev',
	'coordenador',
	'administrador',
	'supervisor'
);
checaPermissao ( $allowUser );
include 'inc/head.php';
?>
<div id="main-wrapper">
	<?php include 'inc/header.php'; ?>
	<?php include 'inc/sidebar.php'; ?>
	<div class="page-wrapper">
		<!-- Bread crumb -->
		<div class="row page-titles">
			<div class="col-md-5 align-self-center">
				<h3 class="padrao">
					MyOmni<i>Dicionário de restrições</i>
				</h3>
			</div>
			<div class="col-md-7 align-self-center">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
					<li class="breadcrumb-item">MyOmni<i>Dicionário de restrições</i></li>
				</ol>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<form action="../application/dicionario-restricoes" method="post" class="enterness">
							<div class="">
								<h3>Dicionário de restrições</h3>
								<br>
							</div>
							<div class="row">
								<div class="col-4 offset-2">
									<input name="palavra" class="form-control" type="text" required="required" placeholder="Informe a palavra">
								</div>
								<div class="col-2">
									<select class="form-control" name="categoria">
										<option value="palavrao">Palavrão</option>
										<option value="alerta">Palavra de alerta</option>
									</select>
								</div>
								<div class="col-2">
									<button type="submit" class="btn btn-info m-b-10 m-l-5">Adicionar</button>
								</div>
							</div>
						</form>
						<hr>
						<div class="row">
							<div class="col-12">
								<?php if(count($linhas) == 0){ ?>
									<center>
										<h3 class="text-muted"><i>Nenhuma palavra voi restringida.</i></h3>
									</center>
									<?php
								} else { ?>
									<h5>Palavras restringidas</h5>
									<div class="table-responsive m-t-10">
										<table id="tabelaLogS"
										class="display nowrap table table-hover table-striped table-bordered"
										cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>#</th>
												<th>Palavra</th>
												<th>Categoria</th>
												<th>Autor do registro</th>
												<th>Ação</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$aux = 1;
											foreach ( $linhas as $linha ) {
												?>
												<tr>
													<td><b><?php echo $aux; ?></b></td>
													<td><?php echo $linha[0]; ?></td>
													<td><?php echo $linha[1]; ?></td>
													<td><?php echo $linha[2]; ?></td>
													<td>
														<a href="../application/dicionario-restricoes?id=<?php echo $linha[3]; ?>">
															<button type="button"
															class="btn btn-sm btn-danger btn-outline">
															<i class="fa fa-trash-o" aria-hidden="true"></i>
														</button>
													</a>
												</td>
											</tr>
											<?php
											$aux ++;
										}
										?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'inc/footer.php'; ?>
</div>
<!-- End Page wrapper  -->
</div>
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>

<script type="text/javascript">
$(document).ready(function() {
	$('#tabelaLogS').DataTable();

	<?php if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'success'){
		?>
		swal("Opa, tudo certo!", "Uma nova palavra foi cadastrada!", "success");
		<?php
	} ?>

	<?php if(isset($_GET['deletar']) && $_GET['deletar'] == 'success'){
		?>
		swal("Opa, tudo certo!", "Palavra apagada!", "success");
		<?php
	} ?>

});

</script>
