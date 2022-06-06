<?php

$workflow_parametros_contas = array(
    0 => array(
        "idopcao" => 1,
        "tipo" => "visualizacao",
        'combo' => 'Gestor',
        "nome" => "Alterar situação",
        "parametros" => array(),
    ),
    1 => array(
        'idopcao' => 2,
        'tipo' => 'prerequisito',
        'nome' => 'Ter Data de pagamento',
        'parametros' => array(),
    ),
    2 => array(
        'idopcao' => 3,
        'tipo' => 'prerequisito',
        'nome' => 'Ter Valor de pagamento',
        'parametros' => array(),
    ),
    3 => array(
        "idopcao" => 4,
        "tipo" => "visualizacao",
        'combo' => 'Atendente',
        "nome" => "Alterar situação",
        "parametros" => array(),
    )
);
