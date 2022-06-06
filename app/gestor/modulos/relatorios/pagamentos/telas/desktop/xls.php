<?php

error_reporting(E_ERROR);
date_default_timezone_set('America/Recife');

$template = $_SERVER['DOCUMENT_ROOT'] . '/' . $url[0] . '/modulos/' . $url[1] . '/' . $url[2] . '/telas/' . $config['tela_padrao'] . '/' . 'xls_' . $config['idioma_padrao'] . '.xls';
$nome_arquivo = $url[1] . '_' . $url[2] . '_' . time() . '.xls';
$arquivo_gerado = $_SERVER['DOCUMENT_ROOT'] . '/storage/temp/' . $nome_arquivo;

require_once '../classes/phpexcel/classes/PHPExcel/IOFactory.php';
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load($template);
$sheet = $objPHPExcel->getActiveSheet();

// Data e Hora que foi gerado
$sheet->setCellValue('A6', 'Gerado dia ' . date('d/m/Y H:i:s') . ' por ' . $usuario['nome'] . ' (' . $usuario['email'] . ')');

$tdAzul = [
    'fill' => [
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => ['rgb' => '87CEEB']
    ]
];

$tdVerde = [
    'fill' => [
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => ['rgb' => '90EE90']
    ]
];

$linhaBase = 4;
if (count($dadosArray) > 0) {
    $sheet->insertNewRowBefore($linhaBase,count($dadosArray));

    foreach ($dadosArray as $ind => $dados) {
        $linha = $linhaBase + $ind;

        $valorTotalPf += $dados['valor_pf'];
        $valorTotalPj += $dados['valor_pj'];

        $formaPagamento = $GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$dados['forma_pagamento']];
        if ($dados['fatura'] == 'S') {
            $formaPagamento = "Pagar.me";
        }

        $valorPf = null;
        if ($dados['valor_pf']) {
            $valorPf = 'R$ ' . number_format($dados['valor_pf'], 2, ',', '.');
        }

        $valorPj = null;
        if ($dados['valor_pj']) {
            $valorPj = 'R$ ' . number_format($dados['valor_pj'], 2, ',', '.');
        }

        $sheet->setCellValue('A' . $linha, $dados['idmatricula']);
        $sheet->setCellValue('B' . $linha, formataData($dados['data_matricula'], 'br', 0));
        $sheet->setCellValue('C' . $linha, $dados['nome']);
        $sheet->setCellValue('D' . $linha, $dados['sindicato']);
        $sheet->setCellValue('E' . $linha, $dados['escola']);
        $sheet->setCellValue('F' . $linha, $dados['atendente']);
        $sheet->setCellValue('G' . $linha, $dados['cidade']);
        $sheet->setCellValue('H' . $linha, $dados['estado']);
        $sheet->setCellValue('I' . $linha, $formaPagamento)
            ->getStyle('I' . $linha)
            ->applyFromArray($tdAzul);
        $sheet->setCellValue('J' . $linha, $valorPf)
            ->getStyle('J' . $linha)
            ->applyFromArray($tdAzul);
        $sheet->setCellValue('K' . $linha, formataData($dados['data_vencimento_pf'], 'br', 0))
            ->getStyle('K' . $linha)
            ->applyFromArray($tdAzul);
        $sheet->setCellValue('L' . $linha, formataData($dados['data_pagamento_pf'], 'br', 0))
            ->getStyle('L' . $linha)
            ->applyFromArray($tdAzul);
        $sheet->setCellValue('M' . $linha, formataData($dados['bom_para_pf'], 'br', 0))
            ->getStyle('M' . $linha)
            ->applyFromArray($tdAzul);
        $sheet->setCellValue('N' . $linha, $dados['situacao_matricula'])
            ->getStyle('N' . $linha)
            ->applyFromArray($tdAzul);
        $sheet->setCellValue('O' . $linha, $dados['idfatura'])
            ->getStyle('O' . $linha)
            ->applyFromArray($tdVerde);
        $sheet->setCellValue('P' . $linha, $valorPj)
            ->getStyle('P' . $linha)
            ->applyFromArray($tdVerde);
        $sheet->setCellValue('Q' . $linha, formataData($dados['data_vencimento_pj'], 'br', 0))
            ->getStyle('Q' . $linha)
            ->applyFromArray($tdVerde);
        $sheet->setCellValue('R' . $linha, formataData($dados['data_pagamento_pj'], "br", 0))
            ->getStyle('R' . $linha)
            ->applyFromArray($tdVerde);
        $sheet->setCellValue('S' . $linha, formataData($dados['bom_para_pj'], "br", 0))
            ->getStyle('S' . $linha)
            ->applyFromArray($tdVerde);
    }

    $linha++;
    $sheet->setCellValue('I' . $linha, 'Total:')
        ->getStyle('I' . $linha)
        ->applyFromArray($tdAzul);

    $sheet->setCellValue('J' . $linha, 'R$ ' . number_format($valorTotalPf, 2, ',', '.'))
        ->getStyle('J' . $linha)
        ->applyFromArray($tdAzul);

    $sheet->getStyle('K' . $linha)
        ->applyFromArray($tdAzul);

    $sheet->getStyle('O' . $linha)
        ->applyFromArray($tdVerde);

    $sheet->setCellValue('P' . $linha, 'R$ ' . number_format($valorTotalPj, 2, ',', '.'))
        ->getStyle('P' . $linha)
        ->applyFromArray($tdVerde);

    $sheet->getStyle('Q' . $linha)
        ->applyFromArray($tdVerde);
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
