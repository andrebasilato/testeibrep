<?php
$url_simulado[1] = "http://www.cursotti.com.br/prova/index.php";
//$url_simulado[21] = "http://www.ibrep.com.br/simulados/avaliacao_imob/questao.php";
$url_simulado[28] = "http://ibrep.com.br/simulados/terrenos_marinha/";

$ava = $GLOBALS["ava"];
$ava["modulos"] = unserialize($ava["modulos"]);

include('idiomas/'.$config['idioma_padrao'].'/index.php');
include('telas/'.$config['tela_padrao'].'/index.php');