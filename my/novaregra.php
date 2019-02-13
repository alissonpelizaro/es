<?php
include '../application/novaregra.php';
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
        <h3 class="padrao">Nova regra de negócio</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Nova regra de negócio</li>
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
            <div class="card-title text-center">
              <h3>Regra de negócio para <b><?php echo retMes($mes) ?>/<?php echo $ano ?></b></h3>
              <hr style="width: 30%;">
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/setRegra" method="post">
                  <input type="hidden" name="vigor" value="<?php echo $mes."-".$ano; ?>">
                  <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                      <h4>Horário de atendimento (dia de semana)</h4>
                      <div class="row">
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Inicio</label>
                          <input class="form-control hora" name="entrada" id="inEntrada" onchange="checaHorario(this)" required placeholder="00:00">
                        </div>
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Saida</label>
                          <input class="form-control hora" name="saida" id="inSaida" onchange="checaHorario(this)" required placeholder="00:00">
                        </div>
                        <div class="col-sm-3 offset-4 form-group">
                          <label class="control-label junta">Horário limite para agendamento</label>
                          <input class="form-control hora" id="hrLimiteAgendamento" onchange="checaHorarioLimite(this, 'semana')" readonly name="limAg" required placeholder="00:00">
                        </div>
                      </div>
                      <hr>
                      <h4>Horário de atendimento (sábado)</h4>
                      <div class="row">
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Início (sábado)</label>
                          <input class="form-control hora" id="inEntradaSabado" onchange="checaHorarioSabado(this)" name="inicioSabado" required placeholder="00:00">
                        </div>
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Saída (sábado)</label>
                          <input class="form-control hora" id="inSaidaSabado" onchange="checaHorarioSabado(this)" name="fimSabado" required placeholder="00:00">
                        </div>
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Limite de agendamento (sábado)</label>
                          <input class="form-control hora" name="limAgSab" id="hrLimiteAgendamentoSabado" onchange="checaHorarioLimite(this, 'sabado')" readonly required placeholder="00:00">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Quantidade de atendimento</label>
                          <input class="form-control numRecal" name="qtdAtendimentoSab" id="qtdAtendimentoSab" required placeholder="0">
                        </div>
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Quantidade de plantão</label>
                          <input id="qpps" class="form-control numRecal" name="qpps" required placeholder="0">
                        </div>
                      </div>
                      <hr style="width: 30%;">
                      <h4>Escala dos sábados <i id="TitleEscalaSabado" style="display: none;">(<span id="escHrEnt"></span> às <span id="escHrSai"></span>)</i></h4>
                      <div class="row">
                        <div class="col-12">
                          <table class="table">
                            <thead>
                              <tr>
                                <th class="text-right" style='border-top: 0px solid white; width: 100px;'>Data</th>
                                <th class="text-center" style='border-top: 0px solid white;'>Consultores técnicos de escala</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $inputName = "";
                              foreach ($sabados as $sab) {
                                $str = explode("/", $sab);
                                $inputName .= "escala-".$str[0]."-".$str[1]."[-]";
                                ?>
                                <tr style="">
                                  <td class="text-info text-right" style="width: 100px; border: 0px solid white; padding-top: 18px;"><?php echo $sab ?></td>
                                  <td style="border: 0px solid white;">
                                    <select class="js-select select-tec-regra" id="tecEscala-<?php echo $str[0] . "-" . $str[1]; ?>" name="escala-<?php echo $str[0] . "-" . $str[1]; ?>[]" multiple="multiple" style="width: 100%;">
                                      <?php foreach ($user as $tec) {
                                        ?>
                                        <option value="<?php echo $tec['idUser'] ?>"><?php echo $tec['nome']. " ". $tec['sobrenome']; ?></option>
                                        <?php
                                      } ?>
                                    </select>
                                  </td>
                                </tr>
                                <?php
                              } ?>
                            </tbody>
                          </table>
                          <input type="hidden" name="arraySabados" value="<?php echo $inputName ?>">
                        </div>
                      </div>
                      <hr>
                      <h4>Regras gerais</h4>
                      <div class="row">
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Quantidade de parcelas</label>
                          <input class="form-control parc" name="quantidadeParcelas" id="inParcelas" required placeholder="0">
                        </div>
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Intervalo entre agendamentos</label>
                          <select class="form-control" id="selectIntervalo" onchange="checaPreviewTableBloqueio()" name="intervalo">
                            <option value="20">20 em 20 min</option>
                            <option value="30">30 em 30 min</option>
                            <option value="60">60 em 60 min</option>
                          </select>
                        </div>
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Quantidade de diagnóstico por dia</label>
                          <input class="form-control numRecal" name="qtdd" id="qtdd" required placeholder="0">
                        </div>
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Quantidade de técnicos mecânicos</label>
                          <input id="qtm" class="form-control numRecal" name="qtm" required placeholder="0">
                        </div>
                      </div>
                      <hr>
                      <h4>Recall Day</h4>
                      <div class="row">
                        <div class="col-sm-4 form-group">
                          <label class="control-label junta">Possui RecallDay?</label>
                          <select class="form-control" id="idRecallSelect" name="recallday">
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                          </select>
                        </div>
                        <div class="col-sm-2 form-group enterness-fade" id="RecallDayInput">
                          <label class="control-label junta">Dia do Recall:</label>
                          <input class="form-control numRecal" min="1" max="31" name="recall" id="inRecall" placeholder="1">
                        </div>
                      </div>
                      <hr>
                      <h3 class="text-center m-b-20">Técnicos</h3>
                      <div id="tabelaBloqueiosFixos" style="overflow-x: auto; ">
                      </div>
                      <input type="hidden" name="fixedBlocks" id="fixedBlockIn" value="">
                      <h4 class="m-t-20 text-muted">Escala de entrada e saída (banco de horas)</h4>
                      <div class="accordion" id="accordionExample">
                        <div class="card">
                          <?php
                          $i = 0;
                          $array = "";
                          $tecs = "";
                          $qtdTec = 0;
                          foreach ($user as $tec) {
                            $qtdTec++;
                            $tecs .= $tec['idUser']."-";
                          }
                          foreach($banco as $semana){
                            $array .= $semana."[-]";
                            ?>
                            <div class="card-header" id="heading<?php echo $i; ?>">
                              <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
                                  <?php echo $semana; ?>
                                </button>
                                <input type="hidden" id="qtdDiaSem<?php echo $i; ?>" name="qtdDiaSem<?php echo $i; ?>" value="<?php echo explodeQtdDiaSemana($semana); ?>">
                              </h5>
                            </div>

                            <div id="collapse<?php echo $i; ?>" class="collapse" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordionExample">
                              <div class="card-body">
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th>C.T.</th>
                                      <th>Entrada</th>
                                      <th>Saída</th>
                                      <th>Agendamento</th>
                                      <th>Observação</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    foreach ($user as $tec) {
                                      ?>
                                      <tr>
                                        <td>
                                          <?php
                                          echo $tec['nome']. " ". $tec['sobrenome'];
                                          ?>
                                        </td>
                                        <td width="127px">
                                          <input class="form-control hrEntBH hora imput-linha" id="inEntradaBancoH-<?php echo $i."-".$tec['idUser']?>" onchange="" name="inicioBancoH-<?php echo $i."-".$tec['idUser']?>" required placeholder="00:00" style="width: 50px;">
                                        </td>
                                        <td width="127px">
                                          <input class="form-control hrSaiBH hora imput-linha" id="inSaidaBancoH-<?php echo $i."-".$tec['idUser']?>" onchange="" name="saidaBancoH-<?php echo $i."-".$tec['idUser']?>" required placeholder="00:00" style="width: 50px;">
                                        </td>
                                        <td width="127px">
                                          <input class="form-control hrAgendamentoTabela hora imput-linha" id="inAgendamentoBancoH-<?php echo $i."-".$tec['idUser']?>" onchange="" name="agendamentoBancoH-<?php echo $i."-".$tec['idUser']?>" required placeholder="00:00" style="width: 50px;">
                                        </td>
                                        <td>
                                          <input class="form-control imput-linha" id="inObsBancoH-<?php echo $i."-".$tec['idUser']?>" name="obsBancoH-<?php echo $i."-".$tec['idUser']?>" maxlength="250">
                                        </td>
                                      </tr>
                                      <?php
                                    }
                                    ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <?php
                            $i++;
                          }
                          ?>
                          <input type="hidden" name="escDados" value="<?php echo $array."<->".$tecs; ?>">
                        </div>
                      </div>
                      <!--<hr style="width: 30%;">-->
                      <br>
                      <h4 class="m-t-20 text-muted"></h4>
                      <table class="table">
                        <thead>
                          <tr>
                            <th style='border-top: 0px solid red;'>C.T.</th>
                            <th colspan="2" style="text-align:center;border-top: 0px solid red;">Hr de almoço</th>
                            <th class="text-center" style='border-top: 0px solid red;width: 130px;'>Folgas</th>
                            <th class="text-center" style='border-top: 0px solid red;width: 140px;'>Férias</th>
                            <th class="text-center" style='border-top: 0px solid red;width: 130px;'>Bloq. pas.</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $j = 0;
                          foreach($user as $tec){
                            ?>
                            <tr>
                              <th>
                                <?php echo $tec['nome']. " ". $tec['sobrenome']; ?>
                              </th>
                              <th style="width: 50px;">
                                <div align="right">
                                  <input class="form-control hrEntAlmocoBH hora imput-linha" id="inHorarioAlmocoEnt-<?php echo $j ?>" onchange="" name="inHorarioAlmocoEnt-<?php echo $tec['idUser'] ?>" value="<?php echo retTecHoraAlmoco($db, $tec['idUser'], 'entrada') ?>" required placeholder="00:00" style="width: 50px;">
                                </div>
                              </th>
                              <th style="width: 50px;">
                                <div>
                                  <input class="form-control hrSaiAlmocoBH hora imput-linha" id="inHorarioAlmocoSai-<?php echo $j ?>" onchange="" name="inHorarioAlmocoSai-<?php echo $tec['idUser'] ?>" value="<?php echo retTecHoraAlmoco($db, $tec['idUser'], 'saida') ?>" required placeholder="00:00" style="width: 50px;">
                                </div>
                              </th>
                              <th>
                                <input id="folgas-<?php echo $j ?>"
                                type="text"
                                class="form-control datepicker-here imput-linha"
                                data-language='pt'
                                data-multiple-dates="31"
                                data-multiple-dates-separator=", "
                                data-position='top left'
                                style="width: 120px;text-align: center;"
                                onclick="idFolgaInput(this);"/>
                                <input hidden="true" id="folgasH-<?php echo $j ?>" name="folgas-<?php echo $tec['idUser'] ?>"/>
                              </th>
                              <th>
                                <input id="ferias-<?php echo $j ?>"
                                type="text"
                                class="form-control datepicker-here imput-linha"
                                data-language="pt"
                                data-range="true"
                                data-multiple-dates-separator=" - "
                                data-position='top left'
                                style="width: 125px;text-align: center;"
                                onclick="idFeriasInput(this);"/>
                                <input hidden="true" id="feriasH-<?php echo $j ?>" name="ferias-<?php echo $tec['idUser'] ?>"/>
                              </th>
                              <th>
                                <input id="blocPas-<?php echo $j ?>"
                                type="text"
                                class="form-control datepicker-here imput-linha"
                                data-language='pt'
                                data-multiple-dates="31"
                                data-multiple-dates-separator=", "
                                data-position='top left'
                                style="width: 120px;text-align: center;"
                                onclick="idBlocPasInput(this);"/>
                                <input hidden="true" id="blocPasH-<?php echo $j ?>" name="blocPas-<?php echo $tec['idUser'] ?>"/>
                              </th>
                            </tr>
                            <?php
                            $j++;
                          }
                          ?>
                        </tbody>
                      </table>
                      <br>
                      <hr>
                      <div class="row">
                        <div class="col-md-6 offset-md-3" id="divCalendario">
                          <h4 class="text-center">Calendário útil da regra</h4>
                          <div class="card">
                            <h5 class="body-head text-center text-muted"><?php echo retMes($mes) ?></h5>
                            <table class="table regraCal">
                              <thead>
                                <tr>
                                  <th class=" text-center">Dom</th>
                                  <th class=" text-center">Seg</th>
                                  <th class=" text-center">Ter</th>
                                  <th class=" text-center">Qua</th>
                                  <th class=" text-center">Qui</th>
                                  <th class=" text-center">Sex</th>
                                  <th class=" text-center">Sáb</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $dia = 1;
                                $qtdDiasSem = array();
                                for ($i=1; $i <= $qtdSem; $i++) {
                                  $qtdDiasSem['sem'.$i] = 0;
                                }
                                for ($i=0; $i < $qtdSem; $i++) {
                                  $pri = 1;
                                  $ult;
                                  $dSem = 0;

                                  ?>
                                  <tr>
                                    <?php for ($j=0; $j < 7; $j++) {
                                      if ($frstDay && $frstDay == $dSem) {
                                        ?>
                                        <td class=" text-center"><p onclick="clickDay('1', '<?php echo $i+1; ?>')" id="calDay1">1</p></td>
                                        <?php

                                        $frstDay = false;
                                        $dia++;
                                        $qtdDiasSem['sem'.($i+1)]++;
                                      } else if($frstDay || $dia > $qtdDias){
                                        ?>
                                        <td class=" text-center"></td>
                                        <?php
                                      } else {
                                        ?>
                                        <td class=" text-center"><p onclick="clickDay('<?php echo $dia ?>', '<?php echo $i+1; ?>')" id="calDay<?php echo $dia ?>"><?php echo $dia ?></p></td>
                                        <?php
                                        if($j == 0){
                                          $pri = $dia;
                                        }
                                        $dia++;
                                        $qtdDiasSem['sem'.($i+1)]++;
                                      }
                                      $dSem++;

                                      if($j == 6){
                                        $ult = $dia-1;
                                      }

                                      if(($i+1) == $qtdSem){
                                        $ult = $qtdDias;
                                      }
                                    } ?>
                                  </tr>
                                  <?php
                                  $diasSemana[$i] = array(
                                    'pri' => $pri,
                                    'ult' => $ult
                                  );
                                } ?>
                              </tbody>
                            </table>
                            <input type="hidden" name="diasCall" value="" id="calendar">
                            <span class="help-block text-center">
                              <small><b><i>Selecione os dias em que a casa <b class="text-danger">não</b> irá trabalhar</i></b></small>
                            </span>
                          </div>
                          <div class="row">
                            <div class="col-12 text-center">
                              <button type="button" id="btnAbreIntencao" class="btn btn-outline btn-info">Registrar calendário</button>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 offset-md-3 enterness-fade" id="divIntencao" style="display: none;">
                          <h4 class="text-center">Calendário de agendamentos</h4>
                          <div class="card bg-primary">
                            <h5 class="color-white text-center">Intenção de agendamentos:</h5>
                            <table>
                              <thead>
                                <tr class="titleInten">
                                  <th class="text-white">Semana</th>
                                  <th class="text-white text-center">Int.</th>
                                  <th class="text-white text-center">Ext.</th>
                                  <th class="text-white text-center">Pas.</th>
                                  <th class="text-white text-center">Intenção</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                for ($i=0; $i < $qtdSem; $i++) {
                                  ?>
                                  <tr class="lineInten" id="lineIntent<?php echo $i ?>">
                                    <td class="color-white" style="width: 150px;"><?php echo $i+1; ?>ª (<?php
                                    if($diasSemana[$i]['pri'] == $diasSemana[$i]['ult']){
                                      echo $diasSemana[$i]['ult'] ?>/<?php echo retMesAbrev($mes);
                                    } else {
                                      echo $diasSemana[$i]['pri'] ?> à <?php echo $diasSemana[$i]['ult'] ?>/<?php echo retMesAbrev($mes);
                                    }
                                    ?>)</td>
                                    <td class="color-white"><input type="text" placeholder="Int" class="form-control text-center prosp" id="valInteInt<?php echo $i+1; ?>" onchange="setCalcInte('<?php echo $i+1 ?>')" name="int<?php echo $i+1?>" value=""></td>
                                    <td class="color-white"><input type="text" placeholder="Ext" class="form-control text-center prosp" id="valInteExt<?php echo $i+1; ?>" onchange="setCalcInte('<?php echo $i+1 ?>')" name="ext<?php echo $i+1?>" value=""></td>
                                    <td class="color-white"><input type="text" placeholder="Pas" class="form-control text-center prosp" id="valIntePas<?php echo $i+1; ?>" onchange="setCalcInte('<?php echo $i+1 ?>')" name="pas<?php echo $i+1?>" value=""></td>
                                    <td class="color-white"><input type="text" readonly class="form-control text-center numRecal" id="totalInte<?php echo $i+1; ?>" name="inte<?php echo $i+1?>" value="0"> </td>
                                  </tr>
                                  <?php
                                }
                                ?>
                              </tbody>
                            </table>
                            <span class="help-block text-center">
                              <small><i>Informe a quantidade de agendamentos esperados por semana</i></small>
                            </span>
                          </div>
                          <div class="row">
                            <div class="col-12 text-center">
                              <button type="button" id="btnVoltaIntencao" class="btn btn-outline btn-danger">Voltar ao calendário</button>
                            </div>
                          </div>
                          <div class="row" style="display: none;" id="alertCapacity">
                            <div class="col-12 m-t-20">
                              <div class="alert alert-danger alert-dismissible fade show enterness-fade">
                                <h4 class="alert-heading text-danger"><strong>Atenção!</strong></h4>
                                <p class="text-danger">A quantidade de intenção de agendamentos feita por você é superior a capacidade da sua concessionária de acordo com os parâmetros que você setou nessa regra de negócio. Nas configurações atuais, sua concessionária consegue atender, no máximo, <b id="qtdCapacidade"></b> agendamentos nesse mês.</p>
                                <hr>
                                <p class="mb-0 text-danger">Recalculamos sua última intenção para atender a recomendação do sistema. Se desejar alterar esse valor, favor entrar em contato com o setor MyOmni posteriormente.</p>
                                <button type="button" class="btn btn-sm btn-danger m-t-20" id="btnIgnora" hidden="true" data-dismiss="alert" aria-label="Close">Ignorar recomendação</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-12 form-group">
                          <label class="control-label junta">Observações:</label>
                          <textarea class="form-control" id="textareaObs" name="obs" placeholder="Observações" style="resize: vertical; height: 90px;"></textarea>
                        </div>
                      </div>
                      <span class="help-block">
                        <h5 class="text-center"><i>As alterações registradas aqui só entrarão em vigor em <b><?php echo retMes($mes) ?> de <?php echo $ano ?></b>.</i></h5>
                      </span>
                    </div>
                    <!-- /# column -->
                  </div>
                  <input type="hidden" name="capacidade" id="inCapacidade" value="">
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="inicio">
                        <button type="button" class="btn btn-secondary" id="btn-return">Voltar</button>
                      </a>
                      <button type="submit" class="btn btn-info" disabled id="btn-send-form">Definir regra</button>
                      <button type="button" style="display: none" class="btn btn-info" disabled id="newButton">Definir regra</button>
                      <h4 id="loadingMessage" class="enterness-fade" style="display: none;">
                        <img src="assets/images/loading.gif" style="height: 35px;" alt="Criando regra..."><br>
                        <i class="text-muted" style="font-size: 14px;">Criando regra...</i>
                      </h4>
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

