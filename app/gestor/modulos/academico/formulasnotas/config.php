<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "validar";
$config["acoes"][5] = "visualizar_sindicatos";
$config["acoes"][6] = "associar_sindicatos";
$config["acoes"][7] = "remover_sindicatos";

$config["monitoramento"]["onde"] = "126";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "formulas_notas",
  "primaria" => "idformula",
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
  /*  "campos_sql_fixo" => array("permissoes" => 'return serialize($_POST["permissoes"]);') */												 
);
?>