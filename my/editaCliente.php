<?php
include '../application/editaCliente.php';
//Define nível de restrição da página
$allowUser = array('dev', 'coordenador', 'administrador', 'supervisor', 'agente');
checaPermissao($allowUser);

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
        <h3 class="padrao">Informações sobre o cliente</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="clientes">Clientes</a></li>
          <li class="breadcrumb-item">Informações sobre o cliente</li>
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
              <div class="row p-10 m-l-25">
                <div class="col-1">
                  <div class="avatar-chat foto-cliente">
                    <?php if($cliente['foto'] != "" && $cliente['foto'] != NULL){
                      ?>
                      <img src="assets/medias/clients/<?php echo $cliente['foto'] ?>">
                      <?php
                    } else {
                      ?>
                      <img src="assets/avatar/default.jpg" alt="Foto do cliente">
                      <?php
                    } ?>
                  </div>
                </div>
                <div class="col-7 p-10">
                  <h1 class="p-l-30">
                    <?php echo $cliente["nome"];?>
                  </h1>
                  <samp class="p-l-30">Registrado em <?php echo dataBdParaDataMesText($cliente["dtRegistro"]);?></samp>
                </div>
                <?php if ($_SESSION['tipo'] == 'agente') {
                  ?>
                  <div class="col-4" style="">
                    <div class="pull-right">
                      <span class="ativos-icones">
                        <a href="../application/startCall?hash=<?php echo $cliente['idCliente'] * 31; ?>&plataforma=whatsapp">
                          <img class="icone op-40" src="assets/icons/social/whatsapp.png" alt="whatsapp">
                        </a>
                      </span>
                    </div>
                  </div>
                  <?php
                } ?>
              </div>
              <hr>
              <div class="row">
                <div class="col-12">
                  <div class="jumbotron j-relative jumboReportRegra">
                    <form class="form-horizontal enterness"
                    action="../application/editaCliente?hash=<?php echo $_GET["hash"];?>"
                    method="POST" name="novoCliente">
                    <?php if ($_SESSION['tipo'] != 'agente') { ?>
                      <div class="btn-trash">
                        <button type="button" onclick="setTrash()" class="btn btn-sm btn-danger btn-outline"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                      </div>
                    <?php } ?>
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Informações pessoais</h4>
                        <hr>
                        <div class="row">
                          <div class="col-3">
                            <div class="form-group">
                              <label class="control-label junta">Nome completo</label>
                              <div class="">
                                <input value="<?php echo $cliente["nome"];?>"
                                type="text" class="form-control" name="nome"
                                id="inNome" placeholder="Nome">
                              </div>
                            </div>
                          </div>
                          <div class="col-3 form-group">
	                            <label class="control-label junta">Telefone</label>
		                      		<div class="input-group">
		                            <input type="tel" 
		                            			 value="<?php echo $fones[0][1];?>"
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
															      <input type="radio" 
															      			 name="numAtivo" 
															      			 value="1" 
															      			 aria-label="Radio button for following text input" 
															      			 <?php if($fones[0][0]){?>checked<?php }?>>
															    </div>
															  </div>
	                            </div>
		                      	</div>
	                      	<div class="col-3 form-group">
                            <label class="control-label junta">Telefone</label>
	                      		<div class="input-group">
	                            <input type="tel" 
	                            			 value="<?php echo $fones[1][1];?>"
	                            			 class="form-control" 
	                            			 name="fone2" 
	                            			 id="inFone2" 
	                            			 placeholder="Telefone" 
	                            			 autocomplete="none"
	                            			 aria-haspopup="true" 
	                            			 aria-expanded="false"
	                            			 data-toggle="dropdown">
	                          	<div class="input-group-prepend">
														    <div class="input-group-text">
														      <input type="radio" 
														      			 name="numAtivo" 
														      			 value="2" 
														      			 aria-label="Radio button for following text input"
	                            			 		 <?php if($fones[1][0]){?>checked<?php }?>>
														    </div>
														  </div>
                            </div>
	                      	</div>
	                      	<div class="col-3 form-group">
	                            <label class="control-label junta">Telefone</label>
		                      		<div class="input-group">
		                            <input type="tel" 
		                            			 value="<?php echo $fones[2][1];?>"
		                            			 class="form-control" 
		                            			 name="fone3" 
		                            			 id="inFone3" 
		                            			 placeholder="Telefone" 
		                            			 autocomplete="none"
		                            			 aria-haspopup="true" 
		                            			 aria-expanded="false"
		                            			 data-toggle="dropdown">
		                          	<div class="input-group-prepend">
															    <div class="input-group-text">
															      <input type="radio" 
															      			 name="numAtivo" 
															      			 value="3" 
															      			 aria-label="Radio button for following text input"
		                            			 		 <?php if($fones[2][0]){?>checked<?php }?>>
															    </div>
															  </div>
	                            </div>
		                      	</div>
                        </div>
                        <div class="row">
                        	<div class="col-3">
                            <div class="form-group">
                              <label class="control-label junta">Data de nascimento</label>
                              <div class="">
                                <input value="<?php echo dataBdParaHtml($cliente["nascimento"]);?>" type="text" class="form-control datepicker-here" data-language='pt' name="nascimento" id="inNascimento" placeholder="Data de nascimento">
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="form-group">
                              <label class="control-label junta">Email</label>
                              <div class="">
                                <input value="<?php echo $cliente["email"];?>"
                                type="email" class="form-control" name="email"
                                id="inEmail" placeholder="Email">
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="form-group">
                              <label class="control-label junta">CPF</label>
                              <div class="">
                                <input value="<?php echo $cliente["cpf"];?>"
                                type="text" class="form-control" name="cpf" id="inCpf"
                                placeholder="CPF">
                              </div>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="form-group center">
                              <label class="control-label junta">Aceita receber
                                material promocional</label>
                                <div class="p-t-10">
                                  <div class="">
                                    <input value="<?php echo $cliente["promocoes"];?>"
                                    type="checkbox" class="form-control" name="promocoes"
                                    id="inPromocoes"
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
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">Endereço</h4>
                            <hr>
                            <div class="row">
                              <div class="col-6">
                                <div class="form-group">
                                  <label class="control-label junta">Rua</label>
                                  <div class="">
                                    <input value="<?php echo $cliente["rua"];?>"
                                    type="text" class="form-control" name="rua" id="inRua"
                                    placeholder="Rua">
                                  </div>
                                </div>
                              </div>
                              <div class="col-2">
                                <div class="form-group">
                                  <label class="control-label junta">Nº</label>
                                  <div class="">
                                    <input value="<?php echo $cliente["numero"];?>"
                                    type="number" class="form-control" name="numResi"
                                    id="inNumResi" placeholder="Número">
                                  </div>
                                </div>
                              </div>
                              <div class="col-4">
                                <div class="form-group">
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
                              <div class="col-2">
                                <div class="form-group">
                                  <label class="control-label junta">CEP</label>
                                  <div class="">
                                    <input value="<?php echo $cliente["cep"];?>"
                                    type="text" class="form-control" name="cep" id="inCep"
                                    placeholder="CEP">
                                  </div>
                                </div>
                              </div>
                              <div class="col-2">
                                <div class="form-group">
                                  <label class="control-label junta">Estado</label> <select
                                  class="form-control" id="estado" name="estado">
                                  <option <?php if($cliente["uf"] == "UF"){?> selected
                                  <?php }?> value="UF">Selecione</option>
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
                            <div class="col-4">
                              <div class="form-group">
                                <label class="control-label junta">Complemento</label>
                                <div class="">
                                  <input value="<?php echo $cliente["complemento"];?>"
                                  type="text" class="form-control" name="complemento"
                                  id="inComplemento" placeholder="complemento">
                                </div>
                              </div>
                            </div>
                            <div class="col-4">
                              <div class="form-group">
                                <label class="control-label junta">Cidade</label> <input
                                value="<?php echo $cliente["cidade"];?>" type="text"
                                class="form-control" name="cidade" id="inCidade"
                                placeholder="Cidade">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-3">
                              <div class="form-group center">
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
                          <div class="pull-right">
                            <a href="clientes">
                              <button type="button" class="btn btn-secondary">Voltar</button>
                            </a>
                            <button type="button" id="idSalvar" class="btn btn-info">Salvar</button>
                          </div>
                        </form>
                        <form action="../application/editaCliente?hash=<?php echo $_GET["hash"];?>&delete=true" name="deleteCliente" hidden="true" method="post"></form>
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

    function setTrash(){
      swal({
        title: "Deseja mesmo excluir esse cliente?",
        text: "Essa ação não poderá ser desfeita!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar",
        closeOnConfirm: true,
        closeOnCancel: true
      },
      function(isConfirm){
        if(isConfirm){
          document.deleteCliente.submit();
        }
      });
    }

    $(function(){
      $.switcher('#inPromocoes');
      $.switcher('#inClienteEmpresa');
    });

    $("#idSalvar").on('click', function () {
      swal({
        title: "Deseja salvar as modificações desse cliente?",
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
          document.novoCliente.submit();
        }
      });
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
        $('#inPromocoes').val(0);
      }
    });

    $(document).ready(function() {
      $('#inCpf').mask('000.000.000-00');
      $('#inFone1').mask('(00) 00000-0000');
      $('#inFone2').mask('(00) 00000-0000');
      $('#inFone3').mask('(00) 00000-0000');
      $('#inCep').mask('00.000-000');
      $('#inNascimento').mask('00/00/0000');
      $('#tabelaAgt').DataTable({});

      if($('#inNascimento').val() == "00/00/0000"){
        $('#inNascimento').val("");
      }
      if($('#inNumResi').val() == "0"){
        $('#inNumResi').val("")
      }
    });

    <?php
    if(isset($_GET['atualizacao']) && $_GET['atualizacao'] == 'success'){ ?>
      swal("Salvo!", "O cliente foi editado!", "success");
      <?php
    } else if(isset($_GET['failure']) && $_GET['failure'] == 'emAtendimento'){ ?>
      swal("Opa!", "Esse cliente está sendo atendido por outro agente", "error");
      <?php
    } else if(isset($_GET['failure']) && $_GET['failure'] == 'paramLost'){ ?>
      swal("Opa!", "Não foi possivel iniciar o atendimento", "error");
      <?php
    } else if(isset($_GET['failure']) && $_GET['failure'] == 'phoneLost'){ ?>
      swal("Opa!", "Esse cliente não possui um número de WhatsApp cadastrado", "error");
      <?php
    } else if(isset($_GET['failure']) && $_GET['failure'] == 'platformLost'){ ?>
      swal("Opa!", "Não foi possivel iniciar o atendimento", "error");
      <?php
    } else if(isset($_GET['failure']) && $_GET['failure'] == 'numberInactive'){ ?>
      swal("Opa!", "Nenhum numero setado como ativo.", "error");
      <?php
    } else if(isset($_GET['delete']) && $_GET['delete'] == 'failure'){ ?>
      swal("Opa!", "O cliente possui um atendimento aberto!", "error");
      <?php
    } ?>

    </script>

  </body>

  </html>
