<?php

$diretorio = dirname(__FILE__);
require_once $diretorio . '/../classes/funcoesComuns.php';
require_once $diretorio . '/idioma.php';
require_once $diretorio . '/../classes/Matricula.php';
$funcoesComuns = new \OrIO\FuncoesComuns();

$funcoesComuns->adicionarHeaders();

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '405', 'mensagem' => $idioma['erro_metodo_nao_permitido']];
    echo json_encode($retorno);
    exit;
}

if (!isset($_SERVER['HTTP_EMAIL']) || !isset($_SERVER['HTTP_EMAIL'])) {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '400', 'mensagem' => $idioma['erro_parametros_nao_informados']];
    echo json_encode($retorno);
    exit;
}

$matriculaObj = new Matricula($funcoesComuns);
$transacoesObj = new Transacoes();

$email_escape = addslashes(strtolower($_SERVER['HTTP_EMAIL']));
$senha = senhaSegura($_SERVER['HTTP_SENHA'], $config['chaveLogin']);

define('INTERFACE_MATRICULA_ALTERACAO', retornarInterface('matricula_alteracao')['id']);
$inicioExecucao = tempoExecucao();
$transacoesObj->iniciaTransacao(INTERFACE_MATRICULA_ALTERACAO, 'E');

try {
    $funcoesComuns->adicionarCabecalhoJson();

    $usuario = $matriculaObj->autenticar($email_escape, $senha);
    $matriculaObj->idusuario = $usuario['idusuario'];
    $matriculaObj->config =  $config;

    $retorno = [];
    $retorno['codigo'] = 200;

    $matriculaObj->campos = '
        m.idmatricula,
        m.idpessoa,
        mc.nome AS motivo_cancelamento,
        mw.nome AS situacao,
        i.nome AS instituicao,
        c.nome AS curso,
        p.nome_fantasia AS polo,
        v.nome AS vendedor,
        m.data_cad AS data_matricula,
        m.data_registro,
        m.data_conclusao,
        m.bolsa,
        m.combo,
        e.nome AS empresa,
        m.valor_contrato';

    $retorno['dados'] = $matriculaObj->retornarMatriculaAlteracao();
    $transacoesObj->finalizaTransacao(null, 2);
    $funcoesComuns->adicionarCabecalhoJson('200');
    echo json_encode($retorno);
} catch (Exception $e) {
    $retorno['codigo'] = $e->getCode();
    $retorno['mensagem'] = $idioma[$e->getMessage()];
    $transacoesObj->finalizaTransacao(null, 3, json_encode(['codigo' => $e->getCode(), 'mensagem' => $e->getMessage()]));
    $funcoesComuns->adicionarCabecalhoJson($retorno['codigo']);
    echo json_encode($retorno);
}
