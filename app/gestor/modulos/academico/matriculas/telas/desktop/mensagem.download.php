<?php

$extensao = strtolower(strrchr($arquivo["arquivo_servidor"], "."));

header("Content-type: ".$arquivo["arquivo_tipo"]);
header('Content-Disposition: attachment; filename="'. basename($arquivo["arquivo_nome"]).'"');

if($extensao != '.rar') 
	header('Content-Length: '.$arquivo["arquivo_tamanho"]);

header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/matriculas_mensagens/".$arquivo["arquivo_servidor"]);
?>