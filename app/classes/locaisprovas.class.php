<?php 
class LocaisProvas extends Core {
		
  function ListarTodas() {		
	$this->sql = 'SELECT 
					'.$this->campos.' 
				  FROM 
					locais_provas l
					INNER JOIN sindicatos i ON (l.idsindicato = i.idsindicato) 
				  WHERE 
					l.ativo = "S"';
		
	$this->aplicarFiltrosBasicos();
		
	$this->groupby = "l.idlocal";
	return $this->retornarLinhas();
  }
	
	
  function Retornar() {
	$this->sql = 'SELECT 
					'.$this->campos.' 
				  FROM 
					locais_provas l
					INNER JOIN sindicatos i ON (l.idsindicato = i.idsindicato) 
				  WHERE 
					l.ativo = "S" AND 
					l.idlocal = "'.$this->id.'"';		
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
  
  //FUNCOES DE CONTATO DO POLO
	function ListarTiposContatos() {		
		$this->retorno = array();
		$this->sql = 'SELECT * FROM tipos_contatos 
						WHERE 
							ativo = "S" 
						ORDER BY nome ASC';
		$seleciona = $this->executaSql($this->sql);
		while($tipo = mysql_fetch_assoc($seleciona)) {
			$this->retorno[] = $tipo;
		}
		return $this->retorno;
	}
	
	function ListarContatos() {		
		$this->sql = 'SELECT '.$this->campos.' FROM
							escolas_contatos c
							INNER JOIN tipos_contatos tc ON (c.idtipo = tc.idtipo) 
						WHERE 
							c.ativo = "S" AND 
							c.idescola = '.$this->id;
		
		$this->groupby = 'c.idescola';
		return $this->retornarLinhas();
	}
	
	function adicionarContato() {		
		$this->retorno = array();
		$this->sql = 'INSERT INTO escolas_contatos SET
						data_cad=now(), 
						ativo="S", 
						idescola= "'.$this->id.'", 
						idtipo="'.$this->post["idtipo"].'", 
						valor="'.$this->post["valor"].'"';
		$cadastrar = $this->executaSql($this->sql);
		if($cadastrar) {
			$this->retorno["sucesso"] = true;
			$this->monitora_oque = 1;
			$this->monitora_onde = 70;
			$this->monitora_qual = mysql_insert_id();
			$this->Monitora();
		} else {
			$this->retorno["sucesso"] = false;
		}
		return $this->retorno;
	}
	
	function RemoverContato() {		
		$this->sql = 'UPDATE escolas_contatos SET 
							ativo="N" 
					WHERE 
						idcontato="'.(int)$this->post["remover"].'" AND 
						idescola = "'.(int)$this->id.'"';
		if($this->executaSql($this->sql)){
			$remover["sucesso"] = true;	
			$this->monitora_oque = 3;
			$this->monitora_onde = 70;
			$this->monitora_qual = $this->post["remover"];
			$this->Monitora();
		} else {
			$remover["sucesso"] = false;	
		}
		return $remover;
		
	}
	
}

?>