var selectedDays = "";
var totalEspInte = <?php if($regra){ echo $totalRec; } else { echo '99999999999'; }?>; //Quantidade de agendamento esperado
var totSemanas = <?php echo $qtdSem; ?>;
var fixedBlocs = "[-]";
var tempoTotal = 0;
var totalAtual = 0;




$(document).ready(function() {
  var textareaVar = false;

  $("#textareaObs").click(function(){
    textareaVar = true;
  });
  $("#textareaObs").blur(function(){
    textareaVar = false;
  });


  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      if(!textareaVar){
        event.preventDefault();
        return false;
      }
    }
  });
});

function setFixedBlock(obj, local){

  if(fixedBlocs.indexOf("[-]"+local+"[-]") >= 0){
    fixedBlocs = fixedBlocs.replace(local+"[-]", "");
    $(obj).removeClass("botao-bloqueio-fixo-setted");
  } else {
    fixedBlocs = fixedBlocs + local + "[-]";
    $(obj).addClass("botao-bloqueio-fixo-setted");
  }

  $("#fixedBlockIn").val(fixedBlocs);

}

function geraTabelaBloqueio(){
  var inicio = $("#inEntrada").val();
  var saida = $("#inSaida").val();
  var intervalo = $("#selectIntervalo").val();

  $.ajax({
    type: "POST",
    data: {
      inicio : inicio,
      saida : saida,
      intervalo : intervalo,
      casa : '<?php echo $idCasa ?>'
    },
    url: "../application/geraTabelaBloqueio",
    success: function(result){
      $("#tabelaBloqueiosFixos").html(result);
    }
  });

}

