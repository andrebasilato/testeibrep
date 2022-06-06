<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/contratos_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "sindicatos";
$config["acoes"][5] = "sindicatos_associar";
$config["acoes"][6] = "sindicatos_remover";
$config['acoes'][7] = 'cursos';

$config["monitoramento"]["onde"] = "13";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
    "tabela" => "contratos",
	"primaria" => "idcontrato",
	"campos_insert_fixo" => array(
	    "data_cad" => "now()",
        "ativo" => "'S'"
	),
	"campos_unicos" => array(
	    array(
	        "campo_banco" => "nome",
	  		"campo_form" => "nome",
			"erro_idioma" => "nome_utilizado"
	   )
    )
);