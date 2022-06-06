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
$sheet->setCellValue('A5', 'Gerado dia '.date("d/m/Y H:i:s").' por '.$usuario["nome"].' ('.$usuario["email"].')');

$linhaBase = 4;
if(count($dadosArray) > 0){
  $sheet->insertNewRowBefore($linhaBase,count($dadosArray));
  foreach($dadosArray as $ind => $dados){	
	$linha = $linhaBase + $ind;	
	$sheet->setCellValue('A'.$linha, $dados["idvendedor"]);
	$sheet->setCellValue('B'.$linha, $dados['nome']);
	$sheet->setCellValue('C'.$linha, $dados['documento']);
	if($dados["data_nasc"] && $dados["data_nasc"] != "0000-00-00") 
		$dados['data_nasc'] = formataData($dados["data_nasc"], "br", 0);
	else
		$dados['data_nasc'] = '';
	$sheet->setCellValue('D'.$linha, $dados['data_nasc']);
	$sheet->setCellValue('E'.$linha, $dados['celular']);
	$sheet->setCellValue('F'.$linha, $dados['telefone']);
	$sheet->setCellValue('G'.$linha, $dados['email']);

	$sheet->setCellValue('H'.$linha, $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$dados["venda_bloqueada"]]);
	$sheet->setCellValue('I'.$linha, $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$dados["ativo_login"]]);
	$sheet->setCellValue('J'.$linha, $dados['cep']);
	$sheet->setCellValue('K'.$linha, $dados['endereco']);
	$sheet->setCellValue('L'.$linha, $dados['bairro']);
	$sheet->setCellValue('M'.$linha, $dados['numero']);
	$sheet->setCellValue('N'.$linha, $dados['regiao']);

	$sheet->setCellValue('O'.$linha, $dados["cidade"]);
	$sheet->setCellValue('P'.$linha, $dados['estado']);
	$sheet->setCellValue('Q'.$linha, $dados['placa_carro']);
	$sheet->setCellValue('R'.$linha, $dados['cartao_combustivel']);
	$sheet->setCellValue('S'.$linha, $dados['observacoes']);
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