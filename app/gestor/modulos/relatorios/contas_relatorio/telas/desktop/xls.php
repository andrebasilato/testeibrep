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
$objPHPExcel->setActiveSheetIndexByName("Contas");
$sheetContas = $objPHPExcel->getActiveSheet();

// Data e Hora que foi gerado
$sheetContas->setCellValue('A6', 'Gerado dia ' . date("d/m/Y H:i:s") . ' por ' . $usuario["nome"] . ' (' . $usuario["email"] . ')');

$letras = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
    "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ",
    "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ",
    "CA", "CB", "CC", "CD", "CE", "CF", "CG", "CH", "CI", "CJ", "CK", "CL", "CM", "CN", "CO", "CP", "CQ", "CR", "CS", "CT", "CU", "CV", "CW", "CX", "CY", "CZ",
    "DA", "DB", "DC", "DD", "DE", "DF", "DG", "DH", "DI", "DJ", "DK", "DL", "DM", "DN", "DO", "DP", "DQ", "DR", "DS", "DT", "DU", "DV", "DW", "DX", "DY", "DZ",
    "EA", "EB", "EC", "ED", "EE", "EF", "EG", "EH", "EI", "EJ", "EK", "EL", "EM", "EN", "EO", "EP", "EQ", "ER", "ES", "ET", "EU", "EV", "EW", "EX", "EY", "EZ",
    "FA", "FB", "FC", "FD", "FE", "FF", "FG", "FH", "FI", "FJ", "FK", "FL", "FM", "FN", "FO", "FP", "FQ", "FR", "FS", "FT", "FU", "FV", "FW", "FX", "FY", "FZ",
    "GA", "GB", "GC", "GD", "GE", "GF", "GG", "GH", "GI", "GJ", "GK", "GL", "GM", "GN", "GO", "GP", "GQ", "GR", "GS", "GT", "GU", "GV", "GW", "GX", "GY", "GZ",
    "HA", "HB", "HC", "HD", "HE", "HF", "HG", "HH", "HI", "HJ", "HK", "HL", "HM", "HN", "HO", "HP", "HQ", "HR", "HS", "HT", "HU", "HV", "HW", "HX", "HY", "HZ");
$numerosEventosFinanceiros = array();
$numerosCentrosDeCustos = array();

$contador = -1;
$totalColspan1 = 0;
foreach ($colunas as $ind => $coluna) {
    if (in_array($ind, $_GET['colunas'])) {
        if ($ind < 11) {
            $totalColspan1++;
        }
        $contador++;
        $sheetContas->setCellValue($letras[$contador] . '2', $idioma[$coluna]);
    }
}

$sheetContas->mergeCells('A1:' . $letras[$contador] . '1');
$sheetContas->mergeCells('A6:' . $letras[$contador] . '6');

