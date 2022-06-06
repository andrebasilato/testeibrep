<?php
function mycallback($str)
{
    list($percent, $label) = explode(' ', $str, 2);
    return sprintf("%s \n (%.1f%%)", $label, $percent);
}
function gerarGraficoEstadosMatriculas($dadosArray)
{
    $plot = new PHPlot();
    $title = utf8_decode('Estados x Matrículas');
    $plot->SetTitle($title);
    $plot->SetShading(20);
    $plot->SetImageBorderType('none');
    $plot->SetPlotType('pie');
    $plot->SetDataType('text-data-single');

    $resultadoPizza = array();
    $legendas = array();
    if (!count($dadosArray['estados'])) {
        return;
    }

    foreach($dadosArray['estados'] as $idestado => $dados) {
        $legendas[] = $dados['estado'].' ('.$dados['quantidade'].')';
        $resultadoPizza[] = array($dados['estado']." (".$dados['quantidade']." Mat.)", (float)$dados['quantidade']);
    }

    $plot->SetDataValues($resultadoPizza);

    $plot->SetLegend($legendas);
    $plot->SetLegendPosition(0, 0, 'plot', 0, 0, 10, 10);

    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');

    $plot->SetPieLabelType(array('percent', 'label'), 'custom', 'mycallback');

    $dir = DIR_APP . "/storage/relatorios_gerenciais/";
    $filename = "grafico_estados_matriculas.jpg";
    $plot->SetFileFormat('jpg');
    $plot->SetIsInline(true); 
    $plot->PHPlot(400,400,$dir.$filename); 
    $plot->DrawGraph();
}

function gerarRelatorioEstatosFaturamento($dadosArray)
{
    $plot = new PHPlot();
    $title = utf8_decode('Estados x Faturamento');
    $plot->SetTitle($title);
    $plot->SetShading(20);
    $plot->SetImageBorderType('none');
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
}

function gerarGraficoMatriculasMetas($dadosArray) 
{
    $plot = new PHPlot();
    $title = utf8_decode("Comparativo Matrículas \n".$dadosArray["mes_nome"]." ".$dadosArray["ano"]["nome"]."/ META");
    $plot->SetTitle($title);
    $plot->SetYTitle(utf8_decode('Quantidade de matrículas'));
    $plot->SetShading(20);
    $plot->SetImageBorderType('none');
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

    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');

    $plot->SetYDataLabelPos('plotin');

    $dir = DIR_APP . "/storage/relatorios_gerenciais/";
    $filename = "grafico_matriculas_metas.jpg";
    $plot->SetFileFormat('jpg');
    $plot->SetIsInline(true); 
    $plot->PHPlot(600,500,$dir.$filename); 
    $plot->DrawGraph();
}

function gerarGraficoFaturamentoMetas($dadosArray)
{
    $plot = new PHPlot();
    $title = utf8_decode("Comparativo Faturamento \n ".$dadosArray["mes_nome"]." ".$dadosArray["ano"]["nome"]."/ META");
    $plot->SetTitle($title);
    $plot->SetYTitle('Valor (R$)');

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
}

function gerarGraficoAcumuladoMatriculas($dadosArray) 
{
    $plot = new PHPlot();
    $title = utf8_decode("Comparativo Acumulado de Matrículas \n ".$dadosArray['uteis_acumulados_trabalhados']." dias trabalhados");
    $plot->SetTitle($title);
    $plot->SetYTitle(utf8_decode('Quantidade de matrículas'));
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
    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');

    $plot->SetYDataLabelPos('plotin');

    $dir = DIR_APP . "/storage/relatorios_gerenciais/";
    $filename = "grafico_acumulado_matriculas.jpg";
    $plot->SetFileFormat('jpg');
    $plot->SetIsInline(true); 
    $plot->PHPlot(600,600,$dir.$filename); 
    $plot->DrawGraph();
}

function gerarGraficoAcumuladoFaturamento($dadosArray) 
{
    $plot = new PHPlot();
    $title = utf8_decode("Comparativo Acumulado Faturamento \n ".$dadosArray['uteis_acumulados_trabalhados']." dias trabalhados");
    $plot->SetTitle($title);
    $plot->SetYTitle('Faturamento');
    $plot->SetShading(20);
    $plot->SetImageBorderType('plain');
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

    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');

    $plot->SetYDataLabelPos('plotin');

    $dir = DIR_APP . "/storage/relatorios_gerenciais/";
    $filename = "grafico_acumulado_faturamento.jpg";
    $plot->SetFileFormat('jpg');
    $plot->SetIsInline(true); 
    $plot->PHPlot(550,500,$dir.$filename); 
    $plot->DrawGraph();
}

function gerarGraficoMatriculasRelacaoAMeta($dadosArray)
{
    $plot = new PHPlot();
    $title = utf8_decode('% Matrículas em relação a meta');
    $plot->SetTitle($title);
    $plot->SetYTitle(utf8_decode('Porcentagem (%)'));
    $plot->SetShading(20);
    $plot->SetImageBorderType('none'); 
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

    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');

    $plot->SetYDataLabelPos('plotin');

    $dir = DIR_APP . "/storage/relatorios_gerenciais/";
    $filename = "grafico_matriculas_relacao_meta.jpg";
    $plot->SetFileFormat('jpg');
    $plot->SetIsInline(true); 
    $plot->PHPlot(550,500,$dir.$filename); 
    $plot->DrawGraph();
}

function gerarGraficoFaturamentoRelacaoAMeta($dadosArray)
{
    $plot = new PHPlot();
    $title = utf8_decode('% Faturamento em relação a meta');
    $plot->SetTitle($title);
    $plot->SetYTitle(utf8_decode('Porcentagem (%)'));
    $plot->SetShading(20);
    $plot->SetImageBorderType('none');
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

    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');

    $plot->SetYDataLabelPos('plotin');

    $dir = DIR_APP . "/storage/relatorios_gerenciais/";
    $filename = "grafico_faturamento_relacao_meta.jpg";
    $plot->SetFileFormat('jpg');
    $plot->SetIsInline(true); 
    $plot->PHPlot(550,500,$dir.$filename); 
    $plot->DrawGraph();
}








