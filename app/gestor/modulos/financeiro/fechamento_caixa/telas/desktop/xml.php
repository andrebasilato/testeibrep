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

$dados = array();
$eventos = array();
foreach ($contas as $conta) {

    if ('xmlporperiodo' == $url[3] && ! empty($_GET["idconta_corrente"])) {
        $linhaObj->cadastrarContaCorrenteXML($conta["idconta"], $_GET["idconta_corrente"]);
    }
    if(!$conta['idpagamento_compartilhado']) {
        $parcela = $conta['parcela'];
        $qtdparcelas = $conta['total_parcelas'];
        $idcurso = null;
        $informacoes = null;
        if ($conta['idmatricula']) {
            $informacoes = $linhaObj->retornarMatricula($conta['idmatricula']);

            $idcurso = $informacoes->idcurso;
            $idcurso = $config['conflitoCodigoCurso'] && $idcurso == 28 ? 1028 : $idcurso;

            if ($conta['idevento'] == $eventoMensalidade['idevento']) {
                if(intval($informacoes->quantidade_parcelas) > 0)
                    $qtdparcelas = intval($informacoes->quantidade_parcelas);

                $ordemParcela = $linhaObj->retornarOrdemParcela($conta, $situacaoRenegociada['idsituacao'], $situacaoCancelada['idsituacao'], $situacaoTransferida['idsituacao'], $conta['idevento']);
                $parcela = $ordemParcela[$conta['idconta']];
            }
        } elseif ($conta['idpessoa']) {
            $informacoes = $linhaObj->retornarPessoa($conta['idpessoa']);
        } else {
            $informacoes = $linhaObj->retornarMatriculaFatura($conta['idconta']);
        }

        if (!is_array($informacoes)) {
            $informacoes = [$informacoes];
        }

        foreach ($informacoes as $informacao) {
            $simpleXml->cupomfiscal[$contador] = new StdClass;
            $valor_parcela = floatval($informacao->valor) > 0 ? floatval($informacao->valor) : $conta['valor'];

            # Primeiro é escola parceiro se for vamos amarzenar em um vetor e depois passa para o xml
            if ($conta['escola_parceiro'] == 'S') {
                if($conta['fatura'] == 'S') {
                    # A escola não existe, então vamos criar ela no vetor
                    if (!array_key_exists($conta['idescola'], $dados)) {
                        $dados[$conta['idescola']] = array(
                            'cpf' => $conta['escola_documento'],
                            'curso' => 4,
                            'nome' => $conta['escola_razao_social'],
                            'endereco' => $conta['escola_endereco'],
                            'numero' => $conta['escola_numero'],
                            'complemento' => $conta['escola_complemento'],
                            'bairro' => $conta['escola_bairro'],
                            'municipio' => $conta['cidade'],
                            'codmunic' => $conta['escola_cidade_codigo'],
                            'cep' => $conta['escola_cep'],
                            'uf' => $conta['escola_sigla'],
                            'fone' => $conta['escola_telefone'],
                            'email' => $conta['email_escola'],
                            'pagamento' => array(
                                $conta['data_pagamento'] => array(
                                    $parcela => array(
                                        'valor' => $valor_parcela,
                                        'formapgto' => $formaPagamentoXML[$conta['forma_pagamento']],
                                        'parcela' => $parcela,
                                        'qtdparcelas' => $qtdparcelas
                                    )
                                )
                            ),
                            'datacontrato' => (!empty($informacao->data_matricula)) ? $informacao->data_matricula : $conta['data_cadastro_escola'],
                            'total' => $valor_parcela
                        );
                    } else {
                        # A escola existe, vamos verificar a parte de pagamento
                        $data_contrato = (!empty($informacao->data_matricula)) ? $informacao->data_matricula : $conta['data_cadastro_escola'];

                        # Vamos ver se ja não existe essa data de pagamento no vetor da escola
                        if (!array_key_exists($conta['data_pagamento'], $dados[$conta['idescola']]['pagamento'])) {
                            # Opa, não existe então vamos criar ela no vetor de pagamento
                            $dados[$conta['idescola']]['pagamento'][$conta['data_pagamento']] = array(
                                $parcela => array(
                                    'valor' => $valor_parcela,
                                    'formapgto' => $formaPagamentoXML[$conta['forma_pagamento']],
                                    'parcela' => $parcela,
                                    'qtdparcelas' => $qtdparcelas
                                )
                            );

                            # Vamos somar o total para gerar valor de contrato
                            // $dados[$conta['idescola']]['total'] += $valor_parcela;
                        } else {
                            # Data de pagamento ja existe, vamos verificar agora o número da parcela existe
                            if (array_key_exists($parcela, $dados[$conta['idescola']]['pagamento'][$conta['data_pagamento']])) {
                                # Parcela existe, então vamos somar o valor
                                // $dados[$conta['idescola']]['pagamento'][$conta['data_pagamento']][$parcela]['valor'] +=
                                //     $valor_parcela;

                                # Vamos somar o total para gerar valor de contrato
                                // $dados[$conta['idescola']]['total'] += $valor_parcela;

                                # Premissa é sempre ter a maior quantidade de parcelas
                                if ($dados[$conta['idescola']]['pagamento'][$conta['data_pagamento']][$parcela]['qtdparcelas'] < $qtdparcelas) {
                                    $dados[$conta['idescola']]['pagamento'][$conta['data_pagamento']][$parcela]['qtdparcelas'] = $qtdparcelas;
                                }
                            } else {
                                # Parcela não existe, então vamos criar o mesmo
                                $dados[$conta['idescola']]['pagamento'][$conta['data_pagamento']][$parcela] = array(
                                    'valor' => $valor_parcela,
                                    'formapgto' => $formaPagamentoXML[$conta['forma_pagamento']],
                                    'parcela' => $parcela,
                                    'qtdparcelas' => $qtdparcelas
                                );

                                # Vamos somar o total para gerar valor de contrato
                                // $dados[$conta['idescola']]['total'] += $valor_parcela;
                                $dados[$conta['idescola']]['total'] = $valor_parcela;
                            }
                        }

                        # Premissa é sempre ter a data mais antiga para essa escola
                        if ($dados[$conta['idescola']]['datacontrato'] > $data_contrato) {
                            $dados[$conta['idescola']]['datacontrato'] = $data_contrato;
                        }
                    }
                }
            } else {
                if($conta['fatura'] == 'N') {
                    # Verificando se não existe no vetor a matricula
                    if(!array_key_exists($informacao->idmatricula, $eventos)){
                        $eventos[$informacao->idmatricula] = array();
                    }

                    # Verificando se o evento é do tipo mensalidade, caso sim, o valor de contrato vem da matricula
                    $valor_contrato = 0;
                    if($conta['idevento'] == $eventoMensalidade['idevento']) {
                        $valor_contrato = $informacao->valor_contrato;
                    }

                    # Verificando se a tag pagamento ja foi criado
                    if(array_key_exists('pagamentos', $eventos[$informacao->idmatricula][$conta['idevento']])) {
                        // $eventos[$informacao->idmatricula][$conta['idevento']]['pagamentos']['valor'] += $valor_parcela;

                        # A Premissa é sempre ter o maior quantidade de parcelas
                        if($eventos[$informacao->idmatricula][$conta['idevento']]['pagamentos']['qtdparcelas'] < $qtdparcelas){
                            $eventos[$informacao->idmatricula][$conta['idevento']]['pagamentos']['qtdparcelas'] =
                                $qtdparcelas;
                        }

                        # A Premissa é sempre ser o numero da maior parcela
                        if($eventos[$informacao->idmatricula][$conta['idevento']]['pagamentos']['parcela'] < $parcela){
                            $eventos[$informacao->idmatricula][$conta['idevento']]['pagamentos']['parcela'] =
                                $parcela;
                        }
                    }

                    # Verificando se não existe no vetor o evento
                    if(!array_key_exists($conta['idevento'], $eventos[$informacao->idmatricula])){
                        $eventos[$informacao->idmatricula][$conta['idevento']] = array(
                            'cpf' => $informacao->documento,
                            'matricula' => $informacao->idmatricula,
                            'curso' => (!empty($idcurso)) ? $idcurso : 4,
                            'nome' => $informacao->nome,
                            'endereco' => $informacao->endereco,
                            'numero' => $informacao->numero,
                            'complemento' => $informacao->complemento,
                            'bairro' => $informacao->bairro,
                            'municipio' => $informacao->cidade,
                            'codmunic' => $informacao->codigo,
                            'cep' => $informacao->cep,
                            'uf' => $informacao->sigla,
                            'fone' => $informacao->telefone,
                            'email' => $informacao->email,
                            'pagamentos' => array(
                                'datapgto' => $conta['data_pagamento'],
                                'valor' => $valor_parcela,
                                'formapgto' => $formaPagamentoXML[$conta['forma_pagamento']],
                                'parcela' => $parcela,
                                'qtdparcelas' => $qtdparcelas,
                                'datacontrato' =>
                                    (!empty($informacao->data_matricula)) ? $informacao->data_matricula : $conta['data_cadastro_escola'],
                                'valorcontrato' => $valor_contrato
                            ),
                            'tipo_mensalidade' => $conta['idevento'] == $eventoMensalidade['idevento']
                        );
                    }
                }
            }
        }

    }
}

