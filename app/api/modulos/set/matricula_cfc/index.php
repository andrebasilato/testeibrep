<?php
$diretorio = dirname(__FILE__);
require_once DIR_APP . '/classes/core.class.php';
include_once DIR_APP . '/classes/orio/Transacoes.php';
require_once $diretorio . '/idioma.php';
require_once $diretorio . '/Escola.php';
require_once $diretorio . '/Matricula.php';
require_once 'Gestor.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    adicionarCabecalhoJson(405);
    $retorno = ['codigo' => 405, 'mensagem' => $idioma['erro_metodo_nao_permitido']];
    echo json_encode($retorno);
    exit;
}

if (!isset($_SERVER['HTTP_EMAIL']) || !isset($_SERVER['HTTP_SENHA'])) {
    adicionarCabecalhoJson(400);
    $retorno = ['codigo' => 400, 'mensagem' => $idioma['usuario_ou_senha_nao_informados']];
    echo json_encode($retorno);
    exit;
}

$cabecalho = $_SERVER;
$gestor = new Gestor(new Core());
$matriculaObj = new Matricula(new Core());

$email_escape = addslashes(strtolower($_SERVER['HTTP_EMAIL']));
$senha = senhaSegura($_SERVER['HTTP_SENHA'], $config['chaveLogin']);


$transacaoObj = new \Transacoes();
try {
    $gestor = $gestor->autenticar($email_escape, $senha);
    $matriculaObj->idusuario = $gestor['idusuario'];
    $matriculaObj->config = $config;
    $dadosMatricula = json_decode(file_get_contents('php://input'), true);

    $interface = retornarInterface('matricula_cfc');
    $transacaoObj->set('json', json_encode($dadosMatricula));
    $transacaoObj->iniciaTransacao($interface['id'], 'E');
    $retorno = $matriculaObj->cadastrar($dadosMatricula, $gestor);
    $transacaoObj->set('json', json_encode($retorno));
    $transacaoObj->finalizaTransacao(null, 2);

    adicionarCabecalhoJson(201);
    echo json_encode($retorno);
} catch (InvalidArgumentException $i) {
    adicionarCabecalhoJson(422);
    if (is_object(json_decode($i->getMessage()))) {
        $transacaoObj->finalizaTransacao(null, 3, $i->getMessage());
        echo $i->getMessage();
        exit();
    } else {
        $retorno = [
            'codigo' => 422,
            'mensagem' => $idioma[$i->getMessage()] ? $idioma[$i->getMessage()] : $i->getMessage()
        ];
        $transacaoObj->set('json', json_encode($retorno));
        $transacaoObj->finalizaTransacao(null, 5);
        echo json_encode($retorno);
    }
} catch (UnexpectedValueException $u){
    adicionarCabecalhoJson(422);
    $retorno = [
        'codigo' => 422,
        'mensagem' => $idioma[$u->getMessage()] ? $idioma[$u->getMessage()] : $u->getMessage()
    ];
    $transacaoObj->set('json', json_encode($retorno));
    $transacaoObj->finalizaTransacao(null, 5);
    echo json_encode($retorno);
}catch (Exception $e) {
    $retorno = [
        'codigo' => $e->getCode() ? $e->getCode() : 400,
        'mensagem' => $idioma[$e->getMessage()] ? $idioma[$e->getMessage()] : $e->getMessage()
    ];
    $transacaoObj->set('json', json_encode($retorno));
    $transacaoObj->finalizaTransacao(null, 5);
    adicionarCabecalhoJson($retorno['codigo']);
    echo json_encode($retorno);
}
