<?php
class Enquetes extends Ava {
	
  var $idava = NULL;
  
  function ListarTodosEnquetes() {		
	$this->sql = "select 
					".$this->campos." 
				  from
					avas_enquetes e
					inner join avas a on (e.idava = a.idava)
				  where 
					e.ativo = 'S' and 
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
		
	$this->groupby = "e.idenquete";
	return $this->retornarLinhas();
  }
	
  function RetornarEnquete() {
	$this->sql = "select 
					".$this->campos."
				  from
					avas_enquetes e
					inner join avas a on e.idava = a.idava
				  where 
					e.ativo = 'S' and 
					e.idenquete = '".$this->id."' and 
					a.idava = ".$this->idava;	
	return $this->retornarLinha($this->sql);
  }
	
  function CadastrarEnquete() {
	$this->post["idava"] = $this->idava;  
	
	return $this->SalvarDados();	
  }
	
  function ModificarEnquete() {
	$this->post["idava"] = $this->idava;  
	
	return $this->SalvarDados();	
  }
	
  function RemoverEnquete() {
	return $this->RemoverDados();	
  }
  
  function ListarTodosEnquetesOpcoes() {		
	$this->sql = "select 
					".$this->campos." 
				  from
					avas_enquetes_opcoes eo
					inner join avas_enquetes e on (eo.idenquete = e.idenquete)
					inner join avas a on (e.idava = a.idava)
				  where 
					eo.ativo = 'S' and 
					a.idava = ".$this->idava." and
					e.idenquete = ".$this->id;
		
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
		
	$this->groupby = "oe.idopcao";
	return $this->retornarLinhas();
  }
  
  function CadastrarOpcoes() {
	if(!$this->post["ordem"]) {
		$erros[] = "ordem_vazio";
	}
	if(!$this->post["opcao"]) {
	  $erros[] = "opcao_vazio";
	} else {
	  $this->post["opcao"] = "'".$this->post["opcao"]."'";
	}

	if(!empty($erros)) {
		$this->retorno["erro"] = true;
		$this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "insert into
					  avas_enquetes_opcoes
					set
					  data_cad = now(),
					  idenquete = '".$this->id."',
					  ordem = ".$this->post["ordem"].",
					  opcao = ".$this->post["opcao"];
	  if($this->executaSql($this->sql)){
		$this->monitora_qual = mysql_insert_id();
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 1;
		$this->monitora_onde = 176;
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }

  function ModificarOpcoes() {

	foreach($this->post["opcoes"] as $idopcao => $post) {
	  if(!$post["ordem"]) {
		$post["ordem"] = "NULL";
	  }

	  $this->sql = "select * from avas_enquetes_opcoes where idenquete = '".$this->id."' and idopcao = ".intval($idopcao);
	  $linhaAntiga = $this->retornarLinha($this->sql);

	  $this->sql = "update
					  avas_enquetes_opcoes
					set
					  ordem = ".$post["ordem"]."
					where
					  idenquete = '".$this->id."' and
					  idopcao = ".intval($idopcao);
	  $executa = $this->executaSql($this->sql);

	  $this->sql = "select * from avas_enquetes_opcoes where idenquete = '".$this->id."' and idopcao = ".intval($idopcao);
	  $linhaNova = $this->retornarLinha($this->sql);

	  if($executa){
		$this->monitora_oque = 2;
		$this->monitora_onde = 176;
		$this->monitora_qual = $idopcao;
		$this->monitora_dadosantigos = $linhaAntiga;
		$this->monitora_dadosnovos = $linhaNova;
		$this->Monitora();

		$this->retorno["sucesso"] = true;
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }

  function RemoverOpcao() {
	include_once("../includes/validation.php");
	$regras = array(); // stores the validation rules

	//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
	if(!$this->post["remover"])
	  $regras[] = "required,remover,remover_vazio";

	//VALIDANDO FORMULARIO
	$erros = validateFields($this->post, $regras);

	//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
	if(!empty($erros)) {
		$this->retorno["erro"] = true;
		$this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "update avas_enquetes_opcoes set ativo = 'N' where idopcao = ".intval($this->post["remover"]);
	  if($this->executaSql($this->sql)){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 176;
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
	
}

?>