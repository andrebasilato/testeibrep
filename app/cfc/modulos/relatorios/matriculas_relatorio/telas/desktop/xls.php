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
	$sheet->setCellValue('B'.$linha, formataData($dados["data_cad"],"br",0));
	$sheet->setCellValue('C'.$linha, $dados["situacao_wf_nome"]);
	$sheet->setCellValue('D'.$linha, $dados["oferta"]);
	$sheet->setCellValue('E'.$linha, $dados["curso"]);
	$sheet->setCellValue('F'.$linha, $dados["turma"]);
	if($dados["empresa"])
		$sheet->setCellValue('G'.$linha, $dados["empresa"]);
	else
		$sheet->setCellValue('G'.$linha, "--");
	$sheet->setCellValue('H'.$linha, $dados["idpessoa"]);
	$sheet->setCellValue('I'.$linha, $dados["cliente"]);
	$sheet->setCellValue('J'.$linha, ' '.$dados["documento"]);
    $sheet->setCellValue('K'.$linha, ' '.$dados["telefone"]);
    $sheet->setCellValue('L'.$linha, ' '.$dados["celular"]);
    $sheet->setCellValue('M'.$linha, ' '.$dados["estado"]);
    $sheet->setCellValue('N'.$linha, ' '.$dados["cidade"]);
    
    $idpedido = '--';
    if($dados["idpedido"]) {
        $idpedido = $dados["idpedido"];
    }
    $sheet->setCellValue('O'.$linha, $idpedido);
    
    $cupom = '--';
    if($dados["idcupom"]) {
        if($dados["tipo_desconto_cupom"] == "V") {
            $cupom = $dados["codigo_cupom"]." - ".$dados["cupom"]." [R$ ".number_format($dados["valor_cupom"], 2, ",", ".")."]";
        } else {
            $cupom = $dados["codigo_cupom"]." - ".$dados["cupom"]." [".number_format($dados["porcentagem_cupom"], 2, ",", ".")." %]";
        }
    }
    $sheet->setCellValue('P'.$linha, $cupom);
    
    $sheet->setCellValue('Q'.$linha, number_format(max($dados["porcentagem"], $dados["porcentagem_manual"]), 2, ",", "."));
	$sheet->setCellValue('R'.$linha, $dados["vendedor"]);
	$sheet->setCellValue('S'.$linha, $dados["cupom_nota_fiscal"]);
	$sheet->setCellValue('T'.$linha, floatval(number_format($dados["valor_contrato"], 2, ".", "")));
  }
  $linha++;
  $sheet->setCellValue('T'.$linha, '=SUM(T'.$linhaBase.':T'.($linha-1).')');//TotAL
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