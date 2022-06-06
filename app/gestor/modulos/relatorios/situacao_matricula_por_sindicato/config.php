<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";
						
			// Array de configuração para a formulario			
			$config["formulario"] = array(
							  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario	
										
											array(
									                "id" => "idregiao",
									                "nome" => "q[1|e.idregiao]",
									                "nomeidioma" => "form_idregiao",
									                "tipo" => "select",
									                "sql" => "SELECT idregiao, nome FROM regioes order by nome--",
									                "sql_valor" => "idregiao",
									                "sql_label" => "nome",
									                "valor" => "idregiao",
									                "sql_filtro" => "select * from regioes where idregiao=%",
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
												//"validacao" => array("required" => "tipo_data_registro_vazio"),
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
													//"validacao" => array("required" => "registro_de_vazio"),
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
													//"validacao" => array("required" => "registro_ate_vazio"),
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
												"banco" => true,
												"banco_string" => true, 
												"sql_filtro" => "array",
												"sql_filtro_label" => "tipo_data_filtro",
												),

											array(
													"id" => "form_matricula_de",
													"nome" => "matricula_de", 
													"nomeidioma" => "form_de",
													"tipo" => "input",
													"class" => "span2",
													"evento" => "onchange='validaIntervaloDatasUmAno(\"form_matricula_de\",\"form_matricula_ate\")'",
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
													"datepicker" => true,
													"input_hidden" => true,
													),
										)
									)					  
						);						
						
?>