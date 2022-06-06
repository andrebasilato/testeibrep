<?php 
header("Content-type: ".$mensagem["arquivo_tipo"]);
header('Content-Disposition: attachment; filename="'. basename($mensagem["arquivo_nome"]).'"');
header('Content-Length: '.$mensagem["arquivo_tamanho"]);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/avas_foruns_topicos_mensagens_arquivo/".$mensagem["arquivo_servidor"]);