<?php
class Faq extends Ava {
		
	var $idava = NULL;

	public function ListarTodasFaqs() {
		$this->sql = "SELECT 
						".$this->campos." 
					  FROM
						avas_faqs af
						INNER JOIN avas a ON (a.idava = af.idava)
					  WHERE 
						af.idava = '".$this->idava."' AND
						af.ativo = 'S'";
			
		if(is_array($_GET["q"])) {
		  foreach($_GET["q"] as $campo => $valor) {
			//explode = Retira, ou seja retira a "|" da variavel campo
			$campo = explode("|",$campo);
			$valor = str_replace("'","",$valor);
			// Listagem se o valor for diferente de Todos ele faz um filtro
			if(($valor || $valor === "0") AND $valor <> "todos") {
			  // se campo[0] for = 1 é pq ele tem de ser um valor exato
			  if($campo[0] == 1) {
				$this->sql .= " AND ".$campo[1]." = '".$valor."' ";
				// se campo[0] for = 2, faz o filtro pelo comando like
			  } elseif($campo[0] == 2)  {
				$busca = str_replace("\\'","",$valor);
				$busca = str_replace("\\","",$busca);
				$busca = explode(" ",$busca);
				foreach($busca as $ind => $buscar){
				  $this->sql .= " AND ".$campo[1]." LIKE '%".urldecode($buscar)."%' ";
				}
			  } elseif($campo[0] == 3)  {
				$this->sql .= " AND date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
			  }
			} 
		  }
		}
			
		$this->groupby = "af.idfaq";
		return $this->retornarLinhas();
	}

	public function RetornarFaq() {
		$this->sql = "SELECT 
						".$this->campos."
					  FROM
						avas_faqs af
						INNER JOIN avas a ON (a.idava = af.idava)
					  WHERE 
						af.idfaq = '".$this->id."' AND
						af.idava = '".$this->idava."' AND
						af.ativo = 'S'";			
		return $this->retornarLinha($this->sql);
	}

	public function CadastrarFaq() {
		$this->post["idava"] = $this->idava;  
		  
		return $this->SalvarDados();	
	}

	public function ModificarFaq() {
		$this->post["idava"] = $this->idava;  
		  
		return $this->SalvarDados();	
	}

	public function RemoverFaq() {
		return $this->RemoverDados();	
	}

	function listarFaqsAva($idAva) {
		$this->sql = "SELECT 
								*
					  FROM
						avas_faqs af
					  WHERE 
						af.idava = ". $idAva . " AND
						af.ativo = 'S' AND
						af.exibir_ava = 'S'";			
		return $this->retornarLinhas($this->sql);
	}
	
}

?>