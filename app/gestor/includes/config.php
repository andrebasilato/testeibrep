<?php

if ($detect->isMobile() && !$detect->isTablet()) {
  $config["tela_padrao"] = "mobile";
  
  if($_GET['classico'] == "true"){		
	  $config["tela_padrao"] = "desktop";
	  $_SESSION['classico'] = true;
  } else if($_GET['classico'] == "false"){
	  $config["tela_padrao"] = "mobile";
	  unset($_SESSION['classico']);
  } else if($_SESSION['classico'])
	  $config["tela_padrao"] = "desktop";
}

/*
if($_SESSION["adm_idusuario"] == "6"){
	$config["tela_padrao"] = "mobile";
}
*/


$url = addslashes(strip_tags(rawurldecode($_SERVER["REQUEST_URI"]))); //Salva a url do browser na variavel $url
$get_array = explode("?",$url); // Separando os GETS
$url = explode("/",$get_array[0]); // Separa a url por a "/"

$qtdUrl = count($url);

for($i = 0; $i <= $qtdUrl; $i++ ) {
	if($url[0] != "gestor"){
		array_shift($url); // O primeiro índice sempre será vazio  
	}
}

// Correção do BUG ativo e não ativo. (Manzano) ARMENGUE!!!
// Somente para facilitar o uso
if($_POST && !$_POST["ativo_painel"]) $_POST["ativo_painel"] = "S"; 

$config["tituloPainel"] = "Administrativo";
$config["tabela_monitoramento"] = "monitora_adm";
$config["tabela_monitoramento_primaria"] = "idusuario";
$config["tabela_monitoramento_log"] = "monitora_adm_log";
