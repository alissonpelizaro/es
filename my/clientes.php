<?php
include '../application/clientes.php';
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
        <h3 class="padrao" style="float:left; margin-right: 10px;">Clientes</h3>
        <a href="javascript:void(0)" onclick="favorito();">
					<img
					src="<?php if($favorito){?>assets/icons/star1.png
					<?php } else {?>assets/icons/star0.png
					<?php }?>"
					id="favorito">
				</a>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Clientes</li>
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
              <h4 class="card-title">
                Relatório de atendimento
                <span>
                  <a href="novoCliente" class="btn btn-sm btn-info btn-new">
                    Novo cliente
                  </a>
                </span>
              </h4>
              <div class="table-responsive m-t-10">
                <?php if(!$clientes){?>
                  <hr>
                  <h3 class="text-muted center"><i>Nenhum cliente cadastrado.</i></h3>
                <?php } else { ?>
                  <table id="tabelaAgt" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th style="width: 35px;"></th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Cidade</th>
                        <th>Status</th>
                        <th style="width: 80px;">Info.</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($clientes as $cliente) {?>
                        <tr>
                          <td><i<?php if ($util->isCadastroClienteCompleto($cliente)){?> style="color: green;"<?php }?> class="fa fa-check" aria-hidden="true"></i></td>
                            <td><?php echo $cliente["nome"]; ?></td>
                            <?php  if ($cliente['fone'] != "") {
                      				$fones = json_decode($cliente["fone"]);
	                            foreach ($fones as $fone) {
																if($fone[0]){?>
	                            		<td><?php echo $fone[1]; ?></td>
	                            	<?php }
                            	}
                            }else{?>
                            	<td></td>
                            <?php }?>
                            <td><?php echo $cliente["email"]; ?></td>
                            <td class="text-center"><?php echo $cliente["cidade"]; ?></td>
                            <td class="text-center" style="width: 100px;"><?php echo $cliente["status"]; ?></td>
                            <td class="text-center">
                              <div class="btn-group">
                                <?php if ($_SESSION['tipo'] == 'agente') { ?>
                                  <div class="btn-group dropleft" role="group">
                                    <button type="button" class="btn-sm btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span class="sr-only">Toggle Dropleft</span>
                                    </button>
                                    <div class="dropdown-menu">
                                      <a href="../application/startCall?hash=<?php echo $cliente['idCliente'] * 31; ?>&plataforma=whatsapp">
                                        <button class="dropdown-item" type="button">WhatsApp</button>
                                      </a>
                                    </div>
                                  </div>
                                <?php } ?>
                                <a href="editaCliente?hash=<?php echo ($cliente["idCliente"]*951); ?>" class="btn btn-secondary btn-sm btn-info btn-outline" style="width: 30px;">
                                  <i style="font-size: 18px;" class="fa fa-info" aria-hidden="true"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php }?>
                      </tbody>
                    </table>
                  <?php }?>
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
$(document).ready(function() {
  $('#tabelaAgt').DataTable({});
} );

<?php if(isset($_GET['delete']) && $_GET['delete'] == 'success'){?>
  swal("Feito!", "O cliente foi deletado!", "success");
<?php }?>
<?php if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'success'){?>
	swal("Feito!", "O cliente foi cadastrado!", "success");
<?php }?>

function favorito() {

	var favorito = document.getElementById("favorito").src;
	var favoritar = 1;

	if(favorito == "http://<?php echo $config->server; ?>/my/assets/icons/star1.png"){
		favoritar = 0;
	}

	$.ajax({
		type: "POST",
		data: {
			page : "clientes",
			favorito: favoritar
		},
		url: "../application/ajaxFavorito",
		success: function(result){
			if(result == "true"){
				document.getElementById("favorito").src = "assets/icons/star1.png";
			} else {
				document.getElementById("favorito").src = "assets/icons/star0.png";
			}
		}
	});

	//document.getElementById('rating').innerHTML = avaliacao;
}
</script>

</body>

</html>
