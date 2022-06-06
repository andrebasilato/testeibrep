<?php

require_once DIR_APP . '/classes/pagarme.class.php';
class Contas extends Core
{
    const TABELA = "contas";

    private $errosLog;

    var $tipo_conta = null;
    var $campos1 = null;
    var $campos2 = null;
    public $totalDados = null;

    function listarAgrupadas()
    {
        $ignorar = array("forn.nome");
        $contas_matriculas = array();

        if ($_GET["q"]["2|forn.nome"] == "") {
            if ($_GET['filtro_dia'] && (!$_GET['filtro_mes'] || !$_GET['filtro_ano'])) {
                $data_quebrada = explode('-', $_GET['filtro_dia']);
                $_GET['filtro_ano'] = $data_quebrada[0];
                $_GET['filtro_mes'] = $data_quebrada[1];
            }

            //Retorna Contas das matrículas
            $this->sql = "SELECT SQL_CACHE
                            " . $this->campos . ",
                            sum(valor) as total,
                            DATE_FORMAT( c.data_vencimento,  '%d-%m-%Y' ) AS vencimento,
                            c.data_vencimento,
                            count(idconta) as qtde_contas,
                            DATE_FORMAT(c.data_vencimento, '%d') as dia
                        FROM
                            contas c
                            INNER JOIN contas_workflow cw ON c.idsituacao = cw.idsituacao
                            INNER JOIN matriculas m ON c.idmatricula = m.idmatricula
                        WHERE
                            c.ativo = 'S' AND
                            c.fatura = 'N' AND
                            c.idmatricula IS NOT NULL AND
                            cw.renegociada <> 'S' AND
                            cw.transferida <> 'S' AND
                            cw.cancelada <> 'S'";

            if ($_SESSION["adm_gestor_sindicato"] <> "S") {
                $this->sql .= " AND m.idsindicato in (" . $_SESSION["adm_sindicatos"] . ") ";
            }

            $filtros = '';
            if ($this->tipo_conta == 'apagar') {
                $filtros .= " and c.tipo = 'despesa' ";
            } elseif ($this->tipo_conta == 'areceber') {
                $filtros .= " and c.tipo = 'receita' ";
            }

            if ($_GET['acao'] == "filtrar_data") {
                if ($_GET['filtro_ano'] && !$_GET['filtro_mes']) {
                    $_GET['filtro_mes'] = date('m');
                }

                if ($_GET['filtro_mes'] && $_GET['filtro_ano']) {
                    $filtros .= " and DATE_FORMAT(c.data_vencimento, '%Y-%m') = '" . $_GET['filtro_ano'] . '-' . $_GET['filtro_mes'] . "' ";
                }
            } else {
                if ($_GET['filtro_dia']) {
                    $filtros .= " and DATE_FORMAT(c.data_vencimento, '%Y-%m-%d') = '" . $_GET['filtro_dia'] . "' ";
                } else {
                    $data_quebrada = explode('-', date("Y-m-d"));
                    $_GET['filtro_ano'] = $data_quebrada[0];
                    $_GET['filtro_mes'] = $data_quebrada[1];
                    $filtros .= " and DATE_FORMAT(c.data_vencimento, '%Y-%m-%d') = '" . date("Y-m-d") . "' ";
                }
            }

            if ($_GET['idsindicato_filtro'] && $_GET['idsindicato_filtro'] != -1) {
                $filtros .= ' and c.idsindicato = ' . $_GET['idsindicato_filtro'] . ' ';
            }

            if (is_array($_GET["q"])) {
                foreach ($_GET["q"] as $campo => $valor) {
                    //explode = Retira, ou seja retira a "|" da variavel campo
                    $campo = explode("|", $campo);
                    $valor = str_replace("'", "", $valor);
                    if (!in_array($campo[1], $ignorar)) {
                        // Listagem se o valor for diferente de Todos ele faz um filtro
                        if (($valor || $valor === "0") and $valor <> "todos") {
                            // se campo[0] for = 1 é pq ele tem de ser um valor exato
                            if ($campo[0] == 1) {
                                $filtros .= " and " . $campo[1] . " = '" . $valor . "' ";
                                // se campo[0] for = 2, faz o filtro pelo comando like
                            } elseif ($campo[0] == 2) {
                                $busca = str_replace("\\'", "", $valor);
                                $busca = str_replace("\\", "", $busca);
                                $busca = explode(" ", $busca);
                                foreach ($busca as $ind => $buscar) {
                                    $filtros .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                                }
                            } elseif ($campo[0] == 3) {
                                $filtros .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                            }
                        }
                    } // Fim do for
                }
            }

            $this->sql .= $filtros;
            $this->sql .= " group BY vencimento ";
            $this->groupby = "vencimento";
            $contas_matriculas = $this->retornarLinhas();

            //Retorna Contas das faturas
            $this->sql = 'SELECT SQL_CACHE
                                ' . $this->campos . ',
                                SUM(valor) AS total,
                                DATE_FORMAT(c.data_vencimento,  "%d-%m-%Y") AS vencimento,
                                c.data_vencimento,
                                count(c.idconta) AS qtde_contas,
                                DATE_FORMAT(c.data_vencimento, "%d") AS dia,
                                c.fatura
                            FROM
                                contas c
                                INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao)
                                INNER JOIN escolas e ON (e.idescola = c.idescola)
                            WHERE
                                c.ativo = "S" AND
                                c.fatura = "S" AND
                                cw.renegociada <> "S" AND
                                cw.transferida <> "S" AND
                                cw.cancelada <> "S"';

            if ($_SESSION['adm_gestor_sindicato'] <> 'S') {
                $this->sql .= ' AND c.idsindicato IN (' . $_SESSION['adm_sindicatos'] . ') ';
            }


            if (is_array($_GET["q"])) {
                foreach ($_GET["q"] as $campo => $valor) {
                    //explode = Retira, ou seja retira a "|" da variavel campo
                    $campo = explode("|", $campo);
                    $valor = str_replace("'", "", $valor);
                    if (!in_array($campo[1], $ignorar)) {
                        // Listagem se o valor for diferente de Todos ele faz um filtro
                        if (($valor || $valor === "0") and $valor <> "todos") {
                            // se campo[0] for = 1 é pq ele tem de ser um valor exato
                            if ($campo[0] == 1) {
                                $filtros .= " and " . $campo[1] . " = '" . $valor . "' ";
                                // se campo[0] for = 2, faz o filtro pelo comando like
                            } elseif ($campo[0] == 2) {
                                $busca = str_replace("\\'", "", $valor);
                                $busca = str_replace("\\", "", $busca);
                                $busca = explode(" ", $busca);
                                foreach ($busca as $ind => $buscar) {
                                    $filtros .= " and " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                                }
                            } elseif ($campo[0] == 3) {
                                $filtros .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                            }
                        }
                    } // Fim do for
                }
            }

            $this->sql .= $filtros;
            $this->sql .= " GROUP BY vencimento";
            $this->groupby = "vencimento";
            $contas_faturas = $this->retornarLinhas();
        }


        $this->campos = $this->campos2;

        //Retorna Contas das matrículas
        $this->sql = "SELECT SQL_CACHE
            " . $this->campos . ",
            DATE_FORMAT(c.data_vencimento, '%d-%m-%Y' ) AS vencimento,
            DATE_FORMAT(c.data_vencimento, '%d') as dia
        FROM
            contas c
            INNER JOIN contas_workflow cw ON c.idsituacao = cw.idsituacao
            LEFT JOIN categorias cat ON c.idcategoria = cat.idcategoria
            LEFT JOIN fornecedores forn ON c.idfornecedor = forn.idfornecedor
        WHERE
            c.ativo = 'S' AND
            c.fatura = 'N' AND
            c.idmatricula IS NULL AND
            cw.renegociada <> 'S' AND
            cw.transferida <> 'S' AND
            cw.cancelada <> 'S'";

        if ($_SESSION["adm_gestor_sindicato"] <> "S") {
            $this->sql .= " AND c.idsindicato in (" . $_SESSION["adm_sindicatos"] . ") ";
        }

