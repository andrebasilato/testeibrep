<?php
class Relatorio extends Core {

    function gerarRelatorio(){

        $this->sql = "SELECT
                        ".$this->campos."
                    FROM
                        fechamentos_caixa fc
                        INNER JOIN contas c ON fc.idfechamento = c.idfechamento
                        INNER JOIN contas_workflow cw ON c.idsituacao = cw.idsituacao
                        LEFT JOIN mantenedoras m ON c.idmantenedora = m.idmantenedora
                        LEFT JOIN sindicatos i ON c.idsindicato = i.idsindicato
                        LEFT JOIN escolas e ON c.idescola = e.idescola
                        LEFT JOIN produtos p ON c.idproduto = p.idproduto
                        LEFT JOIN categorias cat ON c.idcategoria = cat.idcategoria
                        LEFT JOIN matriculas mat ON c.idmatricula = mat.idmatricula
            LEFT JOIN pessoas pes_mat ON mat.idpessoa = pes_mat.idpessoa
            LEFT JOIN fornecedores forn ON c.idfornecedor = forn.idfornecedor
            LEFT JOIN pessoas pes ON c.idpessoa = pes.idpessoa
                        LEFT JOIN contas_correntes cc ON c.idconta_corrente = cc.idconta_corrente
                    WHERE
                        c.ativo = 'S'";

        if(is_array($_GET["q"])) {
            foreach($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|",$campo);
                $valor = str_replace("'","",$valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if(($valor || $valor === "0") && $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if($campo[0] == 1) {
                        $this->sql .= " and ".$campo[1]." = '".$valor."' ";
                    // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif($campo[0] == 2)  {
                        $this->sql .= " and ".$campo[1]." like '%".urldecode($valor)."%' ";
                    }elseif($campo[0] == 'de_ate') {

                        if ($valor == 'HOJ') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
                        } elseif ($valor == 'ONT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
                        } elseif ($valor == 'SET') {
                          $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
                        } elseif ($valor == 'QUI') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
                        } elseif ($valor == 'MAT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
                        } elseif ($valor == 'MPR') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
                        } elseif ($valor == 'MAN') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
                        } else {
                            if($_GET["de"]) {
                                $this->sql .= " and (c.data_vencimento >= '".formataData($_GET["de"],'en',0)." 00:00:00') ";
                            }

                            if($_GET["ate"]) {
                                $this->sql .= " and (c.data_vencimento <= '".formataData($_GET["ate"],'en',0)." 23:59:59') ";
                            }
                      }
                    }
                }
            }
        }

        if($_GET["situacao"]) {
            $this->sql .= " and (c.idsituacao = ".implode(" or c.idsituacao=", $_GET["situacao"]).") ";
        }

        $this->groupby = "c.idconta";
        $linhas = $this->retornarLinhas();

        return $linhas;

    }

        function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem") {

            // Buscando os idiomas do formulario
            include("idiomas/pt_br/index.php");
            /*echo '<table class="zebra-striped" id="sortTableExample">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Filtro</th>';
            echo '<th>Valor</th>';
            echo '</tr>';
            echo '</thead>';
            foreach($this->config["formulario"] as $ind => $fieldset){
                foreach($fieldset["campos"] as $ind => $campo){
                    if($campo["nome"]{0} == "q"){
                      $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
                      $campoAux = $_GET["q"][$campoAux];

                      if($campo["sql_filtro"]){
                          if($campo["sql_filtro"] == "array"){
                              $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
                              $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAux]];
                          } else {
                              $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
                              $seleciona = mysql_query($sql);
                              $linha = mysql_fetch_assoc($seleciona);
                              $campoAux = $linha[$campo["sql_filtro_label"]];
                          }
                      }

                    } elseif(is_array($_GET[$campo["nome"]])){

                      if($campo["array"]){
                          foreach($_GET[$campo["nome"]] as $ind => $val){
                             $_GET[$campo["nome"]][$ind] = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$val];
                          }
                      } elseif($campo["sql_filtro"]){
                          foreach($_GET[$campo["nome"]] as $ind => $val){
                             $sql = str_replace("%",$val,$campo["sql_filtro"]);
                             $seleciona = mysql_query($sql);
                             $linha = mysql_fetch_assoc($seleciona);
                             $_GET[$campo["nome"]][$ind] = $linha[$campo["sql_filtro_label"]];
                          }
                      }

                      $campoAux = implode($_GET[$campo["nome"]], ", ");
                    } else {
                      $campoAux = $_GET[$campo["nome"]];
                    }
                    if($campoAux <> ""){
                        echo '<tr>';
                        echo '<td><strong>'.$idioma[$campo["nomeidioma"]].'</strong></td>';
                        echo '<td>'.$campoAux.'</td>';
                        echo '</tr>';
                    }
                }
            }
            echo '</table><br>';*/


            echo '<table class="zebra-striped" id="sortTableExample">';
            echo '<thead>';
            echo '<tr>';
            foreach($this->config[$configuracao] as $ind => $valor){

                    $tamanho = "";
                    if($valor["tamanho"]) $tamanho = ' width="'.$valor["tamanho"].'"';

                    $th = '<th class="';
                    $th.= $class.' headerSortReloca" '.$tamanho.'>';
                    echo $th;

                    echo "<div class='headerNew'>".$idioma[$valor["variavel_lang"]]."</div>";

                    echo '</th>';

            }
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';

            if(count($dados) == 0){
                echo '<tr>';
                echo '<td colspan="'.count($this->config[$configuracao]).'">Nenhum informação foi encontrada.</td>';
                echo '</tr>';
            } else {
                $vencimentoAnterior = '';
                $subtotalValor = array();
                $total_valor = 0;

                $totalFormaPagamento['receita'] = array();
                $totalFormaPagamento['despesa'] = array();

                $totalConta['receita'] = array();
                $totalConta['despesa'] = array();

                foreach($dados as $i => $linha){
                    $subtotalValor[$linha['data_vencimento']] += $linha['valor'];
                    $total_valor += $linha['valor'];
                    $totalFormaPagamento[$linha['tipo']][$linha['forma_pagamento']] += $linha['valor'];

                    $totalConta[$linha['tipo']][$linha['idconta_corrente']]['nome'] = $linha['nome_conta_corrente'];
                    $totalConta[$linha['tipo']][$linha['idconta_corrente']]['conta'] = $linha['conta_corrente'];
                    $totalConta[$linha['tipo']][$linha['idconta_corrente']]['total'] += $linha['valor'];

                    if($vencimentoAnterior && $vencimentoAnterior != $linha['data_vencimento']) {
                        if($subtotalValor[$vencimentoAnterior] >= 0)
                            $color = "green";
                        else
                            $color = "red";

                        echo '<tr><td colspan="3" style="text-align:right;background-color:#C4C4C4;">SUBTOTAL:</td><td style="background-color:#C4C4C4;"><span style="color:gray; float:left">R$</span> <span style="color:'.$color.'; float:right"><strong>'.number_format($subtotalValor[$vencimentoAnterior],2,",",".").'</strong></span></td><td colspan="11" style="background-color:#C4C4C4;"></td></tr>';
                    }
                    $vencimentoAnterior = $linha['data_vencimento'];

                    echo '<tr>';
                    foreach($this->config[$configuracao] as $ind => $valor){
                        if($valor["tipo"] == "banco") {
                            echo '<td>'.stripslashes($linha[$valor["valor"]]).'</td>';
                        } elseif($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
                            $valor = $valor["valor"]." ?>";
                            $valor = eval($valor);
                            echo '<td>'.stripslashes($valor).'</td>';
                        } elseif($valor["tipo"] == "array") {
                            $variavel = $GLOBALS[$valor["array"]];
                            echo '<td>'.$variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]].'</td>';
                        } elseif($valor["busca_tipo"] != "hidden") {
                            echo '<td>'.stripslashes($valor["valor"]).'</td>';
                        }
                    }

                    echo '</tr>';
                }

                if($subtotalValor[$linha['data_vencimento']] >= 0)
                    $color = "green";
                else
                    $color = "red";

                echo '<tr><td colspan="3" style="text-align:right;background-color:#C4C4C4;">SUBTOTAL:</td><td style="background-color:#C4C4C4;"><span style="color:gray; float:left">R$</span> <span style="color:'.$color.'; float:right"><strong>'.number_format($subtotalValor[$linha['data_vencimento']],2,",",".").'</strong></span></td><td colspan="11" style="background-color:#C4C4C4;"></td></tr>';

            }

            if($total_valor >= 0)
                $color = "green";
            else
                $color = "red";

            echo '<tr><td colspan="3" style="text-align:right;background-color:#A4A4A4;">TOTAL:</td><td style="background-color:#A4A4A4;"><span style="color:gray; float:left">R$</span> <span style="color:'.$color.'; float:right"><strong>'.number_format($total_valor,2,",",".").'</strong></span></td><td colspan="11" style="background-color:#A4A4A4;"></td></tr>';

            echo '</tbody>';
            echo '</table>';

            echo '<br />';
            echo '<br />';
            echo '<table border="1" id="sortTableExample">';
                echo '<tr>';
                  echo '<td colspan="2" bgcolor="#E4E4E4" class="headerSortReloca" style="text-align:center;"><div class="headerNew"><strong>TÍTULOS RECEBIDOS</strong></div></td>';
                echo '</tr>';
                echo '<tr>';
                  echo '<td bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>Forma de pagamento</strong></div></td>';
                  echo '<td bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>Valor</strong></div></td>';
                echo '</tr>';
                $totalRecebidos = 0;
                foreach($totalFormaPagamento['receita'] as $formaPagamento => $total) {
                  $totalRecebidos += $total;
                  echo '<tr>';
                    echo '<td>'.$GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$formaPagamento].'</td>';
                    echo '<td>';
                      echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
                        echo '<tr>';
                          echo '<td style="color:#999">R$</td>';
                          echo '<td align="right" style="text-align:right;">'.number_format($total, 2, ',', '.').'</td>';
                        echo '</tr>';
                      echo '</table>';
                    echo '</td>';
                  echo '</tr>';
                }
                echo '<tr>';
                  echo '<td bgcolor="#F4F4F4">Total</td>';
                  echo '<td bgcolor="#F4F4F4">';
                    echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
                      echo '<tr>';
                        echo '<td style="color:#999">R$</td>';
                        echo '<td align="right" style="text-align:right;">'.number_format($totalRecebidos, 2, ',', '.').'</td>';
                      echo '</tr>';
                    echo '</table>';
                  echo '</td>';
                echo '</tr>';
                echo '<tr>';
                  echo '<td bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>Conta corrente</strong></div></td>';
                  echo '<td bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>Valor</strong></div></td>';
                echo '</tr>';
                foreach($totalConta['receita'] as $contaCorrente) {
                  echo '<tr>';
                    echo '<td>'.$contaCorrente['nome'].'</td>';
                    echo '<td>';
                      echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
                        echo '<tr>';
                          echo '<td style="color:#999">R$</td>';
                          echo '<td align="right" style="text-align:right;">'.number_format($contaCorrente['total'], 2, ',', '.').'</td>';
                        echo '</tr>';
                      echo '</table>';
                    echo '</td>';
                  echo '</tr>';
                }
            echo '</table>';
            echo '<br />';
            echo '<br />';
            echo '<table border="1" id="sortTableExample">';
                echo '<tr>';
                  echo '<td colspan="2" bgcolor="#E4E4E4" class="headerSortReloca" style="text-align:center;"><div class="headerNew"><strong>TÍTULOS PAGOS</strong></div></td>';
                echo '</tr>';
                echo '<tr>';
                  echo '<td bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>Forma de pagamento</strong></div></td>';
                  echo '<td bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>Valor</strong></div></td>';
                echo '</tr>';
                $totalPagos = 0;
                foreach($totalFormaPagamento['despesa'] as $formaPagamento => $total) {
                  $totalPagos += $total;
                  echo '<tr>';
                    echo '<td>'.$GLOBALS['forma_pagamento_conta'][$GLOBALS['config']['idioma_padrao']][$formaPagamento].'</td>';
                    echo '<td>';
                      echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
                        echo '<tr>';
                          echo '<td style="color:#999">R$</td>';
                          echo '<td align="right" style="text-align:right;">'.number_format(abs($total), 2, ',', '.').'</td>';
                        echo '</tr>';
                      echo '</table>';
                    echo '</td>';
                  echo '</tr>';
                }
                echo '<tr>';
                  echo '<td bgcolor="#F4F4F4">Total</td>';
                  echo '<td bgcolor="#F4F4F4">';
                    echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
                      echo '<tr>';
                        echo '<td style="color:#999">R$</td>';
                        echo '<td align="right" style="text-align:right;">'.number_format(abs($totalPagos), 2, ',', '.').'</td>';
                      echo '</tr>';
                    echo '</table>';
                  echo '</td>';
                echo '</tr>';
                echo '<tr>';
                  echo '<td bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>Conta corrente</strong></div></td>';
                  echo '<td bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>Valor</strong></div></td>';
                echo '</tr>';
                foreach($totalConta['despesa'] as $contaCorrente) {
                  echo '<tr>';
                    echo '<td>'.$contaCorrente['nome'].' ('.$contaCorrente['conta'].')</td>';
                    echo '<td>';
                      echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
                        echo '<tr>';
                          echo '<td style="color:#999">R$</td>';
                          echo '<td align="right" style="text-align:right;">'.number_format(abs($contaCorrente['total']), 2, ',', '.').'</td>';
                        echo '</tr>';
                      echo '</table>';
                    echo '</td>';
                  echo '</tr>';
                }
            echo '</table>';

      $this->atualizarValorTotalFechamentoCaixa($_GET['q']['1|fc.idfechamento'], $totalRecebidos, $totalPagos);
        }

    function atualizarValorTotalFechamentoCaixa($idfechamento, $totalRecebidos, $totalPagos) {
      $totalPagos = abs($totalPagos);
      if((int) $idfechamento) {
        $this->sql = 'update fechamentos_caixa set credito_valor = '.$totalRecebidos.', debito_valor = '.$totalPagos.' where idfechamento = '.(int) $idfechamento;
        $this->executaSql($this->sql);
      }
    }
}

?>