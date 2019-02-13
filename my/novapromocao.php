<?php
include '../core.php';
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
        <h3 class="padrao">Nova promoção</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="promocoes">Promoções</a></li>
          <li class="breadcrumb-item">Nova promoção</li>
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
              <h4>Cadastro de nova promoção</h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/cadastrapromocao" method="post">
                  <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">* Descrição da promoção</label>
                            <div class="col-sm-12">
                              <textarea class="form-control" name="promocao" id="inPromocao" required placeholder="Descreva a promoção" style="resize: vertical"></textarea>
                              <span class="help-block">
                                <small><i id="alertTextValor">Tente ser breve, isso ajuda o agente a compartilhar sua promoção com os clientes durante um atendimento</i></small>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Valor</label>
                            <div class="col-sm-12 input-group">
                              <div class="input-group-prepend">
                                <div class="input-group-text p-t-2 p-b-2">R$</div>
                              </div>
                              <input type="text" class="form-control real" name="valor" id="inValor" placeholder="0,00">
                              <span class="help-block">
                                <small><i id="alertTextValor">O valor único de uma promoção não é obrigatório, mas facilita bastante a visualiação para o agente durante um atendimento. Para adicionar multiplos valores ou valores customizados, pode-se utilizar o campo "Descrição"</i></small>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Veiculação</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="veiculacao" id="inVeiculacao" placeholder="Marcas e modelos aceitos na promoção">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <div class="custom-control custom-checkbox" style="margin-left: 15px;">
                              <input type="checkbox" class="custom-control-input" id="checkAlarm">
                              <label class="custom-control-label" for="checkAlarm">Essa promoção tem validade</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row" id="sessaoDataIn" style="display: none;">
                        <div class="col-sm-8">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Data da expiração:</label>
                            <div class="col-sm-12">
                              <input type='text' onkeyup="limpaInput()" id="dataValidade" value="" name="validade" class="form-control datepicker-here" data-language='pt' data-position="top center" placeholder="Defina a data de expiração"/>
                              <span class="help-block">
                                <small><i id="alertTextValor">A promoção será automaticamente excluída na data selecionada.</i></small>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12 form-group">
                          <label class="control-label junta">Observações:</label>
                          <textarea class="form-control" name="obs" placeholder="Observações" style="resize: vertical; height: 90px;"></textarea>
                        </div>
                      </div>
                    </div>
                    <!-- /# column -->
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="promocoes">
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

$(document).ready(function(){

  $( "#checkAlarm" ).change(function() {
    if(this.checked){
      $("#sessaoDataIn").show();
      document.getElementById('dataInLembrete').required = true;
    } else {
      $("#sessaoDataIn").hide();
      document.getElementById('dataInLembrete').required = false;
      limpaInput();
    }
  });

  function limpaInput(){
    document.getElementById('dataInLembrete').value = "";
  }

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
  $('.real').mask('000.000.000,00', {reverse:true});


});

</script>
</body>

</html>
