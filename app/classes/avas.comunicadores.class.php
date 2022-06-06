<?php
class Comunicadores extends Ava {
	
  var $idava = NULL;
		
  function ListarTodasComunicador() {		
	$this->sql = "select 
					".$this->campos." 
				  from
					avas_comunicadores c
					inner join avas a on (c.idava = a.idava)
				  where 
					c.ativo = 'S' and 
					a.idava = ".$this->idava;
		
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
		
	$this->groupby = "c.idcomunicador";
	return $this->retornarLinhas();
  }
	
  function RetornarComunicador() {
	$this->sql = "select 
					".$this->campos."
				  from
					avas_comunicadores c
					inner join avas a on c.idava = a.idava
				  where 
					c.ativo = 'S' and 
					c.idcomunicador = '".$this->id."' and 
					a.idava = ".$this->idava;
	return $this->retornarLinha($this->sql);
  }
	
  function CadastrarComunicador() {
	return $this->SalvarDados();	
  }
	
  function ModificarComunicador() {
	return $this->SalvarDados();	
  }
	
  function RemoverComunicador() {
	return $this->RemoverDados();	
  }
	
}

?>