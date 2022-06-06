<?php
/**
 * `Certificados`
 *
 * @author     Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 *
 * @package    Oráculo Construtor
 * @copyright  Copyright (c) 2014 Alfama Web (http://alfamaweb.com.br)
 * @license    Proprietary AlfamaWeb
 * @version    $Id$
 */
class Certificados extends Core
{

    /* Tabelas usadas */
    const PRIMARY_KEY = 'idcertificado';
    const CURRENT_TABLE = 'certificados';
    const POLOS_TABLE = 'certificados_escolas';
    const PAGES_TABLE = 'certificados_paginas';
    const MIDIAS_TABLE = 'certificados_midias';

    /**
     * @var array
     */
    private $_storage = array();

    /**
     * Cadastra certificado.
     *
     * @return void|boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function cadastrar()
    {
        return $this->salvarDados();
    }

    /**
     * Força download do conteúdo do array $pages.
     * Cada elemento será imprimido em uma folha
     *
     * @author Jefersson Nathan
     */
    public function downloadPages(array $pages)
    {
        include '../assets/plugins/MPDF54/mpdf.php';

        $mpdf = new mPDF('c', 'A4-L', '', '', null, null, null, null, null, null, '');
        $mpdf->ignore_invalid_utf8 = true;
        $mpdf->simpleTables = true;
        set_time_limit(0);

        $css = ".quebra_pagina {page-break-after:always;}";

        $mpdf->defaultfooterline = 0;
        $mpdf->WriteHTML($css, 1);

        $i = 1;
        $number = count($pages);
        foreach ($pages as $page) {
            $mpdf->WriteHTML($css, 1);
            $mpdf->WriteHTML('<div class="quebra_pagina" style="page-break-after:always"></div>'.$page);

            if ($i != $number) {
                $mpdf->AddPage();
                $i++;
            }
        }

        // background
        $mpdf->defaultfooterline = 0;

        $arquivo_nome = "../storage/temp/1_preview.pdf";
        $mpdf->Output($arquivo_nome, "F");

        header('Content-type: application/pdf');
        readfile($arquivo_nome);
        exit;
    }

    public function atualizaFolhaRegistroOfertaCurso($idOfertaCurso, $idFolha)
    {
        if (! is_numeric($idOfertaCurso) || ! is_numeric($idFolha)) {
            return false;
        }

        $retorno = [];

        $sqlOfertasCursos = 'SELECT idfolha FROM ofertas_cursos WHERE idoferta_curso = ' . $idOfertaCurso;
        $linhaAntiga = $this->retornarLinha($sqlOfertasCursos);

        $sql = 'UPDATE
                ofertas_cursos
            SET
                idfolha = ' . $idFolha . '
            WHERE
                idoferta_curso = ' . $idOfertaCurso;
        $salvar = $this->executaSql($sql);

        $linhaNova = $this->retornarLinha($sqlOfertasCursos);

        if ($salvar) {
            $retorno['sucesso'] = true;

            $this->monitora_qual = $idOfertaCurso;
            $this->monitora_oque = 2;
            $this->monitora_onde = 11;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->monitora();
        } else {
            $retorno['erro'] = true;
            $retorno['erros'][] = $this->sql;
            $retorno['erros'][] = mysql_error();
        }

        return $retorno;
    }

    public function retornaDataEmCurso($idmatricula){
        $sql = "SELECT
                    mh.data_cad
                FROM
                    matriculas_historicos mh
                INNER JOIN matriculas_workflow mw ON (mh.para = mw.idsituacao)
                WHERE
                    mh.idmatricula = ".$idmatricula." AND
                    mh.tipo = 'situacao' AND
                    mw.ativa = 'S'
                ORDER BY mh.data_cad DESC";
        return $this->retornarLinha($sql);
    }

    public function ultimaNotaProva($idmatricula){
        $sql = "SELECT
                    ma.idprova,
                    ma.nota,
                    ma.inicio
                FROM
                    matriculas_avaliacoes ma
                WHERE
                    ma.idmatricula = ".$idmatricula." AND
                    ma.ativo = 'S'
                ORDER BY ma.data_correcao DESC";
        return $this->retornarLinha($sql);
    }

    /**
     *
     * gerar o certificado com as informações do aluno
     *
     * @param $idmatricula
     * @param Matriculas $matriculas
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */

