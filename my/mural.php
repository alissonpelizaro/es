<?php
include '../application/mural.php';
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
        <h3 class="padrao">Mural</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Mural</li>
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
              Mensagens de mural ativas:
              <span>
                <a href="cadastramensagem">
                  <button class="btn btn-sm btn-info btn-new">
                    Nova mensagem
                  </button>
                </a>
              </span>
            </h4>
            <hr>

            <div class="card-body">
              <div class="row">
                <?php if(!$msgs){
                  ?>
                  <span style="text-align: center; width: 100%;">
                    <h3><i>Nenhuma mensagem de mural foi criada</i></h3>
                  </span>
                  <?php
                } else {
                  $count = 0;
                  foreach ($msgs as $msg) {
                    $count++;
                    ?>
                    <div class="col-md-6">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">#<?php echo $count ?></h4>
                          <div class="btn-trash">
                            <div class="btn-group">
                              <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-cog"></i>
                              </button>
                              <ul class="dropdown-menu" role="menu">
                                <li><a href="cadastramensagem?form=edit&token=<?php echo $token ?>&id=<?php echo $count; ?>&hash=<?php echo $msg['idMural'] * 7; ?>">Editar</a></li>
                                <li><a href="javascript: void();" onclick="setTrash('<?php echo $msg['idMural'] * 7; ?>')">Apagar</a></li>
                              </ul>
                            </div>
                          </div>
                          <!-- Nav tabs -->
                          <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#visao<?php echo $count ?>" role="tab"><span><i class="fa fa-tasks"></i></span></a> </li>
                            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#det<?php echo $count ?>" role="tab"><span><i class="fa fa-info"></i></span></a> </li>
                          </ul>
                          <!-- Tab panes -->
                          <div class="tab-content tabcontent-border" style="border-left: 2px solid <?php echo retCorMuralBox($msg['criticidade']) ?>">
                            <div class="tab-pane active" id="visao<?php echo $count ?>" role="tabpanel">
                              <div class="p-20">
                                <h4 class="text-<?php echo retCorMuralTexto($msg['criticidade']) ?>"><?php echo $msg['titulo'] ?></h4>
                                <h5><?php echo nl2br($msg['desc']) ?></h5>
                                <h6>Cadastrada por: <?php echo retUserMural($nomes, $msg['idUser']) ?> em <?php echo dataBdParaHtml($msg['data']) ?></h6>
                              </div>
                            </div>
                            <div class="tab-pane  p-20" id="det<?php echo $count ?>" role="tabpanel">
                              <div class="p-1">
                                <h6 class="m-b-15">Informações dessa mensagem:</h6>
                                <section style="font-size: 11px;">
                                  <span><b>Criticidade:</b> <?php echo retCriticidade($msg['criticidade']) ?></span><br>
                                  <span><b>Data de cadastro:</b> <?php echo dataBdParaHtml($msg['data']) ?></span><br>
                                  <span><b>Data de expiração:</b> <?php echo retExpMural($msg['expira']) ?></span><br>
                                  <span><b>Grupos alvo:</b> <?php echo retGruposMural($grupos, $msg['grupos']) ?></span><br>
                                  <span><b>Autor(a):</b> <?php echo retUserMural($nomes, $msg['idUser']) ?></span><br>
                                  <span><b>Editada por:</b> <?php echo retUserMural($nomes, $msg['idUserEdit']) ?></span>
                                </section>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                  }
                } ?>
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

<?php if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'success'){
  ?>
  swal("Opa, tudo certo!", "Uma nova mensagem foi cadastrada!", "success")
  <?php
} ?>
<?php if(isset($_GET['edit']) && $_GET['edit'] == 'success'){
  ?>
  swal("Feito!", "As informações da mensagem foram atualizadas!", "success")
  <?php
} ?>
<?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
  ?>
  swal("Feito!", "A mensagem foi apagada!", "success")
  <?php
} ?>

function setTrash(hash){
  swal({
    title: "Deseja mesmo apagar essa mensagem?",
    text: "Essa ação não poderá ser desfeita!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Sim, apagar!",
    cancelButtonText: "Cancelar",
    closeOnConfirm: true,
    closeOnCancel: true
  },
  function(isConfirm){
    if(isConfirm){
      $.ajax({
        type: "POST",
        data: {
          hash : hash
        },
        url: "../application/deletaMural",
        success: function(result){
          if(result == '1'){
            window.location.href = "mural?action=trashed&status=success";
          } else if(result == '0'){
            window.location.href = "mural?action=trashed&status=failure";
          }
        }
      });
    }
  });
}
</script>

</body>

</html>
