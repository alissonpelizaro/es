<?php
include '../application/detfila.php';
//Define nível de restrição da página
$allowUser = array('dev', 'coordenador', 'administrador');
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
        <h3 class="padrao">Editar fila</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="filas">Filas</a></li>
          <li class="breadcrumb-item">Editar fila</li>
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
              <h4>Editar fila <b><?php echo $fila['nomeFila'] ?></b></h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" name="sendFormAgente" action="../application/editaFila" method="post">
                  <input type="hidden" name="hash" value="<?php echo $_GET['id'] ?>">
                  <input type="hidden" name="setted" id="inSetted" value="<?php
                  foreach ($inside as $agt) {
                    echo "#".$agt['id']."#";
                  } ?>">
                  <input type="hidden" name="nomeAtual" value="<?php echo $fila['nomeFila'] ?>">
                  <div class="btn-trash">
                    <button type="button" onclick="setTrash()" class="btn btn-sm btn-danger btn-outline"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome da fila</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" required name="nome" value="<?php echo $fila['nomeFila'] ?>" id="inNome" placeholder="Nome">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 form-group">
                          <label class="col-sm-12 control-label junta">Transbordar para:</label>
                          <div class="col-sm-12">
                            <select class="form-control" name="transbordo" id="selectTransbordo">
                              <option value="">Sem transbordo</option>
                              <?php
                              foreach ($filas as $fl) {
                                ?>
                                <option value="<?php echo $fl['nomeFila'] ?>" <?php if($fl['nomeFila'] == $fila['transbordo']) { echo 'selected'; } ?>><?php echo $fl['nomeFila'] ?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group text-center">
                            <label class="control-label">Fila ativa nas mídias?</label>
                            <div>
                              <div class="form-check form-check-inline"  style="padding-left: 10px;">
                                <input class="form-check-input" type="checkbox" <?php if($fila['status'] == 1){ echo "checked"; } ?> name="status" id="checkboxStatus" value="1">
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group text-center">
                            <label class="control-label">Protocolo ativo?</label>
                            <div>
                              <div class="form-check form-check-inline"  style="padding-left: 10px;">
                                <input class="form-check-input" type="checkbox" <?php if($fila['statusProtocolo'] == 1){ echo "checked"; } ?> name="statusProtocolo" id="checkboxStatusProtocolo" value="1">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-6" style="border-left: 1px solid #eee;">
                      <div class="row" style="background-color: #fafafa; padding: 15px 0; margin: 0;">
                        <div class="col-6">
                          <h5>Nessa fila:</h5>
                          <ul id="ulInside">
                            <?php foreach ($inside as $agt) {
                              ?>
                              <li onclick="rmvAgent('<?php echo $agt['id'] ?>', this)"><p id="agentName<?php echo $agt['id'] ?>"><?php echo $agt['nome']; if($agt['ramal'] != ""){ echo " (".$agt['ramal'].")"; } ?></p></li>
                              <?php
                            }
                            unset($agt);
                            ?>
                          </ul>
                        </div>
                        <div class="col-6" style="border-left: 1px solid #eee;">
                          <h5>Agentes:</h5>
                          <ul id="ulOutside">
                            <?php foreach ($outside as $agt) {
                              ?>
                              <li onclick="addAgent('<?php echo $agt['id'] ?>', this)"><p id="agentName<?php echo $agt['id'] ?>"><?php echo $agt['nome']; if($agt['ramal'] != ""){ echo " (".$agt['ramal'].")"; } ?></p></li>
                              <?php
                            }
                            unset($agt);
                            ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <!-- /# column -->
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="filas">
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
<script src="assets/js/lib/switcher/jquery.switcher.js"></script>
<script type="text/javascript">

var agentInside = "<?php
foreach ($inside as $agt) {
  echo "#".$agt['id']."#";
} ?>
";

function rmvAgent(id, obj){
  var nome = $("#agentName"+id).html();
  var out = $("#ulOutside").html();
  var newest = out + '<li onclick="addAgent('+"'"+id+"'"+', this)"><p id="agentName'+id+'">'+nome+'</p></li>';
  $(obj).remove();
  $("#ulOutside").html(newest);
  agentInside = agentInside.replace(id+"#", "");
  setInSelected();
}

function addAgent(id, obj){
  var nome = $("#agentName"+id).html();
  var ins = $("#ulInside").html();
  var newest = ins + '<li onclick="rmvAgent('+"'"+id+"'"+', this)"><p id="agentName'+id+'">'+nome+'</p></li>';
  $(obj).remove();
  $("#ulInside").html(newest);
  agentInside = agentInside+id+"#";
  setInSelected();
}

function setInSelected(){
  document.getElementById("inSetted").value = agentInside;
}

function setTrash(){
  swal({
    title: "Deseja mesmo excluir essa fila?",
    text: "Essa ação não poderá ser desfeita!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Sim, excluir!",
    cancelButtonText: "Cancelar",
    closeOnConfirm: true,
    closeOnCancel: true
  },
  function(isConfirm){
    if(isConfirm){
      $.ajax({
        type: "POST",
        data: {
          hash : <?php echo $fila['idFila']*217; ?>,
          nome : '<?php echo $fila['nomeFila'] ?>',
          id : '<?php echo 10; ?>'
        },
        url: "../application/deletaFila",
        success: function(result){
          if(result == '1'){
            window.location.href = "filas?action=trashed&status=success";
          } else if(result == '0'){
            window.location.href = "filas?action=trashed&status=failure";
          }
        }
      });
    }
  });
}


$(document).ready(function() {

  $(function(){
    $.switcher('input[type=checkbox]');
  });

});

</script>

</body>

</html>
