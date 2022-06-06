<?php
class Provas_Presenciais extends Core
{
    public $idcurso = null;
    public $idescola = null;
    public $gestor_sindicato = null;

    function ListarTodas() {

        $this->sql = "SELECT ".$this->campos."
						FROM
							provas_presenciais pr
							inner join matriculas_notas_tipos mnt on (pr.idtipo = mnt.idtipo)
						WHERE
							pr.ativo='S' ";

        if($this->idusuario){
            if($this->gestor_sindicato <> 'S') {
                /*$this->sql .= " AND EXISTS ( SELECT ua.idusuario
                                            FROM usuarios_adm ua
                                            INNER JOIN usuarios_adm_sindicatos uai ON (ua.idusuario = uai.idusuario AND (ua.gestor_sindicato = 'S' OR uai.ativo = 'S'))
                                            INNER JOIN escolas p ON (uai.idsindicato = p.idsindicato AND p.ativo = 'S')
                                            INNER JOIN provas_presenciais_escolas ppp ON (ppp.idescola = p.idescola AND ppp.ativo = 'S')
                                            WHERE ppp.id_prova_presencial = pr.id_prova_presencial AND ua.idusuario = ".$this->idusuario."  AND ( ua.gestor_sindicato = 'S' or (uai.idusuario IS NOT NULL AND p.idsindicato IS NOT NULL ) )
                                            GROUP BY uai.idsindicato LIMIT 1
                                        ) ";*/

                if (!$_SESSION['adm_sindicatos'])
                    $_SESSION['adm_sindicatos'] = '0';

                $this->sql .= " AND
                                    (
                                        EXISTS ( SELECT p.idescola
                                            FROM
                                            escolas p
                                            INNER JOIN provas_presenciais_escolas ppp ON (ppp.idescola = p.idescola AND ppp.ativo = 'S')
                                            WHERE ppp.id_prova_presencial = pr.id_prova_presencial
                                            AND p.idsindicato in (".$_SESSION['adm_sindicatos'].")
                                            LIMIT 1
                                        )
                                        OR
                                        EXISTS ( SELECT lp.idlocal
                                            FROM
                                            locais_provas lp
                                            INNER JOIN provas_presenciais_locais_provas pplp ON (pplp.idlocal = lp.idlocal AND pplp.ativo = 'S')
                                            WHERE pplp.id_prova_presencial = pr.id_prova_presencial
                                            AND lp.idsindicato in (".$_SESSION['adm_sindicatos'].")
                                            LIMIT 1
									    )
                                    )";

                /*if ($_GET['teste_alfama']) {
                    echo $this->sql; exit;
                }*/
            }
        }
        $this->aplicarFiltrosBasicos();
        $this->groupby = "pr.id_prova_presencial";

        $provas = $this->retornarLinhas();
        foreach ($provas as $indArray => $prova) {
            $this->sql = "SELECT
							COUNT( id_solicitacao_prova ) AS total
						  FROM
						  	provas_solicitadas
						  WHERE
						  	id_prova_presencial ='".$prova['id_prova_presencial']."'
						  	AND ativo = 'S'
						  	AND situacao = 'A' ";
            $resultado = $this->retornarLinha($this->sql);
            $provas[$indArray]['qtde_alunos'] = $resultado['total'];
        }

        return $provas;
    }

    function retornarQtdeAlunosProva($idescola = null, $idlocal = null) {
        $this->sql = "SELECT
							count(ps.idmatricula) as total_alunos
					FROM
						provas_solicitadas ps
					WHERE
						ps.ativo = 'S'
						AND ps.situacao = 'A'
						AND ps.id_prova_presencial = ".$this->id;

        if ($idescola) {
            $this->sql .= " AND ps.idescola = ".$idescola;
        }
        if ($idlocal) {
            $this->sql .= " AND ps.idlocal = ".$idlocal;
        }

        $qtde_alunos = $this->retornarLinha($this->sql);
        return $qtde_alunos['total_alunos'];
    }

    function retornarQtdeMaximaProvaEscola($idescola) {
        $this->sql = "SELECT quantidade_pessoas_comportadas FROM escolas WHERE idescola =".$idescola;
        $total = $this->retornarLinha($this->sql);
        return $total['quantidade_pessoas_comportadas'];
    }
    function retornarQtdeMaximaProvaLocal($idlocal) {
        $this->sql = "SELECT quantidade_pessoas_comportadas FROM locais_provas WHERE idlocal =".$idlocal;
        $total = $this->retornarLinha($this->sql);
        return $total['quantidade_pessoas_comportadas'];
    }

    function retornarSindicatosEscolasLocaisProva() {
        $intituicoesLocais = array();
        $intituicoesEscolas = array();

        $sqlEscolas = "SELECT
                    po.idsindicato
                    FROM provas_presenciais_escolas prpo
                    INNER JOIN escolas po ON (po.idescola = prpo.idescola)
                    WHERE
                        prpo.ativo = 'S' AND
                        prpo.id_prova_presencial = ".$this->id;
        $sqlEscolas .= " GROUP BY po.idsindicato ";

        $queryEscolas = $this->executaSql($sqlEscolas);
        while($linha = mysql_fetch_assoc($queryEscolas)) {
            $intituicoesEscolas[] = $linha['idsindicato'];
        }


        $sqlLocais = "SELECT
                    l.idsindicato
                    FROM provas_presenciais_locais_provas prl
                    INNER JOIN locais_provas l ON (l.idlocal = prl.idlocal)
                    WHERE
                        prl.ativo = 'S' AND
                        prl.id_prova_presencial = ".$this->id;
        $sqlLocais .= " GROUP BY l.idlocal ";
        $queryLocais = $this->executaSql($sqlLocais);

        while($linha = mysql_fetch_assoc($queryLocais)) {
            if (!is_numeric(array_search($linha['idsindicato'], $intituicoesEscolas))) {
                $intituicoesLocais[] = $linha['idsindicato'];
            }
        }

        $intituicoes = array_merge($intituicoesEscolas, $intituicoesLocais);

        $intituicoesRetorno = implode(',', $intituicoes);
        return $intituicoesRetorno;
    }

