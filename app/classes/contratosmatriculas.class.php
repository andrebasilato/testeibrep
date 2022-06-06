<?php
class Contratos_Matriculas extends Core {
	var $idgestor = NULL;
	var $situacoesFiltro = array();

	function ListarTodas() {

		$idSituacaoCancelada = $this->retornarIdSituacaoCancelada();
		$idSituacaoFim = $this->retornarIdSituacaoFim();
		$idSituacaoInativa = $this->retornarIdSituacaoInativa();

		$this->sql = "SELECT
						".$this->campos."
					  FROM
						matriculas ma
						INNER JOIN matriculas_workflow mw ON (mw.idsituacao = ma.idsituacao)
						INNER JOIN sindicatos i ON (ma.idsindicato = i.idsindicato)
						INNER JOIN escolas po ON (po.idescola = ma.idescola)
						INNER JOIN cursos cu ON (cu.idcurso = ma.idcurso)
						INNER JOIN pessoas pe ON (pe.idpessoa = ma.idpessoa)
                        INNER JOIN vendedores ve ON (ve.idvendedor = ma.idvendedor)
						INNER JOIN matriculas_contratos mc ON (mc.idmatricula = ma.idmatricula and mc.ativo = 'S')
						LEFT OUTER JOIN contratos co ON (mc.idcontrato = co.idcontrato)";

		$this->sql .= " WHERE ma.ativo = 'S'"; // and md.arquivo_servidor is not null

		if ($_SESSION["adm_gestor_sindicato"] <> "S")
            $this->sql .= " and ma.idsindicato in (" . $_SESSION["adm_sindicatos"] . ") ";

		if($idSituacaoCancelada["idsituacao"])
		  $this->sql .= " AND ma.idsituacao <> ".$idSituacaoCancelada["idsituacao"];

		if($idSituacaoFim["idsituacao"])
		  $this->sql .= " AND ma.idsituacao <> ".$idSituacaoFim["idsituacao"];

		if($idSituacaoInativa["idsituacao"])
		  $this->sql .= " AND ma.idsituacao <> ".$idSituacaoInativa["idsituacao"];

		if($this->situacoesFiltro)
		  $this->sql .= " AND ma.idsituacao NOT IN(".implode(',',$this->situacoesFiltro).")";

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
						$this->sql .= " and (mc.arquivoç like '%".$valor."%' OR co.nome like '%".$valor."%') ";
					} elseif($campo[0] == 4)  {
						if($valor == 'S')
							$this->sql .= " and ".$campo[1]." IS NOT NULL ";
						elseif($valor == 'N')
							$this->sql .= " and ".$campo[1]." IS NULL ";
					}
				}
			}
		}
		//echo $this->sql;exit;

		$this->groupby = "ma.idmatricula";
		$matriculas = array();
		$matriculas = $this->retornarLinhas();
		//print_r2($matriculas,true);
		$retorno = array();
		$this->retorno = array();
		$limite = $this->limite;
		$total = $this->total;
		$ordem = $this->ordem;
		$ordem_campo = $this->ordem_campo;
		foreach($matriculas as $ind => $matricula) {
		    $this->sql = "SELECT
							mwa.idacao, mwa.idopcao
						FROM
							matriculas_workflow_acoes mwa
						WHERE
							mwa.idsituacao = '{$matricula["idsituacao"]}'
						AND
							mwa.ativo = 'S'";

			$this->ordem = "asc";
			$this->ordem_campo = "mwa.idacao";
			$this->limite = -1;
			$acoes = $this->retornarLinhas();
			foreach($acoes as $acao) {
			  foreach($GLOBALS["workflow_parametros_matriculas"] as $opcao) {
				if($opcao["idopcao"] == $acao["idopcao"] && $opcao["tipo"] == "visualizacao") {
				  $matricula["situacao"]["visualizacoes"][$acao["idopcao"]] = $acao;
				}
			  }
			}

		  $retorno[] = $matricula;
		}
		$this->limite = $limite;
		$this->total = $total;
		$this->ordem = $ordem;
		$this->ordem_campo = $ordem_campo;
		//print_r2($retorno,true);
		return $retorno;
	}

	function retornarIdSituacaoFim() {
	  $sql = "SELECT * FROM matriculas_workflow WHERE fim = 'S' AND ativo = 'S' ORDER BY idsituacao DESC LIMIT 1";
	  $linha = $this->retornarLinha($sql);
	  return $linha;
	}

	function retornarIdSituacaoCancelada() {
	  $sql = "SELECT * FROM matriculas_workflow WHERE cancelada = 'S' AND ativo = 'S' ORDER BY idsituacao DESC LIMIT 1";
	  $linha = $this->retornarLinha($sql);
	  return $linha;
	}

	function retornarIdSituacaoInativa() {
	  $sql = "SELECT * FROM matriculas_workflow WHERE inativa = 'S' AND ativo = 'S' ORDER BY idsituacao DESC LIMIT 1";
	  $linha = $this->retornarLinha($sql);
	  return $linha;
	}

	function validarContrato($idmatricula, $idmatricula_contrato, $situacao) {
		$this->retorno = array();
        if ($situacao == 2) {
            $validado = "now()";
            $nao_validado = "null";
        } else if ($situacao == 1) {
            $validado = "null";
            $nao_validado = "now()";
        }
        $this->sql = "update matriculas_contratos set nao_validado = " . $nao_validado . ", validado = " . $validado;
        if ($this->modulo == "gestor") {
            $this->sql .= ", idusuario_validou = '" . $this->idusuario . "'";
        }
        echo $this->sql .= " where idmatricula_contrato = '" . $idmatricula_contrato . "' and idmatricula = '" . $idmatricula . "'";
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            if ($situacao == 2)
                $acao = "validou";
            elseif ($situacao == 1)
                $acao = "desvalidou";
            $this->AdicionarHistorico($idmatricula, "contrato", $acao, NULL, NULL, $idmatricula_contrato);
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "contratos_matricula_validado_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_matricula_validado_erro";
        }
        return $this->retorno;

	}

	function cancelarContrato($idmatricula, $situacao, $justificativa, $idmatricula_contrato) {
		$this->retorno = array();
        if ($situacao == 2) {
            $cancelado = "now()";
        } else {
            $cancelado = "NULL";
        }
        $this->sql = "update matriculas_contratos set cancelado = " . $cancelado . ", justificativa = '" . $justificativa . "'";
        if ($this->modulo == "gestor") {
            $this->sql .= ", idusuario_cancelou = '" . $this->idusuario . "'";
        }
        $this->sql .= " where idmatricula_contrato = '" . $idmatricula_contrato . "' and idmatricula = '" . $idmatricula . "'";
        $salvar = $this->executaSql($this->sql);
        if ($salvar) {
            if ($situacao == 2)
                $acao = "cancelou";
            elseif ($situacao == 1)
                $acao = "descancelou";
            $this->AdicionarHistorico($idmatricula, "contrato", $acao, NULL, NULL, $idmatricula_contrato);
            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "contratos_matricula_cancelado_sucesso";
        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "contratos_matricula_cancelado_erro";
        }
        return $this->retorno;
	}

	function AdicionarHistorico($idmatricula, $tipo, $acao, $de, $para, $id) {
	  $this->sql = "INSERT
					  matriculas_historicos
					SET
					  idmatricula = '".$idmatricula."',
					  data_cad = now(),
					  idusuario = '".$this->idusuario."',
					  tipo = '".$tipo."',
					  acao = '".$acao."'";

	  if($de) {
	  	$this->sql .= ", de = '".$de."'";
	  }

	  if($para) {
	  	$this->sql .= ", para = '".$para."'";
	  }

	  if($id) {
	  	$this->sql .= ", id = '".$id."'";
	  }

		if(($de || $para) && ($de == $para)) {
            return true;
        } else {
            return $this->executaSql($this->sql);
        }
	}

	function visualizacoesWorkflow($idmatricula) {
		$this->sql = "SELECT idsituacao FROM matriculas WHERE idmatricula = ".$idmatricula;
		$matricula = $this->retornarLinha($this->sql);

		$this->sql = "SELECT
						mwa.idacao, mwa.idopcao
					FROM
						matriculas_workflow_acoes mwa
					WHERE
						mwa.idsituacao = '{$matricula["idsituacao"]}'
					AND
						mwa.ativo = 'S'";

		$this->ordem = "asc";
		$this->ordem_campo = "mwa.idacao";
		$this->limite = -1;
		$acoes = $this->retornarLinhas();
		foreach($acoes as $acao) {
			foreach($GLOBALS["workflow_parametros_matriculas"] as $opcao) {
				if($opcao["idopcao"] == $acao["idopcao"] && $opcao["tipo"] == "visualizacao") {
					$matricula["situacao"]["visualizacoes"][$acao["idopcao"]] = $acao;
				}
			}
		}

		return $matricula;
	}

	/*public function existeDevedorSolidario($idmatricula) {
        $this->sql = "SELECT
                        COUNT(*) AS total
                    FROM
                        matriculas_associados ma
                        INNER JOIN tipos_associacoes ta ON (ma.idtipo = ta.idtipo AND ta.ativo = 'S' AND ta.devedor_solidario = 'S')
                    WHERE
                        ma.idmatricula = " . $idmatricula . " AND
                        ma.ativo = 'S'";
        $total = $this->retornarLinha($this->sql);

        if($total['total']) {
            return true;
        } else {
            return false;
        }
    }*/
}