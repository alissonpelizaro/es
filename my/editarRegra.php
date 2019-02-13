<?php
include '../application/editarRegra.php';
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
        <h3 class="padrao">Editar regra de negócio</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Editar regra de negócio</li>
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
              <h3>Editar regra de negócio para <b><?php echo retMes($mes) ?>/<?php echo $ano ?></b></h3>
              <hr style="width: 30%;">
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/updateRegra" method="post">
                  <input type="hidden" name="vigor" value="<?php echo $mes."-".$ano; ?>">
                  <input type="hidden" name="idCasa" value="<?php echo $idCasa; ?>">
                  <input type="hidden" name="idRegra" value="<?php echo $idRegra; ?>">
                  <input type="hidden" name="intervalo" value="<?php echo $tint; ?>">
                  <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                      <h4>Horário de atendimento (dia de semana)</h4>
                      <div class="row">
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Inicio</label>
                          <input class="form-control hora" name="entrada" id="inEntrada" onchange="checaHorario(this)" required placeholder="00:00" value="<?php echo $horarios[0]; ?>">
                        </div>
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Saida</label>
                          <input class="form-control hora" name="saida" id="inSaida" onchange="checaHorario(this)" required placeholder="00:00" value="<?php echo $horarios[3]; ?>">
                        </div>
                        <div class="col-sm-3 offset-4 form-group">
                          <label class="control-label junta">Horário limite para agendamento</label>
                          <input class="form-control hora" id="hrLimiteAgendamento" onchange="checaHorarioLimite(this, 'semana')" name="limAg" required placeholder="00:00" value="<?php echo $dados["hrlag"]; ?>">
                        </div>
                      </div>
                      <hr>
                      <h4>Horário de atendimento (sábado)</h4>
                      <div class="row">
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Início (sábado)</label>
                          <input class="form-control hora" id="inEntradaSabado" onchange="checaHorarioSabado(this)" name="inicioSabado" required placeholder="00:00" value="<?php echo $hrAts[0]; ?>">
                        </div>
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Saída (sábado)</label>
                          <input class="form-control hora" id="inSaidaSabado" onchange="checaHorarioSabado(this)" name="fimSabado" required placeholder="00:00" value="<?php echo $hrAts[1]; ?>">
                        </div>
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Limite de agendamento (sábado)</label>
                          <input class="form-control hora" name="limAgSab" id="hrLimiteAgendamentoSabado" onchange="checaHorarioLimite(this, 'sabado')" required placeholder="00:00" value="<?php echo $dados["hrlags"]; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Quantidade de atendimento</label>
                          <input class="form-control numRecal" name="qtdAtendimentoSab" id="qtdAtendimentoSab" required placeholder="0" value="<?php echo $dados["qtas"]; ?>">
                        </div>
                        <div class="col-sm-3 form-group">
                          <label class="control-label junta">Quantidade de plantão</label>
                          <input id="qpps" class="form-control numRecal" name="qpps" required placeholder="0" value="<?php echo $dados["qpps"]; ?>">
                        </div>
                      </div>
                      <hr style="width: 30%;">
                      <h4>Escala dos sábados <i>(8h00 às 12h00)</i></h4>
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
                                    <select class="js-select select-tec-regra" name="escala-<?php echo $str[0] . "-" . $str[1]; ?>[]" multiple="multiple" style="width: 100%;">
                                      <?php foreach ($user as $tec) {
                                        ?>
                                        <option value="<?php echo $tec['idUser'] ?>" <?php if(isset($arrayEscSab[$str[0]]) && strpos($arrayEscSab[$str[0]], "-".$tec['idUser']."-") === false){ echo "selected"; } ?>><?php echo $tec['nome']. " ". $tec['sobrenome']; ?></option>
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
                          <input class="form-control parc" name="quantidadeParcelas" id="inParcelas" required placeholder="0" value="<?php echo $dados["parc"]; ?>">
                        </div>
                        <!--<div class="col-sm-3 form-group">
                        <label class="control-label junta">Intervalo entre agendamentos</label>
                        <select class="form-control" id="selectIntervalo" onchange="checaPreviewTableBloqueio()" name="intervalo">
                        <option value="20">20 em 20 min</option>
                        <option value="30">30 em 30 min</option>
                        <option value="60">60 em 60 min</option>
                      </select>
                    </div>-->
                    <div class="col-sm-3 form-group">
                      <label class="control-label junta">Quantidade de diagnóstico por dia</label>
                      <input class="form-control numRecal" name="qtdd" id="qtdd" required placeholder="0" value="<?php echo $dados["qtdd"]; ?>">
                    </div>
                    <div class="col-sm-3 form-group">
                      <label class="control-label junta">Quantidade de técnicos mecânicos</label>
                      <input id="qtm" class="form-control numRecal" name="qtm" required placeholder="0" value="<?php echo $dados["qtm"]; ?>">
                    </div>
                  </div>
                  <hr>
                  <h4>Recall Day</h4>
                  <div class="row">
                    <div class="col-sm-4 form-group">
                      <label class="control-label junta">Possui RecallDay?</label>
                      <select class="form-control" id="idRecallSelect" name="recallday">
                        <option value="1">Sim</option>
                        <option<?php if($dados["dataRecall"] == 0 && $dados["dataRecall"] == ""){ echo " selected"; } ?> value="0">Não</option>
                      </select>
                    </div>
                    <div class="col-sm-2 form-group enterness-fade" id="RecallDayInput">
                      <label class="control-label junta">Dia do Recall:</label>
                      <input class="form-control numRecal" min="1" max="31" name="recall" id="inRecall" placeholder="1" value="<?php echo $dados["dataRecall"]; ?>">
                    </div>
                  </div>
                  <hr>
                  <h3 class="text-center m-b-20">Técnicos</h3>
                  <div id="tabelaBloqueiosFixos" style="overflow-x: auto; ">
                    <!-- Colocar a tabela aqui -->
                    <h4 class="text-muted">Pré-adicionar bloqueios fixos nesse mês</h4>
                    <table class="table enterness-fade">
                      <thead>
                        <tr>
                          <th style='border-top: 0px solid red;'>#</th>
                          <th style='border-top: 0px solid red;'>Nome</th>
                          <?php
                          $loop = true;
                          $tLoop = 0;
                          $getHorarios = array();
                          do {
                            ?>
                            <th style='border-top: 0px solid red; font-size: 12px;' class="text-center"><?php echo setNumLoop($hrIni)."h".setNumLoop($mnIni) ?></th>
                            <?php
                            $tLoop++;
                            $getHorarios[$tLoop] = $hrIni.":".$mnIni;
                            $mnIni = $mnIni + $int;
                            if($mnIni >= 60){
                              $mnIni = 0;
                              $hrIni++;
                            }
                            if($int != 60){
                              if($hrIni == $hrFim && $mnIni != 0){
                                $loop = false;
                              }
                            } else {
                              if($hrIni > $hrFim){
                                $loop = false;
                              }
                            }
                          } while ($loop);
                          ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $index = 1;
                        foreach ($tecnicos as $tec) {
                          ?>
                          <tr>
                            <td><?php echo $index; $index++; ?></td>
                            <td><?php echo $tec['nome']." ".$tec['sobrenome'] ?></td>
                            <?php
                            for ($i=0; $i < $tLoop; $i++) {
                              ?>
                              <td class="text-center" style="border-left: 1px solid #eee;">
                                <div class="botao-bloqueio-fixo<?php if(isset($horarioBlocFix[$tec[0]])){ if(marcaBlocFix($horarioBlocFix[$tec[0]], $getHorarios[$i+1])){ ?> botao-bloqueio-fixo-setted<?php }} ?>" id="btnBlockFixed<?php echo $tec['idUser']. "-" . $getHorarios[$i+1] ?>" onclick="setFixedBlock(this, '<?php echo $tec['idUser']. "-" . $getHorarios[$i+1] ?>')">
                                </div>
                              </td>
                              <?php
                            }
                            ?>
                          </tr>
                          <?php
                        } ?>
                      </tbody>
                    </table>
                    <hr style="width: 30%;">
                  </div>
                  <input type="hidden" name="escDados" value="">

                  <input type="hidden" name="fixedBlocks" id="fixedBlockIn" value="<?php echo dadosFixedBloc($horarioBlocFix, $user); ?>">
                  <h4 class="m-t-20 text-muted">Escala de entrada e saída (banco de horas)</h4>
                  <div class="accordion" id="accordionExample">
                    <div class="">
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
                                  <th>Max. Agend.</th>
                                  <th>Observação</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                foreach ($user as $tec) {
                                  $dasdosBd = retDadosBd($db, $tec['idUser'], $semana, $mes, $ano);
                                  ?>
                                  <tr>
                                    <td>
                                      <?php
                                      echo $tec['nome']. " ". $tec['sobrenome'];
                                      ?>
                                    </td>
                                    <td width="127px">
                                      <input type="text" class="form-control hrAgendamentoTabela hora imput-linha" id="inAgendamentoBancoH-<?php echo $i."-".$tec['idUser']?>" onchange="" name="agendamentoBancoH-<?php echo $i."-".$tec['idUser']?>" value="<?php echo $dasdosBd['ag'] ?>" placeholder="00:00" style="width: 50px;">
                                    </td>
                                    <td>
                                      <input type="text" class="form-control imput-linha" id="inObsBancoH-<?php echo $i."-".$tec['idUser']?>" name="obsBancoH-<?php echo $i."-".$tec['idUser']?>"  value="<?php echo $dasdosBd['obs'] ?>" maxlength="250">
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
                  <h4 class="m-t-20 text-muted">Escala horário almoço</h4>
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
                              <input type="text" class="form-control hora imput-linha" id="inHorarioAlmocoEnt-<?php echo $j ?>" onchange="" name="inHorarioAlmocoEnt-<?php echo $tec['idUser'] ?>" required placeholder="00:00" style="width: 50px;" value="<?php
                              if($horaAlmocoUser[$tec[0]][0] != ""){
                                echo $horaAlmocoUser[$tec[0]][0];
                              } else {
                                echo retTecHoraAlmoco($db, $tec['idUser'], 'entrada');
                              } ?>">
                            </div>
                          </th>
                          <th style="width: 50px;">
                            <div>
                              <input type="text" class="form-control hora imput-linha" id="inHorarioAlmocoSai-<?php echo $j ?>" onchange="" name="inHorarioAlmocoSai-<?php echo $tec['idUser'] ?>" required placeholder="00:00" style="width: 50px;" value="<?php
                              if($horaAlmocoUser[$tec[0]][1] != ""){
                                echo $horaAlmocoUser[$tec[0]][1];
                              } else {
                                echo retTecHoraAlmoco($db, $tec['idUser'], 'saida');
                              } ?>">
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
                            onclick="idFolgaInput(this);"
                            value="<?php if(isset($folgaUser[$tec[0]])){ echo $folgaUser[$tec[0]]; } ?>"
                            />
                            <input hidden="true"
                            id="folgasH-<?php echo $j ?>"
                            name="folgas-<?php echo $tec['idUser'] ?>"
                            value="<?php if(isset($folgaUserH[$tec[0]])){ echo $folgaUserH[$tec[0]]; } ?>"
                            />
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
                            onclick="idFeriasInput(this);"
                            value="<?php if(isset($feriasUser[$tec[0]])){ echo $feriasUser[$tec[0]]; } ?>"/>
                            <input hidden="true"
                            id="feriasH-<?php echo $j ?>"
                            name="ferias-<?php echo $tec['idUser'] ?>"
                            value="<?php if(isset($feriasUserH[$tec[0]])){ echo $feriasUserH[$tec[0]]; } ?>"
                            />
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
                            onclick="idBlocPasInput(this);"
                            value="<?php if(isset($bolcPasUser[$tec[0]])){ echo $bolcPasUser[$tec[0]]; } ?>"/>
                            <input hidden="true"
                            id="blocPasH-<?php echo $j ?>"
                            name="blocPas-<?php echo $tec['idUser'] ?>"
                            value="<?php if(isset($bolcPasUserH[$tec[0]])){ echo $bolcPasUserH[$tec[0]]; } ?>"
                            />
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
                    <div class="col-md-6" id="divCalendario">
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
                            for ($i=0; $i < $qtdSem; $i++) {
                              $pri = 1;
                              $ult;
                              $dSem = 0;
                              ?>
                              <tr>
                                <?php for ($j=0; $j < 7; $j++) {
                                  if ($frstDay && $frstDay == $dSem) {
                                    ?>
                                    <td class=" text-center">
                                      <p onclick="clickDay('1')"
                                      id="calDay1"
                                      <?php
                                      foreach($diasSem as $dias){
                                        if($dias == 1) {
                                          ?>class="disabled"<?php
                                        }
                                      }
                                      ?>
                                      >1</p>
                                    </td>
                                    <?php

                                    $frstDay = false;
                                    $dia++;
                                  } else if($frstDay || $dia > $qtdDias){
                                    ?>
                                    <td class=" text-center"></td>
                                    <?php
                                  } else {
                                    ?>
                                    <td class=" text-center">
                                      <p onclick="clickDay('<?php echo $dia ?>')"
                                        id="calDay<?php echo $dia ?>"
                                        <?php
                                        foreach($diasSem as $dias){
                                          if($dias == $dia) {
                                            ?>class="disabled"<?php
                                          }
                                        }
                                        ?>
                                        ><?php echo $dia ?></p>
                                      </td>
                                      <?php
                                      if($j == 0){
                                        $pri = $dia;
                                      }
                                      $dia++;
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
                          <input type="hidden" name="diasCall" value="<?php echo $diasJs; ?>" id="calendar">
                          <span class="help-block text-center">
                            <small><b><i>Selecione os dias em que a casa <b class="text-danger">não</b> irá trabalhar</i></b></small>
                          </span>
                        </div>
                      </div>
                      <div class="col-md-6 enterness-fade" id="divIntencao">
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
                                <tr class="lineInten">
                                  <td class="color-white" style="width: 150px;"><?php echo $i+1; ?>ª (<?php
                                  if($diasSemana[$i]['pri'] == $diasSemana[$i]['ult']){
                                    echo $diasSemana[$i]['ult'] ?>/<?php echo retMesAbrev($mes);
                                  } else {
                                    echo $diasSemana[$i]['pri'] ?> à <?php echo $diasSemana[$i]['ult'] ?>/<?php echo retMesAbrev($mes);
                                  }
                                  ?>)</td>
                                  <td class="color-white"><input type="text" placeholder="Int" class="form-control text-center prosp" id="valInteInt<?php echo $i+1; ?>" onchange="setCalcInte('<?php echo $i+1; ?>')" name="int<?php echo $i+1?>" value="<?php echo $dados['s'.($i+1).'int']; ?>"></td>
                                  <td class="color-white"><input type="text" placeholder="Ext" class="form-control text-center prosp" id="valInteExt<?php echo $i+1; ?>" onchange="setCalcInte('<?php echo $i+1; ?>')" name="ext<?php echo $i+1?>" value="<?php echo $dados['s'.($i+1).'ext']; ?>"></td>
                                  <td class="color-white"><input type="text" placeholder="Pas" class="form-control text-center prosp" id="valIntePas<?php echo $i+1; ?>" onchange="setCalcInte('<?php echo $i+1; ?>')" name="pas<?php echo $i+1?>" value="<?php echo $dados['s'.($i+1).'pas']; ?>"></td>
                                  <td class="color-white"><input type="text" readonly class="form-control text-center numRecal" id="totalInte<?php echo $i+1; ?>" name="inte<?php echo $i+1?>" value="<?php echo ($dados['s'.($i+1).'int'] + $dados['s'.($i+1).'ext'] + $dados['s'.($i+1).'pas']); ?>"> </td>
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
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-12 form-group">
                        <label class="control-label junta">Observações:</label>
                        <textarea class="form-control" name="obs" placeholder="Observações" style="resize: vertical; height: 90px;"><?php echo $dados["obs"]; ?></textarea>
                      </div>
                    </div>
                    <span class="help-block">
                      <h5 class="text-center"><i>As alterações registradas aqui só entrarão em vigor em <b><?php echo retMes($mes) ?> de <?php echo $ano ?></b>.</i></h5>
                    </span>
                  </div>
                  <!-- /# column -->
                </div>
                <hr>
                <div class="row">
                  <div class="center">
                    <a href="inicio">
                      <button type="button" class="btn btn-secondary" id="btn-return">Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-info" id="btn-send-form">Alterar regra</button>
                    <button type="button" style="display: none" class="btn btn-info" disabled id="newButton">Alterar regra</button>
                    <h4 id="loadingMessage" class="enterness-fade" style="display: none;">
                      <img src="assets/images/loading.gif" style="height: 35px;" alt="Carregando..."><br>
                      <i class="text-muted" style="font-size: 14px;">Atualizando...</i>
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

var selectedDays = "<?php echo $diasJs; ?>";
var totalEspInte = <?php if($regra){ echo $totalRec; } else { echo '99999999999'; }?>; //Quantidade de agendamento esperado
var totSemanas = <?php echo $qtdSem; ?>;
var fixedBlocs = "<?php echo dadosFixedBloc($horarioBlocFix, $user); ?>";

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
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

function checaHorarioSabado(obj){
  document.getElementById("inEntradaSabado").style.border = "1px solid #e3e3e3";
  document.getElementById("inSaidaSabado").style.border = "1px solid #e3e3e3";

  if(!retHoraParaSegundos(obj.value)){

    obj.value = "";
    obj.style.border = "1px solid red";
    setToastDanger('Opa...', 'O horário informado é invalido!');
    $("#hrLimiteAgendamentoSabado").prop( "readonly",true);

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

function clickDay(dia){

  if(selectedDays.indexOf("-"+dia+"-") >= 0){

    selectedDays = selectedDays.replace("-"+dia+"-", "");
    $("#calDay"+dia).removeClass('disabled');
  } else {
    selectedDays = selectedDays+"-"+dia+"-";
    $("#calDay"+dia).addClass('disabled');
  }

  $("#calendar").val(selectedDays);
}

$(document).ready(function(){

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

  if(<?php if($dados["dataRecall"]==""){echo 0;}else{echo $dados["dataRecall"];} ?> == 0){
    $("#RecallDayInput").hide();
    $("#inRecall").prop('required', false);
    $("#inRecall").val("");
  } else {
    $("#RecallDayInput").show();
    $("#inRecall").prop('required', true);
  }

  $("#idRecallSelect").change(function(){
    if($("#idRecallSelect").val() == 1){
      $("#RecallDayInput").show();
      $("#inRecall").prop('required', true);
    } else {
      $("#RecallDayInput").hide();
      $("#inRecall").val("");
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
});

</script>
<script type="text/javascript">
var disabledDays = [0];
function idFolgaInput(input){

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
        if(data.indexOf(" à ") > 0){
          $(idFerias).val(dados);
        } else {
          $(idFerias).val("");
        }
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
  if(input.value.indexOf(" - ") > 0 || dados.indexOf(" à ") > 0){
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
      var dados = "";
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
