<?php
/**
 * `Pedidos da loja`
 *
 * @author     Yuri Costa-Silva    <yuric@alfamaweb.com.br>
 *
 * @package    Oráculo
 * @copyright  Copyright (c) 2016 Alfama Web (http://alfamaweb.com.br)
 * @license    Proprietary AlfamaWeb
 * @version    $Id$
 */
class Loja_Pedidos extends Core
{

    const ID_RIO_GRANDE_SUL = 21;
    const ID_PARANA = 16;
    const ID_MARANHAO = 10;
    const ID_MATO_GROSSO_DO_SUL = 12;

    /**
     * Retorna um única linha do banco de dados
     *
     * @return array
     */
    public function retornar()
    {
        $this->sql = 'SELECT ' . $this->campos . ' FROM loja_pedidos WHERE  idpedido = ' . $this->id . ' AND ativo = "S"';
        return $this->retornarLinha($this->sql);
    }

    public function cadastrarMatricula($idPedido)
    {
        if (empty($idPedido)) {
            $retorno['erro'] = true;
            $retorno['mensagem'] = 'parametros_incompletos';
            return $retorno;
        }

        $this->executaSql('BEGIN');

        $this->id = $idPedido;
        $pedido = $this->retornar(); 
        if (empty($pedido['idpedido'])) {
            $retorno['erro'] = true;
            $retorno['mensagem'] = 'pedido_nao_encontrado';
        }

        if ($pedido['valor_final'] > 0) {
            $this->sql = 'SELECT idsituacao FROM matriculas_workflow WHERE ativo = "S" AND inicio = "S"
                ORDER BY idsituacao DESC LIMIT 1';
            $situacaoInicio = $this->retornarLinha($this->sql);

            if (! $situacaoInicio['idsituacao']) {
                $this->executaSql('ROLLBACK');

                $retorno['erro'] = true;
                $retorno['mensagem'] = 'workflow_inicio_matricula_nao_existe';
                return $retorno;
            }

            $idSituacao = $situacaoInicio['idsituacao'];
        } else {
            $this->sql = 'SELECT idsituacao FROM matriculas_workflow WHERE ativo = "S" AND ativa = "S"
                ORDER BY idsituacao DESC LIMIT 1';
            $situacaoAtiva = $this->retornarLinha($this->sql);

            if (! $situacaoAtiva['idsituacao']) {
                $this->executaSql('ROLLBACK');

                $retorno['erro'] = true;
                $retorno['mensagem'] = 'workflow_ativa_matricula_nao_existe';
                return $retorno;
            }

            $idSituacao = $situacaoAtiva['idsituacao'];
        }

        $this->limite = -1;
        // $this->ordem = 'DESC';
        // $this->ordem_campo = 'financeiro'; 
        // tabela loja_pedidos_cursos não existe no ibreptran, acho q funciona fazendo um join de loja_pedidos com ofertas_cursos_escolas por idoferta,idcurso e idescola
        // $this->sql = 'SELECT * FROM loja_pedidos_cursos WHERE idpedido = ' . $pedido['idpedido'];
        $this->sql = 'select o.*, p.idturma, p.idpedido, p.modulo, oc.possui_financeiro as financeiro
                      from ofertas_cursos_escolas o left join loja_pedidos p on (o.idescola = p.idescola and o.idcurso = p.idcurso and o.idoferta = p.idoferta)
                                                    left join ofertas_cursos oc on (oc.idoferta = o.idoferta and oc.idcurso = o.idcurso)
                      WHERE p.idpedido = ' . $pedido['idpedido'];
        $ofertasCursos = $this->retornarLinhas($this->sql);

        unset($this->ordem, $this->ordem_campo);

        // require_once DIR_APP . '/classes/loja.produtos.class.php'; // classe ñ tem no ibreptran
        // $lojaProdutoObj = new Loja_Produtos;
        // $quantidadeRedacoes = $lojaProdutoObj->set('campos', 'quantidade_redacoes')
        //     ->set('id', $pedido['idproduto'])
        //     ->retornar();

        // $quantidadeRedacoes = ! empty($quantidadeRedacoes['quantidade_redacoes']) ? $quantidadeRedacoes['quantidade_redacoes'] : "null";

        foreach ($ofertasCursos as $ofertaCurso) { 
            $matriculaNova = true;

            $this->sql = 'SELECT idescola, nome_fantasia AS nome, idsindicato, idestado, detran_codigo FROM escolas
                WHERE idescola = ' . $ofertaCurso['idescola'] . ' AND ativo = "S"';
            $escolas = $this->retornarLinha($this->sql);

            $this->sql = 'SELECT idsindicato, nome, gerente_email, gerente_nome, idmantenedora FROM sindicatos
                WHERE idsindicato = ' . $escolas['idsindicato'] . ' AND ativo = "S"';
            $sindicato = $this->retornarLinha($this->sql);

            $this->sql = 'SELECT idmantenedora FROM mantenedoras
                WHERE idmantenedora = ' . $sindicato['idmantenedora'] . ' AND ativo = "S"';
            $mantenedora = $this->retornarLinha($this->sql);

            $valor_contrato = 0;
            $forma_pagamento = 'NULL'; 
            
            if ($ofertaCurso['financeiro'] == 'S' && $pedido['valor_final'] > 0) {
                $valor_contrato = $pedido['valor_final'];

                if (!empty($this->post['tipo_pagamento'])) { 
                    $forma_pagamento = $GLOBALS['tipo_pagamento_loja'][$this->post['tipo_pagamento']];
                }
            }

            $matriculaExistente = $this->retornarMatriculaExistente(
                $pedido['idpessoa'],
                $ofertaCurso['idescola'],
                $ofertaCurso['idoferta'],
                $ofertaCurso['idcurso']
            );

            if (! empty($matriculaExistente)) {
                $matriculaNova = false;
                $dadosNovos['idpedido'] = $pedido['idpedido'];
                // $dadosNovos['idoferta_curso_escola'] = $ofertaCurso['idoferta_curso_escola']; // substituindo idpedido_curso
                $dadosNovos['valor_contrato'] = $valor_contrato;
                // $dadosNovos['quantidade_redacoes'] = $quantidadeRedacoes;
                $dadosNovos['data_matricula'] = date('d/m/Y');

                $associou = $this->atualizarPedidoMatriculaExistente(
                    $matriculaExistente,
                    $dadosNovos
                );

                if (! $associou) {
                    $retorno['erro'] = true;
                    $retorno['mensagem'] = 'erro_associar_matricula_antiga';
                    return $retorno;
                }

                $idmatricula = $matriculaExistente['idmatricula'];
            }

            if ($matriculaNova) {
                include("../classes/vendedores.class.php");
                include("../classes/detran.class.php");
                $vendedorObj = new Vendedores();
                $detranObj = new Detran();
                $estadosDetran = $detranObj->listarEstadosIntegrados();

                $vendedorObj->set('campos', 'idvendedor');
                $vendedor = $vendedorObj->retornarVendedorPadrao();

                $numero_contrato = 'V' . $pedido['idpedido'] . '/' . $ofertaCurso['idoferta_curso_escola']; // substituindo idpedido_curso
                if ($ofertaCurso['modulo'] == 'loja') {
                    $numero_contrato = 'L' . $pedido['idpedido'] . '/' . $ofertaCurso['idoferta_curso_escola']; // substituindo idpedido_curso
                }

                $detranSituacao = 'LI';
                if (! empty($escolas['detran_codigo']) && in_array($escolas['idestado'], $estadosDetran)) {
                    $detranSituacao = 'AL';
                }

                if ($escolas['idestado'] == self::ID_RIO_GRANDE_SUL || $escolas['idestado'] == self::ID_PARANA) {
                    $detranSituacao = 'AL';
                }

                $this->sql = "SELECT * FROM cursos WHERE idcurso='" . $ofertaCurso['idcurso'] . "' and ativo = 'S'";
                $curso = $this->retornarLinha($this->sql);

                /* Verifica se o curso é de Reciclagem de Condutores Infratores e se o estado do CFC for o estado de maranhão */
                if (($escolas['idestado'] == self::ID_MARANHAO) && $curso['codigo'] == 'REC'){
                    $detranSituacao = 'LI';
                }

                // $_POST['input_parcela']
                $idmatricula = $this->inserirMatricula(
                    array (
                        'idmantenedora' => $mantenedora['idmantenedora'],
                        'idsindicato' => $sindicato['idsindicato'], // substituindo idinstituicao
                        'idpessoa' => $pedido['idpessoa'],
                        'idoferta' => $ofertaCurso['idoferta'],
                        'idcurso' => $ofertaCurso['idcurso'],
                        'idescola' => $ofertaCurso['idescola'],
                        'idturma' => $ofertaCurso['idturma'],
                        'idsituacao' => $idSituacao,
                        'modulo' => $ofertaCurso['modulo'],
                        'numero_contrato' => $numero_contrato,
                        'forma_pagamento' => $forma_pagamento,
                        // 'bolsa' => $bolsa,
                        'valor_contrato' => $valor_contrato,
                        'idvendedor' => $vendedor['idvendedor'],
                        'idpedido' => $pedido['idpedido'],
                        'detran_situacao' => $detranSituacao,
                        // 'idpedido_curso' => $ofertaCurso['idpedido_curso'],
                        // 'quantidade_redacoes' => $quantidadeRedacoes,
                        // 'pago' => ( ($bolsa == 'S') ? 'S' : 'N'),
                        // 'idsolicitante' => ( (!empty($idsolicitante)) ? $idsolicitante : 'NULL'),
                    )
                );
                $this->AdicionarHistorico($idmatricula, $idSituacao);

                //INÍCIO Cria o contrato do aluno e envia email na função gerarContrato
                $matriculaObj = new Matriculas;
                $matriculaObj->post['idcontrato'] = $ofertaCurso['idcontrato'];
                $matriculaObj->id = $idmatricula;
                $matriculaObj->modulo = $ofertaCurso['modulo'];
                $gerar = $matriculaObj->gerarContrato();
                //FIM Cria o contrato do aluno e envia email na função gerarContrato
            }

            $this->cancelarPedidosPessoaTemCurso(
                $pedido['idpessoa'],
                $ofertaCurso['idescola'],
                $ofertaCurso['idoferta'],
                $ofertaCurso['idcurso']
            );

            if (
                $ofertaCurso['financeiro'] == 'S'
                && $pedido['valor_final'] > 0
            ) {
                $this->sql = 'SELECT idsituacao FROM contas_workflow WHERE ativo = "S" AND emaberto = "S"
                    ORDER BY idsituacao DESC LIMIT 1';
                $situacaoEmAberto = $this->retornarLinha($this->sql);

                if (! $situacaoEmAberto['idsituacao']) {
                    $this->executaSql('ROLLBACK');

                    $retorno['erro'] = true;
                    $retorno['mensagem'] = 'workflow_inicio_conta_nao_existe';
                    return $retorno;
                }

                $this->sql = 'SELECT idevento FROM eventos_financeiros WHERE ativo = "S" AND mensalidade = "S"
                    ORDER BY idevento DESC LIMIT 1';
                $eventoMensalidade = $this->retornarLinha($this->sql);

                if (! $eventoMensalidade['idevento']) {
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

                $parcelas = (isset($this->post['input_parcela']) and !empty($this->post['input_parcela'])) ? $this->post['input_parcela'] : 1;

                $this->sql = 'INSERT INTO contas
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
                                idmatricula = ' . $idmatricula . ',
                                idpessoa = ' . $pedido['idpessoa'] . ',
                                idescola = ' . $ofertaCurso['idescola'] . ',
                                idevento = ' . $eventoMensalidade['idevento'] . ',
                                parcela = 1,
                                total_parcelas = '.$parcelas.',
                                forma_pagamento = ' . $forma_pagamento;
                                // idproduto = ' . $pedido['idoferta'] . ',

                $this->executaSql($this->sql);
                $this->post['idconta'] = mysql_insert_id();
            }

            $this->associarPessoaSindicato($pedido['idpessoa'], $sindicato['idsindicato']);

            if (! empty($sindicato['gerente_email']) && $matriculaNova) {
                $this->sql = 'SELECT * FROM pessoas WHERE idpessoa = '.$pedido['idpessoa'];
                $pessoa = $this->retornarLinha($this->sql);

                $nomePara = utf8_decode($sindicato["gerente_nome"]);

                $message  = "Ol&aacute; <strong>".$nomePara."</strong>,
                            <br /><br />
                            Uma nova matr&iacute;cula foi realizada na loja vitual.
                            <br /><br />
                            Contrato: #" . $numero_contrato . "<br />
                            Matr&iacute;cula: #" . $idmatricula . "<br />
                            Aluno: " . $pessoa['nome'] . "
                            <br /><br />
                            <a href=\"http://" . $_SERVER["SERVER_NAME"] . "/gestor/academico/matriculas/" . $idmatricula . "/administrar\">
                                Clique aqui
                            </a> para acessar a matr&iacute;cula.
                            <br /><br />";

                $emailPara = $sindicato['gerente_email'];
                $assunto = utf8_decode('Nova matrícula #' . $idmatricula);

                $nomeDe = $GLOBALS['config']['tituloEmpresa'];
                $emailDe = $GLOBALS['config']['emailSistema'];

                $this->enviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara);
            }
        }
        $this->executaSql('COMMIT');

        $matriculaObj->eviarEmailBoasVindas($matriculaObj->id, $escolas, $sindicato);

        return ['sucesso' => true, 'idmatricula' => $idmatricula, 'idconta' => $this->post['idconta']];
    }

