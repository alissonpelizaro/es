<?php
include '../application/cadastramensagem.php';
include '../application/relGrupo.php';
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
        <h3 class="padrao"> <?php if (!$edicao){ echo "Nova"; } else { echo "Editar"; } ?> mensagem</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="mural">Mural</a></li>
          <li class="breadcrumb-item"><?php if (!$edicao){ echo "Nova"; } else { echo "Editar"; } ?> mensagem</li>
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
              <h4><?php if (!$edicao){ echo "Cadastrar nova"; } else { echo "Editar"; } ?> mensagem de mural</h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/cadastramensagem" method="post">
                  <input type="hidden" name="tipo" value="<?php if (!$edicao){ echo "nova"; } else { echo "edit"; } ?>"><?php
                  if($edicao){
                    ?>
                    <input type="hidden" name="hash" value="<?php echo $_GET['hash']*7; ?>">
                    <?php
                  } ?>
                  <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Título da mensagem <i>(opcional)</i></label>
                        <div class="col-sm-12">
                          <input type="text" class="form-control" maxlength="90" name="titulo" value="<?php if($edicao){ echo $mural['titulo']; } ?>" placeholder="Título (opcional)">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Conteúdo da mensagem</label>
                            <div class="col-sm-12">
                              <textarea name="conteudo" required class="form-control" rows="8" cols="80" style="height: 120px" placeholder="Conteúdo da mensagem"><?php if($edicao){ echo $mural['desc']; } ?></textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Criticidade:</label>
                        <div class="col-sm-12">
                          <select class="form-control" name="criticidade">
                            <option value="2" <?php if($edicao){ if($mural['criticidade'] == '2'){ echo "selected"; } } ?>>Normal</option>
                            <option value="3" <?php if($edicao){ if($mural['criticidade'] == '3'){ echo "selected"; } } ?>>Baixa</option>
                            <option value="1" <?php if($edicao){ if($mural['criticidade'] == '1'){ echo "selected"; } } ?>>Alta</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Grupos alvo: (opcional)</label>
                        <?php if($grupos){ ?>
                          <div class="custom-control custom-checkbox" style="margin-left: 25px;">
                            <input type="checkbox" name="grupos[]" <?php if(!$edicao) { echo "checked"; } ?> class="custom-control-input" value="todos" id="gpTodos">
                            <label class="custom-control-label" for="gpTodos"><b>Todos</b></label>
                          </div>
                          <section id="dinamicGroup" style="display: <?php if(!$edicao) { echo "none"; } ?>;">
                            <?php

                            foreach ($grupos as $grupo) {
                              ?>
                              <div class="custom-control custom-checkbox" style="margin-left: 25px;">
                                <input type="checkbox" name="grupos[]" <?php if(strpos($gps,$grupo['idGrupo']) !== false){ echo "checked"; } ?> class="custom-control-input" value="<?php echo $grupo['idGrupo'] ?>" id="gp<?php echo $grupo['idGrupo'] ?>">
                                <label class="custom-control-label" for="gp<?php echo $grupo['idGrupo'] ?>"><?php echo $grupo['nome'] ?></label>
                              </div>
                              <?php
                            }
                            ?>
                            <span class="help-block">
                              <small><i id="alertTextEmail">Se não selecionar nenhuma opção, todos os agentes do sistema visualizarão essa mensagem</i></small>
                            </span>
                          </section>
                        <?php } else {
                          ?>
                          <i style="font-size: 12px;">Nenhum grupo foi criado</i>
                          <?php
                        } ?>
                      </div>
                      <hr>
                      <div class="form-group">
                        <div class="custom-control custom-checkbox" style="margin-left: 25px;">
                          <input type="checkbox" <?php if($edicao && $mural['expira'] != '1000-01-01 00:00:00'){ echo 'checked'; } ?> class="custom-control-input" id="checkAlarm">
                            <label class="custom-control-label" for="checkAlarm">Essa mensagem tem validade</label>
                          </div>
                        </div>
                        <div class="row" id="sessaoDataIn" style="display: <?php if(!$edicao || $mural['expira'] == '1000-01-01 00:00:00') { echo "none"; } ?>;">
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label class="junta" style="width: 100%;">Data de expiração<?php if($edicao && $mural['expira'] != '1000-01-01 00:00:00'){ echo " atual (".$expira.")"; }?>:</label>
                                <br>
                                <input type='text' id="dataInLembrete" name="expiracao" class="form-control datepicker-here" data-language='pt' minDate="<?php echo date('Y-m-d'); ?>" data-position="top center" placeholder="<?php if($edicao){ echo "Nova"; } else { echo "Defina a"; } ?> data de vencimento" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- /# column -->
                      </div>
                      <div class="row">
                        <div class="center">
                          <br>
                          <a href="mural">
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

    $(document).ready(function() {
      var selected;
      $('.datepicker-here').datepicker({
        language: 'pt',
        minDate: new Date(), // Now can select only dates, which goes after today
      });
    });

    $( "#gpTodos" ).change(function() {
      if(this.checked){
        $("#dinamicGroup").hide();
      } else {
        $("#dinamicGroup").show();
      }
    });

    $( "#checkAlarm" ).change(function() {
      if(this.checked){
        $("#sessaoDataIn").show();
        document.getElementById('dataInLembrete').required = <?php if($edicao){ echo "false"; } else { echo "true"; } ?>;
      } else {
        $("#sessaoDataIn").hide();
        document.getElementById('dataInLembrete').required = false;
        limpaInput();
      }
    });

    </script>
  </body>

  </html>
