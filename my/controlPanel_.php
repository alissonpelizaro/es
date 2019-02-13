<?php
include '../application/controlPanel.php';
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
        <h3 class="padrao">Configurações<i>MyOmni</i></h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Configurações</li>
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
              <h4 class="card-title text-center">Configurações <i>MyOmni</i>:</h4>
              <div class="row">
                <div class="col-lg-12">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#generals" aria-selected="true" role="tab">Geral</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" id="whatsAppTab" href="#midias" role="tab">Mídias sociais</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" id="monitorTab" href="#monitor" role="tab">Monitor</a></li>
                    <li class="nav-item hide"><a class="nav-link" data-toggle="tab"  href="#integrations" role="tab">Integrações</a></li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <!--second tab-->
                    <div class="tab-pane active show" id="generals" role="tabpanel">
                      <div class="card-body">
                        <form class="enterness" enctype="multipart/form-data" action="controlPanel" method="post">
                          <input type="hidden" name="tab" value="generals">
                          <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                              <div class="row m-t-20" style="border-left: 2px solid #1976C9;">
                                <p class="m-0 m-t-10 m-l-20 padrao">Configurações do DAM</p>
                                <div class="col-12">
                                  <div class="form-group">
                                    <label class="col-sm-12 control-label junta">Priorizar os atendimentos novos para:</label>
                                    <div class="col-sm-12">
                                      <select class="form-control" name="prior">
                                        <option value="0"<?php echo $conf->prioridade == 0 ? " selected" : "" ?>>Qualquer agente disponivel na fila (recomendado)</option>
                                        <option value="1"<?php echo $conf->prioridade == 1 ? " selected" : "" ?>>O agente a mais tempo sem atender</option>
                                        <option value="2"<?php echo $conf->prioridade == 2 ? " selected" : "" ?>>O agente com menos atendimentos no dia</option>
                                        <option value="3"<?php echo $conf->prioridade == 3 ? " selected" : "" ?>>O agente com mais atendimentos no dia</option>
                                        <option value="4"<?php echo $conf->prioridade == 4 ? " selected" : "" ?>>O agente com menos atendimentos ativos</option>
                                        <option value="5"<?php echo $conf->prioridade == 5 ? " selected" : "" ?>>O agente com mais atendimentos ativos</option>
                                        <option value="6"<?php echo $conf->prioridade == 6 ? " selected" : "" ?>>O agente que efetuou atendimentos anteriores ao cliente</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12">
                                  <div class="form-group">
                                    <label class="col-sm-12 control-label junta">Auto-transferir os atendimentos:</label>
                                    <div class="col-sm-12">
                                      <select class="form-control" name="transf" disabled>
                                        <option value="0"<?php echo $conf->transf == 0 ? " selected" : "" ?>>Nunca auto-transferir</option>
                                        <option value="1"<?php echo $conf->transf == 1 ? " selected" : "" ?>>Caso o agente saia do sistema e não retorne dentro de 1 minuto</option>
                                        <option value="2"<?php echo $conf->transf == 2 ? " selected" : "" ?>>Caso o agente fique mais de 30 min sem responder o ciente</option>
                                        <option value="3"<?php echo $conf->transf == 3 ? " selected" : "" ?>>Caso o agente fique mais de 1 hora sem responder o cliente</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="form-group">
                                    <label class="col control-label junta">Mensagem de boas-vindas:</label>
                                    <div class="col-sm">
                                      <textarea class="form-control" id="boasvindasTextarea" name="boas-vindas" maxlength="250" style="height: 100px" placeholder="Olá, seja bem-vindo ao canal de atendimento Enterness."><?php echo $conf->saudacao ?></textarea>
                                      <small><i>Coloque entre * para deixar a mensagem em <b>negrito</b> ao ser enviada para o cliente</i></small>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="form-group">
                                    <label class="col control-label junta">Pré-visualização da URA:</label>
                                    <div class="col-sm">
                                      <div class="body-preview" id="boasvindasPreview">
                                        <?php echo $conf->saudacao != "" ? $conf->saudacao."<br><br>" : "" ?>
                                        Sobre qual assunto deseja falar?
                                        <br>Responda com o <b>número</b> da opção desejada:
                                        <br><br>
                                        <b>1</b> - Fila um<br>
                                        <b>2</b> - Fila dois
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-12">
                                  <center>
                                    <button type="submit" class="btn btn-info">Salvar</button>
                                  </center>
                                </div>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="tab-pane" id="midias" role="tabpanel">
                      <div class="card-body">
                        <form class="enterness" enctype="multipart/form-data" action="controlPanel" method="post">
                          <input type="hidden" name="tab" value="midias">
                          <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                              <div class="m-t-20" style="border-left: 2px solid rgb(82, 190, 102);">
                                <p class="m-0 m-t-10 m-l-20 text-verde">WhatsApp</p>
                                <div class="row">
                                  <div class="col-6 hide" id="whatsAppBody">
                                    <div class="form-group">
                                      <label class="col control-label junta">Status do WhatsApp:</label>
                                      <div class="col-sm">
                                        <p class="h3 text-verde hide" id="messageWhatsAppOk">
                                          <i class="fa fa-check-circle" aria-hidden="true"></i>
                                          <br>
                                          Sincronismo Ok
                                        </p>
                                        <p class="h3 text-danger hide" id="messageWhatsAppErro">
                                          <i class="fa fa-times-circle" aria-hidden="true"></i>
                                          <br>
                                          Não sincronizado
                                        </p>
                                        <p class="h3 text-danger hide" id="messageWhatsAppApiFailure">
                                          <i class="fa fa-times-circle" aria-hidden="true"></i>
                                          <br>
                                          API inoperante
                                        </p>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 text-center" id="loadingWhatsAppBody">
                                    <img src="assets/images/loading.gif" alt="Carregando...">
                                    <p><i>Checando integração com WhatsApp...</i></p>
                                  </div>
                                  <div class="col-6 hide" id="resincronizarWhatsAppBody">
                                    <div class="form-group">
                                      <label class="col control-label junta">Re-sincronizar:</label>
                                      <div class="col-sm">
                                        <div class="" id="divQRCodeWhatsAppLoading">
                                          <img src="assets/images/loading_round.gif" height="150px" alt="Carregando QR Code...">
                                        </div>
                                        <p class="text-danger italic hide" id="QrCodeErroMessage">Erro ao capturar o QRCode.</p>
                                        <div class="body-preview" id="divQRCodeWhatsApp">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6 offset-lg-3">
                              <div class="m-t-20" style="border-left: 2px solid #1976C9;">
                                <p class="m-0 m-t-10 m-l-20 padrao">Enterness</p>
                                <div class="row">
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label class="col control-label junta">Status do Orquestrador Enterness:</label>
                                      <div class="col-sm">
                                        <p class="h3 text-verde">
                                          <i class="fa fa-check-circle" aria-hidden="true"></i>
                                          <br>
                                          Sincronismo Ok
                                        </p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="tab-pane" id="integrations" role="tabpanel">
                      <div class="card-body">
                      </div>
                    </div>
                    <div class="tab-pane" id="monitor" role="tabpanel">
                    </div>
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
<script src="assets/js/lib/qrcodejs/qrcode.js"></script>
<script type="text/javascript">

