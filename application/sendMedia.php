<?php
include '../core.php';

/*
* Arquivo que envia arquivos (imagens, documentos e vídeos) para o whatsapp
*/

$targetPath = '../my/assets/medias/';

if (!empty($_FILES) && isset($_GET['hash'])) {
  //print_r($_FILES);

  ini_set('upload_max_filesize', '100MB');
  set_time_limit(20);

  $data = date("Y-m-d H:i:s");

  //Carrega informações do atendimento
  $idAtendimento = tratarString($_GET['hash'])/11;
  $sql = "SELECT `remetente`, `plataforma` FROM `atendimento` WHERE `idAtendimento` = '$idAtendimento'";
  $at = $db->query($sql);
  $at = (object) $at->fetch();

  //Pega a extensão e define tipo do arquivo
  if($_FILES['file']['name'] == 'blob'){
    $ext = "jpeg";
  } else {
    $ext = explode(".", $_FILES['file']['name']);
    $ext = mb_strtolower($ext[count($ext)-1]);
  }

  if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif'){
    $mediaType = 'image/'.$ext;
  } else if($ext == 'avi' || $ext == 'mp4'){
    $mediaType = 'video/'.$ext;
  } else if($ext == 'mp3'){
    $mediaType = 'audio/mpeg';
  } else if($ext == 'doc' || $ext == 'docx' || $ext == 'pdf' || $ext == 'xls' || $ext == 'xlsx'){
    if($ext == 'doc'){
      $mediaType = 'application/msword';
    } else if($ext == 'docx'){
      $mediaType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    } else if($ext == 'pdf'){
      $mediaType = 'application/pdf';
    } else if($ext == 'xls'){
      $mediaType = 'application/vnd.ms-excel';
    } else if($ext == 'xlsx'){
      $mediaType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }
  } else {
    $mediaType = 'invalid';
  }

  //Salva arquivo no servidor
  $newName = md5(round(0,50).$idAtendimento.date("Y-m-d H:i:s")).".".$ext;
  $_FILES['file']['name'] = $newName;
  $tempFile = $_FILES['file']['tmp_name'];
  $targetFile =  $targetPath. $_FILES['file']['name'];
  move_uploaded_file($tempFile,$targetFile);

  //Registra a mensagem sainte no banco de dados
  if($mediaType == 'image/'.$ext){
    $msg = "<span class='media-img-chat'><a href='http://".$config->server."/my/assets/medias/".$_FILES['file']['name']."' target='_blank'><img src=".$targetFile."></a></span>";
  } else if($mediaType == 'video/'.$ext){
    $msg = "<span class='media-video-chat'>
    <video width='250' controls>
    <source src='".$targetFile."' type='video/mp4' />
    Seu navegador não suporta esse tipo de vídeo
    </video>
    </span>";
  } else if($mediaType == 'audio/mpeg'){
    $msg = "<span class='media-audio-chat'>
    <audio controls>
    <source src='".$targetFile."' type='audio/mpeg'/>
    Seu navegador não suporta esse tipo de audio
    </audio>
    </span>";
  } else if(strpos($mediaType, "application/") !== false){
    $msg = "<span class='media-doc-chat'>
    <a href='http://".$config->server."/my/assets/medias/".$_FILES['file']['name']."' target='_blank'>
    <i class='fa fa-download display-1'></i><br>
    <i>Documento anexado (".$ext.")</i>
    </a>
    </span>";
  } else {
    $msg = "<i class='text-danger'>Tipo de arquivo inválido (".$ext.")</i>";
  }

  $msg = str_replace("'", '"', $msg);
  $sql = "INSERT INTO `chat_atendimento` (`idAtendimento`, `chat`, `rmt`, `visualizada`, `dataEnvio`)
  VALUES ('$idAtendimento', '$msg', 'agente', '0', '$data')";

  if($db->query($sql)){
    $sql = "UPDATE `atendimento` SET `resposta` = 'agente' WHERE `idAtendimento` = '$idAtendimento'";
    $db->query($sql);

    if($mediaType != 'invalid'){
      $url = $config->getUrlWhatsApp($at->remetente);
      //$inline = 'curl -X POST "'.$url.'" -H "Postman-Token: 68f9212f-b8c3-4648-b23e-b52ad1b3c6c3" -H "auth-key: '.$config->getTokenWhatsApp().'" -H "cache-control: no-cache" -H "client_id: '.$config->getUserApi().'" -H "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW" -F "message=@..\\my\\assets\\medias\\'.$_FILES['file']['name'].';filename='.$_FILES['file']['name'].'" -F message=';
      $inline = 'curl -X POST "'.$url.'" -m 15 -H "auth-key: '.$config->getTokenWhatsApp().'" -H "client_id: '.$config->getUserApi().'" -F "message=@'.$targetFile.'" -F message=';
      $response = shell_exec($inline);

      echo "--".$inline;
    }
  }
}

?>
