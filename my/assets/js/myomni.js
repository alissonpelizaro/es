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

$(document).ready(function(){
  var alertId = false;
  var broadcastId = false;
  var time = Math.floor(Math.random() * 10000) - 3000;
  setTimeout(checaLembrete, time);
  setTimeout(checaBroadcast, 2000);
  });

  function checaLembrete(){
    $.ajax({
      type: "POST",
      data: {
        hash : 5
      },
      url: "../application/checaLembrete",
      success: function(result){
        if(result != 'false'){
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
        if(result != 'false'){
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
