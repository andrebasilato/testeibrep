<?php
$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/menu_completo_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'administrar_diplomas';
$config['acoes'][4] = 'remover';

$config['monitoramento']['onde'] = '172';

// Array de configuração de banco de dados
// (
//   nome da tabela, chave primaria,
//   campos com valores fixos,
//   campos unicos
// )
$config['banco'] = array(
    'tabela' => Folhas_Registros_Diplomas::CURRENT_TABLE,
    'primaria' => 'idfolha',
    'campos_insert_fixo' => array(
        'data_cad' => 'now()',
        'ativo' => '"S"'
    )
);