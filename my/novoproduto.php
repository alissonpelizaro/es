<?php
include '../core.php';
//Define nível de restrição da página
$allowUser = array('dev', 'gestor');
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
        <h3 class="padrao">Novo produto</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="produtos">Produtos</a></li>
          <li class="breadcrumb-item">Novo produto</li>
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
              <h4>Cadastro de novo produto</h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/cadastraproduto" method="post">
                  <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Produto</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="produto" id="inProduto" required placeholder="Nome do produto">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Valor do produto (un)</label>
                            <div class="col-sm-12 input-group">
                              <div class="input-group-prepend">
                                <div class="input-group-text p-t-2 p-b-2">R$</div>
                              </div>
                              <input type="text" class="form-control real" name="valor" id="inValor" required placeholder="0,00">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Veiculação</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="veiculacao" id="inVeiculacao" placeholder="Marcas e modelos compatíveis com o produto">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Observação</label>
                            <div class="col-sm-12">
                              <textarea type="text" class="form-control" name="obs" id="inObs" placeholder="Observação" style="resize: vertical; height: 60px;"></textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /# column -->
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="promocoes">
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
<script type="text/javascript">

$(document).ready(function(){

  $('.real').mask('000.000.000,00', {reverse:true});

});

</script>
</body>

</html>
