<?php

$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1] = 'visualizar';

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
$sqlSindicato .= ' order by nome_abreviado';	

// Array de configuração para a listagem
$config['listagem'] = array(
	array(
		'id' => 'idvisita',
		'tipo' => 'banco',
		'valor' => 'idvisita',
		'busca' => true,
		'tamanho' => 40,
		'coluna_sql' => 'idvisita',
		'busca_class' => 'inputPreenchimentoCompleto',
		'busca_metodo' => 1,
		'variavel_lang' => 'tabela_idvisita',
    ),
    array(
        'id' => 'data_cad',
        'tipo' => 'php',
        'valor' => ' return formataData($linha["data_cad"],"br",0); ',
        'busca' => true,
        'coluna_sql' => 'data_cad',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1,
		'tamanho' => 80,
        'variavel_lang' => 'tabela_data',
    ),
    array(
        'id' => 'nome',
        'tipo' => 'php',
        'valor' => 'if($linha["nome"]) return $linha["nome"]; else return $linha["pessoa"];',
        'busca' => true,
        'evento' => 'maxlength="100"',
		'tamanho' => 200,
        'coluna_sql' => 'nome',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2,
        'variavel_lang' => 'tabela_nome',
    ),
    array(
        'id' => 'email',
        'tipo' => 'php',
        'valor' => 'if($linha["email"]) return $linha["email"]; else return $linha["email_pessoa"];',
        'busca' => true,
        'evento' => 'maxlength="100"',
		'tamanho' => 200,
        'coluna_sql' => 'vv.email',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2,
        'variavel_lang' => 'tabela_email'
    ),
    array(
        'id' => 'telefone',
        'tipo' => 'php',
        'valor' => 'if($linha["telefone"]) return $linha["telefone"]; else return $linha["telefone_pessoa"];',
        'busca' => true,
        'evento' => 'maxlength="100"',
        'coluna_sql' => 'vv.telefone',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2,
		'tamanho' => 100,
        'variavel_lang' => 'tabela_telefone'
    ),
	array(
        'id' => 'celular',
        'tipo' => 'banco',
        'valor' => 'celular',
        'busca' => true,
		'tamanho' => 100,
        'evento' => 'maxlength="100"',
        'coluna_sql' => 'pe.celular',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2,
        'variavel_lang' => 'tabela_celular'
    ),

	array(
		"id" => "idvendedor",
		"variavel_lang" => "tabela_vendedor",
		"tipo" => "banco",
		"coluna_sql" => "ve.nome",
		"valor" => "vendedor",
		'tamanho' => 160,
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),	

	array(
		"id" => "curso",
		"variavel_lang" => "tabela_curso",
		"tipo" => "banco",
		"coluna_sql" => "cu.nome",
		"valor" => "curso",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),

	array(
		"id" => "midia",
		"variavel_lang" => "tabela_midia",
		"tipo" => "banco",
		"coluna_sql" => "miv.nome",
		"valor" => "midia",
		"busca" => true,
		"busca_class" => "inputPreenchimentoCompleto",
		"busca_metodo" => 2
	),

array("id" => "localdevisita",
      "variavel_lang" => "tabela_localdevisita",
      "tipo" => "banco",
      "coluna_sql" => "lov.nome",
      "valor" => "local",
      "busca" => true,
      "busca_class" => "inputPreenchimentoCompleto",
      "busca_metodo" => 2),

	array(
		"id" => "situacao",
		"variavel_lang" => "tabela_situacao",
		'tipo' => 'php',
		'tamanho' => 80,
		'valor' => ' return $GLOBALS["situacao_visita_vendedores"][$GLOBALS["config"]["idioma_padrao"]][$linha["situacao"]]; ',
	),
	array(
		"id" => "matricula",
		"variavel_lang" => "tabela_matricula",
		'tipo' => 'php',
		'valor' => ' return "<a target=\"_blank\" href=\"/gestor/academico/matriculas/".$linha["idmatricula"]."/administrar\">".$linha["idmatricula"]."</a>"; '
	),
	array(
		"id" => "endereco",
		"variavel_lang" => "tabela_endereco",
		"tipo" => "banco",
		"valor" => "geolocalizacao_endereco",
	),
	array(
		"id" => "cidade",
		"variavel_lang" => "tabela_cidade",
		"tipo" => "banco",
		"valor" => "cidade",
	),
	array(
		"id" => "estado",
		"variavel_lang" => "tabela_estado",
		"tipo" => "banco",
		'tamanho' => 100,
		"valor" => "estado",
	),
	array(
		"id" => "observacoes",
		"variavel_lang" => "tabela_observacoes",
		"tipo" => "banco",
		"valor" => "observacoes",
	),	

);


