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
        "sql" => "SELECT idtipo, nome FROM contratos_tipos WHERE ativo = 'S' AND ativo_painel = 'S'",
        "sql_valor" => "idtipo",
        "sql_label" => "nome",
        "validacao" => array("required" => "tipo_vazio"),
        "class" => "span2",
        "valor" => "idtipo",
        "referencia_label" => "cadastro_tipocontrato",
        "referencia_link" => "/gestor/configuracoes/tiposcontratos",
        "banco" => true
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
        "pasta" => "contratos_background",
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
        "id" => "gerar_proximo_acesso",
        "nome" => "gerar_proximo_acesso",
        "nomeidioma" => "form_gerar_proximo_acesso",
        "tipo" => "select",
        "array" => "sim_nao", // Array que alimenta o select
        "class" => "span2",
        "valor" => "gerar_proximo_acesso",
        //"validacao" => array("required" => "ativo_vazio"),
        //"ajudaidioma" => "form_ativo_ajuda",
        "banco" => true,
        "banco_string" => true
      ),
      array(
        "id" => "gerar_cfc",
        "nome" => "gerar_cfc",
        "nomeidioma" => "form_gerar_cfc",
        "tipo" => "checkbox",
        //"array" => "sim_nao", // Array que alimenta o select
        "class" => "span2",
        "valor" => "gerar_cfc",
        //"validacao" => array("required" => "ativo_vazio"),
        //"ajudaidioma" => "form_ativo_ajuda",
        "banco" => true,
        "banco_string" => true
      ),
      array(
        "id" => "gerar_aluno",
        "nome" => "gerar_aluno",
        "nomeidioma" => "form_gerar_aluno",
        "tipo" => "checkbox",
        //"array" => "sim_nao", // Array que alimenta o select
        "class" => "span2",
        "valor" => "gerar_aluno",
//        "validacao" => array("required" => "ativo_vazio"),
//        "ajudaidioma" => "form_ativo_ajuda",
        "banco" => true,
        "banco_string" => true
      ),  
      array(
        "id" => "form_contrato",
        "nome" => "contrato",
        "nomeidioma" => "form_contrato",
        "tipo" => "text",
        "editor" => true,
        "valor" => "contrato",
        "class" => "xxlarge",
        "banco" => true,
        "banco_string" => true
      ),

      array(
        "id" => "form_botao_variaveis_cliente", // Id do atributo HTML
        "nome" => "botao_variaveis_cliente", // Name do atributo HTML
        "nomeidioma" => "form_botao_variaveis_cliente", // Referencia a variavel de idioma
        "tipo" => "php", // Tipo do input
        "colunas" => 2,
        "botao_hide" => true,
        "valor" => array(
                      array(
                        "variavel_titulo_cliente" => "titulo",
                        "variavel_cliente_nome" => "[[CLIENTE][NOME]]",
                        "variavel_cliente_estadocivil" => "[[CLIENTE][ESTADO_CIVIL]]",
                        "variavel_cliente_nascimento" => "[[CLIENTE][DATA_NASC]]",
                        "variavel_cliente_nacionalidade" => "[[CLIENTE][NACIONALIDADE]]",
                        "variavel_cliente_naturalidade" => "[[CLIENTE][NATURALIDADE]]",
                        "variavel_cliente_documento" => "[[CLIENTE][DOCUMENTO]]",
                        "variavel_cliente_rg" => "[[CLIENTE][RG]]",
                        "variavel_cliente_orgao_expeditor" => "[[CLIENTE][RG_ORGAO_EMISSOR]]",
                        "variavel_cliente_emissao" => "[[CLIENTE][RG_DATA_EMISSAO]]",
                        "variavel_cliente_mae" => "[[CLIENTE][FILIACAO_MAE]]",
                        "variavel_cliente_pai" => "[[CLIENTE][FILIACAO_PAI]]",
                        "variavel_cliente_cep" => "[[CLIENTE][CEP]]",
                        "variavel_cliente_logradouro" => "[[CLIENTE][LOGRADOURO]]",
                        "variavel_cliente_endereco" => "[[CLIENTE][ENDERECO]]",
                        "variavel_cliente_bairro" => "[[CLIENTE][BAIRRO]]",
                        "variavel_cliente_numero" => "[[CLIENTE][NUMERO]]",
                        "variavel_cliente_complemento" => "[[CLIENTE][COMPLEMENTO]]",
                        "variavel_cliente_estado" => "[[CLIENTE][ESTADO]]",
                        "variavel_cliente_cidade" => "[[CLIENTE][CIDADE]]",
                        "variavel_cliente_telefone" => "[[CLIENTE][TELEFONE]]",
                        "variavel_cliente_celular" => "[[CLIENTE][CELULAR]]",
                        "variavel_cliente_email" => "[[CLIENTE][EMAIL]]",
                        "variavel_cliente_banco" => "[[CLIENTE][BANCO_NOME]]",
                        "variavel_cliente_agencia" => "[[CLIENTE][BANCO_AGENCIA]]",
                        "variavel_cliente_conta" => "[[CLIENTE][BANCO_CONTA]]",
                        "variavel_cliente_banco_nome_titular" => "[[CLIENTE][BANCO_NOME_TITULAR]]",
                        "variavel_cliente_banco_cpf_titular" => "[[CLIENTE][BANCO_CPF_TITULAR]]",
                        "variavel_cliente_banco_observacoes" => "[[CLIENTE][BANCO_OBSERVACOES]]",
                        "variavel_cliente_renda" => "[[CLIENTE][RENDA_FAMILIAR]]",
                        "variavel_cliente_observacoes" => "[[CLIENTE][OBSERVACOES]]",
                        "variavel_cliente_profissao" => "[[CLIENTE][PROFISSAO]]",
                        "variavel_cliente_cnh" => "[[CLIENTE][CNH]]",
                        "variavel_cliente_categoria" => "[[CLIENTE][CATEGORIA]]"
                      )
                    ),
        "class" => "span4" //Class do atributo HTML
      ),
        
     array(
        "id" => "form_botao_variaveis_cfc", // Id do atributo HTML
        "nome" => "botao_variaveis_cfc", // Name do atributo HTML
        "nomeidioma" => "form_botao_variaveis_cfc", // Referencia a variavel de idioma
        "tipo" => "php", // Tipo do input
        "colunas" => 2,
        "botao_hide" => true,
        "valor" => array(
                      array(
                        "variavel_titulo_cfc" => "titulo",
                        "variavel_cfc_nome_fantasia" => "[[CFC][NOME_FANTASIA]]",
                        "variavel_cfc_razao_social" => "[[CFC][RAZAO_SOCIAL]]",
                        "variavel_cfc_inscricao_estadual" => "[[CFC][INSCRICAO_ESTADUAL]]",
                        "variavel_cfc_inscricao_municipal" => "[[CFC][INSCRICAO_MUNICIPAL]]",
                        "variavel_cfc_sindicato" => "[[CFC][SINDICATO]]",
                        "variavel_cfc_documento" => "[[CFC][DOCUMENTO]]",
                        "variavel_cfc_site" => "[[CFC][SITE]]",
                        "variavel_cfc_informacoes" => "[[CFC][INFORMACOES]]",
                        "variavel_cfc_slug" => "[[CFC][SLUG]]",
                        "variavel_cfc_ciretran" => "[[CFC][CIRETRAN]]",
                        "variavel_cfc_ciretran_numero" => "[[CFC][CIRETRAN_NUMERO]]",
                        "variavel_cfc_ciretran_cidade" => "[[CFC][CIRETRAN_CIDADE]]",
                        "variavel_cfc_cep" => "[[CFC][CEP]]",
                        "variavel_cfc_logradouro" => "[[CFC][LOGRADOURO]]",
                        "variavel_cfc_endereco" => "[[CFC][ENDERECO]]",
                        "variavel_cfc_bairro" => "[[CFC][BAIRRO]]",
                        "variavel_cfc_numero" => "[[CFC][NUMERO]]",
                        "variavel_cfc_complemento" => "[[CFC][COMPLEMENTO]]",
                        "variavel_cfc_estado" => "[[CFC][ESTADO]]",
                        "variavel_cfc_cidade" => "[[CFC][CIDADE]]",
                        "variavel_cfc_telefone" => "[[CFC][TELEFONE]]",
                        "variavel_cfc_fax" => "[[CFC][FAX]]",
                        "variavel_cfc_email" => "[[CFC][EMAIL]]",
                          
                          
                        "variavel_cfc_gerente_nome" => "[[CFC][GERENTE_NOME]]",
                        "variavel_cfc_gerente_nascimento" => "[[CFC][GERENTE_DATA_NASC]]",
                        "variavel_cfc_gerente_documento" => "[[CFC][GERENTE_CPF]]",
                        "variavel_cfc_gerente_cep" => "[[CFC][GERENTE_CEP]]",
                        "variavel_cfc_gerente_logradouro" => "[[CFC][GERENTE_LOGRADOURO]]",
                        "variavel_cfc_gerente_endereco" => "[[CFC][GERENTE_ENDERECO]]",
                        "variavel_cfc_gerente_bairro" => "[[CFC][GERENTE_BAIRRO]]",
                        "variavel_cfc_gerente_numero" => "[[CFC][GERENTE_NUMERO]]",
                        "variavel_cfc_gerente_complemento" => "[[CFC][GERENTE_COMPLEMENTO]]",
                        "variavel_cfc_gerente_estado" => "[[CFC][GERENTE_ESTADO]]",
                        "variavel_cfc_gerente_cidade" => "[[CFC][GERENTE_CIDADE]]",
                        "variavel_cfc_gerente_telefone" => "[[CFC][GERENTE_TELEFONE]]",
                        "variavel_cfc_gerente_celular" => "[[CFC][GERENTE_CELULAR]]",
                        "variavel_cfc_gerente_email" => "[[CFC][GERENTE_EMAIL]]",
                        "variavel_cfc_gerente_profissao" => "[[CFC][GERENTE_PROFISSAO]]",
                        
                        "variavel_cfc_responsavel_legal_nome" => "[[CFC][RESPONSAVEL_LEGAL_NOME]]",
                        "variavel_cfc_responsavel_legal_nascimento" => "[[CFC][RESPONSAVEL_LEGAL_DATA_NASC]]",
                        "variavel_cfc_responsavel_legal_documento" => "[[CFC][RESPONSAVEL_LEGAL_CPF]]",
                        "variavel_cfc_responsavel_legal_cep" => "[[CFC][RESPONSAVEL_LEGAL_CEP]]",
                        "variavel_cfc_responsavel_legal_logradouro" => "[[CFC][RESPONSAVEL_LEGAL_LOGRADOURO]]",
                        "variavel_cfc_responsavel_legal_endereco" => "[[CFC][RESPONSAVEL_LEGAL_ENDERECO]]",
                        "variavel_cfc_responsavel_legal_bairro" => "[[CFC][RESPONSAVEL_LEGAL_BAIRRO]]",
                        "variavel_cfc_responsavel_legal_numero" => "[[CFC][RESPONSAVEL_LEGAL_NUMERO]]",
                        "variavel_cfc_responsavel_legal_complemento" => "[[CFC][RESPONSAVEL_LEGAL_COMPLEMENTO]]",
                        "variavel_cfc_responsavel_legal_estado" => "[[CFC][RESPONSAVEL_LEGAL_ESTADO]]",
                        "variavel_cfc_responsavel_legal_cidade" => "[[CFC][RESPONSAVEL_LEGAL_CIDADE]]",
                        "variavel_cfc_responsavel_legal_telefone" => "[[CFC][RESPONSAVEL_LEGAL_TELEFONE]]",
                        "variavel_cfc_responsavel_legal_celular" => "[[CFC][RESPONSAVEL_LEGAL_CELULAR]]",
                        "variavel_cfc_responsavel_legal_email" => "[[CFC][RESPONSAVEL_LEGAL_EMAIL]]",
                        "variavel_cfc_responsavel_legal_profissao" => "[[CFC][RESPONSAVEL_LEGAL_PROFISSAO]]",
                          
                        "variavel_cfc_diretor_ensino_nome" => "[[CFC][DIRETOR_ENSINO_NOME]]",
                        "variavel_cfc_diretor_ensino_nascimento" => "[[CFC][DIRETOR_ENSINO_DATA_NASC]]",
                        "variavel_cfc_diretor_ensino_documento" => "[[CFC][DIRETOR_ENSINO_CPF]]",
                        "variavel_cfc_diretor_ensino_telefone" => "[[CFC][DIRETOR_ENSINO_TELEFONE]]",
                        "variavel_cfc_diretor_ensino_celular" => "[[CFC][DIRETOR_ENSINO_CELULAR]]",
                        "variavel_cfc_diretor_ensino_email" => "[[CFC][DIRETOR_ENSINO_EMAIL]]",
                        "variavel_cfc_diretor_ensino_skype" => "[[CFC][DIRETOR_ENSINO_SKYPE]]",
                        "variavel_cfc_diretor_ensino_portaria" => "[[CFC][DIRETOR_ENSINO_PORTARIA]]",  
                        "variavel_cfc_diretor_ensino_profissao" => "[[CFC][DIRETOR_ENSINO_PROFISSAO]]"  
                          
                      )
                    ),
        "class" => "span4" //Class do atributo HTML
      ),   

    array(
        "id" => "form_botao_variaveis_devedor_solidario", // Id do atributo HTML
        "nome" => "botao_variaveis_devedor_solidario", // Name do atributo HTML
        "nomeidioma" => "form_botao_variaveis_devedor_solidario", // Referencia a variavel de idioma
        "tipo" => "php", // Tipo do input
        "colunas" => 2,
        "botao_hide" => true,
        "valor" => array(
                      array(
                        "variavel_titulo_devedor_solidario" => "titulo",
                        "variavel_devedor_solidario_nome" => "[[DEV_SOLIDARIO][NOME]]",
                        "variavel_devedor_solidario_estadocivil" => "[[DEV_SOLIDARIO][ESTADO_CIVIL]]",
                        "variavel_devedor_solidario_nascimento" => "[[DEV_SOLIDARIO][DATA_NASC]]",
                        "variavel_devedor_solidario_nacionalidade" => "[[DEV_SOLIDARIO][NACIONALIDADE]]",
                        "variavel_devedor_solidario_naturalidade" => "[[DEV_SOLIDARIO][NATURALIDADE]]",
                        "variavel_devedor_solidario_documento" => "[[DEV_SOLIDARIO][DOCUMENTO]]",
                        "variavel_devedor_solidario_rg" => "[[DEV_SOLIDARIO][RG]]",
                        "variavel_devedor_solidario_orgao_expeditor" => "[[DEV_SOLIDARIO][RG_ORGAO_EMISSOR]]",
                        "variavel_devedor_solidario_emissao" => "[[DEV_SOLIDARIO][RG_DATA_EMISSAO]]",
                        "variavel_devedor_solidario_mae" => "[[DEV_SOLIDARIO][FILIACAO_MAE]]",
                        "variavel_devedor_solidario_pai" => "[[DEV_SOLIDARIO][FILIACAO_PAI]]",
                        "variavel_devedor_solidario_cep" => "[[DEV_SOLIDARIO][CEP]]",
                        "variavel_devedor_solidario_logradouro" => "[[DEV_SOLIDARIO][LOGRADOURO]]",
                        "variavel_devedor_solidario_endereco" => "[[DEV_SOLIDARIO][ENDERECO]]",
                        "variavel_devedor_solidario_bairro" => "[[DEV_SOLIDARIO][BAIRRO]]",
                        "variavel_devedor_solidario_numero" => "[[DEV_SOLIDARIO][NUMERO]]",
                        "variavel_devedor_solidario_complemento" => "[[DEV_SOLIDARIO][COMPLEMENTO]]",
                        "variavel_devedor_solidario_estado" => "[[DEV_SOLIDARIO][ESTADO]]",
                        "variavel_devedor_solidario_cidade" => "[[DEV_SOLIDARIO][CIDADE]]",
                        "variavel_devedor_solidario_telefone" => "[[DEV_SOLIDARIO][TELEFONE]]",
                        "variavel_devedor_solidario_celular" => "[[DEV_SOLIDARIO][CELULAR]]",
                        "variavel_devedor_solidario_email" => "[[DEV_SOLIDARIO][EMAIL]]",
                        "variavel_devedor_solidario_banco" => "[[DEV_SOLIDARIO][BANCO_NOME]]",
                        "variavel_devedor_solidario_agencia" => "[[DEV_SOLIDARIO][BANCO_AGENCIA]]",
                        "variavel_devedor_solidario_conta" => "[[DEV_SOLIDARIO][BANCO_CONTA]]",
                        "variavel_devedor_solidario_banco_nome_titular" => "[[DEV_SOLIDARIO][BANCO_NOME_TITULAR]]",
                        "variavel_devedor_solidario_banco_cpf_titular" => "[[DEV_SOLIDARIO][BANCO_CPF_TITULAR]]",
                        "variavel_devedor_solidario_banco_observacoes" => "[[DEV_SOLIDARIO][BANCO_OBSERVACOES]]",
                        "variavel_devedor_solidario_renda" => "[[DEV_SOLIDARIO][RENDA_FAMILIAR]]",
                        "variavel_devedor_solidario_observacoes" => "[[DEV_SOLIDARIO][OBSERVACOES]]",
                        "variavel_devedor_solidario_profissao" => "[[DEV_SOLIDARIO][PROFISSAO]]"
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
                        "variavel_curso_inicio" => "[[CURSO][INICIO]]",
                        "variavel_curso_termino" => "[[CURSO][TERMINO]]",
                      )
                    ),
        "class" => "span4" //Class do atributo HTML
      ),

      array(
        "id" => "form_botao_variaveis_financeiro", // Id do atributo HTML
        "nome" => "botao_variaveis_financeiro", // Name do atributo HTML
        "nomeidioma" => "form_botao_variaveis_financeiro", // Referencia a variavel de idioma
        "tipo" => "php", // Tipo do input
        "colunas" => 2,
        "botao_hide" => true,
        "valor" => array(
                      array(
                        "variavel_titulo_financeiro" => "titulo",
                        "variavel_financeiro_valor_total_mensalidades" => "[[FINANCEIRO][VALOR_TOTAL_MENS]]",
                        "variavel_financeiro_valor_total_mensalidades_extenso" => "[[FINANCEIRO][VALOR_TOTAL_MENS_EXTENSO]]",
                        "variavel_financeiro_valor_total_outras" => "[[FINANCEIRO][VALOR_TOTAL_OUTRAS]]",
                        "variavel_financeiro_valor_total_outras_extenso" => "[[FINANCEIRO][VALOR_TOTAL_OUTRAS_EXTENSO]]",
                        "variavel_financeiro_forma_pagamento_detalhado" => "[[FINANCEIRO][FORMA_PAGAMENTO_DETALHADO]]",
                        "variavel_financeiro_forma_pagamento" => "[[FINANCEIRO][FORMA_PAGAMENTO]]",
                        "variavel_financeiro_qnt_parcelas" => "[[FINANCEIRO][QNT_PARCELAS]]",
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
                        "variavel_vendedor_data_emissao" => "[[ATENDENTE][RG_DATA_EMISSAO]]",
                        "variavel_vendedor_rne" => "[[ATENDENTE][RNE]]",
                      )
                    ),
        "class" => "span4" //Class do atributo HTML
      ),

      array(
        "id" => "form_botao_variaveis_adicionais", // Id do atributo HTML
        "nome" => "botao_variaveis_adicionais", // Name do atributo HTML
        "nomeidioma" => "form_botao_variaveis_adicionais", // Referencia a variavel de idioma
        "tipo" => "php", // Tipo do input
        "colunas" => 2,
        "botao_hide" => true,
        "valor" => array(
                      array(
                        "variavel_tabela_documentos" => "[[TABELA_DOCUMENTOS]]",
                        "variavel_data_geracao" => "[[DATA_GERACAO_CONTRATO]]",
                        "variavel_data_geracao_extenso" => "[[DATA_GERACAO_CONTRATO_EXTENSO]]",
                        "variavel_quebra_pagina" => "[[QUEBRA_DE_PAGINA]]",
                        "variavel_adicional_local" => "[[campo_adicional_local]]",
                        "variavel_adicional_1" => "[[campo_adicional_1]]",
                        "variavel_adicional_2" => "[[campo_adicional_2]]",
                        "variavel_adicional_3" => "[[campo_adicional_3]]",
                        "variavel_adicional_4" => "[[campo_adicional_4]]"
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
                          "variavel_matricula_sindicato" => "[[MATRICULA][INSTITUICAO]]",
                          "variavel_matricula_oferta" => "[[MATRICULA][OFERTA]]",
                          "variavel_matricula_curso" => "[[MATRICULA][CURSO]]",
                          "variavel_matricula_curriculo" => "[[MATRICULA][CURRICULO]]",
                          "variavel_matricula_escola" => "[[MATRICULA][POLO]]",
                          "variavel_matricula_turma" => "[[MATRICULA][TURMA]]",
                          "variavel_matricula_vendedor" => "[[MATRICULA][ATENDENTE]]",
                          "variavel_matricula_data_registro" => "[[MATRICULA][DATA_REGISTRO]]",
                          "variavel_matricula_forma_pagamento" => "[[MATRICULA][FORMA_PAGAMENTO]]",
                          "variavel_matricula_numero_contrato" => "[[MATRICULA][NUMERO_CONTRATO]]",
                          "variavel_matricula_data_matricula" => "[[MATRICULA][DATA_MATRICULA]]",
                          "variavel_matricula_data_conclusao" => "[[MATRICULA][DATA_CONCLUSAO]]",
                      )
                    ),
        "class" => "span4" //Class do atributo HTML
      ),

    )
  )
);

$config["formulario_imagem"] = array(
  array(
    "fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
    "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
    "campos" => array(
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
                "pasta" => "contratos_background",
                "download" => true,
                "excluir" => true,
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_campo" => "background", // Nome das colunas da tabela do banco de dados que retorna o valor.
                "ignorarsevazio" => true
            ),
        )
    )
);