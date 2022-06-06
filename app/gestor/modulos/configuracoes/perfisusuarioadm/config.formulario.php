<?php
// Array de configuração para a formulario
$config['formulario'] = array(
    array(
        'fieldsetid' => 'dadosdousuario',
        'legendaidioma' => 'legendadadosusuarios',
        'campos' => array(
            array(
                'id' => 'form_nome',
                'nome' => 'nome',
                'nomeidioma' => 'form_nome',
                'tipo' => 'input',
                'valor' => 'nome',
                'validacao' => array('required' => 'nome_vazio'),
                'class' => 'span6',
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
                'validacao' => array('required' => 'ativo_vazio'),
                'ajudaidioma' => 'form_ativo_ajuda',
                'banco' => true,
                'banco_string' => true
            ),
            array(
                'id' => 'form_observacoes',
                'nome' => 'observacoes',
                'nomeidioma' => 'form_observacoes',
                'tipo' => 'text',
                'valor' => 'observacoes',
                'class' => 'xxlarge',
                'banco' => true,
                'banco_string' => true
            )
        )
    )
);