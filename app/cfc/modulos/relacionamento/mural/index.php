<?php

include '../classes/murais.class.php';
include 'config.php';
include 'config.formulario.php';
include 'config.listagem.php';

//Incluimos o arquivo com variaveis padrão do sistema.
include 'idiomas/'.$config['idioma_padrao'].'/idiomapadrao.php';

$linhaObj = new Murais();
$linhaObj->Set('idescola', $usuario['idescola']);
$linhaObj->Set('monitora_onde', $config['monitoramento']['onde']);

if ($url[3] && $url[4] == 'visualizar') {
    $linhaObj->Set('id', intval($url[3]));
    $linhaObj->Set('campos', '*');
    $linha = $linhaObj->RetornarPreviewMuralDisponiveis('idescola', $usuario['idescola']);

    if ($linha) {
        include 'idiomas/'.$config['idioma_padrao'].'/visualizar.php';
        include 'telas/'.$config['tela_padrao'].'/visualizar.php';
        exit;
    } else {
        header('Location: /'.$url[0].'/'.$url[1].'/'.$url[2].'');
        exit();
    }
} elseif ($url[3] && $url[4] == 'downloadArquivo') {
    $linhaObj->Set('id', intval($url[5]));
    $arquivo = $linhaObj->RetornarArquivoDownload();
    include 'telas/'.$config['tela_padrao'].'/download_arquivo.php';
    break;
} else {
    $linhaObj->Set('pagina', $_GET['pag']);
    if (! $_GET['ordem']) {
        $_GET['ordem'] = 'desc';
    }
    $linhaObj->Set('ordem', $_GET['ord']);
    if (! $_GET['qtd']) {
        $_GET['qtd'] = 30;
    }
    $linhaObj->Set('limite', intval($_GET['qtd']));
    if (! $_GET['cmp']) {
        $_GET['cmp'] = $config['banco']['primaria'];
    }
    $linhaObj->Set('ordem_campo', $_GET['cmp']);
    $linhaObj->Set('campos', 'm.idmural, m.titulo, m.resumo, m.data_cad,mf.data_lido');
    $dadosArray = $linhaObj->ListarTodasDisponiveis('idescola', $usuario['idescola']);
    include 'idiomas/'.$config['idioma_padrao'].'/index.php';
    include 'telas/'.$config['tela_padrao'].'/index.php';
}
