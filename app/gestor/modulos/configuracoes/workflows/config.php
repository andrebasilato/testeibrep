<?php

$config['funcionalidade'] = 'funcionalidade';
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/workflows_32.png";
$config['acoes'][1] = 'visualizar';

$config['workflows'] = array(
    'atendimentos' => array(
        'titulo' => 'Workflow de atendimentos',
        'arquivo' => 'workflow.atendimentos.php',
        'banco' => 'atendimentos_workflow',
        'tabela' => 'atendimentos',
        'tipos' => array(
            'acao' => 'Ação',
            'prerequisito' => 'Pre-requisito'
        ),
        'flags' => array(
            'inicio' => 'Início',
            'fim' => 'Fim',
            'respondido_cliente' => 'Mensagem Cliente',
            'respondido_gestor' => 'Mensagem Gestor'
        )
    ),
    'contas' => array(
        'titulo' => 'Workflow de contas',
        'arquivo' => 'workflow.contas.php',
        'banco' => 'contas_workflow',
        'tabela' => 'contas',
        'tipos' => array(
            'acao' => 'Ação',
            'prerequisito' => 'Pre-requisito'
        ),
        'flags' => array(
            'faturar' => 'A Faturar',
            'emaberto' => 'Em aberto',
            'pago' => 'Pago',
            'cancelada' => 'Cancelada',
            'renegociada' => 'Renegociada',
            'transferida' => 'Transferida',
            'pagseguro' => 'PagSeguro',
            'fastconnect' => 'FastConnect'
        )
    ),
    'matriculas' => array(
        'titulo' => 'Workflow de matriculas',
        'arquivo' => 'workflow.matriculas.php',
        'banco' => 'matriculas_workflow',
        'tabela' => 'matriculas',
        'tipos' => array(
            'acao' => 'Ação',
            'prerequisito' => 'Pre-requisito'
        ),
        'flags' => array(
            'inicio' => 'Pré-Matrícula',
            'aprovado_comercial' => 'Aprovado Financeiro',
            'ativa' => 'Em Curso',
            'homologar_certificado' => 'Homologar Certificado',
            'cancelada' => 'Cancelada',
            'fim' => 'Concluído',
            'inativa' => 'Inativo',
            'aprovado_pendencias' => 'Aprovado Pendencias',
            'diploma_expedido' => 'Diploma Expedido',
            'diploma' => 'Diploma Aguardando',
            'aprovado' => 'Aprovado',
        )
    ),
    'ofertas' => array(
        'titulo' => 'Workflow de ofertas',
        'arquivo' => 'workflow.ofertas.php',
        'banco' => 'ofertas_workflow',
        'tabela' => 'ofertas',
        'tipos' => array(
            'acao' => 'Ação',
            'prerequisito' => 'Pre-requisito'
        ),
        'flags' => array(
            'inicio' => 'Início',
            'cancelada' => 'Cancelada'
        )
    )
);

if ($url[2] == 'workflows') {
    if (! $config['modulos']['workflow']['atendimentos']) {
        unset($config['workflows']['atendimentos']);
    }

    if (! $config['modulos']['workflow']['contas']) {
        unset($config['workflows']['contas']);
    }

    if (! $config['modulos']['workflow']['matriculas']) {
        unset($config['workflows']['matriculas']);
    }

    if (! $config['modulos']['workflow']['ofertas']) {
        unset($config['workflows']['ofertas']);
    }
}

$workflowObj = new Workflow();

foreach ($config['workflows'] as $ind => $workflow) {
    $config['acoes'][$ind] = $workflow['tabela'];
    $config['acoes'][$ind . '_alterar'] = $workflow['tabela'] . '_alterar';
    if (!$workflowObj->verificaPermissao($perfil['permissoes'], $url[2] . '|' . $workflow['tabela'], false)) {
        unset($config['workflows'][$ind]);
    }
}
