<?php

/**
 * idsituacao vem do array situacao_transacao no config
 */
class Transacoes extends Core
{
    const TABELA = 'orio_transacoes';
    const CHAVE_PRIMARIA = 'idtransacao';
    const SITUACAO_TRANSACAO_PENDENTE = 1;
    const SITUACAO_TRANSACAO_CONCLUIDA = 2;
    const SITUACAO_TRANSACAO_ERRO = 3;
    const SITUACAO_TRANSACAO_REPROCESSADA = 4;
    const SITUACAO_TRANSACAO_ERRO_DE_NEGOCIO = 5;

    public $acessoBancoLog = null;
    public $tabela = null;
    public $slug = null;
    public $idInterface = null;
    public $tipo = null;
    public $idTransacao = null;
    protected $json = null;
    private $tempoInicial;
    private $tempoFinal;

    public function listar()
    {
        $this->sql = "SELECT {$this->campos}
                    FROM " . static::TABELA . "
                    WHERE
                        ativo = 'S'";

        if (is_array($_GET["q"])) {
            foreach ($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|", $campo);
                $valor = str_replace("'", "", $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === "0") and $valor != "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        $this->sql .= " and " . $campo[1] . " = '" . $valor . "' ";
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    }
                }
            }
        }

        if (!empty($_GET['q']['9|idinterface'])) {
            $this->sql .= ' AND idinterface IN (' . str_replace(
                    array('-', 'bs'),
                    array('', ','),
                    $_GET['q']['9|idinterface']
                ) . ')';
        }

        if (!empty($_GET['q']['6|data_cad'])) {
            $dateTime = \DateTime::createFromFormat('d/m/Y H:i:s', $_GET['q']['6|data_cad']);
            $this->sql .= " AND data_cad >= '" . $dateTime->format('Y-m-d H:i:s') . "'";
        }

        if (!empty($_GET['q']['7|data_cad'])) {
            $dateTime = \DateTime::createFromFormat('d/m/Y H:i:s', $_GET['q']['7|data_cad']);
            $this->sql .= " AND data_cad <= '" . $dateTime->format('Y-m-d H:i:s') . "'";
        }

        $this->groupby = "idtransacao";
        $transacoes = $this->retornarLinhas();

        return $transacoes;
    }

    public function finalizaTransacao(
        $tempo = 0,
        $situacao = self::SITUACAO_TRANSACAO_PENDENTE,
        $erro = null,
        $resposta = null
    )
    {
        if (!$this->idTransacao) {
            return false;
        }

        $this->tempoFinal = tempoExecucao($this->tempoInicial);
        $tempo = (empty($tempo)) ? $this->tempoFinal : $tempo;
        $sql = "UPDATE " . static::TABELA . " SET
                    tempo = " . $tempo;

        if (!empty($_GET['idTransacaoReprocessada'])) {
            $sql .= ", idtransacao_reprocessada = " . $_GET['idTransacaoReprocessada'];
        }

        if (!empty($_GET['idUsuarioReprocessou'])) {
            $sql .= ", idusuario_reprocessou = " . $_GET['idUsuarioReprocessou'];
        }

        $sql .= ', erro = "' . addslashes($erro) . '"
                , situacao = ' . $situacao . '
                , xml_resposta = "' . $resposta . '"';

        $sql .= ", json_resposta= '" . $this->json . "'
                WHERE " . static::CHAVE_PRIMARIA . " = " . $this->idTransacao;

        return $this->executaSql($sql);
    }

    public function reprocessar($idTransacao)
    {
        $retorno['sucesso'] = false;
        $this->set('id', $idTransacao);
        $this->set('idTransacao', $idTransacao);
        $this->set('campos', 't.*');
        $linha = $this->retornar();

        if (!empty($linha['json'])) {
            $this->json = $this->post['json_alterar'];
            $this->idTransacaoReprocessada = $idTransacao;
            $this->idUsuarioReprocessou = $this->idusuario;
            $this->iniciaTransacao($linha['idinterface'], $linha['tipo']);
        } else {
            if (
                empty($GLOBALS['orio_interfaces'][$linha['idinterface']]['slug'])
                || empty($GLOBALS['orio_interfaces'][$linha['idinterface']]['soapAction'])
            ) {
                $retorno['erro'] = true;
                $retorno['erros'][] = 'informacoes_insuficientes';

                return $retorno;
            }

            $interface = $GLOBALS['orio_interfaces'][$linha['idinterface']]['slug'];
            $acao = $GLOBALS['orio_interfaces'][$linha['idinterface']]['soapAction'];
            $wsdl = $GLOBALS['config']['urlSistema'] . '/api/orio/' . $interface . '?wsdl';

            $client = new SoapClientFilho(
                $wsdl,
                array(
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    'trace' => true,
                )
            );

            try {
                $request = $client->fazerRequisicao(
                    $acao,
                    $this->post['xml_alterar'],
                    $interface,
                    $idTransacao,
                    $this->idusuario
                );
            } catch (\Exception $e) {
            }
        }

        $this->alterarSituacao($idTransacao, 4);

        $retorno['sucesso'] = true;

        return $retorno;
    }

    public function retornar()
    {
        $this->sql = "select " . $this->campos . "
                FROM " .
                     static::TABELA . " t
                LEFT JOIN
                    orio_transacoes ct on (ct.idtransacao_reprocessada = t.idtransacao)
                WHERE
                    t.ativo = 'S' AND
                    t." . static::CHAVE_PRIMARIA . " = " . $this->id;

        return $this->retornarLinha($this->sql);
    }

    public function iniciaTransacao($idInterface, $tipo, $dadosRequisicao = null)
    {
        $this->tempoInicial = tempoExecucao();
        $this->idInterface = $idInterface;
        $this->tipo = $tipo;
        $ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['SERVER_ADDR'];
        if ($dadosRequisicao) {
            $requisicao = json_encode($dadosRequisicao);
        } else {
            $requisicao = file_get_contents('php://input');
            if ($requisicao) {
                $requisicao = (string)$requisicao;
            } else {
                $requisicao = json_encode([]);
            }
        }
        // esconde a senha
        if ($_SERVER['HTTP_SENHA']) {
            $senha = $_SERVER['HTTP_SENHA'];
            $_SERVER['HTTP_SENHA'] = '**********';
        }

        $sql = 'INSERT INTO ' . static::TABELA . ' SET
            data_cad = NOW(),
            ativo = "S",
            situacao = ' . self::SITUACAO_TRANSACAO_PENDENTE . ',
            tipo = "' . $tipo . '",
            idinterface = ' . $idInterface . ',
            ip = "' . $ip . '",
            _get = "' . mysql_real_escape_string(json_encode($_GET)) . '",
            _post = "' . mysql_real_escape_string(json_encode($_POST)) . '",
            xml_requisicao = "' . mysql_real_escape_string($requisicao) . '",
            _server = "' . mysql_real_escape_string(json_encode($_SERVER)) . '"';

        // recoloca a senha
        if ($_SERVER['HTTP_SENHA']) {
            $_SERVER['HTTP_SENHA'] = $senha;
        }

        if (!empty($this->json)) {
            $sql .= ', json = "' . mysql_real_escape_string($this->json) . '"';
        }

        if (!empty($this->idTransacaoReprocessada)) {
            $sql .= ', idtransacao_reprocessada = ' . $this->idTransacaoReprocessada;
        }

        if (!empty($this->idUsuarioReprocessou)) {
            $sql .= ', idusuario_reprocessou = ' . $this->idUsuarioReprocessou;
        }

        $this->idTransacao = ($this->executaSql($sql)) ? mysql_insert_id() : false;
        return $this->idTransacao;
    }

    public function alterarSituacao($idTransacao, $situacao)
    {
        $sql = "UPDATE " .
               static::TABELA . "
                SET
                    situacao = '" . $situacao . "'
                WHERE
                    idTransacao = " . $idTransacao;

        return $this->executaSql($sql);
    }

    public function retornarTransacoesParaEnvio($qtdTransacoes)
    {
        $this->sql = "SELECT
                        idtransacao,
                        situacao,
                        idinterface,
                        json
                    FROM " . static::TABELA . "
                    WHERE
                        ativo = 'S' and
                        tipo = 'S' and
                        situacao = " . self::SITUACAO_TRANSACAO_PENDENTE . "
                        and json is not null
        ";

        $this->ordem_campo = null;
        $this->ordem = null;
        $this->limite = $qtdTransacoes;

        return $this->retornarLinhas();
    }

    public function salvarXMLTransacaoEnviada(
        $idTransacao,
        $requisicao,
        $resposta,
        $situacao
    )
    {
        $sql = "UPDATE " .
               static::TABELA . "
                SET
                    situacao = " . $situacao . ",
                    xml_requisicao = '" . $requisicao . "',
                    xml_resposta = '" . $resposta . "'
                WHERE
                    idTransacao = " . $idTransacao;
        $this->executaSql($sql);
    }

    public function salvarJsonTransacaoEnviada($requisicao)
    {
        $sql = "UPDATE " .
               static::TABELA . "
                SET
                    json = '" . mysql_real_escape_string(json_encode($requisicao)) . "'
                WHERE
                    idTransacao = " . $this->idTransacao;
        $this->executaSql($sql);
    }

    public function salvarErroTransacaoEnviada($idTransacao, $requisicao, $erro)
    {
        $sql = "UPDATE " .
               static::TABELA . "
                SET
                    situacao = " . self::SITUACAO_TRANSACAO_ERRO . ",
                    xml_requisicao = '" . $requisicao . "',
                    erro = '" . $erro . "'
                WHERE
                    idTransacao = " . $idTransacao;
        $this->executaSql($sql);
    }

    public function salvarJsonTransacaoResposta($resposta)
    {
        $sql = 'UPDATE ' .
               static::TABELA . '
            SET
                json_resposta = "' . mysql_real_escape_string(json_encode($resposta)) . '"
            WHERE
                idTransacao = ' . $this->idTransacao;
        return $this->executaSql($sql);
    }

    public function salvarTokenUsuario($usuarioToken)
    {
        $sql = 'UPDATE ' .
               static::TABELA . '
            SET
                usuario_token = "' . mysql_real_escape_string($usuarioToken) . '"
            WHERE
                idTransacao = ' . $this->idTransacao;
        return $this->executaSql($sql);
    }

    public function atualizarTransacao($post)
    {
        $sql = "UPDATE " .
               static::TABELA . "
                SET
                    _post = '" . mysql_real_escape_string(json_encode($post)) . "'
                WHERE
                    idTransacao = " . $this->idTransacao;

        return $this->executaSql($sql);
    }
}
