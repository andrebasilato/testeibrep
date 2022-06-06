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
$sheet->setCellValue('A6', 'Gerado dia '.date("d/m/Y H:i:s").' por '.$usuario["nome"].' ('.$usuario["email"].')');

$letras = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
				"AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",
				"BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ",
				"CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ",
				"DA","DB","DC","DD","DE","DF","DG","DH","DI","DJ","DK","DL","DM","DN","DO","DP","DQ","DR","DS","DT","DU","DV","DW","DX","DY","DZ",
				"EA","EB","EC","ED","EE","EF","EG","EH","EI","EJ","EK","EL","EM","EN","EO","EP","EQ","ER","ES","ET","EU","EV","EW","EX","EY","EZ",
				"FA","FB","FC","FD","FE","FF","FG","FH","FI","FJ","FK","FL","FM","FN","FO","FP","FQ","FR","FS","FT","FU","FV","FW","FX","FY","FZ",
				"GA","GB","GC","GD","GE","GF","GG","GH","GI","GJ","GK","GL","GM","GN","GO","GP","GQ","GR","GS","GT","GU","GV","GW","GX","GY","GZ",
				"HA","HB","HC","HD","HE","HF","HG","HH","HI","HJ","HK","HL","HM","HN","HO","HP","HQ","HR","HS","HT","HU","HV","HW","HX","HY","HZ");

$contador = -1;
$totalColspan1 = 0;

$vendasSindicato = array();
$totalMatriculasSindicatos = 0;
$totalFaturamentoSindicatos = 0;
$totalTaxasSindicatos = 0;

foreach($dadosArray as $ind => $linha) {
	$vendasSindicato[$linha["idsindicato"]]["nome"] = $linha["sindicato"];
	$vendasSindicato[$linha["idsindicato"]]["matriculas"] += 1;
	$vendasSindicato[$linha["idsindicato"]]["faturamento"] += $linha["valor_contrato"];
	$vendasSindicato[$linha["idsindicato"]]["taxa"] += $linha["valor_por_matricula"];
}

foreach($colunas as $ind => $coluna) {

  if(in_array($ind,$_GET['colunas'])) {
	//if($ind < 8) {
	  $totalColspan1++;
	//}

	$contador++;
	$sheet->setCellValue($letras[$contador].'2', $idioma[$coluna]);
  }
}



$sheet->mergeCells('A1:'.$letras[$contador].'1');
$sheet->mergeCells('A6:'.$letras[$contador].'6');

$linhaBase = 4; //print_r2($dadosArray,1);
if(count($dadosArray) > 0){
  $sheet->insertNewRowBefore($linhaBase,count($dadosArray) + 5);
  foreach($dadosArray as $ind => $dados){
	$linha = $linhaBase + $ind;

	$contador = -1;
	foreach($colunas as $ind2 => $coluna) {
	  if(in_array($ind2,$_GET['colunas'])) {
		$contador++;
		if ($coluna == 'data' && in_array(1,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, formataData($dados['data_cad'],"br",0));
		} else if ($coluna == 'matricula' && in_array(2,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['idmatricula']);
		} else if ($coluna == 'contrato' && in_array(3,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['numero_contrato']);
		} else if ($coluna == 'aluno' && in_array(4,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['cliente']);
		} else if ($coluna == 'situacao' && in_array(5,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['situacao_wf_nome']);
		} else if ($coluna == 'oferta' && in_array(6,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['oferta']);
		} else if ($coluna == 'curso' && in_array(7,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['curso']);
		} else if ($coluna == 'solicitante' && in_array(8,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['solicitante']);
		} else if ($coluna == 'turma' && in_array(9,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['turma']);
		} else if ($coluna == 'escola' && in_array(10,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['escola']);
		} else if ($coluna == 'estado' && in_array(11,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['estado']);
		} else if ($coluna == 'cidade' && in_array(12,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['cidade']);
		} else if ($coluna == 'forma_pagamento' && in_array(13,$_GET['colunas'])) {
			$forma_conta = ($dados["forma_pagamento"]) ? $GLOBALS["forma_pagamento_conta"]["pt_br"][$dados["forma_pagamento"]] : '--';
			$sheet->setCellValue($letras[$contador].$linha, $forma_conta);
		} else if ($coluna == 'parcelas' && in_array(14,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['quantidade_parcelas']);
		} else if ($coluna == 'vendedor' && in_array(15,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, $dados['vendedor']);
		} else if ($coluna == 'valor_contrato' && in_array(16,$_GET['colunas'])) {
			$sheet->setCellValue($letras[$contador].$linha, floatval(number_format($dados["valor_contrato"], 2, ".", "")));
		}

	  }
	}
  }
}


