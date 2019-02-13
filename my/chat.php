<?php
include '../application/chat.php';
//Define nível de restrição da página
$allowUser = array('dev', 'coordenador', 'administrador', 'supervisor', 'agente', 'gestor');
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
        <h3 class="padrao">MyOmni<i>Chat</i></h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">MyOmni<i>Chat</i></li>
        </ol>
      </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
      <!-- Start Page Content -->
      <div class="row">
        <div class="col-md-7">
          <div class="card">
            <div class="card-body">
              <?php if(!$setted){
                ?>
                <div class="background-chat">
                </div>
                <?php
              } else { ?>
                <div class="box-chat">
                  <div class="box-chat-header">
                    <div id="circleStatusChat" class="avatar-chat border-color-<?php echo statusAgente($dst['logged'], $dst['ultimoRegistro']) ?>">
                      <img src="assets/avatar/<?php if($dst['avatar'] == ''){ echo 'default.jpg'; } else { echo $dst['avatar']; } ?>" alt="<?php echo $dst['nome']." ".$dst['sobrenome'] ?>">
                    </div>
                    <div class="name-chat">
                      <h3><?php echo $dst['nome']." ".$dst['sobrenome'] ?></h3>
                      <i class="text-muted" id="textStatusChat"><?php echo statusAgente($dst['logged'], $dst['ultimoRegistro']) ?></i>
                      <div class="dropdown btn-trash" style="display: none;">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                          <span class="fa fa-ellipsis-v" aria-hidden="true"></span>
                          <span class="caret"></span></button>
                          <ul class="dropdown-menu">
                            <li><a href="#">Apagar conversa</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="box-chat-body" id="chatMensagens" onclick="desativaAncora()">
                    </div>
                    <div class="box-chat-footer">
                      <form class="enterness">
                        <div class="row">
                          <div class="col-10">
                            <textarea name="msg" autofocus id="textareaChat" placeholder="Digite..." onkeydown="areaEnvia(this, event);"></textarea>
                          </div>
                          <div class="col-2 btn-send-chat">
                            <button type="button"class="btn btn-info btn-block" onclick="sendMessage()">
                              <i class="fa fa-share" aria-hidden="true"></i>
                            </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-8">
                    <h4 class="card-title">Meus contatos</h4>
                  </div>
                  <div class="col-lg-4">
                    <span class="enterness">
                      <input type="text" class="form-control inputSearchContacts" id="searchContacts" value="" placeholder="Buscar...">
                    </span>
                  </div>
                </div>
                <div class="recent-comment box-contatos" id="listaContatos">
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

  var ancora = true;
  <?php if($setted){ ?>
    $("#chatMensagens").scrollTop($("#chatMensagens")[0].scrollHeight);
	<?php } ?>

    $(document).ready(function(){
      <?php if($setted){ ?>
        intervalAttMensagens();
        <?php } ?>
        attContatos();
      });

      //Mantem aberta o collapsible da sessão selecionada
      var sectionsOpened = "<?php if($setted) { echo $dst['setor']; }?>";

      //Controla a atualização da lista de contatos durante a busca
      var searching = false;
      var valSearch = "";

      $("#searchContacts").keyup(function(){
        valSearch = $(this).val();
        attContatos(true);
      });
      $("#searchContacts").click(function(){
        valSearch = $(this).val();
        attContatos(true);
      });
      $("#searchContacts").change(function(){
        valSearch = $(this).val();
        attContatos(true);
      });


      function setSectionOpen(id){

        if(sectionsOpened == id){
          sectionsOpened = "";
        } else {
          sectionsOpened = id;
        }

      }

      function attContatos(searching = false){
        $.ajax({
          type: "POST",
          data: {
            hash : 1,
            opened : sectionsOpened,
            search : valSearch
          },
          url: "../application/retListaContatos",
          success: function(result){
            document.getElementById('listaContatos').innerHTML = result;
            <?php if($setted){ ?>
              attCircleStatus();
              <?php } ?>
            }
          });
          if(!searching){
            setTimeout(attContatos, 11000);
          }
        }

        <?php if($setted){ ?>

          function attCircleStatus(){
            $.ajax({
              type: "POST",
              data: {
                key : '<?php echo $_GET['hash']; ?>'
              },
              url: "../application/retListaContatos",
              success: function(result){
                if(result != '0'){
                  var stateCircle = 'border-color-' + result;
                  $('#circleStatusChat').removeClass('border-color-offline');
                  $('#circleStatusChat').removeClass('border-color-ausente');
                  $('#circleStatusChat').removeClass('border-color-online');
                  $('#circleStatusChat').addClass(stateCircle);
                  document.getElementById('textStatusChat').innerHTML = result;
                }
              }
            });
          }

          var showingMessage = 0;
          var dataLast = 0;
          var messagesId = "-";

          function attMensagens(){
            $.ajax({
              type: "POST",
              data: {
                hash : '<?php echo $_GET['hash'] ?>',
                last : showingMessage,
                dataLast : dataLast
              },
              url: "../application/chatMensagens",
              success: function(result){
                result = JSON.parse(result);

                var i = 0;
                for(i = 0; i<result.length; i++) {
                  if(messagesId.indexOf("-"+result[i]['idMessage']+"-") < 0){
                    showingMessage = result[i]['idMessage'];
                    dataLast = result[i]['dataMessage'];
                    $("#chatMensagens").append(result[i]['bodyMessage']);
                    $("#idMsg"+result[i]['idMessage']).fadeIn('slow');
                    messagesId = messagesId + result[i]['idMessage']+"-";
                  }
                }
                if(i > 0 && ancora == true){
                  var timeTo = 0;
                  if(i < 10){
                    timeTo = 1000;
                  }
                  var heightTo = $("#chatMensagens")[0].scrollHeight;
                  $('#chatMensagens').animate({
                    scrollTop: heightTo
                  }, timeTo);
                }
              }
            });
          }

          function intervalAttMensagens(){
            attMensagens();
            setTimeout(intervalAttMensagens, 4000);
          }

          function ativaAncora(){
            ancora = true;
          }

          function desativaAncora(){
            ancora = false;
          }

          function sendMessage(){
            var msg = document.getElementById('textareaChat').value;
            if(msg != ""){
              $.ajax({
                type: "POST",
                data: {
                  hash : '<?php echo $_GET['hash']; ?>',
                  token : '<?php echo $_GET['token']; ?>',
                  msg : msg
                },
                url: "../application/sendMessage",
                success: function(result){
                  document.getElementById('textareaChat').value = "";
                  attMensagens();
                }
              });
            } else {
              $('#textareaChat').focus();
            }
          }

          $('#textareaChat').click(ativaAncora);

          function stopEvent(event) {
            if (event.preventDefault) {
              event.preventDefault();
              event.stopPropagation();
            } else {
              event.returnValue = false;
              event.cancelBubble = true;
            }
          }

          function areaEnvia(obj, evt) {
            var e = evt || event;
            var k = e.keyCode;
            if(k == 13) { //verifica se teclou enter
              if(!e.shiftKey) {
                if(obj.form){
                  sendMessage();
                }
                stopEvent(e);
              }
            }
          }

          <?php } ?>
</script>
</body>
</html>
