<?php
$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/interesses_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'remover';
$config['acoes'][4] = 'mensagens';
$config["acoes"][5] = "iteracoes";

$config['monitoramento']['onde'] = '81';

$config['banco'] = array(
	'tabela' => 'visitas_vendedores',
	'primaria' => 'idvisita',
	'campos_insert_fixo' => array(
		'data_cad' => 'now()', 
		'ativo' => "'S'"
	),
);