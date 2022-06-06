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
$sheet->setCellValue('A5', 'Gerado dia ' . date('d/m/Y H:i:s') . ' por ' . $usuario['nome'] . ' (' . $usuario['email'] . ')');

$linhaBase = 4;
if (count($dadosArray) > 0){
	$sheet->insertNewRowBefore($linhaBase,count($dadosArray));

	foreach ($dadosArray as $ind => $dados) {
		$linha = $linhaBase + $ind;

		$sheet->setCellValue('A' . $linha, $dados['idconta']);
		$sheet->setCellValue('B' . $linha, $dados['escola']);
		$sheet->setCellValue('C' . $linha, 'R$ ' . number_format($dados['valor'], 2, ',', '.'));
		$sheet->setCellValue('D' . $linha, formataData($dados['data_vencimento'], 'br', 0));
		$sheet->setCellValue('E' . $linha, $dados['qnt_matriculas']);
		$sheet->setCellValue('F' . $linha, $dados['situacao']);
		$sheet->setCellValue('G' . $linha, formataData($dados['data_modificacao_fatura'], 'br', 1));
		$sheet->setCellValue('H' . $linha, $GLOBALS['statusTransacaoPagarme'][$GLOBALS['config']['idioma_padrao']][$dados['statusPagarme']]);
	}
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
