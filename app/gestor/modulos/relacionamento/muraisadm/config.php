<?php

$config["link_manual_funcionalidade"] = "/gestor/categoria/52/mural-administrativo.html";

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/muraladministrador_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "cadastrar_imagem";
$config["acoes"][5] = "remover_imagem";
$config["acoes"][6] = "download_imagem";
$config["acoes"][7] = "preview_imagem";
$config["acoes"][8] = "fila";
$config["acoes"][9] = "cadastrar_arquivo";
$config["acoes"][10] = "remover_arquivo";
$config["acoes"][11] = "download_arquivo";

$config["monitoramento"]["onde"] = "82";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array("tabela" => "murais",
						 "primaria" => "idmural",
						 "campos_insert_fixo" => array("data_cad" => "now()", 
						 							   "ativo" => "'S'"
													  ),
						 /*
						 "campos_unicos" => array(array("campo_banco" => "nome", 
												  		"campo_form" => "nome", 
														"erro_idioma" => "nome_utilizado"
													   )
						
												 ),
						  */
						);

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco_fila"] = array(
	"tabela" => "murais_filas",
	"primaria" => "idfila",
	"campos_insert_fixo" => array(
		"data_cad" => "now()", 
		"ativo" => "'S'"
	)
);						

?>