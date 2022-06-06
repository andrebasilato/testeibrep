<?php
class Relatorio extends Core {

    function gerarRelatorio(){

        if($_GET['q']['de_ate|tipo_data_filtro|vv.data_cad'] == 'PER' && (!$_GET["de"] || !$_GET["ate"]) ){
            unset($_GET['q']['de_ate|tipo_data_filtro|vv.data_cad']);
        }
	
		#VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO
		if ( 
			($_GET['q']['de_ate|tipo_data_filtro|vv.data_cad'] == 'PER' && (!$_GET["de"] || !$_GET["ate"]) ) 
			) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'datas_obrigatorias';
			return $retorno;
		}
		if (
			(dataDiferenca(formataData($_GET["de"], 'en', 0), formataData($_GET["ate"], 'en', 0), 'D') > 365)
			) {
			$retorno['erro'] = true;
			$retorno['erros'][] = 'intervalo_maior_um_ano';
			return $retorno;
		}
		#VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO - FIM

        $this->sql = "SELECT
                        ".$this->campos."
                      FROM
                        visitas_vendedores vv
                        INNER JOIN vendedores ve ON (vv.idvendedor = ve.idvendedor)
                        LEFT OUTER JOIN visitas_vendedores_cursos vvc ON (vvc.idvisita = vv.idvisita)
                        LEFT OUTER JOIN cursos cu ON (vvc.idcurso = cu.idcurso)
                        LEFT OUTER JOIN pessoas pe ON (vv.idpessoa = pe.idpessoa)
                        LEFT OUTER JOIN midias_visitas miv ON (vv.idmidia = miv.idmidia)
                        LEFT OUTER JOIN locais_visitas lov ON (vv.idlocal = lov.idlocal)
                        LEFT OUTER JOIN estados AS e ON (e.idestado = vv.idestado)
                        LEFT OUTER JOIN cidades AS c ON (c.idcidade = vv.idcidade)";
        if($_GET["idsindicato"]) {
            $this->sql .= " INNER JOIN vendedores_sindicatos vi ON vi.idvendedor = ve.idvendedor AND vi.ativo = 'S' AND vi.idsindicato = ".$_GET["idsindicato"];
        }
        
        $this->sql .= " WHERE 
                        vv.ativo = 'S'";
        
        $this->sql .= $this->colocarFiltros('vv.data_cad');
        
        $this->sql .= " UNION ALL SELECT
                        ".$this->campos2."
                      FROM
                        visitas_vendedores vv
                        inner join visitas_vendedores_iteracoes vvi on vv.idvisita = vvi.idvisita and vvi.ativo = 'S'
                        INNER JOIN vendedores ve ON (vv.idvendedor = ve.idvendedor)
                        LEFT OUTER JOIN visitas_vendedores_cursos vvc ON (vvc.idvisita = vv.idvisita)
                        LEFT OUTER JOIN cursos cu ON (vvc.idcurso = cu.idcurso)
                        LEFT OUTER JOIN pessoas pe ON (vv.idpessoa = pe.idpessoa)
                        LEFT OUTER JOIN midias_visitas miv ON (vv.idmidia = miv.idmidia)
                        LEFT OUTER JOIN locais_visitas lov ON (vv.idlocal = lov.idlocal)
                        LEFT OUTER JOIN estados AS e ON (e.idestado = vv.idestado)
                        LEFT OUTER JOIN cidades AS c ON (c.idcidade = vv.idcidade)";
        if($_GET["idsindicato"]) {
            $this->sql .= " INNER JOIN vendedores_sindicatos vi ON vi.idvendedor = ve.idvendedor AND vi.ativo = 'S' AND vi.idsindicato = ".$_GET["idsindicato"];
        }
        
        $this->sql .= " WHERE 
                        vv.ativo = 'S'";
        
        $this->sql .= $this->colocarFiltros('vvi.data_visita');
        //echo $this->sql;exit;
        //$this->sql .= " group by vv.idvisita";
        //echo $this->sql;exit;
        //$this->groupby = "vv.idvisita";
        $retorno = $this->retornarLinhas();
        foreach($retorno as $ind => $val) {
            $this->sql = "SELECT cu.nome
                      FROM
                        visitas_vendedores_cursos vvc
                        INNER JOIN cursos cu ON (vvc.idcurso = cu.idcurso)
                    where 
                        vvc.idvisita = '".$val['idvisita']."'";
            $this->limite = -1;
            $this->ordem = "asc";
            $this->ordem_campo = "cu.nome";
            $cursos = $this->retornarLinhas();
            $cursosVisita = array();
            foreach($cursos as $curso) {
                $cursosVisita[] = $curso['nome'];
            }
            
            if ($val['data_visita']) {
                $retorno[$ind]['data_cad'] = $val['data_visita'];
            }
			
			/*$this->sql = "SELECT vvi.numero, vvi.data_visita
                      FROM
                        visitas_vendedores_iteracoes vvi
                    where 
                        vvi.idvisita = '".$val['idvisita']."' and vvi.ativo = 'S' ";
            $this->limite = -1;
            $this->ordem = "asc";
            $this->ordem_campo = "vvi.data_visita";
            $iteracoes = $this->retornarLinhas();
            $iteracoesVisita = array();
			$iteracoesVisita[] = '1 - ' . formataData($val['data_cad'], 'pt', 0);
            foreach($iteracoes as $iteracao) {
                $iteracoesVisita[] = $iteracao['numero'] . ' - ' . formataData($iteracao['data_visita'], 'pt', 0);
            }
            
			$retorno[$ind]['iteracoes'] = implode(', ',$iteracoesVisita);*/
            $retorno[$ind]['curso'] = implode(', ',$cursosVisita);
        }
        
