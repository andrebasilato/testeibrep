<?php

class Relatorio extends Core
{
    public function gerarRelatorio()
    {
        if (
            $_GET['q']['de_ate|tipo_data_pagamento|c.data_pagamento'] == 'PER'
            && (! $_GET['data_pagamento_de'] || ! $_GET['data_pagamento_ate'])
        ) {
            unset($_GET['q']['de_ate|tipo_data_pagamento|c.data_pagamento']);
        }

        if (
            $_GET['q']['de_ate|tipo_data_prevista_disponivel_pagseguro|c.data_prevista_disponivel_pagseguro'] == 'PER'
            && (! $_GET['data_prevista_disponivel_pagseguro_de'] || ! $_GET['data_prevista_disponivel_pagseguro_ate'])
        ) {
            unset($_GET['q']['de_ate|tipo_data_prevista_disponivel_pagseguro|c.data_prevista_disponivel_pagseguro']);
        }

        if (
            (
                $_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento'] == 'PER'
                && (
                    ! $_GET['data_vencimento_de'] ||
                    ! $_GET['data_vencimento_ate']
                )
            )
            || ! array_key_exists($_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento'], $GLOBALS['tipo_data_filtro'][$GLOBALS['config']['idioma_padrao']])
        ) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'datas_obrigatorias';
            return $retorno;
        }

        if (
            dataDiferenca(formataData($_GET['data_vencimento_de'], 'en', 0), formataData($_GET['data_vencimento_ate'], 'en', 0), 'D') > 365
            || dataDiferenca(formataData($_GET['data_pagamento_de'], 'en', 0), formataData($_GET['data_pagamento_ate'], 'en', 0), 'D') > 365
            || dataDiferenca(formataData($_GET['data_prevista_disponivel_pagseguro_de'], 'en', 0), formataData($_GET['data_prevista_disponivel_pagseguro_ate'], 'en', 0), 'D') > 365
        ) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'intervalo_maior_um_ano';
            return $retorno;
        }

        require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/matriculas.class.php';
        $matriculaObj = new Matriculas();
        $situacaoAtiva = $matriculaObj->retornarSituacaoAtiva();

