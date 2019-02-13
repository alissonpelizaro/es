<?php
include '../application/detwiki.php';
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
          <li class="breadcrumb-item"><a href="wiki">MyOmni<i>Wiki</a></i></li>
          <li class="breadcrumb-item">Detalhamento da <i>Wiki</i></li>
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
                <?php if($wiki['logo'] != ""){ ?>
                  <img src="assets/images/icons/<?php echo $wiki['logo']; ?>.png" class="icon-det-wiki">
                <?php } ?>
                <div class="" <?php if($wiki['logo'] != ""){ ?>style="padding-left: 160px;"<?php } ?>>
                  <?php if($_SESSION['tipo'] != 'agente' && $_SESSION['tipo'] != 'supervisor'){ ?>
                    <div class="btn-group btn-group-wiki">
                      <div>
                        <button type="button" onclick="setTrash()" class="btn btn-sm btn-danger btn-outline">
                          <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                        <a href="novawiki?hash=<?php echo $wiki['idWiki']*313 ?>&action=edit">
                          <button type="button" class="btn btn-sm btn-secondary">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                          </button>
                        </a>
                      </div>
                    </div>
                  <?php } ?>
                  <h1 class="display-6"><i class="fa fa-quote-left" aria-hidden="true"></i> <?php echo $wiki['titulo'] ?><i><sub style="font-size: 25px;">Wiki</sub></i></h1>
                  <p class="lead"><i><?php echo $wiki['subtitulo'] ?></i></p>
                </div>
                <hr style="width: 80%;">
                <div class="bg-light box-wiki padroniza-wiki">
                  <span><?php echo html_entity_decode($wiki['conteudo']) ?></span>
                  <br>
                  <p class="text-muted float-right" style="font-size: 12px;"><i>Autor(a): <b><?php echo $autor ?></b></i></p>
                </div>
                <br>
                <div class="row">
                  <div class="center">
                    <a href="wiki">
                      <button type="button" class="btn btn-secondary">Voltar</button>
                    </a>
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
    <?php include 'inc/footer.php'; ?>
  </div>
  <!-- End Page wrapper  -->
</div>
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">

function setTrash(){
  swal({
    title: "Deseja mesmo excluir essa postagem?",
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
          hash : <?php echo $wiki['idWiki']*23; ?>
        },
        url: "../application/deletaWiki",
        success: function(result){
          if(result == '1'){
            window.location.href = "wiki?action=trashed&status=success";
          } else if(result == '0'){
            window.location.href = "wiki?action=trashed&status=failure";
          }
        }
      });
    }
  });
}
</script>
</body>

</html>
