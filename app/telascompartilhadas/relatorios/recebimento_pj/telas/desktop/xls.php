<?php

error_reporting(E_ERROR);
date_default_timezone_set('America/Recife');

$template = $_SERVER['DOCUMENT_ROOT'] . '/telascompartilhadas/' . $url[1] . '/' . $url[2] . '/telas/' . $config['tela_padrao'] . '/' . 'xls_' . $config['idioma_padrao'] . '.xls';
$nome_arquivo = $url[1] . '_' . $url[2] . '_' . time() . '.xls';
$arquivo_gerado = $_SERVER['DOCUMENT_ROOT'] . '/storage/temp/' . $nome_arquivo;

require_once '../classes/phpexcel/classes/PHPExcel/IOFactory.php';
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load($template);
$sheet = $objPHPExcel->getActiveSheet();

// Data e Hora que foi gerado
$sheet->setCellValue('A7', 'Gerado dia ' . date('d/m/Y H:i:s') . ' por ' . $usuario['nome'] . ' (' . $usuario['email'] . ')');

$estiloMesAno = [
    'font' => [
        'bold' => true,
    ],
    'fill' => [
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => ['rgb' => 'CECECE']
    ]
];

$estiloTabelaSecundaria = [
    'borders' => [
        'allborders' => [
            'style' => 'thin',
            'color' => ['rgb' => '000000']
        ]
    ],
    'alignment' => [
        'horizontal' => 'center'
    ]
];

$negritoDireita = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => 'right'
    ]
];

