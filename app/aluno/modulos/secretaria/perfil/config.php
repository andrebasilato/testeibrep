<?php
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';

$config['monitoramento']['onde'] = '16';

$config['banco'] = array(
	'tabela' => 'pessoas',
	'primaria' => 'idpessoa',
	'campos_insert_fixo' => array(
	'data_cad' => 'now()', 
	'ativo' => '"S"'
),
'campos_unicos' => array(
	array(
		'campo_banco' => 'documento',
		'campo_form' => 'documento', 
		'erro_idioma' => 'cpf_utilizado',
		'campo_php' => 'return str_replace(array(".", "-", "/"),"","%s")'
	),
	array('campo_banco' => 'email', 
		'campo_form' => 'email', 
		'erro_idioma' => 'email_utilizado',)
	),											 
);