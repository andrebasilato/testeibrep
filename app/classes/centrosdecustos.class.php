<?php 
class Centros_Custos extends Core {
		
  function ListarTodas() {		
	$this->sql = "select ".$this->campos." from centros_custos where ativo = 'S'";
		
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
		
	$this->groupby = "idcentro_custo";
	return $this->retornarLinhas();
  }
	
	
  function Retornar() {
	$this->sql = "select ".$this->campos." from centros_custos where ativo = 'S' and idcentro_custo = '".$this->id."'";			
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
  
  function AssociarSindicato() {
	foreach($this->post["sindicatos"] as $idsindicato) {
	  $this->sql = "select count(idcentro_custo_sindicato) as total, idcentro_custo_sindicato from centros_custos_sindicatos where idcentro_custo = '".$this->id."' and idsindicato = '".intval($idsindicato)."'";
	  $totalAssociado = $this->retornarLinha($this->sql); 
	  if($totalAssociado["total"] > 0) {
		$this->sql = "update centros_custos_sindicatos set ativo = 'S' where idcentro_custo_sindicato = ".$totalAssociado["idcentro_custo_sindicato"];
		$associar = $this->executaSql($this->sql);
		$this->monitora_qual = $totalAssociado["idcentro_custo_sindicato"];					
	  } else {
		$this->sql = "insert into centros_custos_sindicatos set ativo = 'S', data_cad = now(), idcentro_custo = '".$this->id."', idsindicato = '".intval($idsindicato)."'";
		$associar = $this->executaSql($this->sql);
		$this->monitora_qual = mysql_insert_id();
	  }
	  if($associar){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 1;
		$this->monitora_onde = 202;
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }


  function DesassociarSindicato() {
		
	include_once("../includes/validation.php");		
	$regras = array(); // stores the validation rules
		
	//VERIFICANDO SE OPÃ‡ÃƒO REMOVER FOI MARCADA
	if(!$this->post["remover"])
	  $regras[] = "required,remover,remover_vazio";
		
	//VALIDANDO FORMULÃRIO
	$erros = validateFields($this->post, $regras);

	//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
	if(!empty($erros)){
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "update centros_custos_sindicatos set ativo = 'N' where idcentro_custo_sindicato = ".intval($this->post["remover"]);
	  $desassociar = $this->executaSql($this->sql);

	  if($desassociar){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 202;
		$this->monitora_qual = intval($this->post["remover"]);
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }
	
  function ListarSindicatosAssociadas() {		
	$this->sql = "select 
					".$this->campos." 
				  from
					centros_custos_sindicatos cci
					inner join sindicatos i ON (cci.idsindicato = i.idsindicato)
				  where 
					i.ativo = 'S' and 
					cci.ativo= 'S' and 
					cci.idcentro_custo = ".intval($this->id);
		
	$this->limite = -1;
	$this->ordem = "asc";
	$this->ordem_campo = "i.nome";
	return $this->retornarLinhas();
  }
	
  function BuscarSindicatos() {		
	$this->sql = "select 
					i.idsindicato as 'key', 
					i.nome_abreviado as value 
				  from 
					sindicatos i 
				  where 
					i.nome_abreviado LIKE '%".$this->get["tag"]."%' AND 
					i.ativo = 'S' and 
					i.ativo_painel = 'S' and 
					not exists (
					  select 
						cci.idcentro_custo 
					  from 
						centros_custos_sindicatos cci 
					  where 
						cci.idsindicato = i.idsindicato and 
						cci.idcentro_custo = '".intval($this->id)."' and 
						cci.ativo = 'S'
					)";

	$this->limite = -1;
	$this->ordem_campo = "i.nome";
	$this->groupby = "i.idsindicato";
	$this->retorno = $this->retornarLinhas();
					
	return json_encode($this->retorno);		
  }
  
    public function retornarCentroSindicato($idsindicato, $json = false) {
		
		$this->sql = "SELECT 
						cc.idcentro_custo, 
						cc.nome
					  FROM 
						  centros_custos cc
					  WHERE 
						  cc.ativo =  'S' AND
							(
							not exists (
							  select 
								cci.idcentro_custo_sindicato 
							  from 
								centros_custos_sindicatos cci 
							  where 
								cci.ativo = 'S' and 
								cci.idcentro_custo = cc.idcentro_custo
							)	  
							OR
							exists (
							  select 
								cci.idcentro_custo_sindicato 
							  from 
								centros_custos_sindicatos cci 
							  where 
								cci.ativo = 'S' and cci.idsindicato = '" . intval($idsindicato) . "' and
								cci.idcentro_custo = cc.idcentro_custo
							)
						) 
						GROUP BY cc.idcentro_custo 
						order by cc.nome";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
		
		$this->retorno[0]['idcentro_custo'] = -100;
		$this->retorno[0]['nome'] = '- Escolher mais de um centro de custo -';
		
        while ($row = mysql_fetch_assoc($query)) {
            $this->retorno[] = $row;
        }
		
		if (count($this->retorno) == 1)
			$this->retorno = array();	
		
		if ($json)
			echo json_encode($this->retorno);
		else
			return $this->retorno;
    }
}