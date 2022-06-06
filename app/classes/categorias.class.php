<?php
/**
 * `Categorias`
 *
 * @author     Tomaz Novaes     <tomaz@alfamaweb.com.br>
 * @author     Henrique Feitosa <henriquef@alfamaweb.com.br>
 * @author     Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 *
 * @package    Oráculo
 * @copyright  Copyright (c) 2014 Alfama Web (http://alfamaweb.com.br)
 * @license    Proprietary AlfamaWeb
 * @version    $Id$
 */
class Categorias extends Core
{
    /**
     * @var string
     */
    public $sql_2;

    /**
     * @var string
     */
    public $campos_2;

    /**
     * @var int
     */
    public $limite = 30;

    /**
     * @var string
     */
    public $funcionalidade = 'categorias';

    /**
     * @return array
     */
    public function listarTodas()
    {
        // Carrega query para pegar somente as categorias
        $this->set('sql', $this->getSqlCategorias());

        // Carrega query para pegar as subcategorias
        $this->set('sql_2', $this->getSqlSubCategorias());

        // Prepara dados para pegar o total de registros encontrados
        $this->total = $this->getQuantidadeDeCategoriasESubcategorias();

        $limite = (int)$this->limite;
        if ($limite <= 0 and -1 != $limite) {
            $this->limite = 1;
        }

        // Configura páginas
        $this->paginas = ceil($this->total / $this->limite);
        $this->inicio = ($this->pagina - 1) * $this->limite;

        if ($this->inicio < 0) {
            $this->inicio = 0;
        }

        $sqlAux = $this->executaSql($this->unirQuerys());

        while ($linha = mysql_fetch_assoc($sqlAux)) {
            $this->retorno[] = $linha;
        }

        return $this->retorno;
    }

