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
															"class" => "span4",
															"banco" => true,
															"banco_string" => true,
															"evento" => "maxlength='250'"
															),
													  array(
															"id" => "form_tipo",
															"nome" => "tipo",
															"nomeidioma" => "form_tipo",
															"botao_hide" => true,
															"iddivs" => array("multipla_escolha","sentido","quantidade_colunas"),
															"tipo" => "select",
															"iddiv" => "multipla_escolha",
															"iddiv2" => "sentido",
															"iddiv3" => "quantidade_colunas",
															"array" => "tipo", // Array que alimenta o select
															"class" => "span2", 
															"valor" => "tipo",
															"validacao" => array("required" => "tipo_vazio"),
															"ajudaidioma" => "form_tipo_ajuda",
															"banco" => true,
															"banco_string" => true
															),	
													  array(
															"id" => "form_multipla_escolha",
															"nome" => "multipla_escolha",
															"nomeidioma" => "form_multipla_escolha",
															"tipo" => "select",
															"botao_hidden" => true,
															"select_hidden" => true,
															"array" => "sim_nao", // Array que alimenta o select
															"class" => "span2", 
															"valor" => "multipla_escolha",
															"validacao" => array("required" => "multipla_escolha_vazio"),
															"ajudaidioma" => "form_multipla_escolha_ajuda",
															"banco" => true,
															"nao_nulo" => true,
															"campo_hidden" => true,
															"classe_label" => "control-label",
															"banco_string" => true															
															),
													   array(
															"id" => "form_sentido",
															"nome" => "sentido",
															"nomeidioma" => "form_sentido",
															"tipo" => "select",
															"array" => "sentido", // Array que alimenta o select
															"class" => "span2", 
															"valor" => "sentido",
															//"validacao" => array("required" => "sentido_vazio"),
															"ajudaidioma" => "form_sentido_ajuda",
															"banco" => true,
															"select_hidden" => true,
															"banco_string" => true
															),
															
													  array(
															"id" => "form_quantidade_colunas",
															"nome" => "quantidade_colunas", 
															"nomeidioma" => "form_quantidade_colunas",
															"tipo" => "input",
															"valor" => "quantidade_colunas",
															"class" => "span2",
															"numerico" => true,
															"input_hidden" => true,
															"banco" => true,
															),
															
													  array(
															"id" => "form_espacamento_esquerda",
															"nome" => "espacamento_esquerda", 
															"nomeidioma" => "form_espacamento_esquerda",
															"tipo" => "input",
															"valor" => "espacamento_esquerda",
															"ajudaidioma" => "espacamento_esquerda_ajuda",
															"class" => "span2",
															"numerico" => true,
															"banco" => true,
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
?>