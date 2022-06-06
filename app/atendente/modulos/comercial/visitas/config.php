<?php
//$config["link_manual_funcionalidade"] = "/gestor/categoria/98/interessado.html";

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/interesses_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";

$config["monitoramento"]["onde"] = "81";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
	"tabela" => "visitas_vendedores",
	"primaria" => "idvisita",
	"campos_insert_fixo" => array(
		"data_cad" => "now()", 
		"ativo" => "'S'"
	),
	/*"campos_unicos" => array(
		array(
			"campo_banco" => "nome", 
			"campo_form" => "nome", 
			"erro_idioma" => "nome_utilizado"
		)
	),*/	
);
?>