<?php
include '../application/media.php';
// Define nível de restrição da página
$allowUser = array (
		'dev',
		'coordenador',
		'administrador',
		'supervisor',
		'agente'
);
checaPermissao ( $allowUser );
$page = "media";
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
				<h3 class="padrao">
					Atendimento: <i>Redes sociais</i>
				</h3>
			</div>
			<div class="col-md-7 align-self-center">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
					<li class="breadcrumb-item">Atendimento: <i>Redes sociais</i></li>
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
						<div class="row">
							<div class="col-12">
								<div class="">
									<h4 class="card-title">Meus atendimentos abertos:</h4>
									<div class="recent-comment box-contatos box-atendimentos"
										id="listaAtendimentos"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div id="content" class="col-12">
								<div class="">
									<div class="card-body">
										<?php if (! $setted) {?>
											<div class="background-chat"></div>
										<?php } else {?>
											<div class="box-chat">
											<div class="box-chat-header">
												<div id="circleStatusChat"
													class="avatar-chat avatar-atendimentos">
													<?php if ($cliente ['foto'] != "" && $cliente ['foto'] != NULL) {?>
													<div class="avatar-chat">
														<img src="assets/medias/clients/<?php echo $cliente['foto'] ?>">
													</div>
														<?php } else {?>
														<img src="assets/icons/social/<?php echo $at->plataforma ?>.png">
														<?php }?>
												</div>
												<div class="name-chat">
													<h3><?php if($at->nome != ""){ echo $at->nome;} else { echo $at->remetente; }?></h3>
													<i class="text-muted">Inicio do atendimento: <?php echo dataBdParaHtml($at->dataInicio) ?></i>
													<div class="dropdown btn-trash" style="margin-top: 30px;">
														<!-- Split dropleft button -->
														<?php if ($at->plataforma != 'whatsapp') {?>
															<span class="btn btn-secondary btn-sm btn-outline"
															onclick="changeTo('whatsapp')"> <span
															class="fa fa-whatsapp" aria-hidden="true"></span>
														</span>
															<?php }?>
														<span class="btn btn-secondary btn-sm btn-outline"
															id="startWebCam" data-toggle="modal"
															data-target="#attachWebcam"> <span class="fa fa-camera"
															aria-hidden="true"></span>
														</span> <span class="btn btn-secondary btn-sm btn-outline"
															data-toggle="modal" data-target="#attachModal"> <span
															class="fa fa-paperclip" aria-hidden="true"></span>
														</span> <span class="btn btn-secondary btn-sm btn-outline"
															id="popover-atalos"> <span class="fa fa-th-list"
															aria-hidden="true"></span>
														</span>
														<!-- Lista de popover -->
														<div id="popover-content" class="hide">
															<ul>
																<li><b>Alt+1:</b> <?php echo $atalhos[1]["texto"];?></li>
																<li><b>Alt+2:</b> <?php echo $atalhos[2]["texto"];?></li>
																<li><b>Alt+3:</b> <?php echo $atalhos[3]["texto"];?></li>
																<li><b>Alt+4:</b> <?php echo $atalhos[4]["texto"];?></li>
																<li><b>Alt+5:</b> <?php echo $atalhos[5]["texto"];?></li>
																<li><b>Alt+6:</b> <?php echo $atalhos[6]["texto"];?></li>
																<li><b>Alt+7:</b> <?php echo $atalhos[7]["texto"];?></li>
																<li><b>Alt+8:</b> <?php echo $atalhos[8]["texto"];?></li>
																<li><b>Alt+9:</b> <?php echo $atalhos[9]["texto"];?></li>
																<li><b>Alt+0:</b> <?php echo $atalhos[0]["texto"];?></li>
															</ul>
														</div>
														<div class="btn-group btn-sm">
															<div class="btn-group dropleft" role="group">
																<button type="button"
																	class="btn btn-secondary dropdown-toggle dropdown-toggle-split btn-sm"
																	data-toggle="dropdown" aria-haspopup="true"
																	aria-expanded="false">
																	<span class="sr-only"></span>
																</button>
																<div class="dropdown-menu">
																	<a class="dropdown-item" href="#" data-toggle="modal"
																		data-target="#modalTransfereAtendimento"
																		onclick="openModal()">Transferir</a>
																	<button class="dropdown-item" data-toggle="modal"
																		data-target="#modalEstacionarAtendimento"
																		onclick="openModal()">Estacionar</button>
																	<!-- <a class="dropdown-item" href="#">Something else here</a> -->
																</div>
															</div>
															<button
																class="btn btn-info btn-sm btn-hover-success btn-group"
																onclick="salvarAntes()">Finalizar</button>
															<button id="salvarAntes" data-toggle="modal"
																data-target="#modalFinalizaAtendimento" hidden="true">oi</button>
														</div>
														<span id="botaoCadastro" class="btn btn-sm btn-info"> <span
															class="fa fa-bars" aria-hidden="true"></span>
														</span>
													</div>
												</div>
											</div>
											<div class="box-chat-body" id="chatAtendimento"
												onclick="desativaAncora()"></div>
											<div class="box-chat-footer">
												<form class="enterness">
													<div class="row">
														<div class="col-10">
															<textarea class="textarea_editor" name="msg" autofocus
																id="textareaChat" placeholder="Digite..."
																onkeydown="areaEnvia(this, event);"></textarea>
															<span class="botoes-textarea">
																<ul>
																	<li onclick="selecionaTexto('negrito')"><i
																		class="fa fa-bold" aria-hidden="true"></i></li>
																	<li onclick="selecionaTexto('italico')"><i
																		class="fa fa-italic" aria-hidden="true"></i></li>
																	<li onclick="selecionaTexto('riscado')"><i
																		class="fa fa-strikethrough" aria-hidden="true"></i></li>
																</ul>
															</span>
														</div>
														<div class="col-2 btn-send-chat">
															<button type="button" class="btn btn-info btn-block"
																onclick="sendMessage()">
																<i class="fa fa-share" aria-hidden="true"></i>
															</button>
														</div>
													</div>
												</form>
											</div>
										</div>
							<?php }?>
						</div>
								</div>
							</div>

							<div id="sidebar" class="col-md-5 bg-white"
								style="display: none;">
								<div class="row">
									<div class="col-12 p-l-0">
										<div class="j-relative jumboReportRegra m-0 p-20 p-b-55"
											style="height: 450px; background: #fafafa">
											<ul class="nav nav-tabs" id="myTab" role="tablist">
												<li class="nav-item"><a class="nav-link active" id="tabUm"
													data-toggle="tab" href="#cardUm" role="tab"
													aria-controls="cardUm" aria-selected="true">Pessoal</a></li>
												<li class="nav-item"><a class="nav-link" id="tabDois"
													data-toggle="tab" href="#cardDois" role="tab"
													aria-controls="cardDois" aria-selected="false">Endereço</a>
												</li>
											</ul>
											<form class="form-horizontal enterness"
												action="../application/editaCliente?hash=<?php echo "none";?>"
												method="POST" name="novoCliente">
												<div class="tab-content" id="myTabContent">
													<div class="tab-pane active card m-0" id="cardUm"
														role="tabpanel" aria-labelledby="tabUm"
														style="height: 325px;">
														<div class="card-body">
															<div class="row">
																<div class="col-7">
																	<div class="form-group m-0">
																		<label class="control-label junta">Nome completo</label>
																		<div class="">
																			<input value="<?php echo $cliente["nome"];?>"
																				type="text" class="form-control" name="nome"
																				id="inNome" placeholder="Nome">
																		</div>
																	</div>
																</div>
																<div class="col-5">
																	<div class="form-group m-0">
																		<label class="control-label junta">Data de nascimento</label>
																		<div class="">
																			<input
																				value="<?php echo dataBdParaHtml($cliente["nascimento"]);?>"
																				type="text" class="form-control datepicker-here"
																				data-position='left top' data-language='pt'
																				name="nascimento" id="inNascimento"
																				placeholder="Data de nascimento">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-6">
																	<div class="form-group m-0">
																		<label class="control-label junta">Email</label>
																		<div class="">
																			<input value="<?php echo $cliente["email"];?>"
																				type="email" class="form-control" name="email"
																				id="inEmail" placeholder="Email">
																		</div>
																	</div>
																</div>
																<div class="col-6 form-group">
																	<label class="control-label junta">Telefone</label>
																	<div class="input-group">
																		<input type="tel" value="<?php echo $fones[0][1];?>"
																			class="form-control" name="fone1" id="inFone1"
																			placeholder="Telefone" autocomplete="none"
																			aria-haspopup="true" aria-expanded="false"
																			data-toggle="dropdown" checked />
																		<div class="input-group-prepend">
																			<div class="input-group-text">
																				<input id="at1" type="radio" name="numAtivo" value="1"
																					aria-label="Radio button for following text input"
																					<?php if($fones[0][0]){?> checked <?php }?>>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-6 form-group">
																	<label class="control-label junta">Telefone</label>
																	<div class="input-group">
																		<input type="tel" value="<?php echo $fones[1][1];?>"
																			class="form-control" name="fone1" id="inFone2"
																			placeholder="Telefone" autocomplete="none"
																			aria-haspopup="true" aria-expanded="false"
																			data-toggle="dropdown" />
																		<div class="input-group-prepend">
																			<div class="input-group-text">
																				<input id="at2" type="radio" name="numAtivo" value="2"
																					aria-label="Radio button for following text input"
																					<?php if($fones[1][0]){?> checked <?php }?>>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-6 form-group">
																	<label class="control-label junta">Telefone</label>
																	<div class="input-group">
																		<input type="tel" value="<?php echo $fones[2][1];?>"
																			class="form-control" name="fone1" id="inFone3"
																			placeholder="Telefone" autocomplete="none"
																			aria-haspopup="true" aria-expanded="false"
																			data-toggle="dropdown" />
																		<div class="input-group-prepend">
																			<div class="input-group-text">
																				<input id="at3" type="radio" name="numAtivo" value="3"
																					aria-label="Radio button for following text input"
																					<?php if($fones[2][0]){?> checked <?php }?>>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-5">
																	<div class="form-group m-0">
																		<label class="control-label junta">CPF</label>
																		<div class="">
																			<input value="<?php echo $cliente["cpf"];?>"
																				type="text" class="form-control" name="cpf"
																				id="inCpf" placeholder="CPF">
																		</div>
																	</div>
																</div>
																<div class="col-7">
																	<div class="form-group m-0 center">
																		<label class="control-label junta">Aceita receber
																			material promocional</label>
																		<div class="p-t-10">
																			<div class="">
																				<input value="<?php echo $cliente["promocoes"];?>"
																					type="checkbox" class="form-control"
																					name="promocoes" id="inPromocoes"
																					placeholder="Aceita receber material promocional"
																					<?php if ($cliente["promocoes"] == 1) {?>
																					checked="checked" <?php }?> />
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="tab-pane card m-0" id="cardDois"
														role="tabpanel" aria-labelledby="tabDois"
														style="height: 325px;">
														<div class="card-body">
															<div class="row">
																<div class="col-8">
																	<div class="form-group m-0">
																		<label class="control-label junta">Rua</label>
																		<div class="">
																			<input value="<?php echo $cliente["rua"];?>"
																				type="text" class="form-control" name="rua"
																				id="inRua" placeholder="Rua">
																		</div>
																	</div>
																</div>
																<div class="col-4">
																	<div class="form-group m-0">
																		<label class="control-label junta">Nº</label>
																		<div class="">
																			<input value="<?php if(isset($cliente["numero"])){echo $cliente["numero"];}?>"
																				type="number" class="form-control" name="numResi"
																				id="inNumResi" placeholder="Número">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-6">
																	<div class="form-group m-0">
																		<label class="control-label junta">Complemento</label>
																		<div class="">
																			<input value="<?php echo $cliente["complemento"];?>"
																				type="text" class="form-control" name="complemento"
																				id="inComplemento" placeholder="complemento">
																		</div>
																	</div>
																</div>
																<div class="col-6">
																	<div class="form-group m-0">
																		<label class=" control-label junta">Bairro</label>
																		<div class="">
																			<input value="<?php echo $cliente["bairro"];?>"
																				type="text" class="form-control" name="bairro"
																				id="inBairro" placeholder="Bairro">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-5">
																	<div class="form-group m-0">
																		<label class="control-label junta">Cidade</label> <input
																			value="<?php echo $cliente["cidade"];?>" type="text"
																			class="form-control" name="cidade" id="inCidade"
																			placeholder="Cidade">
																	</div>
																</div>
																<div class="col-4">
																	<div class="form-group m-0">
																		<label class="control-label junta">Estado</label> <select
																			class="form-control" id="estado" name="estado">
																			<option <?php if($cliente["uf"] == ""){?> selected
																				<?php }?> value="">Selecione</option>
																			<option disabled>──────────</option>
																			<option <?php if($cliente["uf"] == "AC"){?> selected
																				<?php }?> value="AC">Acre</option>
																			<option <?php if($cliente["uf"] == "AL"){?> selected
																				<?php }?> value="AL">Alagoas</option>
																			<option <?php if($cliente["uf"] == "AP"){?> selected
																				<?php }?> value="AP">Amapá</option>
																			<option <?php if($cliente["uf"] == "AM"){?> selected
																				<?php }?> value="AM">Amazonas</option>
																			<option <?php if($cliente["uf"] == "BA"){?> selected
																				<?php }?> value="BA">Bahia</option>
																			<option <?php if($cliente["uf"] == "CE"){?> selected
																				<?php }?> value="CE">Ceará</option>
																			<option <?php if($cliente["uf"] == "DF"){?> selected
																				<?php }?> value="DF">Distrito Federal</option>
																			<option <?php if($cliente["uf"] == "ES"){?> selected
																				<?php }?> value="ES">Espírito Santo</option>
																			<option <?php if($cliente["uf"] == "GO"){?> selected
																				<?php }?> value="GO">Goiás</option>
																			<option <?php if($cliente["uf"] == "MA"){?> selected
																				<?php }?> value="MA">Maranhão</option>
																			<option <?php if($cliente["uf"] == "MT"){?> selected
																				<?php }?> value="MT">Mato Grosso</option>
																			<option <?php if($cliente["uf"] == "MS"){?> selected
																				<?php }?> value="MS">Mato Grosso do Sul</option>
																			<option <?php if($cliente["uf"] == "MG"){?> selected
																				<?php }?> value="MG">Minas Gerais</option>
																			<option <?php if($cliente["uf"] == "PA"){?> selected
																				<?php }?> value="PA">Pará</option>
																			<option <?php if($cliente["uf"] == "PB"){?> selected
																				<?php }?> value="PB">Paraíba</option>
																			<option <?php if($cliente["uf"] == "PR"){?> selected
																				<?php }?> value="PR">Paraná</option>
																			<option <?php if($cliente["uf"] == "PE"){?> selected
																				<?php }?> value="PE">Pernambuco</option>
																			<option <?php if($cliente["uf"] == "PI"){?> selected
																				<?php }?> value="PI">Piauí</option>
																			<option <?php if($cliente["uf"] == "RJ"){?> selected
																				<?php }?> value="RJ">Rio de Janeiro</option>
																			<option <?php if($cliente["uf"] == "RN"){?> selected
																				<?php }?> value="RN">Rio Grande do Norte</option>
																			<option <?php if($cliente["uf"] == "RS"){?> selected
																				<?php }?> value="RS">Rio Grande do Sul</option>
																			<option <?php if($cliente["uf"] == "RO"){?> selected
																				<?php }?> value="RO">Rondônia</option>
																			<option <?php if($cliente["uf"] == "RR"){?> selected
																				<?php }?> value="RR">Roraima</option>
																			<option <?php if($cliente["uf"] == "SC"){?> selected
																				<?php }?> value="SC">Santa Catarina</option>
																			<option <?php if($cliente["uf"] == "SP"){?> selected
																				<?php }?> value="SP">São Paulo</option>
																			<option <?php if($cliente["uf"] == "SE"){?> selected
																				<?php }?> value="SE">Sergipe</option>
																			<option <?php if($cliente["uf"] == "TO"){?> selected
																				<?php }?> value="TO">Tocantins</option>
																		</select>
																	</div>
																</div>
																<div class="col-3">
																	<div class="form-group m-0">
																		<label class="control-label junta">CEP</label>
																		<div class="">
																			<input value="<?php echo $cliente["cep"];?>"
																				type="text" class="form-control" name="cep"
																				id="inCep" placeholder="CEP">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-12">
																<div class="form-group m-0 center">
																	<label class="control-label junta">Esse cliente
																		representa uma empresa</label>
																	<div class="p-t-10">
																		<input type="checkbox" class="form-control"
																			value="<?php echo $cliente["empresa"];?>"
																			name="clienteEmpresa" id="inClienteEmpresa"
																			<?php if ($cliente["empresa"] == 1) {?>
																			checked="checked" <?php }?> />
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<input hidden="true" value="<?php echo $idCliente;?>"
													id="idCliente">
												<div class="pull-right p-t-10">
													<button type="button" id="btnSalvar" class="btn btn-info">Salvar</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
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
<!-- End Wrapper -->
<?php if($setted){ ?>
<div class="modal fade" id="attachWebcam" tabindex="-1000" role="dialog"
	aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Tirar foto</h5>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close" onclick="reload()"
					style="margin-top: -20px; margin-right: -20px;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="media-webcam-content m-t-10">
						<video src="" id="video" muted autoplay></video>
						<canvas id="pic"></canvas>
					</div>
					<center id="botaoTirar">
						<input type="button" class="btn btn-info m-b-20 m-t-15"
							id="btnStart" value="Tirar Foto">
					</center>
					<center id="botoesEnviar">
						<input type="button"
							class="btn btn-danger btn-outline m-b-20 m-t-15"
							id="btnTirarOutra" value="Tirar outra"> <input type="button"
							class="btn btn-info m-b-20 m-t-15" id="btnEnviarFoto"
							value="Enviar foto">
					</center>
					<div class="alert alert-info m-l-30 m-r-30 text-center"
						id="fotoEnviada" role="alert">Foto enviada com sucesso!</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"
					onclick="reload()">Voltar</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="attachModal" tabindex="-1000" role="dialog"
	aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Anexar arquivos</h5>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close" onclick="reload()"
					style="margin-top: -20px; margin-right: -20px;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form
				action="../application/sendMedia?hash=<?php echo $at->idAtendimento * 11 ?>"
				method="post" class="dropzone p-15" enctype="multipart/form-data">
				<div class="fallback">
					<input name="file" type="file" multiple />
				</div>
			</form>
			<p class="text-center text-muted m-0 p-5" style="font-size: 12px;">
				<i>Formatos permitidos: 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi',
					'mp3', 'doc', 'docx' e 'pdf'</i>
			</p>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"
					onclick="reload()">Ok</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalFinalizaAtendimento" tabindex="-1"
	role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Finalizar
					atendimento</h5>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close" onclick="reload()"
					style="margin-top: -20px; margin-right: -20px;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form class="enterness" action="../application/finalizaAtendimento"
				method="post">
				<input type="hidden" name="crip"
					value="<?php echo $at->idAtendimento * 7 ?>">
				<div class="modal-body">
					<div class="">
						<label class="junta">Observação <i>(Opcional)</i></label>
						<textarea class="form-control m-b-5" id="textObs" maxlength="400"
							name="obs"
							placeholder="Você pode adicionar uma observação desse atendimento aqui..."
							rows="4" style="height: 110px;"></textarea>
						<span id="followSuccess" class="hide">
					<?php if($followiseConf->status){ ?>
						<label class="m-r-15 clear">Enviar conversa ao <b>Followise</b>?
						</label> <input value="1" type="checkbox" id="inCheckFollowize"
							name="followise">
					<?php } ?>
				</span> <span id="followError" class="hide"> <small>
						<?php if($followiseConf->status){ ?>
							<i class="text-danger">O cadastro desse cliente está incompleto,
									portanto não será possível enviar essa conversa ao <b>Followise</b>.
									O <b>nome</b> e o <b>e-mail</b> são obrigatórios.
							</i>
						<?php } ?>
					</small>
						</span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary"
						data-dismiss="modal" onclick="reload()">Voltar</button>
					<button type="submit" class="btn btn-info">Finalizar</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modalEstacionarAtendimento" tabindex="-1"
	role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Estacionar atendimento</h5>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close" onclick="reload()"
					style="margin-top: -20px; margin-right: -20px;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form class="enterness"
				action="../application/estacionar?hash=<?php echo $_GET['hash']; ?>&token=<?php echo $_GET['token']; ?>"
				method="post">
				<input type="hidden" name="crip"
					value="<?php echo $at->idAtendimento * 7 ?>">
				<div class="row modal-body">
					<div class="col-sm-8 form-group">
						<label class="junta">Lembrete para retorno de contato <i>(Opcional)</i></label>
						<input type="text" onkeyup="limpaInput()" id="dataInLembrete"
							value="" name="notificacao" class="form-control datepicker-here"
							data-language="pt" data-position="top center"
							placeholder="Defina a data da notificação" autocomplete="off">
					</div>
					<div class="col-sm-4 form-group clockpicker">
						<label class="control-label junta">Hora <i>(Opcional)</i></label>
						<input class="form-control " name="hora" id="inHora"
							placeholder="00:00" autocomplete="off">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary"
						data-dismiss="modal" onclick="reload()">Voltar</button>
					<button type="submit" class="btn btn-info">Estacionar</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modalTransfereAtendimento" tabindex="-1"
	role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Transferir
					atendimento</h5>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close" onclick="reload()"
					style="margin-top: -20px; margin-right: -20px;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form class="enterness" action="../application/transfereAtendimento"
				method="post">
				<input type="hidden" name="crip"
					value="<?php echo $at->idAtendimento * 7 ?>">
				<div class="modal-body">
					<div class="row">
						<div class="col-12 text-center">
							<p>Transferir para:</p>
							<input type="hidden" name="destino" id="inTranfDestino" value="">
							<button type="button" class="btn btn-sm btn-info btn-outline"
								id="modalBtnTransfAgente">Agente</button>
							<button type="button" class="btn btn-sm btn-info btn-outline"
								id="modalBtnTransfFila">Fila</button>
						</div>
					</div>
					<div class="form-group" id="divModalTranfAgente" hidden='true'>
						<label class="junta">Agente de destino:</label> <select
							class="form-control" name="agente">
				<?php

	foreach ( $agentes as $agt ) {
		?>
					<option value="<?php echo $agt['idUser'] ?>"><?php echo $agt['nome']." ".$agt['sobrenome'] . " (".expFila($agt['filas']).")" ?></option>
					<?php
	}
	?>
			</select> <i><b>Atenção!</b> O agente de destino verá todo o seu
							histórico de conversa com esse cliente.</i>
					</div>
					<div class="form-group" id="divModalTranfFila" hidden='true'>
						<label class="junta">Fila de destino:</label> <select
							class="form-control" name="fila">
				<?php

	foreach ( $filas as $fl ) {
		?>
					<option value="<?php echo $fl['idFila'] ?>"><?php echo $fl['nomeFila'] ?></option>
					<?php
	}
	?>
			</select> <i><b>Atenção!</b> O agente de destino verá todo o seu
							histórico de conversa com esse cliente.</i>
					</div>
					<hr>
					<div class="form-group">
						<label class="junta">Mensagem <i>(Opcional)</i></label>
						<textarea class="form-control" id="textObs" maxlength="400"
							name="obs"
							placeholder="Você pode adicionar uma observação desse atendimento aqui..."
							rows="4" style="height: 110px;"></textarea>
						<span class="help-block"> <small><i id="alertTextEmail">Apenas o
									agente de destino verá a mensagem inserida aqui.</i></small>
						</span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary"
						data-dismiss="modal" onclick="reload()">Voltar</button>
					<button type="submit" disabled class="btn btn-info btn-outline"
						id="btnSubmitFormTransf">Transferir</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php } ?>
