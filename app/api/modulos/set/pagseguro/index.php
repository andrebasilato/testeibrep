<?php

header('access-control-allow-origin: ' . $config['pagSeguro']['url']);

//Se a $url[3] for retorno, é pq está no processo de pagamento de uma conta
if ($url[3] == 'retorno') {
	header('Location: ' . $_SESSION['pagseguro']['retorno'] . '/?idescola=' . $url[4] . '&transactionCode=' . $_GET['transactionCode']);
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/pagseguro.class.php';

$pagSeguroObj = new PagSeguro($url[3]);
$pagSeguro = $pagSeguroObj->retornaNotificacao($_POST['notificationCode']);

$sql = 'SELECT idpagseguro FROM pagseguro WHERE code = "' . $pagSeguro['xml']->code . '"';
$pagSeguro = $pagSeguroObj->retornarLinha($sql);

if ($pagSeguro['idpagseguro']) {
    //Irá consultar o pagamento e atualizar seu status, e da baixa ou estorna a conta, a depender do status
    $atualizar = $pagSeguroObj->atualizaTransacao($pagSeguro['idpagseguro']);
}