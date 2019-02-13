<?php
include '../application/lembretes.php';
// Define nível de restrição da página
$allowUser = array (
	'dev',
	'coordenador',
	'administrador',
	'supervisor',
	'agente',
	'gestor',
	'tecnico'
);
checaPermissao ( $allowUser );

include 'inc/head.php';
?>

<!-- Main wrapper  -->
<div id="main-wrapper">
	<?php include 'inc/header.php'; ?>
	<?php include 'inc/sidebar.php'; ?>
	<!-- Page wrapper  -->
	<div class="page-wrapper">
		<!-- Bread crumb -->
		<div class="row page-titles">
			<div class="col-md-5 align-self-center">
				<h3 class="padrao" style="float:left; margin-right: 10px;">Post-its</h3>
				<a href="javascript:void(0)" onclick="favorito();">
					<img
					src="<?php if($favorito){?>assets/icons/star1.png
					<?php } else {?>assets/icons/star0.png
					<?php }?>"
					id="favorito">
				</a>
			</div>
			<div class="col-md-7 align-self-center">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
					<li class="breadcrumb-item">Meus Post-its</li>
				</ol>
			</div>
		</div>
		<!-- End Bread crumb -->
		<!-- Container fluid  -->
		<div class="container-fluid">
			<!-- Start Page Content -->
			<div class="row">
				<div class="col-12">
					<div class="card">
						<h4 class="card-title">
							Meus post-its <span>
								<button class="btn btn-sm btn-info btn-new" data-toggle="modal"
								data-target="#modalNovoLembrete">Novo post-it</button>
							</span>
						</h4>
						<hr>
						<div class="row">
							<?php

							if (! $lembretes) {
								?>
								<span style="text-align: center; width: 100%;">
									<h3>
										<i>Você não tem nenhum post-it</i>
									</h3>
								</span>
								<?php
							} else {
								foreach ( $lembretes as $lembrete ) {
									?>
									<div class="col-md-6 col-lg-4">
										<div
										class="card bg-<?php echo $lembrete['cor']; ?> p-20 postit-box">
										<div class="trash-postit-edit"
										onclick="setEditLembrete('<?php echo $lembrete['titulo'];?>', '<?php echo $lembrete['desc'];?>', '<?php echo dataBdParaHtml($lembrete['alarme']);?>', '<?php echo $lembrete['idLembrete']*17;?>');"
										data-toggle="modal" data-target="#modalEditLembrete">
										<i class="fa fa-pencil-square-o"></i>
									</div>
									<div class="trash-postit"
									onclick="setTrash('<?php echo $lembrete['idLembrete']*17; ?>')">
									<i class="fa fa-times"></i>
								</div>
								<div class="media widget-ten">
									<div class="media-left meida media-middle">
										<span><i
											class="fa fa-bell-<?php if($lembrete['alarme'] == '1000-01-01 00:00:00' || $lembrete['alarme'] == ""){ echo "slash-"; }?>o f-s-40"></i></span>
											</div>
											<div class="media-body media-text-right">
												<h2 class="color-white"><?php echo $lembrete['titulo'] ?></h2>
												<p class="m-b-5"><?php echo $lembrete['desc'] ?></p>
												<?php if($lembrete['alarme'] != '1000-01-01 00:00:00' && $lembrete['alarme'] != ""){ ?>
													<p class="m-b-0 notif-postit">Notificação em <?php echo dataBdParaHtml($lembrete['alarme']) ?></p>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<!-- End PAge Content -->
	</div>
	<!-- End Container fluid  -->
	<?php include 'inc/footer.php'; ?>
</div>
<!-- End Page wrapper  -->
</div>
<div class="modal fade" id="modalNovoLembrete" tabindex="-1"
role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalCenterTitle">Inserir novo
				post-it</h5>
				<button type="button" class="close" data-dismiss="modal"
				aria-label="Close" onclick="reload()"
				style="margin-top: -20px; margin-right: -20px;">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<form class="enterness" action="../application/novoLembrete"
		method="post">
		<div class="modal-body">
			<div class="form-group">
				<label class="junta">Título do post-it <i>(Opcional)</i></label> <input
				type="text" maxlength="40" name="titulo" class="form-control"
				placeholder="Ex.: Não esquecer!">
			</div>
			<div class="form-group">
				<label class="junta">Conteúdo do post-it</label>
				<textarea class="form-control" maxlength="400" required
				name="lembrete" placeholder="Lembrete" rows="4"
				style="height: 110px;"></textarea>
			</div>
			<div class="form-group">
				<label class="junta">Cor do post-it</label> <input type="hidden"
				name="cor" id="corIn" value="primary">
				<div class="box-lembretes-cores">
					<div class="cor-lembrete bg-primary cor-lembrete-ativo"
					id="cor-primary" onclick="setCorModal('primary')"></div>
					<div class="cor-lembrete bg-info" id="cor-info"
					onclick="setCorModal('info')"></div>
					<div class="cor-lembrete bg-success" id="cor-success"
					onclick="setCorModal('success')"></div>
					<div class="cor-lembrete bg-warning" id="cor-warning"
					onclick="setCorModal('warning')"></div>
					<div class="cor-lembrete bg-danger" id="cor-danger"
					onclick="setCorModal('danger')"></div>
					<div class="cor-lembrete bg-dark" id="cor-dark"
					onclick="setCorModal('dark')"></div>
					<div class="cor-lembrete bg-secondary" id="cor-secondary"
					onclick="setCorModal('secondary')"></div>
				</div>
			</div>
			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input"
					id="checkAlarm"> <label class="custom-control-label"
					for="checkAlarm">Receber notificação do sistema</label>
				</div>
			</div>
			<div class="row" id="sessaoDataIn" style="display: none;">
				<div class="col-sm-8">
					<div class="form-group">
						<label class="junta" style="width: 100%;">Data da notificação:</label>
						<br> 
						<input type='text' 
									 onkeyup="limpaInput()" 
									 id="dataInLembrete" 
									 value="" 
									 name="notificacao" 
									 class="form-control datepicker-here" 
									 data-language='pt' 
									 data-position="top center" 
									 placeholder="Defina a data da notificação" 
									 autocomplete="off"/>
					</div>
				</div>
				<div class="col-sm-4 form-group clockpicker">
          <label class="control-label junta">Hora</label>
          <input type='text' class="form-control" onkeyup="limpaInput()" name="hora" id="inHora" placeholder="00:00" autocomplete="off">
        </div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary"
			data-dismiss="modal" onclick="reload()">Cancelar</button>
			<button type="submit" class="btn btn-info">Salvar lembrete</button>
		</div>
	</form>
</div>
</div>
</div>
<!-- Editar post-it -->
<div class="modal fade" id="modalEditLembrete" tabindex="-1"
role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalCenterTitleEdit">Editar
				post-it</h5>
				<button type="button" class="close" data-dismiss="modal"
				aria-label="Close" onclick="reload()"
				style="margin-top: -20px; margin-right: -20px;">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<form class="enterness" action="../application/editaLembrete"
		method="post">
		<input type="hidden" name="father" value="inicio">
		<div class="modal-body">
			<div class="form-group">
				<label class="junta">Título do post-it <i>(Opcional)</i></label> <input
				type="text" maxlength="40" name="titulo" class="form-control"
				placeholder="Ex.: Não esquecer!">
			</div>
			<div class="form-group">
				<label class="junta">Conteúdo do post-it</label>
				<textarea class="form-control" maxlength="400" required
				name="lembrete" placeholder="Lembrete" rows="4"
				style="height: 110px;"></textarea>
			</div>
			<div class="form-group">
				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input"
					id="checkAlarmEdit"> <label class="custom-control-label"
					for="checkAlarmEdit">Receber notificação do sistema</label>
				</div>
			</div>
			<div class="row" id="sessaoDataInEdit" style="display: none;">
				<div class="col-sm-8">
					<div class="form-group">
						<label class="junta" style="width: 100%;">Data da notificação:</label>
						<br> 
						<input type='text' 
									 onkeyup="limpaInput()" 
									 id="dataInLembrete" 
									 value="" 
									 name="notificacao" 
									 class="form-control datepicker-here" 
									 data-language='pt' 
									 data-position="top center" 
									 placeholder="Defina a data da notificação" 
									 autocomplete="off"/>
					</div>
				</div>
				<div class="col-sm-4 form-group clockpicker">
          <label class="control-label junta">Hora</label>
          <input type='text' class="form-control" onkeyup="limpaInput()" name="hora" id="inHora" placeholder="00:00" autocomplete="off">
        </div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary"
			data-dismiss="modal" onclick="reload()">Cancelar</button>
			<button type="submit" class="btn btn-info">Salvar edição</button>
		</div>
		<input hidden="true" value="lembretes" name="page"> <input
		hidden="true" value="" name="idEdit" id="idEdit">
	</form>
</div>
</div>
</div>
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">

$('.clockpicker').clockpicker({
  placement: 'top',
	autoclose: true
});

function setEditLembrete(titulo, desc, dataTime, id){
	$("#idEdit").val(id);
	$("input[name='titulo']").val(titulo);
	while(desc.indexOf("<br>") != -1){
		desc = desc.replace("<br>", "\r\n");
	}
	$("textarea[name='lembrete']").val(desc);
	if(dataTime != "01/01/1000 às 00:00"){
		$("#checkAlarmEdit").prop("checked", true);
		$("#sessaoDataInEdit").show();
		document.getElementById('dataInLembreteEdit').required = true;
		$("#dataInLembreteEdit").val(dataTime);
	}
}

function limpaInputEdit(){
	document.getElementById('dataInLembreteEdit').value = "";
}

$(document).ready(function() {

	$( "#checkAlarmEdit" ).change(function() {
		if(this.checked){
			$("#sessaoDataInEdit").show();
			document.getElementById('dataInLembreteEdit').required = true;
		} else {
			$("#sessaoDataInEdit").hide();
			document.getElementById('dataInLembreteEdit').required = false;
			limpaInputEdit();
		}
	});

	var selected;
	$('.datepicker-here').datepicker({
		language: 'pt',
		minDate: new Date() // Now can select only dates, which goes after today
	});
	<?php

	if (isset ( $_GET ['cadastro'] ) && $_GET ['cadastro'] == 'success') {
		?>
		swal("Opa, tudo certo!", "Um novo post-it foi criado!", "success")
		<?php
	}
	?>
	<?php

	if (isset ( $_GET ['action'] ) && $_GET ['action'] == 'trashed' && isset ( $_GET ['status'] ) && $_GET ['status'] == 'success') {
		?>
		swal("Feito!", "O post-it foi jogado fora!", "success")
		<?php
	}
	?>
	<?php if(isset($_GET['edit']) && $_GET['edit'] == 'success'){?>
		swal("Feito!", "O post-it foi alterado!", "success")
		<?php }?>
	});

	function limpaInput(){
		document.getElementById('dataInLembrete').value = "";
	}

	function reload(){
		window.location.href="lembretes";
	}

	function setCorModal(cor){
		$(".cor-lembrete").addClass('cor-lembrete-inativo').removeClass('cor-lembrete-ativo');
		$("#cor-"+cor).addClass('cor-lembrete-ativo');
		document.getElementById('corIn').value = cor;
	}

	$( "#checkAlarm" ).change(function() {
		if(this.checked){
			$("#sessaoDataIn").show();
			document.getElementById('dataInLembrete').required = true;
		} else {
			$("#sessaoDataIn").hide();
			document.getElementById('dataInLembrete').required = false;
			limpaInput();
		}
	});

	function setTrash(hash){
		swal({
			title: "Deseja mesmo jogar esse Post-it fora?",
			text: "Essa ação não poderá ser desfeita!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Sim, jogar fora!",
			cancelButtonText: "Cancelar",
			closeOnConfirm: true,
			closeOnCancel: true
		},
		function(isConfirm){
			if(isConfirm){
				$.ajax({
					type: "POST",
					data: {
						hash : hash
					},
					url: "../application/deletaPostit",
					success: function(result){
						if(result == '1'){
							window.location.href = "lembretes?action=trashed&status=success";
						} else if(result == '0'){
							window.location.href = "lembretes?action=trashed&status=failure";
						}
					}
				});
			}
		});
	}

	function favorito() {

		var favorito = document.getElementById("favorito").src;
		var favoritar = 1;

		if(favorito == "http://<?php echo $config->server; ?>/my/assets/icons/star1.png"){
			favoritar = 0;
		}

		$.ajax({
			type: "POST",
			data: {
				page : "lembretes",
				favorito: favoritar
			},
			url: "../application/ajaxFavorito",
			success: function(result){
				if(result == "true"){
					document.getElementById("favorito").src = "assets/icons/star1.png";
				} else {
					document.getElementById("favorito").src = "assets/icons/star0.png";
				}
			}
		});

	}
</script>
</body>
</html>
