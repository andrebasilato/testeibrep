<?php

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/pesquisas_32.png";
$config["monitoramento"]["onde"] = "128";

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