<?php

$diretorio = dirname(__FILE__);
require_once $diretorio . '/../classes/funcoesComuns.php';
require_once $diretorio . '/idioma.php';
require_once $diretorio . '/../classes/Matricula.php';
$funcoesComuns = new \OrIO\FuncoesComuns();

$funcoesComuns->adicionarHeaders();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '405', 'mensagem' => $idioma['erro_metodo_nao_permitido']];
    echo json_encode($retorno);
    exit;
}

if (!isset($_SERVER['HTTP_EMAIL']) || !isset($_SERVER['HTTP_SENHA'])) {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '400', 'mensagem' => $idioma['erro_parametros_nao_informados']];
    echo json_encode($retorno);
    exit;
}

$cabecalho = $_SERVER;

$matriculaObj = new Matricula($funcoesComuns);
$transacoesObj = new Transacoes();

$email_escape = addslashes(strtolower($_SERVER['HTTP_EMAIL']));
$senha = senhaSegura($_SERVER['HTTP_SENHA'], $config['chaveLogin']);

define('INTERFACE_MATRICULA', retornarInterface('matricula')['id']);
$inicioExecucao = tempoExecucao();
$transacoesObj->iniciaTransacao(INTERFACE_MATRICULA, 'E');

try {
    $usuario = $matriculaObj->autenticar($email_escape, $senha);
    $matriculaObj->idusuario = $usuario['idusuario'];
    $matriculaObj->config =  $config;
    $dadosMatricula = json_decode(file_get_contents('php://input'), true);

    $retorno = $matriculaObj->cadastrar($dadosMatricula);

    $transacoesObj->finalizaTransacao(null, 2);
    $funcoesComuns->adicionarCabecalhoJson('200');
    echo json_encode($retorno);
} catch (Exception $e) {
    $retorno['codigo'] = $e->getCode();
    $retorno['mensagem'] = $idioma[$e->getMessage()];
    $codHeader = ($e->getCode() != '500') ? '200' : $e->getCode();
    $transacoesObj->finalizaTransacao(null, 3, json_encode(['codigo' => $e->getCode(), 'mensagem' => $e->getMessage()]));
    $funcoesComuns->adicionarCabecalhoJson($codHeader);
    echo json_encode($retorno);
}

