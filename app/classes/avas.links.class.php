<?php
class Links extends Ava {
		
  var $idava = NULL;
  
  function ListarTodasLink() {		
	$this->sql = "select 
					".$this->campos." 
				  from
					avas_links l
					inner join avas a on (l.idava = a.idava)
				  where 
					l.ativo = 'S' and 
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
		
	$this->groupby = "l.idlink";
	return $this->retornarLinhas();
  }
	
  function RetornarLink() {
	$this->sql = "select 
					".$this->campos."
				  from
					avas_links l
					inner join avas a on l.idava = a.idava
				  where 
					l.ativo = 'S' and 
					l.idlink = '".$this->id."' and 
					a.idava = ".$this->idava;			
	return $this->retornarLinha($this->sql);
  }
	
  function CadastrarLink() {
	$this->post["idava"] = $this->idava;
	
	return $this->SalvarDados();	
  }
	
  function ModificarLink() {
	$this->post["idava"] = $this->idava;
	
	return $this->SalvarDados();	
  }
	
  function RemoverLink() {
	return $this->RemoverDados();	
  }
  
  function RemoverArquivo($modulo, $pasta, $dados, $idioma) {
    echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);		
  }
	
}

?>