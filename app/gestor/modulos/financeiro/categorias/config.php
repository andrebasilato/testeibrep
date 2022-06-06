<?php
$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/assuntos_atendimento_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'remover';
$config['acoes'][4] = 'associar';
$config['acoes'][5] = 'remover_associar';

$config['monitoramento']['onde'] = '49';

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config['banco'] = array(
    'tabela'             => 'categorias',
    'primaria'           => 'idcategoria',
    'campos_insert_fixo' => array(
        'data_cad' => 'now()',
        'ativo'    => "'S'"
    ),
    "campos_unicos"      => array(array(
        "campo_banco" => "nome",
        "campo_form"  => "nome",
        "erro_idioma" => "nome_utilizado"
    )),
);

$config['banco_subcategoria'] = array(
    'tabela'             => 'categorias_subcategorias',
    'primaria'           => 'idsubcategoria',
    'campos_insert_fixo' => array(
        'data_cad' => 'now()',
        'ativo'    => '"S"'
    ),
    'campos_unicos'      => array(array('campo_banco' => 'nome',
                                        'campo_form'  => 'nome||idcategoria',
                                        'erro_idioma' => 'nome_utilizado'
    )),
);