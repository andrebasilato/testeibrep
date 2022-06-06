<?php
if($_POST['acao'] == 'remover' && (int) $_POST['idfavorito']){
	$matriculaObj->set("post",$_POST);
	$salvar = $matriculaObj->removerFavorito($ava['idava'], (int) $_POST['idfavorito']);
	if($salvar["sucesso"]){
		$matriculaObj->cadastrarHistorioAluno($ava['idava'], 'removeu', "favorito", (int) $_POST['idfavorito']);
		$matriculaObj->set("pro_mensagem_idioma","remover_sucesso");
		$matriculaObj->set("url","/".$url[0]."/".$url[1]."/".$url[2]."/".$matricula['idmatricula']."/".$url[4]."/".$url[5]);
		$matriculaObj->Processando();
	}
}

$favoritos = $matriculaObj->retornarFavoritos($ava['idava']);
$matriculaObj->cadastrarHistorioAluno($ava['idava'], 'visualizou', "favorito");

require 'idiomas/'.$config['idioma_padrao'].'/favoritos.php';
require 'telas/'.$config['tela_padrao'].'/favoritos.php';
exit;