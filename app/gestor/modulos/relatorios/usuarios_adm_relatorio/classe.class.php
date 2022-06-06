<?php
class Relatorio extends Core
{
	function gerarRelatorio()
	{
		$this->sql = 'SELECT
							' . $this->campos . '
						FROM
							usuarios_adm u
					  		LEFT OUTER JOIN usuarios_adm_perfis p ON (p.idperfil = u.idperfil)
					  	WHERE
					  		u.ativo = "S"';

		if (is_array($_GET['q'])) {
			foreach($_GET['q'] as $campo => $valor) {
				//explode = Retira, ou seja retira a '|' da variavel campo
				$campo = explode('|',$campo);
				$valor = str_replace('\'','',$valor);
				// Listagem se o valor for diferente de Todos ele faz um filtro
				if (($valor || $valor === '0') && $valor <> 'todos') {
					// se campo[0] for = 1 é pq ele tem de ser um valor exato
					if ($campo[0] == 1) {
						$this->sql .= ' AND ' . $campo[1] . ' = "' . $valor . '"';
					// se campo[0] for = 2, faz o filtro pelo comando like
					} elseif ($campo[0] == 2)  {
						$busca = str_replace('\\\'','',$valor);
						$busca = str_replace('\\','',$busca);
						$busca = explode(' ',$busca);
						foreach($busca as $ind => $buscar) {
							$this->sql .= ' AND ' . $campo[1] . ' LIKE "%' . urldecode($buscar) . '%"';
						}
					} elseif ($campo[0] == 3)  {
						$this->sql .= ' AND DATE_FORMAT(' . $campo[1] . ',"%Y-%m-%d") = "' . formataData($valor, 'en', 0) . '"';
					} elseif ($campo[0] == 5)  { // data_de
					    $this->sql .= ' AND DATE_FORMAT(' . $campo[1] . ',"%Y-%m-%d") >= "' . formataData($valor, 'en', 0) . '"';
					}  elseif ($campo[0] == 6)  { //data_ate
					    $this->sql .= ' AND DATE_FORMAT(' . $campo[1] . ',"%Y-%m-%d") <= "' . formataData($valor, 'en', 0) . '"';
					} elseif ($campo[0] == 7)  {
						$this->sql .= ' AND DATE_FORMAT(' . $campo[1] . ',"%m") = "' . $valor . '"';
					} elseif ($campo[0] == 8)  {
						$this->sql .= ' AND (
												(
													SELECT
														count(i.idsindicato)
													FROM
														sindicatos i
														INNER JOIN usuarios_adm_sindicatos uai ON (uai.idsindicato = i.idsindicato AND uai.ativo = "S")
													WHERE
														uai.idusuario = u.idusuario AND
														i.idsindicato = "' . $valor . '"
												) > 0 OR
												u.gestor_sindicato = "S"
										)';
					}
				} 
			}
		}
		
		$this->sql .= ' GROUP BY u.idusuario';

		return $this->retornarLinhas();
	}
	
	function gerarTabela($dados,$q = null,$idioma,$configuracao = "listagem")
	{
		// Buscando os idiomas do formulario
		include("idiomas/pt_br/index.php");
		echo '<table class="zebra-striped" id="sortTableExample">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Filtro</th>';
		echo '<th>Valor</th>';
		echo '</tr>';
		echo '</thead>';
		foreach($this->config["formulario"] as $ind => $fieldset) {
			foreach($fieldset["campos"] as $ind => $campo) {
				if ($campo["nome"]{0} == "q") { 
				  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
				  $campoAux = $_GET["q"][$campoAux];
				  if ($campo["sql_filtro"]) {
				  	  if ($campo["sql_filtro"] == "array") {
						  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
						  $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAux]];
					  } else {
						  $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
						  $seleciona = mysql_query($sql);
						  $linha = mysql_fetch_assoc($seleciona);
						  $campoAux = $linha[$campo["sql_filtro_label"]];
					  }
				  }
				} elseif (is_array($_GET[$campo["nome"]])) {
					
				  if ($campo["array"]) {
					  foreach($_GET[$campo["nome"]] as $ind => $val) {
						 $_GET[$campo["nome"]][$ind] = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$val];
					  }
				  } elseif ($campo["sql_filtro"]) {
					  foreach($_GET[$campo["nome"]] as $ind => $val) {
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
				if ($campoAux <> "") {				  
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
		foreach($this->config[$configuracao] as $ind => $valor) {
		
				$tamanho = "";
				if ($valor["tamanho"]) $tamanho = ' width="'.$valor["tamanho"].'"';
				
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
					
		if (count($dados) == 0) {
			echo '<tr>';
			echo '<td colspan="'.count($this->config[$configuracao]).'">Nenhum informação foi encontrada.</td>';
			echo '</tr>';
		} else {
			foreach($dados as $i => $linha) {
				echo '<tr>';
				foreach($this->config[$configuracao] as $ind => $valor) {
					
					if ($valor["tipo"] == "banco") {
						echo '<td>'.stripslashes(strtoupper($linha[$valor["valor"]])).'</td>';
					} elseif ($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
						$valor = $valor["valor"]." ?>";
						$valor = eval($valor);							
						echo '<td>'.stripslashes($valor).'</td>';
					} elseif ($valor["tipo"] == "array") {
						$variavel = $GLOBALS[$valor["array"]];
						echo '<td>'.strtoupper($variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]]).'</td>';
					} elseif ($valor["busca_tipo"] != "hidden") {
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