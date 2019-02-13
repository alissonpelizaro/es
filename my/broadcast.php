<?php
include '../application/lembretes.php';
include '../application/relGrupo.php';

//Define nível de restrição da página
$allowUser = array('dev', 'coordenador', 'administrador', 'supervisor');
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
        <h3 class="padrao">Broadcast</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="broadcasts">Broadcasts</a></li>
          <li class="breadcrumb-item">Nova Broadcast</li>
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
            <h4 class="card-title">
              Enviar nova Broadcast
            </h4>
            <hr>
            <form action="../application/novabroadcast" method="post">
              <div class="row">
                <div class="col-lg-6 offset-lg-3">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Texto</label>
                        <div class="col-sm-12">
                          <textarea type="text" class="form-control" style="height: 120px;" name="broadcast" placeholder="Texto da broadcast" required></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 offset-lg-3">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <div class="form-group">
                          <label class="col-sm-12 control-label junta">Grupos alvo:</label>
                          <div class="custom-control custom-checkbox" style="margin-left: 25px; display: <?php if(!$grupos){ echo 'none'; } ?>">
                            <input type="checkbox" name="grupos[]" <?php if($grupos){ echo 'checked'; } ?> class="custom-control-input" value="todos" id="gpTodos">
                            <label class="custom-control-label" for="gpTodos"><b>Todos</b></label>
                          </div>
                          <section id="dinamicGroup" style="display: <?php if($grupos){ echo "none"; } ?>">
                            <?php
                            if ($grupos) {
                              ?>
                              <?php
                              foreach ($grupos as $grupo) {
                                ?>
                                <div class="custom-control custom-checkbox" style="margin-left: 25px;">
                                  <input type="checkbox" name="grupos[]" class="custom-control-input" value="<?php echo $grupo['idGrupo'] ?>" id="gp<?php echo $grupo['idGrupo'] ?>">
                                  <label class="custom-control-label" for="gp<?php echo $grupo['idGrupo'] ?>"><?php echo $grupo['nome'] ?></label>
                                </div>
                                <?php
                              }
                            } else {
                              ?>
                              <i style="font-size: 12px; margin-left: 20px;">Nenhum grupo criado</i><br><?php
                            } ?>
                            <span class="help-block">
                              <small><i>Se não selecionar nenhum grupo, todos os agentes do sistema receberão a broadcast</i></small>
                            </span>
                          </section>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="broadcasts">
                        <button type="button" class="btn btn-secondary">Voltar</button>
                      </a>
                      <button type="submit" class="btn btn-info">Enviar</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
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
  $( "#gpTodos" ).change(function() {
    if(this.checked){
      $("#dinamicGroup").hide();
    } else {
      $("#dinamicGroup").show();
    }
  });

  <?php if(isset($_GET['send']) && $_GET['send'] == 'success'){
    ?>
    swal("Enviada!", "Uma nova broadcast foi enviada!", "success")
    <?php
  } ?>
  </script>
</body>
</html>
