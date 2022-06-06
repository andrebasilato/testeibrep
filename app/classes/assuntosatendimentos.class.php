<?
class Assuntos_Atendimentos extends Core
{
		
	function ListarTodas() {
		
		$this->sql = "SELECT 
						".$this->campos."
					  FROM 
						atendimentos_assuntos WHERE ativo = 'S'";

		if(is_array($_GET["q"])) {
			foreach($_GET["q"] as $campo => $valor) {
				//explode = Retira, ou seja retira a "|" da variavel campo
				$campo = explode("|",$campo);
				$valor = str_replace("'","",$valor);
				// Listagem se o valor for diferente de Todos ele faz um filtro
				if(($valor || $valor === "0") && $valor <> "todos") {
					if($campo[1] == "subassunto") {
						$this->sql .= " and idassunto IS NULL ";
						break;
					}
					$campo_busca = array("idsubassunto" => "idassunto", "assunto" => "nome", "sla" => "sla", "ativo_painel" => "ativo_painel");
					$campo[1] = $campo_busca[$campo[1]];
					// se campo[0] for = 1 é pq ele tem de ser um valor exato
					if($campo[0] == 1) {
						$this->sql .= " and ".$campo[1]." = '".$valor."' ";
					// se campo[0] for = 2, faz o filtro pelo comando like
					} elseif($campo[0] == 2) {
						$busca = str_replace("\\'","",$valor);
						$busca = str_replace("\\","",$busca);
						$busca = explode(" ",$busca);
						foreach($busca as $ind => $buscar){
							$this->sql .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
						}
					} elseif($campo[0] == 3) {
						$this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
					}
				}
			}
		}
		
		$this->sql_2 = "SELECT 
						".$this->campos_2."
					  FROM 
						atendimentos_assuntos_subassuntos aas
						INNER JOIN atendimentos_assuntos aa ON (aas.idassunto = aa.idassunto) WHERE aas.ativo = 'S'";

		if(is_array($_GET["q"])) {
			foreach($_GET["q"] as $campo => $valor) {
				//explode = Retira, ou seja retira a "|" da variavel campo
				$campo = explode("|",$campo);
				$valor = str_replace("'","",$valor);
				// Listagem se o valor for diferente de Todos ele faz um filtro
				if(($valor || $valor === "0") and $valor <> "todos") {
					$campo_busca = array("idsubassunto" => "aas.idsubassunto", "assunto" => "aa.nome", "subassunto" => "aas.nome", "sla" => "sla", "ativo_painel" => "aas.ativo_painel");
					$campo[1] = $campo_busca[$campo[1]];
					// se campo[0] for = 1 é pq ele tem de ser um valor exato
					if($campo[0] == 1) {
						$this->sql_2 .= " and ".$campo[1]." = '".$valor."' ";
					// se campo[0] for = 2, faz o filtro pelo comando like
					} elseif($campo[0] == 2) {
						$busca = str_replace("\\'","",$valor);
						$busca = str_replace("\\","",$busca);
						$busca = explode(" ",$busca);
						foreach($busca as $ind => $buscar){
							$this->sql_2 .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
						}
					} elseif($campo[0] == 3) {
						$this->sql_2 .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
					}
				} 
			}
		}
		
		$this->sqlAux = " SELECT 
						  (
						  ".str_replace($this->campos, "count(idassunto) as total", $this->sql)."
						  ) + ( 
						  ".str_replace($this->campos_2, "count(aas.idsubassunto) as total", $this->sql_2)."
						  ) AS total";
		$linhaAux = $this->retornarLinha($this->sqlAux);
		$this->total = intval($linhaAux["total"]);
		
		if(intval($this->limite) <= 0 and intval($this->limite) != -1)
		  $this->limite = 1;
	
		$this->paginas = ceil($this->total/$this->limite);
		$this->inicio = ($this->pagina-1) * $this->limite;
				  
		$this->sql = $this->sql." UNION ".$this->sql_2; 
		
		if($this->ordem_campo && $this->ordem) $this->sql .= " order by ".$this->ordem_campo." ".$this->ordem."";
		if($this->limite > 0) $this->sql .= " limit ".$this->inicio.",".$this->limite."";

		$sqlAux = $this->executaSql($this->sql);
		while($linha = mysql_fetch_assoc($sqlAux)){
			$this->retorno[] = $linha;
		}

		return $this->retorno;
	}

	
	function RetornarAssunto() {
		$this->sql = "SELECT 
						".$this->campos."
					  FROM
						atendimentos_assuntos 
					  WHERE ativo = 'S' and 
					  idassunto='".$this->id."'";			
		return $this->retornarLinha($this->sql);
	}
	
	function RetornarTodosAssuntos() {
		$this->sql = 'SELECT 
						'.$this->campos.'
					FROM
						atendimentos_assuntos 
					WHERE 
						ativo = "S" AND 
						ativo_painel = "S"';	

		if ($this->idusuario && $_SESSION['adm_assuntos'])
			$this->sql .= ' AND idassunto in ('.$_SESSION['adm_assuntos'].')';
		
		$this->limite = -1;
		$this->ordem = 'ASC';
		$this->ordem_campo = 'nome';
		$this->groupby = 'nome';
		
		return $this->retornarLinhas($this->sql);
	}
	
	function RetornarSubassunto() {
		$this->sql = "SELECT 
						".$this->campos."
					  FROM
						atendimentos_assuntos_subassuntos aas
						INNER JOIN atendimentos_assuntos aa ON (aas.idassunto = aa.idassunto) 
					  WHERE 
					  	aas.ativo = 'S' and 
						aas.idsubassunto='".$this->id."'";			
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
	
}

?>