<?php

// Array de configuração para a formulario
$config["formulario"] = array(
    array(
        "fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array(// Campos do formulario
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
                "evento" => "maxlength='100'"
            ),
            array(
                "id" => "form_abreviacao",
                "nome" => "abreviacao",
                "nomeidioma" => "form_abreviacao",
                "tipo" => "input",
                "valor" => "abreviacao",
                "class" => "span2",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_codigo", // Id do atributo HTML
                "nome" => "codigo", // Name do atributo HTML
                "nomeidioma" => "form_codigo", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                //"numerico" => true,
                "valor" => "codigo", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao" => array("required" => "codigo_vazio"), // Validação do campo
                "class" => "span2", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "evento" => "maxlength='10'"
            ),
            array(
                "id" => "form_tipo",
                "nome" => "tipo",
                "nomeidioma" => "form_tipo",
                "tipo" => "select",
                "array" => "tipo_curso", // Array que alimenta o select
                "class" => "span2",
                "valor" => "tipo",
                "validacao" => array("required" => "tipo_vazio"),
                //"ajudaidioma" => "form_ativo_ajuda",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_usar_datavalid",
                "nome" => "usar_datavalid",
                "nomeidioma" => "form_validar_datavalid",
                "tipo" => "select",
                "array" => "sim_nao", // Array que alimenta o select
                "class" => "span2",
                "valor" => "usar_datavalid",
                "validacao" => array("required" => "required_datavalid"),
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
                "validacao" => array("valid_email" => "email_invalido"),
                "class" => "span5",
                "legenda" => "@",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='100'"
            ),
            array(
                "id" => "form_carga_horaria_presencial", // Id do atributo HTML
                "nome" => "carga_horaria_presencial", // Name do atributo HTML
                "nomeidioma" => "form_carga_horaria_presencial", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "numerico" => true,
                "valor" => "carga_horaria_presencial", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "carga_horaria_vazio"), // Validação do campo
                "class" => "span2", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "evento" => "maxlength='10'"
            ),
            array(
                "id" => "form_carga_horaria_distancia", // Id do atributo HTML
                "nome" => "carga_horaria_distancia", // Name do atributo HTML
                "nomeidioma" => "form_carga_horaria_distancia", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "numerico" => true,
                "valor" => "carga_horaria_distancia", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "pratica_vazio"), // Validação do campo
                "class" => "span2", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "evento" => "maxlength='10'"
            ),
            array(
                "id" => "form_carga_horaria_total", // Id do atributo HTML
                "nome" => "carga_horaria_total", // Name do atributo HTML
                "nomeidioma" => "form_carga_horaria_total", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "numerico" => true,
                "valor" => "carga_horaria_total", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "teorica_vazio"), // Validação do campo
                "class" => "span2", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "evento" => "maxlength='10'"
            ),
            array(
                "id" => "form_ordem",
                "nome" => "ordem",
                "nomeidioma" => "form_ordem",
                "tipo" => "input",
                "valor" => "ordem",
                "class" => "span1",
                "banco" => true,
                "numerico" => true,
                "evento" => "maxlength='2'",
                "banco_string" => true,
            ),
            array(
                "id" => "form_acesso_simultaneo",
                "nome" => "acesso_simultaneo",
                "nomeidioma" => "form_acesso_simultaneo",
                "tipo" => "select",
                "sem_primeira_linha" => true,
                "array" => "sim_nao", // Array que alimenta o select
                "valor" => "acesso_simultaneo",
                "class" => "span1",
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
        )
    )
);

