<?php
include '../core.php';

/**
 * Pucha todas as palavras com restrição do banco.
 * @var string $sql variavel que comprotará a quary para consulta.
 * @var PDOStatement $listaPalavras variavel que receberá os dados da consulta.
 * @var ArrayObject $db variavel padrão para consultas de query's.
 */
$sql = "SELECT * FROM `dicionario`";
$listaPalavras = $db->query($sql);
$listaPalavras = $listaPalavras->fetchAll();

/**
 * Pega o id do usuario, localiza no banco e tras o nome e o sobrenome.
 * @var array $linhas Nova lista, contendo o nome e sobrenome, em vez do id.
 * @var integer $cont Contador, para gerar numeração em sequencia.
 * @var string $sql Variavel que comprotará a quary para consulta.
 * @var PDOStatement $nomeUser Variavel que receberá os dados da consulta.
 * @var ArrayObject $db Variavel padrão para consultas de query's.
 */
$linhas = array();
$cont = 0;
foreach ($listaPalavras as $linha) {
	$id = $linha[3];
	$sql = "SELECT `nome`, `sobrenome` FROM `user` WHERE idUser = $id";
	$nomeUser = $db->query($sql);
	$nomeUser = $nomeUser->fetch();
	$nome = $nomeUser[0] . " " . $nomeUser[1];
	if($linha[2] == "palavrao"){
		$categoria = "Palavrão";
	} else {
		$categoria = "Palavra de alerta";
	}
	
	$linhas[$cont] = array($linha[1], $categoria, $nome, $linha[0]);
	$cont++;
}

if(isset($_POST["palavra"])){
	
	$palavra = tratarString($_POST["palavra"]);
	$categoria = tratarString($_POST["categoria"]);
	
	$sql = "INSERT INTO dicionario (`palavra`, `categoria`, `idUser`) VALUES ('".strtolower($palavra)."', '$categoria', '$idUser')";
	if($db->query($sql)){
		header("location: ../my/dicionario-restricoes?cadastro=success");	
	} else {
		header("location: ../my/dicionario-restricoes?cadastro=failure");
	}
}

if(isset($_GET["id"])){
	$id = tratarString($_GET["id"]);
	
	$sql = "DELETE FROM dicionario WHERE idPalavra = $id";
	if($db->query($sql)){
		header("location: ../my/dicionario-restricoes?deletar=success");
	} else {
		header("location: ../my/dicionario-restricoes?deletar=failure");
	}
}

?>