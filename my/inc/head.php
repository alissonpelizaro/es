<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="MyOmni | Gestor de agendamentos de callcenter">
  <meta name="author" content="Alisson Pelizaro">
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/icon.png">
  <title>MyOmni | Smart scheduling </title>

	<!-- Plugin clockpicker -->
  <link href="assets/css/lib/clockpicker/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="assets/css/lib/clockpicker/jquery-clockpicker.min.css" rel="stylesheet" type="text/css">

  <!-- Bootstrap Core CSS -->
  <link href="assets/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/lib/toastr/toastr.min.css" rel="stylesheet">
  <link href="assets/css/lib/select2/select2.min.css" rel="stylesheet">
  <link href="assets/css/lib/sweetalert/sweetalert.css" rel="stylesheet">
  <link href="assets/css/lib/dropzone/dropzone.css" rel="stylesheet">
  <!--
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  -->

  <!-- Custom CSS -->
  <link href="assets/css/helper.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">

  <link href="assets/css/myV2.3.css" rel="stylesheet">
  <link href="assets/css/media_enterness.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/css/lib/pignose/pignose.calendar.css">
  <link href="assets/css/lib/pignose/datepicker.min.css" rel="stylesheet" type="text/css">

	<!-- Plugin draggable -->
	<link href="assets/css/lib/draggable/gridstack.min.css" rel="stylesheet"><!-- Erro atend -->

  <?php if(isset($wysiwyg)){ ?>
    <!-- HTML5 EDITOR -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
    <!-- FIM HTML5 EDITOR -->
  <?php } ?>

</head>

<body class="fix-header fix-sidebar mini-sidebar">
  <!-- Preloader - style you can find in spinners.css -->
  <div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
      <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
    </svg>
  </div>