$config["formulario_ava"] = array(
    array(
        "fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array(// Campos do formulario
            array(
                "id" => "form_dias_acesso_ava", // Id do atributo HTML
                "nome" => "dias_acesso_ava", // Name do atributo HTML
                "nomeidioma" => "form_dias_acesso_ava", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "numerico" => true,
                "valor" => "dias_acesso_ava", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "dias_acesso_ava_vazio"), // Validação do campo
                "class" => "span2", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "evento" => "maxlength='4'"
            ),
            array(
                "id" => "form_percentual_ideal_ava", // Id do atributo HTML
                "nome" => "percentual_ideal_ava", // Name do atributo HTML
                "nomeidioma" => "form_percentual_ideal_ava", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "valor" => "percentual_ideal_ava", // Nome da coluna da tabela do banco de dados que retorna o valor.
                //"validacao" => array("required" => "dias_acesso_ava_vazio"), // Validação do campo
                "ajudaidioma" => "form_percentual_ideal_ava_ajuda",
                "decimal" => true,
                "class" => "span2", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "evento" => "maxlength='6'"
            ),
            array(
                "id" => "form_imagem_exibicao", // Id do atributo HTML
                "nome" => "imagem_exibicao", // Name do atributo HTML
                "nomeidioma" => "form_imagem_exibicao", // Referencia a variavel de idioma
                "arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
                "arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
                "tipo" => "file", // Tipo do input
                "extensoes" => 'jpg|jpeg|gif|png|bmp',
                "ajudaidioma" => "form_imagem_exibicao_ajuda",
                //"largura" => 350,
                //"altura" => 180,
                "validacao" => array("formato_arquivo" => "arquivo_invalido"),
                "class" => "span6", //Class do atributo HTML
                "pasta" => "cursos_imagem_exibicao",
                "download" => true,
                "download_caminho" => $url["0"] . "/" . $url["1"] . "/" . $url["2"] . "/" . $url["3"],
                "excluir" => true,
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_campo" => "imagem_exibicao", // Nome das colunas da tabela do banco de dados que retorna o valor.
                "ignorarsevazio" => true
            ),
        )
    )
);

