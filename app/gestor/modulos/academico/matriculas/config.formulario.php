<?php
if ($_SESSION ['matricula'] ['pessoa'] ['idpessoa']) {
    $sqlMatricula = 'select m.idmatricula, CONCAT(m.idmatricula, " (", c.nome, ")") as nome_curso
                        from matriculas m
                        inner join cursos c on m.idcurso = c.idcurso
                        where m.ativo = "S" and m.idpessoa = ' . $_SESSION ['matricula'] ['pessoa'] ['idpessoa'];
}

// Array de configuração para a formulario
$config ["formulario_pessoas"] = array (
    array (
        "fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array ( // Campos do formulario
            array(
                "id"           => "documento_tipo",
                "nome"         => "documento_tipo",
                "nomeidioma"   => "form_tipo",
                "tipo"         => "input",
                "valor"        => "documento_tipo",
                "banco"        => true,
                "class"        => "invisivel",
                "input_hidden" => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "documento",
                "nome"         => "documento",
                "tipo"         => "input",
                "valor"        => "documento",
                "class"        => "invisivel",
                "banco"        => true,
                "input_hidden" => true,
                "banco_php"    => 'return str_replace(array(".", "-","/"),"","%s")',
                "banco_string" => true
            ),
            array(
                "id"           => "form_rg",
                "nome"         => "rg",
                "nomeidioma"   => "form_rg",
                "tipo"         => "input",
                "valor"        => "rg",
                "validacao"    => array("required" => "rg_vazio"),
                "class"        => "span2",
                "evento"       => "maxlength='20'",
                //"numerico" => true,
                "banco"        => true,
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "input_hidden" => true
            ),
            array(
                "id"           => "form_rg_orgao_emissor",
                "nome"         => "rg_orgao_emissor",
                "nomeidioma"   => "form_orgao_emissor",
                "tipo"         => "input",
                "valor"        => "rg_orgao_emissor",
                // "validacao"    => array("required" => "orgao_emissor_vazio"),
                "class"        => "span2",
                "evento"       => "maxlength='20'",
                "banco"        => true,
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "input_hidden" => true
            ),
            array(
                "id"           => "rg_data_emissao",
                "nome"         => "rg_data_emissao",
                "nomeidioma"   => "form_data_emissao",
                "tipo"         => "input",
                "valor"        => "rg_data_emissao",
                "valor_php"    => 'if($dados["rg_data_emissao"]) return formataData("%s", "br", 0)',
                "class"        => "span2",
                "mascara"      => "99/99/9999",
                "datepicker"   => true,
                "banco"        => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true,
                "input_hidden" => true
            ),
            array(
                "id"           => "form_rne",
                "nome"         => "rne",
                "nomeidioma"   => "form_rne",
                "ajudaidioma"  => "form_rne_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
                "tipo"         => "input",
                "valor"        => "rne",
                //"validacao" => array("required" => "rne_vazio"),
                "class"        => "span2",
                "evento"       => "maxlength='30'",
                //"numerico" => true,
                "banco"        => true,
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "input_hidden" => true
            ),
            array(
                "id"           => "form_razao_social", // Id do atributo HTML
                "nome"         => "razao_social", // Name do atributo HTML
                "nomeidioma"   => "form_razao_social", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='200'",
                "valor"        => "razao_social", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "razao_social"), // Validação do campo
                "class"        => "span5", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "input_hidden" => true
            ),
            array(
                "id"           => "form_nome_fantasia", // Id do atributo HTML
                "nome"         => "nome_fantasia", // Name do atributo HTML
                "nomeidioma"   => "form_nome_fantasia", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='100'",
                "valor"        => "nome_fantasia", // Nome da coluna da tabela do banco de dados que retorna o valor.
                // "validacao" => array("required" => "nome_fantasia"), // Validação do campo
                "class"        => "span5", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "input_hidden" => true
            ),
            array(
                "id"           => "form_representante", // Id do atributo HTML
                "nome"         => "representante", // Name do atributo HTML
                "nomeidioma"   => "form_representante", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='100'",
                "valor"        => "representante", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "representante"), // Validação do campo
                "class"        => "span5", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "input_hidden" => true
            ),
            array(
                "id"           => "form_nome",
                "nome"         => "nome",
                "nomeidioma"   => "form_nome",
                "tipo"         => "input",
                "valor"        => "nome",
                "evento" => "onMouseOut=letrasMaiusculas(this), onfocusout=letrasMaiusculas(this)",
                "validacao"    => array("required" => "nome_vazio"),
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
            array (
                "id" => "sexo",
                "nome" => "sexo",
                "nomeidioma" => "form_sexo",
                "tipo" => "select",
                "array" => "sexo", // Array que alimenta o select
                "class" => "span2",
                "valor" => "sexo",
                "validacao" => array (
                    "required" => "sexo_vazio"
                ),
                "banco" => true,
                "banco_string" => true
            ),
            array (
                "id" => "estado_civil", // Id do atributo HTML
                "nome" => "estado_civil", // Name do atributo HTML
                "tipo" => "hidden", // Tipo do input
                "valor" => 'return $dados["estado_civil"];',
                "banco" => true
            ),
            array (
                "id" => "data_nasc", // Id do atributo HTML
                "nome" => "data_nasc", // Name do atributo HTML
                "nomeidioma"   => "form_nascimento",
                "tipo" => "input", // Tipo do input
                "valor" => 'data_nasc',
                "valor_php" => 'if($dados["data_nasc"]) return formataData("%s", "br", 0)',
                "validacao" => array ("required" => "form_data_nascimento_vazio"),
                "mascara"      => "99/99/9999",
                //"datepicker"   => true,
                "banco" => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            array (
                "id" => "idpais", // Id do atributo HTML
                "nome" => "idpais", // Name do atributo HTML
                "tipo" => "hidden", // Tipo do input
                "valor" => 'return $dados["idpais"];',
                "banco" => true
            ),
            array (
                "id" => "naturalidade", // Id do atributo HTML
                "nome" => "naturalidade", // Name do atributo HTML
                "tipo" => "hidden", // Tipo do input
                "valor" => 'return $dados["naturalidade"];',
                "banco" => true,
                "banco_string" => true
            ),
            array (
                "id" => "rg", // Id do atributo HTML
                "nome" => "rg", // Name do atributo HTML
                "tipo" => "hidden", // Tipo do input
                "valor" => 'return $dados["rg"];',
                "banco" => true,
                "banco_string" => true
            ),
            array (
                "id" => "filiacao_mae", // Id do atributo HTML
                "nome" => "filiacao_mae", // Name do atributo HTML
                "tipo" => "hidden", // Tipo do input
                "valor" => 'return $dados["filiacao_mae"];',
                "banco" => true,
                "banco_string" => true
            ),
            array (
                "id" => "filiacao_pai", // Id do atributo HTML
                "nome" => "filiacao_pai", // Name do atributo HTML
                "tipo" => "hidden", // Tipo do input
                "valor" => 'return $dados["filiacao_pai"];',
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id"            => "form_cep",
                "nome"          => "cep",
                "nomeidioma"    => "form_cep",
                "tipo"          => "input",
                "valor"         => "cep",
                "validacao"     => array("required" => "cep_vazio"),
                "class"         => "span2",
                "ajudaidioma"   => "form_cep_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
                "mascara"       => "99999-999", //Mascara do campo
                "banco"         => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_php"     => 'return str_replace(array("-", ""),"","%s")', // Executa um script php antes de salvar no banco (Utilizado na função SalvarDados)
                "banco_string"  => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"         => "idlogradouro",
                "nome"       => "idlogradouro",
                "nomeidioma" => "form_logradouro",
                "tipo"       => "select",
                "sql"        => "SELECT idlogradouro, nome FROM logradouros WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome ", // SQL que alimenta o select
                "sql_valor"  => "idlogradouro", // Coluna da tabela que será usado como o valor do options
                "sql_label"  => "nome", // Coluna da tabela que será usado como o label do options
                "valor"      => "idlogradouro",
                "validacao" => array("required" => "logradouro_vazio"),
                "banco"      => true
            ),
            array(
                "id"            => "form_endereco", // Id do atributo HTML
                "nome"          => "endereco", // Name do atributo HTML
                "nomeidioma"    => "form_endereco", // Referencia a variavel de idioma
                "tipo"          => "input", // Tipo do input
                "evento"        => "maxlength='100'",
                "valor"         => "endereco", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"     => array("required" => "endereco_vazio"), // Validação do campo
                "class"         => "span6", //Class do atributo HTML
                "banco"         => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string"  => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"            => "form_bairro", // Id do atributo HTML
                "nome"          => "bairro", // Name do atributo HTML
                "nomeidioma"    => "form_bairro", // Referencia a variavel de idioma
                "tipo"          => "input", // Tipo do input
                "evento"        => "maxlength='100'",
                "valor"         => "bairro", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"     => array("required" => "bairro_vazio"), // Validação do campo
                "class"         => "span5", //Class do atributo HTML
                "banco"         => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string"  => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"            => "form_numero", // Id do atributo HTML
                "nome"          => "numero", // Name do atributo HTML
                "nomeidioma"    => "form_numero", // Referencia a variavel de idioma
                "ajudaidioma"   => "form_numero_ajuda",
                "tipo"          => "input", // Tipo do input
                "evento"        => "maxlength='10'",
                "valor"         => "numero", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"     => array("required" => "numero_vazio"), // Validação do campo
                "class"         => "span2", //Class do atributo HTML
                "banco"         => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string"  => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_complemento", // Id do atributo HTML
                "nome"         => "complemento", // Name do atributo HTML
                "nomeidioma"   => "form_complemento", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='100'",
                "valor"        => "complemento", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "complemento_vazio"), // Validação do campo
                "class"        => "span4", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"         => "idestado",
                "nome"       => "idestado",
                "nomeidioma" => "form_idestado",
                "tipo"       => "select",
                "sql"        => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
                "sql_valor"  => "idestado", // Coluna da tabela que será usado como o valor do options
                "sql_label"  => "nome", // Coluna da tabela que será usado como o label do options
                "valor"      => "idestado",
                "validacao" => array("required" => "form_selecione_estado"),
                "banco"      => true
            ),
            array(
                "id"                    => "idcidade",
                "nome"                  => "idcidade",
                "nomeidioma"            => "form_idcidade",
                "json"                  => true,
                "json_idpai"            => "idestado",
                "json_url"              => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . '/' . $url["3"] . "/ajax_cidades/",
                "json_input_pai_vazio"  => "form_selecione_estado",
                "json_input_vazio"      => "form_selecione_cidade",
                "json_campo_exibir"     => "nome",
                "tipo"                  => "select",
                "valor"                 => "idcidade",
                "sql_valor"             => "idcidade",
                "validacao"             => array("required" => "form_selecione_cidade"),
                "banco"                 => true
            ),
            array(
                "id"            => "profissao", // Id do atributo HTML
                "nome"          => "profissao", // Name do atributo HTML
                "nomeidioma"    => "form_profissao", // Referencia a variavel de idioma
                "tipo"          => "input", // Tipo do input
                "valor"         => "profissao", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao"     => array(), // Validação do campo
                "class"         => "span4", //Class do atributo HTML
                "banco"         => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string"  => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            )
        )
    ),

    array(
        "fieldsetid"    => "dados_documentos", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_dados_documentos", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"           => "form_rg",
                "nome"         => "rg",
                "nomeidioma"   => "form_rg",
                "tipo"         => "input",
                "valor"        => "rg",
                "validacao"    => array("required" => "rg_vazio"),
                "class"        => "span2",
                "evento"       => "maxlength='20'",
                //"numerico" => true,
                "banco"        => true,
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                //"input_hidden" => false
            ),
            array(
                "id"           => "form_rg_orgao_emissor",
                "nome"         => "rg_orgao_emissor",
                "nomeidioma"   => "form_orgao_emissor",
                "tipo"         => "input",
                "valor"        => "rg_orgao_emissor",
                // "validacao"    => array("required" => "orgao_emissor_vazio"),
                "class"        => "span2",
                "evento"       => "maxlength='20'",
                "banco"        => true,
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                //"input_hidden" => true
            ),
            array(
                "id"           => "rg_data_emissao",
                "nome"         => "rg_data_emissao",
                "nomeidioma"   => "form_data_emissao",
                "tipo"         => "input",
                "valor"        => "rg_data_emissao",
                "valor_php"    => 'if($dados["rg_data_emissao"]) return formataData("%s", "br", 0)',
                "class"        => "span2",
                "mascara"      => "99/99/9999",
                "datepicker"   => true,
                "banco"        => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true,
                //"input_hidden" => true
            ),
            array(
                "id"           => "form_rne",
                "nome"         => "rne",
                "nomeidioma"   => "form_rne",
                "ajudaidioma"  => "form_rne_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
                "tipo"         => "input",
                "valor"        => "rne",
                //"validacao" => array("required" => "rne_vazio"),
                "class"        => "span2",
                "evento"       => "maxlength='30'",
                //"numerico" => true,
                "banco"        => true,
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                //"input_hidden" => true
            ),
            array(
                "id"           => "form_razao_social", // Id do atributo HTML
                "nome"         => "razao_social", // Name do atributo HTML
                "nomeidioma"   => "form_razao_social", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='200'",
                "valor"        => "razao_social", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "razao_social"), // Validação do campo
                "class"        => "span5", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                //"input_hidden" => true
            ),
            array(
                "id"           => "form_nome_fantasia", // Id do atributo HTML
                "nome"         => "nome_fantasia", // Name do atributo HTML
                "nomeidioma"   => "form_nome_fantasia", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='100'",
                "valor"        => "nome_fantasia", // Nome da coluna da tabela do banco de dados que retorna o valor.
                // "validacao" => array("required" => "nome_fantasia"), // Validação do campo
                "class"        => "span5", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                //"input_hidden" => true
            ),
            array(
                "id"           => "form_representante", // Id do atributo HTML
                "nome"         => "representante", // Name do atributo HTML
                "nomeidioma"   => "form_representante", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='100'",
                "valor"        => "representante", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "representante"), // Validação do campo
                "class"        => "span5", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                //"input_hidden" => true
            )
        )
    ),
    array(
        "fieldsetid"    => "dados_habilitacao", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_dados_habilitacao", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
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
                //"numerico" => true,
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "form_data_primeira_habilitacao",
                "nome"         => "data_primeira_habilitacao",
                "nomeidioma"   => "form_data_primeira_habilitacao",
                "tipo"         => "input",
                "valor"        => "data_primeira_habilitacao",
                "valor_php"    => 'if($dados["data_primeira_habilitacao"]) return formataData("%s", "br", 0)',
                "class"        => "span2",
                "mascara"      => "99/99/9999",
                "datepicker"   => true,
                "banco"        => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true,
            ),
            array(
                "id"           => "cnh_data_emissao",
                "nome"         => "cnh_data_emissao",
                "nomeidioma"   => "form_cnh_data_emissao",
                "tipo"         => "input",
                "valor"        => "cnh_data_emissao",
                "valor_php"    => 'if($dados["cnh_data_emissao"]) return formataData("%s", "br", 0)',
                "class"        => "span2",
                "mascara"      => "99/99/9999",
                "datepicker"   => true,
                "banco"        => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true,
            ),
            array(
                "id"           => "data_validade",
                "nome"         => "data_validade",
                "nomeidioma"   => "form_data_validade",
                "tipo"         => "input",
                "valor"        => "data_validade",
                "valor_php"    => 'if($dados["data_validade"]) return formataData("%s", "br", 0)',
                "class"        => "span2",
                "mascara"      => "99/99/9999",
                "datepicker"   => true,
                "banco"        => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true,
            ), 
            array(
                "id"           => "renach",
                "nome"         => "renach",
                "nomeidioma"   => "renach",
                "tipo"         => "input",
                "valor"        => "renach",
//              "validacao" => array("required" => "ato_punitivo_vazio"),
                "class"        => "span2",
                "evento"       => "maxlength='50'",
                "banco"        => false,
                "banco_string" => true,
            ),
        )
    ),

    array (
        "fieldsetid" => "dados_contato", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_dados_contato", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array ( // Campos do formulario
            array (
                "id" => "form_telefone",
                "nome" => "telefone",
                "nomeidioma" => "form_telefone",
                "tipo" => "input",
                "valor" => "telefone",
                // "validacao" => array("required" => "telefone_vazio"),
                "class" => "span2",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true
            ),
            array (
                "id" => "form_celular",
                "nome" => "celular",
                "nomeidioma" => "form_celular",
                "tipo" => "input",
                "valor" => "celular",
                "validacao" => array (
                    "required" => "celular_vazio"
                ),
                "class" => "span2",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true
            ),
            array (
                "id" => "form_email",
                "nome" => "email",
                "nomeidioma" => "form_email",
                "tipo" => "input",
                "valor" => "email",
                "ajudaidioma" => "form_email_ajuda",
                "validacao" => array (
                    "required" => "email_vazio",
                    "valid_email" => "email_invalido"
                ),
                "class" => "span5",
                "legenda" => "@",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='100'"
            )
        )
    ),
    array (
        "fieldsetid" => "dados_outros", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_dados_outros", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array ( // Campos do formulario
            array (
                "id" => "form_observacoes",
                "nome" => "observacoes",
                "nomeidioma" => "form_observacoes",
                "tipo" => "text",
                "valor" => "observacoes",
                "class" => "xxlarge",
                "banco" => true,
                "banco_string" => true
            )
        )
    )
);

