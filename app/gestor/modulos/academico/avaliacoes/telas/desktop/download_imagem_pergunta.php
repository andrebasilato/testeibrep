<?php
header("Content-type: ".$arquivo["imagem_tipo"]);
header('Content-Disposition: attachment; filename="'.basename($arquivo["imagem_nome"]).'"');
header('Content-Length: '.$arquivo["imagem_tamanho"]);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"]."/storage/disciplinas_perguntas_imagens/".$arquivo["imagem_servidor"]);
?>