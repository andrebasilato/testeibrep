<?			   
// Array de configuração para a formulario			
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
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
        "evento" => "maxlength='100'",
	  ),
	  array(
		"id" => "idchecklist",
		"nome" => "idchecklist",
		"nomeidioma" => "form_checklist",
		"tipo" => "select",
		"sql" => "SELECT idchecklist, nome FROM checklists WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
		"sql_valor" => "idchecklist", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"valor" => "idchecklist",
		/* "validacao" => array("required" => "checklist_vazio"), */
		"referencia_label" => "cadastro_checklist",
		"referencia_link" => "/gestor/relacionamento/checklist",
		"banco" => true
	  ),
	  array(
			"id" => "form_horas",
			"nome" => "sla", 
			"nomeidioma" => "form_sla",
			"tipo" => "input",
			//"horas" => true,
			"valor" => "sla",
			//"mascara" => "99:99:99", 
			"class" => "span1",
			"evento" => "maxlength='4'",
			"numerico" => true,
			"banco" => true,
			"banco_string" => true,
			),
	  array(
			"id" => "subassunto_obrigatorio",
			"nome" => "subassunto_obrigatorio",
			"nomeidioma" => "form_subassunto_obrigatorio",
			"tipo" => "select",
			"array" => "sim_nao",
			"class" => "span2", 
			"valor" => "subassunto_obrigatorio",
			"validacao" => array("required" => "subassunto_obrigatorio_vazio"),
			"ajudaidioma" => "form_subassunto_obrigatorio_ajuda",
			"banco" => true,
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
							)
			)								  
);
						
						// Array de configuração para a formulario			
			$config["formulario_subassunto"] = array(
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
															"id" => "idassunto",
															"nome" => "idassunto",
															"nomeidioma" => "form_assunto",
															"tipo" => "select",
															"sql" => "SELECT idassunto, nome FROM atendimentos_assuntos WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
															"sql_valor" => "idassunto", // Coluna da tabela que será usado como o valor do options
															"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
															"valor" => "idassunto",
															"validacao" => array("required" => "assunto_vazio"),
															"banco" => true
															),
													  array(
															"id" => "idchecklist",
															"nome" => "idchecklist",
															"nomeidioma" => "form_checklist",
															"tipo" => "select",
															"sql" => "SELECT idchecklist, nome FROM checklists WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
															"sql_valor" => "idchecklist", // Coluna da tabela que será usado como o valor do options
															"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
															"valor" => "idchecklist",
															//"validacao" => array("required" => "checklist_vazio"),
															"referencia_label" => "cadastro_checklist",
		                                                    "referencia_link" => "/gestor/relacionamento/checklist",
															"banco" => true
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
															)																																											
													)
									)								  
						);
?>