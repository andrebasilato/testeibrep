<?php
@session_start();

$url = addslashes(strip_tags(rawurldecode($_SERVER["REQUEST_URI"]))); //Salva a url do browser na variavel $url
$get_array = explode("?",$url); // Separando os GETS
$url = explode("/",$get_array[0]); // Separa a url por a "/"

$qtdUrl = count($url);


for($i = 0; $i <= $qtdUrl; $i++ ) {
	if($url[0] != "api"){
		array_shift($url); // O primeiro índice sempre será vazio  
	}
}


$config["tituloPainel"] = "API";
$config["idiomas"] = array("pt_br" => "Portugues"); // , "en" => "English"
$config["idioma_padrao"] = "pt_br";
$config["tabela_monitoramento"] = "monitora_adm";
$config["tabela_monitoramento_primaria"] = "idusuario";
$config["tabela_monitoramento_log"] = "monitora_adm_log";
