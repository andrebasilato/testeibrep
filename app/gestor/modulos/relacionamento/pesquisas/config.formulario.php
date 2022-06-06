<?			   
			// Array de configuração para a formulario			
			$config["formulario"] = array(
							  array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									"campos" => array( // Campos do formulario																						
													  array(
															"id" => "form_nome",
															"nome" => "nome", 
															"nomeidioma" => "form_nome",
															"tipo" => "input",
															"valor" => "nome",
															"validacao" => array("required" => "nome_vazio"), 
															"class" => "span6",
															"banco" => true,
															"banco_string" => true,															
															),
													  array(
															"id" => "form_descricao",
															"nome" => "descricao",
															"nomeidioma" => "form_descricao",
															"tipo" => "text", 
															"editor" => true,
															"valor" => "descricao",
															"class" => "xxlarge",
															"banco" => true,
															"banco_string" => true
															),																	
													  array(
														"id" => "de",
														"nome" => "de",
														"nomeidioma" => "form_de",
														"tipo" => "input", 
														"valor" => "de",
														"valor_php" => 'if($dados["de"]) return formataData("%s", "br", 0)',
														"evento" => "readonly='readonly'",
														"class" => "span2",
														"mascara" => "99/99/9999",
														"datepicker" => true,
														"banco" => true, 
														"validacao" => array("required" => "de_vazio"),
														"banco_php" => 'return formataData("%s", "en", 0)',
														"banco_string" => true 															
													  ),
													  array(
														"id" => "ate",
														"nome" => "ate",
														"nomeidioma" => "form_ate",
														"tipo" => "input", 
														"valor" => "ate",
														"valor_php" => 'if($dados["ate"]) return formataData("%s", "br", 0)',
														"class" => "span2",
														"evento" => "readonly='readonly'",
														"mascara" => "99/99/9999",
														"datepicker" => true,
														"banco" => true, 
														"validacao" => array("required" => "ate_vazio"),
														"banco_php" => 'return formataData("%s", "en", 0)',
														"banco_string" => true 															
													  ),

													  array(
															"id" => "form_ativo_painel",
															"nome" => "ativo_painel",
															"nomeidioma" => "form_ativo_painel",
															"tipo" => "select",
															"array" => "ativo", // Array que alimenta o select
															"class" => "span2", 
															"valor" => "ativo_painel",
															"validacao" => array("required" => "ativo_vazio"),
															"ajudaidioma" => "form_ativo_ajuda",
															"banco" => true,
															"banco_string" => true
															),
														
													  array(
															"id" => "form_ativo_cliente",
															"nome" => "ativo_cliente",
															"nomeidioma" => "form_ativo_cliente",
															"tipo" => "select",
															"array" => "ativo", // Array que alimenta o select
															"class" => "span2", 
															"valor" => "ativo_cliente",
															"validacao" => array("required" => "ativo_cliente_vazio"),
															//"ajudaidioma" => "form_ativo_cliente_ajuda",
															"banco" => true,
															"banco_string" => true
															),
															
													)
									)								  
						);

$config["formulario_pessoas"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados_pessoas", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
		"id" => "estado_civil",
		"nome" => "estado_civil",
		"nomeidioma" => "form_estado_civil",
		"tipo" => "select",
		"array" => "estadocivil", // Array que alimenta o select
		"class" => "span2", 
	  ),
	  array(
		"id" => "data_nasc_dia",
		"nome" => "data_nasc_dia",
		"nomeidioma" => "form_data_nasc_dia",
		"tipo" => "select",
		"array" => "dia_mes", // Array que alimenta o select
		"class" => "span2", 
	  ),
	  array(
		"id" => "data_nasc_mes",
		"nome" => "data_nasc_mes",
		"nomeidioma" => "form_data_nasc_mes",
		"tipo" => "select",
		"array" => "meses_idioma", // Array que alimenta o select
		"class" => "span2", 
	  ),
	  array(
		"id" => "idestado",
		"nome" => "idestado",
		"nomeidioma" => "form_idestado",
		"tipo" => "select",
		"sql" => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
		"sql_valor" => "idestado", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome",
		"class" => "span2", 
	  ),
	  array(
		"id" => "idcidade",
		"nome" => "idcidade",
		"nomeidioma" => "form_idcidade",
		"json" => true,
		"json_idpai" => "idestado",
		"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"].'/'.$url["3"]."/json/cidades/",
		"json_input_pai_vazio" => "form_selecione_estado",
		"json_input_vazio" => "form_selecione_cidade",
		"json_campo_exibir" => "nome",
		"tipo" => "select",
		"valor" => "idcidade",
		"class" => "span3",
	  ),
	  array(
			"id" => "nome",
			"nome" => "nome",
			"nomeidioma" => "form_nome",
			"tipo" => "input", 
			"class" => "span2",														
	    ),
		array(
			"id" => "documento",
			"nome" => "documento",
			"numerico" => true,
			"nomeidioma" => "form_documento",
			"tipo" => "input", 
			"class" => "span2",														
	    ),
		array(
			"id" => "email",
			"nome" => "email",
			"nomeidioma" => "form_email",
			"tipo" => "input", 
			"class" => "span2",														
	    ),																														
	)
  )								  
);
						
