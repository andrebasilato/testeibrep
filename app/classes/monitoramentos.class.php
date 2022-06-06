<?php 
class Monitoramentos extends Core {
		
  function ListarTodas() {		
	$this->sql = "select 
					".$this->campos." 
				  from 
					monitora_adm m
					inner join usuarios_adm u on (m.idusuario = u.idusuario)
				  where 
					1 = 1";
		
	if(is_array($_GET["q"])) {
	  foreach($_GET["q"] as $campo => $valor) {
		//explode = Retira, ou seja retira a "|" da variavel campo
		$campo = explode("|",$campo);
		$valor = str_replace("'","",$valor);
		// Listagem se o valor for diferente de Todos ele faz um filtro
		if(($valor || $valor === "0") and $valor <> "todos") {
		  // se campo[0] for = 1 é pq ele tem de ser um valor exato
		  if($campo[0] == 1) {
			$this->sql .= " and ".$campo[1]." = '".$valor."' ";
			// se campo[0] for = 2, faz o filtro pelo comando like
		  } elseif($campo[0] == 2)  {
			$busca = str_replace("\\'","",$valor);
			$busca = str_replace("\\","",$busca);
			$busca = explode(" ",$busca);
			foreach($busca as $ind => $buscar){
			  $this->sql .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
			}
		  } elseif($campo[0] == 3)  {
			$this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
		  }
		} 
	  }
	}
		
	$this->groupby = "m.idmonitora";
	return $this->retornarLinhas();
  }

  function ListarTodasBaseLog() {		
	$this->sql = "select 
					".$this->campos." 
				  from 
					monitora_adm m
				  where 
					1 = 1";
		
	if(is_array($_GET["q"])) {
	  foreach($_GET["q"] as $campo => $valor) {
		//explode = Retira, ou seja retira a "|" da variavel campo
		$campo = explode("|",$campo);
		$valor = str_replace("'","",$valor);
		// Listagem se o valor for diferente de Todos ele faz um filtro
		if(($valor || $valor === "0") and $valor <> "todos") {
		  // se campo[0] for = 1 é pq ele tem de ser um valor exato
		  if($campo[0] == 1) {
			$this->sql .= " and ".$campo[1]." = '".$valor."' ";
			// se campo[0] for = 2, faz o filtro pelo comando like
		  } elseif($campo[0] == 2)  {
			$busca = str_replace("\\'","",$valor);
			$busca = str_replace("\\","",$busca);
			$busca = explode(" ",$busca);
			foreach($busca as $ind => $buscar){
			  $this->sql .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
			}
		  } elseif($campo[0] == 3)  {
			$this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
		  }
		} 
	  }
	}
		
	$this->groupby = "m.idmonitora";
	return $this->retornarLinhas();
  }
	
  function Retornar() {
	$this->sql = "select 
					".$this->campos." 
				  from 
					monitora_adm m
					inner join usuarios_adm u on (m.idusuario = u.idusuario)
				  where 
					m.idmonitora = '".$this->id."'";		
	return $this->retornarLinha($this->sql);
  }	
  
 function RetornarBaseLog() {
	$this->sql = "select 
					".$this->campos." 
				  from 
					monitora_adm m
				  where 
					m.idmonitora = '".$this->id."'";		
	return $this->retornarLinha($this->sql);
  }	
  
