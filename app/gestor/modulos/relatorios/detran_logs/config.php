<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

// Array de configuração para a listagem	
$config["listagem"] = array(

    array("id" => "tabela_idlog",
        "variavel_lang" => "tabela_idlog",
        "tipo" => "banco",
        "coluna_sql" => "idlog",
        "valor" => "idlog"),

    array("id" => "tabela_idmatricula",
        "variavel_lang" => "tabela_idmatricula",
        "tipo" => "banco",
        "coluna_sql" => "idmatricula",
        "valor" => "idmatricula"),

    array("id" => "tabela_data_cad",
        "variavel_lang" => "tabela_data_cad",
        "tipo" => "php",
        "coluna_sql" => "data_cad",
        "valor" => 'return formataData($linha["data_cad"],"br",1);'),

    array("id" => "tabela_cod_transacao",
        "variavel_lang" => "tabela_cod_transacao",
        "tipo" => "banco",
        "coluna_sql" => 'cod_transacao',
        "valor" => 'cod_transacao'),

    array("id" => "tabela_retorno",
        "variavel_lang" => "tabela_retorno",
        "tipo" => "banco",
        "coluna_sql" => 'retorno',
        "valor" => 'retorno'
    ),

    array("id" => "tabela_string_envio",
        "variavel_lang" => "tabela_string_envio",
        "tipo" => "banco",
        "coluna_sql" => 'string_envio',
        "valor" => 'string_envio'
    ));


// Array de configuração para a formulario
$config["formulario"] = array(
    array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario
            array(
                "id" => "form_idmatricula",
                "nome" => "q[1|idmatricula]",
                "nomeidioma" => "form_idmatricula",
                "tipo" => "input",
                "valor" => "idmatricula",
                "class" => "span2",
                "evento" => "maxlength='20'",
                "numerico" => true,
                "validacao" => array("required" => "matricula_vazio"),
            ),
            array(
                'id' => 'form_tipo_data_filtro',
                'nome' => 'q[de_ate|tipo_data_filtro|data_cad]',
                'tipo' => 'select',
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_filtro',
                'banco' => true,
                'iddiv' => 'de',
                'iddiv2' => 'ate',
                'iddivs' => array('de', 'ate'),
                'iddiv_obr' => true,
                //'validacao' => array('required' => 'tipo_data_filtro_vazio'),
                'nomeidioma' => 'form_tipo_data_filtro',
                'botao_hide' => true,
                'iddiv2_obr' => true,
                'sql_filtro' => 'array',
                'banco_string' => true,
                'sql_filtro_label' => 'tipo_data_filtro'
            ),
            array(
                'id' => 'form_de',
                'nome' => 'de',
                'valor' => 'de',
                'tipo' => 'input',
                'class' => 'span2',
                "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
                //"validacao" => array("required" => "de_vazio"),
                'nomeidioma' => 'form_de',
                'mascara' => '99/99/9999',
                'datepicker' => true,
                'input_hidden' => true
            ),
            array(
                'id' => 'form_ate',
                'nome' => 'ate',
                'valor' => 'ate',
                'tipo' => 'input',
                'class' => 'span2',
                "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
                //"validacao" => array("required" => "ate_vazio"),
                'nomeidioma' => 'form_ate',
                'mascara' => '99/99/9999',
                'datepicker' => true,
                'input_hidden' => true
            ),

        )
    )
);
?>