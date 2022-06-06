<?php

$url = addslashes(strip_tags(rawurldecode($_SERVER["REQUEST_URI"]))); //Salva a url do browser na variavel $url
$get_array = explode("?",$url); // Separando os GETS
$url = explode("/",$get_array[0]); // Separa a url por a "/"

$qtdUrl = count($url);

for($i = 0; $i <= $qtdUrl; $i++ ) {
	if($url[0] != "pesquisa"){
		array_shift($url); // O primeiro índice sempre será vazio  
	}
}

$config["tituloPainel"] = "Pesquisa";
