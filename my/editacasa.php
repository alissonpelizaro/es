<?php
include '../application/editacasa.php';
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
        <h3 class="padrao">Gerenciar casa (<?php echo $casa['nome'] ?>)</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="casas">Casas</a></li>
          <li class="breadcrumb-item">Gerenciar casa</li>
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
              <h4><?php echo $casa['nome'] ?></h4>
            </div>
            <div class="card-body">
              <div class="horizontal-form-elements">
                <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormCasa" action="../application/editacasa" method="post">
                  <input type="hidden" name="hash" value="<?php echo $_GET['action']; ?>">
                  <div class="btn-trash">
                    <button type="button" onclick="resetPass()" class="btn btn-sm btn-warning btn-outline"><i class="fa fa-key" style="color: gray" aria-hidden="true"></i></button>
                    <button type="button" onclick="setTrash()" class="btn btn-sm btn-danger btn-outline"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Nome da casa</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="nome" value="<?php echo $casa['nome'] ?>" id="inNome" required placeholder="Nome da casa">
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Responsável</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="responsavel" value="<?php echo $casa['responsavel'] ?>" id="inResponsavel" required placeholder="Responsável">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Endereço</label>
                        <div class="col-sm-12">
                          <input type="text" class="form-control" name="endereco" id="inEndereco" value="<?php echo $casa['endereco'] ?>" placeholder="Endereço completo">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-5">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Bairro</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="bairro" value="<?php echo $casa['bairro'] ?>" id="inBairro" placeholder="Bairro">
                            </div>
                          </div>
                        </div>
                        <div class="col-5">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Cidade</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name="cidade" value="<?php echo $casa['cidade'] ?>" id="inCidade" placeholder="Cidade">
                            </div>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">UF</label>
                            <div class="col-sm-12">
                              <select class="form-control" name="estado">
                                <option value="">...</option>
                                <option value="PR" <?php if($casa['estado'] == 'PR'){ echo "selected"; } ?>>PR</option>
                                <option value="SP" <?php if($casa['estado'] == 'SP'){ echo "selected"; } ?>>SP</option>
                                <option value="SC" <?php if($casa['estado'] == 'SC'){ echo "selected"; } ?>>SC</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Telefone</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control phone_with_ddd" name="telefone" value="<?php echo $casa['telefone'] ?>" id="inTelefone" placeholder="(   )      -">
                            </div>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Recado</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control celphone_with_ddd" name="recado" value="<?php echo $casa['recado'] ?>" id="inRecado" placeholder="(   )      -">
                            </div>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">E-mail</label>
                            <div class="col-sm-12">
                              <input type="email" class="form-control" name="email" value="<?php echo $casa['email'] ?>" id="inEmail" placeholder="exemplo@myomni.com">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="col-sm-12 control-label junta">Login</label>
                            <div class="col-sm-12">
                              <input class="form-control" type="text" name="login" id="inLogin" value="<?php echo $gestor['usuario'] ?>" readonly required placeholder="Login">
                            </div>
                          </div>
                        </div>
                        <div class="col-2" style="padding-top: 35px;">
                          <button type="button" class="btn btn-sm btn-info btn-outline" data-toggle="modal" data-target="#modalEditaLogin">Editar login</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="col-sm-12 control-label junta">Atualizar logo da casa:</label>
                        <input type="hidden" value="" name="icon" id="inIcon">
                        <section id="icons">
                          <br>
                          <?php
                          for($i = 16; $i <= 22; $i++){ ?>
                            <div class="icon-wiki icon-wiki-opaco" onclick="setIcon('<?php echo $i; ?>')" id="icon<?php echo $i; ?>">
                              <img src="assets/casas/<?php echo $i; ?>.png">
                            </div>
                            <?php
                          } ?>
                        </section>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="center">
                      <a href="casas">
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
<div class="modal fade" id="modalEditaLogin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Editar login de acesso</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="reload()" style="margin-top: -20px; margin-right: -20px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="enterness" name="formNewLogin" action="../application/setlogin" method="post">
        <input type="hidden" name="where" value="login">
        <input type="hidden" name="casa" value="<?php echo $casa['idCasa'] ?>">
        <input type="hidden" name="id" value="<?php echo $gestor['idUser'] ?>">
        <div class="modal-body">
          <div class="form-group">
            <label class="junta">Novo login:</label>
            <input type="text" maxlength="45" name="login" class="form-control" placeholder="Novo login" id="inNewLogin">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reload()">Cancelar</button>
          <button type="button" id="btnNewLogin" class="btn btn-info">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">

