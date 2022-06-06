<?php
class Exercicios extends Ava {
		
  var $idava = NULL;
  
  function ListarTodasExercicio() {		
	$this->sql = "SELECT 
					{$this->campos}
				  FROM
					avas_exercicios ae
					inner join avas a on (ae.idava = a.idava)
				  WHERE 
					ae.ativo = 'S' AND 
					a.idava = ".$this->idava;
		
	$this->aplicarFiltrosBasicos();
		
	$this->groupby = "ae.idexercicio";
	return $this->retornarLinhas();
  }
	
  function RetornarExercicio() {
	$this->sql = "SELECT 
					{$this->campos}
				  FROM
					avas_exercicios ae
					inner join avas a on (ae.idava = a.idava)
				  WHERE 
					ae.ativo = 'S' AND 
					ae.idexercicio = '".$this->id."' AND 
					a.idava = ".$this->idava;
	return $this->retornarLinha($this->sql);
  }

  public function retornarDisciplinasPerguntas() {
	$this->sql = "SELECT 
					{$this->campos}
				  FROM
					avas_exercicios_disciplinas avd 
				  WHERE 
					avd.ativo = 'S' AND 
					avd.idexercicio = '".$this->id."'";
	return $this->retornarLinhas();
  }
	
  function CadastrarExercicio() {

    if (count($this->post['iddisciplina_perguntas']) == 0) {
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"][] = 'erro_disciplinas_perguntas_vazio';
	  return $this->retorno;
    }

    foreach($this->post['iddisciplina_perguntas'] as $iddisciplina) {
	  $arrayDisciplinas[] = $iddisciplina;
    }
	$disciplinas = implode(',', $arrayDisciplinas);

	$this->sql = "SELECT 
					count(idpergunta) AS total 
				  FROM 
					perguntas 
				  WHERE 
					ativo = 'S' AND 
					iddisciplina in(".$disciplinas.") AND 
					exercicio = 'S' AND 
					tipo = 'O' AND 
					dificuldade = 'F'";
	$objetivas_faceis = $this->retornarLinha($this->sql);

	if($objetivas_faceis['total'] < $this->post["objetivas_faceis"]){
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"][] = 'erro_objetivas_faceis_insuficientes';
	}

	$this->sql = "SELECT 
					count(idpergunta) AS total 
				  FROM 
					perguntas 
				  WHERE 
					ativo = 'S' AND 
					iddisciplina in(".$disciplinas.") AND 
					exercicio = 'S' AND 
					tipo = 'O' AND 
					dificuldade = 'M'";
	$objetivas_medias = $this->retornarLinha($this->sql);

	if($objetivas_medias['total'] < $this->post["objetivas_intermediarias"]){
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"][] = 'erro_objetivas_medias_insuficientes';
	}

	$this->sql = "SELECT 
					count(idpergunta) AS total 
				  FROM 
					perguntas 
				  WHERE 
					ativo = 'S' AND 
					iddisciplina in(".$disciplinas.") AND 
					exercicio = 'S' AND 
					tipo = 'O' AND 
					dificuldade = 'D'";
	$objetivas_dificeis = $this->retornarLinha($this->sql);

	if($objetivas_dificeis['total'] < $this->post["objetivas_dificeis"]){
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"][] = 'erro_objetivas_dificeis_insuficientes';
	}

    if ($this->retorno["erro"]) {
	  return $this->retorno;
    } else {
	  $this->executaSql('begin');
	  
	  $salvar = $this->SalvarDados();
	  if ($salvar['sucesso']) {
		foreach ($this->post['iddisciplina_perguntas'] as $iddisciplina) {
  
		  $this->sql = "INSERT INTO 
						  avas_exercicios_disciplinas
						SET
						  data_cad = NOW(),
						  ativo = 'S',
						  idexercicio = ".(int)$salvar['id'].",
						  iddisciplina = ".(int)$iddisciplina;

		  if (!$this->executaSql($this->sql)) {
			$this->executaSql('rollback');
			$retorno["erro"] = true;
			$retorno["erros"][] = $this->sql;
			$retorno["erros"][] = mysql_error();
			return $retorno;
		  }
		}
	  }
	  
	  $this->executaSql('commit');
	  $retorno["sucesso"] = true;
	  $retorno["id"] = $salvar['id'];
    }
    
    return $retorno;	
  }
	
