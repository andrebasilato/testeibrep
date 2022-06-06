<?php 
class Previsoes_Gastos extends Core {

    var $tipo_conta = null;
    var $campos = null;
		
    function ListarTodas() {
						
		$this->sql = "select ".$this->campos." from previsoes_gastos pg						
						INNER JOIN categorias cat ON pg.idcategoria = cat.idcategoria
						where pg.ativo = 'S'";
				
        if ($_GET['filtro_dia'] && (!$_GET['filtro_mes'] || !$_GET['filtro_ano'])) {
            $data_quebrada = explode('-', $_GET['filtro_dia']);
            $_GET['filtro_ano'] = $data_quebrada[0];
            $_GET['filtro_mes'] = $data_quebrada[1];
        }
        
        if($_GET['acao'] == "filtrar_data") {
            if ($_GET['filtro_ano'] && !$_GET['filtro_mes'])
                $_GET['filtro_mes'] = date('m');
            if($_GET['filtro_mes'] && $_GET['filtro_ano']) {
                $filtros .= " and DATE_FORMAT(pg.data, '%Y-%m') = '".$_GET['filtro_ano'].'-'.$_GET['filtro_mes']."' ";				
            }
        } else {
            if($_GET['filtro_dia']) {
                $filtros .= " and DATE_FORMAT(pg.data, '%Y-%m-%d') = '".$_GET['filtro_dia']."' ";				
            } else {
                $data_quebrada = explode('-', date("Y-m-d"));
                $_GET['filtro_ano'] = $data_quebrada[0];
                $_GET['filtro_mes'] = $data_quebrada[1];
                $filtros .= " and DATE_FORMAT(pg.data, '%Y-%m-%d') = '".date("Y-m-d")."' ";
            }
        }
        
		if($_SESSION["adm_gestor_sindicato"] <> "S") {
			$this->sql .= " and pg.idsindicato in (".$_SESSION["adm_sindicatos"].") ";
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
			  }
			} 
		  }
		}//print_r2($_GET);
		
		if($_GET['filtro_mes'] && $_GET['filtro_ano']) {
			$this->sql .= " and DATE_FORMAT(pg.data, '%Y-%m') = '".$_GET['filtro_ano'].'-'.$_GET['filtro_mes']."' ";				
		} else {
			$this->sql .= " and DATE_FORMAT(pg.data, '%Y-%m') = '".date("Y-m")."' ";
		}
        
        if ($_GET['idsindicato_filtro'] && $_GET['idsindicato_filtro'] != -1)
			$filtros .= ' and pg.idsindicato = ' . $_GET['idsindicato_filtro'] . ' ';
		
        $this->sql .= $filtros;
		$this->groupby = "pg.idconta";
		return $this->retornarLinhas();
    }	
		
    function Retornar() {
		$this->sql = "select ".$this->campos." 
						from previsoes_gastos pg
						where pg.ativo = 'S' and pg.idprevisao = '".$this->id."'";

		if($this->idusuario)
			$this->sql .= " 
                and 
                (	
                    (	
                        select ua.idusuario 
							from usuarios_adm ua
                                left join usuarios_adm_sindicatos uai on ua.idusuario = uai.idusuario and uai.ativo = 'S'
                                left join escolas p on uai.idsindicato = p.idsindicato
                            where 
                                ua.idusuario = " . $this->idusuario . "									   
                                and 
                                (	
                                    ua.gestor_sindicato = 'S'
                                    or
                                        pg.idsindicato is null
                                    or 
                                    (
                                        uai.idusuario is not null and 
                                        p.idsindicato is not null
                                    ) 
                                    or 
                                    (	uai.idsindicato = pg.idsindicato and
                                        uai.idusuario is not null
                                    )
                                    or 
                                    (	
                                        uai.idusuario is not null and
                                        uai.idsindicato is not null
                                    )													
                                )
                            limit 1
					) is not null 				
				) ";
						
		$this->retorno = $this->retornarLinha($this->sql);
		
		$this->sql = "select 
						cwa.idacao, 
						cwa.idopcao 
					  from
						contas_workflow_acoes cwa
					  where 
						cwa.idsituacao = '".$this->retorno["idsituacao"]."' and 
						cwa.ativo = 'S' ";			
		$resultado = mysql_query($this->sql);

		while($acao = mysql_fetch_assoc($resultado)) {
		  foreach($GLOBALS['workflow_parametros_contas'] as $op)
			if($op['idopcao'] == $acao['idopcao'] && $op['tipo'] == "visualizacao")
			  $this->retorno["situacao"]["visualizacoes"][$acao["idopcao"]] = $acao;
		}
		
		return $this->retorno;
    }
	
	function Cadastrar() {			
		return $this->SalvarDados();	
	}
	
    function Modificar() {		
		return $this->SalvarDados();	
	}
	
    function Remover() {
		return $this->RemoverDados();	
	}
	
	function RetornarSubcategorias($idcategoria, $json = true) {
				
		$this->sql = "SELECT idsubcategoria, nome FROM categorias_subcategorias where idcategoria = '".$idcategoria."' AND ativo = 'S' AND ativo_painel = 'S' ";

		$this->ordem_campo = "nome";
		$this->groupby = "nome";

		if ($json) {
			$sql = "SELECT nome, subcategoria_obrigatoria FROM categorias where idcategoria = '".$idcategoria."' AND ativo = 'S' and ativo_painel = 'S' ";
			$categoria = $this->retornarLinha($sql);
		}
		$this->limite = -1;
		$this->ordem = "ASC";
		$dados = $this->retornarLinhas();
		if ($json) {			
			$dadosJson = array();
			$dadosJson["subcategoria"] = $dados;
			$dadosJson["categoria"] = $categoria["nome"];
			$dadosJson["subcategoria_obrigatoria"] = $categoria["subcategoria_obrigatoria"];
			return json_encode($dadosJson);
		}
		else
			return $dados;
	}
	
}