<?
class Perguntas_Pesquisas extends Core
{
	var $id = NULL;
		
	function ListarTodas() {		
		$this->sql = "SELECT ".$this->campos." FROM
							perguntas_pesquisas where ativo='S'";
		
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
		
		$this->groupby = "idpergunta";
		return $this->retornarLinhas();
	}
	
	
	function Retornar() {
		$this->sql = "SELECT ".$this->campos."
							FROM
							 perguntas_pesquisas where ativo='S' and idpergunta='".$this->id."'";			
		return $this->retornarLinha($this->sql);
	}
	
	function Cadastrar() {
		return $this->SalvarDados();	
	}
	
	function Modificar() {
		if($this->post['tipo'] == 'S'){ //SE MUDAR DE OBJ PARA SUBJ DESATIVO AS OPCOES
		   $sql = "SELECT COUNT(idopcao) FROM pesquisas_perguntas_opcoes WHERE idpergunta = ".$this->id;
		   $query = mysql_query($sql);
		   if (mysql_num_rows($query) > 0)
		       $this->desativarOpcoesPergunta();
		}
		return $this->SalvarDados();	
	}
	
	function Remover() {
		return $this->RemoverDados();	
	}
	
	function cadastrarOpcao($idpergunta, $numero, $titulo) {
		if ($numero && $titulo) { 
			$sql = "insert into pesquisas_perguntas_opcoes set idpergunta = '$idpergunta', numero = '$numero', titulo = '$titulo' ";
			$query = $this->executaSql($sql);
			if($query){
				$this->retorno["sucesso"] = true;
			} else {
			  $this->retorno["erro"] = true;
			  $this->retorno["erros"][] = $this->sql;
			  $this->retorno["erros"][] = mysql_error();
			}
			return $this->retorno;
		}else
			$this->retorno["sucesso"] = false;
			
		return $this->retorno;
		
	}
	
	function removerOpcao() {
		$sql = "delete from pesquisas_perguntas_opcoes where idopcao = '".$this->post['remover']."' ";
		$query = $this->executaSql($sql);	
	}
	
	function ListarOpcoes($idpergunta) {		
		$this->sql = "SELECT ".$this->campos." FROM
							pesquisas_perguntas_opcoes where idpergunta = $idpergunta ";
		
		
		$this->ordem = "asc";
		$this->ordem_campo = "numero";
		$this->groupby = "idopcao";
		return $this->retornarLinhas();
	}
	
	function ListarPesquisasPergunta($idpergunta) {	
		$this->sql = "SELECT ".$this->campos." FROM
							pesquisas_perguntas	pp
							inner join perguntas_pesquisas p on pp.idpergunta = p.idpergunta					
							where pp.idpergunta = ".intval($idpergunta)." and pp.ativo = 'S' and p.ativo = 'S' and p.ativo_painel = 'S' ";	

		$this->groupby = "pp.idpesquisa";
		return $this->retornarLinhas();
	}
	

