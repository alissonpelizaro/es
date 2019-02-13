<?php
include '../application/setupcasa.php';
//Define nível de restrição da página
$allowUser = array('dev', 'gestor');
checaPermissao($allowUser);

include 'inc/head.php';
?>
<!-- Main wrapper  -->
<div id="main-wrapper">
  <!-- Page wrapper  -->
  <div class="pae-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="padrao">Cadastro da concessionária (<?php echo $casa['nome'] ?>)</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Minha concessionária</li>
        </ol>
      </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
      <!-- Start Page Content -->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-title">
              <p>Por favor, complete o cadastro da sua concessionária para continuar utilizando esse sistema:</p>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormCasa" action="../application/editacasa" method="post">
                  <input type="hidden" name="hash" value="<?php echo $idCasa*17; ?>">
                  <div class="row">
                    <div class="col-12">
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome da concessionária</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="nome" value="<?php echo $casa['nome'] ?>" id="inNome" required placeholder="Nome da casa">
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome do responsável</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="responsavel" value="<?php echo $casa['responsavel'] ?>" id="inResponsavel" required placeholder="Responsável">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
	                      <div class="col-3">
		                      <div class="form-group">
		                        <label class="col-sm-12 control-label junta">CEP</label>
		                        <div class="col-sm-12">
		                          <input type="text" class="form-control" name="cep" id="inCep" value="<?php echo "" ?>" required placeholder="CEP">
		                        </div>
		                      </div>
	                      </div>
	                      <div class="col-7">
		                      <div class="form-group">
		                        <label class="col-sm-12 control-label junta">Endereço completo</label>
		                        <div class="col-sm-12">
		                          <input type="text" class="form-control" name="endereco" id="inEndereco" value="<?php echo $casa['endereco'] ?>" required placeholder="Endereço completo e número">
		                        </div>
		                      </div>
	                      </div>
	                      <div class="col-2">
		                      <div class="form-group">
		                        <label class="col-sm-12 control-label junta">Número</label>
		                        <div class="col-sm-12">
		                          <input type="number" class="form-control" name="numResi" id="inNumResi" value="<?php echo "" ?>" required placeholder="Nº">
		                        </div>
		                      </div>
	                      </div>
                      </div>
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Complemento</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="complemento" value="<?php echo ""?>" id="inComplemento" required placeholder="Complemento">
                            </div>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Bairro</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="bairro" value="<?php echo $casa['bairro'] ?>" id="inBairro" required placeholder="Bairro">
                            </div>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Cidade</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="cidade" value="<?php echo $casa['cidade'] ?>" id="inCidade" required placeholder="Cidade">
                            </div>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">UF</label>
                            <div class="col-sm-12">
                              <select class="form-control" name="estado" id="estado">
                                <option value="PR" <?php if($casa['estado'] == 'PR'){ echo "selected"; } ?>>PR</option>
                                <option value="AC" <?php if($casa['estado'] == 'AC'){ echo "selected"; } ?>>AC</option>
                                <option value="AL" <?php if($casa['estado'] == 'AL'){ echo "selected"; } ?>>AL</option>
                                <option value="AP" <?php if($casa['estado'] == 'AP'){ echo "selected"; } ?>>AP</option>
                                <option value="AM" <?php if($casa['estado'] == 'AM'){ echo "selected"; } ?>>AM</option>
                                <option value="BA" <?php if($casa['estado'] == 'BA'){ echo "selected"; } ?>>BA</option>
                                <option value="CE" <?php if($casa['estado'] == 'CE'){ echo "selected"; } ?>>CE</option>
                                <option value="DF" <?php if($casa['estado'] == 'DF'){ echo "selected"; } ?>>DF</option>
                                <option value="ES" <?php if($casa['estado'] == 'ES'){ echo "selected"; } ?>>ES</option>
                                <option value="GO" <?php if($casa['estado'] == 'GO'){ echo "selected"; } ?>>GO</option>
                                <option value="MA" <?php if($casa['estado'] == 'MA'){ echo "selected"; } ?>>MA</option>
                                <option value="MT" <?php if($casa['estado'] == 'MT'){ echo "selected"; } ?>>MT</option>
                                <option value="MS" <?php if($casa['estado'] == 'MS'){ echo "selected"; } ?>>MS</option>
                                <option value="MG" <?php if($casa['estado'] == 'MG'){ echo "selected"; } ?>>MG</option>
                                <option value="PA" <?php if($casa['estado'] == 'PA'){ echo "selected"; } ?>>PA</option>
                                <option value="PB" <?php if($casa['estado'] == 'PB'){ echo "selected"; } ?>>PB</option>
                                <option value="PR" <?php if($casa['estado'] == 'PR'){ echo "selected"; } ?>>PR</option>
                                <option value="PE" <?php if($casa['estado'] == 'PE'){ echo "selected"; } ?>>PE</option>
                                <option value="PI" <?php if($casa['estado'] == 'PI'){ echo "selected"; } ?>>PI</option>
                                <option value="RJ" <?php if($casa['estado'] == 'RJ'){ echo "selected"; } ?>>RJ</option>
                                <option value="RN" <?php if($casa['estado'] == 'RN'){ echo "selected"; } ?>>RN</option>
                                <option value="RS" <?php if($casa['estado'] == 'RS'){ echo "selected"; } ?>>RS</option>
                                <option value="RO" <?php if($casa['estado'] == 'RO'){ echo "selected"; } ?>>RO</option>
                                <option value="RR" <?php if($casa['estado'] == 'RR'){ echo "selected"; } ?>>RR</option>
                                <option value="SC" <?php if($casa['estado'] == 'SC'){ echo "selected"; } ?>>SC</option>
                                <option value="SP" <?php if($casa['estado'] == 'SP'){ echo "selected"; } ?>>SP</option>
                                <option value="SE" <?php if($casa['estado'] == 'SE'){ echo "selected"; } ?>>SE</option>
                                <option value="TO" <?php if($casa['estado'] == 'TO'){ echo "selected"; } ?>>TO</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Telefone</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control phone_with_ddd" name="telefone" value="<?php echo $casa['telefone'] ?>" required id="inTelefone" placeholder="(   )      -">
                            </div>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Recado (celular)</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control celphone_with_ddd" name="recado" value="<?php echo $casa['recado'] ?>" id="inRecado" placeholder="(   )      -">
                            </div>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">E-mail</label>
                            <div class="col-sm-12">
                              <input type="email" class="form-control" name="email" value="<?php echo $casa['email'] ?>" id="inEmail" required placeholder="exemplo@myomni.com">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <button type="submit" class="btn btn-info" id="btn-send-form">Cadastrar</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- /# card -->
        </div>
      </div>
      <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <?php //include 'inc/footer.php'; ?>
  </div>
  <!-- End Page wrapper  -->
</div>
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
<script src="assets/js/lib/jquery-mask/jquery.mask.js"></script>

<script type="text/javascript">

function setBorderDanger(id){
  document.getElementById(id).style.border = "1px solid red";
}

function unsetBorderDanger(id){
  document.getElementById(id).style.border = "1px solid #eee";
}

function reload(){
  window.location.reload();
}
$('#inCep').mask('00.000-000');
$('.phone_with_ddd').mask('(00) 0000-0000');
$('.celphone_with_ddd').mask('(00) 00000-0000');

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
	            $("#inEndereco").val(dados.logradouro);
	            $("#inBairro").val(dados.bairro);
	            $("#estado").val(dados.uf);
	            $("#inCidade").val(dados.localidade);
	          }
	        });
	      }
	    }
	  }
	});
});

</script>

</body>

</html>
