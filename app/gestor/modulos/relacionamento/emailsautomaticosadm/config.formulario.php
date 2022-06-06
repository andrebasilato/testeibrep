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
		"array" => "tipos_emails_automaticos_adm", // Array que alimenta o select
		"class" => "span4", 
		"valor" => "tipo",
		"validacao" => array("required" => "tipo_vazio"),
		"ajudaidioma" => "form_tipo_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),	  
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
	  /*array(
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
		"banco_string" => true
	  ),*/
	  array(
			'id' => 'idsituacao_matricula',
			"sql" => "select idsituacao as idsituacao_matricula, nome FROM matriculas_workflow WHERE ativo='S' ORDER BY nome",
			"nome" => "idsituacao_matricula",
			"tipo" => "select",
			"valor" => "idsituacao_matricula",
			"class" => "span5",
			"sql_valor" => "idsituacao_matricula",
			"sql_label" => "nome",
			"nomeidioma" => "form_idsituacao_matricula",
			"sql_filtro" => "select * from matriculas_workflow where ativo='S' and idsituacao=%",
			"sql_filtro_label" => "nome",
			"banco" => true,
			"banco_string" => true,
			"ajudaidioma" => "form_idsituacao_matricula_ajuda",
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
		"id" => "form_corpo_sms",
		"nome" => "corpo_sms",
		"nomeidioma" => "form_corpo_sms",
		"tipo" => "text", 
		"editor" => false,
		"valor" => "corpo_sms",
		"ajudaidioma" => "form_corpo_sms_ajuda",
		"banco" => true,
		"banco_string" => true
	  ),
	  /*array(
		"id" => "form_botao_variaveis_cliente", // Id do atributo HTML
		"nome" => "botao_variaveis_cliente", // Name do atributo HTML
		"nomeidioma" => "form_botao_variaveis_cliente", // Referencia a variavel de idioma
		"tipo" => "php", // Tipo do input
		"colunas" => 2,
		"botao_hide" => true,
		"valor" => array(
		  array(
			"variavel_titulo_cliente" => "titulo",
			"variavel_cliente_nome" => "[[CLIENTE][NOME]]",
			"variavel_cliente_estadocivil" => "[[CLIENTE][ESTADO_CIVIL]]",
			"variavel_cliente_nascimento" => "[[CLIENTE][DATA_NASC]]",
			"variavel_cliente_nacionalidade" => "[[CLIENTE][NACIONALIDADE]]",
			"variavel_cliente_naturalidade" => "[[CLIENTE][NATURALIDADE]]",
			"variavel_cliente_documento" => "[[CLIENTE][DOCUMENTO]]",
			"variavel_cliente_rg" => "[[CLIENTE][RG]]",
			"variavel_cliente_orgao_expeditor" => "[[CLIENTE][RG_ORGAO_EMISSOR]]",
			"variavel_cliente_emissao" => "[[CLIENTE][RG_DATA_EMISSAO]]",
			"variavel_cliente_mae" => "[[CLIENTE][FILIACAO_MAE]]",
			"variavel_cliente_pai" => "[[CLIENTE][FILIACAO_PAI]]",
			"variavel_cliente_cep" => "[[CLIENTE][CEP]]",
			"variavel_cliente_logradouro" => "[[CLIENTE][LOGRADOURO]]",
			"variavel_cliente_endereco" => "[[CLIENTE][ENDERECO]]",
			"variavel_cliente_bairro" => "[[CLIENTE][BAIRRO]]",
			"variavel_cliente_numero" => "[[CLIENTE][NUMERO]]",
			"variavel_cliente_complemento" => "[[CLIENTE][COMPLEMENTO]]",
			"variavel_cliente_estado" => "[[CLIENTE][ESTADO]]",
			"variavel_cliente_cidade" => "[[CLIENTE][CIDADE]]",
			"variavel_cliente_telefone" => "[[CLIENTE][TELEFONE]]",
			"variavel_cliente_celular" => "[[CLIENTE][CELULAR]]",
			"variavel_cliente_email" => "[[CLIENTE][EMAIL]]",
			"variavel_cliente_banco" => "[[CLIENTE][BANCO_NOME]]",
			"variavel_cliente_agencia" => "[[CLIENTE][BANCO_AGENCIA]]",
			"variavel_cliente_conta" => "[[CLIENTE][BANCO_CONTA]]",
			"variavel_cliente_banco_nome_titular" => "[[CLIENTE][BANCO_NOME_TITULAR]]",
			"variavel_cliente_banco_cpf_titular" => "[[CLIENTE][BANCO_CPF_TITULAR]]",
			"variavel_cliente_banco_observacoes" => "[[CLIENTE][BANCO_OBSERVACOES]]",
			"variavel_cliente_renda" => "[[CLIENTE][RENDA_FAMILIAR]]",
			"variavel_cliente_observacoes" => "[[CLIENTE][OBSERVACOES]]",
			"variavel_cliente_profissao" => "[[CLIENTE][PROFISSAO]]"
		  )
		),
		"class" => "span4" //Class do atributo HTML															
	  ),*/
	)
  )								  
);
?>