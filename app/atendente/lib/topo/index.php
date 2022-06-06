<?php
include_once('../classes/matriculas.class.php');
$matriculaObj = new Matriculas();

$situacaoInicial = $matriculaObj->retornarSituacaoInicial();
$situacaAtiva = $matriculaObj->retornarSituacaoAtiva();
$situacaoFim = $matriculaObj->retornarSituacaoConcluida();
$situacaoCancelada = $matriculaObj->retornarSituacaoCancelada();

include('idiomas/' . $config['idioma_padrao'] . '/index.php');
include('telas/' . $config['tela_padrao'] . '/index.php');