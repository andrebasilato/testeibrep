<?php

class Feriados extends Core
{		
  function ListarTodas() {		
	$this->sql = "select ".$this->campos." from feriados where ativo = 'S'";
		
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
		
	$this->groupby = "idferiado";
	return $this->retornarLinhas();
  }
	
	
  function Retornar() {
	$this->sql = "select ".$this->campos." from feriados where ativo = 'S' and idferiado = '".$this->id."'";			
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
  
    function BuscarCidade() {		
		$this->sql = "select 
						c.idcidade as 'key', c.nome as value 
					  from
						cidades c 
					  where 
					     c.nome like '%".$_GET["tag"]."%' AND  
						 NOT EXISTS (SELECT fc.idcidade FROM feriados_cidades fc WHERE fc.idcidade = c.idcidade AND fc.idferiado = '".$this->id."' AND fc.ativo = 'S')";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";
		
		$dados = $this->retornarLinhas();						
		return json_encode($dados);		
	}
	
	function ListarCidadesAss() {		
		$this->sql = "SELECT 
						".$this->campos." 
					  FROM
						cidades c
						INNER JOIN feriados_cidades fc ON (c.idcidade = fc.idcidade) 
					  WHERE 
						fc.ativo = 'S' and 
						fc.idferiado = ".intval($this->id);
		
		$this->groupby = "fc.idferiado_cidade";		
		return $this->retornarLinhas();
	}	
	
	function AssociarCidades($idferiado, $arrayCidades) {
		foreach($arrayCidades as $ind => $id) {
					
			  $this->sql = "select count(idferiado_cidade) as total, idferiado_cidade from feriados_cidades where idferiado = '".intval($idferiado)."' and idcidade = '".intval($id)."'";
			  $totalAss = $this->retornarLinha($this->sql); 
			  if($totalAss["total"] > 0) {
				  $this->sql = "update feriados_cidades set ativo = 'S' where idferiado_cidade = ".$totalAss["idferiado_cidade"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idferiado_cidade"];					
			  } else {
				  $this->sql = "insert into feriados_cidades set ativo = 'S', data_cad = now(), idferiado = '".intval($idferiado)."', idcidade = '".intval($id)."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }			
			
			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 120;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
			
		}
		return $this->retorno;
	}	
	
	function DesassociarCidades() {
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";
		
		//VALIDANDO FORMULÃRIO
		$erros = validateFields($this->post, $regras);

		//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update feriados_cidades set ativo = 'N' where idferiado_cidade = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 120;
				$this->monitora_qual = intval($this->post["remover"]);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}		
		return $this->retorno;
	} 
	
	function BuscarEstado() {		
		$this->sql = "select 
						e.idestado as 'key', e.nome as value 
					  from
						estados e 
					  where 
					     e.nome like '%".$_GET["tag"]."%' AND  
						 NOT EXISTS (SELECT fe.idestado FROM feriados_estados fe WHERE fe.idestado = e.idestado AND fe.idferiado = '".$this->id."' AND fe.ativo = 'S')";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";
		
		$dados = $this->retornarLinhas();						
		return json_encode($dados);		
	}
	
	function ListarEstadosAss() {		
		$this->sql = "SELECT 
						".$this->campos." 
					  FROM
						estados e
						INNER JOIN feriados_estados fe ON (e.idestado = fe.idestado) 
					  WHERE 
						fe.ativo = 'S' and 
						fe.idferiado = ".intval($this->id);
		
		$this->groupby = "fe.idferiado_estado";		
		return $this->retornarLinhas();
	}	
	
	function AssociarEstados($idferiado, $arrayEstados) {
		foreach($arrayEstados as $ind => $id) {
					
			  $this->sql = "select count(idferiado_estado) as total, idferiado_estado from feriados_estados where idferiado = '".intval($idferiado)."' and idestado = '".intval($id)."'";
			  $totalAss = $this->retornarLinha($this->sql); 
			  if($totalAss["total"] > 0) {
				  $this->sql = "update feriados_estados set ativo = 'S' where idferiado_estado = ".$totalAss["idferiado_estado"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idferiado_estado"];					
			  } else {
				  $this->sql = "insert into feriados_estados set ativo = 'S', data_cad = now(), idferiado = '".intval($idferiado)."', idestado = '".intval($id)."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }			
			
			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 121;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
			
		}
		return $this->retorno;
	}	
	
	function DesassociarEstados() {
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";
		
		//VALIDANDO FORMULÃRIO
		$erros = validateFields($this->post, $regras);

		//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update feriados_estados set ativo = 'N' where idferiado_estado = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 121;
				$this->monitora_qual = intval($this->post["remover"]);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}		
		return $this->retorno;
	}
	
	function BuscarEscola() {		
		$this->sql = "select 
						p.idescola as 'key', p.nome_fantasia as value 
					  from
						escolas p 
					  where 
					     p.nome_fantasia like '%".$_GET["tag"]."%' AND p.ativo = 'S' AND p.ativo_painel = 'S' AND
						 NOT EXISTS (SELECT fp.idescola FROM feriados_escolas fp WHERE fp.idescola = p.idescola AND fp.idferiado = '".$this->id."' AND fp.ativo = 'S')";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";
		
		$dados = $this->retornarLinhas();						
		return json_encode($dados);		
	}
	
	function ListarEscolasAss() {		
		$this->sql = "SELECT 
						".$this->campos." 
					  FROM
						escolas p
						INNER JOIN feriados_escolas fp ON (p.idescola = fp.idescola) 
					  WHERE 
						fp.ativo = 'S' and 
						fp.idferiado = ".intval($this->id);
		
		$this->groupby = "fp.idferiado_escola";		
		return $this->retornarLinhas();
	}	
	
	function AssociarEscolas($idferiado, $arrayEscolas) {
		foreach($arrayEscolas as $ind => $id) {
					
			  $this->sql = "select count(idferiado_escola) as total, idferiado_escola from feriados_escolas where idferiado = '".intval($idferiado)."' and idescola = '".intval($id)."'";
			  $totalAss = $this->retornarLinha($this->sql); 
			  if($totalAss["total"] > 0) {
				  $this->sql = "update feriados_escolas set ativo = 'S' where idferiado_escola = ".$totalAss["idferiado_escola"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idferiado_escola"];					
			  } else {
				  $this->sql = "insert into feriados_escolas set ativo = 'S', data_cad = now(), idferiado = '".intval($idferiado)."', idescola = '".intval($id)."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }			
			
			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 122;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
			
		}
		return $this->retorno;
	}	
	
	function DesassociarEscolas() {
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";
		
		//VALIDANDO FORMULÃRIO
		$erros = validateFields($this->post, $regras);

		//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update feriados_escolas set ativo = 'N' where idferiado_escola = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 122;
				$this->monitora_qual = intval($this->post["remover"]);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}		
		return $this->retorno;
	}
	
	function BuscarSindicato() {		
		$this->sql = "select 
						i.idsindicato as 'key', i.nome_abreviado as value 
					  from
						sindicatos i 
					  where 
					     i.nome_abreviado like '%".$_GET["tag"]."%' AND i.ativo = 'S' AND i.ativo_painel = 'S' AND
						 NOT EXISTS (SELECT fi.idsindicato FROM feriados_sindicatos fi WHERE fi.idsindicato = i.idsindicato AND fi.idferiado = '".$this->id."' AND fi.ativo = 'S')";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";
		
		$dados = $this->retornarLinhas();						
		return json_encode($dados);		
	}
	
	function ListarSindicatosAss() {		
		$this->sql = "SELECT 
						".$this->campos." 
					  FROM
						sindicatos i
						INNER JOIN feriados_sindicatos fi ON (i.idsindicato = fi.idsindicato) 
					  WHERE 
						fi.ativo = 'S' and 
						fi.idferiado = ".intval($this->id);
		
		$this->groupby = "fi.idferiado_sindicato";		
		return $this->retornarLinhas();
	}	
	
	function AssociarSindicatos($idferiado, $arraySindicatos) {
		foreach($arraySindicatos as $ind => $id) {
					
			  $this->sql = "select count(idferiado_sindicato) as total, idferiado_sindicato from feriados_sindicatos where idferiado = '".intval($idferiado)."' and idsindicato = '".intval($id)."'";
			  $totalAss = $this->retornarLinha($this->sql); 
			  if($totalAss["total"] > 0) {
				  $this->sql = "update feriados_sindicatos set ativo = 'S' where idferiado_sindicato = ".$totalAss["idferiado_sindicato"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idferiado_sindicato"];					
			  } else {
				  $this->sql = "insert into feriados_sindicatos set ativo = 'S', data_cad = now(), idferiado = '".intval($idferiado)."', idsindicato = '".intval($id)."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }			
			
			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 123;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
			
		}
		return $this->retorno;
	}	
	
	function DesassociarSindicatos() {
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";
		
		//VALIDANDO FORMULÃRIO
		$erros = validateFields($this->post, $regras);

		//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update feriados_sindicatos set ativo = 'N' where idferiado_sindicato = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 123;
				$this->monitora_qual = intval($this->post["remover"]);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}		
		return $this->retorno;
	}

	function diasFeriados($ano, $idEstado = null, $idCidade = null, $idEscola = null, $idSindicato = null)
	{
	    if (empty($ano)) {
            $erros['erro'] = 'true';
            $erros['erros'][] = 'parametros_incompletos';
            return $erros;
        }

	    // Datas Fixas dos feriados Nacionais Basileiros
	    $feriados[] = (new DateTime($ano . '-01-01'))->format('Y-m-d');//01 de janeiro./ Confraternização Universal - Lei nº 662, de 06/04/49
	    $feriados[] = (new DateTime($ano . '-04-21'))->format('Y-m-d');//21 de abril./ Tiradentes - Lei nº 662, de 06/04/49
	    $feriados[] = (new DateTime($ano . '-05-01'))->format('Y-m-d');//01 de maio./ Dia do Trabalhador - Lei nº 662, de 06/04/49
	    $feriados[] = (new DateTime($ano . '-09-07'))->format('Y-m-d');//07 de setembro./ Dia da Independência - Lei nº 662, de 06/04/49
	    $feriados[] = (new DateTime($ano . '-10-12'))->format('Y-m-d');//12 de outubro./ N. S. Aparecida - Lei nº 6802, de 30/06/80
	    $feriados[] = (new DateTime($ano . '-11-02'))->format('Y-m-d');//02 de novembro./ Todos os santos - Lei nº 662, de 06/04/49
	    $feriados[] = (new DateTime($ano . '-11-15'))->format('Y-m-d');//15 de novembro./ Proclamação da republica - Lei nº 662, de 06/04/49
	    $feriados[] = (new DateTime($ano . '-12-25'))->format('Y-m-d');//25 de dezemrbo./ Natal - Lei nº 662, de 06/04/49

	    //Retorna o dia da páscoa. Limite de 1970 até 2037 da easter_date.
	    $pascoa = (new DateTime())
	    	->setTimestamp(easter_date($ano))
	    	->format('Y-m-d');

	    //Carnaval
	    $feriados[] = (new DateTime($pascoa))
	    	->modify('-47 day')
	    	->format('Y-m-d');
    	//Sexta-feira Santa
	    $feriados[] = (new DateTime($pascoa))
	    	->modify('-2 day')
	    	->format('Y-m-d');
	    $feriados[] = $pascoa;//Pascoa
	    //Corpus Christi
	    $feriados[] = (new DateTime($pascoa))
	    	->modify('+60 day')
	    	->format('Y-m-d');
	    
	    $this->sql = 'SELECT f.data FROM feriados f
	    	LEFT OUTER JOIN feriados_estados fe ON (fe.idferiado = f.idferiado AND fe.ativo = "S")
	    	LEFT OUTER JOIN feriados_cidades fc ON (fc.idferiado = f.idferiado AND fc.ativo = "S")
	    	LEFT OUTER JOIN feriados_escolas fesc ON (fesc.idferiado = f.idferiado AND fesc.ativo = "S")
	    	LEFT OUTER JOIN feriados_sindicatos fs ON (fs.idferiado = f.idferiado AND fs.ativo = "S")
	    	WHERE f.ativo = "S" AND f.ativo_painel = "S"';

	    if (! empty($idEstado)) {
	    	$this->sql .= ' AND (fe.idestado = ' . $idEstado . ' OR fe.idestado IS NULL)';
	    }

	    if (! empty($idCidade)) {
	    	$this->sql .= ' AND (fc.idcidade = ' . $idCidade . ' OR fc.idcidade IS NULL)';
	    }

	    if (! empty($idEscola)) {
	    	$this->sql .= ' AND (fesc.idescola = ' . $idEscola . ' OR fesc.idescola IS NULL)';
	    }

	    if (! empty($idSindicato)) {
	    	$this->sql .= ' AND (fs.idsindicato = ' . $idSindicato . ' OR fs.idsindicato IS NULL)';
	    }

	    $this->sql .= ' GROUP BY f.data';

	    $this->ordem_campo = 'f.data';
	    $this->ordem = 'ASC';
	    $this->limite = -1;
	    $feriadosArray = $this->retornarLinhas();
		foreach ($feriadosArray as $ind => $var) {
			$feriados[] = $var['data'];
		}

	    $feriados = array_unique($feriados);

	    return $feriados;
	}
}