$config["formulario_cursosindicato"] = array(
    array(
        "fieldsetid" => "dadosdainformacao",
        "legendaidioma" => "legenda_dadosdainformacao",
        "campos" => array(
            array(
                "id" => "idcertificado",
                "nome" => "idcertificado",
                "nomeidioma" => "form_idcertificado",
                "tipo" => "select",
                "sql" => "SELECT idcertificado, nome FROM certificados WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome ", // SQL que alimenta o select
                "sql_valor" => "idcertificado", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idcertificado",
                //"validacao" => array("required" => "logradouro_vazio"),
                "banco" => true,
                "banco_string" => true,
                "ignorarsevazio" => false,
            ),
            array(
                "id" => "idhistorico_escolar",
                "nome" => "idhistorico_escolar",
                "nomeidioma" => "form_idhistorico_escolar",
                "tipo" => "select",
                "sql" => "SELECT idhistorico_escolar, nome FROM historico_escolar WHERE ativo = 'S' AND ativo_painel = 'S' ORDER BY nome ", // SQL que alimenta o select
                "sql_valor" => "idhistorico_escolar", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idhistorico_escolar",
                //"validacao" => array("required" => "logradouro_vazio"),
                "banco" => true,
                "banco_string" => true,
                "ignorarsevazio" => false,
            ),
            array(
                "id" => "form_certificado_ava",
                "nome" => "certificado_ava",
                "nomeidioma" => "form_certificado_ava",
                "tipo" => "select",
                "array" => "sim_nao", // Array que alimenta o select
                "class" => "span2",
                "valor" => "certificado_ava",
                //"validacao" => array("required" => "certificado_ava_vazio"),
                "ajudaidioma" => "form_certificado_ava_ajuda",
                "botao_hide" => true,
                "iddiv" => "renach_obrigatorio",
                "iddiv_obr" => true,
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_renach_obrigatorio",
                "nome" => "renach_obrigatorio",
                "nomeidioma" => "form_renach_obrigatorio",
                "tipo" => "select",
                "array" => "sim_nao", // Array que alimenta o select
                "class" => "span2",
                "valor" => "renach_obrigatorio",
                "validacao" => array("required" => "renach_obrigatorio_vazio"),
                "ajudaidioma" => "form_renach_obrigatorio_ajuda",
                "banco" => true,
                "banco_string" => true,
                "select_hidden" => true,
            ),
            array(
                "id" => "form_homologar_certificado",
                "nome" => "homologar_certificado",
                "nomeidioma" => "form_homologar_certificado",
                "tipo" => "select",
                "array" => "sim_nao",
                "class" => "span2",
                "valor" => "homologar_certificado",
                "ajudaidioma" => "form_homologar_certificado_ajuda",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "fundamentacao",
                "nome" => "fundamentacao",
                "nomeidioma" => "form_fundamentacao",
                "tipo" => "text",
                //"editor" => true,
                "valor" => "fundamentacao",
                "class" => "span4",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "fundamentacao_legal",
                "nome" => "fundamentacao_legal",
                "nomeidioma" => "form_fundamentacao_legal",
                "tipo" => "text",
                //"editor" => true,
                "valor" => "fundamentacao_legal",
                "class" => "span4",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "autorizacao",
                "nome" => "autorizacao",
                "nomeidioma" => "form_autorizacao",
                "tipo" => "text",
                //"editor" => true,
                "valor" => "autorizacao",
                "class" => "span4",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "perfil",
                "nome" => "perfil",
                "nomeidioma" => "form_perfil",
                "tipo" => "text",
                //"editor" => true,
                "valor" => "perfil",
                "class" => "span4",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "regulamento",
                "nome" => "regulamento",
                "nomeidioma" => "form_regulamento",
                "tipo" => "text",
                //"editor" => true,
                "valor" => "regulamento",
                "class" => "span4",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "form_botao_variaveis", // Id do atributo HTML
                "nome" => "botao_variaveis", // Name do atributo HTML
                "nomeidioma" => "form_botao_variaveis", // Referencia a variavel de idioma
                "tipo" => "php", // Tipo do input
                "colunas" => 2,
                "botao_hide" => true,
                "valor" => array(
                    array(
                        "variavel_nome" => "[[NOME_ALUNO]]",
                        "variavel_id" => "[[ID_ALUNO]]",
                        "variavel_email" => "[[EMAIL_ALUNO]]",
                        "variavel_cpf_cnpj" => "[[CPF_CNPJ_ALUNO]]",
                        "variavel_matricula" => "[[MATRICULA]]",
                        "variavel_curso" => "[[CURSO]]",
                        "variavel_oferta" => "[[OFERTA]]",
                        "variavel_escola" => "[[POLO]]",
                        "variavel_sindicato" => "[[INSTITUICAO]]",
                        "variavel_numero_contrato" => "[[NUMERO_CONTRATO]]",
                        "variavel_valor_contrato" => "[[VALOR_CONTRATO]]",
                        "variavel_forma_pagamento" => "[[FORMA_PAGAMENTO]]",
                        "variavel_quantidade_parcelas" => "[[QUANTIDADE_PARCELAS]]",
                        "variavel_link_ambiente_aluno" => "[[LINK_AMBIENTE_ALUNO]]",
                    )
                ),
                "class" => "span4" //Class do atributo HTML
            ),
            array(
                "id" => "email_boas_vindas_sindicato",
                "nome" => "email_boas_vindas_sindicato",
                "nomeidioma" => "form_email_boas_vindas_sindicato",
                "tipo" => "text",
                "editor" => true,
                "valor" => "email_boas_vindas_sindicato",
                "class" => "span4",
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "sms_boas_vindas_sindicato",
                "nome" => "sms_boas_vindas_sindicato",
                "nomeidioma" => "form_sms_boas_vindas_sindicato",
                "tipo" => "text",
                "valor" => "sms_boas_vindas_sindicato",
                "ajudaidioma" => "sms_boas_vindas_sindicato_ajuda",
                "class" => "span4",
                "banco" => true,
                "banco_string" => true
            ),
        )
    )
);
?>
