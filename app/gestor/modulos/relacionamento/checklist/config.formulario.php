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
															"class" => "span5",
															"banco" => true,
															"banco_string" => true,
															),
													  array(
															"id" => "form_obrigatorio",
															"nome" => "obrigatorio",
															"nomeidioma" => "form_obrigatorio",
															"tipo" => "select",
															"array" => "sim_nao", // Array que alimenta o select
															"class" => "span2", 
															"valor" => "obrigatorio",
															"validacao" => array("required" => "obrigatorio_vazio"),
															"banco" => true,
															"banco_string" => true
															),
													  array(
															"id" => "form_ativo_usuarios",
															"nome" => "ativo_painel",
															"nomeidioma" => "form_ativo_usuarios",
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
		
			
			$config["formulario_opcoes"] = array(
							  array("fieldsetid" => "dadosdainformacao", // Titulo do formulario (referencia a variavel de idioma)
									"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
									
									"campos" => array( // Campos do formulario
															
													  array(
															"id" => "idchecklist", // Id do atributo HTML
															"nome" => "idchecklist", // Name do atributo HTML
															"tipo" => "hidden", // Tipo do input
															"valor" => 'return $this->url["3"];',
															"banco" => true
															),

													   array(
															"id" => "form_nome",
															"nome" => "nome", 
															"nomeidioma" => "form_nome",
															"tipo" => "input",
															"valor" => "nome",
															"layout_horizontal" => true,
															"class_layout" => "span6 well wellDestaque",
															"validacao" => array("required" => "nome_vazio"), 
															"class" => "span4",
															"banco" => true,
															"banco_string" => true,
															),
															
													  /*array(
															"id" => "btn_submit",
															"nome" => "btn_submit",
															"tipo" => "botao",
															"tipo_botao" => "submit",
															"class" => "span1",
															"class_botao" => "btn btn-primary",
															"nomeidioma" => "btn_adicionar",
															"fim_layout" => true
															)*/
														)
									)
						);
?>