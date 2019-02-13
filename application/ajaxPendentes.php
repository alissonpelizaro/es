<?php
include '../coreExt.php';

if(isset($_POST["local"]) && $_POST["local"] == "lista"){
?>

 <ul>
          		    <li>
              		  <div class="drop-title">Atendimentos estacionados</div>
             			</li>
              		<li>
                		<div class="message-center">
                  		<!-- Message -->
                  		<?php
                  			$pendentesToolbar = retArrayPendentesToolbar($db);
                  			if(count($pendentesToolbar) == 0){
                    	?>
                    	<h6><i>Nenhum atendimento estacionados</i></h6>
                    	<?php
                  			}
                  			foreach ($pendentesToolbar as $l) {
                  				$alertaPendente = retArrayAlertaPendenteToolbar($db, $l["idAtendimento"]);
                    	?>
                    	<a href="media?hash=<?php echo $l["idAtendimento"]*253;?>&token=61sK4x6JwS5rRYg">
                      	<div class="pull-left" style="margin-right: 10px;"><img src="assets/icons/social/<?php echo $l["plataforma"];?>.png" height="40px"></div>
                      	<div class="mail-contnet" title="<?php if($l["nome"] != "") { echo $l["nome"]; } else { echo $l['remetente']; } ?>">
	                        	<h5>
	                        		<?php if($alertaPendente[0] > 0){ ?><b><?php } ?>
	                        		<?php if($l["nome"] != "") { echo $l["nome"]; } else { echo $l['remetente']; } ?>
	                        		<?php if($alertaPendente[0] > 0){ ?></b><?php } ?>
	                        	</h5>
	                        <span class="mail-desc"><?php echo $l['fila'] ?></span>
	                        <span class="time"><?php if($l['dataInicio'] != "1000-01-01 00:00:00"){ echo dataBdParaHtml($l['dataInicio']); }; ?></span>
                        </div>
                      </a>
                      <?php
                    		}
                    	?>
                  	</div>
                	</li>
              	</ul>
              	
              	<?php } ?>
              	