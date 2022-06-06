<?php

$workflow_parametros_atendimentos = array(
    0 => array(
        'idopcao' => 1,
        'tipo' => 'visualizacao',
        'combo' => 'Sem Painel',
        'nome' => 'Responder atendimento',
        'parametros' => array(
        ),
    ),
    2 => array(
        'idopcao' => 3,
        'tipo' => 'acao',
        'nome' => 'Enviar e-mail para o cliente',
        'parametros' => array(
            0 => array(
                'idparametro' => 12,
                'tipo' => 'textarea',
                'nome' => 'Texto do e-mail:',
                'valor' => 'Ol&aacute; <strong>[[CLIENTE][NOME]]</strong>,<br /><br />O seu atendimento, de protocolo #[[ATENDIMENTO][PROTOCOLO]], sofreu uma modifica&ccedil;&atilde;o.<br /><br />Para visualizar a modifica&ccedil;&atilde;o <a href="'.$_SERVER['HTTP_HOST'].'/gestor/relacionamento/atendimentos/[[ATENDIMENTO][IDATENDIMENTO]]/administrar">clique aqui</a>.<br /><br />'
            ),
        ),
    ),
    3 => array(
        'idopcao' => 4,
        'tipo' => 'acao',
        'nome' => 'Cancelar atendimento',
        'parametros' => array(
        ),
    ),
    4 => array(
        'idopcao' => 5,
        'tipo' => 'prerequisito',
        'nome' => 'Ter uma resposta pública',
        'parametros' => array(
        ),
    ),
    5 => array(
        'idopcao' => 6,
        'tipo' => 'prerequisito',
        'nome' => 'Ter uma resposta do cliente',
        'parametros' => array(
        ),
    ),
    6 => array(
        'idopcao' => 7,
        'tipo' => 'acao',
        'nome' => 'Enviar e-mail para os usuários administrativos',
        'parametros' => array(
            0 => array(
                'idparametro' => 10,
                'tipo' => 'textarea',
                'nome' => 'E-mails dos usuários administrativos:',
                'valor' => 'Separar os e-mails por ";"'
            ),
            1 => array(
                'idparametro' => 11,
                'tipo' => 'textarea',
                'nome' => 'Texto do e-mail:',
                'valor' => 'Ol&aacute; <strong>[[USUARIO_ADM][NOME]]</strong>,<br /><br />O atendimento #[[ATENDIMENTO][PROTOCOLO]] sofreu uma modifica&ccedil;&atilde;o.<br /><br />Para visualizar a modifica&ccedil;&atilde;o <a href="'.$_SERVER['HTTP_HOST'].'/gestor/relacionamento/atendimentos/[[ATENDIMENTO][IDATENDIMENTO]]/administrar">clique aqui</a>.<br /><br />'
            ),
        ),
    ),
);
