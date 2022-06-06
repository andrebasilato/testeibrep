<?php 
class Pagamentos_Compartilhados extends Core {

    var $tipo_conta = null;
		
    function ListarTodas() {		
		$this->sql = "select ".$this->campos." from pagamentos_compartilhados pc					
						where pc.ativo = 'S'";
			
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
			
		$this->groupby = "pc.idpagamento";
		return $this->retornarLinhas();
    }
	
	
    function Retornar() {
		$this->sql = "select ".$this->campos." 
						from pagamentos_compartilhados pc
						inner join sindicatos i on pc.idsindicato = i.idsindicato
						where pc.ativo = 'S' and pc.idpagamento = '".$this->id."'";			
		return $this->retornarLinha($this->sql);
    }
	
    function Cadastrar() {

		if(count($this->post['matriculas']) < 2) {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'pagamento_sem_matriculas';
			return $this->retorno;
		} else if(!$this->post['idsindicato']) {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'pagamento_sem_sindicato';
			return $this->retorno;
		}
		
		$valor = str_replace(',','.',str_replace('.','',$_POST['valor']));
		
		foreach($this->post['matriculas_array'] as $id => $linha) {
			$this->post['matriculas_array'][$id]['valor'] = str_replace(',','.',str_replace('.','',$this->post['matriculas_array'][$id]['valor']));
			$valor_total_post += $this->post['matriculas_array'][$id]['valor'];
		}
		
		if($valor != $valor_total_post) {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'valor_total_diferente';
			return $this->retorno;
		}

		$sql = "select * from contas_workflow where ativo = 'S' and emaberto = 'S' ";
		$workflow_aberto = $this->retornarLinha($sql);	

		$sql = "select * from eventos_financeiros where ativo = 'S' and ativo_painel = 'S' and mensalidade = 'S' ";
		$evento_mensalidade = $this->retornarLinha($sql);			
		
		$total = $this->post['parcelas'];
		mysql_query("START TRANSACTION");
		
		$sql_relacao = "insert into pagamentos_compartilhados set data_cad = NOW(), valor = '".$valor."', nome = '".date("d/m/Y").' ('.implode(',',$_POST['matriculas']).")', idsindicato = '".$this->post['idsindicato']."' ";
		$this->executaSql($sql_relacao);
		$id_pagamento = mysql_insert_id();
		
		if(!$id_pagamento) {
			mysql_query("ROLLBACK");
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'erro_inserir_pagamento';
			return $this->retorno;
		} else {
			$this->monitora_oque = 1;
			$this->monitora_onde = "21";
			$this->monitora_qual = $id_pagamento;
			$this->Monitora();			
		}
		
		if($this->post['matriculas']) {
			foreach($this->post['matriculas'] as $mat) {
				$sql_matricula = " insert into pagamentos_compartilhados_matriculas set idpagamento = '".$id_pagamento."',
																			  idmatricula = '".$mat."',
																			  data_cad = NOW(),
																			  valor = '".$this->post['matriculas_array'][$mat]['valor']."'	";
				$insere_matricula = $this->executaSql($sql_matricula);
				$id_pagamento_matricula = mysql_insert_id();
				if(!$insere_matricula) {
					mysql_query("ROLLBACK");
					$this->retorno["erro"] = true;
					$this->retorno["erros"][] = 'erro_inserir_matricula';
					return $this->retorno;
				} else {
					$this->monitora_oque = 1;
					$this->monitora_onde = "53";
					$this->monitora_qual = $id_pagamento_matricula;
					$this->Monitora();
				}
			}
		}
		
		$this->monitora_oque = 1;
		$this->monitora_onde = "21";
		$this->monitora_qual = $id_pagamento;
		$this->Monitora();		
		
		//CADASTROS DE CONTAS
		$erro = array();
		if(!$this->post["forma_pagamento_post"]) {
		  $erro[] = "financeiro_forma_pagamento_vazio";
		} elseif($this->post["forma_pagamento_post"] == 2 || $this->post["forma_pagamento_post"] == 3) {
		  if(!$this->post["idbandeira"]) {
			$erro[] = "bandeira_cartao_vazio";
		  }
		  if(!$this->post["autorizacao_cartao"]) {
			$erro[] = "autorizacao_cartao_vazio";
		  }
		} elseif($this->post["forma_pagamento_post"] == 4) {
		  if(!$this->post["idbanco"]) {
			$erro[] = "banco_cheque_vazio";
		  }
		  if(!$this->post["agencia_cheque"]) {
			$erro[] = "agencia_cheque_vazio";
		  }
		  if(!$this->post["cc_cheque"]) {
			$erro[] = "cc_cheque_vazio";
		  }
		  if(!$this->post["numero_cheque"]) {
			$erro[] = "numero_cheque_vazio";
		  }
		  if(!$this->post["emitente_cheque"]) {
			$erro[] = "emitente_cheque_vazio";
		  }
		  $numero_cheque = intval($this->post['numero_cheque']);
		}
		if(!$this->post["quantidade_parcelas"]) {
		  $erro[] = "financeiro_quantidade_parcelas_vazio";
		}
		if(!$this->post["valor"]) {
		  $erro[] = "financeiro_valor_vazio";
		} else {
		  $this->post["valor"] = floatval(str_replace(',','.',str_replace('.','',$this->post['valor'])));
		}
		if(!$this->post["vencimento"]) {
		  $erro[] = "financeiro_vencimento_vazio";
		}
		if(count($erro) <= 0) {
		  
		  if(!intval($this->post['quantidade_parcelas']) || $this->post["forma_pagamento_post"] == 3 || $this->post["forma_pagamento_post"] == 5) {
			$this->post['quantidade_parcelas'] = 1;
		  }
		
		  $valorParcela = round($this->post["valor"] / $this->post['quantidade_parcelas'], 2);
		  $valorPrimeiraParcela = $valorParcela;
		  $valorTotal = $valorParcela * $this->post['quantidade_parcelas'];
		  if($valorTotal <= $this->post["valor"]) {
			$valorPrimeiraParcela += ($this->post["valor"] - $valorTotal);
		  } elseif($valorTotal >= $this->post["valor"]) {
			$valorPrimeiraParcela += ($valorTotal - $this->post["valor"]);
		  }
		  $data = explode("/", $this->post["vencimento"]);
		  
			if(count($this->post['parcelas_array'])) {
				$total_parcelas = count($this->post['parcelas_array']);
				foreach($this->post['parcelas_array'] as $ind => $parcela) {					
				
					$valor = str_replace(',','.',str_replace('.','',$parcela['valor']));
				
					$this->sql = "INSERT INTO contas SET
							data_cad = NOW(),
							tipo = 'receita',
							nome = '".$parcela['nome']."',
							valor = '".$valor."',
							data_vencimento = '".formataData($parcela['vencimento'],'en',0)."',
							idsituacao = '".$workflow_aberto['idsituacao']."',
							idpagamento_compartilhado = '".$id_pagamento."',
							idsindicato = '".$this->post["idsindicato"]."',
							idevento = ".$evento_mensalidade['idevento'].",
							parcela = '".$ind."',
							idmatricula = '".$this->post['matriculas'][0]."',
							total_parcelas = '".$total_parcelas."' ";
						
						if($this->post["forma_pagamento_post"] == 2 || $this->post["forma_pagamento_post"] == 3) {
						  $this->sql .= ", forma_pagamento = ".$this->post['forma_pagamento_post'].",
										idbandeira = ".$this->post['idbandeira'].",
										autorizacao_cartao = '".$this->post['autorizacao_cartao']."'";
						} elseif($this->post["forma_pagamento_post"] == 4) {
						  $this->sql .= ", forma_pagamento = ".$this->post['forma_pagamento_post'].",
										idbanco = ".$this->post['idbanco'].",
										agencia_cheque = '".$this->post['agencia_cheque']."',
										cc_cheque = '".$this->post['cc_cheque']."',
										numero_cheque = '".str_pad($numero_cheque,6,'0',STR_PAD_LEFT)."',
										emitente_cheque = '".$this->post['emitente_cheque']."'";
						  $numero_cheque++;
						} else {
						  $this->sql .= ", forma_pagamento = ".$this->post['forma_pagamento_post'];
						}
									
					if($this->executaSql($this->sql)){
						$id = mysql_insert_id();			  
						$this->AdicionarHistoricoContas("situacao", "modificou", NULL, $workflow_aberto["idsituacao"], $id);	  
					  
						$this->monitora_oque = 1;
						$this->monitora_onde = "52";
						$this->monitora_qual = $id;
						$this->Monitora();							
					} else {
						mysql_query("ROLLBACK");
						$this->retorno["erro"] = true;
						$this->retorno["erros"][] = 'erro_inserir_parcela';
						return $this->retorno;
					}
					
				}
				
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = 'erro_sem_parcela';
				return $this->retorno;
			}
		
		} else {
		  $this->retorno["erro"] = true;
		  $this->retorno["erros"][] = 'mensagem_financeiro_campos_obrigatorios';
		  mysql_query("ROLLBACK");
		  return $this->retorno;
		}
		
		mysql_query("COMMIT");
		$this->retorno["sucesso"] = true;
		return $this->retorno;	
	
    }
	
