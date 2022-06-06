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
                "evento" => "onMouseOut=letrasMaiusculas(this), onfocusout=letrasMaiusculas(this)",
                "banco"        => true,
                "banco_string" => true,
                'banco_php' => 'return mb_strtoupper("%s")'
            ),
            array(
                "id"           => "sexo",
                "nome"         => "sexo",
                "nomeidioma"   => "form_sexo",
                "tipo"         => "select",
                "array"        => "sexo", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "sexo",
                "validacao"    => array("required" => "sexo_vazio"),
                "banco"        => true,
                "banco_string" => true
            ),
            array(
                "id"           => "estado_civil",
                "nome"         => "estado_civil",
                "nomeidioma"   => "form_estadocivil",
                "tipo"         => "select",
                "array"        => "estadocivil", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "estado_civil",
                "banco"        => true,
                "banco_string" => true
            ),
            array(
                "id"           => "data_nasc",
                "nome"         => "data_nasc",
                "nomeidioma"   => "form_nascimento",
                "tipo"         => "input",
                "valor"        => "data_nasc",
                "valor_php"    => 'if($dados["data_nasc"] && $dados["data_nasc"] != "0000-00-00") return formataData("%s", "br", 0)',
                "class"        => "span2",
                "mascara"      => "99/99/9999",
                "validacao" => array ("required" => "form_data_nascimento_vazio"),
                "banco"        => true,
                "banco_php"    => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            /*array(
                "id"           => "form_idpais",
                "nome"         => "idpais",
                "nomeidioma"   => "form_nacionalidade",
                "tipo"         => "select",
                "valor"        => "idpais",
                "class"        => "invisivel",
                "banco"        => true,
                "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),*/
            array(
                "id"           => "form_nacionalidade",
                "nome"         => "nacionalidade",
                "nomeidioma"   => "form_nacionalidade",
                "tipo"         => "input",
                "valor"        => "nacionalidade",
                //"validacao"    => array("required" => "nacionalidade_vazio"),
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "form_naturalidade",
                "nome"         => "naturalidade",
                "nomeidioma"   => "form_naturalidade",
                "tipo"         => "input",
                "valor"        => "naturalidade",
                "ajudaidioma"  => "form_naturalidade_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
                "class"        => "span3",
                "evento"       => "maxlength='100'",
                "banco"        => true,
                "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
        )
    ),
    array(
        "fieldsetid"    => "dados_documentos", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_dados_documentos", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"           => "form_tipo",
                "nome"         => "documento_tipo",
                "nomeidioma"   => "form_tipo",
                "botao_hide"   => true,
                "iddivs"       => array("documento", "documento_cnpj","rg","rg_orgao_emissor","rg_data_emissao","rne","razao_social","nome_fantasia","representante"),
                "evento"       => "disabled='disabled'",
                "tipo"         => "select",
                "iddiv"        => "documento",
                "iddiv2"       => "documento_cnpj",
                "iddiv3"       => "rg",
                "iddiv4"       => "rg_orgao_emissor",
                "iddiv5"       => "rg_data_emissao",
                "iddiv6"       => "rne",
                "iddiv7"       => "razao_social",
                "iddiv8"       => "nome_fantasia",
                "iddiv9"       => "representante",
                "array"        => "tipo_documento", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "documento_tipo",
                "validacao"    => array("required" => "tipo_vazio"),
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "form_documento",
                "nome"         => "documento",
                "nomeidioma"   => "form_cpf",
                "tipo"         => "input",
                "valor"        => "documento",
                "class"        => "span3",
                "ajudaidioma"  => "form_cpf_ajuda",
                "evento"       => "disabled='disabled' maxlength='14'",
                "validacao"    => array("required" => "cpf_vazio", "valida_cpf" => "cpf_invalido"),
                "mascara"      => "999.999.999-99",
                "banco"        => true,
                "banco_php"    => 'return str_replace(array(".", "-","/"),"","%s")',
                "banco_string" => true,
                "input_hidden" => true,
            ),
            array(
                "id"           => "form_documento_cnpj",
                "nome"         => "documento_cnpj",
                "nomeidioma"   => "form_cnpj",
                "tipo"         => "input",
                "valor"        => "documento",
                "class"        => "span3",
                "ajudaidioma"  => "form_cnpj_ajuda",
                "evento"       => "disabled='disabled' maxlength='18'",
                "validacao"    => array("required" => "cnpj_vazio", "valida_cnpj" => "cnpj_invalido"),
                "mascara"      => "99.999.999/9999-99",
                "banco"        => true,
                "banco_php"    => 'return str_replace(array(".", "-", "/"),"","%s")',
                "banco_string" => true,
                "input_hidden" => true
            ),
            array(
                "id"           => "form_rg",
                "nome"         => "rg",
                "nomeidioma"   => "form_rg",
                "tipo"         => "input",
                "valor"        => "rg",
                //"validacao"    => array("required" => "rg_vazio"),
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
                "id"           => "form_cnh",
                "nome"         => "cnh",
                "nomeidioma"   => "form_cnh",
                "tipo"         => "input",
                "valor"        => "cnh",
                "validacao" => array("required" => "cnh_vazio"),
                "class"        => "span2",
                "evento"       => "maxlength='30'",
                //"numerico" => true,
                "banco"        => true,
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
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
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
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
        )
    ),
    array(
        "fieldsetid"    => "dados_filiacao", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_dados_filiacao", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"           => "form_filiacao_mae", // Id do atributo HTML
                "nome"         => "filiacao_mae", // Name do atributo HTML
                "nomeidioma"   => "form_filiacao_mae", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='100'",
                "valor"        => "filiacao_mae", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "filiacao_mae_vazio"), // Validação do campo
                "class"        => "span6", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_filiacao_pai", // Id do atributo HTML
                "nome"         => "filiacao_pai", // Name do atributo HTML
                "nomeidioma"   => "form_filiacao_pai", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='100'",
                "valor"        => "filiacao_pai", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "class"        => "span6", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
        )
    ),
    array(
        "fieldsetid"    => "dados_endereco", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_dados_endereco", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"           => "form_cep",
                "nome"         => "cep",
                "nomeidioma"   => "form_cep",
                "tipo"         => "input",
                "valor"        => "cep",
                "validacao" => array("required" => "cep_vazio"),
                "class"        => "span2",
                "ajudaidioma"  => "form_cep_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
                "mascara"      => "99999-999", //Mascara do campo
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_php"    => 'return str_replace(array("-", ""),"","%s")', // Executa um script php antes de salvar no banco (Utilizado na função SalvarDados)
                "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
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
                "banco"      => true
            ),
            array(
                "id"           => "form_endereco", // Id do atributo HTML
                "nome"         => "endereco", // Name do atributo HTML
                "nomeidioma"   => "form_endereco", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='100'",
                "valor"        => "endereco", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao" => array("required" => "endereco_vazio"), // Validação do campo
                "class"        => "span6", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_bairro", // Id do atributo HTML
                "nome"         => "bairro", // Name do atributo HTML
                "nomeidioma"   => "form_bairro", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='100'",
                "valor"        => "bairro", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao" => array("required" => "bairro_vazio"), // Validação do campo
                "class"        => "span5", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_numero", // Id do atributo HTML
                "nome"         => "numero", // Name do atributo HTML
                "nomeidioma"   => "form_numero", // Referencia a variavel de idioma
                "ajudaidioma"  => "form_numero_ajuda",
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='10'",
                "valor"        => "numero", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao" => array("required" => "numero_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
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
                "id"                   => "idcidade",
                "nome"                 => "idcidade",
                "nomeidioma"           => "form_idcidade",
                "json"                 => true,
                "json_idpai"           => "idestado",
                "json_url"             => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . '/' . $url["3"] . "/ajax_cidades/",
                "json_input_pai_vazio" => "form_selecione_estado",
                "json_input_vazio"     => "form_selecione_cidade",
                "json_campo_exibir"    => "nome",
                "tipo"                 => "select",
                "valor"                => "idcidade",
                "sql_valor"            => "idcidade",
                "validacao" => array("required" => "form_selecione_cidade"),
                "banco"                => true
            ),
        )
    ),
    array(
        "fieldsetid"    => "dados_contato", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_dados_contato", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"           => "form_email",
                "nome"         => "email",
                "nomeidioma"   => "form_email",
                "tipo"         => "input",
                "valor"        => "email",
                "ajudaidioma"  => "form_email_ajuda",
                "validacao"    => array("required" => "email_vazio", "valid_email" => "email_invalido"),
                "class"        => "span5",
                "legenda"      => "@",
                "banco"        => true,
                "banco_string" => true,
                "evento"       => "maxlength='100'"
            ),
            array(
                "id"           => "form_telefone",
                "nome"         => "telefone",
                "nomeidioma"   => "form_telefone",
                "tipo"         => "input",
                "valor"        => "telefone",
                //"validacao" => array("required" => "telefone_vazio"),
                "class"        => "span2",
                "mascara"      => "(99) 9999-9999",
                "banco"        => true,
                "banco_string" => true
            ),
            array(
                "id"           => "form_celular",
                "nome"         => "celular",
                "nomeidioma"   => "form_celular",
                "tipo"         => "input",
                "valor"        => "celular",
                "validacao" => array("required" => "celular_vazio"),
                "class"        => "span2",
                "mascara"      => "(99) 9999-9999",
                "banco"        => true,
                "banco_string" => true
            ),
            array(
                'id' => 'form_receber_email',
                'nome' => 'receber_email',
                'nomeidioma' => 'form_receber_email',
                'validacao' => array('required' => "receber_email_vazio"),
                'tipo' => 'select',
                'array' => 'sim_nao',
                'class' => 'span2',
                'valor' => 'receber_email',
                'banco' => true,
                'banco_string' => true
            ),
        )
    ),
    array(
        "fieldsetid"    => "dadosdeacesso",
        "legendaidioma" => "legendadadosdeacesso",
        "campos"        => array(
            array(
                "id"             => "form_senha",
                "nome"           => "senha",
                "nomeidioma"     => "form_senha",
                "tipo"           => "input",
                "senha"          => true,
                "ajudaidioma"    => "form_senha_ajuda",
                "class"          => "span3 verificaSenha",
                "validacao"      => array("length>=8" => "minimo_senha", "length<=30" => "maximo_senha"),
                "legenda"        => "#", // Adiciona uma legenda ao campo no formulario
                "ignorarsevazio" => true, // ignora o campo se ele for uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco"          => true,
                "banco_php"      => 'return senhaSegura("%s","' . $config["chaveLogin"] . '")',
                "banco_string"   => true,
                "evento"         => "maxlength='30'"
            ),
            array(
                "id"          => "form_confirma",
                "nome"        => "confirma",
                "nomeidioma"  => "form_confirma",
                "tipo"        => "input",
                "senha"       => true, // Informa que o campo é uma senha (password)
                "ajudaidioma" => "form_confirma_ajuda",
                "validacao"   => array("same_as,senha" => "confirmacao_invalida"),
                "class"       => "span4",
                "evento"      => "maxlength='30'"
            )
        )
    ),
    array("fieldsetid"    => "dados_bancarios", // Titulo do formulario (referencia a variavel de idioma)
          "legendaidioma" => "legendadadosbancarios", // Legenda do fomrulario (referencia a variavel de idioma)

          "campos"        => array( // Campos do formulario
              array(
                  "id"           => "banco_nome", // Id do atributo HTML
                  "nome"         => "banco_nome", // Name do atributo HTML
                  "nomeidioma"   => "form_banco", // Referencia a variavel de idioma
                  "tipo"         => "input", // Tipo do input
                  "evento"       => "maxlength='40'",
                  "valor"        => "banco_nome", // Nome da coluna da tabela do banco de dados que retorna o valor.
                  "class"        => "span3", //Class do atributo HTML
                  "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                  "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
              ),
              array(
                  "id"           => "banco_agencia", // Id do atributo HTML
                  "nome"         => "banco_agencia", // Name do atributo HTML
                  "nomeidioma"   => "form_agencia", // Referencia a variavel de idioma
                  "tipo"         => "input", // Tipo do input
                  "evento"       => "maxlength='40'",
                  "valor"        => "banco_agencia", // Nome da coluna da tabela do banco de dados que retorna o valor.
                  "class"        => "span2", //Class do atributo HTML
                  "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                  "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
              ),
              array(
                  "id"           => "banco_conta", // Id do atributo HTML
                  "nome"         => "banco_conta", // Name do atributo HTML
                  "nomeidioma"   => "form_contacorrente", // Referencia a variavel de idioma
                  "tipo"         => "input", // Tipo do input
                  "evento"       => "maxlength='40'",
                  "ajudaidioma"  => "form_contacorrente_ajuda",
                  "valor"        => "banco_conta", // Nome da coluna da tabela do banco de dados que retorna o valor.
                  "class"        => "span2", //Class do atributo HTML
                  "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                  "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
              ),
              array(
                  "id"           => "banco_nome_titular",
                  "nome"         => "banco_nome_titular",
                  "nomeidioma"   => "form_banco_nome_titular",
                  "tipo"         => "input",
                  "valor"        => "banco_nome_titular",
                  //"validacao" => array("required" => "nome_vazio"),
                  "class"        => "span5",
                  "banco"        => true,
                  "banco_string" => true,
                  "evento"       => "maxlength='100'"
              ),
              array(
                  "id"           => "banco_cpf_titular",
                  "nome"         => "banco_cpf_titular",
                  "nomeidioma"   => "form_banco_cpf_titular",
                  "tipo"         => "input",
                  "valor"        => "banco_cpf_titular",
                  "class"        => "span2",
                  "evento"       => "maxlength='11'",
                  "validacao"    => array("valida_cpf" => "cpf_titular_invalido"),
                  "mascara"      => "999.999.999-99",
                  "banco"        => true,
                  "banco_php"    => 'return str_replace(array(".", "-","/"),"","%s")',
                  "banco_string" => true
              ),
              array(
                  "id"           => "banco_observacoes",
                  "nome"         => "banco_observacoes",
                  "nomeidioma"   => "form_banco_observacoes",
                  "tipo"         => "input",
                  "valor"        => "banco_observacoes",
                  "banco"        => true,
                  "banco_string" => true
              )
          )
    ),
    array(
        "fieldsetid"    => "dados_outros", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_dados_outros", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"           => "renda_familiar", // Id do atributo HTML
                "nome"         => "renda_familiar", // Name do atributo HTML
                "nomeidioma"   => "form_rendafamiliar", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "evento"       => "maxlength='12'",
                "decimal"      => true,
                "valor"        => "renda_familiar", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "rendafamiliar_vazio"), // Validação do campo
                "class"        => "span2", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "profissao", // Id do atributo HTML
                "nome"         => "profissao", // Name do atributo HTML
                "nomeidioma"   => "form_profissao", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "valor"        => "profissao", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "class"        => "span4", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "facebook", // Id do atributo HTML
                "nome"         => "facebook", // Name do atributo HTML
                "nomeidioma"   => "form_facebook", // Referencia a variavel de idioma
                "tipo"         => "input", // Tipo do input
                "valor"        => "facebook", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "facebook_vazio"), // Validação do campo
                "class"        => "span4", //Class do atributo HTML
                "banco"        => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id"           => "form_disponivel_interacao",
                "nome"         => "disponivel_interacao",
                "nomeidioma"   => "form_disponivel_interacao",
                "tipo"         => "select",
                "array"        => "sim_nao", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "disponivel_interacao",
                "validacao"    => array("required" => "disponivel_interacao_vazio"),
                "ajudaidioma"  => "form_disponivel_interacao_ajuda",
                "banco"        => true,
                "banco_string" => true
            ),

            array(
                "id"           => "form_observacoes",
                "nome"         => "observacoes",
                "nomeidioma"   => "form_observacoes",
                "tipo"         => "text",
                "valor"        => "observacoes",
                "class"        => "xxlarge",
                "banco"        => true,
                "banco_string" => true
            )
        )
    ),
    array(
        "fieldsetid"    => "foto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_foto", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"               => "form_foto", // Id do atributo HTML
                "nome"             => "avatar", // Name do atributo HTML
                "nomeidioma"       => "form_foto", // Referencia a variavel de idioma
                "arquivoidioma"    => "arquivo_enviado", // Referencia a variavel de idioma
                "arquivoexcluir"   => "arquivo_excluir", // Referencia a variavel de idioma
                "tipo"             => "file", // Tipo do input
                "extensoes"        => 'jpg|jpeg|gif|png|bmp',
                "ajudaidioma"      => "form_foto_ajuda",
                //"largura" => 350,
                //"altura" => 180,
                "validacao"        => array("formato_arquivo" => "arquivo_invalido"),
                "class"            => "span6", //Class do atributo HTML
                "pasta"            => "pessoas_avatar",
                "download"         => true,
                "download_caminho" => $url["0"] . "/" . $url["1"] . "/" . $url["2"] . "/" . $url["3"],
                "excluir"          => true,
                "banco"            => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_campo"      => "avatar", // Nome das colunas da tabela do banco de dados que retorna o valor.
                "ignorarsevazio"   => true
            ),
        )
    ),
    array(
        "fieldsetid"    => "curso_anterior", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_curso_anterior", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos"        => array( // Campos do formulario
            array(
                "id"           => "form_escolaridade",
                "nome"         => "escolaridade",
                "nomeidioma"   => "form_escolaridade",
                "tipo"         => "select",
                "array"        => "escolaridade", // Array que alimenta o select
                "class"        => "span2",
                "valor"        => "escolaridade",
                "banco"        => true,
                "banco_string" => true
            ),
            array(
                "id"           => "form_curso_anterior_nome",
                "nome"         => "curso_anterior_nome",
                "nomeidioma"   => "form_curso_anterior_nome",
                "tipo"         => "input",
                "valor"        => "curso_anterior_nome",
                "evento"       => "maxlength='100'",
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "form_curso_anterior",
                "nome"         => "curso_anterior",
                "nomeidioma"   => "form_curso_anterior",
                "tipo"         => "input",
                "valor"        => "curso_anterior",
                "evento"       => "maxlength='100'",
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
            array(
                "id"           => "form_curso_anterior_sindicato",
                "nome"         => "curso_anterior_sindicato",
                "nomeidioma"   => "form_curso_anterior_sindicato",
                "tipo"         => "input",
                "valor"        => "curso_anterior_sindicato",
                "evento"       => "maxlength='100'",
                "class"        => "span6",
                "banco"        => true,
                "banco_string" => true,
            ),
            /*array(
                "id"         => "curso_anterior_idestado",
                "nome"       => "curso_anterior_idestado",
                "nomeidioma" => "form_curso_anterior_idestado",
                "tipo"       => "select",
                "sql"        => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
                "sql_valor"  => "idestado", // Coluna da tabela que será usado como o valor do options
                "sql_label"  => "nome", // Coluna da tabela que será usado como o label do options
                "valor"      => "curso_anterior_idestado",
                //"validacao" => array("required" => "estado_vazio"),
                "banco"      => true
            ),
            array(
                "id"                   => "curso_anterior_idcidade",
                "nome"                 => "curso_anterior_idcidade",
                "nomeidioma"           => "form_curso_anterior_idcidade",
                "json"                 => true,
                "json_idpai"           => "curso_anterior_idestado",
                "json_url"             => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . '/' . $url["3"] . "/ajax_cidades_curso_anterior/",
                "json_input_pai_vazio" => "form_selecione_curso_anterior_idestado",
                "json_input_vazio"     => "form_selecione_curso_anterior_idcidade",
                "json_campo_exibir"    => "nome",
                "tipo"                 => "select",
                "valor"                => "curso_anterior_idcidade",
                "sql_valor"            => "idcidade",
                //"validacao" => array("required" => "cidade_vazio"),
                "banco"                => true
            ),*/

            //\/ campo "País"
            /*array(
                "id"           => "curso_anterior_estado",
                "nome"         => "curso_anterior_estado",
                "nomeidioma"   => "form_curso_anterior_idestado",
                "tipo"         => "input",
                "valor"        => "curso_anterior_estado",
                //"validacao"    => array("required" => "curso_anterior_estado_vazio"),
                "class"        => "span4",
                "banco"        => true,
                "banco_string" => true,
            ),*/

                // \/ campo "Cidade"
                array(
                        "id"           => "curso_anterior_cidade",
                        "nome"         => "curso_anterior_cidade",
                        "nomeidioma"   => "form_curso_anterior_idcidade",
                        "tipo"         => "input",
                        "valor"        => "curso_anterior_cidade",
                        //"validacao"    => array("required" => "curso_anterior_cidade_vazio"),
                        "class"        => "span4",
                        "banco"        => true,
                        "banco_string" => true,
                ),

                // \/ campo "Estado"
                array(
                        "id"           => "curso_anterior_estado",
                        "nome"         => "curso_anterior_estado",
                        "nomeidioma"   => "form_curso_anterior_idestado",
                        "tipo"         => "input",
                        "valor"        => "curso_anterior_estado",
                        //"validacao"    => array("required" => "curso_anterior_cidade_vazio"),
                        "class"        => "span4",
                        "banco"        => true,
                        "banco_string" => true,
                ),

                // campo "País"
                array(
                        "id"           => "curso_anterior_pais",
                        "nome"         => "curso_anterior_pais",
                        "nomeidioma"   => "form_curso_anterior_pais",
                        "tipo"         => "input",
                        "valor"        => "curso_anterior_pais",
                        //"validacao"    => array("required" => "curso_anterior_cidade_vazio"),
                        "class"        => "span4",
                        "banco"        => true,
                        "banco_string" => true,
                ),

                //\/ campo "Carga Horária"
                array(
                        "id"           => "curso_anterior_carga_horaria",
                        "nome"         => "curso_anterior_carga_horaria",
                        "nomeidioma"   => "form_curso_anterior_carga_horaria",
                        "tipo"         => "input",
                        "valor"        => "curso_anterior_carga_horaria",
                        //"validacao"    => array("required" => "curso_anterior_estado_vazio"),
                        "class"        => "span4",
                        "banco"        => true,
                        "banco_string" => true,
                ),

            array(
                "id"           => "form_curso_anterior_ano_conclusao",
                "nome"         => "curso_anterior_ano_conclusao",
                "nomeidioma"   => "form_curso_anterior_ano_conclusao",
                "tipo"         => "input",
                "valor"        => "curso_anterior_ano_conclusao",
                //"validacao" => array("required" => "rg_vazio"),
                "mascara"      => "9999",
                "class"        => "span2",
                "evento"       => "maxlength='4'",
                "numerico"     => true,
                "banco"        => true,
                "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
        )
    )
);
