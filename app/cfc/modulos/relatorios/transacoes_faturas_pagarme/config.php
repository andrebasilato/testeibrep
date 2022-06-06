<?php

$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1] = 'visualizar';

$sqlEscola = 'SELECT idescola, nome_fantasia FROM escolas WHERE ativo = "S"';
if ($_SESSION['adm_gestor_sindicato'] != 'S') {
	$sqlEscola .= ' AND idsindicato IN (' . $_SESSION['adm_sindicatos'] . ')';	
}
$sqlEscola .= ' order by nome_fantasia';

$config['listagem'] = array(
	array(
		'id' => 'tabela_idconta',
		'variavel_lang' => 'tabela_idconta',
		'tipo' => 'banco',
		'valor' => 'idconta',
		'tamanho' => 60
	),
	array(
		'id' => 'tabela_escola', 
		'variavel_lang' => 'tabela_escola', 
		'tipo' => 'banco', 
		'valor' => 'escola'
	),
	array(
		'id' => 'tabela_valor', 
		'variavel_lang' => 'tabela_valor', 
		'tipo' => 'php',
		'valor' => 'return "R$ " . number_format($linha["valor"], 2, ",", ".") . "</span>";',
	),
	array(
		'id' => 'tabela_data_vencimento', 
		'variavel_lang' => 'tabela_data_vencimento', 
		'tipo' => 'php',
		'valor' => 'return formataData($linha["data_vencimento"], "br", 0);',
	),
	array(
		'id' => 'tabela_qnt_matriculas', 
		'variavel_lang' => 'tabela_qnt_matriculas', 
		'tipo' => 'banco', 
		'valor' => 'qnt_matriculas',
	),
	array(
		'id' => 'tabela_situacao', 
		'variavel_lang' => 'tabela_situacao', 
		'tipo' => 'banco', 
		'valor' => 'situacao'
	),
	array(
		'id' => 'tabela_data_modificacao_fatura', 
		'variavel_lang' => 'tabela_data_modificacao_fatura', 
		'tipo' => 'php',
		'valor' => 'return formataData($linha["data_modificacao_fatura"], "br", 1);',
	),
	array(
		'id' => 'tabela_pagarme_id',
		'variavel_lang' => 'tabela_pagarme_id',
		'tipo' => 'banco',
		'valor' => 'pagarme_id',
		'tamanho' => 80
	),
	array(
		'id' => 'tabela_statusPagarme', 
		'variavel_lang' => 'tabela_statusPagarme',
		'tipo' => 'php',
		'valor' => 'return $GLOBALS["statusTransacaoPagarme"][$GLOBALS["config"]["idioma_padrao"]][$linha["statusPagarme"]];',
	)
);

//Cria outro array com os tipos de datas para usar para data de cadastro, pois nunca terá dado no futuro
$tipo_data_filtro_sem_data_futura[$config['idioma_padrao']] = $tipo_data_filtro[$config['idioma_padrao']];
unset($tipo_data_filtro_sem_data_futura[$config['idioma_padrao']]['MPR']);

$config['formulario'] = array(
	array(
		'fieldsetid' => 'dadosdoobjeto',
		'legendaidioma' => 'legendadadosdados',
		'campos' => array(
			array(
				'id' => 'form_tipo_data_cad',
				'nome' => 'q[de_ate|tipo_data_cad|c.data_cad]',
				'nomeidioma' => 'form_tipo_data_cad',
				'botao_hide' => true,
				'iddivs' => array('data_cad_de','data_cad_ate'),
				'tipo' => 'select',
				'iddiv' => 'data_cad_de',
				'iddiv2' => 'data_cad_ate',
				'iddiv_obr' => true,
				'iddiv2_obr' => true,
				'array' => 'tipo_data_filtro_sem_data_futura', // Array que alimenta o select
				'class' => 'span3', 
				'valor' => 'tipo_data_filtro',
				'validacao' => array('required' => 'tipo_data_cad_vazio'),
				'banco' => true,
				'banco_string' => true, 
				'sql_filtro' => 'array',
				'sql_filtro_label' => 'tipo_data_filtro'
			),
			array(
				'id' => 'form_data_cad_de',
				'nome' => 'data_cad_de', 
				'nomeidioma' => 'form_data_cad_de',
				'tipo' => 'input',
				'class' => 'span2',
				'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_cad_de","form_data_cad_ate")\'',
				'validacao' => array('required' => 'data_cad_de_vazio'),
				'datepicker' => true,
				'input_hidden' => true,
			),	
			array(
				'id' => 'form_data_cad_ate',
				'nome' => 'data_cad_ate', 
				'nomeidioma' => 'form_data_cad_ate',
				'tipo' => 'input',
				'class' => 'span2',
				'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_cad_de","form_data_cad_ate")\'',
				'validacao' => array('required' => 'data_cad_ate_vazio'),
				'datepicker' => true,
				'input_hidden' => true,
			),
			array(
				'id' => 'idsituacao',
				'nome' => 'situacao',
				'nomeidioma' => 'form_idsituacao',
				'tipo' => 'checkbox',
				'sql' => 'SELECT idsituacao, nome FROM contas_workflow WHERE ativo="S"',
				'sql_valor' => 'idsituacao',
				'sql_label' => 'nome',
				'sql_ordem_campo' => 'nome',
				'sql_ordem' => 'ASC',
				'valor' => 'idsituacao',
				'class' => 'span3',
				'sql_filtro' => 'SELECT * FROM contas_workflow WHERE idsituacao = %',
				'sql_filtro_label' => 'nome'
			),
			array(
				'id' => 'statusPagarme',
				'nome' => 'statusPagarme',
				'nomeidioma' => 'form_statusPagarme',
				'tipo' => 'checkbox',
				'array' => 'statusTransacaoPagarme',
				'valor' => 'idsituacao',
				'class' => 'span3'
			),
			
			array(
				'id' => 'form_tipo_data_vencimento',
				'nome' => 'q[de_ate|tipo_data_vencimento|c.data_vencimento]',
				'nomeidioma' => 'form_tipo_data_vencimento',
				'botao_hide' => true,
				'iddivs' => array('data_vencimento_de','data_vencimento_ate'),
				'tipo' => 'select',
				'iddiv' => 'data_vencimento_de',
				'iddiv2' => 'data_vencimento_ate',
				'iddiv_obr' => true,
				'iddiv2_obr' => true,
				'array' => 'tipo_data_filtro', // Array que alimenta o select
				'class' => 'span3', 
				'valor' => 'tipo_data_filtro',
				'banco' => true,
				'banco_string' => true, 
				'sql_filtro' => 'array',
				'sql_filtro_label' => 'tipo_data_filtro'
			),
			array(
				'id' => 'form_data_vencimento_de',
				'nome' => 'data_vencimento_de', 
				'nomeidioma' => 'form_data_vencimento_de',
				'tipo' => 'input',
				'class' => 'span2',
				'datepicker' => true,
				'input_hidden' => true,
			),	
			array(
				'id' => 'form_data_vencimento_ate',
				'nome' => 'data_vencimento_ate', 
				'nomeidioma' => 'form_data_vencimento_ate',
				'tipo' => 'input',
				'class' => 'span2',
				'datepicker' => true,
				'input_hidden' => true,
			),
		)
	)
);
