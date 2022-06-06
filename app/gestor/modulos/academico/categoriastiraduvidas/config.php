<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "listar_professores";
$config["acoes"][5] = "associar_professores";
$config["acoes"][6] = "remover_professores";

$config["monitoramento"]["onde"] = "229";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
						"tabela" => "avas_tiraduvidas_categorias",
						"primaria" => "idcategoria",
						"campos_insert_fixo" => array(
													"data_cad" => "NOW()", 
													"ativo" => "'S'"
												),
													 
					);