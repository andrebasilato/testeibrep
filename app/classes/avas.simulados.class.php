<?php
class Simulados extends Ava {

    var $idava = null;

    function ListarTodasSimulado() {
        $this->sql = "select
                    ".$this->campos."
                  from
                    avas_simulados aa
                    inner join avas a on (aa.idava = a.idava)
                  where
                    aa.ativo = 'S' and
                    a.idava = ".$this->idava;

        $this->aplicarFiltrosBasicos();
        $this->set('groupby', 'aa.idsimulado');
        return $this->retornarLinhas();
    }

    function ListarTodasSimuladoExibidos() {
        $this->sql = "select
                        ".$this->campos."
                      from
                        avas_simulados aa
                        inner join avas a on (aa.idava = a.idava)
                      where
                        aa.exibir_ava = 'S' and
                        aa.ativo = 'S' and
                        a.idava = ".$this->idava;

        $this->aplicarFiltrosBasicos();
        $this->set('groupby', 'aa.idsimulado');
        return $this->retornarLinhas();
    }

    function RetornarSimulado() {
        $this->sql = "SELECT
                    ".$this->campos."
                  FROM
                    avas_simulados aa
                    INNER JOIN avas a ON aa.idava = a.idava
                  WHERE
                    aa.ativo = 'S' AND
                    aa.idsimulado = '".$this->id."' AND
                    a.idava = ".$this->idava;
        return $this->retornarLinha($this->sql);
    }

    public function retornarDisciplinasPerguntas() {
        $this->sql = "SELECT
                    ".$this->campos."
                  FROM
                    avas_simulados_disciplinas avd
                  WHERE
                    avd.ativo = 'S' AND
                    avd.idsimulado = '".$this->id."'";
        return $this->retornarLinhas();
    }

    function CadastrarSimulado() {

        if (! count($this->post['iddisciplina_perguntas'])) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_disciplinas_perguntas_vazio';
            return $this->retorno;
        }

        $disciplinas = implode(',', $this->post['iddisciplina_perguntas']);

        $this->sql = "SELECT
                        count(idpergunta) as total
                    FROM
                        perguntas
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        iddisciplina IN(".$disciplinas.") AND
                        simulado = 'S' and tipo = 'O' AND
                        dificuldade = 'F' ";
        $objetivas_faceis = $this->retornarLinha($this->sql);

