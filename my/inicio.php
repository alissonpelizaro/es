<?php
include '../application/inicio.php';
include 'inc/head.php';
?>

<!-- Novo Início  -->
<!-- Main wrapper  -->
<div id="main-wrapper">
  <?php include 'inc/header.php'; ?>
  <?php include 'inc/sidebar.php'; ?>
  <!-- Page wrapper  -->
  <div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="padrao">Início</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item" active>Inicio</li>
        </ol>
      </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->

    <span class="p-0 m-0 m-t-10 card btn-ini-reset">
      <button type="button" class="btn btn-sm btn-light bg-white" onclick="resetPosicoes()">
        <i class="fa fa-repeat text-secondary" aria-hidden="true"></i>
      </button>
    </span>
    <span class="p-0 m-0 m-t-10 card btn-ini-favo">
      <button id="myBtn" type="button" class="btn btn-sm btn-light bg-white" onclick="">
        <i class="fa fa-star-o" aria-hidden="true"></i>
      </button>
    </span>
    <div class="container-fluid p-t-10">
      <div class="alert alert-warning alert-dismissible fade hide">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Atenção!</strong> <br>O sistema MyOmni estará passando por migração e poderá apresentar inconsistências nessa data. Tudo se normalizará em breve.
      </div>
      <div id="grid-principal" class="grid-stack" data-gs-width="12" data-gs-animate="yes">

        <?php
        if($_SESSION['tipo'] == 'dev' ||
        $_SESSION['tipo'] == 'coordenador' ||
        $_SESSION['tipo'] == 'administrador' ||
        $_SESSION['tipo'] == 'supervisor'){
          $cardAtendimento = true;
          $xAg = 6;
          $xSu = 9;
          if ($favoritos && $favoritos["atendimento"] && $util->getSectionPermission('media')) {
            atendimentos(0, 0, $atendimentos);
          }else{
            $xAg = 0;
            $xSu = 3;
          }

          $xLem = 0;
          if ($favoritos && !$favoritos["clientes"]) {
            $xLem = 6;
          }

          agentes($xAg, 0, $licenca, $agentes);
          supervisores($xSu, 0, $licenca, $supervisores);
          administradores(6, 2, $licenca, $administradores);
          coordenadores(9, 2, $licenca, $coordenadores);
          grafico(0, 4, $favoritos, $graficoAtendimentos);
          concessionaria(0, 10, $logos, $util, $concessionaria);
          mural(0, 13, $msgs, $nomes, $util, $mural);
          lembretes($xLem, 15, $favoritos, $lembretes, $lembreteCookie, $lembretePosicoesCookie);
          clientes(6, 13, $listaClientes, $favoritos);
          //chat(0, 15);

        }else if($_SESSION['tipo'] == 'agente'){
          $xLem = 0;
          if ($favoritos && !$favoritos["clientes"] && !$favoritos["meusAtendimentos"]) {
            $xLem = 6;
          }

          concessionaria(0, 0, $logos, $util, $concessionaria);
          mural(0, 3, $msgs, $nomes, $util, $mural);
          lembretes($xLem, 5, $favoritos, $lembretes, $lembreteCookie, $lembretePosicoesCookie);
          clientes(6, 7, $listaClientes, $favoritos);
          meusAtendimentos(6, 3, $meusAtendimentos, $favoritos);

        }else if($_SESSION['tipo'] == 'tecnico'){
          lembretes(0, 0, $favoritos, $lembretes, $lembreteCookie, $lembretePosicoesCookie);

        }else if($_SESSION['tipo'] == 'gestor'){
          regraDeNegocio(0, 0, $tecnico, $last, $regraDeNegocio);
          lembretes(6, 0, $favoritos, $lembretes, $lembreteCookie, $lembretePosicoesCookie);
        }?>

      </div>
      <!-- End Container fluid  -->
      <?php include 'inc/footer.php'; ?>
    </div>
    <!-- End Page wrapper  -->
  </div>
  <div class="modal fade" id="modalNovoLembrete" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Inserir novo post-it</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="reload()" style="margin-top: -20px; margin-right: -20px;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="enterness" action="../application/novoLembrete" method="post">
          <input type="hidden" name="father" value="inicio">
          <div class="modal-body">
            <div class="form-group">
              <label class="junta">Título do post-it <i>(Opcional)</i></label>
              <input type="text" maxlength="40" name="titulo" class="form-control" placeholder="Ex.: Não esquecer!">
            </div>
            <div class="form-group">
              <label class="junta">Conteúdo do post-it</label>
              <textarea class="form-control" maxlength="400" required name="lembrete" placeholder="Lembrete" rows="4" style="height: 110px;"></textarea>
            </div>
            <div class="form-group">
              <label class="junta">Cor do post-it</label>
              <input type="hidden" name="cor" id="corIn" value="primary">
              <div class="box-lembretes-cores">
                <div class="cor-lembrete bg-primary cor-lembrete-ativo" id="cor-primary" onclick="setCorModal('primary')"></div>
                <div class="cor-lembrete bg-info" id="cor-info" onclick="setCorModal('info')"></div>
                <div class="cor-lembrete bg-success" id="cor-success" onclick="setCorModal('success')"></div>
                <div class="cor-lembrete bg-warning" id="cor-warning" onclick="setCorModal('warning')"></div>
                <div class="cor-lembrete bg-danger" id="cor-danger" onclick="setCorModal('danger')"></div>
                <div class="cor-lembrete bg-dark" id="cor-dark" onclick="setCorModal('dark')"></div>
                <div class="cor-lembrete bg-secondary" id="cor-secondary" onclick="setCorModal('secondary')"></div>
              </div>
            </div>
            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="checkAlarm">
                <label class="custom-control-label" for="checkAlarm">Receber notificação do sistema</label>
              </div>
            </div>
            <div class="row" id="sessaoDataIn" style="display: none;">
              <div class="col-sm-8">
                <div class="form-group">
                  <label class="junta" style="width: 100%;">Data da notificação:</label>
                  <br>
                  <input type='text' onkeyup="limpaInput()" id="dataInLembrete" value="" name="notificacao" class="form-control datepicker-here" data-language='pt' data-position="top center" placeholder="Defina a data da notificação" autocomplete="off"/>
                </div>
              </div>
              <div class="col-sm-4 form-group clockpicker">
                <label class="control-label junta">Hora</label>
                <input type='text' class="form-control" onkeyup="limpaInput()" name="hora" id="inHora" placeholder="00:00" autocomplete="off">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reload()">Cancelar</button>
            <button type="submit" class="btn btn-info">Salvar lembrete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modalEditLembrete" tabindex="-1"
  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitleEdit">Editar
          post-it</h5>
          <button type="button" class="close" data-dismiss="modal"
          aria-label="Close" onclick="reload()"
          style="margin-top: -20px; margin-right: -20px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="enterness" action="../application/editaLembrete"
      method="post">
      <input type="hidden" name="father" value="inicio">
      <div class="modal-body">
        <div class="form-group">
          <label class="junta">Título do post-it <i>(Opcional)</i></label>
          <input type="text" maxlength="40" name="titulo"
          class="form-control" placeholder="Ex.: Não esquecer!">
        </div>
        <div class="form-group">
          <label class="junta">Conteúdo do post-it</label>
          <textarea class="form-control" maxlength="400" required
          name="lembrete" placeholder="Lembrete" rows="4"
          style="height: 110px;"></textarea>
        </div>
        <div class="form-group">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input"
            id="checkAlarmEdit"> <label class="custom-control-label"
            for="checkAlarmEdit">Receber notificação do sistema</label>
          </div>
        </div>
        <div class="row" id="sessaoDataInEdit" style="display: none;">
          <div class="col-sm-8">
            <div class="form-group">
              <label class="junta" style="width: 100%;">Data da
                notificação:</label>
                <br>
                <input type='text'
                onkeyup="limpaInput()"
                id="dataInLembreteEdit"
                value=""
                name="notificacao"
                class="form-control datepicker-here"
                data-language='pt'
                data-position="top center"
                placeholder="Defina a data da notificação"
                autocomplete="off"/>
              </div>
            </div>
            <div class="col-sm-4 form-group clockpicker">
              <label class="control-label junta">Hora</label>
              <input type='text' class="form-control" onkeyup="limpaInput()" name="hora" id="inHoraEdit" placeholder="00:00" autocomplete="off">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
          data-dismiss="modal" onclick="reload()">Cancelar</button>
          <button type="submit" class="btn btn-info">Salvar edição</button>
        </div>
        <input hidden="true" value="inicio" name="page">
        <input hidden="true" value="" name="idEdit" id="idEdit">
      </form>
    </div>
  </div>
