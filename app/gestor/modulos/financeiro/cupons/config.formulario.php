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
                "id"           => "form_codigo",
                "nome"         => "codigo",
                "nomeidioma"   => "form_codigo",
                "evento"       => "maxlength='7'",
                "tipo"         => "input",
                "valor"        => "codigo",
                "validacao"    => array("required" => "codigo_vazio"),
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true,
            ),

            /*array(
                "id"           => "form_tipo",
                "nome"         => "tipo",
                "nomeidioma"   => "form_tipo",
                "tipo"         => "select",
                "array"        => "tipo_cupons", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "tipo",
                "validacao"    => array("required" => "tipo_vazio"),
                "ajudaidioma"  => "tipo_ajuda",
                "banco"        => true,
                "banco_string" => true
            ),*/

            array(
                "id"           => "form_tipo_desconto",
                "nome"         => "tipo_desconto",
                "nomeidioma"   => "form_tipo_desconto",
                "tipo"         => "select",
                "array"        => "tipo_desconto_cupons", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "tipo_desconto",
                "validacao"    => array("required" => "tipo_desconto_vazio"),
                "ajudaidioma"  => "tipo_desconto_ajuda",
                "banco"        => true,
                "banco_string" => true,
                "botao_hide"   => true,
                "iddivs"       => array("porcentagem", "valor"),
                "iddiv"        => "porcentagem",
                "iddiv2"       => "valor",
            ),

            array(
                "id"           => "form_porcentagem",
                "nome"         => "porcentagem",
                "nomeidioma"   => "form_porcentagem",
                "tipo"         => "input",
                "valor"        => "porcentagem",
                "evento"       => "maxlength='6'",
                "validacao"    => array("required" => "porcentagem_vazio"),
                "class"        => "span1",
                "banco"        => true,
                "banco_string" => true,
                "decimal"      => true,
                "input_hidden" => true,
            ),

            array(
                "id"           => "form_valor",
                "nome"         => "valor",
                "nomeidioma"   => "form_valor",
                "tipo"         => "input",
                "valor"        => "valor",
                "evento"       => "maxlength='8'",
                "validacao"    => array("required" => "valor_vazio"),
                "class"        => "span1",
                "banco"        => true,
                "banco_string" => true,
                "decimal"      => true,
                "input_hidden" => true,
            ),

            array(
                "id"           => "form_quantidade",
                "nome"         => "quantidade",
                "nomeidioma"   => "form_quantidade",
                "tipo"         => "input",
                "valor"        => "quantidade",
                "evento"       => "maxlength='2'",
                "validacao"    => array("required" => "quantidade_vazio"),
                "class"        => "span1",
                "banco"        => true,
                "banco_string" => true,
                "numerico"     => true,
            ),

            array(
                "id"           => "form_validade",
                "nome"         => "validade",
                "nomeidioma"   => "form_validade",
                "tipo"         => "input",
                "valor"        => "validade",
                "valor_php"    => 'if($dados["validade"]) return formataData("%s", "br", 0)',
                "class"        => "span2",
                "mascara"      => "99/99/9999",
                "datepicker"   => true,
                "banco"        => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),

            array(
                "id"           => "form_descricao",
                "nome"         => "descricao",
                "nomeidioma"   => "form_descricao",
                "tipo"         => "text",
                "valor"        => "descricao",
                "class"        => "span6",
                "evento"       => "style='height:100px;'",
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