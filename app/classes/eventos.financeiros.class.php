<?php 
/**
 * CRUD para Eventos Financeiros
 *
 * @package oraculo
 * @author  Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 */
class EventosFinanceiros extends Core
{

    /**
     * current Table for consultings
     */
    const TABLE = 'eventos_financeiros';

    /**
     * Lista todas as linhas que estejam ativas
     * ativo = 'S' e filtra também pela busca
     * passada via $_GET['q']
     *
     * @return array
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function ListarTodas() {		
	
        $this->sql = $this->getSimpleQuery(
            $this->filterParamsByGET()
        ); 
		
	    $this->groupby = 'idevento';
	    return $this->retornarLinhas();
    }

    /**
     * GET params passed by GET HTTP Method and filter
     * the content of list by it
     *
     * @return string | bollean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    protected function filterParamsByGET()
    {
        if (! is_array($_GET['q'])) {
            return false;
        }

        foreach($_GET['q'] as $campo => $valor) {

            $campo = explode('|', $campo);
            $valor = str_replace("'", "", $valor);

            if(('0' === $valor || $valor) 
                and 'todos' != $valor
            ) {

                switch ($campo[0]) {
                    case '1':
                        $this->sql = sprintf(' and %s = "%s"', $campo[1], $valor);
                        break;
                    case '2':
                        $busca = str_replace(
                            array('\\\'', '\\'), 
                            array('', ''),
                            array($valor, $busca)
                        );

                        $busca = explode(' ', $busca[0]);
                        
                        foreach($busca as $ind => $buscar){
                            $this->sql .= ' and '.$campo[1]
                                ." like '%".urldecode($buscar)."%' ";
                        }

                        break;
                    case '3':
                        $this->sql .= ' and date_format('
                            .$campo[1].",'%d/%m/%Y') = '".$valor."' ";
                        break;
                }
            } 
        }

        return $this->sql;
    }

    /**
     * Return Simple Query Base to construct
     * querys more especificly
     *
     * @param string $complement
     *
     * @return string
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    protected function getSimpleQuery($complement = '')
    {
        return sprintf(
            "SELECT  %s  FROM `%s` WHERE ativo = 'S'  %s", 
            $this->campos, 
            self::TABLE,
            $complement
        );
    }
    
    public function Retornar() {
		$this->sql = $this->getSimpleQuery(" AND idevento = '{$this->id}'");
		return $this->retornarLinha($this->sql);
    }
	
    public function Cadastrar() {
		$retorno = array();
		if($this->verificaTipoMensalidade() || ($this->post["ativo_painel"] == "N" || $this->post["mensalidade"] == "N")) {
		  $retorno = $this->SalvarDados();
		} else {
		  $retorno["sucesso"] = false;
		  $retorno["erros"][] = "evento_mensalidade_duplicada";
		} 
			
		return $retorno;
    }

    public function Modificar() {
		$retorno = array();		
		if($this->post["mensalidade"]=="S" && !$this->verificaTipoMensalidade($this->post['idevento']) &&
		   $this->post["ativo_painel"]=="S"){		 
		  $retorno["sucesso"] = false;
		  $retorno["erros"][] = "evento_mensalidade_duplicada";		  
		  return $retorno;
		}
		
		if($this->post["taxa_reativacao"]=="S" && !$this->verificaTaxaReativacao($this->post['idevento']) &&
		 $this->post["ativo_painel"]=="S"){
		  $retorno["sucesso"] = false;
		  $retorno["erros"][] = "evento_taxa_reativacao_duplicado";
		  return $retorno;
		}		 
		
		$retorno = $this->SalvarDados();				
		return $retorno;
    }

    public function Remover() {
        return $this->RemoverDados();	
    }
	
	function verificaTipoMensalidade($idevento = false) {
	  $this->sql = "select count(*) as total from eventos_financeiros where mensalidade = 'S' and ativo = 'S' and ativo_painel = 'S'";
	  if($idevento) $this->sql .= " and idevento <> ".$idevento;

	  $total = $this->retornarLinha($this->sql);

	  if($total["total"] > 0) {
		return false;
	  } else {
		return true;
	  }
    }
    
    function verificaTaxaReativacao($idevento = false) {
     $this->sql = "select count(*) as total from eventos_financeiros where taxa_reativacao = 'S' and ativo = 'S' and ativo_painel = 'S'";
     if($idevento) $this->sql .= " and idevento <> ".$idevento;
    
     $total = $this->retornarLinha($this->sql);
    
     if($total["total"] > 0) {
      return false;
     } else {
      return true;
     }
    }
}