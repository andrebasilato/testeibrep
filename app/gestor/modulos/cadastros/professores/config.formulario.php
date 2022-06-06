<?php
// Array de configuração para a formulario
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array(
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
		"id" => "estado_civil",
		"nome" => "estado_civil",
		"nomeidioma" => "form_estadocivil",
		"tipo" => "select",
		"array" => "estadocivil",
		"class" => "span2",
		"valor" => "estado_civil",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "tipo",
		"nome" => "tipo",
		"nomeidioma" => "form_tipo",
		"tipo" => "select",
		"array" => "tipo_professor_config",
		"validacao" => array("required" => "tipo_vazio"),
		"class" => "span2",
		"valor" => "tipo",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "data_nasc",
		"nome" => "data_nasc",
		"nomeidioma" => "form_nascimento",
		"tipo" => "input",
		"valor" => "data_nasc",
		//"validacao" => array("required" => "data_nasc_vazio"),
		"valor_php" => 'if($dados["data_nasc"] && $dados["data_nasc"] != "0000-00-00") return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		//"datepicker" => true,
		"banco" => true,
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true
	  ),
	  array(
		"id" => "form_idpais",
		"nome" => "idpais",
		"nomeidioma" => "form_nacionalidade",
		"tipo" => "select",
		"valor" => "idpais",
		//"validacao" => array("required" => "nacionalidade_vazio"),
		"class" => "invisivel",
		"banco" => true,
		"banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	  array(
		"id" => "form_naturalidade",
		"nome" => "naturalidade",
		"nomeidioma" => "form_naturalidade",
		"tipo" => "input",
		"valor" => "naturalidade",
		//"validacao" => array("required" => "naturalidade_vazio"),
		"ajudaidioma" => "form_naturalidade_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
		"class" => "span3",
		"evento" => "maxlength='100'",
		"banco" => true,
		"banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	  array(
		"id" => "form_ativo_painel",
		"nome" => "ativo_painel_aluno",
		"nomeidioma" => "form_ativo_painel",
		"tipo" => "select",
		"array" => "ativo", // Array que alimenta o select
		"class" => "span2",
		"valor" => "ativo_painel_aluno",
		"validacao" => array("required" => "ativo_vazio"),
		"ajudaidioma" => "form_ativo_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	)
  ),
  array(
	"fieldsetid" => "dados_documentos", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legenda_dados_documentos", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario
	  /*array(
		"id" => "form_tipo",
		"nome" => "documento_tipo",
		"nomeidioma" => "form_tipo",
		"botao_hide" => true,
		"iddivs" => array("documento","documento_cnpj"),
		"evento" => "disabled='disabled'",
		"tipo" => "select",
		"iddiv" => "documento",
		"iddiv2" => "documento_cnpj",
		"array" => "tipo_documento", // Array que alimenta o select
		"class" => "span2",
		"valor" => "documento_tipo",
		"validacao" => array("required" => "tipo_vazio"),
		"banco" => true,
		"banco_string" => true,
	  ),*/
	  array(
		"id" => "form_documento",
		"nome" => "documento",
		"nomeidioma" => "form_cpf",
		"tipo" => "input",
		"valor" => "documento",
		"class" => "span3",
		"ajudaidioma" => "form_cpf_ajuda",
		"evento" => "maxlength='11'",
		"validacao" => array("required" => "cpf_vazio", "valida_cpf" => "cpf_invalido"),
		"mascara" => "999.999.999-99",
		"banco" => true,
		"banco_php" => 'return str_replace(array(".", "-","/"),"","%s")',
		"banco_string" => true,
		//"input_hidden" => true,
	  ),
	  /*array(
		"id" => "form_documento_cnpj",
		"nome" => "documento_cnpj",
		"nomeidioma" => "form_cnpj",
		"tipo" => "input",
		"valor" => "documento",
		"class" => "span3",
		"ajudaidioma" => "form_cnpj_ajuda",
		"evento" => "readonly='readonly' maxlength='11'",
		"validacao" => array("required" => "cnpj_vazio", "valida_cnpj" => "cnpj_invalido"),
		"mascara" => "99.999.999/9999-99",
		"banco" => true,
		"banco_php" => 'return str_replace(array(".", "-", "/"),"","%s")',
		"banco_string" => true,
		"input_hidden" => true,
	  ),*/
	  array(
		"id" => "form_rg",
		"nome" => "rg",
		"nomeidioma" => "form_rg",
		"tipo" => "input",
		"valor" => "rg",
		//"validacao" => array("required" => "rg_vazio"),
		"class" => "span2",
		"evento" => "maxlength='20'",
		"numerico" => true,
		"banco" => true,
		"banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	  array(
		"id" => "form_rg_orgao_emissor",
		"nome" => "rg_orgao_emissor",
		"nomeidioma" => "form_orgao_emissor",
		"tipo" => "input",
		"valor" => "rg_orgao_emissor",
		//"validacao" => array("required" => "orgao_emissor_vazio"),
		"class" => "span2",
		"evento" => "maxlength='20'",
		"banco" => true,
		"banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
	  ),
	  array(
		"id" => "rg_data_emissao",
		"nome" => "rg_data_emissao",
		"nomeidioma" => "form_data_emissao",
		"tipo" => "input",
		"valor" => "rg_data_emissao",
		"valor_php" => 'if($dados["rg_data_emissao"]) return formataData("%s", "br", 0)',
		"class" => "span2",
		"mascara" => "99/99/9999",
		"datepicker" => true,
		"banco" => true,
		"banco_php" => 'return formataData("%s", "en", 0)',
		"banco_string" => true
	  )
	)
  ),
  array(
	"fieldsetid" => "dados_endereco", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legenda_dados_endereco", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario
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
	  )
	)
  ),
  array(
	"fieldsetid" => "dados_contato", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legenda_dados_contato", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario
	  array(
		"id" => "form_email",
		"nome" => "email",
		"nomeidioma" => "form_email",
		"tipo" => "input",
		"valor" => "email",
		"ajudaidioma" => "form_email_ajuda",
		"validacao" => array("required" => "email_vazio", "valid_email" => "email_invalido"),
		"class" => "span5",
		"legenda" => "@",
		"banco" => true,
		"banco_string" => true,
		"evento" => "maxlength='100'"
	  ),
	  array(
		"id" => "form_telefone",
		"nome" => "telefone",
		"nomeidioma" => "form_telefone",
		"tipo" => "input",
		"valor" => "telefone",
		//"validacao" => array("required" => "telefone_vazio"),
		"class" => "span2",
		"mascara" => "(99) 9999-9999",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_celular",
		"nome" => "celular",
		"nomeidioma" => "form_celular",
		"tipo" => "input",
		"valor" => "celular",
		"class" => "span2",
		"mascara" => "(99) 9999-9999",
		"banco" => true,
		"banco_string" => true
	  ),
	)
  ),
  array(
	"fieldsetid" => "dadosdeacesso",
	"legendaidioma" => "legendadadosdeacesso",
	"campos" => array(
	  array(
		"id" => "form_senha",
		"nome" => "senha",
		"nomeidioma" => "form_senha",
		"tipo" => "input",
		"senha" => true,
		"ajudaidioma" => "form_senha_ajuda",
		"class" => "span3 verificaSenha",
		"validacao" => array("length>=8" => "minimo_senha", "length<=30" => "maximo_senha"),
		"legenda" => "#", // Adiciona uma legenda ao campo no formulario
		"ignorarsevazio" => true, // ignora o campo se ele for uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco" => true,
		"banco_php" => 'return senhaSegura("%s","'.$config["chaveLogin"].'")',
		"banco_string" => true ,
		"evento" => "maxlength='30'"
	  ),
	  array(
		"id" => "form_confirma",
		"nome" => "confirma",
		"nomeidioma" => "form_confirma",
		"tipo" => "input",
		"senha" => true, // Informa que o campo é uma senha (password)
		"ajudaidioma" => "form_confirma_ajuda",
		"validacao" => array("same_as,senha" => "confirmacao_invalida"),
		"class" => "span4",
		"evento" => "maxlength='30'"
	  )
	)
  ),
  array(
	"fieldsetid" => "dados_outros", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legenda_dados_outros", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario
	  array(
		"id" => "form_observacoes",
		"nome" => "observacoes",
		"nomeidioma" => "form_observacoes",
		"tipo" => "text",
		"valor" => "observacoes",
		"class" => "xxlarge",
		"banco" => true,
		"banco_string" => true
	  )
	)
    ),
    array(
	"fieldsetid" => "avatar", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legenda_avatar", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario
	  array(
		"id" => "form_avatar", // Id do atributo HTML
		"nome" => "avatar", // Name do atributo HTML
		"nomeidioma" => "form_avatar", // Referencia a variavel de idioma
		"arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
		"arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
		"tipo" => "file", // Tipo do input
		"extensoes" => 'jpg|jpeg|gif|png|bmp',
		"ajudaidioma" => "form_avatar_ajuda",
		//"largura" => 350,
		//"altura" => 180,
		"validacao" => array("formato_arquivo" => "arquivo_invalido"),
		"class" => "span6", //Class do atributo HTML
		"pasta" => "professores_avatar",
		"download" => true,
		"download_caminho" => $url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"],
		"excluir" => true,
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_campo" => "avatar", // Nome das colunas da tabela do banco de dados que retorna o valor.
		"ignorarsevazio" => true
	  ),
	)
  )
);
