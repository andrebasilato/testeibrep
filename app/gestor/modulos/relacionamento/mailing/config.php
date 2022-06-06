<?php

$config["link_manual_funcionalidade"] = "/gestor/categoria/59/pesquisas.html";

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/atendimentos_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "visualizar_pessoas_associadas";
$config["acoes"][5] = "associar_pessoa";
$config["acoes"][6] = "remover_pessoa";
$config["acoes"][7] = "visualizar_layout";
$config["acoes"][8] = "cadastrar_layout";
$config["acoes"][9] = "visualizar_imagens";
$config["acoes"][10] = "cadastrar_imagem";
$config["acoes"][11] = "remover_imagem";
$config["acoes"][12] = "visualizar_preview";
$config["acoes"][13] = "clonar_mailing";
$config["acoes"][14] = "fila";
$config["acoes"][15] = "resultado";
$config["acoes"][16] = "reenviar";
$config["acoes"][17] = "corpo_email";


$config["monitoramento"]["onde"] = "146";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array("tabela" => "mailings",
						 "primaria" => "idemail",
						 "campos_insert_fixo" => array("data_cad" => "now()", 
						 							   "ativo" => "'S'"
													  ),
						 "campos_unicos" => array(array("campo_banco" => "nome", 
												  		"campo_form" => "nome", 
														"erro_idioma" => "nome_utilizado"
													   )
												 ),
						);

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco_fila"] = array(
	"tabela" => "mailings_fila",
	"primaria" => "idmailing_pessoa",
	"campos_insert_fixo" => array(
		"data_cad" => "now()", 
		"ativo" => "'S'"
	)
);
?>