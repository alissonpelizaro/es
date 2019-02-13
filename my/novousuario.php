<?php
include '../application/novousuario.php';
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
        <h3 class="padrao">Novo <?php echo $nivel ?></h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="<?php echo $nivel ?>es"><?php echo ucfirst($nivel) ?>es</a></li>
          <li class="breadcrumb-item">Novo <?php echo $nivel ?></li>
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
              <h4>Cadastro de um novo <?php echo $nivel ?></h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/cadastrausuario" method="post">
                  <input type="hidden" name="tipo" value="<?php echo $nivel ?>">
                  <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome do <?php echo $nivel ?></label>
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
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Email</label>
                            <div class="col-sm-12">
                              <input type="email" class="form-control" name="email" id="inEmail" placeholder="Email">
                              <span class="help-block">
                                <small><i id="alertTextEmail">Essa informação é importante para a recuperação de senha</i></small>
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
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
                      <hr>
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
                    <!-- /# column -->
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="<?php echo $nivel ?>es">
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

$("#btn-send-form").on('click', function () {
  if($("#inNome").val() == ""){
    $("#inNome").removeClass('bd-success').addClass('bd-danger');
    setToastDanger('Campo obrigatório', 'Digite o nome do novo <?php echo $nivel ?>');
  } else {
    $("#inNome").removeClass('bd-danger').addClass('bd-success');
    if($("#inSobrenome").val() == ""){
      $("#inSobrenome").removeClass('bd-success').addClass('bd-danger');
      setToastDanger('Campo obrigatório', 'Digite o sobrenome do novo <?php echo $nivel ?>');
    } else {
      $("#inSobrenome").removeClass('bd-danger').addClass('bd-success');
      if($("#inEmail").val() == ""){
        $("#inEmail").removeClass('bd-success').addClass('bd-danger');
        setToastDanger('Campo obrigatório', 'Digite o e-mail do novo <?php echo $nivel ?>');
      } else {
        $.ajax({
          type: "POST",
          data: {
            value : $("#inEmail").val(),
            param : 'email'
          },
          url: "../application/checaAjax",
          success: function(result){
            if(result == 1){
              $("#inEmail").removeClass('bd-success').addClass('bd-danger');
              setToastDanger('E-mail inválido', 'Esse e-mail já está sendo usado por outro usuário');
            } else {
              $('#alertTextEmail').hide();
              $("#inEmail").removeClass('bd-danger').addClass('bd-success');
              if($("#inLogin").val() == ""){
                $("#inLogin").removeClass('bd-success').addClass('bd-danger');
                setToastDanger('Campo obrigatório', 'Crie um login para o novo <?php echo $nivel ?>');
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
