<?php
// Array de configuração para a formulario
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario

	  array(
			"id" => "form_razao_social",
			"nome" => "razao_social",
			"nomeidioma" => "form_razao_social",
			"tipo" => "input",
			"valor" => "razao_social",
			"validacao" => array("required" => "razao_social_vazio"),
			"class" => "span5",
			"banco" => true,
			"banco_string" => true,
			"evento" => "maxlength='100'"
			),
	  array(
			"id" => "form_nome_fantasia",
			"nome" => "nome_fantasia",
			"nomeidioma" => "form_nome_fantasia",
			"tipo" => "input",
			"valor" => "nome_fantasia",
			"validacao" => array("required" => "nome_fantasia_vazio"),
			"class" => "span5",
			"banco" => true,
			"banco_string" => true,
			"evento" => "maxlength='100'"
			),
	  array(
			"id" => "form_documento_cnpj",
			"nome" => "documento",
			"nomeidioma" => "form_cnpj",
			"tipo" => "input",
			"valor" => "documento",
			"class" => "span3",
			"ajudaidioma" => "form_cnpj_ajuda",
			"evento" => " maxlength='14'", //readonly='readonly'
			"validacao" => array("required" => "cnpj_vazio", "valida_cnpj" => "cnpj_invalido"),
			"mascara" => "99.999.999/9999-99",
			"banco" => true,
			"banco_php" => 'return str_replace(array(".", "-", "/"),"","%s")',
			"banco_string" => true,
			),
	  array(
			"id" => "form_inscricao_estadual",
			"nome" => "inscricao_estadual",
			"nomeidioma" => "form_inscricao_estadual",
			"tipo" => "input",
			"valor" => "inscricao_estadual",
			"class" => "span5",
			"banco" => true,
			"banco_string" => true,
			"evento" => "maxlength='100'"
			),
	  array(
			"id" => "form_inscricao_municipal",
			"nome" => "inscricao_municipal",
			"nomeidioma" => "form_inscricao_municipal",
			"tipo" => "input",
			"valor" => "inscricao_municipal",
			"class" => "span5",
			"banco" => true,
			"banco_string" => true,
			"evento" => "maxlength='100'"
			),
	  array(
		"id" => "form_fax",
		"nome" => "fax",
		"nomeidioma" => "form_fax",
		"tipo" => "input",
		"valor" => "fax",
		"class" => "span3",
		"mascara" => "(99) 9999-9999",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_email",
		"nome" => "email",
		"nomeidioma" => "form_email",
		"tipo" => "input",
		"valor" => "email",
		"class" => "span5",
		"legenda" => "@",
		"banco" => true,
		"banco_string" => true,
		"evento" => "maxlength='100'"
	  ),
	  array(
		'id' => 'codigo',
		'nome' => 'codigo',
		'nomeidioma' => 'form_codigo',
		'tipo' => 'input',
		'valor' => 'codigo',
		//'validacao' => array('required' => 'estado_vazio'),
		'banco' => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_site", // Id do atributo HTML
		"nome" => "site", // Name do atributo HTML
		"nomeidioma" => "form_site", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"evento" => "maxlength='50'",
		"valor" => "site", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "nre_vazio"), // Validação do campo
		"class" => "span2", //Class do atributo HTML
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
		),
	  array(
		"id" => "form_telefone",
		"nome" => "telefone",
		"nomeidioma" => "form_telefone",
		"tipo" => "input",
		"valor" => "telefone",
		"class" => "span3",
		"mascara" => "(99) 9999-9999",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_cep",
		"nome" => "cep",
		"nomeidioma" => "form_cep",
		"tipo" => "input",
		"valor" => "cep",
		//"validacao" => array("required" => "cep_vazio"),
		"class" => "span2",
		"ajudaidioma" => "form_cep_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
		"mascara" => "99999-999", //Mascara do campo
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_php" => 'return str_replace(array("-", ""),"","%s")', // Executa um script php antes de salvar no banco (Utilizado na função SalvarDados)
		"banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	  array(
		"id" => "idlogradouro",
		"nome" => "idlogradouro",
		"nomeidioma" => "form_logradouro",
		"tipo" => "select",
		"sql" => "SELECT idlogradouro, nome FROM logradouros WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome ", // SQL que alimenta o select
		"sql_valor" => "idlogradouro", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"valor" => "idlogradouro",
		//"validacao" => array("required" => "logradouro_vazio"),
		"banco" => true
	  ),
	  array(
		"id" => "form_endereco", // Id do atributo HTML
		"nome" => "endereco", // Name do atributo HTML
		"nomeidioma" => "form_endereco", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"evento" => "maxlength='100'",
		"valor" => "endereco", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "endereco_vazio"), // Validação do campo
		"class" => "span6", //Class do atributo HTML
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	  array(
		"id" => "form_bairro", // Id do atributo HTML
		"nome" => "bairro", // Name do atributo HTML
		"nomeidioma" => "form_bairro", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"evento" => "maxlength='100'",
		"valor" => "bairro", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "bairro_vazio"), // Validação do campo
		"class" => "span5", //Class do atributo HTML
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	  array(
		"id" => "form_numero", // Id do atributo HTML
		"nome" => "numero", // Name do atributo HTML
		"nomeidioma" => "form_numero", // Referencia a variavel de idioma
		"ajudaidioma" => "form_numero_ajuda",
		"tipo" => "input", // Tipo do input
		"evento" => "maxlength='10'",
		"valor" => "numero", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "numero_vazio"), // Validação do campo
		"class" => "span2", //Class do atributo HTML
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	  array(
		"id" => "form_complemento", // Id do atributo HTML
		"nome" => "complemento", // Name do atributo HTML
		"nomeidioma" => "form_complemento", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"evento" => "maxlength='100'",
		"valor" => "complemento", // Nome da coluna da tabela do banco de dados que retorna o valor.
		//"validacao" => array("required" => "complemento_vazio"), // Validação do campo
		"class" => "span4", //Class do atributo HTML
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	  array(
		"id" => "idestado",
		"nome" => "idestado",
		"nomeidioma" => "form_idestado",
		"tipo" => "select",
		"sql" => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
		"sql_valor" => "idestado", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"valor" => "idestado",
		//"validacao" => array("required" => "estado_vazio"),
		"banco" => true
	  ),
	  array(
		"id" => "idcidade",
		"nome" => "idcidade",
		"nomeidioma" => "form_idcidade",
		"json" => true,
		"json_idpai" => "idestado",
		"json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"].'/'.$url["3"]."/ajax_cidades/",
		"json_input_pai_vazio" => "form_selecione_estado",
		"json_input_vazio" => "form_selecione_cidade",
		"json_campo_exibir" => "nome",
		"tipo" => "select",
		"valor" => "idcidade",
		//"validacao" => array("required" => "cidade_vazio"),
		"banco" => true
	  ),
	  array(
		"id" => "form_upload", // Id do atributo HTML
		"nome" => "logo", // Name do atributo HTML
		"nomeidioma" => "form_upload", // Referencia a variavel de idioma
		"arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
		"arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
		"tipo" => "file", // Tipo do input
		"extensoes" => 'jpg|jpeg|gif|png|bmp',
		"largura" => 350,
		"altura" => 180,
		"validacao" => array("formato_arquivo" => "arquivo_invalido"),
		"class" => "span6", //Class do atributo HTML
		"pasta" => "mantenedoras_logo",
		"download" => true,
		"excluir" => true,
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_campo" => "logo", // Nome das colunas da tabela do banco de dados que retorna o valor.
		"ignorarsevazio" => true
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
  ),
  array(
	"fieldsetid" => "dados_gerente", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendada_dados_gerente", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario
	  array(
		"id" => "form_gerente_nome",
		"nome" => "gerente_nome",
		"nomeidioma" => "form_gerente_nome",
		"tipo" => "input",
		"evento" => "maxlength='100'",
		"valor" => "gerente_nome",
		"class" => "span6",
		"banco" => true,
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_gerente_telefone",
		"nome" => "gerente_telefone",
		"nomeidioma" => "form_gerente_telefone",
		"tipo" => "input",
		"valor" => "gerente_telefone",
		"class" => "span3",
		"mascara" => "(99) 9999-9999",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_gerente_celular",
		"nome" => "gerente_celular",
		"nomeidioma" => "form_gerente_celular",
		"tipo" => "input",
		"valor" => "gerente_celular",
		"class" => "span3",
		"mascara" => "(99) 9999-9999",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_gerente_email",
		"nome" => "gerente_email",
		"nomeidioma" => "form_gerente_email",
		"tipo" => "input",
		"valor" => "gerente_email",
		"validacao" => array("valid_email" => "email_invalido"),
		"class" => "span5",
		"legenda" => "@",
		"banco" => true,
		"banco_string" => true,
		"evento" => "maxlength='100'"
	  ),
	)
  )
);
?>