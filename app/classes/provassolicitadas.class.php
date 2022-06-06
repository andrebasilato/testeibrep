<?php
class Provas_Solicitadas extends Core
{
    public $idmatricula = null;
    public $idcurso = null;
    public $idsindicato = null;

    public function listarTodas()
    {
        $this->campos = 'DISTINCT(ps.id_solicitacao_prova),' . $this->campos;

        $this->sql = "SELECT
                            {$this->campos}
                        FROM
                            provas_solicitadas ps
                            INNER JOIN matriculas m ON (ps.idmatricula = m.idmatricula)
                            INNER JOIN pessoas pe ON (m.idpessoa = pe.idpessoa)
                            LEFT JOIN provas_presenciais pp ON (ps.id_prova_presencial = pp.id_prova_presencial)
                            INNER JOIN cursos c ON (c.idcurso = ps.idcurso)
                            LEFT JOIN escolas po ON (po.idescola = ps.idescola)
                            LEFT JOIN locais_provas l ON (l.idlocal = ps.idlocal)
                            LEFT JOIN motivos_cancelamento_solicitacao_prova mc ON (mc.idmotivo = ps.idmotivo)
                            LEFT JOIN provas_solicitadas_disciplinas psd ON (psd.id_solicitacao_prova = ps.id_solicitacao_prova AND psd.ativo = 'S')
                            LEFT JOIN disciplinas d ON (d.iddisciplina = psd.iddisciplina)
                        WHERE
                            ps.ativo = 'S' ";

        if ($this->idusuario) {
            $this->sql .= " AND (
                                    SELECT
                                        ua.idusuario
                                    FROM
                                        usuarios_adm ua
                                        LEFT JOIN usuarios_adm_sindicatos uai ON (ua.idusuario = uai.idusuario AND uai.ativo = 'S')
                                    WHERE
                                        ua.idusuario = ".$this->idusuario."
                                        AND (
                                                ua.gestor_sindicato = 'S' OR
                                                (
                                                    (
                                                        uai.idsindicato = po.idsindicato OR
                                                        uai.idsindicato = l.idsindicato
                                                    ) AND
                                                    uai.idusuario IS NOT NULL
                                                )
                                            )
                                    LIMIT 1
                                ) IS NOT NULL";
        }

