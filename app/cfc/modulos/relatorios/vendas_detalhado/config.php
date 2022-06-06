<?php
$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1]     = 'visualizar';

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
//if($_SESSION['usu_vendedor_instituicao'] != 'S')
    //$sqlSindicato .= ' and idsindicato in ('.$_SESSION['usu_vendedor_sindicatos'].')';
$sqlSindicato .= ' order by nome_abreviado';
//echo $sqlSindicato; exit;
//print_r2($_SESSION);
//$sqlEscola = 'select idescola, razao_social from escolas where ativo = "S"';
//if($_SESSION['usu_vendedor_instituicao'] != 'S')
    //$sqlEscola .= ' and idsindicato in ('.$_SESSION['usu_vendedor_sindicatos'].')';
//$sqlEscola .= ' order by razao_social';
//echo $sqlEscola; exit;
//print_r2($_SESSION, true);



$config['listagem'] = array(

    array(
        'id' => 'data_cad',
        'variavel_lang' => 'tabela_datacad',
        'tipo' => 'php',
        'coluna_sql' => 'ma.data_cad',
        'valor' => 'return formataData($linha["data_cad"],"br",0);',
        'tamanho' => '80',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 3
    ),
    array(
        'id' => 'idmatricula',
        'variavel_lang' => 'tabela_matricula',
        'tipo' => 'banco',
        'coluna_sql' => 'ma.idmatricula',
        'valor' => 'idmatricula',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1,
        'tamanho' => 60
    ),
    array(
        'id' => 'numero_contrato',
        'variavel_lang' => 'tabela_contrato',
        'tipo' => 'banco',
        'coluna_sql' => 'ma.numero_contrato',
        'valor' => 'numero_contrato',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 1,
        'tamanho' => 60
    ),
    array(
        'id' => 'cliente',
        'variavel_lang' => 'tabela_cliente',
        'tipo' => 'banco',
        'coluna_sql' => 'pe.nome',
        'valor' => 'cliente',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'situacao',
        'variavel_lang' => 'tabela_situacao',
        'tipo' => 'php',
        'coluna_sql' => 'ma.idsituacao',
        'valor' => 'return "<span>".$linha["situacao_wf_nome"]."</span>";',
        'busca' => true,
        'tamanho' => 100,
        'busca_tipo' => 'select',
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_sql' => 'SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo = "S"',
        'busca_sql_valor' => 'idsituacao',
        'busca_sql_label' => 'nome',
        'busca_metodo' => 1
    ),
    array(
        'id' => 'oferta',
        'variavel_lang' => 'tabela_oferta',
        'tipo' => 'banco',
        'coluna_sql' => 'o.nome',
        'valor' => 'oferta',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'curso',
        'variavel_lang' => 'tabela_curso',
        'tipo' => 'banco',
        'tamanho' => 60,
        'coluna_sql' => 'cu.nome',
        'valor' => 'curso',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'turma',
        'variavel_lang' => 'tabela_turma',
        'tipo' => 'banco',
        'coluna_sql' => 'ma.idturma',
        'valor' => 'turma',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'valor_contrato',
        'variavel_lang' => 'valor_contrato',
        'tipo' => 'php',
        'valor' => 'return number_format($linha["valor_contrato"], 2, ",", ".");',
        'busca' => false
    )   ,
    array(
        'id' => 'forma_pagamento',
        'variavel_lang' => 'tabela_forma_pagamento',
        'tipo' => 'php',
        'valor' => ' return $GLOBALS["forma_pagamento_conta"]["pt_br"][$linha["forma_pagamento"]]; ',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),


    array(
        'id' => 'quantidade_parcelas',
        'variavel_lang' => 'tabela_quantidade_parcelas',
        'tipo' => 'banco',
        'coluna_sql' => 'ma.quantidade_parcelas',
        'valor' => 'quantidade_parcelas',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),
    array(
        'id' => 'vendedor',
        'variavel_lang' => 'tabela_vendedor',
        'tipo' => 'banco',
        'coluna_sql' => 've.nome',
        'valor' => 'vendedor',
        'busca' => true,
        'busca_class' => 'inputPreenchimentoCompleto',
        'busca_metodo' => 2
    ),




);


