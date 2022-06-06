<?php

$config["link_manual_funcionalidade"] = "/gestor/categoria/92/atendimentos.html";

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/atendimentos_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";

$config["monitoramento"]["onde"] = "90";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
	"tabela" => "atendimentos",
	"primaria" => "ate.idatendimento",
	"campos_insert_fixo" => array(
		"data_cad" => "now()", 
		"ativo" => "'S'"
	)
);

$config["banco_pessoas"] = array(
	"tabela" => "pessoas",
	"primaria" => "idpessoa",
	"campos_insert_fixo" => array(
		"data_cad" => "now()", 
		"ativo" => "'S'"
	)
);

?>