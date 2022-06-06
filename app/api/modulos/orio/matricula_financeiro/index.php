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

$token = $funcoesComuns->getHeaderToken();

if (!isset($token)) {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '401', 'mensagem' => $idioma['erro_token_nao_informado']];
    echo json_encode($retorno);
    exit;
}

$matriculaObj = new Matricula($funcoesComuns);
$transacoesObj = new Transacoes();

$hoje = date('Y-m-d H:s');
$matriculaObj->perido_de = date('Y-m-d', strtotime($hoje . "-90 days"));
$matriculaObj->perido_ate = date('Y-m-d', strtotime($hoje . "+90 days"));

define('INTERFACE_MATRICULA_FINANCEIRO', retornarInterface('matricula_financeiro')['id']);
$inicioExecucao = tempoExecucao();
$transacoesObj->iniciaTransacao(INTERFACE_MATRICULA_FINANCEIRO, 'S');

try {
    $funcoesComuns->adicionarCabecalhoJson();

    $retorno = [];
    $retorno['codigo'] = 200;

    $aluno = $funcoesComuns->autenticarPessoaPorToken($token);
    $retorno['dados'] = $matriculaObj->retornarBoletosEmAberto($aluno['idpessoa']);
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

