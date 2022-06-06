<?
class Metas extends Core
{
		
	function ListarTodas() {		
		$this->sql = "SELECT ".$this->campos." FROM
							metas where ativo='S'";
		
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
		
		$this->groupby = "idmeta";
		return $this->retornarLinhas();
	}
	
	
	function Retornar() {
		$this->sql = "SELECT ".$this->campos."
							FROM
							 metas m 
							 LEFT JOIN usuarios_adm ua ON m.idusuario_processou = ua.idusuario
							 where m.ativo='S' and m.idmeta='".$this->id."'";			
		return $this->retornarLinha($this->sql);
	}
	
	function Cadastrar() {
		$_POST['ratio'] = str_replace(',','.',str_replace('.','',$_POST['ratio']));
		return $this->SalvarDados();	
	}
	
	function Modificar() {
		$_POST['ratio'] = str_replace(',','.',str_replace('.','',$_POST['ratio']));
		return $this->SalvarDados();	
	}
	
	function Remover() {
		return $this->RemoverDados();	
	}
	
	function ListarCursos() {		
		$this->sql = "SELECT ".$this->campos." FROM	cursos c WHERE c.ativo = 'S' AND c.ativo_painel = 'S' AND
					  NOT EXISTS (
									SELECT mc.idcurso FROM metas_cursos mc WHERE mc.idcurso = c.idcurso AND 
									mc.idmeta = ".$this->id." AND mc.ativo = 'S'
								  )";
		$this->groupby = "c.idcurso";
		return $this->retornarLinhas();
	}
	
	function ListarCursosAss() {		
		$this->sql = "SELECT 
						".$this->campos." 
					  FROM
						cursos c
						INNER JOIN metas_cursos mc ON (c.idcurso = mc.idcurso) 
					  WHERE 
						mc.ativo = 'S' and 
						mc.idmeta = ".intval($this->id);
		
		$this->groupby = "mc.idmeta_curso";
		
		return $this->retornarLinhas();
	}
	
	function AssociarCursos($idmeta, $idcurso) {
		$_POST['valor_qtd'] = str_replace(',','.',str_replace('.','',$_POST['valor_qtd']));		
		
		$this->sql = "select count(idmeta_curso) as total, idmeta_curso from metas_cursos where idmeta = '".intval($idmeta)."' and idcurso = '".intval($idcurso)."'";
		$totalAss = $this->retornarLinha($this->sql); 
		if($totalAss["total"] > 0) {
			$this->sql = "update metas_cursos set ativo = 'S', valor_qtd = '".$_POST['valor_qtd']."' where idmeta_curso = ".$totalAss["idmeta_curso"];
			$associar = $this->executaSql($this->sql);
			$this->monitora_qual = $totalAss["idmeta_curso"];					
		} else {
			$this->sql = "insert into metas_cursos set ativo = 'S', valor_qtd = '".$_POST['valor_qtd']."', data_cad = now(), idmeta = '".intval($idmeta)."', idcurso = '".intval($idcurso)."'";
			$associar = $this->executaSql($this->sql);
			$this->monitora_qual = mysql_insert_id();
		}

		if($associar){
			$this->retorno["sucesso"] = true;
			$this->monitora_oque = 1;
			$this->monitora_onde = 104;
			$this->Monitora();
		} else {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = "erro_atualiza_valor";
			$this->retorno["erros"][] = $this->sql;
			$this->retorno["erros"][] = mysql_error();
		}

		return $this->retorno;
	}
	
	function DesassociarCursos($idmeta_curso) {
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		if(!$idmeta_curso)
			$regras[] = "required,remover,remover_vazio";
		
		$erros = validateFields($this->post, $regras);

		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{			
			$this->sql = "update metas_cursos set ativo = 'N' where idmeta_curso = ".intval($idmeta_curso);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 104;
				$this->monitora_qual = intval($idmeta_curso);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}
		
		return $this->retorno;

	}
	
	function AlterarMetasCursos($idmeta_curso, $valor_meta) {
	
		$valor_meta = str_replace(',','.',str_replace('.','',$valor_meta));
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		if(!$idmeta_curso)
			$regras[] = "required,remover,curso_vazio";
		
		$erros = validateFields($this->post, $regras);

		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{	
			$this->sql = "update metas_cursos set valor_qtd = '".$valor_meta."' where idmeta_curso = ".intval($idmeta_curso);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 2;
				$this->monitora_onde = 104;
				$this->monitora_qual = intval($idmeta_curso);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}
		
		return $this->retorno;
	}
	
