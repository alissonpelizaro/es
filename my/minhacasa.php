<?php
include '../application/minhacasa.php';
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
        <h3 class="padrao">Minha concessionária</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Minha concessionária</li>
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
                <?php } ?>
                <div class="" <?php if($casa['logo'] != ""){ ?>style="padding-left: 160px;"<?php } ?>>
                  <div class="btn-group btn-group-wiki" style="display: none;">
                    <div>
                      <button type="button" onclick="setTrash()" class="btn btn-sm btn-danger btn-outline">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                      </button>
                      <a href="novawiki?hash=<?php //echo $wiki['idWiki']*313 ?>&action=edit">
                        <button type="button" class="btn btn-sm btn-secondary">
                          <i class="fa fa-edit" aria-hidden="true"></i>
                        </button>
                      </a>
                    </div>
                  </div>
                  <h1 class="display-6"><i class="fa fa-quote-left" aria-hidden="true"></i> <?php echo $casa['nome'] ?><i><sub style="font-size: 25px;">Regra de negócio</sub></i></h1>
                  <br>
                </div>
                <hr style="width: 80%;">
                <div class="jumbotron j-relative jumboReportRegra">
                  <h2 class="text-center"><i><?php echo retMes($regra['mes']) ."/". $regra['ano'] ?></i></h2>
                  <form class="form enterness formRegraReport" name="dateSetted" action="minhacasa" method="post">
                    <div class="form-group">
                      <label class="control-label junta">Alterar mês:</label>
                      <br>
                      <select class="form-group form-control" name="idRegra" onchange="document.dateSetted.submit()">
                        <?php foreach ($regras as $rg) {
                          ?>
                          <option value="<?php echo $rg['idRegra']*11 ?>" <?php if(isset($_POST['idRegra']) && $idRegra == $rg['idRegra']){ echo "selected"; } ?>><?php echo retMes($rg['mes'])."/".$rg['ano'] ?></option>
                          <?php
                        } ?>
                      </select>
                    </div>
                  </form>
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
                                <td>Horário de atendimento no sábado:</td>
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
                            <p><?php echo $regra['obs']; ?></p>
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
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">
<?php if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'success'){
  ?>
  swal("Feito!", "Uma nova Wiki foi cadastrada!", "success");
  <?php
} ?>
<?php if(isset($_GET['edit']) && $_GET['edit'] == 'success'){
  ?>
  swal("Sucesso!", "A Wiki foi editada!", "success");
  <?php
} ?>
<?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
  ?>
  swal("Pronto!", "A Wiki foi excluida!", "success");
  <?php
} ?>
</script>
</body>

</html>
