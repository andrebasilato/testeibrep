<?php
//error_reporting(E_ALL);
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
if(count($dadosArray) > 0){
  $sheet->insertNewRowBefore($linhaBase,count($dadosArray));
  foreach($dadosArray as $ind => $dados){
	set_time_limit(0);	
	$linha = $linhaBase + $ind;

/*
Data	Matrícula	Contrato	Cliente	Oferta	Curso	Turma	Valor de contrato	Forma de pagamento	Parcelas	Vendedor
*/
	
	$sheet->setCellValue('A'.$linha, formataData($dados["data_cad"],"br",0));
	$sheet->setCellValue('B'.$linha, $dados["idmatricula"]);
	$sheet->setCellValue('C'.$linha, $dados["numero_contrato"]);
	$sheet->setCellValue('D'.$linha, $dados["cliente"]);
	$sheet->setCellValue('E'.$linha, $dados["situacao_wf_nome"]);
	$sheet->setCellValue('F'.$linha, $dados["oferta"]);
	$sheet->setCellValue('G'.$linha, $dados["curso"]);
	$sheet->setCellValue('H'.$linha, $dados["turma"]);
	$sheet->setCellValue('I'.$linha, floatval(number_format($dados["valor_contrato"], 2, ".", "")));
	$sheet->setCellValue('J'.$linha, $forma_pagamento_conta["pt_br"][$dados["forma_pagamento"]]);
	$sheet->setCellValue('K'.$linha, $dados["quantidade_parcelas"]);
	$sheet->setCellValue('L'.$linha, $dados["vendedor"]);
  }
  $linha++;
  $sheet->setCellValue('I'.$linha, '=SUM(I'.$linhaBase.':I'.($linha-1).')');//TotAL
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