<?
class Emails_Automaticos_Adm extends Core
{
		
	function ListarTodas() {		
		$this->sql = "SELECT ".$this->campos." FROM
							emails_automaticos_adm where ativo='S'";
		
		$this->aplicarFiltrosBasicos();

		$this->groupby = "idemail";
		return $this->retornarLinhas();
	}
	
	
	function Retornar() {
		$this->sql = "SELECT ".$this->campos."
							FROM
							 emails_automaticos_adm where ativo='S' and idemail='".$this->id."'";		
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
						 NOT EXISTS (SELECT ec.idcurso FROM emails_automaticos_cursos_adm ec WHERE ec.idcurso = c.idcurso AND ec.idemail = '".$this->id."' AND ec.ativo = 'S')";
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
						INNER JOIN emails_automaticos_cursos_adm ec ON (c.idcurso = ec.idcurso) 
					  WHERE 
						ec.ativo = 'S' and c.ativo = 'S' and c.ativo_painel = 'S' and
						ec.idemail = ".intval($this->id);
		
		$this->groupby = "ec.idemail_curso";		
		return $this->retornarLinhas();
	}	
	
	function AssociarCursos($idemail, $arrayCursos) {
		foreach($arrayCursos as $ind => $id) {
					
			  $this->sql = "select count(idemail_curso) as total, idemail_curso from emails_automaticos_cursos_adm where idemail = '".$idemail."' and idcurso = '".$id."'";
			  $totalAss = $this->retornarLinha($this->sql); 
			  if($totalAss["total"] > 0) {
				  $this->sql = "update emails_automaticos_cursos_adm set ativo = 'S' where idemail_curso = ".$totalAss["idemail_curso"];
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = $totalAss["idemail_curso"];					
			  } else {
				  $this->sql = "insert into emails_automaticos_cursos_adm set ativo = 'S', data_cad = now(), idemail = '".$idemail."', idcurso = '".$id."'";
				  $associar = $this->executaSql($this->sql);
				  $this->monitora_qual = mysql_insert_id();
			  }			
			
			if($associar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 195;
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
			$this->sql = "update emails_automaticos_cursos_adm set ativo = 'N' where idemail_curso = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 195;
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
	
}

?>