<?php

class Fechamentos_Caixa extends Core
{

    function ListarTodas()
    {
        $this->sql = "SELECT
                        ".$this->campos."
                    FROM
                        fechamentos_caixa fc
                        inner join usuarios_adm ua on fc.idusuario = ua.idusuario
                    where
                        fc.ativo = 'S'";

        $this->aplicarFiltrosBasicos();

        if ($_SESSION['adm_gestor_sindicato'] != 'S') {
            if (!$_SESSION['adm_sindicatos'])
                $_SESSION['adm_sindicatos'] = 0;
            $this->sql .= ' and ( select count(1) from fechamentos_caixa_sindicatos fci where fci.idfechamento = fc.idfechamento and fci.idsindicato in (' . $_SESSION['adm_sindicatos'] . ') ) > 0';
        }

        //echo $this->sql;exit;
        $this->groupby = "fc.idfechamento";
        return $this->retornarLinhas();
    }

    function retornarContas()
    {
        $this->sql = "select idsituacao from contas_workflow where pago = 'S' and ativo = 'S' ";
        $situacao_pago = $this->retornarLinha($this->sql);
        if(!$situacao_pago['idsituacao']) {
            $erros['erro'] = true;
            $erros['erros'][] = 'sem_workflow_vendido';
            return $erros;
        }

        $this->sql = "select idsituacao from contas_workflow where renegociada = 'S' and ativo = 'S' ";
        $situacao_renegociado = $this->retornarLinha($this->sql);

        $this->sql = "select idsituacao from contas_workflow where cancelada = 'S' and ativo = 'S' ";
        $situacao_cancelado = $this->retornarLinha($this->sql);

        $this->sql = "select idsituacao from contas_workflow where transferida = 'S' and ativo = 'S' ";
        $situacao_transferido = $this->retornarLinha($this->sql);


        //Trazer as receitas
        $retorno['receita'] = array();
        if(($_POST['tipo_data_receber'] == 'PER' && $_POST['periodo_inicio_receber'] && $_POST['periodo_final_receber']) || $_POST['tipo_data_receber'] != 'PER') {
            $this->sql = 'SELECT
                    c.*,
                    p.nome AS pessoa,
                    p.idpessoa,
                    cc.nome AS conta_corrente,
                    cw.nome AS situacao,
                    s.nome_abreviado AS sindicato,
                    IF(c.idmatricula, emat.nome_fantasia, econ.nome_fantasia) AS cfc
                FROM
                    contas c
                    INNER JOIN contas_workflow cw ON (c.idsituacao = cw.idsituacao)
                    LEFT OUTER JOIN matriculas m ON (c.idmatricula = m.idmatricula)
                    LEFT OUTER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
                    LEFT OUTER JOIN contas_correntes cc ON (c.idconta_corrente = c.idconta_corrente)
                    LEFT OUTER JOIN sindicatos s ON (s.idsindicato = c.idsindicato)
                    LEFT OUTER JOIN escolas emat ON (emat.idescola = m.idescola)
                    LEFT OUTER JOIN escolas econ ON (econ.idescola = c.idescola)
                WHERE
                    c.tipo = "receita" AND
                    c.ativo = "S" AND
                    c.idsituacao <> "' . $situacao_pago['idsituacao'] . '" AND
                    c.ativo_painel = "S"';

            if ($situacao_renegociado) {
                $this->sql .= ' AND c.idsituacao <> "' . $situacao_renegociado['idsituacao'] . '"';
            }

            if ($situacao_cancelado) {
                $this->sql .= ' AND c.idsituacao <> "' . $situacao_cancelado['idsituacao'] . '"';
            }

            if ($situacao_transferido) {
                $this->sql .= ' AND c.idsituacao <> "' . $situacao_transferido['idsituacao'] . '"';
            }

            if ($_POST['tipo_data_receber']) {
                if ($_POST['tipo_data_receber'] == 'HOJ') {
                    $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") = "' . date('Y-m-d') . '"';
                } elseif ($_POST['tipo_data_receber'] == 'SET') {
                    $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") <= "' . date('Y-m-d') . '" AND
                        DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y'))) . '"';
                } elseif ($_POST['tipo_data_receber'] == 'MAT') {
                    $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m") = "' . date('Y-m') . '"';
                } elseif ($_POST['tipo_data_receber'] == 'MPR') {
                    $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'))) . '"';
                } elseif ($_POST['tipo_data_receber'] == 'MAN') {
                    $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'))) . '"';
                } elseif ($_POST['tipo_data_receber'] == 'PER') {
                    if ($_POST['periodo_inicio_receber']) {
                        $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") >= "' . formataData($_POST['periodo_inicio_receber'],'en',0) . '"';
                    }

                    if ($_POST['periodo_final_receber']) {
                        $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") <= "' . formataData($_POST['periodo_final_receber'],'en',0) . '"';
                    }
                }
            }

            $idsindicato = implode(', ', $_POST['idsindicato']);
            if ($idsindicato) {
                $this->sql .= ' AND c.idsindicato IN (' . $idsindicato . ') ';
            } else {
                if ($_SESSION['adm_gestor_sindicato'] != 'S') {
                    if (! $_SESSION['adm_sindicatos']) {
                        $_SESSION['adm_sindicatos'] = 0;
                    }

                    $this->sql .= ' AND c.idsindicato IN (' . $_SESSION['adm_sindicatos'] . ') ';
                }
            }

            $idescola = implode(', ', $_POST['idescola']);
            if ($idescola) {
                $this->sql .= ' AND c.idescola IN (' . $idescola . ') ';
            } else {
                if ($_SESSION['adm_gestor_sindicato'] != 'S') {
                    if (!$_SESSION['adm_sindicatos']) {
                        $_SESSION['adm_sindicatos'] = 0;
                    }

                    $this->sql .= ' AND c.idsindicato IN (' . $_SESSION['adm_sindicatos'] . ') ';
                }
            }

            if ($_POST['forma_pagamento_receber']) {
                $this->sql .= ' AND c.forma_pagamento = ' . $_POST['forma_pagamento_receber'] . ' ';
            }

            if($_POST['valor_evento_financeiro']) {
                $_POST['valor_evento_financeiro'] = (int) $_POST['valor_evento_financeiro'];
                $this->sql .= " and c.idevento = {$_POST['valor_evento_financeiro']}";
            }

            $this->sql .= ' GROUP BY c.idconta ';

            if ($_POST['ordenacao_data_receber']) {
                $this->ordem_campo = $_POST['ordenacao_data_receber'];
            } else {
                $this->ordem_campo = 'c.idconta';
            }

            $this->limite = -1;
            $retorno['receita'] = $this->retornarLinhas();
        }

        //Trazer as despesas
        $retorno['despesa'] = array();
        if(($_POST['tipo_data_pagar'] == 'PER' && $_POST['periodo_inicio_pagar'] && $_POST['periodo_final_pagar']) || $_POST['tipo_data_pagar'] != 'PER') {
            $this->sql = 'SELECT
                    c.*,
                    f.nome AS fornecedor,
                    p.nome AS produto,
                    cc.nome AS conta_corrente
                FROM
                    contas c
                    LEFT OUTER JOIN fornecedores f on c.idfornecedor = f.idfornecedor
                    LEFT OUTER JOIN produtos p on p.idproduto = c.idproduto
                    LEFT OUTER JOIN contas_correntes cc on c.idconta_corrente = c.idconta_corrente
                WHERE
                    c.tipo = "despesa" AND
                    c.ativo = "S" AND
                    c.idsituacao <> "' . $situacao_pago['idsituacao'] . '" AND
                    c.ativo_painel = "S"';

            if ($situacao_renegociado) {
                $this->sql .= ' AND c.idsituacao <> "' . $situacao_renegociado['idsituacao'] . '"';
            }

            if ($situacao_cancelado) {
                $this->sql .= ' AND c.idsituacao <> "' . $situacao_cancelado['idsituacao'] . '"';
            }

            if ($_POST['tipo_data_pagar']) {
                if($_POST['tipo_data_pagar'] == 'HOJ') {
                    $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") = "' . date('Y-m-d') . '"';
                } elseif ($_POST['tipo_data_pagar'] == 'SET') {
                    $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") <= "' . date('Y-m-d') . '" AND
                        DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y'))) . '"';
                } elseif ($_POST['tipo_data_pagar'] == 'MAT') {
                    $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m") = "' . date('Y-m') . '"';
                } elseif ($_POST['tipo_data_pagar'] == 'MPR') {
                    $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'))) . '"';
                } elseif ($_POST['tipo_data_pagar'] == 'MAN') {
                    $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'))) . '"';
                } elseif ($_POST['tipo_data_pagar'] == 'PER') {
                    if ($_POST['periodo_inicio_pagar']) {
                        $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") >= "' . formataData($_POST['periodo_inicio_pagar'],'en',0) . '"';
                    }

                    if($_POST['periodo_final_pagar']) {
                        $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") <= "' . formataData($_POST['periodo_final_pagar'],'en',0) . '"';
                    }

                }
            }

            $idsindicato = implode(', ', $_POST['idsindicato']);
            if ($idsindicato) {
                $this->sql .= ' AND c.idsindicato IN (' . $idsindicato . ') ';
            } else {
                if ($_SESSION['adm_gestor_sindicato'] != 'S') {
                    if (! $_SESSION['adm_sindicatos']) {
                        $_SESSION['adm_sindicatos'] = 0;
                    }

                    $this->sql .= ' AND c.idsindicato IN (' . $_SESSION['adm_sindicatos'] . ') ';
                }
            }

            $this->sql .= ' GROUP BY c.idconta ';

            $this->ordem_campo = 'c.idconta';
            $this->limite = -1;
            $retorno['despesa'] = $this->retornarLinhas();
        }

        return $retorno;
    }

