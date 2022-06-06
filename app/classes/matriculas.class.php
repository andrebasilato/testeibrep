<?php
require_once 'detran.class.php';

/**
 * `Matriculas`
 *
 * @author     Gabriel Leite    <gabriel@alfamaweb.com.br>
 * @author     Tomaz Novaes     <tomaz@alfamaweb.com.br>
 * @author     Daiane Azevedo   <daianea@alfamaweb.com.br>
 * @author     Henrique Feitosa <henriquef@alfamaweb.com.br>
 * @author     Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 * @author     Junior Lisboa <josej@alfamaweb.com.br>
 *
 * @package    Oráculo Construtor
 * @copyright  Copyright (c) 2014 Alfama Web (http://alfamaweb.com.br)
 * @license    Proprietary AlfamaWeb
 * @version    $Id$
 */


class Matriculas extends Core
{
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

    /**
     * @var integer Id do vendedor
     */
    public $matricula;

    /**
     * @var boolean Irar incluir inner joins das tabelas contratos
     */
    public $incluirContratos = false;

    /**
     * @var boolean Irar incluir inner joins das tabelas ofertas
     */
    public $incluirOfertas = false;

    /**
     * @var string Irar incluir no where a clausa in para as matriculas inseridas na variavel
     */
    public $matriculasIn = null;

    /**
     * @var boolean Irar incluir inner joins das tabelas MatriculasWorkflow
     */
    public $incluirWorkflow = false;

    /**
     * @var boolean Irar incluir inner joins das tabelas Pessoas
     */
    public $incluirPessoas = false;

    /**
     * @var boolean Irar incluir inner joins das tabelas vendedores
     */
    public $incluirVendedores = false;

    /**
     * @var boolean Irar incluir inner joins das tabelas escolas
     */
    public $incluirEscolas = false;

    /**
     * @var boolean Irar incluir inner joins das tabelas matriculas contas
     */
    public $conta = false;

    /**
     * @var string Irar servir para aggregar valores ao where das querys
     */
    public $where = '';

    public function listarTodas()
    {
        $this->sql = "SELECT
                   {$this->campos}
                 FROM
                   matriculas m";
        if ($this->incluirWorkflow) {
            $this->sql .= " INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)";
        }
        if ($this->incluirPessoas) {
            $this->sql .= " INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)";
        }
        if ($this->incluirVendedores) {
            $this->sql .= " LEFT OUTER JOIN vendedores v ON (m.idvendedor = v.idvendedor)";
        }
        if ($this->incluirEscolas || $this->idescola) {
            $this->sql .= " LEFT JOIN escolas e ON (e.idescola = m.idescola)";
        }
        if ($this->incluirOfertas) {
            $this->sql .= " INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN ofertas_turmas ot ON (m.idturma = ot.idturma) ";
        }
        if ($this->incluirContratos) {
            $this->sql .= " LEFT JOIN matriculas_contratos_gerados cg on (m.idmatricula = cg.idmatricula and cg.aceito = 'N')
            LEFT JOIN matriculas_contratos mc on (m.idmatricula = mc.idmatricula and mc.aceito_aluno = 'N') ";
        }

        if ($this->mapa_alcance) {
            $this->sql .= " INNER JOIN cidades ci ON (p.idcidade = ci.idcidade)
                            INNER JOIN estados es ON (p.idestado = es.idestado) ";
        }

        if ($this->conta) {
            $this->sql .= " INNER JOIN contas_matriculas cm ON (m.idmatricula = cm.idMatricula)";
        }
        if ($this->where != '')
            $this->sql .= " where {$this->where} and
                            m.ativo = 'S'";
        else
            $this->sql .= " where m.ativo = 'S'";

        if ($this->conta) {
            $this->sql .= " AND cm.idconta = " . $this->conta;
        }
        if ($this->matriculasIn !== null) {
            $this->sql .= " AND m.idmatricula IN ({$this->matriculasIn})";
        }

        if ($this->idescola) {
            $this->sql .= ' AND e.idescola = ' . $this->idescola;
        }

        if ($this->naotraz) {
            $this->sql .= " AND (m.idmatricula <>  {$this->naotraz} and (m.combo_matricula <> '{$this->naotraz}' or m.combo_matricula is null)) ";
        }

        if ($_SESSION["adm_gestor_sindicato"] <> "S" && $this->mapa_alcance)
            $this->sql .= " and m.idsindicato in (" . $_SESSION["adm_sindicatos"] . ") ";
//        if ($_SESSION["adm_gestor_cfc"] != "S")
//            $this->sql .= "and m.idescola in (" . $_SESSION["adm_cfcs"] . ")";
        if ($this->idvendedor)
            $this->sql .= " and m.idvendedor = '" . $this->idvendedor . "' ";
        if (is_array($_GET["q"])) {
            foreach ($_GET["q"] as $campo => $valor) {
                if ($campo == '1|cg.aceito' && $valor == 'N') {
                    $this->sql .= " and cg.aceito = '" . $valor . "' ";
                    $this->sql .= " or mc.aceito_aluno = '" . $valor . "' ";
                    break;
                }
            }
            foreach ($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|", $campo);
                $valor = str_replace("'", "", $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === "0") and $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        if ($campo[1] == 'cg.aceito' && $valor == 'N') {
                            continue;
                        } else if ($campo[1] != 'cg.aceito' || $valor != 'S') {
                            $this->sql .= " and " . $campo[1] . " = '" . $valor . "' ";
                        } else {
                            $this->having = ' having count(cg.aceito) = 0 and count(mc.aceito_aluno) = 0';
                        }
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }
//        echo "<pre>";
//        var_dump($_SESSION); exit;
        $this->mantem_groupby = false;
        $matriculas = $this->retornarLinhas();

        if (!$this->mapa_alcance) {
            foreach ($matriculas as $ind => $matricula) {
                $this->sql = "select * from sindicatos where idsindicato='" . $matricula["idsindicato"] . "'";
                $matriculas[$ind]["sindicato"] = $this->retornarLinha($this->sql);
                $this->sql = "select * from escolas where idescola='" . $matricula["idescola"] . "'";
                $matriculas[$ind]["escola"] = $this->retornarLinha($this->sql);
                $this->sql = "select * from cursos where idcurso='" . $matricula["idcurso"] . "'";
                $matriculas[$ind]["curso"] = $this->retornarLinha($this->sql);
            }
        }
        return $matriculas;
    }

    /**
     * Retorna os dadaos de uma matrícula de acordo com o `idmatricula` passado
     * para o método
     *
     * @since 3.0
     */
    public function getMatricula($idmatricula)
    {
        $this->id = $idmatricula;
        return $this->Retornar();
    }

    public function retornar()
    {
        if (!is_numeric($this->id)) {
            throw new InvalidArgumentException('O parametro `ID` precisa ser um valor numérico.');
        }

        $this->sql = "SELECT * from matriculas where idmatricula = " . $this->id;

        if ($this->idusuario && $_SESSION["adm_gestor_sindicato"] <> "S") {
            $this->sql .= " and idsindicato in (" . $_SESSION["adm_sindicatos"] . ")";
        }

        if ($this->idvendedor) {
            $this->sql .= " and idvendedor = '" . $this->idvendedor . "' ";
        }

        if ($this->idescola) {
            $this->sql .= ' AND idescola = ' . $this->idescola;
        }

        $this->sql .= " and ativo = 'S'";

        $this->matricula = $this->retornarLinha($this->sql);

        if (!$this->matricula) {
            return null;
        }

        if ($this->matricula["idmotivo_cancelamento"]) {
            $this->sql = "SELECT * FROM motivos_cancelamento where idmotivo = " . $this->matricula["idmotivo_cancelamento"];
            $this->matricula["motivo_cancelamento"] = $this->retornarLinha($this->sql);
        }

        if ($this->matricula["idmotivo_inativo"]) {
            $this->sql = "SELECT * FROM motivos_inatividade where idmotivo = " . $this->matricula["idmotivo_inativo"];
            $this->matricula["motivo_inativo"] = $this->retornarLinha($this->sql);
        }

        if ($this->matricula["idbandeira"]) {
            $this->sql = "SELECT * FROM bandeiras_cartoes where idbandeira = " . $this->matricula["idbandeira"];
            $this->matricula["bandeira"] = $this->retornarLinha($this->sql);
        }

        if ($this->matricula["idoferta"]) {
            $this->sql = "SELECT possui_financeiro
                          FROM ofertas_cursos
                          WHERE ativo = 'S' AND
                                idoferta ='" . $this->matricula["idoferta"] . "' AND
                                idcurso ='" . $this->matricula["idcurso"] . "'";
            $retorno = $this->retornarLinha($this->sql);
            $this->matricula["possui_financeiro"] = $retorno["possui_financeiro"];
        }

        $this->retorno = $this->matricula;
        return $this->retorno;
    }

    public function retornarSituacao($idsituacao)
    {
        $situacao = array();
        $this->sql = "SELECT
                        mwa.idacao, mwa.idopcao
                    FROM
                        matriculas_workflow_acoes mwa
                    WHERE
                        mwa.idsituacao = '" . $idsituacao . "'
                    AND
                        mwa.ativo = 'S'";

        $this->ordem = "asc";
        $this->ordem_campo = "mwa.idacao";
        $this->limite = -1;
        $acoes = $this->retornarLinhas();
        foreach ($acoes as $acao) {
            foreach ($GLOBALS["workflow_parametros_matriculas"] as $opcao) {
                if ($opcao["idopcao"] == $acao["idopcao"] && $opcao["tipo"] == "visualizacao") {
                    $situacao["visualizacoes"][$acao["idopcao"]] = $acao;
                }
            }
        }
        return $situacao;
    }

    public function retornarPessoa()
    {
        $this->sql = 'SELECT
                        p.*,
                        CONCAT_WS(" ", l.nome, p.endereco) AS endereco_logradouro,
                        e.nome as estado,
                        e.sigla AS uf,
                        c.nome as cidade
                      FROM
                        pessoas p
                        LEFT OUTER JOIN estados e on (p.idestado = e.idestado)
                        LEFT OUTER JOIN cidades c on (p.idcidade = c.idcidade)
                        LEFT OUTER JOIN logradouros l ON (l.idlogradouro = p.idlogradouro)
                      WHERE
                        idpessoa = ' . $this->matricula['idpessoa'];
        return $this->retornarLinha($this->sql);
    }

    public function RetornarOferta()
    {
        $this->sql = "SELECT * FROM ofertas where idoferta = '" . $this->matricula["idoferta"] . "'";
        return $this->retornarLinha($this->sql);
    }

    public function RetornarCurso()
    {
        $this->sql = "SELECT * FROM cursos where idcurso = '" . $this->matricula["idcurso"] . "'";
        return $this->retornarLinha($this->sql);
    }

    public function retornarOfertaCurso()
    {
        $this->sql = "SELECT * FROM ofertas_cursos
                      WHERE idcurso = '" . $this->matricula["idcurso"] . "' AND
                            idoferta = '" . $this->matricula["idoferta"] . "'";
        return $this->retornarLinha($this->sql);
    }

    public function RetornarEscola()
    {
        $this->sql = "SELECT *, e.sigla AS uf  FROM escolas
        LEFT OUTER JOIN estados e on (escolas.idestado = e.idestado)
        where idescola = '" . $this->matricula["idescola"] . "'";
        return $this->retornarLinha($this->sql);
    }

    public function RetornarTurma()
    {
        $this->sql = "SELECT * FROM ofertas_turmas where idturma = '" . $this->matricula["idturma"] . "'";
        return $this->retornarLinha($this->sql);
    }

    public function RetornarMantenedora()
    {
        $this->sql = "SELECT * FROM mantenedoras where idmantenedora = '" . $this->matricula["idmantenedora"] . "'";
        return $this->retornarLinha($this->sql);
    }

    public function RetornarSindicato()
    {
        $this->sql = "SELECT * FROM sindicatos where idsindicato = '" . $this->matricula["idsindicato"] . "'";
        return $this->retornarLinha($this->sql);
    }

    public function RetornarCursoSindicato()
    {
        $this->sql = 'SELECT * FROM
                            cursos_sindicatos
                    WHERE
                        idsindicato = ' . $this->matricula["idsindicato"] . '
                        AND idcurso = ' . $this->matricula["idcurso"];
        return $this->retornarLinha($this->sql);
    }

    public function RetornarCurriculo()
    {
        $this->sql = "select
                        c.*
                    from
                        curriculos c
                        inner join ofertas_cursos_escolas ocp on (c.idcurriculo = ocp.idcurriculo)
                    where ocp.ativo = 'S'";

        if ($this->matricula["idoferta"]) {
            $this->sql .= " AND ocp.idoferta = '" . $this->matricula["idoferta"] . "'";
        }

        if ($this->matricula["idcurso"]) {
            $this->sql .= " AND ocp.idcurso = '" . $this->matricula["idcurso"] . "'";
        }

        if ($this->matricula["idescola"]) {
            $this->sql .= " AND ocp.idescola = '" . $this->matricula["idescola"] . "'";
        }

        return $this->retornarLinha($this->sql);
    }

    public function RetornarDisciplinas($media_curriculo)
    {
        $disciplinas = array();
        $this->sql = "SELECT
                            d.*,
                            cb.nome as bloco,
                            cbd.idbloco_disciplina,
                            cbd.idformula,
                            cbd.ignorar_historico,
                            cbd.contabilizar_media,
                            cbd.exibir_aptidao,
                            cbd.nota_conceito,
                            oca.idava
                        FROM
                            disciplinas d
                            INNER JOIN curriculos_blocos_disciplinas cbd on (d.iddisciplina = cbd.iddisciplina and cbd.ativo = 'S')
                            INNER JOIN curriculos_blocos cb on (cbd.idbloco = cb.idbloco and cb.ativo = 'S')
                            INNER JOIN ofertas_cursos_escolas ocp on (cb.idcurriculo = ocp.idcurriculo)
                            INNER JOIN matriculas m on (ocp.idescola = m.idescola and ocp.idoferta = m.idoferta and ocp.idcurso = m.idcurso)
                            LEFT OUTER JOIN ofertas_curriculos_avas oca on (oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = m.idoferta and oca.ativo = 'S')
                        WHERE
                            m.idmatricula = " . $this->id . "
                        GROUP BY
                            d.iddisciplina ";
        $this->ordem = "asc";
        $this->ordem_campo = "cb.ordem, cbd.ordem, d.nome";
        $this->limite = -1;
        $disciplinas = $this->retornarLinhas();

        foreach ($disciplinas as $ind => $disciplina) {
            $disciplinas[$ind]["andamento"] = $this->retornarAndamentoDisciplina($disciplina["idbloco_disciplina"]);
            $disciplinas[$ind]["notas"] = $this->retornarNotasDisciplina($this->id, $disciplina["iddisciplina"]);
            $disciplinas[$ind]["exercicios"] = $this->retornarExerciciosDisciplina($this->id, $disciplina["iddisciplina"], $disciplina["idava"]);
            $disciplinas[$ind]["situacao"] = $this->retornarSituacaoDisciplina($this->id, $disciplina, $media_curriculo);
        }
        return $disciplinas;
    }

    /**
     * Método para alterar valor da coluna contratos aceitos da tabela matriculas para sim, caso seja necessário para não, tem que alterar o valor da variável atualizar.
     * @access public
     * @param int $idmatricula
     * @param boolean $atualizar
     * @return void
     */

    public function alterarSituacaoContratosAceitos($idmatricula, $atualizar = false)
    {
        try {
            if (!is_numeric($idmatricula)) {
                throw new InvalidArgumentException('Parâmetro idmatricula tem que ser do tipo inteiro.');
            } else {
                if ($this->verificarSeTodosContratosForamAceitos($this->consultarContratos($idmatricula))) {
                    $this->sql = "UPDATE matriculas SET contratos_aceitos = 'S' WHERE idmatricula = {$idmatricula}";
                    if (!mysql_query($this->sql)) {
                        throw new Exception(mysql_error());
                    }
                } else {
                    if ($atualizar) {
                        $this->sql = "UPDATE matriculas SET contratos_aceitos = 'N' WHERE idmatricula = {$idmatricula}";
                        if (!mysql_query($this->sql)) {
                            throw new Exception(mysql_error());
                        }
                    }
                }
            }
        } catch (InvalidArgumentException $i) {
            echo "Ops! ocorreu um erro: {$i->getMessage()}";
        } catch (Exception $e) {
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
                if ($this->ordem_campo) $this->ordem_campo = null;
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
        } catch (InvalidArgumentException $i) {
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
                    foreach ($contratos['contratos_gerados'] as $contrato_gerado) {
                        if ($contrato_gerado['aceito'] == 'N') {
                            return false;
                        }
                    }
                    foreach ($contratos['contratos'] as $contrato) {
                        if ($contrato['cancelado']) {
                            continue;
                        } else if (is_null($contrato['assinado']) || $contrato['aceito_aluno'] == 'N') {
                            return false;
                        }

                    }
                }
                return true;
            }
        } catch (InvalidArgumentException $i) {
            echo "Ops! ocorreu um erro: {$i->getMessage()}";
        }
    }

    public function getNomeProfessor($idMatricula, $idOferta, $idDisciplina, $idCurso, $idEscola, $idAva)
    {

        $this->sql = "SELECT nome FROM professores AS p ";
        $this->sql .= "INNER JOIN professores_avas AS pa ON ( p.idprofessor = pa.idprofessor AND pa.ativo = 'S' AND pa.idava = '" . $idAva . "' )";
        $this->sql .= "INNER JOIN professores_disciplinas AS pd ON ( p.idprofessor = pd.idprofessor AND pd.ativo = 'S' AND pd.iddisciplina = '" . $idDisciplina . "' )";
        $this->sql .= "INNER JOIN professores_cursos AS pc ON ( p.idprofessor = pc.idprofessor AND pc.ativo = 'S' AND pc.idcurso = '" . $idCurso . "' )";
        $this->sql .= "INNER JOIN professores_ofertas AS po ON ( p.idprofessor = po.idprofessor AND po.ativo = 'S' AND po.idoferta = '" . $idOferta . "' ) ";
        $this->sql .= "INNER JOIN matriculas AS m ON po.idoferta = m.idoferta ";
        $this->sql .= "WHERE m.idmatricula = '" . $idMatricula . "' AND
                            m.idescola = '" . $idEscola . "' AND
                            p.ativo = 'S' AND p.ativo_login = 'S'
                    ";
        $linha = $this->retornarLinha($this->sql);
        return $linha['nome'];

    }

    public function RetornarVendedor()
    {
        if ($this->matricula["idvendedor"]) {
            $this->sql = "SELECT * FROM vendedores where idvendedor = " . $this->matricula["idvendedor"];
            return $this->retornarLinha($this->sql);
        }
    }

    public function RetornarContas($contrato = false)
    {
        $eventoFinanceiroMensalidade = $this->retornarEventoMensalidade();
        $contasArray = array();

        $this->sql = 'SELECT
                        c.*,
                        ef.nome as evento,
                        bc.nome as bandeira_cartao,
                        b.nome as banco,
                        cw.nome as situacao,
                        cw.cancelada as situacao_cancelada,
                        cw.renegociada as situacao_renegociada,
                        cw.transferida as situacao_transferida,
                        cw.pago as situacao_paga,
                        cw.pagseguro AS situacao_pagseguro,
                        cw.fastconnect AS situacao_fastconnect,
                        cw.cor_nome,
                        cw.cor_bg,
                        e.fastconnect_client_code,
                        e.fastconnect_client_key,
                        e.pagseguro_email,
                        e.pagseguro_token,
                        e.idescola,
                        (
                            SELECT
                                COUNT(f.idfastconnect)
                            FROM
                                fastconnect f
                            WHERE
                                f.idconta = c.idconta AND
                                f.idsituacao IN (0,1,2,4) AND
                                f.tipo <> "boleto" AND
                                f.ativo = "S"
                        ) AS totalPagamentosAbertosFastConnect
                    FROM
                        contas c
                        INNER JOIN contas_workflow cw on (c.idsituacao = cw.idsituacao)
                        INNER JOIN eventos_financeiros ef on (c.idevento = ef.idevento)
                        left outer join bandeiras_cartoes bc on (c.idbandeira = bc.idbandeira)
                        left outer join bancos b on (c.idbanco = b.idbanco)
                        LEFT OUTER JOIN matriculas m ON (m.idmatricula = c.idmatricula)
                        LEFT OUTER JOIN escolas e ON (e.idescola = m.idescola)
                    WHERE
                        c.idmatricula = ' . $this->id . ' and
                        c.ativo = "S"';

        if ($contrato) {
            $this->sql .= " AND cw.cancelada != 'S'";
        }

        $this->sql2 = 'SELECT
                            c.*,
                            ef.nome as evento,
                            bc.nome as bandeira_cartao,
                            b.nome as banco,
                            cw.nome as situacao,
                            cw.cancelada as situacao_cancelada,
                            cw.renegociada as situacao_renegociada,
                            cw.transferida as situacao_transferida,
                            cw.pago as situacao_paga,
                            cw.pagseguro AS situacao_pagseguro,
                            cw.fastconnect AS situacao_fastconnect,
                            cw.cor_nome,
                            cw.cor_bg,
                            e.pagseguro_email,
                            e.pagseguro_token,
                            e.fastconnect_client_code,
                            e.fastconnect_client_key,
                            e.idescola,
                            (
                                SELECT
                                    COUNT(f.idfastconnect)
                                FROM
                                    fastconnect f
                                WHERE
                                    f.idconta = c.idconta AND
                                    f.idsituacao IN (0,1,2,4) AND
                                    f.tipo <> "boleto" AND
                                    f.ativo = "S"
                            ) AS totalPagamentosAbertosFastConnect
                        FROM
                            contas c
                            INNER JOIN contas_workflow cw on (c.idsituacao = cw.idsituacao)
                            INNER JOIN eventos_financeiros ef on (c.idevento = ef.idevento)
                            INNER JOIN pagamentos_compartilhados_matriculas pcm on (c.idpagamento_compartilhado = pcm.idpagamento and pcm.ativo = "S")
                            LEFT OUTER JOIN bandeiras_cartoes bc on (c.idbandeira = bc.idbandeira)
                            LEFT OUTER JOIN bancos b on (c.idbanco = b.idbanco)
                            LEFT OUTER JOIN matriculas m ON (m.idmatricula = c.idmatricula)
                            LEFT OUTER JOIN escolas e ON (e.idescola = m.idescola)
                        WHERE
                            pcm.idmatricula = ' . $this->id . ' and
                            c.ativo = "S"';

        if ($contrato) {
            $this->sql2 .= " AND cw.cancelada != 'S'";
        }

        $this->sql = $this->sql . ' UNION ' . $this->sql2;

        $this->ordem = 'ASC';
        $this->ordem_campo = 'data_vencimento';
        $this->limite = -1;
        $contas = $this->retornarLinhas();

        $this->matricula['total_mensalidades'] = 0;
        foreach ($contas as $ind => $conta) {
            $conta['totalPagamentosAbertos'] = $conta['totalPagamentosAbertosFastConnect'];
            $conta['valor_parcela'] = $conta['valor'];

            if ($conta['idpagamento_compartilhado']) {
                $this->sql = "SELECT count(1) as total_contas_compartilhadas FROM contas WHERE idpagamento_compartilhado = " . $conta['idpagamento_compartilhado'] . " AND ativo = 'S'";
                $totalContasCompartilhadas = $this->retornarLinha($this->sql);
                $conta['total_contas_compartilhadas'] = $totalContasCompartilhadas['total_contas_compartilhadas'];

                $this->sql = "SELECT valor FROM pagamentos_compartilhados_matriculas WHERE idpagamento = " . $conta['idpagamento_compartilhado'] . " AND idmatricula = " . $this->id . " AND ativo = 'S'";
                $valorContasCompartilhadas = $this->retornarLinha($this->sql);
                $conta['valor_matricula'] = $valorContasCompartilhadas['valor'];

                $conta['valor_parcela'] = $conta['valor_matricula'] / $conta['total_contas_compartilhadas'];
            }

            if ($conta['idconta_transferida']) {
                $this->sql = "SELECT idmatricula as matricula_transferida FROM contas WHERE idconta = " . $conta['idconta_transferida'];
                $contasTransferida = $this->retornarLinha($this->sql);
                $conta["matricula_transferida"] = $contasTransferida['matricula_transferida'];
            }

            if ($conta['idevento'] == $eventoFinanceiroMensalidade['idevento']) {
                if ($conta['situacao_cancelada'] != 'S' && $conta['situacao_renegociada'] != 'S' && $conta['situacao_transferida'] != 'S') {
                    $this->matricula['total_mensalidades'] += $conta['valor_parcela'];
                }
            }

            if ($conta['forma_pagamento'] == 10) {
                //PagSeguro
                $sql = 'SELECT
                            p.idpagseguro,
                            p.status,
                            p.paymentLink,
                            p.paymentMethod_type
                        FROM
                            pagseguro p
                            INNER JOIN contas c ON (c.idconta = p.idconta)
                        WHERE
                            p.idconta = ' . $conta['idconta'] . ' AND
                            p.ativo = "S"
                        ORDER BY
                            p.idpagseguro DESC
                        LIMIT 1';
                $pagSeguro = $this->retornarLinha($sql);

                $conta['pagSeguro'] = $pagSeguro;
                $conta['pagSeguro']['pagseguro_email'] = $conta['pagseguro_email'];
                $conta['pagSeguro']['pagseguro_token'] = $conta['pagseguro_token'];
                $conta['pagSeguro']['idescola'] = $conta['idescola'];
            }

            if ($conta['forma_pagamento'] == 11) {
                //PagSeguro
                $sql = 'SELECT
                            f.idfastconnect,
                            f.idsituacao,
                            f.link_pagamento,
                            f.tipo
                        FROM
                            fastconnect f
                            INNER JOIN contas c ON (c.idconta = f.idconta)
                        WHERE
                            f.idconta = ' . $conta['idconta'] . ' AND
                            f.ativo = "S"
                        ORDER BY
                            f.idfastconnect DESC
                        LIMIT 1';
                $fastConnect = $this->retornarLinha($sql);

                $conta['fastConnect'] = $fastConnect;
                $conta['fastConnect']['fastconnect_client_code'] = $conta['fastconnect_client_code'];
                $conta['fastConnect']['fastconnect_client_key'] = $conta['fastconnect_client_key'];
                $conta['fastConnect']['idescola'] = $conta['idescola'];
            }

            if ($conta['situacao_pagseguro'] == 'S') {
                $sql = 'SELECT
                        *
                    FROM
                        matriculas_historicos
                    WHERE
                        idmatricula = "' . $conta['idmatricula'] . '" AND
                        id = ' . $conta['idconta'] . ' AND
                        para = ' . $conta['idsituacao'] . ' AND
                        tipo = "parcela_situacao" AND
                        acao = "modificou"
                    ORDER BY
                        idhistorico DESC
                    LIMIT 1';
                $historicoPagseguro = $this->retornarLinha($sql);

                $conta['historico_pagseguro'] = $historicoPagseguro;
            }

            if ($conta['situacao_fastconnect'] == 'S') {
                $sql = 'SELECT
                        *
                    FROM
                        matriculas_historicos
                    WHERE
                        idmatricula = "' . $conta['idmatricula'] . '" AND
                        id = ' . $conta['idconta'] . ' AND
                        para = ' . $conta['idsituacao'] . ' AND
                        tipo = "parcela_situacao" AND
                        acao = "modificou"
                    ORDER BY
                        idhistorico DESC
                    LIMIT 1';
                $historicoFastConnect = $this->retornarLinha($sql);

                $conta['historico_fastconnect'] = $historicoFastConnect;
            }

            $contasArray[$conta['idevento']][] = $conta;
        }

        $this->matricula['total_mensalidades'] = number_format($this->matricula['total_mensalidades'], 2, '.', '');
        return $contasArray;
    }

    public function RetornarDocumentos()
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

    public function RetornarContratos()
    {
        $this->sql = "SELECT
                            mc.*,
                            c.nome as contrato,
                            ct.nome as tipo
                          FROM
                            matriculas_contratos mc
                            left outer join contratos c on (mc.idcontrato = c.idcontrato)
                            INNER JOIN contratos_tipos ct on (mc.idtipo = ct.idtipo or c.idtipo = ct.idtipo)
                          where
                            mc.idmatricula = " . $this->id . " and
                            mc.ativo = 'S'";
        $this->ordem = "asc";
        $this->ordem_campo = "data_cad";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function RetornarDeclaracoes()
    {

        $this->sql = "SELECT
                            md.*,
                            d.nome as declaracao,
                            dt.nome as tipo
                          FROM
                            matriculas_declaracoes md
                            left outer join declaracoes d on (md.iddeclaracao = d.iddeclaracao)
                            INNER JOIN declaracoes_tipos dt on (md.idtipo = dt.idtipo or d.idtipo = dt.idtipo)
                          where
                            md.idmatricula = " . $this->id . " and
                            md.ativo = 'S'";
        $this->ordem = "asc";
        $this->ordem_campo = "data_cad";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function RetornarMensagens()
    {
        $this->sql = "SELECT idmensagem FROM matriculas_mensagens where idmatricula = " . $this->id . " order by data_cad desc limit 1";
        $ultimaMensagem = $this->retornarLinha($this->sql);
        $this->matricula["id_ultima_mensagem"] = $ultimaMensagem["idmensagem"];

        $this->sql = "SELECT
                            mm.*,
                            IF(mm.idusuario IS NOT NULL, ua.nome, e.nome_fantasia) AS usuario,
                            IF(mm.idusuario IS NOT NULL, ua.avatar_servidor, e.avatar_servidor) AS avatar_servidor,
                            IF(mm.idusuario IS NOT NULL, 'usuariosadm_avatar', 'escolas_avatar') AS pasta
                        FROM
                            matriculas_mensagens mm
                            left join usuarios_adm ua on (mm.idusuario = ua.idusuario)
                            left join escolas e on (e.idescola = mm.idescola)
                          where
                            mm.idmatricula = " . $this->id . " and
                            mm.ativo = 'S'";
        $this->ordem = "desc";
        $this->ordem_campo = "mm.data_cad";
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    public function retornarMensagensArquivos($idmensagem)
    {
        $this->sql = "SELECT * FROM matriculas_mensagens_arquivos
                      WHERE idmensagem = {$idmensagem} and ativo = 'S' ";
        $this->ordem = "desc";
        $this->ordem_campo = "data_cad";
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    public function retornarMensagensArquivo($idarquivo)
    {
        $this->sql = "SELECT * FROM matriculas_mensagens_arquivos
                      WHERE idarquivo = {$idarquivo} and ativo = 'S' ";
        $this->ordem = "desc";
        $this->ordem_campo = "data_cad";
        $this->limite = -1;

        return $this->retornarLinha($this->sql);
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
                    #$notas[$tipo_class[$tipo_peso[$nota['tipo_avaliacao']]]] = $disciplina[$tipo_peso[$nota['tipo_avaliacao']]];
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

    public function retornarOfertaCursoEscola($idoferta, $idcurso, $idescola)
    {
        $this->sql = 'SELECT * FROM
                        ofertas_cursos_escolas oc
                    WHERE
                        idcurso = ' . $idcurso . ' AND
                        idescola = ' . $idescola . ' AND
                        idoferta = ' . $idoferta . ' AND
                        ativo = "S" ';
        return $this->retornarLinha($this->sql);
    }

    public function comparaEscola($idNovaEscola, $idVelhaEscola)
    {
        $this->sql = 'SELECT * FROM
                        escolas esc
                    WHERE
                        idescola = ' . $idNovaEscola . '';
        $novaEscola = $this->retornarLinha($this->sql);

        $this->sql = 'SELECT * FROM
                        escolas esc
                    WHERE
                        idescola = ' . $idVelhaEscola . '';
        $velhaEscola = $this->retornarLinha($this->sql);
        return ($novaEscola['idestado'] == $velhaEscola['idestado']) ? false : true;
    }

    public function listarMatriculasPedido($idPedido)
    {
        $this->sql = 'SELECT idmatricula FROM matriculas WHERE idpedido = ' . $idPedido . ' AND ativo = "S"';

        $this->ordem_campo = 'valor_contrato';
        $this->ordem = 'DESC';
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    public function retornarSiglaEstado($idestado)
    {
        $sql = "select sigla FROM estados where idestado = '" . $idestado . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['sigla'];
    }

    public function cadastrar($idPedido = null)
    {
        $this->executaSql('BEGIN');

        if (!empty($idPedido)) {
            require_once '../classes/loja.pedidos.class.php';
            $lojaPedidoObj = new Loja_Pedidos;
            $pedido = $lojaPedidoObj->set('id', $idPedido)
                ->set('campos', 'idpedido, data_cad, idpessoa, idescola, valor_final')
                ->retornar();

            $existeMatriculaPedido = $this->listarMatriculasPedido($idPedido);
            unset($this->ordem_campo, $this->ordem);

            if (count($existeMatriculaPedido) > 0) {
                $retorno['erro'] = true;
                $retorno['mensagem'] = 'pedido_tem_matricula';
                return $retorno;
            }

            $this->post['financeiro']['valor_contrato'] = number_format($pedido['valor_final'], 2, ',', '.');
            $this->post['financeiro']['bolsa'] = 'N';
            $this->post['financeiro']['quantidade_parcelas'] = 1;
            $this->post['financeiro']['forma_pagamento'] = $GLOBALS['tipo_pagamento_loja'][$this->post['pagamento']['tipo_pagamento']];
        }

        $this->sql = "SELECT * FROM
                        matriculas_workflow
                    WHERE
                        ativo = 'S' AND
                        inicio = 'S'
                    ORDER BY
                        idsituacao DESC LIMIT 1";
        $situacaoInicio = $this->retornarLinha($this->sql);

        $this->post['financeiro']['valor_contrato'] = str_replace('.', '', $this->post['financeiro']['valor_contrato']);
        $this->post['financeiro']['valor_contrato'] = str_replace(',', '.', $this->post['financeiro']['valor_contrato']);

        $dataDiasParaAva = NULL;
        $data_matricula = ' now() ';
        $dataDiasParaAva = new DateTime();
        if ($this->post["financeiro"]["data_matricula"]) {
            $data_matricula = "'" . formataData($this->post["financeiro"]["data_matricula"], "en", 0) . "'";
            $dataDiasParaAva = new DateTime(formataData($this->post["financeiro"]["data_matricula"], "en", 0));
        }
        $oferta_curso_escola = $this->retornarOfertaCursoEscola($this->post['idoferta'], $this->post['idcurso'], $this->post['idescola']);
        if ($oferta_curso_escola['dias_para_ava']) {
            $dataDiasParaAva->modify('+ ' . $oferta_curso_escola['dias_para_ava'] . ' days');
        }
        $dataLimiteAva = NULL;
        if ($oferta_curso_escola['data_limite_ava']) {
            $dataLimiteAva = new DateTime($oferta_curso_escola['data_limite_ava']);
        }

        if ($dataDiasParaAva && $dataLimiteAva) {
            if ($dataDiasParaAva > $dataLimiteAva) {
                $data_limite_acesso_ava = $dataDiasParaAva->format('Y-m-d');
                $data_limite_acesso_ava = "'" . $data_limite_acesso_ava . "'";
            } else {
                $data_limite_acesso_ava = $dataLimiteAva->format('Y-m-d');
                $data_limite_acesso_ava = "'" . $data_limite_acesso_ava . "'";
            }
        } elseif ($dataDiasParaAva) {
            $data_limite_acesso_ava = "NULL";
        } else {
            $data_limite_acesso_ava = $dataLimiteAva->format('Y-m-d');
            $data_limite_acesso_ava = "'" . $data_limite_acesso_ava . "'";
        }

        $this->sql = "SELECT * FROM escolas WHERE idescola='" . $this->post["idescola"] . "' and ativo = 'S'";
        $escola = $this->retornarLinha($this->sql);

        if ($escola['acesso_bloqueado'] == 'S' && $this->url[0] == 'atendente') {
            $this->executaSql('ROLLBACK');

            $retorno['erro'] = true;
            $retorno['mensagem'] = 'acesso_bloqueado_cfc';
            return $retorno;
        }

        /* Se o estado não tem integração, o mesmo já vai como Liberado */
        $detran = new Detran();
        $estado = $this->retornarSiglaEstado($escola['idestado']);
        $curso_integrado = in_array((int)$this->post['idcurso'], array_keys($GLOBALS['detran_tipo_aula'][$estado]));
        $estado_integrado = $detran->obterSituacaoIntegracao((int)$escola['idestado']);
        $detranSituacao = 'LI';
        if ($estado_integrado && $curso_integrado)
            $detranSituacao = 'AL';

        $this->sql = "SELECT * FROM sindicatos WHERE idsindicato='" . $escola["idsindicato"] . "' and ativo = 'S'";
        $sindicato = $this->retornarLinha($this->sql);
        $this->sql = "SELECT * FROM mantenedoras WHERE idmantenedora='" . $sindicato["idmantenedora"] . "' and ativo = 'S'";
        $mantenedora = $this->retornarLinha($this->sql);


        $this->sql = "INSERT INTO
                        matriculas
                    SET
                        data_cad = now(),
                        data_matricula = " . $data_matricula . ",
                        data_prolongada = " . $data_limite_acesso_ava . ",
                        detran_situacao = '" . $detranSituacao . "',
                        idmantenedora = '" . $mantenedora["idmantenedora"] . "',
                        idsindicato = '" . $sindicato["idsindicato"] . "',
                        idpessoa = " . $this->post["idpessoa"] . ",
                        idoferta = " . $this->post["idoferta"] . ",
                        idcurso = " . $this->post["idcurso"] . ",
                        idescola = " . $this->post["idescola"] . ",
                        idturma = " . $this->post["idturma"] . ",
                        aprovado_comercial = 'N',
                        idsituacao = " . $situacaoInicio["idsituacao"] . ",
                        modulo = '" . $this->modulo . "',
                        bolsa = '" . $this->post["financeiro"]["bolsa"] . "',
                        limite_datavalid = " . $GLOBALS["config"]["datavalid"]["limite_tentativas"] . ",
                        observacao = '" . $this->post["financeiro"]["observacao"] . "'";

        if (!empty($pedido['idpedido'])) {
            $this->sql .= ', idpedido = ' . $pedido['idpedido'];
        }

        if ($this->post["financeiro"]["forma_pagamento"]) {
            $this->sql .= ", forma_pagamento = " . $this->post["financeiro"]["forma_pagamento"] . "";
            if ($this->post["financeiro"]["forma_pagamento"] == 2 || $this->post["financeiro"]["forma_pagamento"] == 3) {
                $this->sql .= ", idbandeira = " . $this->post["financeiro"]['idbandeira'] . " , autorizacao_cartao = '" . $this->post["financeiro"]['autorizacao_cartao'] . "' ";
            }
        }
        if ($this->post["financeiro"]["data_registro"]) {
            $this->sql .= ", data_registro = '" . formataData($this->post["financeiro"]["data_registro"], 'en', 0) . "'";
        } else {
            $this->sql .= ", data_registro = '" . date('Y-m-d') . "'";
        }

        if ($this->post["financeiro"]["bolsa"] == "S") { //Bolsa Total
            $this->sql .= ", idsolicitante = " . $this->post["financeiro"]["idsolicitante"] . ",
                            valor_contrato = 0,
                            quantidade_parcelas = 0";
        } elseif ($this->post["financeiro"]["bolsa"] == "BP") { //Bolsa Parcial
            //trata valores vazios de parcelas, contrato e forma de pagamento
            if (empty($this->post["financeiro"]["valor_contrato"])) {
                $this->post["financeiro"]["valor_contrato"] = 'NULL';
            }
            if (empty($this->post["financeiro"]["quantidade_parcelas"])) {
                $this->post["financeiro"]["quantidade_parcelas"] = 'NULL';
            }
            if (empty($this->post["financeiro"]["forma_pagamento"])) {
                $this->post["financeiro"]["forma_pagamento"] = 'NULL';
            }

            $this->sql .= ", idsolicitante = " . $this->post["financeiro"]["idsolicitante"] . ",
                            valor_contrato = " . $this->post["financeiro"]["valor_contrato"] . ",
                            quantidade_parcelas = " . $this->post["financeiro"]["quantidade_parcelas"];
            //Não Possui Bolsa
        } elseif ($this->post["financeiro"]["bolsa"] == "N") {
            //trata valores vazios de parcelas, contrato e forma de pagamento
            if (empty($this->post["financeiro"]["valor_contrato"])) {
                $this->post["financeiro"]["valor_contrato"] = 'NULL';
            }

            if (empty($this->post["financeiro"]["quantidade_parcelas"])) {
                $this->post["financeiro"]["quantidade_parcelas"] = 'NULL';
            }

            if (empty($this->post["financeiro"]["forma_pagamento"])) {
                $this->post["financeiro"]["forma_pagamento"] = 'NULL';
            }

            $this->sql .= ", idsolicitante = null,
                            valor_contrato = " . $this->post["financeiro"]["valor_contrato"] . ",
                            quantidade_parcelas = " . $this->post["financeiro"]["quantidade_parcelas"];
        }
        if ($this->post["financeiro"]["idempresa"]) {
            $this->sql .= ", idempresa = " . $this->post["financeiro"]["idempresa"];
        }
        if ($this->post["pessoa"]["renach"]) {
            $this->sql .= ", renach = '" . $this->post["pessoa"]["renach"]."'";
        }
        if ($this->post["financeiro"]["combo"]) {
            $this->sql .= ", combo = '" . $this->post["financeiro"]["combo"] . "' ";
            if ($this->post["financeiro"]["combo_matricula"]) {
                $this->sql .= ', combo_matricula = ' . $this->post["financeiro"]["combo_matricula"];
            }
        }

        if ($this->modulo == 'web') {
            include("../classes/vendedores.class.php");
            $vendedorObj = new Vendedores();
            $vendedorObj->set('campos', 'idvendedor');
            $vendedor = $vendedorObj->retornarVendedorPadrao();

            if (!empty($vendedor)) {
                $this->sql .= ", idvendedor = " . $vendedor['idvendedor'];
            } else {
                $this->sql .= ", idvendedor = NULL ";
            }
        } elseif ($this->idvendedor) {
            $this->sql .= ", idvendedor = " . $this->idvendedor . " ";
        } else {
            $idusuario = $this->idusuario;
            if ($this->modulo == 'escola' || $this->modulo == 'web') {
                $idusuario = 'NULL';
            }

            $this->sql .= ", idusuario = " . $idusuario . ",
                            idvendedor = " . $this->post["idvendedor"] . ",
                            numero_contrato = '" . $this->post["financeiro"]["numero_contrato"] . "' ";
        }

        if ($this->executaSql($this->sql)) {
            $this->id = mysql_insert_id();

            if ($this->idvendedor) {
                $this->sql = "update matriculas set numero_contrato = 'V" . $this->idvendedor . "." . $this->id . "' where idmatricula = '" . $this->id . "'";
                $this->executaSql($this->sql);

                if ($sindicato['gerente_email']) {
                    $this->sql = 'SELECT * FROM pessoas WHERE idpessoa = ' . $this->post["idpessoa"];
                    $pessoa = $this->retornarLinha($this->sql);

                    $nomePara = utf8_decode($sindicato["gerente_nome"]);

                    $message = "Ol&aacute; <strong>" . $nomePara . "</strong>,
                                <br /><br />
                                Uma nova matr&iacute;cula foi realizada pelo vendedor.
                                <br /><br />
                                Contrato: #V" . $this->idvendedor . "." . $this->id . "
                                <br />
                                Matr&iacute;cula: #" . $this->id . "
                                <br />
                                Aluno: " . $pessoa['nome'] . "
                                <br /><br />
                                <a href=\"http://" . $_SERVER["SERVER_NAME"] . "/gestor/academico/matriculas/" . $this->id . "/administrar\">Clique aqui</a> para acessar a matr&iacute;cula.
                                <br /><br />";

                    $emailPara = $sindicato["gerente_email"];
                    $assunto = utf8_decode("Nova matrícula #" . $this->id);

                    $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
                    $emailDe = $GLOBALS["config"]["emailSistema"];

                    // $this->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara);
                }
            }

            $this->AdicionarHistorico(NULL, "situacao", "modificou", NULL, $situacaoInicio["idsituacao"], NULL);
            $this->associarPessoaSindicato($this->post["idpessoa"], $this->post["idescola"]);

            if ($this->post['pagamento']['tipo_pagamento'] == 'PS' && $pedido['valor_final'] > 0) {
                $this->sql = 'SELECT idsituacao FROM contas_workflow WHERE ativo = "S" AND emaberto = "S"
                    ORDER BY idsituacao DESC LIMIT 1';
                $situacaoEmAberto = $this->retornarLinha($this->sql);

                if (!$situacaoEmAberto['idsituacao']) {
                    $this->executaSql('ROLLBACK');

                    $retorno['erro'] = true;
                    $retorno['mensagem'] = 'workflow_inicio_conta_nao_existe';
                    return $retorno;
                }

                $this->sql = 'SELECT idevento FROM eventos_financeiros WHERE ativo = "S" AND mensalidade = "S"
                    ORDER BY idevento DESC LIMIT 1';
                $eventoMensalidade = $this->retornarLinha($this->sql);

                if (!$eventoMensalidade['idevento']) {
                    $this->executaSql('ROLLBACK');

                    $retorno['erro'] = true;
                    $retorno['mensagem'] = 'evento_financeiro_mensalidade_nao_existe';
                    return $retorno;
                }

                $this->sql = 'INSERT INTO contas_relacoes SET data_cad = NOW()';
                $this->executaSql($this->sql);
                $idRelacao = mysql_insert_id();

                $vencimento = (new DateTime($pedido['data_cad']))
                    ->modify('+' . $GLOBALS['config']['dias_vencimento_conta'] . ' days')
                    ->format('Y-m-d');

                $this->sql = 'INSERT INTO
                                contas
                            SET
                                data_cad = NOW(),
                                tipo = "receita",
                                nome = "Parcela 1",
                                valor = ' . $pedido['valor_final'] . ',
                                data_vencimento = "' . $vencimento . '",
                                idsituacao = ' . $situacaoEmAberto['idsituacao'] . ',
                                idrelacao = ' . $idRelacao . ',
                                idmantenedora = ' . $mantenedora['idmantenedora'] . ',
                                idsindicato = ' . $sindicato['idsindicato'] . ',
                                idmatricula = ' . $this->id . ',
                                idpessoa = ' . $pedido['idpessoa'] . ',
                                idevento = ' . $eventoMensalidade['idevento'] . ',
                                parcela = 1,
                                total_parcelas = 1,
                                forma_pagamento = ' . $this->post['financeiro']['forma_pagamento'];
                $this->executaSql($this->sql);

                $this->post['pagamento']['idconta'] = mysql_insert_id();

                if ($this->post['pagamento']['tipo_pagamento'] == 'PS') { //Se for PagSeguro
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/pagseguro.class.php';
                    $pagSeguroObj = new PagSeguro($pedido['idescola']);
                    $criarTransacaoPagSeguro = $pagSeguroObj->set('naoIniciarTransacao', true)
                        ->set('post', $this->post['pagamento'])
                        ->set('idusuario', $this->idusuario)
                        ->set('idpessoa', $pedido['idpessoa'])
                        ->set('modulo', $this->url[0])
                        ->criarTransacao();
                }
            }

            $this->eviarEmailBoasVindas($this->id, $escola, $sindicato);
            $this->sql = 'select idcurso from cursos where idcurso = ' . $this->post["idcurso"];
            $curso = $this->retornarLinha($this->sql);

            if ($this->post['financeiro']['gerar_visita'] == 'S') {
                $_POST['nome'] = $this->post['pessoa']['nome'];
                $_POST['email'] = $this->post['pessoa']['email'];
                $_POST['cursos'][] = $this->post['idcurso'];
                $_POST['idvendedor'] = $this->post['idvendedor'];
                $visitaObj = new VisitasVendedores();
                $visitaObj->post = $_POST;

                include_once("../gestor/modulos/comercial/visitas/config.formulario.php");
                $visitaObj->config = $config;
                $salvar_visita = $visitaObj->Cadastrar();
            }

            if (empty($this->nao_monitara)) {
                $this->monitora_oque = 1;
                $this->monitora_onde = '79';
                $this->monitora_qual = $this->id;
                $this->Monitora();
            }

            $this->executaSql('COMMIT');

            $this->retorno = $this->Retornar();

            if ($salvar_visita['erro']) {
                $this->retorno["visita_erros"] = $salvar_visita['erros'];
            }

            $this->retorno['oferta'] = $this->RetornarOferta();
            $this->retorno['curso'] = $this->RetornarCurso();
            $this->retorno['escola'] = $this->RetornarEscola();
            $this->retorno['turma'] = $this->RetornarTurma();
            $this->retorno['vendedor'] = $this->RetornarVendedor();
            $this->retorno['pessoa'] = $this->RetornarPessoa();
            $this->retorno['oferta_curso'] = $this->retornarOfertaCurso();

            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    private function associarPessoaSindicato($idpessoa, $iescola)
    {
        $sql_sindicato = 'SELECT idsindicato FROM escolas where idescola = ' . $iescola;
        $sindicato = $this->retornarLinha($sql_sindicato);
        $sql = 'SELECT idpessoa_sindicato, ativo FROM pessoas_sindicatos where idpessoa = ' . $idpessoa . ' and idsindicato = ' . $sindicato['idsindicato'] . ' ';
        $pessoa_sindicato = $this->retornarLinha($sql);
        if ($pessoa_sindicato['idpessoa_sindicato']) {
            if ($pessoa_sindicato['ativo'] == 'N') {
                $sql = 'update pessoas_sindicatos set ativo = "S" where idpessoa_sindicato = ' . $pessoa_sindicato['idpessoa_sindicato'];
                $resultado = $this->executaSql($sql);
            }
        } else {
            $sql = 'insert into pessoas_sindicatos set data_cad = NOW(), idpessoa = ' . $idpessoa . ', idsindicato = ' . $sindicato['idsindicato'] . ' ';
            $resultado = $this->executaSql($sql);
        }
    }

    public function RetornarSituacoesWorkflow()
    {
        $this->sql = "SELECT * FROM matriculas_workflow where ativo = 'S'";
        $this->ordem = "asc";
        $this->ordem_campo = "ordem, nome";
        $this->limite = -1;
        $situacoes = $this->retornarLinhas();
        $this->retorno = NULL;
        foreach ($situacoes as $situacao) {
            $this->retorno[$situacao["idsituacao"]] = $situacao;
        }
        return $this->retorno;
    }

    public function RetornarRelacionamentosWorkflow($idsituacao)
    {
        $this->sql = "SELECT idsituacao_para FROM matriculas_workflow_relacionamentos where idsituacao_de = '" . mysql_real_escape_string($idsituacao) . "' and ativo = 'S'";
        $this->limite = -1;
        $this->ordem_campo = "idrelacionamento";
        $this->groupby = "idrelacionamento";
        return $this->retornarLinhas();
    }

    public function alterarSituacao($de, $para)
    {
        $this->executaSql('BEGIN');
        $this->retorno = array();

        $this->sql = "SELECT * FROM matriculas where idmatricula = " . intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);

        $situacaoCancelada = $this->retornarSituacaoCancelada();
        $situacaoAtiva = $this->retornarSituacaoAtiva();
        $situacaoAprovadoComercial = $this->retornarSituacaoAprovadoComercial();
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

    public function aprovarMatricula($idmatricula)
    {
        $retorno = array();

        $this->id = $idmatricula;
        $matricula = $this->retornar();

        $situacaoAtiva = $this->retornarSituacaoAtiva();
        $situacaoInicial = $this->retornarSituacaoInicial();

        //Só pode aprovar a matrícula se ela estiver na situação inicio(Pré-Matrícula)
        if ($matricula['idsituacao'] != $situacaoInicial['idsituacao']) {
            $retorno['sucesso'] = false;
            $retorno['mensagem'] = 'erro_aprovar_situacao_nao_inicio';
            return $retorno;
        }

        if (!$situacaoAtiva) {
            $retorno['sucesso'] = false;
            $retorno['mensagem'] = 'nao_existe_situacao_ativa';
            return $retorno;
        }

        //Aprovando a matrícula muda a situação da mesma para ativa(Em curso)
        $retorno = $this->alterarSituacao($matricula['idsituacao'], $situacaoAtiva['idsituacao']);

        if ($retorno['sucesso']) {
            $this->adicionarHistorico($this->idusuario, 'matricula', 'aprovou', null, null, null);
            $retorno['mensagem'] = 'aprovar_matricula_sucesso';
        }

        return $retorno;
    }

    public function AdicionarHistorico($idusuario, $tipo, $acao, $de, $para, $id)
    {
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

            if ($this->modulo == "cfc" && $this->idescola) {
                $this->sql .= ", idescola = '" . $this->idescola . "'";
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

            if ((!empty($de) || !empty($para)) && ($de == $para)) {
                return true;
            } else {
                return $this->executaSql($this->sql);
            }
        }
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
                            $data_de_conclusao = new DateTime(formataData($this->post['data_conclusao'], 'en'));
                        } catch (Exception $e) {
                            return $e;
                        }
                        if ($this->post['data_conclusao'] == null) {
                            $data_formatada = $data_de_conclusao->format('Y-m-d');// DATA ATUAL = now()
                        } else {
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
                    case 75:
                        $linhaAntiga = $this->retornarLinha($this->sql);
                        $this->sql = "update matriculas set
                            aprovado_comercial = 'N',
                            data_aprovado_comercial = null,
                            idusuario_aprovado_comercial = null
                            WHERE idmatricula = '" . intval($this->id) . "'";
                        $salvar = $this->executaSql($this->sql);
                        if ($salvar) {
                            $linhaNova = $this->retornarLinha($this->sql);
                            $this->AdicionarHistorico($this->idusuario, "matricula", "desaprovou_comercial", $linhaAntiga["aprovado_comercial"], $linhaNova["aprovado_comercial"], NULL);
                        }
                        $sql = "SELECT idsituacao FROM contas_workflow where ativo = 'S' and faturar = 'S' order by idsituacao desc limit 1";
                        $situacao_contas = $this->retornarLinha($sql);
                        if (array_key_exists('idsituacao', $situacao_contas)) {
                            $sql = "SELECT idsituacao FROM contas_workflow where ativo = 'S' and emaberto = 'S' order by idsituacao desc limit 1";
                            $situacao_contas1 = $this->retornarLinha($sql);
                            $this->sql = "update contas set
                                idsituacao = {$situacao_contas['idsituacao']}
                                where
                                      idmatricula = {$this->id} and
                                      idsituacao = {$situacao_contas1['idsituacao']}";
                            $this->executaSql($this->sql);
                        }
                        break;

                    case 76:
                        $this->aprovarComercialMatricula();
                        break;
                }
            }
        }
    }

    public function VerificaPreRequesito($de, $para)
    {
        $retorno["verifica"] = true;
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
                            td.idtipo
                          FROM
                            tipos_documentos td
                          where
                            td.ativo = 'S' and ativo_painel = 'S' and
                            (
                                td.idtipo in(SELECT idtipo FROM tipos_documentos_sindicatos where idtipo = td.idtipo and idsindicato = " . $matricula["idsindicato"] . " and ativo = 'S') OR
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

    public function removerAssociado($idassociado)
    {
        $this->sql = "update matriculas_associados set ativo = 'N' where idassociado = " . $idassociado;
        $salvar = $this->executaSql($this->sql);
        $this->AdicionarHistorico($this->idusuario, "associado", "removeu", NULL, NULL, $idassociado); //SE ADD NO HISTORICO
        $this->monitora_onde = 117;
        $this->monitora_oque = 3;
        $this->monitora_qual = $idassociado;
        $this->Monitora(); // SE MONITOROU
        $this->retorno["sucesso"] = true;
        $this->retorno["mensagem"] = "mensagem_associado_removido_sucesso";
        return $this->retorno;
    }

    public function retornarEventoMensalidade()
    {
        $this->sql = "SELECT * FROM  eventos_financeiros where ativo = 'S' and mensalidade = 'S' order by idevento desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    public function cadastrarFormaPagamento()
    {
        $erro = array();
        if (!$this->post["idevento"]) {
            $erro[] = "financeiro_idevento_vazio";
        }
        if ($this->modulo == "vendedor") {
            $this->post["forma_pagamento"] = $GLOBALS['tipo_pagamento_loja'][$this->post["forma_pagamento"]];
        }
        if (!$this->post["forma_pagamento"]) {
            $erro[] = "financeiro_forma_pagamento_vazio";
        } elseif ($this->post["forma_pagamento"] == 2 || $this->post["forma_pagamento"] == 3) {
            if (!$this->post["idbandeira"]) {
                $erro[] = "bandeira_cartao_vazio";
            }
            if (!$this->post["autorizacao_cartao"]) {
                $erro[] = "autorizacao_cartao_vazio";
            }
        } elseif ($this->post["forma_pagamento"] == 4) {
            if (!$this->post["idbanco"]) {
                $erro[] = "banco_cheque_vazio";
            }
            if (!$this->post["agencia_cheque"]) {
                $erro[] = "agencia_cheque_vazio";
            }
            if (!$this->post["cc_cheque"]) {
                $erro[] = "cc_cheque_vazio";
            }
            if (!$this->post["numero_cheque"]) {
                $erro[] = "numero_cheque_vazio";
            }
            if (!$this->post["emitente_cheque"]) {
                $erro[] = "emitente_cheque_vazio";
            }
            $numero_cheque = intval($this->post['numero_cheque']);
        }

        if (!$this->post["quantidade_parcelas"]) {
            $erro[] = "financeiro_quantidade_parcelas_vazio";
        }

        if (!$this->post["valor"]) {
            $erro[] = "financeiro_valor_vazio";
        } else {
            $this->post["valor"] = floatval(str_replace(',', '.', str_replace('.', '', $this->post['valor'])));
        }

        if (!$this->post["vencimento"]) {
            $erro[] = "financeiro_vencimento_vazio";
        }

        if (count($erro) <= 0) {
            $sql = "SELECT * FROM contas_workflow where ativo = 'S' and faturar = 'S' order by idsituacao desc limit 1";
            $situacao = $this->retornarLinha($sql);
            mysql_query("START TRANSACTION");
            $sql = "insert into contas_relacoes set data_cad = now()"; // para que serve???
            $this->executaSql($sql);
            $idRelacao = mysql_insert_id();
            if (!intval($this->post['quantidade_parcelas']) || $this->post["forma_pagamento"] == 3 || $this->post["forma_pagamento"] == 5) {
                $this->post['quantidade_parcelas'] = 1;
            }
            $valorParcela = round($this->post["valor"] / $this->post['quantidade_parcelas'], 2);
            $valorTotal = $valorParcela * $this->post['quantidade_parcelas'];
            $valorPrimeiraParcela = $valorParcela + ($this->post["valor"] - $valorTotal);
            $data = explode("/", $this->post["vencimento"]);

            if (!$this->post['nome'])
                $this->post['nome'] = 'Referente a uma parcela da matricula ' . $this->id;

            $this->executaSql("begin");
            for ($parcela = 1; $parcela <= $this->post['quantidade_parcelas']; $parcela++) {
                $this->post['valor'] = $valorParcela;
                if ($parcela == 1)
                    $this->post['valor'] = $valorPrimeiraParcela;

                $mes = ($data[1] + ($parcela - 1));
                $dia = $data[0];

                if ($mes == 2 && $dia >= 29) {
                    $dia = date("t", mktime(0, 0, 0, $mes, 1, $data[2]));
                }

                $vencimento = date("Y-m-d", mktime(0, 0, 0, ($data[1] + ($parcela - 1)), $dia, $data[2]));

                $this->sql = "insert into
                        contas
                      set
                        data_cad = now(),
                        tipo = 'receita',
                        nome = '" . $this->post['nome'] . "',
                        valor = " . $this->post['valor'] . ",
                        data_vencimento = '" . $vencimento . "',
                        idsituacao = " . $situacao['idsituacao'] . ",
                        idrelacao = " . $idRelacao . ",
                        idmantenedora = " . $this->post['idmantenedora'] . ",
                        idsindicato = " . $this->post['idsindicato'] . ",
                        idmatricula = " . $this->id . ",
                        idpessoa = " . $this->post['idpessoa'] . ",
                        idevento = " . $this->post['idevento'] . ",
                        parcela = " . $parcela . ",
                        total_parcelas = '" . $this->post['quantidade_parcelas'] . "',
                        idescola = '" . $this->post['idescola'] . "' ";
                if ($this->post["forma_pagamento"] == 2 || $this->post["forma_pagamento"] == 3) {
                    $this->sql .= ", forma_pagamento = " . $this->post['forma_pagamento'] . ",
                        idbandeira = " . $this->post['idbandeira'] . ",
                        autorizacao_cartao = '" . $this->post['autorizacao_cartao'] . "'";
                } elseif ($this->post["forma_pagamento"] == 4) {
                    $this->sql .= ", forma_pagamento = " . $this->post['forma_pagamento'] . ",
                        idbanco = " . $this->post['idbanco'] . ",
                        agencia_cheque = '" . $this->post['agencia_cheque'] . "',
                        cc_cheque = '" . $this->post['cc_cheque'] . "',
                        numero_cheque = '" . str_pad($numero_cheque, 6, '0', STR_PAD_LEFT) . "',
                        emitente_cheque = '" . $this->post['emitente_cheque'] . "'";
                    $numero_cheque++;
                } else {
                    $this->sql .= ", forma_pagamento = " . $this->post['forma_pagamento'];
                }
                $this->executaSql($this->sql);
                $this->idconta = mysql_insert_id();

                $this->AdicionarHistorico($this->idusuario, "parcela", "cadastrou", NULL, NULL, $this->idconta);

                $this->monitora_onde = 52;
                $this->monitora_oque = 1;
                $this->monitora_qual = $this->idconta;
                $this->Monitora();
                if ($this->post["forma_pagamento"] == 11) {
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/fastconnect.class.php';
                    $fastConnectObj = new FastConnect($this->post['fastconnect_client_code'], $this->post['fastconnect_client_key']);
                    $jsonLink = '{
                            "slug": "' . $this->post['nome'] . " " . $this->idconta . '",
                            "vl_total": ' . $this->post['valor'] . ',
                            "tp_boleto": true,
                            "tp_pagamento_boleto" : "PL",
                            "tp_credito": true,
                            "tp_pagamento_credito": "PL",
                            "url_retorno": "' . $GLOBALS['config']['urlSistema'] . '/api/set/fastconnect/' . $this->idconta . '"
                        }';

                    $pagamento = $fastConnectObj->gerarLinkPagamento($jsonLink);

                    if ($pagamento['success']) {
                        $sql = 'UPDATE
                        contas
                        SET
                            fastconnect_url_link = "' . $pagamento['data']['url_link'] . '",
                            fastconnect_nu_link = ' . $pagamento['data']['nu_link'] . '
                        WHERE idconta = ' . $this->idconta;
                        $update = $this->executaSql($sql);
                    }
                }
            }
            $this->executaSql("commit");
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "mensagem_financeiro_cadastrado_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_financeiro_campos_obrigatorios";
        }
        return $this->retorno;
    }

    public function AlterarPagamentoHistorico($antigo)
    {
        $sql_novo = "SELECT * FROM contas where idconta = '" . $this->post['idconta'] . "' ";
        $novo = $this->retornarLinha($sql_novo);
        foreach ($antigo as $ind => $campo) {
            if ($novo[$ind] != $campo) {
                $tipo = 'parcela_' . $ind;
                //$this->AdicionarHistorico($ind, "modificou", $antigo[$ind], $novo[$ind], NULL);
                $this->AdicionarHistorico($this->idusuario, $tipo, "modificou", $antigo[$ind], $novo[$ind], $this->post['idconta']);
            }
        }
    }

    public function adicionarDocumento()
    {
        $this->retorno = array();

        require_once '../classes/tiposdocumentos.class.php';
        $matricula['curso'] = $this->RetornarCurso();
        $matricula['escola'] = $this->RetornarEscola();
        $tiposDocumentosObj = new Tipos_Documentos();
        $tiposDocumentos = $tiposDocumentosObj->set('idmatricula', $this->id)
            ->set('modulo', $this->modulo)
            ->retornarTodosComObrigatorio($matricula["escola"]["idsindicato"], $matricula["curso"]["idcurso"]);
        $tiposDocumentosPermitidos = array();
        foreach ($tiposDocumentos as $ind => $var) {
            $tiposDocumentosPermitidos[] = $var['idtipo'];
        }

        if (!in_array($this->post["idtipo"], $tiposDocumentosPermitidos)) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "idtipo_invalido";
            return $this->retorno;
        }

        if (!$this->post["idtipo_associacao"]) {
            $this->post["idtipo_associacao"] = "NULL";
        }

        if ($_FILES["documento"]['error'] != 4) {
            $validarTamanho = $this->ValidarArquivo($_FILES["documento"]);
            if ($validarTamanho) {
                $this->retorno["erro"] = true;
                $this->retorno["mensagem"] = $validarTamanho;
                return $this->retorno;
            } else {
                $data_cadastro = $this->retornarDataCadMatricula();
                $data_cadastro = new DateTime($data_cadastro);
                $pasta = $_SERVER["DOCUMENT_ROOT"] . "/storage/matriculas_documentos/" . $data_cadastro->format('Y') . "/" . $data_cadastro->format('m') . '/' . $this->id;
                $extensao = strtolower(strrchr($_FILES["documento"]["name"], "."));
                $nomeServidor = date("YmdHis") . "_" . uniqid() . $extensao;
                if (!is_dir($pasta)) {
                    @mkdir($pasta, 0777, true);
                }
                @chmod($pasta, 0777);
                $envio = move_uploaded_file($_FILES["documento"]["tmp_name"], $pasta . "/" . $nomeServidor);
                chmod($pasta . "/" . $nomeServidor, 0777);

                if ($envio) {
                    $this->sql = "INSERT INTO
                                            matriculas_documentos
                                        SET
                                            data_cad = now(),
                                            idmatricula = " . $this->id . ",
                                            idtipo = " . $this->post["idtipo"] . ",
                                            idtipo_associacao = " . $this->post["idtipo_associacao"] . ",
                                            protocolo = '" . $this->post["protocolo"] . "',
                                            arquivo_nome = '" . $_FILES["documento"]["name"] . "',
                                            arquivo_servidor = '" . $nomeServidor . "',
                                            arquivo_tipo = '" . $_FILES["documento"]["type"] . "',
                                            arquivo_tamanho = '" . $_FILES["documento"]["size"] . "',
                                            arquivo_pasta = '" . $data_cadastro->format('Y') . "/" . $data_cadastro->format('m') . "'";
                    $salvar = $this->executaSql($this->sql);
                    if ($salvar) {
                        $iddocumento = mysql_insert_id();
                        $documentoBiometria = $this->retornarDocumentoBiometria($this->id, $iddocumento);

                        if(count($documentoBiometria) > 0)
                        {
                            $sql = "UPDATE matriculas SET envio_foto_documento_oficial = 'S' WHERE idmatricula = {$this->id}";
                            $this->executaSql($sql);
                        }
                        $this->AdicionarHistorico($this->idusuario, "documento", "cadastrou", NULL, NULL, $iddocumento);
                        $this->retorno["sucesso"] = true;
                        $this->retorno["mensagem"] = "documentos_matricula_envio_sucesso";
                    } else {
                        $this->retorno["sucesso"] = false;
                        $this->retorno["mensagem"] = "documentos_matricula_envio_erro";
                    }
                } else {
                    $this->retorno["sucesso"] = false;
                    $this->retorno["mensagem"] = "documentos_matricula_envio_erro";
                }
            }
        } else {
            $this->sql = "INSERT INTO
                                    matriculas_documentos
                                SET
                                    data_cad = now(),
                                    idmatricula = " . $this->id . ",
                                    idtipo = " . $this->post["idtipo"] . ",
                                    protocolo = '" . $this->post["protocolo"] . "',
                                    idtipo_associacao = " . $this->post["idtipo_associacao"];
            $salvar = $this->executaSql($this->sql);
            if ($salvar) {
                $iddocumento = mysql_insert_id();
                $documentoBiometria = $this->retornarDocumentoBiometria($this->id, $iddocumento);

                if(count($documentoBiometria) > 0)
                {
                    $sql = "UPDATE matriculas SET envio_foto_documento_oficial = 'S' WHERE idmatricula = {$this->id}";
                    $this->executaSql($sql);
                }
                $this->AdicionarHistorico($this->idusuario, "documento", "cadastrou", NULL, NULL, $iddocumento);
                $this->retorno["sucesso"] = true;
                $this->retorno["mensagem"] = "documentos_matricula_envio_sucesso";
            } else {
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "documentos_matricula_envio_erro";
            }
        }

        return $this->retorno;
    }

    public function adicionarLoteDocumento()
    {
        $this->retorno = array();
        $this->executaSql('begin');
        if (count($this->post["documentos"]) > 0) {
            foreach ($this->post["documentos"] as $idtipo) {
                $this->sql = "insert into
                                matriculas_documentos
                            set
                                data_cad = now(),
                                idmatricula = " . $this->id . ",
                                protocolo = '" . $this->post["protocolo"] . "',
                                situacao = 'aguardando',
                                idtipo = " . $idtipo;
                $salvar = $this->executaSql($this->sql);
                if ($salvar) {
                    $this->AdicionarHistorico($this->idusuario, "documento", "cadastrou", NULL, NULL, mysql_insert_id());
                    $this->retorno["sucesso"] = true;
                    $this->retorno["mensagem"] = "documentos_matricula_envio_sucesso";
                } else {
                    $this->retorno["sucesso"] = false;
                    $this->retorno["mensagem"] = "documentos_matricula_envio_erro";
                    $this->executaSql('rollback');
                    return $this->retorno;
                }
            }
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "documentos_lote_nenhum_selecionado";
        }
        $this->executaSql('commit');
        return $this->retorno;
    }

    public function retornarDataCadMatricula()
    {
        $this->sql = 'SELECT data_cad FROM matriculas WHERE idmatricula = "' . $this->id . '" ';
        $resultado = $this->retornarLinha($this->sql);
        return $resultado['data_cad'];
    }

    public function editarArquivo()
    {

        if ($this->post['contaflag']) {
            $table = 'contas_arquivos';
        } else {
            $table = 'matriculas_arquivos';
        }

        $this->retorno = array();

        $this->sql = "SELECT * FROM $table where  idarquivo = " . $this->post["idarquivo"] . " and idmatricula = " . $this->id;
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update
                        $table
                    set
                        protocolo = '" . $this->post["protocolo"] . "'
                    where
                        idarquivo = " . $this->post["idarquivo"] . " and
                        idmatricula = " . $this->id;
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            $this->sql = "SELECT * FROM $table where  idarquivo = " . $this->post["idarquivo"] . " and idmatricula = " . $this->id;
            $linhaNova = $this->retornarLinha($this->sql);
            if ($table == 'matriculas_arquivos') {
                $this->AdicionarHistorico($this->idusuario, "arquivo", "modificou", $linhaAntiga['protocolo'], $linhaNova['protocolo'], $this->post["idarquivo"]);
            } else {
                $this->sql = "insert contas_historicos  set
                                            idconta = '" . $linhaNova['idconta'] . "',
                                            data_cad = now(),
                                            tipo = 'arquivo',
                                            acao = 'modificou',
                                            idusuario = '" . $this->idusuario . "',
                                            id = '" . $this->post["idarquivo"] . "'
                            ";
                $this->executaSql($this->sql);
            }
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "documentos_editou_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "documentos_editou_erro";
        }

        return $this->retorno;
    }

    public function editarDocumento()
    {
        $this->retorno = array();

        $this->sql = "SELECT * FROM matriculas_documentos where  iddocumento = " . $this->post["iddocumento"] . " and idmatricula = " . $this->id;
        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update
                        matriculas_documentos
                    set
                        protocolo = '" . $this->post["protocolo"] . "'
                    where
                        iddocumento = " . $this->post["iddocumento"] . " and
                        idmatricula = " . $this->id;
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            $this->sql = "SELECT * FROM matriculas_documentos where  iddocumento = " . $this->post["iddocumento"] . " and idmatricula = " . $this->id;
            $linhaNova = $this->retornarLinha($this->sql);

            $this->AdicionarHistorico($this->idusuario, "documento", "modificou", $linhaAntiga['protocolo'], $linhaNova['protocolo'], $this->post["iddocumento"]);
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "documentos_editou_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "documentos_editou_erro";
        }

        return $this->retorno;
    }

    public function enviarDocumento($iddocumento)
    {
        $this->retorno = array();
        if ($_FILES["documento"]['error'] != 4) {
            $validarTamanho = $this->ValidarArquivo($_FILES["documento"]);
            if ($validarTamanho) {
                $this->retorno["erro"] = true;
                $this->retorno["mensagem"] = $validarTamanho;
                return $this->retorno;
            } else {
                $data_matricula = $this->retornarDataCadMatricula();
                $data_matricula = new DateTime($data_matricula);
                $pasta = $_SERVER["DOCUMENT_ROOT"] . "/storage/matriculas_documentos/" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "/" . $this->id;
                $extensao = strtolower(strrchr($_FILES["documento"]["name"], "."));
                $nomeServidor = date("YmdHis") . "_" . uniqid() . $extensao;
                if (!is_dir($pasta)) {
                    @mkdir($pasta, 0777, true);
                }
                @chmod($pasta, 0777);
                $envio = move_uploaded_file($_FILES["documento"]["tmp_name"], $pasta . "/" . $nomeServidor);
                chmod($pasta . "/" . $nomeServidor, 0777);
                if ($envio) {
                    $this->sql = "update
                    matriculas_documentos
                  set
                    situacao='aguardando',
                    arquivo_nome = '" . $_FILES["documento"]["name"] . "',
                    arquivo_servidor = '" . $nomeServidor . "',
                    arquivo_tipo = '" . $_FILES["documento"]["type"] . "',
                    arquivo_tamanho = '" . $_FILES["documento"]["size"] . "',
                    arquivo_pasta = '" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "'
                  where
                    iddocumento = " . $iddocumento . " and
                    idmatricula = " . $this->id;
                    $salvar = $this->executaSql($this->sql);
                    if ($salvar) {
                        $this->AdicionarHistorico($this->idusuario, "documento", "enviou", NULL, NULL, $iddocumento);
                        $this->retorno["sucesso"] = true;
                        $this->retorno["mensagem"] = "documentos_arquivo_envio_sucesso";
                    } else {
                        $this->retorno["sucesso"] = false;
                        $this->retorno["mensagem"] = "documentos_arquivo_envio_erro";
                    }
                } else {
                    $this->retorno["sucesso"] = false;
                    $this->retorno["mensagem"] = "documentos_arquivo_envio_erro";
                }
            }
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "documentos_arquivo_envio_erro";
        }
        return $this->retorno;
    }

    public function removerDocumento()
    {
        $this->retorno = array();
        $this->sql = "update matriculas_documentos set ativo = 'N' where iddocumento = " . $this->iddocumento . " and idmatricula = " . $this->id;
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            $this->AdicionarHistorico($this->idusuario, "documento", "removeu", NULL, NULL, $this->iddocumento);
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "documentos_matricula_remover_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "documentos_matricula_remover_erro";
        }
        return $this->retorno;
    }

    public function removerArquivo()
    {
        $this->retorno = array();
        $this->sql = "UPDATE matriculas_arquivos SET ativo ='N' WHERE idarquivo = {$this->idarquivo} AND idmatricula = " . $this->id;
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            $this->AdicionarHistorico($this->idusuario, "arquivo", "removeu", NULL, NULL, $this->idarquivo);
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "arquivo_matricula_remover_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "arquivo_matricula_remover_erro";
        }
        return $this->retorno;
    }

    public function retornarDocumento()
    {
        $this->sql = "SELECT
                            md.*,
                            m.data_cad as data_matricula
                    FROM
                        matriculas_documentos md
                    INNER JOIN matriculas m ON (md.idmatricula = m.idmatricula)
                    WHERE
                        md.iddocumento = " . $this->iddocumento . " AND
                        md.idmatricula = " . $this->id;
        return $this->retornarLinha($this->sql);
    }

    /**
     * @access public
     * @param int $idMatricula
     * @return array
     */
    public function retornarDocumentosPorMatricula($idMatricula)
    {
        try {
            if (!is_numeric($idMatricula)) {
                throw new InvalidArgumentException("para realizar a consulta dos documentos, o valor da matrícula precisa ser um inteiro!");
            } else {
                $this->sql = "SELECT * FROM matriculas_documentos md WHERE md.idmatricula = {$idMatricula} AND md.situacao = 'aprovado' AND md.ativo = 'S'";
                $resultado = mysql_query($this->sql);
                if (!$resultado) {
                    throw new Exception(mysql_error());
                } else {
                    while ($linha = mysql_fetch_assoc($resultado)) {
                        $retorno[] = $linha;
                    }
                    return $retorno;
                }
            }
        } catch (InvalidArgumentException $i) {
            echo "Ops! {$i->getMessage()}";
        } catch (Exception $e) {
            die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => $e->getMessage())));
        }
    }

    public function retornarArquivo()
    {
        $this->sql = "SELECT *, idarquivo as iddocumento FROM matriculas_arquivos WHERE idarquivo = " . $this->iddocumento . " and ativo = 'S' and idmatricula = " . $this->id;
        return $this->retornarLinha($this->sql);
    }

    public function retornarArquivoConta()
    {
        $this->sql = "SELECT *, idarquivo as iddocumento FROM contas_arquivos WHERE idarquivo = " . $this->iddocumento . " and ativo = 'S' and idmatricula = " . $this->id;
        return $this->retornarLinha($this->sql);
    }

    public function aprovarDocumento()
    {
        $this->retorno = array();

        $documentoBiometria = $this->retornarDocumentoBiometria($this->iddocumento, $this->id);

        $this->sql = "UPDATE
                            matriculas_documentos
                        SET
                            situacao = '" . $this->post["situacao"] . "'";

        if ($this->post["situacao"] == "reprovado") {
            if (!$this->post["descricao_motivo_reprovacao"]) {
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "documentos_matricula_reprovacao_descricao_vazio";
                return $this->retorno;
            }

            if ($this->post["descricao_motivo_reprovacao"]) {
                $this->sql .= ", descricao_motivo_reprovacao = '" . $this->post["descricao_motivo_reprovacao"] . "'";
            }
        }

        $this->sql .= " WHERE
                            iddocumento = '" . $this->iddocumento . "' AND
                            idmatricula = '" . $this->id . "'";
        $salvar = $this->executaSql($this->sql);

        if ($salvar) {
            if ($this->post["situacao"] == "aprovado") {
                $acao = "aprovou";
                if($documentoBiometria)
                {
                    $this->executaSql("
                    UPDATE matriculas
                    SET liberacao_temporaria_datavalid = 'N'                    
                    WHERE idmatricula = $this->id
                    ");
                    $this->atualizarPorcentagemBiometriaDataValid($this->id, 0.85);
                }
            } else {
                $acao = "reprovou";
                if(count($documentoBiometria) > 0)
                {
                    $this->executaSql("
                    UPDATE matriculas
                    SET envio_foto_documento_oficial = 'N', email_documento_biometria = 'N'
                    WHERE idmatricula = $this->id
                    ");
                } 
            }

            $this->AdicionarHistorico($this->idusuario, "documento", $acao, NULL, NULL, $this->iddocumento);
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "documentos_matricula_situacao_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "documentos_matricula_situacao_erro";
        }

        return $this->retorno;
    }

    public function RetornarContratosGrupos()
    {
        $this->sql = "SELECT
           " . $this->campos . "
           FROM
           contratos c
           where
           c.ativo = 'S' and
           c.ativo_painel = 'S'

           union

           SELECT
           " . $this->campos_2 . "
           FROM
           contratos_grupos gc
           where
           gc.ativo = 'S' and
           gc.ativo_painel = 'S'";
        $this->groupby = "c.idcontrato";
        return $this->retornarLinhas();
    }

    public function gerarContrato($idioma = null)
    {
        if ($this->post["idcontrato"]) {
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
                } elseif ($ind == "gerente_data_nasc") {
                    $documento = str_ireplace("[[cfc][gerente_data_nasc]]", formataData($val, "br", 0), $documento);
                    $documento = str_ireplace("[[cfc][gerente_data_nasc]]", formataData($val, "br", 0), $documento);
                } elseif ($ind == "diretor_ensino_data_nasc") {
                    $documento = str_ireplace("[[cfc][diretor_ensino_data_nasc]]", formataData($val, "br", 0), $documento);
                    $documento = str_ireplace("[[cfc][diretor_ensino_data_nasc]]", formataData($val, "br", 0), $documento);
                } elseif ($ind == "responsavel_legal_idlogradouro") {
                    $documento = str_ireplace("[[cfc][responsavel_legal_logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                    $documento = str_ireplace("[[cfc][responsavel_legal_logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                } elseif ($ind == "gerente_idlogradouro") {
                    $documento = str_ireplace("[[cfc][gerente_logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                    $documento = str_ireplace("[[cfc][gerente_logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                } elseif ($ind == "idlogradouro") {
                    $documento = str_ireplace("[[cfc][logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                    $documento = str_ireplace("[[cfc][logradouro]]", $this->retornarNomeLogradouro($val), $documento);
                } else {
                    $documento = str_ireplace("[[cfc][" . $ind . "]]", $val, $documento);
                    $documento = str_ireplace("[[cfc][" . $ind . "]]", $val, $documento);
                }
            }
            //FIM CFC

            //CURSO
            $inicioCurso = $this->retornarInicioCurso($matricula['idoferta'], $matricula['idcurso']); //Retorna a data de início do curso data_inicio_aula
            $acessoAva = $this->retornarAcessoAva($matricula['idoferta'], $matricula['idcurso'], $matricula["idescola"]); //Retorna o Período de acesso ao ava

            $documento = str_ireplace("[[curso][inicio]]", formataData($inicioCurso['data_inicio_aula'], 'br', 0), $documento);
            $documento = str_ireplace("[[curso][termino]]", formataData($acessoAva['data_limite_acesso_ava'], 'br', 0), $documento);
            //FIM CURSO

            //FINANCEIRO
            $situacaoRenegociadaConta = $this->retornarSituacaoRenegociadaConta();
            $situacaoCanceladaConta = $this->retornarSituacaoCanceladaConta();
            $situacaoTransferidaConta = $this->retornarSituacaoTransferidaConta();
            $_GET['q']['1|cw.cancelada'] = 'N';
            $contasArray = $this->RetornarContas(true);
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
            foreach ($contasArray as $idevento => $contas) {

                $this->sql = "SELECT
                                nome
                            FROM
                               eventos_financeiros
                            WHERE
                               idevento = " . $contas[0]['idevento'] . " and ativo = 'S' and ativo_painel = 'S'  LIMIT 1 ";

                $eventoTabela = $this->retornarLinha($this->sql);


                $tabelaFormaPagamentoDetalhado .= '<br><table border="1" style="width:500px; ">
                                      <tr>
                                          <td colspan="3">
                                              ' . $eventoTabela['nome'] . '
                                          </td>
                                      </tr>
                                       <tr>
                                                <td>Forma de Pagamento</td>
                                                 <td>Valor</td>
                                                <td>Vencimento</td>
                                       </tr>
                                      ';

                foreach ($contas as $conta) {
                    if ($conta['valor_matricula']) {
                        $valor_parcela = ($conta["valor_matricula"] / $conta['total_contas_compartilhadas']);
                    }

                    if ($situacaoRenegociadaConta['idsituacao'] != $conta['idsituacao'] && $situacaoCanceladaConta['idsituacao'] != $conta['idsituacao'] && $situacaoTransferidaConta['idsituacao'] != $conta['idsituacao']) {
                        /*if ($conta['valor_matricula']) {
                            $total_compartilhado += $valor_parcela;
                        } else {
                            $total += $conta["valor"];
                        }*/
                        if ($eventoMensalidade['idevento'] == $conta['idevento'])
                            $total = $total + $conta['valor'];
                        if ($eventoMensalidade['idevento'] != $conta['idevento'])
                            $total_outras = $total_outras + $conta['valor'];
                    }

                    $tabelaFormaPagamentoDetalhado .= '  <tr>
                                                            <td>' . $GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$conta["forma_pagamento"]] . '</td>
                                                            <td>' . number_format($conta['valor'], 2, ',', '.') . '</td>
                                                            <td>' . formataData($conta['data_vencimento'], "br", 0) . '</td>
                                                        </tr>';
                }
                $tabelaFormaPagamentoDetalhado .= '</table>';
            }

            $documento = str_ireplace("[[financeiro][forma_pagamento_detalhado]]", $tabelaFormaPagamentoDetalhado, $documento);
            $documento = str_ireplace("[[financeiro][valor_total_mens]]", "R$ " . number_format($matricula['valor_contrato'], 2, ',', '.'), $documento);
            $documento = str_ireplace("[[financeiro][valor_total_mens_extenso]]", extenso($matricula['valor_contrato'], true), $documento);
            $documento = str_ireplace("[[financeiro][valor_total_outras]]", "R$ " . $total_outras, $documento);
            $documento = str_ireplace("[[financeiro][valor_total_outras_extenso]]", extenso($total_outras, true), $documento);

            $tabelaFormaPagamento = $GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula["forma_pagamento"]];
            if ($matricula["forma_pagamento"] == 2 || $matricula["forma_pagamento"] == 3) { //Se a forma de pagamento for cartão de crédito ou débito

                require_once '../classes/bandeirascartoes.class.php';
                $bandeirasObj = new Bandeiras_Cartoes();

                $bandeira = $bandeirasObj->set('id', $matricula["idbandeira"])
                    ->Retornar();
                $tabelaFormaPagamento = '<table border="1" style="width:500px; ">
                                            <tr>
                                                <td colspan="2">
                                                    ' . $GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula["forma_pagamento"]] . '
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Bandeira</td>
                                                <td>Autorização</td>
                                            </tr>
                                            <tr>
                                                <td>' . $bandeira['nome'] . '</td>
                                                <td>' . $matricula['autorizacao_cartao'] . '</td>
                                            </tr></table>';
            }

            $documento = str_ireplace("[[financeiro][forma_pagamento]]", $tabelaFormaPagamento, $documento);
            $documento = str_ireplace("[[financeiro][qnt_parcelas]]", $matricula['quantidade_parcelas'], $documento);
            //FIM FINANCEIRO

            //VENDEDOR
            $this->sql = "SELECT * FROM vendedores WHERE idvendedor = '" . $matricula["idvendedor"] . "'";
            $vendedor = $this->retornarLinha($this->sql);
            foreach ($vendedor as $ind => $val) {
                if ($ind == "rg_data_emissao") {
                    $documento = str_ireplace("[[atendente][" . $ind . "]]", formataData($val, "br", 0), $documento);
                } else {
                    $documento = str_ireplace("[[atendente][" . $ind . "]]", $val, $documento);
                }
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
            //FIM MATRICULA

            //CAMPOS CONTRATO/ADICIONAIS

            //Cria a tabela de documentos
            $documentosArray = $this->RetornarDocumentos();
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
                                            <td>' . $var['tipo'] . '</td>
                                            <td>' . $associacao . '</td>
                                            <td>' . $var['arquivo_nome'] . '</td>
                                            <td>' . $GLOBALS['situacao_documento'][$GLOBALS['config']['idioma_padrao']][$var['situacao']] . '</td>
                                        </tr>';
            }
            $tabela_documentos .= '</table>';
            $documento = str_ireplace("[[tabela_documentos]]", $tabela_documentos, $documento);

            $documento = str_ireplace("[[DATA_GERACAO_CONTRATO]]", date("d/m/Y"), $documento);

            setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $documento = str_ireplace("[[DATA_GERACAO_CONTRATO_EXTENSO]]", utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today'))), $documento);

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
            $this->retorno = array();
            if ($documento) {
                $data_matricula = $this->retornarDataCadMatricula();
                $data_matricula = new DateTime($data_matricula);

                $this->sql = "insert into matriculas_contratos set data_cad = now(), idmatricula = " . $this->id . ", idcontrato = " . $contrato["idcontrato"] . ", arquivo_pasta = '" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "'";
                if ($this->modulo == "gestor") {
                    $this->sql .= ", idusuario = '" . $this->idusuario . "'";
                }

                if ($this->modulo == "loja") {
                    $this->sql .= ", aceito_aluno = 'S', aceito_aluno_data = now()";
                }

                $salvar = $this->executaSql($this->sql);
                $idcontratoMatricula = mysql_insert_id();
                if ($salvar) {

                    $sql = 'select p.* from pessoas p inner join matriculas m on m.idpessoa = p.idpessoa where m.idmatricula = "' . $this->id . '"';
                    $resultado = $this->executaSql($sql);
                    $pessoa = mysql_fetch_assoc($resultado);

                    if ($pessoa['idpessoa']) {
                        $nomePara = utf8_decode($pessoa["nome"]);

                        $message = "Ol&aacute; <strong>" . $nomePara . "</strong>,
                                    <br /><br />
                                    Um novo contrato foi gerado para a matr&iacute;cula #" . $this->id . ", acesse a p&aacute;gina de contratos para a visualiza&ccedil;&atilde;o ou para aceitar o contrato.
                                    <br /><br />
                                    <a href=\"http://" . $_SERVER["SERVER_NAME"] . "/aluno/secretaria/contratos/" . $this->id . "\">Clique aqui</a> para aceitar o contrato.
                                    <br /><br />";

                        $emailPara = $pessoa["email"];
                        $assunto = utf8_decode("Novo contrato na matrícula #" . $this->id);

                        $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
                        $emailDe = $GLOBALS["config"]["emailSistema"];

                        $this->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara);

                        if ($devedor['idpessoa']) {
                            $nomePara = utf8_decode($devedor["nome"]);

                            $message = "Ol&aacute; <strong>" . $nomePara . "</strong>,
                                        <br /><br />
                                        Um novo contrato foi gerado para a matr&iacute;cula #" . $this->id . ", acesse a p&aacute;gina de contratos para a visualiza&ccedil;&atilde;o ou para aceitar o contrato.
                                        <br /><br />
                                        <a href=\"http://" . $_SERVER["SERVER_NAME"] . "/devedorsolidario/?cpf=" . $devedor["documento"] . "\">Clique aqui</a> para aceitar o contrato.
                                        <br /><br />";

                            $emailPara = $devedor["email"];
                            $assunto = utf8_decode("Novo contrato na matrícula #" . $this->id);

                            $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
                            $emailDe = $GLOBALS["config"]["emailSistema"];

                            $this->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara);
                        }
                    }

                    $pastaContratos = $_SERVER["DOCUMENT_ROOT"] . "/storage/matriculas_contratos/" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "/" . $this->id;
                    if (!is_dir($pastaContratos)) {
                        @mkdir($pastaContratos, 0777, true);
                    }
                    @chmod($pastaContratos, 0777);
                    /*if (!is_dir($pastaContratos))
                        mkdir($pastaContratos, 0777);*/
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
                    $this->AdicionarHistorico($this->idusuario, "contrato", "cadastrou", NULL, NULL, $idcontratoMatricula);
                    $this->retorno["sucesso"] = true;
                    $this->retorno["mensagem"] = "contrato_gerado_sucesso";
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
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = "contrato_vazio";
        }
        return $this->retorno;
    }

    public function enviarContrato()
    {
        if (!$_FILES["contrato"]["tmp_name"]) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_matricula_arquivo_vazio";
            return $this->retorno;
        }
        if (!(int)$this->post["idtipo"]) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_matricula_tipo_vazio";
            return $this->retorno;
        }
        $this->retorno = array();
        $validar = $this->ValidarArquivo($_FILES["contrato"]);
        $extensao = strtolower(strrchr($_FILES["contrato"]["name"], "."));
        if ($validar || ($extensao != ".pdf" && $extensao != ".doc" && $extensao != ".docx")) {
            $this->retorno["erro"] = true;
            if ($validar) {
                $this->retorno["mensagem"] = $validar;
            } else {
                $this->retorno["mensagem"] = "contratos_matricula_extensao_erro";
            }
            return $this->retorno;
        }
        $data_matricula = $this->retornarDataCadMatricula();
        $data_matricula = new DateTime($data_matricula);
        $pasta = $_SERVER["DOCUMENT_ROOT"] . "/storage/matriculas_contratos/" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "/" . $this->id;
        $nomeServidor = date("YmdHis") . "_" . uniqid() . $extensao;
        if (!is_dir($pasta)) {
            @mkdir($pasta, 0777, true);
        }
        @chmod($pasta, 0777);
        $envio = move_uploaded_file($_FILES["contrato"]["tmp_name"], $pasta . "/" . $nomeServidor);
        @chmod($pasta . "/" . $nomeServidor, 0777);
        if (!$envio) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_matricula_envio_erro";
            return $this->retorno;
        }
        $this->sql = "INSERT INTO
                            matriculas_contratos
                    SET
                         data_cad = now(),
                         idmatricula = " . $this->id . ",
                         idtipo = " . (int)$this->post["idtipo"] . ",
                         arquivo = '" . $_FILES["contrato"]["name"] . "',
                         arquivo_tipo = '" . $_FILES["contrato"]["type"] . "',
                         arquivo_tamanho = '" . $_FILES["contrato"]["size"] . "',
                         arquivo_servidor = '" . $nomeServidor . "',
                         arquivo_pasta = '" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "'";
        if ($this->modulo == "gestor") {
            $this->sql .= ", idusuario = '" . $this->idusuario . "'";
        }
        $salvar = $this->executaSql($this->sql);
        if (!$salvar) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_matricula_envio_erro";
            return $this->retorno;
        }
        $this->AdicionarHistorico($this->idusuario, "contrato", "cadastrou", NULL, NULL, mysql_insert_id());
        $this->retorno["sucesso"] = true;
        $this->retorno["mensagem"] = "contratos_matricula_envio_sucesso";
        return $this->retorno;
    }

    public function assinarContrato($idmatricula_contrato, $situacao)
    {
        $this->retorno = array();
        if ($situacao == 2) {
            $assinado = "now()";
            $nao_assinado = "null";
        } else if ($situacao == 1) {
            $assinado = "null";
            $nao_assinado = "now()";
        }
        $this->sql = "update matriculas_contratos set nao_assinado = " . $nao_assinado . ", assinado = " . $assinado;
        if ($this->modulo == "gestor") {
            $this->sql .= ", idusuario_assinou = '" . $this->idusuario . "'";
        }
        $this->sql .= " where idmatricula_contrato = '" . $idmatricula_contrato . "' and idmatricula = '" . $this->id . "'";
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            if ($situacao == 2)
                $acao = "assinou";
            elseif ($situacao == 1)
                $acao = "desassinou";
            $this->AdicionarHistorico($this->idusuario, "contrato", $acao, NULL, NULL, $idmatricula_contrato);
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "contratos_matricula_assinado_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_matricula_assinado_erro";
        }
        return $this->retorno;
    }

    public function assinarContratoDevedor($idmatricula_contrato)
    {
        $erros = array();
        $this->retorno = array();

        //Verifica se o contrato existe e não está cancelado
        $this->sql = 'SELECT
                            idmatricula_contrato
                        FROM
                            matriculas_contratos
                        WHERE
                            idmatricula_contrato = "' . $idmatricula_contrato . '" AND
                            idmatricula = "' . $this->id . '" AND
                            cancelado IS NULL';
        $contrato = $this->retornarLinha($this->sql);

        if (!$contrato['idmatricula_contrato']) {
            $this->retorno['sucesso'] = false;
            $this->retorno['mensagem'] = "contratos_matricula_nao_existe";
        } else {
            $this->sql = "UPDATE
                                matriculas_contratos
                            SET
                                assinado_devedor = NOW(),
                                iddevedor_assinou = '" . $this->idpessoa . "'
                            WHERE
                                idmatricula_contrato = '" . $idmatricula_contrato . "' and
                                idmatricula = '" . $this->id . "' AND
                                cancelado IS NULL";
            $salvar = $this->executaSql($this->sql);

            if ($salvar) {
                $this->AdicionarHistorico(NULL, "contrato", "assinou", NULL, NULL, $idmatricula_contrato);
                $this->retorno["sucesso"] = true;
                $this->retorno["mensagem"] = "contratos_matricula_assinado_sucesso";
            } else {
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "contratos_matricula_assinado_erro";
            }
        }

        return $this->retorno;
    }

    public function validarContrato($idmatricula_contrato, $situacao)
    {
        $this->retorno = array();
        if ($situacao == 2) {
            $validado = "now()";
            $nao_validado = "null";
        } else if ($situacao == 1) {
            $validado = "null";
            $nao_validado = "now()";
        }
        $this->sql = "update matriculas_contratos set nao_validado = " . $nao_validado . ", validado = " . $validado;
        if ($this->modulo == "gestor") {
            $this->sql .= ", idusuario_validou = '" . $this->idusuario . "'";
        }
        $this->sql .= " where idmatricula_contrato = '" . $idmatricula_contrato . "' and idmatricula = '" . $this->id . "'";
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            if ($situacao == 2)
                $acao = "validou";
            elseif ($situacao == 1)
                $acao = "desvalidou";
            $this->AdicionarHistorico($this->idusuario, "contrato", $acao, NULL, NULL, $idmatricula_contrato);
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "contratos_matricula_validado_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_matricula_validado_erro";
        }
        return $this->retorno;
    }

    public function cancelarContrato($situacao, $justificativa, $idmatricula_contrato)
    {
        $this->retorno = array();
        if ($situacao == 2) {
            $cancelado = "now()";
        } else {
            $cancelado = "NULL";
        }
        $this->sql = "update matriculas_contratos set cancelado = " . $cancelado . ", justificativa = '" . $justificativa . "'";
        if ($this->modulo == "gestor") {
            $this->sql .= ", idusuario_cancelou = '" . $this->idusuario . "'";
        }
        $this->sql .= " where idmatricula_contrato = '" . $idmatricula_contrato . "' and idmatricula = '" . $this->id . "'";
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            if ($situacao == 2)
                $acao = "cancelou";
            elseif ($situacao == 1)
                $acao = "descancelou";
            $this->AdicionarHistorico($this->idusuario, "contrato", $acao, NULL, NULL, $idmatricula_contrato);
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "contratos_matricula_cancelado_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_matricula_cancelado_erro";
        }
        return $this->retorno;
    }

    public function retornarContrato($idmatricula_contrato)
    {
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
                        mc.idmatricula_contrato = '" . $idmatricula_contrato . "' AND
                        mc.idmatricula = '" . $this->id . "' AND
                        mc.ativo = 'S'";
        return $this->retornarLinha($this->sql);
    }

    public function RetornarDeclaracoesGrupos($json = false)
    {
        $matricula = $this->Retornar();
        $this->sql = "SELECT
       " . $this->campos . "
        FROM
        declaracoes d
        LEFT JOIN declaracoes_sindicatos di on ( di.iddeclaracao = d.iddeclaracao and di.idsindicato = " . $matricula['idsindicato'] . " and di.ativo = 'S')
        LEFT JOIN declaracoes_cursos dc on ( dc.iddeclaracao = d.iddeclaracao and dc.idcurso = " . $matricula['idcurso'] . " and dc.ativo = 'S')
        WHERE
        (di.iddeclaracao IS NOT NULL or dc.iddeclaracao IS NOT NULL) AND
        d.ativo = 'S' AND
        d.ativo_painel = 'S' ";
        if ($this->modulo != 'gestor') {
            $this->sql .= "AND d.aluno_solicita = 'S'";
        }
        $this->sql .= " UNION

           SELECT
           " . $this->campos_2 . "
           FROM
          declaracoes_grupos gd
           WHERE
           gd.ativo = 'S' AND
           gd.ativo_painel = 'S'";
        $this->groupby = "d.iddeclaracao";
        $declaracoes = $this->retornarLinhas();
        if ($json) {
            return json_encode($declaracoes);
        } else {
            return $declaracoes;
        }
    }

    /**
     * Modifica a visibilidade da declaracao em `matriculas_declaracoes`
     *
     * @param $idmatriculadeclaracao
     * @param $situacao
     *
     * @return array
     */
    public function alterarVisibilidadeDeclaracao($idmatriculadeclaracao, $situacao)
    {
        $this->sql = "UPDATE `matriculas_declaracoes`
                    SET aluno_visualiza = '{$situacao}'
                  WHERE idmatriculadeclaracao = '{$idmatriculadeclaracao}'";
        return $this->executaSql($this->sql);
    }

    public function retornarDeclaracao($idmatricula_declaracao)
    {
        $this->sql = 'SELECT
                        md.*,
                        dt.nome AS tipo,
                        d.nome AS declaracao,
                        d.margem_left,
                        d.margem_right,
                        d.margem_top,
                        d.margem_bottom,
                        d.background_servidor
                    FROM
                        matriculas_declaracoes md
                        LEFT OUTER JOIN declaracoes d ON (md.iddeclaracao = d.iddeclaracao)
                        LEFT OUTER JOIN declaracoes_tipos dt ON (d.idtipo = dt.idtipo)
                    WHERE
                        md.idmatriculadeclaracao = ' . $idmatricula_declaracao . ' AND
                        md.ativo = "S"';
        return $this->retornarLinha($this->sql);
    }

    public function gerarDeclaracao($idioma = null)
    {

        $this->retorno = array();
        if (!$this->post["iddeclaracao"]) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = "declaracao_vazio";
            return $this->retorno;
        }
        $this->sql = "SELECT * FROM declaracoes
                    WHERE iddeclaracao = " . $this->post["iddeclaracao"];
        $declaracao = $this->retornarLinha($this->sql);
        $documento = $declaracao["declaracao"];
        //Matrículas
        $this->sql = "SELECT m.* FROM matriculas m
                    WHERE m.idmatricula = " . $this->id;
        $matricula = $this->retornarLinha($this->sql);
        foreach ($matricula as $ind => $val) {
            //$dados = utf8_decode($dados);
            //$dados = htmlentities($val);
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
                    WHERE m.idmatricula = " . $this->id;
        $aluno = $this->retornarLinha($sql_aluno);
        foreach ($aluno as $ind => $cli) {
            //$dados = utf8_decode($dados);
            //$dados = htmlentities($cli);
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
            //$dados = utf8_decode($dados);
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
            //$dados = utf8_decode($dados);
            $dados = htmlentities($dados);
            if ($ind == 'idlogradouro') {
                $documento = str_ireplace("[[escola][logradouro]]", $this->retornarNomeLogradouro($dados), $documento);
            } elseif ($ind == 'idcidade') {
                $documento = str_ireplace("[[escola][cidade]]", $this->retornarNomeCidade($dados), $documento);
            } elseif ($ind == 'idestado') {
                $documento = str_ireplace("[[escola][estado]]", $this->retornarNomeEstado($dados), $documento);
            } else {
                $documento = str_ireplace("[[escola][" . $ind . "]]", $dados, $documento);
            }
        }
        $sql = "SHOW COLUMNS FROM `escolas`";
        $escolas = $this->retornarLinha($sql);
        foreach ($escolas as $ind => $dados) {
            $documento = str_ireplace("[[escola][" . $ind . "]]", $dados, $documento);
        }

        //Sindicatos
        $sql = "SELECT * FROM sindicatos
                WHERE idsindicato = " . $escola["idsindicato"];
        $sindicato = $this->retornarLinha($sql);
        foreach ($sindicato as $ind => $dados) {
            //$dados = utf8_decode($dados);
            $dados = htmlentities($dados);
            if ($ind == 'documento') {
                $documento = str_ireplace("[[sindicato][cnpj]]", formatar($dados, "cnpj"), $documento);
            } elseif ($ind == 'idlogradouro') {
                $documento = str_ireplace("[[sindicato][logradouro]]", $this->retornarNomeLogradouro($dados), $documento);
            } elseif ($ind == 'idcidade') {
                $documento = str_ireplace("[[sindicato][cidade]]", $this->retornarNomeCidade($dados), $documento);
            } elseif ($ind == 'idestado') {
                $documento = str_ireplace("[[sindicato][estado]]", $this->retornarNomeEstado($dados), $documento);
            } else {
                $documento = str_ireplace("[[sindicato][" . $ind . "]]", $dados, $documento);
            }
        }
        $documento = str_ireplace("[[sindicato][secretario][portaria]]", $sindicato["secretario_portaria"], $documento);
        $documento = str_ireplace("[[sindicato][diretor][portaria]]", $sindicato["diretor_portaria"], $documento);
        //Diretores
        $sql = "SELECT u.* FROM
                    usuarios_adm_sindicatos u
              WHERE u.idsindicato = " . $escola["idsindicato"];
        $diretores = $this->retornarLinha($sql);
        foreach ($diretores as $ind => $dados) {
            //$dados = utf8_decode($dados);
            $dados = htmlentities($dados);
            $documento = str_ireplace("[[sindicato][diretor][" . $ind . "]]", $dados, $documento);
        }
        /*
        //Secretários
        $sql = "SELECT u.* FROM
                    usuarios_adm u
                INNER JOIN sindicatos i
                ON (u.idusuario = i.idsecretario)
                WHERE i.idsindicato = " . $escola["idsindicato"];
        $secretarios = $this->retornarLinha($sql);
        foreach ($secretarios as $ind => $dados) {
            //$dados = utf8_decode($dados);
            $dados = htmlentities($dados);
            $documento = str_ireplace("[[sindicato][secretario][" . $ind . "]]", $dados, $documento);
        }
        */
        $sql = "SHOW COLUMNS FROM `usuarios_adm`";
        $secretarios = $this->retornarLinha($sql);
        foreach ($secretarios as $ind => $dados) {
            $documento = str_ireplace("[[sindicato][secretario][" . $ind . "]]", $dados, $documento);
            $documento = str_ireplace("[[sindicato][diretor][" . $ind . "]]", $dados, $documento);
        }
        $sql = "SHOW COLUMNS FROM `sindicatos`";
        $sindicatos = $this->retornarLinha($sql);
        foreach ($sindicatos as $ind => $dados) {
            //$dados = utf8_decode($dados);
            $dados = htmlentities($dados);
            $documento = str_ireplace("[[sindicato][" . $ind . "]]", $dados, $documento);
        }
        //Ofertas
        $sql = "SELECT * FROM ofertas
                WHERE idoferta = " . $matricula["idoferta"];
        $oferta = $this->retornarLinha($sql);
        foreach ($oferta as $ind => $dados) {
            //$dados = utf8_decode($dados);
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
        if ($matricula["idvendedor"]) {
            $sql = "SELECT * FROM vendedores
            WHERE idvendedor = " . $matricula["idvendedor"];
            $vendedor = $this->retornarLinha($sql);
            foreach ($vendedor as $ind => $dados) {
                //$dados = utf8_decode($dados);
                $dados = htmlentities($dados);
                if ($ind == 'documento') {
                    if ($aluno['documento_tipo'] == 'cpf') {
                        $documento = str_ireplace("[[atendente][documento]]", formatar($dados, "cpf"), $documento);
                    } else {
                        $documento = str_ireplace("[[atendente][documento]]", formatar($dados, "cnpj"), $documento);
                    }
                } elseif ($ind == 'rg_data_emissao') {
                    $documento = str_ireplace("[[atendente][rg_data_emissao]]", formataData($dados, "br", 0), $documento);
                } else {
                    $documento = str_ireplace("[[atendente][" . $ind . "]]", $dados, $documento);
                }
            }
        }
        $sql = "SHOW COLUMNS FROM `vendedores`";
        $vendedores = $this->retornarLinha($sql);
        foreach ($vendedores as $ind => $dados) {
            $documento = str_ireplace("[[atendente][" . $ind . "]]", $dados, $documento);
        }
        //Currículos
        $sql = "SELECT c.* FROM
                    curriculos c
                INNER JOIN ofertas_cursos_escolas ocp
                ON (c.idcurriculo = ocp.idcurriculo)
              WHERE ocp.idoferta = '" . $matricula["idoferta"] . "' AND
              ocp.idcurso = '" . $matricula["idcurso"] . "' AND
              ocp.idescola = '" . $matricula["idescola"] . "' AND
              ocp.ativo = 'S' ";
        $curriculos = $this->retornarLinha($sql);
        foreach ($curriculos as $ind => $dados) {
            //$dados = utf8_decode($dados);
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
                                iddeclaracao = " . $declaracao['iddeclaracao'] . " AND
                                iddeclaracao_imagem = " . $val . "";
                $linha = $this->retornarLinha($this->sql);
                $documento = str_replace("[[I][" . $val . "]]", "<div style=\"text-align:left; width:800px; text-align:center\"><img src=\"http://" . $_SERVER["SERVER_NAME"] . "/storage/declaracoes_imagens/" . $linha["servidor"] . "\" border=\"0\" /></div>", $documento);
            }
        }
        //$documento = htmlentities($documento);
        if ($documento) {
            $data_matricula = $this->retornarDataCadMatricula();
            $data_matricula = new DateTime($data_matricula);

            $this->sql = "INSERT INTO
                            matriculas_declaracoes
                        SET
                            data_cad = now(),
                            aluno_visualiza = 'N',
                            idmatricula = " . $this->id . ",
                            iddeclaracao = " . $declaracao["iddeclaracao"] . ",
                            arquivo_pasta = '" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "'";
            if ($this->modulo == "gestor") {
                $this->sql .= ", idusuario = '" . $this->idusuario . "'";
            }
            $salvar = $this->executaSql($this->sql);
            $iddeclaracaoMatricula = mysql_insert_id();
            $codigoValidacao = md5($iddeclaracaoMatricula);
            $this->sql = "UPDATE matriculas_declaracoes
                        SET cod_validacao = '" . $codigoValidacao . "'
                        WHERE idmatriculadeclaracao = " . $iddeclaracaoMatricula;
            $this->retornarLinha($this->sql);

            /*$this->sql = "INSERT INTO mensagens_alerta
                    SET tipo_alerta = 'documentospedagogicos',
                        iddocumento =". (int) $this->id.",
                        situacao_documento = 'I',
                        idmatricula = (".$this->id.")";
            $this->executaSql($this->sql);*/
            //Hach para imprimir na declaração
            $link_validacao = $GLOBALS['config']['urlSistema'] . '/validador</br></br>';
            $data_extenso = date("d") . " de " . $GLOBALS["meses_idioma"]["pt_br"][date("m")] . " de " . date("Y");
            $documento = str_ireplace("[[DECLARACAO][DATA_GERACAO]]", date("d/m/Y"), $documento);
            $documento = str_ireplace("[[DECLARACAO][QUEBRA_DE_PAGINA]]", "<div class='quebra_pagina'></div>", $documento);
            $documento = str_ireplace("[[DECLARACAO][DATA_GERACAO_EXTENSO]]", $data_extenso, $documento);
            $documento = str_ireplace("[[DECLARACAO][CAMPO_ADICIONAL_LOCAL]]", nl2br($this->post["campo_adicional_local"]), $documento);
            $documento = str_ireplace("[[DECLARACAO][DATA_PREVISTA_CONCLUSAO]]", $this->post["data_prevista_conclusao"], $documento);
            $documento = str_ireplace("[[DECLARACAO][CAMPO_ADICIONAL]]", nl2br($this->post["campo_adicional"]), $documento);
            $documento = str_ireplace("[[DECLARACAO][CAMPO_ADICIONAL2]]", nl2br($this->post["campo_adicional2"]), $documento);
            $documento = str_ireplace("[[DECLARACAO][CAMPO_ADICIONAL3]]", nl2br($this->post["campo_adicional3"]), $documento);
            $documento = str_ireplace("[[DECLARACAO][CAMPO_ADICIONAL4]]", nl2br($this->post["campo_adicional4"]), $documento);
            $documento = str_ireplace("[[DECLARACAO][LINK_VALIDACAO]]", $link_validacao, $documento);
            $documento = str_ireplace("[[DECLARACAO][CODIGO_VALIDACAO]]", $codigoValidacao, $documento);
            if ($salvar) {
                $pastaDeclaracoes = $_SERVER["DOCUMENT_ROOT"] . "/storage/matriculas_declaracoes/" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "/" . $this->id;
                if (!is_dir($pastaDeclaracoes)) {
                    @mkdir($pastaDeclaracoes, 0777, true);
                }

                $id = fopen($pastaDeclaracoes . "/" . $iddeclaracaoMatricula . ".html", "w");
                fwrite($id, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                     <html xmlns="http://www.w3.org/1999/xhtml">
                     <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                        <title>' . $declaracao["nome"] . '</title>
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
                $this->retorno["sucesso"] = true;
                $this->retorno["mensagem"] = "declaracao_gerada_sucesso";
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
            //$dados = utf8_decode($dados);
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
            //$dados = utf8_decode($dados);
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
            //$dados = utf8_decode($dados);
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
            //$dados = utf8_decode($dados);
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
        $sql = "SELECT * FROM sindicatos
            WHERE idsindicato = " . $escola["idsindicato"];
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
            //$dados = utf8_decode($dados);
            $dados = htmlentities($dados);
            $documento = str_ireplace("[[SINDICATO][" . $ind . "]]", $dados, $documento);
        }
        //Ofertas
        $sql = "SELECT * FROM ofertas
            WHERE idoferta = " . $matricula["idoferta"];
        $oferta = $this->retornarLinha($sql);
        foreach ($oferta as $ind => $dados) {
            //$dados = utf8_decode($dados);
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
            //$dados = utf8_decode($dados);
            $dados = htmlentities($dados);
            if ($ind == 'documento') {
                if ($aluno['documento_tipo'] == 'cpf') {
                    $documento = str_ireplace("[[atendente][documento]]", formatar($dados, "cpf"), $documento);
                } else {
                    $documento = str_ireplace("[[atendente][documento]]", formatar($dados, "cnpj"), $documento);
                }
            } elseif ($ind == 'rg_data_emissao') {
                $documento = str_ireplace("[[atendente][rg_data_emissao]]", formataData($dados, "br", 0), $documento);
            } else {
                $documento = str_ireplace("[[atendente][" . $ind . "]]", $dados, $documento);
            }
        }
        $sql = "SHOW COLUMNS FROM `vendedores`";
        $vendedores = $this->retornarLinha($sql);
        foreach ($vendedores as $ind => $dados) {
            $documento = str_ireplace("[[atendente][" . $ind . "]]", $dados, $documento);
        }
        //Currículos
        $sql = "SELECT c.* FROM
                curriculos c
            INNER JOIN ofertas_cursos_escolas ocp
            ON (c.idcurriculo = ocp.idcurriculo)
          WHERE ocp.idoferta = '" . $matricula["idoferta"] . "' AND
          ocp.idcurso = '" . $matricula["idcurso"] . "' AND
          ocp.idescola = '" . $matricula["idescola"] . "' AND
          ocp.ativo = 'S'";
        $curriculos = $this->retornarLinha($sql);
        foreach ($curriculos as $ind => $dados) {
            //$dados = utf8_decode($dados);
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
                if (!is_dir($pastaDeclaracoes)) {
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

    public function retornarHistoricosLinhas()
    {
        $this->sql = "SELECT * FROM matriculas_historicos WHERE idmatricula = " . $this->id;
        $this->limite = -1;
        $this->ordem = "desc";
        $this->ordem_campo = "idhistorico";
        return $this->retornarLinhas();
    }

    public function RetornarHistoricos()
    {
        $retorno = array();
        $historicos = $this->retornarHistoricosLinhas();
        foreach ($historicos as $historico) {
            $historico["modulo"] = "Sistema";
            if ($historico["idusuario"]) {
                $this->sql = "SELECT * FROM usuarios_adm WHERE idusuario = " . $historico["idusuario"];
                $historico["usuario"] = $this->retornarLinha($this->sql);
                $historico["modulo"] = "Gestor";
            } elseif ($historico["idpessoa"]) {
                $this->sql = "SELECT * FROM pessoas WHERE idpessoa = " . $historico["idpessoa"];
                $historico["usuario"] = $this->retornarLinha($this->sql);
                $historico["modulo"] = "Aluno";
            } elseif ($historico["idvendedor"]) {
                $this->sql = "SELECT * FROM vendedores WHERE idvendedor = " . $historico["idvendedor"];
                $historico["usuario"] = $this->retornarLinha($this->sql);
                $historico["modulo"] = "Vendedor";
            } elseif ($historico["iddevedor"]) {
                $this->sql = "SELECT * FROM pessoas WHERE idpessoa = " . $historico["iddevedor"];
                $historico["usuario"] = $this->retornarLinha($this->sql);
                $historico["modulo"] = "Devedor solidário";
            } elseif ($historico["idescola"]) {
                $this->sql = "SELECT *, nome_fantasia AS nome FROM escolas WHERE idescola = " . $historico["idescola"];
                $historico["usuario"] = $this->retornarLinha($this->sql);
                $historico["modulo"] = "CFC";
            } else {
                $historico["usuario"]["nome"] = "--";
            }
            switch ($historico["tipo"]) {
                case "situacao":
                    if ($historico["de"]) {
                        $this->sql = "SELECT * FROM matriculas_workflow WHERE idsituacao = " . $historico["de"];
                        $historico["situacao"]["de"] = $this->retornarLinha($this->sql);
                    }
                    $this->sql = "SELECT * FROM matriculas_workflow WHERE idsituacao = " . $historico["para"];
                    $historico["situacao"]["para"] = $this->retornarLinha($this->sql);
                    switch ($historico["acao"]) {
                        case "modificou":
                            if ($historico["situacao"]["de"]) {
                                $historico["descricao"] = "Modificou a situação da matrícula.<br>De <span class=\"label\" style=\"background-color:#" . $historico["situacao"]["de"]["cor_bg"] . "; color:#" . $historico["situacao"]["de"]["cor_nome"] . "\">" . $historico["situacao"]["de"]["nome"] . "</span> para <span class=\"label\" style=\"background-color:#" . $historico["situacao"]["para"]["cor_bg"] . "; color:#" . $historico["situacao"]["para"]["cor_nome"] . "\">" . $historico["situacao"]["para"]["nome"] . "</span>.";
                            } else {
                                $sql = 'select m.idusuario, m.idvendedor, ua.nome as nome_usuario, v.nome as nome_vendedor
                                            from matriculas m
                                            left join usuarios_adm ua on m.idusuario = ua.idusuario
                                            left join vendedores v on m.idvendedor = v.idvendedor
                                            where m.idmatricula = ' . $this->id;
                                $resultado = mysql_query($sql);
                                $matricula = mysql_fetch_assoc($resultado);
                                if ($matricula['idusuario']) {
                                    $historico["modulo"] = "Gestor";
                                    $historico['usuario']['nome'] = $matricula['nome_usuario'];
                                } else if ($matricula['idvendedor']) {
                                    $historico["modulo"] = "Vendedor";
                                    $historico['usuario']['nome'] = $matricula['nome_vendedor'];
                                }
                                $historico["descricao"] = "Modificou a situação da matrícula.<br>Para <span class=\"label\" style=\"background-color:#" . $historico["situacao"]["para"]["cor_bg"] . "; color:#" . $historico["situacao"]["para"]["cor_nome"] . "\">" . $historico["situacao"]["para"]["nome"] . "</span>.";
                            }
                            break;
                    }
                    break;
                case "data_matricula":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a data de matrícula.<br><span style=\"color:#666666\">De " . formataData($historico["de"], 'br', 0) . " para " . formataData($historico["para"], 'br', 0) . "</span>";
                            break;
                    }
                    break;
                case "data_conclusao":
                case "modificou":
                    $historico["descricao"] = "Modificou a data de conclusão.<br><span style=\"color:#666666\">";
                    ($historico["de"]) ?  $de = formataData($historico["de"], 'br', 0)  : $de = 'sem data' ;
                    ($historico["para"]) ?  $para = formataData($historico["para"], 'br', 0)  : $para = 'sem data' ;
                    $historico["descricao"] .= "De " . $de . " para " . $para . "</span>";
                    break;
                case "data_inicio_curso":
                case "modificou":
                    $historico["descricao"] = "Modificou a data de início.<br><span style=\"color:#666666\">";
                    ($historico["de"]) ?  $de = formataData($historico["de"], 'br', 0)  : $de = 'sem data' ;
                    ($historico["para"]) ?  $para = formataData($historico["para"], 'br', 0)  : $para = 'sem data' ;
                    $historico["descricao"] .= "De " . $de . " para " . $para . "</span>";
                    break;
                case "data_prolongada":
                case "modificou":
                    $historico["descricao"] = "Modificou a data de vencimento do ava.<br><span style=\"color:#666666\">";
                    ($historico["de"]) ?  $de = formataData($historico["de"], 'br', 0)  : $de = 'sem data' ;
                    ($historico["para"]) ?  $para = formataData($historico["para"], 'br', 0)  : $para = 'sem data' ;
                    $historico["descricao"] .= "De " . $de . " para " . $para . "</span>";
                    break;
                case "data_expedicao":
                case "modificou":
                    $historico["descricao"] = "Modificou a data de expedição.<br><span style=\"color:#666666\">";
                    ($historico["de"]) ?  $de = formataData($historico["de"], 'br', 0)  : $de = 'sem data' ;
                    ($historico["para"]) ?  $para = formataData($historico["para"], 'br', 0)  : $para = 'sem data' ;
                    $historico["descricao"] .= "De " . $de . " para " . $para . "</span>";
                    break;
                case "situacao_carteirinha":
                case "modificou":
                    $historico["descricao"] = "Modificou a situação da carteirinha.<br><span style=\"color:#666666\">";
                    if ($historico["de"]) {
                        $historico["descricao"] .= "De " . $GLOBALS["situacao_carteirinha_aluno"][$this->config["idioma_padrao"]][$historico["de"]] . " para " . $GLOBALS["situacao_carteirinha_aluno"][$this->config["idioma_padrao"]][$historico["para"]] . "</span>";
                    } else {
                        $historico["descricao"] .= "Para " . $GLOBALS["situacao_carteirinha_aluno"][$this->config["idioma_padrao"]][$historico["para"]] . "</span>";
                    }
                    break;
                    break;
                case "cancelar_tentativa_prova":
                case "cancelou":
                    $historico["descricao"] = "Cancelou a tentativa de prova #" . $historico["id"] . ".";
                    break;
                    break;
                case "data_solicitacao_carteirinha":
                case "modificou":
                    $historico["descricao"] = "Modificou a data de solicitação da carteirinha.<br><span style=\"color:#666666\">";
                    if ($historico["de"]) {
                        $historico["descricao"] .= "De " . formataData($historico["de"], 'br', 0) . " para " . formataData($historico["para"], 'br', 0) . "</span>";
                    } else {
                        $historico["descricao"] .= "Para " . formataData($historico["para"], 'br', 0) . "</span>";
                    }
                    break;
                    break;
                case "numero_contrato":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o número do contrato.<br><span style=\"color:#666666\">De " . $historico["de"] . " para " . $historico["para"] . "</span>";
                            break;
                    }
                    break;
                case "bolsa":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a situação de bolsa da matrícula.<br><span style=\"color:#666666\">De " . $GLOBALS["bolsaMatricula"][$this->config["idioma_padrao"]][$historico["de"]] . " para " . $GLOBALS["bolsaMatricula"][$this->config["idioma_padrao"]][$historico["para"]] . "</span>";
                            break;
                    }
                    break;
                case "solicitante":
                    if ($historico["de"]) {
                        $this->sql = "select nome FROM solicitantes_bolsas where idsolicitante = " . $historico["de"];
                        $historico["solicitante"]["de"] = $this->retornarLinha($this->sql);
                    }
                    if ($historico["para"]) {
                        $this->sql = "select nome FROM solicitantes_bolsas where idsolicitante = " . $historico["para"];
                        $historico["solicitante"]["para"] = $this->retornarLinha($this->sql);
                    }
                    switch ($historico["acao"]) {
                        case "modificou":
                            if ($historico["de"] && $historico["para"]) {
                                $historico["descricao"] = "Modificou o solicitante de bolsa.<br><span style=\"color:#666666\">De " . $historico["solicitante"]["de"]["nome"] . " para " . $historico["solicitante"]["para"]["nome"] . "</span>";
                            } elseif ($historico["de"]) {
                                $historico["descricao"] = "Retirou o solicitante de bolsa.<br><span style=\"color:#666666\">" . $historico["solicitante"]["de"]["nome"] . "</span>";
                            } else {
                                $historico["descricao"] = "Adicionou o solicitante de bolsa.<br><span style=\"color:#666666\">" . $historico["solicitante"]["para"]["nome"] . "</span>";
                            }
                            break;
                    }
                    break;
                case "valor_contrato":
                case "modificou":
                    $historico["descricao"] = "Modificou o valor do contrato.<br><span style=\"color:#666666\">De R$ " . number_format($historico["de"], 2, ",", ".") . " para R$ " . number_format($historico["para"], 2, ",", ".") . "</span>";
                    break;
                    break;
                case "renach":
                case "modificou":
                    $historico["descricao"] = "Modificou o Renach.<br><span style=\"color:#666666\">";
                    ($historico["de"]) ?  $de = $historico["de"] : $de = 'vazio' ;
                    ($historico["para"]) ?  $para = $historico["para"]  : $para = 'vazio' ;
                    $historico["descricao"] .= "De " . $de . " para " . $para . "</span>";
                    break;
                    break;
                case "data_inicio_certificado":
                case "modificou":
                    $historico["descricao"] = "Modificou a data do inicio do certificado.<br><span style=\"color:#666666\">De " . formataData($historico["de"], 'br', 0) . " para " . formataData($historico["para"], 'br', 0) . "</span>";
                    break;
                    break;
                case "data_final_certificado":
                case "modificou":
                    $historico["descricao"] = "Modificou a data final do certificado.<br><span style=\"color:#666666\">De " . formataData($historico["de"], 'br', 0) . " para " . formataData($historico["para"], 'br', 0) . "</span>";
                    break;
                    break;
                case "cupom_nota_fiscal":
                case "modificou":
                    $historico["descricao"] = "Modificou o cupom / nota fiscal.<br><span style=\"color:#666666\">";

                    if ($historico["de"])
                        $historico["descricao"] .= "De " . $historico["de"] . " ";
                    if ($historico["para"])
                        $historico["descricao"] .= " Para " . $historico["para"];

                    $historico["descricao"] .= "</span>";

                    break;
                    break;
                case "quantidade_parcelas":
                case "modificou":
                    $historico["descricao"] = "Modificou a quantidade de parcelas.<br><span style=\"color:#666666\">De " . number_format($historico["de"], 0) . " para " . number_format($historico["para"], 0) . "</span>";
                    break;
                    break;
                case "empresa":
                    if ($historico["de"]) {
                        $this->sql = "select nome FROM empresas where idempresa = " . $historico["de"];
                        $historico["empresa"]["de"] = $this->retornarLinha($this->sql);
                    }
                    if ($historico["para"]) {
                        $this->sql = "select nome FROM empresas where idempresa = " . $historico["para"];
                        $historico["empresa"]["para"] = $this->retornarLinha($this->sql);
                    }
                    switch ($historico["acao"]) {
                        case "modificou":
                            if ($historico["de"] && $historico["para"]) {
                                $historico["descricao"] = "Modificou a empresa associada a matrícula.<br><span style=\"color:#666666\">De " . $historico["empresa"]["de"]["nome"] . " para " . $historico["empresa"]["para"]["nome"] . "</span>";
                            } elseif ($historico["de"]) {
                                $historico["descricao"] = "Retirou a associação da empresa.<br><span style=\"color:#666666\">" . $historico["empresa"]["de"]["nome"] . "</span>";
                            } else {
                                $historico["descricao"] = "Adicionou a associação da empresa.<br><span style=\"color:#666666\">" . $historico["empresa"]["para"]["nome"] . "</span>";
                            }
                            break;
                    }
                    break;
                case "escola":
                    if ($historico["de"]) {
                        $this->sql = "select nome_fantasia FROM escolas where idescola = " . $historico["de"];
                        $historico["escola"]["de"] = $this->retornarLinha($this->sql);
                    }
                    if ($historico["para"]) {
                        $this->sql = "select nome_fantasia FROM escolas where idescola = " . $historico["para"];
                        $historico["escola"]["para"] = $this->retornarLinha($this->sql);
                    }
                    switch ($historico["acao"]) {
                        case "modificou":
                            if ($historico["de"] && $historico["para"]) {
                                $historico["descricao"] = "Modificou a escola associada a matrícula.<br><span style=\"color:#666666\">De " . $historico["escola"]["de"]["nome_fantasia"] . " para " . $historico["escola"]["para"]["nome_fantasia"] . "</span>";
                            } elseif ($historico["de"]) {
                                $historico["descricao"] = "Retirou a associação da escola.<br><span style=\"color:#666666\">" . $historico["escola"]["de"]["nome_fantasia"] . "</span>";
                            } else {
                                $historico["descricao"] = "Adicionou a associação da escola.<br><span style=\"color:#666666\">" . $historico["escola"]["para"]["nome_fantasia"] . "</span>";
                            }
                            break;
                    }
                    break;
                case "oferta":
                    if ($historico["de"]) {
                        $this->sql = "select nome FROM ofertas where idoferta = " . $historico["de"];
                        $historico["oferta"]["de"] = $this->retornarLinha($this->sql);
                    }
                    if ($historico["para"]) {
                        $this->sql = "select nome FROM ofertas where idoferta = " . $historico["para"];
                        $historico["oferta"]["para"] = $this->retornarLinha($this->sql);
                    }
                    switch ($historico["acao"]) {
                        case "modificou":
                            if ($historico["de"] && $historico["para"]) {
                                $historico["descricao"] = "Modificou a oferta associada à matrícula.<br><span style=\"color:#666666\">De " . $historico["oferta"]["de"]["nome"] . " para " . $historico["oferta"]["para"]["nome"] . "</span>";
                            }
                            break;
                    }
                    break;
                case "turma":
                    if ($historico["de"]) {
                        $this->sql = "select nome FROM ofertas_turmas where idturma = " . $historico["de"];
                        $historico["turma"]["de"] = $this->retornarLinha($this->sql);
                    }
                    if ($historico["para"]) {
                        $this->sql = "select nome FROM ofertas_turmas where idturma = " . $historico["para"];
                        $historico["turma"]["para"] = $this->retornarLinha($this->sql);
                    }
                    switch ($historico["acao"]) {
                        case "modificou":
                            if ($historico["de"] && $historico["para"]) {
                                $historico["descricao"] = "Modificou a turma associada à matrícula.<br><span style=\"color:#666666\">De " . $historico["turma"]["de"]["nome"] . " para " . $historico["turma"]["para"]["nome"] . "</span>";
                            }
                            break;
                    }
                    break;
                case "observacao":
                case "modificou":
                    if ($historico["de"] && $historico["para"]) {
                        $historico["descricao"] = "Modificou a observação da matrícula.<br><span style=\"color:#666666\">De " . $historico["de"] . " para " . $historico["para"] . "</span>";
                    } elseif ($historico["de"]) {
                        $historico["descricao"] = "Retirou a observação da matrícula.<br><span style=\"color:#666666\">" . $historico["de"] . "</span>";
                    } else {
                        $historico["descricao"] = "Adicionou a observação da matrícula.<br><span style=\"color:#666666\">" . $historico["para"] . "</span>";
                    }
                    break;
                    break;
                case "renach":
                case "modificou":
                    if ($historico["de"] && $historico["para"]) {
                        $historico["descricao"] = "Modificou o código do Número Renach.<br><span style=\"color:#666666\">De " . $historico["de"] . " para " . $historico["para"] . "</span>";
                    } elseif ($historico["de"]) {
                        $historico["descricao"] = "Retirou o código do Número Renach.<br><span style=\"color:#666666\">" . $historico["de"] . "</span>";
                    } else {
                        $historico["descricao"] = "Cadastrou o código do Número Renach.<br><span style=\"color:#666666\">" . $historico["para"] . "</span>";
                    }
                    break;
                    break;
                case "prova":
                    switch ($historico["acao"]) {
                        case "visualizou":
                            $historico["descricao"] = "Visualizou a prova para respondê-la.";
                            break;
                        case "respondeu":
                            $historico["descricao"] = "Respondeu a prova.";
                            break;
                    }
                case "matricula":
                    switch ($historico["acao"]) {
                        case "desaprovou_comercial":
                            $historico["descricao"] = "Removeu aprovação comercial da matrícula.";
                            break;
                        case "aprovou_comercial":
                            $historico["descricao"] = "Fez aprovação comercial da matrícula.";
                            break;
                        case "negativou":
                            $historico["descricao"] = "Negativou a matrícula.";
                            break;
                        case "desnegativou":
                            $historico["descricao"] = "Desnegativou a matrícula.";
                            break;
                        case "aprovou":
                            $historico["descricao"] = "Aprovou a matrícula.";
                            break;
                    }
                    break;
                case "data_negativacao":
                    switch ($historico["acao"]) {
                        case "modificou":
                            if (!$historico["de"])
                                $historico["de"] = "Vazio";
                            if (!$historico["para"])
                                $historico["para"] = "Vazio";
                            $historico["descricao"] = "Modificou a data de negativação.<br><span style=\"color:#666666\">De " . formataData($historico["de"], 'pt', 0) . " para " . formataData($historico["para"], 'pt', 0) . "</span>";
                            break;
                    }
                    break;
                case "associado":
                    $this->sql = "select
                          p.*
                        FROM
                          pessoas p
                          INNER JOIN matriculas_associados ma on (p.idpessoa = ma.idpessoa)
                        where
                          ma.idassociado = " . $historico["id"];
                    $historico["associado"] = $this->retornarLinha($this->sql);
                    switch ($historico["acao"]) {
                        case "cadastrou":
                            $historico["descricao"] = "Adicionou o(a) associado(a):<br><span style=\"color:#666666\">" . $historico["associado"]["nome"] . "</span>";
                            break;
                        case "removeu":
                            $historico["descricao"] = "Removeu o(a) associado(a):<br><span style=\"color:#666666\">" . $historico["associado"]["nome"] . "</span>";
                            break;
                    }
                    break;
                case "parcela":
                    $this->sql = "select * FROM contas where idconta = '" . $historico["id"] . "'";
                    $historico["conta"] = $this->retornarLinha($this->sql);
                    switch ($historico["acao"]) {
                        case "cadastrou":
                            $historico["descricao"] = "Adicionou a parcela:<br><span style=\"color:#666666\">" . $historico["conta"]['idconta'] . " - R$ " . number_format($historico["conta"]["valor"], 2, ",", ".") . " - " . formataData($historico["conta"]["data_vencimento"], 'br', 0) . "</span>";
                            break;
                        case "modificou":
                            $historico["descricao"] = "Modificou a parcela:<br><span style=\"color:#666666\">" . $historico["conta"]['idconta'] . " - R$ " . number_format($historico["conta"]["valor"], 2, ",", ".") . " - " . formataData($historico["conta"]["data_vencimento"], 'br', 0) . "</span>";
                            break;
                        case "renegociou":
                            $historico["descricao"] = "Renegociou a parcela:<br><span style=\"color:#666666\">" . $historico["conta"]['idconta'] . " - R$ " . number_format($historico["conta"]["valor"], 2, ",", ".") . " - " . formataData($historico["conta"]["data_vencimento"], 'br', 0) . "<br />Novas parcelas: " . $historico["conta"]["parcela_transferida"] . "</span>";
                            break;
                        case "transferiu":
                            $historico["descricao"] = "Transferiu a parcela:<br><span style=\"color:#666666\">" . $historico["id"] . " - R$ " . number_format($historico["conta"]["valor"], 2, ",", ".") . " - " . formataData($historico["conta"]["data_vencimento"], 'br', 0) . "<br />Nova parcela: " . $historico["conta"]['idconta_transferida'] . "</span>";
                            break;
                    }
                    break;
                case "parcela_situacao":
                    $this->sql = "select * FROM contas where idconta = " . $historico["id"];
                    $historico["conta"] = $this->retornarLinha($this->sql);
                    $this->sql = "select * FROM contas_workflow where idsituacao = " . $historico["de"];
                    $historico["conta"]["de"] = $this->retornarLinha($this->sql);
                    $this->sql = "select * FROM contas_workflow where idsituacao = " . $historico["para"];
                    $historico["conta"]["para"] = $this->retornarLinha($this->sql);
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a situação da parcela: <span style=\"color:#666666\">" . $historico["conta"]['idconta'] . "</style>.<br>De <span class=\"label\" style=\"background-color:#" . $historico["conta"]["de"]["cor_bg"] . "; color:#" . $historico["conta"]["de"]["cor_nome"] . "\">" . $historico["conta"]["de"]["nome"] . "</span> para <span class=\"label\" style=\"background-color:#" . $historico["conta"]["para"]["cor_bg"] . "; color:#" . $historico["conta"]["para"]["cor_nome"] . "\">" . $historico["conta"]["para"]["nome"] . "</span>.";
                            break;
                    }
                    break;
                case "arquivo":
                    $arquivo = $this->retornarLinha(sprintf('SELECT * FROM matriculas_arquivos WHERE idarquivo = %d', $historico['id']));
                    switch ($historico['acao']) {
                        case 'cadastrou':
                            if ($arquivo['arquivo_nome']) {
                                $historico["descricao"] = sprintf('Cadastrou o arquivo <strong>%s</strong> à pasta virtual, <strong>%s</strong>', $arquivo['arquivo_nome'], $arquivo['nome_arquivo']);
                            } else {
                                $historico["descricao"] = sprintf('Cadastrou um arquivo na pasta virtual, nome:<strong>%s</strong>', $arquivo['nome_arquivo']);
                            }
                            break;
                        case 'enviou':
                            $historico["descricao"] = sprintf('Enviou o arquivo <strong>%s</strong> à pasta virtual, nome:<strong>%s</strong>', $arquivo['arquivo_nome'], $arquivo['nome_arquivo']);
                            break;
                        case 'modificou':
                            $historico["descricao"] = "Modificou o protocolo do arquivo: <strong>" . $arquivo['arquivo_nome'] . "</strong><br><span style=\"color:#666666\">De: " . $historico["de"] . " Para: " . $historico["para"] . "</span>";
                            break;
                        case 'removeu':
                            $historico["descricao"] = sprintf('Removeu o arquivo <strong>%s</strong> da pasta virtual', $arquivo['arquivo_nome']);
                            break;
                    }
                    break;
                case "documento":
                    $this->sql = "select
                          md.*,
                          td.nome as tipo
                        FROM
                          matriculas_documentos md
                          INNER JOIN tipos_documentos td on (md.idtipo = td.idtipo)
                        where
                          iddocumento = " . $historico["id"];
                    $historico["documento"] = $this->retornarLinha($this->sql);
                    switch ($historico["acao"]) {
                        case "cadastrou":
                            $historico["descricao"] = "Cadastrou o documento: <strong>" . $historico["documento"]["arquivo_nome"] . "</strong>";
                            break;
                        case "modificou":
                            // Busca a disciplina
                            if ($historico["de"]) {
                                $this->sql = "select * from disciplinas where iddisciplina='" . $historico["id"] . "'";
                                $disciplina = $this->retornarLinha($this->sql);
                            } else {
                                $historico["de"] = 'Vazio';
                            }
                            $historico["descricao"] = "Modificou o protocolo do documento: <strong>" . $historico["documento"]["tipo"] . "</strong><br><span style=\"color:#666666\">De: " . $historico["de"] . " Para: " . $historico["para"] . "</span>";
                            break;
                        case "removeu":
                            $historico["descricao"] = "Removeu o documento: <strong>" . $historico["documento"]["arquivo_nome"] . "</strong>";
                            break;
                        case "aprovou":
                            $historico["descricao"] = "Aprovou o documento: <strong>" . $historico["documento"]["arquivo_nome"] . "</strong>";
                            break;
                        case "reprovou":
                            $historico["descricao"] = "Reprovou o documento: <strong>" . $historico["documento"]["arquivo_nome"] . "</strong>";
                            break;
                        case "enviou":
                            $historico["descricao"] = "Enviou o arquivo do documento: <strong>" . $historico["documento"]["arquivo_nome"] . "</strong>";
                            break;
                    }
                    if ($historico["acao"] != "modificou") {
                        if (!$historico["documento"]["associacao"])
                            $historico["documento"]["associacao"] = "Aluno";
                        $historico["descricao"] .= "<br><span style=\"color:#666666\"> Tipo: " . $historico["documento"]["tipo"] . " - Associação: " . $historico["documento"]["associacao"] . ".</span>";
                    }
                    break;
                case "biometria_liberada":
                    $historico["descricao"] = sprintf('Modificou a liberação da biometria de <strong>%s</strong> para <strong>%s</strong>', $historico['de'] == 'S' ? 'Sim' : 'Não', $historico['para'] == 'S' ? 'Sim' : 'Não');
                    break;
                case "contrato":
                    $this->sql = "select
                          mc.*,
                          ct.nome as tipo,
                          c.nome as contrato
                        FROM
                          matriculas_contratos mc
                          left outer join contratos c on (mc.idcontrato = c.idcontrato)
                          INNER JOIN contratos_tipos ct on (c.idtipo = ct.idtipo or mc.idtipo = ct.idtipo)
                        WHERE
                          mc.idmatricula_contrato = '" . $historico["id"] . "'";
                    $historico["contrato"] = $this->retornarLinha($this->sql);
                    if ($historico["contrato"]["arquivo"]) {
                        $historico["contrato"]["contrato"] = $historico["contrato"]["arquivo"];
                    }
                    switch ($historico["acao"]) {
                        case "cadastrou":
                            $historico["descricao"] = "Adicionou o contrato: <strong>" . $historico["contrato"]["contrato"] . "</strong><br><span style=\"color:#666666\"> Tipo: " . $historico["contrato"]["tipo"] . " - Número: " . $historico["contrato"]["idmatricula_contrato"] . ".</span>";
                            break;
                        case "validou":
                            $historico["descricao"] = "Validou o contrato: <strong>" . $historico["contrato"]["contrato"] . "</strong><br><span style=\"color:#666666\"> Tipo: " . $historico["contrato"]["tipo"] . " - Número: " . $historico["contrato"]["idmatricula_contrato"] . ".</span>";
                            break;
                        case "desvalidou":
                            $historico["descricao"] = "Não validou o contrato: <strong>" . $historico["contrato"]["contrato"] . "</strong><br><span style=\"color:#666666\"> Tipo: " . $historico["contrato"]["tipo"] . " - Número: " . $historico["contrato"]["idmatricula_contrato"] . ".</span>";
                            break;
                        case "assinou":
                            $historico["descricao"] = "Informou a assinatura do contrato: <strong>" . $historico["contrato"]["contrato"] . "</strong><br><span style=\"color:#666666\"> Tipo: " . $historico["contrato"]["tipo"] . " - Número: " . $historico["contrato"]["idmatricula_contrato"] . ".</span>";
                            break;
                        case "desassinou":
                            $historico["descricao"] = "Informou contrato não assinado: <strong>" . $historico["contrato"]["contrato"] . "</strong><br><span style=\"color:#666666\"> Tipo: " . $historico["contrato"]["tipo"] . " - Número: " . $historico["contrato"]["idmatricula_contrato"] . ".</span>";
                            break;
                        case "cancelou":
                            $historico["descricao"] = "Cancelou o contrato: <strong>" . $historico["contrato"]["contrato"] . "</strong><br><span style=\"color:#666666\"> Tipo: " . $historico["contrato"]["tipo"] . " - Número: " . $historico["contrato"]["idmatricula_contrato"] . ".</span>";
                            break;
                        case "descancelou":
                            $historico["descricao"] = "Retirou o cancelamento do contrato: <strong>" . $historico["contrato"]["contrato"] . "</strong><br><span style=\"color:#666666\"> Tipo: " . $historico["contrato"]["tipo"] . " - Número: " . $historico["contrato"]["idmatricula_contrato"] . ".</span>";
                            break;
                        case "enviou":
                            $historico["descricao"] = "Enviou o e-mail para o aluno avisando que um novo contrato foi gerado.";
                            break;
                    }
                    break;
                case "notas":
                    switch ($historico["acao"]) {
                        case "cadastrou":
                            // Busca a disciplina
                            if ($historico["de"]) {
                                $this->sql = "select * from disciplinas where iddisciplina='" . $historico["de"] . "'";
                                $disciplina = $this->retornarLinha($this->sql);
                            }
                            $historico["descricao"] = "Lançou nota na matrícula.<br><span style=\"color:#666666\">Disciplina: " . $disciplina["nome"] . " | Nota: " . $historico["para"] . "</span>";
                            break;
                        case "removeu":
                            // Busca a disciplina
                            if ($historico["de"]) {
                                $this->sql = "select * from disciplinas where iddisciplina='" . $historico["de"] . "'";
                                $disciplina = $this->retornarLinha($this->sql);
                            }
                            $historico["descricao"] = "Removeu nota da matrícula.<br><span style=\"color:#666666\">Disciplina: " . $disciplina["nome"] . " | Nota: " . $historico["para"] . "</span>";
                            break;
                        case "modificou":
                            // Busca a disciplina
                            if ($historico["de"]) {
                                $this->sql = "select * from disciplinas where iddisciplina='" . $historico["id"] . "'";
                                $disciplina = $this->retornarLinha($this->sql);
                            }
                            $historico["descricao"] = "Modificou nota na matrícula.<br><span style=\"color:#666666\">Disciplina: " . $disciplina["nome"] . " | De: " . $historico["de"] . " Para: " . $historico["para"] . "</span>";
                            break;
                    }
                    break;
                case "declaracao":
                    $this->sql = "select
                          md.*,
                          dt.nome as tipo,
                          d.nome as declaracao
                        FROM
                          matriculas_declaracoes md
                          left outer join declaracoes d on (md.iddeclaracao = d.iddeclaracao)
                          INNER JOIN declaracoes_tipos dt on (d.idtipo = dt.idtipo or md.idtipo = dt.idtipo)
                        where
                          md.idmatriculadeclaracao = " . $historico["id"];
                    $historico["declaracao"] = $this->retornarLinha($this->sql);
                    if ($historico["declaracao"]["arquivo"]) {
                        $historico["declaracao"]["declaracao"] = $historico["declaracao"]["arquivo"];
                    }
                    switch ($historico["acao"]) {
                        case "cadastrou":
                            $historico["descricao"] = "Gerou a declaração: <strong>" . $historico["declaracao"]["declaracao"] . "</strong><br><span style=\"color:#666666\"> Tipo: " . $historico["declaracao"]["tipo"] . " - Número: " . $historico["declaracao"]["idmatriculadeclaracao"] . ".</span>";
                            break;
                    }
                    break;
                case "mensagem":
                    $this->sql = "select * FROM matriculas_mensagens where idmensagem = '" . $historico["id"] . "'";
                    $historico["mensagem"] = $this->retornarLinha($this->sql);
                    switch ($historico["acao"]) { //tamanhoTexto(100,$historico["mensagem"]["mensagem"],true,false)
                        case "cadastrou":
                            $historico["descricao"] = "Cadastrou a mensagem:<br><span style=\"color:#666666\">\"" . strip_tags($historico["mensagem"]["mensagem"]) . "\"</span>";
                            break;
                        case "modificou":
                            $historico['descricao'] = "Modificou a situação da mensagem <strong># {$historico['id']}</strong>:<br />";
                            $historico['descricao'] .= sprintf("De <i>exibir no diploma <strong>%s</strong> </i> para <i>exibir no diploma <strong>%s</strong></i>", ($historico['para'] == 'N') ? 'Sim' : 'Não', ($historico['para'] == 'N') ? 'Não' : 'Sim');
                            break;
                        case "removeu":
                            $historico["descricao"] = "Removeu a mensagem:<br><span style=\"color:#666666\">\"" . strip_tags($historico["mensagem"]["mensagem"]) . "\"</span>";
                            break;
                    }
                    break;
                case "nome_aluno":
                case "modificou":
                    $historico["descricao"] = "Modificou o nome do aluno.<br><span style=\"color:#666666\">De " . $historico["de"] . " para " . $historico["para"] . "</span>";
                    break;
                    break;
                case "email_aluno":
                case "modificou":
                    $historico["descricao"] = "Modificou o e-mail do aluno.<br><span style=\"color:#666666\">De " . $historico["de"] . " para " . $historico["para"] . "</span>";
                    break;
                    break;
                case "cep_aluno":
                case "modificou":
                    $historico["descricao"] = "Modificou o CEP do aluno.<br><span style=\"color:#666666\">De " . $historico["de"] . " para " . $historico["para"] . "</span>";
                    break;
                    break;
                case "logradouro_aluno":
                    $this->sql = "select * from logradouros where idlogradouro = '" . $historico["de"] . "'";
                    $logradouro['de'] = $this->retornarLinha($this->sql);
                    $this->sql = "select * from logradouros where idlogradouro = '" . $historico["para"] . "'";
                    $logradouro['para'] = $this->retornarLinha($this->sql);
                case "modificou":
                    $historico["descricao"] = "Modificou o logradouro do endereço do aluno.<br><span style=\"color:#666666\">De " . $logradouro["de"]['nome'] . " para " . $logradouro["para"]['nome'] . "</span>";
                    break;
                    break;
                case "endereco_aluno":
                case "modificou":
                    $historico["descricao"] = "Modificou o endereco do aluno.<br><span style=\"color:#666666\">De " . $historico["de"] . " para " . $historico["para"] . "</span>";
                    break;
                    break;
                case "bairro_aluno":
                case "modificou":
                    $historico["descricao"] = "Modificou o bairro do endereço do aluno.<br><span style=\"color:#666666\">De " . $historico["de"] . " para " . $historico["para"] . "</span>";
                    break;
                    break;
                case "numero_aluno":
                case "modificou":
                    $historico["descricao"] = "Modificou o numero do endereço do aluno.<br><span style=\"color:#666666\">De " . $historico["de"] . " para " . $historico["para"] . "</span>";
                    break;
                    break;
                case "complemento_aluno":
                case "modificou":
                    if ($historico["de"] && $historico["para"]) {
                        $historico["descricao"] = "Modificou o complemento do endereço do aluno.<br><span style=\"color:#666666\">De " . $historico["de"] . " para " . $historico["para"] . "</span>";
                    } elseif ($historico["de"]) {
                        $historico["descricao"] = "Retirou o complemento do endereço do aluno.<br><span style=\"color:#666666\">" . $historico["de"] . "</span>";
                    } else {
                        $historico["descricao"] = "Cadastrou o complemento do endereço do aluno.<br><span style=\"color:#666666\">" . $historico["para"] . "</span>";
                    }
                    break;
                    break;
                case "estado_aluno":
                    $this->sql = "select * from estados where idestado = '" . $historico["de"] . "'";
                    $estado['de'] = $this->retornarLinha($this->sql);
                    $this->sql = "select * from estados where idestado = '" . $historico["para"] . "'";
                    $estado['para'] = $this->retornarLinha($this->sql);
                case "modificou":
                    $historico["descricao"] = "Modificou o estado do endereço do aluno.<br><span style=\"color:#666666\">De " . $estado["de"]['nome'] . " para " . $estado["para"]['nome'] . "</span>";
                    break;
                    break;
                case "cidade_aluno":
                    $this->sql = "select * from cidades where idcidade = '" . $historico["de"] . "'";
                    $cidade['de'] = $this->retornarLinha($this->sql);
                    $this->sql = "select * from cidades where idcidade = '" . $historico["para"] . "'";
                    $cidade['para'] = $this->retornarLinha($this->sql);
                case "modificou":
                    $historico["descricao"] = "Modificou a cidade do endereço do aluno.<br><span style=\"color:#666666\">De " . $cidade["de"]['nome'] . " para " . $cidade["para"]['nome'] . "</span>";
                    break;
                    break;
                case "permissao_aprovacao":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $sim_nao = array(
                                '' => 'Vazio',
                                'S' => 'Sim',
                                'N' => 'Não'
                            );
                            $historico["descricao"] = "Modificou a premissão de aprovação da matrícula.<br><span style=\"color:#666666\">De " . $sim_nao[$historico["de"]] . " para " . $sim_nao[$historico["para"]] . "</span>";
                            break;
                    }
                    break;
                case "data_registro":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a data de registro.<br><span style=\"color:#666666\">";
                            ($historico["de"]) ?  $de = formataData($historico["de"], 'br', 0)  : $de = 'sem data' ;
                            ($historico["para"]) ?  $para = formataData($historico["para"], 'br', 0)  : $para = 'sem data' ;
                            $historico["descricao"] .= "De " . $de . " para " . $para . "</span>";
                            break;
                    }
                    break;
                case "combo":
                    switch ($historico["acao"]) {
                        case "modificou":
                            if ($historico["de"] == 'S') {
                                $historico["descricao"] = "Vinculou a um combo.<br>    <span style=\"color:#666666\">
                                                                </span>";
                            } else {
                                $historico["descricao"] = "Desvinculou um combo.<br>   <span style=\"color:#666666\">
                                                                </span>";
                            }

                            break;
                    }
                    break;
                case "combo_matricula":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o Combo da Matrícula.<br><span style=\"color:#666666\">";
                            if ($historico["de"] and $historico["para"]) {
                                $historico["descricao"] .= "De Matrícula " . $historico["de"] . " para " . $historico["para"] . "</span>";
                            } else if ($historico["de"] and !$historico["para"]) {
                                $historico["descricao"] = "Desvinculou combo.<br></span>";
                                $historico["descricao"] .= "De " . $historico["de"] . "</span>";
                            } else {
                                $historico["descricao"] = "Vinculou o Combo na Matrícula.<br><span style=\"color:#666666\">";

                                $historico["descricao"] .= "Para " . $historico["para"] . "</span>";
                            }
                            break;
                    }
                    break;
                case "vendedor":
                    $this->sql = "select * from vendedores where idvendedor = '" . $historico["de"] . "'";
                    $vendedor['de'] = $this->retornarLinha($this->sql);
                    $this->sql = "select * from vendedores where idvendedor = '" . $historico["para"] . "'";
                    $vendedor['para'] = $this->retornarLinha($this->sql);
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o(a) atendente da matrícula.<br><span style=\"color:#666666\">De " . $vendedor["de"]['nome'] . " para " . $vendedor["para"]['nome'] . "</span>";
                            break;
                    }
                    break;
                case "forma_pagamento":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a forma de pagamento.<br><span style=\"color:#666666\">";
                            if ($historico["de"]) {
                                $historico["descricao"] .= "De " . $GLOBALS["forma_pagamento_conta"][$this->config["idioma_padrao"]][$historico["de"]] . " para " . $GLOBALS["forma_pagamento_conta"][$this->config["idioma_padrao"]][$historico["para"]] . "</span>";
                            } else {
                                $historico["descricao"] .= "Para " . $GLOBALS["forma_pagamento_conta"][$this->config["idioma_padrao"]][$historico["para"]] . "</span>";
                            }
                            break;
                    }
                    break;
                case "idbandeira":
                    $this->sql = "select * from bandeiras_cartoes where idbandeira = '" . $historico["de"] . "'";
                    $bandeira['de'] = $this->retornarLinha($this->sql);
                    if (!$bandeira['de'])
                        $bandeira['de']['nome'] = "Vazio";
                    $this->sql = "select * from bandeiras_cartoes where idbandeira = '" . $historico["para"] . "'";
                    $bandeira['para'] = $this->retornarLinha($this->sql);
                    if (!$bandeira['para'])
                        $bandeira['para']['nome'] = "Vazio";
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a bandeira do cartão.<br><span style=\"color:#666666\">De " . $bandeira["de"]['nome'] . " para " . $bandeira["para"]['nome'] . "</span>";
                            break;
                    }
                    break;
                case "autorizacao_cartao":
                    if (!$historico["de"])
                        $historico["de"] = "Vazio";
                    if (!$historico["para"])
                        $historico["para"] = "Vazio";
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a autorização do cartão.<br><span style=\"color:#666666\">De " . $historico["de"] . " para " . $historico["para"] . "</span>";
                            break;
                    }
                    break;
                case "data_comissao":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a data de comissão.<br><span style=\"color:#666666\">De " . formataData($historico["de"], 'br', 0) . " para " . formataData($historico["para"], 'br', 0) . "</span>";
                            break;
                    }
                    break;
                case "porcentagem_manual":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a porcentagem do aluno.<br><span style=\"color:#666666\">De " . number_format($historico["de"], 2, ',', '.') . " para " . number_format($historico["para"], 2, ',', '.') . "</span>";
                            break;
                    }
                    break;
                case 'detran_situacao':
                    switch ($historico['acao']) {
                        case 'modificou':
                            $historico['descricao'] = 'Modificou a situação no detran.<br />
                                <span style="color:#666666">
                                    De ' . $GLOBALS['situacaoDetran'][$this->config['idioma_padrao']][$historico['de']] . '
                                    para ' . $GLOBALS['situacaoDetran'][$this->config['idioma_padrao']][$historico['para']] . '
                                </span>';
                            break;
                        case 'detran_nao_respondeu':
                            $historico['descricao'] = '404 - Busca da situação no detran falhou.';
                            break;
                    }
                    break;
                case 'detran_creditos':
                    switch ($historico['acao']) {
                        case 'modificou':
                            $historico['descricao'] = 'Modificou créditos enviados para o detran.<br />
                                <span style="color:#666666">
                                    De ' . $GLOBALS['sim_nao'][$this->config['idioma_padrao']][$historico['de']] . '
                                    para ' . $GLOBALS['sim_nao'][$this->config['idioma_padrao']][$historico['para']] . '
                                </span>';
                            break;
                        case 'detran_nao_respondeu':
                            $historico['descricao'] = '404 - Envio dos créditos para o detran falhou.';
                            break;
                        case 'solicitou':
                            $historico['descricao'] = 'Solicitou envio/reenvio dos créditos para o detran.';
                            break;
                    }
                    break;
                case 'detran_certificado':
                    switch ($historico['acao']) {
                        case 'modificou':
                            $historico['descricao'] = 'Modificou certificado enviado para o detran.<br />
                                <span style="color:#666666">
                                    De ' . $GLOBALS['sim_nao'][$this->config['idioma_padrao']][$historico['de']] . '
                                    para ' . $GLOBALS['sim_nao'][$this->config['idioma_padrao']][$historico['para']] . '
                                </span>';
                            break;
                        case 'detran_nao_respondeu':
                            $historico['descricao'] = '404 - Envio do certificado para o detran falhou.';
                            break;
                        case 'solicitou':
                            $historico['descricao'] = 'Solicitou envio/reenvio de certificado para o detran.';
                            break;
                    }
                    break;
                case 'detran_cancelamento':
                    switch ($historico['acao']) {
                        case 'solicitou':
                            $historico['descricao'] = 'Solicitou cancelamento da matrícula na base do detran.';
                            break;
                    }
                    break;
                case 'detran_importacao':
                    switch ($historico['acao']) {
                        case 'solicitou':
                            $historico['descricao'] = 'Solicitou importação do aluno na base do detran.';
                            break;
                    }
                    break;
                case 'detran_finalizar':
                    switch ($historico['acao']) {
                        case 'modificou':
                            $historico['descricao'] = 'Modificou curso finalizado para o detran.<br />
                                <span style="color:#666666">
                                    De ' . $GLOBALS['sim_nao'][$this->config['idioma_padrao']][$historico['de']] . '
                                    para ' . $GLOBALS['sim_nao'][$this->config['idioma_padrao']][$historico['para']] . '
                                </span>';
                            break;
                        case 'detran_nao_respondeu':
                            $historico['descricao'] = '404 - Envio do certificado para o detran falhou.';
                            break;
                    }
                    break;
                case 'data_primeiro_acesso':
                    switch ($historico['acao']) {
                        case 'cadastrou':
                            $historico['descricao'] = 'Cadastrou a data de primeiro acesso.<br />
                                <span style="color:#666666">' . formataData($historico['para'], 'br', 0) . '
                                </span>';
                            break;
                    }
                    break;
                case 'tentativas_prova':
                    $historico['descricao'] = "O usuário <strong>{$historico["usuario"]["nome"]}</strong> zerou o total de tentativas de prova dessa matrícula.";
                    break;
                case 'zerar_tentativas_prova':
                    $historico['descricao'] = "O usuário <strong>{$historico["usuario"]["nome"]}</strong> zerou a tentativa #{$historico["id"]} de prova dessa matrícula.";
                    break;
                case 'faturada':
                    $historico['descricao'] = "O usuário <strong>{$historico["usuario"]["nome"]}</strong> Trocou o campo Faturada de " . $GLOBALS["cupom_sim_nao"][$this->config['idioma_padrao']][$historico['de']] . " para " . $GLOBALS["cupom_sim_nao"][$this->config['idioma_padrao']][$historico['para']] . ".";
                    break;
                case 'limite_datavalid':
                    $historico["descricao"] = "Modificou as tentativas máximas para o Datavalid. <br><span style=\"color:#666666\">";
                    $historico["descricao"] .="De ". $historico['de'] . " para  " . $historico['para'] . "</span>";
                    break;

            }
            if ($historico["tipo"] != 'combo') {
                $retorno[] = $historico;
            }
        }
        return $retorno;
    }

    public function retornarHistoricoTabela($historicos, $idioma)
    {
        $retorno = '
    <table cellpadding="5" cellspacing="0" class="table table-bordered table-condensed tabelaSemHover" width="900" style="width:900px;">
     <tr>
      <td width="100" bgcolor="#F4F4F4"><strong>' . $idioma["historico_matricula_modulo"] . '</strong></td>
      <td width="200" bgcolor="#F4F4F4"><strong>' . $idioma["historico_matricula_usuario"] . '</strong></td>
      <td width="140" bgcolor="#F4F4F4"><strong>' . $idioma["historico_matricula_data"] . '</strong></td>
      <td bgcolor="#F4F4F4"><strong>' . $idioma["historico_matricula_descricao"] . '</strong></td>
  </tr>
  <tbody>
      <tr>
        <td colspan="4" style="padding:0px;">
         <div style="height:400px; overflow:auto;">
           <table border="0" cellspacing="0" width="100%">';
        foreach ($historicos as $historico) {
            $retorno .= '
              <tr>
               <td width="100">' . $historico["modulo"] . '</td>
               <td width="200">' . $historico["usuario"]["nome"] . '</span></td>
               <td width="140">' . formataData($historico["data_cad"], 'br', 1) . '<br /><span style="color:#999;">' . $idioma["historico_matricula_id"] . ' ' . $historico["idhistorico"] . '</span></td>
               <td width=""> ' . $historico["descricao"] . '</td>
           </tr>';
        }
        $retorno .= '
   </table>
</div>
</td>
</tr>
</tbody>
</table>';
        return $retorno;
    }

    public function RetornarTodosMatriculas()
    {
        $this->sql = "SELECT
          " . $this->campos . "
         FROM
           matriculas m
           INNER JOIN pessoas p ON m.idpessoa = p.idpessoa ";
        if ($this->idusuario)
            $this->sql .= "
                    INNER JOIN escolas po on (m.idescola = po.idescola)
                    INNER JOIN usuarios_adm ua on ua.idusuario = " . $this->idusuario . "
                    left join usuarios_adm_sindicatos uai on po.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario    ";
        $this->sql .= " WHERE m.ativo = 'S'";
        if ($this->idusuario)
            $this->sql .= " and (ua.gestor_sindicato = 'S' or uai.idusuario is not null) ";
        $this->limite = -1;
        $this->ordem = "ASC";
        $this->ordem_campo = "idmatricula";
        $this->groupby = "idmatricula";
        return $this->retornarLinhas();
    }

    public function listarTotalMatriculas($idsindicato = false, $idcurso = false, $idregiao = false, $idescola = false)
    {
        $this->sql = 'select
                         count(m.idmatricula) as total
                     from
                         matriculas m ';

        if ($idregiao)
            $this->sql .= ' inner join sindicatos i on m.idsindicato = i.idsindicato
                            left outer join estados e on i.idestado = e.idestado';

        $this->sql .= ' where
                         m.ativo = "S"';
        if ($_SESSION["adm_gestor_sindicato"] <> "S" && $this->url[0] != "cfc")
            $this->sql .= ' and m.idsindicato in (' . $_SESSION["adm_sindicatos"] . ')';
        if ($idsindicato)
            $this->sql .= ' and m.idsindicato = ' . $idsindicato;
        if ($idcurso)
            $this->sql .= ' and m.idcurso = ' . $idcurso;
        if ($idregiao)
            $this->sql .= ' and e.idregiao = ' . $idregiao;
        if ($idescola)
            $this->sql .= ' and m.idescola = ' . $idescola;

        $dados = $this->retornarLinha($this->sql);
        return $dados['total'];
    }

    public function totalMatriculas20Dias($idsindicato = false, $idcurso = false, $idregiao = false, $idescola = false)
    {

        $sql = 'select
                    date_format(m.data_cad,"%d/%m/%Y") as data,
                    count(m.idmatricula) as total
                from
                    matriculas m
                    inner join matriculas_workflow mw on m.idsituacao = mw.idsituacao';
        if ($idregiao)
            $sql .= ' inner join sindicatos i on m.idsindicato = i.idsindicato
                      left outer join estados e on i.idestado = e.idestado';
        $sql .= ' where
                     m.data_cad >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 19, date('Y'))) . '" and
                     mw.ativa = "S" and
                     m.ativo = "S" and
                     mw.ativo = "S"';

        if ($_SESSION["adm_gestor_sindicato"] <> "S" && $this->url[0] != "cfc")
            $sql .= " and m.idsindicato in (" . $_SESSION["adm_sindicatos"] . ")";
        if ($idsindicato)
            $sql .= " and m.idsindicato = " . $idsindicato;
        if ($idcurso)
            $sql .= " and m.idcurso = " . $idcurso;
        if ($idregiao)
            $sql .= ' and e.idregiao = ' . $idregiao;
        if ($idescola)
            $sql .= ' and m.idescola = ' . $idescola;

        $sql .= " group by
                    data
                order by
                    m.data_cad asc
                limit 20";

        $query = $this->executaSql($sql);
        while ($linha = mysql_fetch_assoc($query)) {
            $valores[$linha['data']] = $linha['total'];
        }

        return $valores;
    }

    public function atualizaPorcentagemAva($idmatricula, $porcentagem_manual, $idava = null)
    {
        $this->sql = 'UPDATE
                            matriculas_avas_porcentagem
                        SET
                            porcentagem_manual = "' . str_replace(',', '.', $porcentagem_manual) . '"
                        WHERE
                            idmatricula = "' . (int)$idmatricula . '"';
        return $this->executaSql($this->sql);
    }

    public function editarDadosMatricula()
    {
        $this->retorno = array();
        $erro = array();

        //Bolsa é sempre não
        $this->post["bolsa"] = "N";

        if (!$this->post["idvendedor"]) {
            $this->post["idvendedor"] = 'NULL';
        }
        if ($this->post["data_registro"]) {
            $this->post["data_registro"] = "'" . formataData($this->post["data_registro"], "en", 0) . "'";
        } else {
            $this->post["data_registro"] = "NULL";
        }
        //$this->post['possui_financeiro']=='N';
        //if($this->post['possui_financeiro']=='S' && $this->modulo != 'cfc'){
        if ($this->post["forma_pagamento"] && $this->post["bolsa"] != "S") {
            if ($this->post["forma_pagamento"] == 2 || $this->post["forma_pagamento"] == 3) {
                if (!$this->post['idbandeira']) {
                    $this->post['idbandeira'] = NULL;
                }

                if (!$this->post['autorizacao_cartao']) {
                    $this->post['autorizacao_cartao'] = NULL;
                }
            } else {
                $this->post['idbandeira'] = "NULL";
                $this->post['autorizacao_cartao'] = NULL;
            }
        } else {
            if (!$this->post['idbandeira']) {
                $this->post['idbandeira'] = "NULL";
            }

            if (!$this->post['autorizacao_cartao']) {
                $this->post['autorizacao_cartao'] = NULL;
            }
        }
        if (
            ($GLOBALS["situacao"]["visualizacoes"][51] && $GLOBALS['modificar_matricula'] === 'S')
            || !empty($_SESSION["adm_idusuario"])
            || !empty($_SESSION["usu_vendedor_idvendedor"])
        ) {
            if ($this->post["data_matricula"]) {
                $this->post["data_matricula"] = "'" . formataData($this->post["data_matricula"], "en", 0) . "'";
            } else {
                $erro[] = "dados_matricula_data_matricula_vazio";
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "dados_matricula_data_matricula_vazio";
                return $this->retorno;
            }
        } else {
            $this->post["data_matricula"] = "matriculas.data_matricula";
        }
        if ($this->post["data_prolongada"]) {
            $this->post["data_prolongada"] = "'" . formataData($this->post["data_prolongada"], "en", 0) . "'";
        } else {
            $this->post["data_prolongada"] = "NULL";
        }
        if ($this->post["data_conclusao"]) {
            $this->post["data_conclusao"] = "'" . formataData($this->post["data_conclusao"], "en", 0) . "'";
        } else {
            $this->post["data_conclusao"] = "NULL";
        }

        if ($this->post["data_inicio_curso"]) {
            $this->post["data_inicio_curso"] = "'" . formataData($this->post["data_inicio_curso"], "en", 0) . "'";
        } else {
            $this->post["data_inicio_curso"] = "NULL";
        }

        if ($this->post["data_expedicao"]) {
            $this->post["data_expedicao"] = "'" . formataData($this->post["data_expedicao"], "en", 0) . "'";
        } else {
            $this->post["data_expedicao"] = "NULL";
        }
        if ($this->post["data_solicitacao_carteirinha"]) {
            $this->post["data_solicitacao_carteirinha"] = "'" . formataData($this->post["data_solicitacao_carteirinha"], "en", 0) . "'";
        } else {
            $this->post["data_solicitacao_carteirinha"] = "NULL";
        }
        if ($this->post["data_comissao"]) {
            $this->post["data_comissao"] = "'" . formataData($this->post["data_comissao"], "en", 0) . "'";
        } else {
            $this->post["data_comissao"] = "NULL";
        }

        if ($this->post["numero_contrato"]) {
            $this->post["numero_contrato"] = "'" . $this->post["numero_contrato"] . "'";
        } else {
            $this->post["numero_contrato"] = "NULL";
        }
        if ($this->post["combo_matricula"]) {
            $this->post["combo_matricula"] = "'" . $this->post["combo_matricula"] . "'";
            $this->post["combo"] = "'S'";
        } else {
            $this->post["combo_matricula"] = "NULL";
            $this->post["combo"] = "'N'";
        }

        if ($this->post["renach"]) {
            $this->post["renach"] = "'{$this->post["renach"]}'";
        } else {
            $this->post["renach"] = "NULL";
        }

        if ($this->post["data_inicio_certificado"]) {
            $this->post["data_inicio_certificado"] = "'" . formataData($this->post["data_inicio_certificado"], "en", 0) . "'";
        } else {
            $this->post["data_inicio_certificado"] = "NULL";
        }

        if ($this->post["data_final_certificado"]) {
            $this->post["data_final_certificado"] = "'" . formataData($this->post["data_final_certificado"], "en", 0) . "'";
        } else {
            $this->post["data_final_certificado"] = "NULL";
        }

        if (!$this->post["situacao_carteirinha"]) {
            $this->post["situacao_carteirinha"] = "NULL";
        }
        if ($this->post["observacao"]) {
            $this->post["observacao"] = "'" . $this->post["observacao"] . "'";
        } else {
            $this->post["observacao"] = "NULL";
        }

        //if($this->post['possui_financeiro']=='S' && $this->modulo != 'cfc'){
        if ($this->post["bolsa"] == "S") {
            $this->post["valor_contrato"] = 0;
            $this->post["qtd_parcelas"] = 0;
            $this->post["cupom_nota_fiscal"] = 0;
            if (!$this->post["idsolicitante"]) {
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "financeiro_idsolicitante_vazio";
                return $this->retorno;
            }
            //Bolsa Parcial
        } elseif ($this->post["bolsa"] == "BP") {
            $this->post["valor_contrato"] = floatval(str_replace(',', '.', str_replace('.', '', $this->post['valor_contrato'])));

            if (!$this->post["idsolicitante"]) {
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "financeiro_idsolicitante_vazio";
                return $this->retorno;
            }

            if (!$this->post["valor_contrato"]) {
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "financeiro_valor_contrato_vazio";
                return $this->retorno;
            }
            if (!$this->post["qtd_parcelas"]) {
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "financeiro_qtd_parcelas_vazio";
                return $this->retorno;
            }
            if (!$this->post["cupom_nota_fiscal"]) {
                $this->post["cupom_nota_fiscal"] = 'NULL';
            } else {
                $this->post["cupom_nota_fiscal"] = "'" . $this->post["cupom_nota_fiscal"] . "'";
            }
            //Não Possui Bolsa
        } elseif ($this->post["bolsa"] == "N") {
            $this->post["valor_contrato"] = floatval(str_replace(',', '.', str_replace('.', '', $this->post['valor_contrato'])));
            $this->post["idsolicitante"] = "NULL";
            if (!$this->post["valor_contrato"]) {
                $this->post["valor_contrato"] = "NULL";
            }
            if (!$this->post["qtd_parcelas"]) {
                $this->post["qtd_parcelas"] = "NULL";
            }
            if (!$this->post["combo_matricula"]) {
                $this->post["combo_matricula"] = "NULL";
            }
            if (!$this->post["combo"]) {
                $this->post["combo"] = "NULL";
            }
            if (!$this->post["forma_pagamento"]) {
                $this->post["forma_pagamento"] = "NULL";
            }
            if (!$this->post["idbandeira"]) {
                $this->post["idbandeira"] = "NULL";
            }

            if (!$this->post["valor_contrato"]) {
                $this->post["valor_contrato"] = "NULL";
            }
            if (!$this->post["cupom_nota_fiscal"]) {
                $this->post["cupom_nota_fiscal"] = "NULL";
            } else {
                $this->post["cupom_nota_fiscal"] = "'" . $this->post["cupom_nota_fiscal"] . "'";
            }
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "financeiro_bolsa_vazio";
            return $this->retorno;
        }
        //}

        if ($this->post['autorizacao_cartao']) {
            $this->post['autorizacao_cartao'] = '"' . $this->post['autorizacao_cartao'] . '"';
        } else {
            $this->post['autorizacao_cartao'] = 'NULL';
        }

        if (!$this->post["idempresa"]) {
            $this->post["idempresa"] = "NULL";
        }
        if ($this->post["limite_datavalid"]) {
            $this->post["limite_datavalid"] = (int)$this->post["limite_datavalid"];
        } else {
            $this->post["limite_datavalid"] = "NULL";
        }

        if (!$this->post["biometria_liberada"] ||
            !in_array($this->post["biometria_liberada"], array('N', 'S')))
            $this->post["biometria_liberada"] = "matriculas.biometria_liberada";
        else
            $this->post["biometria_liberada"] = "'{$this->post["biometria_liberada"]}'";

        $this->post["bolsa"] = "'{$this->post["bolsa"]}'";

        $this->sql = "SELECT
                  *
              FROM
                matriculas
              WHERE
                idmatricula = " . intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);

        // removendo campo faturada das alterações que houver
        if (!array_key_exists('faturada', $this->post))
            $this->post["faturada"] = 'matriculas.faturada';
        else
            $this->post["faturada"] = "'{$this->post["faturada"]}'";

        $this->sql = "UPDATE
              matriculas
            SET
              data_comissao =               " . ($this->post["data_comissao"]) . ",
              data_expedicao =              " . ($this->post["data_expedicao"]) . ",
              data_prolongada =             " . ($this->post["data_prolongada"]) . ",
              data_inicio_curso =              " . ($this->post["data_inicio_curso"]) . ",
              data_conclusao =              " . ($this->post["data_conclusao"]) . ",
              data_matricula =              " . ($this->post["data_matricula"]) . ",
              data_registro =               " . ($this->post["data_registro"]) . ",
              observacao =                  " . $this->post["observacao"] . ",
              idempresa =                   " . $this->post["idempresa"] . ",
              renach =                      {$this->post["renach"]},
              data_inicio_certificado =     " . $this->post["data_inicio_certificado"] . ",
              data_final_certificado =      " . $this->post["data_final_certificado"] . ",
              data_solicitacao_carteirinha =" . $this->post["data_solicitacao_carteirinha"] . ",
              situacao_carteirinha =        " . $this->post["situacao_carteirinha"] . ",
              idvendedor =                  " . $this->post["idvendedor"] . ",
              numero_contrato =             " . $this->post["numero_contrato"] . ",
              bolsa =                       {$this->post["bolsa"]},
              idsolicitante =               {$this->post["idsolicitante"]},
              valor_contrato =              {$this->post["valor_contrato"]},
              quantidade_parcelas =         {$this->post["qtd_parcelas"]},
              faturada =                    {$this->post["faturada"]},
              combo_matricula =             {$this->post["combo_matricula"]},
              combo =                       {$this->post["combo"]},
              forma_pagamento =             {$this->post["forma_pagamento"]},
              limite_datavalid =            {$this->post["limite_datavalid"]},
              idbandeira =                  {$this->post["idbandeira"]},
              autorizacao_cartao =          {$this->post["autorizacao_cartao"]},
              biometria_liberada =    {$this->post["biometria_liberada"]},
              cupom_nota_fiscal =           {$this->post["cupom_nota_fiscal"]}";

        if (str_replace(',', '.', $this->post["porcentagem_manual"]) > 0.00) {
            $this->sql .= ", porcentagem_manual = '" . str_replace(',', '.', $this->post["porcentagem_manual"]) . "' ";
        }

        $this->sql .= " WHERE
                  idmatricula = '" . $this->id . "'";

        $salvar = $this->executaSql($this->sql);
        $this->sql = "SELECT
                            *
                    FROM
                        matriculas
                    WHERE
                        idmatricula = " . $this->id;
        $linhaNova = $this->retornarLinha($this->sql);

        if (!$linhaAntiga["data_registro"] && !$linhaNova["data_registro"]) {
            $linhaAntiga["data_registro"] = "vazio";
            $linhaNova["data_registro"] = "vazio";
        }

        if (!$linhaAntiga["data_comissao"] && !$linhaNova["data_comissao"]) {
            $linhaAntiga["data_comissao"] = "vazio";
            $linhaNova["data_comissao"] = "vazio";
        }

        if (!$linhaAntiga["data_expedicao"] && !$linhaNova["data_expedicao"]) {
            $linhaAntiga["data_expedicao"] = "vazio";
            $linhaNova["data_expedicao"] = "vazio";
        }

        if (!$linhaAntiga["data_solicitacao_carteirinha"] && !$linhaNova["data_solicitacao_carteirinha"]) {
            $linhaAntiga["data_solicitacao_carteirinha"] = "vazio";
            $linhaNova["data_solicitacao_carteirinha"] = "vazio";
        }

        if (!$linhaAntiga["idsolicitante"] && !$linhaNova["idsolicitante"]) {
            $linhaAntiga["idsolicitante"] = "vazio";
            $linhaNova["idsolicitante"] = "vazio";
        }

        if (!$linhaAntiga["quantidade_parcelas"] && !$linhaNova["quantidade_parcelas"]) {
            $linhaAntiga["quantidade_parcelas"] = "vazio";
            $linhaNova["quantidade_parcelas"] = "vazio";
        }

        if (!$linhaAntiga["idempresa"] && !$linhaNova["idempresa"]) {
            $linhaAntiga["idempresa"] = "vazio";
            $linhaNova["idempresa"] = "vazio";
        }

        if (!$linhaAntiga["renach"] && !$linhaNova["renach"]) {
            $linhaAntiga["renach"] = "vazio";
            $linhaNova["renach"] = "vazio";
        }

        if (!$linhaAntiga["situacao_carteirinha"] && !$linhaNova["situacao_carteirinha"]) {
            $linhaAntiga["situacao_carteirinha"] = "vazio";
            $linhaNova["situacao_carteirinha"] = "vazio";
        }
        if (!$linhaAntiga["data_conclusao"] && !$linhaNova["data_conclusao"]) {
            $linhaAntiga["data_conclusao"] = "vazio";
            $linhaNova["data_conclusao"] = "vazio";
        }

        if (!$linhaAntiga["data_inicio_curso"] && !$linhaNova["data_inicio_curso"]) {
            $linhaAntiga["data_inicio_curso"] = "vazio";
            $linhaNova["data_inicio_curso"] = "vazio";
        }

        if (!$linhaAntiga["data_prolongada"] && !$linhaNova["data_prolongada"]) {
            $linhaAntiga["data_prolongada"] = "vazio";
            $linhaNova["data_prolongada"] = "vazio";
        }
        if (!$linhaAntiga["idbandeira"] && !$linhaNova["idbandeira"]) {
            $linhaAntiga["idbandeira"] = "vazio";
            $linhaNova["idbandeira"] = "vazio";
        }
        if (!$linhaAntiga["autorizacao_cartao"] && !$linhaNova["autorizacao_cartao"]) {
            $linhaAntiga["autorizacao_cartao"] = "vazio";
            $linhaNova["autorizacao_cartao"] = "vazio";
        }
        if (!$linhaAntiga["observacao"] && !$linhaNova["observacao"]) {
            $linhaAntiga["observacao"] = "vazio";
            $linhaNova["observacao"] = "vazio";
        }
        if (!$linhaAntiga["porcentagem_manual"] && !$linhaNova["porcentagem_manual"]) {
            $linhaAntiga["porcentagem_manual"] = "vazio";
            $linhaNova["porcentagem_manual"] = "vazio";
        }
        if (!$linhaAntiga["forma_pagamento"] && !$linhaNova["forma_pagamento"]) {
            $linhaAntiga["forma_pagamento"] = "vazio";
            $linhaNova["forma_pagamento"] = "vazio";
        }

        if (!$linhaAntiga["cupom_nota_fiscal"] && !$linhaNova["cupom_nota_fiscal"]) {
            $linhaAntiga["cupom_nota_fiscal"] = "vazio";
            $linhaNova["cupom_nota_fiscal"] = "vazio";
        }
        if (!$linhaAntiga["combo_matricula"] && !$linhaNova["combo_matricula"]) {
            $linhaAntiga["combo_matricula"] = "vazio";
            $linhaNova["combo_matricula"] = "vazio";
        }
        if (!$linhaAntiga["combo"] && !$linhaNova["combo"]) {
            $linhaAntiga["combo"] = "vazio";
            $linhaNova["combo"] = "vazio";
        }
        if (!$linhaAntiga["data_inicio_certificado"] && !$linhaNova["data_inicio_certificado"]) {
            $linhaAntiga["data_inicio_certificado"] = "vazio";
            $linhaNova["data_inicio_certificado"] = "vazio";
        }
        if (!$linhaAntiga["data_final_certificado"] && !$linhaNova["data_final_certificado"]) {
            $linhaAntiga["data_final_certificado"] = "vazio";
            $linhaNova["data_final_certificado"] = "vazio";
        }
        if (!$linhaAntiga["limite_datavalid"] && !$linhaNova["limite_datavalid"]) {
            $linhaAntiga["limite_datavalid"] = "vazio";
            $linhaNova["limite_datavalid"] = "vazio";
        }
        if (!$linhaAntiga["numero_contrato"] && !$linhaNova["numero_contrato"]) {
            $linhaAntiga["numero_contrato"] = "vazio";
            $linhaNova["numero_contrato"] = "vazio";
        }
        if (!$linhaAntiga["valor_contrato"] && !$linhaNova["valor_contrato"]) {
            $linhaAntiga["valor_contrato"] = "vazio";
            $linhaNova["valor_contrato"] = "vazio";
        }
        if (!$linhaAntiga["idvendedor"] && !$linhaNova["idvendedor"]) {
            $linhaAntiga["idvendedor"] = "vazio";
            $linhaNova["idvendedor"] = "vazio";
        }
        if ($salvar) {

            if (str_replace(',', '.', $this->post["porcentagem_manual"]) > 0) {
                //atualiza a porcentagem do AVA
                $this->atualizaPorcentagemAva($this->id, $this->post["porcentagem_manual"], null);
            }

            //$this->AdicionarHistorico($this->idusuario, "combo_matricula", "modificou", $linhaAntiga["combo_matricula"], //$linhaNova["combo_matricula"], NULL);
            //$this->AdicionarHistorico($this->idusuario, "combo", "modificou", $linhaAntiga["combo"], $linhaNova["combo"], NULL);
            $this->AdicionarHistorico($this->idusuario, "data_registro", "modificou", $linhaAntiga["data_registro"], $linhaNova["data_registro"], NULL);
            $this->AdicionarHistorico($this->idusuario, "data_comissao", "modificou", $linhaAntiga["data_comissao"], $linhaNova["data_comissao"], NULL);
            $this->AdicionarHistorico($this->idusuario, "vendedor", "modificou", $linhaAntiga["idvendedor"], $linhaNova["idvendedor"], NULL);
            //if($this->url[0] == 'gestor')
            $this->AdicionarHistorico($this->idusuario, "forma_pagamento", "modificou", $linhaAntiga["forma_pagamento"], $linhaNova["forma_pagamento"], NULL);


            $this->AdicionarHistorico($this->idusuario, "idbandeira", "modificou", $linhaAntiga["idbandeira"], $linhaNova["idbandeira"], NULL);
            $this->AdicionarHistorico($this->idusuario, "autorizacao_cartao", "modificou", $linhaAntiga["autorizacao_cartao"], $linhaNova["autorizacao_cartao"], NULL);

            $this->AdicionarHistorico($this->idusuario, "data_matricula", "modificou", $linhaAntiga["data_matricula"], $linhaNova["data_matricula"], NULL);
            $this->AdicionarHistorico($this->idusuario, "data_expedicao", "modificou", $linhaAntiga["data_expedicao"], $linhaNova["data_expedicao"], NULL);
            $this->AdicionarHistorico($this->idusuario, "numero_contrato", "modificou", $linhaAntiga["numero_contrato"], $linhaNova["numero_contrato"], NULL);
            //$this->AdicionarHistorico($this->idusuario, "bolsa", "modificou", $linhaAntiga["bolsa"], $linhaNova["bolsa"], NULL);
            //$this->AdicionarHistorico($this->idusuario, "solicitante", "modificou", $linhaAntiga["idsolicitante"], $linhaNova["idsolicitante"], NULL);

            $this->AdicionarHistorico($this->idusuario, "valor_contrato", "modificou", $linhaAntiga["valor_contrato"], $linhaNova["valor_contrato"], NULL);


            $this->AdicionarHistorico($this->idusuario, "quantidade_parcelas", "modificou", $linhaAntiga["quantidade_parcelas"], $linhaNova["quantidade_parcelas"], NULL);
            $this->AdicionarHistorico($this->idusuario, "faturada", "modificou", $linhaAntiga["faturada"], $linhaNova["faturada"], NULL);
            $this->AdicionarHistorico($this->idusuario, "biometria_liberada", "modificou", $linhaAntiga["biometria_liberada"], $linhaNova["biometria_liberada"], NULL);
            $this->AdicionarHistorico($this->idusuario, "observacao", "modificou", $linhaAntiga["observacao"], $linhaNova["observacao"], NULL);
            //$this->AdicionarHistorico($this->idusuario, "empresa", "modificou", $linhaAntiga["idempresa"], $linhaNova["idempresa"], NULL);
            $this->AdicionarHistorico($this->idusuario, "renach", "modificou", $linhaAntiga["renach"], $linhaNova["renach"], NULL);
            $this->AdicionarHistorico($this->idusuario, "data_solicitacao_carteirinha", "modificou", $linhaAntiga["data_solicitacao_carteirinha"], $linhaNova["data_solicitacao_carteirinha"], NULL);
            $this->AdicionarHistorico($this->idusuario, "situacao_carteirinha", "modificou", $linhaAntiga["situacao_carteirinha"], $linhaNova["situacao_carteirinha"], NULL);
            $this->AdicionarHistorico($this->idusuario, "data_prolongada", "modificou", $linhaAntiga["data_prolongada"], $linhaNova["data_prolongada"], NULL);
            $this->AdicionarHistorico($this->idusuario, "data_inicio_curso", "modificou", $linhaAntiga["data_inicio_curso"], $linhaNova["data_inicio_curso"], NULL);
            $this->AdicionarHistorico($this->idusuario, "data_conclusao", "modificou", $linhaAntiga["data_conclusao"], $linhaNova["data_conclusao"], NULL);
            $this->AdicionarHistorico($this->idusuario, "porcentagem_manual", "modificou", $linhaAntiga["porcentagem_manual"], $linhaNova["porcentagem_manual"], NULL);
            $this->AdicionarHistorico($this->idusuario, "data_inicio_certificado", "modificou", $linhaAntiga["data_inicio_certificado"], $linhaNova["data_inicio_certificado"], NULL);
            $this->AdicionarHistorico($this->idusuario, "data_final_certificado", "modificou", $linhaAntiga["data_final_certificado"], $linhaNova["data_final_certificado"], NULL);
            //$this->AdicionarHistorico($this->idusuario, "cupom_nota_fiscal", "modificou", $linhaAntiga["cupom_nota_fiscal"], $linhaNova["cupom_nota_fiscal"], NULL);
            $this->AdicionarHistorico($this->idusuario, "limite_datavalid", "modificou", $linhaAntiga["limite_datavalid"], $linhaNova["limite_datavalid"], NULL);

            $this->monitora_onde = 79;
            $this->monitora_oque = 2;
            $this->monitora_qual = $this->id;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "editar_dados_matricula_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "editar_dados_matricula_erro";
        }
        return $this->retorno;
    }

    public function verificaExisteAssociacao($idpessoa)
    {
        $this->sql = "select count(*) as total FROM matriculas_associados where ativo = 'S' and idmatricula = " . intval($this->id) . " and idpessoa = " . intval($idpessoa);
        $verifica = $this->retornarLinha($this->sql);
        if ($verifica["total"] <= 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * cadastrarMensagem
     *
     * @return array status of operation
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function cadastrarMensagem($arquivos)
    {

        $permissoes = 'JPEG|jpg|jpeg|gif|png|bmp|zip|rar|tar|gz|doc|docx|xls|xlsx|ppt|pptx|pps|ppsx|txt|pdf';
        $campo = array("pasta" => "matriculas_mensagens");

        $existe_arquivos = false;
        foreach ($arquivos['arquivos']['name'] as $ind => $arq)
            if ($arq)
                $existe_arquivos = true;

        if ($existe_arquivos) {
            foreach ($arquivos['arquivos']['name'] as $ind => $arquivo) {
                $file['name'] = $arquivos['arquivos']['name'][$ind];
                $file['tmp_name'] = $arquivos['arquivos']['tmp_name'][$ind];
                $file['size'] = $arquivos['arquivos']['size'][$ind];

                unset($nome_servidor);

                $file_aux['name'] = $file;
                $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
                if ($validacao_tamanho) {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = $validacao_tamanho;
                    return $this->retorno;
                }
            }
        }

        $id = (int)$this->id;
        $message = addslashes($_POST['mensagem']);
        $status = ('on' == $_POST['exibir_diploma']) ? 'S' : 'N';
        $_POST['exibir_diploma'];
        $this->sql = "INSERT INTO
                        matriculas_mensagens
                     SET
                        data_cad = NOW(),
                        idmatricula = '{$id}',
                        mensagem = '{$message}',
                        exibir_diploma = '{$status}'";
        if ($this->modulo == 'gestor') {
            $this->sql .= ", idusuario = '{$this->idusuario}'";
        } elseif ($this->modulo == 'escola') {
            $this->sql .= ", idescola = '{$this->idescola}'";
        }
        $salvar = $this->executaSql($this->sql);
        $idmensagem = mysql_insert_id();

        if ($idmensagem && $existe_arquivos) {
            $this->anexarArquivoMensagem($id, $idmensagem, $arquivos, $campo);
        }

        if ($salvar) {
            $this->set('monitora_oque', 1)->set('monitora_onde', 166)->set('monitora_qual', mysql_insert_id())->monitora();
            $this->adicionarHistorico($this->idusuario, 'mensagem', 'cadastrou', null, null, $this->monitora_qual);
            $this->retorno['sucesso'] = true;
            $this->retorno['id'] = $idmensagem;
            $this->retorno['mensagem'] = 'mensagem_adicionada_sucesso';
        } else {
            $this->retorno['sucesso'] = false;
            $this->retorno['mensagem'] = 'mensagem_adicionada_erro';
        }
        return $this->retorno;
    }

    private function anexarArquivoMensagem($id, $idmensagem, $arquivos, $campo)
    {
        foreach ($arquivos['arquivos']['name'] as $ind => $arquivo) {
            $file['name'] = $arquivos['arquivos']['name'][$ind];
            $file['tmp_name'] = $arquivos['arquivos']['tmp_name'][$ind];
            $file['size'] = $arquivos['arquivo']['size'][$ind];

            unset($nome_servidor);

            $file_aux['name'] = $file;
            $validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
            if ($validacao_tamanho) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $validacao_tamanho;
                return $this->retorno;
            }

            $nome_servidor = $this->uploadFile($file, $campo);

            if ($nome_servidor) {
                $sql = "insert into
                matriculas_mensagens_arquivos
                set
                idmensagem = '{$idmensagem}',
                idmatricula = {$id},
                ativo = 'S',
                titulo='" . $arquivos['arquivos']['name'][$ind] . "',
                data_cad = now(),
                arquivo_nome = '" . $arquivos['arquivos']['name'][$ind] . "',
                arquivo_tipo = '" . $arquivos['arquivos']['type'][$ind] . "',
                arquivo_tamanho = '" . $arquivos['arquivos']['size'][$ind] . "',
                arquivo_servidor = '" . $nome_servidor . "' ";

                $query_arquivo = $this->executaSql($sql);
                $idarquivo = mysql_insert_id();
                if (!$query_arquivo) {
                    $erro = true;
                } else {
                    $this->monitora_onde = 270;
                    $this->monitora_oque = 1;
                    $this->monitora_qual = $idarquivo;
                    $this->Monitora();
                }
            }
        }
    }

    function uploadFile($file, $campoAux)
    {
        $extensao = strtolower(strrchr($file["name"], "."));
        $nome_servidor = date("YmdHis") . "_" . uniqid() . $extensao;

        if (move_uploaded_file($file["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/storage/" . $campoAux["pasta"] . "/" . $nome_servidor)) {
            return $nome_servidor;
        } else
            return false;
    }

    public function removerMensagem($idmensagem)
    {
        $this->sql = "update
           matriculas_mensagens
           set
           ativo = 'N'
           where
           idmensagem = '" . $idmensagem . "'";
        $remover = $this->executaSql($this->sql);
        if ($remover) {
            $this->monitora_oque = 3;
            $this->monitora_onde = 166;
            $this->monitora_qual = $idmensagem;
            $this->Monitora();
            $this->AdicionarHistorico($this->idusuario, "mensagem", "removeu", NULL, NULL, $this->monitora_qual);
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "mensagem_removida_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_removida_erro";
        }
        return $this->retorno;
    }

    public function EnviarMensagemEmail($idmensagem, $nome, $email)
    {
        $sql = "SELECT mensagem FROM matriculas_mensagens WHERE idmensagem = {$idmensagem} LIMIT 1";
        $sel = $this->executaSql($sql);
        $linhaMensagem = mysql_fetch_assoc($sel);
        if ($linhaMensagem["mensagem"]) {
            $arquivos = $this->retornarMensagensArquivos($idmensagem);
            if ($arquivos) {
                $linhaMensagem["mensagem"] .= "<br><br><b>Arquivos:</b><br>";

                foreach ($arquivos as $arquivo) {
                    $linhaMensagem["mensagem"] .= "<a href='http://" . $_SERVER['SERVER_NAME'] . "/api/get/download/matriculas_mensagens/" . $arquivo['arquivo_servidor'] . "'>" . $arquivo['arquivo_nome'] . "</a><br>";
                }
            }

            $adm_nome = $this->config['tituloEmpresa'];
            $adm_email = $_SESSION['adm_email'];
            if ($this->modulo == 'escola') {
                $adm_nome = $_SESSION['escola_nome'];
                $adm_email = $_SESSION['escola_email'];
            }

            if ($this->enviarEmail(utf8_decode($adm_nome), $adm_email, utf8_decode('Mensagens Matrícula'), $linhaMensagem["mensagem"], $nome, $email)) {
                $texto = "De: " . $adm_email . " <br />Para: " . $email . " ";
                $sql = "UPDATE matriculas_mensagens SET enviar_email = '" . $texto . "' WHERE idmensagem = {$idmensagem} LIMIT 1";
                $this->executaSql($sql);
            }
        }
    }

    public function aprovarComercialMatricula()
    {
        $this->retorno = array();
        $this->sql = "select aprovado_comercial, data_aprovado_comercial, idusuario_aprovado_comercial FROM matriculas WHERE idmatricula = " . intval($this->id);
        $linhaAntiga = $this->retornarLinha($this->sql);
        $situacao = $this->retornarSituacaoAprovadoComercial();
        $situacaoID = array_key_exists('idsituacao', $situacao) ? $situacao['idsituacao'] : 'matriculas.idsituacao';
        $this->sql = "update matriculas set
                      aprovado_comercial = 'S',
                      data_aprovado_comercial = now(),
                      idsituacao = {$situacaoID},
                      idusuario_aprovado_comercial = '" . $this->idusuario . "'
                      WHERE idmatricula = '" . intval($this->id) . "'";
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            $this->sql = "SELECT aprovado_comercial, data_aprovado_comercial, idusuario_aprovado_comercial FROM matriculas where idmatricula = " . intval($this->id);
            $linhaNova = $this->retornarLinha($this->sql);
            $this->AdicionarHistorico($this->idusuario, "matricula", "aprovou_comercial", $linhaAntiga["aprovado_comercial"], $linhaNova["aprovado_comercial"], NULL);
            $this->monitora_oque = 2;
            $this->monitora_onde = 79;
            $this->monitora_qual = $this->id;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

            $sql = "SELECT * FROM contas_workflow where ativo = 'S' and emaberto = 'S' order by idsituacao desc limit 1";
            $situacao_contas = $this->retornarLinha($sql);

            $sql = "SELECT * FROM contas_workflow where ativo = 'S' and faturar = 'S' order by idsituacao desc limit 1";
            $situacao_contas1 = $this->retornarLinha($sql);
            if (array_key_exists('idsituacao', $situacao_contas1)) {
                $this->sql = "update contas
                set
                    idsituacao = {$situacao_contas['idsituacao']}
                where
                    idmatricula = {$this->id} and
                    idsituacao = {$situacao_contas1['idsituacao']}";
                $this->executaSql($this->sql);
            }

            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "aprovado_comercial_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "aprovado_comercial_erro";
        }
        return $this->retorno;
    }

    public function retornarMotivoCancelar()
    {
        $this->sql = "select * FROM motivos_cancelamento where ativo = 'S' and cancela_automatico = 'S' order by idmotivo desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoInicial()
    {
        $this->sql = "select * FROM matriculas_workflow where ativo = 'S' and inicio = 'S' order by idsituacao desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoCancelada()
    {
        $this->sql = "select * FROM matriculas_workflow where ativo = 'S' and cancelada = 'S' order by idsituacao desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoAprovadoComercial()
    {
        $this->sql = "select * FROM matriculas_workflow where aprovado_comercial = 'S' and ativo = 'S' order by idsituacao desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoAtiva()
    {
        $this->sql = "select * FROM matriculas_workflow where ativo = 'S' and ativa = 'S' order by idsituacao desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoConcluida()
    {
        $this->sql = "select * FROM matriculas_workflow where ativo = 'S' and fim = 'S' order by idsituacao desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoInativa()
    {
        $this->sql = "select * FROM matriculas_workflow where ativo = 'S' and inativa = 'S' order by idsituacao desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoHomologarCertificado()
    {
        $this->sql = 'SELECT idsituacao FROM matriculas_workflow WHERE homologar_certificado = "S" AND ativo = "S" ORDER BY idsituacao DESC LIMIT 1';
        return $this->retornarLinha($this->sql);
    }

    public function retornarMeusCursos()
    {

        $idSituacaoPreMatricula = $this->retornarSituacaoInicial();
        $idSituacaoEmCurso = $this->retornarSituacaoAtiva();

        $matriculas = array();
        $this->sql = "SELECT
                    " . $this->campos . "
                  FROM
                    matriculas m
                    INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                    INNER JOIN matriculas_workflow mw  ON (m.idsituacao = mw.idsituacao)
                  WHERE
                    m.ativo = 'S' AND
                    mw.ativo = 'S' AND
                    (
                        m.idsituacao = " . $idSituacaoPreMatricula['idsituacao'] . " OR
                        m.idsituacao = " . $idSituacaoEmCurso['idsituacao'] . "
                    ) AND
                    m.idpessoa = " . (int)$this->idpessoa;
        $matriculas = $this->retornarLinhas();
        foreach ($matriculas as $ind => $matricula) {
            $this->id = $matricula["idmatricula"];
            $matriculas[$ind]["avaliacoes"] = $this->retornarAvaliacoesPendentes();
            $andamento['porc_aluno'] = $matricula["porc_aluno"];
            if ($andamento['porc_aluno'] >= 100) {
                $andamento['porc_aluno'] = 100;
                $andamento["porc_aluno_formatada"] = 100;
            } else {
                $andamento["porc_aluno_formatada"] = number_format($andamento["porc_aluno"], 2, ",", ".");
            }
            $matriculas[$ind]["andamento"] = $andamento;
            $matriculas[$ind]["regras_solicitar_prova"] = $this->retornaRegrasSolicitarProva();
            $matriculas[$ind]["qtde_solicitacoes_provas"] = $this->retornarQtdeSolicitacoesProvas();
            $matriculas[$ind]['regras_curriculo'] = $this->retornaRegrasCurriculo();
            $matriculas[$ind]['visualizacao'] = $this->retornarVisualizacoesSituacao($matricula['idsituacao']);
            $linhaOferta = new Ofertas();
            $matriculas[$ind]['permissao_acesso'] = $linhaOferta->verificarAcessoAlunoCurso($matricula['idmatricula']);
        }
        return $matriculas;
    }

    function retornarVisualizacoesSituacao($idsituacao)
    {
        $this->sql = "SELECT
                    mwa.idacao, mwa.idopcao
                FROM
                    matriculas_workflow_acoes mwa
                WHERE
                    mwa.idsituacao = $idsituacao
                AND
                    mwa.ativo = 'S'";
        $this->ordem = "asc";
        $this->ordem_campo = "mwa.idacao";
        $this->limite = -1;
        $acoes = $this->retornarLinhas();
        foreach ($acoes as $acao) {
            foreach ($GLOBALS["workflow_parametros_matriculas"] as $opcao) {
                if ($opcao["idopcao"] == $acao["idopcao"] && $opcao["tipo"] == "visualizacao") {
                    $visualizacoes[$acao["idopcao"]] = $acao;
                }
            }
        }
        return $visualizacoes;
    }

    public function retornaRegrasCurriculo()
    {
        $andamento = array();
        $this->sql = "SELECT
                        ocp.*,
                        c.dias_minimo,
                        c.porcentagem_ava,
                        IFNULL(c.carga_horaria,0) as carga_horaria
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
                    INNER JOIN curriculos c
                    ON (
                        cb.idcurriculo = c.idcurriculo AND
                        c.ativo = 'S'
                        )
                  WHERE
                    m.ativo = 'S' AND
                    m.idmatricula = '{$this->id}'
          GROUP BY c.idcurriculo";

        $this->ordem = false;
        $this->ordem_campo = false;
        $this->limite = -1;
        $curriculo = $this->retornarLinha($this->sql);
        return $curriculo;
    }

    public function retornarMatriculaAluno()
    {
        $situacaoAtiva = $this->retornarSituacaoAtiva();
        $this->sql = "SELECT
                    " . $this->campos . "
                  FROM
                    matriculas m
                    INNER JOIN cursos c
                    ON (m.idcurso = c.idcurso)
                    INNER JOIN ofertas_cursos oc
                    ON (
                        m.idoferta = oc.idoferta AND
                        m.idcurso = oc.idcurso AND
                        oc.ativo = 'S'
                        )
                  WHERE
                    m.ativo = 'S' AND
                    m.idpessoa = " . intval($this->idpessoa) . " AND
                    m.idmatricula = " . intval($this->id);
        $linha = $this->retornarLinha($this->sql);
        $linha['visualizacao'] = $this->retornarVisualizacoesSituacao($linha['idsituacao']);
        if (!$linha['visualizacao'][27])
            $linha = null;
        return $linha;
    }

    public function retornarDisciplinasCurso()
    {
        $blocos = array();
        $disciplinas = array();
        $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
        $this->sql = "SELECT
                    cb.*
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
                    ON (ocp.idcurriculo = cb.idcurriculo AND
                        cb.ativo = 'S')
                  WHERE
                    m.ativo = 'S' AND
                    m.idpessoa = " . (int)$this->idpessoa . " AND
                    m.idmatricula = " . (int)$this->id;
        $this->limite = "-1";
        $this->ordem_campo = "cb.ordem asc, cb.nome";
        $this->ordem = "ASC";
        $blocos = $this->retornarLinhas();
        foreach ($blocos as $indBloco => $bloco) {
            $this->sql = "SELECT
                      d.*,
                      cbd.idbloco_disciplina,
                      oca.idava,
                      ara.idrota_aprendizagem
                    FROM
                      curriculos_blocos_disciplinas cbd
            INNER JOIN curriculos_blocos cb
            ON (cbd.idbloco = cb.idbloco)
                      INNER JOIN disciplinas d
                      ON (cbd.iddisciplina = d.iddisciplina)
            left join ofertas_curriculos_avas oca
            on oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and
            oca.idcurriculo = cb.idcurriculo and
            oca.idoferta = " . $oferta['idoferta'] . "
                      LEFT OUTER JOIN avas_rotas_aprendizagem ara
                      ON (oca.idava = ara.idava AND ara.ativo = 'S')
                    WHERE
                      cbd.ativo = 'S'  AND
                      cbd.idbloco = " . $bloco["idbloco"];
            $this->limite = "-1";
            $this->ordem_campo = "cbd.ordem asc, d.nome";
            $this->ordem = "asc";
            $disciplinas = $this->retornarLinhas();
            foreach ($disciplinas as $ind => $disciplina) {
                $disciplinas[$ind]["andamento"] = $this->retornarAndamentoDisciplina($disciplina["idbloco_disciplina"]);
            }
            $blocos[$indBloco]["disciplinas"] = $disciplinas;
        }
        return $blocos;
    }

    public function retornarObjetoRota($idbloco_disciplina, $idobjeto = false)
    {
        $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
        $objeto = array();
        $this->sql = "SELECT
                    cbd.idbloco_disciplina,
                    ara.idrota_aprendizagem,
                    ara.nome as rota,
                    arao.idobjeto,
                    arao.tempo,
                    arao.tipo,
                    (
                      SELECT
                        idobjeto
                      FROM
                        avas_rotas_aprendizagem_objetos
                      WHERE
                        ativo = 'S' AND
                        idrota_aprendizagem = ara.idrota_aprendizagem AND
                        ordem < arao.ordem AND
                        tipo <> 'objeto_divisor' AND
                        (vencimento >= '" . date('Y-m-d') . "' OR vencimento is null)
                      ORDER BY ordem DESC, data_cad limit 1
                    ) AS anterior,
                    (
                      SELECT
                        idobjeto
                      FROM
                        avas_rotas_aprendizagem_objetos
                      WHERE
                        ativo = 'S' AND
                        idrota_aprendizagem = ara.idrota_aprendizagem and
                        ordem > arao.ordem AND
                        tipo <> 'objeto_divisor' AND
                        (vencimento >= '" . date('Y-m-d') . "' OR vencimento IS NULL)
                      ORDER BY ordem ASC, data_cad LIMIT 1
                    ) AS proximo,
          oca.idava
                  FROM
                    curriculos_blocos_disciplinas cbd
                  INNER JOIN curriculos_blocos cb
                  ON (cbd.idbloco = cb.idbloco)
                  INNER JOIN ofertas_curriculos_avas oca
                  ON oca.ativo = 'S' AND oca.iddisciplina = cbd.iddisciplina AND oca.idcurriculo = cb.idcurriculo AND oca.idoferta = " . $oferta['idoferta'] . "
                  INNER JOIN avas_rotas_aprendizagem ara
                  ON (oca.idava = ara.idava and ara.ativo = 'S')
                  INNER JOIN avas_rotas_aprendizagem_objetos arao
                  ON (
                      ara.idrota_aprendizagem = arao.idrota_aprendizagem AND
                      arao.ativo = 'S' AND
                      (arao.vencimento >= '" . date('Y-m-d') . "' OR arao.vencimento IS NULL)
                    )
                  WHERE
                    cbd.ativo = 'S' AND
                    cbd.idbloco_disciplina = " . (int)$idbloco_disciplina;
        if ($idobjeto) {
            $this->sql .= " AND arao.idobjeto = " . (int)$idobjeto;
        }
        $this->sql .= " ORDER BY arao.ordem, arao.data_cad limit 1";
        $objeto = $this->retornarLinha($this->sql);
        if ($objeto["idobjeto"]) {
            $objeto["objeto"] = $this->retornarObjeto($objeto["idobjeto"], $idbloco_disciplina);
            // Ver se o objeto ja foi contabilizado
            $this->sql = "select * from matriculas_rotas_aprendizagem_objetos where
                        idmatricula='" . $this->id . "' and idobjeto='" . $objeto["idobjeto"] . "' ";
            $objeto["objeto"]["contabiliza_sql"] = $this->sql;
            $objeto["objeto"]["contabiliza"] = $this->retornarLinha($this->sql);
            if ($objeto["anterior"])
                $objeto["anterior"] = $this->retornarObjeto((int)$objeto["anterior"], $idbloco_disciplina);
            if ($objeto["proximo"])
                $objeto["proximo"] = $this->retornarObjeto((int)$objeto["proximo"], $idbloco_disciplina);
        }
        return $objeto;
    }

    public function retornarObjeto($idobjeto, $idbloco_disciplina)
    {
        $this->sql = "select * FROM avas_rotas_aprendizagem_objetos where idobjeto = " . $idobjeto;
        $objeto = $this->retornarLinha($this->sql);
        switch ($objeto["tipo"]) {
            case "audio":
                $this->sql = "select * FROM avas_audios where idaudio = " . $objeto["idaudio"];
                $objeto["objeto"] = $this->retornarLinha($this->sql);
                break;
            case "conteudo":
                $this->sql = "select * FROM avas_conteudos where idconteudo = " . $objeto["idconteudo"];
                $objeto["objeto"] = $this->retornarLinha($this->sql);
                $objeto["objeto"]["conteudo"] = $this->substituiVariaveisConteudo($objeto["objeto"]["conteudo"]);
                break;
            case "objeto_divisor":
                $this->sql = "SELECT * FROM
                          avas_objetos_divisores
                      WHERE idobjeto_divisor = " . $objeto["idobjeto_divisor"];
                $objeto["objeto"] = $this->retornarLinha($this->sql);
                break;
            case "download":
                $this->sql = "select * FROM avas_downloads where iddownload = " . $objeto["iddownload"];
                $objeto["objeto"] = $this->retornarLinha($this->sql);
                break;
            case "link":
                $this->sql = "select * FROM avas_links where idlink = " . $objeto["idlink"];
                $objeto["objeto"] = $this->retornarLinha($this->sql);
                break;
            case "pergunta":
                $this->sql = "select * FROM avas_perguntas where idpergunta = " . $objeto["idpergunta"];
                $objeto["objeto"] = $this->retornarLinha($this->sql);
                break;
            case "video":
                $this->sql = "select * FROM avas_videotecas where idvideo = " . $objeto["idvideo"];
                $objeto["objeto"] = $this->retornarLinha($this->sql);
                break;
            case "exercicio":
                $this->sql = "select * FROM avas_exercicios where idexercicio = " . $objeto["idexercicio"];
                $objeto["objeto"] = $this->retornarLinha($this->sql);
                break;
            case "simulado":
                $this->sql = "select * FROM avas_simulados where idsimulado = " . $objeto["idsimulado"];
                $objeto["objeto"] = $this->retornarLinha($this->sql);
                break;
            case "enquete":
                $this->sql = "select *, 'Enquete' as nome from avas_enquetes where idenquete = " . $objeto["idenquete"];
                $objeto["objeto"] = $this->retornarLinha($this->sql);
                $opcoesVerifica = $this->retornaOpcoesVerificaVotoEnquete($objeto["idenquete"], $idbloco_disciplina);
                $objeto["objeto"]["opcoes"] = $opcoesVerifica['opcoes'];
                $objeto["objeto"]["votou"] = $opcoesVerifica['votou'];
                $objeto["objeto"]["total_votos"] = $opcoesVerifica['total_votos'];
                break;
        }
        return $objeto;
    }

    public function contabilizarRota()
    {
        if (verificaPermissaoAcesso(false)) {
            if (
                senhaSegura($this->id, $GLOBALS["config"]["chaveLogin"]) == $this->post["idmatricula"] &&
                senhaSegura($this->post["disciplina"], $GLOBALS["config"]["chaveLogin"]) == $this->post["iddisciplina"] &&
                senhaSegura($this->post["ava"], $GLOBALS["config"]["chaveLogin"]) == $this->post["idava"] &&
                senhaSegura($this->post["rota"], $GLOBALS["config"]["chaveLogin"]) == $this->post["idrota"] &&
                senhaSegura($this->post["objeto"], $GLOBALS["config"]["chaveLogin"]) == $this->post["idobjeto"]
            ) {

                $this->sql = "select
                                count(*) as total
                            from
                                matriculas_rotas_aprendizagem_objetos
                            where
                                idmatricula = " . $this->id . " and
                                idava = " . $this->post["ava"] . " and
                                idobjeto = " . $this->post["objeto"];
                $verifica = $this->retornarLinha($this->sql);

                if ($verifica["total"] <= 0) {
                    $this->sql = "select porcentagem from avas_rotas_aprendizagem_objetos where idrota_aprendizagem = " . $this->post["rota"] . " and idobjeto = " . $this->post["objeto"];
                    $porcentagem = $this->retornarLinha($this->sql);

                    if (!$porcentagem['porcentagem'])
                        $porcentagem['porcentagem'] = 0;

                    $this->sql = "insert into
                                    matriculas_rotas_aprendizagem_objetos
                                  set
                                    data_cad = now(),
                                    idmatricula = " . $this->id . ",
                                    idava = " . $this->post["ava"] . ",
                                    idobjeto = " . $this->post["objeto"] . ",
                                    porcentagem = " . $porcentagem['porcentagem'];
                    if ($this->executaSql($this->sql)) {
                        $retorno["id"] = mysql_insert_id();
                        $this->monitora_oque = 1;
                        $this->monitora_onde = "141";
                        $this->monitora_qual = $retorno["id"];
                        $this->Monitora();
                        $retorno["sucesso"] = true;
                        $retorno["andamento"] = $this->retornarAndamento();
                    } else {
                        $retorno["erro"] = true;
                        $retorno["erros"][] = $this->sql;
                        $retorno["erros"][] = mysql_error();
                    }
                } else {
                    $retorno["erro"] = true;
                    $retorno["erros"][] = "ja_contabilizado";
                }
            } else {
                $retorno["erro"] = true;
                $retorno["erros"][] = "dados_nao_conferem";
            }

            return json_encode($retorno);
        }
    }

    public function substituiVariaveisConteudo($conteudo)
    {
        $sql = "SELECT p.* FROM
                    pessoas p
                INNER JOIN matriculas m
                ON (p.idpessoa = m.idpessoa)
                WHERE m.idmatricula = " . $this->id;
        $pessoas = $this->retornarLinha($sql);
        foreach ($pessoas as $coluna => $dados) {
            //$dados = utf8_decode($dados);
            $dados = htmlentities($dados);
            $conteudo = str_ireplace("[[aluno][" . $coluna . "]]", $dados, $conteudo);
            if ($coluna == "nome") {
                $dadosAux = explode(" ", $dados);
                $conteudo = str_ireplace("[[aluno][primeiro_nome]]", $dadosAux[0], $conteudo);
            }
        }
        $sql = "SHOW COLUMNS FROM `usuarios_adm`";
        $pessoas = $this->retornarLinha($sql);
        foreach ($pessoas as $coluna => $dados) {
            $conteudo = str_ireplace("[[aluno][" . $coluna . "]]", $dados, $conteudo);
        }
        //$conteudo = "XXXXXXXXXXX".$conteudo;
        return $conteudo;
    }

    public function retornaRegrasSolicitarProva()
    {
        $this->sql = "SELECT
                        oc.porcentagem_minima,
                        oc.qtde_minima_dias
                    FROM
                        matriculas m
                    INNER JOIN
                        ofertas_cursos oc
                    ON  (oc.idoferta = m.idoferta AND
                        oc.idcurso = m.idcurso AND
                        oc.ativo = 'S')
                    WHERE
                        m.idmatricula = " . (int)$this->id;
        return $this->retornarLinha($this->sql);
    }

    public function retornarQtdeSolicitacoesProvas()
    {
        $this->sql = "SELECT
                        count(id_solicitacao_prova) as total
                    FROM
                        provas_solicitadas ps
                    WHERE
                        ps.ativo = 'S' AND
                        ps.idmatricula = " . (int)$this->id;
        $resultado = $this->retornarLinha($this->sql);
        return $resultado['total'];
    }

    public function retornarAndamento()
    {
        $andamento = array();
        $this->sql = "SELECT
                        oca.idava,
                        cbd.idbloco_disciplina
                    FROM
                        matriculas m INNER JOIN ofertas_cursos_escolas ocp ON (m.idoferta = ocp.idoferta AND m.idescola = ocp.idescola AND m.idcurso = ocp.idcurso AND ocp.ativo = 'S' )
                        INNER JOIN curriculos_blocos cb ON ( ocp.idcurriculo = cb.idcurriculo AND cb.ativo = 'S' )
                        INNER JOIN curriculos_blocos_disciplinas cbd ON ( cb.idbloco = cbd.idbloco AND cbd.ativo = 'S' )
                        INNER JOIN ofertas_curriculos_avas oca ON ( oca.ativo = 'S' AND oca.iddisciplina = cbd.iddisciplina AND oca.idcurriculo = cb.idcurriculo AND oca.idoferta = m.idoferta )
                        INNER JOIN avas a ON ( oca.idava = a.idava AND a.ativo = 'S' )
                    where
                        m.ativo = 'S' AND
                        m.idmatricula = " . (int)$this->id . "
                    group by oca.idava";
        $this->ordem = false;
        $this->ordem_campo = false;
        $this->limite = -1;
        $avas = $this->retornarLinhas();
        $avasArray = array();
        foreach ($avas as $ava) {
            $this->salvarAndamentoDisciplina($ava['idbloco_disciplina']);
            $avasArray[] = $ava['idava'];
        }
        $totalPorcentagem = 0;
        $totalPorcentagemAluno = 0;
        $totalAvas = count($avasArray);
        if ($totalAvas > 0) {
            $avasArray = implode(',', $avasArray);
            $this->sql = "select
                            IFNULL(sum(porcentagem_rota),0) as total_rota,
                            IFNULL(sum(porcentagem_chat),0) as total_chat,
                            IFNULL(sum(porcentagem_forum),0) as total_forum,
                            IFNULL(sum(porcentagem_tira_duvida),0) as total_tira_duvida,
                            IFNULL(sum(porcentagem_biblioteca),0) as total_biblioteca,
                            IFNULL(sum(porcentagem_simulado),0) as total_simulado
                          from
                            avas
                          where
                            idava in (" . $avasArray . ")";
            $porcentagens = $this->retornarLinha($this->sql);
            $totalPorcentagem = $porcentagens['total_rota'] + $porcentagens['total_chat'] + $porcentagens['total_forum'] + $porcentagens['total_tira_duvida'] + $porcentagens['total_biblioteca'] + $porcentagens['total_simulado'];

            $this->sql = "select
                            IFNULL(sum(porcentagem),0) as total
                          from
                            matriculas_rotas_aprendizagem_objetos
                          where
                            idava in (" . $avasArray . ") and
                            idmatricula = " . (int)$this->id . " and
                            idobjeto IS NOT NULL";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            $porcentagem['conteudo'] = $porcentagensAluno['total'];
            if ($porcentagens['total_rota'] < $porcentagensAluno['total']) {
                $porcentagem['conteudo'] = $porcentagens['total_rota'];
            }

            $this->sql = "select
                            count(*) as total
                          from
                            matriculas_rotas_aprendizagem_objetos
                          where
                            idava in (" . $avasArray . ") and
                            idmatricula = " . (int)$this->id . " and
                            idchat IS NOT NULL";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['chat'] = $porcentagens['total_chat'];
            } else {
                $porcentagem['chat'] = 0;
            }

            $this->sql = "select
                            count(*) as total
                          from
                            matriculas_rotas_aprendizagem_objetos
                          where
                            idava in (" . $avasArray . ") and
                            idmatricula = " . (int)$this->id . " and
                            idforum IS NOT NULL";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['forum'] = $porcentagens['total_forum'];
            } else {
                $porcentagem['forum'] = 0;
            }

            $this->sql = "select
                            count(*) as total
                          from
                            matriculas_rotas_aprendizagem_objetos
                          where
                            idava in (" . $avasArray . ") and
                            idmatricula = " . (int)$this->id . " and
                            iddownload IS NOT NULL";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['biblioteca'] = $porcentagens['total_biblioteca'];
            } else {
                $porcentagem['biblioteca'] = 0;
            }

            $this->sql = "SELECT
                            COUNT(*) AS total
                          FROM
                            matriculas_rotas_aprendizagem_objetos
                          WHERE
                            idava in (" . $avasArray . ") AND
                            idmatricula = " . (int)$this->id . " AND
                            (
                                idtiraduvida IS NOT NULL OR
                                idmensagem_instantanea IS NOT NULL
                            )";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['tiraduvida'] = $porcentagens['total_tira_duvida'];
            } else {
                $porcentagem['tiraduvida'] = 0;
            }

            $this->sql = "SELECT
                            COUNT(*) AS total
                          FROM
                            matriculas_rotas_aprendizagem_objetos
                          WHERE
                            idava in (" . $avasArray . ") AND
                            idmatricula = " . (int)$this->id . " AND
                            idsimulado IS NOT NULL";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['simulado'] = $porcentagens['total_simulado'];
            } else {
                $porcentagem['simulado'] = 0;
            }

//            if($this->idmatricula){
            $this->sql = "select porcentagem from matriculas where idmatricula = {$this->idmatricula}";
            $porcentagemAtual = $this->retornarLinha($this->sql);
            $totalPorcentagemAluno = $porcentagemAtual['porcentagem'];
//            } else {
//                $totalPorcentagemAluno = $porcentagem['conteudo'] + $porcentagem['chat'] + $porcentagem['forum'] + $porcentagem['biblioteca'] + $porcentagem['tiraduvida'] + $porcentagem['simulado'];
//            }
        }
        $andamento["porc_total"] = $totalPorcentagem;
        $andamento["porc_aluno"] = $totalPorcentagemAluno;
        if ($andamento["porc_aluno"] >= 100) {
            $andamento["porc_aluno"] = 100;
            $andamento["porc_aluno_formatada"] = 100;
        } else {
            $andamento["porc_aluno_formatada"] = number_format($andamento["porc_aluno"], 2, ",", ".");
        }
        $this->sql = "update matriculas set porcentagem = (" . $andamento["porc_aluno"] . ") where idmatricula = " . (int)$this->id;
        if (!$this->executaSql($this->sql)) {
            $andamento = array();
            $andamento["erro"] = true;
            $andamento["erros"][] = $this->sql;
            $andamento["erros"][] = mysql_error();
        }

        return $andamento;
    }

    public function situacaoAtualMatricula($idmatricula)
    {
        try {
            if (gettype($idmatricula) != "integer") {
                throw new InvalidArgumentException("Para realizar a consulta da situação atual da matricula, o valor da matrícula precisa ser um inteiro!");
            } else {
                $this->sql = "select m.idsituacao, mw.nome from matriculas m inner join matriculas_workflow mw on m.idsituacao = mw.idsituacao where idmatricula = ${idmatricula}";
                return $this->retornarLinha($this->sql);
            }
        } catch (InvalidArgumentException $e) {
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
            if (!is_numeric($idMatricula)) {
                throw new InvalidArgumentException("Para realizar a consulta da porcentagem atual do curso, o valor da matrícula precisa ser um inteiro!");
            } else {
                $porcentagensAvas = $this->retornarAvas();
                $qtdAvas = count($porcentagensAvas);
                $porcentagemCursoAtual = 0;
                foreach ($porcentagensAvas as $porcentagemAva) {
                    $porcentagemCursoAtual += $porcentagemAva['porcentagem'];
                }
                return $porcentagemCursoAtual / $qtdAvas;
            }

        } catch (InvalidArgumentException $e) {
            echo "Ops! {$e->getMessage()}";
        }
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
                    where m.idmatricula = ' . (int)$this->id . ' and m.idpessoa = ' . (int)$this->idpessoa . ' and m.ativo = "S"
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

    public function workFlowMatriculasRelacionadasComSituacaoConcluido()
    {

        $situacaoConcluida = $this->retornarSituacaoConcluido();

        $this->sql = "SELECT r.idrelacionamento, r.idsituacao_de, a1.idapp as idsituacao_de_app, r.idsituacao_para, a2.idapp as idsituacao_para_app FROM matriculas_workflow_relacionamentos r inner join matriculas_workflow a1 on (a1.idsituacao = r.idsituacao_de) inner join matriculas_workflow a2 on (a2.idsituacao = r.idsituacao_para) WHERE r.ativo = 'S' and a1.ativo = 'S' and a2.ativo = 'S' and r.idsituacao_para = {$situacaoConcluida['idsituacao']}";

        $this->ordem_campo = null;

        return $this->retornarLinhas();
    }

    public function retornarAndamentoDisciplina($idbloco_disciplina)
    {
        $andamento = array();
        $ava = $this->retornarAva($idbloco_disciplina);
        if ($ava['idava']) {
            $this->sql = "select idmatricula_ava_porcentagem, porcentagem as porc_aluno from matriculas_avas_porcentagem where idava = " . $ava['idava'] . " and idmatricula = " . (int)$this->id;
            $andamento = $this->retornarLinha($this->sql);
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

    public function salvarAndamentoDisciplina($idbloco_disciplina)
    {
        $andamento = array();
        $ava = $this->retornarAva($idbloco_disciplina);
        $totalPorcentagem = 0;
        $totalPorcentagemAluno = 0;
        $porcentagem['conteudo'] = 0;
        $porcentagem['chat'] = 0;
        $porcentagem['forum'] = 0;
        $porcentagem['biblioteca'] = 0;
        $porcentagem['tiraduvida'] = 0;
        if ($ava['idava']) {
            $this->sql = "select
                            IFNULL(sum(porcentagem_rota),0) as total_rota,
                            IFNULL(sum(porcentagem_chat),0) as total_chat,
                            IFNULL(sum(porcentagem_forum),0) as total_forum,
                            IFNULL(sum(porcentagem_tira_duvida),0) as total_tira_duvida,
                            IFNULL(sum(porcentagem_biblioteca),0) as total_biblioteca,
                            IFNULL(sum(porcentagem_simulado),0) as total_simulado
                          from
                            avas
                          where
                            idava = " . $ava['idava'];
            $porcentagens = $this->retornarLinha($this->sql);
            $totalPorcentagem = $porcentagens['total_rota'] + $porcentagens['total_chat'] + $porcentagens['total_forum'] + $porcentagens['total_tira_duvida'] + $porcentagens['total_biblioteca'] + $porcentagens['total_simulado'];

            $this->sql = "select
                            IFNULL(sum(porcentagem),0) as total
                          from
                            matriculas_rotas_aprendizagem_objetos
                          where
                            idava = " . $ava['idava'] . " and
                            idmatricula = " . (int)$this->id . " and
                            idobjeto is not null";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            $porcentagem['conteudo'] = $porcentagensAluno['total'];
            if ($porcentagens['total_rota'] < $porcentagensAluno['total']) {
                $porcentagem['conteudo'] = $porcentagens['total_rota'];
            }

            $this->sql = "select
                            count(*) as total
                          from
                            matriculas_rotas_aprendizagem_objetos
                          where
                            idava = " . $ava['idava'] . " and
                            idmatricula = " . (int)$this->id . " and
                            idchat IS NOT NULL";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['chat'] = $porcentagens['total_chat'];
            } else {
                $porcentagem['chat'] = 0;
            }

            $this->sql = "select
                            count(*) as total
                          from
                            matriculas_rotas_aprendizagem_objetos
                          where
                            idava = " . $ava['idava'] . " and
                            idmatricula = " . (int)$this->id . " and
                            idforum IS NOT NULL";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['forum'] = $porcentagens['total_forum'];
            } else {
                $porcentagem['forum'] = 0;
            }

            $this->sql = "select
                            count(*) as total
                          from
                            matriculas_rotas_aprendizagem_objetos
                          where
                            idava = " . $ava['idava'] . " and
                            idmatricula = " . (int)$this->id . " and
                            iddownload IS NOT NULL";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['biblioteca'] = $porcentagens['total_biblioteca'];
            } else {
                $porcentagem['biblioteca'] = 0;
            }

            $this->sql = "SELECT
                            COUNT(*) AS total
                          FROM
                            matriculas_rotas_aprendizagem_objetos
                          WHERE
                            idava = " . $ava['idava'] . " AND
                            idmatricula = " . (int)$this->id . " AND
                            (
                                idtiraduvida IS NOT NULL OR
                                idmensagem_instantanea IS NOT NULL
                            )";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['tiraduvida'] = $porcentagens['total_tira_duvida'];
            } else {
                $porcentagem['tiraduvida'] = 0;
            }

            $this->sql = "SELECT
                            COUNT(*) AS total
                          FROM
                            matriculas_rotas_aprendizagem_objetos
                          WHERE
                            idava = " . $ava['idava'] . " AND
                            idmatricula = " . (int)$this->id . " AND
                            idsimulado IS NOT NULL";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['simulado'] = $porcentagens['total_simulado'];
            } else {
                $porcentagem['simulado'] = 0;
            }

            $totalPorcentagemAluno = $porcentagem['conteudo'] + $porcentagem['chat'] + $porcentagem['forum'] + $porcentagem['biblioteca'] + $porcentagem['tiraduvida'] + $porcentagem['simulado'];
        }
        $andamento["porc_total"] = $totalPorcentagem;
        $andamento["porc_aluno"] = $totalPorcentagemAluno;
        if ($andamento["porc_aluno"] >= 100) {
            $andamento["porc_aluno"] = 100;
            $andamento["porc_aluno_formatada"] = 100;
        } else {
            $andamento["porc_aluno_formatada"] = number_format($andamento["porc_aluno"], 2, ",", ".");
        }
        if ($ava['idava']) {
            $this->sql = "select idmatricula_ava_porcentagem, count(*) as total from matriculas_avas_porcentagem where idava = " . $ava['idava'] . " and idmatricula = " . (int)$this->id;
            $verificaPorcentagem = $this->retornarLinha($this->sql);
            if ($verificaPorcentagem['total'] > 0) {
                $this->sql = "update matriculas_avas_porcentagem set porcentagem = (" . $andamento["porc_aluno"] . ") where idmatricula_ava_porcentagem = " . $verificaPorcentagem['idmatricula_ava_porcentagem'];
            } else {
                $this->sql = "insert into matriculas_avas_porcentagem set idmatricula = " . (int)$this->id . ", idava = " . $ava['idava'] . ", porcentagem = " . $andamento["porc_aluno"];
            }
            if (!$this->executaSql($this->sql)) {
                $andamento = array();
                $andamento["erro"] = true;
                $andamento["erros"][] = $this->sql;
                $andamento["erros"][] = mysql_error();
            }
        }
        return $andamento;
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

    public function LancarNotas($idmatricula, $iddisciplina, $nota, $idtipo, $idmodelo, $id_solicitacao_prova = null, $aproveitamento_estudo = 'N')
    {
        //$nota = str_replace(",", ".", $nota);

        if ($nota > 10) $nota = 10;
        if ($nota < 0) $nota = 0;

        if (!$id_solicitacao_prova) {
            $id_solicitacao_prova = 'NULL';
        }

        $this->executaSql("BEGIN");
        $this->sql = "insert into
                        matriculas_notas
                      set
                          idmatricula = " . intval($idmatricula) . ",
                          idprova = NULL,
                          iddisciplina = " . intval($iddisciplina) . ",
                          aproveitamento_estudo = '" . $aproveitamento_estudo . "',
                          ativo = 'S',
                          data_cad = now()";

        if ($aproveitamento_estudo != 'S') {
            $this->sql .= ", id_solicitacao_prova = " . $id_solicitacao_prova . ",
                             nota = " . str_replace(",", ".", $nota) . ",
                             idtipo = " . intval($idtipo) . ",
                             idmodelo = " . intval($idmodelo);
        } else {
            $nota = 'AE';
        }

        if ($this->executaSql($this->sql)) {
            // No lugar de DE, foi colocado o ID da disciplina para poder imprimir a mensagem no histórico.
            if ($this->AdicionarHistorico($this->idusuario, "notas", "cadastrou", $iddisciplina, $nota, mysql_insert_id())) {
                $this->executaSql("COMMIT");
            } else {
                $this->executaSql("ROLLBACK");
            }
        } else {
            $this->executaSql("ROLLBACK");
        }
        return $notas;
    }

    public function RemoverNotas($idmatricula, $iddisciplina, $idmatricula_nota)
    {
        $this->sql = "select *
                    FROM matriculas_notas
          WHERE
          idmatricula_nota = '" . intval($idmatricula_nota) . "'";
        $nota = $this->retornarLinha($this->sql);
        $this->sql = "update
                    matriculas_notas
                  set
            ativo = 'N'
          WHERE
          idmatricula_nota = '" . intval($idmatricula_nota) . "' and
          idmatricula = '" . intval($idmatricula) . "' and
          iddisciplina = '" . intval($iddisciplina) . "'";
        $insere = $this->executaSql($this->sql);
        if ($nota['aproveitamento_estudo'] == 'S') {
            $nota['nota'] = 'AE';
        }
        // No lugar de DE, foi colocado o ID da disciplina para poder imprimir a mensagem no histórico.
        $this->AdicionarHistorico($this->idusuario, "notas", "removeu", $iddisciplina, $nota["nota"], $insere);
        return $notas;
    }

    public function ModificarNotas($idmatricula, $notas)
    {
        $this->executaSql('START TRANSACTION');
        foreach ($notas as $iddisciplina => $disciplina) {
            foreach ($disciplina as $idmatricula_nota => $nota) {
                $this->sql = "select *
                FROM matriculas_notas
                WHERE
                idmatricula_nota = '" . $idmatricula_nota . "' and idprova IS NULL and id_solicitacao_prova IS NULL "; # tipo_avaliacao = '0'
                $nota_antiga = $this->retornarLinha($this->sql);
                if ($nota_antiga['nota'] != str_replace(',', '.', $nota)) {
                    $this->sql = "update
                  matriculas_notas
                  set
                  nota = '" . str_replace(',', '.', $nota) . "'
              WHERE
              idmatricula_nota = '" . $idmatricula_nota . "' and
              idmatricula = '" . $idmatricula . "' and
              iddisciplina = '" . $iddisciplina . "'";
                    $insere = $this->executaSql($this->sql);
                    if ($insere) {
                        $notas_modificadas = true;
                        $this->AdicionarHistorico($this->idusuario, "notas", "modificou", $nota_antiga["nota"], $nota, $idmatricula_nota);
                    } else {
                        $retorno["erro"] = true;
                        $retorno["erros"][] = 'erro_atualizar_notas';
                        $this->executaSql('ROLLBACK');
                        return $retorno;
                    }
                }
            }
        }
        $this->executaSql('COMMIT');
        if ($notas_modificadas) {
            $retorno["sucesso"] = true;
        } else {
            $retorno["erro"] = true;
            $retorno["erros"][] = 'erro_atualizar_notas_vazio';
            $this->executaSql('ROLLBACK');
            return $retorno;
        }
        return $retorno;
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

    public function retornarNomeLogradouro($idlogradouro)
    {
        $sql = "select nome FROM logradouros where idlogradouro = '" . $idlogradouro . "' ";
        $linha = $this->retornarLinha($sql);
        return $linha['nome'];
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

    public function retornarObjetosRota($idbloco_disciplina, $objeto_divisor = false)
    {
        $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
        $objetos = array();
        $this->sql = "SELECT
            cbd.idbloco_disciplina,
            ara.idrota_aprendizagem,
            ara.nome as rota,
            arao.idobjeto,
            ara.idava
          FROM
            curriculos_blocos_disciplinas cbd
            INNER JOIN curriculos_blocos cb ON (cbd.idbloco = cb.idbloco)
            INNER JOIN ofertas_curriculos_avas oca ON (oca.ativo = 'S' AND oca.iddisciplina = cbd.iddisciplina AND oca.idcurriculo = cb.idcurriculo AND oca.idoferta = " . $oferta['idoferta'] . ")
            INNER JOIN avas_rotas_aprendizagem ara ON (oca.idava = ara.idava and ara.ativo = 'S')
            INNER JOIN avas_rotas_aprendizagem_objetos arao ON (ara.idrota_aprendizagem = arao.idrota_aprendizagem AND arao.ativo = 'S' AND (arao.vencimento >= '" . date('Y-m-d') . "' OR arao.vencimento IS NULL))
          WHERE
            cbd.ativo = 'S' AND
            cbd.idbloco_disciplina = " . intval($idbloco_disciplina);
        if (!$objeto_divisor) {
            $this->sql .= " AND arao.tipo <> 'objeto_divisor'";
        }
        $this->ordem = "asc";
        $this->ordem_campo = "arao.ordem ASC, arao.data_cad ASC, arao.idobjeto";
        $this->limite = -1;
        $objetos = $this->retornarLinhas();
        foreach ($objetos as $ind => $objeto) {
            $objetos[$ind]["objeto"] = $this->retornarObjeto($objeto["idobjeto"], $objeto["idbloco_disciplina"]);
            $objetos[$ind]['objeto']['ultimo_objeto'] = $this->retornarMaiorOrdemObjetoVisualizadoAluno($objeto["idava"]);
        }
        return $objetos;
    }

    public function retornarMaiorOrdemObjetoVisualizadoAluno($idava)
    {
        $sql = 'SELECT max(arao.ordem) as ordem
        FROM matriculas_rotas_aprendizagem_objetos mrao
          INNER JOIN avas_rotas_aprendizagem_objetos arao
            on mrao.idobjeto = arao.idobjeto
      INNER JOIN avas_rotas_aprendizagem ara
            on arao.idrota_aprendizagem = ara.idrota_aprendizagem and ara.idava = ' . $idava . '
        where
          mrao.idmatricula = ' . $this->id;
        return $this->retornarLinha($sql);
    }

    //caso o gestor ou professor esteja acessando o aluno irá veriicar se o aluno já visualizou o objeto, pois ele só poderá ver se o aluno tiver visto
    public function verificaObjetoVisualizadoAluno($idava, $idobjeto)
    {
        //Se for o professor ou gestor acessando como aluno irá verificar se o aluno já fisualizou o objeto
        if (verificaPermissaoAcesso(false)) {
            return true;
        } else {
            $sql = "SELECT
                            count(arao.idobjeto) as total
                        FROM
                            matriculas_rotas_aprendizagem_objetos mrao
                            INNER JOIN avas_rotas_aprendizagem_objetos arao ON (arao.idobjeto = mrao.idobjeto)
                            INNER JOIN avas_rotas_aprendizagem ara  ON (ara.idrota_aprendizagem = arao.idrota_aprendizagem and ara.idava = " . $idava . ")
                        WHERE
                            mrao.idmatricula = '" . $this->id . "' AND
                            arao.idobjeto = '" . intval($idobjeto) . "'";
            $retorno = $this->retornarLinha($sql);
            if ($retorno["total"] > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function retornarArquivosCurso()
    {
        $this->sql = "select
                    ca.*
                  FROM
                    matriculas m
                    INNER JOIN ofertas_cursos_escolas ocp on (m.idoferta = ocp.idoferta and m.idescola = ocp.idescola and m.idcurso = ocp.idcurso and ocp.ativo = 'S')
                    INNER JOIN curriculos_arquivos ca on (ocp.idcurriculo = ca.idcurriculo and ca.ativo = 'S')
                  where
                    m.ativo = 'S' and
                    m.idpessoa = " . intval($this->idpessoa) . " and
                    m.idmatricula = " . intval($this->id);
        $this->ordem = "asc";
        $this->ordem_campo = "ca.data_cad asc, ca.idarquivo";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function retornarArquivoCurso($idarquivo)
    {
        $this->sql = "select
                    *
                  FROM
                    curriculos_arquivos
                  where
                    ativo = 'S' and
                    idarquivo = " . intval($idarquivo);
        return $this->retornarLinha($this->sql);
    }

    public function cadastrarAnotacao()
    {
        if (verificaPermissaoAcesso(false)) {
            if (senhaSegura($this->id, $GLOBALS["config"]["chaveLogin"]) == $this->post["idmatricula"] && senhaSegura($this->post["disciplina"], $GLOBALS["config"]["chaveLogin"]) == $this->post["iddisciplina"] && senhaSegura($this->post["rota"], $GLOBALS["config"]["chaveLogin"]) == $this->post["idrota"] && senhaSegura($this->post["objeto"], $GLOBALS["config"]["chaveLogin"]) == $this->post["idobjeto"] && $this->post["anotacao"]) {
                $this->sql = "insert into
                          matriculas_anotacoes
                        set
                          data_cad = now(),
                          idmatricula = " . $this->id . ",
                          idbloco_disciplina = " . $this->post["disciplina"] . ",
                          idrota_aprendizagem = " . $this->post["rota"] . ",
                          idobjeto = " . $this->post["objeto"] . ",
                          anotacao = '" . $this->post["anotacao"] . "'";
                if ($this->executaSql($this->sql)) {
                    $retorno["id"] = mysql_insert_id();
                    $this->monitora_oque = 1;
                    $this->monitora_onde = "143";
                    $this->monitora_qual = $retorno["id"];
                    $this->Monitora();
                    $retorno["sucesso"] = true;
                    $retorno["anotacoes"] = $this->retornarAnotacoes($this->post["disciplina"], $this->post["rota"], $this->post["objeto"]);
                    $this->cadastrarHistorioAluno($this->post["disciplina"], "cadastrou", "anotacao", $retorno["id"]);
                } else {
                    $retorno["erro"] = true;
                    $retorno["erros"][] = $this->sql;
                    $retorno["erros"][] = mysql_error();
                }
            } else {
                $retorno["erro"] = true;
                $retorno["erros"][] = "dados_nao_conferem";
            }
            return json_encode($retorno);
        } else {
            $retorno['erro_json'] = "sem_permissao";
            return json_encode($retorno);
        }
    }

    public function deletarAnotacao()
    {
        if (verificaPermissaoAcesso(false)) {
            if (senhaSegura($this->id, $GLOBALS["config"]["chaveLogin"]) == $this->post["idmatricula"] && senhaSegura($this->post["disciplina"], $GLOBALS["config"]["chaveLogin"]) == $this->post["iddisciplina"] && senhaSegura($this->post["rota"], $GLOBALS["config"]["chaveLogin"]) == $this->post["idrota"] && senhaSegura($this->post["objeto"], $GLOBALS["config"]["chaveLogin"]) == $this->post["idobjeto"] && $this->post["idanotacao"]) {
                $this->sql = "update matriculas_anotacoes set ativo = 'N' where idanotacao = " . $this->post["idanotacao"];
                if ($this->executaSql($this->sql)) {
                    $retorno["id"] = mysql_insert_id();
                    $this->monitora_oque = 3;
                    $this->monitora_onde = "143";
                    $this->monitora_qual = $retorno["id"];
                    $this->Monitora();
                    $retorno["sucesso"] = true;
                    $retorno["anotacoes"] = $this->retornarAnotacoes($this->post["disciplina"], $this->post["rota"], $this->post["objeto"]);
                    $this->cadastrarHistorioAluno($this->post["disciplina"], "removeu", "anotacao", $retorno["id"]);
                } else {
                    $retorno["erro"] = true;
                    $retorno["erros"][] = $this->sql;
                    $retorno["erros"][] = mysql_error();
                }
            } else {
                $retorno["erro"] = true;
                $retorno["erros"][] = "dados_nao_conferem";
            }
            return json_encode($retorno);
        } else {
            $retorno['erro_json'] = "sem_permissao";
            return json_encode($retorno);
        }
    }

    public function retornarAnotacoes($idbloco_disciplina, $idrota_aprendizagem, $idobjeto)
    {
        $anotacoes = array();
        $this->sql = "select
                    *
                  FROM
                    matriculas_anotacoes
                  where
                    ativo = 'S' and
                    idbloco_disciplina = " . intval($idbloco_disciplina) . " and
                    idrota_aprendizagem = " . intval($idrota_aprendizagem) . " and
                    idobjeto = " . intval($idobjeto);
        $this->ordem = "desc";
        $this->ordem_campo = "data_cad desc, idanotacao";
        $this->limite = -1;
        $anotacoes = $this->retornarLinhas();
        foreach ($anotacoes as $ind => $anotacao) {
            $anotacoes[$ind]["anotacao"] = nl2br($anotacao["anotacao"]);
        }
        return $anotacoes;
    }

    public function cadastrarPergunta()
    {
        if (verificaPermissaoAcesso(false)) {
            $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
            if (senhaSegura($this->id, $GLOBALS["config"]["chaveLogin"]) == $this->post["idmatricula"] && senhaSegura($this->post["disciplina"], $GLOBALS["config"]["chaveLogin"]) == $this->post["iddisciplina"] && senhaSegura($this->post["rota"], $GLOBALS["config"]["chaveLogin"]) == $this->post["idrota"] && senhaSegura($this->post["objeto"], $GLOBALS["config"]["chaveLogin"]) == $this->post["idobjeto"] && $this->post["pergunta"]) {
                $this->sql = "select
                          oca.idava
                        FROM
                          curriculos_blocos_disciplinas cbd
                INNER JOIN curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
                inner join ofertas_curriculos_avas oca
                on oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . "
                          INNER JOIN avas_tira_duvidas atd on (oca.idava = atd.idava and atd.ativo = 'S')
                        where
                          cbd.ativo = 'S' and
                          cbd.idbloco_disciplina = " . intval($this->post["disciplina"]);
                $ava = $this->retornarLinha($this->sql);
                $this->sql = "insert into
                          avas_tira_duvidas
                        set
                          data_cad = now(),
                          idava = " . $ava["idava"] . ",
                          idmatricula = " . $this->id . ",
                          nome = 'Pergunta cadastrada pelo aluno',
                          pergunta = '" . $this->post["pergunta"] . "'";
                if ($this->executaSql($this->sql)) {
                    $retorno["id"] = mysql_insert_id();
                    $this->monitora_oque = 1;
                    $this->monitora_onde = "32";
                    $this->monitora_qual = $retorno["id"];
                    $this->Monitora();
                    $retorno["sucesso"] = true;
                    $retorno["perguntas"] = $this->retornarPerguntas($this->post["disciplina"]);
                } else {
                    $retorno["erro"] = true;
                    $retorno["erros"][] = $this->sql;
                    $retorno["erros"][] = mysql_error();
                }
            } else {
                $retorno["erro"] = true;
                $retorno["erros"][] = "dados_nao_conferem";
            }
            return json_encode($retorno);
        }
    }

    public function retornarPerguntas($idbloco_disciplina)
    {
        $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
        $duvidas = array();
        $this->sql = "select
                    atd.*
                  FROM
                    curriculos_blocos_disciplinas cbd
          INNER JOIN curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
          inner join ofertas_curriculos_avas oca
            on oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . "
                    INNER JOIN avas_tira_duvidas atd on (oca.idava = atd.idava and atd.ativo = 'S')
                  where
                    cbd.ativo = 'S' and
                    cbd.idbloco_disciplina = " . intval($idbloco_disciplina);
        $this->ordem = "desc";
        $this->ordem_campo = "atd.data_cad desc, atd.idduvida";
        $this->limite = -1;
        $duvidas = $this->retornarLinhas();
        foreach ($duvidas as $ind => $duvida) {
            $duvidas[$ind]["resposta"] = nl2br($duvida["resposta"]);
            $duvidas[$ind]["pergunta"] = nl2br($duvida["pergunta"]);
        }
        return $duvidas;
    }

    public function favoritar()
    {
        if (verificaPermissaoAcesso(false)) {
            if (senhaSegura($this->id, $GLOBALS["config"]["chaveLogin"]) == $this->post["idmatricula"] && senhaSegura($this->post["disciplina"], $GLOBALS["config"]["chaveLogin"]) == $this->post["iddisciplina"] && senhaSegura($this->post["rota"], $GLOBALS["config"]["chaveLogin"]) == $this->post["idrota"] && senhaSegura($this->post["objeto"], $GLOBALS["config"]["chaveLogin"]) == $this->post["idobjeto"]) {
                $this->sql = "select idfavorito, ativo  FROM matriculas_objetos_favoritos where idmatricula = " . $this->id . " and idbloco_disciplina = " . $this->post["disciplina"] . " and idrota_aprendizagem = " . $this->post["rota"] . " and idobjeto = " . $this->post["objeto"];
                $verifica = $this->retornarLinha($this->sql);
                if (!$verifica["idfavorito"]) {
                    $this->sql = "insert into
                            matriculas_objetos_favoritos
                          set
                            data_cad = now(),
                            idmatricula = " . $this->id . ",
                            idbloco_disciplina = " . $this->post["disciplina"] . ",
                            idrota_aprendizagem = " . $this->post["rota"] . ",
                            idobjeto = " . $this->post["objeto"];
                    $this->monitora_oque = 1;
                    $retorno["favorito"] = "S";
                    $tipoHistorico = "cadastrou";
                } elseif ($verifica["ativo"] == "N") {
                    $this->sql = "update matriculas_objetos_favoritos set ativo = 'S' where idfavorito = " . $verifica["idfavorito"];
                    $this->monitora_oque = 2;
                    $this->monitora_dadosantigos = $verifica;
                    $this->monitora_dadosnovos = array(
                        "idfavorito" => $verifica["idfavorito"],
                        "ativo" => "S"
                    );
                    $retorno["favorito"] = "S";
                    $tipoHistorico = "cadastrou";
                } else {
                    $this->sql = "update matriculas_objetos_favoritos set ativo = 'N' where idfavorito = " . $verifica["idfavorito"];
                    $this->monitora_oque = 3;
                    $retorno["favorito"] = "N";
                    $tipoHistorico = "removeu";
                }
                if ($this->executaSql($this->sql)) {
                    if ($verifica["idfavorito"])
                        $retorno["id"] = $verifica["idfavorito"];
                    else
                        $retorno["id"] = mysql_insert_id();
                    $this->cadastrarHistorioAluno($this->post["disciplina"], $tipoHistorico, "favorito", $retorno["id"]);
                    $this->monitora_onde = "144";
                    $this->monitora_qual = $retorno["id"];
                    $this->Monitora();
                    $retorno["sucesso"] = true;
                } else {
                    $retorno["erro"] = true;
                    $retorno["erros"][] = $this->sql;
                    $retorno["erros"][] = mysql_error();
                }
            } else {
                $retorno["erro"] = true;
                $retorno["erros"][] = "dados_nao_conferem";
            }
            return json_encode($retorno);
        } else {
            $retorno['erro_json'] = "sem_permissao";
            return json_encode($retorno);
        }
    }

    public function retornarConteudos($idbloco_disciplina)
    {
        $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
        $this->sql = "select
                    ara.idrota_aprendizagem,
                    arao.idobjeto,
                    ac.*
                  FROM
                    curriculos_blocos_disciplinas cbd
          INNER JOIN curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
          inner join ofertas_curriculos_avas oca
            on oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . "
                    INNER JOIN avas_rotas_aprendizagem ara on (oca.idava = ara.idava and ara.ativo = 'S')
                    INNER JOIN avas_rotas_aprendizagem_objetos arao on (ara.idrota_aprendizagem = arao.idrota_aprendizagem and arao.ativo = 'S')
                    INNER JOIN avas_conteudos ac on (arao.idconteudo = ac.idconteudo and ac.ativo = 'S')
                  where
                    cbd.ativo = 'S' and
                    cbd.idbloco_disciplina = " . intval($idbloco_disciplina);
        $this->ordem = "desc";
        $this->ordem_campo = "ac.ordem asc, ac.data_cad desc, ac.idconteudo";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function retornarAvaliacoesPendentes()
    {
        $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
        $this->sql = "select
                    aa.idavaliacao
                  FROM
                    matriculas m
                    INNER JOIN matriculas_workflow mw on (m.idsituacao = mw.idsituacao and mw.ativa = 'S')
                    INNER JOIN ofertas_cursos_escolas ocp on (m.idoferta = ocp.idoferta and m.idescola = ocp.idescola and m.idcurso = ocp.idcurso and ocp.ativo = 'S')
                    INNER JOIN curriculos_blocos cb on (ocp.idcurriculo = cb.idcurriculo and cb.ativo = 'S')
                    INNER JOIN curriculos_blocos_disciplinas cbd on (cb.idbloco = cbd.idbloco and cbd.ativo = 'S')
          inner join ofertas_curriculos_avas oca
            on oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . "
                    INNER JOIN avas_avaliacoes aa on (oca.idava = aa.idava and aa.ativo = 'S' and aa.exibir_ava = 'S')
                  where
                    m.ativo = 'S' and
                    m.idpessoa = " . intval($this->idpessoa) . " and
                    m.idmatricula = " . intval($this->id) . " and
          aa.periode_de <= '" . date("Y-m-d") . "' and
                    aa.periode_ate >= '" . date("Y-m-d") . "' and
          (select count(*) FROM matriculas_avaliacoes ma where ma.idmatricula = m.idmatricula and ma.idavaliacao = aa.idavaliacao) = 0";
        $this->ordem = "asc";
        $this->ordem_campo = "aa.periode_ate, aa.idavaliacao";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function retornarMultimidia($idbloco_disciplina)
    {
        $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
        $this->sql = "select
                    ara.idrota_aprendizagem,
                    arao.idobjeto,
                    arao.tipo,
                    aa.nome as audio,
                    aa.descricao as descricao_audio,
                    aa.arquivo_servidor as arquivo_servidor_audio,
                    aa.imagem_exibicao_servidor as imagem_exibicao_servidor_audio,
                    av.nome as video,
                    av.descricao as descricao_video,
                    av.idvideo_videoteca,
                    av.youtube,
                    av.imagem_exibicao_servidor as imagem_exibicao_servidor_video
                  FROM
                    curriculos_blocos_disciplinas cbd
          INNER JOIN curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
          inner join ofertas_curriculos_avas oca
            on oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . "
                    INNER JOIN avas_rotas_aprendizagem ara on (oca.idava = ara.idava and ara.ativo = 'S')
                    inner join avas_rotas_aprendizagem_objetos arao on (ara.idrota_aprendizagem = arao.idrota_aprendizagem and arao.ativo = 'S')
                    left outer join avas_audios aa on (arao.idaudio = aa.idaudio and aa.ativo = 'S')
                    left outer join avas_videotecas av on (arao.idvideo = av.idvideo and av.ativo = 'S')
                  where
                    cbd.ativo = 'S' and
                    (arao.tipo = 'audio' or arao.tipo = 'video') and
                    cbd.idbloco_disciplina = " . intval($idbloco_disciplina);
        $this->ordem = "desc";
        $this->ordem_campo = "aa.ordem asc, av.ordem asc, aa.data_cad desc, av.data_cad desc, aa.idaudio, av.idvideo";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function retornarFavoritos($idbloco_disciplina)
    {
        $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
        $favoritos = array();
        $this->sql = "select
                    ara.idrota_aprendizagem,
                    mof.*
                  FROM
                    curriculos_blocos_disciplinas cbd
          inner join curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
          inner join ofertas_curriculos_avas oca
            on oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . "
                    inner join avas_rotas_aprendizagem ara on (oca.idava = ara.idava and ara.ativo = 'S')
                    inner join matriculas_objetos_favoritos mof on (mof.idmatricula = " . $this->id . " and cbd.idbloco_disciplina = mof.idbloco_disciplina and ara.idrota_aprendizagem = mof.idrota_aprendizagem and mof.ativo = 'S')
                  where
                    cbd.ativo = 'S' and
                    cbd.idbloco_disciplina = " . intval($idbloco_disciplina);
        $this->ordem = "desc";
        $this->ordem_campo = "mof.data_cad desc, mof.idfavorito";
        $this->limite = -1;
        $favoritos = $this->retornarLinhas();
        foreach ($favoritos as $ind => $favorito) {
            $favoritos[$ind]["objeto"] = $this->retornarObjeto($favorito["idobjeto"], $idbloco_disciplina);
        }
        return $favoritos;
    }

    public function verificaFavorito($idbloco_disciplina, $idrota_aprendizagem, $idobjeto)
    {
        $this->sql = "select * FROM matriculas_objetos_favoritos where ativo = 'S' and idmatricula = " . $this->id . " and idbloco_disciplina = " . $idbloco_disciplina . " and idrota_aprendizagem = " . $idrota_aprendizagem . " and idobjeto = " . $idobjeto;
        $verifica = $this->retornarLinha($this->sql);
        if ($verifica["idfavorito"]) {
            return true;
        } else {
            return false;
        }
    }

    public function retornarDownloads($idbloco_disciplina)
    {
        $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
        $this->sql = "select
          adp.*
          FROM
                    curriculos_blocos_disciplinas cbd
          inner join curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
          inner join ofertas_curriculos_avas oca
            on oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . "
                    inner join avas_downloads ad on (oca.idava = ad.idava and ad.ativo = 'S' and ad.exibir_ava = 'S')
          inner join avas_downloads_pastas adp on (ad.idpasta = adp.idpasta and adp.ativo = 'S')
                  where
                    cbd.ativo = 'S' and
                    cbd.idbloco_disciplina = " . intval($idbloco_disciplina) . "
          group by idpasta";
        $this->ordem = "asc";
        $this->ordem_campo = "adp.nome";
        $this->limite = -1;
        $pastas = $this->retornarLinhas();
        $retorno = array();
        foreach ($pastas as $ind => $pasta) {
            $retorno[$ind] = $pasta;
            $this->sql = "select
            ad.*
          FROM
            curriculos_blocos_disciplinas cbd
          inner join curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
          inner join ofertas_curriculos_avas oca
            on oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . "
                    inner join avas_downloads ad on (oca.idava = ad.idava and ad.ativo = 'S' and ad.exibir_ava = 'S')
            inner join avas_downloads_pastas adp on (ad.idpasta = adp.idpasta and adp.ativo = 'S')
          where
            cbd.ativo = 'S' and
            cbd.idbloco_disciplina = " . intval($idbloco_disciplina) . " and
            ad.idpasta = " . $pasta['idpasta'];
            $this->ordem = "desc";
            $this->ordem_campo = "ad.ordem asc, ad.data_cad desc, ad.iddownload";
            $this->limite = -1;
            $retorno[$ind]['arquivos'] = $this->retornarLinhas();
        }
        return $retorno;
    }

    public function retornarDownload($idbloco_disciplina, $iddownload)
    {
        $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
        $sql = "select
        ad.*
      FROM
        curriculos_blocos_disciplinas cbd
    inner join curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
    inner join ofertas_curriculos_avas oca
            on oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . "
        inner join avas_downloads ad on (oca.idava = ad.idava and ad.ativo = 'S')
      where
        cbd.ativo = 'S' and
        cbd.idbloco_disciplina = " . intval($idbloco_disciplina) . " and
        ad.iddownload = " . intval($iddownload);
        return $this->retornarLinha($sql);
    }

    public function retornarContasNaoPagasMatricula($idmatricula, $contas_especificas = null, $tipo_conta = null)
    {

        $this->sql = "select
                    c.*,
                    ef.nome as evento,
                    bc.nome as bandeira_cartao,
                    b.nome as banco,
                    cw.nome as situacao,
                    cor_nome,
                    cor_bg
                  FROM
                    contas c
                    inner join contas_workflow cw on (c.idsituacao = cw.idsituacao)
                    inner join eventos_financeiros ef on (c.idevento = ef.idevento)
                    left outer join bandeiras_cartoes bc on (c.idbandeira = bc.idbandeira)
                    left outer join bancos b on (c.idbanco = b.idbanco)
                  where
                    c.idmatricula = " . $idmatricula . "
                    and c.idpagamento_compartilhado IS NULL
                    and c.ativo = 'S'
                    and cw.pago <> 'S'
                    and cw.cancelada <> 'S'
                    and cw.renegociada <> 'S'
          and cw.transferida <> 'S' ";
        if ($contas_especificas)
            $this->sql .= " and c.idconta in (" . implode(',', $contas_especificas) . ") ";
        if ($tipo_conta)
            $this->sql .= " and c.idevento = " . $tipo_conta . " ";
        $this->ordem = "asc";
        $this->ordem_campo = "c.data_vencimento";
        $this->limite = -1;
        $contas = $this->retornarLinhas();
        $retorno["contas"] = array();
        foreach ($contas as $conta) {
            $retorno["contas"][] = $conta;
        }
        return $retorno;
    }

    public function renegociarParcelasMatricula()
    {
        $erro = array();
        if (!$this->post["idevento"]) {
            $erro[] = "financeiro_idevento_vazio";
        }
        if (!$this->post["forma_pagamento"]) {
            $erro[] = "financeiro_forma_pagamento_vazio";
        } elseif ($this->post["forma_pagamento"] == 2 || $this->post["forma_pagamento"] == 3) {
            if (!$this->post["idbandeira"]) {
                $erro[] = "bandeira_cartao_vazio";
            }
            if (!$this->post["autorizacao_cartao"]) {
                $erro[] = "autorizacao_cartao_vazio";
            }
        } elseif ($this->post["forma_pagamento"] == 4) {
            if (!$this->post["idbanco"]) {
                $erro[] = "banco_cheque_vazio";
            }
            if (!$this->post["agencia_cheque"]) {
                $erro[] = "agencia_cheque_vazio";
            }
            if (!$this->post["cc_cheque"]) {
                $erro[] = "cc_cheque_vazio";
            }
            if (!$this->post["numero_cheque"]) {
                $erro[] = "numero_cheque_vazio";
            }
            if (!$this->post["emitente_cheque"]) {
                $erro[] = "emitente_cheque_vazio";
            }
        }
        if (!$this->post["quantidade_parcelas"]) {
            $erro[] = "financeiro_quantidade_parcelas_vazio";
        }
        if (!$this->post["valor"]) {
            $erro[] = "financeiro_valor_vazio";
        } else {
            $this->post["valor"] = floatval(str_replace(',', '.', str_replace('.', '', $this->post['valor'])));
        }
        if (!$this->post["vencimento"]) {
            $erro[] = "financeiro_vencimento_vazio";
        }
        if (!$this->post['parcelas_renegociadas']) {
            $erro[] = "financeiro_parcelas_vazio";
        }
        if (count($erro) <= 0) {
            $sql = "select * FROM contas_workflow where ativo = 'S' and emaberto = 'S' order by idsituacao desc limit 1";
            $situacaoEmAberto = $this->retornarLinha($sql);
            $sql = "select * FROM contas_workflow where ativo = 'S' and renegociada = 'S' order by idsituacao desc limit 1";
            $situacaoRenegociada = $this->retornarLinha($sql);
            mysql_query("START TRANSACTION");
            //MODIFICAR AS CONTAS ANTIGAS PARA RENEGOCIADO
            $idContasNegociadas = array();
            foreach ($this->post['parcelas_renegociadas'] as $parcela_negociada => $linha) {
                $idContasNegociadas[] = $parcela_negociada;
                $sql_antiga = "select idsituacao FROM contas where idconta = " . $parcela_negociada . " ";
                $antiga = $this->retornarLinha($sql_antiga);
                $sql_atualiza = "update contas set idsituacao = " . $situacaoRenegociada['idsituacao'] . " where idconta = " . $parcela_negociada . " ";
                $atualiza = $this->executaSql($sql_atualiza);
                if (!$atualiza) {
                    mysql_query("ROLLBACK");
                    $this->retorno["sucesso"] = false;
                    $this->retorno["mensagem"] = "mensagem_financeiro_erro_atualizar_conta";
                    return $this->retorno;
                } else {
                    $this->AdicionarHistorico($this->idusuario, "parcela", "renegociou", NULL, NULL, $parcela_negociada);
                    $sql = "insert into contas_historicos set data_cad = NOW(),
                                                     idconta = " . $parcela_negociada . ",
                                                     idusuario = " . $this->idusuario . ",
                                                     tipo = 'situacao',
                                                     acao = 'modificou',
                                                     de = " . $antiga['idsituacao'] . ",
                                                     para = " . $situacaoRenegociada['idsituacao'] . "  ";
                    $insere_historico = $this->executaSql($sql);
                    if (!$insere_historico) {
                        mysql_query("ROLLBACK");
                        $this->retorno["sucesso"] = false;
                        $this->retorno["mensagem"] = "mensagem_financeiro_erro_atualizar_conta_historico";
                        return $this->retorno;
                    }
                }
            }
            //MODIFICAR AS CONTAS ANTIGAS PARA RENEGOCIADO - FIM
            $sql = "insert into contas_relacoes set data_cad = now()";
            $this->executaSql($sql);
            $idRelacao = mysql_insert_id();
            if (!intval($this->post['quantidade_parcelas']) || $this->post["forma_pagamento"] == 3 || $this->post["forma_pagamento"] == 5) {
                $this->post['quantidade_parcelas'] = 1;
            }
            $valorParcela = round($this->post["valor"] / $this->post['quantidade_parcelas'], 2);
            $valorPrimeiraParcela = $valorParcela;
            $valorTotal = $valorParcela * $this->post['quantidade_parcelas'];
            if ($valorTotal <= $this->post["valor"]) {
                $valorPrimeiraParcela += ($this->post["valor"] - $valorTotal);
            } elseif ($valorTotal >= $this->post["valor"]) {
                $valorPrimeiraParcela += ($valorTotal - $this->post["valor"]);
            }
            $data = explode("/", $this->post["vencimento"]);
            $idContasRenegociadas = array();
            for ($parcela = 1; $parcela <= $this->post['quantidade_parcelas']; $parcela++) {
                $this->post['valor'] = $valorParcela;
                if ($parcela == 1)
                    $this->post['valor'] = $valorPrimeiraParcela;
                $vencimento = date("Y-m-d", mktime(0, 0, 0, ($data[1] + ($parcela - 1)), $data[0], $data[2]));
                $this->sql = "insert into
                        contas
                      set
                        renegociada = 'S',
                        data_cad = now(),
                        tipo = 'receita',
                        nome = 'Referente a uma parcela da matricula " . $this->id . "',
                        valor = " . $this->post['valor'] . ",
                        data_vencimento = '" . $vencimento . "',
                        idsituacao = " . $situacaoEmAberto['idsituacao'] . ",
                        idrelacao = " . $idRelacao . ",
                        idmantenedora = " . $this->post['idmantenedora'] . ",
                        idsindicato = " . $this->post['idsindicato'] . ",
                        idmatricula = " . $this->id . ",
                        idpessoa = " . $this->post['idpessoa'] . ",
                        idevento = " . $this->post['idevento'] . ",
            parcela = " . $parcela . ",
            total_parcelas = '" . $this->post['quantidade_parcelas'] . "' ";
                if ($this->post["forma_pagamento"] == 2 || $this->post["forma_pagamento"] == 3) {
                    $this->sql .= ", forma_pagamento = " . $this->post['forma_pagamento'] . ",
                        idbandeira = " . $this->post['idbandeira'] . ",
                        autorizacao_cartao = '" . $this->post['autorizacao_cartao'] . "'";
                } elseif ($this->post["forma_pagamento"] == 4) {
                    $this->sql .= ", forma_pagamento = " . $this->post['forma_pagamento'] . ",
                        idbanco = " . $this->post['idbanco'] . ",
                        agencia_cheque = '" . $this->post['agencia_cheque'] . "',
                        cc_cheque = '" . $this->post['cc_cheque'] . "',
                        numero_cheque = '" . $this->post['numero_cheque'] . "',
                        emitente_cheque = '" . $this->post['emitente_cheque'] . "'";
                } else {
                    $this->sql .= ", forma_pagamento = " . $this->post['forma_pagamento'];
                }
                $this->executaSql($this->sql);
                $idContasRenegociadas[] = $this->idconta = mysql_insert_id();
                $this->AdicionarHistorico($this->idusuario, "parcela", "cadastrou", NULL, NULL, $this->idconta);
                $this->monitora_onde = 52;
                $this->monitora_oque = 1;
                $this->monitora_qual = $this->idconta;
                $this->Monitora();
            }
            $sql_atualiza = "update contas set parcelas_renegociadas = '" . implode('; ', $idContasRenegociadas) . "' where idconta in (" . implode(', ', $idContasNegociadas) . ")";
            $atualiza = $this->executaSql($sql_atualiza);
            if (!$atualiza) {
                mysql_query("ROLLBACK");
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "mensagem_financeiro_erro_atualizar_conta";
                return $this->retorno;
            }
            $this->executaSql("commit");
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "mensagem_financeiro_cadastrado_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_financeiro_campos_obrigatorios";
        }
        return $this->retorno;
    }

    public function retornarDocumentosPendentes($idmatricula, $idsindicato, $idcurso)
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

    public function retornarSituacaoEmAbertoConta()
    {
        $sql = "select idsituacao FROM contas_workflow where emaberto = 'S' and ativo = 'S' limit 1 ";
        return $this->retornarLinha($sql);
    }

    public function retornarFinanceiroAluno()
    {
        // Busca a situação de cancelada
        $situacaoCancelada = $this->retornarSituacaoCancelada();
        // Busca a situação de inativa
        $situacaoInativa = $this->retornarSituacaoInativa();

        $matriculas = array();
        $this->sql = 'SELECT
                            m.* ,
                            c.nome AS curso,
                            c.imagem_exibicao_servidor,
                            o.nome AS oferta
                        FROM
                            matriculas m
                            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
                            INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                            INNER JOIN ofertas_cursos oc ON (o.idoferta = oc.idoferta AND c.idcurso = oc.idcurso AND oc.possui_financeiro = "S")
                        WHERE
                            m.ativo = "S" AND
                            m.idpessoa = ' . (int)$this->idpessoa . ' AND
                            m.idsituacao <> ' . (int)$situacaoCancelada['idsituacao'] . ' AND
                            m.idsituacao <> ' . (int)$situacaoInativa['idsituacao'];
        $this->ordem = 'ASC';
        $this->ordem_campo = 'm.data_matricula';
        $this->limite = -1;
        $matriculas = $this->retornarLinhas();

        foreach ($matriculas as $ind => $matricula) {
            $this->sql = 'SELECT
                                c.*,
                                bc.nome AS bandeira_cartao,
                                b.nome AS banco,
                                cw.nome AS situacao,
                                cw.pago AS situacao_paga,
                                cw.cancelada AS situacao_cancelada,
                                cw.fastconnect AS situacao_fastConnect,
                                cor_nome,
                                cor_bg,
                                pcm.valor AS valor_matricula,
                                cp.bandeira,
                                cp.tid,
                                cp.data_pagamento,
                                e.fastconnect_client_code,
                                e.fastconnect_client_key,
                                e.idescola,
                                (
                                    SELECT
                                        count(1)
                                    FROM
                                        contas c_interno
                                    WHERE
                                        c_interno.idpagamento_compartilhado = c.idpagamento_compartilhado AND
                                        c_interno.ativo = "S"
                                ) AS total_contas_compartilhadas,
                                (
                                    SELECT
                                        COUNT(f.idfastconnect)
                                    FROM
                                        fastconnect f
                                    WHERE
                                        f.idconta = c.idconta AND
                                        f.idsituacao IN (0,1,2,4) AND
                                        f.tipo <> "boleto" AND
                                        f.ativo = "S"
                                ) AS totalPagamentosAbertosFastConnect
                            FROM
                                contas c
                                INNER JOIN contas_workflow cw ON (c.idsituacao = cw.idsituacao)
                                LEFT OUTER JOIN contas_pagamentos cp ON (cp.idconta = c.idconta AND cp.status_transacao = "CAP")
                                LEFT OUTER JOIN bandeiras_cartoes bc ON (c.idbandeira = bc.idbandeira)
                                LEFT OUTER JOIN bancos b ON (c.idbanco = b.idbanco)
                                LEFT OUTER JOIN pagamentos_compartilhados_matriculas pcm ON (c.idpagamento_compartilhado = pcm.idpagamento AND pcm.idmatricula = ' . $matricula['idmatricula'] . ' AND pcm.ativo = "S")
                                LEFT OUTER JOIN matriculas m ON (m.idmatricula = c.idmatricula)
                                LEFT OUTER JOIN escolas e ON (e.idescola = m.idescola)
                            WHERE
                                (
                                    c.idmatricula = ' . $matricula['idmatricula'] . ' OR
                                    pcm.idmatricula = ' . $matricula['idmatricula'] . '
                                ) AND
                                c.ativo = "S"';
            $this->ordem = 'ASC';
            $this->ordem_campo = 'c.data_vencimento';
            $this->limite = -1;

            $matriculas[$ind]['contas'] = $this->retornarLinhas();

            foreach ($matriculas[$ind]['contas'] as $indConta => $varConta) {
                $matriculas[$ind]['contas'][$indConta]['totalPagamentosAbertos'] = $varConta['totalPagamentosAbertosPagseguro'];
                if ($varConta['forma_pagamento'] == 11 && $this->fastConnect) {
                    //FastConnect
                    $sql = 'SELECT
                                f.idsituacao,
                                f.link_pagamento,
                                f.tipo,
                                c.fastconnect_nu_link,
                                c.fastconnect_url_link
                            FROM
                                fastconnect f
                                INNER JOIN contas c ON (c.idconta = f.idconta)
                            WHERE
                                f.idconta = ' . $varConta['idconta'] . ' AND
                                f.ativo = "S"
                            ORDER BY
                                f.idfastconnect DESC
                            LIMIT 1';
                    $fastConnect = $this->retornarLinha($sql);

                    $matriculas[$ind]['contas'][$indConta]['fastConnect'] = $fastConnect;
                    $matriculas[$ind]['contas'][$indConta]['fastConnect']['fastconnect_client_code'] = $varConta['fastconnect_client_code'];
                    $matriculas[$ind]['contas'][$indConta]['fastConnect']['fastconnect_client_key'] = $varConta['fastconnect_client_key'];
                    $matriculas[$ind]['contas'][$indConta]['fastConnect']['idescola'] = $varConta['idescola'];
                }
            }
        }

        return $matriculas;
    }

    public function retornarParcelaBoleto()
    {

        $situacaoEmAbertoConta = $this->retornarSituacaoEmAbertoConta();

        $this->sql = "select
                        c.*,
                        cco.*,
                        c.nome as descritivo,
                        cur.nome as curso,
                        p.documento,
                        p.nome as sacado,
                        b.nome as banco,
                        b.codigo_banco,
                        i.logo_servidor,
                        b.pagina,
                        cw.nome as situacao
                    from
                        contas c
                        inner join matriculas m on (c.idmatricula = m.idmatricula and m.ativo = 'S')
                        inner join pessoas p on (m.idpessoa = p.idpessoa and p.ativo = 'S')
                        inner join cursos cur on (m.idcurso = cur.idcurso and cur.ativo = 'S')
                        inner join contas_correntes_sindicatos cci on (c.idsindicato = cci.idsindicato and cci.ativo = 'S')
                        inner join contas_correntes cco on (cci.idconta_corrente = cco.idconta_corrente and cco.ativo = 'S' and cco.boleto = 'S' and cco.ativo_painel = 'S')
                        inner join contas_workflow cw on (c.idsituacao = cw.idsituacao)
                        inner join bancos b on (cco.idbanco = b.idbanco and b.ativo = 'S' and b.ativo_painel = 'S')
                        inner join sindicatos i on (m.idsindicato = i.idsindicato)
                    where
                        c.idconta = " . $this->idconta . "
                        and c.idmatricula = " . $this->idmatricula . "
                        and c.idsituacao = " . $situacaoEmAbertoConta['idsituacao'] . "
                        and c.ativo = 'S'
                        and b.pagina is not null
                        and b.codigo_banco > 0
                        and cco.agencia is not null
                        and cco.conta is not null
                        and cco.conta_dig is not null
                        and cco.carteira is not null";

        $this->ordem = "asc";
        $this->ordem_campo = "c.data_vencimento";
        $this->groupby = "c.idconta";
        $this->limite = 1;


        $dados = $this->retornarLinha($this->sql);

        return $dados;
    }

    public function geraHistoricoBoleto($idconta, $idconta_corrente, $idbanco, $data_vencimento, $valor)
    {

        $this->sql = 'insert into contas_boletos_gerado set data_cad = now(), idconta = ' . $idconta . ', idconta_corrente = ' . $idconta_corrente . ', idbanco = ' . $idbanco . ', data_vencimento = "' . $data_vencimento . '", valor = ' . $valor;
        $this->executaSql($this->sql);
    }

    public function retornarDeclaracoesAluno()
    {

        // Busca a situação de ativa
        $situacaoAtiva = $this->retornarSituacaoAtiva();

        // Busca a situação de concluida
        $situacaoConcluida = $this->retornarSituacaoConcluida();

        $matriculas = array();
        $this->sql = "SELECT
                        m.* ,
                        c.nome as curso,
                        c.imagem_exibicao_servidor,
                        o.nome as oferta
                      FROM
                        matriculas m
                        inner join ofertas o on (m.idoferta = o.idoferta)
                        inner join cursos c on (m.idcurso = c.idcurso)
                      where
                        m.ativo = 'S' and
                        m.idpessoa = " . intval($this->idpessoa) . "";
        /* and
                        (m.idsituacao = " . $situacaoAtiva['idsituacao'] . " or
                        m.idsituacao = " . $situacaoConcluida['idsituacao'] . ")";*/
        $this->ordem = "asc";
        $this->ordem_campo = "m.idmatricula";
        $this->limite = -1;
        $matriculas = $this->retornarLinhas();
        foreach ($matriculas as $ind => $matricula) {
            $this->sql = "SELECT
                            sd.idsolicitacao_declaracao,
                            sd.idmatriculadeclaracao,
                            sd.data_solicitacao,
                            sd.data_geracao,
                            sd.situacao,
                            sd.motivo_cancelamento,
                            md.data_cad,
                            d.nome
                        FROM
                            matriculas_solicitacoes_declaracoes sd
                        LEFT OUTER JOIN
                            matriculas_declaracoes md ON (sd.idmatriculadeclaracao = md.idmatriculadeclaracao)
                        INNER JOIN
                            declaracoes d ON (d.iddeclaracao = sd.iddeclaracao)
                        WHERE
                            (sd.idmatricula = " . $matricula['idmatricula'] . ")
                            AND sd.ativo = 'S'";
            $this->ordem = "asc";
            $this->ordem_campo = "idsolicitacao_declaracao";
            $this->limite = -1;
            $deSolicitacoes = $this->retornarLinhas();
            $this->sql = "SELECT
                            NULL AS idsolicitacao_declaracao,
                            md.idmatriculadeclaracao,
                            NULL AS data_solicitacao,
                            NULL AS data_geracao,
                            NULL AS situacao,
                            md.data_cad,
                            d.nome
                        FROM
                            matriculas_declaracoes md
                        INNER JOIN
                           declaracoes d
                           ON (d.iddeclaracao = md.iddeclaracao)
                        WHERE
                            (md.idmatricula = " . $matricula["idmatricula"] . ")
                            AND md.ativo = 'S'
                            AND md.aluno_visualiza = 'S'
                            AND (SELECT sd.idsolicitacao_declaracao
                                  FROM matriculas_solicitacoes_declaracoes sd
                                  WHERE sd.idmatriculadeclaracao = md.idmatriculadeclaracao) IS NULL";
            $this->ordem_campo = "idmatriculadeclaracao";
            $this->ordem = "asc";
            $this->limite = -1;
            $doAdministrar = $this->retornarLinhas();
            $matriculas[$ind]["declaracoes"] = array_merge($deSolicitacoes, $doAdministrar);
        }

        return $matriculas;
    }

    public function retornarColegas($idoferta, $idcurso, $idescola, $idava, $letra = null)
    {

        $this->campos = "DISTINCT(p.idpessoa),
                         p.*";

        $this->sql = "
            SELECT
                " . $this->campos . "
            FROM
                pessoas p
                INNER JOIN matriculas m ON (m.idpessoa = p.idpessoa)
                INNER JOIN ofertas_cursos_escolas ocp ON (ocp.idoferta = m.idoferta AND m.idcurso = m.idcurso AND ocp.idescola = m.idescola)
                INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = ocp.idcurriculo)
                INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco)
                INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = m.idoferta AND oca.idcurriculo = ocp.idcurriculo)
                INNER JOIN matriculas_workflow_acoes mwa ON (mwa.idsituacao = m.idsituacao AND mwa.ativo = 'S' AND idopcao = '27')
              WHERE
                oca.idava = '" . $idava . "' AND
                (
                    (
                        (date_format(DATE_ADD(m.data_cad, INTERVAL ocp.dias_para_ava DAY),'%Y-%m-%d') >= NOW() OR ocp.dias_para_ava IS NULL) AND
                        (ocp.data_inicio_ava <= NOW() OR ocp.data_inicio_ava IS NULL) AND
                        (ocp.data_limite_ava >= NOW() OR ocp.data_limite_ava IS NULL) AND
                        m.data_prolongada IS NULL
                    )
                    OR
                    (m.data_prolongada >= now() OR m.data_prolongada IS NOT NULL)
                ) AND
                p.ativo = 'S' AND
                m.ativo = 'S' AND
                ocp.ativo = 'S' AND
                cb.ativo = 'S' AND
                cbd.ativo = 'S' AND
                oca.ativo = 'S' AND
                p.idpessoa <> " . intval($this->idpessoa);

        if ($letra) {
            $this->sql .= " AND p.nome like '" . $letra . "%'";
        }

        if ($_GET["q"]["2|p.nome"]) {
            $this->sql .= " AND p.nome like '%" . $_GET["q"]["2|p.nome"] . "%'";
        }


        $this->groupby = "DISTINCT(p.idpessoa)";
        $this->ordem = "asc";
        $this->ordem_campo = "p.nome";
        $pessoas = $this->retornarLinhas();

        return $pessoas;
    }

    public function cadastrarHistorioAluno($idbloco_disciplina, $acao, $oque, $id = "null")
    {
        if (verificaPermissaoAcesso(false)) {
            if (!$_SESSION["ultimo_acesso_ava"]) {
                $_SESSION["ultimo_acesso_ava"] = date("Y-m-d H:i:s");
                $this->sql = "update
                          matriculas
                        set
                          ultimo_acesso_ava = now(),
                          total_acessos_ava = total_acessos_ava + 1
                        where
                          idmatricula = " . $this->id;
                $this->executaSql($this->sql);
            }

            $this->sql = "insert
                        matriculas_alunos_historicos
                      set
                        idmatricula = " . $this->id . ",";

            if ($idbloco_disciplina)
                $this->sql .= " idbloco_disciplina = " . $idbloco_disciplina . ",";

            $this->sql .= " data_cad = now(),
                        acao = '" . $acao . "',
                        oque = '" . $oque . "',
                        id = " . $id;

            return $this->executaSql($this->sql);
        }
    }

    public function retornarAva($idbloco_disciplina)
    {
        $oferta = $this->retornarLinha('select idoferta from matriculas where idmatricula = ' . $this->id);
        $this->sql = "select
              a.*, cbd.iddisciplina
            FROM
              curriculos_blocos_disciplinas cbd
              inner join curriculos_blocos cb on (cbd.idbloco = cb.idbloco)
              inner join ofertas_curriculos_avas oca on (oca.ativo = 'S' and oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = " . $oferta['idoferta'] . ")
              inner join avas a on (a.ativo = 'S' and oca.idava = a.idava)
            where
              cbd.ativo = 'S' and cbd.idbloco_disciplina = " . intval($idbloco_disciplina);
        $this->ordem = "desc";
        $this->ordem_campo = "oca.idava";
        return $this->retornarLinha($this->sql);
    }

    /**
     *
     *
     * @return array with notes
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    private function _fetchNotas()
    {
        return $this->set('sql', 'SELECT * FROM matriculas WHERE ativo = "S"')->set('ordem', 'desc')->set('ordem_campo', 'idmatricula')->set('limite', 1)->retornarLinha($this->get('sql'));
    }

    /**
     * undocumented function
     *
     * @return string with informations
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function montarTabela($idMatricula, $type = 'html')
    {
        return $this->_fetchNotas();
    }

    function ListarTodasMensagens($idbloco_disciplina)
    {
        $this->sql = "select
            am.*,
            pd.idpessoa as idpessoa_de,
            pd.nome as pessoa_de,
            pd.avatar_servidor as avatar_servidor_de,
            pp.idpessoa as idpessoa_para,
            pp.nome as pessoa_para,
            pp.avatar_servidor as avatar_servidor_para
          FROM
            avas_mensagens am
            inner join matriculas md on (am.idmatricula_de = md.idmatricula)
            inner join matriculas mp on (am.idmatricula_para = mp.idmatricula)
            inner join pessoas pd on (md.idpessoa = pd.idpessoa)
            inner join pessoas pp on (mp.idpessoa = pp.idpessoa)
          where
            am.ativo = 'S' and
            (am.idmatricula_de = " . $this->id . " or am.idmatricula_para = " . $this->id . ") and
      am.idbloco_disciplina = " . $idbloco_disciplina;
        $this->ordem = 'desc';
        $this->ordem_campo = 'am.data_cad';
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function RetornarMensagem($idmensagem)
    {
        $this->sql = "select
            am.idmensagem,
            amt.idmensagem_texto,
            amt.idmatricula,
            amt.data_cad,
            amt.mensagem,
            amt.arquivo_nome,
            amt.arquivo_servidor,
            amt.arquivo_tipo,
            amt.arquivo_tamanho,
            p.idpessoa,
            p.nome,
            p.avatar_servidor
          FROM
            avas_mensagens am
            inner join avas_mensagens_texto amt on (am.idmensagem = amt.idmensagem)
            inner join matriculas m on (amt.idmatricula = m.idmatricula)
            inner join pessoas p on (m.idpessoa = p.idpessoa)
          where
            am.idmensagem = " . $idmensagem . " and
            am.ativo = 'S' and
            amt.ativo= 'S'";
        $this->ordem = 'asc';
        $this->ordem_campo = 'amt.data_cad';
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function RetornarMensagemDownload($idmensagem, $idmensagem_texto)
    {
        $this->sql = "select
            amt.*
          FROM
            avas_mensagens am
            inner join avas_mensagens_texto amt on (am.idmensagem = amt.idmensagem)
          where
            am.idmensagem = " . $idmensagem . " and
            amt.idmensagem_texto = " . $idmensagem_texto . " and
            am.ativo = 'S' and
            amt.ativo = 'S'";
        return $this->retornarLinha($this->sql);
    }

    function verificaColega($idoferta, $idcurso, $idescola, $idmatricula)
    {
        $this->sql = "select
            p.*,
            m.idmatricula
          FROM
            pessoas p
            inner join matriculas m on (p.idpessoa = m.idpessoa)
          where
            p.ativo = 'S' and
            m.ativo = 'S' and
            m.idoferta = " . $idoferta . " and
            m.idcurso = " . $idcurso . " and
            m.idescola = " . $idescola . " and
            m.idmatricula = " . $idmatricula;
        return $this->retornarLinha($this->sql);
    }

    function verificaMensagem($idmatricula, $idbloco_disciplina)
    {
        $this->sql = "select
            idmensagem
          FROM
            avas_mensagens
          where
            ativo = 'S' and
            (
            (idmatricula_de = " . $this->id . " and idmatricula_para = " . $idmatricula . ") or
            (idmatricula_de = " . $idmatricula . " and idmatricula_para = " . $this->id . ")
            ) and
      idbloco_disciplina = " . $idbloco_disciplina;
        return $this->retornarLinha($this->sql);
    }

    function CadastrarMensagemColega($idmatricula, $idbloco_disciplina)
    {
        if (verificaPermissaoAcesso(true)) {
            $arquivo = false;
            if ($_FILES["arquivo"]["tmp_name"]) {
                $validar = $this->ValidarArquivo($_FILES["arquivo"]);
                $extensao = strtolower(strrchr($_FILES["arquivo"]["name"], "."));
                if ($validar || ($extensao != ".jpg" && $extensao != ".jpeg" && $extensao != ".gif" && $extensao != ".png" && $extensao != ".bmp" && $extensao != ".pdf" && $extensao != ".doc" && $extensao != ".docx")) {
                    $retorno = array(
                        'erro' => true
                    );
                    if ($validar) {
                        $retorno["mensagem"] = $validar;
                    } else {
                        $retorno["mensagem"] = "contratos_matricula_extensao_erro";
                    }
                    return $retorno;
                } else {
                    $pasta = $_SERVER["DOCUMENT_ROOT"] . "/storage/avas_mensagens";
                    $nomeServidor = date("YmdHis") . "_" . uniqid() . $extensao;
                    $envio = move_uploaded_file($_FILES["arquivo"]["tmp_name"], $pasta . "/" . $nomeServidor);
                    chmod($pasta . "/" . $nomeServidor, 0777);
                    if ($envio) {
                        $arquivo = true;
                        $arquivo_nome = $_FILES["arquivo"]["name"];
                        $arquivo_tipo = $_FILES["arquivo"]["type"];
                        $arquivo_tamanho = $_FILES["arquivo"]["size"];
                    } else {
                        $retorno = array(
                            'erro' => true,
                            'mensagem' => ''
                        );
                        return $retorno;
                    }
                }
            }
            $mensagem = $this->verificaMensagem($idmatricula, $idbloco_disciplina);
            if ($mensagem['idmensagem']) {
                $idmensagem = $mensagem['idmensagem'];
            } else {
                $this->sql = 'insert into avas_mensagens set data_cad = now(), idbloco_disciplina = ' . $idbloco_disciplina . ', idmatricula_de = ' . $this->id . ', idmatricula_para = ' . $idmatricula;
                $this->executaSql($this->sql);
                $idmensagem = mysql_insert_id();
            }
            $this->sql = 'insert into avas_mensagens_texto set data_cad = now(), idmensagem = ' . $idmensagem . ', idmatricula = ' . $this->id . ', mensagem = "' . $this->post['mensagem'] . '"';
            if ($arquivo) {
                $this->sql .= ', arquivo_nome = "' . $arquivo_nome . '", arquivo_servidor = "' . $nomeServidor . '", arquivo_tipo = "' . $arquivo_tipo . '", arquivo_tamanho = ' . $arquivo_tamanho;
            }
            $this->executaSql($this->sql);
            $retorno = array(
                'sucesso' => true,
                'idmensagem' => $idmensagem
            );
            return $retorno;
        }
    }

    function retornarProfessores($idoferta, $idcurso, $idava, $letra = null)
    {
        $this->sql = "SELECT
            p.*
        FROM
            professores p
            INNER JOIN professores_avas pa on (p.idprofessor = pa.idprofessor)
        WHERE
            p.ativo = 'S' and
            pa.ativo = 'S' and
            pa.idava = " . $idava . " ";
        /*
        and
        pc.idcurso = ".$idcurso." and
        po.idoferta = ".$idoferta;
        */
        if ($letra)
            $this->sql .= " and p.nome like '" . $letra . "%'";
        $this->sql .= " group by p.idprofessor";
        $this->ordem = "asc";
        $this->ordem_campo = "p.nome";
        $this->limite = -1;
        $professores = $this->retornarLinhas();
        foreach ($professores as $ind => $professor) {
            $sql = "SELECT
                    pd.idprofessor, d.iddisciplina, d.nome
                FROM professores_disciplinas pd
                    inner join disciplinas d on (pd.iddisciplina = d.iddisciplina)
                WHERE pd.idprofessor='" . $professor["idprofessor"] . "' order by d.nome asc ";
            //echo $sql."<br>";
            $seleciona = mysql_query($sql);
            while ($disciplina = mysql_fetch_array($seleciona)) {
                //echo $disciplina["nome"]."<br>";
                $professores[$ind]["disciplinas"][] = $disciplina;
            }
        }
        return $professores;
    }

    function retornaOpcoesVerificaVotoEnquete($idenquete, $idbloco_disciplina)
    {
        $retorno = array();
        $this->sql = "select
          *
          from
          avas_enquetes_opcoes
          where
          idenquete = " . $idenquete . " and ativo = 'S'";
        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "ordem";
        $opcoes = $this->retornarLinhas();
        $totalVotos = 0;
        foreach ($opcoes as $ind => $opcao) {
            $this->sql = "select
            count(*) as votos
          from
            avas_enquetes_opcoes_votos eov
            inner join avas_enquetes_opcoes eo on (eov.idopcao = eo.idopcao)
          where
            eo.idenquete = " . $idenquete . " and
            eov.idopcao = " . $opcao['idopcao'];
            $votos = $this->retornarLinha($this->sql);
            $totalVotos += $opcoes[$ind]['votos'] = $votos['votos'];
        }
        $retorno['opcoes'] = $opcoes;
        $retorno['total_votos'] = $totalVotos;
        $this->sql = "select
          eov.idopcao
          from
          avas_enquetes_opcoes_votos eov
          inner join avas_enquetes_opcoes eo on (eov.idopcao = eo.idopcao)
          where
          eo.idenquete = " . $idenquete . " and
          eov.idmatricula = " . $this->id . " and
          eov.idbloco_disciplina = " . $idbloco_disciplina;
        $votou = $this->retornarLinha($this->sql);
        $retorno['votou'] = $votou['idopcao'];
        return $retorno;
    }

    function votarEnquete($idbloco_disciplina)
    {
        if (verificaPermissaoAcesso(true)) {
            if (intval($this->post['idopcao'])) {
                $this->sql = 'insert into avas_enquetes_opcoes_votos set data_cad = now(), idbloco_disciplina = ' . $idbloco_disciplina . ', idmatricula = ' . $this->id . ', idopcao = ' . $this->post['idopcao'];
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

    /**
     * Lista de avas do curso Atual (Acessado atualmente)
     *
     * @return array
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public static function listaDeAvas()
    {
        $query = 'SELECT * FROM cursos c
        INNER JOIN matriculas mt ON mt.idmatricula = ' . Request::url(4) . '
            WHERE c.idcurso = mt.idcurso';
        $curso = mysql_fetch_assoc(mysql_query($query));
        return $curso;
    }

    function gerarRetornarExercicio($idexercicio)
    {
        if (verificaPermissaoAcesso(true)) {
            $retorno = array();
            $this->sql = "select * from avas_exercicios where idexercicio = " . $idexercicio . " and ativo = 'S'";
            $exercicio = $this->retornarLinha($this->sql);
            $exercicio['nova'] = true;
            $this->sql = "select * from matriculas_exercicios where idexercicio = " . $idexercicio . " and idmatricula = " . $this->id . " and ativo = 'S' order by nota desc";
            $exercicioMatricula = $this->retornarLinha($this->sql);
            if ($exercicioMatricula['idmatricula_exercicio'] && $exercicioMatricula['nota'] >= $exercicio['nota_minima']) {
                $exercicioMatricula['perguntas'] = $this->retornarExercicio($exercicioMatricula['idmatricula_exercicio']);
                $exercicio = $exercicioMatricula;
            } else {
                $this->sql = "select * from avas_exercicios_disciplinas where idexercicio = " . $idexercicio . " and ativo = 'S'";
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
                $this->sql = 'insert into matriculas_exercicios set inicio = now(), idexercicio = ' . $idexercicio . ', idmatricula = ' . $this->id;
                $this->executaSql($this->sql);
                $exercicio['idmatricula_exercicio'] = mysql_insert_id();
                foreach ($perguntas as $pergunta) {
                    $this->sql = 'insert into matriculas_exercicios_perguntas set idmatricula_exercicio = ' . $exercicio['idmatricula_exercicio'] . ', idpergunta = ' . $pergunta['idpergunta'];
                    $this->executaSql($this->sql);
                }
                $perguntas = $this->retornarExercicio($exercicio['idmatricula_exercicio']);
                $exercicio['perguntas'] = $perguntas;
            }
            return $exercicio;
        }
    }

    function retornarPerguntasExercicio($disciplinas, $tipo, $dificudade, $quantidade)
    {
        $this->sql = "SELECT
            idpergunta, nome
          FROM
            perguntas
          WHERE
              iddisciplina IN (" . $disciplinas . ") AND
              tipo = '" . $tipo . "' AND
              ativo = 'S' AND
              ativo_painel = 'S' AND
              dificuldade = '" . $dificudade . "'
              ORDER BY RAND() LIMIT " . $quantidade;
        $this->limite = -1;
        $this->ordem = false;
        $this->ordem_campo = false;
        $perguntas = $this->retornarLinhas();
        foreach ($perguntas as $ind => $pergunta) {
            $this->sql = "SELECT * FROM
                                perguntas_opcoes
                            WHERE
                                idpergunta = " . $pergunta['idpergunta'] . " AND
                                ativo = 'S' AND
                                ativo_painel = 'S' ";
            $this->limite = -1;
            $this->ordem = "asc";
            $this->ordem_campo = "ordem";
            $opcoes = $this->retornarLinhas();
            $perguntas[$ind]['opcoes'] = $opcoes;
        }
        return $perguntas;
    }

    function retornarExercicio($idmatricula_exercicio)
    {
        $this->sql = "SELECT
                          mep.*,
                          p.nome,
                          p.tipo,
                          p.multipla_escolha,
                          p.imagem_servidor
                    FROM
                        matriculas_exercicios_perguntas mep
                        INNER JOIN perguntas p ON (mep.idpergunta = p.idpergunta)
                    WHERE
                        mep.idmatricula_exercicio = " . $idmatricula_exercicio;
        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "idmatricula_exercicio_pergunta";
        $perguntas = $this->retornarLinhas();
        foreach ($perguntas as $ind => $pergunta) {
            $this->sql = "SELECT
                            po.*,
                            IF(mepom.idmatricula_exercicio_opcao IS NULL, 'N', 'S') as marcada
                        FROM
                            perguntas_opcoes po
                            LEFT OUTER JOIN matriculas_exercicios_perguntas_opcoes_marcadas mepom ON (mepom.idmatricula_exercicio_pergunta = " . $pergunta['idmatricula_exercicio_pergunta'] . " AND po.idopcao = mepom.idopcao)
                        WHERE
                            po.idpergunta = " . $pergunta['idpergunta'] . " AND
                            po.ativo = 'S'";
            $this->limite = -1;
            $this->ordem = "asc";
            $this->ordem_campo = "po.ordem";
            $opcoes = $this->retornarLinhas();
            $perguntas[$ind]['opcoes'] = $opcoes;
        }
        return $perguntas;
    }

    function salvarExercicio()
    {
        if (verificaPermissaoAcesso(true)) {
            $this->sql = "select count(*) as total from matriculas_exercicios_perguntas where idmatricula_exercicio = " . $this->post['idmatricula_exercicio'];
            $totalPerguntas = $this->retornarLinha($this->sql);
            $corretas = 0;

            $perguntasCorrigir = array();
            $perguntasCorretas = array();
            foreach ($this->post['pergunta'] as $idmatricula_exercicio_pergunta => $opcoes) {
                $this->sql = "select
                                p.*
                            from
                                perguntas p
                                inner join matriculas_exercicios_perguntas mep on (p.idpergunta = mep.idpergunta)
                            where
                                mep.idmatricula_exercicio_pergunta = " . $idmatricula_exercicio_pergunta;
                $pergunta = $this->retornarLinha($this->sql);

                $perguntasCorrigir[$pergunta['idpergunta']]['opcoes_certas'] = 1;
                $perguntasCorrigir[$pergunta['idpergunta']]['marcadas_certas'] = 0;
                $perguntasCorrigir[$pergunta['idpergunta']]['marcadas'] = 0;
                if ($pergunta['tipo'] == 'O' && $pergunta['multipla_escolha'] == 'S') {
                    $this->sql = 'select count(*) as total from perguntas_opcoes where idpergunta = ' . $pergunta['idpergunta'] . ' and correta = "S" and ativo = "S"';
                    $totalCorretas = $this->retornarLinha($this->sql);
                    $perguntasCorrigir[$pergunta['idpergunta']]['opcoes_certas'] = $totalCorretas['total'];
                }

                if (is_array($opcoes['opcao'])) {
                    foreach ($opcoes['opcao'] as $opcao) {
                        $this->sql = "select idopcao, idpergunta, correta from perguntas_opcoes where idopcao = " . $opcao;
                        $opcao = $this->retornarLinha($this->sql);

                        $perguntasCorrigir[$opcao['idpergunta']]['marcadas']++;

                        if ($opcao['correta'] == 'S')
                            $perguntasCorrigir[$opcao['idpergunta']]['marcadas_certas']++;

                        if (
                            $perguntasCorrigir[$opcao['idpergunta']]['opcoes_certas'] == $perguntasCorrigir[$opcao['idpergunta']]['marcadas'] &&
                            $perguntasCorrigir[$opcao['idpergunta']]['opcoes_certas'] == $perguntasCorrigir[$opcao['idpergunta']]['marcadas_certas']
                        ) {
                            $perguntasCorretas[$opcao['idpergunta']] = $opcao['idpergunta'];
                        } else {
                            unset($perguntasCorretas[$opcao['idpergunta']]);
                        }

                        $this->sql = 'insert into matriculas_exercicios_perguntas_opcoes_marcadas set idmatricula_exercicio_pergunta = ' . $idmatricula_exercicio_pergunta . ', idopcao = ' . $opcao['idopcao'];
                        $this->executaSql($this->sql);
                    }
                } else {
                    $this->sql = "select idopcao, idpergunta, correta from perguntas_opcoes where idopcao = " . $opcoes['opcao'];
                    $opcao = $this->retornarLinha($this->sql);

                    $perguntasCorrigir[$opcao['idpergunta']]['marcadas']++;

                    if ($opcao['correta'] == 'S')
                        $perguntasCorrigir[$opcao['idpergunta']]['marcadas_certas']++;

                    if (
                        $perguntasCorrigir[$opcao['idpergunta']]['opcoes_certas'] == $perguntasCorrigir[$opcao['idpergunta']]['marcadas'] &&
                        $perguntasCorrigir[$opcao['idpergunta']]['opcoes_certas'] == $perguntasCorrigir[$opcao['idpergunta']]['marcadas_certas']
                    ) {
                        $perguntasCorretas[$opcao['idpergunta']] = $opcao['idpergunta'];
                    } else {
                        unset($perguntasCorretas[$opcao['idpergunta']]);
                    }

                    $this->sql = 'insert into matriculas_exercicios_perguntas_opcoes_marcadas set idmatricula_exercicio_pergunta = ' . $idmatricula_exercicio_pergunta . ', idopcao = ' . $opcao['idopcao'];
                    $this->executaSql($this->sql);
                }
            }
            $corretas = count($perguntasCorretas);

            $erradas = $totalPerguntas['total'] - $corretas;
            $nota = number_format(((10 * $corretas) / $totalPerguntas['total']), 2, '.', '');
            $this->sql = 'update matriculas_exercicios set fim = now(), corretas = ' . $corretas . ', erradas = ' . $erradas . ', nota = ' . $nota . ' where idmatricula_exercicio = ' . $this->post['idmatricula_exercicio'];
            $this->executaSql($this->sql);

            return $retorno = array(
                'sucesso' => true
            );
        }
    }

    function retornarMatriculaExercicio($idmatricula_exercicio)
    {
        $this->sql = "SELECT * FROM
                        matriculas_exercicios
                    WHERE
                        idmatricula_exercicio = " . $idmatricula_exercicio;
        return $this->retornarLinha($this->sql);
    }

    function verificaPreRequisito($idobjeto)
    {
        $this->sql = "select
          ae.*
          from
          avas_exercicios ae
          inner join avas_rotas_aprendizagem_objetos arao on (arao.idexercicio = ae.idexercicio)
          where
          arao.idobjeto = " . $idobjeto . " and arao.ativo = 'S'";
        $exercicio = $this->retornarLinha($this->sql);
        $this->sql = "select * from matriculas_exercicios where idexercicio = " . $exercicio['idexercicio'] . " and idmatricula = " . $this->id . " and ativo = 'S' order by nota desc";
        $exercicioMatricula = $this->retornarLinha($this->sql);
        if ($exercicioMatricula['idmatricula_exercicio'] && $exercicioMatricula['nota'] >= $exercicio['nota_minima']) {
            return true;
        } else {
            return false;
        }
    }

    function contabilizarDownload($idmatricula, $idava, $iddownload)
    {
        if (verificaPermissaoAcesso(false)) {
            $sql = "select count(*) as total from matriculas_rotas_aprendizagem_objetos where idmatricula = " . $idmatricula . " and idava = " . $idava . " and iddownload = " . $iddownload;
            $verifica = $this->retornarLinha($sql);
            if ($verifica["total"] <= 0) {
                $sql = "SELECT porcentagem_biblioteca FROM avas WHERE idava = " . $idava;
                $porcentagem = $this->retornarLinha($sql);
                if (!$porcentagem['porcentagem_biblioteca']) {
                    $porcentagem['porcentagem_biblioteca'] = 0;
                }
                $sql = "INSERT INTO
                    matriculas_rotas_aprendizagem_objetos
                    SET
                    data_cad = now(),
                    idmatricula = " . $idmatricula . ",
                    idava = " . $idava . ",
                    iddownload = " . $iddownload . ",
                    porcentagem = " . $porcentagem['porcentagem_biblioteca'];
                $this->executaSql($sql);
            }
            return true;
        }
    }

    public function retornarExerciciosDisciplina($idmatricula, $iddisciplina, $idava)
    {
        $this->sql = "SELECT * FROM
                    avas_exercicios
                  WHERE
                    idava = " . intval($idava) . " and
                    iddisciplina_nota = " . intval($iddisciplina) . " and
                    ativo = 'S'";
        $this->ordem = "ASC";
        $this->ordem_campo = "ordem";
        $this->limite = -1;
        $exercicios = $this->retornarLinhas();
        foreach ($exercicios as $ind => $exercicio) {
            $sql = "SELECT * FROM
                        matriculas_exercicios
                    WHERE
                        idexercicio = {$exercicio['idexercicio']} AND
                        idmatricula = {$idmatricula} AND
                        ativo = 'S'
                    ORDER BY nota DESC
                    LIMIT 1";
            $nota = $this->retornarLinha($sql);
            $exercicios[$ind]['nota'] = $nota['nota'];
            $exercicios[$ind]['inicio'] = $nota['inicio'];
        }
        return $exercicios;
    }

    public function retornarContribuicao($idmatricula, $idpessoa, $idava = null)
    {
        $contribuicao = array();
        $this->sql = "SELECT
                    count(*) as total
                  FROM
                    avas_tiraduvidas
                  where
                    idmatricula = " . intval($idmatricula) . " and
          ativo = 'S'";

        $tiraduvidasTotal = $this->retornarLinha($this->sql);

        $this->sql = "SELECT
                        count(*) AS total
                      FROM
                        avas_mensagem_instantanea ami
                            INNER JOIN avas_mensagem_instantanea_integrantes amii
                                ON amii.idmensagem_instantanea = ami.idmensagem_instantanea
                            INNER JOIN matriculas m
                                ON m.idpessoa = amii.idpessoa
                       WHERE
                            m.idmatricula = $idmatricula
                       AND  amii.criador = 'S'
                       AND  m.ativo = 'S'";

        if ($idava) {
            $this->sql .= " AND ami.idava = $idava";
        }

        $tiraduvidasTotal2 = $this->retornarLinha($this->sql);

        $contribuicao['tiraduvidas'] = $tiraduvidasTotal['total'] + $tiraduvidasTotal2["total"];

        $this->sql = "SELECT
                    count(*) as total
                  FROM
                    avas_foruns_topicos_mensagens
                  where
                    idmatricula = " . intval($idmatricula) . " and
          ativo = 'S'";
        $forumTotal = $this->retornarLinha($this->sql);
        $contribuicao['forum'] = $forumTotal['total'];

        $this->sql = "SELECT
                    count(*) as total
                  FROM
                    matriculas_rotas_aprendizagem_objetos
                  where
                    idmatricula = " . intval($idmatricula) . " and
          iddownload is not null";
        $bibliotecaTotal = $this->retornarLinha($this->sql);
        $contribuicao['biblioteca'] = $bibliotecaTotal['total'];

        $this->sql = "SELECT
                    count(*) as total
                  FROM
                    matriculas_rotas_aprendizagem_objetos
                  where
                    idmatricula = " . intval($idmatricula) . " and
                    idsimulado IS NOT NULL";
        $simuladoTotal = $this->retornarLinha($this->sql);
        $contribuicao['simulado'] = $simuladoTotal['total'];

        $this->sql = "SELECT
          oca.idava
          FROM
          matriculas m
          INNER JOIN ofertas_cursos_escolas ocp on (ocp.idoferta = m.idoferta and ocp.idcurso = m.idcurso and ocp.idescola = m.idescola)
          INNER JOIN ofertas_curriculos_avas oca on (ocp.idoferta = oca.idoferta and ocp.idcurriculo = oca.idcurriculo and oca.ativo = 'S')
          where
          m.idmatricula = " . $idmatricula . " group by idava";
        $this->ordem = false;
        $this->ordem_campo = false;
        $this->limite = -1;
        $avas = $this->retornarLinhas();
        $total = 0;
        foreach ($avas as $ava) {
            $this->sql = "SELECT
            *
          FROM
            avas_chats
          where
            idava = " . intval($ava['idava']) . " and
            ativo = 'S'";
            $this->ordem = "asc";
            $this->ordem_campo = "idchat";
            $this->limite = -1;
            $chats = $this->retornarLinhas();
            foreach ($chats as $chat) {
                $sql = "SELECT
          count(*) as total
        FROM
          chats_mensagens
        where
          idchat = " . $chat['idchat'] . " and
          idpessoa = " . $idpessoa . " and
          usuario_tipo = 0";
                $chatTotal = $this->retornarLinha($sql);
                $total += $chatTotal['total'];
            }
        }
        $contribuicao['chat'] = $total;

        return $contribuicao;
    }

    public function retornarPorcentagem($idmatricula)
    {
        $porcentagem = array();

        $this->sql = "SELECT
          oca.idava
          FROM
          matriculas m
          INNER JOIN ofertas_cursos_escolas ocp on (ocp.idoferta = m.idoferta and ocp.idcurso = m.idcurso and ocp.idescola = m.idescola)
          INNER JOIN ofertas_curriculos_avas oca on (ocp.idoferta = oca.idoferta and ocp.idcurriculo = oca.idcurriculo and oca.ativo = 'S')
          INNER JOIN disciplinas d on (d.iddisciplina = oca.iddisciplina AND d.ativo = 'S')
          where
          m.idmatricula = " . $idmatricula . " group by idava";

        $this->ordem = false;
        $this->ordem_campo = false;
        $this->limite = -1;
        $avas = $this->retornarLinhas();
        $avasArray = array();

        foreach ($avas as $ava) {
            if ($ava['idava'])
                $avasArray[] = $ava['idava'];
        }

        $totalAvas = count($avasArray);

        if ($totalAvas > 0) {
            $avasArray = implode(',', $avasArray);

            $this->sql = "select
                            IFNULL(sum(porcentagem_rota),0) as total_rota,
                            IFNULL(sum(porcentagem_chat),0) as total_chat,
                            IFNULL(sum(porcentagem_forum),0) as total_forum,
                            IFNULL(sum(porcentagem_tira_duvida),0) as total_tira_duvida,
                            IFNULL(sum(porcentagem_biblioteca),0) as total_biblioteca,
                            IFNULL(sum(porcentagem_simulado),0) as total_simulado
                          from
                            avas
                          where
                            idava in (" . $avasArray . ")";
            $porcentagens = $this->retornarLinha($this->sql);

            $this->sql = "select
                    sum(porcentagem) as total
                  from
                    matriculas_rotas_aprendizagem_objetos
                  where
                    idava in (" . $avasArray . ") and
                    idmatricula = " . (int)$this->id . " and
                    idobjeto is not null";
            $porcentagensAluno = $this->retornarLinha($this->sql);
            $porcentagem['conteudo'] = $porcentagensAluno['total'];
            if ($porcentagens['total_rota'] < $porcentagensAluno['total']) {
                $porcentagem['conteudo'] = $porcentagens['total_rota'];
            }

            $this->sql = "select
                    count(*) as total
                  from
                    matriculas_rotas_aprendizagem_objetos
                  where
                    idava in (" . $avasArray . ") and
                    idmatricula = " . (int)$this->id . " and
                    idchat is not null";
            $porcentagensAluno = $this->retornarLinha($this->sql);

            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['chat'] = $porcentagens['total_chat'];
            } else {
                $porcentagem['chat'] = 0;
            }

            $this->sql = "select
                    count(*) as total
                  from
                    matriculas_rotas_aprendizagem_objetos
                  where
                    idava in (" . $avasArray . ") and
                    idmatricula = " . (int)$this->id . " and
                    idforum is not null";
            $porcentagensAluno = $this->retornarLinha($this->sql);

            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['forum'] = $porcentagens['total_forum'];
            } else {
                $porcentagem['forum'] = 0;
            }

            $this->sql = "select
                    count(*) as total
                  from
                    matriculas_rotas_aprendizagem_objetos
                  where
                    idava in (" . $avasArray . ") and
                    idmatricula = " . (int)$this->id . " and
                    iddownload is not null";
            $porcentagensAluno = $this->retornarLinha($this->sql);

            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['biblioteca'] = $porcentagens['total_biblioteca'];
            } else {
                $porcentagem['biblioteca'] = 0;
            }

            $this->sql = "SELECT
                    COUNT(*) AS total
                  FROM
                    matriculas_rotas_aprendizagem_objetos
                  WHERE
                    idava IN (" . $avasArray . ") AND
                    idmatricula = " . (int)$this->id . " AND
                    (
                        idtiraduvida IS NOT NULL OR
                        idmensagem_instantanea IS NOT NULL
                    )";

            $porcentagensAluno = $this->retornarLinha($this->sql);

            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['tiraduvida'] = $porcentagens['total_tira_duvida'];
            } else {
                $porcentagem['tiraduvida'] = 0;
            }

            $this->sql = "SELECT
                    COUNT(*) AS total
                  FROM
                    matriculas_rotas_aprendizagem_objetos
                  WHERE
                    idava IN (" . $avasArray . ") AND
                    idmatricula = " . (int)$this->id . " AND
                    idsimulado IS NOT NULL";

            $porcentagensAluno = $this->retornarLinha($this->sql);

            if ($porcentagensAluno['total'] > 0) {
                $porcentagem['simulado'] = $porcentagens['total_simulado'];
            } else {
                $porcentagem['simulado'] = 0;
            }
        }

        return $porcentagem;
    }

    /**
     * Verify if a matriculation has a diploma or stay in a list of
     * `Folha de Registro`
     *
     * @param integer $idmatriculation matriculation number for consulting
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

    /**
     * Get Student name By IdMatricula
     *
     * @param integer $idMatriculation
     *
     * @return null|string
     */
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

    /**
     * Modify the visibiity of a message on `matriculas_mensagens`
     *
     * @param $idmessage
     * @param $toSituation
     *
     * @return boolean
     */
    public function modifyMessageVisibilityTo($idmessage, $toSituation)
    {
        $query = "UPDATE `matriculas_mensagens`
                    SET exibir_diploma = '{$toSituation}'
                  WHERE idmensagem = '{$idmessage}'";
        $resource = $this->executaSql($query);
        if ($resource) {
            $this->adicionarHistorico($this->idusuario, "mensagem", "modificou", "", $toSituation, $idmessage);
            return $resource;
        }
        return false;
    }

    /**
     * Move a file uploaded to a created folder and register it on `database`
     *
     * @return array
     */
    public function adicionarArquivo()
    {
        $this->return = array();
        if ($_FILES['documento']['error'] === 0) {
            $data_matricula = $this->retornarDataCadMatricula();
            $data_matricula = new DateTime($data_matricula);
            $pasta = $_SERVER['DOCUMENT_ROOT'] . '/storage/matriculas_arquivos/' . $data_matricula->format('Y') . '/' . $data_matricula->format('m') . '/' . $this->id;
            $extensao = strtolower(strrchr($_FILES['documento']['name'], '.'));
            $nomeServidor = date('YmdHis') . '_' . uniqid() . $extensao;
            mkdir($pasta, 0777, true);
            chmod($pasta, 0777);
            $envio = move_uploaded_file($_FILES['documento']['tmp_name'], $pasta . '/' . $nomeServidor);
            chmod($pasta . '/' . $nomeServidor, 0777);
            if (!$envio) {
                $this->return["sucesso"] = false;
                $this->return["mensagem"] = "arquivos_matricula_envio_erro";
                return $this->return;
            }

            $this->sql = "INSERT INTO
                        matriculas_arquivos
                    SET
                        data_cad = now(),
                        idmatricula = " . $this->id . ",
                        arquivo_nome = '" . $_FILES["documento"]["name"] . "',
                        arquivo_servidor = '" . $nomeServidor . "',
                        arquivo_tipo = '" . $_FILES["documento"]["type"] . "',
                        arquivo_tamanho = '" . $_FILES["documento"]["size"] . "',
                        protocolo = '" . $_POST["protocolo"] . "',
                        nome_arquivo = '" . $_POST["nome_arquivo"] . "',
                        arquivo_pasta = '" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "' ";

            $salvar = $this->executaSql((string)$this->sql);
            if (!$salvar) {
                $this->return["sucesso"] = false;
                $this->return["mensagem"] = "arquivos_matricula_envio_erro";
                return $this->return;
            }

            $this->AdicionarHistorico($this->idusuario, "arquivo", "cadastrou", NULL, NULL, mysql_insert_id());
            $this->return["sucesso"] = true;
            $this->return["mensagem"] = "arquivos_matricula_envio_sucesso";
            return $this->return;
        } else if (!$_FILES['documento']['tmp_name']) {
            $data_matricula = $this->retornarDataCadMatricula();
            $data_matricula = new DateTime($data_matricula);
            $this->sql = "INSERT INTO
                        matriculas_arquivos
                    SET
                        data_cad = now(),
                        idmatricula = " . $this->id . ",
                        protocolo = '" . $_POST["protocolo"] . "',
                        nome_arquivo = '" . $_POST["nome_arquivo"] . "',
                        arquivo_pasta = '" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "' ";
            $salvar = $this->executaSql((string)$this->sql);
            if (!$salvar) {
                $this->return["sucesso"] = false;
                $this->return["mensagem"] = "arquivos_matricula_envio_erro";
                return $this->return;
            }

            $this->AdicionarHistorico($this->idusuario, "arquivo", "cadastrou", NULL, NULL, mysql_insert_id());
            $this->return["sucesso"] = true;
            $this->return["mensagem"] = "arquivos_matricula_envio_sucesso";
            return $this->return;
        }

        $this->sql = "INSERT INTO
                        matriculas_arquivos
                    SET
                        data_cad = now(),
                        idmatricula = " . $this->id . ",
                        idtipo = " . $this->post["idtipo"] . ",
                        idtipo_associacao = " . $this->post["idtipo_associacao"];
        $salvar = $this->executaSql($this->sql);
        if (!$salvar) {
            $this->return["sucesso"] = false;
            $this->return["mensagem"] = "arquivos_matricula_envio_erro";
            return $this->return;
        }
        $this->AdicionarHistorico($this->idusuario, "arquivo", "cadastrou", NULL, NULL, mysql_insert_id());
        $this->return["sucesso"] = true;
        $this->return["mensagem"] = "arquivos_matricula_envio_sucesso";
        return $this->return;
    }

    public function enviarArquivo($idarquivo)
    {
        $this->retorno = array();
        if ($_FILES["arquivo"]['error'] != 4) {
            $validarTamanho = $this->ValidarArquivo($_FILES["arquivo"]);
            if ($validarTamanho) {
                $this->retorno["erro"] = true;
                $this->retorno["mensagem"] = $validarTamanho;
                return $this->retorno;
            } else {
                $data_matricula = $this->retornarDataCadMatricula();
                $data_matricula = new DateTime($data_matricula);
                $pasta = $_SERVER["DOCUMENT_ROOT"] . "/storage/matriculas_arquivos/" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "/" . $this->id;
                $extensao = strtolower(strrchr($_FILES["arquivo"]["name"], "."));
                $nomeServidor = date("YmdHis") . "_" . uniqid() . $extensao;
                if (!is_dir($pasta)) {
                    @mkdir($pasta, 0777, true);
                }
                @chmod($pasta, 0777);
                $envio = move_uploaded_file($_FILES["arquivo"]["tmp_name"], $pasta . "/" . $nomeServidor);
                chmod($pasta . "/" . $nomeServidor, 0777);

                if ($envio) {
                    $this->sql = "update
                    matriculas_arquivos
                  set
                    arquivo_nome = '" . $_FILES["arquivo"]["name"] . "',
                    arquivo_servidor = '" . $nomeServidor . "',
                    arquivo_tipo = '" . $_FILES["arquivo"]["type"] . "',
                    arquivo_tamanho = '" . $_FILES["arquivo"]["size"] . "',
                    arquivo_pasta = '" . $data_matricula->format('Y') . "/" . $data_matricula->format('m') . "'
                  where
                    idarquivo = " . $idarquivo . " and
                    idmatricula = " . $this->id;

                    $salvar = $this->executaSql($this->sql);
                    if ($salvar) {
                        $this->AdicionarHistorico($this->idusuario, "arquivo", "enviou", NULL, NULL, $idarquivo);
                        $this->retorno["sucesso"] = true;
                        $this->retorno["mensagem"] = "arquivos_matricula_envio_sucesso";
                    } else {
                        $this->retorno["sucesso"] = false;
                        $this->retorno["mensagem"] = "arquivos_matricula_envio_erro";
                    }
                } else {
                    $this->retorno["sucesso"] = false;
                    $this->retorno["mensagem"] = "arquivos_matricula_envio_erro";
                }
            }
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "arquivos_matricula_envio_erro";
        }
        return $this->retorno;
    }

    public function retornarListaArquivos()
    {
        $this->sql = "SELECT *,idarquivo as iddocumento FROM matriculas_arquivos md
                    WHERE idmatricula = {$this->id}
                                AND ativo = 'S'";
        $this->ordem = 'ASC';
        $this->ordem_campo = "data_cad";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function updateMatricula($idmatricula, array $dados)
    {
        if (!is_numeric($idmatricula)) {
            throw new InvalidArgumentException('First parameter need to be a number');
        }
        $db = new Zend_Db_Select(new Zend_Db_MySql());
        $updateStmt = $db->update('matriculas', $dados, 'idmatricula = ' . $idmatricula);
        return $this->executaSql($updateStmt);
    }

    function BuscarMatriculasTransferencia()
    {
        $this->sql = "select
            m.idmatricula as 'key',
            CONCAT(m.idmatricula, ' - ', p.nome) as value
            from
            pessoas p
            inner join matriculas m on p.idpessoa = m.idpessoa
            inner join matriculas_workflow mw on m.idsituacao = mw.idsituacao and mw.inicio = 'S'
            where
            (p.nome LIKE '%" . $this->get["tag"] . "%' OR m.idmatricula LIKE '%" . $this->get["tag"] . "%') and
            m.ativo = 'S' ";
        $this->limite = -1;
        $this->ordem_campo = "p.nome";
        $this->groupby = "p.idpessoa";
        $this->retorno = $this->retornarLinhas();
        return json_encode($this->retorno);
    }

    public function transferirParcelasMatricula()
    {
        if (!$this->post['parcelas_transferidas']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "financeiro_parcelas_vazio";
            return $this->retorno;
        }
        if (!$this->post['matricula'][0]) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "financeiro_matricula_vazio";
            return $this->retorno;
        }

        $sql = "select * FROM contas_workflow where ativo = 'S' and emaberto = 'S' order by idsituacao desc limit 1";
        $situacaoEmAberto = $this->retornarLinha($sql);
        if (!$situacaoEmAberto['idsituacao']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_financeiro_erro_sem_situacao_inicial";
            return $this->retorno;
        }
        $sql = "select * FROM contas_workflow where ativo = 'S' and transferida = 'S' order by idsituacao desc limit 1";
        $situacaoTransferida = $this->retornarLinha($sql);
        if (!$situacaoTransferida['idsituacao']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_financeiro_erro_sem_situacao_transferida";
            return $this->retorno;
        }
        $sql_matricula = "select m.*
              FROM matriculas m
              inner join matriculas_workflow mw on m.idsituacao = mw.idsituacao and mw.inicio = 'S'
              where idmatricula = " . $this->post['matricula'][0] . " ";
        $matricula = $this->retornarLinha($sql_matricula);
        if (!$matricula['idmatricula']) {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "financeiro_matricula_vazio";
            return $this->retorno;
        }
        mysql_query("START TRANSACTION");
        $sql = "insert into contas_relacoes set data_cad = now()";
        $this->executaSql($sql);
        $idRelacao = mysql_insert_id();
        $numero_parcela = 0;
        $idContasTransferidas = count($this->post['parcelas_transferidas']);
        //MODIFICAR AS CONTAS ANTIGAS PARA TRANSFERIDO
        foreach ($this->post['parcelas_transferidas'] as $parcela_transferida => $linha) {
            if (!$parcela_transferida) {
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "mensagem_financeiro_erro_conta";
                return $this->retorno;
            }
            $sql_antiga = "select idsituacao FROM contas where idconta = " . $parcela_transferida . " ";
            $antiga = $this->retornarLinha($sql_antiga);
            $this->sql = "INSERT INTO contas
              (data_cad, idmantenedora, idsindicato, idsituacao, idcategoria,
               idsubcategoria, idfornecedor, idpessoa, idmatricula,
               idproduto, idescola, idconta_corrente, idrelacao, ativo,
               ativo_painel, parcela, total_parcelas, nome, tipo, forma_pagamento,
               valor, valor_juros, valor_outro, valor_multa, valor_desconto, data_vencimento,
               tipo_documento, documento, idevento, idbandeira, autorizacao_cartao, idbanco,
               agencia_cheque, cc_cheque, numero_cheque, emitente_cheque, idcheque, valor_liquido,
               data1_cheque_alinea, id1_cheque_alinea, data2_cheque_alinea, id2_cheque_alinea, data3_cheque_alinea, id3_cheque_alinea,
               idcentro_custo, idmotivo, transferida)
              SELECT
              NOW(), " . $matricula['idmantenedora'] . ", " . $matricula['idsindicato'] . ", " . $situacaoEmAberto['idsituacao'] . ", idcategoria,
              idsubcategoria, idfornecedor, idpessoa, " . $matricula['idmatricula'] . ",
              idproduto, idescola, idconta_corrente, " . $idRelacao . ", 'S',
              ativo_painel, " . ++$numero_parcela . ", " . $idContasTransferidas . ", nome, tipo, forma_pagamento,
              valor, valor_juros, valor_outro, valor_multa, valor_desconto, data_vencimento,
              tipo_documento, documento, idevento, idbandeira, autorizacao_cartao, idbanco,
              agencia_cheque, cc_cheque, numero_cheque, emitente_cheque, idcheque, valor_liquido,
              data1_cheque_alinea, id1_cheque_alinea, data2_cheque_alinea, id2_cheque_alinea, data3_cheque_alinea, id3_cheque_alinea,
              idcentro_custo, idmotivo, 'S'
              FROM contas WHERE idconta = '" . $parcela_transferida . "' ";
            $this->executaSql($this->sql);
            $idconta_nova = mysql_insert_id();
            $this->AdicionarHistorico($this->idusuario, "parcela", "cadastrou", NULL, NULL, $idconta_nova);
            $this->monitora_onde = 52;
            $this->monitora_oque = 1;
            $this->monitora_qual = $idconta_nova;
            $this->Monitora();
            $sql_atualiza = "update contas set idsituacao = " . $situacaoTransferida['idsituacao'] . ", idconta_transferida = " . $idconta_nova . " where idconta = " . $parcela_transferida;
            $atualiza = $this->executaSql($sql_atualiza);
            if (!$atualiza) {
                mysql_query("ROLLBACK");
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "mensagem_financeiro_erro_atualizar_conta";
                return $this->retorno;
            }
            $this->AdicionarHistorico($this->idusuario, "parcela", "transferiu", null, null, $parcela_transferida);
            $sql = "insert into contas_historicos set data_cad = NOW(),
                           idconta = " . $parcela_transferida . ",
                           idusuario = " . $this->idusuario . ",
                           tipo = 'situacao',
                           acao = 'modificou',
                           de = " . $antiga['idsituacao'] . ",
                           para = " . $situacaoTransferida['idsituacao'] . "  ";
            $insere_historico = $this->executaSql($sql);
            if (!$insere_historico) {
                mysql_query("ROLLBACK");
                $this->retorno["sucesso"] = false;
                $this->retorno["mensagem"] = "mensagem_financeiro_erro_atualizar_conta_historico";
                return $this->retorno;
            }
        }
        //MODIFICAR AS CONTAS ANTIGAS PARA TRANSFERIDO - FIM
        $this->executaSql("commit");
        $this->retorno["sucesso"] = true;
        $this->retorno["mensagem"] = "mensagem_financeiro_cadastrado_sucesso";
        return $this->retorno;
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

    function alterarNegativacao($idmatricula, $post)
    {
        $sql = 'select * from matriculas where idmatricula = ' . $idmatricula;
        $resultado = $this->executaSql($sql);
        $linha_antiga = mysql_fetch_assoc($resultado);
        if ($post['acao_negativar']) {
            $acao = 'negativou';
            $sql = 'update matriculas set negativada = "S" ';
            $post_data_negativacao = formataData($post['data_negativacao'], 'en', 0);
            if ($post['data_negativacao'] && $linha_antiga['data_negativacao'] != $post_data_negativacao) {
                $data_negativacao = true;
                $sql .= ', data_negativacao = "' . $post_data_negativacao . '"';
            } else {
                $sql .= ', data_negativacao = now() ';
            }
        } else {
            $acao = 'desnegativou';
            $sql = 'update matriculas set negativada = "N", data_negativacao = NULL ';
        }
        $sql .= ' where idmatricula = ' . $idmatricula;
        $resultado = $this->executaSql($sql);
        if (!$resultado) {
            $retorno["sucesso"] = false;
            $retorno["mensagem"] = "mensagem_financeiro_erro_conta";
            return $retorno;
        } else {
            if ($linha_antiga['data_negativacao'] != $post_data_negativacao) {
                $this->AdicionarHistorico($this->idusuario, 'data_negativacao', 'modificou', $linha_antiga['data_negativacao'], $post_data_negativacao, null);
            }
            $this->AdicionarHistorico($this->idusuario, 'matricula', $acao, null, null, null);
        }
        $retorno["sucesso"] = true;
        return $retorno;
    }

    function verificaMatriculaAprovadaNotas($porcentagemFolhaRegistro = null)
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

    public function verificaMatriculaAprovadaNotasDias($idmatricula, $idoferta, $idcurso)
    {
        if (!$idmatricula || !$idoferta || !$idcurso) {
            return false;
        }

        $sql = "SELECT mh.data_cad as data_inicio_estudo FROM matriculas_historicos mh LEFT JOIN matriculas_workflow mw ON (mh.para = mw.idsituacao) WHERE idmatricula = '" . $idmatricula . "' AND mw.ativa = 'S'";

        $matricula = $this->retornarLinha($sql);

        $sql = "SELECT gerar_quantidade_dias FROM ofertas_cursos WHERE idoferta = " . $idoferta . " AND idcurso = " . $idcurso;

        $quantidade_dias = $this->retornarLinha($sql);

        if (!empty($matricula['data_inicio_estudo'])) {
            $data_conclusao = new DateTime($matricula['data_inicio_estudo']);
            $data_atual = new DateTime();
            $data_conclusao_dias = $data_conclusao->modify('+ ' . $quantidade_dias['gerar_quantidade_dias'] . ' day');

            if ($data_atual > $data_conclusao_dias) {
                return true;
            }
        }

        return false;
    }

    function possuiDocumentosPendentes($idmatricula)
    {
        $sql = 'select m.*
                from matriculas m
                where idmatricula = ' . $idmatricula;
        $matricula = $this->retornarLinha($sql);
        $possui = false;
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
                        td.ativo = 'S' and
                        (td.idtipo in(SELECT idtipo FROM tipos_documentos_sindicatos where idtipo = td.idtipo and idsindicato = " . $matriculaSindicatoCurso["idsindicato"] . " and ativo = 'S') or
                        td.idtipo in(SELECT idtipo FROM tipos_documentos_cursos where idtipo = td.idtipo and idcurso = " . $matricula["idcurso"] . " and ativo = 'S') or
                        td.todas_sindicatos_obrigatorio = 'S' or
                        td.todos_cursos_obrigatorio = 'S')
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
                $possui = true;
                $retorno["mensagem"] = "ter_documento_obrigatorios";
            }
        }
        return $possui;
    }

    function eviarEmailBoasVindas($idmatricula, $escola, $sindicato)
    {
        $this->sql = "select * from matriculas where idmatricula = " . $idmatricula;
        $matricula = $this->retornarLinha($this->sql);

        if ($matricula['idmatricula']) {
            $this->sql = "select * from pessoas where idpessoa = " . $matricula['idpessoa'];
            $pessoa = $this->retornarLinha($this->sql);

            $this->sql = "select * from ofertas where idoferta = " . $matricula['idoferta'];
            $oferta = $this->retornarLinha($this->sql);

            $this->sql = "select
                            c.*,
                            ci.email_boas_vindas_sindicato,
                            ci.sms_boas_vindas_sindicato
                        from
                            cursos c
                            left join cursos_sindicatos ci on c.idcurso = ci.idcurso and ci.ativo = 'S' and ci.idsindicato = '" . $matricula['idsindicato'] . "'
                        where
                            c.idcurso = " . $matricula['idcurso'];
            $curso = $this->retornarLinha($this->sql);

            if ($curso['email_boas_vindas_sindicato'])
                $emailBoasVindas = $curso['email_boas_vindas_sindicato'];
            else
                $emailBoasVindas = $curso['email_boas_vindas'];

            if ($emailBoasVindas) {
                $emailBoasVindas = str_ireplace("[[MATRICULA]]", $matricula['idmatricula'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[NUMERO_CONTRATO]]", $matricula['numero_contrato'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[VALOR_CONTRATO]]", number_format($matricula['valor_contrato'], 2, ',', '.'), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[FORMA_PAGAMENTO]]", ($GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula['forma_pagamento']]), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[QUANTIDADE_PARCELAS]]", $matricula['quantidade_parcelas'], $emailBoasVindas);

                $emailBoasVindas = str_ireplace("[[NOME_ALUNO]]", (htmlentities($pessoa['nome'])), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[ID_ALUNO]]", $pessoa['idpessoa'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[CPF_CNPJ_ALUNO]]", $pessoa['documento'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[EMAIL_ALUNO]]", $pessoa['email'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[CURSO]]", ($curso['nome']), $emailBoasVindas);

                $emailBoasVindas = str_ireplace("[[OFERTA]]", ($oferta['nome']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[POLO]]", ($escola['nome']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[INSTITUICAO]]", ($sindicato['nome']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[LINK_AMBIENTE_ALUNO]]", $linkAmbienteAluno['nome'], $emailBoasVindas);

                $emailBoasVindas = utf8_decode($emailBoasVindas);

                $nomeDe = utf8_decode($GLOBALS['config']['tituloEmpresa'] . ' - ' . $GLOBALS['config']['tituloSistema']);
                if ($curso['email']) {
                    $emailDe = $curso['email'];
                } else {
                    $emailDe = $GLOBALS['config']['emailSistema'];
                }
                $assunto = 'BEM-VINDO AO CURSO';
                $nomePara = utf8_decode($pessoa['nome']);
                $emailPara = $pessoa['email'];

                $this->enviarEmail($nomeDe, $emailDe, $assunto, $emailBoasVindas, $nomePara, $emailPara);
            }

            if ($curso['sms_boas_vindas_sindicato'])
                $smsBoasVindas = $curso['sms_boas_vindas_sindicato'];
            else
                $smsBoasVindas = $curso['sms_boas_vindas'];

            if ($smsBoasVindas && $pessoa['celular'] && $GLOBALS['config']['integrado_com_sms']) {
                $smsBoasVindas = str_ireplace("[[MATRICULA]]", $matricula['idmatricula'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[NUMERO_CONTRATO]]", $matricula['numero_contrato'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[VALOR_CONTRATO]]", number_format($matricula['valor_contrato'], 2, ',', '.'), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[FORMA_PAGAMENTO]]", ($GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula['forma_pagamento']]), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[QUANTIDADE_PARCELAS]]", $matricula['quantidade_parcelas'], $smsBoasVindas);

                $smsBoasVindas = str_ireplace("[[NOME_ALUNO]]", ($pessoa['nome']), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[ID_ALUNO]]", $pessoa['idpessoa'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[CPF_CNPJ_ALUNO]]", $pessoa['documento'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[EMAIL_ALUNO]]", $pessoa['email'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[CURSO]]", ($curso['nome']), $smsBoasVindas);

                $smsBoasVindas = str_ireplace("[[OFERTA]]", ($oferta['nome']), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[POLO]]", ($escola['nome']), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[INSTITUICAO]]", ($sindicato['nome']), $smsBoasVindas);

                $smsBoasVindas = html_entity_decode($smsBoasVindas);

                if ($smsBoasVindas) {
                    $this->enviarSms($matricula['idmatricula'], 'M', $pessoa['nome'], $pessoa['celular'], $smsBoasVindas);
                }
            }
        }
    }

    function transferirMatriculaTurma($matricula, $idoferta_curso_escola, $idturma, $remover_dados_tabelas)
    {
        //AL
        $this->id = $matricula['idmatricula'];

        $sql = 'SELECT  o.nome AS oferta,
                        o.idoferta, c.nome AS curso,
                        c.idcurso,
                        p.nome_fantasia,
                        p.idescola,
                        p.idsindicato,
                        oci.limite
                FROM ofertas_cursos_escolas ocp
                    INNER JOIN ofertas o ON ocp.idoferta = o.idoferta AND o.ativo = "S"
                    INNER JOIN cursos c ON ocp.idcurso = c.idcurso AND c.ativo = "S"
                    INNER JOIN escolas p ON ocp.idescola = p.idescola AND p.ativo = "S"
                    LEFT JOIN ofertas_cursos_sindicatos oci ON oci.idsindicato = p.idsindicato AND oci.idcurso = c.idcurso AND oci.idoferta = o.idoferta AND oci.ativo = "S"
                WHERE ocp.ativo = "S"
                        AND ocp.ignorar = "N"
                        AND ocp.idcurso = ' . $matricula['idcurso'] . '
                        AND ocp.idoferta_curso_escola = ' . $idoferta_curso_escola;

        $oferta_curso_escola = $this->retornarLinha($sql);
        if (!$oferta_curso_escola) {
            $retorno['mensagem'] = 'erro_transferir_turma';
            return $retorno;
        }

        if (
            $matricula['idoferta'] == $oferta_curso_escola['idoferta']
            && $matricula['idescola'] == $oferta_curso_escola['idescola']
            && $matricula['idturma'] == $idturma
        ) {
            $retorno['mensagem'] = 'erro_transferir_turma_sem_alteracao';
            return $retorno;
        }

        $ofertaObj = new Ofertas();
        $total_alunos = $ofertaObj->retornarTotalMatriculasPorCursoEscola(
            $oferta_curso_escola["idoferta"],
            $oferta_curso_escola["idcurso"],
            $oferta_curso_escola["idescola"],
            $idturma
        );

        if ($oferta_curso_escola["limite"] === '0' || ($oferta_curso_escola["limite"] != "" && $total_alunos >= $oferta_curso_escola["limite"])) {
            $retorno['mensagem'] = 'erro_transferir_turma_completa';
            return $retorno;
        }

        $this->executaSql('START TRANSACTION');

        $sql = 'update matriculas set ';
        if ($this->post['remover_dados'] == 'sim') {
            $separador = ', ';
            $sql .= 'porcentagem = "0"';
        }

        if ($matricula['idoferta'] != $oferta_curso_escola['idoferta']) {
            $sql .= $separador . 'idoferta = ' . $oferta_curso_escola['idoferta'];
            $this->AdicionarHistorico($this->idusuario, 'oferta', 'modificou', $matricula['idoferta'], $oferta_curso_escola['idoferta'], null);
            $separador = ', ';
        }

        if ($matricula['idescola'] != $oferta_curso_escola['idescola']) {
            $sql .= $separador . 'idescola = ' . $oferta_curso_escola['idescola'];
            $this->AdicionarHistorico($this->idusuario, 'escola', 'modificou', $matricula['idescola'], $oferta_curso_escola['idescola'], null);
            $separador = ', ';
            if ($this->comparaEscola($matricula['idescola'], $oferta_curso_escola['idescola']))
                $sql .= $separador . 'detran_situacao = ' . '"AL"';
            if ($matricula['idsindicato'] != $oferta_curso_escola['idsindicato']) {
                $sql .= $separador . 'idsindicato = ' . $oferta_curso_escola['idsindicato'];
                $this->AdicionarHistorico($this->idusuario, 'sindicato', 'modificou', $matricula['idsindicato'], $oferta_curso_escola['idsindicato'], null);
            }
        }

        if ($matricula['idturma'] != $idturma) {
            $sql .= $separador . 'idturma = ' . $idturma;
            $this->AdicionarHistorico($this->idusuario, 'turma', 'modificou', $matricula['idturma'], $idturma, null);
        }

        $sql .= ' where idmatricula = ' . $matricula['idmatricula'];

        $salvar = $this->executaSql($sql);

        if (!$salvar) {
            $this->executaSql('ROLLBACK');
            $retorno['mensagem'] = 'erro_transferir_turma';
            return $retorno;
        }

        $sql_prova_presencial = 'update provas_solicitadas set situacao = "C" where idmatricula = ' . $matricula['idmatricula'];
        if (!$this->executaSql($sql_prova_presencial)) {
            $retorno['mensagem'] = 'erro_transferir_turma';
            return $retorno;
        }
        if ($this->post['remover_dados'] == 'sim') {

            $resultado_provas = $this->removerPerguntasOpcoesProvasMatricula($matricula['idmatricula']);

            if (!$resultado_provas['sucesso']) {
                $this->executaSql('ROLLBACK');
                $retorno['mensagem'] = 'erro_remover_perguntas_opcoes_provas_aluno';
                return $retorno;
            }

            $resultado = $this->removerDadosAlunoTransferido($matricula['idmatricula'], $remover_dados_tabelas);

            if (!$resultado['sucesso']) {
                $this->executaSql('ROLLBACK');
                $retorno['mensagem'] = 'erro_transferir_turma_remover_dados_aluno';
                return $retorno;
            }
        }

        $this->executaSql('COMMIT');
        $retorno['sucesso'] = true;

        return $retorno;
    }

    private function removerDadosAlunoTransferido($idmatricula, $remover_dados_tabelas)
    {

        if (!$idmatricula) {
            $retorno['mensagem'] = 'erro_remover_dados_aluno';
            return $retorno;
        }

        foreach ($remover_dados_tabelas as $tabela) {
            $sql = 'delete from ' . $tabela . ' where idmatricula = ' . $idmatricula;
            if (!$this->executaSql($sql)) {
                $retorno['mensagem'] = 'erro_remover_dados_aluno';
                return $retorno;
            }
        }

        $retorno['sucesso'] = true;
        return $retorno;
    }

    private function removerPerguntasOpcoesProvasMatricula($idmatricula)
    {

        $sqlOpcoesMarcadas = 'DELETE FROM
                        matriculas_avaliacoes_perguntas_opcoes_marcadas
                WHERE EXISTS (
                    SELECT id_prova_pergunta
                    FROM matriculas_avaliacoes_perguntas avp
                    INNER JOIN matriculas_avaliacoes mav ON ( mav.idprova = avp.idprova )
                    WHERE avp.id_prova_pergunta = matriculas_avaliacoes_perguntas_opcoes_marcadas.id_prova_pergunta
                    AND mav.idmatricula =' . $idmatricula . ')';

        if (!$this->executaSql($sqlOpcoesMarcadas)) {
            $retorno['mensagem'] = 'erro_remover_opcoes_avaliacoes';
            return $retorno;
        }

        $sqlPerguntas = 'DELETE FROM
                                matriculas_avaliacoes_perguntas
                        WHERE EXISTS (
                            SELECT idprova
                            FROM matriculas_avaliacoes mav
                            WHERE
                                matriculas_avaliacoes_perguntas.idprova = mav.idprova AND
                                mav.idmatricula = ' . $idmatricula . ')';

        if (!$this->executaSql($sqlPerguntas)) {
            $retorno['mensagem'] = 'erro_remover_perguntas_avaliacoes';
            return $retorno;
        }

        $retorno['sucesso'] = true;
        return $retorno;
    }

    function gerarExercicio($idexercicio)
    {
        if (verificaPermissaoAcesso(true)) {
            $retorno = array();
            $this->sql = "SELECT * FROM avas_exercicios WHERE idexercicio = " . $idexercicio . " AND ativo = 'S'";
            $exercicio = $this->retornarLinha($this->sql);
            $exercicio['nova'] = true;

            $this->sql = "SELECT * FROM avas_exercicios_disciplinas WHERE idexercicio = " . $idexercicio . " AND ativo = 'S'";
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

            $this->sql = 'INSERT INTO matriculas_exercicios SET inicio = now(), idexercicio = ' . $idexercicio . ', idmatricula = ' . $this->id;
            $this->executaSql($this->sql);
            $exercicio['idmatricula_exercicio'] = mysql_insert_id();

            foreach ($perguntas as $pergunta) {
                $this->sql = 'INSERT INTO matriculas_exercicios_perguntas SET idmatricula_exercicio = ' . $exercicio['idmatricula_exercicio'] . ', idpergunta = ' . $pergunta['idpergunta'];
                $this->executaSql($this->sql);
            }

            $perguntas = $this->retornarExercicio($exercicio['idmatricula_exercicio']);
            $exercicio['perguntas'] = $perguntas;

            return $exercicio;
        }
    }

    function contabilizarSimulado($idava)
    {
        if (verificaPermissaoAcesso(true)) {
            $this->sql = 'select
                            count(1) as total
                        from
                            matriculas_rotas_aprendizagem_objetos
                        where
                            idmatricula = ' . $this->id . ' and
                            idava = ' . $idava . ' and
                            idsimulado = 0';
            $verifica = $this->retornarLinha($this->sql);
            if ($verifica['total'] <= 0) {
                $this->sql = "select
                                IFNULL(sum(porcentagem_simulado),0) as porcentagem_simulado
                            from
                                avas
                            where
                                idava = " . $idava;
                $porcentagem = $this->retornarLinha($this->sql);
                if (!$porcentagem['porcentagem_simulado'])
                    $porcentagem['porcentagem_simulado'] = 0;

                $this->sql = "insert into
                                matriculas_rotas_aprendizagem_objetos
                            set
                                data_cad = now(),
                                idmatricula = " . $this->id . ",
                                idava = " . $idava . ",
                                porcentagem = " . $porcentagem['porcentagem_simulado'] . ",
                                idsimulado = 0";
                $this->executaSql($this->sql);

                $this->retornarAndamento();
            }

            return true;
        }
    }

    public function retornarListaArquivosMatricula()
    {
        $this->sql = "SELECT *,idarquivo as iddocumento FROM contas_arquivos
                    WHERE idmatricula = {$this->id}
                                AND ativo = 'S'";
        $this->ordem = 'ASC';
        $this->ordem_campo = "data_cad";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function ListarMatriculasMapaAlcance($idoferta, $idcurso, $idestado)
    {
        $matriculas = array();

        if ($idoferta || $idcurso || $idestado) {
            $this->sql = 'SELECT
                            ci.latitude,
                            ci.longitude
                        FROM
                            matriculas m
                            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
                            INNER JOIN cidades ci ON (p.idcidade = ci.idcidade)
                            INNER JOIN estados es ON (p.idestado = es.idestado)
                        WHERE
                            m.ativo = "S"';

            if ($_SESSION["adm_gestor_sindicato"] <> "S")
                $this->sql .= ' AND m.idsindicato IN (' . $_SESSION['adm_sindicatos'] . ')';

            if ($idoferta)
                $this->sql .= ' AND m.idoferta = ' . $idoferta;

            if ($idcurso)
                $this->sql .= ' AND m.idcurso = ' . $idcurso;

            if ($idestado)
                $this->sql .= ' AND p.idestado = ' . $idestado;

            $this->ordem_campo = 'm.idmatricula';
            $this->ordem = 'DESC';
            $this->limite = -1;
            $matriculas = $this->retornarLinhas();
        }

        return $matriculas;
    }

    public function enviarSms($idchave, $origem, $nome, $celular, $sms)
    {

        require_once '../classes/sms.class.php';

        $smsobj = new Sms();

        $smsobj->Set('idchave', $idchave);
        $smsobj->Set('origem', $origem);

        $smsobj->Set('url_webservicesms', $GLOBALS['config']['linkapiSMS']);
        $dados_gateway = array(
            'loginSMS' => $GLOBALS['config']['loginSMS'],
            'tokenSMS' => $GLOBALS['config']['tokenSMS'],
            'celular' => $celular,
            'nome' => $nome,
            'mensagem' => $sms
        );

        $smsobj->Set('dado_seguro', $dados_gateway);
        $smsobj->ExecutaIntegraSMS();
    }

    public function retornarInicioCurso()
    {

        $this->sql = 'SELECT
                        oc.data_inicio_aula,
                        oc.dias_para_prova
                    FROM
                        matriculas m
                        INNER JOIN ofertas_cursos oc ON
                        (oc.idoferta = m.idoferta AND oc.idcurso = m.idcurso AND oc.ativo = "S")
                    WHERE
                        m.idmatricula = ' . (int)$this->id;
        return $this->retornarLinha($this->sql);
    }

    public function retornarAcessoAva()
    {
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
                        m.idmatricula = ' . $this->id . ' AND
                        m.ativo = "S"';

        $datas = $this->retornarLinha($this->sql);

        $retorno['dias_para_prova'] = $datas['dias_para_prova'];

        if ($datas['data_inicio_ava']) {
            $retorno['data_inicio_acesso_ava'] = $datas['data_inicio_ava'];
            if ($retorno['data_inicio_acesso_ava'] > $dataHoje) {
                $retorno['pode_acessar_ava'] = false;
            }
        }

        if ($datas['data_prolongada']) {
            $retorno['data_limite_acesso_ava'] = $datas['data_prolongada'];
        } elseif ($datas['dias_para_ava'] || $datas['data_limite_ava']) {

            $dataDiasParaAva = NULL;
            if ($datas['dias_para_ava']) {
                $dataDiasParaAva = new DateTime($datas['data_matricula']);
                $dataDiasParaAva->modify('+ ' . $datas['dias_para_ava'] . ' days');
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
            } elseif ($dataDiasParaAva) {
                $retorno['data_limite_acesso_ava'] = $dataDiasParaAva->format('Y-m-d');
            } else {
                $retorno['data_limite_acesso_ava'] = $dataLimiteAva->format('Y-m-d');
            }
        }

        if ($retorno['data_limite_acesso_ava'] < $dataHoje) {
            $retorno['pode_acessar_ava'] = false;
        }

        return $retorno;
    }

    public function retornarSituacaoConcluido()
    {
        $this->sql = 'SELECT idsituacao FROM matriculas_workflow WHERE fim = "S" AND ativo = "S" ORDER BY idsituacao DESC LIMIT 1';
        return $this->retornarLinha($this->sql);
    }

    public function reenviarEmailContrato()
    {

        $sql = 'SELECT p.* FROM pessoas p INNER JOIN matriculas m ON m.idpessoa = p.idpessoa where m.idmatricula = "' . $this->id . '"';
        $resultado = $this->executaSql($sql);
        $pessoa = mysql_fetch_assoc($resultado);

        $nomePara = utf8_decode($pessoa["nome"]);

        $message = "Ol&aacute; <strong>" . $nomePara . "</strong>,
                    <br /><br />
                    Um novo contrato foi gerado para a matr&iacute;cula #" . $this->id . ", acesse a p&aacute;gina de contratos para a visualiza&ccedil;&atilde;o ou para aceitar o contrato.
                    <br /><br />
                    <a href=\"http://" . $_SERVER["SERVER_NAME"] . "/aluno/secretaria/contratos/" . $this->id . "\">Clique aqui</a> para aceitar o contrato.
                    <br /><br />";

        $emailPara = $pessoa["email"];
        $assunto = utf8_decode("Novo contrato na matrícula #" . $this->id);

        $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
        $emailDe = $GLOBALS["config"]["emailSistema"];

        $emailPessoa = $this->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara);
        if ($emailPessoa) {

            $this->AdicionarHistorico($this->idusuario, "contrato", "enviou", NULL, NULL, NULL);

            $this->sql = "SELECT
                            p.*
                        FROM
                            pessoas p
                            INNER JOIN matriculas_associados ma ON (ma.idpessoa = p.idpessoa)
                        WHERE
                            ma.idmatricula = " . $this->id . " AND
                            ma.ativo = 'S'";
            $devedor = $this->retornarLinha($this->sql);
            if ($devedor['idpessoa']) {

                $nomePara = utf8_decode($devedor["nome"]);

                $message = "Ol&aacute; <strong>" . $nomePara . "</strong>,
                            <br /><br />
                            Um novo contrato foi gerado para a matr&iacute;cula #" . $this->id . ", acesse a p&aacute;gina de contratos para a visualiza&ccedil;&atilde;o ou para aceitar o contrato.
                            <br /><br />
                            <a href=\"http://" . $_SERVER["SERVER_NAME"] . "/devedorsolidario/?cpf=" . $devedor["documento"] . "\">Clique aqui</a> para aceitar o contrato.
                            <br /><br />";

                $emailPara = $devedor["email"];
                $assunto = utf8_decode("Novo contrato na matrícula #" . $this->id);

                $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
                $emailDe = $GLOBALS["config"]["emailSistema"];

                return $this->EnviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara);
            } else {
                return $emailPessoa;
            }
        } else {
            return $emailPessoa;
        }
    }

    public function RetornarProvasSolicitadas()
    {
        $this->sql = "SELECT
                        ps.id_solicitacao_prova,
                        DATE_FORMAT(ps.data_cad, '%d/%m/%Y às %Hh%i') AS data_solicitacao,
                        DATE_FORMAT(pp.data_realizacao, '%d/%m/%Y') AS data_realizacao,
                        DATE_FORMAT(pp.hora_realizacao_de, '%H:%i') AS de,
                        DATE_FORMAT(pp.hora_realizacao_ate, '%H:%i') AS ate,
                        po.nome_fantasia AS escola,
                        l.nome AS local,
                        ps.situacao,
                        mc.nome AS motivo_cancelamento
                    FROM
                        provas_solicitadas ps
                        INNER JOIN provas_presenciais pp ON (ps.id_prova_presencial = pp.id_prova_presencial)
                        LEFT JOIN escolas po ON (po.idescola = ps.idescola)
                        LEFT JOIN locais_provas l ON (l.idlocal = ps.idlocal)
                        LEFT JOIN motivos_cancelamento_solicitacao_prova mc ON (mc.idmotivo = ps.idmotivo)
                    WHERE
                        ps.idmatricula = " . $this->id . " AND
                        ps.ativo = 'S' ";
        $this->ordem = "DESC";
        $this->ordem_campo = "ps.id_solicitacao_prova";
        $this->limite = -1;
        $solicitacoes = $this->retornarLinhas();
        $retorno = array();
        foreach ($solicitacoes as $solicitacao) {
            $solicitacao['disciplinas'] = $this->retornarDisciplinasSolicitacao($solicitacao['id_solicitacao_prova']);
            if ($solicitacao['escola']) {
                $solicitacao['escola_local'] = $solicitacao['escola'];
            } else {
                $solicitacao['escola_local'] = $solicitacao['local'];
            }
            $retorno[] = $solicitacao;
        }

        return $retorno;
    }

    public function retornarDisciplinasSolicitacao($idsolicitacao)
    {
        $this->sql = 'SELECT
                        d.iddisciplina,
                        d.nome
                    FROM
                        provas_solicitadas_disciplinas psd
                        INNER JOIN disciplinas d ON ( d.iddisciplina = psd.iddisciplina )
                    WHERE
                        psd.ativo = "S" AND
                        psd.id_solicitacao_prova = "' . $idsolicitacao . '"';
        $this->ordem = "ASC";
        $this->ordem_campo = "d.nome";
        $this->limite = -1;
        $disciplinas = $this->retornarLinhas();
        $disciplinas = array_map('array_pop', $disciplinas);
        $disciplinas = implode(', ', $disciplinas);

        return $disciplinas;
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

    public function se_historico()
    {
        $this->sql = 'SELECT idhistorico_escolar FROM cursos_sindicatos
                      WHERE
                          idsindicato = ' . $this->matricula["idsindicato"] . '
                          AND idcurso = ' . $this->matricula["idcurso"];
        $res = $this->retornarLinha($this->sql);
        return (is_numeric($res['idhistorico_escolar'])) ? (true) : (false);
    }

    public function retornaSimuladosRealizadosMatricula($idmatricula, $idava)
    {
        $this->sql = 'SELECT
                        masl.*,
                        aas.nome as simulado
                    FROM
                        avas_simulados aas
                        INNER JOIN matriculas_simulados masl ON (masl.idsimulado = aas.idsimulado)
                    WHERE
                        masl.idmatricula = ' . (int)$idmatricula . ' AND
                        aas.idava = ' . (int)$idava . ' AND
                        aas.ativo = "S" AND
                        masl.ativo = "S"
                        AND masl.idmatricula_simulado in (
                            SELECT
                                MAX(idmatricula_simulado)
                            FROM
                                matriculas_simulados
                            GROUP BY idsimulado)';

        $this->ordem = "DESC";
        $this->ordem_campo = "masl.idmatricula_simulado";
        $this->limite = -1;
        $simulados = $this->retornarLinhas();

        return $simulados;
    }

    /**
     * Retorna quantidade de matrículas de uma pessoa no mesmo curso, oferta, polo e escola
     * @access public
     * @param int $idpessoa : [Obrigatório] ID da pessoa que verificará as matrículas
     * @param int $idoferta : [Obrigatório] ID da idoferta que verificará as matrículas
     * @param int $idcurso : [Obrigatório] ID do idcurso que verificará as matrículas
     * @param int $idescola : [Obrigatório] ID da idescola que verificará as matrículas
     * @return array
     * @var
     * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
     */
    public function verificaMatriculado($idpessoa, $idoferta, $idcurso, $idescola)
    {
        $this->sql = 'SELECT
                COUNT(m.idmatricula) AS total
            FROM
                matriculas m
                inner join matriculas_workflow mw ON (mw.idsituacao = m.idsituacao)
            WHERE
                m.idpessoa = ' . $idpessoa . ' AND
                m.idoferta = ' . $idoferta . ' AND
                m.idcurso = ' . $idcurso . ' AND
                m.idescola = ' . $idescola . ' AND
                m.ativo = "S" AND
                mw.inativa <> "S" AND
                mw.cancelada <> "S"';
        return $this->retornarLinha($this->sql);
    }

    public function atualizarSituacaoConcluido($idMatricula)
    {
        if (!is_numeric($idMatricula)) {
            throw new InvalidArgumentException('O parametro `IdMatricula` precisa ser um valor numérico.');
        }

        $idMatricula = (int)$idMatricula;

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
        $this->adicionarHistorico(null, 'situacao', 'modificou', $linhaAntiga['idsituacao'], $linhaNova['idsituacao'], null);
        $this->AdicionarHistorico(null, 'data_conclusao', 'modificou', $linhaAntiga['data_conclusao'], $dataAtual, null);

        $retorno['sucesso'] = true;
        $retorno['mensagem'] = 'mensagem_situacao_sucesso';

        return $retorno;
    }

    public function retornarContratosPendentes()
    {

        $this->sql = 'SELECT
            mcg.*,
            mcg.aceito AS aceito_aluno,
            mcg.aceito_data AS aceito_aluno_data,
            "S" AS contrato_pendente,
            c.nome as contrato,
            ct.nome as tipo
          FROM
            matriculas_contratos_gerados mcg
            INNER JOIN contratos c ON (mcg.idcontrato = c.idcontrato)
            INNER JOIN contratos_tipos ct ON (ct.idtipo = c.idtipo)
          where
            mcg.idmatricula = ' . $this->id . ' and
            mcg.ativo = "S"';

        $this->ordem_campo = 'idmatricula_contrato';
        $this->ordem = 'ASC';
        $this->limite = -1;

        return $this->retornarLinhas();
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

    public function alterarSituacaoDetran($idmatricula)
    {
        $this->id = $idmatricula;
        $matricula = $this->retornar();

        if (!in_array($matricula['detran_situacao'], ['NL', 'LI'])) {
            $retorno['sucesso'] = false;
            $retorno['mensagem'] = 'erro_alterar_situacao_diferente_nao_liberado';
            return $retorno;
        }

        $sql = 'UPDATE matriculas SET detran_situacao = "AL", detran_creditos = "N", detran_certificado = "N" WHERE idmatricula = ' . $idmatricula;
        $this->executaSql($sql);

        $this->adicionarHistorico($this->idusuario, 'detran_situacao', 'modificou', $matricula['detran_situacao'], 'AL', null);

        if ($matricula['detran_creditos'] == 'S')
            $this->adicionarHistorico($this->idusuario, 'detran_creditos', 'modificou', 'S', 'N', null);

        if ($matricula['detran_certificado'] == 'S')
            $this->adicionarHistorico($this->idusuario, 'detran_certificado', 'modificou', 'S', 'N', null);

        $retorno['sucesso'] = true;
        return $retorno;
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

    public function cancelarTentativaProva()
    {
        $totalTentativas = $this->retornarToTalTentativasProva();
        if (count($totalTentativas) === 0) {
            $retorno['sucesso'] = false;
            $retorno['msg'] = 'erro_sem_tentativas';
            return $retorno;
        }

        $sql = "UPDATE matriculas_avaliacoes SET ativo = 'N' WHERE idmatricula = {$this->id} AND idprova = " . $totalTentativas[0]['idprova'];

        if (!$this->executaSql($sql)) {
            $retorno['sucesso'] = false;
            $retorno['msg'] = 'erro_zerar_tentativas';
            return $retorno;
        }

        $this->removerTodasAsNotas($this->id, $totalTentativas[0]['idprova']);

        $this->adicionarHistorico($this->idusuario, 'cancelar_tentativa_prova', 'cancelou', null, null, $totalTentativas[0]['idprova']);

        $retorno['sucesso'] = true;
        $retorno['msg'] = 'sucesso_zerar_tentativas';
        return $retorno;
    }

    public function zerarTentativasProva()
    {
        $totalTentativas = $this->retornarToTalTentativasProva();
        if ($totalTentativas === 0) {
            $retorno['sucesso'] = false;
            $retorno['msg'] = 'erro_sem_tentativas';
            return $retorno;
        }

        $sql = "UPDATE matriculas_avaliacoes SET ativo = 'N' WHERE idmatricula = {$this->id} AND ativo='S'";

        if (!$this->executaSql($sql)) {
            $retorno['sucesso'] = false;
            $retorno['msg'] = 'erro_zerar_tentativas';
            return $retorno;
        }

        $this->removerTodasAsNotas($this->id);

        $this->adicionarHistorico($this->idusuario, 'tentativas_prova', 'modificou', null, null, null);

        $retorno['sucesso'] = true;
        $retorno['msg'] = 'sucesso_zerar_tentativas';
        return $retorno;
    }

    public function zerarTentativa($idprova)
    {
        $totalTentativas = $this->retornarToTalTentativasProva();

        if ($totalTentativas === 0) {
            $retorno['sucesso'] = false;
            $retorno['msg'] = 'erro_sem_tentativas';
            return $retorno;
        }

        $sql = "UPDATE
                matriculas_avaliacoes
            SET
                ativo = 'N'
            WHERE
                idmatricula = {$this->id}
                AND ativo='S'
                AND idprova = {$idprova}
        ";

        if (!$this->executaSql($sql)) {
            $retorno['sucesso'] = false;
            $retorno['msg'] = 'erro_zerar_tentativas';
            return $retorno;
        }

        $this->removerTodasAsNotas($this->id, $idprova);

        $this->adicionarHistorico(
            $this->idusuario,
            'zerar_tentativas_prova',
            'modificou',
            null,
            null,
            $idprova
        );

        $retorno['sucesso'] = true;
        $retorno['msg'] = 'sucesso_zerar_tentativas';

        return $retorno;
    }

    public function retornarToTalTentativasProva()
    {
        $this->sql = "SELECT ma.*, ava.nome as avaliacao FROM matriculas_avaliacoes ma inner join avas_avaliacoes as ava on ma.idavaliacao = ava.idavaliacao  WHERE ma.idmatricula = {$this->id} AND ma.ativo = 'S'";
        $this->limite = -1;
        $this->groupby = "idprova";
        $this->ordem_campo = "ma.data_correcao ASC, ma.idprova";
        $this->ordem = "ASC";

        $total = $this->retornarLinhas();

        return $total;
    }

    private function removerTodasAsNotas($idmatricula, $idprova = null)
    {
        $sql = "
            UPDATE matriculas_notas
            SET ativo = 'N'
            WHERE idmatricula = {$idmatricula}
            AND ativo='S'
            ";

        if (!empty($idprova)) {
            $sql .= "AND idprova = {$idprova}";
        }

        return $this->executaSql($sql);
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
                        c.idmatricula = "' . $this->id . '" AND
                        c.ativo = "S" AND
                        c.data_vencimento < "' . $data_atual->format('Y-m-d') . '"';
        $retorno = $this->retornarLinha($this->sql);
        if (!$retorno['idconta']) {
            return false;
        }
        return true;
    }

    public function retornarUltimoRetornoDetran($matricula, $estadosDetran)
    {

        if (empty($matricula['escola']['detran_codigo']) || !in_array($matricula['escola']['idestado'], $estadosDetran)) {
            $log['retorno'] = false;
        } else {
            $this->sql = "SELECT string_envio, retorno FROM detran_logs WHERE idmatricula = {$this->id} AND ativo = 'S' ORDER BY idlog DESC limit 1";

            $log = $this->retornarLinha($this->sql);
        }
        return $log;
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

    public function listarTodosAcompanhamentoContratosMatriculas()
    {
        $this->sql = "SELECT
                   {$this->campos}
                 FROM
                   matriculas m";

        if ($this->incluirPessoas) {
            $this->sql .= " INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)";
        }
        if ($this->incluirOfertas) {
            $this->sql .= " INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN ofertas_turmas ot ON (m.idturma = ot.idturma) ";
        }
        if ($this->incluirContratos) {
            $this->sql .= " LEFT JOIN matriculas_contratos_gerados cg on (m.idmatricula = cg.idmatricula and cg.aceito = 'N')
            LEFT JOIN matriculas_contratos mc on (m.idmatricula = mc.idmatricula and mc.aceito_aluno = 'N') ";
        }

        $this->sql .= " where
                        m.ativo = 'S'";

        if ($this->matriculasIn !== null) {
            $this->sql .= " AND m.idmatricula IN ({$this->matriculasIn})";
        }

        if ($this->idescola) {
            $this->sql .= ' AND e.idescola = ' . $this->idescola;
        }

        if ($this->naotraz) {
            $this->sql .= " AND (m.idmatricula <>  {$this->naotraz} and (m.combo_matricula <> '{$this->naotraz}' or m.combo_matricula is null)) ";
        }

        if ($_SESSION["adm_gestor_sindicato"] <> "S" && $this->mapa_alcance)
            $this->sql .= " and m.idsindicato in (" . $_SESSION["adm_sindicatos"] . ") ";
        if ($this->idvendedor)
            $this->sql .= " and m.idvendedor = '" . $this->idvendedor . "' ";
        if (is_array($_GET["q"])) {
            foreach ($_GET["q"] as $campo => $valor) {
                if ($campo == '1|cg.aceito' && $valor == 'N') {
                    $this->sql .= " and cg.aceito = '" . $valor . "' ";
                    $this->sql .= " or mc.aceito_aluno = '" . $valor . "' ";
                    break;
                }
            }
            foreach ($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|", $campo);
                $valor = str_replace("'", "", $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === "0") and $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        if ($campo[1] == 'cg.aceito' && $valor == 'N') {
                            continue;
                        } else if ($campo[1] != 'cg.aceito' || $valor != 'S') {
                            $this->sql .= " and " . $campo[1] . " = '" . $valor . "' ";
                        } else {
                            $this->having = ' having count(cg.aceito) = 0 and count(mc.aceito_aluno) = 0';
                        }
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }
        $this->mantem_groupby = false;
        $matriculas = $this->retornarLinhas();


        $pagina = $this->pagina;
        $paginas = $this->paginas;
        $total = $this->total;
        foreach ($matriculas as $ind => $matricula) {
            $this->ordem_campo = null;
            $this->sql = "select aceito from matriculas_contratos_gerados where idmatricula = {$matricula['idmatricula']}";
            $matriculas[$ind]["matriculas_contratos_gerados"] = $this->retornarLinhas();
            $this->sql = "select aceito_aluno from matriculas_contratos where idmatricula = {$matricula['idmatricula']}";
            $matriculas[$ind]["matriculas_contratos"] = $this->retornarLinhas();
        }


        $this->pagina = $pagina;
        $this->paginas = $paginas;
        $this->total = $total;

        $this->ordem_campo = 'm.idmatricula';
        if (!$this->mapa_alcance) {
            foreach ($matriculas as $ind => $matricula) {
                $this->sql = "select * from sindicatos where idsindicato='" . $matricula["idsindicato"] . "'";
                $matriculas[$ind]["sindicato"] = $this->retornarLinha($this->sql);
                $this->sql = "select * from escolas where idescola='" . $matricula["idescola"] . "'";
                $matriculas[$ind]["escola"] = $this->retornarLinha($this->sql);
                $this->sql = "select * from cursos where idcurso='" . $matricula["idcurso"] . "'";
                $matriculas[$ind]["curso"] = $this->retornarLinha($this->sql);
            }
        }
        return $matriculas;
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

    /**
     * Método para retornar o documento que está com a situação aguardando da biometria.
     * @param $idMatricula
     * @param $idDocumento
     * @return mixed
     */
    public function retornarDocumentoBiometria($idMatricula, $idDocumento)
    {
        try {
            if (is_numeric($idMatricula) && is_numeric($idDocumento)) {

                $sql = "SELECT
                        md.*
                    FROM
                        matriculas_documentos md
                    INNER JOIN tipos_documentos td ON (md.idtipo = td.idtipo)
                    WHERE md.iddocumento = $idMatricula
                    AND md.idmatricula = $idDocumento
                    AND td.documento_foto_oficial = 'S'
                    AND md.situacao = 'aguardando'
                    AND md.ativo = 'S'
                    AND td.ativo = 'S'";

                return $this->retornarLinha($sql);
            } else {
                throw new InvalidArgumentException('Parâmetro(s) tem que ser do tipo númerico.');
            }
        } catch (InvalidArgumentException $e) {
            echo $e->getMessage();
        }

    }

    /**
     * Método para atualizar porcentagem da biometria do datavalid.
     * @param $idMatricula
     * @param $porcentagem
     * @return void
     */
    public function atualizarPorcentagemBiometriaDataValid($idMatricula, $porcentagem)
    {
        try {
        if(is_numeric($idMatricula) && is_numeric($porcentagem))
        {
            $sql = "UPDATE matriculas_reconhecimentos
                    SET probabilidade_datavalid = $porcentagem
                    WHERE idmatricula = $idMatricula AND ativo = 'S' and ativo_painel = 'S' AND foto_principal = 'S'";

            $this->executaSql($sql);

        } else {
            throw new Exception('Parâmetros tem que ser do tipo númerico');
        }
        } catch (InvalidArgumentException $e)
        {
            echo $e->getMessage();
        }

    }


    /**
     * Método para atualizar a coluna envio_foto_documento_oficial de acordo com os tipo de documentos que está com a coluna documento_foto_oficial = 'S'.
     * @return void
     */
    public function alterarEnvioDocumentoFotoOficial()
    {
        $sql = "SELECT idtipo FROM tipos_documentos WHERE documento_foto_oficial = 'S'";
        $documentos = $this->retornarLinha($sql);

        if (count($documentos) > 0)
        {
            $sql = "UPDATE matriculas
                INNER JOIN matriculas_documentos ON (matriculas.idmatricula = matriculas_documentos.idmatricula)
                SET matriculas.envio_foto_documento_oficial = 'S'
                WHERE matriculas.email_documento_biometria = 'S'
                AND matriculas.ativo = 'S'
                AND matriculas_documentos.idtipo IN (" . implode(",", array_values($documentos)) . ")";
            $this->executaSql($sql);

            $sql = $sql = "UPDATE matriculas
                INNER JOIN matriculas_documentos ON (matriculas.idmatricula = matriculas_documentos.idmatricula)
                SET matriculas.envio_foto_documento_oficial = 'N'
                WHERE matriculas.email_documento_biometria = 'S'
                AND matriculas.ativo = 'S'
                AND matriculas_documentos.idtipo NOT IN (" . implode(",", array_values($documentos)) . ")";
            $this->executaSql($sql);
        }

    }
}
