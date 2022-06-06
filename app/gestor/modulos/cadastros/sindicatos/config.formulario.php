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
                "id" => "form_nome_abreviado",
                "nome" => "nome_abreviado",
                "nomeidioma" => "form_nome_abreviado",
                "tipo" => "input",
                "valor" => "nome_abreviado",
                "validacao" => array("required" => "nome_abreviado_vazio"),
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
            ),

            array(
                "id" => "idestado_competencia",
                "nome" => "idestado_competencia",
                "nomeidioma" => "form_idestado_competencia",
                "tipo" => "select",
                "sql" => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idestado", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idestado_competencia",
                "validacao" => array("required" => "idestado_competencia_vazio"),
                "banco" => true
            ),

            //nome_abreviado
            array(
                "id" => "form_documento_cnpj",
                "nome" => "documento",
                "nomeidioma" => "form_cnpj",
                "tipo" => "input",
                "valor" => "documento",
                "class" => "span3",
                "ajudaidioma" => "form_cnpj_ajuda",
                "evento" => " maxlength='14'", //readonly='readonly'
                "validacao" => array("required" => "cnpj_vazio", "valida_cnpj" => "cnpj_invalido"),
                "mascara" => "99.999.999/9999-99",
                "banco" => true,
                "banco_php" => 'return str_replace(array(".", "-", "/"),"","%s")',
                "banco_string" => true,
            ),
            array(
                "id" => "idmantenedora",
                "nome" => "idmantenedora",
                "nomeidioma" => "form_mantenedora",
                "tipo" => "select",
                "sql" => "SELECT idmantenedora, nome_fantasia FROM mantenedoras WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome_fantasia ", // SQL que alimenta o select
                "sql_valor" => "idmantenedora", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome_fantasia", // Coluna da tabela que será usado como o label do options
                "valor" => "idmantenedora",
                "validacao" => array("required" => "mantenedora_vazio"),
                "referencia_label" => "cadastro_mantenedoras",
                "referencia_link" => "/gestor/cadastros/mantenedoras",
                "banco" => true
            ),
            array(
                "id" => "form_fax",
                "nome" => "fax",
                "nomeidioma" => "form_fax",
                "tipo" => "input",
                "valor" => "fax",
                "class" => "span3",
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
                //"validacao" => array("valid_email" => "email_invalido"),
                "class" => "span5",
                "legenda" => "@",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='100'"
            ),
            array(
                "id" => "form_site", // Id do atributo HTML
                "nome" => "site", // Name do atributo HTML
                "nomeidioma" => "form_site", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "evento" => "maxlength='50'",
                "valor" => "site", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "nre_vazio"), // Validação do campo
                "class" => "span2", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id" => "form_nre", // Id do atributo HTML
                "nome" => "nre", // Name do atributo HTML
                "nomeidioma" => "form_nre", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "evento" => "maxlength='10'",
                "valor" => "nre", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "nre_vazio"), // Validação do campo
                "class" => "span2", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id" => "form_telefone",
                "nome" => "telefone",
                "nomeidioma" => "form_telefone",
                "tipo" => "input",
                "valor" => "telefone",
                "class" => "span3",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_cep",
                "nome" => "cep",
                "nomeidioma" => "form_cep",
                "tipo" => "input",
                "valor" => "cep",
                //"validacao" => array("required" => "cep_vazio"),
                "class" => "span2",
                "ajudaidioma" => "form_cep_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
                "mascara" => "99999-999", //Mascara do campo
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_php" => "return str_replace(array(\"-\"),\"\",\"%s\")", // Executa um script php antes de salvar no banco (Utilizado na função SalvarDados)
                "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id" => "idlogradouro",
                "nome" => "idlogradouro",
                "nomeidioma" => "form_logradouro",
                "tipo" => "select",
                "sql" => "SELECT idlogradouro, nome FROM logradouros WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome ", // SQL que alimenta o select
                "sql_valor" => "idlogradouro", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idlogradouro",
                //"validacao" => array("required" => "logradouro_vazio"),
                "banco" => true
            ),
            array(
                "id" => "form_endereco", // Id do atributo HTML
                "nome" => "endereco", // Name do atributo HTML
                "nomeidioma" => "form_endereco", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "evento" => "maxlength='100'",
                "valor" => "endereco", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "endereco_vazio"), // Validação do campo
                "class" => "span6", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id" => "form_bairro", // Id do atributo HTML
                "nome" => "bairro", // Name do atributo HTML
                "nomeidioma" => "form_bairro", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "evento" => "maxlength='100'",
                "valor" => "bairro", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "bairro_vazio"), // Validação do campo
                "class" => "span5", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id" => "form_numero", // Id do atributo HTML
                "nome" => "numero", // Name do atributo HTML
                "nomeidioma" => "form_numero", // Referencia a variavel de idioma
                "ajudaidioma" => "form_numero_ajuda",
                "tipo" => "input", // Tipo do input
                "evento" => "maxlength='10'",
                "valor" => "numero", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "numero_vazio"), // Validação do campo
                "class" => "span2", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id" => "form_complemento", // Id do atributo HTML
                "nome" => "complemento", // Name do atributo HTML
                "nomeidioma" => "form_complemento", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "evento" => "maxlength='100'",
                "valor" => "complemento", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "complemento_vazio"), // Validação do campo
                "class" => "span4", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id" => "idestado",
                "nome" => "idestado",
                "nomeidioma" => "form_idestado",
                "tipo" => "select",
                "sql" => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
                "sql_valor" => "idestado", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idestado",
                //"validacao" => array("required" => "estado_vazio"),
                "banco" => true
            ),
            array(
                "id" => "idcidade",
                "nome" => "idcidade",
                "nomeidioma" => "form_idcidade",
                "json" => true,
                "json_idpai" => "idestado",
                "json_url" => "/".$url["0"]."/".$url["1"]."/".$url["2"]."/".$url["3"]."/ajax_cidades/",
                "json_input_pai_vazio" => "form_selecione_estado",
                "json_input_vazio" => "form_selecione_cidade",
                "json_campo_exibir" => "nome",
                "tipo" => "select",
                "valor" => "idcidade",
                //"validacao" => array("required" => "cidade_vazio"),
                "banco" => true
            ),
            array(
                "id" => "form_upload", // Id do atributo HTML
                "nome" => "logo", // Name do atributo HTML
                "nomeidioma" => "form_upload", // Referencia a variavel de idioma
                "arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
                "arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
                "tipo" => "file", // Tipo do input
                "extensoes" => "jpg|jpeg|gif|png|bmp",
                /*"largura" => 350,
                "altura" => 180,*/
                "diminuir_largura" => 350,
                "validacao" => array("formato_arquivo" => "arquivo_invalido"),
                "class" => "span6", //Class do atributo HTML
                "pasta" => "sindicatos_logo",
                "download" => true,
                "excluir" => true,
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_campo" => "logo", // Nome das colunas da tabela do banco de dados que retorna o valor.
                "ignorarsevazio" => true
            ),
            array(
                "id" => "form_brasao", // Id do atributo HTML
                "nome" => "brasao", // Name do atributo HTML
                "nomeidioma" => "form_brasao", // Referencia a variavel de idioma
                "arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
                "arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
                "tipo" => "file", // Tipo do input
                "extensoes" => "jpg|jpeg|gif|png|bmp",
                /*"largura" => 350,
                "altura" => 180,*/
                "diminuir_largura" => 350,
                "validacao" => array("formato_arquivo" => "arquivo_invalido"),
                "class" => "span6", //Class do atributo HTML
                "pasta" => "sindicatos_brasao",
                "download" => true,
                "excluir" => true,
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_campo" => "brasao", // Nome das colunas da tabela do banco de dados que retorna o valor.
                "ignorarsevazio" => true
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
                "id" => "form_ativ_federal",
                "nome" => "ativ_federal",
                "nomeidioma" => "form_ativ_federal",
                "tipo" => "input",
                "array" => "ativo", // Array que alimenta o select
                "class" => "span3",
                "valor" => "ativ_federal",
                //"validacao" => array("required" => "ativ_federal_vazio"),
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_descricao",
                "nome" => "descricao",
                "nomeidioma" => "form_descricao",
                "tipo" => "text",
                "valor" => "descricao",
                "class" => "span4",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "validade_mandato",
                "nome" => "validade_mandato",
                "nomeidioma" => "form_validade_mandato",
                "tipo" => "input",
                "valor" => "validade_mandato",
                "valor_php" => 'if($dados["validade_mandato"]) return formataData("%s", "br", 0)',
                "class" => "span2",
                "mascara" => "99/99/9999",
                "datepicker" => true,
                "banco" => true,
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            array(
                "id" => "usar_datavalid",
                "nome" => "usar_datavalid",
                "nomeidioma" => "form_validar_datavalid",
                "tipo" => "select",
                "array" => "sim_nao",
                "class" => "span2",
                "valor" => "usar_datavalid",
                "validacao" => array("required" => "required_datavalid"),
                "banco" => true,
                "banco_string" => true
            ),
        )
    ),
    array(
        "fieldsetid" => "financeiro", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendada_financeiro", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario
            array(
                "id" => "form_max_parcelas",
                "nome" => "max_parcelas",
                "nomeidioma" => "form_max_parcelas",
                "tipo" => "input",
                "class" => "span2",
                "evento" => "maxlength='10'",
                "valor" => "max_parcelas",
                "banco" => true,
                "banco_string" => true,
                "ajudaidioma" => "form_max_parcelas_ajuda",
                "numerico" => true,
            ),
            array(
                "id" => "form_max_boletos",
                "nome" => "max_boletos",
                "nomeidioma" => "form_max_boletos",
                "tipo" => "input",
                "class" => "span2",
                "evento" => "maxlength='10'",
                "valor" => "max_boletos",
                "banco" => true,
                "banco_string" => true,
                "ajudaidioma" => "form_max_boletos_ajuda",
                "numerico" => true,
            ),
        )
    ),
    array(
        "fieldsetid" => "dados_gerente", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendada_dados_gerente", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario
            array(
                "id" => "form_gerente_nome",
                "nome" => "gerente_nome",
                "nomeidioma" => "form_gerente_nome",
                "tipo" => "input",
                "evento" => "maxlength='100'",
                "valor" => "gerente_nome",
                "class" => "span6",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_gerente_cpf",
                "nome" => "gerente_cpf",
                "nomeidioma" => "form_gerente_cpf",
                "tipo" => "input",
                "mascara" => "999.999.999-99",
                "banco_php" => 'return str_replace(array(".", "-", "/"),"","%s")',
                "validacao" => array("valida_cpf" => "cpf_invalido"),
                "valor" => "gerente_cpf",
                "class" => "span3",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_gerente_data_nasc",
                "nome" => "gerente_data_nasc",
                "nomeidioma" => "form_gerente_data_nasc",
                "tipo" => "input",
                "mascara" => "99/99/9999",
                "banco_php" => 'return formataData("%s", "en", 0)',
                "valor_php" => 'if($dados["gerente_data_nasc"]) return formataData("%s", "br", 0)',
                "datepicker" => true,
                "valor" => "gerente_data_nasc",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_gerente_email",
                "nome" => "gerente_email",
                "nomeidioma" => "form_gerente_email",
                "tipo" => "input",
                "evento" => "maxlength='100'",
                "valor" => "gerente_email",
                "validacao" => array("valid_email" => "email_invalido"),
                "class" => "span5",
                "legenda" => "@",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_gerente_telefone",
                "nome" => "gerente_telefone",
                "nomeidioma" => "form_gerente_telefone",
                "tipo" => "input",
                "valor" => "gerente_telefone",
                "class" => "span3",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_gerente_celular",
                "nome" => "gerente_celular",
                "nomeidioma" => "form_gerente_celular",
                "tipo" => "input",
                "valor" => "gerente_celular",
                "class" => "span3",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_gerente_skype",
                "nome" => "gerente_skype",
                "nomeidioma" => "form_gerente_skype",
                "tipo" => "input",
                "valor" => "gerente_skype",
                "class" => "span4",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='100'"
            ),
        )
    ),
    array(
        "fieldsetid" => "dados_presidente_sind", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendada_dados_presidente_sind", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario
            array(
                "id" => "form_presidente_sind_nome",
                "nome" => "presidente_sind_nome",
                "nomeidioma" => "form_presidente_sind_nome",
                "tipo" => "input",
                "evento" => "maxlength='100'",
                "valor" => "presidente_sind_nome",
                "class" => "span6",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_presidente_sind_cpf",
                "nome" => "presidente_sind_cpf",
                "nomeidioma" => "form_presidente_sind_cpf",
                "tipo" => "input",
                "mascara" => "999.999.999-99",
                "banco_php" => 'return str_replace(array(".", "-", "/"),"","%s")',
                "validacao" => array("valida_cpf" => "cpf_invalido"),
                "valor" => "presidente_sind_cpf",
                "class" => "span3",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_presidente_sind_data_nasc",
                "nome" => "presidente_sind_data_nasc",
                "nomeidioma" => "form_presidente_sind_data_nasc",
                "tipo" => "input",
                "mascara" => "99/99/9999",
                "banco_php" => 'return formataData("%s", "en", 0)',
                "valor_php" => 'if($dados["presidente_sind_data_nasc"]) return formataData("%s", "br", 0)',
                "datepicker" => true,
                "valor" => "presidente_sind_data_nasc",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_presidente_sind_email",
                "nome" => "presidente_sind_email",
                "nomeidioma" => "form_presidente_sind_email",
                "tipo" => "input",
                "evento" => "maxlength='25'",
                "valor" => "presidente_sind_email",
                "validacao" => array("valid_email" => "email_invalido"),
                "class" => "span5",
                "legenda" => "@",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_presidente_sind_telefone",
                "nome" => "presidente_sind_telefone",
                "nomeidioma" => "form_presidente_sind_telefone",
                "tipo" => "input",
                "valor" => "presidente_sind_telefone",
                "class" => "span3",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_presidente_sind_celular",
                "nome" => "presidente_sind_celular",
                "nomeidioma" => "form_presidente_sind_celular",
                "tipo" => "input",
                "valor" => "presidente_sind_celular",
                "class" => "span3",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_presidente_sind_inicio_mandato",
                "nome" => "presidente_sind_inicio_mandato",
                "nomeidioma" => "form_presidente_sind_inicio_mandato",
                "tipo" => "input",
                "class" => "span2",
                "mascara" => "99/99/9999",
                "banco_php" => 'return formataData("%s", "en", 0)',
                "valor_php" => 'if($dados["presidente_sind_inicio_mandato"]) return formataData("%s", "br", 0)',
                "datepicker" => true,
                "valor" => "presidente_sind_inicio_mandato",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_presidente_sind_termino_mandato",
                "nome" => "presidente_sind_termino_mandato",
                "nomeidioma" => "form_presidente_sind_termino_mandato",
                "tipo" => "input",
                "class" => "span2",
                "mascara" => "99/99/9999",
                "banco_php" => 'return formataData("%s", "en", 0)',
                "valor_php" => 'if($dados["presidente_sind_termino_mandato"]) return formataData("%s", "br", 0)',
                "datepicker" => true,
                "valor" => "presidente_sind_termino_mandato",
                "banco" => true,
                "banco_string" => true,
            ),
        )
    ),
    array(
        "fieldsetid" => "dados_vice_presidente_sind", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendada_dados_vice_presidente_sind", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario
            array(
                "id" => "form_vice_presidente_sind_nome",
                "nome" => "vice_presidente_sind_nome",
                "nomeidioma" => "form_vice_presidente_sind_nome",
                "tipo" => "input",
                "evento" => "maxlength='100'",
                "valor" => "vice_presidente_sind_nome",
                "class" => "span6",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_vice_presidente_sind_cpf",
                "nome" => "vice_presidente_sind_cpf",
                "nomeidioma" => "form_vice_presidente_sind_cpf",
                "tipo" => "input",
                "mascara" => "999.999.999-99",
                "banco_php" => 'return str_replace(array(".", "-", "/"),"","%s")',
                "validacao" => array("valida_cpf" => "cpf_invalido"),
                "valor" => "vice_presidente_sind_cpf",
                "class" => "span3",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_vice_presidente_sind_data_nasc",
                "nome" => "vice_presidente_sind_data_nasc",
                "nomeidioma" => "form_vice_presidente_sind_data_nasc",
                "tipo" => "input",
                "mascara" => "99/99/9999",
                "banco_php" => 'return formataData("%s", "en", 0)',
                "valor_php" => 'if($dados["vice_presidente_sind_data_nasc"]) return formataData("%s", "br", 0)',
                "datepicker" => true,
                "valor" => "vice_presidente_sind_data_nasc",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_vice_presidente_sind_email",
                "nome" => "vice_presidente_sind_email",
                "nomeidioma" => "form_vice_presidente_sind_email",
                "tipo" => "input",
                "evento" => "maxlength='100'",
                "valor" => "vice_presidente_sind_email",
                "validacao" => array("valid_email" => "email_invalido"),
                "class" => "span5",
                "legenda" => "@",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_vice_presidente_sind_telefone",
                "nome" => "vice_presidente_sind_telefone",
                "nomeidioma" => "form_vice_presidente_sind_telefone",
                "tipo" => "input",
                "valor" => "vice_presidente_sind_telefone",
                "class" => "span3",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_vice_presidente_sind_celular",
                "nome" => "vice_presidente_sind_celular",
                "nomeidioma" => "form_vice_presidente_sind_celular",
                "tipo" => "input",
                "valor" => "vice_presidente_sind_celular",
                "class" => "span3",
                "mascara" => "(99) 9999-9999",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_vice_presidente_sind_inicio_mandato",
                "nome" => "vice_presidente_sind_inicio_mandato",
                "nomeidioma" => "form_vice_presidente_sind_inicio_mandato",
                "tipo" => "input",
                "class" => "span2",
                "mascara" => "99/99/9999",
                "banco_php" => 'return formataData("%s", "en", 0)',
                "valor_php" => 'if($dados["vice_presidente_sind_inicio_mandato"]) return formataData("%s", "br", 0)',
                "datepicker" => true,
                "valor" => "vice_presidente_sind_inicio_mandato",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_vice_presidente_sind_termino_mandato",
                "nome" => "vice_presidente_sind_termino_mandato",
                "nomeidioma" => "form_vice_presidente_sind_termino_mandato",
                "tipo" => "input",
                "class" => "span2",
                "mascara" => "99/99/9999",
                "banco_php" => 'return formataData("%s", "en", 0)',
                "valor_php" => 'if($dados["vice_presidente_sind_termino_mandato"]) return formataData("%s", "br", 0)',
                "datepicker" => true,
                "valor" => "vice_presidente_sind_termino_mandato",
                "banco" => true,
                "banco_string" => true,
            ),
        )
    ),
);

