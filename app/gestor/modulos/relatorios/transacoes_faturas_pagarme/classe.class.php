<?php

class Relatorio extends Core
{
	public function gerarRelatorio()
	{
		if (
			$_GET['q']['de_ate|tipo_data_cad|c.data_vencimento'] == 'PER' &&
			(!$_GET['data_vencimento_de'] || !$_GET['data_vencimento_ate'])
		) {
			unset($_GET['q']['de_ate|tipo_data_cad|c.data_vencimento']);
		}

		if (
			($_GET['q']['de_ate|tipo_data_cad|c.data_cad'] == 'PER' &&
			(!$_GET["data_cad_de"] || !$_GET["data_cad_ate"])) ||
			! array_key_exists($_GET['q']['de_ate|tipo_data_cad|c.data_cad'], $GLOBALS['tipo_data_filtro'][$GLOBALS['config']['idioma_padrao']])
		) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'datas_obrigatorias';
			return $retorno;
		}

		//VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO
		if (dataDiferenca(formataData($_GET['data_cad_de'], 'en', 0), formataData($_GET['data_cad_ate'], 'en', 0), 'D') > 365) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'intervalo_maior_um_ano';
			return $retorno;
		}

		$this->sql = 'SELECT
					  		c.idconta, e.nome_fantasia AS escola, c.valor,
                            c.data_vencimento, c.qnt_matriculas, cw.nome AS situacao,
                            cw.cor_bg AS situacao_cor_bg, cw.cor_nome AS situacao_cor_nome,
                            c.data_modificacao_fatura, c.data_cad,
                            COALESCE(SUM(cvv.qtd_parcelas), e.qtd_parcelas) AS qtd_parcelas,
							e.documento, e.email, p.status AS statusPagarme, p.id AS pagarme_id,
                            s.nome AS sindicato_nome,
							p.date_created,
							c.data_pagamento,
							c.valor_juros
						FROM
							contas c
                            INNER JOIN contas_workflow cw ON (cw.idsituacao = c.idsituacao)
                            INNER JOIN escolas e ON (e.idescola = c.idescola)
							LEFT JOIN cfcs_valores_cursos cvv ON (cvv.idcfc = e.idescola AND cvv.ativo = "S")
                            INNER JOIN sindicatos s ON (s.idsindicato = e.idsindicato)
                            LEFT OUTER JOIN pagarme p ON (p.idpagarme = (
                            	SELECT
                            		pag.idpagarme
                            	FROM
                            		pagarme pag
                            	WHERE
                            		pag.idconta = c.idconta AND pag.ativo = "S"
                            	ORDER BY
                            		pag.idpagarme DESC
                            	LIMIT 1
                        	))
						WHERE
							c.fatura = "S" AND c.ativo = "S"';

		if ($_SESSION['adm_gestor_sindicato'] != 'S') {
            $this->sql .= ' AND c.idsindicato IN (' . $_SESSION['adm_sindicatos'] . ') ';
        }

		if (is_array($_GET['q'])) {
			foreach($_GET['q'] as $campo => $valor) {
				$campo = explode('|', $campo);
				$valor = str_replace('\'', '', $valor);

				if (($valor || $valor === '0') && $valor <> 'todos') {
					if ($campo[0] == 1) {
						$this->sql .= ' AND '.$campo[1].' = "' . $valor . '" ';
					} elseif ($campo[0] == 2)  {
						$this->sql .= ' AND '.$campo[1].' LIKE "%'. urldecode($valor) . '%"';
					} elseif ($campo[0] == 'de_ate') {
						if ($valor == 'HOJ') {
							$this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") = "' . date('Y-m-d') . '"';
						} elseif ($valor == 'ONT') {
							$this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") = "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))) . '"';
						} elseif ($valor == 'SET') {
							$this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") <= "' . date('Y-m-d') . '" AND
								DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y'))) . '"';
						} elseif ($valor == 'QUI') {
							$this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") <= "' . date('Y-m-d') . '"
							AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m-%d") >= "' . date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 15, date('Y'))) . '"';
						} elseif ($valor == 'MAT') {
							$this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m") = "' . date('Y-m') . '"';
						} elseif ($valor == 'MPR') {
							$this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'))) . '"';
						} elseif ($valor == 'MAN') {
							$this->sql .= ' AND DATE_FORMAT(' . $campo[2] . ',"%Y-%m") = "' . date('Y-m', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'))) . '"';
						}
				    }
				}
			}
		}

		if ($_GET['data_cad_de'] && $_GET['q']['de_ate|tipo_data_cad|c.data_cad'] == 'PER') {
			$this->sql .= ' AND DATE_FORMAT(c.data_cad,"%Y-%m-%d") >= "' . formataData($_GET['data_cad_de'],'en',0) . '"';
		}

		if ($_GET['data_cad_ate'] && $_GET['q']['de_ate|tipo_data_cad|c.data_cad'] == 'PER') {
			$this->sql .= ' AND DATE_FORMAT(c.data_cad,"%Y-%m-%d") <= "' . formataData($_GET['data_cad_ate'],'en',0) . '"';
		}

		if ($_GET['data_vencimento_de'] && $_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento'] == 'PER') {
			$this->sql .= ' AND DATE_FORMAT(c.data_vencimento,"%Y-%m-%d") >= "' . formataData($_GET['data_vencimento_de'],'en',0) . '"';
		}

		if ($_GET['data_vencimento_ate'] && $_GET['q']['de_ate|tipo_data_vencimento|c.data_vencimento'] == 'PER') {
			$this->sql .= ' AND DATE_FORMAT(c.data_vencimento,"%Y-%m-%d") <= "' . formataData($_GET['data_vencimento_ate'],'en',0) . '"';
		}

		if ($_GET['situacao']) {
			$this->sql .= ' AND c.idsituacao IN (' . implode(',', $_GET['situacao']) . ') ';
		}

		if ($_GET['statusPagarme']) {
			$this->sql .= ' AND p.status IN ("' . implode('","', $_GET['statusPagarme']) . '") ';
		}

		$this->sql .= ' GROUP BY c.idconta';

		$this->groupby = 'c.idconta';
		$this->ordem_campo = 'c.idconta DESC, p.idpagarme';
		$this->ordem = 'DESC';
		$this->limite = -1;
		return $this->retornarLinhas();
	}

	function gerarTabela($dados,$q = null,$idioma,$configuracao = "listagem")
	{
        $contasObj = new Contas();
		// Buscando os idiomas do formulario
		include 'idiomas/' . $this->config['idioma_padrao'] . '/index.php';
		echo '<table class="zebra-striped" id="sortTableExample">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Filtro</th>';
		echo '<th>Valor</th>';
		echo '</tr>';
		echo '</thead>';
		foreach($this->config["formulario"] as $ind => $fieldset){
			foreach($fieldset["campos"] as $ind => $campo){
				if ($campo["nome"]{0} == "q"){
				  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
				  $campoAux = $_GET["q"][$campoAux];

				  if ($campo["sql_filtro"]){
				  	  if ($campo["sql_filtro"] == "array"){
						  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
						  $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAux]];
					  } else {
						  $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
						  $seleciona = mysql_query($sql);
						  $linha = mysql_fetch_assoc($seleciona);
						  $campoAux = $linha[$campo["sql_filtro_label"]];
					  }
				  }

				} elseif (is_array($_GET[$campo["nome"]])){

				  if ($campo["array"]){
					  foreach($_GET[$campo["nome"]] as $ind => $val){
						 $_GET[$campo["nome"]][$ind] = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$val];
					  }
				  } elseif ($campo["sql_filtro"]){
					  foreach($_GET[$campo["nome"]] as $ind => $val){
						 $sql = str_replace("%",$val,$campo["sql_filtro"]);
						 $seleciona = mysql_query($sql);
						 $linha = mysql_fetch_assoc($seleciona);
						 $_GET[$campo["nome"]][$ind] = $linha[$campo["sql_filtro_label"]];
					  }
				  }

				  $campoAux = implode($_GET[$campo["nome"]], ", ");
				} else {
				  $campoAux = $_GET[$campo["nome"]];
				}
				if ($campoAux <> ""){
					echo '<tr>';
					echo '<td><strong>'.$idioma[$campo["nomeidioma"]].'</strong></td>';
					echo '<td>'.$campoAux.'</td>';
					echo '</tr>';
				}
			}
		}
		echo '</table><br>';


		echo '<table class="zebra-striped" id="sortTableExample">';
		echo '<thead>';
		echo '<tr>';
		foreach($this->config[$configuracao] as $ind => $valor){
				$tamanho = "";
				if ($valor["tamanho"]) $tamanho = ' width="'.$valor["tamanho"].'"';

				$th = '<th class="';
				$th.= $class.' headerSortReloca" '.$tamanho.'>';
				echo $th;

				echo "<div class='headerNew'>".$idioma[$valor["variavel_lang"]]."</div>";

				echo '</th>';

		}
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		echo '<tr>';

		if (count($dados) == 0){
			echo '<tr>';
			echo '<td colspan="'.count($this->config[$configuracao]).'">Nenhuma informação foi encontrada.</td>';
			echo '</tr>';
		} else {
			foreach($dados as $i => $linha){
                                $valorTotal += $linha['valor'];
				echo '<tr>';
				foreach($this->config[$configuracao] as $ind => $valor){
                                        if($valor['nome'] == 'valor'){
                                            $total = $ind;
                                        }
					if ($valor["tipo"] == "banco") {
						echo '<td>'.stripslashes($linha[$valor["valor"]]).'</td>';
					} elseif ($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
						$valor = $valor["valor"]." ?>";
						$valor = eval($valor);
						echo '<td>'.stripslashes($valor).'</td>';
					} elseif ($valor["tipo"] == "array") {
						$variavel = $GLOBALS[$valor["array"]];
						echo '<td>'.$variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]].'</td>';
					} elseif ($valor["busca_tipo"] != "hidden") {
						echo '<td>'.stripslashes($valor["valor"]).'</td>';
					}
				}

				echo '</tr>';
			}
                        
                        echo '<tr>';
                        echo '<td colspan="2" style="text-align: right;">Total:</td>';
                        echo '<td>R$ '.number_format($valorTotal,2,',','.').'</td>';
                        echo '<td colspan="'.(count($this->config[$configuracao]) - 3).'"></td>';
                        echo '</tr>';

		}

		echo '</tbody>';
		echo '</table>';
	}
}