function checaHorarioSabado(obj){
  document.getElementById("inEntradaSabado").style.border = "1px solid #e3e3e3";
  document.getElementById("inSaidaSabado").style.border = "1px solid #e3e3e3";

  if(!retHoraParaSegundos(obj.value)){

    obj.value = "";
    obj.style.border = "1px solid red";
    setToastDanger('Opa...', 'O horário informado é invalido!');
    $("#hrLimiteAgendamentoSabado").prop("readonly",true);

  } else {

    var inEntradaSabado = $("#inEntradaSabado").val();
    var inSaidaSabado = $("#inSaidaSabado").val();

    if(inEntradaSabado != "" && inSaidaSabado != ""){

      if(retHoraParaSegundos(inEntradaSabado) > retHoraParaSegundos(inSaidaSabado)){
        document.getElementById("inEntradaSabado").style.border = "1px solid red";
        document.getElementById("inSaidaSabado").style.border = "1px solid red";
        $("#hrLimiteAgendamentoSabado").prop( "readonly",true);
        setToastDanger('Opa...', 'O horário de início não pode ser depois do horário final!');
      } else {
        $("#hrLimiteAgendamentoSabado").prop( "readonly",false);
      }

    } else {
      $("#hrLimiteAgendamentoSabado").prop( "readonly",true);
      $("#hrLimiteAgendamentoSabado").val("");
    }
  }
}

