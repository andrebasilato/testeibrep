<?php

/**
 * Class Relatório
 *
 */
class Relatorio extends Core
{
    /**
     * Conteudo que armazena informações temporaria
     * ou final dos relatórios
     *
     * @var string
     */
    private $_content = null;

    /**
     * @param string $content
     */
    private function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }


    /**
     * Concatena uma valor a uma variável já iniciada
     *
     * @param $var    string variavel a ser concatenada
     * @param $value  string valor adicionado para a variável
     *
     * @return $this
     */
    public function concat($var, $value)
    {
        $this->{$var} .= $value;
        return $this;
    }

    /**
     * Gera os dados do relatório
     *
     * @return mixed
     */
    function gerarRelatorio(){
        // var_dump($_GET);exit;
       if ($_GET['q']['de_ate|tipo_data_filtro|ma.data_registro'] == 'PER') {
            $data_aux_de = $_GET['de'];
            $data_aux_ate = $_GET['ate'];


            if (
                ((!$data_aux_de || !$data_aux_ate) )
                ) {
                $retorno['erro'] = true;
                $retorno['erros'][] = 'datas_obrigatorias';
                return $retorno;
            }
            if (
                (dataDiferenca(formataData($data_aux_de, 'en', 0), formataData($data_aux_ate, 'en', 0), 'D') > 365)
                ) {
                $retorno['erro'] = true;
                $retorno['erros'][] = 'intervalo_maior_um_ano';
                return $retorno;
            }
            #VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO - ##### SEM DIA NA DATA - FIM
        }elseif (!$_GET['q']['de_ate|tipo_data_filtro|ma.data_registro'] && !$_GET['data_cancelamento_de']) {
            if (!$_GET["de_comissao"] && !$_GET["ate_comissao"]) {
                $retorno['erro'] = true;
                $retorno['erros'][] = 'tipo_data_filtro_vazio';
                return $retorno;
            }
        }

        $_request = $_GET['q'];


        if ($_GET['data_cancelamento_de'])
            $this->_setQueryHeaderCancelamento();
        else
            $this->_setQueryHeader();

        $this->sql .= " WHERE ma.ativo='S'";

        if(!$_GET["idsindicato"] && $_SESSION['adm_gestor_sindicato'] != 'S'){
              $this->sql .= ' and ma.idsindicato in ('.$_SESSION['adm_sindicatos'].')';
        }
        if(!$_GET["q[3|ma.idescola]"] && $_SESSION['adm_gestor_sindicato'] != 'S'){
            $this->sql .= ' and ma.idescola in ('.$_SESSION['adm_sindicatos'].')';
        }
        if ($_GET['idsindicato']) {
            $this->sql .= ' AND ma.idsindicato IN (' . implode(', ', $_GET['idsindicato']) . ')';
        }
        // if ($_GET['idescola']) {
        //     $this->sql .= ' AND ma.idescola IN (' . implode(', ', $_GET['idescola']) . ')';
        // }
        if (is_array($_GET["q"])) {
            foreach($_GET["q"] as $campo => $valor) {

                $campo = explode("|",$campo);
                $valor = str_replace("'","",$valor);

                if(($valor || $valor === "0") && $valor <> "todos") {

                    if($campo[0] == 1) {
                        if ($valor == 'sem_cidade') {
                             $this->sql .= " and ".$campo[1]." IS NULL ";
                        } else {
                            $this->sql .= " and ".$campo[1]." = '".$valor."' ";
                        }
                     } elseif($campo[0] == 2)  {
                        $this->sql .= " and ".$campo[1]." like '%".urldecode($valor)."%' ";
                    }
                    elseif($campo[0] == 3)  {
                        $this->sql .= " and ".$campo[1]." in (" . implode(",", $valor) . ") ";
                    }
                     elseif($campo[0] == 'de_ate') {
                        if($valor == 'HOJ') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
                        } elseif ($valor == 'ONT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
                        } elseif($valor == 'SET') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
                        } elseif ($valor == 'QUI') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
                        } elseif($valor == 'MAT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
                        } elseif($valor == 'MPR') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
                        } elseif($valor == 'MAN') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
                        }
                    }
                }
            }
        }

        //echo $this->sql;

        if($data_aux_de) {
            $this->sql .= " and (ma.data_registro >= '".formataData($data_aux_de,'en',0)." 00:00:00') ";
        }

        if($data_aux_ate) {
            $this->sql .= " and (ma.data_registro <= '".formataData($data_aux_ate,'en',0)." 23:59:59') ";
        }

        if($_GET["de_datamatricula"]) {
            $this->sql .= " and (ma.data_registro >= '".formataData($_GET["de_datamatricula"],'en',0)." 00:00:00') ";
        }

        if($_GET["ate_datamatricula"]) {
            $this->sql .= " and (ma.data_registro <= '".formataData($_GET["ate_datamatricula"],'en',0)." 23:59:59') ";
        }

        if($_GET["de_comissao"]) {
            $this->sql .= " and (ma.data_comissao >= '".formataData($_GET["de_comissao"],'en',0)." 00:00:00') ";
        }

        if($_GET["ate_comissao"]) {
            $this->sql .= " and (ma.data_comissao <= '".formataData($_GET["ate_comissao"],'en',0)." 23:59:59') ";
        }

        if($_GET["idsituacao"]) {
            $this->sql .= " and ma.idsituacao in (".implode(", ", $_GET["idsituacao"]).")";
        }

        if ($_GET['data_cancelamento_de']) {
            $_GET["data_cancelamento_de"] = formataData($_GET["data_cancelamento_de"], "en", 0);
      $_GET["data_cancelamento_ate"] = formataData($_GET["data_cancelamento_ate"], "en", 0);
            $this->sql .= " and
                                (select max(mhi.idhistorico)
                                 from matriculas_historicos mhi
                                 where
                                    mhi.idmatricula = ma.idmatricula
                                    and mhi.tipo = 'situacao'
                                    and mhi.acao = 'modificou'
                                    and mhi.para = '" . $_GET['q']["1|ma.idsituacao"] . "'
                                    and date_format(mhi.data_cad,'%Y-%m-%d') >= '{$_GET["data_cancelamento_de"]}'
                                    and date_format(mhi.data_cad,'%Y-%m-%d') <= '{$_GET["data_cancelamento_ate"]}')
                            and ma.idmotivo_cancelamento = '" . $_GET['motivo_cancelamento'] . "' ";
            $this->sql .= " group by ma.idmatricula";

            $resultado = $this->executaSql($this->sql);
            while ($linha = mysql_fetch_assoc($resultado)) {
                $retorno[] = $linha;
            }

            $this->setContent($retorno);

            $_GET['colunas'] = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16);
        } else {
            $this->sql .= " group by ma.idmatricula";
            $this->setContent(
                $this->set('ordem_campo', 'ma.idmatricula')
                    ->set('ordem', 'desc')
                    ->set('groupby', 'ma.idmatricula')
                    ->retornarLinhas()
            );
        }




        return $this->getContent();
    }

    /**
     * @return $this
     */
    private function _setQueryHeader()
    {
        $this->sql =<<< QUERY
            SELECT
                {$this->campos}
                    FROM
                        matriculas ma
                        INNER JOIN sindicatos inst ON (ma.idsindicato=inst.idsindicato)
                        LEFT JOIN sindicatos_valores_cursos svv ON (svv.idsindicato = inst.idsindicato AND svv.ativo = 'S')
                        INNER JOIN mantenedoras mant ON (ma.idmantenedora=mant.idmantenedora)
                        INNER JOIN cursos cu ON (ma.idcurso=cu.idcurso)
                        INNER JOIN contas co ON (ma.idmatricula=co.idmatricula)
                        INNER JOIN ofertas o ON (ma.idoferta=o.idoferta)
                        INNER JOIN ofertas_turmas tu ON (ma.idturma=tu.idturma)
                        INNER JOIN escolas po ON (ma.idescola=po.idescola)
                        LEFT JOIN cfcs_valores_cursos cvv ON (cvv.idcfc = po.idescola AND cvv.ativo = 'S')
                        INNER JOIN pessoas pe ON (ma.idpessoa=pe.idpessoa)
                        LEFT JOIN vendedores ve ON (ma.idvendedor=ve.idvendedor)
                        INNER JOIN matriculas_workflow mw on (ma.idsituacao = mw.idsituacao)
                        LEFT OUTER JOIN cidades cid on (pe.idcidade = cid.idcidade)
                        LEFT OUTER JOIN estados est on (pe.idestado = est.idestado)
                        LEFT OUTER JOIN estados estinst on (inst.idestado = estinst.idestado)
            LEFT OUTER JOIN solicitantes_bolsas sb on (ma.idsolicitante = sb.idsolicitante)
QUERY;

        return $this;
    }

    private function _setQueryHeaderCancelamento()
    {
        $this->sql = '
            SELECT
                ' . $this->campos . '
                    FROM
                        matriculas ma
                        INNER JOIN sindicatos inst ON (ma.idsindicato=inst.idsindicato)
                        INNER JOIN mantenedoras mant ON (ma.idmantenedora=mant.idmantenedora)
                        INNER JOIN cursos cu ON (ma.idcurso=cu.idcurso)
                        INNER JOIN ofertas o ON (ma.idoferta=o.idoferta)
                        INNER JOIN ofertas_turmas tu ON (ma.idturma=tu.idturma)
                        INNER JOIN escolas po ON (ma.idescola=po.idescola)
                        INNER JOIN pessoas pe ON (ma.idpessoa=pe.idpessoa)
                        LEFT JOIN vendedores ve ON (ma.idvendedor=ve.idvendedor)
                        INNER JOIN matriculas_workflow mw on (ma.idsituacao = mw.idsituacao)

                        inner join matriculas_historicos mh
                                on ma.idmatricula = mh.idmatricula and mh.tipo = "situacao" and mh.acao = "modificou" and mh.para = "' .$_GET['q']['1|ma.idsituacao'] . '" and mh.data_cad >= "' . formataData($_GET['data_cancelamento_de'],'en',0) . '" and mh.data_cad <= "' . formataData($_GET['data_cancelamento_ate'],'en',0) . '"

                        LEFT OUTER JOIN cidades cid on (pe.idcidade = cid.idcidade)
                        LEFT OUTER JOIN estados est on (pe.idestado = est.idestado)
                        LEFT OUTER JOIN estados estinst on (inst.idestado = estinst.idestado)
            LEFT OUTER JOIN solicitantes_bolsas sb on (ma.idsolicitante = sb.idsolicitante)
                         ';
        return $this;
    }
    /**
     *
     *
     * @param $dados
     * @param null $q
     * @param $idioma
     * @param string $configuracao
     */
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
					  $campoAux = str_replace(array("q[",'[',"]"),"",$campo["nome"]);
					  $campoAux = $_GET["q"][$campoAux];

					  if($campo["sql_filtro"]){
					  	  if($campo["sql_filtro"] == "array"){
							   $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
							   $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAux]];
						  } else {
					  	       if (! is_array($campoAux)) {
                                   $sql = str_replace("%", $campoAux, $campo["sql_filtro"]);
                                   $seleciona = mysql_query($sql);
                                   $linha = mysql_fetch_assoc($seleciona);
                                   $campoAux = $linha[$campo["sql_filtro_label"]];
                               } else {
					  	           $lista = '';
					  	           $cont = 1;
					  	           if (in_array('todos', $campoAux)) {
                                       $campoAux = 'Todos';
                                   } else {
                                       foreach ($campoAux as $aux) {
                                           $sql = str_replace("%", $aux, $campo["sql_filtro"]);
                                           $seleciona = mysql_query($sql);
                                           $linha = mysql_fetch_assoc($seleciona);
                                           $aux = $linha[$campo["sql_filtro_label"]];

                                           if ($cont == count($campoAux)) {
                                               $lista .= $aux;
                                           } else {
                                               $lista .= $aux . ', ';
                                           }
                                           $cont++;
                                       }
                                       $campoAux = $lista;
                                   }
                               }
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


        }

    /**
     *
     *
     *
     * @api
     */
    function RetornarCursosOferta() {
        $this->sql = "SELECT c.idcurso, c.nome
                          FROM cursos c
                          INNER JOIN ofertas_cursos oc on c.idcurso = oc.idcurso and oc.ativo = 'S'
                          WHERE oc.idoferta = '".$this->id."'";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while($row = mysql_fetch_assoc($query)){
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }

    public function RetornarSolicitantesBolsas() {
        $this->sql  = " SELECT idsolicitante, nome";
        $this->sql .= " FROM solicitantes_bolsas";
        $this->sql .= " WHERE ativo = 'S'";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();

        while ($row = mysql_fetch_assoc($query)) {
            $this->retorno[] = $row;
        }

        echo json_encode($this->retorno);
    }

    public function retornarVendedorPadrao() {
        $this->sql = "select nome, idvendedor from vendedores where ativo = 'S' and atendente_padrao = 'S'";
        return $this->retornarLinha($this->sql);
    }
}
