<?php
if($_POST['acao'] == 'remover' && (int) $_POST['idanotacao']){
	$_POST['idava'] = $ava['idava'];
	$matriculaObj->set("post",$_POST);
	$salvar = $matriculaObj->deletarAnotacao();
	if($salvar["sucesso"]){
		$matriculaObj->cadastrarHistorioAluno($ava['idava'], 'removeu', "anotacao", (int) $_POST['idanotacao']);
		$matriculaObj->set("pro_mensagem_idioma","remover_sucesso");
		$matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]);
		$matriculaObj->Processando();
	}
}

$anotacoes = $matriculaObj->retornarAnotacoes($ava['idava']);
$matriculaObj->cadastrarHistorioAluno($ava['idava'], 'visualizou', "anotacao");

require 'idiomas/'.$config['idioma_padrao'].'/anotacoes.php';
require 'telas/'.$config['tela_padrao'].'/anotacoes.php';
exit;