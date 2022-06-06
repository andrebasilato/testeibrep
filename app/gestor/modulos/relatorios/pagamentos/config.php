<?php

$config['funcionalidade'] = 'funcionalidade';
$config['acoes'][1] = 'visualizar';

$sqlEscola = 'SELECT idescola, nome_fantasia FROM escolas WHERE ativo = "S"';
if ($_SESSION['adm_gestor_sindicato'] != 'S') {
    $sqlEscola .= ' AND idsindicato IN (' . $_SESSION['adm_sindicatos'] . ')';
}

$sqlSindicato = 'SELECT idsindicato, nome FROM sindicatos WHERE ativo = "S"';
if ($_SESSION['adm_gestor_sindicato'] != 'S') {
    $sqlSindicato .= ' AND idsindicato IN (' . $_SESSION['adm_sindicatos'] . ')';
}

$forma_pagamento_conta[$config['idioma_padrao']][9] = 'Pagar.me';

$config['listagem'] = array(
    array(
        'id' => 'tabela_idmatricula',
        'variavel_lang' => 'tabela_idmatricula',
        'tipo' => 'banco',
        'valor' => 'idmatricula'
    ),
    array(
        'id' => 'tabela_data_matricula',
        'variavel_lang' => 'tabela_data_matricula',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_matricula"], "br", 0);'
    ),
    array(
        'id' => 'tabela_nome',
        'variavel_lang' => 'tabela_nome',
        'tipo' => 'banco',
        'valor' => 'nome'
    ),
    array(
        'id' => 'tabela_sindicato',
        'variavel_lang' => 'tabela_sindicato',
        'tipo' => 'banco',
        'valor' => 'sindicato'
    ),
    array(
        'id' => 'tabela_escola',
        'variavel_lang' => 'tabela_escola',
        'tipo' => 'banco',
        'valor' => 'escola'
    ),
    array(
        'id' => 'tabela_atendente',
        'variavel_lang' => 'tabela_atendente',
        'tipo' => 'banco',
        'valor' => 'atendente'
    ),
    array(
        'id' => 'tabela_cidade',
        'variavel_lang' => 'tabela_cidade',
        'tipo' => 'banco',
        'valor' => 'cidade'
    ),
    array(
        'id' => 'tabela_estado',
        'variavel_lang' => 'tabela_estado',
        'tipo' => 'banco',
        'valor' => 'estado'
    ),
    array(
        'id' => 'tabela_forma_pagamento',
        'variavel_lang' => 'tabela_forma_pagamento',
        'tipo' => 'php',
        'valor' => '
            $retorno = $GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]][$linha["forma_pagamento"]];
            if ($linha["fatura"] == "S") {
                $retorno = "Pagar.me";
            }

            return $retorno',
        'classTd' => 'tdAzul'
    ),
    array(
        'id' => 'tabela_valor_pf',
        'variavel_lang' => 'tabela_valor_pf',
        'tipo' => 'php',
        'valor' => '
            if ($linha["valor_pf"]) {
                return "R$ " . number_format($linha["valor_pf"], 2, ",", ".") . "</span>";
            }',
        'tamanho' => 65,
        'classTd' => 'tdAzul'
    ),
    array(
        'id' => 'tabela_data_vencimento_pf',
        'variavel_lang' => 'tabela_data_vencimento_pf',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_vencimento_pf"], "br", 0);',
        'classTd' => 'tdAzul'
    ),
    array(
        'id' => 'tabela_data_pagamento_pf',
        'variavel_lang' => 'tabela_data_pagamento_pf',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_pagamento_pf"], "br", 0);',
        'classTd' => 'tdAzul'
    ),
    array(
        'id' => 'tabela_bom_para_pf',
        'variavel_lang' => 'tabela_bom_para_pf',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["bom_para_pf"], "br", 0);',
        'classTd' => 'tdAzul'
    ),
    array(
        'id' => 'tabela_situacao_matricula',
        'variavel_lang' => 'tabela_situacao_matricula',
        'tipo' => 'banco',
        'valor' => 'situacao_matricula',
        'classTd' => 'tdAzul'
    ),

    array(
        'id' => 'tabela_idfatura',
        'variavel_lang' => 'tabela_idfatura',
        'tipo' => 'banco',
        'valor' => 'idfatura',
        'classTd' => 'tdVerde'
    ),
    array(
        'id' => 'tabela_valor_pj',
        'variavel_lang' => 'tabela_valor_pj',
        'tipo' => 'php',
        'valor' => '
            if ($linha["valor_pj"]) {
                return "R$ " . number_format($linha["valor_pj"], 2, ",", ".") . "</span>";
            }',
        'classTd' => 'tdVerde'
    ),
    array(
        'id' => 'tabela_data_vencimento_pj',
        'variavel_lang' => 'tabela_data_vencimento_pj',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_vencimento_pj"], "br", 0);',
        'classTd' => 'tdVerde'
    ),
    array(
        'id' => 'tabela_data_pagamento_pj',
        'variavel_lang' => 'tabela_data_pagamento_pj',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_pagamento_pj"], "br", 0);',
        'classTd' => 'tdVerde'
    ),
    array(
        'id' => 'tabela_bom_para_pj',
        'variavel_lang' => 'tabela_bom_para_pj',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["bom_para_pj"], "br", 0);',
        'classTd' => 'tdVerde'
    ),
);

