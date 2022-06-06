<?php
/**
 * Class Avaliacoes
 */
class Avaliacoes extends Core
{
    /**
     * @var
     */
    public $idprofessor;
    /**
     * @var
     */
    public $idmatricula;
    /**
     * @var
     */
    public $idavaliacaoava;

    /**
     * @return array
     */
    public function listarTodas()
    {

        $this->sql = "SELECT {$this->campos}
              FROM matriculas_avaliacoes ma
                INNER JOIN matriculas m ON ma.idmatricula = m.idmatricula
                INNER JOIN pessoas p ON m.idpessoa = p.idpessoa
                INNER JOIN avas_avaliacoes aa ON ma.idavaliacao = aa.idavaliacao
				INNER JOIN avas a ON aa.idava = a.idava	";

			if($this->idprofessor)
				$this->sql .= ' INNER JOIN professores_avas pa
                    on pa.idava = a.idava AND
                    pa.idprofessor = '.$this->idprofessor.' AND
                    pa.ativo = "S" ';

		$this->sql .=	"INNER JOIN disciplinas d
                            ON aa.iddisciplina_nota = d.iddisciplina
						 LEFT JOIN professores prof
                         ON ma.idprofessor = prof.idprofessor
					        WHERE ma.ativo = 'S'";

        $this->_filtroDeBusca();



        return $this->set('groupby', "ma.idprova")
            ->retornarLinhas();
    }

    /**
     * @return bool
     */
    private function _filtroDeBusca()
    {
        if (! is_array($_GET['q'])) {
            return false;
        }

        foreach ($_GET['q'] as $campo => $valor) {

            $tipo = current(explode('|', $campo));
            $campo = end(explode('|', $campo));

            $valor = str_replace("'", '', $valor);

            /**
              * Se $<valor> for igual a "todos" não aplica o filtro
              * e passa para o próximo item do loop cuidado aqui com
              * essa regrita complexa
              *
              * @deprecated Simplificar esse código aqui depois
              */
            if (! (($valor || '0' === $valor) and 'todos' != $valor)) {
                continue 1;
            }


            if (1 == $tipo) {
                $this->sql .= " and ".$campo." = '".$valor."' ";
            }

            if (2 == $tipo) {

                $busca = str_replace(
                    array("\\'", "\\"),
                    array('', ''),
                    $valor
                );

                $busca = explode(' ', $busca);

                foreach ($busca as $ind => $buscar) {
                    $this->sql .= " and ".$campo." like '%".urldecode($buscar)."%' ";
                }
            }

            if (3 == $tipo) {
                $this->sql .= sprintf(
                    ' AND date_format(%s, "%d/%m/%Y") = "%s"',
                    $campo,
                    $valor
                );
            }
        }
    }

    /**
     * @return array
     */
    public function retornar()
    {
        $this->set(
            'sql',
            "SELECT {$this->campos}
                FROM matriculas_avaliacoes ma
                    INNER JOIN matriculas m
                        ON ma.idmatricula = m.idmatricula
					INNER JOIN ofertas o
                        ON m.idoferta = o.idoferta
                    INNER JOIN cursos c
                        ON m.idcurso = c.idcurso
					INNER JOIN escolas pol
                        ON m.idescola = pol.idescola
                    INNER JOIN pessoas p
                        ON m.idpessoa = p.idpessoa
                    INNER JOIN avas_avaliacoes aa
                        ON ma.idavaliacao = aa.idavaliacao
                    INNER JOIN avas a
                        ON aa.idava = a.idava
                    INNER JOIN disciplinas d
                        ON aa.iddisciplina_nota = d.iddisciplina
                    LEFT JOIN professores prof
                        ON ma.idprofessor = prof.idprofessor
            WHERE ma.ativo = 'S' AND ma.idprova = '{$this->id}'"
        );

        return $this->retornarLinha($this->get('sql'));
    }

    /**
     * @return array
     */
    public function cadastrar()
    {
        return $this->SalvarDados();
    }

    /**
     * @return array
     */
    public function modificar()
    {
        return $this->SalvarDados();
    }

    /**
     * @return array
     */
    public function remover()
    {
        return $this->RemoverDados();
    }

    /**
     * @return array
     */
    public function retornarProva()
    {
        $this->sql = "SELECT ma.*
            FROM matriculas_avaliacoes ma
            WHERE ma.ativo = 'S'
            AND ma.idprova = '{$this->id}'";

        return $this->retornarLinha($this->sql);
    }

    /**
     * @param $idprova
     * @return mixed
     */
    public function retornarProvaRespondida($idprova)
    {
        $this->sql = 'SELECT
                        avl.nome as avaliacao,
                        avl.imagem_exibicao_servidor,
                        p.*,
                        map.resposta, map.id_prova_pergunta,
                        map.observacao, map.nota_questao,
						map.arquivo as aluno_arquivo,
                        map.arquivo_servidor as aluno_arquivo_servidor,
						map.arquivo_tamanho as aluno_arquivo_tamanho,
                        map.arquivo_tipo as aluno_arquivo_tipo,
						map.arquivo_professor as professor_arquivo,
                        map.arquivo_professor_servidor as professor_arquivo_servidor,
						map.arquivo_professor_tamanho as professor_arquivo_tamanho,
                        map.arquivo_professor_tipo as professor_arquivo_tipo
                FROM matriculas_avaliacoes ma
                    INNER JOIN matriculas_avaliacoes_perguntas map
                        ON ma.idprova = map.idprova
                    INNER JOIN perguntas p
                        ON map.idpergunta = p.idpergunta and p.ativo = "S"
                    INNER JOIN avas_avaliacoes avl
                        ON avl.idavaliacao = ma.idavaliacao
                WHERE ma.ativo = "S" and ma.idprova = '.$idprova;

        $perguntas = $this->retornarLinhas();

        foreach ($perguntas as $pergunta) {
            $retorno[$pergunta['idpergunta']] = $pergunta;

            if ($pergunta['tipo'] != 'S') {
                $this->sql = 'SELECT po.*, mapom.id_prova_pergunta_opcao
                        FROM perguntas_opcoes po
                        LEFT JOIN
                            matriculas_avaliacoes_perguntas_opcoes_marcadas mapom
                                ON po.idopcao = mapom.idopcao
                                and mapom.id_prova_pergunta = '.
                                    $pergunta['id_prova_pergunta'].'
                        WHERE po.ativo = "S" and po.idpergunta = '.
                            $pergunta['idpergunta'] . ' group by po.idopcao ';

                $opcoes = $this->retornarLinhas();
                $retorno[$pergunta['idpergunta']]['opcoes'] = $opcoes;
            }
        }

        return $retorno;
    }

