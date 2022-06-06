<?php
require_once $caminhoApp . '/app/classes/fastconnect.class.php';

require_once $caminhoApp . '/app/classes/escolas.class.php';

$escolasObj = new Escolas();
$escolasObj->set('campos', 'p.fastconnect_client_key, p.fastconnect_client_code');

$fastConnectObj = new FastConnect();

//Retorna as contas que tenham pagamento sem retorno do FastConnect
//$fastConnectObj->inserirPagamentosPorLink(array("dt_venda" => date("Y-m-d")));
$contasSemRetorno = $fastConnectObj->retornaPagamentosSemRetorno();

foreach ($contasSemRetorno as $ind => $var) {
    $escolasObj->set('id', $var['idescola']);
    $escola = $escolasObj->retornar();
    if (!empty($escola['fastconnect_client_code']) && !empty($escola['fastconnect_client_code'])) {
        $fastConnectObj = new FastConnect(
            $escola['fastconnect_client_code'],
            $escola['fastconnect_client_key']
        );
    }
    //Irá consultar o pagamento e atualizar seu status, e da baixa ou estorna a conta, a depender do status
    $fastConnectObj->atualizaTransacao($var['idfastconnect'], true);
}
