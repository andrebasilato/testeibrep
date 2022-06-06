<?php

$config["link_manual_funcionalidade"] = "/gestor/categoria/59/pesquisas.html";

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/pesquisas_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "visualizar_perguntas_associadas";
$config["acoes"][5] = "associar_pergunta";
$config["acoes"][6] = "remover_pergunta";
$config["acoes"][7] = "visualizar_pessoas_associadas";
$config["acoes"][8] = "associar_pessoa";
$config["acoes"][9] = "remover_pessoa";
$config["acoes"][10] = "visualizar_layout";
$config["acoes"][11] = "cadastrar_layout";
$config["acoes"][12] = "visualizar_imagens";
$config["acoes"][13] = "cadastrar_imagem";
$config["acoes"][14] = "remover_imagem";
$config["acoes"][15] = "visualizar_preview";
$config["acoes"][16] = "clonar_pesquisa";
$config["acoes"][17] = "fila";
$config["acoes"][18] = "resultado";
$config["acoes"][19] = "reenviar";
$config["acoes"][20] = "corpo_email";


$config["monitoramento"]["onde"] = "128";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array("tabela" => "pesquisas",
						 "primaria" => "idpesquisa",
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
	"tabela" => "pesquisas_fila",
	"primaria" => "idpesquisa_pessoa",
	"campos_insert_fixo" => array(
		"data_cad" => "now()", 
		"ativo" => "'S'"
	)
);
?>