  function ModificarExercicio() {

    if (count($this->post['iddisciplina_perguntas']) == 0) {
        $this->retorno["erro"] = true;
        $this->retorno["erros"][] = 'erro_disciplinas_perguntas_vazio';
        return $this->retorno;
    }

    foreach($this->post['iddisciplina_perguntas'] as $iddisciplina) {
        $arrayDisciplinas[] = $iddisciplina;
    }
    $disciplinas = implode(',', $arrayDisciplinas);

	$this->sql = "SELECT count(idpergunta) as total FROM perguntas WHERE ativo = 'S' AND iddisciplina in(".$disciplinas.") AND exercicio = 'S' AND tipo = 'O' AND  dificuldade = 'F' ";
	$objetivas_faceis = $this->retornarLinha($this->sql);

	if($objetivas_faceis['total'] < $this->post["objetivas_faceis"]){
		$this->retorno["erro"] = true;
	    $this->retorno["erros"][] = 'erro_objetivas_faceis_insuficientes';
	}

	$this->sql = "SELECT count(idpergunta) AS total FROM perguntas WHERE ativo = 'S' AND iddisciplina in(".$disciplinas.") AND exercicio = 'S' AND tipo = 'O' AND  dificuldade = 'M' ";
	$objetivas_medias = $this->retornarLinha($this->sql);

	if($objetivas_medias['total'] < $this->post["objetivas_intermediarias"]){
		$this->retorno["erro"] = true;
	   $this->retorno["erros"][] = 'erro_objetivas_medias_insuficientes';
	}

	$this->sql = "SELECT count(idpergunta) AS total FROM perguntas WHERE ativo = 'S' AND iddisciplina in(".$disciplinas.") AND exercicio = 'S' AND tipo = 'O' AND  dificuldade = 'D' ";
	$objetivas_dificeis = $this->retornarLinha($this->sql);

	if($objetivas_dificeis['total'] < $this->post["objetivas_dificeis"]){
		$this->retorno["erro"] = true;
	   $this->retorno["erros"][] = 'erro_objetivas_dificeis_insuficientes';
	}

    if ($this->retorno["erro"]) {
	  return $this->retorno;
    }

	$this->executaSql('begin');
	$salvar = $this->SalvarDados();

    if (!$salvar['sucesso']) {
	  $this->executaSql('rollback');
	  return $salvar;
    } elseif ($salvar['sucesso']) {

	  $this->sql = "UPDATE 
					  avas_exercicios_disciplinas
					SET 
					  ativo = 'N'
					WHERE 
					  idexercicio = ".(int)$salvar['id'];
	  $this->executaSql($this->sql);

	  foreach ($this->post['iddisciplina_perguntas'] as $iddisciplina) {

		$sql_verifica_existe = "SELECT * FROM avas_exercicios_disciplinas WHERE idexercicio = ".(int)$salvar['id']." AND iddisciplina = ".(int)$iddisciplina;
		$linha_existente = $this->retornarLinha($sql_verifica_existe);

		if ($linha_existente['idexercicio_disciplina']) {
		  $this->sql = "UPDATE avas_exercicios_disciplinas SET ativo = 'S' WHERE idexercicio = ".(int)$salvar['id']." AND iddisciplina = ".(int)$iddisciplina;

		  if (!$this->executaSql($this->sql)) {
			$this->executaSql('rollback');
			$retorno["erro"] = true;
			$retorno["erros"][] = $this->sql;
			$retorno["erros"][] = mysql_error();
			return $retorno;
		  }
		} else {

		  $this->sql = "INSERT INTO 
						  avas_exercicios_disciplinas
						SET
						  data_cad = NOW(),
						  ativo = 'S',
						  idexercicio = ".(int)$salvar['id'].",
						  iddisciplina = ".(int)$iddisciplina;

		  if (!$this->executaSql($this->sql)) {
			$this->executaSql('rollback');
			$retorno["erro"] = true;
			$retorno["erros"][] = $this->sql;
			$retorno["erros"][] = mysql_error();
			return $retorno;
		  }
		}
	  }
	  $this->executaSql('commit');
	  $retorno["sucesso"] = true;
	  $retorno["id"] = $salvar['id'];
    }
	
	
    return $retorno;
  }
	
  function RemoverExercicio() {
	return $this->RemoverDados();	
  }
  
  function RemoverArquivo($modulo, $pasta, $dados, $idioma) {
    echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);		
  }
	
}

?>