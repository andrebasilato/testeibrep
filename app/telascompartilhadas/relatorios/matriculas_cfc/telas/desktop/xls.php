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
$sheet->setCellValue('A6', 'Gerado dia ' . date('d/m/Y H:i:s') . ' por ' . $usuario['nome'] . ' (' . $usuario['email'] . ')');

$linhaBase = 4;
if (count($dadosArray) > 0) {
    $sheet->insertNewRowBefore($linhaBase, count($dadosArray));

    $valorTotal = 0;
    $valorTotalTaxa = 0;
    $valorTotalLiquido = 0;

    $linha = $linhaBase - 1;
    foreach ($dadosArray as $ind => $dados) {
        $linha++;

        $valorTotal += $dados['valor_contrato'];
        $valorTotalTaxa += $dados['taxa'];
        $valorTotalLiquido += $dados['valor_liquido'];

        $dataCad = new DateTime($dados['data_cad']);
        $dataDe = $dataCad->format("01/m/Y");
        $dataAte = $dataCad->format("15/m/Y");

        if ($dataCad->format("d") >= 16) {
            $dataDe = $dataCad->format("16/m/Y");
            $dataAte = $dataCad->format("t/m/Y");
        }

        $sheet->setCellValue('A' . $linha, $dados['sindicato']);
        $sheet->setCellValue('B' . $linha, $dados['escola']);
        $sheet->setCellValue('C' . $linha, formataData($dados['data_matricula'], 'br', 0));
        $sheet->setCellValue('D' . $linha, formataData($dados['data_em_curso'], 'br', 0));
        $sheet->setCellValue('E' . $linha, $dados['situacao_matricula']);
        $sheet->setCellValue('F' . $linha, $dados['idmatricula']);
        $sheet->setCellValue('G' . $linha, $dados['nome']);
        $sheet->setCellValue('H' . $linha, ' ' . $dados['documento']);
        $sheet->setCellValue('I' . $linha, $dados['telefone']);
        $sheet->setCellValue('J' . $linha, $dados['celular']);
        $sheet->setCellValue('K' . $linha, $dados['email']);
        $sheet->setCellValue('L' . $linha, $dataDe);
        $sheet->setCellValue('M' . $linha, $dataAte);
        $sheet->setCellValue('N' . $linha, 'R$ ' . number_format($dados['valor_contrato'], 2, ',', '.'));
        $sheet->setCellValue('O' . $linha, 'R$ ' . number_format($dados['taxa'], 2, ',', '.'));
        $sheet->setCellValue('P' . $linha, 'R$ ' . number_format($dados['valor_liquido'], 2, ',', '.'));
        $sheet->setCellValue('Q' . $linha, $dados['situacao']);
        $sheet->setCellValue('R' . $linha, formataData($dados['data_vencimento'], "br", 0));
        $sheet->setCellValue('S' . $linha, formataData($dados['data_pagamento'], "br", 0));
        $sheet->setCellValue('T' . $linha, formataData($dados['data_prevista_disponivel_pagarme'], "br", 0));
    }

    $letraTotal1 = 'N';
    $letraTotal2 = 'O';
    $letraTotal3 = 'P';
    if ($url[0] == 'cfc') {
        $letraTotal1 = 'L';
        $letraTotal2 = 'M';
        $letraTotal3 = 'N';

        $objPHPExcel->getActiveSheet()->removeColumn('B');
        $objPHPExcel->getActiveSheet()->removeColumn('A');
    }

    $linha++;
    $sheet->setCellValue($letraTotal1 . $linha, 'R$ ' . number_format($valorTotal, 2, ',', '.'));
    $sheet->setCellValue($letraTotal2 . $linha, 'R$ ' . number_format($valorTotalTaxa, 2, ',', '.'));
    $sheet->setCellValue($letraTotal3 . $linha, 'R$ ' . number_format($valorTotalLiquido, 2, ',', '.'));
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