    public function gerarCertificado($idmatricula, Matriculas $matriculas, $idFolhaRegistro = NULL)
    {

        $folhaObj = new Folhas_Registros_Diplomas();
        $currentTime = new DateTime('now');
        $boletim = new Boletim(new Avaliacoes);
        $avaliacaoObj = new Avaliacoes();
        $curso = new Cursos;

        $idmatricula = (int)$idmatricula;

        $this->retornarDadosParaGerarCertificado($matriculas, $idmatricula, $information, $certificadoCurso, $paginas, $dataInicioCurso, $ultimaNotaProva, $gabaritoProva);

        if ($folhaObj->folhaAtiva($information['oferta_curso']['idfolha']) && $matriculas->verificaMatriculaAprovadaNotas($information['oferta_curso']['porcentagem_minima_disciplinas'])) {

            $this->verificaParaAtualizarSituacao($information, $matriculas, $idmatricula);
            $this->verificaParaAssociarDiploma($folhaObj, $information, $idmatricula, $folhaRegistro);

        }

        /** Mensagens da matrícula */
        $matriculas->groupby = 'idmensagem';
        $messageCollection = $matriculas->listarMensagensParaCertificado($idmatricula);
        $mensagens = '';
        foreach ($messageCollection as $message) {
            $mensagens .= $message['mensagem'] . nl2br(PHP_EOL);
        }

        // Se estiver na página de folhas de registro do diploma traz informações da folha atual
        if ('folhasregistrosdiplomas' == Request::url(3)) {
            $idFolhaRegistro = Request::url(4);
        }
        $folhas = $folhaObj->getRowByIdFolha($idFolhaRegistro);
        $folhaDiplomaMatricula = $folhaObj->retornarMatriculasDaFolha($idFolhaRegistro, $idmatricula);

        $boletim['idmatricula'] = $idmatricula;
        $boletim->buscarDadosDaMatriculaHistorico($information['oferta']['idoferta']);

        $matriculaArray['idoferta'] = $information['oferta']['idoferta'];
        $matriculaArray['idcurso'] = $information['curso']['idcurso'];
        $matriculaArray['idescola'] = $information['escola']['idescola'];
        $matriculas->set('matricula', $matriculaArray);

        /*Retorna os dados do currículo da matrícula*/
        $matriculaCurriculo = $matriculas->RetornarCurriculo();
        $cargaHorariaTotal = 0;

        /* Variáveis nota_prova */
        $data_primeiro_acesso = $dataInicioCurso['data_cad'];
        if (!empty($information['matricula']['data_primeiro_acesso'])) {
            $data_primeiro_acesso = $information['matricula']['data_primeiro_acesso'];
        }

        $data_em_curso = '';
        if (!empty($information['historico'])) {
            foreach ($information['historico'] as $historico) {
                if ($historico['tipo'] == 'situacao' && $historico['para'] == $information['id_em_curso']['idsituacao']) {
                    $data_em_curso = $historico['data_cad'];
                    break;
                }
            }
        }

        $data_final_certificado = $folhaDiplomaMatricula['data_cad'];
        if (!empty($information['matricula']['data_final_certificado'])) {
            $data_final_certificado = $information['matricula']['data_final_certificado'];
        }

        $qrcodeInfo = [
            htmlspecialchars($information['curso']['nome']),
            $information['matricula']['idmatricula'],
            htmlspecialchars($information['pessoa']['nome']),
            $information['pessoa']['documento'],
        ];

        $qrcodePath = $this->gerarQRCode($qrcodeInfo);

        /* Estruturando dados do aluno */
        foreach ($information['pessoa'] as $tag => $valor) {
            if ($tag == 'escolaridade') {
                $valor = $GLOBALS['escolaridade'][$GLOBALS['config']['idioma_padrao']][$valor];
            }
            $subject["[[aluno][" . $tag . "]]"] = $valor;
        }

        foreach ($information as $key => $value) {
            if (is_array($value) && $key != 'pessoa') {
                foreach ($value as $tag => $valor) {
                    if (!is_array($valor)) {
                        $subject["[[" . $key . "][" . $tag . "]]"] = $valor;
                    }
                }
            }
        }

        $this->prepararDados($_tmp, $subject, $information);

        /** Pegar áreas do curso ``Separadas por vírgula`` */
        $areas = $curso->set('id', $subject['[[matricula][idcurso]]'])
            ->set('campos', 'nome')
            ->listarAreasAssociadas();

        foreach ($areas as $area) {
            $_area[] = $area['nome'];
        }

        $subject['[[curso][area]]'] = implode(', ', $_area);

        $this->montarHTML($ultimaNotaProva, $avaliacaoObj, $subject, $information, $boletim, $matriculas, $idmatricula, $matriculaArray, $matriculaCurriculo, $cargaHorariaTotal, $folhaDiplomaMatricula, $data_final_certificado, $data_primeiro_acesso, $data_em_curso, $currentTime, $folhas['numero_folha'], $qrcodePath, $certificadoCurso, $mensagens, $gabaritoProva);

        $patterns = array(
            '#\[\[midia\]\[(\d+)\]\]#i' => 'SELECT * FROM certificados_midias WHERE idcertificado_midia = %d AND idcertificado = ' . $certificadoCurso['idcertificado']
        );

        /* diretório com os modelos dos certificados */
        $foundFilesOn = realpath(
            dirname(__FILE__)
            . '/../storage/certificados/' . $certificadoCurso['idcertificado']
        );

        foreach ($paginas as $pagina) {
            $subject['[[extra][ordem_pagina_certificado]]'] = str_pad($folhaDiplomaMatricula['numero_ordem'], 9, "0", STR_PAD_LEFT);
            $_tmp_file_location = $foundFilesOn . '/' . $pagina['arquivo_servidor'];

            if (file_exists($_tmp_file_location)) {
                $content = file_get_contents($_tmp_file_location);

                // Aplica as primeiras modificações com replace
                foreach ($subject as $search => $replace) {
                    $content = str_ireplace($search, $replace, $content);
                }

                // Faz busca e replace apartir de regex
                foreach ($patterns as $pattern => $query) {
                    preg_match_all($pattern, $content, $match);
                    $midiasCollection = array_combine($match[1], $match[0]);

                    foreach ($midiasCollection as $id => $tagToReplace) {
                        $_query = mysql_fetch_object(mysql_query(sprintf($query, $id)));
                        if ($_query) {
                            $content = str_ireplace($tagToReplace, $_SERVER['DOCUMENT_ROOT'] . '/storage/certificados_midias/' . $_query->idcertificado . '/' . $_query->arquivo_servidor, $content);
                        } else {
                            $content = str_ireplace($tagToReplace, $_SERVER['DOCUMENT_ROOT'] . '/assets/img/__semimagem_api.jpg', $content);
                        }
                    }

                }

                $this->_storage($content);
            }
        }
        return $this->_storage;
    }

