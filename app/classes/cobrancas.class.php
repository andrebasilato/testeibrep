<?php 
class Cobrancas extends Core {
		
  function ListarTodas() {		
	$this->sql = "select ".$this->campos." 
				  from 
						cobrancas_log c 
				  INNER JOIN matriculas m ON (m.idmatricula = c.idmatricula)
				  INNER JOIN pessoas p ON (p.idpessoa = m.idpessoa)
				  INNER JOIN usuarios_adm ua ON (ua.idusuario = c.idusuario) ";
				  
	if($this->idusuario)
		$this->sql .= " 
				    inner join escolas po on (m.idescola = po.idescola)
					left join usuarios_adm_sindicatos uai on po.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario	";
				  
	$this->sql .= " where c.ativo = 'S' ";
					
	if($this->idusuario)
		$this->sql .= " and (ua.gestor_sindicato = 'S' or uai.idusuario is not null) and ua.idusuario = ".$this->idusuario." "; 
		
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
	if ($_GET["todas"] == 1){
		$this->sql .= " and c.proxima_acao  >= '".date("Y-m-d")."' and c.proxima_acao  <= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 6, date("Y")))."'";
	}else if ($_GET["idmatricula"]){
		$this->sql .=" and m.idmatricula='".$_GET["idmatricula"]."'";
	}else if($_GET["idcobranca"]){
		$this->sql .=" and c.idcobranca='".$_GET["idcobranca"]."'";
	}
	//------
	$this->ordem = "desc";
	$this->ordem_campo = "c.proxima_acao";
	$this->groupby = "c.idcobranca";
	//echo $this->sql;exit();
	return $this->retornarLinhas();
  }
  
  function Retornar() {
	$this->sql = "select ".$this->campos." 
					from cobrancas_log c ";
	
	if($this->idusuario)
		$this->sql .= " INNER JOIN matriculas m ON (m.idmatricula = c.idmatricula)
				    INNER JOIN usuarios_adm ua ON (ua.idusuario = c.idusuario)
				    inner join escolas po on (m.idescola = po.idescola)
					left join usuarios_adm_sindicatos uai on po.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario	";
	
	$this->sql .= " where c.ativo = 'S' and c.idcobranca = '".$this->id."'";

	if($this->idusuario)
		$this->sql .= " and (ua.gestor_sindicato = 'S' or uai.idusuario is not null) and ua.idusuario = ".$this->idusuario." "; 
	
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
					m.idmatricula as 'key', CONCAT(p.nome,' - ',m.idmatricula) as value 
				  from
					matriculas m 
					inner join pessoas p on (m.idpessoa = p.idpessoa) ";
	
	if($this->idusuario)
	$this->sql .= " inner join escolas po on (m.idescola = po.idescola)
					inner join usuarios_adm ua on ua.idusuario = ".$this->idusuario."
					left join usuarios_adm_sindicatos uai on po.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario	";
	
	$this->sql .= " where 
					 (p.nome like '%".$_GET["tag"]."%' OR m.idmatricula like '%".$_GET["tag"]."%') AND 
					 m.ativo = 'S'";
					 
	if($this->idusuario)
		$this->sql .= " and (ua.gestor_sindicato = 'S' or uai.idusuario is not null) and ua.idusuario = ".$this->idusuario." "; 
					 
	$this->limite = -1;
	$this->ordem_campo = "value";
	$this->groupby = "value";
	
	$dados = $this->retornarLinhas();						
	return json_encode($dados);
  }
  
  function RetornarContas($idmatricula){
	  $this->sql = "select 
					c.*,
					ef.nome as evento,
					bc.nome as bandeira_cartao,
					b.nome as banco,
					cw.nome as situacao,
					cor_nome,
					cor_bg,
					pcm.valor as valor_matricula,
					(select count(1) from contas c_interno where c_interno.idpagamento_compartilhado = c.idpagamento_compartilhado and c_interno.ativo = 'S') as total_contas_compartilhadas
				  from 
					contas c
					inner join contas_workflow cw on (c.idsituacao = cw.idsituacao)
					inner join eventos_financeiros ef on (c.idevento = ef.idevento)
					left outer join bandeiras_cartoes bc on (c.idbandeira = bc.idbandeira)
					left outer join bancos b on (c.idbanco = b.idbanco)
					left outer join pagamentos_compartilhados_matriculas pcm on (c.idpagamento_compartilhado = pcm.idpagamento and pcm.idmatricula = ".$idmatricula." and pcm.ativo = 'S')
				  where 
					(c.idmatricula = ".$idmatricula." or pcm.idmatricula is not null) and 
					c.ativo = 'S'";
	$this->ordem = "asc";
	$this->ordem_campo = "c.data_vencimento";
	$this->limite = -1;
	$contas = $this->retornarLinhas();
	$matricula["contas"] = array();
	$matricula["total_mensalidades"] = 0;
	foreach($contas as $conta) {
	  if($conta["idevento"] == $eventoFinanceiroMensalidade["idevento"]) $matricula["total_mensalidades"] += $conta["valor"];
	  $matricula["contas"][$conta["idevento"]][] = $conta;
	}
	return $matricula["contas"];
	  
  }
  
  function adicionarCobranca(){
	  $this->sql = "INSERT INTO 
					cobrancas_log
					SET
					idmatricula = '".$this->post["idmatricula"]."',
					idusuario = '".$this->idusuario."', 
					data_cad = NOW(), 
					ativo = 'S', 
					mensagem = '".$this->post["mensagem"]."', 
					proxima_acao = '".formataData($this->post["proxima_acao"], "en", 0)."'";
	$salvar = $this->executaSql($this->sql);
	
	if($salvar) {
	  $this->retorno["sucesso"] = true;
	  $this->retorno["mensagem"] = "cobranca_adicionada_sucesso";
	} else {
	  $this->retorno["sucesso"] = false;
	  $this->retorno["mensagem"] = "cobranca_adicionada_erro";	
	}
	return $this->retorno;
  }
  
  function removerCobranca($idcobranca) {
	$this->sql = "update 
					cobrancas_log
				  set 
					ativo = 'N'
				  where 
					idcobranca = '".$idcobranca."'";
	$remover = $this->executaSql($this->sql);

	if($remover) {
	  $this->retorno["sucesso"] = true;
	  $this->retorno["mensagem"] = "cobranca_removida_sucesso";
	} else {
	  $this->retorno["sucesso"] = false;
	  $this->retorno["mensagem"] = "cobranca_removida_erro";	
	}
	return $this->retorno;		
  }
  
    public function retornarPessoaPorMatricula($idmatricula) {
        $sql = '
            select 
                p.* 
            from 
                matriculas m                     
            inner join 
                pessoas p 
                    on 
                        m.idpessoa = p.idpessoa 
            where 
                m.idmatricula = "' . $idmatricula . '" ';
        return $this->retornarLinha($sql);
    }
  
    public function retornarContatosPorMatricula($idmatricula) {
        $sql = '
            select 
                c.*, tc.nome as tipo 
            from 
                matriculas m 
            inner join
                pessoas_contatos c 
                    on 
                        m.idpessoa = c.idpessoa and c.ativo = "S" 
            inner join 
                tipos_contatos tc 
                    on 
                        c.idtipo = tc.idtipo 
            where 
                m.idmatricula = "' . $idmatricula . '" ';
        $resultado = $this->executaSql($sql);
        while ($contato = mysql_fetch_assoc($resultado)) {
            $retorno[] = $contato;
        }
        return $retorno;
    }
	
}

?>