// Array de configuração para a formulario
$config["formulario"] = array(
	array(
		"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
		"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
		"campos" => array( // Campos do formulario                      
			array(
				"id" => "form_tipo_data_filtro",
				"nome" => "q[de_ate|tipo_data_filtro|vv.data_cad]",
				"nomeidioma" => "form_tipo_data_filtro",
				"botao_hide" => true,
				"iddivs" => array("de","ate"),
				"tipo" => "select",
				"iddiv" => "de",
				"iddiv2" => "ate",
				"iddiv_obr" => false,
				"iddiv2_obr" => false,
				"array" => "tipo_data_filtro", // Array que alimenta o select
				"class" => "span3",
				"valor" => "tipo_data_filtro",
				"validacao" => array("required" => "tipo_data_filtro_vazio"),
				"banco" => true,
				"banco_string" => true,
				"sql_filtro" => "array",
				"sql_filtro_label" => "tipo_data_filtro"
			),
                      array(
                            "id" => "form_de",
                            "nome" => "de",
                            "nomeidioma" => "form_de",
                            "tipo" => "input",
                            "class" => "span2",
							"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
							"validacao" => array("required" => "de_vazio"),
                            "datepicker" => true,
                            "input_hidden" => true,
                            ),
                      array(
                            "id" => "form_ate",
                            "nome" => "ate",
                            "nomeidioma" => "form_ate",
                            "tipo" => "input",
                            "class" => "span2",
							"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
							"validacao" => array("required" => "ate_vazio"),
                            "datepicker" => true,
                            "input_hidden" => true,
                            ),

                     array(
						"id" => "form_idsindicato",
						"nome" => "idsindicato",
						"nomeidioma" => "form_idsindicato",
						"tipo" => "select",
						"sql" => $sqlSindicato, // SQL que alimenta o select
						"sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
						"sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
						"valor" => "idsindicato",
						"sql_filtro" => "select * from sindicatos where idsindicato = %",
						"sql_filtro_label" => "nome_abreviado",
						"class" => "span4",
					  ),
					 array(
                            "id" => "idvendedor",
                            "nome" => "q[1|vv.idvendedor]",
                            "nomeidioma" => "form_idvendedor",
                            "tipo" => "select",
                            "sql" => "SELECT idvendedor, nome FROM vendedores WHERE ativo='S' ORDER BY nome", // SQL que alimenta o select
                            "sql_valor" => "idvendedor", // Coluna da tabela que será usado como o valor do options
                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                            "valor" => "idvendedor",
                            "class" => "span3",
                            "sql_filtro" => "select * from vendedores where ativo='S' and idvendedor=%",
                            "sql_filtro_label" => "nome"
                            ),

                        array(
                            "id" => "idcurso",
                            "nome" => "q[1|cu.idcurso]",
                            "nomeidioma" => "form_idcurso",
                            "tipo" => "select",
                            "sql" => "SELECT idcurso, nome FROM cursos WHERE ativo='S' ORDER BY nome", // SQL que alimenta o select
                            "sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                            "valor" => "idcurso",
                            "class" => "span3",
                            "sql_filtro" => "select * from cursos where ativo='S' and idcurso=%",
                            "sql_filtro_label" => "nome"
                            ),


                         array(
                            "id" => "idmidia",
                            "nome" => "q[1|vv.idmidia]",
                            "nomeidioma" => "form_midia",
                            "tipo" => "select",
                            "sql" => "SELECT idmidia, nome FROM midias_visitas WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
                            "sql_valor" => "idmidia", // Coluna da tabela que será usado como o valor do options
                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                            "valor" => "idmidia",
                            "banco" => true,
							"class" => "span2",
                            "sql_filtro" => "select * from midias_visitas where ativo='S' and idmidia=%",
                            "sql_filtro_label" => "nome"
                            ),
                       array(
                            "id" => "idlocal",
                            "nome" => "q[1|vv.idlocal]",
                            "nomeidioma" => "form_local",
                            "tipo" => "select",
                            "sql" => "SELECT idlocal, nome FROM locais_visitas WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
                            "sql_valor" => "idlocal", // Coluna da tabela que será usado como o valor do options
                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                            "valor" => "idlocal",
                            "banco" => true,
							"class" => "span2",
                            "sql_filtro" => "select * from locais_visitas where ativo='S' and idlocal=%",
                            "sql_filtro_label" => "nome"
                        ),
        array(
                'id' => 'idestado',
                "sql" => "SELECT idestado, nome FROM estados ORDER BY nome",
                "nome" => "q[1|e.idestado]",
                "tipo" => "select",
                "valor" => "idestado",
                "class" => "span5",
                "sql_valor" => "idestado",
                "sql_label" => "nome",
				"class" => "span3",
                "nomeidioma" => "form_estado_filtro",
                "sql_filtro" => "select * from estados where idestado=%",
                "sql_filtro_label" => "nome"
            ),
            array(
                'id' => 'idcidade',
                // "sql" => "SELECT idestado, nome FROM estados ORDER BY nome",
                "nome" => "q[1|vv.idcidade]",
                "tipo" => "select",
                "valor" => "idcidade",
                "class" => "span5",
                // "sql_valor" => "idestado",
                // "sql_label" => "nome",
				"class" => "span3",
                "nomeidioma" => "form_cidade_filtro",
                // "sql_filtro" => "select * from estados where idestado=%",
                "sql_filtro_label" => "nome"
            ),
                                                      )
                                    )
                        );




?>