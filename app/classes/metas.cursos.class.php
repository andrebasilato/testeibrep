<?php
class Metas_Cursos extends Core {
                				
	function ListarTodas() {
		$retorno = array();
		
		$sql_cursos = 'select c.idcurso, c.nome from cursos c
						inner join cursos_sindicatos ci on c.idcurso = ci.idcurso
						where ci.ativo = "S" and c.ativo = "S" ';
						
		if ($_SESSION['adm_gestor_sindicato'] != 'S') {
			$sql_cursos .= " and ci.idsindicato in (" . $_SESSION['adm_sindicatos'] . ")";
		}
		
		if ($_GET['idsindicato'])
			$sql_cursos .= ' and ci.idsindicato = ' . $_GET['idsindicato'];
			
		$sql_cursos .= ' group by c.idcurso ';
			
		$resultado = $this->executaSql($sql_cursos);
		while ($curso = mysql_fetch_assoc($resultado)) {
			$cursos[$curso['idcurso']] = $curso;
		}
		
		$dataInicio = date("Y-m", mktime(0, 0, 0, $_GET["de_mes"], 1, $_GET["de_ano"]));
		$dataFim    = date("Y-m", mktime(0, 0, 0, $_GET["ate_mes"] + 1, 1, $_GET["ate_ano"]));
		
		$this->sql = "select mc.*, c.nome 
						from metas_cursos mc
						inner join cursos c on mc.idcurso = c.idcurso
						inner join cursos_sindicatos ci on c.idcurso = ci.idcurso and ci.ativo = 'S'
						where 
							(DATE_FORMAT(mc.mes,'%Y-%m') >= '" . $dataInicio . "' 
							and DATE_FORMAT(mc.mes,'%Y-%m') <= '" . $dataFim . "' ) 
							and mc.ativo = 'S' ";
							
		if ($_SESSION['adm_gestor_sindicato'] != 'S') {
			$this->sql .= " and ci.idsindicato in (" . $_SESSION['adm_sindicatos'] . ")";
		}
		
		if ($_GET['idsindicato'])
			$this->sql .= ' and mc.idsindicato = ' . $_GET['idsindicato'];
			
			//echo $this->sql;
							
		$this->ordem = "asc";
		$this->ordem_campo = "mc.data_cad";
		$this->limite = -1;
		$retorno = $this->retornarLinhas();
		
		foreach ($retorno as $curso) {
			$cursos[$curso['idcurso']]['nome'] = $curso['nome'];
			$cursos[$curso['idcurso']]['idcurso'] = $curso['idcurso'];
			$cursos[$curso['idcurso']]['metas'][substr($curso['mes'], 0, -3)] = $curso;
		}
		
		return $cursos;
	}       
				
	function CadastrarModificar() {
                                
		$this->executaSql("begin");
		
		foreach ($this->post["dados"] as $idcurso => $dados) {
			$idmeta   = array();
			$monitora = false;
			foreach ($dados as $mes => $valores) {
						
				$monitora = false;
				
				if ($valores["valor"]) {
					$valores["valor"]     = str_replace(".", "", $valores["valor"]);
					$valores["valor"]     = str_replace(",", ".", $valores["valor"]);
					$valores["valor_sql"] = "'" . $valores["valor"] . "'";
				} else {
					$valores["valor_sql"] = "NULL";
				}
						
				if ($valores["quantidade"]) {
					$valores["quantidade_sql"] = "'" . intval($valores["quantidade"]) . "'";
				} else {
					$valores["quantidade_sql"] = "NULL";
				}
						
				$this->monitora_dadosantigos = NULL;
				$this->monitora_dadosnovos   = NULL;
						
				if ($valores["idmeta"]) {								
					$this->sql = "select * from metas_cursos where idmeta = " . $valores["idmeta"];
					$this->monitora_dadosantigos = $this->retornarLinha($this->sql);
					
					if (($valores["valor"] <> $this->monitora_dadosantigos["valor"]) or ($valores["quantidade"] <> $this->monitora_dadosantigos["quantidade"])) {									
						$monitora = true;
						
						$this->sql = "update metas_cursos set ativo = 'S', valor = " . $valores["valor_sql"] . ", quantidade = " . $valores["quantidade_sql"] . " where idmeta = " . $valores["idmeta"];
						$executa   = $this->executaSql($this->sql);
						
						$this->sql = "select * from metas_cursos where idmeta = " . $valores["idmeta"];
						$this->monitora_dadosnovos = $this->retornarLinha($this->sql);
						
						$this->monitora_oque = 2;
						$this->monitora_qual = $valores["idmeta"];									
					}								
				} else {
					if ($valores["valor"] <> "" or $valores["quantidade"] <> "") {
						$this->sql = "insert into metas_cursos set data_cad = now(), idcurso = " . $idcurso . ", mes = '" . $mes . "-01', valor = " . $valores["valor_sql"] . ", quantidade = " . $valores["quantidade_sql"] . ", idsindicato = " . $_POST['sindicato'];
						$executa   = $this->executaSql($this->sql);
						
						$this->monitora_oque = 1;
						$this->monitora_qual = mysql_insert_id();
						
						$monitora = true;									
					}
				}
				if ($executa) {
					$this->monitora_onde = 185;
					if ($monitora)
						$this->Monitora();
				}
										
			}
		}
		
		$this->executaSql("commit");
		
		$this->retorno["sucesso"] = true;
		return $this->retorno;
	}         
    
}

?>