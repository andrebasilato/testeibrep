<?php 
class Cartoes extends Core {
		
	function ListarTodas() {		
		$this->sql = "select ".$this->campos." from cartoes where ativo = 'S'";

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

	$this->groupby = "idcartao";
		return $this->retornarLinhas();
	}


	function Retornar() {
		$this->sql = "select ".$this->campos." from cartoes where ativo = 'S' and idcartao = '".$this->id."'";			
		return $this->retornarLinha($this->sql);
	}

	function Cadastrar() {

		//Se homologado for Não
        if ($this->post['homologado'] == 'N') {
        	unset($this->post["numero_estabelecimento"]);
        	unset($this->post["token"]);

        	//Retira a validação dos campos que não serão obrigatórios
			foreach($this->config["formulario"][0]["campos"] as $ind => $var){
				if($var["id"] == "form_numero_estabelecimento"){
					unset($this->config["formulario"][0]["campos"][$ind]["validacao"]);
				}

				if($var["id"] == "form_token"){
					unset($this->config["formulario"][0]["campos"][$ind]["validacao"]);
				}
			}
        }

		return $this->SalvarDados();	
	}

	function Modificar() {

		//Se homologado for Não
        if ($this->post['homologado'] == 'N') {
        	unset($this->post["numero_estabelecimento"]);
        	unset($this->post["token"]);
        	
        	//Retira a validação dos campos que não serão obrigatórios
			foreach($this->config["formulario"][0]["campos"] as $ind => $var){
				if($var["id"] == "form_numero_estabelecimento"){
					unset($this->config["formulario"][0]["campos"][$ind]["validacao"]);
				}

				if($var["id"] == "form_token"){
					unset($this->config["formulario"][0]["campos"][$ind]["validacao"]);
				}
			}
        }

		return $this->SalvarDados();	
	}

	function Remover() {
		return $this->RemoverDados();	
	}

	function BuscarSindicato() {
		$this->sql = "SELECT 
							i.idsindicato AS 'key',
							i.nome_abreviado AS value 
						FROM
							sindicatos i 
						WHERE 
							i.nome_abreviado LIKE '%".$_GET["tag"]."%' AND
							i.ativo = 'S' AND
							i.ativo_painel = 'S' AND
							NOT EXISTS (
											SELECT
												ci.idsindicato
											FROM
												cartoes_sindicatos ci
												INNER JOIN cartoes c ON (c.idcartao = ci.idcartao)
											WHERE
												ci.idsindicato = i.idsindicato AND
												ci.ativo = 'S' AND
												c.ativo = 'S'
										)";
		$this->limite = -1;
		$this->ordem_campo = "value";
		$this->groupby = "value";
		
		$dados = $this->retornarLinhas();						
		return json_encode($dados);		
	}
	
	function ListarSindicatosAss() {		
		$this->sql = "select 
						".$this->campos." 
					  from
						sindicatos i
						inner join cartoes_sindicatos ci ON (i.idsindicato = ci.idsindicato) 
					  where 
						ci.ativo = 'S' and 
						ci.idcartao = ".intval($this->id);
		
		$this->groupby = "ci.idcartao_sindicato";		
		return $this->retornarLinhas();
	}	
	
	function AssociarSindicatos($idcartao, $arraySindicatos) {
		foreach($arraySindicatos as $ind => $id) {
			//Verifica se já existe associação de algum cartão com essa sindicato
			$this->sql = "SELECT
								count(ci.idcartao_sindicato) AS total,
								ci.idcartao_sindicato
							FROM
								cartoes_sindicatos ci
								INNER JOIN cartoes c ON (c.idcartao = ci.idcartao)
							WHERE
								ci.idsindicato = '".intval($id)."' AND
								ci.ativo = 'S' AND
								c.ativo = 'S'";
			$existeAssSindicato = $this->retornarLinha($this->sql);

			//Se não existir outro cartão associado à essa sindicato
			if ($existeAssSindicato['total'] == 0) {
				$this->sql = "SELECT
									count(idcartao_sindicato) AS total,
									idcartao_sindicato
								FROM
									cartoes_sindicatos
								WHERE
									idcartao = '".intval($idcartao)."' AND
									idsindicato = '".intval($id)."'";
				$totalAss = $this->retornarLinha($this->sql);
				if($totalAss["total"] > 0) {
					$this->sql = "UPDATE cartoes_sindicatos SET ativo = 'S' WHERE idcartao_sindicato = ".$totalAss["idcartao_sindicato"];
					$associar = $this->executaSql($this->sql);
					$this->monitora_qual = $totalAss["idcartao_sindicato"];					
				} else {
					$this->sql = "INSERT INTO
										cartoes_sindicatos
									SET
										ativo = 'S',
										data_cad = now(),
										idcartao = '".intval($idcartao)."',
										idsindicato = '".intval($id)."'";
					$associar = $this->executaSql($this->sql);
					$this->monitora_qual = mysql_insert_id();
				}
				
				if ($associar) {
					$this->retorno["sucesso"] = true;
					$this->monitora_oque = 1;
					$this->monitora_onde = 220;
					$this->Monitora();
				} else {
					$this->retorno["erro"] = true;
					$this->retorno["erros"][] = $this->sql;
					$this->retorno["erros"][] = mysql_error();
				}
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
			$this->sql = "update cartoes_sindicatos set ativo = 'N' where idcartao_sindicato = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 220;
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

	function RetornarCartaoEscolaSindicato() {		
		$this->sql = "SELECT 
						".$this->campos." 
					  FROM
						cartoes c
						INNER JOIN cartoes_sindicatos ci ON (ci.idcartao = c.idcartao)
						INNER JOIN sindicatos i ON (i.idsindicato = ci.idsindicato)
						INNER JOIN escolas p ON (p.idsindicato = i.idsindicato)
					  WHERE 
						p.idescola = '".intval($this->idescola)."' AND
						c.ativo = 'S' AND
						ci.ativo = 'S'";
		return $this->retornarLinha($this->sql);
	}

	function RetornarCartaoSindicato($idsindicato) {		
		$this->sql = "SELECT 
						c.*
					  FROM
						cartoes c
						INNER JOIN cartoes_sindicatos ci ON (ci.idcartao = c.idcartao)
					  WHERE 
						ci.idsindicato = '".intval($idsindicato)."' AND
						c.ativo = 'S' AND
						ci.ativo = 'S'";
		return $this->retornarLinha($this->sql);
	}
	
}