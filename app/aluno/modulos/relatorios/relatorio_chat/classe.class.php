<?

class Relatorio extends Core {

	function gerarRelatorio(){
		
		$this->sql = 'SELECT
							oca.idava
						FROM
							matriculas m
							INNER JOIN ofertas_cursos_escolas ocp ON (m.idoferta = ocp.idoferta AND m.idescola = ocp.idescola AND m.idcurso = ocp.idcurso AND ocp.ativo = "S")
							INNER JOIN ofertas_curriculos_avas oca ON (ocp.idoferta = oca.idoferta AND ocp.idcurriculo = oca.idcurriculo AND oca.ativo = "S" AND idava IS NOT NULL) 
							LEFT OUTER JOIN matriculas_avas_porcentagem map ON (m.idmatricula = map.idmatricula AND oca.idava = map.idava)
							INNER JOIN matriculas_workflow mw ON (m.idsituacao = mw.idsituacao)

						WHERE
							m.idpessoa = '.(int)$this->idpessoa.' AND
							m.ativo = "S" AND mw.cancelada = "N"
						GROUP BY oca.idava';
						
		$query = $this->executaSql($this->sql);		
		while($val = mysql_fetch_assoc($query)){
			$avas[] = $val['idava'];
		}
		
		if (empty($avas)) {
			return $avas;
		}

		$this->sql = "SELECT
						  ".$this->campos."
							  FROM
							   avas_chats c
							   INNER JOIN avas a 
							   		ON (c.idava=a.idava)
							WHERE a.ativo='S' AND c.ativo='S' AND c.exibir_ava='S'
							AND (c.inicio_entrada_aluno >= NOW() or c.fim_entrada_aluno >= NOW() or c.fim_entrada_aluno is null  or c.inicio_entrada_aluno is null)
						  ";
		
			  
		if(is_array($avas)){
			$this->sql .= " and c.idava in(".implode(',',$avas).") ";
		} 
		
		return $this->retornarLinhas();;
							  
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