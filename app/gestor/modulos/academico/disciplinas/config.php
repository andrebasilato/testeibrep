<?php
$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/menu_completo_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'remover';
$config['acoes'][4] = 'visualizar_cursos';
$config['acoes'][5] = 'associar_cursos';
$config['acoes'][6] = 'remover_cursos';
$config['acoes'][7] = 'visualizar_perguntas';
$config['acoes'][8] = 'editar_perguntas';
$config['acoes'][9] = 'remover_perguntas';
$config['acoes'][10] = 'cadastrar_perguntas';
$config['acoes'][11] = 'clonar_perguntas';

$config['monitoramento']['onde'] = '39';

// Array de configuração de banco de dados 
//(nome da tabela, chave primaria, campos 
// com valores fixos, campos unicos)
$config['banco'] = array(
    'tabela' => 'disciplinas',
    'primaria' => 'iddisciplina',
    'campos_insert_fixo' => array(
        'data_cad' => 'now()', 
        'ativo' => '"S"'
    ),
    'campos_unicos' => array(
        array(
            'campo_banco' => 'nome',
            'campo_form' => 'nome', 
            'erro_idioma' => 'nome_utilizado'
        )
    ),
);