// Array de configuração para a formulario
$config['formulario'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(

            array(
                'id' => 'form_tipo_data_filtro',
                'nome' => 'q[de_ate|tipo_data_filtro|ma.data_matricula]',
                'tipo' => 'select',
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_filtro',
                'banco' => true,
                'iddiv' => 'de',
                'iddiv2' => 'ate',
                'iddivs' => array(
                    'de',
                    'ate'
                ),
                'iddiv_obr' => true,
                'validacao' => array(
                    'required' => 'tipo_data_filtro_vazio'
                ),
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
                'nomeidioma' => 'form_de',
                "validacao" => array("required" => "de_vazio"),
                'datepicker' => true,
                'input_hidden' => true,
                "mascara" => "99/99/9999"
            ),
            array(
                'id' => 'form_ate',
                'nome' => 'ate',
                'valor' => 'ate',
                'tipo' => 'input',
                'class' => 'span2',
                'nomeidioma' => 'form_ate',
                "validacao" => array("required" => "ate_vazio"),
                'datepicker' => true,
                'input_hidden' => true,
                "mascara" => "99/99/9999"
            ),

            /*array(
                'id' => 'idmantenedoras',
                'sql' => 'SELECT idmantenedora, razao_social FROM mantenedoras WHERE ativo="S" ORDER BY razao_social',
                'nome' => 'q[1|mant.idmantenedora]',
                'tipo' => 'select',
                'valor' => 'idmantenedora',
                'class' => 'span5',
                'sql_valor' => 'idmantenedora',
                'sql_label' => 'razao_social',
                'nomeidioma' => 'form_mantenedoras',
                'sql_filtro' => 'select * from mantenedoras where ativo="S" and idmantenedora=%',
                'sql_filtro_label' => 'nome'
            ),*/

            array(
                'id' => 'idinstituicao',
                "sql" => $sqlSindicato,
                "nome" => "q[1|ma.idinstituicao]",
                "tipo" => "select",
                "valor" => "idinstituicao",
                "class" => "span5",
                "sql_valor" => "idinstituicao",
                "sql_label" => "nome_abreviado",
                // "validacao" => array("required" => "tipo_data_filtro_vazio"),
                "nomeidioma" => "form_instituicao_filtro",
                "sql_filtro" => "select * from sindicatos where ativo='S' and idinstituicao=%",
                "sql_filtro_label" => "nome_abreviado"
            ),
/*
            array(
                'id' => 'idescola',
                "sql" => $sqlEscola,
                "nome" => "q[1|ma.idescola]",
                "tipo" => "select",
                "valor" => "idescola",
                "class" => "span5",
                "sql_valor" => "idescola",
                "sql_label" => "razao_social",
                "nomeidioma" => "form_escola",
                "sql_filtro" => "select * from escolas where ativo='S' and idescola=%",
                "sql_filtro_label" => "razao_social"
            ),*/

            array(
                'id' => 'idturma',
                "sql" => "SELECT idturma, nome FROM ofertas_turmas WHERE ativo='S' ORDER BY nome",
                "nome" => "q[1|ma.idturma]",
                "tipo" => "select",
                "valor" => "idescola",
                "class" => "span5",
                "sql_valor" => "idturma",
                "sql_label" => "nome",
                "nomeidioma" => "form_turma",
                "sql_filtro" => "select * from ofertas_turmas where ativo='S' and idturma=%",
                "sql_filtro_label" => "nome"
            ),


            array(
                'id' => 'idoferta',
                'sql' => "SELECT idoferta, nome FROM ofertas WHERE ativo='S' ORDER BY nome",
                'nome' => 'q[1|ma.idoferta]',
                'tipo' => 'select',
                'valor' => 'idoferta',
                'class' => 'span5',
                'sql_label' => 'nome',
                'sql_valor' => 'idoferta',
                'nomeidioma' => 'form_idoferta',
                'sql_filtro' => 'select * from ofertas where idoferta=%',
                'sql_filtro_label' => 'nome'
            ),


            array(
                'id' => 'idcurso',
                "sql" => "SELECT idcurso, nome FROM cursos WHERE ativo='S' ORDER BY nome",
                'nome' => 'q[1|ma.idcurso]',
                "tipo" => "select",
                "valor" => "idcurso",
                "class" => "span5",
                "sql_valor" => "idcurso",
                "sql_label" => "nome",
                // "validacao" => array("required" => "tipo_data_filtro_vazio"),
                "nomeidioma" => "form_idcurso",
                "sql_filtro" => "select * from cursos where ativo='S' and idcurso=%",
                "sql_filtro_label" => "nome"
            ),

/*
            array(
                'id' => 'idgrupo',
                'sql' => "SELECT idgrupo, nome FROM grupos_vendedores WHERE ativo='S' ORDER BY nome",
                'nome' => 'q[1|vegru.idgrupo]',
                'tipo' => 'select',
                'valor' => 'idgrupo',
                'class' => 'span5',
                'sql_label' => 'nome',
                'sql_valor' => 'idgrupo',
                'nomeidioma' => 'form_idgrupo',
                'sql_filtro' => 'select * from grupos_vendedores where idgrupo=%',
                'sql_filtro_label' => 'nome'
            ),


            array(
                'id' => 'idvendedor',
                'sql' => "SELECT idvendedor, nome FROM vendedores WHERE ativo='S' ORDER BY nome",
                'nome' => 'q[1|ma.idvendedor]',
                'tipo' => 'select',
                'valor' => 'idvendedor',
                'class' => 'span5',
                'sql_label' => 'nome',
                'sql_valor' => 'idvendedor',
                'nomeidioma' => 'form_idvendedor',
                'sql_filtro' => 'select * from vendedores where idvendedor=%',
                'sql_filtro_label' => 'nome'
            ),
            */

          array(
                "id" => "forma_pagamento",
                "nome" => "q[1|ma.forma_pagamento]",
                "nomeidioma" => "form_forma_pagamento",
                "tipo" => "select",
                "array" => "forma_pagamento_conta", // Array que alimenta o select
                "class" => "span2",
                "valor" => "forma_pagamento",
                "sql_filtro" => "array", //PARA PEGAR ARRAY DO CONFIG
                "sql_filtro_label" => "forma_pagamento"
                ),

            array(
                'id' => 'form_quantidade_parcelas',
                'nome' => 'q[1|ma.quantidade_parcelas]',
                'tipo' => 'input',
                'class' => 'span1',
                'nomeidioma' => 'form_quantidade_parcelas'
            ),


      array(
        "id" => "form_idsituacao",
        "nome" => "idsituacao",
        "nomeidioma" => "form_idsituacao",
        "tipo" => "checkbox",
        "sql" => "select idsituacao, nome from matriculas_workflow where ativo = 'S'", // SQL que alimenta o select
        "sql_ordem_campo" => "nome",
        "sql_ordem" => "asc",
        "sql_valor" => "idsituacao", // Coluna da tabela que será usado como o valor do options
        "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
        "valor" => "idsituacao",
        "sql_filtro" => "select * from matriculas_workflow where idsituacao = %",
        "sql_filtro_label" => "nome"
      ),



        )
    )
);