</div>
<!-- Modal Atendimento -->
<div class="modal fade" id="modalAtendimentos" tabindex="1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog"  style="max-height: 100%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Atendimentos ativos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="reload()" style="margin-top: -20px; margin-right: -20px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <span class="enterness form-inline spanSearchModalAtendimento">
          <label for="filterModalAtendimentos"><i class="fa fa-search" aria-hidden="true"></i></label>
          <input class="form-control m-l-10 inputSearchContacts" placeholder="Encontrar um agente..." type="text" id="filterModalAtendimentos" value="">
        </span>
        <hr class="w-60">
        <div id="modalAtendimentosBody">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reload()">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- The Modal -->
<div id="modalFavoritos" class="modal-favoritos">
  <!-- Modal content -->
  <div class="modal-content-favoritos">
    <div class="modal-header-favoritos">
      <span class="close-favoritos" onclick="modal.style.display = 'none';">&times;</span>
      <h2><i class="fa fa-star" aria-hidden="true" style="font-size: 16px;"></i> Favoritos</h2>
    </div>
    <div class="modal-body-favoritos">
    	<ul class="list-group">

			  <li class="list-group-item">
					Post-its
		      <a class="float-right" href="javascript:void(0)" onclick="favorito('lembreteFavorito');">
						<img style="width: 20px;"
							src="<?php if($favoritos['lembrete']){?>assets/icons/star1.png
							<?php } else {?>assets/icons/star0.png
							<?php }?>"
							id="lembreteFavorito">
					</a>
				</li>

    		<?php if($_SESSION['tipo'] != 'gestor' && $_SESSION['tipo'] != 'tecnico'){?>
			  <li class="list-group-item">
					Clientes
		      <a  class="float-right" href="javascript:void(0)" onclick="favorito('clientesFavorito');">
						<img style="width: 20px;"
							src="<?php if($favoritos['clientes']){?>assets/icons/star1.png
							<?php } else {?>assets/icons/star0.png
							<?php }?>"
							id="clientesFavorito">
					</a>
				</li>
    		<?php }?>

    		<?php if ($_SESSION['tipo'] == 'dev' || $_SESSION['tipo'] == 'coordenador' ||
						      $_SESSION['tipo'] == 'administrador' || $_SESSION['tipo'] == 'supervisor') {?>
			  <li class="list-group-item">
					Atendimento
		      <a class="float-right" href="javascript:void(0)" onclick="favorito('atendimentoFavorito');">
						<img style="width: 20px;"
							src="<?php if($favoritos['atendimento']){?>assets/icons/star1.png
							<?php } else {?>assets/icons/star0.png
							<?php }?>"
							id="atendimentoFavorito">
					</a>
				</li>

			  <li class="list-group-item">
					Gráfico atendimentos
		      <a class="float-right" href="javascript:void(0)" onclick="favorito('graficoAtendimentosFavorito');">
						<img style="width: 20px;"
							src="<?php
										if($favoritos['graficoAtendimentos']){
											echo "assets/icons/star1.png";
									 	} else {
									 		echo "assets/icons/star0.png";
    								}?>"
							id="graficoAtendimentosFavorito">
					</a>
				</li>
    		<?php }?>

    		<?php if ($_SESSION['tipo'] == 'agente') {?>
			  <li class="list-group-item">
					Meus atendimentos
		      <a class="float-right" href="javascript:void(0)" onclick="favorito('meusAtendimentosFavorito');">
						<img style="width: 20px;"
							src="<?php if($favoritos['meusAtendimentos']){?>assets/icons/star1.png
							<?php } else {?>assets/icons/star0.png
							<?php }?>"
							id="meusAtendimentosFavorito">
					</a>
				</li>
    		<?php }?>

			</ul>
    </div>
    <div class="modal-footer-favoritos">
      <button class="btn btn-info btn-sm" type="button" onclick="salvaFavoritos();">Salvar</button>
    </div>
  </div>
