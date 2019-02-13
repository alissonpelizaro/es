<?php
include '../application/relatorioAtendimento.php';
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
        <h3 class="padrao">Atendimento</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Atendimento</li>
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
                Relatório de atendimento
              </h4>
              <h6 class="card-subtitle">Recomenda-se um intervalo de datas de, no máximo, 6 meses.</h6>
              <div class="row">
                <div class="col-12">
                  <form action="log" method="post" class="enterness">
                    <div class="form-body">
                      <div class="row p-b-10">
                        <div class="col-md-3">
                          <label class="control-label junta">Data inicial</label>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend"><i class="fa fa-calendar"></i></span>
                              <input type="text" placeholder="<?php echo date("d/m/Y") ?>" id="inDataIni" class="form-control datepicker-here">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <label class="control-label junta">Hora inicial</label>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend"><i class="fa fa-clock-o"></i></span>
                              <input type="text" placeholder="00:00" id="inHoraIni" class="form-control clockpicker">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <label class="control-label junta">Data final</label>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend"><i class="fa fa-calendar"></i></span>
                              <input type="text" placeholder="<?php echo date("d/m/Y") ?>" id="inDataFim" class="form-control datepicker-here">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <label class="control-label junta">Hora final</label>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend"><i class="fa fa-clock-o"></i></span>
                              <input type="text" placeholder="23:59" id="inHoraFim" class="form-control clockpicker">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <label class="control-label junta">Sentido</label>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend"><i class="fa fa-exchange"></i></span>
                              <select class="form-control" id="inSentido">
                                <option value="">Ambos</option>
                                <option value="externo">Entrante</option>
                                <option value="interno">Sainte</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row p-b-10">
                        <div class="col-md-6">
                          <label class="control-label junta">Filas</label>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="form-ammend select-ammend"><i class="fa fa-bars"></i></span>
                              <select class="js-select js-select-rel-fix" id="inFilas" multiple="multiple">
                                <?php $filas = $rel->getFilas();
                                foreach ($filas as $fila) {
                                  ?>
                                  <option value="<?php echo $fila['nomeFila'] ?>"><?php echo $fila['nomeFila'] ?></option>
                                  <?php
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label class="control-label junta">Agentes</label>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend select-ammend"><i class="fa fa-users"></i></span>
                              <select class="js-select js-select-rel-fix" id="inAgentes" multiple="multiple">
                                <?php $agentes = $rel->getAgentes();
                                foreach ($agentes as $agente) {
                                  ?>
                                  <option value="<?php echo $agente['idUser'] ?>"><?php echo $agente['nome'] . " ". $agente['sobrenome'] ?></option>
                                  <?php
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row p-b-10">
                        <div class="col-md-4">
                          <label class="control-label junta">Origem</label>
                          <select class="input-sm form-mini p-0" id="inOrigemFiltro">
                            <option value="igual">igual à:</option>
                            <option value="contem">contém:</option>
                            <option value="inicia">inicia com:</option>
                            <option value="ncontem">não contém:</option>
                            <option value="termina">termina com:</option>
                          </select>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend"><i class="fa fa-arrow-down"></i></span>
                              <input type="text" placeholder="Origem" id="inOrigem" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <label class="control-label junta">Plataforma de destino</label>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend select-ammend"><i class="fa fa-link"></i></span>
                              <select class="js-select js-select-rel-fix" id="inPlataforma" multiple="multiple">
                                <option value="whatsapp">Whatsapp</option>
                                <option value="enterness">Enterness</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <label class="control-label junta">Protocolo</label>
                          <select class="input-sm form-mini p-0" id="inProtocoloFiltro">
                            <option value="igual">igual à:</option>
                            <option value="contem">contém:</option>
                            <option value="inicia">inicia com:</option>
                            <option value="ncontem">não contém:</option>
                            <option value="termina">termina com:</option>
                          </select>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend"><i class="fa fa-ticket"></i></span>
                              <input type="text" placeholder="Protocolo" id="inProtocolo" class="form-control mask-number">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row p-b-10">
                        <div class="col-md-4">
                          <label class="control-label junta">Classificação</label>
                          <select class="input-sm form-mini p-0"  id="inClassificacaoFiltro">
                            <option value="igual">igual à:</option>
                            <option value="contem">contém:</option>
                            <option value="inicia">inicia com:</option>
                            <option value="ncontem">não contém:</option>
                            <option value="termina">termina com:</option>
                          </select>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend"><i class="fa fa-tags"></i></span>
                              <input type="text" readonly placeholder="Classificação" id="inClassificacao" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-8">
                          <label class="control-label junta">Ordenar</label>
                          <div class="">
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend select-ammend"><i class="fa fa-sort-alpha-asc"></i></span>
                              <select class="js-select js-select-rel-fix" id="inOrdenar" multiple="multiple">
                                <option value="origem">Origem</option>
                                <option value="dataInicio">Data de inicio</option>
                                <option value="dataFim">Data final</option>
                                <option value="plataforma">Plataforma</option>
                                <option value="fila">Fila</option>
                                <option value="protocolo">Protocolo</option>
                                <option value="obs">Observações</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12 text-center">
                          <button type="button" id="btnSubmit" class="btn btn-info btn-sm m-t-25">
                            Gerar relatório
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div id="tableResult">
                <hr>
                <p class="text-center"><i>Nenhum dado para exibir</i></p>
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

var page = 1;
function clickPagination(action){

  var tpg = $("#btpg").html();
  var apg = $("#bapg").html();
  var trg = $("#btrg").html();

  if(action == 'next'){
    page++;
  } else if(action == 'prev'){
    page--;
  } else if(action == 'first'){
    page = 1;
  } else {
    page = tpg;
  }

  getTable();
}

function getTable(){
  var dataini = $("#inDataIni").val();
  var horaini = $("#inHoraIni").val();
  var datafim = $("#inDataFim").val();
  var horafim = $("#inHoraFim").val();
  var sentido = $("#inSentido").val();
  var filas = $("#inFilas").val();
  var agentes = $("#inAgentes").val();
  var origemFiltro = $("#inOrigemFiltro").val();
  var origem = $("#inOrigem").val();
  var plataforma = $("#inPlataforma").val();
  var protocoloFiltro = $("#inProtocoloFiltro").val();
  var protocolo = $("#inProtocolo").val();
  var classificacaoFiltro = $("#inClassificacaoFiltro").val();
  var classificacao = $("#inClassificacao").val();
  var ordenar = $("#inOrdenar").val();

  $.ajax({
    type: "POST",
    data: {
      origin : 'request',
      page : page,
      dataini : dataini,
      horaini : horaini,
      datafim : datafim,
      horafim : horafim,
      sentido : sentido,
      filas : filas,
      agentes : agentes,
      origemFiltro : origemFiltro,
      origem : origem,
      plataforma : plataforma,
      protocoloFiltro : protocoloFiltro,
      protocolo : protocolo,
      classificacaoFiltro : classificacaoFiltro,
      classificacao : classificacao,
      ordenar : ordenar
    },
    url: "../application/relatorioAtendimento",
    success: function(result){
      $("#tableResult").html(result);
      $("#tableResult").show();
    }
  });
}

$("#btnSubmit").click(function(){
  page = 1;
  getTable();
});

$('.js-select').select2({
  closeOnSelect: false
});
$('.datepicker-here').datepicker({
  language: 'pt',
  maxDate: new Date()
});
$('.clockpicker').clockpicker({
  autoclose: true
});


</script>
</body>

</html>