        if (is_array($_GET["q"])) {
            foreach ($_GET["q"] as $campo => $valor) {

                $campo = explode("|", $campo);
                $valor = str_replace("'", "", $valor);

                if (($valor || $valor === "0") and $valor <> "todos") {

                    if ($campo[0] == 1) {
                        $this->sql .= " AND " . $campo[1] . " = '" . $valor . "' ";
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);

                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= " AND " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " AND date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    } elseif ($campo[0] == 4) {
                        $this->sql .= " AND date_format(" . $campo[1] . ",'%Y-%m-%d') <= '" . $valor . "' ";
                    } elseif ($campo[0] == 5) {
                        $this->sql .= " AND date_format(" . $campo[1] . ",'%Y-%m-%d') >= '" . $valor . "' ";
                    } elseif ($campo[0] == 6) {
                        $this->sql .= " AND (po.nome_fantasia like '%" . $valor . "%' or l.nome like '%" . $valor . "%') ";
                    } elseif ($campo[0] == 7) {
                        $this->sql .= " AND d.iddisciplina = '" . $valor . "'";
                    }

                }
            }
        }

        if ($this->idmatricula) {
            $this->sql .= "AND ps.idmatricula = ".$this->idmatricula;
        }

        $this->groupby = 'DISTINCT(ps.id_solicitacao_prova)';
        $solicitacoes = $this->retornarLinhas();

        foreach ($solicitacoes as $indice => $solicitacao) {
            $solicitacao['disciplinas'] = $this->retornarDisciplinasSolicitacao($solicitacao['id_solicitacao_prova'], false);
            if ($solicitacao['escola']) {
                $solicitacao['escola_local'] = $solicitacao['escola'];
            }else {
                $solicitacao['escola_local'] = $solicitacao['local'];
            }
            $retorno[] = $solicitacao;
        }

        return $retorno;

    }

    public function retornarDisciplinas($idsolicitacao) {
        $this->sql = 'SELECT
                            d.iddisciplina
                        FROM
                            provas_solicitadas_disciplinas psd
                        INNER JOIN
                            disciplinas d ON ( d.iddisciplina = psd.iddisciplina )
                        WHERE
                            psd.ativo =  "S" AND
                            psd.id_solicitacao_prova ="'.$idsolicitacao.'"
                        group by d.iddisciplina ORDER BY psd.id_solicitacao_prova_disciplina';
        $query = $this->executaSql($this->sql);
        while($disciplina = mysql_fetch_assoc($query)){
            $disciplinas[$disciplina['iddisciplina']] = $disciplina['iddisciplina'];
        }
        return $disciplinas;
    }

    public function retornarDisciplinasSolicitacao($idsolicitacao, $likeArray = true) {
        $this->sql = 'SELECT
                            d.iddisciplina,
                            d.nome
                        FROM
                            provas_solicitadas_disciplinas psd
                        INNER JOIN
                            disciplinas d ON ( d.iddisciplina = psd.iddisciplina )
                        WHERE
                            psd.ativo =  "S" AND
                            psd.id_solicitacao_prova ="'.$idsolicitacao.'" ';
        $limiteAnterior = $this->limite;
        $ordemAnterior = $this->ordem_campo;
        $groubyAnterior = $this->groupby;
        $totalAnterior = $this->total;
        $this->limite = -1;
        $this->groupby = 'd.iddisciplina';
        $this->ordem_campo = 'id_solicitacao_prova_disciplina';
        $disciplinas = $this->retornarLinhas();

        if (! $likeArray) {
            $values = array_map('array_pop', $disciplinas);

            $disciplinas = implode(", ", $values);
        }
        $this->limite = $limiteAnterior;
        $this->groupby = $groubyAnterior;
        $this->ordem_campo = $ordemAnterior;
        $this->total = $totalAnterior;
        return $disciplinas;
    }

    public function retornar()
    {
        $this->set(
            'sql',
            "SELECT {$this->campos}
                FROM provas_solicitadas ps
                INNER JOIN matriculas m
                    ON ps.idmatricula = m.idmatricula
                INNER JOIN pessoas pe
                    ON m.idpessoa = pe.idpessoa
                LEFT JOIN provas_presenciais pp
                    ON ps.id_prova_presencial = pp.id_prova_presencial
                INNER JOIN ofertas o
                    ON o.idoferta = m.idoferta
                INNER JOIN cursos c
                    ON c.idcurso = ps.idcurso
                LEFT OUTER JOIN escolas po
                    ON po.idescola = ps.idescola
                LEFT OUTER JOIN locais_provas l
                    ON l.idlocal = ps.idlocal
                INNER JOIN sindicatos i
                    ON (i.idsindicato = po.idsindicato or l.idsindicato = i.idsindicato)
                LEFT OUTER JOIN motivos_cancelamento_solicitacao_prova mc
                    ON mc.idmotivo = ps.idmotivo
            WHERE ps.ativo = 'S' AND ps.id_solicitacao_prova = '{$this->id}'"
        );
        return $this->retornarLinha($this->get('sql'));
    }

    public function retornarCursoEscola(){
        $this->sql = "SELECT m.idescola,
                            m.idcurso,
							m.idsindicato,
                            lp.quantidade_pessoas_comportadas as qtde_pessoas
                    FROM matriculas m
                    INNER JOIN escolas po
                        ON (po.idescola = m.idescola)
                    LEFT JOIN locais_provas lp
                        ON (po.idescola = lp.idescola)
                    WHERE
                        m.idmatricula = '".(int) $this->idmatricula."'";

        return $this->retornarLinha($this->sql);
    }

    public function arquivosObrigatoriosAssociados($idMatricula, $idSindicato)
    {
        $this->sql = "SELECT
            td.idtipo
          FROM
            tipos_documentos td
          where
            td.ativo = 'S' and
            (td.idtipo in(SELECT idtipo FROM tipos_documentos_sindicatos_agendamento where idtipo = td.idtipo and idsindicato = " . $idSindicato . " and ativo = 'S')
            or
            td.todas_sindicatos_obrigatorio_agendamento = 'S')
          group by
            td.idtipo";
        $this->limite = -1;
        $this->ordem_campo = false;
        $this->ordem = false;
        $tipos = $this->retornarLinhas();

        if (!count($tipos)) {
            return true;
        }

        foreach ($tipos as $tipo) {
            $this->sql = "SELECT count(*) as total FROM matriculas_documentos where idmatricula = " . $idMatricula . " and idtipo = " . $tipo["idtipo"] . " and ativo = 'S' and situacao = 'aprovado' and idtipo_associacao is null";
            $totalDocumento = $this->retornarLinha($this->sql);
            if ($totalDocumento["total"] <= 0) {
                return false;
            }
        }
        return true;
    }

    public function salvarSolicitacao()
    {
        if (verificaPermissaoAcesso(true)) {
            if (!$this->post['id_prova_presencial'] || !$this->post['idescola_idlocal']) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_prova_vazia';
                return $this->retorno;
            }
            $informacoes = $this->retornarCursoEscola();

            if (!count($this->post['disciplinas'])) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_sem_disciplinas';
                $this->executaSql('ROLLBACK');
                return $this->retorno;
            }

            if (!$this->arquivosObrigatoriosAssociados($this->idmatricula, $informacoes['idsindicato'])) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'ter_documento_obrigatorios';
                return $this->retorno;
            }

            $escolaLocal = explode('|', $this->post['idescola_idlocal']);
            if ($escolaLocal[1] == 'escola') {
                $idescola = $escolaLocal[0];
            } else {
                $idlocal = $escolaLocal[0];
            }

            $this->executaSql('START TRANSACTION');

            $this->sql = 'INSERT INTO
                                provas_solicitadas
                            SET
                                data_cad = NOW(),
                                ativo = "S",
                                situacao = "E",
                                idmatricula = "'.(int) $this->idmatricula.'",
                                idcurso = "'.$informacoes['idcurso'].'",
                                id_prova_presencial = "'.$this->post["id_prova_presencial"].'" ';
            if ($idescola)  {
                $this->sql .= ', idescola = "'.$idescola.'" ';
            } elseif ($idlocal)  {
                $this->sql .= ', idlocal = "'.$idlocal.'" ';
            }

            if ($this->executaSql($this->sql)) {
                $id_solicitacao_prova = mysql_insert_id();
                foreach ($this->post['disciplinas'] as $disciplina) {
                    $sql_disciplinas = 'INSERT INTO provas_solicitadas_disciplinas SET
											id_solicitacao_prova = "' . $id_solicitacao_prova . '",
											iddisciplina = 	"' . $disciplina . '",
											ativo = "S",
											data_cad = NOW() ';
                    $resultado = $this->executaSql($sql_disciplinas);
                    if (!$resultado) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_inserir_disciplina';
                        $this->executaSql('ROLLBACK');
                        return $this->retorno;
                    }
                }

                $this->monitora_oque = 1;
                $this->monitora_onde = 160;
                $this->monitora_qual = $id_solicitacao_prova;
                $this->Monitora();
                $this->retorno['sucesso'] = true;

                $this->executaSql('COMMIT');
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_salvar_solicitacao';
            }
            return $this->retorno;
        }
    }

    public function consultaAssociacaoDisciplina($iddisciplina)
    {
        $sql = 'SELECT
                    psd.id_solicitacao_prova_disciplina
                FROM
                    provas_solicitadas_disciplinas psd
                INNER JOIN
                    disciplinas d ON ( d.iddisciplina = psd.iddisciplina )
                WHERE
                    psd.iddisciplina = "'.$iddisciplina.'" AND
                    psd.id_solicitacao_prova ="'.$this->id.'" LIMIT 1 ';
        return $this->retornarLinha($sql);
    }

    public function associarDisciplinas() {

        if (! count($this->post['idmatricula'])) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'erro_idmatricula_vazio';
            return $retorno;
        }

        if (!$this->post['id_solicitacao_prova']) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'erro_idsolicitacao_vazio';
            return $retorno;
        }

        if (! count($this->post['disciplinas'])) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'erro_disciplinas_vazio';
            return $retorno;
        }

        $this->executaSql('START TRANSACTION');

        $disciplinasAntigas = array();
        $disciplinasAntigas = $this->retornarDisciplinas($this->id);

        $diferenca = array();
        $diferenca1 = array_diff($disciplinasAntigas, $this->post['disciplinas']);
        $diferenca2 = array_diff($this->post['disciplinas'], $disciplinasAntigas);

        $diferenca = array_merge($diferenca1, $diferenca2);


        if (! is_array($diferenca) && ! count($disciplinasAntigas)) {
            $diferenca = $this->post['disciplinas'];
        }

        foreach ($diferenca as $disciplina) {
            if (array_search($disciplina, $this->post['disciplinas'])) {

                $associacaoDisciplinaInativa = $this->consultaAssociacaoDisciplina($disciplina);
                if ($associacaoDisciplinaInativa['id_solicitacao_prova_disciplina']) {

                    $sql_disciplinas = 'UPDATE provas_solicitadas_disciplinas SET
                                            ativo = "S"
                                        WHERE
                                            id_solicitacao_prova_disciplina =  "' . $associacaoDisciplinaInativa['id_solicitacao_prova_disciplina'] . '"';
                    $resultado = $this->executaSql($sql_disciplinas);
                    if (!$resultado) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_alterar_disciplina';
                        $this->executaSql('ROLLBACK');
                        return $this->retorno;
                    }

                    $this->monitora_oque = 1;
                    $this->monitora_onde = 214;
                    $this->monitora_qual = $associacaoDisciplinaInativa['id_solicitacao_prova_disciplina'];
                    $this->Monitora();
                    $this->retorno['sucesso'] = true;

                } else {

                    $sql_disciplinas = 'INSERT INTO provas_solicitadas_disciplinas SET
                                        id_solicitacao_prova = "' . $this->id . '",
                                        iddisciplina =  "' . $disciplina . '",
                                        ativo = "S",
                                        data_cad = NOW() ';
                    $resultado = $this->executaSql($sql_disciplinas);
                    if (!$resultado) {
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_inserir_disciplina';
                        $this->executaSql('ROLLBACK');
                        return $this->retorno;
                    }

                    $this->monitora_oque = 1;
                    $this->monitora_onde = 214;
                    $this->monitora_qual = mysql_insert_id();
                    $this->Monitora();
                    $this->retorno['sucesso'] = true;

                }


            } elseif (array_search($disciplina, $disciplinasAntigas)) {
                $sql_disciplinas = 'UPDATE provas_solicitadas_disciplinas SET
                                        ativo = "N"
                                    WHERE
                                        id_solicitacao_prova = "' . $this->id . '" AND
                                        iddisciplina =  "' . $disciplina . '"';
                $resultado = $this->executaSql($sql_disciplinas);
                if (!$resultado) {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_alterar_disciplina';
                    $this->executaSql('ROLLBACK');
                    return $this->retorno;
                }

                $sql = 'SELECT
                            id_solicitacao_prova_disciplina
                        FROM
                            provas_solicitadas_disciplinas
                        WHERE
                            id_solicitacao_prova = "' . $this->id . '" AND
                            iddisciplina =  "' . $disciplina . '"';

                $alterado = $this->retornarLinha($sql);
                $this->monitora_oque = 3;
                $this->monitora_onde = 214;
                $this->monitora_qual = $alterado['id_solicitacao_prova_disciplina'];
                $this->Monitora();
                $this->retorno['sucesso'] = true;
            }

        }
        $this->executaSql('COMMIT');
        return $this->retorno;

    }

    public function cancelarSolicitacao()
    {
        if (verificaPermissaoAcesso(true)) {
            if (!$this->post['idmotivo']) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_motivo_vazio';
                return $this->retorno;
            }
            $this->sql = "UPDATE
                            provas_solicitadas
                        SET
                            situacao = 'C',
                            idmotivo = '".$this->post['idmotivo']."'
                        WHERE
                            id_solicitacao_prova = ". $this->id;
            $cancelar = $this->executaSql($this->sql);

            /*$this->sql = "INSERT INTO
                           mensagens_alerta(tipo_alerta, id_solicitacao_prova, idmatricula)(SELECT 'agendamento',".$this->id.", m.idmatricula
                               FROM matriculas m INNER JOIN provas_solicitadas ps ON (ps.idmatricula = m.idmatricula)
                       WHERE
                        id_solicitacao_prova = ".(int) $this->id.")";*/

            if(! $cancelar){
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
                return $this->retorno;
            }

            $this->monitora_oque = 12;
            $this->monitora_onde = 160;
            $this->monitora_qual = $this->id;
            $this->Monitora();

            $envio_email = $this->enviarEmailSituacaoSolicitacao(true, false);

            if (!$envio_email['sucesso']) {
                $this->retorno["sucesso"] = false;
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = "mensagem_erro_envio_email";
                return $this->retorno;
            }

            $this->retorno["sucesso"] = true;

            return $this->retorno;
        }
    }

    function retornarQtdeAlunosProva($id_prova_presencial, $idescola, $idlocal)
    {
        $this->sql = 'SELECT
                            count(ps.idmatricula) as total_alunos
                    FROM
                        provas_solicitadas ps
                    WHERE
                        ps.ativo = "S"
                        AND ps.situacao = "A"
                        AND ps.id_prova_presencial = "'.$id_prova_presencial.'"';
        if ($idescola) {
            $this->sql .= ' AND ps.idescola = "'.$idescola.'" ';
        }
        if ($idlocal) {
            $this->sql .= ' AND ps.idlocal = "'.$idlocal.'" ';
        }
        $qtde_alunos = $this->retornarLinha($this->sql);
        return $qtde_alunos['total_alunos'];
    }

    public function aprovarSolicitacao()
    {
        $this->campos = "ps.idmatricula,
        ps.id_prova_presencial,
        ps.idescola,
        ps.idlocal,
        po.quantidade_pessoas_comportadas as qtde_escola,
        l.quantidade_pessoas_comportadas as qtde_local";
        $solicitacao = $this->retornar();

        if ($solicitacao['idescola']) {
            $qtdeAlunosProva = $this->retornarQtdeAlunosProva(
                $solicitacao['id_prova_presencial'],
                $solicitacao['idescola']
            );

            if ($solicitacao['qtde_escola'] <= $qtdeAlunosProva['total_alunos']) {
                $this->retorno["erro"] = true;
                $this->retorno["mensagem"] = "mensagem_erro_qtde_excedida";
                return $this->retorno;
            }
        } elseif ($solicitacao['idlocal']) {
            $qtdeAlunosProva = $this->retornarQtdeAlunosProva(
                $solicitacao['id_prova_presencial'], null,
                $solicitacao['idlocal']
            );

            if ($solicitacao['qtde_local'] <= $qtdeAlunosProva['total_alunos']) {
                $this->retorno["erro"] = true;
                $this->retorno["mensagem"] = "mensagem_erro_qtde_excedida";
                return $this->retorno;
            }
        }


        $this->sql = "UPDATE
                        provas_solicitadas
                    SET
                        situacao = 'A'
                    WHERE
                        id_solicitacao_prova = ".(int) $this->id;
        $associar = $this->executaSql($this->sql);

        /*$this->sql = "INSERT INTO
                            mensagens_alerta(tipo_alerta, id_solicitacao_prova, idmatricula)(SELECT 'agendamento',".(int) $this->id.", m.idmatricula
                                FROM matriculas m INNER JOIN provas_solicitadas ps ON (ps.idmatricula = m.idmatricula)
                        WHERE id_solicitacao_prova = ".(int) $this->id.")";
        $this->executaSql($this->sql);*/


        if(! $associar){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
            return $this->retorno;
        }

        $this->monitora_oque = 11;
        $this->monitora_onde = 160;
        $this->monitora_qual = (int) $this->id;
        $this->Monitora();

        $envio_email = $this->enviarEmailSituacaoSolicitacao(true, true);

        if (!$envio_email['sucesso']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = "mensagem_erro_envio_email";
            return $this->retorno;
        }
        $this->retorno["sucesso"] = true;

        return $this->retorno;
    }

    public function desassociarProva()
    {
        $this->sql = "UPDATE
                        provas_solicitadas
                    SET
                        id_prova_presencial = NULL,
                        data_agendamento = NULL,
                        situacao = 'E'
                    WHERE
                        id_solicitacao_prova = ".(int) $this->post['remover'];
        $desassociar = $this->executaSql($this->sql);

        if($desassociar){
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 2;
            $this->monitora_onde = 160;
            $this->monitora_qual = (int)$this->post["remover"];
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }

    function retornarDocumentosPendentesAluno(){
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
                                tdc.idtipo = td.idtipo and
                                tdc.idcurso = $this->idcurso
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
                                tdi.idtipo = td.idtipo and
                                tdi.idsindicato = $this->idsindicato
                        )
                    )
                )
                and (
                        SELECT iddocumento
                        FROM matriculas_documentos md
                        WHERE
                            md.idtipo = td.idtipo AND
                            md.idmatricula  = $this->idmatricula AND
                            md.situacao = 'aprovado' AND
                            md.ativo = 'S' AND
                            md.idtipo_associacao IS NULL
                        LIMIT 1
                ) IS NULL
                AND td.ativo = 'S' ";

        return $this->retornarLinhas();
    }

    public function retornarNomeLogradouro($idlogradouro)
    {
        $sql = "SELECT nome FROM logradouros WHERE idlogradouro = '" . $idlogradouro . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
    }

    public function retornarNomeCidade($idcidade)
    {
        $sql = "SELECT nome FROM cidades WHERE idcidade = '" . $idcidade . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
    }

    public function retornarNomeEstado($idestado)
    {
        $sql = "SELECT nome FROM estados WHERE idestado = '" . $idestado . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
    }

    private function retornarInformacoesSolicitacao()
    {
        $this->campos = "ps.*, pe.nome as aluno,
                        pe.email as email_aluno, c.nome as curso,
                        c.email as email_curso,
                        po.nome_fantasia as escola, po.email as email_escola,
                        po.idlogradouro as idlogradouro_escola,
                        po.endereco as endereco_escola,
                        po.bairro as bairro_escola, po.numero as numero_escola,
                        po.complemento as complemento_escola,
                        po.idestado as idestado_escola,
                        po.idcidade as idcidade_escola,
                        l.nome as local, l.email as email_local,
                        l.idlogradouro as idlogradouro_local,
                        l.endereco as endereco_local,
                        l.bairro as bairro_local, l.numero as numero_local,
                        l.complemento as complemento_local,
                        l.idestado as idestado_local, l.idcidade as idcidade_local,
                        o.nome as oferta, pp.data_realizacao,
                        DATE_FORMAT( pp.hora_realizacao_de,  '%H:%i' ) AS hora_realizacao,
                        i.nome as sindicato, i.gerente_nome as gerente,
                        m.idcurso, i.idsindicato,
                        mc.nome AS motivo_cancelamento
                        ";
        return $this->retornar();

    }

    public function montarEnderecoEmail($dados_endereco)
    {
        if ($dados_endereco['idlogradouro']) {
            $retorno .= $this->retornarNomeLogradouro($dados_endereco['idlogradouro']);
        }
        if ($dados_endereco['endereco']) {
            $retorno .= ' '.$dados_endereco['endereco'];
        }
        if ($dados_endereco['bairro']) {
            $retorno .= ', bairro ' . $dados_endereco['bairro'];
        }
        if ($dados_endereco['numero']) {
            $retorno .= ', Nº ' . $dados_endereco['numero'];
        }
        if ($dados_endereco['complemento']) {
            $retorno .= ', ' . $dados_endereco['complemento'];
        }
        if ($dados_endereco['idcidade']) {
            $retorno .= ', ' . $this->retornarNomeCidade($dados_endereco['idcidade']);
        }
        if ($dados_endereco['idestado']) {
            $retorno .= ', ' . $this->retornarNomeEstado($dados_endereco['idestado']);
        }
        return $retorno;
    }

    function enviarEmailSituacaoSolicitacao($enviarAluno, $enviarEscolaLocal)
    {
        $solicitacao = $this->retornarInformacoesSolicitacao();
        $this->idsindicato = $solicitacao['idsindicato'];
        $this->idcurso = $solicitacao['idcurso'];
        if ($solicitacao['situacao'] == 'A') {

            if ($solicitacao['idescola']) {
                $dados_endereco['idlogradouro'] = $solicitacao['logradouro_escola'];
                $dados_endereco['endereco'] = $solicitacao['endereco_escola'];
                $dados_endereco['bairro'] = $solicitacao['bairro_escola'];
                $dados_endereco['numero'] = $solicitacao['numero_escola'];
                $dados_endereco['complemento'] = $solicitacao['complemento_escola'];
                $dados_endereco['idestado'] = $solicitacao['idestado_escola'];
                $dados_endereco['idcidade'] = $solicitacao['idcidade_escola'];
            } else {
                $dados_endereco['idlogradouro'] = $solicitacao['logradouro_local'];
                $dados_endereco['endereco'] = $solicitacao['endereco_local'];
                $dados_endereco['bairro'] = $solicitacao['bairro_local'];
                $dados_endereco['numero'] = $solicitacao['numero_local'];
                $dados_endereco['complemento'] = $solicitacao['complemento_local'];
                $dados_endereco['idestado'] = $solicitacao['idestado_local'];
                $dados_endereco['idcidade'] = $solicitacao['idcidade_local'];
            }

            if ($enviarAluno) {
                $corpoEmailAluno = 'Ol&aacute; [[NOME_DESTINATARIO]]<br />
                                    Sua solicita&ccedil;&atilde;o de id.[[IDSOLICITACAO]]
                                    referente &agrave; matr&iacute;cula de N&ordm; [[IDMATRICULA]]
                                    do curso <br /> [[CURSO]], foi aprovada pela
                                    institui&ccedil;&atilde;o [[INSTITUICAO]]. <br />
                                    A prova ser&aacute; realizada no dia [[DATA_REALIZACAO]]
                                    &agrave;s [[HORA_PROVA]] na escola/local de prova [[POLO]].<br />
                                    Endere&ccedil;o: [[ENDERECO_POLO]].<br />';
            }
            if ($enviarEscolaLocal) {
                $corpoEmailEscola = 'Escola/ Local de prova [[NOME_DESTINATARIO]]<br />
                                    Um usu&aacute;rio administrativo da institui&ccedil;&atilde;o ou
                                    gestor de institui&ccedil;&otilde;es aprovou a
                                    solicita&ccedil;&atilde;o do aluno [[ALUNO]],
                                    de matr&iacute;cula de N&ordm; [[IDMATRICULA]], <br /> do curso
                                    [[CURSO]] e oferta [[OFERTA]] <br />de realizar a prova
                                    presencial de id. [[IDPROVA]] agendada para a
                                    data [[DATA_REALIZACAO]] &agrave;s [[HORA_PROVA]].<br /><br />
                                    A mesma ser&aacute; realizada na respectiva escola da
                                    institui&ccedil;&atilde;o.<br /> Endere&ccedil;o: [[ENDERECO_POLO]].<br />';
            }
        } elseif ($solicitacao['situacao'] == 'C') {
            if ($enviarAluno) {
                $corpoEmailAluno = 'Ol&aacute; [[NOME_DESTINATARIO]]<br />
                                    Sua solicita&ccedil;&atilde;o de id.[[IDSOLICITACAO]] referente &agrave; matr&iacute;cula de N&ordm; [[IDMATRICULA]] do curso <br />
                                    [[CURSO]], foi cancelada pela institui&ccedil;&atilde;o [[INSTITUICAO]]. <br />
                                    A prova seria realizada no dia [[DATA_REALIZACAO]] &agrave;s [[HORA_PROVA]] no escola/ local de prova [[POLO]].
                                    <br /><br />
                                    <strong>Motivo do cancelamento:</strong> [[MOTIVO_CANCELAMENTO]]';
            }
            if ($enviarEscolaLocal) {
                $corpoEmailEscola = 'Escola/ Local de prova [[NOME_DESTINATARIO]]<br />
                                    Um usu&aacute;rio administrativo da institui&ccedil;&atilde;o ou
                                    gestor de institui&ccedil;&otilde;es cancelou a
                                    solicita&ccedil;&atilde;o do aluno [[ALUNO]],
                                    de matr&iacute;cula de N&ordm; [[IDMATRICULA]], <br /> do curso
                                    [[CURSO]] e oferta [[OFERTA]] de realizar a prova presencial
                                    de id.[[IDPROVA]] agendada para a data [[DATA_REALIZACAO]]
                                    &agrave;s [[HORA_PROVA]].<br /><br />
                                    A mesma ser&aacute; realizada no
                                    respectivo escola da institui&ccedil;&atilde;o.';
            }
        }

        if ($solicitacao['email_curso']) {
            $emailDe = $solicitacao['email_curso'];
        } else {
            $emailDe = $GLOBALS['config']['emailSistema'];
        }
        $nomeDe = utf8_decode($config['tituloEmpresa']);
        $assunto = utf8_decode("Alteração de Status da Solicitação de Prova Presencial do Aluno");

        if ($corpoEmailAluno) {
            $nomePara = utf8_decode($solicitacao['aluno']);
            $emailPara = $solicitacao['email_aluno'];
            $messageAluno = $corpoEmailAluno;

            $messageAluno = str_ireplace("[[NOME_DESTINATARIO]]",
                utf8_decode($solicitacao['aluno']),$messageAluno);

            $messageAluno = str_ireplace("[[INSTITUICAO]]",
                utf8_decode($solicitacao['sindicato']),$messageAluno);

            $messageAluno = str_ireplace("[[IDSOLICITACAO]]",
                $solicitacao['id_solicitacao_prova'],$messageAluno);

            $messageAluno = str_ireplace("[[IDPROVA]]",
                $solicitacao['id_prova_presencial'],$messageAluno);

            $messageAluno = str_ireplace("[[IDMATRICULA]]",
                $solicitacao['idmatricula'],$messageAluno);

            $messageAluno = str_ireplace("[[OFERTA]]",
                utf8_decode($solicitacao['oferta']),$messageAluno);

            $messageAluno = str_ireplace("[[CURSO]]",
                utf8_decode($solicitacao['curso']),$messageAluno);

            $messageAluno = str_ireplace("[[DATA_REALIZACAO]]",
                formataData($solicitacao['data_realizacao'], "br", 0),$messageAluno);

            $messageAluno = str_ireplace("[[HORA_PROVA]]",
                $solicitacao['hora_realizacao'],$messageAluno);

            $messageAluno = str_ireplace("[[ENDERECO_POLO]]",
                utf8_decode($this->montarEnderecoEmail($dados_endereco)),$messageAluno);

            if ($solicitacao['escola']) {
                $messageAluno = str_ireplace("[[POLO]]",
                    utf8_decode($solicitacao['escola']),$messageAluno);
            } else {
                $messageAluno = str_ireplace("[[POLO]]",
                    utf8_decode($solicitacao['local']),$messageAluno);
            }

            $messageAluno = str_ireplace("[[MOTIVO_CANCELAMENTO]]",
                utf8_decode($solicitacao['motivo_cancelamento']),$messageAluno);


            /*$documentosPendentes = $this->retornarDocumentosPendentesAluno();
            if (count($documentosPendentes) > 0) {
                $messageAluno .= "</br></br>Caro aluno, voc&ecirc; possui documento(s)
                pendente(s). S&atilde;o eles:</br></br>";
                foreach ($documentosPendentes as $indice => $documento) {
                    $messageAluno .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ".utf8_decode($documento['nome'])."; </br>" ;
                }
            }*/
            //echo 'informções '.$nomeDe.'<br>'.$emailDe.'<br>'.$assunto.'<br>'.$messageAluno.'<br>'.$nomePara.'<br>'.$emailPara.'<br>'.'layout';

            if ($this->EnviarEmail($nomeDe, $emailDe, $assunto, $messageAluno,
                $nomePara, $emailPara, 'layout')) {
                $sucessoAluno = true;
            }
        }
        if ($corpoEmailEscola) {
            if ($solicitacao['escola']) {
                $nomePara = utf8_decode($solicitacao['escola']);
                $emailPara = $solicitacao['email_escola'];
            } else {
                $nomePara = utf8_decode($solicitacao['local']);
                $emailPara = $solicitacao['email_local'];
            }

            $messageEscola = $corpoEmailEscola;

            if ($solicitacao['escola']) {
                $messageEscola = str_ireplace("[[NOME_DESTINATARIO]]",
                    utf8_decode($solicitacao['escola']),$messageEscola);
            } else {
                $messageEscola = str_ireplace("[[NOME_DESTINATARIO]]",
                    utf8_decode($solicitacao['local']),$messageEscola);
            }


            $messageEscola = str_ireplace("[[INSTITUICAO]]",
                utf8_decode($solicitacao['sindicato']),$messageEscola);

            $messageEscola = str_ireplace("[[IDSOLICITACAO]]",
                $solicitacao['id_solicitacao_prova'],$messageEscola);

            $messageEscola = str_ireplace("[[IDMATRICULA]]",
                $solicitacao['idmatricula'],$messageEscola);

            $messageEscola = str_ireplace("[[IDPROVA]]",
                $solicitacao['id_prova_presencial'],$messageEscola);

            $messageEscola = str_ireplace("[[ALUNO]]",
                utf8_decode($solicitacao['aluno']),$messageEscola);

            $messageEscola = str_ireplace("[[OFERTA]]",
                utf8_decode($solicitacao['oferta']),$messageEscola);

            $messageEscola = str_ireplace("[[CURSO]]",
                utf8_decode($solicitacao['curso']),$messageEscola);

            $messageEscola = str_ireplace("[[DATA_REALIZACAO]]",
                formataData($solicitacao['data_realizacao'], "br", 0),
                $messageEscola);

            $messageEscola = str_ireplace("[[HORA_PROVA]]",
                $solicitacao['hora_realizacao'],$messageEscola);

            $messageEscola = str_ireplace("[[POLO]]",
                utf8_decode($solicitacao['escola']),$messageEscola);

            $messageEscola = str_ireplace("[[ENDERECO_POLO]]",
                utf8_decode($this->montarEnderecoEmail($dados_endereco)),$messageEscola);

            //echo 'informções'.$nomeDe.'<br>'.$emailDe.'<br>'.$assunto.'<br>'.$messageEscola.'<br>'.$nomePara.'<br>'.$emailPara.'<br>'.'layout';exit;
            if ($this->EnviarEmail($nomeDe, $emailDe, $assunto,
                $messageEscola, $nomePara, $emailPara, 'layout')) {
                $sucessoEscola = true;
            }
        }
        if ((($enviarAluno && $sucessoAluno) && ($enviarEscolaLocal && $sucessoEscola))
            || (($enviarAluno && $sucessoAluno) && (!$enviarEscolaLocal))
            || (($enviarEscola && $sucessoEscola) && (!$enviarAluno))) {
            $this->retorno['sucesso'] = true;
        } else {
            $this->retorno['sucesso'] = false;
        }

        return $this->retorno['sucesso'];
    }

    function retornarSolicitacoesAluno($compareceu = false) {
        $this->sql = "SELECT
						ps.*,
						pp.data_realizacao,
						pp.hora_realizacao_de,
						pp.hora_realizacao_ate
					FROM
						provas_solicitadas ps
						inner join provas_presenciais pp on (ps.id_prova_presencial = pp.id_prova_presencial)
					WHERE
						ps.idmatricula = '".(int) $this->idmatricula."' AND
						ps.ativo = 'S'";
        if($compareceu) {
            $this->sql .= " and ps.compareceu = '".$compareceu."'";
        }

        $this->ordem = "desc";
        $this->ordem_campo = "ps.id_solicitacao_prova";
        $this->limite = -1;
        return $this->retornarLinhas();

    }

    public function retornarMotivoCancelamento()
    {
        $this->sql = "SELECT
                            m.idmotivo, m.nome as motivo_cancelamento, descricao
                    FROM
                        provas_solicitadas ps
                    INNER JOIN motivos_cancelamento_solicitacao_prova m
                    ON (m.idmotivo = ps.idmotivo)
                    WHERE
                        ps.id_solicitacao_prova = {$this->id}";
        return $this->retornarLinha($this->sql);
    }

}
