<?php
include '../application/atendimento.php';
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
        <h3 class="padrao">Detalhes do atendimento</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="relatorioAtendimento">Atendimento</a></li>
          <li class="breadcrumb-item">Detalhes do atendimento</li>
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
							<div class="jumbotron j-relative">
								<div class="btn-group btn-group-wiki">
	                <a href="<?php if($_SESSION["tipo"] == 'agente'){?>meusAtendimentos<?php }else{?>relatorioAtendimento<?php }?>">
  	                <button type="button" class="btn btn-sm btn-secondary">
    	                Voltar
                    </button>
                   </a>
                  </div>
								<h1 class="text-info"><i class="fa fa-info m-r-20" aria-hidden="true"></i>Atendimento: <i><?php echo $atendimento['protocolo'];?></i></h1>
								<br>
								<div class="bg-light box-wiki padroniza-wiki">
								<?php foreach ($feed as $texto) {?>
                  <div style="color: gray;"><?php echo $texto["bodyMessage"];?></div>
                  <?php }?>
                  <br style="clear: both;">
                  <hr>
                  <p class="text-muted float-right" style="font-size: 12px;">
                  	<i>Agente: <b><?php echo $atendimento['nome'] . " " . $atendimento['sobrenome']; ?></b></i>
                  </p>
                </div>
							</div>
								<h2>Tempo</h2>
							<div class="row">
                <div class="col-md-4">
                  <div class="card p-15">
                    <div class="media">
                      <div class="media-left meida media-middle">
                        <span><i class="fa fa-clock-o f-s-40 color-primary" aria-hidden="true"></i></span>
                      </div>
                      <div class="media-body media-text-right">
                        <h2><?php echo segundosParaHora($atendimento["ta"]); ?></h2>
                        <p class="m-b-0">Tempo de atendimento</p>
                        <p style="height: 15px;"></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card p-15">
                    <div class="media">
                      <div class="media-left meida media-middle">
                        <span><i class="fa fa-user f-s-40 color-warning" aria-hidden="true"></i></span>
                      </div>
                      <div class="media-body media-text-right">
                        <h2><?php echo segundosParaHora($atendimento["tmrc"]); ?></h2>
                        <p class="m-b-0">Tempo médio de resposta</p>
                        <p>(cliente)</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card p-15">
                    <div class="media">
                      <div class="media-left meida media-middle">
                        <span><i class="fa fa-volume-control-phone f-s-40 color-danger" aria-hidden="true"></i></span>
                      </div>
                      <div class="media-body media-text-right">
                        <h2><?php echo segundosParaHora($atendimento["tmra"]); ?></h2>
                        <p class="m-b-0">Tempo médio de resposta</p>
                        <p>(agente)</p>
                      </div>
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
$(document).ready(function(){
	$(".msg-fadeTo").show();
});
</script>
</body>

</html>
