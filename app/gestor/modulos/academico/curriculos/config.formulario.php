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
		"id" => "form_carga_horaria",
		"nome" => "carga_horaria", 
		"nomeidioma" => "form_carga_horaria",
		"tipo" => "input",
		"valor" => "carga_horaria",
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
	  ),	  
	  array(
		"id" => "form_idcurso",
		"nome" => "idcurso",
		"nomeidioma" => "form_idcurso",
		"tipo" => "select",
		"sql" => "select idcurso, nome from cursos where ativo = 'S' and ativo_painel = 'S' order by nome", // SQL que alimenta o select
		"sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"valor" => "idcurso",
		"validacao" => array("required" => "idcurso_vazio"),
		"referencia_label" => "cadastro_curso",
		"referencia_link" => "/gestor/academico/cursos",
		"banco" => true
	  ),
	  /*array(
		"id" => "form_media", // Id do atributo HTML
		"nome" => "media", // Name do atributo HTML
		"nomeidioma" => "form_media", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"evento" => "maxlength='5'",
		"decimal" => true,
		"valor" => "media", // Nome da coluna da tabela do banco de dados que retorna o valor.
		"validacao" => array("required" => "media_vazio"), // Validação do campo
		"class" => "span1", //Class do atributo HTML
		"classe_label" => "control-label",
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),*/
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
  ),
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadaaprovacao", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario
	  array(
		"id" => "form_dias_minimo", // Id do atributo HTML
		"nome" => "dias_minimo", // Name do atributo HTML
		"nomeidioma" => "form_dias_minimo", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"evento" => "maxlength='5'",
		"ajudaidioma" => "form_dias_minimo_ajuda",
		//"decimal" => true,
		"numerico" => true,
		"valor" => "dias_minimo", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "media_vazio"), // Validação do campo
		"class" => "span1", //Class do atributo HTML
		"classe_label" => "control-label",
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	  array(
		"id" => "form_dias_maximo", // Id do atributo HTML
		"nome" => "dias_maximo", // Name do atributo HTML
		"nomeidioma" => "form_dias_maximo", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"evento" => "maxlength='5'",
		"ajudaidioma" => "form_dias_maximo_ajuda",
		//"decimal" => true,
		"numerico" => true,
		"valor" => "dias_maximo", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "media_vazio"), // Validação do campo
		"class" => "span1", //Class do atributo HTML
		"classe_label" => "control-label",
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	  array(
		"id" => "form_media", // Id do atributo HTML
		"nome" => "media", // Name do atributo HTML
		"nomeidioma" => "form_media", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"evento" => "maxlength='5'",
		"decimal" => true,
		"valor" => "media", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "media_vazio"), // Validação do campo
		"class" => "span1", //Class do atributo HTML
		"classe_label" => "control-label",
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),	
	  array(
		"id" => "form_porcentagem_ava", // Id do atributo HTML
		"nome" => "porcentagem_ava", // Name do atributo HTML
		"nomeidioma" => "form_porcentagem_ava", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		'evento' => 'maxlength="6"',
		'decimal' => true,
		"valor" => "porcentagem_ava", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "media_vazio"), // Validação do campo
		"class" => "span1", //Class do atributo HTML
		"classe_label" => "control-label",
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	)
  )  
);
?>