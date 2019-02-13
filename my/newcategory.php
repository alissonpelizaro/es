<?php
include '../application/wiki.php';
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
        <h3 class="padrao">Nova categoria<i>Wiki</i></h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="wikimanager">MyOmni<i>Wiki</i></a></li>
          <li class="breadcrumb-item"><a href="categories">Categorias<i>Wiki</i></a></li>
          <li class="breadcrumb-item">Nova categoria<i>Wiki</i></li>
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
              <div class="jumbotron j-relative">
                <div class="container">
                  <div class="btn-group btn-group-wiki">
                    <div class="btn-group dropleft">
                      <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="novawiki">Nova wiki</a></li>
                          <div class="dropdown-divider"></div>
                          <li><a href="wikicategories">Ver categorias</a></li>
                          <li><a href="newcategory">Nova categoria</a></li>
                        </ul>
                      </div>
                    </div>
                    <h1 class="display-6"><i class="fa fa-book" aria-hidden="true"></i> MyOmni<i>Wiki<sub style="font-size: 25px;">editor</sub></i></h1>
                  </div>
                  <br>
                  <hr style="width: 80%;">
                  <div class="row">
                    <div class="col-md-6 offset-md-3">
                      <h2>Cadastro de nova categoria</h2>
                      <form class="enterness" action="../application/novaCategoria" method="post">
                        <input type="text" class="form-control" placeholder="Ex.: Agendamento" name="categoria" required maxlength="90">
                        <br>
                        <div class="row">
                          <div class="center">
                            <a href="wikicategories">
                              <button type="button" class="btn btn-secondary">Voltar</button>
                            </a>
                            <button type="submit" class="btn btn-info">Cadastrar</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End PAge Content -->
      </div>
      <!-- End Container fluid  -->
      <div class="modal fade" id="modalEditaCategoria" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle">Editar categoria</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="reload()" style="margin-top: -20px; margin-right: -20px;">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form class="enterness" action="../application/editaCategoria" method="post">
              <div class="modal-body">
                <div class="form-group">
                  <label class="junta">Categoria</label>
                  <input type="text" maxlength="90" required name="categoria" id="inCategoria" class="form-control" placeholder="Nome da categoria" value="">
                  <input type="hidden" name="hash" id="inHash" value="">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reload()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
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

  function reload(){
    window.location.href="wikicategories";
  }

  function setMotalEdit(hash, cat){
    document.getElementById('inHash').value = hash;
    document.getElementById('inCategoria').value = cat;
  }

  </script>
</body>

</html>