$(document).ready(function(){

  $("#monitorTab").click(function (){
    var contentMonitor = '<iframe src="../thirdy/probe/p.php" style="width: 100%; height: 500px;"></iframe>';

    $("#monitor").html(contentMonitor);
  });

  $("#boasvindasTextarea").keyup(function(){
    var content = $(this).val();
    if(content != ""){
      content = content + "<br><br>";
    }
    content = content + "Sobre qual assunto deseja falar?<br>Responda com o <b>número</b> da opção desejada:<br><br><b>1</b> - Fila um<br><b>2</b> - Fila dois";
    $("#boasvindasPreview").html(content);
  });
});

$('#whatsAppTab').click(function(){
  startWhatsAppTest();
});

function startWhatsAppTest(){

    resetBodyWhatsApp();
    $("#loadingWhatsAppBody").fadeIn(1000);

    var whatsAppSettings = {
      "url": "../application/whatsAppControl"
    }

    var client_id = "<?php echo $config->getUserApi() ?>";

    $.ajax(whatsAppSettings).done(function (response) {
      response = JSON.parse(response);

      $("#whatsAppBody").show();
      if(response['status'] == 0){
        //Api inoperante
        $("#loadingWhatsAppBody").hide();
        $("#messageWhatsAppApiFailure").fadeIn(600);

      } else if(response['status'] == 1){
        //Sincronismo ok
        $("#loadingWhatsAppBody").hide();
        $("#messageWhatsAppOk").fadeIn(600);

      } else if(response['status'] == 2){
        //Não sincronizado
        $("#loadingWhatsAppBody").hide();
        $("#messageWhatsAppErro").fadeIn(600);
        $("#resincronizarWhatsAppBody").fadeIn(600);

        getQRCodeWhatsApp();

        //setTimeOutQrCode();

      } else if(response['status'] == 3){
        //Cliente inexistente
        $("#loadingWhatsAppBody").hide();
        $("#messageWhatsAppApiFailure").fadeIn(600);

      } else if(response['status'] == 4){
        //Impossivel capturar o QR code


      } else {
        //Falha na requisição dos testes
        $("#loadingWhatsAppBody").hide();
        $("#messageWhatsAppApiFailure").fadeIn(600);

      }

      //console.log(response);

    });

}

function getQRCodeWhatsApp(){

  $.ajax({
    type: 'POST',
    url: '../application/whatsAppControl',
    data: {
      qrRequest: 52
    },
    success: function(result){

      result = JSON.parse(result);

      if(result['target']){
        $("#divQRCodeWhatsAppLoading").hide();
        $("#QrCodeErroMessage").hide();
        $("#divQRCodeWhatsApp").html();
        $("#divQRCodeWhatsApp").html('<img src="assets/medias/whatsApp/'+result['target']+'">');
        setTimeout(getQRCodeWhatsApp, 3000);
      } else {
        $("#divQRCodeWhatsApp").html();
        startWhatsAppTest();
        //$("#QrCodeErroMessage").fadeIn();
      }

    }
  });


}

function resetBodyWhatsApp(){
  $("#whatsAppBody").hide();
  $("#loadingWhatsAppBody").hide();
  $("#messageWhatsAppOk").hide();
  $("#messageWhatsAppErro").hide();
  $("#messageWhatsAppApiFailure").hide();
  $("#resincronizarWhatsAppBody").hide();
  $("#QrCodeErroMessage").hide();
}


<?php if(isset($_GET['setup']) && $_GET['setup'] == 'success'){
  ?>
  swal("Feito!", "As configurações foram atualizadas!", "success");
  <?php
} ?>
<?php if(isset($_GET['setor']) && $_GET['setor'] == 'atualizado'){
  ?>
  swal("Tudo certo!", "Você trocou de setor!", "success");
  <?php
} ?>
</script>
</body>

</html>