$linhaBase = 4;
if (count($dadosArray) > 0) {
    $sheet->insertNewRowBefore($linhaBase,count($dadosArray));

    $valorTotalMediaMatriculaMes = 0;
    $qntValoreslMediaMatriculaMes = 0;
    $valorTotalMes = 0;
    $escolas = [];
    $totalMatriculas = 0;
    $valorTotal = 0;
    $valorTotalPagarme = 0;
    $escolasInadimplentes = [];

    $linha = $linhaBase - 1;
    foreach ($dadosArray as $ind => $dados) {
        $linha++;

        $mesAnoCadastro = (new \DateTime($dados['data_cad']))->format('Y-m');
        if ($mesAnoAgrupado != $mesAnoCadastro) {
            $mesAnoAgrupado = $mesAnoCadastro;
            $mesCadastro = $GLOBALS['meses_idioma'][$GLOBALS['config']['idioma_padrao']][(new \DateTime($dados['data_cad']))->format('m')];
            $anoCadastro = (new \DateTime($dados['data_cad']))->format('Y');

            if ($ind > 0) {
                $sheet->insertNewRowBefore($linha + 1, 1);

                $sheet->mergeCells('A' . $linha . ':F' . $linha);
                $sheet->mergeCells('I' . $linha . ':M' . $linha);
                $sheet->setCellValue('G' . $linha, 'R$ ' . number_format($valorTotalMediaMatriculaMes / $qntValoreslMediaMatriculaMes, 2, ',', '.'));
                $sheet->setCellValue('H' . $linha, 'R$ ' . number_format($valorTotalMes, 2, ',', '.'));

                $linha++;

                $valorTotalMediaMatriculaMes = 0;
                $qntValoreslMediaMatriculaMes = 0;
                $valorTotalMes = 0;
            }

            $sheet->insertNewRowBefore($linha + 1, 1);

            $sheet->mergeCells('A' . $linha . ':M' . $linha);
            $sheet->setCellValue('A' . $linha, mb_strtoupper($mesCadastro . ' ' . $anoCadastro, 'UTF-8'))
                ->getStyle('A' . $linha)
                ->applyFromArray($estiloMesAno);

            $linha++;
        }

        $valorTotalMediaMatriculaMes += $dados['media_por_matricula'];
        $qntValoreslMediaMatriculaMes++;
        $valorTotalMes += $dados['valor'];
        $escolas[$dados['idescola']] = $dados['idescola'];
        $totalMatriculas += $dados['qnt_matriculas'];
        $valorTotal += $dados['valor'];

        if ($dados['conta_pago'] == 'S') {
            $valorTotalPagarme += $dados['valor'];
        }

        if (
            $dados['conta_pago'] == 'N'
            && $dados['conta_renegociada'] == 'N'
            && $dados['conta_transferida'] == 'N'
            && $dados['conta_cancelada'] == 'N'
            && (new \DateTime($dados['data_vencimento']))->format('Y-m-d') < (new \DateTime)->format('Y-m-d')
        ) {
            $escolasInadimplentes[$dados['idescola']] = $dados['idescola'];
        }

        $dataCad = new DateTime($dados['data_cad']);
        $dataDe = $dataCad->format("01/m/Y");
        $dataAte = $dataCad->format("15/m/Y");

        if ($dataCad->format("d") >= 16) {
            $dataDe = $dataCad->format("16/m/Y");
            $dataAte = $dataCad->format("t/m/Y");
        }

        $sheet->setCellValue('A' . $linha, $dados['sindicato']);
        $sheet->setCellValue('B' . $linha, $dados['escola']);
        $sheet->setCellValue('C' . $linha, $dados['idconta']);
        $sheet->setCellValue('D' . $linha, $dataDe);
        $sheet->setCellValue('E' . $linha, $dataAte);
        $sheet->setCellValue('F' . $linha, $dados['qnt_matriculas']);
        $sheet->setCellValue('G' . $linha, 'R$ ' . number_format($dados['media_por_matricula'], 2, ',', '.'));
        $sheet->setCellValue('H' . $linha, 'R$ ' . number_format($dados['valor'], 2, ',', '.'));
        $sheet->setCellValue('I' . $linha, $dados['situacao']);
        $sheet->setCellValue('J' . $linha, formataData($dados['data_vencimento'], "br", 0));
        $sheet->setCellValue('K' . $linha, formataData($dados['data_pagamento'], "br", 0));
        $sheet->setCellValue('L' . $linha, formataData($dados['data_prevista_disponivel_pagarme'], "br", 0));
        $sheet->setCellValue('M' . $linha, $dados['pagarme_id']);
    }

    $letraTotal1 = 'G';
    $letraTotal2 = 'H';
    if ($url[0] == 'cfc') {
        $letraTotal1 = 'F';
        $letraTotal2 = 'G';
        $objPHPExcel->getActiveSheet()->removeColumn('B');
    }

    $linha++;
    $sheet->setCellValue($letraTotal1 . $linha, 'R$ ' . number_format($valorTotalMediaMatriculaMes / $qntValoreslMediaMatriculaMes, 2, ',', '.'));
    $sheet->setCellValue($letraTotal2 . $linha, 'R$ ' . number_format($valorTotalMes, 2, ',', '.'));

    $linha = $linha + 2;
    $sheet->insertNewRowBefore($linha, 5);

    $sheet->mergeCells('A' . $linha . ':B' . $linha);
    $sheet->mergeCells('C' . $linha . ':D' . $linha);
    $sheet->setCellValue('A' . $linha, 'Total de CFC:');
    $sheet->setCellValue('C' . $linha, count($escolas));

    $sheet->getStyle('A' . $linha)->applyFromArray(array_merge($estiloTabelaSecundaria, $negritoDireita));
    $sheet->getStyle('B' . $linha)->applyFromArray(array_merge($estiloTabelaSecundaria, $negritoDireita));
    $sheet->getStyle('C' . $linha)->applyFromArray($estiloTabelaSecundaria);
    $sheet->getStyle('D' . $linha)->applyFromArray($estiloTabelaSecundaria);

    $linha++;
    $sheet->mergeCells('A' . $linha . ':B' . $linha);
    $sheet->mergeCells('C' . $linha . ':D' . $linha);
    $sheet->setCellValue('A' . $linha, 'Total de Matrículas:');
    $sheet->setCellValue('C' . $linha, $totalMatriculas);

    $sheet->getStyle('A' . $linha)->applyFromArray(array_merge($estiloTabelaSecundaria, $negritoDireita));
    $sheet->getStyle('B' . $linha)->applyFromArray(array_merge($estiloTabelaSecundaria, $negritoDireita));
    $sheet->getStyle('C' . $linha)->applyFromArray($estiloTabelaSecundaria);
    $sheet->getStyle('D' . $linha)->applyFromArray($estiloTabelaSecundaria);

    $linha++;
    $sheet->mergeCells('A' . $linha . ':B' . $linha);
    $sheet->mergeCells('C' . $linha . ':D' . $linha);
    $sheet->setCellValue('A' . $linha, 'Total a receber:');
    $sheet->setCellValue('C' . $linha, 'R$ ' . number_format($valorTotal, 2, ',', '.'));

    $sheet->getStyle('A' . $linha)->applyFromArray(array_merge($estiloTabelaSecundaria, $negritoDireita));
    $sheet->getStyle('B' . $linha)->applyFromArray(array_merge($estiloTabelaSecundaria, $negritoDireita));
    $sheet->getStyle('C' . $linha)->applyFromArray($estiloTabelaSecundaria);
    $sheet->getStyle('D' . $linha)->applyFromArray($estiloTabelaSecundaria);

    $linha++;
    $sheet->mergeCells('A' . $linha . ':B' . $linha);
    $sheet->mergeCells('C' . $linha . ':D' . $linha);
    $sheet->setCellValue('A' . $linha, 'Valor a receber do pagar.me:');
    $sheet->setCellValue('C' . $linha, 'R$ ' . number_format($valorTotalPagarme, 2, ',', '.'));

    $sheet->getStyle('A' . $linha)->applyFromArray(array_merge($estiloTabelaSecundaria, $negritoDireita));
    $sheet->getStyle('B' . $linha)->applyFromArray(array_merge($estiloTabelaSecundaria, $negritoDireita));
    $sheet->getStyle('C' . $linha)->applyFromArray($estiloTabelaSecundaria);
    $sheet->getStyle('D' . $linha)->applyFromArray($estiloTabelaSecundaria);

    $linha++;
    $sheet->mergeCells('A' . $linha . ':B' . $linha);
    $sheet->mergeCells('C' . $linha . ':D' . $linha);
    $sheet->setCellValue('A' . $linha, 'CFCs inadimplentes:');
    $sheet->setCellValue('C' . $linha, count($escolasInadimplentes));

    $sheet->getStyle('A' . $linha)->applyFromArray(array_merge($estiloTabelaSecundaria, $negritoDireita));
    $sheet->getStyle('B' . $linha)->applyFromArray(array_merge($estiloTabelaSecundaria, $negritoDireita));
    $sheet->getStyle('C' . $linha)->applyFromArray($estiloTabelaSecundaria);
    $sheet->getStyle('D' . $linha)->applyFromArray($estiloTabelaSecundaria);
}

$sheet->removeRow($linhaBase-1,1);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($arquivo_gerado);


header('Content-type: ' . filetype($arquivo_gerado));
header('Content-Disposition: attachment; filename="' . basename($nome_arquivo) . '"');
header('Content-Length: ' . filesize($arquivo_gerado));
header('Expires: 0');
header('Pragma: no-cache');
readfile($arquivo_gerado);
