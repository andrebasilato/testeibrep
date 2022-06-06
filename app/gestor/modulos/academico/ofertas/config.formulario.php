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
        "evento" => "maxlength='100'"
	  ),
	  /*array(
		"id" => "form_status",
		"nome" => "status",
		"nomeidioma" => "form_status",
		"tipo" => "select",
		"array" => "status_oferta", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "status",
		"validacao" => array("required" => "status_vazio"),
		"banco" => true,
		"banco_string" => true
	  ),*/
	  array(
		"id" => "idsituacao", // Id do atributo HTML
		"nome" => "idsituacao", // Name do atributo HTML
		"tipo" => "hidden", // Tipo do input
		"valor" => "0",
		"banco" => true
	  ),	  
	  array(
		"id" => "data_inicio_matricula",
		"nome" => "data_inicio_matricula",
		"nomeidioma" => "form_data_inicio_matricula",
		"tipo" => "input", 
		"valor" => "data_inicio_matricula",
		"valor_php" => 'if($dados["data_inicio_matricula"]) return formataData("%s", "br", 0)',
		"evento" => "readonly='readonly' style='cursor:text;'",
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"validacao" => array("required" => "data_inicio_matricula_vazio"),
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),
	  array(
		"id" => "data_fim_matricula",
		"nome" => "data_fim_matricula",
		"nomeidioma" => "form_data_fim_matricula",
		"tipo" => "input", 
		"valor" => "data_fim_matricula",
		"valor_php" => 'if($dados["data_fim_matricula"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"evento" => "readonly='readonly' style='cursor:text;'",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"validacao" => array("required" => "data_fim_matricula_vazio"),
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
	  ),
	  /*
	  array(
		"id" => "data_inicio_acesso_ava",
		"nome" => "data_inicio_acesso_ava",
		"nomeidioma" => "form_data_inicio_acesso_ava",
		"tipo" => "input", 
		"valor" => "data_inicio_acesso_ava",
		"valor_php" => 'return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
		),
	  array(
		"id" => "data_fim_acesso_ava",
		"nome" => "data_fim_acesso_ava",
		"nomeidioma" => "form_data_fim_acesso_ava",
		"tipo" => "input", 
		"valor" => "data_fim_acesso_ava",
		"valor_php" => 'return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true, 
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true 															
		),
		*/
	  array(
		"id" => "form_modalidade",
		"nome" => "modalidade",
		"nomeidioma" => "form_modalidade",
		"tipo" => "select",
		"array" => "tipo_oferta", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "modalidade",
		"validacao" => array("required" => "modalidade_vazio"),
		//"ajudaidioma" => "form_ativo_ajuda",
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
?>