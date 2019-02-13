<?php
include '../application/inicio.php';
include 'inc/head.php';
?>
<!-- Main wrapper -->
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
    <div class="container-fluid">
      <div class="row">
        <?php if($_SESSION['tipo'] != 'agente' && $_SESSION['tipo'] != 'gestor' && $_SESSION['tipo'] != 'tecnico'){ ?>
          <!-- Start Page Content -->
          <!-- VISUALIZAÇÃO SOMENTE DE SUPERVISORES > -->
          <div class="col-md-12">
            <div class="row">
              <?php if ($cardAtendimento){ 
              	if ($favoritos && $favoritos["atendimento"]) {?>
                <div class="col-md-6" id="cardAtendimento"></div>
              <?php }
              } ?>
              <div class="col-md-<?php if(!$cardAtendimento || !$favoritos["atendimento"]){ echo "12"; } else { echo "6"; } ?>">
                <div class="row">
                  <div class="col-md-<?php if(!$cardAtendimento || !$favoritos["atendimento"]){ echo "3"; } else { echo "6"; } ?>">
                    <div class="card p-30">
                      <div class="media">
                        <div class="media-left meida media-middle">
                          <span><i class="fa fa-headphones f-s-40 color-primary"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                          <h2><?php echo $licenca->getTotalAgente() ?><span style="font-size: 15px;" class="text-muted">/<?php echo $licenca->getLicencaAgente() ?></span></h2>
                          <p class="m-b-0">Agentes</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-<?php if(!$cardAtendimento || !$favoritos["atendimento"]){ echo "3"; } else { echo "6"; } ?>">
                    <div class="card p-30">
                      <div class="media">
                        <div class="media-left meida media-middle">
                          <span><i class="fa fa-user-circle f-s-40 color-success"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                          <h2><?php echo $licenca->getTotalSupervisor() ?><span style="font-size: 15px;" class="text-muted">/<?php echo $licenca->getLicencaSupervisor() ?></span></h2>
                          <p class="m-b-0">Supervisores</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-<?php if(!$cardAtendimento || !$favoritos["atendimento"]){ echo "3"; } else { echo "6"; } ?>">
                    <div class="card p-30">
                      <div class="media">
                        <div class="media-left meida media-middle">
                          <span><i class="fa fa-user f-s-40 color-warning"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                          <h2><?php echo $licenca->getTotalAdministrador() ?><span style="font-size: 15px;" class="text-muted">/<?php echo $licenca->getLicencaAdministrador() ?></span></h2>
                          <p class="m-b-0">Administradores</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-<?php if(!$cardAtendimento || !$favoritos["atendimento"]){ echo "3"; } else { echo "6"; } ?>">
                    <div class="card p-30">
                      <div class="media">
                        <div class="media-left meida media-middle">
                          <span><i class="fa fa-briefcase f-s-40 color-danger"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                          <h2><?php echo $licenca->getTotalCoordenador() ?><span style="font-size: 15px;" class="text-muted">/<?php echo $licenca->getLicencaCoordenador() ?></span></h2>
                          <p class="m-b-0">Coordenadores</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>

        <?php if ($_SESSION['tipo'] != 'tecnico' && $_SESSION['tipo'] != 'gestor' && $util->getSectionPermission('conc')) {
          ?>
          <div class="col-12">
            <div class="card">
              <h4 class="card-title">Concessionárias</h4>
              <div class="card-body body-concessionarias">
                <?php
                if(!$logos){
                  ?>
                  <hr>
                  <center>
                    <h3 class="text-muted"><i>Nenhuma concessionária cadastrada.</i></h3>
                  </center>
                  <?php
                } else {
                  $aux = 0;
                  ?>
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs profile-tab" role="tablist">
                    <?php foreach ($logos as $logo) {

                      ?>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#logoC<?php echo $aux; ?>" role="tab" onclick="requestCasas('<?php echo $logo['logo'] ?>', 'logoC<?php echo $aux; ?>')" aria-selected="true">
                          <div class="bloco-casa-menu" id="logoC<?php echo $aux; ?>img">
                            <img src="assets/casas/<?php if($logo['logo'] != ""){ echo $logo['logo']; } else { echo "default.png"; } ?>" alt="Concessionária">
                          </div>
                        </a>
                      </li>
                      <?php
                      $aux++;
                    } ?>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content p-t-20">
                    <?php
                    $aux = 0;
                    foreach ($logos as $logo) {
                      ?>
                      <div class="tab-pane" id="logoC<?php echo $aux; ?>" role="tabpanel">
                      </div>
                      <?php
                      $aux++;
                    }
                    $aux++;
                    ?>
                  </div>
                  <?php
                } ?>
              </div>
            </div>
          </div>
          <?php
        } ?>
        <?php if($_SESSION['tipo'] != 'tecnico'){ ?>
          <?php if($_SESSION['tipo'] != 'gestor' && $util->getSectionPermission('mural')) { ?>
            <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Mural da supervisão</h4>
                  <div class="card-body">
                    <div class="row">
                      <?php if(!$msgs){
                        ?>
                        <span style="text-align: center; width: 100%; font-size: 13px;">
                          <i>O mural de mensagens está limpo</i>
                        </span>
                        <?php
                      } else {
                        $count = 0;
                        foreach ($msgs as $msg) {
                          $count++;
                          ?>
                          <div class="col-md-12" style="margin-bottom: 30px;">
                            <div class="card-body">
                              <!-- Nav tabs -->
                              <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#visao<?php echo $count ?>" role="tab"><span><i class="fa fa-tasks"></i></span></a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#det<?php echo $count ?>" role="tab"><span><i class="fa fa-info"></i></span></a> </li>
                              </ul>
                              <!-- Tab panes -->
                              <div class="tab-content tabcontent-border" style="border-left: 2px solid <?php echo retCorMuralBox($msg['criticidade']) ?>">
                                <div class="tab-pane active" id="visao<?php echo $count ?>" role="tabpanel">
                                  <div class="p-20">
                                    <h4 class="text-<?php echo retCorMuralTexto($msg['criticidade']) ?>"><?php echo $msg['titulo'] ?></h4>
                                    <h5><?php echo nl2br($msg['desc']) ?></h5>
                                    <i style="font-size: 12px;">Cadastrada por: <?php echo retUserMural($nomes, $msg['idUser']) ?> em <?php echo dataBdParaHtml($msg['data']) ?></i>
                                  </div>
                                </div>
                                <div class="tab-pane  p-20" id="det<?php echo $count ?>" role="tabpanel">
                                  <div class="p-1">
                                    <h6 class="m-b-15">Informações dessa mensagem:</h6>
                                    <section style="font-size: 11px;">
                                      <span><b>Criticidade:</b> <?php echo retCriticidade($msg['criticidade']) ?></span><br>
                                      <span><b>Data de cadastro:</b> <?php echo dataBdParaHtml($msg['data']) ?></span><br>
                                      <span><b>Data de expiração:</b> <?php echo retExpMural($msg['expira']) ?></span><br>
                                      <span><b>Autor(a):</b> <?php echo retUserMural($nomes, $msg['idUser']) ?></span><br>
                                      <span><b>Editada por:</b> <?php echo retUserMural($nomes, $msg['idUserEdit']) ?></span>
                                    </section>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php
                        }
                      } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php } else { ?>
            <div class="col-6">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Minha regra de negócio</h4>
                  <div class="card-body">
                    <br>
                    <?php
                    if(!$tecnico){
                      ?>
                      <i class="text-danger"><b>Importante!</b> Você precisa cadastrar, pelo menos, um técnico consultor.</i>
                      <br><br>
                      <a href="novotecnico">
                        <button type="button" class="btn btn-sm btn-danger">Cadastrar agora</button>
                      </a>
                      <?php
                    } else {
                      if(!$last){
                        ?>
                        <i><b>Você deve definir uma regra de negócio para começar a utilizar o sistema.</b></i>
                        <br><br>
                        <a href="novaregra">
                          <button type="button" class="btn btn-sm btn-info">Definir agora</button>
                        </a>
                        <?php
                      } else if($sitRegra == 'dia'){

                        ?>
                        <i>Muito bem! Sua regra de negócio está em dia.</i>
                        <br><br>
                        <a href="minhacasa">
                          <button type="button" class="btn btn-sm btn-info">Ver regra atual</button>
                        </a>
                        <?php

                      } else if($sitRegra == 'pendente'){
                        ?>
                        <i class="text-danger"><b>Atenção!</b> Está na hora de definir a regra de negócio para o próximo mês.</i>
                        <br><br>
                        <a href="novaregra">
                          <button type="button" class="btn btn-sm btn-danger">Definir agora</button>
                        </a>
                        <?php
                      } else {
                        ?>
                        <i class="text-danger"><b>Atenção!</b> A sua regra de negócio está <strong>atrasada</strong>. Você deve definir uma regra de negócio agora.</i>
                        <br><br>
                        <a href="novaregra">
                          <button type="button" class="btn btn-sm btn-danger">Definir agora</button>
                        </a>
                        <?php
                      }
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php }
        if ($favoritos && $favoritos["lembrete"]) { ?>
        <div class="col-md-6">
          <div class="card">
            <h4 class="card-title">
              Meus post-its
              <span>
                <button class="btn btn-sm btn-outline btn-info btn-new" data-toggle="modal" data-target="#modalNovoLembrete">
                  Novo post-it
                </button>
              </span>
            </h4>
            <div class="row">
              <?php if(!$lembretes){
                ?>
                <span style="text-align: center; width: 100%; font-size: 13px;">
                  <i>Você não tem nenhum post-it</i>
                </span>
                <?php
              } else {
                foreach ($lembretes as $lembrete) {
                  ?>
                  <div class="col-md-6 col-lg-6">
                    <div class="card bg-<?php echo $lembrete['cor']; ?> p-20 postit-box">
                      <div class="trash-postit-edit"
                      onclick="setEditLembrete('<?php echo $lembrete['titulo'];?>', '<?php echo $lembrete['desc'];?>', '<?php echo dataBdParaHtml($lembrete['alarme']);?>', '<?php echo $lembrete['idLembrete']*17;?>');"
                      data-toggle="modal" data-target="#modalEditLembrete">
                      <i class="fa fa-pencil-square-o"></i>
                    </div>
                    <div class="trash-postit" onclick="setTrash('<?php echo $lembrete['idLembrete']*17; ?>')">
                      <i class="fa fa-times"></i>
                    </div>
                    <div class="media widget-ten">
                      <div class="media-body media-text-right">
                        <h2 class="color-white"><?php echo $lembrete['titulo'] ?></h2>
                        <p class="m-b-5"><?php echo $lembrete['desc'] ?></p>
                        <?php if($lembrete['alarme'] != '1000-01-01 00:00:00'){ ?>
                          <p class="m-b-0 notif-postit ">Notificação em <?php echo dataBdParaHtml($lembrete['alarme']) ?></p>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }
            } ?>
          </div>
        </div>
      </div>
    <?php } ?>
      <!-- End PAge Content -->
    </div>
    <?php //include 'inc/chatinc.php'; ?>
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
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">

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

<?php if($cardAtendimento){
  ?>
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
  <?php
} ?>

function requestCasas(name, local){
  $('.bloco-casa-menu-ativa').removeClass("bloco-casa-menu-ativa");
  $("#"+local+"img").addClass('bloco-casa-menu-ativa');

  $.ajax({
    type: "POST",
    data: {
      hash : name
    },
    url: "../application/getCasas",
    success: function(result){
      $("#"+local).html(result);
    }
  });

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
</script>

</body>

</html>
