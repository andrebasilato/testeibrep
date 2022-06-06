<?php
class Relatorio extends Core
{
	function gerarRelatorio()
	{
		$sql = " SELECT idsituacao 
				FROM matriculas_workflow 
				WHERE ativo = 'S' AND fim = 'S' ";
        $situacao_concluida = $this->retornarLinha($sql);
		
		$this->sql = 'SELECT
					  		ma.idmatricula,
					  		o.idoferta,
					  		cu.idcurso,
					  		po.idescola,
					  		cbd.iddisciplina,
					  		cbd.idformula,
					  		cbd.ignorar_historico,
					  		cbd.contabilizar_media,
					  		cbd.exibir_aptidao,
					  		'.$this->campos.'
					  	FROM
					   		matriculas ma
							INNER JOIN ofertas o ON (ma.idoferta=o.idoferta)
							INNER JOIN cursos cu ON (ma.idcurso=cu.idcurso)
							INNER JOIN escolas po ON (ma.idescola=po.idescola)
							INNER JOIN sindicatos i ON (i.idsindicato = ma.idsindicato)
							INNER JOIN mantenedoras mt ON (i.idmantenedora = mt.idmantenedora)
							INNER JOIN pessoas pe ON (ma.idpessoa = pe.idpessoa)
					   		INNER JOIN matriculas_notas mn ON (mn.idmatricula = ma.idmatricula AND mn.ativo = "S")
						   	INNER JOIN matriculas_notas_tipos mnt ON (mn.idtipo = mnt.idtipo)
						   	LEFT OUTER JOIN provas_solicitadas ps ON (mn.id_solicitacao_prova = ps.id_solicitacao_prova)
						   	LEFT OUTER JOIN modelos_prova mp ON (mp.idmodelo = ps.modelo)
						   	INNER JOIN ofertas_cursos_escolas ocp ON (ocp.idoferta = ma.idoferta AND ocp.idcurso = ma.idcurso AND ocp.idescola = ma.idescola AND ocp.ativo = "S")
							INNER JOIN curriculos c ON (c.idcurriculo = ocp.idcurriculo AND c.ativo =  "S" AND c.ativo_painel =  "S")
							INNER JOIN curriculos_blocos cb ON (c.idcurriculo = cb.idcurriculo AND cb.ativo =  "S")
							INNER JOIN curriculos_blocos_disciplinas cbd ON (cb.idbloco = cbd.idbloco AND cbd.ativo =  "S" AND cbd.iddisciplina = mn.iddisciplina)
						WHERE
							ma.ativo = "S" ';

		if(!$_GET["q"]["1|i.idsindicato"] && $_SESSION['adm_gestor_sindicato'] != 'S'){
			$this->sql .= ' and i.idsindicato in ('.$_SESSION['adm_sindicatos'].')';
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
					  	if ($valor == 'HOJ') {
						  	$this->sql .= " AND date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
					  	} elseif ($valor == 'ONT') {
						    $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
						} elseif ($valor == 'SET') {
						  	$this->sql .= " AND date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
										  AND date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'	";
					  	} elseif ($valor == 'QUI') {
						    $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
						                  and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
						} elseif ($valor == 'MAT') {
						  	$this->sql .= " AND date_format(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
					  	} elseif ($valor == 'MPR') {
						  	$this->sql .= " AND date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
					  	} elseif ($valor == 'MAN') {
						  	$this->sql .= " AND date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
					  	}
				    }
				} 
			}
		}
		
		$this->ordem_campo = "pe.nome";
		$this->ordem = "asc";
		
		$this->groupby = "ma.idmatricula";

		$linhas = $this->retornarLinhas();

		require_once('../classes/matriculas.class.php');
		$matriculaObj = new Matriculas();

		foreach ($linhas as $ind => $var) {
			$matriculaArray['idoferta'] = $var['idoferta'];
			$matriculaArray['idcurso'] = $var['idcurso'];
			$matriculaArray['idescola'] = $var['idescola'];
			$matriculaObj->set('matricula', $matriculaArray);
			$matriculaCurriculo = $matriculaObj->RetornarCurriculo();//Retorna os dados do currículo da matrícula

			$disciplina['iddisciplina'] = $var['iddisciplina'];
	  		$disciplina['idformula'] = $var['idformula'];
	  		$disciplina['ignorar_historico'] = $var['ignorar_historico'];
	  		$disciplina['contabilizar_media'] = $var['contabilizar_media'];
	  		$disciplina['exibir_aptidao'] = $var['exibir_aptidao'];

			//Retorna a situação da disciplina
			$disciplinaSituacao = $matriculaObj->retornarSituacaoDisciplina($var['idmatricula'], $disciplina, $matriculaCurriculo['media']);
			$linhas[$ind]['situacao'] = $disciplinaSituacao['situacao'];
		}

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
			
			$totalNotas = 0;
			$qtdeNotas = 0;
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

						if($valor["id"] == 'nota') {
							$totalNotas += $linha["nota"];
							$qtdeNotas++;
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
				echo '<tr>
						<td colspan="4" style="text-align:right">Média:</td>
						<td>'.number_format(($totalNotas/$qtdeNotas),2,",",".").'</td>
						<td></td>
					</tr>';
			}
			
			echo '</tbody>';
			echo '</table>';
		}
		
	function RetornarCursosOferta() {
		$this->sql = "SELECT c.idcurso, c.nome
						  FROM cursos c
						  INNER JOIN ofertas_cursos oc on c.idcurso = oc.idcurso and oc.ativo = 'S'
						  ";
		if ($this->id)
			$this->sql .= " WHERE oc.idoferta = '".$this->id."' ";
		$this->sql .= ' GROUP BY c.idcurso ';
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