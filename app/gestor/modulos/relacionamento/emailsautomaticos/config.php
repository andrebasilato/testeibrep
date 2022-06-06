<?php

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/assuntos_atendimento_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "email_cursos";
$config["acoes"][5] = "email_cursos_modificar";
$config["acoes"][6] = "email_cursos_remover";
$config["acoes"][7] = "email_ofertas";
$config["acoes"][8] = "email_ofertas_modificar";
$config["acoes"][9] = "email_ofertas_remover";
$config["acoes"][10] = "email_sindicatos";
$config["acoes"][11] = "email_sindicatos_modificar";
$config["acoes"][12] = "email_sindicatos_remover";

$config["monitoramento"]["onde"] = "163";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array("tabela" => "emails_automaticos",
						 "primaria" => "idemail",
						 "campos_insert_fixo" => array("data_cad" => "now()", 
						 							   "ativo" => "'S'"
													  ),
						 "campos_unicos" => array(array("campo_banco" => "nome", 
												  		"campo_form" => "nome", 
														"erro_idioma" => "nome_utilizado"
													   )
												 )
						);
?>