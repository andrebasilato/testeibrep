<?php

if ($detect->isMobile() && !$detect->isTablet()) {
    $config['tela_padrao'] = 'mobile';

    if ($_GET['classico'] == 'true') {
        $config['tela_padrao'] = 'desktop';
        $_SESSION['classico'] = true;
    } elseif ($_GET['classico'] == 'false') {
        $config['tela_padrao'] = 'mobile';
        unset($_SESSION['classico']);
    } elseif ($_SESSION['classico']) {
        $config['tela_padrao'] = 'desktop';
    }
}

$url = addslashes(strip_tags(rawurldecode($_SERVER['REQUEST_URI']))); //Salva a url do browser na variavel $url
$get_array = explode('?', $url); // Separando os GETS
$url = explode('/', $get_array[0]); // Separa a url por a "/"

$qtdUrl = count($url);

for ($i = 0; $i <= $qtdUrl; ++$i) {
    if ($url[0] != 'cfc') {
        array_shift($url); // O primeiro índice sempre será vazio  
    }
}

// Correção do BUG ativo e não ativo. (Manzano) ARMENGUE!!!
// Somente para facilitar o uso
if ($_POST && !$_POST['ativo_painel']) {
    $_POST['ativo_painel'] = 'S';
}

$config['tituloPainel'] = 'Administrativo';
$config['tabela_monitoramento'] = 'monitora_escola';
$config['tabela_monitoramento_primaria'] = 'idescola';
$config['tabela_monitoramento_log'] = 'monitora_escola_log';