    /**
     * [associarPessoaSindicato]
     * @param  [integer] $idpessoa
     * @param  [integer] $idsindicato
     * @return 
     */
    private function associarPessoaSindicato($idpessoa, $idsindicato)
    {
        $this->sql = 'SELECT idpessoa_sindicato, ativo FROM pessoas_sindicatos WHERE idpessoa = '.$idpessoa.' AND idsindicato = '.$idsindicato;
        $pessoaInstituicao = $this->retornarLinha($this->sql);
        if ($pessoaInstituicao['idpessoa_sindicato']) {
            if ($pessoaInstituicao['ativo'] == 'N') {
                $this->sql = 'UPDATE pessoas_sindicatos SET ativo = "S" WHERE idpessoa_sindicato = ' . $pessoaInstituicao['idpessoa_sindicato'];
            }
        } else {
            $this->sql = 'INSERT INTO pessoas_sindicatos SET data_cad = NOW(), idpessoa = '.$idpessoa.', idsindicato = '.$idsindicato;
        }

        return $this->executaSql($this->sql);
    }

    /**
     * [cancelarPedidosPessoaTemCurso]
     * @param  [integer] $idpessoa
     * @param  [integer] $idescola
     * @param  [integer] $idoferta
     * @param  [integer] $idcurso 
     * @return [array]
     */
    public function cancelarPedidosPessoaTemCurso($idpessoa, $idescola, $idoferta, $idcurso)
    {
        if (empty($idpessoa) || empty($idescola) || empty($idoferta) || empty($idcurso)) {
            $retorno['erro'] = true;
            $retorno['mensagem'] = 'parametros_incompletos';
            return $retorno;
        }

        // INNER JOIN loja_pedidos_cursos lpc ON (lpc.idpedido = lp.idpedido)
        $this->sql = 'SELECT
                lp.idpedido
            FROM
                loja_pedidos lp
                INNER JOIN ofertas_cursos_escolas oce ON (oce.idescola = lp.idescola and oce.idcurso = lp.idcurso and oce.idoferta = lp.idoferta)
            WHERE
                lp.idpessoa = ' . $idpessoa . ' AND
                oce.idescola = ' . $idescola . ' AND
                oce.idoferta = ' . $idoferta . ' AND
                oce.idcurso = ' . $idcurso . ' AND
                lp.situacao = "A" AND
                (SELECT COUNT(m.idmatricula) FROM matriculas m WHERE m.idpedido = lp.idpedido) = 0
            GROUP BY lp.idpedido';

        $this->ordem_campo = 'lp.idpedido';
        $this->ordem = 'ASC';
        $this->limite = -1;

        $pedidos = $this->retornarLinhas();

        $pedidosCancelar = [];
        foreach ($pedidos as $ind => $var) {
            $pedidosCancelar[] = $var['idpedido'];
        }

        $retorno = true;
        if (count($pedidosCancelar)) {
            $this->sql = 'UPDATE loja_pedidos SET situacao = "C"
                WHERE idpedido IN (' . implode(',', $pedidosCancelar) . ')';

            $retorno = $this->executaSql($this->sql);
        }

        return $retorno;
    }

