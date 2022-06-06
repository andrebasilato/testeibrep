<?php 
class Comissoes_Competencias extends Core {
		
  
  function ListarTodas() {		
	$retorno = array();
	
	$this->sql = "select idsindicato, nome_abreviado from sindicatos where ativo = 'S' and ativo_painel = 'S'";	
	if($_SESSION['adm_gestor_sindicato'] != 'S') {
	  if(!$_SESSION['adm_sindicatos']) {
		return $retorno;
	  } else {
		$this->sql .= " and idsindicato in (".$_SESSION['adm_sindicatos'].")";
	  }
	}
	$this->limite = -1;
	$this->ordem = "asc";
	$this->ordem_campo = "nome_abreviado";
	$retorno["sindicatos"] = $this->retornarLinhas();
	
	$dataInicio = date("Y-m-d",mktime(0,0,0,$_GET["de_mes"],1,$_GET["de_ano"]));
	$dataFim = date("Y-m-d",mktime(0,0,0,$_GET["ate_mes"]+1,1,$_GET["ate_ano"]));
	
	foreach($retorno["sindicatos"] as $indSindicato => $sindicato) {
	  $deMes = $_GET["de_mes"];
	  for($data = $dataInicio;$data != $dataFim;$data = date("Y-m-d",mktime(0,0,0,++$deMes,1,$_GET["de_ano"]))) { 
		$this->sql = "select * from comissoes_competencias where ativo = 'S' and idsindicato = ".$sindicato["idsindicato"]." and mes = '".$data."' order by data_cad desc limit 1";	
		$retorno["sindicatos"][$indSindicato]["competencias"][$data] = $this->retornarLinha($this->sql);
	  }
	  
	  $this->sql = "select 
					  c.idcurso,
					  c.nome
					from 
					  cursos c
					  inner join cursos_sindicatos ci on (c.idcurso = ci.idcurso and ci.ativo = 'S')
					where 
					 ci.idsindicato = ".$sindicato["idsindicato"]." and
					 c.ativo_painel = 'S' and 
					 c.ativo = 'S'";	
	  $this->limite = -1;
	  $this->ordem = "asc";
	  $this->ordem_campo = "c.nome";
	  $retorno["sindicatos"][$indSindicato]["cursos"] = $this->retornarLinhas();
	  foreach($retorno["sindicatos"][$indSindicato]["cursos"] as $indCurso => $curso) {
		$this->sql = "select 
						cr.*
					  from 
						comissoes_regras cr
					  where 
						cr.ativo = 'S' and
						cr.ativo_painel = 'S' and
						(
						  (
							todas_sindicatos = 'S' or 
							exists(select cri.idregra_sindicato from comissoes_regras_sindicatos cri where cr.idregra = cri.idregra and cri.idsindicato = ".$sindicato["idsindicato"]." and cri.ativo = 'S')
						  ) and (
							todos_cursos = 'S' or
							exists(select crc.idregra_curso from comissoes_regras_cursos crc where cr.idregra = crc.idregra and crc.idcurso = ".$curso["idcurso"]." and crc.ativo = 'S')
						  )
						)";	
		$this->limite = -1;
		$this->ordem = "asc";
		$this->ordem_campo = "cr.nome";
		$retorno["sindicatos"][$indSindicato]["cursos"][$indCurso]["regras"] = $this->retornarLinhas();
		
		foreach($retorno["sindicatos"][$indSindicato]["competencias"] as $data => $competencia) {
		  $this->sql = "select 
						  idregra 
						from 
						  comissoes_competencias_cursos 
						where 
						  ativo = 'S' and 
						  idcompetencia = '".$competencia["idcompetencia"]."' and
						  idcurso = ".$curso["idcurso"]."
						order by data_cad desc limit 1";	
		  $regra = $this->retornarLinha($this->sql);
		  $retorno["sindicatos"][$indSindicato]["cursos"][$indCurso]["competencias"][$data] = $regra["idregra"];
		}
	  }
	}
	
	return $retorno;
  }
  
  
  /*function ListarTodas2() {		
	$retorno = array();
	
	$dataInicio = date("Y-m-d",mktime(0,0,0,$_GET["de_mes"],1,$_GET["de_ano"]));
	$dataFim = date("Y-m-d",mktime(0,0,0,$_GET["ate_mes"]+1,1,$_GET["ate_ano"]));
	$deMes = $_GET["de_mes"];
	for($data = $dataInicio;$data != $dataFim;$data = date("Y-m-d",mktime(0,0,0,++$deMes,1,$_GET["de_ano"]))) { 
	  $this->sql = "select * from comissoes_competencias where ativo = 'S' and mes = '".$data."' order by data_cad desc limit 1";	
	  $retorno["competencias"][$data] = $this->retornarLinha($this->sql);
	}
	
	$this->sql = "select idsindicato, nome from sindicatos where ativo = 'S'";	
	$this->limite = -1;
	$this->ordem = "asc";
	$this->ordem_campo = "nome";
	$retorno["sindicatos"] = $this->retornarLinhas();
	
	foreach($retorno["sindicatos"] as $indSindicato => $sindicato) {
	  $this->sql = "select 
					  c.idcurso,
					  c.nome,
					  ci.idcurso_sindicato
					from 
					  cursos c
					  inner join cursos_sindicatos ci on (c.idcurso = ci.idcurso and ci.ativo = 'S')
					where 
					  c.ativo = 'S' and 
					  ci.idsindicato = ".$sindicato["idsindicato"];	
	  $this->limite = -1;
	  $this->ordem = "asc";
	  $this->ordem_campo = "c.nome";
	  $retorno["sindicatos"][$indSindicato]["cursos"] = $this->retornarLinhas();
	  foreach($retorno["sindicatos"][$indSindicato]["cursos"] as $indCurso => $curso) {
		$this->sql = "select 
						cr.*
					  from 
						comissoes_regras cr
					  where 
						cr.ativo = 'S' and
						cr.ativo_painel = 'S' and
						(
						  (
							todas_sindicatos = 'S' or 
							exists(select cri.idregra_sindicato from comissoes_regras_sindicatos cri where cr.idregra = cri.idregra and cri.idsindicato = ".$sindicato["idsindicato"]." and cri.ativo = 'S')
						  ) and (
							todos_cursos = 'S' or
							exists(select crc.idregra_curso from comissoes_regras_cursos crc where cr.idregra = crc.idregra and crc.idcurso = ".$curso["idcurso"]." and crc.ativo = 'S')
						  )
						)";	
		$this->limite = -1;
		$this->ordem = "asc";
		$this->ordem_campo = "cr.nome";
		$retorno["sindicatos"][$indSindicato]["cursos"][$indCurso]["regras"] = $this->retornarLinhas();
		
		foreach($retorno["competencias"] as $data => $competencia) {
		  $this->sql = "select 
						  idregra 
						from 
						  comissoes_competencias_sindicatos_cursos 
						where 
						  ativo = 'S' and 
						  idcompetencia = '".$competencia["idcompetencia"]."' and
						  idcurso_sindicato = ".$curso["idcurso_sindicato"]."
						order by data_cad desc limit 1";	
		  $regra = $this->retornarLinha($this->sql);
		  $retorno["sindicatos"][$indSindicato]["cursos"][$indCurso]["competencias"][$data] = $regra["idregra"];
		}
	  }
	}
	
	return $retorno;
  }*/
  