  function RetornarLog($idmonitora) {
	$this->sql = "select * from monitora_adm_log where idmonitora = '".$idmonitora."'";
	$this->limite = -1; 
	$this->ordem = "asc";
	$this->ordem_campo = "idmonitora"; 
	return $this->retornarLinhas();
	
	/*$retorno = array();		
	foreach($logs as $ind => $val) {
	  if($val["campo"] == "forma") {
		$val["de"] = $GLOBALS["empreendimento_tabela_formas"][$GLOBALS["config"]["idioma_padrao"]][$val["de"]];
		$val["para"] = $GLOBALS["empreendimento_tabela_formas"][$GLOBALS["config"]["idioma_padrao"]][$val["para"]];				
	  } elseif($val["campo"] == "idtipo") {
		if($val["de"]){
		  $this->sql = "SELECT nome FROM tabelasdepreco_tipo WHERE idtipo = ".$val["de"];
		  $pais = $this->retornarLinha($this->sql);
		  $val["de"] = $pais["nome"];
		}
		if($val["para"]) {
		  $this->sql = "SELECT nome FROM tabelasdepreco_tipo WHERE idtipo = ".$val["para"];
		  $pais = $this->retornarLinha($this->sql);
		  $val["para"] = $pais["nome"];
		}
	  } elseif($val["campo"] == "valor_metro") {
		if($val["de"] || $val["de"] === "0") $val["de"] = "R$ ".number_format($val["de"], 2,',','.');
		if($val["para"] || $val["para"] === "0") $val["para"] = "R$ ".number_format($val["para"], 2,',','.');	
	  } elseif($val["campo"] == "data_vigencia_de") {
		$val["de"] = formataData($val["de"],"br",0);
		$val["para"] = formataData($val["para"],"br",0);				
	  } elseif($val["campo"] == "data_vigencia_ate") {
		$val["de"] = formataData($val["de"],"br",0);
		$val["para"] = formataData($val["para"],"br",0);				
	  } elseif($val["campo"] == "referencia_comissao") {
		$val["de"] = $GLOBALS["empreendimento_tabela_referencia_comissao"][$GLOBALS["config"]["idioma_padrao"]][$val["de"]];
		$val["para"] = $GLOBALS["empreendimento_tabela_referencia_comissao"][$GLOBALS["config"]["idioma_padrao"]][$val["para"]];	
	  } elseif($val["campo"] == "porcentagem_comissao") {
		if($val["de"] || $val["de"] === "0") $val["de"] = number_format($val["de"], 2,',','.')." %";
		if($val["para"] || $val["para"] === "0") $val["para"] = number_format($val["para"], 2,',','.')." %";
	  } elseif($val["campo"] == "porcentagem") {
		if($val["de"] || $val["de"] === "0") $val["de"] = number_format($val["de"], 2,',','.')." %";
		if($val["para"] || $val["para"] === "0") $val["para"] = number_format($val["para"], 2,',','.')." %";
	  } elseif($val["campo"] == "vencimento") {
		$val["de"] = formataData($val["de"],"br",0);
		$val["para"] = formataData($val["para"],"br",0);				
	  } elseif($val["campo"] == "resto") {
		$val["de"] = $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$val["de"]];
		$val["para"] = $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$val["para"]];				
	  } elseif($val["campo"] == "valorfixo") {
		if($val["de"] || $val["de"] === "0") $val["de"] = "R$ ".number_format($val["de"], 2,',','.');
		if($val["para"] || $val["para"] === "0") $val["para"] = "R$ ".number_format($val["para"], 2,',','.');				
	  } elseif($val["campo"] == "incluirem_formadepagamento") {
		$val["de"] = $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$val["de"]];
		$val["para"] = $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$val["para"]];				
	  } elseif($val["campo"] == "incluirem_totalunidade") {
		$val["de"] = $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$val["de"]];
		$val["para"] = $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$val["para"]];				
	  } elseif($val["campo"] == "incluirem_subtotal") {
		$val["de"] = $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$val["de"]];
		$val["para"] = $GLOBALS["sim_nao"][$GLOBALS["config"]["idioma_padrao"]][$val["para"]];				
	  }			
	  $retorno[] = $val;
	}	
	return $retorno;*/	
  }

  public function iniciaConexao($host,$usuario,$senha,$banco)
    {
       $connect = @mysql_connect($host,$usuario,$senha);
       @mysql_select_db($banco); 

       return $connect;
    }

   public function fechaConexao($conexao)
    {
       @mysql_close($conexao);
    }

}

?>