    function retornarLocaisProva($tipoRetorno = 2, $idsindicato = false) {

        if (! $this->id) {
            return null;
        }

        $sql = "SELECT
                    prlo.idlocal,
                    l.nome as local_prova,
                    l.idsindicato
            FROM provas_presenciais_locais_provas prlo
            INNER JOIN locais_provas l ON (l.idlocal = prlo.idlocal)
            WHERE
                prlo.ativo = 'S' AND
                prlo.id_prova_presencial = ".$this->id;

        if($idsindicato) {
            $sql .= " AND l.idsindicato = ".$idsindicato;
        }

        $sql .= " ORDER BY prlo.idlocal ASC";
        $query = $this->executaSql($sql);

        while($linha = mysql_fetch_assoc($query)) {

            $locais[] = $linha;

            $arrayLocais1[] = $linha['idlocal'];
            $locaisforma1 = implode(',', $arrayLocais1);

            $arrayLocais2[] = $linha['idlocal'];

            $arrayLocais3[] = $linha['local_prova'];
            $locaisforma3 = implode(', ', $arrayLocais3);

        }

        if ($tipoRetorno == 1) {
            return $locaisforma1;
        } elseif ($tipoRetorno == 2) {
            return $arrayLocais2;
        } elseif ($tipoRetorno == 3) {
            return $locaisforma3;
        }
        return $locais;
    }

    function retornarEscolasProva($tipoRetorno = 4, $idsindicato = false) {
        $sql = "SELECT
                            prpo.idescola,
                            po.nome_fantasia as escola,
                            po.idsindicato
                    FROM provas_presenciais_escolas prpo
                    INNER JOIN escolas po ON (po.idescola = prpo.idescola)
                    WHERE
                        prpo.ativo = 'S' AND
                        prpo.id_prova_presencial = ".$this->id;
        if($idsindicato) {
            $sql .= " AND po.idsindicato = ".$idsindicato;
        }
        $sql .= " ORDER BY prpo.idescola ASC";
        $query = $this->executaSql($sql);

        while($linha = mysql_fetch_assoc($query)){
            $escolas[] = $linha;

            $arrayEscolas1[] = $linha['idescola'];
            $escolasforma1 = implode(',', $arrayEscolas1);

            $arrayEscolas2[$linha['idescola']] = $linha['idescola'];

            $arrayEscolas3[] = $linha['escola'];
            $escolasforma3 = implode(', ', $arrayEscolas3);
        }

        if ($tipoRetorno == 1) {
            return $escolasforma1;
        } elseif ($tipoRetorno == 2) {
            return $arrayEscolas2;
        } elseif ($tipoRetorno == 3) {
            return $escolasforma3;
        }
        return $escolas;
    }

    function retornarEscolaMatricula($idmatricula) {
        $this->sql = "SELECT po.idsindicato, m.idescola
                    FROM matriculas m
                    INNER JOIN escolas po ON (po.idescola = m.idescola)
                    WHERE
                        m.idmatricula = ".$idmatricula;
        $informacao = $this->retornarLinha($this->sql);
        return $informacao;
    }

    function retornarProvasDisponiveisAluno($matricula, $idescola_idlocal) {
        $provas = array();
        $data_atual = new DateTime();

        $acessoAva = $matricula->retornarAcessoAva();
        $ultima_data_prova = new DateTime($acessoAva['data_limite_acesso_ava']);
        $dias = (int)$acessoAva['dias_para_prova'];
        $ultima_data_prova->modify("+{$dias} days");
        $escolalocal = explode('|', $idescola_idlocal);

        if ($escolalocal[1] == 'escola') {
            $idescola = $escolalocal[0];
            $sql_escola_local = ' prpo.idescola = '.$idescola.' AND';
        } else {
            $idlocal = $escolalocal[0];
            $sql_escola_local = ' prl.idlocal = '.$idlocal.' AND';
        }

        $this->sql = 'SELECT
    						'.$this->campos.'
    					FROM
    						provas_presenciais pp
    						INNER JOIN matriculas_notas_tipos mnt ON (mnt.idtipo = pp.idtipo)
    						LEFT OUTER JOIN provas_presenciais_escolas prpo ON (prpo.id_prova_presencial = pp.id_prova_presencial and prpo.ativo = "S")
                            LEFT OUTER JOIN provas_presenciais_locais_provas prl ON (prl.id_prova_presencial = pp.id_prova_presencial and prl.ativo = "S")
    					WHERE
    						pp.data_realizacao >= "'.$data_atual->format('Y-m-d').'" AND
                            pp.data_realizacao <= "'.$ultima_data_prova->format('Y-m-d').'" AND
    						pp.ativo_painel = "S" AND
                            '.$sql_escola_local.'
    						pp.ativo = "S" AND
    						(
    							SELECT
    								ps.id_solicitacao_prova
    							FROM
    								provas_solicitadas ps
    							WHERE
    								ps.id_prova_presencial = pp.id_prova_presencial AND
    								ps.idmatricula = "'.(int) $matricula->Get('id').'" AND
    								(ps.situacao = "E" OR ps.situacao = "A") AND
    								ps.ativo = "S"
                                LIMIT 1
    						) IS NULL
                        GROUP BY pp.id_prova_presencial
         				ORDER BY pp.data_realizacao asc, mnt.nome ASC ';
        $query = $this->executaSql($this->sql);

        while ($prova = mysql_fetch_assoc($query)) {

            $this->id = $prova['id_prova_presencial'];

            if ($idescola) {
                $qtdeAlunosProva = $this->retornarQtdeAlunosProva($idescola);
                $qtdeAlunosEscolaLocal = $this->retornarQtdeMaximaProvaEscola($idescola);
            } else {
                $qtdeAlunosProva = $this->retornarQtdeAlunosProva(null, $idlocal);
                $qtdeAlunosEscolaLocal = $this->retornarQtdeMaximaProvaLocal($idlocal);
            }

            if ($qtdeAlunosProva < $qtdeAlunosEscolaLocal) {
                $provas[] = $prova;
            }

        }
        return json_encode($provas);
    }

