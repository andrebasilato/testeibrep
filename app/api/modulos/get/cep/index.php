<?php
$ch = curl_init();

// informar URL e outras funções ao CURL
//curl_setopt($ch, CURLOPT_URL, "http://cep.construtor.alfamaweb.com.br/cep.php");
curl_setopt($ch, CURLOPT_URL, "https://construtor.de/vendas/cep.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Faz um POST
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);

// Acessar a URL e retornar a saída
$output = curl_exec($ch);
// liberar
curl_close($ch);

// Imprimir a saída
echo $output;