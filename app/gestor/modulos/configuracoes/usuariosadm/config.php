<?php
$config["link_manual_funcionalidade"] = "";

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/usuarios_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "desativar_login";
$config["acoes"][5] = "resetar_senha";
$config["acoes"][6] = "visualizar_sindicatos";
$config["acoes"][7] = "associar_sindicatos";
$config["acoes"][8] = "remover_sindicatos";
$config["acoes"][9] = "emails";
$config["acoes"][10] = "visualizar_cfcs";
$config["acoes"][11] = "associar_cfcs";
$config["acoes"][12] = "remover_cfcs";

$config["monitoramento"]["onde"] = "1";

// Array de configuração de banco de dados
// (
//     nome da tabela,
//     chave primaria,
//     campos com valores fixos,
//     campos unicos
// )
$config["banco"] = array(
    "tabela" => "usuarios_adm",
    "primaria" => "idusuario",
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
        ),
        array(
            "campo_banco" => "email",
            "campo_form" => "email",
            "erro_idioma" => "email_utilizado"
        )
    )
);