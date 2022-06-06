<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";

$config["monitoramento"]["onde"] = "177";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "relacionamentos_comercial",
  "primaria" => "idmensagem",
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
  ),
											 
);
?>