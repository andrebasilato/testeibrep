<?php
if((int) $url[6]) {
	if($url[7] == 'mensagens') {
		require 'idiomas/'.$config['idioma_padrao'].'/colegasdesala.mensagens.php';
		require 'telas/'.$config['tela_padrao'].'/colegasdesala.mensagens.php';
		exit;
	}
} else {
	if(!$_GET['p']) $_GET['p'] = 1;
	$matriculaObj->Set('pagina',$_GET['p']);
	$colegas = $matriculaObj->retornarColegas($ava['idava'], $_GET['b'], $_GET['l']);
	$matriculaObj->cadastrarHistorioAluno($ava['idava'], 'visualizou', "colegas");

	require 'idiomas/'.$config['idioma_padrao'].'/colegasdesala.php';
	require 'telas/'.$config['tela_padrao'].'/colegasdesala.php';
	exit;
}