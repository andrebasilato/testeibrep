<?php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/core.class.php';
$coreObj = new Core();
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/fastconnect.class.php';

$sql = "SELECT e.idescola, e.fastconnect_client_code, e.fastconnect_client_key FROM contas c INNER JOIN matriculas m ON (c.idmatricula = m.idmatricula) INNER JOIN escolas e ON (m.idescola = e.idescola) WHERE idconta = ".$url[3]."";

$escola = $coreObj->retornarLinha($sql);

$fastconnectObj = new FastConnect($escola['fastconnect_client_code'], $escola['fastconnect_client_key']);

$dados = json_decode(file_get_contents('php://input'), true);
$dados['idescola'] = $escola['idescola'];

$fastconnect = $fastconnectObj->inserirPagamentosPorLinkApi($dados, $url[3]);

if ($fastconnect['sucesso']) {
    //Irá consultar o pagamento e atualizar seu status, e da baixa ou estorna a conta, a depender do status
    $atualizar = $fastconnectObj->atualizaTransacao($fastconnect['idfastconnect'], true);
}