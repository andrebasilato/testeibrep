<?php			   
// Array de configuração para a formulario			
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( 
		array(
			"id" => "data_realizacao",
			"nome" => "data_realizacao",
			"nomeidioma" => "form_data_realizacao",
			"tipo" => "input", 
			"valor" => "data_realizacao",
			"valor_php" => 'if($dados["data_realizacao"]) return formataData("%s", "br", 0)',
			"class" => "span2",
			"mascara" => "99/99/9999",
			"datepicker" => true,
			"banco" => true, 
			"validacao" => array("required" => "data_realizacao_vazio"),
			"banco_php" => 'return formataData("%s", "en", 0)',
			"banco_string" => true 															
	  	),
	  	array(
			"id" => "idcurso",
			"nome" => "idcurso",
			"nomeidioma" => "form_idcurso",
			"tipo" => "select",
			"sql" => "SELECT idcurso, nome FROM cursos WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome", // SQL que alimenta o select
			"sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
			"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
			"valor" => "idcurso",
			"validacao" => array("required" => "curso_vazio"),
			"banco" => true
	  ),
	  array(
			"id" => "idescola",
			"nome" => "idescola",
			"nomeidioma" => "form_idescola",
			"tipo" => "select",
			"sql" => "SELECT idescola, nome_fantasia FROM escolas WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome_fantasia", // SQL que alimenta o select
			"sql_valor" => "idescola", // Coluna da tabela que será usado como o valor do options
			"sql_label" => "nome_fantasia", // Coluna da tabela que será usado como o label do options
			"valor" => "idescola",
			"validacao" => array("required" => "escola_vazio"),
			"banco" => true
	  ),
	)
  )								  
);
?>