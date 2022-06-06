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
                "evento" => "maxlength='100'",
            ),
            array(
                "id"           => "vencimento",
                "nome"         => "data_vencimento",
                "nomeidioma"   => "form_vencimento",
                "tipo"         => "input",
                "valor"        => "data_vencimento",
                "validacao"    => array("required" => "vencimento_vazio"),
                "valor_php"    => 'if($dados["data_vencimento"] && $dados["data_vencimento"] != "0000-00-00") return formataData("%s", "br", 0)',
                "class"        => "span2",
                "mascara"      => "99/99/9999",
                "datepicker"   => true,
                "banco"        => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            array(
                "id"           => "form_valor", // Id do atributo HTML
                "nome"         => "valor", // Name do atributo HTML
                "nomeidioma"   => "form_valor", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='13' onblur='calcularParcelas()'",
                "decimal"      => true,
                "valor"        => "valor", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"    => array("required" => "valor_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_parcelas",
                "nome"         => "parcelas",
                "nomeidioma"   => "form_parcelas",
                "ajudaidioma"  => "form_parcelas_ajuda",
                "evento"       => "maxlength='2' onblur='calcularParcelas()'",
                "tipo"         => "input",
                "valor"        => "parcelas",
                "class"        => "span1",
                "numerico"     => true,
                "banco"        => true,
                "banco_string" => true
            ),
            array(
                "id"           => "data_pagamento",
                "nome"         => "data_pagamento",
                "nomeidioma"   => "form_data_pagamento",
                "tipo"         => "input",
                "valor"        => "data_pagamento",
                //"validacao" => array("required" => "data_pagamento_vazio"),
                "valor_php"    => 'if($dados["data_pagamento"] && $dados["data_pagamento"] != "0000-00-00") return formataData("%s", "br", 0)',
                "class"        => "span2",
                "mascara"      => "99/99/9999",
                "datepicker"   => true,
                "banco"        => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            array(
                "id"           => "form_valor_pago", // Id do atributo HTML
                "nome"         => "valor_pago", // Name do atributo HTML
                "nomeidioma"   => "form_valor_pago", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='8'",
                "decimal"      => true,
                "valor"        => "valor_pago", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "valor_pago_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"               => "form_idconta_corrente",
                "nome"             => "idconta_corrente",
                "nomeidioma"       => "form_idconta_corrente",
                "tipo"             => "select",
                "sql"              => "SELECT idconta_corrente, nome FROM contas_correntes where ativo = 'S' AND ativo_painel = 'S'", // SQL que alimenta o select
                "sql_valor"        => "idconta_corrente", // Coluna da tabela que será usado como o valor do options
                "sql_label"        => "nome", // Coluna da tabela que será usado como o label do options
                "valor"            => "idconta_corrente",
                //"validacao" => array("required" => "idconta_corrente_vazio"),
                "referencia_label" => "cadastro_contacorrente",
                "referencia_link"  => "/gestor/financeiro/contascorrentes",
                "banco"            => true
            ),
            array(
                "id"           => "form_matriculas",
                "nome"         => "matriculas",
                "nomeidioma"   => "form_matriculas",
                "tipo"         => "select",
                "valor"        => "matriculas",
                //"validacao" => array("required" => "matriculas_vazio"),
                "class"        => "invisivel",
                "banco"        => true,
                "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
        )
    )
);

$config["formulario_editar"] = array(
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
                "id"           => "form_valor", // Id do atributo HTML
                "nome"         => "valor", // Name do atributo HTML
                "nomeidioma"   => "form_valor", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='13' onblur='calcularParcelas()'",
                "decimal"      => true,
                "valor"        => "valor", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"    => array("required" => "valor_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "classe_label" => "control-label",
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
        )
    )
);
?>