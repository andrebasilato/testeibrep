<?php
class Relatorio extends Core
{

    function gerarRelatorio()
    {
        $this->sql = "SELECT 
                            {$this->campos}
                      FROM
                            matriculas ma
                                INNER JOIN                                 
                            matriculas_workflow mw ON (ma.idsituacao = mw.idsituacao)
                            	INNER JOIN
                            ofertas o ON (ma.idoferta = o.idoferta)
                                INNER JOIN
                        	ofertas_turmas tu on (ma.idturma = tu.idturma)
                        		INNER JOIN 
                            cursos cu ON (ma.idcurso = cu.idcurso)
                                INNER JOIN
                            escolas po ON (ma.idescola = po.idescola)
                                INNER JOIN
                            sindicatos i ON (ma.idsindicato = i.idsindicato)
                                LEFT JOIN                         
	                        cidades c on (c.idcidade = i.idcidade)
	                           INNER JOIN
                            mantenedoras m ON (i.idmantenedora = m.idmantenedora)
                               INNER JOIN 
                    	    pessoas a on a.idpessoa = ma.idpessoa
                        WHERE
							ma.ativo = 'S'
                            AND mw.fim = 'S'";
        
        $this->aplicarFiltrosBasicos();
        
        $this->ordem_campo = "a.nome";
        $this->ordem = "asc";
        $this->groupby = "ma.idmatricula";        
        
        $dados = $this->retornarLinhas();        
        $dadosRelatorio = $this->retornarDadosMatricula($dados);
        
        return $dadosRelatorio;
    }

