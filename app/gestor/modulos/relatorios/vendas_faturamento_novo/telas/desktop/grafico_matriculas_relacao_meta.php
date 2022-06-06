<?php
//require_once '../classes/phplot/phplot.php';

$plot = new PHPlot();
$title = utf8_decode('% Matrículas em relação a meta');
$plot->SetTitle($title);
$plot->SetYTitle(utf8_decode('Porcentagem (%)'));
$plot->SetShading(20);
$plot->SetImageBorderType('none'); // Improves presentation in the manual
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');

$totais['meta_matricula'] = 0;
$totais['meta_valor'] = 0;
$totais['matricula'] = 0;
$totais['valor'] = 0;

if (!count($dadosArray['estados'])) {
    return;
}

foreach ($dadosArray['estados'] as $idestado => $dados) {
    $totais['meta_matricula'] += $dadosArray['estados_metas'][$idestado]['quantidade'];
    $totais['meta_valor'] += $dadosArray['estados_metas'][$idestado]['valor'];
    $totais['matricula'] += $dados['quantidade'];
    $totais['valor'] += $dados['valor']; 
}

$resultadoBarras = array();
$porcentagem_meta = ($totais['meta_matricula']/$dadosArray['uteis']) * $dadosArray['uteis_trabalhados'];
$resultadoBarras[] = array("Ideal para atingir meta (".(int) $totais['meta_matricula'].")", number_format(((int) $porcentagem_meta/(int) $totais['meta_matricula']) * 100, 2, '.', ''));
foreach($dadosArray['estados'] as $idestado => $dados) { 
    $metas_quantidade = ($dados['quantidade']/$dadosArray['estados_metas'][$idestado]['quantidade']) * 100;
    $resultadoBarras[] = array($dados['estado'].' ('.number_format($dados['quantidade'], 2, '.', '').')', number_format($metas_quantidade, 2, '.', ''));
}
$resultadoBarras[] = array('Total ('.(int) $totais['matricula'].')', number_format(((int) $totais['matricula']/(int) $totais['meta_matricula'])*100, 2, '.', ''));  
$plot->SetDataValues($resultadoBarras);

$plot->SetLegend('Faturamento');


//Turn off X axis ticks and labels because they get in the way:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->SetYDataLabelPos('plotin');

$dir = DIR_APP . "/storage/relatorios_gerenciais/";
$filename = "grafico_matriculas_relacao_meta.jpg";
$plot->SetFileFormat('jpg');
$plot->SetIsInline(true); 
$plot->PHPlot(550,500,$dir.$filename); 
$plot->DrawGraph();


