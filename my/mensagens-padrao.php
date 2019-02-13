<?php
include '../application/mensagens-padrao.php';
// Define nível de restrição da página
$allowUser = array (
		'dev',
		'coordenador',
		'administrador'
);
checaPermissao ( $allowUser );
include 'inc/head.php';
?>
<div id="main-wrapper">
  <?php include 'inc/header.php'; ?>
  <?php include 'inc/sidebar.php'; ?>
	<div class="page-wrapper">
		<div class="row page-titles">
			<div class="col-md-5 align-self-center">
				<h3 class="padrao">
					MyOmni<i>Mensagens padrões</i>
				</h3>
			</div>
			<div class="col-md-7 align-self-center">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
					<li class="breadcrumb-item">MyOmni<i>Mensagens padrões</i></li>
				</ol>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<form action="../application/mensagens-padrao" method="post" class="enterness">
							<h3>Mensagens padrões</h3>
							<br>
							<div class="row">
								<div class="col-8 offset-2">
									<div class="row">
										<div class="col-1">
											<label style="font-size: 18px;">Alt+1</label>
										</div>
										<div class="col-11">
											<input name="c1" class="form-control" type="text" value="<?php echo $atalhos[1];?>" placeholder="Mensagem" maxlength="250">
										</div>
									</div>
									<div class="row" style="margin-top: 10px;">
										<div class="col-1">
											<label style="font-size: 18px;">Alt+2</label>
										</div>
										<div class="col-11">
											<input name="c2" class="form-control" type="text" value="<?php echo $atalhos[2];?>" placeholder="Mensagem" maxlength="250">
										</div>
									</div>
									<div class="row" style="margin-top: 10px;">
										<div class="col-1">
											<label style="font-size: 18px;">Alt+3</label>
										</div>
										<div class="col-11">
											<input name="c3" class="form-control" type="text" value="<?php echo $atalhos[3];?>" placeholder="Mensagem" maxlength="250">
										</div>
									</div>
									<div class="row" style="margin-top: 10px;">
										<div class="col-1">
											<label style="font-size: 18px;">Alt+4</label>
										</div>
										<div class="col-11">
											<input name="c4" class="form-control" type="text" value="<?php echo $atalhos[4];?>" placeholder="Mensagem" maxlength="250">
										</div>
									</div>
									<div class="row" style="margin-top: 10px;">
										<div class="col-1">
											<label style="font-size: 18px;">Alt+5</label>
										</div>
										<div class="col-11">
											<input name="c5" class="form-control" type="text" value="<?php echo $atalhos[5];?>" placeholder="Mensagem" maxlength="250">
										</div>
									</div>
									<div class="row" style="margin-top: 10px;">
										<div class="col-1">
											<label style="font-size: 18px;">Alt+6</label>
										</div>
										<div class="col-11">
											<input name="c6" class="form-control" type="text" value="<?php echo $atalhos[6];?>" placeholder="Mensagem" maxlength="250">
										</div>
									</div>
									<div class="row" style="margin-top: 10px;">
										<div class="col-1">
											<label style="font-size: 18px;">Alt+7</label>
										</div>
										<div class="col-11">
											<input name="c7" class="form-control" type="text" value="<?php echo $atalhos[7];?>" placeholder="Mensagem" maxlength="250">
										</div>
									</div>
									<div class="row" style="margin-top: 10px;">
										<div class="col-1">
											<label style="font-size: 18px;">Alt+8</label>
										</div>
										<div class="col-11">
											<input name="c8" class="form-control" type="text" value="<?php echo $atalhos[8];?>" placeholder="Mensagem" maxlength="250">
										</div>
									</div>
									<div class="row" style="margin-top: 10px;">
										<div class="col-1">
											<label style="font-size: 18px;">Alt+9</label>
										</div>
										<div class="col-11">
											<input name="c9" class="form-control" type="text" value="<?php echo $atalhos[9];?>" placeholder="Mensagem" maxlength="250">
										</div>
									</div>
									<div class="row" style="margin-top: 10px;">
										<div class="col-1">
											<label style="font-size: 18px;">Alt+0</label>
										</div>
										<div class="col-11">
											<input name="c0" class="form-control" type="text" value="<?php echo $atalhos[0];?>" placeholder="Mensagem" maxlength="250">
										</div>
									</div>
									<div class="row" style="margin-top: 10px; float: right; padding-right: 15px;">
										<button type="submit" class="btn btn-info m-b-10 m-l-5">Salvar</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
		    <?php include 'inc/footer.php'; ?>
		  </div>
		</div>
	</div>
</div>
<?php include 'inc/scripts.php'; ?>

<script type="text/javascript">
$(document).ready(function() {

  <?php if(isset($_GET['salvar']) && $_GET['salvar'] == 'success'){
    ?>
    swal("Opa, tudo certo!", "Alteração realizada com sucesso!", "success");
    <?php
  } ?>
  
});

</script>

<?php include '../application/atalhos.php'; ?>

