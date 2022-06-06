<?php
class Perfis extends Core
{
		
	function ListarTodas() {		
		$this->sql = 'SELECT '.$this->campos.' FROM
							usuarios_adm_perfis where ativo="S"';
		
		if(is_array($_GET['q'])) {
			foreach($_GET['q'] as $campo => $valor) {
				//explode = Retira, ou seja retira a "|" da variavel campo
				$campo = explode('|', $campo);
				$valor = str_replace("'","",$valor);
				// Listagem se o valor for diferente de Todos ele faz um filtro
				if(('0' === $valor || $valor) and $valor != 'todos') {
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
		
		$this->groupby = 'idperfil';
		return $this->retornarLinhas();
	}
	
	function Retornar() {
		$this->sql = "SELECT ".$this->campos."
							FROM
							 usuarios_adm_perfis where ativo='S' and idperfil='".$this->id."'";			
		return $this->retornarLinha($this->sql);
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
	
	function RetornarAcoes(){
		$this->retorno = array();
		$diretorio_modulos = getcwd();
		$diretorio_modulos = $diretorio_modulos."/modulos";
		$ponteiro  = opendir($diretorio_modulos);
		$ignorar = array(".","..","index","otimizacao");
		
		while($modulo = readdir($ponteiro)) {
		    include("../especifico/inc/config.modulos.php");
		    
			if(!in_array($modulo, $ignorar) && is_dir($diretorio_modulos)) {
								
				$arquivo_config = $diretorio_modulos.'/'.$modulo.'/index/config.php';
				$arquivo_idioma = $diretorio_modulos.'/'.$modulo.'/index/idiomas/'.$this->config['idioma_padrao'].'/config.php';
				
				if (file_exists($arquivo_config) 
					&& file_exists($arquivo_idioma)				    
				){
				    				        
                    include($arquivo_config);
		            include($arquivo_idioma);
		            $nome_modulo = $idioma[$config["modulo"]];
		            unset($config);
		             
		            $diretorio_funcionalidades = $diretorio_modulos."/".$modulo;
		            $ponteiro_funcionalidades  = opendir($diretorio_funcionalidades);
		            $funcionalidades = array();
		            while($funcionalidade = readdir($ponteiro_funcionalidades)) {
		                if(!in_array($funcionalidade, $ignorar) && is_dir($diretorio_funcionalidades) && verificaModulos($modulo, $funcionalidade, false)) {
		                     
		                    $arquivo_config = $diretorio_funcionalidades."/".$funcionalidade."/config.php";
		                    $arquivo_idioma = $diretorio_funcionalidades."/".$funcionalidade."/idiomas/".$this->config["idioma_padrao"]."/config.php";
		                    if(file_exists($arquivo_config) && file_exists($arquivo_idioma)){
		                        //echo $arquivo_config.'-'.$arquivo_idioma.'<br />';
		                        include($arquivo_config);
		                        include($arquivo_idioma);
		                        $funcionalidades[$funcionalidade]["nome"] = $idioma[$config["funcionalidade"]];
		            
		                        if(is_array($config["acoes"])){
		                            foreach($config["acoes"] as $ind => $idioma_acao){
		                                $funcionalidades[$funcionalidade]["acoes"][$ind] = $idioma[$idioma_acao];
		                            }
		                        }
		            
		                        unset($config);
		                    }
		                }
		            }
		            asort($funcionalidades);
		            $this->retorno[$modulo]["nome"] = $nome_modulo;
		            $this->retorno[$modulo]["funcionalidades"] = $funcionalidades;				        
				}// Fim do file_exists
			}
		}
		return $this->retorno;
		
	}
	
  function RetornarUsuariosPerfil() {		
	$this->sql = "SELECT 
					u.nome
				  FROM
					usuarios_adm u
					INNER JOIN usuarios_adm_perfis p ON (u.idperfil = p.idperfil) 
				  WHERE 
					u.idperfil = '".$this->id."' AND
					u.ativo = 'S'";
		
		$this->ordem = 'ASC';
		$this->ordem_campo = 'u.nome';
		$this->limite = -1;		
		$this->groupby = 'u.nome';
		
		return $this->retornarLinhas();
	}
}