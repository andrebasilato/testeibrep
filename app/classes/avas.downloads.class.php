<?php
class Downloads extends Ava {
		
  var $idava = NULL;
		
  function ListarTodasDownload() {		
	$this->sql = "select 
					".$this->campos." 
				  from
					avas_downloads d
					inner join avas_downloads_pastas p on (d.idpasta = p.idpasta)
					inner join avas a on (d.idava = a.idava)
				  where 
					d.ativo = 'S' and 
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
		
	$this->groupby = "d.iddownload";
	return $this->retornarLinhas();
  }
	
  function RetornarDownload() {
	$this->sql = "select 
					".$this->campos."
				  from
					avas_downloads d
					inner join avas a on d.idava = a.idava
				  where 
					d.ativo = 'S' and 
					d.iddownload = ".$this->id." and 
					a.idava = ".$this->idava;	
	return $this->retornarLinha($this->sql);
  }
	
  function CadastrarDownload() {
	$this->post["idava"] = $this->idava;
	
	return $this->SalvarDados();	
  }
	
  function ModificarDownload() {
	$this->post["idava"] = $this->idava;
	
	$this->config["formulario"][0]["campos"][4]["validacao"] = array("formato_arquivo" => "arquivo_invalido");
	
	return $this->SalvarDados();	
  }
	
  function RemoverDownload() {
	return $this->RemoverDados();	
  }
  
  function RemoverArquivo($modulo, $pasta, $dados, $idioma) {
    echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);		
  }
  
  function ListarTodasPastas() {		
	$this->sql = "select 
					* 
				  from
					avas_downloads_pastas
				  where 
					ativo = 'S' and 
					idava = ".$this->idava;

	$this->ordem = 'asc';
	$this->limite = -1;
	$this->ordem_campo = 'nome';
		
	$this->groupby = "d.iddownload";
	return $this->retornarLinhas();
  }
  
  function CadastrarPasta() {
	if (!$_POST['nome']) {
	  return false;
	}

	$this->sql = "insert into avas_downloads_pastas set data_cad = now(), idava = ".$this->idava.", nome = '".$_POST['nome']."'";
	if($this->executaSql($this->sql)) {
	  return json_encode(array('id' => mysql_insert_id(),'name' => $_POST['nome']));
	}
  }
  
  function RemoverPasta() {
	if (!$_POST['id']) {
	  return false;
	}

	$this->sql = "select count(*) as total from avas_downloads where ativo = 'S' and idava = ".$this->idava." and idpasta = ".$_POST['id'];	
	$total = $this->retornarLinha($this->sql);
	if($total['total'] <= 0) {
	  $this->sql = "update avas_downloads_pastas set ativo = 'N' where idpasta = ".$_POST['id'];
	  if($this->executaSql($this->sql)) {
		return json_encode(array('error' => 0,'alert' => 'Pasta removida com sucesso.'));
	  } else {	
		return json_encode(array('error' => 1,'alert' => 'Ocorreu uma falha ao tentar remover a pasta.'));
	  }
	} else {
	  return json_encode(array('error' => 1,'alert' => 'A pasta não pode ser removida porque tem arquivos nesta pasta.'));
	}
  }
  
  function ModificarPasta() {
	$this->sql = "update avas_downloads_pastas set nome = '".$_POST['nome']."' where idpasta = ".$_POST['id'];
	if($this->executaSql($this->sql)) {
	  return 'Pasta renomeada com sucesso!';
	}
  }
  
}

?>