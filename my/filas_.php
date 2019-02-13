<?php
include '../application/filas.php';
// Define nível de restrição da página
$allowUser = array (
		'dev',
		'coordenador',
		'administrador'
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
				<h3 class="padrao">Filas</h3>
			</div>
			<div class="col-md-7 align-self-center">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
					<li class="breadcrumb-item">Filas</li>
				</ol>
			</div>
		</div>
		<!-- End Bread crumb -->
		<!-- Container fluid  -->
		<div class="container-fluid">
			<!-- Start Page Content -->
      <?php if($licenca->getXcontactStatus()){ ?>
        <div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title">Filas sincronizadas</h4>
							<div class="table-responsive m-t-10">
								<table id="tabelaLogS"
									class="display nowrap table table-hover table-striped table-bordered"
									cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Nome da fila</th>
											<th>Nome visível</th>
											<th>Status</th>
											<th>Transborda p/</th>
											<th>Ação</th>
										</tr>
									</thead>
									<tbody>
                      <?php
							$aux = 1;
							foreach ( $filas as $fl ) {
								?>
                        <tr>
											<td><b><?php echo $aux ?></b></td>
											<td><?php echo $fl['nome'] ?></td>
											<td><?php echo $fl['fantasia'] ?></td>
											<td>
                            <?php
								if ($fl ['status'] == 0) {
									?>
                              <span
												class="label label-rouded label-default">Desativado</span>
                              <?php
								} else {
									?>
                              <span
												class="label label-rouded label-primary">Ativado</span>
                              <?php
								}
								?>
                          </td>
											<td><?php echo $fl['transbordo'] ?></td>
											<td><button type="button" class="btn btn-secondary btn-sm"
													data-toggle="modal" data-target="#modalSetupFila"
													onclick="setModalEditFila('<?php echo $fl['idFila'] ?>', '<?php echo $fl['nome'] ?>','<?php echo $fl['fantasia'] ?>', '<?php echo $fl['status'] ?>', '<?php echo $fl['transbordo'] ?>')">
													<i class="ti-pencil-alt"></i>
												</button></td>
										</tr>
                        <?php
								$aux ++;
							}
							?>
                    </tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
      <?php } else { ?>
        <div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-6">
									<h4 class="card-title">Filas cadastradas</h4>
								</div>
								<div class="col-6">
									<button style="" class="btn btn-info btn-sm pull-right"
										data-toggle="modal" data-target="#modalNovaFila">Nova fila</button>
									<button style="margin-right: 10px;"
										class="btn btn-info btn-sm pull-right" data-toggle="modal"
										data-target="#modalConfigFilas">Priori. filas</button>
								</div>
							</div>
							<div class="table-responsive m-t-10">
								<table id="tabelaLogC"
									class="display nowrap table table-hover table-striped table-bordered"
									cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Nome da fila</th>
											<th>Transbordo</th>
											<th>Status nas mídias</th>
											<th>Status protocolo</th>
											<th>Data de cadastro</th>
											<th>Agentes</th>
											<th>Ação</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$aux = 1;
										foreach ( $filas as $fl ) {
										?>
                    <tr>
											<td><b><?php echo $aux ?></b></td>
											<td><?php echo $fl['nomeFila'] ?></td>
											<td><?php echo $fl['transbordo'] ?></td>
											<td><?php if ($fl ['status'] == 0) {?>
												<span class="label label-rouded label-default">Desativada</span>
                        <?php } else {?>
                        <span class="label label-rouded label-primary">Ativada</span>
                        <?php }?>
                      </td>
											<td><?php if ($fl ['statusProtocolo'] == 0) {?>
												<span class="label label-rouded label-default">Desativado</span>
                        <?php } else {?>
                        <span class="label label-rouded label-primary">Ativado</span>
                        <?php }?>
                      </td>
											<td><?php echo dataBdParaHtml($fl['dataCadastro']) ?></td>
											<td><?php echo qtdAgentes($fl['nomeFila']) ?></td>
											<td><a
												href="detfila?cript=hash&id=<?php echo $fl['idFila']*53 ?>">
													<button type="button" class="btn btn-secondary btn-sm">
														<i class="ti-pencil-alt"></i>
													</button>
											</a></td>
										</tr>
										<?php
										$aux ++;
										}
										?>
                  </tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-6">
									<h4 class="card-title">Filas de e-mail</h4>
								</div>
								<div class="col-6" style="display: <?php if(count($filas) == 0){ echo "none"; } ?>">
									<button class="btn btn-info btn-sm pull-right"
										data-toggle="modal" data-target="#modalNovoEmail">Novo e-mail</button>
								</div>
							</div>
							<div class="table-responsive m-t-10">
								<table id="tabelaLogE"
									class="display nowrap table table-hover table-striped table-bordered"
									cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>#</th>
											<th>E-mail</th>
											<th>Fila</th>
											<th>Sincronismo</th>
											<th>Data de cadastro</th>
											<th>Ação</th>
										</tr>
									</thead>
									<tbody>
                      <?php
							$aux = 1;
							foreach ( $emails as $fl ) {
								?>
                        <tr>
											<td><b><?php echo $aux ?></b></td>
											<td><?php echo $fl['email'] ?></td>
											<td><?php echo $fl['fila'] ?></td>
											<td>
                            <?php
								if ($fl ['valid'] == 'yes') {
									?>
                              <span
												class="label label-rouded label-success">Ativo</span>
                              <?php
								} else if ($fl ['valid'] == 'no') {
									?>
                              <span
												class="label label-rouded label-danger">Inativo</span>
                              <?php
								} else {
									?>
                              <span
												class="label label-rouded label-warning">Testando</span>
                              <?php
								}
								?>
                          </td>
											<td><?php echo dataBdParaHtml($fl['dataCadastro']) ?></td>
											<td><button type="button" class="btn btn-secondary btn-sm"
													data-toggle="modal" data-target="#modalNovoEmail"
													onclick="setModalEditEmail('<?php echo $fl['idEmail'] ?>', '<?php echo $fl['fila'] ?>','<?php echo $fl['email'] ?>', '<?php echo $fl['senha'] ?>', '<?php echo $fl['conexao'] ?>', '<?php echo $fl['servidor'] ?>', '<?php echo $fl['port'] ?>', '<?php echo $fl['criptografia'] ?>')">
													<i class="ti-pencil-alt"></i>
												</button></td>
										</tr>
                        <?php
								$aux ++;
							}
							?>
                    </tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
      <?php } ?>
      <!-- End PAge Content -->
		</div>
		<!-- End Container fluid  -->
    <?php include 'inc/footer.php'; ?>
  </div>
	<!-- End Page wrapper  -->
</div>
<!-- End Wrapper -->

<!-- Modal -->
<div class="modal fade" id="modalSetupFila" tabindex="-1" role="dialog"
	aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form class="enterness" name="formEditFila"
				action="../application/editaFila" method="POST">
				<div class="modal-header">
					<h5 class="modal-title" id="modalTitle">Editar fila</h5>
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close" onclick="reload()"
						style="margin-top: -20px; margin-right: -20px;">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<input type="hidden" name="id" id="idIn" value="">
							<div class="form-group">
								<label class="col-sm-12 control-label junta">Nome visível:</label>
								<div class="col-sm-12">
									<input type="text" maxlength="48" required name="fantasia"
										class="form-control" id="inFantasia"
										placeholder="Digite um nome visível">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-12 control-label junta">Transbordar para:</label>
								<div class="col-sm-12">
									<select class="form-control" name="transbordo"
										id="selectTransbordo">
										<option value="">Sem transbordo</option>
                    <?php
																				foreach ( $filas as $fila ) {
																					?>
                      <option value="<?php echo $fila['nome'] ?>"><?php echo $fila['fantasia'] ?></option>
                      <?php
																				}
																				?>
                  </select>
								</div>
							</div>
							<div class="form-group text-center">
								<label class="col-sm-12 control-label">Fila ativa?</label>
								<div class="col-sm-12">
									<div class="form-check form-check-inline"
										style="padding-left: 10px;">
										<input class="form-check-input" type="checkbox" name="status"
											id="checkboxStatus" value="1">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary"
						data-dismiss="modal" onclick="reload()">Cancelar</button>
					<button type="submit" class="btn btn-info">Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalNovaFila" tabindex="-1" role="dialog"
	aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form class="enterness" name="formEditFila"
				action="../application/novaFila" method="POST">
				<div class="modal-header">
					<h5 class="modal-title">Nova fila</h5>
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close" onclick="reload()"
						style="margin-top: -20px; margin-right: -20px;">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								<label class="col-sm-12 control-label junta">Nome da fila:</label>
								<div class="col-sm-12">
									<input type="text" maxlength="48" required name="nome"
										class="form-control" id="idNomeFila"
										placeholder="Nome da fila" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-12 control-label junta">Transbordar para:</label>
								<div class="col-sm-12">
									<select class="form-control" name="transbordo"
										id="selectTransbordoNova">
										<option value="">Sem transbordo</option>
                    <?php
																				foreach ( $filas as $fila ) {
																					?>
                      <option value="<?php echo $fila['nomeFila'] ?>"><?php echo $fila['nomeFila'] ?></option>
                      <?php
																				}
																				?>
                  </select>
								</div>
							</div>
							<div class="row form-group text-center">
								<label class="col-sm-6 control-label">Fila ativa nas mídias?</label>
								<label class="col-sm-6 control-label">Protocolo ativa?</label>
								<div class="col-sm-6">
									<div class="form-check form-check-inline"
										style="padding-left: 10px;">
										<input class="form-check-input" type="checkbox" name="status"
											id="checkboxStatusNewst" value="1">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-check form-check-inline"
										style="padding-left: 10px;">
										<input class="form-check-input" type="checkbox" name="statusProtocolo"
											id="checkboxStatusProtocolo" value="1">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary"
						data-dismiss="modal" onclick="reload()">Cancelar</button>
					<button type="submit" class="btn btn-info">Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal para oredenar a sequencia das filas -->
<div class="modal fade" id="modalConfigFilas" tabindex="-1"
	role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form class="enterness" name="formEditFila"
				action="../application/prioriFila" method="POST">
				<div class="modal-header">
					<h5 class="modal-title">Prioridade das filas</h5>
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close" onclick="reload()"
						style="margin-top: -20px; margin-right: -20px;">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-8 offset-2">
							<table id="linhas">
								<tr>
									<th style="border-right: 1px solid #eee;"><b>Nº</b></th>
									<th style="text-align: center;"><b>Fila</b></th>
								</tr>	
								<?php
								$cont = 1;
								$novaPri = "";
								foreach ( $filas as $fila ) {
									?>								
								<tr>
									<td class="row-n"><?php echo $cont; ?></td>
									<td class="row-fila">
										<div class="linha label-primary" draggable="true"><?php echo $fila["nomeFila"]; ?>
											<samp class="idFilaHidden" hidden="true"><?php echo $fila["idFila"]; ?></samp>
										</div>
									</td>
								</tr>
								<?php
									if ($novaPri == "") {
										$novaPri = $fila ["idFila"];
									} else {
										$novaPri = $novaPri . "-" . $fila ["idFila"];
									}
									$cont ++;
								}
								?>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary"
						data-dismiss="modal" onclick="reload()">Cancelar</button>
					<button type="submit" class="btn btn-info">Salvar</button>
				</div>
				<input hidden="true" id="novaPri" name="novaPri"
					value="<?php echo $novaPri; ?>">
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalNovoEmail" tabindex="-1" role="dialog"
	aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form class="enterness" name="formEditFila"
				action="../application/novoEmail" method="POST">
				<div class="modal-header">
					<h5 class="modal-title">Novo e-mail</h5>
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close" onclick="reload()"
						style="margin-top: -20px; margin-right: -20px;">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<input type="hidden" name="id" id="idInEmail" value="">
							<div class="row form-group">
								<div class="col-sm-12">
									<label class="control-label junta">Fila:</label> <select
										class="form-control" name="fila" id="selectFila">
                    <?php
																				foreach ( $filas as $fila ) {
																					?>
                      <option value="<?php echo $fila['nomeFila'] ?>"><?php echo $fila['nomeFila'] ?></option>
                      <?php
																				}
																				?>
                  </select>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-sm-12">
									<label class="control-label junta">E-mail:</label> <input
										type="text" maxlength="48" required name="email"
										class="form-control" id="inEmail" placeholder="E-mail">
								</div>
							</div>
							<div class="row form-group">
								<div class="col-sm-12">
									<label class="control-label junta">Senha:</label> <input
										type="password" maxlength="48" required name="senha"
										class="form-control" id="inSenha" placeholder="Senha">
								</div>
							</div>
							<div class="row form-group">
								<div class="col-4">
									<label class="control-label junta">Conexão:</label> <select
										class="form-control" name="conexao" id="selectConexao">
										<option value="imap" selected>IMAP</option>
										<option value="pop3">POP3</option>
									</select>
								</div>
								<div class="col-8">
									<label class="control-label junta">Servidor:</label> <input
										type="text" maxlength="48" required name="host"
										class="form-control" id="inHost" placeholder="Servidor">
								</div>
							</div>
							<div class="row form-group">
								<div class="col-6">
									<label class="control-label junta">Porta:</label> <input
										type="number" min="0" max="9999999999" required name="porta"
										class="form-control" id="inPorta" placeholder="Servidor">
								</div>
								<div class="col-6">
									<label class="control-label junta">Habilitar SSL?</label> <select
										class="form-control" name="ssl" id="selectSsl">
										<option value="1">Sim</option>
										<option value="0">Não</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer-en"
					style="padding: 20px; border-top: 1px solid #eee;">
					<div class="row">
						<div class="col-3">
							<button type="button"
								class="btn btn-outline btn-sm btn-danger pull-left"
								id="btnTrashEmail" style="display: none;">
								<i class="ti-trash"></i>
							</button>
						</div>
						<div class="col-9">
							<button type="submit" class="btn btn-info pull-right">Salvar</button>
							<button type="button" class="btn btn-secondary pull-right"
								data-dismiss="modal" onclick="reload()"
								style="margin-right: 10px;">Cancelar</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


<?php include 'inc/scripts.php'; ?>
<script src="assets/js/lib/switcher/jquery.switcher.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	function carregaOrdem(){
		var novaPri = "";
		$("#linhas tr").each(function() {
			var idFila = $(this).find('.idFilaHidden').html();
			if(typeof idFila != "undefined"){
				if(novaPri == ""){
					novaPri = idFila;
				} else {
					novaPri = novaPri + "-" + idFila;
				}
			}
		});
		$("#novaPri").val(novaPri);
	}
	
	function handleDragStart(e) {
	  this.style.opacity = '0.4';

	  this.classList.add('moving');
	  
	  dragSrcEl = this;

	  e.dataTransfer.effectAllowed = 'move';
	  e.dataTransfer.setData('text/html', this.innerHTML);
	}

	function handleDragOver(e) {
		if (e.preventDefault) {
			e.preventDefault();
		}
		e.dataTransfer.dropEffect = 'move';
		return false;
	}

	function handleDragEnter(e) {
	  this.classList.add('over');
	}

	function handleDragLeave(e) {
	  this.classList.remove('over');
	}

	function handleDrop(e) {
		  if (e.stopPropagation) {
		    e.stopPropagation();
		  }
		  if (dragSrcEl != this) {
		    dragSrcEl.innerHTML = this.innerHTML;
		    this.innerHTML = e.dataTransfer.getData('text/html');
		  }
		  carregaOrdem();
		  return false;
	}

	function handleDragEnd(e) {
	  [].forEach.call(cols, function (col) {
	    col.classList.remove('over');
	  });

	  this.classList.remove('moving');
	  this.style.opacity = '1';
	}

	var cols = document.querySelectorAll('#linhas .linha');
		[].forEach.call(cols, function(col) {
	  col.addEventListener('dragstart', handleDragStart, false);
	  col.addEventListener('dragenter', handleDragEnter, false)
	  col.addEventListener('dragover', handleDragOver, false);
	  col.addEventListener('dragleave', handleDragLeave, false);
	  col.addEventListener('drop', handleDrop, false);
	  col.addEventListener('dragend', handleDragEnd, false);
	});
	
  <?php

		if (isset ( $_GET ['update'] ) && $_GET ['update'] == 'success') {
			?>
    swal("Sucesso!", "As informações da fila foram atualizadas!", "success");
    <?php
		}
		?>
  <?php

		if (isset ( $_GET ['cadastro'] ) && $_GET ['cadastro'] == 'failure' && isset ( $_GET ['registry'] )) {
			?>
    swal("Ops!", "A fila <?php echo urldecode($_GET['registry']) ?> já está cadastrada!", "danger");
    <?php
		}
		?>
  <?php

		if (isset ( $_GET ['cadastroemail'] ) && $_GET ['cadastroemail'] == 'failure' && isset ( $_GET ['registry'] )) {
			?>
    swal("Ops!", "O e-mail <?php echo urldecode($_GET['registry']) ?> já está cadastrado!", "danger");
    <?php
		}
		?>
  <?php

		if (isset ( $_GET ['cadastro'] ) && $_GET ['cadastro'] == 'success') {
			?>
    swal("Sucesso!", "Nova fila cadastrada!", "success");
    <?php
		}
		?>
  <?php

		if (isset ( $_GET ['cadastroemail'] ) && $_GET ['cadastroemail'] == 'success') {
			?>
    swal("Sucesso!", "Novo e-mail cadastrado!", "success");
    <?php
		}
		?>
  <?php

		if (isset ( $_GET ['editemail'] ) && $_GET ['editemail'] == 'success') {
			?>
    swal("Sucesso!", "As informações do e-mail foram alteradas!", "success");
    <?php
		}
		?>
  <?php

		if (isset ( $_GET ['emailremoved'] ) && $_GET ['emailremoved'] == 'success') {
			?>
    swal("Sucesso!", "E-mail apagado!", "success");
    <?php
		}
		?>
  <?php

		if (isset ( $_GET ['action'] ) && $_GET ['action'] == 'trashed' && isset ( $_GET ['status'] ) && $_GET ['status'] == 'success') {
			?>
    swal("Feito!", "A fila foi apagada", "success");
    <?php
		}
		?>

  $('#tabelaLogS').DataTable({
    dom: 'Bfrtip',
    buttons: [
      /*'csv', 'excel', 'pdf', 'print'*/
    ]
  });
  $('#tabelaLogC').DataTable({
    dom: 'Bfrtip',
    buttons: [
      /*'csv', 'excel', 'pdf', 'print'*/
    ]
  });
  $('#tabelaLogE').DataTable({
    dom: 'Bfrtip',
    buttons: [
      /*'csv', 'excel', 'pdf', 'print'*/
    ]
  });

  var ano = new Date();
  ano.setFullYear(ano.getFullYear() - 1);

  $('.datepicker-here').datepicker({
    language: 'pt',
    minDate: ano,
    maxDate: new Date()
  });

  $(function(){
    $.switcher('input[type=checkbox]');
  });


});

