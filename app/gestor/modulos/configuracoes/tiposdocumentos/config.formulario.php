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
                "id" => "form_documento_foto",
                "nome" => "documento_foto_oficial",
                "nomeidioma" => "form_documento_foto",
                "tipo" => "select",
                "array" => "sim_nao", // Array que alimenta o select
                "class" => "span2",
                "valor" => "documento_foto_oficial",
                "sem_primeira_linha" => true,
                "validacao" => array("required" => "documento_vazio"),
                "banco" => true,
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
            array(
                "id" => "form_exibir_ava",
                "nome" => "exibir_ava",
                "nomeidioma" => "form_exibir_ava",
                "tipo" => "select",
                "array" => "sim_nao", // Array que alimenta o select
                "class" => "span2",
                "valor" => "exibir_ava",
                "validacao" => array("required" => "exibir_ava_vazio"),
                "ajudaidioma" => "form_exibir_ava_ajuda",
                "banco" => true,
                "banco_string" => true
            ),
            /*array(
             "id" => "form_obrigatorio_workflow",
             "nome" => "obrigatorio_workflow",
             "nomeidioma" => "form_obrigatorio_workflow",
             "tipo" => "select",
             "array" => "sim_nao", // Array que alimenta o select
             "class" => "span2",
             "valor" => "obrigatorio_workflow",
             "validacao" => array("required" => "obrigatorio_workflow_vazio"),
             //"ajudaidioma" => "form_obrigatorio_workflow_ajuda",
             "banco" => true,
             "banco_string" => true
             ),*/
        )
    )
);
?>
