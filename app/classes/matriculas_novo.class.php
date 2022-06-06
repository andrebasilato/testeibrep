<?php
/**
 * `Matriculas`
 *
 * @author     Gabriel Leite    <gabriel@alfamaweb.com.br>
 * @author     Tomaz Novaes     <tomaz@alfamaweb.com.br>
 * @author     Daiane Azevedo   <daianea@alfamaweb.bcom.br>
 * @author     Henrique Feitosa <henriquef@alfamaweb.com.br>
 * @author     Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 *
 * @package    Oráculo Construtor
 * @copyright  Copyright (c) 2014 Alfama Web (http://alfamaweb.com.br)
 * @license    Proprietary AlfamaWeb
 * @version    $Id$
 */
class Matriculas extends Core {
    public $modulo;
    public $mapa_alcance;
    /**
     * @var integer Id da conta
     */
    public $idconta;
    /**
     * @var integer Id do documento
     */
    public $iddocumento;
    /**
     * @var integer Id da pessoa
     */
    public $idpessoa;
    /**
     * @var integer Id do vendedor
     */
    public $idvendedor;

    public $matricula;

    public function retornarMeusCursos() {
        $situacaoCancelada = $this->retornarSituacaoCancelada();

        $matriculas = array();

        $this->sql = 'SELECT
                        '.$this->campos.'
                      FROM
                        matriculas m
                        INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                        INNER JOIN sindicatos i ON (i.idsindicato = m.idsindicato)
                      WHERE
                        m.idpessoa = '.(int)$this->idpessoa.' AND
                        m.idsituacao <> '.$situacaoCancelada['idsituacao'].' AND
                        m.ativo = "S"';

        $matriculas = $this->retornarLinhas();
        return $matriculas;
    }

    public function getMatricula($idmatricula) {
        $this->id = $idmatricula;
        return $this->Retornar();
    }

    public function retornarAcessoAva() {
        $retorno = array();
        $retorno['pode_acessar_ava'] = true;

        $dataHoje = date('Y-m-d');

        $retorno['data_inicio_acesso_ava'] = $dataHoje;
        $retorno['data_limite_acesso_ava'] = $dataHoje;

        $this->sql = 'SELECT
                        m.data_matricula,
                        m.data_prolongada,
                        ocp.data_inicio_ava,
                        ocp.dias_para_ava,
                        ocp.dias_para_prova,
                        ocp.data_limite_ava
                    FROM
                        matriculas m
                        INNER JOIN ofertas_cursos_escolas ocp ON
                        (
                            m.idoferta = ocp.idoferta AND
                            m.idescola = ocp.idescola AND
                            m.idcurso = ocp.idcurso AND
                            ocp.ativo = "S"
                        )
                    WHERE
                        m.idmatricula = '.$this->id.' AND
                        m.ativo = "S"';

        $datas = $this->retornarLinha($this->sql);

        $retorno['dias_para_prova'] = $datas['dias_para_prova'];

        if($datas['data_inicio_ava']) {
            $retorno['data_inicio_acesso_ava'] = $datas['data_inicio_ava'];
            if($retorno['data_inicio_acesso_ava'] > $dataHoje) {
                $retorno['pode_acessar_ava'] = false;
            }
        }

        if($datas['data_prolongada'] && $datas['data_prolongada'] != '0000-00-00') {
            $retorno['data_limite_acesso_ava'] = $datas['data_prolongada'];
        } elseif($datas['dias_para_ava'] || $datas['data_limite_ava']) {

            $dataDiasParaAva = NULL;
            if ($datas['dias_para_ava']) {
                $dataDiasParaAva = new DateTime($datas['data_matricula']);
                $dataDiasParaAva->modify('+ '.$datas['dias_para_ava'].' days');
            }

            $dataLimiteAva = NULL;
            if ($datas['data_limite_ava']) {
                $dataLimiteAva = new DateTime($datas['data_limite_ava']);
            }

            if ($dataDiasParaAva && $dataLimiteAva) {
                if ($dataDiasParaAva > $dataLimiteAva) {
                    $retorno['data_limite_acesso_ava'] = $dataDiasParaAva->format('Y-m-d');
                } else {
                    $retorno['data_limite_acesso_ava'] = $dataLimiteAva->format('Y-m-d');
                }
            } elseif($dataDiasParaAva) {
                $retorno['data_limite_acesso_ava'] = $dataDiasParaAva->format('Y-m-d');
            } else {
                $retorno['data_limite_acesso_ava'] = $dataLimiteAva->format('Y-m-d');
            }
        }

        if($retorno['data_limite_acesso_ava'] < $dataHoje) {
            $retorno['pode_acessar_ava'] = false;
        }

        return $retorno;
    }

    function retornarVisualizacoesSituacao($idsituacao) {
        $visualizacoes = array();

        $this->sql = 'SELECT
                        mwa.idacao, mwa.idopcao
                    FROM
                        matriculas_workflow_acoes mwa
                    WHERE
                        mwa.idsituacao = '.$idsituacao.' AND
                        mwa.ativo = "S"';
        $this->ordem = 'asc';
        $this->ordem_campo = 'mwa.idacao';
        $this->limite = -1;
        $acoes = $this->retornarLinhas();
        foreach ($acoes as $acao) {
            foreach ($GLOBALS['workflow_parametros_matriculas'] as $opcao) {
                if ($opcao['idopcao'] == $acao['idopcao'] && $opcao['tipo'] == 'visualizacao') {
                    $visualizacoes[$acao['idopcao']] = $acao;
                }
            }
        }

        return $visualizacoes;
    }

    public function RetornarAlerta($tipo, $matricula){

        switch ($tipo) {
            case 'forum':
                $sql = "SELECT COUNT(*) as total, ma.idtopico, af.idava as idava FROM mensagens_alerta ma
                    INNER JOIN avas_foruns_topicos aft ON(aft.idtopico = ma.idtopico)
                    INNER JOIN avas_foruns af ON (aft.idforum = af.idforum)
                    WHERE tipo_alerta = 'forum' AND ma.idmatricula = ". $matricula;
                $select = mysql_query($sql);
                $valores = mysql_fetch_assoc($select);
                break;

            case 'documentospedagogicos':
                $sql = "SELECT COUNT(*) AS total, ma.idmatricula as idmatricula, ma.situacao_documento as situacao_documento,
                    ma.iddocumento as iddocumento
                    FROM mensagens_alerta ma
                    WHERE tipo_alerta = 'documentospedagogicos' AND idmatricula =". $matricula;
                $select = mysql_query($sql);
                $valores = mysql_fetch_assoc($select);
                break;

            case 'atendimento':
                $sql = "SELECT COUNT(*) AS total, ma.idatendimento as idatendimento,
                    aw.nome as situacao_nome, at.protocolo as protocolo
                    FROM mensagens_alerta ma INNER JOIN atendimentos_workflow aw ON(aw.idsituacao = ma.idsituacao_atendimento)
                    INNER JOIN atendimentos at ON(ma.idatendimento = at.idatendimento)
                    WHERE tipo_alerta = 'atendimento' AND ma.idmatricula =". $matricula;
                $select = mysql_query($sql);
                $valores = mysql_fetch_assoc($select);
                break;

            case 'tiraduvidas':
                $sql = "SELECT COUNT(*) as total, ma.idmensagem_instantanea,
                    ami.idava as idava
                    FROM mensagens_alerta ma
                    INNER JOIN avas_mensagem_instantanea ami ON(ma.idmensagem_instantanea = ami.idmensagem_instantanea)
                    WHERE tipo_alerta = 'tiraduvidas' AND ma.idmatricula =". $matricula;
                $select = mysql_query($sql);
                $valores = mysql_fetch_assoc($select);
                break;

            case 'agendamento':
                $sql = "SELECT COUNT(*) AS total, ma.idmatricula as idmatricula, ma.id_solicitacao_prova as id_solicitacao_prova
                    FROM mensagens_alerta ma
                    WHERE tipo_alerta = 'agendamento' AND idmatricula =". $matricula;
                $select = mysql_query($sql);
                $valores = mysql_fetch_assoc($select);
        }

        return $valores;
    }

    public function retornarPodeSolicitarProva() {
        $podeSolicitar = false;

        $this->sql = 'SELECT
                        m.data_matricula,
                        IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) AS porcentagem,
                        oc.porcentagem_minima,
                        oc.qtde_minima_dias
                    FROM
                        matriculas m
                        INNER JOIN ofertas_cursos oc ON (oc.idoferta = m.idoferta AND oc.idcurso = m.idcurso AND oc.ativo = "S")
                    WHERE
                        m.idmatricula = '.(int)$this->id;
        $matricula = $this->retornarLinha($this->sql);

        $dataHoje = strtotime(date('Y-m-d'));
        $dataMatricula = strtotime($matricula['data_matricula']);
        $diasDiferenca = floor(($dataHoje - $dataMatricula) / (60 * 60 * 24));

        if (
            ($matricula['porcentagem_minima'] || $matricula['qtde_minima_dias']) &&
            ($matricula['porcentagem_minima'] <= $matricula['porcentagem']) &&
            ($matricula['qtde_minima_dias'] <= $diasDiferenca)
        ) {
            $podeSolicitar = true;
        }