    function GerarTabela($dados, $q = null, $idioma, $configuracao = "listagem")
    {        
        // Buscando os idiomas do formulario
        include ("idiomas/pt_br/index.php");
        echo '<table class="zebra-striped" id="sortTableExample">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Filtro</th>';
        echo '<th>Valor</th>';
        echo '</tr>';
        echo '</thead>';
        
        foreach ($this->config["formulario"] as $ind => $fieldset) {
            foreach ($fieldset["campos"] as $ind => $campo) {
                if ($campo["nome"]{0} == "q") {
                    $campoAux = str_replace(array(
                        "q[",
                        "]"
                    ), "", $campo["nome"]);
                    $campoAux = $_GET["q"][$campoAux];
                    
                    if ($campo["sql_filtro"]) {
                        if ($campo["sql_filtro"] == "array") {
                            $campoAuxNovo = str_replace(array(
                                "q[",
                                "]"
                            ), "", $campo["nome"]);
                            $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAuxNovo]];
                        } else {
                            $sql = str_replace("%", $campoAux, 
                                $campo["sql_filtro"]);
                            $seleciona = mysql_query($sql);
                            $linha = mysql_fetch_assoc($seleciona);
                            $campoAux = $linha[$campo["sql_filtro_label"]];
                        }
                    }
                } elseif (is_array($_GET[$campo["nome"]])) {
                    
                    if ($campo["array"]) {
                        foreach ($_GET[$campo["nome"]] as $ind => $val) {
                            $_GET[$campo["nome"]][$ind] = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$val];
                        }
                    } elseif ($campo["sql_filtro"]) {
                        foreach ($_GET[$campo["nome"]] as $ind => $val) {
                            $sql = str_replace("%", $val, $campo["sql_filtro"]);
                            $seleciona = mysql_query($sql);
                            $linha = mysql_fetch_assoc($seleciona);
                            $_GET[$campo["nome"]][$ind] = $linha[$campo["sql_filtro_label"]];
                        }
                    }
                    
                    $campoAux = implode($_GET[$campo["nome"]], ", ");
                } else {
                    $campoAux = $_GET[$campo["nome"]];
                }
                if ($campoAux != "") {
                    echo '<tr>';
                    echo '<td><strong>' . $idioma[$campo["nomeidioma"]] .
                         '</strong></td>';
                    echo '<td>' . $campoAux . '</td>';
                    echo '</tr>';
                }
            }
        }
        echo '</table><br>';
        
        echo '<table class="zebra-striped" id="sortTableExample" width="100%">';
        echo '<thead>';
        echo '<tr>';
        echo '<tr>';
        echo '<td> Nenhum informação foi encontrada.</td>';
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
    }

    function retornarDadosMatricula($dados)
    {   
        foreach ($dados as $key => $value) {
            $dadosMatricula['idsindicato'] = $value['idsindicato'];
            $dadosMatricula['sindicato'] = $value['sindicato'];
            $dadosMatricula['secretario'] = $value['secretario'];
            $dadosMatricula['secretario_portaria'] = $value['secretario_portaria'];
            $dadosMatricula['diretor'] = $value['diretor'];
            $dadosMatricula['diretor_portaria'] = $value['diretor_portaria'];
            $dadosMatricula['idcurso'] = $value['idcurso'];
            $dadosMatricula['idturma'] = $value['idturma'];
            $dadosMatricula['turma'] = $value['turma'];
            $dadosMatricula['curso'] = $value['curso'];
            $dadosMatricula['carga_horaria_total'] = $value['carga_horaria_total'];
            $dadosMatricula['idescola'] = $value['idescola'];
            $dadosMatricula['idoferta'] = $value['idoferta'];
            $dadosMatricula['oferta'] = $value['oferta'];
            $dadosMatricula['data_inicio_matricula'] = date_create($value['data_inicio_matricula']);
            $dadosMatricula['data_fim_matricula'] = date_create($value['data_fim_matricula']);
            $dadosMatricula['cidade'] = $value['cidade'];
            $dadosMatricula['mantenedora'] = $value['mantenedora'];
            $dadosMatricula['nre'] = $value['nre'];
            $dadosMatricula['alunos'][$value['idmatricula']] = array(
                "idmatricula" => $value['idmatricula'],
                "nome" => $value['nome'],
                "sexo" => $value['sexo']
            );
        }
        
        require_once ('../classes/matriculas.class.php');
        $matriculaObj = new Matriculas();
        
        // Retornar Currículo
        $matriculaArray['idoferta'] = $dadosMatricula['idoferta'];
        $matriculaArray['idcurso'] = $dadosMatricula['idcurso'];
        $matriculaArray['idescola'] = $dadosMatricula['idescola'];
        $matriculaObj->set('matricula', $matriculaArray);
        $matriculaCurriculo = $matriculaObj->RetornarCurriculo(); // Retorna os dados do currículo da matrícula
                                                                  
        // Carregar dados do aluno e notas
        foreach ($dadosMatricula['alunos'] as $ind => $var) {
            $dadosMatricula['media'] = (floatval($matriculaCurriculo['media']));
            $matriculaObj->set("id", $var['idmatricula']);
            $dadosMatricula["disciplinas"] = $this->RetornarDisciplinas($ind, 
                $matriculaCurriculo["curriculo"]['media']);
            
            foreach ($dadosMatricula["disciplinas"] as $key => $d) {
                // Retorna a situação da disciplina
                $disciplinaSituacao = $matriculaObj->retornarSituacaoDisciplina(
                    $var['idmatricula'], $d, $matriculaCurriculo['media']);
                $dadosMatricula['alunos'][$ind]['situacao'][$d['iddisciplina']] = $disciplinaSituacao;
            }
        }
        
        return $dadosMatricula;
    }

    function retornarMatriculas($dados)
    {
        $this->sql = "SELECT 
                        ma.idmatricula,
                    	a.nome,
                    	a.sexo
                    FROM
                        matriculas ma
                            INNER JOIN
                        ofertas o ON (ma.idoferta = o.idoferta)
                            INNER JOIN
                        ofertas_turmas tu ON (ma.idoferta = tu.idturma)
                            INNER JOIN
                        cursos cu ON (ma.idcurso = cu.idcurso)
                            INNER JOIN
                        escolas po ON (ma.idescola = po.idescola)
                            INNER JOIN
                        sindicatos i ON (ma.idsindicato = ma.idsindicato)
                    		INNER JOIN 
                    	pessoas a on a.idpessoa = ma.idpessoa 
                    WHERE
                        ma.ativo = 'S'
                            AND ma.curso_consluido = 'S'
                            and ma.idsindicato = {$dados['idsindicato']}
                            and ma.idescola = {$dados['idescola']}
                            and ma.idoferta = {$dados['idoferta']}
                            and ma.idcurso = {$dados['idcurso']}
                            and ma.idturma = {$dados['idturma']}";
        
        $this->sql .= " GROUP BY ma.idmatricula, a.nome, a.sexo ";
        $this->limite = - 1;
        $this->ordem_campo = "nome";
        $this->ordem = "asc";
        
        return $this->retornarLinhas();
    }

    public function retornarDisciplinas($idmatricula, $media_curriculo)
    {
        $this->sql = "SELECT
                            d.*,
                            cb.nome as bloco,
                            cbd.idbloco_disciplina,
                            cbd.idformula,
                            cbd.ignorar_historico,
                            cbd.contabilizar_media,
                            cbd.exibir_aptidao,
                            cbd.nota_conceito,
                            oca.idava
                        FROM
                            disciplinas d
                            INNER JOIN curriculos_blocos_disciplinas cbd on (d.iddisciplina = cbd.iddisciplina and cbd.ativo = 'S')
                            INNER JOIN curriculos_blocos cb on (cbd.idbloco = cb.idbloco and cb.ativo = 'S')
                            INNER JOIN ofertas_cursos_escolas ocp on (cb.idcurriculo = ocp.idcurriculo)
                            INNER JOIN matriculas m on (ocp.idescola = m.idescola and ocp.idoferta = m.idoferta and ocp.idcurso = m.idcurso)
                            LEFT OUTER JOIN ofertas_curriculos_avas oca on (oca.iddisciplina = cbd.iddisciplina and oca.idcurriculo = cb.idcurriculo and oca.idoferta = m.idoferta and oca.ativo = 'S')
                        WHERE
                            m.idmatricula = {$idmatricula}
                        GROUP BY
                            d.iddisciplina ";
        $this->ordem = "asc";
        $this->ordem_campo = "cb.ordem, cbd.ordem, d.nome";
        $this->limite = - 1;
        
        return $this->retornarLinhas();
    }

    function retornarEscolas()
    {
        $this->sql = "SELECT idescola, nome_fantasia 
                  FROM escolas 
                  WHERE ativo = 'S'";
        if ($this->id)
            $this->sql .= " AND idsindicato = '" . $this->id . "'";
        $this->sql .= ' ORDER BY nome_fantasia ';
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while ($row = mysql_fetch_assoc($query)) {
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }

    function retornarCursosOferta()
    {
        $this->sql = "SELECT c.idcurso, c.nome
				  FROM cursos c
				  INNER JOIN ofertas_cursos oc 
                        on c.idcurso = oc.idcurso and oc.ativo = 'S'";
        if ($this->id)
            $this->sql .= " WHERE oc.idoferta = '" . $this->id . "' ";
        $this->sql .= ' GROUP BY c.idcurso ';
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while ($row = mysql_fetch_assoc($query)) {
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }

    function retornarTurmasOferta()
    {
        $this->sql = "SELECT tu.idturma, tu.nome
					FROM ofertas_turmas tu
					WHERE tu.ativo = 'S' and 
                          tu.idoferta = '" . $this->id . "'";
        $this->sql .= ' ORDER BY tu.nome ';
        $query = $this->executaSql($this->sql);
        $this->retorno = array();
        while ($row = mysql_fetch_assoc($query)) {
            $this->retorno[] = $row;
        }
        echo json_encode($this->retorno);
    }
}

?>
