<?php
if((int) $url[6] && $url[7] == "download") {
	$arquivo = $matriculaObj->retornarArquivoBiblioteca($ava['idava'], (int) $url[6]);
	$matriculaObj->cadastrarHistorioAluno($ava['idava'], 'download', 'arquivo', $arquivo['iddownload']);
	$matriculaObj->contabilizarArquivo($matricula['idmatricula'], $ava['idava'], $arquivo['iddownload']);
	
	include("telas/".$config["tela_padrao"]."/arquivos.download.php");
	exit;
} else {
	$pastas = $matriculaObj->retornarArquivosBiblioteca($ava['idava']);
	$matriculaObj->cadastrarHistorioAluno($ava['idava'], 'visualizou', 'arquivo');
	
	require 'idiomas/'.$config['idioma_padrao'].'/arquivos.php';
	require 'telas/'.$config['tela_padrao'].'/arquivos.php';
	exit;
}