    function Remover() {

        if(!$this->post['remover']) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'remover_vazio';
            return $this->retorno;
        }
		$sql = "select * from pagamentos_compartilhados where idpagamento = ".$this->post['remover']." ";
		$pagamento = $this->retornarLinha($sql);
		if($pagamento['ativo_painel'] != 'S') {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'pagamento_cancelado';
			return $this->retorno;
		}
	
		mysql_query("START TRANSACTION");
		$sql = "select idsituacao from contas_workflow where ativo = 'S' and cancelada = 'S' ";
		$workflow_cancelado = $this->retornarLinha($sql);	
	
		$sql = "update pagamentos_compartilhados set ativo_painel = 'N' where idpagamento = ".$this->post['remover']." ";
		$pagamento_cancelado = $this->executaSql($sql);
		if($pagamento_cancelado) {
			$this->sql = "select idconta, idsituacao from contas where idpagamento_compartilhado = ".$this->post['remover']." ";
			$this->limite = -1;
			$this->ordem_campo = "idconta";
			$contas = $this->retornarLinhas();
			if(!$contas) {
				mysql_query("ROLLBACK");
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = 'erro_selecionar_contas';
				return $this->retorno;
			}
			
			foreach($contas as $linha) {
				$sql = "update contas set idsituacao = '".$workflow_cancelado['idsituacao']."' where idpagamento_compartilhado = ".$this->post['remover']." ";
				$contas_canceladas = $this->executaSql($sql);
				if(!$contas_canceladas) {
					mysql_query("ROLLBACK");
					$this->retorno["erro"] = true;
					$this->retorno["erros"][] = 'erro_cancelar_contas';
					return $this->retorno;
				} 
				$this->AdicionarHistoricoContas("situacao", "modificou", $linha["idsituacao"], $workflow_cancelado["idsituacao"], $linha['idconta']);	
			}			
		} else {
			mysql_query("ROLLBACK");
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'erro_cancelar_pagamento';
			return $this->retorno;
		}
		
