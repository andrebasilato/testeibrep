<?php
// Array de configuração para a formulario			
$config["formulario"] = array(
    array(
        "fieldsetid"    => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"           => "form_nome",
                "nome"         => "nome",
                "nomeidioma"   => "form_nome",
                "tipo"         => "input",
                "valor"        => "nome",
                "validacao"    => array("required" => "nome_vazio"),
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "form_ativo_painel",
                "nome"         => "ativo_painel",
                "nomeidioma"   => "form_ativo_painel",
                "tipo"         => "select",
                "array"        => "ativo", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "ativo_painel",
                "validacao"    => array("required" => "ativo_vazio"),
                "ajudaidioma"  => "form_ativo_ajuda",
                "banco"        => true,
                "banco_string" => true
            ),
        )
    )
);

// Array de configuração para a formulario			
$config["formulario_todos_cursos"] = array(
    array(
        "fieldsetid"    => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"           => "todos_cursos",
                "nome"         => "todos_cursos",
                "nomeidioma"   => "todos_cursos",
                "tipo"         => "checkbox",
                "valor"        => "todos_cursos",
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
        )
    )
);

// Array de configuração para a formulario			
$config["formulario_todas_sindicatos"] = array(
    array(
        "fieldsetid"    => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"           => "todas_sindicatos",
                "nome"         => "todas_sindicatos",
                "nomeidioma"   => "todas_sindicatos",
                "tipo"         => "checkbox",
                "valor"        => "nome",
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
        )
    )
);