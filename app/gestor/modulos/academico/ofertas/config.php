<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";

$config["acoes"][4] = "visualizar_cursos";
$config["acoes"][5] = "associar_cursos";
$config["acoes"][6] = "remover_cursos";

$config["acoes"][7] = "visualizar_escolas";
$config["acoes"][8] = "associar_escolas";
$config["acoes"][9] = "remover_escolas";

$config["acoes"][10] = "visualizar_turmas";
$config["acoes"][11] = "associar_turmas";
$config["acoes"][12] = "remover_turmas";

$config["acoes"][13] = "cursos_escolas";
$config["acoes"][14] = "campos_prova_presencial";
$config["acoes"][15] = "curriculos_avas";

$config["acoes"][16] = "cursos_sindicatos";
$config["acoes"][17] = "clonar";

$config["monitoramento"]["onde"] = "10";
$config["monitoramento"]["onde_cursos"] = "11";
$config["monitoramento"]["onde_escolas"] = "161";
$config["monitoramento"]["onde_turmas"] = "162";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "ofertas",
  "primaria" => "idoferta",
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

$config["banco_cursos"] = array(
  "tabela" => "ofertas_cursos",
  "primaria" => "idoferta_curso",
  "campos_insert_fixo" => array(
	"data_cad" => "now()", 
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome", 
	  "campo_form" => "nome||idoferta", 
	  "erro_idioma" => "nome_utilizado"
	)
  ),
);

$config["banco_escolas"] = array(
  "tabela" => "ofertas_escolas",
  "primaria" => "idoferta_escola",
  "campos_insert_fixo" => array(
	"data_cad" => "now()", 
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome_fantasia", 
	  "campo_form" => "nome_fantasia||idoferta", 
	  "erro_idioma" => "nome_utilizado"
	)
  ),
);

$config["banco_turmas"] = array(
  "tabela" => "ofertas_turmas",
  "primaria" => "idturma",
  "campos_insert_fixo" => array(
	"data_cad" => "now()", 
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome", 
	  "campo_form" => "nome||idoferta", 
	  "erro_idioma" => "nome_utilizado"
	)
  ),
);

$config["banco_cursos_escolas"] = array(
  "tabela" => "ofertas_cursos_escolas",
  "primaria" => "idoferta_curso_escola",
  "campos_insert_fixo" => array(
	"data_cad" => "now()", 
	"ativo" => "'S'"
  ),
);

$config["banco_cursos_sindicatos"] = array(
  "tabela" => "ofertas_cursos_sindicatos",
  "primaria" => "idoferta_curso_sindicato",
  "campos_insert_fixo" => array(
	"data_cad" => "now()", 
	"ativo" => "'S'"
  ),
);
?>