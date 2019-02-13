<?php
include '../application/profile.php';
//Define nível de restrição da página
$allowUser = array('dev', 'coordenador', 'administrador', 'supervisor', 'agente', 'gestor', 'tecnico');
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
        <h3 class="padrao">Meu perfil</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Meu perfil</li>
        </ol>
      </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
      <!-- Start Page Content -->
      <div class="row">
        <!-- Column -->
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="card-two">
                <header>
                  <?php if($user['avatar'] == ""){
                    ?>
                    <div class="avatar-profile">
                      <img src="assets/avatar/default.jpg" alt="<?php echo $user['nome'] . " " . $user['sobrenome'] ?>" />
                    </div>
                    <?php
                  } else {
                    ?>
                    <div class="avatar-profile avatar-hover" onclick="setTrashAvatar()">
                      <img src="assets/avatar/<?php echo $user['avatar'] ?>" alt="<?php echo $user['nome'] . " " . $user['sobrenome'] ?>" />
                    </div>
                    <?php
                  } ?>

                </header>
                <h3><?php echo $user['nome'] . " " . $user['sobrenome'] ?></h3>
                <div class="desc">
                  <?php echo retCargo($user['tipo']) ?>
                </div>
                <div class="contacts" style="display: none;">
                  <a href=""><i class="fa fa-plus"></i></a>
                  <a href=""><i class="fa fa-whatsapp"></i></a>
                  <a href=""><i class="fa fa-envelope"></i></a>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-12">
          <div class="card">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs profile-tab" role="tablist">
              <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Minhas informações</a> </li>
              <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#access" role="tab">Acesso</a> </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
              <!--second tab-->
              <div class="tab-pane" id="settings" role="tabpanel">
                <div class="card-body">
                  <form class="enterness" enctype="multipart/form-data" action="../application/attProfile" method="post">
                    <input type="hidden" name="type" value="info">
                    <div class="row">
                      <div class="col-lg-6 offset-lg-3">
                        <div class="row">
                          <div class="col-6">
                            <div class="form-group">
                              <label class="col-sm-12 control-label junta">Nome</label>
                              <div class="col-sm-12">
                                <input type="text" class="form-control" value="<?php echo $user['nome'] ?>" required name="nome" placeholder="Nome">
                              </div>
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="form-group">
                              <label class="col-sm-12 control-label junta">Sobrenome</label>
                              <div class="col-sm-12">
                                <input type="text" class="form-control" value="<?php echo $user['sobrenome'] ?>" required name="sobrenome" placeholder="Sobrenome">
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-12 control-label junta">Atualizar seu avatar?</label>
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
                        <hr>
                        <div class="row">
                          <div class="col-12">
                            <center>
                              <button type="submit" class="btn btn-info">Atualizar</button>
                            </center>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane" id="access" role="tabpanel">
                <div class="card-body">
                  <form class="enterness">
                    <div class="row">
                      <div class="col-lg-6 offset-lg-3">
                        <div class="form-group">
                          <label class="col-sm-12 control-label junta">Email</label>
                          <div class="col-sm-12">
                            <input type="email" disabled class="form-control" value="<?php echo $user['email'] ?>" name="email" id="inEmail" placeholder="Email">
                            <span class="help-block">
                              <small><i id="alertTextEmail">Essa informação é importante para a recuperação de senha</i></small>
                            </span>
                            <hr>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-12 control-label junta">Login</label>
                          <div class="col-sm-12">
                            <input class="form-control" disabled value="<?php echo $user['usuario'] ?>" type="text" name="login" id="inLogin" placeholder="Login">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="col-sm-12 control-label junta">Senha</label>
                              <div class="col-sm-12">
                                <input type="password" disabled value="******" class="form-control" name="senha" id="inSenha" placeholder="Senha">
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-12">
                            <center>
                              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#attEmailModal">Atualizar email</button>
                              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#attLoginModal">Atualizar login</button>
                              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#attSenhaModal">Atualizar senha</button>
                            </center>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Column -->
      </div>
      <!-- End PAge Content -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="attEmailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Atualizar e-mail</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="reload()" style="margin-top: -20px; margin-right: -20px;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <form class="enterness">
                  <div class="form-group">
                    <label class="col-sm-12 control-label junta">Email atual</label>
                    <div class="col-sm-12">
                      <input type="email" disabled class="form-control" value="<?php echo $user['email'] ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-12 control-label junta">Senha atual</label>
                    <div class="col-sm-12">
                      <input type="password" class="form-control" id="inSenhaEmail" placeholder="Digite sua senha">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-12 control-label junta">Novo email</label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="inNovoEmail" placeholder="Digite seu novo email">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reload()">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="checkFromEmail()">Salvar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="attLoginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Atualizar login de acesso</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="reload()" style="margin-top: -20px; margin-right: -20px;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <form class="enterness">
                  <div class="form-group">
                    <label class="col-sm-12 control-label junta">Login atual</label>
                    <div class="col-sm-12">
                      <input type="text" disabled class="form-control" value="<?php echo $user['usuario'] ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-12 control-label junta">Novo login</label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" autocomplete="false" id="inNovoLogin" placeholder="Digite seu novo login">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-12 control-label junta">Senha atual</label>
                    <div class="col-sm-12">
                      <input type="password" class="form-control" id="inSenhaAtualL" placeholder="Digite sua senha">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reload()">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="checkFromLogin()">Salvar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="attSenhaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Atualizar senha de acesso</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="reload()" style="margin-top: -20px; margin-right: -20px;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <form class="enterness">
                  <div class="form-group">
                    <label class="col-sm-12 control-label junta">Senha atual</label>
                    <div class="col-sm-12">
                      <input type="password" class="form-control" id="inSenhaAtualS" placeholder="Digite sua senha atual">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-12 control-label junta">Nova senha</label>
                    <div class="col-sm-12">
                      <input type="password" class="form-control" id="inSenhaNovaS" placeholder="Digite a nova senha">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-12 control-label junta">Repita a nova senha</label>
                    <div class="col-sm-12">
                      <input type="password" class="form-control" id="inRSenhaNovaS" placeholder="Repita a nova senha">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reload()">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="checkFromSenha()">Salvar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Container fluid  -->
    <?php include 'inc/footer.php'; ?>
  </div>
  <!-- End Page wrapper  -->
