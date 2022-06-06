<?php

class Relatorio extends Core {

    function gerarRelatorio()
    {

        $this->sql = "SELECT
                        {$this->campos}
                    FROM
                        matriculas ma
                        INNER JOIN pessoas pe ON (ma.idpessoa = pe.idpessoa)
                        LEFT JOIN vendedores ve ON (ma.idvendedor = ve.idvendedor)
                    WHERE
                        ma.ativo = 'S' ";

        if(is_array($_GET["q"])) {
            foreach($_GET["q"] as $campo => $valor) {
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
                    }  elseif($campo[0] == 'de_ate_matricula') {
                        if($valor == 'HOJ') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
                        } elseif($valor == 'ONT') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
                        } else if($valor == 'SET') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
                        } elseif($valor == 'QUI') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          AND DATE_FORMAT(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
                        } else if($valor == 'MAT') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
                        } else if($valor == 'MPR') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
                        } else if($valor == 'MAN') {
                            $this->sql .= " AND DATE_FORMAT(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
                        } else if ($valor == 'PER') {
                            if (!$_GET["de_matricula"] || !$_GET["ate_matricula"]) {
                                    $retorno['erro'] = true;
                                    $retorno['erros'][] = 'datas_obrigatorias';
                                    return $retorno;
                            }
                            if (dataDiferenca(formataData($_GET["de_matricula"], 'en', 0), formataData($_GET["ate_matricula"], 'en', 0), 'D') > 31) {
                                    $retorno['erro'] = true;
                                    $retorno['erros'][] = 'intervalo_maior_um_mes';
                                    return $retorno;
                            }

                            $this->sql .= " AND (ma.data_matricula >= '".formataData($_GET["de_matricula"],'en',0)." 00:00:00')
                                            AND (ma.data_matricula <= '".formataData($_GET["ate_matricula"],'en',0)." 23:59:59') ";

                        }
                    }
                }
            }
        }
        //echo $this->sql;
        $this->groupby = "ma.idmatricula";
        $matriculas = $this->retornarLinhas();
        $linhas = array();
        foreach ($matriculas as $matricula) {

            $matricula['documentos'] = $this->RetornarDocumentos($matricula['idmatricula']);
            $matricula['curriculo'] = $this->RetornarCurriculo($matricula['idoferta'], $matricula['idcurso'], $matricula['idescola']);
            $matricula['disciplinas'] = $this->RetornarDisciplinas($matricula['idmatricula'], $matricula['curriculo']['media']);
            $matricula['contas'] = $this->RetornarContas($matricula['idmatricula']);

            $linhas[] = $matricula;
        }

        return $linhas;

    }

    public function RetornarDocumentos($idmatricula)
    {
        $this->sql = "SELECT
                        md.*,
                        td.nome as tipo
                      FROM
                        matriculas_documentos md
                        INNER JOIN tipos_documentos td ON (md.idtipo = td.idtipo)
                      where
                        md.idmatricula = " . $idmatricula . " and
                        md.ativo = 'S'";

        $this->ordem = "asc";
        $this->ordem_campo = "data_cad";
        $this->limite = -1;
        return $this->retornarLinhas();
    }

    public function RetornarDisciplinas($idmatricula, $media_curriculo)
    {
        $disciplinas = array();
        $this->sql = "SELECT
                        d.*,
                        cb.nome as bloco,
                        cbd.idbloco_disciplina,
                        cbd.idformula,
                        cbd.ignorar_historico,
                        cbd.contabilizar_media,
                        cbd.exibir_aptidao,
                        oca.idava
                    FROM
                        disciplinas d
                        INNER JOIN curriculos_blocos_disciplinas cbd on (d.iddisciplina = cbd.iddisciplina and cbd.ativo = 'S')
                        INNER JOIN curriculos_blocos cb on (cbd.idbloco = cb.idbloco and cb.ativo = 'S')
                        INNER JOIN ofertas_cursos_escolas ocp on (cb.idcurriculo = ocp.idcurriculo)
                        INNER JOIN matriculas m on (ocp.idescola = m.idescola and ocp.idoferta = m.idoferta and ocp.idcurso = m.idcurso)
                        LEFT OUTER JOIN ofertas_curriculos_avas oca on (oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = m.idoferta and oca.ativo = 'S')
                    WHERE
                        m.idmatricula = " . $idmatricula. " GROUP BY d.iddisciplina ";
        $this->ordem = "asc";
        $this->ordem_campo = "cb.ordem, cbd.ordem, d.nome";
        $this->limite = -1;
        $disciplinas = $this->retornarLinhas();

        foreach ($disciplinas as $ind => $disciplina) {
            $disciplinas[$ind]["notas"] = $this->retornarNotasDisciplina($idmatricula, $disciplina["iddisciplina"]);
            $disciplinas[$ind]["situacao"] = $this->retornarSituacaoDisciplina($idmatricula, $disciplina, $media_curriculo);
        }

        return $disciplinas;
    }

    public function retornarNotasDisciplina($idmatricula, $iddisciplina)
    {
        $this->sql = "SELECT
                        mn.*,
                        mnt.nome as tipo,
                        mnt.sigla as tipo_sigla,
                        mp.nome as modelo,
                        pp.data_realizacao,
                        pp.hora_realizacao_de,
                        pp.hora_realizacao_ate,
                        IF(mn.aproveitamento_estudo = 'S', 'AE', mn.nota) as nota
                    FROM
                        matriculas_notas mn
                        LEFT JOIN matriculas_notas_tipos mnt on mn.idtipo = mnt.idtipo
                        LEFT JOIN provas_solicitadas ps on mn.id_solicitacao_prova = ps.id_solicitacao_prova
                        LEFT JOIN provas_presenciais pp on ps.id_prova_presencial = pp.id_prova_presencial
                        LEFT JOIN modelos_prova mp on mn.idmodelo = mp.idmodelo
                    WHERE
                        mn.idmatricula = " . intval($idmatricula) . " and
                        mn.iddisciplina = " . intval($iddisciplina) . " and
                        mn.ativo = 'S'";

        $this->ordem = "asc";
        $this->ordem_campo = " mn.idmatricula_nota ";
        $this->limite = -1;
        $notas = $this->retornarLinhas();
        return $notas;
    }

    private function retornarSituacaoDisciplina($idmatricula, $disciplina, $media)
    {
        $boletim = new Boletim(new Avaliacoes);
        $boletim['idmatricula'] = (int)$idmatricula;
        $boletim->buscarDadosDaMatriculaHistorico();
        $formula = new Formulas_Notas;

        $aproveitamento_estudo = boletim::getAproveitamentoEstudos($idmatricula, $disciplina['iddisciplina']);

        if (!$aproveitamento_estudo['idmatricula_nota']) {

            $notas_disciplina = boletim::getProvasTipos($idmatricula, $disciplina['iddisciplina']);
            foreach ($notas_disciplina as $nota) {
                $notas[$nota['idtipo']] = number_format($nota['nota'], 2, ',', '.');
                #$notas[$tipo_class[$tipo_peso[$nota['tipo_avaliacao']]]] = $disciplina[$tipo_peso[$nota['tipo_avaliacao']]];
            }
            $formResult = $formula->set('id', $disciplina['idformula'])->set('post', $notas)->validarFormula($media); #print_r2($formResult, true);
        }

        if ($aproveitamento_estudo['aproveitamento_estudo'] == 'S') {
            $formResult['situacao'] = 'AE - Aproveitamento de Estudos';
            $formResult['lancar_nota'] = false;
        } elseif ($disciplina['ignorar_historico'] == 'S') {
            $formResult['situacao'] = 'Ignorada no histórico';
            $formResult['lancar_nota'] = true;
        } elseif ($disciplina['contabilizar_media'] == 'N') {
            $formResult['situacao'] = 'Não contabilizada no histórico';
            $formResult['lancar_nota'] = true;
        } else {
            if ($formResult['valor'] >= $media) {
                if ($disciplina['exibir_aptidao'] == 'N')
                    $formResult['situacao'] = 'Aprovado';
                else {
                    if ($formResult['valor'] == '10.00' || $formResult['valor'] == '10')
                        $formResult['situacao'] = 'Apto';
                    else {
                        $formResult['situacao'] = 'Inapto';
                        $formResult['lancar_nota'] = true;
                    }
                }
            } else {
                if ($disciplina['exibir_aptidao'] == 'N') {
                    $formResult['situacao'] = 'Reprovado';
                    $formResult['lancar_nota'] = true;
                } else {
                    $formResult['situacao'] = 'Inapto';
                    $formResult['lancar_nota'] = true;
                }
            }
            // Se nao tiver nota
            if (!count($notas_disciplina)) {
                $formResult['situacao'] = 'Sem nota';
                $formResult['lancar_nota'] = true;
            }
        }

        return $formResult;
    }

    public function RetornarCurriculo($idoferta, $idcurso, $idescola)
    {
        $this->sql = "select
                        c.media
                    from
                        curriculos c
                        inner join ofertas_cursos_escolas ocp on (c.idcurriculo = ocp.idcurriculo)
                    where
                        ocp.idoferta = '" . $idoferta . "' and
                        ocp.idcurso = '" . $idcurso . "' and
                        ocp.idescola = '" . $idescola . "' and
                        ocp.ativo = 'S'";
        return $this->retornarLinha($this->sql);
    }

    public function RetornarContas($idmatricula)
    {

        $eventoFinanceiroMensalidade = $this->retornarEventoMensalidade();
        $contasArray = array();

        $this->sql = "SELECT
                        c.*,
                        ef.nome as evento,
                        bc.nome as bandeira_cartao,
                        b.nome as banco,
                        cw.nome as situacao,
                        cw.cancelada as situacao_cancelada,
                        cw.renegociada as situacao_renegociada,
                        cw.transferida as situacao_transferida,
                        cw.pago as situacao_paga,
                        cw.pagseguro AS situacao_pagseguro,
                        cw.cor_nome,
                        cw.cor_bg
                    FROM
                        contas c
                        INNER JOIN contas_workflow cw on (c.idsituacao = cw.idsituacao)
                        INNER JOIN eventos_financeiros ef on (c.idevento = ef.idevento)
                        left outer join bandeiras_cartoes bc on (c.idbandeira = bc.idbandeira)
                        left outer join bancos b on (c.idbanco = b.idbanco)
                    WHERE
                        c.idmatricula = " . $idmatricula . " and
                        c.ativo = 'S'";

        $this->sql2 = "SELECT
                            c.*,
                            ef.nome as evento,
                            bc.nome as bandeira_cartao,
                            b.nome as banco,
                            cw.nome as situacao,
                            cw.cancelada as situacao_cancelada,
                            cw.renegociada as situacao_renegociada,
                            cw.transferida as situacao_transferida,
                            cw.pago as situacao_paga,
                            cw.pagseguro AS situacao_pagseguro,
                            cw.cor_nome,
                            cw.cor_bg
                        FROM
                            contas c
                            INNER JOIN contas_workflow cw on (c.idsituacao = cw.idsituacao)
                            INNER JOIN eventos_financeiros ef on (c.idevento = ef.idevento)
                            INNER JOIN pagamentos_compartilhados_matriculas pcm on (c.idpagamento_compartilhado = pcm.idpagamento and pcm.ativo = 'S')
                            left outer join bandeiras_cartoes bc on (c.idbandeira = bc.idbandeira)
                            left outer join bancos b on (c.idbanco = b.idbanco)
                        WHERE
                            pcm.idmatricula = " . $idmatricula . " and
                            c.ativo = 'S'";

        $this->sql = $this->sql." UNION ".$this->sql2;

        $this->ordem = "asc";
        $this->ordem_campo = "data_vencimento";
        $this->limite = -1;
        $contas = $this->retornarLinhas();
        $this->matricula["total_mensalidades"] = 0;
        foreach ($contas as $conta) {
            $conta["valor_parcela"] = $conta["valor"];

            if($conta['idpagamento_compartilhado']) {
                $this->sql = "SELECT count(1) as total_contas_compartilhadas FROM contas WHERE idpagamento_compartilhado = " . $conta['idpagamento_compartilhado'] . " AND ativo = 'S'";
                $totalContasCompartilhadas = $this->retornarLinha($this->sql);
                $conta["total_contas_compartilhadas"] = $totalContasCompartilhadas['total_contas_compartilhadas'];

                $this->sql = "SELECT valor FROM pagamentos_compartilhados_matriculas WHERE idpagamento = " . $conta['idpagamento_compartilhado'] . " AND idmatricula = " . $this->id . " AND ativo = 'S'";
                $valorContasCompartilhadas = $this->retornarLinha($this->sql);
                $conta["valor_matricula"] = $valorContasCompartilhadas['valor'];

                $conta["valor_parcela"] = $conta["valor_matricula"] / $conta['total_contas_compartilhadas'];
            }

            if($conta['idconta_transferida']) {
                $this->sql = "SELECT idmatricula as matricula_transferida FROM contas WHERE idconta = " . $conta['idconta_transferida'];
                $contasTransferida = $this->retornarLinha($this->sql);
                $conta["matricula_transferida"] = $contasTransferida['matricula_transferida'];
            }

            if ($conta["idevento"] == $eventoFinanceiroMensalidade["idevento"]) {
                if ($conta['situacao_cancelada'] != 'S' && $conta['situacao_renegociada'] != 'S' && $conta['situacao_transferida'] != 'S') {
                    //if ($conta['idpagamento_compartilhado']) {
                        //$this->matricula["total_mensalidades"] += ($conta['valor_matricula'] / $conta['total_contas_compartilhadas']);
                    //} else
                        $this->matricula["total_mensalidades"] += $conta["valor_parcela"];
                }
            }

            $contasArray[$conta["idevento"]][] = $conta;
        }

        $this->matricula["total_mensalidades"] = number_format($this->matricula["total_mensalidades"], 2, '.', '');
        return $contasArray;
    }

    public function retornarEventoMensalidade()
    {
        $this->sql = "SELECT * FROM  eventos_financeiros where ativo = 'S' and mensalidade = 'S' order by idevento desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem")
    {

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
                            $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
                            $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAux]];
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
                    $_GET[$campo["nome"]] = $linha[$campo["sql_filtro_label"]];

                    $campoAux = $_GET[$campo["nome"]];
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

    function RetornarVendedoresSindicato($idsindicato)
    {
        $this->sql = "SELECT
                            ve.idvendedor,
                            ve.nome
                        FROM
                            vendedores ve
                          WHERE
                          (
                            (
                                SELECT
                                    vi.idvendedor
                                FROM
                                    vendedores_sindicatos vi,
                                    sindicatos i
                                WHERE
                                    vi.idvendedor = ve.idvendedor AND
                                    vi.ativo= 'S' AND
                                    vi.idsindicato = '{$idsindicato}'
                                LIMIT 1
                            ) IS NOT NULL OR
                            (
                                SELECT
                                    vi.idvendedor
                                FROM
                                    vendedores_sindicatos vi,
                                    sindicatos i
                                WHERE
                                    vi.idvendedor = ve.idvendedor AND
                                    vi.ativo='S'
                                LIMIT 1
                            ) IS NULL
                          ) AND
                            ve.ativo = 'S' AND
                            ve.ativo_login = 'S'
                        GROUP BY
                            ve.idvendedor
                        ORDER BY ve.nome";
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while($row = mysql_fetch_assoc($query)){
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }
}

?>