    /**
     * @param $idbloco_disciplina
     * @param $idmatricula
     * @return array
     */
    function retornarAvaliacoesPorBlocoDisciplina($idbloco_disciplina, $idmatricula)
    {
        $oferta = $this->retornarLinha('SELECT idoferta FROM
                                                 matriculas
                                        WHERE idmatricula = '.$idmatricula);

        $this->sql = "SELECT DISTINCT
                            avl.*
                    FROM
                        curriculos_blocos_disciplinas cbd
                    INNER JOIN curriculos_blocos cb
                        ON (cbd.idbloco = cb.idbloco)
                    INNER JOIN disciplinas d
                        ON (cbd.iddisciplina = d.iddisciplina)
                    LEFT JOIN ofertas_curriculos_avas oca
                        ON oca.ativo = 'S' AND
                        oca.iddisciplina = cbd.iddisciplina AND
                        oca.idcurriculo = cb.idcurriculo AND
                        oca.idoferta = ".$oferta['idoferta']."
                    INNER JOIN
                        avas_avaliacoes avl
                    ON (avl.idava = oca.idava AND avl.ativo = 'S')
                    WHERE
                        cbd.idbloco_disciplina = ".(int)$idbloco_disciplina."  AND
                        cbd.ativo = 'S' ";
        $this->ordem = "desc";
        $this->ordem_campo = "avl.idavaliacao";
        $this->limite = -1;
        $avaliacoes = $this->retornarLinhas();

        foreach ($avaliacoes as $ind => $avaliacao) {
            $this->idmatricula = $idmatricula;
            $this->idavaliacaoava = $avaliacao['idavaliacao'];
            $prova = $this->retornarInformacoesAvaliacaoAluno();
            if ($prova['idprova']) {
                $avaliacoes[$ind]['idprova'] = $prova['idprova'];
                $avaliacoes[$ind]['ultima_tentativa'] = $prova['inicio'];
                $avaliacoes[$ind]['nota'] = $prova['nota'];
                $avaliacoes[$ind]['tentativas'] = $prova['tentativas'];
            }
        }
        return $avaliacoes;
    }

    function retornarInformacoesAvaliacaoAluno() {
        $this->sql = "SELECT max(idprova) as idprova,
                                count(idprova) as tentativas,
                                max(inicio) as inicio,
                                max(nota) as nota
                      FROM
                        matriculas_avaliacoes
                      WHERE
                      idavaliacao = '".(int)$this->idavaliacaoava."'
                      AND idmatricula ='".(int)$this->idmatricula."'
                      AND ativo = 'S' order by idprova desc limit 1";

        $prova = $this->retornarLinha($this->sql);
        return $prova;
    }

    /**
     * @param $idavaliacao
     * @return array
     */
    public function retornarAvaliacaoProva($idavaliacao)
    {

        $this->sql = sprintf(
            'SELECT %s FROM avas_avaliacoes aa
                WHERE aa.ativo = "S"
                    AND aa.idavaliacao = %d',
            $this->campos,
            (int) $idavaliacao
        );

        $avaliacao = $this->retornarLinha($this->sql);
        $array_data = explode(':', $avaliacao['tempo']);
        $avaliacao['tempo_em_segundos'] = ($array_data[2] + ($array_data[1]*60) + ($array_data[0]*3600 ));
        $array_data = explode(':', $avaliacao['tempo_alerta']);
        $avaliacao['tempo_em_segundos_alerta'] = ($array_data[2] + ($array_data[1]*60) + ($array_data[0]*3600 ));

        return $avaliacao;
    }

    /**
     * @param $idavaliacao
     * @param $idmatricula
     *
     * @internal param $idbloco_disciplina
     * @return array
     */
    function gerarProva($idavaliacao, $idmatricula)
    {
        if (verificaPermissaoAcesso(true)) {
            $this->sql = "SELECT
                            avl.*
                        FROM
                          avas_avaliacoes avl
                        WHERE
                          idavaliacao = '".(int)$idavaliacao."'";

            $avaliacao = $this->retornarLinha($this->sql);

            $this->sql = "SELECT
                            avd.iddisciplina
                        FROM
                            avas_avaliacoes_disciplinas avd
                        WHERE
                            avd.ativo = 'S' AND
                            avd.idavaliacao = ".(int)$idavaliacao;
            $this->ordem_campo = "avd.iddisciplina";
            $this->ordem = " ASC";
            $this->limite = - 1;
            $this->groupby = "avd.iddisciplina";
            $disciplinasPerguntas = $this->retornarLinhas();

            foreach ($disciplinasPerguntas as $indice => $disciplina) {
                $arrayDisciplinas[] =  $disciplina['iddisciplina'];
            }
            $disciplinas = implode(',', $arrayDisciplinas);

            $objetivas_faceis = array();
            $objetivas_medias = array();
            $objetivas_dificeis = array();

            if ($avaliacao['objetivas_faceis'] > 0) {
                $this->sql = "SELECT *
                        FROM perguntas WHERE ativo = 'S' AND ativo_painel = 'S'
                    AND iddisciplina in(".$disciplinas.")
                    AND avaliacao_virtual = 'S'
                    AND tipo = 'O'
                    AND dificuldade = 'F' ";

                $this->ordem_campo = "rand() ";
                $this->limite = $avaliacao['objetivas_faceis'];
                $this->ordem = " ";
                $this->groupby = "idpergunta";

                $objetivas_faceis = $this->retornarLinhas();
            }

            if ($avaliacao['objetivas_intermediarias'] > 0) {
                $this->sql = "SELECT *
                    FROM perguntas WHERE ativo = 'S' AND ativo_painel = 'S'
                        AND iddisciplina in(".$disciplinas.")
                        AND avaliacao_virtual = 'S'
                        AND tipo = 'O' and  dificuldade = 'M' ";

                $this->ordem_campo = "rand() ";
                $this->limite = $avaliacao['objetivas_intermediarias'];
                $this->ordem = " ";
                $this->groupby = "idpergunta";
                $objetivas_medias = $this->retornarLinhas();
            }

            if ($avaliacao['objetivas_dificeis'] > 0) {
                $this->sql = "SELECT * FROM perguntas
                        WHERE ativo = 'S' AND ativo_painel = 'S'
                        AND iddisciplina in(".$disciplinas.")
                        AND avaliacao_virtual = 'S' AND tipo = 'O'
                        AND  dificuldade = 'D' ";
                $this->ordem_campo = "rand() ";
                $this->ordem = " ";
                $this->limite = $avaliacao['objetivas_dificeis'];
                $this->groupby = "idpergunta";
                $objetivas_dificeis = $this->retornarLinhas();
            }

            $perguntasObjetivas = array_merge(
                $objetivas_faceis,
                $objetivas_medias,
                $objetivas_dificeis
            );

            foreach ($perguntasObjetivas as $ind => $pergunta) {
                $this->sql = "SELECT
                              *
                            FROM
                              perguntas_opcoes
                            WHERE
                              ativo = 'S' AND
                              ativo_painel = 'S' AND
                              idpergunta = '".$pergunta['idpergunta']."'";
                $this->ordem_campo = " ordem";
                $this->ordem = " ASC";
                $this->limite = - 1;
                $this->groupby = " idpergunta";
                $perguntasObjetivas[$ind]['opcoes'] = $this->retornarLinhas();
            }

            $subjetivas_faceis = array();
            $subjetivas_medias = array();
            $subjetivas_dificeis = array();
            if ($avaliacao["avaliador"] == 'professor') {

                if ($avaliacao['subjetivas_faceis'] > 0) {
                    $this->sql = "SELECT *
                                FROM
                                    perguntas
                                WHERE
                                    ativo = 'S' AND
                                    ativo_painel = 'S' AND
                                    iddisciplina in(".$disciplinas.") AND
                                    avaliacao_virtual = 'S'AND
                                    tipo = 'S' AND
                                    dificuldade = 'F' ";
                    $this->ordem_campo = "rand() ";
                    $this->ordem = " ";
                    $this->limite = $avaliacao['subjetivas_faceis'];
                    $this->groupby = "idpergunta";
                    $subjetivas_faceis = $this->retornarLinhas();
                }

                if ($avaliacao['subjetivas_intermediarias'] > 0) {
                    $this->sql = "SELECT * FROM perguntas WHERE ativo = 'S'
                            AND ativo_painel = 'S'
                            AND iddisciplina in(".$disciplinas.")
                            AND avaliacao_virtual = 'S' AND tipo = 'S'
                            AND  dificuldade = 'M' ";

                    $this->ordem_campo = " rand() ";
                    $this->ordem = " ";
                    $this->limite = $avaliacao['subjetivas_intermediarias'];
                    $this->groupby = "idpergunta";
                    $subjetivas_medias = $this->retornarLinhas();
                }

                if ($avaliacao['subjetivas_dificeis'] > 0) {
                    $this->sql = "SELECT * FROM perguntas WHERE ativo = 'S'
                        AND ativo_painel = 'S'
                        AND iddisciplina in(".$disciplinas.")
                        AND avaliacao_virtual = 'S'
                        AND tipo = 'S'
                        AND  dificuldade = 'D' ";

                    $this->ordem_campo = " rand() ";
                    $this->ordem = " ";
                    $this->limite = $avaliacao['subjetivas_dificeis'];
                    $this->groupby = "idpergunta";
                    $subjetivas_dificeis = $this->retornarLinhas();
                }
            }

            $perguntas = array_merge(
                $perguntasObjetivas,
                $subjetivas_faceis,
                $subjetivas_medias,
                $subjetivas_dificeis
            );

            $this->sql = "INSERT INTO
                                matriculas_avaliacoes
                            SET
                                    inicio = now(),
                                    idavaliacao = '".$avaliacao['idavaliacao']."',
                                    idmatricula = '".(int)$idmatricula."',
                                    prova_corrigida = 'N',
                                    ativo = 'S'";

            $insere_prova = $this->executaSql($this->sql);

            if (! $insere_prova) {
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "mensagem_erro_gerar_prova";
            } else {
                $this->id = mysql_insert_id();
                $this->monitora_oque = 10;
                $this->monitora_onde = 157;
                $this->monitora_qual = $this->id;
                $this->Monitora();

                foreach ($perguntas as $ind => $pergunta) {
                    $this->sql = "INSERT INTO
                                    matriculas_avaliacoes_perguntas
                                SET
                                    idprova = '".$this->id."',
                                    idpergunta = '".$pergunta['idpergunta']."'";
                    $this->executaSql($this->sql);
                    $perguntas[$ind]['idprovapergunta'] = mysql_insert_id();
                    $perguntas[$ind]['idprova'] = $this->id;

                }
                $perguntas['idprova'] = $this->id;
                $_SESSION['prova_gerada'] = $this->id ;
            }
            return $perguntas;
        } else {
            $perguntas['erro_json'] = "sem_permissao";
            return $perguntas;
        }
    }

    /**
     * @param $file
     * @param $campoAux
     * @return bool|string
     */
    function moverArquivo($file, $campoAux){
        $extensao = strtolower(strrchr($file["name"], "."));
        $nome_servidor = date("YmdHis")."_".uniqid().$extensao;

        if (move_uploaded_file($file["tmp_name"],$_SERVER["DOCUMENT_ROOT"].
            "/storage/".$campoAux["pasta"]."/".$nome_servidor)) {
            return $nome_servidor;
        } else
            return false;
    }

    /**
     * @return array
     */
    function salvarRespostasProva()
    {
        if (verificaPermissaoAcesso(true)) {
            $prova = $this->retornarProva();
            /** Salvando Respostas objetivas */
            foreach ($this->post['respostas_subjetivas'] as $ind => $subjetiva) {
                $this->sql = "UPDATE matriculas_avaliacoes_perguntas SET
                                   resposta = '{$subjetiva}'
                            WHERE id_prova_pergunta = '{$ind}'";
                $this->executaSql($this->sql);
            }

            /** Salvando Respostas objetivas única escolha */
            foreach ($this->post['opcoes_unica'] as $ind => $objetiva_unica) {
                $this->sql = "INSERT INTO matriculas_avaliacoes_perguntas_opcoes_marcadas
                        SET id_prova_pergunta = '".$ind."',
                            idopcao = '".$objetiva_unica."'";
                $this->executaSql($this->sql);
            }
            /** Salvando Respostas objetivas múltipla escolha */
            foreach ($this->post['opcoes_multipla'] as $ind => $objetiva_multiplas) {
                foreach ($objetiva_multiplas as $chave => $opcao) {
                    $this->sql = "INSERT INTO
                            matriculas_avaliacoes_perguntas_opcoes_marcadas SET
                            id_prova_pergunta = '".$ind."',
                            idopcao = '".$opcao."'";
                    $this->executaSql($this->sql);
                }
            }
            $campoAux['pasta'] = 'provas_alunos_anexos';
            foreach ($this->files['anexos_perguntas']['name'] as $idprovapergunta => $arquivo_propriedade) {
                $arquivo['name'] = $this->files['anexos_perguntas']['name'][$idprovapergunta];
                $arquivo['tmp_name'] = $this->files['anexos_perguntas']['tmp_name'][$idprovapergunta];
                $arquivo['size'] = $this->files['anexos_perguntas']['size'][$idprovapergunta];
                $arquivo['type'] = $this->files['anexos_perguntas']['type'][$idprovapergunta];
                $arquivo['error'] = $this->files['anexos_perguntas']['error'][$idprovapergunta];
                $arquivo_servidor = $this->moverArquivo($arquivo, $campoAux);
                if ($arquivo_servidor) {
                    $this->sql = "UPDATE matriculas_avaliacoes_perguntas SET
                                arquivo = '".$arquivo['name']."',
                                arquivo_servidor = '{$arquivo_servidor}',
                                arquivo_tipo = '".$arquivo['type']."',
                                arquivo_tamanho = '".$arquivo['size']."'
                                WHERE id_prova_pergunta = '{$idprovapergunta}'";
                    $this->executaSql($this->sql);
                }
            }

            $dadosdousuario = retornaSOBrowser();

            $this->sql = "UPDATE matriculas_avaliacoes SET
                                    fim = now(),
                                    ip = '" . $dadosdousuario['ip'] . "',
                                    navegador = '" . mysql_escape_string($dadosdousuario['navegador']) . "',
                                    sistema_operacional = '" . mysql_escape_string($dadosdousuario['so']) . "',
                                    navegador_versao = '" . mysql_escape_string($dadosdousuario['navegador_versao']) . "',
                                    user_agent = '" . mysql_escape_string($dadosdousuario['user_agent']) . "'
                                    WHERE idprova = '".$this->id."'";
            $conclusao_prova = $this->executaSql($this->sql);
            if ($conclusao_prova) {
                $this->executaSql("commit");
                $this->monitora_oque = 4;
                $this->monitora_onde = 157;
                $this->monitora_qual = $this->id;
                $this->Monitora();

                $this->retorno["sucesso"] = true;
                $this->retorno["mensagem"] = "mensagem_prova_responder_sucesso";

                if ($this->post['tipo_correcao'] == 'sistema') {
                    $resultado_prova = $this->corrigirProvaSistema();

                    if (! $resultado_prova['sucesso']) {
                        return $resultado_prova;
                    }
                }

            } else {
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "mensagem_prova_responder_erro";
            }
            unset($_SESSION['prova_gerada']);
            return $this->retorno;
        }
    }

    /**
     * @return array
     */
    public function corrigirProvaSistema()
    {
        $sql = 'SELECT
                    ma.*, avl.iddisciplina_nota, avl.idtipo
                FROM
                    matriculas_avaliacoes ma
                INNER JOIN avas_avaliacoes avl
                ON (avl.idavaliacao = ma.idavaliacao)
                WHERE idprova = '.$this->id;
        $antiga = $this->retornarLinha($sql);

        if (! $antiga) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_linha_antiga';
            return $this->retorno;
        }

        $seleciona_idnota = "SELECT
                                    *
                                FROM
                                    matriculas_notas
                                WHERE
                                    idprova = ".$this->id."
                                LIMIT 1";
        $matriculas_notas = $this->retornarLinha($seleciona_idnota);

        $this->sql = 'SELECT
                        avl.objetivas_faceis as faceis,
                        avl.objetivas_intermediarias as medias,
                        avl.objetivas_dificeis as dificeis,
                        p.multipla_escolha,
                        p.tipo,
                        p.idpergunta,
                        map.id_prova_pergunta
            FROM matriculas_avaliacoes ma
              INNER JOIN matriculas_avaliacoes_perguntas map
                    ON ma.idprova = map.idprova
              INNER JOIN perguntas p
                    ON map.idpergunta = p.idpergunta and p.ativo = "S"
              INNER JOIN avas_avaliacoes avl
                    ON avl.idavaliacao = ma.idavaliacao
            WHERE ma.ativo = "S" and ma.idprova = '.$this->id;

        $perguntas = $this->retornarLinhas();

        $valorQuestao = 10 / ($perguntas[0]['faceis']
            + $perguntas[0]['medias']
            + $perguntas[0]['dificeis']);

        foreach ($perguntas as $ind => $pergunta) {

            $retorno[$pergunta['idpergunta']] = $pergunta;

            $this->sql = 'SELECT * FROM
                                perguntas_opcoes
                            WHERE ativo="S"
                            AND idpergunta ='.$pergunta['idpergunta'].'
                            AND correta = "S" ';

            $this->set('ordem', 'desc')
                ->set('ordem_campo', 'idopcao')
                ->set('limite', -1);

            $opcoes_certas = $this->retornarLinhas();

            mysql_query('START TRANSACTION');

            $sql = 'SELECT * FROM matriculas_avaliacoes_perguntas
                WHERE id_prova_pergunta = '.$pergunta['id_prova_pergunta'].'
                AND idprova = '.$this->id;

            $antiga_pergunta = $this->retornarLinha($sql);

            $this->sql = 'SELECT *
                  FROM
                    matriculas_avaliacoes_perguntas_opcoes_marcadas
                WHERE
                    id_prova_pergunta ='.$pergunta['id_prova_pergunta'];

            $this->set('ordem_campo ', 'idopcao');
            $opcoes_marcadas = $this->retornarLinhas();

            if ($pergunta['multipla_escolha'] != 'S') {
                if ($opcoes_certas[0]['idopcao'] == $opcoes_marcadas[0]['idopcao']) {
                    $notaQuestao += $valorQuestao;
                }
            } elseif ($pergunta['multipla_escolha'] == 'S') {
                $valorOpcao = $valorQuestao / count($opcoes_certas);

                foreach ($opcoes_certas as $indCert => $opcao_certa) {
                    foreach ($opcoes_marcadas as $indMarc => $opcao_marcada) {
                        if ($opcao_marcada['idopcao'] == $opcao_certa['idopcao']) {
                            $notaQuestao += $valorOpcao;
                            break;
                        }
                    }
                }
            }

            if (! $notaQuestao) {
                $notaQuestao = 0.0;
            }

            $sql = 'UPDATE matriculas_avaliacoes_perguntas set
                  nota_questao = '.$notaQuestao.'
                WHERE id_prova_pergunta = '.$pergunta['id_prova_pergunta'].' and
                    idprova = '.$this->id;

            $atualiza = $this->executaSql($sql);

            if (! $atualiza) {
                mysql_query('ROLLBACK');
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_corrigir_questoes';
                return $this->retorno;
            }

            $sql = 'SELECT * FROM matriculas_avaliacoes_perguntas
                    WHERE id_prova_pergunta = '.$pergunta['id_prova_pergunta'].'
                        and idprova = '.$this->id;

            $nova_pergunta = $this->retornarLinha($sql);

            if (! $nova_pergunta) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_linha_nova';
                return $this->retorno;
            }

            $this->monitora_oque = 158;
            $this->monitora_qual = $this->id;
            $this->monitora_dadosantigos = $antiga_pergunta;
            $this->monitora_dadosnovos = $nova_pergunta;
            $this->Monitora();

            $notaAluno += $notaQuestao;
            $notaQuestao = 0;

            if (! $notaAluno) {
                $notaAluno = 0.0;
            }
        }

