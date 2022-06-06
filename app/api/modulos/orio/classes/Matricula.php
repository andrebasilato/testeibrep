<?php

class Matricula
{
    private $funcoesComuns;
    private $acessoBanco;
    public  $config;
    public  $idmatricula;
    public  $idpessoa;
    public  $idusuario;

    const TIPO_PAGEMENTO_BOLETO = 1;

    public function __construct(\OrIO\FuncoesComuns $funcoesComuns)
    {
        $this->acessoBanco = $funcoesComuns->acessoBanco;
        $this->funcoesComuns = $funcoesComuns;
        $this->acessoBanco->ignorarTratamentoErro = true;
    }

    public function autenticar($email, $senha)
    {
        $sql = "SELECT
                    idusuario,
                    nome
                FROM
                    usuarios_adm
                WHERE
                    email='{$email}' AND
                    senha='{$senha}' AND
                    ativo='S' AND
                    ativo_login = 'S'";

        $usuario = $this->acessoBanco->retornarLinha($sql);

        if (!$usuario) {
            throw new \Exception("erro_senha_incorreta", 401);
        } else {
            return $usuario;
        }
    }

    public function cadastrar($dados)
    {
        if (empty($dados)) {
            throw new \Exception('dados_corrompidos', 400);
        }

        foreach($dados as $matricula) {

            $sql = "SELECT * FROM
                        ofertas
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        idoferta = ".$matricula['idoferta'];

            $oferta = $this->acessoBanco->retornarLinha($sql);

            if(!$oferta) {
                throw new \Exception("erro_oferta_invalida", 400);
            }

            $sql = "SELECT * FROM
                        cursos
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        idcurso = ".$matricula['idcurso'];

            $curso = $this->acessoBanco->retornarLinha($sql);

            if(!$curso) {
                throw new \Exception("erro_curso_invalido", 400);
            }

            $sql = "SELECT * FROM
                        polos
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        idpolo = ".$matricula['idpolo'];

            $polo = $this->acessoBanco->retornarLinha($sql);

            if(!$polo) {
                throw new \Exception("erro_polo_invalido", 400);
            }

            $ofertaCursoPolo = $this->verificarMatriculasCursoPolo($matricula['idoferta'], $matricula['idcurso'], $matricula['idpolo'], $matricula['idturma']);

            if($ofertaCursoPolo['erro']) {
                throw new \Exception($ofertaCursoPolo['erro']['mensagem'], $ofertaCursoPolo['erro']['codigo']);
            }

            if($ofertaCursoPolo['total'] == $ofertaCursoPolo['maximo_turma']) {
                throw new \Exception("erro_maximo_turma", 424);
            }

            require_once 'Pessoa.php';
            $pessoaObj = new Pessoa($this->funcoesComuns);
            $pessoa = $pessoaObj->retornarIdPorCPF($matricula['documentoaluno']);

            if($pessoa) {
                $matricula['idpessoa'] = $pessoa['idpessoa'];
                $pessoa = $pessoaObj->modificar($matricula);
            } else {
                $pessoa = $pessoaObj->cadastrar($matricula);
            }
            if($pessoa['erro']) {
                throw new \Exception($pessoa['erro']['mensagem'], $pessoa['erro']['codigo']);
            }

            $sql = "SELECT nome FROM
                        vendedores
                    WHERE
                        ativo = 'S' AND
                        ativo_login = 'S' AND
                        venda_bloqueada = 'N' AND
                        idvendedor = ".$matricula['idvendedor'];

            $vendedor = $this->acessoBanco->retornarLinha($sql);

            if(!$vendedor) {
                throw new \Exception("erro_vendedor_invalido", 400);
            }

            $sql = "SELECT * FROM
                        matriculas_workflow
                    WHERE
                        ativo = 'S' AND
                        ativa = 'S'
                    ORDER BY
                        idsituacao DESC LIMIT 1";
            $situacaoAtiva = $this->acessoBanco->retornarLinha($sql);
            $matricula["valor_contrato"] = str_replace('.', '', $matricula["valor_contrato"]);
            $matricula["valor_contrato"] = str_replace(',', '.', $matricula["valor_contrato"]);

            $bolsa = ($matricula["valor_contrato"] == 0) ? 'S' : 'N';

            $dataDiasParaAva = new DateTime();

            $oferta_curso_polo = $this->retornarOfertaCursoPolo($matricula['idoferta'], $matricula['idcurso'], $matricula['idpolo']);

            if ($oferta_curso_polo['dias_para_ava']) {
                $dataDiasParaAva->modify('+ ' . $oferta_curso_polo['dias_para_ava'] . ' days');
            }

            $dataLimiteAva = NULL;
            if ($oferta_curso_polo['data_limite_ava']) {
                $dataLimiteAva = new DateTime($oferta_curso_polo['data_limite_ava']);
            }

            if ($dataDiasParaAva && $dataLimiteAva) {
                if ($dataDiasParaAva > $dataLimiteAva) {
                    $data_limite_acesso_ava = $dataDiasParaAva->format('Y-m-d');
                } else {
                    $data_limite_acesso_ava = $dataLimiteAva->format('Y-m-d');
                }
            } elseif ($dataDiasParaAva) {
                $data_limite_acesso_ava = $dataDiasParaAva->format('Y-m-d');
            } else {
                $data_limite_acesso_ava = $dataLimiteAva->format('Y-m-d');
            }

            $sql = "SELECT
                        *
                    FROM
                        polos
                    WHERE
                        idpolo='" . $matricula["idpolo"] . "' AND
                        ativo = 'S'";
            $polo = $this->acessoBanco->retornarLinha($sql);

            $sql = "SELECT
                        *
                    FROM
                        instituicoes
                    WHERE
                        idinstituicao='" . $polo["idinstituicao"] . "' AND
                        ativo = 'S'";

            $instituicao = $this->acessoBanco->retornarLinha($sql);
            $sql = "SELECT
                        *
                    FROM
                        mantenedoras
                    WHERE
                        idmantenedora='" . $instituicao["idmantenedora"] . "' AND
                        ativo = 'S'";
            $mantenedora = $this->acessoBanco->retornarLinha($sql);

            $sql = "SELECT
                        COUNT(m.idmatricula) AS matricula_duplicada
                    FROM
                        matriculas m
                        INNER JOIN matriculas_workflow mw ON m.idsituacao = mw.idsituacao
                    WHERE
                        mw.inativa <> 'S' AND
                        mw.cancelada <> 'S' AND
                        m.ativo = 'S' AND
                        m.idpessoa = " . $pessoa["idpessoa"] . " AND
                        m.idoferta = " . $matricula["idoferta"] . " AND
                        m.idcurso = " . $matricula["idcurso"] . " AND
                        m.idpolo = " . $matricula["idpolo"];

            $matriculas = $this->acessoBanco->retornarLinha($sql);

            if ($matriculas['matricula_duplicada'] > 0) {
                throw new \Exception('matricula_duplicada', 302);
            }

            $sql = "SELECT
                              livre
                          FROM
                              cursos
                          WHERE
                              ativo = 'S' AND
                              idcurso = " . $matricula['idcurso'];

            $curso = $this->acessoBanco->retornarLinha($sql);

            if(! empty($curso)){
                if($curso['livre'] == 'S'){
                    $data_limite_acesso_ava = NULL;
                    $data_prolongada = '';
                    $aceito_curso = 'N';
                } else {
                    $data_prolongada = 'data_prolongada = "' . $data_limite_acesso_ava . '", ';
                    $aceito_curso = 'S';
                }
            }

            $sql = "INSERT INTO
                              matriculas
                          SET
                              data_cad = now(),
                              aceito_inicio_curso = '" . $aceito_curso . "',
                              contrato_gerado = 'N',
                              data_matricula = now(),
                              " . $data_prolongada . "
                              idmantenedora = '" . $mantenedora["idmantenedora"] . "',
                              idinstituicao = '" . $instituicao["idinstituicao"] . "',
                              idpessoa = " . $pessoa["idpessoa"] . ",
                              idoferta = " . $matricula["idoferta"] . ",
                              idcurso = " . $matricula["idcurso"] . ",
                              idpolo = " . $matricula["idpolo"] . ",
                              idturma = " . $matricula["idturma"] . ",
                              aprovado_comercial = 'S',
                              idsituacao = " . $situacaoAtiva["idsituacao"] . ",
                              modulo = 'gestor',
                              bolsa = '" . $bolsa . "',
                              observacao = NULL,
                              origem = 'AP',
                              data_registro = '" . date('Y-m-d') . "'";

            if ($matricula["financeiro"]) {
                if ($matricula["financeiro"]["forma_pagamento"] == "Cartão" || $matricula["financeiro"]["forma_pagamento"] == "Cartão de crédito") {
                    $sql .= ", forma_pagamento = 2,
                              autorizacao_cartao = '".$matricula['financeiro']['autorizacao_tid']."'";
                    $bandeira = $this->retornarIdBandeira($matricula["financeiro"]['bandeira']);
                    $sql .= ", idbandeira = '".$bandeira['idbandeira']."'";
                } else if ($matricula["financeiro"]["forma_pagamento"] == "Boleto") {
                    $sql .= ", forma_pagamento = 1";
                } else if ($matricula["financeiro"]["forma_pagamento"] == "Dinheiro") {
                    $sql .= ", forma_pagamento = 5";
                } else if ($matricula["financeiro"]["forma_pagamento"] == "Cheque") {
                    $sql .= ", forma_pagamento = 4";
                }
            }

            if ($bolsa == "S") {
                $sql .= ", valor_contrato = 0,
                           quantidade_parcelas = 0";
            } else if ($bolsa == "N") {
                $sql .= ", idsolicitante = NULL,
                            valor_contrato = " . $matricula["valor_contrato"] . ",
                            quantidade_parcelas = " . $matricula["financeiro"]["parcelas"];
            }

            $sql .= ", idusuario = " . $this->idusuario . ",
                       idvendedor = " . $matricula["idvendedor"];

            if ($this->acessoBanco->executaSql($sql)) {
                $this->idmatricula = mysql_insert_id();

                $sql = "INSERT INTO
                            matriculas_historicos
                        SET
                            idmatricula = '" . $this->idmatricula . "',
                            data_cad = now(),
                            tipo = 'situacao',
                            acao = 'modificou',
                            para = " . $situacaoAtiva['idsituacao'] . ",
                            idusuario = " . $this->idusuario;

                $this->acessoBanco->executaSql($sql);

                $sql = 'SELECT
                            idpessoa_instituicao,
                            ativo
                        FROM
                            pessoas_instituicoes
                        WHERE
                            idpessoa = ' . $pessoa['idpessoa'] . ' AND
                            idinstituicao = ' . $instituicao['idinstituicao'] . ' ';
                $pessoa_instituicao = $this->acessoBanco->retornarLinha($sql);

                if ($pessoa_instituicao['idpessoa_instituicao']) {
                    if ($pessoa_instituicao['ativo'] == 'N') {
                        $sql = 'UPDATE
                                    pessoas_instituicoes
                                SET
                                    ativo = "S"
                                WHERE
                                    idpessoa_instituicao = ' . $pessoa_instituicao['idpessoa_instituicao'];

                        $resultado = $this->acessoBanco->executaSql($sql);
                    }
                } else {
                    $sql = 'INSERT INTO
                                pessoas_instituicoes
                            SET
                                data_cad = NOW(),
                                idpessoa = ' . $pessoa['idpessoa'] . ',
                                idinstituicao = ' . $instituicao['idinstituicao'] . ' ';

                    $resultado = $this->acessoBanco->executaSql($sql);
                }

                $this->eviarEmailBoasVindas($this->idmatricula, $polo, $instituicao);

                $sql = 'SELECT
                            idcurso,
                            cofeci
                        FROM
                            cursos
                        WHERE
                            idcurso = ' . $matricula["idcurso"];
                $curso = $this->acessoBanco->retornarLinha($sql);

                if ($GLOBALS['config']['modulos']['url']['configuracoes']['logcofeci'] && $curso['cofeci'] == 'S') {
                    $logCofeci = new LogCofeci(new Core);
                    $logCofeci->registerLog(new ArrayObject(array(
                        'idmatricula' => $this->idmatricula,
                        'situacao' => 0,
                        'data_cad' => date('Y-m-d H:i:s')
                    )));
                }

                if ($matricula["financeiro"]["forma_pagamento"]) {
                    if($matricula["financeiro"]["forma_pagamento"] == "Cartão" || $matricula["financeiro"]["forma_pagamento"] == "Cartão de crédito") {
                        if (! $matricula["financeiro"]['bandeira']) {
                            throw new \Exception('bandeira_cartao_vazio', 400);
                        }
                        if (! $matricula["financeiro"]['autorizacao_tid']) {
                            throw new \Exception('autorizacao_cartao_vazio', 400);
                        }
                        if (! $matricula["financeiro"]["parcelas"]) {
                            throw new \Exception('financeiro_quantidade_parcelas_vazio', 400);
                        }
                        $bandeira = $this->retornarIdBandeira($matricula["financeiro"]['bandeira']);
                    }

                    if (! $matricula["financeiro"]["valor_parcela"]) {
                        throw new \Exception('financeiro_valor_parcela_vazio', 400);
                    }
                    if (! $matricula["financeiro"]["valor_total"]) {
                        throw new \Exception('financeiro_valor_vazio', 400);
                    }
                    if (! $matricula["financeiro"]["vencimento_primeira"] || ! $matricula["financeiro"]["vencimento_ultima"]) {
                        throw new \Exception('financeiro_vencimento_vazio', 400);
                    }
                    if (! $matricula["financeiro"]["situacao"]) {
                        throw new \Exception('financeiro_situacao_vazio', 400);
                    }

                    $sql = "SELECT
                                *
                            FROM
                                contas_workflow
                            WHERE
                                ativo = 'S' AND
                                nome LIKE '" . $matricula["financeiro"]["situacao"]."%'";

                    $situacaoFinanceiro = $this->acessoBanco->retornarLinha($sql);

                    $sql = "SELECT
                                *
                            FROM
                                eventos_financeiros
                            WHERE
                                ativo = 'S' AND
                                    nome LIKE '" . $matricula["financeiro"]["tipo"] ."'";

                    $tipoFinanceiro = $this->acessoBanco->retornarLinha($sql);

                    $sql = "INSERT INTO contas_relacoes SET data_cad = now()";
                    $this->acessoBanco->executaSql($sql);
                    $idRelacao = mysql_insert_id();
                    if (! intval($matricula["financeiro"]['quantidade_parcelas'])/* || $matricula["financeiro"]["forma_pagamento"] == "Boleto"*/) {
                        $matricula["financeiro"]['quantidade_parcelas'] = 1;
                    }

                    $valorParcela = !empty($matricula["financeiro"]["valor_parcela"]) ? $matricula["financeiro"]["valor_parcela"] : round($matricula["financeiro"]["valor_total"] / $matricula["financeiro"]['parcelas'], 2);
                    $valorPrimeiraParcela =  $valorParcela;
                    $data = explode("-", $matricula["financeiro"]["vencimento_primeira"]);
                    $matricula["financeiro"]['nome'] = 'Referente a uma parcela da matricula ' . $this->idmatricula;
                    $matricula["financeiro"]['parcelas'] = (int)$matricula["financeiro"]['parcelas'];

                    for ($parcela = 1; $parcela <= $matricula["financeiro"]['parcelas']; $parcela++) {
                        $matricula["financeiro"]['valor'] = $valorParcela;
                        if ($parcela == 1)
                            $matricula["financeiro"]['valor'] = $valorPrimeiraParcela;

                        $mes = ($data[1] + ($parcela - 1));
                        $dia = $data[2];

                        if ($mes == 2 && $dia >= 29) {
                            $dia = date("t", mktime(0, 0, 0, $mes, 1, $data[0]));
                        }

                        $vencimento = date("Y-m-d", mktime(0, 0, 0, $mes, $dia, $data[0]));

                        $matricula["financeiro"]['valor'] = str_replace('.', '', $matricula["financeiro"]['valor']);
                        $matricula["financeiro"]['valor'] = str_replace(',', '.', $matricula["financeiro"]['valor']);

                        $sql = "INSERT INTO
                                      contas
                                SET
                                      data_cad = now(),
                                      tipo = 'receita',
                                      nome = '" . $matricula["financeiro"]['nome'] . "',
                                      valor = " . $matricula["financeiro"]['valor'] . ",
                                      data_vencimento = '" . $vencimento . "',
                                      idsituacao = " . $situacaoFinanceiro['idsituacao'] . ",
                                      idrelacao = " . $idRelacao . ",
                                      idmantenedora = " . $mantenedora["idmantenedora"] . ",
                                      idinstituicao = " . $instituicao['idinstituicao'] . ",
                                      idmatricula = " . $this->idmatricula . ",
                                      idpessoa = " . $pessoa["idpessoa"] . ",
                                      idevento = " . $tipoFinanceiro['idevento'] . ",
                                      parcela = " . $parcela . ",
                                      total_parcelas = '" . $matricula["financeiro"]['parcelas'] . "' ";
                        if($matricula["financeiro"]["forma_pagamento"] == "Cartão" || $matricula["financeiro"]["forma_pagamento"] == "Cartão de crédito") {
                            $forma_pagamento = 2;
                            $sql .= ", forma_pagamento = 2,
                                    idbandeira = " . $bandeira['idbandeira'] . ",
                                    autorizacao_cartao = '" . $matricula["financeiro"]['autorizacao_tid'] . "'";
                        } elseif ($matricula["financeiro"]["forma_pagamento"] == "Boleto") {
                            $forma_pagamento = 1;
                            $sql .= ", forma_pagamento = 1";
                        } elseif ($matricula["financeiro"]["forma_pagamento"] == "Dinheiro") {
                            $forma_pagamento = 5;
                            $sql .= ", forma_pagamento = 5";
                        } else if ($matricula["financeiro"]["forma_pagamento"] == "Cheque") {
                            $banco = $this->retornarIdBanco($matricula["financeiro"]['banco_cheque']);
                            if(!$banco) {
                                throw new \Exception("erro_banco_invalido", 400);
                            }

                            $sql .= ", forma_pagamento = 4,
                            idbanco = " . $banco['idbanco'] . ",
                            agencia_cheque = '" . $matricula["financeiro"]['agencia_cheque'] . "',
                            cc_cheque = '" . $matricula["financeiro"]['cc_cheque'] . "',
                            numero_cheque = '" . str_pad(intval($matricula["financeiro"]['numero_cheque']), 6, '0', STR_PAD_LEFT) . "',
                            emitente_cheque = '" . $matricula["financeiro"]['emitente_cheque'] . "',
                            emitente_cpf = '" . $matricula["financeiro"]['emitente_cpf'] . "'";
                        }
                        $this->acessoBanco->executaSql($sql);
                        $idconta = mysql_insert_id();

                        $nossonumero = $idconta;
                        $sql = 'SELECT
                                    b.nome AS banco,
                                    b.codigo_banco AS banco_boleto,
                                    cci.idinstituicao,
                                    ".REM" AS extensao_arquivo,
                                    cc.numero_convenio AS convenio,
                                    cc.carteira,
                                    cc.agencia,
                                    cc.conta,
                                    cc.conta_dig,
                                    i.nome,
                                    cc.cod_transmissao,
                                    cc.empresa,
                                    cc.cnpj,
                                    i.bairro,
                                    cc.gera_remessa
                                FROM
                                    bancos b
                                    INNER JOIN contas_correntes cc ON (b.idbanco = cc.idbanco)
                                    INNER JOIN contas_correntes_instituicoes cci ON (cc.idconta_corrente = cci.idconta_corrente)
                                    INNER JOIN instituicoes i ON (cci.idinstituicao = i.idinstituicao)
                                WHERE
                                    cc.ativo = "S" AND
                                    cci.ativo="S"
                                ORDER BY cc.idconta_corrente DESC LIMIT 1';

                        $boletoConta = $this->acessoBanco->retornarLinha($sql);

                        if (! empty($boletoConta['gera_remessa']) && 'S' == $boletoConta['gera_remessa']) {
                            if ($boletoConta['banco_boleto'] == 341) {
                                $nossoNumero = str_pad($idconta, 8, '0', STR_PAD_LEFT);
                            } else {
                                $nossoNumero = str_pad($idconta, 7, '0', STR_PAD_LEFT);
                                $tamanho = strlen($nossoNumero);
                                $total = 0;
                                $valor = 2;
                                for ($i = ($tamanho - 1); $i >= 0; $i--) {
                                    if (10 == $valor) {
                                        $valor = 2;
                                    }
                                    $total += $nossoNumero[$i] * $valor;
                                    $valor++;
                                }
                                $resto = ($total % 11);
                                $retorno = 11 - $resto;
                                if ($resto == 0 || $resto == 1) {
                                    $retorno = $resto;
                                }
                                $nossoNumero .= $retorno;
                            }

                            $nossonumero = substr($nossoNumero, -8, 8);
                        }

                        $sql = "UPDATE contas SET nossonumero = '{$nossoNumero}' WHERE idconta = {$idconta}";
                        $this->acessoBanco->executaSql($sql);

                        $sql = "INSERT INTO
                                    matriculas_historicos
                                SET
                                    idmatricula = '" . $this->idmatricula . "',
                                    data_cad = now(),
                                    tipo = 'parcela',
                                    acao = 'cadastrou',
                                    idusuario = " . $this->idusuario . ",
                                    id = " . $idconta;

                        $this->acessoBanco->executaSql($sql);

                        //INÍCIO Classifica a conta em uma regra
                        $sql = 'SELECT
                                    idpolo,
                                    idcentro_custo,
                                    idcategoria,
                                    idsubcategoria
                                FROM
                                    contas
                                WHERE
                                    idconta = "' . $idconta . '" AND
                                    ativo = "S"';
                        $conta = $this->acessoBanco->retornarLinha($sql);

                        if (!$conta['idcentro_custo']) {
                            $sql = 'SELECT
                                        idcentro_custo
                                    FROM
                                        contas_centros_custos
                                    WHERE
                                        idconta = "' . $idconta . '" AND
                                        ativo = "S"
                                    ORDER BY
                                        porcentagem DESC
                                    LIMIT 1';
                            $contaCentroCustos = $this->acessoBanco->retornarLinha($sql);
                            $conta['idcentro_custo'] = $contaCentroCustos['idcentro_custo'];
                        }

                        $sql = 'SELECT
                                    ocdr.idregra
                                FROM
                                    obz_classificacao_despesa_regras ocdr
                                    INNER JOIN obz_classificacao_despesa ocd ON (ocd.idclassificacao = ocdr.idclassificacao AND ocd.ativo = "S" AND ocd.ativo_painel = "S")
                                WHERE
                                    ocdr.idpolo = "' . $conta['idpolo'] . '" AND
                                    ocdr.idcentro_custo = "' . $conta['idcentro_custo'] . '" AND
                                    ocdr.idcategoria = "' . $conta['idcategoria'] . '" AND
                                    ocdr.idsubcategoria = "' . $conta['idsubcategoria'] . '" AND
                                    ocdr.ativo = "S"';
                        $regra = $this->acessoBanco->retornarLinha($sql);

                        if ($regra['idregra']) {
                            $idregra = '"' . $regra['idregra'] . '"';
                        } else {
                            $idregra = 'NULL';
                        }

                        $sql = 'UPDATE
                                    contas
                                SET
                                    idregra = ' . $idregra . '
                                WHERE
                                    idconta = ' . $idconta;
                        $this->acessoBanco->executaSql($sql);
                        //FIM Classifica a conta em uma regra

                        /*$this->monitora_onde = 52;
                        $this->monitora_oque = 1;
                        $this->monitora_qual = $this->idconta;
                        $this->Monitora();*/
                    }

                }

            } else {
                throw new \Exception('erro_cadastro_matricula', 400);
            }
        }
        $retorno["codigo"] = 200;
        $retorno["mensagem"] = "Matrícula cadastrada com sucesso";

        return $retorno;
    }