    /**
     * @param array $information
     * @param Matriculas $matriculas
     * @param $idmatricula
     */
    private function verificaParaAtualizarSituacao(array $information, Matriculas $matriculas, $idmatricula)
    {
        /** Validação será alterada para o específico */
        /* Verifica se o curso é o de Reciclagem de Condutores e Infratores e se o estado do CFC é o de maranhão.
        Essa verificação ocorre para não mudar a situação quando clicar para gerar o diploma, pois é a cron do detran de MA que deve alterar para concluido. */
        if ($information['escola']['idestado'] != 10 || $information['curso']['codigo'] != 'REC') {
            $situacaoAtualMatricula = $matriculas->situacaoAtualMatricula($idmatricula);


            /**Rotina comentada para que a única forma de conclusão da matrícula seja manual ou pela cron de homologar certificado */
            //Verifica se a situação atual da tem relação com a situação concluído em matrículas workflow.
//            $workFlowMatriculas = $matriculas->workFlowMatriculasRelacionadasComSituacaoConcluido();
//            foreach ($workFlowMatriculas as $workFlowMatricula) {
//                if ($workFlowMatricula['idsituacao_de'] === $situacaoAtualMatricula['idsituacao']) {
//                    $matriculas->atualizarSituacaoConcluido($idmatricula);
//                    break;
//                }
//            }
        }
    }

    /**
     * @param Folhas_Registros_Diplomas $folhaObj
     * @param $information
     * @param $idmatricula
     * @param $folhaRegistro
     */
    private function verificaParaAssociarDiploma(Folhas_Registros_Diplomas $folhaObj, &$information, $idmatricula, &$folhaRegistro)
    {

        $alunoJaPossuiFolha = $folhaObj->retornarMatriculasDaFolha($information['oferta_curso']['idfolha'], $idmatricula);

        if (!$alunoJaPossuiFolha) {
            $folhaRegistro = $folhaObj->set('id', $information['oferta_curso']['idfolha'])
                ->set('campos', 'frd.limite_matriculas')
                ->retornar();
            $qntMatriculas = $folhaObj->retornarTotalMatriculasDaFolha($information['oferta_curso']['idfolha']);

            //Verifica se há espaço na folha de registro vinculada a oferta curso para novas matrículas, caso não haja clona a folha de registro.
            if (!empty($folhaRegistro['limite_matriculas']) && $qntMatriculas >= $folhaRegistro['limite_matriculas']) {
                $clonar = $folhaObj->clonar($information['oferta_curso']['idfolha']);

                if (!empty($clonar['sucesso'])) {
                    $this->atualizaFolhaRegistroOfertaCurso($information['oferta_curso']['idoferta_curso'], $clonar['id']
                    );

                    $information['oferta_curso']['idfolha'] = $clonar['id'];
                }
                /*
                else {
                    return $clonar;
                 }
                */
            }

            $folhaObj->associarDiploma($information['oferta_curso']['idfolha'], $idmatricula);
        }
    }

