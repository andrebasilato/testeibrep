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
if(!$url[1]) {
	include("modulos/index/index.php");
}
?>