        return $podeSolicitar;
    }

    public function retornarDiasParaProva()
    {
        $this->sql = 'SELECT
                        oc.dias_para_prova
                    FROM
                        matriculas m
                        INNER JOIN ofertas_cursos oc ON (oc.idoferta = m.idoferta AND oc.idcurso = m.idcurso AND oc.ativo = "S")
                    WHERE
                        m.idmatricula = '.(int)$this->id;
        return $this->retornarLinha($this->sql);
    }

    public function retornarInicioCurso() {

        $this->sql = 'SELECT
                        oc.data_inicio_aula,
                        oc.dias_para_prova
                    FROM
                        matriculas m
                        INNER JOIN ofertas_cursos oc ON (oc.idoferta = m.idoferta AND oc.idcurso = m.idcurso AND oc.ativo = "S")
                    WHERE
                        m.idmatricula = '.(int)$this->id;
        return $this->retornarLinha($this->sql);
    }

    public function retornarQtdeSolicitacoesProvas() {
        $this->sql = 'SELECT
                        count(id_solicitacao_prova) as total
                    FROM
                        provas_solicitadas ps
                    WHERE
                        ps.idmatricula = '.(int)$this->id.' AND
                        ps.ativo = "S"';
        $retorno = $this->retornarLinha($this->sql);

        return $retorno['total'];
    }

    public function retornarAvaliacoesPendentes() {
        $this->sql = 'SELECT
                        aa.idavaliacao,
                        count(*) as total
                    FROM
                        matriculas m
                        INNER JOIN ofertas_cursos_escolas ocp ON (m.idoferta = ocp.idoferta AND m.idcurso = ocp.idcurso AND m.idescola = ocp.idescola AND ocp.ativo = "S")
                        INNER JOIN ofertas_curriculos_avas oca ON (ocp.idoferta = oca.idoferta AND ocp.idcurriculo = oca.idcurriculo AND oca.ativo = "S")
                        INNER JOIN avas_avaliacoes aa ON (oca.idava = aa.idava AND aa.ativo = "S" AND aa.exibir_ava = "S")
                    WHERE
                        m.idmatricula = '.(int) $this->id.' AND
                        aa.periode_de <= "'.date('Y-m-d').'" AND
                        aa.periode_ate >= "'.date('Y-m-d').'" AND
                        (
                            SELECT
                                count(*)
                            FROM
                                matriculas_avaliacoes ma
                            WHERE
                                m.idmatricula = ma.idmatricula AND
                                aa.idavaliacao = ma.idavaliacao AND
                                ativo = "S"
                        ) < aa.qtde_tentativas AND
                        (
                            SELECT
                                max(nota)
                            FROM
                                matriculas_avaliacoes
                            WHERE
                                idmatricula = m.idmatricula AND
                                idavaliacao = aa.idavaliacao AND
                                ativo = "S"
                            ORDER BY
                                idprova DESC LIMIT 1
                        ) < aa.nota_minima
                    GROUP
                        BY aa.idavaliacao';

        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoConcluido() {
        $this->sql = 'SELECT idsituacao FROM matriculas_workflow WHERE fim = "S" AND ativo = "S" ORDER BY idsituacao DESC LIMIT 1';
        return $this->retornarLinha($this->sql);
    }

    public function retornarMotivoCancelar()
    {
        $this->sql = "select * FROM motivos_cancelamento where ativo = 'S' and cancela_automatico = 'S' order by idmotivo desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoAtiva() {
        $this->sql = 'SELECT idsituacao FROM matriculas_workflow WHERE ativa = "S" AND ativo = "S" ORDER BY idsituacao DESC LIMIT 1';
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoInativa()
    {
        $this->sql = 'SELECT * FROM matriculas_workflow WHERE ativo = "S" AND inativa = "S" ORDER BY idsituacao DESC LIMIT 1';
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoCancelada() {
        $this->sql = 'SELECT idsituacao FROM matriculas_workflow WHERE cancelada = "S" AND ativo = "S" ORDER BY idsituacao DESC LIMIT 1';
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoInicial()
    {
        $this->sql = "select * FROM matriculas_workflow where ativo = 'S' and inicio = 'S' order by idsituacao desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    public function situacaoAtualMatricula($idmatricula)
    {
        try {
            if(gettype($idmatricula) != "integer"){
                throw new InvalidArgumentException("Para realizar a consulta da situação atual da matricula, o valor da matrícula precisa ser um inteiro!");
            } else {
                $this->sql = "select m.idsituacao, mw.nome from matriculas m inner join matriculas_workflow mw on m.idsituacao = mw.idsituacao where idmatricula = ${idmatricula}";
                return $this->retornarLinha($this->sql);
            }
        }catch (InvalidArgumentException $e) {
            echo "Ops! {$e->getMessage()}";
        }
    }

    /**
     * @access public
     * @param int $idMatricula
     * @return float
     */
    public function porcentagemCursoAtual($idMatricula)
    {
        try {
            if(!is_numeric($idMatricula))
            {
                throw new InvalidArgumentException("Para realizar a consulta da porcentagem atual do curso, o valor da matrícula precisa ser um inteiro!");
            } else {
                $porcentagensAvas = $this->retornarAvas();
                $qtdAvas = count($porcentagensAvas);
                $porcentagemCursoAtual = 0;
                foreach ($porcentagensAvas as $porcentagemAva)
                {
                    $porcentagemCursoAtual += $porcentagemAva['porcentagem'];
                }

                return $porcentagemCursoAtual / $qtdAvas;
            }

        } catch (InvalidArgumentException $e) {
            echo "Ops! {$e->getMessage()}";
        }
    }

    public function workFlowMatriculasRelacionadasComSituacaoConcluido()
    {

        $situacaoConcluida = $this->retornarSituacaoConcluido();

        $this->sql = "SELECT r.idrelacionamento, r.idsituacao_de, a1.idapp as idsituacao_de_app, r.idsituacao_para, a2.idapp as idsituacao_para_app FROM matriculas_workflow_relacionamentos r inner join matriculas_workflow a1 on (a1.idsituacao = r.idsituacao_de) inner join matriculas_workflow a2 on (a2.idsituacao = r.idsituacao_para) WHERE r.ativo = 'S' and a1.ativo = 'S' and a2.ativo = 'S' and r.idsituacao_para = {$situacaoConcluida['idsituacao']}";

        $this->ordem_campo = null;

        return $this->retornarLinhas();
    }

    public function Retornar() {

        if (!is_numeric($this->id)) {
            throw new InvalidArgumentException('O parametro `ID` precisa ser um valor numérico.');
        }

        $this->sql = 'SELECT
                            m.*,
                            IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) AS porcentagem,
                            i.acesso_ava
                        FROM
                            matriculas m
                            INNER JOIN sindicatos i ON (i.idsindicato = m.idsindicato)
                        WHERE
                            m.idmatricula = '.$this->id;

        if ($this->idusuario && $_SESSION['adm_gestor_sindicato'] <> 'S')
            $this->sql .= ' AND m.idsindicato in ('.$_SESSION["adm_sindicatos"].')';

        if ($this->idvendedor)
            $this->sql .= ' AND m.idvendedor = '.$this->idvendedor;

        if ($this->idpessoa)
            $this->sql .= ' AND m.idpessoa = '.$this->idpessoa;

        $this->sql .= ' AND m.ativo = "S"';

        $this->matricula = $this->retornarLinha($this->sql);

        if (!$this->matricula) {
            return null;
        }

        if (!$this->idpessoa) {
            if ($this->matricula['idmotivo_cancelamento']) {
                $this->sql = 'SELECT * FROM motivos_cancelamento where idmotivo = '.$this->matricula['idmotivo_cancelamento'];
                $this->matricula['motivo_cancelamento'] = $this->retornarLinha($this->sql);
            }

            if ($this->matricula['idmotivo_inativo']) {
                $this->sql = 'SELECT * FROM motivos_inatividade where idmotivo = '.$this->matricula['idmotivo_inativo'];
                $this->matricula["motivo_inativo"] = $this->retornarLinha($this->sql);
            }

            if ($this->matricula['idbandeira']) {
                $this->sql = 'SELECT * FROM bandeiras_cartoes where idbandeira = '. $this->matricula['idbandeira'];
                $this->matricula['bandeira'] = $this->retornarLinha($this->sql);
            }
        }

        $this->retorno = $this->matricula;
        return $this->retorno;
    }

    public function retornarAvas()
    {
        $this->sql = 'select d.nome as nome_disciplina,
                      ocp.idcurriculo,
                      ocp.idoferta,
                      oca.idava,
                      oca.iddisciplina,
                      If(map.porcentagem_manual > map.porcentagem, map.porcentagem_manual,
                      map.porcentagem) as porcentagem,
                      map.data_ini,
                      map.data_fim,
                      ava.contabilizar_datas,
                      ava.carga_horaria_min as carga_min,
                      avas_avaliacoes.nota_minima
                    from matriculas m
                      inner join ofertas_cursos_escolas ocp on m.idoferta = ocp.idoferta and
                        m.idescola = ocp.idescola and m.idcurso = ocp.idcurso and (ocp.ativo = "S")
                      inner join ofertas_curriculos_avas oca on ocp.idoferta = oca.idoferta and
                        ocp.idcurriculo = oca.idcurriculo and (oca.ativo = "S" and
                        oca.idava is not null)
                      inner join curriculos_blocos cb on cb.idcurriculo = oca.idcurriculo and
                        (cb.ativo = "S")
                      inner join curriculos_blocos_disciplinas cbd on cbd.iddisciplina =
                        oca.iddisciplina and (cbd.ativo = "S")
                      inner join disciplinas d on d.iddisciplina = cbd.iddisciplina
                      left outer join avas ava on ava.idava = oca.idava and (ava.ativo = "S")
                      left outer join matriculas_avas_porcentagem map on m.idmatricula =
                        map.idmatricula and oca.idava = map.idava
                      left join avas_avaliacoes on ava.idava = avas_avaliacoes.idava and avas_avaliacoes.ativo = "S"
                    where m.idmatricula = ' . (int) $this->id . ' and m.idpessoa = ' . (int) $this->idpessoa . ' and m.ativo = "S"
                    group by oca.idava,
                      cbd.iddisciplina,
                      avas_avaliacoes.nota_minima
        ';
        $this->limite = -1;
        $this->ordem_campo = 'oca.idava';
        $this->ordem = 'ASC';

        $disciplinas = $this->retornarLinhas();
        $disciplinasEmOrdem = $this->retornarDisciplinasCurriculoOrdem();
        $retorno = [];
        $avas = [];

        foreach ($disciplinas as $disciplina) {
            if (
                isset($disciplinasEmOrdem[$disciplina['iddisciplina']])
                && !in_array($disciplina['idava'], $avas)
            ) {
                $retorno[$disciplinasEmOrdem[$disciplina['iddisciplina']]] = $disciplina;
                $retorno[$disciplinasEmOrdem[$disciplina['iddisciplina']]]['avaliacao_pendente'] =
                    $this->possuiAvaliacaoPendente($this->id, $disciplina['idava']);
                $avas[] = $disciplina['idava'];
            }
        }
        ksort($retorno);
        return array_values($retorno);
    }

    public function retornarAvasDisciplinas($avas) {
        $avasDisciplinas = array();

        foreach ($avas as $ava) {
            $this->sql = 'SELECT
                            d.nome as disciplina
                        FROM
                            disciplinas d
                            INNER JOIN curriculos_blocos_disciplinas cbd ON (d.iddisciplina = cbd.iddisciplina AND cbd.ativo = "S")
                            INNER JOIN curriculos_blocos cb ON (cbd.idbloco = cb.idbloco AND cb.ativo = "S")
                            INNER JOIN ofertas_curriculos_avas oca ON (cb.idcurriculo = oca.idcurriculo AND cbd.iddisciplina = oca.iddisciplina AND oca.ativo = "S")
                        WHERE
                            d.ativo = "S" AND
                            cb.idcurriculo = '.$ava['idcurriculo'].' AND
                            oca.idoferta = '.$ava['idoferta'].' AND
                            oca.idava = '.$ava['idava'];
            $this->limite = -1;
            $this->ordem_campo = 'cb.ordem ASC, cb.nome ASC, cbd.ordem ASC, d.nome';
            $this->ordem = 'ASC';
            $disciplinas = $this->retornarLinhas();

            $avasDisciplinas[$ava['idava']]['idava'] = $ava['idava'];
            $avasDisciplinas[$ava['idava']]['porcentagem'] = $ava['porcentagem'];
            $avasDisciplinas[$ava['idava']]['nota_minima'] = $ava['nota_minima'];
            $avasDisciplinas[$ava['idava']]['data_ini'] = $ava['data_ini'];
            $avasDisciplinas[$ava['idava']]['data_fim'] = $ava['data_fim'];
            $avasDisciplinas[$ava['idava']]['contabilizar_datas'] = $ava['contabilizar_datas'];
            $avasDisciplinas[$ava['idava']]['carga_min'] = $ava['carga_min'];
            $avasDisciplinas[$ava['idava']]['avaliacao_pendente'] = $ava['avaliacao_pendente'];
            $avasDisciplinas[$ava['idava']]['disciplinas'] = $disciplinas;
        }

        return $avasDisciplinas;
    }

    public function retornarRotaDeAprendizagem($idava) {
        $this->sql = 'SELECT
                        ara.idava,
                        arao.*
                      FROM
                        avas_rotas_aprendizagem ara
                        INNER JOIN avas_rotas_aprendizagem_objetos arao ON (ara.idrota_aprendizagem = arao.idrota_aprendizagem and arao.ativo = "S")
                      WHERE
                        ara.idava = '.intval($idava).' AND
                        ara.ativo = "S"';
        $this->ordem = "asc";
        $this->ordem_campo = "arao.ordem ASC, arao.data_cad ASC, arao.idobjeto";
        $this->limite = -1;
        $this->groupby = 'arao.idobjeto';
        $objetosRota = $this->retornarLinhas();
        foreach ($objetosRota as $ind => $objetoRota) {
            $objetosRota[$ind]['objeto'] = $this->retornarObjeto($objetoRota);
        }

        return $objetosRota;
    }

    public function ultimoConteudoRotaAprendizagem($idava) {
        $ultimaRota = NULL;
        $campos = 'ara.idava, arao.*';
        $sql = 'SELECT
                    '.$campos.'
                FROM
                    avas_rotas_aprendizagem ara
                    INNER JOIN avas_rotas_aprendizagem_objetos arao ON (ara.idrota_aprendizagem = arao.idrota_aprendizagem and arao.ativo = "S")
                WHERE
                    ara.idava = '.intval($idava).' AND
                    arao.tipo <> "objeto_divisor" AND
                    ara.ativo = "S" ORDER BY arao.ordem DESC';
        ## Paginação
        $linha = $this->retornarLinha($sql);
        return $linha['idobjeto'];
    }

    // avaliação concluida
    public function avaliacoesConcluidas($idmatricula) {
        $this->sql = 'SELECT
						mavl.*,
						aa.idavaliacao,
                        aa.nome as avaliacao,
                        aa.idava,
                        aa.periode_de,
                        aa.periode_ate,
                        aa.imagem_exibicao_servidor
					FROM
						avas_avaliacoes aa
                        INNER JOIN matriculas_avaliacoes mavl ON (mavl.idavaliacao = aa.idavaliacao)
					WHERE
                        mavl.idmatricula = '.(int) $idmatricula.' AND
						aa.ativo = "S" AND
                        mavl.ativo = "S" ';
        $this->limite = -1;
        $this->ordem = 'DESC';
        $this->ordem_campo = 'mavl.idprova';
        $this->groupby = 'aa.idava';
        $avaliacoes = $this->retornarLinhas();
        return $avaliacoes;
    }

    public function possuiAvaliacaoPendente($idMatricula, $idAva)
    {
        $sql = "SELECT
                MAX(ma.nota) as nota,
                aa.nota_minima
            FROM
                avas_avaliacoes aa
            LEFT JOIN matriculas_avaliacoes ma ON (
                ma.idavaliacao = aa.idavaliacao
                AND ma.idmatricula = {$idMatricula}
                AND ma.ativo = 'S'
            )
            WHERE
                aa.ativo = 'S'
            AND aa.exibir_ava = 'S'
            AND aa.idava = {$idAva}
            GROUP BY aa.idavaliacao
        ";
        $query = mysql_query($sql);
        while ($linha = mysql_fetch_assoc($query)) {
            if (
                empty($linha['nota'])
                || $linha['nota'] < $linha['nota_minima']
            ) {
                return true;
            }
        }
        return false;
    }

    public function verificarTodosDownloadsEbooksFeitos($idmatricula, $idava){
        $this->ordem_campo = false;
        $ordem_campo = $this->ordem_campo;

        $this->sql = "SELECT iddownload FROM avas_downloads where idava = '" . $idava . "' AND ebook='S' AND ativo='S'";
        $arquivos_avas = $this->retornarLinhas();
        if(empty($arquivos_avas)){
            return true;
        }
        $this->sql = "SELECT m.id, m.data_cad
                        from matriculas_alunos_historicos m
                        where m.idava = '" . $idava . "'
                         AND m.acao='download'
                          AND m.oque = 'arquivo'
                            AND m.idmatricula = '" . $idmatricula . "'";
        $downloads = $this->retornarLinhas();
        foreach($arquivos_avas as $arquivo_ava) {
            $downloadFeito = false;
            foreach($downloads as $download){
                if($arquivo_ava['iddownload'] == $download['id']){
                    $downloadFeito = true;
                }
            }
            if($downloadFeito == false){
                return false;
            }
        }
        $this->ordem_campo = $ordem_campo;
        return true;

    }

    public function retornarObjetoDaRotaDeAprendizagem($idava, $pagina = 1) {
        $campos = 'ara.idava, arao.*';
        $sql = 'SELECT
                    '.$campos.'
                FROM
                    avas_rotas_aprendizagem ara
                    INNER JOIN avas_rotas_aprendizagem_objetos arao ON (ara.idrota_aprendizagem = arao.idrota_aprendizagem and arao.ativo = "S")
                WHERE
                    ara.idava = '.intval($idava).' AND
                    arao.tipo <> "objeto_divisor" AND
                    ara.ativo = "S"';

        ## Paginação
        $sqlCount = str_replace($campos, ' count(arao.idobjeto) as total', $sql);
        $total = $this->retornarLinha($sqlCount);

        $total = (int) $total['total'];

        $limite = 1;

        if((int) $pagina <= 0) $pagina = 1;
        ## /Paginação

        $sql .= ' ORDER BY arao.ordem ASC, arao.data_cad ASC, arao.idobjeto ASC';

        ## Conteudo vizualizado
        $inicio = ($pagina - 1) * $limite;
        $sqlObjeto = ' LIMIT '.$inicio.', '.$limite;
        $objetosRota = $this->retornarLinha($sql.$sqlObjeto);
        $objetosRota['objeto'] = $this->retornarObjeto($objetosRota);
        ## /Conteudo vizualizado

        ## Conteudo anterior
        if($pagina > 1) {
            $inicio = ($pagina - 2) * $limite;
            $sqlObjeto = ' LIMIT '.$inicio.', '.$limite;
            $objetosRota['objeto_anterior'] = $this->retornarLinha($sql.$sqlObjeto);
            $objetosRota['objeto_anterior']['objeto'] = $this->retornarObjeto($objetosRota['objeto_anterior']);
        }

        if($pagina > 2) {
            $inicio = ($pagina - 3) * $limite;
            $sqlObjeto = ' LIMIT '.$inicio.', '.$limite;
            $objetosRota['objeto_anterior_anterior'] = $this->retornarLinha($sql.$sqlObjeto);
            $objetosRota['objeto_anterior_anterior']['objeto'] = $this->retornarObjeto($objetosRota['objeto_anterior_anterior']);
        }
        ## /Conteudo anterior

        ## Conteudo proximo
        $inicio = ($pagina) * $limite;
        $sqlObjeto = ' LIMIT '.$inicio.', '.$limite;
        $objetosRota['objeto_proximo'] = $this->retornarLinha($sql.$sqlObjeto);
        if($objetosRota['objeto_proximo']['idobjeto'])
            $objetosRota['objeto_proximo']['objeto'] = $this->retornarObjeto($objetosRota['objeto_proximo']);
        else
            unset($objetosRota['objeto_proximo']);

        $inicio = ($pagina + 1) * $limite;
        $sqlObjeto = ' LIMIT '.$inicio.', '.$limite;
        $objetosRota['objeto_proximo_proximo'] = $this->retornarLinha($sql.$sqlObjeto);
        if($objetosRota['objeto_proximo_proximo']['idobjeto'])
            $objetosRota['objeto_proximo_proximo']['objeto'] = $this->retornarObjeto($objetosRota['objeto_proximo_proximo']);
        else
            unset($objetosRota['objeto_proximo_proximo']);
        ## /Conteudo proximo

        return $objetosRota;
    }

    public function retornarObjeto($objetoRota)
    {
        switch ($objetoRota["tipo"]) {
            case 'audio':
                $this->sql = 'SELECT * FROM avas_audios WHERE idaudio = '.$objetoRota["idaudio"];
                $objeto = $this->retornarLinha($this->sql);
                break;
            case 'conteudo':
                $this->sql = 'SELECT * FROM avas_conteudos WHERE idconteudo = '.$objetoRota["idconteudo"];
                $objeto = $this->retornarLinha($this->sql);
                break;
            case 'objeto_divisor':
                $this->sql = 'SELECT * FROM avas_objetos_divisores WHERE idobjeto_divisor = '.$objetoRota["idobjeto_divisor"];
                $objeto = $this->retornarLinha($this->sql);
                break;
            case 'download':
                $this->sql = 'SELECT * FROM avas_downloads WHERE iddownload = '.$objetoRota["iddownload"];
                $objeto = $this->retornarLinha($this->sql);
                break;
            case 'link':
                $this->sql = 'SELECT * FROM avas_links WHERE idlink = '.$objetoRota["idlink"];
                $objeto = $this->retornarLinha($this->sql);
                break;
            case 'pergunta':
                $this->sql = 'SELECT * FROM avas_perguntas WHERE idpergunta = '.$objetoRota["idpergunta"];
                $objeto = $this->retornarLinha($this->sql);
                break;
            case 'video':
                $this->sql = 'SELECT
                                    *,
                                    titulo AS nome
                                FROM
                                    videotecas
                                WHERE
                                    idvideo = ' . (int) $objetoRota['idvideo'];
                $objeto = $this->retornarLinha($this->sql);
                break;
            case 'exercicio':
                $this->sql = 'SELECT * FROM avas_exercicios WHERE idexercicio = '.$objetoRota["idexercicio"];
                $objeto = $this->retornarLinha($this->sql);
                break;
            case 'simulado':
                $this->sql = 'SELECT * FROM avas_simulados WHERE idsimulado = '.$objetoRota["idsimulado"];
                $objeto = $this->retornarLinha($this->sql);
                break;
            case 'enquete':
                $this->sql = 'SELECT *, "Enquete" as nome from avas_enquetes WHERE idenquete = '.$objetoRota["idenquete"];
                $objeto = $this->retornarLinha($this->sql);
                //$opcoesVerifica = $this->retornaOpcoesVerificaVotoEnquete($objeto["idenquete"], $idbloco_disciplina);
                //$objeto["objeto"]["opcoes"] = $opcoesVerifica['opcoes'];
                //$objeto["objeto"]["votou"] = $opcoesVerifica['votou'];
                //$objeto["objeto"]["total_votos"] = $opcoesVerifica['total_votos'];
                break;
        }

        return $objeto;
    }

    public function retornarAva($idava) {
        $this->sql = 'SELECT * FROM avas WHERE idava = '.(int)$idava;
        return $this->retornarLinha($this->sql);
    }

    public function retornarAvaMatricula($idava)
    {
        $this->sql = "SELECT
                        a.*, d.nome as disciplina
                    FROM
                        matriculas m
                        INNER JOIN ofertas_cursos_escolas ocp ON (ocp.idoferta = m.idoferta AND ocp.idescola = m.idescola AND ocp.idcurso = m.idcurso AND ocp.ativo = 'S')
                        INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = ocp.idoferta AND oca.idcurriculo = ocp.idcurriculo AND oca.ativo = 'S')
                        INNER JOIN disciplinas d USING (iddisciplina)
                        INNER JOIN avas a ON (a.idava = oca.idava)
                    WHERE
                        m.idmatricula = '".(int)$this->id."' AND
                        m.idpessoa = '".(int)$this->idpessoa."' AND
                        a.idava = '".(int)$idava."' AND
                        m.ativo = 'S'
                    GROUP BY a.idava";
        return $this->retornarLinha($this->sql);
    }

    /**
     * Método para buscar todas informações da tabela matriculas_avas_porcentagem filtrando pela id do ava e id da matrícula
     * @access public
     * @param int $idMatricula
     * @param int $idAva
     * @return array
     */
    public function matriculaAvaPorcentagem($idAva, $idMatricula){

        try {
            if(!is_int($idMatricula) || !is_int($idAva)) {
                throw new InvalidArgumentException('Parâmetros idMatricula e idAva tem que ser do tipo inteiro.');
            } else {
                $this->sql = "SELECT * FROM matriculas_avas_porcentagem WHERE idmatricula = {$idMatricula} AND idava = {$idAva}";
                return $this->retornarLinha($this->sql);
            }
        }catch (InvalidArgumentException $i){
            echo "Ops! ocorreu um erro: {$i->getMessage()}";
        }

    }

    public function substituiVariaveisConteudo($conteudo, $variaveisConteudo = null) {
        $sql = 'SELECT
                    p.*
                FROM
                    pessoas p
                    INNER JOIN matriculas m ON (p.idpessoa = m.idpessoa)
                WHERE
                    m.idmatricula = '.$this->id;
        $pessoa = $this->retornarLinha($sql);
        foreach ($pessoa as $coluna => $dados) {
            //$dados = utf8_decode($dados);
            $dados = htmlentities($dados);
            $conteudo = str_ireplace('[[aluno]['.$coluna.']]', $dados, $conteudo);

            if ($coluna == 'nome') {
                $nome = explode(' ', $dados);
                $conteudo = str_ireplace('[[aluno][primeiro_nome]]', $nome[0], $conteudo);
            }
        }

        $conteudo = str_ireplace('[[conteudo][conteudo_anterior]]', $variaveisConteudo["linkAnterior"], $conteudo);
        $conteudo = str_ireplace('[[conteudo][proximo_conteudo]]', $variaveisConteudo["linkProximo"], $conteudo);

        return $conteudo;
    }

    public function mudarDataFim($idava){
        if (verificaPermissaoAcesso(false)) {
            $idava = (int)$idava;
            $this->sql = "UPDATE matriculas_avas_porcentagem SET data_fim = NOW() WHERE idmatricula = {$this->id} AND idava = {$idava} AND data_fim IS NULL";
            $this->executaSql($this->sql);
        }
    }

    public function contabilizarRota() {
        if (verificaPermissaoAcesso(false)) {
            if (senhaSegura($this->id, $GLOBALS['config']['chaveLogin']) == $this->post['idmatricula'] &&
                senhaSegura($this->post['ava'], $GLOBALS['config']['chaveLogin']) == $this->post['idava'] &&
                senhaSegura($this->post['objeto'], $GLOBALS['config']['chaveLogin']) == $this->post['idobjeto']) {
                $verifica = $this->verificaContabilizado($this->post['ava'], 'objeto', $this->post['objeto']);
                if (!$verifica) {
                    $this->executaSql("BEGIN");

                    $this->sql = 'SELECT
                                    IFNULL(arao.porcentagem,0) AS porcentagem
                                FROM
                                    avas_rotas_aprendizagem ara
                                    INNER JOIN avas_rotas_aprendizagem_objetos arao ON (ara.idrota_aprendizagem = arao.idrota_aprendizagem and arao.ativo = "S")
                                WHERE
                                    arao.idobjeto = '.$this->post['objeto'].' AND
                                    ara.idava = '.$this->post['ava'].' AND
                                    ara.ativo = "S"';
                    $porcentagem = $this->retornarLinha($this->sql);

                    $this->sql = 'INSERT INTO
                                    matriculas_rotas_aprendizagem_objetos
                                  SET
                                    data_cad = now(),
                                    idmatricula = '.$this->id.',
                                    idava = '.$this->post['ava'].',
                                    idobjeto = '.$this->post['objeto'].',
                                    porcentagem = '.$porcentagem['porcentagem'];
                    if ($this->executaSql($this->sql)) {
                        $this->sql = 'SELECT
                                        idmatricula_ava_porcentagem,
                                        porcentagem,
                                        data_ini,
                                        COUNT(*) AS total
                                    FROM
                                        matriculas_avas_porcentagem
                                    WHERE
                                        idmatricula = '.$this->id.' AND
                                        idava = '.$this->post['ava'];
                        $verificaPorcentagem = $this->retornarLinha($this->sql);
                        if (!$verificaPorcentagem['total']) {
                            $this->sql = 'INSERT INTO matriculas_avas_porcentagem SET idmatricula = '.$this->id.', data_ini = NOW(), idava = '.$this->post['ava'].', porcentagem = '.$porcentagem['porcentagem'];
                        } else {
                            $condUpdDataInicio = '';
                            if (empty($verificaPorcentagem['data_ini'])) {
                                $condUpdDataInicio = ", data_ini = NOW()";
                            }
                            $this->sql = "UPDATE matriculas_avas_porcentagem SET
                                            porcentagem = IF((porcentagem +
                                            {$porcentagem['porcentagem']}) > 100, 100, (porcentagem +
                                            {$porcentagem['porcentagem']}))
                                            {$condUpdDataInicio}
                                        WHERE
                                            idmatricula_ava_porcentagem = {$verificaPorcentagem['idmatricula_ava_porcentagem']}";
                        }

                        if ($this->executaSql($this->sql)) {
                            $avas = $this->retornarAvas();
                            $qtdAvas = count($avas);
                            foreach ($avas as $ava) {
                                $porcentagemTotal += floatval($ava['porcentagem']);
                            }
                            $porcentagemTotal = $porcentagemTotal / $qtdAvas;
                            $sql = 'UPDATE matriculas SET porcentagem='.$porcentagemTotal.' WHERE idmatricula = ' . $this->id;
                            if ($this->executaSql($sql)) {
                                $this->sql = 'SELECT IF(porcentagem_manual > porcentagem, porcentagem_manual, porcentagem) as porcentagem FROM matriculas WHERE idmatricula = '.$this->id;
                                $matriculaPorcentagem = $this->retornarLinha($this->sql);
                                if($matriculaPorcentagem['porcentagem'] > 100) $matriculaPorcentagem['porcentagem'] = '100';

                                $this->executaSql("COMMIT");

                                $retorno['porcentagem'] = $porcentagemTotal;
                                $retorno['porcentagem_formatada'] = number_format($porcentagemTotal, 2, ",", ".");
                                $retorno['sucesso'] = true;
                            } else {
                                $this->executaSql("ROLLBACK");
                                $retorno['erro'] = true;
                                $retorno['erros'][] = $this->sql;
                                $retorno['erros'][] = mysql_error();
                            }
                        } else {
                            $this->executaSql("ROLLBACK");
                            $retorno['erro'] = true;
                            $retorno['erros'][] = $this->sql;
                            $retorno['erros'][] = mysql_error();
                        }
                    } else {
                        $this->executaSql("ROLLBACK");
                        $retorno['erro'] = true;
                        $retorno['erros'][] = $this->sql;
                        $retorno['erros'][] = mysql_error();
                    }
                } else {
                    $retorno['erro'] = true;
                    $retorno['erros'][] = 'ja_contabilizado';
                }
            } else {
                $retorno['erro'] = true;
                $retorno['erros'][] = 'dados_nao_conferem';
            }

            return json_encode($retorno);
        }
    }

    public function verificaContabilizado($idava, $tipo, $id) {

        switch($tipo) {
            case 'chat':
                $chave = 'idchat';
                break;
            case 'forum':
                $chave = 'idforum';
                break;
            case 'download':
                $chave = 'iddownload';
                break;
            case 'tiraduvida':
                $this->sql = 'SELECT
                                    COUNT(*) AS total
                                FROM
                                    matriculas_rotas_aprendizagem_objetos
                                WHERE
                                    idmatricula = '.$this->id.' AND
                                    idava = '.$idava.' AND
                                    (
                                        idtiraduvida = '.$id.' OR
                                        idmensagem_instantanea = '.$id.'
                                    )';
                break;
            case 'simulado':
                $chave = 'idsimulado';
                break;
            default:
                $chave = 'idobjeto';
                break;
        }

        if ($tipo != 'tiraduvida') {
            $this->sql = 'SELECT
                                COUNT(*) AS total
                            FROM
                                matriculas_rotas_aprendizagem_objetos
                            WHERE
                                idmatricula = '.$this->id.' AND
                                idava = '.$idava.' AND
                                '.$chave.' = '.$id;
        }

        $verifica = $this->retornarLinha($this->sql);
        if ($verifica['total']) {
            return true;
        } else {
            return false;
        }

    }

    public function verificaFavorito($idava, $idobjeto) {

        $this->sql = 'SELECT
                        COUNT(*) AS total
                    FROM
                        matriculas_objetos_favoritos
                    WHERE
                        idmatricula = '.$this->id.' AND
                        idava = '.$idava.' AND
                        idobjeto = '.$idobjeto.' AND
                        ativo = "S"';
        $verifica = $this->retornarLinha($this->sql);
        if ($verifica['total']) {
            return true;
        } else {
            return false;
        }

    }

    public function inadimplente()
    {
        $data_atual = new DateTime();
        $this->sql = 'SELECT
                            c.idconta
                    FROM
                        contas c
                    INNER JOIN contas_workflow cw ON
                    (
                        cw.ativo = "S" AND
                        c.idsituacao = cw.idsituacao AND
                        (cw.emaberto = "S" OR
                        (cw.emaberto="N" AND cw.pago="N" AND cw.renegociada="N"
                            AND cw.transferida="N" AND cw.cancelada = "N" ))
                    )
                    WHERE
                        c.idmatricula = "'.$this->id.'" AND
                        c.ativo = "S" AND
                        c.data_vencimento < "'. $data_atual->format('Y-m-d').'"';
        $retorno = $this->retornarLinha($this->sql);
        if (!$retorno['idconta']) {
            return false;
        }
        return true;
    }

    public function favoritar() {
        if (verificaPermissaoAcesso(false)) {
            $this->sql = 'SELECT idfavorito, ativo FROM matriculas_objetos_favoritos WHERE idmatricula = '.$this->id.' AND idava = '.$this->post['idava'].' AND idobjeto = '.$this->post['idobjeto'];
            $verifica = $this->retornarLinha($this->sql);
            if (!$verifica['idfavorito']) {
                $this->sql = 'insert into
                                matriculas_objetos_favoritos
                              set
                                data_cad = now(),
                                idmatricula = '.$this->id.',
                                idava = '.$this->post['idava'].',
                                idobjeto = '.$this->post['idobjeto'];
                $this->monitora_oque = 1;
                $retorno['favorito'] = 'S';
                $tipoHistorico = 'cadastrou';
            } elseif ($verifica['ativo'] == 'N') {
                $this->sql = 'update matriculas_objetos_favoritos set ativo = "S" where idfavorito = '.$verifica['idfavorito'];
                $this->monitora_oque = 2;
                $this->monitora_dadosantigos = $verifica;
                $this->monitora_dadosnovos = array(
                    'idfavorito' => $verifica['idfavorito'],
                    'ativo' => 'S'
                );
                $retorno['favorito'] = 'S';
                $tipoHistorico = 'cadastrou';
            } else {
                $this->sql = 'update matriculas_objetos_favoritos set ativo = "N" where idfavorito = '.$verifica['idfavorito'];
                $this->monitora_oque = 2;
                $this->monitora_dadosantigos = $verifica;
                $this->monitora_dadosnovos = array(
                    'idfavorito' => $verifica['idfavorito'],
                    'ativo' => 'N'
                );
                $retorno['favorito'] = 'N';
                $tipoHistorico = 'removeu';
            }
            if ($this->executaSql($this->sql)) {
                if ($verifica['idfavorito'])
                    $retorno['id'] = $verifica['idfavorito'];
                else
                    $retorno['id'] = mysql_insert_id();
                $this->cadastrarHistorioAluno($this->post['idava'], $tipoHistorico, 'favorito', $retorno['id']);
                $this->monitora_onde = '144';
                $this->monitora_qual = $retorno['id'];
                $this->Monitora();
                $retorno['sucesso'] = true;
            } else {
                $retorno['erro'] = true;
                $retorno['erros'][] = $this->sql;
                $retorno['erros'][] = mysql_error();
            }

            return json_encode($retorno);
        } else {
            $retorno['erro_json'] = 'sem_permissao';
            return json_encode($retorno);
        }
    }

    public function retornarContratoPendente($idMatriculaContrato)
    {
        $this->sql = 'SELECT
                mcg.*,
                "S" AS contrato_pendente,
                ct.nome as tipo,
                c.nome as contrato,
                m.data_cad as data_matricula
            FROM
                matriculas_contratos_gerados mcg
                INNER JOIN matriculas m ON (m.idmatricula = mcg.idmatricula)
                INNER JOIN contratos c ON (c.idcontrato = mcg.idcontrato)
                INNER JOIN contratos_tipos ct ON (ct.idtipo = c.idtipo)
            WHERE
                mcg.idmatricula_contrato = ' . $idMatriculaContrato . ' AND
                mcg.idmatricula = ' . $this->id . ' AND
                mcg.ativo = "S"';
        return $this->retornarLinha($this->sql);
    }

    public function processaAcoes($de, $para)
    {
        $this->sql = "SELECT idrelacionamento FROM matriculas_workflow_relacionamentos where idsituacao_de = " . $de . " and idsituacao_para = " . $para . " and ativo = 'S'";
        $relacionamento = $this->retornarLinha($this->sql);
        $this->sql = "SELECT
                    rwa.idacao,
                    rwa.idopcao
                  FROM
                    matriculas_workflow_acoes rwa
                  where
                    rwa.idrelacionamento = " . $relacionamento["idrelacionamento"] . " and
                    rwa.ativo = 'S' ";
        $this->limite = -1;
        $this->ordem_campo = "rwa.idopcao";
        if($relacionamento)
            $acoes = $this->retornarLinhas();
        $preRequisitos = array();
        foreach ($acoes as $acao) {
            foreach ($GLOBALS["workflow_parametros_matriculas"] as $opcao) {
                if ($opcao["idopcao"] == $acao["idopcao"] && $opcao["tipo"] == "acao") {
                    $preRequisitos[] = $acao;
                }
            }
        }
        if (count($preRequisitos) > 0) {
            $this->sql = 'SELECT * FROM matriculas where idmatricula = ' . intval($this->id);
            $matricula = $this->retornarLinha($this->sql);
            foreach ($preRequisitos as $ind => $preRequisito) {
                switch ($preRequisito['idopcao']) {
                    case 29:
                        try {
                            $data_de_conclusao = new DateTime(formataData($this->post['data_conclusao'], 'en', 0));
                        } catch (Exception $e) {
                            return $e;
                        }
                        if($this->post['data_conclusao'] == null){
                            $data_formatada = $data_de_conclusao->format('Y-m-d');// DATA ATUAL = now()
                        }else{
                            $data_formatada = $data_de_conclusao->format('Y-m-d');// DATA INFORMADA PELO USUARIO
                        }
                        $update = sprintf('UPDATE matriculas SET data_conclusao = "%s" WHERE idmatricula = %d', $data_formatada, $this->id);
                        $this->executaSql($update);
                        $this->AdicionarHistorico($this->idusuario, "data_conclusao", "modificou", $matricula["data_conclusao"], $data_formatada, NULL);
                        break;
                    case 31:
                        $data_atual = new DateTime('now');
                        $data_atual_formatada = $data_atual->format('Y-m-d');
                        $update = sprintf('UPDATE matriculas SET data_comissao = "%s" WHERE idmatricula = %d', $data_atual_formatada, $this->id);
                        $this->executaSql($update);
                        $this->AdicionarHistorico($this->idusuario, "data_comissao", "modificou", $matricula["data_comissao"], $data_atual_formatada, NULL);
                        break;
                    case 33:
                        $this->sql = 'select nome, celular from pessoas where idpessoa = ' . $matricula["idpessoa"];
                        $pessoa = $this->retornarLinha($this->sql);

                        $this->sql = 'select nome from cursos where idcurso = ' . $matricula['idcurso'];
                        $curso = $this->retornarLinha($this->sql);

                        //Texto do e-mail
                        $this->sql = "SELECT idparametro, valor FROM matriculas_workflow_acoes_parametros WHERE idacao = " . $preRequisito["idacao"] . " AND idparametro = 1";
                        $parametro = $this->retornarLinha($this->sql);

                        if ($pessoa["celular"] && $parametro["valor"]) {

                            $sms = $parametro["valor"];

                            $sms = str_ireplace("[[CURSO][NOME]]", $curso["nome"], $sms);
                            $sms = str_ireplace("[[ALUNO][EMAIL]]", $pessoa["email"], $sms);
                            $sms = substr($sms, 0, 160);

                            $this->enviarSms($matricula['idmatricula'], 'M', $pessoa['nome'], $pessoa['celular'], $sms);
                        }
                        break;
                    case 34:
                        $this->sql = 'select nome, celular from pessoas where idpessoa = ' . $matricula["idpessoa"];
                        $pessoa = $this->retornarLinha($this->sql);

                        $this->sql = 'select nome from cursos where idcurso = ' . $matricula['idcurso'];
                        $curso = $this->retornarLinha($this->sql);

                        //Texto do e-mail
                        $this->sql = 'SELECT idparametro, valor FROM matriculas_workflow_acoes_parametros WHERE idacao = ' . $preRequisito['idacao'] . ' AND idparametro = 2';
                        $parametro = $this->retornarLinha($this->sql);

                        if ($pessoa["celular"] && $parametro["valor"]) {
                            $sms = $parametro["valor"];

                            $sms = str_ireplace("[[CURSO][NOME]]", $curso["nome"], $sms);
                            $sms = str_ireplace("[[EMPRESA]]", $this->config['tituloEmpresa'], $sms);
                            $sms = substr($sms, 0, 160);

                            $this->enviarSms($matricula['idmatricula'], 'M', $pessoa['nome'], $pessoa['celular'], $sms);
                        }
                        break;
                    case 35:
                        $this->sql = 'select nome, celular from pessoas where idpessoa = ' . $matricula["idpessoa"];
                        $pessoa = $this->retornarLinha($this->sql);

                        $this->sql = 'select nome from cursos where idcurso = ' . $matricula['idcurso'];
                        $curso = $this->retornarLinha($this->sql);

                        //Texto do e-mail
                        $this->sql = 'SELECT idparametro, valor FROM matriculas_workflow_acoes_parametros WHERE idacao = ' . $preRequisito['idacao'] . ' AND idparametro = 3';
                        $parametro = $this->retornarLinha($this->sql);

                        if ($pessoa["celular"] && $parametro["valor"]) {
                            $sms = $parametro["valor"];

                            $sms = str_ireplace("[[CURSO][NOME]]", $curso["nome"], $sms);
                            $sms = str_ireplace("[[MATRICULA][ID]]", $matricula["idmatricula"], $sms);
                            $sms = str_ireplace("[[EMPRESA]]", $this->config['tituloEmpresa'], $sms);
                            $sms = substr($sms, 0, 160);

                            $this->enviarSms($matricula['idmatricula'], 'M', $pessoa['nome'], $pessoa['celular'], $sms);
                        }
                        break;
                    case 73:
                        $this->sql = 'select * from pessoas where idpessoa = ' . $matricula["idpessoa"];
                        $pessoa = $this->retornarLinha($this->sql);

                        $this->sql = 'select * from cursos where idcurso = ' . $matricula['idcurso'];
                        $curso = $this->retornarLinha($this->sql);

                        //Texto do e-mail
                        $this->sql = 'SELECT idparametro, valor FROM matriculas_workflow_acoes_parametros WHERE idacao = ' . $preRequisito['idacao'] . ' AND idparametro = 2';
                        $parametro = $this->retornarLinha($this->sql);

                        $sql = 'SELECT * FROM ofertas WHERE idoferta = ' . $matricula['idoferta'];
                        $oferta = $this->retornarLinha($sql);

                        if ($pessoa["email"] && $parametro["valor"]) {
                            $email = $parametro["valor"];
                            $email = str_ireplace("[[MATRICULA]]", $matricula['idmatricula'], $email);
                            $email = str_ireplace("[[NUMERO_CONTRATO]]", $matricula['numero_contrato'], $email);
                            $email = str_ireplace("[[VALOR_CONTRATO]]", number_format($matricula['valor_contrato'], 2, ',', '.'), $email);
                            $email = str_ireplace("[[FORMA_PAGAMENTO]]", ($GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula['forma_pagamento']]), $email);
                            $email = str_ireplace("[[QUANTIDADE_PARCELAS]]", $matricula['quantidade_parcelas'], $email);
                            $email = str_ireplace("[[NOME_ALUNO]]", ($pessoa['nome']), $email);
                            $email = str_ireplace("[[ID_ALUNO]]", $pessoa['idpessoa'], $email);
                            $email = str_ireplace("[[CPF_CNPJ_ALUNO]]", $pessoa['documento'], $email);
                            $email = str_ireplace("[[EMAIL_ALUNO]]", $pessoa['email'], $email);
                            $email = str_ireplace("[[CURSO]]", ($curso['nome']), $email);
                            $email = str_ireplace("[[OFERTA]]", ($oferta['nome']), $email);
                            $email = str_ireplace("[[CURSO][NOME]]", $curso["nome"], $email);
                            $email = str_ireplace("[[MATRICULA][ID]]", $matricula["idmatricula"], $email);
                            $email = str_ireplace("[[EMPRESA]]", $this->config['tituloEmpresa'], $email);
                            $email = utf8_decode($email);

                            $nomeDe = utf8_decode($GLOBALS['config']['tituloSistema'] . ' - ' . $GLOBALS['config']['tituloEmpresa']);
                            if ($curso['email']) {
                                $emailDe = $curso['email'];
                            } else {
                                $emailDe = $GLOBALS['config']['emailSistema'];
                            }
                            $assunto = utf8_decode('Situação da matrícula alterada');
                            $nomePara = utf8_decode($pessoa['nome']);
                            $emailPara = $pessoa['email'];
                            $this->enviarEmail($nomeDe, $emailDe, $assunto, $email, $nomePara, $emailPara);
                        }
                        break;
                }
            }
        }
    }

    function verificaPreRequisito($idobjeto) {
        $retorno = true;

        $this->sql = 'SELECT
                        ae.*
                    FROM
                      avas_exercicios ae
                      INNER JOIN avas_rotas_aprendizagem_objetos arao ON (arao.idexercicio = ae.idexercicio)
                    WHERE
                        arao.idobjeto = '.$idobjeto.' AND
                        arao.ativo = "S"';
        $exercicio = $this->retornarLinha($this->sql);
        if($exercicio['idexercicio']) {
            $this->sql = 'SELECT * FROM matriculas_exercicios WHERE idexercicio = '.$exercicio['idexercicio'].' AND idmatricula = '.$this->id.' AND ativo = "S" ORDER BY nota DESC';
            $exercicioMatricula = $this->retornarLinha($this->sql);
            if (!$exercicioMatricula['idmatricula_exercicio'] || ($exercicioMatricula['idmatricula_exercicio'] && $exercicioMatricula['nota'] < $exercicio['nota_minima'])) {
                $retorno = false;
            }
        }

        return $retorno;
    }

    function verificaTodosPreRequisito($idava, $ordem) {
        $retorno = true;

        $this->sql = 'SELECT
                        arao.idobjeto_pre_requisito
                    FROM
                        avas_rotas_aprendizagem_objetos arao
                        INNER JOIN avas_rotas_aprendizagem ara ON (arao.idrota_aprendizagem = ara.idrota_aprendizagem AND ara.ativo = "S" )
                    WHERE
                        ara.idava = '.(int)$idava.' AND
                        arao.ordem <= '.(int)$ordem.' AND
                        arao.ativo = "S" AND
                        arao.idobjeto_pre_requisito IS NOT NULL ';
        $this->limite = -1;
        $this->ordem_campo = 'arao.ordem';
        $this->ordem = 'ASC';
        $objetos = $this->retornarLinhas();
        foreach($objetos as $objeto) {
            $retorno = $this->verificaPreRequisito($objeto['idobjeto_pre_requisito']);
            if(!$retorno) return $retorno;
        }

        return $retorno;
    }

    function retornarExercicio($idmatricula_exercicio) {
        $this->sql = 'SELECT
                        mep.*,
                        p.nome,
                        p.critica,
                        p.tipo,
                        p.multipla_escolha,
                        p.imagem_servidor
                    FROM
                        matriculas_exercicios_perguntas mep
                        INNER JOIN perguntas p ON (mep.idpergunta = p.idpergunta)
                    WHERE
                        mep.idmatricula_exercicio = '.$idmatricula_exercicio;
        $this->limite = -1;
        $this->ordem = 'ASC';
        $this->ordem_campo = 'mep.idmatricula_exercicio_pergunta';
        $perguntas = $this->retornarLinhas();
        foreach ($perguntas as $ind => $pergunta) {
            $this->sql = "SELECT
                            po.*,
                            IF(mepom.idmatricula_exercicio_opcao IS NULL, 'N', 'S') as marcada
                        FROM
                            perguntas_opcoes po
                            LEFT OUTER JOIN matriculas_exercicios_perguntas_opcoes_marcadas mepom ON (mepom.idmatricula_exercicio_pergunta = ".$pergunta['idmatricula_exercicio_pergunta']." AND po.idopcao = mepom.idopcao)
                        WHERE
                            po.idpergunta = ".$pergunta['idpergunta']." AND
                            po.ativo = 'S'";
            $this->limite = -1;
            $this->ordem = 'asc';
            $this->ordem_campo = 'ordem';
            $opcoes = $this->retornarLinhas();
            $perguntas[$ind]['opcoes'] = $opcoes;
        }
        return $perguntas;
    }

    function retornarPerguntasExercicio($disciplinas, $tipo, $dificudade, $quantidade) {
        $this->sql = 'SELECT
                        idpergunta,
                        nome
                    FROM
                        perguntas
                    WHERE
                        iddisciplina IN ('.$disciplinas.') AND
                        tipo = "'.$tipo.'" AND
                        ativo_painel = "S" AND
                        dificuldade = "'.$dificudade.'" AND
                        ativo = "S"
                    ORDER BY RAND() LIMIT '.$quantidade;
        $this->limite = -1;
        $this->ordem = false;
        $this->ordem_campo = false;
        $perguntas = $this->retornarLinhas();
        foreach ($perguntas as $ind => $pergunta) {
            $this->sql = 'SELECT * FROM
                            perguntas_opcoes
                        WHERE
                            idpergunta = '.$pergunta['idpergunta'].' AND
                            ativo = "S"';
            $this->limite = -1;
            $this->ordem = 'asc';
            $this->ordem_campo = 'ordem';
            $opcoes = $this->retornarLinhas();
            $perguntas[$ind]['opcoes'] = $opcoes;
        }

        return $perguntas;
    }

    function gerarExercicio($idexercicio) {

        $this->sql = 'SELECT * FROM avas_exercicios WHERE idexercicio = '.$idexercicio.' AND ativo = "S"';
        $exercicio = $this->retornarLinha($this->sql);

        $this->sql = 'SELECT * FROM avas_exercicios_disciplinas WHERE idexercicio = '.$exercicio['idexercicio'].' and ativo = "S"';
        $this->limite = -1;
        $this->ordem = false;
        $this->ordem_campo = false;
        $disciplinas = $this->retornarLinhas();
        $arrayIdDisciplinas = array();
        foreach ($disciplinas as $disciplina) {
            $arrayIdDisciplinas[] = $disciplina['iddisciplina'];
        }
        $arrayIdDisciplinas = implode(',', $arrayIdDisciplinas);

        $perguntas = array();
        $perguntasFaceis = array();
        $perguntasIntermediarias = array();
        $perguntasDificeis = array();
        if ($exercicio['objetivas_faceis'] > 0) {
            $perguntasFaceis = $this->retornarPerguntasExercicio($arrayIdDisciplinas, 'O', 'F', $exercicio['objetivas_faceis']);
        }
        if ($exercicio['objetivas_intermediarias'] > 0) {
            $perguntasIntermediarias = $this->retornarPerguntasExercicio($arrayIdDisciplinas, 'O', 'M', $exercicio['objetivas_intermediarias']);
        }
        if ($exercicio['objetivas_dificeis'] > 0) {
            $perguntasDificeis = $this->retornarPerguntasExercicio($arrayIdDisciplinas, 'O', 'D', $exercicio['objetivas_dificeis']);
        }
        $perguntas = array_merge($perguntasFaceis, $perguntasIntermediarias, $perguntasDificeis);
        shuffle($perguntas);

        $this->executaSql("BEGIN");
        $this->sql = 'INSERT INTO matriculas_exercicios SET inicio = NOW(), idexercicio = '.$idexercicio.', idmatricula = '.$this->id;
        $this->executaSql($this->sql);
        $idmatricula_exercicio = mysql_insert_id();
        foreach ($perguntas as $pergunta) {
            $this->sql = 'INSERT INTO matriculas_exercicios_perguntas SET idmatricula_exercicio = '.$idmatricula_exercicio.', idpergunta = '.$pergunta['idpergunta'];
            $this->executaSql($this->sql);
        }
        $this->executaSql("COMMIT");


        $exercicio = $this->retornarMatriculaExercicio($idmatricula_exercicio);

        return $exercicio;
    }

    function gerarRefazerRetornarExercicio($idexercicio, $refazer = false) {
        if (verificaPermissaoAcesso(true)) {
            if($refazer) {
                $exercicio = $this->gerarExercicio($idexercicio);
                $exercicio['acao'] = 'refazer';
            } else {
                $this->sql = 'SELECT * FROM avas_exercicios WHERE idexercicio = '.$idexercicio.' AND ativo = "S"';
                $exercicio = $this->retornarLinha($this->sql);

                $this->sql = 'SELECT * FROM matriculas_exercicios WHERE idexercicio = '.$idexercicio.' AND idmatricula = '.$this->id.' AND ativo = "S" ORDER BY nota DESC';
                $exercicioMatricula = $this->retornarLinha($this->sql);
                if ($exercicioMatricula['idmatricula_exercicio'] && $exercicioMatricula['nota'] >= $exercicio['nota_minima']) {
                    $exercicioMatricula['perguntas'] = $this->retornarExercicio($exercicioMatricula['idmatricula_exercicio']);
                    $exercicio = $exercicioMatricula;
                    $exercicio['acao'] = 'retornar';
                } else {
                    $exercicio = $this->gerarExercicio($idexercicio);
                    $exercicio['acao'] = 'gerar';
                }
            }

            return $exercicio;
        }
    }

    function salvarExercicio() {

        if (verificaPermissaoAcesso(true)) {
            $this->executaSql('BEGIN');

            $this->sql = 'SELECT COUNT(*) AS total FROM matriculas_exercicios_perguntas WHERE idmatricula_exercicio = '.$this->post['idmatricula_exercicio'];
            $totalPerguntas = $this->retornarLinha($this->sql);
            $corretas = 0;
            $perguntasCorrigir = array();
            $perguntasCorretas = array();

            foreach ($this->post['pergunta'] as $idmatricula_exercicio_pergunta => $opcoes) {
                $this->sql = "SELECT
                                p.*
                            FROM
                                perguntas p
                                INNER JOIN matriculas_exercicios_perguntas mep ON (p.idpergunta = mep.idpergunta)
                            WHERE
                                mep.idmatricula_exercicio_pergunta = ".$idmatricula_exercicio_pergunta;
                $pergunta = $this->retornarLinha($this->sql);

                $perguntasCorrigir[$pergunta['idpergunta']]['opcoes_certas'] = 1;
                $perguntasCorrigir[$pergunta['idpergunta']]['marcadas_certas'] = 0;
                $perguntasCorrigir[$pergunta['idpergunta']]['marcadas'] = 0;

                if ($pergunta['tipo'] == 'O' && $pergunta['multipla_escolha'] == 'S') {
                    $this->sql = 'SELECT COUNT(*) AS total FROM perguntas_opcoes WHERE idpergunta = '.$pergunta['idpergunta'].' AND correta = "S" AND ativo = "S"';
                    $totalCorretas = $this->retornarLinha($this->sql);
                    $perguntasCorrigir[$pergunta['idpergunta']]['opcoes_certas'] = $totalCorretas['total'];
                }

                if (is_array($opcoes['opcao'])) {

                    foreach ($opcoes['opcao'] as $opcao) {
                        $this->sql = 'SELECT idopcao, idpergunta, correta FROM perguntas_opcoes WHERE idopcao = '.$opcao;
                        $opcao = $this->retornarLinha($this->sql);

                        $perguntasCorrigir[$opcao['idpergunta']]['marcadas']++;

                        if ($opcao['correta'] == 'S')
                            $perguntasCorrigir[$opcao['idpergunta']]['marcadas_certas']++;

                        if ($perguntasCorrigir[$opcao['idpergunta']]['opcoes_certas'] == $perguntasCorrigir[$opcao['idpergunta']]['marcadas'] &&
                            $perguntasCorrigir[$opcao['idpergunta']]['opcoes_certas'] == $perguntasCorrigir[$opcao['idpergunta']]['marcadas_certas']) {
                            $perguntasCorretas[$opcao['idpergunta']] = $opcao['idpergunta'];
                        }
                        else {
                            unset($perguntasCorretas[$opcao['idpergunta']]);
                        }

                        $this->sql = 'INSERT INTO matriculas_exercicios_perguntas_opcoes_marcadas SET idmatricula_exercicio_pergunta = '.$idmatricula_exercicio_pergunta.', idopcao = '.$opcao['idopcao'];
                        $this->executaSql($this->sql);
                    }
                }
                else {
                    $this->sql = 'SELECT idopcao, idpergunta, correta FROM perguntas_opcoes WHERE idopcao = '.$opcoes['opcao'];
                    $opcao = $this->retornarLinha($this->sql);

                    $perguntasCorrigir[$opcao['idpergunta']]['marcadas']++;

                    if ($opcao['correta'] == 'S'){
                        $perguntasCorrigir[$opcao['idpergunta']]['marcadas_certas']++;
                    }

                    if ($perguntasCorrigir[$opcao['idpergunta']]['opcoes_certas'] == $perguntasCorrigir[$opcao['idpergunta']]['marcadas'] &&
                        $perguntasCorrigir[$opcao['idpergunta']]['opcoes_certas'] == $perguntasCorrigir[$opcao['idpergunta']]['marcadas_certas']) {
                        $perguntasCorretas[$opcao['idpergunta']] = $opcao['idpergunta'];
                    }
                    else {
                        unset($perguntasCorretas[$opcao['idpergunta']]);
                    }

                    $this->sql = 'INSERT INTO matriculas_exercicios_perguntas_opcoes_marcadas SET idmatricula_exercicio_pergunta = '.$idmatricula_exercicio_pergunta.', idopcao = '.$opcao['idopcao'];
                    $this->executaSql($this->sql);
                }
            }

            $corretas = count($perguntasCorretas);
            $erradas = $totalPerguntas['total'] - $corretas;
            $nota = number_format(((10 * $corretas) / $totalPerguntas['total']), 2, '.', '');

            $this->sql = 'UPDATE matriculas_exercicios SET fim = NOW(), corretas = '.$corretas.', erradas = '.$erradas.', nota = '.$nota.' where idmatricula_exercicio = '.$this->post['idmatricula_exercicio'];
            $this->executaSql($this->sql);

            $this->executaSql('COMMIT');

            return $retorno = array(
                'id' => $this->post['idmatricula_exercicio'],
                'sucesso' => true
            );
        }
    }

    function retornarMatriculaExercicio($idmatricula_exercicio) {
        $this->sql = 'SELECT * FROM matriculas_exercicios WHERE idmatricula_exercicio = '.$idmatricula_exercicio.' AND idmatricula = '.$this->id;
        $exercicio = $this->retornarLinha($this->sql);
        $exercicio['perguntas'] = $this->retornarExercicio($exercicio['idmatricula_exercicio']);

        return $exercicio;
    }

    public function retornarAnotacoes($idava, $idobjeto = NULL) {
        $anotacoes = array();

        $this->sql = 'select
                        *
                    FROM
                        matriculas_anotacoes
                    where
                        idmatricula = '.$this->id.' and
                        idava = '.$idava;
        if($idobjeto)
            $this->sql .= ' and idobjeto = '.$idobjeto;

        $this->sql .= ' and ativo = "S"';

        $this->ordem = 'DESC';
        $this->ordem_campo = 'data_cad DESC, idanotacao';
        $this->limite = -1;
        $anotacoes = $this->retornarLinhas();

        $ordem = $this->retornarOrdemObjetoRotaDeAprendizagem($idava);

        foreach ($anotacoes as $ind => $anotacao) {
            $anotacoes[$ind]["pagina"] = $ordem[$anotacao['idobjeto']];

            $anotacoes[$ind]["anotacao"] = nl2br($anotacao["anotacao"]);
        }

        return $anotacoes;
    }

    public function cadastrarAnotacao() {
        if (verificaPermissaoAcesso(false)) {
            $this->sql = 'insert into
                            matriculas_anotacoes
                        set
                          data_cad = now(),
                          idmatricula = '.$this->id.',
                          idava = '.$this->post['idava'].',
                          idobjeto = '.$this->post['idobjeto'].',
                          anotacao = "'.$this->post['anotacao'].'"';
            if ($this->executaSql($this->sql)) {
                $retorno['id'] = mysql_insert_id();

                $this->monitora_oque = 1;
                $this->monitora_onde = '143';
                $this->monitora_qual = $retorno['id'];
                $this->Monitora();

                $retorno['sucesso'] = true;
                $retorno['anotacoes'] = $this->retornarAnotacoes($this->post['idava'], $this->post['idobjeto']);
                $this->cadastrarHistorioAluno($this->post['idava'], 'cadastrou', 'anotacao', $retorno['id']);
            } else {
                $retorno['erro'] = true;
                $retorno['erros'][] = $this->sql;
                $retorno['erros'][] = mysql_error();
            }

            return json_encode($retorno);

        } else {
            $retorno['erro_json'] = 'sem_permissao';
            return json_encode($retorno);
        }
    }

    public function deletarAnotacao() {
        if (verificaPermissaoAcesso(false)) {
            $this->sql = 'UPDATE matriculas_anotacoes SET ativo = "N" WHERE idanotacao = '.$this->post['idanotacao'];
            if ($this->executaSql($this->sql)) {

                $this->monitora_oque = 3;
                $this->monitora_onde = '143';
                $this->monitora_qual = $this->post['idanotacao'];
                $this->Monitora();

                $retorno['sucesso'] = true;
                $retorno['anotacoes'] = $this->retornarAnotacoes($this->post['idava'], $this->post['idobjeto']);
                $this->cadastrarHistorioAluno($this->post['idava'], 'removeu', 'anotacao', $this->post['idanotacao']);

            } else {
                $retorno['erro'] = true;
                $retorno['erros'][] = $this->sql;
                $retorno['erros'][] = mysql_error();
            }

            return json_encode($retorno);

        } else {
            $retorno['erro_json'] = 'sem_permissao';
            return json_encode($retorno);
        }
    }

    public function cadastrarHistorioAluno($idava, $acao, $oque, $id = 'null') {
        if (verificaPermissaoAcesso(false)) {
            if (!$_SESSION['ultimo_acesso_ava']) {
                $_SESSION['ultimo_acesso_ava'] = date('Y-m-d H:i:s');
                $this->sql = 'UPDATE
                                matriculas
                            SET
                                ultimo_acesso_ava = now(),
                                total_acessos_ava = total_acessos_ava + 1
                            WHERE
                                idmatricula = '.$this->id;
                $this->executaSql($this->sql);
            }

            $this->sql = 'insert
                            matriculas_alunos_historicos
                        set
                            data_cad = now(),
                            idmatricula = '.$this->id.',
                            idava = '.$idava.',
                            acao = "'.$acao.'",
                            oque = "'.$oque.'",
                            id = '.$id;
            return $this->executaSql($this->sql);
        }
    }

    public function retornarUltimoObjetoContabilizado($idava) {
        $sql = 'SELECT
                    arao.idobjeto
                FROM
                    matriculas_rotas_aprendizagem_objetos mrao
                    INNER JOIN avas_rotas_aprendizagem_objetos arao ON (mrao.idobjeto = arao.idobjeto)
                    INNER JOIN avas_rotas_aprendizagem ara ON (arao.idrota_aprendizagem = ara.idrota_aprendizagem)
                WHERE
                    mrao.idmatricula = '.$this->id.' AND
                    ara.idava = '.$idava.'
                ORDER BY ordem DESC LIMIT 1';

        return $this->retornarLinha($sql);
    }

    public function retornarDeclaracoesMatricula($idmatricula) {
        $this->sql = 'SELECT
                        d.nome,
                        dt.nome as tipo,
                        sd.idsolicitacao_declaracao,
                        sd.data_solicitacao,
                        sd.situacao,
                        md.idmatriculadeclaracao,
                        md.data_cad
                    FROM
                        matriculas_solicitacoes_declaracoes sd
                        INNER JOIN declaracoes d ON (sd.iddeclaracao = d.iddeclaracao)
                        INNER JOIN declaracoes_tipos dt ON (d.idtipo = dt.idtipo)
                        LEFT OUTER JOIN matriculas_declaracoes md ON (sd.idmatriculadeclaracao = md.idmatriculadeclaracao AND md.ativo = "S" AND md.aluno_visualiza = "S")
                    WHERE
                        sd.idmatricula = '.$idmatricula.' AND
                        sd.ativo = "S"';
        $this->ordem = 'ASC';
        $this->ordem_campo = 'sd.idsolicitacao_declaracao';
        $this->limite = -1;
        $solicitacoes = $this->retornarLinhas();

        $this->sql = 'SELECT
                        d.nome,
                        dt.nome as tipo,
                        NULL AS idsolicitacao_declaracao,
                        NULL AS data_solicitacao,
                        NULL AS situacao,
                        md.idmatriculadeclaracao,
                        md.data_cad
                    FROM
                        matriculas_declaracoes md
                        INNER JOIN declaracoes d ON (md.iddeclaracao = d.iddeclaracao)
                        INNER JOIN declaracoes_tipos dt ON (d.idtipo = dt.idtipo)
                    WHERE
                        md.idmatricula = '.$idmatricula.' AND
                        md.ativo = "S" AND
                        md.aluno_visualiza = "S" AND
                        (SELECT
                            sd.idsolicitacao_declaracao
                        FROM
                            matriculas_solicitacoes_declaracoes sd
                        WHERE
                            sd.idmatriculadeclaracao = md.idmatriculadeclaracao) IS NULL';
        $this->ordem = 'ASC';
        $this->ordem_campo = 'md.idmatriculadeclaracao';
        $this->limite = -1;
        $declaracoes = $this->retornarLinhas();

        return array_merge($solicitacoes, $declaracoes);
    }

    public function retornarNomePais($idpais)
    {
        if ($idpais) {
            $this->sql = "SELECT nome FROM paises WHERE idpais = " . $idpais;
            $dados = $this->retornarLinha($this->sql);
            return $dados['nome'];
        } else {
            return false;
        }
    }

    public function retornarNomeCidade($idcidade)
    {
        $sql = "select nome FROM cidades where idcidade = '" . $idcidade . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
    }

    public function retornarNomeEstado($idestado)
    {
        $sql = "select nome FROM estados where idestado = '" . $idestado . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
    }

    public function retornarSiglaEstado($idestado)
    {
        $sql = "select sigla FROM estados where idestado = '" . $idestado . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['sigla'];
    }

    //Adicionada para deferir automaticamene a solicitação
    public function gerarDeclaracaoSolicitacao($idioma = null, $idsolicitacao_declaracao)
    {
        $this->sql = "SELECT msd.*, d.declaracao, d.nome FROM
                      matriculas_solicitacoes_declaracoes msd
                INNER JOIN declaracoes d ON (msd.iddeclaracao = d.iddeclaracao)
                WHERE msd.idsolicitacao_declaracao = " . (int)$idsolicitacao_declaracao;
        $solicitacao = $this->retornarLinha($this->sql);
        $this->id = $solicitacao['idmatricula'];
        $documento = $solicitacao["declaracao"];
        //Matrículas
        $this->sql = "SELECT m.* FROM matriculas m
                WHERE m.idmatricula = " . $solicitacao['idmatricula'];
        $matricula = $this->retornarLinha($this->sql);
        foreach ($matricula as $ind => $val) {
            /*$dados = utf8_decode($dados);*/
            $dados = htmlentities($val);
            if ($ind == "idmatricula") {
                $documento = str_ireplace("[[matricula][numero]]", $val, $documento);
            } elseif ($ind == "quantidade_parcelas") {
                $documento = str_ireplace("[[matricula][quantidade_parcelas]]", $val, $documento);
            } elseif ($ind == "numero_contrato") {
                $documento = str_ireplace("[[matricula][numero_contrato]]", $val, $documento);
            } elseif ($ind == "data_conclusao") {
                $documento = str_ireplace("[[matricula][data_conclusao]]", formataData($val, "br", 0), $documento);
            } elseif ($ind == "data_expedicao") {
                $documento = str_ireplace("[[matricula][data_expedicao]]", formataData($val, "br", 0), $documento);
            } elseif ($ind == "data_registro") {
                $documento = str_ireplace("[[matricula][data_registro]]", formataData($val, "br", 0), $documento);
            } elseif ($ind == "data_matricula") {
                $documento = str_ireplace("[[matricula][data_matricula]]", formataData($val, "br", 0), $documento);
            } elseif ($ind == "ultimo_acesso_ava") {
                $documento = str_ireplace("[[matricula][ultimo_acesso_ava]]", formataData($val, "br", 0), $documento);
            } elseif ($ind == "valor_contrato") {
                $documento = str_ireplace("[[matricula][valor_contrato]]", number_format($val, 2, ",", "."), $documento);
            } else {
                $documento = str_ireplace("[[matricula][" . $ind . "]]", $val, $documento);
            }
        }
        //Alunos
        $sql_aluno = "SELECT * FROM pessoas p
                    INNER JOIN matriculas m
                    ON (m.idpessoa = p.idpessoa)
                WHERE m.idmatricula = " . $solicitacao['idmatricula'];
        $aluno = $this->retornarLinha($sql_aluno);
        foreach ($aluno as $ind => $cli) {
            /*$dados = utf8_decode($dados);*/
            $dados = htmlentities($cli);
            if ($ind == 'data_nasc') {
                $documento = str_ireplace("[[aluno][data_nasc]]", formataData($cli, "br", 0), $documento);
            } elseif ($ind == 'idpais') {
                $documento = str_ireplace("[[aluno][nacionalidade]]", $this->retornarNomePais($cli), $documento);
            } elseif ($ind == 'documento') {
                if ($aluno['documento_tipo'] == 'cpf') {
                    $documento = str_ireplace("[[aluno][documento]]", formatar($cli, "cpf"), $documento);
                } else {
                    $documento = str_ireplace("[[aluno][documento]]", formatar($cli, "cnpj"), $documento);
                }
            } elseif ($ind == 'banco_cpf_titular') {
                $documento = str_ireplace("[[aluno][banco_cpf_titular]]", formatar($cli, "cpf"), $documento);
            } elseif ($ind == 'rg_data_emissao') {
                $documento = str_ireplace("[[aluno][rg_data_emissao]]", formataData($cli, "br", 0), $documento);
            } elseif ($ind == 'cep') {
                $documento = str_ireplace("[[aluno][cep]]", formatar($cli, "cep"), $documento);
            } elseif ($ind == 'renda_familiar') {
                $documento = str_ireplace("[[aluno][renda_familiar]]", number_format($cli, 2, ",", "."), $documento);
            } elseif ($ind == 'estado_civil') {
                $documento = str_ireplace("[[aluno][estado_civil]]", $GLOBALS['estadocivil'][$this->config["idioma_padrao"]][$aluno['estado_civil']], $documento);
            } elseif ($ind == 'idlogradouro') {
                $documento = str_ireplace("[[aluno][logradouro]]", $this->retornarNomeLogradouro($cli), $documento);
            } elseif ($ind == 'idcidade') {
                $documento = str_ireplace("[[aluno][cidade]]", $this->retornarNomeCidade($cli), $documento);
            } elseif ($ind == 'idestado') {
                $documento = str_ireplace("[[aluno][estado]]", $this->retornarNomeEstado($cli), $documento);
            } else {
                $documento = str_ireplace("[[aluno][" . $ind . "]]", $cli, $documento);
            }
        }
        //Cursos
        $sql_curso = "SELECT * FROM cursos WHERE idcurso = " . $matricula["idcurso"];
        $curso = $this->retornarLinha($sql_curso);
        foreach ($curso as $ind => $dados) {
            /*$dados = utf8_decode($dados);*/
            $dados = htmlentities($dados);
            $documento = str_ireplace("[[curso][" . $ind . "]]", $dados, $documento);
        }
        $sql = "SHOW COLUMNS FROM `cursos`";
        $cursos = $this->retornarLinha($sql);
        foreach ($cursos as $ind => $dados) {
            $documento = str_ireplace("[[curso][" . $ind . "]]", $dados, $documento);
        }
        //Escolas
        $sql = "SELECT * FROM escolas WHERE idescola = " . $matricula["idescola"];
        $escola = $this->retornarLinha($sql);
        foreach ($escola as $ind => $dados) {
            $dados = htmlentities($dados);
            if ($ind == 'idlogradouro') {
                $documento = str_ireplace("[[CFC][logradouro]]", $this->retornarNomeLogradouro($dados), $documento);
            } elseif ($ind == 'idcidade') {
                $documento = str_ireplace("[[CFC][cidade]]", $this->retornarNomeCidade($dados), $documento);
            } elseif ($ind == 'idestado') {
                $documento = str_ireplace("[[CFC][estado]]", $this->retornarNomeEstado($dados), $documento);
            } else {
                $documento = str_ireplace("[[CFC][" . $ind . "]]", $dados, $documento);
            }
        }
        $sql = "SHOW COLUMNS FROM `escolas`";
        $escolas = $this->retornarLinha($sql);
        foreach ($escolas as $ind => $dados) {
            $documento = str_ireplace("[[CFC][" . $ind . "]]", $dados, $documento);
        }

        //Sindicatos
        $sql = "SELECT * FROM sindicatos WHERE idsindicato = " . $escola["idsindicato"];
        $sindicato = $this->retornarLinha($sql);
        foreach ($sindicato as $ind => $dados) {
            //$dados = utf8_decode($dados);
            $dados = htmlentities($dados);
            if ($ind == 'documento') {
                $documento = str_ireplace("[[SINDICATO][cnpj]]", formatar($dados, "cnpj"), $documento);
            } elseif ($ind == 'idlogradouro') {
                $documento = str_ireplace("[[SINDICATO][logradouro]]", $this->retornarNomeLogradouro($dados), $documento);
            } elseif ($ind == 'idcidade') {
                $documento = str_ireplace("[[SINDICATO][cidade]]", $this->retornarNomeCidade($dados), $documento);
            } elseif ($ind == 'idestado') {
                $documento = str_ireplace("[[SINDICATO][estado]]", $this->retornarNomeEstado($dados), $documento);
            } else {
                $documento = str_ireplace("[[SINDICATO][" . $ind . "]]", $dados, $documento);
            }
        }
        $documento = str_ireplace("[[SINDICATO][secretario][portaria]]", $sindicato["secretario_portaria"], $documento);
        $documento = str_ireplace("[[SINDICATO][diretor][portaria]]", $sindicato["diretor_portaria"], $documento);
        $sql = "SHOW COLUMNS FROM `sindicatos`";
        $sindicatos = $this->retornarLinha($sql);
        foreach ($sindicatos as $ind => $dados) {
            $dados = htmlentities($dados);
            $documento = str_ireplace("[[SINDICATO][" . $ind . "]]", $dados, $documento);
        }

        //Ofertas
        $sql = "SELECT * FROM ofertas WHERE idoferta = " . $matricula["idoferta"];
        $oferta = $this->retornarLinha($sql);
        foreach ($oferta as $ind => $dados) {
            $dados = htmlentities($dados);
            if ($ind == "data_inicio_matricula") {
                $documento = str_ireplace("[[oferta][data_inicio_matricula]]", formataData($dados, "br", 0), $documento);
            } elseif ($ind == "data_fim_matricula") {
                $documento = str_ireplace("[[oferta][data_fim_matricula]]", formataData($dados, "br", 0), $documento);
            } elseif ($ind == "data_limite") {
                $documento = str_ireplace("[[oferta][data_limite]]", formataData($dados, "br", 0), $documento);
            } elseif ($ind == "data_inicio_acesso_ava") {
                $documento = str_ireplace("[[oferta][data_inicio_acesso_ava]]", formataData($dados, "br", 0), $documento);
            } elseif ($ind == "data_fim_acesso_ava") {
                $documento = str_ireplace("[[oferta][data_fim_acesso_ava]]", formataData($dados, "br", 0), $documento);
            } else {
                $documento = str_ireplace("[[oferta][" . $ind . "]]", $dados, $documento);
            }
        }
        $sql = "SHOW COLUMNS FROM `ofertas`";
        $ofertas = $this->retornarLinha($sql);
        foreach ($ofertas as $ind => $dados) {
            $documento = str_ireplace("[[oferta][" . $ind . "]]", $dados, $documento);
        }

        //Vendedores
        $sql = "SELECT * FROM vendedores
            WHERE idvendedor = " . $matricula["idvendedor"];
        $vendedor = $this->retornarLinha($sql);
        foreach ($vendedor as $ind => $dados) {
            $dados = htmlentities($dados);
            if ($ind == 'documento') {
                if ($aluno['documento_tipo'] == 'cpf') {
                    $documento = str_ireplace("[[vendedor][documento]]", formatar($dados, "cpf"), $documento);
                } else {
                    $documento = str_ireplace("[[vendedor][documento]]", formatar($dados, "cnpj"), $documento);
                }
            } elseif ($ind == 'rg_data_emissao') {
                $documento = str_ireplace("[[vendedor][rg_data_emissao]]", formataData($dados, "br", 0), $documento);
            } else {
                $documento = str_ireplace("[[vendedor][" . $ind . "]]", $dados, $documento);
            }
        }
        $sql = "SHOW COLUMNS FROM `vendedores`";
        $vendedores = $this->retornarLinha($sql);
        foreach ($vendedores as $ind => $dados) {
            $documento = str_ireplace("[[vendedor][" . $ind . "]]", $dados, $documento);
        }
        //Currículos
        $sql = "SELECT c.* FROM
                curriculos c
            INNER JOIN ofertas_cursos_escolas oce
            ON (c.idcurriculo = oce.idcurriculo)
          WHERE oce.idoferta = '" . $matricula["idoferta"] . "' AND
          oce.idcurso = '" . $matricula["idcurso"] . "' AND
          oce.idescola = '" . $matricula["idescola"] . "' AND
          oce.ativo = 'S'";
        $curriculos = $this->retornarLinha($sql);
        foreach ($curriculos as $ind => $dados) {
            /*$dados = utf8_decode($dados);*/
            $dados = htmlentities($dados);
            $documento = str_ireplace("[[curriculo][" . $ind . "]]", $dados, $documento);
        }
        $sql = "SHOW COLUMNS FROM `curriculos`";
        $curriculos = $this->retornarLinha($sql);
        foreach ($curriculos as $ind => $dados) {
            $documento = str_ireplace("[[curriculo][" . $ind . "]]", $dados, $documento);
        }
        $variavel = explode("[[I][", $documento);
        if ($variavel) {
            foreach ($variavel as $ind => $val) {
                $id = explode("]]", $val);
                $indice[] = $id[0];
            }
            unset($indice[array_search("", $indice)]);
            foreach ($indice as $ind => $val) {
                $this->sql = "SELECT iddeclaracao_imagem, servidor
                        FROM declaracoes_imagens
                        WHERE ativo = 'S' AND
                            iddeclaracao = " . $solicitacao['iddeclaracao'] . " AND
                            iddeclaracao_imagem = " . $val . "";
                $linha = $this->retornarLinha($this->sql);
                $documento = str_replace("[[I][" . $val . "]]", "<div style=\"text-align:left; width:800px; text-align:center\"><img src=\"http://" . $_SERVER["SERVER_NAME"] . "/storage/declaracoes_imagens/" . $linha["servidor"] . "\" border=\"0\" /></div>", $documento);
            }
        }
        $this->retorno = array();
        if ($documento) {
            $data_matricula = $this->retornarDataCadMatricula();
            $data_matricula = new DateTime($data_matricula);

            $this->sql = "INSERT INTO
                          matriculas_declaracoes
                      SET
                          data_cad = now(),
                          aluno_visualiza = 'S',
                          idmatricula = " . $solicitacao['idmatricula'] . ",
                          iddeclaracao = " . $solicitacao["iddeclaracao"] . ",
                          arquivo_pasta = '" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "'";
            if ($this->modulo == "gestor") {
                $this->sql .= ", idusuario = '" . $this->idusuario . "'";
            }
            $salvar = $this->executaSql($this->sql);
            $iddeclaracaoMatricula = mysql_insert_id();
            $codigoValidacao = md5($iddeclaracaoMatricula);
            $this->sql = "UPDATE matriculas_declaracoes
                      SET
                        cod_validacao = '" . $codigoValidacao . "'
                      WHERE idmatriculadeclaracao = " . $iddeclaracaoMatricula;
            $this->retornarLinha($this->sql);
            //Hach para imprimir na declaração
            $link_validacao = $GLOBALS['config']['urlSistema'] . '/validador</br>';
            $data_extenso = date("d") . " de " . $GLOBALS["meses_idioma"]["pt_br"][date("m")] . " de " . date("Y");
            $documento = str_ireplace("[[DECLARACAO][DATA_GERACAO]]", date("d/m/Y"), $documento);
            $documento = str_ireplace("[[DECLARACAO][QUEBRA_DE_PAGINA]]", "<div class='quebra_pagina'></div>", $documento);
            $documento = str_ireplace("[[DECLARACAO][DATA_GERACAO_EXTENSO]]", $data_extenso, $documento);
            $documento = str_ireplace("[[DECLARACAO][CAMPO_ADICIONAL_LOCAL]]", nl2br($this->post["campo_adicional_local"]), $documento);
            $documento = str_ireplace("[[DECLARACAO][DATA_PREVISTA_CONCLUSAO]]", $this->post["data_prevista_conclusao"], $documento);
            $documento = str_ireplace("[[DECLARACAO][CAMPO_ADICIONAL]]", nl2br($this->post["campo_adicional_1"]), $documento);
            $documento = str_ireplace("[[DECLARACAO][CAMPO_ADICIONAL2]]", nl2br($this->post["campo_adicional_2"]), $documento);
            $documento = str_ireplace("[[DECLARACAO][CAMPO_ADICIONAL3]]", nl2br($this->post["campo_adicional_3"]), $documento);
            $documento = str_ireplace("[[DECLARACAO][CAMPO_ADICIONAL4]]", nl2br($this->post["campo_adicional_4"]), $documento);
            $documento = str_ireplace("[[DECLARACAO][LINK_VALIDACAO]]", $link_validacao, $documento);
            $documento = str_ireplace("[[DECLARACAO][CODIGO_VALIDACAO]]", $codigoValidacao, $documento);
            if ($salvar) {
                $pastaDeclaracoes = $_SERVER["DOCUMENT_ROOT"] . "/storage/matriculas_declaracoes/" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "/" . $this->id;
                if (! is_dir($pastaDeclaracoes)) {
                    @mkdir($pastaDeclaracoes, 0777, true);
                }

                $id = fopen($pastaDeclaracoes . "/" . $iddeclaracaoMatricula . ".html", "w");
                fwrite($id, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                 <html xmlns="http://www.w3.org/1999/xhtml">
                 <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <title>' . $solicitacao["nome"] . '</title>
                    <style type="text/css">
                     .quebra_pagina {
                        page-break-after:always;
                    }
                </style>
            </head>
            <!-- Gerado pelo Alfama Oráculo -->
            <!-- www.alfamaoraculo.com.br -->
            <!-- Gerado dia: ' . date("d/m/Y H:i:s") . ' -->
            <body>');
                fwrite($id, $documento);
                //fwrite($id,"<p>Para validar esta declara&ccedil;&atilde;o acesse o link <a TARGET = '_blank' href=".$GLOBALS["config"]["urlSistema"]."/validador".">".$GLOBALS["config"]["urlSistema"]."/validador"."</a>.<br> Utilize este c&oacute;digo de valida&ccedil;&atilde;o: <strong>".$codigoValidadcao."</strong></p>");
                fwrite($id, "</body></html>");
                fclose($id);
                $this->AdicionarHistorico($this->idusuario, "declaracao", "cadastrou", NULL, NULL, $iddeclaracaoMatricula);
                $this->sql = "update
                                matriculas_solicitacoes_declaracoes
                            set
                                data_geracao = now(),
                                situacao = 'D',
                                idmatriculadeclaracao = " . $iddeclaracaoMatricula . "
                            where
                                idsolicitacao_declaracao =" . (int)$idsolicitacao_declaracao;
                $this->executaSql($this->sql);
                $this->retorno["sucesso"] = true;
                $this->retorno["mensagem"] = "solicitacao_deferida_sucesso";
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }
    public function RetornarDeclaracoesAlunoPodeSolicitar() {
        $matricula = $this->Retornar();

        $this->sql = 'SELECT
                        '.$this->campos.'
                    FROM
                        declaracoes d
                        LEFT JOIN declaracoes_sindicatos di ON (di.iddeclaracao = d.iddeclaracao AND di.ativo = "S")
                        LEFT JOIN declaracoes_cursos dc ON (dc.iddeclaracao = d.iddeclaracao AND dc.ativo = "S")
                    WHERE
                        (
                            (di.idsindicato IS NULL AND dc.idcurso IS NULL) OR
                            (di.idsindicato IS NULL AND dc.idcurso = '.$matricula['idcurso'].') OR
                            (di.idsindicato = '.$matricula['idsindicato'].' AND dc.idcurso IS NULL) OR
                            (di.idsindicato = '.$matricula['idsindicato'].' AND dc.idcurso = '.$matricula['idcurso'].')
                        ) AND
                        d.ativo = "S" AND
                        d.ativo_painel = "S" AND
                        d.aluno_solicita = "S"
                    GROUP BY d.iddeclaracao, d.nome';
        $this->ordem = 'ASC';
        $this->ordem_campo = 'd.nome';
        $this->limite = -1;
        $declaracoes = $this->retornarLinhas();

        return json_encode($declaracoes);
    }

    public function retornarDeclaracao($idmatricula_declaracao) {
        $this->sql = 'SELECT
                        md.*,
                        dt.nome AS tipo,
                        d.nome AS declaracao,
                        d.margem_left,
                        d.margem_right,
                        d.margem_top,
                        d.margem_bottom,
                        m.data_cad as data_matricula,
                        d.background_servidor
                    FROM
                        matriculas_declaracoes md
                        INNER JOIN matriculas m ON (m.idmatricula = md.idmatricula)
                        LEFT OUTER JOIN declaracoes d ON (md.iddeclaracao = d.iddeclaracao)
                        LEFT OUTER JOIN declaracoes_tipos dt ON (d.idtipo = dt.idtipo)
                    WHERE
                        md.idmatriculadeclaracao = '.$idmatricula_declaracao.' AND
                        md.ativo = "S"';
        return $this->retornarLinha($this->sql);
    }

    public function RetornarCurso() {
        $this->sql = "SELECT * FROM cursos WHERE idcurso = " . $this->matricula["idcurso"];
        return $this->retornarLinha($this->sql);
    }

    public function retornarPodeFazerProvaVirtual() {
        $podeFazer = false;

        $this->sql = 'SELECT
                        IF(m.porcentagem_manual > m.porcentagem, m.porcentagem_manual, m.porcentagem) AS porcentagem,
                        oc.porcentagem_minima_virtual
                    FROM
                        matriculas m
                        INNER JOIN ofertas_cursos oc ON (oc.idoferta = m.idoferta AND oc.idcurso = m.idcurso AND oc.ativo = "S")
                    WHERE
                        m.idmatricula = '.(int)$this->id;
        $matricula = $this->retornarLinha($this->sql);
        if ((float) $matricula['porcentagem'] >= (float) $matricula['porcentagem_minima_virtual']) {
            $podeFazer = true;
        }

        return $podeFazer;
    }

    public function retornarArquivosBiblioteca($idava) {
        $this->sql = 'SELECT * FROM avas_downloads_pastas WHERE idava = '.$idava.' AND ativo = "S"';

        $this->ordem = "ASC";
        $this->ordem_campo = "nome";
        $this->limite = -1;
        $pastas = $this->retornarLinhas();
        foreach ($pastas as $ind => $pasta) {
            $this->sql = 'SELECT * FROM avas_downloads WHERE idpasta = '.$pasta['idpasta'].' AND idava = '.$pasta['idava'].' AND ativo = "S"';
            $this->ordem = "ASC";
            $this->ordem_campo = "ordem ASC, data_cad ASC, iddownload";
            $this->limite = -1;
            $pastas[$ind]['arquivos'] = $this->retornarLinhas();
        }

        return $pastas;
    }

    public function retornarArquivoBiblioteca($idava, $iddownload) {
        $this->sql = 'SELECT * FROM avas_downloads WHERE iddownload = '.$iddownload.' AND idava = '.$idava;
        $arquivo = $this->retornarLinha($this->sql);

        return $arquivo;
    }

    public function retornarFavoritos($idava) {
        $this->sql = 'SELECT
                        mof.idfavorito,
                        arao.*
                    FROM
                        matriculas_objetos_favoritos mof
                        INNER JOIN avas_rotas_aprendizagem_objetos arao ON (mof.idobjeto = arao.idobjeto and arao.ativo = "S")
                    WHERE
                        mof.idmatricula = '.$this->id.' AND
                        mof.idava = '.$idava.' AND
                        mof.ativo = "S"';
        $this->ordem = "ASC";
        $this->ordem_campo = "mof.data_cad ASC, mof.idfavorito";
        $this->limite = -1;
        $favoritos = $this->retornarLinhas();

        $ordem = $this->retornarOrdemObjetoRotaDeAprendizagem($idava);

        foreach ($favoritos as $ind => $favorito) {
            $favoritos[$ind]['pagina'] = $ordem[$favorito['idobjeto']];
            $favoritos[$ind]["objeto"] = $this->retornarObjeto($favorito);
        }

        return $favoritos;
    }

    public function retornarOrdemObjetoRotaDeAprendizagem($idava) {
        $ordem = array();

        $this->sql = 'SELECT
                        arao.idobjeto
                      FROM
                        avas_rotas_aprendizagem ara
                        INNER JOIN avas_rotas_aprendizagem_objetos arao ON (ara.idrota_aprendizagem = arao.idrota_aprendizagem and arao.ativo = "S")
                      WHERE
                        ara.idava = '.intval($idava).' AND
                        arao.tipo <> "objeto_divisor" AND
                        ara.ativo = "S"';
        $this->ordem = "asc";
        $this->ordem_campo = "arao.ordem ASC, arao.data_cad ASC, arao.idobjeto";
        $this->limite = -1;
        $this->groupby = 'arao.idobjeto';
        $objetosRota = $this->retornarLinhas();
        foreach ($objetosRota as $ind => $objetoRota) {
            $ordem[$objetoRota['idobjeto']] = $ind + 1;
        }

        return $ordem;
    }

    public function removerFavorito($idava, $idfavorito) {
        if (verificaPermissaoAcesso(false)) {

            $this->sql = 'UPDATE matriculas_objetos_favoritos SET ativo = "N" WHERE idfavorito = '.$idfavorito.' AND idmatricula = '.$this->id.' AND idava = '.$idava;
            if ($this->executaSql($this->sql)) {
                $this->monitora_oque = 2;
                $retorno['id'] = $idfavorito;
                $this->cadastrarHistorioAluno($idava, 'removeu', 'favorito', $idfavorito);
                $this->monitora_onde = '144';
                $this->monitora_qual = $retorno['id'];
                $this->Monitora();
                $retorno['sucesso'] = true;
            } else {
                $retorno['erro'] = true;
                $retorno['erros'][] = $this->sql;
                $retorno['erros'][] = mysql_error();
            }

            return $retorno;
        } else {
            $retorno['erro_json'] = 'sem_permissao';
        }

        return $retorno;
    }

    public function retornarColegas($idava, $busca = null, $letra = null) {

        $sindicato = $this->retornarSindicato();
        $this->campos = 'DISTINCT(p.idpessoa), p.*';

        $this->sql = 'SELECT
                        '.$this->campos.'
                    FROM
                        pessoas p
                        INNER JOIN matriculas m ON (m.idpessoa = p.idpessoa AND m.ativo = "S")
                        INNER JOIN ofertas_cursos_escolas ocp ON (m.idoferta = ocp.idoferta AND m.idcurso = m.idcurso AND m.idescola = ocp.idescola AND ocp.ativo = "S")
                        INNER JOIN ofertas_curriculos_avas oca ON (m.idoferta = oca.idoferta AND ocp.idcurriculo = oca.idcurriculo AND oca.ativo = "S")
                        INNER JOIN matriculas_workflow_acoes mwa ON (m.idsituacao = mwa.idsituacao AND mwa.ativo = "S" AND mwa.idopcao = "27")
                    WHERE
                        oca.idava = '.$idava.' AND
                        p.idpessoa <> '.(int) $this->idpessoa.' AND
                        (
                            (
                                (date_format(DATE_ADD(m.data_cad, INTERVAL ocp.dias_para_ava DAY),"%Y-%m-%d") >= NOW() OR ocp.dias_para_ava IS NULL) AND
                                (ocp.data_inicio_ava <= NOW() OR ocp.data_inicio_ava IS NULL) AND
                                (ocp.data_limite_ava >= NOW() OR ocp.data_limite_ava IS NULL) AND
                                m.data_prolongada IS NULL
                            )
                            OR
                            (m.data_prolongada >= NOW() OR m.data_prolongada IS NOT NULL)
                        ) AND
                        p.ativo = "S" AND
                        m.idsindicato = '.$sindicato['idsindicato'];

        if ($busca) {
            $this->sql .= ' AND p.nome LIKE "%'.$busca.'%"';
        } elseif ($letra){
            $this->sql .= ' AND p.nome LIKE "'.$letra.'%"';
        }

        $this->groupby = "DISTINCT(p.idpessoa)";
        $this->ordem = "ASC";
        $this->ordem_campo = "p.nome";
        $this->limite = 40;

        return $this->retornarLinhas();

    }

    public function retornarPaginacaoColegas($idioma) {

        $this->retorno = "";

        $menos = $this->pagina - 1;
        $mais = $this->pagina + 1;
        $valorUtilizado = 5;
        $link = '?b='.$_GET['b'].'&?l='.$_GET['l'];
        if ($this->paginas > 1) {
            if ($menos > 0)
                $this->retorno .= '<li><a href="'.$link.'&p='.$menos.'">'.$idioma['anterior'].'</a></li>';

            if (($this->pagina - $valorUtilizado) < 1)
                $anterior = 1;
            else
                $anterior = $this->pagina - $valorUtilizado;

            if (($this->pagina + $valorUtilizado) > $this->paginas)
                $posterior = $this->paginas;
            else
                $posterior = $this->pagina + $valorUtilizado;

            for ($i = $anterior; $i <= $posterior; $i++)
                if ($i != $this->pagina)
                    $this->retorno .= '<li><a href="'.$link.'&p='.$i.'">'.$i.'</a></li>';
                else
                    $this->retorno .= '<li class="active"><a href="'.$link.'&p='.$i.'">'.$i.'</a></li>';

            if ($mais <= $this->paginas)
                $this->retorno .= '<li class="next"><a href="'.$link.'&p='.$mais.'">'.$idioma['proxima'].'</a></li>';
        }

        return $this->retorno;

    }

    public function retornarProfessores($idava)
    {
        $idava = intval($idava);
        $this->sql = 'SELECT
                        p.*
                    FROM
                        professores p
                        INNER JOIN professores_avas pa on (p.idprofessor = pa.idprofessor AND pa.ativo = "S")
                    WHERE
                        pa.idava = ' . $idava . ' AND
                        p.ativo = "S" AND
                        p.ativo_painel_aluno = "S"
                    GROUP BY p.idprofessor';

        $this->ordem = "ASC";
        $this->ordem_campo = "p.nome";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function retornarDisciplinasProfessor($idprofessor) {
        $this->sql = 'SELECT
                        d.nome
                    FROM
                        disciplinas d
                        INNER JOIN professores_disciplinas pd on (d.iddisciplina = pd.iddisciplina AND pd.ativo = "S")
                    WHERE
                        pd.idprofessor = '.$idprofessor;

        $this->ordem = "ASC";
        $this->ordem_campo = "d.nome";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function contabilizarSimulado($idmatricula, $idava, $idsimulado) {
        if (verificaPermissaoAcesso(true)) {
            $this->executaSql("BEGIN");

            //Busca se já foi contabilizado a porcentagem de download da biblioteca
            $this->sql = 'SELECT
                                count(*) as total
                              FROM
                                matriculas_rotas_aprendizagem_objetos
                              WHERE
                                idmatricula = '.$idmatricula.' AND
                                idava = '.$idava.' AND
                                idsimulado IS NOT NULL';
            $simuladoContabilizado = $this->retornarLinha($this->sql);

            $sql = 'SELECT COUNT(*) AS total FROM matriculas_rotas_aprendizagem_objetos WHERE idmatricula = '.$idmatricula.' AND idava = '.$idava.' AND idsimulado = '.$idsimulado;
            $verifica = $this->retornarLinha($sql);
            if ($verifica['total'] <= 0) {
                $sql = 'SELECT porcentagem_simulado AS porcentagem FROM avas WHERE idava = '.$idava;
                $porcentagem = $this->retornarLinha($sql);
                if (!$porcentagem['porcentagem'])
                    $porcentagem['porcentagem'] = 0;

                $sql = 'INSERT INTO
                            matriculas_rotas_aprendizagem_objetos
                        SET
                            data_cad = NOW(),
                            idmatricula = '.$idmatricula.',
                            idava = '.$idava.',
                            idsimulado = '.$idsimulado.',
                            porcentagem = '.$porcentagem['porcentagem'];
                if($this->executaSql($sql)) {
                    if ($simuladoContabilizado['total'] == 0) {
                        $sql = 'SELECT
                                        idmatricula_ava_porcentagem,
                                        porcentagem,
                                        COUNT(*) AS total
                                    FROM
                                        matriculas_avas_porcentagem
                                    WHERE
                                        idmatricula = '.$idmatricula.' AND
                                        idava = '.$idava;
                        $verificaPorcentagem = $this->retornarLinha($sql);
                        if (!$verificaPorcentagem['total']) {
                            $sql = 'INSERT INTO matriculas_avas_porcentagem SET idmatricula = '.$idmatricula.', idava = '.$idava.', porcentagem = '.$porcentagem['porcentagem'];
                        } else {
                            $sql = 'UPDATE
                                            matriculas_avas_porcentagem
                                        SET
                                            porcentagem = IF((porcentagem + '.$porcentagem['porcentagem'].') > 100, 100, (porcentagem + '.$porcentagem['porcentagem'].'))
                                        WHERE
                                            idmatricula_ava_porcentagem = '.$verificaPorcentagem['idmatricula_ava_porcentagem'];
                        }

                        if ($this->executaSql($sql)) {
                            $avas = $this->retornarAvas();
                            $qtdAvas = count($avas);
                            foreach ($avas as $ava) {
                                $porcentagemTotal += floatval($ava['porcentagem']);
                            }
                            $porcentagemTotal = $porcentagemTotal / $qtdAvas;
                            $sql = 'UPDATE matriculas SET porcentagem='.$porcentagemTotal.' WHERE idmatricula = ' . $this->id;
                            if ($this->executaSql($sql)) {
                                $this->executaSql("COMMIT");
                                $retorno['porcentagem'] = $porcentagemTotal;
                                $retorno['porcentagem_formatada'] = number_format($porcentagemTotal, 2, ",", ".");
                                $retorno['sucesso'] = true;
                            } else {
                                $this->executaSql("ROLLBACK");
                                $retorno['erro'] = true;
                                $retorno['erros'][] = $sql;
                                $retorno['erros'][] = mysql_error();
                            }
                        } else {
                            $this->executaSql("ROLLBACK");
                            $retorno['erro'] = true;
                            $retorno['erros'][] = $sql;
                            $retorno['erros'][] = mysql_error();
                        }
                    } else {
                        $this->executaSql("COMMIT");

                        $retorno['sucesso'] = true;
                    }
                } else {
                    $this->executaSql("ROLLBACK");
                    $retorno['erro'] = true;
                    $retorno['erros'][] = $sql;
                    $retorno['erros'][] = mysql_error();
                }
            }

            return $retorno;
        }
    }

    function contabilizarArquivo($idmatricula, $idava, $iddownload) {
        if (verificaPermissaoAcesso(false)) {
            $this->executaSql("BEGIN");

            //Busca se já foi contabilizado a porcentagem de download da biblioteca
            $this->sql = 'SELECT
                                count(*) as total
                              FROM
                                matriculas_rotas_aprendizagem_objetos
                              WHERE
                                idmatricula = '.$idmatricula.' AND
                                idava = '.$idava.' AND
                                iddownload IS NOT NULL';
            $downloadContabilizado = $this->retornarLinha($this->sql);

            $sql = 'SELECT COUNT(*) AS total FROM matriculas_rotas_aprendizagem_objetos WHERE idmatricula = '.$idmatricula.' AND idava = '.$idava.' AND iddownload = '.$iddownload;
            $verifica = $this->retornarLinha($sql);
            if ($verifica['total'] <= 0) {
                $sql = 'SELECT porcentagem_biblioteca AS porcentagem FROM avas WHERE idava = '.$idava;
                $porcentagem = $this->retornarLinha($sql);
                if (!$porcentagem['porcentagem'])
                    $porcentagem['porcentagem'] = 0;

                $sql = 'INSERT INTO
                            matriculas_rotas_aprendizagem_objetos
                        SET
                            data_cad = NOW(),
                            idmatricula = '.$idmatricula.',
                            idava = '.$idava.',
                            iddownload = '.$iddownload.',
                            porcentagem = '.$porcentagem['porcentagem'];
                if($this->executaSql($sql)) {
                    if ($downloadContabilizado['total'] == 0) {
                        $sql = 'SELECT
                                        idmatricula_ava_porcentagem,
                                        porcentagem,
                                        COUNT(*) AS total
                                    FROM
                                        matriculas_avas_porcentagem
                                    WHERE
                                        idmatricula = '.$idmatricula.' AND
                                        idava = '.$idava;
                        $verificaPorcentagem = $this->retornarLinha($sql);
                        if (!$verificaPorcentagem['total']) {
                            $sql = 'INSERT INTO matriculas_avas_porcentagem SET idmatricula = '.$idmatricula.', idava = '.$idava.', porcentagem = '.$porcentagem['porcentagem'];
                        } else {
                            $sql = 'UPDATE
                                            matriculas_avas_porcentagem
                                        SET
                                            porcentagem = IF((porcentagem + '.$porcentagem['porcentagem'].') > 100, 100, (porcentagem + '.$porcentagem['porcentagem'].'))
                                        WHERE
                                            idmatricula_ava_porcentagem = '.$verificaPorcentagem['idmatricula_ava_porcentagem'];
                        }

                        if ($this->executaSql($sql)) {
                            $avas = $this->retornarAvas();
                            $qtdAvas = count($avas);
                            foreach ($avas as $ava) {
                                $porcentagemTotal += floatval($ava['porcentagem']);
                            }
                            $porcentagemTotal = $porcentagemTotal / $qtdAvas;
                            $sql = 'UPDATE matriculas SET porcentagem='.$porcentagemTotal.' WHERE idmatricula = ' . $this->id;
                            if ($this->executaSql($sql)) {
                                $this->executaSql("COMMIT");
                                $retorno['porcentagem'] = $porcentagemTotal;
                                $retorno['porcentagem_formatada'] = number_format($porcentagemTotal, 2, ",", ".");
                                $retorno['sucesso'] = true;
                            } else {
                                $this->executaSql("ROLLBACK");
                                $retorno['erro'] = true;
                                $retorno['erros'][] = $sql;
                                $retorno['erros'][] = mysql_error();
                            }
                        } else {
                            $this->executaSql("ROLLBACK");
                            $retorno['erro'] = true;
                            $retorno['erros'][] = $sql;
                            $retorno['erros'][] = mysql_error();
                        }
                    } else {
                        $this->executaSql("COMMIT");

                        $retorno['sucesso'] = true;
                    }
                } else {
                    $this->executaSql("ROLLBACK");
                    $retorno['erro'] = true;
                    $retorno['erros'][] = $sql;
                    $retorno['erros'][] = mysql_error();
                }
            }

            return $retorno;
        }
    }

    function contabilizarChat($idmatricula, $idava, $idchat) {
        if (verificaPermissaoAcesso(true)) {
            $this->executaSql("BEGIN");

            //Busca se já foi contabilizado a porcentagem de download da biblioteca
            $this->sql = 'SELECT
                                count(*) as total
                              FROM
                                matriculas_rotas_aprendizagem_objetos
                              WHERE
                                idmatricula = '.$idmatricula.' AND
                                idava = '.$idava.' AND
                                idchat IS NOT NULL';
            $chatContabilizado = $this->retornarLinha($this->sql);

            $sql = 'SELECT COUNT(*) AS total FROM matriculas_rotas_aprendizagem_objetos WHERE idmatricula = '.$idmatricula.' AND idava = '.$idava.' AND idchat = '.$idchat;
            $verifica = $this->retornarLinha($sql);
            if ($verifica['total'] <= 0) {
                $sql = 'SELECT porcentagem_chat AS porcentagem FROM avas WHERE idava = '.$idava;
                $porcentagem = $this->retornarLinha($sql);
                if (!$porcentagem['porcentagem'])
                    $porcentagem['porcentagem'] = 0;

                $sql = 'INSERT INTO
                            matriculas_rotas_aprendizagem_objetos
                        SET
                            data_cad = NOW(),
                            idmatricula = '.$idmatricula.',
                            idava = '.$idava.',
                            idchat = '.$idchat.',
                            porcentagem = '.$porcentagem['porcentagem'];
                if($this->executaSql($sql)) {
                    if ($chatContabilizado['total'] == 0) {
                        $sql = 'SELECT
                                        idmatricula_ava_porcentagem,
                                        porcentagem,
                                        COUNT(*) AS total
                                    FROM
                                        matriculas_avas_porcentagem
                                    WHERE
                                        idmatricula = '.$idmatricula.' AND
                                        idava = '.$idava;
                        $verificaPorcentagem = $this->retornarLinha($sql);
                        if (!$verificaPorcentagem['total']) {
                            $sql = 'INSERT INTO matriculas_avas_porcentagem SET idmatricula = '.$idmatricula.', idava = '.$idava.', porcentagem = '.$porcentagem['porcentagem'];
                        } else {
                            $sql = 'UPDATE
                                        matriculas_avas_porcentagem
                                    SET
                                        porcentagem = IF((porcentagem + '.$porcentagem['porcentagem'].') > 100, 100, (porcentagem + '.$porcentagem['porcentagem'].'))
                                    WHERE
                                        idmatricula_ava_porcentagem = '.$verificaPorcentagem['idmatricula_ava_porcentagem'];
                        }

                        if ($this->executaSql($sql)) {
                            $avas = $this->retornarAvas();
                            $qtdAvas = count($avas);
                            foreach ($avas as $ava) {
                                $porcentagemTotal += floatval($ava['porcentagem']);
                            }
                            $porcentagemTotal = $porcentagemTotal / $qtdAvas;
                            $sql = 'UPDATE matriculas SET porcentagem='.$porcentagemTotal.' WHERE idmatricula = ' . $this->id;
                            if ($this->executaSql($sql)) {
                                $this->executaSql("COMMIT");
                                $retorno['porcentagem'] = $porcentagemTotal;
                                $retorno['porcentagem_formatada'] = number_format($porcentagemTotal, 2, ",", ".");
                                $retorno['sucesso'] = true;
                            } else {
                                $this->executaSql("ROLLBACK");
                                $retorno['erro'] = true;
                                $retorno['erros'][] = $sql;
                                $retorno['erros'][] = mysql_error();
                            }
                        } else {
                            $this->executaSql("ROLLBACK");
                            $retorno['erro'] = true;
                            $retorno['erros'][] = $sql;
                            $retorno['erros'][] = mysql_error();
                        }
                    } else {
                        $this->executaSql("COMMIT");

                        $retorno['sucesso'] = true;
                    }
                } else {
                    $this->executaSql("ROLLBACK");
                    $retorno['erro'] = true;
                    $retorno['erros'][] = $sql;
                    $retorno['erros'][] = mysql_error();
                }
            }

            return $retorno;
        }
    }

    function contabilizarForum($idmatricula, $idava, $idforum) {
        if (verificaPermissaoAcesso(true)) {
            $this->executaSql("BEGIN");

            //Busca se já foi contabilizado a porcentagem de download da biblioteca
            $this->sql = 'SELECT
                                count(*) as total
                              FROM
                                matriculas_rotas_aprendizagem_objetos
                              WHERE
                                idmatricula = '.$idmatricula.' AND
                                idava = '.$idava.' AND
                                idforum IS NOT NULL';
            $forumContabilizado = $this->retornarLinha($this->sql);

            $sql = 'SELECT COUNT(*) AS total FROM matriculas_rotas_aprendizagem_objetos WHERE idmatricula = '.$idmatricula.' AND idava = '.$idava.' AND idforum = '.$idforum;
            $verifica = $this->retornarLinha($sql);
            if ($verifica['total'] <= 0) {
                $sql = 'SELECT porcentagem_forum AS porcentagem FROM avas WHERE idava = '.$idava;
                $porcentagem = $this->retornarLinha($sql);
                if (!$porcentagem['porcentagem'])
                    $porcentagem['porcentagem'] = 0;

                $sql = 'INSERT INTO
                            matriculas_rotas_aprendizagem_objetos
                        SET
                            data_cad = NOW(),
                            idmatricula = '.$idmatricula.',
                            idava = '.$idava.',
                            idforum = '.$idforum.',
                            porcentagem = '.$porcentagem['porcentagem'];
                if($this->executaSql($sql)) {
                    if ($forumContabilizado['total'] == 0) {
                        $sql = 'SELECT
                                        idmatricula_ava_porcentagem,
                                        porcentagem,
                                        COUNT(*) AS total
                                    FROM
                                        matriculas_avas_porcentagem
                                    WHERE
                                        idmatricula = '.$idmatricula.' AND
                                        idava = '.$idava;
                        $verificaPorcentagem = $this->retornarLinha($sql);
                        if (!$verificaPorcentagem['total']) {
                            $sql = 'INSERT INTO matriculas_avas_porcentagem SET idmatricula = '.$idmatricula.', idava = '.$idava.', porcentagem = '.$porcentagem['porcentagem'];
                        } else {
                            $sql = 'UPDATE
                                            matriculas_avas_porcentagem
                                        SET
                                            porcentagem = IF((porcentagem + '.$porcentagem['porcentagem'].') > 100, 100, (porcentagem + '.$porcentagem['porcentagem'].'))
                                        WHERE
                                            idmatricula_ava_porcentagem = '.$verificaPorcentagem['idmatricula_ava_porcentagem'];
                        }

                        if ($this->executaSql($sql)) {
                            $avas = $this->retornarAvas();
                            $qtdAvas = count($avas);
                            $porcentagemTotal = 0;
                            foreach ($avas as $ava) {
                                $porcentagemTotal += floatval($ava['porcentagem']);
                            }
                            $porcentagemTotal = $porcentagemTotal / $qtdAvas;
                            $sql = 'UPDATE matriculas SET porcentagem='.$porcentagemTotal.' WHERE idmatricula = ' . $this->id;
                            if ($this->executaSql($sql)) {
                                $this->executaSql("COMMIT");
                                $retorno['porcentagem'] = $porcentagemTotal;
                                $retorno['porcentagem_formatada'] = number_format($porcentagemTotal, 2, ",", ".");
                                $retorno['sucesso'] = true;
                            } else {
                                $this->executaSql("ROLLBACK");
                                $retorno['erro'] = true;
                                $retorno['erros'][] = $sql;
                                $retorno['erros'][] = mysql_error();
                            }
                        } else {
                            $this->executaSql("ROLLBACK");
                            $retorno['erro'] = true;
                            $retorno['erros'][] = $sql;
                            $retorno['erros'][] = mysql_error();
                        }
                    } else {
                        $this->executaSql("COMMIT");

                        $retorno['sucesso'] = true;
                    }
                } else {
                    $this->executaSql("ROLLBACK");
                    $retorno['erro'] = true;
                    $retorno['erros'][] = $sql;
                    $retorno['erros'][] = mysql_error();
                }
            }

            return $retorno;
        }
    }

    public function podeAgendarProva()
    {
        $data_atual = new DateTime();
        $acessoAva = $this->retornarAcessoAva();
        $ultima_data_prova = new DateTime($acessoAva['data_limite_acesso_ava']);
        $dias = (int)$acessoAva['dias_para_prova'];
        $ultima_data_prova->modify("+{$dias} days");
        if ($data_atual <= $ultima_data_prova) {
            $retorno['pode_agendar'] = true;
        } else {
            $retorno['pode_agendar'] = false;
        }
        $retorno['ultima_data_prova'] = $ultima_data_prova;
        return $retorno;
    }

    public function RetornarDisciplinasCurso() {
        $this->sql = "SELECT
                        d.*,
                        cb.nome as bloco,
                        cbd.idbloco_disciplina,
                        cbd.idformula,
                        cbd.ignorar_historico,
                        cbd.contabilizar_media,
                        cbd.exibir_aptidao,
                        ocp.dias_para_prova,
                        oca.idava,
                        c.media as media_curriculo
                    FROM
                        disciplinas d
                        INNER JOIN curriculos_blocos_disciplinas cbd on (d.iddisciplina = cbd.iddisciplina and cbd.ativo = 'S')
                        INNER JOIN curriculos_blocos cb on (cbd.idbloco = cb.idbloco and cb.ativo = 'S')
                        INNER JOIN ofertas_cursos_escolas ocp on (cb.idcurriculo = ocp.idcurriculo)
                        INNER JOIN curriculos c on (ocp.idcurriculo = c.idcurriculo)
                        INNER JOIN matriculas m on (ocp.idescola = m.idescola and ocp.idoferta = m.idoferta and ocp.idcurso = m.idcurso)
                        LEFT OUTER JOIN ofertas_curriculos_avas oca on (oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = m.idoferta and oca.ativo = 'S')
                    WHERE
                        m.idmatricula = " . $this->id;

        if($this->disciplinasLiberadas){
            $this->sql .= " and d.avaliacao_presencial = 'S' ";
        }

        $this->sql .= " GROUP BY d.iddisciplina ";
        $this->ordem = "ASC";
        $this->ordem_campo = "cb.ordem, cbd.ordem, d.nome";
        $this->limite = -1;
        $linhasretorn = $this->retornarLinhas();

        if($this->disciplinasLiberadas){
            foreach($linhasretorn as $ind => $val) {

                $disciplina_situacao = $this->retornarSituacaoDisciplina($this->id, $val, $val['media_curriculo']);
                if ($disciplina_situacao['situacao'] == 'Aprovado' or $disciplina_situacao['situacao'] == 'Apto') {
                    unset($linhasretorn[$ind]);
                }

            }
        }

        return $linhasretorn;
    }

    public function retornarSituacaoDisciplina($idmatricula, $disciplina, $media)
    {
        $boletim = new Boletim(new Avaliacoes);
        $boletim['idmatricula'] = (int)$idmatricula;
        $boletim->buscarDadosDaMatriculaHistorico();
        $formula = new Formulas_Notas;

        $aproveitamento_estudo = boletim::getAproveitamentoEstudos($idmatricula, $disciplina['iddisciplina']);

        if (!$aproveitamento_estudo['idmatricula_nota']) {

            $notas_disciplina = boletim::getProvasTipos($idmatricula, $disciplina['iddisciplina']);
            if ($notas_disciplina) {
                foreach ($notas_disciplina as $nota) {
                    $notas[$nota['idtipo']] = number_format($nota['nota'], 2, ',', '.');
                }
            }
            $formResult = $formula->set('id', $disciplina['idformula'])->set('post', $notas)->validarFormula($media);

        }

        if ($aproveitamento_estudo['aproveitamento_estudo'] == 'S') {
            $formResult['situacao'] = 'AE - Aproveitamento de Estudos';
            $formResult['lancar_nota'] = false;
        } elseif ($disciplina['ignorar_historico'] == 'S') {
            $formResult['situacao'] = 'Ignorada no histórico';
            $formResult['lancar_nota'] = true;
        } elseif ($disciplina['contabilizar_media'] == 'N') {
            $formResult['situacao'] = 'Não contabilizada no histórico';
            $formResult['lancar_nota'] = true;
        } else {
            if ($formResult['valor'] >= $media) {
                if ($disciplina['exibir_aptidao'] == 'N')
                    $formResult['situacao'] = 'Aprovado';
                else {
                    if ($formResult['valor'] == '10.00' || $formResult['valor'] == '10')
                        $formResult['situacao'] = 'Apto';
                    else {
                        $formResult['situacao'] = 'Inapto';
                        $formResult['lancar_nota'] = true;
                    }
                }
            } else {
                if ($disciplina['exibir_aptidao'] == 'N') {
                    $formResult['situacao'] = 'Reprovado';
                    $formResult['lancar_nota'] = true;
                } else {
                    $formResult['situacao'] = 'Inapto';
                    $formResult['lancar_nota'] = true;
                }
            }
            // Se nao tiver nota
            if (!count($notas_disciplina)) {
                $formResult['situacao'] = 'Sem nota';
                $formResult['lancar_nota'] = true;
            }
        }
        return $formResult;
    }

    public function alterarSituacao($de, $para)
    {
        $this->executaSql('BEGIN');
        $this->retorno = array();

        $this->sql = "SELECT * FROM matriculas where idmatricula = " . intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $situacaoCancelada = $this->retornarSituacaoCancelada();
        $situacaoAtiva = $this->retornarSituacaoAtiva();
        $situacaoInativa = $this->retornarSituacaoInativa();

        $verificaPreRequesito = $this->VerificaPreRequesito($de, $para);

        if ($verificaPreRequesito["verifica"]) {
            $this->sql = 'UPDATE matriculas SET idsituacao = ' . $para;
            if ($situacaoCancelada["idsituacao"] == $para) {
                if (!$this->post["idmotivo"]) {
                    $this->retorno["sucesso"] = false;
                    $this->retorno["mensagem"] = "mensagem_erro_motivo_cancelamento";
                    return $this->retorno;
                }
                $this->sql .= ", idmotivo_cancelamento = " . $this->post["idmotivo"];

                $this->sql2 = "SELECT anular_parcelas FROM motivos_cancelamento where idmotivo = " . intval($this->post["idmotivo"]);
                $retornomotivo = $this->retornarLinha($this->sql2);
                if ($retornomotivo['anular_parcelas'] == 'S')
                    $desejaCancelar = true;
            } elseif ($situacaoInativa["idsituacao"] == $para) {
                if (!$this->post["idmotivo"]) {
                    $this->retorno["sucesso"] = false;
                    $this->retorno["mensagem"] = "mensagem_erro_motivo_inativo";
                    return $this->retorno;
                }
                $this->sql .= ", idmotivo_inativo = " . $this->post["idmotivo"];
            } elseif ($situacaoAtiva["idsituacao"] == $para) {
                $sql = "SELECT idpessoa, email FROM pessoas where idpessoa = '" . $linhaAntiga['idpessoa'] . "' ";
                $pessoa = $this->retornarLinha($sql);

                $sql_visitas = "update visitas_vendedores set situacao = 'MAT', idmatricula = " . intval($this->id) . " where (idpessoa = '" . $linhaAntiga['idpessoa'] . "' or email = '" . $pessoa['email'] . "') and ativo = 'S' ";
                if (!$this->executaSql($sql_visitas)) {
                    $this->retorno["sucesso"] = false;
                    $this->retorno["mensagem"] = "mensagem_erro_atualizacao_visitas";
                    return $this->retorno;
                }
            }

            $this->sql .= ' WHERE idmatricula = ' . $this->id;
            $this->executaSql($this->sql);

            $this->sql = "SELECT * FROM matriculas where idmatricula = " . intval($this->id);
            $linhaNova = $this->retornarLinha($this->sql);

            $this->adicionarHistorico($this->idusuario, "situacao", "modificou", $linhaAntiga["idsituacao"], $linhaNova["idsituacao"], NULL);
            $this->processaAcoes($de, $para);
            if ($desejaCancelar) {
                $objetoConta = new Contas();
                $objetoConta->Set("idusuario", $this->idusuario);
                $objetoConta->Set("modulo", $this->modulo);
                $contasAlteradas = $objetoConta->cancelarContasMatricula($this->id);
                foreach ($contasAlteradas['contas'] as $conta) {
                    $this->adicionarHistorico($this->idusuario, "parcela_situacao", "modificou", $contasAlteradas["situacaoDe"], $contasAlteradas["situacaoPara"], $conta);
                }
            }

            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "mensagem_situacao_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = $verificaPreRequesito["mensagem"];
        }
        $this->executaSql("commit");
        return $this->retorno;
    }

    public function VerificaPreRequesito($de, $para)
    {
        $retorno["verifica"] = true;
        $acoes = array();
        $this->sql = "SELECT idrelacionamento FROM matriculas_workflow_relacionamentos where idsituacao_de = " . $de . " and idsituacao_para = " . $para . " and ativo = 'S'";
        $relacionamento = $this->retornarLinha($this->sql);
        $this->sql = "SELECT
                    mwa.idopcao
                  FROM
                    matriculas_workflow_acoes mwa
                  where
                    mwa.idrelacionamento = " . $relacionamento["idrelacionamento"] . " and
                    mwa.ativo = 'S'";
        $this->limite = -1;
        $this->ordem_campo = "mwa.idopcao";
        $this->ordem = "asc";
        if ($relacionamento)
            $acoes = $this->retornarLinhas();

        foreach ($acoes as $acao) {
            foreach ($GLOBALS["workflow_parametros_matriculas"] as $opcao) {
                if ($opcao["idopcao"] == $acao["idopcao"] && $opcao["tipo"] == "prerequisito") {
                    $preRequisitos[] = $acao;
                }
            }
        }
        if (count($preRequisitos) > 0) {
            $this->sql = "SELECT * FROM matriculas where idmatricula = " . intval($this->id);
            $matricula = $this->retornarLinha($this->sql);
            foreach ($preRequisitos as $ind => $preRequisito) {
                switch ($preRequisito["idopcao"]) {
                    //Ter o valor das mensalidades igual ao valor do contrato
                    case 6:
                        $eventoFinanceiroMensalidade = $this->retornarEventoMensalidade();
                        $this->sql = "SELECT ifnull(sum(valor),0) as total FROM contas where idmatricula = " . $matricula["idmatricula"] . " and ativo = 'S' and idevento = " . $eventoFinanceiroMensalidade["idevento"];
                        $totalMensalidades = $this->retornarLinha($this->sql);
                        if ($matricula["valor_contrato"] != $totalMensalidades["total"]) {
                            $retorno["verifica"] = false;
                            $retorno["mensagem"] = "ter_valor_mensalidade_diferente_contrato";
                        }
                        break;
                    //Ter documentos obrigatórios anexados
                    case 10:
                        $this->sql = "SELECT
                            idsindicato
                          FROM
                            escolas
                          where
                            idescola = " . $matricula["idescola"];
                        $matriculaSindicatoCurso = $this->retornarLinha($this->sql);
                        $this->sql = "SELECT
                            td.idtipo
                          FROM
                            tipos_documentos td
                          where
                            td.ativo = 'S' and ativo_painel = 'S' and
                            (
                                td.idtipo in(SELECT idtipo FROM tipos_documentos_sindicatos where idtipo = td.idtipo and idsindicato = " . $matriculaSindicatoCurso["idsindicato"] . " and ativo = 'S') OR
                                td.todas_sindicatos_obrigatorio = 'S'
                            ) AND
                            (
                                td.idtipo in(SELECT idtipo FROM tipos_documentos_cursos where idtipo = td.idtipo and idcurso = " . $matricula["idcurso"] . " and ativo = 'S') OR
                                td.todos_cursos_obrigatorio = 'S'
                            )
                          group by
                            td.idtipo";
                        $this->limite = -1;
                        $this->ordem_campo = false;
                        $this->ordem = false;
                        $tipos = $this->retornarLinhas();
                        foreach ($tipos as $tipo) {
                            $this->sql = "SELECT count(*) as total FROM matriculas_documentos where idmatricula = " . $matricula["idmatricula"] . " and idtipo = " . $tipo["idtipo"] . " and ativo = 'S' and situacao = 'aprovado' and idtipo_associacao is null";
                            $totalDocumento = $this->retornarLinha($this->sql);
                            if ($totalDocumento["total"] <= 0) {
                                $retorno["verifica"] = false;
                                $retorno["mensagem"] = "ter_documento_obrigatorios";
                            }
                        }
                        break;
                    //Ter contrato gerado
                    case 15:
                        $this->sql = "SELECT count(*) as total FROM matriculas_contratos where idmatricula = " . $matricula["idmatricula"] . " and cancelado is null and idusuario_cancelou is null";
                        $totalContratos = $this->retornarLinha($this->sql);
                        if ($totalContratos["total"] <= 0) {
                            $retorno["verifica"] = false;
                            $retorno["mensagem"] = "ter_contrato_gerado";
                        }
                        break;
                    //Ter contrato assinado
                    case 16:
                        $this->sql = "SELECT count(*) as total FROM matriculas_contratos where idmatricula = " . $matricula["idmatricula"] . " and assinado is not null and idusuario_assinou is not null and cancelado is null and idusuario_cancelou is null";
                        $totalContratos = $this->retornarLinha($this->sql);
                        if ($totalContratos["total"] <= 0) {
                            $retorno["verifica"] = false;
                            $retorno["mensagem"] = "ter_contrato_assinado";
                        }
                        break;
                    //Ter matrícula aprovada
                    case 18:
                        if ($matricula["aprovado_comercial"] != "S") {
                            $retorno["verifica"] = false;
                            $retorno["mensagem"] = "ter_matricula_aprovada";
                        }
                        break;
                }
            }
        }
        return $retorno;
    }

    public function retornarEventoMensalidade() {
        $this->sql = "SELECT * FROM  eventos_financeiros where ativo = 'S' and mensalidade = 'S' order by idevento desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    public function RetornarContas() {

        $eventoFinanceiroMensalidade = $this->retornarEventoMensalidade();
        $contasArray = array();
        $this->sql = "SELECT
                            c.*,
                            ef.nome as evento,
                            bc.nome as bandeira_cartao,
                            b.nome as banco,
                            cw.nome as situacao,
                            cw.cancelada as situacao_cancelada,
                            cw.renegociada as situacao_renegociada,
                            cw.transferida as situacao_transferida,
                            cw.pago as situacao_paga,
                            cor_nome,
                            cor_bg,
                            c_transferida.idmatricula as matricula_transferida,
                            pcm.valor as valor_matricula,
                            (SELECT count(1) FROM contas c_interno where c_interno.idpagamento_compartilhado = c.idpagamento_compartilhado and c_interno.ativo = 'S') as total_contas_compartilhadas
                          FROM
                            contas c
                            INNER JOIN contas_workflow cw on (c.idsituacao = cw.idsituacao)
                            INNER JOIN eventos_financeiros ef on (c.idevento = ef.idevento)
                            left outer join bandeiras_cartoes bc on (c.idbandeira = bc.idbandeira)
                            left outer join bancos b on (c.idbanco = b.idbanco)
                            left outer join pagamentos_compartilhados_matriculas pcm on (c.idpagamento_compartilhado = pcm.idpagamento and pcm.idmatricula = " . $this->id . " and pcm.ativo = 'S')
                            left outer join contas c_transferida on (c.idconta_transferida = c_transferida.idconta)
                          where
                            (c.idmatricula = " . $this->id . " or pcm.idmatricula is not null) and
                            c.ativo = 'S'
                             ";
        $this->ordem = "asc";
        $this->ordem_campo = "c.data_vencimento";
        $this->limite = -1;
        $contas = $this->retornarLinhas();
        $this->matricula["total_mensalidades"] = 0;
        foreach ($contas as $conta) {
            if ($conta["idevento"] == $eventoFinanceiroMensalidade["idevento"]) {
                if ($conta['situacao_cancelada'] != 'S' && $conta['situacao_renegociada'] != 'S' && $conta['situacao_transferida'] != 'S') {
                    if ($conta['idpagamento_compartilhado']) {
                        $this->matricula["total_mensalidades"] += ($conta['valor_matricula'] / $conta['total_contas_compartilhadas']);
                    } else
                        $this->matricula["total_mensalidades"] += $conta["valor"];
                }
            }
            $contasArray[$conta["idevento"]][] = $conta;
        }
        $this->matricula["total_mensalidades"] = number_format($this->matricula["total_mensalidades"], 2, '.', '');
        return $contasArray;
    }

    public function retornaCurriculo() {
        $this->sql = 'SELECT
                        o.nome AS oferta,
                        c.porcentagem_ava
                    FROM
                        matriculas m
                        INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
                        INNER JOIN ofertas_cursos_escolas ocp ON (o.idoferta = ocp.idoferta AND m.idescola = ocp.idescola AND m.idcurso = ocp.idcurso AND ocp.ativo = "S")
                        INNER JOIN curriculos c ON (ocp.idcurriculo = c.idcurriculo AND c.ativo = "S")
                    WHERE
                        m.idmatricula = '.(int) $this->id.' AND
                        m.idpessoa = '.(int) $this->idpessoa.' AND
                        m.ativo = "S"';
        return $this->retornarLinha($this->sql);
    }

    public function retornaPorcentagemAva($idAva) {
        $this->sql = 'SELECT
                        por.data_ini,
                        por.data_fim,
                        por.porcentagem
                    FROM
                        matriculas m
                        INNER JOIN matriculas_avas_porcentagem por ON (por.idmatricula = m.idmatricula)
                    WHERE
                        por.idmatricula = '.(int) $this->id.' AND
                        por.idava = '.(int) $idAva.' AND
                        m.ativo = "S"';
        return $this->retornarLinha($this->sql);
    }

    public function RetornarContratos()
    {
        $this->sql = "SELECT
                            mc.*,
                            c.nome AS contrato,
                            ct.nome AS tipo
                          FROM
                            matriculas_contratos mc
                            left outer join contratos c ON (mc.idcontrato = c.idcontrato)
                            INNER JOIN contratos_tipos ct ON (mc.idtipo = ct.idtipo or c.idtipo = ct.idtipo)
                          WHERE
                            mc.idmatricula = ".$this->id." AND
                            mc.ativo = 'S' AND
                            mc.cancelado IS NULL";
        $this->ordem = "asc";
        $this->ordem_campo = "data_cad";
        $this->limite = -1;

        $retorno = $this->retornarLinhas();

        return $retorno;
    }

    public function RetornarContratosPendentes()
    {
        $this->sql = "SELECT
                            mcg.*,
                            'S' AS contrato_pendente,
                            c.nome AS contrato,
                            ct.nome AS tipo,
                            mcg.data_cad as assinado
                        FROM
                            matriculas_contratos_gerados mcg
                        LEFT OUTER JOIN contratos c ON (c.idcontrato = mcg.idcontrato)
                        INNER JOIN contratos_tipos ct ON (c.idtipo = ct.idtipo)
                        WHERE
                            mcg.idmatricula = ".$this->id."
                        AND mcg.ativo='S'";

        $this->ordem = "asc";
        $this->ordem_campo = "data_cad";
        $this->limite = -1;

        $retorno = $this->retornarLinhas();

        return $retorno;
    }

    public function existeContratoParaAssinar()
    {
        $sql = 'SELECT COUNT(*) as total FROM matriculas_contratos
                    where idmatricula = "'.$this->id.'" and ativo = "S" ';

        $query = $this->executaSql($sql);
        $totalGeral = mysql_fetch_assoc($query);

        $sql = 'SELECT COUNT(*) as total FROM matriculas_contratos
                    where idmatricula = "'.$this->id.'" and ativo = "S" AND assinado IS NULL AND cancelado IS NULL ';

        $query = $this->executaSql($sql);
        $totalNaoAssinado = mysql_fetch_assoc($query);

        if($totalNaoAssinado['total'] == $totalGeral['total'])
            return true;
        else
            return false;
    }

    public function retornarContrato($idmatricula_contrato) {
        $this->sql = "SELECT
                           mc.*,
                           ct.nome as tipo,
                           c.nome as contrato,
                           m.data_cad as data_matricula
                        FROM
                            matriculas_contratos mc
                            INNER JOIN matriculas m ON (m.idmatricula = mc.idmatricula)
                            LEFT OUTER JOIN contratos c ON (mc.idcontrato = c.idcontrato)
                            INNER JOIN contratos_tipos ct ON (c.idtipo = ct.idtipo or mc.idtipo = ct.idtipo)
                        WHERE
                            mc.idmatricula_contrato = '" . $idmatricula_contrato . "' and
                            mc.idmatricula = '" . $this->id . "' AND
                            mc.ativo = 'S'";
        return $this->retornarLinha($this->sql);
    }

    public function aceitarContrato($idmatricula_contrato)
    {
        $erros = array();
        $this->retorno = array();

        $matricula = $this->Retornar();

        //Só pode aceitar contrato se a sindicato da matrícula tiver acesso ao AVA liberado
        if ($matricula['acesso_ava'] == 'S') {
            //Verifica se o contrato existe e não está cancelado
            $this->sql = 'SELECT
                                idmatricula_contrato
                            FROM
                                matriculas_contratos
                            WHERE
                                idmatricula_contrato = "'.$idmatricula_contrato.'" AND
                                idmatricula = "'.$this->id.'" AND
                                cancelado IS NULL';
            $contrato = $this->retornarLinha($this->sql);
            $this->sql = "SELECT idmatricula_contrato
                            FROM
                                 matriculas_contratos_gerados
                            WHERE
                                  idmatricula_contrato = {$idmatricula_contrato} AND
                                  ativo = 'S'";
            $contrato_gerado = $this->retornarLinha($this->sql);

            if (!$contrato['idmatricula_contrato'] && !$contrato_gerado['idmatricula_contrato']) {
                $this->retorno['sucesso'] = false;
                $this->retorno['mensagem'] = "contratos_matricula_nao_existe";
            } else {
                if ($contrato['idmatricula_contrato']) {
                    $this->sql = 'UPDATE
                                    matriculas_contratos
                                SET
                                    assinado = NOW(),
                                    nao_assinado = NULL,
                                    idpessoa_assinou = "' . $this->idpessoa . '",
                                    aceito_aluno = "S"
                                WHERE
                                    idmatricula_contrato = "' . $idmatricula_contrato . '" AND
                                    idmatricula = "' . $this->id . '" AND
                                    cancelado IS NULL';
                    $salvar = $this->executaSql($this->sql);
                } else {
                    $salvar = $this->aceitarContratoPendente($contrato_gerado['idmatricula_contrato']);
                }
                if ($salvar) {
                    $this->AdicionarHistorico($this->idpessoa, 'contrato', 'assinou', NULL, NULL, $idmatricula_contrato);
                    $sql = "SELECT oce.dias_para_ava as dias
                            FROM ofertas_cursos_escolas oce
                            INNER JOIN matriculas m ON (
                                oce.idoferta = m.idoferta AND
                                oce.idcurso = m.idcurso AND
                                oce.idescola = m.idescola)
                            WHERE
                                m.idmatricula = {$this->id} AND
                                m.data_prolongada IS NULL";
                    $dias_ava = $this->retornarLinha($sql);
                    if ($dias_ava) {
                        $data_prol = date("Y-m-d", strtotime($dias_ava['dias']." days"));
                        $sql = "UPDATE matriculas
                                SET
                                    data_prolongada = {$data_prol}
                                WHERE
                                    idmatricula = '{$this->id}' AND
                                    data_prolongada IS NULL";
                        $this->executaSql($sql);
                    }
                    $this->retorno['sucesso'] = true;
                    $this->retorno['mensagem'] = 'contratos_matricula_assinado_sucesso';
                } else {
                    $this->retorno['sucesso'] = false;
                    $this->retorno['mensagem'] = 'contratos_matricula_assinado_erro';
                }
            }
        } else {
            $this->retorno['sucesso'] = false;
            $this->retorno['mensagem'] = 'sem_acesso_ava_sindicato';
        }

        return $this->retorno;
    }

    public function somaHorasOfflinesDisciplinas($idAva,$idDisciplina){

        $this->sql = "SELECT idava,iddisciplina,SEC_TO_TIME( SUM( TIME_TO_SEC(tempo_offline))) AS tempo_total_offline
        FROM avas_disciplinas WHERE idava = ".(int)$idAva." AND iddisciplina = ".(int)$idDisciplina." GROUP BY idava,iddisciplina ";
        $this->limite = -1;
        $this->ordem = 'ASC';
        $this->ordem_campo = 'idava';
        $retorno = $this->retornarLinha($this->sql);
        return $retorno;

    }

    public function matriculasRegistroAcessos($idMatricula) {

        $this->sql = "SELECT
        idacessomatricula,
        idpessoa,
        idmatricula,
        idava,
        inatividade,
        date_format( inicio, '%Y-%m-%d' ) AS inicio_data,
        date_format( fim, '%Y-%m-%d' ) AS fim_data,
        date_format( inicio, '%H:%i' ) AS inicio_hora,
	    date_format( fim, '%H:%i' ) AS fim_hora,
        SEC_TO_TIME( SUM( TIME_TO_SEC( duracao ) ) ) AS duracao
    FROM
        pessoas_acessos_matriculas
    WHERE
        idmatricula = '".$idMatricula."'
    GROUP BY
        idacessomatricula,
        idmatricula,
        idava,
        inicio_data,
        fim_data
    ";
        $this->limite = -1;
        $this->ordem = 'ASC';
        $this->ordem_campo = 'inicio_data ASC,inicio_hora';
        $retorno = $this->retornarLinhas();
        return $retorno;
    }

    public function matriculasSomatorioHorasAcessos($idMatricula) {

        $this->sql = "SELECT
        idava,
        idpessoa,
        idmatricula,
        date_format( inicio, '%Y-%m-%d' ) AS inicio_data,
        date_format( fim, '%Y-%m-%d' ) AS fim_data,
        date_format( inicio, '%H:%i' ) AS inicio_hora,
	    date_format( fim, '%H:%i' ) AS fim_hora,
        SEC_TO_TIME( SUM( TIME_TO_SEC( duracao ) ) ) AS duracao
    FROM
        pessoas_acessos_matriculas
        WHERE idmatricula = '".$idMatricula."'
    GROUP BY
        idava";
        $this->limite = -1;
        $this->ordem = 'asc';
        $this->ordem_campo = 'idava';
        $retorno = $this->retornarLinhas();
        return $retorno;

    }

    function retornaOpcoesVerificaVotoEnquete($idenquete, $idava) {
        $this->sql = 'select
                        *
                    from
                        avas_enquetes_opcoes
                    where
                        idenquete = ' . $idenquete . ' and ativo = "S"';
        $this->limite = -1;
        $this->ordem = 'asc';
        $this->ordem_campo = 'ordem';
        $opcoes = $this->retornarLinhas();

        $totalVotos = 0;

        foreach ($opcoes as $ind => $opcao) {
            $this->sql = 'select
                            count(*) as votos
                        from
                            avas_enquetes_opcoes_votos eov
                            inner join avas_enquetes_opcoes eo on (eov.idopcao = eo.idopcao)
                        where
                            eo.idenquete = ' . $idenquete . ' and
                            eov.idopcao = ' . $opcao['idopcao'];
            $votos = $this->retornarLinha($this->sql);
            $totalVotos += $opcoes[$ind]['votos'] = $votos['votos'];
        }

        $retorno['opcoes'] = $opcoes;
        $retorno['total_votos'] = $totalVotos;

        $this->sql = 'select
                          eov.idopcao
                      from
                          avas_enquetes_opcoes_votos eov
                          inner join avas_enquetes_opcoes eo on (eov.idopcao = eo.idopcao)
                      where
                          eo.idenquete = ' . $idenquete . ' and
                          eov.idmatricula = ' . $this->id . ' and
                          eov.idava = ' . $idava;
        $votou = $this->retornarLinha($this->sql);

        $retorno['votou'] = $votou['idopcao'];

        return $retorno;
    }

    function votarEnquete($idava) {
        if (verificaPermissaoAcesso(true)) {
            if ((int) $this->post['idopcao']) {
                $this->sql = 'insert into avas_enquetes_opcoes_votos set data_cad = now(), idava = ' . $idava . ', idmatricula = ' . $this->id . ', idopcao = ' . $this->post['idopcao'];
                $this->executaSql($this->sql);
                $idvoto = mysql_insert_id();
                $retorno = array(
                    'sucesso' => true,
                    'idvoto' => $idvoto
                );
            } else {
                $retorno = array(
                    'erro' => true,
                    'mensagem' => ''
                );
            }
            return $retorno;
        }
    }

    public function AdicionarHistorico($idusuario, $tipo, $acao, $de, $para, $id) {
        if (verificaPermissaoAcesso(false)) {
            $this->sql = "insert
                        matriculas_historicos
                      set
                        idmatricula = '" . $this->id . "',
                        data_cad = now(),
                        tipo = '" . $tipo . "',
                        acao = '" . $acao . "'";
            if ($this->modulo == "gestor" && $idusuario) {
                $this->sql .= ", idusuario = '" . $idusuario . "'";
            }

            if ($this->modulo == "aluno" || $this->modulo == "aluno_novo" && $this->idpessoa) {
                $this->sql .= ", idpessoa = '" . $this->idpessoa . "'";
            }

            if ($this->modulo == "atendente" && $this->idvendedor) {
                $this->sql .= ", idvendedor = '" . $this->idvendedor . "'";
            }

            if ($this->modulo == "devedorsolidario" && $this->idpessoa) {
                $this->sql .= ", iddevedor = '" . $this->idpessoa . "'";
            }

            if ($de) {
                $this->sql .= ", de = '" . $de . "'";
            }

            if ($para) {
                $this->sql .= ", para = '" . $para . "'";
            }

            if ($id) {
                $this->sql .= ", id = '" . $id . "'";
            }

            if(($de || $para) && ($de == $para)) {
                return true;
            } else {
                return $this->executaSql($this->sql);
            }
        }
    }

    public function RetornarOferta()
    {
        $this->sql = "SELECT * FROM ofertas where idoferta = " . $this->matricula["idoferta"];
        return $this->retornarLinha($this->sql);
    }

    public function RetornarEscola()
    {
        $this->sql = "SELECT * FROM escolas where idescola = " . $this->matricula["idescola"];
        return $this->retornarLinha($this->sql);
    }

    public function RetornarTurma()
    {
        $this->sql = "SELECT * FROM ofertas_turmas where idturma = " . $this->matricula["idturma"];
        return $this->retornarLinha($this->sql);
    }

    public function RetornarMantenedora()
    {
        $this->sql = "SELECT * FROM mantenedoras where idmantenedora = " . $this->matricula["idmantenedora"];
        return $this->retornarLinha($this->sql);
    }

    public function RetornarSindicato()
    {
        $this->sql = "SELECT * FROM sindicatos where idsindicato = " . $this->matricula["idsindicato"];
        return $this->retornarLinha($this->sql);
    }

    public function RetornarCursoSindicato()
    {
        $this->sql = 'SELECT
                        *
                    FROM
                        cursos_sindicatos
                    WHERE
                        idsindicato = ' .$this->matricula["idsindicato"].' AND
                        idcurso = '.$this->matricula["idcurso"];
        return $this->retornarLinha($this->sql);
    }

    public function RetornarPessoa()
    {
        $this->sql = "SELECT
                        p.*,
                        e.nome as estado,
                        c.nome as cidade
                      FROM
                        pessoas p
                        left outer join estados e on (p.idestado = e.idestado)
                        left outer join cidades c on (p.idcidade = c.idcidade)
                      where
                        idpessoa = " . $this->matricula["idpessoa"];
        return $this->retornarLinha($this->sql);
    }

    public function listarMensagensParaCertificado($idmatricula)
    {
        if (!is_numeric($idmatricula)) {
            throw new InvalidArgumentException('Primeiro parâmetro tem que ser um valor numérico.');
        }
        $query = sprintf('SELECT * FROM `matriculas_mensagens` WHERE idmatricula = %s AND ativo = "S" AND exibir_diploma = "S"', $idmatricula);
        $this->ordem = "asc";
        $this->ordem_campo = "idmensagem";
        $this->limite = -1;
        return $this->set('sql', $query)->retornarLinhas();
    }

    public function temDiploma($idmatricula)
    {
        if (!$idmatricula) {
            throw new InvalidArgumentException('The parameter $idmatricula is mandatory.');
        }
        $idmatricula = (int)$idmatricula;
        $query = "SELECT idfolha, COUNT(*) AS total
                    FROM `folhas_registros_diplomas_matriculas`
                  WHERE (idmatricula = {$idmatricula})
                    AND (cancelado = 'N')
                    AND ativo = 'S'";
        return $this->retornarLinha($query);
    }

    /**
     * Verify if a matriculation has a diploma or stay in a list of
     * `Folha de Registro`
     *
     * @param  integer $idmatriculation matriculation number for consulting
     *
     * @return boolean
     */
    public function hasDiploma($idmatriculation)
    {
        if (!$idmatriculation) {
            throw new InvalidArgumentException('The parameter $idmatriculation is mandatory.');
        }
        $idmatriculation = (int)$idmatriculation;
        $query = "SELECT idfolha, COUNT(*) AS total
                    FROM `folhas_registros_diplomas_matriculas`
                  WHERE (idmatricula = {$idmatriculation})
                    AND (cancelado = 'N')
                    AND ativo ='S'";
        return $this->retornarLinha($query);
    }

    public function retornarSituacaoDiplomaExpedido() {
        $this->sql = 'SELECT idsituacao FROM matriculas_workflow WHERE diploma_expedido = "S" AND ativo = "S" ORDER BY idsituacao DESC LIMIT 1';
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoHomologarCertificado() {
        $this->sql = 'SELECT idsituacao FROM matriculas_workflow WHERE homologar_certificado = "S" AND ativo = "S" ORDER BY idsituacao DESC LIMIT 1';
        return $this->retornarLinha($this->sql);
    }

    public function RetornarCurriculo()
    {
        $this->sql = "select
                        c.*
                    from
                        curriculos c
                        inner join ofertas_cursos_escolas ocp on (c.idcurriculo = ocp.idcurriculo)
                    where
                        ocp.idoferta = '" . $this->matricula["idoferta"] . "' and
                        ocp.idcurso = '" . $this->matricula["idcurso"] . "' and
                        ocp.idescola = '" . $this->matricula["idescola"] . "' and
                        ocp.ativo = 'S'";
        return $this->retornarLinha($this->sql);
    }

    public function retornarNotasDisciplina($idmatricula, $iddisciplina)
    {
        $this->sql = "SELECT
                    mn.*,
                    mnt.nome as tipo,
                    mnt.sigla as tipo_sigla,
                    mp.nome as modelo,
                    pp.data_realizacao,
                    pp.hora_realizacao_de,
                    pp.hora_realizacao_ate,
                    IF(mn.aproveitamento_estudo = 'S', 'AE', mn.nota) as nota
                  FROM
                    matriculas_notas mn
                  left join matriculas_notas_tipos mnt on mn.idtipo = mnt.idtipo
                  left join provas_solicitadas ps on mn.id_solicitacao_prova = ps.id_solicitacao_prova
                  left join provas_presenciais pp on ps.id_prova_presencial = pp.id_prova_presencial
                  left join modelos_prova mp on mn.idmodelo = mp.idmodelo
                  where
                    mn.idmatricula = " . intval($idmatricula) . " and
          mn.iddisciplina = " . intval($iddisciplina) . " and
          mn.ativo = 'S'";
        $this->ordem = "asc";
        $this->ordem_campo = " mn.idmatricula_nota ";
        $this->limite = -1;
        $notas = $this->retornarLinhas();
        return $notas;
    }

    public function verificaMatriculaAprovadaNotas($porcentagemFolhaRegistro = null)
    {
        $this->sql = '
                    SELECT
                        d.*,
                        cb.nome as bloco,
                        cbd.idbloco_disciplina,
                        cbd.idformula,
                        cbd.ignorar_historico,
                        cbd.contabilizar_media,
                        cbd.exibir_aptidao,
                        oca.idava,
                        c.idcurriculo,
                        c.media as media_curriculo
                    FROM
                        disciplinas d
                        INNER JOIN curriculos_blocos_disciplinas cbd on (d.iddisciplina = cbd.iddisciplina and cbd.ativo = "S")
                        INNER JOIN curriculos_blocos cb on (cbd.idbloco = cb.idbloco and cb.ativo = "S")
                        INNER JOIN ofertas_cursos_escolas ocp on (cb.idcurriculo = ocp.idcurriculo)
                        INNER JOIN curriculos c on ocp.idcurriculo = c.idcurriculo
                        INNER JOIN matriculas m on (ocp.idescola = m.idescola and ocp.idoferta = m.idoferta and ocp.idcurso = m.idcurso)
                        LEFT OUTER JOIN ofertas_curriculos_avas oca on (oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = m.idoferta and oca.ativo = "S")
                    WHERE
                        m.idmatricula = ' . $this->id . ' and (cbd.ignorar_historico = "N" or cbd.ignorar_historico IS NULL) ';
        $this->ordem = 'asc';
        $this->ordem_campo = 'cb.ordem, cbd.ordem, d.nome';
        $this->limite = -1;
        $disciplinas = $this->retornarLinhas();

        if (!count($disciplinas)) {
            return false;
        }

        $qtdDisciplinas = count($disciplinas);

        $reprovado = false;
        $qtdDisciplinasAprovadas = 0;
        foreach ($disciplinas as $ind => $disciplina) {
            $disciplina_situacao = $this->retornarSituacaoDisciplina($this->id, $disciplina, $disciplina['media_curriculo']);
            $qtdDisciplinasAprovadas++;
            if ($disciplina_situacao['situacao'] != 'Aprovado' && $disciplina_situacao['situacao'] != 'Apto') {
                $reprovado = true;
                $qtdDisciplinasAprovadas--;
            }
        }

        if ($porcentagemFolhaRegistro) {
            $divisao = $qtdDisciplinasAprovadas / $qtdDisciplinas;
            $porcentagemFinal = $divisao * 100;
            if ($porcentagemFinal >= $porcentagemFolhaRegistro) {
                return true;
            }
            return false;
        }

        if ($reprovado)
            return false;

        return true;
    }

    public function verificaMatriculaAprovadaNotasDias($idmatricula , $idoferta , $idcurso)
    {
        if(!$idmatricula || !$idoferta || !$idcurso){
            return false;
        }

        $sql = "SELECT mh.data_cad as data_conclusao FROM matriculas_historicos mh LEFT JOIN matriculas_workflow mw ON (mh.para = mw.idsituacao) WHERE idmatricula = '".$idmatricula."' AND mw.ativa = 'S'";

        $matricula = $this->retornarLinha($sql);

        $sql = "SELECT gerar_quantidade_dias FROM ofertas_cursos WHERE idoferta = ".$idoferta." AND idcurso = ".$idcurso;

        $quantidade_dias = $this->retornarLinha($sql);

        if (!empty($matricula['data_conclusao'])) {
            $data_conclusao = new DateTime(substr($matricula['data_conclusao'],0,11));
            $data_atual = new DateTime();
            $data_conclusao_dias = $data_conclusao->modify('+ '.$quantidade_dias['gerar_quantidade_dias'].' day');
            if ($data_atual > $data_conclusao_dias) {
                return true;
            }
        }

        return false;
    }

    public function retornaDadosOfertaCurso($idOferta, $idCurso)
    {
        $this->sql = "SELECT
                         *
                      FROM
                         ofertas_cursos
                      WHERE idoferta = $idOferta
                      AND idcurso = $idCurso
                      AND ativo = 'S'";

        return $this->retornarLinha($this->sql);
    }

    public function se_aluno_historico(&$idsindicato, &$idcurso)
    {
        $this->sql = 'SELECT idhistorico_escolar FROM cursos_sindicatos
                      WHERE
                          idsindicato = '.$idsindicato.'
                          AND idcurso = '.$idcurso;
        $res = $this->retornarLinha($this->sql);
        return (is_numeric($res['idhistorico_escolar']))?(true):(false);
    }

    public function getStudentName($idMatriculation)
    {
        if (!$idMatriculation) {
            throw new InvalidArgumentException('The parameter $idMatriculation is mandatory.');
        }
        $idMatriculation = (int)$idMatriculation;
        $query = "SELECT p.* FROM `pessoas` AS p
                    INNER JOIN `matriculas` AS m
                        ON (m.idpessoa = p.idpessoa)
                    WHERE m.idmatricula = {$idMatriculation}";
        $result = $this->retornarLinha($query);
        return $result['nome'];
    }



    public function atualizarSituacaoConcluido($idMatricula)
    {
        if (! is_numeric($idMatricula)) {
            throw new InvalidArgumentException('O parametro `IdMatricula` precisa ser um valor numérico.');
        }

        $idMatricula = (int) $idMatricula;

        $situacaoConcluida = $this->retornarSituacaoConcluido();
        $situacaoAtiva = $this->retornarSituacaoAtiva();

        $sqlMatricula = 'SELECT idsituacao, data_conclusao FROM matriculas where idmatricula = ' . $idMatricula;
        $linhaAntiga = $this->retornarLinha($sqlMatricula);

        if ($linhaAntiga['idsituacao'] <> $situacaoAtiva['idsituacao']) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'situacao_matricula_nao_ativa';
            return $retorno;
        }

        $dataAtual = (new DateTime())->format('Y-m-d');
        $sql = 'UPDATE matriculas SET idsituacao = ' . $situacaoConcluida['idsituacao'] . ',
            data_conclusao = "' . $dataAtual . '" WHERE idmatricula = ' . $idMatricula;
        $this->executaSql($sql);

        $linhaNova = $this->retornarLinha($sqlMatricula);
        // $this->adicionarHistorico(null, 'situacao', 'modificou', $linhaAntiga['idsituacao'], $linhaNova['idsituacao'], null);
        $this->AdicionarHistorico(null, 'data_conclusao', 'modificou', $linhaAntiga['data_conclusao'], $dataAtual, null);

        $retorno['sucesso'] = true;
        $retorno['mensagem'] = 'mensagem_situacao_sucesso';

        return $retorno;
    }

    public function retornarNomeLogradouro($idlogradouro)
    {
        $sql = "select nome FROM logradouros where idlogradouro = '" . $idlogradouro . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
    }

    public function retornarDocumentosPendentes($idmatricula, $idsindicato, $idcurso, $aguardando = true)
    {
        $retorno = array();
        $situacaoAguardando = ($aguardando) ? " or situacao = 'aguardando'" : "";
        $this->sql = "select
                    td.idtipo,
                    td.nome
                  FROM
                    tipos_documentos td
                  where
                    td.ativo = 'S' and ativo_painel = 'S' and
                        (
                            (
                                td.idtipo in(select idtipo FROM tipos_documentos_sindicatos where idtipo = td.idtipo and idsindicato = " . $idsindicato . " and ativo = 'S'
                                ) or td.todas_sindicatos_obrigatorio = 'S'
                            ) and
                            (
                                td.idtipo in(select idtipo FROM tipos_documentos_cursos where idtipo = td.idtipo and idcurso = " . $idcurso . " and ativo = 'S'
                                ) or td.todos_cursos_obrigatorio = 'S'
                            )
                        )
                  group by
                    td.idtipo";
        $this->limite = -1;
        $this->ordem_campo = false;
        $this->ordem = false;
        $tipos = $this->retornarLinhas();
        foreach ($tipos as $tipo) {
            $this->sql = "select count(*) as total FROM matriculas_documentos where idmatricula = " . $idmatricula . " and idtipo = " . $tipo["idtipo"] . " and ativo = 'S' and (situacao = 'aprovado' $situacaoAguardando) and idtipo_associacao is null";
            $totalDocumento = $this->retornarLinha($this->sql);
            if ($totalDocumento["total"] <= 0) {
                $retorno[] = $tipo;
            }
        }
        return $retorno;
    }

    public function retornarSituacaoRenegociadaConta()
    {
        $sql = "select idsituacao FROM contas_workflow where renegociada = 'S' and ativo = 'S' limit 1 ";
        return $this->retornarLinha($sql);
    }

    public function retornarSituacaoCanceladaConta()
    {
        $sql = "select idsituacao FROM contas_workflow where cancelada = 'S' and ativo = 'S' limit 1 ";
        return $this->retornarLinha($sql);
    }

    public function retornarSituacaoTransferidaConta()
    {
        $sql = "select idsituacao FROM contas_workflow where transferida = 'S' and ativo = 'S' limit 1 ";
        return $this->retornarLinha($sql);
    }

    public function retornarDocumentos()
    {
        $this->sql = "SELECT
                            md.*,
                            td.nome as tipo
                          FROM
                            matriculas_documentos md
                            INNER JOIN tipos_documentos td on (md.idtipo = td.idtipo)
                          where
                            md.idmatricula = " . $this->id . " and
                            md.ativo = 'S'";
        $this->ordem = "asc";
        $this->ordem_campo = "data_cad";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function retornarDataCadMatricula()
    {
        $this->sql = 'SELECT data_cad FROM matriculas WHERE idmatricula = "'.$this->id.'" ';
        $resultado = $this->retornarLinha($this->sql);
        return $resultado['data_cad'];
    }

    public function gerarContratoPendente($idioma = null)
    {
        if ($this->post['idcontrato']) {
            //CONTRATO
            $contract = new Contratos;
            $this->sql = "SELECT * FROM contratos where idcontrato = " . $this->post["idcontrato"];
            $contrato = $this->retornarLinha($this->sql);
            $documento = $contrato["contrato"];

            $documento = $contract->filtrar($documento);

            //MATRICULA
            $this->sql = "
                SELECT
                    m.*, i.nome as sindicato, c.nome as curso, p.nome_fantasia as escola,
                    v.nome as vendedor, o.nome as oferta, t.nome as turma, cu.nome as curriculo
                FROM matriculas m
                    INNER join sindicatos i on m.idsindicato = i.idsindicato
                    INNER join cursos c on m.idcurso = c.idcurso
                    INNER join escolas p on m.idescola = p.idescola
                    LEFT join vendedores v on m.idvendedor = v.idvendedor
                    INNER join ofertas o on m.idoferta = o.idoferta
                    INNER join ofertas_turmas t on m.idturma = t.idturma
                    INNER join ofertas_cursos_escolas ocp
                        on ocp.idescola = m.idescola and ocp.idcurso = m.idcurso and ocp.idoferta = m.idoferta
                    LEFT join curriculos cu on ocp.idcurriculo = cu.idcurriculo
                WHERE m.idmatricula = " . $this->id;
            $matricula = $this->retornarLinha($this->sql);

            foreach ($matricula as $ind => $val) {
                if ($ind == "data_cad") {
                    $documento = str_ireplace("[[matricula][data]]", formataData($val, "br", 1), $documento);
                    $documento = str_ireplace("[[matricula][data_registro]]", formataData($matricula['data_cad'], 'br', 0), $documento);
                } elseif ($ind == "data_matricula") {
                    $documento = str_ireplace("[[matricula][data_matricula]]", formataData($matricula['data_matricula'], 'br', 0), $documento);
                } elseif ($ind == "data_conclusao") {
                    $documento = str_ireplace("[[matricula][data_conclusao]]", formataData($matricula['data_conclusao'], 'br', 0), $documento);
                } elseif ($ind == "forma_pagamento") {
                    $documento = str_ireplace("[[matricula][forma_pagamento]]", $GLOBALS['forma_pagamento_conta']['pt_br'][$matricula['forma_pagamento']], $documento);
                } else {
                    $documento = str_ireplace("[[matricula][" . $ind . "]]", $val, $documento);
                }
            }

            //ALUNO
            $this->sql = "SELECT
                                p.*,
                                e.nome AS estado,
                                c.nome AS cidade
                            FROM
                                pessoas p
                                LEFT OUTER JOIN estados e ON (e.idestado = p.idestado)
                                LEFT OUTER JOIN cidades c ON (c.idcidade = p.idcidade)
                            WHERE
                                idpessoa = " . $matricula["idpessoa"];
            $aluno = $this->retornarLinha($this->sql);
            foreach ($aluno as $ind => $val) {
                if ($ind == "data_nasc") {
                    $documento = str_ireplace("[[aluno][data_nasc]]", formataData($val, "br", 0), $documento);
                    $documento = str_ireplace("[[cliente][data_nasc]]", formataData($val, "br", 0), $documento);
                } elseif ($ind == "estado_civil") {
                    $documento = str_ireplace("[[aluno][estado_civil]]", $GLOBALS['estadocivil'][$GLOBALS['config']["idioma_padrao"]][$val], $documento);
                    $documento = str_ireplace("[[cliente][estado_civil]]", $GLOBALS['estadocivil'][$GLOBALS['config']["idioma_padrao"]][$val], $documento);
                } elseif ($ind == "idlogradouro") {
                    $documento = str_ireplace("[[aluno][logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                    $documento = str_ireplace("[[cliente][logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                } else {
                    $documento = str_ireplace("[[aluno][" . $ind . "]]", $val, $documento);
                    $documento = str_ireplace("[[cliente][" . $ind . "]]", $val, $documento);
                }
            }
            //FIM ALUNO

            foreach ($aluno as $ind => $val) {
                if ($ind == "idlogradouro") {
                    $documento = str_ireplace("[[dev_solidario][logradouro]]", "NÃO APLICÁVEL", $documento);
                } else {
                    $documento = str_ireplace("[[dev_solidario][" . $ind . "]]", "NÃO APLICÁVEL", $documento);
                }
            }

            //CFC
            $this->sql = "SELECT
                                p.*,
                                e.nome AS estado,
                                c.nome AS cidade,
                                ge.nome AS gerente_estado,
                                gc.nome AS gerente_cidade,
                                re.nome AS responsavel_legal_estado,
                                rc.nome AS responsavel_legal_cidade,
                                s.nome AS sindicato
                            FROM
                                escolas p
                                INNER JOIN matriculas m ON (m.idescola = p.idescola)
                                INNER JOIN sindicatos s ON (s.idsindicato = p.idsindicato)
                                LEFT OUTER JOIN estados e ON (e.idestado = p.idestado)
                                LEFT OUTER JOIN cidades c ON (c.idcidade = p.idcidade)
                                LEFT OUTER JOIN estados ge ON (ge.idestado = p.gerente_idestado)
                                LEFT OUTER JOIN cidades gc ON (gc.idcidade = p.gerente_idcidade)
                                LEFT OUTER JOIN estados re ON (re.idestado = p.responsavel_legal_idestado)
                                LEFT OUTER JOIN cidades rc ON (rc.idcidade = p.responsavel_legal_idcidade)
                            WHERE
                                m.idmatricula = " . $this->id;
            $cfc = $this->retornarLinha($this->sql);
            foreach ($cfc as $ind => $val) {
                if ($ind == "responsavel_legal_data_nasc") {
                    $documento = str_ireplace("[[cfc][responsavel_legal_data_nasc]]", formataData($val, "br", 0), $documento);
                    $documento = str_ireplace("[[cfc][responsavel_legal_data_nasc]]", formataData($val, "br", 0), $documento);
                }elseif ($ind == "gerente_data_nasc") {
                    $documento = str_ireplace("[[cfc][gerente_data_nasc]]", formataData($val, "br", 0), $documento);
                    $documento = str_ireplace("[[cfc][gerente_data_nasc]]", formataData($val, "br", 0), $documento);
                }elseif ($ind == "diretor_ensino_data_nasc") {
                    $documento = str_ireplace("[[cfc][diretor_ensino_data_nasc]]", formataData($val, "br", 0), $documento);
                    $documento = str_ireplace("[[cfc][diretor_ensino_data_nasc]]", formataData($val, "br", 0), $documento);
                }elseif ($ind == "responsavel_legal_idlogradouro") {
                    $documento = str_ireplace("[[cfc][responsavel_legal_logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                    $documento = str_ireplace("[[cfc][responsavel_legal_logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                }elseif ($ind == "gerente_idlogradouro") {
                    $documento = str_ireplace("[[cfc][gerente_logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                    $documento = str_ireplace("[[cfc][gerente_logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                }elseif ($ind == "idlogradouro") {
                    $documento = str_ireplace("[[cfc][logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                    $documento = str_ireplace("[[cfc][logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                }else {
                    $documento = str_ireplace("[[cfc][" . $ind . "]]", $val, $documento);
                    $documento = str_ireplace("[[cfc][" . $ind . "]]", $val, $documento);
                }
            }
            //FIM CFC

            //CURSO
            $inicioCurso = $this->retornarInicioCurso($matricula['idoferta'], $matricula['idcurso']);//Retorna a data de início do curso data_inicio_aula
            $acessoAva = $this->retornarAcessoAva($matricula['idoferta'], $matricula['idcurso'], $matricula["idescola"]);//Retorna o Período de acesso ao ava

            $documento = str_ireplace("[[curso][inicio]]", formataData($inicioCurso['data_inicio_aula'], 'br', 0), $documento);
            $documento = str_ireplace("[[curso][termino]]", formataData($acessoAva['data_limite_acesso_ava'], 'br', 0), $documento);
            //FIM CURSO

            //FINANCEIRO
            $situacaoRenegociadaConta = $this->retornarSituacaoRenegociadaConta();
            $situacaoCanceladaConta = $this->retornarSituacaoCanceladaConta();
            $situacaoTransferidaConta = $this->retornarSituacaoTransferidaConta();
            $_GET['q']['1|cw.cancelada'] = 'N';
            $contasArray = $this->retornarContas(true);
            unset($_GET['q']['1|cw.cancelada']);

            $this->sql = "SELECT
                                idevento
                            FROM
                               eventos_financeiros
                            WHERE
                                mensalidade = 'S' and ativo = 'S' and ativo_painel = 'S'  LIMIT 1 ";
            $eventoMensalidade = $this->retornarLinha($this->sql);

            $total = 0;
            $total_compartilhado = 0;
            $total_outras = 0;
            foreach($contasArray as $idevento => $contas) {
                $this->sql = "SELECT
                                nome
                            FROM
                               eventos_financeiros
                            WHERE
                               idevento = ".$contas[0]['idevento']." and ativo = 'S' and ativo_painel = 'S'  LIMIT 1 ";

                $eventoTabela = $this->retornarLinha($this->sql);


                $tabelaFormaPagamentoDetalhado .= '<br><table border="1" style="width:500px; ">
                                      <tr>
                                          <td colspan="3">
                                              '.$eventoTabela['nome'].'
                                          </td>
                                      </tr>
                                       <tr>
                                                <td>Forma de Pagamento</td>
                                                 <td>Valor</td>
                                                <td>Vencimento</td>
                                       </tr>
                                      ';

                foreach($contas as $conta) {
                    if( $conta['valor_matricula']) {
                        $valor_parcela = ($conta["valor_matricula"]/$conta['total_contas_compartilhadas']);
                    }

                    if($situacaoRenegociadaConta['idsituacao'] != $conta['idsituacao'] && $situacaoCanceladaConta['idsituacao'] != $conta['idsituacao']  && $situacaoTransferidaConta['idsituacao'] != $conta['idsituacao']) {
                        if($eventoMensalidade['idevento'] == $conta['idevento'])
                            $total = $total +$conta['valor'];
                        if($eventoMensalidade['idevento'] != $conta['idevento'])
                            $total_outras = $total_outras +$conta['valor'];
                    }

                    $tabelaFormaPagamentoDetalhado .='  <tr>
                                                            <td>'.$GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$conta["forma_pagamento"]].'</td>
                                                            <td>'.number_format($conta['valor'], 2, ',', '.').'</td>
                                                            <td>'.formataData($conta['data_vencimento'], "br", 0).'</td>
                                                        </tr>';

                }
                $tabelaFormaPagamentoDetalhado .= '</table>';
            }

            $documento = str_ireplace("[[financeiro][forma_pagamento_detalhado]]", $tabelaFormaPagamentoDetalhado, $documento);
            $documento = str_ireplace("[[financeiro][valor_total_mens]]", "R$ ".number_format($matricula['valor_contrato'], 2, ',', '.'), $documento);
            $documento = str_ireplace("[[financeiro][valor_total_mens_extenso]]", extenso($matricula['valor_contrato'], true), $documento);
            $documento = str_ireplace("[[financeiro][valor_total_outras]]", "R$ ".$total_outras, $documento);
            $documento = str_ireplace("[[financeiro][valor_total_outras_extenso]]", extenso($total_outras, true), $documento);

            $tabelaFormaPagamento = $GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula["forma_pagamento"]];
            if ($matricula["forma_pagamento"] == 2 || $matricula["forma_pagamento"] == 3) {//Se a forma de pagamento for cartão de crédito ou débito
                require_once '../classes/bandeirascartoes.class.php';
                $bandeirasObj = new Bandeiras_Cartoes();

                $bandeira = $bandeirasObj->set('id', $matricula["idbandeira"])
                    ->Retornar();
                $tabelaFormaPagamento = '<table border="1" style="width:500px; ">
                                            <tr>
                                                <td colspan="2">
                                                    '.$GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula["forma_pagamento"]].'
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Bandeira</td>
                                                <td>Autorização</td>
                                            </tr>
                                            <tr>
                                                <td>'.$bandeira['nome'].'</td>
                                                <td>'.$matricula['autorizacao_cartao'].'</td>
                                            </tr></table>';
            }

            $documento = str_ireplace("[[financeiro][forma_pagamento]]", $tabelaFormaPagamento, $documento);
            $documento = str_ireplace("[[financeiro][qnt_parcelas]]", $matricula['quantidade_parcelas'], $documento);
            //FIM FINANCEIRO

            //VENDEDOR
            $this->sql = "SELECT * FROM vendedores WHERE idvendedor = '".$matricula["idvendedor"]."'";
            $vendedor = $this->retornarLinha($this->sql);
            foreach ($vendedor as $ind => $val) {
                if ($ind == "rg_data_emissao") {
                    $documento = str_ireplace("[[atendente][" . $ind . "]]", formataData($val, "br", 0), $documento);
                } else{
                    $documento = str_ireplace("[[atendente][" . $ind . "]]", $val, $documento);
                }
            }

            $removerVariavel = [];
            $this->sql = 'SHOW COLUMNS FROM vendedores';
            $queryRemoverVariavel = $this->executaSql($this->sql);
            while ($linhaVariavel = mysql_fetch_assoc($queryRemoverVariavel)) {
                $removerVariavel[] = $linhaVariavel['Field'];
            }

            foreach ($removerVariavel as $ind => $var) {
                $documento = str_ireplace('[[atendente][' . $var . ']]', '', $documento);
            }
            //FIM VENDEDOR

            //MATRICULA
            $documento = str_ireplace("[[matricula][sindicato]]", $matricula['sindicato'], $documento);
            $documento = str_ireplace("[[matricula][curso]]", $matricula['curso'], $documento);
            $documento = str_ireplace("[[matricula][escola]]", $matricula['escola'], $documento);
            $documento = str_ireplace("[[matricula][vendedor]]", $matricula['vendedor'], $documento);
            $documento = str_ireplace("[[matricula][oferta]]", $matricula['oferta'], $documento);
            $documento = str_ireplace("[[matricula][curriculo]]", $matricula['curriculo'], $documento);
            $documento = str_ireplace("[[matricula][turma]]", $matricula['turma'], $documento);
            $documento = str_ireplace("[[matricula][numero_contrato]]", $matricula['numero_contrato'], $documento);

            $removerVariavel = [
                'INSTITUICAO',
                'POLO',
                'ATENDENTE'
            ];

            foreach ($removerVariavel as $ind => $var) {
                $documento = str_ireplace('[[MATRICULA][' . $var . ']]', '', $documento);
            }
            //FIM MATRICULA

            //CAMPOS CONTRATO/ADICIONAIS

            //Cria a tabela de documentos
            $documentosArray = $this->retornarDocumentos();
            $tabela_documentos = '<table border="1" style="width:600px; ">
                                    <tr>
                                        <td>Tipo</td>
                                        <td>Aluno</td>
                                        <td>Arquivo</td>
                                        <td>Situação</td>
                                    </tr>';
            foreach ($documentosArray as $ind => $var) {
                $associacao = 'Aluno';
                if ($var["associacao"]) {
                    $associacao = $var["associacao"];
                }

                $tabela_documentos .= '<tr>
                                            <td>'.$var['tipo'].'</td>
                                            <td>'.$associacao.'</td>
                                            <td>'.$var['arquivo_nome'].'</td>
                                            <td>'.$GLOBALS['situacao_documento'][$GLOBALS['config']['idioma_padrao']][$var['situacao']].'</td>
                                        </tr>';
            }
            $tabela_documentos .= '</table>';
            $documento = str_ireplace("[[tabela_documentos]]", $tabela_documentos, $documento);

            $documento = str_ireplace("[[DATA_GERACAO_CONTRATO]]", date("d/m/Y"), $documento);

            setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $documento = str_ireplace("[[DATA_GERACAO_CONTRATO_EXTENSO]]",utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today'))),$documento);

            $documento = str_ireplace("[[QUEBRA_DE_PAGINA]]", "<div class='quebra_pagina'></div>", $documento);
            //FIM CONTRATO/ADICIONAIS

            //RESPONSAVEL
            $associado = array();
            if ($matricula["idassociado"]) {
                $this->sql = "SELECT * FROM pessoas where idpessoa = " . $matricula["idassociado"];
                $associado = $this->retornarLinha($this->sql);
            } else {
                $this->sql = "show columns FROM pessoas";
                $this->limite = -1;
                $this->ordem = false;
                $this->ordem_campo = false;
                $colunasResponsavel = $this->retornarLinhas();
                foreach ($colunasResponsavel as $colunaResponsavel) {
                    $associado[$colunaResponsavel["Field"]] = "";
                }
            }
            foreach ($associado as $ind => $val) {
                if ($ind == "data_nasc") {
                    $documento = str_ireplace("[[associado][data_nasc]]", formataData($val, "br", 0), $documento);
                } else {
                    $documento = str_ireplace("[[associado][" . $ind . "]]", $val, $documento);
                }
            }
            $documento = str_ireplace("[[campo_adicional_local]]", nl2br($this->post["campo_adicional_local"]), $documento);
            $documento = str_ireplace("[[campo_adicional_1]]", nl2br($this->post["campo_adicional_1"]), $documento);
            $documento = str_ireplace("[[campo_adicional_2]]", nl2br($this->post["campo_adicional_2"]), $documento);
            $documento = str_ireplace("[[campo_adicional_3]]", nl2br($this->post["campo_adicional_3"]), $documento);
            $documento = str_ireplace("[[campo_adicional_4]]", nl2br($this->post["campo_adicional_4"]), $documento);
            $retorno = array();

            if ($documento) {
                $data_matricula = $this->retornarDataCadMatricula();
                $data_matricula = new DateTime($data_matricula);

                $this->sql = "insert into matriculas_contratos_gerados set data_cad = now(),
                    idmatricula = " . $this->id . ", idcontrato = " . $contrato["idcontrato"] . ",
                    arquivo_pasta = '" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "'";
                $salvar = $this->executaSql($this->sql);

                $idcontratoMatricula = mysql_insert_id();
                if ($salvar) {
                    $pastaContratos = $_SERVER["DOCUMENT_ROOT"] . "/storage/matriculas_contratos_pendentes/" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "/" . $this->id;
                    if (!is_dir($pastaContratos)) {
                        @mkdir($pastaContratos, 0777, true);
                    }
                    @chmod ($pastaContratos, 0777);

                    $id = fopen($pastaContratos . "/" . $idcontratoMatricula . ".html", "w");

                    fwrite($id, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                                     <html xmlns="http://www.w3.org/1999/xhtml">
                                     <head>
                                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                        <title>' . $contrato["nome"] . '</title>
                                        <style type="text/css">
                                         .quebra_pagina {
                                            page-break-after:always;
                                        }
                                    </style>
                                </head>
                                <!-- Gerado pelo Alfama Oráculo -->
                                <!-- www.alfamaoraculo.com.br -->
                                <!-- Gerado dia: ' . date("d/m/Y H:i:s") . ' -->
                                <body>');
                    fwrite($id, $documento);
                    fwrite($id, "</body></html>");
                    fclose($id);
                    $retorno["sucesso"] = true;
                    $retorno["idmatricula_contrato"] = $idcontratoMatricula;
                    $retorno["mensagem"] = "contrato_gerado_sucesso";
                } else {
                    $retorno["erro"] = true;
                    $retorno["erros"][] = $this->sql;
                    $retorno["erros"][] = mysql_error();
                }
            } else {
                $retorno["erro"] = true;
                $retorno["erros"][] = $this->sql;
                $retorno["erros"][] = mysql_error();
            }
        } else {
            $retorno["erro"] = true;
            $retorno["erros"][] = "contrato_vazio";
        }

        return $retorno;
    }

    public function criarContratosPendentes($idsindicato, $idcurso)
    {
        $retorno['sucesso'] = true;

        $this->sql = 'SELECT
                c.idcontrato
            FROM
                contratos c
            WHERE
                c.ativo = "S" AND
                c.gerar_proximo_acesso = "S" AND
                c.gerar_aluno = "S" AND
                (
                    SELECT
                        COUNT(mcg.idmatricula_contrato)
                    FROM
                        matriculas_contratos_gerados mcg
                    WHERE
                        mcg.idmatricula = ' . $this->id . ' AND
                        mcg.idcontrato = c.idcontrato AND
                        mcg.ativo = "S"
                ) = 0 AND
                (
                    (
                        SELECT
                            COUNT(cs.idcontrato_sindicato)
                        FROM
                            contratos_sindicatos cs
                        WHERE
                            cs.idcontrato = c.idcontrato AND
                            cs.ativo = "S"
                    ) = 0  OR
                    (
                        SELECT
                            COUNT(cs2.idcontrato_sindicato)
                        FROM
                            contratos_sindicatos cs2
                        WHERE
                            cs2.idcontrato = c.idcontrato AND
                            cs2.idsindicato = ' . (int) $idsindicato . ' AND
                            cs2.ativo = "S"
                    ) > 0
                ) AND
                (
                    (
                        SELECT
                            COUNT(cc.idcontrato_curso)
                        FROM
                            contratos_cursos cc
                        WHERE
                            cc.idcontrato = c.idcontrato AND
                            cc.ativo = "S"
                    ) = 0  OR
                    (
                        SELECT
                            COUNT(cc2.idcontrato_curso)
                        FROM
                            contratos_cursos cc2
                        WHERE
                            cc2.idcontrato = c.idcontrato AND
                            cc2.idcurso = ' . (int) $idcurso . ' AND
                            cc2.ativo = "S"
                    ) > 0
                )';

        $this->ordem_campo = 'idcontrato';
        $this->ordem = 'DESC';
        $this->limite = -1;
        $contratos = $this->retornarLinhas();

        foreach ($contratos as $key => $contrato) {
            $this->post['idcontrato'] = $contrato['idcontrato'];
            $gerar = $this->gerarContratoPendente();

            if (! empty($gerar['erro'])) {
                $retorno['sucesso'] = false;
                $retorno['erro'] = true;
                $retorno['erros']['erro_gerar_contrato'] = 'erro_gerar_contrato';
            }
        }

        return $retorno;
    }

    public function getNomeProfessor($idMatricula,$idOferta,$idDisciplina,$idCurso,$idEscola,$idAva){

        $this->sql = "SELECT nome FROM professores AS p ";
        $this->sql.= "INNER JOIN professores_avas AS pa ON ( p.idprofessor = pa.idprofessor AND pa.ativo = 'S' )";
        $this->sql.= "INNER JOIN professores_disciplinas AS pd ON ( p.idprofessor = pd.idprofessor AND pd.ativo = 'S' )";
        $this->sql.= "INNER JOIN professores_cursos AS pc ON ( p.idprofessor = pc.idprofessor AND pc.ativo = 'S' )";
        $this->sql.= "INNER JOIN professores_ofertas AS po ON ( p.idprofessor = po.idprofessor AND po.ativo = 'S' ) ";
        $this->sql.= "INNER JOIN matriculas AS m ON po.idoferta = m.idoferta ";
        $this->sql.= "WHERE m.idmatricula = '".$idMatricula."' AND
                            m.idescola = '".$idEscola."' AND
                            pa.idava = '".$idAva."' AND
                            pd.iddisciplina = '".$idDisciplina."' AND
                            pc.idcurso = '".$idCurso."' AND
                            m.idoferta = '".$idOferta."' AND
                            p.ativo = 'S' AND p.ativo_login = 'S'
                    ";
        $linha = $this->retornarLinha($this->sql);
        return $linha['nome'];

    }

    public function retornarUltimoContratoPendente()
    {
        $sql = 'SELECT
                mcg.*,
                ct.nome AS tipo,
                c.nome AS contrato,
                m.data_cad AS data_matricula
            FROM
                matriculas_contratos_gerados mcg
                INNER JOIN matriculas m ON (m.idmatricula = mcg.idmatricula)
                INNER JOIN contratos c ON (mcg.idcontrato = c.idcontrato)
                INNER JOIN contratos_tipos ct ON (ct.idtipo = c.idtipo)
            WHERE
                mcg.idmatricula = ' . $this->id . ' AND
                (mcg.aceito = "N" OR mcg.aceito IS NULL) AND
                mcg.ativo = "S"
            ORDER BY
                mcg.idmatricula_contrato DESC';
        return $this->retornarLinha($sql);
    }

    public function aceitarContratoPendente($contrato)
    {
        $dadosdousuario = retornaSOBrowser();

        $sql = 'UPDATE
                    matriculas_contratos_gerados
                SET
                    aceito = "S" ,
                    aceito_data = NOW() ,
                    ip = "' . $dadosdousuario['ip'] . '",
                    navegador = "' . mysql_escape_string($dadosdousuario['navegador']) . '",
                    sistema_operacional = "' . mysql_escape_string($dadosdousuario['so']) . '",
                    navegador_versao = "' . mysql_escape_string($dadosdousuario['navegador_versao']) . '",
                    user_agent = "' . mysql_escape_string($dadosdousuario['user_agent']) . '"
                WHERE
                    idmatricula_contrato = ' . $contrato;
        return $this->executaSql($sql);
    }

    /**
     * Método para alterar valor da coluna contratos aceitos para sim da tabela matrículas.
     * @access public
     * @param int $idmatricula
     * @return void
     */

    public function alterarSituacaoContratosAceitos($idmatricula){
        try{
            if(!is_numeric($idmatricula)) {
                throw new InvalidArgumentException('Parâmetro idmatricula tem que ser do tipo inteiro.');
            } else {
                if($this->verificarSeTodosContratosForamAceitos($this->consultarContratos($idmatricula))) {
                    $this->sql = "UPDATE matriculas SET contratos_aceitos = 'S' WHERE idmatricula = {$idmatricula}";
                    if (!mysql_query($this->sql)) {
                        throw new Exception(mysql_error());
                    }
                }
            }
        }catch (InvalidArgumentException $i){
            echo "Ops! ocorreu um erro: {$i->getMessage()}";
        }catch (Exception $e) {
            die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => $e->getMessage())));
        }
    }

    /**
     * Método para retornar todos os contratos da matrículas
     * @access private
     * @param int $idmatricula
     * @return array
     */
    private function consultarContratos($idmatricula)
    {
        try {
            if (!is_numeric($idmatricula)) {
                throw new InvalidArgumentException('Parâmetro idmatricula tem que ser do tipo inteiro.');
            } else {
                $this->sql = "SELECT aceito, aceito_data FROM matriculas_contratos_gerados WHERE idmatricula = {$idmatricula}";
                $contratos_gerados = $this->retornarLinhas();
                $this->sql = "SELECT assinado, aceito_aluno, cancelado FROM matriculas_contratos WHERE idmatricula = {$idmatricula}";
                $contratos = $this->retornarLinhas();

                return array(
                    "idmatricula" => $idmatricula,
                    "contratos_gerados" => $contratos_gerados,
                    "contratos" => $contratos,
                );
            }
        }catch (InvalidArgumentException $i) {
            echo "Ops! ocorreu um erro: {$i->getMessage()}";
        }
    }

    /**
     * Método para verificar se todos contratos da matrícula foi aceito.
     * @access private
     * @param array $contratos
     * @return boolean
     */

    private function verificarSeTodosContratosForamAceitos($contratos)
    {
        try {
            if (!is_array($contratos)) {
                throw new InvalidArgumentException('Parâmetro contratos tem que ser do tipo array.');
            } else {
                if (empty($contratos['contratos_gerados']) && empty($contratos['contratos'])) {
                    return false;
                } else {
                    foreach ($contratos['contratos_gerados']as $contrato_gerado) {
                        if ($contrato_gerado['aceito'] == 'N') {
                            return false;
                        }
                    }
                    foreach ($contratos['contratos'] as $contrato)
                    {
                        if ($contrato['cancelado'])
                        {
                            continue;
                        } else if(is_null($contrato['assinado']) || $contrato['aceito_aluno'] == 'N')
                        {
                            return false;
                        }

                    }
                }
                return true;
            }
        }catch (InvalidArgumentException $i) {
            echo "Ops! ocorreu um erro: {$i->getMessage()}";
        }
    }

    public function retornaDataEmCurso($idmatricula)
    {
        $sql = 'SELECT
                mh.data_cad
            FROM
                matriculas_historicos mh
                INNER JOIN matriculas_workflow mw ON (mw.idsituacao = mh.para)
            WHERE
                mh.idmatricula = ' . $idmatricula . ' AND
                mh.tipo = "situacao" AND
                mw.ativa = "S"
            ORDER BY mh.data_cad ASC
            LIMIT 1';
        return $this->retornarLinha($sql);
    }

    public function retornaDadosEscola()
    {
        $sql = 'SELECT e.*
                FROM matriculas m
                INNER JOIN escolas e ON (m.idescola = e.idescola)
                WHERE m.idmatricula = ' . $this->id;

        return $this->retornarLinha($sql);
    }

    public function atualizaPrimeiroAcesso()
    {
        $sql = 'UPDATE
                    matriculas
                SET
                    data_primeiro_acesso = NOW()
                WHERE
                    idmatricula = ' . $this->id;

        $this->executaSql($sql);

        $sql = "INSERT
                    matriculas_historicos
                SET
                    idmatricula = '" . $this->id . "',
                    data_cad = now(),
                    tipo = 'data_primeiro_acesso',
                    acao = 'cadastrou',
                    idpessoa = '" . $this->idpessoa . "',
                    para = '" . date('Y-m-d') . "'";

        return $this->executaSql($sql);
    }

    public function retornaIdAvas()
    {
        $avas = $this->retornarAvas();
        foreach ($avas as $ava) {
            $idAvas[] = $ava['idava'];
        }
        return $idAvas;
    }

    public function retornarDisciplinasCurriculoOrdem()
    {
        $retorno = [];
        $curriculo = $this->retornarCurriculo();

        if (!$curriculo['idcurriculo']) {
            return false;
        }

        $sql = "SELECT
                cbd.ordem,
                cbd.iddisciplina
            FROM
                curriculos_blocos cb
            INNER JOIN
                curriculos_blocos_disciplinas cbd ON (
                    cb.idbloco = cbd.idbloco
                    AND cbd.ativo = 'S'
                )
            WHERE
                cb.idcurriculo = {$curriculo['idcurriculo']}
            ORDER BY
                cbd.ordem";
        $res = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($res)) {
            $retorno[$linha['iddisciplina']] = $linha['ordem'];
        }
        return $retorno;
    }

    public function retornarMatriculaPorNomeAluno($nomeAluno)
    {
        $nomeAluno = mysql_real_escape_string($nomeAluno);

        $sql = "SELECT
                m.idmatricula as 'key',
                CONCAT(p.nome, ' - ', ' Matricula: ' , m.idmatricula) as 'value'
            FROM
                matriculas m
            INNER JOIN pessoas p ON p.idpessoa = m.idpessoa AND p.ativo = 'S'
            WHERE
                m.ativo = 'S'
                AND lower(p.nome) LIKE lower('{$nomeAluno}%')
            ORDER BY
                p.nome
        ";

        return $this->retornarLinhasArray($sql);
    }

    public function retornarMatriculaPorCpfAluno($cpfAluno)
    {
        $cpfAluno = mysql_real_escape_string($cpfAluno);

        $sql = "SELECT
                m.idmatricula as 'key',
                CONCAT(p.documento, ' - ', p.nome, ' - ', ' Matricula: ' , m.idmatricula) as 'value'
            FROM
                matriculas m
            INNER JOIN pessoas p ON p.idpessoa = m.idpessoa AND p.ativo = 'S'
            WHERE
                m.ativo = 'S'
                AND p.documento LIKE '{$cpfAluno}%'
            ORDER BY
                p.nome
        ";

        return $this->retornarLinhasArray($sql);
    }

    public function retornarHistoricosLinhas() {
        $this->sql = "SELECT * FROM matriculas_historicos WHERE idmatricula = " . $this->id;
        $this->limite = -1;
        $this->ordem = "desc";
        $this->ordem_campo = "idhistorico";
        return $this->retornarLinhas();
    }

    public function retornarSimuladosRealizadosPorAva($idava){
        $this->sql = '
            SELECT '.$this->campos.'
            FROM matriculas_rotas_aprendizagem_objetos
            WHERE
                idsimulado IS NOT NULL AND
                idmatricula = '.$this->id.' AND
                idava = '.(int)$idava;
        $this->ordem = "desc";
        $this->ordem_campo = "data_cad";
        return $this->retornarLinhas();
    }

    /**
     * Método para retornar qual o curso que pode ser acessado caso o curso não seja permitido acessos simultâneos caso
     * a pessoa tenha mais de dois cursos cadastrados que não seja possível acessar ambos simultaneamente.
     * @param $idPessoa
     * @return array|mixed
     * */
    public function retornarAcessoCursoNaoSimultaneo($idPessoa)
    {
        try {
            if(is_numeric($idPessoa))
            {
                $sql = "SELECT m.idmatricula, m.data_cad, c.nome, c.acesso_simultaneo, c.idcurso
                        FROM matriculas m
                        INNER JOIN cursos c ON m.idcurso = c.idcurso
                        INNER JOIN matriculas_workflow mw ON m.idsituacao = mw.idsituacao
                        WHERE m.idpessoa = $idPessoa
                        AND c.acesso_simultaneo = 'N'
                        AND mw.ativa = 'S'";
                $cursos = $this->retornarLinhasArray($sql);

                if(empty($cursos) || count($cursos) === 1)
                {
                    return [];
                } else {
                    if(!empty($this->id))
                    {
                        $idMatriculaAtual = $this->id; // Armazenar valor do atributo ID para não ocorrer problemas em outros métodos.
                    }

                    $this->id = $cursos[0]['idmatricula'];
                    $acessoCurso = $cursos[0];
                    $acessoCurso['porcentagem'] = $this->porcentagemCursoAtual((int) $cursos[0]['idmatricula']);

                    foreach ($cursos as $curso)
                    {
                        $curso['porcentagem'] = $this->porcentagemCursoAtual((int) $curso['idmatricula']);
                        $this->id = $curso['idmatricula'];

                        if($acessoCurso['porcentagem'] < $curso['porcentagem'])
                        {
                            $acessoCurso = $curso;
                        }

                        if($acessoCurso['porcentagem'] == $curso['porcentagem'])
                        {
                            if($acessoCurso['data_cad'] > $curso['data_cad'])
                            {
                                $acessoCurso = $curso;
                            }
                        }
                    }

                    if(!empty($idMatriculaAtual))
                    {
                        $this->id = $idMatriculaAtual; // Receber valor ID armazenado para não ocorrer problemas em outros métodos.
                    }

                    return $acessoCurso;
                }
            } else {
                throw new Exception('Parâmetro tem que ser do tipo númerico.');
            }
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * Método para retornar o valor do campo acesso_simultâneo do curso que a matrícula está vinculada.
     * @param $idMatricula
     * @param $idCurso
     * @return mixed
     */
    public function retornarAcessoCursoSimultaneo ($idMatricula, $idCurso)
    {
        try {
            if(is_numeric($idMatricula) && is_numeric($idCurso))
            {
                $sql = "SELECT c.acesso_simultaneo
                        FROM cursos c
                        INNER JOIN matriculas m ON m.idcurso = c.idcurso
                        WHERE m.idmatricula = $idMatricula
                        AND m.idcurso = $idCurso";

                return $this->retornarLinha($sql)['acesso_simultaneo'];

            } else {
                throw new Exception('Parâmetro tem que ser do tipo númerico.');
            }

        } catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
}
