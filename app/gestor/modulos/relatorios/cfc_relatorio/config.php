<?php

$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1] = 'visualizar';

$sqlSindicato = 'SELECT idsindicato, nome_abreviado FROM sindicatos WHERE ativo = "S"';
if ($_SESSION['adm_gestor_sindicato'] != 'S') {
	$sqlSindicato .= ' AND idsindicato IN ('.$_SESSION['adm_sindicatos'].')';
}
$sqlSindicato .= ' ORDER BY nome_abreviado';

$config['listagem'] = array(
	array(
		'id' => 'tabela_idescola',
		'variavel_lang' => 'tabela_idescola',
		'tipo' => 'banco',
		'valor' => 'idescola',
		'tamanho' => 60
	),
	array(
		'id' => 'tabela_nome_fantasia',
		'variavel_lang' => 'tabela_nome_fantasia',
		'tipo' => 'banco',
		'valor' => 'nome_fantasia'
	),
    array(
        'id' => 'tabela_razao_social',
        'variavel_lang' => 'tabela_razao_social',
        'tipo' => 'banco',
        'valor' => 'razao_social'
    ),
	array(
		'id' => 'tabela_documento',
		'variavel_lang' => 'tabela_documento',
		'tipo' => 'banco',
		'valor' => 'documento'
	),
	array(
		'id' => 'tabela_fax',
		'variavel_lang' => 'tabela_fax',
		'tipo' => 'banco',
		'valor' => 'fax'
	),
	array(
		'id' => 'tabela_telefone',
		'variavel_lang' => 'tabela_telefone',
		'tipo' => 'banco',
		'valor' => 'telefone'
	),	array(
		'id' => 'tabela_celular_administrador',
		'variavel_lang' => 'tabela_celular_administrador',
		'tipo' => 'banco',
		'valor' => 'celular_administrador'
	),
	array(
		'id' => 'tabela_email',
		'variavel_lang' => 'tabela_email',
		'tipo' => 'banco',
		'valor' => 'email'
	),
	array(
		'id' => 'tabela_estado',
		'variavel_lang' => 'tabela_estado',
		'tipo' => 'banco',
		'valor' => 'estado'
	),
    array(
        'id' => 'tabela_cep',
        'variavel_lang' => 'tabela_cep',
        'tipo' => 'banco',
        'valor' => 'cep'
    ),
    array(
        'id' => 'tabela_logradouro',
        'variavel_lang' => 'tabela_logradouro',
        'tipo' => 'banco',
        'valor' => 'logradouro'
    ),
    array(
        'id' => 'tabela_endereco',
        'variavel_lang' => 'tabela_endereco',
        'tipo' => 'banco',
        'valor' => 'endereco'
    ),
    array(
        'id' => 'tabela_bairro',
        'variavel_lang' => 'tabela_bairro',
        'tipo' => 'banco',
        'valor' => 'bairro'
    ),
    array(
        'id' => 'tabela_numero',
        'variavel_lang' => 'tabela_numero',
        'tipo' => 'banco',
        'valor' => 'numero'
    ),
    array(
        'id' => 'tabela_complemento',
        'variavel_lang' => 'tabela_complemento',
        'tipo' => 'banco',
        'valor' => 'complemento'
    ),
	array(
		'id' => 'tabela_cidade',
		'variavel_lang' => 'tabela_cidade',
		'tipo' => 'banco',
		'valor' => 'cidade'
	),
	array(
		'id' => 'tabela_sindicato',
		'variavel_lang' => 'tabela_sindicato',
		'tipo' => 'banco',
		'valor' => 'sindicato'
	)
);


$config['formulario'] = array(
	array(
		'fieldsetid' => 'dadosdoobjeto',
		'legendaidioma' => 'legendadadosdados',
		'campos' => array(
			array(
				'id' => 'form_nome_fantasia',
				'nome' => 'q[2|e.nome_fantasia]',
				'nomeidioma' => 'form_nome_fantasia',
				'tipo' => 'input',
				'valor' => 'nome_fantasia',
				'class' => 'span5',
				'evento' => 'maxlength="100"'
			),
			array(
				'id' => 'idsindicato',
				'nome' => 'q[1|e.idsindicato]',
				'nomeidioma' => 'form_idsindicato',
				'tipo' => 'select',
				'sql' => $sqlSindicato,
				'sql_valor' => 'idsindicato',
				'sql_label' => 'nome_abreviado',
				'valor' => 'idsindicato',
				'class' => 'span3',
				'sql_filtro' => 'SELECT * FROM sindicatos WHERE idsindicato = %',
				'sql_filtro_label' => 'nome_abreviado'
			),
			array(
				'id' => 'idestado',
				'nome' => 'q[1|e.idestado]',
				'nomeidioma' => 'form_idestado',
				'tipo' => 'select',
				'sql' => 'SELECT idestado, nome FROM estados ORDER BY nome',
				'sql_valor' => 'idestado',
				'sql_label' => 'nome',
				'valor' => 'idestado',
				'class' => 'span3',
				'sql_filtro' => 'SELECT * FROM estados WHERE idestado = %',
				'sql_filtro_label' => 'nome'
			),
			array(
				'id' => 'idcidade',
				'nome' => 'q[1|e.idcidade]',
				'nomeidioma' => 'form_idcidade',
				'json' => true,
				'json_idpai' => 'idestado',
				'json_url' => '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/ajax_cidades/',
				'json_input_pai_vazio' => 'form_selecione_estado',
				'json_input_vazio' => 'form_selecione_cidade',
				'json_campo_exibir' => 'nome',
				'tipo' => 'select',
				'valor' => 'idcidade',
				'class' => 'span3',
				'sql_filtro' => 'SELECT * FROM cidades WHERE idcidade = %',
				'sql_filtro_label' => 'nome'
			),
		)
	)
);