$config['formulario'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
            array(
                'id' => 'form_tipo_data_matricula',
                'nome' => 'q[de_ate|tipo_data_matricula|m.data_matricula]',
                'nomeidioma' => 'form_tipo_data_matricula',
                'botao_hide' => true,
                'iddivs' => array('data_matricula_de','data_matricula_ate'),
                'tipo' => 'select',
                'iddiv' => 'data_matricula_de',
                'iddiv2' => 'data_matricula_ate',
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_filtro',
                'banco' => true,
                'banco_string' => true,
                'sql_filtro' => 'array',
                'sql_filtro_label' => 'tipo_data_filtro'
            ),
            array(
                'id' => 'form_data_matricula_de',
                'nome' => 'data_matricula_de',
                'nomeidioma' => 'form_data_matricula_de',
                'tipo' => 'input',
                'class' => 'span2',
                'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_matricula_de","form_data_matricula_ate")\'',
                'datepicker' => true,
                'mascara' => '99/99/9999',
                'input_hidden' => true,
            ),
            array(
                'id' => 'form_data_matricula_ate',
                'nome' => 'data_matricula_ate',
                'nomeidioma' => 'form_data_matricula_ate',
                'tipo' => 'input',
                'class' => 'span2',
                'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_matricula_de","form_data_matricula_ate")\'',
                'datepicker' => true,
                'mascara' => '99/99/9999',
                'input_hidden' => true,
            ),
            array(
                'id' => 'idsindicato',
                'nome' => 'idsindicato',
                'nomeidioma' => 'form_idsindicato',
                'tipo' => 'checkbox',
                'sql' => $sqlSindicato,
                'sql_valor' => 'idsindicato',
                'sql_label' => 'nome',
                'sql_ordem_campo' => 'nome',
                'sql_ordem' => 'ASC',
                'valor' => 'idsindicato',
                'class' => 'span3',
                'sql_filtro' => 'SELECT * FROM sindicatos WHERE idsindicato = %',
                'sql_filtro_label' => 'nome'
            ),
            array(
                'id' => 'idescola',
                'nome' => 'idescola[]',
                'nomeidioma' => 'form_idescola',
                'tipo' => 'select',
                'sql' => $sqlEscola,
                'sql_valor' => 'idescola',
                'sql_label' => 'nome_fantasia',
                'sql_ordem_campo' => 'nome_fantasia',
                'sql_ordem' => 'ASC',
                'valor' => 'idescola',
                'class' => 'span3',
                'sql_filtro' => 'SELECT * FROM escolas WHERE idescola = %',
                'sql_filtro_label' => 'nome_fantasia',
                'evento' => 'multiple'
            ),
            array(
                'id' => 'idestado',
                'nome' => 'q[1|est.idestado]',
                'nomeidioma' => 'form_idestado',
                'tipo' => 'select',
                'sql' => 'SELECT idestado, nome FROM estados ORDER BY nome',
                'sql_valor' => 'idestado',
                'sql_label' => 'nome',
                'valor' => 'idestado',
                'class' => 'span3',
                'sql_filtro' => 'SELECT * FROM estados WHERE idestado = %',
                'sql_filtro_label' => 'nome'
            ),
            array(
                'id' => 'idcidade',
                'nome' => 'q[1|cid.idcidade]',
                'nomeidioma' => 'form_idcidade',
                'json' => true,
                'json_idpai' => 'idestado',
                'json_url' => '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/ajax_cidades/',
                'json_input_pai_vazio' => 'form_selecione_estado',
                'json_input_vazio' => 'form_selecione_cidade',
                'json_campo_exibir' => 'nome',
                'tipo' => 'select',
                'valor' => 'idcidade',
                'class' => 'span3',
                'sql_filtro' => 'SELECT * FROM cidades WHERE idcidade = %',
                'sql_filtro_label' => 'nome'
            ),
            array(
                'id' => 'forma_pagamento',
                'nome' => 'forma_pagamento',
                'nomeidioma' => 'form_forma_pagamento',
                'tipo' => 'checkbox',
                'array' => 'forma_pagamento_conta',
                'class' => 'span3'
            ),
            array(
                'id' => 'form_tipo_data_vencimento',
                'nome' => 'q[de_ate|tipo_data_vencimento|c.data_vencimento]',
                'nomeidioma' => 'form_tipo_data_vencimento',
                'botao_hide' => true,
                'iddivs' => array('data_vencimento_de','data_vencimento_ate'),
                'tipo' => 'select',
                'iddiv' => 'data_vencimento_de',
                'iddiv2' => 'data_vencimento_ate',
                'iddiv_obr' => true,
                'iddiv2_obr' => true,
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_filtro',
                'validacao' => array('required' => 'tipo_data_vencimento_vazio'),
                'banco' => true,
                'banco_string' => true,
                'sql_filtro' => 'array',
                'sql_filtro_label' => 'tipo_data_filtro'
            ),
            array(
                'id' => 'form_data_vencimento_de',
                'nome' => 'data_vencimento_de',
                'nomeidioma' => 'form_data_vencimento_de',
                'tipo' => 'input',
                'class' => 'span2',
                'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_vencimento_de","form_data_vencimento_ate")\'',
                'validacao' => array('required' => 'data_vencimento_de_vazio'),
                'datepicker' => true,
                'mascara' => '99/99/9999',
                'input_hidden' => true,
            ),
            array(
                'id' => 'form_data_vencimento_ate',
                'nome' => 'data_vencimento_ate',
                'nomeidioma' => 'form_data_vencimento_ate',
                'tipo' => 'input',
                'class' => 'span2',
                'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_vencimento_de","form_data_vencimento_ate")\'',
                'validacao' => array('required' => 'data_vencimento_ate_vazio'),
                'datepicker' => true,
                'mascara' => '99/99/9999',
                'input_hidden' => true,
            ),
            array(
                'id' => 'form_tipo_data_pagamento',
                'nome' => 'q[de_ate|tipo_data_pagamento|c.data_pagamento]',
                'nomeidioma' => 'form_tipo_data_pagamento',
                'botao_hide' => true,
                'iddivs' => array('data_pagamento_de','data_pagamento_ate'),
                'tipo' => 'select',
                'iddiv' => 'data_pagamento_de',
                'iddiv2' => 'data_pagamento_ate',
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_filtro',
                'banco' => true,
                'banco_string' => true,
                'sql_filtro' => 'array',
                'sql_filtro_label' => 'tipo_data_filtro'
            ),
            array(
                'id' => 'form_data_pagamento_de',
                'nome' => 'data_pagamento_de',
                'nomeidioma' => 'form_data_pagamento_de',
                'tipo' => 'input',
                'class' => 'span2',
                'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_pagamento_de","form_data_pagamento_ate")\'',
                'datepicker' => true,
                'mascara' => '99/99/9999',
                'input_hidden' => true,
            ),
            array(
                'id' => 'form_data_pagamento_ate',
                'nome' => 'data_pagamento_ate',
                'nomeidioma' => 'form_data_pagamento_ate',
                'tipo' => 'input',
                'class' => 'span2',
                'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_pagamento_de","form_data_pagamento_ate")\'',
                'datepicker' => true,
                'mascara' => '99/99/9999',
                'input_hidden' => true,
            ),
             array(
                'id' => 'idsituacao_matricula',
                'nome' => 'idsituacao_matricula',
                'nomeidioma' => 'form_idsituacao_matricula',
                'tipo' => 'checkbox',
                'sql' => 'SELECT idsituacao, nome FROM matriculas_workflow WHERE ativo="S"',
                'sql_valor' => 'idsituacao',
                'sql_label' => 'nome',
                'sql_ordem_campo' => 'nome',
                'sql_ordem' => 'ASC',
                'valor' => 'idsituacao',
                'class' => 'span3',
                'sql_filtro' => 'SELECT * FROM matriculas_workflow WHERE idsituacao = %',
                'sql_filtro_label' => 'nome'
            )
        )
    )
);
