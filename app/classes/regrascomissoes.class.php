<?php 
class Regras_Comissoes extends Core {
		
  function ListarTodas() {		
	$this->sql = "select ".$this->campos." from comissoes_regras where ativo = 'S'";
		
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
		
	$this->groupby = "idregra";
	return $this->retornarLinhas();
  }
	
	
  function Retornar() {
	$this->sql = "select ".$this->campos." from comissoes_regras where ativo = 'S' and idregra = '".$this->id."'";			
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
  
  function ListarValores(){
	$this->sql = "select 
					".$this->campos."
				  from
					comissoes_regras_valores 
				  where 
					ativo = 'S' and
					idregra = ".$this->id;
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
	  
	$this->groupby = "idvalor";
	return $this->retornarLinhas();
  }
  
  function cadastrarValor() {	
	$this->post['valor'] = str_replace(',','.',str_replace('.','',$this->post["valor"]));
	$this->post['porcentagem'] = str_replace(',','.',str_replace('.','',$this->post["porcentagem"]));
	
	$this->sql = "select count(idvalor) as total, idvalor from comissoes_regras_valores where idregra = ".$this->id." and valor = ".$this->post['valor']." and porcentagem = ".$this->post['porcentagem'];
	$totalValores = $this->retornarLinha($this->sql); 
			
	if($totalValores["total"] > 0) {
	  $this->sql = "update 
					  comissoes_regras_valores 
					set
					  ativo = 'S'								
					where 
					  idvalor = '".$totalValores["idvalor"]."' ";
	  $executa = $this->executaSql($this->sql);
	  $this->monitora_qual = $totalValores["idvalor"];					
	} else {
	  $this->sql = "insert into 
					  comissoes_regras_valores 
					set
					  ativo = 'S',
					  idregra = ".$this->id.",
					  data_cad = now(),
					  valor = ".$this->post['valor'].",
					  porcentagem = ".$this->post['porcentagem'];
	  $executa = $this->executaSql($this->sql);
	  $this->monitora_qual = mysql_insert_id();
	}

	if($executa){
	  $this->retorno["sucesso"] = true;
	  $this->monitora_oque = 1;
	  $this->monitora_onde = 136;
	  $this->Monitora();
	} else {
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"][] = $this->sql;
	  $this->retorno["erros"][] = mysql_error();
	}	
	return $this->retorno;
  }
	
  function removerValor() { 
	include_once("../includes/validation.php");		
	$regras = array();
	  
	if(!$this->post["remover"])
	  $regras[] = "required,remover,remover_valor_regra_vazio";
	  
	$erros = validateFields($this->post, $regras);

	if(!empty($erros)){
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "update comissoes_regras_valores set ativo = 'N' where idvalor = ".intval($this->post["remover"]);
	  $executa = $this->executaSql($this->sql);

	  if($executa){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 136;
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
  
  function BuscarCurso() {		
	$this->sql = "select 
					c.idcurso as 'key', 
					c.nome as value 
				  from 
					cursos c 
				  where 
					c.nome LIKE '%".$this->get["tag"]."%' and 
					c.ativo = 'S' and
					not exists (
					  select 
						crc.idregra_curso 
					  from 
						comissoes_regras_cursos crc 
					  where 
						crc.idcurso = c.idcurso and 
						crc.idregra = ".intval($this->id)." and 
						crc.ativo = 'S'
					)";

	$this->limite = -1;
	$this->ordem_campo = "c.nome";
	$this->groupby = "c.idcurso";
	$this->retorno = $this->retornarLinhas();
					
	return json_encode($this->retorno);		
  }
	
  function AssociarCursos($cursos) {
	foreach($cursos as $ind => $idcurso) {
	  $this->sql = "select count(idregra_curso) as total, idregra_curso from comissoes_regras_cursos where idregra = ".intval($this->id)." and idcurso = ".intval($idcurso);
	  $total = $this->retornarLinha($this->sql); 
	  if($total["total"] > 0) {
		$this->sql = "update comissoes_regras_cursos set ativo = 'S' where idregra_curso = ".$total["idregra_curso"];
		$executa = $this->executaSql($this->sql);
		$this->monitora_qual = $total["idregra_curso"];					
	  } else {
		$this->sql = "insert into comissoes_regras_cursos set ativo = 'S', data_cad = now(), idregra = ".intval($this->id).", idcurso = ".intval($idcurso);
		$executa = $this->executaSql($this->sql);
		$this->monitora_qual = mysql_insert_id();
	  }
	  if($executa){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 1;
		$this->monitora_onde = 138;
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }
	
  function DesassociarCursos() {		
	include_once("../includes/validation.php");		
	$regras = array();
	  
	if(!$this->post["remover"])
	  $regras[] = "required,remover,remover_vazio";
	  
	$erros = validateFields($this->post, $regras);

	if(!empty($erros)){
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "update comissoes_regras_cursos set ativo = 'N' where idregra_curso = ".intval($this->post["remover"]);
	  $executa = $this->executaSql($this->sql);

	  if($executa){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 138;
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
	
  function ListarCursosAssociados() {		
	$this->sql = "select 
					".$this->campos." 
				  from
					comissoes_regras cr
					INNER JOIN comissoes_regras_cursos crc on (cr.idregra = crc.idregra)
					INNER JOIN cursos c on (crc.idcurso = c.idcurso) 
				  where 
					crc.ativo = 'S' and 
					cr.idregra = ".intval($this->id);
	  
	$this->limite = -1;
	$this->ordem_campo = "c.nome";
	$this->groupby = "tc.idregra_curso";
	return $this->retornarLinhas();
  }
	
  function BuscarSindicato() {		
	$this->sql = "select 
					i.idsindicato as 'key', 
					i.nome_abreviado as value 
				  from 
					sindicatos i 
				  where 
					i.nome_abreviado LIKE '%".$this->get["tag"]."%' and 
					i.ativo = 'S' and
					not exists (
					  select 
						cri.idregra_sindicato 
					  from 
						comissoes_regras_sindicatos cri 
					  where 
						cri.idsindicato = i.idsindicato and 
						cri.idregra = ".intval($this->id)." and 
						cri.ativo = 'S'
					)";

	$this->limite = -1;
	$this->ordem_campo = "i.nome_abreviado";
	$this->groupby = "i.idsindicato";
	$this->retorno = $this->retornarLinhas();
					
	return json_encode($this->retorno);		
  }
	
  function AssociarSindicatos($sindicatos) {
	foreach($sindicatos as $ind => $idsindicato) {
	  $this->sql = "select count(idregra_sindicato) as total, idregra_sindicato from comissoes_regras_sindicatos where idregra = ".intval($this->id)." and idsindicato = ".intval($idsindicato);
	  $total = $this->retornarLinha($this->sql); 
	  if($total["total"] > 0) {
		$this->sql = "update comissoes_regras_sindicatos set ativo = 'S' where idregra_sindicato = ".$total["idregra_sindicato"];
		$executa = $this->executaSql($this->sql);
		$this->monitora_qual = $total["idregra_sindicato"];					
	  } else {
		$this->sql = "insert into comissoes_regras_sindicatos set ativo = 'S', data_cad = now(), idregra = ".intval($this->id).", idsindicato = ".intval($idsindicato);
		$executa = $this->executaSql($this->sql);
		$this->monitora_qual = mysql_insert_id();
	  }
	  if($executa){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 1;
		$this->monitora_onde = 137;
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }
	
  function DesassociarSindicatos() {		
	include_once("../includes/validation.php");		
	$regras = array();
	  
	if(!$this->post["remover"])
	  $regras[] = "required,remover,remover_vazio";
	  
	$erros = validateFields($this->post, $regras);

	if(!empty($erros)){
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "update comissoes_regras_sindicatos set ativo = 'N' where idregra_sindicato = ".intval($this->post["remover"]);
	  $desassociar = $this->executaSql($this->sql);

	  if($desassociar){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 137;
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
					comissoes_regras cr
					inner join comissoes_regras_sindicatos cri ON (cr.idregra = cri.idregra)
					inner join sindicatos i ON (cri.idsindicato = i.idsindicato) 
				  where 
					cri.ativo = 'S' and 
					cr.idregra = ".intval($this->id);
	  
	  $this->groupby = "cri.idregra_sindicato";
	  return $this->retornarLinhas();
  }
  
}

?>