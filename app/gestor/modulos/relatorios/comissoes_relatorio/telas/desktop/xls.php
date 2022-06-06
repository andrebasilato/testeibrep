<?php
error_reporting(E_ALL);
set_time_limit(0);
date_default_timezone_set('America/Recife');

$template = $_SERVER['DOCUMENT_ROOT']."/".$url[0]."/modulos/".$url[1]."/".$url[2]."/telas/".$config["tela_padrao"]."/"."xls_".$config["idioma_padrao"].".xls";
$nome_arquivo = $url[1]."_".$url[2]."_".time().".xls";
$arquivo_gerado = $_SERVER['DOCUMENT_ROOT']."/storage/temp/".$nome_arquivo;

/** PHPExcel_IOFactory */
require_once("../classes/phpexcel/classes/PHPExcel/IOFactory.php");
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load($template);

//Perguntas objetivas
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();
$linhaBase = 5;
$letraBase = 3;
$totalLinhas = count($linhas);

$de = explode("/",$_GET["competencia_de"]);
$ate = explode("/",$_GET["competencia_ate"]);	
$dataInicio = date("m/Y",mktime(0,0,0,$de[0],1,$de[1]));
$dataFim = date("m/Y",mktime(0,0,0,$ate[0]+1,1,$ate[1]));
$deMes = $de[0];
$totalMeses = 0;
$meses = array();
for($data = $dataInicio;$data != $dataFim;$data = date("m/Y",mktime(0,0,0,++$deMes,1,$de[1]))) { 
  $totalMeses++;
  $meses[] = $data;
}
$letras = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
				"AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",
				"BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ",
				"CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ",
				"DA","DB","DC","DD","DE","DF","DG","DH","DI","DJ","DK","DL","DM","DN","DO","DP","DQ","DR","DS","DT","DU","DV","DW","DX","DY","DZ",
				"EA","EB","EC","ED","EE","EF","EG","EH","EI","EJ","EK","EL","EM","EN","EO","EP","EQ","ER","ES","ET","EU","EV","EW","EX","EY","EZ",
				"FA","FB","FC","FD","FE","FF","FG","FH","FI","FJ","FK","FL","FM","FN","FO","FP","FQ","FR","FS","FT","FU","FV","FW","FX","FY","FZ",
				"GA","GB","GC","GD","GE","GF","GG","GH","GI","GJ","GK","GL","GM","GN","GO","GP","GQ","GR","GS","GT","GU","GV","GW","GX","GY","GZ",
				"HA","HB","HC","HD","HE","HF","HG","HH","HI","HJ","HK","HL","HM","HN","HO","HP","HQ","HR","HS","HT","HU","HV","HW","HX","HY","HZ");
if($totalLinhas && $totalMeses) {
  $sheet->insertNewColumnBefore($letras[$letraBase],$totalMeses);
  $sheet->setCellValue("A1", "Relatório de comissões");
  $sheet->getStyle("A1")->getFont()->setSize(22);
  $sheet->getStyle("A1")->getFont()->setBold(true);
  $sheet->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $sheet->getStyle("A1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
  $sheet->mergeCells("A1:".$letras[($totalMeses*3)+2]."1");
  $sheet->insertNewRowBefore($linhaBase,$totalLinhas);
  $letra = $letraBase;	
  foreach($meses as $ind => $mes){
	$sheet->setCellValue($letras[$letra].($linhaBase-2), 'Qtd');
	$sheet->getStyle($letras[$letra].($linhaBase-2))->getFont()->setBold(true);
	$sheet->getStyle($letras[$letra].($linhaBase-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle($letras[$letra].($linhaBase-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->setCellValue($letras[$letra+1].($linhaBase-2), 'Vendido');
	$sheet->getStyle($letras[$letra+1].($linhaBase-2))->getFont()->setBold(true);
	$sheet->getStyle($letras[$letra+1].($linhaBase-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle($letras[$letra+1].($linhaBase-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->setCellValue($letras[$letra+2].($linhaBase-2), 'Valor');
	$sheet->getStyle($letras[$letra+2].($linhaBase-2))->getFont()->setBold(true);
	$sheet->getStyle($letras[$letra+2].($linhaBase-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle($letras[$letra+2].($linhaBase-2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
	$sheet->setCellValue($letras[$letra].($linhaBase-3), $mes);
	$sheet->getStyle($letras[$letra].($linhaBase-3))->getFont()->setBold(true);
	$sheet->getStyle($letras[$letra].($linhaBase-3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle($letras[$letra].($linhaBase-3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->mergeCells($letras[$letra].($linhaBase-3).':'.$letras[$letra+2].($linhaBase-3));
	
	$letra += 3;
  }
  
  $countLinha = 0;
  foreach($linhas["sindicatos"] as $sindicato) {
	$countVendedores = 0;
	foreach($sindicato["vendedores"] as $vendedor) { 
	  $countVendedores++;
	  $countRegras = 0;
	  foreach($vendedor["regras"] as $regra) { 
		$countRegras++;
		$linha = $linhaBase + $countLinha;	
		$countLinha++;
		$sheet->setCellValue('A'.$linha, $sindicato["nome_abreviado"]);
		$sheet->setCellValue('B'.$linha, $vendedor["nome"]);
		$sheet->setCellValue('C'.$linha, $regra["nome"]);
		$letra = $letraBase;
		foreach($meses as $ind => $mes) {
		  //$comissaoVendedor = "--";
		  //if(is_float($regra["comissao"][$mes])) {
			$valorTotalVendidoVendedor = number_format($regra["comissao"][$mes]["total_vendido"],2,",",".");
			$comissaoVendedor = floatval(number_format($regra["comissao"][$mes]["comissao"],2,".",""));
		  //} 
		  
		  $sheet->setCellValue($letras[$letra].$linha, $regra["comissao"][$mes]["quantidade"]);
		  $letra++;
		  
		  $sheet->getStyle($letras[$letra].$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		  $sheet->setCellValue($letras[$letra].$linha, $valorTotalVendidoVendedor);
		  $letra++;
		  
		  $sheet->getStyle($letras[$letra].$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		  $sheet->setCellValue($letras[$letra].$linha, $comissaoVendedor);
		  $letra++;
		}
	  }
	}
  }
}
$sheet->removeRow($linhaBase - 1,1);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($arquivo_gerado);

header("Content-type: ".filetype($arquivo_gerado));
header('Content-Disposition: attachment; filename="'.basename($nome_arquivo).'"');
header('Content-Length: '.filesize($arquivo_gerado));
header('Expires: 0');
header('Pragma: no-cache');
readfile($arquivo_gerado);
?>