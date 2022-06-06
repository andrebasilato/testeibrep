<?php
// Array de configuração para a formulario
$config["formulario"] = array(
    array(
        "fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array(
            // Campos do formulario
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
                "id" => "form_tipo",
                "nome" => "idtipo",
                "nomeidioma" => "form_tipo",
                "tipo" => "select",
                "sql" => "SELECT idtipo, nome FROM declaracoes_tipos WHERE ativo = 'S' AND ativo_painel = 'S'",
                "sql_valor" => "idtipo",
                "sql_label" => "nome",
                "validacao" => array("required" => "tipo_vazio"),
                "class" => "span2",
                "valor" => "idtipo",
                "referencia_label" => "cadastro_tipodeclaracao",
                "referencia_link" => "/gestor/configuracoes/tiposdeclaracoes",
                "banco" => true
            ),

            array(
                "id" => "form_aluno_solicita",
                "nome" => "aluno_solicita",
                "nomeidioma" => "form_aluno_solicita",
                "tipo" => "select",
                "array" => "sim_nao", // Array que alimenta o select
                "class" => "span2",
                "valor" => "aluno_solicita",
                "validacao" => array("required" => "aluno_solicita_vazio"),
                "ajudaidioma" => "form_aluno_solicita_ajuda",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_upload", // Id do atributo HTML
                "nome" => "background", // Name do atributo HTML
                "nomeidioma" => "form_upload", // Referencia a variavel de idioma
                "ajudaidioma" => "form_logo_ajuda",
                "arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
                "arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
                "tipo" => "file", // Tipo do input
                //"diminuir_largura" => 150,
                "tamanho" => 20,
                "extensoes" => 'jpg|jpeg|gif|png|bmp',
                "validacao" => array("formato_arquivo" => "arquivo_invalido"),
                "class" => "span6", //Class do atributo HTML
                "pasta" => "declaracoes_background",
                "download" => true,
                "excluir" => true,
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_campo" => "background", // Nome das colunas da tabela do banco de dados que retorna o valor.
                "ignorarsevazio" => true
            ),
            array(
                "id" => "form_top",
                "nome" => "margem_top",
                "nomeidioma" => "form_top",
                "tipo" => "input",
                "valor" => "margem_top",
                "evento" => "maxlength='4'",
                "decimal" => true,
                //"validacao" => array("required" => "nome_vazio"),
                "class" => "span1",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_bottom",
                "nome" => "margem_bottom",
                "nomeidioma" => "form_bottom",
                "tipo" => "input",
                "valor" => "margem_bottom",
                "evento" => "maxlength='4'",
                "decimal" => true,
                //"validacao" => array("required" => "nome_vazio"),
                "class" => "span1",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_left",
                "nome" => "margem_left",
                "nomeidioma" => "form_left",
                "tipo" => "input",
                "valor" => "margem_left",
                "evento" => "maxlength='4'",
                "decimal" => true,
                //"validacao" => array("required" => "nome_vazio"),
                "class" => "span1",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_right",
                "nome" => "margem_right",
                "nomeidioma" => "form_right",
                "tipo" => "input",
                "valor" => "margem_right",
                "evento" => "maxlength='4'",
                "decimal" => true,
                //"validacao" => array("required" => "nome_vazio"),
                "class" => "span1",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_declaracao",
                "nome" => "declaracao",
                "nomeidioma" => "form_declaracao",
                "tipo" => "text",
                "editor" => true,
                "valor" => "declaracao",
                "class" => "xxlarge",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_botao_variaveis_aluno", // Id do atributo HTML
                "nome" => "botao_variaveis_aluno", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis_aluno", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_titulo_aluno" => "titulo",
                        "variavel_aluno_nome" => "[[ALUNO][NOME]]",
                        "variavel_aluno_estadocivil" => "[[ALUNO][ESTADO_CIVIL]]",
                        "variavel_aluno_nascimento" => "[[ALUNO][DATA_NASC]]",
                        "variavel_aluno_nacionalidade" => "[[ALUNO][NACIONALIDADE]]",
                        "variavel_aluno_naturalidade" => "[[ALUNO][NATURALIDADE]]",
                        "variavel_aluno_documento" => "[[ALUNO][DOCUMENTO]]",
                        "variavel_aluno_rg" => "[[ALUNO][RG]]",
                        "variavel_aluno_orgao_expeditor" => "[[ALUNO][RG_ORGAO_EMISSOR]]",
                        "variavel_aluno_emissao" => "[[ALUNO][RG_DATA_EMISSAO]]",
                        "variavel_aluno_rne" => "[[ALUNO][RNE]]",
                        "variavel_aluno_mae" => "[[ALUNO][FILIACAO_MAE]]",
                        "variavel_aluno_pai" => "[[ALUNO][FILIACAO_PAI]]",
                        "variavel_aluno_cep" => "[[ALUNO][CEP]]",
                        "variavel_aluno_logradouro" => "[[ALUNO][LOGRADOURO]]",
                        "variavel_aluno_endereco" => "[[ALUNO][ENDERECO]]",
                        "variavel_aluno_bairro" => "[[ALUNO][BAIRRO]]",
                        "variavel_aluno_numero" => "[[ALUNO][NUMERO]]",
                        "variavel_aluno_complemento" => "[[ALUNO][COMPLEMENTO]]",
                        "variavel_aluno_estado" => "[[ALUNO][ESTADO]]",
                        "variavel_aluno_cidade" => "[[ALUNO][CIDADE]]",
                        "variavel_aluno_telefone" => "[[ALUNO][TELEFONE]]",
                        "variavel_aluno_celular" => "[[ALUNO][CELULAR]]",
                        "variavel_aluno_email" => "[[ALUNO][EMAIL]]",
                        "variavel_aluno_banco" => "[[ALUNO][BANCO_NOME]]",
                        "variavel_aluno_agencia" => "[[ALUNO][BANCO_AGENCIA]]",
                        "variavel_aluno_conta" => "[[ALUNO][BANCO_CONTA]]",
                        "variavel_aluno_banco_nome_titular" => "[[ALUNO][BANCO_NOME_TITULAR]]",
                        "variavel_aluno_banco_cpf_titular" => "[[ALUNO][BANCO_CPF_TITULAR]]",
                        "variavel_aluno_banco_observacoes" => "[[ALUNO][BANCO_OBSERVACOES]]",
                        "variavel_aluno_renda" => "[[ALUNO][RENDA_FAMILIAR]]",
                        "variavel_aluno_observacoes" => "[[ALUNO][OBSERVACOES]]",
                        "variavel_aluno_profissao" => "[[ALUNO][PROFISSAO]]"
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "form_botao_variaveis_matricula", // Id do atributo HTML
                "nome" => "botao_variaveis_matricula", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis_matricula", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_titulo_matricula" => "titulo",
                        "variavel_matricula_numero" => "[[MATRICULA][NUMERO]]",
                        "variavel_matricula_data_conclusao" => "[[MATRICULA][DATA_CONCLUSAO]]",
                        "variavel_matricula_data_matricula" => "[[MATRICULA][DATA_MATRICULA]]",
                        "variavel_matricula_num_contrato" => "[[MATRICULA][NUMERO_CONTRATO]]",
                        "variavel_matricula_valor_contrato" => "[[MATRICULA][VALOR_CONTRATO]]",
                        "variavel_matricula_qtde_parcelas" => "[[MATRICULA][QUANTIDADE_PARCELAS]]",
                        "variavel_matricula_data_expedicao" => "[[MATRICULA][DATA_EXPEDICAO]]",
                        "variavel_matricula_ultimo_acesso_ava" => "[[MATRICULA][ULTIMO_ACESSO_AVA]]",
                        "variavel_matricula_total_acessos_ava" => "[[MATRICULA][TOTAL_ACESSOS_AVA]]",
                        "variavel_matricula_data_registro" => "[[MATRICULA][DATA_REGISTRO]]",
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "form_botao_variaveis_curso", // Id do atributo HTML
                "nome" => "botao_variaveis_curso", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis_curso", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_titulo_curso" => "titulo",
                        "variavel_curso_codigo" => "[[CURSO][CODIGO]]",
                        "variavel_curso_nome" => "[[CURSO][NOME]]",
                        "variavel_curso_dia_acesso_ava" => "[[CURSO][DIAS_ACESSO_AVA]]",
                        "variavel_curso_percentual_ideal_ava" => "[[CURSO][PERCENTUAL_IDEAL_AVA]]",
                        "variavel_curso_tipo" => "[[CURSO][TIPO]]",
                        "variavel_curso_carga_horaria_presencial" => "[[CURSO][CARGA_HORARIA_PRESENCIAL]]",
                        "variavel_curso_carga_horaria_distancia" => "[[CURSO][CARGA_HORARIA_DISTANCIA]]",
                        "variavel_curso_carga_horaria_total" => "[[CURSO][CARGA_HORARIA_TOTAL]]",
                        "variavel_curso_perfil" => "[[CURSO][PERFIL]]",
                        "variavel_curso_fundamentacao" => "[[CURSO][FUNDAMENTACAO]]",
                        "variavel_curso_fundamentacao_legal" => "[[CURSO][FUNDAMENTACAO_LEGAL]]",
                        "variavel_curso_abreviacao" => "[[CURSO][ABREVIACAO]]",
                        "variavel_curso_area" => "[[CURSO][AREA]]",
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "form_botao_variaveis_escola", // Id do atributo HTML
                "nome" => "botao_variaveis_escola", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis_escola", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_titulo_escola" => "titulo",
                        "variavel_escola_razao_social" => "[[CFC][RAZAO_SOCIAL]]",
                        "variavel_escola_nome_fantasia" => "[[CFC][NOME_FANTASIA]]",
                        "variavel_escola_inscricao_estadual" => "[[CFC][INSCRICAO_ESTADUAL]]",
                        "variavel_escola_inscricao_municipal" => "[[CFC][INSCRICAO_MUNICIPAL]]",
                        "variavel_escola_fax" => "[[CFC][FAX]]",
                        "variavel_escola_email" => "[[CFC][EMAIL]]",
                        "variavel_escola_telefone" => "[[CFC][TELEFONE]]",
                        "variavel_escola_cep" => "[[CFC][CEP]]",
                        "variavel_escola_logradouro" => "[[CFC][LOGRADOURO]]",
                        "variavel_escola_endereco" => "[[CFC][ENDERECO]]",
                        "variavel_escola_bairro" => "[[CFC][BAIRRO]]",
                        "variavel_escola_estado" => "[[CFC][ESTADO]]",
                        "variavel_escola_quantidade_pessoas_comportadas" => "[[CFC][QUANTIDADE_PESSOAS_COMPORTADAS]]",
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "form_botao_variaveis_sindicato", // Id do atributo HTML
                "nome" => "botao_variaveis_sindicato", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis_sindicato", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_titulo_curso" => "titulo",
                        "variavel_sindicato_nome" => "[[SINDICATO][NOME]]",
                        "variavel_sindicato_nome_abreviado" => "[[SINDICATO][NOME_ABREVIADO]]",
                        "variavel_sindicato_cnpj" => "[[SINDICATO][CNPJ]]",
                        "variavel_sindicato_fax" => "[[SINDICATO][FAX]]",
                        "variavel_sindicato_nre" => "[[SINDICATO][NRE]]",
                        "variavel_sindicato_email" => "[[SINDICATO][EMAIL]]",
                        "variavel_sindicato_telefone" => "[[SINDICATO][TELEFONE]]",
                        "variavel_sindicato_cep" => "[[SINDICATO][CEP]]",
                        "variavel_sindicato_logradouro" => "[[SINDICATO][LOGRADOURO]]",
                        "variavel_sindicato_endereco" => "[[SINDICATO][ENDERECO]]",
                        "variavel_sindicato_bairro" => "[[SINDICATO][BAIRRO]]",
                        "variavel_sindicato_estado" => "[[SINDICATO][ESTADO]]",
                        "variavel_sindicato_cidade" => "[[SINDICATO][CIDADE]]",
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "form_botao_variaveis_oferta", // Id do atributo HTML
                "nome" => "botao_variaveis_oferta", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis_oferta", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_titulo_curso" => "titulo",
                        "variavel_oferta_nome" => "[[OFERTA][NOME]]",
                        "variavel_oferta_data_inicio_matricula" => "[[OFERTA][DATA_INICIO_MATRICULA]]",
                        "variavel_oferta_data_fim_matricula" => "[[OFERTA][DATA_FIM_MATRICULA]]",
                        "variavel_oferta_data_limite" => "[[OFERTA][DATA_LIMITE]]",
                        "variavel_oferta_modalidade" => "[[OFERTA][MODALIDADE]]",
                        "variavel_oferta_data_inicio_acesso_ava" => "[[OFERTA][DATA_INICIO_ACESSO_AVA]]",
                        "variavel_oferta_data_fim_acesso_ava" => "[[OFERTA][DATA_FIM_ACESSO_AVA]]",
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "form_botao_variaveis_vendedor", // Id do atributo HTML
                "nome" => "botao_variaveis_vendedor", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis_vendedor", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_titulo_vendedor" => "titulo",
                        "variavel_vendedor_nome" => "[[ATENDENTE][NOME]]",
                        "variavel_vendedor_documento" => "[[ATENDENTE][DOCUMENTO]]",
                        "variavel_vendedor_rg" => "[[ATENDENTE][RG]]",
                        "variavel_vendedor_orgao_expeditor" => "[[ATENDENTE][RG_ORGAO_EMISSOR]]",
                        "variavel_vendedor_emissao" => "[[ATENDENTE][RG_DATA_EMISSAO]]",
                        "variavel_vendedor_telefone" => "[[ATENDENTE][TELEFONE]]",
                        "variavel_vendedor_celular" => "[[ATENDENTE][CELULAR]]",
                        "variavel_vendedor_email" => "[[ATENDENTE][EMAIL]]",
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "form_botao_variaveis_curriculo", // Id do atributo HTML
                "nome" => "botao_variaveis_curriculo", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis_curriculo", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_titulo_curriculo" => "titulo",
                        "variavel_curriculo_nome" => "[[CURRICULO][NOME]]",
                        "variavel_curriculo_carga_horaria" => "[[CURRICULO][CARGA_HORARIA]]",
                        "variavel_curriculo_media" => "[[CURRICULO][MEDIA]]",
                        "variavel_curriculo_dias_minimo" => "[[CURRICULO][DIAS_MINIMO]]",
                        "variavel_curriculo_porcentagem_ava" => "[[CURRICULO][PORCENTAGEM_AVA]]",
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "form_botao_variaveis_declaracao", // Id do atributo HTML
                "nome" => "botao_variaveis_declaracao", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis_declaracao", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_titulo_declaracao" => "titulo",
                        "variavel_declaracao_link" => "[[DECLARACAO][LINK_VALIDACAO]]",
                        "variavel_declaracao_cod_validacao" => "[[DECLARACAO][CODIGO_VALIDACAO]]",
                        "variavel_quebra_pagina" => "[[DECLARACAO][QUEBRA_DE_PAGINA]]",
                        "variavel_data_geracao" => "[[DECLARACAO][DATA_GERACAO]]",
                        "variavel_data_extenso" => "[[DECLARACAO][DATA_GERACAO_EXTENSO]]",
                        "variavel_local" => "[[DECLARACAO][CAMPO_ADICIONAL_LOCAL]]",
                        "variavel_data_prevista_conclusao" => "[[DECLARACAO][DATA_PREVISTA_CONCLUSAO]]",
                        "variavel_adicional" => "[[DECLARACAO][CAMPO_ADICIONAL]]",
                        "variavel_adicional2" => "[[DECLARACAO][CAMPO_ADICIONAL2]]",
                        "variavel_adicional3" => "[[DECLARACAO][CAMPO_ADICIONAL3]]",
                        "variavel_adicional4" => "[[DECLARACAO][CAMPO_ADICIONAL4]]",
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "form_difere_automatico",
                "nome" => "difere_automatico",
                "nomeidioma" => "form_difere_automatico",
                "tipo" => "select",
                "array" => "sim_nao", // Array que alimenta o select
                "sem_primeira_linha" => true,
                "class" => "span2",
                "valor" => "difere_automatico",
                "banco" => true,
                "banco_string" => true
            ),
        )
    )
);
?>