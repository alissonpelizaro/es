<?php
include '../application/agendaTecnico.php';
include 'inc/head.php';
//Define nível de restrição da página
$allowUser = array('dev', 'tecnico');
checaPermissao($allowUser);
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
        <h3 class="padrao">MyOmni<i>Agenda</i></h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">MyOmni<i>Agenda</i></li>
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
              <?php if($erro){ ?>
                <center>
                  <h3 class="text-muted"><i>Regra de negócio não definida.</i></h3>
                </center>
              <?php } else { ?>
                <div class="jumbotron j-relative">
                  <div class="">
                    <h1 class="display-6"><i class="fa fa-calendar" aria-hidden="true"></i> Agenda do técnico<i><sub style="font-size: 25px;">MyOmni</sub></i></h1>
                  </div>
                  <hr style="width: 80%;">
                  <div class="bg-light box-wiki padroniza-wiki">
                    <div class="row">
                      <div class="col-12">
                        <div class="row">
                          <div id="calendario-icone" class="col-4">
                            <div class="icon-calendar" onMouseOver="mostrarElemento('calendario', 'inline');" onMouseOut="mostrarElemento('calendario', 'none');">
                              <span class="">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                              </span>
                            </div>
                          </div>
                          <form name="formAttAgenda" action="agendaTecnico<?php if(isset($_GET['view']) && $_GET['view'] == 'semana'){ echo "?view=semana";}; ?>" method="POST">
                            <input id="dataCalendar" type="hidden" name="data">
                          </form>
                          <div class="col-4" hidden="true">
                            <div class="botoes-agenda-tecnico-centro">
                              <div class="btn-group">
                                <button id="botao-dia" type="button" class="btn btn-info btn-outline btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-info btn-outline btn-sm"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-8"><!--Se for colocar os botão das setar, mudar esse col para 4-->
                            <div class="botoes-agenda-tecnico-direito">
                              <div class="btn-group">
                                <button onclick="window.location.assign('agendaTecnico?view=dia')" type="button" class="btn btn-info btn-outline btn-sm"><b>Dia</b></button>
                                <button onclick="window.location.assign('agendaTecnico?view=semana')" type="button" class="btn btn-info btn-outline btn-sm"><b>Semana</b></button>
                                <!--<button onclick="window.location.assign('agendaTecnico?view=mes')" type="button" class="btn btn-info btn-outline btn-sm"><b>Mês</b></button>-->
                              </div>
                            </div>
                          </div>
                        </div>
                        <div id="calendario" class="row enterness-fade" onMouseOver="mostrarElemento('calendario', 'inline');" onMouseOut="mostrarElemento('calendario', 'none');">
                          <div class="col-7">
                            <div id="calendario-card" class="card">
                              <div class="year-calendar"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Calendário do dia -->
                    <?php if($view == "dia"){ ?>
                      <div class="row">
                        <div class="col-12">
                          <div class="secao-agente-tecnico">
                            <div class="col-index-agenda-tecnico">
                              <?php
                              $hLoop = $hIni;
                              $hLoopAnt = null;
                              $mLoop = 0;
                              $auxM = 0;
                              $horaControl = array();
                              for ($i=0; $i < $th; $i++) {
                                $horaControl[$i] = array(
                                  'hora' => (int) $hLoop,
                                  'min' => (int) $mLoop
                                );
                                ?>
                                <div class="rh-index-agenda">
                                  <p><?php if($hLoop != $hLoopAnt){ echo setHoraAgenda($hLoop)."h"; }?><span><?php if($mLoop == 0) { echo "00"; } else { echo $mLoop; } ?></span></p>
                                </div>
                                <?php
                                $mLoop = $mLoop+$tint;
                                $hLoopAnt = $hLoop;
                                if($mLoop >= 60){
                                  $mLoop = 0;
                                  $hLoop++;
                                }
                              } ?>
                            </div>
                            <div class="col-agente-tecnico">
                              <div class="col-agente-head-tecnico">
                                <h2 class="dia-da-semana padrao"><b><?php echo diaESemana($data) ?> </b></h2>
                                <div class="linhaMesAno">- <?php echo mesEAno($data); ?></div>
                              </div>
                              <div class="section-horarios-tecnico">
                                <?php foreach ($horaControl as $hora) {
                                  $agora = fixHora($hora['hora'].":".$hora['min']);
                                  $diaIndex = explode('-', $data);
                                  $index = $agora."-".$diaIndex[2];
                                  if(isset($arrayBlocks[$index])){
                                    $bloqueio = $arrayBlocks[$index]['motivo'];
                                  } else {
                                    $bloqueio = setBloqueio($jornada, $agora);
                                  }
                                  ?>
                                  <div class="hr-agenda hr-agenda-<?php echo $bloqueio; ?> cursorDefault">
                                    <span><?php
                                    if($bloqueio == "fora"){
                                      echo "Fora de horário";
                                    } else if($bloqueio == "almoco"){
                                      echo "<p><span>Almoço</span></p>";
                                      echo "<i>".retHoraAgenda($jornada, false, "almoco")."</i>";
                                    } else if($bloqueio == 'folga'){
                                      echo "<p><span>Folga</span></p>";
                                      echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                    } else if($bloqueio == 'ferias'){
                                      echo "<p><span>Férias</span></p>";
                                    } else if($bloqueio == 'pessoal'){
                                      echo "<p><span>Motivos pessoais</span></p>";
                                      echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                    } else if($bloqueio == 'outros'){
                                      echo "<p><span>Outros</span></p>";
                                      echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                    }
                                    ?></span>
                                  </div>
                                  <?php
                                } ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Calendário da semana -->
                    <?php } else if($view == "semana"){ ?>
                      <div class="row">
                        <div class="col-12 secao-agente-tecnico">
                          <h2 class="dia-da-semana padrao"><b><?php echo nomeMes($dataIne, $dataFim); ?></b></h2>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="">
                            <div class="col-index-agenda-tecnico-mes">
                              <?php
                              $hLoop = $hIni;
                              $hLoopAnt = null;
                              $mLoop = 0;
                              $auxM = 0;
                              $horaControl = array();
                              for ($i=0; $i < $th; $i++) {
                                $horaControl[$i] = array(
                                  'hora' => (int) $hLoop,
                                  'min' => (int) $mLoop
                                );
                                ?>
                                <div class="rh-index-agenda">
                                  <p><?php if($hLoop != $hLoopAnt){ echo setHoraAgenda($hLoop)."h"; }?><span><?php if($mLoop == 0) { echo "00"; } else { echo $mLoop; } ?></span></p>
                                </div>
                                <?php
                                $mLoop = $mLoop+$tint;
                                $hLoopAnt = $hLoop;
                                if($mLoop >= 60){
                                  $mLoop = 0;
                                  $hLoop++;
                                }
                              } ?>
                            </div>
                            <?php
                            if(isset($_POST["data"]) && $_POST["data"] != date('Y-m-d')){
                              $dias = array();
                              $indexDias = 0;
                              for($i = 0; $i <= 6; $i++){
                                $dia = margemDeDias($i, $dataIne); ?>
                                <div class="col-agente-mes">
                                  <div class="col-agente-head">
                                    <?php if($dia == date('d')){ ?>
                                      <b class="dia-por-senama padrao"><?php echo $dia; ?></b>
                                      <h4 class="dia-da-semana-mes padrao"><?php echo semanaProNumero($i); ?></h4>
                                    <?php } else { ?>
                                      <b class="dia-por-senama"><?php echo $dia; ?></b>
                                      <h4 class="dia-da-semana-mes"><?php echo semanaProNumero($i); ?></h4>
                                    <?php } 
                                    $dias[$indexDias] = $dia;?>
                                  </div>
                                  <div class="section-horarios">
                                    <?php foreach ($horaControl as $hora) {
                                      $agora = fixHora($hora['hora'].":".$hora['min']);
                                      $index = $agora."-".$dias[$indexDias];
                                      
                                      if($indexDias > 6){
                                        $indexDias = 0;
                                      }
                                                                            
                                      if(isset($arrayBlocks[$index])){
                                        $bloqueio = $arrayBlocks[$index]['motivo'];
                                      } else {
                                        $bloqueio = setBloqueio($jornada, $agora);
                                      }
                                      ?>
                                      <div class="hr-agenda hr-agenda-<?php echo $bloqueio; ?> cursorDefault">
                                        <span><?php
                                        if($bloqueio == "fora"){
                                          echo "Fora de horário";
                                        } else if($bloqueio == "almoco"){
                                          echo "<p><span>Almoço</span></p>";
                                          echo "<i>".retHoraAgenda($jornada, false, "almoco")."</i>";
                                        } else if($bloqueio == 'folga'){
                                          echo "<p><span>Folga</span></p>";
                                          echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                        } else if($bloqueio == 'ferias'){
                                          echo "<p><span>Férias</span></p>";
                                        } else if($bloqueio == 'pessoal'){
                                          echo "<p><span>Motivos pessoais</span></p>";
                                          echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                        } else if($bloqueio == 'outros'){
                                          echo "<p><span>Outros</span></p>";
                                          echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                        }
                                        ?></span>
                                      </div>
                                      <?php
                                    }
                                  $indexDias++; ?>
                                  </div>
                                </div>
                              <?php }
                            } else {
                              $semanaMenos = date('w');
                              $semanaMais = date('w');
                              
                              switch ($semanaMais) {
                                case 0:
                                  $semanaMais= date('w')+1;
                                  break;
                                case 1:
                                  $semanaMais= date('w');
                                  break;
                                case 2:
                                  $semanaMais= date('w')-1;
                                  break;
                                case 3:
                                  $semanaMais= date('w')-2;
                                  break;
                                case 4:
                                  $semanaMais= date('w')-3;
                                  break;
                                case 5:
                                  $semanaMais= date('w')-4;
                                  break;
                                case 6:
                                  $semanaMais= date('w')-5;
                                  break;
                              }
                              
                              $dias = array();
                              $indexDias = 0;
                              for($i = 0; $i <= 6; $i++){ ?>
                                <div class="col-agente-mes">
                                  <div class="col-agente-head">
                                    <?php
                                      if(date('w') == $i){ ?>
                                      <b class="dia-por-senama padrao"><?php echo date('d') ?></b>
                                      <?php $hoje = true;
                                      $dias[$i] = date('d');
                                    } else {
                                      if(date('w') > $i){
                                        $diferenca = date('d', strtotime('-'.$semanaMenos.' days'));
                                        $semanaMenos--;
                                        $dias[$i] = $diferenca;?>
                                        <b class="dia-por-senama"><?php echo $diferenca ?></b>
                                      <?php } else {
                                        $diferenca = date('d', strtotime('+'.$semanaMais.' days'));
                                        $semanaMais++;
                                        $dias[$i] = $diferenca;?>
                                        <b class="dia-por-senama"><?php echo $diferenca ?></b>
                                      <?php }
                                      $hoje = false; ?>
                                    <?php } ?>
                                    <h4 class="dia-da-semana-mes <?php if($hoje){echo 'padrao';} ?>"><?php echo semanaProNumero($i) ?></h4>
                                  </div>
                                  <div class="section-horarios">
                                    <?php 
                                      foreach ($horaControl as $hora) {
                                        $agora = fixHora($hora['hora'].":".$hora['min']);
                                        $index = $agora."-".$dias[$indexDias];
                                        
                                        if($indexDias > 6){
                                          $indexDias = 0;
                                        }
                                      if(isset($arrayBlocks[$index])){
                                        $bloqueio = $arrayBlocks[$index]['motivo'];
                                      } else {
                                        $bloqueio = setBloqueio($jornada, $agora);
                                      }
                                      ?>
                                      <div class="hr-agenda hr-agenda-<?php echo $bloqueio; ?> cursorDefault">
                                        <span><?php
                                        if($bloqueio == "fora"){
                                          echo "Fora de horário";
                                        } else if($bloqueio == "almoco"){
                                          echo "<p><span>Almoço</span></p>";
                                          echo "<i>".retHoraAgenda($jornada, false, "almoco")."</i>";
                                        } else if($bloqueio == 'folga'){
                                          echo "<p><span>Folga</span></p>";
                                          echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                        } else if($bloqueio == 'ferias'){
                                          echo "<p><span>Férias</span></p>";
                                        } else if($bloqueio == 'pessoal'){
                                          echo "<p><span>Motivos pessoais</span></p>";
                                          echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                        } else if($bloqueio == 'outros'){
                                          echo "<p><span>Outros</span></p>";
                                          echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                        }
                                        ?></span>
                                      </div>
                                      <?php
                                    } 
                                    $indexDias++;?>
                                  </div>
                                </div>
                              <?php }
                            }?>
                          </div>
                        </div>
                      </div>

                      <!-- Calendário do mês -->
                    <?php } else { ?>

                    <?php } ?>
                    <br>
                    <div class="row">
                      <div class="center">
                        <a href="inicio">
                          <button type="button" class="btn btn-secondary">Voltar</button>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
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
  <script type="text/javascript"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    "use strict";
    $('.year-calendar').pignoseCalendar({
      <?php if($view == "semana"){ ?>
        pickWeeks: true,
        multiple: true,
        <?php } else { ?>
          date: moment("<?php echo $data ?>"),
          <?php } ?>
          lang: 'pt',
          theme: 'blue', // light, dark, blue
          select: onSelectHandler

        });

        function onSelectHandler(date, context) {
          /**
          * @date is an array which be included dates(clicked date at first index)
          * @context is an object which stored calendar interal data.
          * @context.calendar is a root element reference.
          * @context.calendar is a calendar element reference.
          * @context.storage.activeDates is all toggled data, If you use toggle type calendar.
          * @context.storage.events is all events associated to this date
          */

          var $element = context.element;
          var $calendar = context.calendar;
          var $box = $element.siblings('.box').show();
          var text = '';

          if (date[0] !== null) {
            text += date[0].format('YYYY-MM-DD');
          }

          if (date[0] !== null && date[1] !== null) {
            text += ' ~ ';
          }
          else if (date[0] === null && date[1] == null) {
            text += 'nothing';
          }

          if (date[1] !== null) {
            text += date[1].format('YYYY-MM-DD');
          }

          $("#dataCalendar").val(text);
          document.formAttAgenda.submit();
        }
      });
      </script>
      <script type="text/javascript" language="JavaScript">
      function mostrarElemento(id, visibilidade) {
        document.getElementById(id).style.display = visibilidade;
      }
      </script>
    </body>
    </html>
