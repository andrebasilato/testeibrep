<?php
class Retornos extends Core
{
	private $query = NULL;
	private $retornoLinha = NULL;
	private $nossonumero = NULL;
	private $datacredito = NULL;
	private $valorboleto = NULL;
	private $valorpago = NULL;
	private $code = NULL;
	private $parcela = NULL;
	private $situation = NULL;
	private $pagamento = NULL;
	private $segmento = NULL;
	private $codMovimento = NULL;
	private $erro = false;
	private $data_ocorrencia = NULL;
	private $data_debito = NULL;
	private $mo_nosso_numero  = NULL;
	

	
	function ListarTodas() {		
		$this->sql = "SELECT ".$this->campos." 
					  FROM retornos re
						  INNER JOIN usuarios_adm ua ON(re.idusuario=ua.idusuario)
						 ";	
	if ($_SESSION["adm_gestor_sindicato"] <> "S")				  
			$this->sql .= " INNER JOIN retornos_sindicatos ri ON(re.idretorno=ri.idretorno)";	
					  
	$this->sql .= "  WHERE re.ativo='S'";	
					  
		if ($_SESSION["adm_gestor_sindicato"] <> "S")
           $this->sql .= " and ri.idsindicato in (" . $_SESSION["adm_sindicatos"] . ") and ri.ativo = 'S' ";
		
		if(is_array($_GET["q"])) {
			foreach($_GET["q"] as $campo => $valor) {
				//explode = Retira, ou seja retira a "|" da variavel campo
				$campo = explode("|",$campo);
				$valor = str_replace("'","",$valor);
				// Listagem se o valor for diferente de Todos ele faz um filtro
				if(($valor || $valor === "0") && $valor <> "todos") {
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
		$this->sql .= " GROUP BY re.idretorno";
		$this->groupby = "re.idretorno";
		return $this->retornarLinhas();
	}
	
	function RetornarParcelasRetorno() {
		$this->sql = "SELECT
							".$this->campos."	  
					  	FROM
							retornos_contas rc
							INNER JOIN retornos re ON (re.idretorno = rc.idretorno)
							INNER JOIN contas c ON (rc.idconta = c.idconta)
							INNER JOIN matriculas m ON (c.idmatricula = m.idmatricula)
							INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
						WHERE
							re.idretorno = '".$this->id."' AND
							re.ativo = 'S'";			
		
		$this->groupby = "rc.idretorno_conta";

		return $this->retornarLinhas();
	}
	
	function Retornar() {
		$this->sql = "SELECT ".$this->campos."
					  FROM retornos
					  WHERE ativo='S' AND idretorno='".$this->id."'";			
		return $this->retornarLinha($this->sql);
	}
	
	function validarArquivoBanco($caminho){
		
		$file = file($caminho);
		foreach($file as $line => $value){
			if($line == 0){
				foreach($GLOBALS["bancos"] as $ind => $val){
					$posicoes = explode("|",$val["posicaocodigo"]); 
					if(substr($value,$posicoes[0],$posicoes[1]) == $val["codigo"]){
						  $this->code = $val["codigo"];  // CODIGO BANCO SE FOR VALIDO
						  $this->posicoes = $val["retorno"];  //TRAZ OS DADOS DE POSIÇÕES DOS NUMEROS
						  break;
					}
				}
				break;
			}
		}
	}
	
	function Cadastrar() {
		
		$this->validarArquivoBanco($_FILES["arquivo"]['tmp_name']);
		
		if(!$this->code){
			$this->retorno['erro'] = true;
			$this->retorno['erros'][] = "banco_invalido";
			return $this->retorno;
		};
		$this->retorno = $this->SalvarDados();
		
	    $this->sql = "UPDATE retornos SET banco = '".$this->code."' WHERE idretorno = '".$this->retorno["id"]."'";
		mysql_query($this->sql) or die(incluirLib("erro",$this->config));
		
		return $this->retorno;
	}
	
	function ProcessarRetorno() {
		
		$this->sql = "SELECT arquivo_servidor FROM retornos WHERE idretorno = '".$this->id."'";
		$this->query = $this->executaSql($this->sql)or die(incluirLib("erro",$this->config));
		$this->retornoLinha = mysql_fetch_assoc($this->query);
		
		$this->validarArquivoBanco($_SERVER["DOCUMENT_ROOT"]."/storage/contas_retornos/".$this->retornoLinha["arquivo_servidor"]);
		
		$file = file($_SERVER["DOCUMENT_ROOT"]."/storage/contas_retornos/".$this->retornoLinha["arquivo_servidor"]);
		
		$sql = "select * from contas_workflow where ativo = 'S' and pago = 'S' LIMIT  1 ";
		$workflow_pago = $this->retornarLinha($sql);

		$totalProcessado = 0;
		$contasFechamento = array();
		$totalFechamento = 0;
		
		foreach($file as $line => $value){
			if($line > 0){
					//Atualiza o retorno informando de qual banco é o retorno
					$this->segmento = explode("|",$this->posicoes["segmento"]); 
					$this->segmento = substr($value,$this->segmento[0],$this->segmento[1]);//Verfica o Segmento, T ou U
					
					if($this->segmento == 'T'){
						///COMEÇO SEGMENTO T
						$this->nossonumero = explode("|",$this->posicoes["nossonumero"]);
						$this->nossonumero = substr($value,$this->nossonumero[0],$this->nossonumero[1]);
						//$this->nossonumero = ltrim($this->nossonumero, "0");
						
						$this->valorboleto = explode("|",$this->posicoes["valorboleto"]);
						$this->valorboleto = substr($value,$this->valorboleto[0],$this->valorboleto[1]).".".substr($value,$this->valorboleto[2],$this->valorboleto[3]);
						$this->valorboleto = ltrim($this->valorboleto, "0");

						if($this->posicoes["vencimento"] != "nao"){
							$this->vencimento = explode("|",$this->posicoes["vencimento"]);
							$this->vencimento = substr($value,$this->vencimento[0],$this->vencimento[1]);
							$this->vencimento = substr($this->vencimento,0,2)."/".substr($this->vencimento,2,2)."/".substr($this->vencimento,4,4);
						}

						$linhadoretorno = $value;
						///FIM SEGMENTO T				 
					}elseif($this->segmento == 'U'){
						///COMEÇO SEGMENTO U
						$this->datacredito = explode("|",$this->posicoes["datacredito"]);
						$this->datacredito = substr($value,$this->datacredito[0],$this->datacredito[1]);
						$this->datacredito = substr($this->datacredito,4,4)."-".substr($this->datacredito,2,2)."-".substr($this->datacredito,0,2);
						
						$this->valorpago = explode("|",$this->posicoes["valorpago"]);
						$this->valorpago = substr($value,$this->valorpago[0],($this->valorpago[1]+2)) /100;
						$this->valorpago = ltrim($this->valorpago, "0");
						$linhadoretorno = $linhadoretorno.'\n'.$value;
						
						$this->sql = "SELECT * FROM contas WHERE idconta = '".intval($this->nossonumero)."' ";
						$conta = $this->retornarLinha($this->sql);
						
						$sindicatosArray = explode(',',$_SESSION["adm_sindicatos"]);
						
						if($conta['idsindicato'] and ($_SESSION["adm_gestor_sindicato"] == 'S' or ($_SESSION["adm_gestor_sindicato"] == 'N' and in_array($conta['idsindicato'],$sindicatosArray)))){

									$resultJuros = $this->retornaJurosAdicional($conta['data_vencimento'], $conta['valor'],$this->datacredito);
									$jurosmulta  = $resultJuros['jurosMulta']; 			
									$juros  	 = $resultJuros['juros'];
									$multa   	 = $resultJuros['multa'];

									$totalNecessario = $conta['valor'] + $jurosmulta;


									if($workflow_pago['idsituacao'] == $conta['idsituacao'])
										$status = 'PA'; //PAGO ANTERIORMENTE
									elseif ($this->valorpago == $totalNecessario and $jurosmulta > 0) 
										$status = 'PJ';//PAGO COM JUROS
									elseif ($this->valorpago < $totalNecessario) 
										$status = 'AV';//PAGO A MENOS DO QUE O NECESSÁRIO
									elseif ($this->valorpago > $conta['valor'] and $jurosmulta == 0) 
										$status = 'PM';//PAGO A MAIS SEM JUROS
									else
										$status = 'P';
								

									//ATUALIZA OS PAGAMENTOS INICIO
									$this->sql = "insert retornos_contas
																  set
																	status = '" .$status. "',
																	idretorno = '" .$this->id. "',
																	idconta = '" .$conta['idconta']. "',
																	linha = '" .mysql_escape_string($linhadoretorno). "',
																	data_ocorrencia = '" .$conta['data_vencimento']. "',
																	data_debito = '" .$this->datacredito. "',
																	mo_nosso_numero = '" .$this->nossonumero. "',
																	valorboleto = '" .$conta['valor']. "',
																	juros = '" .$juros. "',
																	multa = '" .$multa. "',
																	valorpago = '" .$this->valorpago. "'
																	";
									$this->executaSql($this->sql);
									$totalProcessado++;
									
									
									if($this->valorpago == $totalNecessario and $workflow_pago['idsituacao'] != $conta['idsituacao']){
										
										//$juros  $multa

										$c = new Contas();
										$array['idconta'] = $conta['idconta']  ; 
										$array['valor_pago'] = number_format($this->valorpago,2,',','.')  ; 
										$array['data_pagamento'] = $this->datacredito  ; 
										$c->Set('post',$array);
                                        $c->Set('idusuario',$this->idusuario);
										$sucesso = $c->quitar();
										
										if($sucesso['sucesso'] and $conta['idmatricula']){

											$totalFechamento += $this->valorpago;
											$contasFechamento[] = array(
																		'idconta'=>$conta['idconta'],
																		'valor_pago'=>$this->valorpago,
																		'valor'=>$conta['valor'],
																		'idsindicato'=>$conta['idsindicato'],
																		'datacredito'=>$this->datacredito
																		);
											
											if($jurosmulta > 0){

												$this->executaSql("update contas 
																		set valor_juros = '".$juros."',
																			valor_multa = '".$multa."'
																		where idconta = '".$conta['idconta']."'");
											}
																						
											/**
                                             * Ação comentada como pedido no chamado #135778
                                             */
//                                          $this->sql = "select * FROM matriculas_workflow where ativo = 'S' and ativa = 'S' order by idsituacao desc limit 1";
//											$situacaoMatriculado = $this->retornarLinha($this->sql);
//											$this->sql = "update matriculas set idsituacao = " . $situacaoMatriculado["idsituacao"]." where idmatricula = '" .$conta['idmatricula']. "'";
//											$this->executaSql($this->sql);
                                            
//                                          $this->executaSql("insert matriculas_historicos
//																  set
//																	idmatricula = '" .$conta['idmatricula']. "',
//																	data_cad = now(),
//																	tipo = 'situacao',
//																	acao = 'modificou',
//																	de = '" .$linhaAntiga['idsituacao']. "',
//																	para = '" .$linhaNova['idsituacao']. "'  ");
                                            
                                            $this->sql = "SELECT * FROM matriculas where idmatricula = " . intval($conta['idmatricula']);
											$linhaAntiga = $this->retornarLinha($this->sql);
											  
											$this->sql = "SELECT * FROM matriculas where idmatricula = " . intval($conta['idmatricula']);
											$linhaNova = $this->retornarLinha($this->sql);

											$this->sql = "SELECT * FROM matriculas where idmatricula = " . intval($conta['idmatricula']);
											$matricula = $this->retornarLinha($this->sql);

											$this->sql = 'SELECT * FROM sindicatos WHERE idsindicato = '.$linhaNova['idsindicato'].' AND ativo = "S"';
                    						$sindicato = $this->retornarLinha($this->sql);
											if($sindicato['gerente_email']) {
						                        $nomePara = utf8_decode($sindicato["gerente_nome"]);

						                        $message  = "Ol&aacute; <strong>".$nomePara."</strong>,
						                                    <br /><br />
						                                    O aluno realizou o pagamento da matr&iacute;cula #".$matricula['idmatricula'].".
						                                    <br /><br />
						                                    <a href=\"http://".$_SERVER["SERVER_NAME"]."/gestor/academico/matriculas/".$matricula['idmatricula']."/administrar\">Clique aqui</a> para acessar a matr&iacute;cula.
						                                    <br /><br />";

						                        $emailPara = $sindicato["gerente_email"];
						                        $assunto = utf8_decode("Nova matrícula #".$matricula['idmatricula']);

						                        $nomeDe = $GLOBALS["config"]["tituloEmpresa"];
						                        $emailDe = $GLOBALS["config"]["emailSistema"];

						                        $this->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara);
						                    }
										}
									}
									
								$this->retorno['sucesso'] = true;
								// ATUALIZA PAGAMENTOS FIM
						}
						
					///FIM SEGMENTO U
					}
			}
		}
		
		if(count($contasFechamento) > 0){
			$this->sql  = "INSERT INTO fechamentos_caixa SET data_cad = NOW(), ativo = 'S', idusuario = '".$this->idusuario."' ";
			$this->sql .= " , credito_valor = '".$totalFechamento."'	";
			$this->sql .= " , credito_quantidade = '".count($contasFechamento)."'	";
			$this->sql .= " , debito_quantidade = '0'	";
			$this->sql .= " , periodo_tipo_receber = 'PER'";
			$this->sql .= " , periodo_de_receber = '".$contasFechamento[0]['datacredito']."' ";
			$this->sql .= " , periodo_ate_receber = '".$contasFechamento[0]['datacredito']."'	";
			$this->sql .= " , forma_pagamento_receber = '1' ";
	
			$fechamento_resultado = $this->executaSql($this->sql);
			$idfechamento = mysql_insert_id();
			
			foreach($contasFechamento as $ind => $contafecha){
					$this->executaSql("
							UPDATE 
								  contas 
								SET 
								  idfechamento = '".$idfechamento."'
								WHERE idconta = '".$contafecha['idconta']."'
					");
					$this->executaSql("											
								INSERT INTO fechamentos_caixa_sindicatos (idfechamento, ativo, data_cad, idsindicato) 
													  SELECT '".$idfechamento."', 'S', NOW(), '".$contafecha['idsindicato']."' FROM dual
												   WHERE NOT EXISTS (
													  SELECT idfechamento_sindicato 
															  FROM fechamentos_caixa_sindicatos 
													  WHERE  idfechamento = '".$idfechamento."' AND 
															 idsindicato = '".$contafecha['idsindicato']."' and 
															 ativo = 'S'
												  );				
					");
			}
			
		}
		
		$this->sql = "UPDATE retornos SET processado = 'S' ,  quantidade_processado = '".$totalProcessado."' ";
		
		if($idfechamento){
			$this->sql .= " , idfechamento = '".$idfechamento."' ";
		}
		
		$this->sql .= " WHERE idretorno = '".$this->id."'";
		
		$this->executaSql($this->sql);
		
		return $this->retorno;
	}
		
	function Preview() {
		$sindicatosArray = explode(',', $_SESSION['adm_sindicatos']);
		
		$this->sql = "SELECT arquivo_servidor FROM retornos WHERE idretorno = '".$this->id."'";
		$this->query     = $this->executaSql($this->sql)or die(incluirLib("erro",$this->config));
		$this->retornoLinha   = mysql_fetch_assoc($this->query);
		
		$this->validarArquivoBanco($_SERVER["DOCUMENT_ROOT"]."/storage/contas_retornos/".$this->retornoLinha["arquivo_servidor"]);
		
		$file = file($_SERVER["DOCUMENT_ROOT"]."/storage/contas_retornos/".$this->retornoLinha["arquivo_servidor"]);
		
		$sql = "select * from contas_workflow where ativo = 'S' and pago = 'S' LIMIT  1 ";
		$workflow_pago = $this->retornarLinha($sql);

		$total =count($file);
		foreach($file as $line => $value){
			if($line > 0){
					//Atualiza o retorno informando de qual banco é o retorno
					$this->segmento = explode("|",$this->posicoes["segmento"]); 
					$this->segmento = substr($value,$this->segmento[0],$this->segmento[1]);//Verfica o Segmento, T ou U
					
					if($this->segmento == 'T'){
						///COMEÇO SEGMENTO T
						$this->nossonumero = explode("|",$this->posicoes["nossonumero"]);
						$this->nossonumero = substr($value,$this->nossonumero[0],$this->nossonumero[1]);
						//$this->nossonumero = ltrim($this->nossonumero, "0");
						
						$this->valorboleto = explode("|",$this->posicoes["valorboleto"]);
						$this->valorboleto = substr($value,$this->valorboleto[0],$this->valorboleto[1]).".".substr($value,$this->valorboleto[2],$this->valorboleto[3]);
						$this->valorboleto = ltrim($this->valorboleto, "0");
						
						if($this->posicoes["vencimento"] != "nao"){
							$this->vencimento = explode("|",$this->posicoes["vencimento"]);
							$this->vencimento = substr($value,$this->vencimento[0],$this->vencimento[1]);
							$this->vencimento = substr($this->vencimento,0,2)."/".substr($this->vencimento,2,2)."/".substr($this->vencimento,4,4);
						}	
						///FIM SEGMENTO T				 
					}elseif($this->segmento == 'U'){
						///COMEÇO SEGMENTO U
						$this->datacredito = explode("|",$this->posicoes["datacredito"]);
						$this->datacredito = substr($value,$this->datacredito[0],$this->datacredito[1]);
						$this->datacredito = substr($this->datacredito,4,4)."-".substr($this->datacredito,2,2)."-".substr($this->datacredito,0,2);
						
						$this->valorpago = explode("|",$this->posicoes["valorpago"]);
						$this->valorpago = substr($value,$this->valorpago[0],($this->valorpago[1]+2)) /100;
						$this->valorpago = ltrim($this->valorpago, "0");
											
												
						$this->sql = "SELECT * FROM contas WHERE idconta = '".intval($this->nossonumero)."' ";
						$conta = $this->retornarLinha($this->sql);
						

						$array = array();
						
						if($conta['idsindicato'] and ($_SESSION["adm_gestor_sindicato"] == 'S' or ($_SESSION["adm_gestor_sindicato"] == 'N' and in_array($conta['idsindicato'],$sindicatosArray)))){
						
							if($conta['idconta']){
								$this->sql = "SELECT  m.idmatricula ,
													  p.nome ,
													  p.documento
												 FROM matriculas m 
												 INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa) 
										  	   WHERE m.idmatricula = " . intval($conta['idmatricula']);
								$matriculas = $this->retornarLinha($this->sql);
								if($matriculas['idmatricula']){
									$array['pessoa'] = $matriculas['nome'];
									$array['documento'] = $matriculas['documento'];
								}

								if($this->posicoes["vencimento"] == "nao"){
									$this->vencimento = formataData($conta['data_vencimento'],'br',0);
								}

							}

							$this->valorboleto =  $conta['valor'];
							$resultJuros = $this->retornaJurosAdicional($conta['data_vencimento'], $conta['valor'],$this->datacredito);
							$jurosmulta = $resultJuros['jurosMulta'];

							$this->atualizado = $conta['valor'] + $jurosmulta;

							if($workflow_pago['idsituacao'] == $conta['idsituacao'])
								$array['pago']=true;
						
						
							$sindicatosArray = explode(',',$_SESSION["adm_sindicatos"]);
													
							$this->executaSql("INSERT INTO retornos_sindicatos (idretorno, ativo, data_cad, idsindicato) 
													  SELECT '".$this->id."', 'S', NOW(), '".$conta['idsindicato']."' FROM dual
												   WHERE NOT EXISTS (
													  SELECT idretorno_sindicato 
															  FROM retornos_sindicatos 
													  WHERE  idretorno = '".$this->id."' AND 
															 idsindicato = '".$conta['idsindicato']."' and 
															 ativo = 'S'
												  );
							  ");

							
						}
						
						$array['nossonumero']    = $this->nossonumero;
						$array['atualizado']    = $this->atualizado;
						$array['valorboleto']    = $this->valorboleto;
						$array['valorpago']      = $this->valorpago;
						$array['datacredito']    = formataData($this->datacredito,'br',0);
						$array['datavencimento'] = $this->vencimento;
						$retorno[] = $array;
					}
			}
		}
		return $retorno;
	}

	function retornaJurosAdicional($vencimento, $valor,$datapagamento){
    
		$jurosMulta = 0;
		$juros = 0;
		$multa = 0;
		$dias_de_prazo_para_pagamento = 0;
		$taxa_boleto = 0;
		$vencimentoNovo=$vencimento;

		$datadif = $this->DataDif($vencimento,$datapagamento, 'D');//   

		if($datadif > 0){
			$taxa = 0.00033;
			$multa = $valor * 0.02;
			$multa = number_format($multa,2,'.','');
			$juros = ($taxa*$valor)*$datadif;
			$juros = number_format($juros,2,'.','');
			$jurosMulta = $juros + $multa;
		}

		$dados_data = explode("-",$vencimento);
		$diaSemana = date("N", mktime(0, 0, 0, $dados_data[1], $dados_data[2], $dados_data[0]));

		if ($diaSemana == 6 and ($datadif == 2 or $datadif == 1)) {
			$jurosMulta = 0;
			$juros = 0;		
			$multa = 0;
		}elseif($diaSemana == 7 and ($datadif == 1)){
			$jurosMulta = 0;
			$juros = 0;		
			$multa = 0;
		}
		$resultadoJurosMulta['juros']=$juros;
		$resultadoJurosMulta['jurosMulta']=$jurosMulta;
		$resultadoJurosMulta['multa']=$multa;
		return $resultadoJurosMulta;
	}
	
	function DataDif($dtInicio, $dtFim, $type){
	    
		switch($type){
			case 'A' : $X = 31536000; break;//ano
			case 'M' : $X = 2592000; break; //mes
			case 'D' : $X = 86400; break;   //dia
			case 'H' : $X = 3600; break;    //hora
			case 'I' : $X = 60; break;      //minuto
			default  : $X = 1; break;       //segundo
		}
		return round ((strtotime($dtFim) - strtotime($dtInicio)) / $X);
	
	}

	function Modificar() {
		return $this->SalvarDados();	
	}
	
	function Remover() {
		return $this->RemoverDados();	
	}

	function GerarTabelaParcelas($dados,$q = null,$idioma,$configuracao = "listagem")
	{
		
		$totalGeral = 0;
		$totalPago = 0;
		include("idiomas/pt_br/index.php");			
		
		echo '<table class="table  table-striped" id="sortTableExample">';
		echo '<thead>';
		echo '<tr>';
		foreach($this->config[$configuracao] as $ind => $valor){
		
				$tamanho = "";
				if($valor["tamanho"]) $tamanho = ' width="'.$valor["tamanho"].'"';
				
				$th = '<th class="';
				$th.= $class.' headerSortReloca" '.$tamanho.'>';
				echo $th;
				echo "<div class='headerNew' >".$idioma[$valor["variavel_lang"]]."</div>";
				
				echo '</th>';
				
		}
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		echo '<tr>';
					
		if(count($dados) == 0){
			echo '<tr>';
			echo '<td colspan="'.count($this->config[$configuracao]).'">Nenhum informação foi encontrada.</td>';
			echo '</tr>';
		} else {
			foreach($dados as $i => $linha){
				echo '<tr>';
				foreach($this->config[$configuracao] as $ind => $valor){
					
					if($valor["tipo"] == "banco") {
						
						echo '<td>'.stripslashes($linha[$valor["valor"]]).'&nbsp;</td>';
						
					} elseif($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
						$valor = $valor["valor"]." ?>";
						$valor = eval($valor);							
						echo '<td>'.stripslashes($valor).'</td>';
					} elseif($valor["tipo"] == "array") {
						$variavel = $GLOBALS[$valor["array"]];
						echo '<td>'.$variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]].'</td>';
					} elseif($valor["busca_tipo"] != "hidden") {
						echo '<td>'.stripslashes($valor["valor"]).'</td>';
					}
				}
				echo '</tr>';
				$totalGeral = $totalGeral + $linha['atualizado'];
				if($linha['atualizado'] > 0) $totalPago = $totalPago + $linha['valorpago'];
				
			}

		}
		echo '<tr>';
		echo '<td colspan="5" style="text-align:right;"><strong> Total Geral:</strong></td>';
		echo '<td colspan="1">R$ '.number_format($totalGeral,2,',','.').'</td>';
		echo '<td colspan="1" style="text-align:right;"><strong> Total Pago:</strong></td>';
		echo '<td colspan="2">R$ '.number_format($totalPago,2,',','.').'</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
	}

}
?>