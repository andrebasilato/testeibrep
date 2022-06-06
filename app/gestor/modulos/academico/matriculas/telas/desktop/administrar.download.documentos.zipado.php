<?php
$nome = explode(" ", $matriculaObj->getStudentName($matricula['idmatricula']));
$nome = limparString($nome[0]);
var_dump($nome);
$zip = new ZipArchive();

$diretorio_zip = "{$_SERVER["DOCUMENT_ROOT"]}/storage/matriculas_documentos/zipados/{$matricula['idmatricula']}_{$nome}.zip";
$zip->open($diretorio_zip, ZipArchive::CREATE || ZipArchive::OVERWRITE);
foreach ($documentos as $chave => $documento) {
    $extensaoDoArquivo = explode(".", $documento['arquivo_servidor']);
    $documentoNome = ($chave + 1) . "_{$matricula['idmatricula']}_{$nome}.{$extensaoDoArquivo[1]}";
    $diretorio_arquivo = "{$_SERVER["DOCUMENT_ROOT"]}/storage/matriculas_documentos/{$documento["arquivo_pasta"]}/{$matricula["idmatricula"]}/{$documentoNome}";
    copy("{$_SERVER["DOCUMENT_ROOT"]}/storage/matriculas_documentos/{$documento["arquivo_pasta"]}/{$matricula["idmatricula"]}/{$documento["arquivo_servidor"]}", $diretorio_arquivo);
    $zip->addFile($diretorio_arquivo, $documentoNome);
}
$zip->close();
header("Content-Type: application/zip");
header("Content-Length: " . filesize("{$matricula['idmatricula']}_{$nome}"));
header("Content-Disposition: attachment; filename=" . basename("{$matricula['idmatricula']}_{$nome}.zip"));
readfile($diretorio_zip);

