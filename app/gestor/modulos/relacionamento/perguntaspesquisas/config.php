<?php

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/perguntas_pesquisa_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";

$config["monitoramento"]["onde"] = "127";

//TIPO
$tipo["pt_br"] = array("O" => "Objetiva", "S" => "Subjetiva");
$tipo["en"] 	= array("O" => "O",  "S" => "S");

//Sentido da pergunta
$sentido["pt_br"] = array("H" => "Horizontal", "V" => "Vertical");
$sentido["en"] 	= array("O" => "O",  "S" => "S");

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array("tabela" => "perguntas_pesquisas",
						 "primaria" => "idpergunta",
						 "campos_insert_fixo" => array("data_cad" => "now()", 
						 							   "ativo" => "'S'"
													  ),
						 "campos_unicos" => array(array("campo_banco" => "nome", 
												  		"campo_form" => "nome", 
														"erro_idioma" => "nome_utilizado"
													   )
												 ),
						);
?>