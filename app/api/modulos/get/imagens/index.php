<?php

// MODELO DE USO: /api/get/imagens/pessoas/300/300/imagem.png
// Opcional:  /api/get/imagens/pessoas/300/300/imagem.png?qualidade=100

$arquivo = $url[6];
$modulo = str_replace("|","/", $url[3]);

$largura = $url[4];
if($largura == "x") $largura = null;

$altura     = $url[5];
if($altura == "x") $altura = null;

$qualidade  = $_GET['qualidade'];
if(!$qualidade) $qualidade = 100;

if($_GET['t'] == "crop") {
	$tipo = 'crop';
} elseif($_GET['t'] == "fill") {
	$tipo = 'fill';
} else {
	$tipo = null;
}

$modulo = preg_replace("/[^a-zA-Z0-9.]\//", "_", $modulo);
$largura = preg_replace("/[^a-zA-Z0-9.]\//", "_", $largura);
$altura = preg_replace("/[^a-zA-Z0-9.]\//", "_", $altura);

$caminho = $_SERVER['DOCUMENT_ROOT']."/storage/cache_imagens/".$modulo."/".$largura."x".$altura."/".$tipo;
$caminho_original = $_SERVER['DOCUMENT_ROOT']."/storage/".$modulo;
$imagem = $caminho."/".$arquivo;

if(!$arquivo){
	$arquivo  = "semimagem_api.jpg";
	$caminho_original = $_SERVER['DOCUMENT_ROOT']."/assets/img";
	$imagem = $caminho."/".$arquivo;
}

if(!$imagem) $imagem = NULL;

if (!file_exists($imagem)) {
    // Criandos as pastas
    mkdir($_SERVER['DOCUMENT_ROOT'] . "/storage/cache_imagens/" . $modulo, 0777);
    mkdir($_SERVER['DOCUMENT_ROOT'] . "/storage/cache_imagens/" . $modulo . "/" . $largura . "x" . $altura, 0777);

    include('m2brimagem.class.php');

    $oImg = new m2brimagem($caminho_original . "/" . $arquivo);
    $valida = $oImg->valida();
    if ($valida == 'OK') {
        $tipo = 'crop';
        if ($oImg->extensao == 'png') {
            $tipo = 'fill';
        }
        if ($_GET['reconhecimento']) {
            $tipo = null;
        }

        $oImg->redimensiona($largura, $altura, $tipo);
        $oImg->grava($imagem, $qualidade);
    } else {
        die($valida);
    }
}

header('Content-type: image/jpg');
header('Content-length: '.filesize($imagem));
readfile($imagem);