	function ListarPerguntaGrafico($idpergunta, $idpesquisa, $idempreendimento, $de, $ate) {

		/* TOTAL para calculo da porcentagem */
		$sql_tot = "select count(*) as total,pr.idpesquisa_pessoa ";		
		$sql_tot .= " from pesquisas_respostas pr ";
				if ($idpesquisa)
					$sql_tot .= " inner join pesquisas_fila pf on pr.idpesquisa_pessoa = pf.idpesquisa_pessoa and pf.idpesquisa = ".intval($idpesquisa)." ";
				if($idempreendimento) {
					$sql_tot .= " left outer join repasses rep ON (rep.idrepasse = pf.idrepasse )
							 left outer join agendamentos_vistorias av ON (av.idvistoria = pf.idvistoria)
							 left outer join agendamentos_visitas avi ON (avi.idvisita= pf.idvisita)
							 left outer join reservas re ON (re.idreserva = pf.idreserva OR re.idreserva = rep.idreserva OR re.idreserva = av.idreserva OR re.idreserva = avi.idreserva)
							 left outer join empreendimentos_unidades eu ON (eu.idunidade = re.idunidade)
							 left outer join empreendimentos_blocos eb ON (eb.idbloco = eu.idbloco)
							 left outer join empreendimentos_etapas ee ON (ee.idetapa = eb.idetapa)
							 left outer join atendimentos at ON (at.idatendimento = pf.idatendimento)
							 left outer join empreendimentos e ON (e.idempreendimento = ee.idempreendimento AND e.idempreendimento =".intval($idempreendimento).")";
				}
		$sql_tot .= " where pr.idpergunta = ".intval($idpergunta)." ";
		
		if ($de) {
			$de_t = formataData($de, 'en', 0);
			$sql_tot .= " and pr.data_cad >= '$de_t'  ";
		}
		
		if ($ate) {
			$ate_t = formataData($ate, 'en', 0);
			$sql_tot .= " and pr.data_cad <= '$ate_t'  ";
		}

		//echo  $sql_tot;
		$query_tot = mysql_query($sql_tot) or die(mysql_error());
		$tot = mysql_fetch_assoc($query_tot);		
		/* FIM TOTAL */
		

		
		$sql = "SELECT pp.idopcao, count(*) as tot, ppo.titulo as opcao,pp.idpesquisa_pessoa ";
		$sql .= " FROM pesquisas_respostas pp ";	
				if ($idpesquisa)
					$sql .= " inner join pesquisas_fila pf on pp.idpesquisa_pessoa = pf.idpesquisa_pessoa and pf.idpesquisa = ".intval($idpesquisa)." ";
				if($idempreendimento) {
					$sql .= "left outer join repasses rep ON (rep.idrepasse = pf.idrepasse )
							 left outer join agendamentos_vistorias av ON (av.idvistoria = pf.idvistoria)
							 left outer join agendamentos_visitas avi ON (avi.idvisita= pf.idvisita)
							 left outer join reservas re ON (re.idreserva = pf.idreserva OR re.idreserva = rep.idreserva OR re.idreserva = av.idreserva OR re.idreserva = avi.idreserva)
							 left outer join empreendimentos_unidades eu ON (eu.idunidade = re.idunidade)
							 left outer join empreendimentos_blocos eb ON (eb.idbloco = eu.idbloco)
							 left outer join empreendimentos_etapas ee ON (ee.idetapa = eb.idetapa)
							 left outer join atendimentos at ON (at.idatendimento = pf.idatendimento)
							 left outer join empreendimentos e ON (e.idempreendimento = ee.idempreendimento AND e.idempreendimento =".intval($idempreendimento).")";
				}					
		$sql .= " left join pesquisas_perguntas_opcoes ppo on pp.idopcao = ppo.idopcao					
							where pp.idpergunta = ".intval($idpergunta)." ";
							
		if ($de) {
			$de = formataData($de, 'en', 0);
			$sql .= " and pp.data_cad >= '$de'  ";
		}
		
		if ($ate) {
			$ate = formataData($ate, 'en', 0);
			$sql .= " and pp.data_cad <= '$ate'  ";
		}
		
		$sql .= " group by pp.idpergunta, pp.idopcao ";

		$query = mysql_query($sql) or die(mysql_error());
		while($linha = mysql_fetch_assoc($query)) {
			$linha["porcentagem"] = number_format(($linha["tot"]/$tot["total"]) * 100 );
			$retorno[] = $linha;			
		}
		
		return $retorno;
	}
	
	function retornarUltimaPerguntaDeUsuario($idacao){
		$sqlPergunta = "SELECT * FROM monitora_adm WHERE idusuario = ".$this->idusuario." AND idacao = ".$idacao." AND idonde = 15 ORDER BY data_cad DESC LIMIT 1";
		$queryPergunta = mysql_query($sqlPergunta) or die();
		return mysql_fetch_assoc($queryPergunta);
	}
	
	function desativarOpcoesPergunta(){
		$sql = "UPDATE pesquisas_perguntas_opcoes SET ativo = 'N' WHERE idpergunta = ".$this->id;
		mysql_query($sql) or die('Não foi possivel remover opcoes da pergunta.');
	}
	
