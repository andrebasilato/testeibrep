<?php

ini_set('memory_limit', '256M');
set_time_limit(0);

$dataAtual = new DateTime();

$_GET['ano'] = $dataAtual->format('Y');
$_GET['mes'] = $dataAtual->format('m');

$dataEnvio = $dataAtual->format('Y_m_d');

$diaSemana = date('w');

$camposDiasDaSemanaAdmRecebeEmail = [
    'receber_email_relatorio_gerencial_domingo',
    'receber_email_relatorio_gerencial_segunda',
    'receber_email_relatorio_gerencial_terca',
    'receber_email_relatorio_gerencial_quarta',
    'receber_email_relatorio_gerencial_quinta',
    'receber_email_relatorio_gerencial_sexta',
    'receber_email_relatorio_gerencial_sabado'
];

require $caminhoApp . '/app/gestor/modulos/relatorios/vendas_faturamento_novo/config.php';
require $caminhoApp . '/app/gestor/modulos/relatorios/vendas_faturamento_novo/classe.class.php';
$relatorioObj = new Relatorio();

$relatorioObj->set('url', 'cron');
$relatorioObj->set('pagina', 1)
    ->set('ordem', 'asc')
    ->set('limite', -1)
    ->set('ordem_campo', 'p.nome')
    ->set('modulo', $url[0])
    ->set('campos', 'p.*, e.nome as estado, c.nome as cidade');
$dadosArray = $relatorioObj->gerarRelatorio();

require $caminhoApp . '/app/classes/phplot/phplot.php';
require $caminhoApp . '/app/gestor/modulos/relatorios/vendas_faturamento_novo/telas/' . $config['tela_padrao'] . '/graficos_faturamento.php';

gerarGraficoEstadosMatriculas($dadosArray);
gerarRelatorioEstatosFaturamento($dadosArray);
gerarGraficoMatriculasMetas($dadosArray);
gerarGraficoFaturamentoMetas($dadosArray);
gerarGraficoAcumuladoMatriculas($dadosArray);
gerarGraficoAcumuladoFaturamento($dadosArray);
gerarGraficoMatriculasRelacaoAMeta($dadosArray);
gerarGraficoFaturamentoRelacaoAMeta($dadosArray);

require $caminhoApp . '/app/assets/plugins/MPDF54/mpdf.php';

