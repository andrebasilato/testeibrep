<?php
error_reporting(E_ALL);
date_default_timezone_set('America/Recife');

$template = $_SERVER['DOCUMENT_ROOT']."/".$url[0]."/modulos/".$url[1]."/".$url[2]."/telas/".$config["tela_padrao"]."/"."xls_".$config["idioma_padrao"].".xls";
$nome_arquivo = $url[1]."_".$url[2]."_".time().".xls";
$arquivo_gerado = $_SERVER['DOCUMENT_ROOT']."/storage/temp/".$nome_arquivo;

/** PHPExcel_IOFactory */
require_once("../classes/phpexcel/classes/PHPExcel/IOFactory.php");
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load($template);
$sheet = $objPHPExcel->getActiveSheet();

// Data e Hora que foi gerado
$sheet->setCellValue('A5', 'Gerado dia '.date("d/m/Y H:i:s").' por '.$usuario["nome"].' ('.$usuario["email"].')');

$linhaBase = 4;

if(count($dadosArray) > 0){

  $sheet->insertNewRowBefore($linhaBase,(count($dadosArray) + 1));
  foreach($dadosArray as $ind => $dados){	
	$linha = $linhaBase + $ind;	
	
	//$valor = ($dados["valor"] + $dados["valor_juros"] + $dados["valor_multa"] + $dados["valor_outro"]) - $dados["valor_desconto"];
	
	$dados["forma_pagamento"] = $forma_pagamento_conta[$config["idioma_padrao"]][$dados["forma_pagamento"]];	
	
	$sheet->setCellValue('A'.$linha, $dados["banco"]);
	$sheet->setCellValue('B'.$linha, $dados["agencia"]);
	$sheet->setCellValue('C'.$linha, $dados["conta"]);
	$sheet->setCellValue('D'.$linha, formataData($dados["data_vencimento"],"br",0));
	$sheet->setCellValue('E'.$linha, formataData($dados["data_pagamento"],"br",0));
	$sheet->setCellValue('F'.$linha, $dados["forma_pagamento"]);
	$sheet->getStyle('G'.$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$sheet->setCellValue('G'.$linha, floatval(number_format($dados["valor"],2,".","")));
	$sheet->getStyle('H'.$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$sheet->setCellValue('H'.$linha, floatval(number_format($dados["valor_desconto"],2,".","")));
	$sheet->getStyle('I'.$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$sheet->setCellValue('I'.$linha, floatval(number_format($dados["valor_pago"],2,".","")));
	$sheet->setCellValue('H'.$linha, $dados["documento"]);
	$sheet->setCellValue('I'.$linha, $dados["nome"]);
  }
} else {
    $linha = $linhaBase;
}

$linha++;

$sheet->setCellValue('F'.$linha, 'Total:');
$sheet->getStyle('F'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$sheet->mergeCells('A'.$linha.':F'.$linha);
$sheet->getStyle('G'.$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->setCellValue('G'.$linha, '=SUM(G'.$linhaBase.':G'.($linha-1).')');
$sheet->getStyle('H'.$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->setCellValue('H'.$linha, '=SUM(H'.$linhaBase.':H'.($linha-1).')');
$sheet->getStyle('I'.$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->setCellValue('I'.$linha, '=SUM(I'.$linhaBase.':I'.($linha-1).')');

$sheet->removeRow($linhaBase-1,1);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($arquivo_gerado);

header("Content-type: ".filetype($arquivo_gerado));
header('Content-Disposition: attachment; filename="'.basename($nome_arquivo).'"');
header('Content-Length: '.filesize($arquivo_gerado));
header('Expires: 0');
header('Pragma: no-cache');
readfile($arquivo_gerado);
?>