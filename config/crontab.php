<?php
include '../coreExt.php';
include 'cront.php';

// Cria objeto Crontab
$cront = new Crontab;

// Executa a limpeza da tabela Broadcast
$cront->limpaBroadcast();

// Executa a limpeza da tabela Categorias da Wiki
$cront->limpaCatWiki();

// Executa a limpeza da tabela Chat
$cront->limpaChat();

// Executa a limpeza da tabela Grupo
$cront->limpaGrupo();

// Executa a limpeza da tabela Lembretes (Post-it)
$cront->limpaLembrete();

// Executa a limpeza da tabela Mural
$cront->limpaMural();

// Executa a limpeza da tabela Usuario
$cront->limpaUsuario();

?>
