<?php
class Relatorio extends Core {
	function gerarRelatorio() {
		$this->sql = "SELECT
                        " . $this->campos . "
                    FROM 
                        fechamentos_caixa fc
                        INNER JOIN contas c ON fc.idfechamento = c.idfechamento         
                        INNER JOIN sindicatos i ON c.idsindicato = i.idsindicato            
                        INNER JOIN contas_correntes cc ON c.idconta_corrente = cc.idconta_corrente 
                        INNER JOIN bancos b  on b.idbanco = cc.idbanco
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
                    }  elseif($campo[0] == 'de_ate_vencimento' || $campo[0] == 'de_ate_pagamento') {
                        if($valor == 'HOJ') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
                        } elseif($valor == 'ONT') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
                        } else if($valor == 'SET') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
                        } elseif($valor == 'QUI') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
                        } else if($valor == 'MAT') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
                        } else if($valor == 'MPR') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
                        } else if($valor == 'MAN') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
                        } else if ($valor == 'PER') {                        	
                        	if($_GET['q']['de_ate_vencimento|tipo_data_vencimento_filtro|c.data_vencimento'] == 'PER' && (!$_GET["de_data_vencimento"] || !$_GET["ate_data_vencimento"]) ){
								unset($_GET['q']['de_ate_vencimento|tipo_data_vencimento_filtro|c.data_vencimento']);
							}else
								if($_GET["de_data_vencimento"]) {
									$this->sql .= " and date_format(c.data_vencimento,'%Y-%m-%d') >= '".formataData($_GET["de_data_vencimento"],'en',0)."'";
					  			}
					  			if($_GET["ate_data_vencimento"]) {
									$this->sql .= " and date_format(c.data_vencimento,'%Y-%m-%d') <= '".formataData($_GET["ate_data_vencimento"],'en',0)."'";
					  			}
					  		}

					  		if($_GET['q']['de_ate_pagamento|tipo_data_pagamento_filtro|c.data_pagamento'] == 'PER' && (!$_GET["de_pagamento"] || !$_GET["ate_pagamento"]) ){
								unset($_GET['q']['de_ate_pagamento|tipo_data_pagamento_filtro|c.data_pagamento']);
							}else{
						  		if($_GET["de_data_pagamento"]) {
									$this->sql .= " and date_format(c.data_pagamento,'%Y-%m-%d') >= '".formataData($_GET["de_data_pagamento"],'en',0)."'";
						  		}
						  		if($_GET["ate_data_pagamento"]) {
									$this->sql .= " and date_format(c.data_pagamento,'%Y-%m-%d') <= '".formataData($_GET["ate_data_pagamento"],'en',0)."'";
						  		}
					  	}         	               	                        
                    }                        
               	}                 
            }
        }  

        $this->sql .= " group by cc.conta, c.tipo, c.forma_pagamento";	        
		$linhas = $this->retornarLinhas();			
		$dados = array ();		
		foreach ( $linhas as $ind => $val ) {						
			
			// Dados para o preenchimento do xls			
			$dados['dados'][$val ['conta'].'-'.$val ['conta_dig']]['tipo'] = $val ['tipo'];
			$dados['dados'][$val ['conta'].'-'.$val ['conta_dig']]['conta'] = $val ['conta'].'-'.$val ['conta_dig'];
			$dados['dados'][$val ['conta'].'-'.$val ['conta_dig']]['sindicato'] = $val ['sindicato']; 
			$dados['dados'][$val ['conta'].'-'.$val ['conta_dig']]['data_cadastro'] =  $val['data_cad'];
			$dados['dados'][$val ['conta'].'-'.$val ['conta_dig']]['data_pagamento'] =  $val['data_pagamento'];
			$dados['dados'][$val ['conta'].'-'.$val ['conta_dig']]['data_vencimento'] = $val['data_vencimento'];
			$dados['dados'][$val ['conta'].'-'.$val ['conta_dig']]['forma_pagamento'] = $val['forma_pagamento'];
			
			// Dados html
			$dados['dados'][$val ['conta'].'-'.$val ['conta_dig']]['valor'] = $val['valor'];
			$dados [$val ['tipo']][$val ['conta'].'-'.$val ['conta_dig']] [$val ['forma_pagamento_receber']] = $val ['valor'];
							
		}					
		return $dados;
	}
	
	function GerarTabela($dados, $q = null, $idioma, $configuracao = "listagem") {
		
		// Buscando os idiomas do formulario
		include ("idiomas/pt_br/index.php");
		
		// Buscando os idiomas do formulario
		echo '<table class="zebra-striped" id="sortTableExample">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Filtro</th>';
		echo '<th>Valor</th>';
		echo '</tr>';
		echo '</thead>';
		foreach ( $this->config ["formulario"] as $ind => $fieldset ) {
			// print_r2($fieldset["campos"]); exit();
			foreach ( $fieldset ["campos"] as $ind => $campo ) {
				if ($campo ["nome"] [0] == "q") {
					$campoAux = str_replace ( array (
							"q[",
							"]" 
					), "", $campo ["nome"] );
					$campoAux = $_GET ["q"] [$campoAux];
					if ($campo ["sql_filtro"] && $campoAux) {
						if ($campo ["sql_filtro"] == "array") {
							$campoAux = $GLOBALS [$campo ["sql_filtro_label"]] [$GLOBALS ["config"] ["idioma_padrao"]] [$campoAux];
						} else {
							$sql = str_replace ( "%", $campoAux, $campo ["sql_filtro"] );
							$linha = $this->retornarLinha ( $sql );
							$campoAux = $linha [$campo ["sql_filtro_label"]];
						}
					}
				} elseif (is_array ( $_GET [$campo ["nome"]] )) {
					if ($campo ["array"]) {
						foreach ( $_GET [$campo ["nome"]] as $ind => $val ) {
							$_GET [$campo ["nome"]] [$ind] = $GLOBALS [$campo ["array"]] [$GLOBALS ["config"] ["idioma_padrao"]] [$val];
						}
					} elseif ($campo ["sql_filtro"]) {
						foreach ( $_GET [$campo ["nome"]] as $ind => $val ) {
							$sql = str_replace ( "%", $val, $campo ["sql_filtro"] );
							$linha = $this->retornarLinha ( $sql );
							$_GET [$campo ["nome"]] [$ind] = $linha [$campo ["sql_filtro_label"]];
						}
					}
					
					$campoAux = implode ( $_GET [$campo ["nome"]], ", " );
				} elseif ($_GET [$campo ["nome"]] && $campo ["array"]) {
					$campoAux = $GLOBALS [$campo ["array"]] [$GLOBALS ["config"] ["idioma_padrao"]] [$_GET [$campo ["nome"]]];
				} elseif ($_GET [$campo ["nome"]] && $campo ["sql_filtro"]) {
					$sql = str_replace ( "%", $_GET [$campo ["nome"]], $campo ["sql_filtro"] );
					$linha = $this->retornarLinha ( $sql );
					$campoAux = $linha [$campo ["sql_filtro_label"]];
				} else {
					$campoAux = $_GET [$campo ["nome"]];
				}
				if ($campoAux != "") {
					echo '<tr>';
					echo '<td><strong>' . $idioma [$campo ["nomeidioma"]] . '</strong></td>';
					echo '<td>' . $campoAux . '</td>';
					echo '</tr>';
				}
			}
		}
		echo '</table><br>';
		
		echo '<table class="zebra-striped" id="sortTableExample">';
		echo '<thead>';
		echo '<tr>';
		foreach ( $this->config [$configuracao] as $ind => $valor ) {
			$tamanho = "";
			if ($valor ["tamanho"])
				$tamanho = ' width="' . $valor ["tamanho"] . '"';
			$th = '<th class="';
			$th .= $class . ' headerSortReloca" ' . $tamanho . '>';
			echo $th;
			echo "<div class='headerNew'>" . $idioma [$valor ["variavel_lang"]] . "</div>";
			echo '</th>';
		}
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		echo '<tr>';
		
		if (count ( $dados ) == 0) {
			echo '<tr>';
			echo '<td colspan="' . count ( $this->config [$configuracao] ) . '">Nenhuma informação foi encontrada.</td>';
			echo '</tr>';
		}
		
		echo '</tbody>';
		echo '</table>';
		
		if (isset ( $dados ['receita'] )) {
			
			echo '<table border="1" id="sortTableExample">';
			echo '<tr>';
			echo '<td colspan="' . (count ( $dados ['receita'] ) + 2) . '" bgcolor="#E4E4E4" class="headerSortReloca" style="text-align:center;"><div class="headerNew"><strong>A RECEBER</strong></div></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>FORMA DE PAGAMENTO</strong></div></td>';			
			
			// Total
			echo '<td align="right" style="text-align:center;" bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>TOTAL</strong></div></td>';
			foreach ( $dados ['receita'] as $conta => $contaCorrente ) {
				echo '<td align="right" style="text-align:center;" bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>' . $conta . '</strong></div></td>';
			}
			
			// Capturando formas de pagamento
			$formPagamentos = array ();
			foreach ( $dados ['receita'] as $conta => $contaCorrente ) {
				foreach ( $contaCorrente as $formaPagamento => $total ) {
					$formPagamentos [$formaPagamento] = $total;
				}
			}
			
			$totalGeral = 0;
			foreach ( $formPagamentos as $fpag => $value ) {
				echo '<tr>';
				echo '<td><strong>' . $GLOBALS ['forma_pagamento_conta'] [$GLOBALS ['config'] ['idioma_padrao']] [$fpag] . '</strong></td>';
				
				$r = 0;
				foreach ( $dados ['receita'] as $cc ) {
					foreach ( $cc as $formaPagamento => $total ) {
						if ($formaPagamento == $fpag)
							$r += $total;
					}
				}
				$totalGeral += $r;
				
				echo '<td>';
				echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
				echo '<tr>';
				echo '<td style="color:#999">R$</td>';
				echo '<td align="right" style="text-align:right;">' . number_format ( $r, 2, ',', '.' ) . '</td>';
				echo '</tr>';
				echo '</table>';
				echo '</td>';
				
				foreach ( $dados ['receita'] as $conta => $contaCorrente ) {
					if ($contaCorrente [$fpag]) {
						echo '<td>';
						echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
						echo '<tr>';
						echo '<td style="color:#999">R$</td>';
						echo '<td align="right" style="text-align:right;">' . number_format ( $contaCorrente [$fpag], 2, ',', '.' ) . '</td>';
						echo '</tr>';
						echo '</table>';
						echo '</td>';
					} else {
						echo '<td align="right" style="color:#999;text-align:center;">-</td>';
					}
				}
				echo '</tr>';
			}
			
			echo '</tr>';
			
			echo '<tr>';
			echo '<td bgcolor="#F4F4F4"><strong>TOTAL:</strong></td>';
			// Total de pagamentos geral
			echo '<td bgcolor="#F4F4F4">';
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
			echo '<tr>';
			echo '<td style="color:#999">R$</td>';
			echo '<td align="right" style="text-align:right;"><strong>' . number_format ( $totalGeral, 2, ',', '.' ) . '</strong></td>';
			echo '</tr>';
			echo '</table>';
			echo '</td>';
			
			foreach ( $dados ['receita'] as $contaCorrente ) {				
				echo '<td bgcolor="#F4F4F4">';
				echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
				echo '<tr>';
				echo '<td style="color:#999">R$</td>';
				// Calculando total por forma de pagamento
				$r = 0;
				foreach ( $contaCorrente as $formaPagamento => $total ) {
					$r += $total;
				}
				echo '<td align="right" style="text-align:right;"><strong>' . number_format ( $r, 2, ',', '.' ) . '</strong></td>';
				echo '</tr>';
				echo '</table>';
				echo '</td>';
			}
			echo '</tr>';
			echo '</table><br/>';
		}
		
		// Títulos a receber
		if (isset ( $dados ['despesa'] )) {
			echo '<table border="1" id="sortTableExample">';
			echo '<tr>';
			echo '<td colspan="' . (count ( $dados ['despesa'] ) + 2) . '" bgcolor="#E4E4E4" class="headerSortReloca" style="text-align:center;"><div class="headerNew"><strong>A PAGAR</strong></div></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>FORMA DE PAGAMENTO</strong></div></td>';
						
			// Total
			echo '<td align="right" style="text-align:center;" bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>TOTAL</strong></div></td>';			
			foreach ( $dados ['despesa'] as $conta => $contaCorrente ) {				
				echo '<td align="right" style="text-align:center;" bgcolor="#E4E4E4" class=" headerSortReloca"><div class="headerNew"><strong>' . $conta . '</strong></div></td>';
			}
			
			// Capturando formas de pagamento
			$formPagamentos = array ();
			foreach ( $dados ['despesa'] as $conta => $contaCorrente ) {
				foreach ( $contaCorrente as $formaPagamento => $total ) {
					$formPagamentos [$formaPagamento] = $total;
				}
			}
			
			$totalGeral = 0;
			foreach ( $formPagamentos as $fpag => $value ) {
				echo '<tr>';
				echo '<td><strong>' . $GLOBALS ['forma_pagamento_conta'] [$GLOBALS ['config'] ['idioma_padrao']] [$fpag] . '</strong></td>';
				
				$r = 0;
				foreach ( $dados ['despesa'] as $cc ) {
					foreach ( $cc as $formaPagamento => $total ) {
						if ($formaPagamento == $fpag)
							$r += $total;
					}
				}
				$totalGeral += $r;
				
				echo '<td>';
				echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
				echo '<tr>';
				echo '<td style="color:#999">R$</td>';
				echo '<td align="right" style="text-align:right;">' . number_format ( $r, 3, ',', '.' ) . '</td>';
				echo '</tr>';
				echo '</table>';
				echo '</td>';
				
				foreach ( $dados ['despesa'] as $conta => $contaCorrente ) {
					if ($contaCorrente [$fpag]) {
						echo '<td>';
						echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
						echo '<tr>';
						echo '<td style="color:#999">R$</td>';
						echo '<td align="right" style="text-align:right;">' . number_format ( ($contaCorrente [$fpag]*-1), 2, ',', '.' ) . '</td>';
						echo '</tr>';
						echo '</table>';
						echo '</td>';
					} else {
						echo '<td align="right" style="color:#999;text-align:center;">-</td>';
					}
				}
				echo '</tr>';
			}
			
			echo '</tr>';
			
			echo '<tr>';
			echo '<td bgcolor="#F4F4F4"><strong>TOTAL</strong></td>';
			// Total de pagamentos geral
			echo '<td bgcolor="#F4F4F4">';
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
			echo '<tr>';
			echo '<td style="color:#999">R$</td>';
			echo '<td align="right" style="text-align:right;"><strong>' . number_format ( ($totalGeral*-1), 2, ',', '.' ) . '</strong></td>';
			echo '</tr>';
			echo '</table>';
			echo '</td>';
			
			foreach ( $dados ['despesa'] as $contaCorrente ) {				
				echo '<td bgcolor="#F4F4F4">';
				echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
				echo '<tr>';
				echo '<td style="color:#999">R$</td>';
				// Calculando total
				$r = 0;
				foreach ( $contaCorrente as $formaPagamento => $total ) {
					$r += $total;
				}
				echo '<td align="right" style="text-align:right;"><strong>' . number_format ( ($r*-1), 2, ',', '.' ) . '</strong></td>';
				echo '</tr>';
				echo '</table>';
				echo '</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
		
		$this->atualizarValorTotalFechamentoCaixa ( $_GET ['q'] ['1|fc.idfechamento'], $totalRecebidos, $totalPagos );
	}

	function atualizarValorTotalFechamentoCaixa($idfechamento, $totalRecebidos, $totalPagos) {
		$totalPagos = abs ( $totalPagos );
		if (( int ) $idfechamento) {
			$this->sql = 'update fechamentos_caixa set credito_valor = ' . $totalRecebidos . ', debito_valor = ' . $totalPagos . ' where idfechamento = ' . ( int ) $idfechamento;
			$this->executaSql ( $this->sql );
		}
	}
	
	function RetornarContasCorrentesBanco($idbanco) {
		$this->sql = "select idconta_corrente, nome from contas_correntes where idbanco = " . $idbanco . " and ativo = 'S'";
		$this->limite = - 1;
		$this->ordem = 'asc';
		$this->ordem_campo = 'nome';
		
		$contasCorrentes = $this->retornarLinhas();
		
		echo json_encode ( $contasCorrentes );
	}
}

?>