<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";

$config["monitoramento"]["onde"] = "12";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "avas",
  "primaria" => "idava",
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
  'campos_sql_fixo' => array(
	'modulos' => 'return serialize($_POST["modulos"]);'
  )	  
);