<?php
  include '../application/pagina.php';
  checaPermissao(1); //Define nível de restrição da página
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
            <h3 class="padrao">Dashboard</h3>
          </div>
          <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
              <li class="breadcrumb-item">Dashboard</li>
            </ol>
          </div>
        </div>
        <!-- End Bread crumb -->
        <!-- Container fluid  -->
       <div class="container-fluid">
         <!-- Start Page Content -->
         <div class="row">
           <div class="col-6">
             <div class="card">
               <div class="card-body"> Essa é uma mensagem de teste. </div>
             </div>
           </div>
           <div class="col-6">
             <div class="card">
               <div class="card-body"> Essa é outra mensagem de teste. </div>
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

</body>

</html>
