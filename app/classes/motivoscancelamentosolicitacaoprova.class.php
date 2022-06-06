<?php 
class MotivosCancelamentoSolicitacaoProva extends Core {
		
  function ListarTodas() {		
	$this->sql = "SELECT ".$this->campos." FROM 
						motivos_cancelamento_solicitacao_prova 
				WHERE ativo = 'S'";	
	$this->aplicarFiltrosBasicos();
	$this->groupby = "idmotivo";
	return $this->retornarLinhas();
  }
	
	
  function Retornar() {
	$this->sql = "SELECT ".$this->campos." FROM 
						motivos_cancelamento_solicitacao_prova 
					WHERE ativo = 'S' AND 
					idmotivo = '".$this->id."'";			
	return $this->retornarLinha($this->sql);
  }
	
  function Cadastrar() {
    /*if ($this->post['ativo_painel'] == 'N') {
        $this->post['exibir_aluno'] = 'N';
    }*/
	return $this->SalvarDados();	
  }
	
  function Modificar() {
    /*if ($this->post['ativo_painel'] == 'N') {
        $this->post['exibir_aluno'] = 'N';
    }*/
	return $this->SalvarDados();	
  }
	
  function Remover() {
	return $this->RemoverDados();	
  }
	
}

?>