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
$sheet->setCellValue('A11', 'Gerado dia ' . date('d/m/Y H:i:s') . ' por ' . $usuario['nome'] . ' (' . $usuario['email'] . ')');

$linhaBase = 4;
if (count($dadosArray) > 0) {
    $sheet->insertNewRowBefore($linhaBase,count($dadosArray));

    $inadimplentes = 0;
    foreach ($dadosArray as $ind => $dados) {
        $linha = $linhaBase + $ind;

        $valorTotal += $dados['valor'];
        $matriculas[$dados['idmatricula']] = $dados['idmatricula'];

        if (
            $dados['conta_pago'] == 'N'
            && $dados['conta_renegociada'] == 'N'
            && $dados['conta_transferida'] == 'N'
            && $dados['conta_cancelada'] == 'N'
            && (new \DateTime($dados['data_vencimento']))->format('Y-m-d') < (new \DateTime)->format('Y-m-d')
        ) {
            $inadimplentes += $dados['valor'];
        }
        
        $sheet->setCellValue('A' . $linha, $dados['sindicato']);
        $sheet->setCellValue('B' . $linha, $dados['escola']);
        $sheet->setCellValue('C' . $linha, formataData($dados['data_matricula'], 'br', 0));
        $sheet->setCellValue('D' . $linha, formataData($dados['data_em_curso'], 'br', 0));
        $sheet->setCellValue('E' . $linha, $dados['idmatricula']);
        $sheet->setCellValue('F' . $linha, $dados['situacao_matricula']);
        $sheet->setCellValue('G' . $linha, $dados['nome']);
        $sheet->setCellValue('H' . $linha, ' ' . $dados['documento']);
        $sheet->setCellValue('I' . $linha, $dados['telefone']);
        $sheet->setCellValue('J' . $linha, $dados['celular']);
        $sheet->setCellValue('K' . $linha, $dados['email']);
        $sheet->setCellValue('L' . $linha, $dados['cidade']);
        $sheet->setCellValue('M' . $linha, $dados['estado']);
        $sheet->setCellValue('N' . $linha, 'R$ ' . number_format($dados['valor'], 2, ',', '.'));
        $sheet->setCellValue('O' . $linha, $GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$dados['forma_pagamento']]);
        $sheet->setCellValue('P' . $linha, $dados['parcelas_pagseguro']);
        $sheet->setCellValue('Q' . $linha, $dados['situacao']);
        $sheet->setCellValue('R' . $linha, formataData($dados['data_vencimento'], "br", 0));
        $sheet->setCellValue('S' . $linha, formataData($dados['data_pagamento'], "br", 0));
        $sheet->setCellValue('T' . $linha, formataData($dados['data_prevista_disponivel_pagseguro'], "br", 0));
        $sheet->setCellValue('U' . $linha, $dados['code_pagseguro']);
    }

    $linha++;
    $sheet->setCellValue('A' . $linha, 'Total:');
    $sheet->setCellValue('N' . $linha, 'R$ ' . number_format($valorTotal, 2, ',', '.'));

    switch ($_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento']) {
        case 'PER':
            $periodoDe = $_GET['data_vencimento_de'];
            $periodoAte = $_GET['data_vencimento_ate'];
            break;
        case 'HOJ':
            $periodoDe = (new \DateTime)->format('d/m/Y');
            $periodoAte = (new \DateTime)->format('d/m/Y');
            break;
        case 'ONT':
            $periodoDe = (new \DateTime)->modify('-1 days')->format('d/m/Y');
            $periodoAte = (new \DateTime)->modify('-1 days')->format('d/m/Y');
            break;
        case 'SET':
            $periodoDe = (new \DateTime)->modify('-6 days')->format('d/m/Y');
            $periodoAte = (new \DateTime)->format('d/m/Y');
            break;
        case 'QUI':
            $periodoDe = (new \DateTime)->modify('-15 days')->format('d/m/Y');
            $periodoAte = (new \DateTime)->format('d/m/Y');
            break;
        case 'MAT':
            $periodoDe = (new \DateTime('first day of this month'))->format('d/m/Y');
            $periodoAte = (new \DateTime('last day of this month'))->format('d/m/Y');
            break;
        case 'MPR':
            $periodoDe = (new \DateTime('first day of next month'))->format('d/m/Y');
            $periodoAte = (new \DateTime('last day of next month'))->format('d/m/Y');
            break;
        case 'MAN':
            $periodoDe = (new \DateTime('first day of previous month'))->format('d/m/Y');
            $periodoAte = (new \DateTime('last day of previous month'))->format('d/m/Y');
            break;
    }

    $linha = $linha + 2;
    $sheet->setCellValue('A' . $linha, 'Período:');
    $sheet->setCellValue('B' . $linha, $periodoDe);
    $sheet->setCellValue('C' . $linha, $periodoAte);

    $linha++;
    $sheet->setCellValue('A' . $linha, 'Total de Matrículas:');
    $sheet->setCellValue('B' . $linha, count($matriculas));

    $linha++;
    $sheet->setCellValue('A' . $linha, 'Total a receber:');
    $sheet->setCellValue('B' . $linha, 'R$ ' . number_format($valorTotal, 2, ',', '.'));

    $linha++;
    $sheet->setCellValue('A' . $linha, 'Total inadimplente:');
    $sheet->setCellValue('B' . $linha, 'R$ ' . number_format($inadimplentes, 2, ',', '.'));
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
