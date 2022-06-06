<?
class Emails_Automaticos extends Core
{
		
	function ListarTodas() {		
		$this->sql = "SELECT ".$this->campos." FROM
							emails_automaticos where ativo='S'";
		
		$this->aplicarFiltrosBasicos();

		$this->groupby = "idemail";
		return $this->retornarLinhas();
	}
	
	
	function Retornar() {
		$this->sql = "SELECT ".$this->campos."
							FROM
							 emails_automaticos where ativo='S' and idemail='".$this->id."'";		
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
	
	function BuscarCurso() {		
		$this->sql = "select 
						c.idcurso as 'key', c.nome as value 
					  from
						cursos c 
					  where 
					     c.nome like '%".$_GET["tag"]."%' AND 
						 c.ativo = 'S' AND 
						 c.ativo_painel = 'S' AND 
						 NOT EXISTS (SELECT ec.idcurso FROM emails_automaticos_cursos ec WHERE ec.idcurso = c.idcurso AND ec.idemail = '".$this->id."' AND ec.ativo = 'S')";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";
		
		$dados = $this->retornarLinhas();						
		return json_encode($dados);		
	}

	function BuscarSindicato() {		
		$this->sql = "select 
						i.idsindicato as 'key', i.nome_abreviado as value 
					  from
						sindicatos i 
					  where 
					     i.nome_abreviado like '%".$_GET["tag"]."%' AND 
						 i.ativo = 'S' AND 
						 i.ativo_painel = 'S' AND 
						 NOT EXISTS (SELECT ei.idsindicato FROM emails_automaticos_sindicatos ei WHERE ei.idsindicato = i.idsindicato AND ei.idemail = '".$this->id."' AND ei.ativo = 'S')";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";
		
		$dados = $this->retornarLinhas();						
		return json_encode($dados);		
	}
	
	function ListarCursosAss() {		
		$this->sql = "SELECT 
						 ".$this->campos."
					  FROM
						cursos c
						INNER JOIN emails_automaticos_cursos ec ON (c.idcurso = ec.idcurso) 
					  WHERE 
						ec.ativo = 'S' and c.ativo = 'S' and c.ativo_painel = 'S' and
						ec.idemail = ".intval($this->id);
		
		$this->groupby = "ec.idemail_curso";		
		return $this->retornarLinhas();
	}	
	
	function AssociarCursos($idemail, $arrayCursos) {
		foreach($arrayCursos as $ind => $id) {
					
			  $this->sql = "select count(idemail_curso) as total, idemail_curso from emails_automaticos_cursos where idemail = '".$idemail."' and idcurso = '".$id."'";
			  $totalAss = $this->retornarLinha($this->sql); 
			  if($totalAss["total"] > 0) {
				  $this->sql = "update emails_automaticos_cursos set ativo = 'S' where idemail_curso = ".$totalAss["idemail_curso"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idemail_curso"];					
			  } else {
				  $this->sql = "insert into emails_automaticos_cursos set ativo = 'S', data_cad = now(), idemail = '".$idemail."', idcurso = '".$id."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }			
			
			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 164;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
			
		}
		return $this->retorno;
	}	
	
	function DesassociarCursos() {		
		include_once("../includes/validation.php");		
		$regras = array();
		
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";
		
		$erros = validateFields($this->post, $regras);

		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update emails_automaticos_cursos set ativo = 'N' where idemail_curso = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 164;
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
	
	function BuscarOferta() {		
		$this->sql = "select 
						o.idoferta as 'key', o.nome as value 
					  from
						ofertas o 
					  where 
					     o.nome like '%".$_GET["tag"]."%' AND 
						 o.ativo = 'S' AND 
						 o.ativo_painel = 'S' AND 
						 NOT EXISTS (SELECT eo.idoferta FROM emails_automaticos_ofertas eo WHERE eo.idoferta = o.idoferta AND eo.idemail = '".$this->id."' AND eo.ativo = 'S')";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";
		
		$dados = $this->retornarLinhas();						
		return json_encode($dados);		
	}
	
	function ListarOfertasAss() {		
		$this->sql = "SELECT 
						 ".$this->campos."
					  FROM
						ofertas o
						INNER JOIN emails_automaticos_ofertas eo ON (o.idoferta = eo.idoferta) 
					  WHERE 
						eo.ativo = 'S' and o.ativo = 'S' and o.ativo_painel = 'S' and
						eo.idemail = ".intval($this->id);
		
		$this->groupby = "eo.idemail_oferta";		
		return $this->retornarLinhas();
	}	

	function ListarSindicatosAss() {		
		$this->sql = "SELECT 
						 ".$this->campos."
					  FROM
						sindicatos i
						INNER JOIN emails_automaticos_sindicatos ei ON (i.idsindicato = ei.idsindicato) 
					  WHERE 
						ei.ativo = 'S' and i.ativo = 'S' and i.ativo_painel = 'S' and
						ei.idemail = ".intval($this->id);
		
		$this->groupby = "ei.idemail_sindicato";		
		return $this->retornarLinhas();
	}	
	
	function AssociarOfertas($idemail, $arrayOfertas) {
		foreach($arrayOfertas as $ind => $id) {
					
			  $this->sql = "select count(idemail_oferta) as total, idemail_oferta from emails_automaticos_ofertas where idemail = '".$idemail."' and idoferta = '".$id."'";
			  $totalAss = $this->retornarLinha($this->sql); 
			  if($totalAss["total"] > 0) {
				  $this->sql = "update emails_automaticos_ofertas set ativo = 'S' where idemail_oferta = ".$totalAss["idemail_oferta"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idemail_oferta"];					
			  } else {
				  $this->sql = "insert into emails_automaticos_ofertas set ativo = 'S', data_cad = now(), idemail = '".$idemail."', idoferta = '".$id."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }			
			
			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 198;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
			
		}
		return $this->retorno;
	}	

	public function retornaEmailPorTipo($tipo)
    {
        $sql = "SELECT
                   *
                FROM
                   emails_automaticos
                WHERE ativo = 'S'
                AND ativo_painel = 'S'
                AND tipo = '$tipo'";

        return $this->retornarLinha($sql);
	}
	
	function enviarEmailAutomaticoPessoa($email, $pessoa, $pessoasObj, $coreObj)
	{ 
		if ($pessoa['idcurso']) {
			$emailCurso = retornarEmailCurso($pessoa['idcurso'], $coreObj);
		}

		if ($emailCurso) {
			$emailDe = $emailCurso;
		} else {
			$emailDe = $GLOBALS['config']['emailSistema'];
		}

		$nomeDe =  $GLOBALS['config']['tituloEmpresa'];
		$nomePara = utf8_decode($pessoa['nome']);
		$emailPara = $pessoa['email'];
		$assunto = utf8_decode($email['nome']);
		$message = $email['texto'];
		$message = html_entity_decode(htmlentities($message));
		$sms = $email['corpo_sms'];
		$sms = html_entity_decode($sms);
		
		$indice = array();
		
		$variavel = explode('[[ALUNO][', $message);
		if ($variavel) {
			foreach ($variavel as $ind => $val) {
				$id = explode(']]', $val);
				$indice[] = $id[0];
			}
		}
		$variavelSMS = explode('[[ALUNO][', $sms);
		if ($variavelSMS) {
			foreach ($variavelSMS as $ind => $val) {
				$id = explode(']]', $val);
				$indice[] = $id[0];
			}
		}
		if (count($indice) > 0) {
			unset($indice[array_search('', $indice)]);
			foreach ($indice as $ind => $cli) {
				$cli = strtolower($cli);
				if ($cli == 'data_nasc') {
					$message = str_ireplace('[[ALUNO][DATA_NASC]]', formataData($pessoa[$cli], 'br', 0), $message);
					$sms = str_ireplace('[[ALUNO][DATA_NASC]]', formataData($pessoa[$cli], 'br', 0), $sms);
				} elseif ($cli == 'idpais') {
					$message = str_ireplace('[[ALUNO][NACIONALIDADE]]', ($pessoasObj->retornarNomePais($pessoa['idpais'])), $message);
					$sms = str_ireplace('[[ALUNO][NACIONALIDADE]]', ($pessoasObj->retornarNomePais($pessoa['idpais'])), $sms);
				} elseif ($cli == 'documento') {
					if ($pessoa['documento_tipo'] == 'cpf') {
						$message = str_ireplace('[[ALUNO][DOCUMENTO]]', formatar($pessoa[$cli], 'cpf'), $message);
						$sms = str_ireplace('[[ALUNO][DOCUMENTO]]', formatar($pessoa[$cli], 'cpf'), $sms);
					} else {
						$message = str_ireplace('[[ALUNO][DOCUMENTO]]', formatar($pessoa[$cli], 'cnpj'), $message);
						$sms = str_ireplace('[[ALUNO][DOCUMENTO]]', formatar($pessoa[$cli], 'cnpj'), $sms);
					}
				} elseif ($cli == 'banco_cpf_titular') {
					$message = str_ireplace('[[ALUNO][BANCO_CPF_TITULAR]]', formatar($pessoa[$cli], 'cpf'), $message);
					$sms = str_ireplace('[[ALUNO][BANCO_CPF_TITULAR]]', formatar($pessoa[$cli], 'cpf'), $sms);
				} elseif ($cli == 'rg_data_emissao') {
					$message = str_ireplace('[[ALUNO][RG_DATA_EMISSAO]]', formataData($pessoa[$cli], 'br', 0), $message);
					$sms = str_ireplace('[[ALUNO][RG_DATA_EMISSAO]]', formataData($pessoa[$cli], 'br', 0), $sms);
				} elseif ($cli == 'cep') {
					$message = str_ireplace('[[ALUNO][CEP]]', formatar($pessoa[$cli], 'cep'), $message);
					$sms = str_ireplace('[[ALUNO][CEP]]', formatar($pessoa[$cli], 'cep'), $sms);
				} elseif ($cli == 'renda_familiar') {
					$message = str_ireplace('[[ALUNO][RENDA_FAMILIAR]]', number_format($pessoa[$cli], 2, ',', '.'), $message);
					$sms = str_ireplace('[[ALUNO][RENDA_FAMILIAR]]', number_format($pessoa[$cli], 2, ',', '.'), $sms);
				} elseif ($cli == 'estado_civil') {
					$message = str_ireplace('[[ALUNO][ESTADO_CIVIL]]', ($GLOBALS['estadocivil'][$config['idioma_padrao']][$pessoa['estado_civil']]), $message);
					$sms = str_ireplace('[[ALUNO][ESTADO_CIVIL]]', ($GLOBALS['estadocivil'][$config['idioma_padrao']][$pessoa['estado_civil']]), $sms);
				} elseif ($cli == 'idlogradouro') {
					$message = str_ireplace('[[ALUNO][LOGRADOURO]]', ($pessoasObj->retornarNomeLogradouro($pessoa['idlogradouro'])), $message);
					$sms = str_ireplace('[[ALUNO][LOGRADOURO]]', ($pessoasObj->retornarNomeLogradouro($pessoa['idlogradouro'])), $sms);
				} elseif ($cli == 'cidade') {
					$message = str_ireplace('[[ALUNO][CIDADE]]', ($pessoasObj->retornarNomeCidade($pessoa['idcidade'])), $message);
					$sms = str_ireplace('[[ALUNO][CIDADE]]', ($pessoasObj->retornarNomeCidade($pessoa['idcidade'])), $sms);
				} elseif ($cli == 'estado') {
					$message = str_ireplace('[[ALUNO][ESTADO]]', ($pessoasObj->retornarNomeEstado($pessoa['idestado'])), $message);
					$sms = str_ireplace('[[ALUNO][ESTADO]]', ($pessoasObj->retornarNomeEstado($pessoa['idestado'])), $sms);
				}  elseif ($cli == 'nome') {
					$message = str_ireplace('[[ALUNO][' . strtoupper($cli) . ']]', $pessoa[$cli], $message);
					$sms = str_ireplace('[[ALUNO][' . strtoupper($cli) . ']]', $pessoa[$cli], $sms);
				} else {
					$message = str_ireplace('[[ALUNO][' . strtoupper($cli) . ']]', ($pessoa[$cli]), $message);
					$sms = str_ireplace('[[ALUNO][' . strtoupper($cli) . ']]', ($pessoa[$cli]), $sms);
				}
			}
		}

		$email['corpo_sms'] = $sms;

		if ($message) {
			$coreObj->enviarEmail($nomeDe, $emailDe, $assunto, $message, $nomePara, $emailPara, 'layout_branco');
		}
	}
	
	function DesassociarOfertas() {		
		include_once("../includes/validation.php");		
		$regras = array();
		
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";
		
		$erros = validateFields($this->post, $regras);

		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update emails_automaticos_ofertas set ativo = 'N' where idemail_oferta = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 198;
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

	function AssociarSindicatos($idemail, $arraySindicatos) {
		foreach($arraySindicatos as $ind => $id) {
					
			  $this->sql = "select count(idemail_sindicato) as total, idemail_sindicato from emails_automaticos_sindicatos where idemail = '".$idemail."' and idsindicato = '".$id."'";
			  $totalAss = $this->retornarLinha($this->sql); 
			  if($totalAss["total"] > 0) {
				  $this->sql = "update emails_automaticos_sindicatos set ativo = 'S' where idemail_sindicato = ".$totalAss["idemail_sindicato"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idemail_sindicato"];					
			  } else {
				  $this->sql = "insert into emails_automaticos_sindicatos set ativo = 'S', data_cad = now(), idemail = '".$idemail."', idsindicato = '".$id."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }			
			
			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 231;
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
		$regras = array();
		
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";
		
		$erros = validateFields($this->post, $regras);

		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update emails_automaticos_sindicatos set ativo = 'N' where idemail_sindicato = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 231;
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

	function retornarSindicatosEmails($idEmail)
	{
		$this->sql = "SELECT 
						* 
					FROM
						emails_automaticos_sindicatos eas
					INNER JOIN emails_automaticos ea ON (ea.idemail = eas.idemail)
					WHERE eas.ativo='S'
					AND ea.ativo = 'S'
					AND ea.idemail = " . $idEmail;

		$this->groupby = "idemail_sindicato";
		return $this->retornarLinhas();
	}

	function retornarOfertasEmails($idEmail)
	{
		$this->sql = "SELECT 
						* 
					FROM
						emails_automaticos_ofertas eao
					INNER JOIN emails_automaticos ea ON (ea.idemail = eao.idemail)
					WHERE eao.ativo='S'
					AND ea.ativo = 'S'
					AND ea.idemail = " . $idEmail;

		$this->groupby = "idemail_sindicato";
		return $this->retornarLinhas();
	}

	function retornarCursosEmails($idEmail)
	{
		$this->sql = "SELECT 
						* 
					FROM
						emails_automaticos_cursos eac
					INNER JOIN emails_automaticos ea ON (ea.idemail = eac.idemail)
					WHERE eac.ativo='S'
					AND ea.ativo = 'S'
					AND ea.idemail = " . $idEmail;

		$this->groupby = "idemail_sindicato";
		return $this->retornarLinhas();
	}
}

?>