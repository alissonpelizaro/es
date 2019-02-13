<?php
include '../application/meusAtendimentos.php';
//Define nível de restrição da página
$allowUser = array('agente');
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
        <h3 class="padrao" style="float:left; margin-right: 10px;">Meus atendimentos</h3>
        <a href="javascript:void(0)" onclick="favorito();">
					<img
					src="<?php if($favorito){?>assets/icons/star1.png
					<?php } else {?>assets/icons/star0.png
					<?php }?>"
					id="favorito">
				</a>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Meus atendimentos</li>
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
                Filtros
              </h4>
              <form class="enterness" action="meusAtendimentos" method="post">
	              <div class="row">
	              	<div class="col-sm-3 form-group">
	                  <label class="control-label junta">Data início</label>
	                  <input class="form-control datepicker-here" data-language='pt' name="dtIni" id="inDtIni" placeholder="00/00/0000" autocomplete="off">
	                </div>
	              	<div class="col-sm-2 form-group clockpicker">
	                  <label class="control-label junta">Hora início</label>
	                  <input class="form-control " name="horaIni" id="inHoraIni" placeholder="00:00" autocomplete="off">
	                </div>
	              	<div class="col-sm-3 form-group">
	                  <label class="control-label junta">Data fim</label>
	                  <input class="form-control datepicker-here" data-language='pt' name="dtFim" id="inDtFim" placeholder="00/00/0000" autocomplete="off">
	                </div>
	              	<div class="col-sm-2 form-group clockpicker">
	                  <label class="control-label junta">Hora fim</label>
	                  <input class="form-control" name="horaFim" id="inHoraFim" placeholder="00:00" autocomplete="off">
	                </div>
		              <div class="col-sm-2 form-group">
	                  <label class="control-label junta">Origem</label>
	                	<select class="form-control" id="selectOrigem" name="origem">
                    	<option value="">Todos</option>
                    	<option value="externo">Entrante</option>
                    	<option value="interno">Sainte</option>
										</select>
	                </div>
	              </div>
	              <div class="row">
	              	<div class="col-sm-6 form-group">
	                  <label class="control-label junta">Nome do cliente</label>
	                  <input type="text" class="form-control " name="cliente" id="inCliente" placeholder="Nome">
	                </div>
	              	<div class="col-sm-3 form-group">
	                  <label class="control-label junta">Telefone do cliente</label>
	                  <input class="form-control " name="fone" id="inFone" placeholder="Telefone">
	                </div>
	              	<div class="col-sm-3 form-group">
	                  <label class="control-label junta">Protocolo</label>
	                  <input type="number" class="form-control" name="protocolo" id="inProtocolo" placeholder="Protocolo">
	                </div>
		            </div>
		            <div class="row">
		            	<div class="col-6 form-group">	                  
		            		<label class="control-label junta">Fila</label>
	                  <select class="js-select" id="selectFila" name="fila[]" multiple="multiple" style="width: 100%;">
	                  	<?php foreach ($filas as $fila) {?>
                    	<option value="<?php echo $fila;?>"><?php echo ucfirst($fila);?></option>
                    	<?php }?>
										</select>
		            	</div>
		            	<div class="col-6 form-group">
		            		<label class="control-label junta">Plataforma</label>
	                  <select class="js-select" id="selectPlataforma" name="plataforma[]" multiple="multiple" style="width: 100%;">
                    	<option value="whatsapp">WhatsApp</option>
                    	<option value="telegram">Telegram</option>
                    	<option value="email">Email</option>
                    	<option value="messenger">Messenger</option>
                    	<option value="enterness">EnterNess</option>
                    	<option value="skype">Skype</option>
										</select>
		            	</div>
		            </div>
		            <div class="row">
		            	<div class="col-12">
		            		<button type="submit" class="btn btn-info float-right">Pesquisar</button>
		            	</div>
		            </div>
              </form>
	            <?php if(isset($_POST["dtIni"])){?>
	              <hr>
	              <?php if (!isset($atendimentos[0])) {?>
	              <h3 class="text-muted center"><i>Nenhum atendimento foi encontrado.</i></h3>
	              <?php }else{?>
	              
		            <h4 class="card-title">
	                Atendimentos
	              </h4>
	            	<div class="table-responsive m-t-10">
	                <table id="tabelaAgt" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
	                  <thead>
	                    <tr>
	                      <th>Data início</th>
	                      <th>Data fim</th>
	                      <th>Origem</th>
	                      <th>Cliente</th>
	                      <th>Fila</th>
	                      <th>Plataforma</th>
	                      <th>Protocolo</th>
	                      <th>Observação</th>
	                      <th class="text-center">Ações</th>
	                    </tr>
	                  </thead>
	                  <tbody>
	         						<?php foreach ($atendimentos as $atendimento) {?>
	                      <tr>
	                        <td><?php echo dataBdParaHtml($atendimento["dataInicio"]);?></td>
	                        <td><?php echo dataBdParaHtml($atendimento["dataFim"]);?></td>
	                        <td><?php if($atendimento["origem"] == "externo"){echo "Entrante";}else{echo "Sainte";}?></td>
	                        <td><?php if($atendimento["nome"] != ""){echo $atendimento["nome"];}else{echo $atendimento["remetente"];}?></td>
	                        <td><?php echo $atendimento["fila"];?></td>
	                        <td><?php echo $atendimento["plataforma"];?></td>
	                        <td><?php echo $atendimento["protocolo"];?></td>
	                        <td><?php echo $atendimento["obs"];?></td>
	                        <td class="text-center">
	                          <a href="../application/startCall?hash=<?php echo $atendimento['idCliente'] * 31; ?>&plataforma=<?php echo $atendimento["plataforma"];?>">
	                          	<button class="btn btn-sm btn-secondary btn-info btn-outline" type="button"><i class="<?php echo $util->iconFontWesome($atendimento["plataforma"]);?>" aria-hidden="true"></i></button>
	                          </a>
	                          <a href="../my/atendimento?hash=<?php echo $atendimento['idAtendimento'] * 777; ?>">
	                          	<button class="btn btn-sm btn-secondary btn-info btn-outline" type="button"><i class="fa fa-eye" aria-hidden="true"></i></button>
	                          </a>
	                        </td>
	                      </tr>
	                    <?php }?>
	                  </tbody>
	                </table>
	              </div>
	              <?php }?>
              <?php }?>
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

	$('#inFone').mask('(00) 00000-0000');
	$('#inDtIni').mask('00/00/0000');
	$('#inHoraIni').mask('00:00');
	$('#inDtFim').mask('00/00/0000');
	$('#inHoraFim').mask('00:00');

	$('.clockpicker').clockpicker({
		autoclose: true
	});

	$('#selectFila').select2({
		closeOnSelect: false,
		placeholder: 'Filas'
	});
	$('#selectPlataforma').select2({
		closeOnSelect: false,
		placeholder: 'Plataformas'
	});
	
  $('#tabelaAgt').DataTable({
    dom: 'Bfrtip',
    buttons: [
      'csv', 'excel', 'pdf', 'print'
    ]
  });

  // DataTable
  var table = $('#tabelaAgt').DataTable();

  // Apply the search
  table.columns().every( function () {
  	var that = this;

    $('input', this.footer() ).on( 'keyup change', function () {
    	if ( that.search() !== this.value ) {
    		that
      	.search( this.value )
      	.draw();
    	}
  	});
	});
} );

function favorito() {

	var favorito = document.getElementById("favorito").src;
	var favoritar = 1;

	if(favorito == "http://<?php echo $config->server; ?>/my/assets/icons/star1.png"){
		favoritar = 0;
	}

	$.ajax({
		type: "POST",
		data: {
			page : "meusAtendimentos",
			favorito: favoritar
		},
		url: "../application/ajaxFavorito",
		success: function(result){
			if(result == "true"){
				document.getElementById("favorito").src = "assets/icons/star1.png";
			} else {
				document.getElementById("favorito").src = "assets/icons/star0.png";
			}
		}
	});

	//document.getElementById('rating').innerHTML = avaliacao;
}
</script>
</body>

</html>