$linhaBase = 4;
if (count($dadosArray) > 0) {
    $sheetContas->insertNewRowBefore($linhaBase, count($dadosArray));
    foreach ($dadosArray as $ind => $dados) {
        $linha = $linhaBase + $ind;

        $contador = -1;
        foreach ($colunas as $ind2 => $coluna) {
            if (in_array($ind2, $_GET['colunas'])) {
                $contador++;
                if ($coluna == 'valor' || $coluna == 'valor_juros' || $coluna == 'valor_multa' || $coluna == 'valor_desconto' || $coluna == 'valor_contrato') {
                    $sheetContas->getStyle($letras[$contador] . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheetContas->setCellValue($letras[$contador] . $linha, floatval(number_format($dados[$coluna], 2, ".", "")));
                } elseif ($coluna == 'valor_liquido') {
                    $sheetContas->getStyle($letras[$contador] . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $dados[$coluna] = ($dados["valor"] + $dados["valor_juros"] + $dados["valor_multa"]) - $dados["valor_desconto"];
                    $sheetContas->setCellValue($letras[$contador] . $linha, floatval(number_format($dados[$coluna], 2, ".", "")));
                } elseif ($coluna == 'data_vencimento' || $coluna == 'data_pagamento' || $coluna == 'data1_cheque_alinea' || $coluna == 'data2_cheque_alinea' || $coluna == 'data3_cheque_alinea') {
                    $sheetContas->setCellValue($letras[$contador] . $linha, formataData($dados[$coluna], "br", 0));
                } elseif ($coluna == 'parcela') {
                    $sheetContas->setCellValue($letras[$contador] . $linha, $dados["parcela"] . "/" . $dados["total_parcelas"]);
                } elseif ($coluna == 'forma_pagamento') {
                    $sheetContas->setCellValue($letras[$contador] . $linha, $forma_pagamento_conta[$config["idioma_padrao"]][$dados[$coluna]]);
                } elseif ($coluna == 'adimplente') {
                    $dados["adimplente"] = "Sim";
                    if ($dados["data_vencimento"] < date('Y-m-d') && (!$dados["data_pagamento"] || $dados["data_pagamento"] == '0000-00-00')) {
                        $dados["adimplente"] = "Não";
                    }
                    $sheetContas->setCellValue($letras[$contador] . $linha, $dados[$coluna]);
                } elseif ($coluna == 'possui_orcamento') {
                    $valor = '';
                    if (!empty($dados[$coluna])) {
                        $valor = $sim_nao["pt_br"][$dados[$coluna]];
                    }

                    $sheetContas->setCellValue($letras[$contador] . $linha, $valor);
                } else {
                    $sheetContas->setCellValue($letras[$contador] . $linha, $dados[$coluna]);
                }
            }
        }

        if ($dados["idevento"]) {
            $numerosEventosFinanceiros[$dados['idevento']]['parcelas']++;
            $numerosEventosFinanceiros[$dados['idevento']]['total'] += $dados["valor"];
            $numerosEventosFinanceiros[$dados['idevento']]['desconto'] += $dados["valor_desconto"];
        }

        if (count($dados['centros_custos']) > 0) {
            foreach ($dados['centros_custos'] as $idcentro_custo => $dados_centro_custo) {
                $valorContaCentroCusto = ($dados["valor"] * $dados_centro_custo['porcentagem']) / 100;

                $numerosCentrosDeCustos[$idcentro_custo]['contas']++;
                $numerosCentrosDeCustos[$idcentro_custo]['total'] += $valorContaCentroCusto;
                $numerosCentrosDeCustos['total'] += $valorContaCentroCusto;

                // Dados para aba de Rateios
                $rateios[$dados['idmatricula']][$dados['idconta']][$idcentro_custo]['porcentagem'] = $dados_centro_custo['porcentagem'];
                $rateios[$dados['idmatricula']][$dados['idconta']][$idcentro_custo]['valor_cdc'] = number_format($dados_centro_custo['valor'], 2, ",", ".");
                $rateios[$dados['idmatricula']][$dados['idconta']]['valor'] = number_format($dados['valor'], 2, ",", ".");
            }
        }
    }
}

if ($totalColspan1) {
    $linha++;
    if (in_array(11, $_GET['colunas'])) {
        $sheetContas->getStyle($letras[$totalColspan1] . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheetContas->setCellValue($letras[$totalColspan1] . $linha, '=SUM(' . $letras[$totalColspan1] . $linhaBase . ':' . $letras[$totalColspan1] . ($linha - 1) . ')');
    }
    if (in_array(12, $_GET['colunas'])) {
        $sheetContas->getStyle($letras[$totalColspan1 + 1] . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheetContas->setCellValue($letras[$totalColspan1 + 1] . $linha, '=SUM(' . $letras[$totalColspan1 + 1] . $linhaBase . ':' . $letras[$totalColspan1 + 1] . ($linha - 1) . ')');
    }
    if (in_array(13, $_GET['colunas'])) {
        $sheetContas->getStyle($letras[$totalColspan1 + 2] . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheetContas->setCellValue($letras[$totalColspan1 + 2] . $linha, '=SUM(' . $letras[$totalColspan1 + 2] . $linhaBase . ':' . $letras[$totalColspan1 + 2] . ($linha - 1) . ')');
    }
    if (in_array(14, $_GET['colunas'])) {
        $sheetContas->getStyle($letras[$totalColspan1 + 3] . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheetContas->setCellValue($letras[$totalColspan1 + 3] . $linha, '=SUM(' . $letras[$totalColspan1 + 3] . $linhaBase . ':' . $letras[$totalColspan1 + 3] . ($linha - 1) . ')');
    }
    if (in_array(15, $_GET['colunas'])) {
        $sheetContas->getStyle($letras[$totalColspan1 + 4] . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheetContas->setCellValue($letras[$totalColspan1 + 4] . $linha, '=SUM(' . $letras[$totalColspan1 + 4] . $linhaBase . ':' . $letras[$totalColspan1 + 4] . ($linha - 1) . ')');
    }
}

$objPHPExcel->setActiveSheetIndexByName("Eventos Financeiros");
$sheetEventosFinanceiros = $objPHPExcel->getActiveSheet();
$sheetEventosFinanceiros->setCellValue("A" . '2', "Evento financeiro");
$sheetEventosFinanceiros->setCellValue("B" . '2', "Parcelas");
$sheetEventosFinanceiros->setCellValue("C" . '2', "Total");
$sheetEventosFinanceiros->setCellValue("D" . '2', "Desconto");
$sheetEventosFinanceiros->setCellValue("E" . '2', "Previsão Real");
$sheetEventosFinanceiros->mergeCells('A1:' . "E" . '1');
$sheetEventosFinanceiros->mergeCells('A6:' . "E" . '6');
$sheetEventosFinanceiros->setCellValue('A6', 'Gerado dia ' . date("d/m/Y H:i:s") . ' por ' . $usuario["nome"] . ' (' . $usuario["email"] . ')');

{
    if (count($eventosFinanceiros) > 0)
        $sheetEventosFinanceiros->insertNewRowBefore($linhaBase, count($eventosFinanceiros));

    $previsaoReal = 0;
    $linha = 3;
    foreach ($eventosFinanceiros as $chave => $eventoFinanceiro) {
        $previsaoReal = $numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['total'] - $numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['desconto'];
        $sheetEventosFinanceiros->setCellValue("A" . $linha, $eventoFinanceiro["nome"]);
        $sheetEventosFinanceiros->setCellValue("B" . $linha, $numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['parcelas'] ? $numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['parcelas'] : 0);
        $sheetEventosFinanceiros->setCellValue("C" . $linha, number_format($numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['total'], 2, ',', '.'));
        $sheetEventosFinanceiros->setCellValue("D" . $linha, number_format($numerosEventosFinanceiros[$eventoFinanceiro['idevento']]['desconto'], 2, ',', '.'));
        $sheetEventosFinanceiros->setCellValue("E" . $linha, number_format($previsaoReal, 2, ',', '.'));
        $linha++;

    }
}

$objPHPExcel->setActiveSheetIndexByName("Centros de Custos");
$sheetCentrosDeCustos = $objPHPExcel->getActiveSheet();
$sheetCentrosDeCustos->setCellValue("A" . '2', "Centro de Custo");
$sheetCentrosDeCustos->setCellValue("B" . '2', "Contas");
$sheetCentrosDeCustos->setCellValue("C" . '2', "Total");
$sheetCentrosDeCustos->setCellValue("D" . '2', "%");
$sheetCentrosDeCustos->mergeCells('A1:' . "D" . '1');
$sheetCentrosDeCustos->mergeCells('A6:' . "D" . '6');
$sheetCentrosDeCustos->setCellValue('A6', 'Gerado dia ' . date("d/m/Y H:i:s") . ' por ' . $usuario["nome"] . ' (' . $usuario["email"] . ')');

{
    if (count($centrosDeCustos) > 0)
        $sheetCentrosDeCustos->insertNewRowBefore($linhaBase, count($centrosDeCustos));

    $previsaoReal = 0;
    $linha = 3;
    foreach ($centrosDeCustos as $chave => $centroDeCusto) {
        $porcentagem = ($numerosCentrosDeCustos[$centroDeCusto['idcentro_custo']]['total'] * 100) / $numerosCentrosDeCustos['total'];
        $sheetCentrosDeCustos->setCellValue("A" . $linha, $centroDeCusto['nome']);
        $sheetCentrosDeCustos->setCellValue("B" . $linha, $numerosCentrosDeCustos[$centroDeCusto['idcentro_custo']]['contas'] ? $numerosCentrosDeCustos[$centroDeCusto['idcentro_custo']]['contas'] : 0);
        $sheetCentrosDeCustos->setCellValue("C" . $linha, number_format($numerosCentrosDeCustos[$centroDeCusto['idcentro_custo']]['total'], 2, ',', '.'));
        $sheetCentrosDeCustos->setCellValue("D" . $linha, is_nan($porcentagem) ? 0 : number_format($porcentagem, 2, ',', '.'));
        $linha++;

    }
    $sheetCentrosDeCustos->setCellValue("A" . $linha, "Total: ");
    $sheetCentrosDeCustos->setCellValue("B" . $linha, number_format($numerosCentrosDeCustos['total'], 2, ',', '.'));
    $sheetCentrosDeCustos->setCellValue("C" . $linha, "");
    $sheetCentrosDeCustos->setCellValue("D" . $linha, "");

}

/**
 *      Aba da planilha: Rateios
 */

$objPHPExcel->setActiveSheetIndexByName("Rateios");
$sheetRateios = $objPHPExcel->getActiveSheet();
$sheetRateios->setCellValue("A" . '2', "Matrícula");
$sheetRateios->setCellValue("B" . '2', "Conta");
$sheetRateios->setCellValue("C" . '2', "Valor");

$linha = 3;
$totalColunas = 4;
ksort($rateios);

foreach ($rateios as $idmatricula => $rateio) {

    foreach ($rateio as $idconta => $conta) {

        $sheetRateios->setCellValue("A" . $linha, $idmatricula);
        $sheetRateios->setCellValue("B" . $linha, $idconta);
        $sheetRateios->setCellValue("C" . $linha, $conta['valor']);
        $sheetRateios->getStyle("C" . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $contadorColuna = 3; //Sempre inserir a partir da letra D
        $contadorCDC = 1;
        foreach ($conta as $idcentro_c => $centro_c) {

            if (!empty($centro_c['porcentagem'])) {
                $sheetRateios->setCellValue($letras[$contadorColuna] . '2', "CDC " . $contadorCDC . " - Nome");
                $sheetRateios->setCellValue($letras[$contadorColuna] . $linha, $centrosDeCustos[$idcentro_c]['nome']);
                $contadorColuna++;
                $sheetRateios->setCellValue($letras[$contadorColuna] . '2', "CDC " . $contadorCDC . " - Valor");
                $sheetRateios->setCellValue($letras[$contadorColuna] . $linha, $centro_c['valor_cdc']);
                $sheetRateios->getStyle($letras[$contadorColuna] . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $contadorColuna++;
                $sheetRateios->setCellValue($letras[$contadorColuna] . '2', "CDC " . $contadorCDC . " - Porcentagem");
                $sheetRateios->setCellValue($letras[$contadorColuna] . $linha, is_nan($centro_c['porcentagem']) ? 0 : number_format($centro_c['porcentagem'], 2, ',', '.') . '%');
                $sheetRateios->getStyle($letras[$contadorColuna] . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $contadorColuna++;
                $contadorCDC++;
                $contadorColuna < $totalColunas ?: $totalColunas = $contadorColuna; // Verificar o total de colunas
            }
        }
        $linha++;
    }
}
$linha++;

// Data e Hora que foi gerado
$sheetRateios->setCellValue('A' . $linha, 'Gerado dia ' . date("d/m/Y H:i:s") . ' por ' . $usuario["nome"] . ' (' . $usuario["email"] . ')');
$sheetRateios->getStyle('A' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheetRateios->mergeCells('A' . $linha . ':' . $letras[$totalColunas - 1] . $linha);
$sheetRateios->mergeCells('A1:' . $letras[$totalColunas - 1] . '1');

$objPHPExcel->setActiveSheetIndexByName("Contas");

$sheetContas->removeRow($linhaBase - 1, 1);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($arquivo_gerado);

header("Content-type: ".filetype($arquivo_gerado));
header('Content-Disposition: attachment; filename="'.basename($nome_arquivo).'"');
header('Content-Length: '.filesize($arquivo_gerado));
header('Expires: 0');
header('Pragma: no-cache');
readfile($arquivo_gerado);
?>