        $sql = 'UPDATE matriculas_avaliacoes SET
            nota = '.$notaAluno.',
            prova_corrigida = "S",
            data_correcao = NOW()
          WHERE idprova = '.$this->id;

        $atualiza_avaliacao = $this->executaSql($sql);

        if (! $atualiza_avaliacao) {
            mysql_query('ROLLBACK');
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_atualizar_matriculas_avaliacoes';
            return $this->retorno;
        }

        //Alteração matriculas_notas------
        if (! $matriculas_notas['idmatricula_nota']) {
            $this->sql = "INSERT INTO
                            matriculas_notas
                        SET
                            data_cad = NOW(),
                            ativo = 'S',
                            idprova = '".(int)$this->id."',
                            idmatricula = '".$antiga['idmatricula']."',
                            nota = '".$notaAluno."',
                            iddisciplina = '".(int)$antiga['iddisciplina_nota']."',
                            idtipo = '".(int)$antiga['idtipo']."' ";
							# tipo_avaliacao = '".(int)$antiga['avaliacao']."'
            if ($this->executaSql($this->sql)) {
                $this->monitora_oque = 1;
                $this->monitora_onde = 180;
                $this->monitora_qual = mysql_insert_id();
                $this->Monitora();
            }
        }
        //END

        $sql = 'SELECT * FROM matriculas_avaliacoes WHERE idprova = '.$this->id;
        $nova = $this->retornarLinha($sql);

