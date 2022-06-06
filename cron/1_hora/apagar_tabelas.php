<?php

require_once $caminhoApp . '/app/classes/core.class.php';
require_once $caminhoApp . '/app/includes/config.php';
require_once $caminhoApp . '/app/especifico/inc/config.especifico.php';
require_once $caminhoApp . '/app/includes/funcoes.php';

$data = (new \DateTime())->modify('-3 months');
define('DATA_MINIMA_VENCIMENTO', $data->format('Y-m-d 00:00:00'));

$tabelas = [
    'orio_transacoes' => 'data_cad',
];

$tabelasLog = [
];

$core = new Core;

if (! empty($tabelasLog)) {
    foreach ($tabelasLog as $tabelaLog => $tabela) {
        $sql = 'DELETE log FROM ' . $tabelaLog . ' log
                INNER JOIN ' . $tabela . ' monitora ON (log.idmonitora = monitora.idmonitora)
                WHERE monitora.data_cad < "' . DATA_MINIMA_VENCIMENTO . '"';

        $core->executaSql($sql);
    }
}

if (! empty($tabelas)) {
    foreach ($tabelas as $tabela => $colunaData) {
        $sql = 'DELETE FROM ' . $tabela . '
                WHERE ' . $colunaData . ' < "' . DATA_MINIMA_VENCIMENTO . '"';

        $core->executaSql($sql);
    }
}
