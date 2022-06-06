<?php
date_default_timezone_set('America/Recife');

$template = $_SERVER['DOCUMENT_ROOT']."/".$url[0]."/modulos/".$url[1]."/".$url[2]."/telas/".$config["tela_padrao"]."/"."xls_".$config["idioma_padrao"].".xls";
$nome_arquivo = $url[1]."_".$url[2]."_".time().".xls";
$arquivo_gerado = $_SERVER['DOCUMENT_ROOT']."/storage/temp/".$nome_arquivo;

/** PHPExcel_IOFactory */
require_once("../classes/phpexcel/classes/PHPExcel/IOFactory.php");
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load($template);
$sheet = $objPHPExcel->getActiveSheet();

ini_set('memory_limit', '500M');

// Data e Hora que foi gerado
$sheet->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));
$sheet->setCellValue('A5', 'Gerado dia '.date("d/m/Y H:i:s").' por '.$usuario["nome"].' ('.$usuario["email"].')');

$linhaBase = 4;
if(count($dadosArray) > 0){
    $sheet->insertNewRowBefore($linhaBase,count($dadosArray));
    foreach($dadosArray as $ind => $dados){
    	set_time_limit(3000);
    	$linha = $linhaBase + $ind;

    	$sheet->setCellValue('A'.$linha, $dados["idmatricula"]);
        $sheet->setCellValue('B'.$linha, $dados["cliente"]);
        $sheet->setCellValue('C'.$linha, ' '.$dados["documento"]);
        $sheet->setCellValue('D'.$linha, $dados["solicitante"]);
        $sheet->setCellValue('E'.$linha, $dados["vendedor"]);
        $sheet->setCellValue('F'.$linha, number_format($dados["porcentagem"], 2, ",", "."));

        if ($dados["maior_nota"] && $dados["porcentagem"] == 100) {
            $sheet->setCellValue('G'.$linha, number_format($dados["maior_nota"], 2, ",", "."));
        } else {
            $sheet->setCellValue('G'.$linha, "--");
        }

        if ($dados["maior_nota"] && $dados["porcentagem"] == 100) {
            $sheet->setCellValue('H'.$linha, $dados["situacao_nota"]);
        } else {
            $sheet->setCellValue('H'.$linha, "--");
        }

        if ($dados["data_conclusao"] && $dados["porcentagem"] == 100) {
            $sheet->setCellValue('I'.$linha, formataData($dados["data_conclusao"], "br", 0));
        } else {
            $sheet->setCellValue('I'.$linha, "--");
        }
    }

    $linha++;
}

$sheet->removeRow($linhaBase-1,1);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($arquivo_gerado);

header("Content-type: ".filetype($arquivo_gerado));
header('Content-Disposition: attachment; filename="'.basename($nome_arquivo).'"');
header('Content-Length: '.filesize($arquivo_gerado));
header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60)));
header('Pragma: no-cache');
readfile($arquivo_gerado);