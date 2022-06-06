<?php

$config['link_manual_funcionalidade'] = '/gestor/categoria/51/resposta-padrao-dos-atendimentos.html';

$config['funcionalidade'] = 'funcionalidade';
$config['funcionalidade_icone_32'] = '/assets/icones/preto/32/respostas_padrao_32.png';
$config['acoes'][1] = 'visualizar';
$config['acoes'][2] = 'cadastrar_modificar';
$config['acoes'][3] = 'remover';
$config['acoes'][4] = 'assuntos';

$config['monitoramento']['onde'] = '88';

// Array de configuração de banco de dados
// (
// 		nome da tabela,
// 		chave primaria,
// 		campos com valores fixos,
// 		campos unicos
// )
$config['banco'] = array(
	'tabela' => 'atendimentos_respostas_automaticas',
	'primaria' => 'idresposta',
	'campos_insert_fixo' => array(
		'data_cad' => 'now()',
		'ativo' => "'S'"
	),
	'campos_unicos' => array(
		array(
		    'campo_banco' => 'nome',
			'campo_form' => 'nome',
			'erro_idioma' => 'nome_utilizado'
		)
	),
);