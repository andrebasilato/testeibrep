<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "avaliacoes_visualizar";
$config["acoes"][5] = "avaliacoes_cadastrar_modificar";
$config["acoes"][6] = "avaliacoes_remover";
$config["acoes"][7] = "blocos_visualizar";
$config["acoes"][8] = "blocos_cadastrar_modificar";
$config["acoes"][9] = "blocos_remover";
$config["acoes"][10] = "disciplinas_visualizar";
$config["acoes"][11] = "disciplinas_cadastrar_modificar";
$config["acoes"][12] = "disciplinas_remover";
$config["acoes"][13] = "arquivos_cursos";
$config["acoes"][14] = "arquivos_cursos_enviar";
$config["acoes"][15] = "arquivos_cursos_remover";
$config["acoes"][16] = "visualizar_tipos_notas";
$config["acoes"][17] = "associar_tipos_notas";
$config["acoes"][18] = "remover_tipos_notas";

$config["monitoramento"]["onde"] = "75";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "curriculos",
  "primaria" => "idcurriculo",
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