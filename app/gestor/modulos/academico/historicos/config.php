<?php
$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/menu_completo_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'remover';

$config['monitoramento']['onde'] = '253';

/*
Array de configuração de banco de dados
(
    nome da tabela, chave primaria,
    campos com valores fixos,
    campos unicos
)
*/
$config['banco'] = array(
  'tabela'   => Historicos::CURRENT_TABLE,
  'primaria' => Historicos::PRIMARY_KEY,
);