    /**
     * @param $_tmp
     * @param array $subject
     * @param $information
     */
    private function prepararDados($_tmp, array &$subject, $information)
    {
        /**
         * Prepara os dados
         *
         * - Converte data para o formato padrão do Brasil.
         * - Converte data para o formato de mês transcrito
         * - Converte data para o formato `todo` transcrito
         * -
         */

        /** Aluno */
        unset($_tmp);
        $_tmp = $subject['[[aluno][data_nasc]]'];
        $subject['[[aluno][data_nasc]]'] = FiltrosHelper::converterData($_tmp, 'd/m/Y');
        $subject['[[aluno][data_nasc_mes_extenso]]'] = FiltrosHelper::transcreverData($_tmp);
        $subject['[[aluno][documento]]'] = FiltrosHelper::formatarCpf($subject['[[aluno][documento]]']);
        $subject['[[aluno][pais]]'] = FiltrosHelper::nomeDoPais($subject['[[aluno][idpais]]']);
        /*
        $subject['[[aluno][curso_anterior_cidade]]'] = FiltrosHelper::nomeDaCidade($subject['[[aluno][curso_anterior_idcidade]]'])->nome;
        $subject['[[aluno][curso_anterior_estado]]'] = FiltrosHelper::nomeUfDoEstado($subject['[[aluno][curso_anterior_idestado]]'])->nome;
        */
        $subject['[[aluno][genero]]'] = $GLOBALS["sexo"]["pt_br"][$subject['[[aluno][sexo]]']];
        $subject['[[aluno][codigo]]'] = $subject['[[aluno][idpessoa]]'];
        $subject['[[aluno][nome_curso_anterior]]'] = $subject['[[aluno][curso_anterior]]'];

        /** Escola */
        $subject['[[escola][documento]]'] = FiltrosHelper::formatarCpf($subject['[[escola][documento]]']);

        /** Matrícula */
        unset($_tmp);
        if ($subject['[[matricula][data_conclusao]]'])
            $_tmp = $subject['[[matricula][data_conclusao]]'];
        else
            $_tmp = '0000-00-00';
        $subject['[[matricula][data_conclusao]]'] = FiltrosHelper::converterData($_tmp, 'd/m/Y');
        $subject['[[matricula][data_conclusao_mes_ano]]'] = FiltrosHelper::converterData($_tmp, 'm/Y');
        $subject['[[matricula][data_conclusao_mes_extenso]]'] = FiltrosHelper::transcreverData($_tmp);
        $subject['[[matricula][turma]]'] = $subject['[[turma][nome]]'];
        $subject['[[matricula][renach]]'] = $information['matricula']['renach'];
        $subject['[[matricula][validade_certificado]]'] = FiltrosHelper::somarAno($_tmp, 5);
        $subject['[[matricula][validade_certificado_mes_extenso]]'] = FiltrosHelper::transcreverData(FiltrosHelper::somarAno($_tmp, 5, 'Y-m-d'));


        /** Mantenedora */
        $subject['[[mantenedora][documento]]'] = FiltrosHelper::formatarCNPJ($subject['[[mantenedora][documento]]']);

        /** Sindicato */
        $subject['[[sindicato][secretario]]'] = FiltrosHelper::dadosUsuarioAdministrativo($subject['[[sindicato][idsecretario]]'])->nome;
        $subject['[[sindicato][secretario_rg]]'] = FiltrosHelper::dadosUsuarioAdministrativo($subject['[[sindicato][idsecretario]]'])->rg;
        $subject['[[sindicato][secretario_rg_orgao_emissor]]'] = FiltrosHelper::dadosUsuarioAdministrativo($subject['[[sindicato][idsecretario]]'])->rg_orgao_emissor;;
        $subject['[[sindicato][diretor]]'] = FiltrosHelper::dadosUsuarioAdministrativo($subject['[[sindicato][iddiretor]]'])->nome;
        $subject['[[sindicato][diretor_rg]]'] = FiltrosHelper::dadosUsuarioAdministrativo($subject['[[sindicato][iddiretor]]'])->rg;
        $subject['[[sindicato][diretor_rg_orgao_emissor]]'] = FiltrosHelper::dadosUsuarioAdministrativo($subject['[[sindicato][iddiretor]]'])->rg_orgao_emissor;;
        $subject['[[sindicato][cidade]]'] = FiltrosHelper::nomeDaCidade($subject['[[sindicato][idcidade]]'])->nome;
        $subject['[[sindicato][estado_uf]]'] = FiltrosHelper::nomeUfDoEstado($subject['[[sindicato][idestado]]'])->sigla;
        $subject['[[sindicato][cep]]'] = FiltrosHelper::formatarCEP($subject['[[sindicato][cep]]']);
        $subject['[[sindicato][logradouro]]'] = FiltrosHelper::nomeLogradouro($subject['[[sindicato][idlogradouro]]'])->nome;
        $subject['[[sindicato][municipio]]'] = FiltrosHelper::nomeDaCidade($subject['[[sindicato][idcidade]]'])->nome;
        $subject['[[sindicato][fone]]'] = $subject['[[sindicato][telefone]]'];
    }

