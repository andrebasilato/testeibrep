<?php

/**
 * Boletim
 *
 * Essa classe pode ser acessada como um array e
 * suas instâncias podem ser iteradas como um array interno do php
 *
 * Exemplo de uso:
 *
 * <code>
 * $boletim = new Boletim(new Avaliacoes);
 * $boletim['idmatricula'] = (int) Request::url(4);
 *
 * $boletim->buscarDadosDaMatriculaHistorico();
 *
 * foreach ($boletim ... );
 * </code>
 *
 * @author Henrique Feitosa <henriquef@alfamaweb.com.br>
 * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 *
 */
class Boletim implements ArrayAccess
{

    const DATABASE_RELATION = 'matriculas';

    /**
     * @var integer Id da oferta
     */
    private $idOferta;

    /**
     * @var integer
     */
    private $_idMatricula;

    /**
     * @var Avaliacao
     */
    private $_avaliacao;

    /**
     * @var array guarda dados para camada de acesso via
     *             array syntax
     */
    private $_content;

    /**
     * @param Avaliacao $avaliacao
     */
    public function __construct(Avaliacoes $avaliacao)
    {
        $this->_avaliacao = $avaliacao;
        $this->_avaliacao->set('campos', ' * ');
    }

    protected function _getNotes()
    {
        foreach ($this['curso']['block'] as $key => $value) {

            $stmt = $this->_avaliacao->executaSql(
                'SELECT *, dcp.carga_horaria FROM `curriculos_blocos_disciplinas` AS cbd
                    INNER JOIN disciplinas as dcp ON
                dcp.iddisciplina = cbd.iddisciplina
                    WHERE cbd.idbloco = ' . $value['idbloco'] . ' AND
                cbd.ativo ="S" AND dcp.ativo="S"'
            );

            while ($provas = mysql_fetch_object($stmt)) {
                $this->_content['curso']['block'][$key]['provas'][] = $provas;
            }
        }
    }

    protected function _fetchProvas()
    {
        foreach ($this['curso']['block'] as $key1 => $value1) {
            foreach ($value1['provas'] as $key => $value) {

                // traz notas ainda nn corrigidas
                if ($this->_content['all']) {
                    $sql = 'SELECT * FROM matriculas_avaliacoes as ma
                        INNER JOIN avas_avaliacoes AS aa ON
                            aa.idava = ' . $value->idava . '
                     WHERE ma.idmatricula = ' . $this['idmatricula'] . '
                     AND ma.prova_corrigida = "S"
                     GROUP BY ma.idprova ORDER BY ma.nota DESC LIMIT 1
                    ';
                    # AND aa.avaliacao = "1"
                } else {
                    $sql = 'SELECT * FROM matriculas_avaliacoes as ma
                        INNER JOIN avas_avaliacoes AS aa ON
                            aa.idava = ' . $value->idava . '
                     WHERE ma.idmatricula = ' . $this['idmatricula'] . '
                         AND ma.prova_corrigida = "S"
                    GROUP BY ma.idprova ORDER BY ma.nota DESC LIMIT 1 ';
                }

                $query = $this->_avaliacao->executaSql($sql);

                while ($row = mysql_fetch_assoc($query)) {
                    $this->_content['curso']['block'][$key1]['provas'][$key]->resultado[] = $row;
                }

            }
        }
    }

    public static function getFormula($idava, $matricula, $idavaliacao, $bloco, $iddisciplina, $nome)
    {
        $sql = "SELECT curriculos_blocos_disciplinas.idformula
            FROM curriculos_blocos_disciplinas
                INNER JOIN curriculos_blocos as cb
                    ON cb.nome = '{$nome}'
				inner join ofertas_curriculos_avas oca
						on oca.ativo = 'S' and oca.iddisciplina = curriculos_blocos_disciplinas.iddisciplina and oca.idcurriculo = cb.idcurriculo
            WHERE oca.idava = '{$idava}'
            AND curriculos_blocos_disciplinas.idbloco = '{$bloco}'
            AND curriculos_blocos_disciplinas.iddisciplina = '{$iddisciplina}'
            AND curriculos_blocos_disciplinas.ativo = 'S'
            AND cb.ativo='S'";

        $stmt = $this->_avaliacao->executaSql($sql);

        return mysql_fetch_assoc($stmt);
    }

