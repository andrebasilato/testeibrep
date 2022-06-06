<?php
$pasta = '';
if ($url[5] == 'avatar') {
    $pasta = 'escolas_avatar';
} elseif ($url[5] == 'gerente_assinatura') {
    $pasta = 'escolas_gerente_assinatura';
} elseif ($url[5] == 'responsavel_legal_assinatura') {
    $pasta = 'escolas_responsavel_legal_assinatura';
} elseif ($url[5] == 'diretor_ensino_assinatura') {
    $pasta = 'escolas_diretor_ensino_assinatura';
}

header('Content-type: ' . $linha[$url[5] . '_tipo']);
header('Content-Disposition: attachment; filename="' . basename($linha[$url[5] . '_nome']) . '"');
header('Content-Length: ' . $linha[$url[5] . '_tamanho']);
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER['DOCUMENT_ROOT'] . '/storage/' .$pasta . '/' . $linha[$url[5] . '_servidor']);
