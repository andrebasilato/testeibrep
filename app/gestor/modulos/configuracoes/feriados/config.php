<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "cidades";
$config["acoes"][5] = "cidades_associar";
$config["acoes"][6] = "cidades_remover";
$config["acoes"][7] = "estados";
$config["acoes"][8] = "estados_associar";
$config["acoes"][9] = "estados_remover";
$config["acoes"][10] = "escolas";
$config["acoes"][11] = "escolas_associar";
$config["acoes"][12] = "escolas_remover";
$config["acoes"][13] = "sindicatos";
$config["acoes"][14] = "sindicatos_associar";
$config["acoes"][15] = "sindicatos_remover";

$config["monitoramento"]["onde"] = "5";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
    "tabela" => "feriados",
    "primaria" => "idferiado",
    "campos_insert_fixo" => array(
        "data_cad" => "now()",
        "ativo" => "'S'"
    ),
    /*"campos_unicos" => array(
        array(
            "campo_banco" => "nome",
            "campo_form" => "nome",
            "erro_idioma" => "nome_utilizado"
        )
    ),*/
    /*  "campos_sql_fixo" => array("permissoes" => 'return serialize($_POST["permissoes"]);') */
);
?>