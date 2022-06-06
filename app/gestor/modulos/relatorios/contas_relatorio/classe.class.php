<?php
class Relatorio extends Core {

  function gerarRelatorio(){

    /*if($_GET['filtro_data_vencimento'] == 'PER' && (!$_GET["de_data_vencimento"] || !$_GET["ate_data_vencimento"]) ){
        unset($_GET['filtro_data_vencimento']);
    }*/

    if($_GET['filtro_data_pagamento'] == 'PER' && (!$_GET["de_data_pagamento"] || !$_GET["ate_data_pagamento"]) ){
        unset($_GET['filtro_data_pagamento']);
    }

    if ($_GET['filtro_data_vencimento'] == 'PER' && (!$_GET["de_data_vencimento"] || !$_GET["ate_data_vencimento"])) {
        $retorno['erro'] = true;
        $retorno['erros'][] = 'datas_obrigatorias';
        return $retorno;
    }
    if (dataDiferenca(formataData($_GET["de_data_vencimento"], 'en', 0), formataData($_GET["ate_data_vencimento"], 'en', 0), 'D') > 365) {
        $retorno['erro'] = true;
        $retorno['erros'][] = 'intervalo_maior_um_ano';
        return $retorno;
    }

    #VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO
    /*if (
        ($_GET['filtro_data_vencimento'] == 'PER' && (!$_GET["de_data_vencimento"] || !$_GET["ate_data_vencimento"]) )
        ||
        ($_GET['filtro_data_pagamento'] == 'PER' && (!$_GET["de_data_pagamento"] || !$_GET["ate_data_pagamento"]) )
        ) {
        $retorno['erro'] = true;
        $retorno['erros'][] = 'datas_obrigatorias';
        return $retorno;
    }
    if (
        (dataDiferenca(formataData($_GET["de_data_vencimento"], 'en', 0), formataData($_GET["ate_data_vencimento"], 'en', 0), 'D') > 365)
        ||
        (dataDiferenca(formataData($_GET["de_data_pagamento"], 'en', 0), formataData($_GET["ate_data_pagamento"], 'en', 0), 'D') > 365)
        ) {
        $retorno['erro'] = true;
        $retorno['erros'][] = 'intervalo_maior_um_ano';
        return $retorno;
    }*/
    #VERIFICAÇÃO DO INTERVALO DE DATAS MENOR QUE UM ANO - FIM

    $this->sql = "SELECT
                        ".$this->campos."
                      FROM
                        contas c
                        INNER JOIN contas_workflow cw ON (c.idsituacao = cw.idsituacao)
                        LEFT OUTER JOIN escolas po ON (po.idescola = c.idescola)
                        LEFT OUTER JOIN fornecedores f ON (c.idfornecedor = f.idfornecedor)
                        LEFT OUTER JOIN pessoas p ON (c.idpessoa = p.idpessoa)
                        LEFT OUTER JOIN matriculas m ON (c.idmatricula = m.idmatricula)
                        LEFT OUTER JOIN pessoas mp ON (m.idpessoa = mp.idpessoa)
                        LEFT OUTER JOIN vendedores v ON (m.idvendedor = v.idvendedor)
                        LEFT OUTER JOIN empresas e ON (e.idempresa = m.idempresa)
                      WHERE
                        c.ativo = 'S' AND
                        (
                            (c.idmatricula IS NOT NULL AND m.ativo = 'S') OR
                            c.idmatricula IS NULL
                        )";


    if ($_GET["tipo"] == 'despesa') {
        $this->sql .= " AND c.tipo = 'despesa' ";
    } elseif ($_GET["tipo"] == 'receita') {
        $this->sql .= " AND c.tipo = 'receita' ";
    }

    if($_SESSION['adm_gestor_sindicato'] != 'S'){
        $this->sql .= ' AND c.idsindicato in ('.$_SESSION['adm_sindicatos'].')';
    }

    if(is_array($_GET["q"])) {
        foreach($_GET["q"] as $campo => $valor) {
            $campo = explode("|",$campo);
            $valor = str_replace("'","",$valor);
            if(($valor || $valor === "0") && $valor <> "todos") {
                if($campo[0] == 1) {
                    $this->sql .= " AND ".$campo[1]." = '".$valor."' ";
                } elseif($campo[0] == 2)  {
                    $this->sql .= " AND ".$campo[1]." like '%".urldecode($valor)."%' ";
                }
            }
      }
    }

    if($_GET["filtro_data_vencimento"] == 'HOJ') {
        $this->sql .= " AND c.data_vencimento = '".date("Y-m-d")."'";
    } elseif($_GET["filtro_data_vencimento"] == 'ONT') {
        $this->sql .= " AND date_format(c.data_vencimento,'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
    } elseif($_GET["filtro_data_vencimento"] == 'SET') {
        $this->sql .= " AND date_format(c.data_vencimento,'%Y%m%d') <= '".date("Ymd")."'
                      AND date_format(c.data_vencimento,'%Y%m%d') >= '".date("Ymd", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'";
    } elseif($_GET["filtro_data_vencimento"] == 'QUI') {
        $this->sql .= " AND date_format(c.data_vencimento,'%Y-%m-%d') <= '".date("Y-m-d")."'
                      AND date_format(c.data_vencimento,'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
    } elseif($_GET["filtro_data_vencimento"] == 'MAT') {
        $this->sql .= " AND date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m")."'";
    } elseif($_GET["filtro_data_vencimento"] == 'MPR') {
        $this->sql .= " AND date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
    } elseif($_GET["filtro_data_vencimento"] == 'MAN') {
        $this->sql .= " AND date_format(c.data_vencimento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
    } elseif($_GET["filtro_data_vencimento"] == 'PER') {
        if($_GET["de_data_vencimento"]) {
            $this->sql .= " AND date_format(c.data_vencimento,'%Y-%m-%d') >= '".formataData($_GET["de_data_vencimento"],'en',0)."'";
        }
        if($_GET["ate_data_vencimento"]) {
            $this->sql .= " AND date_format(c.data_vencimento,'%Y-%m-%d') <= '".formataData($_GET["ate_data_vencimento"],'en',0)."'";
        }
    }

    if ($_GET["filtro_data_pagamento"] == 'HOJ') {
        $this->sql .= " AND c.data_pagamento = '".date("Y-m-d")."'";
    } elseif ($_GET["filtro_data_pagamento"] == 'ONT') {
        $this->sql .= " AND date_format(c.data_pagamento,'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
    } elseif ($_GET["filtro_data_pagamento"] == 'SET') {
        $this->sql .= " AND date_format(c.data_pagamento,'%Y%m%d') <= '".date("Ymd")."'
                      AND date_format(c.data_pagamento,'%Y%m%d') >= '".date("Ymd", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'";
    } elseif ($_GET["filtro_data_pagamento"] == 'QUI') {
        $this->sql .= " AND date_format(c.data_pagamento,'%Y-%m-%d') <= '".date("Y-m-d")."'
                      AND date_format(c.data_pagamento,'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
    } elseif ($_GET["filtro_data_pagamento"] == 'MAT') {
        $this->sql .= " AND date_format(c.data_pagamento,'%Y-%m') = '".date("Y-m")."'";
    } elseif ($_GET["filtro_data_pagamento"] == 'MPR') {
        $this->sql .= " AND date_format(c.data_pagamento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
    } elseif ($_GET["filtro_data_pagamento"] == 'MAN') {
        $this->sql .= " AND date_format(c.data_pagamento,'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
    } elseif ($_GET["filtro_data_pagamento"] == 'PER') {
        if($_GET["de_data_pagamento"]) {
           $this->sql .= " AND date_format(c.data_pagamento,'%Y-%m-%d') >= '".formataData($_GET["de_data_pagamento"],'en',0)."'";
        }
        if($_GET["ate_data_pagamento"]) {
           $this->sql .= " AND date_format(c.data_pagamento,'%Y-%m-%d') <= '".formataData($_GET["ate_data_pagamento"],'en',0)."'";
        }
    }

    if ($_GET['idsituacao']) {
        $this->sql .= ' AND c.idsituacao IN (' . implode(', ', $_GET['idsituacao']) . ')';
    }

    if ($_GET['idsindicato']) {
        $this->sql .= ' AND c.idsindicato IN (' . implode(', ', $_GET['idsindicato']) . ')';
    }

    /*if($_GET["idgrupo"]) {
        $idvendedor = array();
        $sql = "SELECT idvendedor FROM grupos_vendedores_vendedores WHERE idgrupo = ".$_GET["idgrupo"];
        $query = $this->executaSql($sql);
        while($vendedor = mysql_fetch_assoc($query)) {
            $idvendedor[] = $vendedor['idvendedor'];
        }

        if(count($idvendedor) > 0) {
            $this->sql .= " AND m.idvendedor in (".implode(", ", $idvendedor).")";
        }
    }*/

    if($_GET["idcentro_custo"]) {
        $this->sql .= " AND EXISTS (SELECT idconta_centro_custo FROM contas_centros_custos ccc WHERE ccc.idconta = c.idconta AND ccc.idcentro_custo = ".$_GET["idcentro_custo"]." AND ccc.ativo = 'S')";
    }

    $this->groupby = "c.idconta";
    $linhas = $this->retornarLinhas();

    foreach($linhas as $ind => $valor) {

        if ($valor["idmantenedora"]) {
            $sql = "SELECT nome_fantasia FROM mantenedoras WHERE idmantenedora = ".$valor["idmantenedora"];
            $mantenedora = $this->retornarLinha($sql);
            $linhas[$ind]["mantenedora"] = $mantenedora['nome_fantasia'];
        }

        if ($valor["idsindicato"]) {
            $sql = "SELECT nome_abreviado FROM sindicatos WHERE idsindicato = ".$valor["idsindicato"];
            $sindicato = $this->retornarLinha($sql);
            $linhas[$ind]["sindicato"] = $sindicato['nome_abreviado'];
        }

        $linhas[$ind]["codigo"] = '--';
        $linhas[$ind]["nome"] = '--';
        $linhas[$ind]["cpf_cnpj"] = '--';
        if ($valor["idmatricula"]) {

            $linhas[$ind]["codigo"] = $valor['idmatricula'];
            $linhas[$ind]["nome"] = $valor['aluno'];
            $linhas[$ind]["cpf_cnpj"] = formatar($valor['documento_aluno'], $valor['documento_tipo_aluno']);
            $linhas[$ind]["telefone"] = $valor['telefone_aluno'];
            $linhas[$ind]["email"] = $valor['email_aluno'];

        } elseif($valor["idfornecedor"]) {

            $linhas[$ind]["codigo"] = $valor['idfornecedor'];
            $linhas[$ind]["nome"] = $valor['fornecedor'];
            $linhas[$ind]["cpf_cnpj"] = formatar($valor['documento_fornecedor'], $valor['documento_tipo_fornecedor']);
            $linhas[$ind]["telefone"] = $valor['telefone_fornecedor'];
            $linhas[$ind]["email"] = $valor['email_fornecedor'];

        } elseif($valor["idpessoa"]) {

            $linhas[$ind]["codigo"] = $valor['idpessoa'];
            $linhas[$ind]["nome"] = $valor['pessoa'];
            $linhas[$ind]["cpf_cnpj"] = formatar($valor['documento_pessoa'], $valor['documento_tipo_pessoa']);
            $linhas[$ind]["telefone"] = $valor['telefone_pessoa'];
            $linhas[$ind]["email"] = $valor['email_pessoa'];

        }

        $linhas[$ind]["bandeira_cartao"] = '--';
        if ($valor["idbandeira"]) {

            $sql = "SELECT nome FROM bandeiras_cartoes WHERE idbandeira = ".$valor["idbandeira"];
            $bandeira = $this->retornarLinha($sql);
            $linhas[$ind]["bandeira_cartao"] = $bandeira['nome'];

        }

        $linhas[$ind]["produto"] = '--';
        if ($valor["idproduto"]) {

            $sql = "SELECT nome FROM produtos WHERE idproduto = ".$valor["idproduto"];
            $produto = $this->retornarLinha($sql);
            $linhas[$ind]["produto"] = $produto['nome'];

        }

        $linhas[$ind]["curso"] = '--';
        if($valor["idcurso"]) {

            $sql = "SELECT nome FROM cursos WHERE idcurso = ".$valor["idcurso"];
            $curso = $this->retornarLinha($sql);
            $linhas[$ind]["curso"] = $curso['nome'];

        }

        $linhas[$ind]["conta_corrente"] = '--';
        $linhas[$ind]["agencia"] = '--';
        $linhas[$ind]["banco"] = '--';
        if ($valor["idconta_corrente"]) {

            $sql = "SELECT
                      cc.nome AS conta_corrente,
                      cc.agencia,
                      cc.conta,
                      b.nome AS banco
                    FROM
                      contas_correntes cc
                      INNER JOIN bancos b ON (cc.idbanco = b.idbanco)
                    WHERE
                      cc.idconta_corrente = ".$valor["idconta_corrente"];
            $contaCorrente = $this->retornarLinha($sql);

            $linhas[$ind]["conta_corrente"] = $contaCorrente['conta'];
            $linhas[$ind]["agencia"] = $contaCorrente['agencia'];
            $linhas[$ind]["banco"] = $contaCorrente['banco'];

        } else {

            $linhas[$ind]["conta_corrente"] = '--';
            $linhas[$ind]["agencia"] = '--';
            $linhas[$ind]["banco"] = '--';
            if($valor["idbanco"]) {
                $sql = "SELECT nome FROM bancos WHERE idbanco = ".$valor["idbanco"];
                $banco = $this->retornarLinha($sql);
                $linhas[$ind]["banco"] = $banco['nome'];
            }
            if($valor["cc_cheque"]){ $linhas[$ind]["conta_corrente"] = $valor["cc_cheque"]; }
            if($valor["agencia_cheque"]){ $linhas[$ind]["agencia"] = $valor["agencia_cheque"]; }

        }

        $linhas[$ind]["devolvido_motivo_1"] = '--';
        if ($valor["id1_cheque_alinea"]) {

            $sql = "SELECT nome FROM cheques_alineas WHERE idcheque_alinea = ".$valor["id1_cheque_alinea"];
            $alinea = $this->retornarLinha($sql);
            $linhas[$ind]["devolvido_motivo_1"] = $alinea['nome'];

        }

        $linhas[$ind]["devolvido_motivo_2"] = '--';
        if ($valor["id2_cheque_alinea"]) {
            $sql = "SELECT nome FROM cheques_alineas WHERE idcheque_alinea = ".$valor["id2_cheque_alinea"];
            $alinea = $this->retornarLinha($sql);
            $linhas[$ind]["devolvido_motivo_2"] = $alinea['nome'];
        }

        $linhas[$ind]["devolvido_motivo_3"] = '--';
        if ($valor["id3_cheque_alinea"]) {

            $sql = "SELECT nome FROM cheques_alineas WHERE idcheque_alinea = ".$valor["id3_cheque_alinea"];
            $alinea = $this->retornarLinha($sql);
            $linhas[$ind]["devolvido_motivo_3"] = $alinea['nome'];

        }

        $this->sql = "SELECT
                            cc.nome,
                            ccc.idcentro_custo,
                            ccc.porcentagem,
                            ccc.valor       
                        FROM
                            contas_centros_custos ccc
                            INNER JOIN centros_custos cc ON (ccc.idcentro_custo = cc.idcentro_custo)
                        WHERE
                            ccc.idconta = ".$valor["idconta"]." AND
                            ccc.ativo = 'S'";
        $this->ordem = 'asc';
        $this->limite = -1;
        $this->ordem_campo = 'cc.nome';
        $centrosCustos = $this->retornarLinhas();
        $linhas[$ind]["centro_custo"] = '--';
        $linhas[$ind]["centros_custos"] = array();
        if(count($centrosCustos) > 0) {
            $nomesCentrosCustos = array();
            foreach($centrosCustos as $centroCusto) {
                $linhas[$ind]["centros_custos"][$centroCusto['idcentro_custo']]['porcentagem'] = $centroCusto['porcentagem'];
                $linhas[$ind]["centros_custos"][$centroCusto['idcentro_custo']]['valor'] = $centroCusto['valor'];
                $nomesCentrosCustos[] = $centroCusto['nome'];
            }
            $linhas[$ind]["centro_custo"] = implode(', ',$nomesCentrosCustos);
        }

        $linhas[$ind]["motivo_cancelamento"] = '--';
        if ($valor["idmotivo"]) {

            $sql = "SELECT nome FROM motivos_cancelamento_conta WHERE idmotivo = ".$valor["idmotivo"];
            $motivoCancelamento = $this->retornarLinha($sql);
            $linhas[$ind]["motivo_cancelamento"] = $motivoCancelamento['nome'];

        }

        $linhas[$ind]["categoria"] = '--';
        if ($valor["idcategoria"]) {
            $sql = "SELECT nome FROM categorias WHERE idcategoria = ".$valor["idcategoria"];
            $categoria = $this->retornarLinha($sql);
            $linhas[$ind]["categoria"] = $categoria['nome'];
        }

        $linhas[$ind]["subcategoria"] = '--';
        if ($valor["idsubcategoria"]) {
            $sql = "SELECT nome FROM categorias_subcategorias WHERE idsubcategoria = ".$valor["idsubcategoria"];
            $subcategoria = $this->retornarLinha($sql);
            $linhas[$ind]["subcategoria"] = $subcategoria['nome'];
        }

    }

    return $linhas;
  }

    function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem") {

        if($_GET['filtro_data_vencimento'] == 'PER' && (!$_GET["de_data_vencimento"] || !$_GET["ate_data_vencimento"]) ){
            unset($_GET['filtro_data_vencimento']);
        }

        if($_GET['filtro_data_pagamento'] == 'PER' && (!$_GET["de_data_pagamento"] || !$_GET["ate_data_pagamento"]) ){
            unset($_GET['filtro_data_pagamento']);
        }

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
            if($campo["nome"][0] == "q"){
              $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
              $campoAux = $_GET["q"][$campoAux];
              if($campo["sql_filtro"] && $campoAux) {
                if($campo["sql_filtro"] == "array") {
                  $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$campoAux];
                } else {
                  $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
                  $linha = $this->retornarLinha($sql);
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
                  $linha = $this->retornarLinha($sql);
                  $_GET[$campo["nome"]][$ind] = $linha[$campo["sql_filtro_label"]];
                }
              }

              $campoAux = implode($_GET[$campo["nome"]], ", ");
            } elseif($_GET[$campo["nome"]] && $campo["array"]){
              $campoAux = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$_GET[$campo["nome"]]];
            } elseif($_GET[$campo["nome"]] && $campo["sql_filtro"]){
              $sql = str_replace("%",$_GET[$campo["nome"]],$campo["sql_filtro"]);
              $linha = $this->retornarLinha($sql);
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

    }

    function retornarEventosFinanceiros() {
        $eventosFinanceiros = array();

        $sql = "SELECT * FROM eventos_financeiros WHERE ativo = 'S' ORDER BY nome ASC";
        $query = $this->executaSql($sql);
        while($eventoFinanceiro = mysql_fetch_assoc($query)) {
            $eventosFinanceiros[$eventoFinanceiro['idevento']] = $eventoFinanceiro;
        }

        return $eventosFinanceiros;
    }

    function retornarCentrosDeCustos() {
        $centrosDeCustos = array();

        $sql = "SELECT * FROM centros_custos WHERE ativo = 'S' ORDER BY nome ASC";
        $query = $this->executaSql($sql);
        while($centroDeCusto = mysql_fetch_assoc($query)) {
            $centrosDeCustos[$centroDeCusto['idcentro_custo']] = $centroDeCusto;
        }

        return $centrosDeCustos;
    }

    public function retornarCentrosDeCustosPorConta($idConta)
    {
        $centrosDeCustos = array();

        $sql = "SELECT occ.*, cc.nome
                FROM contas_centros_custos occ
                INNER JOIN centros_custos cc ON cc.idcentro_custo = occ.idcentro_custo
                WHERE occ.ativo = 'S'
                AND occ.idconta = " . $idConta . " ORDER BY nome ASC";
        $query = $this->executaSql($sql);
        while ($centroDeCusto = mysql_fetch_assoc($query)) {
            $centrosDeCustos[] = $centroDeCusto;
        }

        return $centrosDeCustos;
    }

}

?>