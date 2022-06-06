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
	set_time_limit(-1);
	$linha = $linhaBase + $ind;

	$sheet->setCellValue('A'.$linha, $dados["idmatricula"]);
	$sheet->setCellValue('B'.$linha, formataData($dados["data_cad"],"br",0));
	$sheet->setCellValue('C'.$linha, $dados["situacao_wf_nome"]);
	$sheet->setCellValue('D'.$linha, $dados["matricula_faturada"]);
	$sheet->setCellValue('E'.$linha, $dados["oferta"]);
	$sheet->setCellValue('F'.$linha, $dados["curso"]);
	$sheet->setCellValue('G'.$linha, $dados["turma"]);
    $sheet->setCellValue('H'.$linha, $dados["cfc"]);
	$sheet->setCellValue('I'.$linha, $dados["idpessoa"]);
	$sheet->setCellValue('J'.$linha, $dados["cliente"]);
	$sheet->setCellValue('K'.$linha, ' '.$dados["documento"]);
    $sheet->setCellValue('L'.$linha, ' '.$dados["telefone"]);
    $sheet->setCellValue('M'.$linha, ' '.$dados["celular"]);
    $sheet->setCellValue('N'.$linha, ' '.$dados["estado"]);
    $sheet->setCellValue('O'.$linha, ' '.$dados["cidade"]);
    $sheet->setCellValue('P'.$linha, number_format(max($dados["porcentagem"], $dados["porcentagem_manual"]), 2, ",", "."));
	$sheet->setCellValue('Q'.$linha, $dados["vendedor"]);
      $sheet->setCellValue('R'.$linha, $dados["cupom_nota_fiscal"]);
      $sheet->setCellValue('S'.$linha, $dados["motivo_cancelamento"]);

	$sheet->setCellValue('T'.$linha, $dados["valor_cfc"] >0 ?
        floatval(number_format($dados["valor_cfc"], 2, ",", ".")) :
        floatval(number_format($dados["valor_sindicato"], 2, ",", ".")));
	$sheet->setCellValue('U'.$linha, floatval(number_format($dados["valor_contrato"], 2, ".", "")));
  }
  $linha++;

  $sheet->setCellValue('T'.$linha, '=SUM(T'.$linhaBase.':T'.($linha-1).')');//Total Valor CFC
  $sheet->setCellValue('U'.$linha, '=SUM(U'.$linhaBase.':U'.($linha-1).')');//Total Valor Aluno
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
?>