    function fecharCaixa()
    {

        if(!$_POST['receber_contas'] && !$_POST['pagar_contas']) {
            $erros['erro'] = true;
            $erros['erros'][] = 'erro_sem_contas_selecionadas';
            return $erros;
        }

        //PEGAR A SITUAÇÃO DE CONTA FINALIZADA
        $this->sql = "SELECT idsituacao FROM contas_workflow WHERE pago = 'S' AND ativo = 'S' ";
        $situacao_pago = $this->retornarLinha($this->sql);
        if(!$situacao_pago['idsituacao']) {
            $erros['erro'] = true;
            $erros['erros'][] = 'erro_sem_workflow_vendido';
            return $erros;
        }

        mysql_query('START TRANSACTION');

        //INSERIR A LINHA NO FECHAMENTO DE CAIXA
        $sql_fechamento = "INSERT INTO fechamentos_caixa SET data_cad = NOW(), ativo = 'S', idusuario = '".$this->idusuario."' ";

        if($_POST['tipo_data_pagar']) {
            $sql_fechamento .= ", periodo_tipo_pagar = '".$_POST['tipo_data_pagar']."'";
            if($_POST['tipo_data_pagar'] == 'PER') {
                if($_POST['periodo_inicio_pagar'])
                  $sql_fechamento .= ", periodo_de_pagar = '".formataData($_POST['periodo_inicio_pagar'],'en',0)."' ";
                if($_POST['periodo_final_pagar'])
                  $sql_fechamento .= ", periodo_ate_pagar = '".formataData($_POST['periodo_final_pagar'],'en',0)."' ";
            }
        }

        if($_POST['tipo_data_receber']) {
            $sql_fechamento .= ", periodo_tipo_receber = '".$_POST['tipo_data_receber']."'";
            if($_POST['tipo_data_receber'] == 'PER') {
                if($_POST['periodo_inicio_receber'])
                  $sql_fechamento .= ", periodo_de_receber = '".formataData($_POST['periodo_inicio_receber'],'en',0)."' ";
                if($_POST['periodo_final_receber'])
                  $sql_fechamento .= ", periodo_ate_receber = '".formataData($_POST['periodo_final_receber'],'en',0)."' ";
            }
        }

        if ($_POST['forma_pagamento_receber']) {
            $sql_fechamento .= ", forma_pagamento_receber = '".$_POST['forma_pagamento_receber']."'";
        }

        $fechamento_resultado = $this->executaSql($sql_fechamento);
        if(!$fechamento_resultado) {
            $erros['erro'] = true;
            $erros['erros'][] = 'erro_inserir_fechamento';
            mysql_query('ROLLBACK');
            return $erros;
        }
        $idfechamento = mysql_insert_id();

        $sindicatosFechamento = array();
        //QUITAR AS CONTAS A RECEBER
        if($_POST['receber_contas']) {
            foreach($_POST['receber_contas'] as $idconta => $idsindicato) {
                if($idsindicato)
                    $sindicatosFechamento[$idsindicato] = $idsindicato;

                //INSERIR NO HISTÓRICO A ALTERAÇÃO DA SITUAÇÃO ANTES DE SALVAR, PRA EVITAR OUTRA CONEXÃO
                $sql_hist_receber = "INSERT INTO contas_historicos (idconta, data_cad, tipo, acao, idusuario, de, para)
                            SELECT '".$idconta."', NOW(), 'situacao', 'modificou', '".$this->idusuario."', idsituacao, '".$situacao_pago['idsituacao']."'
                            FROM contas WHERE idconta = '".$idconta."' ";
                $hist_receber = mysql_query($sql_hist_receber);
                if(!$hist_receber) {
                    $erros['erro'] = true;
                    $erros['erros'][] = 'erro_inserir_hist_receber';
                    mysql_query('ROLLBACK');
                    return $erros;
                }

                if (!$_POST['receber_contas_correntes'][$idconta]) {
                    $erros['erro'] = true;
                    $erros['erros'][] = 'erro_conta_sem_conta_corrente';
                    mysql_query('ROLLBACK');
                    return $erros;
                }

                $sql = "UPDATE
                          contas
                        SET
                          idsituacao = '".$situacao_pago['idsituacao']."',
                          data_pagamento = NOW(),
                          idfechamento = '".$idfechamento."',
                          idconta_corrente = '".$_POST['receber_contas_correntes'][$idconta]."'
                        WHERE idconta = '".$idconta."' ";
                $quitar = $this->executaSql($sql);
                if(!$quitar) {
                    $erros['erro'] = true;
                    $erros['erros'][] = 'erro_inserir_receber';
                    mysql_query('ROLLBACK');
                    return $erros;
                }

                $contas_receber_quitadas[] = $idconta;
            }
        }

        //QUITAR AS CONTAS A PAGAR
        if($_POST['pagar_contas']) {
            foreach($_POST['pagar_contas'] as $idconta => $idsindicato) {
                if($idsindicato)
                    $sindicatosFechamento[$idsindicato] = $idsindicato;

                //INSERIR NO HISTÓRICO A ALTERAÇÃO DA SITUAÇÃO ANTES DE SALVAR, PRA EVITAR OUTRA CONEXÃO
                $sql_hist_pagar = "INSERT INTO contas_historicos (idconta, data_cad, tipo, acao, idusuario, de, para)
                            SELECT '".$idconta."', NOW(), 'situacao', 'modificou', '".$this->idusuario."', idsituacao, '".$situacao_pago['idsituacao']."'
                            FROM contas WHERE idconta = '".$idconta."' ";
                $hist_pagar = mysql_query($sql_hist_pagar);
                if(!$hist_pagar) {
                    $erros['erro'] = true;
                    $erros['erros'][] = 'erro_inserir_hist_pagar';
                    mysql_query('ROLLBACK');
                    return $erros;
                }

                if (!$_POST['pagar_contas_correntes'][$idconta]) {
                    $erros['erro'] = true;
                    $erros['erros'][] = 'erro_conta_sem_conta_corrente';
                    mysql_query('ROLLBACK');
                    return $erros;
                }

                $sql = "UPDATE
                          contas
                        SET
                          idsituacao = '".$situacao_pago['idsituacao']."',
                          data_pagamento = NOW(),
                          idfechamento = '".$idfechamento."',
                          idconta_corrente = '".$_POST['pagar_contas_correntes'][$idconta]."'
                        WHERE idconta = '".$idconta."' ";
                $quitar = $this->executaSql($sql);
                if(!$quitar) {
                    $erros['erro'] = true;
                    $erros['erros'][] = 'erro_inserir_pagar';
                    mysql_query('ROLLBACK');
                    return $erros;
                }

                $contas_pagar_quitadas[] = $idconta;
            }
        }

        //INSERIR AS INSTITUIÇÕES NO FECHAMENTO
        if (count($sindicatosFechamento) > 0) {
            foreach ($sindicatosFechamento as $idsindicato) {
                $sql_sindicato = "INSERT INTO fechamentos_caixa_sindicatos
                                        SET
                                            data_cad = NOW(),
                                            ativo = 'S',
                                            idfechamento = '".$idfechamento."',
                                            idsindicato = '".$idsindicato."' ";
                $sindicato_resultado = $this->executaSql($sql_sindicato);
                if(!$sindicato_resultado) {
                    $erros['erro'] = true;
                    $erros['erros'][] = 'erro_inserir_sindicato';
                    mysql_query('ROLLBACK');
                    return $erros;
                }
            }
        }

        //TRAZER OS TOTAIS DE CONTAS A RECEBER
        if(!$contas_receber_quitadas)
            $contas_receber_quitadas[] = 0;
        $sql_total_receber = "SELECT sum(valor) AS total_valor, count(1) AS total_quantidade
                                FROM contas WHERE idconta in(".implode(',',$contas_receber_quitadas).") ";
        $resultado_total_receber = $this->executaSql($sql_total_receber);
        if(!$resultado_total_receber) {
            $erros['erro'] = true;
            $erros['erros'][] = 'erro_buscar_total_receber';
            mysql_query('ROLLBACK');
            return $erros;
        }
        $total_receber = mysql_fetch_assoc($resultado_total_receber);

        //TRAZER OS TOTAIS DE CONTAS A PAGAR
        if(!$contas_pagar_quitadas)
            $contas_pagar_quitadas[] = 0;
        $sql_total_pagar = "SELECT sum(valor) AS total_valor, count(1) AS total_quantidade
                                FROM contas WHERE idconta in(".implode(',',$contas_pagar_quitadas).") ";
        $resultado_total_pagar = $this->executaSql($sql_total_pagar);
        if(!$resultado_total_pagar) {
            $erros['erro'] = true;
            $erros['erros'][] = 'erro_buscar_total_pagar';
            mysql_query('ROLLBACK');
            return $erros;
        }
        $total_pagar = mysql_fetch_assoc($resultado_total_pagar);

        //ATUALIZAR O FECHAMENTO COM OS TOTAIS DAS CONTAS INSERIDAS
        $sql_atualizar_fechamento = " UPDATE fechamentos_caixa SET credito_valor = '". floatval($total_receber['total_valor'])."',
                                                                    credito_quantidade = '".$total_receber['total_quantidade']."',
                                                                    debito_valor = '".($total_pagar['total_valor']*-1)."',
                                                                    debito_quantidade = '".$total_pagar['total_quantidade']."'
                                                WHERE idfechamento = '".$idfechamento."' ";
        $resultado_atualizar_fechamento = $this->executaSql($sql_atualizar_fechamento);
        if(!$resultado_atualizar_fechamento) {
            $erros['erro'] = true;
            $erros['erros'][] = 'erro_atualizar_fechamento';
            mysql_query('ROLLBACK');
            return $erros;
        }

        mysql_query('COMMIT');
        $retorno['sucesso'] = true;

        return $retorno;
    }

