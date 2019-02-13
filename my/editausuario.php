<?php
  include '../application/editausuario.php';
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
            <h3 class="padrao">Editar um <?php echo $nivel ?></h3>
          </div>
          <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?php echo $nivel ?>es"><?php echo ucfirst($nivel) ?>es</a></li>
              <li class="breadcrumb-item">Editar <?php echo $nivel ?></li>
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
                   <form class="form-horizontal enterness" enctype="multipart/form-data" name="sendFormAgente" action="../application/editausuario" method="post">
                     <input type="hidden" name="hash" value="<?php echo $agente['idUser']*19; ?>">
                     <div class="btn-trash">
                       <button type="button" onclick="setTrash()" class="btn btn-sm btn-danger btn-outline"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                     </div>
                     <div class="row">
                       <div class="col-lg-6 offset-lg-3">
                         <div class="row">
                           <div class="col-6">
                             <div class="form-group">
                               <label class="col-sm-12 control-label junta">Nome do <?php echo $nivel ?></label>
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
                         <div class="form-group">
                           <label class="col-sm-12 control-label junta">Nivel de acesso:</label>
                           <div class="col-sm-12">
                             <select class="form-control" name="nivel">
                               <option value="coordenador" <?php if($nivel == 'coordenador'){ echo "selected"; } ?>>Coordenador</option>
                               <option value="administrador" <?php if($nivel == 'administrador'){ echo "selected"; } ?>>Administrador</option>
                               <option value="supervisor" <?php if($nivel == 'supervisor'){ echo "selected"; } ?>>Supervisor</option>
                             </select>
                           </div>
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
                       <!-- /# column -->
                     </div>
                     <hr>
                     <div class="row">
                       <div class="center">
                         <a href="<?php echo $nivel ?>es">
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
          title: "Deseja mesmo excluir esse <?php echo $nivel ?>?",
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
                  window.location.href = "<?php echo $nivel ?>es?action=trashed&status=success";
                } else if(result == '0'){
                  window.location.href = "<?php echo $nivel ?>es?action=trashed&status=failure";
                }
              }
            });
          }
        });
      }

    </script>

</body>

</html>
