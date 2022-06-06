<?php 
class Sms extends Core
{
	public $filacodigo = NULL;
	public $url_webservicesms = NULL;
	public $dado_seguro = array();
	public $tabelaOrigem = NULL;
	public $origem = NULL;
	public $idchave = NULL;
	
	function ListarTodas() {		
		$this->sql = "SELECT ".$this->campos." FROM
							log_sms WHERE ativo='S'";
		$this->aplicarFiltrosBasicos();
		$this->groupby = "idlog_sms";
		return $this->retornarLinhas();
	}
			
	function ExecutaIntegraSMS() {
		$retornoApi = $this->IntegraSMS();
		if($retornoApi->sucesso){
			 $this->sql = "INSERT INTO log_sms 
							  SET ativo = 'S', 
								  enviado = 'N', 
								  celular = '".$this->dado_seguro['celular']."', 
								  nome = '".mysql_escape_string($this->dado_seguro['nome'])."', 
								  mensagem = '".mysql_escape_string($this->dado_seguro['mensagem'])."', 
								  idapi =  '".$retornoApi->filacodigo."',
								  origem = '".$this->origem."',
								  idchave = '".$this->idchave."', 
								  data_cad = NOW() 
								";	
			return $this->executaSql($this->sql);
		}else{
			return false;
		}
	}
	
	function atualizaStatusSMS() {
		
		$retornoApi = $this->consultaStatusSMS();
		if (! $retornoApi->sucesso) {
			return false;
		} else {
			$this->sql = 'UPDATE
								log_sms 
							SET  
								cron = "S"
							WHERE 
								idapi = "' . $retornoApi->filacodigo . '"';
			$this->executaSql($this->sql);
		}

		if($retornoApi->enviado == 'S'){
			$this->sql = 'UPDATE
								log_sms
							SET  
								enviado = "S", 
								data_envio = "' . $retornoApi->data_envio . '"
							WHERE 
								idapi = "' . $retornoApi->filacodigo . '"';
			return $this->executaSql($this->sql);
		}
		return false; 
	}
	

	function IntegraSMS() {
		$this->tipo = 'set';
		return $this->conecta();
	}

	function ConsultaStatusSMS() {
		$this->tipo = 'get';
		return $this->conecta();
	}
	
	function conecta(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url_webservicesms.'api/'.$this->tipo.'/mensagem');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->dado_seguro);
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output);
	}
	

	public function iniciaConexao($host,$usuario,$senha,$banco)
    {
       $connect = @mysql_connect($host,$usuario,$senha);
       @mysql_select_db($banco); 

       return $connect;
    }

   public function fechaConexao($conexao)
    {
       @mysql_close($conexao);
    }
	
}

?>