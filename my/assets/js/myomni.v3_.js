function setToastDanger(titulo, msg){
  toastr.error(msg,titulo,{
    "positionClass": "toast-bottom-left",
    timeOut: 5000,
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
    "tapToDismiss": false
  });
}

function setToastInfo(titulo, msg){
  toastr.info(msg,titulo,{
    "positionClass": "toast-bottom-left",
    timeOut: 5000,
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
    "tapToDismiss": false
  });
}

function setToastInfo(titulo, msg){
  toastr.success(msg,titulo,{
    "positionClass": "toast-bottom-left",
    timeOut: 5000,
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
    "tapToDismiss": false
  });
}

$(document).ready(function(){
  var alertId = false;
  var broadcastId = false;


  $("#headerFrameChat").click(function(){
    $("#chatFrame").addClass('hideChatFrame');
    $("#chatFrame").removeClass('showChatFrame');
  });

  $("#openChatFrame").click(function(){
    $("#chatFrame").addClass('showChatFrame');
    //$("#chatFrame").removeClass('hideChatFrame');
  });

  $("#pauseControl").click(function() {
	  $.ajax({
		  url: "../application/pauseControl",
		  success: function(result){
			  if ($("#iconPause").hasClass("fa-pause")) {
				  $("#pauseControl").html("<i id='iconPause' class='fa fa-play m-r-10'></i>Sair da pausa");
				  $("#avatarStatus").removeClass("border-color-online").addClass("border-color-ausente");
			  } else {
				  $("#pauseControl").html("<i id='iconPause' class='fa fa-pause m-r-10'></i>Entar em pausa");
				  $("#avatarStatus").removeClass("border-color-ausente").addClass("border-color-online");
			  }
		  }
	  });
  });

});

function checaLembrete(){
  $.ajax({
    type: "POST",
    data: {
      hash : 5
    },
    url: "../application/checaLembrete",
    success: function(result){
      if(result != 'false' && result.indexOf("8/8-8/") > 0){
        result = result.split("8/8-8/");
        alertId = result[0];
        document.getElementById('conteudoAlertaBox').innerHTML = result[1];
        $('#boxAlertaBell').show();
        document.getElementById('audioSpan').src = "assets/sounds/lembrete.mp3";
        document.getElementById('audioSpan').play();
        //document.getElementById('audioLembrete').play();
      } else {
        $('#boxAlertaBell').hide();
      }
    }
  });
  setTimeout(checaLembrete, 35000);
}

function adiarAlertaBox(){
  $.ajax({
    type: "POST",
    data: {
      hash : 3,
      id : alertId
    },
    url: "../application/checaLembrete",
    success: function(result){
      $('#boxAlertaBell').hide();
    }
  });
}

function jogarForaAlertaBox(){
  $.ajax({
    type: "POST",
    data: {
      hash : 19,
      id : alertId
    },
    url: "../application/checaLembrete",
    success: function(result){
      $('#boxAlertaBell').hide();
    }
  });
}

function checaBroadcast(){
  $.ajax({
    type: "POST",
    data: {
      hash : 5
    },
    url: "../application/checaBroadcast",
    success: function(result){
      if(result != 'false' && result.indexOf("8/8-8/") > 0){
        result = result.split("8/8-8/");
        broadcastId = result[0];
        document.getElementById('conteudoAlertaBoxBroadcast').innerHTML = result[1];
        $('#boxAlertaBroadcast').show();
        document.getElementById('audioSpan').src = "assets/sounds/broadcast.mp3";
        document.getElementById('audioSpan').play();
        window.focus();
      } else {
        $('#boxAlertaBroadcast').hide();
      }
    }
  });
  setTimeout(checaBroadcast, 31000);
}

function confirmaBroadcast(){
  $.ajax({
    type: "POST",
    data: {
      hash : 14,
      id : broadcastId
    },
    url: "../application/checaBroadcast",
    success: function(result){
      $('#boxAlertaBroadcast').hide();
    }
  });
}

var sincChatSide = true;
var sincChatSideFilter = false;

function startSincChatSide(){
  if(sincChatSide){
    $.ajax({
      type: "POST",
      data: {
        hash : 73
      },
      url: "../application/chatSideBarContacts",
      success: function(result){
        if(result != 'false'){
          $('#body-frame-chat').html(result);
        } else {
          $('#body-frame-chat').html("<i style='text-align: center'>Chat temporariamente inoperante.</i>");
        }
      }
    });
  }
  setTimeout(startSincChatSide, 2000);
}

function checkNewMessage(){
  $.ajax({
    type: "POST",
    data: {
      hash : 52
    },
    url: "../application/checaNewMessage",
    success: function(result){
      if(result == 'true'){
        if(document.getElementById('notifyNewMessage').style.display == 'none'){
          $('#notifyNewMessage').show();
          setToastInfo('MyOmni Chat', 'Você tem uma nova mensagem no chat!');
        }
      } else {
        $('#notifyNewMessage').hide();
      }
    }
  });
  setTimeout(checkNewMessage, 9000);
}

function checkNewMessageAtendimento(){

  $.ajax({
    type: "POST",
    data: {
      hash : 52
    },
    url: "../application/checaNewMessageAtendimento",
    success: function(result){
      if(result == 'true'){
        if(document.getElementById('notifyNewMessageAtendimento').style.display == 'none'){
          $('#spanIconNewAtendimento').css('padding-top', '15px');
          $('#notifyNewMessageAtendimento').show();
          setToastInfo('WhatsApp Chat', 'Você tem uma nova mensagem no WhatsApp!');
        }
      } else {
        $('#notifyNewMessageAtendimento').hide();
        $('#spanIconNewAtendimento').css('padding-top', '0px');
      }
    }
  });
  setTimeout(checkNewMessageAtendimento, 9000);
}

function checkNewRestrict(){
  $.ajax({
    type: "POST",
    data: {
      hash : 53
    },
    url: "../application/checaNewRestrict",
    success: function(result){
      if(result != '0' && result != ""){
        $("#badgetRestrictinicio").addClass('text-danger');
        setToastDanger('Atenção!', '<a href="remoteChat?hash='+result+'&token=uyOZTWCdKn91XG8">Parece que um atendimento requer a intervenção de um superior!</a>')
      } else {
        $("#badgetRestrictinicio").removeClass('text-danger');
      }
    }
  });
  setTimeout(checkNewRestrict, 27000);
}

function mantemSessao(idUser){
  $.ajax({
    type: "POST",
    data: {
      hash : 2,
      id : idUser
    },
    url: "../application/mantemSessao"
  });
  setTimeout(mantemSessao, 65000, idUser);
}
