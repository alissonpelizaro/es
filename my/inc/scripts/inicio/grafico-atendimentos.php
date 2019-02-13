<script type="text/javascript">
	var ctx = document.getElementsByClassName("line-chart");
	
	var config = {
		type: 'line',
		data: {
			labels: ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", 
							 "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", 
							 "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", 
							 "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", 
							 "23:59"],
			datasets: [
				{
					label: "Enterness",
					data: [0],
					borderWidth: 2,
					borderColor: 'rgba(77, 166, 253, 0.85)',
					backgroundColor: 'transparent'
				},
				{
					label: "Whatsapp",
					data: [0],
					borderWidth: 2,
					borderColor: 'rgba(6, 204, 6, 0.85)',
					backgroundColor: 'transparent'
				},
				{
					label: "Telegram",
					data: [0],
					borderWidth: 2,
					borderColor: 'rgba(255, 100, 100, 0.85)',
					backgroundColor: 'transparent'
				},
				{
					label: "Messenger",
					data: [0],
					borderWidth: 2,
					borderColor: 'rgba(153, 102, 255, 0.85)',
					backgroundColor: 'transparent'
				},
				{
					label: "Skype",
					data: [0],
					borderWidth: 2,
					borderColor: 'rgba(255, 205, 86, 0.85)',
					backgroundColor: 'transparent'
				},
				{
					label: "Total",
					data: [0],
					borderWidth: 2,
					borderColor: 'rgba(201, 203, 207, 0.85)',
					backgroundColor: 'transparent'
				}
			]
		},
		options: {
		  responsive: true,
			title: {
			  display: true,
				fontSize: 16,
				text: "Atendimento efetuados"
			},
			labels: {
				fontStyle: "bold"
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			maintainAspectRatio: false
		}
	};
	var chartGraph = new Chart(ctx, config);
	
	var dadosGrafico = JSON.parse('<?php echo $dadosGrafico;?>');
	dadosGrafico.forEach(function(dadoGrafico) {
		var total = (parseInt(dadoGrafico.enterness) +
								 parseInt(dadoGrafico.whatsapp) +
								 parseInt(dadoGrafico.telegram) +
								 parseInt(dadoGrafico.messenger) +
								 parseInt(dadoGrafico.skype));

    var dados = [dadoGrafico.enterness, 
								 dadoGrafico.whatsapp, 
								 dadoGrafico.telegram, 
								 dadoGrafico.messenger, 
								 dadoGrafico.skype, 
								 total];
    atualizaGrafico(dados);
	});

	function atualizaGrafico(dados){
		var i = 0;
		config.data.datasets.forEach(function(dataset) {
			dataset.data.push(dados[i]);
			i++;
		});
		window.chartGraph.update();
	}

  var ultimaHora = 0;
	setInterval(function(){
	  var data = new Date();
	  var min = data.getMinutes();
		if(min == 0){
		  var hora = data.getHours();
			if(hora != ultimaHora){
				$.ajax({
				  method: "POST",
					url: "../application/rotasGraficos",
					data: {
						grafico: 'atendimento'
					},
					success: function(result){
					  var dadoGrafico = JSON.parse(result);
					  var total = (parseInt(dadoGrafico.enterness) +
												 parseInt(dadoGrafico.whatsapp) +
												 parseInt(dadoGrafico.telegram) +
												 parseInt(dadoGrafico.messenger) +
												 parseInt(dadoGrafico.skype));
					  
					  var dados = [dadoGrafico.enterness, 
												 dadoGrafico.whatsapp, 
												 dadoGrafico.telegram, 
												 dadoGrafico.messenger, 
												 dadoGrafico.skype, 
												 total];
						atualizaGrafico(dados);
					}
				});
				ultimaHora = hora;
			}
		}
	}, 55000);
</script>