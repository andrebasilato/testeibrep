<?php
$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1]     = 'visualizar';

$config['listagem'] = array(
    array(
        'id' => 'idtopico',
        'variavel_lang' => 'tabela_idtopico',
        'tipo' => 'banco',
        'coluna_sql' => 'at.idtopico',
        'valor' => 'idtopico',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1,
        'tamanho' => 60
    ),
    array(
        'id' => 'periode_de',
        'variavel_lang' => 'tabela_dt_abertura_forum',
        'tipo' => 'php',
        'coluna_sql' => 'af.periode_de',
        'valor' => 'return formataData($linha["periode_de"],"br",0);',
        'tamanho' => '140',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 3
    ),
	array(
        'id' => 'forum',
        'variavel_lang' => 'tabela_forum',
        'tipo' => 'banco',
        'coluna_sql' => 'f.nome',
        'valor' => 'nome',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),  
    array(
        'id' => 'topico',
        'variavel_lang' => 'tabela_topico',
        'tipo' => 'banco',
        'coluna_sql' => 'p.topico',
        'valor' => 'topico',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
	array(
        'id' => 'postagens_alunos',
        'variavel_lang' => 'tabela_postagem',
        'tipo' => 'banco',
        'coluna_sql' => 'p.postagens_alunos',
        'valor' => 'postagens_alunos',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    )   
);


// Array de configuração para a formulario
$config['formulario'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
			array(
                'id' => 'idava',
                "sql" => "select idava, nome FROM avas WHERE ativo='S' ORDER BY nome",
                'nome' => 'q[1|af.idava]',
                'tipo' => 'select',
                'valor' => 'idava',
				"class" => "span5",
				"sql_valor" => "idava",
                "sql_label" => "nome",
                'nomeidioma' => 'form_idava',
				'sql_filtro' => 'select * from avas where idava=%',
                'sql_filtro_label' => 'nome',
            ),
			array(
                'id' => 'idforum',
                "sql" => "select idforum, nome FROM avas_foruns WHERE ativo='S' ORDER BY nome",
                'nome' => 'q[1|af.idforum]',
                'tipo' => 'select',
                'valor' => 'idava',
				"class" => "span5",
				"sql_valor" => "idforum",
                "sql_label" => "nome",
                'nomeidioma' => 'form_forum',
				'sql_filtro' => 'select * from avas_foruns where idforum=%',
                'sql_filtro_label' => 'nome',
            ),
			  array(
					"id" => "form_tipo",
					"nome" => "tipo", 
					"nomeidioma" => "form_tipo",
					"tipo" => "select",
					"array" => "sim_nao",
					"class" => "span2",
					"valor" => "tipo",					 
					),
            array(
                'id' => 'form_tipo_data_filtro',
                'nome' => 'q[de_ate|tipo_data_filtro|af.data_cad]',
                'tipo' => 'select',
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_filtro',
                'banco' => true,
                'iddiv' => 'de',
                'iddiv2' => 'ate',
                'iddivs' => array(
                    'de',
                    'ate'
                ),
                'iddiv_obr' => true,
                'validacao' => array(
                    'required' => 'tipo_data_filtro_vazio'
                ),
                'nomeidioma' => 'form_tipo_data_filtro',
                'botao_hide' => true,
                'iddiv2_obr' => true,
                'sql_filtro' => 'array',
                'banco_string' => true,
                'sql_filtro_label' => 'tipo_data_filtro'
            ),
           array(
                'id' => 'form_de',
                'nome' => 'de',
				'valor' => 'de',
                'tipo' => 'input',
                'class' => 'span2',
				"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
				"validacao" => array("required" => "de_vazio"),
                'nomeidioma' => 'form_de',
                'datepicker' => true,
                'input_hidden' => true
            ),
            array(
                'id' => 'form_ate',
                'nome' => 'ate',
				'valor' => 'ate',
                'tipo' => 'input',
                'class' => 'span2',
				"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
				"validacao" => array("required" => "ate_vazio"),
                'nomeidioma' => 'form_ate',
                'datepicker' => true,
                'input_hidden' => true
            )
        )
    )
);