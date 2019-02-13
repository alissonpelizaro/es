<?php
include '../core.php';
include '../application/relGrupo.php';
if(!$licenca->validaLicencaAgente()){
  header('Location: ../my/agentes');
}
//Define nível de restrição da página
$allowUser = array('dev', 'coordenador', 'administrador');
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
        <h3 class="padrao">Novo agente</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="agentes">Agentes</a></li>
          <li class="breadcrumb-item">Novo agente</li>
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
              <h4>Cadastro de um novo agente</h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/cadastraagente" method="post">
                  <div class="row">
                    <div class="col-lg-6">
                      <i>Informações de cadastro</i>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome do agente</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="nome" id="inNome" placeholder="Nome">
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Sobrenome</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="sobrenome" id="inSobrenome" placeholder="Sobrenome">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Email (opcional)</label>
                        <div class="col-sm-12">
                          <input type="email" class="form-control" name="email" value="" id="inEmail" placeholder="Email">
                          <span class="help-block">
                            <small><i id="alertTextEmail">Essa informação é importante para a recuperação de senha</i></small>
                          </span>
                          <hr>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Login</label>
                        <div class="col-sm-12">
                          <input class="form-control" type="text" name="login" id="inLogin" placeholder="Login">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Senha</label>
                            <div class="col-sm-12">
                              <input type="password" class="form-control" readonly value="mudar123" name="senha" id="inSenha" placeholder="Senha">
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Confirme a senha</label>
                            <div class="col-sm-12">
                              <input type="password" class="form-control" readonly value="mudar123" name="rsenha" id="inRsenha" placeholder="Repita a senha">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-6" style="border-left: 1px solid #eee;">
                      <i>Informações de atendimento</i>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Ramal</label>
                            <div class="col-sm-12">
                              <input class="form-control" type="number" min="1000" max="9999" maxlength="4" name="ramal" placeholder="0000">
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Setor</label>
                            <div class="col-sm-12">
                              <select class="form-control" name="setor" <?php if($tipo != 'dev'){ echo 'disabled'; } ?>>
                                <?php foreach ($setores as $setor) {
                                  ?>
                                  <option value="<?php echo $setor['idSetor'] ?>" <?php if($setorUser == $setor['idSetor']){ echo 'selected'; } ?>><?php echo $setor['nome'] ?></option>
                                  <?php
                                } ?>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">CHAT permitido?</label>
                        <div class="col-sm-12">
                          <select class="form-control" name="chat">
                            <option value="todos">Sim, para todos</option>
                            <option value="sup">Sim, para supervisores</option>
                            <option value="nao">Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Adicionar às filas:</label>
                        <div class="col-sm-12">
                          <select class="js-select select-tec-regra" name="filas[]" multiple="multiple" style="width: 100%">
                            <?php foreach ($filas as $fila): ?>
                              <option value="<?php echo $fila['nomeFila'] ?>"><?php echo $fila['nomeFila'] ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <div class="row">
	                      <div class="col-5">
		                      <div class="form-group">
		                        <label class="col-sm-12 control-label junta">Vincular aos grupos:</label>
		                        <?php
		                        if ($grupos) {
		                          foreach ($grupos as $grupo) {
		                            ?>
		                            <div class="custom-control custom-checkbox">
		                              <input type="checkbox" name="grupos[]" class="custom-control-input" value="<?php echo $grupo['idGrupo'] ?>" id="gp<?php echo $grupo['idGrupo'] ?>">
		                              <label class="custom-control-label" for="gp<?php echo $grupo['idGrupo'] ?>"><?php echo $grupo['nome'] ?></label>
		                            </div>
		                            <?php
		                          }
		                        } else {
		                          ?>
		                          <i style="font-size: 12px; margin-left: 20px;">Nenhum grupo criado</i><?php
		                        }
		                        ?>
		                      </div>
		                      <div class="form-group">
		                        <label class="col-sm-12 control-label junta">Definir um avatar?</label>
		                        <div class="input-group input-group-flat col-sm-12">
		                          <span class="input-group-btn">
		                            <label for="fileUpload">
		                              <span class="btn btn-secondary"><i class="ti-search"></i></span>
		                            </label>
		                          </span>
		                          <div id="wrapper">
		                            <input id="fileUpload" type="file" name="avatar" style="display: none" accept="image/*">
		                            <div id="image-holder" onclick="clearImage()"></div>
		                          </div>
		                        </div>
		                      </div>
	                      </div>
	                      <div class="col-7" style="border-left: 1px solid #eee; display: <?php if(!$util->getSectionPermission('media')){ echo 'none'; } ?>">
                          <div class="form-group m-t-10">
                            <div class="row">
                              <div class="col-12">
                                <label>WhatsApp:</label>
                                <div class="form-check form-check-inline" style="padding-left: 6px;margin-right: 6px;top: 5px;">
                                  <input class="form-check-input slideCheck" type="checkbox" checked name="checkboxWhatsapp" value="1">
                                </div>
                              </div>
                              <div class="col-12">
                                <label>Enterness:</label>
                                <div class="form-check form-check-inline" style="padding-left: 6px;margin-right: 6px;top: 5px;">
                                  <input class="form-check-input slideCheck" type="checkbox" checked name="checkboxEnterness" value="1">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-12">
                                <label class="junta">Quantidade de atendimentos simultâneos:</label>
                                <input id="qtdInput" value="0" style="width: 170px;" required class="form-control" type="number" min="1" max="20" maxlength="2" name="qtdAt" placeholder="Qtd.">
                                <small><i>(0 = sem limite)</i> </small>
                              </div>
                            </div>
                          </div>
	                      </div>
                      </div>
                    </div>
                    <!-- /# column -->
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="agentes">
                        <button type="button" class="btn btn-secondary">Voltar</button>
                      </a>
                      <button type="button" class="btn btn-info" id="btn-send-form">Cadastrar</button>
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
    <?php include 'inc/footer.php'; ?>
  </div>
  <!-- End Page wrapper  -->
