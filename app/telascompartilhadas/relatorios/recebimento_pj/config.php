<?php

$sqlEscola = 'SELECT idescola, nome_fantasia FROM escolas WHERE ativo = "S"';
if ($_SESSION['adm_gestor_sindicato'] != 'S') {
    $sqlEscola .= ' AND idsindicato IN (' . $_SESSION['adm_sindicatos'] . ')';
}

$sqlSindicato = 'SELECT idsindicato, nome FROM sindicatos WHERE ativo = "S"';
if ($_SESSION['adm_gestor_sindicato'] != 'S') {
    $sqlSindicato .= ' AND idsindicato IN (' . $_SESSION['adm_sindicatos'] . ')';
}

$config['listagem'] = array(
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
        'id' => 'tabela_idconta',
        'variavel_lang' => 'tabela_idconta',
        'tipo' => 'banco',
        'valor' => 'idconta'
    ),
    array(
        'id' => 'tabela_fatura_de',
        'variavel_lang' => 'tabela_fatura_de',
        'tipo' => 'php',
        'valor' => '
            $dataCad = new DateTime($linha["data_cad"]);
            $data = $dataCad->format("01/m/Y");

            if ($dataCad->format("d") >= 16) {
                $data = $dataCad->format("16/m/Y");
            }

            return $data;'
    ),
    array(
        'id' => 'tabela_fatura_ate',
        'variavel_lang' => 'tabela_fatura_ate',
        'tipo' => 'php',
        'valor' => '
            $dataCad = new DateTime($linha["data_cad"]);
            $data = $dataCad->format("15/m/Y");

            if ($dataCad->format("d") >= 16) {
                $data = $dataCad->format("t/m/Y");
            }

            return $data;'
    ),
    array(
        'id' => 'tabela_qnt_matriculas',
        'variavel_lang' => 'tabela_qnt_matriculas',
        'tipo' => 'php',
        'valor' => '
            return "<a
                data-original-title=\"".$idioma["tabela_opcoes_tooltip"]."\"
                href=\"/" . $this->url[0] . "/" . $this->url[1] . "/matriculas_cfc/html?idconta[]=" . $linha["idconta"] . "&q[de_ate|tipo_data_cad|c.data_cad]=PER&data_cad_de=" . $dataCad->format("d/m/Y") . "&data_cad_ate=" . $dataCad->format("d/m/Y") . "\"
                style=\"color: #0000FF;\"
                target=\"_blank\">
                    " . $linha["qnt_matriculas"] . "
                </a>"'
    ),
    array(
        'id' => 'tabela_media_por_matricula',
        'variavel_lang' => 'tabela_media_por_matricula',
        'tipo' => 'php',
        'valor' => 'return "R$ " . number_format($linha["media_por_matricula"], 2, ",", ".") . "</span>";'
    ),
    array(
        'id' => 'tabela_valor',
        'variavel_lang' => 'tabela_valor',
        'tipo' => 'php',
        'valor' => 'return "R$ " . number_format($linha["valor"], 2, ",", ".") . "</span>";'
    ),
    array(
        'id' => 'tabela_situacao',
        'variavel_lang' => 'tabela_situacao',
        'tipo' => 'banco',
        'valor' => 'situacao'
    ),
    array(
        'id' => 'tabela_data_vencimento',
        'variavel_lang' => 'tabela_data_vencimento',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_vencimento"], "br", 0);'
    ),
    array(
        'id' => 'tabela_data_pagamento',
        'variavel_lang' => 'tabela_data_pagamento',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_pagamento"], "br", 0);'
    ),
    array(
        'id' => 'tabela_data_prevista_disponivel_pagarme',
        'variavel_lang' => 'tabela_data_prevista_disponivel_pagarme',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_prevista_disponivel_pagarme"], "br", 0);'
    ),
    array(
        'id' => 'tabela_pagarme_id',
        'variavel_lang' => 'tabela_pagarme_id',
        'tipo' => 'banco',
        'valor' => 'pagarme_id'
    )
);

$config['formulario'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
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
                'id' => 'idconta',
                'nome' => 'idconta',
                'nomeidioma' => 'form_idconta',
                'tipo' => 'select',
                'class' => 'invisivel',
                'sql_filtro' => 'SELECT * FROM contas WHERE idconta = %',
                'sql_filtro_label' => 'idconta'
            ),
            array(
                'id' => 'form_tipo_data_cad',
                'nome' => 'q[de_ate|tipo_data_cad|c.data_cad]',
                'nomeidioma' => 'form_tipo_data_cad',
                'botao_hide' => true,
                'iddivs' => array('data_cad_de','data_cad_ate'),
                'tipo' => 'select',
                'iddiv' => 'data_cad_de',
                'iddiv2' => 'data_cad_ate',
                'iddiv_obr' => true,
                'iddiv2_obr' => true,
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_filtro',
                'validacao' => array('required' => 'tipo_data_cad_vazio'),
                'banco' => true,
                'banco_string' => true,
                'sql_filtro' => 'array',
                'sql_filtro_label' => 'tipo_data_filtro'
            ),
            array(
                'id' => 'form_data_cad_de',
                'nome' => 'data_cad_de',
                'nomeidioma' => 'form_data_cad_de',
                'tipo' => 'input',
                'class' => 'span2',
                'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_cad_de","form_data_cad_ate")\'',
                'validacao' => array('required' => 'data_cad_de_vazio'),
                'datepicker' => true,
                'mascara' => '99/99/9999',
                'input_hidden' => true,
            ),
            array(
                'id' => 'form_data_cad_ate',
                'nome' => 'data_cad_ate',
                'nomeidioma' => 'form_data_cad_ate',
                'tipo' => 'input',
                'class' => 'span2',
                'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_cad_de","form_data_cad_ate")\'',
                'validacao' => array('required' => 'data_cad_ate_vazio'),
                'datepicker' => true,
                'mascara' => '99/99/9999',
                'input_hidden' => true,
            ),
            array(
                'id' => 'form_valor',
                'nome' => 'q[6|c.valor]',
                'nomeidioma' => 'form_valor',
                'tipo' => 'input',
                'class' => 'span2',
                'decimal' => true,
                'evento' => "maxlength='13'"
            ),
            array(
                'id' => 'idsituacao_conta',
                'nome' => 'idsituacao_conta',
                'nomeidioma' => 'form_idsituacao_conta',
                'tipo' => 'checkbox',
                'sql' => 'SELECT idsituacao, nome FROM contas_workflow WHERE ativo="S"',
                'sql_valor' => 'idsituacao',
                'sql_label' => 'nome',
                'sql_ordem_campo' => 'nome',
                'sql_ordem' => 'ASC',
                'valor' => 'idsituacao',
                'class' => 'span3',
                'sql_filtro' => 'SELECT * FROM contas_workflow WHERE idsituacao = %',
                'sql_filtro_label' => 'nome'
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
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_filtro',
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
            )
        )
    )
);

if ($url[0] == 'cfc') {
    $config['listagem'] = $escolaObj->removerListagem($config['listagem'], ['tabela_escola']);
    $config['formulario'] = $escolaObj->alterarConfigFormulario($config['formulario'], ['idescola[]']);
}
