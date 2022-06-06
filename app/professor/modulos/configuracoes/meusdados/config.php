<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/img/usuarios_adm_32x32.png";

$config["monitoramento"]["onde"] = "17";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
	"tabela" => "professores",
	"primaria" => "idprofessor",						 
	"campos_insert_fixo" => array(
		"data_cad" => "now()", 
		"ativo" => "'S'"
	),
);

$config["formulario"] = array(
	array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
		  "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
		  "campos" => array( // Campos do formulario																						
							array(
								  "id" => "form_nome",
								  "nome" => "nome", 
								  "nomeidioma" => "form_nome",
								  "tipo" => "input",
								  "valor" => "nome",
								  "validacao" => array("required" => "nome_vazio"), 
								  "class" => "span4",
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
								  "validacao" => array("required" => "telefone_vazio"),
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
							array(
								  "id" => "form_email",
								  "nome" => "email",
								  "nomeidioma" => "form_email",
								  "tipo" => "input", 
								  "valor" => "email",
								  "validacao" => array("required" => "email_vazio", "valid_email" => "email_invalido"),
								  "class" => "span4",
								  "legenda" => "@",
								  "banco" => true,
								  "banco_string" => true,
								  "evento" => "maxlength='100'"
								  ),
							
							array(
								  "id" => "form_senha_antiga",
								  "nome" => "senha_antiga",
								  "nomeidioma" => "form_senha_antiga",
								  "tipo" => "input",
								  "senha" => true,
								  "ajudaidioma" => "form_senha_antiga_ajuda",
								  "class" => "span3",
								  "validacao" => array("required" => "senha_antiga_vazio"),
								  "legenda" => "#", // Adiciona uma legenda ao campo no formulario
								  "ignorarsevazio" => true, // ignora o campo se ele for uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
								  "banco" => false, 
								  "banco_string" => false ,
								  "evento" => "maxlength='30'"
								  ),								  
						   array(
								  "id" => "form_senha",
								  "nome" => "senha",
								  "nomeidioma" => "form_senha",
								  "tipo" => "input",
								  "senha" => true,
								  "ajudaidioma" => "form_senha_ajuda",
								  "class" => "span3 verificaSenha",
								  "validacao" => array(/*"required" => "senha_vazio",*/ "length>=8" => "minimo_senha", "length<=30" => "maximo_senha"),
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
		  )				  
);
				   

?>