    function retornarEscolasDisponiveisAluno($idsindicato, $id_prova_presencial = NULL) {
        $this->sql = "SELECT
                            {$this->campos}
                        FROM
                            escolas p
                            INNER JOIN provas_presenciais_escolas prpo ON (p.idescola = prpo.idescola and prpo.ativo = 'S')
                            INNER JOIN provas_presenciais pp ON (prpo.id_prova_presencial = pp.id_prova_presencial)
                        WHERE
                            p.idsindicato = ".$idsindicato." AND
                            pp.ativo_painel = 'S' AND
                            pp.ativo = 'S' AND
                            p.ativo = 'S' ";
        if ($id_prova_presencial) {
            $this->sql .= ' AND pp.id_prova_presencial = "'.$id_prova_presencial.'"';
        }

        $this->sql .= "GROUP BY p.idescola ";
        $this->ordem = "ASC";
        $this->ordem_campo = "p.nome_fantasia";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function retornarLocaisDisponiveisAluno($idsindicato, $id_prova_presencial = NULL) {
        $this->sql = "SELECT
                            {$this->campos}
                        FROM
                            locais_provas l
                            INNER JOIN provas_presenciais_locais_provas prl ON (l.idlocal = prl.idlocal and prl.ativo = 'S')
                            INNER JOIN provas_presenciais pp ON (prl.id_prova_presencial = pp.id_prova_presencial)
                        WHERE
                            l.idsindicato = ".$idsindicato." AND
                            pp.ativo_painel = 'S' AND
                            pp.ativo = 'S' AND
                            l.ativo = 'S' ";
        if ($id_prova_presencial) {
            $this->sql .= ' AND pp.id_prova_presencial = "'.$id_prova_presencial.'"';
        }

        $this->sql .= "GROUP BY l.idlocal ";
        $this->ordem = "ASC";
        $this->ordem_campo = "l.nome";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function retornarEscolasProvasDisponiveisAluno($idsindicato) {
        $this->sql = "SELECT
							{$this->campos}
						FROM
							escolas p
							INNER JOIN provas_presenciais_escolas prpo ON (p.idescola = prpo.idescola)
							INNER JOIN provas_presenciais pp ON (prpo.id_prova_presencial = pp.id_prova_presencial)
						WHERE
							p.idsindicato = ".$idsindicato." AND
							pp.data_realizacao >= '".date('Y-m-d')."' AND
							pp.ativo_painel = 'S' AND
							pp.ativo = 'S' AND
							p.ativo = 'S' AND
                            prpo.ativo = 'S'    ";

        $this->sql .= "GROUP BY p.idescola ";
        $this->ordem = "ASC";
        $this->ordem_campo = "p.nome_fantasia";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function retornarEscolasProvasDisponiveisGestor($idsindicato, $id_prova_presencial) {
        $this->sql = "SELECT
							{$this->campos}
						FROM
							escolas p
							INNER JOIN provas_presenciais_escolas prpo ON (p.idescola = prpo.idescola)
							INNER JOIN provas_presenciais pp ON (prpo.id_prova_presencial = pp.id_prova_presencial)
						WHERE
							p.idsindicato = ".$idsindicato." AND
							pp.data_realizacao >= '".date('Y-m-d')."' AND
							pp.ativo_painel = 'S' AND
							pp.ativo = 'S' AND
							p.ativo = 'S' AND
                            prpo.ativo = 'S' AND
                            pp.id_prova_presencial = '".$id_prova_presencial."'    ";


        if($this->gestor_sindicato <> 'S') {
            if (!$_SESSION['adm_sindicatos'])
                $_SESSION['adm_sindicatos'] = '0';

            $this->sql .= " AND
                                EXISTS ( SELECT p.idescola
                                    FROM
                                    escolas p
                                    INNER JOIN provas_presenciais_escolas ppp ON (ppp.idescola = p.idescola AND ppp.ativo = 'S')
                                    WHERE p.idsindicato in (".$_SESSION['adm_sindicatos'].")
                                    LIMIT 1
                                ) ";
        }

        $this->sql .= "GROUP BY p.idescola ";
        $this->ordem = "ASC";
        $this->ordem_campo = "p.nome_fantasia";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function retornarLocaisProvasDisponiveisAluno($idsindicato) {
        $this->sql = "SELECT
                            {$this->campos}
                        FROM
                            locais_provas l
                            INNER JOIN provas_presenciais_locais_provas prl ON (l.idlocal = prl.idlocal)
                            INNER JOIN provas_presenciais pp ON (prl.id_prova_presencial = pp.id_prova_presencial)
                        WHERE
                            l.idsindicato = ".$idsindicato." and
                            pp.data_realizacao >= '".date('Y-m-d')."' and
                            pp.ativo_painel = 'S' and
                            pp.ativo = 'S' and
                            l.ativo = 'S' ";

        $this->sql .= "GROUP BY l.idlocal ";
        $this->ordem = "ASC";
        $this->ordem_campo = "l.nome";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function retornarLocaisProvasDisponiveisGestor($idsindicato, $id_prova_presencial) {
        $this->sql = "SELECT
                            {$this->campos}
                        FROM
                            locais_provas l
                            INNER JOIN provas_presenciais_locais_provas prl ON (l.idlocal = prl.idlocal)
                            INNER JOIN provas_presenciais pp ON (prl.id_prova_presencial = pp.id_prova_presencial)
                        WHERE
                            l.idsindicato = ".$idsindicato." and
                            pp.data_realizacao >= '".date('Y-m-d')."' and
                            pp.ativo_painel = 'S' and
                            pp.ativo = 'S' and
                            l.ativo = 'S' AND
                            pp.id_prova_presencial = '".$id_prova_presencial."'  ";
        if($this->gestor_sindicato <> 'S') {
            if (!$_SESSION['adm_sindicatos'])
                $_SESSION['adm_sindicatos'] = '0';

            $this->sql .= " AND
                                EXISTS ( SELECT lp.idlocal
                                    FROM
                                    locais_provas lp
                                    INNER JOIN provas_presenciais_locais_provas pplp ON (pplp.idlocal = lp.idlocal AND pplp.ativo = 'S')
                                    WHERE lp.idsindicato in (".$_SESSION['adm_sindicatos'].")
                                    LIMIT 1
                                ) ";
        }

        $this->sql .= "GROUP BY l.idlocal ";
        $this->ordem = "ASC";
        $this->ordem_campo = "l.nome";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function Retornar() {
        if (!$this->id) {
            return false;
        }
        $this->sql = "SELECT ".$this->campos."
							FROM
							 provas_presenciais pr
						WHERE pr.ativo='S' AND pr.id_prova_presencial='".(int)$this->id."' ";
        $prova = $this->retornarLinha($this->sql);

        $escolas = $this->retornarEscolasProva(1);
        $locais = $this->retornarLocaisProva(1);

        if ($this->idusuario) {
            if ($escolas) {
                $this->sql = "SELECT ua.idusuario
                        FROM usuarios_adm ua
                            LEFT JOIN usuarios_adm_sindicatos uai
                            ON ua.idusuario = uai.idusuario AND uai.ativo = 'S'
                            LEFT JOIN escolas
                            ON escolas.idsindicato = uai.idsindicato AND escolas.ativo = 'S'
                        WHERE ua.idusuario = ".$this->idusuario."
                            AND (   ua.gestor_sindicato = 'S'
                                        or
                                    (   escolas.idescola IN(".$escolas.") AND
                                        uai.idusuario IS NOT NULL AND
                                        escolas.idsindicato IS NOT NULL
                                    )
                                )
                        LIMIT 1";
                $resultadoUsuario = $this->retornarLinha($this->sql);
                if (!$resultadoUsuario['idusuario']) {
                    $nenhumaProvaPorEscola = true;
                }
            } elseif ($locais) {
                $this->sql = "SELECT ua.idusuario
                            FROM usuarios_adm ua
                                LEFT JOIN usuarios_adm_sindicatos uai
                                ON ua.idusuario = uai.idusuario AND uai.ativo = 'S'
                                LEFT JOIN locais_provas
                                ON locais_provas.idsindicato = uai.idsindicato AND locais_provas.ativo = 'S'
                            WHERE ua.idusuario = ".$this->idusuario."
                                AND (   ua.gestor_sindicato = 'S'
                                            or
                                        (   locais_provas.idlocal IN(".$locais.") AND
                                            uai.idusuario IS NOT NULL AND
                                            locais_provas.idsindicato IS NOT NULL
                                        )
                                    )
                            LIMIT 1";
                $resultadoUsuario = $this->retornarLinha($this->sql);
                if (!$resultadoUsuario['idusuario']) {
                    $nenhumaProvaPorLocal = true;
                }
            }
            if ($nenhumaProvaPorEscola && $nenhumaProvaPorLocal) {
                $prova = null;
            }
        }
        $prova['escolas'] = $this->retornarEscolasProva(4);
        $prova['locais_provas'] = $this->retornarLocaisProva(4);
        return $prova;
    }

    public function retornarAlunosProva(){
        $this->sql = "SELECT
						ps.*,
						pe.idpessoa,
						pe.nome AS aluno,
						po.idsindicato,
						po.nome_fantasia as escola,
						m.idsindicato,
						c.nome as curso
					FROM
						provas_solicitadas ps
						INNER JOIN provas_presenciais pr ON ( ps.id_prova_presencial = pr.id_prova_presencial )
						INNER JOIN matriculas m ON ( m.idmatricula = ps.idmatricula )
						INNER JOIN escolas po ON ( m.idescola = po.idescola )
						INNER JOIN cursos c ON ( m.idcurso = c.idcurso )
						INNER JOIN pessoas pe ON ( pe.idpessoa = m.idpessoa )
					WHERE
						ps.id_prova_presencial ='".(int) $this->id."' AND
						ps.ativo = 'S' AND
						ps.situacao = 'A'
                    GROUP BY ps.idmatricula
                    ORDER BY pr.data_realizacao ";

        $query = $this->executaSql($this->sql);
        while ($aluno = mysql_fetch_assoc($query)) {

            $documentos_concatenados = "";
            $documentos = $this->retornarDocumentosPendentesAluno(
                $aluno['idmatricula'],
                $aluno['idcurso'],
                $aluno['idsindicato']
            );
            if (count($documentos) > 0) {
                foreach ($documentos as $key => $documento) {
                    if ($key == 0) {
                        $documentos_concatenados .= " ".$documento['nome'];
                    } else {
                        $documentos_concatenados .= ", ".$documento['nome'];
                    }
                }
                $aluno['documentos_pendentes'] = "(".
                                                 $documentos_concatenados." )" ;
            } else {
                $aluno['documentos_pendentes'] = 'Nenhum documento';
            }
            if ($this->id) {
                $this->campos = ' p.idescola, p.nome_fantasia as escola ';
                $aluno['escolas'] = $this->retornarEscolasDisponiveisAluno($aluno['idsindicato'], $aluno['id_prova_presencial']);
                $this->campos = ' l.idlocal, l.nome as local ';
                $aluno['locais_provas'] = $this->retornarLocaisDisponiveisAluno($aluno['idsindicato'], $aluno['id_prova_presencial']);
            }

            $alunos[] = $aluno;
        }
        return $alunos;
    }

    function retornarModelosProvaSindicato($idsindicato) {
        $sql = 'select * from modelos_prova where idsindicato = ' . $idsindicato . ' and ativo = "S" and ativo_painel = "S" ';
        $resultado = $this->executaSql($sql);
        while ($modelo = mysql_fetch_assoc($resultado)) {
            $modelos[] = $modelo;
        }
        return $modelos;
    }

    function retornarDisciplinasAluno($idmatricula) {
        $disciplinas = array();

        $this->sql = "SELECT
                        d.iddisciplina, d.nome, m.idmatricula
                      FROM
                        matriculas m
                        INNER JOIN ofertas_cursos_escolas ocp
                        ON (
                            m.idoferta = ocp.idoferta AND
                            m.idescola = ocp.idescola AND
                            m.idcurso = ocp.idcurso AND
                            ocp.ativo = 'S'
                            )
                        INNER JOIN curriculos_blocos cb
                        ON (
                            ocp.idcurriculo = cb.idcurriculo AND
                            cb.ativo = 'S'
                            )
                        INNER JOIN curriculos_blocos_disciplinas cbd
                        ON (
                            cbd.idbloco = cb.idbloco AND
                            cbd.ativo = 'S'
                            )
                        INNER JOIN disciplinas d
                        ON (cbd.iddisciplina = d.iddisciplina)
                        WHERE
                            m.ativo = 'S' AND
                            m.idmatricula = ".(int)$idmatricula;

        $this->ordem = "ASC";
        $this->limite = -1;
        $this->ordem_campo = "d.iddisciplina";
        $this->groupby = "d.iddisciplina";
        $disciplinas = $this->retornarLinhas();

        return $disciplinas;
    }

    function retornarDocumentosPendentesAluno($idmatricula, $idcurso, $idsindicato){
        $this->sql = "
            SELECT
                td.idtipo,
                td.nome,
                td.todos_cursos_obrigatorio
            FROM
                tipos_documentos td
            WHERE
                (
                    (
                        todos_cursos_obrigatorio = 'S'
                        or
                        (
                            SELECT
                                count(1)
                            FROM
                                tipos_documentos_cursos tdc
                            WHERE
                                tdc.ativo = 'S' and
                                tdc.idtipo = td.idtipo AND
                                tdc.idcurso = $idcurso
                        )
                    )
                    or
                    (
                        todas_sindicatos_obrigatorio = 'S'
                        or
                        (
                            SELECT
                                count(1)
                            FROM
                            tipos_documentos_sindicatos tdi
                            WHERE
                                tdi.ativo = 'S' and
                                tdi.idtipo = td.idtipo AND
                                tdi.idsindicato = $idsindicato
                        )
                    )
                )
                and (
                        SELECT iddocumento
                        FROM matriculas_documentos md
                        WHERE
                            md.idtipo = td.idtipo AND
                            md.idmatricula  = $idmatricula AND
                            md.situacao = 'aprovado' AND
                            md.ativo = 'S' AND
                            md.idtipo_associacao IS NULL
                        LIMIT 1
                ) IS NULL
                AND td.ativo = 'S' ";
        $this->ordem_campo = 'td.idtipo';
        $tipos_pendentes = $this->retornarLinhas();
        return $tipos_pendentes;
    }

    public function salvarComparecimentosProva() {
        foreach ($this->post['comparecimento_todos']  as  $id_solicitacao_prova) {

            $this->sql = 'SELECT * FROM
                            provas_solicitadas
                        WHERE id_solicitacao_prova = "'.$id_solicitacao_prova.'" ';
            $linhaAntiga = $this->retornarLinha($this->sql);

            $idescola = NULL;
            $idlocal = NULL;

            $idescola_idlocal = $this->post['idescola_idlocal'][$id_solicitacao_prova];
            $escolalocal = explode('|', $idescola_idlocal);

            if ($escolalocal[1] == 'escola') {
                $idescola = $escolalocal[0];
            } elseif ($escolalocal[1] == 'local') {
                $idlocal = $escolalocal[0];
            }

            if ($this->post['compareceu'][$id_solicitacao_prova]) {
                $compareceu = 'S';
            } else {
                $compareceu = 'N';
            }

            if ($linhaAntiga['compareceu'] == $compareceu &&
                $linhaAntiga['idescola'] == $idescola && $linhaAntiga['idlocal'] == $idlocal) {
                continue;
            }

            $this->sql = 'UPDATE
							provas_solicitadas
						SET
							compareceu = "'.$compareceu.'"';

            if ($idescola) {
                $this->sql .= ', idescola = "'.$idescola.'",
                                idlocal = NULL';
            } elseif($idlocal) {
                $this->sql .= ', idlocal = "'.$idlocal.'",
                                idescola = NULL';
            }

            $this->sql .= " WHERE
							id_solicitacao_prova = ".(int) $id_solicitacao_prova;

            if ($this->executaSql($this->sql)) {

                $sql = 'SELECT * FROM
                            provas_solicitadas
                        WHERE id_solicitacao_prova = "'.$id_solicitacao_prova.'" ';
                $linhaNova = $this->retornarLinha($sql);

                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 2;
                $this->monitora_onde = 160;
                $this->monitora_qual = $id_solicitacao_prova;
                $this->monitora_dadosantigos = $linhaAntiga;
                $this->monitora_dadosnovos = $linhaNova;
                $this->Monitora();

            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;

    }

    function Cadastrar() {

        if (count($this->post['idescola']) == 0 && count($this->post['idlocal']) == 0) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_escolas_locais_vazio';
            return $this->retorno;
        }

        $datasRealizacao = explode(',', $this->post['data_realizacao']);
        if (count($datasRealizacao) == 0) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_data_realizacao_vazio';
            return $this->retorno;
        }

        //Cria array secundário para escolas e locais, para inserir mais um valor 0, pois caso esteja vazio não dará erro no próximo select
        $idEscolas[] = 0;
        if (count($this->post['idescola']) > 0){
            $idEscolas = $this->post['idescola'];
        }

        $idLocais[] = 0;
        if (count($this->post['idlocal']) > 0){
            $idLocais = $this->post['idlocal'];
        }

        foreach ($datasRealizacao as $dataRealizacao) {

            $this->post['data_realizacao'] = formataData($dataRealizacao, "en", 0);

            /*Verifica se já existe uma prova agendada para o mesmo dia e com as
            mesmas características da que se deseja inserir*/
            $this->sql = "SELECT
                                DISTINCT(pp.id_prova_presencial)
                            FROM
                                provas_presenciais pp
                                LEFT OUTER JOIN provas_presenciais_escolas ppp ON (ppp.id_prova_presencial = pp.id_prova_presencial AND ppp.ativo = 'S')
                                LEFT OUTER JOIN provas_presenciais_locais_provas pplp ON (pplp.id_prova_presencial = pp.id_prova_presencial AND pplp.ativo = 'S')
                            WHERE
                                pp.data_realizacao = '".$this->post['data_realizacao']."' AND
                                pp.hora_realizacao_de = '".$this->post['hora_realizacao_de']."' AND
                                pp.hora_realizacao_ate = '".$this->post['hora_realizacao_ate']."' AND
                                pp.idtipo = ".$this->post['idtipo']." AND
                                pp.ativo = 'S' AND
                                (
                                    ppp.idescola IN (".implode(',',$idEscolas).") ||
                                    pplp.idlocal IN (".implode(',',$idLocais).")
                                )
                            LIMIT 1";//echo '<pre>'.$this->sql;exit;
            $verifica_cadastrado = $this->retornarLinha($this->sql);

            if ($verifica_cadastrado['id_prova_presencial']) {
                $this->retorno["sucesso"] = false;
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = "erro_prova_cadastrada";
                return $this->retorno;
            }
            /*$erros = $this->BuscarErros();
            if ($erros) {
                return $erros;
            }*/

            $this->sql = "INSERT INTO provas_presenciais
                        SET
                            data_realizacao = '".$this->post['data_realizacao']."',
                            hora_realizacao_de = '".$this->post['hora_realizacao_de']."',
                            hora_realizacao_ate = '".$this->post['hora_realizacao_ate']."',
                            idtipo = ".$this->post['idtipo'].",
                            observacoes = '".$this->post['observacoes']."',
                            ativo_painel = '".$this->post['ativo_painel']."',
                            ativo = 'S',
                            data_cad = now()";

            if (!$this->executaSql($this->sql)) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_cadastrar_prova_presencial';
                return $this->retorno;
            }

            $id_prova_presencial = mysql_insert_id();
            $this->monitora_oque = 1;
            $this->monitora_onde = 159;
            $this->monitora_qual = $id_prova_presencial;
            $this->Monitora();

            $this->retorno['sucesso'] = true;
            $this->retorno["id"] = $id_prova_presencial;

            foreach ($this->post['idescola'] as $idescola) {
                $this->sql = "INSERT INTO
                                        provas_presenciais_escolas
                                    SET
                                        ativo = 'S',
                                        data_cad = NOW(),
                                        id_prova_presencial = ".(int) $id_prova_presencial.",
                                        idescola = ".(int) $idescola;
                if ($this->executaSql($this->sql)) {
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 187;
                    $this->monitora_qual = mysql_insert_id();
                    $this->Monitora();
                    $this->retorno['sucesso'] = true;
                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_salvar_escola';
                }
            }

            foreach ($this->post['idlocal'] as $idlocal) {
                $this->sql = "INSERT INTO
                                        provas_presenciais_locais_provas
                                    SET
                                        ativo = 'S',
                                        data_cad = NOW(),
                                        id_prova_presencial = ".(int) $id_prova_presencial.",
                                        idlocal = ".(int) $idlocal;

                if ($this->executaSql($this->sql)) {
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 216;
                    $this->monitora_qual = mysql_insert_id();
                    $this->Monitora();
                    $this->retorno['sucesso'] = true;
                } else {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_salvar_local';
                }
            }

        }

        return $this->retorno;
    }

    private function SalvarEscolas()
    {
        $escolasAntigos = array();
        $escolasAntigos = $this->retornarEscolasProva(2);

        $diferenca1 = array_diff($escolasAntigos, $this->post['idescola']);
        $diferenca2 = array_diff($this->post['idescola'], $escolasAntigos);

        $diferenca = array();
        $diferenca = array_merge($diferenca1, $diferenca2);

        if (!is_array($diferenca) && !is_array($escolasAntigos)) {
            $diferenca = $this->post['idescola'];
        } elseif (!is_array($diferenca) && !is_array($this->post['idescola'])) {
            $diferenca = $escolasAntigos;
        }

        foreach ($diferenca as $idescola) {

            if (is_numeric(array_search($idescola, $this->post['idescola']))) {
                $associacaoEscolaInativa = $this->consultaAssociacaoEscola($idescola);
                if ($associacaoEscolaInativa['id_prova_escola']) {
                    $this->sql = 'UPDATE provas_presenciais_escolas SET
                                            ativo = "S"
                                        WHERE
                                            id_prova_escola =  "' . $associacaoEscolaInativa['id_prova_escola'] . '"';
                    if (!$this->executaSql($this->sql)) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_alterar_escola';
                        return $this->retorno;
                    }
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 187;
                    $this->monitora_qual = $associacaoEscolaInativa['id_prova_escola'];
                    $this->Monitora();
                    $this->retorno['sucesso'] = true;

                } else {

                    $this->sql = "INSERT INTO
                                    provas_presenciais_escolas
                                SET
                                    ativo = 'S',
                                    data_cad = NOW(),
                                    id_prova_presencial = ".(int) $this->id.",
                                    idescola = ".(int) $idescola;
                    if (! $this->executaSql($this->sql)) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_salvar_escola';
                        $this->executaSql('ROLLBACK');
                        return $this->retorno;
                    }
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 187;
                    $this->monitora_qual = mysql_insert_id();
                    $this->Monitora();
                    $this->retorno['sucesso'] = true;

                }

            } elseif (is_numeric(array_search($idescola, $escolasAntigos))) {
                $this->sql = 'UPDATE provas_presenciais_escolas SET
                                        ativo = "N"
                                    WHERE
                                        id_prova_presencial = "' . $this->id . '" AND
                                        idescola =  "' . $idescola . '"';
                if (! $this->executaSql($this->sql)) {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_alterar_escola';
                    $this->executaSql('ROLLBACK');
                    return $this->retorno;
                }
                $sql = 'SELECT
                            id_prova_escola
                        FROM
                            provas_presenciais_escolas
                        WHERE
                            id_prova_presencial = "' . $this->id . '" AND
                            idescola =  "' . $idescola . '"';
                $alterado = $this->retornarLinha($sql);
                $this->monitora_oque = 3;
                $this->monitora_onde = 187;
                $this->monitora_qual = $alterado['id_prova_escola'];
                $this->Monitora();
                $this->retorno['sucesso'] = true;

            }
        }
        return $this->retorno;
    }

    private function salvarLocais()
    {
        $locaisAntigos = array();
        $locaisAntigos = $this->retornarLocaisProva(2);

        $diferenca = array();
        $diferenca1 = array_diff($locaisAntigos, $this->post['idlocal']);
        $diferenca2 = array_diff($this->post['idlocal'], $locaisAntigos);
        $diferenca = array_merge($diferenca1, $diferenca2);

        if (!is_array($diferenca) && !is_array($locaisAntigos)) {
            $diferenca = $this->post['idlocal'];
        } elseif (!is_array($diferenca) && !is_array($this->post['idlocal'])) {
            $diferenca = $locaisAntigos;
        }

        foreach ($diferenca as $idlocal) {

            if (is_numeric(array_search($idlocal, $this->post['idlocal']))) {
                $associacaoLocalInativa = $this->consultaAssociacaoLocal($idlocal);
                if ($associacaoLocalInativa['id_prova_local']) {

                    $this->sql = 'UPDATE provas_presenciais_locais_provas SET
                                            ativo = "S"
                                        WHERE
                                            id_prova_local =  "' . $associacaoLocalInativa['id_prova_local'] . '"';

                    if (!$this->executaSql($this->sql)) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_alterar_local';
                        $this->executaSql('ROLLBACK');
                        return $this->retorno;
                    }
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 216;
                    $this->monitora_qual = $associacaoLocalInativa['id_prova_local'];
                    $this->Monitora();
                    $this->retorno['sucesso'] = true;

                } else {

                    $this->sql = "INSERT INTO
                                    provas_presenciais_locais_provas
                                SET
                                    ativo = 'S',
                                    data_cad = NOW(),
                                    id_prova_presencial = ".(int) $this->id.",
                                    idlocal = ".(int) $idlocal;

                    if (! $this->executaSql($this->sql)) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_salvar_local';
                        $this->executaSql('ROLLBACK');
                        return $this->retorno;
                    }
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 216;
                    $this->monitora_qual = mysql_insert_id();
                    $this->Monitora();
                    $this->retorno['sucesso'] = true;

                }

            } elseif (is_numeric(array_search($idlocal, $locaisAntigos))) {

                $this->sql = 'UPDATE provas_presenciais_locais_provas SET
                                        ativo = "N"
                                    WHERE
                                        id_prova_presencial = "' . $this->id . '" AND
                                        idlocal =  "' . $idlocal . '"';

                if (! $this->executaSql($this->sql)) {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_alterar_local';
                    $this->executaSql('ROLLBACK');
                    return $this->retorno;
                }
                $sql = 'SELECT
                            id_prova_local
                        FROM provas_presenciais_locais_provas
                        WHERE
                            id_prova_presencial = "' . $this->id . '" AND
                            idlocal =  "' . $idlocal . '" ';
                $alterado = $this->retornarLinha($sql);

                $this->monitora_oque = 3;
                $this->monitora_onde = 216;
                $this->monitora_qual = $alterado['id_prova_local'];
                $this->Monitora();
                $this->retorno['sucesso'] = true;

            }
        }
        return $this->retorno;

    }


    function Modificar() {

        if (count($this->post['idescola']) == 0 && count($this->post['idlocal']) == 0) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_escolas_locais_vazio';
            return $this->retorno;
        }
        $this->id = $this->post['id_prova_presencial'];
        $linhaAntiga = $this->Retornar();

        //Cria array secundário para escolas e locais, para inserir mais um valor 0, pois caso esteja vazio não dará erro no próximo select
        $idEscolas[] = 0;
        if (count($this->post['idescola']) > 0){
            $idEscolas = $this->post['idescola'];
        }

        $idLocais[] = 0;
        if (count($this->post['idlocal']) > 0){
            $idLocais = $this->post['idlocal'];
        }

        $this->sql = "SELECT
                            DISTINCT(pp.id_prova_presencial)
                        FROM
                            provas_presenciais pp
                            LEFT OUTER JOIN provas_presenciais_escolas ppp ON (ppp.id_prova_presencial = pp.id_prova_presencial AND ppp.ativo = 'S')
                            LEFT OUTER JOIN provas_presenciais_locais_provas pplp ON (pplp.id_prova_presencial = pp.id_prova_presencial AND pplp.ativo = 'S')
                        WHERE
                            pp.data_realizacao = '".formataData($this->post['data_realizacao'], "en", 0)."' AND
                            pp.hora_realizacao_de = '".$this->post['hora_realizacao_de']."' AND
                            pp.hora_realizacao_ate = '".$this->post['hora_realizacao_ate']."' AND
                            pp.idtipo = ".$this->post['idtipo']." AND
                            pp.ativo = 'S' AND
                            (
                                ppp.idescola IN (".implode(',',$idEscolas).") ||
                                pplp.idlocal IN (".implode(',',$idLocais).")
                            ) AND
                            pp.id_prova_presencial <> '".(int) $this->post['id_prova_presencial']."'
                        LIMIT 1";//echo '<pre>'.$this->sql;exit;
        $verifica_cadastrado = $this->retornarLinha($this->sql);

        if ($verifica_cadastrado['id_prova_presencial']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = "erro_prova_cadastrada";
            return $this->retorno;
        }

        $this->executaSql('START TRANSACTION');
        $this->nao_monitara = true;

        $salvar = $this->SalvarDados();

        if (! $salvar['sucesso']) {
            return $salvar;
        }

        $linhaNova = $this->Retornar();

        if (array_diff($linhaNova, $linhaAntiga)) {
            $this->monitora_oque = 2;
            $this->monitora_qual = $this->id;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

        }

        $this->nao_monitara = false;
        $salvarEscolas = $this->SalvarEscolas();
        if (! $salvarEscolas) {
            return $salvarEscolas;
        }
        $salvarLocais = $this->salvarLocais();
        if (! $salvarLocais) {
            return $salvarLocais;
        }
        $this->executaSql('COMMIT');
        return $salvar;
    }

