<?php 
class Empresas extends Core {
		
  function ListarTodas() {		
	$this->sql = "select 
					".$this->campos." 
				  from 
					empresas e
					inner join sindicatos i on (e.idsindicato = i.idsindicato) ";
					
	if($this->idusuario)
		$this->sql .= "	inner join usuarios_adm ua on ua.idusuario = ".$this->idusuario."
						left join usuarios_adm_sindicatos uai on i.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario ";
	
	$this->sql .= "	where					
						e.ativo = 'S'";
						
	if($this->idusuario)
		$this->sql .= " and (ua.gestor_sindicato = 'S' or uai.idusuario is not null) ";
		
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
		
	$this->groupby = "e.idempresa";
	return $this->retornarLinhas();
  }
	
	
  function Retornar() {
	$this->sql = "select 
					".$this->campos." 
				  from 
					empresas e
					inner join sindicatos i on (e.idsindicato = i.idsindicato) ";
	
	if($this->idusuario)
		$this->sql .= "	inner join usuarios_adm ua on ua.idusuario = ".$this->idusuario."
						left join usuarios_adm_sindicatos uai on i.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario ";
	
	$this->sql .= " where 
					e.ativo = 'S' and
					e.idempresa = '".$this->id."'";	

	if($this->idusuario)
		$this->sql .= " and (ua.gestor_sindicato = 'S' or uai.idusuario is not null) ";
					
	return $this->retornarLinha($this->sql);
  }
	
  function Cadastrar() {
	if($this->post["documento_tipo"] == "cnpj") {
	  $this->config["formulario"][0]["campos"][4]["validacao"] = $this->config["formulario"][0]["campos"][5]["validacao"];
	  $this->post["documento"] = $this->post["documento_cnpj"];  
	  
	  unset($this->post["documento_cnpj"]);
	  unset($this->config["formulario"][0]["campos"][5]);
	} else { 
	  unset($this->post["documento_cnpj"]);
	  unset($this->config["formulario"][0]["campos"][5]);	
	}

	//Para comparação da chave mútipla (nome + documento)
	$this->post['documento'] = str_replace(array(".", "-","/"),"",$this->post['documento']);
	$this->post['documento_cnpj'] = str_replace(array(".", "-","/"),"",$this->post['documento_cnpj']);

	return $this->SalvarDados();	
  }
	
  function Modificar() {
	unset($this->config["formulario"][0]["campos"][3]);
	unset($this->config["formulario"][0]["campos"][4]);
	unset($this->config["formulario"][0]["campos"][5]);
	
	return $this->SalvarDados();	
  }
	
  function Remover() {
	return $this->RemoverDados();	
  } 
	
}

?>