	function AlterarVisualizacaoMetaCursos($idmeta, $visualizar_meta) {
		$this->sql = "update metas set exibir_curso = '".$visualizar_meta."' where idmeta = ".intval($idmeta);
		$desassociar = $this->executaSql($this->sql);

		if($desassociar){
			$this->retorno["sucesso"] = true;
			$this->monitora_oque = 2;
			$this->monitora_onde = 45;
			$this->monitora_qual = intval($idmeta);
			$this->Monitora();
		} else {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = $this->sql;
			$this->retorno["erros"][] = mysql_error();
		}
	}
	
	function ListarImobiliarias() {		
		$this->sql = "SELECT ".$this->campos." FROM	imobiliarias i WHERE i.ativo = 'S' AND i.ativo_painel = 'S' AND
					  NOT EXISTS (
									SELECT mi.idimobiliaria FROM metas_imobiliarias mi WHERE mi.idimobiliaria = i.idimobiliaria AND 
									mi.idmeta = ".$this->id." AND mi.ativo = 'S'
								  )";
		$this->groupby = "i.idimobiliaria";
		return $this->retornarLinhas();
	}
	
	function ListarImobiliariasAss() {		
		$this->sql = "SELECT 
						".$this->campos." 
					  FROM
						imobiliarias i
						INNER JOIN metas_imobiliarias mi ON (i.idimobiliaria = mi.idimobiliaria) 
					  WHERE 
						mi.ativo = 'S' and 
						mi.idmeta = ".intval($this->id);
		
		$this->groupby = "mi.idmeta_imobiliaria";
		
		return $this->retornarLinhas();
	}
	
	function AssociarImobiliarias($idmeta, $idimobiliaria) {
		$_POST['valor_qtd'] = str_replace(',','.',str_replace('.','',$_POST['valor_qtd']));	

		$this->sql = "select count(idmeta_imobiliaria) as total, idmeta_imobiliaria from metas_imobiliarias where idmeta = '".intval($idmeta)."' and idimobiliaria = '".intval($idimobiliaria)."'";
		$totalAss = $this->retornarLinha($this->sql); 
		if($totalAss["total"] > 0) {
			$this->sql = "update metas_imobiliarias set ativo = 'S', valor_qtd = '".$_POST['valor_qtd']."' where idmeta_imobiliaria = ".$totalAss["idmeta_imobiliaria"];
			$associar = $this->executaSql($this->sql);
			$this->monitora_qual = $totalAss["idmeta_imobiliaria"];					
		} else {
			$this->sql = "insert into metas_imobiliarias set ativo = 'S', valor_qtd = '".$_POST['valor_qtd']."', data_cad = now(), idmeta = '".intval($idmeta)."', idimobiliaria = '".intval($idimobiliaria)."'";
			$associar = $this->executaSql($this->sql);
			$this->monitora_qual = mysql_insert_id();
		}

		if($associar){
			$this->retorno["sucesso"] = true;
			$this->monitora_oque = 1;
			$this->monitora_onde = 114;
			$this->Monitora();
		} else {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = "erro_atualiza_valor";
			$this->retorno["erros"][] = $this->sql;
			$this->retorno["erros"][] = mysql_error();
		}

		return $this->retorno;
	}
	
	function DesassociarImobiliarias($idmeta_imobiliaria) {
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		if(!$idmeta_imobiliaria)
			$regras[] = "required,remover,remover_vazio";
		
		$erros = validateFields($this->post, $regras);

		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{			
			$this->sql = "update metas_imobiliarias set ativo = 'N' where idmeta_imobiliaria = ".intval($idmeta_imobiliaria);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 114;
				$this->monitora_qual = intval($idmeta_imobiliaria);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}
		
		return $this->retorno;
	}
	
