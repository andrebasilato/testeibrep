<?php
set_time_limit(0);
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

$styleArray = array(
          'borders' => array(
              'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
              )
          )
      );

//$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);


$letras = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
				"AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",
				"BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ",
				"CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ",
				"DA","DB","DC","DD","DE","DF","DG","DH","DI","DJ","DK","DL","DM","DN","DO","DP","DQ","DR","DS","DT","DU","DV","DW","DX","DY","DZ",
				"EA","EB","EC","ED","EE","EF","EG","EH","EI","EJ","EK","EL","EM","EN","EO","EP","EQ","ER","ES","ET","EU","EV","EW","EX","EY","EZ",
				"FA","FB","FC","FD","FE","FF","FG","FH","FI","FJ","FK","FL","FM","FN","FO","FP","FQ","FR","FS","FT","FU","FV","FW","FX","FY","FZ",
				"GA","GB","GC","GD","GE","GF","GG","GH","GI","GJ","GK","GL","GM","GN","GO","GP","GQ","GR","GS","GT","GU","GV","GW","GX","GY","GZ",
				"HA","HB","HC","HD","HE","HF","HG","HH","HI","HJ","HK","HL","HM","HN","HO","HP","HQ","HR","HS","HT","HU","HV","HW","HX","HY","HZ");


$linhaBase = 5; 

if(count($dadosArray) > 0) {
    $indicesArray = array_keys($dadosArray);
	$situacoes = $dadosArray[$indicesArray[0]]['situacoes'];
	$totalColunas = count($situacoes) + 1;
	
	$sheet->mergeCells("A1:".$letras[$totalColunas]."1");
	$sheet->mergeCells("A6:".$letras[$totalColunas]."6");

	$sheet->insertNewRowBefore($linhaBase,count($dadosArray));
	
    $sheet->setCellValue('A2', 'Sindicatos');
    $sheet->getStyle('A2')->getFont()->setBold(true);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->mergeCells('A2:A3');

	$sheet->setCellValue('B2', 'Situações');
	$sheet->mergeCells('B2:'.$letras[($totalColunas - 1)].'2');
    $sheet->getStyle('B2')->getFont()->setBold(true);
    $sheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$sheet->setCellValue($letras[$totalColunas].'2', 'Total');
	$sheet->mergeCells($letras[$totalColunas].'2:'.$letras[$totalColunas].'3');
    $sheet->getStyle($letras[$totalColunas].'2')->getFont()->setBold(true);
    $sheet->getStyle($letras[$totalColunas].'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle($letras[$totalColunas].'2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	foreach($situacoes as $ind => $situacao) {
		$colunaBaseSituacao = 1 + $ind;
		$sheet->setCellValue($letras[$colunaBaseSituacao].'3', $situacao['situacao']);
        $sheet->getStyle($letras[$colunaBaseSituacao].'3')->getFont()->setBold(true);
	}
    $indice = 0;
    foreach($dadosArray as $idsindicato => $dados) {

        $linha = $linhaBase + $indice;
        $indice++;

        $sheet->setCellValue('A'.$linha, $dados["nome_abreviado"]);
        $sheet->getStyle('A'.$linha)->getFont()->setBold(true);
        $colunaBaseSituacao = 0;
        $totalSindicato = 0;
        foreach ($dados['situacoes'] as $ind => $situacao) {
            $colunaBaseSituacao = 1 + $ind;
            $totalSindicato += $situacao['quantidade_matriculas'];
            $totalSituacoes[$ind] += $situacao['quantidade_matriculas'];
            $sheet->setCellValue($letras[$colunaBaseSituacao].$linha, (String)$situacao['quantidade_matriculas']);
            $sheet->getStyle($letras[$colunaBaseSituacao].$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        }
        $sheet->setCellValue($letras[$totalColunas].$linha, (String)$totalSindicato);
        $sheet->getStyle($letras[$totalColunas].$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    }
    $linha++;
    $sheet->setCellValue('A'.$linha, 'Total');
    $sheet->getStyle('A'.$linha)->getFont()->setBold(true);
    $sheet->getStyle('A'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A'.$linha)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $colunaBaseSituacao = 0;
    foreach($totalSituacoes as $ind => $totalSituacao) {
        $colunaBaseSituacao = 1 + $ind;
        $sheet->setCellValue($letras[$colunaBaseSituacao].$linha, '=SUM('.$letras[$colunaBaseSituacao].($linhaBase).':'.$letras[$colunaBaseSituacao].($linha-1).')');
        $sheet->getStyle($letras[$colunaBaseSituacao].$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    }
    $sheet->setCellValue($letras[$totalColunas].$linha, '=SUM('.$letras[$totalColunas].($linhaBase).':'.$letras[$totalColunas].($linha-1).')');
    $sheet->getStyle($letras[$totalColunas].$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    // Data e Hora que foi gerado
    $linha++;
    $sheet->setCellValue('A'.$linha, 'Gerado dia '.date("d/m/Y H:i:s").' por '.$usuario["nome"].' ('.$usuario["email"].')');
    $sheet->getStyle('A'.$linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A'.$linha)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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