    /**
     * @param Matriculas $matriculas
     * @param $idmatricula
     * @param $information
     * @param $certificadoCurso
     * @param $paginas
     * @param $dataInicioCurso
     * @param $ultimaNotaProva
     * @param $gabaritoProva
     */
    private function retornarDadosParaGerarCertificado(Matriculas $matriculas, $idmatricula, &$information, &$certificadoCurso, &$paginas, &$dataInicioCurso, &$ultimaNotaProva, &$gabaritoProva)
    {
        /** dados necessários para gerar o certificado */
        $matriculas->set('id', $idmatricula);
        $information['matricula'] = $matriculas->Retornar();
        $information['oferta'] = $matriculas->RetornarOferta();
        $information['curso'] = $matriculas->RetornarCurso();
        $information['oferta_curso'] = $matriculas->retornaDadosOfertaCurso($information['matricula']['idoferta'], $information['matricula']['idcurso']);
        $information['escola'] = $matriculas->RetornarEscola();
        $information['id_em_curso'] = $matriculas->retornarSituacaoAtiva();
        $information['historico'] = $matriculas->retornarHistoricosLinhas();
        $information['turma'] = $matriculas->RetornarTurma();
        $information['sindicato'] = $matriculas->RetornarSindicato();
        $information['mantenedora'] = $matriculas->RetornarMantenedora();
        $information['pessoa'] = $matriculas->RetornarPessoa();
        $information['cursoinstitucoes'] = $matriculas->RetornarCursoSindicato();
        $information['curso'] = array_merge($information['curso'], $information['cursoinstitucoes']);

//        $information['curso']['certificado'] = nl2br($information['curso']['certificado']);
//        $information['curso']['fundamentacao'] = nl2br($information['curso']['fundamentacao']);
//        $information['curso']['fundamentacao_legal'] = nl2br($information['curso']['fundamentacao_legal']);
//        $information['curso']['autorizacao'] = nl2br($information['curso']['autorizacao']);
//        $information['curso']['perfil'] = nl2br($information['curso']['perfil']);
//        $information['curso']['regulamento'] = nl2br($information['curso']['regulamento']);

        $certificadoCurso = $this->retornarCertificado($information['matricula']['idcurso'], $information['matricula']['idsindicato']);
        //$certificado = $this->set('id', $certificadoCurso['idcertificado'])->retornar(); NÃO USADO
        $paginas = $this->listarPaginas($certificadoCurso['idcertificado']); //verificar

        $dataInicioCurso = $this->retornaDataEmCurso($idmatricula);
        $ultimaNotaProva = $this->ultimaNotaProva($idmatricula);
        $gabaritoProva = null;
    }

