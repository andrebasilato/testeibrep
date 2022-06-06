<?php

require '../classes/pessoas.class.php';
require 'config.php';
require 'config.formulario.php';

$linhaObj = new Pessoas();
$linhaObj->Set('monitora_onde',$config['monitoramento']['onde']);
$linhaObj->Set('idpessoa',$usuario['idpessoa']);

if($_POST['acao'] == 'salvar'){
	$linhaObj->Set('post',$_POST);
	$salvar = $linhaObj->ModificarAluno($usuario['idpessoa']);

	if($salvar['sucesso']) {
		$linhaObj->Set('pro_mensagem_idioma','modificar_sucesso');
		$linhaObj->Set('url','/'.$url[0].'/'.$url[1].'/'.$url[2]);
		$linhaObj->Processando();
	}
}

if($url[3] == 'upload') {
	if($_FILES) {
		foreach($_FILES as $ind => $val) {
			$_POST[$ind] = $val;
		}
    }
	$_POST[$config['banco']['primaria']] = $usuario['idpessoa'];
	if($_POST[$config['banco']['primaria']]) { 
		$linhaObj->Set('config',$config);
		$linhaObj->Set('post',$_POST);				
		$salvar = $linhaObj->Modificar();
		if($salvar['sucesso']){
			$linhaObj->Set('id',$usuario['idpessoa']);
			$linhaObj->Set('campos','p.*, pa.nome as pais');	
			$linha = $linhaObj->Retornar();
			echo '<img class="img-standard" src="/api/get/imagens/pessoas_avatar/268/267/'.$linha['avatar_servidor'].'" alt="Perfil">';
		}
    }
	exit;
}

$linhaObj->Set('id',$usuario['idpessoa']);
$linhaObj->Set('campos','p.*, pa.nome as pais');	
$linha = $linhaObj->Retornar();

if($url[3] == 'ajax_cidades'){
	if($_REQUEST['idestado']) {
		$linhaObj->RetornarJSON('cidades', mysql_real_escape_string($_REQUEST['idestado']), 'idestado', 'idcidade, nome', 'ORDER BY nome');
	} else { 
		$linhaObj->RetornarJSON('cidades', $url[4], 'idestado', 'idcidade, nome', 'ORDER BY nome');
	}
	exit;
}

$estados = $linhaObj->retornarEstados();
//$cidades = $linhaObj->retornarCidades();
$logradouros = $linhaObj->retornarLogradouros();

require 'idiomas/'.$config['idioma_padrao'].'/index.php';
require 'telas/'.$config['tela_padrao'].'/index.php';