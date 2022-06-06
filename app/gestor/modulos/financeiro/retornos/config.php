<?php
$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "cadastrar_modificar";
$config["monitoramento"]["onde"] = "228";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array("tabela" => "retornos",
						 "primaria" => "idretorno",
						 "campos_insert_fixo" => array("datacad" => "now()", 
						 							   "idusuario" => $GLOBALS["usuario"]["idusuario"], 
						 							   "ativo" => "'S'"
													  ),
						 "campos_unicos" => array()
						);

// Array de configuração para a listagem	

						   
						   


?>