        $this->sql = 'SELECT
                s.nome AS sindicato,
                e.nome_fantasia AS escola,
                m.data_matricula,
                (
                    SELECT
                        mh.data_cad
                    FROM
                        matriculas_historicos mh
                    WHERE
                        mh.idmatricula = m.idmatricula AND
                        mh.para = ' . (int) $situacaoAtiva['idsituacao'] . ' AND
                        mh.tipo = "situacao" AND
                        mh.acao = "modificou"
                    ORDER BY mh.idhistorico DESC
                    LIMIT 1
                ) AS data_em_curso,
                m.idmatricula,
                mw.nome AS situacao_matricula,
                p.nome,
                p.documento,
                p.telefone,
                p.celular,
                p.email,
                cid.nome AS cidade,
                est.nome AS estado,
                c.valor,
                c.forma_pagamento,
                IF(c.forma_pagamento = 10, pag.installmentCount, NULL) AS parcelas_pagseguro,
                cw.nome AS situacao,
                c.data_vencimento,
                c.data_pagamento,
                IF(c.forma_pagamento = 10 AND c.data_pagamento IS NOT NULL, DATE_ADD(c.data_pagamento, INTERVAL 30 day), NULL) AS data_prevista_disponivel_pagseguro,
                IF(c.forma_pagamento = 10, pag.code, NULL) AS code_pagseguro,
                cw.pago AS conta_pago,
                cw.pagseguro AS conta_pagseguro,
                cw.renegociada AS conta_renegociada,
                cw.transferida AS conta_transferida,
                cw.cancelada AS conta_cancelada
            FROM
                contas c
                INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao)
                INNER JOIN matriculas m ON (m.idmatricula = c.idmatricula)
                INNER JOIN matriculas_workflow mw ON (mw.idsituacao = m.idsituacao)
                INNER JOIN pessoas p ON (p.idpessoa = m.idpessoa)
                INNER JOIN estados est ON (est.idestado = p.idestado)
                INNER JOIN cidades cid ON (cid.idcidade = p.idcidade)
                INNER JOIN escolas e ON (e.idescola = m.idescola)
                INNER JOIN sindicatos s ON (s.idsindicato = m.idsindicato)
                LEFT OUTER JOIN pagseguro pag ON (pag.idpagseguro = (
                    SELECT
                        pag.idpagseguro
                    FROM
                        pagseguro pag
                    WHERE
                        pag.idconta = c.idconta AND
                        pag.ativo = "S"
                    ORDER BY
                        pag.idpagseguro DESC
                    LIMIT 1
                ))
            WHERE
                c.ativo = "S"';

        if ($_SESSION['adm_gestor_sindicato'] != 'S') {
            $this->sql .= ' AND c.idsindicato IN (' . $_SESSION['adm_sindicatos'] . ') ';
        }

        $this->aplicarFiltrosBasicos();

        if (is_array($_GET['q'])) {
            foreach($_GET['q'] as $campo => $valor) {
                $campo = explode('|', $campo);
                $valor = str_replace('\'', '', $valor);

                if (($valor || $valor === '0') && $valor <> 'todos') {
                    if ($campo[0] == 'de_ate') {
                        if ($campo[2] == 'data_prevista_disponivel_pagseguro') {
                            $campo[2] = 'IF(
                                c.forma_pagamento = 10 AND c.data_pagamento IS NOT NULL,
                                DATE_ADD(c.data_pagamento, INTERVAL 30 day),
                                NULL
                            )';
                        }

                        if ($valor == 'HOJ') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") = "' . date('Y-m-d') . '"';
                        } elseif ($valor == 'ONT') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") = "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))) . '"';
                        } elseif ($valor == 'SET') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") <= "' . date('Y-m-d') . '" AND
                                DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y'))) . '"';
                        } elseif ($valor == 'QUI') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") <= "' . date('Y-m-d') . '"
                            AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 15, date('Y'))) . '"';
                        } elseif ($valor == 'MAT') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m") = "' . date('Y-m') . '"';
                        } elseif ($valor == 'MPR') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'))) . '"';
                        } elseif ($valor == 'MAN') {
                            $this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'))) . '"';
                        }
                    }
                }
            }
        }

        if ($_GET['data_em_curso']) {
            $this->sql .= ' AND (
                    SELECT
                        DATE_FORMAT(mh.data_cad, "%d/%m/%Y")
                    FROM
                        matriculas_historicos mh
                    WHERE
                        mh.idmatricula = m.idmatricula AND
                        mh.para = ' . (int) $situacaoAtiva['idsituacao'] . ' AND
                        mh.tipo = "situacao" AND
                        mh.acao = "modificou"
                    ORDER BY mh.idhistorico DESC
                    LIMIT 1
                ) = "' . $_GET['data_em_curso'] . '"';
        }

        if ($_GET['data_vencimento_de'] && $_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento'] == 'PER') {
            $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") >= "' . formataData($_GET['data_vencimento_de'],'en',0) . '"';
        }

        if ($_GET['data_vencimento_ate'] && $_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento'] == 'PER') {
            $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") <= "' . formataData($_GET['data_vencimento_ate'],'en',0) . '"';
        }

        if ($_GET['data_pagamento_de'] && $_GET['q']['de_ate|tipo_data_pagamento|c.data_pagamento'] == 'PER') {
            $this->sql .= ' AND DATE_FORMAT(c.data_pagamento, "%Y-%m-%d") >= "' . formataData($_GET['data_pagamento_de'],'en',0) . '"';
        }

        if ($_GET['data_pagamento_ate'] && $_GET['q']['de_ate|tipo_data_pagamento|c.data_pagamento'] == 'PER') {
            $this->sql .= ' AND DATE_FORMAT(c.data_pagamento, "%Y-%m-%d") <= "' . formataData($_GET['data_pagamento_ate'],'en',0) . '"';
        }

        if ($_GET['data_prevista_disponivel_pagseguro_de'] && $_GET['q']['de_ate|tipo_data_prevista_disponivel_pagseguro|c.data_prevista_disponivel_pagseguro'] == 'PER') {
            $this->sql .= ' AND IF(
                    c.forma_pagamento = 10 AND c.data_pagamento IS NOT NULL,
                    DATE_FORMAT(DATE_ADD(c.data_pagamento, INTERVAL 30 day), "%Y-%m-%d"),
                    NULL
                ) >= "' . formataData($_GET['data_prevista_disponivel_pagseguro_de'],'en',0) . '"';
        }

        if ($_GET['data_prevista_disponivel_pagseguro_ate'] && $_GET['q']['de_ate|tipo_data_prevista_disponivel_pagseguro|c.data_prevista_disponivel_pagseguro'] == 'PER') {
            $this->sql .= ' AND IF(
                    c.forma_pagamento = 10 AND c.data_pagamento IS NOT NULL,
                    DATE_FORMAT(DATE_ADD(c.data_pagamento, INTERVAL 30 day), "%Y-%m-%d"),
                    NULL
                ) <= "' . formataData($_GET['data_prevista_disponivel_pagseguro_ate'],'en',0) . '"';
        }

        if ($_GET['idsindicato']) {
            $this->sql .= ' AND m.idsindicato IN (' . implode(',', $_GET['idsindicato']) . ') ';
        }

        $_GET['idescola'] = array_filter(array_unique($_GET['idescola']));
        if ($_GET['idescola']) {
            $this->sql .= ' AND m.idescola IN (' . implode(',', $_GET['idescola']) . ') ';
        }

        if ($_GET['idsituacao_matricula']) {
            $this->sql .= ' AND m.idsituacao IN (' . implode(',', $_GET['idsituacao_matricula']) . ') ';
        }

        if ($_GET['forma_pagamento']) {
            $this->sql .= ' AND c.forma_pagamento IN (' . implode(',', $_GET['forma_pagamento']) . ') ';
        }

        if ($_GET['idsituacao_conta']) {
            $this->sql .= ' AND c.idsituacao IN (' . implode(',', $_GET['idsituacao_conta']) . ') ';
        }

        $this->sql .= ' GROUP BY c.idconta';

        $this->groupby = 'c.idconta';
        $this->ordem_campo = 'c.idconta';
        $this->ordem = 'DESC';
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function gerarTabela($dados,$q = null,$idioma,$configuracao = "listagem")
    {
        include 'idiomas/' . $this->config['idioma_padrao'] . '/index.php';
        echo '<table class="zebra-striped" id="sortTableExample">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Filtro</th>';
        echo '<th>Valor</th>';
        echo '</tr>';
        echo '</thead>';
        foreach($this->config['formulario'] as $ind => $fieldset) {
            foreach($fieldset['campos'] as $ind => $campo) {
                $campo['nome'] = str_replace('[]', '', $campo['nome']);

                if ($campo['nome']{0} == 'q') {
                    $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
                    $campoAux = $_GET["q"][$campoAux];

                    if ($campo['sql_filtro']) {
                        if ($campo['sql_filtro'] == 'array') {
                            $campoAux = str_replace(array('q[',']'), '', $campo['nome']);
                            $campoAux = $GLOBALS[$campo['sql_filtro_label']][$GLOBALS['config']['idioma_padrao']][$_GET['q'][$campoAux]];
                        } else {
                            $sql = str_replace('%',$campoAux,$campo['sql_filtro']);
                            $seleciona = mysql_query($sql);
                            $linha = mysql_fetch_assoc($seleciona);
                            $campoAux = $linha[$campo['sql_filtro_label']];
                        }
                    }
                } elseif (is_array($_GET[$campo['nome']])) {
                    if ($campo['array']) {
                        foreach($_GET[$campo['nome']] as $ind => $val) {
                            $_GET[$campo['nome']][$ind] = $GLOBALS[$campo['array']][$GLOBALS['config']['idioma_padrao']][$val];
                        }
                    } elseif ($campo['sql_filtro']) {
                        foreach($_GET[$campo['nome']] as $ind => $val) {
                            $sql = str_replace('%',$val,$campo['sql_filtro']);
                            $seleciona = mysql_query($sql);
                            $linha = mysql_fetch_assoc($seleciona);
                            $_GET[$campo['nome']][$ind] = $linha[$campo['sql_filtro_label']];
                        }
                    }

                    $campoAux = implode($_GET[$campo['nome']], ', ');
                } else {
                    $campoAux = $_GET[$campo['nome']];
                }

                if ($campoAux <> '') {
                    echo '<tr>';
                    echo '<td><strong>'.$idioma[$campo["nomeidioma"]].'</strong></td>';
                    echo '<td>'.$campoAux.'</td>';
                    echo '</tr>';
                }
            }
        }
        echo '</table><br>';


        echo '<table class="zebra-striped" id="sortTableExample">';
        echo '<thead>';
        echo '<tr>';
        foreach ($this->config[$configuracao] as $ind => $valor) {
            $tamanho = ($valor['tamanho']) ? ' style="width: ' . $valor['tamanho'] . 'px;"' : '';

            $th = '<th class="' . $class . ' headerSortReloca">';
            echo $th;

            echo '<div class="headerNew"' . $tamanho . '>' . $idioma[$valor['variavel_lang']] . '</div>';
            echo '</th>';

        }
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        if (count($dados) == 0) { 
            echo '<tr>';
            echo '<td colspan="' . count($this->config[$configuracao]) . '">Nenhuma informação foi encontrada.</td>';
            echo '</tr>';
        } else {
            $inadimplentes = 0;
            foreach ($dados as $i => $linha) {
                $valorTotal += $linha['valor'];
                $matriculas[$linha['idmatricula']] = $linha['idmatricula'];

                if (
                    $linha['conta_pago'] == 'N'
                    && $linha['conta_pagseguro'] == 'N'
                    && $linha['conta_renegociada'] == 'N'
                    && $linha['conta_transferida'] == 'N'
                    && $linha['conta_cancelada'] == 'N'
                    && (new \DateTime($linha['data_vencimento']))->format('Y-m-d') < (new \DateTime)->format('Y-m-d')
                ) {
                    $inadimplentes += $linha['valor'];
                }

                echo '<tr>';
                foreach($this->config[$configuracao] as $ind => $valor) {
                    if($valor['nome'] == 'valor') {
                        $total = $ind;
                    }

                    if ($valor["tipo"] == "banco") {
                        echo '<td>'.stripslashes($linha[$valor["valor"]]).'</td>';
                    } elseif ($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
                        $valor = $valor["valor"]." ?>";
                        $valor = eval($valor);
                        echo '<td>'.stripslashes($valor).'</td>';
                    } elseif ($valor["tipo"] == "array") {
                        $variavel = $GLOBALS[$valor["array"]];
                        echo '<td>'.$variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]].'</td>';
                    } elseif ($valor["busca_tipo"] != "hidden") {
                        echo '<td>'.stripslashes($valor["valor"]).'</td>';
                    }
                }

                echo '</tr>';
            }
                        
            echo '<tr>';
            echo '<td colspan="13" style="text-align: right;">Total:</td>';
            echo '<td>R$ ' . number_format($valorTotal, 2, ',', '.') . '</td>';
            echo '<td colspan="' . (count($this->config[$configuracao]) - 14) . '"></td>';
            echo '</tr>';

        }

        echo '</tbody>';
        echo '</table>';

        if (count($dados) > 0) {
            switch ($_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento']) {
                case 'PER':
                    $periodoDe = $_GET['data_vencimento_de'];
                    $periodoAte = $_GET['data_vencimento_ate'];
                    break;
                case 'HOJ':
                    $periodoDe = (new \DateTime)->format('d/m/Y');
                    $periodoAte = (new \DateTime)->format('d/m/Y');
                    break;
                case 'ONT':
                    $periodoDe = (new \DateTime)->modify('-1 days')->format('d/m/Y');
                    $periodoAte = (new \DateTime)->modify('-1 days')->format('d/m/Y');
                    break;
                case 'SET':
                    $periodoDe = (new \DateTime)->modify('-6 days')->format('d/m/Y');
                    $periodoAte = (new \DateTime)->format('d/m/Y');
                    break;
                case 'QUI':
                    $periodoDe = (new \DateTime)->modify('-15 days')->format('d/m/Y');
                    $periodoAte = (new \DateTime)->format('d/m/Y');
                    break;
                case 'MAT':
                    $periodoDe = (new \DateTime('first day of this month'))->format('d/m/Y');
                    $periodoAte = (new \DateTime('last day of this month'))->format('d/m/Y');
                    break;
                case 'MPR':
                    $periodoDe = (new \DateTime('first day of next month'))->format('d/m/Y');
                    $periodoAte = (new \DateTime('last day of next month'))->format('d/m/Y');
                    break;
                case 'MAN':
                    $periodoDe = (new \DateTime('first day of previous month'))->format('d/m/Y');
                    $periodoAte = (new \DateTime('last day of previous month'))->format('d/m/Y');
                    break;
            }

            echo '<br />
                <table class="zebra-striped" id="sortTableExample">
                    <tr>
                        <td style="text-align: right; font-weight: bold;">Período:</td>
                        <td style="text-align: center; font-weight: bold;">' . $periodoDe . '</td>
                        <td style="text-align: center; font-weight: bold;">' . $periodoAte . '</td>
                    </tr>
                    <tr>
                        <td style="text-align: right; font-weight: bold;">Total de Matrículas:</td>
                        <td colspan="2" style="text-align: center;">
                            ' . count($matriculas) . '
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right; font-weight: bold;">Total a receber:</td>
                        <td colspan="2" style="text-align: center;">
                            R$ ' . number_format($valorTotal, 2, ',', '.') . '
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right; font-weight: bold;">Total inadimplente:</td>
                        <td colspan="2" style="text-align: center;">
                            R$ ' . number_format($inadimplentes, 2, ',', '.') . '
                        </td>
                    </tr>
                </table>';
        }
    }
}
