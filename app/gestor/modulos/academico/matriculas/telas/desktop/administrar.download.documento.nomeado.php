<?php
$nome = explode(" ", $matriculaObj->getStudentName($matricula['idmatricula']));
$nome = limparString($nome[0]);
$extensaoDoArquivo = explode(".", $documentos[0]['arquivo_servidor']);
$documentoNome = "1_{$matricula['idmatricula']}_{$nome}.{$extensaoDoArquivo[1]}";
$diretorio_arquivo = "{$_SERVER["DOCUMENT_ROOT"]}/storage/matriculas_documentos/{$documentos[0]["arquivo_pasta"]}/{$matricula["idmatricula"]}/{$documentoNome}";
copy("{$_SERVER["DOCUMENT_ROOT"]}/storage/matriculas_documentos/{$documentos[0]["arquivo_pasta"]}/{$matricula["idmatricula"]}/{$documentos[0]["arquivo_servidor"]}",  $diretorio_arquivo);
header("Content-Type: {$documentos[0]["arquivo_tipo"]}");
header("Content-Length: " . filesize("{$documentoNome}"));
header("Content-Disposition: attachment; filename=" . basename("{$documentoNome}"));
header('Expires: 0');
header('Pragma: no-cache');
readfile($diretorio_arquivo);