    public function consultaAssociacaoEscola($idescola)
    {
        $sql = 'SELECT
                    prpo.id_prova_escola
                FROM
                    provas_presenciais_escolas prpo
                WHERE
                    prpo.idescola = "'.$idescola.'" AND
                    prpo.id_prova_presencial ="'.$this->id.'" LIMIT 1 ';
        return $this->retornarLinha($sql);
    }

    public function consultaAssociacaoLocal($idlocal)
    {
        $sql = 'SELECT
                    prlo.id_prova_local
                FROM
                    provas_presenciais_locais_provas prlo
                WHERE
                    prlo.idlocal = "'.$idlocal.'" AND
                    prlo.id_prova_presencial ="'.$this->id.'" LIMIT 1 ';
        return $this->retornarLinha($sql);
    }

    function Remover() {
        return $this->RemoverDados();
    }

    function BuscarMatricula() {

        $sindicatos = $this->retornarSindicatosEscolasLocaisProva();

        $this->sql = "SELECT
						CONCAT(m.idmatricula,'|',m.idcurso,'|',m.idescola) AS 'key',
						CONCAT(p.nome,' - ',m.idmatricula) AS value
					FROM
						matriculas m
						INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
					WHERE
						(p.nome like '%".$_GET["tag"]."%' OR m.idmatricula like '%".$_GET["tag"]."%') AND
						 m.ativo = 'S' AND
						 m.idsindicato in(".$sindicatos.") AND
						 m.idmatricula NOT IN (SELECT idmatricula FROM provas_solicitadas WHERE id_prova_presencial = ".$this->id." AND ativo = 'S' AND situacao = 'A')";

        if ($_SESSION["adm_gestor_sindicato"] <> "S")
            $this->sql .= " AND m.idsindicato IN (".$_SESSION["adm_sindicatos"].")";

        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }
    function retornarSituacaoEspera($idmatricula) {
        $this->sql = 'SELECT ps.id_solicitacao_prova FROM
                        provas_solicitadas ps
                    WHERE
                        ps.id_prova_presencial = '.$this->id.' AND
                        ps.ativo = "S" AND
                        ps.idmatricula = '.$idmatricula.' AND
                        ps.situacao = "E"
                    LIMIT 1';
        return $this->retornarLinha($this->sql);
    }

