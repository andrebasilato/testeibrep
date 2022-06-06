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
		"id" => "form_formula",
		"nome" => "formula",
		"nomeidioma" => "form_formula",
		"tipo" => "text",
		"valor" => "formula",
		"validacao" => array("required" => "formula_vazio"),
		"class" => "span6",
		"banco" => true,
		"banco_string" => true
	  ),																															
	)
  )								  
);

$config["formulario_validar"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
		"id" => "form_formula",
		"nome" => "formula",
		"nomeidioma" => "form_formula",
		"tipo" => "text",
		"valor" => "formula",
		"evento" => "disabled='disabled'",
		"class" => "span6",
		"banco" => true,
		"banco_string" => true
	  ),
	),
  ),
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados_variaveis", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario		
	  array(
		"id" => "form_nota_normal_1",
		"nome" => "nota_normal_1", 
		"nomeidioma" => "form_nota_normal_1",
		"tipo" => "input",
		"valor" => "nota_normal_1",
		"evento" => "maxlength='5'",
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"input_hidden" => true,
	  ),
	  array(
		"id" => "form_nota_normal_2",
		"nome" => "nota_normal_2", 
		"nomeidioma" => "form_nota_normal_2",
		"tipo" => "input",
		"evento" => "maxlength='5'",
		"valor" => "nota_normal_2",
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"input_hidden" => true,
	  ),
	  array(
		"id" => "form_nota_virtual_1",
		"nome" => "nota_virtual_1", 
		"nomeidioma" => "form_nota_virtual_1",
		"tipo" => "input",
		"evento" => "maxlength='5'",
		"valor" => "nota_virtual_1",
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"input_hidden" => true,
	  ),
	  array(
		"id" => "form_nota_virtual_2",
		"nome" => "nota_virtual_2", 
		"nomeidioma" => "form_nota_virtual_2",
		"tipo" => "input",
		"evento" => "maxlength='5'",
		"valor" => "nota_virtual_2",
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"input_hidden" => true,
	  ),
	  array(
		"id" => "form_peso_normal_1",
		"nome" => "peso_normal_1", 
		"evento" => "maxlength='2'",
		"nomeidioma" => "form_peso_normal_1",
		"tipo" => "input",
		"valor" => "peso_normal_1",
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"input_hidden" => true,
	  ),
	  array(
		"id" => "form_peso_normal_2",
		"nome" => "peso_normal_2", 
		"nomeidioma" => "form_peso_normal_2",
		"tipo" => "input",
		"evento" => "maxlength='2'",
		"valor" => "peso_normal_2",
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"input_hidden" => true,
	  ),
	  array(
		"id" => "form_peso_virtual_1",
		"nome" => "peso_virtual_1", 
		"nomeidioma" => "form_peso_virtual_1",
		"tipo" => "input",
		"evento" => "maxlength='2'",
		"valor" => "peso_virtual_1",
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"input_hidden" => true,
	  ),
	  array(
		"id" => "form_peso_virtual_2",
		"nome" => "peso_virtual_2", 
		"nomeidioma" => "form_peso_virtual_2",
		"tipo" => "input",
		"evento" => "maxlength='2'",
		"valor" => "peso_virtual_2",
		"class" => "span2",
		"banco" => true,
		"banco_string" => true,
		"input_hidden" => true,
	  ),																															
	)
  )								  
);
?>