</div>
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">

$(function(){
  $.switcher('.slideCheck');
  $('.js-select').select2({
    closeOnSelect: false
  });
});

$("#btn-send-form").on('click', function () {
  if($("#inNome").val() == ""){
    $("#inNome").removeClass('bd-success').addClass('bd-danger');
    setToastDanger('Campo obrigatório', 'Digite o nome do novo agente');
  } else {
    $("#inNome").removeClass('bd-danger').addClass('bd-success');
    if($("#inSobrenome").val() == ""){
      $("#inSobrenome").removeClass('bd-success').addClass('bd-danger');
      setToastDanger('Campo obrigatório', 'Digite o sobrenome do novo agente');
    } else {
      $("#inSobrenome").removeClass('bd-danger').addClass('bd-success');
      if($("#inEmail").val() == "dev@enterness.com"){
        $("#inEmail").removeClass('bd-success').addClass('bd-danger');
        setToastDanger('Hey!', 'Você não pode usar esse e-mail.');
      } else {
        $.ajax({
          type: "POST",
          data: {
            value : $("#inEmail").val(),
            param : 'email'
          },
          url: "../application/checaAjax",
          success: function(result){
            if(result == 1 && $("#inEmail").val() != ""){
              $("#inEmail").removeClass('bd-success').addClass('bd-danger');
              setToastDanger('E-mail inválido', 'Esse e-mail já está sendo usado por outro usuário');
            } else {
              $('#alertTextEmail').hide();
              $("#inEmail").removeClass('bd-danger').addClass('bd-success');
              if($("#inLogin").val() == ""){
                $("#inLogin").removeClass('bd-success').addClass('bd-danger');
                setToastDanger('Campo obrigatório', 'Crie um login para o novo agente');
              } else {
                $.ajax({
                  type: "POST",
                  data: {
                    value : $("#inLogin").val(),
                    param : 'login'
                  },
                  url: "../application/checaAjax",
                  success: function(result){
                    if(result == 1){
                      $("#inLogin").removeClass('bd-success').addClass('bd-danger');
                      setToastDanger('Login inválido', 'Esse login já está sendo usado por outro usuário');
                    } else {
                      $("#inLogin").removeClass('bd-danger').addClass('bd-success');
                      if($("#inSenha").val() == "" || $("#inRsenha").val() == ""){
                        $("#inSenha").removeClass('bd-success').addClass('bd-danger');
                        $("#inRsenha").removeClass('bd-success').addClass('bd-danger');
                        setToastDanger('Campos obrigatórios', 'Crie e confirme uma senha para login');
                      } else {
                        var senha = $('#inSenha').val();
                        if(senha.length < 6){
                          $("#inSenha").removeClass('bd-success').addClass('bd-danger');
                          $("#inRsenha").removeClass('bd-success').addClass('bd-danger');
                          setToastDanger('Senha muito fraca', 'Defina uma senha de 6 caracteres no mínimo.');
                        } else {
                          if(senha != $("#inRsenha").val()){
                            $("#inSenha").removeClass('bd-success').addClass('bd-danger');
                            $("#inRsenha").removeClass('bd-success').addClass('bd-danger');
                            setToastDanger('Senhas divergentes', 'As senha digitadas não conferem, tente novamente.');
                          } else {
                            document.sendFormAgente.submit();
                          }
                        }
                      }
                    }
                  }
                });
              }
            }
          }
        });
      }
    }
  }
});

function clearImage(){
  var image_holder = $("#image-holder");
  image_holder.hide();
  image_holder.empty();
  document.getElementById('fileUpload').value="";
}

$("#fileUpload").on('change', function () {

  if (typeof (FileReader) != "undefined") {

    var image_holder = $("#image-holder");
    image_holder.empty();

    var reader = new FileReader();
    reader.onload = function (e) {
      $("<img />", {
        "src": e.target.result,
        "class": "thumb-image"
      }).appendTo(image_holder);
    }
    image_holder.show();
    $("#image-holder").show();
    reader.readAsDataURL($(this)[0].files[0]);
  }
});
</script>
</body>

</html>
