<?php
include '../core.php';
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
        <h3 class="padrao">Nova casa</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="casas">Casas</a></li>
          <li class="breadcrumb-item">Nova casa</li>
        </ol>
      </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
      <!-- Start Page Content -->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-title">
              <h4>Cadastro de nova casa</h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/cadastracasa" method="post">
                  <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome da casa</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="nome" id="inNome" required placeholder="Nome da casa">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome do responsável</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="responsavel" id="inResponsavel" required placeholder="Nome do responsável">
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Telefone</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control phone_with_ddd" name="telefone" id="inTelefone" placeholder="(   )      -">
                            </div>
                          </div>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Inserira um logo para a casa:</label>
                            <input type="hidden" value="" name="icon" id="inIcon">
                            <section id="icons">
                              <br>
                              <?php
                              for($i = 16; $i <= 22; $i++){ ?>
                                <div class="icon-wiki icon-wiki-opaco" onclick="setIcon('<?php echo $i; ?>')" id="icon<?php echo $i; ?>">
                                  <img src="assets/casas/<?php echo $i; ?>.png">
                                </div>
                                <?php
                              } ?>
                            </section>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /# column -->
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="casas">
                        <button type="button" class="btn btn-secondary">Voltar</button>
                      </a>
                      <button type="submit" class="btn btn-info" id="btn-send-form">Cadastrar</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- /# card -->
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

function clearImage(){
  var image_holder = $("#image-holder");
  image_holder.hide();
  image_holder.empty();
  document.getElementById('fileUpload').value="";
}

function setIcon(id){
  resetaOpacity();
  document.getElementById('inIcon').value = id;
  //document.getElementById('icon'+id).style.opacity = "1";
  $('#icon'+id).removeClass('icon-wiki-opaco');
  $('#icon'+id).addClass('icon-wiki-show');
}

function resetaOpacity(){
  for (var i = 1; i <= 22; i++) {
    //document.getElementById('icon'+i).style.opacity = "0.4";
    $('#icon'+i).removeClass('icon-wiki-show');
    $('#icon'+i).addClass('icon-wiki-opaco');
  }
}

function showIcons(){
  var atual = document.getElementById('icons').style.display;
  if(atual == 'none'){
    document.getElementById('icons').style.display = 'block';
  } else {
    document.getElementById('icons').style.display = 'none';
  }
}

$(document).ready(function(){
  $("#fileUpload").on('change', function () {

    if (typeof (FileReader) != "undefined") {

      var image_holder = $("#image-holder");
      image_holder.empty();

      var reader = new FileReader();
      reader.onload = function (e) {
        $("<img />", {
          "src": e.target.result,
          "class": "thumb-image"
        }).appendTo(image_holder);
      }
      image_holder.show();
      $("#image-holder").show();
      reader.readAsDataURL($(this)[0].files[0]);
    }
  });

  $('.phone_with_ddd').mask('(00) 0000-0000');

});

</script>
</body>

</html>
