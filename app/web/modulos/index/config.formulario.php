<?php
$config["monitoramento"]["onde"] = "16";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
    "tabela" => "pessoas",
    "primaria" => "idpessoa",
    "campos_insert_fixo" => array(
        "data_cad" => "now()",
        "ativo" => "'S'"
    ),

    "campos_unicos" => array(
        array(
            "campo_banco" => "documento",
            "campo_form" => "documento",
            "erro_idioma" => "cpf_utilizado",
            "campo_php" => 'return str_replace(array(".", "-", "/"),"","%s")'
        ),

        array(
            "campo_banco" => "email",
            "campo_form" => "email",
            "erro_idioma" => "email_utilizado",
        )
    ),
);

$config["formulario_pagamentocurso"] = array(
    array(
        "fieldsetid" => "dadosdoobjeto",
        "legendaidioma" => "legendadadosdados",
        "campos" => array(
            array(
                "id" => "email",
                "nome" => "email",
                "nomeidioma" => "email",
                "tipo" => "input",
                "valor" => "email",
                "ajudaidioma" => "email_ajuda",
                "validacao" => array("required" => "email_vazio", "valid_email" => "email_invalido"),
                "class" => "span5",
                "legenda" => "@",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='100'"
            ),
            array(
                "id" => "documento",
                "nome" => "documento",
                "tipo" => "input",
                "valor" => "documento",
                "validacao" => array("valida_cpf" => "documento_invalido", "required" => "documento_vazio"),
                "banco" => true,
                "banco_php" => 'return str_replace(array(".", "-","/"),"","%s")',
                "banco_string" => true,
                'ignorarsevazio' => true
            ),
            array(
                "id" => "nome",
                "nome" => "nome",
                "nomeidioma" => "nome",
                "tipo" => "input",
                "valor" => "nome",
                "validacao" => array("required" => "nome_vazio"),
                "class" => "span6",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "cnh",
                "nome"         => "cnh",
                "nomeidioma"   => "cnh",
                "tipo"         => "input",
                "valor"        => "cnh",
                "validacao" => array("required" => "cnh_vazio"),
                "class"        => "span2",
                "evento"       => "maxlength='30'",
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id" => "categoria",
                "nome" => "categoria",
                "tipo" => "input",
                "valor" => 'return $dados["categoria"];',
                "validacao" => array("required" => "categoria_vazio"),
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "sexo",
                "nome" => "sexo",
                "nomeidioma" => "sexo",
                "tipo" => "select",
                "array" => "sexo",
                "class" => "span2",
                "valor" => "sexo",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "celular",
                "nome" => "celular",
                "nomeidioma" => "celular",
                "tipo" => "input",
                "valor" => "celular",
                "validacao" => array("required" => "celular_vazio"),
                "class" => "span2",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "ato_punitivo",
                "nome" => "ato_punitivo",
                "nomeidioma" => "ato_punitivo",
                "tipo" => "input",
                "valor" => "ato_punitivo",
               #"validacao" => array("required" => "ato_punitivo_vazio"),
                "class" => "span6",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "data_nasc",
                "nome" => "data_nasc",
                "nomeidioma" => "data_nasc",
                "tipo" => "input",
                "valor" => "data_nasc",
                "valor_php" => 'if($dados["data_nasc"] && $dados["data_nasc"] != "0000-00-00") return formataData("%s", "br", 0)',
                "class" => "span2",
                "mascara" => "99/99/9999",
                "validacao" => array("required" => "data_nasc_vazio"),
                "banco" => true,
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            array(
                "id"           => "rg",
                "nome"         => "rg",
                "nomeidioma"   => "rg",
                "tipo"         => "input",
                "valor"        => "rg",
                "class"        => "span2",
                "evento"       => "maxlength='30'",
                "validacao" => array("required" => "rg_vazio"),
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "rg_orgao_emissor",
                "nome"         => "rg_orgao_emissor",
                "nomeidioma"   => "rg_orgao_emissor",
                "tipo"         => "input",
                "valor"        => "rg_orgao_emissor",
                "class"        => "span2",
                "evento"       => "maxlength='10'",
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id" => "idpais",
                "nome" => "idpais",
                "tipo" => "hidden",
                "valor" => 'return $dados["idpais"];',
                "banco" => true
            ),
            array(
                "id" => "cep",
                "nome" => "cep",
                "tipo" => "input",
                "valor" => 'return $dados["cep"];',
                "validacao" => array("required" => "cep_vazio"),
                "banco_php" => 'return str_replace(array("-", ""),"","%s")',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "endereco",
                "nome" => "endereco",
                "tipo" => "input",
                "valor" => 'return $dados["endereco"];',
                "validacao" => array("required" => "endereco_vazio"),
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "bairro",
                "nome" => "bairro",
                "tipo" => "input",
                "valor" => 'return $dados["bairro"];',
                "validacao" => array("required" => "bairro_vazio"),
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "numero",
                "nome" => "numero",
                "tipo" => "input",
                "valor" => 'return $dados["numero"];',
                "validacao" => array("required" => "numero_vazio"),
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "complemento",
                "nome" => "complemento",
                "tipo" => "input",
                "valor" => 'return $dados["complemento"];',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "idestado",
                "nome" => "idestado",
                "tipo" => "hidden",
                "valor" => 'return $dados["idestado"];',
                "validacao" => array("required" => "idestado_vazio"),
                "banco" => true
            ),
            array(
                "id" => "idcidade",
                "nome" => "idcidade",
                "tipo" => "hidden",
                "valor" => 'return $dados["idcidade"];',
                "validacao" => array("required" => "idcidade_vazio"),
                "banco" => true
            ),
        )
    )
);

$config["formulario_pessoas"] = array(
    array(
        "fieldsetid" => "dadosdoobjeto",
        "legendaidioma" => "legendadadosdados",
        "campos" => array(
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
                "ajudaidioma" => 'form_nome_ajuda',
                'banco_php' => 'return mb_strtoupper("%s")'
            ),
            array(
                "id" => "form_sobrenome",
                "nome" => "sobrenome",
                "nomeidioma" => "form_sobrenome",
                "tipo" => "input",
                "valor" => "sobrenome",
                "validacao" => array("required" => "sobrenome_vazio"),
                "class" => "span6",
                "banco" => false,
                'banco_php' => 'return mb_strtoupper("%s")'
            ),
            array(
                "id" => "form_documento",
                "nome" => "documento",
                "tipo" => "input",
                "valor" => "documento",
                "validacao" => array("required" => "documento_vazio", "valida_cpf" => "documento_invalido"),
                "banco" => true,
                "banco_php" => 'return str_replace(array(".", "-","/"),"","%s")',
                "banco_string" => true,
            ),
            array(
                "id" => "sexo",
                "nome" => "sexo",
                "nomeidioma" => "form_sexo",
                "tipo" => "select",
                "array" => "sexo",
                "class" => "span2",
                "valor" => "sexo",
                "validacao" => array("required" => "sexo_vazio"),
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_celular",
                "nome" => "celular",
                "nomeidioma" => "form_celular",
                "tipo" => "input",
                "valor" => "celular",
                "validacao" => array("required" => "celular_vazio"),
                "class" => "span2",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_email",
                "nome" => "email",
                "nomeidioma" => "form_email",
                "tipo" => "input",
                "valor" => "email",
                "ajudaidioma" => "form_email_ajuda",
                "validacao" => array("required" => "email_vazio", "valid_email" => "email_invalido"),
                "class" => "span5",
                "legenda" => "@",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='100'"
            ),
            array(
                "id" => "form_senha",
                "nome" => "senha",
                "tipo" => "hidden",
                "valor" => "senha",
                "banco" => true,
                "banco_php" => 'return senhaSegura("%s","'.$config["chaveLogin"].'")',
                "banco_string" => true,
            ),
            array(
                "id" => "estado_civil",
                "nome" => "estado_civil",
                "tipo" => "hidden",
                "valor" => 'return $dados["estado_civil"];',
                "banco" => true
            ),
            array(
                "id" => "data_nasc",
                "nome" => "data_nasc",
                "tipo" => "hidden",
                "valor" => "data_nasc",
                "valor_php"    => 'if($dados["data_nasc"] && $dados["data_nasc"] != "0000-00-00") return formataData("%s", "br", 0)',
                "validacao" => array("required" => "data_nasc_vazio"),
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_nacionalidade",
                "nome" => "nacionalidade",
                "tipo" => "hidden",
                "valor" => 'return $dados["nacionalidade"];',
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "idpais",
                "nome" => "idpais",
                "tipo" => "hidden",
                "valor" => 'return $dados["idpais"];',
                "banco" => true
            ),
            array(
                "id" => "naturalidade",
                "nome" => "naturalidade",
                "tipo" => "hidden",
                "valor" => 'return $dados["naturalidade"];',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "rg",
                "nome" => "rg",
                "tipo" => "hidden",
                "valor" => 'return $dados["rg"];',
                "validacao" => array("required" => "rg_vazio"),
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_rg_orgao_emissor",
                "nome" => "rg_orgao_emissor",
                "tipo" => "hidden",
                "valor" => 'return $dados["rg_orgao_emissor"];',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "rg_data_emissao",
                "nome" => "rg_data_emissao",
                "tipo" => "hidden",
                "valor" => "rg_data_emissao",
                "valor_php" => 'if($dados["rg_data_emissao"]) return formataData("%s", "br", 0)',
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id"           => "form_cnh",
                "nome"         => "cnh",
                "nomeidioma"   => "form_cnh",
                "tipo"         => "input",
                "valor"        => "cnh",
                "validacao" => array("required" => "cnh_vazio"),
                "class"        => "span2",
                "evento"       => "maxlength='30'",
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "form_categoria",
                "nome"         => "categoria",
                "nomeidioma"   => "form_categoria",
                "tipo"         => "input",
                "valor"        => "categoria",
                "validacao" => array("required" => "categoria_vazio"),
                "class"        => "span2",
                "evento"       => "maxlength='10'",
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id" => "data_primeira_habilitacao",
                "nome" => "data_primeira_habilitacao",
                "tipo" => "input",
                "valor" => "data_primeira_habilitacao",
                "valor_php"    => 'if($dados["data_primeira_habilitacao"] && $dados["data_primeira_habilitacao"] != "0000-00-00") return formataData("%s", "br", 0)',
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "cnh_data_emissao",
                "nome" => "cnh_data_emissao",
                "tipo" => "input",
                "valor" => "cnh_data_emissao",
                "valor_php"    => 'if($dados["cnh_data_emissao"] && $dados["cnh_data_emissao"] != "0000-00-00") return formataData("%s", "br", 0)',
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "data_validade",
                "nome" => "data_validade",
                "tipo" => "input",
                "valor" => "data_validade",
                "valor_php"    => 'if($dados["data_validade"] && $dados["data_validade"] != "0000-00-00") return formataData("%s", "br", 0)',
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "filiacao_mae",
                "nome" => "filiacao_mae",
                "tipo" => "hidden",
                "valor" => 'return $dados["filiacao_mae"];',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "filiacao_pai",
                "nome" => "filiacao_pai",
                "tipo" => "hidden",
                "valor" => 'return $dados["filiacao_pai"];',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "cep",
                "nome" => "cep",
                "tipo" => "hidden",
                "valor" => 'return $dados["cep"];',
                "validacao" => array("required" => "cep_vazio"),
                "banco" => true,
                "banco_php"    => 'return str_replace(array("-", ""),"","%s")',
                "banco_string" => true
            ),
            array(
                "id" => "endereco",
                "nome" => "endereco",
                "tipo" => "hidden",
                "valor" => 'return $dados["endereco"];',
                "validacao" => array("required" => "endereco_vazio"),
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "bairro",
                "nome" => "bairro",
                "tipo" => "hidden",
                "valor" => 'return $dados["bairro"];',
                "validacao" => array("required" => "bairro_vazio"),
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "numero",
                "nome" => "numero",
                "tipo" => "hidden",
                "valor" => 'return $dados["numero"];',
                "validacao" => array("required" => "numero_vazio"),
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "complemento",
                "nome" => "complemento",
                "tipo" => "hidden",
                "valor" => 'return $dados["complemento"];',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "idestado",
                "nome" => "idestado",
                "tipo" => "hidden",
                "valor" => 'return $dados["idestado"];',
                "validacao" => array("required" => "idestado_vazio"),
                "banco" => true
            ),
            array(
                "id" => "idcidade",
                "nome" => "idcidade",
                "tipo" => "hidden",
                "valor" => 'return $dados["idcidade"];',
                "validacao" => array("required" => "idcidade_vazio"),
                "banco" => true
            ),
            array(
                "id" => "profissao",
                "nome" => "profissao",
                "tipo" => "hidden",
                "valor" => 'return $dados["profissao"];',
                "banco" => true,
                "banco_string" => true
            ),
        )
)
);
