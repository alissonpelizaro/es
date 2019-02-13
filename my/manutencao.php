<?php
include '../config/config.php';
$config = new Ambiente;
//Checa se o sistema está mesmo em manutenção
if($config->environment != "MAINTENANCE"){
  header('Location: inicio');
}
include 'inc/head.php'; ?>
<!-- Main wrapper  -->
<div id="main-wrapper">
  <div class="unix-login">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-4">
          <div class="login-content card">
            <div class="login-form">
              <h4><img src="assets/images/omni_.jpg"></h4>
              <h1>Ops...</h1>
              <h3>O sistema está em manutenção!</h3>
              <p>Por favor aguarde alguns minutos... Tudo se normalizará em breve.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
</body>
</html>
