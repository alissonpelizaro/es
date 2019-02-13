<?php
include '../application/remoteChat.php';
//Define nível de restrição da página
$allowUser = array('dev', 'coordenador', 'administrador', 'supervisor', 'agente');
checaPermissao($allowUser);
$page = "media";
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
        <h3 class="padrao">Monitoramento: <i>Whatsapp</i></h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Monitoramento: <i>Whatsapp</i></li>
        </ol>
      </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
      <!-- Start Page Content -->
      <div class="row">
        <div class="col-7">
          <?php if($at->notifRest == 1 && ($at->restUser != "" || $at->restClient != "")){ ?>
            <div class="alert alert-danger" role="alert" id="boxAlertaRestrict">
              <h4 class="alert-heading text-danger"><b>Atenção!</b></h4>
              <?php if($at->restUser != "" && $at->restClient != ""){ ?>
                <p class="mb-0 text-danger">O agente tentou insultar esse cliente e o cliente demonstrou uma grande insatisfação no atendimento.</p>
              <?php } else if($at->restUser != ""){
                ?>
                <p class="mb-0 text-danger">O agente tentou insultar esse cliente.</p>
                <?php
              } else {
                ?>
                <p class="mb-0 text-danger">O cliente citou palavra(s) que indica(m) uma possível insatisfação.</p>
                <?php
              }
              ?>
            <button type="button" id="desativaAlertaRestrict" class="btn btn-danger btn-sm m-t-10">Desativar alerta!</button></i>
          </div>
        <?php } ?>
        <div class="card">
          <div class="row">
            <div class="col-12">
              <h4 style="margin-bottom: 0px;" class="card-title">Atendimento do agente <b><?php echo $agente["nome"] . " ". $agente["sobrenome"][0] . "."; ?></b> com o cliente <b><?php if($at->nome != ""){ echo $at->nome; } else { echo $at->remetente; }?></b>
                <img style="height: 25px; width: 25px;float: right" src="assets/icons/social/<?php echo $at->plataforma ?>.png">
              </h4>
              <i style="font-size:12px;" class="text-muted">Inicio do atendimento: <?php echo dataBdParaHtml($at->dataInicio) ?></i>
            </div>
          </div>
          <div class="row" >
            <div class="col-md-<?php if(!$setted){ echo "8 offset-md-2"; } ?> col-sm-12">
              <div class="">
                <div class="card-body">
                  <?php if(!$setted){
                    ?>
                    <div class="background-chat">
                    </div>
                    <?php
                  } else { ?>
                    <div class="box-chat" style="height: 100%;">
                      <div class="box-chat-body" id="remoteChatAtendimento">
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
			<div class="col-md-5">
				<div class="card">
				  <div class="card-body">
						<div class="box-chat" style="height: 350px;">
							<div class="box-chat-header">
							  <div id="circleStatusChat" class="avatar-chat">
							    <img src="assets/avatar/<?php if($agente["avatar"]){ echo $agente["avatar"]; } else { echo 'default.jpg'; } ?>" alt="<?php echo $agente["nome"] . " ". $agente["sobrenome"];?>">
							  </div>
							  <div class="name-chat">
							    <h3><?php echo $agente["nome"] . " ". $agente["sobrenome"];?></h3>
							    <div class="dropdown btn-trash" style="display: none;">
							      <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
							        <span class="fa fa-ellipsis-v" aria-hidden="true"></span>
							        <span class="caret"></span>
							       </button>
							       <ul class="dropdown-menu">
							       	<li><a href="#">Apagar conversa</a></li>
							       </ul>
							    </div>
							  </div>
							</div>
							<div class="box-chat-body" id="chatMensagens" onclick="desativaAncora()" style="max-height: 200px;height: 200px;"></div>
							<div class="box-chat-footer">
							  <form class="enterness">
							    <div class="row">
							      <div class="col-10">
							        <textarea name="msg" autofocus="" id="textareaChat" placeholder="Digite..." onkeydown="areaEnvia(this, event);"></textarea>
							      </div>
							      <div class="col-2 btn-send-chat">
							        <button type="button" class="btn btn-info btn-block" onclick="sendMessage()">
							          <i class="fa fa-share" aria-hidden="true"></i>
							        </button>
							      </div>
							    </div>
							  </form>
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
<?php include 'inc/scripts.php'; ?>

<!-- Remote chat -->
<script type="text/javascript">
var remoteAncora = true;
var focus = true;
<?php if($setted){ ?>
//Se está com uma conversa setada, força o scroll da conversa sempre para baixo
$("#remoteChatAtendimento").scrollTop($("#remoteChatAtendimento")[0].scrollHeight);
<?php } ?>

$(document).ready(function(){
	$("#desativaAlertaRestrict").click(function(){
		$("#boxAlertaRestrict").hide();
		$.ajax({
			type: "POST",
			data: {
				hash : '<?php echo $at->idAtendimento * 53 ?>',
				token : '<?php echo $_GET['token'] ?>'
			},
			url: "../application/desativaAlertaRestrict",
			success: function(callback){
				checkNewRestrict();
			}
		});
	});

	$('.js-example-basic-single').select2();

	<?php if($setted){ ?>
	remoteIntervalAttMensagens();
	<?php } ?>

});

<?php if($setted){ ?>

var showingMessage = 0;
var dataLast = 0;
var messagesId = "-";

function remoteAttMensagens(){
	$.ajax({
		type: "POST",
		data: {
			hash : '<?php echo $at->idAtendimento * 53 ?>',
			token : '<?php echo $_GET['token'] ?>',
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
					$("#remoteChatAtendimento").append(result[i]['bodyMessage']);
					$("#idMsg"+result[i]['idMessage']).fadeIn('slow');
					messagesId = messagesId + result[i]['idMessage']+"-";
				}
			}
			if(i > 0){
				var timeTo = 0;
				if(i < 10){
					timeTo = 1000;
				}
				var heightTo = $("#remoteChatAtendimento")[0].scrollHeight;
				if(remoteAncora || $("#remoteChatAtendimento")[0].scrollTop == 0){
					$('#remoteChatAtendimento').animate({
						scrollTop: heightTo
					}, timeTo);
				}
			}
		}
	});
}

function remoteIntervalAttMensagens(){
	remoteAttMensagens();
	setTimeout(remoteIntervalAttMensagens, 4000);
}

$("#remoteChatAtendimento").scroll(function (){
  if(($("#remoteChatAtendimento")[0].scrollHeight - 300) <= $("#remoteChatAtendimento")[0].scrollTop){
    remoteAtivaAncora()
  } else {
		remoteDesativaAncora()
  }
});

function remoteAtivaAncora(){
  remoteAncora = true;
}

function remoteDesativaAncora(){
  remoteAncora = false;
}

<?php } ?>

</script>

<!-- Chate com o agente -->
<script type="text/javascript">

var ancora = true;
<?php if($setted){ ?>
$("#chatMensagens").scrollTop($("#chatMensagens")[0].scrollHeight);
<?php } ?>

$(document).ready(function(){
	<?php if($setted){ ?>
  intervalAttMensagens();
  <?php } ?>
});


<?php if($setted){ ?>

var showingMessage = 0;
var dataLast = 0;
var messagesId = "-";

function attMensagens(){
  $.ajax({
    type: "POST",
    data: {
      hash : '<?php echo $hash."-".($idAgente * 319)."-".geraSenha(4) ?>',
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
        hash : '<?php echo $hash."-".($idAgente * 319)."-".geraSenha(4); ?>',
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