    function aprovarSolicitacaoEspera($id_solicitacao_prova, $idcurso) {

        $this->sql = 'UPDATE
                            provas_solicitadas
                        SET
                            situacao = "A",
                            idusuario = "'.$this->idusuario.'",
                            idcurso = "'.$idcurso.'"
                        WHERE
                         id_solicitacao_prova = '.(int) $id_solicitacao_prova;

        $aprovar = $this->executaSql($this->sql);

        /*$this->sql = "INSERT INTO
                            mensagens_alerta(tipo_alerta, id_solicitacao_prova, idmatricula)(SELECT 'agendamento',".$id_solicitacao_prova.", m.idmatricula
                                FROM matriculas m INNER JOIN provas_solicitadas ps ON (ps.idmatricula = m.idmatricula)
                        WHERE
                         id_solicitacao_prova = ".(int) $id_solicitacao_prova.")";
        $this->executaSql($this->sql);*/

        if(! $aprovar){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
            return $this->retorno;
        }

        $this->monitora_oque = 11;
        $this->monitora_onde = 160;
        $this->monitora_qual = (int) $id_solicitacao_prova;
        $this->Monitora();

        $objSolicitacao = new Provas_Solicitadas();
        $objSolicitacao->Set('idusuario',$this->idusuario);
        $objSolicitacao->Set('id', $id_solicitacao_prova);

        $envio_email = $objSolicitacao->enviarEmailSituacaoSolicitacao(true, true);
        if (! $envio_email['sucesso']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = "mensagem_erro_envio_email";
            return $this->retorno;
        }
        $this->retorno["sucesso"] = true;
        return $this->retorno;

    }

