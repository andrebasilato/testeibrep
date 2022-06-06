<?php
class Relatorio extends Core {

	function gerarRelatorio() { 
	
		$dias_mes = date("t", mktime(0, 0, 0, $_GET['mes'], 1, $_GET['ano']));
		
		$retorno['ano'] = $_GET['ano'];
		$retorno['ano_anterior'] = ($_GET['ano'] - 1);
		$retorno['mes_numero'] = $_GET['mes'];
		$retorno['mes_nome'] = $GLOBALS['meses'][$GLOBALS['config']['idioma_padrao']][$_GET['mes']];
		
		$this->sql = "SELECT idsituacao, nome FROM `matriculas_workflow` WHERE ativo='S' and ativa='S'";
		$situacaoMatriculado = $this->retornarLinha($this->sql);
		
		$retorno['uteis'] = dias_uteis($_GET['ano'].'-'.$_GET['mes'].'-01', $_GET['ano'].'-'.$_GET['mes'].'-'.$dias_mes, 6);
		if ($_GET['mes'] != date('m')) {
			$retorno['uteis_trabalhados'] = $retorno['uteis'];
		} else {
			$retorno['uteis_trabalhados'] = dias_uteis($_GET['ano'].'-'.$_GET['mes'].'-01', date('Y-m-d'), 6);
		}
		
		//RETORNAR O TOTAL DE MATRICULAS NO MÊS DO ANO ATUAL E DO ANO ANTERIOR
		/*$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) AS valor,
					date_format(mh.data_cad,'%Y') AS ano
				FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					INNER JOIN matriculas_historicos mh ON (mh.idmatricula=mat.idmatricula)							   
				WHERE 
					mat.ativo = 'S' AND 
					mh.tipo = 'situacao' AND 
					mh.acao = 'modificou' AND 
					mh.para = '".$situacaoMatriculado["idsituacao"]."' AND 
					(date_format(mh.data_cad,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' OR
					date_format(mh.data_cad,'%Y-%m') = '".($_GET['ano']-1).'-'.$_GET['mes']."') ";*/
		
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) AS valor,
					date_format(mat.data_registro,'%Y') AS ano
				FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor						   
				WHERE 
					mat.ativo = 'S' AND 
					(date_format(mat.data_registro,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' OR
					date_format(mat.data_registro,'%Y-%m') = '".($_GET['ano']-1).'-'.$_GET['mes']."') ";							
									
		if(!$_GET["q"]["1|mat.idsindicato"] && $_SESSION['adm_gestor_sindicato'] != 'S'){
			$this->sql .= ' and mat.idsindicato in ('.$_SESSION['adm_sindicatos'].')';	
		}									
							
		if (is_array($_GET['q'])) {
		    foreach ($_GET['q'] as $campo => $valor) {
				$campo = explode('|', $campo);
				$valor = str_replace("'", '', $valor);
				if (($valor || $valor === '0') && $valor <> 'todos') {
				    if ($campo[0] == 1) {
						$sql .= ' and '.$campo[1].' = "'.$valor.'" ';
				    } elseif ($campo[0] == 2)  {
						$sql .= ' and '.$campo[1].' like "%'.urldecode($valor).'%" ';
				    } elseif ($campo[0] == 7)  {
						if (count($valor)) {
							$sql .= ' and '.$campo[1].' in ('.implode(',', $valor).') ';
						}
				    }
				} 
		    }
		}
		
		$sql .= ' GROUP BY ano ORDER BY ano';
		
		$query_v = $this->executaSql($sql);
		while ($linha = mysql_fetch_assoc($query_v)) {
			$retorno['tabela_1'][$linha['ano']] = $linha;
		}
		
		if(!$retorno['tabela_1'][$retorno['ano_anterior']]['quantidade']) {
			$retorno['tabela_1']['porcentagem']['quantidade'] = 100;
		} else {
			$retorno['tabela_1']['porcentagem']['quantidade'] = 
				($retorno['tabela_1'][$retorno['ano']]['quantidade'] / $retorno['tabela_1'][$retorno['ano_anterior']]['quantidade']) * 100;
		}
		
		if(!$retorno['tabela_1'][$retorno['ano_anterior']]['valor']) {
			$retorno['tabela_1']['porcentagem']['valor'] = 100;
		} else {
			$retorno['tabela_1']['porcentagem']['valor'] = 
				($retorno['tabela_1'][$retorno['ano']]['valor'] / $retorno['tabela_1'][$retorno['ano_anterior']]['valor']) * 100;
		}
		
		//RETORNAR O TOTAL DE MATRICULAS NO MÊS DO ANO AGRUPADO POR ESTADO DA INSTITUIÇÃO
		/*$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado,
					est.sigla as estado
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					INNER JOIN matriculas_historicos mh ON (mh.idmatricula=mat.idmatricula)							   
				WHERE 
					mat.ativo = 'S' and 
					mh.tipo = 'situacao' and 
					mh.acao = 'modificou' and 
					mh.para = '".$situacaoMatriculado["idsituacao"]."' and 
					date_format(mh.data_cad,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' ";*/
							
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado,
					est.sigla as estado
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor						   
				WHERE 
					mat.ativo = 'S' and 
					date_format(mat.data_registro,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' ";
		
		if (is_array($_GET['q'])) {
		    foreach ($_GET['q'] as $campo => $valor) {
				$campo = explode('|', $campo);
				$valor = str_replace("'", '', $valor);
				if (($valor || $valor === '0') && $valor <> 'todos') {
				    if ($campo[0] == 1) {
						$sql .= ' and '.$campo[1].' = "'.$valor.'" ';
				    } elseif ($campo[0] == 2)  {
						$sql .= ' and '.$campo[1].' like "%'.urldecode($valor).'%" ';
				    } elseif ($campo[0] == 7)  {
						if (count($valor)) {
							$sql .= ' and '.$campo[1].' in ('.implode(',', $valor).') ';
						}
				    }
				} 
		    }
		}
							
		$sql .= ' group by ins.idestado';
		$query_v = $this->executaSql($sql);
		
		$totais = array();
		while ($linha = mysql_fetch_assoc($query_v)) {
			$retorno['tabela_2']['totais']['valor'] += $linha['valor'];
			$retorno['tabela_2']['totais']['quantidade'] += $linha['quantidade'];
			$retorno['tabela_2']['estados'][$linha['idestado']] = $linha;
			$retorno['tabela_2']['estados'][$linha['idestado']]['dia_atual'] = $this->retornarVendasDiaAtualEstado($linha['idestado'], $situacaoMatriculado["idsituacao"]);
			$total_dia_atual_quantidade += $retorno['tabela_2']['estados'][$linha['idestado']]['dia_atual']['quantidade'];
			$total_dia_atual_valor += $retorno['tabela_2']['estados'][$linha['idestado']]['dia_atual']['valor'];
		}
		
		//RETORNAR A META NO MÊS POR INSTITUICAO
		$sql = "SELECT
						    mi.quantidade as valor, est.idestado, est.sigla as estado
						 FROM
							metas_cursos mi
								INNER JOIN sindicatos ins ON mi.idsindicato = ins.idsindicato
								INNER JOIN estados est ON ins.idestado = est.idestado
							    WHERE mi.ativo='S' 
									and date_format(mi.mes,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' ";
		
		if($_GET['q']["1|mat.idsindicato"]) $sql .= " and ins.idsindicato = '".$_GET['q']["1|mat.idsindicato"]."' ";
		if($_GET['q']["1|est.idregiao"]) $sql .= " and est.idregiao = '".$_GET['q']["1|est.idregiao"]."' ";
							
		$sql .= ' group by est.idestado';
		
		$query_v = $this->executaSql($sql);
		while ($linha = mysql_fetch_assoc($query_v)) {
			$retorno['tabela_2']['totais']['meta'] += $linha['valor'];
			$retorno['tabela_2']['estados'][$linha['idestado']]['meta'] = $linha['valor'];
			$retorno['tabela_2']['estados'][$linha['idestado']]['porcentagem'] = 
				($retorno['tabela_2']['estados'][$linha['idestado']]['quantidade'] / $linha['valor']) * 100;
				
			if (!$retorno['tabela_2']['estados'][$linha['idestado']]['estado'])
				$retorno['tabela_2']['estados'][$linha['idestado']]['estado'] = $linha['estado'];
		}

		$retorno['tabela_2']['projetado']['quantidade'] = 
			($retorno['tabela_2']['totais']['quantidade'] * ($retorno['uteis'] / $retorno['uteis_trabalhados']));
		$retorno['tabela_2']['projetado']['valor'] = 
			($retorno['tabela_2']['totais']['valor'] * ($retorno['uteis'] / $retorno['uteis_trabalhados']));
			
		$retorno['tabela_2']['media']['quantidade'] = 
			($retorno['tabela_2']['totais']['quantidade'] / $retorno['uteis_trabalhados']);
		$retorno['tabela_2']['media']['valor'] = 
			($retorno['tabela_2']['totais']['valor'] / $retorno['uteis_trabalhados']);
			
		$retorno['tabela_2']['totais']['dia_atual']['quantidade'] = $total_dia_atual_quantidade;
		$retorno['tabela_2']['totais']['dia_atual']['valor'] = $total_dia_atual_valor;
			
		//RETORNAR O TOTAL DE MATRICULAS AGRUPADO POR VENDEROR E INSTITUIÇÃO
		/*$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado,
					est.sigla as estado,
					mat.idvendedor,
					ven.nome as vendedor,
					ven.regiao
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					INNER JOIN matriculas_historicos mh ON (mh.idmatricula=mat.idmatricula)							   
				WHERE 
					mat.ativo='S' and 
					mh.tipo='situacao' and 
					mh.acao='modificou' and 
					mh.para='".$situacaoMatriculado["idsituacao"]."' and 
					date_format(mh.data_cad,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' ";*/
					
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado,
					est.sigla as estado,
					mat.idvendedor,
					ven.nome as vendedor
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor					   
				WHERE 
					mat.ativo = 'S' and 
					date_format(mat.data_registro,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' ";
							
		if (is_array($_GET['q'])) {
		    foreach ($_GET['q'] as $campo => $valor) {
				$campo = explode('|', $campo);
				$valor = str_replace("'", '', $valor);
				if (($valor || $valor === '0') && $valor <> 'todos') {
				    if ($campo[0] == 1) {
						$sql .= ' and '.$campo[1].' = "'.$valor.'" ';
				    } elseif ($campo[0] == 2)  {
						$sql .= ' and '.$campo[1].' like "%'.urldecode($valor).'%" ';
				    } elseif ($campo[0] == 7)  {
						if (count($valor)) {
							$sql .= ' and '.$campo[1].' in ('.implode(',', $valor).') ';
						}
				    }
				} 
		    }
		}
							
		$sql .= ' group by ins.idestado, mat.idvendedor';
		$query_v = $this->executaSql($sql);
		
		$totais = array();
		while ($linha = mysql_fetch_assoc($query_v)) {
			$retorno['tabela_3']['totais']['estados'][$linha['idestado']]['estado'] = $linha['estado'];	
			$retorno['tabela_3']['totais']['estados'][$linha['idestado']]['quantidade'] += $linha['quantidade'];
			$retorno['tabela_3']['totais']['estados'][$linha['idestado']]['valor'] += $linha['valor'];	
			$retorno['tabela_3']['totais']['quantidade'] += $linha['quantidade'];
			$retorno['tabela_3']['totais']['valor'] += $linha['valor'];	

			if (!$retorno['tabela_3']['estados'][$linha['idestado']]) {
				$retorno['tabela_3']['totais']['total_estados']++;
			}
			$linha['diario_media'] = ($linha['valor'] / $linha['quantidade']);
			$retorno['tabela_3']['estados'][$linha['idestado']]['estado'] = $linha['estado'];
			$retorno['tabela_3']['estados'][$linha['idestado']]['vendedores'][$linha['idvendedor']] = $linha;
		}
		
		foreach ($retorno['tabela_3']['totais']['estados'] as $idestado => $estado) {
			$retorno['tabela_3']['totais']['estados'][$idestado]['valor_unitario'] += ($estado['valor'] / $estado['quantidade']);
		}
		$retorno['tabela_3']['totais']['valor_media'] += ($retorno['tabela_3']['totais']['valor'] / $retorno['tabela_3']['totais']['total_estados']);
		$retorno['tabela_3']['totais']['quantidade_media'] += ($retorno['tabela_3']['totais']['quantidade'] / $retorno['tabela_3']['totais']['total_estados']);
		$retorno['tabela_3']['totais']['unitario_media'] = 
			($retorno['tabela_3']['totais']['valor_media'] / $retorno['tabela_3']['totais']['quantidade_media']);
		unset($retorno['tabela_3']['totais']['quantidade'], $retorno['tabela_3']['totais']['valor'], $retorno['tabela_3']['totais']['total_estados']);
		
		//RETORNAR O TOTAL DE MATRICULAS AGRUPADO POR ESTADO DA INSTITUIÇÃO - GRÁFICO 1
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado,
					est.sigla as estado,
					mi.quantidade as meta
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					LEFT JOIN metas_cursos mi ON (mi.idsindicato = ins.idsindicato and mi.idcurso = mat.idcurso and date_format(mi.mes,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' and mi.ativo = 'S')
				WHERE 
					mat.ativo = 'S' and 
					date_format(mat.data_registro,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' ";
							
		if (is_array($_GET['q'])) {
		    foreach ($_GET['q'] as $campo => $valor) {
				$campo = explode('|', $campo);
				$valor = str_replace("'", '', $valor);
				if (($valor || $valor === '0') && $valor <> 'todos') {
				    if ($campo[0] == 1) {
						$sql .= ' and '.$campo[1].' = "'.$valor.'" ';
				    } elseif ($campo[0] == 2)  {
						$sql .= ' and '.$campo[1].' like "%'.urldecode($valor).'%" ';
				    } elseif ($campo[0] == 7)  {
						if (count($valor)) {
							$sql .= ' and '.$campo[1].' in ('.implode(',', $valor).') ';
						}
				    }
				} 
		    }
		}
							
		$sql .= ' group by ins.idestado';
		$query_v = $this->executaSql($sql);

		$retorno['grafico_1']['estados']['ideal']['porcentagem'] = (($retorno['uteis_trabalhados'] * 100) / $retorno['uteis']);
		$retorno['grafico_1']['estados']['ideal']['estado'] = 'Ideal';
		while ($linha = mysql_fetch_assoc($query_v)) {
			$linha['porcentagem'] = (($linha['quantidade'] * 100) / $linha['meta']);
			$retorno['grafico_1']['estados'][$linha['idestado']] = $linha;
			$total_quantidade_1 += $linha['quantidade'];
			$total_meta_1 += $linha['meta'];
		}
		$retorno['grafico_1']['estados']['total']['porcentagem'] = (($total_quantidade_1 * 100) / $total_meta_1);
		$retorno['grafico_1']['estados']['total']['estado'] = 'Total';
		
		//RETORNAR O TOTAL DE MATRICULAS DO MESMO MÊS NO ANO ANTERIOR AGRUPADO POR ESTADO DA INSTITUIÇÃO
		/*$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado,
					est.sigla as estado
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					INNER JOIN matriculas_historicos mh ON (mh.idmatricula=mat.idmatricula)								
				WHERE 
					mat.ativo='S' and 
					mh.tipo='situacao' and 
					mh.acao='modificou' and 
					mh.para='".$situacaoMatriculado["idsituacao"]."' and 
					date_format(mh.data_cad,'%Y-%m') = '".($_GET['ano']-1).'-'.$_GET['mes']."' ";*/
							
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado,
					est.sigla as estado
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor							
				WHERE 
					mat.ativo='S' and 
					date_format(mat.data_registro,'%Y-%m') = '".($_GET['ano']-1).'-'.$_GET['mes']."' ";
		
		if (is_array($_GET['q'])) {
		    foreach ($_GET['q'] as $campo => $valor) {
				$campo = explode('|', $campo);
				$valor = str_replace("'", '', $valor);
				if (($valor || $valor === '0') && $valor <> 'todos') {
				    if ($campo[0] == 1) {
						$sql .= ' and '.$campo[1].' = "'.$valor.'" ';
				    } elseif ($campo[0] == 2)  {
						$sql .= ' and '.$campo[1].' like "%'.urldecode($valor).'%" ';
				    } elseif ($campo[0] == 7)  {
						if (count($valor)) {
							$sql .= ' and '.$campo[1].' in ('.implode(',', $valor).') ';
						}
				    }
				} 
		    }
		}
							
		$sql .= ' group by ins.idestado';
		$query_v = $this->executaSql($sql);
		
		$retorno['grafico_2']['estados']['ideal']['porcentagem'] = $retorno['grafico_1']['estados']['ideal']['porcentagem'];
		while ($linha = mysql_fetch_assoc($query_v)) {
			$retorno['grafico_2']['ano_anterior'][$linha['idestado']] = $linha;
		}
		
		foreach ($retorno['grafico_1']['estados'] as $idestado => $estado) {
			if (!$retorno['grafico_2']['ano_anterior'][$idestado]['quantidade']) {
				$estado['porcentagem'] = 100;
			} else {
				$estado['porcentagem'] = (($estado['quantidade'] * 100) / $retorno['grafico_2']['ano_anterior'][$idestado]['quantidade']);
			}
			$retorno['grafico_2']['estados'][$idestado] = $estado;
			$total_meta_2 += $estado['meta'];
			$total_quantidade_2 += $estado['quantidade'];
			$total_quantidade_anterior_2 += $retorno['grafico_2']['ano_anterior'][$idestado]['quantidade'];
			$total_valor_2 += $estado['valor'];
			$total_valor_anterior_2 += $retorno['grafico_2']['ano_anterior'][$idestado]['valor'];
		}

		if(!$total_quantidade_anterior_2) {
			$retorno['grafico_2']['estados']['total']['porcentagem'] = 100;
		} else {
			$retorno['grafico_2']['estados']['total']['porcentagem'] = 
				(($total_quantidade_2 * 100) / $total_quantidade_anterior_2);
		}
	
		//GRÁFICO 3
		$retorno['grafico_3'] = $retorno['grafico_1'];
		unset($retorno['grafico_3']['estados']['ideal'], $retorno['grafico_3']['estados']['total']);
		$retorno['grafico_3']['estados']['total']['quantidade'] = $total_quantidade_2;
		$retorno['grafico_3']['estados']['total']['meta'] = $total_meta_2;
		$retorno['grafico_3']['estados']['total']['estado'] = 'Total';
		
		//GRÁFICO 4
		$retorno['grafico_temp_4'] = $retorno['grafico_2'];
		unset($retorno['grafico_temp_4']['estados']['ideal'], $retorno['grafico_temp_4']['estados']['total']);
		
		foreach ($retorno['grafico_temp_4']['estados'] as $idestado => $estado) {
			$estado['valor_ano_anterior'] = $retorno['grafico_temp_4']['ano_anterior'][$idestado]['valor'];
			$retorno['grafico_4']['estados'][$idestado] = $estado;
		}
		unset($retorno['grafico_temp_4']);
		$retorno['grafico_4']['estados']['total']['valor'] = $total_valor_2;
		$retorno['grafico_4']['estados']['total']['valor_ano_anterior'] = $total_valor_anterior_2;
		$retorno['grafico_4']['estados']['total']['estado'] = 'Total';
		unset($retorno['grafico_2']['ano_anterior']);
		
		//GRÁFICO 5 e 6
		/*$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) AS valor,
					est.idestado,
					est.sigla AS estado,
					date_format(mh.data_cad,'%Y') AS ano
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					INNER JOIN matriculas_historicos mh ON (mh.idmatricula=mat.idmatricula)								
				WHERE 
					mat.ativo='S' AND 
					mh.tipo='situacao' AND 
					mh.acao='modificou' AND
					mh.para='".$situacaoMatriculado["idsituacao"]."' AND 
					(
						date_format(mh.data_cad,'%Y-%m') <= '".$_GET['ano'].'-'.$_GET['mes']."' AND 
						date_format(mh.data_cad,'%Y') = '".$_GET['ano']."'
					) OR (
						date_format(mh.data_cad,'%Y-%m') <= '".($_GET['ano']-1).'-'.$_GET['mes']."' AND
						date_format(mh.data_cad,'%Y') = '".($_GET['ano']-1)."'
					) ";*/
					
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) AS valor,
					est.idestado,
					est.sigla AS estado,
					date_format(mat.data_registro,'%Y') AS ano
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor							
				WHERE 
					mat.ativo='S' AND 
					(
						date_format(mat.data_registro,'%Y-%m') <= '".$_GET['ano'].'-'.$_GET['mes']."' AND 
						date_format(mat.data_registro,'%Y') = '".$_GET['ano']."'
					) OR (
						date_format(mat.data_registro,'%Y-%m') <= '".($_GET['ano']-1).'-'.$_GET['mes']."' AND
						date_format(mat.data_registro,'%Y') = '".($_GET['ano']-1)."'
					) ";
							
		if (is_array($_GET['q'])) {
		    foreach ($_GET['q'] as $campo => $valor) {
				$campo = explode('|', $campo);
				$valor = str_replace("'", '', $valor);
				if (($valor || $valor === '0') && $valor <> 'todos') {
				    if ($campo[0] == 1) {
						$sql .= ' AND '.$campo[1].' = "'.$valor.'" ';
				    } elseif ($campo[0] == 2)  {
						$sql .= ' AND '.$campo[1].' LIKE "%'.urldecode($valor).'%" ';
				    } elseif ($campo[0] == 7)  {
						if (count($valor)) {
							$sql .= ' AND '.$campo[1].' IN ('.implode(',', $valor).') ';
						}
				    }
				} 
		    }
		}
							
		$sql .= ' group by ano, ins.idestado';
		$query_v = $this->executaSql($sql);

		while ($linha = mysql_fetch_assoc($query_v)) {
			$retorno['grafico_temp_5'][$linha['ano']]['estados'][$linha['idestado']] = $linha;
		}
		foreach ($retorno['grafico_temp_5'][$_GET['ano']]['estados'] as $idestado => $estado) {
			$estado['valor_ano_anterior'] = $retorno['grafico_temp_5'][$_GET['ano']-1]['estados'][$idestado]['valor'];
			$estado['quantidade_ano_anterior'] = $retorno['grafico_temp_5'][$_GET['ano']-1]['estados'][$idestado]['quantidade'];
			$retorno['grafico_5_6']['estados'][$idestado] = $estado;
			$total_quantidade_5 += $estado['quantidade'];
			$total_valor_5 += $estado['valor'];
			$total_quantidade_anterior_5 += $estado['quantidade_ano_anterior'];
			$total_valor_anterior_5 += $estado['valor_ano_anterior'];
		}
		unset($retorno['grafico_temp_5']);
		$retorno['grafico_5_6']['estados']['total']['quantidade_ano_anterior'] = $total_quantidade_anterior_5;
		$retorno['grafico_5_6']['estados']['total']['valor_ano_anterior'] = $total_valor_anterior_5;
		$retorno['grafico_5_6']['estados']['total']['quantidade'] = $total_quantidade_5;
		$retorno['grafico_5_6']['estados']['total']['valor'] = $total_valor_5;
		$retorno['grafico_5_6']['estados']['total']['estado'] = 'Total';
		
		#print_r2($retorno, true);
		return $retorno;							  
	}
	
	private function retornarVendasDiaAtualEstado($idestado, $situacao_matriculado) {
		//RETORNAR AS VENDAS DO DIA ATUAL
		/*$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					INNER JOIN matriculas_historicos mh ON (mh.idmatricula=mat.idmatricula)							   
				WHERE 
					mat.ativo = 'S' and 
					mh.tipo = 'situacao' and 
					mh.acao = 'modificou' and 
					mh.para = '".$situacao_matriculado."' and 
					date_format(mh.data_cad,'%Y-%m-%d') = '".date('Y-m-d')."' and 
					est.idestado = " . $idestado;*/
					
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN estados est ON ins.idestado = est.idestado
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor						   
				WHERE 
					mat.ativo = 'S' and 
					date_format(mat.data_registro,'%Y-%m-%d') = '".date('Y-m-d')."' and 
					est.idestado = " . $idestado;
							
		if (is_array($_GET['q'])) {
		    foreach ($_GET['q'] as $campo => $valor) {
				$campo = explode('|', $campo);
				$valor = str_replace("'", '', $valor);
				if (($valor || $valor === '0') && $valor <> 'todos') {
				    if ($campo[0] == 1) {
						$sql .= ' and '.$campo[1].' = "'.$valor.'" ';
				    } elseif ($campo[0] == 2)  {
						$sql .= ' and '.$campo[1].' like "%'.urldecode($valor).'%" ';
				    } elseif ($campo[0] == 7)  {
						if (count($valor)) {
							$sql .= ' and '.$campo[1].' in ('.implode(',', $valor).') ';
						}
				    }
				} 
		    }
		}
							
		return $this->retornarLinha($sql);
	}
	
	function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem") {
			
			// Buscando os idiomas do formulario
			include("idiomas/pt_br/index.php");
			echo '<table class="zebra-striped" id="sortTableExample">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>Filtro</th>';
			echo '<th>Valor</th>';
			echo '</tr>';
			echo '</thead>';
			foreach($this->config["formulario"] as $ind => $fieldset){
				foreach($fieldset["campos"] as $ind => $campo){
					if($campo["nome"]{0} == "q"){
					  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
					  $campoAux = $_GET["q"][$campoAux];
					  
					  if($campo["sql_filtro"]){
					  	  if($campo["sql_filtro"] == "array"){
							  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
							  $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAux]];
						  } else {
							  $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
							  $seleciona = mysql_query($sql);
							  $linha = mysql_fetch_assoc($seleciona);
							  $campoAux = $linha[$campo["sql_filtro_label"]];
						  }
					  }
					  
					} elseif(is_array($_GET[$campo["nome"]])){
						
					  if($campo["array"]){
						  foreach($_GET[$campo["nome"]] as $ind => $val){
							 $_GET[$campo["nome"]][$ind] = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$val];
						  }
					  } elseif($campo["sql_filtro"]){
						  foreach($_GET[$campo["nome"]] as $ind => $val){
							 $sql = str_replace("%",$val,$campo["sql_filtro"]);
							 $seleciona = mysql_query($sql);
							 $linha = mysql_fetch_assoc($seleciona);
							 $_GET[$campo["nome"]][$ind] = $linha[$campo["sql_filtro_label"]];
						  }
					  }
						
					  $campoAux = implode($_GET[$campo["nome"]], ", ");					
					} else {
					  if($campo["nome"] == 'mes') {
						$campoAux = $GLOBALS['meses_idioma'][$this->config['idioma_padrao']][$_GET[$campo["nome"]]]; 
					  } else
						$campoAux = $_GET[$campo["nome"]]; 
					}
					if($campoAux <> ""){				  
						echo '<tr>';
						echo '<td><strong>'.$idioma[$campo["nomeidioma"]].'</strong></td>';	
						echo '<td>'.$campoAux.'</td>'; 
						echo '</tr>';
					}
				}
			}
			echo '</table><br>';			
			
		}
		
	function RetornarCursosSindicato() {
		$this->sql = "SELECT c.idcurso, c.nome
						  FROM cursos c
						  INNER JOIN cursos_sindicatos ci on c.idcurso = ci.idcurso and ci.ativo = 'S'
						  WHERE ci.idsindicato = '".$this->id."'";		
		$query = $this->executaSql($this->sql);
		$this->retorno = array();
		while($row = mysql_fetch_assoc($query)){
			$this->retorno[] = $row;
		}
		echo json_encode($this->retorno);
	}
		
}

?>