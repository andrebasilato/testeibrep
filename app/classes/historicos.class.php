<?php
/**
 * `Historicos`
 *
 * @author     Junior Lisboa <josej@alfamaweb.com.br>
 *
 * @package    Oráculo Construtor
 * @copyright  Copyright (c) 2014 Alfama Web (http://alfamaweb.com.br)
 * @license    Proprietary AlfamaWeb
 * @version    $Id$
 */
class Historicos extends Core
{

    /* Tabelas usadas */
    const PRIMARY_KEY = 'idhistorico_escolar';
    const CURRENT_TABLE = 'historico_escolar';
    const PAGES_TABLE = 'historico_escolar_paginas';
    const MIDIAS_TABLE = 'historico_escolar_midias';
    const PASTA = 'historico_escolar';
    const PASTAMIDIAS = 'historico_escolar_midias';



    /**
     * @var array
     */
    private $_storage = array();


    public function cadastrar()
    {
        return $this->salvarDados();
    }


    public function downloadPages(array $pages)
    {
        include '../assets/plugins/MPDF54/mpdf.php';

        $mpdf = new mPDF('c', 'A4', '', '', null, null, null, null, null, null, '');
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

        $arquivo_nome = "../storage/temp/historico_escolar_".$this->idmatricula.".pdf";
        $mpdf->Output($arquivo_nome, "F");

        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($arquivo_nome) . '"');
        header('Content-Length: ' . filesize($arquivo_nome));
        header('Expires: 0');
        header('Pragma: no-cache');
        readfile($arquivo_nome);
        exit;
    }

    public function gerarCertificado($idmatricula, Matriculas $matriculas, $idFolhaRegistro = NULL)
    {
        $idmatricula = (int) $idmatricula;

        /** dados necessários para gerar o certificado */
        $matriculas->set('id', $idmatricula);
        $information['matricula'] = $matriculas->Retornar();
        $information['oferta'] = $matriculas->RetornarOferta();
        $information['curso'] = $matriculas->RetornarCurso();
        $information['escola'] = $matriculas->RetornarEscola();
        $information['turma'] = $matriculas->RetornarTurma();
        $information['sindicato'] = $matriculas->RetornarSindicato();
        $information['mantenedora'] = $matriculas->RetornarMantenedora();
        $information['pessoa'] = $matriculas->RetornarPessoa();
        $information['cursoinstitucoes'] = $matriculas->RetornarCursoSindicato();

        $information['curso'] = array_merge($information['curso'], $information['cursoinstitucoes']);

        $information['curso']['certificado'] = nl2br($information['curso']['certificado']);
        $information['curso']['fundamentacao'] = nl2br($information['curso']['fundamentacao']);
        $information['curso']['fundamentacao_legal'] = nl2br($information['curso']['fundamentacao_legal']);
        $information['curso']['autorizacao'] = nl2br($information['curso']['autorizacao']);
        $information['curso']['perfil'] = nl2br($information['curso']['perfil']);
        $information['curso']['regulamento'] = nl2br($information['curso']['regulamento']);
        $certificadoCurso = $this->retornarHistorico($information['matricula']['idcurso'], $information['matricula']['idsindicato']);

        $cod_validacao = $this->geraCodValidacao($idmatricula , $_POST['data_historico'] , $certificadoCurso['idhistorico_escolar']);
        // printf("[Eureka; %s():%d]",__FUNCTION__,__LINE__);

        $certificado = $this->set('id', $certificadoCurso['idhistorico_escolar'])->retornar();
        $paginas = $this->listarPaginas($certificadoCurso['idhistorico_escolar']);

        /** Mensagens da matrícula */
        $matriculas->groupby = 'idmensagem';
        $messageCollection = $matriculas->listarMensagensParaCertificado($idmatricula);

        $mensagens = '';
        foreach ($messageCollection as $message) {
            $mensagens .= $message['mensagem'] . nl2br(PHP_EOL);
        }

        // Se estiver na página de folhas de registro do diploma
        // pegar informações da folha atual
        if ('folhasregistrosdiplomas' == Request::url(3)) {
            $idFolhaRegistro = Request::url(4);
        }

        $currentTime = new DateTime('now');

        $boletim = new Boletim(new Avaliacoes);
        $boletim['idmatricula'] = $idmatricula;
        $boletim->buscarDadosDaMatriculaHistorico();


        $html = '<table width="100%" border="0" cellpadding="8" cellspacing="0" style="border: 1px solid #000;">
					<tr>
						<td bgcolor="#F4F4F4">Disciplina</td>
						<td bgcolor="#F4F4F4" align="center">Carga Horária</td>
						<td bgcolor="#F4F4F4" align="center">Média Final</td>
					</tr>';
		$cargaHorariaTotal = 0;
		foreach ($boletim['aluno_disciplinas'] as $disciplina) {
			$notaAluno = null;

           $textodisciplinaporc = '';
            if($boletim['aluno']->porcentagem_ava > 0) {
                if($boletim['aluno']->porcentagem_manual) {
                    $disciplina['porcentagem_aluno_ava']["porc_aluno"] = $boletim['aluno']->porcentagem_manual;
                }
                $materia_reprovada = false;
                if($disciplina['porcentagem_aluno_ava']['porc_aluno'] < $boletim['aluno']->porcentagem_ava) {
                    $materia_reprovada = true;
                    $textodisciplinaporc = '<font style="color:red"> (não atingiu a porcentagem mínima do AVA)</font>';
                }
            }



			if ($disciplina['ignorar_historico'] == 'S') {
				continue;
			}

			$aproveitamento_estudo = boletim::getAproveitamentoEstudos($boletim['idmatricula'], $disciplina['iddisciplina']);

			if (!$aproveitamento_estudo['idmatricula_nota']) {

				$notas_disciplina = boletim::getProvasTipos($boletim['idmatricula'], $disciplina['iddisciplina']);
				foreach ($notas_disciplina as $nota) {
					$notas[$nota['idtipo']] = number_format($nota['nota'], 2, ',', '.');
				}
				$formula = new Formulas_Notas;
				$formResult = $formula->set('id', $disciplina['idformula'])->set('post', $notas)->validarFormula();

			}

			if ($aproveitamento_estudo['aproveitamento_estudo'] == 'S') {
				$notaAluno = 'AE - Aproveitamento de Estudos';
			} else if ($disciplina['exibir_aptidao'] == 'S') {
				if ($formResult['valor'] == '10.00' || $formResult['valor'] == '10') {
					$notaAluno = 'APTO';
				} else {
					$notaAluno = 'INAPTO';
				}
			} else {
				$notaAluno = number_format($formResult['valor'], 2, ',', '.');
			}

            if ($disciplina['contabilizar_media'] == 'S') {
                $media_total += $formResult['valor'];
                $disciplinas++;
            }

			$html .= '<tr>
						<td>'.$disciplina['nome'].$textodisciplinaporc.'</td>
						<td align="center">'.$disciplina['horas'].'</td>
						<td align="center">'.(isset($notaAluno) ? $notaAluno : '--').'</td>
					</tr>';

			$cargaHorariaTotal += $disciplina['horas'];
		}
		$html .= '<tr>
					<td colspan="3" align="center"><strong>Total: '.$cargaHorariaTotal.' Horas</strong></td>
				</tr>';

        $html .= '<tr>
                    <td colspan="3" align="center">';

                        if($media_total && $disciplinas) {
                            $media_final = ($media_total/$disciplinas);
                        }

                       $html .= '  <strong>Resultado final: </strong>';


                        if ($boletim['aluno']->dias_minimo) {
                            $data_atual = date('Y-m-d');
                            $data_minima = new DateTime(formataData($boletim['aluno']->data_matricula, 'en', 0));
                            $data_minima->modify('+' . $boletim['aluno']->dias_minimo . ' days');
                            if($data_minima->format('Y-m-d') <= $data_atual) {
                                $pode_aprovar_curriculo = true;
                            }
                        } else {
                            $pode_aprovar_curriculo = true;
                        }


                         if($media_final >= $boletim['aluno']->media_curriculo && $pode_aprovar_curriculo && !$materia_reprovada) {
                          $html .= 'APROVADO';
                        } else {
                          $html .= 'REPROVADO';
                         }
        $html .= '      </td>
                </tr>';

		$html .= '</table>';

        /* variáveis */
        $fixed = array(
            '[[extra][codigo_validacao]]' => ($cod_validacao) ? $cod_validacao : null,
            '[[extra][link_validacao]]' => $GLOBALS['config']['urlSistema'] . '/validador</br></br>',
            '[[extra][data]]' => $currentTime->format('d/m/Y'),
            '[[extra][data_mes_extenso]]' => FiltrosHelper::transcreverData($currentTime->format('Y-m-d')),
            '[[extra][data_informada]]' => $_POST['data_historico'],
            '[[extra][data_informada_extenso]]' => FiltrosHelper::transcreverData(formataData($_POST['data_historico'],'en',0)),
            '[[extra][data_informada_ano]]' => substr($_POST['data_historico'], -4),
            '[[extra][data_expedicao]]' => formataData($folhas['data_expedicao'],'pt',0),
            '[[extra][data_expedicao_mes_extenso]]' => FiltrosHelper::transcreverData($folhas['data_expedicao']),
            '[[extra][observacoes]]' => ($folhas['observacoes']) ? $folhas['observacoes'] : null,
			'[[extra][folha_registro]]' => ($folhaDiplomaMatricula['numero_registro']) ? $folhaDiplomaMatricula['numero_registro'] : null,
			'[[extra][folha_relacao]]' => ($folhas['numero_relacao']) ? $folhas['numero_relacao'] : null,
			'[[extra][folha_livro]]' => ($folhas['numero_livro']) ? $folhas['numero_livro'] : null,
			'[[extra][folha_numero]]' => ($folhas['numero_folha']) ? $folhas['numero_folha'] : null,
			'[[curso][regulamento]]' => ($certificadoCurso['regulamento']) ? nl2br($certificadoCurso['regulamento']) : null,
            '[[matricula][boletim]]' => $html,
            '[[matricula][mensagens]]' => $mensagens,
			'[[sindicato][brasao]]' => 'http://'.$_SERVER['SERVER_NAME'].'/storage/sindicatos_brasao/'.$information['sindicato']['brasao_servidor'],
			'[[sindicato][logo]]' => 'http://'.$_SERVER['SERVER_NAME'].'/storage/sindicatos_logo/'.$information['sindicato']['logo_servidor']
        );

        foreach ($information['pessoa'] as $tag => $valor) {
            $subject["[[aluno][".$tag."]]"] = $valor;
        }

        foreach ($information as $key => $value) {
            if (is_array($value) && $key != 'pessoa') {
                foreach ($value as $tag => $valor) {
                    if (! is_array($valor)) {
                        $subject["[[".$key."][".$tag."]]"] = $valor;
                    }
                }
            }
        }

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
        $subject['[[aluno][curso_anterior_cidade]]'] = FiltrosHelper::nomeDaCidade($subject['[[aluno][idcidade]]'])->nome;
		    $subject['[[aluno][curso_anterior_estado]]'] = FiltrosHelper::nomeUfDoEstado($subject['[[aluno][idestado]]'])->nome;
        */
        $subject['[[aluno][genero]]'] = $GLOBALS["sexo"]["pt_br"][ $subject['[[aluno][sexo]]'] ];
        $subject['[[aluno][codigo]]'] = $subject['[[aluno][idpessoa]]'];
        $subject['[[aluno][nome_curso_anterior]]'] = $subject['[[aluno][curso_anterior]]'];

        /** Escola */
        $subject['[[escola][documento]]'] = FiltrosHelper::formatarCpf($subject['[[escola][documento]]']);

        /** Matrícula */
        unset($_tmp);
        if($subject['[[matricula][data_conclusao]]'])
			$_tmp = $subject['[[matricula][data_conclusao]]'];
		else
			$_tmp = '0000-00-00';
        $subject['[[matricula][data_conclusao]]'] = FiltrosHelper::converterData($_tmp, 'd/m/Y');
		    $subject['[[matricula][data_conclusao_mes_ano]]'] = FiltrosHelper::converterData($_tmp, 'm/Y');
        $subject['[[matricula][data_conclusao_mes_extenso]]'] = FiltrosHelper::transcreverData($_tmp);
        $subject['[[matricula][turma]]'] = $subject['[[turma][nome]]'];
        $subject['[[matricula][renach]]'] = $information['matricula']['renach'];

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

        /** Pegar áreas do curso ``Separadas por vírgula`` */
        $curso = new Cursos;
        $areas = $curso->set('id', $subject['[[matricula][idcurso]]'])
            ->set('campos', 'nome')
            ->listarAreasAssociadas();

        foreach($areas as $area) {
            $_area[] = $area['nome'];
        }

        $subject['[[curso][area]]'] = implode(', ', $_area);

        if (null !== $subject) {
          $subject += $fixed;
        }

        $patterns = array(
            '#\[\[midia\]\[(\d+)\]\]#i' => 'SELECT * FROM historico_escolar_midias WHERE idhistorico_escolar_midia = %d'
        );

        /* diretório com os modelos dos certificados */
        $foundFilesOn = realpath(
            dirname(__FILE__)
            .'/../storage/'.self::PASTA.'/'.$certificadoCurso['idhistorico_escolar']
        );

        foreach ($paginas as $pagina) {
            $_tmp_file_location = $foundFilesOn .'/'.$pagina['arquivo'];

            if (file_exists($_tmp_file_location)) {
                $content = file_get_contents($_tmp_file_location);

                // Aplica as primeiras modificações com replace
                foreach ($subject as $search => $replace) {
                    $content = str_ireplace($search, $replace, $content);
                }

                //echo $content;exit;/*[<< EXIBIR HISTORICO NA PAGINA]*/

                // Faz busca e replace apartir de regex
                foreach ($patterns as $pattern => $query) {
                    preg_match_all($pattern, $content, $match);
                    $midiasCollection = array_combine($match[1], $match[0]);

                    foreach ($midiasCollection as $id => $tagToReplace) {
                        $_query = mysql_fetch_object(mysql_query(sprintf($query, $id)));
                        $content = str_ireplace($tagToReplace, 'http://'.$_SERVER['SERVER_NAME'].'/storage/'.self::PASTAMIDIAS.'/'.$_query->idhistorico_escolar.'/'.$_query->arquivo, $content);
                    }

                }

                $this->_storage($content);
            }
        }
        return $this->_storage;
    }

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

    public function listaDePaginas()
    {
        $sql = 'SELECT * FROM '.self::PAGES_TABLE.' WHERE idhistorico_escolar = 1';
        $this->set('sql', $sql);
        $this->retornarLinhas();
    }


    public function listarMidias($id)
    {
        if (is_null($id)) {
            $id = Request::url(4);
        }

        $query = 'SELECT * FROM '.self::MIDIAS_TABLE.'
                WHERE idhistorico_escolar = '.Request::url(4).'
                    AND ativo = "S"';

        $query .= $this->aplicarFiltrosBasicos(true);

        $query .= ' ORDER BY idhistorico_escolar_midia DESC';

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
                     WHERE idhistorico_escolar = '.$id.'
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

    public function listarTodas()
    {
        $this->set('sql', 'SELECT * FROM `' . self::CURRENT_TABLE . '` WHERE ativo = "S"')
            ->set('campos', '*')
            ->aplicarFiltrosBasicos()
            ->set('groupby', '*');

        return $this->retornarLinhas();
    }

    public function modificar()
    {
        return $this->salvarDados();
    }

    public function registerNewMidia()
    {
        $uploadDirectory = realpath(dirname(__FILE__).'/../storage/'.self::PASTAMIDIAS);

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
                        idhistorico_escolar = '.$id.',
                        nome = "'.$nome.'",
                        arquivo = "'.$fileName.'",
                        ativo = "S",
                        ativo_painel = "S"';

        return mysql_query($query);

    }

    public function registerNewPage()
    {
        $uploadDirectory = realpath(dirname(__FILE__).'/../storage/'.self::PASTA);

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
                        idhistorico_escolar = '.$id.',
                        nome = "'.$nome.'",
                        ordem = "'.$order.'",
                        arquivo = "'.$fileName.'",
                        ativo = "S",
                        ativo_painel = "S"';

        return mysql_query($query);
    }

    public function remover()
    {
        return $this->removerDados();
    }

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

	public function disablePage($idPage)
	{
		if (! is_numeric($idPage)) {
			throw new InvalidArgumentException('First parameters is not a number');
		}

		$query = sprintf(
			'UPDATE %s SET ativo = "%s" WHERE idhistorico_escolar_paginas = %d',
			self::PAGES_TABLE,
			'N',
			$idPage
		);

		return $this->executaSql($query);
	}


	public function getPageInfo($idPage)
	{
		if (! is_numeric($idPage)) {
			throw new InvalidArgumentException('First parameters is not a number');
		}

		$query = sprintf(
			'SELECT * FROM `%s` WHERE idhistorico_escolar_paginas = %d',
			self::PAGES_TABLE,
			$idPage
		);

		return $this->retornarLinha($query);
	}


    private function _storage($content)
    {
        $this->_storage[] = $content;
    }

	public function retornarHistorico($idcurso, $idsindicato) {
		$sql = 'select * from cursos_sindicatos where idcurso = '.$idcurso.' and idsindicato = '.$idsindicato.' and ativo = "S"';
		return $this->retornarLinha($sql);
	}

	function removerMidia($idarquivo, $idhistorico_escolar)
    {
        $this->sql = "UPDATE historico_escolar_midias SET ativo='N' where idhistorico_escolar_midia = " . $idarquivo . " and idhistorico_escolar = " . $idhistorico_escolar;
        $dados = $this->executaSql($this->sql);

        if ($dados) {
            $this->retorno["sucesso"] = true;
            $this->monitora_onde = 255;
            $this->monitora_oque = 3;
            $this->monitora_qual = $idarquivo;
            $this->Monitora();
        }

        return $this->retorno;
    }

    function geraCodValidacao($matricula , $data , $historico) {

        $data = explode('/', $data);
        $data = $data[2].'-'.$data[1].'-'.$data[0];
        $this->sql = "INSERT INTO matriculas_historico SET idmatricula = ".$matricula." , idhistorico = ".$historico." , ativo = 'S' , data_cad = '".$data."' ";
        $query = $this->executaSql($this->sql);
        if($query){
            $id = mysql_insert_id();
            $cod = md5($id);
            $this->sql = "UPDATE matriculas_historico SET cod_validacao = '".$cod."' WHERE idmatricula_historico = ".$id;
            $this->executaSql($this->sql);
            return $cod;
        }
    }
}
