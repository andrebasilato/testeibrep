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
		"id" => "form_codigo",
		"nome" => "codigo", 
		"nomeidioma" => "form_codigo",
		"tipo" => "input",
		"valor" => "codigo",
		//"validacao" => array("required" => "codigo_vazio"), 
		"class" => "span2",
		"banco" => true,
		"evento" => "maxlength='10'",
		"banco_string" => true,
	  ),
	  array(
		"id" => "form_idsindicato",
		"nome" => "idsindicato",
		"nomeidioma" => "form_idsindicato",
		"tipo" => "select",
		"sql" => "SELECT i.idsindicato, i.nome 
					FROM sindicatos i 
					inner join usuarios_adm ua on ua.idusuario = ".$usuario['idusuario']."
				    left join usuarios_adm_sindicatos uai on i.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario
					where i.ativo = 'S' AND i.ativo_painel = 'S' 
						  and (ua.gestor_sindicato = 'S' or uai.idusuario is not null) ", // SQL que alimenta o select
		"sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"valor" => "idsindicato",
		"validacao" => array("required" => "idsindicato_vazio"),
		"referencia_label" => "cadastro_sindicato",
		"referencia_link" => "/gestor/cadastros/sindicatos",
		"banco" => true
	  ),
	  array(
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
		"referencia_label" => "cadastro_tipodocumento",
		"referencia_link" => "/gestor/configuracoes/tiposdocumentos",
		"banco" => true,
		"banco_string" => true, 
	  ),
	  array(
		"id" => "form_documento",
		"nome" => "documento",
		"nomeidioma" => "form_cpf",
		"tipo" => "input", 
		"valor" => "documento",
		"class" => "span3",
		"ajudaidioma" => "form_cpf_ajuda", 
		"evento" => "disabled='disabled' maxlength='14'", 
		"validacao" => array(/*"required" => "cpf_vazio",*/ "valida_cpf" => "cpf_invalido"),
		"mascara" => "999.999.999-99",
		"banco" => true,
		"banco_php" => 'return str_replace(array(".", "-","/"),"","%s")',
		"banco_string" => true, 
		"input_hidden" => true,															
	  ),
	  array(
		"id" => "form_documento_cnpj",
		"nome" => "documento_cnpj",
		"nomeidioma" => "form_cnpj",
		"tipo" => "input", 
		"valor" => "documento",
		"class" => "span3",
		"ajudaidioma" => "form_cnpj_ajuda", 
		"evento" => "disabled='disabled' maxlength='18'", 
		"validacao" => array(/*"required" => "cnpj_vazio",*/ "valida_cnpj" => "cnpj_invalido"),
		"mascara" => "99.999.999/9999-99",
		"banco" => true, 
		"banco_php" => 'return str_replace(array(".", "-", "/"),"","%s")', 
		"banco_string" => true,
		"input_hidden" => true,
	  ),
	  array(
		"id" => "form_email",
		"nome" => "email",
		"nomeidioma" => "form_email",
		"tipo" => "input", 
		"valor" => "email",
		//"ajudaidioma" => "form_email_ajuda",
		"validacao" => array(/*"required" => "email_vazio",*/ "valid_email" => "email_invalido"),
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
		"id" => "form_fax",
		"nome" => "fax",
		"nomeidioma" => "form_fax",
		"tipo" => "input", 
		"valor" => "fax",
		//"validacao" => array("required" => "fax_vazio"),
		"class" => "span2",
		"mascara" => "(99) 9999-9999",
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
);
?>