foreach ($dados as $key => $informacao) {
    foreach ($informacao['pagamento'] as $data_pagamento => $pagamento) {
        foreach ($pagamento as $parcela_numero => $parcela) {
            $simpleXml->cupomfiscal[$contador] = new StdClass;
            $simpleXml->cupomfiscal[$contador]->aluno->cpf = $informacao['cpf'];
            $simpleXml->cupomfiscal[$contador]->aluno->matricula = $key;
            $simpleXml->cupomfiscal[$contador]->aluno->curso = 4;
            $simpleXml->cupomfiscal[$contador]->aluno->nome = $informacao['nome'];
            $simpleXml->cupomfiscal[$contador]->aluno->endereco = $informacao['endereco'];
            $simpleXml->cupomfiscal[$contador]->aluno->numero = $informacao['numero'];
            $simpleXml->cupomfiscal[$contador]->aluno->complemento = $informacao['complemento'];
            $simpleXml->cupomfiscal[$contador]->aluno->bairro = $informacao['bairro'];
            $simpleXml->cupomfiscal[$contador]->aluno->municipio = $informacao['municipio'];
            $simpleXml->cupomfiscal[$contador]->aluno->codmunic = $informacao['codmunic'];
            $simpleXml->cupomfiscal[$contador]->aluno->cep = $informacao['cep'];
            $simpleXml->cupomfiscal[$contador]->aluno->uf = $informacao['uf'];
            $simpleXml->cupomfiscal[$contador]->aluno->fone = $informacao['fone'];
            $simpleXml->cupomfiscal[$contador]->aluno->email = $informacao['email'];

            $simpleXml->cupomfiscal[$contador]->pagamento->datapgto = $data_pagamento;
            $simpleXml->cupomfiscal[$contador]->pagamento->valor = number_format($parcela['valor'], 2, '.', '');
            $simpleXml->cupomfiscal[$contador]->pagamento->formapgto = $parcela['formapgto'];
            $simpleXml->cupomfiscal[$contador]->pagamento->parcela = $parcela_numero;
            if(intval($parcela['qtdparcelas']) == 0)
                $simpleXml->cupomfiscal[$contador]->pagamento->qtdparcelas = count($informacao['pagamento']);
            else
                $simpleXml->cupomfiscal[$contador]->pagamento->qtdparcelas = $parcela['qtdparcelas'];

            $simpleXml->cupomfiscal[$contador]->pagamento->valorcontrato = number_format($parcela['valor'], 2, '.', '');
            // $simpleXml->cupomfiscal[$contador]->pagamento->valor = number_format($parcela['valor'], 2, '.', '');
            $simpleXml->cupomfiscal[$contador]->pagamento->datacontrato = $informacao['datacontrato'];
            $contador++;
        }
    }
}

