<?php

//$config["link_manual_funcionalidade"] = "/gestor/categoria/66/etiquetas.html";

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/contratos_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";
$config["acoes"][4] = "gerar";
$config["acoes"][5] = "preview";

$config["monitoramento"]["onde"] = "116";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
    "tabela" => "etiquetas",
    "primaria" => "idetiqueta",
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
    )
);