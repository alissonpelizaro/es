<?php
include '../application/log.php';
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
        <h3 class="padrao">Log</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Log</li>
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
              <h4 class="card-title">
                Histórico de eventos
              </h4>
              <h6 class="card-subtitle">Log de eventos permanecerá disponivel para consulta por 1 ano.</h6>
              <div class="row">
                <div class="col-12">
                  <form action="log" method="post" class="enterness">
                    <div class="form-body">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label class="control-label junta">Data inicial</label>
                            <input type="text" value="" id="dataini" name="dataini" class="form-control datepicker-here" data-language='pt' data-position="bottom left" data-timepicker="true" data-time-format='hh:ii aa' placeholder="Data de início">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group has-danger">
                            <label class="control-label junta">Data final</label>
                            <input type="text" value="" id="datafim" name="datafim" class="form-control datepicker-here" data-language='pt' data-position="bottom left" data-timepicker="true" data-time-format='hh:ii aa' placeholder="Data final">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label class="control-label junta">Usuário</label>
                            <select class="form-control" name="user">
                              <option value="">Todos</option>
                              <?php foreach ($users as $k) {
                                ?>
                                <option value="<?php echo $k['id'] ?>"><?php echo $k['nome'] ?></option>
                                <?php
                              } ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label class="control-label junta">Ferramenta</label>
                            <select class="form-control" name="ferramenta">
                              <option value="">Todas</option>
                              <option value="Broadcast">Broadcast</option>
                              <option value="Mural">Mural</option>
                              <option value="MyOmni">MyOmni</option>
                              <option value="Wiki">Wiki</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-1">
                          <button type="submit" class="btn btn-info m-t-20">
                            <i class="fa fa-search" aria-hidden="true"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <?php if(isset($_POST['dataini'])): ?>
                <div class="alert alert-info" style="font-size: 13px">
                  <b>Filtros aplicados:</b><br>
                  Data inicial: <i><?php echo $_POST['dataini'] ?></i><br>
                  Data final: <i><?php echo $_POST['datafim'] ?></i><br>
                  Usuário: <i><?php
                  if($_POST['user'] != ""){
                    echo $users[$_POST['user']]['nome'];
                  }

                   ?></i><br>
                  Ferramenta: <i><?php echo $_POST['ferramenta'] ?></i><br>
                </div>
              <?php endif; ?>
              <div class="table-responsive m-t-10">
                <table id="tabelaLog" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>Data</th>
                      <th>Usuário</th>
                      <th>Tipo</th>
                      <th>Ferramenta</th>
                      <th>Ação</th>
                      <th>Observação</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($logs as $linha): ?>
                      <tr>
                        <td><?php echo dataBdParaHtml($linha['dataLog']) ?></td>
                        <td><?php echo $users[$linha['idUsuario']]['nome'] ?></td>
                        <td><?php echo $users[$linha['idUsuario']]['tipo'] ?></td>
                        <td><?php echo $linha['ferramenta'] ?></td>
                        <td><?php echo $linha['acao'] ?></td>
                        <td><?php echo $linha['obs'] ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
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
$(document).ready(function() {
  $('#tabelaLog').DataTable({
    dom: 'Bfrtip',
    buttons: [
      'csv', 'excel', 'pdf', 'print'
    ]
  });

  var ano = new Date();
  ano.setFullYear(ano.getFullYear() - 1);

  $('.datepicker-here').datepicker({
    language: 'pt',
    minDate: ano,
    maxDate: new Date()
  });
});

</script>
</body>

</html>
