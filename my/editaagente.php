<?php
include '../application/editaagente.php';
include '../application/relGrupo.php';
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
        <h3 class="padrao">Editar um agente</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="agentes">Agentes</a></li>
          <li class="breadcrumb-item">Editar agente</li>
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
              <h4>Editar o cadastro de <?php echo $agente['nome'] ?></h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/editaagente" method="post">
                  <input type="hidden" name="hash" value="<?php echo $agente['idUser']*19; ?>">
                  <div class="btn-trash">
                    <button type="button" onclick="resetPass()" class="btn btn-sm btn-warning btn-outline"><i class="fa fa-key" style="color: gray" aria-hidden="true"></i></button>
                    <button type="button" onclick="setTrash()" class="btn btn-sm btn-danger btn-outline"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome do agente</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="nome" value="<?php echo $agente['nome'] ?>" id="inNome" placeholder="Nome">
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Sobrenome</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="sobrenome" value="<?php echo $agente['sobrenome'] ?>" id="inSobrenome" placeholder="Sobrenome">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Email</label>
                        <div class="col-sm-12">
                          <input type="email" class="form-control" name="email" id="inEmail" value="<?php echo $agente['email'] ?>" disabled placeholder="Email">
                          <span class="help-block">
                            <small><i id="alertTextEmail">Essa informação é importante para a recuperação de senha</i></small>
                          </span>
                          <hr>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Login</label>
                        <div class="col-sm-12">
                          <input class="form-control" type="text" name="login" id="inLogin" value="<?php echo $agente['usuario'] ?>" disabled placeholder="Login">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Senha</label>
                            <div class="col-sm-12">
                              <input type="password" class="form-control" name="senha" id="inSenha" value="<?php echo $agente['senha'] ?>" disabled placeholder="Senha">
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Confirme a senha</label>
                            <div class="col-sm-12">
                              <input type="password" class="form-control" name="rsenha" id="inRsenha" value="<?php echo $agente['senha'] ?>" disabled placeholder="Repita a senha">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-6" style="border-left: 1px solid #eee;">

                      <div class="form-group">
                        <label class="col-sm-6 control-label junta">Ramal</label>
                        <div class="col-sm-6">
                          <input class="form-control" type="number" min="1000" max="9999" maxlength="4" name="ramal" value="<?php echo $agente['ramal'] ?>" placeholder="0000">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">CHAT permitido?</label>
                        <div class="col-sm-12">
                          <select class="form-control" name="chat">
                            <option value="todos" <?php if($agente['chat'] == 'todos'){ echo "selected"; } ?>>Sim, para todos</option>
                            <option value="sup" <?php if($agente['chat'] == 'sup'){ echo "selected"; } ?>>Sim, para supervisores</option>
                            <option value="nao" <?php if($agente['chat'] == 'nao'){ echo "selected"; } ?>>Não</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Adicionar às filas:</label>
                        <div class="col-sm-12">
                          <select class="js-select select-tec-regra" name="filas[]" multiple="multiple" style="width: 100%">
                            <?php foreach ($filas as $fila): ?>
                              <option value="<?php echo $fila['nomeFila'] ?>" <?php if(strpos($agente['filas'], "-".$fila['nomeFila']."-") !== false) { echo "selected"; } ?>><?php echo $fila['nomeFila'] ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-5">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Vincular aos grupos:</label>
                            <?php
                            if ($grupos) {
                              foreach ($grupos as $grupo) {
                                ?>
                                <div class="custom-control custom-checkbox">
                                  <input type="checkbox" name="grupos[]" <?php if(strpos($gps,$grupo['idGrupo']) !== false){ echo "checked"; } ?> class="custom-control-input" value="<?php echo $grupo['idGrupo'] ?>" id="gp<?php echo $grupo['idGrupo'] ?>">
                                  <label class="custom-control-label" for="gp<?php echo $grupo['idGrupo'] ?>"><?php echo $grupo['nome'] ?></label>
                                </div>
                                <?php
                              }
                            } else {
                              ?>
                              <i style="font-size: 12px; margin-left: 20px;">Nenhum grupo criado</i><?php
                            } ?>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Definir um avatar?</label>
                            <div class="input-group input-group-flat col-sm-12">
                              <span class="input-group-btn">
                                <label for="fileUpload">
                                  <span class="btn btn-secondary"><i class="ti-search"></i></span>
                                </label>
                              </span>
                              <div id="wrapper">
                                <input id="fileUpload" type="file" name="avatar" style="display: none" accept="image/*">
                                <div id="image-holder" onclick="clearImage()"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-7" style="border-left: 1px solid #eee; display: <?php if(!$util->getSectionPermission('media')){ echo 'none'; } ?>">
                          <div class="form-group m-t-10">
                            <div class="row">
                              <div class="col-12">
                                <label>WhatsApp:</label>
                                <div class="form-check form-check-inline" style="padding-left: 6px;margin-right: 6px;top: 5px;">
                                  <input class="form-check-input slideCheck" type="checkbox" <?php if (strpos($agente['midias'], "-whatsapp-") !== false){ echo "checked "; } ?>name="checkboxWhatsapp" value="1">
                                </div>
                              </div>
                              <div class="col-12">
                                <label>Enterness:</label>
                                <div class="form-check form-check-inline" style="padding-left: 6px;margin-right: 6px;top: 5px;">
                                  <input class="form-check-input slideCheck" type="checkbox" <?php if (strpos($agente['midias'], "-enterness-") !== false){ echo "checked "; } ?>name="checkboxEnterness" value="1">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-12">
                                <label class="junta">Quantidade de atendimentos simultâneos:</label>
                                <input id="qtdInput" value="<?php echo $agente['qtdAt']; ?>" required style="width: 170px;" class="form-control" type="number" min="1" max="20" maxlength="2" name="qtdAt" placeholder="Qtd.">
                                <small><i>(0 = sem limite)</i> </small>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /# column -->
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="agentes">
                        <button type="button" class="btn btn-secondary">Voltar</button>
                      </a>
                      <button type="button" class="btn btn-info" id="btn-send-form">Cadastrar</button>
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

