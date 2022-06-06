<?php

require_once '../classes/avaliacoes.class.php';
require_once '../classes/formulasnotas.class.php';
require_once 'config.php';
require_once 'config.formulario.php';
require_once 'config.listagem.php';

require_once '../classes/matriculas.class.php';
//Incluimos o arquivo com variaveis padrão do sistema.
require_once 'idiomas/'.$config['idioma_padrao'].'/idiomapadrao.php';

$solicitacaoDeclaracaoObj = new SolicitacoesDeclaracoes();
$matriculaObj = new Matriculas();

$matriculaObj->set('idvendedor', $usu_vendedor['idvendedor'])
    ->set('monitora_onde', $config['monitoramento']['onde'])
    ->set('modulo', $url[0]);

if (isset($url[5])) {
    if ($url[4] == 'ajax_cidades') {
        if ($_REQUEST['idestado']) {
            $matriculaObj->RetornarJSON('cidades', mysql_real_escape_string($_REQUEST['idestado']), 'idestado', 'idcidade, nome', 'ORDER BY nome');
        } else {
            $matriculaObj->RetornarJSON('cidades', $url[5], 'idestado', 'idcidade, nome', 'ORDER BY nome');
        }
        exit;
    } elseif ($url[4] == 'ajax_cidades_curso_anterior') {
        if ($_REQUEST['curso_anterior_idestado']) {
            $matriculaObj->RetornarJSON('cidades', mysql_real_escape_string($_REQUEST['curso_anterior_idestado']), 'idestado', 'idcidade, nome', 'ORDER BY nome');
        } else {
            $matriculaObj->RetornarJSON('cidades', $url[5], 'idestado', 'idcidade, nome', 'ORDER BY nome');
        }
        exit;
    } elseif ($url[4] == 'ajax_cidades_ensino_medio') {
        if ($_REQUEST['idestado_ensino_medio']) {
            $matriculaObj->RetornarJSON('cidades', mysql_real_escape_string($_REQUEST['idestado_ensino_medio']), 'idestado', 'idcidade, nome', 'ORDER BY nome');
        } else {
            $matriculaObj->RetornarJSON('cidades', $url[6], 'idestado', 'idcidade, nome', 'ORDER BY nome');
        }
        exit;
    }
}

