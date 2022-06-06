<?php

$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/menu_completo_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'remover';
$config['acoes'][4] = 'contatos';
$config['acoes'][5] = 'valores_cursos';
$config["acoes"][6] = "acessarcomo";
$config['acoes'][7] = 'pasta_virtual';
$config['acoes'][8] = 'contratos';
$config['acoes'][9] = 'bloquear_acesso';
$config["acoes"][10] = "listar_estados_cidades";
$config["acoes"][11] = "associar_estados_cidades";
$config["acoes"][12] = "remover_estados_cidades";
$config["acoes"][13] = "mensagens";


$config['monitoramento']['onde'] = 37;
$config['monitoramento']['onde_valores_cursos'] = 282;
$config['monitoramento']['onde_escolas_formas_pagamento'] = 284;

$config['banco'] = array(
    'tabela' => 'escolas',
    'primaria' => 'idescola',
    'campos_insert_fixo' => array(
        'data_cad' => 'now()',
        'ativo' => "'S'"
    ),
    'campos_unicos' => array(
        array(
            'campo_banco' => 'nome_fantasia',
            'campo_form' => 'nome_fantasia',
            'erro_idioma' => 'nome_fantasia_utilizado'
            ),
        array(
            'campo_banco' => 'email',
            'campo_form' => 'email',
            'erro_idioma' => 'email_utilizado'
        )
    ),
);

$config['banco_valores_cursos'] = array(
    'tabela' => 'cfcs_valores_cursos',
    'primaria' => 'idvalor_curso',
    'campos_insert_fixo' => array(
        'data_cad' => 'now()',
        'ativo' => "'S'"
    ),
);