  function CadastrarModificar() {
	
	$this->executaSql("begin");
	
	foreach($this->post["sindicato"] as $idsindicato => $post) {
	  $idcompetencia[$idsindicato] = array();
	  foreach($post["competencias"] as $mes => $periodo) {
		$this->sql = "select count(idcompetencia) as total, idcompetencia from comissoes_competencias where idsindicato = ".$idsindicato." and mes = '".$mes."'";
		$totalCompetencia = $this->retornarLinha($this->sql);
		if($totalCompetencia["total"] > 0) {
		  if(!$periodo["de"]) $periodo["de"] = "NULL"; else $periodo["de"] = "'".formataData($periodo["de"], "en", 0)."'";
		  if(!$periodo["ate"]) $periodo["ate"] = "NULL"; else $periodo["ate"] = "'".formataData($periodo["ate"], "en", 0)."'";
		  $this->sql = "select * from comissoes_competencias where idcompetencia = ".$totalCompetencia["idcompetencia"];
		  $this->monitora_dadosantigos = $this->retornarLinha($this->sql);
		  
		  $this->sql = "update comissoes_competencias set ativo = 'S', de = ".$periodo["de"].", ate = ".$periodo["ate"]." where idcompetencia = ".$totalCompetencia["idcompetencia"];
		  $executa = $this->executaSql($this->sql);
		  
		  $this->sql = "select * from comissoes_competencias where idcompetencia = ".$totalCompetencia["idcompetencia"];
		  $this->monitora_dadosnovos = $this->retornarLinha($this->sql);
		  
		  $this->monitora_oque = 2;
		  $this->monitora_qual = $totalCompetencia["idcompetencia"];
		} else {
		  if(!$periodo["de"]) $periodo["de"] = "NULL"; else $periodo["de"] = "'".formataData($periodo["de"], "en", 0)."'";
		  if(!$periodo["ate"]) $periodo["ate"] = "NULL"; else $periodo["ate"] = "'".formataData($periodo["ate"], "en", 0)."'";
  
		  $this->sql = "insert into comissoes_competencias set data_cad = now(), idsindicato = ".$idsindicato.", mes = '".$mes."', de = ".$periodo["de"].", ate = ".$periodo["ate"];
		  $executa = $this->executaSql($this->sql);
		  
		  $this->monitora_oque = 1;
		  $this->monitora_qual = mysql_insert_id();
		}
		if($executa){
		  $idcompetencia[$idsindicato][$mes] = $this->monitora_qual;
		  $this->monitora_onde = 139;
		  $this->Monitora();
		}
	  }
	  foreach($post["cursos"] as $idcurso => $regras) {
		foreach($regras as $competencia => $idregra) {
		  $this->sql = "select 
						  count(idcompetencia_curso) as total, 
						  idcompetencia_curso 
						from 
						  comissoes_competencias_cursos 
						where 
						  idcurso = ".$idcurso." and
						  idcompetencia = ".$idcompetencia[$idsindicato][$competencia];
		  $totalRegra = $this->retornarLinha($this->sql);
		  if($totalRegra["total"] > 0) {
			if(!$idregra) $idregra = "NULL"; 
			$this->sql = "select * from comissoes_competencias_cursos where idcompetencia_curso = ".$totalRegra["idcompetencia_curso"];
			$this->monitora_dadosantigos = $this->retornarLinha($this->sql);
			
			$this->sql = "update comissoes_competencias_cursos set ativo = 'S', idregra = ".$idregra." where idcompetencia_curso = ".$totalRegra["idcompetencia_curso"];
			$executa = $this->executaSql($this->sql);
			
			$this->sql = "select * from comissoes_competencias_cursos where idcompetencia_curso = ".$totalRegra["idcompetencia_curso"];
			$this->monitora_dadosnovos = $this->retornarLinha($this->sql);
			
			if($executa){
			  $this->monitora_oque = 2;
			  $this->monitora_qual = $totalRegra["idcompetencia_curso"];
			  $this->monitora_onde = 140;
			  $this->Monitora();
			}
		  } else {
			if(!$idregra) $idregra = "NULL"; 
			$this->sql = "insert into comissoes_competencias_cursos set data_cad = now(), idcurso = ".$idcurso.", idcompetencia = ".$idcompetencia[$idsindicato][$competencia].", idregra = ".$idregra;
			$executa = $this->executaSql($this->sql);
			
			if($executa){
			  $this->monitora_oque = 1;
			  $this->monitora_qual = mysql_insert_id();
			  $this->monitora_onde = 140;
			  $this->Monitora();
			}
		  }
		}
	  }
	}
	
	$this->executaSql("commit");
	
	$this->retorno["sucesso"] = true;
	return $this->retorno;
  }
  
