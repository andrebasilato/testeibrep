<?php
class Orcamentos_Planejados extends Core {
    
	var $idsindicato;
    
    function ListarTodas() {
		
        $dataInicio = date("Y-m", mktime(0, 0, 0, $_GET["de_mes"], 1, $_GET["de_ano"]));
        $dataFim    = date("Y-m", mktime(0, 0, 0, $_GET["ate_mes"] + 1, 1, $_GET["ate_ano"]));

		$this->pagina       = 1;
		$this->limite       = -1;
		$this->sql          = "select ".$this->campos." from contas_orcamentos cp where idsindicato = " . $this->idsindicato . " and (DATE_FORMAT(mes,'%Y-%m') >= '" . $dataInicio . "' and DATE_FORMAT(mes,'%Y-%m') <= '" . $dataFim . "' ) and ativo = 'S'";
		$this->ordem        = "asc";
		$this->ordem_campo  = "data_cad";
		return $this->retornarLinhas($this->sql);
        
    }
    
    
    function CadastrarModificar() {
        
        $this->executaSql("begin");
        
        /*
        echo "<pre>";
        print_r($this->post);
        echo "</pre>";
        exit();
        */
		
		
        
        foreach ($this->post["dados"] as $idsindicato => $categorias) {
            $idmeta   = array();
            $monitora = false;
            foreach ($categorias as $idcategoria => $dados) {
			foreach ($dados as $mes => $valores) {
                
                $monitora = false;
                
                if ($valores["valor"]) {
                    $valores["valor"]     = str_replace(".", "", $valores["valor"]);
                    $valores["valor"]     = str_replace(",", ".", $valores["valor"]);
                    $valores["valor_sql"] = "'" . $valores["valor"] . "'";
                } else {
                    $valores["valor_sql"] = "NULL";
                }
                                
                $this->monitora_dadosantigos = NULL;
                $this->monitora_dadosnovos   = NULL;
                
                if ($valores["idorcamento"]) {
                    
                    $this->sql                   = "select * from contas_orcamentos where idorcamento = " . $valores["idorcamento"];
                    $this->monitora_dadosantigos = $this->retornarLinha($this->sql);
                    
                    if ( ($valores["valor"] <> $this->monitora_dadosantigos["valor"]) ) {
                        
                        $monitora = true;
                        
                        $this->sql = "update contas_orcamentos set ativo = 'S', valor = " . $valores["valor_sql"] . " where idorcamento = " . $valores["idorcamento"];
                        $executa   = $this->executaSql($this->sql);
                        
                        $this->sql                 = "select * from contas_orcamentos where idorcamento = " . $valores["idorcamento"];
                        $this->monitora_dadosnovos = $this->retornarLinha($this->sql);
                        
                        $this->monitora_oque = 2;
                        $this->monitora_qual = $valores["idorcamento"];
                        
                    }
                    
                } else {
                    if ($valores["valor"] <> "" && $valores["valor"] <> "0,00") {
                        $this->sql = "insert into contas_orcamentos set data_cad = now(), idsindicato = " . $idsindicato . ", mes = '" . $mes . "-01', valor = " . $valores["valor_sql"] . ", idcategoria = " . $idcategoria . "";
                        $executa   = $this->executaSql($this->sql);
                        
                        $this->monitora_oque = 1;
                        $this->monitora_qual = mysql_insert_id();
                        
                        $monitora = true;
                        
                    }
                }
                if ($executa) {
                    $this->monitora_onde = 186;
                    if ($monitora)
                        $this->Monitora();
                }
                
            }
			}
        }
        
        $this->executaSql("commit");
        
        $this->retorno["sucesso"] = true;
        return $this->retorno;
    }
    
    
}