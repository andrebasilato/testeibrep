<?php
include("../classes/calendario.class.php");
include("config.php");
include("config.formulario.php");
include("config.listagem.php");
	
//Incluimos o arquivo com variaveis padrão do sistema.
include("idiomas/".$config["idioma_padrao"]."/idiomapadrao.php");
	
$linhaObj = new Calendario();
$linhaObj->verificaPermissao($perfil["permissoes"], $url[2]."|1");	
	
$linhaObj->Set("idusuario",$usuario["idusuario"]);
$linhaObj->Set("monitora_onde",$config["monitoramento"]["onde"]);	
$linhaObj->set('modulo', $url[0]);
			
if($url[3] && $url[4]) {
	switch ($url[3]) {
		case "chats":			
			$chats = $linhaObj->retornarChats($url[4]);
			
			include("idiomas/".$config["idioma_padrao"]."/chats.php");
			include("telas/".$config["tela_padrao"]."/chats.php");
		break;
		case "provas":	
			$provas = $linhaObj->retornarProvas($url[4], $url[5]);
				
			include("idiomas/".$config["idioma_padrao"]."/provas.php");
			include("telas/".$config["tela_padrao"]."/provas.php");
		break;	
		default:
			include("idiomas/".$config["idioma_padrao"]."/index.php");
			include("telas/".$config["tela_padrao"]."/index.php");	
		exit();
	}
} else {
	$linhaObjInst = new Sindicatos();	
	$sindicatos = $linhaObjInst->retornarSindicatosUsuario($usuario["idusuario"]);

	include("idiomas/".$config["idioma_padrao"]."/index.php");
	include("telas/".$config["tela_padrao"]."/index.php");
	exit();
}
	