</div>

<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>

<!-- grafico atendimentos -->
<?php
if($favoritos && $favoritos['graficoAtendimentos']){
  include 'inc/scripts/inicio/grafico-atendimentos.php';
}
 ?>
<!-- modal favoritos -->
<?php include 'inc/scripts/inicio/modal-favoritos.php'; ?>

<script type="text/javascript">
<?php if($_SESSION['tipo'] == 'agente'){
  ?>
  attCardMeusAtendimento();

  function attCardMeusAtendimento(){

    $.ajax({
      type: "POST",
      data: {
        agent : <?php echo $idUser*521 ?>
      },
      url: "../application/ajaxMeusAtendimentos",
      success: function(result){
        var ats = JSON.parse(result);
        var content = "";

        if(ats.length == 0){
          content = "<i class='text-muted'>Nenhum atendimento ativo</i>";
        } else {
          var i = 0;
          for(i = 0; i<ats.length; i++) {
            content += '<div class="col-4 p-5"><div class="cursorPointer card-agente-atendimento" onclick="window.location.href='+"'media?hash="+ats[i]['idAtendimento']+"&token=LbglGH8vDSJdCMv'"+'">';
            content += '<img src="assets/icons/social/'+ats[i]['plataforma']+'.png" alt="'+ats[i]['plataforma']+'">';
            content += '<span class="card-agente-atendimento-gradient"></span><div class="card-agente-atendimento-body">';
            content += '<p class="m-0 p-0 text-truncate padrao">'+ats[i]['nome']+'</p>';
            content += '<p class="p-0 m-0 p-b-5 lista-cliente">';
            if(ats[i]['last']){
              content += '<i>Ultima resposta: </i>';
              content += '<span class="badge badge-secondary '+ats[i]['last'].sit+' text-white">'+ats[i]['last'].time+'</span>';
            } else {
              content += '<i>Aguardando cliente</i>';
            }
            content += '</p></div></div></div>';
          }
        }

        $("#body-meus-atendimentos").html(content);
        setTimeout(attCardMeusAtendimento, 7000);
      }
    });
  }
  <?php
} ?>

