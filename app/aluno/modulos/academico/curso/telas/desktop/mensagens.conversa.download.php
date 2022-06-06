<?php
header("Content-type: ".$download["arquivo_tipo"]."; charset=utf-8");
header('Content-Disposition: attachment; filename="'.basename($download["arquivo_nome"]).'"');
header('Content-Length: '.$download["arquivo_tamanho"]);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/avas_mensagens_instantaneas/".$download["arquivo_servidor"]);