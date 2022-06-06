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
                "id"           => "form_slug",
                "nome"         => "slug",
                "nomeidioma"   => "form_slug",
                "tipo"         => "input",
                "valor"        => "slug",
                "validacao"    => array("required" => "slug_vazio"),
                "evento"       => "maxlength='20'",
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true,
            ),

            array(
                "id"           => "form_homologado",
                "nome"         => "homologado",
                "nomeidioma"   => "form_homologado",
                "tipo"         => "select",
                "array"        => "sim_nao", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "homologado",
                "validacao"    => array("required" => "homologado_vazio"),
                "ajudaidioma"  => "form_homologado_ajuda",
                "banco"        => true,
                "banco_string" => true
            ),

            array(
                "id"           => "form_numero_estabelecimento",
                "nome"         => "numero_estabelecimento",
                "nomeidioma"   => "form_numero_estabelecimento",
                "tipo"         => "input",
                "valor"        => "numero_estabelecimento",
                "validacao"    => array("required" => "numero_estabelecimento_vazio"),
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true,
                "input_hidden" => true,
            ),

            array(
                "id"           => "form_token",
                "nome"         => "token",
                "nomeidioma"   => "form_token",
                "tipo"         => "input",
                "valor"        => "token",
                "validacao"    => array("required" => "token_vazio"),
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
                "input_hidden" => true
            ),

            array(
                "id"                => "form_bandeiras",
                "nome"              => "bandeiras",
                "nomeidioma"        => "form_bandeiras",
                "tipo"              => "checkbox",
                "array"             => "bandeiras_cartoes",
                "validacao"         => array("required" => "bandeiras_vazio"),
                "ajudaidioma"       => "bandeiras_ajuda",
                "class"             => "span2",
                "array_serializado" => true
            ),

            array(
                "id"                => "form_formas_pagamento",
                "nome"              => "formas_pagamento",
                "nomeidioma"        => "form_formas_pagamento",
                "tipo"              => "checkbox",
                "array"             => "formas_pagamento_cartoes",
                "validacao"         => array("required" => "formas_pagamento_vazio"),
                "ajudaidioma"       => "formas_pagamento_ajuda",
                "class"             => "span2",
                "array_serializado" => true
            ),

            array(
                "id"           => "form_qtd_parcela",
                "nome"         => "qtd_parcela",
                "nomeidioma"   => "form_qtd_parcela",
                "tipo"         => "input",
                "valor"        => "qtd_parcela",
                "evento"       => "maxlength='2'",
                "validacao"    => array("required" => "qtd_parcela_vazio"),
                "class"        => "span1",
                "banco"        => true,
                "banco_string" => true,
            ),

            array(
                "id"           => "form_parcelamento",
                "nome"         => "parcelamento",
                "nomeidioma"   => "form_parcelamento",
                "tipo"         => "select",
                "array"        => "tipo_parcelamento_cartoes", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "parcelamento",
                "validacao"    => array("required" => "parcelamento_vazio"),
                "ajudaidioma"  => "parcelamento_ajuda",
                "banco"        => true,
                "banco_string" => true
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
?>