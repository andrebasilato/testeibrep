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

        $_request = $_GET['q'];

        $this->_setQueryHeader();

        $this->sql .= " WHERE
                            po.idescola = '".$_SESSION["escola_idescola"]."' AND
                            ma.ativo='S'";

        /*
        if(!$_GET["q"]["1|ma.idsindicato"] && $_SESSION['adm_gestor_instituicao'] != 'S'){
              $this->sql .= ' and ma.idsindicato in ('.$_SESSION['adm_sindicatos'].')';
        }
        */



        if (is_array($_GET["q"])) {
            foreach($_GET["q"] as $campo => $valor) {

                $campo = explode("|",$campo);
                $valor = str_replace("'","",$valor);

                if(($valor || $valor === "0") && $valor <> "todos") {

                    if($campo[0] == 1) {
                        $this->sql .= " and ".$campo[1]." = '".$valor."' ";
                    } elseif($campo[0] == 2)  {
                        $this->sql .= " and ".$campo[1]." like '%".urldecode($valor)."%' ";
                    } elseif($campo[0] == 'de_ate') {
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

        if($_GET["de"]) {
            $this->sql .= " and (ma.data_registro >= '".formataData($_GET["de"],'en',0)." 00:00:00') ";
        }

        if($_GET["ate"]) {
            $this->sql .= " and (ma.data_registro <= '".formataData($_GET["ate"],'en',0)." 23:59:59') ";
        }

        if($_GET["de_datamatricula"]) {
            $this->sql .= " and (ma.data_matricula >= '".formataData($_GET["de_datamatricula"],'en',0)." 00:00:00') ";
        }

        if($_GET["ate_datamatricula"]) {
            $this->sql .= " and (ma.data_matricula <= '".formataData($_GET["ate_datamatricula"],'en',0)." 23:59:59') ";
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


        //echo $this->sql;

        $this->setContent(
            $this->set('ordem_campo', 'ma.idmatricula')
                ->set('ordem', 'desc')
                ->set('groupby', 'ma.idmatricula')
                ->retornarLinhas()
        );

        //echo $this->sql;

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
                        INNER JOIN mantenedoras mant ON (ma.idmantenedora=mant.idmantenedora)
                        INNER JOIN cursos cu ON (ma.idcurso=cu.idcurso)
                        INNER JOIN ofertas o ON (ma.idoferta=o.idoferta)
                        INNER JOIN ofertas_turmas tu ON (ma.idturma=tu.idturma)
                        INNER JOIN escolas po ON (ma.idescola=po.idescola)
                        INNER JOIN pessoas pe ON (ma.idpessoa=pe.idpessoa)
                        INNER JOIN vendedores ve ON (ma.idvendedor=ve.idvendedor)
                        INNER JOIN matriculas_workflow mw on (ma.idsituacao = mw.idsituacao)
                        LEFT OUTER JOIN cidades cid on (pe.idcidade = cid.idcidade)
                        LEFT OUTER JOIN estados est on (pe.idestado = est.idestado)
QUERY;
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


            if($_GET["de_comissao"] && $_GET["de_comissao"]){
                echo '<tr>';
                echo '<td><strong>Comissão de:</strong></td>';
                echo '<td>'.formataData($_GET["de_comissao"],'br',0).'</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>Comissão até:</strong></td>';
                echo '<td>'.formataData($_GET["ate_comissao"],'br',0).'</td>';
                echo '</tr>';
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
        $query = bl_query($this->sql);
        $this->retorno = array();
        while($row = mysql_fetch_assoc($query)){
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }
}