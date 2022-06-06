<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";

$config["monitoramento"]["onde_foruns_topicos"] = "153";

$config["banco_foruns_topicos"] = array(
  "tabela" => "avas_foruns_topicos",
  "primaria" => "idtopico",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idforum",
	  "erro_idioma" => "nome_utilizado"
	)
  )
);
?>