        if (! $nova) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_linha_nova';
            return $this->retorno;
        }

        $this->monitora_oque = 157;
        $this->monitora_qual = $this->id;
        $this->monitora_dadosantigos = $antiga;
        $this->monitora_dadosnovos = $nova;
        $this->Monitora();

        mysql_query('COMMIT');
        $this->retorno['sucesso'] = true;
        return $this->retorno;
    }

    /**
     * @param $idavaliacao
     * @param $idbloco_disciplina
     * @param $idmatricula
     * @return mixed
     */
    function retornarQtdeTentativasRealizadas(
        $idavaliacao,
        $idbloco_disciplina,
        $idmatricula
    ) {
        $this->sql = "SELECT
                            count(mavl.idprova) as totalTentativas
                    FROM
                        curriculos_blocos_disciplinas cbd
                    INNER JOIN
                        ofertas_curriculos_avas oca
                    ON
                        oca.ativo = 'S' AND
                        oca.iddisciplina = cbd.iddisciplina
                    INNER JOIN
                        avas_avaliacoes avl
                    ON (avl.idava = oca.idava)
                    INNER JOIN
                        matriculas_avaliacoes mavl
                    ON (mavl.idavaliacao = avl.idavaliacao)
                    WHERE
                        cbd.idbloco_disciplina = ".(int)$idbloco_disciplina."  AND
                        cbd.ativo = 'S' AND
                        mavl.ativo =  'S' AND
                        mavl.idavaliacao = '".(int)$idavaliacao."' AND
                        mavl.idmatricula = '".$idmatricula."'";
        $resultado = $this->retornarLinha($this->sql);

        return $resultado['totalTentativas'];
    }

    /**
     * @param $idavaliacao
     * @param $idbloco_disciplina
     * @param $idmatricula
     * @return array
     */
    public function retornarTentativasAvaliacao(
        $idavaliacao,
        $idbloco_disciplina,
        $idmatricula
    ) {

        $oferta = $this->retornarLinha('SELECT idoferta FROM
                                                 matriculas
                                        WHERE idmatricula = '.$idmatricula);

        $this->sql = "
                    SELECT DISTINCT
                        mavl.*,
                        avl.idavaliacao,
                        avl.nome as avaliacao,
                        avl.periode_de,
                        avl.periode_ate,
                        avl.imagem_exibicao_servidor
                    FROM
                        curriculos_blocos_disciplinas cbd
                    INNER JOIN
                        ofertas_curriculos_avas oca
                    ON
                        (
                            oca.ativo = 'S' AND
                            oca.iddisciplina = cbd.iddisciplina
                        )
                    INNER JOIN
                        avas_avaliacoes avl
                    ON (avl.idava = oca.idava)
                    INNER JOIN
                        matriculas_avaliacoes mavl
                    ON (mavl.idavaliacao = avl.idavaliacao)
                    WHERE
                        cbd.idbloco_disciplina = ".(int)$idbloco_disciplina."  AND
                        cbd.ativo = 'S' AND
                        mavl.ativo =  'S' AND
                        mavl.idavaliacao = '".(int)$idavaliacao."' AND
                        mavl.idmatricula = '".(int)$idmatricula."' AND
                        oca.idoferta = ".(int)$oferta['idoferta'];

        $this->ordem = "desc";
        $this->ordem_campo = "mavl.idprova";
        $this->limite = -1;
        $provas = $this->retornarLinhas();
        return $provas;
    }

    /**
     * @return array
     */
    function retornarHistorico()
    {
        $this->sql = "SELECT * FROM matriculas_avaliacoes_historicos
        WHERE idprova = '".$this->id."' order by data_cad desc";

        $seleciona = $this->executaSql($this->sql);

        while ($historico = mysql_fetch_assoc($seleciona)) {
            $historico["modulo"] = "Sistema";

            if ($historico["idprofessor"]) {
                $this->sql = "SELECT * FROM professores
                    WHERE idprofessor='".$historico["idprofessor"]."'";

                $historico["usuario"] = $this->retornarLinha($this->sql);
                $historico["modulo"] = "Professor";
            } elseif ($historico["idmatricula"]) {
                $this->sql = "SELECT p.*
								FROM matriculas m
								INNER JOIN pessoas p ON m.idpessoa = p.idpessoa
								WHERE m.idmatricula='".$historico["idmatricula"]."'";

                $historico["usuario"] = $this->retornarLinha($this->sql);
                $historico["modulo"] = "Aluno";
            }

            switch ($historico["tipo"]) {
            case "prova":
                switch ($historico["acao"]) {
                case "abriu":
                    $historico["descricao"] = "Abriu a prova.<br>";
                    break;
                case "respondeu":
                    $historico["descricao"] = "Respondeu a prova.<br>";
                    break;
                case "corrigiu":
                    $historico["descricao"] = "Corrigiu a prova.<br>";
                    break;
                case "recorrigiu":
                    $historico["descricao"] = "Recorrigiu a prova.<br>";
                    break;
                }
                break;
            }
            $historicos[] = $historico;
        }
        return $historicos;
    }

    /**
     * @param $historicoArray
     * @param $idioma
     * @param bool $ficha
     * @return string
     */
    function retornarHistoricoTabela($historicoArray, $idioma, $ficha = false)
    {

        $retorno
            = '<table cellpadding="5" cellspacing="0"
                class="table table-bordered table-condensed tabelaSemHover">';

        if (! $ficha) {
            $div_style = 'style="height:400px; overflow:auto;"';
            $wid_col1 = 100;
            $wid_col2 = 200;
            $wid_col3 = 140;
        } else {
            $retorno .= '<tr>
                <td bgcolor="#F4F4F4" colspan="4" style="padding:0px; height:30px;">
                <strong>'.$idioma["hist_label"].'</strong></td></tr>';
        }

        $retorno
            .= ' <tr>
                <td width="'.$wid_col1.'" bgcolor="#F4F4F4">
                    <strong>'.$idioma["historico_modulo"].'</strong>
                </td>
                <td width="'.$wid_col2.'" bgcolor="#F4F4F4">
                    <strong>'.$idioma["historico_usuario"].'</strong>
                </td>
                <td width="'.$wid_col3.'" bgcolor="#F4F4F4">
                    <strong>'.$idioma["historico_data"].'</strong>
                </td>
                <td bgcolor="#F4F4F4">
                    <strong>'.$idioma["historico_desc"].'</strong>
                </td>
            </tr>';

        foreach ($historicoArray as $ind => $val) {
            $retorno
                .= '<tr>
                <td width="'.$wid_col1.'">'. $val["modulo"].'</td>
                <td width="'.$wid_col2.'">'.
                    $val["usuario"]["nome"].'<br /><span style="color:#999;">'.
                    $val["usuario"]["email"].'</span></td>
                <td width="'.$wid_col3.'">'.
                    formataData($val["data_cad"], 'br', 1).
                    '<br /><span style="color:#999;">'.
                        $idioma["historico_repasse_id"].' '.
                            $val["idhistorico"].'</span></td>
                <td width="">'.
                    $val["descricao"]
                .'</td>
                </tr>';
        }

        $retorno .= '</table> ';

        return $retorno;
    }

    /**
     * @return array
     */
    function corrigirProva()
    {

        $sql = 'SELECT
                    ma.*, avl.iddisciplina_nota, avl.idtipo
                FROM
                    matriculas_avaliacoes ma
                INNER JOIN avas_avaliacoes avl
                ON (avl.idavaliacao = ma.idavaliacao)
                WHERE
                    idprova = '.$this->id;
        $antiga = $this->retornarLinha($sql);

        if (! $antiga) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_linha_antiga';
            return $this->retorno;
        }

        $seleciona_idnota = "SELECT
                                    *
                                FROM
                                    matriculas_notas
                                WHERE
                                    idprova = ".$this->id."
                                LIMIT 1";
        $matriculas_notas = $this->retornarLinha($seleciona_idnota);

        foreach ($this->post['notas'] as $id => $nota) {
            $nota_total += str_replace(',', '.', $nota);
        }

        if ($nota_total > 10) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'data_maior_dez';
            return $this->retorno;
        } else if (! count($this->post['notas'])) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'notas_vazio';
            return $this->retorno;
        }

        mysql_query('START TRANSACTION');

        foreach ($this->post['notas'] as $id => $nota) {
            $sql = 'SELECT * FROM matriculas_avaliacoes_perguntas
                    WHERE id_prova_pergunta = '.$id.' and idprova = '.$this->id;

            $antiga_pergunta = $this->retornarLinha($sql);

            if (! $antiga_pergunta) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_linha_antiga';
                return $this->retorno;
            }

            $sql = 'UPDATE matriculas_avaliacoes_perguntas SET
                nota_questao = '.str_replace(',', '.', $nota).',
                observacao = "'.$this->post['observacoes'][$id].'"
              WHERE id_prova_pergunta = '.$id.' and idprova = '.$this->id;

            $atualiza = $this->executaSql($sql);

            if (! $atualiza) {
                mysql_query('ROLLBACK');
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_corrigir_questoes';
                return $this->retorno;
            }

			//*******
			$campoAux['pasta'] = 'provas_professores_anexos';
			$arquivo['name'] = $this->post['anexos']['name'][$id];
            $arquivo['tmp_name'] = $this->post['anexos']['tmp_name'][$id];
            $arquivo['size'] = $this->post['anexos']['size'][$id];
            $arquivo['type'] = $this->post['anexos']['type'][$id];
            $arquivo['error'] = $this->post['anexos']['error'][$id];

			$arquivo_servidor = $this->moverArquivo($arquivo, $campoAux);
			if ($arquivo_servidor) {
				$this->sql = "UPDATE matriculas_avaliacoes_perguntas SET
								arquivo_professor = '".$arquivo['name']."',
								arquivo_professor_servidor = '{$arquivo_servidor}',
								arquivo_professor_tipo = '".$arquivo['type']."',
								arquivo_professor_tamanho = '".$arquivo['size']."'
							  WHERE id_prova_pergunta = ".$id;
				$atualiza_arquivo = $this->executaSql($this->sql);
				if (! $atualiza_arquivo) {
					mysql_query('ROLLBACK');
					$this->retorno["erro"] = true;
					$this->retorno["erros"][] = 'erro_salvar_arquivo';
					return $this->retorno;
				}
			}
			//*******

            $sql = 'SELECT * FROM matriculas_avaliacoes_perguntas
            WHERE id_prova_pergunta = '.$id.' and idprova = '.$this->id;

            $nova_pergunta = $this->retornarLinha($sql);

            if (! $nova_pergunta) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_linha_nova';
                return $this->retorno;
            }

            $this->monitora_oque = 158;
            $this->monitora_qual = $this->id;
            $this->monitora_dadosantigos = $antiga_pergunta;
            $this->monitora_dadosnovos = $nova_pergunta;
            $this->Monitora();
        }

        $sql = 'UPDATE matriculas_avaliacoes SET
              nota = '.$nota_total.',
              prova_corrigida = "S",
              data_correcao = NOW(),
              idprofessor = '.$this->idprofessor.'
            WHERE idprova = '.$this->id;

        $atualiza_avaliacao = $this->executaSql($sql);

        if (! $atualiza_avaliacao) {
            mysql_query('ROLLBACK');
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_atualizar_matriculas_avaliacoes';
            return $this->retorno;
        }

        //Inserir em matriculas_notas
        if ($matriculas_notas['idmatricula_nota']
        && ($matriculas_notas['nota'] != $nota_total
        || $matriculas_notas['iddisciplina'] != $disciplina)) {
            $this->sql = "UPDATE
                            matriculas_notas
                        SET
                            ativo = 'S',
                            nota = '".$nota_total."',
                            iddisciplina = '".(int)$antiga['iddisciplina_nota']."',
							idtipo = '".(int)$antiga['idtipo']."'
                        WHERE
                            idprova = ".(int)$this->id;
							# tipo_avaliacao = '".(int)$antiga['avaliacao']."'
            if ($this->executaSql($this->sql)) {
                $this->monitora_oque = 2;
                $this->monitora_onde = 180;
                $this->monitora_qual = $matriculas_notas['idmatricula_nota'];
                $this->Monitora();
            }

        } elseif (! $matriculas_notas['idmatricula_nota']) {
            $this->sql = "INSERT INTO
                            matriculas_notas
                        SET
                            ativo = 'S',
                            data_cad = NOW(),
                            idprova = '".(int)$this->id."',
                            nota = '".$nota_total."',
                            idmatricula = '".$antiga['idmatricula']."',
                            iddisciplina = '".(int)$antiga['iddisciplina_nota']."',
							idtipo = '".(int)$antiga['idtipo']."'
                             ";
							# tipo_avaliacao = '".(int)$antiga['avaliacao']."'
            if ($this->executaSql($this->sql)) {
                $this->monitora_oque = 1;
                $this->monitora_onde = 180;
                $this->monitora_qual = mysql_insert_id();
                $this->Monitora();
            }
        }

        $sql = 'INSERT INTO matriculas_avaliacoes_historicos SET
              data_cad = NOW(),
              idprova = '.$this->id.',
              idprofessor = '.$this->idprofessor.',
              tipo = "prova" ';

        if ($antiga['prova_corrigida'] == 'S') {
            $sql .= ', acao = "recorrigiu" ';
        } else {
            $sql .= ', acao = "corrigiu" ';
        }

        $atualiza = $this->executaSql($sql);

        if (! $atualiza) {
            mysql_query('ROLLBACK');
            $this->retorno["erro"] = true;
            $this->retorno["erros"][]
                = 'erro_atualizar_matriculas_avaliacoes_historico';
            return $this->retorno;
        }

        $sql = 'SELECT * FROM matriculas_avaliacoes WHERE idprova = '.$this->id;
        $nova = $this->retornarLinha($sql);

        if (! $nova) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_linha_nova';
            return $this->retorno;
        }

        $this->monitora_oque = 157;
        $this->monitora_qual = $this->id;
        $this->monitora_dadosantigos = $antiga;
        $this->monitora_dadosnovos = $nova;
        $this->Monitora();

        mysql_query('COMMIT');
        $this->retorno['sucesso'] = true;
        return $this->retorno;
    }

    /**
     * @param $idpergunta
     * @return array
     */
    function retornaArquivoPerguntaDownload($idpergunta) {
		$this->sql = "SELECT * FROM perguntas
					  where
						idpergunta = ".$idpergunta;
		$retorno = $this->retornarLinha($this->sql);
		return $retorno;
	}

    /**
     * @param $id_prova_pergunta
     * @return array
     */
    function retornaArquivoPerguntaAlunoDownload($id_prova_pergunta) {
		$this->sql = "SELECT arquivo, arquivo_servidor, arquivo_tipo, arquivo_tamanho
					  FROM matriculas_avaliacoes_perguntas
					  WHERE
						id_prova_pergunta = ".$id_prova_pergunta;
		$retorno = $this->retornarLinha($this->sql);
		return $retorno;
	}

    /**
     * @param $id_prova_pergunta
     * @return array
     */
    function retornaArquivoPerguntaProfessorDownload($id_prova_pergunta) {
		$this->sql = "SELECT arquivo_professor, arquivo_professor_servidor, arquivo_professor_tipo, arquivo_professor_tamanho
					  FROM matriculas_avaliacoes_perguntas
					  WHERE
						id_prova_pergunta = ".$id_prova_pergunta;
		$retorno = $this->retornarLinha($this->sql);
		return $retorno;
	}

    function retornarPerguntasMatriculaProva($idprova) {
        $this->sql = 'SELECT
                        map.*,
                        p.nome,
                        p.imagem_servidor,
                        p.tipo,
                        p.permite_anexo_resposta,
                        p.multipla_escolha
                    FROM
                        matriculas_avaliacoes_perguntas map
                        INNER JOIN perguntas p ON (map.idpergunta = p.idpergunta)
                    WHERE
                        map.idprova = '.$idprova;
        $this->limite = -1;
        $this->ordem = 'ASC';
        $this->ordem_campo = 'map.id_prova_pergunta';
        $perguntas = $this->retornarLinhas();
        foreach ($perguntas as $ind => $pergunta) {
            if($pergunta['tipo'] == 'O') {
                $this->sql = "SELECT
                                po.*,
                                IF(mapom.id_prova_pergunta_opcao IS NULL, 'N', 'S') as marcada
                            FROM
                                perguntas_opcoes po
                                LEFT OUTER JOIN matriculas_avaliacoes_perguntas_opcoes_marcadas mapom ON (mapom.id_prova_pergunta = ".$pergunta['id_prova_pergunta']." AND po.idopcao = mapom.idopcao)
                            WHERE
                                po.idpergunta = ".$pergunta['idpergunta']." AND
                                po.ativo = 'S'";
                $this->limite = -1;
                $this->ordem = 'ASC';
                $this->ordem_campo = 'ordem';
                $opcoes = $this->retornarLinhas();
                $perguntas[$ind]['opcoes'] = $opcoes;
            }
        }
        return $perguntas;
    }

}
