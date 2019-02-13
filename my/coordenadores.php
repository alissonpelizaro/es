<?php
include '../application/coordenadores.php';

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
        <h3 class="padrao">Coordenadores</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Coordenadores</li>
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
                Coordenadores cadastrados
                <?php if($licenca->validaLicencaCoordenador()){ ?>
                  <span>
                    <a href="novousuario?token=<?php echo $token; ?>" class="btn btn-sm btn-info btn-new">
                      Novo coordenador
                    </a>
                  </span>
                <?php } ?>
              </h4>
              <div class="table-responsive m-t-10">
                <?php if(!$users){
                  ?>
                  <hr>
                  <center>
                    <h3 class="text-muted"><i>Nenhum coordenador cadastrado.</i></h3>
                  </center>
                  <?php
                } else { ?>
                  <table id="agentesTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style="width: 50px;"></th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Data de cadastro</th>
                        <th>Visto por último</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($users as $user) {
                        ?>
                        <tr>
                          <td>
                            <div class="avatar-ball avatar-bd-<?php echo statusAgente($user['logged'], $user['ultimoRegistro']); ?>">
                              <img src="assets/avatar/<?php
                              if($user['avatar'] == ""){
                                echo "default.jpg";
                              } else {
                                echo $user['avatar'];
                              }
                              ?>">
                            </div>
                          </td>
                          <td><?php echo $user['nome']." ".$user['sobrenome']; ?></td>
                          <td><?php echo $user['email'] ?></td>
                          <td><?php echo dataBdParaHtml($user['dataCadastro']) ?></td>
                          <td><?php
                          if($user['ultimoRegistro'] == '1000-01-01 00:00:00'){
                            echo "Nunca";
                          } else {
                            echo dataBdParaHtml($user['ultimoRegistro']);
                          }
                          ?>
                        </td>
                        <td>
                          <a href="editausuario?hash=<?php echo $token ?>&id=hidden&action=<?php echo $user['idUser']*17 ?>&form=edit">
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
    swal("Opa, tudo certo!", "Um novo coordenador foi cadastrado!", "success")
    <?php
  } ?>
  <?php if(isset($_GET['edicao']) && $_GET['edicao'] == 'success'){
    ?>
    swal("Feito!", "As informações do coordenador foram atualizadas!", "success")
    <?php
  } ?>

  <?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
    ?>
    swal("Feito!", "O coordenador foi excluído!", "success")
    <?php
  } ?>
});
</script>
</body>

</html>
