<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(file_exists("../thirdy/phpMailer/vendor/autoload.php")){
  //Load Composer's autoloader
  require '../thirdy/phpMailer/vendor/autoload.php';

}



function pegaExtensao($arquivo){
  $arquivo = strtolower($arquivo);
  $explode = explode(".", $arquivo);
  $arquivo = end($explode);

  return ($arquivo);
}

function tiraAcento($string){
  return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$string);
}


function sendMailRecovery($email, $token){

  $mensagem = "
  <div style='font-family: Calibri, Arial; text-align: center; color: gray'>
  <br>
  Olá! Recebemos uma solicitação de recuperação de senha do <strong>Portal MyOmni</strong> através do seu e-mail.
  Se essa solicitação foi realmente feita por você, acesse o link abaixo para criar uma nova senha. Caso contrário, desconsidere esse e-mail.
  <br><br>
  <i>
  <a href='localhost/omni/my/passrecovery?token=$token'>localhost/omni/my/passrecovery?token=$token</a>
  </i>
  <br><br>
  Obrigado!
  <br>
  Equipe MyOmni.
  </div>";

  // Configuração E-mail
  $email_base = "no-reply@enterness.com";
  $email_password = 'entercbs2017';
  $email_host = 'mx.enterness.com';
  $email_auth = true;
  $email_charset = 'UTF-8';
  $email_secure = 'tls';
  $email_port = 25;

  $mail = new PHPMailer(false);                              // Passing `true` enables exceptions
  //Server settings
  $mail->SMTPDebug = 0;                                 // Enable verbose debug output
  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->SMTPSecure = $email_secure;                            // Enable TLS encryption, `ssl` also accepted
  $mail->Host = $email_host;                     // Specify main and backup SMTP servers
  $mail->Port = $email_port;                                    // TCP port to connect to
  $mail->SMTPAuth = $email_auth;                               // Enable SMTP authentication
  $mail->Username = $email_base;           // SMTP username
  $mail->Password = $email_password;                     // SMTP password
  $mail->CharSet = $email_charset;
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );

  //Recipients
  $mail->setFrom($email_base);

  //Content
  $mail->isHTML(true);
  $mail->Timeout = 10; // set the timeout (seconds)
  $mail->SMTPKeepAlive = true;
  $mail->addAddress($email);

  $mail->Subject = 'MyOmni - Recuperação de senha!';
  $mail->Body    = $mensagem;
  //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  if($mail->send()){
    $mail->SmtpClose();
    return true;
  } else {
    $mail->SmtpClose();
    return false;
  }

}

function datepickerParaBd($data){
  $exp = explode(' ', $data);
  if(!isset($exp[0]) || !isset($exp[1]) || !isset($exp[2])){
    $exp = explode('/', $data);
    if(isset($exp[0]) && isset($exp[1]) && isset($exp[2])){
      return $exp[2]."-".$exp[1]."-".$exp[0]." 00:00:00";
    } else {
      return false;
    }
  } else {
    $data = explode('/', $exp[0]);
    $data = $data[2]."-".$data[1]."-".$data[0];

    $hora = explode(':', $exp[1]);
    $h = $hora[0];
    $m = $hora[1];

    if($h == '12' && $exp[2] == 'am'){
      $h = '00';
    } else if($h != '12' && $exp[2] == 'pm'){
      $h = $h+12;
    }

    return $data . " " . $h.":".$m.":00";
  }
}

function dateHtmlParaBd($data){
	$data = explode(" ", $data);
	if(isset($data[1])){
	  if(is_array($data)){
	  	if($data[2] == "pm" || $data[2] == "am"){
	  		if ($data[2] == "pm") {
	  			$cursor = explode(":", $data[1]);
	  			$data[1] = ($cursor[0] + 12) . ":" . $cursor[1];
	  		}
	  		$hora = $data[1];
	  		$data = explode("/", $data[0]);
	  		return $data[2]."-".$data[1]."-".$data[0]." ".$hora;
	  	} else if ($data[1] == "às") {
	  		$hora = $data[2];
	  		$data = explode("/", $data[0]);
	  		return $data[2]."-".$data[1]."-".$data[0]." ".$hora;
	  	} else {
	    	$hora = $data[1];
	      $data = explode("/", $data[0]);
	      return $data[2]."-".$data[1]."-".$data[0]." ".$hora;
	  	}
	  } else {
	    return false;
	  }
	} else {
		$data = explode("/", $data[0]);
		if(is_array($data)){
			return $data[2]."-".$data[1]."-".$data[0];
		} else {
			return false;
		}
	}
}

