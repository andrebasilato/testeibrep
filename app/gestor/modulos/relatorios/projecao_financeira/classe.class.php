<?php
class Relatorio extends Core {

	function gerarRelatorio() { 
		
		$deArray = explode('/',$_GET['de']);
		$ateArray = explode('/',$_GET['ate']);
        
		$this->sql = "SELECT idsituacao, nome FROM `contas_workflow` WHERE ativo='S' and cancelada='S'";
		$situacaoCancelada = $this->retornarLinha($this->sql);  

		$this->sql = "SELECT idsituacao, nome FROM `contas_workflow` WHERE ativo='S' and renegociada='S'";
		$situacaoRebegociada = $this->retornarLinha($this->sql);                
	
		$sql = "SELECT idevento, nome FROM `eventos_financeiros` WHERE ativo='S' order by nome ";
		$query_v = $this->executaSql($sql);
		while ($linha = mysql_fetch_assoc($query_v)) {
				$retorno['eventos'][] = $linha;
		}
		
		$sql = "SELECT 
					idsindicato, nome_abreviado 
					FROM sindicatos ins
					INNER JOIN estados est ON (ins.idestado_competencia = est.idestado)
					WHERE ins.ativo='S' ";
		if($_SESSION['adm_gestor_sindicato'] <> "S"){
			$sql .= ' and ins.idsindicato in ('.$_SESSION['adm_sindicatos'].')';
		}						
		if($_GET['idsindicato']){
			$sql .= " and ins.idsindicato in (".implode(",", $_GET['idsindicato']).") ";	
		}
		if($_GET['idregiao']){
			$sql .= " and est.idregiao = '".$_GET['idregiao']."' ";	
		}				
		$sql .= " order by nome_abreviado ";
		$query_v = $this->executaSql($sql);
		$sindicatosArray = array();
		while ($linha = mysql_fetch_assoc($query_v)) {
				$retorno['sindicatos'][] = $linha;
				$sindicatosArray[] = $linha['idsindicato'];
		}
		
		
		$sql = "SELECT 
					c.idsindicato, m.idcurso, cur.codigo as curso, c.idevento, count(c.idconta) as parcelas, sum(c.valor) as total
					FROM contas c
                    INNER JOIN matriculas m on (c.idmatricula = m.idmatricula)
					INNER JOIN sindicatos ins ON (m.idsindicato = ins.idsindicato)
					INNER JOIN estados est ON (ins.idestado_competencia = est.idestado)
                    INNER JOIN cursos cur on (cur.idcurso = m.idcurso)
				where c.idmatricula is not null"; 

		if (count($sindicatosArray) > 0 ) {
			$sql .= " and c.idsindicato in (".implode(",", $sindicatosArray).")"; 
		}
		
		$sql .= " and c.idsituacao <> '".$situacaoCancelada["idsituacao"]."'
	              and c.idsituacao <> '".$situacaoRebegociada["idsituacao"]."'
				  and date_format(c.data_vencimento,'%Y%m') >= '".$deArray[1].$deArray[0]."' 
				  and date_format(c.data_vencimento,'%Y%m') <= '".$ateArray[1].$ateArray[0]."'"; 

		if($_SESSION['adm_gestor_sindicato'] <> "S"){
			$sql .= ' and c.idsindicato in ('.$_SESSION['adm_sindicatos'].')';
		}
		if($_GET['idcurso']){
			$sql .= " and m.idcurso in (".implode(",", $_GET['idcurso']).") ";	
		}   
		if($_GET['idsindicato']){
			$sql .= " and ins.idsindicato in (".implode(",", $_GET['idsindicato']).") ";	
		}
		if($_GET['idregiao']){
			$sql .= " and est.idregiao = '".$_GET['idregiao']."' ";	
		}    				
		$sql .= " group by c.idsindicato, m.idcurso, c.idevento ";
		$query_v = $this->executaSql($sql);
		while ($linha = mysql_fetch_assoc($query_v)) {
				$retorno['matriculas'][$linha['idsindicato']][$linha['idcurso']][$linha['idevento']]['parcelas'] += $linha["parcelas"];
				$retorno['matriculas'][$linha['idsindicato']][$linha['idcurso']][$linha['idevento']]['total'] += $linha["total"];
		        $retorno['cursos'][$linha['idcurso']] = $linha['curso'];
        }
		
		$sql = "SELECT 
					c.idsindicato, c.tipo, count(c.idconta) as parcelas, sum(c.valor) as total
					FROM contas c
					INNER JOIN sindicatos ins ON c.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado_competencia = est.idestado
				where c.idmatricula is null";

		if (count($sindicatosArray) > 0 ) {
			$sql .= " and c.idsindicato in (".implode(",", $sindicatosArray).")"; 
		}
		
		$sql .= " and c.idsituacao <> '".$situacaoCancelada["idsituacao"]."'
                  and c.idsituacao <> '".$situacaoRebegociada["idsituacao"]."'                      
				  and date_format(c.data_vencimento,'%Y%m') >= '".$deArray[1].$deArray[0]."' 
				  and date_format(c.data_vencimento,'%Y%m') <= '".$ateArray[1].$ateArray[0]."'"; 

		if($_SESSION['adm_gestor_sindicato'] <> "S"){
			$sql .= ' and c.idsindicato in ('.$_SESSION['adm_sindicatos'].')';
		}	
		if($_GET['idsindicato']){
			$sql .= " and ins.idsindicato in (".implode(",", $_GET['idsindicato']).") ";	
		}
		if($_GET['idregiao']){
			$sql .= " and est.idregiao = '".$_GET['idregiao']."' ";	
		}   		
		$sql .= " group by idsindicato, c.tipo ";
		$query_v = $this->executaSql($sql);
		while ($linha = mysql_fetch_assoc($query_v)) {
				$retorno['contas'][$linha['idsindicato']][$linha['tipo']]['parcelas'] += $linha["parcelas"];
				$retorno['contas'][$linha['idsindicato']][$linha['tipo']]['total'] += $linha["total"];
		}			
			
		return $retorno;							  
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
					} else {
					 
					  if($campo["nome"] == "idregiao" && $campo["sql_filtro"]){
							 $sql = str_replace("%",$_GET[$campo["nome"]],$campo["sql_filtro"]);
							 $seleciona = mysql_query($sql);
							 $linha = mysql_fetch_assoc($seleciona);
							 $_GET[$campo["nome"]] = $linha[$campo["sql_filtro_label"]];
					  }
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
            
			
		}
		
    
    function FormataValor($valor,$negrito = false, $compararCom = 0){
        
        if($valor < $compararCom) $cor = "#FF0000";
        else $cor = "#000";
        
        $style = ' ';
        if($negrito) $style .= ' font-weight:bold;';
        
        echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
          <tr>
            <td style="color:#CCCCCC; padding:2px;">R$</td>
            <td style="text-align:right; padding:2px; color: '.$cor.';'.$style.'">'.number_format($valor, 2, ',', '.').'</td>
          </tr>
        </table>';
        
    }
    
    function FormataNumero($numero,$negrito = false, $compararCom = 0){
        
        if($numero < $compararCom) $cor = "#FF0000";
        else $cor = "#000";
        
        $style = ' ';
        if($negrito) $style .= ' font-weight:bold;';
        
        echo '<span style="color: '.$cor.';'.$style.'">'.number_format((int) $numero, 0, ',', '.').'</span>';
        
    }    
		
}

?>