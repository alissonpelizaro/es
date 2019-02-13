<?php
include '../application/atendimentos.php';
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
        <h3 class="padrao" style="float:left; margin-right: 10px;">Atendimentos</h3>
        <a href="javascript:void(0)" onclick="favorito();">
          <img
          src="<?php if($favorito){ ?>assets/icons/star1.png
          <?php } else { ?>assets/icons/star0.png
          <?php }?>"
          id="favorito">
        </a>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Atendimentos</li>
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
            <div class="row">
              <div class="col-md-3">
                <h4 class="card-title">
                  Atendentes
                </h4>
                <input type="text" placeholder="Buscar..." class="input-filter-atendimentos form-control" id="filterAtendente">
                <div class="box-atendentes set-scoll-atendimentos" id="div-atendentes">
                </div>
              </div>
              <div class="col-md-9" style="border-left: 1px solid #eee;" id="divAtendimentos">
                <h4 class="card-title">
                  Atendimentos
                </h4>
                <input type="text" placeholder="Buscar..." class="mw-350 input-filter-atendimentos form-control" id="filterAtendimento">
                <div class="row" style="overflow-y: auto;max-height: 610px" id="div-atendimentos">
                </div>
              </div>
              <div class="col-md-6" id="divChatAtendimento" style="display: none; border-left: 1px solid #eee;">
                <h4 class="card-title">
                  Conversa
                  <i class="span-close-atendimento fa fa-times pull-right" onclick="closeDivChat('span')" aria-hidden="true"></i>
                </h4>
                <div class="card" >
                  <div id="bodyChatAtendimento">
                    <div class="box-chat">
                      <div class="box-chat-header">
                        <div class="avatar-chat">
                          <img id="avatarAtendimento" src="assets/avatar/default.jpg">
                        </div>
                        <div class="name-chat">
                          <h3 id="nomeCliente"></h3>
                        </div>
                      </div>
                      <div class="box-chat-body" id="chatMensagens" style="max-height: 450px; height: 370px" onclick="desativaAncora()">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" id="mediaTempos"></div>
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
var atendimentoChecked = "";
var msgChecked = "";
var showingCalls = 0;
var first = 1;
var filterAtendente = "";
var filterAtendimento = "";
var ancora = true;

var showingMessage = 0;
var dataLast = 0;
var messagesId = "-";

$("#filterAtendente").keyup(function(){
  filterAtendente = $(this).val();
  attAtendentes();
});
$("#filterAtendimento").keyup(function(){
  filterAtendimento = $(this).val();
  attAtendimentos();
});

function closeDivChat(local = false){
  $("#divAtendimentos").removeClass("col-md-3").addClass("col-md-9");
  $(".classAtendimento").removeClass("col-12").addClass("col-xl-4");
  $("#divChatAtendimento").hide();
  $(".classAtendimento").hide();
  $(".classAtendimento").fadeIn("slow");
  showingCalls = 0;
  first = 1;
  if(local == 'span'){
    attAtendimentos();
  }
}