function retSoDataDatePicker($hora){
  if($hora == ''){
    return "";
  }
  $hora = explode(' ', $hora);
  $data = $hora[0];

  $data = explode('-', $data);

  return $data[2]."/".$data[1]."/".$data[0];
}

function dataBdParaHtml($hora){
  if($hora == ''){
    return "";
    die;
  }
  $hora = explode(' ', $hora);
  if(count($hora) > 0){
    $data = $hora[0];
    $hora = $hora[1];

    $data = explode('-', $data);
    $hora = explode(':', $hora);

    return $data[2]."/".$data[1]."/".$data[0]." às ".$hora[0].":".$hora[1];
  } else {
    $data = explode('-', $hora);
    return $data[2]."/".$data[1]."/".$data[0];
  }
}

function dataBdParaDataMesText($data){
	$meses = array("Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez");

	$data = explode('-', $data);

	return $data[2] . " " . $meses[($data[1]-1)] . " " . $data[0];
}

function fixHora($hora){
  $hora = explode(":",$hora);
  if(!is_array($hora)){
    return false;
  } else {
    if($hora[0] < 10){
      $hora[0] = "0". (int) $hora[0];
    }
    if($hora[1] < 10){
      $hora[1] = "0". (int) $hora[1];
    }
    return $hora[0].":".$hora[1];
  }
}

function cortaString($str, $len){
  if(strlen($str) <= $len){
    return $str;
  } else {
    $str = substr($str, 0, ($len-2));
    return $str."...";
  }
}

function retCargo($str){
  if($str == 'dev'){
    return "Desenvolvedor";
  } else if($str == 'coordenador'){
    return "Coordenador";
  } else if($str == 'administrador'){
    return "Administrador";
  } else if($str == 'supervisor'){
    return "Supervisor";
  } else if($str == 'agente'){
    return "Agente";
  } else if($str == 'gestor'){
    return "Gestor";
  } else if($str == 'tecnico'){
    return "Técnico";
  }
}

function chatState($state){
  if($state == 'todos'){
    return 'Liberado';
  } else if($state == 'sup'){
    return 'P/ supervisores';
  } else {
    return 'Bloqueado';
  }
}

function statusAgente($logged, $userTime){
  if($logged == '1'){
    $time = $_SESSION['timeout'];
    if($time == '0'){
      $time = 999999999;
    }
    if(strtotime(date('Y-m-d H:i:s', strtotime('-'.$time.' minute'))) > strtotime($userTime)){
      return "offline";
    } else if(strtotime(date('Y-m-d H:i:s', strtotime('-10 minute'))) > strtotime($userTime)){
      return "ausente";
    } else {
      return "online";
    }
  } else {
    return "offline";
  }
}

function pegaUsuario($db, $id){
  $sql = "SELECT `nome`, `sobrenome` FROM `user` WHERE `idUser` = '$id'";
  $result = $db->query($sql);
  $result = $result->fetchAll();
  if(count($result) == 0){
    return false;
  } else {
    return $result[0]['nome']." ".$result[0]['sobrenome'];
  }
}

function retArrayLembretesToolbar($db){
  $id = $_SESSION['id'];

  $sql = "SELECT `titulo`, `desc`, `cor`, `alarme` FROM `lembrete` WHERE `idUser` = '$id' AND `status` = '1'  AND `alarme` != '0000-00-00 00:00:00'";
  $lembretes = $db->query($sql);
  $lembretes = $lembretes->fetchAll();
  return $lembretes;

}

function retSitChat($db){
  $id = $_SESSION['id'];

  $sql = "SELECT `idChat` FROM `chat` WHERE `dst` = '$id' AND `visualizada` = '0'";
  $sit = $db->query($sql);
  $sit = $sit->fetchAll();

  if(count($sit) == 0){
    return false;
  } else {
    return true;
  }
}


function retSitChatAtendimento($db){
  $id = $_SESSION['id'];
  $ding = false;

  $sql = "SELECT `idAtendimento` FROM `atendimento` WHERE `status` = '0' AND `idAgente` = '$id' AND (`pendente` != 1 OR `pendente` IS NULL)";
  $at = $db->query($sql);
  $at = $at->fetchAll();
  foreach ($at as $k) {
    $idAt = $k['idAtendimento'];
    $sql = "SELECT count(`idChatAtendimento`) AS `total` FROM `chat_atendimento` WHERE `idAtendimento` = '$idAt' AND `visualizada` = 0 AND `rmt` = 'cliente'";
    $tt = $db->query($sql);
    $tt = $tt->fetch();
    if($tt['total'] > 0){
      $ding = true;
    }
  }

  if($ding){
    return true;
  } else {
    return false;
  }
}