    function Retornar()
    {
        $this->sql = "SELECT ".$this->campos."
                            FROM
                             fechamentos_caixa where ativo='S' and idfechamento='".$this->id."'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar()
    {
        return $this->SalvarDados();
    }

    public function retornarPessoa($idpessoa)
    {
        $idpessoa = (int) $idpessoa;
        $query = 'SELECT
                    p.*,
                    e.nome as estado,
                    e.sigla,
                    c.nome as cidade,
                    c.codigo,
                    IF (
                        l.nome IS NULL,
                        p.endereco,
                        CONCAT(l.nome, " ", p.endereco)
                    ) AS endereco
                FROM
                    pessoas p
                    left outer join estados e on (p.idestado = e.idestado)
                    left outer join cidades c on (p.idcidade = c.idcidade)
                    left outer join logradouros l on (l.idlogradouro = p.idlogradouro)
                WHERE
                    p.idpessoa = '.$idpessoa;
        return (object) $this->retornarLinha($query);
    }

    public function retornarMatricula($idmatricula)
    {
        $idmatricula = (int) $idmatricula;
        $query = 'SELECT
                    p.*,
                    e.nome as estado,
                    e.sigla,
                    c.nome as cidade,
                    c.codigo,
                    IF (
                        l.nome IS NULL,
                        p.endereco,
                        CONCAT(l.nome, " ", p.endereco)
                    ) AS endereco,
                    m.idmatricula,
                    m.valor_contrato,
                    m.data_matricula,
                    m.quantidade_parcelas,
                    m.idcurso
                FROM
                    matriculas m
                    inner join pessoas p on (m.idpessoa = p.idpessoa)
                    left outer join estados e on (p.idestado = e.idestado)
                    left outer join cidades c on (p.idcidade = c.idcidade)
                    left outer join logradouros l on (l.idlogradouro = p.idlogradouro)
                WHERE
                    m.idmatricula = '.$idmatricula;
        return (object) $this->retornarLinha($query);
    }

    public function retornarMatriculaFatura($idConta)
    {
        $retorno = [];
        $idConta = (int) $idConta;
        $query = 'SELECT
                    p.*,
                    cm.valor_fatura as valor,
                    e.nome as estado,
                    e.sigla,
                    ci.nome as cidade,
                    ci.codigo,
                    IF (
                        l.nome IS NULL,
                        p.endereco,
                        CONCAT(l.nome, " ", p.endereco)
                    ) AS endereco,
                    m.idmatricula,
                    m.valor_contrato,
                    m.data_matricula,
                    m.quantidade_parcelas,
                    m.idcurso
                FROM
                    contas_matriculas cm
                    INNER JOIN contas c ON (c.idconta = cm.idconta)
                    INNER JOIN matriculas m ON (m.idmatricula = cm.idmatricula)
                    INNER JOIN pessoas p ON (p.idpessoa = m.idpessoa)
                    LEFT OUTER JOIN estados e ON (p.idestado = e.idestado)
                    LEFT OUTER JOIN cidades ci ON (p.idcidade = ci.idcidade)
                    LEFT OUTER JOIN logradouros l ON (l.idlogradouro = p.idlogradouro)
                WHERE
                    cm.idconta = ' . $idConta;
        $res = $this->executaSql($query);
        while ($linha = mysql_fetch_assoc($res)) {
            $retorno[] = (object) $linha;
        }

        return $retorno;
    }

    public function retornarContaFechamento($idfechamento)
    {
        $this->sql = "SELECT
                c.*,
                e.idescola,
                e.data_cad AS data_cadastro_escola,
                e.nome_fantasia as escola,
                e.email as email_escola,
                e.razao_social AS escola_razao_social,
                e.documento AS escola_documento,
                e.slug AS escola_slug,
                IF(l.nome IS NULL, e.endereco, CONCAT(l.nome, ' ', e.endereco)) AS escola_endereco,
                e.numero AS escola_numero,
                e.complemento AS escola_complemento,
                e.bairro AS escola_bairro,
                e.cep AS escola_cep,
                e.telefone AS escola_telefone,
                s.nome as sindicato,
                es.nome as estado,
                es.sigla as escola_sigla,
                cc.nome as cidade,
                cc.codigo as escola_cidade_codigo,
                e.parceiro as escola_parceiro
            FROM
                contas c
                LEFT JOIN escolas e ON (c.idescola = e.idescola)
                LEFT JOIN cfcs_valores_cursos cvv ON (cvv.idcfc = e.idescola AND cvv.ativo = 'S')
                LEFT JOIN cidades cc ON (cc.idcidade = e.idcidade)
                LEFT JOIN estados es ON (es.idestado = e.idestado)
                LEFT JOIN sindicatos s ON (s.idsindicato = e.idsindicato)
                LEFT JOIN sindicatos_valores_cursos svv ON (svv.idsindicato = s.idsindicato AND svv.ativo = 'S')
                LEFT OUTER JOIN logradouros l ON (l.idlogradouro = e.idlogradouro)
            WHERE
                c.idfechamento = " . $idfechamento . " and
                c.tipo = 'receita' and
                c.ativo = 'S'
            GROUP BY c.idconta";


        $this->ordem = 'asc';
        $this->ordem_campo = 'c.idconta';
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    public function retornarUnidadeXML($idfechamento)
    {
        $sql = 'select
                    count(1) as total
                from
                    fechamentos_caixa_sindicatos fci
                    inner join sindicatos i on (fci.idsindicato = i.idsindicato)
                where
                    fci.idfechamento = '.(int) $idfechamento;
        $total = $this->retornarLinha($sql);
        if($total['total'] == 1) {
            $sql = 'select
                    i.idsindicato,
                    i.idmantenedora
                from
                    fechamentos_caixa_sindicatos fci
                    inner join sindicatos i on (fci.idsindicato = i.idsindicato)
                where
                    fci.idfechamento = '.(int) $idfechamento;
            $sindicato = $this->retornarLinha($sql);
            return 'M'.$sindicato['idmantenedora'].'I'.$sindicato['idsindicato'];
        } else {
            return 'MI';
        }
    }

    public function retornarOrdemParcela(
        $conta,
        $idSituacaoRenegociada,
        $idSituacaoCancelada,
        $idSituacaoTransferida,
        $idEvento,
        $compartilhado = false
    ) {
        $this->sql = 'select
                        c.*
                    from
                        contas c ';
        if($compartilhado)
            $this->sql .= ' left outer join pagamentos_compartilhados_matriculas pcm
                            on (c.idpagamento_compartilhado = pcm.idpagamento and pcm.ativo = "S")
                        where
                            (c.idmatricula = '.$conta['idmatricula'].' or pcm.idmatricula = '.$conta['idmatricula'].')';
        else
            $this->sql .= ' where c.idmatricula = '.$conta['idmatricula'];

        if($idSituacaoRenegociada) $this->sql .= ' and c.idsituacao <> '.$idSituacaoRenegociada;
        if($idSituacaoCancelada) $this->sql .= ' and c.idsituacao <> '.$idSituacaoCancelada;
        if($idSituacaoTransferida) $this->sql .= ' and c.idsituacao <> '.$idSituacaoTransferida;

        $this->sql .= ' and c.idevento = '.$idEvento.' and c.ativo = "S"';

        $this->ordem = 'asc';
        $this->ordem_campo = 'c.data_vencimento, c.idconta';
        $this->limite = -1;
        $parcelas = $this->retornarLinhas();
        $ordemParcela = array();
        $ordem = 0;
        foreach($parcelas as $parcela) {
            $ordem++;
            if($ordem == 1 && ($parcela['renegociada'] == 'S' || $parcela['transferida'] == 'S')) {
                $ordem++;
            }
            $ordemParcela[$parcela['idconta']] = $ordem;
        }

        return $ordemParcela;
    }

    public function retornarSituacaoRenegociada()
    {
        $this->sql = "select idsituacao from contas_workflow where renegociada = 'S' and ativo = 'S' ";
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoPago()
    {
        $this->sql = "select idsituacao from contas_workflow where pago = 'S' and ativo = 'S'";
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoCancelada()
    {
        $this->sql = "select idsituacao from contas_workflow where cancelada = 'S' and ativo = 'S' ";
        return $this->retornarLinha($this->sql);
    }

    public function retornarSituacaoTransferida()
    {
        $this->sql = "select idsituacao from contas_workflow where transferida = 'S' and ativo = 'S' ";
        return $this->retornarLinha($this->sql);
    }

    public function retornarEventoMensalidade()
    {
        $this->sql = 'select idevento from eventos_financeiros where ativo = "S" and mensalidade = "S" limit 1';
        return $this->retornarLinha($this->sql);
    }
    public function consultarValorEventosFinanceiro($idEvento, $idMat)
    {
        try
        {
            if(!is_numeric($idEvento) && !is_numeric($idMat)){
                throw new InvalidArgumentException('Parâmetros tem que ser do tipo númerico.');
            } else {
                $this->sql = "SELECT SUM(valor) as valor
                FROM contas c
                WHERE idevento = {$idEvento} AND idmatricula = {$idMat} and ativo = 'S' and ativo_painel = 'S' and fatura = 'N'";
                return $this->retornarLinha($this->sql)['valor'];
            }
        } catch (InvalidArgumentException $i)
        {
            echo "Ops! ocorreu um erro: {$i->getMessage()}";
            return false;
        }
    }

    public function retornarContasXMLPeriodo($idMatricula = null, $situacoes = null)
    {
        $situacaoPago = $this->retornarSituacaoPago();
        $colunaData = "data_pagamento";

        $this->sql = "SELECT
                c.*,
                e.idescola,
                DATE_FORMAT(e.data_cad,'%Y-%m-%d') AS data_cadastro_escola,
                e.nome_fantasia as escola,
                e.email as email_escola,
                e.razao_social AS escola_razao_social,
                e.documento AS escola_documento,
                e.slug AS escola_slug,
                IF(l.nome IS NULL, e.endereco, CONCAT(l.nome, ' ', e.endereco)) AS escola_endereco,
                e.numero AS escola_numero,
                e.complemento AS escola_complemento,
                e.bairro AS escola_bairro,
                e.cep AS escola_cep,
                e.telefone AS escola_telefone,
                s.nome as sindicato,
                es.nome as estado,
                es.sigla as escola_sigla,
                cc.nome as cidade,
                cc.codigo as escola_cidade_codigo,
                e.parceiro as escola_parceiro
            FROM
                contas c
                LEFT JOIN escolas e ON (c.idescola = e.idescola)
                LEFT JOIN cfcs_valores_cursos cvv ON (cvv.idcfc = e.idescola AND cvv.ativo = 'S')
                LEFT JOIN cidades cc ON (cc.idcidade = e.idcidade)
                LEFT JOIN estados es ON (es.idestado = e.idestado)
                LEFT JOIN sindicatos s ON (s.idsindicato = e.idsindicato)
                LEFT JOIN sindicatos_valores_cursos svv ON (svv.idsindicato = s.idsindicato AND svv.ativo = 'S')
                LEFT OUTER JOIN logradouros l ON (l.idlogradouro = e.idlogradouro)
            WHERE
                c.tipo = 'receita' and
                c.ativo = 'S'";

        if(empty($idMatricula))
        {
            $this->sql .= " and c.data_pagamento is not null";
        } else {
            $this->sql .= " and c.idmatricula = $idMatricula";
            $colunaData = "data_vencimento";
        }

        if(empty($situacoes))
        {
            $this->sql .= " and c.idsituacao = ".$situacaoPago['idsituacao']."";
        } else {
            $implode = array_column($situacoes, 'idsituacao');
            $this->sql .= " and c.idsituacao in (" . implode(",", $implode) . ")";
        }
        if($_GET['tipo_periodo']) {
            if($_GET['tipo_periodo'] == 'HOJ') {
                $this->sql .= " and date_format(c.$colunaData,'%Y%m%d') = date_format(now(),'%Y%m%d')";
            } else if($_GET['tipo_periodo'] == 'SET') {
                $this->sql .= " and date_format(c.$colunaData,'%Y%m%d') <= date_format(now(),'%Y%m%d')
                and date_format(c.$colunaData,'%Y%m%d') >= '".date("Ymd", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'  ";
            } else if($_GET['tipo_periodo'] == 'MAT') {
                $this->sql .= " and date_format(c.$colunaData,'%Y%m') = date_format(now(),'%Y%m')";
            } else if($_GET['tipo_periodo'] == 'MPR') {
                $this->sql .= " and date_format(c.$colunaData,'%Y%m') = '".date("Ym", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
            } else if($_GET['tipo_periodo'] == 'MAN') {
                $this->sql .= " and date_format(c.$colunaData,'%Y%m') = '".date("Ym", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
            } else if($_GET['tipo_periodo'] == 'PER') {
                if($_GET['periodo_inicio'])
                    $this->sql .= " and DATE_FORMAT(c.$colunaData,'%Y-%m-%d') >= '".formataData($_GET['periodo_inicio'],'en',0)."' ";
                if($_GET['periodo_final'])
                    $this->sql .= " and DATE_FORMAT(c.$colunaData,'%Y-%m-%d') <= '".formataData($_GET['periodo_final'],'en',0)."' ";
            }
        }

        $idsindicato = implode(', ', $_GET['idsindicato']);
        if($idsindicato) {
            $this->sql .= " and c.idsindicato in (".$idsindicato.") ";
        } else {
            if ($_SESSION['adm_gestor_sindicato'] != 'S') {
                if (!$_SESSION['adm_sindicatos'])
                    $_SESSION['adm_sindicatos'] = 0;
                $this->sql .= ' and c.idsindicato in (' . $_SESSION['adm_sindicatos'] . ') ';
            }
        }

        $idescola = implode(', ', $_GET['idescola']);
        if($idescola) {
            $this->sql .= " and c.idescola in (".$idescola.") ";
        } else {
            if ($_SESSION['adm_gestor_sindicato'] != 'S') {
                if (!$_SESSION['adm_sindicatos'])
                    $_SESSION['adm_sindicatos'] = 0;
                $this->sql .= ' and c.idsindicato in (' . $_SESSION['adm_sindicatos'] . ') ';
            }
        }
        $this->sql .= ' GROUP BY	c.idconta ';

        $this->ordem = 'asc';
        $this->ordem_campo = "c.$colunaData asc, idconta";
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    public function retornarUnidadeXMLPeriodo()
    {
        if(count($_GET['idescola']) == 1) {
            $sql = 'select
                        e.idescola,
                        e.nome_fantasia as escola,
                        e.email as email_escola,
                        s.nome as sindicato,
                        es.nome as estado,
                        c.nome as cidade,
                        s.idmantenedora
                    from
                        escolas e
                    inner join cidades c ON (c.idcidade = e.idcidade)
                    inner join estados es ON (es.idestado = e.idestado)
                    inner join sindicatos s ON (s.idsindicato = e.idsindicato)
                    where
                        e.idescola = '.(int) $_GET['idescola'][0];
            $escola = $this->retornarLinha($sql);
            return 'M'.$escola['idmantenedora'].'I'.$escola['idescola'];
        } else {
            return 'MI';
        }
    }

    public function retornarMatriculasCompartilhadas($idpagamento_compartilhado)
    {
        $retorno = array();

        $sql = 'select count(1) as total from contas where idpagamento_compartilhado = '.(int) $idpagamento_compartilhado.' and ativo = "S"';
        $totalContas = $this->retornarLinha($sql);
        $retorno['total_contas'] = $totalContas['total'];

        $this->sql = 'select * from pagamentos_compartilhados_matriculas where idpagamento = '.(int) $idpagamento_compartilhado;
        $this->limite = -1;
        $this->ordem = 'asc';
        $this->ordem_campo = 'idmatricula';
        $retorno['matriculas'] = $this->retornarLinhas();

        return $retorno;
    }

    public function retornarContasCorrentes(array $idescolas)
    {
        $total = count($idescolas);
        $escolas = implode(',', $idescolas);
        $sql = " SELECT ccs.idconta_corrente, cc.nome, count(e.idescola) as total"
             . " FROM escolas e"
             . " INNER JOIN sindicatos s ON (e.idsindicato = s.idsindicato)"
             . " INNER JOIN contas_correntes_sindicatos ccs ON (ccs.idsindicato = s.idsindicato AND ccs.idsindicato = e.idsindicato)"
             . " INNER JOIN contas_correntes cc ON (ccs.idconta_corrente = cc.idconta_corrente)"
             . " WHERE idescola IN ({$escolas})"
             . " AND ccs.ativo = 'S'"
             . " GROUP BY ccs.idconta_corrente"
             . " HAVING total = {$total}";

        $query = mysql_query($sql);
        $retorno = array();
        while ($linha = mysql_fetch_assoc($query)) {
            $retorno[] = array(
                "nome" => $linha["nome"],
                "id" => $linha["idconta_corrente"]
            );
        }

        return $retorno;
    }

    public function cadastrarContaCorrenteXML($idconta, $idcontaCorrente)
    {
        $idconta = (empty($idconta)) ? null : (int) $idconta;
        $idcontaCorrente = (empty($idcontaCorrente)) ? null : (int) $idcontaCorrente;
        $sql = " INSERT INTO contas_correntes_fechamentos"
             . " SET data_cad=NOW()"
             . ", idconta={$idconta}"
             . ", idconta_corrente={$idcontaCorrente}";

         return $this->executaSql($sql);
    }

    /**
     * Método para retornar as situações das contas de acordo com os filtros informados
     * @param array $filtros
     * @return array
     */
    public function retornarSituacoesFiltradas($filtros)
    {
        $sql = "SELECT idsituacao FROM contas_workflow WHERE ";
        foreach ($filtros as $campo => $valor)
        {
            if(next($filtros))
            {
                $sql .= "{$campo} {$valor} AND ";
            } else {
                $sql .= "{$campo} {$valor}";
            }
        }

        return $this->retornarLinhasArray($sql);
    }
}
