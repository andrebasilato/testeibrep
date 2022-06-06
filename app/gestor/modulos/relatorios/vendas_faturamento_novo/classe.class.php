<?php
class Relatorio extends Core {

	function gerarRelatorio($cron = false) {    
    
        $sql_feriados = 'select idferiado, date_format(data, "%d") as dia from feriados where ativo = "S" and ativo_painel  = "S" and DATE_FORMAT(data, "%m-%Y") = "' . $_GET['mes'].'-'.$_GET['ano'] . '" ';
        
        $resultado_feriados = $this->executaSql($sql_feriados);
        while ($fer = mysql_fetch_assoc($resultado_feriados)) {
            
			$timestamp = strtotime($_GET['ano'].'-'.$_GET['mes'].'-'.$fer['dia']);
			$dia = date('N', $timestamp);
			if ($dia < 6) { //FERIADO Q CAI FINAL DE SEMANA NAO PRECISA ENTRAR POIS JA É RETIRADO NOS DIAS UTEIS
				
				$feriados[$fer['idferiado']]['dia'] = $fer['dia'];
           
				$sql_estados = 'select idestado from feriados_estados where idferiado = "' . $fer['idferiado'] . '" and ativo = "S" ';
				$resultado_estados = $this->executaSql($sql_estados);
				while ($estado = mysql_fetch_assoc($resultado_estados)) {
					$feriados[$fer['idferiado']]['estados'][$estado['idestado']] = $estado['idestado'];
				}
			}
        }         
        $retorno['feriados'] = $feriados;        
	
		$dias_mes = date("t", mktime(0, 0, 0, $_GET['mes'], 1, $_GET['ano']));
		$dias_mes_ano_anterior = date("t", mktime(0, 0, 0, $_GET['mes'], 1, ($_GET['ano'] - 1)));
		
		$retorno['dias_mes'] = $dias_mes;
		$retorno['ano']['nome'] = $_GET['ano'];
		$retorno['ano_anterior']['nome'] = ($_GET['ano'] - 1);
		$retorno['mes_numero'] = $_GET['mes'];
		$retorno['mes_nome'] = $GLOBALS['meses'][$GLOBALS['config']['idioma_padrao']][$_GET['mes']];
		$retorno['primeiro_dia'] = "01/".$_GET['mes']."/".$_GET['ano'];
		$retorno['ultimo_dia'] = $dias_mes."/".$_GET['mes']."/".$_GET['ano'];;
		
		$retorno['uteis'] = dias_uteis($_GET['ano'].'-'.$_GET['mes'].'-01', $_GET['ano'].'-'.$_GET['mes'].'-'.$dias_mes, 6);
		$retorno['uteis_acumulados'] = dias_uteis($_GET['ano'].'-01-01', $_GET['ano'].'-'.$_GET['mes'].'-'.$dias_mes, 6);
		if ($_GET['mes'] != date('m')) {
			$retorno['uteis_trabalhados'] = $retorno['uteis'];
			$retorno['uteis_acumulados_trabalhados'] = $retorno['uteis_acumulados'];
		} else {
			$data = date('Y-m-d');
			
			if($cron)
				$data = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
			
			$retorno['uteis_trabalhados'] = dias_uteis($_GET['ano'].'-'.$_GET['mes'].'-01', $data, 6); //Aqui!
			$retorno['uteis_acumulados_trabalhados'] = dias_uteis($_GET['ano'].'-01-01', $data, 6); //Aqui!

		}

		$retorno['uteis_acumulados'] = dias_uteis($_GET['ano'].'-01-01', $_GET['ano'].'-'.$_GET['mes'].'-'.$dias_mes, 6);

		$this->sql = "SELECT idsituacao, nome FROM `matriculas_workflow` WHERE ativo='S' and cancelada='S'";
		$situacaoCancelada = $this->retornarLinha($this->sql);
		
		if (count($_GET['situacao'])) {
			$retorno['situacoesInclusas'] = $_GET['situacao'];
		} else {
			$this->sql = "SELECT idsituacao, nome FROM `matriculas_workflow` WHERE ativo='S' and cancelada<>'S'";
			$situacoesInclusas = $this->executaSql($this->sql);

			while ($situacao = mysql_fetch_assoc($situacoesInclusas)) {
				$retorno['situacoesInclusas'][] = $situacao["idsituacao"];
			}
		}

			
		//print_r2($retorno['situacoesInclusas']);
		$retorno['URL_situacoesInclusas'] = "&idsituacao[]=".implode("&idsituacao[]=", $retorno['situacoesInclusas']);		
	
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) AS valor,
					date_format(mat.data_registro,'%Y') AS ano
				FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato					
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					INNER JOIN estados est ON ins.idestado_competencia = est.idestado						   
				WHERE 
					mat.ativo = 'S' AND					
					(date_format(mat.data_registro,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' OR
					date_format(mat.data_registro,'%Y-%m') = '".($_GET['ano']-1).'-'.$_GET['mes']."') ";							
									
		$sql = $this->aplicarFiltros($sql);          
		
		$sql .= ' GROUP BY ano ORDER BY ano';
		$query_v = $this->executaSql($sql);
		while ($linha = mysql_fetch_assoc($query_v)) {
			if($linha['ano'] == $_GET['ano']) {
				$retorno['ano']["dados"] = $linha;
			} else {
				$retorno['ano_anterior']["dados"] = $linha;
			}
		}

		//RETORNAR AS VENDAS NO MÊS POR ESTADO
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado,
					est.sigla as estado
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					INNER JOIN estados est ON ins.idestado_competencia = est.idestado
				WHERE 
					mat.ativo = 'S' and					
					date_format(mat.data_registro,'%Y-%m') >= '".($_GET['ano'] - 1)."-01' AND 
					date_format(mat.data_registro,'%Y-%m') <= '".($_GET['ano'] - 1).'-'.$_GET['mes']."' ";

		$sql = $this->aplicarFiltros($sql);  
							
		$sql .= ' group by ins.idestado_competencia';
		$query_v = $this->executaSql($sql);
		
		$totais = array();

		while ($linha = mysql_fetch_assoc($query_v)) {
			$retorno['acumulado_estados_ano_anterior'][$linha['idestado']] = $linha;
		}

		//RETORNAR AS VENDAS NO MÊS POR ESTADO
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado,
					est.sigla as estado
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					INNER JOIN estados est ON ins.idestado_competencia = est.idestado
				WHERE 
					mat.ativo = 'S' and					
					date_format(mat.data_registro,'%Y-%m') >= '".$_GET['ano']."-01' AND 
					date_format(mat.data_registro,'%Y-%m') <= '".$_GET['ano'].'-'.$_GET['mes']."' ";

		$sql = $this->aplicarFiltros($sql);  

							
		$sql .= ' group by ins.idestado_competencia';
		$query_v = $this->executaSql($sql);
		
		$totais = array();

		while ($linha = mysql_fetch_assoc($query_v)) {
			$retorno['acumulado_estados'][$linha['idestado']] = $linha;
		}	
		
		//RETORNAR AS VENDAS NO MÊS POR ESTADO
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado,
					est.sigla as estado
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					INNER JOIN estados est ON ins.idestado_competencia = est.idestado
											   
				WHERE 
					mat.ativo = 'S' and					
					date_format(mat.data_registro,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' ";

		$sql = $this->aplicarFiltros($sql);      	
							
		$sql .= ' group by ins.idestado_competencia';		
		
		$query_v = $this->executaSql($sql);
		
		$totais = array();
		while ($linha = mysql_fetch_assoc($query_v)) {
			$retorno['estados'][$linha['idestado']] = $linha;
		}	
		
		
		//RETORNAR A META NO MÊS POR INSTITUICAO
		$sql = "SELECT
						    mi.mes, sum(mi.quantidade) as quantidade, sum(mi.valor) as valor, est.idestado, est.sigla as estado
						 FROM
							metas_cursos mi
								INNER JOIN sindicatos ins ON mi.idsindicato = ins.idsindicato
								INNER JOIN estados est ON ins.idestado_competencia = est.idestado
							    WHERE mi.ativo='S' 
									and date_format(mi.mes,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' ";
		
		if($_SESSION['adm_gestor_sindicato'] <> "S" && $this->idusuario) {
			if (count($_SESSION['adm_sindicatos'])) {
				$sql .= ' and ins.idsindicato in ('.$_SESSION['adm_sindicatos'].')';
			} else {//VEM DO CRON 24 HORAS
				$sql .= ' and ins.idsindicato in ('.$_GET['adm_sindicatos'].')';
			}
		}

		if($_GET['idregiao']){
			$sql .= " and est.idregiao = '".$_GET['idregiao']."' ";	
		}
		
		if($_GET['idestado']){
			$sql .= " and ins.idestado_competencia = '".$_GET['idestado']."' ";	
		}			 

		if($_GET['idsindicato']){
			$sql .= " and ins.idsindicato = '".$_GET['idsindicato']."' ";	
		}
		
		if($_GET['idcurso']){
			$sql .= " and mi.idcurso = '".$_GET['idcurso']."' ";	
		}	
							
		$sql .= ' group by ins.idestado_competencia';

		$query_v = $this->executaSql($sql);
		while ($linha = mysql_fetch_assoc($query_v)) {
			$retorno['estados_metas'][$linha['idestado']] = $linha;
		}
		
		
		//RETORNAR O TOTAL DE MATRICULAS AGRUPADO POR VENDEROR E INSTITUIÇÃO
		$sql = "SELECT
					COUNT(mat.idmatricula) AS quantidade,
					SUM(mat.valor_contrato) as valor,
					est.idestado,
					est.sigla as estado,
					mat.idvendedor,
					ven.nome as vendedor,
					-- ven.regiao,
					date_format(mat.data_registro,'%d') as dia
				 FROM
					matriculas mat
					INNER JOIN escolas p ON p.idescola = mat.idescola
					INNER JOIN sindicatos ins ON p.idsindicato = ins.idsindicato
					INNER JOIN vendedores ven ON mat.idvendedor = ven.idvendedor
					INNER JOIN estados est ON ins.idestado_competencia = est.idestado
				WHERE 
					mat.ativo = 'S' and					
					date_format(mat.data_registro,'%Y-%m') = '".$_GET['ano'].'-'.$_GET['mes']."' ";
							
		$sql = $this->aplicarFiltros($sql);  
   	
							
		$sql .= ' group by ins.idestado_competencia, mat.idvendedor, dia';
		$query_v = $this->executaSql($sql);
		
		$totais = array();
		while ($linha = mysql_fetch_assoc($query_v)) {
			$retorno['vendas_dia'][$linha['idestado']]["nome"] = $linha["estado"];
			$retorno['vendas_dia'][$linha['idestado']]["vendedores"][$linha['idvendedor']]["nome"] = $linha["vendedor"];
			$retorno['vendas_dia'][$linha['idestado']]["vendedores"][$linha['idvendedor']]["regiao"] = $linha["regiao"];
			$retorno['vendas_dia'][$linha['idestado']]["vendedores"][$linha['idvendedor']]["dias"][intval($linha['dia'])]["valor"] = $linha["valor"];
			$retorno['vendas_dia'][$linha['idestado']]["vendedores"][$linha['idvendedor']]["dias"][intval($linha['dia'])]["quantidade"] = $linha["quantidade"];
		}
		
		foreach($retorno['vendas_dia'] as $idestado => $dados) {
			foreach($dados['vendedores'] as $idvendedor => $vendedor) {
				$retorno['vendas_dia'][$idestado]["vendedores"][$idvendedor]['dias_trabalhados'] = count($retorno['vendas_dia'][$idestado]["vendedores"][$idvendedor]['dias']);
			}
		}
			
		return $retorno;							  
	}

	private function aplicarFiltros($sql) {

		if($_SESSION['adm_gestor_sindicato'] <> "S" && $this->idusuario) {
			if (count($_SESSION['adm_sindicatos'])) {
				$sql .= ' and mat.idsindicato in ('.$_SESSION['adm_sindicatos'].')';
			} else {//VEM DO CRON 24 HORAS
				$sql .= ' and mat.idsindicato in ('.$_GET['adm_sindicatos'].')';
			}
		}
									
		if($_GET['idsindicato']){
			$sql .= " and mat.idsindicato = '".$_GET['idsindicato']."' ";	
		}

		if($_GET['situacao']){
			$sql .= " and mat.idsituacao in (".implode(",", $_GET['situacao']).") ";	
		}
		
		if($_GET['idestado']){
			$sql .= " and ins.idestado_competencia = '".$_GET['idestado']."' ";	
		}
		
		if($_GET['idregiao']){
			$sql .= " and est.idregiao = '".$_GET['idregiao']."' ";	
		}			
		
		if($_GET['idcurso']){
			$sql .= " and mat.idcurso = '".$_GET['idcurso']."' ";	
		}
        
		if($_GET['bolsa'] <> 'S'){
			$sql .= " and mat.bolsa = 'N' ";	
		}  
        
		if($_GET['combo'] <> 'S'){
			$sql .= " and mat.combo = 'N' ";	
		} 
		return $sql;
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
					 
					  if($campo["sql_filtro"]){
							 $sql = str_replace("%",$_GET[$campo["nome"]],$campo["sql_filtro"]);
							 $seleciona = mysql_query($sql);
							 $linha = mysql_fetch_assoc($seleciona);
							 $_GET[$campo["nome"]] = $linha[$campo["sql_filtro_label"]];
					  }
					  if($campo["nome"] == 'mes') {
						$campoAux = $GLOBALS['meses_idioma'][$this->config['idioma_padrao']][$_GET[$campo["nome"]]]; 
                      } else {
                        $campoAux = $_GET[$campo["nome"]];
                      }

                       
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
		
    function FormataValor($valor,$negrito = false, $compararCom = 0){
        
        if($valor < $compararCom) $cor = "#FF0000";
        else $cor = "#000";
        
        $style = ' ';
        if($negrito) $style .= ' font-weight:bold;';
        
        echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:2px;">
          <tr>
            <td style="color:#CCCCCC; padding:2px;">R$</td>
            <td style="text-align:right; padding:2px; color: '.$cor.';'.$style.'">'.number_format($valor, 2, ',', '.').'</td>
          </tr>
        </table>';
        
    }
    public function retornaSrc($nomeArquivo)
    {
	    $caminhoRelatorios =  $_SERVER['DOCUMENT_ROOT']."/storage/relatorios_gerenciais/";
	    $arquivo = $caminhoRelatorios . $nomeArquivo; 
	    if (!file_exists($arquivo)) {
	    	return "/assets/img/semimagem_api.jpg";
	    }
	    return  "/storage/relatorios_gerenciais/grafico_estados_matriculas.jpg";
    }
    
    
    function FormataNumero($numero,$negrito = false, $compararCom = 0){
        
        if($numero < $compararCom) $cor = "#FF0000";
        else $cor = "#000";
        
        $style = ' ';
        if($negrito) $style .= ' font-weight:bold;';
        
        echo '<span style="color: '.$cor.';'.$style.'">'.number_format((int) $numero, 0, ',', '.').'</span>';
        
    }  
		
}

?>