function checaHorarioLimite(obj, local){
  if(local == "semana"){
    var inInicio = $("#inEntrada").val();
    var inSaida = $("#inSaida").val();

    if(retHoraParaSegundos(obj.value) > retHoraParaSegundos(inSaida) || retHoraParaSegundos(obj.value) < retHoraParaSegundos(inInicio)){
      obj.style.border = "1px solid red";
      obj.value = "";
      setToastDanger('Opa...', 'O horário limite deve estar entre o horário de início e fim!');
    } else {
      obj.style.border = "1px solid #e3e3e3";
    }

  } else if(local == "sabado"){
    var inInicio = $("#inEntrada").val();
    var inSaida = $("#inSaida").val();

    if(retHoraParaSegundos(obj.value) > retHoraParaSegundos(inSaida) || retHoraParaSegundos(obj.value) < retHoraParaSegundos(inInicio)){
      obj.style.border = "1px solid red";
      obj.value = "";
      setToastDanger('Opa...', 'O horário limite deve estar entre o horário de início e fim!');
    } else {
      obj.style.border = "1px solid #e3e3e3";
    }
  }
}

function checaPreviewTableBloqueio(){
  var inInicio = $("#inEntrada").val();
  var inSaida = $("#inSaida").val();

  if(inInicio != "" && inSaida != ""){
    geraTabelaBloqueio();
  }
}

