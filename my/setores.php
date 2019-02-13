<?php
include '../application/setores.php';
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
        <h3 class="padrao">Setores</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Setores</li>
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
                Setores
                <span>
                  <button class="btn btn-sm btn-info btn-new" onclick="novoSetor()">
                    Novo setor
                  </button>
                </span>
              </h4>
              <div class="table-responsive m-t-10">
                <?php if(!$setores){
                  ?>
                  <hr>
                  <center>
                    <h3 class="text-muted"><i>Nenhum setor foi criado.</i></h3>
                  </center>
                  <?php
                } else { ?>
                  <table class="table table-bordered table-hover table-striped">
                    <thead>
                      <tr>
                        <th style="width: 40px;">#</th>
                        <th>Departamento</th>
                        <th>Data de cadastro</th>
                        <th>Cadastrado por</th>
                        <th>Usuários</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $aux = 1;
                      foreach ($setores as $setor) {
                        ?>
                        <tr>
                          <td><?php echo $aux; $aux++; ?></td>
                          <td><?php echo $setor['nome']; ?></td>
                          <td><?php echo dataBdParaHtml($setor['dataCadastro']) ?></td>
                          <td><?php echo pegaUsuario($db, $setor['idSupervisor']) ?></td>
                          <td><?php echo retQtdSetor($db, $setor['idSetor']) ?></td>
                          <td>
                            <a href="editarSetor?hash=<?php echo $setor['idSetor']*311 ?>">
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
      <!-- End Page Content -->
    </div>
    <!-- End Container fluid  -->
    <div class="modal fade" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar setor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px; margin-right:-20px;" onclick="window.location.href='setores'">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="enterness" action="../application/atualizasetor" method="post">
            <div class="modal-body">
              <div class="form-group">
                <label for="nomeModal" class="junta">Nome do grupo</label>
                <input type="text" class="form-control" id="nomeModal" name="nome" aria-describedby="nomeModal" placeholder="Nome do grupo" required>
                <br>
                <div class="form-group text-center">
                  <label class="col-sm-12 control-label">Gerenciar módulos:</label>
                  <div class="col-sm-12">
                    <div class="form-check text-center"  style="padding-left: 10px; text-align: center;">
                      <table style="width: 50%; margin: auto;">
                        <tr>
                          <td>Concessionárias:</td>
                          <td>
                            <input class="form-check-input" type="checkbox" name="checkCons" id="checkCons" value="1">
                          </td>
                        </tr>
                        <tr>
                          <td>Mural:</td>
                          <td>
                            <input class="form-check-input" type="checkbox" name="checkMural" id="checkMural" value="1">
                          </td>
                        </tr>
                        <tr>
                          <td>Broadcast:</td>
                          <td>
                            <input class="form-check-input" type="checkbox" name="checkBroad" id="checkBroad" value="1">
                          </td>
                        </tr>
                        <tr>
                          <td>Wiki:</td>
                          <td>
                            <input class="form-check-input" type="checkbox" name="checkWiki" id="checkWiki" value="1">
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" name="hash" id="hashModal">
            </div>
            <div class="modal-footer">
              <span class="btn btn-danger btn-sm btn-outline" data-dismiss="modal" onclick="setTrash()" style="position: absolute; bottom: 0; left: 0; margin: 15px;"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
              <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='setores'">Fechar</button>
              <button type="submit" class="btn btn-info">Salvar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php include 'inc/footer.php'; ?>
  </div>
  <!-- End Page wrapper  -->
</div>
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">

var selected = "";

function setTrash(){
  swal({
    title: "Deseja mesmo excluir esse setor?",
    text: "Essa ação não poderá ser desfeita!",
    type: "warning",
    showCancelButton: true,
    confirmButtonText: "Sim, excluir!",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#DD6B55",
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

function novoSetor(){
  swal({
    title: "Novo setor",
    text: "Qual será o nome desse setor?",
    type: "input",
    showCancelButton: true,
    closeOnConfirm: false,
    confirmButtonColor: "#2E64FE",
    animation: "slide-from-top",
    inputPlaceholder: "Dê um nome ao novo setor"
  },
  function(inputValue){
    if (inputValue === false) return false;
    if (inputValue === "") {
      swal.showInputError("Você precisa dar um nome para o setor!");
      return false
    } else {
      $.ajax({
        type: "POST",
        data: {
          value : inputValue
        },
        url: "../application/novoSetor",
        success: function(result){
          if(result == '1'){
            swal({
              title: "Feito!",
              text: "Um novo setor foi criado.",
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "green",
              confirmButtonText: "Ok",
              closeOnConfirm: false
            },
            function(){
              window.location.href = "setores";
            });
          } else {
            swal({
              title: "Opa!",
              text: "Algo deu errado ao criar o setor. Tente novamente.",
              type: "error",
              showCancelButton: false,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Ok",
              closeOnConfirm: false
            },
            function(){
              window.location.href = "setores";
            });
          }
        }
      });
    }
  });
}

$(document).ready(function() {

  $('#agentesTable').DataTable();

  <?php if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'success'){
    ?>
    swal("Opa, tudo certo!", "Um novo grupo foi cadastrado!", "success");
    <?php
  } ?>
  <?php if(isset($_GET['edicao']) && $_GET['edicao'] == 'success'){
    ?>
    swal("Feito!", "As informações do grupo foram atualizadas!", "success");
    <?php
  } ?>

  <?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
    ?>
    swal("Feito!", "O grupo foi excluído!", "success");
    <?php
  } ?>
  <?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'used'){
    ?>
    swal("Opa!", "Esse setor ainda possui usuários ativos!", "warning");
    <?php
  } ?>
});


</script>
</body>

</html>
