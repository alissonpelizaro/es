<?php
include '../coreExt.php';
require_once 'Sincronismo.php';

$teste = new Sincronismo;
echo "URL: ".$teste->access->getTelegramUrlCheck()."<hr>";
echo "<pre>";
$teste->telegramSinc();
echo "</pre>";
//4195015630
//actiotechb1
?>
