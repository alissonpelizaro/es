<!-- footer -->
<footer class="footer">
  <div class="row box-footer">
    <div class="col-md-9 pad-sm" style="padding-left: 30px;">
      © 2018 - Todos os direitos reservados. Desenvolvido por <a class="padrao" href="https://www.enterness.com">ENTERness</a>
    </div>
    <div class="col-md-3">
      <p class="text-right rmv-pad" style="padding-top: 3px"><i>Um sistema:</i> <img height="30px" src="assets/images/logominig.jpg"></p>
    </div>
  </div>
</footer>
<!-- End footer -->

<section class="spanLembrete" id="boxAlertaBell">
  <div class="card bg-bell p-20">
    <div class="media widget-ten">
      <div class="media-left meida media-middle">
        <span class="span-img-alerta">
          <img src="assets/images/alert/bell.gif">
        </span>
      </div>
      <div class="media-body media-text-right">
        <h2 class="color-white"><i id="mensagemAlerta">Lembrete de Post-it!</i></h2>
        <p class="m-b-0" id="conteudoAlertaBox"></p>
        <div class="pad-box-alert">
          <button type="button" class="btn btn-secondary btn-sm float-right" onclick="adiarAlertaBox()">Adiar 5 minutos</button>
          <button type="button" class="btn btn-danger btn-sm float-right" style="margin-right: 10px;" onclick="jogarForaAlertaBox()<?php if(isset($lembretes)){?>;reload()<?php }?>">Ok, jogar fora</button>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="spanLembrete spanBroadcast" id="boxAlertaBroadcast">
  <div class="card bg-bell p-20">
    <div class="media widget-ten">
      <div class="media-left meida media-middle">
        <span class="span-img-alerta">
          <img src="assets/images/alert/plane.gif">
        </span>
      </div>
      <div class="media-body media-text-right">
        <h2 class="text-light"><i id="mensagemAlertaBroadcast">Nova broadcast da Supervisão!</i></h2>
        <p class="m-b-0" id="conteudoAlertaBoxBroadcast">Conteudo</p>
        <div class="pad-box-alert">
          <button type="button" class="btn btn-secondary btn-sm float-right" onclick="confirmaBroadcast()">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
</section>
<audio id="audioSpan" style="display: none;" type="audio/mp3">
</audio>
