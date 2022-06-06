<?php

$plot = new PHPlot();
$title = utf8_decode('Estados x Faturamento');
$plot->SetTitle($title);
$plot->SetShading(20);
$plot->SetImageBorderType('none'); // Improves presentation in the manual
$plot->SetPlotType('pie');
$plot->SetDataType('text-data-single');
$plot->SetDataColors();

$resultadoPizza = array();
$legendas = array();

if (!count($dadosArray['estados'])) {
    return;
}

foreach($dadosArray['estados'] as $idestado => $dados) { 
    $legendas[] = $dados['estado'].' (R$ '.number_format($dados['valor'].')', 0, '.', '.').')';
    $resultadoPizza[] = array($dados['estado'].' (R$ '.number_format($dados['valor'].')', 0, '.', '.').')', number_format($dados['valor'], 0, '.', '.')); 
}

$plot->SetDataValues($resultadoPizza);

$plot->SetLegend($legendas);
$plot->SetLegendPosition(0, 0, 'plot', 0, 0, 10, 10);

$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->SetPieLabelType(array('percent', 'label'), 'custom', 'mycallback');

$dir = DIR_APP . "/storage/relatorios_gerenciais/";
$filename = "grafico_estados_faturamento.jpg";
$plot->SetFileFormat('jpg');
$plot->SetIsInline(true); 
$plot->PHPlot(400,400,$dir.$filename); 
$plot->DrawGraph();


