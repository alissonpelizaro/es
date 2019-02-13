<?php
include '../application/setup.php';
//Define nível de restrição da página
$allowUser = array('dev');
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
        <h3 class="padrao">Setup<i>MyOmni</i></h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Configurações</li>
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
            <div class="card-body">
              <h4 class="card-title text-center">Configurações gerais:</h4>
              <div class="row">
                <div class="col-md-6 offset-md-3">
                  <form class="enterness" action="../application/setup" method="post">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="junta">Licenças de coordenadores</label>
                          <input type="number" required name="mtdr" value="<?php echo $config['mtdr'] ?>" min="0" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="junta">Licenças de administradores</label>
                          <input type="number" required name="mtmr" value="<?php echo $config['mtmr'] ?>" min="0" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="junta">Licenças de supervisores</label>
                          <input type="number" required name="mtvr" value="<?php echo $config['mtvr'] ?>" min="0" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="junta">Licenças de agentes</label>
                          <input type="number" required name="mtnt" value="<?php echo $config['mtnt'] ?>" min="0" class="form-control">
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="junta">Dias de varredura do CronTab</label>
                          <input type="number" required name="cront" value="<?php echo $config['diasCrontab'] ?>" min="0" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="junta">Minutos de timeout da sessão</label>
                          <input type="number" required name="timeout" value="<?php echo $config['timeoutSessao'] ?>" min="0" class="form-control">
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-12">
                        <center>
                          <button type="submit" class="btn btn-info">Salvar mudanças</button>
                        </center>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <form class="" action="setup" method="post">
                <div class="row">
                  <div class="col-6 offset-3">
                    <hr>
                    <div class="form-group">
                      <label class="col-sm-12 control-label junta">Setor</label>
                      <div class="col-sm-12">
                        <select class="form-control" name="setor">
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
                <div class="row">
                  <div class="col-6 offset-3 text-center">
                    <button type="submit" class="btn btn-sm btn-info btn-outline" name="button">Trocar setor</button>
                  </div>
                </div>
              </form>
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
<?php if(isset($_GET['setup']) && $_GET['setup'] == 'success'){
  ?>
  swal("Feito!", "As configurações foram atualizadas!", "success");
  <?php
} ?>
<?php if(isset($_GET['setor']) && $_GET['setor'] == 'atualizado'){
  ?>
  swal("Tudo certo!", "Você trocou de setor!", "success");
  <?php
} ?>
</script>
</body>

</html>
