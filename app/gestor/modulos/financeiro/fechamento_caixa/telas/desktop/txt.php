<?php
$urlSegment = (object)parse_url($_SERVER['REQUEST_URI']);

$closedBox = $linhaObj->fetchClosedBox($urlSegment->query);
$payments = $linhaObj->fetchAllCounts($urlSegment->query);

$manteiner = $linhaObj->fetchCodeOfMantenable(
    $closedBox['idfechamento']
);

// DIN – Dinheiro
// CHQ – Cheque
// BOL – Boleto
// DEP – Depósito
// CAR – Cartão
// OUT - Outros
$relationPaymentForm = array(
    1 => 'BOL',
    2 => 'CAR',
    3 => 'CAR',
    4 => 'CHQ',
    5 => 'DIN'
);

$arquivo_nome = 'cf' . time() . '.txt';
$arquivo = fopen('../storage/temp/' . $arquivo_nome, 'w');

while ($payment = mysql_fetch_assoc($payments)) {
    $userInformation = $linhaObj->fetchStudentDataById($payment['idpessoa']);
    $classInformation = $linhaObj->fetchDataOfClass($payment['idmatricula']);
    $totalOfParcelas = $linhaObj->fetchTotalParcelasNumber($payment['idmatricula']);

    $linha =
        $userInformation->documento . ';' .
        $payment['idmatricula'] . ';' .
        $classInformation->idcurso . ';' .
        $userInformation->nome . ';' .
        $userInformation->endereco . ';' .
        $userInformation->numero . ';' .
        $userInformation->complemento . ';' .
        $userInformation->bairro . ';' .
        $userInformation->cidade . ';' .
        '' . ';' .
        $userInformation->cep . ';' .
        $userInformation->uf . ';' .
        $userInformation->telefone . ';' .
        $userInformation->email . ';' .
        $payment['data_pagamento'] . ';' .
        $payment['valor'] . ';' .
        $relationPaymentForm[$payment['forma_pagamento']] . ';' .
        $totalOfParcelas->quantity . ';' .
        $payment['parcela'] . ';' .
        ($payment['valor'] * $totalOfParcelas) . ';' .
        date('Y-m-d', strtotime($payment['data_cad'])) . "\r\n";

    fwrite($arquivo, $linha);
}

fclose($arquivo);

header("Content-type: text/plain");
header('Content-Disposition: attachment; filename="cf' . date('Ymd', time()) . '.txt"');
header('Expires: 0');
header('Pragma: no-cache');
readfile($_SERVER["DOCUMENT_ROOT"] . '/storage/temp/' . $arquivo_nome);