<?

class Relatorio extends Core {

	function gerarRelatorio(){
		
		$this->sql = "SELECT
						  ".$this->campos."
						FROM 
							relacionamentos_comerciais rc ";	
		
		$this->sql .= " WHERE rc.ativo = 'S' ";			  
		
		//print_r2($_GET,true);
		$data_cadastro_mensagem = $_GET['q']['de_ate|tipo_data_filtro|rcm.data_cad'];
		unset($_GET['q']['de_ate|tipo_data_filtro|rcm.data_cad']);
		
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
					}  elseif($campo[0] == 'de_ate' || $campo[0] == 'de_ate_matricula') {
						if($valor == 'HOJ') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
                        } elseif($valor == 'ONT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
                        } else if($valor == 'SET') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
                        } elseif($valor == 'QUI') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
                        } else if($valor == 'MAT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
                        } else if($valor == 'MPR') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
                        } else if($valor == 'MAN') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
                        }
                    }
				} 
			}
		}
		
		$_GET['q']['de_ate|tipo_data_filtro|rcm.data_cad'] = $data_cadastro_mensagem;
		
		if($_GET["de"]) {
			$this->sql .= " and (rc.data_cad >= '".formataData($_GET["de"],'en',0)." 00:00:00') ";
		}
		
		if($_GET["ate"]) {
			$this->sql .= " and (rc.data_cad <= '".formataData($_GET["ate"],'en',0)." 23:59:59') ";
		}

		$this->groupby = "rc.idrelacionamento";
		$linhas = $this->retornarLinhas();
		foreach ($linhas as $relacionamento) {
			$sql = 'SELECT rcm.*, v.nome as vendedor, ua.nome as usuario 
						FROM relacionamentos_comerciais_mensagens rcm 
						LEFT JOIN vendedores v ON rcm.idvendedor = v.idvendedor 
						LEFT JOIN usuarios_adm ua ON rcm.idusuario = ua.idusuario 
						WHERE rcm.ativo = "S" and rcm.idrelacionamento = ' . $relacionamento['idrelacionamento'];
				
			if ($_GET['q']['de_ate|tipo_data_filtro|rcm.data_cad'] != 'PER') {
				$filtro = $_GET['q']['de_ate|tipo_data_filtro|rcm.data_cad'];
				if($filtro == 'HOJ') {
					$sql .= " and date_format(rcm.data_cad,'%Y-%m-%d') = '".date("Y-m-d")."'";
				} elseif($filtro == 'ONT') {
					$sql .= " and date_format(rcm.data_cad,'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
				} else if($filtro == 'SET') {
					$sql .= " and date_format(rcm.data_cad,'%Y-%m-%d') <= '".date("Y-m-d")."'
								  and date_format(rcm.data_cad,'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
				} elseif($filtro == 'QUI') {
					$sql .= " and date_format(rcm.data_cad,'%Y-%m-%d') <= '".date("Y-m-d")."'
								  and date_format(rcm.data_cad,'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
				} else if($filtro == 'MAT') {
					$sql .= " and date_format(rcm.data_cad,'%Y-%m') = '".date("Y-m")."'";
				} else if($filtro == 'MPR') {
					$sql .= " and date_format(rcm.data_cad,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
				} else if($filtro == 'MAN') {
					$sql .= " and date_format(rcm.data_cad,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
				}
			}
				
			if($_GET["de_proxima_acao"]) {
				$sql .= " and (rcm.proxima_acao >= '".formataData($_GET["de_proxima_acao"],'en',0)."') ";
			}
			
			if($_GET["ate_proxima_acao"]) {
				$sql .= " and (rcm.proxima_acao <= '".formataData($_GET["ate_proxima_acao"],'en',0)."') ";
			}
			//echo $sql . '<br />';			
			$resultado = $this->executaSql($sql);
			$data_anterior = 0;
			while ($mensagem = mysql_fetch_assoc($resultado)) {
				if ($mensagem['proxima_acao'] > $data_anterior) {
					$data_anterior = $mensagem['proxima_acao'];
					$relacionamento['proxima_acao'] = $data_anterior;
				}
				$relacionamento['mensagens'][] = $mensagem;
			}
			
			$relacionamentos[] = $relacionamento; 
		}
		
		//print_r2($relacionamentos);
		return $relacionamentos;
							  
	}
	
		function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem") {
			
			// Buscando os idiomas do formulario
			include("idiomas/pt_br/index.php");
			echo '<table class="zebra-striped" id="sortTableExample">';
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
					} elseif($campo["sql_filtro"]){
						$sql = str_replace("%",$_GET[$campo["nome"]],$campo["sql_filtro"]);
						$seleciona = mysql_query($sql);
						$linha = mysql_fetch_assoc($seleciona);
						$_GET[$campo["nome"]] = $linha[$campo["sql_filtro_label"]];
						
						$campoAux = $_GET[$campo["nome"]];  
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
			echo '</table><br>';			
			
			/*
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
				foreach($dados as $i => $linha){
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

			}

			echo '</tbody>';
			echo '</table>';*/
		}
}

?>