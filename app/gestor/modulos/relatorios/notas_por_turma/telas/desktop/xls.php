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
//$sheet->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));
$sheet->setCellValue('A5', 'Gerado dia '.date("d/m/Y H:i:s").' por '.$usuario["nome"].' ('.$usuario["email"].')');

$linhaBase = 4; 
$valorTotalNotas = 0;
$qtdeTotalNotas = 0;
$mediaGeral = 0;
if(count($dadosArray) > 0){
    $sheet->insertNewRowBefore($linhaBase,(count($dadosArray) + 1));
    foreach($dadosArray as $ind => $dados){
    	set_time_limit(0);	
    	$linha = $linhaBase + $ind;
    	$valorTotalNotas += $dados['nota'];
        $qtdeTotalNotas++;

        if ($dados['nota_conceito'] == 'S') {
            $nota = notaConceito($dados['nota']);
        } else {
            $nota = number_format($dados['nota'],2,',','.');
        }

      	$sheet->setCellValue('A'.$linha, $dados['idmatricula']);
        $sheet->setCellValue('B'.$linha, $dados['aluno']);
      	$sheet->setCellValue('C'.$linha, $GLOBALS['tipo_avaliacao'][$GLOBALS['config']['idioma_padrao']][$dados['tipo_avaliacao']]);
      	$sheet->setCellValue('D'.$linha, $dados['modelo']);
        $sheet->getStyle('E'.$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->setCellValue('E'.$linha, $nota);
        $sheet->setCellValue('F'.$linha, $dados['situacao']);
    }
}
$mediaGeral = ($valorTotalNotas / $qtdeTotalNotas);

$linha++;
$sheet->setCellValue('D'.$linha, 'Média:');
$sheet->getStyle('E'.$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->setCellValue('E'.$linha, $mediaGeral);   

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