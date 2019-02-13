<?php
include '../core.php';
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
        <h3 class="padrao">Novo técnico</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="tecnicos">Técnicos</a></li>
          <li class="breadcrumb-item">Novo técnico</li>
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
              <h4>Cadastro de novo técnico</h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/cadastratecnico" method="post">
                  <div class="row">
                    <div class="col-8 offset-2">
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="nome" id="inNome" placeholder="Nome do técnico">
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Sobrenome</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="sobrenome" id="inSobrenome" placeholder="Sobrenome do técnico">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-8">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">E-mail</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="email" id="inEmail" placeholder="exemplo@myomni.com">
                              <span class="help-block">
                                <small><i id="alertTextEmail">Essa informação é importante para a recuperação de senha</i></small>
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Ramal</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control ramal-num" name="ramal" id="inRamal" placeholder="Ramal">
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
                              <input type="text" class="form-control hora_mask" value="08:00" name="entrada" id="inEntrada" placeholder="08:00">
                            </div>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Saida p/ almoço</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control hora_mask" value="12:00" name="saidaAlmoco" id="inSaidaAlmoco" placeholder="12:00">
                            </div>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Retorno do almoço</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control hora_mask" value="13:00" name="entradaAlmoco" id="inEntradaAlmoco" placeholder="13:00">
                            </div>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Saída</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control hora_mask" value="18:00" name="saida" id="inSaida" placeholder="18:00">
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
                                <option value="2" selected>Segunda-feira</option>
                                <option value="3" selected>Terça-feira</option>
                                <option value="4" selected>Quarta-feira</option>
                                <option value="5" selected>Quinta-feira</option>
                                <option value="6" selected>Sexta-feira</option>
                                <option value="7">Sábado</option>
                                <option value="1">Domingo</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Login de acesso</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="login" id="inLogin" required placeholder="Login de acesso">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Senha</label>
                            <div class="col-sm-12">
                              <input type="password" class="form-control" name="senha" id="inSenha" required placeholder="Senha">
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Repita a senha</label>
                            <div class="col-sm-12">
                              <input type="password" class="form-control phone_with_ddd" name="rSenha" id="inRSenha" placeholder="Repita a senha">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Foto do técnico:</label>
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

$(document).ready(function(){

  $("#btn-send-form").click(function(){

    unsetBorderDanger("inNome");
    unsetBorderDanger("inSobrenome");
    unsetBorderDanger("inEmail");
    unsetBorderDanger("inEntrada");
    unsetBorderDanger("inSaidaAlmoco");
    unsetBorderDanger("inEntradaAlmoco");
    unsetBorderDanger("inSaida");
    unsetBorderDanger("inLogin");
    unsetBorderDanger("inSenha");
    unsetBorderDanger("inRSenha");

    var nome = $("#inNome").val();
    var sobrenome = $("#inSobrenome").val();
    var email = $("#inEmail").val();
    var entrada = $("#inEntrada").val();
    var saidaAlmoco = $("#inSaidaAlmoco").val();
    var entradaAlmoco = $("#inEntradaAlmoco").val();
    var saida = $("#inSaida").val();
    var login = $("#inLogin").val();
    var senha = $("#inSenha").val();
    var rSenha = $("#inRSenha").val();

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
              if(result == '1'){
                limpaValor('inEmail');
                setBorderDanger("inEmail");
                setToastDanger('Opa', 'O e-mail que você digitou já está vinculado a outro usuário!');
              } else if(result != 0){
                setToastDanger('Opa', 'Houve um erro desconhecido.');
              } else {
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
                              if(login == ""){
                                setBorderDanger("inLogin");
                                setToastDanger('Opa', 'Digite um login de acesso para o técnico!');
                              } else {
                                $.ajax({
                                  type: "POST",
                                  data: {
                                    param : 'login',
                                    value : login
                                  },
                                  url: "../application/checaAjax",
                                  success: function(result){
                                    if(result == '1'){
                                      setBorderDanger("inLogin");
                                      limpaValor('inLogin');
                                      setToastDanger('Opa', 'O login que você digitou já está vinculado a outro usuário!');
                                    } else {
                                      if(senha == "" || senha.length < 6 ){
                                        setBorderDanger("inSenha");
                                        limpaValor('inSenha');
                                        setToastDanger('Opa', 'Insira uma senha de, no mínimo, 6 caracteres!');
                                      } else {
                                        if(rSenha == ""){
                                          setBorderDanger("inRSenha");
                                          setToastDanger('Opa', 'Repita a senha criada!');
                                        } else {
                                          if(senha != rSenha){
                                            limpaValor('inSenha');
                                            limpaValor('inRSenha');
                                            setBorderDanger("inSenha");
                                            setBorderDanger("inRSenha");
                                            setToastDanger('Opa', 'As senhas digitadas não conferem!');
                                          } else {
                                            document.sendFormAgente.submit();
                                          }
                                        }
                                      }
                                    }
                                  }
                                });
                              }
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
  $('.ramal-num').mask('0000000000');
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

</script>
</body>

</html>
