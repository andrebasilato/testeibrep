<?php

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "deferir_declaracao";
$config["acoes"][3] = "indeferir_declaracao";

$config["monitoramento"]["onde"] = "166";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array("tabela" => "matriculas_solicitacoes_declaracoes",
						 "primaria" => "idsolicitacao_declaracao",
						 "campos_insert_fixo" => array("data_cad" => "now()", 
						 							   "ativo" => "'S'"
													  ),
						);
?>