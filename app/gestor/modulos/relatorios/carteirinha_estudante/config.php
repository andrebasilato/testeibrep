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
		
					  		array("id" => "idmatricula",
							  	  "variavel_lang" => "tabela_matricula", 
							  	  "tipo" => "banco", 
							  	  "coluna_sql" => "ma.idmatricula", 
							  	  "valor" => "idmatricula", 
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 1,
								  "tamanho" => 60),
								  						
							array("id" => "nome", 
							  	  "variavel_lang" => "tabela_nome",
							  	  "tipo" => "banco",
								  "coluna_sql" => "pe.nome",
								  "valor" => 'nome',	
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),	

							array("id" => "data_nasc", 
							  	  "variavel_lang" => "tabela_data_nasc",
							  	  "tipo" => "php",
								  "coluna_sql" => "pe.data_nasc",
								  "valor" => 'return formataData($linha["data_nasc"],"br",0);',	
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
								  
							array("id" => "documento", 
							  	  "variavel_lang" => "tabela_documento",
							  	  "tipo" => "banco",
								  "coluna_sql" => "pe.documento",
							  	  "valor" => "documento",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),

							array("id" => "rg", 
							  	  "variavel_lang" => "tabela_rg",
							  	  "tipo" => "banco",
								  "coluna_sql" => "pe.rg",
							  	  "valor" => "rg",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),

							array("id" => "curso", 
							  	  "variavel_lang" => "tabela_curso",
							  	  "tipo" => "banco",
								  "coluna_sql" => "cu.nome",
							  	  "valor" => "curso",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
							
							array("id" => "turma", 
							  	  "variavel_lang" => "tabela_turma",
							  	  "tipo" => "banco",
								  "coluna_sql" => "tu.nome",
							  	  "valor" => "turma",
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),

							array("id" => "filiacao_pai", 
							  	  "variavel_lang" => "tabela_filiacao_pai",
							  	  "tipo" => "banco",
								  "coluna_sql" => "pe.filiacao_pai",
								  "valor" => 'filiacao_pai',	
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),

							array("id" => "filiacao_mae", 
							  	  "variavel_lang" => "tabela_filiacao_mae",
							  	  "tipo" => "banco",
								  "coluna_sql" => "pe.filiacao_mae",
								  "valor" => 'filiacao_mae',	
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),

							array("id" => "validade", 
							  	  "variavel_lang" => "tabela_validade",
							  	  "tipo" => "php",
								  "coluna_sql" => "ma.validade",
								  "valor" => 'return formataData($linha["validade"],"br",0);',	
								  "busca" => true,
								  "busca_class" => "inputPreenchimentoCompleto",
								  "busca_metodo" => 2),
						   );

						
			// Array de configuração para a formulario			
			$config["formulario"] = array(
							  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario	
											array(
													"id" => "idsindicato",
													"nome" => "q[1|ma.idsindicato]",
													"nomeidioma" => "form_idsindicato",
													"tipo" => "select",
													"sql" => $sqlSindicato, // SQL que alimenta o select
													"sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
													"sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
													"valor" => "idsindicato",
														"sql_filtro" => "select * from sindicatos where idsindicato=%",
														"sql_filtro_label" => "nome_abreviado",																
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
													"id" => "idturma",
													"nome" => "q[1|ma.idturma]",
													"nomeidioma" => "form_idturma",
													"tipo" => "select",
													"sql" => "SELECT idturma, nome 
															FROM ofertas_turmas 
															WHERE ativo='S' 
															ORDER BY nome", // SQL que alimenta o select
													"sql_valor" => "idturma", // Coluna da tabela que será usado como o valor do options
													"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
													"valor" => "idturma",
													"class" => "span5",
													"sql_filtro" => "select * from ofertas_turmas where idturma=%",
													"sql_filtro_label" => "nome",																
													),

											array(
													"id" => "idcurso",
													"nome" => "q[1|ma.idcurso]",
													"nomeidioma" => "form_idcurso",
													"tipo" => "select",
													"sql" => "SELECT idcurso, nome 
															FROM cursos 
															WHERE ativo='S' 
															ORDER BY nome", // SQL que alimenta o select
													"sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
													"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
													"valor" => "idcurso",
													"class" => "span5",
													"sql_filtro" => "SELECT * FROM cursos 
																	WHERE idcurso=%",
													"sql_filtro_label" => "nome",																
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
												"nome" => "q[de_ate|tipo_data_filtro|ma.data_matricula]",
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
												"nome" => "q[de_ate|tipo_data_conclusao|ma.data_conclusao]",
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

											array(
												"id" => "idsituacao",
												"nome" => "situacao",
												"nomeidioma" => "form_idsituacao",
												"tipo" => "checkbox",
												"sql" => "SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo='S'", // SQL que alimenta o select
												"sql_ordem" => "asc",
												"sql_ordem_campo" => "nome",
												"sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
												"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
												"valor" => "idsituacao",
												"sql_filtro" => "select * from matriculas_workflow where idsituacao=%",
												"sql_filtro_label" => "nome"														
											),

											array(
								                "id" => "situacao_carteirinha",
								                "nome" => "q[1|ma.situacao_carteirinha]",
								                "nomeidioma" => "form_situacao_carteirinha",
								                "tipo" => "select",
								                "array" => 'situacao_carteirinha_aluno',
								                "valor" => "situacao_carteirinha",
								                "sql_label" => "nome",
								                "sql_valor" => "situacao_carteirinha",
								                "sql_filtro_label" => "nome"
								            ),
										)
									)					  
						);						
						
?>