/*function retSitChatAtendimentoPendente($db) {
$id = $_SESSION['id'];
$ding = false;

$sql = "SELECT `idAtendimento` FROM `atendimento` WHERE `status` = '0' AND `idAgente` = '$id' AND `pendente` = 1";
$at = $db->query($sql);
$at = $at->fetchAll();
foreach ($at as $k) {
$idAt = $k['idAtendimento'];
$sql = "SELECT count(`idChatAtendimento`) AS `total` FROM `chat_atendimento` WHERE `idAtendimento` = '$idAt' AND `visualizada` = 0 AND `rmt` = 'cliente'";
$tt = $db->query($sql);
$tt = $tt->fetch();
if($tt['total'] > 0){
$ding = true;
}
}

if($ding){
return true;
} else {
return false;
}
}*/

function retSitChatAtendimentoPendente($db) {
  $id = $_SESSION['id'];
  $ding = false;

  $sql = "SELECT `idAtendimento` FROM `atendimento` WHERE `status` = '0' AND `idAgente` = '$id' AND `pendente` = 1";

  $at = $db->query($sql);
  $at = $at->fetchAll();
  //echo("qui");

  foreach ($at as $k) {
    $idAt = $k['idAtendimento'];
    $sql = "SELECT count(`idAtendimento`) AS `total` FROM `chat_atendimento` WHERE `idAtendimento` = '$idAt' AND `visualizada` = 0 AND `rmt` = 'cliente'";
    $tt = $db->query($sql);
    $tt = $tt->fetch();
    //echo $tt['total'];
    if($tt['total'] > 0){
      $ding = true;
    }
  }

  return $ding;
}

function retQtdGrupo($str){

  $cont = 0;
  $str = explode("-", $str);

  foreach ($str as $k) {
    if($k != ""){
      $cont++;
    }
  }

  return $cont;
}

function retMes($mes){
  if($mes == 1){
    return "Janeiro";
  } else if($mes == 2){
    return "Fevereiro";
  } else if($mes == 3){
    return "Março";
  } else if($mes == 4){
    return "Abril";
  } else if($mes == 5){
    return "Maio";
  } else if($mes == 6){
    return "Junho";
  } else if($mes == 7){
    return "Julho";
  } else if($mes == 8){
    return "Agosto";
  } else if($mes == 9){
    return "Setembro";
  } else if($mes == 10){
    return "Outubro";
  } else if($mes == 11){
    return "Novembro";
  } else if($mes == 12){
    return "Dezembro";
  } else {
    return false;
  }
}

function retMesAbrev($mes){
  if($mes == 1){
    return "Jan";
  } else if($mes == 2){
    return "Fev";
  } else if($mes == 3){
    return "Mar";
  } else if($mes == 4){
    return "Abr";
  } else if($mes == 5){
    return "Mai";
  } else if($mes == 6){
    return "Jun";
  } else if($mes == 7){
    return "Jul";
  } else if($mes == 8){
    return "Ago";
  } else if($mes == 9){
    return "Set";
  } else if($mes == 10){
    return "Out";
  } else if($mes == 11){
    return "Nov";
  } else if($mes == 12){
    return "Dez";
  } else {
    return false;
  }
}

function retMesAbrevInverso($mes){
  if($mes == "Jan"){
    return 1;
  } else if($mes == "Fev"){
    return 2;
  } else if($mes == "Mar"){
    return 3;
  } else if($mes == "Abr"){
    return 4;
  } else if($mes == "Mai"){
    return 5;
  } else if($mes == "Jun"){
    return 6;
  } else if($mes == "Jul"){
    return 7;
  } else if($mes == "Ago"){
    return 8;
  } else if($mes == "Set"){
    return 9;
  } else if($mes == "Out"){
    return 10;
  } else if($mes == "Nov"){
    return 11;
  } else if($mes == "Dez"){
    return 12;
  } else {
    return false;
  }
}


function retDiaSem($dia){
  if($dia == 1){
    return "Domingo";
  } else if($dia == 2){
    return "Segunda";
  } else if($dia == 3){
    return "Terça";
  } else if($dia == 4){
    return "Quarta";
  } else if($dia == 5){
    return "Quinta";
  } else if($dia == 6){
    return "Sexta";
  } else if($dia == 7){
    return "Sábado";
  } else {
    return false;
  }
}