	function AlterarMetasImobiliarias($idmeta_imobiliaria, $valor_meta) {
	
		$valor_meta = str_replace(',','.',str_replace('.','',$valor_meta));
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		if(!$idmeta_imobiliaria)
			$regras[] = "required,remover,imobiliaria_vazio";
		
		$erros = validateFields($this->post, $regras);

		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{	
			$this->sql = "update metas_imobiliarias set valor_qtd = '".$valor_meta."' where idmeta_imobiliaria = ".intval($idmeta_imobiliaria);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 2;
				$this->monitora_onde = 114;
				$this->monitora_qual = intval($idmeta_imobiliaria);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}
		
		return $this->retorno;
	}
	
	function AlterarVisualizacaoMetaImobiliarias($idmeta, $visualizar_meta) {
		$this->sql = "update metas set exibir_imobiliaria = '".$visualizar_meta."' where idmeta = ".intval($idmeta);
		$desassociar = $this->executaSql($this->sql);

		if($desassociar){
			$this->retorno["sucesso"] = true;
			$this->monitora_oque = 2;
			$this->monitora_onde = 111;
			$this->monitora_qual = intval($idmeta);
			$this->Monitora();
		} else {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = $this->sql;
			$this->retorno["erros"][] = mysql_error();
		}
	}
	
	function ListarVendedores() {		
		$this->sql = "SELECT ".$this->campos." FROM	vendedores v WHERE v.ativo = 'S' AND
					  NOT EXISTS (
									SELECT mv.idvendedor FROM metas_vendedores mv WHERE mv.idvendedor = v.idvendedor AND 
									mv.idmeta = ".$this->id." AND mv.ativo = 'S'
								  )";
		$this->groupby = "v.idvendedor";
		return $this->retornarLinhas();
	}
	
	function ListarVendedoresAss() {		
		$this->sql = "SELECT 
						".$this->campos." 
					  FROM
						vendedores v
						INNER JOIN metas_vendedores mv ON (v.idvendedor = mv.idvendedor) 
					  WHERE 
						mv.ativo = 'S' and 
						mv.idmeta = ".intval($this->id);
		$this->groupby = "mv.idmeta_vendedor";
		
		return $this->retornarLinhas();
	}
	
	function AssociarVendedores($idmeta, $idvendedor) {
		$_POST['valor_qtd'] = str_replace(',','.',str_replace('.','',$_POST['valor_qtd']));	

		$this->sql = "select count(idmeta_vendedor) as total, idmeta_vendedor from metas_vendedores where idmeta = '".intval($idmeta)."' and idvendedor = '".intval($idvendedor)."'";
		$totalAss = $this->retornarLinha($this->sql); 
		if($totalAss["total"] > 0) {
			$this->sql = "update metas_vendedores set ativo = 'S', valor_qtd = '".$_POST['valor_qtd']."' where idmeta_vendedor = ".$totalAss["idmeta_vendedor"];
			$associar = $this->executaSql($this->sql);
			$this->monitora_qual = $totalAss["idmeta_vendedor"];					
		} else {
			$this->sql = "insert into metas_vendedores set ativo = 'S', valor_qtd = '".$_POST['valor_qtd']."', data_cad = now(), idmeta = '".intval($idmeta)."', idvendedor = '".intval($idvendedor)."'";
			$associar = $this->executaSql($this->sql);
			$this->monitora_qual = mysql_insert_id();
		}

		if($associar){
			$this->retorno["sucesso"] = true;
			$this->monitora_oque = 1;
			$this->monitora_onde = 105;
			$this->Monitora();
		} else {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = "erro_atualiza_valor";
			$this->retorno["erros"][] = $this->sql;
			$this->retorno["erros"][] = mysql_error();
		}

		return $this->retorno;
	}
	
	function DesassociarVendedores($idmeta_vendedor) {
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		if(!$idmeta_vendedor)
			$regras[] = "required,remover,remover_vazio";
		
		$erros = validateFields($this->post, $regras);

		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{			
			$this->sql = "update metas_vendedores set ativo = 'N' where idmeta_vendedor = ".intval($idmeta_vendedor);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 105;
				$this->monitora_qual = intval($idmeta_vendedor);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}
		
		return $this->retorno;
	}
	
