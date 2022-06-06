<?
// Log dos acontecimentos com o painel.
// Caso precise retirar, existe um codigo no final do lib/rodapé.
// Documentação: http://phpbuglost.com/docs/installation
include '../classes/phpbuglost.php';
 

// Includes gerais
include("../includes/config.php");
include("../includes/funcoes.php");

// Includes do adm
include("includes/config.php");
include("includes/funcoes.php");

// Classe PHPMailer (e-mail)
include("../classes/PHPMailer/PHPMailerAutoload.php");

// Classe Core (classe pai)
include("../classes/core.class.php");

// Login
if(($url[1] != "cadastros" && $url[2] != "professores") || ($url[1] == "cadastros" && $url[2] != "professores"))
include("includes/login.php");

define('_debug', false);

// Verifica a url[1](adm) e inclue o arquivo de acordo com a informação da url[1]
if($url[1]) {
	$modulo = "modulos/".$url[1];
  	// Verifica se o arquivo existe
  	if(file_exists($modulo)){	
		// Verifica a url[2](configuracoes, academico, financeiro) e inclue o arquivo de acordo com a informação da url[2]
		if($url[2]) {
			$funcionalidade = $modulo."/".$url[2];
			// Verifica se o arquivo existe
			if(file_exists($funcionalidade)){
				include($funcionalidade."/index.php");
			// Se o arquivo não existir, mostra ERRO 404
			} else {
				incluirLib("404",$config);
			}
		// Se o arquivo não existir, mostra ERRO 404
		} else {
			include("modulos/".$url[1]."/index/index.php");
		}
	// Se o arquivo não existir, mostra ERRO 404
	} else {
		incluirLib("404",$config);
	}
// Se o não tiver a url[1], inclue a home	
} else {
	include("modulos/index/index.php");
}
?>