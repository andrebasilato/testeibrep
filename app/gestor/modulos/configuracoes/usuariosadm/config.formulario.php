<?php
// Array de configuração para a formulario          
$config["formulario"] = array(
    array(
        "fieldsetid" => "dadosdousuario", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosusuarios", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario
            array(
                "id" => "form_nome", // Id do atributo HTML
                "nome" => "nome", // Name do atributo HTML
                "nomeidioma" => "form_nome", // Referencia a variavel de idioma
                "tipo" => "input", // Tipo do input
                "valor" => "nome", // Nome da coluna da tabela do banco de dados que retorna o valor.
                "validacao" => array("required" => "nome_vazio"), // Validação do campo
                "class" => "span5", //Class do atributo HTML
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_string" => true, // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "evento" => "maxlength='80'"
            ),
            array(
                "id" => "form_cpf",
                "nome" => "documento",
                "nomeidioma" => "form_cpf",
                "tipo" => "input",
                "valor" => "documento",
                "class" => "span3",
                "ajudaidioma" => "form_cpf_ajuda", //Ajuda sobre o campo (referencia a variavel de idioma)
                "evento" => "maxlength='14'",
                "validacao" => array("required" => "cpf_vazio"),
                "mascara" => "999.999.999-99", //Mascara do campo
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_php" => 'return str_replace(array(".", "-"),"","%s")', // Executa um script php antes de salvar no banco (Utilizado na função SalvarDados)
                "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
            ),
            array(
                "id" => "form_rg",
                "nome" => "rg",
                "nomeidioma" => "form_rg",
                "tipo" => "input",
                "valor" => "rg",
                //"validacao" => array("required" => "rg_vazio"),
                "class" => "span2",
                "evento" => "maxlength='20'",
                //"numerico" => true,
                "banco" => true,
                "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
              ),      
              array(
                "id" => "form_rg_orgao_emissor",
                "nome" => "rg_orgao_emissor",
                "nomeidioma" => "form_orgao_emissor",
                "tipo" => "input",
                "valor" => "rg_orgao_emissor",
                //"validacao" => array("required" => "orgao_emissor_vazio"),
                "class" => "span2",
                "evento" => "maxlength='20'",
                "banco" => true,
                "banco_string" => true // Verifica se é uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
              ),
            array(
                "id" => "nascimento",
                "nome" => "data_nasc",
                "nomeidioma" => "form_nascimento",
                "tipo" => "input",
                "valor" => "data_nasc",
                "valor_php" => 'if($dados["data_nasc"] && $dados["data_nasc"] != "0000-00-00") return formataData("%s", "br", 0)',
                "class" => "span2",
                "mascara" => "99/99/9999",
                "banco" => true,
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            array(
                "id" => "form_departamento",
                "nome" => "departamento",
                "nomeidioma" => "form_departamento",
                "tipo" => "input",
                "valor" => "departamento",
                "class" => "span5",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='40'"
            ),
            array(
                "id" => "form_funcao",
                "nome" => "funcao",
                "nomeidioma" => "form_funcao",
                "tipo" => "input",
                "valor" => "funcao",
                "class" => "span5",
                "banco" => true,
                "banco_string" => true,
                "evento" => "maxlength='70'"
            ),
            array(
                "id" => "form_telefone",
                "nome" => "telefone",
                "nomeidioma" => "form_telefone",
                "tipo" => "input",
                "valor" => "telefone",
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
            array(
                "id" => "idestado",
                "nome" => "idestado",
                "nomeidioma" => "form_idestado",
                "tipo" => "select",
                "sql" => "SELECT idestado, nome FROM estados", // SQL que alimenta o select
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
                "json_url" => '/' . $url["0"] . '/' . $url["1"] . '/' . $url["2"] . '/' . $url["3"] . "/ajax_cidades/",
                "json_input_pai_vazio" => "form_selecione_estado",
                "json_input_vazio" => "form_selecione_cidade",
                "json_campo_exibir" => "nome",
                "tipo" => "select",
                "valor" => "idcidade",
                "validacao" => array("required" => "cidade_vazio"),
                "banco" => true
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
        "fieldsetid" => "dadosdeacesso",
        "legendaidioma" => "legendadadosdeacesso",
        "campos" => array(
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
                "nomeidioma" => "form_senha",
                "tipo" => "input",
                "senha" => true,
                "ajudaidioma" => "form_senha_ajuda",
                "class" => "span3 verificaSenha",
                "validacao" => array("length>=8" => "minimo_senha", "length<=30" => "maximo_senha"),
                "legenda" => "#", // Adiciona uma legenda ao campo no formulario
                "ignorarsevazio" => true, // ignora o campo se ele for uma string para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco" => true,
                "banco_php" => 'return senhaSegura("%s","' . $config["chaveLogin"] . '")',
                "banco_string" => true,
                "evento" => "maxlength='30'"
            ),
            array(
                "id" => "form_confirma",
                "nome" => "confirma",
                "nomeidioma" => "form_confirma",
                "tipo" => "input",
                "senha" => true, // Informa que o campo é uma senha (password)
                "ajudaidioma" => "form_confirma_ajuda",
                "validacao" => array("same_as,senha" => "confirmacao_invalida"),
                "class" => "span4",
                "evento" => "maxlength='30'"
            ),
            array(
                "id" => "idperfil",
                "nome" => "idperfil",
                "nomeidioma" => "form_idperfil",
                "tipo" => "select",
                "sql" => "SELECT idperfil, nome FROM usuarios_adm_perfis where ativo = 'S' AND ativo_painel = 'S' order by nome asc", // SQL que alimenta o select
                "sql_valor" => "idperfil", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idperfil",
                "validacao" => array("required" => "perfil_vazio"),
                "referencia_label" => "cadastro_perfil",
                "referencia_link" => "/gestor/configuracoes/perfisusuarioadm",
                "banco" => true
            ),
            array(
                "id" => "form_orio",
                "nome" => "orio",
                "nomeidioma" => "form_orio",
                "tipo" => "select",
                "array" => "sim_nao",
                "class" => "span1",
                "classe_label" => "control-label",
                "valor" => "orio",
                'ignorarsevazio' => true,
                "banco" => true,
                "banco_string" => true
            ),
            array(
                "id" => "validade",
                "nome" => "validade",
                "nomeidioma" => "form_validade",
                "tipo" => "input",
                "valor" => "validade",
                "valor_php" => 'if($dados["validade"]) return formataData("%s", "br", 0)',
                "class" => "span2",
                "mascara" => "99/99/9999",
                "ajudaidioma" => "form_validade_ajuda",
                "datepicker" => true,
                "banco" => true,
                "banco_php" => 'return formataData("%s", "en", 0)',
                "banco_string" => true
            ),
            array(
                "id" => "idexcecao",
                "nome" => "idexcecao",
                "nomeidioma" => "form_idexcecao",
                "tipo" => "select",
                "sql" => "SELECT idexcecao, nome FROM excecoes where ativo='S' AND ativo_painel = 'S'", // SQL que alimenta o select
                "sql_valor" => "idexcecao", // Coluna da tabela que será usado como o valor do options
                "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                "valor" => "idexcecao",
                //"validacao" => array("required" => "excecao_vazio"),
                "referencia_label" => "cadastro_excecao",
                "referencia_link" => "/gestor/configuracoes/excecoes",
                "banco" => true,
                "banco_string" => true
            ),
        ),
    ),
    array(
        "fieldsetid" => "avatar", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legenda_avatar", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario
            array(
                "id" => "form_avatar", // Id do atributo HTML
                "nome" => "avatar", // Name do atributo HTML
                "nomeidioma" => "form_avatar", // Referencia a variavel de idioma
                "arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
                "arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
                "tipo" => "file", // Tipo do input
                "extensoes" => 'jpg|jpeg|gif|png|bmp',
                "ajudaidioma" => "form_avatar_ajuda",
                //"largura" => 350,
                //"altura" => 180,
                "validacao" => array("formato_arquivo" => "arquivo_invalido"),
                "class" => "span6", //Class do atributo HTML
                "pasta" => "usuariosadm_avatar",
                "download" => true,
                "download_caminho" => $url["0"] . "/" . $url["1"] . "/" . $url["2"] . "/" . $url["3"],
                "excluir" => true,
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_campo" => "avatar", // Nome das colunas da tabela do banco de dados que retorna o valor.
                "ignorarsevazio" => true
            ),
        )
    )
);
?>
