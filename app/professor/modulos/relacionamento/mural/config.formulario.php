<?
            // Array de configuração para a formulario
            $config["formulario_pessoas"] = array(
                              array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
                                    "campos" => array( // Campos do formulario
                                                      array(
                                                            "id" => "documento",
                                                            "nome" => "documento",
                                                            "nomeidioma" => "form_cpf",
                                                            "tipo" => "hidden",
                                                            "valor" => 'if($this->post["cpf"]) return $this->post["cpf"]; else return $this->post["cnpj"];',
                                                            "validacao" => array("required" => "cpf_vazio"),
                                                            "banco" => true,
                                                            "banco_php" => 'return str_replace(array(".", "-"),"","%s")',
                                                            "banco_string" => true
                                                      ),
                                                      array(
                                                            "id" => "form_nome",
                                                            "nome" => "nome",
                                                            "nomeidioma" => "form_nome",
                                                            "tipo" => "input",
                                                            "valor" => "nome",
                                                            "validacao" => array("required" => "nome_vazio"),
                                                            "class" => "span5",
                                                            "banco" => true,
                                                            "banco_string" => true,
                                                            "evento" => "maxlength='100'"
                                                            ),
                                                      array(
                                                            "id" => "estado_civil",
                                                            "nome" => "estado_civil",
                                                            "nomeidioma" => "form_estadocivil",
                                                            "tipo" => "select",
                                                            "array" => "estadocivil", // Array que alimenta o select
                                                            "class" => "span2",
                                                            "valor" => "estado_civil",
                                                            "validacao" => array("required" => "estadocivil_vazio"),
                                                            "banco" => true,
                                                            "banco_string" => true
                                                            ),
                                                      array(
                                                            "id" => "data_nasc",
                                                            "nome" => "data_nasc",
                                                            "nomeidioma" => "form_nascimento",
                                                            "tipo" => "input",
                                                            "valor" => "data_nasc",
                                                            "validacao" => array("required" => "data_nasc_vazio"),
                                                            "valor_php" => 'if($dados["data_nasc"]) return formataData("%s", "br", 0)',
                                                            "class" => "span2",
                                                            "mascara" => "99/99/9999",
                                                            "datepicker" => true,
                                                            "banco" => true,
                                                            "banco_php" => 'return formataData("%s", "en", 0)',
                                                            "banco_string" => true
                                                            ),
                                                      array(
                                                            "id" => "form_idpais",
                                                            "nome" => "idpais",
                                                            "nomeidioma" => "form_nacionalidade",
                                                            "tipo" => "select",
                                                            "valor" => "idpais",
                                                            "validacao" => array("required" => "nacionalidade_vazio"),
                                                            "class" => "span3",
                                                            "banco" => true,
                                                            "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
                                                            "id" => "form_naturalidade",
                                                            "nome" => "naturalidade",
                                                            "nomeidioma" => "form_naturalidade",
                                                            "tipo" => "input",
                                                            "valor" => "naturalidade",
                                                            "validacao" => array("required" => "naturalidade_vazio"),
                                                            "class" => "span3",
                                                            "evento" => "maxlength='100'",
                                                            "banco" => true,
                                                            "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            )
                                                      )
                                    ),
                              array("fieldsetid" => "dados_documentos", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_dados_documentos", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array(
                                                      array(
                                                            "id" => "form_rg",
                                                            "nome" => "rg",
                                                            "nomeidioma" => "form_rg",
                                                            "tipo" => "input",
                                                            "valor" => "rg",
                                                            "validacao" => array("required" => "rg_vazio"),
                                                            "class" => "span2",
                                                            "evento" => "maxlength='20'",
                                                            "numerico" => true,
                                                            "banco" => true,
                                                            "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
                                                            "id" => "form_rg_orgao_emissor",
                                                            "nome" => "rg_orgao_emissor",
                                                            "nomeidioma" => "form_orgao_emissor",
                                                            "tipo" => "input",
                                                            "valor" => "rg_orgao_emissor",
                                                            "validacao" => array("required" => "orgao_emissor_vazio"),
                                                            "class" => "span2",
                                                            "evento" => "maxlength='20'",
                                                            "banco" => true,
                                                            "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
                                                            "id" => "rg_data_emissao",
                                                            "nome" => "rg_data_emissao",
                                                            "nomeidioma" => "form_data_emissao",
                                                            "tipo" => "input",
                                                            "valor" => "rg_data_emissao",
                                                            "valor_php" => 'if($dados["rg_data_emissao"]) return formataData("%s", "br", 0)',
                                                            "class" => "span2",
                                                            "mascara" => "99/99/9999",
                                                            "datepicker" => true,
                                                            "banco" => true,
                                                            "banco_php" => 'return formataData("%s", "en", 0)',
                                                            "banco_string" => true
                                                            )
                                                      )
                                    ),
                              array("fieldsetid" => "dados_filiacao", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_dados_filiacao", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array( // Campos do formulario
                                                      array(
                                                            "id" => "form_filiacao_mae", // Id do atributo HTML
                                                            "nome" => "filiacao_mae", // Name do atributo HTML
                                                            "nomeidioma" => "form_filiacao_mae", // Referencia a variavel de idioma
                                                            "tipo" => "input", // Tipo do input
                                                            "evento" => "maxlength='100'",
                                                            "valor" => "filiacao_mae", // Nome da coluna da tabela do banco de dados que retorna o valor.
                                                            "validacao" => array("required" => "filiacao_mae_vazio"), // Validação do campo
                                                            "class" => "span6", //Class do atributo HTML
                                                            "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
                                                            "id" => "form_filiacao_pai", // Id do atributo HTML
                                                            "nome" => "filiacao_pai", // Name do atributo HTML
                                                            "nomeidioma" => "form_filiacao_pai", // Referencia a variavel de idioma
                                                            "tipo" => "input", // Tipo do input
                                                            "evento" => "maxlength='100'",
                                                            "valor" => "filiacao_pai", // Nome da coluna da tabela do banco de dados que retorna o valor.
                                                            "class" => "span6", //Class do atributo HTML
                                                            "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      )
                                    ),
                              array("fieldsetid" => "dados_endereco", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_dados_endereco", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array( // Campos do formulario
                                                      array(
                                                            "id" => "form_cep",
                                                            "nome" => "cep",
                                                            "nomeidioma" => "form_cep",
                                                            "tipo" => "input",
                                                            "valor" => "cep",
                                                            "validacao" => array("required" => "cep_vazio"),
                                                            "class" => "span2",
                                                            "ajudaidioma" => "form_cep_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
                                                            "mascara" => "99999-999", //Mascara do campo
                                                            "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            "banco_php" => 'return str_replace(array("-", ""),"","%s")', // Executa um script php antes de salvar no banco (Utilizado na função SalvarDados)
                                                            "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
                                                            "id" => "idlogradouro",
                                                            "nome" => "idlogradouro",
                                                            "nomeidioma" => "form_logradouro",
                                                            "tipo" => "select",
                                                            "sql" => "SELECT idlogradouro, nome FROM logradouros WHERE ativo = 'S' ORDER BY nome", // SQL que alimenta o select
                                                            "sql_valor" => "idlogradouro", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "valor" => "idlogradouro",
                                                            "validacao" => array("required" => "logradouro_vazio"),
                                                            "banco" => true
                                                            ),
                                                      array(
                                                            "id" => "form_endereco", // Id do atributo HTML
                                                            "nome" => "endereco", // Name do atributo HTML
                                                            "nomeidioma" => "form_endereco", // Referencia a variavel de idioma
                                                            "tipo" => "input", // Tipo do input
                                                            "evento" => "maxlength='100'",
                                                            "valor" => "endereco", // Nome da coluna da tabela do banco de dados que retorna o valor.
                                                            "validacao" => array("required" => "endereco_vazio"), // Validação do campo
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
                                                            "validacao" => array("required" => "bairro_vazio"), // Validação do campo
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
                                                            "validacao" => array("required" => "numero_vazio"), // Validação do campo
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
                                                            "validacao" => array("required" => "estado_vazio"),
                                                            "banco" => true
                                                            ),
                                                     array(
                                                            "id" => "idcidade",
                                                            "nome" => "idcidade",
                                                            "nomeidioma" => "form_idcidade",
                                                            "json" => true,
                                                            "json_idpai" => "idestado",
                                                            "json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"].'/'.$url["3"]."/ajax_cidades/",
                                                            "json_input_pai_vazio" => "form_selecione_estado",
                                                            "json_input_vazio" => "form_selecione_cidade",
                                                            "json_campo_exibir" => "nome",
                                                            "tipo" => "select",
                                                            "valor" => "idcidade",
                                                            "validacao" => array("required" => "cidade_vazio"),
                                                            "banco" => true
                                                            )
                                                      )
                                    ),
                              array("fieldsetid" => "dados_conato", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_dados_contato", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array( // Campos do formulario
                                                      array(
                                                            "id" => "form_email",
                                                            "nome" => "email",
                                                            "nomeidioma" => "form_email",
                                                            "tipo" => "input",
                                                            "valor" => "email",
                                                            "validacao" => array("required" => "email_vazio", "valid_email" => "email_invalido"),
                                                            "class" => "span5",
                                                            "legenda" => "@",
                                                            "banco" => true,
                                                            "banco_string" => true,
                                                            "evento" => "maxlength='100'"
                                                            ),
                                                      array(
                                                            "id" => "form_telefone",
                                                            "nome" => "telefone",
                                                            "nomeidioma" => "form_telefone",
                                                            "tipo" => "input",
                                                            "valor" => "telefone",
                                                            "validacao" => array("required" => "telefone_vazio"),
                                                            "class" => "span2",
                                                            "mascara" => "(99) 9999-9999",
                                                            "banco" => true,
                                                            "banco_string" => true
                                                            ),
                                                      array(
                                                            "id" => "form_celular",
                                                            "nome" => "celular",
                                                            "nomeidioma" => "form_celular",
                                                            "tipo" => "input",
                                                            "valor" => "celular",
                                                            "class" => "span2",
                                                            "mascara" => "(99) 9999-9999",
                                                            "banco" => true,
                                                            "banco_string" => true
                                                            ),
                                                      )
                                    ),
                              array("fieldsetid" => "dados_outros", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_dados_outros", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array( // Campos do formulario
                                                      array(
                                                            "id" => "renda_familiar", // Id do atributo HTML
                                                            "nome" => "renda_familiar", // Name do atributo HTML
                                                            "nomeidioma" => "form_rendafamiliar", // Referencia a variavel de idioma
                                                            "tipo" => "input", // Tipo do input
                                                            "evento" => "maxlength='12'",
                                                            "decimal" => true,
                                                            "valor" => "renda_familiar", // Nome da coluna da tabela do banco de dados que retorna o valor.
                                                            "validacao" => array("required" => "rendafamiliar_vazio"),
                                                            "class" => "span2", //Class do atributo HTML
                                                            "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
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



            // Array de configuração para a formulario
            $config["formulario_conjuge"] = array(
                              array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
                                    "campos" => array( // Campos do formulario
                                                      array(
                                                            "id" => "documento",
                                                            "nome" => "documento",
                                                            "nomeidioma" => "form_cpf",
                                                            "tipo" => "hidden",
                                                            "valor" => '',
                                                            "validacao" => array("required" => "cpf_vazio"),
                                                            "banco" => true,
                                                            "banco_php" => 'return str_replace(array(".", "-"),"","%s")',
                                                            "banco_string" => true
                                                      ),
                                                      array(
                                                            "id" => "form_nome",
                                                            "nome" => "nome",
                                                            "nomeidioma" => "form_nome",
                                                            "tipo" => "input",
                                                            "valor" => "nome",
                                                            "validacao" => array("required" => "nome_vazio"),
                                                            "class" => "span5",
                                                            "banco" => true,
                                                            "banco_string" => true,
                                                            "evento" => "maxlength='100'"
                                                            ),
                                                      /*array(
                                                            "id" => "idtipo",
                                                            "nome" => "idtipo",
                                                            "nomeidioma" => "form_tipoassociacao",
                                                            "tipo" => "select",
                                                            "sql" => "SELECT idtipo, nome FROM pessoas_tipos_associacoes WHERE ativo = 'S' ORDER BY nome", // SQL que alimenta o select
                                                            "sql_valor" => "idtipo", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "valor" => "idtipo",
                                                            "validacao" => array("required" => "tipoassociacao_vazio"),
                                                            "banco" => false
                                                            ),  */
                                                      array(
                                                            "id" => "estado_civil",
                                                            "nome" => "estado_civil",
                                                            "nomeidioma" => "form_estadocivil",
                                                            "tipo" => "select",
                                                            "array" => "estadocivil", // Array que alimenta o select
                                                            "class" => "span2",
                                                            "valor" => "estado_civil",
                                                            "validacao" => array("required" => "estadocivil_vazio"),
                                                            "banco" => true,
                                                            "banco_string" => true
                                                            ),
                                                      array(
                                                            "id" => "data_nasc",
                                                            "nome" => "data_nasc",
                                                            "nomeidioma" => "form_nascimento",
                                                            "tipo" => "input",
                                                            "valor" => "data_nasc",
                                                            "validacao" => array("required" => "data_nasc_vazio"),
                                                            "valor_php" => 'if($dados["data_nasc"]) return formataData("%s", "br", 0)',
                                                            "class" => "span2",
                                                            "mascara" => "99/99/9999",
                                                            "datepicker" => true,
                                                            "banco" => true,
                                                            "banco_php" => 'return formataData("%s", "en", 0)',
                                                            "banco_string" => true
                                                            ),
                                                      array(
                                                            "id" => "form_idpais",
                                                            "nome" => "idpais",
                                                            "nomeidioma" => "form_nacionalidade",
                                                            "tipo" => "select",
                                                            "valor" => "idpais",
                                                            "validacao" => array("required" => "nacionalidade_vazio"),
                                                            "class" => "span3",
                                                            "banco" => true,
                                                            "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
                                                            "id" => "form_naturalidade",
                                                            "nome" => "naturalidade",
                                                            "nomeidioma" => "form_naturalidade",
                                                            "tipo" => "input",
                                                            "valor" => "naturalidade",
                                                            "validacao" => array("required" => "naturalidade_vazio"),
                                                            "class" => "span3",
                                                            "evento" => "maxlength='100'",
                                                            "banco" => true,
                                                            "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            )
                                                      )
                                    ),
                              array("fieldsetid" => "dados_documentos", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_dados_documentos", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array(
                                                      array(
                                                            "id" => "form_rg",
                                                            "nome" => "rg",
                                                            "nomeidioma" => "form_rg",
                                                            "tipo" => "input",
                                                            "valor" => "rg",
                                                            "validacao" => array("required" => "rg_vazio"),
                                                            "class" => "span2",
                                                            "evento" => "maxlength='20'",
                                                            "numerico" => true,
                                                            "banco" => true,
                                                            "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
                                                            "id" => "form_rg_orgao_emissor",
                                                            "nome" => "rg_orgao_emissor",
                                                            "nomeidioma" => "form_orgao_emissor",
                                                            "tipo" => "input",
                                                            "valor" => "rg_orgao_emissor",
                                                            "validacao" => array("required" => "orgao_emissor_vazio"),
                                                            "class" => "span2",
                                                            "evento" => "maxlength='20'",
                                                            "banco" => true,
                                                            "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
                                                            "id" => "rg_data_emissao",
                                                            "nome" => "rg_data_emissao",
                                                            "nomeidioma" => "form_data_emissao",
                                                            "tipo" => "input",
                                                            "valor" => "rg_data_emissao",
                                                            "valor_php" => 'if($dados["rg_data_emissao"]) return formataData("%s", "br", 0)',
                                                            "class" => "span2",
                                                            "mascara" => "99/99/9999",
                                                            "datepicker" => true,
                                                            "banco" => true,
                                                            "banco_php" => 'return formataData("%s", "en", 0)',
                                                            "banco_string" => true
                                                            )
                                                      )
                                    ),
                              array("fieldsetid" => "dados_filiacao", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_dados_filiacao", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array( // Campos do formulario
                                                      array(
                                                            "id" => "form_filiacao_mae", // Id do atributo HTML
                                                            "nome" => "filiacao_mae", // Name do atributo HTML
                                                            "nomeidioma" => "form_filiacao_mae", // Referencia a variavel de idioma
                                                            "tipo" => "input", // Tipo do input
                                                            "evento" => "maxlength='100'",
                                                            "valor" => "filiacao_mae", // Nome da coluna da tabela do banco de dados que retorna o valor.
                                                            "validacao" => array("required" => "filiacao_mae_vazio"), // Validação do campo
                                                            "class" => "span6", //Class do atributo HTML
                                                            "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
                                                            "id" => "form_filiacao_pai", // Id do atributo HTML
                                                            "nome" => "filiacao_pai", // Name do atributo HTML
                                                            "nomeidioma" => "form_filiacao_pai", // Referencia a variavel de idioma
                                                            "tipo" => "input", // Tipo do input
                                                            "evento" => "maxlength='100'",
                                                            "valor" => "filiacao_pai", // Nome da coluna da tabela do banco de dados que retorna o valor.
                                                            "class" => "span6", //Class do atributo HTML
                                                            "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      )
                                    ),
                              array("fieldsetid" => "dados_endereco", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_dados_endereco", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array( // Campos do formulario
                                                      array(
                                                            "id" => "form_cep",
                                                            "nome" => "cep",
                                                            "nomeidioma" => "form_cep",
                                                            "tipo" => "input",
                                                            "valor" => "cep",
                                                            "validacao" => array("required" => "cep_vazio"),
                                                            "class" => "span2",
                                                            "ajudaidioma" => "form_cep_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
                                                            "mascara" => "99999-999", //Mascara do campo
                                                            "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            "banco_php" => 'return str_replace(array("-", ""),"","%s")', // Executa um script php antes de salvar no banco (Utilizado na função SalvarDados)
                                                            "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
                                                            "id" => "idlogradouro",
                                                            "nome" => "idlogradouro",
                                                            "nomeidioma" => "form_logradouro",
                                                            "tipo" => "select",
                                                            "sql" => "SELECT idlogradouro, nome FROM logradouros WHERE ativo = 'S' ORDER BY nome", // SQL que alimenta o select
                                                            "sql_valor" => "idlogradouro", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "valor" => "idlogradouro",
                                                            "validacao" => array("required" => "logradouro_vazio"),
                                                            "banco" => true
                                                            ),
                                                      array(
                                                            "id" => "form_endereco", // Id do atributo HTML
                                                            "nome" => "endereco", // Name do atributo HTML
                                                            "nomeidioma" => "form_endereco", // Referencia a variavel de idioma
                                                            "tipo" => "input", // Tipo do input
                                                            "evento" => "maxlength='100'",
                                                            "valor" => "endereco", // Nome da coluna da tabela do banco de dados que retorna o valor.
                                                            "validacao" => array("required" => "endereco_vazio"), // Validação do campo
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
                                                            "validacao" => array("required" => "bairro_vazio"), // Validação do campo
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
                                                            "validacao" => array("required" => "numero_vazio"), // Validação do campo
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
                                                            "validacao" => array("required" => "estado_vazio"),
                                                            "banco" => true
                                                            ),
                                                     array(
                                                            "id" => "idcidade",
                                                            "nome" => "idcidade",
                                                            "nomeidioma" => "form_idcidade",
                                                            "json" => true,
                                                            "json_idpai" => "idestado",
                                                            "json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"].'/'.$url["3"]."/ajax_cidades/",
                                                            "json_input_pai_vazio" => "form_selecione_estado",
                                                            "json_input_vazio" => "form_selecione_cidade",
                                                            "json_campo_exibir" => "nome",
                                                            "tipo" => "select",
                                                            "valor" => "idcidade",
                                                            "validacao" => array("required" => "cidade_vazio"),
                                                            "banco" => true
                                                            )
                                                      )
                                    ),
                              array("fieldsetid" => "dados_conato", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_dados_contato", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array( // Campos do formulario
                                                      array(
                                                            "id" => "form_email",
                                                            "nome" => "email",
                                                            "nomeidioma" => "form_email",
                                                            "tipo" => "input",
                                                            "valor" => "email",
                                                            "validacao" => array("required" => "email_vazio", "valid_email" => "email_invalido"),
                                                            "class" => "span5",
                                                            "legenda" => "@",
                                                            "banco" => true,
                                                            "banco_string" => true,
                                                            "evento" => "maxlength='100'"
                                                            ),
                                                      array(
                                                            "id" => "form_telefone",
                                                            "nome" => "telefone",
                                                            "nomeidioma" => "form_telefone",
                                                            "tipo" => "input",
                                                            "valor" => "telefone",
                                                            "validacao" => array("required" => "telefone_vazio"),
                                                            "class" => "span2",
                                                            "mascara" => "(99) 9999-9999",
                                                            "banco" => true,
                                                            "banco_string" => true
                                                            ),
                                                      array(
                                                            "id" => "form_celular",
                                                            "nome" => "celular",
                                                            "nomeidioma" => "form_celular",
                                                            "tipo" => "input",
                                                            "valor" => "celular",
                                                            "class" => "span2",
                                                            "mascara" => "(99) 9999-9999",
                                                            "banco" => true,
                                                            "banco_string" => true
                                                            ),
                                                      )
                                    ),
                              array("fieldsetid" => "dados_outros", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_dados_outros", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array( // Campos do formulario
                                                      array(
                                                            "id" => "renda_familiar", // Id do atributo HTML
                                                            "nome" => "renda_familiar", // Name do atributo HTML
                                                            "nomeidioma" => "form_rendafamiliar", // Referencia a variavel de idioma
                                                            "tipo" => "input", // Tipo do input
                                                            "evento" => "maxlength='12'",
                                                            "decimal" => true,
                                                            "valor" => "renda_familiar", // Nome da coluna da tabela do banco de dados que retorna o valor.
                                                            "validacao" => array("required" => "rendafamiliar_vazio"),
                                                            "class" => "span2", //Class do atributo HTML
                                                            "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                                                            ),
                                                      array(
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



            // Array de configuração para a formulario
            $config["formulario_disponibilidade"] = array(
                              array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
                                    "campos" => array( // Campos do formulario
                                                       array(
                                                            "id" => "form_empreendimento",
                                                            "nome" => "idempreendimento",
                                                            "nomeidioma" => "form_empreendimento",
                                                            "tipo" => "select",
                                                            "sql" => "SELECT e.idempreendimento, e.nome FROM empreendimentos e INNER JOIN corretores_empreendimentos c ON (e.idempreendimento = c.idempreendimento) WHERE c.idcorretor = ".$usuario["idcorretor"]." AND e.ativo = 'S' AND c.ativo = 'S' ORDER BY e.nome", // SQL que alimenta o select
                                                            "sql_valor" => "idempreendimento", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "valor" => "idempreendimento",
                                                            "banco" => false
                                                            ),
                                                     array(
                                                            "id" => "form_bloco",
                                                            "nome" => "idbloco",
                                                            "nomeidioma" => "form_bloco",
                                                            "json" => true,
                                                            "json_idpai" => "form_empreendimento",
                                                            "json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/json/retornarBlocos/",
                                                            "json_input_pai_vazio" => "form_selecione_empreendimento",
                                                            "json_input_vazio" => "form_selecione_bloco",
                                                            "json_campo_exibir" => "nome",
                                                            "tipo" => "select",
                                                            "valor" => "idbloco",
                                                            "banco" => false
                                                            )
                                                      )
                                    )
                        );


            $config["formulario_condicoes_pagamento"] = array(
                              array("fieldsetid" => "dadosdainformacao", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_condicoes_pagamento", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array( // Campos do formulario

                                                      array(
                                                            "id" => "form_quantidade",
                                                            "nome" => "quantidade",
                                                            "nomeidioma" => "form_quantidade",
                                                            "tipo" => "input",
                                                            "valor" => "quantidade",
                                                            "validacao" => array("required" => "quantidade_vazio"),
                                                            "class" => "span2",
                                                            "evento" => "maxlength='3'",
                                                            "numerico" => true,
                                                            "layout_horizontal" => true,
                                                            "class_layout" => "span10 well"
                                                            ),

                                                      array(
                                                            "id" => "form_serie",
                                                            "nome" => "idserie",
                                                            "nomeidioma" => "form_serie",
                                                            "tipo" => "select",
                                                            "class" => "span2",
                                                            "sql" => "SELECT idserie, nome FROM tabelasdepreco_series WHERE ativo = 'S' ORDER BY nome", // SQL que alimenta o select
                                                            "sql_valor" => "idserie", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "valor" => "idserie",
                                                            "validacao" => array("required" => "serie_vazio")
                                                            ),

                                                      array(
                                                            "id" => "form_valor", // Id do atributo HTML
                                                            "nome" => "valor", // Name do atributo HTML
                                                            "nomeidioma" => "form_valor", // Referencia a variavel de idioma
                                                            "tipo" => "input", // Tipo do input
                                                            "evento" => "maxlength='12'",
                                                            "decimal" => true,
                                                            "valor" => "valor", // Nome da coluna da tabela do banco de dados que retorna o valor.
                                                            "validacao" => array("required" => "valor_vazio"),
                                                            "class" => "span2"
                                                            ),

                                                      array(
                                                            "id" => "form_vencimento",
                                                            "nome" => "vencimento",
                                                            "nomeidioma" => "form_vencimento",
                                                            "tipo" => "input",
                                                            "valor" => "vencimento",
                                                            "class" => "span2",
                                                            "mascara" => "99/99/9999",
                                                            "validacao" => array("required" => "vencimento_vazio"),
                                                            "datepicker" => true
                                                            ),

                                                      array(
                                                            "id" => "btn_submit",
                                                            "nome" => "btn_submit",
                                                            "tipo" => "botao",
                                                            "tipo_botao" => "submit",
                                                            "class" => "span1",
                                                            "class_botao" => "btn btn-primary",
                                                            "nomeidioma" => "btn_adicionar",
                                                            "fim_layout" => true
                                                            )
                                                        )
                                    )
                        );


            $config["formulario_documentacao"] = array(
                              array("fieldsetid" => "dadosdainformacao", // Titulo do formulario (referencia a variavel de idioma)
                                    "legendaidioma" => "legenda_documentacao", // Legenda do fomrulario (referencia a variavel de idioma)

                                    "campos" => array( // Campos do formulario

                                                      array(
                                                            "id" => "form_tipo",
                                                            "nome" => "idtipo",
                                                            "nomeidioma" => "form_tipo",
                                                            "tipo" => "select",
                                                            "class" => "span2",
                                                            "sql" => "SELECT idtipo, nome FROM arquivos_tipos WHERE ativo = 'S' ORDER BY nome", // SQL que alimenta o select
                                                            "sql_valor" => "idtipo", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "valor" => "idtipo",
                                                            "validacao" => array("required" => "tipo_vazio"),
                                                            "layout_horizontal" => true,
                                                            "class_layout" => "span10 well"
                                                            ),

                                                      /*array(
                                                            "id" => "form_tipoassociacao",
                                                            "nome" => "idtipoassociacao",
                                                            "nomeidioma" => "form_tipoassociacao",
                                                            "tipo" => "select",
                                                            "class" => "span2",
                                                            "sql" => "SELECT idtipo, nome FROM pessoas_tipos_associacoes WHERE ativo = 'S' ORDER BY nome", // SQL que alimenta o select
                                                            "sql_valor" => "idtipo", // Coluna da tabela que será usado como o valor do options
                                                            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                                                            "valor" => "idtipoassociacao",
                                                            "validacao" => array("required" => "tipoassociacao_vazio")
                                                            ),*/

                                                      array(
                                                            "id" => "form_upload", // Id do atributo HTML
                                                            "nome" => "arquivo", // Name do atributo HTML
                                                            "nomeidioma" => "form_upload", // Referencia a variavel de idioma
                                                            "tipo" => "file", // Tipo do input
                                                            "extensoes" => 'jpg|jpeg|gif|png|bmp',
                                                            "validacao" => array("required" => "arquivo_vazio", "formato_arquivo" => "arquivo_invalido"),
                                                            "class" => "span3", //Class do atributo HTML
                                                            "pasta" => "documentos_temp"
                                                            ),

                                                      array(
                                                            "id" => "btn_submit",
                                                            "nome" => "btn_submit",
                                                            "tipo" => "botao",
                                                            "tipo_botao" => "submit",
                                                            "class" => "span1",
                                                            "class_botao" => "btn btn-primary",
                                                            "nomeidioma" => "btn_adicionar",
                                                            "fim_layout" => true
                                                            )
                                                        )
                                    )
                        );
?>