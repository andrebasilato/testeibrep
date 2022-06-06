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

if (empty($url[3])) {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '405', 'mensagem' => $idioma['param_nao_informados']];
    echo json_encode($retorno);
    exit;
}

$transacoesObj = new Transacoes();

define('INTERFACE_MATRICULA_LOCAIS_PROVA', retornarInterface('matricula_locais_prova')['id']);
$inicioExecucao = tempoExecucao();
$transacoesObj->iniciaTransacao(INTERFACE_MATRICULA_LOCAIS_PROVA, 'S');

try {
    $funcoesComuns->adicionarCabecalhoJson();

    $retorno = [];
    $aluno = $funcoesComuns->autenticarPessoaPorToken($token);
    $matriculaObj = new Matricula($funcoesComuns);

    $matriculaObj->idmatricula = $url[3];
    $matricula = $matriculaObj->retornar();

    $matriculaObj->campos = 'l.idlocal, l.nome';
    $locais = $matriculaObj->retornarLocaisProvasDisponiveisAluno($matricula['idinstituicao']);
    $retorno['codigo'] = 200;

    $retorno['dados'] = $locais;
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
