<?

class Relatorio extends Core {

	function gerarRelatorio(){
		
		if($_GET['q']['de_ate|tipo_data_registro|c.inicio_entrada_aluno'] == 'PER' && (!$_GET["q"]["3|c.inicio_entrada_aluno"] || !$_GET["q"]["4|c.inicio_entrada_aluno"]) ){
			unset($_GET['q']['de_ate|tipo_data_registro|c.inicio_entrada_aluno']);
		}
		if($_GET['q']['de_ate|tipo_data_filtro|c.fim_entrada_aluno'] == 'PER' && (!$_GET["q"]["3|c.fim_entrada_aluno"] || !$_GET["q"]["4|c.fim_entrada_aluno"]) ){
			unset($_GET['q']['de_ate|tipo_data_filtro|c.fim_entrada_aluno']);
		}	
		
		$this->sql = "SELECT
						  ".$this->campos."
							  FROM
							   avas_chats c
							   INNER JOIN avas a 
							   		ON (c.idava=a.idava)
							WHERE a.ativo='S' AND c.ativo='S'
						  ";
						  		
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
					} elseif($campo[0] == 3)  {
						$valor =explode('/',$valor);
						$this->sql .= " and date_format(".$campo[1].",'%Y-%m-%d') >= '".$valor[2].'-'.$valor[1].'-'.$valor[0]."' ";
					} elseif($campo[0] == 4)  {
						$valor =explode('/',$valor);
						$this->sql .= " and date_format(".$campo[1].",'%Y-%m-%d') <= '".$valor[2].'-'.$valor[1].'-'.$valor[0]."' ";
					} elseif($campo[0] == 'de_ate') {
					  	if($valor == 'HOJ') {
						  	$this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
					  	} elseif ($valor == 'ONT') {
						    $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
						} elseif ($valor == 'SET') {
						  	$this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
										  and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'	";
					  	} elseif($valor == 'QUI') {
						    $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
						                  and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
						} elseif ($valor == 'MAT') {
						  	$this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
					  	} elseif ($valor == 'MPR') {
						  	$this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
					  	} elseif ($valor == 'MAN') {
						  	$this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
					  	}
				    }
				} 
			}
		}		
			
		$linhas = $this->retornarLinhas();
		
		return $linhas;
							  
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
						  $campoAuxNovo = str_replace(array("q[","]"),"",$campo["nome"]);
						  $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAuxNovo]];
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
			echo '</table><br>';
						
			
			
			
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
				
				
			$totalValor = 0;
				
				foreach($dados as $i => $linha){
					echo '<tr>';
					foreach($this->config[$configuracao] as $ind => $valor){

						if($valor["id"] == 'valor_contrato') {
							$total_valor_contrato += $linha["valor_contrato"];
						}
						
						if($valor["tipo"] == "banco") {
							//echo '<td>'.stripslashes(strtoupper($linha[$valor["valor"]])).'</td>';
							echo '<td>'.stripslashes($linha[$valor["valor"]]).'</td>';
						} elseif($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
							$valor = $valor["valor"]." ?>";
							$valor = eval($valor);							
							echo '<td>'.stripslashes($valor).'</td>';
						} elseif($valor["tipo"] == "array") {
							$variavel = $GLOBALS[$valor["array"]];
							echo '<td>'.strtoupper($variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]]).'</td>';
						} elseif($valor["busca_tipo"] != "hidden") {
							echo '<td>'.stripslashes($valor["valor"]).'</td>';
						}
					}

					echo '</tr>';
				}

			}

			echo '</tbody>';
			echo '</table>';
		}
		
}

?>