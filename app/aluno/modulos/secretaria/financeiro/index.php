<?php

require_once '../classes/matriculas.class.php';
require_once '../classes/pessoas.class.php';
require_once '../classes/pagseguro.class.php';
require_once '../classes/fastconnect.class.php';

if ($_POST['acao'] == 'salvar_pagamento') {
    $matriculaObj = new Matriculas();
    $matriculaObj->set('idpessoa', $usuario['idpessoa']);
    $matriculaObj->set('modulo', $url[0]);

    switch ($_POST['tipo_pagamento']) {
        case 'PS'://Se for PagSeguro
            $pagSeguroObj = new PagSeguro(null, $_POST['idconta']);
            $salvar = $pagSeguroObj->set('post', $_POST)
                ->set('idusuario', $usuario['idusuario'])
                ->set('modulo',  $url[0])
                ->criarTransacao();

            if ($salvar['sucesso']) {
                $pagSeguroObj->set('pro_mensagem_idioma', 'salvar_pagamento_sucesso')
                    ->set('url', '/' . $url[0] . '/' . $url[1] . '/' . $url[2])
                    ->processando();
            }
            break;
    }
}

//Se tiver o transactionCode é porque retornou do PagSeguro, assim já submete o formulário
if (! empty($_GET['transactionCode'])) {
    $pagSeguroObj = new PagSeguro($_GET['idescola']);
    $retornoTransacao = $pagSeguroObj->retornaTransacao($_GET['transactionCode']);

    $_POST['acao'] = 'salvar_pagamento';
    $_POST['idconta'] = str_replace('C_', '', $retornoTransacao['xml']->reference);
    $_POST['tipo_pagamento'] = 'PS';
    $_POST['codigo_transacao_pagseguro'] = $_GET['transactionCode'];
    $informacoes['url'] = '/' . $url[0] . '/' . $url[1] . '/' . $url[2];
    incluirLib('processar_post', $config, $informacoes);
    exit;
}

$matriculaObj = new Matriculas();
$matriculaObj->set('idpessoa', $usuario['idpessoa']);
$matriculaObj->set('modulo', $url[0]);

$situacaoRenegociadaConta = $matriculaObj->retornarSituacaoRenegociadaConta(); 
$situacaoCanceladaConta = $matriculaObj->retornarSituacaoCanceladaConta(); 
$situacaoEmAbertoConta = $matriculaObj->retornarSituacaoEmAbertoConta(); 
$matriculas = $matriculaObj->set('fastConnect', true)
    ->retornarFinanceiroAluno();

$pessoaObj = new Pessoas;
$pessoaObj->set('modulo', $url[0]);
$pessoaObj->Set('id', $usuario['idpessoa']);
$pessoaObj->Set('campos', 'p.nome, p.documento, p.email, CONCAT_WS(" ", l.nome, p.endereco) AS endereco,
    p.numero, p.complemento, p.bairro, cid.nome AS cidade, est.sigla AS uf, p.cep, p.celular, p.telefone, p.data_nasc');
$pessoa = $pessoaObj->retornar();

include 'idiomas/' . $config['idioma_padrao'] . '/index.php';
include 'telas/' . $config['tela_padrao'] . '/index.php';
