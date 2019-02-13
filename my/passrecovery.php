<?php
include '../application/passRecoveryToken.php';
include 'inc/head.php'; ?>
<!-- Main wrapper  -->
<div id="main-wrapper">
  <div class="unix-login">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-4">
          <div class="login-content card">
            <div class="login-form">
              <h2>Olá, <?php echo $dados['nome'] ?>!</h2>
              <p>Vamos criar uma nova senha para você...</p>
              <hr>
              <form action="../application/passRecoveryForm" name="formNewPass" class="enterness" method="post">
                <input type="hidden" name="token" value="<?php echo $_GET['token'] ?>">
                <div class="form-group">
                  <label class="junta">Nova senha</label>
                  <input type="password" class="form-control" maxlength="50" id="inSenha" name="senha" placeholder="Nova senha" required>
                </div>
                <div class="form-group">
                  <label class="junta">Repita a nova senha</label>
                  <input type="password" class="form-control" maxlength="50" id="inRsenha" name="rsenha" placeholder="Repita a nova senha" required>
                </div>
                <button type="button" class="btn btn-info btn-flat m-b-30 m-t-30" id="checaSenhas">Salvar</button>
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

$("#checaSenhas").on('click', function () {

  if($("#inSenha").val() == "" || $("#inRsenha").val() == ""){
    $("#inSenha").removeClass('bd-success').addClass('bd-danger');
    $("#inRsenha").removeClass('bd-success').addClass('bd-danger');
    setToastDanger('Campos obrigatórios', 'Crie e confirme uma nova senha');
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
        document.formNewPass.submit();
      }
    }
  }
});

</script>
</body>
</html>
