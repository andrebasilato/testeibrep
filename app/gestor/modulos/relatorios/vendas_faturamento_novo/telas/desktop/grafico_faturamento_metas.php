<?php

$plot = new PHPlot();
$title = utf8_decode("Comparativo Faturamento \n ".$dadosArray["mes_nome"]." ".$dadosArray["ano"]["nome"]."/ META");
$plot->SetTitle($title);
$plot->SetYTitle('Valor (R$)');
//$plot->SetXTitle('Estados');

$plot->SetShading(20);
$plot->SetImageBorderType('plain');
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$resultadoBarras = array();

if (!count($dadosArray['estados'])) {
    return;
}

foreach($dadosArray['estados'] as $idestado => $dados) { 
    $resultadoBarras[] = array($dados['estado'], number_format($dados['valor'], 2, '.', ''), number_format($dadosArray['estados_metas'][$idestado]['valor'], 2, '.', ''));
}

$plot->SetDataValues($resultadoBarras);
$plot->SetLegend(array('Faturamento', 'Metas'));
$plot->SetLegendPosition(0, 0, 'plot', 0, 0, 10, 10);

$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->SetYDataLabelPos('plotin');

$dir = DIR_APP . "/storage/relatorios_gerenciais/";
$filename = "grafico_faturamento_metas.jpg";
$plot->SetFileFormat('jpg');
$plot->SetIsInline(true); 
$plot->PHPlot(550,500,$dir.$filename); 
$plot->DrawGraph();


