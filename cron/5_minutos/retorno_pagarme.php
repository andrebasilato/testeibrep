<?php
require_once($caminhoApp . '/app/classes/pagarme.class.php');

$pagarmeObj = new PagarmeObj();

//Retorna as contas que tenham pagamento sem retorno do pagar.me
$contasSemRetorno = $pagarmeObj->retornaPagamentosSemRetorno();

foreach ($contasSemRetorno as $ind => $var) {
    //Irá consultar o pagamento e atualizar seu status, e da baixa ou estorna a conta, a depender do status
    $atualizar = $pagarmeObj->atualizaTransacao($var['idpagarme'], true);
}
