<?php

$diretorio = dirname(__FILE__);
require_once $diretorio . '/../classes/funcoesComuns.php';
require_once $diretorio . '/idioma.php';
require_once $diretorio . '/../classes/Login.php';
$funcoesComuns = new \OrIO\FuncoesComuns();

$funcoesComuns->adicionarHeaders();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '405', 'mensagem' => $idioma['erro_metodo_nao_permitido']];
    echo json_encode($retorno);
    exit;
}

if (!isset($_POST['usuario']) || !isset($_POST['senha'])) {
    $funcoesComuns->adicionarCabecalhoJson();
    $retorno = ['codigo' => '400', 'mensagem' => $idioma['erro_parametros_nao_informados']];
    echo json_encode($retorno);
    exit;
}

$cabecalho = $_SERVER;

$loginObj = new Login($funcoesComuns);
$transacoesObj = new Transacoes();

$email_escape = addslashes(strtolower($_POST['usuario']));
$senha = senhaSegura($_POST['senha'], $config['chaveLogin']);

define('INTERFACE_LOGIN', retornarInterface('login')['id']);
$inicioExecucao = tempoExecucao();
$transacoesObj->iniciaTransacao(INTERFACE_LOGIN, 'E');

try {
    $retorno = [];
    $retorno['codigo'] = 1;
    if(!isset($url[3])) {
        $retorno['dados'] = $loginObj->realizarLogin($email_escape, $senha);
    } elseif(isset($url[3]) && $url[3] == "v2") {
        $retorno['dados'] = $loginObj->realizarLoginV2($email_escape, $senha);
    }

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