function checaHorario(obj){

  document.getElementById("inEntrada").style.border = "1px solid #e3e3e3";
  document.getElementById("inSaida").style.border = "1px solid #e3e3e3";

  if(!retHoraParaSegundos(obj.value)){

    obj.value = "";
    obj.style.border = "1px solid red";
    setToastDanger('Opa...', 'O horário informado é invalido!');
    $("#hrLimiteAgendamento").prop( "readonly",true);

  } else {

    obj.style.border = "1px solid #e3e3e3";
    var inInicio = $("#inEntrada").val();
    var inSaida = $("#inSaida").val();

    if(inInicio != "" && inSaida != ""){

      if(retHoraParaSegundos(inInicio) > retHoraParaSegundos(inSaida)){
        document.getElementById("inEntrada").style.border = "1px solid red";
        document.getElementById("inSaida").style.border = "1px solid red";
        $("#hrLimiteAgendamento").prop( "readonly",true);
        setToastDanger('Opa...', 'O horário de início não pode ser depois do horário final!');
      } else {
        $("#hrLimiteAgendamento").prop( "readonly",false);
        geraTabelaBloqueio();
      }

    } else {
      $("#hrLimiteAgendamento").prop( "readonly",true);
      $("#hrLimiteAgendamento").val("");
    }
  }
}

function retHoraParaSegundos(val){
  if(val.indexOf(":") > 0){
    val = val.split(":");
    if(val[0] > 23 || val[1] > 59){
      return false;
    } else {
      var tVal = (val[0]*60)*60;
      tVal = tVal + (val[1]*60);
      return tVal;
    }
  } else {
    return false;
  }
}

function setCalcInte(sem){
  var int = $("#valInteInt"+sem).val();
  var ext = $("#valInteExt"+sem).val();
  var pas = $("#valIntePas"+sem).val();
  var tot = Number(int) + Number(ext) + Number(pas);
  $("#totalInte"+sem).val(tot);
  if(!checkAlarmIntent()){
    setupIntentAccord(sem);
  }
}

function setupIntentAccord(sem){
  var int = $("#valInteInt"+sem).val();
  var ext = $("#valInteExt"+sem).val();
  var pas = $("#valIntePas"+sem).val();

  var totalAtual = 0;
  for (var i = 1; i <= totSemanas; i++) {
    var val = $("#totalInte"+i).val();
    totalAtual = totalAtual + Number(val);
  }

  var diff = totalAtual - $("#inCapacidade").val();

  if(pas != ""){
    $("#valIntePas"+sem).val(pas-diff);
  } else if(ext != ""){
    $("#valInteExt"+sem).val(ext-diff);
  } else {
    $("#valInteInt"+sem).val(int-diff);
  }
  var int = $("#valInteInt"+sem).val();
  var ext = $("#valInteExt"+sem).val();
  var pas = $("#valIntePas"+sem).val();
  var tot = Number(int) + Number(ext) + Number(pas);
  $("#totalInte"+sem).val(tot);
}

function checkAlarmIntent(){
  var totalAtual = 0;
  for (var i = 1; i <= totSemanas; i++) {
    var val = $("#totalInte"+i).val();
    totalAtual = totalAtual + Number(val);
  }
  if(totalAtual > $("#inCapacidade").val()){
    $("#qtdCapacidade").html($("#inCapacidade").val());
    $("#alertCapacity").show();
    $("#btn-send-form").prop('disabled', false);
    return false;
  } else {
    $("#alertCapacity").hide();
    $("#btn-send-form").prop('disabled', false);
    return true;
  }
}

var qtdSemObj = [
  <?php for ($i=1; $i <= $qtdSem; $i++) {
    ?>
    {checked:0, total:<?php if($qtdDiasSem['sem'.$i] > 7) { echo $qtdDiasSem['sem'.$i] - 7; } else { echo $qtdDiasSem['sem'.$i]; } ?>},
    <?php
  } ?>
];

function clickDay(dia, sem){
  sem--;
  var checkedSem = parseInt(qtdSemObj[sem]['checked']);

  if(selectedDays.indexOf("-"+dia+"-") >= 0){
    selectedDays = selectedDays.replace("-"+dia+"-", "");
    $("#calDay"+dia).removeClass('disabled');
    checkedSem--;
  } else {
    selectedDays = selectedDays+"-"+dia+"-";
    $("#calDay"+dia).addClass('disabled');
    checkedSem++;
  }

  qtdSemObj[sem]['checked'] = checkedSem;
  $("#calendar").val(selectedDays);

  setIntentions();
}

