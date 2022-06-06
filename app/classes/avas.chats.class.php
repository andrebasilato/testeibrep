<?php
class Chats extends Ava {
	
  var $idava = NULL;	
		
  function ListarTodasChat() {		
	$this->sql = "select 
					".$this->campos." 
				  from
					avas_chats c
					inner join avas a on (c.idava = a.idava)
				  where 
					c.ativo = 'S' and 
					a.idava = ".$this->idava;
		
	$this->aplicarFiltrosBasicos();
		
	$this->groupby = "c.idchat";
	return $this->retornarLinhas();
  }
	
  function RetornarChat() {
	$this->sql = "select 
					".$this->campos."
				  from
					avas_chats c
					inner join avas a on c.idava = a.idava
				  where 
					c.ativo = 'S' and 
					c.idchat = '".$this->id."' and 
					a.idava = ".$this->idava;
	return $this->retornarLinha($this->sql);
  }
	
  function CadastrarChat() {
	return $this->SalvarDados();	
  }
	
  function ModificarChat() {
       return $this->SalvarDados();	
  }
  
  function RemoverChat() {
	return $this->RemoverDados();	
  }
  
  /*function RemoverArquivo($modulo, $pasta, $dados, $idioma) {
    echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);		
  }*/
  
    function RemoverArquivo($modulo, $modulo_2, $pasta, $dados, $idioma) {
		if(unlink($_SERVER["DOCUMENT_ROOT"]."/storage/".$modulo."_".$modulo_2.'_'.$pasta."/".$dados[$pasta."_servidor"])) {
			  $this->sql = "select * from ".$this->config["banco_chats"]["tabela"]." where ".$this->config["banco_chats"]["primaria"]." = ".$dados[$this->config["banco_chats"]["primaria"]]."";
			  $linhaAntiga = $this->retornarLinha($this->sql);
			  
			  $this->sql = "UPDATE ".$this->config["banco_chats"]["tabela"]." SET  
								".$pasta."_nome = NULL,
								".$pasta."_servidor = NULL,
								".$pasta."_tipo = NULL,
								".$pasta."_tamanho = NULL
							where ".$this->config["banco_chats"]["primaria"]." = ".$dados[$this->config["banco_chats"]["primaria"]]."";
			  mysql_query($this->sql) or die(incluirLib("erro",$this->config,array("sql" => $this->sql, "session" => $_SESSION, "get" => $_GET, "post" => $_POST, "mysql_error" => mysql_error())));

			  $this->sql = "select * from ".$this->config["banco_chats"]["tabela"]." where ".$this->config["banco_chats"]["primaria"]." = ".$dados[$this->config["banco_chats"]["primaria"]]."";
			  $linhaNova = $this->retornarLinha($this->sql);

			  $this->monitora_oque = 2;
			  $this->monitora_qual = $dados[$this->config["banco_chats"]["primaria"]];
			  $this->monitora_dadosantigos = $linhaAntiga;
			  $this->monitora_dadosnovos = $linhaNova;
			  $this->Monitora();
			  
			  return $idioma["excluido_sucesso"];
		} else {
			  return $idioma["excluido_falha"];
		}	
	}
	
}
?>
