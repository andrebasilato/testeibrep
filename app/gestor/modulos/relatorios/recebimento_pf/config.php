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
        'id' => 'tabela_data_matricula',
        'variavel_lang' => 'tabela_data_matricula',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_matricula"], "br", 0);'
    ),
    array(
        'id' => 'tabela_data_em_curso',
        'variavel_lang' => 'tabela_data_em_curso',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_em_curso"], "br", 0);'
    ),
    array(
        'id' => 'tabela_idmatricula',
        'variavel_lang' => 'tabela_idmatricula',
        'tipo' => 'banco',
        'valor' => 'idmatricula'
    ),
    array(
        'id' => 'tabela_situacao_matricula',
        'variavel_lang' => 'tabela_situacao_matricula',
        'tipo' => 'banco',
        'valor' => 'situacao_matricula'
    ),
    array(
        'id' => 'tabela_nome',
        'variavel_lang' => 'tabela_nome',
        'tipo' => 'banco',
        'valor' => 'nome'
    ),
    array(
        'id' => 'tabela_documento',
        'variavel_lang' => 'tabela_documento',
        'tipo' => 'banco',
        'valor' => 'documento'
    ),
    array(
        'id' => 'tabela_telefone',
        'variavel_lang' => 'tabela_telefone',
        'tipo' => 'banco',
        'valor' => 'telefone'
    ),
    array(
        'id' => 'tabela_celular',
        'variavel_lang' => 'tabela_celular',
        'tipo' => 'banco',
        'valor' => 'celular'
    ),
    array(
        'id' => 'tabela_email',
        'variavel_lang' => 'tabela_email',
        'tipo' => 'banco',
        'valor' => 'email'
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
        'id' => 'tabela_valor',
        'variavel_lang' => 'tabela_valor',
        'tipo' => 'php',
        'valor' => 'return "R$ " . number_format($linha["valor"], 2, ",", ".") . "</span>";',
        'tamanho' => 65
    ),
    array(
        'id' => 'tabela_forma_pagamento',
        'variavel_lang' => 'tabela_forma_pagamento',
        'tipo' => 'php',
        'valor' => 'return $GLOBALS["forma_pagamento_conta"][$GLOBALS["config"]["idioma_padrao"]][$linha["forma_pagamento"]];'
    ),
    array(
        'id' => 'tabela_parcelas_pagseguro',
        'variavel_lang' => 'tabela_parcelas_pagseguro',
        'tipo' => 'banco',
        'valor' => 'parcelas_pagseguro'
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
        'id' => 'tabela_data_prevista_disponivel_pagseguro',
        'variavel_lang' => 'tabela_data_prevista_disponivel_pagseguro',
        'tipo' => 'php',
        'valor' => 'return formataData($linha["data_prevista_disponivel_pagseguro"], "br", 0);'
    ),
    array(
        'id' => 'tabela_code_pagseguro',
        'variavel_lang' => 'tabela_code_pagseguro',
        'tipo' => 'banco',
        'valor' => 'code_pagseguro'
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
                'id' => 'data_matricula',
                'nome' => 'q[3|m.data_matricula]', 
                'nomeidioma' => 'form_data_matricula',
                'tipo' => 'input',
                'class' => 'span2',
                'datepicker' => true,
                'mascara' => '99/99/9999'
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
            ),
            array(
                'id' => 'data_em_curso',
                'nome' => 'data_em_curso', 
                'nomeidioma' => 'form_data_em_curso',
                'tipo' => 'input',
                'class' => 'span2',
                'datepicker' => true,
                'mascara' => '99/99/9999'
            ),
            array(
                'id' => 'form_matricula',
                'nome' => 'q[1|m.idmatricula]',
                'nomeidioma' => 'form_matricula',
                'tipo' => 'input',
                'class' => 'span2',
                'numerico' => true,
                'evento' => "maxlength='10'"
            ),
            array(
                'id' => 'form_nome',
                'nome' => 'q[2|p.nome]',
                'nomeidioma' => 'form_nome',
                'tipo' => 'input',
                'class' => 'span5',
                'evento' => "maxlength='100'"
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
                'id' => 'form_tipo_data_prevista_disponivel_pagseguro',
                'nome' => 'q[de_ate|tipo_data_prevista_disponivel_pagseguro|data_prevista_disponivel_pagseguro]',
                'nomeidioma' => 'form_tipo_data_prevista_disponivel_pagseguro',
                'botao_hide' => true,
                'iddivs' => array('data_prevista_disponivel_pagseguro_de','data_prevista_disponivel_pagseguro_ate'),
                'tipo' => 'select',
                'iddiv' => 'data_prevista_disponivel_pagseguro_de',
                'iddiv2' => 'data_prevista_disponivel_pagseguro_ate',
                'array' => 'tipo_data_filtro',
                'class' => 'span3',
                'valor' => 'tipo_data_filtro',
                'banco' => true,
                'banco_string' => true,
                'sql_filtro' => 'array',
                'sql_filtro_label' => 'tipo_data_filtro'
            ),
            array(
                'id' => 'form_data_prevista_disponivel_pagseguro_de',
                'nome' => 'data_prevista_disponivel_pagseguro_de',
                'nomeidioma' => 'form_data_prevista_disponivel_pagseguro_de',
                'tipo' => 'input',
                'class' => 'span2',
                'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_prevista_disponivel_pagseguro_de","form_data_prevista_disponivel_pagseguro_ate")\'',
                'datepicker' => true,
                'mascara' => '99/99/9999',
                'input_hidden' => true,
            ),
            array(
                'id' => 'form_data_prevista_disponivel_pagseguro_ate',
                'nome' => 'data_prevista_disponivel_pagseguro_ate',
                'nomeidioma' => 'form_data_prevista_disponivel_pagseguro_ate',
                'tipo' => 'input',
                'class' => 'span2',
                'evento' => 'onchange=\'validaIntervaloDatasUmAno("form_data_prevista_disponivel_pagseguro_de","form_data_prevista_disponivel_pagseguro_ate")\'',
                'datepicker' => true,
                'mascara' => '99/99/9999',
                'input_hidden' => true,
            ),
        )
    )
);