    /**
     * @todo Refatorar a assinatura desse método, muitos argumentos sem uso
     */
    public static function getProva($idava, $matricula, $tipo, $idavaliacao, $iddisciplina = null)
    {
        $sql = 'SELECT mn.*
                    FROM matriculas_notas mn
					WHERE mn.idmatricula = ' . $matricula . '
						and mn.iddisciplina = ' . $iddisciplina . '
						and mn.idtipo = ' . $tipo . '
					ORDER BY mn.nota DESC LIMIT 1
				';

        $result = $this->_avaliacao->executaSql($sql);

        return mysql_fetch_assoc($result);
    }

    public function offsetSet($offset, $value)
    {
        $this->_content[$offset] = $value;
    }

    public function offsetGet($offset)
    {
        return $this->_content[$offset];
    }

    public function offsetUnset($offset)
    {
        unset($this->_content[$offset]);
    }

    public function offsetExists($offset)
    {
        if ($this->_content[$offset]) {
            return true;
        }
        return false;
    }

    public function buscarDadosDaMatriculaHistorico()
    {
        if (!$this->offsetExists('idmatricula')) {
            throw new Exception('Set o id da matrícula no objeto.');
        }

        $this->_produzirInformacaoDoHistorico();
    }

    private function _produzirInformacaoDoHistorico()
    {

        $sql = sprintf(
            "SELECT
						p.*,
						pol.nome_fantasia as escola,
						pol.inscricao_estadual,
						pol.inscricao_municipal,
						est.sigla as estado,
						cid.nome as cidade,
						curri.media as media_curriculo,
						curri.idcurriculo,
						curri.dias_minimo,
						curri.porcentagem_ava,
						ins.nome as sindicato,
                        lgd_ins.nome as ins_logradouro,
						CONCAT(ins.endereco, ', ', ins.numero, ', ', ins.bairro, ', ', cid_ins.nome, ', ', est_ins.sigla) as ins_endereco,
						ins.logo_servidor as sindicato_logo,
						cid_ins.nome as cidade_ins,
						man.nome_fantasia as mantenedora,
						c.data_matricula,
						cu.nome as nome_curso,
						ci.fundamentacao,
						ci.fundamentacao_legal,
						ci.autorizacao,
						ci.perfil,
						ci.regulamento,
						c.porcentagem_manual,
                        'Rosemary Ramos Martins' AS diretor_pedagogico
					 FROM `%s` as c
						INNER JOIN cursos cu on c.idcurso = cu.idcurso
                        INNER JOIN pessoas as p ON c.idpessoa = p.idpessoa
						LEFT JOIN cidades cid ON p.idcidade = cid.idcidade
						LEFT JOIN estados est ON p.idestado = est.idestado
						INNER JOIN escolas pol ON c.idescola = pol.idescola
						INNER JOIN sindicatos ins ON pol.idsindicato = ins.idsindicato
						INNER JOIN cursos_sindicatos ci ON ins.idsindicato = ci.idsindicato and cu.idcurso = ci.idcurso and ci.ativo = 'S'
						LEFT JOIN cidades cid_ins ON ins.idcidade = cid_ins.idcidade
						LEFT JOIN estados est_ins ON ins.idestado = est_ins.idestado
                        LEFT JOIN logradouros lgd_ins ON ins.idlogradouro = lgd_ins.idlogradouro
						INNER JOIN mantenedoras man ON man.idmantenedora = ins.idmantenedora
						INNER JOIN ofertas_cursos_escolas ocp ON c.idoferta = ocp.idoferta AND c.idcurso = ocp.idcurso AND c.idescola = ocp.idescola AND ocp.ativo = 'S' AND ocp.ignorar = 'N'
						INNER JOIN curriculos curri ON ocp.idcurriculo = curri.idcurriculo
                    WHERE c.idmatricula = %d",
            self::DATABASE_RELATION,
            $this['idmatricula']
        );

        $result = mysql_fetch_object(
            $this->_avaliacao->executaSql(
                $sql
            )
        );
        $disciplinas = $this->retornarDisciplinas($this['idmatricula']);
        $this['idaluno'] = $result->idpessoa;
        $this['nome_aluno'] = $result->nome;
        $this['aluno'] = $result;
        $this['aluno_disciplinas'] = $disciplinas;
        return true;
    }

