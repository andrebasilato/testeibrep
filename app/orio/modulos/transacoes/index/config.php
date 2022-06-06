<?php

$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = 'fa-usd';
$config['acoes'][1] = 'visualizar';

$config['monitoramento']['onde'] = 2;

$config['banco'] = array(
    'tabela' => 'orio_transacoes',
    'primaria' => 'idtransacao',
    'campos_insert_fixo' => array(
        'data_cad' => 'now()', 
        'ativo' => 'S'
    )										 
);
