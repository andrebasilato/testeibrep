<?php			   
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
	  ),	  
	  array(
		"id" => "form_codigo", // Id do atributo HTML
		"nome" => "codigo", // Name do atributo HTML
		"nomeidioma" => "form_codigo", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		//"numerico" => true,
		"valor" => "codigo", // Nome da coluna da tabela do banco de dados que retorna o valor.
		"validacao" => array("required" => "codigo_vazio"), // Validação do campo
		"class" => "span2", //Class do atributo HTML
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
		"evento" => "maxlength='10'"
	  ),
	  array(
		"id" => "form_tipo",
		"nome" => "tipo",
		"nomeidioma" => "form_tipo",
		"tipo" => "select",
		"array" => "tipo_disciplina", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "tipo",
		"validacao" => array("required" => "tipo_vazio"),
		//"ajudaidioma" => "form_tipo_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_avaliacaoprese",
		"nome" => "avaliacao_presencial",
		"nomeidioma" => "form_avaliacaoprese",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "avaliacao_presencial",
		"validacao" => array("required" => "avaliacaoprese_vazio"),
		//"ajudaidioma" => "form_tipo_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
/*	  
	  array(
		"id" => "form_carga_horaria", // Id do atributo HTML
		"nome" => "carga_horaria", // Name do atributo HTML
		"nomeidioma" => "form_carga_horaria", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"numerico" => true,
		"valor" => "carga_horaria", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "carga_horaria_vazio"), // Validação do campo
		"class" => "span2", //Class do atributo HTML
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
		"evento" => "maxlength='10'"
	  ),
*/	  
	  array(
		"id" => "form_pratica", // Id do atributo HTML
		"nome" => "pratica", // Name do atributo HTML
		"nomeidioma" => "form_pratica", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"numerico" => true,
		"valor" => "pratica", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "pratica_vazio"), // Validação do campo
		"class" => "span2", //Class do atributo HTML
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
		"evento" => "maxlength='10'"
	  ),
	  array(
		"id" => "form_teorica", // Id do atributo HTML
		"nome" => "teorica", // Name do atributo HTML
		"nomeidioma" => "form_teorica", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"numerico" => true,
		"valor" => "teorica", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "teorica_vazio"), // Validação do campo
		"class" => "span2", //Class do atributo HTML
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
		"evento" => "maxlength='10'"
	  ),
	  array(
		"id" => "form_laboratorio", // Id do atributo HTML
		"nome" => "laboratorio", // Name do atributo HTML
		"nomeidioma" => "form_laboratorio", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"numerico" => true,
		"valor" => "laboratorio", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "laboratorio_vazio"), // Validação do campo
		"class" => "span2", //Class do atributo HTML
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
		"evento" => "maxlength='10'"
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
		"id" => "form_observacoes",
		"nome" => "observacoes",
		"nomeidioma" => "form_observacoes",
		"tipo" => "text",
		//"editor" => true,
		"valor" => "observacoes",
		"class" => "span6",
		"banco" => true,
		"banco_string" => true
	  ),																														
	)
  )								  
);
?>