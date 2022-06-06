<?php
// Array de configuração para a formulario			
$config["formulario"] = array(
    array(
        "fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario
            array(
                "id" => "form_nome",
                "nome" => "nome",
                "nomeidioma" => "form_nome",
                "tipo" => "input",
                "valor" => "nome",
                "validacao" => array("required" => "nome_vazio"),
                "class" => "span6",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_data",
                "nome" => "data",
                "nomeidioma" => "form_data",
                "tipo" => "input",
                "valor" => "data",
                "valor_php" => 'if($dados["data"]) return formataData("%s", "br", 0)',
                "class" => "span2",
                "validacao" => array("required" => "data_vazio"),
                "mascara" => "99/99/9999",
                "datepicker" => true,
                "banco" => true,
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            array(
                "id" => "form_ativo_painel",
                "nome" => "ativo_painel",
                "nomeidioma" => "form_ativo_painel",
                "tipo" => "select",
                "array" => "ativo", // Array que alimenta o select
                "class" => "span2",
                "valor" => "ativo_painel",
                "validacao" => array("required" => "ativo_vazio"),
                "ajudaidioma" => "form_ativo_ajuda",
                "banco" => true,
                "banco_string" => true
            ),
        )
    )
);
?>