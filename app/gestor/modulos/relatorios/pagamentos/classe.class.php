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
                m.idmatricula,
                m.data_matricula,
                p.nome,
                s.nome AS sindicato,
                e.nome_fantasia AS escola,
                v.nome AS atendente,
                cid.nome AS cidade,
                est.nome AS estado,
                c.forma_pagamento,
                c.valor AS valor_pf,
                c.data_vencimento AS data_vencimento_pf,
                c.data_pagamento AS data_pagamento_pf,
                IF(
                    c.forma_pagamento = 10 AND c.data_pagamento IS NOT NULL,
                    DATE_ADD(c.data_pagamento, INTERVAL 30 day),
                    NULL
                ) AS bom_para_pf,
                mw.nome AS situacao_matricula,
                "" AS idfatura,
                "" AS valor_pj,
                "" AS data_vencimento_pj,
                "" AS data_pagamento_pj,
                "" AS bom_para_pj,
                c.idconta,
                c.fatura
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
                LEFT OUTER JOIN vendedores v ON (v.idvendedor = m.idvendedor)
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

        if ($_GET['data_matricula_de'] && $_GET['q']['de_ate|tipo_data_matricula|c.data_matricula'] == 'PER') {
            $this->sql .= ' AND DATE_FORMAT(m.data_matricula, "%Y-%m-%d") >= "' . formataData($_GET['data_matricula_de'],'en',0) . '"';
        }

        if ($_GET['data_matricula_ate'] && $_GET['q']['de_ate|tipo_data_matricula|c.data_matricula'] == 'PER') {
            $this->sql .= ' AND DATE_FORMAT(m.data_matricula, "%Y-%m-%d") <= "' . formataData($_GET['data_matricula_ate'],'en',0) . '"';
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
            $this->sql .= ' AND (c.forma_pagamento IN (' . implode(',', $_GET['forma_pagamento']) . ')';

            if (in_array(9, $_GET['forma_pagamento'])) {
                $this->sql .= ' OR c.fatura = "S"';
            }

            $this->sql .= ')';
        }

        $this->sql .= ' GROUP BY c.idconta';

        if (
            ($_GET['q']['de_ate|tipo_data_matricula|m.data_matricula'] == 'PER' || empty($_GET['q']['de_ate|tipo_data_matricula|m.data_matricula']))
            && empty($_GET['data_matricula_de'])
            && empty($_GET['data_matricula_ate'])
            && empty($_GET['idsituacao_matricula'])
            && empty($_GET['q']['1|est.idestado'])
            && empty($_GET['q']['1|cid.idcidade'])
        ) {
            $this->sql .= ' UNION SELECT
                    "" AS idmatricula,
                    "" AS data_matricula,
                    "" AS nome,
                    s.nome AS sindicato,
                    e.nome_fantasia AS escola,
                    "" AS atendente,
                    "" AS cidade,
                    "" AS estado,
                    c.forma_pagamento,
                    "" AS valor_pf,
                    "" AS data_vencimento_pf,
                    "" AS data_pagamento_pf,
                    "" AS bom_para_pf,
                    "" AS situacao_matricula,
                    c.idconta AS idfatura,
                    c.valor AS valor_pj,
                    c.data_vencimento AS data_vencimento_pj,
                    c.data_pagamento AS data_pagamento_pj,
                    IF(
                        c.data_pagamento IS NOT NULL,
                        DATE_ADD(c.data_pagamento, INTERVAL 3 day),
                        NULL
                    ) AS bom_para_pj,
                    c.idconta,
                    c.fatura
                FROM
                    contas c
                    INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao)
                    INNER JOIN escolas e ON (e.idescola = c.idescola)
                    INNER JOIN sindicatos s ON (s.idsindicato = e.idsindicato)
                    LEFT OUTER JOIN pagarme p ON (p.idpagarme = (
                        SELECT
                            pag.idpagarme
                        FROM
                            pagarme pag
                        WHERE
                            pag.idconta = c.idconta AND
                            pag.ativo = "S"
                        ORDER BY
                            pag.idpagarme DESC
                        LIMIT 1
                    ))
                WHERE
                    c.fatura = "S" AND
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

            if ($_GET['idsindicato']) {
                $this->sql .= ' AND c.idsindicato IN (' . implode(',', $_GET['idsindicato']) . ') ';
            }

            $_GET['idescola'] = array_filter(array_unique($_GET['idescola']));
            if ($_GET['idescola']) {
                $this->sql .= ' AND c.idescola IN (' . implode(',', $_GET['idescola']) . ') ';
            }

            if ($_GET['forma_pagamento']) {
                $this->sql .= ' AND (c.forma_pagamento IN (' . implode(',', $_GET['forma_pagamento']) . ')';

                if (in_array(9, $_GET['forma_pagamento'])) {
                    $this->sql .= ' OR c.fatura = "S"';
                }

                $this->sql .= ')';
            }
        }

        $this->groupby = 'c.idconta';
        $this->ordem_campo = 'idconta';
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
            foreach ($dados as $i => $linha) {
                $valorTotalPf += $linha['valor_pf'];
                $valorTotalPj += $linha['valor_pj'];

                echo '<tr>';
                foreach($this->config[$configuracao] as $ind => $valor) {
                    if($valor['nome'] == 'valor') {
                        $total = $ind;
                    }

                    $classTd = null;
                    if (! empty($valor['classTd'])) {
                        $classTd = 'class="' . $valor['classTd'] . '"';
                    }

                    if ($valor['tipo'] == 'banco') {
                        echo '<td ' . $classTd . '>'.stripslashes($linha[$valor['valor']]).'</td>';
                    } elseif ($valor['tipo'] == 'php' && $valor['busca_tipo'] != 'hidden') {
                        $valor = $valor['valor'].' ?>';
                        $valor = eval($valor);
                        echo '<td ' . $classTd . '>'.stripslashes($valor).'</td>';
                    } elseif ($valor['tipo'] == 'array') {
                        $variavel = $GLOBALS[$valor['array']];
                        echo '<td ' . $classTd . '>'.$variavel[$this->config['idioma_padrao']][$linha[$valor['valor']]].'</td>';
                    } elseif ($valor['busca_tipo'] != 'hidden') {
                        echo '<td ' . $classTd . '>'.stripslashes($valor['valor']).'</td>';
                    }
                }

                echo '</tr>';
            }

            echo '<tr>';
            echo '<td colspan="8"></td>';
            echo '<td style="text-align: right;" class="tdAzul">Total:</td>';
            echo '<td class="tdAzul">R$ ' . number_format($valorTotalPf, 2, ',', '.') . '</td>';
            echo '<td colspan="4" class="tdAzul"></td>';
            echo '<td class="tdVerde"></td>';
            echo '<td class="tdVerde">R$ ' . number_format($valorTotalPj, 2, ',', '.') . '</td>';
            echo '<td colspan="' . (count($this->config[$configuracao]) - 16) . '" class="tdVerde"></td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }
}
