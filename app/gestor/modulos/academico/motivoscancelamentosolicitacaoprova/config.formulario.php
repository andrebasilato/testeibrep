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
		"evento" => "maxlength='100'",
		"class" => "span6",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_descricao",
		"nome" => "descricao",
		"nomeidioma" => "form_descricao",
		"tipo" => "text",
		"valor" => "descricao",
		"class" => "xxlarge",
		"class" => "span6",
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
	  array(
		"id" => "form_exibir_aluno",
		"nome" => "exibir_aluno",
		"nomeidioma" => "form_exibir_aluno",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "exibir_aluno",
		"validacao" => array("required" => "exibir_aluno_vazio"),
		"ajudaidioma" => "form_exibir_aluno_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),																															
	)
  )								  
);
?>