        return $retorno;

    }
    
    public function colocarFiltros($campoSelect)
    {
        $sql = '';
        if(is_array($_GET["q"])) {
            foreach($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|",$campo);
                $valor = str_replace("'","",$valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if(($valor || $valor === "0") && $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if($campo[0] == 1) {
                        $sql .= " and ".$campo[1]." = '".$valor."' ";
                    // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif($campo[0] == 2)  {
                        $sql .= " and ".$campo[1]." like '%".urldecode($valor)."%' ";
                    } elseif($campo[0] == 'de_ate') {
                        if ($valor == 'HOJ') {
                            $sql .= " and date_format(".$campoSelect.",'%Y%m%d') = '".date("Ymd")."'";
                        } elseif ($valor == 'ONT') {
                            $sql .= " and date_format(".$campoSelect.",'%Y%m%d') = '".date("Ymd", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
                        } elseif ($valor == 'SET') {
                            $sql .= " and date_format(".$campoSelect.",'%Y%m%d') <= '".date("Ymd")."'
                                            and date_format(".$campoSelect.",'%Y%m%d') >= '".date("Ymd", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
                        } elseif ($valor == 'QUI') {
                            $sql .= " and date_format(".$campoSelect.",'%Y%m%d') <= '".date("Ymd")."'
                                          and date_format(".$campoSelect.",'%Y%m%d') >= '".date("Ymd", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
                        } elseif ($valor == 'MAT') {
                            $sql .= " and date_format(".$campoSelect.",'%Y%m') = '".date("Ym")."'";
                        } elseif ($valor == 'MPR') {
                            $sql .= " and date_format(".$campoSelect.",'%Y%m') = '".date("Ym", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
                        } elseif ($valor == 'MAN') {
                            $sql .= " and date_format(".$campoSelect.",'%Y%m') = '".date("Ym", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
                        }
                    }
                }
            }
        }

        if($_GET["de"]) {
            $sql .= " and (date_format(".$campoSelect.",'%Y%m%d') >= '".str_replace("-", "", formataData($_GET["de"],'en',0))."') ";
        }

        if($_GET["ate"]) {
            $sql .= " and (date_format(".$campoSelect.",'%Y%m%d') <= '".str_replace("-", "", formataData($_GET["ate"],'en',0))."') ";
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
                          $campoAuxNovo = str_replace(array("q[","]"),"",$campo["nome"]);
                          $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAuxNovo]];
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
                    } elseif($campo["sql_filtro"]){
                        $sql = str_replace("%",$_GET[$campo["nome"]],$campo["sql_filtro"]);
                        $seleciona = mysql_query($sql);
                        $linha = mysql_fetch_assoc($seleciona);
                        $campoAux = $linha[$campo["sql_filtro_label"]];
                    } else {
                      $campoAux = $_GET[$campo["nome"]];
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


            echo '<table class="zebra-striped" id="sortTableExample" width="3000">';
            echo '<thead>';
            echo '<tr>';
            foreach($this->config[$configuracao] as $ind => $valor){

                    $tamanho = "";
                    if($valor["tamanho"]) $tamanho = ' width="'.$valor["tamanho"].'"';

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

            if(count($dados) == 0){
                echo '<tr>';
                echo '<td colspan="'.count($this->config[$configuracao]).'">Nenhum informação foi encontrada.</td>';
                echo '</tr>';
            } else {
                foreach($dados as $i => $linha){
                    echo '<tr>';
                    foreach($this->config[$configuracao] as $ind => $valor){

                        if($valor["tipo"] == "banco") {
                            //echo '<td>'.stripslashes(strtoupper($linha[$valor["valor"]])).'</td>';
                            echo '<td>'.stripslashes($linha[$valor["valor"]]).'</td>';
                        } elseif($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
                            $valor = $valor["valor"]." ?>";
                            $valor = eval($valor);
                            echo '<td>'.stripslashes($valor).'</td>';
                        } elseif($valor["tipo"] == "array") {
                            $variavel = $GLOBALS[$valor["array"]];
                            echo '<td>'.strtoupper($variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]]).'</td>';
                        } elseif($valor["busca_tipo"] != "hidden") {
                            echo '<td>'.stripslashes($valor["valor"]).'</td>';
                        }
                    }

                    echo '</tr>';
                }

            }

            echo '</tbody>';
            echo '</table>';
        }

}

?>