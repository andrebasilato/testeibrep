<?php 
class Formulas_Notas extends Core {
		
  	function ListarTodas() 
  	{		
		$this->sql = "SELECT ".$this->campos." FROM formulas_notas WHERE ativo = 'S'";
			
		$this->aplicarFiltrosBasicos();
			
		$this->groupby = "idformula";
		return $this->retornarLinhas();
  	}
	
	
  	function Retornar() 
  	{
		$this->sql = "SELECT ".$this->campos." FROM formulas_notas WHERE ativo = 'S' AND idformula = '".$this->id."'";			
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
  
  /*function validarFormula() {
	$formula = $this->Retornar(); #print_r2($formula);
	
	if (trim($formula['formula']) == '[[MAIOR]]') {
		$array_notas[] = str_replace(',','.',$this->post["nota_normal_1"]);
		$array_notas[] = str_replace(',','.',$this->post["nota_normal_2"]);
		$array_notas[] = str_replace(',','.',$this->post["nota_virtual_1"]);
		$array_notas[] = str_replace(',','.',$this->post["nota_virtual_2"]);
		$array_notas[] = str_replace(',','.',$this->post["nota_lancada"]);

		$this->retorno["sucesso"] = true;
		$this->retorno["valor"] = max($array_notas);
		$this->retorno["formula"] = $formula["formula"];
		return $this->retorno;
	}
	
	if(!preg_match("/^[MAIORNVP0-9*>+=\][\-\/<!\'?;_@#\|\n\t\r\f :.&()]+$/",$formula["formula"])) {
	  $this->retorno["erro"] = true;
	} else { #print_r2($this->post);
	  $valorVariaveis = array(
		"[[N][1]]" => $this->post["nota_normal_1"],
		"[[N][2]]" => $this->post["nota_normal_2"],
		"[[V][1]]" => $this->post["nota_virtual_1"],
		"[[V][2]]" => $this->post["nota_virtual_2"],
		"[[PN][1]]" => intval($this->post["peso_normal_1"]),
		"[[PN][2]]" => intval($this->post["peso_normal_2"]),
		"[[PV][1]]" => intval($this->post["peso_virtual_1"]),
		"[[PV][2]]" => intval($this->post["peso_virtual_2"])
	  );#print_r2($valorVariaveis,true);
	  $this->retorno["formula"] = strtr($formula["formula"],$valorVariaveis);
	  
	  $formula["formula"] = str_replace(';',',',$formula["formula"]);
	  
	  $valorVariaveis = array(
		"[[N][1]]" => str_replace(',','.',str_replace('.','',$this->post["nota_normal_1"])),
		"[[N][2]]" => str_replace(',','.',str_replace('.','',$this->post["nota_normal_2"])),
		"[[V][1]]" => str_replace(',','.',str_replace('.','',$this->post["nota_virtual_1"])),
		"[[V][2]]" => str_replace(',','.',str_replace('.','',$this->post["nota_virtual_2"])),
		"[[PN][1]]" => intval($this->post["peso_normal_1"]),
		"[[PN][2]]" => intval($this->post["peso_normal_2"]),
		"[[PV][1]]" => intval($this->post["peso_virtual_1"]),
		"[[PV][2]]" => intval($this->post["peso_virtual_2"]),
		">>" => "max",
		"<<" => "min",
		"//" => "calculaMedia",
		"@" => "round",
		"_" => "floor",
		"#" => "ceil"
	  );
	  $formulaValores = strtr($formula["formula"],$valorVariaveis);
	  if(@eval('$valor = '.$formulaValores.';') === false) {
		$this->retorno["erro"] = true;
	  } else {
		$this->retorno["sucesso"] = true;
		$this->retorno["valor"] = $valor;
	  };	  
	}
	return $this->retorno;
  }*/
  
  function validarFormula($media) {
	$this->retorno = null;
	$formula = $this->Retornar(); #print_r2($this->post);
    #print_r2($formula['formula']);
	if (trim($formula['formula']) == '[[MAIOR]]') {
		if($this->post)
			foreach ($this->post as $tipo => $nota)
				$array_notas[$tipo] = str_replace(',','.',str_replace('.','',$nota));

		$this->retorno["sucesso"] = true;
		$this->retorno["valor"] = is_array($array_notas) ? max($array_notas) : 0.00;
		$this->retorno["formula"] = $formula["formula"];
		return $this->retorno;
	}
	
	if(!preg_match("/^[MAIORNVPMC0-9*>+=\][\-\/<!\'?;_@#&\|\n\t\r\f :.&()]+$/",$formula["formula"])) {
	  $this->retorno["erro"] = true;
	} else { #print_r2($this->post);
	  $valorVariaveis = array();
	  foreach ($this->post as $tipo => $nota)
		$valorVariaveis['[[N][' . $tipo . ']]'] = $nota;

	  $this->retorno["formula"] = strtr($formula["formula"],$valorVariaveis);
	  $this->retorno["formula"] = preg_replace('#\[\[N\]\[(\d+)\]\]#i', '0,00', $this->retorno["formula"]);
	  $formula["formula"] = str_replace(';',',',$formula["formula"]);
      
      if(!$media){ $media = 0.00; }
      $formula["formula"] = preg_replace('#\[\[MC\]\]#', $media, $formula["formula"]);

	  foreach ($this->post as $tipo => $nota)
		$valorVariaveis['[[N][' . $tipo . ']]'] = str_replace(',','.',str_replace('.','',$nota));
	  
	  $valorVariaveis[">>"] = "max";
	  $valorVariaveis["<<"] = "min";
	  $valorVariaveis["//"] = "calculaMedia";
	  $valorVariaveis["@"] = "round";
	  $valorVariaveis["_"] = "floor";
	  $valorVariaveis["#"] = "ceil";
	  $valorVariaveis["!"] = "abs";
      $valorVariaveis["&&"] = "calculaNotaMaiorMedia";
	  #print_r2($valorVariaveis);
	  $formulaValores = strtr($formula["formula"],$valorVariaveis);
	  $formulaValores = preg_replace('#\[\[N\]\[(\d+)\]\]#i', 0.00, $formulaValores);
	  if(@eval('$valor = '.$formulaValores.';') === false) {
		$this->retorno["erro"] = true;
	  } else {
		$this->retorno["sucesso"] = true;
		$this->retorno["valor"] = $valor;
	  };	  
	}
	return $this->retorno;
  }
  
  function AssociarSindicato() {
	foreach($this->post["sindicatos"] as $idsindicato) {
	  $this->sql = "select count(idformula_sindicato) as total, idformula_sindicato from formulas_notas_sindicatos where idformula = '".$this->id."' and idsindicato = '".intval($idsindicato)."'";
	  $totalAssociado = $this->retornarLinha($this->sql); 
	  if($totalAssociado["total"] > 0) {
		$this->sql = "update formulas_notas_sindicatos set ativo = 'S' where idformula_sindicato = ".$totalAssociado["idformula_sindicato"];
		$associar = $this->executaSql($this->sql);
		$this->monitora_qual = $totalAssociado["idformula_sindicato"];					
	  } else {
		$this->sql = "insert into formulas_notas_sindicatos set ativo = 'S', data_cad = now(), idformula = '".$this->id."', idsindicato = '".intval($idsindicato)."'";
		$associar = $this->executaSql($this->sql);
		$this->monitora_qual = mysql_insert_id();
	  }
	  if($associar){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 1;
		$this->monitora_onde = 203;
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }
	}
	return $this->retorno;
  }


  function DesassociarSindicato() {
		
	include_once("../includes/validation.php");		
	$regras = array(); // stores the validation rules
		
	//VERIFICANDO SE OPÃ‡ÃƒO REMOVER FOI MARCADA
	if(!$this->post["remover"])
	  $regras[] = "required,remover,remover_vazio";
		
	//VALIDANDO FORMULÃRIO
	$erros = validateFields($this->post, $regras);

	//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
	if(!empty($erros)){
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "update formulas_notas_sindicatos set ativo = 'N' where idformula_sindicato = ".intval($this->post["remover"]);
	  $desassociar = $this->executaSql($this->sql);

	  if($desassociar){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 203;
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
	
  function ListarSindicatosAssociadas() {		
	$this->sql = "select 
					".$this->campos." 
				  from
					formulas_notas_sindicatos fni
					inner join sindicatos i ON (fni.idsindicato = i.idsindicato)
				  where 
					i.ativo = 'S' and 
					fni.ativo= 'S' and 
					fni.idformula = ".intval($this->id);
		
	$this->limite = -1;
	$this->ordem = "asc";
	$this->ordem_campo = "i.nome";
	return $this->retornarLinhas();
  }
	
  function BuscarSindicatos() {		
	$this->sql = "select 
					i.idsindicato as 'key', 
					i.nome_abreviado as value 
				  from 
					sindicatos i 
				  where 
					i.nome_abreviado LIKE '%".$this->get["tag"]."%' AND 
					i.ativo = 'S' and 
					i.ativo_painel = 'S' and 
					not exists (
					  select 
						fni.idformula 
					  from 
						formulas_notas_sindicatos fni 
					  where 
						fni.idsindicato = i.idsindicato and 
						fni.idformula = '".intval($this->id)."' and 
						fni.ativo = 'S'
					)";

	$this->limite = -1;
	$this->ordem_campo = "i.nome";
	$this->groupby = "i.idsindicato";
	$this->retorno = $this->retornarLinhas();
					
	return json_encode($this->retorno);		
  }
	
}
?>