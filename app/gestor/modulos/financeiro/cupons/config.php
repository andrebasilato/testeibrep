<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "escolas";
$config["acoes"][5] = "escolas_associar";
$config["acoes"][6] = "escolas_remover";
$config["acoes"][7] = "cursos";
$config["acoes"][8] = "cursos_associar";
$config["acoes"][9] = "cursos_remover";

$config["monitoramento"]["onde"] = "221";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
    "tabela"             => "cupons",
    "primaria"           => "idcupom",
    "campos_insert_fixo" => array(
        "data_cad" => "now()",
        "ativo"    => "'S'"
    ),
    "campos_unicos"      => array(
        array(
            "campo_banco" => "codigo",
            "campo_form"  => "codigo",
            "erro_idioma" => "codigo_utilizado"
        )
    )
);
?>