foreach ($eventos as $matricula => $evento) {
    foreach ($evento as $key => $informacao) {
        $simpleXml->cupomfiscal[$contador] = new StdClass;
        $simpleXml->cupomfiscal[$contador]->aluno->cpf = $informacao['cpf'];
        $simpleXml->cupomfiscal[$contador]->aluno->matricula = $matricula;
        $simpleXml->cupomfiscal[$contador]->aluno->curso = $informacao['curso'];
        $simpleXml->cupomfiscal[$contador]->aluno->nome = $informacao['nome'];
        $simpleXml->cupomfiscal[$contador]->aluno->endereco = $informacao['endereco'];
        $simpleXml->cupomfiscal[$contador]->aluno->numero = $informacao['numero'];
        $simpleXml->cupomfiscal[$contador]->aluno->complemento = $informacao['complemento'];
        $simpleXml->cupomfiscal[$contador]->aluno->bairro = $informacao['bairro'];
        $simpleXml->cupomfiscal[$contador]->aluno->municipio = $informacao['municipio'];
        $simpleXml->cupomfiscal[$contador]->aluno->codmunic = $informacao['codmunic'];
        $simpleXml->cupomfiscal[$contador]->aluno->cep = $informacao['cep'];
        $simpleXml->cupomfiscal[$contador]->aluno->uf = $informacao['uf'];
        $simpleXml->cupomfiscal[$contador]->aluno->fone = $informacao['fone'];
        $simpleXml->cupomfiscal[$contador]->aluno->email = $informacao['email'];
        $simpleXml->cupomfiscal[$contador]->pagamento->datapgto = $informacao['pagamentos']['datapgto'];
        $simpleXml->cupomfiscal[$contador]->pagamento->valor = number_format($informacao['pagamentos']['valor'], 2, '.', '');
        $simpleXml->cupomfiscal[$contador]->pagamento->formapgto = $informacao['pagamentos']['formapgto'];
        $simpleXml->cupomfiscal[$contador]->pagamento->parcela = $informacao['pagamentos']['parcela'];
        $simpleXml->cupomfiscal[$contador]->pagamento->qtdparcelas = $informacao['pagamentos']['qtdparcelas'];

        if($informacao['tipo_mensalidade'])
            $simpleXml->cupomfiscal[$contador]->pagamento->valorcontrato = number_format($informacao['pagamentos']['valorcontrato'], 2, '.', '');
        else {
            $valor_contrato = $linhaObj->consultarValorEventosFinanceiro(
                $key, $matricula
            );
            $simpleXml->cupomfiscal[$contador]->pagamento->valorcontrato = number_format(floatval($valor_contrato), 2, '.', '');
        }
        $simpleXml->cupomfiscal[$contador]->pagamento->datacontrato = $informacao['pagamentos']['datacontrato'];
        $contador++;
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
