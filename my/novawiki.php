<?php
$wysiwyg = true;

include '../application/wiki.php';
include '../application/editawiki.php';

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
        <h3 class="padrao">MyOmni<i>Wiki</i> - <?php if($edit){ echo "Editar"; } else { echo "Nova"; } ?> wiki</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item"><a href="wiki">MyOmni<i>Wiki</i></a></li>
          <li class="breadcrumb-item"><?php if($edit){ echo "Editar"; } else { echo "Nova"; } ?><i>Wiki</i></li>
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
            <div class="card-body">
              <div class="jumbotron j-relative">
                <div class="container">
                  <div class="btn-group">
                    <h1 class="display-6"><i class="fa fa-book" aria-hidden="true"></i> MyOmni<i>Wiki<sub style="font-size: 25px;">editor</sub></i></h1>
                  </div>
                  <br>
                  <hr style="width: 80%;">
                  <?php if(!$categorias){
                    ?>
                    <div class="center">
                      <h3><i>Não existe nenhuma categoria criada ainda. Primeiro crie uma categoria.</i></h3>
                      <br>
                      <div class="row">
                        <div class="center">
                          <a href="wikicategories">
                            <button type="button" class="btn btn-secondary">Voltar</button>
                          </a>
                          <a href="newcategory">
                            <button type="submit" class="btn btn-info">Nova categoria</button>
                          </a>
                        </div>
                      </div>
                    </div>
                    <?php
                  } else {
                    ?>
                    <div class="row">
                      <div class="col">
                        <form method="post" action="../application/novaWiki" class="">
                          <input type="hidden" name="type" value="<?php if($edit){ echo $wiki['idWiki']*27; } else { echo "new"; } ?>">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="junta">Título</label>
                                <input type="text" class="form-control" name="titulo" value="<?php if($edit){ echo $wiki['titulo']; } ?>" maxlength="90" placeholder="Título" required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="junta">Categoria</label>
                                <select class="form-control my-1 mr-sm-2" name="categoria">
                                  <?php foreach ($categorias as $cat) {
                                    ?>
                                    <option value="<?php echo $cat['idCat'] ?>" <?php if($edit){ if($cat['idCat'] == $wiki['idCat']){ echo "selected"; } } ?>><?php echo $cat['nomeCat'] ?></option>
                                    <?php
                                  } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="junta">Subtítulo/resumo</label>
                                <input type="text" class="form-control" value="<?php if($edit){ echo $wiki['subtitulo']; } ?>" name="subtitulo" maxlength="240" required placeholder="Ex.: Entenda os processos para captalizar um cliente por telefone" />
                                  <small class="form-text text-muted"><i>Diga aqui de forma direta sobre do que se trata essa Wiki.</i></small>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label class="junta" onclick="showIcons()">
                                    <button type="button" class="btn btn-sm btn-info">Adicionar um ícone</button>
                                  </label>
                                  <input type="hidden" value="<?php if($edit){ echo $wiki['logo']; } ?>" name="icon" id="inIcon">
                                  <section id="icons" style="display: none;">
                                    <br>
                                    <?php
                                    for($i = 1; $i <= 22; $i++){ ?>
                                      <div class="icon-wiki" onclick="setIcon('<?php echo $i; ?>')" id="icon<?php echo $i; ?>">
                                        <img src="assets/images/icons/<?php echo $i; ?>.png">
                                      </div>
                                      <?php
                                    } ?>
                                  </section>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label class="junta" style="margin-bottom: -10px;">Conteúdo</label>
                                  <textarea class="textarea_editor form-control" id="conteudo-wiki"  name="conteudo" rows="15" placeholder="Digite..." style="height:450px" required><?php if($edit){ echo html_entity_decode($wiki['conteudo']); } ?></textarea>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="center">
                                <a href="wiki">
                                  <button type="button" class="btn btn-secondary">Voltar</button>
                                </a>
                                <button type="submit" class="btn btn-info">Salvar</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                      <?php
                    } ?>
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
    <script src="assets/js/lib/html5-editor/wysihtml5-0.3.0.js"></script>
    <script src="assets/js/lib/html5-editor/bootstrap-wysihtml5.js"></script>
    <script type="text/javascript">

    $(document).ready(function() {
      $('.textarea_editor').summernote({
        height: 400
      });
      <?php if($edit){ ?>
        resetaOpacity();
        $('#icon<?php echo $wiki['logo']; ?>').removeClass('icon-wiki-opaco');
        $('#icon<?php echo $wiki['logo']; ?>').addClass('icon-wiki-show');
        <?php } ?>
      });

      function setIcon(id){
        resetaOpacity();
        document.getElementById('inIcon').value = id;
        //document.getElementById('icon'+id).style.opacity = "1";
        $('#icon'+id).removeClass('icon-wiki-opaco');
        $('#icon'+id).addClass('icon-wiki-show');
      }

      function resetaOpacity(){
        for (var i = 1; i <= 22; i++) {
          //document.getElementById('icon'+i).style.opacity = "0.4";
          $('#icon'+i).removeClass('icon-wiki-show');
          $('#icon'+i).addClass('icon-wiki-opaco');
        }
      }

      function showIcons(){
        var atual = document.getElementById('icons').style.display;
        if(atual == 'none'){
          document.getElementById('icons').style.display = 'block';
        } else {
          document.getElementById('icons').style.display = 'none';
        }
      }

      </script>
      <!-- Include the Quill library -->
      <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

      <!-- Initialize Quill editor -->
      <script>

      $("#conteudo-preview").removeClass();
      var editor = document.getElementById("conteudo-wiki");
      var output = document.getElementById("conteudo-preview");

      function sendCode(){
        var atual = $('#conteudo-wiki').val();
        output.innerHTML = atual;
        setTimeout(sendCode, 3000);
      }
      sendCode();

      function htmlEntities(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
      }

      </script>
    </body>

    </html>