if (isset($url[3])) {
    if ('novamatricula' == $url[3]) {
        include 'novamatricula.index.php';
        exit();
    } elseif ($url[3] == 'json') {
        include 'telas/'.$config['tela_padrao'].'/json.php';
        exit();
    } else {
        $matriculaObj->Set('id', intval($url[3]));
        $matricula = $matriculaObj->Retornar();

        if ($matricula['idmatricula']) {
            $se_historico__ = $matriculaObj->se_historico();
            $matricula['historico'] = $se_historico__;

            $matricula['situacao'] = $matriculaObj->RetornarSituacao($matricula['idsituacao']);

            switch ($url[4]) {
                case 'gerar_historico':
                    include 'telas/'.$config['tela_padrao'].'/administrar.menu.gerar.historico.php';
                    exit;
                    break;
                case 'administrar':
                    include 'administrar.php';
                    break;
                case 'dossie':

                    $matricula['situacao'] = $matriculaObj->RetornarSituacao($matricula['idsituacao']);
                    $matricula['oferta'] = $matriculaObj->RetornarOferta();
                    $matricula['curso'] = $matriculaObj->RetornarCurso();
                    $matricula['escola'] = $matriculaObj->RetornarEscola();
                    $matricula['turma'] = $matriculaObj->RetornarTurma();
                    $matricula['sindicato'] = $matriculaObj->RetornarSindicato();
                    $matricula['vendedor'] = $matriculaObj->RetornarVendedor();
                    //$matricula['associados'] = $matriculaObj->RetornarAssociados();
                    $matricula['pessoa'] = $matriculaObj->RetornarPessoa();
                    $matricula['documentos'] = $matriculaObj->RetornarDocumentos();
                    $matricula['contas'] = $matriculaObj->RetornarContas();
                    $matricula['curriculo'] = $matriculaObj->RetornarCurriculo();
                    $matricula['disciplinas'] = $matriculaObj->RetornarDisciplinas($matricula['curriculo']['media']);
                    $matricula['solicitacoes'] = $matriculaObj->RetornarProvasSolicitadas();

                    $situacaoRenegociadaConta = $matriculaObj->retornarSituacaoRenegociadaConta();
                    $situacaoCanceladaConta = $matriculaObj->retornarSituacaoCanceladaConta();
                    $situacaoTransferidaConta = $matriculaObj->retornarSituacaoTransferidaConta();

                    $matricula['documentos_pendentes'] = $matriculaObj->retornarDocumentosPendentes($matricula['idmatricula'], $matricula['escola']['idsindicato'], $matricula['curso']['idcurso']);

                    $matriculaObj->Set('idpessoa', $matricula['idpessoa']);
                    $matriculaObj->Set("idmatricula", $matricula["idmatricula"]);
                    $matricula['andamento'] = $matriculaObj->retornarAndamento();

                    $contribuicao = $matriculaObj->retornarContribuicao($matricula['idmatricula'], $matricula['idpessoa'], $matricula['disciplinas'][0]['idava']);
                    $porcentagem = $matriculaObj->retornarPorcentagem($matricula['idmatricula']);

                    include '../assets/plugins/MPDF54/mpdf.php';

                    $mpdf = new mPDF('c', 'A4');

                    ob_start();

                    include 'idiomas/'.$config['idioma_padrao'].'/dossie.php';
                    include 'telas/'.$config['tela_padrao'].'/dossie.php';

                    $saida = ob_get_contents();
                    ob_end_clean();

                    $arquivo_nome = '../storage/temp/dossie_'.$url[3].'.pdf';

                    $mpdf->simpleTables = true;
                    $mpdf->packTableData = true;
                    set_time_limit(0);

                    $mpdf->use_kwt = true;
                    $css = '.quebra_pagina {page-break-after:always;}';

                    $mpdf->WriteHTML($css, 1);
                    $mpdf->WriteHTML($saida);

                    $mpdf->Output($arquivo_nome, 'F');

                    header('Content-type: '.filetype($arquivo_nome));
                    header('Content-Disposition: attachment; filename="'.basename($arquivo_nome).'"');
                    header('Content-Length: '.filesize($arquivo_nome));
                    header('Expires: 0');
                    header('Pragma: no-cache');
                    readfile($arquivo_nome);

                    exit;

                    break;
                case 'historico_escolar':

                    $sindicatoCurso = $matriculaObj->RetornarCursoSindicato();

                    if (is_numeric($sindicatoCurso['idhistorico_escolar'])) {
                        $historico = new Historicos();
                        $resultado = $historico->gerarCertificado((int) $url[3], $matriculaObj);
                        $historico->Set('idmatricula', (int) $url[3]);
                        $historico->downloadPages($resultado);
                        exit;
                    }

                    $boletim = new Boletim(new Avaliacoes());
                    $boletim['idmatricula'] = (int) $url[3];
                    $boletim->buscarDadosDaMatriculaHistorico();

                    $matriculas->groupby = 'idmensagem';
                    $messageCollection = $matriculaObj->listarMensagensParaCertificado($matricula['idmatricula']);
                    $mensagens = '';
                    foreach ($messageCollection as $message) {
                        $mensagens .= $message['mensagem'].nl2br(PHP_EOL);
                    }

                    $formula = new Formulas_Notas();
                    $controls = false;

                    include '../assets/plugins/MPDF54/mpdf.php';

                    $marginLeft = $marginRight = $marginHeader = $marginFooter = 1;
                    $mpdf = new mPDF('c', 'A4', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');

                    ob_start();

                    include 'idiomas/'.$config['idioma_padrao'].'/historico_escolar.php';
                    include 'telas/'.$config['tela_padrao'].'/historico_escolar.php';

                    $saida = ob_get_contents();
                    ob_end_clean();

                    $arquivo_nome = '../storage/temp/historico_escolar_'.$url[3].'.pdf';

                    $mpdf->simpleTables = true;
                    $mpdf->packTableData = true;
                    set_time_limit(120);
                    $mpdf->WriteHTML($saida);

                    $mpdf->Output($arquivo_nome, 'F');

                    header('Content-type: '.filetype($arquivo_nome));
                    header('Content-Disposition: attachment; filename="'.basename($arquivo_nome).'"');
                    header('Content-Length: '.filesize($arquivo_nome));
                    header('Expires: 0');
                    header('Pragma: no-cache');
                    readfile($arquivo_nome);

                    exit;
                    break;

                case 'download':
                    $arquivo = $matriculaObj->retornarMensagensArquivo($url[5]);

                    include 'telas/'.$config['tela_padrao'].'/mensagem.download.php';
                    break;
                default:
                    header('Location: /'.$url[0].'/'.$url[1].'/'.$url[2]);
                    exit;
            }
        } else {
            header('Location: /'.$url[0].'/'.$url[1].'/'.$url[2]);
            exit;
        }
    }
} else {
    $matriculaObj->Set('pagina', $_GET['pag']);
    if (!$_GET['ordem']) {
        $_GET['ordem'] = 'desc';
    }
    $matriculaObj->Set('ordem', $_GET['ord']);
    if (!$_GET['qtd']) {
        $_GET['qtd'] = 30;
    }
    $matriculaObj->Set('limite', intval($_GET['qtd']));
    if (!$_GET['cmp']) {
        $_GET['cmp'] = $config['banco']['primaria'];
    }
    $matriculaObj->Set('ordem_campo', $_GET['cmp']);
    $matriculaObj->Set('incluirPessoas', true);
    $matriculaObj->Set('incluirWorkflow', true);
    $matriculaObj->Set('incluirOfertas', true);
    $matriculaObj->Set('incluirVendedores', true);
    $matriculaObj->Set('campos', 'm.*, cw.nome as situacao, cw.cor_bg as situacao_cor_bg, 
    cw.cor_nome as situacao_cor_nome, p.nome as aluno, p.documento, p.email, o.nome as oferta, ot.nome as turma, 
    v.nome as vendedor ');
    $dadosArray = $matriculaObj->ListarTodas();
    include 'idiomas/'.$config['idioma_padrao'].'/index.php';
    include 'telas/'.$config['tela_padrao'].'/index.php';
}