$config["formulario_usuarios_adm"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados_usuarios_adm", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  	array(
			"id" => "idsindicato",
			"nome" => "idsindicato",
			"nomeidioma" => "form_idsindicato",
			"tipo" => "select",
			"sql" => "select idsindicato, concat('(',idsindicato,') ',nome_abreviado) as nome FROM sindicatos WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
			"sql_valor" => "idsindicato",
			"sql_label" => "nome"
	  	),
	  	array(
			"id" => "idescola",
			"nome" => "idescola",
			"nomeidioma" => "form_idescola",
			"tipo" => "select",
			"sql" => "select idescola, nome_fantasia as nome FROM escolas WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome_fantasia", // SQL que alimenta o select
			"sql_valor" => "idescola",
			"sql_label" => "nome"
	  	),																															
	  	/*array(
			"id" => "idescola",
			"nome" => "idescola",
			"nomeidioma" => "form_idescola",
			"json" => true,
			"json_idpai" => "idsindicato",
			"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"].'/'.$url["3"]."/json/escolas/",
			"json_input_pai_vazio" => "form_selecione_sindicato",
			"json_input_vazio" => "form_selecione_escola",
			"json_campo_exibir" => "nome_fantasia",
			"tipo" => "select",
			"valor" => "idescola"
		  ),*/																						
	  array(
		"id" => "data_nasc_dia",
		"nome" => "data_nasc_dia",
		"nomeidioma" => "form_data_nasc_dia",
		"tipo" => "select",
		"array" => "dia_mes", // Array que alimenta o select
		"class" => "span2", 
	  ),
	  array(
		"id" => "data_nasc_mes",
		"nome" => "data_nasc_mes",
		"nomeidioma" => "form_data_nasc_mes",
		"tipo" => "select",
		"array" => "meses_idioma", // Array que alimenta o select
		"class" => "span2", 
	  ),
	  array(
		"id" => "idestado",
		"nome" => "idestado",
		"nomeidioma" => "form_idestado",
		"tipo" => "select",
		"sql" => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
		"sql_valor" => "idestado", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome",
		"class" => "span2", 
	  ),
	  array(
		"id" => "idcidade",
		"nome" => "idcidade",
		"nomeidioma" => "form_idcidade",
		"json" => true,
		"json_idpai" => "idestado",
		"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"].'/'.$url["3"]."/json/cidades/",
		"json_input_pai_vazio" => "form_selecione_estado",
		"json_input_vazio" => "form_selecione_cidade",
		"json_campo_exibir" => "nome",
		"tipo" => "select",
		"valor" => "idcidade",
		"class" => "span3",
	  ),
	  array(
			"id" => "nome",
			"nome" => "nome",
			"nomeidioma" => "form_nome",
			"tipo" => "input", 
			"class" => "span2",														
	    ),
		array(
			"id" => "documento",
			"nome" => "documento",
			"numerico" => true,
			"nomeidioma" => "form_documento",
			"tipo" => "input", 
			"class" => "span2",														
	    ),
		array(
			"id" => "email",
			"nome" => "email",
			"nomeidioma" => "form_email",
			"tipo" => "input", 
			"class" => "span2",														
	    ),
		array(
			"id" => "idperfil",
			"nome" => "idperfil",
			"nomeidioma" => "form_idperfil",
			"tipo" => "select",
			"sql" => "SELECT idperfil, nome FROM usuarios_adm_perfis where ativo='S' ORDER BY nome ", // SQL que alimenta o select
			"sql_valor" => "idperfil", // Coluna da tabela que será usado como o valor do options
			"sql_label" => "nome",
			"class" => "span2", 
	    )																															
	)
  )								  
);

