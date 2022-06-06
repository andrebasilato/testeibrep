<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$config["banco"] = array("tabela" => "pessoas",
						 "primaria" => "idpessoa",
						);

// Array de configuração para a listagem	
$config["listagem"] = array(
	array(
		"id" => "tabela_idaluno", // Id do atributo
		"variavel_lang" => "tabela_idaluno", // Referencia a variavel de idioma
		"tipo" => "banco", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
		"coluna_sql" => "p.idpessoa", // Nome da coluna no banco de dados
		"valor" => 'idpessoa',
		"tamanho" => "60"
	),

							array("id" => "tabela_aluno", 
							  	  "variavel_lang" => "tabela_aluno", 
								  "tipo" => "banco", 
								  "coluna_sql" => "p.nome", 
								  "valor" => "aluno"), 
								  
							array("id" => "tabela_idmatricula", 
							  	  "variavel_lang" => "tabela_idmatricula", 
								  "tipo" => "banco", 
								  "coluna_sql" => "m.idmatricula", 
								  "valor" => "idmatricula"), 
								  
							array("id" => "tabela_sindicato", 
								  "variavel_lang" => "tabela_sindicato", 
								  "tipo" => "banco", 
								  "coluna_sql" => "i.sindicato", 
								  "valor" => "sindicato"),	
								  
							array("id" => "tabela_curso", 
								  "variavel_lang" => "tabela_curso", 
								  "tipo" => "banco", 
								  "coluna_sql" => 'c.curso', 
								  "valor" => 'curso'),
								  
							array("id" => "tabela_financeiro", 
								  "variavel_lang" => "tabela_financeiro", 
								  "tipo" => "tabela_financeiro"),
								  
							array("id" => "tabela_mensagens", 
								  "variavel_lang" => "tabela_mensagens", 
								  "tipo" => "tabela_mensagens"),								  
							
								  );

						
			// Array de configuração para a formulario			
			$config["formulario"] = array(
							  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario	
													  array(
															"id" => "form_idpessoa",
															"nome" => "q[1|p.idpessoa]", 
															"nomeidioma" => "form_idpessoa",
															"tipo" => "input",
															"valor" => "idpessoa", 
															"class" => "span2",
															"evento" => "maxlength='100'",
															"numerico" => true,
															//"validacao" => array("required" => "matricula_vazio"),
															),
													  array(
															"id" => "form_idmatricula",
															"nome" => "q[1|m.idmatricula]", 
															"nomeidioma" => "form_idmatricula",
															"tipo" => "input",
															"valor" => "idmatricula", 
															"class" => "span2",
															"evento" => "maxlength='100'",
															"numerico" => true,
															//"validacao" => array("required" => "matricula_vazio"),
															),
													  array(
															"id" => "idsindicato",
															"nome" => "idsindicato",
															"nomeidioma" => "form_idsindicato",
															"tipo" => "select",
															"sql" => "SELECT idsindicato, nome_abreviado FROM sindicatos where ativo = 'S' ORDER BY nome_abreviado", // SQL que alimenta o select
															"sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
															"sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
															"valor" => "idsindicato",
															"sql_filtro" => "select * from sindicatos where idsindicato = %",
															"sql_filtro_label" => "nome_abreviado"															
															),
													  array(
														'id' => 'form_tipo_data_filtro',
														'nome' => 'q[de_ate|tipo_data_filtro|cl.data_cad]',
														'tipo' => 'select',
														'array' => 'tipo_data_filtro',
														'class' => 'span3',
														'valor' => 'tipo_data_filtro',
														'banco' => true,
														'iddiv' => 'de',
														'iddiv2' => 'ate',
														'iddivs' => array('de','ate'),
														'iddiv_obr' => true,
														//'validacao' => array('required' => 'tipo_data_filtro_vazio'),
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
														//"validacao" => array("required" => "de_vazio"),
														'nomeidioma' => 'form_de',
														'mascara' => '99/99/9999',
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
														//"validacao" => array("required" => "ate_vazio"),
														'nomeidioma' => 'form_ate',
														'mascara' => '99/99/9999',
														'datepicker' => true,
														'input_hidden' => true
													),
															
													  )
									)					  
						);						
						
						
						
						
?>