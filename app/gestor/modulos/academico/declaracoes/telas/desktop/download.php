<?php
header("Content-type: ".$imagem["tipo"]);
header('Content-Disposition: attachment; filename="'. basename($imagem["nome"]).'"');
header('Content-Length: '.$imagem["tamanho"]);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/declaracoes_imagens/".$imagem["servidor"]);
?>