<?php
include '../application/controlPanel.php';
//Define nível de restrição da página
$allowUser = array('dev', 'coordenador');
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
        <h3 class="padrao">Configurações<i></i></h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item">Configurações</li>
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
              <h4 class="card-title text-center">Configurações <i>EasyChannel</i>:</h4>
              <div class="row">
                <div class="col-lg-12">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#generals" aria-selected="true" role="tab">Geral</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" id="whatsAppTab" href="#midias" role="tab">Mídias sociais</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab"  href="#integrations" role="tab">Integrações</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" id="clientesTab" href="#clientes" role="tab">Clientes</a></li>
                    <?php if($_SESSION['tipo'] == 'dev'){
                      ?>
                      <li class="nav-item"><a class="nav-link" data-toggle="tab" id="devTab" href="#dev" role="tab">Avançado</a></li>
                      <?php
                    } ?>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" id="monitorTab" href="#monitor" role="tab">Monitor</a></li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <!--second tab-->
                    <div class="tab-pane active show" id="generals" role="tabpanel">
                      <div class="card-body">
                        <div class="jumbotron">
                          <h1 class="display-6 padrao">Geral</h1>
                          <i class="lead">Configure a triagem dos atendimentos e defina uma mensagem da URA</i>
                          <hr class="my-4">
                          <form class="enterness" enctype="multipart/form-data" action="controlPanel" method="post">
                            <input type="hidden" name="tab" value="generals">
                            <div class="row">
                              <div class="col-lg-6 offset-lg-3">
                                <div class="row m-t-20" style="border-left: 2px solid #1976C9;">
                                  <p class="m-0 m-t-10 m-l-20 padrao">Configurações do DAM</p>
                                  <div class="col-12">
                                    <div class="form-group">
                                      <label class="col-sm-12 control-label junta">Priorizar os atendimentos novos para:</label>
                                      <div class="col-sm-12">
                                        <select class="form-control" name="prior">
                                          <option value="0"<?php echo $conf->prioridade == 0 ? " selected" : "" ?>>Qualquer agente disponivel na fila (recomendado)</option>
                                          <option value="1"<?php echo $conf->prioridade == 1 ? " selected" : "" ?>>O agente a mais tempo sem atender</option>
                                          <option value="2"<?php echo $conf->prioridade == 2 ? " selected" : "" ?>>O agente com menos atendimentos no dia</option>
                                          <option value="3"<?php echo $conf->prioridade == 3 ? " selected" : "" ?>>O agente com mais atendimentos no dia</option>
                                          <option value="4"<?php echo $conf->prioridade == 4 ? " selected" : "" ?>>O agente com menos atendimentos ativos</option>
                                          <option value="5"<?php echo $conf->prioridade == 5 ? " selected" : "" ?>>O agente com mais atendimentos ativos</option>
                                          <option value="6"<?php echo $conf->prioridade == 6 ? " selected" : "" ?>>O agente que efetuou atendimentos anteriores ao cliente</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12">
                                    <div class="form-group">
                                      <label class="col-sm-12 control-label junta">Auto-transferir os atendimentos:</label>
                                      <div class="col-sm-12">
                                        <select class="form-control" name="transf">
                                          <option value="0"<?php echo $conf->transf == 0 ? " selected" : "" ?>>Nunca auto-transferir</option>
                                          <option value="1"<?php echo $conf->transf == 1 ? " selected" : "" ?>>Caso o agente saia do sistema e não retorne dentro de 1 minuto</option>
                                          <option value="2"<?php echo $conf->transf == 2 ? " selected" : "" ?>>Caso o agente fique mais de 15 min sem responder o ciente</option>
                                          <option value="3"<?php echo $conf->transf == 3 ? " selected" : "" ?>>Caso o agente fique mais de 30 min sem responder o ciente</option>
                                          <option value="4"<?php echo $conf->transf == 4 ? " selected" : "" ?>>Caso o agente fique mais de 1 hora sem responder o cliente</option>
                                          <option value="5"<?php echo $conf->transf == 5 ? " selected" : "" ?>>Caso o agente fique mais de 2 horas sem responder o ciente</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12">
                                    <div class="col form-group">
                                      <label class="control-label junta m-r-15 clear">Auto-transbordar os atendimentos novos?</label>
                                      <input value="1"
                                      type="checkbox"
                                      id="inTransbordo"
                                      name="checkTransb"
                                      <?php echo $conf->transb ? "checked" : ""; ?>>
                                      <span class="clear-both <?php echo $conf->transb ? "" : "hide"; ?>" id="spanTransbordo">
                                        <p class="m-0 p-0">
                                          <small class=" text-default pull-left m-t-10 m-r-10">Minutos:</small>
                                          <input type="text" class="form-control num-min w-20" name="transb" value="<?php echo $conf->transb ? "$conf->transb" : ""; ?>" placeholder="0">
                                        </p>
                                        <small><i>Você pode definir um tempo (minutos) para que o sistema transborde um atendimento<br>
                                          novo, caso o agente não tenha dado inicio na conversa.</i>
                                        </small>
                                      </span>
                                    </div>
                                    <hr>
                                  </div>
                                  <div class="col-12">
                                    <p class="m-0 m-t-10 m-l-20 padrao">Configurações de URA</p>
                                  </div>
                                  <div class="col-12">
                                    <div class="form-group">
                                      <label class="col-sm-12 control-label junta">Mostrar na URA:</label>
                                      <div class="col-sm-12">
                                        <select class="form-control" name="exibirFilas">
                                          <option value="disp"<?php echo $conf->exibirFilas == 'disp' ? " selected" : "" ?>>Apenas as filas que possuem agentes disponíveis para atender</option>
                                          <option value="todas"<?php echo $conf->exibirFilas == 'todas' ? " selected" : "" ?>>Todas as filas, e acionar o cliente quando houver alguém para atender</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12">
                                    <div class="col form-group">
                                      <label class="control-label junta m-r-15 clear">Limitar horário de funcionamento da URA?</label>
                                      <input value="1"
                                      type="checkbox"
                                      id="inLimite"
                                      name="checkLimit"
                                      <?php echo $limiteConf->status ? "checked" : ""; ?>>
                                      <span class="clear-both <?php echo $limiteConf->status ? "" : "hide"; ?>" id="spanLimite">
                                        <div class="row">
                                          <div class="m-l-15" style="width: 60px;">
                                            <div class="form-group">
                                              <label class=" control-label junta">Inicio:</label>
                                                <input type="text" class="form-control hora" id="horaIniLimite" name="horaIni" value="<?php echo $limiteConf->inicio ?>" placeholder="00:00">
                                            </div>
                                          </div>
                                          <div class="m-l-5" style="width: 60px;">
                                            <div class="form-group">
                                              <label class="control-label junta">Fim:</label>
                                              <input type="text" class="form-control hora" id="horaFimLimite" name="horaFim" value="<?php echo $limiteConf->fim ?>" placeholder="23:59">
                                            </div>
                                          </div>
                                          <div class="col">
                                            <div class="form-group">
                                              <label class="col-sm-12 control-label junta">Resposta para mensagens enviadas fora do horário:</label>
                                              <div class="col-sm-12">
                                                <input type="text" class="form-control" id="respostaLimite" maxlength="250" name="limiteResp" value="<?php echo $limiteConf->resposta ?>">
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </span>
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label class="col control-label junta">Mensagem de boas-vindas:</label>
                                      <div class="col-sm">
                                        <textarea class="form-control" id="boasvindasTextarea" name="boas-vindas" maxlength="250" style="height: 100px" placeholder="Olá, seja bem-vindo ao canal de atendimento Enterness."><?php echo $conf->saudacao ?></textarea>
                                        <small><i>Coloque entre * para deixar a mensagem em <b>negrito</b> ao ser enviada para o cliente</i></small>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label class="col control-label junta">Pré-visualização da URA:</label>
                                      <div class="col-sm">
                                        <div class="body-preview padrao" id="boasvindasPreview">
                                          <?php echo $conf->saudacao != "" ? $conf->saudacao."<br><br>" : "" ?>
                                          Sobre qual assunto deseja falar?
                                          <br>Responda com o <b>número</b> da opção desejada:
                                          <br><br>
                                          <b>1</b> - Fila um<br>
                                          <b>2</b> - Fila dois
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-12">
                                    <center>
                                      <button type="submit" class="btn btn-info">Salvar</button>
                                    </center>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="midias" role="tabpanel">
                      <div class="card-body">
                        <div class="jumbotron">
                          <h1 class="display-6 padrao">Midias sociais</h1>
                          <i class="lead">Realize a checagem da comunicação com as mídias sociais que o EasyChannel tem integração</i>
                          <hr class="my-4">
                          <form class="enterness" enctype="multipart/form-data" action="controlPanel" method="post">
                            <input type="hidden" name="tab" value="midias">
                            <div class="row">
                              <div class="col-lg-6 offset-lg-3">
                                <div class="m-t-20" style="border-left: 2px solid rgb(82, 190, 102);">
                                  <p class="m-0 m-t-10 m-l-20 text-verde">WhatsApp</p>
                                  <div class="row">
                                    <div class="col-6 hide" id="whatsAppBody">
                                      <div class="form-group">
                                        <label class="col control-label junta">Status do WhatsApp:</label>
                                        <div class="col-sm">
                                          <p class="h3 text-verde hide" id="messageWhatsAppOk">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                            <br>
                                            Sincronismo Ok
                                          </p>
                                          <p class="h3 text-danger hide" id="messageWhatsAppErro">
                                            <i class="fa fa-times-circle" aria-hidden="true"></i>
                                            <br>
                                            Não sincronizado
                                          </p>
                                          <p class="h3 text-danger hide" id="messageWhatsAppApiFailure">
                                            <i class="fa fa-times-circle" aria-hidden="true"></i>
                                            <br>
                                            API inoperante
                                          </p>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 text-center" id="loadingWhatsAppBody">
                                      <img src="assets/images/loading.gif" alt="Carregando...">
                                      <p><i>Checando integração com WhatsApp...</i></p>
                                    </div>
                                    <div class="col-6 hide" id="resincronizarWhatsAppBody">
                                      <div class="form-group">
                                        <label class="col control-label junta">Re-sincronizar:</label>
                                        <div class="col-sm">
                                          <div class="" id="divQRCodeWhatsAppLoading">
                                            <img src="assets/images/loading_round.gif" height="150px" alt="Carregando QR Code...">
                                          </div>
                                          <p class="text-danger italic hide" id="QrCodeErroMessage">Erro ao capturar o QRCode.</p>
                                          <div class="body-preview" id="divQRCodeWhatsApp">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-6 offset-lg-3">
                                <div class="m-t-20" style="border-left: 2px solid #1976C9;">
                                  <p class="m-0 m-t-10 m-l-20 padrao">Enterness</p>
                                  <div class="row">
                                    <div class="col-6">
                                      <div class="form-group">
                                        <label class="col control-label junta">Status do Orquestrador Enterness:</label>
                                        <div class="col-sm">
                                          <p class="h3 text-verde">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                            <br>
                                            Sincronismo Ok
                                          </p>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="clientes" role="tabpanel">
                      <div class="card-body">
                        <div class="jumbotron">
                          <h1 class="display-6 padrao">Clientes</h1>
                          <i class="lead">Importe cadastro de clientes de outra base de dados</i>
                          <hr class="my-4">
                          <form class="enterness" action="controlPanel" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="tab" value="clientes">
                            <div class="row">
                              <div class="col-lg-6 offset-lg-3">
                                <div class="row m-t-20" style="border-left: 2px solid #1976C9;">
                                  <p class="m-0 m-t-10 m-l-20 padrao">Configurações para os clientes</p>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label class="control-label junta col-sm-12">Habilitar ativos em novos clientes</label>
                                      <div class="col-sm-12">
                                        <input value="<?php echo $ativo;?>"
                                        type="checkbox"
                                        class="form-control"
                                        name="ativo"
                                        id="inAtivo"
                                        placeholder="Habilitar ativos"
                                        <?php if($ativo){?>checked="checked"<?php }?>/>
                                      </div>
                                    </div>
                                  </div>
                                  <p class="m-0 m-t-10 m-l-20 padrao">Importação de clientes</p>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label class="control-label junta col-sm-12">Manter os dados do EasyChannel</label>
                                      <div class="col-sm-12">
                                        <input value="1"
                                        type="checkbox"
                                        class="form-control"
                                        name="manter"
                                        id="inManter"
                                        placeholder="Manter dados"/>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-sm-12">
                                    <div class="form-group">
                                      <label class="col control-label junta">Importar dados de clientes:</label>
                                      <div class="col-sm-12">
                                        <input type="file" name="fileClientes"/>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-12 center">
                                    <button type="submit" class="btn btn-info">Salvar</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <?php if($_SESSION['tipo'] == 'dev'){ ?>
                      <div class="tab-pane" id="dev" role="tabpanel">
                        <div class="card-body">
                          <div class="jumbotron">
                            <h1 class="display-6 padrao">Avançado</h1>
                            <i class="lead">Configure lincenças do sistema e migre entre um setor e outro</i>
                            <hr class="my-4">
                            <div class="row">
                              <div class="col-md-6 offset-md-3">
                                <form class="enterness" action="../application/setup" method="post">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="junta">Licenças de coordenadores</label>
                                        <input type="number" required name="mtdr" value="<?php echo $lic['mtdr'] ?>" min="0" class="form-control">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="junta">Licenças de administradores</label>
                                        <input type="number" required name="mtmr" value="<?php echo $lic['mtmr'] ?>" min="0" class="form-control">
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="junta">Licenças de supervisores</label>
                                        <input type="number" required name="mtvr" value="<?php echo $lic['mtvr'] ?>" min="0" class="form-control">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="junta">Licenças de agentes</label>
                                        <input type="number" required name="mtnt" value="<?php echo $lic['mtnt'] ?>" min="0" class="form-control">
                                      </div>
                                    </div>
                                  </div>
                                  <hr>
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="junta">Dias de varredura do CronTab</label>
                                        <input type="number" required name="cront" value="<?php echo $lic['diasCrontab'] ?>" min="0" class="form-control">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="junta">Minutos de timeout da sessão</label>
                                        <input type="number" required name="timeout" value="<?php echo $lic['timeoutSessao'] ?>" min="0" class="form-control">
                                      </div>
                                    </div>
                                  </div>
                                  <hr>
                                  <div class="row">
                                    <div class="col-12">
                                      <center>
                                        <button type="submit" class="btn btn-info">Salvar mudanças</button>
                                      </center>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <form class="" action="setup" method="post">
                              <div class="row">
                                <div class="col-6 offset-3">
                                  <hr>
                                  <div class="form-group">
                                    <label class="col-sm-12 control-label junta">Setor</label>
                                    <div class="col-sm-12">
                                      <select class="form-control" name="setor">
                                        <?php foreach ($setores as $setor) {
                                          ?>
                                          <option value="<?php echo $setor['idSetor'] ?>" <?php if($setorUser == $setor['idSetor']){ echo 'selected'; } ?>><?php echo $setor['nome'] ?></option>
                                          <?php
                                        } ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-6 offset-3 text-center">
                                  <button type="submit" class="btn btn-sm btn-info btn-outline" name="button">Trocar setor</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                    <div class="tab-pane" id="integrations" role="tabpanel">
                      <div class="card-body">
                        <div class="jumbotron">
                          <h1 class="display-6 padrao">Integrações</h1>
                          <i class="lead">Ajuste integrações automáticas do EasyChannel com  outros CRM</i>
                          <hr class="my-4">
                          <div class="row">
                            <div class="col-3">
                              <div class="list-group" id="myList" role="tablist">
                                <a class="list-group-item list-group-item-action active" data-toggle="list" href="#tab-followwize" role="tab">FolloWize</a>
                                <a class="list-group-item list-group-item-action" data-toggle="list" href="#tab-enterness" role="tab">Enterness</a>
                              </div>
                            </div>
                            <div class="col-9">
                              <div class="tab-content">
                                <div class="tab-pane active p-l-20" id="tab-followwize" role="tabpanel" style="border-left: 2px solid rgb(63, 189, 72)">
                                  <h3 class="padrao">Integração com FolloWize</h3>
                                  <?php $followiseConfig = json_decode($conf->followise) ?>
                                  <form class="enterness" action="controlPanel" method="post">
                                    <input type="hidden" name="tab" value="integration">
                                    <input type="hidden" name="where" value="followise">
                                    <div class="row">
                                      <div class="col-12">
                                        <label class="control-label junta m-r-15 clear">Aitvar integração com o Followize?</label>
                                        <input value="1"
                                        type="checkbox"
                                        id="inCheckFollowize"
                                        name="checkFollowise"
                                        <?php echo $followiseConfig->status ? "checked" : ""; ?>>
                                      </div>
                                    </div>
                                    <br>
                                    <span class="<?php echo $followiseConfig->status ? "" : "hide"; ?>" id="spanFollowise">
                                      <div class="row">
                                        <div class="col-6">
                                          <div class="form-group">
                                            <label class="junta">Endereço da API:</label>
                                            <input type="text" required readonly name="api" value="<?php echo $followiseConfig->api ?>" class="form-control">
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-6">
                                          <div class="form-group">
                                            <label class="junta">Chave do cliente (client key):</label>
                                            <input type="text" required name="client" value="<?php echo $followiseConfig->tokenClient ?>" class="form-control">
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-6">
                                          <div class="form-group">
                                            <label class="junta">Chave da equipe (team key):</label>
                                            <input type="text" required name="team" value="<?php echo $followiseConfig->tokenTeam ?>" class="form-control">
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-6">
                                          <div class="form-group">
                                            <label class="junta">Tipo de conexão:</label>
                                            <select class="form-control" name="tipo">
                                              <option value="API v2.0" <?php echo $followiseConfig->tipo == 'API v2.0' ? " selected" : ""; ?>>API v2.0</option>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                    </span>
                                    <div class="row">
                                      <div class="col-6 text-center">
                                        <button class="btn btn-info" type="submit" name="button">Salvar</button>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                                <div class="tab-pane  p-l-20" id="tab-enterness" role="tabpanel" style="border-left: 2px solid #1976C9;">
                                  <h3 class="padrao">Integração com Enterness<sub><i>orchestrator</i></sub></h3>
                                  <?php $enternessConfig = json_decode($conf->enterness) ?>
                                  <form class="enterness" action="controlPanel" method="post">
                                    <input type="hidden" name="tab" value="integration">
                                    <input type="hidden" name="where" value="enterness">
                                    <div class="row">
                                      <div class="col-12">
                                        <label class="control-label junta m-r-15 clear">Ativar integração com a Enterness?</label>
                                        <input value="1" disabled
                                        type="checkbox"
                                        id="inCheckEnterness"
                                        name="checkEnterness"
                                        <?php echo $enternessConfig->status ? "checked" : ""; ?>>
                                      </div>
                                    </div>
                                    <br>
                                    <span class="<?php echo $enternessConfig->status ? "" : "hide"; ?>" id="spanFollowise">
                                      <div class="row">
                                        <div class="col-6">
                                          <div class="form-group">
                                            <label class="junta">Endereço da API:</label>
                                            <input type="text" readonly readonly name="api" value="<?php echo $enternessConfig->api ?>" class="form-control">
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-6">
                                          <div class="form-group">
                                            <label class="junta">Token:</label>
                                            <input type="text" readonly name="client" value="<?php echo $enternessConfig->token ?>" class="form-control">
                                          </div>
                                        </div>
                                      </div>
                                    </span>
                                    <div class="row">
                                      <div class="col-6 text-center">
                                        <button class="btn btn-info" type="submit" name="button">Salvar</button>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="monitor" role="tabpanel">
                      <div class="jumbotron">
                        <h1 class="display-6 padrao">Monitoração</h1>
                        <i class="lead">Monitore em tempo real o desempenho do servidor EasyChannel e cheque versões do sistema</i>
                        <hr class="my-4">
                        <div id="monitorBody"></div>
                      </div>
                    </div>
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
  <!-- End Wrapper -->
  <?php include 'inc/scripts.php'; ?>
  <script src="assets/js/lib/qrcodejs/qrcode.js"></script>
  <script type="text/javascript">

  $(document).ready(function(){

    $('.hora').mask('00:00');


    $("#inTransbordo").click(function(){
      if($(this).is(':checked')){
        $("#spanTransbordo").fadeIn();
      } else {
        $("#spanTransbordo").hide();
      }
    });

    $("#inLimite").click(function(){
      if($(this).is(':checked')){
        $("#spanLimite").fadeIn();

        $("#horaIniLimite").prop('required',true);
        $("#horaFimLimite").prop('required',true);
        $("#respostaLimite").prop('required',true);


      } else {
        $("#spanLimite").hide();

        $("#horaIniLimite").val('');
        $("#horaFimLimite").val('');

        $("#horaIniLimite").prop('required',false);
        $("#horaFimLimite").prop('required',false);
        $("#respostaLimite").prop('required',false);
      }
    });

    $("#inCheckFollowize").click(function(){
      if($(this).is(':checked')){
        $("#spanFollowise").fadeIn();
      } else {
        $("#spanFollowise").hide();
      }
    });

    $("#inCheckEnterness").click(function(){
      setToastDanger('Ops...', 'O orquestrador da Enterness não pode ser desligado no momento.');
    });

    $(function(){
      $.switcher('#inAtivo');
      $.switcher('#inManter');
      $.switcher('#inTransbordo');
      $.switcher('#inLimite');
      $.switcher('#inCheckFollowize');
      $.switcher('#inCheckEnterness');
      $('.num-min').mask('0000');
    });

    $("#monitorTab").click(function (){
      var contentMonitor = '<iframe src="../thirdy/probe/p.php" style="width: 100%; height: 500px;"></iframe>';

      $("#monitorBody").html(contentMonitor);
    });

    $("#boasvindasTextarea").keyup(function(){
      var content = $(this).val();
      if(content != ""){
        content = content + "<br><br>";
      }
      content = content + "Sobre qual assunto deseja falar?<br>Responda com o <b>número</b> da opção desejada:<br><br><b>1</b> - Fila um<br><b>2</b> - Fila dois";
      $("#boasvindasPreview").html(content);
    });
  });

  $('#whatsAppTab').click(function(){
    startWhatsAppTest();
  });

  function startWhatsAppTest(){

    resetBodyWhatsApp();
    $("#loadingWhatsAppBody").fadeIn(1000);

    var whatsAppSettings = {
      "url": "../application/whatsAppControl.php"
    }

    var client_id = "<?php echo $config->getUserApi() ?>";

    $.ajax(whatsAppSettings).done(function (response) {
      response = JSON.parse(response);

      $("#whatsAppBody").show();
      if(response['status'] == 0){
        //Api inoperante
        $("#loadingWhatsAppBody").hide();
        $("#messageWhatsAppApiFailure").fadeIn(600);

      } else if(response['status'] == 1){
        //Sincronismo ok
        $("#loadingWhatsAppBody").hide();
        $("#messageWhatsAppOk").fadeIn(600);

      } else if(response['status'] == 2){
        //Não sincronizado
        $("#loadingWhatsAppBody").hide();
        $("#messageWhatsAppErro").fadeIn(600);
        $("#resincronizarWhatsAppBody").fadeIn(600);

        getQRCodeWhatsApp();

        //setTimeOutQrCode();

      } else if(response['status'] == 3){
        //Cliente inexistente
        $("#loadingWhatsAppBody").hide();
        $("#messageWhatsAppApiFailure").fadeIn(600);

      } else if(response['status'] == 4){
        //Impossivel capturar o QR code


      } else {
        //Falha na requisição dos testes
        $("#loadingWhatsAppBody").hide();
        $("#messageWhatsAppApiFailure").fadeIn(600);

      }

      //console.log(response);

    });

  }

  function getQRCodeWhatsApp(){

    $.ajax({
      type: 'POST',
      url: '../application/whatsAppControl',
      data: {
        qrRequest: 52
      },
      success: function(result){

        result = JSON.parse(result);

        if(result['target']){
          $("#divQRCodeWhatsAppLoading").hide();
          $("#QrCodeErroMessage").hide();
          $("#divQRCodeWhatsApp").html();
          $("#divQRCodeWhatsApp").html('<img src="assets/medias/whatsApp/'+result['target']+'">');
          setTimeout(getQRCodeWhatsApp, 3000);
        } else {
          $("#divQRCodeWhatsApp").html();
          startWhatsAppTest();
          //$("#QrCodeErroMessage").fadeIn();
        }

      }
    });


  }

  function resetBodyWhatsApp(){
    $("#whatsAppBody").hide();
    $("#loadingWhatsAppBody").hide();
    $("#messageWhatsAppOk").hide();
    $("#messageWhatsAppErro").hide();
    $("#messageWhatsAppApiFailure").hide();
    $("#resincronizarWhatsAppBody").hide();
    $("#QrCodeErroMessage").hide();
  }

  <?php if(isset($saved) && $saved){
    ?>
    swal("Feito!", "As configurações foram atualizadas!", "success");
    <?php
  } ?>


  <?php if(isset($_GET['setup']) && $_GET['setup'] == 'success'){
    ?>
    swal("Feito!", "As configurações foram atualizadas!", "success");
    <?php
  } ?>
  <?php if(isset($_GET['setor']) && $_GET['setor'] == 'atualizado'){
    ?>
    swal("Tudo certo!", "Você trocou de setor!", "success");
    <?php
  } ?>

  <?php if(isset($csv)){?>
    <?php if ($csv) {?>
      <?php if ($statusImportação) {?>
        swal("Feito!", "Todos os dados foram importados!", "success");
        <?php }else{?>
          swal("Opa!", "Não foi possivel importados todos os dados", "error");
          <?php }?>
          <?php }else{?>
            swal("Opa!", "O arquivo escolhido não é válido", "error");
            <?php }?>
            <?php }?>

            </script>
          </body>

          </html>
