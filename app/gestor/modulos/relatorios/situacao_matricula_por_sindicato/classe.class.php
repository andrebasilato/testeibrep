<?

class Relatorio extends Core {

	function gerarRelatorio(){
		
		if($_GET['q']['de_ate|tipo_data_filtro|ma.data_cad'] == 'PER' && (!$_GET["matricula_de"] || !$_GET["matricula_ate"]) ){
			unset($_GET['q']['de_ate|tipo_data_filtro|ma.data_cad']);
		}

		if($_GET['q']['de_ate|tipo_data_registro|ma.data_registro'] == 'PER' && (!$_GET["registro_de"] || !$_GET["registro_ate"]) ){
			unset($_GET['q']['de_ate|tipo_data_registro|ma.data_registro']);
		}
		
		#VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO	
		/*if ($_GET['q']['de_ate|tipo_data_registro|ma.data_registro'] == 'PER' && (!$_GET["registro_de"] || !$_GET["registro_ate"])) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'datas_obrigatorias';
			return $retorno;
		}*/	
		if (dataDiferenca(formataData($_GET["registro_de"], 'en', 0), formataData($_GET["registro_ate"], 'en', 0), 'D') > 365) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'intervalo_maior_um_ano';
			return $retorno;
		}

		if (dataDiferenca(formataData($_GET["matricula_de"], 'en', 0), formataData($_GET["matricula_ate"], 'en', 0), 'D') > 365) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'intervalo_maior_um_ano';
			return $retorno;
		}
	
		$sql = " SELECT idsituacao 
				FROM matriculas_workflow 
				WHERE ativo = 'S' AND fim = 'S' ";
        $situacao_concluida = $this->retornarLinha($sql);

        $sqlInsituicao = 'SELECT i.idsindicato, i.nome_abreviado 
        						FROM 
        							sindicatos i 
        						INNER JOIN estados e ON (e.idestado = i.idestado_competencia)
        						WHERE 
        							i.ativo = "S"';
        if ($_GET['q']['1|e.idregiao']) {
        	$sqlInsituicao .= ' AND e.idregiao = "'.$_GET['q']['1|e.idregiao'].'"';
        	$buscaRegiao = $_GET['q']['1|e.idregiao'];
        	unset($_GET['q']['1|e.idregiao']);
        }
		if($_SESSION['adm_gestor_sindicato'] != 'S') {
			$sqlInsituicao .= ' AND i.idsindicato IN ('.$_SESSION['adm_sindicatos'].')';
		}
		$sqlInsituicao .= ' ORDER BY i.nome_abreviado';		
		$query = $this->executaSql($sqlInsituicao);

        while($sindicato = mysql_fetch_assoc($query)) {

        	$subselectMatriculas = 'SELECT 
							   		count(ma.idmatricula) from matriculas ma 
							   	WHERE 
							   		ma.ativo = "S" AND
							   		ma.idsituacao = mw.idsituacao AND 
							   		ma.idsindicato = "'.$sindicato['idsindicato'].'"';

			if(is_array($_GET["q"])) {
				foreach($_GET["q"] as $campo => $valor) {
					//explode = Retira, ou seja retira a "|" da variavel campo
					$campo = explode("|",$campo);
					$valor = str_replace("'","",$valor);
					// Listagem se o valor for diferente de Todos ele faz um filtro
					if(($valor || $valor === "0") && $valor <> "todos") {
						// se campo[0] for = 1 é pq ele tem de ser um valor exato
						if($campo[0] == 1) {
							$subselectMatriculas .= " and ".$campo[1]." = '".$valor."' ";
						// se campo[0] for = 2, faz o filtro pelo comando like
						} elseif($campo[0] == 2)  {
							$subselectMatriculas .= " and ".$campo[1]." like '%".urldecode($valor)."%' ";
						} elseif($campo[0] == 'de_ate') {
						  if($valor == 'HOJ') {
							  $subselectMatriculas .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
						  } else if($valor == 'ONT') {
	                            $subselectMatriculas .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
	                      } else if($valor == 'SET') {
							  $subselectMatriculas .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
											  and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'	";
						  } else if($valor == 'QUI') {
	                            $subselectMatriculas .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
	                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
	                      } else if($valor == 'MAT') {
							  $subselectMatriculas .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
						  } else if($valor == 'MPR') {
							  $subselectMatriculas .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
						  } else if($valor == 'MAN') {
							  $subselectMatriculas .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
						  }
					    }
					} 
				}
			}
					
			if($_GET["matricula_de"] && $_GET['q']['de_ate|tipo_data_filtro|ma.data_cad'] == 'PER') {
				$subselectMatriculas .= " and (ma.data_matricula >= '".formataData($_GET["matricula_de"],'en',0)."') ";
			}
			
			if($_GET["matricula_ate"] && $_GET['q']['de_ate|tipo_data_filtro|ma.data_cad'] == 'PER') {
				$subselectMatriculas .= " and (ma.data_matricula <= '".formataData($_GET["matricula_ate"],'en',0)."') ";
			}

			if($_GET["registro_de"] && $_GET['q']['de_ate|tipo_data_registro|ma.data_registro'] == 'PER') {
				$subselectMatriculas .= " AND (ma.data_registro >= '".formataData($_GET["registro_de"],'en',0)."') ";
			}
			
			if($_GET["registro_ate"] && $_GET['q']['de_ate|tipo_data_registro|ma.data_registro'] == 'PER') {
				$subselectMatriculas .= " AND (ma.data_registro <= '".formataData($_GET["registro_ate"],'en',0)."') ";
			}

            $this->sql = 'SELECT
							   mw.idsituacao, 
							   mw.nome as situacao, 
							   (
							   	'.$subselectMatriculas.'
							   	) as quantidade_matriculas
								  FROM
								    matriculas_workflow mw
								WHERE mw.ativo = "S"';
			
			$this->ordem_campo = "mw.ordem";
			$this->ordem = "asc";
			
			$this->groupby = "mw.idsituacao";
			$situacoes = $this->retornarLinhas();

			$insituicoes[$sindicato['idsindicato']] = $sindicato;
			$insituicoes[$sindicato['idsindicato']]['situacoes'] = $situacoes;
        }
        $_GET['q']['1|e.idregiao'] = $buscaRegiao;
		return $insituicoes;					  
	}
	
		function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem") {


			if($_GET['q']['de_ate|tipo_data_registro|ma.data_registro'] == 'PER' && (!$_GET["registro_de"] || !$_GET["registro_ate"]) ){
				unset($_GET['q']['de_ate|tipo_data_registro|ma.data_registro']);
			}
			
			if($_GET['q']['de_ate|tipo_data_filtro|ma.data_cad'] == 'PER' && (!$_GET["matricula_de"] || !$_GET["matricula_ate"]) ){
				unset($_GET['q']['de_ate|tipo_data_filtro|ma.data_cad']);
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
			if(count($dados) == 0){
				echo '<tr>';
				echo '<td colspan="'.count($this->config[$configuracao]).'">Nenhuma informação foi encontrada.</td>';
				echo '</tr>';
			} else {
				$colunaSituacao = 0;
				$totalSituacao = array();

				$firstKey = array_keys($dados);
				$firstKey = $firstKey[0];
				$situacoes = $dados[$firstKey]['situacoes'];

				echo '<thead>';
				echo '<tr>';
				echo '<td rowspan="2" style ="text-align: center;vertical-align:middle;"><strong>Sindicatos</strong></td>';
				echo '<td colspan="'.(count($situacoes) + 2).'"><center><strong>Situações</strong></center></td></tr>';
				echo '<tr>';
				
				foreach($situacoes as $indice => $situacao) {
					echo '<td class="headerSortReloca">';
					
					echo "<div class='headerNew'><strong>".$situacao['situacao']."</strong></div>";
					
					echo '</td>';
				}
				echo '<td>Total</td>';
				echo '</tr>';
				echo '</thead>';
				foreach ($dados as $idsindicato => $sindicato) {
					$totalSindicato = 0;
					echo '<tbody>';
					echo '<tr>';
					echo '<td class="headerSortReloca"><strong>'.$sindicato['nome_abreviado'].'</strong></td>';
					foreach ($sindicato['situacoes'] as $indice => $situacao) {
						echo '<td>'.$situacao['quantidade_matriculas'].'</td>';
						$totalSindicato += $situacao['quantidade_matriculas'];
						$totalSituacao[$situacao['idsituacao']] += $situacao['quantidade_matriculas'];
					}
					echo '<td style="background-color:#E4E4E4;"><strong>'.$totalSindicato.'</strong></td>';
					echo '</tr>';

				}	
				echo '<tr>';
				echo '<td>Total</td>';
				foreach ($totalSituacao as $idsituacao => $quantidadeSituacao) {
					$totalMatriculas += $quantidadeSituacao;
					echo '<td style="background-color:#E4E4E4;"><strong>'.$quantidadeSituacao.'</strong></td>';
				}
				echo '<td style="background-color:#E4E4E4;"><strong>'.$totalMatriculas.'</td>';
				echo '</tr>';
				
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