$config["formulario_professores"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados_professores", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						  
	  array(
		"id" => "idava",
		"nome" => "idava",
		"nomeidioma" => "form_idava",
		"tipo" => "select",                          
		"sql" => "SELECT idava, nome FROM avas WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
		"sql_valor" => "idava",
		"sql_label" => "nome"
	  ),
	  array(
		"id" => "idoferta",
		"nome" => "idoferta",
		"nomeidioma" => "form_idoferta",
		"tipo" => "select",
		"sql" => "SELECT idoferta, nome FROM ofertas ORDER BY nome", // SQL que alimenta o select
		"sql_valor" => "idoferta", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome",
		//"class" => "span2", 
	  ),
	  array(
		"id" => "idcurso",
		"nome" => "idcurso",
		"nomeidioma" => "form_idcurso",
		"json" => true,
		"json_idpai" => "idoferta",
		"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"].'/'.$url["3"]."/json/cursos/",
		"json_input_pai_vazio" => "form_selecione_oferta",
		"json_input_vazio" => "form_selecione_curso",
		"json_campo_exibir" => "nome",
		"tipo" => "select",
		"valor" => "idcurso",
		//"class" => "span2",
	  ),
	  array(
		"id" => "data_nasc_dia",
		"nome" => "data_nasc_dia",
		"nomeidioma" => "form_data_nasc_dia",
		"tipo" => "select",
		"array" => "dia_mes", // Array que alimenta o select
		"class" => "span2", 
	  ),
	  	array(
			"id" => "data_nasc_mes",
			"nome" => "data_nasc_mes",
			"nomeidioma" => "form_data_nasc_mes",
			"tipo" => "select",
			"array" => "meses_idioma", // Array que alimenta o select
			"class" => "span2", 
	  	),
	  	array(
			"id" => "idestado",
			"nome" => "idestado",
			"nomeidioma" => "form_idestado",
			"tipo" => "select",
			"sql" => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
			"sql_valor" => "idestado", // Coluna da tabela que será usado como o valor do options
			"sql_label" => "nome",
			"class" => "span2", 
	  	),
	  	array(
			"id" => "idcidade",
			"nome" => "idcidade",
			"nomeidioma" => "form_idcidade",
			"json" => true,
			"json_idpai" => "idestado",
			"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"].'/'.$url["3"]."/json/cidades/",
			"json_input_pai_vazio" => "form_selecione_estado",
			"json_input_vazio" => "form_selecione_cidade",
			"json_campo_exibir" => "nome",
			"tipo" => "select",
			"valor" => "idcidade",
			"class" => "span3",
	  	),
	  	array(
			"id" => "nome",
			"nome" => "nome",
			"nomeidioma" => "form_nome",
			"tipo" => "input", 
			"class" => "span2",														
	    ),
		array(
			"id" => "documento",
			"nome" => "documento",
			"numerico" => true,
			"nomeidioma" => "form_documento",
			"tipo" => "input", 
			"class" => "span2",														
	    ),
		array(
			"id" => "email",
			"nome" => "email",
			"nomeidioma" => "form_email",
			"tipo" => "input", 
			"class" => "span2",														
	    ),
	)
  )	  
);

