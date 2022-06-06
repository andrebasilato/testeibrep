<?
class Relatorios extends Core
{

	function ListarTodas() {		
		$this->sql = "SELECT ".$this->campos." FROM
							relatorios where ativo='S' and idusuario = '".$this->idusuario."' and modulo = '".$this->url[0]."' ";
		
		$this->aplicarFiltrosBasicos();
		
		$this->groupby = "idrelatorio";
		return $this->retornarLinhas();
	}

	function salvarRelatorio() {
		$uri_array = explode("/",$_SERVER['REQUEST_URI']);
		$this->sql = "select idrelatorio from relatorios where uri = '".$_SERVER['REQUEST_URI']."' and idusuario = '".$this->idusuario."' and modulo = '".$uri_array[1]."' and ativo = 'S' ";
		$linha = $this->retornarLinha($this->sql);
		
		if($linha['idrelatorio']) {
			$retorno['erro'] = true;
			$retorno['erro_texto'] = 'relatorio_existente_erro';
			return $retorno;
		}
	
		if($this->post['nome']) {				
			$this->sql = "insert into relatorios set ativo = 'S', data_cad = NOW(), nome = '".$this->post['nome']."', uri = '".$_SERVER['REQUEST_URI']."', idusuario = '".$this->idusuario."', modulo = '".$uri_array[1]."' ";
			$salvar = $this->executaSql($this->sql);
		}
		
		if(!$salvar) {
			$retorno['erro'] = true;
			$retorno['erro_texto'] = 'salvar_relatorio_erro';				
		} else {
			$this->monitora_oque = 1;
			$this->monitora_onde = 19;
			$this->monitora_qual = mysql_insert_id();
			$this->Monitora();
			$retorno['sucesso'] = true;
		}			
		
		return $retorno;
	}	
	
	function Retornar() {
		$this->sql = "SELECT ".$this->campos."
							FROM
							 relatorios where ativo='S' and idrelatorio='".$this->id."'";			
		return $this->retornarLinha($this->sql);
	}
	
	function Remover($idrelatorio) {
		$this->sql = "update relatorios set ativo = 'N' where idrelatorio = '".$idrelatorio."' ";
		$salvar = $this->executaSql($this->sql);
		if($salvar) {
			$this->monitora_oque = 3;
			$this->monitora_onde = 19;
			$this->monitora_qual = $idrelatorio;
			$this->Monitora();
			$retorno['sucesso'] = true;
		} else {
			$retorno['erro'] = true;
			$retorno['erro_texto'] = 'remover_relatorio_erro';	
		}
		return $retorno;
	}
	
	function atualiza_visualizacao_relatorio() {
		$this->sql = "select idrelatorio from relatorios where ativo = 'S' and idusuario = '".$this->idusuario."' and uri = '".$_SERVER['REQUEST_URI']."' ";
		$linha = $this->retornarLinha($this->sql);
		if($linha['idrelatorio']) {
			$this->sql = "update relatorios set ultimo_view = NOW() where idrelatorio = '".$linha['idrelatorio']."' ";
			$salvar = $this->executaSql($this->sql);
		}
	}
	
}

?>