<?php
require_once dirname(__DIR__) . '/classes/matriculas.class.php';
require_once dirname(__DIR__) . '/classes/orio/Transacoes.php';
require_once dirname(__DIR__) . '/../detran/lib/restsecurity/ConnectionFactory.php';

use detran\restsecurity\ConnectionFactory as ConnectionFactory;

class Detran extends Core
{
    private $detran_tipo_aula;
    private $detran_horas_aula;
    private $detran_codigo_materia;
    private $matriculaObj;
    private $transacoes;
    public $rotinaCron;

    public function __construct()
    {
        parent::__construct();
        $this->detran_tipo_aula = $GLOBALS['detran_tipo_aula'];
        $this->detran_carga_horaria = $GLOBALS['detran_carga_horaria'];
        $this->detran_horas_aula = $GLOBALS['detran_horas_aula'];
        $this->detran_codigo_materia = $GLOBALS['detran_codigo_materia'];
        $this->matriculaObj = new Matriculas();
        $this->transacoes = new Transacoes();
        $this->rotinaCron = false;
    }

    public function setarConfiguracaoIntegracao($idEstado, $configuracao)
    {
        if (!$idEstado)
            throw new InvalidArgumentException('O parametro $idEstado é obrigatório.');
        if ($idEstado <= 0)
            throw new InvalidArgumentException('O parametro $idEstado tem que ser positivo.');

        if ($this->obterConfiguracaoIntegracao($idEstado) !== false) {
            $this->sql = "UPDATE configuracoes SET valor = '{$configuracao}' WHERE chave = 'detran_{$idEstado}'";
            return $this->executaSql($this->sql);
        } else {
            $this->sql = "INSERT INTO configuracoes (chave, valor) VALUES ('detran_{$idEstado}', '{$configuracao}')";
            return $this->executaSql($this->sql);
        }
    }

    public function listarEstadosIntegrados(){
        $arr = $arrUF = [];
        $sql = 
        "SELECT
            e.idestado, TRIM(UPPER(e.sigla)) as sigla, c.valor
        FROM configuracoes c
        INNER JOIN estados e ON (concat('detran_', e.idestado) = c.chave)
        ORDER BY e.sigla
        ";
        $arr = $this->retornarLinhasArray($sql);
        foreach ($arr as $linha) {
            $valor = json_decode($linha['valor']);
            if(isset($valor->ativo))
                $arrUF[$linha['sigla']] = $linha['idestado'];
        }
        return $arrUF;
    }

    public function obterConfiguracaoIntegracao($idEstado)
    {
        if (!$idEstado)
            throw new InvalidArgumentException('O parametro $idEstado é obrigatório.');
        if ($idEstado <= 0)
            throw new InvalidArgumentException('O parametro $idEstado tem que ser positivo.');

        $this->sql = "SELECT valor FROM configuracoes where chave = 'detran_{$idEstado}'";
        $retorno = $this->retornarLinha($this->sql);
        if ($retorno === false)
            return false;
        else
            return $retorno['valor'];
    }

    public function setarSituacaoIntegracao($idEstado, $situacao)
    {
        if (!$idEstado)
            throw new InvalidArgumentException('O parametro $idEstado é obrigatório.');

        if ($idEstado <= 0)
            throw new InvalidArgumentException('O parametro $idEstado tem que ser positivo.');

        if (!$situacao)
            throw new InvalidArgumentException('O parametro $situacao é obrigatório.');

        if (!in_array($situacao, ['S', 'N']))
            throw new InvalidArgumentException('O valor do parametro $situacao está incorreto, só é permitido "S" ou "N". Valor informado foi: ' . $situacao);

        $configuracao = $this->obterConfiguracaoIntegracao($idEstado);
        try {
            $configuracao = json_decode($configuracao);
            if(isset($configuracao->ativo))
                $configuracao->ativo = $situacao;
            else
                $configuracao = array('ativo' => $situacao);
        } catch (Exception $e) {
            $configuracao = array('ativo' => $situacao);
        }
        $configuracao = json_encode($configuracao);
        return $this->setarConfiguracaoIntegracao($idEstado, $configuracao);
    }

