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
    $sheet->setCellValue('A'.$linha, $dados["idconta"]);
    $sheet->setCellValue('B'.$linha, formataData($dados["data_matricula"],'br',0));
    $sheet->setCellValue('C'.$linha, formataData($dados["data_vencimento"],'br',0));

    $sheet->getStyle('D'.$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->setCellValue('D'.$linha, floatval(number_format($dados['valor'],2,".","")));

    $sheet->setCellValue('E'.$linha, $dados['situacao_wf_nome']);
    $sheet->setCellValue('F'.$linha, $dados['nome']);
    $sheet->setCellValue('G'.$linha, $dados['mantenedora']);
    $sheet->setCellValue('H'.$linha, $dados['escola']);
    $sheet->setCellValue('I'.$linha, $dados['sindicato']);

    if($dados["idmatricula"]) {
        $codigo = "Mat.: ".$dados["idmatricula"]." - Aluno: ".$dados["idpessoa_matricula"];
    } elseif($dados["idcliente"]) {
        $codigo = $dados["idcliente"];
    } elseif($dados["idfornecedor"]) {
        $codigo = $dados["idfornecedor"];
    } elseif($dados["idpessoa"]) {
        $codigo = $dados["idpessoa"];
    }
    $sheet->setCellValue('J'.$linha, $codigo);

    if($dados["idmatricula"]) {
        $nome = $dados["aluno"];
    } elseif($dados["idcliente"]) {
        $nome = $dados["cliente"];
    } elseif($dados["idfornecedor"]) {
        $nome = $dados["fornecedor"];
    } elseif($dados["idpessoa"]) {
        $nome = $dados["pessoa"];
    }
    $sheet->setCellValue('K'.$linha, $nome);

    $sheet->setCellValue('L'.$linha, $dados['produto']);
    $sheet->setCellValue('M'.$linha, $dados['categoria']);
    $sheet->setCellValue('N'.$linha, $dados['tipo']);
    $sheet->setCellValue('O'.$linha, formataData($dados["data_cad"],'br',1));
    $sheet->setCellValue('P'.$linha, $dados['conta_corrente']);
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