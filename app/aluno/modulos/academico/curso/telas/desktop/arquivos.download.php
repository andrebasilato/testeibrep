<?php 
header('Content-type: '.$arquivo['arquivo_tipo']);
header('Content-Disposition: attachment; filename="'. basename($arquivo['arquivo_nome']).'"');
header('Content-Length: '.$arquivo['arquivo_tamanho']);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER['DOCUMENT_ROOT'].'/storage/avas_downloads_arquivo/'.$arquivo['arquivo_servidor']);