$sheet
	->setCellValue("A${contador}",'Sindicato')
	->setCellValue("B${contador}",'Matrículas')
	->setCellValue("C${contador}",'Faturamento')
	->setCellValue("D${contador}",'Média')
	->setCellValue("E${contador}",'Taxa')
  ->getStyle("A${contador}:E${contador}")
	->getFont()
	->setBold(true);
foreach($vendasSindicato as $ind => $linha) {
	$contador++;
	$totalMatriculasSindicatos += $linha["matriculas"];
	$totalFaturamentoSindicatos += $linha["faturamento"];
	$totalTaxasSindicatos += $linha["taxa"];
	$sheet
		->setCellValue("A${contador}", $linha['nome'])
		->setCellValue("B${contador}",$linha["matriculas"])
		->setCellValue("C${contador}",'R$ ' . number_format($linha["faturamento"], 2, ',', '.'))
		->setCellValue("D${contador}",'R$ ' . number_format($linha["faturamento"]/$linha["matriculas"], 2, ',', '.'))
		->setCellValue("E${contador}", 'R$ ' . number_format($linha["taxa"], 2, ',', '.'));
}
$sheet
	->setCellValue('A' . ++$contador, 'Total')
	->setCellValue("B${contador}", $totalMatriculasSindicatos)
	->setCellValue("C${contador}",'R$ ' . number_format($totalFaturamentoSindicatos, 2, ',', '.'))
	->setCellValue("E${contador}", 'R$ ' . number_format($totalTaxasSindicatos, 2, ',', '.'));




/*if($totalColspan1) {
  $linha++;
  if(in_array(8,$_GET['colunas'])) {
	$sheet->getStyle($letras[$totalColspan1].$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$sheet->setCellValue($letras[$totalColspan1].$linha, '=SUM('.$letras[$totalColspan1].$linhaBase.':'.$letras[$totalColspan1].($linha-1).')');
  }
  if(in_array(9,$_GET['colunas'])) {
	$sheet->getStyle($letras[$totalColspan1+1].$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$sheet->setCellValue($letras[$totalColspan1+1].$linha, '=SUM('.$letras[$totalColspan1+1].$linhaBase.':'.$letras[$totalColspan1+1].($linha-1).')');
  }
  if(in_array(10,$_GET['colunas'])) {
	$sheet->getStyle($letras[$totalColspan1+2].$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$sheet->setCellValue($letras[$totalColspan1+2].$linha, '=SUM('.$letras[$totalColspan1+2].$linhaBase.':'.$letras[$totalColspan1+2].($linha-1).')');
  }
  if(in_array(11,$_GET['colunas'])) {
	$sheet->getStyle($letras[$totalColspan1+3].$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$sheet->setCellValue($letras[$totalColspan1+3].$linha, '=SUM('.$letras[$totalColspan1+3].$linhaBase.':'.$letras[$totalColspan1+3].($linha-1).')');
  }
  if(in_array(12,$_GET['colunas'])) {
	$sheet->getStyle($letras[$totalColspan1+4].$linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$sheet->setCellValue($letras[$totalColspan1+4].$linha, '=SUM('.$letras[$totalColspan1+4].$linhaBase.':'.$letras[$totalColspan1+4].($linha-1).')');
  }
}*/

/*$linha++;
$sheet->setCellValue('A'.$linha, $idioma['evento_financeiro']);
$sheet->setCellValue('B'.$linha, $idioma['parcelas']);
$sheet->setCellValue('C'.$linha, $idioma['total']);
$sheet->setCellValue('D'.$linha, $idioma['desconto']);
$sheet->setCellValue('E'.$linha, $idioma['previsao_real']);*/

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
