<?php
class Relatorio extends Core {

	function gerarRelatorio() { 
		
		$deArray = explode('/',$_GET['de']);
		$ateArray = explode('/',$_GET['ate']);
        
		$this->sql = "SELECT idsituacao, nome FROM `contas_workflow` WHERE ativo='S' and cancelada='S'";
		$situacaoCancelada = $this->retornarLinha($this->sql);  

		$this->sql = "SELECT idsituacao, nome FROM `contas_workflow` WHERE ativo='S' and renegociada='S'";
		$situacaoRenegociada = $this->retornarLinha($this->sql);    

		$this->sql = "SELECT idsituacao, nome FROM `contas_workflow` WHERE ativo='S' and transferida='S'";
		$situacaoTransferida = $this->retornarLinha($this->sql);  
	
		$sql = "SELECT idcategoria, nome FROM `categorias` WHERE ativo='S' order by nome ";
		$query_v = $this->executaSql($sql);
		$arrayCategoriasAtivas = array();
		while ($linha = mysql_fetch_assoc($query_v)) {
				$arrayCategoriasAtivas[] = $linha['idcategoria'];
				$retorno['categorias'][] = $linha;
		}
		
		$sql = "SELECT 
					idsindicato, nome_abreviado 
					FROM sindicatos ins
					INNER JOIN estados est ON ins.idestado_competencia = est.idestado
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
					c.idsindicato, c.idcategoria, count(c.idconta) as parcelas, sum(c.valor) as total
					FROM contas c
					INNER JOIN sindicatos ins ON c.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado_competencia = est.idestado					
				where c.idmatricula is null and idpagamento_compartilhado is null";

		if (count($sindicatosArray) > 0 ) {
			$sql .= " and c.idsindicato in (".implode(",", $sindicatosArray).")"; 
		}
		
		if ($_GET['situacao']) {
			$sql .= ' and c.idsituacao in ( ' . implode(',', $_GET['situacao']) . ' ) ';			
		} else {
			$sql .= " and c.idsituacao <> '".$situacaoCancelada["idsituacao"]."'
					  and c.idsituacao <> '".$situacaoRenegociada["idsituacao"]."'
					  and c.idsituacao <> '".$situacaoTransferida["idsituacao"]."'	";
		}
		
		$sql .= " and date_format(c.data_vencimento,'%Y%m') >= '".$deArray[1].$deArray[0]."' 
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
		$sql .= " group by c.idsindicato, c.idcategoria ";
		//echo '<!--'.$sql.'-->';
		$query_v = $this->executaSql($sql);
		$arrayCategoriasContas = array();
		while ($linha = mysql_fetch_assoc($query_v)) {
				$arrayCategoriasContas[] = $linha["idcategoria"];
				$retorno['contas'][$linha['idsindicato']][$linha['idcategoria']]['parcelas'] += $linha["parcelas"];
				$retorno['contas'][$linha['idsindicato']][$linha['idcategoria']]['total'] += $linha["total"];
		}
		
		$arrayCategoriasDesativadas = array_diff($arrayCategoriasContas, $arrayCategoriasAtivas);	
		if(count($arrayCategoriasDesativadas) > 0) {
			$sql = "SELECT idcategoria, IF(ativo = 'N',CONCAT(nome, ' (CAT. DESATIVADA)'),nome) as nome FROM `categorias` WHERE ((idcategoria in (".implode(",", $arrayCategoriasDesativadas).") AND ativo = 'N' ) || ativo = 'S') order by nome";
			$query_v = $this->executaSql($sql);
			$retorno['categorias'] = array();
			while ($linha = mysql_fetch_assoc($query_v)) {
					$retorno['categorias'][] = $linha;
			}
		}
		
		//print_r2($retorno['categorias'],true);
		//print_r2($arrayCategoriasDesativadas,true);
		//print_r2($retorno,true);
		
		
		$sql = "SELECT 
					cp.idsindicato, cp.idcategoria, count(cp.idorcamento) as parcelas, sum(cp.valor) as total
					FROM contas_orcamentos cp
					INNER JOIN sindicatos ins ON cp.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado_competencia = est.idestado					
				WHERE"; 
		
		if (count($sindicatosArray) > 0 ) {
			$sql .= " cp.idsindicato in (".implode(",", $sindicatosArray).") AND"; 
		}
		
		$sql .= " date_format(cp.mes,'%Y%m') >= '".$deArray[1].$deArray[0]."' AND 
				  date_format(cp.mes,'%Y%m') <= '".$ateArray[1].$ateArray[0]."'"; 


		if($_SESSION['adm_gestor_sindicato'] <> "S"){
			$sql .= ' and cp.idsindicato in ('.$_SESSION['adm_sindicatos'].')';
		}	
		if($_GET['idsindicato']){
			$sql .= " and ins.idsindicato in (".implode(",", $_GET['idsindicato']).") ";	
		}
		if($_GET['idregiao']){
			$sql .= " and est.idregiao = '".$_GET['idregiao']."' ";	
		}

        $sql .= ' 
            and 
            (
                select 
                    count(1) 
                from
                    categorias_subcategorias_sindicatos csi 
                inner join categorias_subcategorias ci on csi.idsubcategoria = ci.idsubcategoria 
                where ci.idcategoria = cp.idcategoria and csi.ativo = "S" and cp.idsindicato = csi.idsindicato
            ) > 0 ';
        
		$sql .= " group by idsindicato, cp.idcategoria ";
        
		$query_v = $this->executaSql($sql);
		while ($linha = mysql_fetch_assoc($query_v)) {
				$retorno['orcamentos'][$linha['idsindicato']][$linha['idcategoria']]['parcelas'] += $linha["parcelas"];
				$retorno['orcamentos'][$linha['idsindicato']][$linha['idcategoria']]['total'] += $linha["total"];
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
        
		//echo " -- $valor -- $compararCom --";
		
        $style = ' ';
        if($negrito) $style = ' font-weight:bold;';
		
        if(abs($valor) >= $compararCom && $valor <> 0) { 
			$cor = "#FF0000";
			$style = ' font-weight:bold;';
		} else {
			$cor = "#000";
		}
		
		if($valor == 0){
			$cor = "#000";
			if(!$negrito)  $style = ' ';
		}
       
        
        echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
          <tr>
            <td style="color:#CCCCCC; padding:2px;">R$</td>
            <td style="text-align:right; padding:2px; color: '.$cor.';'.$style.'">'.number_format($valor, 2, ',', '.').'</td>
          </tr>
        </table>';
        
    }
	
    function FormataSaldo($valor,$negrito = false, $compararCom = 0){
        
		//echo " -- $valor -- $compararCom --";
		
        $style = ' ';
        if($negrito) $style = ' font-weight:bold;';
		
        if($valor < $compararCom && $valor <> 0) { 
			$cor = "#FF0000";
			$style = ' font-weight:bold;';
		} else {
			$cor = "#000";
		}
		
		if($valor == 0){
			$cor = "#000";
			if(!$negrito)  $style = ' ';
		}
       
        
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