<?php
class Relatorio extends Core
{

    const CURRENT_TABLE = 'visitas_vendedores';

    /**
     * @var mixed content
     */
    private $_data = null;

    /**
     * @return mixed
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function _fetchData()
    {
        $this->set('sql', 'SELECT * FROM');
    }

    /**
     * @return object Relatorio
     *
     * @api
     */
    public function createConsult()
    {
        $query = "SELECT
          {$this->campos}
              FROM
                 ".self::CURRENT_TABLE." vv
                 INNER JOIN vendedores ve ON (vv.idvendedor=ve.idvendedor)
                 LEFT JOIN midias_visitas miv ON (vv.idmidia=miv.idmidia)
                 LEFT JOIN pessoas pe ON (vv.idpessoa=pe.idpessoa)
                 LEFT JOIN cursos cu ON (vvc.idcurso=cu.idcurso)
                 LEFT JOIN locais_visitas lov ON (vv.idlocal=lov.idlocal)
             WHERE vv.ativo='S'";

        return $this;
    }

    /**
     * @return mixed
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function getResult()
    {
        return $this->_data;
    }

    function gerarRelatorio(){

        /*if($_GET['q']['de_ate|tipo_data_filtro|vv.data_cad'] == 'PER' && (!$_GET["de"] || !$_GET["ate"]) ){
            unset($_GET['q']['de_ate|tipo_data_filtro|vv.data_cad']);
        }*/

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

        /* empreendimento */
        $this->sql = 'SELECT vv.*, vv.data_cad, ve.nome as vendedor
                            FROM visitas_vendedores vv
                                INNER JOIN vendedores ve
                                    ON (vv.idvendedor=ve.idvendedor)';

        $this->campos = '*';
        $this->ordem_campo = 'data_cad';
        $this->set('limite', -1);

        /*if ($idGrupo = $_GET['q']['1|grupo.idgrupo']) {
            $this->sql .= " INNER JOIN grupos_vendedores_vendedores grupo ON (grupo.idvendedor=ve.idvendedor)";
        }*/

        if ($idEstado = $_GET['q']['1|e.idestado']) {
            $this->sql .= " INNER JOIN estados AS e ON (e.idestado=vv.idestado)
                        AND e.idestado = ". $idEstado;
        }

        if ($idCidade = $_GET['q']['1|c.idcidade']) {
            $this->sql .= " INNER JOIN cidades AS c ON (c.idcidade=vv.idcidade)
                        AND (c.idcidade = ". $idCidade.")";
        }

        if ($idSindicato = $_GET['q']['1|vi.idsindicato']) {
      $this->sql .= ' INNER JOIN vendedores_sindicatos AS vi';
            $this->sql .= ' ON (vi.idvendedor = ve.idvendedor and vi.idsindicato = '.$idSindicato.' AND vi.ativo = "S")';
        }

    if(!$_GET['q']['1|vi.idsindicato'] && $_SESSION['adm_gestor_sindicato'] != 'S'){
      $this->sql .= ' INNER JOIN vendedores_sindicatos AS vi';
            $this->sql .= ' ON (vi.idvendedor = ve.idvendedor and vi.idsindicato in ('.$_SESSION['adm_sindicatos'].') AND vi.ativo = "S")';
    }

        if ($_GET['q']['1|cu.idcurso']) {
            $this->sql .= ' INNER JOIN visitas_vendedores_cursos AS cu';
            $this->sql .= ' ON (cu.idcurso = '.$_GET['q']['1|cu.idcurso'].')';
            $this->sql .= ' AND (cu.idvisita = vv.idvisita)';
        }

        $this->sql .= " WHERE vv.ativo='S'";

        if (is_array($_GET["q"])) {
            foreach ($_GET["q"] as $campo => $valor) {
                //explode = Retira, ou seja retira a "|" da variavel campo
                $campo = explode("|",$campo);
                $valor = str_replace("'","",$valor);
                // Listagem se o valor for diferente de Todos ele faz um filtro
                if(($valor || $valor === "0") && $valor <> "todos") {
                    // se campo[0] for = 1 é pq ele tem de ser um valor exato
                    if($campo[0] == 1) {
                    $this->sql .= " and ".$campo[1]." = '".$valor."' ";
                    // se campo[0] for = 2, faz o filtro pelo comando like
                    } elseif($campo[0] == 2)  {
                        $this->sql .= " and ".$campo[1]." like '%".urldecode($valor)."%' ";
                    } elseif($campo[0] == 'de_ate') {
                        if($valor == 'HOJ') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
                        } elseif ($valor == 'ONT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
                        } else if($valor == 'SET') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
                        } elseif ($valor == 'QUI') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
                        } else if($valor == 'MAT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
                        } else if($valor == 'MPR') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
                        } else if($valor == 'MAN') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
                        }
                    }
                }
            }
        }

        if($_GET["de"]) {
            $this->sql .= " and (vv.data_cad >= '".formataData($_GET["de"],'en',0)." 00:00:00') ";
        }

        if($_GET["ate"]) {
            $this->sql .= " and (vv.data_cad <= '".formataData($_GET["ate"],'en',0)." 23:59:59') ";
        }

        $this->campos = '*';
        $this->ordem_campo = 'data_cad';
        $this->set('limite', -1);

        $this->sql .= ' GROUP BY vv.idvisita ORDER BY vv.data_cad DESC';

        $resultado = $this->executaSql($this->sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            $retorno[] = $linha;
        }

        //return $this->retornarLinhas();
        return $retorno;

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

                      if($campo['sql_filtro']){
                        if($campo['sql_filtro'] == 'array'){
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
            echo '</table><br>';
        }


        function RetornarBlocosEmpreendimento($idempreendimento) {
        $this->sql = "SELECT bl.idbloco, bl.nome
                      FROM empreendimentos_blocos bl
                      INNER JOIN empreendimentos_etapas et ON (et.idetapa=bl.idetapa)
                      INNER JOIN empreendimentos em ON (et.idempreendimento=em.idempreendimento)
                      WHERE bl.ativo='S' AND bl.ativo_painel = 'S' AND em.idempreendimento = '".$idempreendimento."'";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while($row = mysql_fetch_assoc($query)){
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }

    function RetornarUnidadesBloco($idbloco) {
        $this->sql = "SELECT un.idunidade, un.nome
                      FROM empreendimentos_unidades un
                      INNER JOIN empreendimentos_blocos bl ON (bl.idbloco=un.idbloco)
                      INNER JOIN  empreendimentos_etapas et ON (et.idetapa=bl.idetapa)
                      INNER JOIN empreendimentos em ON (et.idempreendimento=em.idempreendimento)
                      WHERE bl.idbloco = ".$idbloco." AND un.ativo='S'
                      AND un.ativo_painel = 'S' ";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while($row = mysql_fetch_assoc($query)){
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }
}