<?php
require_once '../classes/pessoas.class.php';
require_once '../classes/detran.class.php';

if (count($url) == 3 && isset($usuario['idusuario']) && $url[2] == 'detrans') {
    $pessoaObj = new Pessoas();
    $detranObj = new Detran();
    $estados = $pessoaObj->retornarEstados();
    include 'idiomas/' . $config['idioma_padrao'] . '/formulario.php';
    include 'telas/' . $config['tela_padrao'] . '/formulario.php';
}

if (count($url) == 5 && isset($usuario['idusuario']) && $url[4] == 'alterar_estados') {
    $detranObj = new Detran();
    $detranObj->setarSituacaoIntegracao((int)$_POST['idestado'], $_POST['situacao']);
}