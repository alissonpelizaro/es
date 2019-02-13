<?php
include '../application/casas.php';
//Define nível de restrição da página
$allowUser = array('dev', 'coordenador', 'administrador');
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
        <h3 class="padrao">Casas</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Casas</li>
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
                Casas cadastradas
                <span>
                  <a href="novacasa" class="btn btn-sm btn-info btn-new">
                    Nova casa
                  </a>
                </span>
              </h4>
              <div class="table-responsive m-t-10">
                <?php if(!$casas){
                  ?>
                  <hr>
                  <center>
                    <h3 class="text-muted"><i>Nenhuma casa cadastrada.</i></h3>
                  </center>
                  <?php
                } else { ?>
                  <table id="agentesTable" class="table table-hover">
                    <thead>
                      <tr>
                        <th style="width: 50px;"></th>
                        <th>Nome</th>
                        <th>Responsável</th>
                        <th>Login</th>
                        <th>Telefone</th>
                        <th>Localização</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($casas as $casa) {
                        $gestor = getGestor($casa['idCasa']);

                        ?>
                        <tr>
                          <td>
                            <div class="avatar-casa">
                              <img src="assets/casas/<?php
                              if($casa['logo'] == ""){
                                echo "default.png";
                              } else {
                                echo $casa['logo'];
                              }
                              ?>">
                            </div>
                          </td>
                          <td><?php echo $casa['nome'] ?></td>
                          <td><?php echo $gestor['nome']." ".$gestor['sobrenome']; ?></td>
                          <td><?php echo $gestor['usuario'] ?></td>
                          <td><?php echo $casa['telefone'] ?></td>
                          <td><?php if($casa['estado'] != ""){ echo $casa['bairro']." - ".$casa['cidade'].", ".$casa['estado']; } ?></td>
                          <td>
                            <a href="editacasa?hash=ssl&id=hidden&action=<?php echo $casa['idCasa']*17 ?>&form=edit">
                              <button type="button" class="btn btn-secondary btn-sm"><i class="ti-pencil-alt"></i></button>
                            </a>
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
    swal("Opa, tudo certo!", "Uma nova casa foi cadastrada!", "success")
    <?php
  } ?>

  <?php if(isset($_GET['edicao']) && $_GET['edicao'] == 'success'){
    ?>
    swal("Feito!", "As informações da casa foram atualizadas!", "success")
    <?php
  } ?>

  <?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
    ?>
    swal("Feito!", "A casa foi excluída!", "success")
    <?php
  } ?>
});
</script>
</body>

</html>
