<?php
$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1] = 'visualizar';

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';
$sqlSindicato .= ' order by nome_abreviado';

// Array de configuração para a listagem
$config["listagem"] = array(
    array(
        'id' => 'idvendedor',
        'variavel_lang' => 'tabela_vendedor',
        'tipo' => 'banco',
        'coluna_sql' => 've.nome',
        'valor' => 'vendedor',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => "idvisita",
        "tipo" => "banco",
        "valor" => "idvisita",
        "busca" => true,
        "tamanho" => 40,
        "coluna_sql" => "idvisita",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 1,
        "variavel_lang" => "tabela_idvisita",
    ),
    array(
        'id' => 'data_cad',
        'tipo' => 'php',
        'valor' => ' return formataData($linha["data_cad"],"br",1); ',
        'busca' => true,
        'coluna_sql' => 'data_cad',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1,
        'variavel_lang' => 'tabela_data'
    ),
    array(
        'id' => 'nome',
        'tipo' => 'php',
        'valor' => 'return $linha["nome"]; ',
        'busca' => true,
        'evento' => "maxlength='100'",
        'coluna_sql' => 'nome',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2,
        'variavel_lang' => 'tabela_nome'
    ),
    array(
        'id' => 'documento',
        'tipo' => 'php',
        'valor' => 'return formatar($linha["documento"],"cpf");',
        'busca' => true,
        'evento' => "maxlength='11'",
        'coluna_sql' => 'documento',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2,
        'variavel_lang' => 'tabela_documento'
    ),
    array(
        "id" => "email",
        "tipo" => "banco",
        "valor" => "email",
        "busca" => true,
        "evento" => "maxlength='100'",
        "coluna_sql" => "pe.email",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2,
        "variavel_lang" => "tabela_email",
    ),

    array(
        "id" => "telefone",
        "tipo" => "banco",
        "valor" => "telefone",
        "busca" => true,
        "evento" => "maxlength='100'",
        "coluna_sql" => "pe.telefone",
        "busca_class" => "inputPreenchimentoCompleto",
        "busca_metodo" => 2,
        "variavel_lang" => "tabela_telefone",
    ),

    array(
        'id' => 'midia',
        'tipo' => 'banco',
        'valor' => 'midia',
        'busca' => true,
        'coluna_sql' => 'miv.nome',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2,
        'variavel_lang' => 'tabela_midia',
    ),

    array(
        'id' => 'curso',
        'tipo' => 'banco',
        'valor' => 'curso',
        'busca' => true,
        'coluna_sql' => 'cu.nome',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2,
        'variavel_lang' => 'tabela_curso',
    ),

    array(
        'id' => 'localdevisita',
        'tipo' => 'banco',
        'valor' => 'local',
        'busca' => true,
        'coluna_sql' => 'lov.nome',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2,
        'variavel_lang' => 'tabela_localdevisita',
    ),

);


