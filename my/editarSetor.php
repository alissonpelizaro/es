<?php
include '../application/editarSetor.php';
//Define nível de restrição da página
$allowUser = array('dev', 'coordenador');
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
        <h3 class="padrao">Editar setor</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="setores">Setores</a></li>
          <li class="breadcrumb-item">Editar fila</li>
        </ol>
      </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
      <!-- Start Page Content -->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-title">
              <h4>Editar setor <b><?php echo $setor['nome'] ?></b></h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" name="sendFormAgente" action="../application/atualizasetor" method="post">
                  <input type="hidden" name="hash" value="<?php echo $idSetor * 37; ?>">
                  <div class="btn-trash">
                    <button type="button" onclick="setTrash()" class="btn btn-sm btn-danger btn-outline"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                  </div>
                  <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome do setor</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" required name="nome" value="<?php echo $setor['nome'] ?>" id="inNome" placeholder="Nome">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                      <div class="form-check text-center"  style="padding-left: 10px; text-align: center;">
                        <label class="col-sm-12 m-t-20 control-label">Gerenciar módulos:</label>
                        <table style="width: 225px; margin: auto;">
                          <!--<tr>
                            <td class="p-b-5 p-t-5">Concessionárias:</td>
                            <td>
                              <input class="form-check-input" type="checkbox" <?php if(strpos($setor['modulos'], '-conc-') !== false){ echo "checked"; } ?> name="checkCons" id="checkCons" value="1">
                            </td>
                          </tr> -->
                          <tr>
                            <td class="p-b-5 p-t-5">Mural:</td>
                            <td>
                              <input class="form-check-input" type="checkbox" <?php if(strpos($setor['modulos'], '-mural-') !== false){ echo "checked"; } ?> name="checkMural" id="checkMural" value="1">
                            </td>
                          </tr>
                          <tr>
                            <td class="p-b-5 p-t-5">Broadcast:</td>
                            <td>
                              <input class="form-check-input" type="checkbox" <?php if(strpos($setor['modulos'], '-broad-') !== false){ echo "checked"; } ?> name="checkBroad" id="checkBroad" value="1">
                            </td>
                          </tr>
                          <tr>
                            <td class="p-b-5 p-t-5">Wiki:</td>
                            <td>
                              <input class="form-check-input" type="checkbox" <?php if(strpos($setor['modulos'], '-wiki-') !== false){ echo "checked"; } ?> name="checkWiki" id="checkWiki" value="1">
                            </td>
                          </tr>
                          <tr>
                            <td class="p-b-5 p-t-5">Redes sociais:</td>
                            <td>
                              <input class="form-check-input" type="checkbox" <?php if(strpos($setor['modulos'], '-media-') !== false){ echo "checked"; } ?> name="checkMedia" id="checkWiki" value="1">
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="setores">
                        <button type="button" class="btn btn-secondary">Voltar</button>
                      </a>
                      <button type="submit" class="btn btn-info" id="btn-send-form">Cadastrar</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- /# card -->
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
<script src="assets/js/lib/switcher/jquery.switcher.js"></script>
<script type="text/javascript">

function setTrash(){
  swal({
    title: "Deseja mesmo excluir esse setor?",
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
          hash : <?php echo $setor['idSetor']*37; ?>,
          nome : '<?php echo $setor['nome'] ?>',
          id : '<?php echo 10; ?>'
        },
        url: "../application/deletaSetor",
        success: function(result){
          if(result == '1'){
            window.location.href = "setores?action=trashed&status=success";
          } else if(result == '0'){
            window.location.href = "setores?action=trashed&status=failure";
          } else if(result == '3'){
            window.location.href = "setores?action=trashed&status=used";
          }
        }
      });
    }
  });
}


$(document).ready(function() {

  $(function(){
    $.switcher('input[type=checkbox]');
  });

});

</script>

</body>

</html>