function setIntentions(){
  for (var i = 0; i < totSemanas; i++) {
    if(qtdSemObj[i]['checked'] == qtdSemObj[i]['total']){
      $("#lineIntent"+i).hide();
    } else {
      $("#lineIntent"+i).show();
    }
  }
}

$(document).ready(function(){

  $("#inEntradaSabado").change(function(){
    $("#escHrEnt").html($(this).val());
    checkTitleEscalaSabado();
  });
  $("#inSaidaSabado").change(function(){
    $("#escHrSai").html($(this).val());
    checkTitleEscalaSabado();
  });

  function checkTitleEscalaSabado(){
    if($("#inEntradaSabado").val() != "" && $("#inSaidaSabado").val() != ""){
      $("#TitleEscalaSabado").fadeIn("slow");
    } else {
      $("#TitleEscalaSabado").fadeOut("slow");
    }
  }

  $("#btn-send-form").click(function(){
    var inEntrada = $("#inEntrada").val();
    var inSaida = $("#inSaida").val();
    var hrLimiteAgendamento = $("#hrLimiteAgendamento").val();
    var inEntradaSabado = $("#inEntradaSabado").val();
    var inSaidaSabado = $("#inSaidaSabado").val();
    var hrLimiteAgendamentoSabado = $("#hrLimiteAgendamentoSabado").val();
    var qtdAtendimentoSab = $("#qtdAtendimentoSab").val();
    var qpps = $("#qpps").val();
    var inParcelas = $("#inParcelas").val();
    var qtdd = $("#qtdd").val();
    var qtm = $("#qtm").val();

    if(inEntrada != "" &&
    inSaida != "" &&
    hrLimiteAgendamento != "" &&
    inEntradaSabado != "" &&
    inSaidaSabado != "" &&
    hrLimiteAgendamentoSabado != "" &&
    qtdAtendimentoSab != "" &&
    qpps != "" &&
    inParcelas != "" &&
    qtdd != "" &&
    qtm != ""){
      $(this).hide();
      $("#btn-return").hide();
      //$("#newButton").show();
      $("#loadingMessage").show();
    }

  });

  $("#inEntrada").change(function (){
    $(".hrEntBH").val(this.value);
  });

  $("#inSaida").change(function (){
    $(".hrSaiBH").val(this.value);
    if(this.value.indexOf(":") > 0){
      temp = this.value.split(":");
      $(".hrAgendamentoTabela").val((temp[0]-2)+":"+temp[1]);
    }
  });

  $("#btnVoltaIntencao").click(function (){
    $("#divCalendario").addClass("enterness-fade");
    $("#divIntencao").hide();
    $("#divCalendario").show();
    $("#btn-send-form").prop("disabled", true);
  });

  $("#btnAbreIntencao").click(function (){
    $("#divCalendario").hide();
    $("#divIntencao").show();
    $("#btn-send-form").prop("disabled", false);
    calculaCapacidade();
  });

  $("#idRecallSelect").change(function(){
    if($("#idRecallSelect").val() == 1){
      $("#inRecall").val("");
      $("#RecallDayInput").show();
      $("#inRecall").prop('required', true);
    } else {
      $("#RecallDayInput").hide();
      $("#inRecall").prop('required', false);
    }
  });

  $("#btnIgnora").click(function (){
    $("#btn-send-form").prop('disabled', false);
  });

  $('.js-select').select2({
    closeOnSelect: false
  });

  function limpaInput(){
    document.getElementById('dataInLembrete').value = "";
  }

  $('.phone_with_ddd').mask('(00) 0000-0000');
  $('.real').mask('000.000.000,00', {reverse:true});
  $('.hora').mask('00:00');
  $('.prosp').mask('00000');
  $('.parc').mask('00x', {reverse:true});
  $('.numRecal').mask('00');

  $(function() {
    "use strict";
    $('.year-calendar').pignoseCalendar({
      theme: 'light' // light, dark, blue
    });

    $('input.calendar').pignoseCalendar({
      format: 'YYYY-MM-DD' // date format string. (2017-02-02)
    });
  });

  //calculo para saber quatos agendamentos podem ser feitos

  function calculaCapacidade(){
    //Prepara variáveis padrões para o cálculo
    var tseg = 0;
    var qtdTec = <?php echo count($user) ?> ;
    var qtdSem = <?php echo count($banco) ?> ;

    var a1 = retHoraParaSegundos($("#inEntrada").val());
    var a2 = retHoraParaSegundos($("#inSaida").val());
    var tjor = (a2 - a1)

    //Varialvel que conterá todos os débitos de tempo em segundos
    var debtoJor = 0;

    //Debito de horario de almoço diário por técnico
    //Considerar apenas dias de semana
    var deptoAlm = 0;

    // Calcula jornada diária
    var tjorDia = tjor;

    // Calcula jornada diária fim de semana
    a1 = retHoraParaSegundos($("#inEntradaSabado").val());
    a2 = retHoraParaSegundos($("#inSaidaSabado").val());
    var tjorFds = a2 - a1;

    var arrayTec = new Array();
    <?php
    $inx = 0;
    foreach ($user as $tec){
      ?>
      arrayTec[<?php echo $inx ?>] = "<?php echo $tec['idUser'] ?>";
      <?php
      $inx++;
    }
    ?>

    //Calcula capacidade em relação aos tecnicos de escala no sábado
    var tt = 0;
    <?php
    $aux = 1;
    $tt = 0;
    foreach ($sabados as $sab) {
      $str = explode("/", $sab);
      ?>
      var qtdTemp = $("#tecEscala-<?php echo $str[0] . "-" . $str[1]; ?>").val();
      tt = tt + qtdTemp.length;
      <?php
    } ?>

    var ttDbto = tt;

    //Calcula débitos da tabela BANCO DE HORAS
    for (var i = 0; i < qtdSem; i++) {
      var diasSem = $("#qtdDiaSem"+i).val();
      for (var j = 0; j < qtdTec; j++) {
        var entTemp = retHoraParaSegundos($("#inEntradaBancoH-"+i+"-"+arrayTec[j]).val());
        var saiTemp = retHoraParaSegundos($("#inSaidaBancoH-"+i+"-"+arrayTec[j]).val());

        if(entTemp != 0 && saiTemp != 0 && (saiTemp - entTemp) < tjorDia){
          debtoJor = debtoJor + ((tjorDia - (saiTemp - entTemp)) * diasSem);
        }
      }
    }

    //Calcula débitos da tabela escala horário
    for (var j = 0; j < qtdTec; j++) {

      //Calcula débitos de horário de almoço
      var hrAlmoEnt = retHoraParaSegundos($("#inHorarioAlmocoEnt-"+j).val());
      var hrAlmoSai = retHoraParaSegundos($("#inHorarioAlmocoSai-"+j).val());

      var deptoAlm = deptoAlm + (hrAlmoSai - hrAlmoEnt);

      //Calculas débitos de folgas
      var diasFolgas = $("#folgasH-"+j).val();

      var diasSemana = 0;
      var diasSemanaFim = 0;

      if(diasFolgas != ""){
        if(diasFolgas.indexOf(", ") > 0){
          var resp = diasFolgas.split(", ");
          resp.forEach(function(data){

            var semana = diasFimSemana(data);
            if(semana == 0 || semana == 6){
              diasSemanaFim++;
            } else {
              diasSemana++;
            }
          });

        } else {
          var semana = diasFimSemana(diasFolgas);
          if(semana == 0 || semana == 6){
            diasSemanaFim = 1;
          } else {
            diasSemana = 1;
          }
        }
      }

      debtoJor = debtoJor - (deptoAlm * diasSemana);
      debtoJor = debtoJor + (tjorDia * diasSemana);
      debtoJor = debtoJor + (tjorFds * diasSemanaFim);

      //Calcula férias
      diasSemana = 0;
      diasSemanaFim = 0;

      var diasFerias = $("#feriasH-"+j).val();
      if(diasFerias != ""){

        var resp = diasFerias.split(" - ");
        var dataIne = resp[0].split("/");
        var dataFim = resp[1].split("/");
        var ldia = <?php echo $ldia; ?>;
        var diaFimLoop = 0;
        if(dataFim[1] > <?php echo $mes ?> || dataFim[2] > <?php echo $ano ?>){
          diaFimLoop = ldia;
        } else {
          diaFimLoop = dataFim[0];
        }

        for(var i = dataIne[0]; i <= diaFimLoop; i++){
          var semana = diasFimSemana(i+"/"+dataIne[1]+"/"+dataIne[2]);
          if(semana == 0 || semana == 6){
            if(semana == 6){
              diasSemanaFim++;
            }
          } else {
            diasSemana++;
          }
        }

        debtoJor = debtoJor + (tjorDia * diasSemana);
        debtoJor = debtoJor + (tjorFds * diasSemanaFim);
      }

      //Calcula Bloc. pas.
      var diasBlocPas = $("#blocPasH-"+j).val();

      diasSemana = 0;
      diasSemanaFim = 0;

      if(diasBlocPas != ""){
        if(diasBlocPas.indexOf(", ") > 0){
          var resp = diasBlocPas.split(", ");
          resp.forEach(function(data){
            var dados = data.split("/");
            if(dados[1] == <?php echo $mes ?> && dados[2] == <?php echo $ano ?>){
              var semana = diasFimSemana(data);
              if(semana == 0 || semana == 6){
                diasSemanaFim++;
              } else {
                diasSemana++;
              }
            }
          });

        } else {
          var dados = diasBlocPas.split("/");
          if(dados[1] == <?php echo $mes ?> && dados[2] == <?php echo $ano ?>){
            var semana = diasFimSemana(diasBlocPas);
            if(semana == 0 || semana == 6){
              diasSemanaFim = 1;
            } else {
              diasSemana = 1;
            }
          }
        }
      }

      debtoJor = debtoJor + (tjorDia * diasSemana);
      debtoJor = debtoJor + (tjorFds * diasSemanaFim);

    }

    //Calcula calendário útil da regra
    var calendario = $("#calendar").val();
    var diasSemana = 0;
    var diasSemanaFim = 0;

    if(calendario != ""){
      if(calendario.indexOf("--") > 0){
        var resp = calendario.split("--");
        resp.forEach(function(data){
          var semana = diasFimSemana(data.replace("-", "")+"/"+<?php echo $mes; ?>+"/"+<?php echo $ano ?>);
          if(semana == 0 || semana == 6){
            diasSemanaFim++;
          } else {
            diasSemana++;
          }
        });

      } else {

        calendario = calendario.replace("-", "")
        var data = calendario.replace("-", "")+"/"+<?php echo $mes; ?>+"/"+<?php echo $ano ?>;
        var semana = diasFimSemana(data);
        if(semana == 0 || semana == 6){
          diasSemanaFim = 1;
        } else {
          diasSemana = 1;
        }
      }
    }

    debtoJor = debtoJor + (tjorDia * diasSemana);
    debtoJor = debtoJor + (tjorFds * diasSemanaFim);

    diasSemanaFim = <?php echo count($sabados) + count($domingos); ?>;
    diasSemana = <?php echo $diasUteis ?>;

    //Bloqueios fixos
    var entradaTemp = $("#inEntrada").val();
    entradaTemp = entradaTemp.split(":");
    var saidaTemp = $("#inSaida").val();
    saidaTemp = saidaTemp.split(":");
    var blocFixos = $("#fixedBlockIn").val();
    var cont = 0;
    var intervalo = $("#selectIntervalo").val();
    if(blocFixos != "[-]"){
      var resp = blocFixos.split("[-]");
      resp.forEach(function(dados){
        if(dados != "" && dados != null){
          var cursor = dados.split("-");
          var temp = cursor[1].split(":");

          if(temp[0] > parseInt(entradaTemp[0]) && temp[0] < parseInt(saidaTemp[0])){
            cont++;
          } else if(parseInt(temp[0]) == parseInt(entradaTemp[0])){
            cont++;
          } else if(parseInt(temp[0]) == parseInt(saidaTemp[0])){
            if(temp[1] <= parseInt(saidaTemp[1])){
              cont++;
            }
          }
        }
      });

      if(cont != -1){
        debtoJor = debtoJor + (((cont*intervalo)* 60)* diasSemana);
      }
    }


    var tjorMes = 0;

    for(var i = 1; i <= <?php echo $ldia ?>; i++){
      var semana = diasFimSemana(i+"/"+<?php echo $mes; ?>+"/"+<?php echo $ano; ?>);
      if(semana == 0 || semana == 6){
        //tjorMes = tjorMes + (tjorFds * ttDbto);
      } else {
        tjorMes = tjorMes + (tjorDia * <?php echo $qtdTec ?>);
      }
    }

    tjorMes = tjorMes + (tjorFds * ttDbto);

    tempoTotal = Math.round((((tjorMes - debtoJor)/60)/intervalo));

    $("#inCapacidade").val(tempoTotal);
  }

  function diasFimSemana(data){
    var res = data.split("/").reverse();
    var date = new Date(res[0], res[1] - 1, res[2]);
    var semana = date.getDay();
    return semana;
  }

});