	function AlterarMetasVendedores($idmeta_vendedor, $valor_meta) {
	
		$valor_meta = str_replace(',','.',str_replace('.','',$valor_meta));
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		if(!$idmeta_vendedor)
			$regras[] = "required,remover,vendedor_vazio";
		
		$erros = validateFields($this->post, $regras);

		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{	
			$this->sql = "update metas_vendedores set valor_qtd = '".$valor_meta."' where idmeta_vendedor = ".intval($idmeta_vendedor);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 2;
				$this->monitora_onde = 105;
				$this->monitora_qual = intval($idmeta_vendedor);
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}
		
		return $this->retorno;
	}
	
	function AlterarVisualizacaoMetaVendedores($idmeta, $visualizar_meta, $exibir_ranking_vendedor) {
		$this->sql = "update metas set exibir_vendedor = '".$visualizar_meta."', exibir_ranking_vendedor = '".$exibir_ranking_vendedor."' where idmeta = ".intval($idmeta);
		$desassociar = $this->executaSql($this->sql);

		if($desassociar){
			$this->retorno["sucesso"] = true;
			$this->monitora_oque = 2;
			$this->monitora_onde = 105;
			$this->monitora_qual = intval($idmeta);
			$this->Monitora();
		} else {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = $this->sql;
			$this->retorno["erros"][] = mysql_error();
		}
	}
	
	function processarMeta($array_empreendimentos, $array_imobiliarias, $array_corretores) {
		mysql_query("START TRANSACTION");
		foreach($array_empreendimentos as $emp) {
			if(!$emp['valor_total']) $emp['valor_total'] = 0;
			$sql_emp = "insert into metas_processadas set data_cad = NOW(),
														  idempreendimento = '".$emp['idempreendimento']."',
														  idmeta = '".$this->url[3]."',
														  meta = '".$emp['valor_qtd']."',
														  total_quantidade = '".$emp['total']."',
														  total_valor = '".$emp['valor_total']."'	";
			$inserir_emp = $this->executaSql($sql_emp);
			if(!$inserir_emp) {
				mysql_query("ROLLBACK");
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = 'erro_processa_empreendimentos';
				return $this->retorno;
			}
		}
		
		foreach($array_imobiliarias as $imob) {
			if(!$imob['valor_total']) $imob['valor_total'] = 0;
			$sql_imob = "insert into metas_processadas set data_cad = NOW(),
														  idimobiliaria = '".$imob['idimobiliaria']."',
														  idmeta = '".$this->url[3]."',
														  meta = '".$imob['valor_qtd']."',
														  total_quantidade = '".$imob['total']."',
														  total_valor = '".$imob['valor_total']."'	";
			$inserir_emp = $this->executaSql($sql_imob);
			if(!$inserir_emp) {
				mysql_query("ROLLBACK");
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = 'erro_processa_imobiliarias';
				return $this->retorno;
			}
		}
		
		foreach($array_corretores as $corr) {
			if(!$corr['valor_total']) $corr['valor_total'] = 0;
			$sql_corr = "insert into metas_processadas set data_cad = NOW(),
														  idcorretor = '".$corr['idcorretor']."',
														  idmeta = '".$this->url[3]."',
														  meta = '".$corr['valor_qtd']."',
														  total_quantidade = '".$corr['total']."',
														  total_valor = '".$corr['valor_total']."'	";
			$inserir_emp = $this->executaSql($sql_corr);
			if(!$inserir_emp) {
				mysql_query("ROLLBACK");
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = 'erro_processa_corretores';
				return $this->retorno;
			}
		}
		
		$sql = "update metas set idusuario_processou = '".$this->idusuario."', data_processou = NOW() where idmeta = '".$this->url[3]."' ";
		$processou_meta = $this->executaSql($sql);
		if(!$processou_meta) {
			mysql_query("ROLLBACK");
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'erro_processa_meta';
			return $this->retorno;
		} else {
			mysql_query("COMMIT");
			$this->retorno["sucesso"] = true;
			return $this->retorno;
		}		
	}
	
	function ListarMetasEmpreendimentos($idempreendimento) {
		$this->sql = "select m.* from metas m
						  inner join metas_empreendimentos me on m.idmeta = me.idmeta 
					  where me.idempreendimento = '".$idempreendimento."' and me.ativo = 'S' and m.exibir_empreendimento = 'S' ";
		$this->limit = -1;
		return $this->retornarLinhas();		
	}
	
