<?php
include '../application/editatecnico.php';
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
        <h3 class="padrao">Editar técnico</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="tecnicos">Técnicos</a></li>
          <li class="breadcrumb-item">Editar técnico</li>
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
              <h4>Editar técnico (<?php echo $user['nome']." ".$user['sobrenome'] ?>)</h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/editatecnico" method="post">
                  <input type="hidden" name="hash" value="<?php echo $user['idUser']*13 ?>">
                  <div class="btn-trash">
                    <button type="button" onclick="resetPass()" class="btn btn-sm btn-warning btn-outline"><i class="fa fa-key" style="color: gray" aria-hidden="true"></i></button>
                    <button type="button" onclick="setTrash()" class="btn btn-sm btn-danger btn-outline"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                  </div>
                  <div class="row">
                    <div class="col-8 offset-2">
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="nome" id="inNome" value="<?php echo $user['nome'] ?>" placeholder="Nome do técnico">
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Sobrenome</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="sobrenome" id="inSobrenome" value="<?php echo $user['sobrenome'] ?>" placeholder="Sobrenome do técnico">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">E-mail</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="email" id="inEmail" readonly value="<?php echo $user['email'] ?>" placeholder="exemplo@myomni.com">
                              <span class="help-block">
                                <small><i id="alertTextEmail">Essa informação é importante para a recuperação de senha</i></small>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-12">
                          <p class="text-muted text-center">Jornada de trabalho</p>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-3">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Entrada</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control hora_mask" value="<?php echo $obs['entrada'] ?>" name="entrada" id="inEntrada" placeholder="08:00">
                            </div>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Saida p/ almoço</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control hora_mask" value="<?php echo $obs['saidaAlmoco'] ?>" name="saidaAlmoco" id="inSaidaAlmoco" placeholder="12:00">
                            </div>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Retorno do almoço</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control hora_mask" value="<?php echo $obs['entradaAlmoco'] ?>" name="entradaAlmoco" id="inEntradaAlmoco" placeholder="13:00">
                            </div>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Saída</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control hora_mask" value="<?php echo $obs['saida'] ?>" name="saida" id="inSaida" placeholder="18:00">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Dias da semana</label>
                            <div class="col-sm-12">
                              <select class="form-control js-select" name="dias[]" multiple="multiple">
                                <option value="2" <?php if(strpos($obs['dias'], "Segunda") !== false){ echo "selected"; } ?>>Segunda-feira</option>
                                <option value="3" <?php if(strpos($obs['dias'], "Terça") !== false){ echo "selected"; } ?>>Terça-feira</option>
                                <option value="4" <?php if(strpos($obs['dias'], "Quarta") !== false){ echo "selected"; } ?>>Quarta-feira</option>
                                <option value="5" <?php if(strpos($obs['dias'], "Quinta") !== false){ echo "selected"; } ?>>Quinta-feira</option>
                                <option value="6" <?php if(strpos($obs['dias'], "Sexta") !== false){ echo "selected"; } ?>>Sexta-feira</option>
                                <option value="7" <?php if(strpos($obs['dias'], "Sábado") !== false){ echo "selected"; } ?>>Sábado</option>
                                <option value="1" <?php if(strpos($obs['dias'], "Domingo") !== false){ echo "selected"; } ?>>Domingo</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <hr>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Atualizar a foto do técnico?</label>
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
                        <span class="help-block">
                          <small><i id="alertTextAvatar">Recomendamos inserir uma imagem que tenha o aspecto quadrangular e que não seja muito grande</i></small>
                        </span>
                      </div>
                    </div>
                    <!-- /# column -->
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="tecnicos">
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

function clearImage(){
  var image_holder = $("#image-holder");
  image_holder.hide();
  image_holder.empty();
  document.getElementById('fileUpload').value="";
}

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
          hash : <?php echo $user['idUser']*217; ?>,
          id : '<?php echo 25 ?>'
        },
        url: "../application/deletaAgente",
        success: function(result){
          if(result == '1'){
            window.location.href = "tecnicos?action=trashed&status=success";
          } else if(result == '0'){
            window.location.href = "tecnicos?action=trashed&status=failure";
          }
        }
      });
    }
  });
}

function resetPass(){
  swal({
    title: "Deseja resetar a senha desse técnico?",
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
          hash : <?php echo $user['idUser']*217; ?>,
          id : '<?php echo 15 ?>'
        },
        url: "../application/resetaSenha",
        success: function(result){
          if(result == '1'){
            window.location.href = "editatecnico?agentes?hash=<?php echo 61 ?>&id=hidden&action=<?php echo $user['idUser']*17 ?>&method=reseted&status=success";
          } else if(result == '0'){
            window.location.href = "editatecnico?agentes?hash=<?php echo 60 ?>&id=hidden&action=<?php echo $user['idUser']*17 ?>&method=reseted&status=failure";
          }
        }
      });
    }
  });
}

