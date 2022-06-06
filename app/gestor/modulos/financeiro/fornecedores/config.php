<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "produtos";
$config["acoes"][5] = "produtos_associar";
$config["acoes"][6] = "produtos_remover";

$config["monitoramento"]["onde"] = "46";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
    "tabela"             => "fornecedores",
    "primaria"           => "idfornecedor",
    "campos_insert_fixo" => array(
        "data_cad" => "now()",
        "ativo"    => "'S'"
    ),
    "campos_unicos"      => array(
        array(
            "campo_banco" => "nome",
            "campo_form"  => "nome||documento",
            "erro_idioma" => "nome_utilizado"
        ),
        array(
            "campo_banco" => "documento",
            "campo_form" => "documento",
            "erro_idioma" => "cpf_cnpj_utilizado",
            "campo_php" => 'return str_replace(array(".", "-", "/"),"","%s")'
        ),
    ),
);
?>
