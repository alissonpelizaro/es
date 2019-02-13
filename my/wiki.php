<?php
include '../application/wiki.php';
//Define nível de restrição da página
$allowUser = array('dev', 'coordenador', 'administrador', 'supervisor', 'agente');
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
        <h3 class="padrao">MyOmni<i>Wiki</i></h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">MyOmni<i>Wiki</i></li>
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
                  <?php if($_SESSION['tipo'] != 'agente' && $_SESSION['tipo'] != 'supervisor'){ ?>
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
                    <?php } ?>
                    <h1 class="display-6"><i class="fa fa-book" aria-hidden="true"></i> MyOmni<i>Wiki<sub style="font-size: 25px;">editor</sub></i></h1>
                  </div>
                  <br>
                  <hr style="width: 80%;">
                  <div class="row">
                    <div class="col-md-6 offset-md-3">
                      <form class="enterness" action="wiki" method="post">
                        <div class="input-group input-group-flat">
                          <input type="text" placeholder="Encontre algo..." autofocus value="<?php if(isset($_POST['filtro'])){ echo tratarString($_POST['filtro']); } ?>" name="filtro" class="form-control">
                          <span class="input-group-btn"><button class="btn btn-sm btn-info p-t-7" style="height: 30px;" type="submit"><i class="ti-search"></i></button></span>
                        </div>
                        <br>

                      </form>
                    </div>
                  </div>

                  <?php if(!$categorias){
                    ?>
                    <h3><i>Nada foi publicado na Wiki ainda</i></h3>
                    <?php
                  } else {
                    foreach ($categorias as $cat) {
                      $wikis = retArrayWikis($db, $cat['idCat'], $filtro);
                      if($wikis){
                        ?>
                        <div class="list-group">
                          <div class="list-group-item flex-column align-items-start bg-secondary">
                            <div class="d-flex w-100 justify-content-between">
                              <h3 class="m-0 text-light"><?php echo $cat['nomeCat'] ?></h3>
                            </div>
                          </div>
                          <?php
                          foreach ($wikis as $wiki) {
                            ?>
                            <a <?php if($wiki['logo'] != ""){ ?>style="padding-left: 80px;"<?php } ?> href="detwiki?hash=<?php echo $wiki['idWiki']*311; ?>" class="list-group-item list-group-item-action flex-column align-items-start">
                              <?php if($wiki['logo'] != ""){ ?>
                                <img class="icon-rel-wiki" src="assets/images/icons/<?php echo $wiki['logo']?>.png">
                              <?php } ?>
                              <div class="d-flex w-100 justify-content-between">
                                <h4 class="mb-1"><?php echo $wiki['titulo'] ?></h4>
                                <small class="text-muted" style="text-align: right; margin-top: -10px; margin-right: -10px;">Data de cadastro: <?php
                                echo dataBdParaHtml($wiki['dataCadastro']).".";
                                if($wiki['dataEdicao'] != '1000-01-01 00:00:00'){
                                  echo "<br> Editada por ".$wiki['nomeEdicao']." em: ". dataBdParaHtml($wiki['dataEdicao']).".";
                                } ?></small>
                              </div>
                              <p class="mb-0"><?php echo $wiki['subtitulo'] ?></p>
                            </a>
                            <?php
                          }
                        }
                      } ?>
                    </div>
                    <br><br>
                    <?php
                  } ?>
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
  <?php if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'success'){
    ?>
    swal("Feito!", "Uma nova Wiki foi cadastrada!", "success")
    <?php
  } ?>
  <?php if(isset($_GET['edit']) && $_GET['edit'] == 'success'){
    ?>
    swal("Sucesso!", "A Wiki foi editada!", "success")
    <?php
  } ?>
  <?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
    ?>
    swal("Pronto!", "A Wiki foi excluida!", "success")
    <?php
  } ?>
</script>
</body>

</html>
