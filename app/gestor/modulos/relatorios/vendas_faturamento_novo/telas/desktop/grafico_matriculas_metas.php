<?php

$plot = new PHPlot();
$title = utf8_decode("Comparativo Matrículas \n".$dadosArray["mes_nome"]." ".$dadosArray["ano"]["nome"]."/ META");
$plot->SetTitle($title);
$plot->SetYTitle(utf8_decode('Quantidade de matrículas'));
//$plot->SetXTitle('Estados');
$plot->SetShading(20);
$plot->SetImageBorderType('none'); // Improves presentation in the manual
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$resultadoBarras = array();
$legendas = array();

if (!count($dadosArray['estados'])) {
    return;
}

foreach($dadosArray['estados'] as $idestado => $dados) { 
    $resultadoBarras[] = array($dados['estado'], (int)$dados['quantidade'], (int)$dadosArray['estados_metas'][$idestado]['quantidade']);
}

$plot->SetDataValues($resultadoBarras);

$plot->SetLegend(array('Faturamento', 'Metas'));
$plot->SetLegendPosition(0, 0, 'plot', 0, 0, 10, 10);
# Make legend lines go bottom to top, like the bar segments (PHPlot > 5.4.0)
//$plot->SetLegendReverse(True);

$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->SetYDataLabelPos('plotin');

$dir = DIR_APP . "/storage/relatorios_gerenciais/";
$filename = "grafico_matriculas_metas.jpg";
$plot->SetFileFormat('jpg');
$plot->SetIsInline(true); 
$plot->PHPlot(600,500,$dir.$filename); 
$plot->DrawGraph();


