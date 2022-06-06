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
$sheet->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));
$sheet->setCellValue('A5', 'Gerado dia '.date("d/m/Y H:i:s").' por '.$usuario["nome"].' ('.$usuario["email"].')');

$linhaBase = 4;
if(count($dadosArray['dados']) > 0){	
  $sheet->insertNewRowBefore($linhaBase,count($dadosArray['dados']));
  $linha = $linhaBase;  
  foreach($dadosArray['dados'] as $ind => $dados){  	
  	$sheet->setCellValue('A'.$linha, $dados['tipo']);
  	$sheet->setCellValue('B'.$linha, $dados['sindicato']);
  	$sheet->setCellValue('C'.$linha, $dados['conta']);
  	$sheet->setCellValue('D'.$linha, $dados["data_cadastro"]);
  	$sheet->setCellValue('E'.$linha, $dados["data_pagamento"]);
  	$sheet->setCellValue('F'.$linha, $dados["data_vencimento"]);
	$sheet->setCellValue('G'.$linha, $GLOBALS ['forma_pagamento_conta'] [$GLOBALS ['config'] ['idioma_padrao']] [$dados['forma_pagamento']]);
	
	$sheet->getStyle('H'.$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$sheet->setCellValue('H'.$linha, floatval(number_format($dados["valor"],2,".","")));
	
	$linha++;	
  }
}

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