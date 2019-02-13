<?php
include '../application/grupos.php';
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
        <h3 class="padrao">Grupos</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Grupos</li>
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
                Grupos de agentes
                <span>
                  <button class="btn btn-sm btn-info btn-new" onclick="novoGrupo()">
                    Novo grupo
                  </button>
                </span>
              </h4>
              <div class="table-responsive m-t-10">
                <?php if(!$grupos){
                  ?>
                  <hr>
                  <center>
                    <h3 class="text-muted"><i>Nenhum grupo de agentes foi criado.</i></h3>
                  </center>
                  <?php
                } else { ?>
                  <table class="table table-bordered table-hover table-striped">
                    <thead>
                      <tr>
                        <th style="width: 40px;">#</th>
                        <th>Nome</th>
                        <th>Data de cadastro</th>
                        <th>Cadastrado por</th>
                        <th>Agentes</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $aux = 1;
                      foreach ($grupos as $grupo) {
                        ?>
                        <tr>
                          <td><?php echo $aux; $aux++; ?></td>
                          <td><?php echo $grupo['nome']; ?></td>
                          <td><?php echo dataBdParaHtml($grupo['dataCadastro']) ?></td>
                          <td><?php echo pegaUsuario($db, $grupo['idSupervisor']) ?></td>
                          <td><?php echo retQtdGrupo($grupo['agentes']); ?></td>
                          <td>
                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#editGroup" onclick="setModal('<?php echo $grupo['nome'] ?>', '<?php echo $grupo['idGrupo']*37 ?>')"><i class="ti-pencil-alt"></i></button>
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
    <div class="modal fade" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar grupo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px; margin-right:-20px;" onclick="window.location.href='grupos'">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="enterness" action="../application/atualizagrupo" method="post">
            <div class="modal-body">
              <div class="form-group">
                <label for="nomeModal" class="junta">Nome do grupo</label>
                <input type="text" class="form-control" id="nomeModal" name="nome" aria-describedby="nomeModal" placeholder="Nome do grupo" required>
              </div>
              <input type="hidden" name="hash" id="hashModal">
            </div>
            <div class="modal-footer">
              <span class="btn btn-danger btn-outline" data-dismiss="modal" onclick="setTrash()" style="position: absolute; bottom: 0; left: 0; margin: 15px;"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
              <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='grupos'">Fechar</button>
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
    title: "Deseja mesmo excluir esse grupo?",
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
        url: "../application/deletaGrupo",
        success: function(result){
          if(result == '1'){
            window.location.href = "grupos?action=trashed&status=success";
          } else if(result == '0'){
            window.location.href = "grupos?action=trashed&status=failure";
          }
        }
      });
    }
  });
}

function setModal(nome, hash){
  document.getElementById('nomeModal').value = nome;
  document.getElementById('hashModal').value = hash;
  selected = hash;
}

function novoGrupo(){
  swal({
    title: "Novo grupo...",
    text: "Qual será o nome desse grupo?",
    type: "input",
    showCancelButton: true,
    closeOnConfirm: false,
    confirmButtonColor: "#2E64FE",
    animation: "slide-from-top",
    inputPlaceholder: "Dê um nome ao novo grupo"
  },
  function(inputValue){
    if (inputValue === false) return false;
    if (inputValue === "") {
      swal.showInputError("Você precisa dar um nome para o grupo!");
      return false
    } else {
      $.ajax({
        type: "POST",
        data: {
          value : inputValue
        },
        url: "../application/novoGrupo",
        success: function(result){
          if(result == '1'){
            swal({
              title: "Feito!",
              text: "Um novo grupo foi criado.",
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "green",
              confirmButtonText: "Ok",
              closeOnConfirm: false
            },
            function(){
              window.location.href = "grupos";
            });
          } else {
            swal({
              title: "Opa!",
              text: "Algo deu errado ao criar o grupo. Tente novamente.",
              type: "error",
              showCancelButton: false,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Ok",
              closeOnConfirm: false
            },
            function(){
              window.location.href = "grupos";
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
    swal("Opa, tudo certo!", "Um novo grupo foi cadastrado!", "success")
    <?php
  } ?>
  <?php if(isset($_GET['edicao']) && $_GET['edicao'] == 'success'){
    ?>
    swal("Feito!", "As informações do grupo foram atualizadas!", "success")
    <?php
  } ?>

  <?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
    ?>
    swal("Feito!", "O grupo foi excluído!", "success")
    <?php
  } ?>
});


</script>
</body>

</html>