    /**
     * @param $ultimaNotaProva
     * @param Avaliacoes $avaliacaoObj
     * @param $subject
     * @param $information
     * @param Boletim $boletim
     * @param Matriculas $matriculas
     * @param $idmatricula
     * @param $matriculaArray
     * @param $matriculaCurriculo
     * @param $cargaHorariaTotal
     * @param $folhaDiplomaMatricula
     * @param $data_final_certificado
     * @param $data_primeiro_acesso
     * @param $data_em_curso
     * @param DateTime $currentTime
     * @param $numero_folha
     * @param $qrcodePath
     * @param $certificadoCurso
     * @param $mensagens
     * @param $gabaritoProva
     */
    private function montarHTML(&$ultimaNotaProva, Avaliacoes $avaliacaoObj, &$subject, $information, Boletim $boletim, Matriculas $matriculas, $idmatricula, $matriculaArray, $matriculaCurriculo, $cargaHorariaTotal, $folhaDiplomaMatricula, $data_final_certificado, $data_primeiro_acesso, $data_em_curso, DateTime $currentTime, $numero_folha, $qrcodePath, $certificadoCurso, $mensagens, &$gabaritoProva)
    {
        /** Montando HTML do Certificado */

        /* Informações sobre a avaliação final (se existir) */
        if (!empty($ultimaNotaProva['idprova'])) {

            require_once '../classes/avaliacoes.class.php';

            $perguntas = $avaliacaoObj->retornarPerguntasMatriculaProva($ultimaNotaProva['idprova']);


            $gabaritoProva = '
                <style type="text/css">
                    table.zebra-striped {
                        border-collapse: collapse;
                    }

                    table.zebra-striped td {
                        padding-top: 0px;
                        padding-bottom: 0px;
                        padding-left: 8px;
                        padding-right: 8px;
                        border: 1px solid #000;
                        vertical-align: top;
                        text-align: center;
                    }
                </style>
                <table class="zebra-striped">
                    <tr>
                        <td colspan="3">
                            <b>AVALIAÇÃO FINAL</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>NÚMERO DA QUESTÃO</b>
                        </td>
                        <td>
                            <b>GABARITO</b>
                        </td>
                        <td>
                            <b>RESPOSTA DO ALUNO</b>
                        </td>
                    </tr>';

            $ordem = 0;
            foreach ($perguntas as $indPergunta => $varPergunta) {
                $gabarito = [];
                $resposta = [];
                foreach ($varPergunta['opcoes'] as $indOpcao => $varOpcao) {
                    if ($varOpcao['correta'] == 'S') {
                        $gabarito[] = $varOpcao['ordem'];
                    }

                    if ($varOpcao['marcada'] == 'S') {
                        $resposta[] = $varOpcao['ordem'];
                    }
                }

                $ordem++;
                $gabaritoProva .= '
                    <tr>
                        <td>
                            ' . $ordem . '
                        </td>
                        <td>
                            ' . implode(', ', $gabarito) . '
                        </td>
                        <td>
                            ' . implode(', ', $resposta) . '
                        </td>
                    </tr>';
            }

            $gabaritoProva .= '</table>';
        }

        /* Conteúdo do certificado */
        if (null !== $subject) {

            $html = '<table width="100%" border="0" cellpadding="2" cellspacing="0" style="border: 1px solid #000000; font-size:12px;" class="tableBoletim">
					<tr>
						<td colspan="5" align="center">
                            <strong>' . $information['curso']['nome'] . '</strong>
                        </td>
					</tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="4" align="center" style="font-size:12px;">
                            <strong>HISTÓRICO</strong>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#F4F4F4" width="30%"><strong>ORGANIZAÇÃO CURRICULAR</strong></td>
                        <td bgcolor="#F4F4F4" width="20%"><strong>TUTOR/INSTRUTOR</strong></td>
                        <td bgcolor="#F4F4F4" align="center"><strong>Carga Horária</strong></td>
                        <td bgcolor="#F4F4F4" align="center"><strong>Nota</strong></td>
                        <td bgcolor="#F4F4F4" align="center"><strong>Resultado</strong></td>
                    </tr>';


            foreach ($boletim['aluno_disciplinas'] as $disciplina) {
                if ($disciplina['ignorar_historico'] == 'S') {
                    continue;
                }
                $nomeProfessor = $matriculas->getNomeProfessor($idmatricula, $matriculaArray['idoferta'],
                    $disciplina['iddisciplina'], $matriculaArray['idcurso'], $matriculaArray['idescola'],
                    $disciplina['idava']);
                $disciplinaHist = $matriculas->retornarSituacaoDisciplina($idmatricula, $disciplina, $matriculaCurriculo['media']);

                $html .= '<tr>
                        <td>' . $disciplina['nome'] . '</td>
                        <td>' . $nomeProfessor . '</td>
						<td align="center">' . $disciplina['horas'] . '</td>
						<td align="center">' . ($disciplina['nota_conceito'] == 'S' ? notaConceito($disciplinaHist['valor']) : number_format($disciplinaHist['valor'], 2, ',', '.')) . '</td>
                        <td align="center">' . $disciplinaHist['situacao'] . '</td>
					</tr>';

                $cargaHorariaTotal += $disciplina['horas'];
            }

            $html .= '<tr>
					<td colspan="2">Total</td>
                    <td align="center">' . $cargaHorariaTotal . '</td>
                    <td colspan="2">&nbsp;</td>
				</tr>';
            $html .= '</table>';


            $fixed = array(
                '[[extra][codigo_validacao]]' => ($folhaDiplomaMatricula['cod_validacao']) ? $folhaDiplomaMatricula['cod_validacao'] : null,
                '[[extra][data_geracao_certificado]]' => ($data_final_certificado) ? FiltrosHelper::converterData($data_final_certificado, "d/m/Y") : null,
                '[[extra][data_inicio_curso]]' => ($data_primeiro_acesso) ? FiltrosHelper::converterData($data_primeiro_acesso, "d/m/Y") : null,
                '[[extra][data_em_curso]]' => ($data_em_curso) ? FiltrosHelper::converterData($data_em_curso, "d/m/Y") : null,
                '[[extra][link_validacao]]' => $GLOBALS['config']['urlSistema'] . '/validador</br></br>',
                '[[extra][data]]' => $currentTime->format('d/m/Y'),
                '[[extra][nota_prova]]' => $ultimaNotaProva['nota'],
                '[[extra][nota_prova_porcentagem]]' => (($ultimaNotaProva['nota'] * 100) / 10) . "%",
                '[[extra][idprova]]' => $ultimaNotaProva['idprova'],
                '[[extra][horario_prova]]' => formataData($ultimaNotaProva['inicio'], 'br', 1),
                '[[extra][gabarito_prova]]' => $gabaritoProva,
                '[[extra][data_mes_extenso]]' => FiltrosHelper::transcreverData($currentTime->format('Y-m-d')),
                '[[extra][data_expedicao]]' => formataData($folhaDiplomaMatricula['data_expedicao'], 'pt', 0),
                '[[extra][data_expedicao_mes_extenso]]' => FiltrosHelper::transcreverData($folhaDiplomaMatricula['data_expedicao']),
                '[[extra][observacoes]]' => ($numero_folha['observacoes']) ? $numero_folha['observacoes'] : null,
                '[[extra][numero_registro]]' => ($folhaDiplomaMatricula) ? $folhaDiplomaMatricula : null,
                '[[extra][folha_relacao]]' => ($numero_folha['numero_relacao']) ? $numero_folha['numero_relacao'] : null,
                '[[extra][folha_livro]]' => ($numero_folha['numero_livro']) ? $numero_folha['numero_livro'] : null,
                '[[extra][numero_folha]]' => ($numero_folha) ? $numero_folha : null,
                '[[extra][qr_code]]' => ($qrcodePath) ? '<img src="' . $qrcodePath . '" />' : null,
                '[[curso][regulamento]]' => ($certificadoCurso) ? nl2br($certificadoCurso) : null,
                '[[matricula][boletim]]' => $html,
                '[[matricula][mensagens]]' => $mensagens,
                '[[sindicato][brasao]]' => $_SERVER['DOCUMENT_ROOT'] . '/storage/sindicatos_brasao/' . $information['sindicato']['brasao_servidor'],
                '[[sindicato][logo]]' => $_SERVER['DOCUMENT_ROOT'] . '/storage/sindicatos_logo/' . $information
            );

            $subject += $fixed;
        }
    }

