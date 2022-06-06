<?php

$perfil['permissoes'] = (! empty($perfil['permissoes'])) ? $perfil['permissoes'] : null;

$permissoes = [];
$permissoes['visualizar'] = (
    $relatorioObj->verificaPermissao($perfil['permissoes'], $url[2] . '|1', false)
    || $url[0] == 'cfc'
);
