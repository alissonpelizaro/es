<?php
//Habilita sidechat nessa página
$chatSide = true;
?>
<section id="chatFrame">
  <div class="chat-frame-box">
    <div class="chat-frame-head cursor-pointer" id="headerFrameChat">
      <h4>MyOmni<i>chat</i></h4>
      <span id="hideChatFrame">
        <i class="fa fa-angle-double-right" aria-hidden="true"></i>
      </span>
    </div>
    <div class="chat-frame-search">
      <form class="enterness">
        <div class="form-group row">
          <div class="col-sm-1 col-form-label">
            <i class="fa fa-search" aria-hidden="true"></i>
          </div>
          <div class="col-sm-10">
            <input class="form-control-plaintext" type="text" id="inputSearchFrameChat" placeholder="Buscar alguém...">
          </div>
        </div>
      </form>
    </div>
    <div class="chat-frame-body" id="body-frame-chat">
    </div>
  </div>
</section>

<section class="media-enterness-chat">
  <div class="head-media-enterness">
    <h5 class="text-white">Atendimento online</h5>
    <span class="enterness-minimize-icon">
      &times;
    </span>
  </div>
  <div class="body-media-enterness">
    <section>
      <div class="messages-media-enterness" id="campo-mensagens-enterness">
        <div class="msg-entrante-enterness">
          <span>
            oi
          </span>
        </div>
        <div class="msg-sainte-enterness">
          <span>
            oi
          </span>
        </div>
        <div class="msg-sainte-enterness">
          <span>
            Texto grande texto grande Texto grande texto grande Texto grande texto grande
          </span>
        </div>
        <div class="msg-sainte-enterness">
          <span>
            Texto grande texto grande Texto grande texto grande Texto grande texto grande
          </span>
        </div>
        <div class="msg-entrante-enterness">
          <span>
            Texto grande texto grande Texto grande texto grande Texto grande texto grande
          </span>
        </div>
      </div>
      <div class="form-media-enterness">
        <form>
          <textarea class="inMsg-media-enternes" placeholder="Digite sua mensagem..."></textarea>
          <button type="button" class="btn-inMsg-enterness"></button>
        </form>
      </div>
    </section>
  </div>
</section>
