<?php

$config["link_manual_funcionalidade"] = "/gestor/categoria/60/checklist.html";

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/checklist_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "checklistopcoes";

$config["monitoramento"]["onde"] = "86";
$config["monitoramento"]["onde_opcoes"] = "87";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array("tabela" => "checklists",
						 "primaria" => "idchecklist",
						 "campos_insert_fixo" => array("data_cad" => "now()", 
						 							   "ativo" => "'S'"
													  ),
						 "campos_unicos" => array(array("campo_banco" => "nome", 
												  		"campo_form" => "nome", 
														"erro_idioma" => "nome_utilizado"
													   )
												 )											 
						);

$config["banco_checklistopcoes"] = array("tabela" => "checklists_opcoes",
						 "primaria" => "idopcao",

						 "campos_insert_fixo" => array("data_cad" => "now()", 
						 							   "ativo" => "'S'"
													  ),
													  
						 "campos_unicos" => array(array("campo_banco" => "nome", 
												  		"campo_form" => "nome||idchecklist", 
														"erro_idioma" => "nome_utilizado"
													   )
												 )
						);
?>