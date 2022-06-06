<?php
class Matriculas_Aprovacao extends Core
{
	
	function ListarTodas() {		
		$this->sql = "SELECT 
						".$this->campos." 
					  FROM
						matriculas ma 
						INNER JOIN matriculas_workflow mw ON (mw.idsituacao = ma.idsituacao)
						INNER JOIN ofertas of ON (of.idoferta = ma.idoferta)
						INNER JOIN cursos cu ON (cu.idcurso = ma.idcurso)
						INNER JOIN escolas po ON (po.idescola = ma.idescola)
						INNER JOIN pessoas pe ON (pe.idpessoa = ma.idpessoa) 
						INNER JOIN sindicatos i ON (ma.idsindicato = i.idsindicato)
					  WHERE ma.ativo = 'S' and mw.ativa = 'S' and ma.pode_aprovar = 'S' ";                      
                      
        if($this->idusuario){
			if($this->gestor_sindicato <> 'S') {
                if (!$_SESSION['adm_sindicatos'])
                    $_SESSION['adm_sindicatos'] = '0';
                $this->sql .= ' and i.idsindicato in ('.$_SESSION['adm_sindicatos'].') ';
            }
        }

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
					} elseif($campo[0] == 5)  {
						$this->sql .= " and ".$campo[1]." = ".base64_decode($valor)." ";
					}  
				} 
			}
		}
		//echo $this->sql;exit;
		$this->groupby = "ma.idmatricula";
		$matriculas = array();
		$matriculas = $this->retornarLinhas();
		
		return $matriculas;
	}
	
	function retornarDataLiberacaoAprovacao($idmatricula) {
		$sql = 'select data_cad 
				from matriculas_historicos 
				where 
					tipo = "permissao_aprovacao" and acao = "modificou" and de IS NULL and para = "S" and idmatricula = ' . $idmatricula . ' limit 1';
		return $this->retornarLinha($sql);
	}
	
	function aprovarMatricula($idmatricula) {
		$sql = 'select m.idmatricula, m.idsituacao
				from matriculas m
				inner join matriculas_workflow mw on m.idsituacao = mw.idsituacao and mw.ativa = "S"
				where m.pode_aprovar = "S"	';
		$matricula = $this->retornarLinha($sql);		
		
		if ($matricula['idmatricula']) {
		
			if ($_POST['btn_valida']) {
				$matriculaObj = new Matriculas();
				$matriculaObj->Set("idusuario",$this->idusuario);
				$matriculaObj->Set("modulo", 'gestor');
				$matriculaObj->Set('id', $matricula['idmatricula']); # para AlterarSituacao()
				$possui_documentos_pendentes = $matriculaObj->possuiDocumentosPendentes($idmatricula);
				
				if ($possui_documentos_pendentes) {
					$sql_aprovado_pendencia = 'select idsituacao from matriculas_workflow where ativo = "S" and aprovado_pendencias = "S" limit 1';
					$situacao_aprovado_pendencia = $this->retornarLinha($sql_aprovado_pendencia);
					if (!$situacao_aprovado_pendencia) {
						$this->retorno["sucesso"] = false;
						$this->retorno["mensagem"] = "mensagem_sem_workflow_aprovado_pendencia";
						return $this->retorno;
					}
					$matriculaObj->post["situacao_para"] = $situacao_aprovado_pendencia['idsituacao'];
					$salvar = $matriculaObj->AlterarSituacao($matricula['idsituacao'], $situacao_aprovado_pendencia['idsituacao']);					
				} else {
					$sql_aprovado = 'select idsituacao from matriculas_workflow where ativo = "S" and aprovado = "S" limit 1';
					$situacao_aprovado = $this->retornarLinha($sql_aprovado);
					if (!$situacao_aprovado) {
						$this->retorno["sucesso"] = false;
						$this->retorno["mensagem"] = "mensagem_sem_workflow_aprovado";
						return $this->retorno;
					}
					$matriculaObj->post["situacao_para"] = $situacao_aprovado['idsituacao'];
					$salvar = $matriculaObj->AlterarSituacao($matricula['idsituacao'], $situacao_aprovado['idsituacao']);
				}
				
				if ($salvar['sucesso']) {
					$this->retorno["sucesso"] = true;
					$this->retorno["mensagem"] = "mensagem_situacao_aprovada_sucesso";
					return $this->retorno;
				}
			} else {
				$sql = 'update matriculas set pode_aprovar = "N" where idmatricula = ' . $matricula['idmatricula'];
				$atualiza = $this->executaSql($sql);
				if ($atualiza) {
					$sql = 'insert into matriculas_historicos 
							set 
								data_cad = NOW(),
								tipo = "permissao_aprovacao",
								acao = "modificou",
								de = "S",
								para = "N",
								idusuario = ' . $this->idusuario . ',
								idmatricula = ' . $matricula['idmatricula'];					
					$resultado = $this->executaSql($sql);
					
					$this->retorno["sucesso"] = true;
					$this->retorno["mensagem"] = "mensagem_situacao_reprovada_sucesso";
					return $this->retorno;
				} else {
					$this->retorno["sucesso"] = false;
					$this->retorno["mensagem"] = "erro_reprovar_matricula";
					return $this->retorno;
				}
			}
			
		} else {
			$this->retorno["sucesso"] = false;
			$this->retorno["mensagem"] = "mensagem_sem_matricula_workflow";
			return $this->retorno;
		}
	}
	
}
?>