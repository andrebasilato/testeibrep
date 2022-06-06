<?php

$arquivo = $_SERVER['DOCUMENT_ROOT'].'/storage/escolas_arquivos/'.$download['idescola'].'/'.$download['arquivo_servidor'];
if (file_exists($arquivo)) {
    header('Content-type: '.$download['arquivo_tipo']);
    header('Content-length: '.filesize($arquivo));
    readfile($arquivo);
} else {
    echo 'Arquivo não encontrado.';
}