$marginLeft = $marginRight = $marginHeader = $marginFooter = 1;
$mpdf = new mPDF('P', 'A4', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');

ob_start();

$url_logo_pequena = $caminhoApp . '/app/assets/img/logo_pequena.png';

require $caminhoApp . '/app/gestor/modulos/relatorios/vendas_faturamento_novo/idiomas/pt_br/html.php';
require $caminhoApp . '/app/gestor/modulos/relatorios/vendas_faturamento_novo/telas/desktop/relatorio_gerencial_pdf.php';

$saida = ob_get_contents();
ob_end_clean();

$pastaRelatorios = $caminhoApp . '/app/storage/relatorios_gerenciais';

if (! is_dir($pastaRelatorios)) {
    mkdir($pastaRelatorios, 0777);
}

$arquivo_nome = $pastaRelatorios . '/relatorio_gerencial_' . $dataEnvio . '.pdf';

$mpdf->simpleTables = true;
$mpdf->packTableData = true;
set_time_limit(120);
$mpdf->WriteHTML($saida);

$mpdf->Output($arquivo_nome);

$coreObj->anexoEmail = $arquivo_nome;

$queryModel = 'SELECT
            ua.idusuario,
            ua.nome,
            ua.email,
            ua.idexcecao
        FROM
            usuarios_adm ua
        WHERE
            ua.ativo = "S" AND
            gestor_sindicato = "%s" AND
            ua.receber_email = "S" AND
            ' . $camposDiasDaSemanaAdmRecebeEmail[$diaSemana] . ' = "S" ';

$query = sprintf($queryModel, 'S');
$resultado = $coreObj->executaSql($query);
while ($usuarioGestor = mysql_fetch_assoc($resultado)) {
    $nomePara = utf8_decode($usuarioGestor['nome']);
    $emailPara = utf8_decode($usuarioGestor['email']);
    $nomeDe = utf8_decode($GLOBALS['config']['tituloSistema'] . ' - ' . $GLOBALS['config']['tituloEmpresa']);
    $emailDe = $GLOBALS['config']['emailSistema'];
    $assunto = utf8_decode('Relatório Gerencial de Vendas e Faturamento');
    $mensagem = 'Ol&aacute;, <strong>' . $usuarioGestor['nome'] . '</strong>.<br><br>Segue anexo do <strong>Relat&oacute;rio Gerencial de Vendas e Faturamento</strong> gerado na data <strong>' . $dataAtual->format('d/m/Y') . '</strong>.';

    $coreObj->enviarEmail($nomeDe, $emailDe, $assunto, utf8_decode($mensagem), $nomePara, $emailPara, $layout = 'layout');
}

$query = sprintf($queryModel, 'N');
$resultado = $coreObj->executaSql($query);
$raiz = $caminhoApp . '/app/storage/relatorios_gerenciais';
apagar_recursividade($raiz, $raiz);
while ($usuario = mysql_fetch_assoc($resultado)) {
    $sql = "SELECT
                i.idsindicato
            FROM
                usuarios_adm_sindicatos uai, sindicatos i
            WHERE
                uai.idusuario='" . $usuario['idusuario'] . "' AND
                uai.ativo='S' AND
                uai.idsindicato=i.idsindicato AND
                i.ativo='S' ";
    $querySindicatos = $coreObj->executaSql($sql);

    $intituicoes = [];
    while ($sindicato = mysql_fetch_assoc($querySindicatos)) {
        $intituicoes[$sindicato['idsindicato']] = $sindicato['idsindicato'];
    }
    if (! $intituicoes) {
        continue;
    }
    $intituicoes = implode(',', $intituicoes);

    $_GET['adm_sindicatos'] = $intituicoes;

    if ($usuario['idexcecao']) {
        $sql = 'SELECT * FROM excecoes WHERE idexcecao = "' . $usuario['idexcecao'] . '" AND ativo = "S"';
        $excecao = mysql_fetch_assoc($coreObj->executaSql($sql));

        if ($excecao['logo_pequena_servidor']) {
            $url_logo_pequena = $excecao['logo_pequena_servidor'];
        }
    }

    //Gerar relatórios individuais
    $relatorioObj->Set('idusuario', $usuario['idusuario']);
    $dadosArray = $relatorioObj->gerarRelatorio();

    require_once $caminhoApp . '/app/classes/phplot/phplot.php';

    require_once $caminhoApp . '/app/gestor/modulos/relatorios/vendas_faturamento_novo/telas/' . $config['tela_padrao'] . '/graficos_faturamento.php';
    gerarGraficoEstadosMatriculas($dadosArray);
    gerarRelatorioEstatosFaturamento($dadosArray);
    gerarGraficoMatriculasMetas($dadosArray);
    gerarGraficoFaturamentoMetas($dadosArray);
    gerarGraficoAcumuladoMatriculas($dadosArray);
    gerarGraficoAcumuladoFaturamento($dadosArray);
    gerarGraficoMatriculasRelacaoAMeta($dadosArray);
    gerarGraficoFaturamentoRelacaoAMeta($dadosArray);

    $marginLeft = $marginRight = $marginHeader = $marginFooter = 1;
    $mpdf = new mPDF('P', 'A4', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');

    ob_start();

    require $caminhoApp . '/app/gestor/modulos/relatorios/vendas_faturamento_novo/idiomas/' . $config['idioma_padrao'] . '/html.php';
    require $caminhoApp . '/app/gestor/modulos/relatorios/vendas_faturamento_novo/telas/desktop/relatorio_gerencial_pdf.php';

    $saida = ob_get_contents();
    ob_end_clean();

    $arquivo_nome = $pastaRelatorios . '/relatorio_gerencial_' . $dataEnvio . '_' . $usuario['idusuario'] . '.pdf';

    $mpdf->simpleTables = true;
    $mpdf->packTableData = true;
    set_time_limit(120);
    $mpdf->WriteHTML($saida);
    $mpdf->Output($arquivo_nome);

    $nomePara = utf8_decode($usuario['nome']);
    $emailPara = utf8_decode($usuario['email']);
    $nomeDe = utf8_decode($GLOBALS['config']['tituloSistema'] . ' - ' . $GLOBALS['config']['tituloEmpresa']);
    $emailDe = $GLOBALS['config']['emailSistema'];
    $assunto = utf8_decode('Relatório Gerencial de Vendas e Faturamento');
    $mensagem = 'Ol&aacute;, <strong>' . $usuario['nome'] . '</strong>.<br><br>Segue anexo do <strong>Relat&oacute;rio Gerencial de Vendas e Faturamento</strong> gerado na data <strong>' . $dataAtual->format('d/m/Y') . '</strong>.';

    $coreObj->anexoEmail = $arquivo_nome;
    $coreObj->enviarEmail($nomeDe, $emailDe, $assunto, utf8_decode($mensagem), $nomePara, $emailPara, $layout = 'layout');
}
