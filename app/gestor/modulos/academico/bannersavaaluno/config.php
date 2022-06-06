<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "listar_sindicato";
$config["acoes"][5] = "associar_sindicato";
$config["acoes"][6] = "remover_sindicato";
$config["acoes"][7] = "listar_escola";
$config["acoes"][8] = "associar_escola";
$config["acoes"][9] = "desassociar_escola";
$config["acoes"][10] = "listar_curso";
$config["acoes"][11] = "associar_curso";
$config["acoes"][12] = "remover_curso";

$config["monitoramento"]["onde"] = "165";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "banners_ava_aluno",
  "primaria" => "idbanner",
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
?>
