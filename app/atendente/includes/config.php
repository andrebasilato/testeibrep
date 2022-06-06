<?php
@session_start();

$config["tela_padrao"] = "desktop";

$url = addslashes(strip_tags(rawurldecode($_SERVER["REQUEST_URI"]))); //Salva a url do browser na variavel $url
$get_array = explode("?",$url); // Separando os GETS
$url = explode("/",$get_array[0]); // Separa a url por a "/"

$qtdUrl = count($url);

for($i = 0; $i <= $qtdUrl; $i++ ) {
    if($url[0] != "atendente"){
        array_shift($url); // O primeiro índice sempre será vazio
    }
}

$config["tituloPainel"] = "Atendente";
$config["tabela_monitoramento"] = "monitora_vendedor";
$config["tabela_monitoramento_primaria"] = "idvendedor";
$config["tabela_monitoramento_log"] = "monitora_vendedor_log";
