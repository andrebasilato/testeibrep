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
if(count($dadosArray) > 0){
  $sheet->insertNewRowBefore($linhaBase,count($dadosArray));    
  foreach($dadosArray as $ind => $dados){	
	$linha = $linhaBase + $ind;	
	$sheet->setCellValue('A'.$linha, $dados["contrato"]);
	$sheet->setCellValue('B'.$linha, $dados["idmatricula"]);
	$sheet->setCellValue('C'.$linha, $dados['curso']);
	$sheet->setCellValue('D'.$linha, $dados['nome_aluno']);
	$sheet->setCellValue('E'.$linha, $dados['email_aluno']);
	$sheet->setCellValue('F'.$linha, $dados['vendedor']);
	$sheet->setCellValue('G'.$linha, $dados['dias_matricula']);
	$sheet->setCellValue('H'.$linha, $dados['situacao']);
    $sheet->setCellValue('I'.$linha, (isset($dados['assinado']) ? 'Sim' : 'Não'));
	$sheet->setCellValue('J'.$linha, $dados['assinado'] );	
	$sheet->setCellValue('K'.$linha, (isset($dados['devedor']) ? 'Sim' : 'Não'));
	$sheet->setCellValue('L'.$linha, (isset($dados['validado']) ? 'Sim' : 'Não'));
	$sheet->setCellValue('M'.$linha, $dados['validado'] );	
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