function favorito() {
  var favorito = document.getElementById("favorito").src;
  var favoritar = 1;

  if(favorito == "http://<?php echo $config->server; ?>/my/assets/icons/star1.png"){
    favoritar = 0;
  }

  $.ajax({
    type: "POST",
    data: {
      page : "atendimentos",
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
}

function clickAtendimento(id){
  $("#divAtendimentos").removeClass("col-md-9").addClass("col-md-3");
  $(".classAtendimento").removeClass("col-xl-4").addClass("col-12");
  $("#divChatAtendimento").fadeOut(100).fadeIn(350);
  if(showingCalls == 0){
    $(".classAtendimento").hide();
    $(".classAtendimento").fadeIn("slow");
    showingCalls = 1;
  }
  first = 0;
  msgChecked = id;
  $(".classAtendimento-ativo").removeClass("classAtendimento-ativo");
  $("#atendimento-"+msgChecked).addClass("classAtendimento-ativo");

  showingMessage = 0;
  dataLast = 0;
  messagesId = "-";

  $("#chatMensagens").html("");

  attMensagens();
  mediaTempos();
  carregaAvatar();
}

function carregaAvatar(){
  if(msgChecked != ""){
    $.ajax({
      type: "POST",
      data: {
        idCliente : false,
        idAtendimento : msgChecked
      },
      url: "../application/carregaCliente",
      success: function(result){
        if(result != "false"){
          result = JSON.parse(result);
          if(result['foto'] != "" && result['foto'] != null){
            $("#avatarAtendimento").attr('src', 'assets/medias/clients/'+result['foto']);
          } else {
            $("#avatarAtendimento").attr('src', 'assets/avatar/default.jpg');
          }
        } else {
          $("#avatarAtendimento").attr('src', 'assets/avatar/default.jpg');
        }
      }
    });
  }
}

function clickAtendente(id){
  atendimentoChecked = id;
  first = 1;
  closeDivChat();
  attAtendimentos();
  msgChecked = "";
  $(".div-atendente-ativo").removeClass("div-atendente-ativo");
  $("#atendente-"+atendimentoChecked).addClass("div-atendente-ativo");
}


function attAtendentes(){
  $.ajax({
    type: "POST",
    data: {
      filter : filterAtendente,
      setor : <?php echo $setorUser ?>
    },
    url: "../application/atendentesAjax",
    success: function(result){
      $("#div-atendentes").html(result);
      first = 0;
      $("#atendente-"+atendimentoChecked).addClass("div-atendente-ativo");
    }
  });
}
attAtendentes();
setInterval(attAtendentes, 5000);

function attAtendimentos(){
  if(atendimentoChecked != ""){
    $.ajax({
      type: "POST",
      data: {
        filter : filterAtendimento,
        showingCalls : showingCalls,
        id : atendimentoChecked,
        first : first
      },
      url: "../application/atendimentosAjax",
      success: function(result){
        $("#div-atendimentos").html(result);
        $("#atendimento-"+msgChecked).addClass("classAtendimento-ativo");
        first = 0;
      }
    });
  }
}
setInterval(attAtendimentos, 13000);

function attMensagens(){
  if(msgChecked != ""){
    hash = msgChecked * 53;
    $.ajax({
      type: "POST",
      data: {
        hash : hash,
        token : 'nome',
        local : 'assistindo',
        last : showingMessage,
        dataLast : dataLast
      },
      url: "../application/chatAtendimento",
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
          if(ancora || $("#chatMensagens")[0].scrollTop == 0){
            $('#chatMensagens').animate({
              scrollTop: heightTo
            }, timeTo);
          }
          //$("#chatAtendimento").scrollTop($("#chatAtendimento")[0].scrollHeight);
        }
        // alert($("#chatAtendimento")[0].scrollHeight);
      }
    });
  }
}
setInterval(attMensagens, 4000);

$("#chatMensagens").scroll(function (){

  if(($("#chatMensagens")[0].scrollHeight - 370) <= $("#chatMensagens")[0].scrollTop){
    ativaAncora();
  } else {
    desativaAncora();
  }
});

function ativaAncora(){
  ancora = true;
}

function desativaAncora(){
  ancora = false;
}

function mediaTempos(){
  if(msgChecked != ""){
    hash = msgChecked * 53;
    $.ajax({
      type: "POST",
      data: {
        hash : hash
      },
      url: "../application/atendimentosMsgAjax",
      success: function(result){
        var cursor = result.split("-*-");
        document.getElementById('nomeCliente').innerHTML = cursor[0];
        document.getElementById('mediaTempos').innerHTML = cursor[1];
      }
    });
  }
}
setInterval(mediaTempos, 60000);



<?php if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'success'){
  ?>
  swal("Opa, tudo certo!", "Um novo post-it foi criado!", "success");
  <?php
} ?>
<?php if(isset($_GET['action']) && $_GET['action'] == 'trashed' && isset($_GET['status']) && $_GET['status'] == 'success'){
  ?>
  swal("Feito!", "O post-it foi jogado fora!", "success");
  <?php
} ?>

</script>
</body>
</html>
