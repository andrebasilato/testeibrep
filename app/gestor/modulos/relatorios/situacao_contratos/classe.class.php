<?
class Relatorio extends Core {
	
	function gerarRelatorio() {
		
		//print_r2($_GET); exit();

		$this->sql = "SELECT
						  " . $this->campos . "
					  from matriculas m 	
						left join matriculas_contratos mc on mc.idmatricula = m.idmatricula
						inner join pessoas p on p.idpessoa = m.idpessoa
					    inner join matriculas_workflow mw on mw.idsituacao = m.idsituacao
						inner join sindicatos i on i.idsindicato = m.idsindicato
						inner join contratos_sindicatos ci on ci.idsindicato = i.idsindicato
						inner join contratos c on c.idcontrato = ci.idcontrato
						inner join vendedores v on v.idvendedor = m.idvendedor
						left join cursos on cursos.idcurso = m.idcurso ";
		
		$this->sql .= " WHERE m.ativo = 'S' ";				

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
                    }  elseif($campo[0] == 'de_ate_validado' || $campo[0] == 'de_ate_aprovado') {
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
         	               	if($_GET['q']['de_ate_validado|tipo_data_validado_filtro|mc.validado'] == 'PER' && (!$_GET["de_validacao"] || !$_GET["ate_validacao"]) ){
								unset($_GET['q']['de_ate_validado|tipo_data_validado_filtro|mc.validado']);
							}else{
								if( $_GET["de_validacao"] ) {
                        			$this->sql .= " AND (mc.assinado >= '".formataData($_GET["de_validacao"],'en',0)." 00:00:00') ";	
                        		}                           
                        	
                        		if( $_GET["ate_validacao"] ){
                        			$this->sql .= " AND (mc.assinado <= '".formataData($_GET["ate_validacao"],'en',0)." 23:59:59') ";	
                        		}     
							}
		
							if($_GET['q']['de_ate_aprovado|tipo_data_aprovado_filtro|data_aprovacao_comercial'] == 'PER' && (!$_GET["de_aprovacao"] || !$_GET["ate_aprovacao"]) ){
								unset($_GET['q']['de_ate_aprovado|tipo_data_aprovado_filtro|data_aprovacao_comercial']);
							}else{
								if( $_GET["de_aprovacao"] ) {
                        			$this->sql .= " AND (mc.validado >= '".formataData($_GET["de_aprovacao"],'en',0)." 00:00:00') ";	
                        		}                           
                        	
                        		if( $_GET["ate_aprovacao"] ){
                        			$this->sql .= " AND (mc.validado <= '".formataData($_GET["ate_aprovacao"],'en',0)." 23:59:59') ";	
                        		}    
							}               
                        }
                    }
                } 
            }
        }  

        if($_GET["validado_aluno"][0] == 'N' && (!(isset($_GET["validado_aluno"][1])))) {         	
			$this->sql .= " and mc.assinado is null ";
		}	
		
        if($_GET["aprovado_comercial"][0] == 'N' && (!(isset($_GET["aprovado_comercial"][1])))) {         	
			$this->sql .= " and mc.validado is null ";
		}			
		
		if($_GET["situacao"]) {
			$this->sql .= " and (m.idsituacao = ".implode(" or m.idsituacao=", $_GET["situacao"]).") ";
		}

		if(!empty((int)$_GET["dias_matricula"])){
			$this->sql .= " and DATEDIFF(curdate(), m.data_matricula) = ". $_GET["dias_matricula"];
		}

		//print_r2($_GET); exit();
		
		$this->sql .= " group by m.idmatricula, cursos.nome, p.nome, p.email, v.nome, m.data_matricula, DATEDIFF(curdate(),m.data_matricula), mc.validado ";		
		$this->groupby = "m.idmatricula";

		//echo $this->sql; exit;
		$linhas = $this->retornarLinhas();		

		return $linhas;
	}
	
	function GerarTabela($dados, $q = null, $idioma, $configuracao = "listagem") {
		
		// Buscando os idiomas do formulario
		include ("idiomas/pt_br/index.php");
		echo '<table class="zebra-striped" id="sortTableExample">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Filtro</th>';
		echo '<th>Valor</th>';
		echo '</tr>';
		echo '</thead>';		
		foreach ( $this->config ["formulario"] as $ind => $fieldset ) {
			//print_r2($fieldset["campos"]); exit();
			foreach ( $fieldset ["campos"] as $ind => $campo ) {
				if ($campo ["nome"] {0} == "q") {
					$campoAux = str_replace ( array (
							"q[",
							"]" 
					), "", $campo ["nome"] );
					$campoAux = $_GET ["q"] [$campoAux];
					
					if ($campo ["sql_filtro"]) {
						if ($campo ["sql_filtro"] == "array") {
							$campoAux = str_replace ( array (
									"q[",
									"]" 
							), "", $campo ["nome"] );
							$campoAux = $GLOBALS [$campo ["sql_filtro_label"]] [$GLOBALS ["config"] ["idioma_padrao"]] [$_GET ["q"] [$campoAux]];
						} else {
							$sql = str_replace ( "%", $campoAux, $campo ["sql_filtro"] );
							$seleciona = mysql_query ( $sql );
							$linha = mysql_fetch_assoc ( $seleciona );
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
							$seleciona = mysql_query ( $sql );
							$linha = mysql_fetch_assoc ( $seleciona );
							$_GET [$campo ["nome"]] [$ind] = $linha [$campo ["sql_filtro_label"]];
						}
					}
					
					$campoAux = implode ( $_GET [$campo ["nome"]], ", " );
				} elseif ($campo ["sql_filtro"]) {
					$sql = str_replace ( "%", $_GET [$campo ["nome"]], $campo ["sql_filtro"] );
					$seleciona = mysql_query ( $sql );
					$linha = mysql_fetch_assoc ( $seleciona );
					$_GET [$campo ["nome"]] = $linha [$campo ["sql_filtro_label"]];
					
					$campoAux = $_GET [$campo ["nome"]];
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
			echo '<td colspan="' . count ( $this->config [$configuracao] ) . '">Nenhum informação foi encontrada.</td>';
			echo '</tr>';
		} else {
			//print_r2( $dados ); exit();
			echo '<tr>';				
				echo '<td><strong>Número do Contrato:</strong></td>';
				echo '<td><strong>Matrícula</strong></td>';
				echo '<td><strong>Curso</strong></td>';
				echo '<td><strong>Aluno(a)</strong></td>';
				echo '<td><strong>Email</strong></td>';
				echo '<td><strong>Vendedor</strong></td>';
				echo '<td><strong>Dias da Matrícula</strong></td>';
				echo '<td><strong>Situação da Matrícula</strong></td>';
				echo '<td><strong>Validado pelo Aluno</strong></td>';			
				echo '<td><strong>Data da validação</strong></td>';				
				echo '<td><strong>Aprovado pelo Comercial</strong></td>';			
				echo '<td><strong>Data da Aprovação</strong></td>';
				echo '<td><strong>Validado pelo Devedor Solidário</strong></td>';				
			echo '</tr>';
			


			foreach ( $dados as $i => $linha ) {
				echo '<tr>';					
					echo '<td>'. $linha['contrato'] . '</td>';
					echo '<td>'. $linha['idmatricula'] . '</td>';
					echo '<td>'. $linha['curso'] . '</td>';			
					echo '<td>'. $linha['nome_aluno'] . '</td>';
					echo '<td>'. $linha['email_aluno'] . '</td>';
					echo '<td>'. $linha['vendedor'] . '</td>';					
					echo '<td>'. $linha['dias_matricula'] . '</td>';
					echo '<td>'. $linha['situacao'] . '</td>';
					echo '<td>'. (isset($linha['assinado']) ? 'Sim' : 'Não') . '</td>';
					echo '<td>'. $linha['assinado'] . '</td>';
					echo '<td>'. (isset($linha['validado']) ? 'Sim' : 'Não') . '</td>';
					echo '<td>'. $linha['validado'] . '</td>';
					echo '<td>'. (isset($linha['devedor']) ? 'Sim' : 'Não') . '</td>';
				echo '</tr>';
			}					
		}		
		echo '</tbody>';
		echo '</table>';
	}
}

?>