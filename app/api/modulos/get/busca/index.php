<?php

if($_POST['painel'] and $_POST['palavra']){

	$busca = new Busca();
	$busca->Set('painel',$_POST['painel']);
	$busca->Set('palavra',$_POST['palavra']);
	$json = $busca->buscarOpcoes();
	
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json; charset=UTF8');
	echo json_encode($json);
}