$('.clockpicker').clockpicker({
  placement: 'top',
  autoclose: true
});

<?php if(isset($_GET['pass']) && $_GET['pass'] == 'reseted'){
  ?>
  swal("Feito!", "A sua senha foi alterada!", "success")
  <?php
} ?>
</script>


<script type="text/javascript">

/* Função para edição dos lembretes */
function setEditLembrete(titulo, desc, dataTime, id){
  $("#idEdit").val(id);
  $("input[name='titulo']").val(titulo);
  while(desc.indexOf("<br>") != -1){
    desc = desc.replace("<br>", "\r\n");
  }
  $("textarea[name='lembrete']").val(desc);
  if(dataTime != "01/01/1000 às 00:00"){
    $("#checkAlarmEdit").prop("checked", true);
    $("#sessaoDataInEdit").show();
    document.getElementById('dataInLembreteEdit').required = true;
    var cursor = dataTime.split(" ");
    $("#dataInLembreteEdit").val(cursor[0]);
    $("#inHoraEdit").val(cursor[2]);
  }
}

<?php if($cardAtendimento){?>
  attCardAtendimento();
  var attCard = 'true';

  function blocoAtendimentoCardClick(){
    attCard = 'false';
  }

  function attCardAtendimento(){
    $.ajax({
      type: "POST",
      url: "../application/cardAtendimento",
      success: function(result){
        if(attCard == 'true'){
          $("#cardAtendimento").html(result);
        }
        setTimeout(attCardAtendimento, 17000);
      }
    });
    $('[data-toggle="popover"]').popover('hide');
  }
  <?php }?>

  function requestCasas(name, local){
    $('.bloco-casa-menu-ativa').removeClass("bloco-casa-menu-ativa");
    $("#"+local+"img").addClass('bloco-casa-menu-ativa');

    $.ajax({
      type: "POST",
      data: {hash : name},
      url: "../application/getCasas",
      success: function(result){
        $("#"+local).html(result);
      }
    });

    var concessionaria = document.getElementById("concessionaria");

    if(concessionaria.getAttribute("data-gs-height") == 3){
      var grid = $('.grid-stack').data('gridstack');
      grid.resize("#concessionaria", concessionaria.getAttribute("data-gs-width"), 5);
    }
  }

  $(document).ready(function() {

    var selected;

    $('.datepicker-here').datepicker({
      language: 'pt',
      minDate: new Date() // Now can select only dates, which goes after today
    });

    <?php if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'success'){
      ?>
      swal("Opa, tudo certo!", "Um novo post-it foi criado!", "success")
      <?php
    } ?>
    <?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
      ?>
      swal("Feito!", "O post-it foi jogado fora!", "success")
      <?php
    } ?>
    <?php if(isset($_GET['regraedit']) && $_GET['regraedit'] == 'success'){
      ?>
      swal("Feito!", "A regra foi editada!", "success")
      <?php
    } ?>
    <?php if(isset($_GET['setup']) && $_GET['setup'] == 'success'){
      ?>
      swal("Feito!", "Uma nova regra de negócio foi definida!", "success")
      <?php
    } ?>
  });

  function limpaInput(){
    document.getElementById('dataInLembrete').value = "";
  }

  function limpaInputEdit(){
    document.getElementById('dataInLembreteEdit').value = "";
  }

  function reload(){
    window.location.href="inicio";
  }

  function setCorModal(cor){
    $(".cor-lembrete").addClass('cor-lembrete-inativo').removeClass('cor-lembrete-ativo');
    $("#cor-"+cor).addClass('cor-lembrete-ativo');
    document.getElementById('corIn').value = cor;
  }

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

  $( "#checkAlarmEdit" ).change(function() {
    if(this.checked){
      $("#sessaoDataInEdit").show();
      document.getElementById('dataInLembreteEdit').required = true;
    } else {
      $("#sessaoDataInEdit").hide();
      document.getElementById('dataInLembreteEdit').required = false;
      limpaInputEdit();
    }
  });

  function setTrash(hash){
    swal({
      title: "Deseja mesmo jogar esse Post-it fora?",
      text: "Essa ação não poderá ser desfeita!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Sim, jogar fora!",
      cancelButtonText: "Cancelar",
      closeOnConfirm: true,
      closeOnCancel: true
    },
    function(isConfirm){
      if(isConfirm){
        $.ajax({
          type: "POST",
          data: {
            hash : hash
          },
          url: "../application/deletaPostit",
          success: function(result){
            if(result == '1'){
              window.location.href = "inicio?action=trashed&status=success";
            } else if(result == '0'){
              window.location.href = "inicio?action=trashed&status=failure";
            }
          }
        });
      }
    });
  }

  /* Salva a posição dos cardes em cookie */
  function setCookies(){

    <?php
    $favorito = true;

    if($util->getSectionPermission('conc')){
      if($_SESSION['tipo'] == 'dev' ||
      $_SESSION['tipo'] == 'coordenador' ||
      $_SESSION['tipo'] == 'administrador' ||
      $_SESSION['tipo'] == 'supervisor' ||
      $_SESSION['tipo'] == 'agente'){?>

        //Concessionaria
        var concessionaria = document.getElementById("concessionaria");

        var conH = concessionaria.getAttribute("data-gs-height");

        if(conH == 5){
          conH = 3;
        }

        var arrayConcessionaria = new Array("concessionaria",
        concessionaria.getAttribute("data-gs-x"),
        concessionaria.getAttribute("data-gs-y"),
        concessionaria.getAttribute("data-gs-width"),

        conH);
        <?php }
      }

      if ($util->getSectionPermission('mural')) {
        if($_SESSION['tipo'] == 'dev' ||
        $_SESSION['tipo'] == 'coordenador' ||
        $_SESSION['tipo'] == 'administrador' ||
        $_SESSION['tipo'] == 'supervisor' ||
        $_SESSION['tipo'] == 'agente'){?>

          //Mural
          var mural = document.getElementById("mural");

          var arrayMural = new Array("mural",
          mural.getAttribute("data-gs-x"),
          mural.getAttribute("data-gs-y"),
          mural.getAttribute("data-gs-width"),
          mural.getAttribute("data-gs-height"));
          <?php }
        }
        if($favoritos && !$favoritos["lembrete"]){
          $favorito = false;
        }

        if($favorito &&
        ($_SESSION['tipo'] == 'dev' ||
        $_SESSION['tipo'] == 'coordenador' ||
        $_SESSION['tipo'] == 'administrador' ||
        $_SESSION['tipo'] == 'supervisor' ||
        $_SESSION['tipo'] == 'gestor' ||
        $_SESSION['tipo'] == 'tecnico' ||
        $_SESSION['tipo'] == 'agente')){?>

          //Lembrete
          var lembrete = document.getElementById("lembrete");

          var arrayLembrete = new Array("lembrete",
          lembrete.getAttribute("data-gs-x"),
          lembrete.getAttribute("data-gs-y"),
          lembrete.getAttribute("data-gs-width"),
          lembrete.getAttribute("data-gs-height"));
          <?php }

          if($_SESSION['tipo'] == 'dev' ||
          $_SESSION['tipo'] == 'coordenador' ||
          $_SESSION['tipo'] == 'administrador' ||
          $_SESSION['tipo'] == 'supervisor'){?>

            //Agentes
            var agentes = document.getElementById("agentes");

            var arrayAgentes = new Array("agentes",
            agentes.getAttribute("data-gs-x"),
            agentes.getAttribute("data-gs-y"),
            agentes.getAttribute("data-gs-width"),
            agentes.getAttribute("data-gs-height"));
            <?php	}

            if($_SESSION['tipo'] == 'dev' ||
            $_SESSION['tipo'] == 'coordenador' ||
            $_SESSION['tipo'] == 'administrador' ||
            $_SESSION['tipo'] == 'supervisor'){?>

              //Supervisores
              var supervisores = document.getElementById("supervisores");

              var arraySupervisores = new Array("supervisores",
              supervisores.getAttribute("data-gs-x"),
              supervisores.getAttribute("data-gs-y"),
              supervisores.getAttribute("data-gs-width"),
              supervisores.getAttribute("data-gs-height"));
              <?php }

              if($_SESSION['tipo'] == 'dev' ||
              $_SESSION['tipo'] == 'coordenador' ||
              $_SESSION['tipo'] == 'administrador' ||
              $_SESSION['tipo'] == 'supervisor'){?>

                //Administradores
                var administradores = document.getElementById("administradores");

                var arrayAdministradores = new Array("administradores",
                administradores.getAttribute("data-gs-x"),
                administradores.getAttribute("data-gs-y"),
                administradores.getAttribute("data-gs-width"),
                administradores.getAttribute("data-gs-height"));
                <?php }

                if($_SESSION['tipo'] == 'dev' ||
                $_SESSION['tipo'] == 'coordenador' ||
                $_SESSION['tipo'] == 'administrador' ||
                $_SESSION['tipo'] == 'supervisor' ||
                $_SESSION['tipo'] == ''){?>

                  //Coordenadores
                  var coordenadores = document.getElementById("coordenadores");

                  var arrayCoordenadores = new Array("coordenadores",
                  coordenadores.getAttribute("data-gs-x"),
                  coordenadores.getAttribute("data-gs-y"),
                  coordenadores.getAttribute("data-gs-width"),
                  coordenadores.getAttribute("data-gs-height"));
                  <?php }

                  if ($util->getSectionPermission('media')) {
                  	$favorito = true;
                  	if($favoritos && !$favoritos["graficoAtendimentos"]){
                  		$favorito = false;
                  	}
                  }

                  if($favorito &&
                  		($_SESSION['tipo'] == 'dev' ||
                  		 $_SESSION['tipo'] == 'coordenador' ||
                  		 $_SESSION['tipo'] == 'administrador' ||
                  		 $_SESSION['tipo'] == 'supervisor')){?>

										//Gráfico atendimentos
                  	var graficoAtendimentos = document.getElementById("graficoAtendimentos");

				            var arrayGraficoAtendimentos = new Array("graficoAtendimentos",
				        	    graficoAtendimentos.getAttribute("data-gs-x"),
				        	    graficoAtendimentos.getAttribute("data-gs-y"),
				        	    graficoAtendimentos.getAttribute("data-gs-width"),
				        	    graficoAtendimentos.getAttribute("data-gs-height"));

                  <?php }


                  if ($util->getSectionPermission('media')) {
                    $favorito = true;
                    if($favoritos && !$favoritos["atendimento"]){
                      $favorito = false;
                    }
                  }

                  if($favorito &&
                  ($_SESSION['tipo'] == 'dev' ||
                  $_SESSION['tipo'] == 'coordenador' ||
                  $_SESSION['tipo'] == 'administrador' ||
                  $_SESSION['tipo'] == 'supervisor')){?>

                    //Atendimentos
                    var atendimentos = document.getElementById("atendimentos");

                    var arrayAtendimentos = new Array("atendimentos",
                    atendimentos.getAttribute("data-gs-x"),
                    atendimentos.getAttribute("data-gs-y"),
                    atendimentos.getAttribute("data-gs-width"),
                    atendimentos.getAttribute("data-gs-height"));
                    <?php }


                    if($_SESSION['tipo'] == 'gestor'){?>
                      //Regra de negocio
                      var regraDeNegocio = document.getElementById("regraDeNegocio");

                      var arrayRegraDeNegocio = new Array("regraDeNegocio",
                      regraDeNegocio.getAttribute("data-gs-x"),
                      regraDeNegocio.getAttribute("data-gs-y"),
                      regraDeNegocio.getAttribute("data-gs-width"),
                      regraDeNegocio.getAttribute("data-gs-height"));
                      <?php }

                      $favorito = true;
                      if($favoritos && !$favoritos["clientes"]){
                        $favorito = false;
                      }

                      if($favorito &&
                      ($_SESSION['tipo'] == 'dev' ||
                      $_SESSION['tipo'] == 'coordenador' ||
                      $_SESSION['tipo'] == 'administrador' ||
                      $_SESSION['tipo'] == 'supervisor' ||
                      $_SESSION['tipo'] == 'agente')){?>
                        //Lista de clientes
                        var listaClientes = document.getElementById("listaClientes");

                        var arrayListaClientes = new Array("listaClientes",
                        listaClientes.getAttribute("data-gs-x"),
                        listaClientes.getAttribute("data-gs-y"),
                        listaClientes.getAttribute("data-gs-width"),
                        listaClientes.getAttribute("data-gs-height"));
                        <?php }

                        $favorito = true;
                        if($favoritos && !$favoritos["meusAtendimentos"]){
                          $favorito = false;
                        }

                        if($favorito && $_SESSION['tipo'] == 'agente'){?>
                          //Meus atendimentos
                          var meusAtendimentos = document.getElementById("meusAtendimentos");

                          var arrayMeusAtendimentos = new Array("meusAtendimentos",
                          meusAtendimentos.getAttribute("data-gs-x"),
                          meusAtendimentos.getAttribute("data-gs-y"),
                          meusAtendimentos.getAttribute("data-gs-width"),
                          meusAtendimentos.getAttribute("data-gs-height"));
                          <?php }?>

                          //Salvando em um array para converter em cookie
                          <?php
                          if($_SESSION['tipo'] == 'dev' ||
                          $_SESSION['tipo'] == 'coordenador' ||
                          $_SESSION['tipo'] == 'administrador' ||
                          $_SESSION['tipo'] == 'supervisor'){?>
                            var dados = new Array(
                              <?php if($util->getSectionPermission('conc')){?>arrayConcessionaria, <?php }?>
                              <?php if ($util->getSectionPermission('mural')) {?>arrayMural, <?php }?>
                              <?php if($favoritos && $favoritos["lembrete"]){?>arrayLembrete, <?php }?>
                              <?php if($favoritos && $favoritos["graficoAtendimentos"]){?>arrayGraficoAtendimentos, <?php }?>
                              <?php if($favoritos && $favoritos["atendimento"] &&
                              $util->getSectionPermission('media')){?>arrayAtendimentos, <?php }?>
                              <?php if($favoritos && $favoritos["clientes"]){?>arrayListaClientes, <?php }?>
                              arrayCoordenadores,
                              arrayAdministradores,
                              arraySupervisores,
                              arrayAgentes);

                              <?php }else if($_SESSION['tipo'] == 'agente'){?>
                                var dados = new Array(
                                  <?php if($util->getSectionPermission('conc')){?>arrayConcessionaria, <?php }?>
                                  <?php if($favoritos && $favoritos["lembrete"]){?>arrayLembrete, <?php }?>
                                  <?php if($favoritos && $favoritos["clientes"]){?>arrayListaClientes, <?php }?>
                                  <?php if($favoritos && $favoritos["meusAtendimentos"]){?>arrayMeusAtendimentos, <?php }?>
                                  arrayMural);

                                  <?php }else if($_SESSION['tipo'] == 'gestor'){?>
                                    var dados = new Array(
                                      arrayRegraDeNegocio,
                                      <?php if($favoritos && $favoritos["lembrete"]){?>arrayLembrete<?php }?>);

                                      <?php }else if($_SESSION['tipo'] == 'tecnico'){?>
                                        var dados = new Array(
                                          <?php if($favoritos && $favoritos["lembrete"]){?>arrayLembrete<?php }?>);
                                          <?php }?>

                                          //Convertendo array em cookie
                                          if(dados != ""){
                                            $.cookie("inicioProsicoes", JSON.stringify(dados), {expires: 365});
                                          }
                                        }

                                        /* Salava em cookie a posição, altura e largura dos post-its */
                                        function salvarCookieLembretes(){
                                          <?php
                                          $ids = "";
                                          if(isset($lembretes) && $lembretes != ""){
                                            foreach ($lembretes as $lembrete) {
                                              if ($ids == "") {
                                                $ids = $lembrete['idLembrete']*17;
                                              }else{
                                                $ids = $ids.",".$lembrete['idLembrete']*17;
                                              }
                                            }
                                          }
                                          ?>
                                          var ids = '<?php echo $ids?>';
                                          var ids = ids.split(",");
                                          var i = 0;
                                          var lembretes = new Array();

                                          ids.forEach(function(id){
                                            var lembrete = document.getElementById("lembrete"+id);

                                            lembretes[i] = new Array("lembrete"+id,
                                            lembrete.getAttribute("data-gs-x"),
                                            lembrete.getAttribute("data-gs-y"),
                                            lembrete.getAttribute("data-gs-width"),
                                            lembrete.getAttribute("data-gs-height"));
                                            i++;
                                          });

                                          $.cookie("lembretes", JSON.stringify(lembretes), {expires: 365});
                                        }

                                        /* Reseta a posição dos cardes para o padrão */
                                        function resetPosicoes(){
                                          if($.cookie("inicioProsicoes")){
                                            $.cookie("inicioProsicoes", JSON.stringify("",{expires: -1}));
                                            var grid = $('.grid-stack').data('gridstack');

                                            <?php if ($_SESSION['tipo'] == 'dev' ||
                                            $_SESSION['tipo'] == 'coordenador' ||
                                            $_SESSION['tipo'] == 'administrador' ||
                                            $_SESSION['tipo'] == 'supervisor') {?>
                                              <?php
                                              $xAg = 6;
                                              $xSu = 9;
                                              if ($favoritos && $favoritos["atendimento"] && $util->getSectionPermission('media')) {?>
                                                grid.update("#atendimentos", 0, 0, 6, 4);
                                                <?php
                                              }else{
                                                $xAg = 0;
                                                $xSu = 3;
                                              }?>
                                              grid.update("#agentes", parseInt(<?php echo $xAg;?>), 0, 3, 2);
                                              grid.update("#supervisores", parseInt(<?php echo $xSu;?>), 0, 3, 2);
                                              grid.update("#administradores", 6, 2, 3, 2);
                                              grid.update("#coordenadores", 9, 2, 3, 2);
                                              grid.update("#graficoAtendimentos", 0, 4, 12, 6);
                                              grid.update("#concessionaria", 0, 10, 12, 3);
                                              grid.update("#mural", 0, 13, 6, setAlturaMural());
                                              grid.update("#lembrete", parseInt(<?php echo $xLem;?>), 15, 6, parseInt(<?php if($lembretes){echo "3";}else{echo "2";}?>));
                                              grid.update("#listaClientes", 6, 13, 6, 8);

                                              <?php }elseif ($_SESSION['tipo'] == 'agente') {?>
                                                grid.update("#concessionaria", 0, 0, 12, 3);
                                                grid.update("#mural", 0, 3, 6, parseInt(<?php if($msgs){echo 5;}else{echo 2;}?>));
                                                grid.update("#lembrete", parseInt(<?php echo $xLem;?>), 5, 6, parseInt(<?php if($lembretes){echo "4";}else{echo "2";}?>));
                                                grid.update("#meusAtendimentos", 6, 3, 6, 4);
                                                grid.update("#listaClientes", 6, 7, 6, 8);

                                                <?php }elseif ($_SESSION['tipo'] == 'gestor') {?>
                                                  grid.update("#regraDeNegocio", 0, 0, 6, 3);
                                                  grid.update("#lembrete", 6, 0, 6, parseInt(<?php if($lembretes){echo "4";}else{echo "2";}?>));

                                                  <?php }elseif ($_SESSION['tipo'] == 'tecnico') {?>
                                                    grid.update("#lembrete", 0, 0, 6, parseInt(<?php if($lembretes){echo "4";}else{echo "2";}?>));
                                                    <?php }?>

                                                  }
                                                }
                                        			<?php if ($_SESSION['tipo'] != 'gestor' && $_SESSION['tipo'] != 'tecnico') {?>
                                                /* Almenta o tamanho do mural ate que mostre todos dos dados */
                                                function setAlturaMural(){
                                                  <?php if (isset($msgs) && $msgs != "") {?>
                                                    var tPx = 34;
                                                    for (var i = 0; i < <?php echo count($msgs); ?>; i++) {
                                                      tPx = tPx + $("#visao"+(i+1)).height();
                                                    }

                                                    var temp = (tPx +30) / 45;
                                                    var rows = parseInt(temp);
                                                    temp = temp - rows;
                                                    if(temp > 0){
                                                      rows++;
                                                    }

                                                    return parseInt(rows);
                                                    <?php }else{?>
                                                      return 2;
                                                      <?php }?>
                                                    }



                                                    /* Tempo para redimencionar o tamanho do mural */
                                                    setTimeout(initPage, 500);
                                                    function initPage(){
                                                      var grid = $('.grid-stack').data('gridstack');
                                                      var mural = document.getElementById("mural");
                                                      grid.resize("#mural", mural.getAttribute("data-gs-width"), setAlturaMural());
                                                    }

                                                    /* Mudar de página na tabela de clientes */
                                                    var page = 1;
                                                    var filtro = "";
                                                    listaClientes();
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

                                                      listaClientes();
                                                    }

                                                    $("#pesquisaCliente").keyup(function(){
                                                      page = 1;
                                                      filtro = $(this).val();
                                                      listaClientes();
                                                    });

                                                    function listaClientes(){
                                                      $.ajax({
                                                        method: "POST",
                                                        url: "../application/cardListaClientes.php",
                                                        data: {
                                                          page: page,
                                                          filtro: filtro
                                                        },
                                                        success: function(result){
                                                          $("#tabelaCliente").html(result);
                                                        }
                                                      });
                                                    }

                                                    $("#btnSubmit").click(function(){
                                                      page = 1;
                                                      geraRelatorio();
                                                    });
																									<?php }?>
                                                    </script>

