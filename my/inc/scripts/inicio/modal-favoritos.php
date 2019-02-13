<script>
// Get the modal
var modal = document.getElementById('modalFavoritos');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// When the user clicks the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

var lembretesFavo = <?php echo $favoritos['lembrete'];?>;
<?php if ($_SESSION['tipo'] != 'tecnico' && $_SESSION['tipo'] != 'gestor') {?>
	var clientesFavo = <?php echo $favoritos['clientes'];?>;
<?php }
if ($_SESSION['tipo'] == 'dev' ||
		$_SESSION['tipo'] == 'coordenador' ||
		$_SESSION['tipo'] == 'administrador' ||
		$_SESSION['tipo'] == 'supervisor') {?>
	var graficoAtendimentosFavo = <?php echo $favoritos['graficoAtendimentos'];?>;
	var atendimentosFavo = <?php echo $favoritos['atendimento'];?>;
<?php }
if ($_SESSION['tipo'] == 'agente') {?>
	var meusAtendimentosFavo = <?php echo $favoritos['meusAtendimentos'];?>;
<?php }?>

function favorito(id){

	var favorito = document.getElementById(id).src;
	var val = 1;

	if(favorito == "http://<?php echo $config->server; ?>/my/assets/icons/star1.png"){
	    val = 0;
	}

  switch(id) {
    case "lembreteFavorito":
      if(val == 0){
	  		lembretesFavo = 0;
	  		document.getElementById("lembreteFavorito").src = "assets/icons/star0.png";
      }else{
	  		lembretesFavo = 1;
	  		document.getElementById("lembreteFavorito").src = "assets/icons/star1.png";
      }
      break;

    case "atendimentoFavorito":
			if(val == 0){
			    atendimentosFavo = 0;
			    document.getElementById("atendimentoFavorito").src = "assets/icons/star0.png";
			}else{
			    atendimentosFavo = 1;
			    document.getElementById("atendimentoFavorito").src = "assets/icons/star1.png";
			}
      break;

    case "clientesFavorito":
			if(val == 0){
			    clientesFavo = 0;
			    document.getElementById("clientesFavorito").src = "assets/icons/star0.png";
			}else{
			    clientesFavo = 1;
			    document.getElementById("clientesFavorito").src = "assets/icons/star1.png";
			}
      break;

    case "meusAtendimentosFavorito":
			if(val == 0){
			    meusAtendimentosFavo = 0;
			    document.getElementById("meusAtendimentosFavorito").src = "assets/icons/star0.png";
			}else{
			    meusAtendimentosFavo = 1;
			    document.getElementById("meusAtendimentosFavorito").src = "assets/icons/star1.png";
			}
			break;

    case "graficoAtendimentosFavorito":
			if(val == 0){
			    graficoAtendimentosFavo = 0;
			    document.getElementById("graficoAtendimentosFavorito").src = "assets/icons/star0.png";
			}else{
			    graficoAtendimentosFavo = 1;
			    document.getElementById("graficoAtendimentosFavorito").src = "assets/icons/star1.png";
			}
			break;
  }
}

function salvaFavoritos(){
	var dados = new Array(
		<?php if ($_SESSION['tipo'] != 'tecnico' && $_SESSION['tipo'] != 'gestor') {?>
		new Array('clientes', clientesFavo),
		<?php }
		if ($_SESSION['tipo'] == 'dev' ||
			$_SESSION['tipo'] == 'coordenador' ||
			$_SESSION['tipo'] == 'administrador' ||
			$_SESSION['tipo'] == 'supervisor') {?>
		new Array('atendimento', atendimentosFavo),
		new Array('graficoAtendimentos', graficoAtendimentosFavo),
		<?php }
		if ($_SESSION['tipo'] == 'agente') {?>
		new Array('meusAtendimentos', meusAtendimentosFavo),
		<?php }?>
		new Array('lembrete', lembretesFavo));
	
	$.ajax({
		method: "POST",
		url: "../application/ajaxFavorito.php",
		data: {
			page: 'inicio',
			dados: dados
		},
		success: function(result){
			if(result == "true"){
				reload();
			}
		}
	});
}
</script>