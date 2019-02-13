<!-- Left Sidebar  -->
<div class="left-sidebar">
  <!-- Sidebar scroll-->
  <div class="scroll-sidebar">
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav">
      <ul id="sidebarnav">
        <?php

        /* ACESSO DESENVOLVEDOR */
        if($_SESSION['tipo'] == "dev"){ ?>
          <li class="nav-devider"></li>
          <li class="nav-label">Inicio</li>
          <li>
            <a href="inicio" aria-expanded="false"><i class="fa fa-home" id="badgetRestrictinicio"></i><span class="hide-menu">Inicio </span></a>
          </li>
          <li class="nav-label">Facilidades</li>
          <li>
            <a href="chat" aria-expanded="false"><i class="fa fa-comments-o"></i><span class="hide-menu">CHAT</span></a>
          </li>
          <?php if($util->getSectionPermission('mural')){ ?>
            <li>
              <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-square-o"></i><span class="hide-menu">Mural</span></a>
              <ul aria-expanded="false" class="collapse">
                <li><a href="mural">Ver mural</a></li>
                <li><a href="cadastramensagem">Nova mensagem</a></li>
              </ul>
            </li>
          <?php }
          if($util->getSectionPermission('broad')){ ?>
            <li>
              <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-paper-plane-o"></i><span class="hide-menu">Broadcast</span></a>
              <ul aria-expanded="false" class="collapse">
                <li><a href="broadcast">Nova broadcast</a></li>
                <li><a href="broadcasts">Histórico de broadcast</a></li>
              </ul>
            </li>
          <?php }
          if($util->getSectionPermission('wiki')){ ?>
            <li>
              <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-book"></i><span class="hide-menu">Wiki</span></a>
              <ul aria-expanded="false" class="collapse">
                <li><a href="wiki">Abrir wiki</a></li>
                <li><a href="novawiki">Nova wiki</a></li>
                <li><a href="wikicategories">Categorias wiki</a></li>
              </ul>
            </li>
          <?php } ?>
          <li>
            <a href="lembretes" aria-expanded="false"><i class="fa fa-sticky-note-o"></i><span class="hide-menu">Post-its</span></a>
          </li>
          <?php if($util->getSectionPermission('media')){ ?>
            <li>
              <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-file-text-o" aria-hidden="true"></i><span class="hide-menu">Relatórios</span></a>
              <ul aria-expanded="false" class="collapse">
                <li><a href="relatorioAtendimento">Relatório de atendimentos</a></li>
                <!-- <li><a href="relatorioAgente">Relatório de agentes</a></li> -->
                <li><a href="relatorioEventos">Relatório de eventos</a></li>
                <!-- <li><a href="relatorioEventos">Relatório de filas</a></li>
                <li><a href="relatorioEventos">Relatório de pausas</a></li>
                <li><a href="log">Log de eventos</a></li> -->
              </ul>
            </li>
            <li>
              <a href="atendimentos" aria-expanded="false"><i class="fa fa-handshake-o" aria-hidden="true"></i><span class="hide-menu">Atendimentos</span></a>
            </li>
          <?php } ?>

  
            <?php if($util->getSectionPermission('media')){ ?>
              <li class="nav-label">Configurações</li>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-commenting"></i><span class="hide-menu">Mídias</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="dicionario-restricoes">Dicionario de restrições</a></li>
                  <li><a href="mensagens-padrao">Mensagens padrão</a></li>
                </ul>
              </li>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-pencil" aria-hidden="true"></i><span class="hide-menu">Cadastros</span></a>
                <ul aria-expanded="false" class="collapse">
									<?php if($util->getSectionPermission('media')){ ?>
				            <li><a href="clientes" aria-expanded="false">Clientes</a></li>
				          <?php }?>
									<li>
				            <a class="has-arrow  " href="#" aria-expanded="false">Usuários</a>
				            <ul aria-expanded="false" class="collapse">
				              <li><a href="coordenadores">Coordenadores</a></li>
				              <li><a href="administradores">Administradores</a></li>
				              <li><a href="supervisores">Supervisores</a></li>
				              <li><a href="agentes">Agentes</a></li>
				            </ul>
				          </li>
				          <li>
				            <a class="has-arrow  " href="#" aria-expanded="false">Grupos</a>
				            <ul aria-expanded="false" class="collapse">
				              <li><a href="grupos">Gerenciar grupos</a></li>
				            </ul>
				          </li>
				          <li>
				            <a class="has-arrow  " href="#" aria-expanded="false">Setores</a>
				            <ul aria-expanded="false" class="collapse">
				              <li><a href="setores">Gerenciar setores</a></li>
				            </ul>
				          </li>
									<?php if($util->getSectionPermission('conc')){ ?>
			              <li>
			                <a class="has-arrow  " href="#" aria-expanded="false">Casas</a>
			                <ul aria-expanded="false" class="collapse">
			                  <li><a href="casas">Gerenciar casas</a></li>
			                  <li><a href="novacasa">Nova casa</a></li>
			                </ul>
			              </li>
			            <?php } ?>
			            <?php if($util->getSectionPermission('media')){ ?>
			              <li><a href="filas"aria-expanded="false">Filas</a></li>
			            <?php } ?>
								</ul>
							</li>
							<?php } ?>
					<?php

            /* ACESSO COORDENADOR */
          } else if($_SESSION['tipo'] == "coordenador"){
            ?>
            <li class="nav-devider"></li>
            <li class="nav-label">Inicio</li>
            <li>
              <a href="inicio" aria-expanded="false"><i class="fa fa-home"></i><span class="hide-menu">Inicio </span><span id="badgetRestrictinicio" class="badge badge-danger"></span></a>
            </li>
            <li class="nav-label">Facilidades</li>
            <li>
              <a href="chat" aria-expanded="false"><i class="fa fa-comments-o"></i><span class="hide-menu">CHAT</span></a>
            </li>
            <?php if($util->getSectionPermission('mural')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-square-o"></i><span class="hide-menu">Mural</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="mural">Ver mural</a></li>
                  <li><a href="cadastramensagem">Nova mensagem</a></li>
                </ul>
              </li>
            <?php }
            if($util->getSectionPermission('broad')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-paper-plane-o"></i><span class="hide-menu">Broadcast</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="broadcast">Nova broadcast</a></li>
                  <li><a href="broadcasts">Histórico de broadcast</a></li>
                </ul>
              </li>
            <?php }
            if($util->getSectionPermission('wiki')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-book"></i><span class="hide-menu">Wiki</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="wiki">Abrir wiki</a></li>
                  <li><a href="novawiki">Nova wiki</a></li>
                  <li><a href="wikicategories">Categorias wiki</a></li>
                </ul>
              </li>
            <?php } ?>
            <li>
              <a href="lembretes" aria-expanded="false"><i class="fa fa-sticky-note-o"></i><span class="hide-menu">Post-its</span></a>
            </li>
            <?php if($util->getSectionPermission('media')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-file-text-o" aria-hidden="true"></i><span class="hide-menu">Relatórios</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="relatorioAtendimento">Relatório de atendimentos</a></li>
                  <!-- <li><a href="relatorioAgente">Relatório de agentes</a></li> -->
                  <li><a href="relatorioEventos">Relatório de eventos</a></li>
                  <!-- <li><a href="relatorioEventos">Relatório de filas</a></li>
                  <li><a href="relatorioEventos">Relatório de pausas</a></li> -->
                </ul>
              </li>
              <li>
                <a href="atendimentos" aria-expanded="false"><i class="fa fa-handshake-o" aria-hidden="true"></i><span class="hide-menu">Atendimentos</span></a>
              </li>
            <?php } ?>
            <?php if($util->getSectionPermission('media')){ ?>
              <li class="nav-label">Configurações</li>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-commenting"></i><span class="hide-menu">Mídias</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="dicionario-restricoes">Dicionario de restrições</a></li>
                  <li><a href="mensagens-padrao">Mensagens padrão</a></li>
                </ul>
              </li>
             	<li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-pencil" aria-hidden="true"></i><span class="hide-menu">Cadastros</span></a>
                <ul aria-expanded="false" class="collapse">
	                <?php if($util->getSectionPermission('media')){ ?>
			              <li><a href="clientes" aria-expanded="false">Clientes</a></li>
			            <?php } ?>
									<li>
			              <a class="has-arrow  " href="#" aria-expanded="false">Usuários</a>
			              <ul aria-expanded="false" class="collapse">
			                <li><a href="administradores">Administradores</a></li>
			                <li><a href="supervisores">Supervisores</a></li>
			                <li><a href="agentes">Agentes</a></li>
			              </ul>
			            </li>
			            <li>
			              <a class="has-arrow  " href="#" aria-expanded="false">Grupos</a>
			              <ul aria-expanded="false" class="collapse">
			                <li><a href="grupos">Gerenciar grupos</a></li>
			              </ul>
			            </li>
			            <?php if($util->getSectionPermission('conc')){ ?>
			              <li>
			                <a class="has-arrow  " href="#" aria-expanded="false">Casas</a>
			                <ul aria-expanded="false" class="collapse">
			                  <li><a href="casas">Gerenciar casas</a></li>
			                  <li><a href="novacasa">Nova casa</a></li>
			                </ul>
			              </li>
			            <?php } ?>
			            <?php if($util->getSectionPermission('media')){ ?>
			              <li>
			                <a href="filas"aria-expanded="false">Filas</a>
			              </li>
			            <?php } ?>
								</ul>
							</li>
            	<?php } ?>
            <?php



            /* ACESSO ADMINISTRADOR */
          }  else if($_SESSION['tipo'] == "administrador"){
            ?>
            <li class="nav-devider"></li>
            <li class="nav-label">Inicio</li>
            <li>
              <a href="inicio" aria-expanded="false"><i class="fa fa-home"></i><span class="hide-menu">Inicio </span><span id="badgetRestrictinicio" class="badge badge-danger"></span></a>
            </li>
            <li class="nav-label">Facilidades</li>
            <li>
              <a href="chat" aria-expanded="false"><i class="fa fa-comments-o"></i><span class="hide-menu">CHAT</span></a>
            </li>
            <?php if($util->getSectionPermission('mural')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-square-o"></i><span class="hide-menu">Mural</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="mural">Ver mural</a></li>
                  <li><a href="cadastramensagem">Nova mensagem</a></li>
                </ul>
              </li>
            <?php }
            if($util->getSectionPermission('broad')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-paper-plane-o"></i><span class="hide-menu">Broadcast</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="broadcast">Nova broadcast</a></li>
                  <li><a href="broadcasts">Histórico de broadcast</a></li>
                </ul>
              </li>
            <?php }
            if($util->getSectionPermission('wiki')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-book"></i><span class="hide-menu">Wiki</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="wiki">Abrir wiki</a></li>
                  <li><a href="novawiki">Nova wiki</a></li>
                  <li><a href="wikicategories">Categorias wiki</a></li>
                </ul>
              </li>
            <?php } ?>
            <li>
              <a href="lembretes" aria-expanded="false"><i class="fa fa-sticky-note-o"></i><span class="hide-menu">Post-its</span></a>
            </li>
            <?php if($util->getSectionPermission('media')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-file-text-o" aria-hidden="true"></i><span class="hide-menu">Relatórios</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="relatorioAtendimento">Atendimento</a></li>
                </ul>
              </li>
            <?php } ?>
            <?php if($util->getSectionPermission('media')){ ?>
              <li class="nav-label">Configurações</li>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-commenting"></i><span class="hide-menu">Mídias</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="dicionario-restricoes">Dicionario de restrições</a></li>
                  <li><a href="mensagens-padrao">Mensagens padrão</a></li>
                </ul>
              </li>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-pencil" aria-hidden="true"></i><span class="hide-menu">Cadastros</span></a>
                <ul aria-expanded="false" class="collapse">
	                <?php if($util->getSectionPermission('media')){ ?>
		              <li><a href="clientes" aria-expanded="false">Clientes</a></li>
			            <?php } ?>
									<li>
			              <a class="has-arrow  " href="#" aria-expanded="false">Usuários</a>
			              <ul aria-expanded="false" class="collapse">
			                <li><a href="supervisores">Supervisores</a></li>
			                <li><a href="agentes">Agentes</a></li>
			              </ul>
			            </li>
			            <li>
			              <a class="has-arrow  " href="#" aria-expanded="false">Grupos</a>
			              <ul aria-expanded="false" class="collapse">
			                <li><a href="grupos">Gerenciar grupos</a></li>
			              </ul>
			            </li>
									<?php if($util->getSectionPermission('conc')){ ?>
			              <li>
			                <a class="has-arrow  " href="#" aria-expanded="false">Casas</a>
			                <ul aria-expanded="false" class="collapse">
			                  <li><a href="casas">Gerenciar casas</a></li>
			                  <li><a href="novacasa">Nova casa</a></li>
			                </ul>
			              </li>
			            <?php } ?>
									<?php if($util->getSectionPermission('media')){ ?>
			              <li><a href="filas"aria-expanded="false">Filas</a></li>
			            <?php } ?>
		            </ul>
							</li>   
            <?php } ?>
            <?php



            /* ACESSO SUPERVISOR */
          }  else if($_SESSION['tipo'] == "supervisor"){
            ?>
            <li class="nav-devider"></li>
            <li class="nav-label">Inicio</li>
            <li>
              <a href="inicio" aria-expanded="false"><i class="fa fa-home"></i><span class="hide-menu">Inicio </span><span id="badgetRestrictinicio" class="badge badge-danger"></span></a>
            </li>
            <li class="nav-label">Facilidades</li>
            <li>
              <a href="chat" aria-expanded="false"><i class="fa fa-comments-o"></i><span class="hide-menu">CHAT</span></a>
            </li>
            <?php if($util->getSectionPermission('mural')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-square-o"></i><span class="hide-menu">Mural</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="mural">Ver mural</a></li>
                  <li><a href="cadastramensagem">Nova mensagem</a></li>
                </ul>
              </li>
            <?php }
            if($util->getSectionPermission('broad')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-paper-plane-o"></i><span class="hide-menu">Broadcast</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="broadcast">Nova broadcast</a></li>
                  <li><a href="broadcasts">Histórico de broadcast</a></li>
                </ul>
              </li>
            <?php }
            if($util->getSectionPermission('wiki')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-book"></i><span class="hide-menu">Wiki</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="wiki">Abrir wiki</a></li>
                </ul>
              </li>
            <?php } ?>
            <li>
              <a href="lembretes" aria-expanded="false"><i class="fa fa-sticky-note-o"></i><span class="hide-menu">Post-its</span></a>
            </li>
            <?php if($util->getSectionPermission('media')){ ?>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-file-text-o" aria-hidden="true"></i><span class="hide-menu">Relatórios</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="relatorioAtendimento">Relatório de atendimentos</a></li>
                  <!-- <li><a href="relatorioEventos">Relatório de agentes</a></li> -->
                  <li><a href="relatorioEventos">Relatório de eventos</a></li>
                  <!-- <li><a href="relatorioEventos">Relatório de filas</a></li>
                  <li><a href="relatorioEventos">Relatório de pausas</a></li> -->
                </ul>
              </li>
              <li>
                <a href="atendimentos" aria-expanded="false"><i class="fa fa-handshake-o" aria-hidden="true"></i><span class="hide-menu">Atendimentos</span></a>
              </li>
              <li class="nav-label">Configurações</li>
              <li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-commenting"></i><span class="hide-menu">Mídias</span></a>
                <ul aria-expanded="false" class="collapse">
                  <li><a href="dicionario-restricoes">Dicionario de restrições</a></li>
                  <li><a href="mensagens-padrao">Mensagens padrão</a></li>
                </ul>
              </li>
							<li>
                <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-pencil" aria-hidden="true"></i><span class="hide-menu">Cadastros</span></a>
                <ul aria-expanded="false" class="collapse">
	                <li><a href="clientes" aria-expanded="false">Clientes</a></li>
	              </ul>
							</li>
            <?php } ?>
            <?php



            /* ACESSO AGENTE */
          }  else if($_SESSION['tipo'] == "agente"){
            ?>
            <li class="nav-devider"></li>
            <li class="nav-label">Inicio</li>
            <li>
              <a href="inicio" aria-expanded="false"><i class="fa fa-home"></i><span class="hide-menu">Inicio</span></a>
            </li>
            <li class="nav-label">Facilidades</li>
            <?php if($_SESSION['chat'] != "nao" && $_SESSION['chat'] != ""){
              ?>
              <li>
                <a href="chat" aria-expanded="false"><i class="fa fa-comments-o"></i><span class="hide-menu">CHAT</span></a>
              </li>
              <?php
            } ?>
            <?php if($util->getSectionPermission('wiki')){ ?>
              <li>
                <a href="wiki" aria-expanded="false"><i class="fa fa-book"></i><span class="hide-menu">Wiki</span></a>
              </li>
            <?php } ?>
            <li>
              <a href="lembretes" aria-expanded="false"><i class="fa fa-sticky-note-o"></i><span class="hide-menu">Post-its</span></a>
            </li>
            <?php if($util->getSectionPermission('media')){ ?>
            <li>
              <a href="clientes" aria-expanded="false">
                <i class="fa fa-address-book-o" aria-hidden="true"></i><span class="hide-menu">Clientes</span>
              </a>
            </li>
            <li>
              <a href="meusAtendimentos" aria-expanded="false">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span class="hide-menu">Meus atendimentos</span>
              </a>
            </li>
          <?php } ?>
            <?php



            /* ACESSO GESTOR */
          } else if($_SESSION['tipo'] == "gestor"){
            ?>
            <li class="nav-devider"></li>
            <li class="nav-label">Inicio</li>
            <li>
              <a href="inicio" aria-expanded="false"><i class="fa fa-home"></i><span class="hide-menu">Inicio</span></a>
            </li>
            <li class="nav-label">Facilidades</li>
            <li>
              <a href="agenda" aria-expanded="false"><i class="fa fa-calendar-o"></i><span class="hide-menu">Agenda</span></a>
            </li>
            <li>
              <a href="minhacasa" aria-expanded="false"><i class="fa fa-building-o"></i><span class="hide-menu">Regra de negócio</span></a>
            </li>
            <?php
            if($_SESSION['chat'] != "nao" && $_SESSION['chat'] != ""){
              ?>
              <li>
                <a href="chat" aria-expanded="false"><i class="fa fa-comments-o"></i><span class="hide-menu">CHAT</span></a>
              </li>
              <?php
            } ?>
            <li>
              <a href="lembretes" aria-expanded="false"><i class="fa fa-sticky-note-o"></i><span class="hide-menu">Post-its</span></a>
            </li>
            <li class="nav-label">Cadastros</li>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-tags"></i><span class="hide-menu">Promoções</span></a>
              <ul aria-expanded="false" class="collapse">
                <li><a href="promocoes">Gerenciar promoções</a></li>
                <li><a href="novapromocao">Nova promoção</a></li>
              </ul>
            </li>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-car"></i><span class="hide-menu">Produtos</span></a>
              <ul aria-expanded="false" class="collapse">
                <li><a href="produtos">Gerenciar produtos</a></li>
                <li><a href="novoproduto">Novo produto</a></li>
              </ul>
            </li>
            <li>
              <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-wrench"></i><span class="hide-menu">Técnicos</span></a>
              <ul aria-expanded="false" class="collapse">
                <li><a href="tecnicos">Gerenciar técnicos</a></li>
                <li><a href="novotecnico">Novo técnico</a></li>
              </ul>
            </li>
            <?php


            /* ACESSO TÉCNICO */
          } else if($_SESSION['tipo'] == "tecnico"){ ?>
            <li class="nav-devider"></li>
            <li class="nav-label">Inicio</li>
            <li>
              <a href="inicio" aria-expanded="false"><i class="fa fa-home"></i><span class="hide-menu">Inicio</span></a>
            </li>
            <li class="nav-label">Facilidades</li>
            <li>
              <a href="agendaTecnico" aria-expanded="false"><i class="fa fa-calendar-o"></i><span class="hide-menu">Agenda</span></a>
            </li>
            <li>
              <a href="lembretes" aria-expanded="false"><i class="fa fa-sticky-note-o"></i><span class="hide-menu">Post-its</span></a>
            </li>
          <?php }


          /* CASO NÃO TENHA TIPO */
          else { echo "<script>window.location.assign(".$server.");</script>";}?>
        </ul>
      </nav>
      <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
  </div>
  <!-- End Left Sidebar  -->
