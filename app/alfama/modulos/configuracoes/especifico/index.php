<?php

include 'config.php';

if ($_POST['acao'] == 'salvar') {
    $escreveu = file_put_contents(
        DIR_APP . '/especifico/inc/config.especifico.php',
        $_POST['dadosEspecifico']
    );

    if ($escreveu) {
        require DIR_APP . '/especifico/inc/config.especifico.php';
    }
}
if (isset($usuario['idusuario'])) {
    include 'idiomas/' . $config['idioma_padrao'] . '/formulario.php';
    include 'telas/' . $config['tela_padrao'] . '/formulario.php';
}
