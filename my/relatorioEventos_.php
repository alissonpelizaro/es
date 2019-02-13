<?php
include '../application/relatorioEventos.php';
//Define nível de restrição da página.
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
        <h3 class="padrao">Eventos</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Eventos</li>
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
                Relatório de eventos
              </h4>
              <h6 class="card-subtitle">Recomenda-se um intervalo de datas de, no máximo, 6 meses.</h6>
              <div class="row">
                <div class="col-12">
                  <form action="log" method="post" class="enterness">
                    <div class="form-body">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="control-label junta">Data inícial</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend"><i class="fa fa-calendar"></i></span>
                            <input type="text" placeholder="Data inícial" id="dtIni" class="datepicker-here form-control" autocomplete="off">
                          </div>
                        </div>
                        <div class="col-sm-2 clockpicker">
                          <label class="control-label junta">Hora inícial</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend"><i class="fa fa-clock-o"></i></span>
                            <input type="text" placeholder="Hora inícial" id="horaIni" class="form-control" autocomplete="off">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="has-danger">
                            <label class="control-label junta">Data final</label>
                            <div class="input-group input-group-default">
                              <span class="input-group-btn form-ammend"><i class="fa fa-calendar"></i></span>
                              <input type="text" placeholder="Data final" id="dtFim" class="datepicker-here form-control" autocomplete="off">
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-2 clockpicker">
                          <label class="control-label junta">Hora final</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend"><i class="fa fa-clock-o"></i></span>
                            <input type="text" placeholder="Hora final" id="horaFim" class="form-control" autocomplete="off">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <label class="control-label junta">Fila(as)</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend select-ammend"><i class="icon-append fa fa-reorder"></i></span>
                            <select class="js-select form-control" id="filas" multiple="multiple" style="width: 345px">
                            	<?php foreach ($relatorio->getFilas() as $fila) {?>
                              <option value="<?php echo $fila["nomeFila"];?>"><?php echo $fila["nomeFila"];?></option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-8">
                          <label class="control-label junta">Agentes</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend select-ammend"><i class="icon-append fa fa-group"></i></span>
                            <select class="js-select form-control" id="agentes" multiple="multiple" style="width: 753px">
                              <?php foreach ($relatorio->getAgentes() as $agente) {?>
                              <option value="<?php echo $agente["idUser"];?>"><?php echo $agente["nome"]." ".$agente["sobrenome"];?></option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-5">
                          <label class="control-label junta">Eventos</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend select-ammend"><i class="icon-append fa fa-bookmark"></i></span>
                            <select class="js-select form-control" id="eventos" multiple="multiple" style="width: 445px">
                              <?php foreach ($relatorio->getEventos() as $evento) {?>
                              <option value="<?php echo $evento["acao"];?>"><?php echo $evento["evento"];?></option>
                              <?php }?>
                            </select>
                 	         </div>
                        </div>
                        <div class="col-md-7">
                          <label class="control-label junta">Ordenar</label>
                          <div class="input-group input-group-default">
                            <span class="input-group-btn form-ammend select-ammend"><i class="icon-append fa fa-sort-alpha-asc"></i></span>
                            <select class="js-select form-control" id="ordenar" multiple="multiple" style="width: 653px">
                              <option value="data">Data</option>
                              <option value="fila">Fila</option>
                              <option value="agente">Agente</option>
                              <option value="evento">Evento</option>
                              <option value="espera">Espera</option>
                              <option value="duracao">Duração</option>
                              <option value="quemDesligou">Quem desligou?</option>
                              <option value="numero-motivo">Número/Motivo</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 text-center">
                          <button id="btnSubmit" type="button" class="btn btn-info m-t-25 btn-sm">
                            Gerar relatório
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="row">
								<div id="tabela" class="col-12"></div>
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

	$('.clockpicker').clockpicker({
		autoclose: true
	});

  $('.js-select').select2({
    closeOnSelect: false
  });

  $('.datepicker-here').datepicker({
    language: 'pt'
  });
  
});

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

  geraRelatorio();
}

function geraRelatorio(){
	$.ajax({
		method: "POST",
		url: "../application/relatorioEventos.php",
		data: {
	  	origem: "requisicao",
	  	page: page,
	  	dtIni: $("#dtIni").val(),
	  	horaIni: $("#horaIni").val(),
	  	dtFim: $("#dtFim").val(),
	  	horaFim: $("#horaFim").val(),
	  	filas: $("#filas").val(),
	  	agentes: $("#agentes").val(),
	  	eventos: $("#eventos").val(),
	  	ordenar: $("#ordenar").val()
	  },
	  success: function(result){
	    $("#tabela").html(result);
	  }
	});
}

$("#btnSubmit").click(function(){
  page = 1;
  geraRelatorio();
});

</script>

</body>

</html>