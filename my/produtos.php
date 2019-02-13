<?php
include '../application/produtos.php';
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
        <h3 class="padrao">Produtos</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Produtos</li>
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
                Meus produtos
                <span>
                  <a href="novoproduto" class="btn btn-sm btn-info btn-new">
                    Novo produto
                  </a>
                </span>
              </h4>
              <div class="table-responsive m-t-10">
                <?php if(!$produtos){
                  ?>
                  <hr>
                  <center>
                    <h3 class="text-muted"><i>Nenhum produto cadastrado.</i></h3>
                  </center>
                <?php } else { ?>
                  <table id="promocoesTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Produto</th>
                        <th>Veiculação</th>
                        <th>Valor</th>
                        <th>Obs.</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $aux = 0;
                      foreach ($produtos as $prod) {
                        $aux++;
                        ?>
                        <tr>
                          <td><b><?php echo $aux; ?></b></td>
                          <td><?php echo $prod['produto']; ?></td>
                          <td><?php echo $prod['veiculacao']; ?></td>
                          <td><?php if($prod['valor'] != ""){ echo "R$ ".$prod['valor']; } ?></td>
                          <td><?php echo $prod['obs']; ?></td>
                            <td>
                              <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                  <i class="fa fa-bars" aria-hidden="true"></i>
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                  <li><a href="editaproduto?token=<?php echo 21 ?>&hash=<?php echo $prod['idProduto']*13; ?>&typography=inside">Editar produto</a></li>
                                  <li><a href="javascript: void()" onclick="setTrash('<?php echo $prod['idProduto']*13; ?>')">Excluir produto</a></li>
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
    $('#promocoesTable').DataTable();

    <?php if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'success'){
      ?>
      swal("Opa, feito!", "Um novo produto foi castrado!", "success")
      <?php
    } ?>
    <?php if(isset($_GET['edit']) && $_GET['edit'] == 'success'){
      ?>
      swal("Pronto!", "As informações do produto foram atualizadas!", "success")
      <?php
    } ?>
    <?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
      ?>
      swal("Feito!", "O produto foi excluído!", "success")
      <?php
    } ?>
  });

  function setTrash(selected){
    swal({
      title: "Deseja mesmo excluir esse  produto?",
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
          url: "../application/deletaproduto",
          success: function(result){
            if(result == '1'){
              window.location.href = "produtos?action=trashed&status=success";
            } else if(result == '0'){
              window.location.href = "produtos?action=trashed&status=failure";
            }
          }
        });
      }
    });
  }
</script>
</body>

</html>