    /*function adicionarMatriculas($idmatricula,$idcurso,$idescola) {
        $solicitacao = $this->retornarSituacaoEspera($idmatricula);
        if ($solicitacao['id_solicitacao_prova']) {
            $aprovou = $this->aprovarSolicitacaoEspera($solicitacao['id_solicitacao_prova'], $idcurso);
            if (!$aprovou) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_adicionar_matricula';
                return $this->retorno;
            }
            $id_solicitacao_prova = mysql_insert_id();
            $this->monitora_oque = 1;
            $this->monitora_onde = 160;
            $this->monitora_qual = $id_solicitacao_prova;
            $this->Monitora();
            $this->retorno['sucesso'] = true;
            $this->retorno["id"] = $id_solicitacao_prova;
            return $this->retorno;
        }
        $this->sql = "INSERT
                        provas_solicitadas
                    SET
                        idmatricula = ".$idmatricula.",
                        idcurso = ".$idcurso.",
                        idescola = ".$idescola.",
                        id_prova_presencial = ".$this->id.",
                        situacao = 'A',
                        data_cad = now(),
                        idusuario = ".$this->idusuario."";
        if (!$this->executaSql($this->sql)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_adicionar_matricula';
            return $this->retorno;
        }
        $id_solicitacao_prova = mysql_insert_id();
        $this->monitora_oque = 11;
        $this->monitora_onde = 160;
        $this->monitora_qual = $id_solicitacao_prova;
        $this->Monitora();
        $objSolicitacao = new Provas_Solicitadas();
        $objSolicitacao->Set('idusuario',$this->idusuario);
        $objSolicitacao->Set('id', $id_solicitacao_prova);
        $envio_email = $objSolicitacao->enviarEmailSituacaoSolicitacao(true, true);
        if (! $envio_email['sucesso']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = "mensagem_erro_envio_email";
            return $this->retorno;
        }
        $this->retorno['sucesso'] = true;
        $this->retorno["id"] = $id_solicitacao_prova;
        return $this->retorno;
    }*/

