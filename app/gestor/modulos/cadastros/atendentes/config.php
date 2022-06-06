<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "desativar_login";
$config["acoes"][5] = "resetar_senha";
$config["acoes"][6] = "contatos";
$config["acoes"][7] = "bloquear_vendas";
$config["acoes"][8] = "visualizar_sindicatos";
$config["acoes"][9] = "associar_sindicatos";
$config["acoes"][10] = "remover_sindicatos";
$config["acoes"][11] = "acessarcomo";
$config["acoes"][12] = "visualizar_cfc";
$config["acoes"][13] = "associar_cfc";
$config["acoes"][14] = "remover_cfc";

$config["monitoramento"]["onde"] = "20";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
    "tabela" => "vendedores",
    "primaria" => "idvendedor",
    "campos_insert_fixo" => array(
        "data_cad" => "now()",
        "ativo" => "'S'"
    ),
    "campos_unicos" => array(
        array(
            "campo_banco" => "email",
            "campo_form" => "email",
            "erro_idioma" => "email_utilizado"
        ),
        array(
            "campo_banco" => "documento",
            "campo_form" => "documento",
            "erro_idioma" => "cpf_cnpj_utilizado",
            "campo_php" => 'return str_replace(array(".", "-", "/"),"","%s")'
        )
    ),
    /*  "campos_sql_fixo" => array("permissoes" => 'return serialize($_POST["permissoes"]);') */
);
?>