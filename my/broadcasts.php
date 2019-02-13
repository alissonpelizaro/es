<?php
include '../application/broadcasts.php';
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
        <h3 class="padrao">Broadcasts</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Broadcasts</li>
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
                Broadcasts enviadas
                <span>
                  <a href="broadcast" class="btn btn-sm btn-info btn-new">
                    Nova broadcast
                  </a>
                </span>
              </h4>
              <div class="table-responsive m-t-10">
                <?php if(!$broadcasts){
                  ?>
                  <hr>
                  <center>
                    <h3 class="text-muted"><i>Nenhuma broadcast ativa.</i></h3>
                  </center>
                  <?php
                } else { ?>
                  <table id="agentesTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style="width: 50px;"></th>
                        <th>Conteúdo</th>
                        <th>Data de envio</th>
                        <th>Grupos alvo</th>
                        <th>Agentes alvo</th>
                        <th>Visualizações pendentes</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($broadcasts as $broad) {
                        $dst = retQtdAgente($broad['destinatarios']);
                        $conf = retQtdAgente($broad['confirmacoes']);
                        ?>
                        <tr>
                          <td><i class="fa fa-paper-plane-o text-<?php if($dst == $conf){ echo 'success'; } else { echo 'warning'; }?>" aria-hidden="true"></i></td>
                          <td><?php
                          if(strlen($broad['broadcast']) > 60){
                            echo substr($broad['broadcast'], 0, 60)."...";
                          } else {
                            echo $broad['broadcast'];
                          }
                          ?></td>
                          <td><?php echo dataBdParaHtml($broad['data']) ?></td>
                          <td><?php echo retGruposMural($grupos, $broad['grupos']) ?></td>
                          <td><?php echo $dst; ?></td>
                          <td><?php echo $dst - $conf; ?></td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fa fa-bars" aria-hidden="true"></i>
                                <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                  <li><a href="broadcastDetails?token=<?php echo $token ?>&hash=<?php echo $broad['idBroadcast']*13; ?>&typography=inside">Detalhar broadcast</a></li>
                                  <li><a href="javascript: void()" onclick="setTrash('<?php echo $broad['idBroadcast']*13; ?>')">Cancelar broacast</a></li>
                                </ul>
                              </div>
                            </td>
                          </tr>
                          <?php
                        } ?>
                      </tbody>
                    </table>
                  <?php } ?>
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
    $('#agentesTable').DataTable();

    <?php if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'success'){
      ?>
      swal("Opa, feito!", "Uma nova broadcast foi enviada!", "success")
      <?php
    } ?>
    <?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
      ?>
      swal("Feito!", "A broadcast foi excluída!", "success")
      <?php
    } ?>
  });

  function setTrash(selected){
    swal({
      title: "Deseja mesmo excluir essa broadcast?",
      text: "Essa ação não poderá ser desfeita!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Sim, excluir!",
      cancelButtonText: "Cancelar",
      closeOnConfirm: true,
      closeOnCancel: true
    },
    function(isConfirm){
      if(isConfirm){

        $.ajax({
          type: "POST",
          data: {
            hash : selected
          },
          url: "../application/deletaBroadcast",
          success: function(result){
            if(result == '1'){
              window.location.href = "broadcasts?action=trashed&status=success";
            } else if(result == '0'){
              window.location.href = "broadcasts?action=trashed&status=failure";
            }
          }
        });
      }
    });
  }
</script>
</body>

</html>
