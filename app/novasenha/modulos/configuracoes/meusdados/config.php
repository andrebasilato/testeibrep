<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/meus_dados_32.png";

$config["monitoramento"]["onde"] = "1";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "usuarios_adm",
  "primaria" => "idusuario",
  "campos_insert_fixo" => array(
	"data_cad" => "now()", 
	"ativo" => "'S'"
  )
);
					   
// Array de configuração para a formulario			
$config["formulario"] = array(
  array(
	"fieldsetid" => "dadosdousuario", // Titulo do formulario (referencia a variavel de idioma)
	"legendaidioma" => "legendadadosusuarios", // Legenda do fomrulario (referencia a variavel de idioma)
	"campos" => array( // Campos do formulario																	 																							
	  array(
		"id" => "form_senha",
		"nome" => "senha",
		"nomeidioma" => "form_senha",
		"tipo" => "input",
		"senha" => true,
		"ajudaidioma" => "form_senha_ajuda",
		"class" => "span3 verificaSenha",
		"validacao" => array("required" => "senha_vazio", "length>=8" => "minimo_senha", "length<=30" => "maximo_senha"),
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
		"class" => "span3",
		"evento" => "maxlength='30'"
	  )
	)
  )								  
);
?>