    /**
     * Query para carregar as categorias
     *
     * @return string
     */
    private function getSqlCategorias()
    {
        if ($this->funcionalidade == 'previsaodegastos') {
            $relacaoQuery = 'INNER JOIN ';
        } else {
            $relacaoQuery = 'LEFT OUTER JOIN ';
        }

        $query = sprintf('SELECT %s FROM categorias c
                        ' .$relacaoQuery. ' categorias_subcategorias cs ON (c.idcategoria = cs.idcategoria AND cs.ativo = "S")
                        WHERE c.ativo = "S" ', $this->campos);
		if ($_SESSION["adm_gestor_sindicato"] <> "S"){
			 //$query .= ' and cs.idsindicato in (' . $_SESSION["adm_sindicatos"] . ') ';
			  $query .= ' AND 
                (  NOT EXISTS (
                      SELECT 
                        csss.idassociacao 
                      FROM 
                        categorias_subcategorias_sindicatos csss 
                      WHERE 
                        csss.ativo = "S" AND 
                        csss.idsubcategoria = cs.idsubcategoria
                    )     
                    OR                   
                    EXISTS (
                      SELECT 
                        csss.idassociacao 
                      FROM 
                        categorias_subcategorias_sindicatos csss 
                      WHERE 
                        csss.ativo = "S" AND 
                        csss.idsindicato in (' . $_SESSION["adm_sindicatos"] . ') AND
                        csss.idsubcategoria = cs.idsubcategoria
                    )
                )';
		}
		
        if ($_GET["idsindicato"]) {
            $query .= ' AND 
                (
                    NOT EXISTS (
                      SELECT 
                        csss.idassociacao 
                      FROM 
                        categorias_subcategorias_sindicatos csss 
                      WHERE 
                        csss.ativo = "S" AND 
                        csss.idsubcategoria = cs.idsubcategoria
                    )     
                    OR
                    EXISTS (
                      SELECT 
                        csss.idassociacao 
                      FROM 
                        categorias_subcategorias_sindicatos csss 
                      WHERE 
                        csss.ativo = "S" AND 
                        csss.idsindicato = "'.$_GET["idsindicato"].'" AND
                        csss.idsubcategoria = cs.idsubcategoria
                    )
                )';
        }

        if(is_array($_GET["q"])) {
			foreach($_GET["q"] as $campo => $valor) {
				//explode = Retira, ou seja retira a "|" da variavel campo
				$campo = explode("|",$campo);
				$valor = str_replace("'","",$valor);
				// Listagem se o valor for diferente de Todos ele faz um filtro
				if(($valor || $valor === "0") && $valor <> "todos") {
					if($campo[1] == "subcategoria") {
						$query .= " and c.idcategoria IS NULL ";
						break;
					}
					$campo_busca = array("idsubcategoria" => "c.idcategoria", "categoria" => "c.nome", "ativo_painel" => "c.ativo_painel");
					$campo[1] = $campo_busca[$campo[1]];
					// se campo[0] for = 1 é pq ele tem de ser um valor exato
					if($campo[0] == 1) {
						$query .= " and ".$campo[1]." = '".$valor."' ";
					// se campo[0] for = 2, faz o filtro pelo comando like
					} elseif($campo[0] == 2) {
						$busca = str_replace("\\'","",$valor);
						$busca = str_replace("\\","",$busca);
						$busca = explode(" ",$busca);
						foreach($busca as $ind => $buscar){
							$query .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
						}
					} elseif($campo[0] == 3) {
						$query .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
					}
				} 
			}
		}
        //echo $query;exit;
        return $query;
    }

    /**
     * Query para carregar as SubCategorias
     *
     * @return string
     */
    private function getSqlSubCategorias()
    {
        $query = sprintf(
            'SELECT %s FROM categorias_subcategorias cs
                INNER JOIN categorias c
                    ON (cs.idcategoria = c.idcategoria) 
                WHERE cs.ativo = "S"',
            $this->campos_2
        );
		if ($_SESSION["adm_gestor_sindicato"] <> "S"){
			 //$query .= ' and cs.idsindicato in (' . $_SESSION["adm_sindicatos"] . ') ';
			  $query .= ' AND 
                (   NOT EXISTS (
                      SELECT 
                        csss.idassociacao 
                      FROM 
                        categorias_subcategorias_sindicatos csss 
                      WHERE 
                        csss.ativo = "S" AND 
                        csss.idsubcategoria = cs.idsubcategoria
                    )     
                    OR                  
                    EXISTS (
                      SELECT 
                        csss.idassociacao 
                      FROM 
                        categorias_subcategorias_sindicatos csss 
                      WHERE 
                        csss.ativo = "S" AND 
                        csss.idsindicato in (' . $_SESSION["adm_sindicatos"] . ') AND
                        csss.idsubcategoria = cs.idsubcategoria
                    )
                )';
		}
        if ($_GET["idsindicato"]) {  
            $query .= ' AND 
                (
                    NOT EXISTS (
                      SELECT 
                        csss.idassociacao 
                      FROM 
                        categorias_subcategorias_sindicatos csss 
                      WHERE 
                        csss.ativo = "S" AND 
                        csss.idsubcategoria = cs.idsubcategoria
                    )     
                    OR
                    EXISTS (
                      SELECT 
                        csss.idassociacao 
                      FROM 
                        categorias_subcategorias_sindicatos csss 
                      WHERE 
                        csss.ativo = "S" AND 
                        csss.idsindicato = "'.$_GET["idsindicato"].'" AND
                        csss.idsubcategoria = cs.idsubcategoria
                    )
                )';
        }

        if(is_array($_GET["q"])) {
			foreach($_GET["q"] as $campo => $valor) {
				//explode = Retira, ou seja retira a "|" da variavel campo
				$campo = explode("|",$campo);
				$valor = str_replace("'","",$valor);
				// Listagem se o valor for diferente de Todos ele faz um filtro
				if(($valor || $valor === "0") && $valor <> "todos") {
					$campo_busca = array("idsubcategoria" => "cs.idsubcategoria", "categoria" => "c.nome", "subcategoria" => "cs.nome", "ativo_painel" => "cs.ativo_painel");
					$campo[1] = $campo_busca[$campo[1]];
					// se campo[0] for = 1 é pq ele tem de ser um valor exato
					if($campo[0] == 1) {
						$query .= " and ".$campo[1]." = '".$valor."' ";
					// se campo[0] for = 2, faz o filtro pelo comando like
					} elseif($campo[0] == 2) {
						$busca = str_replace("\\'","",$valor);
						$busca = str_replace("\\","",$busca);
						$busca = explode(" ",$busca);
						foreach($busca as $ind => $buscar){
							$query .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
						}
					} elseif($campo[0] == 3) {
						$query .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
					}
				} 
			}
		}

        return $query;
    }

    /**
     * @return int
     */
    private function getQuantidadeDeCategoriasESubcategorias()
    {
        $compareFields = array();
        $compareFields[] = str_replace($this->campos, 'count(c.idcategoria) as total', $this->sql);
        $compareFields[] = str_replace($this->campos_2, 'count(cs.idsubcategoria) as total', $this->sql_2);

        $auxSql = sprintf('SELECT (%s) + (%s) AS total', $compareFields[0], $compareFields[1]);
        $linhaAux = $this->retornarLinha($auxSql);

        return (int)$linhaAux['total'];
    }

    /**
     * Une a query em {$this->sql} e {$this->sql_2} usando `UNION`
     * e aplica alguns `filtros` como `order by` e `limit`
     *
     * @return string
     */
    private function unirQuerys()
    {
        $query = $this->sql . ' UNION ' . $this->sql_2;

        if ($this->ordem_campo && $this->ordem) {
            $query .= sprintf(' ORDER BY %s %s', $this->ordem_campo, $this->ordem);
        }

        if ($this->limite > 0) {
            $query .= sprintf(' LIMIT %s, %s', $this->inicio, $this->limite);
        }

        return $query;

    }

    /**
     * @return array
     */
    public function retornarCategoria()
    {
        $this->sql = "SELECT " . $this->campos . " FROM categorias WHERE ativo = 'S' and idcategoria='" . $this->id . "'";

        return $this->retornarLinha($this->sql);
    }

    /**
     * @return array
     */
    public function retornarTodasCategorias()
    {
        $this->sql = sprintf(
            'SELECT %s FROM categorias WHERE ativo="S" AND ativo_painel="S"',
            $this->campos
        );

        $this->set('limite', -1)
            ->set('ordem', 'ASC')
            ->set('ordem_campo', 'nome')
            ->set('groupby', 'nome');

        return $this->retornarLinhas($this->sql);
    }

    /**
     * @return array
     */
    public function retornarSubcategoria()
    {
        $this->sql = "SELECT
						" . $this->campos . "
					  FROM
						categorias_subcategorias cs
						INNER JOIN categorias c ON (cs.idcategoria = c.idcategoria)
					  WHERE
					  	cs.ativo = 'S' and
						cs.idsubcategoria='" . $this->id . "'";

        return $this->retornarLinha($this->sql);
    }

    /**
     * @return array
     */
    public function cadastrar()
    {
        return $this->salvarDados();
    }

    /**
     * @return array
     */
    public function modificar()
    {
        return $this->salvarDados();
    }

    /**
     * @return array
     */
    public function remover()
    {
        return $this->removerDados();
    }

    /**
     * @return string
     */
    public function retornarSubcategoriasCategoria()
    {
        $this->sql = "SELECT sub.idsubcategoria, sub.nome
						  FROM categorias_subcategorias sub
						  INNER JOIN categorias c ON ( c.idcategoria = sub.idcategoria )
						  WHERE c.ativo =  'S'
						  AND c.idcategoria = '" . $this->id . "' "; 

        if ($_SESSION["adm_gestor_sindicato"] <> "S") {
            $this->sql .= " AND 
                            (
                            not exists (
                              select 
                                csss.idassociacao 
                              from 
                                categorias_subcategorias_sindicatos csss 
                              where 
                                csss.ativo = 'S' and 
                                csss.idsubcategoria = sub.idsubcategoria
                            )     
                            OR
                            exists (
                              select 
                                csss.idassociacao 
                              from 
                                categorias_subcategorias_sindicatos csss 
                              where 
                                csss.ativo = 'S' and csss.idsindicato IN (" . $_SESSION["adm_sindicatos"] . ") and
                                csss.idsubcategoria = sub.idsubcategoria
                            )
                        )";
        }
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while ($row = mysql_fetch_assoc($query)) {
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }

    public function retornarCategoriaSindicato() {
		
		$this->sql = "SELECT 
						c.idcategoria, 
						c.nome
					  FROM 
						  categorias c
						  INNER JOIN categorias_subcategorias cs ON ( c.idcategoria = cs.idcategoria )
					  WHERE 
						  c.ativo =  'S' AND  cs.ativo =  'S' and
							(
							not exists (
							  select 
								csss.idassociacao 
							  from 
								categorias_subcategorias_sindicatos csss 
							  where 
								csss.ativo = 'S' and 
								csss.idsubcategoria = cs.idsubcategoria
							)	  
							OR
							exists (
							  select 
								csss.idassociacao 
							  from 
								categorias_subcategorias_sindicatos csss 
							  where 
								csss.ativo = 'S' and csss.idsindicato = '" . intval($this->id) . "' and
								csss.idsubcategoria = cs.idsubcategoria
							)
						) 
						GROUP BY c.idcategoria 
						order by c.nome";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while ($row = mysql_fetch_assoc($query)) {
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }
	public function retornarSubcategoriasSindicato() {		
		 $this->sql = "SELECT sub.idsubcategoria, sub.nome
						  FROM categorias_subcategorias sub
						  WHERE sub.ativo =  'S'
                          AND sub.ativo_painel =  'S'
						  AND sub.idcategoria = '" . $this->id . "'
							 and
					    (
							not exists (
							  select 
								cs.idassociacao 
							  from 
								categorias_subcategorias_sindicatos cs 
							  where 
								cs.ativo = 'S' and 
								cs.idsubcategoria = sub.idsubcategoria
							)	  
							OR
							exists (
							  select 
								cs.idassociacao 
							  from 
								categorias_subcategorias_sindicatos cs 
							  where 
								cs.ativo = 'S' and cs.idsindicato = '" . intval($this->idsindicato) . "' and
								cs.idsubcategoria = sub.idsubcategoria
							)
						)
						order by sub.nome
					";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while ($row = mysql_fetch_assoc($query)) {
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }
    /**
     * @param $idCategoria
     *
     * @return AssociacaoIterator
     * @throws InvalidArgumentException
     */
    public function retornarAssociacoes($idCategoria)
    {
        if (!is_numeric($idCategoria)) {
            throw new InvalidArgumentException('Parâmetro $idCategoria deve conter um valor numérico');
        }

        $mySqlResource = mysql_query('SELECT * FROM categorias_subcategorias_sindicatos WHERE  ativo = "S" and  idsubcategoria = ' . $idCategoria);

        return new AssociacaoIterator($mySqlResource);
    }
	
	function BuscarSindicatos()
    {
        $this->sql = "select
					i.idsindicato as 'key', 
					i.nome_abreviado as value 
				  from 
					sindicatos i 
				  where 
					i.nome_abreviado LIKE '%" . $this->get["tag"] . "%' AND
					i.ativo = 'S' and
					not exists (
					  select 
						cs.idassociacao 
					  from 
						categorias_subcategorias_sindicatos cs 
					  where 
						cs.idsindicato = i.idsindicato and  cs.ativo = 'S' and 
						cs.idsubcategoria = '" . intval($this->id) . "'
					)";

        $this->limite = -1;
        $this->ordem_campo = "i.nome";
        $this->groupby = "i.idsindicato";
        $this->retorno = $this->retornarLinhas();

        return json_encode($this->retorno);
    }
	
	function ListarSindicatosAssociadas()
    {
        $this->sql = "select
					" . $this->campos . "
				  from
					categorias_subcategorias_sindicatos cs
					inner join sindicatos i ON (cs.idsindicato = i.idsindicato)
				  where 
					i.ativo = 'S' and cs.ativo = 'S' and 
					cs.idsubcategoria = " . intval($this->id);

        $this->limite = -1;
        $this->ordem = "asc";
        $this->ordem_campo = "i.nome";
        return $this->retornarLinhas();
    }
	 function AssociarSindicato()
    {
        foreach ($this->post["ids"] as $idsindicato) {
            
			$this->sql = "insert into categorias_subcategorias_sindicatos set ativo = 'S', data_cad = now(), idsubcategoria = '" . $this->id . "', idsindicato = '" . intval($idsindicato) . "'";
			$associar = $this->executaSql($this->sql);
			$this->monitora_qual = mysql_insert_id();
			
            if ($associar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 4;
                $this->monitora_onde = 49;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }


    function DesassociarSindicato()
    {

          $this->sql = "update categorias_subcategorias_sindicatos set ativo = 'N' where idassociacao = " . intval($this->post["remover"]);
          $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        return $this->retorno;
    }
	
}