<script type="text/javascript">
try{Typekit.load({ async: true });}catch(e){}
$(".messages").animate({ scrollTop: $(document).height() }, "fast");
$("#profile-img").click(function() {
	$("#status-options").toggleClass("active");
});
$(".expand-button").click(function() {
  $("#profile").toggleClass("expanded");
	$("#contacts").toggleClass("expanded");
});
$("#status-options ul li").click(function() {
	$("#profile-img").removeClass();
	$("#status-online").removeClass("active");
	$("#status-away").removeClass("active");
	$("#status-busy").removeClass("active");
	$("#status-offline").removeClass("active");
	$(this).addClass("active");
	if($("#status-online").hasClass("active"))
		$("#profile-img").addClass("online");
	else if ($("#status-away").hasClass("active"))
		$("#profile-img").addClass("away");
	else if ($("#status-busy").hasClass("active"))
		$("#profile-img").addClass("busy");
	else if ($("#status-offline").hasClass("active"))
		$("#profile-img").addClass("offline");
	else
		$("#profile-img").removeClass();
	$("#status-options").removeClass("active");
});
function newMessage() {
	message = $(".message-input input").val();
	if($.trim(message) === '')
		return false;
	$('<li class="sent"><img src="http://emilcarlsson.se/assets/mikeross.png" alt="" /><p>' + message + '</p></li>').appendTo($('.messages ul'));
	$('.message-input input').val(null);
	$('.contact.active .preview').html('<span>You: </span>' + message);
	$(".messages").animate({ scrollTop: $(document).height() }, "fast");
}
$('.submit').click(function() {
  newMessage();
});
/*
$(window).on('keydown', function(e) {
  if (e.which == 13) {
    newMessage();
    return false;
  }
});*/
</script>

</body>
</html>
