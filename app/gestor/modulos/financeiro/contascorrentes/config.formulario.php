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
                "id"           => "form_empresa",
                "nome"         => "empresa",
                "nomeidioma"   => "form_empresa",
                "tipo"         => "input",
                "valor"        => "empresa",
                "validacao"    => array("required" => "empresa_vazio"),
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),

            array(
                "id"           => "form_cnpj",
                "nome"         => "cnpj",
                "nomeidioma"   => "form_cnpj",
                "tipo"         => "input",
                "valor"        => "cnpj",
                "validacao"    => array("required" => "cnpj_vazio", "valida_cnpj" => "cnpj_invalido"),
                "mascara"      => "99.999.999/9999-99",
                "banco_php"    => 'return str_replace(array(".", "-", "/"),"","%s")',
                "evento"       => " maxlength='14'",
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true,
            ),

            array(
                "id"         => "form_banco",
                "nome"       => "idbanco",
                "nomeidioma" => "form_banco",
                "tipo"       => "select",
                "sql"        => "SELECT idbanco, nome FROM bancos where ativo = 'S' AND ativo_painel = 'S'", // SQL que alimenta o select
                "sql_valor"  => "idbanco", // Coluna da tabela que será usado como o valor do options
                "sql_label"  => "nome", // Coluna da tabela que será usado como o label do options
                "valor"      => "idbanco",
                "class"      => "span4",
                "validacao"  => array("required" => "banco_vazio"),
                "banco"      => true
            ),


            array(
                "id"           => "form_agencia",
                "nome"         => "agencia",
                "nomeidioma"   => "form_agencia",
                "tipo"         => "input",
                "valor"        => "agencia",
                "validacao"    => array("required" => "agencia_vazio"),
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true,
            ),

            array(
                "id"           => "form_agencia_dig",
                "nome"         => "agencia_dig",
                "nomeidioma"   => "form_agencia_dig",
                "tipo"         => "input",
                "valor"        => "agencia_dig",
                "validacao"    => array("required" => "agencia_dig_vazio"),
                "class"        => "span1",
                "banco"        => true,
                "banco_string" => true,
            ),

            array(
                "id"           => "form_conta",
                "nome"         => "conta",
                "nomeidioma"   => "form_conta",
                "tipo"         => "input",
                "valor"        => "conta",
                "validacao"    => array("required" => "conta_vazio"),
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true,
            ),

            array(
                "id"           => "form_conta_dig",
                "nome"         => "conta_dig",
                "nomeidioma"   => "form_conta_dig",
                "tipo"         => "input",
                "valor"        => "conta_dig",
                "validacao"    => array("required" => "conta_dig_vazio"),
                "class"        => "span1",
                "banco"        => true,
                "banco_string" => true,
            ),

            array(
                "id"           => "form_carteira",
                "nome"         => "carteira",
                "nomeidioma"   => "form_carteira",
                "tipo"         => "input",
                "valor"        => "carteira",
                "validacao"    => array("required" => "carteira_vazio"),
                "class"        => "span2",
                "banco"        => true,
                "banco_string" => true,
            ),

            array(
                "id"           => "form_observacoes",
                "nome"         => "observacoes",
                "nomeidioma"   => "form_observacoes",
                "tipo"         => "text",
                "valor"        => "observacoes",
                "class"        => "span6",
                "evento"       => "style='height:100px;'",
                "banco"        => true,
                "banco_string" => true
            ),

            array(
                "id"           => "form_boleto",
                "nome"         => "boleto",
                "nomeidioma"   => "form_boleto",
                "tipo"         => "select",
                "array"        => "ativo", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "boleto",
                "validacao"    => array("required" => "boleto_vazio"),
                "ajudaidioma"  => "form_boleto_ajuda",
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