    /**
     *
     * @param array $upload
     *
     * @return boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function isValidFile(array $upload)
    {
        if (4 == $upload['error']) {
            $_POST['error'] = 'arquivo_nao_escolhido';
            return false;
        }

        if ('text/html' != $upload['type']) {
            $_POST['error'] = 'tipo_de_arquivo_nao_permitido';
            return false;
        }
        return true;
    }

    /**
     *
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function listaDePaginas()
    {
        $sql = 'SELECT * FROM '.self::PAGES_TABLE.' WHERE idcertificado = 1';
        $this->set('sql', $sql);
        $this->retornarLinhas();
    }

    /**
     * Lista as mídias associadas ao
     *
     * @param $id
     *
     * @return array coleçao de mídias com tags montadas
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function listarMidias($id)
    {
        if (is_null($id)) {
            $id = Request::url(4);
        }

        $query = 'SELECT * FROM '.self::MIDIAS_TABLE.'
                WHERE idcertificado = '.Request::url(4).'
                    AND ativo = "S"';

        $query .= $this->aplicarFiltrosBasicos(true);

        $query .= ' ORDER BY idcertificado_midia DESC';

        $midias = mysql_query($query);

        $_midias = array();
        while ($midia = mysql_fetch_assoc($midias)) {
            $_midias[] = $midia;
        }

        return $_midias;
    }

    /**
     *
     *
     * @param null $id
     *
     * @return array
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function listarPaginas($id = null)
    {
        if (is_null($id)) {
            $id = Request::url(4);
        }

        $query = 'SELECT * FROM `'.self::PAGES_TABLE.'`
                     WHERE idcertificado = '.$id.'
                            AND ativo = "S"';

       // Up and running...
       return $this->set('sql', $query)
                   ->set('campos', '*')
                   ->aplicarFiltrosBasicos()
                   ->set('groupby', '*')
                   ->set('ordem', ($_GET['ord']) ? $_GET['ord'] : 'ASC')
                   ->set('ordem_campo', ($_GET['cmp']) ? $_GET['cmp'] : 'ordem')
                   ->retornarLinhas();
    }

    /**
     * Lista todos os dados cadastrados
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function listarTodas()
    {
        $this->set('sql', 'SELECT * FROM `' . self::CURRENT_TABLE . '` WHERE ativo = "S"')
            ->set('campos', '*')
            ->aplicarFiltrosBasicos()
            ->set('groupby', '*');

        return $this->retornarLinhas();
    }

    /**
     * Salva os dados, ou atualiza-os.
     *
     * @return void|boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function modificar()
    {
        return $this->salvarDados();
    }

    /**
     * Cadastra uma nova imagem
     *
     * @throws Exception
     * @return boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function registerNewMidia()
    {
        $uploadDirectory = realpath(dirname(__FILE__).'/../storage/certificados_midias');

        if (! $uploadDirectory) {
            throw new Exception('Diretório base '.$uploadDirectory.' parece não existir!');
        }

        $id   = Request::url(4);
        $nome = Request::post('nome');
        $file = Request::files('arquivo');

        $nome = trim($nome);

        if (empty($nome)) {
            $_POST['error'] = 'nome_vazio';
            return false;
        }

        $uploadDirectory .= '/'.$id;

        if (! file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777);
        }

        $fileName = md5($file['name']).basename($file['name']);

        move_uploaded_file(
            $file['tmp_name'],
            $uploadDirectory.'/'.$fileName
        );

        $query = 'INSERT INTO '.self::MIDIAS_TABLE.'
                    SET
                        idcertificado = '.$id.',
                        nome = "'.$nome.'",
                        arquivo_nome = "'.$file['name'].'",
                        arquivo_servidor = "'.$fileName.'",
                        arquivo_tipo = "'.$file['type'].'",
                        arquivo_tamanho = '.$file['size'].',
                        ativo = "S",
                        ativo_painel = "S"';
        $salvar = mysql_query($query);
        if($salvar){
          $this->retorno["sucesso"] = true;
          $this->monitora_qual = mysql_insert_id();
          $this->monitora_oque = 1;
          $this->monitora_onde = 211;
          $this->Monitora();
        }
        return $salvar;

    }

    /**
     *
     * @throws Exception
     *
     * @return boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function registerNewPage()
    {
        $uploadDirectory = realpath(dirname(__FILE__).'/../storage/certificados');

        if (! $uploadDirectory) {
            throw new Exception('Diretório base '.$uploadDirectory.' parece não existir!');
            exit;
        }

        $id    = Request::url(4);
        $nome  = Request::post('nome');
        $order = Request::post('ordem');
        $file  = Request::files('arquivo');

        $nome = trim($nome);

        if (empty($nome)) {
            $_POST['error'] = 'nome_vazio';
            return false;
        }


        if (! $this->isValidFile($file)) {
            return false;
        }

        $uploadDirectory .= '/'.$id;

        if (! file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777);
        }

        $fileName = md5($file['name']).'.html';

        move_uploaded_file(
            $file['tmp_name'],
            $uploadDirectory.'/'.$fileName
        );

        $query = 'INSERT INTO '.self::PAGES_TABLE.'
                    SET
                        idcertificado = '.$id.',
                        nome = "'.$nome.'",
                        ordem = "'.$order.'",
                        arquivo_nome = "'.$file['name'].'",
                        arquivo_servidor = "'.$fileName.'",
                        arquivo_tipo = "'.$file['type'].'",
                        arquivo_tamanho = '.$file['size'].',
                        ativo = "S",
                        ativo_painel = "S"';
        $salvar = mysql_query($query);

        if($salvar){
          $this->retorno["sucesso"] = true;
          $this->monitora_qual = mysql_insert_id();
          $this->monitora_oque = 1;
          $this->monitora_onde = 278;
          $this->Monitora();
        }

        return $salvar;
    }

    /**
     * Remove uma linha no banco
     *
     * @return boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function remover()
    {
        return $this->removerDados();
    }

    /**
     * Retorna uma única linha do banco de dados.
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @api
     */
    public function retornar()
    {
        if (Request::url(4)) {
            $id = Request::url(4);
        } else {
            $id = 1;
        }

        $querySelect = 'SELECT * FROM `' . self::CURRENT_TABLE . '`
                    WHERE ' . self::PRIMARY_KEY . ' = ' . $id;

        return $this->retornarLinha($querySelect);
    }

