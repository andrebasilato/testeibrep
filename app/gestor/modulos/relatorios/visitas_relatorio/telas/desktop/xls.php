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

$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Gerado dia '.date("d/m/Y H:i:s").' por '.$usuario["nome"].' ('.$usuario["email"].')');



$linhaBase = 4;
if(count($dadosArray) > 0){
	$sheet->insertNewRowBefore($linhaBase,count($dadosArray));
	foreach($dadosArray as $ind => $dados){	
		$linha = $linhaBase + $ind;	

		$sheet->setCellValue('A'.$linha, $dados["idvendedor"]);

		$sheet->setCellValue('A'.$linha, $dados["idvisita"]);
		$sheet->setCellValue('B'.$linha, formataData($dados["data_cad"],"br",1));
		$sheet->setCellValue('C'.$linha, $dados['nome']);
		$sheet->setCellValue('D'.$linha, $dados['email']);
		$sheet->setCellValue('E'.$linha, $dados["telefone"]);
		$sheet->setCellValue('F'.$linha, $dados["celular"]);
		$sheet->setCellValue('G'.$linha, $dados["vendedor"]);
		$sheet->setCellValue('H'.$linha, $dados["curso"]);
		$sheet->setCellValue('I'.$linha, $dados["midia"]);
		$sheet->setCellValue('J'.$linha, $dados["local"]);
		$sheet->setCellValue('K'.$linha, $GLOBALS["situacao_visita_vendedores"][$GLOBALS["config"]["idioma_padrao"]][$dados["situacao"]]);
		$sheet->setCellValue('L'.$linha, $dados["matricula"]);
		$sheet->setCellValue('M'.$linha, $dados["geolocalizacao_endereco"]);
		$sheet->setCellValue('N'.$linha, $dados["cidade"]);
		$sheet->setCellValue('O'.$linha, $dados["estado"]);
		$sheet->setCellValue('P'.$linha, $dados["iteracoes"]);
		$sheet->setCellValue('Q'.$linha, $dados["observacoes"]);
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