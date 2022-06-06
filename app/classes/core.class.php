<?php
class Core
{
    public $busca = false;
    public $reenviar = false;
    public $having = null;
    public $email_reenvio = '';
    public $ordem = "desc";
    public $limite = 30;
    public $ordem_campo = "null";
    public $pagina = 1;
    public $paginas = 1;
    public $total = 1;
    public $inicio = null;
    public $groupby = null;
    public $manter_groupby = false;
    public $remover_distinct = false;
    public $post = false;
    public $id = false;
    public $usuario = null;
    public $retorno = array();
    public $idusuario = null;
    public $campos = " * ";
    public $sql = false;
    public $sqlAux = false;
    public $config = null;
    public $idioma = null;
    public $url = null;
    public $pro_mensagem_idioma = null;
    public $ancora = null;
    public $informacoes = array();
    public $nao_monitara = false;
    public $naoSalvarLogEmail = false;
    public $anexoEmail = null;

    public $perfil_permissao = array();

    // Monitoramento
    public $monitora_onde = null;
    public $monitora_oque = null;
    public $monitora_qual = null;
    public $monitora_observacoes = null;
    public $monitora_dadosantigos = null;
    public $monitora_dadosnovos = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->url = array_key_exists('url', $GLOBALS) ? $GLOBALS['url'] : $GLOBALS['config']['url'];
        $this->config = $GLOBALS["config"];
    }

    public function set($variavel, $valor)
    {
        if ($valor) {
            if ($variavel == 'ordem_campo' || $variavel == 'ordem' || $variavel == 'limite' || $variavel == 'having')
                $valor = addslashes(strip_tags($valor));
            $this->$variavel = $valor;
        }
        return $this;
    }

    public function apagaVariavel($variavel) {
        unset($this->$variavel);
        return $this;
    }

    public function setNull($variavel) {
        $this->$variavel = NULL;
        return $this;
    }

    public function setaFalso($variavel) {
        $this->$variavel = FALSE;
        return $this;
    }

    function setArray($array, $posicao, $valor)
    {
        $this->{$array}[$posicao] = $valor;
    }

    function Get($variavel)
    {
        return $this->$variavel;
    }

    function verificaPermissao($permissao, $permissaoid, $redireciona = true)
    {
        if ($permissao[$permissaoid]) {
            return true;
        } else {
            if ($redireciona) {
                incluirLib("sempermissao", $this->config, $GLOBALS["usuario"]);
                exit();
            } else {
                return false;
            }
        }
    }

    function Monitora($observacoes = false)
    {
        if (! $this->monitora_qual) {
            return false;
        }

        $idprimaria = $this->config["tabela_monitoramento_primaria"];
        if ($this->config["tabela_monitoramento"]) {
            $this->sql = "insert into
                      " . $this->config["tabela_monitoramento"] . "
                    set
                      " . $idprimaria . " = " . $this->$idprimaria . ",
                      idacao = " . $this->monitora_oque . ",
                      idonde = " . $this->monitora_onde . ",
                      data_cad = now(),
                      id = " . $this->monitora_qual . "";
            if ($observacoes) {
                $this->sql .= ", observacoes = '" . mysql_real_escape_string($this->monitora_observacoes) . "'";
            }

            mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            $idmonitora = mysql_insert_id();

            if (isset($this->monitora_dadosantigos) && isset($this->monitora_dadosnovos)) {
                foreach ($this->monitora_dadosantigos as $ind => $valor) {
                    if ($this->monitora_dadosantigos[$ind] <> $this->monitora_dadosnovos[$ind]) {
                        $this->sql = "insert into
                            " . $this->config["tabela_monitoramento_log"] . "
                          set
                            idmonitora = " . $idmonitora . ",
                            campo = '" . $ind . "',
                            de = '" . mysql_real_escape_string($this->monitora_dadosantigos[$ind]) . "',
                            para = '" . mysql_real_escape_string($this->monitora_dadosnovos[$ind]) . "'";
                        mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                    }
                }
            }
        }
        return true;
    }

    function iniciaTransacao()
    {
        return mysql_query("begin") or die(incluirLib("erro", $this->config, array("sql" => "begin", "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
    }

    function finalizaTransacao($rollback = false)
    {
        return mysql_query($rollback ? "rollback" : "commit") or die(incluirLib("erro", $this->config, array("sql" => "commit", "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
    }

    function retornarLinhasTD()
    {
        $this->retorno = array();
        if ($this->limite != -1) {
            $this->sqlAux = str_replace(
                $this->campos, " count(*) as total FROM (SELECT	" . $this->distinct . $this->groupby . "",
                $this->sql
            );

            $this->sqlAux .= $this->mantem_groupby && !empty($this->groupby) ? " GROUP BY " . $this->groupby. ")  AS Registros" : "";
            $linhaAux = $this->retornarLinha($this->sqlAux);

            $this->total = (int)$linhaAux["total"];
            if (intval($this->limite) <= 0 && intval($this->limite) != -1)
                $this->limite = 1;
            $this->paginas = ceil($this->total / $this->limite);
            if ($this->paginas == 0)
                $this->paginas = 1;

            $this->inicio = ($this->pagina - 1) * $this->limite;

            $this->sql .= $this->mantem_groupby && !empty($this->groupby) ? " GROUP BY " . $this->groupby : "";

            if ($this->remover_distinct) {
                $this->groupby = str_replace("DISTINCT", "", $this->groupby);
            }

            if ($this->ordem_campo && $this->ordem)
                $this->sql .= " order by " . $this->ordem_campo . " " . $this->ordem . "";
            if ($this->limite > 0)
                $this->sql .= " limit " . $this->inicio . "," . $this->limite . "";
        } else {
            if ($this->groupby && $this->mantem_groupby) {
                $this->sql .= ' group by ' . $this->groupby;
                unset($this->groupby);
            }
            if ($this->ordem_campo && $this->ordem)
                $this->sql .= " order by " . $this->ordem_campo . " " . $this->ordem . "";
        }
//echo $this->sql;
        $sqlAux = mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
        while ($linha = mysql_fetch_assoc($sqlAux)) {
            $this->retorno[] = $linha;
        }
        if ($this->limite == -1) {
            $this->total = count($this->retorno);
        }

        return $this->retorno;
    }

    function executaSql($sql)
    {
        $retorno = mysql_query($sql)
        or die(
        incluirLib(
            "erro",
            $this->config,
            array(
                "sql" => $sql,
                "session" => $_SESSION,
                "get" => $_GET,
                "post" => $_POST,
                "mysql_error" => mysql_error()
            )
        )
        );
        return $retorno;
    }

    function retornarLinhas()
    {
        $this->retorno = array();
        // $this->validarCampoOrdem();
        $this->removerInconsistenciaSQL($this->ordem_campo, '^(\w\.?)+ ?((asc|desc)?)+');
        $this->removerInconsistenciaSQL($this->ordem, '^(asc|desc)');
        if ($this->limite != -1) {
            if($this->having){
                $this->sqlAux = str_replace(
                    $this->campos, " count( " . $this->distinct . $this->groupby . ") as total ",
                    $this->sql
                );
                $this->sqlAux .= 'group by m.idmatricula ' . $this->having;
                $this->total = count($this->retornarLinhasArray($this->sqlAux));
            } else {
                if ($this->groupby != null or $this->distinct != null) {

                    $this->sqlAux = str_replace(
                        $this->campos, " count(  " . $this->distinct . $this->groupby . ") as total ",
                        $this->sql
                    );
                }else{
                    $this->sqlAux = str_replace(
                        $this->campos, " count(0) as total ",
                        $this->sql
                    );
                }
                $linhaAux = $this->retornarLinha($this->sqlAux);
                $this->total = (int)$linhaAux["total"];
            }
            //echo '<!-- '.$this->sqlAux.' -->';
            if (intval($this->limite) <= 0 && intval($this->limite) != -1)
                $this->limite = 1;
            $this->paginas = ceil($this->total / $this->limite);
            if ($this->paginas == 0)
                $this->paginas = 1;

            $this->inicio = ($this->pagina - 1) * $this->limite;

            $this->sql .= $this->mantem_groupby && !empty($this->groupby) ? " GROUP BY " . $this->groupby : "";
            $this->sql .= $this->having ? $this->having : "";
            if ($this->remover_distinct) {
                $this->groupby = str_replace("DISTINCT", "", $this->groupby);
            }

            if ($this->ordem_campo && $this->ordem)
                $this->sql .= " order by " . $this->ordem_campo . " " . $this->ordem . "";
            if ($this->limite > 0)
                $this->sql .= " limit " . $this->inicio . "," . $this->limite . "";
        } else {
            if ($this->groupby && $this->manter_groupby) {
                $this->sql .= ' group by ' . $this->groupby;
                unset($this->groupby);
            }

            $this->sql .= $this->having ? $this->having : "";

            if ($this->ordem_campo && $this->ordem)
                $this->sql .= " order by " . $this->ordem_campo . " " . $this->ordem . "";
        }
        // echo $this->sql;die();
        $sqlAux = mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
        while ($linha = mysql_fetch_assoc($sqlAux)) {
            $this->retorno[] = $linha;
        }
        unset($this->having);
        if ($this->limite == -1) {
            $this->total = count($this->retorno);
        }
        return $this->retorno;
    }

    function retornarLinha($sql)
    {
        //echo $sql;
        $seleciona = mysql_query($sql) or die(incluirLib("erro", $this->config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
        return mysql_fetch_assoc($seleciona);
    }

    public function retornarLinhasArray($sql)
    {
        $retornar = [];
        $executar = $this->executaSql($sql);

        while ($linha = mysql_fetch_assoc($executar)) {
            $retornar[] = $linha;
        }

        return $retornar;

    }

    /**
     * @param array $dados
     * @param null $q
     * @param $idioma
     * @param string $configuracao
     * @param null $classTabela
     */
    public function GerarTabela(array $dados, $q = null, $idioma, $configuracao = 'listagem', $classTabela = NULL)
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

        if (!$valor["idform"])
            $idform = "formBusca";
        else
            $idform = $valor["idform"];

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
                            $objCore = new Core();
                            $paginas = $this->paginas;
                            $objCore->sql = $valor["busca_sql"];
                            $limiteAux = $this->limite;
                            $objCore->limite = -1;
                            $objCore->ordem_campo = "nome";
                            $objCore->ordem = "asc";
                            $objCore->groupby = $valor["busca_sql_valor"];
                            $objCore->config[$configuracao] = NULL;
                            $dadosAux = $objCore->retornarLinhas();
                            $objCore->limite = $limiteAux;
                            foreach ($dadosAux as $ind => $campo_banco) {
                                $arrayAux = array("valor" => $campo_banco[$valor["busca_sql_valor"]], "label" => $campo_banco[$valor["busca_sql_label"]]);
                                $dadosSelect[] = $arrayAux;
                            }
                            $this->paginas = $paginas;
                            //Carrega os option de uma variavel global
                        } elseif ($valor["busca_array"]) {
                            $variavel = $GLOBALS[$valor["busca_array"]];
                            if (!$valor["ignoraridioma"])
                                $variavel = $variavel[$this->config["idioma_padrao"]];
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
                            if ($_GET['q'][$valor["busca_metodo"] . "|" . $valor["coluna_sql"]] === (string)$valorSelect["valor"]) {
                                $selected = 'selected="selected"';
                            }
                            echo '<option value="' . $valorSelect["valor"] . '" ' . $selected . '>' . stripslashes($valorSelect["label"]) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';
                        //Monta um hidden
                    } elseif ($valor["busca_tipo"] == "hidden") {
                        if ($valor["tipo"] == "php")
                            $valor["valor"] = eval($valor["valor"] . " ?>");
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
            foreach ($dados as $i => $linha) {
                echo '<tr>';
                foreach ($this->config[$configuracao] as $ind => $valor) {
                    if ($valor["tamanho"])
                        $style = " style=\"width:" . $valor["tamanho"] . "px;\"";
                    else
                        $style = "";

                    if ($valor["tipo"] == "banco") {
                        if ($valor["overflow"]) $linha[$valor["valor"]] = "<div class=\"tabelaOverflow\"$style>" . $linha[$valor["valor"]] . "</div>";
                        echo '<td>' . stripslashes($linha[$valor["valor"]]) . '</td>';
                    } elseif ($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
                        $valor = $valor["valor"] . " ?>";
                        $valor = eval($valor);
                        if ($valor)
                            $valor = "<div class=\"tabelaOverflow\"$style>" . $valor . "</div>";

                        echo '<td>' . stripslashes($valor) . '</td>';
                    } elseif ($valor["tipo"] == "array") {
                        $variavel = $GLOBALS[$valor["array"]];
                        if ($valor["overflow"])
                            $variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]] = "<div class=\"tabelaOverflow\"$style>" . $variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]] . "</div>";
                        echo '<td>' . $variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]] . '</td>';
                    } elseif ($valor["busca_tipo"] != "hidden") {
                        if ($valor["overflow"])
                            $valor["valor"] = "<div class=\"tabelaOverflow\"$style>" . $valor["valor"] . "</div>";

                        echo '<td>' . stripslashes($valor["valor"]) . '</td>';
                    }
                }
                echo '</tr>';
            }
        }
        echo '</tbody>';
        echo '</table>';
    }

    function GerarPaginacao($idioma, $mobile = null)
    {
        $this->retorno = "";
        $menos = $this->pagina - 1;
        $mais = $this->pagina + 1;

        $valor_utilizado = 5;
        if ($mobile) {
            $valor_utilizado = $mobile;
            unset($idioma["pag_anterior"]);
            unset($idioma["pag_proxima"]);
        }

        $link = '?qtd=' . $this->limite . '&cmp=' . $this->ordem_campo . '&ord=' . $this->ordem;
        if ($_GET["q"]) {
            foreach ($_GET["q"] as $tipo => $valor) {
                $link .= "&q[$tipo]=$valor";
            }
        }

        if ($this->paginas > 1) {
            if ($menos > 0)
                $this->retorno .= '<li class="prev"><a href="' . $link . '&pag=' . $menos . '">&#8592; ' . $idioma["pag_anterior"] . ' </a></li>';

            if (($this->pagina - $valor_utilizado) < 1)
                $anterior = 1; else $anterior = $this->pagina - $valor_utilizado;

            if (($this->pagina + $valor_utilizado) > $this->paginas)
                $posterior = $this->paginas; else $posterior = $this->pagina + $valor_utilizado;

            for ($i = $anterior; $i <= $posterior; $i++)
                if ($i != $this->pagina)
                    $this->retorno .= '<li><a href="' . $link . '&pag=' . $i . '">' . $i . '</a></li>';
                else
                    $this->retorno .= '<li class="active"><a href="' . $link . '&pag=' . $i . '">' . $i . '</a></li>';

            if ($mais <= $this->paginas)
                $this->retorno .= '<li class="next"><a href="' . $link . '&pag=' . $mais . '">' . $idioma["pag_proxima"] . ' &#8594;</a></li>';
        }
        return $this->retorno;
    }