    /**
     * Disable a page from a passed Id
     *
     * @param integer $idPage
     *
     * @throws InvalidArgumentException
     * @return resource
     */
	public function disablePage($idPage)
	{
		if (! is_numeric($idPage)) {
			throw new InvalidArgumentException('First parameters is not a number');
		}

		$query = sprintf(
			'UPDATE %s SET ativo = "%s" WHERE certificados_paginas = %d',
			self::PAGES_TABLE,
			'N',
			$idPage
		);
		$salvar =  $this->executaSql($query);
		if($salvar){
   		  $this->retorno["sucesso"] = true;
   		  $this->monitora_qual = $idPage;
   		  $this->monitora_oque = 3;
   		  $this->monitora_onde = 278;
   		  $this->Monitora();
		}

		return $this->retorno;
	}

    /**
     * Return informations of a page based on your `certificados_paginas`
     *
     * @param $idPage
     *
     * @throws InvalidArgumentException
     * @return array
     * @internal param int $idpage
     */
	public function getPageInfo($idPage)
	{
		if (! is_numeric($idPage)) {
			throw new InvalidArgumentException('First parameters is not a number');
		}

		$query = sprintf(
			'SELECT * FROM `%s` WHERE certificados_paginas = %d',
			self::PAGES_TABLE,
			$idPage
		);

		return $this->retornarLinha($query);
	}

    /**
     * Garda um valor no array
     *
     * @param $content
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    private function _storage($content)
    {
        $this->_storage[] = $content;
    }

	public function retornarCertificado($idcurso, $idsindicato) {
		$sql = 'select * from cursos_sindicatos where idcurso = '.$idcurso.' and idsindicato = '.$idsindicato.' and ativo = "S"';
		return $this->retornarLinha($sql);
	}

	function removerMidia($idarquivo, $idcertificado)
    {
        $this->sql = "UPDATE certificados_midias SET ativo='N' where idcertificado_midia = " . $idarquivo . " and idcertificado = " . $idcertificado;
        $dados = $this->executaSql($this->sql);

        if ($dados) {
            $this->retorno["sucesso"] = true;
            $this->monitora_onde = 211;
            $this->monitora_oque = 3;
            $this->monitora_qual = $idarquivo;
            $this->Monitora();
        }

        return $this->retorno;
    }

    public function gerarQRCode($dadosArray) {
        if (empty($dadosArray)) {
            die('Sem dados para gerar o QR code.');
        }

        require "phpqrcode/qrlib.php";
        $content = implode(' | ', $dadosArray);

        $fileDirTemp = DIR_APP . "/storage/temp/";
        $fileName = 'qrcode_'.md5($content).'.png';
        $filePath = $fileDirTemp.$fileName;

        // generating
        if (!file_exists($filePath)) {
            QRcode::png($content, $filePath, QR_ECLEVEL_H, 2, 1);
        }

        return $filePath;

    }

}
