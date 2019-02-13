<?php
include '../application/broadcastDetails.php';
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
        <h3 class="padrao">Detalhar broadcast</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="broadcasts">Broadcasts</a></li>
          <li class="breadcrumb-item">Detalhar broadcast</li>
        </ol>
      </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
      <!-- Start Page Content -->
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="jumbotron">
                <div class="container">
                  <h1 class="display-6">Detalhes da broadcast #<?php echo $broad['idBroadcast'] ?></h1>
                  <p class="lead"><?php echo nl2br($broad['broadcast']) ?></p>
                  <i class="text-muted"> Enviada por: <b><?php echo $users[$broad['idUser']]['nome']." ".$users[$broad['idUser']]['sobrenome'] ?></b> para <?php echo relGruposBroadcast($grupos, $broad['grupos']) ?> em <?php echo dataBdParaHtml($broad['data']) ?></i>
                </div>
                <br>
                <hr>
                <div class="row">
                  <div class="col-md-4">
                    <div class="card card-broadcast card-broadcast-blue">
                      <div class="card-header">
                        Enviada para:
                      </div>
                      <ul class="list-group list-group-flush">
                        <?php foreach ($enviadas as $k) {
                          if ($k != "" && isset($users[$k])) {
                            $tEnviadas++;
                          ?>
                          <li class="list-group-item">
                            <div class="avatar-ball avatar-bd-<?php echo statusAgente($users[$k]['logged'], $users[$k]['lastData']); ?> float-left m-r-15">
                              <img src="assets/avatar/<?php if($users[$k]['avatar'] == ""){ echo "default.jpg"; } else { echo $users[$k]['avatar']; } ?>">
                            </div>
                            <span class="fix-name-broadcast"><?php echo $users[$k]['nome']." ".$users[$k]['sobrenome'] ?></span>
                          </li>
                          <?php
                          }
                        } ?>
                      </ul>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card card-broadcast card-broadcast-green">
                      <div class="card-header">
                        Confirmada por:
                      </div>
                      <ul class="list-group list-group-flush">
                        <?php foreach ($confirmadas as $k) {
                            if ($k != ""  && isset($users[$k])) {
                              $tConfirmadas++;
                          ?>
                          <li class="list-group-item">
                            <div class="avatar-ball avatar-bd-<?php echo statusAgente($users[$k]['logged'], $users[$k]['lastData']); ?> float-left m-r-15">
                              <img src="assets/avatar/<?php if($users[$k]['avatar'] == ""){ echo "default.jpg"; } else { echo $users[$k]['avatar']; } ?>">
                            </div>
                            <span class="fix-name-broadcast"><?php echo $users[$k]['nome']." ".$users[$k]['sobrenome'] ?></span>
                          </li>
                          <?php
                          }
                        } ?>
                      </ul>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card card-broadcast card-broadcast-yellow">
                      <div class="card-header">
                        Ainda não confirmada por:
                      </div>
                      <ul class="list-group list-group-flush">
                        <ul class="list-group list-group-flush">
                          <?php foreach ($pendentes as $k) {
                              if ($k != ""  && isset($users[$k])) {
                                $tPendentes++;
                            ?>
                            <li class="list-group-item">
                              <div class="avatar-ball avatar-bd-<?php echo statusAgente($users[$k]['logged'], $users[$k]['lastData']); ?> float-left m-r-15">
                                <img src="assets/avatar/<?php if($users[$k]['avatar'] == ""){ echo "default.jpg"; } else { echo $users[$k]['avatar']; } ?>">
                              </div>
                              <span class="fix-name-broadcast"><?php echo $users[$k]['nome']." ".$users[$k]['sobrenome'] ?></span>
                            </li>
                            <?php
                            }
                          } ?>
                        </ul>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <div class="card p-30">
                    <div class="media">
                      <div class="media-left meida media-middle">
                        <span><i class="fa fa-paper-plane f-s-40 color-primary"></i></span>
                      </div>
                      <div class="media-body media-text-right">
                        <h2><?php echo $tEnviadas ?></h2>
                        <p class="m-b-0">Total de destinatários</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="card p-30">
                    <div class="media">
                      <div class="media-left meida media-middle">
                        <span><i class="fa fa-check f-s-40 color-success"></i></span>
                      </div>
                      <div class="media-body media-text-right">
                        <h2><?php echo round(($tConfirmadas/$tEnviadas)*100, 2) ?>%</h2>
                        <p class="m-b-0">Total de confimações</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="card p-30">
                    <div class="media">
                      <div class="media-left meida media-middle">
                        <span><i class="fa fa-question f-s-40 color-warning"></i></span>
                      </div>
                      <div class="media-body media-text-right">
                        <h2><?php echo $tPendentes ?></h2>
                        <p class="m-b-0">Agentes pendentes</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="card p-30">
                    <div class="media">
                      <div class="media-left meida media-middle">
                        <span><i class="fa fa-users f-s-40 color-purple"></i></span>
                      </div>
                      <div class="media-body media-text-right">
                        <h2><?php echo retQtdGrupo($broad['grupos']); ?></h2>
                        <p class="m-b-0">Grupos alvo</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="center">
                  <a href="broadcasts">
                    <button type="button" class="btn btn-secondary">Voltar</button>
                  </a>
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

</body>

</html>
