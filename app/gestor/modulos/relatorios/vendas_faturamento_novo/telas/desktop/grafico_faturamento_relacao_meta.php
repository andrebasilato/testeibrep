<?php

$plot = new PHPlot();
$title = utf8_decode('% Faturamento em relação a meta');
$plot->SetTitle($title);
$plot->SetYTitle(utf8_decode('Porcentagem (%)'));
$plot->SetShading(20);
$plot->SetImageBorderType('none'); // Improves presentation in the manual
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');

$resultadoBarras = array();
$porcentagem_meta_valor = ($totais['meta_valor']/$dadosArray['uteis'])*$dadosArray['uteis_trabalhados'];
$resultadoBarras[] = array('Ideal para atingir meta (R$ '.number_format($totais['meta_valor'], 0, '', '.').')', number_format(((int) $porcentagem_meta_valor/(int) $totais['meta_valor'])*100, 2, '.', ''));

if (!count($dadosArray['estados'])) {
    return;
}

foreach($dadosArray['estados'] as $idestado => $dados) { 
    $metas_valor = ($dados['valor']/$dadosArray['estados_metas'][$idestado]['valor'])*100;
    $resultadoBarras[] = array($dados['estado'].' (R$ '.number_format($dados['valor'], 0, '', '.').')', number_format($metas_valor, 0, '', '.'));
}
$resultadoBarras[] = array('Total (R$ '.number_format($totais['valor'], 0, '', '.').')', number_format(((int) $totais['valor']/(int) $totais['meta_valor'])*100, 0, '', '.'));  

$plot->SetDataValues($resultadoBarras);

$plot->SetLegend('Faturamento');


//Turn off X axis ticks and labels because they get in the way:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->SetYDataLabelPos('plotin');

$dir = DIR_APP . "/storage/relatorios_gerenciais/";
$filename = "grafico_faturamento_relacao_meta.jpg";
$plot->SetFileFormat('jpg');
$plot->SetIsInline(true); 
$plot->PHPlot(550,500,$dir.$filename); 
$plot->DrawGraph();