    public function retornar()
    {
        if(empty($this->campos)) {
            $this->campos = "m.*, c.nome AS curso, c.imagem_exibicao_servidor, o.nome AS oferta, YEAR(m.data_matricula) AS ano";
        }

        $sql = "SELECT
                    " . $this->campos . "
                FROM
                    matriculas m
                    INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
                    INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                    INNER JOIN ofertas_cursos oc ON o.idoferta = oc.idoferta
                                                AND c.idcurso = oc.idcurso
                                                AND oc.possui_financeiro = 'S'
                    INNER JOIN polos p ON (m.idpolo = p.idpolo)
                    INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao)
                    INNER JOIN instituicoes i ON (m.idinstituicao = i.idinstituicao)
                    LEFT JOIN vendedores v ON (m.idvendedor = v.idvendedor)
                    LEFT JOIN motivos_cancelamento mc ON (m.idmotivo_cancelamento = mc.idmotivo)
                    LEFT JOIN empresas e ON (m.idempresa = e.idempresa)
                WHERE
                    m.ativo = 'S' AND
                    m.idmatricula = '" . intval($this->idmatricula) ."'";

        $matricula = $this->acessoBanco->retornarLinha($sql);
        return $matricula;
    }

    public function retornarBoletosEmAberto($idPessoa)
    {
        $matriculas = $this->retornarMatriculasAluno($idPessoa);
        $retorno = [];

        foreach ($matriculas as $key => $matricula) {
            $dadosFinanceiros = $this->retornaDadosFinanceiro((int) $matricula['idmatricula']);

            foreach ($dadosFinanceiros as $key => $dados) {
                $retorno[] = array(
                    'id' => $dados['idconta'],
                    'numero' => $dados['nossonumero'],
                    'valor' => $dados['valor'],
                    'referencia' => $dados['referencia'],
                    'quitado' => $dados['pago'],
                    'forma_pagamento' => $dados['forma_pagamento'].' - '.$GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$dados['forma_pagamento']]
                );
            }

        }

        return $retorno;
    }

    public function retornarCalendario($idPessoa)
    {
        $matriculas = $this->retornarMatriculasAluno($idPessoa);
        foreach ($matriculas as $key => $matricula) {
            if (!empty($matricula['idmatricula'])) {
                $this->idsMatriculas[] = $matricula['idmatricula'];
            }
        }
        $avas = $this->retornarAvasAluno($idPessoa);
        foreach ($avas as $key => $ava) {
            if (!empty($ava['idava'])) {
                $this->idsAvas[] = $ava['idava'];
            }
        }

        $eventos = $this->retornarChatsProvas();

        foreach ($eventos['provas'] as $key => $evento) {
            $retorno[] = array(
                'data_inicio' => $evento['data_realizacao'].' '.$evento['hora_realizacao_de'],
                'data_fim' => $evento['data_realizacao'].' '.$evento['hora_realizacao_ate'],
                'titulo' => 'Prova: '.$evento['curso'],
                'descricao' => $evento['descricao'],
                'polo' => $evento['polo'],
                'curso' => $evento['curso'],
            );
        }

        foreach ($eventos['chats'] as $key => $chat) {
            $search = $chat['idava'];
            $ind_ava = array_keys(
                array_filter(
                    $avas,
                    function ($value) use ($search) {
                        return (strpos($value['idava'], $search) !== false);
                    }
                )
            );
            if (isset($ind_ava[0])) {
                $polo = $avas[$ind_ava[0]]['polo'];
                $curso = $avas[$ind_ava[0]]['curso'];
            }
            $retorno[] = array(
                'data_inicio' => $chat['inicio_entrada_aluno'],
                'data_fim' => $chat['fim_entrada_aluno'],
                'titulo' => $chat['nome'],
                'descricao' => $chat['descricao'],
                'polo' => $polo,
                'curso' => $curso,
            );
        }
        usort($retorno, 'ordenarPorIndice($a,$b)');
        return $retorno;
    }

    public function retornarLocaisProvasDisponiveisAluno($idinstituicao)
    {
        $this->acessoBanco->sql = "SELECT
                            {$this->campos}
                        FROM
                            locais_provas l
                            INNER JOIN provas_presenciais_locais_provas prl ON (l.idlocal = prl.idlocal)
                            INNER JOIN provas_presenciais pp ON (prl.id_prova_presencial = pp.id_prova_presencial)
                        WHERE
                            l.idinstituicao = " . $idinstituicao . " and
                            pp.data_realizacao >= '" . date('Y-m-d') . "' and
                            pp.ativo_painel = 'S' and
                            pp.ativo = 'S' and
                            l.ativo = 'S' ";

        $this->acessoBanco->sql .= "GROUP BY l.idlocal ";
        $this->acessoBanco->ordem = "ASC";
        $this->acessoBanco->ordem_campo = "l.nome";
        $this->acessoBanco->limite = -1;
        return $this->acessoBanco->retornarLinhas();
    }

    public function retornarPolosProvasDisponiveisAluno($idinstituicao)
    {
        $this->acessoBanco->sql = "SELECT
							{$this->campos}
						FROM
							polos p
							INNER JOIN provas_presenciais_polos prpo ON (p.idpolo = prpo.idpolo)
							INNER JOIN provas_presenciais pp ON (prpo.id_prova_presencial = pp.id_prova_presencial)
						WHERE
							p.idinstituicao = " . $idinstituicao . " AND
							pp.data_realizacao >= '" . date('Y-m-d') . "' AND
							pp.ativo_painel = 'S' AND
							pp.ativo = 'S' AND
							p.ativo = 'S' AND
                            prpo.ativo = 'S' ";

        $this->acessoBanco->sql .= "GROUP BY p.idpolo ";
        $this->acessoBanco->ordem = "ASC";
        $this->acessoBanco->ordem_campo = "p.nome_fantasia";
        $this->acessoBanco->limite = -1;
        return $this->acessoBanco->retornarLinhas();
    }
    public function retornarProvas($aluno)
    {
        $this->acessoBanco->sql = "SELECT
                            {$this->campos}
                        FROM
                            pessoas pe
                        INNER JOIN matriculas m ON (m.idpessoa = pe.idpessoa)
                        INNER JOIN provas_presenciais_polos ppp ON (ppp.idpolo = m.idpolo)
                        INNER JOIN provas_presenciais p ON (p.id_prova_presencial = ppp.id_prova_presencial)
                        INNER JOIN disciplinas_cursos dc ON (dc.idcurso = m.idcurso)
                        WHERE
                            m.idpessoa = '".$aluno."'
                            AND p.data_realizacao > '" . date('Y-m-d') . "' AND
                            m.ativo = 'S' AND
                            p.ativo = 'S' AND
                            dc.ativo = 'S' ";

        $this->acessoBanco->sql .= "GROUP BY p.data_realizacao, p.id_prova_presencial , dc.iddisciplina , m.idpolo ";
        $this->acessoBanco->ordem = "ASC";
        $this->acessoBanco->ordem_campo = "p.data_realizacao , p.hora_realizacao_de, p.id_prova_presencial";
        $this->acessoBanco->limite = -1;
        return $this->acessoBanco->retornarLinhas();
    }

    public function retornarBoletim($idPessoa)
    {
        $matriculas = $this->retornarMatriculasAluno($idPessoa);
        $retornoNotas = [];
        $retornoDisciplina = [];
        foreach ($matriculas as $key => $matricula) {

            $disciplinas = $this->retornarDisciplinasCurso($matricula['idcurso']);

            foreach ($disciplinas as $key => $disciplina) {
                $notas = $this->retornarNotas($matricula['idmatricula'], $disciplina['iddisciplina']);
                $notas_final = array();
                foreach ($notas as $key => $nota) {
                    $num_nota = $key + 1;
                    $retornoNotas[] = array('periodo' => $num_nota.'ª Nota', 'nota' => $nota['nota']);
                    $notas_final[] = $nota['nota'];
                }
                if (count($retornoNotas) > 0) {
                    sort($notas_final);
                    $retornoNotas[] = array('periodo' => 'Nota final', 'nota' => end($notas_final), 'nota_final' => 'S');
                    $retornoDisciplina[] = array('id' => $disciplina['iddisciplina'], 'nome' => $disciplina['nome'], 'notas' => $retornoNotas);
                }
                $retornoNotas = [];
            }
            $dadosRetorno['matricula'] = $matricula['idmatricula'];
            $dadosRetorno['ano'] = $matricula['ano'];
            $dadosRetorno['nome'] = $matricula['curso'];
            $dadosRetorno['disciplinas'] = $retornoDisciplina;
            if (count($retornoDisciplina) > 0) {
                $retorno[] = $dadosRetorno;
            }
            $retornoDisciplina = [];
        }

        return $retorno;
    }

    private function retornarNotas($idMatricula, $idDisciplina)
    {
        $this->acessoBanco->sql = "SELECT
                *
            FROM
                matriculas_notas mn
            WHERE
                mn.idmatricula = '".$idMatricula."'
                AND mn.iddisciplina = '".$idDisciplina."'
                AND mn.ativo = 'S' ";
        $this->acessoBanco->ordem_campo = 'mn.data_cad';
        $this->acessoBanco->ordem = 'ASC';
        $this->acessoBanco->limite = -1;

        return $this->acessoBanco->retornarLinhas();
    }

    private function retornarDisciplinasCurso($idCurso)
    {
        $this->acessoBanco->sql = "select
                *
            FROM
                disciplinas_cursos dc
            INNER JOIN  disciplinas d ON dc.iddisciplina = d.iddisciplina
            WHERE
                dc.idcurso = '".$idCurso."'
                AND d.ativo = 'S' ";
        $this->acessoBanco->ordem_campo = 'dc.data_cad';
        $this->acessoBanco->ordem = 'DESC';
        $this->acessoBanco->limite = -1;

        return $this->acessoBanco->retornarLinhas();

    }

    public function retornarMatriculasAluno($idPessoa)
    {
        // Busca a situação de cancelada
        $situacaoCancelada = $this->retornarSituacaoCancelada();
        // Busca a situação de inativa
        $situacaoInativa = $this->retornarSituacaoInativa();

        $matriculas = array();
        $this->acessoBanco->sql = "SELECT
                            m.* ,
                            c.nome AS curso,
                            c.imagem_exibicao_servidor,
                            o.nome AS oferta,
                            YEAR(m.data_matricula) AS ano
                        FROM
                            matriculas m
                            INNER JOIN ofertas o ON (m.idoferta = o.idoferta)
                            INNER JOIN cursos c ON (m.idcurso = c.idcurso)
                            INNER JOIN ofertas_cursos oc ON o.idoferta = oc.idoferta
                                                        AND c.idcurso = oc.idcurso
                                                        AND oc.possui_financeiro = 'S'
                        WHERE
                            m.ativo = 'S' AND
                            m.idpessoa = " . intval($idPessoa) . " AND
                            m.idsituacao <> " . $situacaoCancelada["idsituacao"] . " AND
                            m.idsituacao <> " . $situacaoInativa["idsituacao"];

        $this->acessoBanco->ordem = "ASC";
        $this->acessoBanco->ordem_campo = "m.data_matricula";
        $this->acessoBanco->limite = -1;
        $matriculas = $this->acessoBanco->retornarLinhas();
        return $matriculas;
    }

    private function retornaDadosFinanceiro($idMatricula)
    {
        if (! is_int($idMatricula)) {
            return false;
        }

        $this->acessoBanco->sql = "SELECT
                        c.autorizacao_cartao,
                        c.data_pagamento,
                        c.data_vencimento,
                        c.agencia_cheque,
                        c.cc_cheque,
                        c.numero_cheque,
                        c.emitente_cheque,
                        c.forma_pagamento,
                        c.idsituacao,
                        bc.nome AS bandeira_cartao,
                        b.nome AS banco,
                        cw.nome AS situacao,
                        cw.pago,
                        cor_nome,
                        cor_bg,
                        pcm.valor AS valor_matricula,
                        cp.bandeira,
                        cp.tid,
                        m.idpedido,
                        m.data_cad as data_cad_matricula,
                        cp.data_pagamento,
                        c.valor,
                        c.idconta,
                        c.nossonumero,
                        concat(MONTH(c.data_vencimento), '/', YEAR(c.data_vencimento)) as referencia
                      FROM
                        contas c
                        INNER JOIN contas_workflow cw ON (c.idsituacao = cw.idsituacao)
                        INNER JOIN matriculas m ON m.idmatricula = c.idmatricula
                        LEFT OUTER JOIN contas_pagamentos cp ON (cp.idconta = c.idconta AND cp.status_transacao = 'CAP')
                        LEFT OUTER JOIN bandeiras_cartoes bc ON (c.idbandeira = bc.idbandeira)
                        LEFT OUTER JOIN bancos b ON (c.idbanco = b.idbanco)
                        LEFT OUTER JOIN pagamentos_compartilhados_matriculas pcm ON (c.idpagamento_compartilhado = pcm.idpagamento AND pcm.idmatricula = '$idMatricula' AND pcm.ativo = 'S')
                     WHERE
                        (
                            c.idmatricula = '$idMatricula' OR
                            pcm.idmatricula = '$idMatricula'
                        ) AND
                        c.ativo = 'S'" ;

        if ($this->mesAtual) {
            $this->acessoBanco->sql .= " AND MONTH(c.data_vencimento) = '".date('m')."' AND YEAR(c.data_vencimento) = '".date('Y')."' ";
        } else {
            if ($this->perido_de && !$this->perido_ate) {
                $this->acessoBanco->sql .= " AND c.data_vencimento >= '".$this->perido_de."' ";
            } elseif (!$this->perido_de && $this->perido_ate) {
                $this->acessoBanco->sql .= " AND c.data_vencimento <= '".$this->perido_ate."' ";
            } elseif($this->perido_de && $this->perido_ate) {
                $this->acessoBanco->sql .= " AND (c.data_vencimento >= '".$this->perido_de."' AND c.data_vencimento <= '".$this->perido_ate."') ";
            }
        }

        $this->acessoBanco->ordem = "ASC";
        $this->acessoBanco->ordem_campo = "c.data_vencimento";
        $this->acessoBanco->limite = -1;

        return $this->acessoBanco->retornarLinhas();
    }

    private function retornarSituacaoEmAbertoConta()
    {
        $sql = "select idsituacao FROM contas_workflow where emaberto = 'S' and ativo = 'S' limit 1 ";
        return $this->acessoBanco->retornarLinha($sql);
    }

    private function retornarSituacaoCancelada()
    {
        $sql = "select * FROM matriculas_workflow where ativo = 'S' and cancelada = 'S' order by idsituacao desc limit 1";
        return $this->acessoBanco->retornarLinha($sql);
    }

    private function retornarSituacaoInativa()
    {
        $sql = "select * FROM matriculas_workflow where ativo = 'S' and inativa = 'S' order by idsituacao desc limit 1";
        return $this->acessoBanco->retornarLinha($sql);
    }

    public function retornarChatsProvas()
    {
        $retorno['chats'] = array();
        if(count($this->idsAvas) > 0) {
            $this->acessoBanco->sql = "select
								ac.*,
                                a.nome as ava,
                                a.idava
							from
								avas_chats ac
                                inner join avas a on (ac.idava = a.idava)
							where
                                ac.ativo = 'S' and
                                a.idava in (".implode(',',$this->idsAvas).") and
                                ac.inicio_entrada_aluno >= now()";
            $this->acessoBanco->ordem_campo = 'ac.inicio_entrada_aluno asc, ac.idchat';
            $this->acessoBanco->ordem = 'desc';
            $this->acessoBanco->limite = -1;

            $retorno['chats'] = $this->acessoBanco->retornarLinhas();
        }

        $retorno['provas'] = array();
        if(count($this->idsMatriculas) > 0) {
            $this->acessoBanco->sql = "select
							pp.*,
							ps.id_solicitacao_prova,
							ps.idmatricula,
							ps.situacao,
							c.nome as curso,
							p.nome_fantasia as polo,
							mcsp.nome as motivo,
							mcsp.descricao
						from
							provas_solicitadas ps
							inner join provas_presenciais pp on (ps.id_prova_presencial = pp.id_prova_presencial and pp.ativo = 'S')
							inner join matriculas m on (ps.idmatricula = m.idmatricula)
							left outer join cursos c on (m.idcurso = c.idcurso)
							left outer join polos p on (ps.idpolo = p.idpolo)
							left outer join motivos_cancelamento_solicitacao_prova mcsp on (ps.idmotivo = mcsp.idmotivo)
						where
							ps.idmatricula in(".implode(',',$this->idsMatriculas).") and
                            ps.ativo = 'S' and
                            pp.data_realizacao >= now()";
            $this->acessoBanco->ordem_campo = 'pp.data_realizacao asc, pp.id_prova_presencial';
            $this->acessoBanco->ordem = 'desc';
            $this->acessoBanco->limite = -1;
            $retorno['provas'] = $this->acessoBanco->retornarLinhas();
        }

        return $retorno;
    }

    public function retornarAvasAluno($idpessoa)
    {

        $retorno = array();

        $this->acessoBanco->sql = "SELECT
                        oca.*,
                        m.idmatricula,
                        c.idcurso,
                        c.nome as curso,
                        p.nome_fantasia as polo
					FROM
						matriculas m
						inner join ofertas_cursos_polos ocp on (m.idoferta = ocp.idoferta and m.idcurso = ocp.idcurso and m.idpolo = ocp.idpolo and ocp.ativo = 'S')
                        inner join ofertas_curriculos_avas oca on (ocp.idoferta = oca.idoferta and ocp.idcurriculo = oca.idcurriculo and oca.ativo = 'S')
                        INNER JOIN cursos c ON m.idcurso = c.idcurso
                        INNER JOIN polos p ON m.idpolo = p.idpolo
					WHERE
						m.idpessoa = ".$idpessoa." and
						m.ativo = 'S'
					GROUP BY oca.idava";
        $this->acessoBanco->ordem_campo = 'oca.idava';
        $this->acessoBanco->ordem = 'asc';
        $this->acessoBanco->limite = -1;
        return $this->acessoBanco->retornarLinhas();
    }

    public function retornarColegas($idAva, $idPessoa, $busca = null)
    {
        $dataAtual = new DateTime();
        $dataAtualFormatada = $dataAtual->format('Y-m-d');

        $this->acessoBanco->sql = '
                    SELECT
                        p.nome,
                        p.data_nasc as data_nascimento,
                        p.telefone,
                        p.email
                    FROM pessoas p
                    INNER JOIN colegas_classe cc ON cc.idpessoa = p.idpessoa
                    INNER JOIN cidades c ON p.idcidade = c.idcidade
                    INNER JOIN estados e ON p.idestado = e.idestado
                    LEFT OUTER JOIN paises pa ON p.idpais = pa.idpais
                    WHERE cc.idava = ' . $idAva.'
                    AND p.idpessoa <> '. $idPessoa .'
                    AND p.ativo = "S"
                    GROUP BY p.idpessoa
                    ';

        if ($busca) {
            $this->acessoBanco->sql .= ' AND p.nome LIKE "%'.$busca.'%"';
        }

        $this->acessoBanco->ordem_campo = "p.nome";
        $this->acessoBanco->ordem = "ASC";
        $this->acessoBanco->groupby = "idpessoa";
        $this->acessoBanco->limite = 40;

        return $this->acessoBanco->retornarLinhas();
    }

    public function salvarSolicitacaoProvaPresencial()
    {
        $matricula = $this->retornar();
        if (!empty($this->post['idpolo'])) {
            $idpolo = $this->post['idpolo'];
            $this->campos = 'p.*';
            $polos = $this->retornarPolosProvasDisponiveisAluno($matricula['idinstituicao']);

            $polos_prova = array_keys(
                array_filter(
                    $polos,
                    function ($value) use ($idpolo) {
                        return (strpos($value['idpolo'], $idpolo) !== false);
                    }
                )
            );
            if (count($polos_prova) == 0) {
                throw new \Exception("polo_nao_disponivel", 400);
            }

        } elseif (!empty($this->post['idlocal'])) {
            $idlocal = $this->post['idlocal'];
            $this->campos = 'l.*';
            $locais = $this->retornarLocaisProvasDisponiveisAluno($matricula['idinstituicao']);

            $locais_provas = array_keys(
                array_filter(
                    $locais,
                    function ($value) use ($idlocal) {
                        return (strpos($value['idlocal'], $idlocal) !== false);
                    }
                )
            );
            if (count($locais_provas) == 0) {
                throw new \Exception("local_nao_disponivel", 400);
            }
        } else {
            throw new \Exception("param_nao_informados", 400);
        }

        $matriculas = $this->retornarMatriculasAluno($this->idpessoa);

        $search = $this->idmatricula;
        $matriculas_aluno = array_keys(
            array_filter(
                $matriculas,
                function ($value) use ($search) {
                    return (strpos($value['idmatricula'], $search) !== false);
                }
            )
        );


        if (count($matriculas_aluno) == 0) {
            throw new \Exception("matricula_nao_encontrada", 400);
        }

        $informacoes = $this->retornarCursoPolo();

        if (!count($this->post['disciplinas'])) {
            throw new \Exception("param_nao_informados", 400);
        }

        if (!$this->verificarProvasPresenciais($this->post["idprova_presencial"])) {
            throw new \Exception("erro_data_indisponivel", 400);
        }

        if (!$this->arquivosObrigatoriosAssociados($this->idmatricula, $informacoes['idinstituicao'])) {
            throw new \Exception("ter_documento_obrigatorios", 400);
        }

        if($this->verificarSolicitacoesDuplicadas($informacoes)){
            throw new \Exception("solicitacoes_duplicadas", 400);
        }

        $this->acessoBanco->executaSql('START TRANSACTION');

        $sql = 'INSERT INTO
                            provas_solicitadas
                        SET
                            data_cad = NOW(),
                            ativo = "S",
                            situacao = "E",
                            idmatricula = "'.(int) $this->idmatricula.'",
                            idcurso = "'.$informacoes['idcurso'].'",
                            id_prova_presencial = "'.$this->post["idprova_presencial"].'" ';
        if ($idpolo)  {
            $sql .= ', idpolo = "'.$idpolo.'" ';
        } elseif ($idlocal)  {
            $sql .= ', idlocal = "'.$idlocal.'" ';
        }

        if ($this->acessoBanco->executaSql($sql)) {
            $id_solicitacao_prova = mysql_insert_id();
            foreach ($this->post['disciplinas'] as $disciplina) {
                $total_disciplinas['total'] = count(
                    $this->retornaDisciplinasTaxa(
                        [
                            "1|psd.iddisciplina" => $disciplina,
                            "4|ps.situacao" => 'C'
                        ]
                    )
                );

                $sql_disciplinas = 'INSERT INTO provas_solicitadas_disciplinas SET
                                        id_solicitacao_prova = "' . $id_solicitacao_prova . '",
                                        iddisciplina =  "' . $disciplina . '",
                                        ativo = "S",
                                        data_cad = NOW() ';
                if (intval($total_disciplinas['total']) == 0) {
                    $sql_disciplinas .= ", pago_taxa_reprova = 'S' ";
                }

                $resultado = $this->acessoBanco->executaSql($sql_disciplinas);
                if (!$resultado) {
                    throw new \Exception("erro_inserir_disciplina", 500);
                }
            }

            $this->retorno['sucesso'] = true;

            $this->acessoBanco->executaSql('COMMIT');
        } else {
            throw new \Exception("erro_salvar_solicitacao", 500);
        }
        return $this->retorno;
    }

    private function retornarCursoPolo()
    {
        $sql = "SELECT m.idpolo,
                            m.idcurso,
                            m.idinstituicao,
                            po.quantidade_pessoas_comportadas as qtde_pessoas
                    FROM matriculas m
                    INNER JOIN polos po
                        ON (po.idpolo = m.idpolo)
                    WHERE
                        m.idmatricula = '".(int) $this->idmatricula."'";

        return $this->acessoBanco->retornarLinha($sql);
    }

    private function arquivosObrigatoriosAssociados($idMatricula, $idInstituicao)
    {
        $this->acessoBanco->sql = "SELECT
            td.idtipo
          FROM
            tipos_documentos td
          where
            td.ativo = 'S' and td.ativo_painel = 'S' and
            (td.idtipo in(SELECT idtipo FROM tipos_documentos_instituicoes_agendamento where idtipo = td.idtipo and idinstituicao = " . $idInstituicao . " and ativo = 'S')
            or
            td.todas_instituicoes_obrigatorio_agendamento = 'S')
          group by
            td.idtipo";
        $this->acessoBanco->limite = -1;
        $this->acessoBanco->ordem_campo = false;
        $this->acessoBanco->ordem = false;
        $tipos = $this->acessoBanco->retornarLinhas();

        if (!count($tipos)) {
            return true;
        }

        foreach ($tipos as $tipo) {
            $sql = "SELECT count(*) as total FROM matriculas_documentos where idmatricula = " . $idMatricula . " and idtipo = " . $tipo["idtipo"] . " and ativo = 'S' and situacao = 'aprovado' and idtipo_associacao is null";
            $totalDocumento = $this->acessoBanco->retornarLinha($sql);
            if ($totalDocumento["total"] <= 0) {
                return false;
            }
        }
        return true;
    }

    private function verificarSolicitacoesDuplicadas($informacoes)
    {
        $sql = "SELECT
                    COUNT(*) AS total
                FROM provas_solicitadas WHERE
                idcurso = '".$informacoes['idcurso']."' AND
                ativo = 'S' AND
                situacao = 'E' AND
                id_prova_presencial = '".$this->post['idprova_presencial']."' AND
                idmatricula = '".(int) $this->idmatricula."'";

        $provas = mysql_fetch_assoc(mysql_query($sql));

        if($provas['total'] > 0){
            return true;
        }
        return false;
    }

    private function retornaDisciplinasTaxa($arrayWhere = ["1|psd.pago_taxa_reprova" => 'N'])
    {
        $this->acessoBanco->sql = "SELECT
            psd.id_solicitacao_prova_disciplina, d.nome AS disciplinas, pp.data_realizacao,
            ps.situacao, pp.hora_realizacao_de, pp.hora_realizacao_ate, ps.data_cad,
            ps.id_solicitacao_prova
            FROM provas_solicitadas ps
            INNER JOIN provas_presenciais pp ON (ps.id_prova_presencial = pp.id_prova_presencial)
            INNER JOIN provas_solicitadas_disciplinas psd ON (psd.id_solicitacao_prova = ps.id_solicitacao_prova)
            INNER JOIN disciplinas d ON (d.iddisciplina = psd.iddisciplina)
            INNER JOIN matriculas ma ON (ma.idmatricula = ps.idmatricula)
            INNER JOIN ofertas_turmas_instituicoes oti ON (oti.idoferta = ma.idoferta
                AND oti.idturma=ma.idturma
                AND oti.idinstituicao=ma.idinstituicao)
            INNER JOIN ofertas_cursos_polos ocp ON (ocp.idoferta = ma.idoferta
                AND ocp.idcurso=ma.idcurso
                AND ocp.idpolo=ma.idpolo)
            INNER JOIN curriculos_blocos cb ON (ocp.idcurriculo = cb.idcurriculo)
            INNER JOIN curriculos_blocos_disciplinas cbd ON (cb.idbloco = cbd.idbloco
                AND cbd.iddisciplina = d.iddisciplina)
            WHERE
            ma.idmatricula = {$this->idmatricula} AND
            cbd.taxa_reprova = 'S' AND
            oti.taxa_reprova = 'S' AND
            psd.ativo = 'S' AND
            oti.valor_taxa IS NOT NULL";
        foreach ($arrayWhere as $ind => $val) {
            $ind = explode('|', $ind);
            $comparador = "";
            if ($ind[0] == 4) {
                $comparador = "<>";
            } elseif ($ind[0] == 1) {
                $comparador = "=";
            }
            $this->acessoBanco->sql .= " AND {$ind[1]} {$comparador} '{$val}' ";
        }
        $this->acessoBanco->sql .= "GROUP BY psd.id_solicitacao_prova_disciplina ORDER BY ps.data_cad ASC";
        //echo $this->sql."<br><br>";
        $this->acessoBanco->ordem_campo = NULL;
        $this->acessoBanco->ordem = NULL;
        $this->acessoBanco->groupby = NULL;
        $this->acessoBanco->limite = -1;
        return (array) $this->acessoBanco->retornarLinhas();
    }

    private function verificarProvasPresenciais($idProvaPresencial)
    {
        $sql = "SELECT
                    COUNT(*) AS total
                FROM provas_presenciais WHERE
                ativo = 'S'
                AND data_realizacao >= CURDATE()
                AND id_prova_presencial = '".$idProvaPresencial."' ";
        $provas = mysql_fetch_assoc(mysql_query($sql));

        if($provas['total'] > 0){
            return true;
        }

        return false;

    }

    private function verificarMatriculasCursoPolo($idoferta, $idcurso, $idpolo, $idturma)
    {
        if (!(int)$idoferta || !(int)$idcurso || !(int)$idpolo || !(int)$idturma) {
            $retorno['erro'] = true;
            $retorno['erro']['mensagem'] = 'erro_dados_corrompidos';
            $retorno['erro']['codigo'] = '400';
            return $retorno;
        }

        $sql_instituicao = 'SELECT
                                idinstituicao
                            FROM
                                polos
                            WHERE
                                idpolo = ' . $idpolo;

        $instituicao = $this->acessoBanco->retornarLinha($sql_instituicao);

        $sql = 'SELECT
                    COUNT(1) AS total,
                    (SELECT
                        oci.limite
                     FROM
                        ofertas_cursos_polos ocp
                        INNER JOIN polos p ON ocp.idpolo = p.idpolo
                        INNER JOIN ofertas_cursos_instituicoes oci ON ocp.idoferta = oci.idoferta AND ocp.idcurso = oci.idcurso AND p.idinstituicao = oci.idinstituicao AND oci.ativo = "S"
                     WHERE
                        ocp.idoferta = ' . $idoferta . ' AND
                        ocp.idcurso = ' . $idcurso . ' AND
                        p.idpolo = ' . $idpolo . ' AND
                        ocp.ativo = "S") AS maximo_turma
                  FROM
                      matriculas
                  WHERE
                      idoferta = ' . $idoferta . ' AND
                      idcurso = ' . $idcurso . ' AND
                      idinstituicao = ' . $instituicao['idinstituicao'] . ' AND
                      idturma = ' . $idturma . ' AND
                      ativo = "S" ';

        $resultado = $this->acessoBanco->executaSql($sql);
        if (!$resultado) {
            $retorno['erro'] = true;
            $retorno['erro']['mensagem'] = 'erro_verificar_matriculas';
            $retorno['erro']['codigo'] = '400';
            return $retorno;
        }
        $matriculas = mysql_fetch_assoc($resultado);
        return $matriculas;
    }

    private function retornarOfertaCursoPolo($idoferta, $idcurso, $idpolo)
    {
        $sql = 'SELECT
                          *
                      FROM
                        ofertas_cursos_polos oc
                      WHERE
                        idcurso = ' . $idcurso . ' AND
                        idpolo = ' . $idpolo . ' AND
                        idoferta = ' . $idoferta . ' AND
                        ativo = "S" ';
        return $this->acessoBanco->retornarLinha($sql);
    }

    private function retornarIdBandeira($bandeira)
    {
        $sql = "SELECT
                    idbandeira
                FROM
                    bandeiras_cartoes
                WHERE
                    ativo = 'S' AND
                    ativo_painel = 'S' AND
                    nome LIKE '".$bandeira."%'";

        return $this->acessoBanco->retornarLinha($sql);
    }

    private function retornarIdBanco($banco)
    {
        $sql = "SELECT
                    idbanco
                FROM
                    bancos
                WHERE
                    ativo = 'S' AND
                    ativo_painel = 'S' AND
                    nome = '" . $banco . "'";

        return $this->acessoBanco->retornarLinha($sql);
    }

    private function eviarEmailBoasVindas($idmatricula, $polo, $instituicao)
    {
        $sql = 'SELECT
                    *
                FROM
                    matriculas
                WHERE
                    idmatricula = ' . $idmatricula;

        $matricula = $this->acessoBanco->retornarLinha($sql);

        if ($matricula['idmatricula']) {
            $sql = 'SELECT
                        *
                    FROM
                        pessoas
                    WHERE
                        idpessoa = ' . $matricula['idpessoa'];
            $pessoa = $this->acessoBanco->retornarLinha($sql);

            $sql = 'SELECT
                        *
                    FROM
                        ofertas
                    WHERE
                        idoferta = ' . $matricula['idoferta'];
            $oferta = $this->acessoBanco->retornarLinha($sql);

            $sql = "SELECT
                        c.*,
                        ci.email_boas_vindas_instituicao,
                        ci.sms_boas_vindas_instituicao
                    FROM
                        cursos c
                        LEFT JOIN cursos_instituicoes ci ON c.idcurso = ci.idcurso AND ci.ativo = 'S' AND ci.idinstituicao = '" . $matricula['idinstituicao'] . "'
                    WHERE
                        c.idcurso = " . $matricula['idcurso'];
            $curso = $this->acessoBanco->retornarLinha($sql);

            if ($curso['email_boas_vindas_instituicao'])
                $emailBoasVindas = $curso['email_boas_vindas_instituicao'];
            else
                $emailBoasVindas = $curso['email_boas_vindas'];

            $dataMatriculaTrinta = NULL;
            if ($matricula['data_matricula']) {
                $dataMatriculaTrinta = new DateTime($matricula['data_matricula']);
                $dataMatriculaTrinta->modify('+30 days');
                $matricula['data_matricula'] = $dataMatriculaTrinta->format('d-m-Y');
            }

            if ($emailBoasVindas) {
                $emailBoasVindas = str_ireplace("[[MATRICULA]]", $matricula['idmatricula'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[NUMERO_CONTRATO]]", $matricula['numero_contrato'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[VALOR_CONTRATO]]", number_format($matricula['valor_contrato'], 2, ',', '.'), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[FORMA_PAGAMENTO]]", ($GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula['forma_pagamento']]), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[QUANTIDADE_PARCELAS]]", $matricula['quantidade_parcelas'], $emailBoasVindas);

                $nome = explode(' ', $pessoa['nome']);
                $emailBoasVindas = str_ireplace("[[NOME_ALUNO]]", ($pessoa['nome']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[PRIMEIRO_NOME_ALUNO]]", $nome[0], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[ID_ALUNO]]", $pessoa['idpessoa'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[CPF_CNPJ_ALUNO]]", $pessoa['documento'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[EMAIL_ALUNO]]", $pessoa['email'], $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[CURSO]]", ($curso['nome']), $emailBoasVindas);

                $emailBoasVindas = str_ireplace("[[OFERTA]]", ($oferta['nome']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[POLO]]", ($polo['nome']), $emailBoasVindas);
                $emailBoasVindas = str_ireplace("[[INSTITUICAO]]", ($instituicao['nome']), $emailBoasVindas);

                $emailBoasVindas = str_ireplace("[[DATA_MATRICULA_MAIS_TRINTA]]", ($matricula['data_matricula']), $emailBoasVindas);

                $emailBoasVindas = html_entity_decode(htmlentities($emailBoasVindas));

                $nomeDe = ($GLOBALS['config']['tituloSistema'] . ' - ' . $GLOBALS['config']['tituloEmpresa']);
                if ($curso['email']) {
                    $emailDe = $curso['email'];
                } else {
                    $emailDe = $GLOBALS['config']['emailSistema'];
                }
                $assunto = 'BEM VINDO AO CURSO';
                $nomePara = ($pessoa['nome']);
                $emailPara = $pessoa['email'];

                $this->enviarEmail($nomeDe, $emailDe, $assunto, $emailBoasVindas, $nomePara, $emailPara);

            }

            if ($curso['sms_boas_vindas_instituicao'])
                $smsBoasVindas = $curso['sms_boas_vindas_instituicao'];
            else
                $smsBoasVindas = $curso['sms_boas_vindas'];

            if ($smsBoasVindas && $pessoa['celular'] && $GLOBALS['config']['integrado_com_sms']) {
                $smsBoasVindas = str_ireplace("[[MATRICULA]]", $matricula['idmatricula'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[NUMERO_CONTRATO]]", $matricula['numero_contrato'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[VALOR_CONTRATO]]", number_format($matricula['valor_contrato'], 2, ',', '.'), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[FORMA_PAGAMENTO]]", ($GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$matricula['forma_pagamento']]), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[QUANTIDADE_PARCELAS]]", $matricula['quantidade_parcelas'], $smsBoasVindas);

                $nome = explode(' ', $pessoa['nome']);
                $smsBoasVindas = str_ireplace("[[NOME_ALUNO]]", ($pessoa['nome']), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[PRIMEIRO_NOME_ALUNO]]", $nome[0], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[ID_ALUNO]]", $pessoa['idpessoa'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[CPF_CNPJ_ALUNO]]", $pessoa['documento'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[EMAIL_ALUNO]]", $pessoa['email'], $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[CURSO]]", ($curso['nome']), $smsBoasVindas);

                $smsBoasVindas = str_ireplace("[[OFERTA]]", ($oferta['nome']), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[POLO]]", ($polo['nome']), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[INSTITUICAO]]", ($instituicao['nome']), $smsBoasVindas);
                $smsBoasVindas = str_ireplace("[[DATA_MATRICULA_MAIS_TRINTA]]", ($matricula['data_matricula']), $smsBoasVindas);

                $smsBoasVindas = html_entity_decode($smsBoasVindas);

                if ($smsBoasVindas) {
                    $this->enviarSms($matricula['idmatricula'], 'M', $pessoa['nome'], $pessoa['celular'], $smsBoasVindas, $pessoa['idpessoa']);
                }
            }
        }
    }

    private function enviarEmail($nomeDe, $emailDe, $assunto, $mensagem, $nomePara, $emailPara, $layout = "layout", $charset = 'iso-8859-1') {
        if (!$emailPara) {
            return false;
        }

        if ($this->config['email_naoresponda'] && $this->config['email_host'] && $this->config['email_port']) {
            $diretorio = dirname(__FILE__);
            require_once $diretorio."/../../../../classes/PHPMailer/class.phpmailer.php";
            $mail = new PHPMailer;

            $mail->setLanguage('br');
            $mail->CharSet = 'utf-8';

            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = $this->config['email_host'];
            $mail->Port = $this->config['email_port'];
            $mail->SMTPSecure = $this->config['email_secure'];

            $mail->SMTPAuth = false;
            if($this->config['email_username'] && $this->config['email_password']) {
                $mail->SMTPAuth = true;
                $mail->Username = $this->config['email_username'];
                $mail->Password = $this->config['email_password'];
            }

            $mail->isHTML(true); // Set email format to HTML
            // Busca o layout do email
            $layoutAux = file($diretorio."/../../../../assets/email/" . $layout . ".html");
            $layoutHTML = "";
            foreach ($layoutAux as $linha => $valor) {
                $layoutHTML .= $valor;
            }
            $layoutHTML = str_replace("[[MENSAGEM]]", html_entity_decode($mensagem), $layoutHTML);
            $layoutHTML = str_replace("[[URLSISTEMA]]", $this->config["urlSistema"], $layoutHTML);

            $sql = "INSERT INTO
                        emails_log
                    SET
                        data_cad = now(),
                        de_nome = '".mysql_real_escape_string(($nomeDe))."',
                        de_email = '".$emailDe."',
                        para_nome = '".mysql_real_escape_string(($nomePara))."',
                        para_email = '".$emailPara."',
                        assunto = '".mysql_real_escape_string(($assunto))."',
                        layout = '".$layout."',
                        mensagem = '".mysql_real_escape_string((html_entity_decode($mensagem)))."',
                        cabecalho = ''";

            $log = mysql_query($sql);

            if (!$log) {
                $retorno['erro'] = true;
                $retorno['erro']['mensagem'] = 'erro_log_email';
                $retorno['erro']['codigo'] = '206';
                return $retorno;
            }

            $idLog = mysql_insert_id();

            $layoutHTML .= "<img src='" . $this->config['urlSistema'] . "/api/set/img_confirmacao/" . $idLog . ".png'>";

            $mail->setFrom($this->config['email_naoresponda'], $nomeDe);
            $mail->addReplyTo($emailDe, $nomeDe);
            $mail->addAddress($emailPara, $nomePara);
            $mail->Subject = $assunto;
            $mail->Body    = $layoutHTML;

            $enviado = $mail->send();

            if(!$enviado) {
                $sql = 'UPDATE
                            emails_log
                        SET
                            enviado = "N",
                            erro = "'.$mail->ErrorInfo.'"
                        WHERE idemail = '.$idLog;

                $log = mysql_query($sql);

                if (!$log) {
                    $retorno['erro'] = true;
                    $retorno['erro']['mensagem'] = 'erro_log_email';
                    $retorno['erro']['codigo'] = '206';
                    return $retorno;
                }
            }

            $mail->ClearAllRecipients();
            $mail->ClearAttachments();
            return $enviado;
        }
        return false;
    }

    private function enviarSms($idchave, $origem, $nome, $celular, $sms, $idpessoa)
    {

        $diretorio = dirname(__FILE__);
        require_once $diretorio.'/../../../../classes/sms.class.php';

        $smsobj = new Sms();

        $smsobj->Set('idchave', $idchave);
        $smsobj->Set('origem', $origem);

        $smsobj->Set('url_webservicesms', $GLOBALS['config']['linkapiSMS']);

        $contaSms = $this->retornarContaSms($idpessoa);
        if ($contaSms) { //conta SMS local
            $dados_gateway = array(
                'loginSMS' => $contaSms['login'],
                'tokenSMS' => $contaSms['token'],
                'celular' => $celular,
                'nome' => $nome,
                'mensagem' => $sms
            );
        } else {
            $dados_gateway = array( //conta SMS geral
                'loginSMS' => $GLOBALS['config']['loginSMS'],
                'tokenSMS' => $GLOBALS['config']['tokenSMS'],
                'celular' => $celular,
                'nome' => $nome,
                'mensagem' => $sms
            );
        }

        $smsobj->Set('dado_seguro', $dados_gateway);
        $smsobj->ExecutaIntegraSMS();
    }

    private function retornarContaSms($id)
    {

        $sql = "SELECT
                  i.sms, i.sms_login as login, i.sms_token as token , m.idinstituicao
                FROM
                  matriculas m
                INNER JOIN instituicoes i ON (i.idinstituicao = m.idinstituicao)
                WHERE m.idpessoa = " . $id;

        $contaSms = $this->acessoBanco->retornarLinha($sql);

        if ($contaSms['sms'] == 'S') {
            return $contaSms;
        }

        return false;
    }

    private function ordenarPorIndice($a, $b)
    {
        $campo = 'data_inicio';
        return strcasecmp($a[$campo], $b[$campo]);
    }

    public function retornarMatriculaAlteracao()
    {
        $retorno = [];

        require_once 'Pessoa.php';
        $pessoaObj = new Pessoa($this->funcoesComuns);
        $ontem = \DateTime::createFromFormat('Y-m-d', (new \DateTime())->format('Y-m-d'))
            ->modify('-1 days');

        $this->acessoBanco->sql = "SELECT
                                      idmatricula
                                   FROM
                                      matriculas_historicos
                                   WHERE
                                      data_cad >= '" . $ontem->format('Y-m-d') . "'
                                   GROUP BY idmatricula";

        $this->acessoBanco->ordem_campo = "data_cad";
        $this->acessoBanco->ordem = "ASC";
        $this->acessoBanco->limite = -1;
        $matriculas = $this->acessoBanco->retornarLinhas();

        $pessoaObj->campos = '
            p.idpessoa as idaluno,
            p.nome,
            p.sexo as genero,
            p.data_nasc as data_nascimento,
            c.nome as cidade,
            e.nome as estado,
            p.email,
            p.celular,
            p.escolaridade,
            p.data_cad as data_registro,
            p.ultimo_acesso
            ';

        foreach ($matriculas as $matricula) {
            $linha = [];
            $this->idmatricula = $matricula['idmatricula'];
            $dados_matricula = $this->retornar();

            $parcelas = $this->retornarParcelas();
            foreach ($parcelas as $parcela) {
                $dados_matricula['parcelas'][$parcela['idconta']]['situacao'] = $parcela['situacao'];
            }

            $documentos = $this->retornarDocumentos();
            foreach ($documentos as $documento) {
                $dados_matricula['documentos'][$documento['iddocumento']]['tipo'] = $documento['tipo'];
                $dados_matricula['documentos'][$documento['iddocumento']]['nome'] = $documento['nome'];
            }

            $dados_matricula['data_ultima_modificacao'] = formataData($this->retornarDataUltimaModificacao(), 'br', 1);

            $pessoaObj->id = $dados_matricula['idpessoa'];
            $dados_pessoa = $pessoaObj->retornar(false);

            unset($dados_matricula['idpessoa']);
            $dados_matricula['data_matricula'] = formataData($dados_matricula['data_matricula'], 'br', 1);
            $dados_matricula['data_registro'] = formataData($dados_matricula['data_registro'], 'br', 0);
            $dados_matricula['data_conclusao'] = formataData($dados_matricula['data_conclusao'], 'br', 0);

            $dados_pessoa['genero'] = ($dados_pessoa['genero'] == 'F') ? 'Feminino' : 'Masculino';
            $dados_pessoa['data_nascimento'] = formataData($dados_pessoa['data_nascimento'], 'br', 0);
            $dados_pessoa['data_registro'] = formataData($dados_pessoa['data_registro'], 'br', 1);
            $dados_pessoa['ultimo_acesso'] = formataData($dados_pessoa['ultimo_acesso'], 'br', 1);
            $dados_pessoa['endereco_completo'] = $dados_pessoa['logradouro'] . ' ' . $dados_pessoa['endereco'] . ', ' . $dados_pessoa['numero'] . ', ' . $dados_pessoa['bairro'] . ' - ' . $dados_pessoa['cep'] . ', ' . $dados_pessoa['cidade'] . ', ' . $dados_pessoa['estado'];
            unset($dados_pessoa['logradouro'], $dados_pessoa['endereco'], $dados_pessoa['numero'], $dados_pessoa['bairro'], $dados_pessoa['cep'], $dados_pessoa['cidade'], $dados_pessoa['estado']);

            $retorno[] = ['matricula' => $dados_matricula, 'aluno' => $dados_pessoa];
        }

        return $retorno;
    }

    private function retornarParcelas() {
        $this->acessoBanco->sql = 'SELECT
                                        c.idconta, cw.nome AS situacao
                                    FROM
                                        contas c
                                            INNER JOIN
                                        contas_workflow cw ON (c.idsituacao = cw.idsituacao)
                                    WHERE
                                        c.idmatricula = ' . $this->idmatricula;

        $this->acessoBanco->ordem_campo = "c.idconta";
        $this->acessoBanco->ordem = "ASC";
        $this->acessoBanco->limite = -1;
        return $this->acessoBanco->retornarLinhas();
    }

    private function retornarDocumentos() {
        $this->acessoBanco->sql = 'SELECT
                                        md.iddocumento, td.nome as tipo, md.arquivo_nome as nome
                                    FROM
                                        matriculas_documentos md
                                            INNER JOIN
                                        tipos_documentos td ON (md.idtipo = td.idtipo)
                                    WHERE
                                        md.idmatricula = ' . $this->idmatricula;

        $this->acessoBanco->ordem_campo = "md.iddocumento";
        $this->acessoBanco->ordem = "ASC";
        $this->acessoBanco->limite = -1;
        return $this->acessoBanco->retornarLinhas();
    }

    private function retornarDataUltimaModificacao() {
        $this->acessoBanco->sql = 'SELECT
                                        data_cad
                                    FROM
                                        matriculas_historicos
                                    WHERE
                                        idmatricula = ' . $this->idmatricula .'
                                    ORDER BY
                                        data_cad
                                    DESC
                                    LIMIT 1';

        return $this->acessoBanco->retornarLinha($this->acessoBanco->sql)['data_cad'];
    }
}