$("#btnNewLogin").click(function(){
  var newLogin = $("#inNewLogin").val();
  var oldLogin = "<?php echo $gestor['usuario'] ?>";
  if(newLogin == ""){
    setToastDanger('Login inválido', 'Digite um login');
    setBorderDanger("inNewLogin");
  } else {
    if(newLogin == oldLogin){
      setBorderDanger("inNewLogin");
      document.getElementById('inNewLogin').value = "";
      setToastDanger('Opa', 'O login que você digitou já é o seu cadastrado!');
    } else {
      $.ajax({
        type: "POST",
        data: {
          param : 'login',
          value : newLogin
        },
        url: "../application/checaAjax",
        success: function(result){
          if(result == '0'){
            document.formNewLogin.submit();
          } else {
            $("#inNovoLogin").removeClass('bd-success').addClass('bd-danger');
            document.getElementById('inNewLogin').value = "";
            setToastDanger('Opa', 'O login que você digitou já está vinculado a outro usuário!');
          }
        }
      });
    }
  }
});

function setBorderDanger(id){
  document.getElementById(id).style.border = "1px solid red";
}

function unsetBorderDanger(id){
  document.getElementById(id).style.border = "1px solid #eee";
}

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
    title: "Deseja mesmo excluir essa casa?",
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
          hash : <?php echo $casa['idCasa']*217; ?>,
          id : '<?php echo 10 ?>',
          nome : '<?php echo $casa['nome'] ?>'
        },
        url: "../application/deletaCasa",
        success: function(result){
          if(result == '1'){
            window.location.href = "casas?action=trashed&status=success";
          } else if(result == '0'){
            window.location.href = "casas?action=trashed&status=failure";
          }
        }
      });
    }
  });
}

function setIcon(id){
  resetaOpacity();
  document.getElementById('inIcon').value = id;
  //document.getElementById('icon'+id).style.opacity = "1";
  $('#icon'+id).removeClass('icon-wiki-opaco');
  $('#icon'+id).addClass('icon-wiki-show');
}

function resetaOpacity(){
  for (var i = 1; i <= 22; i++) {
    //document.getElementById('icon'+i).style.opacity = "0.4";
    $('#icon'+i).removeClass('icon-wiki-show');
    $('#icon'+i).addClass('icon-wiki-opaco');
  }
}

function showIcons(){
  var atual = document.getElementById('icons').style.display;
  if(atual == 'none'){
    document.getElementById('icons').style.display = 'block';
  } else {
    document.getElementById('icons').style.display = 'none';
  }
}


function reload(){
  window.location.reload();
}

function resetPass(){
  swal({
    title: "Deseja resetar a senha do gestor dessa casa?",
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
          hash : <?php echo $gestor['idUser']*217; ?>,
          id : '<?php echo 10 ?>'
        },
        url: "../application/resetaSenha",
        success: function(result){
          if(result == '1'){
            window.location.href = "editacasa?agentes?hash=<?php echo 10 ?>&id=hidden&action=<?php echo $idCasa*17 ?>&method=reseted&status=success";
          } else if(result == '0'){
            window.location.href = "editacasa?agentes?hash=<?php echo 10 ?>&id=hidden&action=<?php echo $idCasa*17 ?>&method=reseted&status=failure";
          }
        }
      });
    }
  });
}

$('.phone_with_ddd').mask('(00) 0000-0000');
$('.celphone_with_ddd').mask('(00) 00000-0000');

<?php if(isset($_GET['method']) && $_GET['method'] == 'reseted' && isset($_GET['status']) && $_GET['status'] == 'success'){
  ?>
  swal("Feito!", "A senha foi resetada para 'mudar123'!", "success");
  <?php
} ?>

<?php if(isset($_GET['update']) && $_GET['update'] == 'success'){
  ?>
  swal("Feito!", "As informações foram atualizadas!", "success");
  <?php
} ?>

</script>

</body>

</html>
