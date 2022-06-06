<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";

$config["acoes"][4] = "visualizar_areas";
$config["acoes"][5] = "associar_areas";
$config["acoes"][6] = "remover_areas";

$config["acoes"][7] = "visualizar_sindicatos";
$config["acoes"][8] = "associar_sindicatos";
$config["acoes"][9] = "remover_sindicatos";

$config["acoes"][10] = "cadastrar_alterar_email_boasvindas";

$config["acoes"][11] = "cadastrar_alterar_informacoes_ava";

$config["monitoramento"]["onde"] = "8";
$config["monitoramento"]["onde_cursosindicato"] = "66";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "cursos",
  "primaria" => "idcurso",
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

$config["banco_cursosindicato"] = array("tabela" => "cursos_sindicatos",
                                        "primaria" => "idcurso_sindicato",                                                 
                                        );
?>
