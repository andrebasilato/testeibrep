<?php
header("Content-type: ".$imagem["background_tipo"]);
header('Content-Disposition: attachment; filename="'. basename($imagem["background_nome"]).'"');
header('Content-Length: '.$imagem["background_tamanho"]);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/declaracoes_background/".$imagem["background_servidor"]);
?>