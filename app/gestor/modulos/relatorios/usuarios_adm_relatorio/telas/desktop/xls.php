<?php
error_reporting(E_ERROR);
date_default_timezone_set('America/Recife');

$template = $_SERVER['DOCUMENT_ROOT']."/".$url[0]."/modulos/".$url[1]."/".$url[2]."/telas/".$config["tela_padrao"]."/"."xls_".$config["idioma_padrao"].".xls";
$nome_arquivo = $url[1]."_".$url[2]."_".time().".xls";
$arquivo_gerado = $_SERVER['DOCUMENT_ROOT']."/storage/temp/".$nome_arquivo;

/** PHPExcel_IOFactory */
require_once("../classes/phpexcel/classes/PHPExcel/IOFactory.php");
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load($template);

// Data e Hora que foi gerado
$objPHPExcel->getActiveSheet()->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));

$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Gerado dia '.date("d/m/Y H:i:s").' por '.$usuario["nome"].' ('.$usuario["email"].')');

$linhaBase = 4;
if (is_array($dadosArray)) {
  foreach ($dadosArray as $ind => $dados) {
	$linha = $linhaBase + $ind;
	$objPHPExcel->getActiveSheet()->insertNewRowBefore($linha,1);
	
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$linha, $dados["idusuario"]);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$linha, $dados['usuario']);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$linha, $dados['email']);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$linha, $dados['perfil']);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$linha, $dados['sindicatos']);
	
  }
}

$objPHPExcel->getActiveSheet()->removeRow($linhaBase-1,1);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($arquivo_gerado);

header("Content-type: ".filetype($arquivo_gerado));
header('Content-Disposition: attachment; filename="'.basename($nome_arquivo).'"');
header('Content-Length: '.filesize($arquivo_gerado));
header('Expires: 0');
header('Pragma: no-cache');
readfile($arquivo_gerado);