<?php include 'inc/scripts.php'; ?>


<?php include '../application/atalhos.php'; ?>
<?php include 'inc/atalhos.php'; ?>

<script type="text/javascript">

<?php if($setted){ ?>
	function changeTo(plataforma){
		if(plataforma == 'whatsapp'){
			if($("#inFone").val() != ""){
				var title = "Deseja gerar um link de conversa no "+plataforma+"?";
				var text = "O sistema enviará uma URL para o cliente";
			} else {
				swal({
					title: "O WhatsApp do cliente não está cadastrado!",
					text: "Por favor, complete o cadastrodo cliente e tente novamente.",
					type: "error",
					showCancelButton: false,
					confirmButtonColor: "#f45b5b",
					confirmButtonText: "OK",
					closeOnConfirm: true
				});
				return false;
			}
		} else {
			return false;
		}

		swal({
			title: title,
			text: text,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#d4c496",
			confirmButtonText: "Sim",
			cancelButtonText: "Não",
			closeOnConfirm: true,
			closeOnCancel: true
		},
		function(isConfirm){
			if(isConfirm){
				if(plataforma == 'whatsapp'){
					var link = "<a href='https://api.whatsapp.com/send?phone=<?php echo $conf->phone ?>&text=Ol%C3%A1!%20Gostaria%20de%20dar%20continuidade%20ao%20atendimento%20n%C2%BA%20<?php echo $at->protocolo ?>%20por%20aqui.' target='_blank'>Continuar atendimento no WhatsApp</a>";
					$("#textareaChat").val(link);
					sendMessage();
				}
			}
		});
	}

	<?php } ?>


	$(document).ready(function() {


		$.switcher('#inCheckFollowize');

		$('.clockpicker').clockpicker({
			placement: 'top',
			autoclose: true
		});

		$('#popover-atalos').popover({
			title: "Atalhos:",
			content: function() {
				return $('#popover-content').html();
			},
			html: true,
			placement: "bottom",
			trigger: "hover"
		});

		$('#inCpf').mask('000.000.000-00');
		$('#inFone1').mask('(00) 00000-0000');
		$('#inFone2').mask('(00) 00000-0000');
		$('#inFone3').mask('(00) 00000-0000');
		$('#inCep').mask('00.000-000');
		$('#inNascimento').mask('00/00/0000');

		if($('#inNascimento').val() == "00/00/0000"){
			$('#inNascimento').val("");
		}
		if($('#inNumResi').val() == "0"){
			$('#inNumResi').val("")
		}
	});

	$(function(){
		$.switcher('#inPromocoes');
		$.switcher('#inClienteEmpresa');
	});

	$('#inPromocoes').click(function() {
		var promocao = $('#inPromocoes').val();

		if(promocao == 0){
			$('#inPromocoes').val(1);
			setCookies();
		} else {
			$('#inPromocoes').val(0);
			setCookies();
		}
	});

	$('#inClienteEmpresa').click(function() {
		var promocao = $('#inClienteEmpresa').val();

		if(promocao == 0){
			$('#inClienteEmpresa').val(1);
			setCookies();
		} else {
			$('#inClienteEmpresa').val(0);
			setCookies();
		}
	});

	$('#btnSalvar').click(function() {

    var choices = [];
    var els = document.getElementsByName('numAtivo');
    for (var i=0;i<els.length;i++){
      if ( els[i].checked ) {
        choices.push(els[i].value);
      }
    }

		$.ajax({
			type: "POST",
			url: "../application/editaCliente",
			data: {nome: $("#inNome").val(),
			fone1: $("#inFone1").val(),
			fone2: $("#inFone2").val(),
			fone3: $("#inFone3").val(),
			numAtivo: choices[0],
			email: $("#inEmail").val(),
			nascimento: $("#inNascimento").val(),
			cpf: $("#inCpf").val(),
			promocoes: $("#inPromocoes").val(),
			rua: $("#inRua").val(),
			bairro: $("#inBairro").val(),
			numResi: $("#inNumResi").val(),
			cep: $("#inCep").val(),
			estado: $("#estado").val(),
			complemento: $("#inComplemento").val(),
			cidade: $("#inCidade").val(),
			empresa: $("#inClienteEmpresa").val(),
			idCliente: $("#idCliente").val()},
			success: function(result){
				if(result == "true"){
					setToastInfo('MyOmni', 'Os dados do cliente foram atualizados!');
					document.cookie = 'atendimentos=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
				} else {
					setToastDanger('MyOmni', 'Os dados do cliente não foram atualizados!');
				}
			}
		});
	});

	var divCadastro = false;

	$(document).ready(function () {
		$("#botaoCadastro").click(function () {
			$("#content").toggleClass("col-12 col-7");

			if(divCadastro){
				divCadastro = false;
				$("#sidebar").hide();
			}else{
				setTimeout(function(){
					divCadastro = true;
					$("#sidebar").fadeIn();
				}, 500);
			}

			return false;
		});
	});



	//se tem atendimento setado, prepara a webcam
	<?php if($setted){ ?>
		var tmpStream;

		$("#startWebCam").click(function(){
			startAll();
			$("#fotoEnviada").hide();
		});

		$("#btnTirarOutra").click(function(){
			startAll();
		});

		function setMedia(video, s) {
			tmpStream = s;

			try {
				video.srcObject = s;
			} catch (error) {
				video.src = URL.createObjectURL(s);
			}
		}

		//função para iniciar a camera
		function startCamera(){
			navigator.mediaDevices.getUserMedia({
				video:{facingMode:'environment'},
				audio:false
			})
			.then((stream) => {
				setMedia(document.getElementById('video'), stream)
			});
			$("#btnEnviarFoto").prop("disabled", false);
			$("#pic").hide();
			$("#botoesEnviar").hide();
			$("#video").fadeIn();
			$("#botaoTirar").fadeIn();
		}
		//função para parar a camera
		function stopCamera(){
			$("#video").hide();
			$("#pic").fadeIn('fast');
			$("#botaoTirar").hide();
			$("#botoesEnviar").fadeIn();
			//Se não tiver stream definido ainda
			if (!tmpStream) return;
			tmpStream.getVideoTracks().forEach(track => track.stop())
		}


		//inicia os eventos
		//window.addEventListener("DOMContentLoaded", startAll)

		var formData = new FormData();

		function startAll(){
			//ligar a camera automaticamente
			startCamera();

			//função para tirar foto
			document.querySelector('#btnStart').addEventListener('click', event => {
				const canvas = document.getElementById('pic')
				const context = canvas.getContext('2d')
				const video = document.getElementById('video')
				//tamanho da foto mesmo tamanho do video
				canvas.width = video.offsetWidth
				canvas.height = video.offsetHeight
				//desenha o video no canvas
				context.drawImage(video, 0, 0, canvas.width, canvas.height)
				//metodo do canvas para pegar um objeto Blob
				canvas.toBlob(function(blob){
					const url = URL.createObjectURL(blob);
					stopCamera();
					$("#fotoEnviada").slideUp();
					formData.append('file', blob);
				}, 'image/jpeg', 0.95)
				//closeCamera()
			})
		}

		$("#btnEnviarFoto").click(function(){
			$(this).prop("disabled", true);
			$.ajax('../application/sendMedia?hash=<?php echo $at->idAtendimento * 11 ?>', {
				method: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function (response) {
					$("#fotoEnviada").slideDown();
					startCamera();
				}
			});
		});
		<?php } ?>

		var ancora = true;
		var focus = true;
		<?php if($setted){ ?>
			//Se está com uma conversa setada, força o scroll da conversa sempre para baixo
			$("#chatAtendimento").scrollTop($("#chatAtendimento")[0].scrollHeight);
			<?php } ?>

			$(document).ready(function(){

				$("#inCep").keyup(function(){
					var cep = $("#inCep").val();

					if(cep.length == 10){
						//Nova variável "cep" somente com dígitos.
						cep = $("#inCep").val().replace(/\D/g, '');

						//Verifica se campo cep possui valor informado.
						if (cep != "") {

							//Expressão regular para validar o CEP.
							var validacep = /^[0-9]{8}$/;

							//Valida o formato do CEP.
							if(validacep.test(cep)) {

								//Consulta o webservice viacep.com.br/
								$.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
									if (!("erro" in dados)) {
										//Atualiza os campos com os valores da consulta.
										$("#inRua").val(dados.logradouro);
										$("#inBairro").val(dados.bairro);
										$("#estado").val(dados.uf);
										$("#inCidade").val(dados.localidade);
									}
								});
							}
						}
					}
				});

				$('#dataInLembrete').datepicker({
					minDate: new Date()
				})

				$("#modalBtnTransfAgente").click(function (){
					$(this).addClass("active");
					$("#modalBtnTransfFila").removeClass("active");
					$("#divModalTranfFila").prop("hidden", true);
					$("#divModalTranfAgente").prop("hidden", false);
					$("#inTranfDestino").val("agente");
					$("#btnSubmitFormTransf").prop("disabled", false);
				});

				$("#modalBtnTransfFila").click(function (){
					$(this).addClass("active");
					$("#modalBtnTransfAgente").removeClass("active");
					$("#divModalTranfAgente").prop("hidden", true);
					$("#divModalTranfFila").prop("hidden", false);
					$("#inTranfDestino").val("fila");
					$("#btnSubmitFormTransf").prop("disabled", false);
				});



				$('.js-example-basic-single').select2();
				<?php if($setted){ ?>
					intervalAttMensagens();
					<?php } ?>
					attAtendimentos();
				});

				function openModal(){
					focus = false;
					$('#textObs').focus();
				}

				function attAtendimentos(){
					$.ajax({
						type: "POST",
						data: {
							hash : <?php if(isset($_GET['hash'])){ echo $_GET['hash']; } else { echo 0; } ?>
						},
						url: "../application/retListaAtendimentos",
						success: function(result){
							document.getElementById('listaAtendimentos').innerHTML = result;
						}
					});
					setTimeout(attAtendimentos, 11000);
				}

				<?php if($setted){ ?>

					function salvarAntes(){

						if (typeof $.cookie("atendimentos") !== 'undefined') {
							var cookies = JSON.parse($.cookie("atendimentos"));
						}else {
							var cookies = "";
						}
						$.ajax({
							type: "POST",
							data: {
								dados: cookies,
								id: '<?php echo $idCliente;?>'
							},
							url: "../application/verificaDadosCliente",
							success: function(result){
								if(result == "false"){
									swal({
										title: "Deseja salvar as alterações do cliente?",
										text: "Essa ação não poderá ser desfeita!",
										type: "warning",
										showCancelButton: true,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Sim, editar	!",
										cancelButtonText: "Cancelar",
										closeOnConfirm: true,
										closeOnCancel: true
									},
									function(isConfirm){
										if(isConfirm){
									    var choices = [];
									    var els = document.getElementsByName('numAtivo');
									    for (var i=0;i<els.length;i++){
									      if ( els[i].checked ) {
									        choices.push(els[i].value);
									      }
									    }

											$.ajax({
												type: "POST",
												url: "../application/editaCliente",
												data: {nome: $("#inNome").val(),
												fone1: $("#inFone1").val(),
												fone2: $("#inFone2").val(),
												fone3: $("#inFone3").val(),
												numAtivo: choices[0],
												email: $("#inEmail").val(),
												nascimento: $("#inNascimento").val(),
												cpf: $("#inCpf").val(),
												promocoes: $("#inPromocoes").val(),
												rua: $("#inRua").val(),
												bairro: $("#inBairro").val(),
												numResi: $("#inNumResi").val(),
												cep: $("#inCep").val(),
												estado: $("#estado").val(),
												complemento: $("#inComplemento").val(),
												cidade: $("#inCidade").val(),
												empresa: $("#inClienteEmpresa").val(),
												idCliente: $("#idCliente").val()},
												success: function(result){
													if(result == "true"){
														setToastInfo('MyOmni', 'Os dados do cliente foram atualizados!');
														$("#salvarAntes").click();
													} else {
														setToastDanger('MyOmni', 'Os dados do cliente não foram atualizados!');
													}
												}
											});
										}else{
											setSpanFollowise();
											$("#salvarAntes").click();
										}
									});
								}else{
									setSpanFollowise();
									$("#salvarAntes").click();
								}
							}
						});
					}

						$("#textareaChat").hover(function(){
							$(".botoes-textarea").fadeIn(1000);
							$(this).focusout(function(){
								$(".botoes-textarea").hide();
							});
						});

						$(".botoes-textarea").hover(function(){
							$(this).show();
						});

						function setSpanFollowise(){
							if($("#inNome").val() == "" || $("#inEmail").val() == "") {
								$("#followSuccess").hide();
								$("#followError").show();
							} else {
								$("#followError").hide();
								$("#followSuccess").show();
							}
						}


						function selecionaTexto(frm) {
							var add = setFrm(frm);
							var textArea = document.getElementById('textareaChat');
							var selectedText;
							var startPos = textArea.selectionStart; //Inicio da selecao
							var endPos   = textArea.selectionEnd;     //Fim da selecao
							selectedText = textArea.value.substring(startPos, endPos);

							if(selectedText != ""){
								var novoTexto = textArea.value.substring(0, startPos) + add + selectedText + add + textArea.value.substring(endPos);
								textArea.value = novoTexto;
							} else {
								if(textArea.value != ""){
									textArea.value = add + textArea.value + add;
								}
							}

						}

						function setFrm(frm){
							<?php

if ($at->plataforma == 'whatsapp') {
						?>
								if(frm == 'negrito'){
									return "*";
								} else if(frm == 'italico'){
									return "_";
								} else if(frm == 'riscado'){
									return "~";
								}
								<?php
					}
					?>
							return false;
						}

						var showingMessage = 0;
						var dataLast = 0;
						var messagesId = "-";
						var trava = false;

						function attMensagens(){
							$.ajax({
								type: "POST",
								data: {
									hash : '<?php echo $at->idAtendimento * 53 ?>',
									token : '<?php echo $_GET['token'] ?>',
									last : showingMessage,
									dataLast : dataLast
								},
								url: "../application/chatAtendimento",
								success: function(result){
									if(result == 'transbordo' || result == 'unlogged'){
										window.location.href = "media";
									} else {
										result = JSON.parse(result);

										var i = 0;
										for(i = 0; i<result.length; i++) {
											if(messagesId.indexOf("-"+result[i]['idMessage']+"-") < 0){
												showingMessage = result[i]['idMessage'];
												dataLast = result[i]['dataMessage'];
												$("#chatAtendimento").append(result[i]['bodyMessage']);
												$("#idMsg"+result[i]['idMessage']).fadeIn('slow');
												messagesId = messagesId + result[i]['idMessage']+"-";
											}
										}
										if(i > 0 && ancora == true){
											var timeTo = 0;
											if(i < 10){
												timeTo = 1000;
											}
											var heightTo = $("#chatAtendimento")[0].scrollHeight;
											$('#chatAtendimento').animate({
												scrollTop: heightTo
											}, timeTo);
											//$("#chatAtendimento").scrollTop($("#chatAtendimento")[0].scrollHeight);
										}

										if(!trava){
											$.ajax({
												url: "../application/checaNewMessageAtendimento",
												success: function(result){
													result = result.split("-");
													if(result[0] != 'true'){
														$('#notifyNewMessageAtendimento').hide();
													}
												}
											});
											trava = true;
										}
									}
								}
							});
							/*if(focus){
							$('#textareaChat').focus();
						}*/
						//alert($("#chatAtendimento")[0].scrollTop);
					}

					function intervalAttMensagens(){
						attMensagens();
						setTimeout(intervalAttMensagens, 3000);
					}

					function ativaAncora(){
						ancora = true;
						$('#chatAtendimento').animate({
							scrollTop: $("#chatAtendimento")[0].scrollHeight
						}, 1000);
					}

					setTimeout(ativaAncora, 500);

					function desativaAncora(){
						ancora = false;
					}

					function reload(){
						window.location.href="media?hash=<?php echo $_GET['hash'] ?>&token=<?php echo $_GET['token']?>";
					}

					function sendMessage(){
						var msg = document.getElementById('textareaChat').value;

						if(msg != ""){
							document.getElementById('textareaChat').value = "";

							//palavra inapropriada
							palavrasRestringidas = validaTexto(msg);
							var restricted = 0;

							if(palavrasRestringidas){

								setToastDanger('Alerta!', 'Você usou palavras inapropriadas. Seu supervisor foi notificado!');

								$.ajax({
									type: "POST",
									data: {
										idAtendimento: <?php echo $at->idAtendimento; ?>,
										palavras : palavrasRestringidas
									},
									url: "../application/notificaRestricao.php"
								});

								msg = "<i class='text-danger'>"+msg+"</i>";
								restricted = 1;
							}

							$.ajax({
								type: "POST",
								data: {
									hash : '<?php echo $at->idAtendimento * 53 ?>',
									plataforma : '<?php echo $at->plataforma ?>',
									token : '<?php echo $_GET['token'] ?>',
									dst : '<?php echo $at->remetente ?>',
									msg : msg,
									rst : restricted
								},
								url: "../application/sendMessageAtendimento",
								success: function(result){
									attMensagens();
								}
							});

						} else {
							$('#textareaChat').focus();
						}
					}

					$('#textareaChat').click(ativaAncora);

					function validaTexto(texto){

						var palavrasEncontradas = "";
						var listaPalavroes = new Array(<?php
					$palavras = "";
					foreach ( $listaPalavras as $palavra ) {
						if ($palavras == "") {
							$palavras = "'" . $palavra ["palavra"] . "'";
						} else {
							$palavras = $palavras . ", '" . $palavra ["palavra"] . "'";
						}
					}
					echo $palavras;
					?>);

						var resposta = "";

						listaPalavroes.forEach(function(palavra){

							var comEspaco = palavra.indexOf(" ");

							if(comEspaco >= 0){
								var pos = texto.toLowerCase().indexOf(palavra);
								if (pos >= 0) {
									if(palavrasEncontradas == ""){
										palavrasEncontradas = palavra;
									} else {
										palavrasEncontradas = palavrasEncontradas + ", " + palavra;
									}
								}
							}	else {

								var textoComEspaco = texto.indexOf(" ");

								if(textoComEspaco >= 0){
									if(resposta != ""){
										texto = resposta;
									}
									resposta = "";

									var palavrasTexto = texto.split(" ");

									palavrasTexto.forEach(function(palavraTexto){
										if(palavraTexto.toLowerCase() == palavra){
											pos = palavrasEncontradas.indexOf(palavra);

											if (pos == -1) {
												if(palavrasEncontradas == ""){
													palavrasEncontradas = palavra;
												} else {
													palavrasEncontradas = palavrasEncontradas + ", " + palavra;
												}
											}

										} else {
											if(resposta == ""){
												resposta = palavraTexto;
											} else {
												resposta = resposta + " " + palavraTexto;
											}
										}
									});
								} else {
									if (texto.toLowerCase() == palavra) {
										palavrasEncontradas = palavra;
									}
								}
							}
						});

						if(palavrasEncontradas == ""){
							return false;
						}

						return palavrasEncontradas;
					}

					function stopEvent(event) {
						if (event.preventDefault) {
							event.preventDefault();
							event.stopPropagation();
						} else {
							event.returnValue = false;
							event.cancelBubble = true;
						}
					}

					function areaEnvia(obj, evt) {
						var e = evt || event;
						var k = e.keyCode;
						if(k == 13) { //verifica se teclou enter
							if(!e.shiftKey) {
								if(obj.form){
									sendMessage();
								}
								stopEvent(e);
							}
						}
					}

					<?php } ?>
					<?php

					if (isset ( $_GET ['transferencia'] ) && $_GET ['transferencia'] == 'unavailable') {
						?>
						swal("Opa!", "Agente de destino indisponível!", "error");
						<?php
					}
					?>
					<?php

					if (isset ( $_GET ['transferencia'] ) && $_GET ['transferencia'] == 'success') {
						?>
						swal("Feito!", "O atendimento foi transferido!", "success");
						<?php
					}
					?>
					<?php

					if (isset ( $_GET ['transferencia'] ) && $_GET ['transferencia'] == 'falha') {
						?>
						swal("Opa!", "Não conseguimos transferir o atendimento!", "error");
						<?php
					}
					?>
					<?php

					if (isset ( $_GET ['finish'] ) && $_GET ['finish'] == 'success') {
						?>
						swal("Feito!", "O atendimento foi finalizado!", "success");
						<?php
					} else if (isset ( $_GET ['finish'] ) && $_GET ['finish'] == 'failure') {
						?>
						swal("Ops...", "Houve um problema ao finalizar esse atendimento!", "error");
						<?php
					} else if (isset ( $_GET ['estacionar'] ) && $_GET ['estacionar'] == 'success') {
						?>
						swal("Feito!", "O cliente foi estacionado!", "success");
						<?php
					} else if (isset ( $_GET ['estacionar'] ) && $_GET ['estacionar'] == 'failure') {
						?>
						swal("Ops...", "Houve um problema ao estacionar esse cliente!", "error");
						<?php
					}
					?>

					function setCookies(){
						<?php if ($setted) {?>
						  var choices = [];
					    var els = document.getElementsByName('numAtivo');
					    for (var i=0;i<els.length;i++){
					      if ( els[i].checked ) {
					        choices.push(els[i].value);
					      }
					    }
							var cookie = new Array(
								$("#idCliente").val(),
								$("#inNome").val(),
								$("#inNascimento").val(),
								$("#inEmail").val(),
								$("#inFone1").val(),
								$("#inFone2").val(),
								$("#inFone3").val(),
								choices[0],
								$("#inCpf").val(),
								$("#inPromocoes").val(),
								$("#inRua").val(),
								$("#inBairro").val(),
								$("#inNumResi").val(),
								$("#inCep").val(),
								$("#estado").val(),
								$("#inComplemento").val(),
								$("#inCidade").val(),
								$("#inClienteEmpresa").val()
							);

							if($.cookie("atendimentos")){

								var cookies = JSON.parse($.cookie("atendimentos"));

								if(Array.isArray(cookies[0])){
									var trava = false;
									var index = 0;

									cookies.forEach(function(cookieEach){
										if($("#idCliente").val() == cookieEach[0]){
											cookies[index] = cookie;
											trava = true;
										}
										index++;
									});

									if(!trava){
										cookies[cookies.length] = cookie;
									}
								}else{
									if($("#idCliente").val() == cookies[0]){
										cookies = cookie;
									} else {
										cookies = [cookies, cookie];
									}
								}
							} else {
								cookies = cookie;
							}
							$.cookie("atendimentos", JSON.stringify(cookies));//, { expires: 1 } -> para um dia
							<?php }?>
						}

						$(document).ready(function(){
							<?php if ($setted) {?>
								if($.cookie("atendimentos")){
									var cookies = JSON.parse($.cookie("atendimentos"));

									if(Array.isArray(cookies[0])){
										cookies.forEach(function(cookieEach){
											if($("#idCliente").val() == cookieEach[0]){
												$("#inNome").val(cookieEach[1]);
												$("#inNascimento").val(cookieEach[2]);
												$("#inEmail").val(cookieEach[3]);
												$("#inFone1").val(cookieEach[4]);
												$("#inFone2").val(cookieEach[5]);
												$("#inFone3").val(cookieEach[6]);
												if(cookieEach[7] == 1){
												    $('#at1').attr('checked', true);
												    $('#at2').attr('checked', false);
												    $('#at3').attr('checked', false);
												}else if(cookieEach[7] == 2){
												    $('#at1').attr('checked', false);
												    $('#at2').attr('checked', true);
												    $('#at3').attr('checked', false);
												}else if(cookieEach[7] == 3){
												    $('#at1').attr('checked', false);
												    $('#at2').attr('checked', false);
												    $('#at3').attr('checked', true);
												}
												$("#inCpf").val(cookieEach[8]);
												$("#inPromocoes").val(cookieEach[9]);
												$("#inRua").val(cookieEach[10]);
												$("#inBairro").val(cookieEach[11]);
												$("#inNumResi").val(cookieEach[12]);
												$("#inCep").val(cookieEach[13]);
												$("#estado").val(cookieEach[14]);
												$("#inComplemento").val(cookieEach[15]);
												$("#inCidade").val(cookieEach[16]);
												$("#inClienteEmpresa").val(cookieEach[17]);
											}
										});
									} else {
										if($("#idCliente").val() == cookies[0]){
											$("#inNome").val(cookies[1]);
											$("#inNascimento").val(cookies[2]);
											$("#inEmail").val(cookies[3]);
											$("#inFone1").val(cookies[4]);
											$("#inFone2").val(cookies[5]);
											$("#inFone3").val(cookies[6]);
											if(cookies[7] == 1){
											    $('#at1').attr('checked', true);
											    $('#at2').attr('checked', false);
											    $('#at3').attr('checked', false);
											}else if(cookies[7] == 2){
											    $('#at1').attr('checked', false);
											    $('#at2').attr('checked', true);
											    $('#at3').attr('checked', false);
											}else if(cookies[7] == 3){
											    $('#at1').attr('checked', false);
											    $('#at2').attr('checked', false);
											    $('#at3').attr('checked', true);
											}
											$("#inCpf").val(cookies[8]);
											$("#inPromocoes").val(cookies[9]);
											$("#inRua").val(cookies[10]);
											$("#inBairro").val(cookies[11]);
											$("#inNumResi").val(cookies[12]);
											$("#inCep").val(cookies[13]);
											$("#estado").val(cookies[14]);
											$("#inComplemento").val(cookies[15]);
											$("#inCidade").val(cookies[16]);
											$("#inClienteEmpresa").val(cookies[17]);
										}
									}
								}
								<?php }?>

								$("#inNome").blur(function(){
									setCookies();
								});

								$("#inFone1").blur(function(){
									setCookies();
								});

								$("#inFone2").blur(function(){
									setCookies();
								});

								$("#inFone3").blur(function(){
									setCookies();
								});

								$("#numAtivo").blur(function(){
									setCookies();
								});

								$("#inEmail").blur(function(){
									setCookies();
								});

								$("#inNascimento").blur(function(){
									setCookies();
								});

								$("#inCpf").blur(function(){
									setCookies();
								});

								$("#inRua").blur(function(){
									setCookies();
								});

								$("#inBairro").blur(function(){
									setCookies();
								});

								$("#inNumResi").blur(function(){
									setCookies();
								});

								$("#inCep").blur(function(){
									setCookies();
								});

								$("#estado").blur(function(){
									setCookies();
								});

								$("#inComplemento").blur(function(){
									setCookies();
								});

								$("#inCidade").blur(function(){
									setCookies();
								});

							});

							</script>
</body>

</html>
