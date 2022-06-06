<?php

$config["funcionalidade"] = "funcionalidade";
$config["acoes"][1] = "visualizar";

$config["banco"] = array("tabela" => "pessoas",
    "primaria" => "idpessoa",
);

// Array de configuração para a listagem
$config["listagem"] = array(


    array("id" => "tabela_numero", // Id do atributo
        "variavel_lang" => "tabela_numero", // Referencia a variavel de idioma
        "tipo" => "banco", // Referencia ao tipo do campo (banco => o valor vem do banco de dados, php => é executado um codigo php que retorna o valor)
        "coluna_sql" => "mah.idhistorico", // Nome da coluna no banco de dados
        "valor" => 'idhistorico',
        "tamanho" => "60"
    ),
    array("id" => "tabela_id_ava",
        "variavel_lang" => "tabela_id_ava",
        "tipo" => "banco",
        "coluna_sql" => "a.idava",
        "valor" => "idava"),
    array("id" => "tabela_ava",
        "variavel_lang" => "tabela_ava",
        "tipo" => "banco",
        "coluna_sql" => "a.nome",
        "valor" => "ava"),
    array("id" => "tabela_acao",
        "variavel_lang" => "tabela_acao",
        "tipo" => "banco",
        "coluna_sql" => "mah.acao",
        "valor" => "acao"),

    array("id" => "tabela_oque",
        "variavel_lang" => "tabela_oque",
        "tipo" => "php",
        "coluna_sql" => 'mah.oque',
        "valor" => 'return $linha["oque"];'),

    array(
        'id' => 'tabela_qual',
        'variavel_lang' => 'tabela_qual',
        'tipo' => 'php',
        'coluna_sql' => 'mah.qual',
        'valor' => 'return $linha["id"];',
        'tamanho' => '80',
    ),

    array("id" => "tabela_qtd_corretas",
        "variavel_lang" => "tabela_qtd_corretas",
        "coluna_sql" => "msi.total_corretas",
        "tipo" => "php",
        "valor" => 'if ($linha["total_perguntas_corretas"]) {
                                                  return  $linha["total_perguntas_corretas"]."/".$linha["total_perguntas"];
                                               } else {
                                                  return "--";
                                               }',
        "tamanho" => "100"),

    array("id" => "tabela_data_registro",
        "variavel_lang" => "tabela_data_registro",
        "tipo" => "php",
        "valor" => 'return formataData($linha["data_cad"],"br",1);',
        "tamanho" => "160"),

);


// Array de configuração para a formulario
$config["formulario"] = array(
    array("fieldsetid" => "dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
        "legendaidioma" => "legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
        "campos" => array( // Campos do formulario

            array(
                "id" => "form_matricula",
                "nome" => "q[1|mah.idmatricula]",
                "nomeidioma" => "form_matricula",
                "tipo" => "input",
                "valor" => "matricula",
                "class" => "span2",
                "evento" => "maxlength='100'",
                "numerico" => true,
                "validacao" => array("required" => "matricula_vazio"),
            ),
            array(
                "id" => "form_nome",
                "nome" => "q[|p.nome]",
                "nomeidioma" => "form_nome",
                "tipo" => "hidden",
                "valor" => "nome",
                "class" => "span5",
                "evento" => "maxlength='100'"
            ),
            /*array(
                  "id" => "estado_civil",
                  "nome" => "q[1|p.estado_civil]",
                  "nomeidioma" => "form_estadocivil",
                  "tipo" => "select",
                  "array" => "estadocivil", // Array que alimenta o select
                  "class" => "span2",
                  "valor" => "estado_civil",
                  "sql_filtro" => "array", //PARA PEGAR ARRAY DO CONFIG
                  "sql_filtro_label" => "estadocivil"
                  ),
           array(
                  "id" => "idestado",
                  "nome" => "q[1|p.idestado]",
                  "nomeidioma" => "form_idestado",
                  "tipo" => "select",
                  "sql" => "SELECT idestado, nome FROM estados ORDER BY nome", // SQL que alimenta o select
                  "sql_valor" => "idestado", // Coluna da tabela que será usado como o valor do options
                  "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
                  "valor" => "idestado",
                  "sql_filtro" => "select * from estados where idestado=%",
                  "sql_filtro_label" => "nome"
                  ),
           array(
                  "id" => "idcidade",
                  "nome" => "q[1|p.idcidade]",
                  "nomeidioma" => "form_idcidade",
                  "json" => true,
                  "json_idpai" => "idestado",
                  "json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_cidades/",
                  "json_input_pai_vazio" => "form_selecione_estado",
                  "json_input_vazio" => "form_selecione_cidade",
                  "json_campo_exibir" => "nome",
                  "tipo" => "select",
                  "valor" => "idcidade",
                  "sql_filtro" => "select * from cidades where idcidade=%",
                  "sql_filtro_label" => "nome"
                  ),
           array(
                  "id" => "idsindicato",
                  "nome" => "idsindicato",
                  "nomeidioma" => "form_idsindicato",
                  "tipo" => "select",
                  "sql" => "SELECT idsindicato, nome_abreviado FROM sindicatos where ativo = 'S' ORDER BY nome_abreviado", // SQL que alimenta o select
                  "sql_valor" => "idsindicato", // Coluna da tabela que será usado como o valor do options
                  "sql_label" => "nome_abreviado", // Coluna da tabela que será usado como o label do options
                  "valor" => "idsindicato",
                  "sql_filtro" => "select * from sindicatos where idsindicato=%",
                  "sql_filtro_label" => "nome_abreviado"
                  ),*/

            array(
                'id' => 'form_tipo_data_filtro',
                'nome' => 'q[de_ate|tipo_data_filtro|mah.data_cad]',
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
