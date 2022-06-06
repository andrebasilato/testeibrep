<?php 
class Cheques extends Core {
	//Atualmente os cheques são contas com a FORMA DE PAGAMENTO CHEQUE(config - select $forma_pagamento)

  function ListarTodas() {		
	$this->sql = "SELECT ".$this->campos." 
				  FROM contas c
				  INNER JOIN contas_workflow cw ON c.idsituacao = cw.idsituacao
				  LEFT OUTER JOIN bancos b ON b.idbanco = c.idbanco
				  LEFT OUTER JOIN matriculas m ON m.idmatricula = c.idmatricula
				  LEFT OUTER JOIN pessoas p ON p.idpessoa = m.idpessoa
	 			WHERE c.forma_pagamento = 4 AND c.ativo = 'S'";
		
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
		
	$this->groupby = "idconta";
	return $this->retornarLinhas();
  }

  function Retornar() {
	$this->sql = "select ".$this->campos." from contas where ativo = 'S' and idconta = '".$this->id."'";			
	return $this->retornarLinha($this->sql);
  }
	
  function Cadastrar() {
	return $this->SalvarDados();	
  }
	
  function Modificar() {
	return $this->SalvarDados();	
  }
	
  function Remover() {
	return $this->RemoverDados();	
  }
	
}

?>