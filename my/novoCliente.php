<?php
include '../application/novoCliente.php';
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
				<h3 class="padrao">Novo cliente</h3>
			</div>
			<div class="col-md-7 align-self-center">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
					<li class="breadcrumb-item"><a href="clientes">Clientes</a></li>
					<li class="breadcrumb-item">Novo cliente</li>
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
						<div class="card-body bg-white">
              <div class="row">
								<div class="col-12">
									<div class="jumbotron j-relative jumboReportRegra">
										<h1 class="center">Novo cadastro</h1>
										<form class="form-horizontal enterness" action="../application/novoCliente" method="POST">
											<div class="card">
												<div class="card-body">
		                      <h4 class="card-title">
		                      	Informações pessoais
		                      </h4>
		                      <hr>
		                      <div class="row">
		                      	<div class="col-3">
				                      <div class="form-group">
		                            <label class="control-label junta">Nome completo</label>
	                              <input type="text" class="form-control" name="nome" id="inNome" placeholder="Nome" required="required">
		                          </div>
		                      	</div>
		                      	<div class="col-3 form-group">
	                            <label class="control-label junta">Telefone</label>
		                      		<div class="input-group">
		                            <input type="tel" 
		                            			 class="form-control" 
		                            			 name="fone1" 
		                            			 id="inFone1" 
		                            			 placeholder="Telefone" 
		                            			 autocomplete="none"
		                            			 aria-haspopup="true" 
		                            			 aria-expanded="false"
		                            			 data-toggle="dropdown"/>
		                          	<div class="input-group-prepend">
															    <div class="input-group-text">
															      <input type="radio" name="numAtivo" value="1" aria-label="Radio button for following text input" checked>
															    </div>
															  </div>
	                            </div>
		                      	</div>
		                      	<div class="col-3 form-group">
	                            <label class="control-label junta">Telefone</label>
		                      		<div class="input-group">
		                            <input type="tel" 
		                            			 class="form-control" 
		                            			 name="fone2" 
		                            			 id="inFone2" 
		                            			 placeholder="Telefone" 
		                            			 autocomplete="none"
		                            			 aria-haspopup="true" 
		                            			 aria-expanded="false"
		                            			 data-toggle="dropdown"/>
		                          	<div class="input-group-prepend">
															    <div class="input-group-text">
															      <input type="radio" name="numAtivo" value="2" aria-label="Radio button for following text input">
															    </div>
															  </div>
	                            </div>
		                      	</div>
		                      	<div class="col-3 form-group">
	                            <label class="control-label junta">Telefone</label>
		                      		<div class="input-group">
		                            <input type="tel" 
		                            			 class="form-control" 
		                            			 name="fone3" 
		                            			 id="inFone3" 
		                            			 placeholder="Telefone" 
		                            			 autocomplete="none"
		                            			 aria-haspopup="true" 
		                            			 aria-expanded="false"
		                            			 data-toggle="dropdown"/>
		                          	<div class="input-group-prepend">
															    <div class="input-group-text">
															      <input type="radio" name="numAtivo" value="3" aria-label="Radio button for following text input">
															    </div>
															  </div>
	                            </div>
		                      	</div>
		                      </div>
	                      	<div class="row">
	                      		<div class="col-3">
		                      		<div class="form-group">
		                            <label class="control-label junta">Data de nascimento</label>
	                              <input type="text" class="form-control datepicker-here" data-language='pt' name="nascimento" id="inNascimento" placeholder="Data de nascimento">
		                          </div>
		                      	</div>
		                      	<div class="col-3">
				                      <div class="form-group">
		                            <label class="control-label junta">Email</label>
	                              <input type="email" class="form-control" name="email" id="inEmail" placeholder="Email">
		                          </div>
		                      	</div>
		                      	<div class="col-3">
		                      		<div class="form-group">
		                            <label class="control-label junta">CPF</label>
		                            <input type="text" class="form-control cpf" name="cpf" id="inCpf" placeholder="CPF">
		                          </div>
		                      	</div>
		                      	<div class="col-3">
		                      		<div class="form-group center">
		                            <label class="control-label junta">Aceita receber material promocional</label>
	                            	<div class="p-t-10">
																	<input type="checkbox" name="promocoes" id="inPromocoes" value="0">
																</div>
															</div>
		                      	</div>
		                      </div>
		                      <div class="row">
		                      	<div class=""></div>
		                      </div>
	                      </div>
											</div>
											<div class="card">
												<div class="card-body">
	                      	<h4 class="card-title">
	                        	Endereço
	                        </h4>
	                        <hr>
	                        <div class="row">
		                      	<div class="col-6">
				                      <div class="form-group">
		                            <label class="control-label junta">Rua</label>
	                              <input type="text" class="form-control" name="rua" id="inRua" placeholder="Rua">
		                          </div>
		                      	</div>
		                      	<div class="col-2">
		                      		<div class="form-group">
		                            <label class="control-label junta">Nº</label>
	                              <input type="number" class="form-control" name="numResi" id="inNumResi" placeholder="Número">
		                          </div>
		                      	</div>
		                      	<div class="col-4">
		                      		<div class="form-group">
		                            <label class=" control-label junta">Bairro</label>
	                              <input type="text" class="form-control" name="bairro" id="inBairro" placeholder="Bairro">
		                          </div>
		                      	</div>
		                      </div>
	                        <div class="row">
		                      	<div class="col-2">
				                      <div class="form-group">
		                            <label class="control-label junta">CEP</label>
	                              <input type="text" class="form-control" name="cep" id="inCep" placeholder="CEP">
		                          </div>
		                      	</div>
		                      	<div class="col-2">
		                      		<div class="form-group">
		                            <label class="control-label junta">Estado</label>
	                              <select class="form-control" id="estado" name="estado">
															    <option value="UF">Selecione</option>
															    <option disabled>──────────</option>
															    <option value="AC">Acre</option>
															    <option value="AL">Alagoas</option>
															    <option value="AP">Amapá</option>
															    <option value="AM">Amazonas</option>
															    <option value="BA">Bahia</option>
															    <option value="CE">Ceará</option>
															    <option value="DF">Distrito Federal</option>
															    <option value="ES">Espírito Santo</option>
															    <option value="GO">Goiás</option>
															    <option value="MA">Maranhão</option>
															    <option value="MT">Mato Grosso</option>
															    <option value="MS">Mato Grosso do Sul</option>
															    <option value="MG">Minas Gerais</option>
															    <option value="PA">Pará</option>
															    <option value="PB">Paraíba</option>
															    <option value="PR">Paraná</option>
															    <option value="PE">Pernambuco</option>
															    <option value="PI">Piauí</option>
															    <option value="RJ">Rio de Janeiro</option>
															    <option value="RN">Rio Grande do Norte</option>
															    <option value="RS">Rio Grande do Sul</option>
															    <option value="RO">Rondônia</option>
															    <option value="RR">Roraima</option>
															    <option value="SC">Santa Catarina</option>
															    <option value="SP">São Paulo</option>
															    <option value="SE">Sergipe</option>
															    <option value="TO">Tocantins</option>
																</select>
	                            </div>
		                      	</div>
		                      	<div class="col-4">
		                      		<div class="form-group">
		                            <label class="control-label junta">Complemento</label>
	                              <input type="text" class="form-control" name="complemento" id="inComplemento" placeholder="Complemento">
		                          </div>
		                      	</div>
		                      	<div class="col-4">
		                      		<div class="form-group">
		                            <label class="control-label junta">Cidade</label>
	                              <input type="text" class="form-control" name="cidade" id="inCidade" placeholder="Cidade">
		                          </div>
		                      	</div>
		                      </div>
		                      <div class="row">
		                      	<div class="col-3">
		                      		<div class="form-group center">
		                            <label class="control-label junta">Esse cliente representa uma empresa</label>
	                              <div class="p-t-10">
		                              <input type="checkbox" value="0" name="clienteEmpresa" id="inClienteEmpresa"/>
	                              </div>
		                          </div>
		                      	</div>
		                      </div>
	                      </div>
											</div>
											<div class="pull-right">
												<a href="clientes">
	                        <button type="button" class="btn btn-secondary">Voltar</button>
	                      </a>
												<button type="submit" class="btn btn-info">Salvar</button>
											</div>
              			</form>
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
<?php include 'inc/scripts.php'; ?>

<script type="text/javascript">

$(function(){
  $.switcher('#inPromocoes');
  $.switcher('#inClienteEmpresa');
});

$('#inPromocoes').click(function() {
	var promocao = $('#inPromocoes').val();

	if(promocao == 0){
		$('#inPromocoes').val(1);
	} else {
		$('#inPromocoes').val(0);
	}
});

$('#inClienteEmpresa').click(function() {
	var promocao = $('#inClienteEmpresa').val();

	if(promocao == 0){
		$('#inClienteEmpresa').val(1);
	} else {
		$('#inClienteEmpresa').val(0);
	}
});

$(document).ready(function() {
	$('#inCpf').mask('000.000.000-00');
	$('#inFone1').mask('(00) 00000-0000');
	$('#inFone2').mask('(00) 00000-0000');
	$('#inFone3').mask('(00) 00000-0000');
	$('#inCep').mask('00.000-00');
  $('#tabelaAgt').DataTable({});
});

</script>

</body>

</html>