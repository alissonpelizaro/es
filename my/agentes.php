<?php
include '../application/agentes.php';
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
                Agentes cadastrados
                <?php if($licenca->validaLicencaAgente()){ ?>
                  <span>
                    <a href="novoagente" class="btn btn-sm btn-info btn-new">
                      Novo agente
                    </a>
                  </span>
                <?php } ?>
              </h4>
              <div class="table-responsive m-t-10">
                <?php if(!$agentes){
                  ?>
                  <hr>
                  <center>
                    <h3 class="text-muted"><i>Nenhum agente cadastrado.</i></h3>
                  </center>
                  <?php
                } else { ?>
                  <table id="agentesTable" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th style="width: 50px;"></th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Ramal</th>
                        <th>CHAT</th>
                        <th>Grupos</th>
                        <th>Data de cadastro</th>
                        <th>Visto por último</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($agentes as $agente) {
                        ?>
                        <tr>
                          <td>
                            <div class="avatar-ball avatar-bd-<?php echo statusAgente($agente['logged'], $agente['ultimoRegistro']); ?>">
                              <img src="assets/avatar/<?php
                              if($agente['avatar'] == ""){
                                echo "default.jpg";
                              } else {
                                echo $agente['avatar'];
                              }
                              ?>">
                            </div>
                          </td>
                          <td><?php echo $agente['nome']." ".$agente['sobrenome']; ?></td>
                          <td><?php echo $agente['email'] ?></td>
                          <td><?php echo $agente['ramal'] ?></td>
                          <td><?php echo chatState($agente['chat']) ?></td>
                          <td><?php echo retArrayGrupo($grupos,$agente['idUser']); ?></td>
                          <td><?php echo dataBdParaHtml($agente['dataCadastro']) ?></td>
                          <td><?php
                          if($agente['ultimoRegistro'] == '1000-01-01 00:00:00'){
                            echo "Nunca";
                          } else {
                            echo dataBdParaHtml($agente['ultimoRegistro']);
                          }
                          ?>
                        </td>
                        <td>
                          <a href="editaagente?hash=<?php echo $token ?>&id=hidden&action=<?php echo $agente['idUser']*17 ?>&form=edit">
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
    swal("Opa, tudo certo!", "Um novo agente foi cadastrado!", "success");
    <?php
  } ?>

  <?php if(isset($_GET['edicao']) && $_GET['edicao'] == 'success'){
    ?>
    swal("Feito!", "As informações do agente foram atualizadas!", "success");
    <?php
  } ?>

  <?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
    ?>
    swal("Feito!", "O agente foi excluído!", "success");
    <?php
  } ?>
});
</script>
</body>

</html>
