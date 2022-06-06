<?php

$config["link_manual_funcionalidade"] = "/gestor/categoria/73/grupos-de-usuarios-administrativos.html";

$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/grupos_administrativos_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";

$config["acoes"][4] = "visualizar_usuarios";
$config["acoes"][5] = "associar_usuarios";
$config["acoes"][6] = "remover_usuarios";
$config["acoes"][7] = "visualizar_assuntos";
$config["acoes"][8] = "associar_assuntos";
$config["acoes"][9] = "remover_assuntos";
$config["acoes"][10] = "visualizar_subassuntos";
$config["acoes"][11] = "associar_subassuntos";
$config["acoes"][12] = "remover_subassuntos";

$config["monitoramento"]["onde"] = "91";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array("tabela" => "grupos_usuarios_adm",
    "primaria" => "idgrupo",
    "campos_insert_fixo" => array("data_cad" => "now()",
        "ativo" => "'S'"
    ),
    "campos_unicos" => array(array("campo_banco" => "nome",
        "campo_form" => "nome",
        "erro_idioma" => "nome_utilizado"
    )
    ),
    /*  "campos_sql_fixo" => array("permissoes" => 'return serialize($_POST["permissoes"]);') */
);

?>