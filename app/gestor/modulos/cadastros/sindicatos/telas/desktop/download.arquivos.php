<?php

$arquivo = $_SERVER['DOCUMENT_ROOT'].'/storage/sindicatos_arquivos/'.$download['idsindicato'].'/'.$download['arquivo_servidor'];
if (file_exists($arquivo)) {
    header('Content-type: '.$download['arquivo_tipo']);
    header('Content-Disposition: attachment; filename="'.basename($download['arquivo_nome']).'"');
    header('Content-Length: '.$download['arquivo_tamanho']);
    header('Expires: 0');
    header('Pragma: no-cache');
    readfile($arquivo);
} else {
    echo 'Arquivo não encontrado.';
}
