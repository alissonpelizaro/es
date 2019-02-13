<?php
include '../coreExt.php';
$token = geraSenha(15);
$id = $_SESSION['id'];
$ativo = $_POST['hash'];

$sql = "SELECT `idAtendimento`, `dataInicio`, `remetente`, `plataforma`, `nome` FROM `atendimento` WHERE `status` = 0 AND `idAgente` = '$id' AND (`pendente` != 1 OR `pendente` IS NULL)";
$at = $db->query($sql);
$at = $at->fetchAll();

if(count($at) == 0){
  ?>
  <p><i>Nenhum atendimento aberto</i></p>
  <?php
} else {

  $nviz = array();
  $auxnviz = 0;

  $viz = array();
  $auxviz = 0;

  foreach ($at as $k) {
    $v = chechaMsg($k['idAtendimento'], $db);

    if ($v > 0){
      $var = "nviz";
    } else {
      $var = "viz";
    }

    ${$var}[${"aux".$var}] = "
    <a href='media?hash=".$k['idAtendimento']*253 ."&token=".$token."'>
    <div class='media campo-contato campo-atendimento ";
    if($ativo == ($k['idAtendimento']*253)){
      ${$var}[${"aux".$var}] .= "campo-atendimento-ativo";
    }
    ${$var}[${"aux".$var}] .= "'>
    <div class='avatar-chat avatar-lista-contatos avatar-atendimentos'>
    <img alt='nome' src='assets/icons/social/". $k['plataforma'] .".png'>
    </div>
    <div class='media-body contato-lista-chat'>
    <h4 class='media-heading'>";
    if ($v > 0){
      if($k['nome'] != ''){
        ${$var}[${"aux".$var}] .= '<b>'.$k['nome'].'</b>';
      } else {
        ${$var}[${"aux".$var}] .= '<b>'.$k['remetente'].'</b>';
      }
      ${$var}[${"aux".$var}] .= " <span class='label label-info bg-bell'><b>". $v ."</b></span>";
    } else {
      if($k['nome'] != ''){
        ${$var}[${"aux".$var}] .= $k['nome'];
      } else {
        ${$var}[${"aux".$var}] .= $k['remetente'];
      }
    }
    ${$var}[${"aux".$var}] .= "</h4>
    <p class='p-lista-contatos'>Inicio: ".dataBdParaHtml($k['dataInicio']) ."</p>
    </div>
    </div>
    </a>
    ";
    ${"aux".$var}++;
  }

  $atendimentos = array_merge($nviz, $viz);

  foreach ($atendimentos as $at) {
    echo $at;
  }

}


function chechaMsg($at, $db){
  $sql = "SELECT count(`idChatAtendimento`) AS `total` FROM `chat_atendimento` WHERE `idAtendimento` = '$at' AND `visualizada` = 0 AND `rmt` = 'cliente'";
  $tt = $db->query($sql);
  $tt = $tt->fetchAll();
  return $tt[0]['total'];
}

?>
