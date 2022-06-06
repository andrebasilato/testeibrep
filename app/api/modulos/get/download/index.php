<?php  
// MODELO DE USO: /api/get/download/pessoas/imagem.png
// Opcional:  /api/get/download/pessoas/imagem.png?nome=titulo_arquivo&tipo=application/pdf

$arquivo = $url[4]; 
$pasta = str_replace("|","/", $url[3]);

$tipo = $_GET['tipo']; 

$nome  = $_GET['nome']; 
if(!$nome) $nome = $arquivo;

$caminho = $_SERVER['DOCUMENT_ROOT']."/storage/".$pasta; 
$localArquivo = $caminho."/".$arquivo;

if (file_exists($localArquivo)) {
	header('Content-type: '.$tipo);
	header('Content-Disposition: attachment; filename="'.basename($nome).'"');
	header('Content-Length: '.filesize($localArquivo));
	header('Expires: 0');
	header('Pragma: no-cache');
	readfile($localArquivo);
} else {
	echo 'Arquivo não encontrado!';
}