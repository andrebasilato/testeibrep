<?php

$plot = new PHPlot();
$title = utf8_decode("Comparativo Acumulado de Matrículas \n ".$dadosArray['uteis_acumulados_trabalhados']." dias trabalhados");
$plot->SetTitle($title);
$plot->SetYTitle(utf8_decode('Quantidade de matrículas'));
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
    $resultadoBarras[] = array($dados['estado'], (int)$dados['quantidade'], (int)$dadosArray['acumulado_estados_ano_anterior'][$idestado]['quantidade']);
}

$plot->SetDataValues($resultadoBarras);
$plot->SetLegend(array($dadosArray["ano"]["nome"], $dadosArray["ano_anterior"]["nome"]));
$plot->SetLegendPosition(0, 0, 'plot', 0, 0, 10, 10);
//Turn off X axis ticks and labels because they get in the way:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->SetYDataLabelPos('plotin');

$dir = DIR_APP . "/storage/relatorios_gerenciais/";
$filename = "grafico_acumulado_matriculas.jpg";
$plot->SetFileFormat('jpg');
$plot->SetIsInline(true); 
$plot->PHPlot(600,600,$dir.$filename); 
$plot->DrawGraph();