    public function retornarAva($idbloco_disciplina)
    {
        $sql = 'select idoferta from matriculas where idmatricula = ' . $this['idmatricula'];
        $resultado = $this->_avaliacao->executaSql($sql);
        $oferta = mysql_fetch_assoc($resultado);

        $sql = "select
              a.*, cbd.iddisciplina
            FROM
              curriculos_blocos_disciplinas cbd
              inner join curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
              inner join ofertas_curriculos_avas oca on (oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . ")
              inner join avas a on (a.ativo = 'S' and oca.idava = a.idava)
            where
              cbd.ativo = 'S' and cbd.idbloco_disciplina = " . intval($idbloco_disciplina) . "
			  order by oca.idava desc";
        $resultado = $this->_avaliacao->executaSql($sql);
        return mysql_fetch_assoc($resultado);
    }

    public function getIdAVA($idbloco_disciplina)
    {
        $sql = 'select idoferta from matriculas where idmatricula = ' . $this['idmatricula'];
        $resultado = $this->_avaliacao->executaSql($sql);
        $oferta = mysql_fetch_assoc($resultado);

        $sql = "select
              a.idava
            FROM
              curriculos_blocos_disciplinas cbd
              inner join curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
              inner join ofertas_curriculos_avas oca on (oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . ")
              inner join avas a on (a.ativo = 'S' and oca.idava = a.idava)
            where
              cbd.ativo = 'S' and cbd.idbloco_disciplina = " . intval($idbloco_disciplina) . "
			  order by oca.idava desc";
        $resultado = $this->_avaliacao->executaSql($sql);
        $linha = mysql_fetch_assoc($resultado);
        return $linha['idava'];
    }

    public function _retornarAndamentoDisciplina($idbloco_disciplina)
    {

        $andamento = array();
        $ava = $this->retornarAva($idbloco_disciplina);
        if ($ava['idava']) {
            $sql = "SELECT
                        idmatricula_ava_porcentagem,
                        IF(porcentagem_manual > porcentagem, porcentagem_manual, porcentagem) AS porc_aluno
                    FROM
                        matriculas_avas_porcentagem
                    WHERE
                        idava = " . $ava['idava'] . " and
                        idmatricula = " . $this['idmatricula'];
            $resultado = $this->_avaliacao->executaSql($sql);
            $andamento = mysql_fetch_assoc($resultado);
            if ($andamento['idmatricula_ava_porcentagem']) {
                if ($andamento["porc_aluno"] >= 100) {
                    $andamento["porc_aluno"] = 100;
                    $andamento["porc_aluno_formatada"] = 100;
                } else {
                    $andamento["porc_aluno_formatada"] = number_format($andamento["porc_aluno"], 2, ",", ".");
                }
            } else {
                $andamento["porc_aluno"] = 0;
                $andamento["porc_aluno_formatada"] = 0;
            }
        }
        return $andamento;
    }

    private function retornarDisciplinas($idmatricula)
    {
        if (is_int($idmatricula) && $idmatricula > 0) {
            $sql = "SELECT m.idoferta,cb.idbloco FROM matriculas AS m ";
            $sql .= "INNER JOIN ofertas_cursos_escolas ocp ON ( m.idoferta = ocp.idoferta AND m.idcurso = ocp.idcurso AND m.idescola = ocp.idescola AND ocp.ativo = 'S' ) ";
            $sql .= "INNER JOIN curriculos c ON ( ocp.idcurriculo = c.idcurriculo AND c.ativo =  'S' AND c.ativo_painel =  'S' ) ";
            $sql .= "INNER JOIN curriculos_blocos cb ON ( c.idcurriculo = cb.idcurriculo AND cb.ativo = 'S' ) ";
            $sql .= "WHERE m.idmatricula = '" . (int)$idmatricula . "' LIMIT 1";
            $resultado = $this->_avaliacao->executaSql($sql);
            $oferta = mysql_fetch_assoc($resultado);

            $sql = '
			SELECT d.*, cbd.*,ocp.idcurso,oca.idava,oca.idoferta,cbd.iddisciplina
                FROM matriculas m
				INNER JOIN ofertas_cursos_escolas ocp ON ( m.idoferta = ocp.idoferta AND m.idcurso = ocp.idcurso AND m.idescola = ocp.idescola AND ocp.ativo = "S" )
				INNER JOIN curriculos c ON ( ocp.idcurriculo = c.idcurriculo AND c.ativo =  "S" AND c.ativo_painel =  "S" )
                INNER JOIN curriculos_blocos cb ON ( c.idcurriculo = cb.idcurriculo AND cb.ativo = "S" )
				INNER JOIN curriculos_blocos_disciplinas cbd ON ( cb.idbloco = cbd.idbloco AND cbd.ativo = "S" AND cb.idbloco = "' . $oferta['idbloco'] . '")
                INNER JOIN disciplinas d ON ( cbd.iddisciplina = d.iddisciplina AND d.ativo = "S" )
                INNER JOIN avas_disciplinas ad ON ( ad.iddisciplina = d.iddisciplina AND ad.ativo = "S" )
                INNER JOIN ofertas_curriculos_avas oca ON ( oca.ativo = "S" AND oca.iddisciplina = cbd.iddisciplina AND oca.idcurriculo = cb.idcurriculo AND oca.idoferta = "' . $oferta['idoferta'] . '" )
				WHERE m.ativo =  "S" AND m.idmatricula = ' . $idmatricula . ' GROUP BY oca.idoferta,oca.idava,cbd.iddisciplina ORDER BY cbd.ordem ASC';
            $resultado = $this->_avaliacao->executaSql($sql);
            $retorno = array();
            while ($linha = mysql_fetch_assoc($resultado)) {
                $linha['porcentagem_aluno_ava'] = $this->_retornarAndamentoDisciplina($linha['idbloco_disciplina']);
                $retorno[] = $linha;
            }
            return $retorno;
        } else {
            throw new InvalidArgumentException('retornarDisciplinas function only accepts integers. Input was: ' . $idmatricula);
        }
    }

    public function setOferta($idOferta)
    {

        $this->idOferta = $idOferta;

    }

    private function getOferta()
    {

        return $this->idOferta;

    }

    public static function getProvas($matricula, $iddisciplina)
    {
        $sql = 'SELECT mn.*
                    FROM matriculas_notas mn
					WHERE mn.idmatricula = ' . $matricula . '
						and mn.iddisciplina = ' . $iddisciplina . '
						and ativo = "S"
					ORDER BY mn.nota DESC LIMIT 1
				';

        $resultado = $this->_avaliacao->executaSql($sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            $retorno[] = $linha;
        }
        return $retorno;
    }

    public static function getProvasTipos($matricula, $iddisciplina)
    {
        $sql = '
            SELECT mn.*
            FROM matriculas_notas mn
            WHERE mn.idmatricula = ' . $matricula . '
                and mn.iddisciplina = ' . $iddisciplina . '
                and mn.ativo = "S"
                and mn.nota = (
                    select max(mni.nota)
                    from matriculas_notas mni
                    where
                        mni.idtipo = mn.idtipo and
                        mni.iddisciplina = mn.iddisciplina and
                        mn.idmatricula = mni.idmatricula and
                        mni.ativo = "S"
                )
            group by mn.idtipo
            ';
        $resultado = mysql_query($sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            $retorno[$linha['idtipo']] = $linha;
        }
        return $retorno;
    }

    public static function getAproveitamentoEstudos($matricula, $iddisciplina)
    {
        $sql = 'SELECT mn.*
                    FROM matriculas_notas mn
					WHERE mn.idmatricula = ' . $matricula . '
						and mn.iddisciplina = ' . $iddisciplina . '
						and mn.ativo = "S"
						and mn.aproveitamento_estudo = "S"
					limit 1
				';

        $resultado = mysql_query($sql);
        return mysql_fetch_assoc($resultado);
    }
}
