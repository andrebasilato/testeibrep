<?php
class Relatorio extends Core {

  function gerarRelatorio(){
		
	$sql = "select idsituacao from contas_workflow where pago = 'S' and ativo = 'S' order by idsituacao desc limit 1";
	$situacao = $this->retornarLinha($sql);
	
	$this->sql = "SELECT 
					".$this->campos." 
				  FROM 
					contas c
					INNER JOIN contas_correntes cc ON (c.idconta_corrente = cc.idconta_corrente) 
					INNER JOIN bancos b ON (cc.idbanco = b.idbanco)
				  WHERE 
					c.ativo = 'S' AND 
					c.idsituacao = ". (int) $situacao['idsituacao'];
					
	if(!$_GET["q"]["1|c.idsindicato"] && $_SESSION['adm_gestor_sindicato'] != 'S'){
		$this->sql .= ' and c.idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
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
			$this->sql .= " and ".$campo[1]." = '".$valor."' ";
			// se campo[0] for = 2, faz o filtro pelo comando like
		  } elseif($campo[0] == 2)  {
			$this->sql .= " and ".$campo[1]." like '%".urldecode($valor)."%' ";
		  }
		}  
	  }
	}
	
	if($_GET["filtro_data_vencimento"] == 'HOJ') {
	  $this->sql .= " and c.data_vencimento = '".date("Y-m-d")."'";
	} elseif($_GET["filtro_data_vencimento"] == 'ONT') {
	    $this->sql .= " and date_format(c.data_vencimento,'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
	} elseif($_GET["filtro_data_vencimento"] == 'SET') {
	  $this->sql .= " and date_format(c.data_vencimento,'%Y%m%d') <= '".date("Ymd")."'
					  and date_format(c.data_vencimento,'%Y%m%d') >= '".date("Ymd", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'";
	} elseif($_GET["filtro_data_vencimento"] == 'QUI') {
	    $this->sql .= " and date_format(c.data_vencimento,'%Y-%m-%d') <= '".date("Y-m-d")."'
	                  and date_format(c.data_vencimento,'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
	} elseif($_GET["filtro_data_vencimento"] == 'MAT') {
	  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m")."'";
	} elseif($_GET["filtro_data_vencimento"] == 'MPR') {
	  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
	} elseif($_GET["filtro_data_vencimento"] == 'MAN') {
	  $this->sql .= " and date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
	} elseif($_GET["filtro_data_vencimento"] == 'PER') {
	  if($_GET["de_data_vencimento"]) {
		$this->sql .= " and date_format(c.data_vencimento,'%Y-%m-%d') >= '".formataData($_GET["de_data_vencimento"],'en',0)."'";
	  }
	  if($_GET["ate_data_vencimento"]) {
		$this->sql .= " and date_format(c.data_vencimento,'%Y-%m-%d') <= '".formataData($_GET["ate_data_vencimento"],'en',0)."'";
	  }
	}
	
	if($_GET["filtro_data_pagamento"] == 'HOJ') {
	  $this->sql .= " and c.data_pagamento = '".date("Y-m-d")."'";
	} elseif($_GET["filtro_data_pagamento"] == 'ONT') {
	    $this->sql .= " and date_format(c.data_pagamento,'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
	} elseif($_GET["filtro_data_pagamento"] == 'SET') {
	  $this->sql .= " and date_format(c.data_pagamento,'%Y%m%d') <= '".date("Ymd")."'
					  and date_format(c.data_pagamento,'%Y%m%d') >= '".date("Ymd", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'";
	} elseif($_GET["filtro_data_pagamento"] == 'QUI') {
	    $this->sql .= " and date_format(c.data_pagamento,'%Y-%m-%d') <= '".date("Y-m-d")."'
	                  and date_format(c.data_pagamento,'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
	} elseif($_GET["filtro_data_pagamento"] == 'MAT') {
	  $this->sql .= " and date_format(c.data_pagamento,'%Y-%m') = '".date("Y-m")."'";
	} elseif($_GET["filtro_data_pagamento"] == 'MPR') {
	  $this->sql .= " and date_format(c.data_pagamento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
	} elseif($_GET["filtro_data_pagamento"] == 'MAN') {
	  $this->sql .= " and date_format(c.data_pagamento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
	} elseif($_GET["filtro_data_pagamento"] == 'PER') {
	  if($_GET["de_data_pagamento"]) {
		$this->sql .= " and date_format(c.data_pagamento,'%Y-%m-%d') >= '".formataData($_GET["de_data_pagamento"],'en',0)."'";
	  }
	  if($_GET["ate_data_pagamento"]) {
		$this->sql .= " and date_format(c.data_pagamento,'%Y-%m-%d') <= '".formataData($_GET["ate_data_pagamento"],'en',0)."'";
	  }
	}
	
	$this->groupby = "c.idconta";
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
		if($campo["nome"][0] == "q"){
		  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
		  $campoAux = $_GET["q"][$campoAux];
		  if($campo["sql_filtro"] && $campoAux) {
			if($campo["sql_filtro"] == "array") {
			  $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$campoAux];
			} else {
			  $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
			  $linha = $this->retornarLinha($sql);
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
			  $linha = $this->retornarLinha($sql);
			  $_GET[$campo["nome"]][$ind] = $linha[$campo["sql_filtro_label"]];
			}
		  }
				
		  $campoAux = implode($_GET[$campo["nome"]], ", ");					
		} elseif($_GET[$campo["nome"]] && $campo["array"]){
		  $campoAux = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$_GET[$campo["nome"]]];
		} elseif($_GET[$campo["nome"]] && $campo["sql_filtro"]){
		  $sql = str_replace("%",$_GET[$campo["nome"]],$campo["sql_filtro"]);
		  $linha = $this->retornarLinha($sql);
		  $campoAux = $linha[$campo["sql_filtro_label"]];
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
  }
  
  function RetornarContasCorrentesBanco($idbanco) {
	$this->sql = "select idconta_corrente, nome from contas_correntes where idbanco = ".$idbanco." and ativo = 'S'";		
	$this->limite = -1;
	$this->ordem = 'asc';
	$this->ordem_campo = 'nome';
	
	$contasCorrentes = $this->retornarLinhas();
	
	echo json_encode($contasCorrentes);
  }
  
}

?>