    /**
     * [AdicionarHistorico]
     * @param [integer] $idmatricula
     * @param [integer] $para: idsituacao atual
     */
    public function AdicionarHistorico($idmatricula, $para)
    {
        $this->sql = 'INSERT INTO
                        matriculas_historicos
                    SET
                        data_cad = NOW(),
                        idmatricula = '.$idmatricula.',
                        tipo = "situacao",
                        acao = "modificou",
                        para = '.$para;
        return $this->executaSql($this->sql);
    }

    /**
     * [inserirMatricula]
     * @param  [array] $dados
     * @return [integer]
     */
    private function inserirMatricula($dados)
    {
        $this->sql = 'INSERT INTO matriculas
            SET data_cad = NOW(),
                data_matricula = DATE_FORMAT(NOW(), "%Y-%m-%d"),
                idmantenedora = ' . $dados['idmantenedora'] . ',
                idsindicato = ' . $dados['idsindicato'] . ',
                idpessoa = ' . $dados['idpessoa'] . ',
                idoferta = ' . $dados['idoferta'] . ',
                idcurso = ' . $dados['idcurso'] . ',
                idescola = ' . $dados['idescola'] . ',
                idturma = ' . $dados['idturma'] . ',
                aprovado_comercial = "N",
                idsituacao = ' . $dados['idsituacao'] . ',
                modulo = "' . $dados['modulo'] . '",
                numero_contrato = "' . $dados['numero_contrato'] . '",
                data_registro = DATE_FORMAT(NOW(), "%Y-%m-%d"),
                forma_pagamento = ' . $dados['forma_pagamento'] . ',
                valor_contrato = ' . $dados['valor_contrato'] . ',
                quantidade_parcelas = 1,
                idpedido = ' . $dados['idpedido'] . ',
                idvendedor = ' . $dados['idvendedor'] . ',
                detran_situacao = "' . $dados['detran_situacao'] . '"';
                // idpedido_cursos = ' . $dados['idpedido_cursos'];
                // idvendedor = ' . $dados['idvendedor'] . ',
                // pago = "' . $dados['pago'] . '",
                // bolsa = "' . $dados['bolsa'] . '",
                // idsolicitante = ' . $dados['idsolicitante'] . ',
                 // . ',quantidade_redacoes = ' . $dados['quantidade_redacoes'];

        $this->executaSql($this->sql);

        return mysql_insert_id();
    }

