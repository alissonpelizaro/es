<script type="text/javascript">

var teclasAtalhos = new Array(<?php
			$trava = true;
			foreach ($teclasAtalhos as $dado) {
				if($trava){
					echo "'".$dado."'";
					$trava = false;
				} else {
					echo ", '" . $dado."'";
				}
			}
		?>);
var pressedAlt = false; //variável de controle 
$(document).keyup(function (e) {  //O evento Kyeup é acionado quando as teclas são soltas
	if(e.which == 18) pressedAlt=false; //Quando qualuer tecla for solta é preciso informar que Crtl não está pressionada
});
$(document).keydown(function (e) { //Quando uma tecla é pressionada
	if(e.which == 18) pressedAlt = true; //Informando que Crtl está acionado
	
	if(e.keyCode == 49 && pressedAlt == true) { //Reconhecendo tecla 1 
		$("#textareaChat").val($("#textareaChat").val() + teclasAtalhos[1]);
		setToastInfo('MyOmni Chat', 'Atalho reconhecido!');
		pressedAlt = false;
	}
	if(e.keyCode == 50 && pressedAlt == true) { //Reconhecendo tecla 2
		$("#textareaChat").val($("#textareaChat").val() + teclasAtalhos[2]);
		setToastInfo('MyOmni Chat', 'Atalho reconhecido!');
		pressedAlt = false;
	}
	if(e.keyCode == 51 && pressedAlt == true) { //Reconhecendo tecla 3
		$("#textareaChat").val($("#textareaChat").val() + teclasAtalhos[3]);
		setToastInfo('MyOmni Chat', 'Atalho reconhecido!');
		pressedAlt = false;
	}
	if(e.keyCode == 52 && pressedAlt == true) { //Reconhecendo tecla 4
		$("#textareaChat").val($("#textareaChat").val() + teclasAtalhos[4]);
		setToastInfo('MyOmni Chat', 'Atalho reconhecido!');
		pressedAlt = false;
	}
	if(e.keyCode == 53 && pressedAlt == true) { //Reconhecendo tecla 5
		$("#textareaChat").val($("#textareaChat").val() + teclasAtalhos[5]);
		setToastInfo('MyOmni Chat', 'Atalho reconhecido!');
		pressedAlt = false;
	}
	if(e.keyCode == 54 && pressedAlt == true) { //Reconhecendo tecla 6
		$("#textareaChat").val($("#textareaChat").val() + teclasAtalhos[6]);
		setToastInfo('MyOmni Chat', 'Atalho reconhecido!');
		pressedAlt = false;
	}
	if(e.keyCode == 55 && pressedAlt == true) { //Reconhecendo tecla 7
		$("#textareaChat").val($("#textareaChat").val() + teclasAtalhos[7]);
		setToastInfo('MyOmni Chat', 'Atalho reconhecido!');
		pressedAlt = false;
	}
	if(e.keyCode == 56 && pressedAlt == true) { //Reconhecendo tecla 8
		$("#textareaChat").val($("#textareaChat").val() + teclasAtalhos[8]);
		setToastInfo('MyOmni Chat', 'Atalho reconhecido!');
		pressedAlt = false;
	}
	if(e.keyCode == 57 && pressedAlt == true) { //Reconhecendo tecla 9
		$("#textareaChat").val($("#textareaChat").val() + teclasAtalhos[9]);
		setToastInfo('MyOmni Chat', 'Atalho reconhecido!');
		pressedAlt = false;
	}
	if(e.keyCode == 48 && pressedAlt == true) { //Reconhecendo tecla 0
		$("#textareaChat").val($("#textareaChat").val() + teclasAtalhos[0]);
		setToastInfo('MyOmni Chat', 'Atalho reconhecido!');
		pressedAlt = false;
	}
});

</script>