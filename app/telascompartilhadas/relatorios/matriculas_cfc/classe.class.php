<?php

class Relatorio extends Core
{
    public function gerarRelatorio()
    {
        if (
            $_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento'] == 'PER'
            && (! $_GET['data_vencimento_de'] || ! $_GET['data_vencimento_ate'])
        ) {
            unset($_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento']);
        }

        if (
            (
                $_GET['q']['de_ate|tipo_data_cad|c.data_cad'] == 'PER'
                && (
                    ! $_GET['data_cad_de'] ||
                    ! $_GET['data_cad_ate']
                )
            )
            || ! array_key_exists($_GET['q']['de_ate|tipo_data_cad|c.data_cad'], $GLOBALS['tipo_data_filtro'][$GLOBALS['config']['idioma_padrao']])
        ) {
            $retorno['erro'] = true;
            $retorno['erros'][] = 'datas_obrigatorias';
            return $retorno;
        }

        if (
            dataDiferenca(formataData($_GET['data_cad_de'], 'en', 0), formataData($_GET['data_cad_ate'], 'en', 0), 'D') > 365
            || dataDiferenca(formataData($_GET['data_vencimento_de'], 'en', 0), formataData($_GET['data_vencimento_ate'], 'en', 0), 'D') > 365
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
                mw.nome AS situacao_matricula,
                m.idmatricula,
                p.nome,
                p.documento,
                p.telefone,
                p.celular,
                p.email,
                m.valor_contrato,
                cm.valor_total AS taxa,
                (IFNULL(m.valor_contrato, 0) - cm.valor_total) AS valor_liquido,
                cw.nome AS situacao,
                c.data_vencimento,
                c.data_pagamento,
                IF(
                    c.data_pagamento IS NOT NULL,
                    DATE_ADD(c.data_pagamento, INTERVAL 3 day),
                    NULL
                ) AS data_prevista_disponivel_pagarme,
                c.data_cad
            FROM
                contas c
                INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao)
                INNER JOIN contas_matriculas cm ON (cm.idconta = c.idconta)
                INNER JOIN matriculas m ON (m.idmatricula = cm.idmatricula)
                INNER JOIN matriculas_workflow mw ON (mw.idsituacao = m.idsituacao)
                INNER JOIN pessoas p ON (p.idpessoa = m.idpessoa)
                INNER JOIN escolas e ON (e.idescola = c.idescola)
                INNER JOIN sindicatos s ON (s.idsindicato = e.idsindicato)
                LEFT OUTER JOIN pagarme pagm ON (pagm.idpagarme = (
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
                c.ativo = "S" AND
                cm.ativo = "S"';

        if ($this->url[0] == 'cfc') {
            $this->sql .= ' AND e.idescola = ' . $this->idescola;
        }

        if ($this->url[0] == 'gestor' && $_SESSION['adm_gestor_sindicato'] != 'S') {
            $this->sql .= ' AND c.idsindicato IN (' . $_SESSION['adm_sindicatos'] . ') ';
        }

        $this->aplicarFiltrosBasicos();

        if (is_array($_GET['q'])) {
            foreach($_GET['q'] as $campo => $valor) {
                $campo = explode('|', $campo);
                $valor = str_replace('\'', '', $valor);

                if (($valor || $valor === '0') && $valor <> 'todos') {
                    if ($campo[0] == 6) {
                        $valor = str_replace(['.', ','], ['', '.'], $valor);
                        $this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '" ';
                    } elseif ($campo[0] == 'de_ate') {
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

        if ($_GET['data_cad_de'] && $_GET['q']['de_ate|tipo_data_cad|c.data_cad'] == 'PER') {
            $this->sql .= ' AND DATE_FORMAT(c.data_cad, "%Y-%m-%d") >= "' . formataData($_GET['data_cad_de'],'en',0) . '"';
        }

        if ($_GET['data_cad_ate'] && $_GET['q']['de_ate|tipo_data_cad|c.data_cad'] == 'PER') {
            $this->sql .= ' AND DATE_FORMAT(c.data_cad, "%Y-%m-%d") <= "' . formataData($_GET['data_cad_ate'],'en',0) . '"';
        }

        if ($_GET['data_vencimento_de'] && $_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento'] == 'PER') {
            $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") >= "' . formataData($_GET['data_vencimento_de'],'en',0) . '"';
        }

        if ($_GET['data_vencimento_ate'] && $_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento'] == 'PER') {
            $this->sql .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") <= "' . formataData($_GET['data_vencimento_ate'],'en',0) . '"';
        }

        if ($_GET['idsindicato']) {
            $this->sql .= ' AND c.idsindicato IN (' . implode(',', $_GET['idsindicato']) . ') ';
        }

        $_GET['idescola'] = array_filter(array_unique($_GET['idescola']));
        if ($_GET['idescola']) {
            $this->sql .= ' AND c.idescola IN (' . implode(',', $_GET['idescola']) . ') ';
        }

        if ($_GET['idconta']) {
            $this->sql .= ' AND c.idconta IN (' . implode(',', $_GET['idconta']) . ') ';
        }

        if ($_GET['idsituacao_conta']) {
            $this->sql .= ' AND c.idsituacao IN (' . implode(',', $_GET['idsituacao_conta']) . ') ';
        }

        $this->groupby = 'c.idconta';
        $this->ordem_campo = 'c.idconta ASC, cm.idconta_matricula';
        $this->ordem = 'ASC';
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function gerarTabela($dados, $q = null, $idioma, $configuracao = "listagem")
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
            $valorTotal = 0;
            $valorTotalTaxa = 0;
            $valorTotalLiquido = 0;

            foreach ($dados as $i => $linha) {
                $valorTotal += $linha['valor_contrato'];
                $valorTotalTaxa += $linha['taxa'];
                $valorTotalLiquido += $linha['valor_liquido'];

                echo '<tr>';
                foreach ($this->config[$configuracao] as $ind => $valor) {
                    if ($valor['nome'] == 'valor') {
                        $total = $ind;
                    }

                    if ($valor['tipo'] == 'banco') {
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
                        
            echo '<tr>
                    <td colspan="' . (($this->url[0] == 'cfc') ? 11 : 13) . '" style="text-align: right;">&nbsp;</td>
                    <td>R$ ' . number_format($valorTotal, 2, ',', '.') . '</td>
                    <td>R$ ' . number_format($valorTotalTaxa, 2, ',', '.') . '</td>
                    <td>R$ ' . number_format($valorTotalLiquido, 2, ',', '.') . '</td>
                    <td colspan="' . (count($this->config[$configuracao]) - 14) . '"></td>
                </tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    public function buscarConta()
    {
        $this->sql = 'SELECT idconta AS "key", idconta AS value FROM contas
            WHERE idconta LIKE "%'.$_GET['tag'].'%" AND fatura = "S" AND ativo = "S"';

        if ($this->url[0] == 'cfc') {
            $this->sql .= ' AND idescola = ' . $this->idescola;
        }

        $this->limite = -1;
        $this->ordem_campo = 'value';
        $dados = $this->retornarLinhas();

        return json_encode($dados);
    }
}
