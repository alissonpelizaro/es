<?php
include '../coreExt.php';

/*
* Arquivo que carrega lista de casas
* cadastradas para a tela inicial
*/

$logo = tratarString($_POST['hash']);

$sql = "SELECT `idCasa`, `nome`, `logo` FROM `casa` WHERE `status` = '1' AND `logo` = '$logo' ORDER BY `nome`";
$casas = $db->query($sql);
$casas = $casas->fetchAll();

foreach ($casas as $casa) {
  ?>
  <a href="detregra?crip=ssl&hash=<?php echo $casa['idCasa']*3237; ?>&token=none">
    <div class="bloco-casa">
      <img src="assets/casas/<?php if($casa['logo'] != ""){ echo $casa['logo']; } else { echo "default.png"; } ?>" alt="Nome da casa">
      <h4><?php echo cortaString($casa['nome'], 30) ?></h4>
    </div>
  </a>
  <?php
}
?>
