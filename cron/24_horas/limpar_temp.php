<?php
$raiz = $caminhoApp."/app/storage/temp";
apagar_recursividade($raiz, $raiz);
$raiz = $caminhoApp."/app/storage/relatorios_gerenciais";
apagar_recursividade($raiz, $raiz);
$raiz = $caminhoApp."/app/storage/cache_imagens";
apagar_recursividade($raiz, $raiz);
?>
