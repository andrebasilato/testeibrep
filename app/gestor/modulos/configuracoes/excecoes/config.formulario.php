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
                "id" => "form_url",
                "nome" => "url",
                "nomeidioma" => "form_url",
                "tipo" => "input",
                "valor" => "url",
                "validacao" => array("required" => "url_vazio"),
                "class" => "span3",
                "banco" => true,
                "banco_string" => true,
            ),
            array(
                "id" => "form_logo", // Id do atributo HTML
                "nome" => "logo", // Name do atributo HTML
                "nomeidioma" => "form_logo", // Referencia a variavel de idioma
                "arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
                "arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
                "tipo" => "file", // Tipo do input
                "extensoes" => 'jpg|jpeg|gif|png|bmp',
                "largura" => 350,
                "altura" => 180,
                "validacao" => array("formato_arquivo" => "arquivo_invalido"),
                "class" => "span6", //Class do atributo HTML
                "pasta" => "excecoes_logo",
                "download" => true,
                "excluir" => true,
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_campo" => "logo", // Nome das colunas da tabela do banco de dados que retorna o valor.
                "ignorarsevazio" => true
            ),
            array(
                "id" => "form_logo_pequena", // Id do atributo HTML
                "nome" => "logo_pequena", // Name do atributo HTML
                "nomeidioma" => "form_logo_pequena", // Referencia a variavel de idioma
                "arquivoidioma" => "arquivo_enviado", // Referencia a variavel de idioma
                "arquivoexcluir" => "arquivo_excluir", // Referencia a variavel de idioma
                "tipo" => "file", // Tipo do input
                "extensoes" => 'jpg|jpeg|gif|png|bmp',
                /*"largura" => 350,
                "altura" => 180,*/
                "diminuir_largura" => 135,
                "validacao" => array("formato_arquivo" => "arquivo_invalido"),
                "class" => "span6", //Class do atributo HTML
                "pasta" => "excecoes_logo_pequena",
                "download" => true,
                "excluir" => true,
                "banco" => true, // Verifica se é para ser salva no banco de dados (Utilizado na função SalvarDados)
                "banco_campo" => "logo_pequena", // Nome das colunas da tabela do banco de dados que retorna o valor.
                "ignorarsevazio" => true
            ),
            array(
                "id" => "form_titulo",
                "nome" => "titulo",
                "nomeidioma" => "form_titulo",
                "tipo" => "input",
                "valor" => "titulo",
                "class" => "span3",
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
?>