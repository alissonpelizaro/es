<?php
include '../application/novaSenha.php';
include 'inc/head.php'; ?>
<!-- Main wrapper  -->
<div id="main-wrapper">
  <div class="unix-login">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-4">
          <div class="login-content card">
            <div class="login-form">
              <h1>Olá <?php echo $nome; ?>!</h1>
              <h5>Está na hora de você definir uma nova senha para acesso ao <strong>Portal MyOmni</strong>!</h5>
              <hr>
              <form action="../application/attProfile" name="formSetPass" class="enterness" method="post">
                <input type="hidden" name="hash" value="<?php echo $id*573 ?>">
                <input type="hidden" name="type" value="reset">
                <div class="form-group">
                  <label class="junta">Nova senha</label>
                  <input type="password" class="form-control" id="inSenha" maxlength="50" name="senha" placeholder="Nova senha">
                </div>
                <div class="form-group">
                  <label class="junta">Senha</label>
                  <input type="password" class="form-control" id="inRsenha" maxlength="50" name="rsenha" placeholder="Repita a nova senha">
                </div>
                <button type="button" onclick="setForm()" class="btn btn-info btn-flat m-b-30 m-t-30">Salvar senha</button>
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
function setForm(){
  $("#inSenha").removeClass('bd-danger');
  $("#inRsenha").removeClass('bd-danger');

  var nova = document.getElementById('inSenha').value;
  var rnova = document.getElementById('inRsenha').value;

  if(nova == '' || nova.length < 6){
    $("#inSenha").removeClass('bd-success').addClass('bd-danger');
    document.getElementById('inSenha').value = "";
    setToastDanger('Senha inválida', 'Crie uma senha de 6 caracteres no mínimo');
  } else if (rnova == ''){
    $("#inRsenha").removeClass('bd-success').addClass('bd-danger');
    document.getElementById('inRsenha').value = "";
    setToastDanger('Repite a senha', 'Confirme a senha que você criou');
  } else {
    if(nova != rnova){
      $("#inSenha").removeClass('bd-success').addClass('bd-danger');
      $("#inRsenha").removeClass('bd-success').addClass('bd-danger');
      document.getElementById('inSenha').value = "";
      document.getElementById('inRsenha').value = "";
      setToastDanger('Senhas não conferem', 'Você não confirmou a senha criada. Tente novamente');
    } else {
      document.formSetPass.submit();
    }
  }

}

</script>
</body>
</html>