    /**
     * [atualizarPedidoMatriculaExistente]
     * @param  [array] $matricula  
     * @param  [array] $dadosNovos 
     * @return [boolean] 
     */
    private function atualizarPedidoMatriculaExistente($matricula, $dadosNovos)
    {
                // idpedido_cursos = '{$dadosNovos['idpedido_cursos']}',
                // quantidade_redacoes = '{$dadosNovos['quantidade_redacoes']}',
        $sql = "
            UPDATE
                matriculas
            SET
                idpedido = '{$dadosNovos['idpedido']}',
                valor_contrato = '{$dadosNovos['valor_contrato']}',
                data_matricula = '" . formataData($dadosNovos['data_matricula'], "en", 0)  . "',
                data_registro = '" . formataData($dadosNovos['data_matricula'], "en", 0)  . "',
                data_prolongada = NULL
            WHERE idmatricula = {$matricula["idmatricula"]}";

        $inseriu = $this->executaSql($sql);

        if (! $inseriu) {
            return false;
        }

        // $quantidadeRedacoes = (int) $dadosNovos['quantidade_redacoes'];
        $matriculaObj = new Matriculas();
        $matriculaObj->set('id', $matricula['idmatricula']);
        $matriculaObj->adicionarHistorico(null, 'pedido', 'modificou', $matricula['idpedido'], $dadosNovos['idpedido']);
        // if (! empty($quantidadeRedacoes)) {
        //     $matriculaObj->adicionarHistorico(null, 'quantidade_redacoes', 'modificou', $matricula['quantidade_redacoes'], $quantidadeRedacoes);
        // }
        $matriculaObj->adicionarHistorico(null, 'valor_contrato', 'modificou', $matricula['valor_contrato'], $dadosNovos['valor_contrato']);
        $matriculaObj->adicionarHistorico(null, 'data_matricula', 'modificou', $matricula['data_matricula'], formataData($dadosNovos['data_matricula'], "en", 0));
        $matriculaObj->adicionarHistorico(null, 'data_registro', 'modificou', $matricula['data_registro'], formataData($dadosNovos['data_matricula'], "en", 0));
        if (! empty($matricula['data_prolongada'])) {
            $matriculaObj->adicionarHistorico(null, 'data_prolongada', 'modificou', $matricula['data_prolongada'], null);
        }

        return true;
    }