// Array de configuração para a formulario
$config['formulario'] = array(
array(
    'fieldsetid' => 'dadosdoobjeto',
    'legendaidioma' => 'legendadadosdados',
    'campos' => array(
        array(
            "id" => "form_tipo_data_filtro",
            "nome" => "q[de_ate|tipo_data_filtro|vv.data_cad]",
            "nomeidioma" => "form_tipo_data_filtro",
            "botao_hide" => true,
            "iddivs" => array("de","ate"),
            "tipo" => "select",
            "iddiv" => "de",
            "iddiv2" => "ate",
            "iddiv_obr" => true,
            "iddiv2_obr" => true,
            "array" => "tipo_data_filtro", // Array que alimenta o select
            "class" => "span3",
            "valor" => "tipo_data_filtro",
            "validacao" => array("required" => "tipo_data_filtro_vazio"),
            "banco" => true,
            "banco_string" => true,
            "sql_filtro" => "array",
            "sql_filtro_label" => "tipo_data_filtro"
        ),
        array(
            "id" => "form_de",
            "nome" => "de",
            "nomeidioma" => "form_de",
            "tipo" => "input",
            "class" => "span2",
            "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
            "validacao" => array("required" => "de_vazio"),
            "datepicker" => true,
            "input_hidden" => true,
        ),
        array(
            "id" => "form_ate",
            "nome" => "ate",
            "nomeidioma" => "form_ate",
            "tipo" => "input",
            "class" => "span2",
            "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
            "validacao" => array("required" => "ate_vazio"),
            "datepicker" => true,
            "input_hidden" => true,
        ),
        array(
            'id' => 'idsindicato',
            "sql" => $sqlSindicato,
            "nome" => "q[1|vi.idsindicato]",
            "tipo" => "select",
            "valor" => "idsindicato",
            "class" => "span5",
            "sql_valor" => "idsindicato",
            "sql_label" => "nome_abreviado",
            // "validacao" => array("required" => "tipo_data_filtro_vazio"),
            "nomeidioma" => "form_sindicato_filtro",
            "sql_filtro" => "select * from sindicatos where ativo='S' and idsindicato=%",
            "sql_filtro_label" => "nome_abreviado"
        ),


        array(
            'id' => 'idvendedor',
            'nome' => 'q[1|vv.idvendedor]',
            'nomeidioma' => 'form_idvendedor',
            'tipo' => 'select',
            'sql' => 'SELECT idvendedor, nome FROM vendedores WHERE ativo="S" ORDER BY nome', // SQL que alimenta o select
            'sql_valor' => 'idvendedor', // Coluna da tabela que será usado como o valor do options
            'sql_label' => 'nome', // Coluna da tabela que será usado como o label do options
            'valor' => 'idvendedor',
            'class' => 'span5',
            'sql_filtro' => 'select * from vendedores where ativo="S" and idvendedor=%',
            'sql_filtro_label' => 'nome'
        ),

            array(
                "id" => "idcurso",
                "nome" => "q[1|cu.idcurso]",
                "nomeidioma" => "form_idcurso",
                "tipo" => "select",
                "sql" => "SELECT idcurso, nome FROM cursos WHERE ativo='S' ORDER BY nome",
                "sql_valor" => "idcurso",
                "sql_label" => "nome",
                "valor" => "idcurso",
                "class" => "span5",
                "sql_filtro" => "select * from cursos where ativo='S' and idcurso=%",
                "sql_filtro_label" => "nome"
            ),
            /*array(
                "id" => "idgrupo",
                "nome" => "q[1|grupo.idgrupo]",
                "nomeidioma" => "form_idgrupo",
                "tipo" => "select",
                "sql" => "SELECT idgrupo, nome FROM grupos_vendedores WHERE ativo='S' ORDER BY nome",
                "sql_valor" => "idgrupo",
                "sql_label" => "nome",
                "valor" => "idgrupo",
                "class" => "span5",
                "sql_filtro" => "select * from grupos_vendedores where ativo='S' and idgrupo=%",
                "sql_filtro_label" => "nome"
            ),*/
            array(
                'id' => 'idestado',
                "sql" => "SELECT idestado, nome FROM estados ORDER BY nome",
                "nome" => "q[1|e.idestado]",
                "tipo" => "select",
                "valor" => "idestado",
                "class" => "span5",
                "sql_valor" => "idestado",
                "sql_label" => "nome",
                "nomeidioma" => "form_estado_filtro",
                "sql_filtro" => "select * from estados where idestado=%",
                "sql_filtro_label" => "nome"
            ),
            array(
                'id' => 'idcidade',
                "nome" => "q[1|c.idcidade]",
                "tipo" => "select",
                "valor" => "idcidade",
                "class" => "span5",
                "nomeidioma" => "form_cidade_filtro",
                "sql_filtro_label" => "nome"
            ),
        )
    )
);