</div>
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">
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

function reload(){
  window.location.href="profile";
}

function checkFromSenha(){

  $("#inSenhaAtualS").removeClass('bd-danger');
  $("#inSenhaNovaS").removeClass('bd-danger');
  $("#inRSenhaNovaS").removeClass('bd-danger');

  var atual = document.getElementById('inSenhaAtualS').value;
  var nova = document.getElementById('inSenhaNovaS').value;
  var rnova = document.getElementById('inRSenhaNovaS').value;

  if(atual == '' || atual.length < 6){
    $("#inSenhaAtualS").removeClass('bd-success').addClass('bd-danger');
    document.getElementById('inSenhaAtualS').value = "";
    setToastDanger('Senha inválida', 'Digite sua senha');
  } else {
    if(nova == '' || nova.length < 6){
      $("#inSenhaNovaS").removeClass('bd-success').addClass('bd-danger');
      document.getElementById('inSenhaNovaS').value = "";
      setToastDanger('Senha inválida', 'Crie uma senha de 6 caracteres no mínimo');
    } else if (rnova == ''){
      $("#inRSenhaNovaS").removeClass('bd-success').addClass('bd-danger');
      document.getElementById('inRSenhaNovaS').value = "";
      setToastDanger('Repite a senha', 'Confirme a senha que você criou');
    } else {
      if(nova != rnova){
        $("#inSenhaNovaS").removeClass('bd-success').addClass('bd-danger');
        $("#inRSenhaNovaS").removeClass('bd-success').addClass('bd-danger');
        document.getElementById('inSenhaNovaS').value = "";
        document.getElementById('inRSenhaNovaS').value = "";
        setToastDanger('Senhas não conferem', 'Você não confirmou a senha criada. Tente novamente');
      } else {
        $.ajax({
          type: "POST",
          data: {
            senha : atual,
            nova : nova,
            type : 'senha'
          },
          url: "../application/attProfile",
          success: function(result){
            if(result == '1'){
              window.location.href = "profile?update=success&status=success";
            } else if(result == '0'){
              window.location.href = "profile?update=failure&status=failure";
            } else if(result == 'senha'){
              $("#inSenhaAtualS").removeClass('bd-success').addClass('bd-danger');
              document.getElementById('inSenhaAtualS').value = "";
              setToastDanger('Senha inválida', 'Digite sua senha');
            }
          }
        });
      }
    }
  }
}

