<?php
$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/repasses_32.png';

$config['acoes'][1] = 'visualizar';
$config['acoes'][4] = 'visualizar_ficha';

$config['monitoramento']['onde'] = '52';

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config['banco'] = array(
                        'tabela' => 'contas',
                        'primaria' => 'idconta',
                        'campos_insert_fixo' => array(
                                                    'data_cad' => 'NOW()',
                                                    'ativo' => '"S"'
                                                )
                    );