    function adicionarMatriculas($idmatricula,$idcurso,$idescola_idlocal) {

        $solicitacao = $this->retornarSituacaoEspera($idmatricula);

        if ($solicitacao['id_solicitacao_prova']) {
            $aprovou = $this->aprovarSolicitacaoEspera($solicitacao['id_solicitacao_prova'], $idcurso);
            if (!$aprovou) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_adicionar_matricula';
                return $this->retorno;
            }

            $id_solicitacao_prova = mysql_insert_id();
            $this->monitora_oque = 1;
            $this->monitora_onde = 160;
            $this->monitora_qual = $id_solicitacao_prova;
            $this->Monitora();

            $this->retorno['sucesso'] = true;
            $this->retorno["id"] = $id_solicitacao_prova;

            return $this->retorno;

        }

        $this->sql = "INSERT
						provas_solicitadas
					SET
						idmatricula = ".$idmatricula.",
						idcurso = ".$idcurso.",
						id_prova_presencial = ".$this->id.",
						situacao = 'A',
						data_cad = now(),
						idusuario = ".$this->idusuario."";

        $dados_escola_local = explode('|',$idescola_idlocal);
        if ($dados_escola_local[1] == 'local')
            $this->sql .= ', idlocal = '.(int)$dados_escola_local[0].' ';
        else
            $this->sql .= ', idescola = '.(int)$dados_escola_local[0].' ';

        if (!$this->executaSql($this->sql)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_adicionar_matricula';
            return $this->retorno;
        }
        $id_solicitacao_prova = mysql_insert_id();
        $this->monitora_oque = 11;
        $this->monitora_onde = 160;
        $this->monitora_qual = $id_solicitacao_prova;
        $this->Monitora();

        foreach($this->post['disciplinas'] as $disc) {
            $sql = 'insert into provas_solicitadas_disciplinas set id_solicitacao_prova = "' . $id_solicitacao_prova . '", data_cad = NOW(), ativo = "S", iddisciplina = "' . $disc . '" ';
            if (!$this->executaSql($sql)) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_adicionar_matricula';
                return $this->retorno;
            }
        }

        $objSolicitacao = new Provas_Solicitadas();
        $objSolicitacao->Set('idusuario',$this->idusuario);
        $objSolicitacao->Set('id', $id_solicitacao_prova);

        $envio_email = $objSolicitacao->enviarEmailSituacaoSolicitacao(true, true);
        if (! $envio_email['sucesso']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = "mensagem_erro_envio_email";
            return $this->retorno;
        }

        $this->retorno['sucesso'] = true;
        $this->retorno["id"] = $id_solicitacao_prova;
        return $this->retorno;
    }

}

?>
