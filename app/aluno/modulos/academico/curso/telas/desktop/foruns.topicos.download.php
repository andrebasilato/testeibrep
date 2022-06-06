<?php 
header("Content-type: ".$topico["arquivo_tipo"]);
header('Content-Disposition: attachment; filename="'. basename($topico["arquivo_nome"]).'"');
header('Content-Length: '.$topico["arquivo_tamanho"]);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/avas_foruns_topicos_arquivo/".$topico["arquivo_servidor"]);