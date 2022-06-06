<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "desativar_login";
$config["acoes"][5] = "resetar_senha";
$config["acoes"][6] = "associar_curso";
$config["acoes"][7] = "desassociar_curso";
$config["acoes"][8] = "associar_ava";
$config["acoes"][9] = "desassociar_ava";
$config["acoes"][10] = "associar_oferta";
$config["acoes"][11] = "desassociar_oferta";
$config["acoes"][12] = "acessarcomo";
$config["acoes"][13] = "associar_disciplina";
$config["acoes"][14] = "desassociar_disciplina";
$config["acoes"][15] = "enviar_arquivos_pasta";
$config["acoes"][16] = "alterar_arquivos_pasta";
$config["acoes"][17] = "remover_arquivos_pasta";

$config["monitoramento"]["onde"] = "17";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "professores",
  "primaria" => "idprofessor",
  "campos_insert_fixo" => array(
	"data_cad" => "now()", 
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "documento", 
	  "campo_form" => "documento", 
	  "erro_idioma" => "cpf_utilizado", 
	  "campo_php" => 'return str_replace(array(".", "-", "/"),"","%s")'
	)
  )
  /*  "campos_sql_fixo" => array("permissoes" => 'return serialize($_POST["permissoes"]);') */												 
);
?>