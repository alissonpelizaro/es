<?php
include '../application/tecnicos.php';
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
        <h3 class="padrao">Técnicos</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Técnicos</li>
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
                Meus técnicos
                <span>
                  <a href="novotecnico" class="btn btn-sm btn-info btn-new">
                    Novo técnico
                  </a>
                </span>
              </h4>
              <div class="table-responsive m-t-10">
                <?php if(!$tecnicos){
                  ?>
                  <hr>
                  <center>
                    <h3 class="text-muted"><i>Nenhum técnico cadastrado.</i></h3>
                  </center>
                  <?php
                } else { ?>
                  <table id="agentesTable" class="table table-hover">
                    <thead>
                      <tr>
                        <th style="width: 50px;"></th>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>Data de cadastro</th>
                        <th>E-mail</th>
                        <th>Horário de trabalho</th>
                        <th>Dias trabalhados</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($tecnicos as $tec) {
                        $obs = setObs($tec['filas']);
                        ?>
                        <tr>
                          <td>
                            <div class="avatar-casa">
                              <img src="assets/avatar/<?php
                              if($tec['avatar'] == ""){
                                echo "default.jpg";
                              } else {
                                echo $tec['avatar'];
                              }
                              ?>">
                            </div>
                          </td>
                          <td><?php echo $tec['nome']." ".$tec['sobrenome'] ?></td>
                          <td><?php echo $tec['usuario']; ?></td>
                          <td><?php echo dataBdParaHtml($tec['dataCadastro']) ?></td>
                          <td><?php echo $tec['email']; ?></td>
                          <td><?php echo $obs['entrada']." - ".$obs['saidaAlmoco']." - ".$obs['entradaAlmoco']." - ".$obs['saida']; ?></td>
                          <td><?php echo $obs['dias'] ?></td>
                          <td>
                            <a href="editatecnico?hash=ssl&id=hidden&action=<?php echo $tec['idUser']*17 ?>&form=edit">
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
    swal("Opa, tudo certo!", "Um novo técnico foi cadastrado!", "success");
    <?php
  } ?>

  <?php if(isset($_GET['edicao']) && $_GET['edicao'] == 'success'){
    ?>
    swal("Feito!", "As informações do técnico foram atualizadas!", "success");
    <?php
  } ?>

  <?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
    ?>
    swal("Feito!", "O técnico foi excluído!", "success");
    <?php
  } ?>
});
</script>
</body>

</html>
