<?php

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
$sqlSindicato .= ' order by nome_abreviado';	

$sqlEscola = 'select idescola, razao_social from escolas where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
	$sqlEscola .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
$sqlEscola .= ' order by razao_social';

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

		$config["listagem"] = array(

							array("id" => "nome", 
							  	  "variavel_lang" => "tabela_name",
							  	  "tipo" => "banco",
								  "coluna_sql" => "pe.nome",
								  "valor" => 'nome',	
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),

					  		array("id" => "documento",
							  	  "variavel_lang" => "tabela_login", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "pe.documento", 
							  	  "valor" => "documento", 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 60),
								  						

							array("id" => "email", 
							  	  "variavel_lang" => "tabela_email",
							  	  "tipo" => "banco",
								  "coluna_sql" => "pe.email",
								  "valor" => 'email',	
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
								/*  
							array("id" => "documento", 
							  	  "variavel_lang" => "tabela_password",
							  	  "tipo" => "banco",
								  "coluna_sql" => "pe.documento",
							  	  "valor" => "documento",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
								  */
								 								  

							array("id" => "documento", 
							  	  "variavel_lang" => "tabela_password",
							  	  "tipo" => "banco",
								  "coluna_sql" => "pe.documento",
							  	  "valor" => "documento",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
								  
								  //Seria na sigla
							array("id" => "subdominio", 
							  	  "variavel_lang" => "tabela_subdomain",
							  	  "tipo" => "php",
							  	  "valor" => 'return "IBREP";',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),	
								  
							array("id" => "profile", 
							  	  "variavel_lang" => "tabela_profile",
							  	  "tipo" => "php",
							  	  "valor" => 'return ALUNO;',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),									  							  

/*
							array("id" => "curso", 
							  	  "variavel_lang" => "tabela_course",
							  	  "tipo" => "banco",
								  "coluna_sql" => "cu.nome",
							  	  "valor" => "curso",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
*/		  


							array("id" => "validade", 
							  	  "variavel_lang" => "tabela_valid",
							  	  "tipo" => "php",
							  	  'valor' => 'return str_replace("-","",$linha["validade"]);',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
								  

							array("id" => "ativo", 
							  	  "variavel_lang" => "tabela_active",
							  	  "tipo" => "php",
							  	  "valor" => 'return 1;',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),

/*
							array("id" => "regra", 
							  	  "variavel_lang" => "tabela_role",
							  	  "tipo" => "php",
							  	  "valor" => 'return "UNO";',
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
*/								  


				
						   );

						
			// Array de configuração para a formulario			
			$config["formulario"] = array(
							  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario	
											/*
											array(
													"id" => "idmantenedora",
													"nome" => "q[1|mt.idmantenedora]",
													"nomeidioma" => "form_idmantenedora",
													"tipo" => "select",
													"sql" => "SELECT idmantenedora, nome_fantasia 
															FROM mantenedoras 
															WHERE ativo='S' 
															ORDER BY nome_fantasia", // SQL que alimenta o select
													"sql_valor" => "idmantenedora", // Coluna da tabela que será usado como o valor do options
													"sql_label" => "nome_fantasia", // Coluna da tabela que será usado como o label do options
													"valor" => "idmantenedora",
													"class" => "span5",
													"sql_filtro" => "select * from mantenedoras where idmantenedora=%",
													"sql_filtro_label" => "nome_fantasia",																
													), */ 
																									
											array(
													"id" => "idsindicato",
													"nome" => "q[1|ma.idsindicato]",
													"nomeidioma" => "form_idsindicato",
													"tipo" => "select",
													"sql" => $sqlSindicato,
													"sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
													"sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options																											
													"valor" => "idsindicato",
														"sql_filtro" => "select * from sindicatos where idsindicato=%",
														"sql_filtro_label" => "nome_abreviado",																
													),
											array(
													"id" => "idoferta",
													"nome" => "q[1|ma.idoferta]",
													"nomeidioma" => "form_idoferta",
													"tipo" => "select",
													"sql" => "SELECT idoferta, nome 
															FROM ofertas 
															WHERE ativo='S' 
															ORDER BY nome", // SQL que alimenta o select
													"sql_valor" => "idoferta", // Coluna da tabela que será usado como o valor do options
													"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
													"valor" => "idoferta",
													"class" => "span5",
													"sql_filtro" => "SELECT * FROM ofertas 
																	WHERE idoferta=%",
													"sql_filtro_label" => "nome",																
													),
											array(
													"id" => "idcurso",
													"nome" => "q[1|ma.idcurso]",
													"nomeidioma" => "form_idcurso",
													"json" => true,
													"json_idpai" => "idoferta",
													"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_cursos/",
													"json_input_pai_vazio" => "form_selecione_oferta",
													"json_input_vazio" => "form_selecione_curso",
													"json_campo_exibir" => "nome",
													"tipo" => "select",
													"valor" => "idcurso",
													"sql_filtro" => "SELECT * FROM cursos 
																	WHERE idcurso=%",
													"sql_filtro_label" => "nome",																
													),
											array(
													"id" => "idturma",
													"nome" => "q[1|ma.idturma]",
													"nomeidioma" => "form_idturma",
													"json" => true,
													"json_idpai" => "idoferta",
													"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_turmas/",
													"json_input_pai_vazio" => "form_selecione_oferta",
													"json_input_vazio" => "form_selecione_turma",
													"json_campo_exibir" => "nome",
													"tipo" => "select",
													"valor" => "idturma",
													"sql_filtro" => "select * from ofertas_turmas where idturma=%",
													"sql_filtro_label" => "nome",																
													),

											array(
									                "id" => "idsituacao",
									                "nome" => "situacao",
									                "nomeidioma" => "form_idsituacao",
									                "tipo" => "checkbox",
									                "sql" => "SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo='S' order by nome--",
									                "sql_valor" => "idsituacao",
									                "sql_label" => "nome",
									                "valor" => "idsituacao",
									                "sql_filtro" => "select * from matriculas_workflow where idsituacao=%",
									                "sql_filtro_label" => "nome"
									            ),															
													
											array(
													"id" => "idescola",
													"nome" => "q[1|ma.idescola]",
													"nomeidioma" => "form_idescola",
													"tipo" => "select",
													"sql" => $sqlEscola, // SQL que alimenta o select
													"sql_valor" => "idescola", // Coluna da tabela que será usado como o valor do options
													"sql_label" => "razao_social", // Coluna da tabela que será usado como o label do options
													"valor" => "idescola",
													"class" => "span5",
													"sql_filtro" => "select * from escolas where idescola=%",
													"sql_filtro_label" => "razao_social",																
													),

											array(
												"id" => "form_tipo_data_registro",
												"nome" => "q[de_ate|tipo_data_registro|ma.data_registro]",
												"nomeidioma" => "form_tipo_data_registro",
												"botao_hide" => true,
												"iddivs" => array("registro_de","registro_ate"),
												"tipo" => "select",
												"iddiv" => "registro_de",
												"iddiv2" => "registro_ate",
												"iddiv_obr" => true,
												"iddiv2_obr" => true,
												"array" => "tipo_data_filtro", // Array que alimenta o select
												"class" => "span3", 
												"valor" => "tipo_data_filtro",
												"validacao" => array("required" => "tipo_data_registro_vazio"),
												"banco" => true,
												"banco_string" => true, 
												"sql_filtro" => "array",
												"sql_filtro_label" => "tipo_data_filtro"
												),
											array(
													"id" => "form_registro_de",
													"nome" => "registro_de", 
													"nomeidioma" => "form_registro_de",
													"tipo" => "input",
													"class" => "span2",
													"evento" => "onchange='validaIntervaloDatasUmAno(\"form_registro_de\",\"form_registro_ate\")'",
													"validacao" => array("required" => "registro_de_vazio"),
													"datepicker" => true,
													"input_hidden" => true,
													),	
											array(
													"id" => "form_registro_ate",
													"nome" => "registro_ate", 
													"nomeidioma" => "form_registro_ate",
													"tipo" => "input",
													"class" => "span2",
													"evento" => "onchange='validaIntervaloDatasUmAno(\"form_registro_de\",\"form_registro_ate\")'",
													"validacao" => array("required" => "registro_ate_vazio"),
													"datepicker" => true,
													"input_hidden" => true,
													),
											 
											array(
												"id" => "form_tipo_data_matricula",
												"nome" => "q[de_ate|tipo_data_filtro|ma.data_cad]",
												"nomeidioma" => "form_tipo_data_matricula",
												"botao_hide" => true,
												"iddivs" => array("matricula_de","matricula_ate"),
												"tipo" => "select",
												"iddiv" => "matricula_de",
												"iddiv2" => "matricula_ate",
												"iddiv_obr" => true,
												"iddiv2_obr" => true,
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
													"id" => "form_matricula_de",
													"nome" => "matricula_de", 
													"nomeidioma" => "form_de",
													"tipo" => "input",
													"class" => "span2",
													"evento" => "onchange='validaIntervaloDatasUmAno(\"form_matricula_de\",\"form_matricula_ate\")'",
													//"validacao" => array("required" => "matricula_de_vazio"),
													"datepicker" => true,
													"input_hidden" => true,
													),	
											array(
													"id" => "form_matricula_ate",
													"nome" => "matricula_ate", 
													"nomeidioma" => "form_ate",
													"tipo" => "input",
													"class" => "span2",
													"evento" => "onchange='validaIntervaloDatasUmAno(\"form_matricula_de\",\"form_matricula_ate\")'",
													//"validacao" => array("required" => "matricula_ate_vazio"),
													"datepicker" => true,
													"input_hidden" => true,
													),

											array(
												"id" => "form_tipo_data_conclusao",
												"nome" => "q[de_ate|tipo_data_conclusao|mh.data_cad]",
												"nomeidioma" => "form_tipo_data_conclusao",
												"botao_hide" => true,
												"iddivs" => array("conclusao_de","conclusao_ate"),
												"tipo" => "select",
												"iddiv" => "conclusao_de",
												"iddiv2" => "conclusao_ate",
												"iddiv_obr" => true,
												"iddiv2_obr" => true,
												"array" => "tipo_data_filtro", // Array que alimenta o select
												"class" => "span3", 
												"valor" => "tipo_data_filtro",
												//"validacao" => array("required" => "tipo_data_conclusao_vazio"),
												"banco" => true,
												"banco_string" => true, 
												"sql_filtro" => "array",
												"sql_filtro_label" => "tipo_data_filtro"
												),
											array(
													"id" => "form_conclusao_de",
													"nome" => "conclusao_de", 
													"nomeidioma" => "form_conclusao_de",
													"tipo" => "input",
													"class" => "span2",
													"evento" => "onchange='validaIntervaloDatasUmAno(\"form_conclusao_de\",\"form_conclusao_ate\")'",
													//"validacao" => array("required" => "conclusao_de_vazio"),
													"datepicker" => true,
													"input_hidden" => true,
													),	
											array(
													"id" => "form_conclusao_ate",
													"nome" => "conclusao_ate", 
													"nomeidioma" => "form_conclusao_ate",
													"tipo" => "input",
													"class" => "span2",
													"evento" => "onchange='validaIntervaloDatasUmAno(\"form_conclusao_de\",\"form_conclusao_ate\")'",
													//"validacao" => array("required" => "conclusao_ate_vazio"),
													"datepicker" => true,
													"input_hidden" => true,
													),

										)
									)					  
						);						
						
?>