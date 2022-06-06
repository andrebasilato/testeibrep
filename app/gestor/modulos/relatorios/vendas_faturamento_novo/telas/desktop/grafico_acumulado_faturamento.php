<?php

$plot = new PHPlot();
$title = utf8_decode("Comparativo Acumulado Faturamento \n ".$dadosArray['uteis_acumulados_trabalhados']." dias trabalhados");
$plot->SetTitle($title);
$plot->SetYTitle('Faturamento');
//$plot->SetXTitle('Estados');
$plot->SetShading(20);
$plot->SetImageBorderType('plain'); // Improves presentation in the manual
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');

$resultadoBarras = array();
if (!count($dadosArray['acumulado_estados'])) {
    return;
}
foreach($dadosArray['acumulado_estados'] as $idestado => $dados) { 
    $resultadoBarras[] = array($dados['estado'], number_format($dados['valor'], 2, '.', ''), number_format($dadosArray['acumulado_estados_ano_anterior'][$idestado]['valor'], 2, '.', ''));
}

$plot->SetLegend(array($dadosArray["ano"]["nome"], $dadosArray["ano_anterior"]["nome"]));
$plot->SetLegendPosition(0, 0, 'plot', 0, 0, 10, 10);
$plot->SetDataValues($resultadoBarras);

//Turn off X axis ticks and labels because they get in the way:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->SetYDataLabelPos('plotin');

$dir = DIR_APP . "/storage/relatorios_gerenciais/";
$filename = "grafico_acumulado_faturamento.jpg";
$plot->SetFileFormat('jpg');
$plot->SetIsInline(true); 
$plot->PHPlot(550,500,$dir.$filename); 
$plot->DrawGraph();