	function ativarDesativar($idpergunta, $idopcao) {

		
		$this->sql = "select * from pesquisas_perguntas_opcoes where idpergunta = ".intval($idpergunta)." and idopcao = ".intval($idopcao);
		$linhaAntiga = $this->retornarLinha($this->sql);
			
		if($linhaAntiga["ativo_painel"] == "S"){
		   $ativo_painel = "N";
		} else {
		  $ativo_painel = "S";
		}
		
		$this->sql = "update pesquisas_perguntas_opcoes set ativo_painel = '".$ativo_painel."' where idpergunta = ".intval($idpergunta)." and idopcao = ".intval($idopcao);
		$executa = $this->executaSql($this->sql);
		
		$this->sql = "select * from pesquisas_perguntas_opcoes where idpergunta = ".intval($idpergunta)." and idopcao = ".intval($idopcao);
		$linhaNova = $this->retornarLinha($this->sql);
		
		$info = array();

		if($executa){
			
		   $this->monitora_oque = 2;
		   $this->monitora_qual = $idopcao;
		   $this->monitora_dadosantigos = $linhaAntiga;
		   $this->monitora_dadosnovos = $linhaNova;
		   $this->Monitora();
		
		   $info['sucesso'] = true;
		   $info['ativo'] = $linhaNova["ativo_painel"];
		   $info['opcao'] = $linhaNova["idopcao"];
		} else {
		   $info['sucesso'] = false;
		   $info['ativo'] = $ativo_painel;
		   $info['opcao'] = $linhaNova["idopcao"];
		}
		
		return json_encode($info);
		
	}
	
	function editarOrdemOpcoes($idpergunta) {
		
		foreach($this->post["numero"] as $idopcao => $numero) {
		
		  $this->sql = "select * from pesquisas_perguntas_opcoes where idpergunta = ".$idpergunta." and idopcao = ".intval($idopcao);
		  $linhaAntiga = $this->retornarLinha($this->sql);
		  
		  $this->sql = "update pesquisas_perguntas_opcoes set numero = ".intval($numero)." where idpergunta = ".$idpergunta." and idopcao = ".intval($idopcao);
		  $executa = $this->executaSql($this->sql);
		  
		  $this->sql = "select * from pesquisas_perguntas_opcoes where idpergunta = ".$idpergunta." and idopcao = ".intval($idopcao);
		  $linhaNova = $this->retornarLinha($this->sql);
  
		  if($executa){
			  
			 $this->monitora_oque = 2;
			 $this->monitora_qual = $idopcao;
			 $this->monitora_dadosantigos = $linhaAntiga;
			 $this->monitora_dadosnovos = $linhaNova;
			 $this->Monitora();
		  
			 $retorno["sucesso"] = true;
		  } else {
			 $retorno["erro"] = true;
		  }
		}
		return $retorno;
	}
	
	function verificaPerguntaRespondida($idpergunta) {
		$this->sql = "select count(pr.idpesquisa_resposta) as total from pesquisas_respostas pr						
						inner join pesquisas_fila pf on pf.idpesquisa_pessoa = pr.idpesquisa_pessoa
						inner join pesquisas_perguntas pp on pp.idpesquisa = pf.idpesquisa and pr.idpergunta = pp.idpergunta
						where pr.idpergunta = '".$idpergunta."' and pp.ativo = 'S' ";
		$linha = $this->retornarLinha($this->sql);
		return $linha['total'];
	}
	
	function verificaPerguntaRespondidaPesquisa($idpesquisa_pergunta, $idpesquisa) {
		$this->sql = "select count(pr.idpesquisa_resposta) as total from pesquisas_respostas pr						
						inner join pesquisas_fila pf on pf.idpesquisa_pessoa = pr.idpesquisa_pessoa
						inner join pesquisas_perguntas pp on pp.idpesquisa = pf.idpesquisa and pr.idpergunta = pp.idpergunta
						where pp.idpesquisa_pergunta = '".$idpesquisa_pergunta."' and pf.idpesquisa = '".$idpesquisa."' and pp.ativo = 'S' ";
		$linha = $this->retornarLinha($this->sql);
		return $linha['total'];
	}
	
}

?>