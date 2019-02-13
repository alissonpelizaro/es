<?php
include '../../../coreExt.php';
include '../../Api.php';



$api = new Api;
$array = $api->getQueues();

print_r(json_encode($array));

?>
