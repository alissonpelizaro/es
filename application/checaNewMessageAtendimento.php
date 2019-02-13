<?php
include '../coreExt.php';

if(retSitChatAtendimento($db)){
  echo "true";
} else {
  echo "false";
}

echo "-";

if(retSitChatAtendimentoPendente($db)){
	echo "true";
} else {
	echo "false";
}
?>