function setModalEditFila(id, nome, fantasia, status, trans){
  $("#idIn").val(id);
  $("#inFantasia").val(fantasia);
  var atStat = document.getElementById('checkboxStatus').checked;

  $("#selectTransbordo option[value='"+nome+"']").remove();
  $("#selectTransbordo option[value='"+trans+"']").attr('selected', true);

  if(status == '1' && atStat == false){
    $("#checkboxStatus").click();
  } else if(status == '0' && atStat == true){
    $("#checkboxStatus").click();
  }
}

function setModalEditEmail(id, fila, email, senha, conn, host, port, crip){
  $("#idInEmail").val(id);
  $("#selectFila option[value='"+fila+"']").attr('selected', true);
  $("#inEmail").val(email);
  $("#inSenha").val(senha);
  $("#selectConexao option[value='"+conn+"']").attr('selected', true);
  $("#inHost").val(host);
  $("#inPorta").val(port);
  $("#selectSsl option[value='"+crip+"']").attr('selected', true);
  $("#btnTrashEmail").show();
}

$("#btnTrashEmail").click(function(){

  var settedId = $("#idInEmail").val();
  swal({
    title: "Deseja realmente excluir esse e-mail?",
    text: "Essa ação não poderá ser desfeita!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Sim, apagar!",
    cancelButtonText: "Cancelar",
    closeOnConfirm: true,
    closeOnCancel: true
  },
  function(isConfirm){
    if(isConfirm){
      $.ajax({
        url: '../application/novoEmail',
        method: 'POST',
        data: {
          action: 'delete',
          crip: settedId
        },
        success: function(data) {
          window.location.href="filas?emailremoved=success";
        }
      });
    }
  });

});


function reload(){
  window.location.href="filas";
}

</script>
</body>

</html>