function countSemanasMes ($ano, $mes){

  $data = new DateTime("$ano-$mes-01");
  $dataFimMes = new DateTime($data->format('Y-m-t'));

  $numSemanaInicio = $data->format('W');
  $numSemanaFinal  = $dataFimMes->format('W') + 1;

  // Última semana do ano pode ser semana 1
  $numeroSemanas = ($numSemanaFinal < $numSemanaInicio)
  ? (52 + $numSemanaFinal) - $numSemanaInicio
  : $numSemanaFinal - $numSemanaInicio;


  $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
  $atual = $ano."-".$mes."-".cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
  $diasemana_numero = date('w', strtotime($atual));

  if($diasemana[$diasemana_numero] == "Domingo"){
    $numeroSemanas++;
  }

  return $numeroSemanas;
}

function retJornada($horario){
  try {
    $horario = explode("#", $horario);
    $hora = explode("-", $horario[0]);
    $dias = explode("-", $horario[1]);

    return array(
      'entrada' => $hora[0],
      'almoco' => $hora[1],
      'retorno' => $hora[2],
      'saida' => $hora[3],
      'dias' => $horario[1]
    );

  } catch (\Exception $e) {
    return false;
  }

}

function horaParaSegundos($hora){
  $hora = explode(":", $hora);
  $th = ((int) $hora[0] * 60)*60;
  $tm = (int) $hora[1] * 60;

  return $th + $tm;
}

function segundosParaHora($seg){
  $horas = floor($seg / 3600);
  $minutos = floor(($seg - ($horas * 3600)) / 60);
  $segundos = floor($seg % 60);
  if($horas < 10){
    $horas = "0".$horas;
  }
  if($minutos < 10){
    $minutos = "0".$minutos;
  }
  if($segundos < 10){
    $segundos = "0".$segundos;
  }

  return $horas . ":" . $minutos . ":". $segundos;
}

function getWorkingDays($startDate, $endDate) {
  $begin = strtotime($startDate);
  $end   = strtotime($endDate);
  if ($begin > $end) {
    return 0;
  }
  else {
    $holidays = array('01/01', '25/12', '12/10');
    $weekends = 0;
    $no_days = 0;
    $holidayCount = 0;
    while ($begin <= $end) {
      $no_days++; // no of days in the given interval
      if (in_array(date("d/m", $begin), $holidays)) {
        $holidayCount++;
      }
      $what_day = date("N", $begin);
      if ($what_day > 5) { // 6 and 7 are weekend days
        $weekends++;
      };
      $begin += 86400; // +1 day
    };
    $working_days = $no_days - $weekends - $holidayCount;

    return $working_days;
  }
}

function retArrayPendentesToolbar($db) {
  $id = $_SESSION['id'];

  $sql = "SELECT `plataforma`, `origem`, `dataInicio`, `remetente`, `nome`, `fila`, `idAtendimento`
  FROM `atendimento`
  WHERE `idAgente` = '$id'
  AND `status` != '1'
  AND `pendente` = '1'";
  $pendentes = $db->query($sql);
  $pendentes = $pendentes->fetchAll();

  $dados = array();
  //$cont = 0;

  foreach ($pendentes as $value) {
    array_push($value, retArrayAlertaPendenteToolbar($db, $value["idAtendimento"]));
    //$dados[$cont] = $value;
    $dados = insert_sort($dados, $value);
    //$cont++;
  }

  return $dados;
}

function retArrayAlertaPendenteToolbar($db, $idAtendimento) {

  $sql = "SELECT COUNT(`idChatAtendimento`) AS `total`
  FROM `chat_atendimento`
  WHERE `visualizada` = 0
  AND	`rmt` = 'cliente'
  AND `idAtendimento` = '$idAtendimento'";

  $dados = $db->query($sql);
  $dados = $dados->fetch();
  $dados = $dados['total'];

  return $dados;
}

function insert_sort($result, $item)
{
  // Vari�vel de controle da posi��o na lista:
  $index = 0;

  // Percorre a lista:
  foreach ($result as $j => $value)
  {

    // Verifica a condi��o: (novo item) > (item da lista)?
    if (array_values($item)[14] >= array_values($value)[14])
    {
      // Sim, ent�o pare de percorrer a lista
      break;
    }

    // N�o, ent�o continue para a pr�xima posi��o:
    $index++;
  }

  // O novo item ser� inserido na posi��o $index
  // Para isso, precisamos abrir o espa�o na lista com a fun��o array_splice:
  array_splice($result, $index, 0, array($item));

  // Retorne a lista ordenada:
  return $result;
}

?>