$config["formulario_matriculas"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados_reservas", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario
      array(
			"id" => "data_cad_de",
			"nome" => "data_cad_de",
			"nomeidioma" => "form_data_cad_de",
			"tipo" => "input", 
			"class" => "span2",
			"mascara" => "99/99/9999",
			"datepicker" => true															
		  ),
		array(
			"id" => "data_cad_ate",
			"nome" => "data_cad_ate",
			"nomeidioma" => "form_data_cad_ate",
			"tipo" => "input", 
			"class" => "span2",
			"mascara" => "99/99/9999",
			"datepicker" => true															
		  ),	
		array(
			"id" => "idsindicato",
			"nome" => "idsindicato",
			"nomeidioma" => "form_idsindicato",
			"tipo" => "select",
			"sql" => "select idsindicato, concat('(',idsindicato,') ', nome_abreviado) as nome FROM sindicatos WHERE ativo='S' and ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
			"sql_valor" => "idsindicato",
			"sql_label" => "nome"
		),																					
	  
	  array(
			"id" => "idoferta",
			"nome" => "idoferta",
			"nomeidioma" => "form_idoferta",
			"tipo" => "select",
			"sql" => "SELECT idoferta, nome FROM ofertas WHERE ativo='S' ORDER BY nome", // SQL que alimenta o select
			"sql_valor" => "idoferta", // Coluna da tabela que será usado como o valor do options
			"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
			"valor" => "idoferta",
			"class" => "span5",
			"sql_filtro" => "SELECT * FROM ofertas WHERE idoferta=%",
			"sql_filtro_label" => "nome",																
			),
	array(
			"id" => "idcurso",
			"nome" => "idcurso",
			"nomeidioma" => "form_idcurso",
			"json" => true,
			"json_idpai" => "idoferta",
			"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_cursos/",
			"json_input_pai_vazio" => "form_selecione_oferta",
			"json_input_vazio" => "form_selecione_curso",
			"json_campo_exibir" => "nome",
			"tipo" => "select",
			"valor" => "idcurso",
			"sql_filtro" => "SELECT * FROM cursos WHERE idcurso=%",
			"sql_filtro_label" => "nome",																
			),
	array(
			"id" => "idturma",
			"nome" => "idturma",
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
			"id" => "idescola",
			"nome" => "idescola",
			"nomeidioma" => "form_idescola",
			"json" => true,
			"json_idpai" => "idoferta",
			"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_escolas/",
			"json_input_pai_vazio" => "form_selecione_oferta",
			"json_input_vazio" => "form_selecione_escola",
			"json_campo_exibir" => "nome",
			"tipo" => "select",
			"valor" => "idescola",
			"sql_filtro" => "select * from escolas where idescola=%",
			"sql_filtro_label" => "nome",																
			),

		array(
			"id" => "idmatricula",
			"nome" => "idmatricula",
			"nomeidioma" => "form_idmatricula",
			"tipo" => "input", 
			"class" => "span2",															
		  ),

		array(
			"id" => "data_matricula",
			"nome" => "data_matricula",
			"nomeidioma" => "form_data_matricula",
			"tipo" => "input", 
			"class" => "span2",
			"mascara" => "99/99/9999",
			"datepicker" => true															
		  ),			
			
	  
	  array(
		"id" => "idsituacao",
		"nome" => "idsituacao",
		"nomeidioma" => "form_idsituacao",
		"tipo" => "select",
		"sql" => "SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo = 'S' order by nome", // SQL que alimenta o select
		"sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome"
	  ),
	  array(
			"id" => "data_alteracao_de",
			"nome" => "data_alteracao_de",
			"nomeidioma" => "form_data_alteracao_de",
			"tipo" => "input", 
			"class" => "span2",
			"mascara" => "99/99/9999",
			"datepicker" => true															
		  ),
		array(
			"id" => "data_alteracao_ate",
			"nome" => "data_alteracao_ate",
			"nomeidioma" => "form_data_alteracao_ate",
			"tipo" => "input", 
			"class" => "span2",
			"mascara" => "99/99/9999",
			"datepicker" => true															
		  ),

		array(
			"id" => "nome",
			"nome" => "nome",
			"nomeidioma" => "form_nome",
			"tipo" => "input", 
			"class" => "span2",															
		  ),

		array(
			"id" => "documento",
			"nome" => "documento",
			"nomeidioma" => "form_documento",
			"tipo" => "input", 
			"class" => "span2",															
		  ),
	  array(
		"id" => "idvendedor",
		"nome" => "idvendedor",
		"nomeidioma" => "form_idvendedor",
		"tipo" => "select",
		"sql" => "SELECT idvendedor, nome FROM vendedores WHERE ativo = 'S' order by nome", // SQL que alimenta o select
		"sql_valor" => "idvendedor", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome"
	  )																													
	)
  )								  
);
?>