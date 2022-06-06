<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "visualizar_regras";
$config["acoes"][5] = "cadastrar_regras";
$config["acoes"][6] = "remover_regras";
$config["acoes"][7] = "visualizar_sindicatos";
$config["acoes"][8] = "cadastrar_sindicatos";
$config["acoes"][9] = "remover_sindicatos";
$config["acoes"][10] = "visualizar_cursos";
$config["acoes"][11] = "cadastrar_cursos";
$config["acoes"][12] = "remover_cursos";

$config["monitoramento"]["onde"] = "135";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
    "tabela"             => "comissoes_regras",
    "primaria"           => "idregra",
    "campos_insert_fixo" => array(
        "data_cad" => "now()",
        "ativo"    => "'S'"
    ),
    "campos_unicos"      => array(
        array(
            "campo_banco" => "nome",
            "campo_form"  => "nome",
            "erro_idioma" => "nome_utilizado"
        )
    ),
);