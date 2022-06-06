<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";


		$config["listagem"] = array(
		
					  		array("id" => "ava",
							  	  "variavel_lang" => "tabela_ava", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "a.nome", 
							  	  "valor" => "ava", 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 60),
								  						
							array("id" => "chat", 
							  	  "variavel_lang" => "tabela_chat",
							  	  "tipo" => "banco",
								  "coluna_sql" => "a.nome",
								  "valor" => 'chat',	
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),	
								  
							array("id" => "exibir_ava", 
							  	  "variavel_lang" => "tabela_situacao",
							  	  "tipo" => "php",
								  "coluna_sql" => "c.exibir_ava",
							  	  "valor" => 'if($linha["exibir_ava"] == "S"){
								  		return "Ativo no Painel";
								  }else{
								  		return "Inativo no Painel";
								  }',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
							
							array("id" => "inicio", 
							  	  "variavel_lang" => "tabela_abertura",
							  	  "tipo" => "php",
								  "coluna_sql" => "c.inicio_entrada_aluno",
							  	  "valor" => 'return $linha["inicio"];',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1),
				
						   );

						
			// Array de configuração para a formulario			
			$config["formulario"] = array(
							  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario		
											array(
													"id" => "idava",
													"nome" => "q[1|a.idava]",
													"nomeidioma" => "form_idava",
													"tipo" => "select",
													"sql" => "SELECT idava, nome 
															FROM avas 
															WHERE ativo='S'", // SQL que alimenta o select
													"sql_valor" => "idava", // Coluna da tabela que será usado como o valor do options
													"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
													"valor" => "idsindicato",
														"sql_filtro" => "select * from avas where idava=%",
														"sql_filtro_label" => "nome",																
													),									
											array(
													"id" => "form_situacao",
													"nome" => "q[1|c.exibir_ava]", 
													"nomeidioma" => "form_situacao",
													"array" => "ativo",
													"tipo" => "select",
													"class" => "span2",
													),	
											array(
												"id" => "form_tipo_data_registro",
												"nome" => "q[de_ate|tipo_data_registro|c.inicio_entrada_aluno]",
												"nomeidioma" => "form_tipo_data_registro",
												"botao_hide" => true,
												"iddivs" => array("periodo_de","periodo_ate"),
												"tipo" => "select",
												"iddiv" => "periodo_de",
												"iddiv2" => "periodo_ate",
												"iddiv_obr" => true,
												"iddiv2_obr" => true,
												"array" => "tipo_data_filtro", // Array que alimenta o select
												"class" => "span3", 
												"valor" => "tipo_data_filtro",
												//"validacao" => array("required" => "tipo_data_registro_vazio"),
												"banco" => true,
												"banco_string" => true, 
												"sql_filtro" => "array",
												"sql_filtro_label" => "tipo_data_filtro"
												),
											array(
													"id" => "form_periodo_de",
													"nome" => "q[3|c.inicio_entrada_aluno]", 
													"nomeidioma" => "form_periodo_de",
													"tipo" => "input",
													"class" => "span2",
													"evento" => "onchange='validaIntervaloDatasUmAno(\"form_periodo_de\",\"form_periodo_de\")'",
													//"validacao" => array("required" => "matricula_de_vazio"),
													"datepicker" => true,
													"input_hidden" => true,),	
											array(
													"id" => "form_periodo_ate",
													"nome" => "q[4|c.inicio_entrada_aluno]", 
													"nomeidioma" => "form_periodo_ate",
													"tipo" => "input",
													"class" => "span2",
													"evento" => "onchange='validaIntervaloDatasUmAno(\"form_periodo_ate\",\"form_periodo_ate\")'",
													//"validacao" => array("required" => "matricula_ate_vazio"),
													"datepicker" => true,
													"input_hidden" => true,),

										array(
												"id" => "form_tipo_data_matricula",
												"nome" => "q[de_ate|tipo_data_filtro|c.fim_entrada_aluno]",
												"nomeidioma" => "form_tipo_data_matricula",
												"botao_hide" => true,
												"iddivs" => array("periodo_de2","periodo_ate2"),
												"tipo" => "select",
												"iddiv" => "periodo_de2",
												"iddiv2" => "periodo_ate2",
												"iddiv_obr" => false,
												"iddiv2_obr" => false,
												"array" => "tipo_data_filtro", // Array que alimenta o select
												"class" => "span3", 
												"valor" => "tipo_data_filtro",
												//"validacao" => array("required" => "tipo_data_matricula_vazio"),
												"banco" => true,
												"banco_string" => true, 
												"sql_filtro" => "array",
												"sql_filtro_label" => "tipo_data_filtro"
												),
										array(
													"id" => "form_periodo_de2",
													"nome" => "q[3|c.fim_entrada_aluno]", 
													"nomeidioma" => "form_periodo_de2",
													"tipo" => "input",
													"class" => "span2",
													"evento" => "onchange='validaIntervaloDatasUmAno(\"form_periodo_de2\",\"form_periodo_de2\")'",
													//"validacao" => array("required" => "matricula_de_vazio"),
													"datepicker" => true,
													"input_hidden" => true,),	
										array(
													"id" => "form_periodo_ate2",
													"nome" => "q[4|c.fim_entrada_aluno]", 
													"nomeidioma" => "form_periodo_ate2",
													"tipo" => "input",
													"class" => "span2",
													"evento" => "onchange='validaIntervaloDatasUmAno(\"form_periodo_ate2\",\"form_periodo_ate2\")'",
													//"validacao" => array("required" => "matricula_ate_vazio"),
													"datepicker" => true,
													"input_hidden" => true,),

										)
									)					  
						);						
						
?>