		mysql_query("COMMIT");
		$this->monitora_oque = 3;
		$this->monitora_onde = "21";
		$this->monitora_qual = $this->post['remover'];
		$this->Monitora();
		$this->retorno["sucesso"] = true;
		return $this->retorno;
    }
  
    function AdicionarHistoricoContas($tipo, $acao, $de, $para, $id) {

		$this->sql = "insert 
						contas_historicos 
					  set
						idconta = '".$id."',
						data_cad = now(), 
						tipo = '".$tipo."', 
						acao = '".$acao."'";
		if($this->modulo == "gestor") $this->sql .= ", idusuario = '".$this->idusuario."'";
		if($de) $this->sql .= ", de = '".$de."'"; else  $de = uniqid();
		if($para) $this->sql .= ", para = '".$para."'"; else  $para = uniqid();

		if($de != $para)
		  return $this->executaSql($this->sql);
		else 	
		  return true;
    }
	
	function retornarSituacaoPago() {
		$this->sql = "SELECT 
					idsituacao
				  FROM  
					 contas_workflow
				 WHERE  
					 pago =  'S' and ativo = 'S' ORDER BY idsituacao DESC limit 1 ";
		$dados = $this->retornarLinha($this->sql);
		return $dados['idsituacao'];	
	}
	
	function RetornarMatriculas() {
		$this->sql = "select m.idmatricula as 'key', CONCAT(m.idmatricula,': ',p.nome,' - ',p.documento) as value 
						from matriculas m
							inner join pessoas p on (p.idpessoa=m.idpessoa)
						where (m.idmatricula like '%".$_GET["tag"]."%' OR p.nome like '%".$_GET["tag"]."%' OR p.documento like '%".$_GET["tag"]."%') ";
		$this->limite = -1;
		$this->ordem_campo = "m.idmatricula";
		$this->groupby = "m.idmatricula";
		$dados = $this->retornarLinhas();
						
		return json_encode($dados);	  
    }

	function RetornarMatriculasEscolhidas($matriculas) {
		if($matriculas) {
			$this->sql = "select m.idmatricula, p.nome, p.documento 
							from matriculas m
								inner join pessoas p on (p.idpessoa=m.idpessoa)
							where m.idmatricula in (".implode(',',$matriculas).") ";
			$this->limite = -1;
			$this->ordem_campo = "m.idmatricula";
			$this->groupby = "m.idmatricula";
							
			$matriculas_array = $this->retornarLinhas();
		}
		return $matriculas_array;
    }
	
	function ListarMatriculasAssociadas($idpagamento) {
		$this->sql = "select m.idmatricula, p.nome, p.documento, pcm.valor, pcm.idpagamento_matricula
						from pagamentos_compartilhados_matriculas pcm
							inner join matriculas m on m.idmatricula = pcm.idmatricula
							inner join pessoas p on p.idpessoa = m.idpessoa
						where pcm.idpagamento = '".$idpagamento."' and pcm.ativo = 'S'	";
						
		$this->limite = -1;
		$this->ordem_campo = "m.idmatricula";
		$this->groupby = "m.idmatricula";
						
		return $this->retornarLinhas();	
		
	}

  function BuscarMatricula() {
	$this->sql = "select m.idmatricula as 'key', CONCAT(m.idmatricula,': ',p.nome,' - ',p.documento) as value 
						from matriculas m
							inner join pessoas p on (p.idpessoa=m.idpessoa)
							inner join escolas pol on m.idescola = pol.idescola and pol.ativo = 'S' and pol.ativo_painel = 'S'
						where (m.idmatricula like '%".$_GET["tag"]."%' OR p.nome like '%".$_GET["tag"]."%' OR p.documento like '%".$_GET["tag"]."%')
						and pol.idsindicato = ".$this->url[6]."
						and NOT EXISTS (SELECT pcm.idmatricula FROM pagamentos_compartilhados_matriculas pcm WHERE pcm.idmatricula = m.idmatricula AND pcm.idpagamento = '".$this->id."' AND pcm.ativo = 'S')";
  
	$this->limite = -1;
	$this->ordem_campo = "value";
	$this->groupby = "value";
	
	$dados = $this->retornarLinhas();						
	return json_encode($dados);	  
  }
	
  function AssociarMatriculas($idpagamento, $arrayMatriculas) {
  
    $sql = "select * from pagamentos_compartilhados where idpagamento = '".$idpagamento."' ";
	$pagamento = $this->retornarLinha($sql);
	if($pagamento['ativo_painel'] != 'S') {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = 'pagamento_cancelado';
		return $this->retorno;
	}
  
    mysql_query("START TRANSACTION");
	foreach($arrayMatriculas as $ind => $id) {
			  
	  $this->sql = "select count(idpagamento_matricula) as total, idpagamento_matricula from pagamentos_compartilhados_matriculas where idpagamento = '".intval($idpagamento)."' and idmatricula = '".intval($id)."'";
	  $totalAss = $this->retornarLinha($this->sql); 
	  if($totalAss["total"] > 0) {
		$this->sql = "update pagamentos_compartilhados_matriculas set ativo = 'S' where idpagamento_matricula = ".$totalAss["idpagamento_matricula"];
		$associar = $this->executaSql($this->sql);
		if(!$associar) {
			mysql_query("ROLLBACK");
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'erro_associar_matricula';
			return $this->retorno;
		}
		$this->monitora_qual = $totalAss["idpagamento_matricula"];					
	  } else {
		$this->sql = "insert into pagamentos_compartilhados_matriculas set ativo = 'S', data_cad = now(), idpagamento = '".intval($idpagamento)."', idmatricula = '".intval($id)."'";
		$associar = $this->executaSql($this->sql);
		if(!$associar) {
			mysql_query("ROLLBACK");
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'erro_associar_matricula';
			return $this->retorno;
		}
		$this->monitora_qual = mysql_insert_id();
	  }
						  
	  if($associar){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 1;
		$this->monitora_onde = 53;
		$this->Monitora();
	  } else {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
	  }			
	}
	
	//ATUALIZAR O NOME DO PAGAMENTO COMPARTILHADO - INÍCIO
	$sql_retorno = "select idmatricula from pagamentos_compartilhados_matriculas where idpagamento = ".$idpagamento." and ativo = 'S' ";
	$execulta = $this->executaSql($sql_retorno);
	if(!$execulta) {
		mysql_query("ROLLBACK");
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = 'erro_associar_matricula';
		return $this->retorno;
	}
	  
	while($linha = mysql_fetch_assoc($execulta)) {
		$matriculas[] = $linha['idmatricula'];
	}
	$nome_compartilhamento = ''.implode(',',$matriculas);
	$array_nome = explode('(', $pagamento['nome']);
	$novo_nome = $array_nome[0].'('.$nome_compartilhamento.')';

	$sql_atualiza = "update pagamentos_compartilhados set nome = '".mysql_real_escape_string($novo_nome)."' where idpagamento = ".$idpagamento." ";
	$atualiza = $this->executaSql($sql_atualiza);
	if(!$atualiza) {
		mysql_query("ROLLBACK");
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = 'erro_associar_matricula';
		return $this->retorno;
	}
	//ATUALIZAR O NOME DO PAGAMENTO COMPARTILHADO - FIM
	
	mysql_query("COMMIT");
	return $this->retorno;
  }	
	
  function RemoverMatriculas($idpagamento) {

	$sql = "select * from pagamentos_compartilhados where idpagamento = '".$idpagamento."' ";
	$pagamento = $this->retornarLinha($sql);
	if($pagamento['ativo_painel'] != 'S') {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = 'pagamento_cancelado';
		return $this->retorno;
	}
  
	include_once("../includes/validation.php");		
	$regras = array(); // stores the validation rules
	
	//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
	if(!$this->post["remover"])
		$regras[] = "required,remover,remover_vazio";
	
	//VALIDANDO FORMULARIO
	$erros = validateFields($this->post, $regras);

	//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
	if(!empty($erros)) {
		$this->retorno["erro"] = true;
		$this->retorno["erros"] = $erros;
	} else {
	  $this->sql = "update pagamentos_compartilhados_matriculas set ativo = 'N' where idpagamento_matricula = ".intval($this->post["remover"]);
	  $desassociar = $this->executaSql($this->sql);

	  if($desassociar){
	  
	    //ATUALIZAR O NOME DO PAGAMENTO COMPARTILHADO - INÍCIO
		$sql_retorno = "select idmatricula from pagamentos_compartilhados_matriculas where idpagamento = ".$idpagamento." and ativo = 'S' ";
		$execulta = $this->executaSql($sql_retorno);
		if(!$execulta) {
			mysql_query("ROLLBACK");
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'erro_desassociar_matricula';
			return $this->retorno;
		}
		  
		while($linha = mysql_fetch_assoc($execulta)) {
			$matriculas[] = $linha['idmatricula'];
		}
		$nome_compartilhamento = ''.implode(',',$matriculas);
		$array_nome = explode('(', $pagamento['nome']);
		$novo_nome = $array_nome[0].'('.$nome_compartilhamento.')';

		$sql_atualiza = "update pagamentos_compartilhados set nome = '".mysql_real_escape_string($novo_nome)."' where idpagamento = ".$idpagamento." ";
		$atualiza = $this->executaSql($sql_atualiza);
		if(!$atualiza) {
			mysql_query("ROLLBACK");
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'erro_desassociar_matricula';
			return $this->retorno;
		}
		//ATUALIZAR O NOME DO PAGAMENTO COMPARTILHADO - FIM
	  
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 53;
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
  
  function SalvarValoresMatriculas($idpagamento) {
  
	$sql = "select * from pagamentos_compartilhados where idpagamento = '".$idpagamento."' ";
	$pagamento = $this->retornarLinha($sql);
	if($pagamento['ativo_painel'] != 'S') {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = 'pagamento_cancelado';
		return $this->retorno;
	}
  
    $sql = "select sum(valor) as total from pagamentos_compartilhados_matriculas where idpagamento = '".$idpagamento."' ";
	$valor_total = $this->retornarLinha($sql);
	
	if(!$valor_total['total']) {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = 'valor_total_vazio';
		return $this->retorno;
	}
	
	foreach($this->post['matriculas_array'] as $id => $linha) {
		$this->post['matriculas_array'][$id]['valor'] = str_replace(',','.',str_replace('.','',$this->post['matriculas_array'][$id]['valor']));
		$valor_total_post += $this->post['matriculas_array'][$id]['valor'];
	}
	
	if($valor_total['total'] != $valor_total_post) {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = 'valor_total_diferente';
		return $this->retorno;
	}
	
	foreach($this->post['matriculas_array'] as $id => $linha) {
		$sql = "select idpagamento_matricula, valor from pagamentos_compartilhados_matriculas where idpagamento_matricula = '".$id."' ";
		$pag_matricula = $this->retornarLinha($sql);

		if($pag_matricula['valor'] != $linha['valor']) {
			$sql = "update pagamentos_compartilhados_matriculas set valor = '".$linha['valor']."' where idpagamento_matricula = '".$id."'  ";
			$atualizar_valor = $this->executaSql($sql);
			if(!$atualizar_valor) {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = 'erro_atualizar_valor';
				return $this->retorno;
			} else {
				$atualizacoes_feitas = true;
				$this->monitora_oque = 2;
				$this->monitora_onde = 53;
				$this->monitora_qual = $id;
				$this->Monitora();
			}
		}
	}
	
	if($atualizacoes_feitas)
		$this->retorno["sucesso"] = true;	
	return $this->retorno;
  }
  
  function RetornarContas($idpagamento) {
		$this->sql = "select c.*, cw.nome as situacao, cw.cor_bg as situacao_cor_bg, cw.cor_nome as situacao_cor_nome 
						from contas c 
						inner join contas_workflow cw on c.idsituacao = cw.idsituacao
						where c.idpagamento_compartilhado = '".$idpagamento."' and c.ativo = 'S' ";
		$this->limite = -1;
		$this->ordem_campo = "c.idconta";
		$this->groupby = "c.idconta";						
		return $this->retornarLinhas();	  
  }
  
  function SalvarDadosContas($idpagamento) {
  
	$sql = "select * from pagamentos_compartilhados where idpagamento = '".$idpagamento."' ";
	$pagamento = $this->retornarLinha($sql);
	if($pagamento['ativo_painel'] != 'S') {
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = 'pagamento_cancelado';
		return $this->retorno;
	}
	
	mysql_query("START TRANSACTION");
	foreach($this->post['contas_array'] as $id => $linha) {
		$sql = "select c.idconta, c.nome, c.data_vencimento, cw.pago, cw.renegociada, cw.cancelada				
					from contas c 
					inner join contas_workflow cw on c.idsituacao = cw.idsituacao
					where c.idconta = '".$id."' ";
		$conta = $this->retornarLinha($sql);
		$vencimento_banco = formataData($linha['vencimento'],'en',0);
		if($conta['nome'] != $linha['nome'] || $conta['data_vencimento'] != $vencimento_banco) {
		
			if($conta['pago'] == 'S' || $conta['renegociada'] == 'S' || $conta['cancelada'] == 'S') {
				mysql_query("ROLLBACK");
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = 'erro_pagamento_finalizado';
				return $this->retorno;
			}
		
			$sql = "update contas set ";
			if($conta['nome'] != $linha['nome']) {
				$sql .= " nome = '".$linha['nome']."'";
				$alterou_nome = true;
			}
			if($conta['data_vencimento'] != $vencimento_banco) {
				if($alterou_nome)
					$sql .= ",";
				$sql .= " data_vencimento = '".$vencimento_banco."'";
				$alterou_vencimento = true;
			}
			
			$sql .= " where idconta = '".$id."'";
			$atualizar = $this->executaSql($sql);
			if(!$atualizar) {
				mysql_query("ROLLBACK");
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = 'erro_atualizar_dados';
				return $this->retorno;
			} else {
				if($alterou_vencimento)
					$this->AdicionarHistoricoContas("data_vencimento", "modificou", $conta['data_vencimento'], $vencimento_banco, $id);
			
				$atualizacoes_feitas = true;
				$this->monitora_oque = 2;
				$this->monitora_onde = 53;
				$this->monitora_qual = $id;
				$this->Monitora();
			}
		}
		unset($alterou_nome);
		unset($alterou_vencimento);
	}
	
	if($atualizacoes_feitas) {
		$this->retorno["sucesso"] = true;
		mysql_query("COMMIT");
    }		
	return $this->retorno;
  }
	
}

?>