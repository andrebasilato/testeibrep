<?php
$situacaoRenegociada = $linhaObj->retornarSituacaoRenegociada();
$situacaoCancelada = $linhaObj->retornarSituacaoCancelada();
$situacaoTransferida = $linhaObj->retornarSituacaoTransferida();
$eventoMensalidade = $linhaObj->retornarEventoMensalidade();

// DIN – Dinheiro
// CHQ – Cheque
// BOL – Boleto
// DEP – Depósito
// CAR – Cartão
// OUT - Outros
$formaPagamentoXML = array(
    1 => 'BOL',
    2 => 'CAR',
    3 => 'CAR',
    4 => 'CHQ',
    5 => 'DIN',
    6 => 'DEP',
    7 => 'DEP',
    8 => 'OUT'
);

$simpleXml = simplexml_load_file(dirname(__FILE__) . '/model.xml');
$simpleXml->versao = '1.00';
$simpleXml->unidade = $unidade;
$simpleXml->lote = str_pad($linha['idfechamento'], 6, '0', STR_PAD_LEFT);
$contador = 0;

if ('xmlporperiodo' == $url[3] && ! empty($_GET["idconta_corrente"])) {
    $linhaObj->executaSQL("START TRANSACTION;");
}

foreach ($contas as $conta) {
    if ('xmlporperiodo' == $url[3] && ! empty($_GET["idconta_corrente"])) {
        $linhaObj->cadastrarContaCorrenteXML($conta["idconta"], $_GET["idconta_corrente"]);
    }

    if(!$conta['idpagamento_compartilhado']) {

        $parcela = $conta['parcela'];
        $qtdparcelas = $conta['total_parcelas'];
        $idcurso = null;
        if ($conta['idmatricula']) {
            $informacoes = $linhaObj->retornarMatricula($conta['idmatricula']);
            $idcurso = $informacoes->idcurso;

            if ($conta['idevento'] == $eventoMensalidade['idevento']) {
                $qtdparcelas = $informacoes->quantidade_parcelas;

                $ordemParcela = $linhaObj->retornarOrdemParcela($conta, $situacaoRenegociada['idsituacao'], $situacaoCancelada['idsituacao'], $situacaoTransferida['idsituacao'], $conta['idevento']);
                $parcela = $ordemParcela[$conta['idconta']];
            }

        } elseif ($conta['idpessoa']) {
            $informacoes = $linhaObj->retornarPessoa($conta['idpessoa']);
        }/* else {
            $informacoes = $linhaObj->retornarCliente($conta['idcliente']);
        }*/
        $simpleXml->cupomfiscal[$contador] = new StdClass;

        $simpleXml->cupomfiscal[$contador]->aluno->cpf = $conta['documento'];
        $simpleXml->cupomfiscal[$contador]->aluno->matricula = $conta['idmatricula'];
        $simpleXml->cupomfiscal[$contador]->aluno->curso = $idcurso;
        $simpleXml->cupomfiscal[$contador]->aluno->nome = $conta['pessoa'];
        $simpleXml->cupomfiscal[$contador]->aluno->endereco = $conta['escola_endereco'];
        $simpleXml->cupomfiscal[$contador]->aluno->numero = $conta['escola_numero'];
        $simpleXml->cupomfiscal[$contador]->aluno->complemento = $conta['escola_complemento'];
        $simpleXml->cupomfiscal[$contador]->aluno->bairro = $conta['escola_bairro'];
        $simpleXml->cupomfiscal[$contador]->aluno->municipio = $conta['cidade'];
        $simpleXml->cupomfiscal[$contador]->aluno->codmunic = $conta['escola_cidade_codigo'];
        $simpleXml->cupomfiscal[$contador]->aluno->cep = $conta['escola_cep'];
        $simpleXml->cupomfiscal[$contador]->aluno->uf = $conta['escola_sigla'];
        $simpleXml->cupomfiscal[$contador]->aluno->fone = $conta['escola_telefone'];
        $simpleXml->cupomfiscal[$contador]->aluno->email = $conta['email'];

        $simpleXml->cupomfiscal[$contador]->pagamento->datapgto = $conta['data_pagamento'];
        $simpleXml->cupomfiscal[$contador]->pagamento->valor = $conta['valor'];
        $simpleXml->cupomfiscal[$contador]->pagamento->formapgto = $formaPagamentoXML[$conta['forma_pagamento']];
        $simpleXml->cupomfiscal[$contador]->pagamento->parcela = $parcela;
        $simpleXml->cupomfiscal[$contador]->pagamento->qtdparcelas = $qtdparcelas;

        if($conta["idevento"] == $eventoMensalidade["idevento"]){
            $simpleXml->cupomfiscal[$contador]->pagamento->valorcontrato = $informacoes->valor_contrato;
        }else{
            $simpleXml->cupomfiscal[$contador]->pagamento->valorcontrato = $conta['valor'];
        }
        $simpleXml->cupomfiscal[$contador]->pagamento->datacontrato = $informacoes->data_matricula;

        $contador++;
    } else {
        $qtdparcelas = $conta['total_parcelas'];

        $matriculas = $linhaObj->retornarMatriculasCompartilhadas($conta['idpagamento_compartilhado']);
        foreach ($matriculas['matriculas'] as $matricula) {
            $conta['idmatricula'] = $matricula['idmatricula'];
            $parcela = $conta['parcela'];

            $idcurso = null;
            $informacoes = $linhaObj->retornarMatricula($matricula['idmatricula']);
            $idcurso = $informacoes->idcurso;
            if ($conta['idevento'] == $eventoMensalidade['idevento']) {
                $qtdparcelas = $informacoes->quantidade_parcelas;

                $ordemParcela = $linhaObj->retornarOrdemParcela($conta, $situacaoRenegociada['idsituacao'], $situacaoCancelada['idsituacao'], $situacaoTransferida['idsituacao'], $conta['idevento'], true);
                $parcela = $ordemParcela[$conta['idconta']];
            }
            $simpleXml->cupomfiscal[$contador] = new StdClass;

            $simpleXml->cupomfiscal[$contador]->aluno->cpf = $conta['documento'];
            $simpleXml->cupomfiscal[$contador]->aluno->matricula = $conta['idmatricula'];
            $simpleXml->cupomfiscal[$contador]->aluno->curso = $idcurso;
            $simpleXml->cupomfiscal[$contador]->aluno->nome = $conta['pessoa'];
            $simpleXml->cupomfiscal[$contador]->aluno->endereco = $conta['escola_endereco'];
            $simpleXml->cupomfiscal[$contador]->aluno->numero = $conta['escola_numero'];
            $simpleXml->cupomfiscal[$contador]->aluno->complemento = $conta['escola_complemento'];
            $simpleXml->cupomfiscal[$contador]->aluno->bairro = $conta['escola_bairro'];
            $simpleXml->cupomfiscal[$contador]->aluno->municipio = $conta['cidade'];
            $simpleXml->cupomfiscal[$contador]->aluno->codmunic = $conta['escola_cidade_codigo'];
            $simpleXml->cupomfiscal[$contador]->aluno->cep = $conta['escola_cep'];
            $simpleXml->cupomfiscal[$contador]->aluno->uf = $conta['escola_sigla'];
            $simpleXml->cupomfiscal[$contador]->aluno->fone = $conta['escola_telefone'];
            $simpleXml->cupomfiscal[$contador]->aluno->email = $conta['email'];

            $simpleXml->cupomfiscal[$contador]->pagamento->datapgto = $conta['data_pagamento'];
            $simpleXml->cupomfiscal[$contador]->pagamento->valor = number_format(($matricula["valor"] / $matriculas['total_contas']), 2, ".", "");
            $simpleXml->cupomfiscal[$contador]->pagamento->formapgto = $formaPagamentoXML[$conta['forma_pagamento']];
            $simpleXml->cupomfiscal[$contador]->pagamento->parcela = $parcela;
            $simpleXml->cupomfiscal[$contador]->pagamento->qtdparcelas = $qtdparcelas;

            $simpleXml->cupomfiscal[$contador]->pagamento->valorcontrato = $informacoes->valor_contrato;

            if($contas["idevento"] == $eventoMensalidade["idevento"]){
                $simpleXml->cupomfiscal[$contador]->pagamento->valorcontrato = $informacoes->valor_contrato;
            }else{
                $simpleXml->cupomfiscal[$contador]->pagamento->valorcontrato = $conta['valor'];
            }
            $simpleXml->cupomfiscal[$contador]->pagamento->datacontrato = $informacoes->data_matricula;

            $contador++;
        }
    }
}

$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($simpleXml->asXML());

// header('Content-Type: text/plain');
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="cf' . date('Ymd', time()) . '.xml"');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');

echo $dom->saveXML();

if ('xmlporperiodo' == $url[3] && ! empty($_GET["idconta_corrente"])) {
    $linhaObj->executaSQL("COMMIT;");
}
