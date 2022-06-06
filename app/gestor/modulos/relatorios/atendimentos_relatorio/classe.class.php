<?

class Relatorio extends Core {

	function gerarRelatorio(){
	
		#VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO
		/*if($_GET['q']['de_ate|tipo_data_filtro|ate.data_cad'] == 'PER' && (!$_GET["q"]["4|ah.data_cad"] || !$_GET["q"]["4|ah.data_cad"]) ){
			unset($_GET['q']['de_ate|tipo_data_filtro|ate.data_cad']);
		}*/
		if($_GET['q']['de_ate|tipo_data_filtro|ah.data_cad'] == 'PER' && (!$_GET["q"]["4|ate.data_cad"] || !$_GET["q"]["5|ate.data_cad"]) ){
			unset($_GET['q']['de_ate|tipo_data_filtro|ah.data_cad']);
		}

		if ($_GET['q']['de_ate|tipo_data_filtro|ate.data_cad'] == 'PER' && (!$_GET["q"]["4|ah.data_cad"] || !$_GET["q"]["5|ah.data_cad"])) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'datas_obrigatorias';
			return $retorno;
		}
		if (dataDiferenca(formataData($_GET["q"]["4|ah.data_cad"], 'en', 0), formataData($_GET["q"]["5|ah.data_cad"], 'en', 0), 'D') > 365) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'intervalo_maior_um_ano';
			return $retorno;
		}		
		/*
		if ( 
			($_GET['q']['de_ate|tipo_data_filtro|ate.data_cad'] == 'PER' && (!$_GET["q"]["4|ah.data_cad"] || !$_GET["q"]["5|ah.data_cad"]) ) 
			||
			($_GET['q']['de_ate|tipo_data_filtro|ah.data_cad'] == 'PER' && (!$_GET["q"]["4|ate.data_cad"] || !$_GET["q"]["5|ate.data_cad"]) )
			) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'datas_obrigatorias';
			return $retorno;
		}
		if (
			(dataDiferenca(formataData($_GET["q"]["4|ah.data_cad"], 'en', 0), formataData($_GET["q"]["5|ah.data_cad"], 'en', 0), 'D') > 365)
			||
			(dataDiferenca(formataData($_GET["q"]["4|ate.data_cad"], 'en', 0), formataData($_GET["q"]["5|ate.data_cad"], 'en', 0), 'D') > 365)
			) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'intervalo_maior_um_ano';
			return $retorno;
		}
		*/
		#VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO - FIM
		
		$this->sql = "SELECT 
						".$this->campos." 
		              FROM 
						atendimentos ate
					    INNER JOIN atendimentos_workflow aw on ate.idsituacao = aw.idsituacao
						INNER JOIN pessoas p on ate.idpessoa = p.idpessoa
						INNER JOIN atendimentos_assuntos aa on ate.idassunto = aa.idassunto
						INNER JOIN atendimentos_historicos ah on (ate.idatendimento = ah.idatendimento and 
																  ah.tipo = 'S' and 
																  ah.para = ate.idsituacao";
		
		if($_GET["q"]["4|ah.data_cad"] && $_GET['q']['de_ate|tipo_data_filtro|ate.data_cad']) {
		  $this->sql .= " and date_format(ah.data_cad,'%Y-%m-%d') >= '".formataData($_GET["q"]["4|ah.data_cad"],'en',0)."'"; 
		}
		if($_GET["q"]["5|ah.data_cad"] && $_GET['q']['de_ate|tipo_data_filtro|ate.data_cad']) {
		  $this->sql .= " and date_format(ah.data_cad,'%Y-%m-%d') <= '".formataData($_GET["q"]["5|ah.data_cad"],'en',0)."'";
		}
		if($_GET["q"]["1|ah.idusuario"]) {
		  $this->sql .= " and ah.idusuario = '".$_GET["q"]["1|ah.idusuario"]."'";
		}
		$this->sql .= " )";
		
		$this->sql .= " LEFT OUTER JOIN usuarios_adm usu on (ah.idusuario = usu.idusuario)
						LEFT OUTER JOIN atendimentos_assuntos_subassuntos asub on ate.idsubassunto = asub.idsubassunto
					  WHERE 
						ate.ativo = 'S' "; 
		
		if($_GET["idsituacao"])				
			$this->sql .= " and (ate.idsituacao = ".implode(" or ate.idsituacao = ", $_GET["idsituacao"]).") ";
		
		if(is_array($_GET["q"])) {
			foreach($_GET["q"] as $campo => $valor) {
				$campo = explode("|",$campo);
				$valor = str_replace("'","",$valor);
				if(($valor || $valor === "0") and $valor <> "todos" && $campo[1] != "ate.data_situacao" && $campo[1] != "ah.idusuario") {
					if($campo[0] == 1) {
							$this->sql .= " and ".$campo[1]." = '".$valor."' ";	
					} elseif($campo[0] == 2)  {
						$busca = str_replace("\\'","",$valor);
						$busca = str_replace("\\","",$busca);
						$busca = explode(" ",$busca);
						foreach($busca as $ind => $buscar){
							$this->sql .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
						}
					} elseif($campo[0] == 3)  {
						$this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
					} elseif($campo[0] == 4 && $campo[1] )  {
						$this->sql .= " and ".$campo[1]." >= '".formataData($valor,'en',0)." 00:00:00' ";
					} elseif($campo[0] == 5)  {
						$this->sql .= " and ".$campo[1]." <= '".formataData($valor,'en',0)." 23:59:59' ";
					}elseif($campo[0] == 'de_ate') {
						  if($valor == 'HOJ') {
							  $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
						  } else if($valor == 'ONT') {
	                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
	                      } else if($valor == 'SET') {
							  $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
											  and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'	";
						  } else if($valor == 'QUI') {
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

		$this->sql .= " group by ate.idatendimento";
		$this->limite = -1;
		$this->ordem_campo = false;
		$this->ordem = false;
		
		return $this->retornarLinhas();							  
	}
	
		function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem") {
			
		if($_GET['q']['de_ate|tipo_data_filtro|ate.data_cad'] == 'PER' && (!$_GET["q"]["4|ah.data_cad"] || !$_GET["q"]["4|ah.data_cad"]) ){
			unset($_GET['q']['de_ate|tipo_data_filtro|ate.data_cad']);
		}
		if($_GET['q']['de_ate|tipo_data_filtro|ah.data_cad'] == 'PER' && (!$_GET["q"]["4|ate.data_cad"] || !$_GET["q"]["5|ate.data_cad"]) ){
			unset($_GET['q']['de_ate|tipo_data_filtro|ah.data_cad']);
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
				foreach($dados as $i => $linha){
					echo '<tr>';
					foreach($this->config[$configuracao] as $ind => $valor){
						
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
		
	function retornaSituacoes() {
		  $this->sql = "select idsituacao, nome FROM atendimentos_workflow WHERE ativo = 'S'";
		  $this->limite = -1;
		  $this->ordem_campo = "nome";
		  $this->ordem = "asc";
		  $situacoes = $this->retornarLinhas();
		  //$retorno["todos"] = "Todos";
		  foreach($situacoes as $situacao){				
			$retorno[$situacao["idsituacao"]] = $situacao["nome"];  
		  }
		  return $retorno;
  	}
}

?>