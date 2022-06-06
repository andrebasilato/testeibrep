<?php 
class Produtos extends Core {
		
  function ListarTodas() {		
	$this->sql = "select ".$this->campos." from produtos where ativo = 'S'";
		
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
		
	$this->groupby = "idproduto";
	return $this->retornarLinhas();
  }
	
	
  function Retornar() {
	$this->sql = "select ".$this->campos." from produtos where ativo = 'S' and idproduto = '".$this->id."'";			
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
  
  function AssociarFornecedores() {
	foreach($this->post["fornecedores"] as $idfornecedor) {
	  $this->sql = "select count(idproduto_fornecedor) as total, idproduto_fornecedor from produtos_fornecedores where idproduto = '".$this->id."' and idfornecedor = '".intval($idfornecedor)."'";
	  $totalAssociado = $this->retornarLinha($this->sql); 
	  if($totalAssociado["total"] > 0) {
		$this->sql = "update produtos_fornecedores set ativo = 'S' where idproduto_fornecedor = ".$totalAssociado["idproduto_fornecedor"];
		$associar = $this->executaSql($this->sql);
		$this->monitora_qual = $totalAssociado["idproduto_fornecedor"];					
	  } else {
		$this->sql = "insert into produtos_fornecedores set ativo = 'S', data_cad = now(), idproduto = '".$this->id."', idfornecedor = '".intval($idfornecedor)."'";
		$associar = $this->executaSql($this->sql);
		$this->monitora_qual = mysql_insert_id();
	  }
	  if($associar){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 1;
		$this->monitora_onde = 73;
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }


  function DesassociarFornecedor() {
		
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
	  $this->sql = "update produtos_fornecedores set ativo = 'N' where idproduto_fornecedor = ".intval($this->post["remover"]);
	  $desassociar = $this->executaSql($this->sql);

	  if($desassociar){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 73;
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
	
  function ListarFornecedoresAssociados() {		
	$this->sql = "select 
					".$this->campos." 
				  from
					produtos_fornecedores pf
					inner join fornecedores f ON (pf.idfornecedor = f.idfornecedor)
				  where 
					f.ativo = 'S' and 
					pf.ativo= 'S' and 
					pf.idproduto = ".intval($this->id);
		
	$this->limite = -1;
	$this->ordem = "asc";
	$this->ordem_campo = "f.nome";
	return $this->retornarLinhas();
  }
	
  function BuscarFornecedores() {		
	$this->sql = "select 
					f.idfornecedor as 'key', 
					f.nome as value 
				  from 
					fornecedores f 
				  where 
					f.nome LIKE '%".$this->get["tag"]."%' and 
					f.ativo = 'S' and
					not exists (
					  select 
						pf.idfornecedor 
					  from 
						produtos_fornecedores pf 
					  where 
						pf.idfornecedor = f.idfornecedor and 
						pf.idproduto = '".intval($this->id)."' and 
						pf.ativo = 'S'
					)";

	$this->limite = -1;
	$this->ordem_campo = "f.nome";
	$this->groupby = "f.idfornecedor";
	$this->retorno = $this->retornarLinhas();
					
	return json_encode($this->retorno);		
  }
	
}

?>