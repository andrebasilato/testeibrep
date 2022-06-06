<?php
if ($usuario["orio"] == 'N') {
    $_POST["msg"] = "sem_acesso_orio";
    incluirLib("login", $config);
    exit();
}

include "../classes/orio/Inicio.php";
$config['orio_interfaces'] = $orio_interfaces;

$linhaObjInicio = new Inicio();

$dias = 30;
$arrayTransacoes = $linhaObjInicio->totalTransacoesDiario($dias);
$datas_transacoes = join(', ', $arrayTransacoes["datas"]);
$totais_transacoes = join(', ', $arrayTransacoes["totais"]);
$grafico_totais_transacoes = join(', ', $arrayTransacoes["totais_geral"]);

$totalTransacoes = $linhaObjInicio->totalTransacoes();
$totalTransacoes_concluidas = $linhaObjInicio->totalTransacoes(2);
$totalTransacoes_pendentes = $linhaObjInicio->totalTransacoes(1);
$totalTransacoes_erros = $linhaObjInicio->totalTransacoes(3);
$totalTransacoes_entrada = $linhaObjInicio->totalTransacoes(null, 'E');
$totalTransacoes_saida = $linhaObjInicio->totalTransacoes(null, 'S');

require "idiomas/" . $config["idioma_padrao"] . "/index.php";
require "telas/" . $config["tela_padrao"] . "/index.php";