    /**
     * [retornarMatriculaExistente]
     * @param  [integer] $idPessoa
     * @param  [integer] $idEscola
     * @param  [integer] $idOferta
     * @param  [integer] $idCurso 
     * @return [array] 
     */
    public function retornarMatriculaExistente($idPessoa, $idEscola, $idOferta, $idCurso)
    {
        $this->sql = '
            SELECT
                m.*, c.nome as curso
            FROM
                matriculas m
            INNER JOIN
                matriculas_workflow mw ON (m.idsituacao = mw.idsituacao)
            INNER JOIN
                cursos c ON (m.idcurso=c.idcurso)
            WHERE
                m.idpessoa = ' . $idPessoa . ' AND
                m.idescola = ' . $idEscola . ' AND
                m.idoferta = ' . $idOferta . ' AND
                m.idcurso = ' . $idCurso . ' AND
                m.ativo = "S" AND
                mw.inativa <> "S" AND
                mw.cancelada <> "S"';

        return $this->retornarLinha($this->sql);
    }

    /**
     * [enviarNotificacao]
     * @param  [array] $dadosArray
     * @return [type]
     */
    public function enviarNotificacao($dadosArray)
    {
        $nome = explode(' ', $dadosArray['pessoa']['nome']);

        $message = 'Olá ' . $nome[0] . ', <br/><br/>';
        $message .= 'Sua matrícula foi realizada com sucesso! Devido ao sistema de compensação, o seu acesso ao curso poderá ser liberado em até 3 dias.<br/><br/>';
        $message .= 'Acesse seu Ambiente Virtual de Aprendizagem clicando no link: <a href = "' . $GLOBALS['config']['urlSistema'] . '/aluno">' . $GLOBALS['config']['urlSistema'] . '/aluno</a>';

        if (isset($dadosArray['fastconnect']['data']['link_pdf'])) { // se for boleto
            $message .= '
                    <br/><br/>
                    <a href="' . $dadosArray['fastconnect']['data']['link_pdf'] . '">Clique aqui</a>, para gerar seu boleto de pagamento.
                ';
        }

        if ($dadosArray['pessoa']['novoCadastro']) {
            $message .= '
                    <br/><br/>
                    Dados de acesso:
                    <br/>
                    <strong>E-mail de acesso:</strong> ' . $dadosArray['pessoa']["email"] . '
                    <br/>
                    <strong>Senha de acesso:</strong> ' . $dadosArray['pessoa']['novaSenha'];
        }

        $message .= '<br/><br/><small>Todas as transações são realizadas pelo ?pagseguro? e são criptografadas, as informações do cartão de crédito nunca são armazenadas.</small>';

        $nomePara = $dadosArray['pessoa']['nome'];
        $emailPara = $dadosArray['pessoa']['email'];
        $assunto = 'MINHA MATRÍCULA - ' . $GLOBALS['config']['tituloEmpresa'];

        $nomeDe = $GLOBALS['config']['tituloEmpresa'];
        $emailDe = $GLOBALS['config']['emailSistema'];

        $this->enviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara, 'layout', 'utf-8');
    }

    public function cadastrar($idpessoa, $idOferta, $idCurso, $idEscola, $idTurma, $valor)
    {
        if (
            ! is_numeric($idpessoa)
            || ! is_numeric($idOferta)
            || ! is_numeric($idCurso)
            || ! is_numeric($idEscola)
            || ! is_numeric($idTurma)
            || ! is_numeric($valor)
        ) {
            throw new Exception('Parâmetros inválidos.');
        }

        $this->executaSql('BEGIN');

        $matriculaObj = new Matriculas();
        $verificaMatriculado = $matriculaObj->verificaMatriculado(
            (int) $idpessoa,
            (int) $idOferta,
            (int) $idCurso,
            (int) $idEscola
        );

        if ($verificaMatriculado['total'] > 0) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'aluno_matriculado';
            return $retorno;
        }

        $situacao = 'A';
        if ($valor <= 0) {
            $valor = 0;
            $situacao = 'P';
        }

        $dadosdousuario = retornaSOBrowser();

        $this->sql = 'INSERT INTO
                        loja_pedidos
                    SET
                        data_cad = NOW(),
                        idpessoa = '.$idpessoa.',
                        idoferta = ' . $idOferta . ',
                        idcurso = ' . $idCurso . ',
                        idescola = ' . $idEscola . ',
                        idturma = ' . $idTurma . ',
                        valor_final = '.$valor.',
                        situacao = "' . $situacao . '",
                        ip = "'.$dadosdousuario['ip'].'",
                        navegador = "'.mysql_escape_string($dadosdousuario['navegador']).'",
                        sistema_operacional = "'.mysql_escape_string($dadosdousuario['so']).'",
                        navegador_versao = "'.mysql_escape_string($dadosdousuario['navegador_versao']).'",
                        user_agent = "'.mysql_escape_string($dadosdousuario['user_agent']).'"';

        $this->executaSql($this->sql);

        $idPedido = mysql_insert_id();

        $this->executaSql('COMMIT');

        return $idPedido;
    }

    public function cancelarPedidosSemMatricula()
    {
        $dataInicial = (new DateTime)->modify('-7 days')->format('Y-m-d');

        $this->sql = 'SELECT lp.idpedido FROM loja_pedidos lp WHERE DATE_FORMAT(data_cad, "%Y-%m-%d") <= "' . $dataInicial . '" AND
            situacao = "A" AND (SELECT COUNT(m.idmatricula) FROM matriculas m WHERE m.idpedido = lp.idpedido) = 0';

        $this->ordem_campo = 'lp.idpedido';
        $this->ordem = 'ASC';
        $this->limite = -1;

        $pedidos = $this->retornarLinhas();

        $pedidosCancelar = [];
        foreach ($pedidos as $ind => $var) {
            $pedidosCancelar[] = $var['idpedido'];
        }


        $retorno = true;
        if (count($pedidosCancelar)) {
            $this->sql = 'UPDATE loja_pedidos SET situacao = "C"
                WHERE idpedido IN (' . implode(',', $pedidosCancelar) . ')';

            $retorno = $this->executaSql($this->sql);
        }

        return $retorno;
    }
}