$config['formulario_valores_curso'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
            array(
                'id' => 'idsindicato',
                'nome' => 'idsindicato',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'idcurso',
                'nome' => 'idcurso',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true
            ),
/*            array(
                'id' => 'avista',
                'nome' => 'avista',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true,
                'decimal' => true
            ),
            array(
                'id' => 'aprazo',
                'nome' => 'aprazo',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true,
                'decimal' => true
            ),*/

            array(
                'id' => 'quantidade_matriculas',
                'nome' => 'quantidade_matriculas',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'valor_por_matricula',
                'nome' => 'valor_por_matricula',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true,
                'decimal' => true
            ),
            array(
                'id' => 'quantidade_matriculas_2',
                'nome' => 'quantidade_matriculas_2',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'valor_por_matricula_2',
                'nome' => 'valor_por_matricula_2',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true,
                'decimal' => true
            ),
            array(
                'id' => 'valor_excedente',
                'nome' => 'valor_excedente',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true,
                'decimal' => true
            ),
            array(
                'id' => 'parcelas',
                'nome' => 'parcelas',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'max_parcelas',
                'nome' => 'max_parcelas',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'quantidade_faturas_ciclo',
                'nome' => 'quantidade_faturas_ciclo',
                'tipo' => 'input',
                'banco' => true,
                'banco_string' => true
            ),
        ),
    ),
);
