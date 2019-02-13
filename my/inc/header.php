<?php if(!isset($page)){
  $page = false;
} ?>
<!-- header header  -->
<div class="header">
  <nav class="navbar top-navbar navbar-expand-md navbar-light">
    <!-- Logo -->
    <div class="navbar-header">
      <a class="navbar-brand" href="inicio">
        <!-- Logo icon -->
        <b><img src="assets/images/o_.png" height="40px" alt="homepage" class="dark-logo" /></b>
        <!--End Logo icon -->
        <!-- Logo text -->
        <span style="margin-left: -5px;"><img src="assets/images/easy.png" height="40px" alt="homepage" class="dark-logo" /></span>
      </a>
    </div>
    <!-- End Logo -->
    <div class="navbar-collapse">
      <!-- toggle and nav items -->
      <ul class="navbar-nav mr-auto mt-md-0">
        <!-- This is  -->
        <li class="nav-item">
          <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)">
            <i class="mdi mdi-menu"></i>
          </a>
        </li>
        <li class="nav-item m-l-10">
          <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)">
            <i class="ti-menu"></i>
          </a>
        </li>
        <!-- Messages -->
        <!-- End Messages -->
      </ul>
      <?php if($_SESSION['tipo'] == 'agente' || $_SESSION['tipo'] == 'supervisor'){ ?>
        <div class="center-nav">
          <ul class="navbar-nav" id="spanIconNewAtendimento" style="width: 50px; margin: auto; padding-top: 0px;">
            <li class="nav-item" style="padding-left: 50px; padding-right: 22px;">
              <span>
                <a class="<?php if($page == 'media'){ echo "padrao"; } ?>" href="media">
                  <i class="fa fa-commenting" aria-hidden="true"></i>
                  <div class="notify" id="notifyNewMessageAtendimento" style="display: none"><span class="heartbit"></span> <span class="point"></span> </div>
                </a>
              </span>
            </li>
            <li class="nav-item dropdown" style="padding-left: 12px;padding-right: 12px;">
	      			<a class="dropdown-toggle" id="pendentesBtn" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    	          <i class="fa fa-hourglass" aria-hidden="true" style="font-size: 1.3em;padding-top: 4px"></i>
    	          <div class="notify" id="notifyNewMessageAtendimentoPendente" style="display: <?php if(!retSitChatAtendimentoPendente($db)){ echo 'none'; } ?>"><span class="heartbit"></span> <span class="point"></span> </div>
              </a>
              <div id="listaPendentes" class="dropdown-menu dropdown-menu-right mailbox animated fadeIn"></div>
            </li>
          </ul>
        </div>
      <?php } ?>
      <!-- User profile and search -->
      <ul class="navbar-nav my-lg-0">

        <!-- Comment -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-muted text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-bell"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right mailbox animated fadeIn">
            <ul>
              <li>
                <div class="drop-title">Meus lembretes</div>
              </li>
              <li>
                <div class="message-center">
                  <!-- Message -->
                  <?php
                  $lembToolbar = retArrayLembretesToolbar($db);
                  if(count($lembToolbar) == 0){
                    ?>
                    <h6><i>Nenhum lembrete de post-it ativo</i></h6>
                    <?php
                  }
                  foreach ($lembToolbar as $l) {
                    ?>
                    <a href="lembretes">
                      <div class="btn btn-<?php echo $l['cor']?> btn-circle btn-lembretes-toolbar" ><i class="fa fa-sticky-note-o"></i></div>
                      <div class="mail-contnet" title="teste">
                        <h5><?php echo $l['titulo'] ?></h5>
                        <span class="mail-desc"><?php echo $l['desc'] ?></span>
                        <span class="time"><?php if($l['alarme'] != "1000-01-01 00:00:00"){ echo dataBdParaHtml($l['alarme']); }; ?></span>
                        </div>
                      </a>
                      <?php
                    }
                    ?>
                  </div>
                </li>
                <li>
                  <a class="nav-link text-center" href="lembretes">Ver todos os Post-its <i class="fa fa-angle-right"></i> </a>
                </li>
              </ul>
            </div>
          </li>
          <!-- End Comment -->
          <!-- Messages -->
          <li class="nav-item dropdown"  style="display: <?php if($_SESSION['chat'] == 'nao'){ echo "none"; } ?>">
            <a class="nav-link text-muted  " href="chat" id="2"> <i class="fa fa-envelope"></i>
              <div class="notify" id="notifyNewMessage" style="display: <?php if(!retSitChat($db)){ echo "none"; } ?>"> <span class="heartbit"></span> <span class="point"></span> </div>
            </a>
          </li>
          <!-- End Messages -->
          <!-- Profile -->
          <li class="nav-item dropdown">
            <div id="avatarStatus" class="dropdown-toggle avatar-ball-toolbar border-color-<?php if($pausaStatus){ ?>ausente<?php } else { ?>online<?php } ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img src="assets/avatar/<?php echo $_SESSION['avatar']; ?>" alt="user"/>
            </div>
            <div class="dropdown-menu dropdown-menu-right animated fadeIn">
              <ul class="dropdown-user">
                <?php if($_SESSION['tipo'] == 'agente'){ ?>
                  <li><a id="pauseControl" class="cursorPointer"><i id="iconPause" class="fa <?php if($pausaStatus){ ?>fa-play m-r-10"></i>Sair da pausa<?php } else { ?>fa-pause m-r-10"></i>Entrar em pausa<?php } ?></a></li>
                <?php } ?>
                <?php if($_SESSION['tipo'] == 'coordenador' || $_SESSION['tipo'] == 'dev' ){ ?>
                  <li><a href="controlPanel"><i class="fa fa-cogs m-r-10"></i>Configurações</a></li>
                <?php } ?>
                <li><a href="profile"><i class="ti-user m-r-10"></i>Meu perfil</a></li>
                <li class="li-separator"></li>
                <li><a href="../application/logout"><i class="fa fa-power-off m-r-10"></i>Sair</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item dropdown" id="openChatFrame" style="display: none<?php //if($_SESSION['chat'] == 'nao'){ echo 'none'; } ?>">
            <span class="nav-link" style="font-size: 22px; margin-top: -4px;">
              <i class="fa fa-list-ul" aria-hidden="true"></i>
            </span>
          </li>
        </ul>
      </div>
    </nav>
  </div>
  <!-- End header header -->
