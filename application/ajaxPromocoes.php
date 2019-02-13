<?php
include '../coreExt.php';

//Carrega promoções da cancessionária
$sql = "SELECT
					*
				FROM
					`promocao`
				WHERE
					`casa` = '".$_POST["id"]."'";

$filtro = tratarString($_POST["filtro"]);
if ($_POST["filtro"] != "") {
	$sql .= " AND
							(`promocao` LIKE '%$filtro%'
						OR
							`veiculacao` LIKE '%$filtro%'
						OR
							`valor` LIKE '%$filtro%'
						OR
							`obs` LIKE '%$filtro%')";
}

$promocoes = $db->query($sql);
$promocoes = $promocoes->fetchAll();
if(count($promocoes) == 0){
	$promocoes = false;
}

if(!$promocoes && $filtro == ""){ ?>
<center style="width: 100%;">
	<h3 class="text-muted">
		<i>Essa casa não possui nenhuma promoção ativa.</i>
	</h3>
</center>
<?php
} else if(!$promocoes && $filtro != "") {
	?>
	<center style="width: 100%;">
		<h3 class="text-muted">
		<i>Nenhuma promoção encontrada.</i>
		</h3>
	</center>
	<?php 
} else {
	foreach ( $promocoes as $promo ) {?>
	<div class="col-3" style="margin-bottom: 30px;">
	  <div class="card-body">
	    <!-- Nav tabs -->
	    <ul class="nav nav-tabs" role="tablist">
	      <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#visao<?php echo $promo['idPromocao'];?>" role="tab"><span><i class="fa fa-usd" aria-hidden="true"></i></span></a> </li>
	      <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#det<?php echo $promo['idPromocao'];?>" role="tab"><span><i class="fa fa-info"></i></span></a> </li>
	    </ul>
	    <!-- Tab panes -->
	    <div class="tab-content tabcontent-border" style="border-left: 2px solid #045FB4">
	      <div class="tab-pane active" id="visao<?php echo $promo['idPromocao'];?>" role="tabpanel">
	        <div class="p-20">
	          <h4 class="text-info"><?php echo "R$ ".$promo['valor'];?></h4>
	          <span style="font-size: 14px;">Descrição: <?php echo $promo['promocao'];?></span>
	        </div>
	      </div>
	      <div class="tab-pane  p-20" id="det<?php echo $promo['idPromocao'];?>" role="tabpanel">
	        <div class="p-1">
	          <section style="font-size: 13px;">
	            <span><b>Veiculação:</b> <?php echo $promo['veiculacao'];?></span><br>
	            <span><b>Validade:</b> <?php if($promo['dataExpiracao'] != "2000-01-01 00:00:00"){ echo retSoDataDatePicker($promo['dataExpiracao']); } else { echo "Sem validade"; }?></span><br>
	            <span><b>Observação:</b> <?php if($promo['obs'] != ""){ echo $promo['obs']; } else { echo "Sem observação"; }?></span><br>
	          </section>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<?php	}
}?>
<script type="text/javascript">
$(document).ready(function(){
$(function () {
	  $('[data-toggle="popover"]').popover({
		  trigger: 'focus'
	  });
	});
	});
</script>

     