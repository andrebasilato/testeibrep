<?php
$calendarioObj = new Calendario();
	
$calendarioObj->Set('idpessoa',$usuario['idpessoa'])
				->Set('monitora_onde',$config['monitoramento']['onde'])
				->set('modulo', $url[0])
				->set('idsAvas', $calendarioObj->retornarAvasAluno($usuario['idpessoa']))
				->set('idsMatriculas', $calendarioObj->retornarMatriculasAluno($usuario['idpessoa']));

if(isset($url[3])){
	$chatsProvas = $calendarioObj->retornarChatsProvasNaData($url[3]);
	//print_r2($chatsProvas,true);
	
	require 'idiomas/'.$config['idioma_padrao'].'/visualizar.php';
	require 'telas/'.$config['tela_padrao'].'/visualizar.php';
	exit();
} else {	
	if(!$_GET['mes']) $_GET['mes'] = date("m"); 
	if(!$_GET['ano']) $_GET['ano'] = date("Y");
	
	require 'idiomas/'.$config['idioma_padrao'].'/index.php';
	require 'telas/'.$config['tela_padrao'].'/index.php';
	exit();
}