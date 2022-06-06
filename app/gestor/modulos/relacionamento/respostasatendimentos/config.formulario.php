<?php
$config['formulario'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
            array(
                'id' => 'form_nome',
                'nome' => 'nome',
                'nomeidioma' => 'form_nome',
                'tipo' => 'input',
                'valor' => 'nome',
                'validacao' => array(
                    'required' => 'nome_vazio'
                ),
                'class' => 'span5',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'form_ativo_painel',
                'nome' => 'ativo_painel',
                'nomeidioma' => 'form_ativo_painel',
                'tipo' => 'select',
                'array' => 'ativo',
                'class' => 'span2',
                'valor' => 'ativo_painel',
                'validacao' => array(
                    'required' => 'ativo_vazio'
                ),
                'ajudaidioma' => 'form_ativo_ajuda',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'form_anexo',
                'nome' => 'anexo',
                'nomeidioma' => 'form_anexo',
                'tipo' => 'select',
                'array' => 'sim_nao',
                'class' => 'span2',
                'valor' => 'anexo',
                'validacao' => array(
                    'required' => 'anexo_vazio'
                ),
                'ajudaidioma' => 'form_anexo_ajuda',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'form_resposta',
                'nome' => 'resposta',
                'nomeidioma' => 'form_resposta',
                'tipo' => 'text',
                'valor' => 'resposta',
                'class' => 'span7',
                'evento' => 'rows="5"',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'form_botao_variaveis',
                'nome' => 'botao_variaveis',
                'nomeidioma' => 'form_botao_variaveis',
                'tipo' => 'php',
                'colunas' => 2,
                'botao_hide' => true,
                'valor' => array(
                    array(
                        'variavel_titulo_cliente' => 'titulo',
                        'variavel_nome' => '[[NOME]]',
                        'variavel_cpf' => '[[CPF]]',
                        'variavel_rg' => '[[RG]]',
                        'variavel_estadocivil' => '[[ESTADOCIVIL]]',
                        'variavel_endereco' => '[[ENDERECO]]',
                        'variavel_bairro' => '[[BAIRRO]]',
                        'variavel_estado' => '[[ESTADO]]',
                        'variavel_cidade' => '[[CIDADE]]',
                        'variavel_cep' => '[[CEP]]',
                        'variavel_numero' => '[[NUMERO]]',
                        'variavel_complemento' => '[[COMPLEMENTO]]',
                        'variavel_nascimento' => '[[NASCIMENTO]]'
                    )
                ),
                'class' => 'span4'
            )
        )
    )
);

$config['formulario_relacoes'] = array(
     array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
            array(
                'id' => 'form_escola',
                'nome' => 'idescola',
                'nomeidioma' => 'form_escola',
                'tipo' => 'checkbox',
                'sql' => 'SELECT CONCAT(p.nome_fantasia, " / ", i.nome_abreviado) as nome_fantasia,p.idescola FROM escolas p INNER JOIN sindicatos i ON i.idsindicato = p.idsindicato WHERE p.ativo ="S" AND p.ativo_painel ="S"',
                'sql_ordem_campo' => 'idescola',
                'sql_valor' => 'idescola',
                'sql_label' => 'nome_fantasia',
                'valor' => 'nome',
                'class' => 'span5',
                'banco' => true,
                'banco_string' => true
            ),
        ),
    ),
);