	function ListarMetasImobiliarias($idimobiliaria) {
		$this->sql = "select m.* from metas m
						  inner join metas_imobiliarias mi on m.idmeta = mi.idmeta 
					  where mi.idimobiliaria = '".$idimobiliaria."' and mi.ativo = 'S' and m.exibir_imobiliaria = 'S' ";
		
		if(is_array($_GET["q"])) {
			foreach($_GET["q"] as $campo => $valor) {
				$campo = explode("|",$campo);
				$valor = str_replace("'","",$valor);
				if(($valor || $valor === "0") and $valor <> "todos") {
					if($campo[0] == 1) {
						$this->sql .= " and ".$campo[1]." = '".$valor."' ";
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
		
		$this->limit = -1;
		return $this->retornarLinhas();		
	}
	
	function ListarMetasCorretores($idcorretor) {
		$this->sql = "select m.* from metas m
						  inner join metas_corretores mc on m.idmeta = mc.idmeta 
					  where mc.idcorretor = '".$idcorretor."' and mc.ativo = 'S' and m.exibir_corretor = 'S' ";
		$this->limit = -1;
		return $this->retornarLinhas();		
	}
	
	function listarVendasPeriodo($data_banco, $idcorretor, $tipo, $idimobiliaria = null) {
		$this->sql = "SELECT * FROM `reservas_workflow` WHERE ativo='S' and vendida='S'";
		$situacaoVendas = $this->retornarLinha($this->sql);
		$this->sql = "SELECT * FROM `reservas_workflow` WHERE ativo='S' and distrato='S'";
		$situacaoDistratos = $this->retornarLinha($this->sql);
		
		if($tipo == 'QTD')
			$campo = "COUNT(re.idreserva)";
		else
			$campo = "SUM(re.valor_contrato)";
		
		$sql_vendas_p = "SELECT
						  $campo AS total
							  FROM
							   reservas re
							   INNER JOIN reservas_historicos rh ON (rh.idreserva=re.idreserva)
							   INNER JOIN empreendimentos_unidades eu ON (re.idunidade=eu.idunidade)
							   INNER JOIN empreendimentos_blocos eb ON (eu.idbloco=eb.idbloco)	
							   INNER JOIN empreendimentos_etapas et ON (et.idetapa=eb.idetapa)	
							   INNER JOIN empreendimentos e ON (e.idempreendimento=et.idempreendimento)	
							   WHERE re.ativo='S' and rh.tipo='situacao' and rh.acao='modificou' and rh.para='".$situacaoVendas["idsituacao"]."' and DATE_FORMAT(rh.data_cad,'%Y-%m') = '".$data_banco."' ";
							   if($idcorretor)
								  $sql_vendas_p .= " and re.idcorretor = '".$idcorretor."' ";
							   if($idimobiliaria)
								  $sql_vendas_p .= " and re.idimobiliaria = '".$idimobiliaria."' ";
		$vendas_p = $this->retornarLinha($sql_vendas_p);
		
		$sql_distratos_p = "SELECT
						  $campo AS total
							  FROM
							   reservas re
							   INNER JOIN reservas_historicos rh ON (rh.idreserva=re.idreserva)
							   INNER JOIN empreendimentos_unidades eu ON (re.idunidade=eu.idunidade)
							   INNER JOIN empreendimentos_blocos eb ON (eu.idbloco=eb.idbloco)	
							   INNER JOIN empreendimentos_etapas et ON (et.idetapa=eb.idetapa)	
							   INNER JOIN empreendimentos e ON (e.idempreendimento=et.idempreendimento)	
							   WHERE re.ativo='S' and rh.tipo='situacao' and rh.acao='modificou' and rh.para='".$situacaoDistratos["idsituacao"]."' and DATE_FORMAT(rh.data_cad,'%Y-%m') = '".$data_banco."' ";
							   if($idcorretor)
								  $sql_distratos_p .= " and re.idcorretor = '".$idcorretor."' ";
							   if($idimobiliaria)
								  $sql_vendas_p .= " and re.idimobiliaria = '".$idimobiliaria."' ";
		$distratos_p = $this->retornarLinha($sql_distratos_p);	
		
		//return $vendas_p['total']-$distratos_p['total'];
		return $vendas_p['total'];
	}
	
}

?>