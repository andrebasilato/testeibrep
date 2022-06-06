<?php
$config['formulario_pessoas'] = array(
    array(
        'fieldsetid' => 'dadosdoobjeto',
        'legendaidioma' => 'legendadadosdados',
        'campos' => array(
            array(
                'id' => 'titulo',
                'nome' => 'titulo',
                'tipo' => 'input',
                'valor' => 'titulo',
                'banco' => true,
                'class' => 'span6',
                'validacao' => array('required' => 'titulo_vazio'),
                'nomeidioma' => 'titulo',
                'banco_string' => true,
            ),
            array(
                'id' => 'descricao',
                'nome' => 'descricao',
                'tipo' => 'text',
                'banco' => true,
                'valor' => 'descricao',
                'class' => 'xxlarge span6" style="height: 100px',
                'validacao' => array('required' => 'descricao_vazio'),
                'nomeidioma' => 'descricao',
                'banco_string' => true
            ),
            array(
                'id' => 'idinstrutor',
                'nome' => 'idinstrutor',
                'tipo' => 'hidden',
                'valor' => 'return $usu_professor["idprofessor"];',
                'banco' => true,
                'class' => 'span6',
                'nomeidioma' => 'idinstrutor',
                'banco_string' => true
            ),
            array(
                'id' => 'data_agendamento',
                'nome' => 'data_agendamento',
                'tipo' => 'input',
                'banco' => true,
                'valor' => 'data_agendamento',
                'class' => 'span6',
                'validacao' => array('required' => 'agendamento_vazio'),
                'nomeidioma' => 'data_agendamento',
                'banco_string' => true
            ),
            array(
                'id' => 'data_encerramento',
                'tipo' => 'input',
                'nome' => 'data_encerramento',
                'valor' => 'data_encerramento',
                'banco' => true,
                'class' => 'span6',
                'validacao' => array('required' => 'encerramento_vazio'),
                'nomeidioma' => 'data_encerramento',
                'banco_string' => true
            ),
            array(
                'id' => 'idinstrutor',
                'tipo' => 'select',
                'nome' => 'idinstrutor',
                'valor' => 'Professores::associacoesDoProfessor(1);',
                'banco' => true,
                'class' => 'span6',
                'validacao' => array('required' => 'instrutor_vazio'),
                'nomeidioma' => 'idinstrutor',
                'banco_string' => true
            ),
            array(
                'id' => 'ativo_painel',
                'tipo' => 'select',
                'nome' => 'ativo_painel',
                'array' => 'ativo',
                'banco' => true,
                'class' => 'span6',
                'validacao' => array('required' => 'valor_vazio'),
                'nomeidioma' => 'ativo_painel',
                'banco_string' => true
            ),
        )
    ),
);