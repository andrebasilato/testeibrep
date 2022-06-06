<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/pagarme.class.php';

$pagarmeObj = new PagarmeObj();

$sql = 'SELECT idpagarme FROM pagarme WHERE id = ' . $_POST['id'];
$pagarme = $pagarmeObj->retornarLinha($sql);

if ($pagarme['idpagarme']) {
    //Irá consultar o pagamento e atualizar seu status, e da baixa ou estorna a conta, a depender do status
    $atualizar = $pagarmeObj->atualizaTransacao($pagarme['idpagarme']);
}