<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "cursos";
$config["acoes"][5] = "cursos_alterar";
$config["acoes"][6] = "cursos_remover";
$config["acoes"][7] = "sindicatos";
$config["acoes"][8] = "sindicatos_alterar";
$config["acoes"][9] = "sindicatos_remover";
$config["acoes"][10] = "sindicatos_agendamento";
$config["acoes"][11] = "sindicatos_agendamento_alterar";
$config["acoes"][12] = "sindicatos_agendamento_remover";

$config["monitoramento"]["onde"] = "100";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
    "tabela" => "tipos_documentos",
    "primaria" => "idtipo",
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
