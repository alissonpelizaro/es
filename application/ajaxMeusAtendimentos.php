<?php
include '../coreExt.php';

$idUser = $_POST['agent']/521;

$filtro = array(
  'status' => 0,
  'idAgente' => $idUser,
  'pendente' => 0
);
$ats = $model->getQuery('atendimento',$filtro);

$atsArray = array();

foreach ($ats as $at) {
  $idAt = $at['idAtendimento'];

  if($at['nome'] == ""){
    $at['nome'] = str_replace("@c.us", "", $at['remetente']);
  }

  $atsArray[count($atsArray)] = array(
    'plataforma' => $at['plataforma'],
    'idAtendimento' => $at['idAtendimento']*253,
    'nome' => $at['nome'],
    'last' => setupUltimaResposta($at['resposta'])
  );
}

echo json_encode($atsArray);

function setupUltimaResposta($data){
  if($data == 'agente' || $data == ''){
    return false;
  }

  $hoje = date("Y-m-d H:i:s");
  $date_time  = new DateTime($data." America/Sao_Paulo");
  $diff       = $date_time->diff( new DateTime($hoje." America/Sao_Paulo"));
  $hr = ($diff->days * 24) + $diff->h;
  $min = (int) $diff->i;
  if(($hr*60)+$min < 10){
    $sit = 'bg-verde';
  } else if(($hr*60)+$min < 15){
    $sit = 'badge-warning';
  }else {
    $sit = 'badge-danger';
  }
  return array(
    'sit' => $sit,
    'time' => $hr.":".$min
  );
  //return $diff->format('%y ano(s), %m mês(s), %d dia(s), %H hora(s), %i minuto(s) e %s segundo(s)');

}


 ?>
