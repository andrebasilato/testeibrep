<?

class Relatorio extends Core {

	function gerarRelatorio(){
	
		/*if($_GET['q']['de_ate|tipo_data_registro|ma.data_registro'] == 'PER' && (!$_GET["registro_de"] || !$_GET["registro_ate"]) ){
			unset($_GET['q']['de_ate|tipo_data_registro|ma.data_registro']);
		}*/
		
		if($_GET['q']['de_ate|tipo_data_filtro|ma.data_matricula'] == 'PER' && (!$_GET["matricula_de"] || !$_GET["matricula_ate"]) ){
			unset($_GET['q']['de_ate|tipo_data_filtro|ma.data_matricula']);
		}
		
		if($_GET['q']['de_ate|tipo_data_conclusao|ma.data_conclusao'] == 'PER' && (!$_GET["conclusao_de"] || !$_GET["conclusao_ate"]) ){
			unset($_GET['q']['de_ate|tipo_data_conclusao|ma.data_conclusao']);
		}

		#VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO
		if ($_GET['q']['de_ate|tipo_data_registro|ma.data_registro'] == 'PER' && (!$_GET["registro_de"] || !$_GET["registro_ate"])) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'datas_obrigatorias';
			return $retorno;
		}
		if (dataDiferenca(formataData($_GET["registro_de"], 'en', 0), formataData($_GET["registro_ate"], 'en', 0), 'D') > 365) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'intervalo_maior_um_ano';
			return $retorno;
		}
		#VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO - FIM
	
		#VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO
		/*if ( 
			($_GET['q']['de_ate|tipo_data_registro|ma.data_registro'] == 'PER' && (!$_GET["registro_de"] || !$_GET["registro_ate"]) ) 
			||
			($_GET['q']['de_ate|tipo_data_filtro|ma.data_matricula'] == 'PER' && (!$_GET["matricula_de"] || !$_GET["matricula_ate"]) )
			||
			($_GET['q']['de_ate|tipo_data_conclusao|ma.data_conclusao'] == 'PER' && (!$_GET["conclusao_de"] || !$_GET["conclusao_ate"]) )
			) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'datas_obrigatorias';
			return $retorno;
		}
		if (
			(dataDiferenca(formataData($_GET["registro_de"], 'en', 0), formataData($_GET["registro_ate"], 'en', 0), 'D') > 365)
			||
			(dataDiferenca(formataData($_GET["matricula_de"], 'en', 0), formataData($_GET["matricula_ate"], 'en', 0), 'D') > 365)
			||
			(dataDiferenca(formataData($_GET["conclusao_de"], 'en', 0), formataData($_GET["conclusao_ate"], 'en', 0), 'D') > 365)
			) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'intervalo_maior_um_ano';
			return $retorno;
		}*/
		#VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO - FIM
		
		$this->sql = "SELECT
						  ".$this->campos."
							  FROM
							   matriculas ma
							   INNER JOIN cursos cu 
							   		ON (ma.idcurso=cu.idcurso)
							   INNER JOIN ofertas_turmas tu 
							   		ON (ma.idturma=tu.idturma)
							   INNER JOIN pessoas pe 
							   		ON (ma.idpessoa=pe.idpessoa)
							WHERE ma.ativo='S'
						  ";	
						  
						  
		if(!$_GET["q"]["1|ma.idsindicato"] && $_SESSION['adm_gestor_sindicato'] != 'S'){
			$this->sql .= ' and ma.idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
		}						  		  
		
		if(is_array($_GET["q"])) {
			foreach($_GET["q"] as $campo => $valor) {
				//explode = Retira, ou seja retira a "|" da variavel campo
				$campo = explode("|",$campo);
				$valor = str_replace("'","",$valor);
				// Listagem se o valor for diferente de Todos ele faz um filtro
				if(($valor || $valor === "0") && $valor <> "todos") {
					// se campo[0] for = 1 é pq ele tem de ser um valor exato
					if($campo[0] == 1) {
						$this->sql .= " AND ".$campo[1]." = '".$valor."' ";
					// se campo[0] for = 2, faz o filtro pelo comando like
					} elseif($campo[0] == 2)  {
						$this->sql .= " AND ".$campo[1]." like '%".urldecode($valor)."%' ";
					} elseif($campo[0] == 'de_ate') {
					  if($valor == 'HOJ') {
						  $this->sql .= " AND date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
					  } else if($valor == 'ONT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
                      } else if($valor == 'SET') {
						  $this->sql .= " AND date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
										  AND date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'	";
					  } else if($valor == 'QUI') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
                      } else if($valor == 'MAT') {
						  $this->sql .= " AND date_format(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
					  } else if($valor == 'MPR') {
						  $this->sql .= " AND date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
					  } else if($valor == 'MAN') {
						  $this->sql .= " AND date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
					  }
				    }
				} 
			}
		}
				
		if($_GET["matricula_de"] && $_GET['q']['de_ate|tipo_data_filtro|ma.data_matricula'] == 'PER') {
			$this->sql .= " AND (ma.data_matricula >= '".formataData($_GET["matricula_de"],'en',0)."') ";
		}
		if($_GET["matricula_ate"] && $_GET['q']['de_ate|tipo_data_filtro|ma.data_matricula'] == 'PER') {
			$this->sql .= " AND (ma.data_matricula <= '".formataData($_GET["matricula_ate"],'en',0)."') ";
		}

		if($_GET["registro_de"] && $_GET['q']['de_ate|tipo_data_registro|ma.data_registro'] == 'PER') {
			$this->sql .= " AND (ma.data_registro >= '".formataData($_GET["registro_de"],'en',0)."') ";
		}
		if($_GET["registro_ate"] && $_GET['q']['de_ate|tipo_data_registro|ma.data_registro'] == 'PER') {
			$this->sql .= " AND (ma.data_registro <= '".formataData($_GET["registro_ate"],'en',0)."') ";
		}

		if($_GET["conclusao_de"] && $_GET['q']['de_ate|tipo_data_conclusao|ma.data_conclusao'] == 'PER') {
			$this->sql .= " AND (ma.data_conclusao >= '".formataData($_GET["conclusao_de"],'en',0)."') ";
		}
		if($_GET["conclusao_ate"] && $_GET['q']['de_ate|tipo_data_conclusao|ma.data_conclusao'] == 'PER') {
			$this->sql .= " AND (ma.data_conclusao <= '".formataData($_GET["conclusao_ate"],'en',0)."') ";
		}

		if($_GET["situacao"]) {
			foreach($_GET["situacao"] as $idsituacao) {
        		$arraySituacoes[] = $idsituacao;
    		}
			$this->sql .= " AND ma.idsituacao IN(".implode(',', $arraySituacoes).") ";
		}	
		
		$this->groupby = "ma.idmatricula";
		
		$linhas = $this->retornarLinhas();
		
		return $linhas;
							  
	}
	
		function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem") {

		if($_GET['q']['de_ate|tipo_data_registro|ma.data_registro'] == 'PER' && (!$_GET["registro_de"] || !$_GET["registro_ate"]) ){
			unset($_GET['q']['de_ate|tipo_data_registro|ma.data_registro']);
		}
		
		if($_GET['q']['de_ate|tipo_data_filtro|ma.data_matricula'] == 'PER' && (!$_GET["matricula_de"] || !$_GET["matricula_ate"]) ){
			unset($_GET['q']['de_ate|tipo_data_filtro|ma.data_matricula']);
		}
		
		if($_GET['q']['de_ate|tipo_data_conclusao|ma.data_conclusao'] == 'PER' && (!$_GET["conclusao_de"] || !$_GET["conclusao_ate"]) ){
			unset($_GET['q']['de_ate|tipo_data_conclusao|ma.data_conclusao']);
		}

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
					  } elseif($campo["array"]) {
						  $campoAuxNovo = str_replace(array("q[","]"),"",$campo["nome"]);
						  $campoAux = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAuxNovo]];
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
		
	function RetornarCursosOferta() {
		$this->sql = "SELECT c.idcurso, c.nome
						  FROM cursos c
						  INNER JOIN ofertas_cursos oc on c.idcurso = oc.idcurso and oc.ativo = 'S'
						  WHERE oc.idoferta = '".$this->id."'";			
		$query = $this->executaSql($this->sql);
		$this->retorno = array();
		while($row = mysql_fetch_assoc($query)){
			$this->retorno[] = $row;
		}
		echo json_encode($this->retorno);
	}

	function RetornarTurmasOferta() {
		$this->sql = "SELECT tu.idturma, tu.nome
					FROM ofertas_turmas tu
					WHERE tu.idoferta = '".$this->id."'";			
		$query = $this->executaSql($this->sql);
		$this->retorno = array();
		while($row = mysql_fetch_assoc($query)){
			$this->retorno[] = $row;
		}
		echo json_encode($this->retorno);
	}

	function RetornarSindicatosMantenedoras() {
		$this->sql = "SELECT i.idsindicato, i.nome
						  FROM sindicatos i
						  WHERE i.idsindicato = '".$this->id."' AND
						  i.ativo = 'S'";		
		$query = $this->executaSql($this->sql);
		$this->retorno = array();
		while($row = mysql_fetch_assoc($query)){
			$this->retorno[] = $row;
		}
		echo json_encode($this->retorno);
	}
}

?>