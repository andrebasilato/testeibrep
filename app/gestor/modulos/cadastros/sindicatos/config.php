<?php

$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/menu_completo_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'remover';
$config['acoes'][4] = 'acesso_ava';
$config["acoes"][5] = "enviar_arquivos_pasta";
$config["acoes"][6] = "alterar_arquivos_pasta";
$config["acoes"][7] = "remover_arquivos_pasta";
$config["acoes"][8] = "valores_cursos";

$config['monitoramento']['onde'] = '18';
$config['monitoramento']['onde_valores_cursos'] = 288;
$config['monitoramento']['onde_sindicatos_formas_pagamento'] = 289;

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config['banco'] = array(
    'tabela' => 'sindicatos',
    'primaria' => 'idsindicato',
    'campos_insert_fixo' => array(
        'data_cad' => 'now()',
        'ativo' => "'S'",
    )
);

$config['banco_valores_cursos'] = array(
    'tabela' => 'sindicatos_valores_cursos',
    'primaria' => 'idvalor_curso',
    'campos_insert_fixo' => array(
        'data_cad' => 'now()',
        'ativo' => "'S'"
    ),
);