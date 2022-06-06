<?php
require '../classes/matriculas_novo.class.php';
require '../classes/bannersavaaluno.class.php';

$matriculaObj = new Matriculas;
$matriculas = $matriculaObj->set('idpessoa', $usuario['idpessoa'])
                        ->set('modulo', $url[0])
                        ->set('limite', -1)
                        ->set('ordem_campo', 'c.ordem ASC, m.idmatricula')
                        ->set('ordem', 'desc')
                        ->set('campos', 'm.*,
                                        c.codigo as codigo_curso,
                                        c.nome as curso,
                                        c.carga_horaria_total,
                                        c.imagem_exibicao_servidor,
                                        c.acesso_simultaneo,
                                        i.acesso_ava,
                                        IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) AS porcentagem,
                                        IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) AS porc_aluno')
                        ->retornarMeusCursos();

$acessoCursoNaoSimultaneo = $matriculaObj->retornarAcessoCursoNaoSimultaneo($usuario['idpessoa']);
$situacaoAtiva = $matriculaObj->retornarSituacaoAtiva();
if (isset($url[3])) {
    switch ($url[4]) {
        case 'boletim':
            $idmatricula = (int) $url[3];

            /** @var Turmas $turma */
            $turma = new Turmas();

            /** @var Sindicatos $sindicato */
            $sindicato = new Sindicatos();

            /** @var Boletim $boletim */
            $boletim = new Boletim(new Avaliacoes);

            $boletim['idmatricula'] = $idmatricula;
            $boletim->buscarDadosDaMatriculaHistorico();

            $boletim['matriculas'] = $matriculaObj->getMatricula($idmatricula);

            //Se a sindicato da matrícula não tiver acesso ao AVA liberado será redirecionado para a página incial
            if ($boletim['matriculas']['acesso_ava'] == 'N') {
                header('Location: /'.$url[0].'/secretaria/meuscursos');
                exit;
            }

            $boletim['turma'] = $turma->getTurma($boletim['matriculas']['idturma']);

            $boletim['sindicato'] = $sindicato->getSindicato($boletim['matriculas']['idsindicato']);

            // Define constante para o logotipo
            if ($urlLogo = $boletim['sindicato']['logo_servidor']) {
                define('URL_LOGO_PEGUENA', '/api/get/imagens/sindicatos_logo/x/95/'.$urlLogo);
            }

            defined('URL_LOGO_PEGUENA') or define("URL_LOGO_PEGUENA", "/assets/img/logo_pequena.png");

            $formula = new Formulas_Notas;
            $controls = true;

            $data = new DateTime('now');

            require '../assets/plugins/MPDF54/mpdf.php';

            $mpdf = new mPDF('c', 'A4');

            ob_start();

            require 'idiomas/'.$config['idioma_padrao'].'/boletim.php';
            require 'telas/'.$config['tela_padrao'].'/boletim.php';

            $saida = ob_get_contents();
            ob_end_clean();

            $arquivo_nome = "../storage/temp/boletim_".$idmatricula.".pdf";

            $mpdf->simpleTables = true;
            $mpdf->packTableData = true;
            set_time_limit(120);
            $mpdf->WriteHTML($saida);

            $mpdf->Output($arquivo_nome, "F");

            header("Content-type: " . filetype($arquivo_nome));
            header('Content-Disposition: attachment; filename="'.basename($arquivo_nome).'"');
            header('Content-Length: '.filesize($arquivo_nome));
            header('Expires: 0');
            header('Pragma: no-cache');
            readfile($arquivo_nome);
            exit;
            break;
        case 'diploma':
            $certificado = new Certificados;
            $paginas = $certificado->set('idpessoa', $usuario['idpessoa'])->gerarCertificado((int) $url[3], new Matriculas, (int) $url[5]);
            $certificado->downloadPages($paginas);
            break;
        case 'historico':
                    $matricula = $matriculaObj->set('id', (int) $url[3])
                                                            ->set('modulo', $url[0])
                                                            ->set('idpessoa', $usuario['idpessoa'])
                                                            ->Retornar();

                    $matriculaObj->set('matricula',$matricula);

                    $sindicatoCurso = $matriculaObj->RetornarCursoSindicato();

                    $_POST['data_historico'] = date("d/m/Y");
                    if(is_numeric($sindicatoCurso['idhistorico_escolar'])){
                        $historico = new Historicos();
                        $resultado = $historico->gerarCertificado((int)$url[3], $matriculaObj);
                        $historico->Set("idmatricula",(int)$url[3]);
                        $historico->downloadPages($resultado);
                        exit;
                    }

                    $boletim = new Boletim(new Avaliacoes);
                    $boletim['idmatricula'] = (int)$url[3];
                    $boletim->buscarDadosDaMatriculaHistorico();

                    $matriculas->groupby = 'idmensagem';
                    $messageCollection = $matriculaObj->listarMensagensParaCertificado($matricula["idmatricula"]);
                    $mensagens = '';
                    foreach ($messageCollection as $message) {
                            $mensagens .= $message['mensagem'] . nl2br(PHP_EOL);
                    }

                    $formula = new Formulas_Notas;
                    $controls = false;

                    include("../assets/plugins/MPDF54/mpdf.php");

                    $marginLeft = $marginRight = $marginHeader = $marginFooter = 1;
                    $mpdf = new mPDF('c', 'A4', '', '', $marginLeft, $marginRight, $marginHeader, $marginFooter, 15, 15, '');

                    ob_start();

                    include 'idiomas/' . $config['idioma_padrao'] . '/historico_escolar.php';
                    include 'telas/' . $config['tela_padrao'] . '/historico_escolar.php';

                    $saida = ob_get_contents();
                    ob_end_clean();

                    $arquivo_nome = "../storage/temp/historico_escolar_".$url[3].".pdf";

                    $mpdf->simpleTables = true;
                    $mpdf->packTableData = true;
                    set_time_limit(120);
                    $mpdf->WriteHTML($saida);

                    $mpdf->Output($arquivo_nome, "F");

                    header("Content-type: " . filetype($arquivo_nome));
                    header('Content-Disposition: attachment; filename="' . basename($arquivo_nome) . '"');
                    header('Content-Length: ' . filesize($arquivo_nome));
                    header('Expires: 0');
                    header('Pragma: no-cache');
                    readfile($arquivo_nome);
                    exit;
                    break;
    }
} else {
    $bannerObj = new Banners_Ava_Aluno;
    $bannerObj->set('idpessoa', $usuario['idpessoa']);
    $banners = $bannerObj->retornarBannersAluno();

    $situacaoConcluido = $matriculaObj->retornarSituacaoConcluido();
    $situacaoInicial = $matriculaObj->retornarSituacaoInicial();

    require 'idiomas/'.$config['idioma_padrao'].'/index.php';
    require 'telas/'.$config['tela_padrao'].'/index.php';
}
