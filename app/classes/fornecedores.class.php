<?php 
class Fornecedores extends Core {
		
  function ListarTodas() {		
	$this->sql = "select 
					".$this->campos." 
				  from 
					fornecedores f
					inner join sindicatos i on (f.idsindicato = i.idsindicato)
				  where 
					f.ativo = 'S'";
		
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
		
	$this->groupby = "f.idfornecedor";
	return $this->retornarLinhas();
  }
	
	
  function Retornar() {
	$this->sql = "select 
					".$this->campos." 
				  from 
					fornecedores f
					inner join sindicatos i on (f.idsindicato = i.idsindicato)
				  where 
					f.ativo = 'S' and
					f.idfornecedor = '".$this->id."'";			
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
  
  function AssociarProdutos() {
	foreach($this->post["produtos"] as $idproduto) {
	  $this->sql = "select count(idproduto_fornecedor) as total, idproduto_fornecedor from produtos_fornecedores where idfornecedor = '".$this->id."' and idproduto = '".intval($idproduto)."'";
	  $totalAssociado = $this->retornarLinha($this->sql); 
	  if($totalAssociado["total"] > 0) {
		$this->sql = "update produtos_fornecedores set ativo = 'S' where idproduto_fornecedor = ".$totalAssociado["idproduto_fornecedor"];
		$associar = $this->executaSql($this->sql);
		$this->monitora_qual = $totalAssociado["idproduto_fornecedor"];					
	  } else {
		$this->sql = "insert into produtos_fornecedores set ativo = 'S', data_cad = now(), idfornecedor = '".$this->id."', idproduto = '".intval($idproduto)."'";
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


  function DesassociarProduto() {
		
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
	
  function ListarProdutosAssociados() {		
	$this->sql = "select 
					".$this->campos." 
				  from
					produtos_fornecedores pf
					inner join produtos p ON (pf.idproduto = p.idproduto)
				  where 
					p.ativo = 'S' and 
					pf.ativo= 'S' and 
					pf.idfornecedor = ".intval($this->id);
		
	$this->limite = -1;
	$this->ordem = "asc";
	$this->ordem_campo = "p.nome";
	return $this->retornarLinhas();
  }
	
  function BuscarProdutos() {		
	$this->sql = "select 
					p.idproduto as 'key', 
					p.nome as value 
				  from 
					produtos p 
				  where 
					p.nome LIKE '%".$this->get["tag"]."%' and 
					p.ativo = 'S' and
					not exists (
					  select 
						pf.idproduto 
					  from 
						produtos_fornecedores pf 
					  where 
						pf.idproduto = p.idproduto and 
						pf.idfornecedor = '".intval($this->id)."' and 
						pf.ativo = 'S'
					)";

	$this->limite = -1;
	$this->ordem_campo = "p.nome";
	$this->groupby = "p.idproduto";
	$this->retorno = $this->retornarLinhas();
					
	return json_encode($this->retorno);		
  }
  
  function RetornarProdutosFornecedor() {
		$this->sql = "SELECT p.idproduto, p.nome
						  FROM produtos p
						  INNER JOIN produtos_fornecedores pf ON ( pf.idproduto = p.idproduto ) 
						  WHERE pf.ativo =  'S'
						  AND pf.idfornecedor = '".$this->id."'";	
		$query = $this->executaSql($this->sql);
		$this->retorno = array();
		while($row = mysql_fetch_assoc($query)){
			$this->retorno[] = $row;
		}
		echo json_encode($this->retorno);
	}
	
}

?>