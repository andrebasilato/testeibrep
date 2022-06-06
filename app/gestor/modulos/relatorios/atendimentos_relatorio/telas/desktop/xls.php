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
	
	if(!$dados['datacad_situacao']) {
	  $dados['datacad_situacao'] = $dados['data_cad'];
	}
		
	$sheet->setCellValue('A'.$linha, " ".$dados["protocolo"]);
	$sheet->setCellValue('B'.$linha, $dados['cliente']);
	$sheet->setCellValue('C'.$linha, formataData($dados['data_cad'],'pt',0));
	$sheet->setCellValue('D'.$linha, formataData($dados['datacad_situacao'],'pt',0));
	$sheet->setCellValue('E'.$linha, $dados['situacao_atendimento']);
	$sheet->setCellValue('F'.$linha, $dados['assunto']);
	$sheet->setCellValue('G'.$linha, $dados['subassunto']);
	$sheet->setCellValue('H'.$linha, $dados["usuario"]);
	$sheet->setCellValue('I'.$linha, $prioridades[$config["idioma_padrao"]][$dados["prioridade"]]);
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