</script>

<script>
var disabledDays = [0];
function idFolgaInput(input){

  var dados = input.value;
  var dados = input.value;
  var maxData = "<?php echo ($mes ."/". $ldia ."/". $ano); ?>";
  var idFolga = "#"+input.id;
  var res = idFolga.split("-");
  var idFolgaH = res[0] + "H-" + res[1];

  $(idFolga).datepicker({
    language: 'pt',
    onSelect: function(data) {
      var datas = "";
      var res = data.split(", ");
      var datasA = new Array();

      res.forEach(function(data){
        var resposta  = data.split("/");
        datasA.push(resposta[0]);
      });

      dados = datasA;
      $(idFolgaH).val(data);
      $(idFolga).val(datasA);
    },
    onRenderCell: function (date, cellType) {
      if (cellType == 'day') {
        var day = date.getDay(),
        isDisabled = disabledDays.indexOf(day) != -1;

        return {
          disabled: isDisabled
        }
      }
    },
    maxDate: new Date(maxData),
    minDate: new Date(<?php echo ($mes); ?>+"/01/"+<?php echo $ano ?>)
  });
  input.value = dados;
}
//-------------------------
function idFeriasInput(input){

  var dados = input.value;
  var maxData = "<?php echo maxData($mes, $ano, 0); ?>";
  var idFerias = "#"+input.id;
  var res = idFerias.split("-");
  var idFeriasH = res[0] + "H-" + res[1];

  $(idFerias).datepicker({
    language: 'pt',
    onSelect: function(data) {
      if(data.indexOf(" - ") > 0){
        var meses = new Array("jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez");
        var res = data.split(" - ");
        var resposta  = res[0].split("/");
        var ferias = resposta[0] +"/"+ meses[resposta[1]-1];
        resposta  = res[1].split("/");
        ferias = ferias + " à " + resposta[0] +"/"+ meses[resposta[1]-1];
        $(idFerias).val(ferias);
      } else {
        $(idFerias).val("");
      }
      $(idFeriasH).val(data);
    },
    onRenderCell: function (date, cellType) {
      if (cellType == 'day') {
        var day = date.getDay(),
        isDisabled = disabledDays.indexOf(day) != -1;

        return {
          disabled: isDisabled
        }
      }
    },
    maxDate: new Date(maxData),
    minDate: new Date(<?php echo ($mes); ?>+"/01/"+<?php echo $ano ?>)
  });
  if(input.value.indexOf(" - ") > 0){
    input.value = dados;
  }else{
    input.value = "";
  }
}

//-------------------------
function idBlocPasInput(input){

  var dados = input.value;
  var maxData = "<?php echo maxData($mes, $ano, 1); ?>";
  var idBlocPas = "#"+input.id;
  var res = idBlocPas.split("-");
  var idBlocPasH = res[0] + "H-" + res[1];

  $(idBlocPas).datepicker({
    language: 'pt',
    onSelect: function(data) {
      var datas = "";
      var res = data.split(", ");
      var datasA = new Array();

      res.forEach(function(data){
        var resposta  = data.split("/");
        datasA.push(resposta[0]);
      });

      dados = datasA;
      $(idBlocPas).val(datasA);
      $(idBlocPasH).val(data);
    },
    onRenderCell: function (date, cellType) {
      if (cellType == 'day') {
        var day = date.getDay(),
        isDisabled = disabledDays.indexOf(day) != -1;

        return {
          disabled: isDisabled
        }
      }
    },
    maxDate: new Date(maxData),
    minDate: new Date(<?php echo ($mes); ?>+"/01/"+<?php echo $ano ?>)
  });
  input.value = dados;
}
</script>
</body>

</html>
