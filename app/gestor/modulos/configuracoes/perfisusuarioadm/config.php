<?php
$config['link_manual_funcionalidade'] = '';

$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/perfis_acessos_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'remover';

$config['monitoramento']['onde'] = '2';

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config['banco'] = array(
    'tabela' => 'usuarios_adm_perfis',
    'primaria' => 'idperfil',
    'campos_insert_fixo' => array(
        'data_cad' => 'now()',
        'ativo' => '1'
    ),
    'campos_unicos' => array(
        array(
            'campo_banco' => 'nome',
            'campo_form' => 'nome',
            'erro_idioma' => 'nome_utilizado'
        )
    ),
    'campos_sql_fixo' => array(
        'permissoes' => 'return serialize($_POST["permissoes"]);'
    )
);