<?php
include '../application/agenda.php';
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
                  <h3 class="text-muted"><i>Regra de negócio inválida.</i></h3>
                </center>
              <?php } else { ?>
                <div class="jumbotron j-relative">
                  <div class="">
                    <h1 class="display-6"><i class="fa fa-calendar" aria-hidden="true"></i> Agenda de técnicos<i><sub style="font-size: 25px;">MyOmni</sub></i></h1>
                  </div>
                  <hr style="width: 80%;">
                  <div class="bg-light box-wiki padroniza-wiki">
                    <div class="row">
                      <div class="col-4">
                        <div id="calendario-icone" class="col-4">
                          <div class="icon-calendar"  onMouseOver="mostrarElemento('calendario', 'inline');" onMouseOut="mostrarElemento('calendario', 'none');">
                            <span class="icone-calendario">
                              <i class="fa fa-calendar" aria-hidden="true"></i>
                            </span>
                          </div>
                        </div>
                        <div id="calendario" class="row enterness-fade" onMouseOver="mostrarElemento('calendario', 'inline');" onMouseOut="mostrarElemento('calendario', 'none');">
                          <div class="col-7">
                            <div id="calendario-card" class="card">
                              <div class="year-calendar"></div>
                            </div>
                          </div>
                        </div>
                        <form class="enterness" name="formAttAgenda" action="agenda" method="post">
                          <input type="hidden" id="dataCalendar" name="data">
                        </form>
                      </div>
                      <div class="col-4">
                        <h2 class="text-center"><?php echo $dataSplit[2]." de ".retMes($dataSplit[1]). " de ".$dataSplit[0]; ?></h2>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-12">
                        <div class="secao-agente">
                          <div class="col-index-agenda">
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
                                <p><?php if($hLoop != $hLoopAnt){ echo (int) $hLoop. "<span>h"; } else { echo "<span>"; }?><?php if($mLoop == 0) { echo "00"; } else { echo $mLoop; } ?></span></p>
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
                          <?php foreach ($tecs as $tec) {
                            $jornada = retJornada($tec['filas']);
                            ?>
                            <div class="col-agente">
                              <div class="col-agente-head">
                                <div class="avatar-head-agenda">
                                  <img src="assets/avatar/<?php if($tec['avatar'] != ""){ echo $tec['avatar']; } else { echo "default.jpg"; } ?>" alt="">
                                </div>
                                <h4><?php echo fixName($tec['nome'] ." ". $tec['sobrenome']) ?></h4>
                              </div>
                              <div class="section-horarios">
                                <?php
                                $last = "";
                                foreach ($horaControl as $hora) {
                                  $agora = fixHora($hora['hora'].":".$hora['min']);
                                  $index = $agora."-".$tec['idUser'];
                                  if(isset($arrayBlocks[$index])){
                                    $bloqueio = $arrayBlocks[$index]['motivo'];
                                  } else {
                                    $bloqueio = setBloqueio($jornada, $agora);
                                  }
                                  ?>
                                  <div class="hr-agenda hr-agenda-<?php echo $bloqueio; ?>" onclick="setBlock('<?php echo $tec['idUser'] ?>', '<?php echo fixName($tec['nome'] ." ". $tec['sobrenome']) ?>', '<?php echo $bloqueio ?>', '<?php echo $data." " .$hora['hora'].":".$hora['min']; ?>')">
                                    <span><?php
                                    if($bloqueio == "fora" || $bloqueio == "fora-cont"){
                                      echo "Fora de horário";
                                    } else if($bloqueio == "almoco"){
                                      if($last != "almoco"){
                                        echo "<p><span>Almoço</span></p>";
                                        echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                        $last = "almoco";
                                      }
                                    } else if($bloqueio == 'folga' || $bloqueio == 'folga-cont'){
                                      if($last != "folga"){
                                        echo "<p><span>Folga</span></p>";
                                        echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                        $last = "folga";
                                      }
                                    } else if($bloqueio == 'ferias' || $bloqueio == 'ferias-cont'){
                                      if($last != "ferias"){
                                        echo "<p><span>Férias</span></p>";
                                        $last = "ferias";
                                      }
                                    } else if($bloqueio == 'pessoal' || $bloqueio == 'pessoal-cont'){
                                      if($last != 'pessoal'){
                                        echo "<p><span>Motivos pessoais</span></p>";
                                        echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                        $last = 'pessoal';
                                      }
                                    } else if($bloqueio == 'outros'){
                                      if($last != 'outros' || $bloqueio == 'outros-cont'){
                                        echo "<p><span>Outros</span></p>";
                                        echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                        $last = 'outros';
                                      }
                                    } else if($bloqueio == 'produtivo' || $bloqueio == 'produtivo-cont'){
                                      if($last != 'produtivo'){
                                        echo "<p><span>Pré-bloqueado</span></p>";
                                        //echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                        $last = 'produtivo';
                                      }
                                    } else {
                                      $last = "";
                                    }
                                    ?></span>
                                  </div>
                                  <?php
                                } ?>
                              </div>
                            </div>
                            <?php
                          } ?>
                        </div>
                      </div>
                    </div>
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
  <div class="background-modal-agenda" id="setBlockModalBackground" onclick="dismiss('setBlockModal')"></div>
  <div class="modal-agenda" id="setBlockModal">
    <div class="modal-agenda-header">
      <h3>Adicionar bloqueio</h3>
      <span class="spanCloseModalAgenda" onclick="dismiss('setBlockModal')">&times;</span>
    </div>
    <form class="form-horizontal enterness" action="../application/setbloqueio" method="post">
      <div class="modal-agenda-body">
        <div class="row">
          <div class="col-12">
            <p>Definir bloqueio para <b id="nomeTecModal"></b></p>
          </div>
        </div>
        <input type="hidden" name="tecnico" id="idTecnicoHidden" value="">
        <input type="hidden" name="origin" value="diariogestor">
        <div class="row">
          <div class="col-9">
            <div class="form-group">
              <label for="databloqueio" class="control-label junta">Data do bloqueio</label>
              <input type="text" class="form-control datepicker-here" id="dataBlockIni" name="dataini" value="" data-position="left top" placeholder="Data do bloqueio">
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="databloqueio" class="control-label junta">Hora</label>
              <input type="text" class="form-control hora_mask" id="horaBlockIni" name="horaini" value="" placeholder="00:00">
            </div>
          </div>
        </div>
        <div class="row enterness-fade" style="display: none;" id="divFormFinalBloqueio">
          <div class="col-9">
            <div class="form-group">
              <label for="databloqueio" class="control-label junta">Data final do bloqueio</label>
              <input type="text" class="form-control datepicker-here" id="dataFinalBloqueio" name="datafim" value="" data-position="left top" placeholder="Data final do bloqueio">
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="databloqueio" class="control-label junta">Hora</label>
              <input type="text" class="form-control hora_mask" id="horaFinalBloqueio" name="horafim" value="" placeholder="00:00">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <p class="text-right txt-intervalo-bloqueio" id="btnIntervaloBloqueio">Adicionar intervalo de bloqueio</p>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="databloqueio" class="control-label junta">Motivo</label>
              <select class="form-control" name="motivo" id="selectMotivo">
                <option value="folga">Folga</option>
                <option value="ferias">Férias</option>
                <option value="pessoal">Motivos pessoais</option>
                <option value="outros">Outros</option>
              </select>
            </div>
          </div>
          <div class="col-12 enterness-fade" id="descMotivoDiv" style="display: none">
            <div class="form-group">
              <label for="databloqueio" class="control-label junta">Descrição do motivo:</label>
              <input type="text" class="form-control" id="descricaoMotivo" name="descricaomotivo" value="" placeholder="Descrição do motivo">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-agenda-footer">
        <hr>
        <div class="row">
          <div class="col-12">
            <div class="pull-right">
              <button type="button" class="btn btn-dark btn-outline btn-focus" onclick="dismiss('setBlockModal')">Voltar</button>
              <button type="submit" id="btnSubmitFormModal" class="btn btn-info btn-focus">Bloquear</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- End Wrapper -->
  <?php include 'inc/scripts.php'; ?>
  <script type="text/javascript">

  function setBlock(id, nome, block, data){
    if(block == "livre"){
      $("#nomeTecModal").html(nome);
      $("#idTecnicoHidden").val(id);

      $("#btnIntervaloBloqueio").html('Adicionar intervalo de bloqueio');
      $("#divFormFinalBloqueio").hide();

      $("#dataFinalBloqueio").val("");
      $("#horaFinalBloqueio").val("");

      $("#dataFinalBloqueio").prop('required',false);
      $("#horaFinalBloqueio").prop('required',false);

      data = data.split(" ");
      dataDef = setupDataModal(data[0]);
      horaDef = setupHoraModal(data[1]);

      $("#dataBlockIni").val(dataDef);
      $("#horaBlockIni").val(horaDef);


      $("#setBlockModal").show();
      $("#setBlockModalBackground").show();
    } else if(block != "fora" && block != "almoco"){
      //alert(id+" "+block+" "+data);
    }
  }

  function dismiss(id){
    $("#setBlockModalBackground").hide();
    $("#"+id).hide();
    limpaFormModal();
  }

  function setupDataModal(data){
    dataDef = data.split("-");
    dataDef = dataDef[2]+"/"+dataDef[1]+"/"+dataDef[0];
    return dataDef;
  }

  function setupHoraModal(hora){
    hora = hora.split(":");
    if(hora[0] < 10){
      hora[0] = "0" + hora[0];
    }
    if(hora[1] < 10){
      hora[1] = "0" + hora[1];
    }
    return hora[0] + ":" + hora[1];
  }

  function limpaFormModal(){
    return true;
  }

  $(document).ready(function(){

    $("#iconeOpenCalendar").hover(function(){
      $("#calendarHover").show();
    });

    $(function() {
      "use strict";
      $('.year-calendar').pignoseCalendar({
        date: moment('<?php echo $data ?>'),
        lang: 'pt',
        theme: 'blue', // light, dark, blue
        select: onSelectHandler
      });
    });

    function onSelectHandler(date, context) {
      var $element = context.element;
      var $calendar = context.calendar;
      var $box = $element.siblings('.box').show();
      var text = '';

      if (date[0] !== null) {
        text += date[0].format('YYYY-MM-DD');
      }

      if (date[0] !== null && date[1] !== null) {
        text += ' ~ ';
      } else if (date[0] === null && date[1] == null) {
        text += 'nothing';
      }

      if (date[1] !== null) {
        text += date[1].format('YYYY-MM-DD');
      }

      $("#dataCalendar").val(text);
      document.formAttAgenda.submit();
    }

    var openIntervalCmd = false;

    $("#btnIntervaloBloqueio").click(function (){
      var atualShow = document.getElementById("divFormFinalBloqueio").style.display;
      openIntervalCmd = 'manual';

      if(atualShow == "none"){
        $("#btnIntervaloBloqueio").html('Remover intervalo de bloqueio');
        $("#divFormFinalBloqueio").show();

        $("#dataFinalBloqueio").prop('required',true);
        $("#horaFinalBloqueio").prop('required',true);
      } else {
        $("#btnIntervaloBloqueio").html('Adicionar intervalo de bloqueio');
        $("#divFormFinalBloqueio").hide();

        $("#dataFinalBloqueio").val("");
        $("#horaFinalBloqueio").val("");

        $("#dataFinalBloqueio").prop('required',false);
        $("#horaFinalBloqueio").prop('required',false);
      }
    });

    $("#selectMotivo").change(function(){
      var selected = $("#selectMotivo").val();
      if(selected == "outros"){
        $("#descMotivoDiv").show();
        $("#descricaoMotivo").prop('required',true);
      } else if(selected == "ferias"){
        openIntervalCmd = 'auto';
        var atualShow = document.getElementById("divFormFinalBloqueio").style.display;
        if(atualShow == "none"){
          $("#btnIntervaloBloqueio").html('Remover intervalo de bloqueio');
          $("#divFormFinalBloqueio").show();

          $("#dataFinalBloqueio").prop('required',true);
          $("#horaFinalBloqueio").prop('required',true);
        }
        $("#descMotivoDiv").hide();
        $("#descricaoMotivo").prop('required',false);
      } else {
        var atualShow = document.getElementById("divFormFinalBloqueio").style.display;
        if(atualShow == "" && openIntervalCmd && openIntervalCmd == "auto"){
          $("#btnIntervaloBloqueio").html('Adicionar intervalo de bloqueio');
          $("#divFormFinalBloqueio").hide();

          $("#dataFinalBloqueio").val("");
          $("#horaFinalBloqueio").val("");

          $("#dataFinalBloqueio").prop('required',false);
          $("#horaFinalBloqueio").prop('required',false);
        }
        $("#descMotivoDiv").hide();
        $("#descricaoMotivo").prop('required',false);
      }
    });

    $('.datepicker-here').datepicker({
      language: 'pt',
      minDate: new Date() // Now can select only dates, which goes after today
    });

    $('.hora_mask').mask('00:00');

    $("#horaBlockIni").change(function(){
      if(!checaHora($("#horaBlockIni").val())){
        $("#horaBlockIni").val("");
        document.getElementById("horaBlockIni").style.border = "1px solid red";
      } else {
        document.getElementById("horaBlockIni").style.border = "1px solid #eee";
      }
    });

    $("#horaFinalBloqueio").change(function(){
      if(!checaHora($("#horaFinalBloqueio").val())){
        $("#horaFinalBloqueio").val("");
        document.getElementById("horaFinalBloqueio").style.border = "1px solid red";
      } else {
        document.getElementById("horaFinalBloqueio").style.border = "1px solid #eee";
      }
    });

    function checaHora(hora){

      hora = hora.split(":");
      if(Array.isArray(hora)){
        if(hora[0] > 23 || hora[1] > 59){
          return false;
        } else {
          return true;
        }
      } else {
        return false;
      }

    }

    <?php if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'success'){
      ?>
      swal("Feito!", "Novo bloqueio foi cadastrado!", "success");
      <?php
    } ?>

  });



</script>
<script type="text/javascript" language="JavaScript">
function mostrarElemento(id, visibilidade) {
  var objDiv = document.getElementById("calendario-icone");
  if(visibilidade == "inline"){
    //console.log(objDiv);
    objDiv.style.color = "#eee";
  }else{
    objDiv.style.color = "white";
  }
  document.getElementById(id).style.display = visibilidade;
}
</script>
</body>

</html>