$config ["formulario_financeiro"] = array (
    array (
        "fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array ( // Campos do formulario
            array (
                "id" => "form_numero_contrato",
                "nome" => "numero_contrato",
                "nomeidioma" => "form_numero_contrato",
                "tipo" => "input",
                "valor" => "numero_contrato",
                //"validacao" => array ("required" => "numero_contrato_vazio"),
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
                "input_hidden" => true

            ),
            array (
                "id" => "form_combo",
                "nome" => "combo",
                "nomeidioma" => "form_combo",
                "tipo" => "select",
                "array" => "sim_nao", // Array que alimenta o select
                "class" => "span2",
                "valor" => "combo",
                //"validacao" => array ("required" => "combo_vazio"),
                "banco" => true,
                "banco_string" => true,
                "select_hidden" => true
            ),
            array (
                "id" => "form_combo_matricula",
                "sql" => $sqlMatricula,
                "nome" => "combo_matricula",
                "tipo" => "select",
                "valor" => "combo_matricula",
                "class" => "span2",
                "sql_valor" => "idmatricula",
                "sql_label" => "nome_curso",
                //"validacao" => array ("required" => "combo_matricula_vazio"),
                "nomeidioma" => "form_combo_matricula",
                "select_hidden" => true
            ),
            /*array(
              "id" => "form_combo_matricula",
              "nome" => "combo_matricula",
              "nomeidioma" => "form_combo_matricula",
              "tipo" => "input",
              "valor" => "combo_matricula",
              "validacao" => array("required" => "combo_matricula_vazio"),
              "numerico" => true,
              "class" => "span2",
              "banco" => true,
              "banco_string" => true,
              "input_hidden" => true,
            ),*/
            array (
                "id" => "form_bolsa",
                "nome" => "bolsa",
                "nomeidioma" => "form_bolsa",
                "tipo" => "select",
                "array" => "bolsaMatricula", // Array que alimenta o select
                "class" => "span2",
                "valor" => "bolsa",
                //"validacao" => array ("required" => "bolsa_vazio"),
                "banco" => true,
                "banco_string" => true,
                "botao_hide" => true,
                "iddiv" => "valor_contrato_quantidade_parcelas",
                "iddiv2" => "idsolicitante",
                "select_hidden" => true
            ),
            array (
                "id" => "form_valor_contrato",
                "nome" => "valor_contrato",
                "nomeidioma" => "form_valor_contrato",
                "tipo" => "input",
                "valor" => "valor_contrato",
                "legenda" => "R$",
                //"validacao" => array ("required" => "valor_contrato_vazio"),
                "decimal" => true,
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
                //"evento" => "readonly",
            ),
            array (
                "id" => "form_quantidade_parcelas",
                "nome" => "quantidade_parcelas",
                "nomeidioma" => "form_quantidade_parcelas",
                "tipo" => "input",
                "valor" => "quantidade_parcelas",
                //"validacao" => array ("required" => "quantidade_parcelas_vazio"),
                "class" => "span1",
                "evento" => "maxlength='2'",
                "numerico" => true,
                "banco" => true,
                "banco_string" => true,
            ),
            array (
                "id" => "form_idsolicitante",
                "nome" => "idsolicitante",
                "nomeidioma" => "form_idsolicitante",
                "tipo" => "select",
                "sql" => "SELECT idsolicitante, nome FROM solicitantes_bolsas ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idsolicitante", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idsolicitante",
                //"validacao" => array ("required" => "idsolicitante_vazio"),
                "banco" => true,
                "select_hidden" => true
            ),
            array (
                "id" => "form_forma_pagamento",
                "nome" => "forma_pagamento",
                "nomeidioma" => "form_forma_pagamento",
                "tipo" => "select",
                "array" => "forma_pagamento_conta", // Array que alimenta o select
                "class" => "span2",
                "valor" => "forma_pagamento",
                //"validacao" => array ("required" => "form_forma_pagamento_vazio"),
                "banco" => true,
                "banco_string" => true,
                "select_hidden" => true,
                "botao_hide" => true,
                "iddiv3" => "idbandeira",
                "iddiv4" => "autorizacao_cartao"
            ),
            array (
                "id" => "form_idbandeira",
                "nome" => "idbandeira",
                "nomeidioma" => "form_idbandeira",
                "tipo" => "select",
                "sql" => "SELECT idbandeira, nome FROM bandeiras_cartoes where ativo = 'S' AND ativo_painel = 'S'", // SQL que alimenta o select
                "sql_valor" => "idbandeira", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idbandeira",
                //"validacao" => array ("required" => "idbandeira_vazio"),
                // "referencia_label" => "cadastro_bandeiras",
                // "referencia_link" => "/gestor/financeiro/bandeirascartoes",
                "banco" => true,
                "select_hidden" => true
            ),
            array (
                "id" => "form_autorizacao_cartao",
                "nome" => "autorizacao_cartao",
                "nomeidioma" => "form_autorizacao_cartao",
                "tipo" => "input",
                "valor" => "autorizacao_cartao",
                // "validacao" => array("required" => "autorizacao_cartao_vazio"),
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
                "input_hidden" => true
            ),
            array (
                "id" => "form_data_registro",
                "nome" => "data_registro",
                "nomeidioma" => "form_data_registro",
                "tipo" => "input",
                "valor" => "data_registro",
                //"evento" => "disabled='disabled'",
                // "valor_php" => 'if($dados["data_registro"]) return formataData("%s", "br", 0)',
                //"validacao" => array ("required" => "form_data_registro_vazio"),
                "valor_php" => 'return date("d/m/Y")',
                "class" => "span2",
                "mascara" => "99/99/9999",
                //"datepicker" => true,
                "banco" => true,
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco_string" => true,
                "evento" => "readonly",
            ),
            array (
                "id" => "form_data_matricula",
                "nome" => "data_matricula",
                "nomeidioma" => "form_data_matricula",
                "tipo" => "input",
                "valor" => "data_matricula",
                "valor_php" => 'return date("d/m/Y")',
                "class" => "span2",
                "mascara" => "99/99/9999",
                //"datepicker" => true,
                "banco" => true,
                "banco_php" => 'return formataData("%s", "en", 0)',
                //"validacao" => array ("required" => "form_data_matricula_vazio" ),
                "banco_string" => true,
                "evento" => "readonly",
            ),
            array (
                "id" => "form_idempresa",
                "nome" => "idempresa",
                "nomeidioma" => "form_idempresa",
                "tipo" => "select",
                "sql" => "SELECT idempresa, nome FROM empresas where ativo = 'S' AND ativo_painel = 'S'", // SQL que alimenta o select
                "sql_valor" => "idempresa", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idempresa",
                // "validacao" => array("required" => "idempresa_vazio"),
                // "referencia_label" => "cadastro_bandeiras",
                // "referencia_link" => "/gestor/financeiro/bandeirascartoes",
                "banco" => true,
                "select_hidden" => true
            ),
            /*        array (
                            "id" => "form_observacao",
                            "nome" => "observacao",
                            "nomeidioma" => "form_observacao",
                            "tipo" => "text",
                            "valor" => "observacao",
                            "class" => "span8",
                            "banco" => true,
                            "banco_string" => true,

                    ),*/
            array (
                "id" => "form_gerar_visita",
                "nome" => "gerar_visita",
                "nomeidioma" => "form_gerar_visita",
                "tipo" => "select",
                "array" => "sim_nao", // Array que alimenta o select
                "class" => "span2",
                "valor" => "gerar_visita",
                // "validacao" => array("required" => "gerar_visita_vazio"),
                "banco" => true,
                "banco_string" => true,
                "select_hidden" => true
            ),
            array (
                'id' => 'geolocalizacao_endereco',
                'nome' => 'geolocalizacao_endereco',
                'nomeidioma' => 'form_endereco_rua',
                'tipo' => 'hidden', // input
                'valor' => 'return $dados["geolocalizacao_endereco"];',
                'banco_string' => true,
                "class" => "span6",
                // 'evento' => 'readonly',
                'banco' => true,
                'banco_string' => true
            ),
            array (
                'id' => 'geolocalizacao_cep',
                'nome' => 'geolocalizacao_cep',
                'nomeidioma' => 'form_endereco_cep',
                'tipo' => 'hidden', // input
                'valor' => 'return $dados["geolocalizacao_cep"];',
                'banco_string' => true,
                // 'evento' => 'readonly',
                'banco' => true,
                'banco_string' => true
            ),
            array (
                'id' => 'geolocalizacao_cidade',
                'nome' => 'geolocalizacao_cidade',
                'nomeidioma' => 'form_endereco_cidade',
                'tipo' => 'hidden', // input
                'valor' => 'return $dados["geolocalizacao_cidade"];',
                'banco_string' => true,
                // 'evento' => 'readonly',
                'banco' => true,
                'banco_string' => true
            ),
            array (
                'id' => 'geolocalizacao_estado',
                'nome' => 'geolocalizacao_estado',
                'nomeidioma' => 'form_endereco_estado',
                'tipo' => 'hidden', // input
                'valor' => 'return $dados["geolocalizacao_estado"];',
                'banco_string' => true,
                // 'evento' => 'readonly',
                'banco' => true,
                'banco_string' => true
            ),
            array (
                'id' => 'geolocation',
                'nome' => 'geolocation',
                'nomeidioma' => 'geolocation',
                'tipo' => 'hidden',
                'valor' => 'geolocation',
                'banco' => true
            )
        )
    )
);
?>
