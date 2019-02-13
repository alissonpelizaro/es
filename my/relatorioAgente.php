<?php
include '../application/relatorioAgente.php';
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
        <h3 class="padrao">Agentes</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Agentes</li>
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
                Relatório de agentes
              </h4>
              <h6 class="card-subtitle">Recomenda-se um intervalo de datas de, no máximo, 6 meses.</h6>
              <div class="row">
                <div class="col-12">
                  <form action="log" method="post" class="enterness">
                    <div class="form-body">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="control-label junta">Data inicial</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend"><i class="fa fa-calendar"></i></span>
                            <input type="text" placeholder="Search Default" name="Search" class="form-control">
                          </div>
                        </div>
                        <div class="col-sm-2 clockpicker">
                          <label class="control-label junta">Hora inícial</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend"><i class="fa fa-clock-o"></i></span>
                            <input type="text" placeholder="Search Default" name="Search" class="form-control">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="has-danger">
                            <label class="control-label junta">Data final</label>
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend"><i class="fa fa-calendar"></i></span>
                              <input type="text" placeholder="Search Default" name="Search" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-2 clockpicker">
                          <label class="control-label junta">Hora final</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend"><i class="fa fa-clock-o"></i></span>
                            <input type="text" placeholder="Search Default" name="Search" class="form-control">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label junta">Agentes</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend select-ammend"><i class="icon-append fa fa-user"></i></span>
                            <select class="js-select form-control" name="filas" multiple="multiple">
                              <option value="">teste</option>
                              <option value="">test2</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label class="control-label junta">Fila(as)</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend select-ammend"><i class="fa fa-bars"></i></span>
                            <select class="js-select form-control" name="filas" multiple="multiple">
                              <option value="">teste</option>
                              <option value="">test2</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <label class="control-label junta">Fila(as)</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend"><i class="icon-append fa fa-times"></i></span>
                            <input type="number" placeholder="Search Default" name="Search" class="form-control">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <label class="control-label junta">Fila(as)</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend"><i class="icon-append fa fa-check"></i></span>
                            <input type="number" placeholder="Search Default" name="Search" class="form-control">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-1">
                          <button type="submit" class="btn btn-sm btn-info m-t-25">
                            <i class="fa fa-search" aria-hidden="true"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="table-responsive m-t-10">
                <table id="tabelaAgt" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>Data</th>
                      <th>Agente</th>
                      <th>Cliente</th>
                      <th>Plataforma</th>
                      <th>Fila</th>
                      <th>Info.</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($relAgt as $linha): ?>
                      <tr>
                        <td><?php echo dataBdParaHtml($linha['dataInicio']) ?></td>
                        <td><?php echo $linha['nome']." ".$linha['sobrenome'] ?></td>
                        <td><?php echo $linha['remetente'] ?></td>
                        <td class="text-center"><?php echo $linha['plataforma'] ?></td>
                        <td class="text-center"><?php echo $linha['fila'] ?></td>
                        <td class="text-center">
                          <a href="atendimento?hash=<?php echo $linha['idAtendimento']*777; ?>">
                            <button class="btn btn-sm btn-info btn-outline" style="width: 30px;">
                              <i style="font-size: 18px;" class="fa fa-info" aria-hidden="true"></i>
                            </button>
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Data</th>
                      <th>Agente</th>
                      <th>Cliente</th>
                      <th>Plataforma</th>
                      <th>Fila</th>
                      <th></th>
                    </tr>
                  </tfoot>
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
  $('#tabelaAgt').DataTable({
    dom: 'Bfrtip',
    buttons: [
      'csv', 'excel', 'pdf', 'print'
    ]
  });

  $('.js-select').select2({
    closeOnSelect: false
  });

  // Setup - add a text input to each footer cell
  $('#tabelaAgt tfoot th').each( function () {
    var title = $(this).text();//
    if(title != ""){
      $(this).html( '<dvi class="enterness"><input type="text" class="form-control" placeholder="Filtrar"></div>' );
    }
  });

  // DataTable
  var table = $('#tabelaAgt').DataTable();

  // Apply the search
  table.columns().every( function () {
    var that = this;

    $('input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
        .search( this.value )
        .draw();
      }
    });
  });
} );
</script>

</body>

</html>