  /*function CadastrarModificar() {
	$idcompetencia = array();
	
	$this->executaSql("begin");
	print_r2($this->post,true);
	foreach($this->post["competencia"] as $mes => $periodo) {
	  $this->sql = "select count(idcompetencia) as total, idcompetencia from comissoes_competencias where mes = '".$mes."'";
	  $totalCompetencia = $this->retornarLinha($this->sql);
	  if($totalCompetencia["total"] > 0) {
		if(!$periodo["de"]) $periodo["de"] = "NULL"; else $periodo["de"] = "'".formataData($periodo["de"], "en", 0)."'";
		if(!$periodo["ate"]) $periodo["ate"] = "NULL"; else $periodo["ate"] = "'".formataData($periodo["ate"], "en", 0)."'";
		$this->sql = "select * from comissoes_competencias where idcompetencia = ".$totalCompetencia["idcompetencia"];
		$this->monitora_dadosantigos = $this->retornarLinha($this->sql);
		
		$this->sql = "update comissoes_competencias set ativo = 'S', de = ".$periodo["de"].", ate = ".$periodo["ate"]." where idcompetencia = ".$totalCompetencia["idcompetencia"];
		$executa = $this->executaSql($this->sql);
		
		$this->sql = "select * from comissoes_competencias where idcompetencia = ".$totalCompetencia["idcompetencia"];
		$this->monitora_dadosnovos = $this->retornarLinha($this->sql);
		
		$this->monitora_oque = 2;
		$this->monitora_qual = $totalCompetencia["idcompetencia"];
	  } else {
		if(!$periodo["de"]) $periodo["de"] = "NULL"; else $periodo["de"] = "'".formataData($periodo["de"], "en", 0)."'";
		if(!$periodo["ate"]) $periodo["ate"] = "NULL"; else $periodo["ate"] = "'".formataData($periodo["ate"], "en", 0)."'";

		$this->sql = "insert into comissoes_competencias set data_cad = now(), mes = '".$mes."', de = ".$periodo["de"].", ate = ".$periodo["ate"];
		$executa = $this->executaSql($this->sql);
		
		$this->monitora_oque = 1;
		$this->monitora_qual = mysql_insert_id();
	  }
	  if($executa){
		$idcompetencia[$mes] = $this->monitora_qual;
		$this->monitora_onde = 139;
		$this->Monitora();
	  }
	}
	foreach($this->post["regra"] as $idcurso_sindicato => $regras) {
	  foreach($regras as $competencia => $idregra) {
		$this->sql = "select 
						count(idcompetencia_oferta_curso) as total, 
						idcompetencia_oferta_curso 
					  from 
						comissoes_competencias_sindicatos_cursos 
					  where 
						idcurso_sindicato = ".$idcurso_sindicato." and
						idcompetencia = ".$idcompetencia[$competencia];
		$totalRegra = $this->retornarLinha($this->sql);
		if($totalRegra["total"] > 0) {
		  if(!$idregra) $idregra = "NULL"; 
		  $this->sql = "select * from comissoes_competencias_sindicatos_cursos where idcompetencia_oferta_curso = ".$totalRegra["idcompetencia_oferta_curso"];
		  $this->monitora_dadosantigos = $this->retornarLinha($this->sql);
		  
		  $this->sql = "update comissoes_competencias_sindicatos_cursos set ativo = 'S', idregra = ".$idregra." where idcompetencia_oferta_curso = ".$totalRegra["idcompetencia_oferta_curso"];
		  $executa = $this->executaSql($this->sql);
		  
		  $this->sql = "select * from comissoes_competencias_sindicatos_cursos where idcompetencia_oferta_curso = ".$totalRegra["idcompetencia_oferta_curso"];
		  $this->monitora_dadosnovos = $this->retornarLinha($this->sql);
		  
		  if($executa){
			$this->monitora_oque = 2;
			$this->monitora_qual = $totalRegra["idcompetencia_oferta_curso"];
			$this->monitora_onde = 140;
			$this->Monitora();
		  }
		} else {
		  if(!$idregra) $idregra = "NULL"; 
		  $this->sql = "insert into comissoes_competencias_sindicatos_cursos set data_cad = now(), idcurso_sindicato = ".$idcurso_sindicato.", idcompetencia = ".$idcompetencia[$competencia].", idregra = ".$idregra;
		  $executa = $this->executaSql($this->sql);
		  
		  if($executa){
			$this->monitora_oque = 1;
			$this->monitora_qual = mysql_insert_id();
			$this->monitora_onde = 140;
			$this->Monitora();
		  }
		}
		
	  }
	}
	$this->executaSql("commit");
	
	$this->retorno["sucesso"] = true;
	return $this->retorno;
  }*/
	
}

?>