$(function(){
  $.switcher('.slideCheck');
  $('.js-select').select2({
    closeOnSelect: false
  });
});


$("#checkboxQtd").change(function() {

  if($("#checkboxQtd").val() == 1){
    $("#qtdInput").attr("readonly", true);
    $("#qtdInput").val("");
    $("#checkboxQtd").val("0");
  } else {
    $("#qtdInput").attr("readonly", false);
    $("#checkboxQtd").val("1");
  }
});

$("#btn-send-form").on('click', function () {
  if($("#inNome").val() == ""){
    $("#inNome").removeClass('bd-success').addClass('bd-danger');
  } else {
    $("#inNome").removeClass('bd-danger').addClass('bd-success');
    if($("#inSobrenome").val() == ""){
      $("#inSobrenome").removeClass('bd-success').addClass('bd-danger');
    } else {
      $("#inSobrenome").removeClass('bd-danger').addClass('bd-success');
      document.sendFormAgente.submit();
    }
  }
});

function clearImage(){
  var image_holder = $("#image-holder");
  image_holder.hide();
  image_holder.empty();
  document.getElementById('fileUpload').value="";
}

$("#fileUpload").on('change', function () {

  if (typeof (FileReader) != "undefined") {

    var image_holder = $("#image-holder");
    image_holder.empty();

    var reader = new FileReader();
    reader.onload = function (e) {
      $("<img />", {
        "src": e.target.result,
        "class": "thumb-image"
      }).appendTo(image_holder);
    }
    image_holder.show();
    reader.readAsDataURL($(this)[0].files[0]);
  }
});

function setTrash(){
  swal({
    title: "Deseja mesmo excluir esse agente?",
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
          hash : <?php echo $agente['idUser']*217; ?>,
          id : '<?php echo $token ?>'
        },
        url: "../application/deletaAgente",
        success: function(result){
          if(result == '1'){
            window.location.href = "agentes?action=trashed&status=success";
          } else if(result == '0'){
            window.location.href = "agentes?action=trashed&status=failure";
          }
        }
      });
    }
  });
}

function resetPass(){
  swal({
    title: "Deseja resetar a senha desse agente?",
    text: "Essa ação não poderá ser desfeita!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Sim, resetar!",
    cancelButtonText: "Cancelar",
    closeOnConfirm: true,
    closeOnCancel: true
  },
  function(isConfirm){
    if(isConfirm){
      $.ajax({
        type: "POST",
        data: {
          hash : <?php echo $agente['idUser']*217; ?>,
          id : '<?php echo $token ?>'
        },
        url: "../application/resetaSenha",
        success: function(result){
          if(result == '1'){
            window.location.href = "editaagente?agentes?hash=<?php echo $token ?>&id=hidden&action=<?php echo $agente['idUser']*17 ?>&method=reseted&status=success";
          } else if(result == '0'){
            window.location.href = "editaagente?agentes?hash=<?php echo $token ?>&id=hidden&action=<?php echo $agente['idUser']*17 ?>&method=reseted&status=failure";
          }
        }
      });
    }
  });
}

<?php if(isset($_GET['method']) && $_GET['method'] == 'reseted' && isset($_GET['status']) && $_GET['status'] == 'success'){
  ?>
  swal("Feito!", "A senha foi resetada para 'mudar123'!", "success");
  <?php
} ?>

</script>

</body>

</html>
