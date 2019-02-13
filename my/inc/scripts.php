<!-- All Jquery -->
<script src="assets/js/lib/jquery/jquery.min.js"></script>
<script src="assets/js/lib/jquery-ui/jquery-ui.min.js"></script>
<script src="assets/js/lib/jquery/jquery.cookie.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="assets/js/lib/bootstrap/js/popper.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/lib/toastr/toastr.min.js"></script>
<script src="assets/js/lib/sweetalert/sweetalert.min.js"></script>
<!--stickey kit -->
<script src="assets/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
<!--Menu sidebar -->
<script src="assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/sidebarmenu.js"></script>

<script src="assets/js/lib/pignose/pignose.calendar.full.min.js"></script>
<script src="assets/js/lib/pignose/pignose.calendar.min.js"></script>
<script src="assets/js/lib/pignose/datepicker.min.js"></script>
<script src="assets/js/lib/pignose/i18n/datepicker.pt.js"></script>
<script src="assets/js/lib/jquery-mask/jquery.mask.js"></script>
<script src="assets/js/lib/select2/select2.full.min.js"></script>
<script src="assets/js/lib/switcher/jquery.switcher.js"></script>
<script src="assets/js/lib/dropzone/dropzone.js"></script>

<!-- Plugin clockpicker -->
<script src="assets/js/lib/clockpicker/bootstrap-clockpicker.min.js"></script>
<script src="assets/js/lib/clockpicker/jquery-clockpicker.min.js"></script>

<!--Custom JavaScript -->
<script src="assets/js/scripts.js"></script>
<script src="assets/js/lib/datatables/datatables.min.js"></script>
<script src="assets/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="assets/js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="assets/js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="assets/js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="assets/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="assets/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

<!-- Plugin draggable -->
<script src="assets/js/lib/draggable/lodash.min.js"></script>
<script src="assets/js/lib/draggable/highlight.min.js"></script>
<script src="assets/js/lib/draggable/gridstack.min.js"></script>
<script src="assets/js/lib/draggable/gridstack.jQueryUI.js"></script><!-- Erro filas e agentes -->

<script type="text/javascript" src="assets/js/lib/chart-js/Chart.min.js"></script>

<script type="text/javascript">
  $(function () {
  	$('#grid-principal').gridstack({
      alwaysShowResizeHandle: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
      resizable: {
      	handles: 'e, s, w'//, sw, se'
      },
      cellHeight: '45px'
    });
		$('#grid-lembretes').gridstack({
      alwaysShowResizeHandle: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
      resizable: {
      	handles: 'e, s, w, sw, se'
      },
      cellHeight: '10px'
    });
  });
</script>

<script type="text/javascript">
$(function () {
  $('[data-toggle="popover"]').popover()
});
</script>

<?php if(isset($wysiwyg)){ ?>
  <!-- HTML5 EDITOR -->
  <script src="assets/js/lib/summernote/summernote.min.js"></script>
  <!-- FIM HTML5 EDITOR -->
<?php } ?>
<script src="assets/js/myomni.v3.js"></script>
<?php if(isset($idUser)){ ?>
  <?php if(!isset($setted)){ ?>
    <script type="text/javascript">
    checkNewMessage();
    </script>
  <?php } ?>
  <?php if(isset($_SESSION['tipo']) && ($_SESSION['tipo'] == 'agente' || $_SESSION['tipo'] == 'supervisor')){ ?>
    <script type="text/javascript">
    checkNewMessageAtendimento();
    </script>
  <?php } ?>
  <?php if(isset($chatSide) && $chatSide){ ?>
    <script type="text/javascript">
    startSincChatSide();
    </script>
  <?php } ?>
  <?php if(isset($_SESSION['tipo']) && $_SESSION['tipo'] != 'agente' && $_SESSION['tipo'] != 'tecnico' && $_SESSION['tipo'] != 'gestor'){ ?>
    <script type="text/javascript">
    checkNewRestrict();
    </script>
  <?php } ?>
  <?php if(isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'agente'){ ?>
    <script type="text/javascript">
    checaLembreteEstacionados();
    </script>
  <?php } ?>
  <script type="text/javascript">
  //Scrips base internos
  var time = Math.floor(Math.random() * 10000) - 3000;
  setTimeout(checaLembrete, time);
  setTimeout(checaBroadcast, 2000);
  mantemSessao(<?php echo $idUser ?>);

  </script>
<?php } ?>