    public function obterSituacaoIntegracao($idEstado)
    {
        if (!$idEstado)
            throw new InvalidArgumentException('O parametro $idEstado é obrigatório.');

        if ($idEstado <= 0)
            throw new InvalidArgumentException('O parametro $idEstado tem que ser positivo.');

        $configuracao = $this->obterConfiguracaoIntegracao($idEstado);
        try {
            $configuracao = json_decode($configuracao);
            if ($configuracao->ativo == 'S')
                return true;
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    private function salvarLogDetran($codTransacao, $idMatricula, $retorno, $stringEnvio = null)
        {
            $sql = 'INSERT INTO detran_logs SET
                data_cad = NOW(),
                cod_transacao = "' . $codTransacao . '",
                idmatricula = ' . $idMatricula . ',
                retorno = "' . addslashes($retorno) . '"';

            if (!empty($stringEnvio)) {
                $sql .= ', string_envio = "' . addslashes($stringEnvio) . '"';
            }

            return $this->executaSql($sql);
        }

    private function salvarAcao($idmatricula, $tipo, $acao){
        if (!$this->rotinaCron)
            $this->matriculaObj
                ->set('id', $idmatricula)->set('modulo', 'gestor')
                ->adicionarHistorico($_SESSION["adm_idusuario"], $tipo, $acao, null, null, null);
    }

    public function DadosCreditoSE($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('SE');
        $sql = "SELECT
            m.idmatricula, p.documento, e.detran_codigo, o.idoferta, c.idcurso, e.idescola, mr.probabilidade_datavalid,
            (
                SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_creditos' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_creditos' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh
                WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'detran_situacao' AND mh.para = 'LI'
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso,
            m.detran_creditos
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
            INNER JOIN matriculas_reconhecimentos mr ON (m.idmatricula = mr.idmatricula AND mr.probabilidade_datavalid >= 0.85)
        WHERE
            {$info['cursos']} AND
            e.idestado = {$info['idestado']} AND
            m.ativo = 'S' AND
            m.detran_situacao = 'LI' AND
            e.detran_codigo IS NOT NULL AND
            cw.fim = 'S' AND
            m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CreditosEspecificoSE($stringEnvio, $matricula)
    {
        $dados['idmatricula'] = $matricula;
        $this->salvarAcao($dados['idmatricula'], 'detran_creditos', 'solicitou');
        define('INTERFACE_DETRAN_SE_CREDITOS', retornarInterface('detran_se_creditos')['id']);

        try {

            //inicio loop


            $dadosSOAP = [
                'executaTransacao' => [
                    'pUsuario' => $this->config['detran']['SE']['pUsuario'],
                    'pSenha' => $this->config['detran']['SE']['pSenha'],
                    'pAmbiente' => $this->config['detran']['SE']['pAmbiente'],
                    'pMensagem' => $stringEnvio,
                ]
            ];
            $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_SE_CREDITOS, 'E', $dadosSOAP);
            $opcoesSOAP = array(
                'trace' => 1,
                'connection_timeout' => 15,
                ['exceptions' => true]
            );
            $soapCliente = new SoapClient($this->config['detran']['SE']['urlWsl'], $opcoesSOAP);
            $options = ['location' => $this->config['detran']['SE']['urlSoap']];
            $respostaSoap = $soapCliente->__soapCall('executaTransacao', $dadosSOAP, $options);

            $retornoDetran['erro'] = false;
            $retornoDetran['mensagem'] = json_encode($respostaSoap->executaTransacaoResult);

            $this->executaSql('BEGIN');

            if (substr($respostaSoap->executaTransacaoResult, 0, 3) == 999) {

                $this->transacoes->finalizaTransacao(null, 2, null, $respostaSoap->executaTransacaoResult);
            } else {
                $this->transacoes->finalizaTransacao(null, 5, null, $respostaSoap->executaTransacaoResult);
                $disciplinaRespostaErro = true;
                $retornoDetran['erro'] = true;
            }

            $this->salvarLogDetran(
                424,
                $dados['idmatricula'],
                $respostaSoap->executaTransacaoResult,
                $stringEnvio);
            $this->executaSql('COMMIT');
            //aqui fim loop

            if (empty($disciplinaRespostaErro)) {
                $sql = 'UPDATE matriculas SET detran_creditos = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $this->executaSql($sql);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_creditos', 'modificou', 'N', 'S', null);
            }
        } catch (Exception $excecao) {
            $this->transacoes->set('json', json_encode(['codigo' => $excecao->getCode(), 'mensagem' => $excecao->getMessage()]));
            $this->transacoes->finalizaTransacao(null, 5);
            $this->transacoes->set('json', null);
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_creditos', 'detran_nao_respondeu', null, null, null);
            }
            $tipoErro = (get_class($excecao) == 'SoapFault') ? 'A conexão com Detran-SE' : 'O Oráculo';
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = $tipoErro.' apresentou uma falha técnica inesperada.';
            $retornoDetran['falha_tecnica'] = true;
        }
        return $retornoDetran;
    }

    public function CreditosSE($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_creditos', 'solicitou');
        define('INTERFACE_DETRAN_SE_CREDITOS', retornarInterface('detran_se_creditos')['id']);

        try {
            $horaFimAulaObj = null;

            $dados['detran_tipo_aula'] = $this->detran_tipo_aula['SE'][$dados['idcurso']];

            $dataAula = null;
            $horaInicioAula = null;
            $horaFimAula = null;
            $disciplinaCodigos = implode(',', array_keys($dados['detran_tipo_aula']['DISCIPLINAS']));
            $sql = "SELECT
                COUNT(d.iddisciplina) as total_disciplinas
            FROM
                ofertas_cursos_escolas oce
                INNER JOIN ofertas_curriculos_avas oca ON (
                    oca.idoferta = oce.idoferta AND
                    oca.idcurriculo = oce.idcurriculo AND
                    oca.ativo = 'S' AND oca.idava IS NOT NULL)
                INNER JOIN curriculos_blocos cb ON (
                    cb.idcurriculo = oca.idcurriculo AND
                    cb.ativo = 'S')
                INNER JOIN curriculos_blocos_disciplinas cbd ON (
                    cbd.idbloco = cb.idbloco AND
                    cbd.ativo = 'S' )
                INNER JOIN disciplinas d ON (
                    d.iddisciplina = cbd.iddisciplina AND
                    d.iddisciplina = oca.iddisciplina AND
                    d.ativo = 'S')
            WHERE
                oce.idoferta = {$dados['idoferta']} AND
                oce.idcurso = {$dados['idcurso']} AND
                oce.idescola = {$dados['idescola']} AND
                d.iddisciplina IN ({$disciplinaCodigos})";

            $disciplinas = $this->retornarLinha($sql);

            if ($disciplinas['total_disciplinas'] == 0) {
                $sql = 'UPDATE matriculas SET detran_creditos = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $this->executaSql($sql);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_creditos', 'modificou', 'N', 'S', null);

                    $retornoDetran['erro'] = false;
                    $retornoDetran['mensagem'] = 'Não existe disciplinas interfaceadas para a oferta de curso. Simulada a atualização dos créditos!';
                    return $retornoDetran;
            }

            $this->sql = "SELECT
                d.iddisciplina, cbd.horas, oca.idava,
                (
                    SELECT CONCAT_WS(' ', dmde.data_aula, dmde.hora_fim_aula) FROM detran_matriculas_disciplinas_enviadas dmde
                    WHERE dmde.idmatricula = {$dados['idmatricula']} AND dmde.ativo = 'S'
                    ORDER BY dmde.data_aula DESC, dmde.hora_fim_aula DESC LIMIT 1
                ) AS data_ultima_aula,
                (
                    SELECT COUNT(dmde.idenviado) FROM detran_matriculas_disciplinas_enviadas dmde
                    WHERE dmde.idmatricula = {$dados['idmatricula']} AND
                    dmde.iddisciplina = d.iddisciplina AND dmde.ativo = 'S'
                ) AS total_horas_enviadas
            FROM
                ofertas_cursos_escolas oce
                INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = oce.idoferta AND oca.idcurriculo = oce.idcurriculo AND oca.ativo = 'S' AND oca.idava IS NOT NULL)
                INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = oca.idcurriculo AND cb.ativo = 'S')
                INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = 'S' )
                INNER JOIN disciplinas d ON (d.iddisciplina = cbd.iddisciplina AND d.iddisciplina = oca.iddisciplina AND d.ativo = 'S')
            WHERE
                oce.idoferta = {$dados['idoferta']} AND
                oce.idcurso = {$dados['idcurso']} AND
                oce.idescola = {$dados['idescola'] } AND
                d.iddisciplina IN ({$disciplinaCodigos})";

            $queryDisciplinas = $this->retornarLinhas();

            if (!empty($queryDisciplinas[0]['data_ultima_aula'])) {
                $dataAulaObj = (new \DateTime($queryDisciplinas[0]['data_ultima_aula']));
            } else {
                $dataAulaObj = (new \DateTime($dados['data_inicio_curso']));
            }

            $disciplinaRespostaErro = false;

            $horaLimiteDiaria = 0;       //Caso já tenha sido enviado mais de 8h, será enviado no outro dia.
            $horaLimiteIntervalo = 0;    //Caso já tenha sido enviado mais de 4h, será enviado após 1h.

            foreach($queryDisciplinas as $disciplina) {
                $disciplina['detran_codigo_materia'] =
                    $dados['detran_tipo_aula']['DISCIPLINAS'][$disciplina['iddisciplina']];

                $qntHorasAulas = $this->detran_horas_aula['SE'][$dados['detran_tipo_aula']['CODIGO']]
                [$disciplina['detran_codigo_materia']];

                $disciplina['detran_codigo_materia'] = str_pad($disciplina['detran_codigo_materia'], 2, ' ', STR_PAD_LEFT);
                $categoriaPratica = str_pad('', 1, ' ');
                $placaCodSimulador = str_pad('', 30, ' ');
                $codCursoEspecializado = str_pad('', 2, ' ');
                $nAcertosProva = str_pad('', 2, ' ');
                $creditoManual = str_pad('', 1, ' ');
                $cpfRespCredManual = str_pad('', 11, ' ');
                $qntAulasSimuladorNoturnas = str_pad('', 1, ' ');
                $biometria = str_pad(str_replace('.','', $dados["probabilidade_datavalid"]) . '00', 506, ' ', STR_PAD_LEFT);

                // Loop de envio de horas por disciplina
                for ($indHora = $disciplina['total_horas_enviadas']; $indHora < $qntHorasAulas; $indHora++) {

                    $horaInicioAulaObj = \DateTime::createFromFormat('Y-m-d H:i:s', $dataAulaObj->format('Y-m-d H') . ':00:00')
                        ->modify('+1 hours');

                    // Atribuições da hora de inicio da aula verificando se é ou não o primeiro envio
                    if ($indHora > 0 && !empty($horaFimAulaObj)) {
                        $horaInicioAulaObj = \DateTime::createFromFormat('Y-m-d H:i:s', $horaFimAulaObj->format('Y-m-d H:i:s'))->modify('+10 minutes');
                    } elseif (!empty($disciplina['data_ultima_aula'])) {
                        $horaInicioAulaObj = $dataAulaObj;
                        if ($disciplina['total_horas_enviadas'] == 0) {
                            $horaInicioAulaObj->modify('+1 hours');
                        }
                    } elseif (!empty($horaFimAulaObj)) {
                        $horaInicioAulaObj = \DateTime::createFromFormat('Y-m-d H:i:s', $horaFimAulaObj->format('Y-m-d H:i:s'))
                            ->modify('+1 hours');
                    }

                    $horaFimAulaObj = \DateTime::createFromFormat('Y-m-d H:i:s', $horaInicioAulaObj->format('Y-m-d H') . ':00:00')
                        ->modify('+1 hours');
                    $horaLimiteAulaDetran = \DateTime::createFromFormat('Y-m-d H:i:s', $dataAulaObj->format('Y-m-d') . '22:00:00');

                    // Verificar se ultrapassou o horário limite de 22h ou 8h por dia
                    if ($horaFimAulaObj > $horaLimiteAulaDetran || $horaLimiteDiaria >= 8) {
                        $dataAulaObj->modify('+1 days');
                        $horaInicioAulaObj = \DateTime::createFromFormat('Y-m-d H:i:s', $dataAulaObj->format('Y-m-d') . '06:00:00');

                        // Zerada a contagem pois é um novo dia
                        $horaLimiteDiaria = 0;
                        $horaLimiteIntervalo = 0;
                    } else {
                        // Verificar se já foi enviado 4h no mesmo dia para ser adicionado o intervalo de 1h
                        if($horaLimiteIntervalo >= 4){
                            $horaInicioAulaObj->modify('+1 hours');
                            $horaLimiteIntervalo = 0;
                        }
                    }

                    // Contabiliza as horas enviadas
                    $horaLimiteDiaria++;
                    $horaLimiteIntervalo++;

                    $dataAula = $dataAulaObj->format('Ymd');
                    $horaInicioAula = $horaInicioAulaObj->format('H00');
                    $horaFimAulaObj = \DateTime::createFromFormat('Y-m-d H:i:s', $horaInicioAulaObj->format('Y-m-d H') . ':00:00')
                        ->modify('+50 minutes');
                    $horaFimAula = $horaFimAulaObj->format('Hi');

                    $stringEnvio = 424 . 'CFC' . $dados['detran_codigo'] . $this->config['detran']['SE']['registro_instrutor'] .
                        $dados['documento'] . $dataAula . $horaInicioAula . $horaFimAula .
                        $disciplina['detran_codigo_materia'] . $dados['detran_tipo_aula']['CODIGO'] . $categoriaPratica . $placaCodSimulador .
                        $codCursoEspecializado . $nAcertosProva . $creditoManual . $cpfRespCredManual . $qntAulasSimuladorNoturnas . $biometria;

                    // Dados a serem enviados via SOAP
                    $dadosSOAP = [
                        'executaTransacao' => [
                            'pUsuario' => $this->config['detran']['SE']['pUsuario'],
                            'pSenha' => $this->config['detran']['SE']['pSenha'],
                            'pAmbiente' => $this->config['detran']['SE']['pAmbiente'],
                            'pMensagem' => $stringEnvio,
                        ]
                    ];
                    $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_SE_CREDITOS, 'E', $dadosSOAP);
                    $opcoesSOAP = array(
                        'trace' => 1,
                        'connection_timeout' => 15,
                        ['exceptions' => true]
                    );
                    $soapCliente = new SoapClient($this->config['detran']['SE']['urlWsl'], $opcoesSOAP);
                    $options = ['location' => $this->config['detran']['SE']['urlSoap']];
                    $respostaSoap = $soapCliente->__soapCall('executaTransacao', $dadosSOAP, $options);

                    $retornoDetran['erro'] = false;
                    $retornoDetran['mensagem'] = json_encode($respostaSoap->executaTransacaoResult);

                    $this->executaSql('BEGIN');

                    if (substr($respostaSoap->executaTransacaoResult, 0, 3) == 999) {
                        $sql = 'INSERT INTO detran_matriculas_disciplinas_enviadas SET
                        ativo = "S",
                        data_cad = NOW(),
                        idmatricula = ' . $dados['idmatricula'] . ',
                        iddisciplina = ' . $disciplina['iddisciplina'] . ',
                        data_aula = "' . $dataAulaObj->format('Y-m-d') . '",
                        hora_inicio_aula = "' . $horaInicioAulaObj->format('H:i:s') . '",
                        hora_fim_aula = "' . $horaFimAulaObj->format('H:i:s') . '"';
                        $this->executaSql($sql);
                        $this->transacoes->finalizaTransacao(null, 2, null, $respostaSoap->executaTransacaoResult);
                    } else {
                        $this->transacoes->finalizaTransacao(null, 5, null, $respostaSoap->executaTransacaoResult);
                        $disciplinaRespostaErro = true;
                        $retornoDetran['erro'] = true;
                    }

                    $this->salvarLogDetran(
                        424,
                        $dados['idmatricula'],
                        $respostaSoap->executaTransacaoResult,
                        $stringEnvio);
                    $this->executaSql('COMMIT');
                }
            }
            // Caso não exista erros modifica a flag de créditos da matrícula
            if (empty($disciplinaRespostaErro)) {
                $sql = 'UPDATE matriculas SET detran_creditos = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $this->executaSql($sql);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_creditos', 'modificou', 'N', 'S', null);
            }
        } catch (Exception $excecao) {
            $this->transacoes->set('json', json_encode(['codigo' => $excecao->getCode(), 'mensagem' => $excecao->getMessage()]));
            $this->transacoes->finalizaTransacao(null, 5);
            $this->transacoes->set('json', null);
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_creditos', 'detran_nao_respondeu', null, null, null);
            }
            $tipoErro = (get_class($excecao) == 'SoapFault') ? 'A conexão com Detran-SE' : 'O Oráculo';
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = $tipoErro.' apresentou uma falha técnica inesperada.';
            $retornoDetran['falha_tecnica'] = true;
        }
        return $retornoDetran;
    }

    public function DadosCertificadoSE($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('SE');
        $sql = "SELECT
            m.idmatricula, m.data_matricula, m.data_conclusao, p.documento, e.detran_codigo, c.idcurso,
            (
                SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_certificado' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_certificado' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            m.detran_certificado
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        WHERE
            e.detran_codigo IS NOT NULL
            AND cw.cancelada = 'N'
            AND m.detran_situacao = 'LI'
            AND m.detran_creditos = 'S'
            AND {$info['cursos']}
            AND e.idestado = {$info['idestado']}
            AND m.ativo = 'S'
            AND cw.fim = 'S'
            AND m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoSE($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_certificado', 'solicitou');
        define('INTERFACE_DETRAN_SE_CERTIFICADO', retornarInterface('detran_se_certificado')['id']);

        ini_set('soap.wsdl_cache_enabled', 0);

        try {
            $dados['detran_tipo_aula'] = $this->detran_tipo_aula['SE'][$dados['idcurso']];

            $dataInicio = (new DateTime($dados['data_matricula']))->format('Ymd');
            $dataFim = (new DateTime($dados['data_conclusao']))->format('Ymd');
            $codCursoEspecializado = str_pad('', 2, ' ');

            $stringEnvio = 427 . 'CFC' . $dados['detran_codigo'] .
                $this->config['detran']['SE']['registro_instrutor'] . $dados['documento'] . $dataInicio .
                $dataFim . $dados['detran_tipo_aula']['CODIGO'] . $codCursoEspecializado;

            $dadosSOAP = [
                'executaTransacao' => [
                    'pUsuario' => $this->config['detran']['SE']['pUsuario'],
                    'pSenha' => $this->config['detran']['SE']['pSenha'],
                    'pAmbiente' => $this->config['detran']['SE']['pAmbiente'],
                    'pMensagem' => $stringEnvio,
                ]
            ];

            $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_SE_CERTIFICADO, 'E', $dadosSOAP);
            $opcoesSOAP = array(
                'trace' => 1,
                'connection_timeout' => 15,
                ['exceptions' => true]
            );
            $soapCliente = new SoapClient($this->config['detran']['SE']['urlWsl'], $opcoesSOAP);
            $options = ['location' => $this->config['detran']['SE']['urlSoap']];
            $respostaSoap = $soapCliente->__soapCall('executaTransacao', $dadosSOAP, $options);

            $retornoDetran['erro'] = false;
            $retornoDetran['mensagem'] = json_encode($respostaSoap->executaTransacaoResult);

            $this->executaSql('BEGIN');

            if (substr($respostaSoap->executaTransacaoResult, 0, 3) == 999) {
                $sql = 'UPDATE matriculas SET detran_certificado = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $this->executaSql($sql);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
                $this->transacoes->finalizaTransacao(null, 2, null, $respostaSoap->executaTransacaoResult);
            } else {
                $this->transacoes->set('json', json_encode($respostaSoap->executaTransacaoResult));
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set('json', NULL);
                $retornoDetran['erro'] = true;
            }

            $this->salvarLogDetran(
                427,
                $dados['idmatricula'],
                $respostaSoap->executaTransacaoResult,
                $stringEnvio
            );

            $this->executaSql('COMMIT');
        } catch (Exception $excecao) {
            $this->transacoes->finalizaTransacao(null, 3, json_encode(
                    [
                        'codigo' => $excecao->getCode(),
                        'mensagem' => $excecao->getMessage()
                    ])
            );
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'detran_nao_respondeu', null, null, null);
            }
            $tipoErro = (get_class($excecao) == 'SoapFault') ? 'A conexão com Detran-SE' : 'O Oráculo';
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = $tipoErro.' apresentou uma falha técnica inesperada.';
            $retornoDetran['falha_tecnica'] = true;
        }
        return $retornoDetran;
    }

    public function DadosCertificadoAL($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('AL');
        $sql = "SELECT
            m.idmatricula,
            m.idsituacao,
            m.data_matricula,
            m.data_conclusao,
            p.documento,
            p.idpessoa,
            e.detran_codigo,
            c.idcurso,
            c.carga_horaria_total,
            m.cod_ticket,
            m.renach,
            m.idoferta,
            m.idcurso,
            m.idescola,
            m.renach,
            e.idcidade,
            p.cnh,
            frdm.idfolha_matricula,
            (
                SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh
                WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'detran_situacao' AND mh.para = 'LI'
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso,
            m.detran_certificado
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
            INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo='S' AND frdm.cancelado <> 'S')
        WHERE
            e.detran_codigo IS NOT NULL AND
            cw.cancelada = 'N' AND
             m.detran_situacao = 'LI' AND
            {$info['cursos']} AND
            e.idestado = {$info['idestado']} AND
            m.ativo = 'S' AND
            m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoAL($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_certificado', 'solicitou');
        define('INTERFACE_DETRAN_AL_CERTIFICADO', retornarInterface('detran_al_certificado')['id']);

        try {
            $opts = array(
                'ssl' => array(
                    'ciphers' => 'RC4-SHA',
                    'verify_peer' => false,
                    'verify_peer_name' => false
                )
            );

            $params = array(
                'encoding' => 'UTF-8',
                'verifypeer' => false,
                'verifyhost' => false,
                'soap_version' => SOAP_1_1,
                'trace' => 1,
                'exceptions' => 1,
                'connection_timeout' => 180,
                'stream_context' => stream_context_create($opts)
            );

            $conexaoSOAP = new SoapClient($this->config['detran']['AL']['urlWslConclusao'], $params);
            $dataInicio = new DateTime($dados['data_inicio_curso']);
            $dataConclusao = new DateTime($dados['data_conclusao']);
            $cargaHoraria = !empty($dados['carga_horaria_total']) ? $dados['carga_horaria_total'] : 30;

            $dadosSOAP = [
                'CFCNW045' => [
                    'ENTRADA' => [
                        'CPF-E' => str_pad($dados['documento'], 11),
                        'CERTIFICADO-E' => '',
                        'TIPO-CURSO-E' => 'T',
                        'CATEGORIA-CURSO-E' => '',
                        'DATA-INICIO-E' => $dataInicio->format('d/m/Y'),
                        'DATA-FINAL-E' => $dataConclusao->format('d/m/Y'),
                        'CARGA-HORARIA-E' => $cargaHoraria,
                        'CPF-INSTRUTOR-E' => $this->config['detran']['AL']['cpf_instrutor'],
                        'CODIGO-CURSO-E' => $this->detran_tipo_aula['AL'][$dados['idcurso']],
                        'CFC-E' => str_pad($dados['detran_codigo'], 11),
                        'CNPJ-INSTITUICAO-ENSINO-E' => $this->config['detran']['AL']['cnpjInst'],
                        'MATRICULA-SISTEMA-E' => $this->config['detran']['AL']['matricula'],
                    ]
                ]
            ];
            $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_AL_CERTIFICADO, 'E', $dadosSOAP);
            $respostaSoap = $conexaoSOAP->__soapCall('CFCNW045', $dadosSOAP);
            $stringEnvio = json_encode($dadosSOAP);
            $retorno = json_encode(
                [
                    'codigo' => $respostaSoap->ENVIO_CURSO->RETORNO->CODIGO,
                    'mensagem' => $respostaSoap->ENVIO_CURSO->RETORNO->MENSAGEM
                ],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

            $retornoDetran['erro'] = false;
            $retornoDetran['mensagem'] = $respostaSoap->ENVIO_CURSO->RETORNO->MENSAGEM;

            $this->executaSql('BEGIN');

            if ($respostaSoap->ENVIO_CURSO->RETORNO->CODIGO == 0) {
                $situacaoConcluida = $this->matriculaObj->retornarSituacaoConcluida();
                $update = "UPDATE matriculas
                    SET detran_certificado = 'S',
                        idsituacao = {$situacaoConcluida['idsituacao']}
                    WHERE idmatricula = {$dados['idmatricula']}";
                $this->matriculaObj->executaSql($update);
                $this->transacoes->finalizaTransacao(null, 2, null, $retorno);
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'situacao', 'modificou', $dados['idsituacao'], $situacaoConcluida['idsituacao'], null);
                $this->transacoes->finalizaTransacao(null, 2, null, $retorno);
            } else {
                $this->transacoes->set('json', $retorno);
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set('json', NULL);
                $retornoDetran['erro'] = true;
            }

            $this->salvarLogDetran(427, $dados['idmatricula'], $retorno, $stringEnvio);
            $this->executaSql('COMMIT');

        } catch (Exception $ex) {
            $this->transacoes->finalizaTransacao(null, 3, json_encode(
                    ['codigo' => $ex->getCode(), 'mensagem' => $ex->getMessage()])
            );

            $tipoErro = (get_class($ex) == 'SoapFault') ? 'A conexão com Detran-AL' : 'O Oráculo';
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = $tipoErro.' apresentou uma falha técnica inesperada.';
            $retornoDetran['falha_tecnica'] = true;
        }
        return $retornoDetran;
    }

    public function DadosCertificadoES($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('ES');
        $sql = "SELECT
            m.idmatricula,
            m.idsituacao,
            m.data_matricula,
            m.data_conclusao,
            p.documento,
            p.idpessoa,
            e.detran_codigo,
            c.idcurso,
            c.carga_horaria_total,
            m.cod_ticket,
            m.idoferta,
            m.idcurso,
            m.idescola,
            m.renach,
            e.idcidade,
            p.cnh,
            frdm.idfolha_matricula,
            (
                SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh
                WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'detran_situacao' AND mh.para = 'LI'
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso,
            m.detran_certificado
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
            INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo='S' AND frdm.cancelado <> 'S')
        WHERE
            e.detran_codigo IS NOT NULL AND
            cw.cancelada = 'N' AND
            m.detran_situacao = 'LI' AND
            {$info['cursos']} AND
            e.idestado = {$info['idestado']} AND
            m.ativo = 'S' AND
            m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoES($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_certificado', 'solicitou');
        define('INTERFACE_DETRAN_ES_CERTIFICADO', retornarInterface('detran_es_certificado')['id']);

        try {
            $opcoesSOAP = array(
                'trace' => 1,
                'exceptions' => true
            );

            $auth = array(
                'ChaveEAD' => $this->config['detran']['ES']['chave']
            );

            $conexaoSOAP = new SoapClient($this->config['detran']['ES']['urlWsl'], $opcoesSOAP);
            $header = new SoapHeader('http://tempuri.org/', 'EADSoapHeader', $auth);
            $conexaoSOAP->__setSoapHeaders($header);

            $dataInicio = new DateTime($dados['data_inicio_curso']);
            $dataConclusao = new DateTime($dados['data_conclusao']);
            $cargaHoraria = !empty($dados['carga_horaria_total']) ? $dados['carga_horaria_total'] : 30;

            $dadosSOAP = [
                'EnviarDadosCurso' => [
                    'login' => $this->config['detran']['ES']['login'],
                    'numRegistroCNHAluno' => str_pad($dados['cnh'], 11),
                    'codigoCurso' => $this->detran_tipo_aula['ES'][$dados['idcurso']],
                    'numeroCertificado' => $dados['idfolha_matricula'],
                    'dataInicioCurso' => $dataInicio->format('Y-m-d'),
                    'dataFimCurso' => $dataConclusao->format('Y-m-d'),
                    'cargaHoraria' => $cargaHoraria,
                    'cpfInstrutor' => $this->config['detran']['ES']['cpf_instrutor']
                ]
            ];

            $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_ES_CERTIFICADO, 'E', $dadosSOAP);
            // Enviar dados do curso
            $respostaSoap = $conexaoSOAP->__soapCall('EnviarDadosCurso', $dadosSOAP);
            $stringEnvio = json_encode($dadosSOAP);
            $retornoSOAP = json_encode(['codigo' => $respostaSoap->EnviarDadosCursoResult->codRetorno, 'mensagem' => $respostaSoap->EnviarDadosCursoResult->descricao], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

            $retornoDetran['erro'] = false;
            $retornoDetran['mensagem'] = $respostaSoap->EnviarDadosCursoResult->descricao;

            $this->executaSql('BEGIN');

            if ($respostaSoap->EnviarDadosCursoResult->codRetorno == 1) {
                $situacaoConcluida = $this->matriculaObj->retornarSituacaoConcluida();
                $update = "UPDATE matriculas
                    SET detran_certificado = 'S',
                        idsituacao = " . $situacaoConcluida['idsituacao'] . "
                    WHERE idmatricula = " . $dados['idmatricula'];
                $this->matriculaObj->executaSql($update);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'situacao', 'modificou', $dados['idsituacao'], $situacaoConcluida['idsituacao'], null);
                $this->transacoes->set('json', $retornoSOAP);
                $this->transacoes->finalizaTransacao(null, 2);
                $this->transacoes->set('json', null);
            } else {
                $this->transacoes->set('json', $retornoSOAP);
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set('json', null);
                $retornoDetran['erro'] = true;
            }

            $this->salvarLogDetran(427, $dados['idmatricula'], $retornoSOAP, $stringEnvio);
            $this->executaSql('COMMIT');

        } catch (Exception $ex) {
            $this->transacoes->set('json', json_encode(['codigo' => $ex->getCode(),'mensagem' => $ex->getMessage()]));
            $this->transacoes->finalizaTransacao(null, 3);
            $this->transacoes->set('json', null);

            $tipoErro = (get_class($ex) == 'SoapFault') ? 'A conexão com Detran-ES' : 'O Oráculo';
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = $tipoErro.' apresentou uma falha técnica inesperada.';
            $retornoDetran['falha_tecnica'] = true;
        }
        return $retornoDetran;
    }

    public function DadosCertificadoMA($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('MA');
        $sql = "SELECT
            m.idmatricula,
            m.idsituacao,
            m.data_matricula,
            m.data_conclusao,
            p.documento,
            p.idpessoa,
            e.detran_codigo,
            c.idcurso,
            c.carga_horaria_total,
            m.cod_ticket,
            m.renach,
            m.idoferta,
            m.idcurso,
            m.idescola,
            e.idcidade,
            (
                SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh
                INNER JOIN matriculas_workflow mw ON (mw.idsituacao = mh.para)
                WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'situacao' AND mw.ativa = 'S'
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso,
            m.detran_certificado
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
            INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo='S' AND frdm.cancelado <> 'S')
        WHERE
            e.detran_codigo IS NOT NULL
            AND cw.cancelada = 'N'
            AND m.detran_situacao = 'LI'
            AND {$info['cursos']}
            AND e.idestado = {$info['idestado']}
            AND m.ativo = 'S'
            AND m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoMA($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_certificado', 'solicitou');
        define('INTERFACE_DETRAN_MA_CERTIFICADO', retornarInterface('detran_ma_certificado')['id']);

        $cidades = array();
        require dirname(__DIR__) . '/includes/cidades.maranhao.php';
        try {
            $dataInicio = new DateTime($dados['data_inicio_curso']);
            $dataConclusao = new DateTime($dados['data_conclusao']);
            $cargaHoraria = !empty($dados['carga_horaria_total']) ? $dados['carga_horaria_total'] : 30;

            $dadosSOAP = [
                'NJXHNEAD' => [
                    'WSIN_CPF_ALUNO' => $dados['documento'],
                    'WSIN_CODIGO_CURSO' => $this->detran_tipo_aula['MA'][$dados['idcurso']],
                    'WSIN_DATA_INICIO' => $dataInicio->format('Ymd'),
                    'WSIN_DATA_FIM' => $dataConclusao->format('Ymd'),
                    'WSIN_CERTIFICADO' => $dados['detran_codigo'],
                    'WSIN_CATEGORIA' => '', // deixar vazio
                    'WSIN_CARGA_HORARIA' => $cargaHoraria,
                    'WSIN_CODIGO_MUNICIPIO' => $cidades[$dados['idcidade']]['idmaranhao'],
                    'WSIN_DATA_VALIDADE' => 0,
                    'WSIN_CPF_INSTRUTOR' => $this->config['detran']['MA']['cpf_instrutor'],
                    'WSIN_CNPJ_ENTIDADE' => $this->config['detran']['MA']['cnpj_entidade'],
                    'WSIN_USUARIO' => $this->config['detran']['MA']['usuario'],
                    'WSIN_SENHA' => $this->config['detran']['MA']['senha']
                ]
            ];
            $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_MA_CERTIFICADO, 'E', $dadosSOAP);
            $opcoesSOAP = array(
                'trace' => 1,
                'exceptions' => true
            );
            $soapCliente = new SoapClient($this->config['detran']['MA']['urlWsl'], $opcoesSOAP);
            $options = ['location' => $this->config['detran']['MA']['urlWsl']];
            $respostaSoap = $soapCliente->__soapCall('NJXHNEAD', $dadosSOAP, $options);


            if ($respostaSoap->WSOUT_EXEC != 1) {
                $this->executaSql('BEGIN');
                if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                    $this->matriculaObj->set('id', $dados['idmatricula'])
                        ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
                }
                $this->salvarLogDetran(427, $dados['idmatricula'], json_encode($respostaSoap),
                    json_encode($dadosSOAP));
                $this->transacoes->finalizaTransacao(null, 3, null, json_encode($respostaSoap));
                $this->executaSql('COMMIT');
                $retornoDetran['erro'] = true;
                $retornoDetran['mensagem'] = !empty($respostaSoap->WSOUT_MSG) ? $respostaSoap->WSOUT_MSG : '(Retorno vazio)';
                return $retornoDetran;
            }

            $retornoDetran['erro'] = false;
            $retornoDetran['mensagem'] = $respostaSoap->WSOUT_MSG;

            $this->executaSql('BEGIN');
            if ($respostaSoap->WSOUT_EXEC == 1) {
                $situacaoConcluida = $this->matriculaObj->retornarSituacaoConcluida();
                $update = "UPDATE matriculas
                    SET detran_certificado = 'S',
                        idsituacao = " . $situacaoConcluida['idsituacao'] . "
                    WHERE idmatricula = " . $dados['idmatricula'];
                $this->executaSql($update);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'situacao', 'modificou', $dados['idsituacao'], $situacaoConcluida['idsituacao'], null);
                $this->transacoes->finalizaTransacao(null, 2, null, $respostaSoap);
            } else {
                $this->transacoes->set('json', json_encode($respostaSoap));
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set('json', NULL);
                $retornoDetran['erro'] = true;
            }
            $this->salvarLogDetran(427, $dados['idmatricula'], json_encode($respostaSoap),
                json_encode($dadosSOAP));
            $this->executaSql('COMMIT');

        } catch (Exception $ex) {
            $this->transacoes->finalizaTransacao(null, 3, json_encode([
                'codigo' => $ex->getCode(),
                'mensagem' => $ex->getMessage()
            ]));
            $tipoErro = (get_class($ex) == 'SoapFault') ? 'A conexão com Detran-MA' : 'O Oráculo';
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = $tipoErro.' apresentou uma falha técnica inesperada.';
            $retornoDetran['falha_tecnica'] = true;
        }
        return $retornoDetran;
    }

    public function DadosCertificadoMS($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('MS');
        $sql = "SELECT
            m.idmatricula,
            m.idsituacao,
            m.data_matricula,
            m.data_conclusao,
            p.documento,
            p.idpessoa,
            p.nome,
            p.data_nasc,
            p.rg,
            p.rg_orgao_emissor,
            p.idcidade,
            e.detran_codigo,
            c.idcurso,
            c.carga_horaria_total,
            m.cod_ticket,
            m.renach,
            m.idoferta,
            m.idcurso,
            m.idescola,
            (
                SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh
                INNER JOIN matriculas_workflow mw ON (mw.idsituacao = mh.para)
                WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'situacao' AND mw.ativa = 'S'
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso,
            m.detran_certificado
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN cidades ci ON (p.idcidade = ci.idcidade)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
            INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo='S' AND frdm.cancelado <> 'S')
        WHERE
            e.detran_codigo IS NOT NULL
            AND cw.cancelada = 'N'
            AND m.detran_situacao = 'LI'
            AND {$info['cursos']}
            AND e.idestado = {$info['idestado']}
            AND m.ativo = 'S'
            AND m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoMS($dados)
    {
        $cidades = array();
        require dirname(__DIR__) . '/includes/cidades.matogrossodosul.php';
        $this->salvarAcao($dados['idmatricula'], 'detran_certificado', 'solicitou');
        define('INTERFACE_DETRAN_MS_CERTIFICADO', retornarInterface('detran_ms_certificado')['id']);

        try {
            $dataInicio = (new DateTime($dados['data_inicio_curso']))->format('Ymd');
            $dataNasc = (new DateTime($dados['data_nasc']))->format('Ymd');
            $anoAtual = (new DateTime())->format('Y');
            $diaAtual = (new DateTime())->format('d');

            $_POST = 'cpf=' . $dados['documento'] .
                '&nome=' . urlencode($dados['nome']) .
                '&dtNasc=' . $dataNasc .
                '&rg=' . urlencode($dados['rg'] . ' ' . $dados['rg_orgao_emissor']) .
                '&cdMun=' . str_pad($cidades[$dados['idcidade']]['idmunicipio'], 5, '0', STR_PAD_LEFT) .
                '&dtIni=' . $dataInicio .
                '&cnpjInst=' . $this->config['detran']['MS']['cnpjInst'] .
                '&codSeg=' . (intval($anoAtual) + intval($diaAtual)) . $this->config['detran']['MS']['cnpjInst'];

            $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_MS_CERTIFICADO, 'E', $_POST);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->config['detran']['MS']['urlConclusao'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $_POST,
                CURLOPT_INTERFACE => $this->config['detran']['MS']['ip'],
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $retorno = ($err) ? "cURL Error #:" . $err : $response;

            $xml = simplexml_load_string($retorno);

            $codigoRetorno = (string)$xml->registra->condutor->codRet;
            $mensagem = (string)$xml->registra->condutor->msg;

            $retorno = json_encode(['codigo' => $codigoRetorno, 'mensagem' => $mensagem], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

            if ($codigoRetorno != '000') {
                $this->executaSql('BEGIN');
                if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                    $this->matriculaObj->set('id', $dados['idmatricula'])
                        ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
                }
                $this->salvarLogDetran(427, $dados['idmatricula'], $codigoRetorno . ' - ' . $mensagem,
                    $_POST);
                $this->executaSql('COMMIT');
                $this->transacoes->set("json", json_encode($err ? $err : $retorno));
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set("json", null);
                $retornoDetran['erro'] = true;
                $retornoDetran['mensagem'] = !empty($mensagem) ? $mensagem : '(Retorno vazio)';
                return $retornoDetran;
            }

            $retornoDetran['erro'] = false;
            $retornoDetran['mensagem'] = $mensagem;

            $this->executaSql('BEGIN');
            if ($codigoRetorno == '000') {
                $situacaoConcluida = $this->matriculaObj->retornarSituacaoConcluida();
                $update = "UPDATE matriculas
                    SET detran_certificado = 'S',
                        idsituacao = " . $situacaoConcluida['idsituacao'] . "
                    WHERE idmatricula = " . $dados['idmatricula'];
                $this->executaSql($update);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'situacao', 'modificou', $dados['idsituacao'], $situacaoConcluida['idsituacao'], null);
                $this->transacoes->set("json", $retorno);
                $this->transacoes->finalizaTransacao(null, 2);
                $this->transacoes->set("json", null);
            } else {
                $this->transacoes->set("json", json_encode($err ? $err : $retorno));
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set("json", null);
                $retornoDetran['erro'] = true;
            }
            $this->salvarLogDetran(427, $dados['idmatricula'], $codigoRetorno . ' - ' . $mensagem,
                $_POST);
            $this->executaSql('COMMIT');

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return $retornoDetran;
    }

    public function DadosCertificadoMT($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('MT');
        $sql = "SELECT
            m.idmatricula,
            m.idsituacao,
            m.data_matricula,
            m.data_conclusao,
            p.documento,
            p.idpessoa,
            p.categoria,
            e.detran_codigo,
            c.idcurso,
            c.carga_horaria_total,
            m.renach,
            m.idcurso,
            frdm.idfolha_matricula,
            e.idcidade,
            (
                SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh
                INNER JOIN matriculas_workflow mw ON (mw.idsituacao = mh.para)
                WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'situacao' AND mw.ativa = 'S'
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso,
            m.detran_certificado
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
            INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo='S' AND frdm.cancelado <> 'S')
        WHERE
            e.detran_codigo IS NOT NULL
            AND cw.cancelada = 'N'
            AND m.detran_situacao = 'LI'
            AND {$info['cursos']}
            AND e.idestado = {$info['idestado']}
            AND m.ativo = 'S'
            AND m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoMT($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_certificado', 'solicitou');
        define('INTERFACE_DETRAN_MT_CERTIFICADO', retornarInterface('detran_mt_certificado')['id']);

        $cidades = array();
        try {
            require dirname(__DIR__) . '/includes/cidades.matogrosso.php';
            $cargaHoraria = !empty($dados['carga_horaria_total']) ? $dados['carga_horaria_total'] : 30;

            $dadosXML = '
            <ProcessaAula>
                <Integrador>' . $this->config['detran']['MT']['integrador'] . '</Integrador>
                <ChaveUnica>' . $this->config['detran']['MT']['chave'] . '</ChaveUnica>
                <CPF>' . $dados['documento'] . '</CPF>
                <NumeroRenach>' . $dados['renach'] . '</NumeroRenach>
                <CategoriaPretendida>' . $dados['categoria'] . '</CategoriaPretendida>
                <CodigoCurso>' . $this->detran_tipo_aula['MT'][$dados['idcurso']] . '</CodigoCurso>
                <ModalidadeCurso>2</ModalidadeCurso>
                <NumeroCertificado>' . $dados['idfolha_matricula'] . '</NumeroCertificado>
                <DataInicio>' . (new DateTime($dados['data_inicio_curso']))->format('Ymd') . '</DataInicio>
                <DataFim>' . (new DateTime($dados['data_conclusao']))->format('Ymd') . '</DataFim>
                <DataValidade>' . (new DateTime($dados['data_conclusao']))->modify("+5 year")->format("Ymd") . '</DataValidade>
                <CargaHoraria>' . $cargaHoraria . '</CargaHoraria>
                <CNPJEntidade>' . $this->config['detran']['MT']['cnpj_entidade'] . '</CNPJEntidade>
                <CPFProfissional>' . $this->config['detran']['MT']['cpf_instrutor'] . '</CPFProfissional>
                <CodigoMunicCurso>' . $cidades[$dados['idcidade']]['idmunicipio'] . '</CodigoMunicCurso>
                <UFMunicipio>MT</UFMunicipio>
            </ProcessaAula>
        ';

            $any = new SoapVar($dadosXML, XSD_ANYXML);
            $dadosSOAP = [
                'ProcessaAula' => [
                    'xmlEntrada' => [
                        'any' => $any
                    ]
                ]
            ];
            $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_MT_CERTIFICADO, 'E', $dadosSOAP);

            $opcoesSOAP = array(
                'trace' => 1,
                'exceptions' => true
            );

            $conexaoSOAP = new SoapClient(
                $this->config['detran']['MT']['urlWsl'],
                $opcoesSOAP
            );
            $result = $conexaoSOAP->__SoapCall('ProcessaAula', $dadosSOAP);
            $ProcessaAulaResult = json_decode(json_encode(simplexml_load_string($result->ProcessaAulaResult->any)), TRUE)['NewDataSet']['Table'];
            $stringEnvio = json_encode($dadosSOAP);
            $retorno = json_encode(['codigo' => $ProcessaAulaResult['CodigoRetorno'], 'mensagem' => $ProcessaAulaResult['MensagemRetorno']]);

            if ($ProcessaAulaResult['CodigoRetorno'] == 1) {
                $this->executaSql('BEGIN');
                if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                    $this->matriculaObj->set('id', $dados['idmatricula'])
                        ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
                }
                $this->salvarLogDetran(427, $dados['idmatricula'], $retorno, $stringEnvio);
                $this->executaSql('COMMIT');
                $this->transacoes->set('json', $retorno);
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set('json', NULL);
                $retornoDetran['erro'] = true;
                $retornoDetran['mensagem'] = !empty($ProcessaAulaResult['MensagemRetorno']) ? $ProcessaAulaResult['MensagemRetorno'] : '(Retorno vazio)';
                return $retornoDetran;
            }

            $retornoDetran['erro'] = false;
            $retornoDetran['mensagem'] = $ProcessaAulaResult['MensagemRetorno'];

            $this->executaSql('BEGIN');
            if ($ProcessaAulaResult['CodigoRetorno'] == 0) {
                $situacaoConcluida = $this->matriculaObj->retornarSituacaoConcluida();
                $update = "UPDATE matriculas
                    SET detran_certificado = 'S',
                        idsituacao = " . $situacaoConcluida['idsituacao'] . "
                    WHERE idmatricula = " . $dados['idmatricula'];
                $this->executaSql($update);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'situacao', 'modificou', $dados['idsituacao'], $situacaoConcluida['idsituacao'], null);
                $this->transacoes->finalizaTransacao(null, 2, null, $retorno);
            } else {
                $this->transacoes->set('json', $retorno);
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set('json', NULL);
                $retornoDetran['erro'] = true;
            }
            $this->salvarLogDetran(427, $dados['idmatricula'], $retorno, $stringEnvio);
            $this->executaSql('COMMIT');

        } catch (Exception $ex) {
            $this->transacoes->finalizaTransacao(
                null,
                3,
                json_encode(['codigo' => $ex->getCode(), 'mensagem' => addslashes($ex->getMessage())])
            );
            $tipoErro = (get_class($ex) == 'SoapFault') ? 'A conexão com Detran-MT' : 'O Oráculo';
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = $tipoErro.' apresentou uma falha técnica inesperada.';
            $retornoDetran['falha_tecnica'] = true;
        }
        return $retornoDetran;
    }

    public function DadosCreditoPE($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $return = false;
        $info = $this->CursosIDestado('PE');
        $disciplinas = implode(',', array_keys($this->detran_codigo_materia['PE']));
        $situacaoEmCurso = $this->matriculaObj->retornarSituacaoAtiva();
        $situacaoConcluido = $this->matriculaObj->retornarSituacaoConcluido();
        $sql = "SELECT
            m.idmatricula, p.documento, p.data_nasc, e.detran_codigo, o.idoferta, c.idcurso, e.idescola,
            (
                SELECT
                    COUNT(d.iddisciplina)
                FROM
                    ofertas_cursos_escolas oce
                    INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = oce.idoferta AND oca.idcurriculo = oce.idcurriculo AND oca.ativo = 'S' AND oca.idava IS NOT NULL)
                    INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = oca.idcurriculo AND cb.ativo = 'S')
                    INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = 'S' )
                    INNER JOIN disciplinas d ON (d.iddisciplina = cbd.iddisciplina AND d.iddisciplina = oca.iddisciplina AND d.ativo = 'S')
                WHERE
                    oce.idoferta = o.idoferta AND
                    oce.idcurso = c.idcurso AND
                    oce.idescola = e.idescola AND
                    d.iddisciplina IN ({$disciplinas})
            ) AS total_disciplinas,
            (
                SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_creditos' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_creditos' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh
                WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'situacao' AND mh.para = {$situacaoEmCurso['idsituacao']}
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh
                WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'situacao' AND mh.para = {$situacaoConcluido['idsituacao']}
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_conclusao,
            m.detran_creditos
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        WHERE
            {$info['cursos']} AND
            e.idestado = {$info['idestado']} AND
            m.ativo = 'S' AND
            m.detran_situacao = 'LI' AND
            e.detran_codigo IS NOT NULL AND
            cw.fim = 'S' AND
            m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CreditosPE($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_creditos', 'solicitou');
        define('INTERFACE_DETRAN_PE_CREDITOS', retornarInterface('detran_pe_creditos')['id']);

        $requisicoes = array(
            $GLOBALS['detran_tipo_aula']['PE'][$dados['idcurso']]
        );
        if (!empty($GLOBALS['detran_tipo_aula_motociclista']['PE'][$dados['idcurso']]))
            array_push ($requisicoes, $GLOBALS['detran_tipo_aula_motociclista']['PE'][$dados['idcurso']]); //Olhe a regra no card AWDE-1241 para entender o porquê disso.

        foreach ($requisicoes as $aula) {
        $arrayEnvio = [
            'Cpf' => $dados['documento'],
            'Nascimento' => $dados['data_nasc'],
            'Curso' => $aula['codigo'],
            'Modulo' => $aula['modulo'],
            'Cnpj' => $this->config['detran']['PE']['registro_empresa'],
            'CpfInstrutor' => $this->config['detran']['PE']['registro_instrutor'],
            'Inicio' => (new DateTime($dados['data_inicio_curso']))->format('Y-m-d'),
            'FimModulo' => (new DateTime($dados['data_conclusao']))->format('Y-m-d'),
        ];
        $_POST = json_encode($arrayEnvio);

        $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_PE_CREDITOS, 'E', $arrayEnvio);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['detran']['PE']['urlJSON'] . '/CursoDistanciaAtualizar');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application\json',
            )
        );
        $chResult = curl_exec($ch);
        curl_close($ch);

        if ($chResult === false) {
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = '(Retorno vazio)';
            return $retornoDetran;
        }

        $retorno = json_decode(json_decode($chResult, true)['CursoDistanciaAtualizarResult'], true);

        $this->executaSql('BEGIN');
        $mensagem = json_encode(['codigo' => $retorno[0]['nErro'], 'mensagem' => $retorno[0]['sMsg']]);

        $retornoDetran['erro'] = false;
        $retornoDetran['mensagem'] = $retorno[0]['sMsg'];

        switch ($retorno[0]['nErro']) {
            case 0:
                $sql = 'UPDATE matriculas SET detran_finalizar = "S", detran_creditos = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $this->executaSql($sql);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_finalizar', 'modificou', 'N', 'S', null);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_creditos', 'modificou', 'N', 'S', null);
                $this->transacoes->set("json", json_encode($retorno));
                $this->transacoes->finalizaTransacao(null, 2);
                $this->transacoes->set("json", null);
                break;
            case 1:
                $this->transacoes->set("json", $mensagem);
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set("json", null);
                $retornoDetran['erro'] = true;
        }

        $this->salvarLogDetran(424, $dados['idmatricula'], $mensagem, $_POST);
        $this->executaSql('COMMIT');
        }
        return $retornoDetran;
    }

    public function DadosCertificadoPE($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('PE');
        $situacaoEmCurso = $this->matriculaObj->retornarSituacaoAtiva();
        $situacaoConcluido = $this->matriculaObj->retornarSituacaoConcluido();
        $disciplinas = implode(',', array_keys($this->detran_codigo_materia['PE']));
        $sql = "SELECT
            m.idmatricula, m.data_matricula, p.documento, p.data_nasc, e.detran_codigo, c.idcurso,
            frdm.idfolha_matricula,m.idoferta, m.idcurso, m.idescola, e.documento as documentoCfc,
            (
                SELECT
                    oca.idava
                FROM
                    ofertas_cursos_escolas oce
                    INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = oce.idoferta AND
                                                               oca.idcurriculo = oce.idcurriculo AND
                                                               oca.ativo = 'S' AND oca.idava IS NOT NULL)
                    INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = oca.idcurriculo AND cb.ativo = 'S')
                    INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = 'S' )
                    INNER JOIN disciplinas d ON (d.iddisciplina = cbd.iddisciplina AND d.iddisciplina = oca.iddisciplina
                                                     AND d.ativo = 'S')
                WHERE
                    oce.idoferta = o.idoferta AND
                    oce.idcurso = c.idcurso AND
                    oce.idescola = e.idescola AND
                    d.iddisciplina IN ({$disciplinas})
                LIMIT 1
            ) AS idava,
            (
                SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_certificado' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_certificado' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            (
                SELECT
                    mh.data_cad
                FROM matriculas_historicos mh
                WHERE
                    mh.idmatricula = m.idmatricula AND
                    mh.tipo = 'situacao' AND mh.para = {$situacaoEmCurso['idsituacao']}
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso,
            (
                SELECT
                    mh.data_cad
                FROM matriculas_historicos mh
                WHERE
                    mh.idmatricula = m.idmatricula AND mh.tipo = 'situacao' AND
                    mh.para = {$situacaoConcluido['idsituacao']}
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_conclusao,
            m.detran_certificado
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
            INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo='S' AND frdm.cancelado <> 'S')
        WHERE
            e.detran_codigo IS NOT NULL
            AND cw.cancelada = 'N'
            AND m.detran_situacao = 'LI'
            AND m.detran_creditos = 'S'
            AND cw.fim = 'S'
            AND {$info['cursos']}
            AND e.idestado = {$info['idestado']}
            AND m.ativo = 'S'
            AND m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoPE($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_certificado', 'solicitou');
        define('INTERFACE_DETRAN_PE_CERTIFICADO', retornarInterface('detran_pe_certificado')['id']);

        $this->matriculaObj->set("id", $dados['idmatricula']);
        $this->matriculaObj->set("matricula", $dados);
        $matricula["curriculo"] = $this->matriculaObj->RetornarCurriculo();
        $matricula["disciplinas"] = $this->matriculaObj->RetornarDisciplinas($matricula["curriculo"]['media']);

        $quantDisciplinas = 0;
        $notas = 0;
        foreach ($matricula["disciplinas"] as $disciplina) {
            if ($disciplina['ignorar_historico'] == "S"
                || $disciplina['contabilizar_media'] == "N") {
                continue;
            }
            $quantDisciplinas++;
            $notas += $disciplina['situacao']['valor'];
        }
        $mediaFinal = (float)($notas / $quantDisciplinas);
        $id_cursos = [57, 55, 59, 58];
        $aula = (!empty($GLOBALS['detran_tipo_aula_motociclista']['PE'][$dados['idcurso']])) ? $GLOBALS['detran_tipo_aula_motociclista']['PE'][$dados['idcurso']] : $GLOBALS['detran_tipo_aula']['PE'][$dados['idcurso']]; //Olhe a regra no card AWDE-1241 para entender o porquê disso.
        $arrayEnvio = [
            'Cpf' => $dados['documento'],
            'Nascimento' => $dados['data_nasc'],
            'Curso' => $aula['codigo'],
            'Modulo' => $aula['modulo'],
            'Cnpj' => $this->config['detran']['PE']['registro_empresa'],
            'CpfInstrutor' => $this->config['detran']['PE']['registro_instrutor'],
            'Inicio' => (new DateTime($dados['data_inicio_curso']))->format('Y-m-d'),
            'Conclusao' => (new DateTime($dados['data_conclusao']))->format('Y-m-d'),
            'Validade' => (new DateTime($dados['data_conclusao']))->modify("+5 year")->format("Y-m-d"), //+5 anos
            'Nota' => (float)number_format($mediaFinal, 2, '.', ''),
            'Certificado' => $dados["idfolha_matricula"],
            'CnpjCfc' => in_array($aula['codigo'], $id_cursos) ? $dados['documentoCfc'] : 0
        ];

        $_POST = json_encode($arrayEnvio);

        $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_PE_CERTIFICADO, 'E', $arrayEnvio);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['detran']['PE']['urlJSON'] . '/CursoDistanciaConcluir');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application\json',
            )
        );
        $chResult = curl_exec($ch);
        curl_close($ch);

        if ($chResult === false) {
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = '(Retorno vazio)';
            return $retornoDetran;
        }

        $retorno = json_decode(json_decode($chResult, true)['CursoDistanciaConcluirResult'], true);

        $this->executaSql('BEGIN');
        $mensagem = json_encode(['codigo' => $retorno[0]['nErro'], 'mensagem' => $retorno[0]['sMsg'], 'retorno' => $retorno[0]['sCertificadoRetorno']]);

        $retornoDetran['erro'] = false;
        $retornoDetran['mensagem'] = $retorno[0]['sMsg'];
        switch ($retorno[0]['nErro']) {
            case 0:
                $sql = 'UPDATE matriculas SET detran_certificado = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $this->executaSql($sql);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
                $this->transacoes->set("json", json_encode($retorno));
                $this->transacoes->finalizaTransacao(null, 2);
                $this->transacoes->set("json", null);
                break;
            case 1:
                $this->transacoes->set("json", $mensagem);
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set("json", null);
                $retornoDetran['erro'] = true;
                break;
        }

        $this->salvarLogDetran(427, $dados['idmatricula'], $mensagem, $_POST);
        $this->executaSql('COMMIT');

        return $retornoDetran;
    }

    public function DadosCancelamentoPE($idmatricula)
    {
    if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('PE');
        $situacaoEmCurso = $this->matriculaObj->retornarSituacaoAtiva();
        $sql = "SELECT
            m.idmatricula, p.documento, p.data_nasc, m.idcurso,
            (
                SELECT
                    mh.data_cad
                FROM matriculas_historicos mh
                WHERE
                    mh.idmatricula = m.idmatricula AND
                    mh.tipo = 'situacao' AND mh.para = {$situacaoEmCurso['idsituacao']}
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        WHERE
            m.idmatricula = {$idmatricula}
            AND m.detran_situacao = 'LI'
            AND m.ativo = 'S'            
            AND (cw.fim = 'S' OR cw.ativa = 'S' OR cw.cancelada = 'S') -- para casos de reenvio
            AND {$info['cursos']}
            AND e.idestado = {$info['idestado']}
        ORDER BY data_inicio_curso ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CancelamentoPE($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_cancelamento', 'solicitou');
        define('INTERFACE_DETRAN_PE_CANCELAMENTO', retornarInterface('detran_pe_cancelamento')['id']);

        $aula = (!empty($GLOBALS['detran_tipo_aula_motociclista']['PE'][$dados['idcurso']])) ? $GLOBALS['detran_tipo_aula_motociclista']['PE'][$dados['idcurso']] : $GLOBALS['detran_tipo_aula']['PE'][$dados['idcurso']]; //Olhe a regra no card AWDE-1241 para entender o porquê disso.
        $arrayEnvio = [
            'Cpf' => $dados['documento'],
            'Nascimento' => $dados['data_nasc'],
            'Curso' => $aula['codigo'],
            'Modulo' => $aula['modulo'],
            'Cnpj' => $this->config['detran']['PE']['registro_empresa'],
            'Inicio' => (new DateTime($dados['data_inicio_curso']))->format('Y-m-d')
        ];

        $_POST = json_encode($arrayEnvio);

        $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_PE_CANCELAMENTO, 'S', $arrayEnvio);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['detran']['PE']['urlJSON'] . '/CursoDistanciaCancelar');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application\json',
            )
        );
        $chResult = curl_exec($ch);
        curl_close($ch);

        if ($chResult === false) {
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = '(Retorno vazio)';
            return $retornoDetran;
        }

        $retorno = json_decode(json_decode($chResult, true)['CursoDistanciaCancelarResult'], true);

        $this->executaSql('BEGIN');
        $mensagem = json_encode(['codigo' => $retorno[0]['nErro'], 'mensagem' => $retorno[0]['sMsg']]);

        $retornoDetran['erro'] = false;
        $retornoDetran['mensagem'] = $retorno[0]['sMsg'];
        switch ($retorno[0]['nErro']) {
            case 0:
                $sql = 'UPDATE matriculas SET detran_cancelamento = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $this->executaSql($sql);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_cancelamento', 'modificou', 'N', 'S', null);
                $this->transacoes->set("json", json_encode($retorno));
                $this->transacoes->finalizaTransacao(null, 2);
                $this->transacoes->set("json", null);
                break;
            case 1:
                $this->transacoes->set("json", $mensagem);
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set("json", null);
                $retornoDetran['erro'] = true;
                break;
        }

        $this->salvarLogDetran(430, $dados['idmatricula'], $mensagem, $_POST);
        $this->executaSql('COMMIT');

        return $retornoDetran;
    }

    public function ImportacaoPE($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_importacao', 'solicitou');
        define('INTERFACE_DETRAN_PE_IMPORTACAO', retornarInterface('detran_pe_importacao')['id']);

        $arrayEnvio = [
            'RegistroRenach' => $dados['renach'],
            'Cnpj' => $this->config['detran']['PE']['registro_empresa'],
        ];

        $_POST = json_encode($arrayEnvio);

        $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_PE_IMPORTACAO, 'S', $arrayEnvio);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['detran']['PE']['urlJSON'] . '/CadastroAlunoOutraUF');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application\json',
            )
        );
        $chResult = curl_exec($ch);
        curl_close($ch);

        if ($chResult === false) {
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = '(Retorno vazio)';
            return $retornoDetran;
        }

        $retorno = json_decode(json_decode($chResult, true)['CadastroAlunoOutraUFResult'], true);

        $this->executaSql('BEGIN');
        $mensagem = json_encode(['codigo' => $retorno[0]['nErro'], 'mensagem' => $retorno[0]['sMsg']]);

        $retornoDetran['erro'] = false;
        $retornoDetran['mensagem'] = $retorno[0]['sMsg'];
        switch ($retorno[0]['nErro']) {
            case 0:
                $sql = 'UPDATE matriculas SET detran_importacao = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $this->executaSql($sql);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_importacao', 'modificou', 'N', 'S', null);
                $this->transacoes->set("json", json_encode($retorno));
                $this->transacoes->finalizaTransacao(null, 2);
                $this->transacoes->set("json", null);
                break;
            case 1:
                $this->transacoes->set("json", $mensagem);
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set("json", null);
                $retornoDetran['erro'] = true;
                break;
        }

        $this->salvarLogDetran(431, $dados['idmatricula'], $mensagem, $_POST);
        $this->executaSql('COMMIT');

        return $retornoDetran;
    }

    public function DadosCertificadoPR($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('PR');
        $sql = "SELECT
            m.idmatricula,
            m.data_matricula,
            m.data_conclusao,
            p.documento,
            p.cnh,
            p.idpessoa,
            c.idcurso,
            p.data_nasc,
            m.idoferta,
            m.idcurso,
            m.idescola,
            (
                SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_certificado' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_certificado' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh
                WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'detran_situacao' AND mh.para = 'LI'
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso,
            m.detran_certificado
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
            INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo='S' AND frdm.cancelado <> 'S')
        WHERE
            e.detran_codigo IS NOT NULL
            AND cw.cancelada = 'N'
            AND m.detran_situacao = 'LI'
            AND {$info['cursos']}
            AND e.idestado = {$info['idestado']}
            AND m.ativo = 'S'
            AND cw.fim = 'S'
            AND m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoPR($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_certificado', 'solicitou');
        define('INTERFACE_DETRAN_PR_CERTIFICADO', retornarInterface('detran_pr_certificado')['id']);

        $connection = ConnectionFactory::getConnection(
            $this->config['detran']['PR']['id'],
            $this->config['detran']['PR']['chave']
        );

        $urlEnvio = $this->config['detran']['PR']['urlJSON'] .
            "/rest/servico/reciclagem/ead/certificado/cadastrar";

        $dataNasc = new DateTime($dados['data_nasc']);
        $dataInicio = new DateTime($dados['data_inicio_curso']);
        $dataConclusao = new DateTime($dados['data_conclusao']);

        $_POST = [
            'numCnpj' => $this->config['detran']['PR']['registro_empresa'],
            'numCpfInstrutor' => $this->config['detran']['PR']['registro_instrutor'],
            'numRegCnh' => $dados['cnh'],
            'numCpfCondutor' => $dados['documento'],
            'dataNascimento' => $dataNasc->format('d/m/Y'),
            'dataInicioCurso' => $dataInicio->format('d/m/Y'),
            'dataFimCurso' => $dataConclusao->format('d/m/Y')
        ];

        foreach ($_POST as $ind => $val) {
            $connection->addFormParam($ind, $val);
        }
        $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_PR_CERTIFICADO, 'E');
        $result = $connection->post($urlEnvio);
        $connection = null;
        $retorno = $result->getBody();
        $retornoHttpCode = $result->getHttpCode();

        if ($retornoHttpCode != 200) {
            $this->executaSql('BEGIN');
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            $this->salvarLogDetran(427, $dados['idmatricula'], $retorno, json_encode($_POST));
            $this->executaSql('COMMIT');
            $this->transacoes->set("json", $retorno);
            $this->transacoes->finalizaTransacao(null, 5);
            $this->transacoes->set("json", null);
            $retornoDetran['erro'] = true;
            if ($retornoHttpCode == 401) $retorno = '401 Unauthorized<br>(Credenciais de autenticação inválidas)';
            $retornoDetran['mensagem'] = !empty($retorno) ? $retorno : '(Retorno vazio)';
            return $retornoDetran;
        }

        $this->executaSql('BEGIN');
        $json = json_decode($retorno);
        $retornoDetran['erro'] = false;
        $retornoDetran['mensagem'] = ($json->mensagens) ? $json->mensagens : json_encode($retorno);
        switch ($retornoHttpCode) {
            case 200:
                if ($json->validacaoOk) {
                    $sql = 'UPDATE matriculas SET detran_certificado = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                    $this->executaSql($sql);

                    $this->matriculaObj->set('id', $dados['idmatricula'])
                        ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
                    $this->transacoes->set("json", $retorno);
                    $this->transacoes->finalizaTransacao(null, 2);
                    $this->transacoes->set("json", null);
                } else {
                    $this->transacoes->set("json", $retorno);
                    $this->transacoes->finalizaTransacao(null, 5);
                    $this->transacoes->set("json", null);
                    $retornoDetran['erro'] = true;
                }
        }

        $this->salvarLogDetran(427, $dados['idmatricula'], $retorno, json_encode($_POST));
        $this->executaSql('COMMIT');
        return $retornoDetran;
    }

    public function DadosCertificadoRJ($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('RJ');
        $disciplinas = implode(',', array_keys($this->detran_codigo_materia['RJ']));
        $sql = "SELECT
            m.idmatricula, m.data_matricula, m.data_conclusao, m.renach, p.documento, p.categoria, e.detran_codigo, c.idcurso, m.idmatricula, p.data_nasc, e.detran_codigo, c.idcurso, frdm.idfolha_matricula, m.idoferta, m.idcurso, m.idescola, m.data_primeiro_acesso,
            (
                SELECT oca.idava
                FROM ofertas_cursos_escolas oce
                    INNER JOIN ofertas_curriculos_avas oca ON (oca.idoferta = oce.idoferta AND oca.idcurriculo = oce.idcurriculo AND oca.ativo = 'S' AND oca.idava IS NOT NULL)
                    INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = oca.idcurriculo AND cb.ativo = 'S')
                    INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.ativo = 'S' )
                    INNER JOIN disciplinas d ON (d.iddisciplina = cbd.iddisciplina AND d.iddisciplina = oca.iddisciplina AND d.ativo = 'S')
                WHERE oce.idoferta = o.idoferta
                    AND oce.idcurso = c.idcurso
                    AND oce.idescola = e.idescola
                    AND d.iddisciplina IN ({$disciplinas})
                LIMIT 1
            ) AS idava,
            (
                SELECT mh.acao
                FROM matriculas_historicos mh
                WHERE mh.idmatricula = m.idmatricula
                AND mh.tipo = 'detran_certificado'
                ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad
                FROM matriculas_historicos mh
                WHERE mh.idmatricula = m.idmatricula
                AND mh.tipo = 'detran_certificado'
                ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh
                WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'detran_situacao' AND mh.para = 'LI'
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso,
            m.detran_certificado
        FROM matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
            INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo='S' AND frdm.cancelado <> 'S')
        WHERE
            m.renach IS NOT NULL
            AND cw.cancelada = 'N'
            AND m.detran_situacao = 'LI'
            AND {$info['cursos']}
            AND e.idestado = {$info['idestado']}
            AND m.ativo = 'S'
            AND cw.fim = 'S'
            AND m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoRJ($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_certificado', 'solicitou');
        define('INTERFACE_DETRAN_RJ_CERTIFICADO', retornarInterface('detran_rj_certificado')['id']);

        ini_set('soap.wsdl_cache_enabled', '0');
        ini_set('soap.wsdl_cache_ttl', 0);

        $retornoConsulta = [
            // CRÍTICAS ESTADUAIS
            '0002' => 'Campos obrigatórios ausentes',
            '0006' => 'Inexistência do número de formulário RENACH',
            '0071' => 'Requerimento 4 incompatível',
            '0072' => 'Certificado de Leg . deve ser para requerimento de primeira hab . ou reabilitação .',
            '0073' => 'País inválido para certificado de auto no requerimento de estrangeiro',
            '0074' => 'País não signatário para certificado de auto no requerimento de estrangeiro',
            '0075' => 'Tipo de requerimento incompatível para certificado de auto',
            '0076' => 'País inválido para certificado de moto no requerimento de estrangeiro',
            '0077' => 'País não signatário para certificado de moto no requerimento de estrangeiro',
            '0078' => 'Necessário categ . A e categ . pretendida X para certificado de moto no req . de mudança de categoria',
            '0079' => 'Condutor não necessita prova de atualização para certificado de atualização',
            '0101' => 'Formulário RENACH encerrado',
            '0105' => 'Serviço liberado apenas a idoso, 2 reprov . ou ativ . remunerada para certificado de atualização',
            '0107' => 'Sem autoriz . especial recepção de carga horária em req . mudança / adição para certificado de auto',
            '0108' => 'Necessita prova de atualização no requerimento de estrangeiro',
            '0109' => 'Formulário RENACH sem certificado de simulador / isenção para certificado de auto',
            '0199' => 'Condutor não exerce atividade remunerada',
            '0217' => 'Código do curso não tabelado',
            '0302' => 'Formulário RENACH em fase de emissão para certificado de CRCI',
            '0305' => 'Formulário RENACH sem reciclagem para certificado de CRCI',
            '0802' => 'Número de formulário RENACH inválido',
            '0950' => 'Código de curso inválido',
            '0999' => 'Transação efetuada com sucesso',
            '1003' => 'Curso - certificado já informado',
            '1004' => 'Data do início ou fim do curso superior a data corrente',
            '1005' => 'Carga horária insuficiente',
            '1006' => 'Carga horária incompatível com período',
            '1007' => 'Data início ou fim do curso diferente de AAAA / MM / DD',
            '1008' => 'Sem autorização especial para recepção de carga horária',
            '1009' => 'Código de resultado de exame clínico inválido para exame médico',
            '1010' => 'Sem autorização especial para ignorar crítica',
            '1011' => 'Reprovado no exame psicológico',
            '1012' => 'Autorização especial para ignorar crítica',
            '1013' => 'Resultado do exame de Leg . diferente de 1',
            '1014' => 'Resultado do exame de Leg . diferente de 1 sem transferência .',
            '1015' => 'Formulário RENACH já possui certificado de auto',
            '1016' => 'Formulário RENACH já possui certificado de moto / já aprovado no exame de Leg . na atualização',
            '1017' => 'Indicador de reaproveitamento de cursos diferente de 1 ou 2',
            '1018' => 'Data de inclusão do requerimento maior que 12 meses',
            '1020' => 'Candidato não está matriculado no curso',
            '1022' => 'Carga horária diferente de 20',
            '1023' => 'Carga horária máxima diária excedida',
            '1024' => 'Curso não é de CRCI',
            '1025' => 'Curso não é a distancia',
            '1026' => 'Carga horária inferior a 20',
            '1029' => 'Data da matrícula é posterior a data do início do curso',
            '1031' => 'Carga horária inferior a 20 na categoria X para certificado de Leg .',
            '1032' => 'Carga horaria inferior a 45 nas categorias A, B, AB ou XB para certificado de Leg .',
            '1033' => 'Carga horária inferior a 10 nas categorias X ou XB para certificado de moto .',
            '1034' => 'Carga horária inferior a 10 nas categorias XB, XC, XD ou XE na adic . Categ . Para certific . de moto',
            'R999' => 'Código de erro do REFOR',
            '6001' => 'Formulário RENACH em branco',
            '6002' => 'Formulário RENACH fora do formato "RJ99999999"',
            '6003' => 'Formulário RENACH com DV inválido',
            '6004' => 'CAER em branco',
            '6005' => 'CAER não encontrado',
            '6006' => 'Código de curso inválido',
            '6007' => 'Tipo de requerimento inválido para curso informado',
            '6008' => 'Data de início / fim de curso em branco',
            '6009' => 'Data de início / fim de curso fora do formato "AAAAMMDD"',
            '6010' => 'CPF do instrutor fora do formato "NNNNNNNNNNN"',
            '6011' => 'CPF do instrutor zerado',
            '6012' => 'CPF do instrutor com DV inválido',
            '6013' => 'Formulário RENACH não encontrado',
            // CRÍTICAS NACIONAIS
            '000' => 'Transação Efetuada .',
            '002' => 'Campos Obrigatórios Ausentes .',
            '006' => 'Inexistência de Número De Formulário RENACH na Base .',
            '011' => 'UF de Origem da Transação Diferente da UF de Domínio .',
            '016' => 'Inexistência de Número de Registro na Base .',
            '017' => 'CNH Sendo Emitida .',
            '042' => 'Liberação já Registrada – Confirme Alteração de Dados .',
            '222' => 'Modalidade do Curso Fora da Tabela ou Ausente ou Não se Aplica ao Curso .',
            '345' => 'Curso / Exame não compatível com a Categoria Registrada no Prontuário do Candidato / Condutor .',
            '346' => 'Exame não compatível com a Categoria Registrada no Prontuário do Candidato / Condutor .',
            '375' => 'O Número Formulário RENACH só Aceito Para Candidato .',
            '376' => 'Tipo de Evento Inválido ou Ausente .',
            '377' => 'Tipo Atualização Evento Inválido ou Ausente .',
            '388' => 'Categoria Permitida Deve ser Igual ou Inferior a Categoria Pretendida .',
            '389' => 'CNH Cancelada Por Erro Gráfica .',
            '390' => 'O Campo Modalidade é Obrigatório Para Este Curso .',
            '391' => 'A Carga Horária é Obrigatório Para Este Curso .',
            '392' => 'O Município do Evento não Pertence a UF Evento .',
            '393' => 'Data Validade é Obrigatório Para Este Evento .',
            '394' => 'Categoria é Obrigatório Para Este Curso .',
            '395' => 'Evento Já Registrado no Prontuário do Condutor / Candidato .',
            '396' => 'Código do Curso Fora da Tabela ou Ausente .',
            '397' => 'Data Inicio Curso Inválida .',
            '0398' => 'Data Fim Curso Inválida .',
            '399' => 'CNPJ Entidade Credenciada Inválido ou Ausente .',
            '400' => 'CPF Instrutor Inválido ou Ausente .',
            '401' => 'Município Evento Fora da Tabela ou Ausente .',
            '402' => 'UF Evento Fora da Tabela ou Ausente .',
            '403' => 'Evento Não Cadastrado no Prontuário do Condutor / Candidato .',
            '404' => 'Dados não Divergem dos Cadastrados na BINCO / BCA .',
            '405' => 'Atributos Divergem dos Cadastrados na BINCO / BCA .',
            '406' => 'Evento Rejeitado – Evento é Pré - Requisito Para CNH Autorizada / Emitida .',
            '407' => 'Código do Exame Fora da Tabela ou Ausente .',
            '409' => 'Resultado Inválido ou Ausente .',
            '410' => 'CPF Examinador 1 Inválido ou Ausente .',
            '411' => 'CPF Examinador 2 Inválido .',
            '412' => 'Categoria Pretendida Fora da Tabela ou Ausente .',
            '413' => 'Categoria Permitida Fora da Tabela ou Ausente .',
            '418' => 'UF Detran Infração Fora da Tabela ou Ausente .',
            '467' => 'Número - Chave(Formulário RENACH) inválido para Condutor .',
            '472' => 'Carga - horária Inválida .',
            '473' => 'Data - Validade Inválida ou Ausente ou Não se Aplica ao Curso / Exame .',
            '474' => 'Dados de Liberação inválidos para tipo de atualização informado .',
            '479' => 'Número - Certificado Inválido .',
            '512' => 'Data Inicio - Penalidade - Bloqueios com Data Calendário no Prontuário, Confirme .',
            '513' => 'Data Inicio - Penalidade - Bloqueios Igual Data Inicio - Penalidade - Bloqueios do Prontuário .',
            '593' => 'Carga horária insuficiente para o Curso / Exame',
            '603' => 'Condutor transferido CNH autorizada ou emitida',
            '611' => 'Inexistência do Código da Entidade / Profissional na Base',
            '616' => 'Entidade ou Profissional Bloqueado',
            '625' => 'Entidade com credenciamento em situação inativa',
            '639' => 'Vínculo do profissional à entidade inexistente',
            '0658' => 'Vínculo do profissional à entidade inativo',
            '659' => 'Vínculo do profissional à entidade bloqueado',
            '661' => 'Vínculo do profissional à entidade com mudança de UF',
            '662' => 'Evento não compatível com a entidade / profissional',
            '663' => 'Entidade não cadastrada / vinculada UF do evento',
            '752' => 'Número de Registro Inválido .',
            '801' => 'UF do Número Formulário RENACH Fora da Tabela de UF’s .',
            '802' => 'Número Formulário RENACH Inválido ou Ausente .',
            '804' => 'UF do Número Formulário RENACH Diferente da UF de Origem da Transação .',
            '837' => 'Código da Categoria Fora da Tabela .',
            '890' => 'Tipo da Chave de Pesquisa Inválido .',
            '998' => 'Tamanho da Transação Menor que o Tamanho Esperado',
        ];

        $numeroSequencial = mysql_fetch_assoc($this->executaSql('SELECT (COUNT(*)+1) as proximo FROM detran_logs'));

        $parteFixa = [
            'sequencial' => str_pad($numeroSequencial['proximo'], 6, '0', STR_PAD_LEFT),
            'cod-transacao' => str_pad(10, 3, 0, STR_PAD_LEFT),
            'modalidade' => '4',
            'cliente' => str_pad($this->config['detran']['RJ']['pUsuario'], 11),
            'uf-transa' => 'BR',
            'uf-origem' => 'BR',
            'uf-destino' => 'RJ',
            'tipo' => '0',
            'tamanho' => '0054',
            'retorno' => '00',
            'juliano' => str_pad(date('z') + 1, 3, '0', STR_PAD_LEFT),
        ];

        try {
            $data_inicio_curso = new DateTime($dados['data_inicio_curso']);

            $parteVariavel = [
                'tipo-chave' => '1',
                'numero-chave' => str_pad($dados['renach'], 11),
                'codigo-evento' => 'C',
                'codigo-atualizacao' => 'I',
                'codigo-curso' => $this->detran_tipo_aula['RJ'][$dados['idcurso']],
                'codigo-modalidade' => 2,
                'numero-certificado' => str_pad($dados["idfolha_matricula"], 15),
                'data-inicio' => $data_inicio_curso->format('Ymd'),
                'data-fim' => str_replace('-', '', $dados['data_conclusao']),
                'carga-horaria' => str_pad(30, 3, 0, STR_PAD_LEFT),
                'entidade-credenciada' => $this->config['detran']['RJ']['registro_empresa'],
                'cpf-instrutor' => $this->config['detran']['RJ']['registro_instrutor'],
                'codigo-municipio-curso' => '08105',
                'uf-curso' => 'SC',
                'data-validade' => str_pad('', 8, 0, STR_PAD_LEFT),
                'codigo-categoria' => str_pad($dados['categoria'], 4),
                'codigo-local-dh' => $this->config['detran']['RJ']['caer'],
                'codigo-disciplina' => '',
                'numero-turma' => '',
                'codigo-operacao' => '',
            ];

            $string_tripa = str_pad(implode('', $parteVariavel), 3237);
            for ($i = 0; $i < 13; $i++) {
                $indice = $i * 249;
                $variavel[$i] = substr($string_tripa, $indice, 249);
            }

            $dadosSOAP = [
                'GTRN0003' => [
                    'PARTE-FIXA' => implode('', $parteFixa),
                    'PARTE-VARIAVEL' => $variavel,
                ]
            ];

            $options = [];

            $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_RJ_CERTIFICADO, 'E', $dadosSOAP);
            $conexaoSOAP = new SoapClient($this->config['detran']['RJ']['urlWsl']);

            $auth = array(
                'exx-natural-security' => 'TRUE',
                'exx-natural-library' => 'LIBBRK',
                'exx-rpc-userID' => $this->config['detran']['RJ']['pUsuario'],
                'exx-rpc-password' => $this->config['detran']['RJ']['pSenha'],
            );
            $authvar = new SoapVar($auth, SOAP_ENC_OBJECT);
            $header = new SoapHeader('urn:com.softwareag.entirex.xml.rt', 'EntireX', $authvar);
            $conexaoSOAP->__setSoapHeaders($header);

            $respostaSoap = $conexaoSOAP->__soapCall('GTRN0003', $dadosSOAP, $options);

            $retorno = json_decode(json_encode($respostaSoap), true);

            if (is_array($retorno['PARTE-VARIAVEL']['string'])) {
                $codigoRetorno = substr($retorno['PARTE-VARIAVEL']['string'][0], 0, 4);
            } else {
                $codigoRetorno = substr($retorno['PARTE-VARIAVEL']['string'], 0, 4);
            }
            $stringEnvio = trim(implode('', $parteFixa) . ' | ' . implode('', $variavel));

            $retornoDetran['erro'] = false;
            $retornoDetran['mensagem'] = $retornoConsulta[$codigoRetorno];

            $this->matriculaObj->executaSql('BEGIN');

            if (in_array($codigoRetorno, ['0999', '000'])) {
                $sql = 'UPDATE matriculas SET detran_certificado = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $this->executaSql($sql);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
                $this->transacoes->finalizaTransacao(null, 2, null, $retorno);
            } else {
                $this->transacoes->set('json', json_encode($retorno));
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set('json', NULL);
                $retornoDetran['erro'] = true;
            }

            $this->salvarLogDetran(
                427,
                $dados['idmatricula'],
                $codigoRetorno . ' - ' . $retornoConsulta[$codigoRetorno],
                $stringEnvio
            );

            $this->executaSql('COMMIT');
        } catch (Exception $excecao) {
            $this->transacoes->finalizaTransacao(null, 3, json_encode([
                    'codigo' => $excecao->getCode(),
                    'mensagem' => $excecao->getMessage()
                ])
            );
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            $tipoErro = (get_class($excecao) == 'SoapFault') ? 'A conexão com Detran-RJ' : 'O Oráculo';
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = $tipoErro.' apresentou uma falha técnica inesperada.';
            $retornoDetran['falha_tecnica'] = true;
        }
        return $retornoDetran;
    }

    public function DadosCertificadoRS($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('RS');
        $sql = "SELECT
            m.idmatricula,
            m.data_matricula,
            m.data_conclusao,
            p.documento,
            p.idpessoa,
            e.detran_codigo,
            c.idcurso,
            c.carga_horaria_total,
            m.cod_ticket,
            m.renach,
            m.idoferta,
            m.idcurso,
            m.idescola,
            (
                SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_certificado' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS acao_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
                mh.tipo = 'detran_certificado' ORDER BY mh.idhistorico DESC LIMIT 1
            ) AS data_ultimo_historico,
            (
                SELECT mh.data_cad FROM matriculas_historicos mh
                WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'detran_situacao' AND mh.para = 'LI'
                ORDER BY mh.data_cad ASC LIMIT 1
            ) AS data_inicio_curso,
            m.detran_certificado
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
            INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo='S' AND frdm.cancelado <> 'S')
        WHERE
            m.cod_ticket IS NOT NULL
            AND m.renach IS NOT NULL
            AND e.detran_codigo IS NOT NULL
            AND m.renach <> ''
            AND e.detran_codigo <> ''
            AND cw.cancelada = 'N'
            AND m.detran_situacao = 'LI'
            AND {$info['cursos']}
            AND e.idestado = {$info['idestado']}
            AND m.ativo = 'S'
            AND cw.fim = 'S'
            AND m.idmatricula = {$idmatricula}
        ORDER BY data_ultimo_historico ASC";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoRS($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_certificado', 'solicitou');
        define('INTERFACE_DETRAN_RS_CERTIFICADO', retornarInterface('detran_rs_certificado')['id']);

        try {
            $dataInicio = new DateTime($dados['data_inicio_curso']);
            $dataConclusao = new DateTime($dados['data_conclusao']);
        } catch (Exception $e) {
            return $e;
        }
        $cargaHoraria = !empty($dados['carga_horaria_total']) ? $dados['carga_horaria_total'] : 30;
        $codCurso = $this->detran_tipo_aula['RS'][$dados['idcurso']];

        # Quando o Codigo do curso for contido em $cursos, o campo $codCertificadoEmpresa tem que substituir os caracteres
        # não numericos
        $cursos = ['019', '044', '014', '015', '016'];
        if (in_array(str_pad($codCurso, 3, '0', STR_PAD_LEFT), $cursos) || substr($dados["renach"], 0, 2) !== "RS")
        {
            $codCertificadoEmpresa =
                str_pad(
                    empty($dados['renach']) ? $dados['idmatricula'] : soNumeros($dados['renach']),
                    11,
                    '0',
                    STR_PAD_LEFT) . 'E' .
                str_pad(
                    $codCurso,
                    3,
                    '0',
                    STR_PAD_LEFT
                );
        } else {
            $codCertificadoEmpresa =
                empty($dados['renach']) ? $dados['idmatricula'] : $dados['renach'] .
                                                      'E' .
                                                      str_pad($codCurso, 3, '0', STR_PAD_LEFT);
        }
        $arrayEnvio = [
            'codTicket' => $dados['cod_ticket'],
            'codEmpresa' => $dados['detran_codigo'],
            'codCurso' => $codCurso,
            'cpfProfissional' => $this->config['detran']['RS']['registro_instrutor'],
            'cpfAluno' => $dados['documento'],
            'renach' => substr($dados["renach"], 0, 2) !== "RS" ? "" : $dados['renach'],
            'codCertificadoEmpresa' => $codCertificadoEmpresa,
            'dthInicio' => $dataInicio->format('Y-m-d H:i'),
            'dthFim' => $dataConclusao->format('Y-m-d H:i'),
            'cargaHoraria' => $cargaHoraria,
        ];
        $_POST = json_encode($arrayEnvio);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['detran']['RS']['urlJSON'] . '/incluiCertificadoAluno');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'organizacao: ' . $this->config['detran']['RS']['organizacao'],
                'matricula: ' . $this->config['detran']['RS']['matricula'],
                'senha:' . $this->config['detran']['RS']['senha']
            )
        );
        $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_RS_CERTIFICADO, 'E');

        $chResult = curl_exec($ch);
        $retorno = $chResult;
        $retornoHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($retorno === false) {
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            $this->transacoes->set("json", $retorno);
            $this->transacoes->finalizaTransacao(null, 3);
            $this->transacoes->set("json", null);
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = '(Retorno vazio)';
            return $retornoDetran;
        }

        $retornoDetran['erro'] = false;
        $retornoDetran['mensagem'] = json_encode($retorno);

        $this->executaSql('BEGIN');
        switch ($retornoHttpCode) {
            case 200:
                $sql = 'UPDATE matriculas SET detran_certificado = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                $this->executaSql($sql);

                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
                $this->transacoes->set("json", $retorno);
                $this->transacoes->finalizaTransacao(null, 2);
                $this->transacoes->set("json", null);
                break;
            case 400:
                $this->transacoes->set("json", $retorno);
                $this->transacoes->finalizaTransacao(null, 5);
                $this->transacoes->set("json", null);
                $erros = json_decode($retorno);
                foreach ($erros as $erro)
                    $strErro .= "<br>Erro: ".$erro->codErro . " - " .$erro->msgErro;
                $retornoDetran['erro'] = true;
                $retornoDetran['mensagem'] = $strErro;

                break;
        }

        $this->salvarLogDetran(427, $dados['idmatricula'], $retorno, $_POST);
        $this->executaSql('COMMIT');
        return $retornoDetran;
    }

    public function DadosCertificadoSC($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('SC');

        $situacaoEmCurso = $this->matriculaObj->retornarSituacaoAtiva();

        $sql = "SELECT
        m.idmatricula,    
        m.data_conclusao,
        p.documento,       
        p.categoria,             
        m.idcurso,     
        frdm.numero_ordem,
        e.detran_codigo,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'detran_situacao' AND mh.para = 'LI'
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_liberacao,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'situacao' AND mh.para = " . $situacaoEmCurso['idsituacao'] . "
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_inicio_curso
    FROM
        matriculas m
        INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
        INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)       
        INNER JOIN escolas e ON (e.idescola = m.idescola)
        INNER JOIN cursos c ON (c.idcurso = m.idcurso)
        INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo='S' AND frdm.cancelado <> 'S')
    WHERE 
        m.idmatricula = {$idmatricula}
        AND m.detran_certificado = 'N'
        AND cw.cancelada = 'N'
        AND m.detran_situacao = 'LI'
        AND {$info['cursos']}
        AND e.idestado = {$info['idestado']}
        AND m.ativo = 'S'
        AND cw.fim = 'S'
        AND m.data_conclusao IS NOT NULL       
    ORDER BY data_ultimo_historico ASC
    LIMIT 10 ";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoSC($dados)
    {
        $this->salvarAcao($dados['idmatricula'], 'detran_certificado', 'solicitou');
        define('INTERFACE_DETRAN_SC_CERTIFICADO', retornarInterface('detran_sc_certificado')['id']);

        $inicio_curso =  $dados['data_liberacao'] ?: $dados['data_inicio_curso'];

        $arrayEnvio = [
            'cargaHoraria' => $this->detran_carga_horaria['SC'][$dados['idcurso']],
            'categoria' => $dados['categoria'],
            'chaveCurso' => str_pad($this->detran_tipo_aula['SC'][$dados['idcurso']] , 2 , '0' , STR_PAD_LEFT),
            'cnpjInstituicao' => $this->config['detran']['SC']['cnpjInst'],
            'cpfCondutor' => $dados['documento'],
            'cpfInstrutor' => $this->config['detran']['SC']['cpf_instrutor'],
            'dataFimCurso' => (new DateTime($dados['data_conclusao']))->format("Y-m-d\TH:i:s"),
            'dataInicioCurso' =>  (new DateTime($inicio_curso))->format("Y-m-d\TH:i:s"),
            'numeroCertificadoStr' => substr($dados['detran_codigo'], -4, 4) . "SC" . str_pad($dados["numero_ordem"] , 9 , '0' , STR_PAD_LEFT)
        ];

        $_POST = json_encode($arrayEnvio);

        if($this->detran_tipo_aula['SC'][$dados['idcurso']] == 20){
            $endpoint = '/educacao/ead/grava-curso-reciclagem';
        } else {
            $endpoint = '/educacao/ead/grava-curso-especializado';
        }

        $this->transacoes->iniciaTransacao(INTERFACE_DETRAN_SC_CERTIFICADO, 'E', $arrayEnvio);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['detran']['SC']['urlJSON'] . $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->config['detran']['SC']['token']
            )
        );

        $chResult = curl_exec($ch);
        $codigoHTTP = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($chResult === false) {
            if ($dados['acao_historico'] != 'detran_nao_respondeu') {
                $this->matriculaObj->set('id', $dados['idmatricula'])
                    ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
            }
            $retornoDetran['erro'] = true;
            $retornoDetran['mensagem'] = '(Retorno vazio)';
            return $retornoDetran;
        }
        $retorno = json_decode($chResult);
        $mensagem = $retorno[0]->mensagemUsuario;
        $numeroHomologacao = $retorno[0]->numeroHomologacao;

        $this->executaSql('BEGIN');

        $retornoDetran['erro'] = false;
        $retornoDetran['mensagem'] = $mensagem;
        if($codigoHTTP == 200){ // Caso diferente, será por conta de erro ou indisponibilidade

            switch ($mensagem) {
                case 'Registro gravado com sucesso.':
                    $sql = 'UPDATE matriculas SET detran_certificado = "S" WHERE idmatricula = ' . $dados['idmatricula'];
                    $this->executaSql($sql);

                    $this->matriculaObj->set('id', $dados['idmatricula'])
                        ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
                    $this->transacoes->set("json", $chResult);
                    $this->transacoes->finalizaTransacao(null, 2);
                    $this->transacoes->set("json", null);
                    break;
                default:
                    $this->transacoes->set("json", $chResult);
                    $this->transacoes->finalizaTransacao(null, 5);
                    $this->transacoes->set("json", null);
                    $retornoDetran['erro'] = true;
                    break;
            }
        }

        $this->salvarLogDetran(427, $dados['idmatricula'], $mensagem . ' - ' . $numeroHomologacao, $_POST);
        $this->executaSql('COMMIT');

        return $retornoDetran;
    }

    public function DadosCertificadoCE($idmatricula)
    {
        if (!$idmatricula)
            throw new InvalidArgumentException('O parametro $idmatricula é obrigatório.');
        if (!is_int($idmatricula))
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser inteiro.');
        if ($idmatricula <= 0)
            throw new InvalidArgumentException('O parametro $idmatricula tem que ser positivo.');
        $info = $this->CursosIDestado('CE');

        $situacaoEmCurso = $this->matriculaObj->retornarSituacaoAtiva();

        $sql = "SELECT
        m.idmatricula,
        m.data_matricula,
        m.data_conclusao,
        p.documento,
        p.idpessoa,
        c.idcurso,
        c.carga_horaria_total,
        e.detran_codigo,
        m.idoferta,
        m.idcurso,
        m.idescola,
        (
            SELECT mh.acao FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = 'detran_certificado' ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS acao_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh WHERE mh.idmatricula = m.idmatricula AND
            mh.tipo = 'detran_certificado' ORDER BY mh.idhistorico DESC LIMIT 1
        ) AS data_ultimo_historico,
        (
            SELECT mh.data_cad FROM matriculas_historicos mh
            WHERE mh.idmatricula = m.idmatricula AND mh.tipo = 'detran_situacao' AND mh.para = ".$situacaoEmCurso['idsituacao']."
            ORDER BY mh.data_cad ASC LIMIT 1
        ) AS data_inicio_curso
        FROM
            matriculas m
            INNER JOIN matriculas_workflow cw ON (m.idsituacao = cw.idsituacao)
            INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
            INNER JOIN escolas e ON (e.idescola = m.idescola)
            INNER JOIN cursos c ON (c.idcurso = m.idcurso)
            INNER JOIN folhas_registros_diplomas_matriculas frdm ON (frdm.idmatricula = m.idmatricula AND frdm.ativo='S')
        WHERE 
            m.idmatricula = ".$idmatricula."
            AND e.detran_codigo IS NOT NULL
            AND m.detran_certificado = 'N'
            AND cw.cancelada = 'N'
            AND m.detran_situacao = 'LI'
            AND ".$info['cursos']." 
            AND e.idestado = " . $info['idestado'] . " 
            AND m.ativo = 'S'
            AND cw.fim = 'S'
        ORDER BY data_ultimo_historico ASC
        LIMIT 10";
        return $this->retornarLinhasArray($sql);
    }

    public function CertificadoCE($dados)
    {
    $siglaEstado = 'CE';
    $dataInicio = new \DateTime($dados['data_inicio_curso']);
    $dataConclusao = new \DateTime($dados['data_conclusao']);
    $cargaHoraria = !empty($dados['carga_horaria_total']) ? $dados['carga_horaria_total'] : 30;
    $codCurso = $this->detran_tipo_aula[$siglaEstado][array_keys($this->detran_tipo_aula[$siglaEstado])[0]];

    $arrayEnvio = [
        'chaveAcesso' => $this->config['detran'][$siglaEstado]['chaveAcesso'],
        'idMatricula' => $dados['idmatricula'],
        'dataInicio' => $dataInicio->format('d/m/Y'),
        'dataFim' => $dataConclusao->format('d/m/Y')
    ];

    $jsonEnvio = json_encode($arrayEnvio);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->config['detran'][$siglaEstado]['urlJSON'] . '/gerarCertificado');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonEnvio);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'User-Agent: Chrome/84.0.4147.125 Safari/537.36',
            'chaveAcesso: ' . $this->config['detran'][$siglaEstado]['chaveAcesso']
        )
    );

    $chResult = curl_exec($ch);
    $retorno = $chResult;
    $retornoHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);


    if ($retorno === false) {
        if ($dados['acao_historico'] != 'detran_nao_respondeu') {
            $this->matriculaObj->set('id', $dados['idmatricula'])
                ->adicionarHistorico(null, 'detran_situacao', 'detran_nao_respondeu', null, null, null);
        }
        continue;
    }

    $this->matriculaObj->executaSql('BEGIN');
    if ($retorno['codigo'] == 'E000'){
        $sql = 'UPDATE matriculas SET detran_certificado = "S" WHERE idmatricula = ' . $dados['idmatricula'];
        $this->matriculaObj->executaSql($sql);

        $this->matriculaObj->set('id', $dados['idmatricula'])
            ->adicionarHistorico(null, 'detran_certificado', 'modificou', 'N', 'S', null);
    }

    $codTransacao = 427; //Cadastro certificado
    salvarLogDetran($this->matriculaObj, $codTransacao, $dados['idmatricula'], $retorno, $jsonEnvio);
    $this->matriculaObj->executaSql('COMMIT');
    }

    private function CursosIDestado($sigla)
    {
        $cursosIn = '';
        if (is_array($this->detran_tipo_aula[$sigla])) {
            $cursosIn = 'c.idcurso IN (' . implode(',', array_keys($this->detran_tipo_aula[$sigla])) . ')';
        }
        $estadosDetran = $this->listarEstadosIntegrados();
        $id = $estadosDetran[$sigla];

        return ['cursos' => $cursosIn, 'idestado' => $id];
    }
}