//Gera o formulario de cadastro(modificar) das CRUD's
    function GerarFormulario($variavel, $dados, $idioma)
    {
        $config = $this->config[$variavel];
        foreach ($config as $fieldsetid => $fieldset) {
            echo '<fieldset id="fieldset_'.$fieldset['fieldsetid'].'">';
            echo '<legend id="legend_'.$fieldset['legendaidioma'].'">' . $idioma[$fieldset['legendaidioma']] . '</legend>';
            foreach ($fieldset["campos"] as $campoid => $campo) {
                //input, text, select, select_multiplo, check, radio
                //Monta um input do tipo text ou passwoed
                if ($campo["tipo"] == "input") {
                    if ($campo["senha"])
                        $tipo = "password";
                    else
                        $tipo = "text";

                    if(!empty($campo["input_tipo"]))
                        $tipo = $campo["input_tipo"];

                    if ($campo["valor_php"]) {
                        $valor = str_replace("%s", $dados[$campo["valor"]], $campo["valor_php"]);
                        $valor = "$valor; ?>";
                        $valor = eval($valor);
                    } elseif ($campo["sql"]) {
                        $this->sql = $campo["sql"];
                        $dadosAux = $this->retornarLinha($this->sql);
                        $valor = $dadosAux[$campo["valor"]];
                    } else {
                        $valor = $dados[$campo["valor"]];
                    }

                    if ($campo["input_hidden"])
                        echo '<div id="div_' . $campo["id"] . '" style="display:none;">';

                    echo '<div class="control-group">';
                    echo '<label class="control-label" for="' . $campo["id"] . '">';
                    if ($campo["validacao"]["required"])
                        echo "<strong>* " . $idioma[$campo["nomeidioma"]] . "</strong>";
                    else
                        echo $idioma[$campo["nomeidioma"]];

                    echo '</label>';
                    echo '<div class="controls">';

                    if ($campo["legenda"])
                        echo '<div class="input-prepend"> <span class="add-on">' . $campo["legenda"] . '</span>';

                    $valorCampo = stripslashes($this->SQLInjectionProtection($valor));

                    if ($campo["decimal"] && $valorCampo)
                        $valorCampo = number_format($valorCampo, 2, ',', '.');

                    echo '<input class="' . $campo["class"] . '" id="' . $campo["id"] . '" name="' . $campo["nome"] . '" type="' . $tipo . '" value="' . $valorCampo . '" ' . $campo["evento"] . ' />';

                    if ($campo["referencia_label"] && $campo["referencia_link"]) {
                        echo "<span class='link_referencia'><a href='" . $campo["referencia_link"] . "' onclick='return confirma_" . $campo["id"] . "()'>[" . $idioma[$campo["referencia_label"]] . "]</a></span>";
                        echo "
                <script>
                  function confirma_" . $campo["id"] . "() {
                    var confirma = confirm('" . $idioma["confirma_administracao"] . "');
                    if(confirma){
                      return true;
                    } else {
                      return false;
                    }
                  }
                </script>";
                    }

                    if ($campo["legenda"])
                        echo '</div>';

                    if ($campo["ajudaidioma"])
                        echo '<p class="help-block">' . $idioma[$campo["ajudaidioma"]] . '</p>';

                    echo '</div>';
                    echo '</div>';
                    if ($campo["input_hidden"])
                        echo '</div>';
                    //Monta um texarea
                } elseif ($campo["tipo"] == "text") {
                    if ($campo["text_hidden"])
                        echo '<div id="div_' . $campo["id"] . '" style="display:none;">';
                    echo '<div class="control-group">';
                    echo '<label class="control-label" for="' . $campo["id"] . '">';
                    if ($campo["validacao"]["required"])
                        echo "<strong>* " . $idioma[$campo["nomeidioma"]] . "</strong>";
                    else
                        echo $idioma[$campo["nomeidioma"]];

                    echo '</label>';
                    echo '<div class="controls">';
                    echo '<textarea class="' . $campo["class"] . '" id="' . $campo["id"] . '" name="' . $campo["nome"] . '" ' . $campo["evento"] . '>' . $dados[$campo["valor"]] . '</textarea>';
                    //echo '<textarea class="' . $campo["class"] . '" id="' . $campo["id"] . '" name="' . $campo["nome"] . '" ' . $campo["evento"] . '>' . nl2br($dados[$campo["valor"]]) . '</textarea>';

                    if ($campo["contador"])
                        echo '<div class="div_contador ' . $campo["class"] . '"><div align="right">' . $idioma[$campo["idiomacaracteres"]] . ' <span id="' . $campo["id"] . '_' . $campo["contador"] . '" class="contador_textarea"></span></div></div>';

                    if ($campo["ajudaidioma"])
                        echo '<p class="help-block">' . $idioma[$campo["ajudaidioma"]] . '</p>';

                    echo '</div>';
                    echo '</div>';

                    if ($campo["text_hidden"])
                        echo '</div>';

                    //Monta um php
                } elseif ($campo["tipo"] == "php") {
                    echo '<div class="control-group">';
                    echo '<label class="control-label" for="' . $campo["id"] . '">' . /*$idioma[$campo["nomeidioma"]].*/
                        '</label>';
                    echo '<div class="controls">';

                    echo '<a style="outline:none;" class="btn" id="' . $campo["id"] . '" name="' . $campo["nome"] . '"><i class="icon-list-alt"></i> ' . $idioma[$campo["nomeidioma"]] . '</a>';

                    if ($campo["ajudaidioma"])
                        echo '<p class="help-block">' . $idioma[$campo["ajudaidioma"]] . '</p>';

                    echo '<div id="div_' . $campo["id"] . '" style="display:none;">';
                    if (is_array($campo["valor"])) {
                        if (!$campo["colunas"]) $campo["colunas"] = 2;
                        foreach ($campo["valor"] as $ind2 => $array) {
                            echo '<p>&nbsp;</p><table class="table-striped table-bordered table-condensed" width="100%"><tr>';
                            $i = 1;
                            foreach ($array as $lingua => $valor) {
                                if ($valor != "titulo") {
                                    echo "<td><strong>$valor</strong></td>";
                                    echo "<td>$idioma[$lingua]</td>";
                                } else {
                                    echo "<td colspan='" . ($campo["colunas"] * 2) . "' style='border:0px; border-bottom: 1px solid #DDDDDD; background-color:#F8F8F8' width='100%'><strong>$idioma[$lingua]</strong></td>";
                                    $i++;
                                }

                                if ($i % $campo["colunas"] == 0) echo "</tr><tr>";
                                $i++;
                            }
                            if ($i - 1 % $campo["colunas"] != 0) echo "<td></td><td></td>";
                            echo '</tr></table>';
                        }
                    } elseif ($campo["tabela"]) {

                        $this->ordem = "asc";
                        $this->limite = -1;
                        $this->campos = implode(",", $campo["tabela"]["tabela_colunas"]);
                        $this->sql = "select " . $this->campos . "
                                              from " . $campo["tabela"]["tabela_nome"] . "
                                              where ativo = 'S' and " . $campo["tabela"]["chave_extrangeira"] . " = " . $this->url[3];
                        $this->groupby = $campo["tabela"]["chave_primaria"];
                        $linhas = $this->retornarLinhas($this->sql);

                        $colunas = count($campo["tabela"]["tabela_colunas"]) + count($campo["tabela"]["tabela_colunas_adicionais"]);

                        echo '<br><table class="table-striped table-bordered table-condensed" width="100%">';
                        if ($campo["tabela"]["titulo"]) {
                            echo "<tr><td colspan=\"" . $colunas . "\"><strong>" . $idioma[$campo["tabela"]["titulo"]] . "</strong></td></tr>";
                        }

                        foreach ($linhas as $ind => $linha) {

                            echo "<tr>";

                            for ($i = 0; $i < count($campo["tabela"]["tabela_colunas"]); $i++) {
                                if ($i == 0) {
                                    if ($campo["tabela"]["flag_identificacao"]) {
                                        echo "<td><strong>" . "[[" . $campo["tabela"]["flag_identificacao"] . "]][[" . $linha[$campo["tabela"]["tabela_colunas"][$i]] . "]]" . "</strong></td>";
                                    } else {
                                        echo "<td><strong>" . "[[" . $linha[$campo["tabela"]["tabela_colunas"][$i]] . "]]" . "</strong></td>";
                                    }
                                } else {
                                    echo "<td>" . $linha[$campo["tabela"]["tabela_colunas"][$i]] . "</td>";
                                }
                            }

                            for ($i = 0; $i < count($campo["tabela"]["tabela_colunas_adicionais"]); $i++) {
                                if (strstr($campo["tabela"]["tabela_colunas_adicionais"][$i], '/"')) {
                                    $link = explode('/"', $campo["tabela"]["tabela_colunas_adicionais"][$i]);
                                    $link[0] = $link[0] . "/" . $linha[$campo["tabela"]["chave_primaria"]];
                                    $link = implode('"', $link);
                                    echo "<td>" . $link . "</td>";
                                } else {
                                    echo "<td>" . $campo["tabela"]["tabela_colunas_adicionais"][$i] . "</td>";
                                }
                            }

                            echo "</tr>";
                        }
                        echo '</table>';
                    } else {
                        echo $campo["valor"];
                    }

                    echo '<p>&nbsp;</p></div>';

                    echo '</div>';
                    echo '</div>';
                } elseif ($campo["tipo"] == "botao") {

                    echo '<div class="control-group">';
                    echo '<label class="control-label" for="' . $campo["id"] . '">';
                    if ($campo["validacao"]["required"])
                        echo "<strong>* " . $idioma[$campo["nomeidioma"]] . "</strong>";
                    else
                        echo $idioma[$campo["nomeidioma"]];
                    echo '</label>';
                    echo '<div class="controls">';

                    echo '<a href="' . $campo["link"] . '" target="' . $campo["target"] . '" class="btn' . $campo["class"] . '" style="outline:none;' . $campo["style"] . '" id="' . $campo["id"] . '" name="' . $campo["nome"] . '" onclick="' . $campo["onclick"] . '"><i class="icon-list-alt"></i> ' . $campo["textobotao"] . '</a>';

                    if(isset($campo["preview"])){
                        echo '&nbsp&nbsp&nbsp<a title="Preview do conteúdo" href="' . $campo["preview"] . '" target="_blank"><i class="icon-eye-open"></i></a>';
                    }

                    if ($campo["ajudaidioma"])
                        echo '<p class="help-block">' . $idioma[$campo["ajudaidioma"]] . '</p>';

                    echo '</div>';
                    echo '</div>';
                    //Monta um select
                } elseif ($campo["tipo"] == "select") {
                    $dadosSelect = array();
                    $arrayValores = array();
                    //Carrega os option diretamente do banco de dados
                    if ($campo["sql"]) {
                        $this->sql = $campo["sql"];
                        $this->limite = -1;
                        $this->ordem_campo = $campo["sql_ordem_campo"];
                        $this->ordem = $campo["sql_ordem"];
                        $this->groupby = $campo["sql_valor"];
                        $dadosAux = $this->retornarLinhas();
                        foreach ($dadosAux as $ind => $campo_banco) {
                            if (is_array($campo["sql_label"])) {
                                $labelAux = "";
                                $label = NULL;
                                foreach ($campo["sql_label"] as $separador => $labels) {
                                    foreach ($labels as $ind => $valor) {
                                        if (is_array($valor)) {
                                            $labelAux[] = $valor[0][$campo_banco[$ind]];
                                        } else {
                                            $labelAux[] = $campo_banco[$valor];
                                        }
                                    }
                                    $label .= implode($separador, $labelAux);
                                }
                                $arrayAux = array("valor" => $campo_banco[$campo["sql_valor"]], "label" => $label);
                            } else {
                                $arrayAux = array("valor" => $campo_banco[$campo["sql_valor"]], "label" => $campo_banco[$campo["sql_label"]]);
                            }

                            $dadosSelect[] = array_map(stripslashes, $arrayAux);
                        }

                        //TIPO CONTADOR CRIAR CAMPO SELECT COM INTERVALO DE NUMEROS
                    } elseif ($campo["contador"] && $campo["intervalo"]) {
                        $intervalo = explode('-', $campo["intervalo"]);
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

                        //Carrega os option de uma variavel global
                    } elseif ($campo["array"]) {
                        if (is_array($campo["array"])) {
                            foreach ($campo["array"] as $ind => $campo_array) {
                                $arrayValores = $GLOBALS[$ind][$campo_array];
                            }
                        } else {
                            $arrayValores = $GLOBALS[$campo["array"]];
                            if (!$campo["ignoraridioma"])
                                $arrayValores = $arrayValores[$this->config["idioma_padrao"]];

                        }
                        $dadosSelect = array();
                        foreach ($arrayValores as $ind => $campo_array) {
                            $arrayAux = array("valor" => $ind, "label" => $campo_array);
                            $dadosSelect[] = $arrayAux;
                        }
                    }

                    if ($campo["select_hidden"])
                        echo '<div id="div_' . $campo["id"] . '" style="display:none;">';

                    echo '<div class="control-group">';
                    echo '<label class="control-label" for="' . $campo["id"] . '">';
                    if ($campo["validacao"]["required"]) echo "<strong>* " . $idioma[$campo["nomeidioma"]] . "</strong>"; else echo $idioma[$campo["nomeidioma"]];
                    echo '</label>';
                    echo '<div class="controls">';

                    if ($campo["legenda"])
                        echo '<div class="input-prepend"> <span class="add-on">' . $campo["legenda"] . '</span>';

                    echo '<select name="' . $campo["nome"] . '" id="' . $campo["id"] . '" class="' . $campo["class"] . '" ' . $campo["evento"] . ' >';

                    if ($campo["textoselect"]) {
                        echo '<option value="todos">' . $idioma[$campo["textoselect"]] . '</option>';
                    } elseif ($campo["sem_primeira_linha"]) {
                        echo '';
                    } else {
                        echo '<option value=""></option>';
                    }


                    if ($campo["campo_todos"]) {
                        if ($dados[$campo["valor"]] === 0)
                            $selectAll = 'selected="selected"';
                        echo '<option value="0" ' . $selectAll . '>' . $idioma[$campo["campo_todos"]] . '</option>';
                    }

                    foreach ($dadosSelect as $indSelect => $valorSelect) {
                        $selected = '';
                        if (isset($dados[$campo["valor"]]) && ($dados[$campo["valor"]] == $valorSelect["valor"])) {
                            $selected = 'selected="selected"';
                        }
                        echo '<option value="' . $valorSelect["valor"] . '" ' . $selected . '>' . $valorSelect["label"] . '</option>';
                    }
                    echo '</select>';

                    if ($campo["referencia_label"] && $campo["referencia_link"]) {
                        echo "<span class='link_referencia'><a href='" . $campo["referencia_link"] . "' onclick='return confirma_" . $campo["id"] . "()'>[" . $idioma[$campo["referencia_label"]] . "]</a></span>";
                        echo "
                                <script>
                                    function confirma_" . $campo["id"] . "() {
                                        var confirma = confirm('" . $idioma["confirma_administracao"] . "');
                                        if(confirma){
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    }
                                </script>
                            ";
                    }

                    if ($campo["legenda"])
                        echo '</div>';

                    if ($campo["ajudaidioma"])
                        echo '<p class="help-block">' . $idioma[$campo["ajudaidioma"]] . '</p>';

                    echo '</div>';
                    echo '</div>';

                    if ($campo["select_hidden"])
                        echo '</div>';

                    // VERIFICA SE O CAMPO DEVERÃ�? SER ATUALIZADO COM AJAX PASSANDO O ID DA TAG HTML
                    // LINHA|NOME_DIV|NOMEAJAX|PARAMETRO_TABELA|PARAMETRO_PRIMARYKEY
                    //$campos[0] = linha
                    //$campos[1] = idcidade
                    //$campos[2] = ajax_cidades
                    //$campos[3] = idestado
                    //$campos[4] = idcidade
                    if ($campo["onload"]) {
                        $tempo = $count++ * 500;
                        $campos = explode("|", $campo["onload"]);
                        $campos[0] = $this->Retornar();
                        /*echo "<script>solicita('".$campos[1]."','/".$this->url["0"].'/'.$this->url["1"].'/'.$this->url["2"].'/'.$this->url["3"].'/'.$campos[2].'/'.$campos[0][$campos[3]].'/'.$campos[0][$campos[4]]."')</script>";*/
                        $ajax = "solicita('" . $campos[1] . "','/" . $this->url["0"] . '/' . $this->url["1"] . '/' . $this->url["2"] . '/' . $this->url["3"] . '/' . $campos[2] . '/' . $campos[0][$campos[3]] . '/' . $campos[0][$campos[4]] . "')";
                        echo '<script>
                            setTimeout("' . $ajax . '", ' . $tempo . ');
                            </script>';
                    }

                } elseif ($campo["tipo"] == "div") {
                    echo '<div class="control-group">';
                    echo '<label class="control-label" for="' . $campo["id"] . '">' . $idioma[$campo["nomeidioma"]] . '</label>';
                    echo '<div class="controls">';
                    echo '<div id="' . $campo["nome"] . '"></div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                    //Monta um select com ajax
                } elseif ($campo["tipo"] == "checkbox") {
                    $dadosRadio = array();
                    //Carrega os option diretamente do banco de dados
                    if ($campo["sql"]) {
                        $this->sql = $campo["sql"];
                        $this->limite = -1;
                        if ($campo["sql_ordem_campo"])
                            $this->ordem_campo = $campo["sql_ordem_campo"];
                        else
                            $this->ordem_campo = "nome";

                        if ($campo["sql_ordem"])
                            $this->ordem = $campo["sql_ordem"];
                        else
                            $this->ordem = "desc";

                        $this->groupby = $campo["sql_valor"];
                        $dadosAux = $this->retornarLinhas();
                        foreach ($dadosAux as $ind => $campo_banco) {
                            $arrayAux = array("valor" => $campo_banco[$campo["sql_valor"]], "label" => $campo_banco[$campo["sql_label"]]);
                            $dadosRadio[] = $arrayAux;
                        }
                    } elseif ($campo["array"]) { //Carrega os option de uma variavel global
                        foreach ($GLOBALS[$campo["array"]][$GLOBALS['config']['idioma_padrao']] as $ind => $campo_array) {
                            $arrayAux = array("valor" => $ind, "label" => $campo_array);
                            $dadosRadio[] = $arrayAux;
                        }
                    } elseif ($campo["contador"] && $campo["intervalo"]) {
                        $intervalo = explode('-', $campo["intervalo"]);
                        if (count($intervalo) > 1) {
                            $cont_de = $intervalo[0];
                            $cont_ate = $intervalo[1];
                        } else {
                            $cont_de = 0;
                            $cont_ate = $intervalo[0];
                        }

                        for ($cont_de; $cont_de <= $cont_ate; $cont_de++) {
                            $arrayAux = array("valor" => $cont_de, "label" => $cont_de);
                            $dadosRadio[] = $arrayAux;
                        }
                    }


                    if ($campo['check_hidden'])
                        echo '<div id="div_' . $campo['id'] . '" style="display:none">';

                    echo '<div class="control-group">';
                    echo '<label class="control-label" for="' . $campo['id'] . '" id="label_'.$campo["id"].'">';
                    if ($campo["validacao"]["required"])
                        echo "<strong>* " . $idioma[$campo["nomeidioma"]] . "</strong>";
                    else
                        echo $idioma[$campo["nomeidioma"]];
                    echo '</label>';
                    echo '<div class="controls">';
                    if ($campo["legenda"])
                        echo '<div class="input-prepend"> <span class="add-on">' . $campo["legenda"] . '</span>';


                    $selected = NULL;
                    if (count($dadosRadio) > 0) {
                        foreach ($dadosRadio as $indSelect => $valorSelect) {
                            if ($campo['sql_comparacao']) {
                                $this->sql = $campo['sql_comparacao'] . '"' . $valorSelect['valor'] . '"';
                                $linhaArquivo = $this->retornarLinha($this->sql);
                                if ($linhaArquivo[$campo['valor']] == $valorSelect["valor"])
                                    $selected = ' checked="checked"';
                            }

                            if ($campo['array_serializado']) {
                                $arrayAux = unserialize($dados[$campo["nome"]]);
                                if (is_array($arrayAux)) {
                                    if (in_array($valorSelect["valor"], $arrayAux)) {
                                        $selected = ' checked="checked"';
                                    }
                                }
                            }

                            if ($dados[$campo["valor"]] == $valorSelect["valor"]) {
                                $selected = ' checked="checked"';
                            }

                            echo '<label class="checkbox">
                                        <input id="' . $campo["id"] . '" name="' . $campo["nome"] . '[]" value="' . $valorSelect["valor"] . '" ' . $campo["evento"] . ' type="checkbox" ' . $selected . '>
                                        ' . $valorSelect["label"] . '
                                      </label>';
                            $selected = '';
                        }
                    } else {
                        if ($dados[$campo["valor"]] == 'S')
                            $selected = ' checked="checked"';
                        //$valorSelect["label"]
                        echo '<label class="checkbox">
                                        <input id="' . $campo["id"] . '" name="' . $campo["nome"] . '[]" value="1" type="checkbox" ' . $selected . '>
                                        ' . $idioma[$campo['labelidioma']] . '
                                      </label>';
                    }

                    if ($campo["legenda"])
                        echo '</div>';

                    if ($campo["ajudaidioma"])
                        echo '<p class="help-block">' . $idioma[$campo["ajudaidioma"]] . '</p>';

                    echo '</div>';
                    echo '</div>';

                    //Monta um radio
                } elseif ($campo["tipo"] == "radio") {

                    $dadosRadio = array();
                    //Carrega os option diretamente do banco de dados
                    if ($campo["sql"]) {
                        $this->sql = $campo["sql"];
                        $this->limite = -1;
                        $this->ordem_campo = "nome";
                        $this->groupby = $campo["sql_valor"];
                        $dadosAux = $this->retornarLinhas();
                        foreach ($dadosAux as $ind => $campo_banco) {
                            $arrayAux = array("valor" => $campo_banco[$campo["sql_valor"]], "label" => $campo_banco[$campo["sql_label"]]);
                            $dadosRadio[] = $arrayAux;
                        }
                    }
                    //Carrega os option de uma variavel global
                    if ($campo["array"]) {
                        foreach ($GLOBALS[$campo["array"]] as $ind => $campo_array) {
                            $arrayAux = array("valor" => $ind, "label" => $campo_array);
                            $dadosRadio[] = $arrayAux;
                        }
                    }
                    echo '<div class="control-group">';
                    echo '<label class="control-label" for="' . $campo["id"] . '">';
                    if ($campo["validacao"]["required"]) echo "<strong>* " . $idioma[$campo["nomeidioma"]] . "</strong>"; else echo $idioma[$campo["nomeidioma"]];
                    echo '</label>';
                    echo '<div class="controls">';
                    if ($campo["legenda"])
                        echo '<div class="input-prepend"> <span class="add-on">' . $campo["legenda"] . '</span>';

                    echo '<ul class="inputs-list">';
                    foreach ($dadosRadio as $indSelect => $valorSelect) {
                        $selected = '';
                        if ($dados[$campo["valor"]] == $valorSelect["valor"])
                            $selected = ' checked="checked"';
                        echo '<label class="radio">
                                        <input id="' . $campo["id"] . $indSelect . '" name="' . $campo["nome"] . '" value="' . $valorSelect["valor"] . '" type="radio" ' . $selected . '>
                                        ' . $valorSelect["label"] . '
                                      </label>';
                    }
                    echo '</ul>';

                    if ($campo["legenda"])
                        echo '</div>';

                    if ($campo["ajudaidioma"])
                        echo '<p class="help-block">' . $idioma[$campo["ajudaidioma"]] . '</p>';

                    echo '</div>';
                    echo '</div>';

                    //Monta um hidden
                } elseif ($campo["tipo"] == "hidden") {
                    $valor = $campo["valor"] . " ?>";
                    $valor = eval($valor);
                    echo '<input id="' . $campo["id"] . '" name="' . $campo["nome"] . '" type="hidden" value="' . $valor . '" />';

                    //Monta um hidden
                } elseif ($campo["tipo"] == "file") {

                    echo '<div class="control-group">';
                    echo '<label class="control-label" for="' . $campo["id"] . '">';
                    if ($campo["validacao"]["file_required"]) echo "<strong>* " . $idioma[$campo["nomeidioma"]] . "</strong>"; else echo $idioma[$campo["nomeidioma"]];
                    echo '</label>';
                    echo '<div class="controls">';
                    if ($campo["legenda"])
                        echo '<div class="input-prepend"> <span class="add-on">' . $campo["legenda"] . '</span>';

                    $valor = $campo["valor"] . " ?>";
                    $valor = eval($valor);
                    echo '<input id="' . $campo["id"] . '" name="' . $campo["nome"] . '" type="file" />';

                    if ($campo["legenda"])
                        echo '</div>';

                    if ($dados[$this->config["banco"]["primaria"]]) {
                        $this->sql = "select * from " . $this->config["banco"]["tabela"] . " where " . $this->config["banco"]["primaria"] . " = " . $dados[$this->config["banco"]["primaria"]] . "";
                        $linhaArquivo = $this->retornarLinha($this->sql);
                        if ($linhaArquivo[$campo["nome"] . "_servidor"]) {
                            echo '<div id="' . $campo["nome"] . '_ajax"><span class="help-block">' . $idioma[$campo["arquivoidioma"]] . ' ';
                            if ($campo["download"]) {
                                if ($campo["download_caminho"]) {
                                    echo '<a href="/' . $campo["download_caminho"] . '/download/' . $campo["nome"] . '">';
                                } elseif ($this->url[5]) {
                                    echo '<a href="/' . $this->url[0] . '/' . $this->url[1] . '/' . $this->url[2] . '/' . $this->url[3] . '/' . $this->url[4] . '/' . $this->url[5] . '/download/' . $campo["nome"] . '">';
                                } elseif ($this->url[3]) {
                                    echo '<a href="/' . $this->url[0] . '/' . $this->url[1] . '/' . $this->url[2] . '/' . $this->url[3] . '/download/' . $campo["nome"] . '">';
                                } else {
                                    echo '<a href="/' . $this->url[0] . '/' . $this->url[1] . '/' . $this->url[2] . '/download/' . $campo["nome"] . '">';
                                }
                            } else {
                                echo '<a href="#">';
                            }
                            echo $linhaArquivo[$campo["nome"] . "_nome"] . ' (' . tamanhoArquivo($linhaArquivo[$campo["nome"] . "_tamanho"]) . ')</a>';

                            if ($campo["excluir"]) {
                                if ($campo["download_caminho"]) {
                                    echo '<a href="javascript:deletaArquivo(\'' . $campo["nome"] . '_ajax\',\'/' . $campo["download_caminho"] . '/excluir/' . $campo["nome"] . '\');">[' . $idioma[$campo["arquivoexcluir"]] . ']</a>';
                                } elseif ($this->url[4] && $this->url[5]) {
                                    echo '<a href="javascript:deletaArquivo(\'' . $campo["nome"] . '_ajax\',\'/' . $this->url[0] . '/' . $this->url[1] . '/' . $this->url[2] . '/' . $this->url[3] . '/' . $this->url[4] . '/' . $this->url[5] . '/excluir/' . $campo["nome"] . '\');">[' . $idioma[$campo["arquivoexcluir"]] . ']</a>';
                                } elseif ($this->url[2] && $this->url[3]) {
                                    echo '<a href="javascript:deletaArquivo(\'' . $campo["nome"] . '_ajax\',\'/' . $this->url[0] . '/' . $this->url[1] . '/' . $this->url[2] . '/' . $this->url[3] . '/excluir/' . $campo["nome"] . '\');">[' . $idioma[$campo["arquivoexcluir"]] . ']</a>';
                                } else {
                                    echo '<a href="javascript:deletaArquivo(\'' . $campo["nome"] . '_ajax\',\'/' . $this->url[0] . '/' . $this->url[1] . '/' . $this->url[2] . '/excluir/' . $campo["nome"] . '\');">[' . $idioma[$campo["arquivoexcluir"]] . ']</a>';
                                }
                            }
                            echo '</span>';
                            echo '</div>';
                        }
                    }
                    if ($campo["ajudaidioma"])
                        echo '<p class="help-block">' . $idioma[$campo["ajudaidioma"]] . '</p>';

                    echo '</div>';
                    echo '</div>';


                } else {
                    echo "<span style=\"color:#FF0000\">Tipo não aceito: <strong>" . $campo["tipo"] . "</strong></span>";
                }
            }
            echo '</fieldset>';
        }
    }

    function SQLInjectionProtection($str, $charset = 'UTF-8')
    {
        //Remove Null Characters
        $str = preg_replace('/\0+/', '', $str);
        $str = preg_replace('/(\\\\0)+/', '', $str);

        //Validate standard character entities
        $str = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u', "\\1;", $str);

        //Validate UTF16 two byte encoding (x00)
        $str = preg_replace('#(&\#x*)([0-9A-F]+);*#iu', "\\1\\2;", $str);

        //URL Decode
        $str = preg_replace("/%u0([a-z0-9]{3})/i", "&#x\\1;", $str);
        $str = preg_replace("/%([a-z0-9]{2})/i", "&#x\\1;", $str);

        //Convert character entities to ASCII
        if (preg_match_all("/<(.+?)>/si", $str, $matches)) {
            for ($i = 0; $i < count($matches['0']); $i++) {
                $str = str_replace($matches['1'][$i],
                    html_entity_decode($matches['1'][$i], ENT_COMPAT, $charset), $str);
            }
        }

        //Convert all tabs to spaces
        $str = preg_replace("#\t+#", " ", $str);

        //Makes PHP tags safe
        $str = str_replace(array('<?php', '<?PHP', '<?', '?>', '"'), array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;', '&quot;'), $str);

        //Compact any exploded words/
        $words = array('javascript', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
        foreach ($words as $word) {
            $temp = '';
            for ($i = 0; $i < strlen($word); $i++) {
                $temp .= substr($word, $i, 1) . "\s*";
            }

            $temp = substr($temp, 0, -3);
            $str = preg_replace('#' . $temp . '#s', $word, $str);
            $str = preg_replace('#' . ucfirst($temp) . '#s', ucfirst($word), $str);
        }

        //Remove disallowed Javascript in links or img tags
        $str = preg_replace("#<a.+?href=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>.*?</a>#si", "", $str);
        $str = preg_replace("#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si", "", $str);
        $str = preg_replace("#<(script|xss).*?\>#si", "", $str);
        $str = preg_replace("#<\/(script|xss).*?\>#si", "", $str);

        //Remove JavaScript Event Handlers
        $str = preg_replace('#(<[^>]+.*?)(onblur|onchange|onclick|onfocus|onload|onmouseover|onmouseup|onmousedown|onselect|onsubmit|onunload|onkeypress|onkeydown|onkeyup|onresize)[^>]*>#iU', "\\1>", $str);

        //Sanitize naughty HTML elements
        $str = preg_replace('#<(/*\s*)(alert|applet|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title|xml|xss)([^>]*)>#is', "&lt;\\1\\2\\3&gt;", $str);

        //Sanitize naughty scripting elements
        $str = preg_replace('#(alert|cmd|passthru|eval|exec|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);

        //Final clean up
        $bad = array(
            'document.cookie' => '',
            'document.write' => '',
            'window.location' => '',
            "javascript\s*:" => '',
            "Redirect\s+302" => '',
            '<!--' => '&lt;!--',
            '-->' => '--&gt;'
        );

        foreach ($bad as $key => $val) {
            $str = preg_replace("#" . $key . "#i", $val, $str);
        }

        //Apenas remover as barras se ele jÃ¡ foi reduzido em PHP
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        //Remover caracteres desagradÃ¡veis MySQL.
        $str = mysql_real_escape_string($str);

        return $str;

    }

    function BuscarErros()
    {
        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules
        if (is_array($this->config["formulario"])) {
            foreach ($this->config["formulario"] as $fieldsetid => $fieldset) {
                foreach ($fieldset["campos"] as $campoid => $campo) {

                    if (!is_array($this->post[$campo["nome"]])) {
                        if ($campo["tipo"] != "text")
                            $this->post[$campo["nome"]] = trim($this->SQLInjectionProtection($this->post[$campo["nome"]]));
                    } else {
                        $this->post[$campo["nome"]] = array_map(trim, $this->post[$campo["nome"]]);
                        $this->post[$campo["nome"]] = array_map($this->SQLInjectionProtection, $this->post[$campo["nome"]]);
                    }

                    if ($campo["mascara"] and is_array($this->post)) {
                        $mascara = str_replace("9", "_", $campo["mascara"]);
                        if ($mascara == $this->post[$campo["nome"]])
                            $this->post[$campo["nome"]] = "";
                    }
                    //echo "<br>".$this->post[$campo["nome"]]["size"];
                    if (is_array($campo["validacao"])) {
                        foreach ($campo["validacao"] as $tipo => $mensagem) {
                            if ($campo["tipo"] == "file") {
                                if ($this->post[$campo["nome"]]['name'])
                                    $regras[] = "$tipo," . $campo["nome"] . "," . $campo["extensoes"] . "," . $campo["tamanho"] . ",$mensagem";
                                else
                                    $regras[] = "$tipo," . $campo["nome"] . ",$mensagem";
                            } elseif ($campo["minimo_check"]) {
                                $regras[] = "$tipo," . $campo["nome"] . "," . $campo["minimo_check"] . ",$mensagem";
                            } else {
                                $regras[] = "$tipo," . $campo["nome"] . ",$mensagem";
                            }
                        }
                    }
                }
            }
            $erros = validateFields($this->post, $regras);
        } else {
            $erros[] = "Arquivo de configuração não setado.";
        }

        // Erros Unicos
        if (is_array($this->config["banco"]["campos_unicos"])) {
            foreach ($this->config["banco"]["campos_unicos"] as $ind => $valor) {

                $valor_form = array();
                $campo_php = array();
                $campos_unicos = array();
                $sql_compara = array();

                if ($valor["campo_php"]) {
                    $campo_php = explode("||", $valor["campo_php"]);
                    $campos_unicos = explode("||", $valor["campo_form"]);

                    foreach ($campo_php as $indc => $valorcampo) {
                        $valor_form_php = str_replace("%s", $this->post[$campos_unicos[$indc]], $valorcampo);
                        $valor_form_php = "$valor_form_php; ?>";
                        $valor_form[] = eval($valor_form_php);
                    }

                } else {
                    $campos_unicos = explode("||", $valor["campo_form"]);

                    foreach ($campos_unicos as $indc => $valorcampo) {
                        $valor_form[] = strtolower($this->post[$valorcampo]);
                    }

                }

                if ($valor_form[0] <> $this->SQLInjectionProtection(strtolower($this->post[$valor["campo_form"] . "_antigo"]))) {

                    foreach ($valor_form as $indval => $val) {
                        $sql_compara[] = $campos_unicos[$indval] . " = '" . $this->SQLInjectionProtection($val) . "'";
                    }

                    $this->sql = "SELECT count(*) as total FROM " . $this->config["banco"]["tabela"] . " WHERE " . implode(" AND ", $sql_compara) . " AND ativo = 'S' ";
                    if ($_POST[$this->config["banco"]["primaria"]])
                        $this->sql .= " AND ".$this->config["banco"]["primaria"]." <> ".$_POST[$this->config["banco"]["primaria"]]." ";

                    $verifica = $this->retornarLinha($this->sql);

                    if ($verifica["total"] > 0) {
                        $erros[] = $valor["erro_idioma"];
                    }
                }
            }
        }

        if (!empty($erros)) {
            return $erros;
        } else {
            return false;
        }
    }

    function SalvarDados()
    {
        $this->retorno = array();
        $arrayAux = array();
        $arrayCampos = array();

        $erros = $this->BuscarErros();
        if ($erros) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;

        } else {
            if (!$this->post[$this->config["banco"]["primaria"]]) {
                foreach ($this->config["banco"]["campos_insert_fixo"] as $campo => $valor) {
                    $arrayCampos[] = $campo . " = " . $valor;
                }
            }

            foreach ($this->config["formulario"] as $fieldsetid => $fieldset) {
                foreach ($fieldset["campos"] as $campoid => $campo) {

                    $valorArray = array();
                    //VERIFICAR SE EXISTE ARRAY NO FORMULARIO
                    if ($campo["tabela"])
                        $formularioArray = true;

                    if ($campo["banco"] && !$campo['nosql']) {

                        if ($campo["decimal"]) {
                            $this->post[$campo["nome"]] = str_replace('.', '', $this->post[$campo["nome"]]);
                            $this->post[$campo["nome"]] = str_replace(',', '.', $this->post[$campo["nome"]]);
                        }

                        if ($campo["tipo"] == "file") {
                            if ($this->post[$campo["nome"]]["error"] != 4) {
                                $validar = $this->ValidarArquivo($this->post[$campo["nome"]]);
                                if (!$validar) {
                                    $arrayAux = $this->Upload($this->post[$this->config["banco"]["primaria"]], $this->post[$campo["nome"]], $campo,
                                        $campo['diminuir_largura']);
                                    if (is_array($arrayAux)) {
                                        foreach ($arrayAux as $indAux => $valAux) {
                                            $arrayCampos[] = $valAux;
                                        }
                                    } else {
                                        echo $campo["nome"];
                                        $erros[] = $arrayAux;
                                        $this->retorno["erro"] = true;
                                        $this->retorno["erros"] = $erros;
                                        return $this->retorno;
                                    }
                                } else {
                                    $erros[] = $validar;
                                    $this->retorno["erro"] = true;
                                    $this->retorno["erros"] = $erros;
                                    return $this->retorno;
                                }
                            }
                            unset($this->post[$campo["nome"]]);
                        }
                        if ($campo["banco_sql"] && $this->post[$campo["nome"]]) {
                            $valor = str_replace("%s", $this->post[$campo["nome"]], $campo["banco_sql"]);
                        } elseif ($campo["banco_php"] && $this->post[$campo["nome"]]) {
                            $valor = str_replace("%s", $this->post[$campo["nome"]], $campo["banco_php"]);
                            $valor = "$valor; ?>";
                            $valor = eval($valor);
                        } else {

                            if (!is_array($this->post[$campo["nome"]])) {
                                $valor = $this->post[$campo["nome"]];
                            } else {
                                if ($campo["tabela"])
                                    $valor = $this->post[$campo["nome"]];
                                else
                                    //ENTRAR NESTA CONFIÇÃO QUANDO FOR UTILIZADO A PARTE O SELECT DO FACEBOOK
                                    $valor = $this->post[$campo["nome"]][0];
                            }
                        }

                        if ($this->post[$campo["nome"]] == "" && $campo["ignorarsevazio"]) {
                            // Ignorar o campo
                        } else {

                            if ($campo["banco_string"]) {
                                if ($valor == "") {
                                    $valor = "NULL";
                                } else {
                                    if ($campo["editor"]) {
                                        $valor = "'". mysql_real_escape_string($valor) ."'";
                                    } else {
                                        $valor = "'$valor'";
                                    }
                                }
                            }

                            if (!is_array($valor) and !$campo["tabela"]) {
                                if ($campo['nao_nulo'] && (!$valor || $valor == "NULL")) {
                                    //NAO RECEBERA VALOR E FICARA COM O VALOR PADRAO DO BANCO
                                } else if ($valor === "") {
                                    $valor = "NULL";
                                } else {
                                    $arrayCampos[] = $campo["nome"] . " = " . $valor;
                                }
                            } else {
                                if (is_array($valor)) {
                                    foreach ($valor as $ind => $valorCampo) {
                                        $valorArray[] = $campo["nome"] . " = '" . $valorCampo . "'";
                                    }
                                }
                                $arrayCampoSecundario[$campo["tabela"]] = $valorArray;
                            }

                        }

                    }

                }

            }

            if (is_array($this->config["banco"]["campos_sql_fixo"])) {
                foreach ($this->config["banco"]["campos_sql_fixo"] as $campo => $retorno) {
                    $retorno = "$retorno; ?>";
                    $retorno = eval($retorno);
                    $arrayCampos[] = $campo . " = '" . $retorno . "'";
                }
            }

            if ($this->post[$this->config["banco"]["primaria"]]) {

                $this->sql = "select * from " . $this->config["banco"]["tabela"] . " where " . $this->config["banco"]["primaria"] . " = " . $this->post[$this->config["banco"]["primaria"]] . "";
                $linhaAntiga = $this->retornarLinha($this->sql);

                $this->sql = "update " . $this->config["banco"]["tabela"] . " set ";
                $this->sql .= implode(", ", $arrayCampos);
                $this->sql .= " where " . $this->config["banco"]["primaria"] . " = " . $this->post[$this->config["banco"]["primaria"]] . "";

                $salvar = mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));

                $this->sql = "select * from " . $this->config["banco"]["tabela"] . " where " . $this->config["banco"]["primaria"] . " = " . $this->post[$this->config["banco"]["primaria"]] . "";
                $linhaNova = $this->retornarLinha($this->sql);

                if (!$this->nao_monitara) {
                    $this->monitora_oque = 2;
                    $this->monitora_qual = $this->post[$this->config["banco"]["primaria"]];
                    $this->monitora_dadosantigos = $linhaAntiga;
                    $this->monitora_dadosnovos = $linhaNova;
                    $this->Monitora();
                }

            } else {
                $this->sql = "insert into " . $this->config["banco"]["tabela"] . " set ";
                $this->sql .= implode(", ", $arrayCampos);

                $salvar = mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));

                $this->monitora_qual = mysql_insert_id();
                if (!$this->nao_monitara) {
                    $this->monitora_oque = 1;
                    $this->Monitora();
                }

            }

            if (!$this->post[$this->config["banco"]["primaria"]])
                $this->post[$this->config["banco"]["primaria"]] = $this->monitora_qual;

            //VERIFICANDO SE FORMULÁRIO TEM ARRAY
            if ($formularioArray) {
                if (is_array($arrayCampoSecundario)) {
                    foreach ($arrayCampoSecundario as $nomeTabela => $arrayCampos) {
                        $array_comparacao = array();
                        $camposValores = NULL;
                        if (count($arrayCampos) > 0) {
                            foreach ($arrayCampos as $ind => $campos) {
                                //VERIFICANDO SE REGISTRO JÁ EXISTE NO BANCO
                                $this->sqlAux[0] = "select * from $nomeTabela where " . $campos . " and " . $this->config["banco"]["primaria"] . " = " . $this->post[$this->config["banco"]["primaria"]];
                                $query_existe = mysql_query($this->sqlAux[0]) or die(incluirLib("erro", $this->config, array("sql" => $this->sqlAux[0], "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                                $existe = @mysql_num_rows($query_existe);

                                //SE NÃO EXISTIR DA UM INSERT
                                if ($existe == 0) {
                                    $this->sqlAux[1] = "insert into " . $nomeTabela . " set " . $campos . ", " . $this->config["banco"]["primaria"] . " = " . $this->post[$this->config["banco"]["primaria"]];
                                    mysql_query($this->sqlAux[1]) or die(incluirLib("erro", $this->config, array("sql" => $this->sqlAux[1], "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));

                                }
                                $array_comparacao[] = $campos;
                            }

                            $camposValores = implode(' and ', $array_comparacao);
                            $camposValores = str_replace("=", "<>", $camposValores);
                        }

                        //DELETANDO DADOS QUE NÃO EXISTEM
                        $this->sqlAux[2] = "delete from $nomeTabela where " . $this->config["banco"]["primaria"] . " = '" . $this->post[$this->config["banco"]["primaria"]] . "'";
                        if ($camposValores) $this->sqlAux[2] .= " and $camposValores";
                        mysql_query($this->sqlAux[2]) or die(incluirLib("erro", $this->config, array("sql" => $this->sqlAux[2], "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                    }
                }
            }

            if ($salvar) {
                $this->retorno["sucesso"] = true;
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }

            if ($this->post[$this->config["banco"]["primaria"]]) {
                $this->retorno["id"] = $this->post[$this->config["banco"]["primaria"]];
            } else {
                $this->retorno["id"] = $this->monitora_qual;
            }

        }
        return $this->retorno;
    }

    function RemoverDados()
    {
        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //CRIANDO AS CONDIcoeES PARA ATUALIZAcaO DAS TABELAS, INFORMANDO SE ELA Ã‰ PARA SER EXCLUIDA OU ATULIZADA ALGUM CAMPO
        if (is_array($this->config["banco"]["relacionamentos"])) {
            foreach ($this->config["banco"]["relacionamentos"] as $tabela => $vinculo) {

                //RESETANDO VARIAVEL DOS DADOS A SEREM ATUALIZADOS
                $camposAtualizar = array();

                //VERIFICANDO OPÃ‡ÃƒO MARCADA E CRIANDO ARRAY COM NOME DO CAMPO E VALOR SELECIONADO
                if ($this->post[$tabela][$tabela] == "mudar_vinculo") {
                    foreach ($vinculo["tabela_vinculo"] as $ind => $campo) {
                        if ($this->post[$tabela][$campo["campo"]]) {
                            $camposAtualizar[] = $campo["campo"] . ' = ' . $this->post[$tabela][$campo["campo"]];
                        }

                        //BUSCANDO ERROS E ARMANZENDO VALIDAÃ‡Ã•ES EM ARRAY REGRAS
                        if (is_array($campo["validacao"])) {
                            foreach ($campo["validacao"] as $tipo => $mensagem) {
                                if (is_array($this->post[$tabela]))
                                    $regras[] = "$tipo,$tabela," . $campo["nome"] . ",$mensagem";
                                else
                                    $regras[] = "$tipo," . $campo["nome"] . ",$mensagem";
                            }
                        }
                    }
                } elseif ($this->post[$tabela][$tabela] == "sem_vinculo") {
                    foreach ($vinculo["tabela_vinculo"] as $ind => $campo) {
                        $camposAtualizar[] = $campo["campo"] . ' = NULL';
                    }
                } elseif ($this->post[$tabela][$tabela] == "remover") {
                    $camposAtualizar[] = "ativo = 'N'";
                } else {
                    if (is_array($vinculo)) {
                        foreach ($vinculo as $ind => $campo) {
                            $camposAtualizar[] = $campo["campo"] . ' = ' . $campo["valor"];
                        }
                    }
                }

                //ARMAZENANDO CAMPOS A SEREM ATUALIZADO EM UM ARRAY COM INDICE SENDO NONE DA TABEKA A SER ATUALIZADA
                $update[$tabela] = $camposAtualizar;
            }
        }

        //VERIFICANDO SE OPÃ‡ÃƒO REMOVER FOI MARCADA
        if (!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÃ�?RIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update " . $this->config["banco"]["tabela"] . " set ativo = 'N' where " . $this->config["banco"]["primaria"] . "=" . intval($this->post["remover"]);
            $remover = mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            //$remover = mysql_query($this->sql) or die(incluirLib("erro",$this->config,$this->sql,array("sql" => $this->sql, "mysql_error" => mysql_error())));
            //echo $this->sql."<br />";

            //ADICIONANDO ID A SER REMOVIDO NA VARIAVEL
            $idExclusao[] = intval($this->post["remover"]);

            #######################################################################################################################################
            # REMOVER CASCATA
            #######################################################################################################################################

            //VERIFICANDO SE EXISTE REMOÃ‡ÃƒO DO TIPO CASCATA
            if ($this->config["banco"]["deleta_castata"]) {

                //SE FOR UM ARRAY
                if (is_array($this->config["banco"]["deleta_castata"])) {

                    //VARRENDO ARRAY CASCATA
                    foreach ($this->config["banco"]["deleta_castata"] as $key => $tabela) {

                        //SE A TABELA FOR UM ARRAY
                        if (is_array($tabela)) {

                            //DELETANDO TODOS OS REGISTROS DO ARRAY
                            foreach ($tabela as $chave => $nomeTabela) {

                                $this->sql = "update $nomeTabela set ";
                                (is_array($update[$nomeTabela])) ? $this->sql .= implode(', ', $update[$nomeTabela]) : $this->sql .= " ativo = 'N' ";
                                $this->sql .= " where $key = " . implode(",", $idExclusao);
                                mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                                //mysql_query($this->sql) or die(incluirLib("erro",$this->config,$this->sql,array("sql" => $this->sql, "mysql_error" => mysql_error())));
                                //echo $this->sql."<br>";
                            }

                            //SE TABELA NAO FOR ARRAY
                        } else {

                            //SE EXITIR RESULTADO NO SELECT FEITO NO FINAL DO METODO
                            if (is_array($result)) {

                                //DELETANDO TODOS OS REGISTROS DO ARRAY
                                foreach ($result as $vlr) {
                                    $this->sql = "update $tabela set ";
                                    (is_array($update[$tabela])) ? $this->sql .= implode(', ', $update[$tabela]) : $this->sql .= " ativo = 'N' ";
                                    $this->sql .= " where $condicao = $vlr";
                                    mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                                    //mysql_query($this->sql) or die(incluirLib("erro",$this->config,$this->sql,array("sql" => $this->sql, "mysql_error" => mysql_error())));
                                    //echo $this->sql."<br>";
                                }

                                //REMOVENDO VARIAVEL DA CONDICÃƒO DO UPDATE
                                unset($condicao);

                                //REMOVENDO VARIAVEL DE RESULTADOS DO SELECT DE OBJETOS
                                unset($result);

                                //SE NÃƒO EXISTIR RESULTADO BUSCADO
                            } else {
                                $this->sql = "update $tabela set ";
                                (is_array($update[$tabela])) ? $this->sql .= implode(', ', $update[$tabela]) : $this->sql .= " ativo = 'N' ";
                                $this->sql .= " where " . $this->config["banco"]["primaria"] . " = " . implode(",", $idExclusao);
                                mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                                //mysql_query($this->sql) or die(incluirLib("erro",$this->config,$this->sql,array("sql" => $this->sql, "mysql_error" => mysql_error())));
                                //echo $this->sql."<br>";
                            }

                            //EFETUANDO BUSCA DE ITENS NA TABELA
                            $this->sql = "SELECT $key FROM $tabela WHERE " . $this->config["banco"]["primaria"] . " in (" . implode(",", $idExclusao) . ")";
                            $query = mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                            //$query = mysql_query($this->sql) or die(incluirLib("erro",$this->config,$this->sql,array("sql" => $this->sql, "mysql_error" => mysql_error())));

                            //REMOVENDO ID ANTERIOR
                            unset($idExclusao);

                            //CASO SEJA ENCONTRADO ALGUM RESULTADO EXCLUI A VARIAVEL ID
                            if (mysql_num_rows($query) == 0)
                                break;

                            //ARMAZENANDO RESULTADOS DO SELECT EM UM ARRAY, INFORMANDO QUAL OS ID A SER BUSCADO E ARMAZENDO A CONDICAO DO SELECT
                            while ($ln = mysql_fetch_assoc($query)) {
                                $result[] = $ln[$key];
                                $condicao = $key;
                                $idExclusao[] = $ln[$key];
                            }

                            //ATRIBUINDO NOVA CHAVE PRIMARIA
                            $this->config["banco"]["primaria"] = $key;
                        }
                    }

                    //SE DELETAR NAO FOR CASCATA
                } else {
                    $this->sql = "update " . $this->config["banco"]["deleta_castata"] . " set ";
                    (is_array($update[$this->config["banco"]["deleta_castata"]])) ? $this->sql .= implode(', ', $update[$this->config["banco"]["deleta_castata"]]) : $this->sql .= " ativo = 'N' ";
                    $this->sql .= " where " . $this->config["banco"]["primaria"] . "=" . intval($this->post["remover"]);
                    mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                    //mysql_query($this->sql) or die(incluirLib("erro",$this->config,$this->sql,array("sql" => $this->sql, "mysql_error" => mysql_error())));
                    //echo $this->sql."<br>";
                }
            }

            if ($remover) {
                $this->retorno["sucesso"] = true;
                $this->retorno["id"] = intval($this->post["remover"]);
                $this->monitora_oque = 3;
                $this->monitora_qual = intval($this->post["remover"]);
                if(!$this->nao_monitara){
                    $this->Monitora();
                }
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    function Processando()
    {
        $this->informacoes["msg"] = $this->pro_mensagem_idioma;
        $this->informacoes["ancora"] = $this->ancora;
        $this->informacoes["url"] = $this->url;
        incluirLib("processando", $this->config, $this->informacoes);
        exit();
    }

    function Upload($id, $file, $campoAux, $diminuir_largura = 0)
    {
        $arrayRetorno = array();
        $erros = array(1 => "error_arquivo_tamanho", //"O tamanho do arquivo é maior do que o tamanho maximo permitido.",
            2 => "error_arquivo_tamanho_html", //"O tamanho do arquivo é maior do que o tamanho maximo permitido (HTML).",
            3 => "error_arquivo_carregar", //"Não foi possivel carregar o arquivo totalmente.",
            4 => "error_arquivo_nenhuma", //"Nenhum arquivo foi enviado.",
            6 => "error_arquivo_pasta_temp", //"Pasta temporária não encontrada.",
            7 => "error_arquivo_gravar", //"Falha ao gravar o arquivo.",
            8 => "error_arquivo_extensao"); //"Extensão de arquivo não permitida.");
        if ($file["error"] == 0) {
            $this->sql = "select * from " . $this->config["banco"]["tabela"] . " where " . $this->config["banco"]["primaria"] . " = '" . $this->post[$this->config["banco"]["primaria"]] . "'";
            $linhaArquivo = $this->retornarLinha($this->sql);

            if ($linhaArquivo[$campoAux["nome"] . "_servidor"]) {
                @unlink($_SERVER["DOCUMENT_ROOT"] . "/storage/" . $campoAux["pasta"] . "/" . $linhaArquivo[$campoAux["nome"] . "_servidor"]);
            }

            $extensao = strtolower(strrchr($file["name"], "."));
            $nome_servidor = date("YmdHis") . "_" . uniqid() . $extensao;

            $arrayRetorno[] = $campoAux["nome"] . "_nome" . " = '" . $file["name"] . "'";
            $arrayRetorno[] = $campoAux["nome"] . "_servidor" . " = '" . $nome_servidor . "'";
            $arrayRetorno[] = $campoAux["nome"] . "_tipo" . " = '" . $file["type"] . "'";

            if ($diminuir_largura) {
                $tamanho = redimensionar($file, $diminuir_largura, $_SERVER["DOCUMENT_ROOT"] . "/storage/" . $campoAux["pasta"] . "/", $nome_servidor);
                $arrayRetorno[] = $campoAux["nome"] . "_tamanho" . " = '" . $tamanho . "'";
                return $arrayRetorno;
            } elseif (move_uploaded_file($file["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/storage/" . $campoAux["pasta"] . "/" . $nome_servidor)) {
                $arrayRetorno[] = $campoAux["nome"] . "_tamanho" . " = '" . $file["size"] . "'";
                return $arrayRetorno;
            }
        } else {

            return $erros[$file["error"]];
        }

    }

    function ExcluirArquivo($modulo, $pasta, $dados, $idioma)
    {
        if (unlink($_SERVER["DOCUMENT_ROOT"] . "/storage/" . $modulo . "_" . $pasta . "/" . $dados[$pasta . "_servidor"])) {
            $this->sql = "select * from " . $this->config["banco"]["tabela"] . " where " . $this->config["banco"]["primaria"] . " = " . $dados[$this->config["banco"]["primaria"]] . "";
            $linhaAntiga = $this->retornarLinha($this->sql);

            $this->sql = "UPDATE " . $this->config["banco"]["tabela"] . " SET
                                    " . $pasta . "_nome = NULL,
                                    " . $pasta . "_servidor = NULL,
                                    " . $pasta . "_tipo = NULL,
                                    " . $pasta . "_tamanho = NULL
                                where " . $this->config["banco"]["primaria"] . " = " . $dados[$this->config["banco"]["primaria"]] . "";
            mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            //mysql_query($this->sql) or die(incluirLib("erro",$this->config,$this->sql,array("sql" => $this->sql, "mysql_error" => mysql_error())));

            $this->sql = "select * from " . $this->config["banco"]["tabela"] . " where " . $this->config["banco"]["primaria"] . " = " . $dados[$this->config["banco"]["primaria"]] . "";
            $linhaNova = $this->retornarLinha($this->sql);

            $this->monitora_oque = 2;
            $this->monitora_qual = $dados[$this->config["banco"]["primaria"]];
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

            return $idioma["excluido_sucesso"];
        } else {
            return $idioma["excluido_falha"];
        }
    }

    function excluirArquivoNovo($campoBanco, $pasta, $dados, $idioma)
    {
        if (unlink($_SERVER['DOCUMENT_ROOT'] . '/storage/' . $pasta . '/' . $dados[$campoBanco . '_servidor'])) {
            $this->sql = "select * from " . $this->config["banco"]["tabela"] . " where " . $this->config["banco"]["primaria"] . " = " . $dados[$this->config["banco"]["primaria"]] . "";
            $linhaAntiga = $this->retornarLinha($this->sql);

            $this->sql = "UPDATE " . $this->config["banco"]["tabela"] . " SET
                                    " . $campoBanco . "_nome = NULL,
                                    " . $campoBanco . "_servidor = NULL,
                                    " . $campoBanco . "_tipo = NULL,
                                    " . $campoBanco . "_tamanho = NULL
                                where " . $this->config["banco"]["primaria"] . " = " . $dados[$this->config["banco"]["primaria"]] . "";
            mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));

            $this->sql = "select * from " . $this->config["banco"]["tabela"] . " where " . $this->config["banco"]["primaria"] . " = " . $dados[$this->config["banco"]["primaria"]] . "";
            $linhaNova = $this->retornarLinha($this->sql);

            $this->monitora_oque = 2;
            $this->monitora_qual = $dados[$this->config["banco"]["primaria"]];
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

            return $idioma["excluido_sucesso"];
        } else {
            return $idioma["excluido_falha"];
        }
    }

    function ValidarArquivo($file)
    {
        $erros = array(1 => "error_arquivo_tamanho", //"O tamanho do arquivo é maior do que o tamanho maximo permitido.",
            2 => "error_arquivo_tamanho_html", //"O tamanho do arquivo é maior do que o tamanho maximo permitido (HTML).",
            3 => "error_arquivo_carregar", //"Não foi possivel carregar o arquivo totalmente.",
            4 => "error_arquivo_nenhuma", //"Nenhum arquivo foi enviado.",
            6 => "error_arquivo_pasta_temp", //"Pasta temporária não encontrada.",
            7 => "error_arquivo_gravar", //"Falha ao gravar o arquivo.",
            8 => "error_arquivo_extensao"); //"Extensão de arquivo não permitida."
        $exetensao = explode("/", $file['type']);
        if ($file['size'] > $this->config['tamanho_upload_padrao']) {
            return $erros[1];
        } else if ($exetensao[0] == "image" && $file['size'] > $this->config['tamanho_upload_padrao']) {
            return $erros[1];
        } else {
            return NULL;
        }
    }

    function RetonaConteudoAjax($tabela, $campo, $valor, $parametro, $campoExibir, $selecionado, $textoSelect = NULL, $campos = '*', $condicoes = NULL)
    {
        $this->sql = "SELECT $campos FROM $tabela WHERE $campo = '" . $valor . "' $condicoes";
        $sqlAux = mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
        //$sqlAux = mysql_query($this->sql) or die(incluirLib("erro",$this->config,$this->sql,array("sql" => $this->sql, "mysql_error" => mysql_error())));
        echo '<option value="">' . $textoSelect . '</option>';
        while ($valorSelect = mysql_fetch_assoc($sqlAux)) {
            $selected = '';
            $campoOption = NULL;
            if ($selecionado == $valorSelect[$parametro])
                $selected = ' selected="selected"';

            if (is_array($campoExibir)) {
                $labelAux = "";
                $label = NULL;
                foreach ($campoExibir as $separador => $labels) {
                    foreach ($labels as $ind => $valor) {
                        if (is_array($valor)) {
                            $labelAux[] = stripslashes($valor[0][$valorSelect[$ind]]);
                        } else {
                            $labelAux[] = stripslashes($valorSelect[$valor]);
                        }
                    }
                    $campoOption .= implode($separador, $labelAux);
                }
            } else {
                $campoOption = stripslashes($valorSelect[$campoExibir]);
            }
            echo '<option value="' . $valorSelect[$parametro] . '" ' . $selected . '>' . $campoOption . '</option>';

        }
    }

    function RetornarJSON($nome_tabela, $opcao_selecionada, $campo_comparacao, $campos_tabela, $condicoes = NULL)
    {
        $this->sql = "SELECT $campos_tabela FROM $nome_tabela WHERE $campo_comparacao = '" . $opcao_selecionada . "' $condicoes";
        $res = mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
        $this->retorno = array();
        while ($row = mysql_fetch_assoc($res)) {
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }

    /*
        function RetornarJSON($nome_tabela, $opcao_selecionada, $campo_comparacao, $campos_tabela, $condicoes = NULL){
            $this->sql = "SELECT $campos_tabela FROM $nome_tabela WHERE $campo_comparacao = $opcao_selecionada $condicoes";
            $res = mysql_query($this->sql) or die(incluirLib("erro",$this->config,array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            //$res = mysql_query($this->sql) or die(incluirLib("erro",$this->config,$this->sql,array("sql" => $this->sql, "mysql_error" => mysql_error())));

            $campos_exibir = explode(",",$campos_tabela);

            while($row = mysql_fetch_assoc($res)){
                $array_campos = array();
                foreach($campos_exibir as $ind => $val){
                    $array_campos[trim($val)] = stripslashes($row[trim($val)]);
                }
                $this->retorno[] = $array_campos;
            }

            echo json_encode($this->retorno);
        }
    */

    function RetonaConteudoAjaxCheck($tabela, $campo, $valor, $parametro, $campoExibir, $selecionado, $tabela_join, $condicao_join, $campos = '*', $condicoes = NULL)
    {
        if ($selecionado && $tabela_join && $condicao_join) {
            $sql = "SELECT $parametro FROM $tabela_join WHERE $condicao_join = $selecionado ";
            $query = mysql_query($sql) or die(incluirLib("erro", $this->config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            //$query = mysql_query($sql) or die(incluirLib("erro",$this->config,$this->sql,array("sql" => $sql, "mysql_error" => mysql_error())));
            while ($linha = mysql_fetch_assoc($query)) {
                $selecionados[] = $linha[$parametro];
            }
        }

        if ($valor and $campo and $tabela) {
            $this->sql = "SELECT $campos FROM $tabela WHERE $campo = $valor $condicoes";
            $sqlAux = mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            //$sqlAux = mysql_query($this->sql) or die(incluirLib("erro",$this->config,$this->sql,array("sql" => $this->sql, "mysql_error" => mysql_error())));

            while ($valorSelect = mysql_fetch_assoc($sqlAux)) {
                $checked = '';
                if (is_array($selecionados) && in_array($valorSelect[$parametro], $selecionados))
                    $checked = ' checked="checked"';

                echo '<input name="' . $parametro . '[]" type="checkbox" value="' . $valorSelect[$parametro] . '" ' . $checked . ' /> ' . stripslashes($valorSelect[$campoExibir]) . "<br />";
            }
        }
    }

    function GerarLayoutCascata($dados, $idioma)
    {
        if (is_array($this->config["banco"]["relacionamentos"])) {
            foreach ($this->config["banco"]["relacionamentos"] as $colunaTabela => $campos) {
                echo '<div class="clearfix">';
                echo '<label for="' . $campo['id'] . '">' . $idioma[$campos["idioma"]] . '</label>';
                echo '<div class="input-prepend">';

                if (is_array($campos['opcoes'])) {
                    $array_key = array_keys($campos['opcoes']);
                    foreach ($campos['opcoes'] as $ind => $label) {
                        ($ind == $array_key[0]) ? $checked = 'checked="checked"' : $checked = NULL;
                        echo '<ul class="inputs-list">
                                    <li>
                                      <label>
                                        <input id="' . $colunaTabela . '" name="' . $colunaTabela . '[' . $colunaTabela . ']" value="' . $ind . '" type="radio" ' . $checked . '>
                                        <span>' . $idioma[$label] . '</span>
                                      </label>
                                    </li>
                                  </ul>';
                        if ($ind == "mudar_vinculo") {
                            if (is_array($campos['tabela_vinculo'])) {
                                foreach ($campos['tabela_vinculo'] as $ind => $campo) {
                                    echo '<div id="div_' . $campo["id"] . '" style="display:none;">';

                                    echo '<div class="clearfix">';
                                    echo '<label for="' . $campo["id"] . '">' . $idioma[$campo["idioma"]] . '</label>';
                                    echo '<div class="input">';
                                    echo '<select name="' . $colunaTabela . '[' . $campo["nome"] . ']" id="' . $campo["id"] . '" class="' . $campo["class"] . '" ' . $campo["evento"] . ' >';

                                    if (!$campo["label"]) {
                                        $campo["label"] = "nome";
                                    }

                                    if ($campo["valor"] != "vazio")
                                        Core::RetonaConteudoAjax($ind, 'ativo', 'S', $campo["campo"], $campo["label"], NULL, NULL, '*', ' AND ' . $campo["campo"] . '<>' . $dados[$this->config["banco"]["primaria"]]);
                                    else
                                        echo '<option value=""></option>';
                                    echo '</select>';
                                    echo ' * ';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }
                        }
                    }
                }
                echo '</div>';
                echo '</div>';
            }
        }

    }


    function GerarFicha($variavel, $dados, $idioma)
    {
        $config = $this->config[$variavel];

        echo '<table border="0" id="ficha">';

        foreach ($config as $fieldsetid => $fieldset) {
            echo '<tr><td colspan="2">';
            echo '<fieldset>';
            echo '<legend>' . $idioma[$fieldset["legendaidioma"]] . '</legend>';
            echo '</fieldset>';
            echo '</td></tr>';

            foreach ($fieldset["campos"] as $campoid => $campo) {

                if ($campo["valor_php"]) {
                    $valor = str_replace("%s", $dados[$campo["valor"]], $campo["valor_php"]);
                    $valor = "$valor; ?>";
                    $valor = eval($valor);
                } else {
                    $valor = $dados[$campo["valor"]];
                }

                if ($campo["array"]) {
                    if (is_array($campo["array"])) {
                        foreach ($campo["array"] as $ind => $campo_array) {
                            $arrayValores = $GLOBALS[$ind][$campo_array];
                        }
                    } else {
                        $arrayValores = $GLOBALS[$campo["array"]];
                        if (!$campo["ignoraridioma"])
                            $arrayValores = $arrayValores[$this->config["idioma_padrao"]];
                    }
                    foreach ($arrayValores as $ind => $valorSelect) {
                        if (isset($dados[$campo["valor"]]) && ($dados[$campo["valor"]] == $ind)) {
                            $valor = $valorSelect;
                            break;
                        }
                    }
                }

                $valorCampo = $valor;

                if (!$campo["texto"])
                    $valorCampo = stripslashes($this->SQLInjectionProtection($valorCampo));

                if ($campo["decimal"] && $valorCampo != "")
                    $valorCampo = "R$ " . number_format($valorCampo, 2, ',', '.');

                echo '<tr>';
                echo '<td class="label-ficha-titulo"><div class="clearfix">' . $idioma[$campo["nomeidioma"]] . '</div></td>';
                echo '<td class="label-ficha"><div class="input">' . $valorCampo . '</div></td>';
                echo '</tr>';
            }
        }
        echo '</table>';
    }

    function GerarFichas($variavel, $dadosArray, $idioma)
    {
        $config = $this->config[$variavel];

        echo '<table border="0" id="ficha">';
        foreach ($dadosArray as $ind => $dados) {

            foreach ($config as $fieldsetid => $fieldset) {
                echo '<tr><td colspan="2">';
                echo '<fieldset>';
                echo '<legend>' . $idioma[$fieldset["legendaidioma"]] . '</legend>';
                echo '</fieldset>';
                echo '</td></tr>';

                foreach ($fieldset["campos"] as $campoid => $campo) {

                    if ($campo["valor_php"]) {
                        $valor = str_replace("%s", $dados[$campo["valor"]], $campo["valor_php"]);
                        $valor = "$valor; ?>";
                        $valor = eval($valor);
                    } else {
                        $valor = $dados[$campo["valor"]];
                    }

                    if ($campo["array"]) {
                        if (is_array($campo["array"])) {
                            foreach ($campo["array"] as $ind => $campo_array) {
                                $arrayValores = $GLOBALS[$ind][$campo_array];
                            }
                        } else {
                            $arrayValores = $GLOBALS[$campo["array"]];
                            if (!$campo["ignoraridioma"])
                                $arrayValores = $arrayValores[$this->config["idioma_padrao"]];
                        }
                        foreach ($arrayValores as $ind => $valorSelect) {
                            if (isset($dados[$campo["valor"]]) && ($dados[$campo["valor"]] == $ind)) {
                                $valor = $valorSelect;
                                break;
                            }
                        }
                    }

                    $valorCampo = $valor;

                    if (!$campo["texto"])
                        $valorCampo = stripslashes($this->SQLInjectionProtection($valorCampo));

                    if ($campo["decimal"] && $valorCampo != "")
                        $valorCampo = "R$ " . number_format($valorCampo, 2, ',', '.');

                    echo '<tr>';
                    echo '<td class="label-ficha-titulo"><div class="clearfix">' . $idioma[$campo["nomeidioma"]] . '</div></td>';
                    echo '<td class="label-ficha"><div class="input">' . $valorCampo . '</div></td>';
                    echo '</tr>';
                }
            }
        }
        echo '</table>';
    }

    function enviarEmail($nomeDe, $emailDe, $assunto, $mensagem, $nomePara, $emailPara, $layout = "layout", $charset = 'iso-8859-1') {
        $headers = '';
        if (!$emailPara) {
            return false;
        }

        if ($this->reenviar) {
            $this->sql = "SELECT {$this->campos} FROM
                            emails_log
                        WHERE
                            idemail = ".$this->id;
            $email = $this->retornarLinha($this->sql);
            $nomeDe =  utf8_decode($email['de_nome']);
            $emailDe = $email['de_email'];
            $assunto =  utf8_decode($email['assunto']);
            $headers = $email['cabecalho'];
            $layout = $email['layout'];
            $nomePara = utf8_decode($email['para_nome']);
            if (! $this->email_reenvio) {
                $this->email_reenvio = $email['para_email'];
            }
            $emailPara = $this->email_reenvio;
            $mensagem = $email['mensagem'];
            $idLog = $email['idemail'];
        }

        $mail = new PHPMailer;
        $mail->setLanguage('br');

        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = $this->config['email_host'];
        $mail->Port = $this->config['email_port'];
        $mail->SMTPSecure = $this->config['email_secure'];

        $mail->CharSet = $charset;

        $mail->SMTPAuth = false;
        if($this->config['email_username'] && $this->config['email_password']) {
            $mail->SMTPAuth = true;
            $mail->Username = $this->config['email_username'];
            $mail->Password = $this->config['email_password'];
        }

        $mail->isHTML(true); // Set email format to HTML

        // Busca o layout do email
        $layoutAux = file(realpath(__DIR__) . "/../assets/email/".$layout.".html");
        $layoutHTML = "";
        foreach ($layoutAux as $linha => $valor) {
            $layoutHTML .= $valor;
        }

        $layoutHTML = str_replace("[[MENSAGEM]]", $mensagem, $layoutHTML);
        $layoutHTML = str_replace("[[URLSISTEMA]]", $this->config["urlSistema"], $layoutHTML);

        if (! $this->naoSalvarLogEmail) {

            //Adicionado mb_check_encoding para verificar codificação das variáveis e alterando se necessário
            $sql = "insert into
                        emails_log
                    set
                        data_cad = now(),
                        de_nome = '".mysql_real_escape_string((mb_check_encoding($nomeDe ,"UTF-8")) ? $nomeDe : utf8_encode($nomeDe))."',
                        de_email = '".$emailDe."',
                        para_nome = '".mysql_real_escape_string((mb_check_encoding($nomePara ,"UTF-8")) ? $nomePara : utf8_encode($nomePara))."',
                        para_email = '".$emailPara."',
                        assunto = '".mysql_real_escape_string((mb_check_encoding($assunto ,"UTF-8")) ? $assunto : utf8_encode($assunto))."',
                        layout = '".$layout."',
                        mensagem = '".mysql_real_escape_string((mb_check_encoding($mensagem ,"UTF-8")) ? $mensagem : utf8_encode($mensagem))."',
                        cabecalho = '".$headers."'";
            mysql_query($sql) or die(incluirLib("erro", $this->config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            $idLog = mysql_insert_id();
        }
        $layoutHTML .= "<img src='http://" . $this->config['urlSistemaFixa'] . "/api/set/img_confirmacao/" . $idLog . ".png'>";

        $mail->setFrom($this->config['email_naoresponda'], $nomeDe);
        $mail->addReplyTo($emailDe, $nomeDe);
        $mail->addAddress($emailPara, $nomePara);
        $mail->Subject =$assunto;
        $mail->Body    = $layoutHTML;

        if ($this->anexoEmail) {
            $mail->AddAttachment($this->anexoEmail);
        }

        $enviado = $mail->send();
        if (! $this->naoSalvarLogEmail) {
            if(!$enviado) {
                $sql = 'update emails_log set enviado = "N", erro = "'.$mail->ErrorInfo.'" where idemail = '.$idLog;
                mysql_query($sql) or die(incluirLib("erro", $this->config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            } elseif($this->reenviar && $idLog) {
                $sql = 'update emails_log set enviado = "S", erro = NULL where idemail = '.$idLog;
                mysql_query($sql) or die(incluirLib("erro", $this->config, array("sql" => $sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            }
        }

        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
        return $enviado;
    }

    function SetConfiguracao($indice, $valor)
    {
        $this->sql = "SELECT COUNT(*) AS total FROM configuracoes WHERE indice = '" . $indice . "'";
        $linha = $this->retornarLinha($this->sql);
        if ($linha["total"] > 0) {
            $this->sql = "UPDATE configuracoes SET valor = '" . $valor . "' WHERE indice = '" . $indice . "'";
            mysql_query($this->sql) or die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
            return true;
        } else {
            /*
                $this->sql = "INSERT INTO configuracoes (indice,valor) VALUES ('".$indice."','".$valor."')";
                mysql_query($this->sql) or die(incluirLib("erro",$this->config,array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));
                */
            return false;
        }
    }

    function GetConfiguracao($indice)
    {
        $this->sql = "SELECT valor FROM configuracoes WHERE indice = '" . $indice . "'";
        $linha = $this->retornarLinha($this->sql);
        return $linha["valor"];
    }

    protected function aplicarFiltrosBasicos($return = false)
    {
        $query = '';
        if (is_array($_GET['q'])) {
            foreach($_GET['q'] as $campo => $valor) {
                $campo = explode('|', $campo);

                $valor = str_replace('\'', '', $valor);
                if( $campo[1] == "c.tipo" ){
                    $valor = substr($valor,0,3);
                }
                if (($valor || $valor === '0') && $valor <> 'todos') {
                    if ($campo[0] == 1) {

                        if($campo[1] == 'p.ultimo_view'){
                            if($valor == "S")
                                $query .= ' AND ' . $campo[1] . ' IS NOT NULL';
                            else
                                $query .= ' AND ' . $campo[1] . ' IS NULL';
                        }else{
                            $query .= ' AND ' . $campo[1] . ' = "' . $valor . '" ';
                        }

                    } elseif ($campo[0] == 2) {
                        $busca = str_replace("\\'", "", $valor);
                        $busca = str_replace("\\", "", $busca);
                        $busca = explode(" ", $busca);

                        foreach ($busca as $ind => $buscar) {
                            $query .= " AND " . $campo[1] . " like '%" . urldecode($buscar) . "%' ";
                        }
                    } elseif ($campo[0] == 3) {
                        $query .= ' AND DATE_FORMAT(' . $campo[1] . ', "%d/%m/%Y") = "' . $valor . '"';
                    } elseif ($campo[0] == 4) {
                        $query .= ' AND DATE_FORMAT(' . $campo[1] . ', "%Y-%m-%d") <= "' . $valor . '"';
                    } elseif ($campo[0] == 5) {
                        $query .= ' AND DATE_FORMAT(' . $campo[1] . ', "%Y-%m-%d") >= "' . $valor . '"';
                    }
                }
            }
        }

        if ($return) {
            return $query;
        }

        $this->sql .= $query;
        return $this;
    }

    public function alterarConfigFormulario($config_formulario, $array_remover = array(), $array_renomear = array() , $array_remover_hidden = array())
    {
        foreach ($config_formulario as $ind_form => $parte_form) {
            foreach ($parte_form['campos'] as $ind_campo => $campo) {
                if (isset($array_renomear[$campo['nome']])) {
                    $config_formulario[$ind_form]['campos'][$ind_campo]['nome'] = $array_renomear[$campo['nome']];
                } else if (in_array($campo['nome'], $array_remover)) {
                    unset($config_formulario[$ind_form]['campos'][$ind_campo]);
                } elseif ( in_array($campo['nome'], $array_remover_hidden)) {
                    unset( $config_formulario[$ind_form]['campos'][$ind_campo]['input_hidden'] );
                }
            }
        }
        return $config_formulario;
    }

    public function inserirAtributos(array $arrayConfig, array $arrayAlterar, array $atributos)
    {
        foreach ($arrayConfig as $indForm => $parte_form) {
            foreach ($parte_form['campos'] as $indCampo => $campo) {
                if (in_array($campo['nome'], $arrayAlterar)) {
                    foreach ($atributos as $chave => $valor) {
                        $arrayConfig[$indForm]['campos'][$indCampo][$chave] = $valor;
                    }
                }
            }
        }

        return $arrayConfig;
    }

    public function removerListagem(array $arrayConfig, array $arrayRemover = null) {
        foreach ($arrayConfig as $indForm => $parte) {
            if (in_array($parte['id'], $arrayRemover)) {
                unset($arrayConfig[$indForm]);
            }
        }

        return $arrayConfig;
    }

    public function removerInconsistenciaSQL(&$str, $pattern){
        $arr = array();
        $ordens = explode(',', $str);
        foreach($ordens as $ordem)
            if(preg_match("/$pattern/sim", trim($ordem), $match))
                $arr[] = trim($match[0]);
        $str = implode(', ', $arr);
    }

    public function validarCampoOrdem($configuracao = 'listagem'){
        $camposDefinidos = array_column($this->config[$configuracao], 'coluna_sql');
        if(!empty($camposDefinidos) && !empty($this->ordem_campo)){
            if(!in_array($this->ordem_campo, $camposDefinidos))
                die(incluirLib("erro", $this->config, array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => 'O campo "'.$this->ordem_campo.'" não está definido.')));
                // if(!empty($this->config["banco"]["primaria"]))
                //     $this->ordem_campo = $this->config['banco']["primaria"];
        }
    }

}
