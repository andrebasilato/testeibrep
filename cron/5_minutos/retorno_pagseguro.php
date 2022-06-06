<?php

require_once($caminhoApp . '/app/classes/pagseguro.class.php');

$pagSeguroObj = new PagSeguro();

//Retorna as contas que tenham pagamento sem retorno do PagSeguro
$contasSemRetorno = $pagSeguroObj->retornaPagamentosSemRetorno();

foreach ($contasSemRetorno as $ind => $var) {
    $pagSeguroObj->setarDadosPagSeguro(null, $var['idconta']);

    //Irá consultar o pagamento e atualizar seu status, e da baixa ou estorna a conta, a depender do status
    $pagSeguroObj->atualizaTransacao($var['idpagseguro'], true);
}
