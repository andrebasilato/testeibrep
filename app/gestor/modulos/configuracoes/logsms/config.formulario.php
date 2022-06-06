<?php			   
// Array de configuração para a formulario			
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( 
	  // Campos do formulario																						
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
		"id" => "form_tipo",
		"nome" => "tipo",
		"nomeidioma" => "form_tipo",
		"tipo" => "select",
		"array" => "tipos_emails_automaticos", // Array que alimenta o select
		"class" => "span4", 
		"valor" => "tipo",
		"validacao" => array("required" => "tipo_vazio"),
		"ajudaidioma" => "form_tipo_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  /*array(
		"id" => "idsindicato",
		"nome" => "idsindicato",
		"nomeidioma" => "form_sindicato",
		"tipo" => "select",
		"sql" => "select idsindicato, concat('(',idsindicato,') ',nome) as nome FROM sindicatos WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome ", // SQL que alimenta o select
		"sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"valor" => "idsindicato",
		"class" => "span4", 
		"referencia_label" => "cadastro_sindicato",
		"referencia_link" => "/gestor/cadastros/sindicatos",
		"banco" => true
	  ),
	  array(
		"id" => "idcurso",
		"nome" => "idcurso",
		"nomeidioma" => "form_curso",
		"tipo" => "select",
		"sql" => "SELECT idcurso, nome FROM cursos WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome ", // SQL que alimenta o select
		"sql_valor" => "idcurso", // Coluna da tabela que será usado como o valor do options
		"sql_label" => "nome", // Coluna da tabela que será usado como o label do options
		"valor" => "idcurso",
		"class" => "span4", 
		"referencia_label" => "cadastro_curso",
		"referencia_link" => "/gestor/academico/cursos",
		"banco" => true
	  ),*/	  
	  array(
		"id" => "form_dia",
		"nome" => "dia", 
		"nomeidioma" => "form_dia",
		"evento" => "maxlength='4'",
		"tipo" => "input",
		"valor" => "dia",
		"class" => "span1",
		"banco" => true,
		"banco_string" => true,
		"ajudaidioma" => "form_dia_ajuda",
	  ),	  
	  array(
		"id" => "form_porcentagem", // Id do atributo HTML
		"nome" => "porcentagem", // Name do atributo HTML
		"nomeidioma" => "form_porcentagem", // Referencia a variavel de idioma
		"tipo" => "input", // Tipo do input
		"evento" => "maxlength='5'",
		"decimal" => true,
		"valor" => "porcentagem", // Nome da coluna da tabela do banco de dados que retorna o valor.
		"class" => "span1", //Class do atributo HTML
		"classe_label" => "control-label",
		"banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
		"banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
		"ajudaidioma" => "form_porcentagem_ajuda",
		),	  	  	  
	  array(
		"id" => "form_dia_semanal",
		"nome" => "dia_semanal",
		"nomeidioma" => "form_dia_semanal",
		"tipo" => "select",
		"array" => "dia_semana", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "dia_semanal",
		//"validacao" => array("required" => "dia_semanal_vazio"),
		//"ajudaidioma" => "form_dia_semanal_ajuda",
		"banco" => true,
		"banco_string" => true,
		"ajudaidioma" => "form_dia_semanal_ajuda",
	  ),
	  array(
		"id" => "form_dia_mensal",
		"nome" => "dia_mensal",
		"nomeidioma" => "form_dia_mensal",
		"tipo" => "select",
		"array" => "dia_mes", // Array que alimenta o select
		"class" => "span2", 
		"valor" => "dia_mensal",
		//"validacao" => array("required" => "dia_mensal_vazio"),
		//"ajudaidioma" => "form_dia_mensal_ajuda",
		"banco" => true,
		"banco_string" => true,
		"ajudaidioma" => "form_dia_mensal_ajuda",
	  ),
	  array(
		"id" => "form_texto",
		"nome" => "texto",
		"nomeidioma" => "form_texto",
		"tipo" => "text", 
		"editor" => true,
		"valor" => "texto",
		"class" => "xxlarge",
		"banco" => true,
		"banco_string" => true
	  ),
	  array(
		"id" => "form_botao_variaveis_aluno", // Id do atributo HTML
		"nome" => "botao_variaveis_aluno", // Name do atributo HTML
		"nomeidioma" => "form_botao_variaveis_aluno", // Referencia a variavel de idioma
		"tipo" => "php", // Tipo do input
		"colunas" => 2,
		"botao_hide" => true,
		"valor" => array(
		  array(
			"variavel_titulo_aluno" => "titulo",
			"variavel_aluno_nome" => "[[ALUNO][NOME]]",
			"variavel_aluno_estadocivil" => "[[ALUNO][ESTADO_CIVIL]]",
			"variavel_aluno_nascimento" => "[[ALUNO][DATA_NASC]]",
			"variavel_aluno_nacionalidade" => "[[ALUNO][NACIONALIDADE]]",
			"variavel_aluno_naturalidade" => "[[ALUNO][NATURALIDADE]]",
			"variavel_aluno_documento" => "[[ALUNO][DOCUMENTO]]",
			"variavel_aluno_rg" => "[[ALUNO][RG]]",
			"variavel_aluno_orgao_expeditor" => "[[ALUNO][RG_ORGAO_EMISSOR]]",
			"variavel_aluno_emissao" => "[[ALUNO][RG_DATA_EMISSAO]]",
			"variavel_aluno_mae" => "[[ALUNO][FILIACAO_MAE]]",
			"variavel_aluno_pai" => "[[ALUNO][FILIACAO_PAI]]",
			"variavel_aluno_cep" => "[[ALUNO][CEP]]",
			"variavel_aluno_logradouro" => "[[ALUNO][LOGRADOURO]]",
			"variavel_aluno_endereco" => "[[ALUNO][ENDERECO]]",
			"variavel_aluno_bairro" => "[[ALUNO][BAIRRO]]",
			"variavel_aluno_numero" => "[[ALUNO][NUMERO]]",
			"variavel_aluno_complemento" => "[[ALUNO][COMPLEMENTO]]",
			"variavel_aluno_estado" => "[[ALUNO][ESTADO]]",
			"variavel_aluno_cidade" => "[[ALUNO][CIDADE]]",
			"variavel_aluno_telefone" => "[[ALUNO][TELEFONE]]",
			"variavel_aluno_celular" => "[[ALUNO][CELULAR]]",
			"variavel_aluno_email" => "[[ALUNO][EMAIL]]",
			"variavel_aluno_banco" => "[[ALUNO][BANCO_NOME]]",
			"variavel_aluno_agencia" => "[[ALUNO][BANCO_AGENCIA]]",
			"variavel_aluno_conta" => "[[ALUNO][BANCO_CONTA]]",
			"variavel_aluno_banco_nome_titular" => "[[ALUNO][BANCO_NOME_TITULAR]]",
			"variavel_aluno_banco_cpf_titular" => "[[ALUNO][BANCO_CPF_TITULAR]]",
			"variavel_aluno_banco_observacoes" => "[[ALUNO][BANCO_OBSERVACOES]]",
			"variavel_aluno_renda" => "[[ALUNO][RENDA_FAMILIAR]]",
			"variavel_aluno_observacoes" => "[[ALUNO][OBSERVACOES]]",
			"variavel_aluno_profissao" => "[[ALUNO][PROFISSAO]]"
		  )
		),
		"class" => "span4" //Class do atributo HTML															
	  ),
	  array(
		"id" => "form_corpo_sms",
		"nome" => "corpo_sms",
		"nomeidioma" => "form_corpo_sms",
		"tipo" => "text", 
		"size"  =>"10",
		"editor" => false,
		"valor" => "corpo_sms",
		"class" => "xxlarge",
		"ajudaidioma" => "form_corpo_sms_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  
	)
  )								  
);
?>