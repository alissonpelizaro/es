<?php
include '../coreExt.php';
include 'inc/head.php'; ?>
<!-- Main wrapper  -->
<div id="main-wrapper">
  <div class="unix-login">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-4">
          <div class="login-content card">
            <div class="login-form">
              <h4><img height="100px" src="assets/images/logominig.png"></h4>
              <form action="../application/login" class="enterness" method="post">
                <?php if(isset($_GET['user']) && $_GET['user'] == 'invalid'){ ?>
                  <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Erro!</strong> Usuário ou senha inválido.
                  </div>
                <?php } ?>
                <div class="form-group">
                  <label class="junta">Usuário</label>
                  <input type="text" class="form-control" maxlength="50" name="usuario" placeholder="Usuário" required>
                </div>
                <div class="form-group">
                  <label class="junta">Senha</label>
                  <input type="password" class="form-control" maxlength="50" name="senha" placeholder="Senha" required>
                </div>
                <div class="checkbox">
                  <label class="pull-right">
                    <span class="text-primary" style="cursor: pointer; font-size: 12px;" onclick="passRecovery()">
                      Esqueceu sua senha?
                    </span>
                  </label>
                </div>
                <button type="submit" class="btn btn-info btn-flat m-b-30 m-t-30">Entrar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">
function passRecovery(){
  swal({
    title: "Recuperação de senha",
    text: "Digite seu e-mail:",
    type: "input",
    showCancelButton: true,
    closeOnConfirm: false,
    confirmButtonColor: "#2E64FE",
    animation: "slide-from-top",
    inputPlaceholder: "exemplo@enterness.com"
  },
  function(inputValue){
    if (inputValue === false) return false;
    if (inputValue === "") {
      swal.showInputError("Você precisa digitar um e-mail!");
      return false
    } else {
      $.ajax({
        type: "POST",
        data: {
          value : inputValue
        },
        url: "../application/passRecovery",
        success: function(result){
          if(result != '0'){
            $.ajax({
              type: "POST",
              data: {
                token : result,
                email : inputValue
              },
              url: "../application/passRecovery",
              success: function(result){
              }
            });
            swal({
              title: "Feito!",
              text: "Enviaremos um link para a recuperação de senha no seu e-mail. Fique atento!",
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "green",
              confirmButtonText: "Ok",
              closeOnConfirm: false
            },
            function(){
              window.location.href = "login";
            });
          } else {
            swal({
              title: "Opa!",
              text: "Não será possivel recuperar a senha desse usuário. Contacte o administrador do sistema.",
              type: "error",
              showCancelButton: false,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Ok",
              closeOnConfirm: false
            },
            function(){
              window.location.href = "login";
            });
          }
        }
      });
    }
  });
}

<?php if(isset($_GET['recovery']) && $_GET['recovery'] == 'success'){
  ?>
  swal("Opa, tudo certo!", "Uma nova senha foi cadastrada!", "success")
  <?php
} ?>

<?php if(isset($_GET['recovery']) && $_GET['recovery'] == 'failure'){
  ?>
  swal("Ops!", "Houve um erro ao criar sua senha. Se o problema persistir, contacte o administrador!", "warning")
  <?php
} ?>

</script>
</body>
</html>
