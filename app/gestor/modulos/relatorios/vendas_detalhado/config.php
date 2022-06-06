<?php
$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1]     = 'visualizar';

$sqlSindicato = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlSindicato .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';
$sqlSindicato .= ' order by nome_abreviado';


$sqlSindicatoC = 'select idsindicato, nome_abreviado from sindicatos where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlSindicatoC .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';
// $sqlSindicatoC .= ' order by nome_abreviado';

$idescola = 'idescola';

$sqlEscola = 'select idescola, razao_social from escolas where ativo = "S"';
if($_SESSION['adm_gestor_sindicato'] != 'S')
    $sqlEscola .= ' and idsindicato in ('.$_SESSION['adm_sindicatos'].')';
// $sqlEscola .= ' order by razao_social';

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
        'id' => 'solicitante',
        'variavel_lang' => 'tabela_solicitante',
        'tipo' => 'banco',
        'busca_class' => 'inputPreenchimentoCompleto',
        'sql_filtro' => 'SELECT idsolicitante, nome FROM solicitantes_bolsas WHERE ativo = "S" AND idsolicitante = %',
        'busca_sql_valor' => 'idsolicitante',
        'busca_sql_label' => 'nome'
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
                'nome' => 'q[de_ate|tipo_data_filtro|ma.data_registro]',
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
                "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
                "validacao" => array("required" => "de_vazio"),
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
                'nomeidioma' => 'form_ate',
                "evento" => "onchange='validaIntervaloDatasUmAno(\"form_de\",\"form_ate\")'",
                "validacao" => array("required" => "ate_vazio"),
                'mascara' => '99/99/9999',
                'datepicker' => true,
                'input_hidden' => true
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
                'id' => 'idsindicato',
                "sql" => $sqlSindicatoC,
                "nome" => "idsindicato",
                "tipo" => "checkbox",
                "valor" => "idsindicato",
                "class" => "span5",
                "nome_idioma"=> "form_sindicato_filtro",
                "nomeidioma"=> "form_sindicato_filtro",
                "sql_ordem" => "asc",
                "sql_valor" => "idsindicato",
                "sql_label" => "nome_abreviado",
                "valor" => "idsindicato",
                "sql_filtro" => "select * from sindicatos where idsindicato = %",
                "sql_filtro_label" => "nome_abreviado"
            ),
            
            array(
                'id' => 'idescola',
                "nome" => 'q[3|ma.idescola][]',
                "nome_idioma"=> "form_escola_filtro",
                "nomeidioma"=> "form_escola_filtro",
                "tipo" => "select",
                'evento' => 'multiple',
                "class" => "select2 span5",
                "valor" => "idescola",
                "sql" => $sqlEscola,
                "sql_valor" => "idescola",
                "sql_label" => "razao_social",
                "sql_ordem_campo" => "razao_social",
                "sql_filtro" => "select * from escolas where ativo='S' and idescola=%",
                "sql_filtro_label" => "razao_social",

            ),

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

             array(
                "id" => "combo",
                "nome" => "q[1|ma.combo]",
                "nomeidioma" => "form_combo",
                "tipo" => "select",
                "array" => "sim_nao", // Array que alimenta o select
                "class" => "span2",
                "valor" => "combo",
                "sql_filtro" => "array", //PARA PEGAR ARRAY DO CONFIG
                "sql_filtro_label" => "combo"
                ),

            array(
                "id" => "bolsa",
                "nome" => "q[1|ma.bolsa]",
                "nomeidioma" => "form_bolsa",
                "tipo" => "select",
                "array" => "bolsaMatricula", // Array que alimenta o select
                "class" => "span2",
                "valor" => "bolsa",
                "sql_filtro" => "array", //PARA PEGAR ARRAY DO CONFIG
                "sql_filtro_label" => "bolsa"
                ),

            array(
                'id' => 'idsolicitante',
                "nome" => "q[1|ma.idsolicitante]",
                "tipo" => "select",
                "class" => "span5",
                "valor" => "idsolicitante",
                "nomeidioma" => "form_solicitante",
                "sql_filtro" => "select * from solicitantes_bolsas where idsolicitante=%",
                "sql_filtro_label" => "nome",
                "json" => true,
                "json_idpai" => "bolsa",
                "json_url" => '/'.$url["0"].'/'.$url["1"].'/'.$url["2"]."/ajax_solicitantes/",
                "json_input_pai_vazio" => "form_selecione_bolsa",
                "json_input_vazio" => "form_selecione_solicitante",
                "json_campo_exibir" => "nome",
                    ),

            array(
            "id" => "form_forma_pagamento",
            "nome" => "q[1|ma.forma_pagamento]",
            "nomeidioma" => "form_forma_pagamento",
            "tipo" => "select",
            "array" => "forma_pagamento_conta", // Array que alimenta o select
            "class" => "span2",
            "valor" => "forma_pagamento",
            //"validacao" => array("required" => "form_forma_pagamento_vazio"),
            "banco" => true,
            "banco_string" => true,
            "select_hidden" => true,
            "botao_hide" => true,
            "iddiv3" => "idbandeira",
            "iddiv4" => "autorizacao_cartao",
            'sql_filtro' => 'array',
            'sql_filtro_label' => 'forma_pagamento_conta'
          ),
          array(
            "id" => "form_idbandeira",
            "nome" => "q[1|ma.idbandeira]",
            "nomeidioma" => "form_idbandeira",
            "tipo" => "select",
            "sql" => "SELECT idbandeira, nome FROM bandeiras_cartoes where ativo = 'S' AND ativo_painel = 'S'", // SQL que alimenta o select
            "sql_valor" => "idbandeira", // Coluna da tabela que será usado como o valor do options
            "sql_label" => "nome", // Coluna da tabela que será usado como o label do options
            "valor" => "idbandeira",
            //"validacao" => array("required" => "idbandeira_vazio"),
            //"referencia_label" => "cadastro_bandeiras",
            //"referencia_link" => "/gestor/financeiro/bandeirascartoes",
            "banco" => true,
            "select_hidden" => true,
            "sql_filtro" => "select * from bandeiras_cartoes where idbandeira=%",
            "sql_filtro_label" => "nome"
          ),
          array(
            "id" => "form_autorizacao_cartao",
            "nome" => "q[1|ma.autorizacao_cartao]",
            "nomeidioma" => "form_autorizacao_cartao",
            "tipo" => "input",
            "valor" => "autorizacao_cartao",
            //"validacao" => array("required" => "autorizacao_cartao_vazio"),
            "class" => "span2",
            "banco" => true,
            "banco_string" => true,
            "input_hidden" => true,
          ),

          array(
                'id' => 'form_quantidade_parcelas',
                'nome' => 'q[1|ma.quantidade_parcelas]',
                'tipo' => 'input',
                'class' => 'span1',
                'nomeidioma' => 'form_quantidade_parcelas'
            ),
          array(
                'id' => 'idempresa',
                "sql" => "select idempresa, nome from empresas where ativo = 'S' order by nome",
                'nome' => 'q[1|ma.idempresa]',
                'tipo' => 'select',
                'valor' => 'idempresa',
                "class" => "span3",
                "sql_valor" => "idempresa",
                "sql_label" => "nome",
                'nomeidioma' => 'form_idempresa',
                'sql_filtro' => 'select * from empresas where idempresa = %',
                'sql_filtro_label' => 'nome',
            ),

        )
    )
);

$colunas = array(
  1 => "data",
  2 => "matricula",
  3 => "contrato",
  4 => "aluno",
  5 => "situacao",
  6 => "oferta",
  7 => "curso",
  8 => "solicitante",
  9 => "turma",
  10 => "escola",
  11 => "estado",
  12 => "cidade",
  13 => "forma_pagamento",
  14 => "parcelas",
  15 => "vendedor",
  16 => "valor_contrato"
);

$colunasPadrao = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16);