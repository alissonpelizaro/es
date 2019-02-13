<?php
include '../core.php';

$msg = tratarString($_POST['msg']);
$hash = tratarString($_POST['hash']);
$data = date('Y-m-d H:i:s');

$hash = explode('-', $hash);
$id = $_SESSION['id'];

if(count($hash) != 4){
  die;
}

$hash[0] = $hash[0]/311;
$hash[1] = $hash[1]/311;
if($hash[0] == $id){
  $rmt = $hash[0];
  $dst = $hash[1];
} else if($hash[1] == $id){
  $rmt = $hash[1];
  $dst = $hash[0];
} else {
  die;
}

$sql = "INSERT INTO `chat` (
  `chat`, `rmt`, `dst`, `ativoRmt`,
  `ativoDst`, `visualizada`, `dataEnvio`
) VALUES ('$msg', '$rmt', '$dst', '1',
  '1', '0', '$data')";
$db->query($sql);

?>