        if (is_array($_GET["q"])) {
            foreach ($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|", $campo);
                $valor = str_replace("'", "", $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === "0") and $valor <> "todos") {
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
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " and date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }

        $this->sql .= $filtros;
        $contas = $this->retornarLinhas();

        $contas_todas = array_merge($contas, $contas_matriculas, $contas_faturas);

        $ordenaIndices = array();
        foreach ($contas_todas as $ind => $valor) {
            $ordenaIndices[intval($valor["dia"])][] = $ind;
        }

        ksort($ordenaIndices);

        $contasArray = array();
        foreach ($ordenaIndices as $dia => $subArray) {
            foreach ($subArray as $ind => $indArray) {
                $contasArray[] = $contas_todas[$indArray];
            }
        }

        return $contasArray;
    }

    /**
     * @param array $dados
     * @param null $q
     * @param $idioma
     * @param string $configuracao
     * @param null $classTabela
     */
    public function GerarTabelaContas(array $dados, $q = null, $idioma, $configuracao = 'listagem', $classTabela = NULL)
    {
        echo '<table class="table table-striped ' . $classTabela . '" id="sortTableExample" style="text-transform:uppercase">';
        echo '<thead>';
        echo '<tr>';

        foreach ($this->config[$configuracao] as $valor) {
            $class = "";
            $ordem = "";
            if ('hidden' != $valor['busca_tipo']) {
                if ($this->ordem_campo == $valor["coluna_sql"] && $this->ordem == "asc") {
                    $class = "headerSortDown";
                    $ordem = "desc";
                }
                if ($this->ordem_campo == $valor["coluna_sql"] && $this->ordem == "desc") {
                    $class = "headerSortUp";
                    $ordem = "asc";
                }

                $tamanho = "";
                if ($valor["tamanho"])
                    $tamanho = ' width="' . $valor["tamanho"] . '"';

                $th = '<th class="';

                if (!$valor["busca_botao"])
                    $th .= "header ";

                $th .= $class . ' headerSortReloca" ' . $tamanho . '>';
                echo $th;

                //header
                $urlBusca = NULL;
                if (!$valor["nao_ordenar"] && !$valor["busca_botao"]) {
                    if (is_array($_GET["q"])) {
                        foreach ($_GET["q"] as $ind => $vlr) {
                            $urlBusca .= 'q[' . $ind . ']=' . $vlr . "&";
                        }
                    }
                    echo '<a href="?' . $urlBusca . 'qtd=' . $this->limite . '&cmp=' . $valor["coluna_sql"] . '&ord=' . $ordem . '" title="' . $valor["coluna_sql"] . '">';
                }
                echo "<div class='headerNew'>" . $idioma[$valor["variavel_lang"]] . "</div>";
                if (!$valor["nao_ordenar"] && !$valor["busca_botao"])
                    echo '</a>';

                echo '</th>';
            }
        }

        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';

        if (!$valor["idform"]) {
            $idform = "formBusca";
        } else {
            $idform = $valor["idform"];
        }

        foreach ($this->config[$configuracao] as $ind => $valor) {
            if ($valor["busca"]) {
                $mostrarBusca = true;
                break;
            }
        }

        if ($mostrarBusca) {
            echo '<form action="" method="get" id="' . $idform . '">';
            foreach ($this->config[$configuracao] as $ind => $valor) {
                if ($valor["busca"]) {
                    if ($valor["busca_tipo"] == "select") {
                        $dadosSelect = array();
                        //Carrega os option diretamente do banco de dados
                        if ($valor["busca_sql"]) {
                            $paginas = $this->paginas;
                            $this->sql = $valor["busca_sql"];
                            $limiteAux = $this->limite;
                            $this->limite = -1;
                            $this->ordem_campo = "nome";
                            $this->ordem = "asc";
                            $this->groupby = $valor["busca_sql_valor"];
                            $dadosAux = $this->retornarLinhas();
                            $this->limite = $limiteAux;
                            foreach ($dadosAux as $ind => $campo_banco) {
                                $arrayAux = array("valor" => $campo_banco[$valor["busca_sql_valor"]], "label" => $campo_banco[$valor["busca_sql_label"]]);
                                $dadosSelect[] = $arrayAux;
                            }
                            $this->paginas = $paginas;
                            //Carrega os option de uma variavel global
                        } elseif ($valor["busca_array"]) {
                            $variavel = $GLOBALS[$valor["busca_array"]];
                            if (!$valor["ignoraridioma"]) {
                                $variavel = $variavel[$this->config["idioma_padrao"]];
                            }

                            if (is_array($variavel)) {
                                foreach ($variavel as $ind => $campo_array) {
                                    $arrayAux = array("valor" => $ind, "label" => $campo_array);
                                    $dadosSelect[] = $arrayAux;
                                }
                            }
                        } elseif ($valor["busca_contador"] && $valor["busca_intervalo"]) {
                            $intervalo = explode('-', $valor["busca_intervalo"]);
                            if (count($intervalo) > 1) {
                                $cont_de = $intervalo[0];
                                $cont_ate = $intervalo[1];
                            } else {
                                $cont_de = 0;
                                $cont_ate = $intervalo[0];
                            }

                            for ($cont_de; $cont_de <= $cont_ate; $cont_de++) {
                                $arrayAux = array("valor" => $cont_de, "label" => $cont_de);
                                $dadosSelect[] = $arrayAux;
                            }
                        }

                        echo '<td>';
                        echo '<select name="q[' . $valor["busca_metodo"] . '|' . $valor["coluna_sql"] . ']" id="q[' . $valor["busca_metodo"] . '|' . $valor["coluna_sql"] . ']" class="' . $valor["busca_class"] . '">';
                        echo '<option value=""></option>';

                        foreach ($dadosSelect as $indSelect => $valorSelect) {
                            $selected = '';
                            if ($q[$valor["busca_metodo"] . "|" . $valor["coluna_sql"]] === $valorSelect["valor"]) {
                                $selected = 'selected="selected"';
                            }

                            echo '<option value="' . $valorSelect["valor"] . '" ' . $selected . '>' . stripslashes($valorSelect["label"]) . '</option>';
                        }

                        echo '</select>';
                        echo '</td>';
                        //Monta um hidden
                    } elseif ($valor["busca_tipo"] == "hidden") {
                        if ($valor["tipo"] == "php") {
                            $valor["valor"] = eval($valor["valor"] . " ?>");
                        }

                        echo '<input id="' . $valor["id"] . '" name="' . $valor["nome"] . '" type="hidden" value="' . $valor["valor"] . '" />';
                    } else {
                        echo '<td><input class="' . $valor["busca_class"] . '" id="q[' . $valor["busca_metodo"] . '|' . $valor["coluna_sql"] . ']" name="q[' . $valor["busca_metodo"] . '|' . $valor["coluna_sql"] . ']" type="text" value="' . $q[$valor["busca_metodo"] . "|" . $valor["coluna_sql"]] . '" /></td>';
                    }
                } elseif ($valor["busca_botao"]) {
                    echo '<td><input type="submit" class="btn small" value="' . $idioma["buscar"] . '" /></td>';
                } else {
                    echo '<td>&nbsp;</td>';
                }
            }
            echo '</form>';
        }

        echo '</tr>';


        if (count($dados) == 0) {
            echo '<tr>';
            echo '<td colspan="' . count($this->config[$configuracao]) . '">Nenhuma informação foi encontrada.</td>';
            echo '</tr>';
        } else {
            $vencimentoAux = null;

            foreach ($dados as $i => $linha) {
                if ($vencimentoAux <> $linha["vencimento"]) {
                    $vencimentoDados = explode("-", $linha["vencimento"]);

                    echo '<tr>';
                    echo '<td class="linhaDivisao" colspan="' . count($this->config[$configuracao]) . '"><strong>' . $vencimentoDados[0] . ' de ' . $GLOBALS["meses_idioma"]["pt_br"][$vencimentoDados[1]] . ' de ' . $vencimentoDados[2] . '</strong></td>';
                    echo '</tr>';

                    $vencimentoAux = $linha["vencimento"];
                }

                echo '<tr>';

                foreach ($this->config[$configuracao] as $ind => $valor) {
                    if ($valor["tamanho"]) {
                        $style = " style=\"width:" . $valor["tamanho"] . "px;\"";
                    } else {
                        $style = "";
                    }

                    if ($valor["tipo"] == "banco") {
                        if ($valor["overflow"]) {
                            $linha[$valor["valor"]] = "<div class=\"tabelaOverflow\"$style>" . $linha[$valor["valor"]] . "</div>";
                        }
                        echo '<td>' . stripslashes($linha[$valor["valor"]]) . '</td>';
                    } elseif ($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
                        $valor = $valor["valor"] . " ?>";
                        $valor = eval($valor);

                        if ($valor["overflow"]) {
                            $valor = "<div class=\"tabelaOverflow\"$style>" . $valor . "</div>";
                        }

                        echo '<td>' . stripslashes($valor) . '</td>';
                    } elseif ($valor["tipo"] == "array") {
                        $variavel = $GLOBALS[$valor["array"]];
                        if ($valor["overflow"]) {
                            $variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]] = "<div class=\"tabelaOverflow\"$style>" . $variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]] . "</div>";
                        }

                        echo '<td>' . $variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]] . '</td>';
                    } elseif ($valor["busca_tipo"] != "hidden") {
                        if ($valor["overflow"]) {
                            $valor["valor"] = "<div class=\"tabelaOverflow\"$style>" . $valor["valor"] . "</div>";
                        }

                        echo '<td>' . stripslashes($valor["valor"]) . '</td>';
                    }
                }

                echo '</tr>';
            }
        }

        echo '</tbody>';
        echo '</table>';
    }

    function RetornarMatriculasDia($vencimento)
    {
        $this->sql = 'SELECT
                        ' . $this->campos . '
                    FROM
                        contas c
                        INNER JOIN contas_workflow cw ON c.idsituacao = cw.idsituacao
                        INNER JOIN matriculas m ON m.idmatricula = c.idmatricula
                        INNER JOIN pessoas p ON p.idpessoa = m.idpessoa
                    WHERE
                        c.ativo = "S" AND
                        c.fatura = "N" AND
                        c.data_vencimento = "' . $vencimento . '" AND
                        cw.renegociada <> "S" AND
                        cw.transferida <> "S" AND
                        cw.cancelada <> "S"';

        if ($_SESSION['adm_gestor_sindicato'] <> 'S') {
            $this->sql .= ' AND m.idsindicato IN (' . $_SESSION['adm_sindicatos'] . ') ';
        }

        if ($_GET['acao'] == 'filtrar_data') {
            if ($_GET['filtro_mes'] && $_GET['filtro_ano']) {
                $filtros .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m") = "' . $_GET['filtro_ano'] . '-' . $_GET['filtro_mes'] . '" ';
            }
        } else {
            if ($_GET['filtro_dia']) {
                $filtros .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") = "' . $_GET['filtro_dia'] . '" ';
            } else {
                $filtros .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") = "' . date('Y-m-d') . '" ';
            }
        }

        if ($_GET['idsindicato_filtro'] && $_GET['idsindicato_filtro'] != -1) {
            $this->sql .= ' AND c.idsindicato = ' . $_GET['idsindicato_filtro'] . ' ';
        }

        if ($this->tipo_conta == 'apagar') {
            $this->sql .= " AND c.tipo = 'despesa' ";
        } elseif ($this->tipo_conta == 'areceber') {
            $this->sql .= " AND c.tipo = 'receita' ";
        }

        if (is_array($_GET["q"])) {
            foreach ($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|", $campo);
                $valor = str_replace("'", "", $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === "0") && $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        $this->sql .= " AND " . $campo[1] . " = '" . $valor . "' ";
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= " AND " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= " AND date_format(" . $campo[1] . ",'%d/%m/%Y') = '" . $valor . "' ";
                    }
                }
            }
        }

        $this->ordem = 'ASC';
        $this->ordem_campo = 'c.idconta';
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    function retornar($joinMatriculas = false, $trazerAcoes = true)
    {
        $this->sql = 'SELECT
                            ' . $this->campos . '
                        FROM
                            contas c
                            INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao)';

        if ($joinMatriculas) {
            $this->sql .= ' LEFT OUTER JOIN matriculas m ON (m.idmatricula = c.idmatricula)';
        }

        $this->sql .= ' WHERE c.idconta = ' . $this->id . ' AND c.ativo = "S"';

        if ($this->modulo == 'cfc') {
            $this->sql .= ' AND c.idescola = ' . $this->idescola;
        }

        if ($this->idusuario) {
            $this->sql .= " AND (   (   select ua.idusuario
                                        from usuarios_adm ua
                                            left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = 'S'
                                            left join pagamentos_compartilhados pc on pc.idsindicato = uai.idsindicato
                                            left join escolas p on uai.idsindicato = p.idsindicato
                                            left join matriculas m on p.idescola = m.idescola
                                        where ua.idusuario = " . $this->idusuario . "
                                            and (   ua.gestor_sindicato = 'S'
                                                    or
                                                    (
                                                        c.idmatricula is null and c.idsindicato is null and c.idpagamento_compartilhado is null
                                                    )
                                                    or
                                                    (   c.idmatricula = m.idmatricula and
                                                        uai.idusuario is not null and
                                                        p.idsindicato is not null
                                                    )
                                                    or
                                                    (   uai.idsindicato = c.idsindicato and
                                                        uai.idusuario is not null
                                                    )
                                                    or
                                                    (   pc.idpagamento = c.idpagamento_compartilhado and
                                                        uai.idusuario is not null and
                                                        uai.idsindicato is not null
                                                    )
                                                )
                                        limit 1
                                    ) is not null
                                ) ";
        }

        $this->retorno = $this->retornarLinha($this->sql);

        if (!$this->retorno) {
            return false;
        }

        if ($trazerAcoes) {
            $this->sql = 'SELECT
                                cwa.idacao,
                                cwa.idopcao
                            FROM
                                contas_workflow_acoes cwa
                            WHERE
                                cwa.idsituacao = ' . $this->retorno['idsituacao'] . ' AND
                                cwa.ativo = "S" ';
            $resultado = $this->executaSql($this->sql);

            while ($acao = mysql_fetch_assoc($resultado)) {
                foreach ($GLOBALS['workflow_parametros_contas'] as $op) {
                    if ($op['idopcao'] == $acao['idopcao'] && $op['tipo'] == "visualizacao") {
                        $this->retorno['situacao']['visualizacoes'][$acao['idopcao']] = $acao;
                    }
                }
            }
        }

        return $this->retorno;
    }

    private function salvarParcelaCadastrar($parcela, $situacao, $id_relacao, $numero_parcela, $total_parcelas, $numero_documento)
    {
        if ($this->post['tipo'] == 'despesa')
            $valor = (str_replace(',', '.', str_replace('.', '', $parcela['valor'])) * -1);
        else
            $valor = str_replace(',', '.', str_replace('.', '', $parcela['valor']));

        $this->sql = "INSERT INTO contas SET
                data_cad = NOW(),
                tipo = '" . $this->post['tipo'] . "',
                nome = '" . $parcela['nome'] . "',
                valor = '" . $valor . "',
                numero_documento = '" . $numero_documento . "',
                data_vencimento = '" . formataData($parcela['data_vencimento'], 'en', 0) . "',
                idsituacao = '" . $situacao . "',
                idrelacao = '" . $id_relacao . "',
                parcela = '" . $numero_parcela . "',
                total_parcelas = '" . $total_parcelas . "' ";

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

        if ($parcela['data_pagamento'])
            $this->sql .= ", data_pagamento = '" . formataData($parcela['data_pagamento'], 'en', 0) . "'  ";
        if ($parcela['valor_pago'])
            $this->sql .= ", valor_pago = '" . str_replace(',', '.', str_replace('.', '', $parcela['valor_pago'])) . "'  ";
        if ($parcela['documento'])
            $this->sql .= ", documento = '" . str_replace(',', '.', str_replace('.', '', $parcela['documento'])) . "'  ";

        if ($parcela['valor_juros'])
            $this->sql .= ", valor_juros = '" . str_replace(',', '.', str_replace('.', '', $parcela['valor_juros'])) . "'  ";
        if ($parcela['valor_outro'])
            $this->sql .= ", valor_outro = '" . str_replace(',', '.', str_replace('.', '', $parcela['valor_outro'])) . "'  ";
        if ($parcela['valor_multa'])
            $this->sql .= ", valor_multa = '" . str_replace(',', '.', str_replace('.', '', $parcela['valor_multa'])) . "'  ";
        if ($parcela['valor_desconto'])
            $this->sql .= ", valor_desconto = '" . str_replace(',', '.', str_replace('.', '', $parcela['valor_desconto'])) . "'  ";

        if ($this->post['idsindicato']) {
            $sql = "select * from sindicatos where idsindicato='" . $this->post['idsindicato'] . "'";
            $sindicato = $this->retornarLinha($sql);

            $this->sql .= ", idmantenedora = '" . $sindicato['idmantenedora'] . "'  ";
            $this->sql .= ", idsindicato = '" . $this->post['idsindicato'] . "'  ";
        }

        if ($this->post['idfornecedor'])
            $this->sql .= ", idfornecedor = '" . $this->post['idfornecedor'] . "'  ";
        if ($this->post['idproduto'])
            $this->sql .= ", idproduto = '" . $this->post['idproduto'] . "'  ";
        if ($this->post['idescola'])
            $this->sql .= ", idescola = '" . $this->post['idescola'] . "'  ";
        if ($this->post['idcategoria'])
            $this->sql .= ", idcategoria = '" . $this->post['idcategoria'] . "'  ";
        if ($this->post['idsubcategoria'])
            $this->sql .= ", idsubcategoria = '" . $this->post['idsubcategoria'] . "'  ";
        if ($this->post['idconta_corrente'])
            $this->sql .= ", idconta_corrente = '" . $this->post['idconta_corrente'] . "'  ";
        if ($this->post['idpessoa'])
            $this->sql .= ", idpessoa = '" . $this->post['idpessoa'] . "'  ";
        if ($this->post['idordemdecompra'])
            $this->sql .= ", idordemdecompra = '" . $this->post['idordemdecompra'] . "'  ";
        if ($this->post['ativo_painel'])
            $this->sql .= ", ativo_painel = '" . $this->post['ativo_painel'] . "'  ";

        if ($this->executaSql($this->sql)) {
            $this->id = mysql_insert_id();

            $salvarCentroCusto = $this->salvarCentroCustoCadastrar($numero_parcela, $total_parcela, $parcela["valor"]);
            if ($salvarCentroCusto["erro"]) {
                return $salvarCentroCusto;
            }

            $this->AdicionarHistorico("situacao", "modificou", NULL, $situacao, NULL);

            $this->monitora_oque = 1;
            $this->monitora_onde = "52";
            $this->monitora_qual = $this->id;
            $this->Monitora();
        } else {
            mysql_query("ROLLBACK");
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_inserir_parcela';
            return $this->retorno;
        }
    }

    private function salvarCentroCustoCadastrar($numero_parcela, $total_parcelas, $valor = null)
    {

        if ($valor) {
            $this->post['valor'] = $valor;
        }

        $valor_liquido = (str_replace(array('.', ','), array('', '.'), $this->post['valor']) +
            str_replace(array('.', ','), array('', '.'), $this->post['valor_juros']) +
            str_replace(array('.', ','), array('', '.'), $this->post['valor_multa']) +
            str_replace(array('.', ','), array('', '.'), $this->post['valor_outro']) -
            str_replace(array('.', ','), array('', '.'), $this->post['valor_desconto']));

        if ($this->post['idcentro_custo'] != -100) {
            $sql = '
                insert into contas_centros_custos
                set
                    ativo = "S",
                    data_cad = NOW(),
                    idconta = "' . $this->id . '",
                    idcentro_custo = "' . $this->post['idcentro_custo'] . '",
                    valor = "' . $valor_liquido . '",
                    porcentagem = "100" ';
            if ($this->executaSql($sql)) {
                $this->AdicionarHistorico("conta_centro_custo", "cadastrou", null, null, mysql_insert_id());
            } else {
                mysql_query("ROLLBACK");
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_inserir_conta_centro_custo';
                return $this->retorno;
            }
        } else {
            $total_centro_custo = count($this->post['centros_array']);
            if ($total_centro_custo) {
                $total_porcentagem = 0;
                $total_valor = 0;

                foreach ($this->post['centros_array'] as $i_centro => $centro) {
                    $porcentagemCentroCusto = str_replace(',', '.', $centro['porcentagem']);
                    $total_porcentagem += $porcentagemCentroCusto;

                    //Calcula o valor do centro de custo pela porcentagem passada
                    $valorCentroCusto = $porcentagemCentroCusto * $valor_liquido / 100;
                    //Valor total do centro de custo
                    $total_valor += str_replace(array('.', ','), array('', '.'), $this->post['centros_array'][$i_centro]['valor']);
                    //Passa o valor do centro de custo para padrão brasileiro
                    $this->post['centros_array'][$i_centro]['valor'] = number_format($valorCentroCusto, 2, ',', '.');

                    if ($i_centro == count($this->post['centros_array']) && $total_valor > $valor_liquido) {
                        //Diminui a diferença a mais do valor total sobre o liquído no valor do centro de custo final.
                        $valorCentroCusto -= $total_valor - $valor_liquido;
                        $this->post['centros_array'][$i_centro]['valor'] = number_format($valorCentroCusto, 2, ',', '.');

                        //Atualiza o valor total diminuindo a diferença anterior
                        $total_valor -= $total_valor - $valor_liquido;
                    }
                }

                //$porcentagem_invalida = (100 != $total_porcentagem && $total_porcentagem != 0);
                $porcentagem_invalida = (strnatcasecmp($total_porcentagem, 100) != 0 && $total_porcentagem != 0);
                //$valor_invalido = ($total_valor != $valor_liquido && $total_valor != 0);
                $valor_invalido = (strnatcasecmp($total_valor, $valor_liquido) != 0 && $total_valor != 0);

                if ($total_porcentagem == 0 && $total_valor == 0) {
                    mysql_query("ROLLBACK");
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_obrigatorio_porcentagem_valor';
                    return $this->retorno;
                } else {
                    if ($porcentagem_invalida) {
                        if ($total_porcentagem > 99 && $total_porcentagem < 100) {
                            $resgate = 100 - $total_porcentagem;
                            $this->post['centros_array'][1]['porcentagem'] = str_replace(',', '.', $this->post['centros_array'][1]['porcentagem']) + str_replace(',', '.', $resgate);
                        } else {
                            $this->retorno["erro"] = true;
                            $this->retorno["erros"][] = 'erro_inserir_conta_centro_custo_porcentagem';
                            return $this->retorno;
                        }
                    }
                    if ($valor_invalido) {
                        if (($valor_liquido < $valor_total) && ($valor_liquido > ($valor_total - 5))) {
                            $resgate = $valor_liquido - $total_valor;
                            $this->post['centros_array'][1]['valor'] += $resgate;
                        } else {
                            $this->retorno["erro"] = true;
                            $this->retorno["erros"][] = 'erro_inserir_conta_centro_custo_valor';
                            return $this->retorno;
                        }
                    }
                }
                /*
                if($porcentagem_invalida && $valor_invalido) {
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'porcentagem_valor_invalidos';
                    return $this->retorno;
                }
                */
                foreach ($this->post['centros_array'] as $ind_centro => $centro) {

                    $this->sql = "select count(idconta_centro_custo) as total, idconta_centro_custo, ativo from contas_centros_custos where idconta = '" . $this->id . "' and idcentro_custo = '" . $centro['idcentro_custo'] . "'";
                    $resultado = $this->executaSql($this->sql);
                    if (!$resultado) {
                        mysql_query("ROLLBACK");
                        $this->retorno["erro"] = true;
                        $this->retorno["erros"][] = 'erro_inserir_conta_centro_custo';
                        return $this->retorno;
                    }
                    $totalAss = mysql_fetch_assoc($resultado);
                    if ($totalAss["total"] > 0) {
                        if ($totalAss["ativo"] == 'N') {
                            $this->sql = "update contas_centros_custos set ativo = 'S' where idconta_centro_custo = " . $totalAss["idconta_centro_custo"];
                            $associar = $this->executaSql($this->sql);
                            if (!$associar) {
                                mysql_query("ROLLBACK");
                                $this->retorno["erro"] = true;
                                $this->retorno["erros"][] = 'erro_inserir_conta_centro_custo';
                                return $this->retorno;
                            }
                            $this->monitora_qual = $totalAss["idconta_centro_custo"];
                        } else {
                            mysql_query("ROLLBACK");
                            $this->retorno["erro"] = true;
                            $this->retorno["erros"][] = 'erro_inserir_conta_centro_custo_associado';
                            return $this->retorno;
                        }
                    } else {

                        $sql = '
                            insert into contas_centros_custos
                            set
                                ativo = "S",
                                data_cad = NOW(),
                                idconta = "' . $this->id . '",
                                idcentro_custo = "' . $centro['idcentro_custo'] . '",
                                valor = "' . str_replace(array('.', ','), array('', '.'), $centro['valor']) . '",
                                porcentagem = "' . str_replace(',', '.', $centro['porcentagem']) . '"   ';
                        if ($this->executaSql($sql)) {
                            $this->AdicionarHistorico("conta_centro_custo", "cadastrou", null, null, mysql_insert_id());
                        } else {
                            mysql_query("ROLLBACK");
                            $this->retorno["erro"] = true;
                            $this->retorno["erros"][] = 'erro_inserir_conta_centro_custo';
                            return $this->retorno;
                        }
                    }
                }
            } else {
                mysql_query("ROLLBACK");
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_centro_custo_obrigatorio';
                return $this->retorno;
            }
        }
    }

    function Cadastrar()
    {
        if ($this->post['forma_pagamento'] == '2' || $this->post['forma_pagamento'] == '3') {
            $config_remover = array(
                'idbanco',
                'agencia_cheque',
                'cc_cheque',
                'numero_cheque',
                'emitente_cheque'
            );
        } elseif ($this->post['forma_pagamento'] == '4') {
            $config_remover = array(
                'idbandeira',
                'autorizacao_cartao'
            );
        } else {
            $config_remover = array(
                'idbandeira',
                'autorizacao_cartao',
                'idbanco',
                'agencia_cheque',
                'cc_cheque',
                'numero_cheque',
                'emitente_cheque'
            );
        }

        if ($this->post['tipo'] == 'despesa') {
            $config_remover[] = 'idcliente';
        } elseif ($this->post['tipo'] == 'receita') {
            $config_remover[] = 'idfornecedor';
            $config_remover[] = 'idproduto';
        }
        $this->config['formulario'] = $this->alterarConfigFormulario($this->config['formulario'], $config_remover);

        $situacao = $this->retornarSituacaoEmAFaturar();

        mysql_query("START TRANSACTION");
        $sql_relacao = "insert into contas_relacoes set data_cad = NOW()";
        $this->executaSql($sql_relacao);
        $id_relacao = mysql_insert_id();

        $total_parcelas = count($this->post['parcelas_array']);
        if ($total_parcelas > 1) {
            foreach ($this->post['parcelas_array'] as $ind => $parcela) {
                $salvarParcela = $this->salvarParcelaCadastrar($parcela, $situacao['idsituacao'], $id_relacao, $ind, $total_parcelas, $this->post["numero_documento"]);
                if ($salvarParcela["erro"]) {
                    return $salvarParcela;
                }
            }
        } else {

            $salvarParcela = $this->salvarParcelaCadastrar($this->post, $situacao['idsituacao'], $id_relacao, 1, 1, $this->post["numero_documento"]);
            if ($salvarParcela["erro"]) {
                return $salvarParcela;
            }
        }

        mysql_query("COMMIT");
        $this->retorno["sucesso"] = true;
        return $this->retorno;
    }

    function quitar()
    {
        $sql_antigo = "select * from contas where idconta = '" . $this->post['idconta'] . "' ";
        $antigo = $this->retornarLinha($sql_antigo);

        $sql = "select * from contas_workflow where ativo = 'S' and pago = 'S' ";
        $workflow_pago = $this->retornarLinha($sql);

        $this->sql = "update contas set
                                data_pagamento = '" . formataData($this->post['data_pagamento'], 'en', 0) . "',
                                valor_pago = '" . str_replace(',', '.', str_replace('.', '', $this->post['valor_pago'])) . "',
                                idsituacao = '" . $workflow_pago['idsituacao'] . "'
                            where idconta = '" . $this->post['idconta'] . "' ";

        if ($this->executaSql($this->sql)) {
            $this->id = $this->post['idconta'];
            $this->AdicionarHistorico("situacao", "modificou", $antigo['idsituacao'], $workflow_pago["idsituacao"], NULL);

            $this->monitora_oque = 1;
            $this->monitora_onde = "52";
            $this->monitora_qual = $this->id;
            $this->Monitora();
            $this->retorno["sucesso"] = true;
        } else {
            mysql_query("ROLLBACK");
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_quitar_conta';
        }

        return $this->retorno;
    }

    function Modificar()
    {
        if ($this->post['forma_pagamento'] == '2' || $this->post['forma_pagamento'] == '3') {
            $config_remover = array(
                'idbanco',
                'agencia_cheque',
                'cc_cheque',
                'numero_cheque',
                'emitente_cheque'
            );
        } elseif ($this->post['forma_pagamento'] == '4') {
            $config_remover = array(
                'idbandeira',
                'autorizacao_cartao'
            );
        } else {
            $config_remover = array(
                'idbandeira',
                'autorizacao_cartao',
                'idbanco',
                'agencia_cheque',
                'cc_cheque',
                'numero_cheque',
                'emitente_cheque'
            );
        }
        if ($this->post['tipo'] == 'despesa') {
            $config_remover[] = 'idcliente';
        } elseif ($this->post['tipo'] == 'receita') {
            $config_remover[] = 'idfornecedor';
            $config_remover[] = 'idproduto';
        }

        $this->executaSql('BEGIN');

        $sql = "update contas set " . implode(' = NULL, ', $config_remover) . " = NULL where idconta = " . $this->post['idconta'];
        if (!$this->executaSql($sql)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_conta_editar';
            return $this->retorno;
        }

        $config_remover[] = 'parcelas';
        $config_remover[] = 'idcentro_custo';
        $config_remover[] = 'quantidade_centro_custo';


        $this->config['formulario'] = $this->alterarConfigFormulario($this->config['formulario'], $config_remover);

        $this->sql = "SELECT idsituacao FROM contas_workflow WHERE cancelada='S' and ativo='S'";
        $wf_cancelada = $this->retornarLinha($this->sql);

        /*$this->sql = "SELECT idsituacao FROM contas_workflow WHERE pago='S' and ativo='S'";
        $wf_pago = $this->retornarLinha($this->sql);*/

        $this->id = $this->post['idconta'];
        if ($this->post['tipo'] == 'despesa') {
            $this->post['valor'] = str_replace(',', '.', str_replace('.', '', $this->post['valor']));
            $this->post['valor'] = ($this->post['valor'] * -1);
            $this->post['valor'] = number_format($this->post['valor'], 2, ',', '.');
        }

        $sql_antigo = "select * from contas where idconta = '" . $this->post['idconta'] . "' ";
        $antigo = $this->retornarLinha($sql_antigo);


        /*if($wf_pago['idsituacao'] == $antigo['idsituacao']) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_conta_paga';
            return $this->retorno;
        } else */
        if ($wf_cancelada['idsituacao'] == $antigo['idsituacao']) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'conta_cancelada';
            return $this->retorno;
        }

        $salvar = $this->SalvarDados();

        if ($salvar['sucesso']) {
            $sql_novo = "select * from contas where idconta = '" . $this->post['idconta'] . "' ";
            $novo = $this->retornarLinha($sql_novo);

            foreach ($antigo as $ind => $campo) {
                if ($novo[$ind] != $campo)
                    $this->AdicionarHistorico($ind, "modificou", $antigo[$ind], $novo[$ind], NULL);
            }

            //Valor liquído da conta
            $valorLiquido = (abs($novo['valor']) + $novo['valor_juros'] + $novo['valor_multa'] + $novo['valor_outro'] - $novo['valor_desconto']);

            //Retorna os centros de custo da conta
            $centrosCustos = $this->ListarCentrosAssociadas($salvar['id']);
            if (count($centrosCustos) == 1) { //Caso tenha apenas um centro de custo irá setar a porcentagem 100% e todo o valor para ele
                $this->post['centros_custos_array'] = array();
                $this->post['centros_custos_array'][$centrosCustos[0]['idconta_centro_custo']]['valor'] = str_replace('.', ',', $valorLiquido);
                $this->post['centros_custos_array'][$centrosCustos[0]['idconta_centro_custo']]['porcentagem'] = 100;
            }

            $salvarCentroCusto = $this->SalvarPorcentagensCentrosCustos($salvar['id']);
            if ($salvarCentroCusto['sucesso']) {
                $this->executaSql('COMMIT');
            } else {
                $this->executaSql('ROLLBACK');

                $salvar['sucesso'] = false;
                $salvar['erro'] = true;
                $salvar['erros'] = $salvarCentroCusto['erros'];
            }
        } else {
            $this->executaSql('ROLLBACK');
        }

        return $salvar;
    }

    function Remover()
    {

        if (!$this->post['idmotivo'] || !$this->post['remover']) {
            $this->retorno["erro"] = true;
            if (!$this->post['idmotivo']) {
                $this->retorno["erros"][] = 'erro_idmotivo_vazio';
            }
            if (!$this->post['remover']) {
                $this->retorno["erros"][] = 'erro_idconta_vazio';
            }
            return $this->retorno;
        }

        if (!$this->post['remover']) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_idconta_vazio';
            return $this->retorno;
        }


        $this->sql = "SELECT idsituacao FROM contas_workflow WHERE cancelada='S' and ativo='S'";
        $wf_cancelada = $this->retornarLinha($this->sql);

        $sql_antigo = "select * from contas where idconta = " . $this->post['remover'] . " ";
        $antigo = $this->retornarLinha($sql_antigo);

        if ($wf_cancelada['idsituacao'] == $antigo['idsituacao']) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'conta_cancelada';
            return $this->retorno;
        }

        $sql = "update contas set idsituacao = '" . $wf_cancelada['idsituacao'] . "', idmotivo = '" . $this->post['idmotivo'] . "' where idconta = " . $this->post['remover'] . " ";
        $conta_cancelada = $this->executaSql($sql);
        if (!$conta_cancelada) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_cancelar_conta';
            return $this->retorno;
        }

        $this->id = $this->post['remover'];
        $this->AdicionarHistorico("situacao", "modificou", $antigo["idsituacao"], $wf_cancelada["idsituacao"]);
        $this->AdicionarHistorico('idmotivo', "modificou", NULL, $this->post['idmotivo'], NULL);

        $this->retorno['sucesso'] = true;
        return $this->retorno;
    }

    function RemoverFaturas()
    {
        //Função para cancelar todas as contas de uma fatura - (módulo de contas)

        if (!$this->post['idmotivo'] || !$this->post['remover']) {
            $this->retorno["erro"] = true;
            if (!$this->post['idmotivo']) {
                $this->retorno["erros"][] = 'erro_idmotivo_vazio';
            }
            if (!$this->post['remover']) {
                $this->retorno["erros"][] = 'erro_idconta_vazio';
            }
            return $this->retorno;
        }

        if (!$this->post['remover']) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_idconta_vazio';
            return $this->retorno;
        }


        $this->sql = "SELECT idsituacao FROM contas_workflow WHERE cancelada='S' and ativo='S'";
        $situacaoCancelada = $this->retornarLinha($this->sql);

        $sql_faturas = "SELECT
                        c.idconta, c.idsituacao
                        FROM
                            contas c
                            INNER JOIN contas_workflow cw ON ( cw.idsituacao = c.idsituacao )
                        WHERE
                            c.ativo = 'S'
                            AND c.fatura = 'S'
                            AND c.data_vencimento = '". $this->post['data_fatura'] ."'
                            AND cw.renegociada <> 'S'
                            AND cw.transferida <> 'S'
                            AND cw.cancelada <> 'S'
                        GROUP BY
                            c.idconta";
        $sql_contas = $this->retornarLinhasArray($sql_faturas);


        foreach ($sql_contas as $sql_conta) {

            $sql = "update contas set data_modificacao_fatura = NOW(), idsituacao = '" . $situacaoCancelada['idsituacao'] . "', idmotivo = '" . $this->post['idmotivo'] . "' where idconta in (" . $sql_conta['idconta'] . ") ";
            $conta_cancelada = $this->executaSql($sql);
            if (!$conta_cancelada) {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_cancelar_conta';
                return $this->retorno;
            }

            $this->id = $sql_conta['idconta'];
            $this->AdicionarHistorico("situacao", "modificou", $sql_conta["idsituacao"], $situacaoCancelada["idsituacao"]);
            $this->AdicionarHistorico('idmotivo', "modificou", NULL, $this->post['idmotivo'], NULL);
        }
        $this->retorno['sucesso'] = true;
        return $this->retorno;
    }

    function AdicionarHistorico($tipo, $acao, $de, $para, $id)
    {

        $this->sql = "insert
                        contas_historicos
                      set
                        idconta = '" . $this->id . "',
                        data_cad = now(),
                        tipo = '" . $tipo . "',
                        acao = '" . $acao . "'";

        if ($this->modulo == "gestor") $this->sql .= ", idusuario = '" . $this->idusuario . "'";
        if ($this->modulo == "vendedor") $this->sql .= ", idvendedor = '" . $this->idvendedor . "'";
        if ($this->modulo == "cfc") $this->sql .= ", idescola = '" . $this->idescola . "'";

        if ($de) $this->sql .= ", de = '" . $de . "'";
        else  $de = uniqid();
        if ($para) $this->sql .= ", para = '" . $para . "'";
        else  $para = uniqid();
        if ($id) $this->sql .= ", id = '" . $id . "'";

        if ($de != $para or $tipo == 'arquivo')
            return $this->executaSql($this->sql);
        else
            return true;
    }

    function RetornarHistoricos()
    {

        $this->sql = "SELECT idsituacao FROM contas_workflow WHERE pago='S' and ativo='S'";
        $wf_pago = $this->retornarLinha($this->sql);

        $historicos = array();
        $this->sql = "SELECT * FROM contas_historicos
                        WHERE idconta='" . $this->id . "'order by data_cad desc";
        $seleciona = $this->executaSql($this->sql);

        while ($historico = mysql_fetch_assoc($seleciona)) {

            $historico["modulo"] = "Sistema";
            $historico["situacao_pago"] = $wf_pago["idsituacao"];

            if ($historico["tipo"] == "situacao") {
                $this->sql = "SELECT * FROM contas_workflow WHERE idsituacao='" . $historico["de"] . "'";
                $historico["situacao"]["de"] = $this->retornarLinha($this->sql);

                $this->sql = "SELECT * FROM contas_workflow WHERE idsituacao='" . $historico["para"] . "'";
                $historico["situacao"]["para"] = $this->retornarLinha($this->sql);
            }

            if ($historico["idusuario"]) {
                $this->sql = "SELECT nome FROM usuarios_adm WHERE idusuario='" . $historico["idusuario"] . "'";
                $historico["usuario"] = $this->retornarLinha($this->sql);
                $historico["modulo"] = "Gestor";
            } elseif ($historico["idvendedor"]) {
                $this->sql = "SELECT nome FROM vendedores WHERE idvendedor='" . $historico["idvendedor"] . "'";
                $historico["usuario"] = $this->retornarLinha($this->sql);
                $historico["modulo"] = "Vendedor";
            }

            if ($historico["tipo"] == 'idconta_corrente') {
                $this->sql = "SELECT nome from contas_correntes where idconta_corrente = '" . $historico["de"] . "'";
                $historico["conta_corrente"]['de'] = $this->retornarLinha($this->sql);

                $this->sql = "SELECT nome from contas_correntes where idconta_corrente = '" . $historico["para"] . "'";
                $historico["conta_corrente"]['para'] = $this->retornarLinha($this->sql);
            } else if ($historico["tipo"] == 'idpessoa') {
                $this->sql = "SELECT nome from pessoas where idpessoa = '" . $historico["de"] . "'";
                $historico["pessoa"]['de'] = $this->retornarLinha($this->sql);

                $this->sql = "SELECT nome from pessoas where idpessoa = '" . $historico["para"] . "'";
                $historico["pessoa"]['para'] = $this->retornarLinha($this->sql);
            } else if ($historico["tipo"] == 'idcategoria') {
                $this->sql = "SELECT nome from categorias where idcategoria = '" . $historico["de"] . "'";
                $historico["categoria"]['de'] = $this->retornarLinha($this->sql);

                $this->sql = "SELECT nome from categorias where idcategoria = '" . $historico["para"] . "'";
                $historico["categoria"]['para'] = $this->retornarLinha($this->sql);
            } else if ($historico["tipo"] == 'idproduto') {
                $this->sql = "SELECT nome from produtos where idproduto = '" . $historico["de"] . "'";
                $historico["produto"]['de'] = $this->retornarLinha($this->sql);

                $this->sql = "SELECT nome from produtos where idproduto = '" . $historico["para"] . "'";
                $historico["produto"]['para'] = $this->retornarLinha($this->sql);
            } else if ($historico["tipo"] == 'idfornecedor') {
                $this->sql = "SELECT nome from fornecedores where idfornecedor = '" . $historico["de"] . "'";
                $historico["fornecedor"]['de'] = $this->retornarLinha($this->sql);

                $this->sql = "SELECT nome from fornecedores where idfornecedor = '" . $historico["para"] . "'";
                $historico["fornecedor"]['para'] = $this->retornarLinha($this->sql);
            } else if ($historico["tipo"] == 'idsindicato') {
                $this->sql = "SELECT nome from sindicatos where idsindicato = '" . $historico["de"] . "'";
                $historico["sindicato"]['de'] = $this->retornarLinha($this->sql);

                $this->sql = "SELECT nome from sindicatos where idsindicato = '" . $historico["para"] . "'";
                $historico["sindicato"]['para'] = $this->retornarLinha($this->sql);
            } else if ($historico["tipo"] == 'idmantenedora') {
                $this->sql = "SELECT nome_fantasia from mantenedoras where idmantenedora = '" . $historico["de"] . "'";
                $historico["mantenedora"]['de'] = $this->retornarLinha($this->sql);

                $this->sql = "SELECT nome_fantasia from mantenedoras where idmantenedora = '" . $historico["para"] . "'";
                $historico["mantenedora"]['para'] = $this->retornarLinha($this->sql);
            } else if ($historico["tipo"] == 'idbanco') {
                $this->sql = "SELECT nome from bancos where idbanco = '" . $historico["de"] . "'";
                $historico["banco"]['de'] = $this->retornarLinha($this->sql);

                $this->sql = "SELECT nome from bancos where idbanco = '" . $historico["para"] . "'";
                $historico["banco"]['para'] = $this->retornarLinha($this->sql);
            } else if ($historico["tipo"] == 'idbandeira') {
                $this->sql = "SELECT nome from bandeiras_cartoes where idbandeira = '" . $historico["de"] . "'";
                $historico["banco"]['de'] = $this->retornarLinha($this->sql);

                $this->sql = "SELECT nome from bandeiras_cartoes where idbandeira = '" . $historico["para"] . "'";
                $historico["banco"]['para'] = $this->retornarLinha($this->sql);
            } else if ($historico["tipo"] == 'idevento') {
                $this->sql = "SELECT nome from eventos_financeiros where idevento = '" . $historico["de"] . "'";
                $historico["idevento"]['de'] = $this->retornarLinha($this->sql);

                $this->sql = "SELECT nome from eventos_financeiros where idevento = '" . $historico["para"] . "'";
                $historico["idevento"]['para'] = $this->retornarLinha($this->sql);
            } else if ($historico["tipo"] == 'conta_centro_custo') {
                $this->sql = "SELECT cc.nome
                                from centros_custos cc
                                inner join contas_centros_custos ccc on cc.idcentro_custo = ccc.idcentro_custo
                                where ccc.idconta_centro_custo = '" . $historico["id"] . "'";
                $historico["centro_custo"] = $this->retornarLinha($this->sql);
            } else if ($historico["tipo"] == 'idmotivo') {
                $this->sql = "SELECT nome from motivos_cancelamento_conta where idmotivo = '" . $historico["para"] . "'";
                $historico["idmotivo"]['para'] = $this->retornarLinha($this->sql);
            }

            switch ($historico["tipo"]) {
                case "arquivo":
                    $arquivo = $this->retornarLinha(sprintf('SELECT * FROM contas_arquivos WHERE idarquivo = %d', $historico['id']));
                    switch ($historico['acao']) {
                        case "cadastrou":
                            if ($arquivo['arquivo_nome']) {
                                $historico["descricao"] = sprintf('Cadastrou o arquivo <strong>%s</strong> à pasta virtual, <strong>%s</strong>', $arquivo['arquivo_nome'], $arquivo['nome_arquivo']);
                            } else {
                                $historico["descricao"] = sprintf('Cadastrou um arquivo na pasta virtual, nome:<strong>%s</strong>', $arquivo['nome_arquivo']);
                            }
                            break;
                        case 'enviou':
                            $historico["descricao"] = sprintf('Enviou o arquivo <strong>%s</strong> à pasta virtual, nome:<strong>%s</strong>', $arquivo['arquivo_nome'], $arquivo['nome_arquivo']);
                            break;
                        case 'removeu':
                            $historico["descricao"] = sprintf('Removeu o arquivo <strong>%s</strong> da pasta virtual', $arquivo['arquivo_nome']);
                            break;
                    }
                    break;

                case "situacao":
                    switch ($historico["acao"]) {
                        case "modificou":
                            if ($historico["situacao"]["de"]) {
                                $historico["descricao"] = "Modificou a situação da conta.<br>De <span class=\"label\" style=\"background-color:#" . $historico["situacao"]["de"]["cor_bg"] . "; color:#" . $historico["situacao"]["de"]["cor_nome"] . "\">" . $historico["situacao"]["de"]["nome"] . "</span> para <span class=\"label\" style=\"background-color:#" . $historico["situacao"]["para"]["cor_bg"] . "; color:#" . $historico["situacao"]["para"]["cor_nome"] . "\">" . $historico["situacao"]["para"]["nome"] . "</span>.";
                            } else {
                                $historico["descricao"] = "Modificou a situação da conta.<br>Para <span class=\"label\" style=\"background-color:#" . $historico["situacao"]["para"]["cor_bg"] . "; color:#" . $historico["situacao"]["para"]["cor_nome"] . "\">" . $historico["situacao"]["para"]["nome"] . "</span>.";
                            }
                            break;
                    }
                    break;
                case "valor":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o valor da conta <br />de: <strong>" . number_format($historico["de"], 2, ',', '.') . "</strong> <br />para: <strong>" . number_format($historico["para"], 2, ',', '.') . "</strong>.";
                            break;
                    }
                    break;
                case "valor_desconto":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o valor da conta <br />de: <strong>" . number_format($historico["de"], 2, ',', '.') . "</strong> <br />para: <strong>" . number_format($historico["para"], 2, ',', '.') . "</strong>.";
                            break;
                    }
                    break;
                case "valor_multa":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o valor da multa <br />de: <strong>" . number_format($historico["de"], 2, ',', '.') . "</strong> <br />para: <strong>" . number_format($historico["para"], 2, ',', '.') . "</strong>.";
                            break;
                    }
                    break;
                case "valor_outro":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o valor outro <br />de: <strong>" . number_format($historico["de"], 2, ',', '.') . "</strong> <br />para: <strong>" . number_format($historico["para"], 2, ',', '.') . "</strong>.";
                            break;
                    }
                    break;
                case "valor_juros":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o valor do juros <br />de: <strong>" . number_format($historico["de"], 2, ',', '.') . "</strong> <br />para: <strong>" . number_format($historico["para"], 2, ',', '.') . "</strong>.";
                            break;
                    }
                    break;
                case "idconta_corrente":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a conta-corrente <br />de: <strong>" . $historico["conta_corrente"]['de']['nome'] . "</strong> <br />para: <strong>" . $historico['conta_corrente']["para"]['nome'] . "</strong>.";
                            break;
                    }
                    break;
                case "idpessoa":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o cliente <br />de: <strong>" . $historico["pessoa"]['de']['nome'] . "</strong> <br />para: <strong>" . $historico['pessoa']["para"]['nome'] . "</strong>.";
                            break;
                    }
                    break;
                case "idcategoria":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a categoria <br />de: <strong>" . $historico["categoria"]['de']['nome'] . "</strong> <br />para: <strong>" . $historico['categoria']["para"]['nome'] . "</strong>.";
                            break;
                    }
                    break;
                case "idproduto":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o produto <br />de: <strong>" . $historico["produto"]['de']['nome'] . "</strong> <br />para: <strong>" . $historico['produto']["para"]['nome'] . "</strong>.";
                            break;
                    }
                    break;
                case "idfornecedor":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o fornecedor <br />de: <strong>" . $historico["fornecedor"]['de']['nome'] . "</strong> <br />para: <strong>" . $historico['fornecedor']["para"]['nome'] . "</strong>.";
                            break;
                    }
                    break;
                case "idsindicato":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a sindicato <br />de: <strong>" . $historico["sindicato"]['de']['nome'] . "</strong> <br />para: <strong>" . $historico['sindicato']["para"]['nome'] . "</strong>.";
                            break;
                    }
                    break;
                case "idmantenedora":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a mantenedora <br />de: <strong>" . $historico["mantenedora"]['de']['nome_fantasia'] . "</strong> <br />para: <strong>" . $historico['mantenedora']["para"]['nome_fantasia'] . "</strong>.";
                            break;
                    }
                    break;
                case "idbanco":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o banco do cheque <br />de: <strong>" . $historico["banco"]['de']['nome'] . "</strong> <br />para: <strong>" . $historico['banco']["para"]['nome'] . "</strong>.";
                            break;
                    }
                    break;
                case "idbandeira":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a bandeira do cartão <br />de: <strong>" . $historico["banco"]['de']['nome'] . "</strong> <br />para: <strong>" . $historico['banco']["para"]['nome'] . "</strong>.";
                            break;
                    }
                    break;
                case "idevento":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o tipo da conta da matrícula <br />de: <strong>" . $historico["idevento"]["de"]["nome"] . "</strong> <br />para: <strong>" . $historico["idevento"]["para"]["nome"] . "</strong>.";
                            break;
                    }
                    break;
                case "conta_centro_custo":
                    switch ($historico["acao"]) {
                        case "cadastrou":
                            $historico["descricao"] = "Cadastrou o centro de custo na conta :<br /> <strong>" . $historico["centro_custo"]['nome'] . "</strong>.";
                            break;
                        case "removeu":
                            $historico["descricao"] = "Removeu o centro de custo na conta :<br /> <strong>" . $historico["centro_custo"]['nome'] . "</strong>.";
                            break;
                        case "modificou_porcentagem":
                            $historico["descricao"] = "Modificou a porcentagem do centro de custo: <strong>" . $historico["centro_custo"]['nome'] . "</strong> <br />de: <strong>" . number_format($historico["de"], 2, ',', '.') . "</strong> <br />para: <strong>" . number_format($historico["para"], 2, ',', '.') . "</strong>.";
                            break;
                    }
                    break;
                case "idmotivo":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Motivo de cancelamento da conta: <br /><strong>" . $historico["idmotivo"]["para"]["nome"] . "</strong>.";
                            break;
                    }
                    break;
                case "autorizacao_cartao":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a autorização do cartão <br />de: <strong>" . $historico["de"] . "</strong> <br />para: <strong>" . $historico["para"] . "</strong>.";
                            break;
                    }
                    break;
                case "agencia_cheque":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a agência do cheque <br />de: <strong>" . $historico["de"] . "</strong> <br />para: <strong>" . $historico["para"] . "</strong>.";
                            break;
                    }
                    break;
                case "cc_cheque":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a conta corrente do cheque <br />de: <strong>" . $historico["de"] . "</strong> <br />para: <strong>" . $historico["para"] . "</strong>.";
                            break;
                    }
                    break;
                case "numero_cheque":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o número do cheque <br />de: <strong>" . $historico["de"] . "</strong> <br />para: <strong>" . $historico["para"] . "</strong>.";
                            break;
                    }
                    break;
                case "parcela":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o número da parcela <br />de: <strong>" . $historico["de"] . "</strong> <br />para: <strong>" . $historico["para"] . "</strong>.";
                            break;
                    }
                    break;
                case "total_parcelas":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o número total de parcelas <br />de: <strong>" . $historico["de"] . "</strong> <br />para: <strong>" . $historico["para"] . "</strong>.";
                            break;
                    }
                    break;
                case "emitente_cheque":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o emitente do cheque <br />de: <strong>" . $historico["de"] . "</strong> <br />para: <strong>" . $historico["para"] . "</strong>.";
                            break;
                    }
                    break;
                case "forma_pagamento":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a forma de pagamento <br />de: <strong>" . $GLOBALS['forma_pagamento_conta'][$this->config["idioma_padrao"]][$historico["de"]] . "</strong> <br />para: <strong>" . $GLOBALS['forma_pagamento_conta'][$this->config["idioma_padrao"]][$historico["para"]] . "</strong>.";
                            break;
                    }
                    break;
                case "data_vencimento":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a data de vencimento da conta.<br><span style=\"color:#666666\">";
                            if ($historico["de"]) {
                                $historico["descricao"] .= "De " . formataData($historico["de"], 'br', 1);
                            }
                            if ($historico["para"]) {
                                $historico["descricao"] .= " Para " . formataData($historico["para"], 'br', 1);
                            }
                            $historico["descricao"] .= "</span>";
                            break;
                    }
                    break;
                case "data_pagamento":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou a data de pagamento da conta.<br><span style=\"color:#666666\">";
                            if ($historico["de"]) {
                                $historico["descricao"] .= "De " . formataData($historico["de"], 'br', 1);
                            }
                            if ($historico["para"]) {
                                $historico["descricao"] .= " Para " . formataData($historico["para"], 'br', 1);
                            }
                            $historico["descricao"] .= "</span>";
                            break;
                    }
                    break;
                case "documento":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o documento da conta.<br><span style=\"color:#666666\">";
                            if ($historico["de"]) {
                                $historico["descricao"] .= "De " . $historico["de"];
                            }
                            if ($historico["para"]) {
                                $historico["descricao"] .= " Para " . $historico["para"];
                            }
                            $historico["descricao"] .= "</span>";
                            break;
                    }
                    break;
                case "nome":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o nome da conta.<br><span style=\"color:#666666\">";
                            if ($historico["de"]) {
                                $historico["descricao"] .= "De " . $historico["de"];
                            }
                            if ($historico["para"]) {
                                $historico["descricao"] .= " Para " . $historico["para"];
                            }
                            $historico["descricao"] .= "</span>";
                            break;
                    }
                    break;
                case "numero_documento":
                    switch ($historico["acao"]) {
                        case "modificou":
                            $historico["descricao"] = "Modificou o número do documento.<br><span style=\"color:#666666\">";
                            if ($historico["de"]) {
                                $historico["descricao"] .= "De " . $historico["de"];
                            }
                            if ($historico["para"]) {
                                $historico["descricao"] .= " Para " . $historico["para"];
                            }
                            $historico["descricao"] .= "</span>";
                            break;
                    }
                    break;
            }

            $historicos[] = $historico;
        }

        return $historicos;
    }

    function retornarSituacaoPago()
    {
        $this->sql = 'SELECT idsituacao FROM contas_workflow
            WHERE pago =  "S" AND ativo = "S" ORDER BY idsituacao DESC';
        $dados = $this->retornarLinha($this->sql);

        return $dados['idsituacao'];
    }

    function retornarSituacaoPagSeguro()
    {
        $this->sql = 'SELECT idsituacao FROM contas_workflow
            WHERE pagseguro =  "S" AND ativo = "S" ORDER BY idsituacao DESC';
        $dados = $this->retornarLinha($this->sql);

        return $dados['idsituacao'];
    }

    function retornarSituacaoFastConnect()
    {
        $this->sql = 'SELECT idsituacao FROM contas_workflow
            WHERE fastconnect = "S" AND ativo = "S" ORDER BY idsituacao DESC limit 1';
        $dados = $this->retornarLinha($this->sql);

        return $dados['idsituacao'];
    }

    function alterarPagamentoMassa($post)
    {

        foreach ($post['idcontas'] as $ind => $val) {
            $this->post = array();
            $this->post['idconta'] = $val;
            $this->post['nome'] = $post['nome'][$val];
            $this->post['data_pagamento'] = $post['data_pagamento'][$val];
            $this->post['documento'] = $post['documento'][$val];
            $this->post['idevento'] = $post['idevento'][$val];
            $this->post['forma_pagamento'] = $post['forma_pagamento'][$val];
            $this->post['vencimento'] = $post['vencimento'][$val];
            $this->post['valor'] = $post['valor'][$val];
            $this->post['parcela'] = $post['parcela'][$val];
            $this->post['total_parcelas'] = $post['total_parcelas'][$val];
            $this->post['idbandeira'] = $post['idbandeira'][$val];
            $this->post['autorizacao_cartao'] = $post['autorizacao_cartao'][$val];
            $this->post['idbanco'] = $post['idbanco'][$val];
            $this->post['agencia_cheque'] = $post['agencia_cheque'][$val];
            $this->post['cc_cheque'] = $post['cc_cheque'][$val];
            $this->post['numero_cheque'] = $post['numero_cheque'][$val];
            $this->post['emitente_cheque'] = $post['emitente_cheque'][$val];
            $retorno = $this->alterarPagamento();
        }

        return $retorno;
    }

    function alterarPagamento()
    {
        $this->sql = "SELECT idsituacao FROM contas_workflow WHERE pago='S' AND ativo='S'";
        $wf_pago = $this->retornarLinha($this->sql);

        $this->id = $this->post['idconta'];

        $sql_antigo = "SELECT * FROM contas WHERE idconta = '" . $this->post['idconta'] . "' ";
        $antigo = $this->retornarLinha($sql_antigo);

        $this->sql = "UPDATE contas SET
                        tipo = 'receita',
                        valor = " . str_replace(',', '.', str_replace('.', '', $this->post['valor'])) . ",
                        data_vencimento = '" . formataData($this->post['vencimento'], 'en', 0) . "',
                        idevento = " . $this->post['idevento'] . ",
                        parcela = " . $this->post['parcela'] . ",
                        total_parcelas = " . $this->post['total_parcelas'];

        if ($this->post["forma_pagamento"] == 2 || $this->post["forma_pagamento"] == 3) {
            $this->sql .= ", forma_pagamento = " . $this->post['forma_pagamento'] . ",
                        idbandeira = " . $this->post['idbandeira'] . ",
                        autorizacao_cartao = '" . $this->post['autorizacao_cartao'] . "',
                        idbanco = NULL,
                        agencia_cheque = NULL,
                        cc_cheque =NULL,
                        numero_cheque = NULL,
                        emitente_cheque = NULL
                        ";
        } elseif ($this->post["forma_pagamento"] == 4) {

            if (!$this->post['idbanco']) $this->post['idbanco'] = "NULL";

            $this->sql .= ", forma_pagamento = " . $this->post['forma_pagamento'] . ",
                        idbanco = " . $this->post['idbanco'] . ",
                        agencia_cheque = '" . $this->post['agencia_cheque'] . "',
                        cc_cheque = '" . $this->post['cc_cheque'] . "',
                        numero_cheque = '" . $this->post['numero_cheque'] . "',
                        emitente_cheque = '" . $this->post['emitente_cheque'] . "',
                        idbandeira = NULL,
                        autorizacao_cartao = NULL";
        } else {

            $this->sql .= ", forma_pagamento = " . $this->post['forma_pagamento'] . ",
                        idbandeira = NULL,
                        autorizacao_cartao = NULL,
                        idbanco = NULL,
                        agencia_cheque = NULL,
                        cc_cheque =NULL,
                        numero_cheque = NULL,
                        emitente_cheque = NULL";
        }

        if ($this->post["nome"])
            $this->sql .= ", nome = '" . $this->post['nome'] . "'";
        if ($this->post["data_pagamento"]) {
            $this->post["data_pagamento"] = formataData($this->post['data_pagamento'], 'en', 0);
            $this->sql .= ", data_pagamento = '" . $this->post["data_pagamento"] . "'";
        }
        if ($this->post["documento"])
            $this->sql .= ", documento = '" . $this->post['documento'] . "'";

        $this->sql .= " WHERE idconta = '" . $this->post['idconta'] . "'";

        $salvar = $this->executaSql($this->sql);

        if ($salvar) {
            $this->retorno['sucesso'] = true;

            $sql_novo = "SELECT * from contas WHERE idconta = '" . $this->post['idconta'] . "' ";
            $novo = $this->retornarLinha($sql_novo);

            foreach ($antigo as $ind => $campo) {
                if ($novo[$ind] != $campo)
                    $this->AdicionarHistorico($ind, "modificou", $antigo[$ind], $novo[$ind], NULL);
            }
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_salvar_conta';
        }

        return $this->retorno;
    }

    function RetornarSubcategorias($idcategoria, $json = true)
    {

        $this->sql = "SELECT idsubcategoria, nome FROM categorias_subcategorias where idcategoria = '" . $idcategoria . "' AND ativo = 'S' AND ativo_painel = 'S' ";

        $this->ordem_campo = "nome";
        $this->groupby = "nome";

        if ($json) {
            $sql = "SELECT nome, subcategoria_obrigatoria FROM categorias where idcategoria = '" . $idcategoria . "' AND ativo = 'S' and ativo_painel = 'S' ";
            $categoria = $this->retornarLinha($sql);
        }
        $this->limite = -1;
        $this->ordem = "ASC";
        $dados = $this->retornarLinhas();
        if ($json) {
            $dadosJson = array();
            $dadosJson["subcategoria"] = $dados;
            $dadosJson["categoria"] = $categoria["nome"];
            $dadosJson["subcategoria_obrigatoria"] = $categoria["subcategoria_obrigatoria"];
            return json_encode($dadosJson);
        } else
            return $dados;
    }

    function RetornarMatriculasContaCompartilhada($idpagamento_compartilhado)
    {
        $this->sql = "select pcm.idmatricula, pcm.idpagamento, p.nome as pessoa, mw.nome as situacao, mw.cor_bg as situacao_cor_bg, mw.cor_nome as situacao_cor_nome
                        from pagamentos_compartilhados_matriculas pcm
                        inner join matriculas m on pcm.idmatricula = m.idmatricula
                        inner join matriculas_workflow mw on m.idsituacao = mw.idsituacao
                        inner join pessoas p on m.idpessoa = p.idpessoa
                        where pcm.ativo = 'S' and pcm.idpagamento = '" . $idpagamento_compartilhado . "' ";
        $this->ordem = "asc";
        $this->ordem_campo = "pcm.idmatricula";
        $this->limite = -1;

        return $this->retornarLinhas();
    }

    public function retornarSituacaoCancelada()
    {
        $sql = 'SELECT
                    idsituacao
                FROM
                    contas_workflow cw
                WHERE
                    ativo = "S" AND
                    cancelada = "S"';
        return $this->retornarLinha($sql);
    }

    public function retornarContasAbertasMatricula($idmatricula)
    {
        $situacaoEmAberto = $this->retornarSituacaoEmAberto();
        $this->sql = 'SELECT
                        c.idconta,
                        c.idsituacao
                    FROM
                        contas c
                    WHERE
                        c.idmatricula = "' . $idmatricula . '" AND
                        c.idsituacao = "' . $situacaoEmAberto['idsituacao'] . '" AND
                        c.ativo = "S"';
        $this->ordem_campo = "c.idconta";
        $this->groupby = "c.idconta";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function retornarContasAbertasEscola($idescola)
    {

        $situacaoEmAberto = $this->retornarSituacaoEmAberto();
        $this->sql = 'SELECT
                        c.idconta,
                        c.idsituacao
                    FROM
                        contas c
                    WHERE
                        c.idescola = "' . $idescola . '" AND
                        c.idsituacao = "' . $situacaoEmAberto['idsituacao'] . '" AND
                        c.ativo = "S"';
        $this->ordem_campo = "c.idconta";
        $this->groupby = "c.idconta";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function cancelarContasMatricula($idmatricula)
    {
        $situacaoCancelada = $this->retornarSituacaoCancelada();
        $contas = $this->retornarContasAbertasMatricula($idmatricula);
        $resultado['situacaoPara'] = $situacaoCancelada['idsituacao'];
        $resultado['situacaoDe'] = $contas[0]['idsituacao'];
        foreach ($contas as $indice => $conta) {
            $this->id = $conta['idconta'];
            $alterouSituacao = $this->AlterarSituacao($conta['idsituacao'], $situacaoCancelada['idsituacao']);
            if ($alterouSituacao['sucesso']) {
                $resultado['contas'][] = $conta['idconta'];
            }
        }
        return $resultado;
    }

    function RetornarSituacoesWorkflow()
    {
        $this->sql = "SELECT * FROM contas_workflow WHERE ativo = 'S' ";
        $this->ordem_campo = "ordem";
        $this->ordem = "asc";
        $this->groupby = "idsituacao";
        $retorno = $this->retornarLinhas();
        $this->retorno = NULL;

        foreach ($retorno as $ind => $var) {
            $this->retorno[$var["idsituacao"]] = $var;
        }
        return $this->retorno;
    }

    function RetornarRelacionamentosWorkflow($idsituacao)
    {
        $this->sql = "select idsituacao_para from contas_workflow_relacionamentos where idsituacao_de = " . mysql_real_escape_string($idsituacao) . " and ativo = 'S' ";
        $this->limite = -1;
        $this->ordem_campo = "idsituacao_para";
        $this->groupby = "idsituacao_para";
        return $this->retornarLinhas();
    }

    public function retornarSituacaoEmAberto()
    {
        $sql = 'SELECT
                    idsituacao
                FROM
                    contas_workflow cw
                WHERE
                    ativo = "S" AND
                    emaberto = "S"';
        return $this->retornarLinha($sql);
    }

    public function retornarSituacaoEmAFaturar()
    {
        $sql = 'SELECT
                    idsituacao
                FROM
                    contas_workflow cw
                WHERE
                    ativo = "S" AND
                    emaberto = "S"';
        return $this->retornarLinha($sql);
    }

    function AlterarSituacao($de, $para)
    {
        $retorno = array();

        $verificacao = $this->VerificaPreRequesito($de, $para);

        if ($verificacao['verifica']) {
            $retorno["sucesso"] = true;
            $retorno["mensagem"] = "mensagem_situacao_sucesso";

            $this->sql = "select * from contas where idconta = " . intval($this->id);
            $linhaAntiga = $this->retornarLinha($this->sql);

            $idmatricula_pagamentos_compartilhados = $linhaAntiga['idmatricula'];
            if (empty($idmatricula_pagamentos_compartilhados)){
                $sql = "select * from pagamentos_compartilhados_matriculas where idpagamento = " . intval($linhaAntiga['idpagamento_compartilhado']);
                $sql = $this->retornarLinha($sql);
                $idmatricula_pagamentos_compartilhados = $sql['idmatricula'];
            }

            if($para == $this->retornarSituacaoPago()){
                $this->sql = "update contas set idsituacao = '" . $para . "', idmatricula = '".$idmatricula_pagamentos_compartilhados."', data_pagamento = NOW()  where idconta = '" . $this->id . "'";

            }else{
                $this->sql = "update contas set idsituacao = '" . $para . "', idmatricula = '".$idmatricula_pagamentos_compartilhados."' where idconta = '" . $this->id . "'";
            }
            $this->executaSql($this->sql);

            $this->sql = "select idsituacao, idconta, idmatricula from contas where idconta = " . intval($this->id);
            $linhaNova = $this->retornarLinha($this->sql);

            $this->AdicionarHistorico("situacao", "modificou", $linhaAntiga["idsituacao"], $linhaNova["idsituacao"], $this->id);
            $situacaoPagseguro = $this->retornarSituacaoPagSeguro();

            $caminhoApp = realpath(__DIR__ . '/..');

            require_once $caminhoApp . '/classes/matriculas.class.php';
            $matriculaObj = new Matriculas();
            $matriculaObj->set('idusuario', $this->idusuario)
                ->set('id', $linhaNova['idmatricula']);
            $matricula = $matriculaObj->retornar();

            $situacaoConcluida = $matriculaObj->retornarSituacaoConcluida();
            $situacaoCancelada = $matriculaObj->retornarSituacaoCancelada();

            if ($linhaNova['idsituacao'] == $situacaoPagseguro && $matricula['idsituacao'] != $situacaoConcluida['idsituacao'] && $matricula['idsituacao'] != $situacaoCancelada['idsituacao']) {
                $situacaoAtiva = $matriculaObj->retornarSituacaoAtiva();

                $sql = 'UPDATE matriculas SET idsituacao = ' . $situacaoAtiva['idsituacao'] . '
                    WHERE idmatricula = ' . $linhaNova['idmatricula'];
                $this->executaSql($sql);

                $matriculaObj->set('id', $matricula['idmatricula'])
                    ->adicionarHistorico(
                        null,
                        'situacao',
                        'modificou',
                        $matricula['idsituacao'],
                        $situacaoAtiva['idsituacao'],
                        null
                    );
            }

            $this->ProcessaAcoes($de, $para);
        } else {
            $retorno["sucesso"] = false;
            if ($verificacao['mensagem'])
                $retorno["mensagem"] = $verificacao['mensagem'];
            else
                $retorno["mensagem"] = "mensagem_situacao_erro_prerequesitos";
        }
        return $retorno;
    }

    function VerificaPreRequesito($de, $para)
    {
        $retorno["verifica"] = true;

        $this->sql = "select idrelacionamento from contas_workflow_relacionamentos where idsituacao_de = " . $de . " and idsituacao_para = " . $para . " and ativo = 'S'";
        $relacionamento = $this->retornarLinha($this->sql);

        $this->sql = "select
                        cwa.idopcao
                      from
                        contas_workflow_acoes cwa
                      where
                        cwa.idrelacionamento = " . $relacionamento["idrelacionamento"] . " and
                        cwa.ativo = 'S'";
        $this->limite = -1;
        $this->ordem_campo = "cwa.idopcao";
        $resultado = $this->executaSql($this->sql);

        while ($acao = mysql_fetch_assoc($resultado)) {
            foreach ($GLOBALS['workflow_parametros_contas'] as $op) {
                if ($op['idopcao'] == $acao['idopcao'] && $op['tipo'] == "prerequisito") {
                    $preRequisitos[] = $acao;
                }
            }
        }

        if (count($preRequisitos) > 0) {
            $this->sql = "select * from contas where idconta = " . intval($this->id);
            $conta = $this->retornarLinha($this->sql);
            foreach ($preRequisitos as $ind => $preRequisito) {
                switch ($preRequisito["idopcao"]) {
                    case 2:
                        if (!$conta["data_pagamento"]) {
                            $retorno["verifica"] = false;
                            $retorno["mensagem"] = "prerequisito_ter_data_pagamento";
                        }
                        break;
                    case 3:
                        if (!$conta["valor_pago"]) {
                            $retorno["verifica"] = false;
                            $retorno["mensagem"] = "prerequisito_ter_valor_pago";
                        }
                        break;
                }
            }
        }
        return $retorno;
    }

    function ProcessaAcoes($de, $para)
    {
        $this->sql = "select idrelacionamento from contas_workflow_relacionamentos where idsituacao_de = " . $de . " and idsituacao_para = " . $para . " and ativo = 'S'";
        $relacionamento = $this->retornarLinha($this->sql);

        $this->sql = "select
                        cwa.idopcao,
                        cwap.valor
                      from
                        contas_workflow_acoes cwa
                        left outer join contas_workflow_acoes_parametros cwap on (cwa.idacao = cwap.idacao)
                      where
                        cwa.idrelacionamento = " . $relacionamento["idrelacionamento"] . " and
                        cwa.ativo = 'S'";
        $this->limite = -1;
        $this->ordem_campo = "cwa.idopcao";
        $acoes = $this->retornarLinhas();

        foreach ($acoes as $acao) {
            foreach ($GLOBALS['workflow_parametros_contas'] as $op) {
                if ($op['idopcao'] == $acao['idopcao'] && $op['tipo'] == "acao") {
                    $preRequisitos[] = $acao;
                }
            }
        }

        if (count($preRequisitos) > 0) {
            $this->sql = "select * from contas where idconta = " . intval($this->id);
            $conta = $this->retornarLinha($this->sql);

            foreach ($preRequisitos as $ind => $preRequisito) {
                switch ($preRequisito["idopcao"]) {
                }
            }
        }
    }

    function ListarCentrosAssociadas($idconta)
    {
        $this->sql = 'SELECT
                            ccc.idcentro_custo,
                            ccc.porcentagem,
                            ccc.valor,
                            ccc.idconta_centro_custo,
                            cc.nome
                        FROM
                            contas_centros_custos ccc
                            INNER JOIN centros_custos cc ON (cc.idcentro_custo = ccc.idcentro_custo)
                        WHERE
                            ccc.idconta = ' . $idconta . ' AND
                            ccc.ativo = "S"';

        $this->limite = -1;
        $this->ordem_campo = 'cc.idcentro_custo';
        $this->ordem = 'DESC';
        $this->groupby = 'cc.idcentro_custo';

        return $this->retornarLinhas();
    }

    function BuscarCentroCusto()
    {
        $this->sql = "select cc.idcentro_custo as 'key', cc.nome as value
                            from centros_custos cc
                            where cc.nome like '%" . $_GET["tag"] . "%' and cc.ativo = 'S'
                            and NOT EXISTS (SELECT ccc.idcentro_custo from contas_centros_custos ccc where cc.idcentro_custo = ccc.idcentro_custo and ccc.ativo = 'S' AND ccc.idconta = '" . $this->id . "')";

        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";
        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    function AssociarCentrosCustos($idconta, $arrayCentrosCustos)
    {

        $sql = "select * from contas where idconta = '" . $idconta . "' ";
        $conta = $this->retornarLinha($sql);
        if ($conta['ativo_painel'] != 'S') {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'conta_inativa';
            return $this->retorno;
        }

        mysql_query("START TRANSACTION");
        foreach ($arrayCentrosCustos as $ind => $id) {

            $this->sql = "select count(idconta_centro_custo) as total, idconta_centro_custo from contas_centros_custos where idconta = '" . $idconta . "' and idcentro_custo = '" . $id . "'";
            $totalAss = $this->retornarLinha($this->sql);
            if ($totalAss["total"] > 0) {
                $this->sql = "update contas_centros_custos set ativo = 'S' where idconta_centro_custo = " . $totalAss["idconta_centro_custo"];
                $associar = $this->executaSql($this->sql);
                if (!$associar) {
                    mysql_query("ROLLBACK");
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_associar_centro_custo';
                    return $this->retorno;
                }
                $this->monitora_qual = $totalAss["idconta_centro_custo"];
            } else {
                $this->sql = "insert into contas_centros_custos set ativo = 'S', data_cad = now(), idconta = '" . $idconta . "', idcentro_custo = '" . $id . "'";
                $associar = $this->executaSql($this->sql);
                if (!$associar) {
                    mysql_query("ROLLBACK");
                    $this->retorno["erro"] = true;
                    $this->retorno["erros"][] = 'erro_associar_centro_custo';
                    return $this->retorno;
                }
                $this->monitora_qual = mysql_insert_id();
            }
            $this->set('id', $idconta);
            $this->AdicionarHistorico("conta_centro_custo", "cadastrou", null, null, $this->monitora_qual);

            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 188;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }

        mysql_query("COMMIT");
        return $this->retorno;
    }

    function SalvarPorcentagensCentrosCustos($idconta)
    {
        $retorno = false;

        $sql = "select * from contas where idconta = '" . $idconta . "' ";
        $conta = $this->retornarLinha($sql);
        if ($conta['ativo_painel'] != 'S') {
            $retorno["erro"] = true;
            $retorno["erros"][] = 'conta_inativa';
            return $retorno;
        }

        foreach ($this->post['centros_custos_array'] as $id => $linha) {
            $linha['valor'] = str_replace(array('.', ','), array('', '.'), $linha['valor']);
            $linha['porcentagem'] = str_replace(',', '.', $linha['porcentagem']);
            $valor_total_post += $linha['valor'];
            $porcentagem_total_post += $linha['porcentagem'];
        }

        $valor_liquido = (abs($conta['valor']) + $conta['valor_juros'] + $conta['valor_multa'] + $conta['valor_outro'] - $conta['valor_desconto']);
        //Colocado essa comparação, pois a outra (100 <> $porcentagem_total_post) estava dando problema, chamado #127498
        $porcentagem_invalida = (strcasecmp($porcentagem_total_post, 100) != 0 && strcasecmp($porcentagem_total_post, 0) != 0);
        $valor_invalido = (strnatcasecmp($valor_total_post, $valor_liquido) != 0 && $valor_total_post != 0);

        if ($porcentagem_total_post == 0 && $valor_total_post == 0) {
            mysql_query("ROLLBACK");
            $retorno["erro"] = true;
            $retorno["erros"][] = 'erro_obrigatorio_porcentagem_valor';
            return $retorno;
        }/*elseif($porcentagem_invalida) {
            $retorno["erro"] = true;
            $retorno["erros"][] = 'porcentagem_total_diferente';
            return $retorno;
        }*/ else if ($valor_invalido) {
            $retorno["erro"] = true;
            $retorno["erros"][] = 'valor_total_diferente';
            return $retorno;
        }

        /*
        if($porcentagem_invalida && $valor_invalido) {
            $retorno["erro"] = true;
            $retorno["erros"][] = 'porcentagem_valor_invalidos';
            return $retorno;
        }
        */

        foreach ($this->post['centros_custos_array'] as $id => $linha) {
            $porcentagem = str_replace(',', '.', $linha['porcentagem']);
            $valor = str_replace(array('.', ','), array('', '.'), $linha['valor']);

            $sqlCentroCusto = "SELECT * FROM contas_centros_custos WHERE idconta_centro_custo = '" . $id . "' ";
            $linhaAntiga = $this->retornarLinha($sqlCentroCusto);

            if ($linhaAntiga['porcentagem'] != $porcentagem || $linhaAntiga['valor'] != $valor) {
                $sql = "UPDATE contas_centros_custos SET porcentagem = '" . $porcentagem . "', valor = '" . $valor . "' WHERE idconta_centro_custo = '" . $id . "'  ";
                $atualizar_valor = $this->executaSql($sql);

                if (!$atualizar_valor) {
                    $retorno["erro"] = true;
                    $retorno["erros"][] = 'erro_atualizar_porcentagem';
                    return $retorno;
                } else {
                    $linhaNova = $this->retornarLinha($sqlCentroCusto);

                    $this->id = $idconta;
                    $this->AdicionarHistorico("conta_centro_custo", "modificou_porcentagem", $linhaAntiga['porcentagem'], $linha['porcentagem'], $id);
                    $atualizacoes_feitas = true;
                    $this->monitora_oque = 2;
                    $this->monitora_onde = 188;
                    $this->monitora_qual = $id;
                    $this->monitora_dadosantigos = $linhaAntiga;
                    $this->monitora_dadosnovos = $linhaNova;
                    $this->Monitora();
                }
            } else {
                $atualizacoes_feitas = true;
            }
        }

        if ($atualizacoes_feitas) {
            $retorno["sucesso"] = true;
        }

        return $retorno;
    }

    function RemoverCentroCusto($idconta)
    {

        $sql = "select * from contas where idconta = '" . $idconta . "' ";
        $conta = $this->retornarLinha($sql);
        if ($conta['ativo_painel'] != 'S') {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'conta_inativa';
            return $this->retorno;
        }

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        $erros = validateFields($this->post, $regras);

        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update contas_centros_custos set ativo = 'N' where idconta_centro_custo = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->set('id', $idconta);
                $this->AdicionarHistorico("conta_centro_custo", "removeu", null, null, $this->post["remover"]);

                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 188;
                $this->monitora_qual = intval($this->post["remover"]);
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function adicionarArquivo($idmatricula = null)
    {
        $this->return = array();
        if ($_FILES['documento']['error'] === 0) {
            $pasta = $_SERVER['DOCUMENT_ROOT'] . '/storage/contas_arquivos/' . $this->id;
            $extensao = strtolower(strrchr($_FILES['documento']['name'], '.'));
            $nomeServidor = date('YmdHis') . '_' . uniqid() . $extensao;
            mkdir($pasta, 0777);
            chmod($pasta, 0777);
            $envio = move_uploaded_file($_FILES['documento']['tmp_name'], $pasta . '/' . $nomeServidor);
            chmod($pasta . '/' . $nomeServidor, 0777);
            //$db = new Zend_Db_Select(new Zend_Db_MySql);
            if ($envio) {
                /*$insert = $db->insert('contas_arquivos', array(
                    'data_cad' => 'NOW()',
                    'idconta' => $this->id,
                    'arquivo_nome' => $db->quote($_FILES["documento"]["name"]),
                    'arquivo_servidor' => $db->quote($nomeServidor),
                    'arquivo_tipo' => $db->quote($_FILES["documento"]["type"]),
                    'arquivo_tamanho' => $db->quote($_FILES["documento"]["size"])
                ));*/
                $insert = 'insert into contas_arquivos set
                              data_cad = NOW(),
                              idconta = ' . $this->id . ',
                              arquivo_nome = "' . $_FILES["documento"]["name"] . '",
                              arquivo_servidor = "' . $nomeServidor . '",
                              arquivo_tipo = "' . $_FILES["documento"]["type"] . '",
                              protocolo = "' . $_POST["protocolo"] . '",
                              arquivo_tamanho = ' . $_FILES["documento"]["size"] . '
                                ';

                if ($_POST["nome_arquivo"]) {
                    $insert .= ", nome_arquivo = '{$_POST["nome_arquivo"]}' ";
                }

                if ($idmatricula) {
                    $insert .= ', idmatricula = ' . $idmatricula;
                }
                $salvar = $this->executaSql($insert);
                $id_arquivo = mysql_insert_id();
                if ($salvar) {
                    $this->AdicionarHistorico("arquivo", "cadastrou", null, null, $id_arquivo);
                    $this->monitora_oque = 1;
                    $this->monitora_onde = 213;
                    $this->monitora_qual = $id_arquivo;
                    $this->Monitora();

                    $this->return["sucesso"] = true;
                    $this->return["mensagem"] = "arquivos_conta_envio_sucesso";
                } else {
                    $this->return["sucesso"] = false;
                    $this->return["mensagem"] = "arquivos_conta_envio_erro";
                }
            } else {
                $this->return["sucesso"] = false;
                $this->return["mensagem"] = "arquivos_conta_envio_erro";
            }
        } else if (!$_FILES['documento']['tmp_name']) {

            $insert = 'insert into contas_arquivos set
                              data_cad = NOW(),
                              idconta = ' . $this->id . ',
                              protocolo = "' . $_POST["protocolo"] . '"
                                ';
            if ($idmatricula) {
                $insert .= ', idmatricula = ' . $idmatricula;
            }

            if ($_POST["nome_arquivo"]) {
                $insert .= ", nome_arquivo = '{$_POST["nome_arquivo"]}' ";
            }

            $salvar = $this->executaSql($insert);
            if ($salvar) {
                $this->AdicionarHistorico("arquivo", "cadastrou", null, null, mysql_insert_id());
                $this->monitora_oque = 1;
                $this->monitora_onde = 213;
                $this->monitora_qual = mysql_insert_id();
                $this->Monitora();

                $this->return["sucesso"] = true;
                $this->return["mensagem"] = "arquivos_conta_envio_sucesso";
            } else {
                $this->return["sucesso"] = false;
                $this->return["mensagem"] = "arquivos_conta_envio_erro";
            }
        } else {
            $this->sql = "insert into
            contas_arquivos
            set
            data_cad = now(),
            idconta = " . $this->id . ",
            idtipo = " . $this->post["idtipo"] . ",
            protocolo = '" . $_POST["protocolo"] . "',
            nome_arquivo = '" . $_POST["nome_arquivo"] . "',
            idtipo_associacao = " . $this->post["idtipo_associacao"];

            if ($_POST["nome_arquivo"]) {
                $this->sql .= ", nome_arquivo = '{$_POST["nome_arquivo"]}' ";
            }

            /*if ($idmatricula) {
                $this->sql .= ', idmatricula = ' . $idmatricula;
            }*/
            $salvar = $this->executaSql($this->sql);
            $id_arquivo = mysql_insert_id();
            if ($salvar) {
                $this->AdicionarHistorico("arquivo", "cadastrou", null, null, $id_arquivo);

                $this->monitora_oque = 1;
                $this->monitora_onde = 213;
                $this->monitora_qual = $id_arquivo;
                $this->Monitora();

                $this->return["sucesso"] = true;
                $this->return["mensagem"] = "arquivos_conta_envio_sucesso";
            } else {
                $this->return["sucesso"] = false;
                $this->return["mensagem"] = "arquivos_conta_envio_erro";
            }
        }
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
                $pasta = $_SERVER['DOCUMENT_ROOT'] . '/storage/contas_arquivos/' . $this->id;
                $extensao = strtolower(strrchr($_FILES['arquivo']['name'], '.'));
                $nomeServidor = date('YmdHis') . '_' . uniqid() . $extensao;
                mkdir($pasta, 0777);
                chmod($pasta, 0777);
                $envio = move_uploaded_file($_FILES['arquivo']['tmp_name'], $pasta . '/' . $nomeServidor);
                chmod($pasta . '/' . $nomeServidor, 0777);

                if ($envio) {
                    $this->sql = "update
                    contas_arquivos
                  set
                    arquivo_nome = '" . $_FILES["arquivo"]["name"] . "',
                    arquivo_servidor = '" . $nomeServidor . "',
                    arquivo_tipo = '" . $_FILES["arquivo"]["type"] . "',
                    arquivo_tamanho = '" . $_FILES["arquivo"]["size"] . "'
                  where
                    idarquivo = " . $idarquivo . " and
                    idconta = " . $this->id;

                    $salvar = $this->executaSql($this->sql);
                    if ($salvar) {
                        $this->AdicionarHistorico("arquivo", "enviou", NULL, NULL, $idarquivo);
                        $this->retorno["sucesso"] = true;
                        $this->retorno["mensagem"] = "arquivos_conta_envio_sucesso";
                    } else {
                        $this->retorno["sucesso"] = false;
                        $this->retorno["mensagem"] = "arquivos_conta_envio_erro";
                    }
                } else {
                    $this->retorno["sucesso"] = false;
                    $this->retorno["mensagem"] = "arquivos_conta_envio_erro";
                }
            }
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "arquivos_conta_envio_erro";
        }
        return $this->retorno;
    }

    public function removerArquivo($idmatricula = null)
    {
        $this->retorno = array();
        $this->sql = "UPDATE contas_arquivos SET ativo ='N' WHERE idarquivo = {$this->idarquivo} AND idconta = " . $this->id;
        if ($idmatricula)
            $this->sql .= ' and idmatricula = ' . $idmatricula;
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            $this->AdicionarHistorico("arquivo", "removeu", null, null, $this->idarquivo);

            $this->monitora_oque = 3;
            $this->monitora_onde = 213;
            $this->monitora_qual = $this->idarquivo;
            $this->Monitora();

            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "arquivo_conta_remover_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "arquivo_conta_remover_erro";
        }
        return $this->retorno;
    }

    public function retornarListaArquivos()
    {
        $this->sql = "SELECT *,idarquivo as iddocumento FROM contas_arquivos
                    WHERE idconta = {$this->id}
                                AND ativo = 'S'";
        $this->ordem = 'ASC';
        $this->ordem_campo = "data_cad";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function retornarArquivo()
    {
        $this->sql = "SELECT *, idarquivo as iddocumento FROM contas_arquivos WHERE idarquivo = " . $this->iddocumento . " and ativo = 'S' and idconta = " . $this->id;
        return $this->retornarLinha($this->sql);
    }

    public function retornarPagamentoConta()
    {

        $sql = "select idrelacao from contas where idconta = '" . $this->id . "' ";
        $conta = $this->retornarLinha($sql);

        $this->sql = 'SELECT
                        cp.*
                    FROM
                        contas_pagamentos cp
                        INNER JOIN contas c ON (cp.idconta = c.idconta)
                    WHERE ';
        if ($conta["idrelacao"])
            $this->sql .= 'c.idrelacao = "' . $conta["idrelacao"] . '"';
        else
            $this->sql .= 'c.idconta = "' . $this->id . '"';

        return $this->retornarLinha($this->sql);
    }

    public function retornarFatura($idFatura, $campos)
    {
        $this->sql = 'SELECT
                ' . $campos . '
                FROM
                    contas c
                    INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao)
                    INNER JOIN escolas e ON (e.idescola = c.idescola)
                    INNER JOIN sindicatos s ON (s.idsindicato = e.idsindicato)
                WHERE
                    c.ativo = "S" AND
                    c.fatura = "S" AND
                    cw.emaberto = "S" AND
                    c.idconta = ' . $idFatura;

        $retorno['fatura'] = $this->retornarLinha($this->sql);

        $this->sql = 'SELECT *
                FROM pagarme
                WHERE
                    idconta = ' . $idFatura;

        $retorno['pagarme'] = $this->retornarLinha($this->sql);

        return $retorno;
    }

    public function baixarManual($idFatura, $idSituacao, $pagarme)
    {
        $sql = 'UPDATE
                    contas
                SET
                    idsituacao = ' . $idSituacao . ',
                    data_modificacao_fatura = NOW(),
                    data_pagamento = NOW()
                WHERE
                    idconta = ' . $idFatura;

        $sqlContas = $this->executaSql($sql);

        if (!empty($pagarme)) {
            $sql = 'UPDATE
                    pagarme
                SET
                    status = "paid",
                    paid_amount = amount
                WHERE
                    idconta = ' . $idFatura;

            $sqlPagarme = $this->executaSql($sql);
        }

        if ($sqlContas) {
            return array('sucesso' => true);
        }
    }

    public function removerFatura($idFatura, $cancelada, $idSituacaoatual)
    {
        //Função para cancelar a fatura - (módulo de faturas)


        $sql = 'UPDATE
                    contas
                SET
                    idsituacao = ' . $cancelada . ',
                    data_modificacao_fatura = NOW()
                WHERE
                    idconta = ' . $idFatura;

        $sqlContas = $this->executaSql($sql);

        $this->id = $idFatura;
        $this->AdicionarHistorico("situacao", "modificou",$idSituacaoatual, $cancelada);

        if ($sqlContas) {
            return array('sucesso' => true);
        }
    }

    public function retornarSituacao($situacao)
    {
        $this->sql = 'SELECT *
                FROM contas_workflow
                WHERE
                    ' . $situacao . ' = "S"';

        return $this->retornarLinha($this->sql);
    }
    /**
     * Retorna todas as faturas
     * @access public
     * @param
     * @var  int $this->campos: [Obrigatório] Campos das tabelas que serão retornados.
     * @return array
     * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
     */
    public function listarTodasFaturas()
    {
        $retorno = NULL;
        $this->campos = 'e.idescola, ' . $this->campos;
        $this->sql = 'SELECT
                            ' . $this->campos . '
                        FROM
                            contas c
                            INNER JOIN contas_matriculas cm ON (cm.idconta = c.idconta)
                            INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao)
                            INNER JOIN escolas e ON (e.idescola = c.idescola)
                            INNER JOIN sindicatos s ON (s.idsindicato = e.idsindicato)
                            LEFT OUTER JOIN cidades cid ON (cid.idcidade = e.idcidade)
                            LEFT OUTER JOIN estados est ON (est.idestado = e.idestado)
                            LEFT OUTER JOIN logradouros l ON (l.idlogradouro = e.idlogradouro)
                        WHERE
                            c.ativo = "S" AND
                            c.fatura = "S"';

        if ($this->modulo == 'cfc') {
            $this->sql .= ' AND c.idescola = ' . $this->idescola;
        }

        if ($_SESSION['adm_gestor_sindicato'] <> 'S' && $this->modulo == 'gestor') {
            $this->sql .= ' AND c.idsindicato IN (' . $_SESSION['adm_sindicatos'] . ') ';
        }

        if ($_GET['acao'] == 'filtrar_data') {
            if ($_GET['filtro_mes'] && $_GET['filtro_ano']) {
                $filtros .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m") = "' . $_GET['filtro_ano'] . '-' . $_GET['filtro_mes'] . '" ';
            }
        } else {
            if ($_GET['filtro_dia']) {
                $filtros .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") = "' . $_GET['filtro_dia'] . '" ';
            } else {
                $filtros .= ' AND DATE_FORMAT(c.data_vencimento, "%Y-%m-%d") = "' . date('Y-m-d') . '" ';
            }
        }

        if ($_GET['idsindicato_filtro'] && $_GET['idsindicato_filtro'] != -1) {
            $this->sql .= ' AND c.idsindicato = ' . $_GET['idsindicato_filtro'] . ' ';
        }

        if ($this->tipo_conta == 'apagar') {
            $this->sql .= " AND c.tipo = 'despesa' ";
        } elseif ($this->tipo_conta == 'areceber') {
            $this->sql .= " AND c.tipo = 'receita' ";
        }

        if (is_array($_GET['q'])) {
            foreach ($_GET['q'] as $campo => $valor) {
                //explode = Retira, ou seja retira a '|' da variavel campo
                $campo = explode('|', $campo);
                $valor = str_replace('\'', '', $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === '0') && $valor <> 'todos') {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        $this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '"';
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", '', $valor);
                        $busca = str_replace("\\", '', $busca);
                        $busca = explode(' ', $busca);
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= ' AND ' . $campo[1] . ' LIKE "%' . urldecode($buscar) . '%"';
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= ' AND DATE_FORMAT(' . $campo[1] . ', "%d/%m/%Y") = "' . $valor . '"';
                    } elseif ($campo[0] == 4) {
                        $valor = str_replace('.', '', $valor);
                        $valor = str_replace(',', '.', $valor);
                        $this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '"';
                    } elseif ($campo[0] == 5) {
                        $this->sql .= ' AND ' . $campo[1] . ' <> "' . $valor . '"';
                    } elseif ($campo[0] == 6) {
                        $this->sql .= ' AND (SELECT ' . $campo[1] . ' FROM pagarme p WHERE p.idconta = c.idconta AND
                            p.ativo = "S" ORDER BY p.idpagarme DESC LIMIT 1) = "' . $valor . '"';
                    }
                }
            }
        }
        //echo $this->sql;
        $retorno = $this->retornarLinhas();
        foreach ($retorno as $ind => $var) {

            if( empty($var['idconta']) || empty($var['idescola']) ){
                unset($retorno[$ind]);
            }else{
                $valorCorrigido = $this->calcularJurosMulta($var['idconta']);

                $retorno[$ind]['valor_corrigido'] = $valorCorrigido['valor_corrigido'];

                $sql = 'SELECT
                            efp.forma_pagamento
                        FROM
                            escolas_formas_pagamento efp
                        WHERE
                            efp.idescola = ' . $var['idescola'] . ' AND
                            efp.ativo = "S"';
                $queryFormasPagamento = $this->executaSql($sql);

                while ($linha = mysql_fetch_assoc($queryFormasPagamento)) {
                    $retorno[$ind]['formas_pagamento'][] = $linha['forma_pagamento'];
                }

                $sql = 'SELECT
                        p.status,
                        p.boleto_url,
                        p.payment_method,
                        p.id,
                        p.boleto_expiration_date
                    FROM
                        pagarme p
                    WHERE
                        p.idconta = ' . ((int)$var['idconta']) . ' AND
                        p.ativo = "S"
                    ORDER BY
                        FIELD(p.status, "paid") DESC, p.idpagarme DESC
                    LIMIT 1';
                $pagarme = $this->retornarLinha($sql);
                $retorno[$ind]['statusPagarme'] = $pagarme['status'];
                $retorno[$ind]['boleto_url'] = $pagarme['boleto_url'];
                $retorno[$ind]['payment_method'] = $pagarme['payment_method'];
                $retorno[$ind]['pagarme_id'] = $pagarme['id'];
                $retorno[$ind]['boleto_expiration_date'] = $pagarme['boleto_expiration_date'];
            }

        }
        return $retorno;
    }

    public function atualizaQtdParcelas($campos)
    {
        $cursosObj = new Cursos();
        $retorno = NULL;
        $this->campos = $campos;
        $this->sql = 'SELECT
                            ' . $this->campos . '
                        FROM
                            contas c
                            INNER JOIN contas_matriculas cm ON (cm.idconta = c.idconta)
                            INNER JOIN escolas e ON (e.idescola = c.idescola)
                            INNER JOIN sindicatos s ON (s.idsindicato = e.idsindicato)
                            INNER JOIN matriculas ma ON (ma.idmatricula = cm.idmatricula)
                        WHERE
                            c.ativo = "S" AND
                            c.fatura = "S" AND
                            ( cm.qtd_parcelas = 0 OR cm.qtd_parcelas IS NULL )
                        ';

        $retorno = $this->retornarLinhas();
        foreach ($retorno as $ind => $var) {
            $idCfc       = NULL;
            $idCfc       = $var['idescola'];
            $idSindicato = NULL;
            $idSindicato = $var['idsindicato'];
            $cursoFaturar = NULL;
            $cursoFaturar = $cursosObj->retornarValoresPorCursoCfc(
                $idCfc,
                'qtd_parcelas',
                $var['idcurso']
            );

            if (
                empty($cursoFaturar['qtd_parcelas'])
                || $cursoFaturar['qtd_parcelas'] == 0
            ) {
                $valorPorMatriculaSindicato = $cursosObj
                    ->retornarValoresCursoSindicato(
                        $idSindicato,
                        'max_parcelas',
                        $var['idcurso']
                    );

                $cursoFaturar['qtd_parcelas'] = $valorPorMatriculaSindicato['max_parcelas'];

            }

            $sqlUp = "UPDATE contas_matriculas SET ";
            $sqlUp.= "qtd_parcelas = '".$cursoFaturar['qtd_parcelas']."' ";
            $sqlUp.= "WHERE idconta_matricula = '".$var['idconta_matricula']."' ";
            $this->executaSql($sqlUp);

        }


    }

    public function setTotalContas($total){
        $this->totalDados = $total;
    }

    public function getTotalContas(){
        return $this->totalDados;
    }

    public function calcularJurosMulta($idConta, $dataPagamento = null)
    {
        if (empty($idConta)) {
            $erros['erro'] = 'true';
            $erros['erros'][] = 'parametros_incompletos';
            return $erros;
        }

        $dataPagamento = new DateTime($dataPagamento);

        $sql = 'SELECT c.valor, c.data_vencimento, cw.pago, cw.cancelada, e.idescola, e.idestado, e.idcidade, e.idsindicato
            FROM contas c INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao)
            INNER JOIN escolas e ON (e.idescola = c.idescola)
            WHERE c.idconta = ' . $idConta . ' AND c.ativo = "S"';
        $conta = $this->retornarLinha($sql);

        if (empty($conta)) {
            $erros['erro'] = 'true';
            $erros['erros'][] = 'conta_nao_existe';
            return $erros;
        }

        $retorno['juros'] = 0;
        $retorno['multa'] = 0;
        $retorno['valor_corrigido'] = null;

        $proximoDiaUtilVencimento = proximoDiaUtil(
            $conta['data_vencimento'],
            $conta['idestado'],
            $conta['idcidade'],
            $conta['idescola'],
            $conta['idsindicato']
        );

        $retorno['proximoDiaUtilVencimento'] = $proximoDiaUtilVencimento;

        if (
            $proximoDiaUtilVencimento->format('Y-m-d') >= $dataPagamento->format('Y-m-d')
            || $conta['pago'] == 'S'
            || $conta['cancelada'] == 'S'
        ) {
            return $retorno;
        }

        $dataVencimento = new DateTime($conta['data_vencimento']);
        $diff = $dataVencimento->diff($dataPagamento);
        //Colocado para sempre o juros contar mais um dia, pq no Pagar.me o boleto só pode ser gerado para no mínimo um dia depois
        $diasAtraso = $diff->days + 1;

        $retorno['multa'] = $conta['valor'] * $GLOBALS['config']['pagarme']['multa_atraso'] / 100;
        $retorno['juros'] = $conta['valor'] * $diasAtraso * $GLOBALS['config']['pagarme']['juros_atraso'] / 100;
        $retorno['valor_corrigido'] = $conta['valor'] + $retorno['multa'] + $retorno['juros'];

        return $retorno;
    }

    /**
     * Retorna as matrículas de uma determinada fatura
     * @access public
     * @param int $idescola: [Obrigatório] ID da escola que será retornada as matrículas.
     * @var
     * @return array
     * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
     */
    public function retornarMatriculasFatura($idconta)
    {
        if (!$idconta) {
            $erros['erro'] = 'true';
            $erros['erros'][] = 'parametros_incompletos';
            return $erros;
        }

        $this->sql = 'SELECT
                            ' . $this->campos . '
                        FROM
                            contas_matriculas cm
                            INNER JOIN contas c ON (c.idconta = cm.idconta)
                            INNER JOIN matriculas m on (m.idmatricula = cm.idmatricula)
                            INNER JOIN pessoas p on (p.idpessoa = m.idpessoa)
                            INNER JOIN cursos cs ON ( cs.idcurso = m.idcurso )
	                        INNER JOIN escolas e ON (e.idescola = m.idescola)
                        WHERE
                            c.idconta = ' . $idconta . ' AND
                            c.fatura = "S" AND
                            c.ativo = "S"';

        if ($this->modulo == 'cfc') {
            $this->sql .= ' AND c.idescola = ' . $this->idescola;
        }

        if (is_array($_GET['q'])) {
            foreach ($_GET['q'] as $campo => $valor) {
                //explode = Retira, ou seja retira a '|' da variavel campo
                $campo = explode('|', $campo);
                $valor = str_replace('\'', '', $valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if (($valor || $valor === '0') && $valor <> 'todos') {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if ($campo[0] == 1) {
                        $this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '"';
                        // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", '', $valor);
                        $busca = str_replace("\\", '', $busca);
                        $busca = explode(' ', $busca);
                        foreach ($busca as $ind => $buscar) {
                            $this->sql .= ' AND ' . $campo[1] . ' LIKE "%' . urldecode($buscar) . '%"';
                        }
                    } elseif ($campo[0] == 3) {
                        $this->sql .= ' AND DATE_FORMAT(' . $campo[1] . ', "%d/%m/%Y") = "' . $valor . '"';
                    } elseif ($campo[0] == 4) {
                        $valor = str_replace('.', '', $valor);
                        $valor = str_replace(',', '.', $valor);
                        $this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '"';
                    }
                }
            }
        }

        $this->groupby = 'cm.idconta_matricula';
        return $this->retornarLinhas();
    }

    /**
     * Retorna as matrículas de uma determinada escola
     * @access private
     * @param int $idescola: [Obrigatório] ID da escola que será retornada as matrículas.
     * @var
     * @return array
     * @author Yuri Costa-Silva <yuric@alfamaweb.com.br>
     */
    private function retornarMatriculas($idescola, $idCurso = null)
    {
        if (!$idescola) {
            $erros['erro'] = 'true';
            $erros['erros'][] = 'parametros_incompletos';
            return $erros;
        }


        $this->sql = "SELECT
                            m.idmatricula
                        FROM
                            matriculas m
                            INNER JOIN matriculas_workflow mw ON (mw.idsituacao = m.idsituacao)
                        WHERE
                            m.idescola = " . (int) $idescola . " AND
                            m.faturada = 'N' AND
                            m.ativo = 'S' AND
                            mw.cancelada = 'N' AND
                            mw.inicio = 'N' ";
        /* if( $GLOBALS['config']['filtro_matricula_boleto_semana'] ){
            $now   = new DateTime;
            $clone = clone $now;
            $ontem = clone $now;
            $clone->modify('-8 day');
            $ontem->modify('-1 day');
            $dataIni = $clone->format('Y-m-d');
            $dataFim = $ontem->format('Y-m-d');
            $this->sql.= " AND ( DATE_FORMAT(m.data_cad,'%Y-%m-%d') >= '$dataIni' AND DATE_FORMAT(m.data_cad,'%Y-%m-%d') <= '$dataFim' ) ";
        }else{
            $mesSub = (date('m')-1);
            $mesAnt = str_pad($mesSub, 2, '0', STR_PAD_LEFT);
            $this->sql.= ' AND YEAR(m.data_cad) = '.date('Y').' AND MONTH(m.data_cad) = '.$mesAnt;
        } */
        if ($idCurso) {
            $idCurso = intval($idCurso);
            $this->sql .= ' AND m.idcurso = ' . $idCurso;
        }

        $this->groupby = 'm.idmatricula';
        $this->ordem_campo = 'm.idmatricula';
        $this->ordem = 'ASC';
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    private function retornarErros($erros)
    {
        return [
            'erro' => true,
            'erros' => $erros
        ];
    }

    private function retornarFaturaMesmoVencimento($idCfc, $idSituacaoEmAberto, $vencimento) {
        $idCfc = intval($idCfc);
        $idSituacaoEmAberto = intval($idSituacaoEmAberto);

        $sql = "SELECT
                c.idconta,
                c.valor,
                c.qnt_matriculas
            FROM
                contas c
            WHERE
                c.idescola = {$idCfc}
                AND c.idsituacao = {$idSituacaoEmAberto}
                AND c.data_vencimento = ' {$vencimento} '
                AND c.ativo = 'S'
                AND c.fatura = 'S'
        ";

        return $this->retornarLinha($sql);
    }

    private function atualizarContasFatura(
        $valor,
        $quantidadeMatriculas,
        $idConta
    ) {
        $idConta = intval($idConta);
        $quantidadeMatriculas = intval($quantidadeMatriculas);
        $valor = floatval($valor);

        $sql = "UPDATE
                " . self::TABELA . "
            SET
                data_modificacao_fatura = NOW(),
                valor = '{$valor}',
                qnt_matriculas = '{$quantidadeMatriculas}'
            WHERE
                idconta = {$idConta}
        ";

        return $this->executaSql($sql);
    }

    public function cadastrarContaFatura($idCfc, $idSindicato, $idSituacao, $valor, $vencimento, $total) {
        $idCfc = intval($idCfc);
        $idSindicato = intval($idSindicato);
        $idSituacao = intval($idSituacao);
        $valor = floatval($valor);
        $total = floatval($total);

        $sql = "INSERT
            INTO
                " . self::TABELA . "
            SET
                ativo = 'S',
                fatura = 'S',
                data_cad = NOW(),
                data_modificacao_fatura = NOW(),
                nome = 'Referente a uma fatura da escola {$idCfc}',
                tipo = 'receita',
                parcela = 1,
                total_parcelas = 1,
                idsindicato = {$idSindicato},
                idescola = {$idCfc},
                idsituacao = {$idSituacao},
                forma_pagamento = 1,
                valor = '{$valor}',
                data_vencimento = '{$vencimento}',
                qnt_matriculas = {$total}

        ";

        return $this->executaSql($sql);
    }

    public function gerarFatura($idCfc, $idSindicato)
    {
        if (!$idCfc) {
            return $this->retornarErros(['idescola_vazio']);
        }

        $situacao = $this->retornarSituacaoEmAFaturar();

        if (!$situacao) {
            return $this->retornarErros(['nao_existe_situacao_em_aberto']);
        }

        $cursosObj = new Cursos();

        $cursosFaturar = $cursosObj->retornarValoresCursoCfc(
            $idCfc,
            'valor_por_matricula,
                    valor_por_matricula_2,
                    quantidade_faturas_ciclo,
                    idcurso,
                    qtd_parcelas,
                    quantidade_matriculas,
                    quantidade_matriculas_2,
                    valor_excedente'
        );

        $valoresCursosSindicato = $cursosObj->retornarValoresCursoSindicato(
            $idSindicato,
            'valor_por_matricula,
                    valor_por_matricula_2
                    quantidade_faturas_ciclo,
                    idcurso, max_parcelas as qtd_parcelas,
                    quantidade_matriculas,
                    quantidade_matriculas_2,
                    valor_excedente'
        );

        $arrCursosCFC = array_combine(array_keys($cursosFaturar), array_column($cursosFaturar, 'idcurso'));
        $arrCursosSindicato = array_combine(array_keys($valoresCursosSindicato), array_column($valoresCursosSindicato, 'idcurso'));
        $arrDiff = array_diff($arrCursosSindicato, $arrCursosCFC); //Filtra somente valores definidos pelo sindicato, e que não foram definidos pelo CFC

        foreach ($arrDiff as $i => $idCurso)
            $cursosFaturar[] = $valoresCursosSindicato[$i];

        if (!$cursosFaturar) {
            return false;
        }

        $matriculasFaturadas = array();

        $this->iniciaTransacao();

        foreach ($cursosFaturar as $cursoFaturar) {
            if (
                empty($cursoFaturar['valor_por_matricula'])
                || $cursoFaturar['valor_por_matricula'] == 0
            ) {
                $valorPorMatriculaSindicato = $cursosObj
                    ->retornarValoresCursoSindicato(
                        $idSindicato,
                        'valor_por_matricula, quantidade_matriculas',
                        $cursoFaturar['idcurso']
                    );

                $cursoFaturar['valor_por_matricula'] = $valorPorMatriculaSindicato['valor_por_matricula'];
                $cursoFaturar['quantidade_matriculas'] = $valorPorMatriculaSindicato['quantidade_matriculas'];

                if (
                    empty($cursoFaturar['valor_por_matricula'])
                    || $cursoFaturar['valor_por_matricula'] == 0
                ) {
                    $this->errosLog[] = 'valor_por_matricula_vazio - Curso: ' . $cursoFaturar['idcurso'];
                    continue;
                }
            }

            if(empty($cursoFaturar['valor_por_matricula_2']))
            {

                $valorPorMatriculaSindicato = $cursosObj
                    ->retornarValoresCursoSindicato(
                        $idSindicato,
                        'valor_por_matricula_2, quantidade_matriculas_2',
                        $cursoFaturar['idcurso']
                    );

                $cursoFaturar['valor_por_matricula_2'] = $valorPorMatriculaSindicato['valor_por_matricula_2'];
                $cursoFaturar['quantidade_matriculas_2'] = $valorPorMatriculaSindicato['quantidade_matriculas_2'];


                if(!empty($cursoFaturar['quantidade_matriculas_2']) && ($cursoFaturar['quantidade_matriculas_2'] <= $cursoFaturar['quantidade_matriculas']))
                {
                    $this->errosLog[] = 'Quantidade de matrículas 2 menor ou igual a quantidade de matrículas 1 - ' . $cursoFaturar['idcurso'];
                    continue;
                }


                if(
                    ((!empty($cursoFaturar['valor_por_matricula_2']  || $cursoFaturar['quantidade_matriculas_2'] === "0")) && empty($cursoFaturar['quantidade_matriculas_2'])) ||
                   ((!empty($cursoFaturar['quantidade_matriculas_2']) || $cursoFaturar['valor_por_matricula_2'] === "0.00") && empty($cursoFaturar['valor_por_matricula_2']))
                )
                {
                    $this->errosLog[] = 'Valor por matrícula 2 preenchido porém quantidade de matrículas 2 vazio ou quantidade de matrículas 2 preenchido porém valor por matrícula 2 vazio - ' . $cursoFaturar['idcurso'];
                    continue;

                }

                if(!empty($cursoFaturar['valor_por_matricula_2']) &&
                   !empty($cursoFaturar['quantidade_matriculas_2']) &&
                   empty($cursoFaturar['quantidade_matriculas']))
                {
                    $this->errosLog[] = 'Quantidade de matrículas 1 vazio com valor por matrículas 2 e quantidades de matrículas 2 preenchidos - ' . $cursoFaturar['idcurso'];
                    continue;
                }


            } else {

                if(empty($cursoFaturar['quantidade_matriculas']))
                {
                    $this->errosLog[] = 'Quantidade de matrículas 1 vazio com valor por matrículas 2 e quantidades de matrículas 2 preenchidos - ' . $cursoFaturar['idcurso'];
                    continue;
                }
                if(!empty($cursoFaturar['quantidade_matriculas_2']) && ($cursoFaturar['quantidade_matriculas_2'] <= $cursoFaturar['quantidade_matriculas']))
                {
                    $this->errosLog[] = 'Quantidade de matrículas 2 menor ou igual a quantidade de matrículas 1 - ' . $cursoFaturar['idcurso'];
                    continue;
                }

                if(
                    ((!empty($cursoFaturar['valor_por_matricula_2']  || $cursoFaturar['quantidade_matriculas_2'] === "0")) && empty($cursoFaturar['quantidade_matriculas_2'])) ||
                   ((!empty($cursoFaturar['quantidade_matriculas_2']) || $cursoFaturar['valor_por_matricula_2'] === "0.00") && empty($cursoFaturar['valor_por_matricula_2']))
                )
                {
                    $this->errosLog[] = 'Valor por matrícula 2 preenchido porém quantidade de matrículas 2 vazio ou quantidade de matrículas 2 preenchido porém valor por matrícula 2 vazio - ' . $cursoFaturar['idcurso'];
                    continue;

                }
            }

            if(empty($cursoFaturar['valor_excedente']))
            {
                $valorExcedenteSindicato = $cursosObj
                    ->retornarValoresCursoSindicato(
                        $idSindicato,
                        'valor_excedente',
                        $cursoFaturar['idcurso']
                    );

                $cursoFaturar['valor_excedente'] = $valorExcedenteSindicato['valor_excedente'];

                if(!empty($cursoFaturar['valor_excedente']) && empty($cursoFaturar['quantidade_matriculas']))
                {
                    $this->errosLog[] = 'Valor excedente preenchido porém quantidades de matrículas 1 não está preenchido. -  ' . $cursoFaturar['idcurso'];
                    continue;
                }

                if(empty($cursoFaturar['valor_por_matricula_2']) && empty($cursoFaturar['quantidade_matriculas_2']) && empty($cursoFaturar['valor_excedente']) && !empty($cursoFaturar['quantidade_matriculas']))
                {
                    $this->errosLog[] = 'Quantidade de matrículas 1 e valor por matrícula 1 preenchido porém valor excedente não preenchido. -  ' . $cursoFaturar['idcurso'];
                    continue;
                }

            } else {
                if(empty($cursoFaturar['quantidade_matriculas']))
                {
                    $this->errosLog[] = 'Valor excedente preenchido porém quantidades de matrículas 1 não está preenchido. -  ' . $cursoFaturar['idcurso'];
                    continue;
                }
            }

            if (
                empty($cursoFaturar['quantidade_faturas_ciclo'])
                || $cursoFaturar['quantidade_faturas_ciclo'] == 0
            ) {
                $quantidadeFaturasCicloSindicato = $cursosObj
                    ->retornarValoresCursoSindicato(
                        $idSindicato,
                        'quantidade_faturas_ciclo',
                        $cursoFaturar['idcurso']
                    );

                $cursoFaturar['quantidade_faturas_ciclo'] = $quantidadeFaturasCicloSindicato['quantidade_faturas_ciclo'];

                if (
                    empty($cursoFaturar['quantidade_faturas_ciclo'])
                    || $cursoFaturar['quantidade_faturas_ciclo'] == 0
                ) {
                    $this->errosLog[] = 'quantidade_faturas_ciclo_vazio - Curso: ' . $cursoFaturar['idcurso'];
                    continue;
                }
            }

            if (
                empty($cursoFaturar['qtd_parcelas'])
                || $cursoFaturar['qtd_parcelas'] == 0
            ) {
                $valorPorMatriculaSindicato = $cursosObj
                    ->retornarValoresCursoSindicato(
                        $idSindicato,
                        'max_parcelas',
                        $cursoFaturar['idcurso']
                    );

                $cursoFaturar['qtd_parcelas'] = $valorPorMatriculaSindicato['max_parcelas'];

                if (
                    empty($cursoFaturar['qtd_parcelas'])
                    || $cursoFaturar['qtd_parcelas'] == 0
                ) {
                    $this->errosLog[] = 'qtd_parcelas_vazio - Curso: ' . $cursoFaturar['idcurso'];
                    continue;
                }
            }


            $matriculasArray = NULL;
            $matriculasArray = $this->retornarMatriculas($idCfc, $cursoFaturar['idcurso']);

            if (!$matriculasArray) {
                continue;
            }

            //INÍCIO cálculos valores que será cada matrícula
            $valorMatriculaPorFatura = round(
                $cursoFaturar['valor_por_matricula'] / $cursoFaturar['quantidade_faturas_ciclo'],
                2
            );
            $valorTotalMatricula = $valorMatriculaPorFatura * $cursoFaturar['quantidade_faturas_ciclo'];
            $valorPrimeiraParcelaMatricula = $valorMatriculaPorFatura
                + ($cursoFaturar['valor_por_matricula'] - $valorTotalMatricula);

            $valorParcela = $valorMatriculaPorFatura * $this->total;
            $valorPrimeiraParcela = $valorPrimeiraParcelaMatricula * $this->total;

            if(
               !empty($cursoFaturar['valor_excedente']) &&
               !empty($cursoFaturar['quantidade_matriculas']) &&
               $this->total > $cursoFaturar['quantidade_matriculas']
              )
            {
                $valorMatriculaExcedente = round(
                    $cursoFaturar['valor_excedente'] / $cursoFaturar['quantidade_faturas_ciclo'],
                    2
                );

                $valorTotalMatriculaExcedente = $valorMatriculaExcedente * $cursoFaturar['quantidade_faturas_ciclo'];
                $valorPrimeiraParcelaMatriculaExcedente = $valorMatriculaExcedente
                                                 + ($cursoFaturar['valor_excedente'] - $valorTotalMatriculaExcedente);

                $valorParcela =
                    ($valorMatriculaPorFatura * $cursoFaturar['quantidade_matriculas'])
                    + ($valorMatriculaExcedente * ($this->total - $cursoFaturar['quantidade_matriculas']));

                $valorPrimeiraParcela =
                    ($valorPrimeiraParcelaMatricula * $cursoFaturar['quantidade_matriculas'])
                    + ($valorPrimeiraParcelaMatriculaExcedente * ($this->total - $cursoFaturar['quantidade_matriculas']));
            }

            if(!empty($cursoFaturar['quantidade_matriculas_2']) && !empty($cursoFaturar['valor_por_matricula_2']))
            {

                $valorMatriculaPorFatura2 = round(
                    $cursoFaturar['valor_por_matricula_2'] / $cursoFaturar['quantidade_faturas_ciclo'],
                    2
                );

                $valorTotalMatricula2 = $valorMatriculaPorFatura2 * $cursoFaturar['quantidade_faturas_ciclo'];
                $valorPrimeiraParcelaMatricula2 = $valorMatriculaPorFatura2
                                                  + ($cursoFaturar['valor_por_matricula_2'] - $valorTotalMatricula2);

                if($this->total > $cursoFaturar['quantidade_matriculas_2'])
                {
                    $valorParcela =
                        ($valorMatriculaPorFatura * $cursoFaturar['quantidade_matriculas']) +
                        ($valorMatriculaPorFatura2 * ($cursoFaturar['quantidade_matriculas_2'] - $cursoFaturar['quantidade_matriculas'])) +
                        ($valorMatriculaExcedente * ($this->total - $cursoFaturar['quantidade_matriculas_2']));

                    $valorPrimeiraParcela =
                        ($valorPrimeiraParcelaMatricula * $cursoFaturar['quantidade_matriculas']) +
                        ($valorPrimeiraParcelaMatricula2 * ($cursoFaturar['quantidade_matriculas_2'] - $cursoFaturar['quantidade_matriculas'])) +
                        ($valorPrimeiraParcelaMatriculaExcedente * ($this->total - $cursoFaturar['quantidade_matriculas_2']));

                } else {
                    $valorParcela =
                        ($valorMatriculaPorFatura * $cursoFaturar['quantidade_matriculas']) +
                        ($valorMatriculaPorFatura2 * ($cursoFaturar['quantidade_matriculas_2'] - $cursoFaturar['quantidade_matriculas']));

                    $valorPrimeiraParcela =
                        ($valorPrimeiraParcelaMatricula * $cursoFaturar['quantidade_matriculas']) +
                        ($valorPrimeiraParcelaMatricula2 * ($cursoFaturar['quantidade_matriculas_2'] - $cursoFaturar['quantidade_matriculas']));
                }

            }



            $diasVencimento = 5;

            if (!empty($GLOBALS['config']['pagarme']['dias_vencimento'])) {
                $diasVencimento = $GLOBALS['config']['pagarme']['dias_vencimento'];
            }

            $dataInicio = (new \DateTime())
                ->modify('+' . $diasVencimento . ' days');

            for ($parcela = 1; $parcela <= $cursoFaturar['quantidade_faturas_ciclo']; $parcela++) {
                $valor = $valorParcela;
                $valorPorMatricula = $valorMatriculaPorFatura;

                if ($parcela == 1) {
                    $valor = $valorPrimeiraParcela;
                    $valorPorMatricula = $valorPrimeiraParcelaMatricula;
                }

                $dataVencimento = new DateTime($dataInicio->format('Y-m-d'));
                $dataVencimento->modify('+' . ($parcela - 1) . ' month');
                $vencimento = $dataVencimento->format('Y-m-d');

                $faturaMesmoVencimento = $this->retornarFaturaMesmoVencimento(
                    $idCfc,
                    $situacao['idsituacao'],
                    $vencimento
                );

                if ($faturaMesmoVencimento) {
                    $idConta = $faturaMesmoVencimento['idconta'];

                    $valor = $valor + $faturaMesmoVencimento['valor'];
                    $quantidadeMatriculas = $this->total + $faturaMesmoVencimento['qnt_matriculas'];

                    $salvar = $this->atualizarContasFatura(
                        $valor,
                        $quantidadeMatriculas,
                        $idConta
                    );
                } else {
                    $salvar = $this->cadastrarContaFatura(
                        $idCfc,
                        $idSindicato,
                        $situacao['idsituacao'],
                        $valor,
                        $vencimento,
                        $this->total
                    );

                    $idConta = mysql_insert_id();
                }

                $pagarmeObj = new PagarmeObj();
                $pagarmeObj->criarTransacaoBoleto($idConta);

                $valorTotal = $cursoFaturar['valor_por_matricula'];
                foreach ($matriculasArray as $ind => $matricula) {

                    if(
                       !empty($cursoFaturar['valor_por_matricula_2']) &&
                       !empty($cursoFaturar['quantidade_matriculas_2']) &&
                       $ind == $cursoFaturar['quantidade_matriculas']
                      )
                    {
                        $valorPorMatricula = $valorMatriculaPorFatura2;
                        $valorTotal = $cursoFaturar['valor_por_matricula_2'];
                        if ($parcela == 1)
                        {
                            $valorPorMatricula = $valorPrimeiraParcelaMatricula2;
                        }
                    }

                    if(
                        empty($cursoFaturar['valor_excedente_2']) &&
                        empty($cursoFaturar['quantidade_matriculas_2']) &&
                        !empty($cursoFaturar['quantidade_matriculas']) &&
                        $ind == $cursoFaturar['quantidade_matriculas']
                    )
                    {
                        $valorPorMatricula = $valorMatriculaExcedente;
                        $valorTotal = $cursoFaturar['valor_excedente'];
                        if ($parcela == 1)
                        {
                            $valorPorMatricula = $valorPrimeiraParcelaMatriculaExcedente;
                        }
                    }

                    if(
                       !empty($cursoFaturar['valor_excedente']) &&
                       !empty($cursoFaturar['quantidade_matriculas']) &&
                       !empty($cursoFaturar['quantidade_matriculas_2']) &&
                       $ind == $cursoFaturar['quantidade_matriculas_2']
                      )
                    {
                        $valorPorMatricula = $valorMatriculaExcedente;
                        $valorTotal = $cursoFaturar['valor_excedente'];
                        if ($parcela == 1)
                        {
                            $valorPorMatricula = $valorPrimeiraParcelaMatriculaExcedente;
                        }
                    }

                    $sql = "INSERT INTO
                            contas_matriculas
                        SET
                            ativo = 'S',
                            data_cad = NOW(),
                            idconta = {$idConta},
                            idmatricula = {$matricula['idmatricula']},
                            valor_fatura = '{$valorPorMatricula}',
                            valor_total = '{$valorTotal}',
                            parcela = {$parcela},
                            total_parcelas = {$cursoFaturar['quantidade_faturas_ciclo']},
                            qtd_parcelas = {$cursoFaturar['qtd_parcelas']}
                    ";
                    $salvar = $this->executaSql($sql);

                    $sql = "UPDATE matriculas SET faturada = 'S' WHERE idmatricula = {$matricula['idmatricula']};";
                    $this->executaSql($sql);

                    $matriculasFaturadas[$matricula['idmatricula']] = $matricula['idmatricula'];
                }
            }

            if (!$salvar) {
                $this->finalizaTransacao(true);

                $erros[] = $sql;
                $erros[] = mysql_error();
            }
        }

        if (!empty($erros)) {
            return $this->retornarErros($erros);
        }

        if (!empty($matriculasFaturadas)){
            $escolaObj = new Escolas();
            $escolaObj->enviarEmailBoletoDisponivel($idCfc);
        }

        $this->finalizaTransacao();

        $retorno['sucesso'] = true;

        return $retorno;
    }

    public function GerarTabelaRelatorio($dados, $q = null, $idioma, $configuracao = 'listagem')
    {

        echo '<table class="zebra-striped" id="sortTableExample">';
        echo '<thead>';
        echo '<tr>';

        foreach ($this->config[$configuracao] as $ind => $valor) {
            $tamanho = "";
            if ($valor["tamanho"]) {
                $tamanho = ' width="' . $valor["tamanho"] . '"';
            }

            $th = '<th class="';
            $th .= $class . ' headerSortReloca" ' . $tamanho . '>';
            echo $th;

            echo '<div class="headerNew">
                    ' . $idioma[$valor['variavel_lang']] . '
                </div>';

            echo '</th>';
        }

        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';

        if (count($dados) == 0) {
            echo '<tr>';
            echo '<td colspan="' . count($this->config[$configuracao]) . '">Nenhuma informação foi encontrada.</td>';
            echo '</tr>';
        } else {
            $totalValorFatura = 0;
            $totalValor = 0;

            foreach ($dados as $i => $linha) {
                $totalValorFatura += $linha['valor_fatura'];
                $totalValor += $linha['valor_total'];

                echo '<tr>';

                foreach ($this->config[$configuracao] as $ind => $valor) {
                    if ($valor['tipo'] == 'banco') {
                        echo '<td>' . stripslashes($linha[$valor['valor']]) . '</td>';
                    } elseif ($valor['tipo'] == 'php' && $valor['busca_tipo'] != 'hidden') {
                        $valor = $valor['valor'] . " ?>";
                        $valor = eval($valor);
                        echo '<td>' . stripslashes($valor) . '</td>';
                    } elseif ($valor['tipo'] == 'array') {
                        $variavel = $GLOBALS[$valor["array"]];
                        echo '<td>' . $variavel[$this->config['idioma_padrao']][$linha[$valor['valor']]] . '</td>';
                    } elseif ($valor['busca_tipo'] != 'hidden') {
                        echo '<td>' . stripslashes($valor['valor']) . '</td>';
                    }
                }

                echo '</tr>';
            }

            if (is_null($idioma['colspan'])){ //Verifica o colspan na última linha da tabela
                $idioma['colspan'] = 4;
            }

            echo '<tr>';
            echo '<td colspan="'. $idioma['colspan'] .'">&nbsp;</td>';
            echo '<td><strong>' . $idioma['total'] . '</strong></td>';
            echo '<td>R$ ' . number_format($totalValorFatura, 2, ',', '.') . '</td>';
            echo '<td>R$ ' . number_format($totalValor, 2, ',', '.') . '</td>';
            echo '<td></td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    public function getErrosLog()
    {
        return $this->errosLog;
    }
}
