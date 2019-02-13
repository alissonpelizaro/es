<?php
include '../application/detregra.php';
//Define nível de restrição da página
$allowUser = array('dev', 'coordenador', 'administrador', 'supervisor', 'agente');
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
        <h3 class="padrao">Regra de negócio</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Regra de negócio</li>
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
              <div class="jumbotron j-relative bg-white" style="padding-bottom: 0px; margin-bottom: 0px;">
                <?php if($casa['logo'] != ""){ ?>
                  <img src="assets/casas/<?php echo $casa['logo']; ?>" class="icon-det-wiki">
                <?php }
                if($_SESSION["tipo"] != "agente"){
                  ?>
                  <div class="btn-group btn-group-wiki">
                    <div class="btn-group dropleft">
                      <a href="editarRegra?crip=ssl&hash=<?php echo $idRegra*313; ?>&token=none">
                        <button type="button" class="btn btn-secondary btn-sm"><i class="ti-pencil-alt"></i></button>
                      </a>
                    </div>
                  </div>
                <?php } ?>
                <div class="" <?php if($casa['logo'] != ""){ ?>style="padding-left: 160px;"<?php } ?>>
                  <h1 class="display-6"><i class="fa fa-quote-left" aria-hidden="true"></i> <?php echo $casa['nome'] ?><i><sub style="font-size: 25px;">Regra de negócio</sub></i></h1>
                  <br>
                </div>
                <hr style="width: 80%;">
                <div class="jumbotron j-relative jumboReportRegra">
                  <?php if(!$regra){
                    ?>
                    <center>
                      <h3 class="text-muted"><i>Essa casa não possui nenhuma regra válida.</i></h3>
                    </center>
                    <?php
                  } else { ?>
                    <h2 class="text-center"><i><?php echo retMes($regra['mes']) ."/". $regra['ano'] ?></i></h2>
                    <div class="row">
                      <div class="col-12">
                        <?php if ($regra['dataRecall'] != "" && $regra['dataRecall'] > 0): ?>
                          <div class="alert alert-danger fade show enterness-fade">
                            <h4 class="alert-heading text-danger"><b>Atenção!</b></h4>
                            <p class="text-danger">Essa concessionária possui <i>RecallDay</i> no mês em curso.</p>
                            <hr>
                            <?php if ($regra['dataRecall'] > date('d')){ ?>
                              <p class="mb-0 text-danger">O <i>RecallDay</i> será no dia <b><?php echo $regra['dataRecall'] ?></b> desse mês (<?php echo retMes($regra['mes']) ?>).</p>
                            <?php } else if($regra['dataRecall'] < date('d')){
                              ?>
                              <p class="mb-0 text-danger">O <i>RecallDay</i> aconteceu no dia <b><?php echo $regra['dataRecall'] ?></b> desse mês (<?php echo retMes($regra['mes']) ?>).</p>
                              <?php
                            } else {
                              ?>
                              <p class="mb-0 text-danger">O <i>RecallDay</i> está acontecendo hoje, dia <b><?php echo $regra['dataRecall'] ?></b> de <?php echo retMes($regra['mes']) ?>.</p>
                              <?php
                            }?>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="card">
                      <div class="row">
                        <div class="col-md-6"  style="border-right: 1px solid #eee;">
                          <h5 class="body-head text-center text-muted">Dias úteis:</h5>
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
                              $diaInativo = 0;
                              for ($i=0; $i < $qtdSem; $i++) {
                                $pri = 1;
                                $ult;
                                $dSem = 0;
                                ?>
                                <tr>
                                  <?php for ($j=0; $j < 7; $j++) {
                                    if ($frstDay && $frstDay == $dSem) {
                                      ?>
                                      <td class=" text-center"><p class="defaultData <?php if(strpos($regra['diasCall'], "-1-") !== false){ echo "disabled"; $diaInativo++; } ?>">1</p></td>
                                      <?php

                                      $frstDay = false;
                                      $dia++;
                                    } else if($frstDay || $dia > $qtdDias){
                                      ?>
                                      <td class=" text-center"></td>
                                      <?php
                                    } else {
                                      ?>
                                      <td class="text-center"><p class="defaultData <?php if(strpos($regra['diasCall'], "-".$dia."-") !== false){ echo "disabled"; $diaInativo++; } ?>"><?php echo $dia ?></p></td>
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
                        </div>
                        <div class="col-6">
                          <div class="p-b-1">
                            <h4 class="text-muted text-center">Intenção de atendimentos por semana:</h4>
                            <table class="table table-hover" style="width: 100%;">
                              <thead>
                                <tr>
                                  <th>Período</th>
                                  <th class="text-center">Int.:</th>
                                  <th class="text-center">Exp.:</th>
                                  <th class="text-center">Pas.:</th>
                                  <th class="text-center">Total</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $tAg = 0;
                                for ($i=0; $i < $qtdSem; $i++) {
                                  $aux = $i+1;
                                  ?>
                                  <tr class="lineInten">
                                    <td class="text-muted" style="width: 150px;"><?php echo $i+1; ?>ª (<?php
                                    if($diasSemana[$i]['pri'] == $diasSemana[$i]['ult']){
                                      echo $diasSemana[$i]['ult'] ."/".retMesAbrev($mes);
                                    } else {
                                      echo $diasSemana[$i]['pri'] . " à ". $diasSemana[$i]['ult'] ."/". retMesAbrev($mes);
                                    }
                                    ?>)</td>
                                    <td class="padrao text-center"><?php $tmp = "s".$aux."int"; echo $regra[$tmp]; $tt = $regra[$tmp]; ?></td>
                                    <td class="padrao text-center"><?php $tmp = "s".$aux."ext"; echo $regra[$tmp]; $tt = $tt+$regra[$tmp]; ?></td>
                                    <td class="padrao text-center"><?php $tmp = "s".$aux."pas"; echo $regra[$tmp]; $tt = $tt+$regra[$tmp]; ?></td>
                                    <td class="padrao text-center"><?php echo $tt; $tAg = $tAg+$tt; $tt = 0; ?></td>
                                  </tr>
                                  <?php
                                }
                                ?>
                              </tbody>
                            </table>
                            <div class="row m-t-10">
                              <div class="col-4">
                                <p class="text-center"><i>Dias úteis:</i> <span class="label label-rouded label-primary"><?php echo $qtdDias-$diaInativo ?></span></p>
                              </div>
                              <div class="col-8">
                                <p class="text-center"><i>Total de agendamentos:</i> <span class="label label-rouded label-danger"><?php echo $tAg ?></span></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="row">
                        <div class="col-7" style="border-right: 1px solid #eee;">
                          <div>
                            <h4 class="text-muted text-center">Observações:</h4>
                            <table class="table table-hover">
                              <tbody>
                                <!--<tr>
                                <td>Dias de atendimento:</td>
                                <td class="text-center"><b><i><?php echo $diaSemTemp ?></b></i></td>
                              </tr>-->
                              <tr>
                                <td>Horário de atendimento:</td>
                                <td class="text-center"><b><i><?php echo $hrAt ?></b></i></td>
                              </tr>
                              <tr>
                                <td>Horário limite de agendamento:</td>
                                <td class="text-center"><b><i><?php echo $regra['hrlag'] ?></b></i></td>
                              </tr>
                              <tr>
                                <td>Horário de limite de atendimento no sábado:</td>
                                <td class="text-center"><b><i><?php echo $regra['hrats'] ?></b></i></td>
                              </tr>
                              <tr>
                                <td>Horário limite de agendamento no sábado:</td>
                                <td class="text-center"><b><i><?php echo $regra['hrlags'] ?></b></i></td>
                              </tr>
                              <tr>
                                <td>Quantidade máxima de parcelamento:</td>
                                <td class="text-center"><b><i><?php echo $regra['parc'] ?></b></i></td>
                              </tr>
                              <tr>
                                <td>Intervalo de agendamentos:</td>
                                <td class="text-center"><b><i>
                                  <?php if($regra['tint'] == '20'){
                                    echo "20 em 20 min";
                                  } else if($regra['tint'] == '30'){
                                    echo "30 em 30 min";
                                  } else {
                                    echo "60 em 60 min";
                                  } ?></b></i></td>
                                </tr>
                                <tr>
                                  <td>Possui <i>Recall Day</i>?</td>
                                  <td class="text-center"><b class="text-<?php if($recall){ echo "success"; } else { echo "warning"; } ?>"><i><?php if($recall){ echo "Sim"; } else { echo "Não"; } ?></b></i></td>
                                </tr>
                                <?php if($recall){ ?>
                                  <tr>
                                    <td>Dia de <i>Recall</i>:</td>
                                    <td class="text-center"><b><i>Dia <?php echo $regra['dataRecall'] ?></b></i></td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-5">
                          <h4 class="text-muted text-center">Informações adicionais:</h4>
                          <table class="table table-hover">
                            <tbody>
                              <tr>
                                <td>Quantidade de agendamentos por sábado</td>
                                <td class="text-center"><span class="label label-rouded label-primary"><?php echo $regra['qtas']; ?></span></td>
                              </tr>
                              <tr>
                                <td>Quantidade de diagnóstico por dia</td>
                                <td class="text-center"><span class="label label-rouded label-warning"><?php echo $regra['qtdd'] ?></span></td>
                              </tr>
                              <tr>
                                <td>Quantidade de consultores técnicos</td>
                                <td class="text-center"><span class="label label-rouded label-success"><?php echo quantConsultasTec($db, $idCasa); ?></span></td>
                              </tr>
                              <tr>
                                <td>Quantidade de consultores em férias no mês</td>
                                <td class="text-center"><span class="label label-rouded label-red"><?php echo quantTecEmFerias($db, $ano, $mes, $idCasa); ?></span></td>
                              </tr>
                              <tr>
                                <td>Quantidade de plantão por sábado</td>
                                <td class="text-center"><span class="label label-rouded label-info"><?php echo quantPlanPorSabado($db, $ano, $mes, $idCasa); ?></span></td>
                              </tr>
                              <tr>
                                <td>Quantidade de técnicos mecânicos</td>
                                <td class="text-center"><span class="label label-rouded label-default"><?php echo quantTecMec($db, $ano, $mes, $idCasa); ?></span></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="card">
                          <h4 class="text-muted text-center">Informações dos técnicos:</h4>
                          <table class="table table-hover">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th class="text-center">Ramal</th>
                                <th class="text-center">Almoço</th>
                                <th class="text-center">Folgas</th>
                                <th class="text-center">Férias</th>
                                <th class="text-center">Bloq. pas.</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $index = 1;
                              foreach($tabelaTecnicos as $dados){
                                $dados = explode("~", $dados); ?>
                                <tr>
                                  <td><b><?php echo $index; ?></b></td>
                                  <td><?php echo $dados[0] ?></td>
                                  <td class="text-center"><?php echo $dados[1]  ?></td>
                                  <td class="text-center"><?php echo $dados[2]  ?></td>
                                  <td class="text-center"><?php echo $dados[3]  ?></td>
                                  <td class="text-center"><?php echo $dados[4]  ?></td>
                                  <td class="text-center"><?php echo $dados[5]  ?></td>
                                </tr>
                                <?php
                                $index++;
                              }?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <?php if($regra['obs'] != ""){ ?>
                      <div class="row">
                        <div class="col-12">
                          <div class="card">
                            <div class="card-body">
                              <h4 class="card-title">
                                Observações do gestor da casa:
                              </h4>
                              <hr>
                              <p><?php echo nl2br($regra['obs']); ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                    <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title enterness">
                              Promoções
                              <input type="text" class="form-control input-promocao" name="fitroPromocao" id="fitroPromocao" placeholder="Buscar..."/>
                            </h4>
                            <hr>
                            <div class="row" id="promocoes"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">
                              Produtos
                            </h4>
                            <div class="table-responsive m-t-10">
                              <?php if(!$produtos){
                                ?>
                                <hr>
                                <h3 class="text-muted center"><i>Nenhum produto disponível.</i></h3>
                                <?php
                              } else { ?>
                              	<table id="produtoTable" class="table table-striped table-hover">
                                  <thead>
                                    <tr>
                                      <th style="width: 50px;">#</th>
                                      <th>Produto</th>
                                      <th>Valor</th>
                                      <th>Veiculação</th>
                                      <th>Observação</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($produtos as $prod) {
                                      ?>
                                      <tr>
                                        <td><?php echo $count; $count++; ?></td>
                                        <td><?php echo $prod['produto']; ?></td>
                                        <td><?php echo "R$ ".$prod['valor']; ?></td>
                                        <td><?php echo $prod['veiculacao']; ?></td>
                                        <td><?php echo $prod['obs']; ?></td>
                                      </tr>
                                      <?php
                                    } ?>
                                  </tbody>
                                </table>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-head">Agenda dos técnicos</h4>
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
                                <form class="enterness" name="formAttAgenda" action="detregra?crip=ssl&hash=<?php echo $_GET['hash'] ?>&token=none#calendario-icone" method="post">
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
                                            $idBlock = $arrayBlocks[$index]['idBloc'];
                                          } else {
                                            $bloqueio = setBloqueio($jornada, $agora);
                                          }
                                          ?>
                                          <div class="hr-agenda hr-agenda-<?php echo $bloqueio; ?> <?php if($_SESSION['tipo'] != 'agente' && $bloqueio != 'livre'){ echo "cursorDefault"; } ?>"
                                          <?php if($_SESSION['tipo'] != "agente"){?> onclick="setBlock('<?php echo $tec['idUser'] ?>', '<?php echo fixName($tec['nome'] ." ". $tec['sobrenome']) ?>', '<?php echo $bloqueio ?>', '<?php echo $data." " .$hora['hora'].":".$hora['min']; ?>')"><?php }?>
                                            <?php if($bloqueio != "livre" && $bloqueio != "almoco" && $bloqueio != "fora" && (strpos($bloqueio, "cont") === false || strpos($bloqueio, "fora") !== false)){ ?>
                                              <span class="rmvAgendaSpan" <?php if($_SESSION['tipo'] != 'agente'){ ?> onclick="openModalEdit('<?php echo $tec['idUser'] ?>',
                                                '<?php echo $tec['nome']." ".$tec['sobrenome'] ?>', '<?php echo $bloqueio ?>',
                                                '<?php echo $data." ".$hora['hora'] .":". $hora['min'];?>', '<?php echo $idBlock ?>')" <?php } ?>>
                                                <?php if($_SESSION['tipo'] != "agente"){?><i class="fa fa-pencil-square-o"></i><?php }?>
                                              </span>
                                            <?php } ?>
                                            <span><?php
                                            if($bloqueio == "fora" || $bloqueio == "fora-cont"){
                                              echo "Fora de horário";
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
                                            } else if($bloqueio == 'outros' || $bloqueio == 'outros-cont'){
                                              if($last != 'outros'){
                                                echo "<p><span>Outros</span></p>";
                                                //echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                                $last = 'outros';
                                              }
                                            } else if($bloqueio == 'produtivo' || $bloqueio == 'produtivo-cont'){
                                              if($last != 'produtivo'){
                                                echo "<p><span>Pré-bloqueado</span></p>";
                                                //echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                                $last = 'produtivo';
                                              }
                                            } else if($bloqueio == "almoco"){
                                              if($last != "almoco"){
                                                echo "<p><span>Almoço</span></p>";
                                                //echo "<i>".retHoraAgenda($arrayBlocks[$index]['dataini'], $arrayBlocks[$index]['datafim'])."</i>";
                                                $last = "almoco";
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
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
                <div class="row" style="display: none">
                  <div class="col-12">
                    <i>Andamento do mês</i>
                    <div class="progress">
                      <div class="progress-bar bg-info" style="width: 60%; height:6px;" role="progressbar"> <span class="sr-only">60% Complete</span> </div>
                    </div>
                  </div>
                </div>
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
<div class="background-modal-agenda" id="setBlockModalBackground" onclick="dismiss()"></div>
<div class="modal-agenda" id="setBlockModal">
  <div class="modal-agenda-header">
    <h3>Adicionar bloqueio</h3>
    <span class="spanCloseModalAgenda" onclick="dismiss('setBlockModal')">&times;</span>
  </div>
  <form class="form-horizontal enterness" action="../application/setbloqueio" method="post">
    <div class="modal-agenda-body criascroll-modal">
      <div class="row">
        <div class="col-12">
          <p>Definir bloqueio para <b id="nomeTecModal"></b></p>
        </div>
      </div>
      <input type="hidden" name="tecnico" id="idTecnicoHidden" value="">
      <input type="hidden" name="origin" value="detregra">
      <input type="hidden" name="hash" value="<?php echo $_GET['hash'] ?>">
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
            <button type="submit" id="btnSubmitFormModalNew" class="btn btn-info btn-focus">Bloquear</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- EDIT MODAL -->
<div class="modal-agenda" id="editaBlockModal">
  <div class="modal-agenda-header">
    <h3>Editar um bloqueio</h3>
    <span class="spanCloseModalAgenda" onclick="dismiss('editaBlockModal')">&times;</span>
  </div>
  <form class="form-horizontal enterness" action="../application/editaBloqueio" method="post">
    <div class="modal-agenda-body criascroll-modal">
      <div class="row">
        <div class="col-12">
          <p>Editar bloqueio de <b id="nomeTecModalEdit"></b></p>
          <i><b>Motivo: </b> <i id="txtMotivoEdit"></i></i>
        </div>
      </div>
      <input type="hidden" name="tecnico" id="idTecnicoHiddenEdit" value="">
      <input type="hidden" name="origin" value="detregra">
      <input type="hidden" name="hash" value="<?php echo $_GET['hash'] ?>">
      <input type="hidden" name="data" id="dataModalEditHidden" value="">
      <input type="hidden" name="mtvOld" id="mtvOldHidden" value="">
      <input type="hidden" name="tint" value="<?php echo $tint ?>">
      <input type="hidden" name="idBlock" id="idBlockHidden" value="">
      <div class="row">
        <div class="col-9">
          <div class="form-group">
            <label for="databloqueio" class="control-label junta">Data do bloqueio</label>
            <input type="text" class="form-control datepicker-here" id="dataBlockIniEdit" name="dataini" value="" data-position="left top" placeholder="Data do bloqueio">
          </div>
        </div>
        <div class="col-3">
          <div class="form-group">
            <label for="databloqueio" class="control-label junta">Hora</label>
            <input type="text" class="form-control hora_mask" id="horaBlockIniEdit" name="horaini" value="" placeholder="00:00">
          </div>
        </div>
      </div>
      <div class="row enterness-fade" style="display: none;" id="divFormFinalBloqueioEdit">
        <div class="col-9">
          <div class="form-group">
            <label for="databloqueio" class="control-label junta">Data final do bloqueio</label>
            <input type="text" class="form-control datepicker-here" id="dataFinalBloqueioEdit" name="datafim" value="" data-position="left top" placeholder="Data final do bloqueio">
          </div>
        </div>
        <div class="col-3">
          <div class="form-group">
            <label for="databloqueio" class="control-label junta">Hora</label>
            <input type="text" class="form-control hora_mask" id="horaFinalBloqueioEdit" name="horafim" value="" placeholder="00:00">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <p class="text-right txt-intervalo-bloqueio" id="btnIntervaloBloqueioEdit">Adicionar intervalo de bloqueio</p>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label for="databloqueio" class="control-label junta">Motivo</label>
            <select class="form-control" name="motivo" id="selectMotivoEdit">
              <option value="folga">Folga</option>
              <option value="ferias">Férias</option>
              <option value="pessoal">Motivos pessoais</option>
              <option value="outros">Outros</option>
            </select>
          </div>
        </div>
        <div class="col-12 enterness-fade" id="descMotivoDivEdit" style="display: none">
          <div class="form-group">
            <label for="databloqueio" class="control-label junta">Descrição do motivo:</label>
            <input type="text" class="form-control" id="descricaoMotivoEdit" name="descricaomotivo" value="" placeholder="Descrição do motivo">
          </div>
        </div>
      </div>
    </div>
    <div class="modal-agenda-footer">
      <hr>
      <div class="row">
        <div class="col-12">
          <div class="pull-right">
            <button type="button" class="btn btn-dark btn-outline btn-focus" onclick="dismiss('editaBlockModal')">Voltar</button>
            <button type="submit" id="btnSubmitFormModalEdit" class="btn btn-info btn-focus">Bloquear</button>
          </div>
          <div class="pull-left">
            <button type="button" class="btn btn-sm btn-danger btn-outline m-t-15" id="btnDeleteBlock"><i class="fa fa-trash" aria-hidden="true"></i></button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">
$('#promocoesTable').DataTable();
$('#produtoTable').DataTable();

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

function openModalEdit(id, nome, block, data, idBloc){
  if(block != "livre" && block != 'almoco' && block != 'cont-almoco' && block != 'fora'){
    $("#nomeTecModalEdit").html(nome);
    $("#idTecnicoHiddenEdit").val(id);
    $("#dataModalEditHidden").val(data);
    $("#mtvOldHidden").val(block);
    $("#idBlockHidden").val(idBloc);

    $("#txtMotivoEdit").html(retMotivo(block));

    $("#btnIntervaloBloqueioEdit").html('Adicionar intervalo de bloqueio');
    $("#divFormFinalBloqueioEdit").hide();

    $("#dataFinalBloqueioEdit").val("");
    $("#horaFinalBloqueioEdit").val("");

    $("#dataFinalBloqueioEdit").prop('required',false);
    $("#horaFinalBloqueioEdit").prop('required',false);

    data = data.split(" ");
    dataDef = setupDataModal(data[0]);
    horaDef = setupHoraModal(data[1]);

    $("#dataBlockIniEdit").val(dataDef);
    $("#horaBlockIniEdit").val(horaDef);


    $("#editaBlockModal").show();
    $("#setBlockModalBackground").show();
  } else if(block != "fora" && block != "almoco"){
    //alert(id+" "+block+" "+data);
  }
}

function retMotivo(val){
  if(val == 'almoco'){
    return "Almoço";
  } else if(val == 'folga'){
    return "Folga";
  } else if(val == 'ferias'){
    return "Férias";
  } else if(val == 'pessoal'){
    return "Motivo pessoal";
  } else if(val == 'outros'){
    return "Outros";
  } else if(val == "produtivo"){
    return "Bloqueio fixo produtivo";
  } else if(val == "fora" || val == "cont-fora"){
    return "Fora de horário";
  } else {
    return "Desconhecido";
  }
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

function dismiss(id = false){
  if(!id){
    $("#setBlockModalBackground").hide();
    $("#editaBlockModal").hide();
    $("#setBlockModal").hide();
  } else {
    $("#setBlockModalBackground").hide();
    $("#"+id).hide();
  }
  limpaFormModal();
}

function filtarPromocao(){
	var filtro = $("#fitroPromocao").val();
	$.ajax({
		type: "POST",
		data: {
			id: <?php echo $idCasa; ?>,
			filtro: filtro},
    url: "../application/ajaxPromocoes",
    success: function(result){
    	$("#promocoes").html(result);
    }
	});
}

$(document).ready(function(){

	filtarPromocao();
	$("#fitroPromocao").keyup(function(){
		filtarPromocao();
  });

  $("#btnDeleteBlock").click(function(){
    swal({
      title: "Deseja mesmo apagar esse bloqueio?",
      text: "Essa ação não poderá ser desfeita!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Sim, apagar!",
      cancelButtonText: "Cancelar",
      closeOnConfirm: true,
      closeOnCancel: true
    },
    function(isConfirm){
      var idTecDel = $("#idTecnicoHiddenEdit").val();
      var dataDel = $("#dataModalEditHidden").val();
      var mtvDel = $("#mtvOldHidden").val();
      var idBlock = $("#idBlockHidden").val();

      if(isConfirm){
        $.ajax({
          type: "POST",
          data: {
            idTecDel : idTecDel,
            dataDel : dataDel,
            mtvDel : mtvDel,
            idBlock : idBlock,
            tInt : '<?php echo $tint; ?>'
          },
          url: "../application/deletaBloqueio",
          success: function(result){
            if(result == 'true'){
              //alert(result);
              window.location.assign("http://<?php echo $config->server ?>/my/detregra?crip=ssl&hash=<?php echo $_GET['hash'] ?>&token=none&deleteBlock=success#calendario-icone");
              location.reload();
            } else if(result == '0'){
              window.location.assign("http://<?php echo $config->server ?>/my/detregra?crip=ssl&hash=<?php echo $_GET['hash'] ?>&token=none&deleteBlock=failure#calendario-icone");
              location.reload();
            }
            window.location.assign("http://<?php echo $config->server ?>/my/detregra?crip=ssl&hash=<?php echo $_GET['hash'] ?>&token=none&deleteBlock=success#calendario-icone");
            location.reload();
          }
        });
      }
    });
  });

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
  var openIntervalCmdEdit = false;

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

  $("#btnIntervaloBloqueioEdit").click(function (){
    var atualShow = document.getElementById("divFormFinalBloqueioEdit").style.display;
    openIntervalCmdEdit = 'manual';

    if(atualShow == "none"){
      $("#btnIntervaloBloqueioEdit").html('Remover intervalo de bloqueio');
      $("#divFormFinalBloqueioEdit").show();

      $("#dataFinalBloqueioEdit").prop('required',true);
      $("#horaFinalBloqueioEdit").prop('required',true);
    } else {
      $("#btnIntervaloBloqueioEdit").html('Adicionar intervalo de bloqueio');
      $("#divFormFinalBloqueioEdit").hide();

      $("#dataFinalBloqueioEdit").val("");
      $("#horaFinalBloqueioEdit").val("");

      $("#dataFinalBloqueioEdit").prop('required',false);
      $("#horaFinalBloqueioEdit").prop('required',false);
    }
  });

  $("#selectMotivo").change(function(){
    var selected = $("#selectMotivo").val();
    if(selected == "outros"){
      $("#descMotivoDiv").show();
      $("#descricaoMotivo").prop('required',true);
    } else if(selected == "ferias") {
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

  $("#selectMotivoEdit").change(function(){
    var selected = $("#selectMotivoEdit").val();
    if(selected == "outros"){
      $("#descMotivoDivEdit").show();
      $("#descricaoMotivoEdit").prop('required',true);
    } else if(selected == "ferias"){
      openIntervalCmdEdit = 'auto';
      var atualShow = document.getElementById("divFormFinalBloqueioEdit").style.display;
      if(atualShow == "none"){
        $("#btnIntervaloBloqueioEdit").html('Remover intervalo de bloqueio');
        $("#divFormFinalBloqueioEdit").show();

        $("#dataFinalBloqueioEdit").prop('required',true);
        $("#horaFinalBloqueioEdit").prop('required',true);
      }
      $("#descMotivoDivEdit").hide();
      $("#descricaoMotivoEdit").prop('required',false);
    } else {
      var atualShow = document.getElementById("divFormFinalBloqueioEdit").style.display;
      if(atualShow == "" && openIntervalCmdEdit && openIntervalCmdEdit == "auto"){
        $("#btnIntervaloBloqueioEdit").html('Adicionar intervalo de bloqueio');
        $("#divFormFinalBloqueioEdit").hide();

        $("#dataFinalBloqueioEdit").val("");
        $("#horaFinalBloqueioEdit").val("");

        $("#dataFinalBloqueioEdit").prop('required',false);
        $("#horaFinalBloqueioEdit").prop('required',false);
      }
      $("#descMotivoDivEdit").hide();
      $("#descricaoMotivoEdit").prop('required',false);
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

  $("#horaBlockIniEdit").change(function(){
    if(!checaHora($("#horaBlockIniEdit").val())){
      $("#horaBlockIniEdit").val("");
      document.getElementById("horaBlockIniEdit").style.border = "1px solid red";
    } else {
      document.getElementById("horaBlockIniEdit").style.border = "1px solid #eee";
    }
  });

  $("#horaFinalBloqueioEdit").change(function(){
    if(!checaHora($("#horaFinalBloqueioEdit").val())){
      $("#horaFinalBloqueioEdit").val("");
      document.getElementById("horaFinalBloqueioEdit").style.border = "1px solid red";
    } else {
      document.getElementById("horaFinalBloqueioEdit").style.border = "1px solid #eee";
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
  <?php if(isset($_GET['edit']) && $_GET['edit'] == 'success'){
    ?>
    swal("Tudo certo!", "O bloqueio foi editado com sucesso!", "success");
    <?php
  } ?>
  <?php if(isset($_GET['edit']) && $_GET['edit'] == 'failure'){
    ?>
    swal("Opa!", "Algo deu errado ao editar o bloqueio!", "error");
    <?php
  } ?>

  <?php if(isset($_GET['deleteBlock']) && $_GET['deleteBlock'] == 'success'){
    ?>
    swal("Feito!", "Bloqueio apagado com sucesso!", "success");
    <?php
  } ?>

});

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
