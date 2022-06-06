<?php 
class RelacionamentoPedagogico extends Core {
	
  function ListarTodas() {		
  $this->sql = "select ".$this->campos." 
				  from 
						relacionamentos_pedagogico rp 
				  INNER JOIN pessoas p ON (p.idpessoa = rp.idpessoa)
				  LEFT OUTER JOIN usuarios_adm ua ON (ua.idusuario = rp.idusuario)
				  where rp.ativo = 'S'
	";
		
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
	//Sempre trazendo as próximas ações da semana -------
	if ($_GET["todas"] == 1) {
		$this->sql .= " and rp.proxima_acao  >= '".date("Y-m-d")."' 
		and rp.proxima_acao  <= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 6, date("Y")))."'";
	} elseif ($_GET["idpessoa"]) {
		$this->sql .=" and rp.idpessoa='".$_GET["idpessoa"]."'";
	} elseif($_GET["idmensagem"]) {
		$this->sql .=" and rp.idmensagem='".$_GET["idmensagem"]."'";
	}

	//------
	$this->groupby = "rp.idmensagem";
	return $this->retornarLinhas();
  }
  
  function Retornar() {
	$this->sql = "select ".$this->campos." from relacionamentos_pedagogico where ativo = 'S' and idmensagem = '".$this->id."'";			
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
  
  //Para pesquisa de matriculas
  function BuscarMatricula() {	  
	$this->sql = "select 
					p.idpessoa as 'key', CONCAT(p.nome,' - ', p.idpessoa,' - ', p.documento) as value 
				  from
					pessoas p 
				  where 
					 (p.nome like '%".$_GET["tag"]."%' OR p.idpessoa like '%".$_GET["tag"]."%' OR p.documento like '%".$_GET["tag"]."%') AND 
					 p.ativo = 'S'";
	$this->limite = -1;
	$this->ordem_campo = "value";
	$this->groupby = "value";
	
	$dados = $this->retornarLinhas();						
	return json_encode($dados);
  }
  
  function adicionarMensagem(){
	  $this->sql = "INSERT INTO 
					relacionamentos_pedagogico
					SET
					idpessoa = '".$this->post["idpessoa"]."',
					data_cad = NOW(), 
					ativo = 'S', 
					mensagem = '".$this->post["mensagem"]."',  
					proxima_acao = '".formataData($this->post["proxima_acao"], "en", 0)."', 
					idusuario = '".$this->idusuario."'";
	$salvar = $this->executaSql($this->sql);
	
	if($salvar) {
	  $this->retorno["sucesso"] = true;
	  $this->retorno["mensagem"] = "mensagem_adicionada_sucesso";
	} else {
	  $this->retorno["sucesso"] = false;
	  $this->retorno["mensagem"] = "mensagem_adicionada_erro";	
	}
	return $this->retorno;
  }
  
  function removerMensagem($idmensagem) {
	$this->sql = "update 
					relacionamentos_pedagogico
				  set 
					ativo = 'N'
				  where 
					idmensagem = '".$idmensagem."'";
	$remover = $this->executaSql($this->sql);

	if($remover) {
	  $this->retorno["sucesso"] = true;
	  $this->retorno["mensagem"] = "mensagem_removida_sucesso";
	} else {
	  $this->retorno["sucesso"] = false;
	  $this->retorno["mensagem"] = "mensagem_removida_erro";	
	}
	return $this->retorno;		
  }
	
}
?>