$(document).ready(function(){

  $("#btn-send-form").click(function(){

    unsetBorderDanger("inNome");
    unsetBorderDanger("inSobrenome");
    unsetBorderDanger("inEmail");
    unsetBorderDanger("inEntrada");
    unsetBorderDanger("inSaidaAlmoco");
    unsetBorderDanger("inEntradaAlmoco");
    unsetBorderDanger("inSaida");

    var nome = $("#inNome").val();
    var sobrenome = $("#inSobrenome").val();
    var email = $("#inEmail").val();
    var entrada = $("#inEntrada").val();
    var saidaAlmoco = $("#inSaidaAlmoco").val();
    var entradaAlmoco = $("#inEntradaAlmoco").val();
    var saida = $("#inSaida").val();

    if(nome == ""){
      setBorderDanger("inNome");
      setToastDanger('Opa', 'Digite o nome do técnico!');
    } else {
      if(sobrenome == ""){
        setBorderDanger("inSobrenome");
        setToastDanger('Opa', 'Digite o sobrenome do técnico!');
      } else {
        if(email == ""){
          setBorderDanger("inEmail");
          setToastDanger('Ops', 'Digite o e-mail do técnico!');
        } else {
          $.ajax({
            type: "POST",
            data: {
              param : 'email',
              value : email
            },
            url: "../application/checaAjax",
            success: function(result){
              if(result){
                if(checaHorario(entrada)){
                  if(checaHorario(saidaAlmoco)){
                    if(checaHorario(entrada) >= checaHorario(saidaAlmoco)){
                      limpaValor('inSaidaAlmoco');
                      setBorderDanger("inSaidaAlmoco");
                      setToastDanger('Opa', 'O horário de almoço dever vir depois do horário de entrada!');
                    } else {
                      if(checaHorario(entradaAlmoco)){
                        if(checaHorario(saidaAlmoco) >= checaHorario(entradaAlmoco)){
                          limpaValor('inEntradaAlmoco');
                          setBorderDanger("inEntradaAlmoco");
                          setToastDanger('Opa', 'O retorno do almoço dever vir depois do horário de saida do almoço!');
                        } else {
                          if(checaHorario(saida)){
                            if(checaHorario(entradaAlmoco) >= checaHorario(saida)){
                              limpaValor('inSaida');
                              setBorderDanger("inSaida");
                              setToastDanger('Opa', 'O horário de saída dever vir depois do retorno do almoço!');
                            } else {
                              document.sendFormAgente.submit();
                            }
                          } else {
                            limpaValor('inSaida');
                            setBorderDanger("inSaida");
                            setToastDanger('Opa', 'Digite um horário válido!');
                          }
                        }
                      } else {
                        limpaValor('inEntradaAlmoco');
                        setBorderDanger("inEntradaAlmoco");
                        setToastDanger('Opa', 'Digite um horário válido!');
                      }
                    }
                  } else {
                    limpaValor('inSaidaAlmoco');
                    setBorderDanger("inSaidaAlmoco");
                    setToastDanger('Opa', 'Digite um horário válido!');
                  }
                } else {
                  limpaValor('inEntrada');
                  setBorderDanger("inEntrada");
                  setToastDanger('Opa', 'Digite um horário válido!');
                }
              }
            }
          });
        }
      }
    }
  });

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
      $("#image-holder").show();
      reader.readAsDataURL($(this)[0].files[0]);
    }
  });

  $('.hora_mask').mask('00:00');
  $('.js-select').select2({
    closeOnSelect: false
  });

  function checaHorario(horario){
    if(horario == ""){
      return false;
    } else {
      horario = horario.split(":");
      if(horario.length != 2){
        return false;
      } else {
        if(horario[0] > 23 || horario[1] > 59){
          return false;
        } else {
          hora = (horario[0]*60)*60;
          minuto = horario[1]*60;
          return hora + minuto;
        }
      }
    }
  }

  function setBorderDanger(id){
    document.getElementById(id).style.border = "1px solid red";
    document.getElementById(id).focus();
  }

  function unsetBorderDanger(id){
    document.getElementById(id).style.border = "1px solid #eee";
  }

  function limpaValor(id){
    document.getElementById(id).value = "";
  }

});

<?php if(isset($_GET['method']) && $_GET['method'] == 'reseted' && isset($_GET['status']) && $_GET['status'] == 'success'){
  ?>
  swal("Feito!", "A senha foi resetada para 'mudar123'!", "success");
  <?php
} ?>

</script>
</body>

</html>