        if($objetivas_faceis['total'] < $this->post["objetivas_faceis"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_faceis_insuficientes';
        }

        $this->sql = "SELECT
                    count(idpergunta) AS total
                FROM
                    perguntas
                WHERE
                    ativo = 'S' AND
                    ativo_painel = 'S' AND
                    iddisciplina IN(".$disciplinas.") AND
                    simulado = 'S' AND
                    tipo = 'O' AND
                    dificuldade = 'M' ";
        $objetivas_medias = $this->retornarLinha($this->sql);

        if($objetivas_medias['total'] < $this->post["objetivas_intermediarias"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_medias_insuficientes';
        }

        $this->sql = "SELECT
                        count(idpergunta) AS total
                    FROM
                        perguntas
                    WHERE
                        ativo = 'S' AND
                        ativo_painel = 'S' AND
                        iddisciplina IN(".$disciplinas.") AND
                        simulado = 'S' AND
                        tipo = 'O' AND
                        dificuldade = 'D' ";
        $objetivas_dificeis = $this->retornarLinha($this->sql);

        if($objetivas_dificeis['total'] < $this->post["objetivas_dificeis"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_dificeis_insuficientes';
        }

        if ($this->retorno["erro"]) {
            return $this->retorno;
        }


        $salvar = $this->SalvarDados();
        if ($salvar['sucesso']) {
            foreach ($this->post['iddisciplina_perguntas'] as $iddisciplina) {

                $this->sql = "INSERT INTO
                                avas_simulados_disciplinas
                            SET
                                data_cad = NOW(),
                                ativo = 'S',
                                idsimulado = ".(int)$salvar['id'].",
                                iddisciplina = ".(int)$iddisciplina;

                if ($this->executaSql($this->sql)) {

                    $this->monitora_oque = 1;
                    $this->monitora_onde = '218';
                    $this->monitora_qual = mysql_insert_id();
                    $this->Monitora();

                } else {

                    $retorno["erro"] = true;
                    $retorno["erros"][] = $this->sql;
                    $retorno["erros"][] = mysql_error();
                }

            }
        }

        if (!$retorno["erro"]) {
            $retorno["sucesso"] = true;
            $retorno["id"] = $salvar['id'];
        }

        return $retorno;
    }

    function ModificarSimulado() {

        if (count($this->post['iddisciplina_perguntas']) == 0) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_disciplinas_perguntas_vazio';
            return $this->retorno;
        }

        $disciplinas = implode(',', $this->post['iddisciplina_perguntas']);

        $this->sql = "SELECT
                    count(idpergunta) as total
                FROM
                    perguntas
                WHERE
                    ativo = 'S' AND
                    ativo_painel = 'S' AND
                    iddisciplina IN(".$disciplinas.") AND
                    simulado = 'S' AND
                    tipo = 'O' AND
                    dificuldade = 'F' ";
        $objetivas_faceis = $this->retornarLinha($this->sql);

        if($objetivas_faceis['total'] < $this->post["objetivas_faceis"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_faceis_insuficientes';
        }

        $this->sql = "SELECT
                    count(idpergunta) AS total
                FROM
                    perguntas
                WHERE
                    ativo = 'S' AND
                    ativo_painel = 'S' AND
                    iddisciplina IN(".$disciplinas.") AND
                    simulado = 'S' AND
                    tipo = 'O' AND
                    dificuldade = 'M' ";
        $objetivas_medias = $this->retornarLinha($this->sql);

        if($objetivas_medias['total'] < $this->post["objetivas_intermediarias"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_medias_insuficientes';
        }

        $this->sql = "SELECT
                    count(idpergunta) AS total
                FROM
                    perguntas
                WHERE
                    ativo = 'S' AND
                    ativo_painel = 'S' AND
                    iddisciplina IN(".$disciplinas.") AND
                    simulado = 'S' AND
                    tipo = 'O' AND
                    dificuldade = 'D' ";
        $objetivas_dificeis = $this->retornarLinha($this->sql);

        if($objetivas_dificeis['total'] < $this->post["objetivas_dificeis"]){
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = 'erro_objetivas_dificeis_insuficientes';
        }

        if ($this->retorno["erro"]) {
            return $this->retorno;
        }

        $salvar = $this->SalvarDados();

        if (!$salvar['sucesso']) {
            return $salvar;
        }

        if ($salvar['sucesso']) {

            $this->sql = "UPDATE
                        avas_simulados_disciplinas
                    SET
                        ativo = 'N'
                    WHERE
                        idsimulado = ".(int)$salvar['id'];
            $this->executaSql($this->sql);

            $this->monitora_oque = 2;
            $this->monitora_onde = '218';
            foreach ($this->post['iddisciplina_perguntas'] as $iddisciplina) {

                $sql_verifica_existe = "SELECT 
                                        * 
                                    FROM 
                                        avas_simulados_disciplinas
                                    WHERE
                                        idsimulado = ".(int)$salvar['id']." AND
                                        iddisciplina = ".(int)$iddisciplina;
                $linha_existente = $this->retornarLinha($sql_verifica_existe);

                if ($linha_existente['idsimulado_disciplina']) {

                    $this->sql = "UPDATE
                                avas_simulados_disciplinas
                            SET
                                ativo = 'S'
                            WHERE
                                idsimulado = ".(int)$salvar['id']." AND
                                iddisciplina = ".(int)$iddisciplina;

                    if ($this->executaSql($this->sql)) {

                        $this->monitora_qual = mysql_insert_id();
                        $this->Monitora();

                    } else {

                        $retorno["erro"] = true;
                        $retorno["erros"][] = $this->sql;
                        $retorno["erros"][] = mysql_error();
                    }

                } else {

                    $this->sql = "INSERT INTO
                                avas_simulados_disciplinas
                            SET
                                data_cad = NOW(),
                                ativo = 'S',
                                idsimulado = ".(int)$salvar['id'].",
                                iddisciplina = ".(int)$iddisciplina;

                    if ($this->executaSql($this->sql)) {

                        $this->monitora_oque = 1;
                        $this->monitora_onde = '218';
                        $this->monitora_qual = mysql_insert_id();
                        $this->Monitora();

                    } else {

                        $retorno["erro"] = true;
                        $retorno["erros"][] = $this->sql;
                        $retorno["erros"][] = mysql_error();
                    }

                }
            }
        }
        if (!$retorno["erro"]) {
            $retorno["sucesso"] = true;
            $retorno["id"] = $salvar['id'];
        }
        return $retorno;
    }

    function RemoverSimulado() {
        return $this->RemoverDados();
    }

    function RemoverArquivo($modulo, $pasta, $dados, $idioma) {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

    public function retornarSimuladoProva($idsimulado) {
        $this->sql = "SELECT 
                        ".$this->campos."
                      FROM
                        avas_simulados aa
                      WHERE
                        aa.idsimulado = ".(int) $idsimulado;

        $simulado = $this->retornarLinha($this->sql);
        $arrayData = explode(':', $simulado['tempo']);
        $simulado['tempo_em_segundos'] = ($arrayData[2] + ($arrayData[1] * 60) + (($arrayData[0] * 60) * 60) );

        return $simulado;
    }

    public function gerarSimulado($idsimulado, $idmatricula)
    {
        if (verificaPermissaoAcesso(true)) {
            $this->sql = "SELECT * FROM avas_simulados WHERE idsimulado = '".(int) $idsimulado."'";
            $simulado = $this->retornarLinha($this->sql);

            $this->sql = 'SELECT iddisciplina FROM avas_simulados_disciplinas WHERE idsimulado = '.$simulado['idsimulado'].' and ativo = "S"';
            $this->limite = -1;
            $this->ordem = false;
            $this->ordem_campo = false;
            $disciplinas = $this->retornarLinhas();
            $arrayIdDisciplinas = array();

            foreach ($disciplinas as $disciplina) {
                $arrayIdDisciplinas[] = $disciplina['iddisciplina'];
            }
            $arrayIdDisciplinas = implode(',', $arrayIdDisciplinas);

            $perguntas = array();
            $perguntasObjFaceis = array();
            $perguntasObjIntermediarias = array();
            $perguntasObjDificeis = array();

            if ($simulado['objetivas_faceis'] > 0) {
                $perguntasObjFaceis = $this->retornarPerguntas($arrayIdDisciplinas, 'F', $simulado['objetivas_faceis']);
            }
            if ($simulado['objetivas_intermediarias'] > 0) {
                $perguntasObjIntermediarias = $this->retornarPerguntas($arrayIdDisciplinas, 'M', $simulado['objetivas_intermediarias']);
            }
            if ($simulado['objetivas_dificeis'] > 0) {
                $perguntasObjDificeis = $this->retornarPerguntas($arrayIdDisciplinas, 'D', $simulado['objetivas_dificeis']);
            }

            $perguntas = array_merge($perguntasObjFaceis, $perguntasObjIntermediarias, $perguntasObjDificeis);
            shuffle($perguntas);

            $this->executaSql("BEGIN");
            $this->sql = 'INSERT INTO 
                            matriculas_simulados 
                          SET 
                            inicio = NOW(), 
                            idsimulado = '.$simulado['idsimulado'].', 
                            idmatricula = '.(int) $idmatricula.', 
                            ativo = "S" ';
            $this->executaSql($this->sql);

            $idmatricula_simulado = mysql_insert_id();
            $this->id = $idmatricula_simulado;
            $this->monitora_oque = 10;
            $this->monitora_onde = 280;
            $this->monitora_qual = $this->id;
            $this->Monitora();

            if (count($perguntas) > 0){
                foreach ($perguntas as $pergunta) {
                    $this->sql = 'INSERT INTO 
                                      matriculas_simulados_perguntas 
                                  SET idmatricula_simulado = '.$idmatricula_simulado.', 
                                      idpergunta = '.$pergunta['idpergunta'];
                    $this->executaSql($this->sql);
                }
            }
            $this->executaSql("COMMIT");

            $simulado = $this->retornaMatriculaSimulado($idmatricula_simulado, $idmatricula);

            return $simulado;
        } else {
            $prova['erro_json'] = "sem_permissao";
            return $simulado;
        }
    }

    function retornarPerguntas($disciplinas, $dificudade, $quantidade) {

        $perguntas = array();

        if (($disciplinas != '') and ($dificudade != '') and ($quantidade != '')){

            $this->sql = 'SELECT
                            *
                        FROM
                            perguntas
                        WHERE
                            iddisciplina IN ('.$disciplinas.') AND
                            tipo = "O" AND
                            dificuldade = "'.$dificudade.'" AND
                            simulado = "S" AND
                            ativo_painel = "S" AND
                            ativo = "S"
                        ORDER BY RAND() LIMIT '.$quantidade;
            $this->limite = -1;
            $this->ordem = false;
            $this->ordem_campo = false;
            $perguntas = $this->retornarLinhas();

            foreach ($perguntas as $ind => $pergunta) {
                $this->sql = 'SELECT 
                            * 
                            FROM 
                                perguntas_opcoes 
                            WHERE 
                                idpergunta = '.$pergunta['idpergunta'].' AND 
                                ativo_painel = "S" AND
                                ativo = "S"';
                $this->limite = -1;
                $this->ordem = 'ASC';
                $this->ordem_campo = 'ordem';
                $opcoes = $this->retornarLinhas();
                $perguntas[$ind]['opcoes'] = $opcoes;
            }

        }

        return $perguntas;
    }

    function corrigirSimulado($idsimulado) {
        $this->sql = "SELECT * FROM avas_simulados WHERE idsimulado = '".(int) $idsimulado."'";
        $simulado = $this->retornarLinha($this->sql);

        $perguntas = implode(', ',$this->post['perguntas']);

        $this->sql = 'SELECT
                        *
                    FROM
                        perguntas
                    WHERE
                        idpergunta IN ('.$perguntas.')';
        $this->limite = -1;
        $this->ordem = false;
        $this->ordem_campo = false;
        $perguntas = $this->retornarLinhas();
        foreach ($perguntas as $ind => $pergunta) {
            $this->sql = 'SELECT 
                        * 
                        FROM 
                            perguntas_opcoes 
                        WHERE 
                            idpergunta = '.$pergunta['idpergunta'].' AND 
                            ativo_painel = "S" AND
                            ativo = "S"';
            $this->limite = -1;
            $this->ordem = 'ASC';
            $this->ordem_campo = 'ordem';
            $opcoes = $this->retornarLinhas();
            $perguntas[$ind]['opcoes'] = $opcoes;
        }

        $simulado['perguntas'] = $perguntas;

        return $simulado;
    }

    /**
     * @return array
     */
    public function salvaRespostasSimulado($idmatricula_simulado, $idmatricula)
    {
        if (!verificaPermissaoAcesso(true)) {
            return false;
        }

        $this->executaSql('BEGIN');

        /** Salvando Respostas objetivas única escolha */
        foreach ($this->post['opcoes_unica'] as $ind => $objetiva_unica) {
            $this->sql = "INSERT INTO 
                              matriculas_simulados_perguntas_opcoes_marcadas
                          SET 
                              idmatricula_simulado_pergunta = '".$ind."',
                              idopcao = '".$objetiva_unica."'";

            $this->executaSql($this->sql);
        }

        /** Salvando Respostas objetivas múltipla escolha */
        foreach ($this->post['opcoes_multipla'] as $ind => $objetiva_multiplas) {
            foreach ($objetiva_multiplas as $chave => $opcao) {
                $this->sql = "INSERT INTO
                                  matriculas_simulados_perguntas_opcoes_marcadas 
                              SET
                                  idmatricula_simulado_pergunta = '".$ind."',
                                  idopcao = '".$opcao."'";
                $this->executaSql($this->sql);
            }
        }

        $simulado = $this->retornaMatriculaSimulado($idmatricula_simulado, $idmatricula);
        $corretas = 0;
        $total_perguntas = count($simulado["perguntas"]);
        foreach ($simulado["perguntas"] as $key => $value) {
            foreach ($value["opcoes"] as $opcao) {
                if ($opcao["correta"] == "S" && $opcao["marcada"] == "S") {
                    $corretas++;
                }
            }
        }

        $this->sql = "UPDATE 
                        matriculas_simulados 
                      SET
                        fim = now(),
                        total_perguntas = $total_perguntas,
                        total_perguntas_corretas = $corretas
                      WHERE idmatricula_simulado = '".$this->id."'";

        if ($this->executaSql($this->sql)) {
            $this->monitora_oque = 4;
            $this->monitora_onde = 280;
            $this->monitora_qual = $this->id;
            $this->Monitora();

            $this->executaSql('COMMIT');

            $this->retorno["sucesso"] = true;
            $this->retorno["mensagem"] = "mensagem_simulado_responder_sucesso";

        } else {
            $this->retorno["sucesso"] = false;
            $this->retorno["mensagem"] = "mensagem_simulado_responder_erro";
        }

        return $this->retorno;
    }

    /**
     * @param $idavaliacao
     * @return array
     */
    public function retornaDadosSimulado($idsimulado)
    {
        $this->sql = "SELECT 
                        ".$this->campos."
                      FROM
                        avas_simulados aas
                      WHERE
                        aas.idsimulado = ".(int) $idsimulado;

        $simulado = $this->retornarLinha($this->sql);
        $arrayData = explode(':', $simulado['tempo']);
        $simulado['tempo_em_segundos'] = ($arrayData[2] + ($arrayData[1] * 60) + (($arrayData[0] * 60) * 60) );

        return $simulado;
    }

    public function retornaTentativas($idsimulado, $idmatricula, $idava, $porPerguntas = true)
    {
        $this->sql = 'SELECT 
                        masl.*, 
                        aas.idsimulado, 
                        aas.nome as simulado,
                        aas.periode_de, 
                        aas.periode_ate,
                        aas.imagem_exibicao_servidor
                    FROM 
                        avas_simulados aas
                        INNER JOIN matriculas_simulados masl ON (masl.idsimulado = aas.idsimulado)
                    WHERE 
                        masl.idsimulado = '.(int) $idsimulado.' AND
                        masl.idmatricula = '.(int) $idmatricula.' AND
                        aas.idava = '.(int) $idava.' AND 
                        aas.ativo = "S" AND
                        masl.ativo = "S"';
        $this->ordem = "DESC";
        $this->ordem_campo = "masl.idmatricula_simulado";
        $this->limite = -1;
        $tentativas = $this->retornarLinhas();

        if ($porPerguntas) {
            $tentativas = $this->retornarTentativasPorPergunta($idmatricula, $tentativas);
        }

        return $tentativas;
    }

    public function retornarTentativasPorPergunta($idmatricula, $tentativas)
    {
        foreach ($tentativas as $ind => $tentativa) {
            $simulado = $this->retornaMatriculaSimulado($tentativa["idmatricula_simulado"], $idmatricula);
            $corretas = 0;
            $tentativas[$ind]["total_perguntas"] = count($simulado["perguntas"]);
            foreach ($simulado["perguntas"] as $key => $value) {
                foreach ($value["opcoes"] as $opcao) {
                    if ($opcao["correta"] == "S" && $opcao["marcada"] == "S") {
                        $corretas++;
                    }
                }
            }
            $tentativas[$ind]["total_perguntas_corretas"] = $corretas;
        }
        return $tentativas;
    }

    public function retornaMatriculaSimulado($idmatricula_simulado, $idmatricula)
    {
        $this->sql = 'SELECT 
                        * 
                      FROM 
                           matriculas_simulados 
                      WHERE idmatricula_simulado = '.$idmatricula_simulado.' 
                      AND idmatricula = '.$idmatricula;

        $prova = $this->retornarLinha($this->sql);
        $prova['perguntas'] = $this->retornaPerguntasMatriculaSimulado($idmatricula_simulado);

        return $prova;
    }

    public function retornaPerguntasMatriculaSimulado($idmatricula_simulado)
    {
        $this->sql = 'SELECT
                        msp.*,
                        p.nome,
                        p.imagem_servidor,
                        p.tipo,
                        p.permite_anexo_resposta,
                        p.multipla_escolha,
                        p.critica
                    FROM
                        matriculas_simulados_perguntas msp
                        INNER JOIN perguntas p ON (msp.idpergunta = p.idpergunta)
                    WHERE
                        msp.idmatricula_simulado = '.$idmatricula_simulado;
        $this->limite = -1;
        $this->ordem = 'ASC';
        $this->ordem_campo = 'msp.idmatricula_simulado_pergunta';
        $perguntas = $this->retornarLinhas();
        foreach ($perguntas as $ind => $pergunta) {
            if ($pergunta['tipo'] == 'O') {
                $this->sql = "SELECT 
                                po.*,
                                IF(mspom.idmatricula_simulado_pergunta_opcao_marcada IS NULL, 'N', 'S') as marcada
                            FROM 
                                perguntas_opcoes po
                                LEFT OUTER JOIN matriculas_simulados_perguntas_opcoes_marcadas mspom ON (mspom.idmatricula_simulado_pergunta = ".$pergunta['idmatricula_simulado_pergunta']." AND po.idopcao = mspom.idopcao)
                            WHERE 
                                po.idpergunta = ".$pergunta['idpergunta']." AND 
                                po.ativo = 'S'
                                GROUP BY po.idopcao";
                $this->limite = -1;
                $this->ordem = 'ASC';
                $this->ordem_campo = 'ordem';
                $opcoes = $this->retornarLinhas();
                $perguntas[$ind]['opcoes'] = $opcoes;
            }
        }
        return $perguntas;
    }
}