function checkFromLogin(){
  $("#inNovoLogin").removeClass('bd-danger');
  $("#inSenhaAtualL").removeClass('bd-danger');

  var atual = '<?php echo $user['usuario'] ?>';
  var novo = document.getElementById('inNovoLogin').value;
  var senha = document.getElementById('inSenhaAtualL').value;
  if(senha == "" || senha.length < 6){
    $("#inSenhaAtualL").removeClass('bd-success').addClass('bd-danger');
    document.getElementById('inSenhaAtualL').value = "";
    setToastDanger('Senha inválida', 'Digite sua senha');
  } else {
    if(novo == ""){
      $("#inNovoLogin").removeClass('bd-success').addClass('bd-danger');
      document.getElementById('inNovoLogin').value = "";
      setToastDanger('Campo obrigatório', 'Digite o novo login');
    } else {
      if(novo == atual){
        $("#inNovoLogin").removeClass('bd-success').addClass('bd-danger');
        document.getElementById('inNovoLogin').value = "";
        setToastDanger('Opa', 'O login que você digitou já é o seu cadastrado!');
      } else {
        $.ajax({
          type: "POST",
          data: {
            param : 'login',
            value : novo
          },
          url: "../application/checaAjax",
          success: function(result){
            if(result == '1'){
              $("#inNovoLogin").removeClass('bd-success').addClass('bd-danger');
              document.getElementById('inNovoLogin').value = "";
              setToastDanger('Opa', 'O login que você digitou já está vinculado a outro usuário!');
            } else if(result == '0'){
              $.ajax({
                type: "POST",
                data: {
                  senha : senha,
                  login : novo,
                  type : 'login'
                },
                url: "../application/attProfile",
                success: function(result){
                  if(result == '1'){
                    window.location.href = "profile?update=success&status=success";
                  } else if(result == '0'){
                    window.location.href = "profile?update=failure&status=failure";
                  } else if(result == 'senha'){
                    $("#inSenhaAtualL").removeClass('bd-success').addClass('bd-danger');
                    document.getElementById('inSenhaAtualL').value = "";
                    setToastDanger('Senha inválida', 'Digite sua senha');
                  }
                }
              });
            }
          }
        });
      }
    }
  }
}

function checkFromEmail(){
  $("#inNovoEmail").removeClass('bd-danger');
  $("#inSenhaEmail").removeClass('bd-danger');

  var atual = '<?php echo $user['email'] ?>';
  var novo = document.getElementById('inNovoEmail').value;
  var senha = document.getElementById('inSenhaEmail').value;
  if(senha == "" || senha.length < 6){
    $("#inSenhaEmail").removeClass('bd-success').addClass('bd-danger');
    document.getElementById('inSenhaEmail').value = "";
    setToastDanger('Senha inválida', 'Digite sua senha');
  } else {
    if(novo == ""){
      $("#inNovoEmail").removeClass('bd-success').addClass('bd-danger');
      document.getElementById('inNovoEmail').value = "";
      setToastDanger('Campo obrigatório', 'Digite o novo e-mail');
    } else {
      if(novo == atual){
        $("#inNovoEmail").removeClass('bd-success').addClass('bd-danger');
        document.getElementById('inNovoEmail').value = "";
        setToastDanger('Opa', 'O e-mail que você digitou já é o seu cadastrado!');
      } else {
        $.ajax({
          type: "POST",
          data: {
            param : 'email',
            value : novo
          },
          url: "../application/checaAjax",
          success: function(result){
            if(result == '1'){
              $("#inNovoEmail").removeClass('bd-success').addClass('bd-danger');
              document.getElementById('inNovoEmail').value = "";
              setToastDanger('Opa', 'O e-mail que você digitou já está vinculado a outro usuário!');
            } else if(result == '0'){
              $.ajax({
                type: "POST",
                data: {
                  senha : senha,
                  email : novo,
                  type : 'email'
                },
                url: "../application/attProfile",
                success: function(result){
                  if(result == '1'){
                    window.location.href = "profile?update=success&status=success";
                  } else if(result == '0'){
                    window.location.href = "profile?update=failure&status=failure";
                  } else if(result == 'senha'){
                    $("#inSenhaEmail").removeClass('bd-success').addClass('bd-danger');
                    document.getElementById('inSenhaEmail').value = "";
                    setToastDanger('Senha inválida', 'Digite sua senha');
                  }
                }
              });
            }
          }
        });
      }
    }
  }
}

<?php if(isset($_GET['update']) && $_GET['update'] == 'success'){
  ?>
  swal("Feito!", "A suas informações de perfil foram atualizadas!", "success")
  <?php
} ?>

function setTrashAvatar(){
  swal({
    title: "Deseja remover sua foto de perfil?",
    text: "Essa ação não poderá ser desfeita!",
    type: "warning",
    showCancelButton: true,
    confirmButtonText: "Sim, remover!",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#DD6B55",
    closeOnConfirm: true,
    closeOnCancel: true
  },
  function(isConfirm){
    if(isConfirm){

      $.ajax({
        type: "POST",
        data: {
          type : 'rmvAvatar'
        },
        url: "../application/attProfile",
        success: function(result){
          if(result == '1'){
            window.location.href = "profile?update=success&status=removed";
          } else if(result == '0'){
            window.location.href = "profile?update=success&status=removed";
          }
        }
      });
    }
  });
}


</script>

</body>

</html>
