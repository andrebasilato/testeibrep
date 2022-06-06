<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$config["banco"] = array("tabela" => "atendimentos",
						 "primaria" => "idatendimento",
						);

// Array de configuração para a listagem	
$config["listagem"] = array(							
							array("id" => "tabela_protocolo", 
							  	  "variavel_lang" => "tabela_protocolo", 
								  "tipo" => "banco", 
								  "coluna_sql" => "ate.protocolo", 
								  "valor" => "protocolo"),	  
							
							array("id" => "tabela_cliente", 
							  	  "variavel_lang" => "tabela_cliente", 
								  "tipo" => "banco", 
								  "coluna_sql" => "p.nome", 
								  "valor" => "cliente"),
								  
							array("id" => "data_cad", 
								  "variavel_lang" => "tabela_datacad", 
								  "tipo" => "php", 
								  "coluna_sql" => "ate.data_cad",
								  "valor" => 'return formataData($linha["data_cad"],"br",0);',
								  "tamanho" => "80",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 3), 

						    array("id" => "situacao_data", 
								  "variavel_lang" => "tabela_datacad_situacao", 
								  "tipo" => "php", 
								  "coluna_sql" => "ate.datacad_situacao",
								  "valor" => 'return formataData($linha["datacad_situacao"],"br",0);',
								  "tamanho" => "80",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 3),
							
							array("id" => "tabela_situacao",
								  "variavel_lang" => "tabela_situacao",
								  "tipo" => "banco", 
								  "valor" => "situacao_atendimento",
								  "tamanho" => "60"
								  ),

							array("id" => "tabela_assunto",
							  	  "variavel_lang" => "tabela_assunto", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "aa.nome", 
							  	  "valor" => "assunto", 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1),
								  
							array("id" => "tabela_subassunto",
							  	  "variavel_lang" => "tabela_subassunto", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "asub.nome", 
							  	  "valor" => "subassunto", 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1),	
							
							array("id" => "tabela_usuario", 
							  	  "variavel_lang" => "tabela_usuario", 
								  "tipo" => "banco", 
								  "coluna_sql" => "usu.nome", 
								  "valor" => "usuario"),
									
							array("id" => "tabela_prioridade", 
							  	  "variavel_lang" => "tabela_prioridade", 
								  "tipo" => "php", 
								  "coluna_sql" => "ate.prioridade",
								  "valor" => 'return $GLOBALS["prioridades"][$this->config["idioma_padrao"]][$linha["prioridade"]];',
								  ),
						   );
						
			// Array de configuração para a formulario			
			$config["formulario"] = array(
							  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario																						
													 
													 array(
														"id" => "form_tipo_data_filtro",
														"nome" => "q[de_ate|tipo_data_filtro|ate.data_cad]",
														"nomeidioma" => "form_tipo_data_filtro",
														"botao_hide" => true,
														"iddivs" => array("de","ate"),
														"tipo" => "select",
														"iddiv" => "de",
														"iddiv2" => "ate",
														"iddiv_obr" => true,
														"iddiv2_obr" => true,
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
															"nome" => "q[4|ate.data_cad]", 
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
															"nome" => "q[5|ate.data_cad]", 
															"nomeidioma" => "form_ate",
															"tipo" => "input",
															"class" => "span2",
															"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
															"validacao" => array("required" => "ate_vazio"),
															"datepicker" => true,
															"input_hidden" => true,
															),
													 array(
															"id" => "idsituacao",
															"nome" => "idsituacao",
															"nomeidioma" => "form_situacao",
															"tipo" => "checkbox",
															"array" => "situacoes", // Array que alimenta o select
															"class" => "span2"
															),
													 array(
														"id" => "form_tipo_data_situacao_filtro",
														"nome" => "q[de_ate|tipo_data_filtro|ah.data_cad]",
														"nomeidioma" => "form_tipo_data_filtro",
														"botao_hide" => true,
														"iddivs" => array("de_situacao","ate_situacao"),
														"tipo" => "select",
														"iddiv" => "de_situacao",
														"iddiv2" => "ate_situacao",
														"iddiv_obr" => true,
														"iddiv2_obr" => true,
														"array" => "tipo_data_filtro", // Array que alimenta o select
														"class" => "span3", 
														"valor" => "tipo_data_filtro",
														//"validacao" => array("required" => "tipo_data_filtro_vazio"),
														"banco" => true,
														"banco_string" => true, 
														"sql_filtro" => "array",
														"sql_filtro_label" => "tipo_data_filtro"
													),
													array(
															"id" => "form_de_situacao",
															"nome" => "q[4|ah.data_cad]", 
															"nomeidioma" => "form_de_situacao",
															"tipo" => "input",
															"class" => "span2",
															"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_situacao\",\"form_ate_situacao\")'",
															//"validacao" => array("required" => "de_situacao_vazio"),
															"datepicker" => true,
															"input_hidden" => true,
															),	
													 array(
															"id" => "form_ate_situacao",
															"nome" => "q[5|ah.data_cad]", 
															"nomeidioma" => "form_ate_situacao",
															"tipo" => "input",
															"class" => "span2",
															"evento" => "onchange='validaIntervaloDatasUmAno(\"form_de_situacao\",\"form_ate_situacao\")'",
															//"validacao" => array("required" => "ate_situacao_vazio"),
															"datepicker" => true,
															"input_hidden" => true,
															),
													  array(
															"id" => "idassunto",
															"nome" => "q[1|ate.idassunto]",//q[1|ate.idassunto]
															"nomeidioma" => "form_idassunto",
															"tipo" => "select",
															"sql" => "SELECT idassunto, nome FROM atendimentos_assuntos WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
															"sql_valor" => "idassunto", // Coluna da tabela que será usado como o valor do options
															"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
															"valor" => "idassunto",
															//"validacao" => array("required" => "assunto_vazio"),
															"banco" => true,
															"sql_filtro" => "SELECT idassunto, nome FROM atendimentos_assuntos WHERE ativo='S' AND idassunto=%",
															"sql_filtro_label" => "nome"
															),
													  array(
															"id" => "idsubassunto",
															"nome" => "q[1|ate.idsubassunto]",
															"nomeidioma" => "form_idsubassunto",
															"json" => true,
															"json_idpai" => "idassunto",
															"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/json/subassunto",
															"json_input_pai_vazio" => "form_selecione_assunto",
															"json_input_vazio" => "form_selecione_subassunto",
															"json_campo_exibir" => "nome",
															"tipo" => "select",
															"valor" => "idsubassunto",
															//"validacao" => array("required" => "subassunto_vazio"),
															"banco" => true,
															"sql_filtro" => "SELECT idsubassunto, nome FROM atendimentos_assuntos_subassuntos WHERE ativo='S' AND idsubassunto=%",
															"sql_filtro_label" => "nome"
															),
														
													array(
															"id" => "idpessoa",
															"nome" => "q[1|p.idpessoa]",
															"nomeidioma" => "form_idpessoa",
															"tipo" => "input",
															"class" => "span2"															
															),
															
													array(
															"id" => "form_cpf",
															"nome" => "q[1|p.documento]", 
															"nomeidioma" => "form_cpf",
															"tipo" => "input",
															"class" => "span2"
															),
													array(
															"id" => "idusuario",
															"nome" => "q[1|usu.idusuario]",
															"nomeidioma" => "form_idusuario",
															"tipo" => "select",
															"sql" => "SELECT idusuario, nome FROM usuarios_adm WHERE ativo = 'S' ORDER BY nome", // SQL que alimenta o select
															"sql_valor" => "idusuario", // Coluna da tabela que será usado como o valor do options
															"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
															"valor" => "idusuario",
															"sql_filtro" => "select * from usuarios_adm where idusuario=%",
															"sql_filtro_label" => "nome"																														
															),
													  )
									)					  
						);						
						
						
			$config["formscript"] = array(
							  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario													 		
													  array(
															"id" => "idassunto",
															"nome" => "q[1|ate.idassunto]",//q[1|ate.idassunto]
															"nomeidioma" => "form_idassunto",
															"tipo" => "select",
															"sql" => "SELECT idassunto, nome FROM atendimentos_assuntos WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
															"sql_valor" => "idassunto", // Coluna da tabela que será usado como o valor do options
															"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
															"valor" => "idassunto",
															//"validacao" => array("required" => "assunto_vazio"),
															"banco" => true,
															"sql_filtro" => "SELECT idassunto, nome FROM atendimentos_assuntos WHERE ativo='S' AND idassunto=%",
															"sql_filtro_label" => "nome"
															),
													  array(
															"id" => "idsubassunto",
															"nome" => "q[1|ate.idsubassunto]",//q[1|ate.idassunto]
															"nomeidioma" => "form_idsubassunto",
															"tipo" => "select",
															"sql" => "SELECT aas.idsubassunto, concat(aa.nome, ' - ', aas.nome) as nome FROM atendimentos_assuntos aa inner join atendimentos_assuntos_subassuntos aas on (aa.idassunto = aas.idassunto) WHERE aas.ativo = 'S' ORDER BY aa.nome, aas.nome", // SQL que alimenta o select
															"sql_valor" => "idsubassunto", // Coluna da tabela que será usado como o valor do options
															"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
															"valor" => "idsubassunto",
															//"validacao" => array("required" => "assunto_vazio"),
															"banco" => true,
															"sql_filtro" => "SELECT idassunto, nome FROM atendimentos_assuntos WHERE ativo='S' AND idassunto=%",
															"sql_filtro_label" => "nome",
															"class" => "span8"
															)
													  )
									)					  
						);												
						
						
?>