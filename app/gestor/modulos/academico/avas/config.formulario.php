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
        "evento" => "maxlength='100'",
	  ),
	  array(
		"id" => "form_pre_requisito",
		"nome" => "pre_requisito",
		"nomeidioma" => "form_pre_requisito",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "pre_requisito",
		"validacao" => array("required" => "pre_requisito_vazio"),
		"ajudaidioma" => "form_pre_requisito_ajuda",
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
		"id" => "form_contabilizar_datas",
		"nome" => "contabilizar_datas",
		"nomeidioma" => "form_contabilizar_datas",
		"tipo" => "select",
		"array" => "sim_nao", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "contabilizar_datas",
		"validacao" => array("required" => "contabilizar_datas_vazio"),
		"ajudaidioma" => "form_contabilizar_datas_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_carga_min",
		"nome" => "carga_horaria_min", 
		"nomeidioma" => "form_carga_min",
		"tipo" => "input",
		"valor" => "carga_horaria_min",
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
		'numerico' => true,
        'evento' => 'maxlength="5"'
	  ),																													
	),
  )	,
  array(
  	"fieldsetid" => "dadosdosimulado", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosimulado", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																						
	  array(
		"id" => "form_simulado_apartir",
		"nome" => "simulados_apartirde", 
		"nomeidioma" => "form_simulado_apartir",
		"tipo" => "input",
		"valor" => "simulados_apartirde",
		"valor_php" => 'if($dados["simulados_apartirde"] && $dados["simulados_apartirde"] != "0000-00-00") return formataData("%s", "br", 0)',
		"mascara" => "99/99/9999",
		"class" => "span2",
		"banco" => true,
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true,		
	  ),
	  array(
		"id" => "form_simulado_link",
		"nome" => "simulados_link", 
		"nomeidioma" => "form_simulado_link",
		"tipo" => "input",
		// "legenda" => "http://",
		"valor" => "simulados_link",
		"banco" => true,
		"class" => "span6", 
		"banco_string" => true,
	  ),	  																															
	),
  ),

	array(
		"fieldsetid" => "dadosdosmodulos", // Titulo do formulario (referencia a variavel de idioma)
		"legendaidioma" => "legendadadosmodulos", // Legenda do fomrulario (referencia a variavel de idioma)
		"campos" => array( // Campos do formulario																						
		array(
			"id" => "modulos",
			"nome" => "modulos",
			"nomeidioma" => "form_modulos",
			"tipo" => "checkbox",
			"array" => "modulos_ava",
			"array_serializado" => "modulos"
			),	  																															
		),
	),  
  
	array(
		"fieldsetid" => "dadosdosmodulos", // Titulo do formulario (referencia a variavel de idioma)
		"legendaidioma" => "legendadadosinstrucoes", // Legenda do fomrulario (referencia a variavel de idioma)
		"campos" => array( // Campos do formulario																						
			array(
				"id" => "form_instrucoes",
				"nome" => "instrucoes",
				"nomeidioma" => "form_instrucoes",
				"tipo" => "text",
				"editor" => true,
				"valor" => "instrucoes",
				"class" => "xxlarge",
				"banco" => true,
				"banco_string" => true
			  ),
		),
	)  
  
  							  
);
?>